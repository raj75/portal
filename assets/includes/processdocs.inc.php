<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

//error_reporting(0);
require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);


$user_one=$_SESSION['user_id'];

$error="Error occured";
$profile = 'default';

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => [
			 'key' => $_ENV['aws_access_key_id'],
			 'secret' => $_ENV['aws_secret_access_key']
	 ]
]);

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];

if(isset($_POST["id"]) and isset($_GET["action"]) and !empty(trim($_POST["id"])) and $_GET["action"]=="deleteit")
{
	$error="Error occured";
	$id = $mysqli->real_escape_string((int)@trim($_POST['id']));

	$stmtsk = $mysqli->prepare('SELECT ID FROM process_docs where ID='.$id.' LIMIT 1');
	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($docid);
		$stmtsk->fetch();

		$stmt = $mysqli->prepare("UPDATE `process_docs` SET `status` = '1' WHERE `ID` = ".$docid) ;
		$stmt->execute();
		$arr["error"]=false;
		echo json_encode($arr);exit();
	}
	echo json_encode(array("error"=>$error));;
	exit();
}elseif(isset($_POST["id"]) and isset($_POST["show"]) and !empty(trim($_POST["id"])))
{
	$error="Error occured";
	$id = $mysqli->real_escape_string((int)@trim($_POST['id']));

	$stmtsk = $mysqli->prepare('SELECT ID,Descriptions FROM process_docs where ID='.$id.' LIMIT 1');
	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($docid,$docdescriptions);
		$stmtsk->fetch();
		$arr["id"]=$docid;
		if(empty(@trim($docdescriptions))) $arr["docdescriptions"]="";
		else{ 
			if(preg_match_all("/src=\"(https\:\/\/datahub360\.s3-us-west-2\.amazonaws\.com\/process_docs\/[^<>\"]+)\"/s",$docdescriptions,$getimagearr)){
				array_shift($getimagearr);
				foreach($getimagearr[0] as $imgkyy=>$imgvll){//echo $imgvl;
					$oldimg=$imgvll;
					$imgvll=str_ireplace("https://datahub360.s3-us-west-2.amazonaws.com/process_docs/","",$imgvll);

					$infotarget = $s3Client->doesObjectExist('datahub360', 'process_docs/'.$imgvll);
					if($infotarget)
					{
						$cmd = $s3Client->getCommand('GetObject', [
							'Bucket' => 'datahub360',
							'Key'    => 'process_docs/'.$imgvll
						]);

						$request = $s3Client->createPresignedRequest($cmd, '+10 minutes');
						$presignedUrl = (string) $request->getUri();
						$docdescriptions=str_ireplace($oldimg,$presignedUrl,$docdescriptions);
					}
				}				
			}
			$arr["docdescriptions"]=base64_encode($docdescriptions);
		
		}	
		$arr["error"]=false;
		echo json_encode($arr);exit();
	}
	echo json_encode(array("error"=>$error));;
	exit();
}elseif(isset($_POST["edit-postm"]) and isset($_POST["post-idm"]) and !empty(trim($_POST["post-idm"])))
{
	$error="Error occured";
	$newPost = @trim($_POST['edit-postm']);
	$editPostID = @trim($_POST['post-idm']);
	//$newPostBanner = $_FILES['new-post-banner'];

	if(preg_match_all("/\!\[\]\(data\:image\/([^\;]+)\;base64\,([^\)]+)\)/s",$newPost,$resultimgarr)){
		//array_shift($resultimgarr);print_r($resultimgarr);die();
		
		foreach($resultimgarr[2] as $imgky=>$imgvl){//echo $imgvl;
			$imgname=saveimg($imgvl,$resultimgarr[1][$imgky]);
			if($imgname){
				$newPost=str_ireplace($resultimgarr[0][$imgky],'<img src="https://datahub360.s3-us-west-2.amazonaws.com/process_docs/'.basename(parse_url(urldecode($imgname), PHP_URL_PATH)).'" style="height:287px; width:575px" class="s3links">',$newPost);
			}
		}
	}
	
	if(preg_match_all("/\!\[\]\((htt[^\)]+)\)/s",$newPost,$resultimgarr)){
		//array_shift($resultimgarr);
		
		foreach($resultimgarr[1] as $imgky=>$imgvl){
			$newPost=str_ireplace($resultimgarr[0][$imgky],'<img src="https://datahub360.s3-us-west-2.amazonaws.com/process_docs/'.basename(parse_url(urldecode($imgvl), PHP_URL_PATH)).'" style="height:287px; width:575px" class="s3links">',$newPost);
			//$newPost=str_ireplace($resultimgarr[0][$imgky],$imgvl,$newPost);
		}
	}	

	$sql="UPDATE process_docs SET Descriptions='".$mysqli->real_escape_string($newPost)."' WHERE id=".$editPostID;
	if($sql != "")
	{
		$stmt = $mysqli->prepare($sql) ;
		$stmt->execute();
		$arr["error"]=false;
		echo json_encode($arr);exit();
	}
	echo json_encode(array("error"=>$error));;
	exit();
}

