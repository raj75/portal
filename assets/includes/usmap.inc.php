<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
if($group_id != 1 and $group_id != 2)
	die("Restricted Access!");


if(isset($_POST["edit-post"]) and isset($_POST["did"]) and isset($_POST["dsid"]) and isset($_POST["dsc1id"]) and isset($_POST["dsc2id"]) and @trim($_POST["did"]) != "" and @trim($_POST["did"]) != 0 and @trim($_POST["dsc1id"]) != "" and @trim($_POST["dsc1id"]) != 0 and @trim($_POST["dsc2id"]) != "" and @trim($_POST["dsc2id"]) != 0 and !isset($_POST["load"]))
{
	 $docs = @trim($_POST["edit-post"]);
	 $dsid = @trim($_POST["dsid"]);
	 $did = @trim($_POST["did"]);
	 $dsc1id = @trim($_POST["dsc1id"]);
	 $dsc2id = @trim($_POST["dsc2id"]);
	
	if($docs != "" and $dsid == ""){
		$stmtski = $mysqli->prepare("SELECT id FROM document_save where document_id='".$mysqli->real_escape_string($did)."' and document_choice_1_id='".$mysqli->real_escape_string($dsc1id)."' and document_choice_2_id='".$mysqli->real_escape_string($dsc2id)."' LIMIT 1");
		if($stmtski){
			$stmtski->execute();
			$stmtski->store_result();
			if ($stmtski->num_rows == 0)
			{	
				$stmtisk = $mysqli->prepare("INSERT INTO document_save SET document='".$mysqli->real_escape_string($docs)."', document_id='".$mysqli->real_escape_string($did)."', document_choice_1_id='".$mysqli->real_escape_string($dsc1id)."', document_choice_2_id='".$mysqli->real_escape_string($dsc2id)."'");
				$stmtisk->execute();
				$lastinsertID=$stmtisk->insert_id;
				if($lastinsertID > 0) {
					echo json_encode(array("error"=>""));
					exit();			
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Document already exist.'));			
				exit();
			}
		}
		echo json_encode(array('error'=>'Error Occured! Database error.'));			
		exit();		
	}elseif($docs == "" and $dsid == ""){
		echo json_encode(array("error"=>""));
		exit();	
	}else{
		$stmtsk = $mysqli->prepare('SELECT document FROM document_save where id="'.$dsid.'" LIMIT 1');
		if($stmtsk){
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows > 0)
			{
				$stmtsk->bind_result($_ds_document);
				$stmtsk->fetch();
				if(@trim($_ds_document) != @trim($docs)){
					$sql='UPDATE document_save SET document ="'.$mysqli->real_escape_string(@trim($docs)).'" WHERE id="'.$dsid.'"';
					$stmt = $mysqli->prepare($sql);
					if($stmt)
					{
						$stmt->execute();
						echo json_encode(array("error"=>""));
						exit();
					}else{
						echo json_encode(array('error'=>'Error Occured! Database error1.'));			
						exit();
					}
				}else{
					echo json_encode(array("error"=>""));
					exit();			
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error2.'));
				exit();				
			}
		}else{
				echo json_encode(array('error'=>'Error Occured! Database error3.'));
				exit();			
		}
	}

}

	
if(isset($_POST["country"]) and isset($_POST["state"]) and isset($_POST["description"]) and !isset($_POST["load"]) and @trim($_POST["country"]) != "" and @trim($_POST["state"]) != "")
{
	 $country = @trim($_POST["country"]);
	 $state = @trim($_POST["state"]);
	 $description = @trim($_POST["description"]);

	$stmtsk = $mysqli->prepare('SELECT id FROM document where country="'.$country.'" and state="'.$state.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$sql='UPDATE document SET document="'.$mysqli->real_escape_string(@trim($_POST['description'])).'" WHERE country="'.$country.'" and state="'.$state.'" LIMIT 1';
			$stmt = $mysqli->prepare($sql);
			if($stmt)
			{
				$stmt->execute();
				echo json_encode(array("error"=>""));
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
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();			
	}	 

}

if(isset($_POST["country"]) and isset($_POST["state"]) and isset($_POST["load"]) and @trim($_POST["country"]) != "" and @trim($_POST["state"]) != "")
{
	$country = @trim($_POST["country"]);
	$state = @trim($_POST["state"]);
	$tmp_choice_1=$tmp_choice_2=$tmp_choice=array();
	
	$stmtsk = $mysqli->prepare('SELECT id FROM document where country="'.$country.'" and state="'.$state.'" and status=1 LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtsk->bind_result($_id);
			$stmtsk->fetch();

			$stmtskkk = $mysqli->prepare('SELECT id,choice_name FROM `document_choice_2` WHERE status=1');
			if($stmtskkk){
				$stmtskkk->execute();
				$stmtskkk->store_result();
				if ($stmtskkk->num_rows > 0)
				{
					$stmtskkk->bind_result($_d2_id,$_d2_choice_name);
					while($stmtskkk->fetch()) {
						$tmp_choice_2[]=array("id"=>$_d2_id,"choice_name"=>$_d2_choice_name,"document"=>"","dsid"=>"");
					}
				}
			}
			
			$stmtskk = $mysqli->prepare('SELECT id,choice_name FROM document_choice_1 WHERE status=1');
			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					$stmtskk->bind_result($_d1_id,$_d1_choice_name);
					while($stmtskk->fetch()) {
						for($z=0;$z<count($tmp_choice_2);$z++)
						{
							$tmp_choice_2[$z]["dsid"]="";
							$tmp_choice_2[$z]["document"]="";
							$stmtskkkk = $mysqli->prepare('SELECT id,document FROM document_save WHERE status=1 and document_id="'.$_id.'" and document_choice_1_id="'.$_d1_id.'" and document_choice_2_id="'.$tmp_choice_2[$z]["id"].'" LIMIT 1');
							if($stmtskkkk){
								$stmtskkkk->execute();
								$stmtskkkk->store_result();
								if ($stmtskkkk->num_rows > 0)
								{
									$stmtskkkk->bind_result($_ds_id,$_ds_document);
									$stmtskkkk->fetch();
									$tmp_choice_2[$z]["dsid"]=$_ds_id;
									$tmp_choice_2[$z]["document"]=$_ds_document;
								}
							}
						}
					
					
						$tmp_choice_1[]=array("did"=>$_id,"id"=>$_d1_id,"choice_name"=>$_d1_choice_name,"sub_choice"=>$tmp_choice_2);
					}
				}
			}			
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
		}
		
		echo json_encode(array('error'=>'','result'=>$tmp_choice_1));
		exit();		
	}	
	echo json_encode(array('error'=>'Error Occured! Database error.'));
	exit();	
}

echo false;

?>