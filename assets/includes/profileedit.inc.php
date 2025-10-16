<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cmpid=$_SESSION['company_id'];

if(isset($user_one) and isset($_SESSION["group_id"]) and $_SESSION["group_id"] != "" and $_SESSION["group_id"] != 0 and isset($_POST['role']) and $_POST["role"] != "" and isset($_POST["usr"]) and $_POST["usr"] != "" and $_POST["usr"] != 0 and $user_one != "" and $user_one != 0 and !isset($_POST["new"]))
{
	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);


	$error="Error occured";
	$sub_query=$usub_query=array();
	$temp_status=$temp_disabledate=null;
	$laclear=$tstatus=0;

	$tmp_usr=$mysqli->real_escape_string(@trim($_POST["usr"]));


	if($group_id != 1)
	{
		//$tmp_usr=$user_one;
	}


	if($group_id==1)
	{
		if(isset($_POST['email']) and @trim($_POST['email']) != "")
		{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// Not a valid email
				echo json_encode(array('error'=>'The email address you entered is not valid'));
				exit();
			}else{
				$email= @strtolower(@trim($email));
				$stmtsk = $mysqli->prepare('SELECT user_id FROM user where user_id="'.$tmp_usr.'" and email="'.$email.'"
				'.(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?'':' and company_id = '.$cmpid.'').' LIMIT 1');

//('SELECT id FROM user where id="'.$tmp_usr.'" and email="'.$email.'" LIMIT 1');

				if($stmtsk){
					$stmtsk->execute();
					$stmtsk->store_result();
					if ($stmtsk->num_rows > 0)
					{
						///New Code added
						if(isset($_POST['newemail']) and @trim($_POST['newemail']) != "")
						{
							$newemail = filter_input(INPUT_POST, 'newemail', FILTER_SANITIZE_EMAIL);
							$newemail = filter_var($newemail, FILTER_VALIDATE_EMAIL);
							if (!filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
								// Not a valid email
								echo json_encode(array('error'=>'The new email address you entered is not valid'));
								exit();
							}else{
								$newemail= @strtolower(@trim($newemail));
								if($newemail != $email){
									$ostmtsk = $mysqli->prepare('SELECT user_id FROM user where email="'.$newemail.'" LIMIT 1');
									if($ostmtsk){
										$ostmtsk->execute();
										$ostmtsk->store_result();
										if ($ostmtsk->num_rows == 0)
										{
											$sub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['newemail']))).'"';
											$usub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['newemail']))).'"';
										}else{
											echo json_encode(array('error'=>'The new email address already exists. Please enter another one.'));
											exit();
										}
									}
								}else{
									$sub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
									$usub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
								}
							}


					}else{
						$sub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
						$usub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
					}
					///New code ends
					}else{
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();
					}
				}else{
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();
				}
			}
		}
	}

	if(isset($_POST['password']) and @trim($_POST['password']) != "" and isset($_POST['p']) and @trim($_POST['p']) != "")
	{
		//$usub_query[]='"password"="'.$mysqli->real_escape_string(@trim($_POST['password'])).'"';
		$password = filter_string_polyfill($_POST['p']);
		if (strlen($password) != 128) {
			// The hashed pwd should be 128 characters long.
			// If it's not, something really odd has happened
			echo json_encode(array('error'=>'Invalid password configuration'));
			exit();
		}else{
			$tmp_password=password_generate($password);
			$tmp_=array();
			$tmp_=explode("@@@@",$tmp_password);
			if(count($tmp_))
			{
				//$random_salt=$tmp_[0];
				//$password=$tmp_[1];
				$sub_query[]='password="'.$mysqli->real_escape_string(@trim($tmp_[1])).'",salt="'.$mysqli->real_escape_string(@trim($tmp_[0])).'"';
				$sub_query[]='password_change_date=now()';
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
			}
		}
	}

	if(isset($_POST['fname']) and @trim($_POST['fname']) != "")
	{
		$sub_query[]='firstname="'.$mysqli->real_escape_string(@trim($_POST['fname'])).'"';
	}

	if(isset($_POST['lname']) and @trim($_POST['lname']) != "")
	{
		$sub_query[]='lastname="'.$mysqli->real_escape_string(@trim($_POST['lname'])).'"';
	}

	if(isset($_POST['address']) and @trim($_POST['address']) != "")
	{
		$sub_query[]='address="'.$mysqli->real_escape_string(@trim($_POST['address'])).'"';
	}

	if(isset($_POST['city']) and @trim($_POST['city']) != "")
	{
		$sub_query[]='city="'.$mysqli->real_escape_string(@trim($_POST['city'])).'"';
	}

	if(isset($_POST['state']) and @trim($_POST['state']) != "")
	{
		$sub_query[]='state="'.$mysqli->real_escape_string(@trim($_POST['state'])).'"';
	}

	if(isset($_POST['country']) and @trim($_POST['country']) != "")
	{
		$sub_query[]='country="'.$mysqli->real_escape_string(@trim($_POST['country'])).'"';
	}

	if(isset($_POST['zip']) and @trim($_POST['zip']) != "")
	{
		$sub_query[]='zip="'.$mysqli->real_escape_string(@trim($_POST['zip'])).'"';
	}

	if(isset($_POST['phone']) and @trim($_POST['phone']) != "")
	{
		$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone']))).'"';
	}

	if(isset($_POST['mobile']) and @trim($_POST['mobile']) != "")
	{
		$sub_query[]='mobile="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['mobile']))).'"';
	}

	if(isset($_POST['fax']) and @trim($_POST['fax']) != "")
	{
		$sub_query[]='fax="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['fax']))).'"';
	}

	if(isset($_POST['title']) and @trim($_POST['title']) != "")
	{
		$sub_query[]='title="'.$mysqli->real_escape_string(@trim($_POST['title'])).'"';
	}

	if(isset($_POST['accuviouser']))
	{
		$sub_query[]='accuvio_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['accuviouser']),'e')).'"';
	}

	if(isset($_POST['accuviopass']) and @trim($_POST['accuviopass']) != "")
	{
		$sub_query[]='accuvio_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['accuviopass']),'e')).'"';
	}
	if(isset($_POST['capturisuser']))
	{
		$sub_query[]='capturis_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisuser']),'e')).'"';
	}

	if(isset($_POST['capturispass']) and @trim($_POST['capturispass']) != "")
	{
		$sub_query[]='capturis_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturispass']),'e')).'"';
	}
	if(isset($_POST['capturisarchiveuser']))
	{
		$sub_query[]='capturis_archive_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisarchiveuser']),'e')).'"';
	}

	if(isset($_POST['capturisarchivepass']) and @trim($_POST['capturisarchivepass']) != "")
	{
		$sub_query[]='capturis_archive_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisarchivepass']),'e')).'"';
	}

