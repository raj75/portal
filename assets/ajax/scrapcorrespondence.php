<?php

ini_set("memory_limit", "-1");
//ignore_user_abort(true);
set_time_limit(0);

//require '../lib/awssdk/aws-autoloader.php';
require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Credentials\CredentialProvider;
use Aws\S3\Exception\S3Exception;

$username="support@vervantis.com";
$pass="RYK8dvJqkPzK";
$tablename='correspondence';

$profile = 'default';
//$path = '../lib/awssdk/credentials.ini';
$path = '../../lib/s3/credentials.ini';


$provider = CredentialProvider::ini($profile, $path);
$provider = CredentialProvider::memoize($provider);

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => $provider
]);

$mysqli = new mysqli("develop.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$stmtupdtall = $mysqli->prepare('UPDATE '.$tablename.' SET check_exists=1') ;
$stmtupdtall->execute();

class Email_message {

    public $connection;
    public $messageNumber;    
    public $bodyHTML = '';
    public $bodyPlain = '';
    public $attachments;
    public $getAttachments = true;

    public function __construct($config_data = array()) {

        $this->connection = $config_data['connection'];
        $this->messageNumber = isset($config_data['message_no'])?$config_data['message_no']:1;
    }

    public function fetch() {

        $structure = @imap_fetchstructure($this->connection, $this->messageNumber);
        if(!$structure) {
            return false;
        }
        else {
            if(isset($structure->parts)){
                $this->recurse($structure->parts);
            }
            return true;
        }

    }

    public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {

        foreach($messageParts as $part) {

            $partNumber = $prefix . $index;

            if($part->type == 0) {
                if($part->subtype == 'PLAIN') {
                    $this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
                }
                else {
                    $this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
                }
            }
            elseif($part->type == 2) {
                $msg = new Email_message(array('connection' =>$this->connection,'message_no'=>$this->messageNumber));
                $msg->getAttachments = $this->getAttachments;
                if(isset($part->parts)){
                    $msg->recurse($part->parts, $partNumber.'.', 0, false);
                }
                $this->attachments[] = array(
                    'type' => $part->type,
                    'subtype' => $part->subtype,
                    'filename' => '',
                    'data' => $msg,
                    'inline' => false,
                );
            }
            elseif(isset($part->parts)) {
                if($fullPrefix) {
                    $this->recurse($part->parts, $prefix.$index.'.');
                } else {
                    $this->recurse($part->parts, $prefix);
                }
            }
            elseif($part->type > 2) {
                if(isset($part->id)) {
                    $id = str_replace(array('<', '>'), '', $part->id);
                    $this->attachments[$id] = array(
                        'type' => $part->type,
                        'subtype' => $part->subtype,
                        'filename' => $this->getFilenameFromPart($part),
                        'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                        'inline' => true,
                    );
                } else {
                    $this->attachments[] = array(
                        'type' => $part->type,
                        'subtype' => $part->subtype,
                        'filename' => $this->getFilenameFromPart($part),
                        'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                        'inline' => false,
                    );
                }
            }

            $index++;

        }

    }

    function getPart($partNumber, $encoding) {

        $data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
        switch($encoding) {
            case 0: return $data; // 7BIT
            case 1: return $data; // 8BIT
            case 2: return $data; // BINARY
            case 3: return base64_decode($data); // BASE64
            case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5: return $data; // OTHER
        }


    }

    function getFilenameFromPart($part) {

        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }

    function get_mail_body($body_type = 'html',$foldertosave='',$s3Client="")
    {
		if($foldertosave=='' || $s3Client=='') return '';
        $mail_body = '';
        if($body_type == 'html'){
            $this->fetch();
            preg_match_all('/src="cid:(.*)"/Uims', $this->bodyHTML, $matches);
            if(count($matches)) {
                $search = array();
                $replace = array();
                foreach($matches[1] as $match) {
                    $unique_filename = time().mt_rand(9,99).mt_rand(20,80).".".strtolower($this->attachments[$match]['subtype']);
					$infotargets = $s3Client->doesObjectExist('datahub360-correspondence', $foldertosave.'/messages/'.$unique_filename);
					if($unique_filename != "" and !$infotargets)
					{
							$tmp_data="";
							if(!empty($this->attachments[$match]['data'])) $tmp_data=$this->attachments[$match]['data'];

							$resultupload = $s3Client->putObject([
								'Bucket' => 'datahub360-correspondence',
								'Key'    => $foldertosave.'/messages/'.$unique_filename,
								'Body' => $tmp_data		
							]);
					}
                    //file_put_contents("./attachments/$unique_filename", $this->attachments[$match]['data']);
                    $search[] = "src=\"cid:$match\"";
                    $replace[] = "src='aws::$unique_filename::aws'";
                }
                $this->bodyHTML = str_replace($search, $replace, $this->bodyHTML);
                $mail_body = $this->bodyHTML;
				if($mail_body=="") $mail_body = $this->get_mail_body("Plain",$foldertosave,$s3Client);
            }
        }else{
                $mail_body = $this->bodyPlain;
        }
        return $mail_body;
    }

}




/////////


if ($scstmt = $mysqli->prepare("SELECT company_id,email_folder_path FROM `company` where email_folder_path != '' Group by email_folder_path")) {
	$scstmt->execute();
	$scstmt->store_result();
	if ($scstmt->num_rows > 0) {
		$scstmt->bind_result($sc_cid,$sc_fpath);
		while($scstmt->fetch()){



			//$email_folder_path="INBOX/Correspondence";
			$email_folder_path=$sc_fpath;
			$mailbox = '{outlook.office365.com:993/imap/ssl}'.$email_folder_path;
			$username = 'operations@vervantis.com';
			$password = ')i2wV}i6,;/x';


			$imapResource = imap_open($mailbox, $username, $password);

			//If the imap_open function returns a boolean FALSE value,
			//then we failed to connect.
			if($imapResource === false){
				throw new Exception(imap_last_error());
			}


			$list = imap_list($imapResource, $mailbox, "*");

			if (is_array($list)) {

				 //loop through rach array index
				 foreach ($list as $val) {

					//remove  any } charactors from the folder
					if (preg_match("/}/i", $val)) {
						$arr = explode('}', $val);
					}

					//also remove the ] if it exists, normally Gmail have them
					if (preg_match("/]/i", $val)) {
						$arr = explode(']/', $val);
					}

					//remove any slashes
					$folder = trim(stripslashes($arr[1]));

					//remove inbox. from the folderName its not needed for displaying purposes
					$folderName = str_replace('INBOX.', '', $folder);
					get_mails(imap_utf7_decode($folder),$sc_cid);

				}

			} else {
				echo "Folders not currently availablen";
			}

			imap_close($imapResource);
			//die();
		}
	}
}
//die();


$stmtupdt = $mysqli->prepare('UPDATE correspondence SET status=1 WHERE check_exists=1') ;
$stmtupdt->execute();



function get_mails($fname="",$sc_cid){
	global $s3Client;
	global $mysqli;
	global $tablename;

	if($fname=="") return false;
	
	$unid=time();

	$mailbox = '{outlook.office365.com:993/imap/ssl}'.$fname;
	$username = 'operations@vervantis.com';
	$password = ')i2wV}i6,;/x';

	$foldertosave= @trim(@str_replace("INBOX/Correspondence/","",$fname),"/");
	if($foldertosave=="") return false;

	$imapResfolderlist = imap_open($mailbox, $username, $password);

	if($imapResfolderlist === false){
		throw new Exception(imap_last_error());
	}




	$search = 'SINCE "' . date("j F Y", strtotime("-965 days")) . '"';
	$emails = imap_search($imapResfolderlist, $search);

	if(!empty($emails)){
		//Loop through the emails.
		foreach($emails as $ky => $email){
			$uuid=imap_uid ( $imapResfolderlist , $email );
			$hdr=imap_headerinfo($imapResfolderlist, $email);
			//$overview = imap_fetch_overview($imapResource, $email);
			//$overview = $overview[0];
			//print_r($hdr);die();continue;

			$fromname=$subject=$fromemail="";
			$toemail=$ccemail=$ccname=$bccemail=$bccname=$toname=array();

			if(isset($hdr->subject)) $subject=htmlentities($hdr->subject);
			if(isset($hdr->from[0]->mailbox)) $fromemail=$hdr->from[0]->mailbox . "@" . $hdr->from[0]->host;
			//if(isset($hdr->to[0]->mailbox)) $toemail=$hdr->to[0]->mailbox . "@" . $hdr->to[0]->host;
			if(isset($hdr->from[0]->personal)) $fromname=$hdr->from[0]->personal;
			//if(isset($hdr->to[0]->personal)) $toname=$hdr->to[0]->personal;
			else $fromname=$fromemail;
			//$messageid=$hdr->message_id;

			if(isset($hdr->to)){
				foreach($hdr->to as $kkyy => $vvll){
					if(isset($vvll->mailbox)) $toemail[]=$vvll->mailbox . "@" . $vvll->host;
					if(isset($vvll->personal)) $toname[]=$vvll->personal;
				}
			}
			
			if(isset($hdr->cc)){
				foreach($hdr->cc as $kky => $vvl){
					if(isset($vvl->mailbox)) $ccemail[]=$vvl->mailbox . "@" . $vvl->host;
					if(isset($vvl->personal)) $ccname[]=$vvl->personal;
				}
			}
			
			if(isset($hdr->bcc)){
				foreach($hdr->bcc as $kky => $vvlll){
					if(isset($vvlll->mailbox)) $bccemail[]=$vvlll->mailbox . "@" . $vvlll->host;
					if(isset($vvlll->personal)) $bccname[]=$vvlll->personal;
				}
			}


			
			//$receivingdate=date("g:i a D, j M Y", strtotime($hdr->date));
			$receivingdate=date("Y-m-d H:i:s", strtotime($hdr->date));
			
			
		/*print_r($subject);echo "<br>";
		print_r($fromname);echo "<br>";
		print_r($toname);echo "<br>";
		print_r($fromemail);echo "<br>";
		print_r($toemail);echo "<br>";
		print_r($receivingdate);echo "<br>";
		print_r($mailbody);echo "<br>";
		print_r($afilename);echo "<br>";
		print_r($foldertosave);echo "<br>";
		print_r($uuid);
		print_r($unid);
		print_r($messageid);
		die();*/
		//print_r($afilename);echo "<br>";die();
		//die('SELECT id FROM correspondence where subject="'.$mysqli->real_escape_string($subject).'" and sender_email="'.$mysqli->real_escape_string($fromemail).'" and receiver_email="'.$mysqli->real_escape_string($toemail).'" and receiving_date="'.$mysqli->real_escape_string($receivingdate).'" and folderpath="'.$mysqli->real_escape_string($foldertosave).'" and message="'.$mysqli->real_escape_string($mailbody).'" LIMIT 1');


			$stmt = $mysqli->prepare('SELECT id FROM '.$tablename.' where company_id='.$sc_cid.' and subject="'.$mysqli->real_escape_string($subject).'" and from_email="'.$mysqli->real_escape_string($fromemail).'" and to_email="'.$mysqli->real_escape_string(@implode(",",$toemail)).'" and cc_email="'.$mysqli->real_escape_string(@implode(",",$ccemail)).'" and bcc_email="'.$mysqli->real_escape_string(@implode(",",$bccemail)).'" and receiving_date="'.$mysqli->real_escape_string($receivingdate).'" and folderpath="'.$mysqli->real_escape_string($foldertosave).'"  LIMIT 1');
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($corsid);
			$stmt->fetch();
			if ($stmt->num_rows == 0) {

				$config_data['connection']=$imapResfolderlist;
				$config_data['message_no']=$email;
				$email_message = new Email_message($config_data);
				$mailbody = $email_message->get_mail_body('html',$foldertosave,$s3Client);
				if($mailbody == "") { $mailbody =imap_body($imapResfolderlist, $email); }
 
				$structure = imap_fetchstructure($imapResfolderlist, $email);
		 
				$attachments = $afilename=array();	


				if(isset($structure->parts) && count($structure->parts)) 
				{
					for($i = 0; $i < count($structure->parts); $i++) 
					{
						$attachments[$i] = array(
							'is_attachment' => false,
							'filename' => '',
							'name' => '',
							'attachment' => ''
						);
		 
						if(isset($structure->parts[$i]->ifdparameters) and isset($structure->parts[$i]->disposition) and @strtolower($structure->parts[$i]->disposition)=="attachment") 
						{
							foreach($structure->parts[$i]->dparameters as $object) 
							{
								if(strtolower($object->attribute) == 'filename') 
								{
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
								}
							}
						}
		 
						if(isset($structure->parts[$i]->ifparameters) and isset($structure->parts[$i]->disposition) and @strtolower($structure->parts[$i]->disposition)=="attachment") 
						{
							foreach($structure->parts[$i]->parameters as $object) 
							{
								if(strtolower($object->attribute) == 'name') 
								{
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['name'] = $object->value;
								}
							}
						}
		 
						if($attachments[$i]['is_attachment']) 
						{
							$attachments[$i]['attachment'] = imap_fetchbody($imapResfolderlist, $email, $i+1);
		 
							/* 3 = BASE64 encoding */
							if($structure->parts[$i]->encoding == 3) 
							{ 
								$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
							}
							/* 4 = QUOTED-PRINTABLE encoding */
							elseif($structure->parts[$i]->encoding == 4) 
							{ 
								$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
							}
						}
					}
				}
		 
				/* iterate through each attachment and save it */
				foreach($attachments as $attachment)
				{
					if($attachment['is_attachment'] == 1)
					{
						$filename = $attachment['name'];
						if(empty($filename)) $filename = $attachment['filename'];
		 
						if(empty($filename)) $filename = time() . ".dat";
		 

						$rfile=$email.time().rand(100,500).rand(10,50). "-" . $filename;
						//$afilename[]=array("filename"=>$filename,"rfile"=>$rfile);
						$afilename[]=$filename.":".$rfile;
 
						$infotarget = $s3Client->doesObjectExist('datahub360-correspondence', $foldertosave.'/'.$rfile);
						if($rfile != "" and !$infotarget)
						{
								$tmps_data="";
								if(!empty($attachment['attachment'])) $tmps_data=$attachment['attachment'];

								$resultupload = $s3Client->putObject([
									'Bucket' => 'datahub360-correspondence',
									'Key'    => $foldertosave.'/'.$rfile,
									'Body' => $tmps_data		
								]);
						}
 
						//$fp = fopen($rfile, "w+");
						//fwrite($fp, $attachment['attachment']);
						//fclose($fp);
					}
		 
				}


				$stmtk = $mysqli->prepare("INSERT INTO ".$tablename." (company_id,subject,from_email,to_email,receiving_date,message,from_name,to_name,folderpath,attachments,cc_name,cc_email,bcc_name,bcc_email) VALUES ('".$sc_cid."','".$mysqli->real_escape_string($subject)."', '".$mysqli->real_escape_string($fromemail)."', '".$mysqli->real_escape_string(@implode(",",$toemail))."', '".$mysqli->real_escape_string($receivingdate)."', '".$mysqli->real_escape_string($mailbody)."', '".$mysqli->real_escape_string($fromname)."', '".$mysqli->real_escape_string(@implode(",",$toname))."', '".$mysqli->real_escape_string($foldertosave)."', '".$mysqli->real_escape_string(@implode("@@@",$afilename))."', '".$mysqli->real_escape_string(@implode(",",$ccname))."', '".$mysqli->real_escape_string(@implode(",",$ccemail))."', '".$mysqli->real_escape_string(@implode(",",$bccname))."', '".$mysqli->real_escape_string(@implode(",",$bccemail))."')") ;
				$stmtk->execute();
			}else{				
				$stmtupdt = $mysqli->prepare('UPDATE '.$tablename.' SET check_exists=0 WHERE id='.$corsid) ;
				$stmtupdt->execute();				
			}
			//die(); 
		}
	}

	imap_close($imapResfolderlist);
}
?>