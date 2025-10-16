<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
set_time_limit(0);

//$_POST['sqlquery'] = "select * from adhoc";

ini_set('memory_limit', -1);

require_once '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

//$s3folder="datahub360-temp24hours";
$s3folder="datahub360-tempdownloads";

//if($_SESSION["group_id"] != 1) die(false);
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	//die(false);

if(!isset($_SESSION['user_id'])) die(false);
if(!isset($_SESSION['dt_query'])) die(false);
if(!isset($_POST['export'])) die(false);
$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];

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


$export=$_POST['export'];

if ( $export=='csvall' ) {
	
	$exporttype = "csv";
	$sql_query = current( explode("WHERE", $_SESSION['dt_query'] ));
	
} else if ( $export=='csvfilter' ) {
	
	$exporttype = "csv";
	$sql_query = $_SESSION['dt_query'];		
	
} else if ( $export=='excelall' ) {
	
	$exporttype = "excel";
	$sql_query = current( explode("WHERE", $_SESSION['dt_query'] ));
	
} else if ( $export=='excelfilter' ) {
	
	$exporttype = "excel";
	
	$sql_query = $_SESSION['dt_query'];
	
}else die(false);

$row_cnt=0;

if(preg_match("/(.*?)(from[^~]+)/is",$sql_query,$sqlarr)){
	array_shift($sqlarr);
	$newsql= "select 1 ". @trim($sqlarr[1])." LIMIT 1000000,1";
	try {
		$resultres = $mysqli -> query($newsql);
	}
	catch(Exception $e) {
		return false;exit();
	}
	if(!$resultres){ echo false;exit(); }
	
	$row_cnt = $resultres->num_rows;
	
	$resultres->free();

	if($row_cnt >= 1) {echo json_encode(["error"=>99,"url"=>""]);die(); }
	//if($row_cnt == 0) {echo json_encode(["error"=>88,"url"=>""]);die(); }
}else {die(false); };

$response=generatecsv($sql_query);//die();
if(preg_match("/^https[^~]+(amazonaws)/s",$response,$nosave)){
	$returnArr = ["error"=>"","url"=>$response];    
	echo json_encode($returnArr);die();	
}else{
	$returnArr = ["error"=>"yes","url"=>$response];    
	echo json_encode($returnArr);die();	
}

die(false);

function generatecsv($sqlquery){
	global $mysqli;
	global $s3Client;
	global $user_one;
	global $group_id;
	global $cname;
	global $s3folder;

	if(empty(@trim($sqlquery))) return 6;//die($sqlquery);
	
	$file_name="a_".$user_one."_".$cname."_".uniqid().".csv";
	$infotaget = $s3Client->doesObjectExist($s3folder, $file_name);
	if($infotaget)
	{
		$file_name="a_".$user_one."_".$cname."_".uniqid().".csv";
	}
	
	if(preg_match("/(.*?)(from[^~]+)/is",$sqlquery,$sqlarr)){
		array_shift($sqlarr);
		$newsql= @trim($sqlarr[0])."
		INTO OUTFILE S3 's3://".$s3folder."/".$file_name."'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' 
". @trim($sqlarr[1])." LIMIT 0,1000000";
	try {
		$resultres = $mysqli -> query($newsql);
	}
	catch(Exception $e) {
		return false;exit();
	}
		if(!$resultres){ echo false;exit(); }

	}else return 7;
	
	$rawfile_name=$file_name.".part_00000";
	$infotaget = $s3Client->doesObjectExist($s3folder, $rawfile_name);
	if($infotaget)
	{
		$s3Client->copyObject([
			'Key' => $file_name,
			'Bucket' =>  $s3folder,
			'CopySource' => $s3folder.'/'.$rawfile_name,
		]);
		
		$s3Client->deleteObject([
				'Bucket' => $s3folder,
				'Key'    => $rawfile_name
		]);	

		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => $s3folder,
			'Key'    => $file_name,
			'ResponseContentDisposition' =>  'attachment; filename="download.csv"',
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+45 minutes');
		return $presignedUrl = (string) $request->getUri();
		exit();
	}else return 8;
	
}

die();


$result_check = mysqli_query($conn, "SELECT *
INTO OUTFILE S3 's3://datahub360-tempdownloads/exported-data1.csv'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM adhoc LIMIT 1000000");
if(!$result_check) throwerror("Sql Query Failed for post_query list Error:".mysqli_error($conn),"null");
die();
?>
