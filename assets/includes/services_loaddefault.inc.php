<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);



//error_reporting(0);
ini_set('max_execution_time', 0);





if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];



if(isset($_POST["cid"]) and $_POST["cid"] !="")
{
	$cid=$_POST["cid"];

	if ($stmtttt = $mysqli->prepare('SELECT cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID,cd.`Billing City`,cd.`Billing State`,cd.`Billing Zip Code` FROM company_defaults cd WHERE cd.companyID='.$cid.'  LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$stmtttt->bind_result($cdEntityName,$cdTaxID,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdcompanyID,$cdBillingCity,$cdBillingState,$cdBillingZipcode);
			$stmtttt->fetch();
			echo json_encode(array("error"=>"","EntityName"=>$cdEntityName,"TaxID"=>$cdTaxID,"BillingAddress1"=>$cdBillingAddress1,"BillingAddress2"=>$cdBillingAddress2,"BillingAddress3"=>$cdBillingAddress3,"companyID"=>$cdcompanyID,"BillingCity"=>$cdBillingCity,"BillingState"=>$cdBillingState,"BillingZipcode"=>$cdBillingZipcode));
		}else{echo false;exit();}
	}else{echo false;exit();}		
	
}
echo false;


?>