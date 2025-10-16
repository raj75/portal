<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();


require '../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$bucket='datahub360';

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die(false);

if(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sssfilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"])){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
//added New
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);


	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fval=@trim($_POST['fvalue']);
	$fval = str_replace("'", "", $fval);
	$fval = str_replace('"', '', $fval);
	$fvalue=$fval.".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	$fdesc = str_replace("'", "", $fdesc);
	$fdesc = str_replace('"', '', $fdesc);
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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id="'.$s3sid.'"').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$fvalue);
							if($infosecond)
							{
								echo 6;exit();
							}
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$fvalue,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fdesc
								]
							]);

							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname
							]);

							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}else if(isset($_POST["type"]) and $_POST["type"]=="s3clfolderadd" and isset($_POST["ticket"]) and $_POST["ticket"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl']) and isset($_POST["fvalue"]) and @trim($_POST["fvalue"]) !="" ){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$newfoldername=@trim($_POST['fvalue']);
	$newfoldername = str_replace("'", "", $newfoldername);
	$newfoldername = str_replace('"', '', $newfoldername);

	if(empty($newfoldername)) {echo false;exit(); }

	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;

	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit(); }
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

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

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$newfoldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$newfoldername.'/',
					'Body' => "",
				]);
/*echo 	'resources/Clients/'.$company_name.$s3_foldername.'/'.$newfoldername.'/remove.txt';	die();
				$s3Client->deleteObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$newfoldername.'/remove.txt'
				]);*/
				echo true;
				die();
			}else{
				echo 9;exit();
			}

		}else{echo false;exit(); }
	}else {echo false;exit(); }
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sssfiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id="'.$s3sid.'"').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fvalue
								]
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sssdelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id="'.$s3sid.'"').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							// Delete an object from the bucket.
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sssview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id="'.$s3sid.'"').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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
}else if(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "maview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and ($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare("SELECT c.company_id,s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and ma.MasterID='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if($company_id != $cname) {echo false;exit();}

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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
}else if(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "sss" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id='.$s3sid.'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+1 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}









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
}else if(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "cm" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('SELECT distinct cm.ContractID,cm.s3_foldername,c.company_id FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($cmid,$s3_foldername,$company_id);
			$stmt->fetch();

			if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+1 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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
}else if(isset($_POST) and isset($_POST["url"]) and isset($_POST["action"]) and $_POST["url"] != "" and $_POST["action"] == "delete"){

	$keyname=".".parse_url(str_ireplace("%20"," ",ltrim($_POST["url"],"Home")), PHP_URL_PATH);
	//$keyname='./invoices/Sample-bill-page-1-06-09-15.gif';

    $profile = 'default';
    //$path = '../../../../../lib/s3/credentials.ini';

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

	$info = $s3Client->doesObjectExist($bucket, $keyname);
	if(!$info)
	{
		return false;
		exit();
	}

	// Delete an object from the bucket.
	$s3Client->deleteObject([
		'Bucket' => $bucket,
		'Key'    => $keyname
	]);

	echo true;
	exit();
}else if(isset($_FILES) and isset($_FILES["mas3filesupload"]) and isset($_GET["masterid"]) and $_GET["masterid"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['masterid']));


	if ($stmt = $mysqli->prepare("SELECT c.company_name,s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$s3foldername);
			$stmt->fetch();

			if($s3foldername==""){
				$s3foldername=time().rand(0,9).rand(10,90);
				if ($stmtupdate = $mysqli->prepare("UPDATE master_agreements SET s3_foldername='".$s3foldername."' WHERE  MasterID='".$s3sid."'")) {

					$stmtupdate->execute();
					if($stmtupdate->affected_rows == 1){

					}else{
						return false;
						//$cncerror="error";
						//break;
					}
				}else return false;

			}
		}else return false;
	}else return false;



	if(isset($_FILES) and isset($_FILES["mas3filesupload"])){
		$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/');
		if(!$infotaget)
		{
			$s3Client->putObject([
				'Bucket' => 'datahub360',
				'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/'
			]);
		}

		foreach($_FILES['mas3filesupload']['name'] as $ky => $vl){
				$vl = str_replace("'", "", $vl);
				$vl = str_replace('"', '', $vl);
				if(@trim($vl)=="") continue;

				$file_name = $vl;
				$temp_file_location = $_FILES['mas3filesupload']['tmp_name'][$ky];


				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/'.$file_name,
					'SourceFile' => $temp_file_location
				]);
		}
	}
	echo json_encode(array("error"=>""));
	die();
}else if(isset($_FILES) and isset($_FILES["sssfilesupload"]) and isset($_GET["sssid"]) and $_GET["sssid"] != ""){
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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['sssid']));

	if ($stmt = $mysqli->prepare('Select c.company_name,s3_foldername From startstop_status ss , user up, company c Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and c.company_id=up.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ' and  up.user_id = '.$_SESSION["user_id"]:'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$s3foldername);
			$stmt->fetch();

			if($s3foldername==""){
				$s3foldername=time().rand(0,9).rand(10,90);
				if ($stmtupdate = $mysqli->prepare("UPDATE startstop_status SET s3_foldername='".$s3foldername."' WHERE  id='".$s3sid."'")) {

					$stmtupdate->execute();
					if($stmtupdate->affected_rows == 1){

					}else{
						die(false);
						//$cncerror="error";
						//break;
					}
				}else die(false);

			}
		}else die(false);
	}else die(false);



	if(isset($_FILES) and isset($_FILES["sssfilesupload"])){
		$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/');
		if(!$infotaget)
		{
			$s3Client->putObject([
				'Bucket' => 'datahub360',
				'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'
			]);
		}

		foreach($_FILES['sssfilesupload']['name'] as $ky => $vl){
				$vl = str_replace("'", "", $vl);
				$vl = str_replace('"', '', $vl);
				if(@trim($vl)=="") continue;

				$file_name = $vl;
				$temp_file_location = $_FILES['sssfilesupload']['tmp_name'][$ky];


				$s3Client->putObject([
					'Body' => '',
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'.$file_name,
					'SourceFile' => $temp_file_location/*,
					'Metadata' => [
						'fdesc' => 'Just Sample'
					],	*/
				]);
		}
	}
	echo json_encode(array("error"=>""));
	die();
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "mafilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fval=@trim($_POST['fvalue']);
	$fval = str_replace("'", "", $fval);
	$fval = str_replace('"', '', $fval);
	$fvalue=$fval.".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	$fdesc = str_replace("'", "", $fdesc);
	$fdesc = str_replace('"', '', $fdesc);
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

	if ($stmt = $mysqli->prepare("SELECT c.company_id,s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$fvalue);
							if($infosecond)
							{
								echo 6;exit();
							}
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$fvalue,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fdesc
								]
							]);

							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname
							]);

							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "madelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare("SELECT c.company_id,s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							// Delete an object from the bucket.
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "mafiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
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

	if ($stmt = $mysqli->prepare("SELECT c.company_id,s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1")) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();


					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fvalue
								]
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "maview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sqll = "SELECT c.company_id,ma.s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and ma.MasterID='".$s3sid."' LIMIT 1";
	}elseif($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
		$sqll = "SELECT c.company_id,ma.s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1";
	}else{
		echo false;exit();
	}

	if ($stmt = $mysqli->prepare($sqll)) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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




