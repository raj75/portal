<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

if(isset($_POST["uname"]) and @trim($_POST["uname"]) != "" and @trim($_POST["uname"]) != 0 and isset($user_one) and @trim($user_one) != "" and !isset($_POST["getMessage"]) and !isset($_POST["uText"]) and !isset($_POST["sDestroy"]))
{
	$error="Error occured";
	$uname=@trim($_POST["uname"]);
	$arr=array("chat_history"=>[]);
	$stmtsk = $mysqli->prepare("SELECT u.firstname,u.lastname,c.company_name FROM user u inner join company c on u.company_id=c.company_id where u.user_id =$uname");

//("SELECT firstname,lastname FROM user where id =$uname");

	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($uFirstname,$uLastname,$company_name);
		while($stmtsk->fetch()){
			if(@trim($uFirstname) == "" and @trim($uLastname) == "")
				$arr["uname"]="No Name";
			else
				//$arr["uname"]=$uFirstname." ".$uLastname." (".$company_name.")";
				$arr["uname"]=$uFirstname." ".$uLastname;
				$arr["uname_chat"] = $uFirstname." ".$uLastname;
				$arr["cname"] = "(".$company_name.")";
		}
		
		$beforeDate=date("Y-m-d",strtotime(' -5 day'));
		$stmtskk = $mysqli->prepare("SELECT id,user_one,user_two,message,datetime FROM chat where date(datetime) > $beforeDate and recd=1 and (user_one = $user_one || user_two = $user_one) and (user_one = $uname || user_two = $uname) ORDER BY id");
		$stmtskk->execute();
		$stmtskk->store_result();
		if ($stmtskk->num_rows > 0) {
			$stmtskk->bind_result($cID,$cOne,$cTwo,$cMessage,$cDatetime);
			while($stmtskk->fetch()){
				$csName="";
				if($cOne == $user_one)
				{
					$arr["chat_history"][]=array("bName"=>"Me","sName"=>$arr["uname_chat"],"message"=>smiley($cMessage),"datetime"=>$cDatetime);
				}
				elseif($cTwo == $user_one)
				{
					$arr["chat_history"][]=array("bName"=>$arr["uname_chat"],"sName"=>"Me","message"=>smiley($cMessage),"datetime"=>$cDatetime);
				}
			}
		}		
		
		
		$_SESSION["chat_session"][$uname]=true;
		
		$arr["error"]=false;		
		echo json_encode($arr);
		exit();	
	}
	$arr["error"]=$error;		
	echo json_encode($arr);
	exit();	
}

if(isset($_POST["uid"]) and @trim($_POST["uid"]) != "" and @trim($_POST["uid"]) != 0 and isset($user_one) and @trim($user_one) != "" and isset($_POST["uText"]) and @trim($_POST["uText"]) != "" and !isset($_POST["getMessage"]) and !isset($_POST["sDestroy"]))
{
	$error="Error occured";
	$uid=@trim($_POST["uid"]);
	$uText=@trim($_POST["uText"]);
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	$arr=array();
	$stmtsk = $mysqli->prepare("INSERT INTO chat SET user_one=$user_one, user_two='".$mysqli->real_escape_string($uid)."', message='".$mysqli->real_escape_string($uText)."', ip='".$mysqli->real_escape_string($ip)."'");
	$stmtsk->execute();
	$lastinsertID=$stmtsk->insert_id;
	if($lastinsertID > 0) {
		$arr["uText"]=smiley($uText);
		$arr["error"]=false;		
		echo json_encode($arr);
		exit();			
	}

	$arr["error"]=$error;		
	echo json_encode($arr);
	exit();	
}

if(isset($_POST["uname"]) and @trim($_POST["uname"]) != "" and @trim($_POST["uname"]) != 0 and isset($user_one) and @trim($user_one) != "" and isset($_POST["getMessage"]) and !isset($_POST["uText"]) and !isset($_POST["sDestroy"]))
{
	$error="Error occured";
	$uname=@trim($_POST["uname"]);
	if(!isset($_SESSION["chat_session"][$mysqli->real_escape_string($uname)]))
		return false;
	
	$username="";
	$arr=array();
	$stmtsk = $mysqli->prepare("SELECT id,user_one,message,datetime FROM chat where user_one = '".$mysqli->real_escape_string($uname)."' and user_two='".$mysqli->real_escape_string($user_one)."' and recd=0 order by id ASC");
	$stmtsk->execute();
	$stmtsk->store_result();
	if ($stmtsk->num_rows > 0) {
		$stmtsk->bind_result($id,$uOne,$uMessage,$uDatetime);
		
		$stmts = $mysqli->prepare("SELECT firstname,lastname FROM user where user_id ='".$mysqli->real_escape_string($uname)."'");

//("SELECT firstname,lastname FROM user where id ='".$mysqli->real_escape_string($uname)."'");

		$stmts->execute();
		$stmts->store_result();
		if ($stmts->num_rows > 0) {
			$stmts->bind_result($uFirstname,$uLastname);
			while($stmts->fetch()){
				if(@trim($uFirstname) == "" and @trim($uLastname) == "")
					$username="No Name";
				else
					$username=$uFirstname." ".$uLastname;
			}
		}
		
		if(@trim($username) != "")
		{
			while($stmtsk->fetch()){
				$arr["getMessage"][]=array("userone"=>$username,"usertwo"=>((isset($_SESSION["fullname"]) and @trim($_SESSION["fullname"]) != "")?$_SESSION["fullname"]:$_SESSION["email"]),"message"=>smiley($uMessage),"datetime"=>$uDatetime);
			}
	
			$stmtss = $mysqli->prepare("UPDATE chat SET recd=1 WHERE user_one = '".$mysqli->real_escape_string($uname)."' and user_two='".$mysqli->real_escape_string($user_one)."'");
			$stmtss->execute();
			
			$arr["error"]=false;		
			echo json_encode($arr);
			exit();
		}
	}
	$arr["error"]=false;
	$arr["getMessage"]=array();	
	echo json_encode($arr);
	exit();
	//$arr["error"]=$error;		
	//echo json_encode($arr);
	//exit();	
}

if(isset($_POST["uname"]) and @trim($_POST["uname"]) != "" and @trim($_POST["uname"]) != 0 and isset($user_one) and @trim($user_one) != "" and !isset($_POST["getMessage"]) and !isset($_POST["uText"]) and isset($_POST["sDestroy"]))
{
	$error="Error occured";
	$uname=@trim($_POST["uname"]);
	if(isset($_SESSION["chat_session"][$mysqli->real_escape_string($uname)]))
	{
		unset($_SESSION["chat_session"][$mysqli->real_escape_string($uname)]);
		echo true;
		exit();
	}
}


echo false;

function smiley($msg) { 
	$msg = str_replace(":)","<img src=\"assets/img/smileys/smile53890.gif\" alt=\":)\" >", $msg);
    $msg = str_replace(":\(","<img src=\"assets/img/smileys/sad54749.gif\" alt=\":\(\" >", $msg); 
    $msg = str_replace(":D","<img src=\"assets/img/smileys/bigsmile54781.gif\" alt=\":D\" >", $msg); 
    $msg = str_replace(":p","<img src=\"assets/img/smileys/wink54827.gif\" alt=\":p\" >", $msg); 
    return $msg; 
}
?>