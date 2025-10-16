<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if(!isset($_SESSION["group_id"]) or !isset($_SESSION['user_id']) or ($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2))
	die();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
 
$validextensions = array("jpeg", "jpg", "png","pdf","txt","doc","docx","xls","xlsx","ppt","pptx","gif","mpeg","mp3","avi");

//Add New Saving Analysis
//isset($_POST["cid"]) and @trim($_POST["cid"]) != 0 and @trim($_POST["cid"]) != "" and isset($_POST["location"]) and @trim($_POST["location"]) != "" and isset($_POST["category"]) and @trim($_POST["category"]) != "" and isset($_POST["commodity"]) and @trim($_POST["commodity"]) != "" and isset($_POST["startdate"]) and @trim($_POST["startdate"]) != "" and isset($_POST["enddate"]) and @trim($_POST["enddate"]) != "" and isset($_POST["link"]) and @trim($_POST["link"]) != "" and isset($_POST["saving"]) and @trim($_POST["saving"]) != "" and 
if(isset($_POST["new"]) and $_POST["new"]=="new")
{
 
	$error="Error occured";
	$sub_query=$new_value=$fname=array();
	$fileok=0;
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

	if(isset($_POST['location']) and @trim($_POST['location']) != "")
	{
		$location=$mysqli->real_escape_string(@trim($_POST['location']));
		$sub_query[]='location="'.$location.'"';
		$new_value['location']=$location;		
	}else{
		echo json_encode(array('error'=>'Error Occured! Location required.'));
		exit();		
	}

	if(isset($_POST['category']) and @trim($_POST['category']) != "")
	{
		$sub_query[]='category="'.$mysqli->real_escape_string(@trim($_POST['category'])).'"';
		$new_value['category']=$mysqli->real_escape_string(@trim($_POST['category']));
	}

	if(isset($_POST['skype']) and @trim($_POST['skype']) != "")
	{
		$sub_query[]='skype="'.$mysqli->real_escape_string(@trim($_POST['skype'])).'"';
		$new_value['skype']=$mysqli->real_escape_string(@trim($_POST['skype']));
	}
	
	if(isset($_POST['commodity']) and @trim($_POST['commodity']) != "")
	{
		$sub_query[]='commodity="'.$mysqli->real_escape_string(@trim($_POST['commodity'])).'"';
		$new_value['commodity']=$mysqli->real_escape_string(@trim($_POST['commodity']));
	}

	if(isset($_POST['startdate']) and @trim($_POST['startdate']) != "")
	{
		$sub_query[]='start="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate'])))).'"';
		$new_value['start']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate']))));
	}
	
	if(isset($_POST['enddate']) and @trim($_POST['enddate']) != "")
	{
		$sub_query[]='end="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate'])))).'"';
		$new_value['end']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate']))));
	}

	if(isset($_POST['saving']) and @trim($_POST['saving']) != "")
	{
		$sub_query[]='saving="'.$mysqli->real_escape_string(@trim($_POST['saving'])).'"';
		$new_value['saving']=$mysqli->real_escape_string(@trim($_POST['saving']));
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
					$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/saving analysis";
					
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
		$sql='INSERT INTO saving_analysis SET '.implode(",",$sub_query);
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
//Add Saving Analysis Ends


//Edit Saving Analysis
//isset($_POST["said"]) and @trim($_POST["said"]) != 0 and @trim($_POST["said"]) != "" and isset($_POST["cid"]) and @trim($_POST["cid"]) != 0 and @trim($_POST["cid"]) != "" and isset($_POST["location"]) and @trim($_POST["location"]) != "" and @trim($_POST["location"]) != 0 and isset($_POST["category"]) and @trim($_POST["category"]) != "" and @trim($_POST["category"]) != 0 and isset($_POST["commodity"]) and @trim($_POST["commodity"]) != "" and @trim($_POST["commodity"]) != 0 and isset($_POST["startdate"]) and @trim($_POST["startdate"]) != "" and isset($_POST["enddate"]) and @trim($_POST["enddate"]) != "" and isset($_POST["link"]) and @trim($_POST["link"]) != "" and isset($_POST["saving"]) and @trim($_POST["saving"]) != "" and 
if(isset($_POST["edit"]) and $_POST["edit"]=="edit")
{

	$error="Error occured";
	$sub_query=$new_value=$fname=array();
	$fileok=0;
	$companyname="";

	if(isset($_POST['said']) and @trim($_POST['said']) != "")
	{
		$tmp_said=$mysqli->real_escape_string(@trim($_POST['said']));
		if ($stmtkks = $mysqli->prepare("SELECT id FROM saving_analysis WHERE id='".$tmp_said."'")) { 
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

	if(isset($_POST['location']) and @trim($_POST['location']) != "" and @trim($_POST['location']) != 0)
	{
		$location=$mysqli->real_escape_string(@trim($_POST['location']));
		$sub_query[]='location="'.$location.'"';
		$new_value['location']=$location;		
	}else{
		echo json_encode(array('error'=>'Error Occured! Location required.'));
		exit();		
	}

	if(isset($_POST['category']) and @trim($_POST['category']) != "" and @trim($_POST['category']) != 0)
	{
		$sub_query[]='category="'.$mysqli->real_escape_string(@trim($_POST['category'])).'"';
		$new_value['category']=$mysqli->real_escape_string(@trim($_POST['category']));
	}
	
	if(isset($_POST['commodity']) and @trim($_POST['commodity']) != "" and @trim($_POST['commodity']) != 0)
	{
		$sub_query[]='commodity="'.$mysqli->real_escape_string(@trim($_POST['commodity'])).'"';
		$new_value['commodity']=$mysqli->real_escape_string(@trim($_POST['commodity']));
	}

	if(isset($_POST['startdate']) and @trim($_POST['startdate']) != "")
	{
		$sub_query[]='start="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate'])))).'"';
		$new_value['start']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['startdate']))));
	}
	
	if(isset($_POST['enddate']) and @trim($_POST['enddate']) != "")
	{
		$sub_query[]='end="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate'])))).'"';
		$new_value['end']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['enddate']))));
	}

	if(isset($_POST['saving']) and @trim($_POST['saving']) != "")
	{
		$sub_query[]='saving="'.$mysqli->real_escape_string(@trim($_POST['saving'])).'"';
		$new_value['saving']=$mysqli->real_escape_string(@trim($_POST['saving']));
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
					$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/saving analysis";
					
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
		$sql='UPDATE saving_analysis SET '.implode(",",$sub_query).' WHERE id="'.$tmp_said.'"';
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
//Edit Saving Analysis Ends





//Delete Saving Analysis
if(isset($_POST["said"]) and @trim($_POST["said"]) != "" and @trim($_POST["said"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=$fnames=array();
	$sacid=$companyname="";
	
	$said=$mysqli->real_escape_string(@trim($_POST["said"]));
	
	$stmtsk = $mysqli->prepare('SELECT id,company_id,link FROM saving_analysis where id="'.$said.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtsk->bind_result($sa_Id,$sa_cid,$sa_Link);
			$stmtsk->fetch();
			$fnames[]=@explode("@@;@@",$sa_Link);
			$sacid=$sa_cid;		
				
			//audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',($fileok==1?"NEW":""),($efile==1?"EXIST":""));
			$stmtskks = $mysqli->prepare('DELETE FROM saving_analysis where id="'.$said.'" LIMIT 1');
			if($stmtskks){
				$stmtskks->execute();
				$lastcaffectedID=$stmtskks->affected_rows;
				if($lastcaffectedID==1)
				{
					echo json_encode(array('error'=>''));
					
					if($sacid != "" and $sacid != 0)
					{
						$stmtsks = $mysqli->prepare('SELECT company_name FROM company WHERE company_id="'.$sacid.'"  LIMIT 1');
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
									$targetPath=realpath(dirname(__FILE__))."/../../uploads/resources/Clients/".strip_tags($companyname)."/saving analysis";					
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
//Delete company ends



//print_r($_POST);
echo false;
exit();
?>