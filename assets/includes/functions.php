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
include_once 'psl-config.php';

function sec_session_start() {//die();
	$kickit=0;
	$samlNameId="";
	$samlUserdata=array();

	/////////Added Later
	$secure = true;

	///////////////////Added Later
	if(isset($_SESSION) and isset($_SESSION['samlNameId']) and isset($_SESSION['samlUserdata']) and !empty($_SESSION['samlNameId']) and !empty($_SESSION['samlUserdata'])){
		$samlNameId=@trim(htmlentities($_SESSION['samlNameId']));
		$samlUserdata=$_SESSION['samlUserdata'];
		//$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

		$_SESSION = array();
		$params = session_get_cookie_params();
		setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], true, $params["httponly"]);
		unset($_COOKIE["vervantison"]);
		setcookie ("vervantison","", time() - 2595000);
		session_destroy();

	}
	///////////////////







	if(isset($_SESSION) and !isset($_SESSION['samlNameId']) and count($_SESSION)){
		if(!isset($_SESSION['ipaddress']) || !isset($_SESSION['useragent']))
			$kickit=1;

		if (isset($_SESSION['ipaddress']) and isset($_SESSION['REMOTE_ADDR'])  and $_SESSION['ipaddress'] != $_SERVER['REMOTE_ADDR'])
			$kickit=1;

		if(isset($_SESSION['useragent']) and isset($_SESSION['HTTP_USER_AGENT'])  and  $_SESSION['useragent'] != $_SERVER['HTTP_USER_AGENT'])
			$kickit=1;
	}

	if($kickit==1){

		$user_one=$_SESSION['user_id'];
		if(!isset($mysqli)){

			$mysqli_temp = new mysqli(HOST, USER, PASSWORD, DATABASE);

			if ($mysqli_temp->connect_error) {
				try {
					$mysqli_temp->close();
				}
				catch(Exception $e) {

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
				@error_log("functionsphp dbconn error", 0);
				header("https://".$_SERVER['HTTP_HOST']."/assets/includes/logout.php?error=System error");
				exit();
			}


			$mysqli_temp->query("UPDATE user_tracking set status='Inactive' WHERE user_id='".$user_one."' and date='".$mysqli_temp->real_escape_string($_SESSION['tracktime'])."'");

			$mysqli_temp->close();
			// Unset all session values
			$_SESSION = array();

			// get session parameters
			$params = session_get_cookie_params();

			// Delete the actual cookie.
			//setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
			setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], true, $params["httponly"]);
					unset($_COOKIE["vervantison"]);
					setcookie ("vervantison","", time() - 2595000);
			// Destroy session
			@session_destroy();
			//ob_start();
			@error_log("functionsphp user tracking inactive error", 0);
			header("https://".$_SERVER['HTTP_HOST']."/assets/includes/logout.php?error=System error");


			exit();
		}
	}

    //$session_name = 'frucksec_session_id';   // Set a custom session name
    $session_name = 'fnjnfszoow293bsds';   // Set a custom session name
   // $secure = SECURE;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    /*if (ini_set('session.use_only_cookies', 1) === FALSE) {
        //header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php?error=System Error');
        exit();
    }*/


	//setcookie ("vervantison",time(), time() - 2595000);

//echo (isset($_POST['alwaysin']) and $_POST['alwaysin'] == 'true')?123:56;
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    //session_set_cookie_params(((isset($_POST['alwaysin']) and $_POST['alwaysin']==true)?(time() + 2592000):$cookieParams["lifetime"]), $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	if(isset($_POST['alwaysin']) and $_POST['alwaysin'] == 'true'){
		$ttime=(time() + 2592000);
		setcookie("vervantison", "because", time() + 2592000, "/");
	}elseif(isset($_COOKIE["vervantison"]) and $_COOKIE["vervantison"] =="because"){
		setcookie ("vervantison","", time() - 2595000);
			//$past = time() - 100;
		//setcookie("vervantison", "avs", $past);
		$ttime=(time() + 2592000);
	}else{
		//unset($_COOKIE["vervantison"]);
		//setcookie ("vervantison",time(), time() - 2595000);
		$ttime=$cookieParams["lifetime"];
	}

		/*if(isset($_SESSION)){
			$_SESSION = array();
			$params = session_get_cookie_params();
			setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], true, $params["httponly"]);
			unset($_COOKIE["vervantison"]);
			setcookie ("vervantison","", time() - 2595000);
			session_destroy();
		}*/
    @session_set_cookie_params($ttime, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

    // Sets the session name to the one set above.
    @session_name($session_name);

  	@session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
	//print_r($_COOKIE);

	if(!empty($samlNameId)) $_SESSION['samlNameId']=$samlNameId;
	//if(!empty($samlUserdata)) $_SESSION['samlUserdata']=$samlUserdata;
}