if($group_id==1 || $group_id==5)
{
	if(isset($_POST['status']) and @trim($_POST['status']) != "")
	{
		$temp_status=$mysqli->real_escape_string(@trim($_POST['status']));
		//$sub_query[]='status="'.$mysqli->real_escape_string(@trim($_POST['status'])).'"';
	}

	if(isset($_POST['disabledate']) and @trim($_POST['disabledate']) != "")
	{
		$temp_disabledate=@trim(@date("Y-m-d H:i:s",@strtotime($_POST['disabledate'])));
		//$sub_query[]='disable_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['disabledate'])))).'"';
	}

	if ($tstmt = $mysqli->prepare('SELECT status,disable_date FROM `user` where user_id='.$tmp_usr.' LIMIT 1')) {
		$tstmt->execute();
		$tstmt->store_result();
		if ($tstmt->num_rows > 0) {
			$tstmt->bind_result($tstatus,$tdisable_date);
			$tstmt->fetch();
			if(!empty($tdisable_date)) $tempddate= @strtotime($tdisable_date);
			else $tempddate="";

			if(!empty($temp_disabledate)) $inputdisabledate= @strtotime($temp_disabledate);
			else $inputdisabledate="";

			$nowtime=strtotime("now");

			if($temp_status != $tstatus){
				////////Status changed
				if($temp_status== 0 or $temp_status==2){
					$sub_query[]='status="'.$temp_status.'"';
					$sub_query[]='disable_date=NOW()';
				}elseif($temp_status==1){
					$sub_query[]='status="'.$temp_status.'"';
					$sub_query[]='disable_date=NULL';
					$sub_query[]='failed_password_attempts=0';
				}elseif($temp_status==3){
					$sub_query[]='status="'.$temp_status.'"';
					$sub_query[]='disable_date=NULL';
				}else{
					echo json_encode(array('error'=>'Error Occured! Invalid Parameters.'));
					exit();
				}

				if($temp_status != 2) $laclear=1;
			}elseif($temp_status == $tstatus){
				/////Status not changed
				if(!empty($temp_disabledate)){
					//Not Null Input disable date
					if($inputdisabledate < $nowtime){
						//Input disable date < NOW
						$sub_query[]='status=1';
						$sub_query[]='disable_date="'.$mysqli->real_escape_string($temp_disabledate).'"';
					}elseif($inputdisabledate > $nowtime){
						//Input disable date > NOW
						$sub_query[]='status=0';
 						$sub_query[]='disable_date="'.$mysqli->real_escape_string($temp_disabledate).'"';
					}
				}else{
					//Input Disable date is empty
					//Do Nothing because things are same as DB
				}

			}


		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
			//$sub_query[]='disable_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['disabledate'])))).'"';
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Database error.'));
		exit();
	}

}


	if($group_id==1)
	{
		if(isset($_POST['gender']) and @trim($_POST['gender']) != "" and (@trim($_POST['gender'])=="M" or @trim($_POST['gender'])=="F"))
		{
			$sub_query[]='gender="'.$mysqli->real_escape_string(@trim($_POST['gender'])).'"';
		}

		if(isset($_POST['company']) and @trim($_POST['company']) != "")
		{
			$sub_query[]='company_id="'.$mysqli->real_escape_string(@trim($_POST['company'])).'"';
		}
/*
		if(isset($_POST['disabledate']) and @trim($_POST['disabledate']) != "")
		{
			$sub_query[]='disable_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['disabledate'])))).'"';
		}
*/
		if(isset($_POST['usergroups']) and @trim($_POST['usergroups']) != "")
		{
			$sub_query[]='usergroups_id="'.$mysqli->real_escape_string(@trim($_POST['usergroups'])).'"';
		}

		if(isset($_POST['notes']))
		{
			$sub_query[]='notes="'.$mysqli->real_escape_string(@trim($_POST['notes'])).'"';
		}
	}

	if(count($sub_query)){
			$sql='UPDATE user SET '.implode(",",$sub_query).' WHERE user_id='.$tmp_usr;
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

				if($laclear==1){
					if ($stmtattemptclear = $mysqli->prepare("DELETE FROM login_attempts WHERE user_id = ".$tmp_usr)) {
						$stmtattemptclear->execute();
					}
				}

				if($tstatus==1){
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$clientip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$clientip = $_SERVER['REMOTE_ADDR'];
					}
					$referrer= @$_SERVER['HTTP_REFERER'];

					//$mysqli->query("UPDATE user_tracking set `status`='Inactive' WHERE user_id='".$tmp_usr."'");
					if(isset($_POST['password']) and @trim($_POST['password']) != "" and isset($_POST['p']) and @trim($_POST['p']) != "")
					{
						$mysqli->query("INSERT INTO user_tracking set status='Inactive', user_id='".$tmp_usr."',password='".$mysqli->real_escape_string($password)."',action='Password Changed', ipaddress='".$mysqli->real_escape_string($clientip)."',referrer='".$mysqli->real_escape_string($referrer)."'");
					}
				}

			}else{
				echo json_encode(array("error"=>$error));
				exit();
			}
		//if($lastaffectedID == 1){


			//echo json_encode(array("error"=>""));
		//}else
			//echo json_encode(array("error"=>$error));

		//exit();
	}

	//File Edit
	$_cuserimage="";
	$_ccompanyimage="";

	if(isset($_FILES["file"]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
		) && ($_FILES["file"]["size"] > 0)//Approx. 100kb files can be uploaded  i.e. 100000.
		&& in_array($file_extension, $validextensions)) {
			if ($_FILES["file"]["error"] > 0)
			{
				$error=$_FILES["file"]["error"];
				echo json_encode(array("error"=>$error));
				exit();
			}
			else
			{
			   if ($ustmt = $mysqli->prepare('SELECT user_id,gender FROM `user` where user_id='.$tmp_usr.' LIMIT 1')) {

//('SELECT id FROM `user` where id='.$tmp_usr.' LIMIT 1')) {

					$ustmt->execute();
					$ustmt->store_result();
					if ($ustmt->num_rows > 0) {
						$ustmt->bind_result($tmp_user_id,$tmp_gender);
						$ustmt->fetch();
						$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
						//$targetPath = "upload/".$__username; // Target path where file is to be stored

						/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/users/profile image/".md5($tmp_usr).".png";
						if(file_exists($targetPath)){
						  @unlink($targetPath);
						}*/

						$ttmp_usr=md5($tmp_usr).".png";

						$info = $s3Client->doesObjectExist('datahub360', 'profiles/users/profile image/'.$ttmp_usr);
						if($info)
						{
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'profiles/users/profile image/'.$ttmp_usr
							]);
						}


						$result = $s3Client->putObject([
							'Bucket' => 'datahub360',
							'Key'    => 'profiles/users/profile image/'.$ttmp_usr,
							'SourceFile' => $sourcePath
						]);

						$_cuserimage=checks3img($ttmp_usr,"profiles/users/profile image/",(($tmp_gender == "M" || @trim($tmp_gender == ""))?"male.png":"female.png"));
						if($_cuserimage==false){$_cuserimage="";}

						//move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
						/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
						echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
						echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
						echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
						echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
					}else{
						echo json_encode(array("error"=>"Error Occured! Please try after sometime."));
						exit();
					}
				}else{
					echo json_encode(array("error"=>"Error Occured! Please try after sometime."));
					exit();
				}
			}
		}
		else
		{
			$error="Invalid file Size or Type";
			echo json_encode(array("error"=>$error));
			exit();
		}
	}

	//File Edit Ends

	//Company Logo Edit

	if(isset($_FILES["companylogo"]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["companylogo"]["name"]);
		$file_extension = end($temporary);
		if ((($_FILES["companylogo"]["type"] == "image/png") || ($_FILES["companylogo"]["type"] == "image/jpg") || ($_FILES["companylogo"]["type"] == "image/jpeg")
		) && ($_FILES["companylogo"]["size"] > 0)//Approx. 100kb files can be uploaded  i.e. 100000.
		&& in_array($file_extension, $validextensions)) {
			if ($_FILES["companylogo"]["error"] > 0)
			{
				$error=$_FILES["companylogo"]["error"];
				echo json_encode(array("error"=>$error));
				exit();
			}
			else
			{
			   if ($cstmt = $mysqli->prepare('SELECT company_id FROM `user` where user_id='.$tmp_usr.' LIMIT 1')) {

//('SELECT id FROM `user` where id='.$tmp_usr.' LIMIT 1')) {

					$cstmt->execute();
					$cstmt->store_result();
					if ($cstmt->num_rows > 0) {
						$cstmt->bind_result($_cid);
						$cstmt->fetch();
						$sourcePath = $_FILES['companylogo']['tmp_name']; // Storing source path of the file in a variable
						//$targetPath = "upload/".$__username; // Target path where file is to be stored

						/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/company/logo/".md5($_cid).".png";
						if(file_exists($targetPath)){
						  @unlink($targetPath);
						}*/

						$ttmp_company=md5($_cid).".png";

						$info = $s3Client->doesObjectExist('datahub360', 'profiles/company/logo/'.$ttmp_company);
						if($info)
						{
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'profiles/company/logo/'.$ttmp_company
							]);
						}


						$result = $s3Client->putObject([
							'Bucket' => 'datahub360',
							'Key'    => 'profiles/company/logo/'.$ttmp_company,
							'SourceFile' => $sourcePath
						]);


						//move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
						/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
						echo "<br/><b>File Name:</b> " . $_FILES["companylogo"]["name"] . "<br>";
						echo "<b>Type:</b> " . $_FILES["companylogo"]["type"] . "<br>";
						echo "<b>Size:</b> " . ($_FILES["companylogo"]["size"] / 1024) . " kB<br>";
						echo "<b>Temp file:</b> " . $_FILES["companylogo"]["tmp_name"] . "<br>";*/
					}else{
						echo json_encode(array("error"=>"Error Occured! Please try after sometime."));
						exit();
					}
				}else{
					echo json_encode(array("error"=>"Error Occured! Please try after sometime."));
					exit();
				}
			}
		}
		else
		{
			$error="Invalid file Size or Type";
			echo json_encode(array("error"=>$error));
			exit();
		}
	}

	//Company Logo Edit Ends
	echo json_encode(array("error"=>"","image"=>$_cuserimage));

	/*if(count($usub_query)){
		$sql='UPDATE user SET '.implode(",",$usub_query).' WHERE id='.$tmp_usr;
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			//if($lastuaffectedID == 1){
				echo json_encode(array("error"=>""));
			//}else{
			//	echo json_encode(array("error"=>$error));
			//}
		}else{
			echo json_encode(array("error"=>$error."234"));
		}
		exit();
	}*/
}

