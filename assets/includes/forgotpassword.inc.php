<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
//header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload; X-XSS-protection: 1; mode=block");
error_reporting(0);
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.
$servername=$_SERVER['HTTP_HOST'];
$error="";

if (isset($_POST['email'])) {
	if(!isset($_POST['captcha'])){
		echo "wrongcaptcha";
		exit();
	}else{
		$secret="6LdcblUUAAAAAC9o7_KznWBb7AZJnCfhIdOmcTzV";
		$response=$_POST["captcha"];

		$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response);
		$captcha_success=json_decode($verify);
		if ($captcha_success->success==false) {
			echo "wrongcaptcha";
			exit();
		}
	}


    $email = filter_string_polyfill($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        echo 'The email address you entered is not valid';
		exit();
    }

    $prep_stmt = "SELECT user_id FROM user WHERE email = ? LIMIT 1";

//"SELECT id FROM user WHERE email = ? LIMIT 1";

    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
			$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
			$hkey = hash('sha512', time() . $random_salt.rand());

			$sql_update="UPDATE user SET hkey='".$mysqli->real_escape_string($hkey)."' WHERE email='".$mysqli->real_escape_string($email)."'";
			$stmt_update = $mysqli->prepare($sql_update);
			if($stmt_update)
			{
				$stmt_update->execute();
				//send email
				/*$body='Hi,
					<br>
					Vervantis.com has received a request to reset the password for your account. If you did not request to reset your password, please ignore this email.
					<br>
					<a href="https://www.vervantis.com/portal/resetpassword.php" style="font-size:14px;font-weight:bold;color:white;border:1px solid #21aa13;background:#93da46;padding:5px 10px" target="_blank">Reset password</a>';*/
					$subject = "Your New Vervantis Password";

					/*$message = "Hi,<br>";
					$message .= "Vervantis.com has received a request to reset the password for your account.
									<br><br>
									<a href='https://www.vervantis.com/portal/resetpassword.php?key=".$hkey."'>Reset password</a>

									<br> If you did not request to reset your password, please ignore this email.
									";*/

					/*$message = '<table class="m_-2118604351086944788MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="2112" style="width:22.0in">
<tbody>
<tr style="height:37.5pt">
<td valign="bottom" style="background:white;padding:0in 0in 0in 0in;height:37.5pt">
<p class="MsoNormal"><span style="color:black"><img width="247" height="51" style="width:2.5729in;height:.5312in" id="m_-2118604351086944788Picture_x0020_1" src="https://'.$servername.'/assets/img2/vervantis_logo_small.png" alt="Vervantis" class="CToWUd"></span><u></u><u></u></p>
</td>
</tr>
<tr style="height:3.75pt">
<td valign="top" style="padding:0in 0in 0in 0in;height:3.75pt">
</td>
</tr>
<tr style="height:225.0pt">
<td valign="top" style="background:white;padding:0in 0in 0in 0in;height:225.0pt">
<table class="m_-2118604351086944788MsoNormalTable" border="0" cellspacing="5" cellpadding="0" width="600" style="width:6.25in">
<tbody>
<tr style="height:37.5pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:37.5pt">

<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Hello,</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u><u></u></span></p>

</td>
</tr>
<tr style="height:225.0pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:225.0pt">
<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Your password has been reset for
</span><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;background:white">Vervantis<span style="color:black">. Your username is:&nbsp;<a href="mailto:'.$email.'" target="_blank">'.$email.'</a></span></span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">
<u></u><u></u></span></p>
<div>
<p class="MsoNormal"><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u>&nbsp;<u></u></span></p>
</div>
<div>
<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Click on the URL below:</span><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><br>
<br>
<a href="https://'.$servername.'/resetpassword.php?key='.$hkey.'">Change Password</a>
</p>
</div>
<div>
<p class="MsoNormal"><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u>&nbsp;<u></u></span></p>
</div>
<div>
<p class="MsoNormal"><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">If you did not request a password reset or you feel that you’ve received this email in error, please contact
</span><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif">Vervantis<span style="color:black"> at
</span><a href="mailto:support@vervantis.com" target="_blank">support@vervantis.com</a><span style="color:black"> or (</span>480<span style="color:black">)
</span>550<span style="color:black">-</span>9225<span style="color:black">.</span></span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif"><u></u><u></u></span></p>
</div>
</td>
</tr>
<tr style="height:37.5pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:37.5pt">
<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Thank you,</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">
<u></u><u></u></span></p>
<div>
<p class="MsoNormal"><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif;background:white">Vervantis Support Team</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u><u></u></span></p>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';*/
//$headers = "Content-Type: text/plain; charset=\"utf-8\"\r\n";
/*$message="Your password has been reset for DataHub360. Your username is: ".$email."<br><br><br>Click on the URL below:<br>
https://".$servername."/resetpassword.php?key=".$hkey."<br><br><br>If you did not request a password reset or you feel that you have received this email in error, please contact Vervantis Support at support@vervantis.com or call (480) 550-9225.<br><br><br>Thank you,<br>Vervantis Support Team";*/
$message="<html>You have requested a password reset for DataHub360. Click on the URL:
https://".$servername."/resetpassword.php?key=".$hkey."&ct=".time()." If you did not request a password reset or you feel that you have received this email in error, please contact Vervantis Support at support@vervantis.com or call (480) 550-9225.<br><br><br>Thank you,<br>Vervantis Support Team</html>";
				//echo firemail2($email,$subject,$message);
				$mailArgs =  array('subject' => $subject,
				    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
				    'toRecipients' => array( array('name' => '', 'address' => $email) ),     // name is optional
				    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
				    'importance' => 'normal',
				    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
				    'body' => $message,
				    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
				    'attachments' => array( )
				  );

				custommsmail('noreply@vervantis.com', $mailArgs,'');
				echo true;
				exit();
			}else{
				echo false;
				//echo json_encode(array("error"=>$error));
				exit();
			}
        }else {/*echo 'The email address does not exist in our system.';*/ echo true; exit(); }
    } else {
        echo 'Password reset failed.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		exit();
    }
}elseif (isset($_POST['pwkey']) and isset($_POST['pw'])) {
	$pwkey = @trim($_POST['pwkey']);
	if($pwkey == ""){echo false;exit();}
    //$password = @trim($_POST['pw']); // The hashed password.

    $password = filter_string_polyfill($_POST["pw"]);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        echo 'Invalid password configuration';
		exit();
    }

    $prep_stmt = "SELECT user_id,email,password,salt FROM user WHERE hkey = ? LIMIT 1";

