<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
sec_session_start();
set_time_limit(0);

$sroot=$_SERVER["DOCUMENT_ROOT"];
require_once $sroot.'/lib/s3/aws-autoloader.php';

if(!isset($dotenv)){
	require_once realpath($sroot . '/assets/plugins/env/autoload.php');
	$dotenv = Dotenv\Dotenv::createImmutable($sroot.'/');
	$dotenv->load();
}

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

error_reporting(0);
ini_set('max_execution_time', 0);
//require 'get_client.php';

if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];





	if(isset($_GET["docname"]) and (
		$_GET["docname"] == "Energy Procurement"
		OR $_GET["docname"] == "Energy Accounting"
		OR $_GET["docname"] == "Data Management"
		OR $_GET["docname"] == "Sustainability"
		//OR $_GET["docname"] == "Master Supply Agreements"
		OR $_GET["docname"] == "Projects"
		OR $_GET["docname"] == "Rate Optimization"
		OR $_GET["docname"] == "Rate Optimization:Regulated Information"
		OR $_GET["docname"] == "Rate Optimization:Utility Rate Reports"
		OR $_GET["docname"] == "Rate Optimization:Utility Rate Change Requests"
		OR $_GET["docname"] == "Energy Procurement:Direct Access Information"
		OR $_GET["docname"] == "Energy Procurement:Strategy"
		OR $_GET["docname"] == "Energy Procurement:Dynamic Risk Management"
		OR $_GET["docname"] == "Data Management:Data Analysis"
		OR $_GET["docname"] == "Data Management:Custom Reports"
		OR $_GET["docname"] == "Data Management:Consumption Reports"
		OR $_GET["docname"] == "Energy Accounting:Invoice Validation"
		OR $_GET["docname"] == "Energy Accounting:Utility Budgets"
		OR $_GET["docname"] == "Energy Accounting:Exception Reports"
		OR $_GET["docname"] == "Energy Accounting:Resolved Exceptions"
		OR $_GET["docname"] == "Energy Accounting:Site and Account Changes"
		OR $_GET["docname"] == "Sustainability:Sustainability Reports"
		OR $_GET["docname"] == "Sustainability:Corporate Reports"
		OR $_GET["docname"] == "Sustainability:Surveys"
		OR $_GET["docname"] == "Projects:Distributed Generation"
		OR $_GET["docname"] == "Projects:Efficiency Upgrades"
		OR $_GET["docname"] == "Projects:EV Charging"
		OR $_GET["docname"] == "Projects:Rebates and Incentives"
		OR $_GET["docname"] == "Projects:Other"
		)
	){
		if ($stmt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($company_name);
				$stmt->fetch();
			}else{echo false;exit();}
		}else{echo false;exit();}


		$docname=$folder_n=$_GET["docname"];
		if(preg_match("/([^\:]+):([^\:]+)/s",$docname,$tmp_docname))
		{
			array_shift($tmp_docname);
			$folder_n=$tmp_docname[0].'/'.$tmp_docname[1];

			$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/'.$folder_n.'/');
			if(!$info)
			{
				/*$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/'.$folder_n.'/'
				]);*/
				echo false;
				exit();
			}
		}else{echo false;exit();}




		function generatetree($foldername=""){
			global $s3Client;
			$files = $curr_folder = array();


			$foldername=str_replace("\\","",$foldername);
			//if($foldername == "") $homefolder="Home";
			$homefolder="Home/".rtrim($foldername,"/");

			$result = $s3Client->ListObjects(array( 'Bucket' => 'datahub360', 'Delimiter' => '/','Prefix'=>$foldername));
			$curr_folder = $result->get("CommonPrefixes");
			foreach($result["Contents"] as $ky=>$vl){
				if(preg_match('/(\/)$/s', $vl["Key"], $nosave)) continue;

				$tmp_fname=basename($vl["Key"]);
				$files[] = array(
					"name" => $tmp_fname,
					"type" => "file",
					"path" => rtrim($homefolder,"/")."/".$tmp_fname,
					"size" => $vl["Size"]
				);
			}



			if(is_array($curr_folder)==true and count($curr_folder)){
				foreach((array)$curr_folder as $kys=>$vls){
					$fname=basename($vls['Prefix']);

					$files[] = array(
						"name" => $fname,
						"type" => "folder",
						"path" => rtrim($homefolder,"/")."/".$fname,
						"items" => generatetree($foldername.$fname."/")
					);
				}
			}

			return $files;
		}





		$dir = "Home";

		// Run the recursive function

		//$response = scan($dir);


		// This function scans the files folder recursively, and builds a large array

		function scan($dir){

			$files = array();

			// Is there actually such a folder/file?

			if(file_exists($dir)){

				foreach(scandir($dir) as $f) {

					if(!$f || $f[0] == '.') {
						continue; // Ignore hidden files
					}

					if(is_dir($dir . '/' . $f)) {

						// The path is a folder

						$files[] = array(
							"name" => $f,
							"type" => "folder",
							"path" => $dir . '/' . $f,
							"items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
						);
					}

					else if (is_file($dir . '/' . $f)) {

						// It is a file

						$files[] = array(
							"name" => $f,
							"type" => "file",
							"path" => $dir . '/' . $f,
							"size" => filesize($dir . '/' . $f) // Gets the size of this file
						);
					}
				}

			}

			return $files;
		}
		$dir="Home";


		// Output the directory listing as JSON

		header('Content-type: application/json');

		echo json_encode(array(
			"name" => basename($dir),
			"type" => "folder",
			"path" => $dir,
			"items" => generatetree("resources/Clients/".$company_name."/".$folder_n."/")
		));
	}else echo false;
}else echo false;
