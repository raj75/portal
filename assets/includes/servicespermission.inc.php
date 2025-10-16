<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

error_reporting(0);
ini_set('max_execution_time', 0);





if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];


if(isset($user_one) and $user_one != "" and $user_one != 0 and isset($_POST["cid"]) and $_POST["cid"] != "" and $_POST["cid"] != 0 and isset($_POST["status"]) and ($_POST["status"] == "ON" || $_POST["status"] == "OFF"))
{
	$cname=$_POST["cid"];
	$offstatus=$_POST["status"];
	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {

		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$sql='Update company SET site_close_btn="'.$offstatus.'" WHERE company_id="'.$cname.'"';
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
				echo true;exit;
			}else{
				echo false;exit();
			}
		}else{echo false;exit();}
	}else{echo false;exit();}
}elseif(isset($user_one) and $user_one != "" and $user_one != 0 and isset($_POST["cid"]) and $_POST["cid"] != "" and $_POST["cid"] != 0 and isset($_POST["accstatus"]) and ($_POST["accstatus"] == "ON" || $_POST["accstatus"] == "OFF"))
{
	$cname=$_POST["cid"];
	$offstatus=$_POST["accstatus"];
	if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {

		$stmtttt->execute();
		$stmtttt->store_result();
		if ($stmtttt->num_rows > 0) {
			$sql='Update company SET acc_close_btn="'.$offstatus.'" WHERE company_id="'.$cname.'"';
			$stmt = $mysqli->prepare($sql);
			if($stmt){
				$stmt->execute();
				echo true;exit;
			}else{
				echo false;exit();
			}
		}else{echo false;exit();}
	}else{echo false;exit();}
}
die(false);
?>