//Add New User
if(isset($user_one) and isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 5) and $user_one != "" and $user_one != 0 and isset($_POST["new"]) and $_POST["new"]=="new")
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);


	$error="Error occured";
	$sub_query=$usub_query=array();
	$_cid=$fname="";

	if(isset($_POST['email']) and @trim($_POST['email']) != "")
	{
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Not a valid email
			echo json_encode(array('error'=>'The email address you entered is not valid'));
			exit();
		}else{
			$stmtsk = $mysqli->prepare('SELECT user_id FROM user where email="'.@strtolower(@trim($email)).'" LIMIT 1');

//('SELECT id FROM user where email="'.$email.'" LIMIT 1');

			if($stmtsk){
				$stmtsk->execute();
				$stmtsk->store_result();
				if ($stmtsk->num_rows == 0)
				{
					$sub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
					$usub_query[]='email="'.$mysqli->real_escape_string(@strtolower(@trim($_POST['email']))).'"';
				}else{
					echo json_encode(array('error'=>200));
					exit();
				}
			}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();
			}
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Email required.'));
		exit();
	}

	if(isset($_POST['password']) and @trim($_POST['password']) != "" and isset($_POST['p']) and @trim($_POST['p']) != "")
	{
		//$usub_query[]='"password"="'.$mysqli->real_escape_string(@trim($_POST['password'])).'"';
		$password = filter_string_polyfill($_POST['p']);
		if (strlen($password) != 128) {
			// The hashed pwd should be 128 characters long.
			// If it's not, something really odd has happened
			echo json_encode(array('error'=>'Invalid password configuration'));
			exit();
		}else{
			$tmp_password=password_generate($password);
			$tmp_=array();
			$tmp_=explode("@@@@",$tmp_password);
			if(count($tmp_))
			{
				//$random_salt=$tmp_[0];
				//$password=$tmp_[1];
				$sub_query[]='password="'.$mysqli->real_escape_string(@trim($tmp_[1])).'",salt="'.$mysqli->real_escape_string(@trim($tmp_[0])).'"';
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
			}
		}
	}



	if(isset($_POST['fname']) and @trim($_POST['fname']) != "")
	{
		$sub_query[]='firstname="'.$mysqli->real_escape_string(@trim($_POST['fname'])).'"';
		$fname=$mysqli->real_escape_string(@trim($_POST['fname']));
	}

	if(isset($_POST['lname']) and @trim($_POST['lname']) != "")
	{
		$sub_query[]='lastname="'.$mysqli->real_escape_string(@trim($_POST['lname'])).'"';
	}

	if(isset($_POST['address']) and @trim($_POST['address']) != "")
	{
		$sub_query[]='address="'.$mysqli->real_escape_string(@trim($_POST['address'])).'"';
	}

	if(isset($_POST['city']) and @trim($_POST['city']) != "")
	{
		$sub_query[]='city="'.$mysqli->real_escape_string(@trim($_POST['city'])).'"';
	}

	if(isset($_POST['state']) and @trim($_POST['state']) != "")
	{
		$sub_query[]='state="'.$mysqli->real_escape_string(@trim($_POST['state'])).'"';
	}

	if(isset($_POST['zip']) and @trim($_POST['zip']) != "")
	{
		$sub_query[]='zip="'.$mysqli->real_escape_string(@trim($_POST['zip'])).'"';
	}

	if(isset($_POST['country']) and @trim($_POST['country']) != "")
	{
		$sub_query[]='country="'.$mysqli->real_escape_string(@trim($_POST['country'])).'"';
	}

	if(isset($_POST['phone']) and @trim($_POST['phone']) != "")
	{
		$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone']))).'"';
	}

	if(isset($_POST['mobile']) and @trim($_POST['mobile']) != "")
	{
		$sub_query[]='mobile="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['mobile']))).'"';
	}

	if(isset($_POST['fax']) and @trim($_POST['fax']) != "")
	{
		$sub_query[]='fax="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['fax']))).'"';
	}

	if(isset($_POST['title']) and @trim($_POST['title']) != "")
	{
		$sub_query[]='title="'.$mysqli->real_escape_string(@trim($_POST['title'])).'"';
	}

	if(isset($_POST['accuviouser']))
	{
		$sub_query[]='accuvio_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['accuviouser']),'e')).'"';
	}

	if(isset($_POST['accuviopass']) and @trim($_POST['accuviopass']) != "")
	{
		$sub_query[]='accuvio_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['accuviopass']),'e')).'"';
	}
	if(isset($_POST['capturisuser']))
	{
		$sub_query[]='capturis_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisuser']),'e')).'"';
	}

	if(isset($_POST['capturispass']) and @trim($_POST['capturispass']) != "")
	{
		$sub_query[]='capturis_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturispass']),'e')).'"';
	}

	if(isset($_POST['capturisarchiveuser']))
	{
		$sub_query[]='capturis_archive_user="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisarchiveuser']),'e')).'"';
	}

	if(isset($_POST['capturisarchivepass']) and @trim($_POST['capturispass']) != "")
	{
		$sub_query[]='capturis_archive_pass="'.$mysqli->real_escape_string(ed_crypt(@trim($_POST['capturisarchivepass']),'e')).'"';
	}

