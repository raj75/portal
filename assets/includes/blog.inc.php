<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);

$user_one=$_SESSION['user_id'];



if(isset($_POST["new-post-title"]) and isset($_POST["new-post"]) and isset($_FILES['new-post-banner']) and trim($_POST["new-post-title"]) != "" and trim($_POST["new-post"]) != "")
{
	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);





	$error="Error occured";
	$newPostTitle = @trim($_POST['new-post-title']);
	$newPost = @trim($_POST['new-post']);
	//$newPostBanner = $_FILES['new-post-banner'];



	$target_filename = microtime().mt_rand(1000,10000).".png";

	$uploadOk = 1;
	//$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["new-post-banner"]["tmp_name"]);
	if($check !== false) {
		//echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		$error = "File is not an image.";
		$uploadOk = 0;
	}

	// Check file size
	/*if ($_FILES["new-post-banner"]["size"] > 500000  and $uploadOk == 1) {
		$error = "Sorry, your file is too large.";
		$uploadOk = 0;
	}*/
	// Allow certain file formats
	/*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif"  and $uploadOk == 1) {
		$error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}*/
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		//echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {

		$resultupload = $s3Client->putObject([
			'Bucket' => 'datahub360-public',
			'Key'    => 'blog/'.$target_filename,
			'SourceFile' => $_FILES["new-post-banner"]["tmp_name"],
			'ACL'    => 'public-read'
		]);

		if ($resultupload) {

			$stmt = $mysqli->prepare("INSERT INTO blog_posts (user_id,post_title,post_cont,post_banner) VALUES ('".$mysqli->real_escape_string($user_one)."', '".$mysqli->real_escape_string($newPostTitle)."', '".$mysqli->real_escape_string($newPost)."', '".$mysqli->real_escape_string($target_filename)."')") ;
			$stmt->execute();
			$lastinsertID=$stmt->insert_id;
			if($lastinsertID > 0) {
				$arr=array();
				$commentUser="Noname";

				$stmtsk = $mysqli->prepare('SELECT id,user_id,post_title,post_cont,post_banner,datetime,status FROM blog_posts where id='.$lastinsertID.' LIMIT 1');
				$stmtsk->execute();
				$stmtsk->store_result();
				if ($stmtsk->num_rows > 0) {
					$stmtsk->bind_result($blogID,$blogUser,$blogTitle,$blogContent,$blogBanner,$blogDateTime,$blogStatus);
					$stmtsk->fetch();
					$arr["id"]=$blogID;
					$arr["user_id"]=$blogUser;
					$arr["blogTitle"]=$blogTitle;
					$arr["blogContent"]=$blogContent;
					$arr["blogBanner"]=checks3image($blogBanner,$s3Client);
					$arr["datetime"]=date('M d, Y', strtotime($blogDateTime));
					$arr["publish"]=(($blogStatus==1)?"Y":"N");
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

				$arr["blogUser"]=$commentUser;
				$arr["error"]=false;

				echo json_encode($arr);
				//@mail("support@vervantis.com","A new Blog post created by:".$commentUser,$blogTitle,"From: support@vervantis.com");
				$mailArgs =  array('subject' => 'A new Blog post created by:'.$commentUser,
				    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
				    'toRecipients' => array( array('name' => '', 'address' => "support@vervantis.com") ),     // name is optional
				    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
				    'importance' => 'normal',
				    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
				    'body' => $blogTitle,
				    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
				    'attachments' => array( )
				  );

				custommsmail('noreply@vervantis.com', $mailArgs,'');
				exit();
			}


		}
	}


	echo json_encode(array("error"=>$error));
	exit();
}

