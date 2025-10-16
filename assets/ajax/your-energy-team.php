<?php
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];
$comp_id=$_SESSION['company_id'];

$_aeauserimage=$_aususerimage=$_acauserimage="";
$aea_gender=$aus_gender=$aca_gender="M";
$aea_Firstname=$aea_Lastname=$aea_email=$aea_phone=$aea_gender=$aea_title=$aus_Firstname=$aus_Lastname=$aus_email=$aus_phone=$aus_gender=$aus_title=$aca_Firstname=$aca_Lastname=$aca_email=$aca_phone=$aca_gender=$aca_title="";

if ($stmtk = $mysqli->prepare("SELECT energy_advocate,ubm_support,company_admin FROM company c Where c.company_id = '".$comp_id."' LIMIT 1")) {
	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($ad_energy_advocate,$ad_ubm_support,$ad_company_admin);
		$stmtk->fetch();

		if(!empty($ad_energy_advocate)){
			if ($stmtkaea = $mysqli->prepare("SELECT firstname,lastname,email,phone,gender,title FROM user Where user_id = '".$ad_energy_advocate."' LIMIT 1")) {
				$stmtkaea->execute();
				$stmtkaea->store_result();
				if ($stmtkaea->num_rows > 0) {
					$stmtkaea->bind_result($aea_Firstname,$aea_Lastname,$aea_email,$aea_phone,$aea_gender,$aea_title);
					$stmtkaea->fetch();

					$_aeauserimage=checks3img(md5($ad_energy_advocate).".png","profiles/users/profile image/",(($aea_gender == "M" || @trim($aea_gender == ""))?"male.png":"female.png"));
					if($_aeauserimage==false){$_aeauserimage="";}

					/*if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_energy_advocate).".png") and ($aea_gender == "M" || @trim($aea_gender == "")))
						$_aeauserimage="male.png";
					elseif(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_energy_advocate).".png") and $aea_gender == "F")
						$_aeauserimage="female.png";
					else
						$_aeauserimage=md5($ad_energy_advocate).".png";*/

					$numbers_only = preg_replace("/[^\d]/", "", $aea_phone);
					$aea_phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $numbers_only);
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
		}

		if(!empty($ad_ubm_support)){
			if ($stmtkaus = $mysqli->prepare("SELECT firstname,lastname,email,phone,gender,title FROM user Where user_id = '".$ad_ubm_support."' LIMIT 1")) {
				$stmtkaus->execute();
				$stmtkaus->store_result();
				if ($stmtkaus->num_rows > 0) {
					$stmtkaus->bind_result($aus_Firstname,$aus_Lastname,$aus_email,$aus_phone,$aus_gender,$aus_title);
					$stmtkaus->fetch();


					$_aususerimage=checks3img(md5($ad_ubm_support).".png","profiles/users/profile image/",(($aus_gender == "M" || @trim($aus_gender == ""))?"male.png":"female.png"));
					if($_aususerimage==false){$_aususerimage="";}

					/*f(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_ubm_support).".png") and ($aus_gender == "M" || @trim($aus_gender == "")))
						$_aususerimage="male.png";
					elseif(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_ubm_support).".png") and $aus_gender == "F")
						$_aususerimage="female.png";
					else
						$_aususerimage=md5($ad_ubm_support).".png";*/

					$numbers_only = preg_replace("/[^\d]/", "", $aus_phone);
					$aus_phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $numbers_only);
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
		}

		if(!empty($ad_company_admin)){
			if ($stmtkaca = $mysqli->prepare("SELECT firstname,lastname,email,phone,gender,title FROM user Where user_id = '".$ad_company_admin."' LIMIT 1")) {
				$stmtkaca->execute();
				$stmtkaca->store_result();
				if ($stmtkaca->num_rows > 0) {
					$stmtkaca->bind_result($aca_Firstname,$aca_Lastname,$aca_email,$aca_phone,$aca_gender,$aca_title);
					$stmtkaca->fetch();

					$_acauserimage=checks3img(md5($ad_company_admin).".png","profiles/users/profile image/",(($aca_gender == "M" || @trim($aca_gender == ""))?"male.png":"female.png"));
					if($_acauserimage==false){$_acauserimage="";}

					/*if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_company_admin).".png") and ($aca_gender == "M" || @trim($aca_gender == "")))
						$_acauserimage="male.png";
					elseif(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($ad_company_admin).".png") and $aca_gender == "F")
						$_acauserimage="female.png";
					else
						$_acauserimage=md5($ad_company_admin).".png";*/

					$numbers_only = preg_replace("/[^\d]/", "", $aca_phone);
					$aca_phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $numbers_only);
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
		}
	}
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}
?>


<html lang="en-us" >
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">-->

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/bootstrap.min.css">

	</head>

	<body>

	<style>

img
{
	height:98px !important;
	width:118px;
}
.txtcont
{
	padding:35px;
}
.txtcont .cmtitle, .txtcont .cmdetails, .cmtitle
{
	font-size: large;
    font-weight: bolder;
}
.txtcont .cmdetails
{
	color:#00B2FF;
}
.txtcont button
{
	height: 25px !important;width: auto !important;font-weight:bold;
}
.border-t{border-top:1px solid #ccc !important;}
.border-b{border-bottom:1px solid #ccc !important;}
.border-r{border-right:1px solid #ccc !important;}
.border-l{border-left:1px solid #ccc !important;}
.vmiddle{position: relative;
    top: 50%;
    transform: translateY(195%);}
.vnmiddle{position: relative;
    top: 39%;
	text-align: center;
}
.padding-t10{padding-top:10px;}
.pleft{float:left !important;}
.pright{float:right !important;}
.height-119{height:106px;}
.height-344{height:344px;}
.map-content{margin-top:-50px;}
div[aria-describedby="sndialog"] div.ui-dialog-titlebar {
  display:none;
}
div[aria-describedby="sndialog"]{width:80% !important;height:auto;max-height:70vh !important;}
div[aria-describedby="sndialog"]{overflow-y:scroll;}
.streamnewsdialog{font-size: 17px;}
</style>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 border-b">
							<div class="row">
							<?php if(!empty($ad_energy_advocate)){ ?>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<img src="<?php echo $_aeauserimage; ?>" width="auto" class="img-responsive img-thumbnail">
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 ">
									<p class="text-justify vmiddle">Energy Advocate</p>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left pleft"><b><?php echo $aea_Firstname." ".$aea_Lastname; ?></b></p>
											<button align="left" class="btn-primary pright" onclick="parent.chatWith('<?php echo $ad_energy_advocate; ?>')">Live Chat</button>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><i><?php echo $aea_title; ?></i></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><b>Call:</b> <?php echo $aea_phone; ?></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left">Email: <?php echo $aea_email; ?></p>
										</div>
									</div>
								</div>
								<?php }else echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 height-119"><p class="text-justify vnmiddle">Energy Advocate not assigned!</p></div>'; ?>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 border-b padding-t10">
							<div class="row">
								<?php if(!empty($aus_email)){ ?>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<img src="<?php echo $_aususerimage; ?>" width="auto" class="img-responsive img-thumbnail">
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<p class="text-justify vmiddle">UBM Support</p>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><b><?php echo $aus_Firstname." ".$aus_Lastname; ?></b></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><i><?php echo $aus_title; ?></i></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><b>Call:</b> <?php echo $aus_phone; ?></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left">Email: <?php echo $aus_email; ?></p>
										</div>
									</div>
								</div>
							<?php }else echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 height-119"><p class="text-justify vnmiddle">UBM Support not assigned!</p></div>'; ?>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-t10">
							<div class="row">
							<?php if(!empty($aca_email)){ ?>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<img src="<?php echo $_acauserimage; ?>" width="auto" class="img-responsive img-thumbnail">
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<p class="text-justify vmiddle">Company Admin</p>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><b><?php echo $aca_Firstname." ".$aca_Lastname; ?></b></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><i><?php echo $aca_title; ?></i></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left"><b>Call:</b> <?php echo $aca_phone; ?></p>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<p class="text-left">Email: <?php echo $aca_email; ?></p>
										</div>
									</div>
								</div>
							</div>
							<?php }else echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 height-119"><p class="text-justify vnmiddle">Company Admin not assigned!</p></div>'; ?>
						</div>


<script src="/assets/js/libs/jquery-2.1.1.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="/assets/js/bootstrap/bootstrap.min.js"></script>



</body>

</html>
