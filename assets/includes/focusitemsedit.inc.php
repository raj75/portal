<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if(!isset($_SESSION["group_id"]) or !isset($_SESSION['user_id']) or ($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2))
	die();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

$validextensions = array("jpeg", "jpg", "png","pdf","txt","doc","docx","xls","xlsx","ppt","pptx","gif","mpeg","mp3","avi");

//Add New Focus Items

//isset($_POST["cid"]) and @trim($_POST["cid"]) != 0 and isset($_POST["category"]) and @trim($_POST["category"]) != "" and @trim($_POST["category"]) != 0 and isset($_POST["description"]) and @trim($_POST["description"]) != "" and isset($_POST["read"]) and @trim($_POST["read"]) != "" and (@trim($_POST["read"]) == "Y" or @trim($_POST["read"]) == "N") and isset($_POST["dateadded"]) and @trim($_POST["dateadded"]) != "" and 


if(isset($_POST["new"]) and $_POST["new"]=="new")
{
	$error="Error occured";
	$sub_query=$new_value=$fname=array();
	$fileok=0;
	$companyname="";
	
	if(isset($_POST['cid']) and @trim($_POST['cid']) != "" and @trim($_POST['cid']) != 0)
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
				
				$sub_query[]='company_id="'.$cid.'"';
				$new_value['company_id']=$cid;
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

	if(isset($_POST['category']) and @trim($_POST['category']) != "" and @trim($_POST['category']) != 0)
	{
		$category=$mysqli->real_escape_string(@trim($_POST['category']));
		$sub_query[]='category="'.$category.'"';
		$new_value['category']=$category;		
	}else{
		echo json_encode(array('error'=>'Error Occured! Category required.'));
		exit();		
	}

	if(isset($_POST['description']) and @trim($_POST['description']) != "")
	{
		$sub_query[]='description="'.$mysqli->real_escape_string(@trim($_POST['description'])).'"';
		$new_value['description']=$mysqli->real_escape_string(@trim($_POST['description']));
	}

	if(isset($_POST['read']) and @trim($_POST['read']) != "" and (@trim($_POST['read']) == "Y" or @trim($_POST['read']) == "N"))
	{
		$sub_query[]='_read="'.$mysqli->real_escape_string(@trim($_POST['read'])).'"';
		$new_value['_read']=$mysqli->real_escape_string(@trim($_POST['read']));
	}
	
	if(isset($_POST['dateadded']) and @trim($_POST['dateadded']) != "")
	{
		$sub_query[]='date_added="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded'])))).'"';
		$new_value['date_added']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded']))));
	}

	//File Edit
	
	for($i=1;$i<7;$i++)
	{
		if(isset($_FILES["file".$i]["type"]))
		{
			$temporary = explode(".", $_FILES["file".$i]["name"]);
			$file_extension = end($temporary);
			if ($_FILES["file".$i]["size"] > 0 && in_array($file_extension, $validextensions))
			{//Approx. 100kb files can be uploaded  i.e. 100000.
				if ($_FILES["file".$i]["error"] > 0)
				{
					$error=$_FILES["file".$i]["error"];
					echo json_encode(array("error"=>$error));
					exit();
				}
				else
				{
					$sourcePath = $_FILES['file'.$i]['tmp_name'];
					$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/focus items";
					
					if(!file_exists($targetPath.'/'))
						mkdir($targetPath.'/',0777);

					$fnsm=microtime().mt_rand(1000,10000).".".end($temporary);

					if(move_uploaded_file($sourcePath,$targetPath."/".$fnsm)){
						$fname[]=$fnsm;
					}	
				}
			}
			/*else
			{
				$error="Invalid file Size or Type";
				echo json_encode(array("error"=>$error));
				exit();
			}*/
		}
	}

	if(count($fname)){
		$sub_query[]='link="'.$mysqli->real_escape_string(@trim(implode("@@;@@",$fname))).'"';
		$new_value['link']=$mysqli->real_escape_string(@trim(implode("@@;@@",$fname)));
	}
	//File Edit Ends
	
	
	
	if(count($sub_query)){

		//audit_log($mysqli,"company","INSERT",$new_value,"",($fileok==1?"New":""),"");
		$sql='INSERT INTO focus_items SET '.implode(",",$sub_query);
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
//Add Focus Items Ends


//Edit Focus Items

//isset($_POST["said"]) and @trim($_POST["said"]) != 0 and @trim($_POST["said"]) != "" and isset($_POST["uid"]) and @trim($_POST["uid"]) != 0 and @trim($_POST["uid"]) != "" and isset($_POST["location"]) and @trim($_POST["location"]) != "" and isset($_POST["category"]) and @trim($_POST["category"]) != "" and isset($_POST["commodity"]) and @trim($_POST["commodity"]) != "" and isset($_POST["startdate"]) and @trim($_POST["startdate"]) != "" and isset($_POST["enddate"]) and @trim($_POST["enddate"]) != "" and isset($_POST["link"]) and @trim($_POST["link"]) != "" and isset($_POST["saving"]) and @trim($_POST["saving"]) != "" and 


if(isset($_POST["edit"]) and $_POST["edit"]=="edit")
{
	$error="Error occured";
	$sub_query=$new_value=$fname=array();
	$fileok=0;
	$companyname="";

	if(isset($_POST['fiid']) and @trim($_POST['fiid']) != "")
	{
		$tmp_fiid=$mysqli->real_escape_string(@trim($_POST['fiid']));
		if ($stmtkks = $mysqli->prepare("SELECT id FROM focus_items WHERE id='".$tmp_fiid."'")) { 
			$stmtkks->execute();
			$stmtkks->store_result();
			if ($stmtkks->num_rows == 0) {
				echo json_encode(array('error'=>'Error Occured! Data doesn\'t exists.'));
				exit();			
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();	
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Please try after sometime.'));
		exit();		
	}

	
	if(isset($_POST['cid']) and @trim($_POST['cid']) != "" and @trim($_POST['cid']) != 0)
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
				
				$sub_query[]='company_id="'.$cid.'"';
				$new_value['company_id']=$cid;
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

	if(isset($_POST['category']) and @trim($_POST['category']) != "" and @trim($_POST['category']) != 0)
	{
		$category=$mysqli->real_escape_string(@trim($_POST['category']));
		$sub_query[]='category="'.$category.'"';
		$new_value['category']=$category;		
	}else{
		echo json_encode(array('error'=>'Error Occured! Category required.'));
		exit();		
	}

	if(isset($_POST['description']) and @trim($_POST['description']) != "")
	{
		$sub_query[]='description="'.$mysqli->real_escape_string(@trim($_POST['description'])).'"';
		$new_value['description']=$mysqli->real_escape_string(@trim($_POST['description']));
	}

	if(isset($_POST['read']) and @trim($_POST['read']) != "" and (@trim($_POST['read']) == "Y" or @trim($_POST['read']) == "N"))
	{
		$sub_query[]='_read="'.$mysqli->real_escape_string(@trim($_POST['read'])).'"';
		$new_value['_read']=$mysqli->real_escape_string(@trim($_POST['read']));
	}
	
	if(isset($_POST['dateadded']) and @trim($_POST['dateadded']) != "")
	{
		$sub_query[]='date_added="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded'])))).'"';
		$new_value['date_added']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['dateadded']))));
	}

	
	//File Edit
	
	for($i=1;$i<7;$i++)
	{
		if(isset($_FILES["file".$i]["type"]))
		{
			$temporary = explode(".", $_FILES["file".$i]["name"]);
			$file_extension = end($temporary);
			if ($_FILES["file".$i]["size"] > 0 && in_array($file_extension, $validextensions))
			{//Approx. 100kb files can be uploaded  i.e. 100000.
				if ($_FILES["file".$i]["error"] > 0)
				{
					$error=$_FILES["file".$i]["error"];
					echo json_encode(array("error"=>$error));
					exit();
				}
				else
				{
					$sourcePath = $_FILES['file'.$i]['tmp_name'];
					$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/focus items";
					
					if(!file_exists($targetPath.'/'))
						mkdir($targetPath.'/',0777);

					$fnsm=microtime().mt_rand(1000,10000).".".end($temporary);

					if(move_uploaded_file($sourcePath,$targetPath."/".$fnsm)){
						$fname[]=$fnsm;
					}	
				}
			}
			/*else
			{
				$error="Invalid file Size or Type";
				echo json_encode(array("error"=>$error));
				exit();
			}*/
		}
	}
	//File Edit Ends

	for($i=1;$i<7;$i++)
	{
		if(isset($_POST['fuploaded'.$i]) and @trim($_POST['fuploaded'.$i]) != "")
		{
			$fname[]=@trim($_POST['fuploaded'.$i]);
		}
	}
	
	if(count($fname)){
		$sub_query[]='link="'.$mysqli->real_escape_string(@trim(implode("@@;@@",$fname))).'"';
		$new_value['link']=$mysqli->real_escape_string(@trim(implode("@@;@@",$fname)));
	}else{
		$sub_query[]='link=""';
		$new_value['link']="";
	}
	
	
	
	
	if(count($sub_query)){
		//audit_log($mysqli,"company","UPDATE",$new_value,'WHERE id='.$tmp_cpy,($fileok==1?"NEW":""),($efile==1?"EXIST":""));		
		$sql='UPDATE focus_items SET '.implode(",",$sub_query).' WHERE id="'.$tmp_fiid.'"';
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
//Edit Focus Items Ends





//Delete Focus Items
if(isset($_POST["fiid"]) and @trim($_POST["fiid"]) != "" and @trim($_POST["fiid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=$fnames=array();
	$ficid=$companyname="";
	
	$fiid=$mysqli->real_escape_string(@trim($_POST["fiid"]));
	
	$stmtsk = $mysqli->prepare('SELECT id,company_id,link FROM focus_items where id="'.$fiid.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtsk->bind_result($fi_Id,$fi_cid,$fi_Link);
			$stmtsk->fetch();
			$fnames[]=@explode("@@;@@",$fi_Link);
			$ficid=$fi_cid;
			
			//audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',($fileok==1?"NEW":""),($efile==1?"EXIST":""));
			$stmtskks = $mysqli->prepare('DELETE FROM focus_items where id="'.$fiid.'" LIMIT 1');
			if($stmtskks){
				$stmtskks->execute();
				$lastcaffectedID=$stmtskks->affected_rows;
				if($lastcaffectedID==1)
				{
					echo json_encode(array('error'=>''));

					if($ficid != "" and $ficid != 0)
					{
						$stmtsks = $mysqli->prepare('SELECT company_name FROM company WHERE company_id="'.$ficid.'"  LIMIT 1');

//('SELECT company_name FROM company WHERE id="'.$ficid.'"  LIMIT 1');

						if($stmtsks){
							$stmtsks->execute();
							$stmtsks->store_result();
							if ($stmtsks->num_rows != 0)
							{
								$stmtsks->bind_result($__cname);
								$stmtsks->fetch();
								$companyname=$__cname;

								if($companyname != "" and $companyname != 0)
								{								
									$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/focus items";					
									for($i=0;$i<count($fnames);$i++)
									{					
										if(file_exists($targetPath.'/'.$fnames[$i]))
										{
											@unlink($targetPath.'/'.$fnames[$i]);
										}
									}
								}
							}
						}
					}
					
					exit();													
				}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();						
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
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
//Delete Focus Items ends


//print_r($_POST);
echo false;
exit();
?>