if($_SESSION["group_id"] == 1){
	if(isset($_POST['gender']) and @trim($_POST['gender']) != "" and (@trim($_POST['gender'])=="M" or @trim($_POST['gender'])=="F"))
	{
		$sub_query[]='gender="'.$mysqli->real_escape_string(@trim($_POST['gender'])).'"';
	}

	if(isset($_POST['company']) and @trim($_POST['company']) != "")
	{
		$sub_query[]='company_id="'.$mysqli->real_escape_string(@trim($_POST['company'])).'"';
	}

	if(isset($_POST['disabledate']) and @trim($_POST['disabledate']) != "")
	{
		$sub_query[]='disable_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['disabledate'])))).'"';
	}

	if(isset($_POST['usergroups']) and @trim($_POST['usergroups']) != "")
	{
		$sub_query[]='usergroups_id="'.$mysqli->real_escape_string(@trim($_POST['usergroups'])).'"';
	}

	if(isset($_POST['notes']))
	{
		$sub_query[]='notes="'.$mysqli->real_escape_string(@trim($_POST['notes'])).'"';
	}
}elseif($_SESSION["group_id"] == 5){
	$sub_query[]='usergroups_id="3"';

		if ($stmt = $mysqli->prepare('SELECT company_id FROM user where (usergroups_id = 5 OR usergroups_id = 3) and user_id="'.$user_one.'" LIMIT 1')) {

//('SELECT company_id FROM user where (usergroups_id = 5 OR usergroups_id = 3) and id="'.$user_one.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($company_id);
				$stmt->fetch();
				$sub_query[]='company_id="'.$mysqli->real_escape_string($company_id).'"';
				$_cid=$company_id;
			}else{echo json_encode(array("error"=>"Error: No Company Found!"));exit();}
		}else die("Error occured. Please try after sometime!");
}
	if(isset($_POST['status']) and @trim($_POST['status']) != "")
	{
		$sub_query[]='status="'.$mysqli->real_escape_string(@trim($_POST['status'])).'"';
	}


	if(count($sub_query)){
		$sql='INSERT INTO user SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){
						//File Edit

						if(isset($_FILES["file"]["type"]))
						{
							$validextensions = array("jpeg", "jpg", "png");
							$temporary = explode(".", $_FILES["file"]["name"]);
							$file_extension = end($temporary);
							if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
							) && ($_FILES["file"]["size"] < 10000000)//Approx. 100kb files can be uploaded i.e. 100000.
							&& in_array($file_extension, $validextensions)) {
								if ($_FILES["file"]["error"] > 0)
								{
									$error=$_FILES["file"]["error"];
									echo json_encode(array("error"=>$error));
									exit();
								}
								else
								{
									$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
									//$targetPath = "upload/".$__username; // Target path where file is to be stored


									$ttmp_usr=md5($insertid).".png";

									$info = $s3Client->doesObjectExist('datahub360', 'profiles/users/profile image/'.$ttmp_usr);
									if($info)
									{
										$s3Client->deleteObject([
											'Bucket' => 'datahub360',
											'Key'    => 'profiles/users/profile image/'.$ttmp_usr
										]);
									}


									$result = $s3Client->putObject([
										'Bucket' => 'datahub360',
										'Key'    => 'profiles/users/profile image/'.$ttmp_usr,
										'SourceFile' => $sourcePath
									]);

									/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/users/profile image/".md5($insertid).".png";
									if(file_exists($targetPath)){
									  @unlink($targetPath);
									}

									move_uploaded_file($sourcePath,$targetPath) ; */// Moving Uploaded file
									/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
									echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
									echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
									echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
									echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
								}
							}
							else
							{
								$error="Invalid file Size or Type";
								echo json_encode(array("error"=>$error));
								$sql='DELETE FROM user WHERE user_id='.$insertid;
								$stmt = $mysqli->prepare($sql);
								$stmt->execute();
							}
						}

						//File Edit Ends

						//Company Logo File Edit Starts
						if(isset($_FILES["companylogo"]["type"]))
						{
							$validextensions = array("jpeg", "jpg", "png");
							$temporary = explode(".", $_FILES["companylogo"]["name"]);
							$file_extension = end($temporary);
							if ((($_FILES["companylogo"]["type"] == "image/png") || ($_FILES["companylogo"]["type"] == "image/jpg") || ($_FILES["companylogo"]["type"] == "image/jpeg")
							) && ($_FILES["companylogo"]["size"] < 10000000)//Approx. 100kb files can be uploaded i.e. 100000.
							&& in_array($file_extension, $validextensions)) {
								if ($_FILES["companylogo"]["error"] > 0)
								{
									$error=$_FILES["companylogo"]["error"];
									echo json_encode(array("error"=>$error));
									exit();
								}
								else
								{
									$sourcePath = $_FILES['companylogo']['tmp_name']; // Storing source path of the file in a variable
									//$targetPath = "upload/".$__username; // Target path where file is to be stored
									if(isset($_POST['company']) and @trim($_POST['company']) != "" and @trim($_POST['company']) != 0 and @trim($_POST['company']) != '0'){
										$_cid=@trim($_POST['company']);

										$ttmp_cmp=md5($_cid).".png";

										$info = $s3Client->doesObjectExist('datahub360', 'profiles/company/logo/'.$ttmp_cmp);
										if($info)
										{
											$s3Client->deleteObject([
												'Bucket' => 'datahub360',
												'Key'    => 'profiles/company/logo/'.$ttmp_cmp
											]);
										}


										$result = $s3Client->putObject([
											'Bucket' => 'datahub360',
											'Key'    => 'profiles/company/logo/'.$ttmp_cmp,
											'SourceFile' => $sourcePath
										]);

										/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/company/logo/".md5($_cid).".png";
										if(file_exists($targetPath)){
										  @unlink($targetPath);
										}

										move_uploaded_file($sourcePath,$targetPath) ;*/ // Moving Uploaded file
									}elseif($_SESSION["group_id"] == 5 and $_cid != ""){
										$ttmp_cmp=md5($_cid).".png";

										$info = $s3Client->doesObjectExist('datahub360', 'profiles/company/logo/'.$ttmp_cmp);
										if($info)
										{
											$s3Client->deleteObject([
												'Bucket' => 'datahub360',
												'Key'    => 'profiles/company/logo/'.$ttmp_cmp
											]);
										}


										$result = $s3Client->putObject([
											'Bucket' => 'datahub360',
											'Key'    => 'profiles/company/logo/'.$ttmp_cmp,
											'SourceFile' => $sourcePath
										]);



										/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/company/logo/".md5($_cid).".png";
										if(file_exists($targetPath)){
										  @unlink($targetPath);
										}

										move_uploaded_file($sourcePath,$targetPath) ;*/
									}else
									{
										$error="Company not selected!";
										echo json_encode(array("error"=>$error));
										$sql='DELETE FROM user WHERE user_id='.$insertid;
										$stmt = $mysqli->prepare($sql);
										$stmt->execute();
										exit();
									}
									/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
									echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
									echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
									echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
									echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
								}
							}
							else
							{
								$error="Invalid file Size or Type";
								echo json_encode(array("error"=>$error));
								$sql='DELETE FROM user WHERE user_id='.$insertid;
								$stmt = $mysqli->prepare($sql);
								$stmt->execute();
								exit();
							}
						}

						//Company Logo File Edit Ends

						echo json_encode(array("error"=>""));

						if(isset($_POST['password']) and @trim($_POST['password']) != "" and isset($_SESSION['email'])){
							//$message="Hello,<br><br>For Vervantis user email: ".$email." the temporary password is : ".$password."	   <br><br><br> For any queiries please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team";
							$message="A new user has been created.<br><br>Username: ".$email."<br>Temporary password: ".$_POST['password']."<br><br>Thank you,<br><br>Vervantis Support<br>Email: support@vervantis.com<br>Phone: (480) 550-9225.";
							//fireamail($_SESSION['email'],"Temporary new password for Vervantis user: ".$email."",$message,"","N");

							$mailArgs =  array('subject' => 'Temporary new password for Vervantis user: '.$email,
									'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
									'toRecipients' => array( array('name' => '', 'address' => $_SESSION['email']) ),
									'ccRecipients' => array(),
									'importance' => 'normal',
									'conversationId' => '',
									'body' => $message,
									'images' => array(),
									'attachments' => array( )
								);

							custommsmail('noreply@vervantis.com', $mailArgs,'');

							/*$message="Hello,<br><br>Your temporary new password for Vervantis: ".$_POST['password']."	   <br><br><br> For any queiries please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team";
							//fireamail("support","Your temporary new password for Vervantis",$message,"","N");

							$mailArgs =  array('subject' => 'Your temporary new password for Vervantis',
									'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
									'toRecipients' => array( array('name' => '', 'address' => $email) ),
									'ccRecipients' => array(),
									'importance' => 'normal',
									'conversationId' => '',
									'body' => $message,
									'images' => array(),
									'attachments' => array( )
								);

							custommsmail('noreply@vervantis.com', $mailArgs,'');*/


							$message="Hello ".@ucfirst(@trim($fname)).",<br><br>Your temporary password for the Vervantis DataHub360 portal is: ".$_POST['password']."<br>Username: ".$email."<br>You can access the portal here: https://portal.vervantis.com	 <br>Please contact the Vervantis Support Team should you have any questions. <br><br><br>Thank you,<br>Vervantis Support Team<br>Email: support@vervantis.com<br>Phone: (480) 550-9225";
							//fireamail("support","Your temporary new password for Vervantis",$message,"","N");

							$mailArgs =  array('subject' => 'New Vervantis DataHub360 login credentials',
									'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
									'toRecipients' => array( array('name' => '', 'address' => $email) ),
									'ccRecipients' => array(),
									'importance' => 'normal',
									'conversationId' => '',
									'body' => $message,
									'images' => array(),
									'attachments' => array( )
								);

							custommsmail('noreply@vervantis.com', $mailArgs,'');
						}

						exit();
					//if($lastaffectedID == 1){


						//echo json_encode(array("error"=>""));
					//}else
						//echo json_encode(array("error"=>$error));

					//exit();
			}else{
				echo json_encode(array("error"=>$error));
			}
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}
//Add user ends

