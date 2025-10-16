<?php

require_once '../external_lib/_saml_toolkit_loader.php';
require_once '../assets/includes/db_connect.php';
require_once '../assets/includes/functions.php';

use OneLogin\Saml2\Constants;
use OneLogin\Saml2\Utils;

Utils::setProxyVars(true);

$posible_nameidformat_values = array(
    'unspecified' => Constants::NAMEID_UNSPECIFIED,
    'emailAddress' => Constants::NAMEID_EMAIL_ADDRESS,
    'transient' => Constants::NAMEID_TRANSIENT,
    'persistent' => Constants::NAMEID_PERSISTENT
);
$posible_requestedauthncontext_values = array(
    'unspecified' => Constants::AC_UNSPECIFIED,
    'password' => Constants::AC_PASSWORD,
    'passwordprotectedtransport' => "urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport",
    'x509' => Constants::AC_X509,
    'smartcard' => Constants::AC_SMARTCARD,
    'kerberos' => Constants::AC_KERBEROS,
);

$companyId="";
//$companyId=1;

//print_r($args);
/*$args = explode('/',strtok($_SERVER['REQUEST_URI'], '?'));

if(!is_numeric($args[count($args)-1]))
{
    print_r("Invalid Request");
    exit();
}*/
//$companyId = (int)$args[count($args)-1];
//$_GET["uname"]="saurabh.singh@vervantis.com";