function login($email, $password, $mysqli, $refreshme) {
	//temporary
	//if(@trim($email) != "admin@vervantis.com") return false;




    // Using prepared statements means that SQL injection is not possible.
    if($stmt = $mysqli->prepare("SELECT user_id,email,password,salt,gender,usergroups_id,firstname,lastname,company_id,status,disable_date,`failed_password_attempts` FROM user WHERE email = '".$mysqli->real_escape_string($email)."' LIMIT 1 "))

	{
        //$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($user_id, $email, $db_password, $salt, $gender, $group_id, $firstname, $lastname,$companyid, $ustatus, $disabledate, $nofailedattempts);
        $stmt->fetch();

        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);//echo $db_password.":".$password;
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
								++$nofailedattempts;
								if (!$mysqli->query("UPDATE user set status=2, `failed_password_attempts`=".$nofailedattempts.", disable_date= (NOW() + INTERVAL 10 MINUTE) WHERE user_id='".$user_id."'")) {
										@mail("support@vervantis.com","user status update failed","User ID:".$user_id."  status update failed");
										@error_log("functionsphp user status update failed $user_id", 0);
								}

                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
									if($ustatus==3){
										echo "pwreset";
										exit();
									}elseif($ustatus==2){
										echo "blocked";
										exit();
									}elseif($ustatus==0){
										echo "inactive";
										exit();
									}

                    // Password is correct!


					$oldpass_stmt = $mysqli->prepare("SELECT date FROM `user_tracking` ut, user u where ut.user_id=u.user_id and ut.password=u.password and ut.date <= (NOW() - INTERVAL 90 DAY) and ut.user_id='".$user_id."' order by ut.date limit 1");

					if ($oldpass_stmt) {
						//$stmt->bind_param('s', $pkey);
						$oldpass_stmt->execute();
						$oldpass_stmt->store_result();
						if ($oldpass_stmt->num_rows > 0) {
							echo "changepwd";
							exit();
						}

					}else return false;


					if($refreshme=='Y'){
						if (!$mysqli->query("UPDATE user_tracking set status='Inactive' WHERE user_id='".$user_id."'")) {
							return false;
							exit();
						}

					}else{
//"SELECT date FROM `user_tracking` ut, user u where ut.user_id=u.user_id and ut.user_id='".$user_id."' and ut.status='Active' limit 1"
						$checklog_stmt = $mysqli->prepare("SELECT date FROM `user_tracking` ut, user u where ut.user_id=u.user_id and ut.user_id='".$user_id."' and ut.status='Active' and u.last_login>(NOW() - INTERVAL 10 MINUTE) limit 1");

						if ($checklog_stmt) {
							//$stmt->bind_param('s', $pkey);
							$checklog_stmt->execute();
							$checklog_stmt->store_result();
							if ($checklog_stmt->num_rows > 0) {
								echo "duplicatelog";
								exit();
							}

						}else{return false;}
					}

                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    $_SESSION['email'] = $email;
					$_SESSION['gender'] = $gender;
					$_SESSION['fullname'] = $firstname." ".$lastname;
					$_SESSION['group_id'] = $group_id;
					$_SESSION['company_id'] = $companyid;
					$_SESSION['user_browser'] = $user_browser;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					$_SESSION['tracktime'] = @date("Y-m-d H:i:s");
					$_SESSION['ipaddress'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['useragent'] = $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['newuser'] = 'Yes';

                    //$now = time();
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$clientip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$clientip = $_SERVER['REMOTE_ADDR'];
					}
					$referrer= @$_SERVER['HTTP_REFERER'];

					$json = @trim(@file_get_contents('https://get.geojs.io/v1/ip/geo/'.$clientip.'.json'));
					$data = @json_decode($json,true);
					if(isset($data['timezone'])) $_SESSION['timezone'] = $data['timezone'];
					else $_SESSION['timezone'] = '';

					$clientbrowser=get_browser(null,true);

					if(isset($clientbrowser["parent"])) $cbrowser=$clientbrowser["parent"];
					else $cbrowser="unknown";

					if(isset($clientbrowser["platform_description"])) $cos=$clientbrowser["platform_description"];
					else $cos="unknown";

					if(isset($clientbrowser["device_type"])) $cdtype=$clientbrowser["device_type"];
					else $cdtype="unknown";

					if(isset($clientbrowser["crawler"])) $ccrawl=$clientbrowser["crawler"];
					else $ccrawl="";

					$ccountry=$cregion=$ccity=$cisp="unknown";
					$cquery = @unserialize (file_get_contents('http://ip-api.com/php/'.$clientip));
					if ($cquery && $cquery['status'] == 'success') {
						$ccountry=$cquery['countryCode'];
						$cregion=$cquery['regionName'];
						$ccity=$cquery['city'];
						$cisp=$cquery['isp'];
					}

					$caddr=$ccity.", ".$cregion.", ".$ccountry;

					if($caddr=="unknown, unknown, unknown") $caddr="unknown";

//,useragent='".$mysqli->real_escape_string($user_browser)."'
                    if (!$mysqli->query("INSERT INTO user_tracking set user_id='".$user_id."', ipaddress='".$mysqli->real_escape_string($clientip)."',referrer='".$mysqli->real_escape_string($referrer)."',password='".$mysqli->real_escape_string($password)."',date='".$mysqli->real_escape_string($_SESSION['tracktime'])."',status='Active',useragent='".$mysqli->real_escape_string($_SESSION['useragent'])."',browser='".$mysqli->real_escape_string($cbrowser)."',operating_system='".$mysqli->real_escape_string($cos)."',estimated_location='".$mysqli->real_escape_string($caddr)."',isp='".$mysqli->real_escape_string($cisp)."',device_type='".$mysqli->real_escape_string($cdtype)."',crawler='".$mysqli->real_escape_string($ccrawl)."'")) {
                        //header("Location: ../error.php?err=Database error: login_attempts");
							@error_log("functionsphp user tracking insert failed", 0);
						header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System Error');
                        exit();
                    }
//die( "SELECT DISTINCT ut.password FROM `user_tracking` ut where ut.user_id='".$user_id."'");
					$new_stmt = $mysqli->prepare("SELECT DISTINCT ut.password FROM `user_tracking` ut where ut.user_id='".$user_id."'");

					if ($new_stmt) {
						//$stmt->bind_param('s', $pkey);
						$new_stmt->execute();
						$new_stmt->store_result();
						if ($new_stmt->num_rows > 1) {//echo "12345";
							$_SESSION['newuser'] = 'No';
						}
					}else{
						@error_log("functionsphp user tracking select failed", 0);
						header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System Error');
                        exit();
					}
										if ( !$mysqli->query("UPDATE user set `failed_password_attempts`=0 WHERE user_id='".$user_id."'")) {
												@mail("support@vervantis.com","user failed_password_attempts nil failed","User ID:".$user_id."  user failed_password_attempts nil failed");
												@error_log("functionsphp user failed_password_attempts nil failed $user_id", 0);
										}
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$clientip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$clientip = $_SERVER['REMOTE_ADDR'];
					}
                    if (!$mysqli->query("INSERT INTO login_attempts(user_id, ipaddress)
                                    VALUES ('$user_id', '$clientip')")) {
                        //header("Location: ../error.php?err=Database error: login_attempts");
						header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');
                        exit();
                    }

                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    } else {
        // Could not create a prepared statement
        //header("Location: ../error.php?err=Database error: cannot prepare statement");
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');
        exit();
    }
	exit();
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time
    //$now = time();

    // All login attempts are counted from the past 2 hours.
    //$valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND datetime > (NOW() - INTERVAL 10 MINUTE)")) {

//    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND datetime > (NOW() - INTERVAL 1 hour)")) {


        $stmt->bind_param('i', $user_id);

        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins
        if ($stmt->num_rows > 3) {
            return true;
        } else {
            return false;
        }
    } else {
        // Could not create a prepared statement
        //header("Location: ../error.php?err=Database error: cannot prepare statement");
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');
        exit();
    }
}

