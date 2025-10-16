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

$todaysdate=@date("m/d/Y");


if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];
$uemail=$_SESSION['email'];
$uname=$_SESSION['fullname'];

//Add start new services
if(isset($user_one) and $user_one != "" and $user_one != 0 and isset($_POST["sns"]) and $_POST["sns"]=="sns")
{
	$error="Error occured";
	$sub_query=array();
	//print_r($_POST);die();
	$arr_utility_service_type=$arr_vendor_name=$arr_account=$arr_meter=$cnc=$cnctmp=array();
	$utility_service_type=$vendor_name=$account=$meter=$cncerror=$date_requested=$site_numbervlc=$site_namevlc="";
	$company_id="";
	foreach ($_POST as $param_name => $param_val) {
		if($param_name == "sns") continue;
		if($param_name == "utility_service_type"){$utility_service_type=@trim($param_val);continue;}
		if($param_name == "vendor_name"){$vendor_name=@trim($param_val);continue;}
		if($param_name == "account"){$account=@trim($param_val);continue;}
		if($param_name == "meter"){$meter=@trim($param_val);continue;}
		if($param_name == "company_id"){$company_id=@trim($param_val);}
		if($param_name == "date_requested"){$date_requested=@trim($param_val);}

		$sub_query[]=$param_name.'="'.$mysqli->real_escape_string(@trim($param_val)).'"';

		if($param_name == "site_number"){$site_numbervlc=@trim($param_val);}
		if($param_name == "site_name"){$site_namevlc=@trim($param_val);}
	}

	if(@trim($account) == "" || @trim($company_id) == "" || $company_id == 0){echo json_encode(array("error"=>$error));die();}


	if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

	$arr_utility_service_type=explode("@@",$utility_service_type);
	$arr_vendor_name=explode("@@",$vendor_name);
	$arr_account=explode("@@",$account);
	if(@trim(@str_replace("@","",$meter)) !="") $arr_meter=explode("@@",$meter);




if(@trim(@str_replace("@","",$utility_service_type))=="" || @trim(@str_replace("@","",$vendor_name))=="" || @trim(@str_replace("@","",$account))=="" || @trim($date_requested) == ""){echo json_encode(array("error"=>5));die();}

	if(!count($arr_account) or !count($sub_query)){echo json_encode(array("error"=>$error));die();}

	$s3foldername=time().rand(0,9).rand(10,90);

	if(isset($_FILES) and isset($_FILES["snsfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';

	foreach($arr_account as $kya => $vla){
		if($cncerror != "") break;
		if(@trim($arr_utility_service_type[$kya]) == "" and @trim($arr_vendor_name[$kya]) == "" and @trim($arr_account[$kya]) == "")
		{// and @trim($arr_meter[$kya]) == ""

		}else{
			if(count($arr_meter) and isset($arr_meter[$kya]) and @trim($arr_meter[$kya])!= "") $ssql=', meter="'.$mysqli->real_escape_string(@trim($arr_meter[$kya])).'"';
			else $ssql='';

			$sql='INSERT INTO startstop_status SET id=null,added_by_user_id="'.$user_one.'", utility_service_type="'.$mysqli->real_escape_string(@trim($arr_utility_service_type[$kya])).'", vendor_name="'.$mysqli->real_escape_string(@trim($arr_vendor_name[$kya])).'", previous_account_number="'.$mysqli->real_escape_string(@trim($arr_account[$kya])).'"'.$ssql.',request_type="Start Service",'.$s3foldersql.implode(",",$sub_query);
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
				if($stmt->affected_rows == 1){
					$cnc[]=$mysqli->insert_id;
					$cnctmp[$mysqli->insert_id]=array("utility_service_type"=>@trim($arr_utility_service_type[$kya]),"vendor_name"=>@trim($arr_vendor_name[$kya]), "account_number"=>@trim($arr_account[$kya]),"meter"=>@trim($arr_meter[$kya]));
				}else{
					$cncerror="error";
					break;
				}
			}else{
				$cncerror="error";
				break;
			}
		}
	}

	if($cncerror != ""){
		if(count($cnc)){
			$sql='Delete from  startstop_status where id in ('.implode(',',$cnc).')';
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
			}
		}
		echo json_encode(array("error"=>$error));
		die();
	}else{
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

		if(isset($_FILES) and isset($_FILES["snsfilesupload"])){
			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'
				]);
			}

			foreach($_FILES['snsfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['snsfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}
		//$message=$messagetype."<br>---------<br>Name: ".$hd_firstname." ".$hd_lastname . "<br>Email: ".$hd_email."<br>Priority: ".$priority."<br><br>".$message;
		if(is_array($cnc) and count($cnc)){
			$message="Hi ".$uname."<br>New start service created.<br>Ticket: ".implode(',',$cnc);
			//$message2="Hi<br>New start service created by Name:".$uname.", Email:".$uemail.".<br>Ticket: ".implode(',',$cnc);
			$subject="Start Service created successfully!";
			@firemail2($uemail,"support@vervantis.com",$subject,$message);
			foreach($cnc as $kycnc=>$vlcnc){
				$message2="Ticket # ".$vlcnc.",<br>Date Requested: ".$date_requested.",<br>Start Service,<br>Requested User Name: ".$uname.",<br>Requested User Number: ".$user_one.",<br>Client Name: ".$company_name.",<br>Client Number: ".$cname.",<br>Site Number: ".$site_numbervlc.",<br>Site Name: ".$site_namevlc.",<br>Service Type: ".$cnctmp[$vlcnc]["utility_service_type"].",<br>Vendor Name: ".$cnctmp[$vlcnc]["vendor_name"].",<br>Account Number: ".$cnctmp[$vlcnc]["account_number"].",<br>Meter Number: ".$cnctmp[$vlcnc]["meter"];

				$subject="Ticket # ".$vlcnc.", ".$todaysdate.", Start Service, ".$company_name;
				//$message2="Hi<br>New start service created by Name:".$uname.", Email:".$uemail.".<br>Ticket: ".$vlcnc;
				@firemail2("operations@vervantis.com","support@vervantis.com",$subject,$message2);
			}
		}
/////////////////
		echo json_encode(array("error"=>""));
	}
}else if(isset($user_one) and $user_one != "" and $user_one != 0 and isset($_POST["ss"]) and $_POST["ss"]=="ss")
{
	$error="Error occured";
	$sub_query=array();

	$arr_utility_service_type=$arr_vendor_name=$arr_account=$arr_meter=$cnc=$cnctmp=array();
	$utility_service_type=$vendor_name=$account=$meter=$cncerror=$company_id=$site_numbervlc=$site_namevlc="";
	foreach ($_POST as $param_name => $param_val) {
		if($param_name == "ss" || $param_name == "siteclose") continue;
		if($param_name == "utility_service_type"){$utility_service_type=@trim($param_val);continue;}
		if($param_name == "vendor_name"){$vendor_name=@trim($param_val);continue;}
		if($param_name == "account"){$account=@trim($param_val);continue;}
		if($param_name == "meter"){$meter=@trim($param_val);continue;}
		if($param_name == "company_id"){$company_id=@trim($param_val);}
		if($param_name == "date_requested"){$date_requested=@trim($param_val);}

		$sub_query[]=$param_name.'="'.$mysqli->real_escape_string(@trim($param_val)).'"';

		if($param_name == "site_number"){$site_numbervlc=@trim($param_val);}
		if($param_name == "site_name"){$site_namevlc=@trim($param_val);}
	}


	if(empty(@trim($account)) or empty(@trim($company_id))){echo json_encode(array("error"=>$error));die();}


	if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

	$arr_utility_service_type= @explode("@@",$utility_service_type);
	$arr_vendor_name= @explode("@@",$vendor_name);
	$arr_account= @explode("@@",$account);
	if(@trim(@str_replace("@","",$meter)) !="")$arr_meter= @explode("@@",$meter);


if(@trim(@str_replace("@","",$utility_service_type))=="" || @trim(@str_replace("@","",$vendor_name))=="" || @trim(@str_replace("@","",$account))=="" || @trim($date_requested) == ""){echo json_encode(array("error"=>5));die();}
	// and @trim(@str_replace("@","",$meter))==""
	//if(!isset($_POST["siteclose"])) {echo json_encode(array("error"=>5));die();}

	if(!count($arr_account) or !count($sub_query)){echo json_encode(array("error"=>$error));die();}


	$s3foldername=time().rand(0,9).rand(10,90);

	if(isset($_FILES) and isset($_FILES["ssfilesupload"])){$s3foldersql='s3_foldername="'.$s3foldername.'",';}
	else $s3foldersql='';

	foreach($arr_account as $kya => $vla){
		if($cncerror != "") break;
		//if(@trim($arr_utility_service_type[$kya]) == "" and @trim($arr_vendor_name[$kya]) == "" and @trim($arr_account[$kya]) == "" and (!isset($_POST["siteclose"])))
		if(@trim($arr_utility_service_type[$kya]) == "" and @trim($arr_vendor_name[$kya]) == "" and @trim($arr_account[$kya]) == "")
		{// and @trim($arr_meter[$kya]) == ""

		}else{
			if(count($arr_meter) and isset($arr_meter[$kya]) and @trim($arr_meter[$kya])!= "") $ssql=', meter="'.$mysqli->real_escape_string(@trim($arr_meter[$kya])).'"';
			else $ssql='';

			$sql='INSERT INTO startstop_status SET id=null,added_by_user_id="'.$user_one.'", utility_service_type="'.$mysqli->real_escape_string(@trim($arr_utility_service_type[$kya])).'", vendor_name="'.$mysqli->real_escape_string(@trim($arr_vendor_name[$kya])).'", account_number="'.$mysqli->real_escape_string(@trim($arr_account[$kya])).'"'.$ssql.', request_type="Stop Service",'.$s3foldersql.implode(",",$sub_query);
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
				if($stmt->affected_rows == 1){
					$cnc[]=$mysqli->insert_id;
					$cnctmp[$mysqli->insert_id]=array("utility_service_type"=>@trim($arr_utility_service_type[$kya]),"vendor_name"=>@trim($arr_vendor_name[$kya]), "account_number"=>@trim($arr_account[$kya]),"meter"=>@trim($arr_meter[$kya]));
				}else{
					$cncerror="error";
					break;
				}
			}else{
				$cncerror="error";
				break;
			}
		}
	}

	if($cncerror != ""){
		if(count($cnc)){
			$sql='Delete from  startstop_status where id in ('.implode(',',$cnc).')';
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
			}
		}
		echo json_encode(array("error"=>$error));
		die();
	}else{
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

		if(isset($_FILES) and isset($_FILES["ssfilesupload"])){
			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'
				]);
			}

			foreach($_FILES['ssfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['ssfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/Start Stop Status/'.$s3foldername.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}

		if(is_array($cnc) and count($cnc)){
			$message="Hi ".$uname."<br>New stop service created.<br>Ticket: ".implode(',',$cnc);
			//$message2="Hi<br>New stop service created by Name:".$uname.", Email:".$uemail.".<br>Ticket: ".implode(',',$cnc);
			$subject="Stop Service created successfully!";
			@firemail2($uemail,"support@vervantis.com",$subject,$message);
			foreach($cnc as $kycnc=>$vlcnc){
				$message2="Ticket # ".$vlcnc.",<br>Date Requested: ".$date_requested.",<br>Stop Service,<br>Requested User Name: ".$uname.",<br>Requested User Number: ".$user_one.",<br>Client Name: ".$company_name.",<br>Client Number: ".$cname.",<br>Site Number: ".$site_numbervlc.",<br>Site Name: ".$site_namevlc.",<br>Service Type: ".$cnctmp[$vlcnc]["utility_service_type"].",<br>Vendor Name: ".$cnctmp[$vlcnc]["vendor_name"].",<br>Account Number: ".$cnctmp[$vlcnc]["account_number"].",<br>Meter Number: ".$cnctmp[$vlcnc]["meter"];

				$subject="Ticket # ".$vlcnc.", ".$todaysdate.", Stop Service, ".$company_name;
				@firemail2("operations@vervantis.com","support@vervantis.com",$subject,$message2);
			}
		}
/////////////////
		echo json_encode(array("error"=>""));
	}
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

		if($cncerror == ""){
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
				//break;
			}
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
}else if(isset($_POST["sssauto"]) and isset($_POST["ssssavename"]) and isset($_POST["sssvalue"]) and $_POST["ssssavename"] != "" and $_POST["sssauto"] != "" and $_POST["sssauto"] != 0)
{

	$error="Error occured";
	$new_value=array();

	$sid = $mysqli->real_escape_string(@trim($_POST["sssauto"]));
	//$masavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$masavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	//$masavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$mavalue = $mysqli->real_escape_string(@trim($_POST["sssvalue"]));


	if($masavename == "Start Date" or $masavename == "End Date"){
		$mavalue = @date_format(@date_create_from_format('m/d/Y', $mavalue), 'Y-m-d');
	}

	if(($masavename == "site_name" or $masavename == "site_number") and ($mavalue=="0" || $mavalue=="")){
		echo json_encode(array("error"=>5));
		die();
	}

	if($masavename == "status_date"){
		//$mavalue = @date_format(@date_create_from_format('m/d/Y', $mavalue), 'Y-m-d');
		$mavalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($mavalue)));
	}

	if(($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)) $checksql='Select ss.id,ss.status From startstop_status ss Where ss.id="'.$sid.'" LIMIT 1';
	elseif(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)) $checksql='Select ss.id,ss.status From startstop_status ss , user up Where ss.id="'.$sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"].' LIMIT 1';
	else {	echo false;exit();}
	if ($stmtttt = $mysqli->prepare($checksql)) {

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave,$status);
			$stmtttt->fetch();

			if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			{	echo false;exit();}

			$new_value[$masavename]=$mysqli->real_escape_string($mavalue);
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $masavename."l".$mavalue;die();

	if($masavename == "status"){
		$timenow = $mysqli->real_escape_string(@date('Y-m-d H:i:s'));
		$new_value['status_date']=@trim($timenow);
		$sql='Update startstop_status SET `'.$masavename.'` = "'.$mysqli->real_escape_string($mavalue).'", `status_date`="'.$timenow.'" WHERE id="'.$sid.'"';
	}else{
		if($masavename == "company_id" and ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){

			if ($stmtc = $mysqli->prepare('SELECT company_name FROM `company` where company_id !=1 and company_id != 2 and company_id='.$mavalue.' LIMIT 1')) {

				$stmtc->execute();
				$stmtc->store_result();
				if ($stmtc->num_rows > 0) {
					$stmtc->bind_result($ccompany_name);
					$stmtc->fetch();

					if ($stmtc = $mysqli->prepare('SELECT `Entity Name`,`Tax ID`,`Billing Address1`,`Billing Address2`,`Billing City`,`Billing State`,`Billing Zip Code` FROM `company_defaults` WHERE companyID='.$mavalue.' LIMIT 1')) {

						$stmtc->execute();
						$stmtc->store_result();
						if ($stmtc->num_rows > 0) {
							$stmtc->bind_result($cdentityname,$cdtaxid,$cdbilladdr1,$cdbilladdr2,$cdbillcity,$cdbillstate,$cdbillzip);
							$stmtc->fetch();



							$sql='UPDATE startstop_status SET company_id='.$mavalue.', entity_name="'.$mysqli->real_escape_string($cdentityname).'",federal_tax_id="'.$mysqli->real_escape_string($cdtaxid).'",billing_address1="'.$mysqli->real_escape_string($cdbilladdr1).'",billing_address2="'.$mysqli->real_escape_string($cdbilladdr2).'",billing_city="'.$mysqli->real_escape_string($cdbillcity).'",billing_state="'.$mysqli->real_escape_string($cdbillstate).'",billing_zip="'.$mysqli->real_escape_string($cdbillzip).'"  WHERE id="'.$sid.'"';

						}else{echo json_encode(array("error"=>6));exit();}
					}else{echo json_encode(array("error"=>6));exit();}
						//}else{echo false;exit();}
					//}else{echo false;exit();}
				}else{echo false;exit();}
			}else{echo false;exit();}
		}else
		$sql='Update startstop_status SET `'.$masavename.'` = "'.$mysqli->real_escape_string($mavalue).'" WHERE id="'.$sid.'"';
	}

	audit_log($mysqli,'startstop_status','UPDATE',$new_value,'WHERE id='.$sid,'','');
	$stmt = $mysqli->prepare($sql);
	if($stmt){
		$stmt->execute();
		$cnc=1;
		if($stmt->affected_rows == 1){
			//$cnc=$mysqli->insert_id;
		}else{

		}
	}else{
		echo json_encode(array("error"=>$error));
		die();
	}


