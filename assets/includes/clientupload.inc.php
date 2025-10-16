<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
set_time_limit(0);

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$profile = 'default';

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => [
			 'key' => $_ENV['aws_access_key_id'],
			 'secret' => $_ENV['aws_secret_access_key']
	 ]
]);

if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];


	//print_r($_POST);
	//print_r($_FILES);
	//print_r($_REQUEST);


	if(isset($_POST["docname"])
		and  isset($_FILES) and isset($_FILES["bmfilesupload"]) and (
		$_POST["docname"] == "Energy Procurement"
		OR $_POST["docname"] == "Energy Accounting"
		OR $_POST["docname"] == "Data Management"
		OR $_POST["docname"] == "Sustainability"
		OR $_POST["docname"] == "Projects"
		OR $_POST["docname"] == "Rate Optimization"
		OR $_POST["docname"] == "Rate Optimization:Regulated Information"
		OR $_POST["docname"] == "Rate Optimization:Utility Rate Reports"
		OR $_POST["docname"] == "Rate Optimization:Utility Rate Change Requests"
		OR $_POST["docname"] == "Energy Procurement:Direct Access Information"
		OR $_POST["docname"] == "Energy Procurement:Strategy"
		OR $_POST["docname"] == "Energy Procurement:Dynamic Risk Management"
		OR $_POST["docname"] == "Data Management:Data Analysis"
		OR $_POST["docname"] == "Data Management:Custom Reports"
		OR $_POST["docname"] == "Data Management:Consumption Reports"
		OR $_POST["docname"] == "Energy Accounting:Invoice Validation"
		OR $_POST["docname"] == "Energy Accounting:Utility Budgets"
		OR $_POST["docname"] == "Energy Accounting:Exception Reports"
		OR $_POST["docname"] == "Energy Accounting:Resolved Exceptions"
		OR $_POST["docname"] == "Energy Accounting:Site and Account Changes"
		OR $_POST["docname"] == "Sustainability:Sustainability Reports"
		OR $_POST["docname"] == "Sustainability:Corporate Reports"
		OR $_POST["docname"] == "Sustainability:Surveys"
		OR $_POST["docname"] == "Projects:Distributed Generation"
		OR $_POST["docname"] == "Projects:Efficiency Upgrades"
		OR $_POST["docname"] == "Projects:EV Charging"
		OR $_POST["docname"] == "Projects:Rebates and Incentives"
		OR $_POST["docname"] == "Projects:Other"
		)
	)
	{

	if ($stmt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name);
			$stmt->fetch();
		}else{echo false;exit();}
	}else{echo false;exit();}



		$docname=$folder_n=$_POST["docname"];
		if(preg_match("/([^\:]+):([^\:]+)/s",$docname,$tmp_docname))
		{
			array_shift($tmp_docname);
			$folder_n=$tmp_docname[0].'/'.$tmp_docname[1];

			$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/'.$folder_n.'/');
			if(!$info)
			{
				echo false;
				exit();
			}

			foreach($_FILES['bmfilesupload']['name'] as $ky => $vl){
					$file_name = $vl;
					$temp_file_location = $_FILES['bmfilesupload']['tmp_name'][$ky];


					$result = $s3Client->putObject([
						'Bucket' => 'datahub360',
						'Key'    => 'resources/Clients/'.$company_name.'/'.$folder_n.'/'.$file_name,
						'SourceFile' => $temp_file_location
					]);

					//var_dump($result);
			}

			echo true;
			exit();

		}
	}else{
		echo false;
		//die("No access");

	}
}else echo false;
?>
