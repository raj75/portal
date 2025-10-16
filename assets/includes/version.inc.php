<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];

//if(!isset($group_id) or ($group_id != 1 and $_SESSION["group_id"] != 2)){echo false;exit();}
if(!isset($group_id) or ($group_id != 1 and $_SESSION["group_id"] != 2 and $group_id != 3 and $_SESSION["group_id"] != 5)){echo false;exit();}

//Show Version
if(isset($_POST["vid"]) and @trim($_POST["vid"]) != "" and @trim($_POST["vid"]) != 0 and isset($_POST["vtname"]) and @trim($_POST["vtname"]) != "" and  isset($_POST["vfname"]) and @trim($_POST["vfname"]) != "" and isset($_POST["action"]) and @trim($_POST["action"]) == "showversion")
{

	$error="Error occured";
	$sub_query=$new_value=$finfo=$editedvalarr=array();
	$z=$t=$v=$editedvalue=$zz="";
	$l=1;
	
	$tablerowid=$mysqli->real_escape_string(@trim($_POST["vid"]));
	$tablename=$mysqli->real_escape_string(@trim($_POST["vtname"]));
	$fieldname=$mysqli->real_escape_string(@trim($_POST["vfname"]));

	if(@trim($tablename) == "" or @trim($tablerowid) == "" or @trim($tablerowid) == 0 or @trim($fieldname) == "")
	{echo "Error";exit();}

	$old_value_arr=$editedvalarr=array();
//echo 'SELECT modified,edited_value,activity,status,id FROM `audit_log` where table_name="'.$tablename.'" and table_row_id="'.$tablerowid.'" ORDER BY modified DESC LIMIT 3';die();
	if ($stmt = $mysqli->query('SELECT modified,edited_value,activity,status,id FROM `audit_log` where table_name="'.$tablename.'" and table_row_id="'.$tablerowid.'" ORDER BY modified DESC')) {
        if ($stmt->num_rows > 0) {
			//$t="<ul id='popup-tab' class='nav nav-tabs bordered'>";
			//$v="<div id='popup-tab-content' class='tab-content padding-10'>";
			$kk="";
			if(isset($_POST["tuid"]) and isset($_POST["tuurl"])){
				$kk=",'".@trim($_POST["tuid"])."','".@trim($_POST["tuurl"])."'";
			}
			while($row=$stmt->fetch_row()) {
				$editedvalue=$row[1];
				if($editedvalue=="")
				{
					echo false;
					exit();
				}

				$editedvalarr=unserialize(base64_decode($editedvalue));
				$z=count($editedvalarr);

				//if($z > 0){$t="<ul id='popup-tab' class='nav nav-tabs bordered'>";$v="<div id='popup-tab-content' class='tab-content padding-10'>";}			
				
				
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and @trim($editedvalarr[$i]["title"]) == @trim($fieldname)){
						$edv=$editedvalarr[$i]["old"];
						$zz = $zz."<tr><td>".date("M d,Y h:i:s A", strtotime($row[0]))."</td><td>".$edv."</td><td>";
						if($row[2]=="UPDATE"){
							if($row[3] != 1){
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'rollback\''.$kk.')" title="Roll Back" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></button>';
							}
							if($row[3] != 2){ 
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'forward\''.$kk.')" title="forward" class="btn btn-xs btn-default"><i class="fa fa-share"></i></button>';						
							}
						}
						$zz = $zz."</td></tr>";
					}
					if(isset($editedvalarr[$i]["file"])){
						//$editedval .= "<b>File:</b> ".$editedvalarr[$i]["file"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
						//$l++;
					}
				}
				//if($z > 0){$t .= "</ul>";$v .= "</div>";}
			}
		}
	}else{
		echo false;
		exit();
	}
if($zz != ""){echo json_encode(array("version"=>"<div class='table-responsive'><table class='table table-bordered table-striped'><thead><tr><th>Date</th><th>Previous Value</th><th>Action</th></tr>".$zz."</thead><tbody>"));}else{echo false;}
	//if($t != "" and $v != ""){echo json_encode(array("version"=>"<ul id='popup-tab' class='nav nav-tabs bordered'>".$t."</ul>"."<div id='popup-tab-content' class='tab-content padding-10'>".$v."</div>"));}else{echo false;}
	exit();
}

//print_r($_POST);
echo false;
exit();
?>