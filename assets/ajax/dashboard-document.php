<?php //require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
?>
<iframe src="../assets/plugins/cute-file-browser-dashboard/index.php" frameborder="0" width="100%" height="100%" id="frame1"></iframe>