<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$ccid=$_SESSION['company_id'];
$group_id=$_SESSION['group_id'];

//Update Company Info
if(isset($user_one) and isset($group_id) and ($group_id == 1 || $group_id == 2 || $group_id == 5)  and $user_one != "" and $user_one != 0 and isset($_POST["company_id"]) and $_POST["company_id"] !="" and $_POST["company_id"] != 0)
{

	$error="Error occured";
	$sub_query=array();
	
	if($group_id == 5 and $ccid != $_POST["company_id"]){echo json_encode(array("error"=>$error));exit();}
	
	$company_id=$mysqli->real_escape_string(@trim($_POST["company_id"]));

	foreach ($_POST as $param_name => $param_val) {
		if($param_name == "company_id") continue;
		$sub_query[]= '`'.@str_replace("_"," ",$param_name).'` = "'.$mysqli->real_escape_string(@trim($param_val)).'"';
	}
	
	
	if(count($sub_query)){
		
		if ($stmtcheck = $mysqli->prepare('SELECT companyID FROM company_defaults where companyID="'.$mysqli->real_escape_string($company_id).'" LIMIT 1')) { 
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows != 0){		
				$sql='UPDATE company_defaults SET updated_by_user_id="'.$user_one.'", '.implode(",",$sub_query).' WHERE companyID="'. $company_id.'"';
				$stmt = $mysqli->prepare($sql);
				if($stmt){
					$stmt->execute();
					echo json_encode(array("error"=>""));
					exit();
				}else{
					echo json_encode(array("error"=>$error));
				}
				exit();
			}else{
				$sql='INSERT INTO company_defaults SET companyID="'.$company_id.'", updated_by_user_id="'.$user_one.'", '.implode(",",$sub_query).'';
				$stmt = $mysqli->prepare($sql);
				if($stmt){
					$stmt->execute();
					echo json_encode(array("error"=>""));
					exit();
				}else{
					echo json_encode(array("error"=>$error));
				}
				exit();				
				
			}
		}
	}else echo json_encode(array("error"=>$error));
}

//print_r($_POST);
echo false;
exit();
?>