$args = explode('/',strtok($_SERVER['REQUEST_URI'], '?'));//print_r($_SESSION);print_r($_COOKIE);
//if(count($args) > 3 and is_numeric($args[count($args)-1]) and ($args[2]=="acs.php" || $args[2]=="metadata.php") )
if(count($args) > 3 and is_numeric($args[count($args)-1]) and $args[2]=="acs.php" )
{
  $tmpcid=trim($args[3], '/');
  
	$checksql="SELECT c.company_id,c.idp_entity_id_azure, c.idp_sso_url_azure, c.idp_slo_url_azure, c.idp_x509cert_azure,c.idp_entity_id_okta,c.idp_sso_url_okta,c.idp_slo_url_okta,c.idp_x509cert_okta,c.sso_choice FROM company c WHERE c.company_id =".$mysqli->real_escape_string($tmpcid)." LIMIT 1 ";

	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string($checksql)."'"))
	{
	  $stmt->execute();
	}
	
  if($stmt = $mysqli->prepare("SELECT c.company_id,c.idp_entity_id_azure, c.idp_sso_url_azure, c.idp_slo_url_azure, c.idp_x509cert_azure,c.idp_entity_id_okta,c.idp_sso_url_okta,c.idp_slo_url_okta,c.idp_x509cert_okta,c.sso_choice FROM company c WHERE c.company_id =".$mysqli->real_escape_string($tmpcid)." LIMIT 1 "))
  {
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows  > 0) {
      $stmt->bind_result($cid,$idp_entity_id_azure,$idp_sso_url_azure,$idp_slo_url_azure,$idp_x509cert_azure,$idp_entity_id_okta,$idp_sso_url_okta,$idp_slo_url_okta,$idp_x509cert_okta,$sso_choice);
      $stmt->fetch();
	  if($sso_choice != 0 && $sso_choice != 1 && $sso_choice != 2 && $sso_choice != 3){
		  header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
		  exit();		  
	  }
//$sso_choice=0 : none, $sso_choice=1: azure, $sso_choice=2: okta, $sso_choice=3:google	  
	  if($sso_choice==1){  
		  if(!empty($idp_entity_id_azure) and !empty($idp_sso_url_azure) and !empty($idp_slo_url_azure) and !empty($idp_x509cert_azure))
		  {
			$companyId=$cid;
			$opt['idp_entity_id'] = @trim($idp_entity_id_azure);
			$opt['idp_sso_url'] = @trim($idp_sso_url_azure);
			$opt['idp_slo_url'] = @trim($idp_slo_url_azure);
			$opt['idp_x509cert'] = @trim($idp_x509cert_azure);
			//echo @trim($idp_entity_id_azure);exit();
			//var_dump($opt['idp_x509cert']);exit();
		  }else{
			//NO. empty fields
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=nosaml');
			exit();
		  }
	  }elseif($sso_choice==2){ 
		  if(!empty($idp_entity_id_okta) and !empty($idp_sso_url_okta) and !empty($idp_slo_url_okta) and !empty($idp_x509cert_okta))
		  {
			$companyId=$cid;
			$opt['idp_entity_id'] = @trim($idp_entity_id_okta);
			$opt['idp_sso_url'] = @trim($idp_sso_url_okta);
			$opt['idp_slo_url'] = @trim($idp_slo_url_okta);
			$opt['idp_x509cert'] = @trim($idp_x509cert_okta);
			//echo @trim($idp_entity_id_azure);exit();
			//var_dump($opt);exit();
		  }else{
			//NO. empty fields
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=nosaml');
			exit();
		  }	  
	  
	  
	  }elseif($sso_choice==3){ 
	  
		//GOOGLE
		header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
		exit();	  
	  
	  
	  }else{	  
	    header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
		exit();	  
	  }
      //exit();
    }else{ 
      //No. record doesn't exists
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
      exit();
    }
  }else{//die("9");
    //NO. Query failed
    header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
    exit();
  }
}elseif(isset($_GET) and isset($_GET["uname"])){
 $uemail = $_GET["uname"];


	$checksql="SELECT u.email,c.company_id,c.idp_entity_id_azure, c.idp_sso_url_azure, c.idp_slo_url_azure, c.idp_x509cert_azure,c.idp_entity_id_okta,c.idp_sso_url_okta,c.idp_slo_url_okta,c.idp_x509cert_okta,c.sso_choice FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($uemail))."' LIMIT 1 ";

	if($stmt = $mysqli->prepare("INSERT into vervantis.saml_track  SET id=null, request='".$mysqli->real_escape_string($checksql)."'"))
	{
	  $stmt->execute();
	}


	//$opt['idp_entity_id']=$opt['idp_sso_url']=$opt['idp_slo_url']=$opt['idp_x509cert']="";
  if($stmt = $mysqli->prepare("SELECT u.email,c.company_id,c.idp_entity_id_azure, c.idp_sso_url_azure, c.idp_slo_url_azure, c.idp_x509cert_azure,c.idp_entity_id_okta,c.idp_sso_url_okta,c.idp_slo_url_okta,c.idp_x509cert_okta,c.sso_choice FROM `user` u INNER JOIN company c ON u.company_id=c.company_id WHERE u.email='".strtolower($mysqli->real_escape_string($uemail))."' LIMIT 1 "))
  {
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows  > 0) {
      $stmt->bind_result($uemail,$cid,$idp_entity_id_azure,$idp_sso_url_azure,$idp_slo_url_azure,$idp_x509cert_azure,$idp_entity_id_okta,$idp_sso_url_okta,$idp_slo_url_okta,$idp_x509cert_okta,$sso_choice);
      $stmt->fetch();
	  
	  if($sso_choice != 0 && $sso_choice != 1 && $sso_choice != 2 && $sso_choice != 3){
		  header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
		  exit();		  
	  }
//$sso_choice=0 : none, $sso_choice=1: azure, $sso_choice=2: okta, $sso_choice=3:google	  
	  if($sso_choice==1){
		  if(!empty($idp_entity_id_azure) and !empty($idp_sso_url_azure) and !empty($idp_slo_url_azure) and !empty($idp_x509cert_azure))
		  {
			$companyId=$cid;
			$opt['idp_entity_id'] = @trim($idp_entity_id_azure);
			$opt['idp_sso_url'] = @trim($idp_sso_url_azure);
			$opt['idp_slo_url'] = @trim($idp_slo_url_azure);
			$opt['idp_x509cert'] = @trim($idp_x509cert_azure);
			//echo @trim($idp_entity_id_azure);exit();
			//var_dump($opt['idp_x509cert']);exit();
		  }else{
			//NO. empty fields
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=nosaml');
			exit();
		  }
	  }elseif($sso_choice==2){
		  if(!empty($idp_entity_id_okta) and !empty($idp_sso_url_okta) and !empty($idp_slo_url_okta) and !empty($idp_x509cert_okta))
		  {
			$companyId=$cid;
			$opt['idp_entity_id'] = @trim($idp_entity_id_okta);
			$opt['idp_sso_url'] = @trim($idp_sso_url_okta);
			$opt['idp_slo_url'] = @trim($idp_slo_url_okta);
			$opt['idp_x509cert'] = @trim($idp_x509cert_okta);
			//echo @trim($idp_entity_id_azure);exit();
			//var_dump($opt);exit();
		  }else{
			//NO. empty fields
			header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=nosaml');
			exit();
		  }	  
	  
	  
	  }elseif($sso_choice==3){ 
	  
		//GOOGLE
		header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
		exit();	  
	  
	  
	  }else{	  
	    header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
		exit();	  
	  }
      //exit();
    }else{
      //No. record doesn't exists
      header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=notreg');
      exit();
    }
  }else{//die("10");
    //NO. Query failed
    header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
    exit();
  }
}else{
  $idp_entity_id_azure=$idp_sso_url_azure=$idp_slo_url_azure=$idp_x509cert_azure="";
}
if(count($args) > 3 and is_numeric($args[count($args)-1]) and $args[2]=="metadata.php")
{

}else{
  if(empty($companyId)){//die("88");
    header('Location: https://'.$_SERVER['HTTP_HOST'].'?samlerror=error');
    exit();
  }
}
//$companyId=1;
$ssoUlr = Utils::getSelfURLhost(). '/saml';

