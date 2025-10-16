<?php
//error_reporting(E_ALL);
//require_once("../inc/init.php");
require_once("../../../lib/config.php");
require_once '../../includes/db_connect.php'; 
require_once '../../includes/functions.php'; 
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];

$chart_id = (isset($_POST['h_chart_id']) and (int)$_POST['h_chart_id'] > 0)?(int)$_POST['h_chart_id']:"";

$chart_name =  mysqli_real_escape_string($mysqli,strip_tags($_POST['h_chart_name']));
//$Commodity = mysqli_real_escape_string($mysqli,strip_tags($post_data['Commodity']));
$chart_query = base64_encode(mysqli_real_escape_string($mysqli,strip_tags($_POST['chart_query'])));

$chart_settings1 = base64_encode(serialize($_POST));

//$chart_settings2 = json_decode($chart_settings1, true);

//var_dump($chart_settings1);
//$chart_name = $chart_settings2['h_chart_name'];
//print_r($chart_settings2);
//die();

//$chart_settings3 = serialize($chart_settings2);

//$dashboard_settings = json_encode($_POST['settings']);
//$dashboard_settings = ($_POST['settings']);

//$dashboard_settings = json_decode($dashboard_settings, true);

//$dashboard_settings = serialize($dashboard_settings);

//echo "<pre>";
//print_r($someArray);

//print_r($dashboard_settings);

if ( $chart_id > 0 ) {
	$sql = "update amcharts set chart_name = '$chart_name', user_id = '$user_one', form_settings = '$chart_settings1', chart_query = '$chart_query' where Id = $chart_id limit 1";
	$mysqli->query($sql);
	echo json_encode(array("newchartid"=>$chart_id));
} else {
	$sql = "insert into amcharts set chart_name = '$chart_name', user_id = '$user_one', form_settings = '$chart_settings1', chart_query = '$chart_query'";
	$mysqli->query($sql);
	$lastchartid=$mysqli->insert_id;
	echo json_encode(array("newchartid"=>$lastchartid));
}




/*
//----------------------------

$chart_qry = $mysqli->query("select * from amcharts order by Id desc limit 1");

//$min_max_obj = $mysqli->query($min_max_query);
				
$chart_data = $chart_qry->fetch_assoc();

$form_settings = unserialize(base64_decode($chart_data['form_settings']));
*/

//print_r( $form_settings );  
//print_r($chart_data);
				
?>