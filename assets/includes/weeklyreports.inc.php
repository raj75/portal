<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

//error_reporting(0);
ini_set('max_execution_time', 0);

require '../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$bucket='datahub360';

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];


if(isset($_POST["action"]) and isset($_POST["pid"])  and !empty($_POST["pid"]))
{

	$error="Error occured";
	$new_value=array();

	$pid = $mysqli->real_escape_string(@trim($_POST["pid"]));


	$profile = 'default';
	//$path = '../../lib/s3/credentials.ini';

	//$provider = CredentialProvider::ini($profile, $path);
	//$provider = CredentialProvider::memoize($provider);

	$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
			'credentials' => [
					 'key' => $_ENV['aws_access_key_id'],
					 'secret' => $_ENV['aws_secret_access_key']
			 ]
	]);

	if ($stmtttt = $mysqli->prepare('Select s3 From weekly_reports Where ID="'.$pid.'" LIMIT 1')) {

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($s3link);
			$stmtttt->fetch();

			$s3link=@str_replace('s3://datahub360/','',$s3link);
			$info = $s3Client->doesObjectExist('datahub360', $s3link);
			if($info)
			{
				$cmd = $s3Client->getCommand('GetObject', [
					'Bucket' => 'datahub360',
					'Key'    => $s3link
				]);

				$request = $s3Client->createPresignedRequest($cmd, '+15 minutes');
				$presignedUrl = (string) $request->getUri();
				echo json_encode(array("data"=>$presignedUrl));
				exit();
			}else{echo false;exit();}
		}else{echo false;exit();}
	}else{echo false;exit();}


/////////////////
	echo json_encode(array("error"=>""));
}

//print_r($_POST);
echo false;
exit();



?>
