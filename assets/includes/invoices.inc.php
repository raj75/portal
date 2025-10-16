<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

//error_reporting(0);
ini_set('max_execution_time', 0);



if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];


if(isset($_POST["inauto"]) and isset($_POST["insavename"]) and isset($_POST["invalue"]) and $_POST["insavename"] != "" and $_POST["inauto"] != "" and $_POST["inauto"] != 0)
{

	$error="Error occured";
	$new_value=array();
	
	$inid = $mysqli->real_escape_string(@trim($_POST["inauto"]));
	//$insavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$insavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["insavename"])));
	//$insavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$invalue = $mysqli->real_escape_string(@trim($_POST["invalue"]));


	if($insavename == "Start Date" or $insavename == "End Date"){
		$invalue = @date_format(@date_create_from_format('m/d/Y', $invalue), 'Y-m-d');
	}
	
	if(($insavename == "site_name" or $insavename == "site_number") and ($invalue=="0" || $invalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}
	
	if($insavename == "Status Date"){
		//$invalue = @date_format(@date_create_from_format('m/d/Y', $invalue), 'Y-m-d');
		$invalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($invalue)));
	}

	if ($stmtttt = $mysqli->prepare('Select invoice_number From invoiceIndex Where invoice_number="'.$inid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ' and  company_id = '.$_SESSION["cname"]:'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			//{	echo false;exit();}

			$new_value[$insavename]=$invalue;
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $insavename."l".$invalue;die();

//audit_log($mysqli,'invoiceIndex','UPDATE',$new_value,'WHERE invoice_number='.$inid,'','','invoice_number');
	$sql='Update invoiceIndex SET `'.$insavename.'` = "'.$invalue.'" WHERE invoice_number="'.$inid.'"';
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



?>