if(isset($_POST["edit-post-title"]) and isset($_POST["edit-post"]) and isset($_POST["post-id"]) and trim($_POST["edit-post-title"]) != "" and trim($_POST["edit-post"]) != "" and trim($_POST["post-id"]) != "")
{

	$profile = 'default';

	$s3Client = new S3Client([
		'region'      => 'us-west-2',
		'version'     => 'latest',
		'credentials' => [
				 'key' => $_ENV['aws_access_key_id'],
				 'secret' => $_ENV['aws_secret_access_key']
		 ]
	]);





	$error="Error occured";
	$newPostTitle = @trim($_POST['edit-post-title']);
	$newPost = @trim($_POST['edit-post']);
	$editPostID = @trim($_POST['post-id']);
	//$newPostBanner = $_FILES['new-post-banner'];

	if(isset($_FILES['edit-post-banner']["name"]) and $_FILES['edit-post-banner']["name"] != ""){
		$target_filename = microtime().mt_rand(1000,10000).".png";
		$uploadOk = 1;
		//$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["edit-post-banner"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$error = "File is not an image.";
			$uploadOk = 0;
		}

		// Check if file already exists
		/*if (file_exists($target_file) and $uploadOk == 1) {
			$error = "Sorry, file already exists.";
			$uploadOk = 0;
		}*/
		// Check file size
		/*if ($_FILES["new-post-banner"]["size"] > 500000  and $uploadOk == 1) {
			$error = "Sorry, your file is too large.";
			$uploadOk = 0;
		}*/
		// Allow certain file formats
		/*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif"  and $uploadOk == 1) {
			$error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}*/
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			$resultupload = $s3Client->putObject([
				'Bucket' => 'datahub360-public',
				'Key'    => 'blog/'.$target_filename,
				'SourceFile' => $_FILES["edit-post-banner"]["tmp_name"],
				'ACL'    => 'public-read'
			]);
			if ($resultupload) {
				$sql="UPDATE blog_posts SET user_id='".$mysqli->real_escape_string($user_one)."',post_title='".$mysqli->real_escape_string($newPostTitle)."',post_cont='".$mysqli->real_escape_string($newPost)."',post_banner='".$mysqli->real_escape_string($target_filename)."' WHERE id=".$editPostID;
			}
		}
	}else
		$sql="UPDATE blog_posts SET post_title='".$mysqli->real_escape_string($newPostTitle)."', post_cont='".$mysqli->real_escape_string($newPost)."' WHERE id=".$editPostID;

	if($sql != "")
	{
		$stmt = $mysqli->prepare($sql) ;
		$stmt->execute();
		$lastinsertID=$editPostID;
		if($lastinsertID > 0) {
			$arr=array();
			$commentUser="Noname";

			$stmtsk = $mysqli->prepare('SELECT id,user_id,post_title,post_cont,post_banner,datetime,status FROM blog_posts where id='.$lastinsertID.' LIMIT 1');
			$stmtsk->execute();
			$stmtsk->store_result();
			if ($stmtsk->num_rows > 0) {
				$stmtsk->bind_result($blogID,$blogUser,$blogTitle,$blogContent,$blogBanner,$blogDateTime,$blogStatus);
				$stmtsk->fetch();
				$arr["id"]=$blogID;
				$arr["user_id"]=$blogUser;
				$arr["blogTitle"]=$blogTitle;
				$arr["blogContent"]=base64_encode($blogContent);
				$arr["blogBanner"]=checks3image($blogBanner,$s3Client);
				$arr["datetime"]=date('M d, Y', strtotime($blogDateTime));
				$arr["publish"]=(($blogStatus==1)?"Y":"N");
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

			$arr["blogUser"]=$commentUser;

			$stmtc = $mysqli->query("SELECT id FROM threaded_comments WHERE blog_posts_id=".$editPostID);
			$noComment=$stmtc->num_rows;
			if(!$noComment)
				$noComment=0;

			$arr["noComments"]=$noComment;
			$arr["error"]=false;

			echo json_encode($arr);exit();
		}
	}
	echo json_encode(array("error"=>$error));;
	exit();
}


