<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

$error="Error occured";

if(isset($_GET["state"]) and isset($_POST["name"]) and isset($_POST["state"])  and isset($_POST["category"])  and isset($_POST["policy"])){
  $subsqlarr=$stateabbr_arr=array();
  $subsql="";
  if(!empty($_POST["name"])) $subsqlarr[]='pg.name LIKE "%'.$mysqli->real_escape_string(urldecode($_POST["name"])).'%"';
  if(!empty($_POST["state"])) $subsqlarr[]='s.abbreviation="'.$mysqli->real_escape_string($_POST["state"]).'"';
  if(!empty($_POST["category"])) $subsqlarr[]='pgct.name="'.$mysqli->real_escape_string(urldecode($_POST["category"])).'"';
  if(!empty($_POST["policy"])) $subsqlarr[]='pt.name="'.$mysqli->real_escape_string(urldecode($_POST["policy"])).'"';

  if(count($subsqlarr)) $subsql=' AND '.implode(' AND ',$subsqlarr);

	$stmt_iso = $mysqli->prepare('SELECT DISTINCT s.abbreviation FROM dsireusa.program pg, dsireusa.program_category pgct, dsireusa.state s, dsireusa.program_type pt WHERE pg.program_category_id=pgct.id and s.id=pg.state_id and pg.program_type_id=pt.id ' .$subsql.' ORDER BY s.abbreviation');
	$stmt_iso->execute();
	$stmt_iso->store_result();
	if ($stmt_iso->num_rows > 0) {
		$stmt_iso->bind_result($stateabbr);
		while($stmt_iso->fetch()) {
			if($stateabbr == "") continue;
			$stateabbr_arr[]=$stateabbr;
		}

		if(count($stateabbr_arr)){
			echo json_encode(array("error"=>"","state"=>$stateabbr_arr));
		}else{
			echo json_encode(array("error"=>""));
		}
	}else{
		echo json_encode(array("error"=>"","state"=>""));
	}
}elseif(isset($_GET["category"]) and isset($_POST["name"]) and isset($_POST["state"])  and isset($_POST["category"])  and isset($_POST["policy"])){
  $subsqlarr=$stateabbr_arr=array();
  $subsql="";
  if(!empty($_POST["name"])) $subsqlarr[]='pg.name LIKE "%'.$mysqli->real_escape_string(urldecode($_POST["name"])).'%"';
  if(!empty($_POST["state"])) $subsqlarr[]='s.abbreviation="'.$mysqli->real_escape_string($_POST["state"]).'"';
  if(!empty($_POST["category"])) $subsqlarr[]='pgct.name="'.$mysqli->real_escape_string(urldecode($_POST["category"])).'"';
  if(!empty($_POST["policy"])) $subsqlarr[]='pt.name="'.$mysqli->real_escape_string(urldecode($_POST["policy"])).'"';

  if(count($subsqlarr)) $subsql=' AND '.implode(' AND ',$subsqlarr);

	$stmt_iso = $mysqli->prepare('SELECT DISTINCT pgct.name FROM dsireusa.program pg, dsireusa.program_category pgct, dsireusa.state s, dsireusa.program_type pt WHERE pg.program_category_id=pgct.id and s.id=pg.state_id and pg.program_type_id=pt.id ' .$subsql.' ORDER BY pgct.name');
	$stmt_iso->execute();
	$stmt_iso->store_result();
	if ($stmt_iso->num_rows > 0) {
		$stmt_iso->bind_result($stateabbr);
		while($stmt_iso->fetch()) {
			if($stateabbr == "") continue;
			$stateabbr_arr[]=$stateabbr;
		}

		if(count($stateabbr_arr)){
			echo json_encode(array("error"=>"","category"=>$stateabbr_arr));
		}else{
			echo json_encode(array("error"=>""));
		}
	}else{
		echo json_encode(array("error"=>"","category"=>""));
	}
}elseif(isset($_GET["policy"]) and isset($_POST["name"]) and isset($_POST["state"])  and isset($_POST["category"])  and isset($_POST["policy"])){
  $subsqlarr=$stateabbr_arr=array();
  $subsql="";
  if(!empty($_POST["name"])) $subsqlarr[]='pg.name LIKE "%'.$mysqli->real_escape_string(urldecode($_POST["name"])).'%"';
  if(!empty($_POST["state"])) $subsqlarr[]='s.abbreviation="'.$mysqli->real_escape_string($_POST["state"]).'"';
  if(!empty($_POST["category"])) $subsqlarr[]='pgct.name="'.$mysqli->real_escape_string(urldecode($_POST["category"])).'"';
  if(!empty($_POST["policy"])) $subsqlarr[]='pt.name="'.$mysqli->real_escape_string(urldecode($_POST["policy"])).'"';

  if(count($subsqlarr)) $subsql=' AND '.implode(' AND ',$subsqlarr);

	$stmt_iso = $mysqli->prepare('SELECT DISTINCT pt.name FROM dsireusa.program pg, dsireusa.program_category pgct, dsireusa.state s, dsireusa.program_type pt WHERE pg.program_category_id=pgct.id and s.id=pg.state_id and pg.program_type_id=pt.id ' .$subsql.' ORDER BY pt.name');
	$stmt_iso->execute();
	$stmt_iso->store_result();
	if ($stmt_iso->num_rows > 0) {
		$stmt_iso->bind_result($stateabbr);
		while($stmt_iso->fetch()) {
			if($stateabbr == "") continue;
			$stateabbr_arr[]=$stateabbr;
		}

		if(count($stateabbr_arr)){
			echo json_encode(array("error"=>"","policy"=>$stateabbr_arr));
		}else{
			echo json_encode(array("error"=>""));
		}
	}else{
		echo json_encode(array("error"=>"","policy"=>""));
	}
}else echo false;
?>
