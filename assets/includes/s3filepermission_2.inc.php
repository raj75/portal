<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();


require '../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$bucket='datahub360';

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die(false);


if(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "saview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($sa_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

		if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname);
				if($infotarget)
				{
					$fileext=@strtolower(pathinfo($keyname, PATHINFO_EXTENSION));
					$filepath='resources/Clients/'.$company_name.'/saving analysis/'.$keyname;
					$status=2;
					if(empty($fileext)) $status=0;
					if($fileext=="pdf"){
						$status=1;
					}else{
						//$supportfileext=array("odt","csv","db","doc","docx","dotx","fodp","fods","fodt","mml","odb","odf","odg","otp","ots","ott","oxt","pptx","psw","sda","sdc","sdd","sdp","sdw","slk","smf","stc","std","sti","stw","sxc","sxg","sxi","sxm","sxw","uof","uop","uos","uot","vsd","vsdx","wdb","wps","wri","xls","xlsx","dic","doc#","mab","tsv","txtrpt");
						$supportfileext=array("jpeg","png","gif","tiff","bmp","webm","mpeg4","3gpp","mov","avi","mpegps","wmv","flv","txt","css","html","php","c","cpp","h","hpp","js","doc","docx","xls","xlsx","ppt","pptx","pdf","pages","ai","psd","tiff","dxf","svg","eps","ps","ttf","xps","csv");
			      if(in_array($fileext,$supportfileext)){
							$status=8;
						}elseif($s3Client->doesObjectExist('resources/Clients/'.$company_name.'/saving analysis/'.$keyname.'.err')){
							$status=0;
						}
						if($s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname.'.pdf')){
							$filepath='resources/Clients/'.$company_name.'/saving analysis/'.$keyname.'.pdf';
							$status=1;
						}
					}

					if($fileext=="mp4" or $fileext=="ogg" or $fileext=="avi" or $fileext=="flv" or $fileext=="mkv" or $fileext=="mov" or $fileext=="mpeg" or $fileext=="mpg" or $fileext=="m4v" or $fileext=="wmv"){
						$status=5;
					}elseif($fileext=="mp3" or $fileext=="m4a" or $fileext=="mp2" or $fileext=="m3u" or $fileext=="wma" or $fileext=="m4a" or $fileext=="m4a"){
						$status=6;
					}elseif($fileext=="jpg" or $fileext=="jpeg" or $fileext=="gif" or $fileext=="png" or $fileext=="tif" or $fileext=="bmp" or $fileext=="ico"){
						$status=7;
					}


					$cmd = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => $filepath
					]);

					$cmd1 = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname
					]);

					if($status != 5 and $status != 6){
						$head = $s3Client->headObject(
						 [
						   'Bucket' => 'datahub360',
						   'Key' => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname,
						 ]
						);
						$sizeresult = (int) ($head->get('ContentLength') ?? 0);
						if($sizeresult > 24000000) $status=9;
					}

					$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
					$request1 = $s3Client->createPresignedRequest($cmd1, '+28 minutes');
					$presignedUrl = (string) $request->getUri();
					$presignedUrl1 = (string) $request1->getUri();
					if(preg_match("/X-Amz-Algorithm=([^\s]+)/s",$presignedUrl1,$tmpurl)){
						$presignedUrl1=$tmpurl[1];
						$tmpurl=null;
					}
					echo json_encode(array("status"=>$status,"presignedurl"=>urlencode($presignedUrl),"oripresignedurl"=>urlencode($presignedUrl1),"name"=>$keyname));
					exit();
				}else{echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}

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




}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sadelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($sa_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();


			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname);
				if($info)
				{
					// Delete an object from the bucket.
					$s3Client->deleteObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname
					]);

					$files_list=array_diff($files_list, [$keyname]);
					if ($stmtupdate = $mysqli->prepare("UPDATE saving_analysis SET link='".$mysqli->real_escape_string(@implode("@@;@@",$files_list))."' WHERE  id='".$s3sid."'")) {

						$stmtupdate->execute();
						if($stmtupdate->affected_rows == 1){

						}else{
							return false;
							//$cncerror="error";
							//break;
						}
					}

					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "safiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($sa_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname);
				if($info)
				{
					$updateResponse = $s3Client->copyObject([
						'Key' => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname,
						'Bucket' =>  'datahub360',
						'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/saving analysis/'.$keyname,
						'MetadataDirective' => 'REPLACE',
						'Metadata' => [
							'fdesc' => $fvalue
						]
					]);
					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "safilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"])){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']).".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($sa_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname);
				if($info)
				{
					$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$fvalue);
					if($infosecond)
					{
						echo 6;exit();
					}
					$updateResponse = $s3Client->copyObject([
						'Key' => 'resources/Clients/'.$company_name.'/saving analysis/'.$fvalue,
						'Bucket' =>  'datahub360',
						'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/saving analysis/'.$keyname,
						'MetadataDirective' => 'REPLACE',
						'Metadata' => [
							'fdesc' => $fdesc
						]
					]);

					$s3Client->deleteObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname
					]);

					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}else if(isset($_FILES) and isset($_FILES["sas3filesupload"]) and isset($_GET["masterid"]) and $_GET["masterid"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$tmp_duplicatefiles=$files_list=$tmp_freshfiles=array();

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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['masterid']));

	if ($stmt = $mysqli->prepare("SELECT c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$filenames);
			$stmt->fetch();

			if(@trim($filenames) != "")
			{
				$files_list=@explode("@@;@@",$filenames);
			}

			if(isset($_FILES) and isset($_FILES["sas3filesupload"])){
				$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/');
				if(!$infotaget)
				{
					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'
					]);
				}

				foreach($_FILES['sas3filesupload']['name'] as $ky => $vl){
						$temp_file_location = $_FILES['sas3filesupload']['tmp_name'][$ky];

						$infotagetfile = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.$vl);
						if($infotagetfile)
						{
							$vl=time()."_".$vl;
						}

						$s3Client->putObject([
							'Bucket' => 'datahub360',
							'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'.$vl,
							'SourceFile' => $temp_file_location
						]);
						$files_list[]=$vl;
				}
			}

			if ($stmtupdate = $mysqli->prepare("UPDATE saving_analysis SET link='".$mysqli->real_escape_string(@implode("@@;@@",$files_list))."' WHERE  id='".$s3sid."'")) {

				$stmtupdate->execute();
				if($stmtupdate->affected_rows == 1){

				}else{
					return false;
					//$cncerror="error";
					//break;
				}
			}else return false;
		}else return false;
	}else return false;

	echo json_encode(array("error"=>""));
	die();
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "fiview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($fi_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

		if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$keyname);
				if($infotarget)
				{
					$fileext=@strtolower(pathinfo($keyname, PATHINFO_EXTENSION));
					$filepath='resources/Clients/'.$company_name.'/focus items/'.$keyname;
					$status=2;
					if(empty($fileext)) $status=0;
					if($fileext=="pdf"){
						$status=1;
					}else{
						//$supportfileext=array("odt","csv","db","doc","docx","dotx","fodp","fods","fodt","mml","odb","odf","odg","otp","ots","ott","oxt","pptx","psw","sda","sdc","sdd","sdp","sdw","slk","smf","stc","std","sti","stw","sxc","sxg","sxi","sxm","sxw","uof","uop","uos","uot","vsd","vsdx","wdb","wps","wri","xls","xlsx","dic","doc#","mab","tsv","txtrpt");
						$supportfileext=array("jpeg","png","gif","tiff","bmp","webm","mpeg4","3gpp","mov","avi","mpegps","wmv","flv","txt","css","html","php","c","cpp","h","hpp","js","doc","docx","xls","xlsx","ppt","pptx","pdf","pages","ai","psd","tiff","dxf","svg","eps","ps","ttf","xps","csv");
			      if(in_array($fileext,$supportfileext)){
							$status=8;
						}elseif($s3Client->doesObjectExist('resources/Clients/'.$company_name.'/focus items/'.$keyname.'.err')){
							$status=0;
						}
						if($s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$keyname.'.pdf')){
							$filepath='resources/Clients/'.$company_name.'/focus items/'.$keyname.'.pdf';
							$status=1;
						}
					}

					if($fileext=="mp4" or $fileext=="ogg" or $fileext=="avi" or $fileext=="flv" or $fileext=="mkv" or $fileext=="mov" or $fileext=="mpeg" or $fileext=="mpg" or $fileext=="m4v" or $fileext=="wmv"){
						$status=5;
					}elseif($fileext=="mp3" or $fileext=="m4a" or $fileext=="mp2" or $fileext=="m3u" or $fileext=="wma" or $fileext=="m4a" or $fileext=="m4a"){
						$status=6;
					}elseif($fileext=="jpg" or $fileext=="jpeg" or $fileext=="gif" or $fileext=="png" or $fileext=="tif" or $fileext=="bmp" or $fileext=="ico"){
						$status=7;
					}


					$cmd = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => $filepath
					]);

					$cmd1 = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/focus items/'.$keyname
					]);

					if($status != 5 and $status != 6){
						$head = $s3Client->headObject(
						 [
						   'Bucket' => 'datahub360',
						   'Key' => 'resources/Clients/'.$company_name.'/focus items/'.$keyname,
						 ]
						);
						$sizeresult = (int) ($head->get('ContentLength') ?? 0);
						if($sizeresult > 24000000) $status=9;
					}

					$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
					$request1 = $s3Client->createPresignedRequest($cmd1, '+28 minutes');
					$presignedUrl = (string) $request->getUri();
					$presignedUrl1 = (string) $request1->getUri();
					if(preg_match("/X-Amz-Algorithm=([^\s]+)/s",$presignedUrl1,$tmpurl)){
						$presignedUrl1=$tmpurl[1];
						$tmpurl=null;
					}
					echo json_encode(array("status"=>$status,"presignedurl"=>urlencode($presignedUrl),"oripresignedurl"=>urlencode($presignedUrl1),"name"=>$keyname));
					exit();
				}else{echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}

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




}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "fidelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($fi_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$keyname);
				if($info)
				{
					// Delete an object from the bucket.
					$s3Client->deleteObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/focus items/'.$keyname
					]);

					$files_list=array_diff($files_list, [$keyname]);
					if ($stmtupdate = $mysqli->prepare("UPDATE focus_items SET link='".$mysqli->real_escape_string(@implode("@@;@@",$files_list))."' WHERE  id='".$s3sid."'")) {

						$stmtupdate->execute();
						if($stmtupdate->affected_rows == 1){

						}else{
							return false;
							//$cncerror="error";
							//break;
						}
					}

					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "fifiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($fi_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$keyname);
				if($info)
				{
					$updateResponse = $s3Client->copyObject([
						'Key' => 'resources/Clients/'.$company_name.'/focus items/'.$keyname,
						'Bucket' =>  'datahub360',
						'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/focus items/'.$keyname,
						'MetadataDirective' => 'REPLACE',
						'Metadata' => [
							'fdesc' => $fvalue
						]
					]);
					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "fifilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"])){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']).".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

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

	if ($stmt = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($fi_id,$company_id,$company_name,$s3_filenames);
			$stmt->fetch();

			$files_list=array();

			if(@trim($s3_filenames) != "")
			{
				$files_list=@explode("@@;@@",$s3_filenames);
			}

			$files_len=count($files_list);

			if($files_len <= 0 || !in_array($keyname,$files_list)){echo false;exit();}
			else{
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items'.$keyname);
				if($info)
				{
					$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$fvalue);
					if($infosecond)
					{
						echo 6;exit();
					}
					$updateResponse = $s3Client->copyObject([
						'Key' => 'resources/Clients/'.$company_name.'/focus items/'.$fvalue,
						'Bucket' =>  'datahub360',
						'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/focus items/'.$keyname,
						'MetadataDirective' => 'REPLACE',
						'Metadata' => [
							'fdesc' => $fdesc
						]
					]);

					$s3Client->deleteObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/focus items/'.$keyname
					]);

					echo true;
					exit();
				}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}else if(isset($_FILES) and isset($_FILES["fis3filesupload"]) and isset($_GET["fimasterid"]) and $_GET["fimasterid"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$tmp_duplicatefiles=$files_list=$tmp_freshfiles=array();

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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['fimasterid']));

	if ($stmt = $mysqli->prepare("SELECT c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$filenames);
			$stmt->fetch();

			if(@trim($filenames) != "")
			{
				$files_list=@explode("@@;@@",$filenames);
			}

			if(isset($_FILES) and isset($_FILES["fis3filesupload"])){
				$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/');
				if(!$infotaget)
				{
					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/focus items/'
					]);
				}

				foreach($_FILES['fis3filesupload']['name'] as $ky => $vl){
						$temp_file_location = $_FILES['fis3filesupload']['tmp_name'][$ky];

						$infotagetfile = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$vl);
						if($infotagetfile)
						{
							$vl=time()."_".$vl;
						}

						$s3Client->putObject([
							'Bucket' => 'datahub360',
							'Key'    => 'resources/Clients/'.$company_name.'/focus items/'.$vl,
							'SourceFile' => $temp_file_location
						]);
						$files_list[]=$vl;
				}
			}

			if ($stmtupdate = $mysqli->prepare("UPDATE focus_items SET link='".$mysqli->real_escape_string(@implode("@@;@@",$files_list))."' WHERE  id='".$s3sid."'")) {

				$stmtupdate->execute();
				if($stmtupdate->affected_rows == 1){

				}else{
					return false;
					//$cncerror="error";
					//break;
				}
			}else return false;
		}else return false;
	}else return false;

	echo json_encode(array("error"=>""));
	die();
}else echo false;
?>
