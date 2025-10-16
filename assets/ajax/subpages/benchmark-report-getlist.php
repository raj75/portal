<?php //require_once("inc/init.php"); 
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die(false);
		
$user_one=$_SESSION["user_id"];


if(isset($_GET["comparable_filter"]) and isset($_GET["cid"]) and isset($_GET["sdate"]) and !empty($_GET["sdate"]) and isset($_GET["edate"]) and !empty($_GET["edate"])){
	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$sdate=$_GET["sdate"];
	$edate=$_GET["edate"];
//die("SELECT 'All' AS list, 'All' AS comparable UNION ALL SELECT DISTINCT IF(a.comparable=1, 'Comparable', 'Non-Comparable') AS list, a.comparable FROM benchmark_report AS a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) >= '".$mysqli->real_escape_string($sdate)."' AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) <= '".$mysqli->real_escape_string(@$edate)."'");
	if(empty(@trim($sdate)) || empty(@trim($edate))){ echo false; exit(); }

	$tmp_arr=array();
   if ($stmt_section1 = $mysqli->prepare("SELECT 'All' AS list, 'All' AS comparable UNION ALL SELECT DISTINCT IF(a.comparable=1, 'Comparable', 'Non-Comparable') AS list, a.comparable FROM benchmark_report AS a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) >= '".$mysqli->real_escape_string($sdate)."' AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) <= '".$mysqli->real_escape_string($edate)."' and a.company_id='".$mysqli->real_escape_string($cid)."'")) { 
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($list,$comparable);
			while($stmt_section1->fetch()){
				$tmp_arr[] = '<option value="'.$comparable.'">'.$list.'</option>';
			}
		}
   }
   
   echo @rawurlencode(@implode("",$tmp_arr));
	exit();
}

if(isset($_GET["site_name_filter"]) and isset($_GET["cid"]) and isset($_GET["sdate"]) and !empty($_GET["sdate"]) and isset($_GET["edate"]) and !empty($_GET["edate"])){
	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$sdate=$_GET["sdate"];
	$edate=$_GET["edate"];
	
	if(empty(@trim($sdate)) || empty(@trim($edate))){ echo false; exit(); }

	$tmp_arr=array();
   if ($stmt_section1 = $mysqli->prepare("SELECT 'All' UNION ALL (SELECT DISTINCT a.site_name FROM benchmark_report AS a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) >= '".$mysqli->real_escape_string($sdate)."' AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) <= '".$mysqli->real_escape_string($edate)."' and a.company_id='".$mysqli->real_escape_string($cid)."' ORDER BY a.site_number ASC)")) { 
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($sitename);
			while($stmt_section1->fetch()){
				if(@empty(@trim($sitename))) continue;
				$tmp_arr[] = '<option value="'.$sitename.'">'.$sitename.'</option>';
			}
		}
   }
   
   echo @rawurlencode(@implode("",$tmp_arr));
	exit();
}


if(isset($_GET["state_filter"]) and isset($_GET["cid"]) and isset($_GET["sdate"]) and !empty($_GET["sdate"]) and isset($_GET["edate"]) and !empty($_GET["edate"])){
	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$sdate=$_GET["sdate"];
	$edate=$_GET["edate"];
	
	if(empty(@trim($sdate)) || empty(@trim($edate))){ echo false; exit(); }

	$tmp_arr=array();
   if ($stmt_section1 = $mysqli->prepare("SELECT 'All' UNION ALL (SELECT DISTINCT a.state FROM benchmark_report AS a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) >= '".$mysqli->real_escape_string($sdate)."' AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) <= '".$mysqli->real_escape_string($edate)."' and a.company_id='".$mysqli->real_escape_string($cid)."' ORDER BY a.state ASC)")) { 
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($statename);
			while($stmt_section1->fetch()){
				if(@empty(@trim($statename))) continue;
				$tmp_arr[] = '<option value="'.$statename.'">'.$statename.'</option>';
			}
		}
   }
   
   echo @rawurlencode(@implode("",$tmp_arr));
	exit();
}

if((isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))  and isset($_GET["cid"]) and isset($_GET["sdate"]) and !empty($_GET["sdate"]) and isset($_GET["edate"]) and !empty($_GET["edate"])){
	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);
	
	if(isset($_GET["group1"])) $grouptype=1;
	elseif(isset($_GET["group2"])) $grouptype=2;
	elseif(isset($_GET["group3"])) $grouptype=3;
	elseif(isset($_GET["group4"])) $grouptype=4;
	elseif(isset($_GET["group5"])) $grouptype=5;

	$sdate=$_GET["sdate"];
	$edate=$_GET["edate"];

	if(empty(@trim($sdate)) || empty(@trim($edate))){ echo false; exit(); }

	$tmp_arr=array();
   if ($stmt_section1 = $mysqli->prepare("SELECT 'All' UNION ALL (SELECT DISTINCT a.grouping".$grouptype." FROM benchmark_report AS a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) >= '".$mysqli->real_escape_string($sdate)."' AND DATE(CONCAT(a.`year`,'-',a.`month`,'-01')) <= '".$mysqli->real_escape_string($edate)."' and a.company_id='".$mysqli->real_escape_string($cid)."' ORDER BY a.grouping".$grouptype." ASC)")) { 
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($regionname);
			while($stmt_section1->fetch()){
				if(@empty(@trim($regionname))) continue;
				$tmp_arr[] = '<option value="'.$regionname.'">'.$regionname.'</option>';
			}
		}
   }
   
   echo @rawurlencode(@implode("",$tmp_arr));
	exit();
}
echo false;
?>