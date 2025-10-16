<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

if(isset($_POST["id"]) and isset($_POST["pname"]) and isset($_POST['pvalue']) and isset($_POST["requestType"]) and trim($_POST["pname"]) != "" and trim($_POST["pvalue"]) != "")
{

	$error="Error occured";
	$id = @trim($_POST['id']);
	if($id == "" and $user_one == 1)
		$id=1;
	elseif(($id == "" and $user_one != 1) or ($id == 1 and  $user_one != 1) or (($id != "" or $id == 0) and ($user_one != 1 and $id != $user_one)))
	{
		echo json_encode(array("error"=>$error));
		exit();
	}
	$pname = @trim($_POST['pname']);
	switch ($pname) {
		case "phn1":
			$pname="phone_part1";
		case "phn2":
			$pname="phone_part2";
			break;
		case "phn3":
			$pname="phone_part3";
			break;
	}
	$pvalue = @trim($_POST['pvalue']);
	
	$stmtsk = $mysqli->prepare('SELECT user_id FROM user where user_id='.$id.' LIMIT 1');

//('SELECT id FROM user where id='.$id.' LIMIT 1');

	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0)
	{
		$stmtskk = $mysqli->prepare("SELECT user_id FROM user where user_id=".$id." and ".$mysqli->real_escape_string($pname)."='".$mysqli->real_escape_string($pvalue)."' LIMIT 1");

//("SELECT id FROM user where id=".$id." and ".$mysqli->real_escape_string($pname)."='".$mysqli->real_escape_string($pvalue)."' LIMIT 1");

		$stmtskk->execute();
		$stmtskk->store_result();
		if ($stmtskk->num_rows > 0)
		{
			echo json_encode(array("error"=>""));
			exit();
		}
	
	
	
		$sql="UPDATE user SET ".$mysqli->real_escape_string($pname)."='".$mysqli->real_escape_string($pvalue)."' WHERE user_id=".$id;
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();
		$lastaffectedID=$stmt->affected_rows;
	}else{
		$sql="INSERT INTO user SET ".$mysqli->real_escape_string($pname)."='".$mysqli->real_escape_string($pvalue)."'";
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();
		$lastaffectedID=$stmt->insert_id;
	}

	if($lastaffectedID == 1)
		echo json_encode(array("error"=>""));
	else
		echo json_encode(array("error"=>$error));
	
	exit();
}

//print_r($_POST);
echo false;
?>