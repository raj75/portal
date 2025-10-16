<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

require '../../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$profile = 'default';

$s3Client = new S3Client([
    'region'      => 'us-west-2',
    'version'     => 'latest',
    'credentials' => [
         'key' => $_ENV['aws_access_key_id'],
         'secret' => $_ENV['aws_secret_access_key']
     ]
]);

function getpresigned($keyname){
  global $s3Client;
  $info = $s3Client->doesObjectExist('datahub360-powergeneration', $keyname);
  if($info)
  {
    $cmd = $s3Client->getCommand('GetObject', [
      'Bucket' => 'datahub360-powergeneration',
      'Key'    => $keyname
    ]);

    $request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
    return $presignedUrl = (string) $request->getUri();
    exit();
  }
  return false;
}

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2 or $_SESSION['group_id'] != 3 or $_SESSION['group_id'] != 5))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");



if(isset($_GET["fname"]) and !empty($_GET["fname"]) and ($_GET["fname"]=="us-energy.geojson" or $_GET["fname"]=="us-energy-totals.csv" or $_GET["fname"]=="LowCarbonPercentage.csv")){
  $filedata=file_get_contents(getpresigned($_GET["fname"]));
  if($filedata !== false){
    echo $filedata;
  }
}


?>
