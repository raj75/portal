<?php
require_once 'db_connect.php';
require_once 'functions.php';
//require_once 'msgraphlib.php';
sec_session_start();

if(!isset($_SESSION["group_id"])) die();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

$emailhost=$_ENV['Email_HOST'];
$emailport=$_ENV['Email_PORT'];
$emailsmtpsecure=$_ENV['Email_SMTPSecure'];
$emailuser=$_ENV['Email_USER'];
$emailpass=$_ENV['EMail_PASS'];

/*if(isset($_POST["subject"]) and isset($_POST["message"]) and isset($_POST["priority"]) and isset($_POST["uid"]) and !isset($_POST["hid"]))*/
if(isset($_POST["subject"]) and isset($_POST["message"]) and isset($_POST["uid"]) and !isset($_POST["hid"]))
{
	//if($_POST["subject"] =="" || $_POST["message"] =="" || $_POST["priority"] ==""){ echo 6;exit(); }
	if($_POST["subject"] =="" || $_POST["message"] =="" ){ echo 6;exit(); }

	if($group_id != 1 and $group_id != 2){if($_POST["uid"] != $user_one){echo false;exit();} $user_id = $user_one; }
	else $user_id=$_POST["uid"];

	$user_id=$mysqli->real_escape_string(@trim($_POST["uid"]));

	$stmtcheck = $mysqli->prepare('SELECT user_id,firstname,lastname,email FROM user WHERE user_id="'.$user_id.'"  LIMIT 1');

	if($stmtcheck){
		$stmtcheck->execute();
		$stmtcheck->store_result();
		if ($stmtcheck->num_rows == 0)
		{
			echo false;
			exit();
		}
		$stmtcheck->bind_result($hd_userid,$hd_firstname,$hd_lastname,$hd_email);
		$stmtcheck->fetch();
	}else{
		echo false;
		exit();
	}

	$subject=$mysqli->real_escape_string(@trim($_POST["subject"]));
	$message=$mysqli->real_escape_string(@trim($_POST["message"]));
	$priority="Medium";
	//$priority=$mysqli->real_escape_string(@trim($_POST["priority"]));
	$messagetype="Helpdesk";
	$to = 'support@vervantis.com';
if($group_id == 1 or $group_id == 2){
	$user_id=0;
	$hd_userid=0;$hd_firstname="Anonymous";$hd_lastname="Anonymous";$hd_email="anonymous@vervantis.com";
	$messagetype="Ethics Hotline";
	$to = 'info@vervantis.com';
}


	$sql='INSERT INTO helpdesk SET id=null,user_id="'.$user_id.'",subject="'.$subject.'",message="'.$message.'",priority="'.$priority.'"';
	$stmt = $mysqli->prepare($sql);
	if($stmt){
		$stmt->execute();
		$lastuaffectedID=$stmt->affected_rows;
		$insertid=$mysqli->insert_id;
		if($lastuaffectedID == 1){
			//echo 1;

/*********************/
//$message="Hello,\r\n\r\nYour password for Vervantis changed successfully.\r\n\r\n\r\nIf you did not request a password reset or you feel that you’ve received this email in error, please contact Vervantis at support@vervantis.com or (480) 550-9225.\r\n\r\nThank you,\r\nVervantis Support Team";
			//$email=@trim($_POST['email']);
			//$name=@trim($_POST['name']);
			//$message=@trim($_POST['message']);
			//$to = 'info@vervantis.com';
			//$to = "john.warrick@vervantis.com";
			//$subject = $subject;
			//$headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email;
			//$headers = 'From: ' . $to . "\r\n" . 'Reply-To: ' . $to;

			/*$message = 'Name: ' . $hd_firstname." ".$hd_lastname . "\n" .
					   'E-mail: ' . $hd_email . "\n" .
					   'Subject: ' . $subject . "\n" .
					   'Message: ' . $message;*/

			//echo Send_Mail($to, $subject, $message);

			$message=$messagetype."<br>---------<br>Name: ".$hd_firstname." ".$hd_lastname . "<br>Email: ".$hd_email."<br>Priority: ".$priority."<br><br>".$message;
			$subject=$messagetype." Priority:".$priority.",  ".$subject;
			//echo firemail2($to,$hd_email,$subject,$message);
			//$emailuser
			//$message="Test Mail";
		  $messageArgs =  array('subject' => $subject,
		  'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
		  'toRecipients' => array( array('name' => '', 'address' => $to) ),     // name is optional .earlier $emailuser
		  'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
		  'importance' => 'normal',
		  'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
		  'body' => $message,
		  'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
		  'attachments' => array( )
		  );

			custommsmail('noreply@vervantis.com', $messageArgs,'');
/****************************/




			echo true;
			exit();
		}else{
			echo false;
		}
	}else{
		echo false;
	}

}elseif(isset($_POST["subject"]) and isset($_POST["message"]) and isset($_POST["hid"]) and isset($_POST["notes"]) and isset($_POST["status"])){
/*}elseif(isset($_POST["subject"]) and isset($_POST["message"]) and isset($_POST["priority"]) and isset($_POST["hid"]) and isset($_POST["notes"]) and isset($_POST["status"]))
{*/
	if($group_id != 1 and $group_id != 2){echo false;die();}

	if($_POST["subject"] =="" || $_POST["message"] ==""){ echo 6;exit(); }
	//if($_POST["subject"] =="" || $_POST["message"] =="" || $_POST["priority"] ==""){ echo 6;exit(); }

	$hid=$mysqli->real_escape_string(@trim($_POST["hid"]));

	$stmtcheck = $mysqli->prepare('SELECT id FROM helpdesk WHERE id="'.$hid.'"  LIMIT 1');

	if($stmtcheck){
		$stmtcheck->execute();
		$stmtcheck->store_result();
		if ($stmtcheck->num_rows == 0)
		{
			echo false;
			exit();
		}
	}else{
		echo false;
		exit();
	}

	$subject=$mysqli->real_escape_string(@trim($_POST["subject"]));
	$message=$mysqli->real_escape_string(@trim($_POST["message"]));
	//$priority=$mysqli->real_escape_string(@trim($_POST["priority"]));
	$priority="Medium";
	$notes=$mysqli->real_escape_string(@trim($_POST["notes"]));
	$status=$mysqli->real_escape_string(@trim($_POST["status"]));

	$sql='UPDATE helpdesk SET subject="'.$subject.'",message="'.$message.'",priority="'.$priority.'",notes="'.$notes.'",status="'.$status.'" WHERE id='.$hid;
	$stmt = $mysqli->prepare($sql);
	if($stmt){
		$stmt->execute();
		echo true;
		exit();
	}else{
		echo false;
	}

}else echo false;

