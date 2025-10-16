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

include_once 'psl-config.php';   // Needed because functions.php is not included


$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
	try {
		$mysqli->close();
	}
	catch(Exception $e) {
		echo $mysqli->connect_error; die();
	}
	/*include_once 'functions.php';
	sec_session_start();

	// Unset all session values
	$_SESSION = array();

	// get session parameters
	$params = session_get_cookie_params();

	// Delete the actual cookie.
	setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
			unset($_COOKIE["vervantison"]);
			setcookie ("vervantison","", time() - 2595000);
	// Destroy session
	session_destroy();*/
	@error_log("dbconnectphp dbconn error", 0);
	header("https://".$_SERVER['HTTP_HOST']."/assets/includes/logout.php?error=System error");
    exit();
}