// SP Options
$opt['sp_entity_id'] = $ssoUlr . "/" . $companyId;
$opt['NameIDFormat'] = 'emailAddress';
$opt['sp_x509cert'] = file_get_contents('../../../../saml.crt');
$opt['sp_privatekey'] = file_get_contents('../../../../saml.pem');


// $nameIDformat = isset($opt['NameIDFormat']) ? $opt['NameIDFormat'] : null;
$opt['NameIDFormat'] = isset($opt['NameIDFormat']) ? $posible_nameidformat_values[$opt['NameIDFormat']] : null;

// print_r($ssoUlr );
// exit();
/*if(empty($opt['sp_entity_id'])) $opt['sp_entity_id']="";
if(empty($opt['NameIDFormat'])) $opt['NameIDFormat']="";
if(empty($opt['sp_x509cert'])) $opt['sp_x509cert']="";
if(empty($opt['sp_privatekey'])) $opt['sp_privatekey']="";
if(empty($opt['idp_entity_id'])) $opt['idp_entity_id']="";
if(empty($opt['idp_sso_url'])) $opt['idp_sso_url']="";
if(empty($opt['idp_slo_url'])) $opt['idp_slo_url']="";
if(empty($opt['idp_x509cert'])) $opt['idp_x509cert']="";*/
$settings = array(

    'strict' => true,
    'debug' => true,

    'sp' => array(
        'entityId' => (!empty($opt['sp_entity_id']) ? $opt['sp_entity_id'] : 'php-saml'),
        'assertionConsumerService' => array(
            'url' => $ssoUlr . '/acs.php/' . $companyId
        ),
        'singleLogoutService' => array(
            'url' => $ssoUlr . '/sls.php/' . $companyId
        ),
        'NameIDFormat' => $opt['NameIDFormat'],
        'x509cert' => $opt['sp_x509cert'],
        'privateKey' => $opt['sp_privatekey'],
    ),

    'idp' => array(
        'entityId' => $opt['idp_entity_id'],
        'singleSignOnService' => array(
            'url' => $opt['idp_sso_url'],
        ),
        'singleLogoutService' => array(
            'url' => $opt['idp_slo_url'],
        ),
        'x509cert' => $opt['idp_x509cert'],
    ),

    'security' => array(
        'signMetadata' => false,
        'nameIdEncrypted' => false,
        'authnRequestsSigned' =>  true,
        'logoutRequestSigned' =>  true,
        'logoutResponseSigned' => true,
        'wantMessagesSigned' =>  false,
        'wantAssertionsSigned' =>  true,
        'wantAssertionsEncrypted' => false,
        'wantNameId' => false,
        'requestedAuthnContext' => false,
        'relaxDestinationValidation' => true,
        'lowercaseUrlencoding' => false,
        'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
        'digestAlgorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
    )
);
//print_r($settings);die();