function login_check($mysqli) {
    // Check if all session variables are set
    if (isset($_SESSION['user_id'], $_SESSION['email'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $email = $_SESSION['email'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password FROM user WHERE user_id = ? LIMIT 1")) {

//("SELECT password FROM user WHERE id = ? LIMIT 1")) {

            // Bind "$user_id" to parameter.
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Could not prepare statement
            //header("Location: ../error.php?err=Database error: cannot prepare statement");
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');
            exit();
        }
    } else {
        // Not logged in
        return false;
    }
}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function audit_log($mysqli,$tablename="",$activity="",$newvalue=array(),$conditions="",$filenew="",$fileold="",$primarykey="id")
{//print_r($newvalue);
	if($mysqli=="" or $tablename == "" or $activity == "" or empty($primarykey) or ($activity=="INSERT" and count($newvalue)==0) or ($activity=="UPDATE" and count($newvalue)==0) or ($activity=="UPDATE" and $conditions=="") or ($activity=="DELETE" and $conditions==""))
	{return "Error";exit();}

	$oldvalue=array();
	$aid=0;

	if($activity != "INSERT" and $conditions != "")
	{
		if($activity=="UPDATE"){
			$query='SELECT `'.$primarykey.'`,'.implode(",",array_keys($newvalue)).' FROM '.$tablename;
		}elseif($activity=="DELETE"){
			$query='SELECT * FROM '.$tablename;
		}else{return "Error";exit();}

		$query .= ' '.$conditions;
		$stmt_1 = $mysqli->query($query);
		if($stmt_1){
			if ($stmt_1->num_rows > 0)
			{
				if($activity=="DELETE"){
					$finfo=$newvalue=array();
					$finfo = $stmt_1->fetch_fields();
					foreach ($finfo as $val) {
						$newvalue[$val->name]=$val->name;
					}
				}

				$row=$stmt_1->fetch_row();
				if(count($newvalue) == 0 or (count($newvalue) !=0 and count($newvalue) != count($row) and $activity=="DELETE") or (count($newvalue) !=0 and count($newvalue) != (count($row) - 1) and $activity=="UPDATE")){return "Error";exit();}

				$aid=$row[0];
				array_shift($row);
				foreach(array_keys($newvalue) as $ky=>$vl){
					$oldvalue[$vl]=$row[$ky];
				}

				if($activity=="DELETE"){$newvalue=array();}
			}else{return "Error";exit();}
		}else{return "Error";exit();}
	}

	if((count($oldvalue) != 0 and count($newvalue) != 0 and count($oldvalue) != count($newvalue)) or $aid == 0)
	{return "Error";exit();}
//print_r($oldvalue);print_r($newvalue);
	$results = array_diff($oldvalue, $newvalue);
	//print_r($results);
	$diff = array();
	foreach($results as $k => $v)
	{
			$diff[] = array('title' => $k,
							'old' => $v,
							'new' => $newvalue[$k]);
	}

	if($filenew != ""){
		$diff[] = array('file' => "Y",//$k,
						'old' => $fileold,
						'new' => $filenew);
	}
//print_r($diff);
//print_r(serialize($diff));
	if(count($diff) > 0 and $aid > 0)
	{
		$user_session=session_id();
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$user_id=$_SESSION['user_id'];
		$sql='INSERT INTO audit_log SET edited_value="'.@$mysqli->real_escape_string(@base64_encode(@serialize($diff))).'",session_id="'.$user_session.'",table_name="'.@$mysqli->real_escape_string($tablename).'",table_row_id="'.@$mysqli->real_escape_string($aid).'",pk_name="'.@$mysqli->real_escape_string($primarykey).'",ip_address="'.$ipaddress.'",user_id="'.$user_id.'",activity="'.@$mysqli->real_escape_string($activity).'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){

			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){return "";exit();}
		}

		$sql='INSERT INTO audit_error SET session_id="'.$user_session.'",table_name="'.@$mysqli->real_escape_string($tablename).'",ip_address="'.$ipaddress.'",user_id="'.$user_id.'",activity="'.@$mysqli->real_escape_string($activity).'"';
		$stmt_3 = $mysqli->prepare($sql);
		if($stmt_3){
			$stmt_3->execute();
		}
	}
}

function audit_look($mysqli,$tablename="",$tablerowid="",$fieldname="")
{
	if(@trim($tablename) == "" or @trim($tablerowid) == "" or @trim($tablerowid) == 0 or @trim($fieldname) == "")
	{return "Error";exit();}

	$old_value_arr=$editedvalarr=array();

	if ($stmt = $mysqli->prepare('SELECT edited_value FROM `audit_log` where table_name="'.$tablename.'" and table_row_id="'.$tablerowid.'"')) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($EditedValue);
			while($stmt->fetch()) {
				$editedval=$z="";
				$editedvalarr=unserialize(base64_decode($Editedvalue));
				$z=count($editedvalarr);
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and @trim($editedvalarr[$i]["title"]) == @trim($fieldname)){
						$editedval .= "<b>Title:</b> ".$editedvalarr[$i]["title"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
					}
					if(isset($editedvalarr[$i]["file"])){
						$editedval .= "<b>File:</b> ".$editedvalarr[$i]["file"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
					}
					if($i<$z){$editedval .= "<hr style='margin:2px' />";}
				}
			}
		}
	}
}

function do_encrypt($text, $salt = "foihibajxinssnsdo")
{
	//return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function do_decrypt($text, $salt = "foihibajxinssnsdo")
{
	//return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

	if($phoneNumber==0) return "";
	else if(strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree.'-'.$lastFour;
    }

    return $phoneNumber;
}


function ed_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'yf642d7#%&gvvh';
    $secret_iv = '33485609';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}


