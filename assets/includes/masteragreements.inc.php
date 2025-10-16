<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

error_reporting(0);
ini_set('max_execution_time', 0);





if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

//Add new master supplier agreements
if(isset($_POST["maadd"]) and $_POST["maadd"]=="new")
{
	$error="Error occured";
	$sub_query=array();


	$company_id="";
	foreach ($_POST as $param_name => $param_val) {
		$param_name=str_ireplace("@"," ",$param_name);
		if($param_name == "maadd") continue;
		if($param_name == "ClientID"){$company_id=@trim($param_val);}
		if($param_name == "VendorID"){$vendor_id=@trim($param_val);}

		if(($param_name == "Start Date" or $param_name == "End Date") and $param_val != ""){
			$param_val = @date_format(@date_create_from_format('m/d/Y', $param_val), 'Y-m-d');
		}

		$sub_query[]= '`'.$param_name.'`="'.$mysqli->real_escape_string(@trim($param_val)).'"';
	}

	if(@trim($vendor_id) == "" || $vendor_id == 0 || @trim($company_id) == "" || $company_id == 0){echo json_encode(array("error"=>5));die();}



	if(!count($sub_query)){echo json_encode(array("error"=>$error));die();}

	$s3foldername=time().rand(0,9).rand(10,90);

	if(isset($_FILES) and isset($_FILES["maaddfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';



	$sql='INSERT INTO master_agreements SET MasterID=null,'.$s3foldersql.implode(',',$sub_query);
	$stmt = $mysqli->prepare($sql);
	if($stmt){
		$stmt->execute();
		if($stmt->affected_rows == 1){
			//$cnc[]=$mysqli->insert_id;
		}else{
			echo json_encode(array("error"=>$error));
			die();
		}
	}else{
		echo json_encode(array("error"=>$error));
		die();
	}

	/*if($cncerror != ""){
		if(count($cnc)){
			$sql='Delete from  master_agreements where id=$cnc';
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
			}
		}
		echo json_encode(array("error"=>$error));
		die();
	}else{*/
////////////////////
		$profile = 'default';

		$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
			'credentials' => [
					 'key' => $_ENV['aws_access_key_id'],
					 'secret' => $_ENV['aws_secret_access_key']
			 ]
		]);

		if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$company_id)) {

			$stmtttt->execute();
			$stmtttt->store_result();
			if ($stmtttt->num_rows > 0) {
				$stmtttt->bind_result($company_name);
				$stmtttt->fetch();
			}else{echo false;exit();}
		}else{echo false;exit();}



		$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/');
		if(!$info)
		{

		}

		if(isset($_FILES) and isset($_FILES["maaddfilesupload"])){
			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/'
				]);
			}

			foreach($_FILES['maaddfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['maaddfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Master Supply Agreements/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}


/////////////////
		echo json_encode(array("error"=>""));
	//}
}else if(isset($_POST["maauto"]) and isset($_POST["masavename"]) and isset($_POST["mavalue"]) and $_POST["masavename"] != "" and $_POST["maauto"] != "" and $_POST["maauto"] != 0)
{
	$error="Error occured";
	$new_value=array();

	$masterid = $mysqli->real_escape_string(@trim($_POST["maauto"]));
	$masavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["masavename"])));
	$mavalue = $mysqli->real_escape_string(@trim($_POST["mavalue"]));


	if($masavename == "Start Date" or $masavename == "End Date"){
		$mavalue = @date_format(@date_create_from_format('m/d/Y', $mavalue), 'Y-m-d');
	}

	if(($masavename == "VendorID" or $masavename == "ClientID") and $mavalue==0){
		echo json_encode(array("error"=>5));
		die();
	}

	$new_value[$masavename]=$mavalue;

	audit_log($mysqli,'master_agreements','UPDATE',$new_value,'WHERE MasterID='.$masterid,'','','MasterID');
	$sql='Update master_agreements SET `'.$masavename.'` = "'.$mavalue.'" WHERE MasterID="'.$masterid.'"';
	$stmt = $mysqli->prepare($sql);
	if($stmt){
		$stmt->execute();
		$cnc=1;
		if($stmt->affected_rows == 1){
			$cnc=$mysqli->insert_id;
		}else{

		}
	}else{
		echo json_encode(array("error"=>$error));
		die();
	}
