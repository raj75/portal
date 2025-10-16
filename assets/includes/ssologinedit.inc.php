<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

require '../../lib/s3/aws-autoloader.php';


//error_reporting(0);
ini_set('max_execution_time', 0);

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cmpid=$_SESSION['company_id'];

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 5){echo false; exit(); }

if(isset($user_one) and isset($_SESSION["group_id"]) and $_SESSION["group_id"] != 0 and isset($_POST['idpentityid']) and isset($_POST["idpssourl"]) and isset($_POST["idpslourl"]) and isset($_POST["idpc509cert"]) and isset($_POST["ssotype"]) and isset($_POST["cid"]) and !empty($_POST["cid"]) and isset($_POST["tenantid"]) and $user_one != "" and $user_one != 0)
{
	$error="Error occured";
	$sub_query=$usub_query=array();

	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cid=$_POST["cid"];
	else $cid=$cname;

	$ssotype=@trim($_POST["ssotype"]);

	$sub_query[]='idp_entity_id_'.$ssotype.'="'.$mysqli->real_escape_string(@trim($_POST['idpentityid'])).'"';
	$sub_query[]='idp_sso_url_'.$ssotype.'="'.$mysqli->real_escape_string(@trim($_POST['idpssourl'])).'"';
	$sub_query[]='idp_slo_url_'.$ssotype.'="'.$mysqli->real_escape_string(@trim($_POST['idpslourl'])).'"';
	$sub_query[]='idp_x509cert_'.$ssotype.'="'.$mysqli->real_escape_string(@trim($_POST['idpc509cert'])).'"';
	$sub_query[]='azure_tenant_id="'.$mysqli->real_escape_string(@trim($_POST['tenantid'])).'"';


	if(count($sub_query)){
			$sql='UPDATE company SET '.implode(",",$sub_query).' WHERE company_id='.intval($cid);
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

			}else{
				echo json_encode(array("error"=>$error));
				exit();
			}
	}
	echo json_encode(array("error"=>""));
}

if(isset($user_one) and isset($_SESSION["group_id"]) and $_SESSION["group_id"] != 0 and isset($_POST['ssochoice']) and isset($_POST["cid"]) and !empty($_POST["cid"]) and $user_one != "" and $user_one != 0)
{
	$error="Error occured";
	$sub_query=$usub_query=array();

	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cid=$_POST["cid"];
	else $cid=$cname;

	//if(count($sub_query)){
			$sql='UPDATE company SET sso_choice="'.$mysqli->real_escape_string(@trim($_POST['ssochoice'])).'" WHERE company_id='.intval($cid);
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

			}else{
				echo json_encode(array("error"=>$error));
				exit();
			}
	//}
	echo json_encode(array("error"=>""));
}

echo false;
exit();
?>
