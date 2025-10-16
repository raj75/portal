<?php
//header("Content-Type: text/html; charset=ISO-8859-1");
//header("Content-Type: text/html; charset=utf-8");
require '../../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

require_once '../../inc/init.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
sec_session_start();

//htmlspecialchars_decode
function replaceAccents($str)
{
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í',
               'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü',
               'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë',
               'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û',
               'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ',
               'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę',
               'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ',
               'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ',
               'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń',
               'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ',
               'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š',
               'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů',
               'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž',
               'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ',
               'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ',
               'ǿ', '€', '™', '˜');
    $b = array('');
    return str_replace($a, $b, $str);
}

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!$_SESSION['user_id'])
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];
/*
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){
	if ($elstmt = $mysqli->prepare('SELECT c.email_folder_path FROM `company` c WHERE c.company_id='.$cname.' LIMIT 1')) {

	//('SELECT c.email_folder_path FROM `company` c, user up where up.company_id=c.id and up.id='.$user_one.' LIMIT 1')) {

		$elstmt->execute();
		$elstmt->store_result();
		if ($elstmt->num_rows > 0) {
				$elstmt->bind_result($email_folder_path);
				$elstmt->fetch();
				if($email_folder_path=="") die("Nothing to show!");
		}else die("Nothing to show!");
	}else die("Error occured. Please try after sometime!");
}
*/

//$usermid="Aarons";
$mysqli->set_charset('utf8mb4');
if(isset($_GET["uid"]) and $_GET["uid"] != "" and isset($_GET["fname"]) and $_GET["fname"] != ""){
	//$uid=@preg_replace('/[^0-9]/', '', $_GET["uid"]);
	//$fname=@preg_replace('/[^\\-a-zA-Z0-9_\@\s]/', '', $_GET["fname"]);

	$uid= $mysqli->real_escape_string(@trim($_GET["uid"]));
	$fname="&folderid=".$mysqli->real_escape_string(@trim($_GET["fname"]));
}else die("Error Occured. Please try after sometime!");

if(isset($_GET["section"]) and @trim($_GET["section"]) != ""){
	$section= $_GET["section"];
}

$randname=chr(rand(65,90)).rand(10,100).time();

//if(!preg_match("/(".str_replace('/', '\/',$email_folder_path).")/s",$fname,$nosave))
	//die("Error Occured. Please try after sometime!");
//else $email_folder_path=$fname;
/////////


if (!function_exists('base_url') and 1==2) {
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

/*

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
*/

$profile = 'default';

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
  'credentials' => [
       'key' => $_ENV['aws_access_key_id'],
       'secret' => $_ENV['aws_secret_access_key']
   ]
]);



/*
function get_mail_message($message="",$s3Client,$foldertosave)
{
	if($message == "" || $foldertosave == "") return "";

	$foldertosave=str_ireplace("INBOX/Correspondence/","",$foldertosave);

	if(preg_match_all('/aws\:\:([^\:]+)\:\:aws/s', $message, $matches)){
		$search = array();
		$replace = array();
		foreach($matches[1] as $kys => $match) {

			$search[] = $matches[0][$kys];
			$infotarget = $s3Client->doesObjectExist('datahub360-correspondence', $foldertosave.'/messages/'.$match);
			if($match != "" and $infotarget)
			{
				$cmd = $s3Client->getCommand('GetObject', [
					'Bucket' => 'datahub360-correspondence',
					'Key'    => $foldertosave.'/messages/'.$match
				]);

				$request = $s3Client->createPresignedRequest($cmd, '+7 minutes');
				$replace[] = (string) $request->getUri();
			}else{
				$replace[] = "";
			}
		}
		$message = str_replace($search, $replace, $message);
	}

	if(!preg_match("/<html/s",$message,$nosave)) $message ="<pre class='nobg'>".$message."</pre>";

	return $message;
}

*/


/////////


$cont_sendername=$crs_senderemail=$cont_subject=$cont_message=$cont_date="";
$cont_attachments=array();

$ssubsql="";

if ($contstmt = $mysqli->prepare("SELECT e.id,e.`sender`,e.subject,e.contentType,e.content,e.sentDateTime,e.attachments,e.`toRecipients`,e.external_attachments FROM payment_notifications_sync.`emails` e where e.id='".$uid."' ".$ssubsql." LIMIT 1")) {
	$contstmt->execute();
	$contstmt->store_result();
	if ($contstmt->num_rows > 0) {
		$contstmt->bind_result($cont_uid,$crs_senderemail,$cont_subject,$contentType,$cont_message,$cont_date,$cont_attachments,$cont_email,$cont_extattach);
		$contstmt->fetch();
    $cont_sendername=$crs_senderemail;
		$cont_message=replaceAccents($cont_message);
		$cont_subject=replaceAccents($cont_subject);
	}
}

