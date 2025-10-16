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


if(isset($_POST["cmaccadd"]) and $_POST["cmaccadd"]=="new" and isset($_POST["ContractID"]) and $_POST["ContractID"] != 0 and !empty($_POST["ContractID"]))
{
	$error="Error occured";
	$sub_query=array();

	$cmid=$mysqli->real_escape_string(@trim($_POST["ContractID"]));

   if ($stmt = $mysqli->prepare('SELECT c.company_name FROM company c,contracts ct where c.company_id=ct.ClientID and ct.ContractID="'.$cmid.'" LIMIT 1')){
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name);
			$stmt->fetch();
		}else{
			echo false;
			exit();
		}
	}else{
		echo false;
		exit();
	}



	foreach ($_POST as $param_name => $param_val) {
		$param_name=str_ireplace("@"," ",$param_name);
		if($param_name == "cmaccadd") continue;
		if($param_name == "MeterID"){$meter_id=@trim($param_val);}
		if($param_name == "AccountID"){$acc_id=@trim($param_val);}


		$sub_query[]= '`'.$param_name.'`="'.$mysqli->real_escape_string(@trim($param_val)).'"';
	}

	if(@trim($meter_id) == "" || $meter_id == 0 || @trim($acc_id) == "" || $acc_id == 0){echo json_encode(array("error"=>5));die();}



	if(!count($sub_query)){echo json_encode(array("error"=>$error));die();}

	$s3foldername=time().rand(0,9).rand(10,90);

	if(isset($_FILES) and isset($_FILES["cmaccaddfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';



	$sql='INSERT INTO contract_accounts SET ContractAcctID=null,'.$s3foldersql.implode(',',$sub_query);
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



		$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/');
		if(!$info)
		{

		}

		if(isset($_FILES) and isset($_FILES["cmaccaddfilesupload"])){
			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/Contract Accounts/'.$s3foldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/Contract Accounts/'.$s3foldername.'/'
				]);
			}

			foreach($_FILES['cmaccaddfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['cmaccaddfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/Contract Accounts/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}


/////////////////
		echo json_encode(array("error"=>""));
	//}
}elseif(isset($_POST["cmadd"]) and $_POST["cmadd"]=="new")
{
	$error="Error occured";
	$sub_query=array();


	$company_id="";
	foreach ($_POST as $param_name => $param_val) {
		$param_name=str_ireplace("@"," ",$param_name);
		if($param_name == "cmadd") continue;
		if($param_name == "ClientID"){$company_id=@trim($param_val);}
		if($param_name == "VendorID"){$vendor_id=@trim($param_val);}
		if($param_name == "AdvisorID"){$advisor_id=@trim($param_val);}
		if($param_name == "Country"){$country=@trim($param_val);}
		if($param_name == "State"){$state=@trim($param_val);}

		if(($param_name == "Start Date" or $param_name == "End Date" or $param_name == "Initiated Date") and $param_val != ""){
			$param_val = @date_format(@date_create_from_format('m/d/Y', $param_val), 'Y-m-d');
		}

		$sub_query[]= '`'.$param_name.'`="'.$mysqli->real_escape_string(@trim($param_val)).'"';
	}

	if(@trim($vendor_id) == "" || $vendor_id == 0 || @trim($company_id) == "" || $company_id == 0 || @trim($advisor_id) == "" || $advisor_id == 0 || empty(@trim($country)) || empty(@trim($state))){echo json_encode(array("error"=>5));die();}



	if(!count($sub_query)){echo json_encode(array("error"=>$error));die();}

	$s3foldername=time().rand(0,9).rand(10,90);

	if(isset($_FILES) and isset($_FILES["cmaddfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';



	$sql='INSERT INTO contracts SET ContractID=null,'.$s3foldersql.implode(',',$sub_query);
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



		$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/');
		if(!$info)
		{

		}

		if(isset($_FILES) and isset($_FILES["cmaddfilesupload"])){
			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'
				]);
			}

			foreach($_FILES['cmaddfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['cmaddfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}


/////////////////
		echo json_encode(array("error"=>""));
	//}
}elseif(isset($_POST["maauto"]) and isset($_POST["masavename"]) and isset($_POST["mavalue"]) and $_POST["masavename"] != "" and $_POST["maauto"] != "" and $_POST["maauto"] != 0)
{
	$error="Error occured";
	$new_value=array();

	$masterid = $mysqli->real_escape_string(@trim($_POST["maauto"]));
	$masavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["masavename"])));
	$mavalue = $mysqli->real_escape_string(@trim($_POST["mavalue"]));


	if($masavename == "Start Date" or $masavename == "End Date" or $masavename == "Initiated Date" and !empty($mavalue)){
		$mavalue = @date_format(@date_create_from_format('m/d/Y', $mavalue), 'Y-m-d');
	}
	
	if((($masavename == "ClientID" or $masavename == "AdvisorID") and $mavalue==0) or (($masavename == "Country" or $masavename == "State") and empty($mavalue))){
		echo json_encode(array("error"=>5));
		die();
	}
	
	if ( $masavename == "SupplierID" and trim($mavalue) == "" ) {
		echo json_encode(array("error"=>5));
		die();
	}

	$new_value[$masavename]=$mavalue;

	audit_log($mysqli,'contracts','UPDATE',$new_value,'WHERE ContractID='.$masterid,'','','ContractID');
	//$sql='SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,v.vendor_name,c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and cm.ContractID="'.$masterid.'" LIMIT 1';
	$sql='Update contracts SET `'.$masavename.'` = "'.$mavalue.'" WHERE ContractID="'.$masterid.'"';
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
}elseif(isset($_POST["maaaceditauto"]) and isset($_POST["maaaceditsavename"]) and isset($_POST["maaaceditvalue"]) and $_POST["maaaceditsavename"] != "" and $_POST["maaaceditauto"] != "" and $_POST["maaaceditauto"] != 0)
{
	$error="Error occured";
	$new_value=array();

	$masterid = $mysqli->real_escape_string(@trim($_POST["maaaceditauto"]));
	$masavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["maaaceditsavename"])));
	$mavalue = $mysqli->real_escape_string(@trim($_POST["maaaceditvalue"]));

	if((($masavename == "AccountID" or $masavename == "MeterID") and $mavalue==0)){
		echo json_encode(array("error"=>5));
		die();
	}

	$new_value[$masavename]=$mavalue;

	audit_log($mysqli,'contract_accounts','UPDATE',$new_value,'WHERE ContractAcctID='.$masterid,'','','ContractAcctID');

	$sql='Update contract_accounts SET `'.$masavename.'` = "'.$mavalue.'" WHERE ContractAcctID="'.$masterid.'"';
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
}else echo false;
exit();


?>
