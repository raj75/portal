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


if(isset($_POST["litauto"]) and isset($_POST["litsavename"]) and isset($_POST["litvalue"]) and $_POST["litsavename"] != "" and $_POST["litauto"] != "" and $_POST["litauto"] != 0)
{

	$error="Error occured";
	$new_value=array();
	
	$accid = $mysqli->real_escape_string(@trim($_POST["litauto"]));
	//$litsavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$litsavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["litsavename"])));
	//$litsavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$litvalue = $mysqli->real_escape_string(@trim($_POST["litvalue"]));

/*
	if($litsavename == "Start Date" or $litsavename == "End Date"){
		$litvalue = @date_format(@date_create_from_format('m/d/Y', $litvalue), 'Y-m-d');
	}
	
	if(($litsavename == "site_name" or $litsavename == "site_number") and ($litvalue=="0" || $litvalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}
	
	if($litsavename == "Status Date"){
		//$litvalue = @date_format(@date_create_from_format('m/d/Y', $litvalue), 'Y-m-d');
		$litvalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($litvalue)));
	}
*/
	if ($stmtttt = $mysqli->prepare('Select ID From line_item_tou Where ID="'.$accid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? '':'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			//{	echo false;exit();}

			$new_value[$litsavename]=$litvalue;
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $litsavename."l".$litvalue;die();

	audit_log($mysqli,'line_item_tou','UPDATE',$new_value,'WHERE ID='.$accid,'','','ID');
	$sql='Update line_item_tou SET `'.$litsavename.'` = "'.$litvalue.'" WHERE ID="'.$accid.'"';
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