if(isset($_POST["publishBlog"]) and isset($_POST["blogId"]) and trim($_POST["publishBlog"]) != "" and trim($_POST["blogId"]) != "" and (trim($_POST["publishBlog"]) == "Publish" or trim($_POST["publishBlog"]) == "Unpublish") and trim($_POST["blogId"]) != 0)
{
	$arr=array();
	$error="Error occured. Please try after sometime";
	$publishBlog = @trim($_POST['publishBlog']);
	$blogId = @trim($_POST['blogId']);
	//if($user_one != 1)
	if($_SESSION['group_id'] != 1)
		$sub_condition=" and user_id=".$user_one;
	else
		$sub_condition="";

	if($mysqli->real_escape_string($publishBlog) == "Publish")
		$sql="UPDATE blog_posts SET status='1' WHERE id='".$blogId."' ".$sub_condition;
	elseif($mysqli->real_escape_string($publishBlog) == "Unpublish")
		$sql="UPDATE blog_posts SET status='0' WHERE id='".$blogId."' ".$sub_condition;
	else
		$sql="";

	if($sql != "")
	{
		$stmt = $mysqli->prepare($sql) ;
		$stmt->execute();

		$stmtsk = $mysqli->prepare('SELECT id,status FROM blog_posts where id="'.$blogId.'"'.$sub_condition.' LIMIT 1');
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0) {
			$stmtsk->bind_result($blogID,$status);
			$stmtsk->fetch();
			if(($mysqli->real_escape_string($publishBlog) == "Publish" and $status==1) or ($mysqli->real_escape_string($publishBlog) == "Unpublish" and $status==0))
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

	$stmtsk = $mysqli->prepare('SELECT user_id, post_banner FROM blog_posts where id="'.$deletePostID.'" LIMIT 1');
	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($blogUser,$blogBanner);
		$stmtsk->fetch();

		$stmtskks = $mysqli->prepare('Delete FROM blog_posts where id="'.$deletePostID.'"');
		if($stmtskks){
			$stmtskks->execute();
			$lastcaffectedID=$stmtskks->affected_rows;
			if($lastcaffectedID==1)
			{
				echo json_encode(array('error'=>''));
				//$target_dir = realpath(dirname(__FILE__))."../../uploads/blog/";
				//$target_file = $target_dir . $blogBanner;

				// Check if file already exists
				/*if (file_exists($target_file)) {
					@unlink($targetPath);
				}*/


				$profile = 'default';

				$s3Client = new S3Client([
					'region'      => 'us-west-2',
					'version'     => 'latest',
					'credentials' => [
		           'key' => $_ENV['aws_access_key_id'],
		           'secret' => $_ENV['aws_secret_access_key']
		       ]
				]);

				$info = $s3Client->doesObjectExist('datahub360-public', 'blog/'.$blogBanner);
				if($info)
				{
					$s3Client->deleteObject([
						'Bucket' => 'datahub360-public',
						'Key'    => 'blog/'.$blogBanner
					]);
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
		echo json_encode(array('error'=>'Error Occured! Blog post not found.'));
		exit();
	}

	echo json_encode(array("error"=>$error));;
	exit();
}

//print_r($_POST);
echo false;

function getpresignedurl($object, $bucket = '', $expiration = '')
{
	$bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
	if (empty($bucket)) {
		throw new InvalidDomainNameException('An empty bucket name was given');
	}
	if ($expiration) {
		$command = $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $object]);
		return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
	} else {
		return $this->client->getObjectUrl($bucket, $object);
	}
}

function checks3image($keyname,$s3Client){
	$keyname=@trim($keyname);
	$infotarget = $s3Client->doesObjectExist('datahub360-public', 'blog/'.$keyname);
	if($keyname != "" and $infotarget)
	{
		return "https://datahub360-public.s3-us-west-2.amazonaws.com/blog/".$keyname;
	}else{
		$infotarget = $s3Client->doesObjectExist('datahub360-public','blog/noImage.png');
		if($infotarget)
		{
			return "https://datahub360-public.s3-us-west-2.amazonaws.com/blog/noImage.png";
		}else{

			//logoff
		}
	}
}
?>