//If the $emails variable is not a boolean FALSE value or
//an empty array.
if($crs_senderemail != ""){
?>
<h2 class="email-open-header">
	<?php echo @str_replace('&ldquo;',"-",htmlentities($cont_subject));?>
</h2>

<div class="inbox-info-bar">
	<div class="row">
		<div class="col-sm-12">
			<strong><?php echo $cont_sendername; ?></strong>
			<span class="hidden-mobile">&lt;<?php echo $crs_senderemail; ?>&gt;to <strong>me</strong> on <i><?php echo date("g:i a D, j M Y", strtotime($cont_date)); ?></i></span>
		</div>
	</div>
</div>
<style>
.pointer {cursor: pointer;}
.inbox-download .data.animated {
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}
.inbox-download .data {
    z-index: -3;
}
.inbox-download ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
	padding-left:0;
}

/* Chrome, Safari, Opera */
.inbox-download @-webkit-keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

/* Standard syntax */
.inbox-download @keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

.inbox-download .data li {
    border-radius: 3px;
    /*background-color: #373743;*/
    background-color: #fff;
  /*  width: 307px;*/
    height: 118px;
    list-style-type: none;
    /*margin: 10px;*/
    display: inline-block;
    position: relative;
    overflow: hidden;
    padding: 0.3em;
    z-index: 1;
    cursor: pointer;
    box-sizing: border-box;
    transition: 0.3s background-color;
	width: 182px !important;
	border-radius: 1px;
	margin: 1px;
}

.inbox-download li {
    display: list-item;
    text-align: -webkit-match-parent;
}

.inbox-download .data li a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.inbox-download a:-webkit-any-link {
    color: -webkit-link;
    cursor: pointer;
    text-decoration: underline;
}

.inbox-download .icon.file.f-doc, .icon.file.f-docx, .icon.file.f-psd {
    box-shadow: 1.74em -2.1em 0 0 #03689b inset;
}

.inbox-download .icon.file {
    width: 2.5em;
    height: 3em;
    line-height: 3em;
    text-align: center;
    border-radius: 0.25em;
    color: #fff;
    display: inline-block;
    margin: 0.9em 1.2em 0.8em 1.3em;
    margin: 0.9em 0.4em 0.8em 1.3em;
    position: relative;
    overflow: hidden;
    box-shadow: 1.74em -2.1em 0 0 #A4A7AC inset;
}

.inbox-download .icon.file:first-line {
	font-size: 13px;
	font-weight: 700;
}
.inbox-download .icon.file:after {
	content: '';
	position: absolute;
	z-index: -1;
	border-width: 0;
	border-bottom: 2.6em solid #DADDE1;
	border-right: 2.22em solid rgba(0, 0, 0, 0);
	top: -34.5px;
	right: -4px;
}
.inbox-download .icon {
    font-size: 23px;
}

.inbox-download .data li .name{
    line-height: 20px;
    width: 170px;
    position: absolute;
    top: 40px;
}

.inbox-download .data li .fname{
    color: #333;
    font-size: 15px;
    font-weight: 700;
	white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
	max-width: 150px;
	width:auto;
    display: inline-block;
	float:left;
}

