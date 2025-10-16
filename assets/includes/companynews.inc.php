<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if(!$_SESSION["group_id"] ==1 or !$_SESSION["group_id"] ==2 or !isset($_SESSION["user_id"]))
	die("Access Restricted.");

$user_one=$_SESSION['user_id'];

if(isset($_POST["new-post-title"]) and isset($_POST["new-post"]))
{

	$error="Error occured";
	
	if(isset($_POST["new-post-title"]) and @trim($_POST["new-post-title"]) != "")
		$newPostTitle = @trim($_POST['new-post-title']);
	else{
		echo json_encode(array("error"=>"Company news title required!"));	
		exit();	
	}
	
	if(isset($_POST["new-post"]) and @trim($_POST["new-post"]) != "")
		$newPost = @trim($_POST['new-post']);
	else{
		echo json_encode(array("error"=>"Company news description required!"));	
		exit();	
	}

	$stmt = $mysqli->prepare("INSERT INTO company_news (user_id,post_title,post_cont) VALUES ('".$mysqli->real_escape_string($user_one)."', '".$mysqli->real_escape_string($newPostTitle)."', '".$mysqli->real_escape_string($newPost)."')") ;
	$stmt->execute();
	$lastinsertID=$stmt->insert_id;
	if($lastinsertID > 0) {
		$arr=array();
		
		$stmtsk = $mysqli->prepare('SELECT id,user_id,post_title,post_cont,post_banner,datetime,status FROM company_news where id='.$lastinsertID.' LIMIT 1');
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0) {
			$stmtsk->bind_result($cnID,$cnUser,$cnTitle,$cnContent,$cnBanner,$cnDateTime,$cnStatus);
			$stmtsk->fetch();
			$arr["id"]=$cnID;
			$arr["user_id"]=$cnUser;
			$arr["cnTitle"]=$cnTitle;
			$arr["cnContent"]=base64_encode($cnContent);
			$arr["cnBanner"]=$cnBanner;
			$arr["datetime"]=date('M d, Y', strtotime($cnDateTime));
			$arr["catdatetime"]=date('M Y', strtotime($cnDateTime));
			$arr["publish"]=(($cnStatus==1)?"Y":"N");
		}
		
		$stmtskk = $mysqli->prepare('SELECT firstname,lastname FROM user where user_id='.$user_one.' LIMIT 1');

//('SELECT firstname,lastname FROM user where id='.$user_one.' LIMIT 1');

		$stmtskk->execute();
		$stmtskk->store_result();
		if ($stmtskk->num_rows > 0) {
			$stmtskk->bind_result($firstName,$lastName);
			$stmtskk->fetch();
			$commentUser=$firstName." ".$lastName;
		}
	
		$arr["cnUser"]=$commentUser;
		$arr["error"]=false;
		
		echo json_encode($arr);exit();
	}

		
	echo json_encode(array("error"=>$error));	
	exit();
}