/////////////////////////////////////////////////////
////////////////////////////////////////////////////


//Supplier Contract



}else if(isset($_FILES) and isset($_FILES["cts3filesupload"]) and isset($_GET["contractid"]) and $_GET["contractid"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['contractid']));


	if ($stmt = $mysqli->prepare('SELECT c.company_name,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$s3foldername);
			$stmt->fetch();

			if($s3foldername==""){
				$s3foldername=time().rand(0,9).rand(10,90);
				if ($stmtupdate = $mysqli->prepare("UPDATE contracts SET s3_foldername='".$s3foldername."' WHERE  ContractID='".$s3sid."'")) {

					$stmtupdate->execute();
					if($stmtupdate->affected_rows == 1){

					}else{
						echo false;
						exit();
						//$cncerror="error";
						//break;
					}
				}else{ echo false;exit();}

			}
		}else{ echo false;exit();}
	}else{ echo false;exit();}



	if(isset($_FILES) and isset($_FILES["cts3filesupload"])){
		$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/');
		if(!$infotaget)
		{
			$s3Client->putObject([
				'Bucket' => 'datahub360',
				'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'
			]);
		}

		foreach($_FILES['cts3filesupload']['name'] as $ky => $vl){
				$vl = str_replace("'", "", $vl);
				$vl = str_replace('"', '', $vl);
				if(@trim($vl)=="") continue;

				$file_name = $vl;
				$temp_file_location = $_FILES['cts3filesupload']['tmp_name'][$ky];


				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'.$file_name,
					'SourceFile' => $temp_file_location
				]);
		}
	}
	echo json_encode(array("error"=>""));
	exit();
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN user u JOIN company c WHERE  cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctfiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();


					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fvalue
								]
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctdelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							// Delete an object from the bucket.
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctfilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvl=$_POST['fvalue'];
	$fvl = str_replace("'", "", $fvl);
	$fvl = str_replace('"', '', $fvl);
	if(@trim($fvl)==""){echo false;exit();}

	$fvalue=$fvl.".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	$fdesc = str_replace("'", "", $fdesc);
	$fdesc = str_replace('"', '', $fdesc);
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$fvalue);
							if($infosecond)
							{
								echo 6;exit();
							}
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$fvalue,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fdesc
								]
							]);

							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}



