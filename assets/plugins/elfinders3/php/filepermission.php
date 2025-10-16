<?php
$sroot=$_SERVER["DOCUMENT_ROOT"];
require_once $sroot.'/lib/s3/aws-autoloader.php';

if(!isset($dotenv)){
	require_once realpath($sroot . '/assets/plugins/env/autoload.php');
	$dotenv = Dotenv\Dotenv::createImmutable($sroot.'/');
	$dotenv->load();
}

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
if(isset($_POST) and isset($_POST["url"]) and isset($_POST["role"]) and $_POST["url"] != "" and $_POST["role"] != ""){
$keyname=".".urldecode(parse_url($_POST["url"], PHP_URL_PATH));
$bucket='datahub360';
//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

    $profile = 'default';

    $s3Client = new S3Client([
        'region'      => 'us-west-2',
        'version'     => 'latest',
        'credentials' => [
             'key' => $_ENV['aws_access_key_id'],
             'secret' => $_ENV['aws_secret_access_key']
         ]
    ]);


$info = $s3Client->doesObjectExist($bucket, $keyname);
if(!$info)
{
	return false;
	exit();
}
$cmd = $s3Client->getCommand('GetObject', [
    'Bucket' => $bucket,
    'Key'    => $keyname
]);

$request = $s3Client->createPresignedRequest($cmd, '+1 minutes');
echo $presignedUrl = (string) $request->getUri();


/**
  * Create a link to a S3 object from a bucket. If expiration is not empty, then it is used to create
  * a signed URL
  *
  * @param  string     $object The object name (full path)
  * @param  string     $bucket The bucket name
  * @param  string|int $expiration The Unix timestamp to expire at or a string that can be evaluated by strtotime
  * @throws InvalidDomainNameException
  * @return string
  */
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
}else echo false;
?>