//Change new password
if(isset($user_one) and isset($_SESSION["group_id"]) and $user_one != "" and $user_one != 0 and isset($_POST["newpwdchange"]))
{
	$newpassword=$mysqli->real_escape_string(@trim($_POST['newpwdchange']));
	if($_POST["newpwdchange"] == ""){
		echo json_encode(array('error'=>'Error Occured! Password incorrect.'));
		exit();
	}

	$stmtsk = $mysqli->prepare('SELECT salt FROM user where user_id="'.$user_one.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows == 0)
		{
			echo json_encode(array('error'=>'Error Occured! Email already exist.'));
			exit();
		}
		$stmtsk->bind_result($oldsalt);
		$stmtsk->fetch();
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
	}




	$error="Error occured";



	if(isset($_POST['newpwdchange']) and @trim($_POST['newpwdchange']) != "" and isset($_POST['p']) and @trim($_POST['p']) != "")
	{
		//$usub_query[]='"password"="'.$mysqli->real_escape_string(@trim($_POST['password'])).'"';
		$password = filter_string_polyfill($_POST['p']);
		if (strlen($password) != 128) {
			// The hashed pwd should be 128 characters long.
			// If it's not, something really odd has happened
			echo json_encode(array('error'=>'Invalid password configuration'));
			exit();
		}else{
			$oldpassword = hash('sha512', $password . $oldsalt);
			$stmtchkexist = $mysqli->prepare('SELECT user_id FROM user_tracking where user_id="'.$user_one.'" and password="'.$mysqli->real_escape_string($oldpassword).'" LIMIT 1');

			if($stmtchkexist){
				$stmtchkexist->execute();
				$stmtchkexist->store_result();
				$stmtchkexist->bind_result($useridd);
				$stmtchkexist->fetch();

			if ($stmtchkexist->num_rows > 0) {
					echo json_encode(array('error'=>6));
					exit();
				}
			}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();
			}




			$tmp_password=password_generate($password);
			$tmp_=array();
			$tmp_=explode("@@@@",$tmp_password);
			if(count($tmp_))
			{
				$sql='UPDATE user SET password="'.$mysqli->real_escape_string(@trim($tmp_[1])).'",salt="'.$mysqli->real_escape_string(@trim($tmp_[0])).'",password_change_date=now() WHERE user_id='.$user_one;
				$stmt = $mysqli->prepare($sql);
				if($stmt)
				{
					$stmt->execute();
					//$lastaffectedID=$stmt->affected_rows;
					$_SESSION['newuser'] = 'No';
					$_SESSION['login_string'] = hash('sha512', $password . $_SESSION['user_browser']);
					echo json_encode(array('error'=>''));


					$message="Hello,<br><br>Your password for Vervantis changed successfully.<br><br><br>If you did not request a password reset or you feel that you’ve received this email in error, please contact Vervantis at support@vervantis.com or (480) 550-9225.<br><br>Thank you,<br>Vervantis Support Team";
					//fireamail($_SESSION['email'],"Password changed successfully.",$message);

						$mailArgs =  array('subject' => 'Password changed successfully.',
								'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
								'toRecipients' => array( array('name' => '', 'address' => $_SESSION['email']) ),
								'ccRecipients' => array(),
								'importance' => 'normal',
								'conversationId' => '',
								'body' => $message,
								'images' => array(),
								'attachments' => array( )
							);

						custommsmail('noreply@vervantis.com', $mailArgs,'');
					exit();
				}else{
					echo json_encode(array("error"=>$error));
					exit();
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
			}
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Error in Request.'));
		exit();
	}

	echo json_encode(array("error"=>$error));
	exit();
}

