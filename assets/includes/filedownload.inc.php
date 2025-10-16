<?php
error_reporting(0);
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();


require '../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


$bucket='datahub360';


if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die(false);


if(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "ctaccfiledownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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

	if ($stmt = $mysqli->prepare('SELECT c.company_id,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$s3sid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){exit();}
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
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$_GET['filename']);
							readfile($presignedUrl = (string) $request->getUri());
							exit();
						}
					}else{exit();}
				}else {exit();}
			}
		}else{exit();}

	}else{exit();}





}elseif(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "ctdownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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


	//if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {
	if ($stmt = $mysqli->prepare('SELECT c.company_id,cm.s3_foldername FROM contracts cm JOIN user u JOIN company c WHERE cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$s3sid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){exit();}
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
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$_GET['filename']);
							readfile($presignedUrl = (string) $request->getUri());
							exit();
						}
					}else{exit();}
				}else {exit();}
			}
		}else{exit();}

	}else{exit();}





}elseif(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "madownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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

	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sqll = "SELECT c.company_id,ma.s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and ma.MasterID='".$s3sid."' LIMIT 1";
	}elseif($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
		$sqll = "SELECT c.company_id,ma.s3_foldername FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$s3sid."' LIMIT 1";
	}else{
		exit();
	}

	if ($stmt = $mysqli->prepare($sqll)) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){exit();}
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
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$_GET['filename']);
							readfile($presignedUrl = (string) $request->getUri());
							exit();
						}
					}else{exit();}
				}else {exit();}
			}
		}else{exit();}

	}else{exit();}





}else if(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "sssdownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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

	if ($stmt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$s3sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' where ss.id="'.$s3sid.'"').' LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_id,$s3_foldername);
			$stmt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo false;exit();}
			if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

			if($s3_foldername==""){exit();}
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
							$cmd = $s3Client->getCommand('GetObject', [
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3_foldername.'/'.$keyname
							]);

							$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$_GET['filename']);
							readfile($presignedUrl = (string) $request->getUri());
							exit();
						}
					}else{exit();}
				}else {exit();}
			}
		}else{exit();}

	}else{exit();}





}elseif(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "s3cldownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !="" and isset($_SESSION['navurl'])){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$company_id=$mysqli->real_escape_string(@trim($_GET['ticket']));
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
				$cmd = $s3Client->getCommand('GetObject', [
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname
				]);

				$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.$_GET['filename']);
				readfile($presignedUrl = (string) $request->getUri());
				exit();
			}
		}else{exit();}
	}else {exit();}





}elseif(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "sadownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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
					$cmd = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/saving analysis/'.$keyname
					]);

					$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.$_GET['filename']);
					readfile($presignedUrl = (string) $request->getUri());
					exit();
				}
			}
		}else{exit();}

	}else{exit();}





}elseif(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "fidownload" and $_GET["filename"] != "" and isset($_GET["ticket"]) and $_GET["ticket"] !=""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));
	$s3sid=$mysqli->real_escape_string(@trim($_GET['ticket']));
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

	if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM focus_items sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

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
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.$keyname);
				if($info)
				{
					$cmd = $s3Client->getCommand('GetObject', [
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/focus items/'.$keyname
					]);

					$request = $s3Client->createPresignedRequest($cmd, '+8 minutes');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.$_GET['filename']);
					readfile($presignedUrl = (string) $request->getUri());
					exit();
				}
			}
		}else{exit();}

	}else{exit();}





}else if(isset($_GET) and isset($_GET["filename"]) and isset($_GET["filename"]) and isset($_GET["type"]) and $_GET["type"] == "zipdownload" and $_GET["filename"] != ""){

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];




	$keyname=str_ireplace("%20"," ",trim($_GET["filename"]));

    $profile = 'default';

    $s3Client = new S3Client([
        'region'      => 'us-west-2',
        'version'     => 'latest',
				'credentials' => [
	           'key' => $_ENV['aws_access_key_id'],
	           'secret' => $_ENV['aws_secret_access_key']
	       ]
    ]);

	$info = $s3Client->doesObjectExist('datahub360-tempdownloads', $keyname);
	if($info)
	{
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => 'datahub360-tempdownloads',
			'Key'    => $keyname
		]);

		$request = $s3Client->createPresignedRequest($cmd, '+60 minutes');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$_GET['filename']);
		readfile($presignedUrl = (string) $request->getUri());
		exit();
	}
}else echo false;

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