function checkpermission($mysqli,$pgid=0){
    if(!isset($_SESSION["user_id"])) {header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');exit();}
    else{$user_one=$_SESSION["user_id"];}

    if($pgid==0 or $pgid=="" or $mysqli=="") return false;

    if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){return true;}
    elseif($_SESSION["group_id"]==4){return true;}
    elseif($_SESSION["group_id"]==3 || $_SESSION["group_id"]==5){

        $colomnsarr=array(0=>array('`Futures Pricing`','`Locational Marginal Pricing`','`Live Weather`','`Streaming News`','`Verve Energy Blog`','`Market Commentary`','`Weekly Reports`','`Direct Access Information`','`Strategy`','`Dynamic Risk Management`','`Master Supply Agreements`','`Supplier Contracts`','`Utility Requirements`','`Regulated Information`','`Utility Rate Reports`','`Utility Rate Change Requests`','`Start New Service`','`Stop Service`','`StartStop Status`','`Correspondence`','`UBM Archive`','`UBM Software`','`Utility Budgets`','`Invoice Validation`','`Exception Reports`','`Resolved Exceptions`','`Site and Account Changes`','`Data Analysis`','`Consumption Reports`','`Custom Reports`','`Benchmark Report`','`CSR ESG Software`','`Sustainability Reports`','`Corporate Reports`','`Surveys`','`Distributed Generation`','`Efficiency Upgrades`','`EV Charging`','`Rebates and Incentives`','`Other`','`Site List`','`Vendors`','`Accounts`','`Invoices`','`Users Edit`','`User Permissions`','`Company Defaults`','`Chat`','`Account Report`','`Vendor Report`','`Processed Invoice Report`','`Accrual Report`','`Invoice Audit`','`Monthly Weather`','`Deposit Late Fee Report`','`Cost and Usage Report`','`GHG Report`','`Site Inventory`'),
            1=>array(48,59,52,60,2,3,102,31,42,46,54,55,56,30,41,61,49,50,51,58,114,35,37,36,62,63,64,33,65,34,78,38,39,74,75,40,66,67,97,69,9,92,93,80,12,76,57,105,117,119,120,122,126,127,128,129,130,131));

        if(!in_array($pgid,$colomnsarr[1])) return false;
        $kyofid = array_search($pgid,$colomnsarr[1]);
        if($kyofid === false) return false;


        $csql='SELECT '.$colomnsarr[0][$kyofid].' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';

        $usql='SELECT '.$colomnsarr[0][$kyofid].' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';


        $c_permission=0;

        $u_permission=0;


        if ($cstmt = $mysqli->prepare($csql)) {
            $cstmt->execute();
            $cstmt->store_result();
            if ($cstmt->num_rows > 0) {
                $cstmt->bind_result($c_permission);

                $cstmt->fetch();
            }
        }


        if ($ustmt = $mysqli->prepare($usql)) {
            $ustmt->execute();
            $ustmt->store_result();
            if ($ustmt->num_rows > 0) {
                $ustmt->bind_result($u_permission);

                $ustmt->fetch();
            }
        }

        if($c_permission==1 and $u_permission==1) return true;
        else return false;
    }
    return false;
}

function showmeversion($primarykey,$tablename,$fieldname,$htmltagid,$urlcalled,$eventtype='onmouseover'){
	if(empty($primarykey) or empty($tablename) or empty($fieldname) or empty($htmltagid) or empty($urlcalled)) return "";
	$ts=rand(1,9).rand(20,50).rand(99,999).rand(45,77);
	return '<a href="javascript:void(0);" class="showversion-link" onclick="showversion(\''.$primarykey.'\',\''.$tablename.'\',\''.$fieldname.'\',\''.$ts.'\',\''.$htmltagid.'\',\''.$urlcalled.'\')" id="'.$ts.'"><i class="fa fa-refresh" aria-hidden="true"></i></a><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p'.$ts.'">&nbsp;</a>';
}