//"SELECT id,email FROM user WHERE hkey = ? LIMIT 1";

    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $pwkey);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
			$stmt->bind_result($gsid,$email,$oldpw,$oldsalt);
			$stmt->fetch();


			$oldpassword = hash('sha512', $password . $oldsalt);//echo $db_password.":".$password;
			if ($oldpw == $oldpassword) {
				echo 'New Password must be different from earlier password';
				exit();
			}



			if($usertracking_stmt = $mysqli->prepare("SELECT user_id FROM user_tracking WHERE user_id = '".$mysqli->real_escape_string($gsid)."' and password='".$mysqli->real_escape_string($oldpassword)."' LIMIT 1 "))
			{
				$usertracking_stmt->execute();
				$usertracking_stmt->store_result();
				$usertracking_stmt->bind_result($user_id);
				$usertracking_stmt->fetch();

				if ($usertracking_stmt->num_rows > 0) {
					echo 'New Password must be different from earlier password';
					exit();
				}
			}







			//die();

			// Create a random salt
			$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

			// Create salted password
			$password = hash('sha512', $password . $random_salt);

			$sql_update="UPDATE user SET `status`=1,disable_date=NULL,password='".$mysqli->real_escape_string($password)."', salt='".$mysqli->real_escape_string($random_salt)."',hkey='',failed_password_attempts=0 WHERE hkey='".$mysqli->real_escape_string($pwkey)."'";
			$stmt_update = $mysqli->prepare($sql_update);
			if($stmt_update)
			{
				$stmt_update->execute();
				//send email password changed successfully
				$subject="Password changed successfully.";

				if ($stmtattemptclear = $mysqli->prepare("DELETE FROM login_attempts WHERE user_id = ".$gsid)) {
					$stmtattemptclear->execute();
				}

				$mysqli->query("UPDATE user_tracking set `status`='Inactive' WHERE user_id='".$gsid."'");

				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$clientip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$clientip = $_SERVER['REMOTE_ADDR'];
				}
				$referrer= @$_SERVER['HTTP_REFERER'];



				$mysqli->query("INSERT INTO user_tracking set status='Inactive', user_id='".$gsid."',password='".$mysqli->real_escape_string($password)."',action='Password Changed', ipaddress='".$mysqli->real_escape_string($clientip)."',referrer='".$mysqli->real_escape_string($referrer)."'");
				/*$message='Hi,<br>
					Your password changed successfully. <br>Now you can login with the new password.';*/


					/*$message = '<table class="m_-2118604351086944788MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="2112" style="width:22.0in">
<tbody>
<tr style="height:37.5pt">
<td valign="bottom" style="background:white;padding:0in 0in 0in 0in;height:37.5pt">
<p class="MsoNormal"><span style="color:black"><img width="247" height="51" style="width:2.5729in;height:.5312in" id="m_-2118604351086944788Picture_x0020_1" src="https://'.$servername.'/assets/img2/vervantis_logo_small.png" alt="Vervantis" class="CToWUd"></span><u></u><u></u></p>
</td>
</tr>
<tr style="height:3.75pt">
<td valign="top" style="padding:0in 0in 0in 0in;height:3.75pt">
</td>
</tr>
<tr style="height:225.0pt">
<td valign="top" style="background:white;padding:0in 0in 0in 0in;height:225.0pt">
<table class="m_-2118604351086944788MsoNormalTable" border="0" cellspacing="5" cellpadding="0" width="600" style="width:6.25in">
<tbody>
<tr style="height:37.5pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:37.5pt">

<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Hello,</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u><u></u></span></p>

</td>
</tr>
<tr style="height:225.0pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:225.0pt">
<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Your password for
</span><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;background:white">Vervantis<span style="color:black"> changed successfully.</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">
<u></u><u></u></span></p>
<div>
<p class="MsoNormal"><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u>&nbsp;<u></u></span></p>
</div>
<div>
<p class="MsoNormal"><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u>&nbsp;<u></u></span></p>
</div>
<div>
<p class="MsoNormal"><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">If you did not request a password reset or you feel that you&#39;ve received this email in error, please contact
</span><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif">Vervantis<span style="color:black"> at
</span><a href="mailto:support@vervantis.com" target="_blank">support@vervantis.com</a><span style="color:black"> or (</span>480<span style="color:black">)
</span>550<span style="color:black">-</span>9225<span style="color:black">.</span></span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif"><u></u><u></u></span></p>
</div>
</td>
</tr>
<tr style="height:37.5pt">
<td valign="top" style="padding:3.75pt 3.75pt 3.75pt 3.75pt;height:37.5pt">
<p class="MsoNormal"><span style="font-size:13.5pt;font-family:&quot;Arial&quot;,sans-serif;color:black;background:white">Thank you,</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black">
<u></u><u></u></span></p>
<div>
<p class="MsoNormal"><span style="font-size:18.0pt;font-family:&quot;Arial&quot;,sans-serif;background:white">Vervantis Support Team</span><span style="font-size:12.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black"><u></u><u></u></span></p>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';*/



