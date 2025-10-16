<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];


if(isset($_GET["req"]) and isset($_GET["cc"]) and !empty($_GET["cc"])){
		$clearingcode=@trim($_GET["cc"]);
		$tmp_arr=array();

	$sql='SELECT
a.expiry,a.`Year`,a.`Month`,a.`Day`,
	CONCAT("$",ROUND(a.settlement,2)) AS settlement,

	CONCAT("$",ROUND(a.range_min,2),"-",ROUND(a.range_max,2)) AS `range`,
	CONCAT("$",ROUND((a.settlement-a.last_settlement),2)) AS last_change,
	CONCAT(ROUND(((a.settlement-a.last_settlement)/a.last_settlement)*100,2),"%") AS `last_%change`,

	CONCAT("$",ROUND(a.1mo_min,2), "-",	ROUND(a.1mo_max,2)) AS `1mo_range`,
	CONCAT("$",ROUND((a.settlement-a.1mo_settlement),2)) AS `1mo_change`,
	CONCAT(ROUND(((a.settlement-a.1mo_settlement)/a.1mo_settlement)*100,2),"%") AS `1mo_%change`,

	CONCAT("$",ROUND(a.1qtr_min,2), "-",	ROUND(a.1qtr_max,2)) AS `1qtr_range`,
	CONCAT("$",ROUND((a.settlement-a.1qtr_settlement),2)) AS `1qtr_change`,
	CONCAT(ROUND(((a.settlement-a.1qtr_settlement)/a.1qtr_settlement)*100,2),"%") AS `1qtr_%change`,

	CONCAT("$",ROUND(a.1yr_min,2), "-",	ROUND(a.1yr_max,2)) AS `1yr_range`,
	CONCAT("$",ROUND((a.settlement-a.1yr_settlement),2)) AS `1yr_change`,
	CONCAT(ROUND(((a.settlement-a.1yr_settlement)/a.1yr_settlement)*100,2),"%") AS `1yr_%change`,
	code
FROM
	ubm_ice.clearing_code_index a
	WHERE a.clearing_code="'.$mysqli->real_escape_string($clearingcode).'" AND a.exchange="ICEP" 
ORDER BY a.`Year`,a.`Month`,a.`Day` DESC';
//ORDER BY a.`Year`,a.`Month`,a.`Day` DESC LIMIT 0,24';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpsExpiry,$tpsYear,$tpsMonth,$tpsDay,$tpsSettlement,$tpsRange,$tpsLast_change,$tpsLast_pchange,$tps1mo_range,$tps1mo_change,$tps1mo_pchange,$tps1qtr_range,$tps1qtr_change,$tps1qtr_pchange,$tps1yr_range,$tps1yr_change,$tps1yr_pchange,$tpscode);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpsExpiry.'","'.$tpsYear.'","'.$tpsMonth.'","'.$tpsDay.'","'.$tpsSettlement.'","'.$tpsRange.'","'.$tpsLast_change.'","'.$tpsLast_pchange.'","'.$tps1mo_range.'","'.$tps1mo_change.'","'.$tps1mo_pchange.'","'.$tps1qtr_range.'","'.$tps1qtr_change.'","'.$tps1qtr_pchange.'","'.$tps1yr_range.'","'.$tps1yr_change.'","'.$tps1yr_pchange.'","'.$tpscode.'"]';
			}
		}
	}
	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="nyiso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="miso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="innerstable" and isset($_GET["cc"]) and !empty($_GET["cc"])){
	$clearingcode=@trim($_GET["cc"]);

	$tmp_arr=$tmpccarr=$tempccarr=array();
	$tmpccarr=@explode(",",$clearingcode);
	if(count($tmpccarr)) foreach($tmpccarr as $vl) $tempccarr[]='"'.$mysqli->real_escape_string($vl).'"';

	$sql='SELECT a.`code`,a.date,a.settlement FROM ubm_ice.AR_MWIS a
	WHERE a.code IN ('.implode(",",$tempccarr).') ORDER BY a.date DESC LIMIT 0,6';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpscode,$tpsdate,$tpssettlement);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpscode.'","'.$tpsdate.'","'.$tpssettlement.'"]';
			}
		}
	}

	//echo json_encode(array("error"=>"","data"=>implode(',',$tmp_arr)));

	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';

}else echo '{"data": []}';