//Delete User
if(isset($_SESSION["group_id"]) and $_SESSION["group_id"] == 1 and isset($_POST["usr"]) and isset($_POST["action"]) and $_POST["action"]=="delete")
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);

	$error="Error occured";
	$sub_query=$usub_query=array();

	$usr=$mysqli->real_escape_string(@trim($_POST["usr"]));
	if($usr == 1)
	{
		echo json_encode(array('error'=>'Error Occured! Administrator deletion is not permitted.'));
		exit();
	}


	$stmtsk = $mysqli->prepare('SELECT user_id FROM user where user_id="'.$usr.'" LIMIT 1');

//('SELECT id FROM user where id="'.$usr.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
				$results = $mysqli->query('DELETE FROM user where user_id="'.$usr.'"');
				if($mysqli->affected_rows > 0){
					echo json_encode(array('error'=>''));


					$stmtsk = $mysqli->prepare('SELECT id FROM users_interface where user_id="'.$usr.'" LIMIT 1');
					if($stmtsk){
						$stmtsk->execute();
						$stmtsk->store_result();
						if ($stmtsk->num_rows > 0)
							$mysqli->query('DELETE FROM users_interface where user_id="'.$usr.'"');
					}

					$stmtsk = $mysqli->prepare('SELECT id FROM chat where user_one="'.$usr.'" or user_two="'.$usr.'" LIMIT 1');
					if($stmtsk){
						$stmtsk->execute();
						$stmtsk->store_result();
						if ($stmtsk->num_rows > 0)
							$mysqli->query('DELETE FROM chat where user_one="'.$usr.'" or user_two="'.$usr.'"');
					}

					$stmtsk = $mysqli->prepare('SELECT id FROM blog_posts where user_id="'.$usr.'" LIMIT 1');
					if($stmtsk){
						$stmtsk->execute();
						$stmtsk->store_result();
						if ($stmtsk->num_rows > 0)
						{
							$stmtsk->bind_result($_id);
							while($stmtsk->fetch())
								$mysqli->query('DELETE FROM threaded_comments where blog_posts_id="'.$_id.'"');

							$mysqli->query('DELETE FROM blog_posts where user_id="'.$usr.'"');
							//$mysqli->query('DELETE FROM threaded_comments where user_id="'.$usr.'"');
						}
					}

					exit();
				}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();
				}

		}else{
			echo json_encode(array('error'=>'Error Occured! User doesn\'t exists.'));
			exit();
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error2.'));
			exit();
	}
}
//Delete user ends

