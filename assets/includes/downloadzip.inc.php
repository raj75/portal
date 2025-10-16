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

$idscount=0;
if(isset($_POST['ids']) and $_POST['ids'] != 0 and $_POST['ids'] != ""){
	$idscount=count(explode(",",$_POST['ids']));
}

if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) and isset($_POST['ids']) and $_POST['ids'] != 0 and $_POST['ids'] != "" and $idscount < 100){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
	$cname=$_SESSION['company_id'];
	$ids=$_POST['ids'];
	$invnbr_arr=array();

	if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $subquery=' and i.company_id='.$cname;
	else $subquery='';
	if($cname==9 || $cname==32){
		//if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){$cname=$company_id;}
		$invnbr_arr[]='sample.pdf';
	}else{
		if ($vestmt = $mysqli->prepare('Select i.company_id,i.invoice_number,a.SourceID,a.InvoiceUBMID From invoiceIndex AS i LEFT JOIN ubm_database.tblInvoices AS a ON a.InvoiceID = i.invoice_number Where a.ClientID=i.company_id and i.invoice_number in ('.$ids.')'.$subquery)) {
			$vestmt->execute();
			$vestmt->store_result();
			if ($vestmt->num_rows > 0) {
				$vestmt->bind_result($company_id,$invnbr,$i_SourceID,$i_InvoiceUBMID);
				while ($vestmt->fetch()){
					//$invnbr_arr[]=$invnbr;
					$invnbr_arr[]=$i_InvoiceUBMID;
				}
				if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){$cname=$company_id;}
			}elseif($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
				if ($vestmtdemo = $mysqli->prepare('Select i.invoice_number From invoiceIndex AS i Where (i.company_id=9 or i.company_id=32) and i.invoice_number in ('.$ids.')')) {
					$vestmtdemo->execute();
					$vestmtdemo->store_result();
					if ($vestmtdemo->num_rows > 0) {
						if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){$cname=9;}
						$invnbr_arr[]='sample.pdf';
					}			
				}else{ echo false;die();}
			}
		}else{ echo false;die();}
	}

	if(count($invnbr_arr) < 1){
		echo json_encode(array("error"=>8));
		die();
	}

	$tmpfolder=$cname."_".time().rand(2,99);
	if( is_dir(dirname(__FILE__) .'/tmpdir/'.$tmpfolder) === false )
	{
		mkdir(dirname(__FILE__) .'/tmpdir/'.$tmpfolder,0755);
	}
	$filepresent=0;

	foreach($invnbr_arr as $vl){
		if($cname==9 || $cname==32) $keyname='sample.pdf';
		else $keyname=$cname.'/'.$i_SourceID.'/'.$vl.'.pdf';
		$info = $s3Client->doesObjectExist('datahub360-invoices', $keyname);
		if($info)
		{
			$result = $s3Client->getObject(array(
				'Bucket' => 'datahub360-invoices',
				'Key'    => $keyname,
				'SaveAs' => dirname(__FILE__) .'/tmpdir/'.$tmpfolder.'/'.$vl.'.pdf',
			));

			$filepresent=1;
		}
		
		if($cname==9 || $cname==32) break;
	}

	if($filepresent==1){
		$filelist = array_diff(scandir(dirname(__FILE__) ."/tmpdir/".$tmpfolder), array('.', '..'));
		$zipfile=dirname(__FILE__) ."/tmpdir/".$tmpfolder.".zip";
		$zip = new ZipArchive;
		if ($zip->open($zipfile,  ZipArchive::CREATE)){
			foreach($filelist as $vll){
				$filename=basename($vll);
				$zip->addFile(dirname(__FILE__) ."/tmpdir/".$tmpfolder."/".$filename,$filename);
			}
			$zip->close();
			//echo $zipfile;




			$infos3zip = $s3Client->doesObjectExist('datahub360-tempdownloads', $tmpfolder.'.zip');
			if($infos3zip)
			{
				echo false;
				exit();
			}

			$results = $s3Client->putObject([
				'Bucket' => 'datahub360-tempdownloads',
				'Key'    => $tmpfolder.'.zip',
				'SourceFile' => $zipfile
			]);


			@array_map('unlink', glob(dirname(__FILE__) ."/tmpdir/".$tmpfolder."/*.*"));
			@unlink(dirname(__FILE__) ."/tmpdir/".$tmpfolder.".zip");
			@rmdir(dirname(__FILE__) ."/tmpdir/".$tmpfolder);
			$info = $s3Client->doesObjectExist('datahub360-tempdownloads', basename($zipfile));
			if($info)
			{
				$cmd = $s3Client->getCommand('GetObject', [
					'Bucket' => 'datahub360-tempdownloads',
					'Key'    => basename($zipfile)
				]);

				$request = $s3Client->createPresignedRequest($cmd, '+60 minutes');
				echo json_encode(array("filename"=>(string) $request->getUri()));
				exit();
			}else false;
			die();
		}else{ echo false;die();}
	}else{echo json_encode(array("error"=>8));die();}



die();


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
}elseif(isset($_POST['ids']) and $_POST['ids'] != 0 and $_POST['ids'] == ""){
	echo json_encode(array("error"=>6));
	die();
}elseif(isset($_POST['ids']) and $_POST['ids'] != 0 and $_POST['ids'] != "" and $idscount > 100){
	echo json_encode(array("error"=>5));
	die();
}else echo false;



function getpresignedurll($object, $bucket = '', $expiration = '')
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
