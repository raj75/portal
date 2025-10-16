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





if(isset($_POST) and isset($_POST["s3clfoldername"]) and isset($_POST["type"]) and $_POST["type"] == "s3clfoldelete" and $_POST["s3clfoldername"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$keyname=str_ireplace("%20"," ",trim($_POST["s3clfoldername"]));
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

//echo ":".$keyname.":";die();
	if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo "yy";exit();}
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cname=$company_id;

	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			$dinfo = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'/');
			if($dinfo)
			{
				$results = $s3Client->listObjectsV2([
					'Bucket' => 'datahub360',
					'Prefix' => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'/'
				]);

				if (isset($results['Contents'])) {//print_r($results['Contents']);
					foreach ($results['Contents'] as $result) {
						$s3Client->deleteObject([
							'Bucket' => 'datahub360',
							'Key' => $result['Key']
						]);
					}
				}

				echo true;
				exit();
			}else{echo 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'/'.'2';exit();}

		}else{echo false;exit();}
	}else {echo false;exit();}
}elseif(isset($_POST) and isset($_POST["foldername"]) and isset($_POST["type"]) and $_POST["type"] == "s3clfoldernameedit" and $_POST["foldername"] != "" and !isset($_POST["action"]) and isset($_POST["ticket"]) and $_POST["ticket"] !="" and isset($_POST["fvalue"]) and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) and isset($_SESSION['navurl'])){

	if(empty(@trim($_POST["fvalue"]))){echo false;exit();}

	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];

	$keyname=str_ireplace("%20"," ",trim($_POST["foldername"]));
	$keyname = str_replace("'", "", $keyname);
	$keyname = str_replace('"', '', $keyname);

	$company_id=$mysqli->real_escape_string(@trim($_POST['ticket']));
	$fvalue = str_replace("'", "", $fvalue);
	$fvalue = str_replace('"', '', $fvalue);
	$fvalue=@trim($_POST['fvalue']);

	if($keyname == "" or $fvalue == "") {echo false;exit();}

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
//echo 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'resources/Clients/'.$company_name.$s3_foldername.'/'.$fvalue;
			$responserename=renamefolder($s3Client,'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname,'resources/Clients/'.$company_name.$s3_foldername.'/'.$fvalue);

			$responserename=str_replace("1","",$responserename);

			if($responserename == ""){//echo 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'/';
				$results = $s3Client->listObjectsV2([
					'Bucket' => 'datahub360',
					'Prefix' => 'resources/Clients/'.$company_name.$s3_foldername.'/'.$keyname.'/'
				]);

				if (isset($results['Contents'])) {//print_r($results['Contents']);die();
					foreach ($results['Contents'] as $result) {
						$s3Client->deleteObject([
							'Bucket' => 'datahub360',
							'Key' => $result['Key']
						]);
					}
				}

				echo true;
				exit();
			}


		}else{echo false;exit();}
	}else {echo false;exit();}

}else echo false;


function renamefolder($s3Client,$foldername,$destinationfolder){
	$curr_folder=array();
	$foldername=rtrim($foldername,'/');
	$destinationfolder=rtrim($destinationfolder,'/');
///Added New
	$destinationfolder = str_replace("'", "", $destinationfolder);
	$destinationfolder = str_replace('"', '', $destinationfolder);



//echo $foldername.":".$destinationfolder.";";
	$infotarget = $s3Client->doesObjectExist('datahub360', $foldername.'/');
	if($infotarget)
	{
		$folderobjects = $s3Client->ListObjects(array( 'Bucket' => 'datahub360', 'Delimiter' => '/','Prefix'=>$foldername.'/'));
		$curr_folder = $folderobjects->get("CommonPrefixes");//var_dump($curr_folder);
		unset($folderobjects);
	}

	$objects = $s3Client->getIterator('ListObjects', array(
		"Bucket" => "datahub360",
		"Prefix" => $foldername.'/',
		'Delimiter' => '/'
	));

	$fcheckinfo = $s3Client->doesObjectExist('datahub360', $destinationfolder.'/');
	if(!$fcheckinfo)
	{
		$s3Client->putObject([
			'Bucket' => 'datahub360',
			'Key'    => $destinationfolder.'/'
		]);
	}

	foreach ($objects as $object){//var_dump($object);
		//if(preg_match('/(\/)$/s', $vl["Key"], $nosave)) continue;
		$fileext=pathinfo($object['Key'], PATHINFO_EXTENSION);
		if ($fileext){//var_dump($object);
			$filebasename=basename($object['Key']);
			$headers = $s3Client->headObject(array(
				  "Bucket" => 'datahub360',
				  "Key" => $object['Key']
				));
			$headarr=$headers->toArray();
			$fdesc="";
			if(isset($headarr["Metadata"]) and isset($headarr["Metadata"]["fdesc"])){ $fdesc=$headarr["Metadata"]["fdesc"]; }

			//$ftime=@date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($object['LastModified']->format(\DateTime::ISO8601))));
			//$filebname=pathinfo($filebasename, PATHINFO_FILENAME);

			$info = $s3Client->doesObjectExist('datahub360', $destinationfolder.'/'.$filebasename);
			if(!$info)
			{//echo "   ".$destinationfolder.'/'.$filebasename."***".$foldername.'/'.$filebasename."     ";
				$updateResponse = $s3Client->copyObject([
					'Key' => $destinationfolder.'/'.$filebasename,
					'Bucket' =>  'datahub360',
					'CopySource' => 'datahub360/'.$foldername.'/'.$filebasename,
					'MetadataDirective' => 'REPLACE',
					'Metadata' => [
						'fdesc' => $fdesc
					]
				]);
			}
		}
	}

	if(is_array($curr_folder)==true and count($curr_folder)){
		foreach((array)$curr_folder as $kys=>$vls){
			if(preg_match('/(\/\/)+/',$vls['Prefix'],$nosave)) continue;
			$flname=basename($vls['Prefix']);

			$finfo = $s3Client->doesObjectExist('datahub360', $destinationfolder.'/'.$flname.'/');
			if(!$finfo)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => $destinationfolder.'/'.$flname.'/'
				]);
			}
//echo $destinationfolder.":::".$flname;
			if(renamefolder($s3Client,$foldername.'/'.rtrim($flname,'/'),rtrim($destinationfolder,'/').'/'.$flname) != true){echo 2;die();}
		}
	}

	return true;
}
?>
