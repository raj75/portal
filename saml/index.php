<?php
/**
 *  SAML Handler
 */

//session_start();

require_once '../assets/includes/db_connect.php';
require_once '../assets/includes/functions.php';
session_start();
sec_session_start();
require_once '../external_lib/_saml_toolkit_loader.php';

/*if(login_sso_oauth($email, $mysqli)==true){
  header('Location: https://'.$_SERVER['HTTP_HOST']);
  exit();
}*/

/*$args = explode('/',$_SERVER['REQUEST_URI']);
print_r($_GET["uname"]);
if(!is_numeric($args[count($args)-1]))
{
    print_r("Invalid Request");
    exit();
}
//$companyId = (int)$args[count($args)-1];
*/
if(isset($_GET) and isset($_GET["uname"])) $uemail = @strtolower($_GET["uname"]);


require_once 'settings.php';

$auth = new OneLogin\Saml2\Auth($settings);
if($uemail=="saurabh.singh@vervantis.com"){print_r($_SESSION);print_r($_COOKIE); }
//print_r($_SESSION);die();

if(isset($_REQUEST)){
	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string(serialize($_REQUEST))."'"))
	{
	  $stmt->execute();
	}
}





//if (isset($_SESSION['samlUserdata'])) {
if (isset($_SESSION['samlNameId'])) {if(isset($_SESSION['saml']) and $_SESSION['saml']=="okta" and isset($_SESSION['samlUserdata']) and empty($_SESSION['samlUserdata'])){$auth->login(); }
    if (!empty($_SESSION['samlNameId'])) {

      $userssoemail=@trim(htmlentities($_SESSION['samlNameId']));
	$anotheruser="anotheruser";
	  if($stmtcheck = $mysqli->prepare("SELECT c.sso_choice FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($userssoemail))."' LIMIT 1 "))
	  {
		$stmtcheck->execute();
		$stmtcheck->store_result();
		if($stmtcheck->num_rows  > 0) {
		  $stmtcheck->bind_result($sso_choice);
		  $stmtcheck->fetch();
		  if($sso_choice==1)$anotheruser="azureanotheruser";
		  elseif($sso_choice==2)$anotheruser="oktaanotheruser";
		}
	  }

      if(isset($_COOKIE) and isset($_COOKIE["passwordform"]) and !empty($_COOKIE["passwordform"])){
        if(@trim(@strtolower($_COOKIE["passwordform"])) != @trim(@strtolower($userssoemail))){
          header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror='.$anotheruser);
          exit();
        }
      }

      if(isset($_GET) and isset($_GET["uname"])){
        if(@trim(@strtolower($uemail)) != @trim(@strtolower($userssoemail))){
          header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror='.$anotheruser);
          exit();
        }
      }
//print_r($_SESSION);

      /*$attributes = $_SESSION['samlUserdata'];
      echo 'You have the following attributes:<br>';
      echo '<table><thead><th>Name</th><th>Values</th></thead><tbody>';
      echo '<tr><td>NameID</td><td>' . htmlentities($_SESSION['samlNameId']) . '</td></tr>';
      foreach ($attributes as $attributeName => $attributeValues) {
          echo '<tr><td>' . htmlentities($attributeName) . '</td><td><ul>';
          foreach ($attributeValues as $attributeValue) {
              echo '<li>' . htmlentities($attributeValue) . '</li>';
          }
          echo '</ul></td></tr>';
      }
      echo '</tbody></table>';

      die();*/


      $responselogin=login_sso_oauth($userssoemail, $mysqli);

      if($responselogin==4){//print_r($_SESSION);die("YES");
        header('Location: https://'.$_SERVER['HTTP_HOST']);
        exit();
      }elseif($responselogin==2){
        header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=noreg');
        exit();
      }else{
        header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
        exit();
      }

        /*$attributes = $_SESSION['samlUserdata'];
        echo 'You have the following attributes:<br>';
        echo '<table><thead><th>Name</th><th>Values</th></thead><tbody>';
        echo '<tr><td>NameID</td><td>' . htmlentities($_SESSION['samlNameId']) . '</td></tr>';
        foreach ($attributes as $attributeName => $attributeValues) {
            echo '<tr><td>' . htmlentities($attributeName) . '</td><td><ul>';
            foreach ($attributeValues as $attributeValue) {
                echo '<li>' . htmlentities($attributeValue) . '</li>';
            }
            echo '</ul></td></tr>';
        }
        echo '</tbody></table>';*/
    } else {
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
      exit();
    }

    echo "<p><a href='/saml/logout.php/$companyId' >Logout</a></p>";
} else {
    $auth->login();
}
