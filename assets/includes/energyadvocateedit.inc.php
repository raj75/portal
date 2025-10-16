<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

//if(!isset($_SESSION["group_id"]) or !isset($_SESSION['user_id']) or ($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2))
	//die();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

//Add New Energy Advocate
if(isset($_POST["newad"]) and $_POST["newad"]=="new")
{

	$error="Error occured";
	$sub_query=$new_value=array();
	$companyname="";
	
	if(isset($_POST['cid']) and @trim($_POST['cid']) != "")
	{
		$cid=$mysqli->real_escape_string(@trim($_POST['cid']));
		$stmtsk = $mysqli->prepare('SELECT company_name FROM company WHERE company_id="'.$cid.'"  LIMIT 1');

//('SELECT company_name FROM company WHERE id="'.$cid.'"  LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{
				$stmtsk->bind_result($__cname);
				$stmtsk->fetch();
				$companyname=$__cname;
				
				//$sub_query[]='company_id="'.$cid.'"';
				//$new_value['company_id']=$cid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company Name doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company Name required.'));
		exit();			
	}
	
	if(isset($_POST['eaid']) and @trim($_POST['eaid']) != "")
	{
		$eaid=$mysqli->real_escape_string(@trim($_POST['eaid']));
		$stmtsk = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$eaid.'"  LIMIT 1');

//('SELECT id FROM user WHERE id="'.$eaid.'"  LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{
				$stmtsk->bind_result($__cname);
				$stmtsk->fetch();
				$companyname=$__cname;
				
				$sub_query[]='user_id="'.$eaid.'"';
				$new_value['user_id']=$eaid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Energy Advocate doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Energy Advocate required.'));
		exit();			
	}
	
//Company Admin
	if(isset($_POST['addcmpadid']) and @trim($_POST['addcmpadid']) != "")
	{
		$addcid=$mysqli->real_escape_string(@trim($_POST['addcmpadid']));
		$stmtskca = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$addcid.'" and company_id="'.$cid.'"  LIMIT 1');

//('SELECT id FROM user WHERE id="'.$eaid.'"  LIMIT 1');

		if($stmtskca){
			$stmtskca->execute();
			$stmtskca->store_result();
			if ($stmtskca->num_rows != 0)
			{
				//$sub_query[]='company_admin="'.$addcid.'"';
				//$new_value['company_admin']=$addcid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company Admin doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company Admin required.'));
		exit();			
	}
	
//UBM support	
	
	if(isset($_POST['addubmid']) and @trim($_POST['addubmid']) != "")
	{
		$addubmid=$mysqli->real_escape_string(@trim($_POST['addubmid']));
		$stmtskus = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$addubmid.'"  LIMIT 1');

		if($stmtskus){
			$stmtskus->execute();
			$stmtskus->store_result();
			if ($stmtskus->num_rows != 0)
			{
				//$sub_query[]='ubm_support="'.$addubmid.'"';
				//$new_value['ubm_support']=$addubmid;
			}else{
				echo json_encode(array('error'=>'Error Occured! UBM Support doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! UBM Support required.'));
		exit();			
	}


	
	

	if(count($sub_query)){
		$stmtsk = $mysqli->prepare('SELECT company_id FROM company WHERE company_id="'.$cid.'"  LIMIT 1');
		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{	

				//$sub_query[]='added_by="'.$eaid.'"';
				//$new_value['added_by']=$eaid;
				//$sub_query[]='date_added="'.$mysqli->real_escape_string(@date("Y-m-d H:i:s")).'"';
				//$new_value['date_added']=$mysqli->real_escape_string(@date("Y-m-d H:i:s"));
				
				$sql='UPDATE company SET energy_advocate="'.$eaid.'",date_added="'.$mysqli->real_escape_string(@date("Y-m-d H:i:s")).'",added_by="'.$user_one.'",company_admin="'.$addcid.'",ubm_support="'.$addubmid.'" WHERE company_id="'.$cid.'"';
				$stmtad = $mysqli->prepare($sql);
				if($stmtad)
				{
					$stmtad->execute();
					$lastaffectedID=$stmtad->affected_rows;

					echo json_encode(array("error"=>""));
					exit();				
				}else{
					echo json_encode(array("error"=>$error));			
					exit();			
				}
				exit();
			}
		}
	}
}
//Add Energy Advocate Ends