if(1==2){
if(isset($_GET["req"]) and isset($_GET["cc"]) and !empty($_GET["cc"])){
		$clearingcode=@trim($_GET["cc"]);
		$tmp_arr=array();

	$sql='SELECT
a.expiry,a.`Year`,a.`Month`,a.`Day`,
	CONCAT("$",ROUND(a.settlement,2)) AS settlement,

	CONCAT("$",ROUND(a.range_min,2),"-",ROUND(a.range_max,2)) AS `range`,
	CONCAT("$",ROUND((a.settlement-a.last_settlement),2)) AS last_change,
	CONCAT(ROUND(((a.settlement-a.last_settlement)/a.last_settlement)*100,2),"%") AS `last_%change`,

	CONCAT("$",ROUND(a.1mo_min,2), "-",	ROUND(a.1mo_max,2)) AS `1mo_range`,
	CONCAT("$",ROUND((a.settlement-a.1mo_settlement),2)) AS `1mo_change`,
	CONCAT(ROUND(((a.settlement-a.1mo_settlement)/a.1mo_settlement)*100,2),"%") AS `1mo_%change`,

	CONCAT("$",ROUND(a.1qtr_min,2), "-",	ROUND(a.1qtr_max,2)) AS `1qtr_range`,
	CONCAT("$",ROUND((a.settlement-a.1qtr_settlement),2)) AS `1qtr_change`,
	CONCAT(ROUND(((a.settlement-a.1qtr_settlement)/a.1qtr_settlement)*100,2),"%") AS `1qtr_%change`,

	CONCAT("$",ROUND(a.1yr_min,2), "-",	ROUND(a.1yr_max,2)) AS `1yr_range`,
	CONCAT("$",ROUND((a.settlement-a.1yr_settlement),2)) AS `1yr_change`,
	CONCAT(ROUND(((a.settlement-a.1yr_settlement)/a.1yr_settlement)*100,2),"%") AS `1yr_%change`,
	code
FROM
	ubm_ice.clearing_code_index a
	WHERE a.clearing_code="'.$mysqli->real_escape_string($clearingcode).'"
ORDER BY a.`Year`,a.`Month`,a.`Day` DESC LIMIT 0,24';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpsExpiry,$tpsYear,$tpsMonth,$tpsDay,$tpsSettlement,$tpsRange,$tpsLast_change,$tpsLast_pchange,$tps1mo_range,$tps1mo_change,$tps1mo_pchange,$tps1qtr_range,$tps1qtr_change,$tps1qtr_pchange,$tps1yr_range,$tps1yr_change,$tps1yr_pchange,$tpscode);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpsExpiry.'","'.$tpsYear.'","'.$tpsMonth.'","'.$tpsDay.'","'.$tpsSettlement.'","'.$tpsRange.'","'.$tpsLast_change.'","'.$tpsLast_pchange.'","'.$tps1mo_range.'","'.$tps1mo_change.'","'.$tps1mo_pchange.'","'.$tps1qtr_range.'","'.$tps1qtr_change.'","'.$tps1qtr_pchange.'","'.$tps1yr_range.'","'.$tps1yr_change.'","'.$tps1yr_pchange.'","'.$tpscode.'"]';
			}
		}
	}
	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="nyiso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="miso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="innerstable" and isset($_GET["cc"]) and !empty($_GET["cc"])){
	$clearingcode=@trim($_GET["cc"]);

	$tmp_arr=$tmpccarr=$tempccarr=array();
	$tmpccarr=@explode(",",$clearingcode);
	if(count($tmpccarr)) foreach($tmpccarr as $vl) $tempccarr[]='"'.$mysqli->real_escape_string($vl).'"';

	$sql='SELECT a.`code`,a.date,a.settlement FROM ubm_ice.AR_MWIS a
	WHERE a.code IN ('.implode(",",$tempccarr).') ORDER BY a.date DESC LIMIT 0,6';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpscode,$tpsdate,$tpssettlement);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpscode.'","'.$tpsdate.'","'.$tpssettlement.'"]';
			}
		}
	}

	//echo json_encode(array("error"=>"","data"=>implode(',',$tmp_arr)));

	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';

}else echo '{"data": []}';