$message="<html>Your password change is successful. If you did not request a password reset or you feel that you’ve received this email in error, please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team</html>";
				//echo firemail2($email,$subject,$message,"",0);
				$mailArgs =  array('subject' => $subject,
				    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
				    'toRecipients' => array( array('name' => '', 'address' => $email) ),     // name is optional
				    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
				    'importance' => 'normal',
				    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
				    'body' => $message,
				    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
				    'attachments' => array( )
				  );

				custommsmail('noreply@vervantis.com', $mailArgs,'');
				echo true;
				exit();
			}else{
				echo false;
				//echo json_encode(array("error"=>$error));
				exit();
			}
		} else {
        echo 'Link expired.  Try again.  If this issue persists, please contact support@vervantis.com.';
				exit();
			}
    } else {
        echo 'Password reset failed.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		exit();
    }
} else {
	echo false;
    exit();
}

function firemail($to="",$subject="",$message="",$header=""){
	if(@trim($to)=="" || @trim($subject)=="" || @trim($message)=="") return false;
	$header = "From:support@vervantis.com \r\n";
	$header .= "Reply-To:support@vervantis.com \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	//$header .= "Content-type: text/html\r\n";
	$header .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";

	$message = wordwrap($message,70, "\r\n");

	echo mail ($to,$subject,$message,$header);
	//echo mail ($to,$subject,$message);
}

function firemail2($to="",$subject="",$message="",$header="",$errordisplay=1){
	if(@trim($to)=="" || @trim($subject)=="" || @trim($message)=="") return false;
	$to = @strtolower(@preg_replace("/\s+/", "", $to));
	require '../plugins/PHPmailer/class.phpmailer.php';
	$message = wordwrap($message,70, "\r\n");
	$mail = new PHPMailer(true);
	$mail->CharSet = "UTF-8";
	$mail->isSMTP();
	$mail->Host = 'smtp.office365.com';
	$mail->Port       = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth   = true;
	$mail->Username = 'support@vervantis.com';
	//$mail->Password = 'Sag28843';
	$mail->Password = 'QhUYZquYQL4oxV#J@ymC';
	$mail->SetFrom('support@vervantis.com', 'FromEmail');
	$mail->addAddress($to, 'ToEmail');
	//$mail->SMTPDebug  = 3;
	//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';
	$mail->IsHTML(false);
	$mail->SMTPDebug = 0;

	$mail->Subject = $subject;
	$mail->Body    = $message;
	//$mail->AltBody = $message;

	if(!$mail->send()) {
		if($errordisplay==1){
			echo 'Password reset failed.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		}else echo true;
		@error_log($mail->ErrorInfo, 0);
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo true;
	}
}
?>
