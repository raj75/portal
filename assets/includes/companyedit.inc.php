<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

ini_set('max_execution_time', 0);

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

$docnarr=array();
$docnarr[]= "Energy Procurement";
$docnarr[]= "Energy Accounting";
$docnarr[]= "Data Management";
$docnarr[]= "Sustainability";
$docnarr[]= "Master Supply Agreements";
$docnarr[]= "Projects";
$docnarr[]= "Rate Optimization";
$docnarr[]= "Start Stop Status";
$docnarr[]= "Supplier Contracts";
$docnarr[]= "focus items";
$docnarr[]= "saving analysis";
$docnarr[]= "Rate Optimization/Regulated Information";
$docnarr[]= "Rate Optimization/Utility Rate Reports";
$docnarr[]= "Rate Optimization/Utility Rate Change Requests";
$docnarr[]= "Energy Procurement/Direct Access Information";
$docnarr[]= "Energy Procurement/Strategy";
$docnarr[]= "Energy Procurement/Dynamic Risk Management";
$docnarr[]= "Energy Procurement/Strategy/Contracts";
$docnarr[]= "Energy Procurement/Strategy/Master Agreements";
$docnarr[]= "Energy Procurement/Strategy/Process";
$docnarr[]= "Energy Procurement/Strategy/Supplier Tracking";
$docnarr[]= "Energy Procurement/Strategy/Contracts/Electric";
$docnarr[]= "Energy Procurement/Strategy/Contracts/Natural Gas";
$docnarr[]= "Data Management/Data Analysis";
$docnarr[]= "Data Management/Custom Reports";
$docnarr[]= "Data Management/Consumption Reports";
$docnarr[]= "Energy Accounting/Invoice Validation";
$docnarr[]= "Energy Accounting/Utility Budgets";
$docnarr[]= "Energy Accounting/Exception Reports";
$docnarr[]= "Energy Accounting/Resolved Exceptions";
$docnarr[]= "Energy Accounting/Site and Account Changes";
$docnarr[]= "Sustainability/Sustainability Reports";
$docnarr[]= "Sustainability/Corporate Reports";
$docnarr[]= "Sustainability/Surveys";
$docnarr[]= "Projects/Distributed Generation";
$docnarr[]= "Projects/Efficiency Upgrades";
$docnarr[]= "Projects/EV Charging";
$docnarr[]= "Projects/Rebates and Incentives";
$docnarr[]= "Projects/Other";