if(1==2){

if(isset($_GET["req"]) and isset($_GET["cc"]) and !empty($_GET["cc"])){
		$clearingcode=@trim($_GET["cc"]);
		$tmp_arr=array();

	$sql='SELECT
a.expiry,a.`Year`,a.`Month`,a.`Day`,
	CONCAT("$",ROUND(a.settlement,2)) AS settlement,

	CONCAT("$",ROUND(a.range_min,2),"-",ROUND(a.range_max,2)) AS `range`,
	CONCAT("$",ROUND((a.settlement-a.last_settlement),2)) AS last_change,
	CONCAT(ROUND(((a.settlement-a.last_settlement)/a.last_settlement)*100,2),"%") AS `last_%change`,

	CONCAT("$",ROUND(a.1mo_min,2), "-",	ROUND(a.1mo_max,2)) AS `1mo_range`,
	CONCAT("$",ROUND((a.settlement-a.1mo_settlement),2)) AS `1mo_change`,
	CONCAT(ROUND(((a.settlement-a.1mo_settlement)/a.1mo_settlement)*100,2),"%") AS `1mo_%change`,

	CONCAT("$",ROUND(a.1qtr_min,2), "-", ROUND(a.1qtr_max,2)) AS `1qtr_range`,
	CONCAT("$",ROUND((a.settlement-a.1qtr_settlement),2)) AS `1qtr_change`,
	CONCAT(ROUND(((a.settlement-a.1qtr_settlement)/a.1qtr_settlement)*100,2),"%") AS `1qtr_%change`,

	CONCAT("$",ROUND(a.1yr_min,2), "-",	ROUND(a.1yr_max,2)) AS `1yr_range`,
	CONCAT("$",ROUND((a.settlement-a.1yr_settlement),2)) AS `1yr_change`,
	CONCAT(ROUND(((a.settlement-a.1yr_settlement)/a.1yr_settlement)*100,2),"%") AS `1yr_%change`,
	code
FROM
	ubm_ice.clearing_code_index a
	WHERE a.clearing_code="'.$mysqli->real_escape_string($clearingcode).'"
ORDER BY a.`Year`,a.`Month`,a.`Day` DESC LIMIT 0,24';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpsExpiry,$tpsYear,$tpsMonth,$tpsDay,$tpsSettlement,$tpsRange,$tpsLast_change,$tpsLast_pchange,$tps1mo_range,$tps1mo_change,$tps1mo_pchange,$tps1qtr_range,$tps1qtr_change,$tps1qtr_pchange,$tps1yr_range,$tps1yr_change,$tps1yr_pchange,$tpscode);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpsExpiry.'","'.$tpsYear.'","'.$tpsMonth.'","'.$tpsDay.'","'.$tpsSettlement.'","'.$tpsRange.'","'.$tpsLast_change.'","'.$tpsLast_pchange.'","'.$tps1mo_range.'","'.$tps1mo_change.'","'.$tps1mo_pchange.'","'.$tps1qtr_range.'","'.$tps1qtr_change.'","'.$tps1qtr_pchange.'","'.$tps1yr_range.'","'.$tps1yr_change.'","'.$tps1yr_pchange.'","'.$tpscode.'"]';
			}
		}
	}
	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="nyiso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="miso"){
	echo '{
		"data": [
			[
				"Test1",
				"Test2"
			],
			[
				"Test1",
				"Test2"
			]
		]
	}';
}elseif(isset($_GET["req"]) and $_GET["req"]=="innerstable" and isset($_GET["cc"]) and !empty($_GET["cc"])){
	$clearingcode=@trim($_GET["cc"]);

	$tmp_arr=$tmpccarr=$tempccarr=array();
	$tmpccarr=@explode(",",$clearingcode);
	if(count($tmpccarr)) foreach($tmpccarr as $vl) $tempccarr[]='"'.$mysqli->real_escape_string($vl).'"';

	$sql='SELECT a.`code`,a.date,a.settlement FROM ubm_ice.AR_MWIS a
	WHERE a.code IN ('.implode(",",$tempccarr).') ORDER BY a.date DESC LIMIT 0,6';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpscode,$tpsdate,$tpssettlement);
			while($stmt->fetch()) {
				$tmp_arr[]='["'.$tpscode.'","'.$tpsdate.'","'.$tpssettlement.'"]';
			}
		}
	}

	//echo json_encode(array("error"=>"","data"=>implode(',',$tmp_arr)));

	echo '{
		"data": [
			'.implode(',',$tmp_arr).'
		]
	}';

}else echo '{"data": []}';


}
}

?>
