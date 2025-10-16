<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];


if(isset($_POST["comment"]) and isset($_POST["parentid"]) and isset($_POST["blogid"]) and trim($_POST["comment"]) != "" and trim($_POST["parentid"]) != "" and trim($_POST["blogid"]) != "" and trim($_POST["blogid"]) != 0)
{
	$comment = @trim($_POST['comment']);
	$parentid = @trim($_POST['parentid']);
	$blogid = @trim($_POST['blogid']);

	$stmt = $mysqli->prepare("INSERT INTO threaded_comments (user_id,comment,parent_id,blog_posts_id) VALUES ('".$mysqli->real_escape_string($user_one)."', '".$mysqli->real_escape_string($comment)."', '".$mysqli->real_escape_string($parentid)."', '".$mysqli->real_escape_string($blogid)."')") ;
	$stmt->execute();
	$lastinsertID=$stmt->insert_id;
	if($lastinsertID > 0) {
		$arr=array();
		$commentUser="Noname";
		
		$stmtsk = $mysqli->prepare('SELECT id,user_id,parent_id,blog_posts_id,datetime FROM threaded_comments where id='.$lastinsertID.' LIMIT 1');
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0) {
			$stmtsk->bind_result($commentID,$commentUserID,$commentParentID,$commentBlogPID,$commentDateTime);
			$stmtsk->fetch();
			$arr["id"]=$commentID;
			$arr["user_id"]=$commentUserID;
			$arr["parent_id"]=$commentParentID;
			$arr["blog_posts_id"]=$commentBlogPID;
			$arr["datetime"]=date('M d, Y', strtotime($commentDateTime));
		}
		
		$stmtsks = $mysqli->prepare("SELECT id FROM threaded_comments where blog_posts_id='".$mysqli->real_escape_string($blogid)."'");
		$stmtsks->execute();
		$stmtsks->store_result();
		$arr["nocomment"]=$stmtsks->num_rows;
	
		$arr["commentuser"]=$commentUser;
		
		$stmtskk = $mysqli->prepare('SELECT firstname,lastname FROM user where user_id='.$commentUserID.' LIMIT 1');
		$stmtskk->execute();
		$stmtskk->store_result();
		if ($stmtskk->num_rows > 0) {
			$stmtskk->bind_result($firstName,$lastName);
			$stmtskk->fetch();
			$commentUser=$firstName." ".$lastName;
		}
	
		$arr["commentuser"]=$commentUser;
		
		echo json_encode($arr);exit();
	}
}
echo false;
?>