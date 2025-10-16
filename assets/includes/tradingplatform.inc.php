<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

if(isset($_POST["exchn"]) and !empty($_POST["exchn"]) and isset($_POST["symbl"]) and !empty($_POST["symbl"]))
{
	$error="Error occured";
	$exchn=$_POST["exchn"];
	$symbl=$_POST["symbl"];

	if($stmt = $mysqli->prepare("SELECT a.Description,a.exchange,a.clearing_code,a.`GROUP` AS commodity,a.`status`,a.contract_type,a.date_code_min,a.date_code_max,a.max_date,a.contracts,a.spot_contract,a.spot_price,a.12_strip,DATE_ADD(a.spot_contract, INTERVAL 12 MONTH) FROM ICE.clearing_code a WHERE a.`status`='Active' and a.exchange='".$mysqli->real_escape_string($exchn)."' and a.clearing_code='".$mysqli->real_escape_string($symbl)."' LIMIT 1")) {
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows > 0) {
			$stmt->bind_result($tpdescription,$tpexchange,$tpclearing_code,$tpcommodity,$tpstatus,$tpcontract_type,$tpdate_code_min,$tpdate_code_max,$tpmax_date,$tpcontracts,$tpspot_contract,$tpspot_price,$tp12_strip,$tpspot_contract1);
			$stmt->fetch();

			echo json_encode(array("error"=>"","sresult"=>array("contracttype"=>$tpcontract_type,"description"=>$tpdescription,"contractstart"=>$tpspot_contract,"contractend"=>$tpspot_contract1)));
		}else{
			echo json_encode(array("error"=>"No data!"));
			exit();
		}
	}

}elseif(isset($_POST["uid"]) and !empty($_POST["uid"]) and isset($_POST["symlist"]) and !empty($_POST["symlist"]) and isset($_POST["chartname"]))
{

	$error="Error occured";
	$uid=$_POST["uid"];
	$uid=$user_one;
	$symlist=$_POST["symlist"];
	$chartname=$_POST["chartname"];

	if(isset($_POST["sid"]) and !empty($_POST["sid"])){
		$osid=$_POST["sid"];

		if($stmt = $mysqli->prepare("SELECT symbol_list FROM ICE.portfolio WHERE user_id=".$uid." and id='".$mysqli->real_escape_string($osid)."'")) {
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0) {
				$stmtu = $mysqli->prepare("UPDATE ICE.portfolio SET symbol_list='".$mysqli->real_escape_string($symlist)."', chart_name='".$mysqli->real_escape_string($chartname)."' WHERE id='".$mysqli->real_escape_string($osid)."'");
				$stmtu->execute();
				echo json_encode(array("error"=>"Portfolio updated successfully!"));
				exit();
			}else{
				echo json_encode(array("error"=>"This portfolio doesnot exist!"));
				exit();
			}
		}
	}else
		$sql="SELECT symbol_list FROM ICE.portfolio WHERE user_id=".$uid." and symbol_list='".$mysqli->real_escape_string($symlist)."'";

	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			echo json_encode(array("error"=>"This portfolio already exist!"));
		}else{
			$sql="INSERT INTO ICE.portfolio SET user_id=".$uid.", symbol_list='".$mysqli->real_escape_string($symlist)."', chart_name='".$mysqli->real_escape_string($chartname)."'";
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			echo json_encode(array("error"=>""));
			exit();
		}
	}

}elseif(isset($_POST["uid"]) and !empty($_POST["uid"]) and isset($_POST["pname"]))
{
	$tmpsql="";
	$error="Error occured";
	$sid=$_POST["uid"];
	$sid=$user_one;
	$pname=@trim($_POST["pname"]);

	if($_SESSION['group_id'] !=1 && $_SESSION['group_id'] !=2) $tmpsql="user_id=".$user_one." and ";

	if($stmt = $mysqli->prepare("SELECT chart_name FROM ICE.portfolio WHERE ".$tmpsql."id='".$mysqli->real_escape_string($sid)."'")) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt = $mysqli->prepare("UPDATE ICE.portfolio SET chart_name='".$mysqli->real_escape_string($pname)."' WHERE id='".$mysqli->real_escape_string($sid)."'");
			$stmt->execute();
			echo json_encode(array("error"=>""));
			exit();
		}else{
			echo json_encode(array("error"=>"This portfolio doesnot exist!"));
			exit();
		}
	}
}elseif(isset($_POST["chartids"]) and !empty($_POST["chartids"]))
{
	$error="Error occured";
	$chartids=$_POST["chartids"];

	if($stmt = $mysqli->prepare("SELECT * from ICE.sort_portfolio where user_id='".$mysqli->real_escape_string($user_one)."' LIMIT 1")) {
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows > 0) {
			$stmt1 = $mysqli->prepare("UPDATE ICE.sort_portfolio SET sort='".$mysqli->real_escape_string($chartids)."' WHERE user_id='".$mysqli->real_escape_string($user_one)."'");
			$stmt1->execute();
			echo json_encode(array("error"=>""));
			exit();
		}else{
			$sql="INSERT INTO ICE.sort_portfolio SET user_id=".$mysqli->real_escape_string($user_one).", sort='".$mysqli->real_escape_string($chartids)."'";
			$stmt1 = $mysqli->prepare($sql);
			$stmt1->execute();
			echo json_encode(array("error"=>""));
			exit();
		}
	}

}elseif(isset($_POST["deleteportfolio"]) and !empty($_POST["deleteportfolio"]))
{
	$error="Error occured";
	$sid=$_POST["deleteportfolio"];

	$tmpsql="";
	if($_SESSION['group_id'] !=1 && $_SESSION['group_id'] !=2) $tmpsql="user_id=".$user_one." and ";
	if($stmt = $mysqli->prepare("SELECT chart_name FROM ICE.portfolio WHERE ".$tmpsql."id='".$mysqli->real_escape_string($sid)."'")) {
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows > 0) {
			$stmt1 = $mysqli->prepare("Delete From ICE.portfolio WHERE id='".$mysqli->real_escape_string($sid)."'");
			$stmt1->execute();
			echo json_encode(array("error"=>""));
			exit();
		}else{
			echo json_encode(array("error"=>"Portfolio doesnot exists."));
			exit();
		}
	}

}

echo false;
?>