if(isset($_POST["inauto"]) and $_POST["inauto"] != "" and $_POST["inauto"] != 0)
{//echo "yes";

	$error="Error occured";
	$new_value=array();
	
	$inid = $mysqli->real_escape_string(@trim($_POST["inauto"]));
	
	$ngroup = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["group"])));
	$nsubgroup1 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup1"])));
	$nsubgroup2 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup2"])));
	$nsubgroup3 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup3"])));
	$nprocessname = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["processname"])));
	$nowner = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["owner"])));
	$ncreateddate = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["createddate"])));
	$nmodifieddate = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["modifieddate"])));

	if($_SESSION["group_id"] == 2) $nowner=$user_one;

	if(!empty($ncreateddate)){
		//$ncreateddate = @date_format(@date_create_from_format('m/d/Y', $ncreateddate), 'Y-m-d');
	}else $ncreateddate = @date('Y-m-d');
	
	if(!empty($nmodifieddate)){
		//$nmodifieddate = @date_format(@date_create_from_format('m/d/Y', $nmodifieddate), 'Y-m-d');
	}else $nmodifieddate = @date('Y-m-d');	
	
	
	
	
	//$insavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	////$insavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["insavename"])));
	//$insavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	////$invalue = $mysqli->real_escape_string(@trim($_POST["invalue"]));

//echo 'Select ID From process_docs Where ID="'.$inid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ' and  Owner = '.$user_one:'').' LIMIT 1';
	if ($stmtttt = $mysqli->prepare('Select ID From process_docs Where ID="'.$inid.'" '.(($_SESSION["group_id"] == 2) ? ' and  Owner = '.$user_one:'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $insavename."l".$invalue;die();
//echo 'Update process_docs SET `'.$insavename.'` = "'.$invalue.'" WHERE ID="'.$inid.'"';
//audit_log($mysqli,'invoiceIndex','UPDATE',$new_value,'WHERE invoice_number='.$inid,'','','invoice_number');
	$sql='Update process_docs SET `Group` = "'.$ngroup.'", `Sub Group 1` = "'.$nsubgroup1.'", `Sub Group 2` = "'.$nsubgroup2.'", `Sub Group 3` = "'.$nsubgroup3.'", `Process Name` = "'.$nprocessname.'", `Owner` = "'.$nowner.'", `Created Date` = "'.$ncreateddate.'", `Modified Date` = "'.$nmodifieddate.'", Descriptions="" WHERE ID="'.$inid.'"';
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
	exit();
}

if(isset($_POST["innew"]) and isset($_POST["group"]) and isset($_POST["subgroup1"]) and isset($_POST["subgroup2"]) and isset($_POST["subgroup3"]) and isset($_POST["processname"]) and isset($_POST["owner"]) and isset($_POST["createddate"]) and isset($_POST["modifieddate"]))
{
	$error="Error occured";
	$new_value=array();
	

	//$insavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$ngroup = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["group"])));
	$nsubgroup1 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup1"])));
	$nsubgroup2 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup2"])));
	$nsubgroup3 = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["subgroup3"])));
	$nprocessname = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["processname"])));
	$nowner = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["owner"])));
	$ncreateddate = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["createddate"])));
	$nmodifieddate = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["modifieddate"])));

	if($_SESSION["group_id"] == 2) $nowner=$user_one;

	if(!empty($ncreateddate)){
		//$ncreateddate = @date_format(@date_create_from_format('m/d/Y', $ncreateddate), 'Y-m-d');
	}else $ncreateddate = @date('Y-m-d');
	
	if(!empty($nmodifieddate)){
		//$nmodifieddate = @date_format(@date_create_from_format('m/d/Y', $nmodifieddate), 'Y-m-d');
	}else $nmodifieddate = @date('Y-m-d');
	
	/*if(($insavename == "site_name" or $insavename == "site_number") and ($invalue=="0" || $invalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}*/

//die();
//echo $insavename."l".$invalue;die();

//audit_log($mysqli,'invoiceIndex','UPDATE',$new_value,'WHERE invoice_number='.$inid,'','','invoice_number');
	//$sql='Update process_docs SET `'.$insavename.'` = "'.$invalue.'" WHERE ID="'.$inid.'"';
	$sql='INSERT INTO process_docs SET ID=NULL, `Group` = "'.$ngroup.'", `Sub Group 1` = "'.$nsubgroup1.'", `Sub Group 2` = "'.$nsubgroup2.'", `Sub Group 3` = "'.$nsubgroup3.'", `Process Name` = "'.$nprocessname.'", `Owner` = "'.$nowner.'", `Created Date` = "'.$ncreateddate.'", `Modified Date` = "'.$nmodifieddate.'", Descriptions=""';
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
	exit();
}

echo json_encode(array("error"=>$error));;
exit();


function saveimg($content,$imgtype){
	if(empty(@trim($content)) || empty(@trim($imgtype))) return false;

	global $s3Client;

	$target_filename = microtime().mt_rand(1000,10000).".".$imgtype;
	
	/*$resultupload = $s3Client->putObject([
        'Bucket' => 'datahub360',
        'Key'    => 'process_docs/'.$target_filename,
        'Body'   => base64_decode($content),
        'ACL'    => 'public-read', // Optional: specify the ACL for the file
    ]);	*/
	
	$resultupload = $s3Client->putObject([
		'Bucket' => 'datahub360',
		'Key'    => 'process_docs/'.$target_filename,
		'Body'   => base64_decode($content),
	]);

	if ($resultupload) {
		return $target_filename;
	}
	return false;
}

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