//Show Logs
function showlogsdirect($mysqli,$primarykey,$tablename,$fieldnamearr=array())
{
	if(empty($primarykey) or empty($tablename)) return "";

	//$fieldnamearr=array("site_number"=>"Site Number","location_type"=>"Location Type","site_name"=>"Site Name","region"=>"Region","entity_name"=>"Entity Name","division"=>"Division","federal_tax_id"=>"Tax ID","gl_site"=>"GL Site","site_address1"=>"Site Address1","account_address1"=>"Account Address1","site_address2"=>"Site Address2","account_address2"=>"Account Address2","site_city"=>"Site City","account_city"=>"Account City","site_state"=>"Site State","account_state"=>"Account State","site_zip"=>"Site Zip","account_zip"=>"Account Zip","site_contact_name"=>"Site Contact Name","billing_address1"=>"Billing Address1","site_contact_title"=>"Site Contact Title","billing_address2"=>"Billing Address2","site_contact_telephone"=>"Contact Telephone","billing_city"=>"Billing City","site_contact_fax"=>"Contact Fax","billing_state"=>"Billing State","billing_zip"=>"Billing Zip","leased_location"=>"Leased Location","landlord_name"=>"Landlord Name","lease_start_date"=>"Lease Start Date","landlord_phone"=>"Contact Number","lease_end_date"=>"Lease End Date","landlord_fax"=>"Contact FAX","tenant"=>"Tenant","sale_date"=>"Purchase Date","landlord_email"=>"Contact Email","sublet"=>"Sublet","landlord_address1"=>"Landlord Address1","landlord_address2"=>"Landlord Address2","owned_location"=>"Owned Location","landlord_city"=>"Landlord City","landlord_state"=>"Landlord State","sale_owner"=>"Previous Owner","landlord_zip"=>"Landlord Zip","date_requested"=>"Date Requested","deposit_preference"=>"Deposit Preference","construction"=>"Construction","check_deposit_ok"=>"Check Deposit Ok","meter_change"=>"New Meters Required","credit_card_deposit_ok"=>"Credit Card Deposit Ok","utility_service_type"=>"Utility Service Type","vendor_name"=>"Vendor Name","account_number"=>"Account Name","previous_account_number"=>"Prev Account Number","meter"=>"Meter","special_instructions"=>"Special Instructions","status"=>"Status","date_completed"=>"Date Completed","request_type"=>"Request Type","date_contacted"=>"Date Contacted","contacted_method"=>"Contacted Method","confirmation_number"=>"Confirmation Number","deposit"=>"Deposit","deposit_method"=>"Deposit Method","billing_cycle"=>"Billing Cycle","notes"=>"Notes","vendor_phone1"=>"Vendor Phone 1","vendor_phone2"=>"Vendor Phone 2","vendor_email1"=>"Vendor Email 1","vendor_email2"=>"Vendor Email 2","vendor_fax1"=>"Vendor Fax 1","vendor_fax2"=>"Vendor Fax 2");



	$error="Error occured";
	$sub_query=$new_value=$finfo=$editedvalarr=array();
	$z=$t=$v=$editedvalue=$zz="";
	$l=1;

	$old_value_arr=$editedvalarr=array();

	if ($stmt = $mysqli->query('SELECT distinct a.modified,u.usergroups_id,u.firstname,u.lastname,a.edited_value,a.activity,a.status,a.id,a.user_id,c.company_name FROM `audit_log` a,user u,company c where a.table_name="'.$tablename.'" and a.table_row_id="'.$primarykey.'" and a.user_id=u.user_id and u.company_id=c.company_id ORDER BY a.modified DESC LIMIT 40')) {
        if ($stmt->num_rows > 0) {

			$kk="";
			while($row=$stmt->fetch_row()) {
				$editedvalue=$row[4];
				if($editedvalue=="")
				{
					echo "";
					exit();
				}

				$utype="";
				if($row[1]==1) $utype="Vervantis Admin";
				if($row[1]==2) $utype="Vervantis Employee";
				if($row[1]==3) $utype="Client";
				if($row[1]==4) $utype="Client Admin";

				$fullname=$row[2]." ".$row[3]." (".$row[9].")";

				$editedvalarr=unserialize(base64_decode($editedvalue));
				$z=count($editedvalarr);


				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"])){

						$fldname=$editedvalarr[$i]["title"];
						if(count($fieldnamearr) and in_array($fldname,$fieldnamearr)){
							$fldname=$fieldnamearr[$fldname];
						}


						$edv=$editedvalarr[$i]["old"];
						$zz = $zz."<tr><td class=\"nodis\">".$row[0]."</td><td>".date("M d,Y h:i:s A", strtotime($row[0]))."</td><td>".$fldname."</td><td>".$edv."</td>";
						if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2) $zz=$zz."<td>".$row[5]."</td>";
						$zz=$zz."<td>".$fullname."</td></tr>";
						/*if($row[2]=="UPDATE"){
							if($row[3] != 1){
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'rollback\''.$kk.')" title="Roll Back" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></button>';
							}
							if($row[3] != 2){
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'forward\''.$kk.')" title="forward" class="btn btn-xs btn-default"><i class="fa fa-share"></i></button>';
							}
						}
						$zz = $zz."</td></tr>";*/
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
		echo "";
		exit();
	}
if($zz != ""){
$returnit="
<style>
#logsshow_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#logsshow_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#logsshow{border-bottom: 1px solid #ccc !important;}}
#logsshow .sssdrp{width:auto !important;}
#logsshow .sssdrp {
    font-weight: 400 !important;
}
#logsshow .nodis{display:none !important;}
</style>
		<article class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">
			<link href=\"/assets/css/jquery.multiSelect.css\" rel=\"stylesheet\" type=\"text/css\" />
			<link rel=\"stylesheet\" href=\"//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css\">
			<link href=\"https://".$_SERVER['HTTP_HOST']."/assets/plugins/datatables1.11.3/datatables.min.css\" rel=\"stylesheet\" type=\"text/css\" />
			<div class=\"jarviswidget jarviswidget-color-blueDark\" id=\"wid-id--1\" data-widget-editbutton=\"false\">
				<header>
					<span class=\"widget-icon\"> <i class=\"fa fa-table\"></i> </span>
					<h2>Logs</h2>
				</header>
				<div class=\"row\">";
$returnit=$returnit."<table class='table table-bordered table-striped' id='logsshow'><thead><tr><th class=\"nodis\">DefaultDate</th><th>Date</th><th>Field Name</th><th>Previous Value</th>".(($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2)?"<th>Action</th>":"")."<th>By User</th></tr></thead><tbody>".$zz."</tbody></table>";

$returnit=$returnit."
				</div>
			</div>
		</article>

<script type=\"text/javascript\">

	pageSetUp();


	// pagefunction
	var pagefunction = function() {
		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otable = $(\"#logsshow\").DataTable( {
				\"lengthMenu\": [[1, 2, 3, 4, 5, 6, 12, 25, -1], [1, 2, 3, 4, 5, 6, 12, 25, \"All\"]],
				\"pageLength\": 12,
				\"retrieve\": true,
				\"scrollCollapse\": true,
				\"searching\": true,
				\"paging\": true,
				\"dom\": 'Blfrtip',
				\"order\": [[ 0, \"desc\" ]],
				//\"stateSave\": true,
				\"buttons\": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					}/*,
					{
						'text': 'Columns',
						'extend': 'colvis'
					}*/
				],
				\"autoWidth\" : true
			});
			otable.columns( [0] ).visible( false );

	    $(\"div.toolbar\").html('<div class=\"text-right\"><img src=\"assets/img/logo.png\" alt=\"SmartAdmin\" style=\"width: 111px; margin-top: 3px; margin-right: 10px;\"></div>');


	    $(\"#logsshow .sssdrp\").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $(\"#logsshow thead th input[type=text]\").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });
	};

	function multifilter(nthis,fieldname,otable)
	{
			var selectedoptions = [];
            $.each($(\"input[name='multiselect_\"+fieldname+\"']:checked\"), function(){
                selectedoptions.push($(this).val());
            });
			otable
	         .column( $(nthis).parent().index()+':visible' )
			 .search(\"^\" + selectedoptions.join(\"|\") + \"$\", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#logsshow tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value=\"' + item + '\">' + item + '</option>');
		})
		return options;
	}

	loadScript(\"assets/plugins/datatables1.11.3/datatables.min.js\", pagefunction);

</script>

";





return $returnit;
}else{echo "";}
	exit();
}


//Show Logs
function showlogs($primarykey,$tablename,$tuid,$turl,$disabled="",$logid="")
{
	if(empty($primarykey) or empty($tablename) or empty($tuid) or empty($turl)) return "";
	$returnit="";

	$lgid="";
	if($logid !=""){$lgid=$logid; $logid='&logid='.$logid;}

	$returnit='<style>
		#logshow'.$lgid.'{margin:0;padding:0;}
	</style>
	<div id="logshow'.$lgid.'"></div>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#logshow'.$lgid.'").load("assets/ajax/showlogs.php?pkey='.$primarykey.'&tname='.$tablename.'&tuid='.$tuid.'&tuurl='.urlencode($turl).'&disb='.@trim($disabled).'&ct='.rand(2,77).$logid.'");
		});
	</script>';

	return $returnit;
}

function checkversionavailability($mysqli,$tablename,$tablerowid,$fieldname,$disabled=""){

	if($disabled != "") return "";
	if(empty($tablename) || empty($tablerowid) || empty($fieldname)) return "";

	if ($stmt = $mysqli->query('SELECT modified,edited_value,activity,status,id FROM `audit_log` where table_name="'.$tablename.'" and table_row_id="'.$tablerowid.'" ORDER BY modified DESC')) {
        if ($stmt->num_rows > 0) {
			$kk="";
			while($row=$stmt->fetch_row()) {
				$editedvalue=$row[1];
				if($editedvalue=="")
				{
					return "";
					exit();
				}

				$editedvalarr=@unserialize(base64_decode($editedvalue));
				if(is_array($editedvalarr)) $z=count($editedvalarr); else $z=0;

				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and @trim($editedvalarr[$i]["title"]) == @trim($fieldname)){
						return '<a href="javascript:void(0);" class="showversion-link" id="'.$fieldname.'" onclick="filterColumn(\''.$fieldname.'\')"><i class="fa fa-refresh" aria-hidden="true"></i></a>';
					}
				}
			}
		}
	}


	return '<a href="javascript:void(0);" class="showversion-link nodis" id="'.$fieldname.'" onclick="filterColumn(\''.$fieldname.'\')"><i class="fa fa-refresh" aria-hidden="true"></i></a>';
}