if(isset($group_id) and ($group_id == 1 or $_SESSION["group_id"] == 2) and isset($_POST["cpy"]) and @trim($_POST["cpy"]) != "" and @trim($_POST["cpy"]) != 0 and isset($_POST["edit"]))
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);

	$error="Error occured";
	$sub_query=$new_value=array();

	$tmp_cpy=$mysqli->real_escape_string(@trim($_POST["cpy"]));


	///Comment this later
	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id != 1 and company_id='.$tmp_cpy)) {

		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($company_name);
			$stmtttt->fetch();

			foreach($docnarr as $vlll){
				$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/'.$vlll.'/');
				if(!$info)
				{
					$s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/'.$vlll.'/'
					]);
				}
			}
		}
	}

	if(isset($_POST['cname']) and @trim($_POST['cname']) != "")
	{
		$__cname = $mysqli->real_escape_string(@trim($_POST['cname']));
		$stmtsk = $mysqli->prepare('SELECT company_id FROM company where company_name="'.$__cname.'" LIMIT 1');

//('SELECT id FROM company where company_name="'.$__cname.'" LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows > 0)
			{
				$sub_query[]='company_name="'.$__cname.'"';
				$new_value['company_name']=$__cname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company Name doesn\'t exists.'));
				exit();
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
		}
	}

	/*if(isset($_POST['skype']) and @trim($_POST['skype']) != "")
	{
		$sub_query[]='skype="'.$mysqli->real_escape_string(@trim($_POST['skype'])).'"';
		$new_value['skype']=$mysqli->real_escape_string(@trim($_POST['skype']));
	}*/

	if(isset($_POST['foundationdate']) and @trim($_POST['foundationdate']) != "")
	{
		$sub_query[]='foundation_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate'])))).'"';
		$new_value['foundation_date']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate']))));
	}

	if(isset($_POST['description']) and @trim($_POST['description']) != "")
	{
		$sub_query[]='about_company_details="'.$mysqli->real_escape_string(@trim($_POST['description'])).'"';
		$new_value['about_company_details']=$mysqli->real_escape_string(@trim($_POST['description']));
	}

	if(isset($_POST['ubm']) and @trim($_POST['ubm']) != "")
	{
		$sub_query[]='ubm_type="'.$mysqli->real_escape_string(@trim($_POST['ubm'])).'"';
		$new_value['ubm_type']=$mysqli->real_escape_string(@trim($_POST['ubm']));
	}

	if(isset($_POST['ubmarchive']) and @trim($_POST['ubmarchive']) != "")
	{
		$sub_query[]='ubmarchive_type="'.$mysqli->real_escape_string(@trim($_POST['ubmarchive'])).'"';
		$new_value['ubmarchive_type']=$mysqli->real_escape_string(@trim($_POST['ubmarchive']));
	}

	if(count($sub_query)){
			$fileok=$efile=0;

			//File Edit

			if(isset($_FILES["file"]["type"]))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$temporary = explode(".", $_FILES["file"]["name"]);
				$file_extension = end($temporary);
				if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
				) && ($_FILES["file"]["size"] < 10000000)//Approx. 100kb files can be uploaded.
				&& in_array($file_extension, $validextensions)) {
					if ($_FILES["file"]["error"] > 0)
					{
						$error=$_FILES["file"]["error"];
						echo json_encode(array("error"=>$error));
						exit();
					}
					else
					{
						$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
						//$targetPath = "upload/".$__username; // Target path where file is to be stored

						/*$targetPath=realpath(dirname(__FILE__))."/../../uploads/profiles/company/logo/".md5($tmp_cpy).".png";
						if(file_exists($targetPath)){
						  $efile=1;
						}*/

						$fileok=1;
					}
				}
				else
				{
					$error="Invalid file Size or Type";
					echo json_encode(array("error"=>$error));
					exit();
				}
			}

			audit_log($mysqli,"company","UPDATE",$new_value,'WHERE company_id='.$tmp_cpy,($fileok==1?"NEW":""),($efile==1?"EXIST":""),"company_id");
			$sql='UPDATE company SET '.implode(",",$sub_query).' WHERE company_id='.$tmp_cpy;
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

				//File Edit

				if(isset($_FILES["file"]["type"]))
				{
					//if($efile==1){
					  //@unlink($targetPath);
					//}

					if($fileok==1){
						//move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file


						$ttmp_company=md5($tmp_cpy).".png";

						$info = $s3Client->doesObjectExist('datahub360', 'profiles/company/logo/'.$ttmp_company);
						if($info)
						{
							$s3Client->deleteObject([
								'Bucket' => 'datahub360',
								'Key'    => 'profiles/company/logo/'.$ttmp_company
							]);
						}


						$result = $s3Client->putObject([
							'Bucket' => 'datahub360',
							'Key'    => 'profiles/company/logo/'.$ttmp_company,
							'SourceFile' => $sourcePath
						]);
					}
					/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
					echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
					echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
					echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
					echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
				}

				//File Edit Ends

				echo json_encode(array("error"=>""));
				exit();
			}else{
				echo json_encode(array("error"=>$error));
				exit();
			}
	}
	echo json_encode(array("error"=>$error));
	exit();
}