.inbox-download .data li .details {
    width: 55px;
    height: 10px;
    top: 60px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.inbox-download .data li .details .fsize{
    color: #b6c1c9;
    font-size: 11px;
    font-weight: 400;
    width: auto;
    white-space: nowrap;
}

.inbox-download .data li .ftime {
    color: #b6c1c9;
    font-size: 11px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 77px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.inbox-download .icon.file:after {
    content: '';
    position: absolute;
    z-index: -1;
    border-width: 0;
    border-bottom: 2.6em solid #DADDE1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    top: -34.5px;
    right: -4px;
}
.inbox-download{
	font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px;
	color:#333;
}

.inbox-download .icon.file.f-avi,
.inbox-download .icon.file.f-flv,
.inbox-download .icon.file.f-mkv,
.inbox-download .icon.file.f-mov,
.inbox-download .icon.file.f-mpeg,
.inbox-download .icon.file.f-mpg,
.inbox-download .icon.file.f-mp4,
.inbox-download .icon.file.f-m4v,
.inbox-download .icon.file.f-wmv {
	box-shadow: 1.74em -2.1em 0 0 #7e70ee inset;
}
.inbox-download .icon.file.f-avi:after,
.inbox-download .icon.file.f-flv:after,
.inbox-download .icon.file.f-mkv:after,
.inbox-download .icon.file.f-mov:after,
.inbox-download .icon.file.f-mpeg:after,
.inbox-download .icon.file.f-mpg:after,
.inbox-download .icon.file.f-mp4:after,
.inbox-download .icon.file.f-m4v:after,
.inbox-download .icon.file.f-wmv:after {
	border-bottom-color: #5649c1;
}

.inbox-download .icon.file.f-mp2,
.inbox-download .icon.file.f-mp3,
.inbox-download .icon.file.f-m3u,
.inbox-download .icon.file.f-wma,
.inbox-download .icon.file.f-xls,
.inbox-download .icon.file.f-xlsb,
.inbox-download .icon.file.f-xlsx {
	box-shadow: 1.74em -2.1em 0 0 #5bab6e inset;
}
.inbox-download .icon.file.f-mp2:after,
.inbox-download .icon.file.f-mp3:after,
.inbox-download .icon.file.f-m3u:after,
.inbox-download .icon.file.f-wma:after,
.inbox-download .icon.file.f-xls:after,
.inbox-download .icon.file.f-xlsx:after {
	border-bottom-color: #448353;
}

.inbox-download .icon.file.f-doc,
.inbox-download .icon.file.f-docx,
.inbox-download .icon.file.f-psd{
	box-shadow: 1.74em -2.1em 0 0 #03689b inset;
}

.inbox-download .icon.file.f-doc:after,
.inbox-download .icon.file.f-docx:after,
.inbox-download .icon.file.f-psd:after {
	border-bottom-color: #2980b9;
}

.inbox-download .icon.file.f-gif,
.inbox-download .icon.file.f-jpg,
.inbox-download .icon.file.f-jpeg,
.inbox-download .icon.file.f-pdf,
.inbox-download .icon.file.f-png {
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset;
}
.inbox-download .icon.file.f-gif:after,
.inbox-download .icon.file.f-jpg:after,
.inbox-download .icon.file.f-jpeg:after,
.inbox-download .icon.file.f-pdf:after,
.inbox-download .icon.file.f-png:after {
	border-bottom-color: #c6393f;
}

.inbox-download .icon.file.f-deb,
.inbox-download .icon.file.f-dmg,
.inbox-download .icon.file.f-gz,
.inbox-download .icon.file.f-rar,
.inbox-download .icon.file.f-zip,
.inbox-download .icon.file.f-7z {
	box-shadow: 1.74em -2.1em 0 0 #867c75 inset;
}
.inbox-download .icon.file.f-deb:after,
.inbox-download .icon.file.f-dmg:after,
.inbox-download .icon.file.f-gz:after,
.inbox-download .icon.file.f-rar:after,
.inbox-download .icon.file.f-zip:after,
.inbox-download .icon.file.f-7z:after {
	border-bottom-color: #685f58;
}

.inbox-download .icon.file.f-html,
.inbox-download .icon.file.f-rtf,
.inbox-download .icon.file.f-xml,
.inbox-download .icon.file.f-xhtml {
	box-shadow: 1.74em -2.1em 0 0 #a94bb7 inset;
}
.inbox-download .icon.file.f-html:after,
.inbox-download .icon.file.f-rtf:after,
.inbox-download .icon.file.f-xml:after,
.inbox-download .icon.file.f-xhtml:after {
	border-bottom-color: #d65de8;
}

.inbox-download .icon.file.f-js {
	box-shadow: 1.74em -2.1em 0 0 #d0c54d inset;
}
.inbox-download .icon.file.f-js:after {
	border-bottom-color: #a69f4e;
}

.inbox-download .icon.file.f-css,
.inbox-download .icon.file.f-saas,
.inbox-download .icon.file.f-scss {
	box-shadow: 1.74em -2.1em 0 0 #44afa6 inset;
}
.inbox-download .icon.file.f-css:after,
.inbox-download .icon.file.f-saas:after,
.inbox-download .icon.file.f-scss:after {
	border-bottom-color: #30837c;
}

.inbox-download .deleteit{
    position: absolute;
    left: 15px;
    top: 10px;
    color: red;
    cursor: pointer;
    z-index: 9999;
    width: 20px;
    height: 20px;
}

.inbox-download .defaultcursor{cursor:default !important;}

.inbox-download .pointercursor{cursor:pointer !important;}
.inbox-download .editdesc,.inbox-download .editfname {cursor:pointer !important;margin-left:2px;color:#1E90FF;vertical-align: top;}
.inbox-download .editfname {
	float:left;
}

.inbox-download .data li .fdesc{
    color: #000;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 20px !important;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
	top: 4px;
}

.inbox-download .data li .downloadf{
	margin-left:3px;
	color:#1E90FF;
	font-size:14px;
	cursor: pointer;
}

.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 6%;
    top: 0;
    width: 33%;
    z-index: 9999;
}

.fdesc span:first-child{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 145px !important;
    width: auto;
    display: inline-block;
}

.icon.folder {
    display: inline-block;
    margin: 1em;
	margin-right: 0.4em;
    background-color: transparent;
    overflow: hidden;
}

.icon.folder:before {
    content: '';
    float: left;
    background-color: #7ba1ad;
    width: 1.5em;
    height: 0.45em;
    margin-left: 0.07em;
    margin-bottom: -0.07em;
    border-top-left-radius: 0.1em;
    border-top-right-radius: 0.1em;
    box-shadow: 1.25em 0.25em 0 0em #7ba1ad;
}

.icon.folder:after {
    content: '';
    float: left;
    clear: left;
    background-color: #a0d4e4;
    width: 3em;
    height: 2.25em;
    border-radius: 0.1em;
}

.inbox-download .breadcrumbs {
    color: #626569;
    margin-left: 20px;
    font-size: 24px;
    font-weight: 700;
    line-height: 35px;
	font-size: 19px;
}

.inbox-download .nothingfound {
    background-color: #fff;
    border-radius: 10px;
    width: 23em;
    height: 21em;
    margin: 0 auto;
    display: none;
    font-family: Arial;
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}

.inbox-download .nothingfound .nofiles {
    margin: 30px auto;
    top: 3em;
    border-radius: 50%;
    position: relative;
    background-color: #d72f6e;
    width: 11em;
    height: 11em;
    line-height: 11.4em;
}

.inbox-download .nothingfound .nofiles:after {
    content: '×';
    position: absolute;
    color: #626569;
    font-size: 14em;
    margin-right: 0.092em;
    right: 0;
}

.inbox-download .nothingfound span {
    margin: 0 auto auto 6.8em;
    color: #626569;
    font-size: 16px;
    font-weight: 700;
    line-height: 20px;
    height: 13px;
    position: relative;
    top: 2em;
}
.inbox-download .showit{display:block !important;background:none !important;}
.placeright{float:right;}
.addfolder{line-height: 2;
    font-size: 19px;
    color: #626569;
    margin-right: 20px;}
.navback{margin-right:18px;cursor:pointer;}
.inbox-download{background-color: #fff;}
.nobg{border:none;background:#fff;}







.s3filemanager .data.animated {
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}
.s3filemanager .data {
    z-index: -3;
}
.s3filemanager ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
	padding-left:0;
}

/* Chrome, Safari, Opera */
.s3filemanager @-webkit-keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

/* Standard syntax */
.s3filemanager @keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

.s3filemanager .data li {
    border-radius: 3px;
    /*background-color: #373743;*/
    background-color: #fff;
    width: 307px;
    height: 118px;
    list-style-type: none;
    margin: 10px;
    display: inline-block;
    position: relative;
    overflow: hidden;
    padding: 0.3em;
    z-index: 1;
    cursor: pointer;
    box-sizing: border-box;
    transition: 0.3s background-color;
	width: 296px;
	width: 284px;
	border-radius: 1px;
	margin: 1px;
}

.s3filemanager li {
    display: list-item;
    text-align: -webkit-match-parent;
}

.s3filemanager .data li a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.s3filemanager a:-webkit-any-link {
    color: -webkit-link;
    cursor: pointer;
    text-decoration: underline;
}

.s3filemanager .icon.file.f-doc, .icon.file.f-docx, .icon.file.f-psd {
    box-shadow: 1.74em -2.1em 0 0 #03689b inset;
}

.s3filemanager .icon.file {
    width: 2.5em;
    height: 3em;
    line-height: 3em;
    text-align: center;
    border-radius: 0.25em;
    color: #fff;
    display: inline-block;
    margin: 0.9em 1.2em 0.8em 1.3em;
    margin: 0.9em 0.4em 0.8em 1.3em;
    position: relative;
    overflow: hidden;
    box-shadow: 1.74em -2.1em 0 0 #A4A7AC inset;
}

.s3filemanager .icon.file:first-line {
	font-size: 13px;
	font-weight: 700;
}
.s3filemanager .icon.file:after {
	content: '';
	position: absolute;
	z-index: -1;
	border-width: 0;
	border-bottom: 2.6em solid #DADDE1;
	border-right: 2.22em solid rgba(0, 0, 0, 0);
	top: -34.5px;
	right: -4px;
}
.s3filemanager .icon {
    font-size: 23px;
}

.s3filemanager .data li .name{
    line-height: 20px;
    width: 170px;
    position: absolute;
    top: 40px;
}

.s3filemanager .data li .fname{
    color: #333;
    font-size: 15px;
    font-weight: 700;
	white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
	max-width: 150px;
	width:auto;
    display: inline-block;
	float:left;
}

.s3filemanager .data li .details {
    width: 55px;
    height: 10px;
    top: 60px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.s3filemanager .data li .details .fsize{
    color: #b6c1c9;
    font-size: 13px;
    font-weight: 400;
    width: auto;
    white-space: nowrap;
}

.s3filemanager .data li .ftime {
    color: #b6c1c9;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 77px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.s3filemanager .icon.file:after {
    content: '';
    position: absolute;
    z-index: -1;
    border-width: 0;
    border-bottom: 2.6em solid #DADDE1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    top: -34.5px;
    right: -4px;
}
.s3filemanager{
	font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px;
	color:#333;
}

.s3filemanager .icon.file.f-avi,
.s3filemanager .icon.file.f-flv,
.s3filemanager .icon.file.f-mkv,
.s3filemanager .icon.file.f-mov,
.s3filemanager .icon.file.f-mpeg,
.s3filemanager .icon.file.f-mpg,
.s3filemanager .icon.file.f-mp4,
.s3filemanager .icon.file.f-m4v,
.s3filemanager .icon.file.f-wmv {
	box-shadow: 1.74em -2.1em 0 0 #7e70ee inset;
}
.s3filemanager .icon.file.f-avi:after,
.s3filemanager .icon.file.f-flv:after,
.s3filemanager .icon.file.f-mkv:after,
.s3filemanager .icon.file.f-mov:after,
.s3filemanager .icon.file.f-mpeg:after,
.s3filemanager .icon.file.f-mpg:after,
.s3filemanager .icon.file.f-mp4:after,
.s3filemanager .icon.file.f-m4v:after,
.s3filemanager .icon.file.f-wmv:after {
	border-bottom-color: #5649c1;
}

.s3filemanager .icon.file.f-mp2,
.s3filemanager .icon.file.f-mp3,
.s3filemanager .icon.file.f-m3u,
.s3filemanager .icon.file.f-wma,
.s3filemanager .icon.file.f-xls,
.s3filemanager .icon.file.f-xlsx {
	box-shadow: 1.74em -2.1em 0 0 #5bab6e inset;
}
.s3filemanager .icon.file.f-mp2:after,
.s3filemanager .icon.file.f-mp3:after,
.s3filemanager .icon.file.f-m3u:after,
.s3filemanager .icon.file.f-wma:after,
.s3filemanager .icon.file.f-xls:after,
.s3filemanager .icon.file.f-xlsx:after {
	border-bottom-color: #448353;
}

.s3filemanager .icon.file.f-doc,
.s3filemanager .icon.file.f-docx,
.s3filemanager .icon.file.f-psd{
	box-shadow: 1.74em -2.1em 0 0 #03689b inset !important;
}

.s3filemanager .icon.file.f-doc:after,
.s3filemanager .icon.file.f-docx:after,
.s3filemanager .icon.file.f-psd:after {
	border-bottom-color: #2980b9;
}

.s3filemanager .icon.file.f-gif,
.s3filemanager .icon.file.f-jpg,
.s3filemanager .icon.file.f-jpeg,
.s3filemanager .icon.file.f-pdf,
.s3filemanager .icon.file.f-png {
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset !important;
}
.s3filemanager .icon.file.f-gif:after,
.s3filemanager .icon.file.f-jpg:after,
.s3filemanager .icon.file.f-jpeg:after,
.s3filemanager .icon.file.f-pdf:after,
.s3filemanager .icon.file.f-png:after {
	border-bottom-color: #c6393f;
}

.s3filemanager .icon.file.f-deb,
.s3filemanager .icon.file.f-dmg,
.s3filemanager .icon.file.f-gz,
.s3filemanager .icon.file.f-rar,
.s3filemanager .icon.file.f-zip,
.s3filemanager .icon.file.f-7z {
	box-shadow: 1.74em -2.1em 0 0 #867c75 inset;
}
.s3filemanager .icon.file.f-deb:after,
.s3filemanager .icon.file.f-dmg:after,
.s3filemanager .icon.file.f-gz:after,
.s3filemanager .icon.file.f-rar:after,
.s3filemanager .icon.file.f-zip:after,
.s3filemanager .icon.file.f-7z:after {
	border-bottom-color: #685f58;
}

.s3filemanager .icon.file.f-html,
.s3filemanager .icon.file.f-rtf,
.s3filemanager .icon.file.f-xml,
.s3filemanager .icon.file.f-xhtml {
	box-shadow: 1.74em -2.1em 0 0 #a94bb7 inset;
}
.s3filemanager .icon.file.f-html:after,
.s3filemanager .icon.file.f-rtf:after,
.s3filemanager .icon.file.f-xml:after,
.s3filemanager .icon.file.f-xhtml:after {
	border-bottom-color: #d65de8;
}

.s3filemanager .icon.file.f-js {
	box-shadow: 1.74em -2.1em 0 0 #d0c54d inset;
}
.s3filemanager .icon.file.f-js:after {
	border-bottom-color: #a69f4e;
}

.s3filemanager .icon.file.f-css,
.s3filemanager .icon.file.f-saas,
.s3filemanager .icon.file.f-scss {
	box-shadow: 1.74em -2.1em 0 0 #44afa6 inset;
}
.s3filemanager .icon.file.f-css:after,
.s3filemanager .icon.file.f-saas:after,
.s3filemanager .icon.file.f-scss:after {
	border-bottom-color: #30837c;
}

.s3filemanager .deleteit{
    position: absolute;
    left: 15px;
    top: 20px;
    color: red;
    cursor: pointer;
    z-index: 9999;
    width: 20px;
    height: 20px;
}

.s3filemanager .defaultcursor{cursor:default !important;}

.s3filemanager .pointercursor{cursor:pointer !important;}
.s3filemanager .editdesc,.s3filemanager .editfname {cursor:pointer !important;margin-left:2px;color:#1E90FF;vertical-align: top;}
.s3filemanager .editfname {
	float:left;
}

.s3filemanager .data li .fdesc{
    color: #000;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 20px !important;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
	top: 4px;
}

.s3filemanager .data li .downloadf{
	margin-left:3px;
	color:#1E90FF;
	font-size:14px;
	cursor: pointer;
}

.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 6%;
    top: 0;
    width: 33%;
    z-index: 9999;
}

.fdesc span:first-child{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 145px !important;
    width: auto;
    display: inline-block;
}

.icon.folder {
    display: inline-block;
    margin: 1em;
	margin-right: 0.4em;
    background-color: transparent;
    overflow: hidden;
}

.icon.folder:before {
    content: '';
    float: left;
    background-color: #7ba1ad;
    width: 1.5em;
    height: 0.45em;
    margin-left: 0.07em;
    margin-bottom: -0.07em;
    border-top-left-radius: 0.1em;
    border-top-right-radius: 0.1em;
    box-shadow: 1.25em 0.25em 0 0em #7ba1ad;
}

.icon.folder:after {
    content: '';
    float: left;
    clear: left;
    background-color: #a0d4e4;
    width: 3em;
    height: 2.25em;
    border-radius: 0.1em;
}

.s3filemanager .breadcrumbs {
    color: #626569;
    margin-left: 20px;
    font-size: 24px;
    font-weight: 700;
    line-height: 35px;
	font-size: 19px;
}

.s3filemanager .nothingfound {
    background-color: #fff;
    border-radius: 10px;
    width: 23em;
    height: 21em;
    margin: 0 auto;
    display: none;
    font-family: Arial;
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}

.s3filemanager .nothingfound .nofiles {
    margin: 30px auto;
    top: 3em;
    border-radius: 50%;
    position: relative;
    background-color: #d72f6e;
    width: 11em;
    height: 11em;
    line-height: 11.4em;
}

.s3filemanager .nothingfound .nofiles:after {
    content: '×';
    position: absolute;
    color: #626569;
    font-size: 14em;
    margin-right: 0.092em;
    right: 0;
}

.s3filemanager .nothingfound span {
    margin: 0 auto auto 6.8em;
    color: #626569;
    font-size: 16px;
    font-weight: 700;
    line-height: 20px;
    height: 13px;
    position: relative;
    top: 2em;
}
.s3filemanager .showit{display:block !important;background:none !important;}
.placeright{float:right;}
.addfolder{line-height: 2;
    font-size: 19px;
    color: #626569;
    margin-right: 20px;}
.navback{margin-right:18px;cursor:pointer;}
.s3filemanager{background-color: #fff;}
body,html{background:#fff !important;}

#s3listview{margin-left:18px;}
#s3listview	.folder,#s3listview	.file{font-size:7px !important;}
#s3listview	.glyphicon-remove-circle{color:red;}
#s3listview	.glyphicon-download{color:#1E90FF;}
#s3listview	.editlfname{
	cursor: pointer !important;
    margin-left: 2px;
    color: #1E90FF;
    vertical-align: top;
}
#s3listview .icon.file:after {
    border-bottom-color: #c6393f;
}
#s3listview .icon.file:after{
	content: '';
    position: absolute;
    z-index: -1;
    border-width: 0;
    border-bottom: 2.6em solid #DADDE1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    top: -7.5px;
    right: -4px;
}
.bold{font-weight:bold;}
#s3listview	.flength{
	text-overflow: ellipsis;
    overflow-x: hidden;
    max-width: 338px;
    display: inline-block;
    white-space: nowrap;
}
.txtright{text-align:right;}
.fileicon{font-size: 47px;
    margin-top: 42px;
    color: #D40202;
	margin-left: 20px;
    margin-right: -21px;
}
.fileicon .icontype{
	font-size: 14px;
    color: #fff;
    position: relative;
    left: -38px;
    top: -12px;
}
.listfileicon{
	font-size: 14px;
    color: #D40202;
    margin-right: -21px;
}
.icon-view{
    margin-top: 42px;
    color: #D40202;
	margin-left: 20px;
    margin-right: 10px;
	width: 43px !important;
    height: 51px !important;
    border-radius: 3px;
}
.icon-view::before{border-width: 8px !important;}
.icon-view::after{bottom: 16px !important;
    left: 10px !important;}
.listiconsview{width: 11px !important;
height: 13px !important;}



@charset "utf-8";
/*! fileicon.css v0.1.1 | MIT License | github.com/picturepan2/fileicon.css */
/* fileicon.basic */
.file-icon {
  font-family: Arial, Tahoma, sans-serif;
  font-weight: 300;
  display: inline-block;
  width: 24px;
  height: 32px;
  background: #018fef;
  position: relative;
  border-radius: 2px;
  text-align: left;
  -webkit-font-smoothing: antialiased;
}
.file-icon::before {
  display: block;
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 0;
  height: 0;
  border-bottom-left-radius: 2px;
  border-width: 5px;
  border-style: solid;
  border-color: #fff #fff rgba(255,255,255,.35) rgba(255,255,255,.35);
}
.file-icon::after {
  display: block;
  content: attr(data-type);
  position: absolute;
  bottom: 0;
  left: 0;
  font-size: 10px;
  color: #fff;
  text-transform: lowercase;
  width: 100%;
  padding: 2px;
  white-space: nowrap;
  overflow: hidden;
}
/* fileicons */
.file-icon-xs {
  width: 12px;
  height: 16px;
  border-radius: 2px;
}
.file-icon-xs::before {
  border-bottom-left-radius: 1px;
  border-width: 3px;
}
.file-icon-xs::after {
  content: "";
  border-bottom: 2px solid rgba(255,255,255,.45);
  width: auto;
  left: 2px;
  right: 2px;
  bottom: 3px;
}
.file-icon-sm {
  width: 18px;
  height: 24px;
  border-radius: 2px;
}
.file-icon-sm::before {
  border-bottom-left-radius: 2px;
  border-width: 4px;
}
.file-icon-sm::after {
  font-size: 7px;
  padding: 2px;
}
.file-icon-lg {
  width: 48px;
  height: 64px;
  border-radius: 3px;
}
.file-icon-lg::before {
  border-bottom-left-radius: 2px;
  border-width: 8px;
}
.file-icon-lg::after {
  font-size: 16px;
  padding: 4px 6px;
}
.file-icon-xl {
  width: 96px;
  height: 128px;
  border-radius: 4px;
}
.file-icon-xl::before {
  border-bottom-left-radius: 4px;
  border-width: 16px;
}
.file-icon-xl::after {
  font-size: 24px;
  padding: 4px 10px;
}
/* fileicon.types */
.file-icon[data-type=zip],
.file-icon[data-type=rar] {
  background: #acacac;
}
.file-icon[data-type^=doc] {
  background: #307cf1;
}
.file-icon[data-type^=xls] {
  background: #0f9d58;
}
.file-icon[data-type^=ppt] {
  background: #d24726;
}
.file-icon[data-type=pdf] {
  background: #e13d34;
}
.file-icon[data-type=txt] {
  background: #5eb533;
}
.file-icon[data-type=mp3],
.file-icon[data-type=wma],
.file-icon[data-type=m4a],
.file-icon[data-type=flac] {
  background: #8e44ad;
}
.file-icon[data-type=mp4],
.file-icon[data-type=wmv],
.file-icon[data-type=mov],
.file-icon[data-type=avi],
.file-icon[data-type=mkv] {
  background: #7a3ce7;
}
.file-icon[data-type=bmp],
.file-icon[data-type=jpg],
.file-icon[data-type=jpeg],
.file-icon[data-type=gif],
.file-icon[data-type=png] {
  background: #f4b400;
}
.inbox-download,.inbox-message{
	margin-right:50px !important;
}
.icon-view{
	margin-left: 0px !important;
  margin-right: 5px !important;
}
</style>
<?php
$afilename=$filearr=$tmps3=array();
if(!empty($cont_attachments) and $cont_attachments != "[]"){
  //$filearr=json_decode($cont_attachments,true);
	//$afilename=@explode("@@@",$cont_attachments);
	$filearr=explode(";",$cont_attachments);
} //print_r($cont_attachments);print_r($filearr);die();
if(is_array($filearr) and count($filearr)){
?>
<?php if($cont_extattach > 0){ ?>
	<div class="inbox-download">
<?php	echo $cont_extattach; ?> attachment(s) <a href="javascript:void(0);" class="hidden"> Download all attachments</a>
			<ul class="inbox-download-lists data">
<?php }
	//$s3folderpath=@str_replace("INBOX/Correspondence/","",$fname);
if(count($filearr)){
	foreach($filearr as $ky => $vl){//print_r($vl);die();
		//$afilename_tmp=@explode(":",$vl);
		$filenamearr=@explode(",",$vl);
		if(!count($filenamearr) or count($filenamearr) != 8) continue;
    //$filename=$vl["filename"];
    $originalfilename=$filenamearr["1"];
    $contentfilename=$filenamearr["5"];
    $filename=$filenamearr["7"];
		$fileext=pathinfo($originalfilename, PATHINFO_EXTENSION);
//echo $s3folderpath.'/'.$afilename_tmp[1];
		$infotarget = $s3Client->doesObjectExist('datahub360-correspondence', "consolidations@vervantis.com/".$filename);
		if(!empty($filename) and $infotarget)
		{
			$headers = $s3Client->headObject(array(
				  "Bucket" => 'datahub360-correspondence',
				  "Key" => "consolidations@vervantis.com/".$filename
				));
			$headarr=$headers->toArray();//print_r($headarr);
			//if(isset($headarr["LastModified"]))print_r($headarr["LastModified"]->date);
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360-correspondence',
				'Key'    => "consolidations@vervantis.com/".$filename
			]);

			$request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
			$fileurl = (string) $request->getUri();

			$tmps3[$contentfilename]=array("content"=>$contentfilename,"filename"=>$filename,"s3filename"=>$fileurl);

			if( !empty($filenamearr["4"])) continue;
				if($cont_extattach > 0){
      ?>
      <li class="files">
        <a href="javascript:void(0);" class="files defaultcursor">
          <span class="pointercursor file-icon icon-view" data-type="<?php  echo $fileext; ?>" onclick="showpreview('<?php echo $fileurl; ?>')"  title="Click to preview: <?php echo $originalfilename; ?>"></span>

          <span class="fdesc">
            <span id="tdesc"></span>
          </span>

          <span class="name">
            <span id="tname" class="fname"><?php echo $originalfilename; ?></span>
          </span>

          <span class="details"><span class="fsize"><?php if(isset($headarr["ContentLength"])) echo formatSizeUnits($headarr["ContentLength"]); ?></span><span class="glyphicon glyphicon-download downloadf" onclick="<?php echo $randname; ?>openInNewTab('<?php echo $fileurl; ?>')" title="Download"></span></span>
          <span class="ftime"><?php if(isset($headarr["LastModified"])) echo @date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($headarr['LastModified']->format(\DateTime::ISO8601)))); ?></span>
        </a>
      </li>
    <?php
			}
		}else{
			$fileurl = "";
		}
?>

<?php }
	}