function rand_Pass($upper = 1, $lower = 5, $numeric = 3, $other = 2) {

    $pass_order = Array();
    $passWord = '';

    //Create contents of the password
    for ($i = 0; $i < $upper; $i++) {
        $pass_order[] = chr(rand(65, 90));
    }
    for ($i = 0; $i < $lower; $i++) {
        $pass_order[] = chr(rand(97, 122));
    }
    for ($i = 0; $i < $numeric; $i++) {
        $pass_order[] = chr(rand(48, 57));
    }
    for ($i = 0; $i < $other; $i++) {
        $pass_order[] = chr(rand(33, 47));
    }

    //using shuffle() to shuffle the order
    shuffle($pass_order);

    //Final password string
    foreach ($pass_order as $char) {
        $passWord .= $char;
    }
    return $passWord;
}

function fireamail($to="",$subject="",$message="",$header=""){
	if(@trim($to)=="" || @trim($subject)=="" || @trim($message)=="") return false;
	$header = "From:support@vervantis.com \r\n";
	$header .= "Reply-To:support@vervantis.com \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	//$header .= "Content-type: text/html\r\n";
	$header .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";

	$message = wordwrap($message,70, "\r\n");

	echo mail ($to,$subject,$message,$header);
	//echo mail ($to,$subject,$message);
}

/**
 * @param $user_agent null
 * @return string
 */
function getOS($user_agent = null)
{
    if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
    $os_array = [
        'windows nt 10'                              =>  'Windows 10',
        'windows nt 6.3'                             =>  'Windows 8.1',
        'windows nt 6.2'                             =>  'Windows 8',
        'windows nt 6.1|windows nt 7.0'              =>  'Windows 7',
        'windows nt 6.0'                             =>  'Windows Vista',
        'windows nt 5.2'                             =>  'Windows Server 2003/XP x64',
        'windows nt 5.1'                             =>  'Windows XP',
        'windows xp'                                 =>  'Windows XP',
        'windows nt 5.0|windows nt5.1|windows 2000'  =>  'Windows 2000',
        'windows me'                                 =>  'Windows ME',
        'windows nt 4.0|winnt4.0'                    =>  'Windows NT',
        'windows ce'                                 =>  'Windows CE',
        'windows 98|win98'                           =>  'Windows 98',
        'windows 95|win95'                           =>  'Windows 95',
        'win16'                                      =>  'Windows 3.11',
        'mac os x 10.1[^0-9]'                        =>  'Mac OS X Puma',
        'macintosh|mac os x'                         =>  'Mac OS X',
        'mac_powerpc'                                =>  'Mac OS 9',
        'linux'                                      =>  'Linux',
        'ubuntu'                                     =>  'Linux - Ubuntu',
        'iphone'                                     =>  'iPhone',
        'ipod'                                       =>  'iPod',
        'ipad'                                       =>  'iPad',
        'android'                                    =>  'Android',
        'blackberry'                                 =>  'BlackBerry',
        'webos'                                      =>  'Mobile',

        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})'=>'Windows Media Center',
        '(win)([0-9]{1,2}\.[0-9x]{1,2})'=>'Windows',
        '(win)([0-9]{2})'=>'Windows',
        '(windows)([0-9x]{2})'=>'Windows',

        // Doesn't seem like these are necessary...not totally sure though..
        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

        'Win 9x 4.90'=>'Windows ME',
        '(windows)([0-9]{1,2}\.[0-9]{1,2})'=>'Windows',
        'win32'=>'Windows',
        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})'=>'Java',
        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'=>'Solaris',
        'dos x86'=>'DOS',
        'Mac OS X'=>'Mac OS X',
        'Mac_PowerPC'=>'Macintosh PowerPC',
        '(mac|Macintosh)'=>'Mac OS',
        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'SunOS',
        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'BeOS',
        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'=>'RISC OS',
        'unix'=>'Unix',
        'os/2'=>'OS/2',
        'freebsd'=>'FreeBSD',
        'openbsd'=>'OpenBSD',
        'netbsd'=>'NetBSD',
        'irix'=>'IRIX',
        'plan9'=>'Plan9',
        'osf'=>'OSF',
        'aix'=>'AIX',
        'GNU Hurd'=>'GNU Hurd',
        '(fedora)'=>'Linux - Fedora',
        '(kubuntu)'=>'Linux - Kubuntu',
        '(ubuntu)'=>'Linux - Ubuntu',
        '(debian)'=>'Linux - Debian',
        '(CentOS)'=>'Linux - CentOS',
        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - Mandriva',
        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - SUSE',
        '(Dropline)'=>'Linux - Slackware (Dropline GNOME)',
        '(ASPLinux)'=>'Linux - ASPLinux',
        '(Red Hat)'=>'Linux - Red Hat',
        // Loads of Linux machines will be detected as unix.
        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
        //'X11'=>'Unix',
        '(linux)'=>'Linux',
        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'=>'AmigaOS',
        'amiga-aweb'=>'AmigaOS',
        'amiga'=>'Amiga',
        'AvantGo'=>'PalmOS',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})'=>'Linux',
        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'=>'WebTV',
        'Dreamcast'=>'Dreamcast OS',
        'GetRight'=>'Windows',
        'go!zilla'=>'Windows',
        'gozilla'=>'Windows',
        'gulliver'=>'Windows',
        'ia archiver'=>'Windows',
        'NetPositive'=>'Windows',
        'mass downloader'=>'Windows',
        'microsoft'=>'Windows',
        'offline explorer'=>'Windows',
        'teleport'=>'Windows',
        'web downloader'=>'Windows',
        'webcapture'=>'Windows',
        'webcollage'=>'Windows',
        'webcopier'=>'Windows',
        'webstripper'=>'Windows',
        'webzip'=>'Windows',
        'wget'=>'Windows',
        'Java'=>'Unknown',
        'flashget'=>'Windows',

        // delete next line if the script show not the right OS
        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
        'MS FrontPage'=>'Windows',
        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        '(msie)([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        'libwww-perl'=>'Unix',
        'UP.Browser'=>'Windows CE',
        'NetAnts'=>'Windows',
    ];

    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
    $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

    foreach ($os_array as $regex => $value) {
        if (preg_match('{\b('.$regex.')\b}i', $user_agent)) {
            return $value.' x'.$arch;
        }
    }

    return 'Unknown';
}

