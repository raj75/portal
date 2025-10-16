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


if(isset($_POST["veauto"]) and isset($_POST["vesavename"]) and isset($_POST["vevalue"]) and $_POST["vesavename"] != "" and $_POST["veauto"] != "" and $_POST["veauto"] != 0)
{

	$error="Error occured";
	$new_value=array();
	
	$accid = $mysqli->real_escape_string(@trim($_POST["veauto"]));
	//$vesavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$vesavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["vesavename"])));
	//$vesavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$vevalue = $mysqli->real_escape_string(@trim($_POST["vevalue"]));

/*
	if($vesavename == "Start Date" or $vesavename == "End Date"){
		$vevalue = @date_format(@date_create_from_format('m/d/Y', $vevalue), 'Y-m-d');
	}
	
	if(($vesavename == "site_name" or $vesavename == "site_number") and ($vevalue=="0" || $vevalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}
	
	if($vesavename == "Status Date"){
		//$vevalue = @date_format(@date_create_from_format('m/d/Y', $vevalue), 'Y-m-d');
		$vevalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($vevalue)));
	}
*/
	if ($stmtttt = $mysqli->prepare('Select vendor_id From vendor Where vendor_id="'.$accid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? '':'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			//{	echo false;exit();}

			$new_value[$vesavename]=$vevalue;
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $vesavename."l".$vevalue;die();


	audit_log($mysqli,'vendor','UPDATE',$new_value,'WHERE vendor_id='.$accid,'','','vendor_id');
	$sql='Update vendor SET `'.$vesavename.'` = "'.$vevalue.'" WHERE vendor_id="'.$accid.'"';
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