function helpdesk_Mail($to,$subject,$body)
{
	require_once '../php/class.phpmailer.php';
	$from = "info@vervantis.com";
	$mail = new PHPMailer();
	$mail->IsSMTP(true); // SMTP
	$mail->SMTPAuth   = true;  // SMTP authentication
	$mail->Mailer = "smtp";
	$mail->Host       = "tls://email-smtp.us-west-2.amazonaws.com"; // Amazon SES server, note "tls://" protocol
	$mail->Port       = 465;                    // set the SMTP port
	$mail->Username   = "AKIAI4DJL43CXB24O2EQ";  // SES SMTP  username
	$mail->Password   = "AtVKkDdRz2OYviHnjghy6FMal7F0H2cG5R2DcR1wKepd";  // SES SMTP password
	$mail->SetFrom($from, 'Vervantis');
	$mail->AddReplyTo($from,'Vervantis');
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$mail->SMTPDebug = 0;
	//$mail->Body = $body;
	$address = $to;
	$mail->AddAddress($address, $to);

	if(!$mail->Send())
		return false;
	else
		return true;
}

function firemail2($to="",$from="",$subject="",$message="",$header=""){
	if(@trim($to)=="" || @trim($from)=="" || @trim($subject)=="" || @trim($message)=="") return false;
	global $emailhost;
	global $emailport;
	global $emailsmtpsecure;
	global $emailuser;
	global $emailpass;


	$to=$emailuser;
	require '../plugins/PHPmailer/class.phpmailer.php';
	$message = wordwrap($message,70, "\r\n");
	$mail = new PHPMailer(true);
	$mail->CharSet = "UTF-8";
	$mail->isSMTP();
	$mail->Host = $emailhost;
	$mail->Port       = $emailport;
	$mail->SMTPSecure = $emailsmtpsecure;
	$mail->SMTPAuth   = true;
	$mail->Username = $emailuser;
	$mail->Password = $emailpass;
	$mail->SetFrom($emailuser, 'FromEmail');
	//$mail->SetFrom($from, 'FromEmail');
	$mail->addAddress($to, 'ToEmail');
	//$mail->SMTPDebug  = 3;
	//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';
	$mail->IsHTML(false);
	$mail->SMTPDebug = 0;

	$mail->Subject = $subject;
	$mail->Body    = $message;
	//$mail->AltBody = $message;

	if(!$mail->send()) {
		echo 'Error Occured.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo true;
	}
}
