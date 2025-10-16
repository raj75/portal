<?php //require_once '../inc/init.php'; 
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
sec_session_start();



/*if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");
	
if(!$_SESSION['user_id'])
	die("Restricted Access");
	
$user_one=$_SESSION['user_id'];

if ($elstmt = $mysqli->prepare('SELECT c.email_folder_path FROM `company` c, user up where up.company_id=c.company_id and up.user_id='.$user_one.' LIMIT 1')) { 

//('SELECT c.email_folder_path FROM `company` c, userprofile up where up.company_id=c.id and up.user_id='.$user_one.' LIMIT 1')) {


	$elstmt->execute();
	$elstmt->store_result();
	if ($elstmt->num_rows > 0) {
			$elstmt->bind_result($email_folder_path);
			$elstmt->fetch();
			if($email_folder_path=="") die("Nothing to show!");
	}else die("Nothing to show!");
}else die("Error occured. Please try after sometime!");



$usermid="Aarons";

if(isset($_GET["uid"]) and $_GET["uid"] != "" and isset($_GET["fname"]) and $_GET["fname"] != ""){
	$uid=$_GET["uid"];
	$fname=$_GET["fname"];
}else die("Error Occured. Please try after sometime!");


if(!preg_match("/(".str_replace('/', '\/',$email_folder_path).")/s",$fname,$nosave))
	die("Error Occured. Please try after sometime!");
else $email_folder_path=$fname;


*/
/////////
$email_folder_path='INBOX/CAPTURIS EXCEPTIONS/PENDING';

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







$mailbox = '{outlook.office365.com:993/imap/ssl}'.$email_folder_path;
$username = 'operations@vervantis.com';
$password = ')i2wV}i6,;/x';


$imapResource = imap_open($mailbox, $username, $password);

//If the imap_open function returns a boolean FALSE value,
//then we failed to connect.
if($imapResource === false){
    throw new Exception(imap_last_error());
}


//$list = imap_list($imapResource, '{outlook.office365.com:993/imap/ssl}'.$email_folder_path, "*");
//print_r($list);
//die();

//Lets get all emails that were received since a given date.
$search = 'SINCE "' . date("j F Y", strtotime("-965 days")) . '"';
$emails = imap_search($imapResource, $search);
//$emails = imap_search($imapResource, 'ALL'); 




