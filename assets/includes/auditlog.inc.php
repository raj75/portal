<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

if(!isset($group_id) or ($group_id != 1 and $_SESSION["group_id"] != 2)){echo false;exit();}


//RollBack
if(isset($_POST["auid"]) and @trim($_POST["auid"]) != "" and @trim($_POST["auid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"]) == "rollback" and isset($_POST["rtype"]))
{
	$error="Error occured";
	$sub_query=$new_value=$newvalue=$finfo=$editedvalarr=array();
	
	$tmp_auid=$mysqli->real_escape_string(@trim($_POST["auid"]));

	$stmtsk = $mysqli->prepare('SELECT id, table_name, table_row_id,pk_name, edited_value, activity FROM audit_log where id="'.$tmp_auid.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$a_Editedvalue=$a_Activity="";
			$stmtsk->bind_result($id,$Tablename,$Tablerowid,$Primarykeyname,$EditedValue,$Activity);
			$stmtsk->fetch();

			$a_Editedvalue=$EditedValue;
			$a_Activity=$Activity;
			if($Primarykeyname=="") $Primarykeyname='id';

			$stmtsk1 = $mysqli->query('SELECT * FROM `'.$Tablename.'` where `'.$Primarykeyname.'`="'.$Tablerowid.'" LIMIT 1');
			if($stmtsk1){
				if ($stmtsk1->num_rows != 0)
				{
					$finfo = $stmtsk1->fetch_fields();
					foreach ($finfo as $val) {
						$newvalue[$val->name]=$val->name;
					}
					
					if(!isset($a_Editedvalue) or $a_Editedvalue ==""){
						echo json_encode(array('error'=>'Error Occured! Edited Value not found in table.'));
						exit();						
					}
					
					$editedvalarr=unserialize(base64_decode($a_Editedvalue));
					$z=count($editedvalarr);
					for($i=0;$i<$z;$i++)
					{
						if(isset($editedvalarr[$i]["title"])){
							if(array_key_exists($editedvalarr[$i]["title"], $newvalue) == false)
							{
								echo json_encode(array('error'=>'Error Occured! Field not found in table.'));
								exit();								
							}
							//$oldval[$editedvalarr[$i]["title"]]=$editedvalarr[$i]["old"];
							$oldtitle=$editedvalarr[$i]["title"];
							$oldval=$editedvalarr[$i]["old"];
							$sub_query[]='`'.$oldtitle.'`="'.$oldval.'"';
							$oldtitle="";
							$oldval="";
						}
						if(isset($editedvalarr[$i]["file"])){
							//$oldval[$editedvalarr[$i]["file"]]=$editedvalarr[$i]["old"];
						}
					}
				
					if(count($newvalue) == 0 or count($sub_query) == 0){
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();					
					}

					$sql='UPDATE `'.$Tablename.'` SET '.implode(",",$sub_query).' WHERE `'.$Primarykeyname.'`="'.$Tablerowid.'"';
					$stmt_up = $mysqli->prepare($sql);
					if($stmt_up)
					{
						$stmt_up->execute();
						$lastaffectedID=$stmt_up->affected_rows;

						$user_session=session_id();
						$ipaddress = $_SERVER['REMOTE_ADDR'];
						
						$sql_status='INSERT INTO audit_log SET status = 0, table_name="'.$Tablename.'", table_row_id ="'.$Tablerowid.'",pk_name="'.$Primarykeyname.'", edited_value="'.$EditedValue.'",activity="'.$Activity.'",user_id="'.$user_one.'",ip_address="'.$ipaddress.'",session_id="'.$user_session.'"';
						$stmt_status = $mysqli->prepare($sql_status);
						if($stmt_status)
						{
							$stmt_status->execute();
							if($stmt_status->affected_rows){						
								echo json_encode(array("error"=>""));
								exit();
							}else{
								echo json_encode(array("error"=>$error));			
								exit();						
							}
						}else{
							echo json_encode(array("error"=>$error));			
							exit();			
						}										
					}else{
						echo json_encode(array("error"=>$error));			
						exit();			
					}					
				}else{
					echo json_encode(array('error'=>'Error Occured! Row ID \''.$id.'\' of table name \''.$Tablename.'\' doesn\'t exist.'));
					exit();				
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
			}




















			
			/*$user_session=session_id();
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$sql_status='INSERT INTO audit_log SET status = 0, table_name="'.$Tablename.'", table_row_id ="'.$Tablerowid.'", edited_value="'.$EditedValue.'",activity="'.$Activity.'",user_id="'.$user_one.'",ip_address="'.$ipaddress.'",session_id="'.$user_session.'"';
			$stmt_status = $mysqli->prepare($sql_status);
			if($stmt_status)
			{
				$stmt_status->execute();
				if($stmt_status->affected_rows){						
					echo json_encode(array("error"=>""));
					exit();
				}else{
					echo json_encode(array("error"=>$error));			
					exit();						
				}
			}else{
				echo json_encode(array("error"=>$error));			
				exit();			
			}	*/	
		}else{
			echo json_encode(array('error'=>'Error Occured! Audit ID doesn\'t exist.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}	

}

//RollBack Old
if(isset($_POST["auid"]) and @trim($_POST["auid"]) != "" and @trim($_POST["auid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"]) == "rollback" and !isset($_POST["rtype"]))
{
	$error="Error occured";
	$sub_query=$new_value=$finfo=$editedvalarr=array();
	
	$tmp_auid=$mysqli->real_escape_string(@trim($_POST["auid"]));

	$stmtsk = $mysqli->prepare('SELECT id, table_name, table_row_id, edited_value, activity FROM audit_log where id="'.$tmp_auid.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$a_Editedvalue=$a_Activity="";
			$stmtsk->bind_result($id,$Tablename,$Tablerowid,$EditedValue,$Activity);
			$stmtsk->fetch();
			$a_Editedvalue=$EditedValue;
			$a_Activity=$Activity;
			$stmtsk1 = $mysqli->query('SELECT * FROM `'.$Tablename.'` where id="'.$Tablerowid.'" LIMIT 1');
			if($stmtsk1){
				if ($stmtsk1->num_rows != 0)
				{
					$finfo = $stmtsk1->fetch_fields();
					foreach ($finfo as $val) {
						$newvalue[$val->name]=$val->name;
					}
					
					if(!isset($a_Editedvalue) or $a_Editedvalue ==""){
						echo json_encode(array('error'=>'Error Occured! Edited Value not found in table.'));
						exit();						
					}
					
					$editedvalarr=unserialize(base64_decode($a_Editedvalue));
					$z=count($editedvalarr);
					for($i=0;$i<$z;$i++)
					{
						if(isset($editedvalarr[$i]["title"])){
							if(array_key_exists($editedvalarr[$i]["title"], $newvalue) == false)
							{
								echo json_encode(array('error'=>'Error Occured! Field not found in table.'));
								exit();								
							}
							//$oldval[$editedvalarr[$i]["title"]]=$editedvalarr[$i]["old"];
							$oldtitle=$editedvalarr[$i]["title"];
							$oldval=$editedvalarr[$i]["old"];
							$sub_query[]='`'.$oldtitle.'`="'.$oldval.'"';
							$oldtitle="";
							$oldval="";
						}
						if(isset($editedvalarr[$i]["file"])){
							//$oldval[$editedvalarr[$i]["file"]]=$editedvalarr[$i]["old"];
						}
					}
				
					if(count($newvalue) == 0 or count($sub_query) == 0){
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();					
					}

					$sql='UPDATE `'.$Tablename.'` SET '.implode(",",$sub_query).' WHERE id="'.$Tablerowid.'"';
					$stmt_up = $mysqli->prepare($sql);
					if($stmt_up)
					{
						$stmt_up->execute();
						$lastaffectedID=$stmt_up->affected_rows;

						$sql_status='UPDATE audit_log SET status = CASE id 
								WHEN '.$tmp_auid.' THEN 1 ELSE 0 
								END 
								WHERE table_name="'.$Tablename.'" and table_row_id ="'.$Tablerowid.'"';
						$stmt_status = $mysqli->prepare($sql_status);
						if($stmt_status)
						{
							$stmt_status->execute();
							$lastaffectedID_status=$stmt_status->affected_rows;						
							echo json_encode(array("error"=>""));
							exit();				
						}else{
							echo json_encode(array("error"=>$error));			
							exit();			
						}										
					}else{
						echo json_encode(array("error"=>$error));			
						exit();			
					}					
				}else{
					echo json_encode(array('error'=>'Error Occured! Row ID \''.$id.'\' of table name \''.$Tablename.'\' doesn\'t exist.'));
					exit();				
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
			}			
		}else{
			echo json_encode(array('error'=>'Error Occured! Audit ID doesn\'t exist.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}	

}


//Forward
if(isset($_POST["auid"]) and @trim($_POST["auid"]) != "" and @trim($_POST["auid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"]) == "forward")
{
	$error="Error occured";
	$sub_query=$new_value=$finfo=$editedvalarr=array();
	
	$tmp_auid=$mysqli->real_escape_string(@trim($_POST["auid"]));

	$stmtsk = $mysqli->prepare('SELECT id, table_name, table_row_id, edited_value, activity FROM audit_log where id="'.$tmp_auid.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$a_Editedvalue=$a_Activity="";
			$stmtsk->bind_result($id,$Tablename,$Tablerowid,$EditedValue,$Activity);
			$stmtsk->fetch();
			$a_Editedvalue=$EditedValue;
			$a_Activity=$Activity;
			$stmtsk1 = $mysqli->query('SELECT * FROM `'.$Tablename.'` where id="'.$Tablerowid.'" LIMIT 1');
			if($stmtsk1){
				if ($stmtsk1->num_rows != 0)
				{
					$finfo = $stmtsk1->fetch_fields();
					foreach ($finfo as $val) {
						$newvalue[$val->name]=$val->name;
					}
					
					if(!isset($a_Editedvalue) or $a_Editedvalue ==""){
						echo json_encode(array('error'=>'Error Occured! Edited Value not found in table.'));
						exit();						
					}
					
					$editedvalarr=unserialize(base64_decode($a_Editedvalue));
					$z=count($editedvalarr);
					for($i=0;$i<$z;$i++)
					{
						if(isset($editedvalarr[$i]["title"])){
							if(array_key_exists($editedvalarr[$i]["title"], $newvalue) == false)
							{
								echo json_encode(array('error'=>'Error Occured! Field not found in table.'));
								exit();								
							}
							//$oldval[$editedvalarr[$i]["title"]]=$editedvalarr[$i]["new"];
							$oldtitle=$editedvalarr[$i]["title"];
							$oldval=$editedvalarr[$i]["new"];
							$sub_query[]='`'.$oldtitle.'`="'.$oldval.'"';
							$oldtitle="";
							$oldval="";
						}
						if(isset($editedvalarr[$i]["file"])){
							//$oldval[$editedvalarr[$i]["file"]]=$editedvalarr[$i]["new"];
						}
					}
				
					if(count($newvalue) == 0 or count($sub_query) == 0){
						echo json_encode(array('error'=>'Error Occured! Database error.'));
						exit();					
					}

					$sql='UPDATE `'.$Tablename.'` SET '.implode(",",$sub_query).' WHERE id="'.$Tablerowid.'"';
					$stmt_up = $mysqli->prepare($sql);
					if($stmt_up)
					{
						$stmt_up->execute();
						$lastaffectedID=$stmt_up->affected_rows;

						$sql_status='UPDATE audit_log SET status = CASE id 
								WHEN '.$tmp_auid.' THEN 2 ELSE 0 
								END 
								WHERE table_name="'.$Tablename.'" and table_row_id ="'.$Tablerowid.'"';
						$stmt_status = $mysqli->prepare($sql_status);
						if($stmt_status)
						{
							$stmt_status->execute();
							$lastaffectedID_status=$stmt_status->affected_rows;						
							echo json_encode(array("error"=>""));
							exit();				
						}else{
							echo json_encode(array("error"=>$error));			
							exit();			
						}				
					}else{
						echo json_encode(array("error"=>$error));			
						exit();			
					}					
				}else{
					echo json_encode(array('error'=>'Error Occured! Row ID \''.$id.'\' of table name \''.$Tablename.'\' doesn\'t exist.'));
					exit();				
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();			
			}			
		}else{
			echo json_encode(array('error'=>'Error Occured! Audit ID doesn\'t exist.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}	

}




//Add New Company
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_POST["new"]) and $_POST["new"]=="new")
{

	$error="Error occured";
	$sub_query=$new_value=array();
	$fileok=0;
	
	if(isset($_POST['email']) and @trim($_POST['email']) != "")
	{
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Not a valid email
			echo json_encode(array('error'=>'The email address you entered is not valid'));
			exit();
		}else{
			$stmtsk = $mysqli->prepare('SELECT company_id FROM company where email="'.$email.'" LIMIT 1');

//('SELECT id FROM company where email="'.$email.'" LIMIT 1');

			if($stmtsk){
				$stmtsk->execute();
				$stmtsk->store_result();
				if ($stmtsk->num_rows == 0)
				{
					$sub_query[]='email="'.$mysqli->real_escape_string(@trim($_POST['email'])).'"';
					$new_value['email']=$mysqli->real_escape_string(@trim($_POST['email']));
				}else{
					echo json_encode(array('error'=>'Error Occured! Email already exist.'));
					exit();				
				}
			}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();			
			}
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Email required.'));
		exit();			
	}

	if(isset($_POST['cname']) and @trim($_POST['cname']) != "")
	{
		$cname=$mysqli->real_escape_string(@trim($_POST['cname']));
	   if ($stmt = $mysqli->prepare('SELECT company_id FROM `company` where company_name="'.$cname.'" LIMIT 1')) { 

//('SELECT id FROM `company` where company_name="'.$cname.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='company_name="'.$cname.'"';
				$new_value['company_name']=$cname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company name already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Company name required.'));
		exit();		
	}

	if(isset($_POST['phone']) and @trim($_POST['phone']) != "")
	{
		$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone']))).'"';
		$new_value['phone']=$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone'])));
	}

	if(isset($_POST['skype']) and @trim($_POST['skype']) != "")
	{
		$sub_query[]='skype="'.$mysqli->real_escape_string(@trim($_POST['skype'])).'"';
		$new_value['skype']=$mysqli->real_escape_string(@trim($_POST['skype']));
	}

	if(isset($_POST['foundationdate']) and @trim($_POST['foundationdate']) != "")
	{
		$sub_query[]='foundation_date="'.$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate'])))).'"';
		$new_value['foundation_date']=$mysqli->real_escape_string(@trim(@date("Y-m-d",@strtotime($_POST['foundationdate']))));
	}

	if(isset($_POST['title']) and @trim($_POST['title']) != "")
	{
		$sub_query[]='about_company_title="'.$mysqli->real_escape_string(@trim($_POST['title'])).'"';
		$new_value['about_company_title']=$mysqli->real_escape_string(@trim($_POST['title']));
	}

	if(isset($_POST['description']) and @trim($_POST['description']) != "")
	{
		$sub_query[]='about_company_details="'.$mysqli->real_escape_string(@trim($_POST['description'])).'"';
		$new_value['about_company_details']=$mysqli->real_escape_string(@trim($_POST['description']));
	}



		//File Edit

	if(isset($_FILES["file"]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
		) && ($_FILES["file"]["size"] < 100000)//Approx. 100kb files can be uploaded.
		&& in_array($file_extension, $validextensions)) {
			if ($_FILES["file"]["error"] > 0)
			{
				$error=$_FILES["file"]["error"];
				echo json_encode(array("error"=>$error));
				exit();
			}
			else
			{
				$fileok=1;
				/*$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				//$targetPath = "upload/".$__username; // Target path where file is to be stored	

				$targetPath=realpath(dirname(__FILE__))."/../img/company/".strip_tags($cname).$insertid.".png";
				if(file_exists($targetPath)){
				  @unlink($targetPath);
				}

				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file*/
				/*echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
				echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
				echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
				echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";*/
			}
		}
		else
		{
			$error="Invalid file Size or Type";
			echo json_encode(array("error"=>$error));
			exit();
		}
	}

	//File Edit Ends



	if(count($sub_query)){

		audit_log($mysqli,"company","INSERT",$new_value,"",($fileok==1?"New":""),"");
		$sql='INSERT INTO company SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){
				if($fileok==1)
				{
					$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

					$targetPath=realpath(dirname(__FILE__))."/../img/company/".strip_tags($cname).$insertid.".png";
					if(file_exists($targetPath)){
					  @unlink($targetPath);
					}

					@move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file			
				}
				//if($lastaffectedID == 1){

				
					echo json_encode(array("error"=>""));
				//}else
					//echo json_encode(array("error"=>$error));					
					
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
//Add company ends


//Delete Company
if(isset($group_id) and ($group_id == 1 or $_SESSION["group_id"] == 2) and isset($_POST["cpy"]) and @trim($_POST["cpy"]) != "" and @trim($_POST["cpy"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();	
	
	$cpy=$mysqli->real_escape_string(@trim($_POST["cpy"]));
	if($cpy == 1)
	{
		echo json_encode(array('error'=>'Error Occured! Vervantis deletion is not permitted.'));
		exit();
	}
	
	
	$stmtsk = $mysqli->prepare('SELECT company_id FROM company where id="'.$cpy.'" LIMIT 1');

//('SELECT id FROM company where id="'.$cpy.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtskk = $mysqli->prepare('SELECT user_id FROM user where company_id="'.$cpy.'" LIMIT 1');

//('SELECT id FROM user where company_id="'.$cpy.'" LIMIT 1');

			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					echo json_encode(array('error'=>'Error Occured!  delete the users of this company.'));
					exit();
				}else{
					audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',($fileok==1?"NEW":""),($efile==1?"EXIST":""));
					$stmtskks = $mysqli->prepare('DELETE FROM company where company_id="'.$cpy.'" LIMIT 1');
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
			echo json_encode(array('error'=>'Error Occured! Company doesn\'t exists.'));
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