<?php //require_once '../inc/init.php'; 
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';




if (!function_exists('base_url')) {
    function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://portal.vervantis.com/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }
}



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

    function get_mail_body($body_type = 'html')
    {
        $mail_body = '';
        if($body_type == 'html'){
            $this->fetch();
            preg_match_all('/src="cid:(.*)"/Uims', $this->bodyHTML, $matches);
            if(count($matches)) {
                $search = array();
                $replace = array();
                foreach($matches[1] as $match) {
                    $unique_filename = time().".".strtolower($this->attachments[$match]['subtype']);
                    file_put_contents("./attachments/$unique_filename", $this->attachments[$match]['data']);
                    $search[] = "src=\"cid:$match\"";
                    $replace[] = "src='".base_url()."/attachments/$unique_filename'";
                }
                $this->bodyHTML = str_replace($search, $replace, $this->bodyHTML);
                $mail_body = $this->bodyHTML;
            }
        }else{
                $mail_body = $this->bodyPlain;
        }
        return $mail_body;
    }

}




/////////






$email_folder_path="INBOX/Correspondence";
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
		get_mails(imap_utf7_decode($folder));

    }

} else {
    echo "Folders not currently availablen";
}



function get_mails($fname=""){
	if($fname=="") return false;

	$mailbox = '{outlook.office365.com:993/imap/ssl}'.$fname;
	$username = 'operations@vervantis.com';
	$password = ')i2wV}i6,;/x';


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
			//print_r($hdr);continue;die();
			
			$subject=htmlentities($hdr->subject);
			$fromname=$hdr->from[0]->personal;	
			$toname=$hdr->to[0]->personal;
			$fromemail=$hdr->from[0]->mailbox . "@" . $hdr->from[0]->host;
			$toemail=$hdr->to[0]->mailbox . "@" . $hdr->to[0]->host;
			
			$receivingdate=date("g:i a D, j M Y", strtotime($hdr->date));



			$config_data['connection']=$imapResfolderlist;
			$config_data['message_no']=$email;
			$email_message = new Email_message($config_data);
			$mailbody = $email_message->get_mail_body();
print_r($subject);echo "<br>";
print_r($fromname);echo "<br>";
print_r($toname);echo "<br>";
print_r($fromemail);echo "<br>";
print_r($toemail);echo "<br>";
print_r($receivingdate);echo "<br>";
print_r($mailbody);echo "<br>";
die();

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
	 
					if($structure->parts[$i]->ifdparameters) 
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
	 
					if($structure->parts[$i]->ifparameters) 
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
						$attachments[$i]['attachment'] = imap_fetchbody($imapResource, $email, $i+1);
	 
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
		 

						$rfile=$email.time().rand(100,500). "-" . $filename;
						$afilename[]=array("filename"=>$filename,"rfile"=>$rfile);

						//$fp = fopen($rfile, "w+");
						//fwrite($fp, $attachment['attachment']);
						//fclose($fp);
					}
		 
				}
	imap_close($imapResfolderlist);	
}


imap_close($imapResource);
?>