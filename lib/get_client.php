<?php
    $ssroot=$_SERVER["DOCUMENT_ROOT"];
    require_once $ssroot.'/lib/s3/aws-autoloader.php';

    if(!isset($dotenv)){
    	require_once realpath($ssroot . '/assets/plugins/env/autoload.php');
    	$dotenv = Dotenv\Dotenv::createImmutable($ssroot.'/');
    	$dotenv->load();
    }

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
