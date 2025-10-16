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


if(isset($_POST["accauto"]) and isset($_POST["accsavename"]) and isset($_POST["accvalue"]) and $_POST["accsavename"] != "" and $_POST["accauto"] != "" and $_POST["accauto"] != 0)
{

	$error="Error occured";
	$new_value=array();
	
	$accid = $mysqli->real_escape_string(@trim($_POST["accauto"]));
	//$accsavename = str_replace("_"," ",$mysqli->real_escape_string(@trim($_POST["ssssavename"])));
	$accsavename = str_replace("@"," ",$mysqli->real_escape_string(@trim($_POST["accsavename"])));
	//$accsavename = $mysqli->real_escape_string(@trim($_POST["ssssavename"]));
	$accvalue = $mysqli->real_escape_string(@trim($_POST["accvalue"]));

/*
	if($accsavename == "Start Date" or $accsavename == "End Date"){
		$accvalue = @date_format(@date_create_from_format('m/d/Y', $accvalue), 'Y-m-d');
	}
	
	if(($accsavename == "site_name" or $accsavename == "site_number") and ($accvalue=="0" || $accvalue=="")){
		echo json_encode(array("error"=>5));
		die();		
	}
	
	if($accsavename == "Status Date"){
		//$accvalue = @date_format(@date_create_from_format('m/d/Y', $accvalue), 'Y-m-d');
		$accvalue = @date('Y-m-d H:m:s',strtotime('+4 hour',strtotime($accvalue)));
	}
*/
	if ($stmtttt = $mysqli->prepare('Select ID From accounts Where ID="'.$accid.'" '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? '':'').' LIMIT 1')) { 

		$stmtttt->execute();
		$stmtttt->store_result();
		$ct=$stmtttt->num_rows;
		if ($ct > 0) {
			$stmtttt->bind_result($nosave);
			$stmtttt->fetch();
			
			//if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and ($status=="Completed" || $status=="Cancelled"))
			//{	echo false;exit();}

			$new_value[$accsavename]=$accvalue;
		}else{echo false;exit();}
	}else{echo false;exit();}
//die();
//echo $accsavename."l".$accvalue;die();

	audit_log($mysqli,'accounts','UPDATE',$new_value,'WHERE ID='.$accid,'','','ID');
	$sql='Update accounts SET `'.$accsavename.'` = "'.$accvalue.'" WHERE ID="'.$accid.'"';
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