////////////////////////////////////
////////////////////////////////////
/////////////Supplier Contract > Contract Account///////////////////////
////////////////////////////////////
////////////////////////////////////

}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctaccfiledelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

;


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							// Delete an object from the bucket.
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctaccfiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();


					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fvalue
								]
							]);
							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctaccfilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$s3sid=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fval=@trim($_POST['fvalue']);
	$fval = str_replace("'", "", $fval);
	$fval = str_replace('"', '', $fval);
	$fvalue=$fval.".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	$fdesc = str_replace("'", "", $fdesc);
	$fdesc = str_replace('"', '', $fdesc);
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname);
						if($info)
						{
							$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$fvalue);
							if($infosecond)
							{
								echo 6;exit();
							}
							$updateResponse = $s3Client->copyObject([
								'Key' => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$fvalue,
								'Bucket' =>  'datahub360',
								'CopySource' => 'datahub360/resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname,
								'MetadataDirective' => 'REPLACE',
								'Metadata' => [
									'fdesc' => $fdesc
								]
							]);

							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							echo true;
							exit();
						}
					}else{echo false;exit();}
				}else {echo false;exit();}
			}
		}else{echo false;exit();}

	}else{echo false;exit();}

}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "ctaccview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !=""){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,ac.s3_foldername FROM contracts cm JOIN user u JOIN company c JOIN contract_accounts ac WHERE cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){echo false;exit();}
			else{


				if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($company_name);
						$stmtttt->fetch();

						$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/Contract Accounts/'.$s3_foldername.'/'.$keyname);
						if($infotarget)
						{
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/Contract Accounts/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							echo $presignedUrl = (string) $request->getUri();
							exit();
						}else{echo false;exit();}
					}else{echo false;exit();}
				}else {echo false;exit();}
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
}else if(isset($_FILES) and isset($_FILES["ctaccedits3filesupload"]) and isset($_GET["contractaccid"]) and $_GET["contractaccid"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){
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

	$s3sid=$mysqli->real_escape_string(@trim($_GET['contractaccid']));

	if ($stmt = $mysqli->prepare('SELECT c.company_name,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$s3foldername);
			$stmt->fetch();

			if($s3foldername==""){
				$s3foldername=time().rand(0,9).rand(10,90);
				if ($stmtupdate = $mysqli->prepare("UPDATE contract_accounts SET s3_foldername='".$s3foldername."' WHERE  ContractAcctID='".$s3sid."'")) {

					$stmtupdate->execute();
					if($stmtupdate->affected_rows == 1){

					}else{
						echo false;
						exit();
						//$cncerror="error";
						//break;
					}
				}else{ echo false;exit();}

			}
		}else{ echo false;exit();}
	}else{ echo false;exit();}



	if(isset($_FILES) and isset($_FILES["ctaccedits3filesupload"])){
		$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/');
		if(!$infotaget)
		{
			$s3Client->putObject([
				'Bucket' => 'datahub360',
				'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'
			]);
		}

		foreach($_FILES['ctaccedits3filesupload']['name'] as $ky => $vl){
				$vl = str_replace("'", "", $vl);
				$vl = str_replace('"', '', $vl);
				$file_name = $vl;
				$temp_file_location = $_FILES['ctaccedits3filesupload']['tmp_name'][$ky];


				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'.$file_name,
					'SourceFile' => $temp_file_location
				]);
		}
	}
	echo json_encode(array("error"=>""));
	exit();
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "s3clview" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_SESSION['navurl'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
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


	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;


	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname);
			if($infotarget)
			{
				$fileext=@strtolower(pathinfo($keyname, PATHINFO_EXTENSION));
				$filepath='resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname;
				$status=2;
				if(empty($fileext)) $status=0;
				if($fileext=="pdf"){
					$status=1;
				}else{
					//$supportfileext=array("odt","csv","db","doc","docx","dotx","fodp","fods","fodt","mml","odb","odf","odg","otp","ots","ott","oxt","pptx","psw","sda","sdc","sdd","sdp","sdw","slk","smf","stc","std","sti","stw","sxc","sxg","sxi","sxm","sxw","uof","uop","uos","uot","vsd","vsdx","wdb","wps","wri","xls","xlsx","dic","doc#","mab","tsv","txtrpt");
					$supportfileext=array("jpeg","png","gif","tiff","bmp","webm","mpeg4","3gpp","mov","avi","mpegps","wmv","flv","txt","css","html","php","c","cpp","h","hpp","js","doc","docx","xls","xlsx","ppt","pptx","pdf","pages","ai","psd","tiff","dxf","svg","eps","ps","ttf","xps","csv");
		      if(in_array($fileext,$supportfileext)){
						$status=8;
					}elseif($s3Client->doesObjectExist('datahub360',  'resources/Clients/'.$company_name.$s3_foldername.'/'.'.'.$keyname.'.err')){
						$status=0;
					}
					if($s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.'.'.$keyname.'.pdf')){
						$filepath='resources/Clients/'.$company_name.$s3_foldername.'/'.'.'.$keyname.'.pdf';
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
					'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname
				]);

				if($status != 5 and $status != 6){
					$head = $s3Client->headObject(
					 [
					   'Bucket' => 'datahub360',
					   'Key' => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname,
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
		}else{echo false;exit();}
	}else {echo false;exit();}





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
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "s3clfilename" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"])and isset($_POST["fdesc"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){
	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];



	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fval=@trim($_POST['fvalue']);
	$fval = str_replace("'", "", $fval);
	$fval = str_replace('"', '', $fval);
	$fvalue=$fval.".".pathinfo($keyname, PATHINFO_EXTENSION);
	$fdesc=@trim($_POST['fdesc']);
	$fdesc = str_replace("'", "", $fdesc);
	$fdesc = str_replace('"', '', $fdesc);
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


	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;


	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname);
			if($infotarget)
			{
				$infosecond = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.'/'.$fvalue);
				if($infosecond)
				{
					echo 6;exit();
				}
				$updateResponse = $s3Client->copyObject([
					'Key' => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$fvalue,
					'Bucket' =>  'datahub360',
					'CopySource' => 'datahub360/resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname,
					'MetadataDirective' => 'REPLACE',
					'Metadata' => [
						'fdesc' => $fdesc
					]
				]);
				if($fvalue != $keyname){
					$s3Client->deleteObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname
					]);
				}

				echo true;
				exit();
			}else{echo false;exit();}


		}else{echo false;exit();}
	}else {echo false;exit();}

}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "s3clfiledesc" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue=@trim($_POST['fvalue']);
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
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

	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;


	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();
			//die('resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname);
			$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname);
			if($info)
			{
				$updateResponse = $s3Client->copyObject([
					'Key' => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname,
					'Bucket' =>  'datahub360',
					'CopySource' => 'datahub360/resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname,
					'MetadataDirective' => 'REPLACE',
					'Metadata' => [
						'fdesc' => $fvalue
					]
				]);
				echo true;
				exit();
			}


		}else{echo false;exit();}
	}else {echo false;exit();}
}elseif(isset($_POST) and isset($_POST["filename"]) and isset($_POST["filename"]) and isset($_POST["type"]) and $_POST["type"] == "s3cldelete" and $_POST["filename"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_POST["filename"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
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
	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;


	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname);
			if($info)
			{
				// Delete an object from the bucket.
				$s3Client->deleteObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname
				]);
				echo true;
				exit();
			}

		}else{echo false;exit();}
	}else {echo false;exit();}
}else if(isset($_FILES) and isset($_FILES["s3browsefilesupload"]) and isset($_REQUEST["ticket"]) and $_REQUEST["ticket"] != "" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$company_id=$mysqli->real_escape_string(@trim($_REQUEST['ticket']));
	$s3_foldername=$_SESSION['navurl'];
	if(!empty($s3_foldername)) $s3_foldername='/'.$s3_foldername;

	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

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
//echo 'SELECT company_name FROM company WHERE company_id='.$cname;
	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();
//echo 'resources/Clients/'.$company_name.$s3_foldername.'/';
			if(isset($_FILES) and isset($_FILES["s3browsefilesupload"])){
				$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/');
				if(!$infotaget)
				{
					/*$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'
					]);*/
					echo false;exit();
				}else{

					foreach($_FILES['s3browsefilesupload']['name'] as $ky => $vl){
							$vl = str_replace("'", "", $vl);
							$vl = str_replace('"', '', $vl);
							if(@trim($vl)=="") continue;
							$file_name = $vl;
							$temp_file_location = $_FILES['s3browsefilesupload']['tmp_name'][$ky];

	//echo 'resources/Clients/'.$company_name.$s3_foldername.'/'.$file_name;die();
							$s3Client->putObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$file_name,
								'SourceFile' => $temp_file_location
							]);
					}
				}
			}
			echo json_encode(array("error"=>""));
			die();

		}else{echo false;exit();}
	}else {echo false;exit();}
}else echo false;
?>
