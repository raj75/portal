<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

$beep = (int) $_POST['beep'];
$sql="UPDATE user SET sound=$beep WHERE user_id=".$user_one." limit 1";
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

//echo false;
?>