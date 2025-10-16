<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 5)
	die("Restricted Access");


$user_one=$_SESSION['user_id'];

if(isset($_POST["userid"]) and isset($_POST["jsondata"]) and $_SESSION["group_id"]==1)
	echo update_user_interface($mysqli,$_POST["userid"],$_POST["jsondata"]);
else if(isset($_POST["userid"]) and isset($_POST["uper"]) and isset($_POST["aper"]))
	echo update_permission($mysqli,$_POST["userid"],$_POST["uper"],$_POST["aper"],$user_one);
else
	echo false;


function update_permission($mysqli,$userid='',$uper='',$aper='',$user_one=0)
{
	$_userid=$userid=(int)@trim($userid);
	if($userid=="" or $userid==0) return false;
	
	$dmyc=$dmya=$dm=array();

	if($_SESSION["group_id"]==5)
	{
		if ($stmtcheck = $mysqli->prepare('SELECT u.user_id,u.company_id FROM user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.user_id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM userprofile usp WHERE usp.user_id= "'.$user_one.'") LIMIT 1')) { 


//('SELECT u.id,u.company_id FROM user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM userprofile usp WHERE usp.user_id= "'.$user_one.'") LIMIT 1')) { 

			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				exit(false);
			}else{
				$stmtcheck->bind_result($_uid,$_ucid);
				$stmtcheck->fetch();
				
				if($_ucid == 0){exit(false);}
				
				if ($stmt = $mysqli->prepare('SELECT id FROM permission where company_id = "'.$mysqli->real_escape_string($_ucid).'" LIMIT 1')) { 
					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($_id);
						$stmt->fetch();

						$result = $mysqli->query("UPDATE permission SET disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."',disabled_by='".$mysqli->real_escape_string($user_one)."'  WHERE id='".$_id."'");
						return true;
					}else{
						$result = $mysqli->query("INSERT INTO permission SET company_id = '".$mysqli->real_escape_string($_ucid)."',disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."', disabled_by='".$mysqli->real_escape_string($user_one)."'");
						
						if($mysqli->affected_rows > 0){
							//$result->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						}

						//$result->close();
					}

					//$stmt->free_result();
					//$stmt->close();
				}	
			}
			exit(false);
		}else{
			exit(false);
			
		}
	}

	
	if($_SESSION["group_id"]==1)
	{	
		if ($stmtcheck = $mysqli->prepare('SELECT user_id,company_id,usergroups_id FROM user where user_id="'.$_userid.'" LIMIT 1')) { 

//('SELECT id,company_id,usergroups_id FROM user where id="'.$_userid.'" LIMIT 1')) {

			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				exit(false);
			}else{
				$stmtcheck->bind_result($_uid,$_ucid,$_ugi);
				$stmtcheck->fetch();
				
				if($_ugi==1){
					exit(false);
				}elseif($_ugi==2)
					$sql='SELECT id FROM permission where user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1';
				elseif($_ugi==3 or $_ugi==5)
					$sql='SELECT id FROM permission where company_id = "'.$mysqli->real_escape_string($_ucid).'" LIMIT 1';
				elseif($_ugi==4){
					exit(false);
				}else{
					exit(false);
				}

				if ($stmt = $mysqli->prepare($sql)) { 
					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($_id);
						$stmt->fetch();

						if($_ugi == 2)
						{
							if(@trim($uper)=="" and @trim($aper)=="")
								$sql='DELETE FROM permission where user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1';
							else $sql="UPDATE permission SET disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."',disabled_menu_by_admin='".$mysqli->real_escape_string($aper)."'  WHERE id='".$_id."'";

							$mysqli->query($sql);
							return true;
							
						}else if($_ugi == 3 || $_ugi==5)
						{
							if($_ucid == 0) exit(false);
							$result = $mysqli->query("UPDATE permission SET disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."',disabled_by='".$mysqli->real_escape_string($user_one)."'  WHERE id='".$_id."'");
							return true;
						}
					}else{
						if($_ugi == 2)
							$sqli="INSERT INTO permission SET user_id = '".$mysqli->real_escape_string($userid)."',disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."',disabled_menu_by_admin='".$mysqli->real_escape_string($aper)."', disabled_by='".$mysqli->real_escape_string($user_one)."'";
						else if($_ugi == 3 || $_ugi==5){
							
							if($_ucid == 0) exit(false);

							$sqli="INSERT INTO permission SET company_id = '".$mysqli->real_escape_string($_ucid)."',disabled_menu_by_clientadmin='".$mysqli->real_escape_string($uper)."', disabled_by='".$mysqli->real_escape_string($user_one)."'";							
						}
						$result = $mysqli->query($sqli);
						
						if($mysqli->affected_rows > 0){
							//$result->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						}

						//$result->close();
					}

					//$stmt->free_result();
					//$stmt->close();
				}	
			}
		}

	}
	return false;
}



function update_user_interface($mysqli,$userid='',$jsondata='')
{
	$userid=(int)@trim($userid);
	$jsondata=@trim($jsondata);
	
	$dmyc=$dmya=$dm=array();
	
	if($userid != '' and $userid != 0 and $_SESSION["group_id"] == 1){
		if ($stmt = $mysqli->prepare('SELECT id,custom_interface FROM users_interface where  user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) { 
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($_id,$_custom_interface);
				$stmt->fetch();

				if($_custom_interface == $jsondata){
					//$stmt->free_result();
					//$stmt->close();			
					return true;
				}

				$result = $mysqli->query("UPDATE users_interface SET custom_interface='$jsondata' WHERE id='".$_id."'");

				//$result->close();
				//$stmt->free_result();
				//$stmt->close();

				return true;
			}else{
				if ($stmtt = $mysqli->prepare('SELECT ug.interface FROM `usergroups` ug, user u where u.usergroups_id=ug.id and u.user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {

//('SELECT ug.interface FROM `usergroups` ug, user u where u.usergroups_id=ug.id and u.id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {

					$stmtt->execute();
					$stmtt->store_result();
					if ($stmtt->num_rows > 0) {
						$stmtt->bind_result($_interface);
						$stmtt->fetch();

						if($_interface == $jsondata){
							//$stmtt->free_result();
							//$stmtt->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						};

						$result = $mysqli->query("INSERT INTO users_interface (user_id,custom_interface) VALUES ('$userid','$jsondata')");
						
						if($mysqli->affected_rows > 0){
							//$result->close();
							//$stmtt->free_result();
							//$stmtt->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						}
						
						//$result->close();
					}
					
					//$stmtt->free_result();
					//$stmtt->close();
				}
			}

			//$stmt->free_result();
			//$stmt->close();
		}	
	}
	return false;
}

function update_user_interface123($mysqli,$userid='',$jsondata='',$disabledmenu='',$user_one=0)
{
	$userid=(int)@trim($userid);
	$jsondata=@trim($jsondata);
	
	$dmyc=$dmya=$dm=array();
	
	if($userid != '' and $userid != 0 and $jsondata != ''){
		if(@trim($disabledmenu)==''){
			$result = $mysqli->query('DELETE FROM permission where user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1');
			//$result->close();
		}else{
			if ($stmt = $mysqli->prepare('SELECT id,disabled_menu_by_clientadmin,disabled_menu_by_admin,disabled_by FROM permission where  user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) { 
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($_id,$_disabled_menu_by_c,$_disabled_menu_by_a,$_disabled_by);
					$stmt->fetch();
					
					$dmyc=@explode(",",$_disabled_menu_by_c);
					$dmya=@explode(",",$_disabled_menu_by_a);
					$dm=@explode(",",$disabledmenu);
					$dm_c=@array_intersect($dmyc,$dm);
					$dm_a = @array_diff($dm, $dm_c);
					$dm_csave=@implode(",",$dm_c);
					$dm_asave=@implode(",",$dm_a);

					if($_SESSION["group_id"] == 1)
					{
						$result = $mysqli->query("UPDATE permission SET disabled_menu_by_clientadmin='".$mysqli->real_escape_string($dm_csave)."',disabled_menu_by_admin='".$mysqli->real_escape_string($dm_asave)."',disabled_by='".$mysqli->real_escape_string($user_one)."'  WHERE id='".$_id."'");
						
					}else if($_SESSION["group_id"] == 5)
					{
						$result = $mysqli->query("UPDATE permission SET disabled_menu_by_clientadmin='".$mysqli->real_escape_string($dm_csave)."',disabled_menu_by_admin='".$mysqli->real_escape_string($dm_asave)."',disabled_by='".$mysqli->real_escape_string($user_one)."'  WHERE id='".$_id."'");	
					}else return false;
					$result = $mysqli->query("UPDATE permission SET disabled_menu='".$mysqli->real_escape_string($disabledmenu)."' WHERE id='".$_id."'");

					//$result->close();
				}else{
					$result = $mysqli->query("INSERT INTO permission set user_id = '".$mysqli->real_escape_string($userid)."' , disabled_menu='".$mysqli->real_escape_string($disabledmenu)."', disabled_by='".$mysqli->real_escape_string($user_one)."'");
					
					if($mysqli->affected_rows == 0){
						//$result->close();
						//$stmt->free_result();
						//$stmt->close();							
						return false;
					}

					//$result->close();
				}

				//$stmt->free_result();
				//$stmt->close();
			}	
		}	
		
		if ($stmt = $mysqli->prepare('SELECT id,custom_interface FROM users_interface where  user_id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) { 
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($_id,$_custom_interface);
				$stmt->fetch();

				if($_custom_interface == $jsondata){
					//$stmt->free_result();
					//$stmt->close();			
					return true;
				}

				$result = $mysqli->query("UPDATE users_interface SET custom_interface='$jsondata' WHERE id='".$_id."'");

				//$result->close();
				//$stmt->free_result();
				//$stmt->close();

				return true;
			}else{
				if ($stmtt = $mysqli->prepare('SELECT ug.interface FROM `usergroups` ug, user u where u.usergroups_id=ug.id and u.id = "'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {
					$stmtt->execute();
					$stmtt->store_result();
					if ($stmtt->num_rows > 0) {
						$stmtt->bind_result($_interface);
						$stmtt->fetch();

						if($_interface == $jsondata){
							//$stmtt->free_result();
							//$stmtt->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						};

						$result = $mysqli->query("INSERT INTO users_interface (user_id,custom_interface) VALUES ('$userid','$jsondata')");
						
						if($mysqli->affected_rows > 0){
							//$result->close();
							//$stmtt->free_result();
							//$stmtt->close();
							//$stmt->free_result();
							//$stmt->close();							
							return true;
						}
						
						//$result->close();
					}
					
					//$stmtt->free_result();
					//$stmtt->close();
				}
			}

			//$stmt->free_result();
			//$stmt->close();
		}	
	}
	return false;
}
?>