<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
set_time_limit(0);

require '../../lib/s3/aws-autoloader.php';

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

if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 4 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];


	//print_r($_POST);
	//print_r($_FILES);
	//print_r($_REQUEST);
/*
    [upload] => Array
        (
            [name] => 02e74f10e0327ad868d138f2b4fdd6f0.png
            [type] => image/png
            [tmp_name] => /tmp/phpPeCHCo
            [error] => 0
            [size] => 82645
        )
https://develop.vervantis.com/assets/includes/ckeditors3connector.php?command=QuickUpload&type=Images&page=mn&CKEditor=new-postm&CKEditorFuncNum=2&langCode=en
<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(2, 'https://datahub360.s3.us-west-2.amazonaws.com/market%20news/0.76762100%2015610510107988.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAIDRTDHNTGQ4TLMYA%2F20190620%2Fus-west-2%2Fs3%2Faws4_request&X-Amz-Date=20190620T171650Z&X-Amz-SignedHeaders=host&X-Amz-Expires=60&X-Amz-Signature=bf4b6f275dd3afa5510281837ba405ff585bf4a12e08104c1d5a156f9ce6a894', '');</script>
*/

	if(isset($_GET["command"]) and  isset($_FILES) and isset($_FILES["upload"]) and $_FILES["upload"]["error"] ==0 and	$_GET["type"] == "Images" and isset($_GET["CKEditor"]) and isset($_GET["page"]) and ($_GET["page"] == "mn" or $_GET["page"] == "b"))
	{
		if($_GET["page"]=="mn") $foldername="market news";
		else if($_GET["page"]=="b") $foldername="blog";
		else{
			echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(2, '', 'Error Occured. Please try after sometimes.1');</script>";
			exit();
		}

		if($_GET["CKEditor"] == "edit-post86"){
			$s3Client->deleteObject([
				'Bucket' => 'datahub360',
				'Key'    => $foldername.'/'.$_FILES["upload"]["name"]
			]);
		}

		$target_filename = microtime().mt_rand(1000,10000).".png";

		$result = $s3Client->putObject([
			'Bucket' => 'datahub360',
			'Key'    => $foldername.'/'.$target_filename,
			'SourceFile' => $_FILES['upload']['tmp_name']
		]);

		if($result){
			$cmd = $s3Client->getCommand('GetObject', [
				'Bucket' => 'datahub360',
				'Key'    => $foldername.'/'.$target_filename
			]);
			$request = $s3Client->createPresignedRequest($cmd, '+6 minutes');
			$presignedUrl = (string) $request->getUri();

			echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(2, '".$presignedUrl."', '');</script>";
		}else{
			echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(2, '', 'Error Occured. Please try after sometimes.2');</script>";
			exit();
		}
	}else{
			echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(2, '', 'Error Occured. Please try after sometimes.3');</script>";
			exit();
	}
}else{
	echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(2, '', 'Error Occured. Please try after sometimes.4');</script>";
	exit();
}

function getpresignedurl($object, $bucket = '', $expiration = '')
{
 $bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
 if (empty($bucket)) {
	 throw new InvalidDomainNameException('An empty bucket name was given');
 }
 if ($expiration) {
	 $command = $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $object]);
	 return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
 } else {
	 return $this->client->getObjectUrl($bucket, $object);
 }
}
?>