/////////////////
	echo json_encode(array("error"=>""));
}else if(isset($user_one) and $user_one != "" and $user_one != 0 and isset($_POST["sss"]) and $_POST["sss"] !="")
{//die();

	$error="Error occured";
	$sub_query=array();
	$sid=$mysqli->real_escape_string(@trim($_POST['sss']));

	$arr_utility_service_type=$arr_vendor_name=$arr_account=$arr_meter=array();
	$utility_service_type=$vendor_name=$account=$meter=$cncerror=$s3foldername="";
	$cnc=0;
	foreach ($_POST as $param_name => $param_val) {
		if($param_name == "sss" || $param_name == "rvfile") continue;
		if($param_name == "utility_service_type") $utility_service_type=@trim($param_val);
		if($param_name == "vendor_name") $vendor_name=@trim($param_val);
		if($param_name == "account") $account=@trim($param_val);
		if($param_name == "meter") $meter=@trim($param_val);

		$sub_query[]=$param_name.'="'.$mysqli->real_escape_string(@trim($param_val)).'"';
	}

	//if(@trim($account) == ""){echo json_encode(array("error"=>$error."2"));die();}

	//if(@trim($utility_service_type)=="" || @trim($vendor_name)=="" || @trim($account)=="" || @trim($meter)==""){echo json_encode(array("error"=>5));die();}

	//if(!count($arr_account) or !count($sub_query)){echo json_encode(array("error"=>$error));die();}



	//die();


	if ($stmtttt = $mysqli->prepare('Select ss.company_id,ss.s3_foldername From startstop_status ss , user up Where ss.id="'.$sid.'" and up.company_id=ss.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ' and  up.user_id = '.$_SESSION["user_id"]:'').' LIMIT 1')) {

		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_id,$s3foldername);
			$stmtttt->fetch();
			if($s3foldername=="") $s3foldername=time().rand(0,9).rand(10,90);
		}else{echo false;exit();}
	}else{echo false;exit();}

	if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

	if($s3foldername=="") $cncerror="error";

	if(isset($_FILES) and isset($_FILES["sssfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';

		if($cncerror != "") break;

		$sql='Update startstop_status SET added_by_user_id="'.$user_one.'",'.$s3foldersql.implode(",",$sub_query).' WHERE id="'.$sid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$cnc=1;
			if($stmt->affected_rows == 1){
				$cnc=$mysqli->insert_id;
			}else{
				//$cncerror="error";
				//break;
			}
		}else{
			$cncerror="error";
			break;
		}

	if($cncerror != ""){
		if($cnc == 0){
			$sql='Delete from  startstop_status where id='.$sid;
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
			}
		}
		echo json_encode(array("error"=>$error));
	}else{
////////////////////
		$profile = 'default';

		$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
		]);

		if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {

			$stmtttt->execute();
			$stmtttt->store_result();
			if ($stmtttt->num_rows > 0) {
				$stmtttt->bind_result($company_name);
				$stmtttt->fetch();
			}else{echo false;exit();}
		}else{echo false;exit();}



		$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/');
		if(!$info)
		{
			//echo false;
			//exit();
		}

		$rvfilearr=array();
		if(isset($_POST["rvfile"]) and $_POST["rvfile"] !=""){
			$rvfilearr=explode(",",$_POST["rvfile"]);
			if(count($rvfilearr)){
				foreach($rvfilearr as $kyy=>$vll){
					if($vll !=""){
						$s3Client->deleteObject([
							'Bucket' => 'datahub360',
							'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'.$vll
						]);
					}
				}
			}
		}


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
					$file_name = $vl;
					$temp_file_location = $_FILES['sssfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}


/////////////////
		echo json_encode(array("error"=>""));
	}
}

//print_r($_POST);
echo false;
exit();
?>
