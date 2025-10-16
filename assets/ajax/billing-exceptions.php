<?php //require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");


//("SELECT DISTINCT e.`Customer #` FROM `exceptions` e,user up where up.user_id = '".$user_one."' and up.company_id = e.`Customer ID` ORDER BY e.`Customer #`"))

$user_one=$_SESSION["user_id"];
$user_one=28;
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<?php

	$cust_arr=array();
	$firstcust="";
	if ($fdstmt = $mysqli->prepare("SELECT DISTINCT e.`Customer #` FROM `exceptions` e,user up where up.user_id = '".$user_one."' and up.company_id = e.`Customer ID` ORDER BY e.`Customer #`")) {

//("SELECT DISTINCT e.`Customer #` FROM `exceptions` e,user up where up.id = '".$user_one."' and up.company_id = e.`Customer ID` ORDER BY e.`Customer #`"))

        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($custno);
			//$fdstmt->bind_result($fd_symbol,$fd_cmonth,$fd_cyear);
			while($fdstmt->fetch()){
				$cust_arr[]=$custno;
				if($firstcust=="") $firstcust=$custno;
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
	if(!count($cust_arr)) die("No data to show!");
	//else $fd_arr=array_unique($fd_arr);
?>
<style>
.dropdown-menu[title]::before {
    content: attr(title);
    /* then add some nice styling as needed, eg: */
    display: block;
    font-weight: bold;
    padding: 4px;
	text-align:center;
}
.dropdown-menu li{
	text-align:center;
}
.dropdown-submenu>a:after{display:none;}
</style>
<div class="dropdown">
<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Select Customer
<span class="caret"></span></button>
<ul class="dropdown-menu">
<?php foreach($cust_arr as $ky=>$vl){ ?>
  <li class="dropdown-submenu">
	<a href="javascript:void(0)" onclick="loadbe('<?php echo @trim($vl); ?>')"><?php echo $vl; ?></a></li>
  </li>
<?php } ?>
</ul>
</div>
<div id="beresponse"></div>
<div id="bemresponse"></div>
<script>
$( document ).ready(function() {
    loadbe(<?php echo @trim($firstcust); ?> );
});
//firstcust
function loadbe(cid){
	$('#beresponse').html('');
	$('#bemresponse').html('');
	$('#beresponse').html('<iframe id="frame" src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/billing-exceptions-details.php?action=view&cid='+cid+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
	$('#bemresponse').html('<iframe id="frame" src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/billing-exceptions-details.php?action=view&mid='+cid+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
}
</script>