//Add Profile Banner
if(isset($_POST["usr"]) and $_POST["usr"] != "" and $_POST["usr"] != 0 and isset($_POST["banner-edit"]) and isset($_POST["banner-name"]))
{



	$error="Error occured";
	$sub_query=$usub_query=array();

	$tmp_usr=$mysqli->real_escape_string(@trim($_POST["usr"]));
	$banner=$mysqli->real_escape_string(@trim($_POST["banner-name"]));

   if ($stmt = $mysqli->prepare('SELECT user_id FROM `user` where user_id="'.$tmp_usr.'" LIMIT 1')) {

//('SELECT id FROM `user` where id="'.$tmp_usr.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			echo json_encode(array('error'=>'Error Occured! User doesnot exist.'));
			exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Database error.'));
		exit();
	}

	$sql='UPDATE user SET banner="'.$banner.'" WHERE user_id='.$tmp_usr;
	$stmtt = $mysqli->prepare($sql);
	if($stmtt)
	{
		$stmtt->execute();
		$lastaffectedID=$stmtt->affected_rows;
		echo json_encode(array("error"=>""));
		exit();
	}else{
		echo json_encode(array("error"=>$error));
		exit();
	}

	echo json_encode(array("error"=>$error));
	exit();



}
//Add Profile Banner Ends

//print_r($_POST);
echo false;
exit();

function checks3img($keyname,$foldername="",$noimage=""){
	global $s3Client;
	if($foldername=="") return false;
	$keyname=@trim($keyname);
	$infotarget = $s3Client->doesObjectExist('datahub360', $foldername.$keyname);
	if($keyname != "" and $infotarget)
	{
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => 'datahub360',
			'Key'    => $foldername.$keyname
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+3 minutes');
		return (string) $request->getUri();
	}elseif($noimage !=""){
		$infotarget = $s3Client->doesObjectExist('datahub360',$foldername.$noimage);
		if($infotarget)
		{
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.$noimage
			]);

			$request = $s3Client->createPresignedRequest($cmd, '+2 minutes');
			return (string) $request->getUri();
		}else{
			return false;
			//logoff
		}
	}else{
		return false;
		//logoff
	}
}

function password_generate($password){
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        // Create salted password
        $password = hash('sha512', $password . $random_salt);
		return $random_salt."@@@@".$password;
}
?>
