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

$validextensions = array("jpeg", "jpg", "png","pdf","txt","doc","docx","xls","xlsx","ppt","pptx","gif","mpeg","mp3","avi");





if(isset($_POST["new"]) and $_POST["new"]=="new")
{

	$error="Error occured";
	$sub_query=$new_value=$fname=array();
	$fileok=0;
	$companyname="";

	if(isset($_POST['cid']) and @trim($_POST['cid']) != "")
	{
		$cid=$mysqli->real_escape_string(@trim($_POST['cid']));
		$stmtsk = $mysqli->prepare('SELECT company_name FROM company WHERE company_id="'.$cid.'"  LIMIT 1');

//('SELECT company_name FROM company WHERE id="'.$cid.'"  LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{
				$stmtsk->bind_result($__cname);
				$stmtsk->fetch();
				$companyname=$__cname;

				$sub_query[]='company_id="'.$cid.'"';
				$new_value['company_id']=$cid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company Name doesnot exist.'));
				exit();
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company Name required.'));
		exit();
	}

	/*if(isset($_POST['location']) and @trim($_POST['location']) != "")
	{
		$location=$mysqli->real_escape_string(@trim($_POST['location']));
		$sub_query[]='location="'.$location.'"';
		$new_value['location']=$location;
	}else{
		echo json_encode(array('error'=>'Error Occured! Location required.'));
		exit();
	}*/

	if(isset($_POST['category']) and @trim($_POST['category']) != "")
	{
		$sub_query[]='category="'.$mysqli->real_escape_string(@trim($_POST['category'])).'"';
		$new_value['category']=$mysqli->real_escape_string(@trim($_POST['category']));
	}

	if(isset($_POST['location']) and @trim($_POST['location']) != "")
	{
		$sub_query[]='location="'.$mysqli->real_escape_string(@trim($_POST['location'])).'"';
		$new_value['location']=$mysqli->real_escape_string(@trim($_POST['location']));
	}

	if(isset($_POST['commodity']) and @trim($_POST['commodity']) != "")
	{
		$sub_query[]='commodity="'.$mysqli->real_escape_string(@trim($_POST['commodity'])).'"';
		$new_value['commodity']=$mysqli->real_escape_string(@trim($_POST['commodity']));
	}

	if(isset($_POST['startdate']) and @trim($_POST['startdate']) != "")
	{
		$sub_query[]='start="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate'])))).'"';
		$new_value['start']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate']))));
	}

	if(isset($_POST['enddate']) and @trim($_POST['enddate']) != "")
	{
		$sub_query[]='end="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate'])))).'"';
		$new_value['end']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate']))));
	}

	if(isset($_POST['saving']) and @trim($_POST['saving']) != "")
	{
		$sub_query[]='saving="'.$mysqli->real_escape_string(@trim($_POST['saving'])).'"';
		$new_value['saving']=$mysqli->real_escape_string(@trim($_POST['saving']));
	}

	if(isset($_POST['read']) and @trim($_POST['read']) != "" and (@trim($_POST['read']) == "Y" or @trim($_POST['read']) == "N"))
	{
		$sub_query[]='_read="'.$mysqli->real_escape_string(@trim($_POST['read'])).'"';
		$new_value['_read']=$mysqli->real_escape_string(@trim($_POST['read']));
	}

	if(isset($_POST['dateadded']) and @trim($_POST['dateadded']) != "")
	{
		$sub_query[]='date_added="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded'])))).'"';
		$new_value['date_added']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded']))));
	}

	//File Edit



	if(isset($_FILES) and isset($_FILES["saaddfilesupload"])){

		foreach($_FILES['saaddfilesupload']['name'] as $ky => $vl){
			$fname[] = $vl;
		}
	}

	if(count($fname)){
		$sub_query[]='link="'.$mysqli->real_escape_string(@trim(implode("@@;@@",$fname))).'"';
		$new_value['link']=$mysqli->real_escape_string(@trim(implode("@@;@@",$fname)));
	}

	//File Edit Ends

	if(count($sub_query)){

		//audit_log($mysqli,"company","INSERT",$new_value,"",($fileok==1?"New":""),"");
		$sql='INSERT INTO saving_analysis SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){

			}else{
				echo json_encode(array("error"=>$error));
				exit();
			}
		}else{
			echo json_encode(array("error"=>$error));
			exit();
		}

		if(isset($_FILES) and isset($_FILES["saaddfilesupload"])){

			$profile = 'default';

			$s3Client = new S3Client([
				'region'      => 'us-west-2',
				'version'     => 'latest',
				'credentials' => [
	           'key' => $_ENV['aws_access_key_id'],
	           'secret' => $_ENV['aws_secret_access_key']
	       ]
			]);


			$infotaget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.strip_tags($companyname).'/saving analysis/');
			if(!$infotaget)
			{
				$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.strip_tags($companyname).'/saving analysis/'
				]);
			}

			foreach($_FILES['saaddfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['saaddfilesupload']['tmp_name'][$ky];


					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.strip_tags($companyname).'/saving analysis/'.$file_name,
						'SourceFile' => $temp_file_location
					]);
			}
		}
	}

	echo json_encode(array("error"=>""));
	exit();

//Add Saving Analysis Ends
}else if(isset($_POST["saauto"]) and isset($_POST["sasavename"]) and isset($_POST["savalue"]) and $_POST["sasavename"] != "" and $_POST["saauto"] != "" and $_POST["saauto"] != 0)
{
	$error="Error occured";
	$new_value=array();

	$masterid = $mysqli->real_escape_string(@trim($_POST["saauto"]));
	$masavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["sasavename"])));
	$mavalue = $mysqli->real_escape_string(@trim($_POST["savalue"]));


	if($masavename == "start" or $masavename == "end" or $masavename == "date_added"){
		$mavalue = @date_format(@date_create_from_format('m/d/Y', $mavalue), 'Y-m-d');
	}

	if(($masavename == "company_id") and $mavalue==0){
		echo json_encode(array("error"=>5));
		die();
	}

	$new_value[$masavename]=$mavalue;

	audit_log($mysqli,'saving_analysis','UPDATE',$new_value,'WHERE id='.$masterid,'','','id');
	$sql='Update saving_analysis SET `'.$masavename.'` = "'.$mavalue.'" WHERE id="'.$masterid.'"';
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

?>
