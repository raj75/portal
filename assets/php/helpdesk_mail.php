<?php
require 'Send_Mail.php';
//$to = "john.warrick@vervantis.com";
//$subject = "Test Mail Subject";
//$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
//echo Send_Mail($to,$subject,$body)
if( isset($_POST['name']) and isset($_POST['email']) and isset($_POST['message']) and @trim($_POST['name']) != "" and @trim($_POST['email']) != "" and @trim($_POST['message']) != "")
{
			
	$email=@trim($_POST['email']);
	$name=@trim($_POST['name']);
	$message=@trim($_POST['message']);
	$to = 'info@vervantis.com';
	//$to = "john.warrick@vervantis.com";
	$subject = $name.' Messaged on Vervantis contact page';
	//$headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email;
	$headers = 'From: ' . $to . "\r\n" . 'Reply-To: ' . $to;
	
	$message = 'Name: ' . $name . "\n" .
			   'E-mail: ' . $email . "\n" .
			   'Subject: ' . $subject . "\n" .
			   'Message: ' . $message;
	
	echo Send_Mail($to, $subject, $message);	
	/*if( $_POST['copy'] == 'on' )
	{
		mail($_POST['email'], $subject, $message, $headers);
	}*/
	exit();
}
echo false;
?>