if(isset($_POST["edit-post-title"]) and isset($_POST["edit-post"]) and isset($_POST["post-id"]))
{

	$error="Error occured";
	
	if(isset($_POST["edit-post-title"]) and @trim($_POST["edit-post-title"]) != "")
		$newPostTitle = @trim($_POST['edit-post-title']);
	else{
		echo json_encode(array("error"=>"Company news title required!"));	
		exit();	
	}
	
	if(isset($_POST["edit-post"]) and @trim($_POST["edit-post"]) != "")
		$newPost = @trim($_POST['edit-post']);
	else{
		echo json_encode(array("error"=>"Company news description required!"));	
		exit();	
	}
	
	if(isset($_POST["post-id"]) and @trim($_POST["post-id"]) != "" and @trim($_POST["post-id"]) != 0)
		$editPostID = @trim($_POST['post-id']);
	else{
		echo json_encode(array("error"=>$error));	
		exit();	
	}

	$sql="UPDATE company_news SET post_title='".$mysqli->real_escape_string($newPostTitle)."', post_cont='".$mysqli->real_escape_string($newPost)."' WHERE id=".$mysqli->real_escape_string($editPostID);

	if($sql != "")
	{
		$stmt = $mysqli->prepare($sql) ;
		$stmt->execute();
		$lastinsertID=$editPostID;
		if($lastinsertID > 0) {
			$arr=array();
			$commentUser="Noname";
			
			$stmtsk = $mysqli->prepare('SELECT id,user_id,post_title,post_cont,post_banner,datetime,status FROM company_news where id='.$lastinsertID.' LIMIT 1');
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows > 0) {
				$stmtsk->bind_result($cnID,$cnUser,$cnTitle,$cnContent,$cnBanner,$cnDateTime,$cnStatus);
				$stmtsk->fetch();
				$arr["id"]=$cnID;
				$arr["user_id"]=$cnUser;
				$arr["cnTitle"]=$cnTitle;
				$arr["cnContent"]=base64_encode($cnContent);
				$arr["cnBanner"]=$cnBanner;
				$arr["datetime"]=date('M d, Y', strtotime($cnDateTime));
				$arr["publish"]=(($cnStatus==1)?"Y":"N");
			}
			
			$stmtskk = $mysqli->prepare('SELECT firstname,lastname FROM user where user_id='.$user_one.' LIMIT 1');

//('SELECT firstname,lastname FROM user where id='.$user_one.' LIMIT 1');

			$stmtskk->execute();
			$stmtskk->store_result();
			if ($stmtskk->num_rows > 0) {
				$stmtskk->bind_result($firstName,$lastName);
				$stmtskk->fetch();
				$commentUser=$firstName." ".$lastName;
			}
		
			$arr["cnUser"]=$commentUser;
			$arr["error"]=false;
			
			echo json_encode($arr);exit();
		}
	}	
	echo json_encode(array("error"=>$error));;	
	exit();
}


if(isset($_POST["publishCN"]) and isset($_POST["cnId"]) and trim($_POST["publishCN"]) != "" and trim($_POST["cnId"]) != "" and (trim($_POST["publishCN"]) == "Publish" or trim($_POST["publishCN"]) == "Unpublish") and trim($_POST["cnId"]) != 0)
{
	$arr=array();
	$error="Error occured. Please try after sometime";
	$publishCN = @trim($_POST['publishCN']);
	$cnId = @trim($_POST['cnId']);
	if($user_one != 1)
		$sub_condition=" and user_id=".$user_one;
	else
		$sub_condition="";

	if($mysqli->real_escape_string($publishCN) == "Publish")
		$sql="UPDATE company_news SET status='1' WHERE id='".$cnId."' ".$sub_condition;
	elseif($mysqli->real_escape_string($publishCN) == "Unpublish")
		$sql="UPDATE company_news SET status='0' WHERE id='".$cnId."' ".$sub_condition;
	else
		$sql="";

	if($sql != "")
	{
		$stmt = $mysqli->prepare($sql) ;
		$stmt->execute();

		$stmtsk = $mysqli->prepare('SELECT id,status FROM company_news where id="'.$cnId.'"'.$sub_condition.' LIMIT 1');
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0) {
			$stmtsk->bind_result($cnId,$status);
			$stmtsk->fetch();
			if(($mysqli->real_escape_string($publishCN) == "Publish" and $status==1) or ($mysqli->real_escape_string($publishCN) == "Unpublish" and $status==0))
			{
				$arr["error"]=false;
				echo json_encode($arr);exit();
			}
		}
	}	
	echo json_encode(array("error"=>$error));;	
	exit();
}


if(isset($_POST["action"]) and isset($_POST["pid"]) and trim($_POST["action"]) == "delete" and trim($_POST["pid"]) != "" and trim($_POST["pid"]) != 0)
{

	$error="Error occured";
	$deletePostID = $mysqli->real_escape_string(@trim($_POST['pid']));
			
	$stmtsk = $mysqli->prepare('SELECT user_id, post_banner FROM company_news where id="'.$deletePostID.'" LIMIT 1');
	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($blogUser,$blogBanner);
		$stmtsk->fetch();

		$stmtskks = $mysqli->prepare('Delete FROM company_news where id="'.$deletePostID.'"');
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
		
		
	}else{
		echo json_encode(array('error'=>'Error Occured! Blog post not found.'));
		exit();		
	}

	echo json_encode(array("error"=>$error));;	
	exit();
}

//print_r($_POST);
echo false;
?>