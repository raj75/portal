<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if(!isset($_SESSION["group_id"]) or !isset($_SESSION['user_id']) or ($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 5))
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$comp_id=$_SESSION['company_id'];

//Edit Client Dashboard Sumary

if(isset($_POST["edit"]) and isset($_POST["cdsid"]) and $_POST["edit"]=="editcds")
{
	$error="Error occured";
	$sub_query=$new_value=array();
	$companyname="";
	
	$cdsid= $mysqli->real_escape_string(@trim($_POST["cdsid"]));
	if($cdsid != "" and $cdsid != 0 and $cdsid != "0")
	{}
	else{
		echo json_encode(array('error'=>'Error Occured! Wrong User Information.'));
		exit();		
	}
	
	if($_SESSION['group_id'] != 1) $cdsid=$comp_id;

	if(isset($_POST['sum']) and @trim($_POST['sum']) != "")
	{
		$tmp_sum=$mysqli->real_escape_string(@trim($_POST['sum']));
		if ($stmtkks = $mysqli->prepare("SELECT company_id FROM company WHERE company_id='".$cdsid."'")) { 
			$stmtkks->execute();
			$stmtkks->store_result();
			if ($stmtkks->num_rows == 0) {
				echo json_encode(array('error'=>'Error Occured! Company data doesn\'t exists.'));
				exit();			
			}else{
				$sub_query[]='sites_under_mgmt="'.$tmp_sum.'"';
				$new_value['sites_under_mgmt']=$tmp_sum;				
			}			
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();	
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Sites Under Mgmt required.'));
		exit();		
	}

	
	if(isset($_POST['vstd']) and @trim($_POST['vstd']) != "")
	{
		$vstd=$mysqli->real_escape_string(@trim($_POST['vstd']));
		$sub_query[]='val_saving_to_date="'.$vstd.'"';
		$new_value['val_saving_to_date']=$vstd;
	}else{
		echo json_encode(array('error'=>'Error Occured! Value Of Saving To Date required.'));
		exit();			
	}

	if(isset($_POST['power']) and @trim($_POST['power']) != "")
	{
		$power=$mysqli->real_escape_string(@trim($_POST['power']));
		$sub_query[]='acc_under_mgmt_pwr="'.$power.'"';
		$new_value['acc_under_mgmt_pwr']=$power;		
	}else{
		echo json_encode(array('error'=>'Error Occured! Accounts Under Mgmt(Power) required.'));
		exit();		
	}

	if(isset($_POST['gas']) and @trim($_POST['gas']) != "")
	{
		$sub_query[]='acc_under_mgmt_gas="'.$mysqli->real_escape_string(@trim($_POST['gas'])).'"';
		$new_value['acc_under_mgmt_gas']=$mysqli->real_escape_string(@trim($_POST['gas']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Accounts Under Mgmt(Gas) required.'));
		exit();		
	}

	if(isset($_POST['gwh']) and @trim($_POST['gwh']) != "")
	{
		$sub_query[]='cons_under_mgmt_gwh="'.$mysqli->real_escape_string(@trim($_POST['gwh'])).'"';
		$new_value['cons_under_mgmt_gwh']=$mysqli->real_escape_string(@trim($_POST['gwh']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Consumption Under Mgmt(GWh) required.'));
		exit();		
	}
	
	if(isset($_POST['mmbtu']) and @trim($_POST['mmbtu']) != "")
	{
		$sub_query[]='cons_under_mgmt_mmbtu="'.$mysqli->real_escape_string(@trim($_POST['mmbtu'])).'"';
		$new_value['cons_under_mgmt_mmbtu']=$mysqli->real_escape_string(@trim($_POST['mmbtu']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Consumption Under Mgmt(MMBtu) required.'));
		exit();		
	}	
	
	if(count($sub_query)){
		//audit_log($mysqli,"company","UPDATE",$new_value,'WHERE id='.$tmp_cpy,($fileok==1?"NEW":""),($efile==1?"EXIST":""));
		if ($stmtk = $mysqli->prepare('SELECT company_id  FROM company WHERE company_id="'.$cdsid.'" LIMIT 1')) { 
			$stmtk->execute();
			$stmtk->store_result();
			if ($stmtk->num_rows > 0) {
				$sql='UPDATE company SET '.implode(",",$sub_query).' WHERE company_id="'.$cdsid.'"';
				$stmt = $mysqli->prepare($sql);
				if($stmt)
				{
					$stmt->execute();
					$lastaffectedID=$stmt->affected_rows;

					echo json_encode(array("error"=>""));
					exit();				
				}else{
					echo json_encode(array("error"=>$error."1"));			
					exit();			
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Company data doesn\'t exists.'));
				exit();				
			}
		}else{
			echo json_encode(array("error"=>$error."4"));			
			exit();			
		}
	}else{
		$sql='UPDATE company SET sites_under_mgmt="",	acc_under_mgmt_pwr="", acc_under_mgmt_gas="", cons_under_mgmt_gwh="", cons_under_mgmt_mmbtu="", val_saving_to_date="" WHERE company_id="'.$cdsid.'"';
		$stmtk = $mysqli->prepare($sql);
		if($stmtk)
		{
			$stmtk->execute();
			$lastaffectedID=$stmtk->affected_rows;

			echo json_encode(array("error"=>""));
			exit();				
		}else{
			echo json_encode(array("error"=>$error."1"));			
			exit();			
		}
	}
}
//Edit Client Dashboard Summary Ends

//print_r($_POST);
echo false;
exit();
?>