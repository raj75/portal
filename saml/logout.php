<?php
/**
 *  SAML Handler
 */
 //session_start();

 require_once '../assets/includes/db_connect.php';
 require_once '../assets/includes/functions.php';
 sec_session_start();

require_once '../external_lib/_saml_toolkit_loader.php';

require_once 'settings.php';

$auth = new OneLogin\Saml2\Auth($settings);
$returnTo = null;
$parameters = array();
$nameId = null;
$sessionIndex = null;
$nameIdFormat = null;
$samlSPNameQualifier = null;

if (isset($_SESSION['samlNameId'])) {
    $nameId = $_SESSION['samlNameId'];
}
if (isset($_SESSION['samlSessionIndex'])) {
    $sessionIndex = $_SESSION['samlSessionIndex'];
}
if (isset($_SESSION['samlNameIdFormat'])) {
    $nameIdFormat = $_SESSION['samlNameIdFormat'];
}
 if (isset($_SESSION['samlSPNameQualifier'])) {
    $samlSPNameQualifier = $_SESSION['samlSPNameQualifier'];
}



$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
unset($_COOKIE["vervantison"]);
setcookie ("vervantison","", time() - 2595000);
@session_start();
@session_unset();
@session_destroy();

$auth->logout($returnTo, $parameters, $nameId, $sessionIndex, false, $nameIdFormat, null, $samlSPNameQualifier);

?>
