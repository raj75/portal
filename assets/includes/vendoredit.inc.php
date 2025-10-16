<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

if(!isset($group_id) or ($group_id != 1 and $_SESSION["group_id"] != 2)){echo false;exit();}

//Add New Company
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_POST["vnew"]) and $_POST["vnew"] != "")
{

	$error="Error occured";
	$sub_query=$new_value=array();
	$fileok=0;
	
	if(isset($_POST['vid']) and @trim($_POST['vid']) != "")
	{
		$vid=$mysqli->real_escape_string(@trim($_POST['vid']));
	   if ($stmt = $mysqli->prepare('SELECT vendor_id FROM `vendor` where vendor_id="'.$vid.'" LIMIT 1')) { 

//('SELECT id FROM `vendor` where vendor_id="'.$vid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='vendor_id="'.$vid.'"';
				$new_value['vendor_id']=$vid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Vendor ID already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor ID required.'));
		exit();		
	}

	if(isset($_POST['vname']) and @trim($_POST['vname']) != "")
	{
		$vname=$mysqli->real_escape_string(@trim($_POST['vname']));
	   if ($stmt = $mysqli->prepare('SELECT vendor_id FROM `vendor` where vendor_name="'.$vname.'" LIMIT 1')) { 
	   if ($stmt = $mysqli->prepare('SELECT v2.vendor_id FROM `vendor` v, vendor v2 where v.vendor_name="'.$vname.'" and v.vendor_name=v2.vendor_name and v.service_group=v2.service_group and v.vendor_id !=v2.vendor_id and v.capturis_vendor_id=v2.capturis_vendor_id LIMIT 1')) {  
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='vendor_name="'.$vname.'"';
				$new_value['vendor_name']=$vname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Vendor name already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor name required.'));
		exit();		
	}

	if(isset($_POST['vabbr']) and @trim($_POST['vabbr']) != "")
	{
		$sub_query[]='vendor_abbreviation="'.$mysqli->real_escape_string(@trim($_POST['vabbr'])).'"';
		$new_value['vendor_abbreviation']=$mysqli->real_escape_string(@trim($_POST['vabbr']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor abbreviation required.'));
		exit();		
	}

	if(isset($_POST['vcom']) and @trim($_POST['vcom']) != "")
	{
		$sub_query[]='service_group="'.$mysqli->real_escape_string(@trim($_POST['vcom'])).'"';
		$new_value['service_group']=$mysqli->real_escape_string(@trim($_POST['vcom']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Commodity required.'));
		exit();		
	}

	if(isset($_POST['vdereg']) and @trim($_POST['vdereg']) != "")
	{
		$sub_query[]='deregulated="'.$mysqli->real_escape_string(@trim($_POST['vdereg'])).'"';
		$new_value['deregulated']=$mysqli->real_escape_string(@trim($_POST['vdereg']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Deregulated required.'));
		exit();		
	}

	if(isset($_POST['valt']) and @trim($_POST['valt']) != "")
	{
		$sub_query[]='vendor_altname1="'.$mysqli->real_escape_string(@trim($_POST['valt'])).'"';
		$new_value['vendor_altname1']=$mysqli->real_escape_string(@trim($_POST['valt']));
	}

	if(count($sub_query)){

		audit_log($mysqli,"vendor","INSERT",$new_value,"","","");
		$sql='INSERT INTO vendor SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){				
				echo json_encode(array("error"=>""));				
				exit();
			}else{					
				echo json_encode(array("error"=>$error));
			}
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}
//Add vendor ends


//Edit Vendor
if(isset($group_id) and ($group_id == 1 or $_SESSION["group_id"] == 2) and isset($_POST["vid"]) and @trim($_POST["vid"]) != "" and @trim($_POST["vid"]) != 0 and isset($_POST["vedit"]))
{

	$error="Error occured";
	$sub_query=$new_value=array();
	
	$vid=$mysqli->real_escape_string(@trim($_POST["vid"]));

	if(isset($_POST['vvid']) and @trim($_POST['vvid']) != "")
	{
		$vvid=$mysqli->real_escape_string(@trim($_POST['vvid']));
	   if ($stmt = $mysqli->prepare('SELECT vendor_id FROM `vendor` where vendor_id="'.$vvid.'" and vendor_id != "'.$vid.'" LIMIT 1')) { 
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='vendor_id="'.$vvid.'"';
				$new_value['vendor_id']=$vvid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Similar Vendor ID already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor ID required.'));
		exit();		
	}

	if(isset($_POST['vname']) and @trim($_POST['vname']) != "")
	{
		$vname=$mysqli->real_escape_string(@trim($_POST['vname']));
	   //if ($stmt = $mysqli->prepare('SELECT vendor_id FROM `vendor` where vendor_name="'.$vname.'" and vendor_id != "'.$vid.'" LIMIT 1')) { 
	   if ($stmt = $mysqli->prepare('SELECT v2.vendor_id FROM `vendor` v, vendor v2 where v.vendor_name="'.$vname.'" and v.vendor_name=v2.vendor_name and v.service_group=v2.service_group and v.vendor_id !=v2.vendor_id and v.capturis_vendor_id=v2.capturis_vendor_id and v.vendor_id != "'.$vid.'" LIMIT 1')) { 

//('SELECT id FROM `vendor` where vendor_name="'.$vname.'" and id != "'.$vid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='vendor_name="'.$vname.'"';
				$new_value['vendor_name']=$vname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Similar Vendor name already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor name required.'));
		exit();		
	}


	if(isset($_POST['vabbr']) and @trim($_POST['vabbr']) != "")
	{
		$sub_query[]='vendor_abbreviation="'.$mysqli->real_escape_string(@trim($_POST['vabbr'])).'"';
		$new_value['vendor_abbreviation']=$mysqli->real_escape_string(@trim($_POST['vabbr']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor abbreviation required.'));
		exit();		
	}

	if(isset($_POST['vcom']) and @trim($_POST['vcom']) != "")
	{
		$sub_query[]='service_group="'.$mysqli->real_escape_string(@trim($_POST['vcom'])).'"';
		$new_value['service_group']=$mysqli->real_escape_string(@trim($_POST['vcom']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Commodity required.'));
		exit();		
	}

	if(isset($_POST['vdereg']) and @trim($_POST['vdereg']) != "")
	{
		$sub_query[]='deregulated="'.$mysqli->real_escape_string(@trim($_POST['vdereg'])).'"';
		$new_value['deregulated']=$mysqli->real_escape_string(@trim($_POST['vdereg']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Deregulated required.'));
		exit();		
	}

	if(isset($_POST['valt']) and @trim($_POST['valt']) != "")
	{
		$sub_query[]='vendor_altname="'.$mysqli->real_escape_string(@trim($_POST['valt'])).'"';
		$new_value['vendor_altname']=$mysqli->real_escape_string(@trim($_POST['valt']));
	}

	if(count($sub_query)){			
			audit_log($mysqli,"vendor","UPDATE",$new_value,'WHERE id='.$vid,"","");			
			$sql='UPDATE vendor SET '.implode(",",$sub_query).' WHERE vendor_id='.$vid;
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

				echo json_encode(array("error"=>""));
				exit();				
			}else{
				echo json_encode(array("error"=>$error));			
				exit();			
			}
	}
	echo json_encode(array("error"=>$error));
	exit();
}

//Delete Vendor
if(isset($group_id) and ($group_id == 1 or $_SESSION["group_id"] == 2) and isset($_POST["vid"]) and @trim($_POST["vid"]) != "" and @trim($_POST["vid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();	
	
	$vid=$mysqli->real_escape_string(@trim($_POST["vid"]));	
	
	$stmtsk = $mysqli->prepare('SELECT vendor_id FROM vendor where vendor_id="'.$vid.'" LIMIT 1');

//('SELECT id FROM vendor where id="'.$vid.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtskk = $mysqli->prepare('SELECT vendor_id FROM vendor where vendor_id="'.$vid.'" LIMIT 1');

//('SELECT id FROM vendor where vendor_id="'.$vid.'" LIMIT 1');

			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					echo json_encode(array('error'=>'Error Occured!  delete the sites/accounts of this vendor.'));
					exit();
				}else{
					audit_log($mysqli,"vendor","DELETE","",'WHERE vendor_id="'.$vid.'" LIMIT 1',"","");

//($mysqli,"vendor","DELETE","",'WHERE id="'.$vid.'" LIMIT 1',"","");

					$stmtskks = $mysqli->prepare('DELETE FROM vendor where vendor_id="'.$vid.'" LIMIT 1');

//('DELETE FROM vendor where id="'.$vid.'" LIMIT 1');

					if($stmtskks){
						$stmtskks->execute();
						$lastcaffectedID=$stmtskks->affected_rows;
						if($lastcaffectedID==1)
						{
							echo json_encode(array('error'=>''));
							exit();													
						}else{
							echo json_encode(array('error'=>'Error Occured! Database error.'));
							exit();						
						}
					}else{
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();
					}				
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Vendor doesn\'t exists.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}
}
//Delete vendor ends



//print_r($_POST);
echo false;
exit();
?>