//Edit Energy Advocate
if(isset($_POST["editad"]) and $_POST["editad"]=="edit")
{
	$error="Error occured";
	$sub_query=$new_value=array();
	$companyname="";

	if(isset($_POST['cid']) and @trim($_POST['cid']) != "")
	{		
		$cid=$mysqli->real_escape_string(@trim($_POST['cid']));
		$stmtsk = $mysqli->prepare('SELECT company_id FROM company WHERE company_id="'.$cid.'" LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{
			}else{
				echo json_encode(array('error'=>'Error Occured! Company doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company Name required.'));
		exit();			
	}

	if(isset($_POST['eaid']) and @trim($_POST['eaid']) != "")
	{
		$eaid=$mysqli->real_escape_string(@trim($_POST['eaid']));
		$stmtsk = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$eaid.'"  LIMIT 1');

//('SELECT id FROM user WHERE id="'.$eaid.'"  LIMIT 1');

		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows != 0)
			{
				$sub_query[]='user_id="'.$eaid.'"';
				$new_value['user_id']=$eaid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Energy Advocate doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Energy Advocate required.'));
		exit();			
	}	

//Company Admin
	if(isset($_POST['editcmpadid']) and @trim($_POST['editcmpadid']) != "")
	{
		$editcmpadid=$mysqli->real_escape_string(@trim($_POST['editcmpadid']));
		$stmtskca = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$editcmpadid.'" and company_id="'.$cid.'"  LIMIT 1');

//('SELECT id FROM user WHERE id="'.$eaid.'"  LIMIT 1');

		if($stmtskca){
			$stmtskca->execute();
			$stmtskca->store_result();
			if ($stmtskca->num_rows != 0)
			{
				$sub_query[]='company_admin="'.$editcmpadid.'"';
				$new_value['company_admin']=$editcmpadid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company Admin doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company Admin required.'));
		exit();			
	}
	
//UBM support	
	
	if(isset($_POST['editubmid']) and @trim($_POST['editubmid']) != "")
	{
		$editubmid=$mysqli->real_escape_string(@trim($_POST['editubmid']));
		$stmtskus = $mysqli->prepare('SELECT user_id FROM user WHERE user_id="'.$editubmid.'"  LIMIT 1');

		if($stmtskus){
			$stmtskus->execute();
			$stmtskus->store_result();
			if ($stmtskus->num_rows != 0)
			{
				$sub_query[]='ubm_support="'.$editubmid.'"';
				$new_value['ubm_support']=$editubmid;
			}else{
				echo json_encode(array('error'=>'Error Occured! UBM Support doesnot exist.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! UBM Support required.'));
		exit();			
	}


	if(count($sub_query)){
		//$sub_query[]='company_id="'.$cid.'"';
		$new_value['company_id']=$cid;
		//audit_log($mysqli,"company","UPDATE",$new_value,'WHERE id='.$tmp_cpy,,);		
		$sql='UPDATE company SET energy_advocate="'.$eaid.'",date_added="'.$mysqli->real_escape_string(@date("Y-m-d H:i:s")).'",added_by="'.$user_one.'",company_admin="'.$editcmpadid.'",ubm_support="'.$editubmid.'" WHERE company_id="'.$cid.'"';
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
}
//Edit Energy Advocate Ends

//Delete Energy Advocate
if(isset($_POST["adid"]) and @trim($_POST["adid"]) != "" and @trim($_POST["adid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=$fnames=array();
	$sacid=$companyname="";
	
	$adid=$mysqli->real_escape_string(@trim($_POST["adid"]));
	
	$stmtsk = $mysqli->prepare('SELECT company_id FROM company where company_id="'.$adid.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{			
			//audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',($fileok==1?"NEW":""),($efile==1?"EXIST":""));
			$stmtskks = $mysqli->prepare('update company set energy_advocate= NULL,company_admin= NULL,ubm_support= NULL,date_added=NULL,added_by="'.$user_one.'" where company_id="'.$adid.'" LIMIT 1');
			if($stmtskks)
			{
				$stmtskks->execute();
				$lastaffectedID=$stmtskks->affected_rows;

				echo json_encode(array("error"=>""));
				exit();				
			}else{
				echo json_encode(array("error"=>$error));			
				exit();			
			}				
		}else{
			echo json_encode(array('error'=>'Error Occured! This Data doesn\'t exists.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}
}
//Delete Energy Advocate ends



//print_r($_POST);
echo false;
exit();
?>