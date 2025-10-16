<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,55)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

//$dashboard_settings = json_encode($_POST['settings']);
$dashboard_settings = ($_POST['resetDb']);

if ($dashboard_settings == 'resetit') {
$sql = "update user set dashboard='' where user_id = $user_one limit 1";

$mysqli->query($sql);
}

?>