//If the $emails variable is not a boolean FALSE value or
//an empty array.
if(!empty($emails)){
    //Loop through the emails.
    foreach($emails as $ky => $email){
		$uuid=imap_uid ( $imapResource , $email );
			$hdr=imap_headerinfo($imapResource, $email);
		if($hdr->subject == "Variance Exception resolved" or $hdr->subject == "Variance Exceptions resolved"){
			
			$cusno=$cusdis=$resol="";
			$vendor_arr=$vendorname_arr=$acc_arr=$docid_arr=$service_arr=$site_arr=$sitename_arr=$sitestate_arr=$error_arr=$errormsg_arr=$duedate_arr=$checkdate_arr=$checkamt_arr=$check_arr=array();
			$ddate=date("Y-m-d H:i:s", strtotime($hdr->date));
			$ndate = date("Y-m-d H:i:s", strtotime('-5 hours', strtotime($hdr->date)));
			
			$mbody=imap_fetchbody($imapResource, $hdr->Msgno, 1);
			//$overview = imap_fetch_overview($imapResource, $email);
			//$overview = $overview[0];
			
			if(preg_match_all('/Customer #:[ ]*+([0-9]+)/s',$mbody,$cusno_arr)){
				array_shift($cusno_arr);
				if(count($cusno_arr[0]) == 1){	
					
					$cusno=$cusno_arr[0][0];

					if(preg_match('/Customer Description:[ ]*([^\n]+)/s',$mbody,$cusdis_arr)){
						array_shift($cusdis_arr);
						$cusdis=$cusdis_arr[0];
					}
					
					if(preg_match('/Resolution:[ ]*([^\n]+)/s',$mbody,$resol_arr)){
						array_shift($resol_arr);
						$resol=$resol_arr[0];
					}
					
					if(preg_match_all('/Vendor #:[ ]*([^\n]+)/s',$mbody,$vendor_arr)){
						array_shift($vendor_arr);
						$vendor_arr=$vendor_arr[0];
					}

					if(preg_match_all('/Vendor Name:[ ]*([^\n]+)/s',$mbody,$vendorname_arr)){
						array_shift($vendorname_arr);
						$vendorname_arr=$vendorname_arr[0];
					}
					
					if(preg_match_all('/Account #:[ ]*([^\n]+)/s',$mbody,$acc_arr)){
						array_shift($acc_arr);
						$acc_arr=$acc_arr[0];
					}
					
					if(preg_match_all('/DocID:[ ]*([^\n]+)/s',$mbody,$docid_arr)){
						array_shift($docid_arr);
						$docid_arr=$docid_arr[0];
					}
					
					if(preg_match_all('/Service:[ ]*([^\n]+)/s',$mbody,$service_arr)){
						array_shift($service_arr);
						$service_arr=$service_arr[0];
					}
					
					if(preg_match_all('/Site #:[ ]*([^\n]+)/s',$mbody,$site_arr)){
						array_shift($site_arr);
						$site_arr=$site_arr[0];
					}
					
					if(preg_match_all('/Site Name:[ ]*([^\n]+)/s',$mbody,$sitename_arr)){
						array_shift($sitename_arr);
						$sitename_arr=$sitename_arr[0];
					}
					
					if(preg_match_all('/Site State:[ ]*([^\n]+)/s',$mbody,$sitestate_arr)){
						array_shift($sitestate_arr);
						$sitestate_arr=$sitestate_arr[0];
					}
					
					if(preg_match_all('/Check #:[ ]*([^\n]+)/s',$mbody,$check_arr)){
						array_shift($check_arr);
						$check_arr=$check_arr[0];
					}
					
					if(preg_match_all('/Check Amt:[ ]*([^\n]+)/s',$mbody,$checkamt_arr)){
						array_shift($checkamt_arr);
						$checkamt_arr=$checkamt_arr[0];
					}
					
					if(preg_match_all('/Check Date:[ ]*([^\n]+)/s',$mbody,$checkdate_arr)){
						array_shift($checkdate_arr);
						$checkdate_arr=$checkdate_arr[0];
					}
					
					if(preg_match_all('/Due Date:[ ]*([^\n]+)/s',$mbody,$duedate_arr)){
						array_shift($duedate_arr);
						$duedate_arr=$duedate_arr[0];
					}
					
					if(preg_match_all('/Error #:[ ]*([^\n]+)/s',$mbody,$error_arr)){
						array_shift($error_arr);
						$error_arr=$error_arr[0];
					}
					
					if(preg_match_all('/Error Message:[ ]*([^\n]+)/s',$mbody,$errormsg_arr)){
						array_shift($errormsg_arr);
						$errormsg_arr=$errormsg_arr[0];
					}
					
					if(count($vendorname_arr) > 0){
						foreach($vendorname_arr as $kys=>$vls){
							$mysqli->query("INSERT INTO exceptions SET UBM='Capturis',`Customer #`='".$mysqli->real_escape_string($cusno)."',`Customer Description`='".$mysqli->real_escape_string($cusdis)."',`Resolution`='".$mysqli->real_escape_string($resol)."',`Vendor #`='".$mysqli->real_escape_string($vendor_arr[$kys])."',`Vendor Name`='".$mysqli->real_escape_string($vls)."',`Account #`='".$mysqli->real_escape_string($acc_arr[$kys])."',`Due Date`='".$mysqli->real_escape_string($duedate_arr[$kys])."',`Check Date`='".$mysqli->real_escape_string($checkdate_arr[$kys])."',`Check Amt`='".$mysqli->real_escape_string($checkamt_arr[$kys])."',`Check #`='".$mysqli->real_escape_string($check_arr[$kys])."',`DocID`='".$mysqli->real_escape_string($docid_arr[$kys])."',`Service`='".$mysqli->real_escape_string($service_arr[$kys])."',`Site #`='".$mysqli->real_escape_string($site_arr[$kys])."',`Site Name`='".$mysqli->real_escape_string($sitename_arr[$kys])."',`Site State`='".$mysqli->real_escape_string($sitestate_arr[$kys])."',`Error #`='".$mysqli->real_escape_string($error_arr[$kys])."',`Error Message`='".$mysqli->real_escape_string($errormsg_arr[$kys])."',`Resolved Date`='".$mysqli->real_escape_string($ddate)."',`EST Date`='".$mysqli->real_escape_string($ndate)."'");
						
						}
						continue;
					}
				}
			}
			//Move to error folder
			continue;
		}
		//die();
	}
	
//  reset($msg_no); 
//  $messageset = implode (",",$msg_no); 
//  imap_mail_move($mbox,$messageset,$newmbox_name); 
//  imap_expunge($mbox);	
}
?>