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
require_once 'db_connect.php';
include_once 'functions.php';

sec_session_start();
if(!isset($_SESSION['user_id'])){
	getout();
	exit();
}
$user_one=$_SESSION['user_id'];

$mysqli->query("UPDATE user_tracking set status='Inactive' WHERE user_id='".$user_one."' and date='".$mysqli->real_escape_string($_SESSION['tracktime'])."'");
$mysqli->query("UPDATE user set last_logoff=now() WHERE user_id='".$user_one."'");

if(isset($_GET['error'])) $errormsg='?error='.$_GET['error'];
else $errormsg='';

getout();

function getout(){
	// Unset all session values
	$_SESSION = array();

	// get session parameters
	$params = session_get_cookie_params();

	// Delete the actual cookie.
	setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	unset($_COOKIE["vervantison"]);
	setcookie ("vervantison","", time() - 2595000);
	// Destroy session
	//$_SESSION = [];
	@session_start();
	@session_unset();
	@session_destroy();
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php'.$errormsg);
	exit();
}
