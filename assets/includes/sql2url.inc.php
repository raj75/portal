<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

ini_set('memory_limit', -1);

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

error_reporting(0);
ini_set('max_execution_time', 0);

//$s3folder="datahub360-temp24hours";
$s3folder="datahub360-tempdownloads";

if($_SESSION["group_id"] != 1) die(false);
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	//die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];

$profile = 'default';
$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => [
			 'key' => $_ENV['aws_access_key_id'],
			 'secret' => $_ENV['aws_secret_access_key']
	 ]
]);

function sql2url($sqlquery){
	global $user_one;
	global $group_id;
	global $cname;
	global $s3folder;
	global $s3Client;
	global $mysqli;

	if(!empty(@trim($sqlquery))){
		$sqlquery=@trim($sqlquery);
		//if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;



	//ob_start();
		$result = $mysqli -> query($sqlquery);
		if(!$result) return false;
		if($result->num_rows == 0) return false;
		$file_name="f_".$user_one."_".$cname."_".uniqid().".csv";
		$path=realpath(__DIR__)."/../uploads/";



		$fp = fopen($path.$file_name, 'wb');

		while($row = $result -> fetch_assoc()){
			fputcsv($fp, $row);
		}

		fclose($fp);

		$result -> free_result();

		$infotaget = $s3Client->doesObjectExist($s3folder, $file_name);
		if(!$infotaget)
		{
			$s3Client->putObject([
				'Bucket' => $s3folder,
				'Key'    => $file_name,
				'SourceFile' => $path.$file_name
			]);

			if(file_exists($path.$file_name))
	    {
	        $status  = unlink($path.$file_name) ? 'The file '.$filename.' has been deleted' : 'Error deleting '.$filename;
	        //echo $status;
	    }

			$info = $s3Client->doesObjectExist($s3folder, $file_name);
			if($info)
			{
				$cmd = $s3Client->getCommand('GetObject', [
					'Bucket' => $s3folder,
					'Key'    => $file_name
				]);

				$request = $s3Client->createPresignedRequest($cmd, '+24 hours');

				//ob_end_clean();

				//header('Content-Type: application/octet-stream');
				//header('Content-Disposition: attachment; filename='.$file_name);
				//readfile($presignedUrl = (string) $request->getUri());
				return (string) $request->getUri();
				exit();
			}
		}
		return false;
	}else{return false;exit();}
}
?>
