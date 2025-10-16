<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once 'db_connect.php';
include_once 'functions.php';
define('NONCE_SECRET', 'jvTGophoRAJ08Pqw9Hej');
require_once('../plugins/NonceUtil.php');

sec_session_start(); // Our custom secure way of starting a PHP session.

$refreshme='';

if (isset($_POST['email']) and !isset($_POST['csrf-token']) and isset($_POST['encsi']) and NonceUtil::check(NONCE_SECRET, $_POST['encsi']) === true) {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	if(isset($_COOKIE['refresh'])){unset($_COOKIE['refresh']);}
  if(isset($_POST['refresh']) and $_POST['refresh']=='Yes'){setcookie("refresh", "yes", (time() + (60 * 10)),"/");}

	if(isset($_COOKIE['alwaysin'])){unset($_COOKIE['alwaysin']);}
  if(isset($_POST['alwaysin']) and $_POST['alwaysin']==true){setcookie("alwaysin", "yes", (time() + (60 * 10)),"/");}

	if($stmt = $mysqli->prepare("SELECT u.email,c.company_id,c.idp_entity_id_azure, c.idp_sso_url_azure, c.idp_slo_url_azure, c.idp_x509cert_azure,idp_entity_id_okta,idp_sso_url_okta,idp_slo_url_okta,idp_x509cert_okta FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE email='".$mysqli->real_escape_string($email)."' LIMIT 1 "))
	{
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows  > 0) {
			$stmt->bind_result($uemail,$cid,$idp_entity_id_azure,$idp_sso_url_azure,$idp_slo_url_azure,$idp_x509cert_azure,$idp_entity_id_okta,$idp_sso_url_okta,$idp_slo_url_okta,$idp_x509cert_okta);
			$stmt->fetch();
			if(isset($_COOKIE['passwordform'])) unset($_COOKIE['passwordform']);
			setcookie("passwordform", $uemail, (time() + (60 * 10)),"/");
			if((!empty($idp_entity_id_azure) and !empty($idp_sso_url_azure) and !empty($idp_slo_url_azure) and !empty($idp_x509cert_azure)) or (!empty($idp_entity_id_okta) and !empty($idp_sso_url_okta) and !empty($idp_slo_url_okta) and !empty($idp_x509cert_okta)))
			{
				if(isset($_COOKIE['step2sso'])){unset($_COOKIE['step2sso']);}
				if(isset($_COOKIE['step2p'])){unset($_COOKIE['step2p']);}
				setcookie("step2sso", "yes", (time() + (60 * 10)),"/");
				echo true;
			}
			else{
				if(isset($_COOKIE['step2p'])){unset($_COOKIE['step2p']);}
				if(isset($_COOKIE['step2sso'])){unset($_COOKIE['step2sso']);}
				setcookie("step2p", "yes", (time() + (60 * 10)),"/");
				echo "pw";
			}
			exit();
		}else{
			if(isset($_COOKIE['step2sso'])){unset($_COOKIE['step2sso']);}
			if(isset($_COOKIE['step2p'])){unset($_COOKIE['step2p']);}
			if(isset($_COOKIE['passwordform'])) unset($_COOKIE['passwordform']);
			if(isset($_COOKIE['refresh'])){unset($_COOKIE['refresh']);}
			if(isset($_COOKIE['alwaysin'])){unset($_COOKIE['alwaysin']);}
			echo "no";
		}
	}else {
		if(isset($_COOKIE['step2sso'])){unset($_COOKIE['step2sso']);}
		if(isset($_COOKIE['step2p'])){unset($_COOKIE['step2p']);}
		if(isset($_COOKIE['passwordform'])) unset($_COOKIE['passwordform']);
		if(isset($_COOKIE['refresh'])){unset($_COOKIE['refresh']);}
		if(isset($_COOKIE['alwaysin'])){unset($_COOKIE['alwaysin']);}
		echo false;
	}
	exit();
}elseif (isset($_POST['email']) and isset($_POST['csrf-token']) and isset($_POST['encsi']) and NonceUtil::check(NONCE_SECRET, $_POST['encsi']) === true) {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$clientip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$clientip = $_SERVER['REMOTE_ADDR'];
	}

    if($stmt = $mysqli->prepare("SELECT date FROM `user_tracking` where ipaddress='".$mysqli->real_escape_string($clientip)."' LIMIT 1 "))
	{
        $stmt->execute();
        $stmt->store_result();
		if ($stmt->num_rows == 0) {
			if(!isset($_POST['captcha'])){
				echo "wrongcaptcha";
				exit();
			}else{
				$secret="6LdcblUUAAAAAC9o7_KznWBb7AZJnCfhIdOmcTzV";
				$response=$_POST["captcha"];

				$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response);
				$captcha_success=json_decode($verify);
				if ($captcha_success->success==false) {
					echo "wrongcaptcha";
					exit();
				}
			}
		}
	}else { echo false; }

  $refreshme='N';
  if(isset($_COOKIE['refresh'])){unset($_COOKIE['refresh']);}
  if(isset($_POST['refresh']) and $_POST['refresh']=='Yes'){setcookie("refresh", "yes", (time() + (60 * 10)),"/");$refreshme='Y';}

	if(isset($_COOKIE['alwaysin'])){unset($_COOKIE['alwaysin']);}
  if(isset($_POST['alwaysin']) and $_POST['alwaysin']==true){setcookie("alwaysin", "yes", (time() + (60 * 10)),"/");}

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = $_POST['csrf-token']; // The hashed password.

	$checklogin=login($email, $password, $mysqli,$refreshme);
    if ($checklogin == true) {
        // Login success
        //header("Location: ../protected_page.php");
		echo true;
        //exit();
    } else if($checklogin=="changepwd"){
		echo "changepwd";
	}else if($checklogin=="duplicatelog"){
		echo "duplicatelog";
	}else if($checklogin=="pwreset"){
		echo "pwreset";
	}else if($checklogin=="blocked"){
		echo "blocked";
	}else if($checklogin=="inactive"){
		echo "inactive";
	}else{
        // Login failed
        //header('Location: ../index.php?error=1');
		echo false;
       // exit();
    }
}elseif(isset($_POST['cname']) and isset($_POST['saml'])){
	if(empty(@trim($_POST['cname']))){echo json_encode(array("error"=>"Please enter company name!")); exit(); }
	$cname= @trim(strtolower($_POST['cname']));

	if($stmt = $mysqli->prepare("SELECT company_id FROM `company` where TRIM(LOWER(company_name))='".$mysqli->real_escape_string($cname)."' LIMIT 1 "))
	{
				$stmt->execute();
				$stmt->store_result();
		if($stmt->num_rows  > 0) {
			$stmt->bind_result($cid);
			$stmt->fetch();
			echo json_encode(array("cid"=>$cid,"error"=>false));
			exit();
		}else{ echo json_encode(array("error"=>"Error occured. Please contact Vervantis!"));}
	}else { echo false;}
	exit();
} else {
    // The correct POST variables were not sent to this page.
    header('Location: ../../index.php');
	//echo false;
    exit();
}
