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

if(isset($_POST["SAMLResponse"])){
    $_GET["SAMLResponse"] = $_POST["SAMLResponse"];
}

if (isset($_SESSION) && isset($_SESSION['LogoutRequestID'])) {
    $requestID = $_SESSION['LogoutRequestID'];
} else {
    $requestID = null;
}

$auth->processSLO(false, $requestID, true);
$errors = $auth->getErrors();
session_abort();
if (empty($errors)) {
  header('Location: https://'.$_SERVER['HTTP_HOST']);
  exit();
    //echo '<p>Sucessfully logged out</p>';
    //exit();

} else {
    echo '<p>', implode(', ', $errors), '. Please Contact Vervantis Support!</p>';
    exit();
}
?>
