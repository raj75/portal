<?php
/**
 *  SAML Handler
 */ 
 require_once '../assets/includes/db_connect.php';
 require_once '../assets/includes/functions.php';
 session_start();
 sec_session_start();
require_once '../external_lib/_saml_toolkit_loader.php';

require_once 'settings.php';
date_default_timezone_set('US/Eastern');
$auth = new OneLogin\Saml2\Auth($settings);
 
if(isset($_REQUEST)){
	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string(serialize($_REQUEST))."'"))
	{
	  $stmt->execute();
	}
}

if(isset($_SERVER)){
	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string(serialize($_SERVER))."'"))
	{
	  $stmt->execute();
	}
}

if(isset($_SESSION)){
	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string(serialize($_SESSION))."'"))
	{
	  $stmt->execute();
	}
}

if(isset($_POST)){
	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string(serialize($_POST))."'"))
	{
	  $stmt->execute();
	}
}
 
if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
        $requestID = $_SESSION['AuthNRequestID'];
    } else {
        $requestID = null;
    }

    $auth->processResponse($requestID);

    $errors = $auth->getErrors();

    if (!empty($errors)) {
        echo '<p>',implode(', ', $errors),'</p>';
    }

    if (!$auth->isAuthenticated()) {
        echo "<p>Not authenticated</p>";
        exit();
    }

    $tmpattr=$auth->getAttributes();//if(is_array($tmpattr["email"]) and count($tmpattr["email"])>0 ) print_r($tmpattr["email"][0]);
	//die();
	//print_r($tmpattr["email"][0]);die();
    /*if(isset($tmpattr["http://schemas.microsoft.com/identity/claims/tenantid"][0])){
      $tenantid=$tmpattr["http://schemas.microsoft.com/identity/claims/tenantid"][0];
    }else{
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=Loginfailed');
      exit();
    }*/

    $args = explode('/',strtok($_SERVER['REQUEST_URI'], '?'));//print_r($args);
    if(count($args) > 3 and is_numeric($args[count($args)-1]) and $args[2]=="acs.php")
    {
      $tmpcid=$args[3];
    }else{
		if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string($_SERVER['REQUEST_URI'])."'"))
		{
		  $stmt->execute();
		}		
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=Loginfailed');
      exit();
    }
	
	$checksql="SELECT u.email FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string(@trim($auth->getNameId())))."' and c.company_id=".$mysqli->real_escape_string($tmpcid)." LIMIT 1";

	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string($checksql)."'"))
	{
	  $stmt->execute();
	}
	
    if($stmt = $mysqli->prepare("SELECT u.email FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string(@trim($auth->getNameId())))."' and c.company_id=".$mysqli->real_escape_string($tmpcid)." LIMIT 1"))
    {
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows  > 0) {
        $stmt->bind_result($uemail);
        $stmt->fetch();
        //echo "YES";
      }else{
		  $responseemail="";
		  if(isset($tmpattr["Email"])){
			  if(is_array($tmpattr["Email"]) and count($tmpattr["Email"])>0 ) $responseemail= @trim($tmpattr["Email"][0]);
		  }else if(isset($tmpattr["email"])){
			  if(is_array($tmpattr["email"]) and count($tmpattr["email"])>0 ) $responseemail= @trim($tmpattr["email"][0]);
		  }else if(isset($tmpattr["Email address"])){
			  if(is_array($tmpattr["Email address"]) and count($tmpattr["Email address"])>0 ) $responseemail= @trim($tmpattr["Email address"][0]);
		  } 
		  
		  if(!empty($responseemail)){
			if($stmtattre = $mysqli->prepare("SELECT u.email FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($responseemail))."' and c.company_id=".$mysqli->real_escape_string($tmpcid)." LIMIT 1"))
			{
			  $stmtattre->execute();
			  $stmtattre->store_result();		  
			  if($stmtattre->num_rows  > 0) {
				$stmtattre->bind_result($uemail);
				$stmtattre->fetch();
				//echo "YES";
			  }else{
				//No. record doesn't exists
				header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
				exit();
			  }
			}else{
			  //NO. Query failed
			  header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
			  exit();				
			}
		  }else{  
			//No. record doesn't exists
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
			exit();
		  }
      }
    }else{
		  $responseemail="";
		  if(isset($tmpattr["Email"])){
			  if(is_array($tmpattr["Email"]) and count($tmpattr["Email"])>0 ) $responseemail= @trim($tmpattr["Email"][0]);
		  }else if(isset($tmpattr["email"])){
			  if(is_array($tmpattr["email"]) and count($tmpattr["email"])>0 ) $responseemail= @trim($tmpattr["email"][0]);
		  }else if(isset($tmpattr["Email address"])){
			  if(is_array($tmpattr["Email address"]) and count($tmpattr["Email address"])>0 ) $responseemail= @trim($tmpattr["Email address"][0]);
		  }else{


			if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string("no attr")."'"))
			{
			  $stmt->execute();
			}			  
			//NO. Query failed
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
			exit();			  
			  
		  }
	$checksql="SELECT u.email FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($responseemail))."' and c.company_id=".$mysqli->real_escape_string($tmpcid)." LIMIT 1";

	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string($checksql)."'"))
	{
	  $stmt->execute();
	}	 	  
		  if(!empty($responseemail)){
			if($stmtattre = $mysqli->prepare("SELECT u.email FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($responseemail))."' and c.company_id=".$mysqli->real_escape_string($tmpcid)." LIMIT 1"))
			{
			  $stmtattre->execute();
			  $stmtattre->store_result();		  
			  if($stmtattre->num_rows  > 0) {
				$stmtattre->bind_result($uemail);
				$stmtattre->fetch();
				//echo "YES";
			  }else{
				//No. record doesn't exists
				header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
				exit();
			  }
			}else{
			  //NO. Query failed
			  header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
			  exit();				
			}
		  }else{  
			//No. record doesn't exists
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
			exit();
		  }
      }//else{
      //NO. Query failed
      //header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
     // exit();
   // }


    $_SESSION['samlUserdata'] = $auth->getAttributes();
    $_SESSION['samlNameId'] = ((isset($uemail) and !empty($uemail))?$uemail : $auth->getNameId());
    $_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
    $_SESSION['samlSessionIndex'] = $auth->getSessionIndex();
    $_SESSION['samlSPNameQualifier'] = $auth->getNameIdSPNameQualifier();

    //print_r($_SESSION);die();
    /*
?>
<table>
  <tbody>
    <tr><td>SAML SSO Authenticated</td></tr>
    <tr><td>Chesapeake Utilities</td></tr>
    <tr><td>Tenant ID:<?php echo $_SESSION["samlUserdata"] ["http://schemas.microsoft.com/identity/claims/tenantid"][0]; ?></td></tr>
    <tr><td>Session ID:<?php echo $_SESSION['samlSessionIndex']; ?></td></tr>
    <tr><td>Name:<?php echo $_SESSION["samlUserdata"] ["http://schemas.microsoft.com/identity/claims/displayname"][0]; ?></td></tr>
    <tr><td>Email:<?php echo $_SESSION['samlNameId']; ?></td></tr>
    <tr><td>Date/Time:<?php echo date('m/d/Y H:i:s'); ?> (EST)</td></tr>
</tbody>
</table>
<?php
*/
    unset($_SESSION['AuthNRequestID']);
    header('Location: https://'.$_SERVER['HTTP_HOST'].'/saml/index.php?uname='.$auth->getNameId());
    exit();
    //die();
    /*$userssoemail=htmlentities($_SESSION['samlNameId']);
    $responselogin=login_sso_oauth($userssoemail, $mysqli);

    if($responselogin==4){//print_r($_SESSION);die("YES");
      header('Location: https://'.$_SERVER['HTTP_HOST']);
      exit();
    }elseif($responselogin==2){
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=nouser');
      exit();
    }else{
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
      exit();
    }*/

    if (isset($_POST['RelayState']) && OneLogin\Saml2\Utils::getSelfURL() != $_POST['RelayState']) {
        $auth->redirectTo($_POST['RelayState']);
    }

 
    //$auth->redirectTo("/");
?>
