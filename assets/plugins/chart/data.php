<?php
require_once '../../../assets/includes/functions.php';
sec_session_start();
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");

if(!isset($_GET["energytype"]) or !isset($_GET["month"]) or !isset($_GET["year"]) or (@trim($_GET["energytype"]) != "NG" and @trim($_GET["energytype"]) != "CO")  or @trim($_GET["month"]) == "0" or @trim($_GET["month"]) == "" or @trim($_GET["year"]) == "0" or @trim($_GET["year"]) == "")
	exit("Error Occured. Please try after sometime.");

require_once '../../../assets/includes/db_connect.php';

$energy_type=@trim($_GET["energytype"]);//NG or CO

if(isset($_GET["month"]) and isset($_GET["year"]))
	$sub_query=" and _month='".$mysqli->real_escape_string(@trim($_GET["month"]))."' and _year='".$mysqli->real_escape_string(@trim($_GET["year"]))."'";
else
	$sub_query="";

if ($stmt = $mysqli->prepare("SELECT _updated, _open As open, _last AS close, _low As low, _high As high, _volume AS volume FROM cme WHERE energy_type='".$mysqli->real_escape_string($energy_type)."'".$sub_query." ORDER BY id")) { 
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_updated,$open,$close,$low,$high,$volume);
		while($stmt->fetch()) {
			$data[] = array("date"=>$_updated,"open"=>$open,"close"=>$close,"low"=>$low,"high"=>$high,"volume"=>$volume);
		}
		// Print out rows
		echo json_encode( $data );
	}else{
		echo json_encode("");
	}
}else{
	echo json_encode("");
}
//$thread = $mysqli->thread_id;
$mysqli->close();
//$mysqli->kill($thread);
?>