?>
<?php if($cont_extattach > 0){ ?>
	</ul>
</div>
<?php
	}
 } ?>
<style>
.inbox-message pre{
    background: none;
    border: none;
    width: 100%;
    overflow: unset;
}
.atable td, .atable th{border:1px solid #000 !important; }
</style>
<div class="inbox-message">
<?php
//echo get_mail_message($cont_message,$s3Client,$fname);
$tempmessage="";
if(!empty($cont_message)){ $tempmessage=$cont_message; }//else{ $tempmessage="<pre>".$contentType."</pre>"; }
if(count($tmps3) and !empty($tempmessage)){
	foreach($tmps3 as $ks3=>$vs3){
		if(is_array($vs3) and count($vs3) and isset($vs3["content"]) and !empty($vs3["content"]) and isset($vs3["s3filename"]) and !empty($vs3["s3filename"])){
			$tmps3fname='/cid\:'.$vs3["content"].'/s';
			$tempmessage= preg_replace($tmps3fname,$vs3["s3filename"],$tempmessage);
		}
	}
}

echo "<pre>".@trim($tempmessage)."</pre>";
?>
</div>
<div id="<?php echo $randname; ?>dialognew" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #<?php echo $randname; ?>dialognew{overflow:hidden !important;text-align:center;}</style>
<script type="text/javascript">

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 *
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 *
	 * TO LOAD A SCRIPT:
	 * var pagefunction = function (){
	 *  loadScript(".../plugin.js", run_after_loaded);
	 * }
	 *
	 * OR
	 *
	 * loadScript(".../plugin.js", run_after_loaded);
	 */


	// PAGE RELATED SCRIPTS

	$(".table-wrap [rel=tooltip]").tooltip();
<?php if(1==2){ ?>
	$(".replythis").click(function(){
		loadURL("ajax/email-reply.php", $('#inbox-content > .table-wrap'));
	})
<?php } ?>
	$( document ).ready(function() {
		$(".inbox-message style").remove();
	});

	function showpreview(filename){
		  var rurll=filename;
		  var file = rurll.replace(/\.\.\//gi,'');
		  var exts = ['jpg','jpeg','gif','png','tif','bmp','ico'];
		  // first check if file field has any value
		  if ( file ) {
			// split file name at dot
			//var get_ext = file.split('.');
			var get_ext = rurll.split(/\#|\?/)[0].split('.').pop().trim();
			// reverse name to check extension
			//get_ext = get_ext.reverse();
			// check file type is valid as given in 'exts' array
			if ( $.inArray ( get_ext.toLowerCase(), exts ) > -1 ){
			  discode='<img src="'+filename+'" width="100%" style="height: auto;max-height: 100%;width: auto" />';
			  parent.$("#<?php echo $randname; ?>dialognew").html('');
			  parent.$("#<?php echo $randname; ?>dialognew").html(discode);
			  parent.$("#<?php echo $randname; ?>dialognew").dialog("open");
			  parent.$("#<?php echo $randname; ?>d-download").attr('href', filename);
			} else {
				filename=encodeURIComponent(filename);
			  discode='<iframe src="https://docs.google.com/viewer?embedded=true&url='+filename+'" frameborder="0" width="100%" height="100%" id="<?php echo $randname; ?>googleload" name="googleload" onload="checkerror()"></iframe><a href="'+filename+'" download><div class="noshow"></div></a><script>var runct=0;window.begincheck = setInterval(reloadIFrame, 2000);function reloadIFrame() {runct=runct+1;if(Number(runct) > 4){clearInterval(window.begincheck);}else{document.getElementById("<?php echo $randname; ?>googleload").src=document.getElementById("<?php echo $randname; ?>googleload").src;}}function checkerror(){clearInterval(window.begincheck);};<\/script>';
			  parent.$("#<?php echo $randname; ?>dialognew").html('');
			  parent.$("#<?php echo $randname; ?>dialognew").html(discode);
			  parent.$("#<?php echo $randname; ?>dialognew").dialog("open");
			  parent.$("#<?php echo $randname; ?>d-download").attr('href', filename);
			}
			//preview_height = document.getElementById('googleload').contentWindow.document.body.scrollHeight;
			//document.getElementById('googleload').height = preview_height;
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
	}

$(document).ready(function(){
	filename="";
	$( "#<?php echo $randname; ?>dialognew" ).dialog({
	  height: ((90/100) * screen.height),
      width: "80%",
      show: "fade",
      hide: "fade",
	  title: 'PreviewTest',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1)){ ?>
		open: function (event, ui) {
			if($("#<?php echo $randname; ?>refreshiframe").length == 0) {
				$(this).parent().find('.ui-dialog-titlebar').append('<a href="javascript:void(0);" onclick="<?php echo $randname; ?>refreshiframe()" id="<?php echo $randname; ?>refreshiframe"><span class="glyphicon glyphicon-refresh" style="vertical-align: bottom;"></span></a>');
			}
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="<?php echo $randname; ?>d-download" download></a>');

		},
        close: function(event, ui)
        {
            $(this).dialog("close");
            //$(this).remove();
            $("#<?php echo $randname; ?>refreshiframe").remove();

        }
<?php } ?>
    });
});
function <?php echo $randname; ?>refreshiframe(){
	$( '#<?php echo $randname; ?>googleload' ).attr( 'src', function ( i, val ) { return val; });
}
function <?php echo $randname; ?>openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}
</script>





<?php
}
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
?>
