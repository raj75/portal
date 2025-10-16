<<<<<<< HEAD
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



if(isset($_POST['sqlquery']) and !empty(@trim($_POST['sqlquery']))){
	$sqlquery=@trim($_POST['sqlquery']);
	//if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;



//ob_start();
	$result = $mysqli -> query($sqlquery);
	if(!$result){ echo false;exit(); }
	if($result->num_rows == 0){ echo false;exit(); }
	$profile = 'default';

	$file_name="f_".$user_one."_".$cname."_".uniqid().".csv";
	$path=realpath(__DIR__)."/../uploads/";

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);

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
			echo (string) $request->getUri();
			exit();
		}
	}

}else{echo false;exit();}
?>
=======
<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

$_POST['sqlquery'] = "select * from adhoc";

ini_set('memory_limit', -1);

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

//$s3folder="datahub360-temp24hours";
$s3folder="datahub360-tempdownloads";

//if($_SESSION["group_id"] != 1) die(false);
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	//die(false);



//$user_one=$_SESSION['user_id'];
//$group_id=$_SESSION['group_id'];
//$cname=$_SESSION['company_id'];

$user_one=111;
$group_id=222;
$cname=333;


//$_POST['sqlquery']="select * from weather";
if(isset($_POST['sqlquery']) and !empty(@trim($_POST['sqlquery']))){
	$sqlquery=@trim($_POST['sqlquery']);
	//if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

//ob_start();
	/*$resultres = $mysqli -> query($sqlquery);
	if(!$resultres){ echo false;exit(); }
	if($resultres->num_rows == 0){ echo false;exit(); }*/
	$profile = 'default';

	$file_name="f_".$user_one."_".$cname."_".uniqid().".csv";
	$path=realpath(__DIR__)."/../uploads/";
	$path=sys_get_temp_dir()."/";
	$filePath=$path . $file_name;

	$fp = fopen($path.$file_name, 'wb');
	if (!file_exists($path . $file_name)){ echo false;exit(); }
	$chunkSize = 1500; // Adjust this to your desired chunk size
 	$offset = 0;
	$runct=0;
	do {
		//$resultres = mysqli_query($conn, $query." LIMIT $chunkSize OFFSET $offset");
		$resultres = $mysqli -> query($sqlquery." LIMIT $chunkSize OFFSET $offset");
		if(!$resultres){ echo false;exit(); }
		//if(mysqli_num_rows($resultres) == 0){ echo false;exit(); }
		if($resultres->num_rows == 0){$runct=1; break; }

		//while($row = mysqli_fetch_assoc($resultres)) {
		while($rows = $resultres -> fetch_assoc()){
			 fputcsv($fp, $rows);
		}

		 $resultres=null;

		 $offset += $chunkSize;
	} while ($runct==0 );

	$rows=null;
	// Close the file
	fclose($fp);




	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);

	$infotaget = $s3Client->doesObjectExist($s3folder, $file_name);
	if(!$infotaget)
	{
				$uploadId = null;
				try {
				    $result = $s3Client->createMultipartUpload([
				        'Bucket' => $s3folder,
				        'Key' => $file_name,
				    ]);
				    $uploadId = $result['UploadId'];
				} catch (AwsException $e) {
						$success=4;
						echo false;
				    //echo "Error initiating multipart upload: " . $e->getMessage();
				    exit();
				}


				//$fp = fopen($path.$file_name, 'wb');
				$partSize = 5 * 1024 * 1024; // 5 MB part size (adjust as needed)
				$handle = fopen($filePath, 'rb');
				$partNumber = 1;
				$parts = [];

				while (!feof($handle)) {
				    $part = fread($handle, $partSize);
				    try {
				        $result = $s3Client->uploadPart([
				            'Bucket' => $s3folder,
				            'Key' => $file_name,
				            'UploadId' => $uploadId,
				            'PartNumber' => $partNumber,
				            'Body' => $part,
				        ]);
				        $parts[] = [
				            'PartNumber' => $partNumber,
				            'ETag' => $result['ETag'],
				        ];
				    } catch (AwsException $e) {


				        echo "Error uploading part {$partNumber}: " . $e->getMessage();
				        exit();
				    }
				    $partNumber++;
				}

				try {
				    $result = $s3Client->completeMultipartUpload([
				        'Bucket' => $s3folder,
				        'Key' => $file_name,
				        'UploadId' => $uploadId,
				        'MultipartUpload' => [
				            'Parts' => $parts,
				        ],
				    ]);
						$success=1;
				    //echo "File uploaded successfully!";
				} catch (AwsException $e) {
						$success=2;
				    echo "Error completing multipart upload: " . $e->getMessage();
				}

				//fclose($fp);

				$resultres -> free_result();

		if(file_exists($path.$file_name))
    {
        $status  = unlink($path.$file_name) ? 'The file '.$file_name.' has been deleted' : 'Error deleting '.$file_name;
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
			echo (string) $request->getUri();
			exit();
		}
	}

}else{echo false;exit();}
?>
>>>>>>> 1b5b8c15 (sql2link file uploaded)