/////////////////
	echo json_encode(array("error"=>""));
}

//print_r($_POST);
echo false;
exit();

function firemail2($to="",$from="",$subject="",$message="",$header=""){//return true;die();
		  $messageArgs =  array('subject' => $subject,
		  'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
		  'toRecipients' => array( array('name' => '', 'address' => $to) ),     // name is optional .earlier $emailuser
		  'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
		  'importance' => 'normal',
		  'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
		  'body' => $message,
		  'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
		  'attachments' => array( )
		  );

			custommsmail('noreply@vervantis.com', $messageArgs,'');
}
function firemail3($to="",$from="",$subject="",$message="",$header=""){return true;die();
	if(@trim($to)=="" || @trim($from)=="" || @trim($subject)=="" || @trim($message)=="") return false;
	$to='support@vervantis.com';
	require '../plugins/PHPmailer/class.phpmailer.php';
	$message = wordwrap($message,70, "<br>");
	$mail = new PHPMailer(true);
	$mail->CharSet = "UTF-8";
	$mail->isSMTP();
	$mail->Host = 'smtp.office365.com';
	$mail->Port       = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth   = true;
	$mail->Username = 'support@vervantis.com';
	$mail->Password = 'QhUYZquYQL4oxV#J@ymC';
	$mail->SetFrom('support@vervantis.com', 'FromEmail');
	//$mail->SetFrom($from, 'FromEmail');
	$mail->addAddress($to, 'ToEmail');
	//$mail->SMTPDebug  = 3;
	//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';
	$mail->IsHTML(false);
	$mail->SMTPDebug = 0;

	$mail->Subject = $subject;
	$mail->Body    = $message;
	//$mail->AltBody = $message;

	if(!$mail->send()) {
		echo 'Error Occured.  Try again later.  If this issue persists, please contact support@vervantis.com.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo true;
	}
}
?>
