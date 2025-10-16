<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if(!isset($_SESSION['user_id'])){
	//$arr["error"]="kick";
	
	$arr["error"]="Timeout";	
	echo json_encode($arr);
	exit();
}	
$user_one=$_SESSION['user_id'];
$user_cid=$_SESSION['company_id'];
$user_tracktime=$_SESSION['tracktime'];

if(isset($_POST["getuserlist"]) and isset($user_one) and @trim($user_one) != "")
{
	$checklog_stmt = $mysqli->prepare("SELECT date FROM `user_tracking` where user_id='".$user_one."' and status='Inactive' and date='".$mysqli->real_escape_string($user_tracktime)."' limit 1");
	
	if ($checklog_stmt) {
		//$stmt->bind_param('s', $pkey);
		$checklog_stmt->execute();
		$checklog_stmt->store_result();
		if ($checklog_stmt->num_rows > 0) {
			$arr["error"]="kick";		
			echo json_encode($arr);
			exit();
		}	
		
	}

	$error="Error occured";
	$time=time();
	$time_check=$time-60; //SET TIME 10 Minute
	$ugid=$_SESSION["group_id"];

	$stmt = $mysqli->prepare("UPDATE user set last_login=NOW() where user_id=".$user_one) ;
	$stmt->execute();
	$arr=array("onlineUser"=>array(),"replyFrom"=>array());

	
	if($ugid==1 or $ugid==2) $sql="SELECT u.user_id,u.email,u.firstname,u.lastname,c.company_name FROM user u inner join company c on u.company_id=c.company_id where u.last_login>(NOW() - INTERVAL 10 MINUTE) and u.last_login AND u.last_login IS NOT NULL AND u.last_login != '0000-00-00 00:00:00' and u.user_id !=".$user_one;
	elseif($ugid==3 or $ugid==5) $sql="SELECT u.user_id,u.email,u.firstname,u.lastname,c.company_name FROM user u inner join company c on u.company_id=c.company_id where u.last_login>(NOW() - INTERVAL 10 MINUTE) AND u.last_login IS NOT NULL AND u.last_login != '0000-00-00 00:00:00' and u.user_id !=".$user_one." and u.company_id=".$user_cid." and (u.usergroups_id=3 or u.usergroups_id=5)";
	elseif($ugid==4) $sql="SELECT u.user_id,u.email,u.firstname,u.lastname,c.company_name FROM user u inner join company c on u.company_id=c.company_id where u.last_login>(NOW() - INTERVAL 2 MINUTE) and u.last_login IS NOT NULL AND u.last_login != '0000-00-00 00:00:00' and u.user_id !=".$user_one." and u.company_id=".$user_cid." and u.usergroups_id=4)";
	else die();
	
	$stmtsk = $mysqli->prepare($sql);
	if ($stmtsk === false) {
		die();
	}
//("SELECT id,email,firstname,lastname FROM user where last_login>(NOW() - INTERVAL 10 MINUTE) and last_login != '' and last_login != 0 ".$subsql_stmtsk." and id !=$user_one");

	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($userID,$uemail,$userFirstname,$userLastname,$company_name);
		while($stmtsk->fetch()){
			$arr["onlineUser"][]=array("userid"=>$userID,"username"=>((@trim($userFirstname)=="" and @trim($userLastname)=="")?$uemail:$userFirstname." ".$userLastname),"company_name"=>"(".$company_name.")");
		}
	}
	
	
	$stmtskk = $mysqli->prepare("SELECT distinct user_one FROM chat where user_two = $user_one and recd=0");
	$stmtskk->execute();
	$stmtskk->store_result();
	if ($stmtskk->num_rows > 0) {
		$stmtskk->bind_result($replyID);
		while($stmtskk->fetch()){
			if(isset($_SESSION["chat_session"][$replyID]))
				$arr["replyFrom"][$replyID]=false;
			else
				$arr["replyFrom"][$replyID]=true;
		}
	}

	$arr["error"]=false;
	echo json_encode($arr);
	exit();
}

//print_r($_POST);
echo false;
?>