//Add New Company
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_POST["new"]) and $_POST["new"]=="new")
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);

	$error="Error occured";
	$sub_query=$new_value=array();
	$fileok=0;
	$cname="";

	if(isset($_POST['cname']) and @trim($_POST['cname']) != "")
	{
		$cname=$mysqli->real_escape_string(@trim($_POST['cname']));
	   if ($stmt = $mysqli->prepare('SELECT company_id FROM `company` where company_name="'.$cname.'" LIMIT 1')) {

//('SELECT id FROM `company` where company_name="'.$cname.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='company_name="'.$cname.'"';
				$new_value['company_name']=$cname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company name already exist.'));
				exit();
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company name required.'));
		exit();
	}

	if(isset($_POST['skype']) and @trim($_POST['skype']) != "")
	{
		$sub_query[]='skype="'.$mysqli->real_escape_string(@trim($_POST['skype'])).'"';
		$new_value['skype']=$mysqli->real_escape_string(@trim($_POST['skype']));
	}

	if(isset($_POST['foundationdate']) and @trim($_POST['foundationdate']) != "")
	{
		$sub_query[]='foundation_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate'])))).'"';
		$new_value['foundation_date']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate']))));
	}

	if(isset($_POST['description']) and @trim($_POST['description']) != "")
	{
		$sub_query[]='about_company_details="'.$mysqli->real_escape_string(@trim($_POST['description'])).'"';
		$new_value['about_company_details']=$mysqli->real_escape_string(@trim($_POST['description']));
	}

	if(isset($_POST['ubm']) and @trim($_POST['ubm']) != "")
	{
		$sub_query[]='ubm_type="'.$mysqli->real_escape_string(@trim($_POST['ubm'])).'"';
		$new_value['ubm_type']=$mysqli->real_escape_string(@trim($_POST['ubm']));
	}

	if(isset($_POST['ubmarchive']) and @trim($_POST['ubmarchive']) != "")
	{
		$sub_query[]='ubmarchive_type="'.$mysqli->real_escape_string(@trim($_POST['ubmarchive'])).'"';
		$new_value['ubmarchive_type']=$mysqli->real_escape_string(@trim($_POST['ubmarchive']));
	}

		//File Edit

	if(isset($_FILES["file"]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
		) && ($_FILES["file"]["size"] < 10000000)//Approx. 100kb files can be uploaded.
		&& in_array($file_extension, $validextensions)) {
			if ($_FILES["file"]["error"] > 0)
			{
				$error=$_FILES["file"]["error"];
				echo json_encode(array("error"=>$error));
				exit();
			}
			else
			{
				$fileok=1;
				/*$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				//$targetPath = "upload/".$__username; // Target path where file is to be stored

				$targetPath=realpath(dirname(__FILE__))."/../img/company/".strip_tags($cname).$insertid.".png";
				if(file_exists($targetPath)){
				  @unlink($targetPath);
				}

				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file*/
				/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
				echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
				echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
				echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
			}
		}
		else
		{
			$error="Invalid file Size or Type";
			echo json_encode(array("error"=>$error));
			exit();
		}
	}

	//File Edit Ends



	if(count($sub_query)){

		audit_log($mysqli,"company","INSERT",$new_value,"",($fileok==1?"New":""),"");
		$sql='INSERT INTO company SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){

			$sqlcd='INSERT INTO company_defaults SET `Entity Name`="'.$cname.'", companyID='.$insertid;
			$stmtcd = $mysqli->prepare($sqlcd);
			if($stmtcd){
				$stmtcd->execute();
			}

				if($fileok==1)
				{
					$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

					$ttmp_company=md5($insertid).".png";

					$info = $s3Client->doesObjectExist('datahub360', 'profiles/company/logo/'.$ttmp_company);
					if($info)
					{
						$s3Client->deleteObject([
							'Bucket' => 'datahub360',
							'Key'    => 'profiles/company/logo/'.$ttmp_company
						]);
					}


					$result = $s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'profiles/company/logo/'.$ttmp_company,
						'SourceFile' => $sourcePath
					]);
				}
				//if($lastaffectedID == 1){


					echo json_encode(array("error"=>""));
				//}else
					//echo json_encode(array("error"=>$error));

				if(isset($cname) and $cname != ""){
					foreach($docnarr as $vlll){
						$infodh = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$cname.'/'.$vlll.'/');
						if(!$infodh)
						{
							$s3Client->putObject([
								'Bucket' => 'datahub360',
								'Key'    => 'resources/Clients/'.$cname.'/'.$vlll.'/'
							]);
						}
					}
/*
					$infodhc = $s3Client->doesObjectExist('datahub360-corresondence', $cname.'/');
					if(!$infodhc)
					{
						$s3Client->putObject([
							'Bucket' => 'datahub360-corresondence',
							'Key'    => $cname.'/'
						]);
					}
*/
					$infodhi = $s3Client->doesObjectExist('datahub360-invoices', $insertid.'/');
					if(!$infodhi)
					{
						$s3Client->putObject([
							'Bucket' => 'datahub360-invoices',
							'Key'    => $insertid.'/'
						]);
					}

				}


				exit();
			}else{
				echo json_encode(array("error"=>$error));
			}
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}
//Add company ends


//Delete Company
if(isset($group_id) and ($group_id == 1 or $_SESSION["group_id"] == 2) and isset($_POST["cpy"]) and @trim($_POST["cpy"]) != "" and @trim($_POST["cpy"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();

	$cpy=$mysqli->real_escape_string(@trim($_POST["cpy"]));
	if($cpy == 1)
	{
		echo json_encode(array('error'=>'Error Occured! Vervantis deletion is not permitted.'));
		exit();
	}


	$stmtsk = $mysqli->prepare('SELECT company_id FROM company where company_id="'.$cpy.'" LIMIT 1');

//('SELECT id FROM company where id="'.$cpy.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtskk = $mysqli->prepare('SELECT user_id FROM user where company_id="'.$cpy.'" LIMIT 1');

//('SELECT id FROM user where company_id="'.$cpy.'" LIMIT 1');

			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					echo json_encode(array('error'=>'Error Occured!  first delete all users under the company.'));
					exit();
				}else{
					//audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',($fileok==1?"NEW":""),($efile==1?"EXIST":""));
					$stmtskks = $mysqli->prepare('DELETE FROM company where company_id="'.$cpy.'" LIMIT 1');
					if($stmtskks){
						$stmtskks->execute();
						$lastcaffectedID=$stmtskks->affected_rows;
						if($lastcaffectedID==1)
						{
							echo json_encode(array('error'=>''));
							exit();
						}else{
							echo json_encode(array('error'=>'Error Occured! Database error.'));
							exit();
						}
					}else{
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();
					}
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Company doesn\'t exists.'));
			exit();
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
	}
}
//Delete company ends



//print_r($_POST);
echo false;
exit();
?>
