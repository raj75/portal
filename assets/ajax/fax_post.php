<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
	die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$user_email=$_SESSION["email"];
$user_fullname = $_SESSION["fullname"];

$full_path = dirname(getcwd(),1);
$upload_fax_path = $full_path.'/uploads/fax_'.$user_one;


if (isset($_GET['upload']) and $_GET['upload']>1) {

	if (!file_exists($upload_fax_path)) {
		mkdir($upload_fax_path, 0755, true);
	}

	delete_fax_files();

	if (!empty($_FILES)) {

		foreach($_FILES['s3browsefilesupload']['name'] as $filekey=>$filename){

			$tempFile = $_FILES['s3browsefilesupload']['tmp_name'][$filekey];
			$targetFile =  $upload_fax_path."/".$filename;

			if (move_uploaded_file($tempFile,$targetFile)){
				echo "file uploaded";
			}
		}

	}


} elseif (isset($_POST['submit_fax'])) {

$sql = "SELECT phone FROM user WHERE user_id= '".$user_one."' LIMIT 1";

$result = $mysqli->query($sql);
$obj = $result->fetch_object();
$user_phone = $obj->phone;

$fax_number = $_POST['fax_number'];
$fax_number_clean = preg_replace('/\D/', '', $_POST['fax_number']);
$recipient_name = $_POST['recipient_name'];
$recipient_company = $_POST['recipient_company'];
$recipient_phone_number = preg_replace('/\D/', '', $_POST['recipient_phone_number']);
$recipient_email_address = $_POST['recipient_email_address'];
$cover_page_message = $_POST['cover_page_message'];

//$to = "1".$recipient_phone_number."@fax.plus";
$to = "1".$fax_number_clean."@fax.plus";

$subject = "Fax";
if (isset($_POST['fax_title']) and !empty($_POST['fax_title'])) {
	$subject = $_POST['fax_title'];
}



$message_html = '
<!doctype html> <html> <head> <meta name="viewport" content="width=device-width" /> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <title></title> <style> img { border: none; -ms-interpolation-mode: bicubic; max-width: 100%; } body { background-color: #eaebed; font-family: Calibri; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; } table { border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; width: 100%; } table td { font-family: Calibri; font-size: 14px; vertical-align: top; } /* ------------------------------------- BODY & CONTAINER ------------------------------------- */ .body { background-color: #eaebed; width: 100%; } .container { display: block; Margin: 0 auto !important; /* max-width: 99%; */ padding: 3px; width: auto; } .content { box-sizing: border-box; display: block; Margin: 0 auto; /* max-width: 99%; */ width:auto; padding: 2px; } .main { background: #ffffff; border-radius: 3px; width: 100%; } .header { padding: 20px 0; } .wrapper { box-sizing: border-box; padding: 20px; } .content-block { padding-bottom: 10px; padding-top: 10px; } .footer { clear: both; Margin-top: 10px; text-align: center; width: 100%; } .footer td, .footer p, .footer span, .footer a { color: #9a9ea6; font-size: 12px; text-align: center; } h1, h2, h3, h4 { color: #06090f; font-family: Calibri; font-weight: 400; line-height: 1.4; margin: 0; margin-bottom: 30px; } h1 { font-size: 35px; font-weight: 300; text-align: center; text-transform: capitalize; } p, ul, ol { font-family: Calibri; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; } p li, ul li, ol li { list-style-position: inside; margin-left: 5px; } a { color: #ec0867; text-decoration: underline; } .btn { box-sizing: border-box; min-width: 100%; width: 100%; } .btn > tbody > tr > td { padding-bottom: 15px; } .btn table { min-width: auto; width: auto; } .btn table td { background-color: #ffffff; border-radius: 5px; text-align: center; } .btn a { background-color: #ffffff; border: solid 1px #ec0867; border-radius: 5px; box-sizing: border-box; color: #ec0867; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize; } .btn-primary table td { background-color: #ec0867; } .btn-primary a { background-color: #ec0867; border-color: #ec0867; color: #ffffff; } .last { margin-bottom: 0; } .first { margin-top: 0; } .align-center { text-align: center; } .align-right { text-align: right; } .align-left { text-align: left; } .clear { clear: both; } .mt0 { margin-top: 0; } .mb0 { margin-bottom: 0; } .preheader { color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0; } .powered-by a { text-decoration: none; padding:0 !important;margin:0 !important;} hr { border: 0; border-bottom: 1px solid #f6f6f6; Margin: 20px 0; } @media only screen and (max-width: 620px) { table[class=body] h1 { font-size: 28px !important; margin-bottom: 10px !important; } table[class=body] p, table[class=body] ul, table[class=body] ol, table[class=body] td, table[class=body] span, table[class=body] a { font-size: 16px !important; } table[class=body] .wrapper, table[class=body] .article { padding: 10px !important; } table[class=body] .content { padding: 0 !important; } table[class=body] .container { padding: 0 !important; width: 100% !important; } table[class=body] .main { border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important; } table[class=body] .btn table { width: 100% !important; } table[class=body] .btn a { width: 100% !important; } table[class=body] .img-responsive { height: auto !important; max-width: 100% !important; width: auto !important; } } @media all { .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } .apple-link a { color: inherit !important; font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important; line-height: inherit !important; text-decoration: none !important; } .btn-primary table td:hover { background-color: #d5075d !important; } .btn-primary a:hover { background-color: #d5075d !important; border-color: #d5075d !important; } } .atable th,.atable td{white-space: nowrap;font-size:8px !important; } .atable {border-collapse: separate;white-space: normal;line-height: normal;color: -internal-quirk-inherit;text-align: start;border-spacing: 0px !important;} </style> </head> <body class="">
<table border="1">
	<tbody>
		<tr>
			<td colspan="2" padding="3" style="width:560pt;">
				<H1><strong>FAX</strong></H1> </td>
		</tr>
		<tr>
			<td>
				<p><strong>From: fax@vervantis.com</strong></p>
			</td>
			<td>
				<p><strong>TO: '.$fax_number.'</strong></p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Name: '.$user_fullname.'</p>
				<p>Company: Vervantis Inc</p>
				<p>Phone Number: '.$user_phone.'</p>
				<p>Email Address: '.$user_email.'</p>
			</td>
			<td>
				<p>Name: '.$recipient_name.'</p>
				<p>Company: '.$recipient_company.'</p>
				<p>Phone Number: '.$recipient_phone_number.'</p>
				<p>Email Address: '.$recipient_email_address.'</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p><strong>Message:</strong></p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p>'.$cover_page_message.'</p>
			</td>
		</tr>
	</tbody>
</table>
<p>&nbsp;</p>
</body> </html>
';


global $upload_fax_path;
$toattachment = $tolattachment=array();
if(is_dir($upload_fax_path)) {
	$root = scandir($upload_fax_path);
	foreach($root as $value)
	{
		if($value === '.' || $value === '..') {continue;}

		if(is_file("$upload_fax_path/$value")) {

			$fileext=pathinfo($upload_fax_path."/".$value, PATHINFO_EXTENSION);
			if ($fileext){
				$filebasename=basename($upload_fax_path."/".$value);
				if(strpos($filebasename, '.') === (int) 0) continue;

				$filebname=pathinfo($filebasename, PATHINFO_FILENAME);

				$fsize=filesize($upload_fax_path."/".$value);

				$csvcustomfile = file_get_contents($upload_fax_path."/".$value);
				$mtype=mime_content_type($upload_fax_path."/".$value);
				if($mtype == false)$mtype='application/zip';
				if ($csvcustomfile !== false){
					if($fsize > 3145728)
					$tolattachment[] = array('Name' => $filebasename, 'ContentType' => $mtype, 'Content' => $csvcustomfile,'Path'=>$upload_fax_path."/".$value,'size'=>$fsize);
					else
					$toattachment[] = array('Name' => $filebasename, 'ContentType' => $mtype, 'Content' => $csvcustomfile,'Path'=>$upload_fax_path."/".$value,'size'=>$fsize);
				}

			}


		}
	}
}

$mailArgs =  array('subject' => $subject,
    'replyTo' => array('name' => '', 'address' => 'fax@vervantis.com'),
    'toRecipients' => array( array('name' => '', 'address' => $to) ),     // name is optional
    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
    'importance' => 'normal',
    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
    'body' => $message_html,
    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
		'attachments' => $toattachment,
		'largeattachments' => $tolattachment
  );

	$sendresponse=custommsmail('fax@vervantis.com', $mailArgs,'fax');
	delete_fax_files();
	if ($sendresponse==1){
		echo "success";
		return true;
	}else{
		echo 'Error Occured.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		return false;
	}
} // end of else

//print_r($_POST);


	function delete_fax_files(){
		global $upload_fax_path;
		if(is_dir($upload_fax_path)) {
			array_map('unlink', glob("$upload_fax_path/*.*"));
			if(!rmdir($upload_fax_path)) {
			  echo ("Could not remove $path");
			}
		}
	}
?>
