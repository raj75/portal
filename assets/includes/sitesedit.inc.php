<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

//Restrict Other than Admin and Employee
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
}else{
	echo false;
	exit();
}

if(isset($_POST["cpy"]) and @trim($_POST["cpy"]) != "" and @trim($_POST["cpy"]) != 0 and isset($_POST["edit"]))
{

	$error="Error occured";
	$sub_query=$new_value=array();
	
	$tmp_cpy=$mysqli->real_escape_string(@trim($_POST["cpy"]));	
	
	if(isset($_POST['email']) and @trim($_POST['email']) != "")
	{
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Not a valid email
			echo json_encode(array('error'=>'The email address you entered is not valid'));
			exit();
		}else{
			$stmtsk = $mysqli->prepare('SELECT company_id FROM company where company_id="'.$tmp_cpy.'" LIMIT 1');

//('SELECT id FROM company where id="'.$tmp_cpy.'" LIMIT 1');

			if($stmtsk){
				$stmtsk->execute();
				$stmtsk->store_result();
				if ($stmtsk->num_rows > 0)
				{
					$sub_query[]='email="'.$mysqli->real_escape_string(@trim($_POST['email'])).'"';
					$new_value['email']=$mysqli->real_escape_string(@trim($_POST['email']));
				}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();				
				}
			}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();			
			}
		}
	}

	
	if(isset($_POST['cname']) and @trim($_POST['cname']) != "")
	{
		$__cname=@trim($_POST['cname']);
		$sub_query[]='company_name="'.$mysqli->real_escape_string($__cname).'"';
		$new_value['company_name']=$mysqli->real_escape_string($__cname);
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

	if(count($sub_query)){
			$fileok=$efile=0;

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
							$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

							$targetPath=realpath(dirname(__FILE__))."/../img/company/".strip_tags($_cname).$tmp_cpy.".png";
							if(file_exists($targetPath)){
							  $efile=1;
							}
							$fileok=1;
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
			
			audit_log($mysqli,"company","UPDATE",$new_value,'WHERE id='.$tmp_cpy,($fileok==1?"NEW":""),($efile==1?"EXIST":""));
			$sql='UPDATE company SET '.implode(",",$sub_query).' WHERE company_id='.$tmp_cpy;
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				$lastaffectedID=$stmt->affected_rows;

				//File Edit

				if(isset($_FILES["file"]["type"]))
				{
					if($efile==1){
					  @unlink($targetPath);
					}
					
					if($fileok==1){
						@move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
					}
				}

				//File Edit Ends
				
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

//Add New User
if(isset($_POST["new"]) and $_POST["new"]=="new")
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
			$stmtsk = $mysqli->prepare('SELECT company_id FROM user where email="'.$email.'" LIMIT 1');

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
if(isset($_POST["cpy"]) and @trim($_POST["cpy"]) != "" and @trim($_POST["cpy"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();	
	
	$cpy=$mysqli->real_escape_string(@trim($_POST["cpy"]));
	if($cpy == 1)
	{
		echo json_encode(array('error'=>'Error Occured! Vervantis deletion is not permitted.'));
		exit();
	}
	
	
	$stmtsk = $mysqli->prepare('SELECT company_id FROM company where company_id="'.$cpy.'" LIMIT 1');

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
					audit_log($mysqli,"company","DELETE","",'WHERE company_id="'.$cpy.'" LIMIT 1',"","");

//($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',"","");

					$stmtskks = $mysqli->prepare('DELETE FROM company where company_id="'.$cpy.'" LIMIT 1');

//('DELETE FROM company where id="'.$cpy.'" LIMIT 1');

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



//Sites
//Add New Sites
if(isset($_POST["snew"]) and $_POST["snew"]=="new")
{

	$error="Error occured";
	$sub_query=$new_value=array();	

	if(isset($_POST['sname']) and @trim($_POST['sname']) != "")
	{
		$sname=$mysqli->real_escape_string(@trim($_POST['sname']));
		$sub_query[]='site_name="'.$sname.'"';
		$new_value['site_name']=$sname;
	}else{
		echo json_encode(array('error'=>'Error Occured! Site name required.'));
		exit();		
	}	


	if(isset($_POST['company']) and @trim($_POST['company']) != "" and @trim($_POST['company']) != 0)
	{
		$cid=$mysqli->real_escape_string(@trim($_POST['company']));
	   if ($stmt = $mysqli->prepare('SELECT company_id FROM `company` where company_id="'.$cid.'" LIMIT 1')) { 

//('SELECT id FROM `company` where id="'.$cid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows != 0) {
				$sub_query[]='company_id="'.$cid.'"';
				$new_value['company_id']=$cid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company name doesn\'t exist.'));
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
	
	
	if(isset($_POST['sitenumber']) and @trim($_POST['sitenumber']) != "")
	{
		$sno=$mysqli->real_escape_string(@trim($_POST['sitenumber']));
	   if ($stmt = $mysqli->prepare('SELECT site_number FROM `sites` where site_number="'.$sno.'" and company_id="'.$cid.'" LIMIT 1')) { 

//('SELECT id FROM `sites` where site_number="'.$sno.'" and company_id="'.$cid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='site_number="'.$sno.'"';
				$new_value['site_number']=$sno;
			}else{
				echo json_encode(array('error'=>'Error Occured! Site Number already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Site number required.'));
		exit();		
	}
	
	if(isset($_POST['division']) and @trim($_POST['division']) != "")
	{
		$division=$mysqli->real_escape_string(@trim($_POST['division']));
		$sub_query[]='division="'.$division.'"';
		$new_value['division']=$division;
	}else{
		echo json_encode(array('error'=>'Error Occured! Division required.'));
		exit();		
	}
	
	if(isset($_POST['activedate']) and @trim($_POST['activedate']) != "")
	{
		$activedate=$mysqli->real_escape_string(@trim($_POST['activedate']));
		$sub_query[]='active_date="'.$activedate.'"';
		$new_value['active_date']=$activedate;
	}
	
	if(isset($_POST['inactivedate']) and @trim($_POST['inactivedate']) != "")
	{
		$inactivedate=$mysqli->real_escape_string(@trim($_POST['inactivedate']));
		$sub_query[]='inactive_date="'.$inactivedate.'"';
		$new_value['inactive_date']=$inactivedate;
	}

	if(isset($_POST['siteadd1']) and @trim($_POST['siteadd1']) != "")
	{
		$siteadd1=$mysqli->real_escape_string(@trim($_POST['siteadd1']));
		$sub_query[]='service_address1="'.$siteadd1.'"';
		$new_value['service_address1']=$siteadd1;
	}else{
		echo json_encode(array('error'=>'Error Occured! Service Address 1 required.'));
		exit();		
	}	
	
	if(isset($_POST['siteadd2']) and @trim($_POST['siteadd2']) != "")
	{
		$siteadd2=$mysqli->real_escape_string(@trim($_POST['siteadd2']));
		$sub_query[]='service_address2="'.$siteadd2.'"';
		$new_value['service_address2']=$siteadd2;
	}

	if(isset($_POST['siteadd3']) and @trim($_POST['siteadd3']) != "")
	{
		$siteadd3=$mysqli->real_escape_string(@trim($_POST['siteadd3']));
		$sub_query[]='service_address3="'.$siteadd3.'"';
		$new_value['service_address3']=$siteadd3;
	}	

	if(isset($_POST['city']) and @trim($_POST['city']) != "")
	{
		$city=$mysqli->real_escape_string(@trim($_POST['city']));
		$sub_query[]='city="'.$city.'"';
		$new_value['city']=$city;
	}else{
		echo json_encode(array('error'=>'Error Occured! City required.'));
		exit();		
	}	
	
	if(isset($_POST['state']) and @trim($_POST['state']) != "")
	{
		$state=$mysqli->real_escape_string(@trim($_POST['state']));
		$sub_query[]='state="'.$state.'"';
		$new_value['state']=$state;
	}else{
		echo json_encode(array('error'=>'Error Occured! State required.'));
		exit();		
	}
	
	if(isset($_POST['zip']) and @trim($_POST['zip']) != "")
	{
		$zip=$mysqli->real_escape_string(@trim($_POST['zip']));
		$sub_query[]='postal_code="'.$zip.'"';
		$new_value['postal_code']=$zip;
	}else{
		echo json_encode(array('error'=>'Error Occured! Postal Code required.'));
		exit();		
	}
	
	if(isset($_POST['country']) and @trim($_POST['country']) != "")
	{
		$country=$mysqli->real_escape_string(@trim($_POST['country']));
		$sub_query[]='country="'.$country.'"';
		$new_value['country']=$country;
	}else{
		echo json_encode(array('error'=>'Error Occured! Country required.'));
		exit();		
	}
	
	if(isset($_POST['sitetype']) and @trim($_POST['sitetype']) != "")
	{
		$sitetype=$mysqli->real_escape_string(@trim($_POST['sitetype']));
		$sub_query[]='site_type="'.$sitetype.'"';
		$new_value['site_type']=$sitetype;
	}

	if(isset($_POST['sqfootage']) and @trim($_POST['sqfootage']) != "")
	{
		$sqfootage=$mysqli->real_escape_string(@trim($_POST['sqfootage']));
		$sub_query[]='square_footage="'.$sqfootage.'"';
		$new_value['square_footage']=$sqfootage;
	}
	
	if(isset($_POST['nofloor']) and @trim($_POST['nofloor']) != "")
	{
		$nofloor=$mysqli->real_escape_string(@trim($_POST['nofloor']));
		$sub_query[]='number_of_floors="'.$nofloor.'"';
		$new_value['number_of_floors']=$nofloor;
	}
	
	if(isset($_POST['nounits']) and @trim($_POST['nounits']) != "")
	{
		$nounits=$mysqli->real_escape_string(@trim($_POST['nounits']));
		$sub_query[]='number_of_units="'.$nounits.'"';
		$new_value['number_of_units']=$nounits;
	}
	
	if(isset($_POST['region']) and @trim($_POST['region']) != "")
	{
		$region=$mysqli->real_escape_string(@trim($_POST['region']));
		$sub_query[]='region="'.$region.'"';
		$new_value['region']=$region;
	}
	
	if(isset($_POST['naics']) and @trim($_POST['naics']) != "")
	{
		$naics=$mysqli->real_escape_string(@trim($_POST['naics']));
		$sub_query[]='naics="'.$naics.'"';
		$new_value['naics']=$naics;
	}
	
	if(isset($_POST['sic']) and @trim($_POST['sic']) != "")
	{
		$sic=$mysqli->real_escape_string(@trim($_POST['sic']));
		$sub_query[]='sic="'.$sic.'"';
		$new_value['sic']=$sic;
	}
	
	if(isset($_POST['managed']) and @trim($_POST['managed']) != "")
	{
		$managed=$mysqli->real_escape_string(@trim($_POST['managed']));
		$sub_query[]='managed="'.$managed.'"';
		$new_value['managed']=$managed;
	}
	
	if(isset($_POST['altname']) and @trim($_POST['altname']) != "")
	{
		$altname=$mysqli->real_escape_string(@trim($_POST['altname']));
		$sub_query[]='alternate_name="'.$altname.'"';
		$new_value['alternate_name']=$altname;
	}
	
	if(isset($_POST['ownership']) and @trim($_POST['ownership']) != "")
	{
		$ownership=$mysqli->real_escape_string(@trim($_POST['ownership']));
		$sub_query[]='ownership="'.$ownership.'"';
		$new_value['ownership']=$ownership;
	}
	
	if(isset($_POST['yearbuilt']) and @trim($_POST['yearbuilt']) != "")
	{
		$yearbuilt=$mysqli->real_escape_string(@trim($_POST['yearbuilt']));
		$sub_query[]='year_built="'.$yearbuilt.'"';
		$new_value['year_built']=$yearbuilt;
	}
	
	if(isset($_POST['noemp']) and @trim($_POST['noemp']) != "")
	{
		$noemp=$mysqli->real_escape_string(@trim($_POST['noemp']));
		$sub_query[]='number_of_employees="'.$noemp.'"';
		$new_value['number_of_employees']=$noemp;
	}
	
	if(isset($_POST['wohours']) and @trim($_POST['wohours']) != "")
	{
		$wohours=$mysqli->real_escape_string(@trim($_POST['wohours']));
		$sub_query[]='weekly_operating_hours="'.$wohours.'"';
		$new_value['weekly_operating_hours']=$wohours;
	}
	
	if(isset($_POST['phone']) and @trim($_POST['phone']) != "")
	{
		$phone=$mysqli->real_escape_string(@trim($_POST['phone']));
		$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone']))).'"';
		$new_value['phone']=$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone'])));
	}else{
		echo json_encode(array('error'=>'Error Occured! Phone required.'));
		exit();		
	}

	if(isset($_POST['hcbp']) and @trim($_POST['hcbp']) != "")
	{
		$hcbp=$mysqli->real_escape_string(@trim($_POST['hcbp']));
		$sub_query[]='hdd_cdd_balance_point="'.$hcbp.'"';
		$new_value['hdd_cdd_balance_point']=$hcbp;
	}

	if(isset($_POST['wban']) and @trim($_POST['wban']) != "")
	{
		$wban=$mysqli->real_escape_string(@trim($_POST['wban']));
		$sub_query[]='wban="'.$wban.'"';
		$new_value['wban']=$wban;
	}

	if(isset($_POST['wstationname']) and @trim($_POST['wstationname']) != "")
	{
		$wstationname=$mysqli->real_escape_string(@trim($_POST['wstationname']));
		$sub_query[]='weather_station_name="'.$wstationname.'"';
		$new_value['weather_station_name']=$wstationname;
	}

	if(isset($_POST['wscity']) and @trim($_POST['wscity']) != "")
	{
		$wscity=$mysqli->real_escape_string(@trim($_POST['wscity']));
		$sub_query[]='weather_station_city="'.$wscity.'"';
		$new_value['weather_station_city']=$wscity;
	}

	if(isset($_POST['sstatus']) and @trim($_POST['sstatus']) != "")
	{
		$sstatus=$mysqli->real_escape_string(@trim($_POST['sstatus']));
		$sub_query[]='site_status="'.$sstatus.'"';
		$new_value['site_status']=$sstatus;
	}
	

	if(count($sub_query)){
		audit_log($mysqli,"sites","INSERT",$new_value,"","","");
		$sql='INSERT INTO sites SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
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
//Add Sites ends

//Edit Sites Starts
if(isset($_POST["sedit"]) and $_POST["sedit"]=="edit")
{

	$error="Error occured";
	$sub_query=$new_value=array();	
	
	if(isset($_POST['sid']) and @trim($_POST['sid']) != "" and @trim($_POST['sid']) != 0)
	{
		$sid=$mysqli->real_escape_string(@trim($_POST['sid']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Please try after sometime.'));
		exit();		
	}

	if(isset($_POST['sname']) and @trim($_POST['sname']) != "")
	{
		$sname=$mysqli->real_escape_string(@trim($_POST['sname']));
		$sub_query[]='site_name="'.$sname.'"';
		$new_value['site_name']=$sname;
	}else{
		echo json_encode(array('error'=>'Error Occured! Site name required.'));
		exit();		
	}


	if(isset($_POST['company']) and @trim($_POST['company']) != "" and @trim($_POST['company']) != 0)
	{
		$cid=$mysqli->real_escape_string(@trim($_POST['company']));
	   if ($stmt = $mysqli->prepare('SELECT company_id FROM `company` where company_id="'.$cid.'" LIMIT 1')) { 

//('SELECT id FROM `company` where id="'.$cid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows != 0) {
				$sub_query[]='company_id="'.$cid.'"';
				$new_value['company_id']=$cid;
			}else{
				echo json_encode(array('error'=>'Error Occured! Company name doesn\'t exist.'));
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
	
	
	if(isset($_POST['sitenumber']) and @trim($_POST['sitenumber']) != "")
	{
		$sno=$mysqli->real_escape_string(@trim($_POST['sitenumber']));
	   if ($stmt = $mysqli->prepare('SELECT site_number FROM `sites` where site_number="'.$sno.'" and company_id="'.$cid.'" and site_number !="'.$sid.'" LIMIT 1')) { 

//('SELECT id FROM `sites` where site_number="'.$sno.'" and company_id="'.$cid.'" and id !="'.$sid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='site_number="'.$sno.'"';
				$new_value['site_number']=$sno;
			}else{
				echo json_encode(array('error'=>'Error Occured! Site Number already exist.'));
				exit();				
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Site number required.'));
		exit();		
	}
	
	if(isset($_POST['division']) and @trim($_POST['division']) != "")
	{
		$division=$mysqli->real_escape_string(@trim($_POST['division']));
		$sub_query[]='division="'.$division.'"';
		$new_value['division']=$division;
	}else{
		echo json_encode(array('error'=>'Error Occured! Division required.'));
		exit();		
	}
	
	if(isset($_POST['activedate']) and @trim($_POST['activedate']) != "")
	{
		$activedate=$mysqli->real_escape_string(@trim($_POST['activedate']));
		$sub_query[]='active_date="'.$activedate.'"';
		$new_value['active_date']=$activedate;
	}
	
	if(isset($_POST['inactivedate']) and @trim($_POST['inactivedate']) != "")
	{
		$inactivedate=$mysqli->real_escape_string(@trim($_POST['inactivedate']));
		$sub_query[]='inactive_date="'.$inactivedate.'"';
		$new_value['inactive_date']=$inactivedate;
	}

	if(isset($_POST['siteadd1']) and @trim($_POST['siteadd1']) != "")
	{
		$siteadd1=$mysqli->real_escape_string(@trim($_POST['siteadd1']));
		$sub_query[]='service_address1="'.$siteadd1.'"';
		$new_value['service_address1']=$siteadd1;
	}else{
		echo json_encode(array('error'=>'Error Occured! Service Address 1 required.'));
		exit();		
	}	
	
	if(isset($_POST['siteadd2']) and @trim($_POST['siteadd2']) != "")
	{
		$siteadd2=$mysqli->real_escape_string(@trim($_POST['siteadd2']));
		$sub_query[]='service_address2="'.$siteadd2.'"';
		$new_value['service_address2']=$siteadd2;
	}

	if(isset($_POST['siteadd3']) and @trim($_POST['siteadd3']) != "")
	{
		$siteadd3=$mysqli->real_escape_string(@trim($_POST['siteadd3']));
		$sub_query[]='service_address3="'.$siteadd3.'"';
		$new_value['service_address3']=$siteadd3;
	}	

	if(isset($_POST['city']) and @trim($_POST['city']) != "")
	{
		$city=$mysqli->real_escape_string(@trim($_POST['city']));
		$sub_query[]='city="'.$city.'"';
		$new_value['city']=$city;
	}else{
		echo json_encode(array('error'=>'Error Occured! City required.'));
		exit();		
	}	
	
	if(isset($_POST['state']) and @trim($_POST['state']) != "")
	{
		$state=$mysqli->real_escape_string(@trim($_POST['state']));
		$sub_query[]='state="'.$state.'"';
		$new_value['state']=$state;
	}else{
		echo json_encode(array('error'=>'Error Occured! State required.'));
		exit();		
	}
	
	if(isset($_POST['zip']) and @trim($_POST['zip']) != "")
	{
		$zip=$mysqli->real_escape_string(@trim($_POST['zip']));
		$sub_query[]='postal_code="'.$zip.'"';
		$new_value['postal_code']=$zip;
	}else{
		echo json_encode(array('error'=>'Error Occured! Postal Code required.'));
		exit();		
	}
	
	if(isset($_POST['country']) and @trim($_POST['country']) != "")
	{
		$country=$mysqli->real_escape_string(@trim($_POST['country']));
		$sub_query[]='country="'.$country.'"';
		$new_value['country']=$country;
	}else{
		echo json_encode(array('error'=>'Error Occured! Country required.'));
		exit();		
	}
	
	if(isset($_POST['sitetype']) and @trim($_POST['sitetype']) != "")
	{
		$sitetype=$mysqli->real_escape_string(@trim($_POST['sitetype']));
		$sub_query[]='site_type="'.$sitetype.'"';
		$new_value['site_type']=$sitetype;
	}

	if(isset($_POST['sqfootage']) and @trim($_POST['sqfootage']) != "")
	{
		$sqfootage=$mysqli->real_escape_string(@trim($_POST['sqfootage']));
		$sub_query[]='square_footage="'.$sqfootage.'"';
		$new_value['square_footage']=$sqfootage;
	}
	
	if(isset($_POST['nofloor']) and @trim($_POST['nofloor']) != "")
	{
		$nofloor=$mysqli->real_escape_string(@trim($_POST['nofloor']));
		$sub_query[]='number_of_floors="'.$nofloor.'"';
		$new_value['number_of_floors']=$nofloor;
	}
	
	if(isset($_POST['nounits']) and @trim($_POST['nounits']) != "")
	{
		$nounits=$mysqli->real_escape_string(@trim($_POST['nounits']));
		$sub_query[]='number_of_units="'.$nounits.'"';
		$new_value['number_of_units']=$nounits;
	}
	
	if(isset($_POST['region']) and @trim($_POST['region']) != "")
	{
		$region=$mysqli->real_escape_string(@trim($_POST['region']));
		$sub_query[]='region="'.$region.'"';
		$new_value['region']=$region;
	}
	
	if(isset($_POST['naics']) and @trim($_POST['naics']) != "")
	{
		$naics=$mysqli->real_escape_string(@trim($_POST['naics']));
		$sub_query[]='naics="'.$naics.'"';
		$new_value['naics']=$naics;
	}
	
	if(isset($_POST['sic']) and @trim($_POST['sic']) != "")
	{
		$sic=$mysqli->real_escape_string(@trim($_POST['sic']));
		$sub_query[]='sic="'.$sic.'"';
		$new_value['sic']=$sic;
	}
	
	if(isset($_POST['managed']) and @trim($_POST['managed']) != "")
	{
		$managed=$mysqli->real_escape_string(@trim($_POST['managed']));
		$sub_query[]='managed="'.$managed.'"';
		$new_value['managed']=$managed;
	}
	
	if(isset($_POST['altname']) and @trim($_POST['altname']) != "")
	{
		$altname=$mysqli->real_escape_string(@trim($_POST['altname']));
		$sub_query[]='alternate_name="'.$altname.'"';
		$new_value['alternate_name']=$altname;
	}
	
	if(isset($_POST['ownership']) and @trim($_POST['ownership']) != "")
	{
		$ownership=$mysqli->real_escape_string(@trim($_POST['ownership']));
		$sub_query[]='ownership="'.$ownership.'"';
		$new_value['ownership']=$ownership;
	}
	
	if(isset($_POST['yearbuilt']) and @trim($_POST['yearbuilt']) != "")
	{
		$yearbuilt=$mysqli->real_escape_string(@trim($_POST['yearbuilt']));
		$sub_query[]='year_built="'.$yearbuilt.'"';
		$new_value['year_built']=$yearbuilt;
	}
	
	if(isset($_POST['noemp']) and @trim($_POST['noemp']) != "")
	{
		$noemp=$mysqli->real_escape_string(@trim($_POST['noemp']));
		$sub_query[]='number_of_employees="'.$noemp.'"';
		$new_value['number_of_employees']=$noemp;
	}
	
	if(isset($_POST['wohours']) and @trim($_POST['wohours']) != "")
	{
		$wohours=$mysqli->real_escape_string(@trim($_POST['wohours']));
		$sub_query[]='weekly_operating_hours="'.$wohours.'"';
		$new_value['weekly_operating_hours']=$wohours;
	}
	
	if(isset($_POST['phone']) and @trim($_POST['phone']) != "")
	{
		$phone=$mysqli->real_escape_string(@trim($_POST['phone']));
		$sub_query[]='phone="'.$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone']))).'"';
		$new_value['phone']=$mysqli->real_escape_string(@preg_replace("/[^0-9]/","",@trim($_POST['phone'])));
	}else{
		echo json_encode(array('error'=>'Error Occured! Phone required.'));
		exit();		
	}

	if(isset($_POST['hcbp']) and @trim($_POST['hcbp']) != "")
	{
		$hcbp=$mysqli->real_escape_string(@trim($_POST['hcbp']));
		$sub_query[]='hdd_cdd_balance_point="'.$hcbp.'"';
		$new_value['hdd_cdd_balance_point']=$hcbp;
	}

	if(isset($_POST['wban']) and @trim($_POST['wban']) != "")
	{
		$wban=$mysqli->real_escape_string(@trim($_POST['wban']));
		$sub_query[]='wban="'.$wban.'"';
		$new_value['wban']=$wban;
	}

	if(isset($_POST['wstationname']) and @trim($_POST['wstationname']) != "")
	{
		$wstationname=$mysqli->real_escape_string(@trim($_POST['wstationname']));
		$sub_query[]='weather_station_name="'.$wstationname.'"';
		$new_value['weather_station_name']=$wstationname;
	}

	if(isset($_POST['wscity']) and @trim($_POST['wscity']) != "")
	{
		$wscity=$mysqli->real_escape_string(@trim($_POST['wscity']));
		$sub_query[]='weather_station_city="'.$wscity.'"';
		$new_value['weather_station_city']=$wscity;
	}

	if(isset($_POST['sstatus']) and @trim($_POST['sstatus']) != "")
	{
		$sstatus=$mysqli->real_escape_string(@trim($_POST['sstatus']));
		$sub_query[]='site_status="'.$sstatus.'"';
		$new_value['site_status']=$sstatus;
	}

//print_r($sub_query);	
//die("Terminated");
	if(count($sub_query)){
		audit_log($mysqli,"sites","UPDATE",$new_value,'WHERE id='.$sid,"","");
		$sql='UPDATE sites SET '.implode(",",$sub_query).' where id="'.$sid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			echo json_encode(array("error"=>""));
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}


//Delete Site
if(isset($_POST["sid"]) and @trim($_POST["sid"]) != "" and @trim($_POST["sid"]) != 0 and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();	
	
	$sid=$mysqli->real_escape_string(@trim($_POST["sid"]));
	/*if($sid == 1)
	{
		echo json_encode(array('error'=>'Error Occured! Vervantis deletion is not permitted.'));
		exit();
	}*/
	
	
	$stmtsk = $mysqli->prepare('SELECT site_number FROM sites where site_number="'.$sid.'" LIMIT 1');

//('SELECT id FROM sites where id="'.$sid.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			die("Under maintainence");
			$stmtskk = $mysqli->prepare('SELECT id FROM accounts where company_id="'.$cpy.'" LIMIT 1');
			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					echo json_encode(array('error'=>'Error Occured!  delete the users of this company.'));
					exit();
				}else{
					audit_log($mysqli,"company","DELETE","",'WHERE id="'.$cpy.'" LIMIT 1',"","");
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
			echo json_encode(array('error'=>'Error Occured! Site doesn\'t exists.'));
			exit();				
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}
}
//Delete Site ends





//Sites Ends




//Accounts Starts

//Edit Sites Starts
if(isset($_POST["aedit"]) and $_POST["aedit"]=="edit")
{

	$error="Error occured";
	$sub_query=$new_value=array();	
	
	if(isset($_POST['aid']) and @trim($_POST['aid']) != "" and @trim($_POST['aid']) != 0)
	{
		$aid=$mysqli->real_escape_string(@trim($_POST['aid']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Please try after sometime.'));
		exit();		
	}

	if(isset($_POST['account1']) and @trim($_POST['account1']) != "")
	{
		$account1=$mysqli->real_escape_string(@trim($_POST['account1']));
		$sub_query[]='account_number1="'.$account1.'"';
		$new_value['account_number1']=$account1;
	}else{
		echo json_encode(array('error'=>'Error Occured! Account Number 1 required.'));
		exit();		
	}
	
	if(isset($_POST['account2']) and @trim($_POST['account2']) != "")
	{
		$account2=$mysqli->real_escape_string(@trim($_POST['account2']));
		$sub_query[]='account_number2="'.$account2.'"';
		$new_value['account_number2']=$account2;
	}
	
	if(isset($_POST['account3']) and @trim($_POST['account3']) != "")
	{
		$account3=$mysqli->real_escape_string(@trim($_POST['account3']));
		$sub_query[]='account_number3="'.$account3.'"';
		$new_value['account_number3']=$account3;
	}
	
	if(isset($_POST['vendor']) and @trim($_POST['vendor']) != "")
	{
		$vendor=$mysqli->real_escape_string(@trim($_POST['vendor']));
		$sub_query[]='vendor_id="'.$vendor.'"';
		$new_value['vendor_id']=$vendor;
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor required.'));
		exit();		
	}
	
	if(isset($_POST['commodity']) and @trim($_POST['commodity']) != "")
	{
		$commodity=$mysqli->real_escape_string(@trim($_POST['commodity']));
		$sub_query[]='commodity_id="'.$commodity.'"';
		$new_value['commodity_id']=$commodity;
	}	
	
	if(isset($_POST['meter']) and @trim($_POST['meter']) != "")
	{
		$meter=$mysqli->real_escape_string(@trim($_POST['meter']));
		$sub_query[]='meter_id="'.$meter.'"';
		$new_value['meter_id']=$meter;
	}else{
		echo json_encode(array('error'=>'Error Occured! Meter Id required.'));
		exit();		
	}

	if(isset($_POST['servicegp']) and @trim($_POST['servicegp']) != "")
	{
		$servicegp=$mysqli->real_escape_string(@trim($_POST['servicegp']));
		$sub_query[]='service_group_id="'.$servicegp.'"';
		$new_value['service_group_id']=$servicegp;
	}else{
		echo json_encode(array('error'=>'Error Occured! Service Group Id required.'));
		exit();		
	}	
	
	if(isset($_POST['activedate']) and @trim($_POST['activedate']) != "")
	{
		$activedate=$mysqli->real_escape_string(@trim($_POST['activedate']));
		$sub_query[]='active_date="'.$activedate.'"';
		$new_value['active_date']=$activedate;
	}
	
	if(isset($_POST['inactivedate']) and @trim($_POST['inactivedate']) != "")
	{
		$inactivedate=$mysqli->real_escape_string(@trim($_POST['inactivedate']));
		$sub_query[]='inactive_date="'.$inactivedate.'"';
		$new_value['inactive_date']=$inactivedate;
	}

	if(isset($_POST['rateid']) and @trim($_POST['rateid']) != "")
	{
		$rateid=$mysqli->real_escape_string(@trim($_POST['rateid']));
		$sub_query[]='rate_id="'.$rateid.'"';
		$new_value['rate_id']=$rateid;
	}	
	
	if(isset($_POST['invoicesrc']) and @trim($_POST['invoicesrc']) != "")
	{
		$invoicesrc=$mysqli->real_escape_string(@trim($_POST['invoicesrc']));
		$sub_query[]='invoice_source="'.$invoicesrc.'"';
		$new_value['invoice_source']=$invoicesrc;
	}
	
	if(isset($_POST['invoicetrk']) and @trim($_POST['invoicetrk']) != "")
	{
		$invoicetrk=$mysqli->real_escape_string(@trim($_POST['invoicetrk']));
		$sub_query[]='invoice_tracked="'.$invoicetrk.'"';
		$new_value['invoice_tracked']=$invoicetrk;
	}
	
	if(isset($_POST['managed']) and @trim($_POST['managed']) != "")
	{
		$managed=$mysqli->real_escape_string(@trim($_POST['managed']));
		$sub_query[]='managed="'.$managed.'"';
		$new_value['managed']=$managed;
	}
	
	if(isset($_POST['utilitymtr']) and @trim($_POST['utilitymtr']) != "")
	{
		$utilitymtr=$mysqli->real_escape_string(@trim($_POST['utilitymtr']));
		$sub_query[]='utility_meter="'.$utilitymtr.'"';
		$new_value['utility_meter']=$utilitymtr;
	}


	if(count($sub_query)){
		audit_log($mysqli,"accounts","UPDATE",$new_value,'WHERE id='.$aid,"","");
		$sql='UPDATE accounts SET '.implode(",",$sub_query).' where id="'.$aid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			echo json_encode(array("error"=>""));
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}

//Add Account Starts
if(isset($_POST["aaddnew"]))
{

	$error="Error occured";
	$sub_query=$new_value=array();	
	
	//if(isset($_POST['sid']) and @trim($_POST['sid']) != "" and @trim($_POST['sid']) != 0) 
	if(isset($_POST['sid']) and @trim($_POST['sid']) != "")
	{
		$sid=$mysqli->real_escape_string(@trim($_POST['sid']));
		//$sub_query[]='sites_id="'.$sid.'"';
		$sub_query[]='site_number="'.$sid.'"';
		//$new_value['sites_id']=$sid;
		$new_value['site_number']=$sid;
	}else{
		echo json_encode(array('error'=>'Error Occured! Please try after sometime.'));
		exit();		
	}

	if(isset($_POST['account1']) and @trim($_POST['account1']) != "")
	{
		$account1=$mysqli->real_escape_string(@trim($_POST['account1']));
		$sub_query[]='account_number1="'.$account1.'"';
		$new_value['account_number1']=$account1;
	}else{
		echo json_encode(array('error'=>'Error Occured! Account Number 1 required.'));
		exit();		
	}
	
	if(isset($_POST['account2']) and @trim($_POST['account2']) != "")
	{
		$account2=$mysqli->real_escape_string(@trim($_POST['account2']));
		$sub_query[]='account_number2="'.$account2.'"';
		$new_value['account_number2']=$account2;
	}
	
	if(isset($_POST['account3']) and @trim($_POST['account3']) != "")
	{
		$account3=$mysqli->real_escape_string(@trim($_POST['account3']));
		$sub_query[]='account_number3="'.$account3.'"';
		$new_value['account_number3']=$account3;
	}
	
	if(isset($_POST['vendor']) and @trim($_POST['vendor']) != "")
	{
		$vendor=$mysqli->real_escape_string(@trim($_POST['vendor']));
		$sub_query[]='vendor_id="'.$vendor.'"';
		$new_value['vendor_id']=$vendor;
	}else{
		echo json_encode(array('error'=>'Error Occured! Vendor required.'));
		exit();		
	}
	
	if(isset($_POST['commodity']) and @trim($_POST['commodity']) != "")
	{
		$commodity=$mysqli->real_escape_string(@trim($_POST['commodity']));
		$sub_query[]='commodity_id="'.$commodity.'"';
		$new_value['commodity_id']=$commodity;
	}	
	
	if(isset($_POST['meter']) and @trim($_POST['meter']) != "")
	{
		$meter=$mysqli->real_escape_string(@trim($_POST['meter']));
		$sub_query[]='meter_id="'.$meter.'"';
		$new_value['meter_id']=$meter;
	}else{
		echo json_encode(array('error'=>'Error Occured! Meter Id required.'));
		exit();		
	}

	if(isset($_POST['servicegp']) and @trim($_POST['servicegp']) != "")
	{
		$servicegp=$mysqli->real_escape_string(@trim($_POST['servicegp']));
		$sub_query[]='service_group_id="'.$servicegp.'"';
		$new_value['service_group_id']=$servicegp;
	}else{
		echo json_encode(array('error'=>'Error Occured! Service Group Id required.'));
		exit();		
	}	
	
	if(isset($_POST['activedate']) and @trim($_POST['activedate']) != "")
	{
		$activedate=$mysqli->real_escape_string(@trim($_POST['activedate']));
		$sub_query[]='active_date="'.$activedate.'"';
		$new_value['active_date']=$activedate;
	}
	
	if(isset($_POST['inactivedate']) and @trim($_POST['inactivedate']) != "")
	{
		$inactivedate=$mysqli->real_escape_string(@trim($_POST['inactivedate']));
		$sub_query[]='inactive_date="'.$inactivedate.'"';
		$new_value['inactive_date']=$inactivedate;
	}

	if(isset($_POST['rateid']) and @trim($_POST['rateid']) != "")
	{
		$rateid=$mysqli->real_escape_string(@trim($_POST['rateid']));
		$sub_query[]='rate_id="'.$rateid.'"';
		$new_value['rate_id']=$rateid;
	}	
	
	if(isset($_POST['invoicesrc']) and @trim($_POST['invoicesrc']) != "")
	{
		$invoicesrc=$mysqli->real_escape_string(@trim($_POST['invoicesrc']));
		$sub_query[]='invoice_source="'.$invoicesrc.'"';
		$new_value['invoice_source']=$invoicesrc;
	}
	
	if(isset($_POST['invoicetrk']) and @trim($_POST['invoicetrk']) != "")
	{
		$invoicetrk=$mysqli->real_escape_string(@trim($_POST['invoicetrk']));
		$sub_query[]='invoice_tracked="'.$invoicetrk.'"';
		$new_value['invoice_tracked']=$invoicetrk;
	}
	
	if(isset($_POST['managed']) and @trim($_POST['managed']) != "")
	{
		$managed=$mysqli->real_escape_string(@trim($_POST['managed']));
		$sub_query[]='managed="'.$managed.'"';
		$new_value['managed']=$managed;
	}
	
	if(isset($_POST['utilitymtr']) and @trim($_POST['utilitymtr']) != "")
	{
		$utilitymtr=$mysqli->real_escape_string(@trim($_POST['utilitymtr']));
		$sub_query[]='utility_meter="'.$utilitymtr.'"';
		$new_value['utility_meter']=$utilitymtr;
	}


	if(count($sub_query)){
		audit_log($mysqli,"accounts","INSERT",$new_value,"","","");
		echo $sql='INSERT INTO accounts SET '.implode(",",$sub_query);die();
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
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

//Accounts Ends


//Usage Starts
//Edit Usage Starts
if(isset($_POST["uedit"]) and $_POST["uedit"]=="edit")
{

	$error="Error occured";
	$sub_query=$new_value=array();	
	
	if(isset($_POST['uid']) and @trim($_POST['uid']) != "" and @trim($_POST['uid']) != 0)
	{
		$uid=$mysqli->real_escape_string(@trim($_POST['uid']));
	}else{
		echo json_encode(array('error'=>'Error Occured! Please try after sometime.'));
		exit();		
	}

	if(isset($_POST['intstart']) and @trim($_POST['intstart']) != "")
	{
		$intstart=$mysqli->real_escape_string(@trim($_POST['intstart']));
		$sub_query[]='interval_start="'.$intstart.'"';
		$new_value['interval_start']=$intstart;
	}else{
		echo json_encode(array('error'=>'Error Occured! Interval Start required.'));
		exit();		
	}
	
	if(isset($_POST['intend']) and @trim($_POST['intend']) != "")
	{
		$intend=$mysqli->real_escape_string(@trim($_POST['intend']));
		$sub_query[]='interval_end="'.$intend.'"';
		$new_value['interval_end']=$intend;
	}else{
		echo json_encode(array('error'=>'Error Occured! Interval End required.'));
		exit();		
	}
	
	if(isset($_POST['intval']) and @trim($_POST['intval']) != "")
	{
		$intval=$mysqli->real_escape_string(@trim($_POST['intval']));
		$sub_query[]='interval_value="'.$intval.'"';
		$new_value['interval_value']=$intval;
	}else{
		echo json_encode(array('error'=>'Error Occured! Interval Value required.'));
		exit();		
	}
	
	if(isset($_POST['unitm']) and @trim($_POST['unitm']) != "")
	{
		$unitm=$mysqli->real_escape_string(@trim($_POST['unitm']));
		$sub_query[]='unit_of_measure="'.$unitm.'"';
		$new_value['unit_of_measure']=$unitm;
	}else{
		echo json_encode(array('error'=>'Error Occured! Unit Of Measure required.'));
		exit();		
	}
	
	if(isset($_POST['cost']) and @trim($_POST['cost']) != "")
	{
		$cost=$mysqli->real_escape_string(@trim($_POST['cost']));
		$sub_query[]='cost="'.$cost.'"';
		$new_value['cost']=$cost;
	}else{
		echo json_encode(array('error'=>'Error Occured! Cost required.'));
		exit();		
	}

	if(count($sub_query)){
		audit_log($mysqli,"usage","UPDATE",$new_value,'WHERE id='.$uid,"","");
		$sql='UPDATE `usage` SET '.implode(",",$sub_query).' where id="'.$uid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			echo json_encode(array("error"=>""));
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}

//Usage Ends



//print_r($_POST);
echo false;
exit();
?>