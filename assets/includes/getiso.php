<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

if(isset($_POST["iso"]))
{

	$error="Error occured";
	$node_arr=array();
	$iso = @trim($_POST['iso']);
	
	if($iso=="") $subsql='';
	else $subsql=' where iso ="'.$mysqli->real_escape_string($iso).'" ';
	
	
	$stmt_iso = $mysqli->prepare('SELECT DISTINCT NODE_ID FROM iso.iso '.$subsql.'ORDER BY NODE_ID');
	$stmt_iso->execute();
	$stmt_iso->store_result();
	if ($stmt_iso->num_rows > 0) {
		$stmt_iso->bind_result($dnodeid);
		while($stmt_iso->fetch()) {
			if($dnodeid == "") continue;
			$node_arr[]=$dnodeid;			
		}
		
		if(count($node_arr)){
			echo json_encode(array("error"=>"","node"=>$node_arr));
		}else{	
			echo json_encode(array("error"=>""));
		}
	}else{
		echo json_encode(array("error"=>$error));	
	}
}

//print_r($_POST);
echo false;
?>