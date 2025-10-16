<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();
//set_time_limit(0);

//error_reporting(0);
ini_set('max_execution_time', 0);



if($_SESSION["group_id"] != 1)
	die(false);



$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];



if(isset($_POST["uName"]) and isset($_POST["passwrd"]) and isset($_POST["newPwd"])){
	$uname=@trim($_POST["uName"]);
	$passwrd=@trim($_POST["passwrd"]);
	$newPwd=@trim($_POST["newPwd"]);
	
	if(empty($uname) or empty($passwrd) or empty($newPwd)) die("Fields cannot be empty");

	$result=getContent("https://portal.capturis.com/ubp-rest/updateUserPassword?newPwd=".urlencode($newPwd),$uname,$passwrd);
	if(is_array($result)) print_r($result[0]); else print_r($result);
	exit();
}
die(false);


function getContent($url,$uname,$passwrd)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$header = array(
		'Accept: application/json',
		'Content-Type: application/x-www-form-urlencoded',
		'Authorization: Basic '. base64_encode($uname.":".$passwrd)
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	$contents = curl_exec($ch);
	curl_close($ch);
	return array($contents);
}
?>