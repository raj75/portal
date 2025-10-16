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


if(isset($_POST["sgauto"]) and isset($_POST["sgsavename"]) and isset($_POST["sgvalue"]) and $_POST["sgsavename"] != "" and $_POST["sgauto"] != "" and $_POST["sgauto"] != 0)
{

	$error="Error occured";
	$new_value=array();
	
	$accid = $mysqli->real_escape_string(@trim($_POST["sgauto"]));
	//$sgsavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$sgsavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["sgsavename"])));
	//$sgsavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$sgvalue = $mysqli->real_escape_string(@trim($_POST["sgvalue"]));

/*
	if($sgsavename == "Start Date" or $sgsavename == "End Date"){
		$sgvalue = @date_format(@date_create_from_format('m/d/Y', $sgvalue), 'Y-m-d');
	}
	
	if(($sgsavename == "site_name" or $sgsavename == "site_number") and ($sgvalue=="0" || $sgvalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}
	
	if($sgsavename == "Status Date"){
		//$sgvalue = @date_format(@date_create_from_format('m/d/Y', $sgvalue), 'Y-m-d');
		$sgvalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($sgvalue)));
	}
*/
	if ($stmtttt = $mysqli->prepare('Select ID From service_group Where ID="'.$accid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? '':'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			//{	echo false;exit();}

			$new_value[$sgsavename]=$sgvalue;
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $sgsavename."l".$sgvalue;die();

	audit_log($mysqli,'service_group','UPDATE',$new_value,'WHERE ID='.$accid,'','','ID');
	$sql='Update service_group SET `'.$sgsavename.'` = "'.$sgvalue.'" WHERE ID="'.$accid.'"';
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