function custommsmail($mailbox, $messageArgs,$mtype) {
     $tenantID=$_ENV['Tenant_ID'];
		 if(!empty($mtype) and $mtype=="fax"){
 			$clientID=$_ENV['Fax_Client_ID'];
      	$clientSecret=$_ENV['Fax_Csecret'];
 		}elseif(!empty($mtype) and $mtype=="support"){
 			$clientID=$_ENV['Support_Client_ID'];
 		  $clientSecret=$_ENV['Support_Csecret'];
 		}else{
 			$clientID=$_ENV['No_Reply_Client_ID'];
      	$clientSecret=$_ENV['No_Reply_Csecret'];
 		}
     $baseURL='https://graph.microsoft.com/v1.0/';

    $oauthRequest = 'client_id=' . $clientID . '&scope=https%3A%2F%2Fgraph.microsoft.com%2F.default&client_secret=' . $clientSecret . '&grant_type=client_credentials';
    $reply = sendPostRequest('https://login.microsoftonline.com/' . $tenantID . '/oauth2/v2.0/token', $oauthRequest,false,'');
    $reply = json_decode($reply['data']);//print_r($reply);
     $Token= $reply->access_token;

    return sendMail($mailbox, $messageArgs, $Token, $baseURL );
}

function sendPostRequest($URL, $Fields, $Headers = false, $Token="") {
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    if ($Fields) curl_setopt($ch, CURLOPT_POSTFIELDS, $Fields);
    if ($Headers) {
        $Headers[] = 'Authorization: Bearer ' . $Token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return array('code' => $responseCode, 'data' => $response);
}

function sendMail($mailbox, $messageArgs, $Token, $baseURL ) {
    if (!$Token) {
        //throw new Exception('No token defined');
        return false;
    }

    foreach ($messageArgs['toRecipients'] as $recipient) {
        if ($recipient['name']) {
            $messageArray['toRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
        } else {
            $messageArray['toRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
        }
    }
    foreach ($messageArgs['ccRecipients'] as $recipient) {
        if ($recipient['name']) {
            $messageArray['ccRecipients'][] = array('emailAddress' => array('name' => $recipient['name'], 'address' => $recipient['address']));
        } else {
            $messageArray['ccRecipients'][] = array('emailAddress' => array('address' => $recipient['address']));
        }
    }
    $messageArray['subject'] = $messageArgs['subject'];
    $messageArray['importance'] = ($messageArgs['importance'] ? $messageArgs['importance'] : 'normal');
    if (isset($messageArgs['replyTo'])) $messageArray['replyTo'] = array(array('emailAddress' => array('name' => $messageArgs['replyTo']['name'], 'address' => $messageArgs['replyTo']['address'])));
    $messageArray['body'] = array('contentType' => 'HTML', 'content' => @str_ireplace("\n","<br>",$messageArgs['body']));
    $messageJSON = json_encode($messageArray);
    $response = sendPostRequest($baseURL . 'users/' . $mailbox . '/messages', $messageJSON, array('Content-type: application/json'), $Token);

    $response = json_decode($response['data']);
    if(!isset($response->id)) return false;
    $messageID = $response->id;

		if(isset($messageArgs['images']) and count($messageArgs['images'])){
	    foreach ($messageArgs['images'] as $image) {
	        $messageJSON = json_encode(array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $image['Name'], 'contentBytes' => base64_encode($image['Content']), 'contentType' => $image['ContentType'], 'isInline' => true, 'contentId' => $image['ContentID']));
	        $response = sendPostRequest($baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/attachments', $messageJSON, array('Content-type: application/json'), $Token);
	    }
		}

		if(isset($messageArgs['attachments']) and count($messageArgs['attachments'])){
	    foreach ($messageArgs['attachments'] as $attachment) {
	        $messageJSON = json_encode(array('@odata.type' => '#microsoft.graph.fileAttachment', 'name' => $attachment['Name'], 'contentBytes' => base64_encode($attachment['Content']), 'contentType' => $attachment['ContentType'], 'isInline' => false));
	        $response = sendPostRequest($baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/attachments', $messageJSON, array('Content-type: application/json'), $Token);
	    }
		}

		if(isset($messageArgs['largeattachments']) and count($messageArgs['largeattachments'])){
      foreach ($messageArgs['largeattachments'] as $attachment) {
          $afilesize=$attachment['size'];


          $messageJSON = json_encode(array('AttachmentItem' => array('attachmentType'=>'file', 'name'=>$attachment['Name'],'size'=>	$afilesize)));
          $response = sendPostRequest($baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/attachments/createUploadSession',$messageJSON, array('Content-type: application/json'));

          if (isset($response['code']) and $response['code'] == '201' and isset($response['data'])){
            $tmpdata = json_decode($response['data']);
            if(isset($tmpdata->uploadUrl)){
              $source=$attachment['Path'];
              $url = $tmpdata->uploadUrl;
              $fragSize = 1024*1024*4;

              $fileSize = $afilesize;
              $numFragments = ceil($fileSize / $fragSize);
              $bytesRemaining = $fileSize;
              $i = 0;
              $response = null;

              while ($i < $numFragments) {
                  $chunkSize = $numBytes = $fragSize;
                  $start = $i * $fragSize;
                  $end = $i * $fragSize + $chunkSize - 1;
                  $offset = $i * $fragSize;

                  if ($bytesRemaining < $chunkSize) {
                      $chunkSize = $numBytes = $bytesRemaining;
                      $end = $fileSize - 1;
                  }

                  if ($stream = fopen($source, 'r')) {
                      // get contents using offset
                      $data = stream_get_contents($stream, $chunkSize, $offset);
                      fclose($stream);
                  }

                  $contentRange = " bytes " . $start . "-" . $end . "/" . $fileSize;
                  $headers = array(
                      "Content-Length: $numBytes",
                      "Content-Range: $contentRange"
                  );

                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  $server_output = curl_exec($ch);
                  $info = curl_getinfo($ch);
                  curl_close($ch);

                  $bytesRemaining = $bytesRemaining - $chunkSize;
                  $i++;
              }
            }
          }else{
          }
      }
    }


    //Send
    $response = sendPostRequest($baseURL . 'users/' . $mailbox . '/messages/' . $messageID . '/send', '', array('Content-Length: 0'), $Token);
    if ($response['code'] == '202') return true;
    return false;

}

function filter_string_polyfill(string $string): string
{
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}


function login_sso_oauth($email, $mysqli) {
    if($stmt = $mysqli->prepare("SELECT user_id,email,password,salt,gender,usergroups_id,firstname,lastname,company_id,status,disable_date,`failed_password_attempts` FROM user WHERE email = '".$mysqli->real_escape_string($email)."' LIMIT 1 "))
	{
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $email, $db_password, $salt, $gender, $group_id, $firstname, $lastname,$companyid, $ustatus, $disabledate, $nofailedattempts);
        $stmt->fetch();

        $password = $db_password;
        if ($stmt->num_rows == 1) {
          $user_browser = $_SERVER['HTTP_USER_AGENT'];
          $user_id = preg_replace("/[^0-9]+/", "", $user_id);
          $_SESSION['user_id'] = $user_id;

          $_SESSION['email'] = $email;
					$_SESSION['gender'] = $gender;
					$_SESSION['fullname'] = $firstname." ".$lastname;
					$_SESSION['group_id'] = $group_id;
					$_SESSION['company_id'] = $companyid;
					$_SESSION['user_browser'] = $user_browser;
          $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					$_SESSION['tracktime'] = @date("Y-m-d H:i:s");
					$_SESSION['ipaddress'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['useragent'] = $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['newuser'] = 'Yes';

                    //$now = time();
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$clientip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$clientip = $_SERVER['REMOTE_ADDR'];
					}
					$referrer= @$_SERVER['HTTP_REFERER'];

					$json = @trim(@file_get_contents('https://get.geojs.io/v1/ip/geo/'.$clientip.'.json'));
					$data = @json_decode($json,true);
					if(isset($data['timezone'])) $_SESSION['timezone'] = $data['timezone'];
					else $_SESSION['timezone'] = '';

					$clientbrowser=get_browser(null,true);

					if(isset($clientbrowser["parent"])) $cbrowser=$clientbrowser["parent"];
					else $cbrowser="unknown";

					if(isset($clientbrowser["platform_description"])) $cos=$clientbrowser["platform_description"];
					else $cos="unknown";

					if(isset($clientbrowser["device_type"])) $cdtype=$clientbrowser["device_type"];
					else $cdtype="unknown";

					if(isset($clientbrowser["crawler"])) $ccrawl=$clientbrowser["crawler"];
					else $ccrawl="";

					$ccountry=$cregion=$ccity=$cisp="unknown";
					$cquery = @unserialize (file_get_contents('http://ip-api.com/php/'.$clientip));
					if ($cquery && $cquery['status'] == 'success') {
						$ccountry=$cquery['countryCode'];
						$cregion=$cquery['regionName'];
						$ccity=$cquery['city'];
						$cisp=$cquery['isp'];
					}

					$caddr=$ccity.", ".$cregion.", ".$ccountry;

					if($caddr=="unknown, unknown, unknown") $caddr="unknown";
                    if (!$mysqli->query("INSERT INTO user_tracking set user_id='".$user_id."', ipaddress='".$mysqli->real_escape_string($clientip)."',referrer='".$mysqli->real_escape_string($referrer)."',password='".$mysqli->real_escape_string($password)."',date='".$mysqli->real_escape_string($_SESSION['tracktime'])."',status='Active',useragent='".$mysqli->real_escape_string($_SESSION['useragent'])."',browser='".$mysqli->real_escape_string($cbrowser)."',operating_system='".$mysqli->real_escape_string($cos)."',estimated_location='".$mysqli->real_escape_string($caddr)."',isp='".$mysqli->real_escape_string($cisp)."',device_type='".$mysqli->real_escape_string($cdtype)."',crawler='".$mysqli->real_escape_string($ccrawl)."'")) {

							@error_log("functionsphp user tracking insert failed", 0);
						header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System Error');
                        exit();
                    }


							$_SESSION['newuser'] = 'No';
							$_SESSION['samlNameId'] = $_SESSION['email'];
//$sso_choice=0 : none, $sso_choice=1: azure, $sso_choice=2: okta, $sso_choice=3:google
	$sso_choiceccfinal='none';						
  if($stmtsso = $mysqli->prepare("SELECT c.sso_choice FROM company c WHERE c.company_id =".$mysqli->real_escape_string($companyid)." LIMIT 1 "))
  {
    $stmtsso->execute();
    $stmtsso->store_result();
    if($stmtsso->num_rows  > 0) {
      $stmtsso->bind_result($sso_choicecc);
      $stmtsso->fetch();
	  if($sso_choicecc != 0 && $sso_choicecc != 1 && $sso_choicecc != 2 && $sso_choicecc != 3){
		  header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System Error');
		  exit();		  
	  }
	  if($sso_choicecc==0) $sso_choiceccfinal='none';	
	  else if($sso_choicecc==1) $sso_choiceccfinal='azure';	
	  else if($sso_choicecc==2) $sso_choiceccfinal='okta';	
	  else if($sso_choicecc==3) $sso_choiceccfinal='google';		
	  
	}
  }
							
							$_SESSION['saml'] = $sso_choiceccfinal;

							return 4;
        } else {
            // No user exists.
            return 2;
        }
    } else {
			return 3;
    }
	exit();
}

function check_sso_oauth_login($mysqli){
    //$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	if(isset($_SESSION["samlNameId"])) $email=$_SESSION["samlNameId"]; else return false;

	return login_sso_oauth($email,$mysqli);
}

function dropdown_options($mysqli,$table)
{
    //$stmt = $mysqli->prepare(" SELECT * FROM dropdown_options $table WHERE dropdown_name = '$dropdown_name' AND table_name = '$table' ");
	//echo "SELECT * FROM dropdown_options WHERE table_name = '$table'";
	$stmt = $mysqli->prepare(" SELECT * FROM dropdown_options WHERE table_name = '$table' ");
	//$stmt->bind_param("s", $_POST['name']);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows > 0) {
		$count = 0;
		while($row = $result->fetch_assoc()) {
			//$optionsArr[$row['dropdown_name']] = $row['option_name'];
			$optionsArr[$row['dropdown_name']][$row['option_value']] = $row['option_name'];
			$count++;
		}
		return $optionsArr;
	}
}
?>
