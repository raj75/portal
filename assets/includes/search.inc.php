<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

$_POST["searchkey"]=$_GET["searchkey"];

if(isset($_POST['searchkey']) and @trim($_POST['searchkey']) != "")
{
    $key=@trim($_POST['searchkey']);
    $array = array("blogsearch"=>array(),"usersearch"=>array());
	
	$stmt = $mysqli->prepare('SELECT id, post_title, post_cont FROM blog_posts where post_title LIKE "%'.$key.'%" or post_cont LIKE  "%'.$key.'%" ORDER BY id DESC');
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($postID,$post_title,$post_cont);
		while($stmt->fetch()){
			$array ["blogsearch"][] = array("id"=>$postID,"title"=>$post_title,"body"=>$post_cont);
		}
	}	
	$stmtk = $mysqli->prepare('SELECT user_id, firstname, lastname,designation,company,phone_part1,phone_part2,phone_part3,email,skype,birthdate,aboutme_title,aboutme_details  FROM user where firstname LIKE "%'.$key.'%" or lastname LIKE "%'.$key.'%" or designation LIKE "%'.$key.'%" or company LIKE "%'.$key.'%" or phone_part1 LIKE "%'.$key.'%" or phone_part2 LIKE "%'.$key.'%" or phone_part3 LIKE "%'.$key.'%" or email LIKE "%'.$key.'%" or  skype LIKE "%'.$key.'%" or birthdate LIKE "%'.$key.'%" or aboutme_title LIKE "%'.$key.'%" or aboutme_details LIKE "%'.$key.'%" ORDER BY user_id DESC');

//('SELECT id, firstname, lastname,designation,company,phone_part1,phone_part2,phone_part3,email,skype,birthdate,aboutme_title,aboutme_details  FROM user where firstname LIKE "%'.$key.'%" or lastname LIKE "%'.$key.'%" or designation LIKE "%'.$key.'%" or company LIKE "%'.$key.'%" or phone_part1 LIKE "%'.$key.'%" or phone_part2 LIKE "%'.$key.'%" or phone_part3 LIKE "%'.$key.'%" or email LIKE "%'.$key.'%" or  skype LIKE "%'.$key.'%" or birthdate LIKE "%'.$key.'%" or aboutme_title LIKE "%'.$key.'%" or aboutme_details LIKE "%'.$key.'%" ORDER BY id DESC');

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($userID,$firstname,$lastname,$designation,$company,$phone_part1,$phone_part2,$phone_part3,$email,$skype,$birthdate,$aboutme,$aboutme_details);
		while($stmtk->fetch()){
			$array ["usersearch"][] = array("id"=>$userID,"firstname"=>$firstname,"lastname"=>$lastname,"designation"=>$designation,"company"=>$company,"phone_part1"=>$phone_part1,"phone_part2"=>$phone_part2,"phone_part3"=>$phone_part3,"email"=>$email,"skype"=>$skype,"birthdate"=>$birthdate,"aboutme"=>$aboutme,"aboutme_details"=>$aboutme_details);			
		}
	}
	
	echo json_encode($array);
	exit();
}else
	echo "2222";
	
print_r($_POST);
echo false;
?>