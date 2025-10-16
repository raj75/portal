<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];
$comp_id=$_SESSION['company_id'];

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access. Please contact Vervantis Support (support@vervantis.com)!</h5>");

////print_r($_SESSION);print_r($_COOKIE);//die();
?>
<style>
#a_wid-id-00 .widget-body > div{
	vertical-align: middle;
    line-height: 5;
}
<?php if($_SESSION["group_id"] == 3  or $_SESSION["group_id"] == 5){ ?>
.demo{display:block;z-index:885 !important;position:fixed !important;top: 136px;}
.demo1 form{display:block !important;}


#a_wid-id-221 .widget-body{padding-bottom: 4px !important;}
#a_wid-id-21 div[role="content"],#a_wid-id-11 div[role="content"]{max-height: 362px !important;min-height: 234px !important;overflow:hidden;height: 362px !important;}
#a_wid-id-32-1{height:530px;}
.centrr{text-align:center;margin-top:45% !important;}
#framecid{
	margin-bottom: 0px;
    margin-left: -9px;
    margin-top: -15px;
}
.h530{height:530px !important}
.bigBoxinline{
    background-color: #004d60;
	margin-top: 51px;
    padding-left: 5px;
    padding-top: 15px;
    padding-right: 5px;
    padding-bottom: 15px;
    width: 116px;
    height: 68px;
    color: #fff;
    box-sizing: content-box;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    border-left: 5px solid rgba(0,0,0,.15);
    overflow: hidden;
    cursor: pointer;}
	#a_wid-id-21 .bigboxnumber{float:right;}
	#a_wid-id-21 .bigboxicon{float:left;}
	#a_wid-id-21 .ttle{text-align:center;}
	/*#framesummary{height:251px !important;}*/
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
.height-119{height:119px;}
.height-344{height:344px;}
.map-content{margin-top:-50px;}
div[aria-describedby="sndialog"] div.ui-dialog-titlebar {
  display:none;
}
div[aria-describedby="sndialog"]{width:80% !important;height:auto;max-height:70vh !important;}
div[aria-describedby="sndialog"]{overflow-y:scroll;}
.streamnewsdialog{font-size: 17px;}

.ui-draggable-dragging{z-index:99 !important;}

.jar_overlay{
	position: absolute; /* Sit on top of the page content */
	/*display: none;*/ /* Hidden by default */
	width: 100%; /* Full width (cover the whole page) */
	height: 100%; /* Full height (cover the whole page) */
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	/*background-color: rgba(0,0,0,0.5);*/ /* Black background with opacity */
	background-color: #f5f5dc;
	z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
	cursor: pointer; /* Add a pointer on hover */
}
div[role='content']{position:relative;}

<?php } ?>
</style>
<div class="row dashboard-content">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa-fw fa fa-home"></i>
				Dashboard
			</span>
		</h1>
	</div>
</div>
<?php //require_once 'dash_links.php';?>
<!-- widget grid -->
<section id="widget-grid" class="dashboard-content">

	<!-- row -->
	<div class="row">

	<article class="col-sm-12 col-md-12 col-lg-12 grid" >
<?php
if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2))
{
?>
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-00">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12 grid-item" id="a_wid-id-00" data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-sortable="false" data-item-id="1">
				<header>
					<span class="widget-icon"> <i class="fa fa-external-link"></i> </span>
					<h2> Website Auto Login </h2>

					<div class="jarviswidget-ctrls" role="menu">
						<a href="javascript:void(0);" class="button-icon togglewidthpack" rel="tooltip" title="" data-placement="bottom" data-original-title="Change width"><i class="glyphicon glyphicon-resize-horizontal"></i></a>
					</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">
<?php
	$tmp_ao_permit=$tmp_cs_permit=0;
	$stmtat = $mysqli->prepare("SELECT accuvio_user,accuvio_pass,capturis_user,capturis_pass FROM user WHERE user_id= '".$user_one."' LIMIT 1");
if(!$stmtat){
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
//("SELECT accuvio_user,accuvio_pass,capturis_user,capturis_pass FROM user WHERE id= '".$user_one."' LIMIT 1");

	$stmtat->execute();
	$stmtat->store_result();
	if ($stmtat->num_rows > 0) {
		$stmtat->bind_result($at_accuvio_user,$at_accuvio_pass,$at_capturis_user,$at_capturis_pass);
		while($stmtat->fetch()){
			if((@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "") or (@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "")){
?>
					<div class="row marginbottom-14">
					<?php if(@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){ ?>
						<div class="<?php if(@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != ""){echo "col-sm-6 col-md-6 col-lg-6";}else{echo "col-sm-12 col-md-12 col-lg-12";} ?> text-center">
							<a title="Accuvio" href="javascript:void(0);" id="Accuvio"><img src="<?php echo APP_URL; ?>/assets/img/accuvio-logo.png" width="180px" class="margintop" /></a>
						</div>
					<?php $tmp_ao_permit=1; }
						if(@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != ""){ ?>
						<div class="<?php if(@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){echo "col-sm-6 col-md-6 col-lg-6";}else{echo "col-sm-12 col-md-12 col-lg-12";} ?> text-center">
							<a title="Capturis" href="javascript:void(0);" id="Capturis"><img src="<?php echo APP_URL; ?>/assets/img/capturis-logo.png" width="180px" /></a>
						</div>
					<?php $tmp_cs_permit=1; } ?>
					</div>
<?php
			}else{
				echo "<center><div>No Data!</div></center>";
			}
		}
	}else{
		echo "<center><div>No Data!</div></center>";
	}

?>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
<?php
}
if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5))
{
	$widgettools='					<div class="jarviswidget-ctrls" role="menu">
						<a href="javascript:void(0);" class="button-icon togglelockpack" rel="tooltip" title="" data-placement="bottom" data-original-title="Lock"><i class="fa fa-unlock" aria-hidden="true"></i></a>
						<a href="javascript:void(0);" class="button-icon toggleheightpack" rel="tooltip" title="" data-placement="bottom" data-original-title="Change height"><i class="glyphicon glyphicon-resize-vertical"></i></a>
						<a href="javascript:void(0);" class="button-icon togglewidthpack" rel="tooltip" title="" data-placement="bottom" data-original-title="Change width"><i class="glyphicon glyphicon-resize-horizontal"></i></a>
						<a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a>
						<a href="javascript:void(0);" class="button-icon toggleclose" rel="tooltip" title="" data-placement="bottom" data-original-title="Close"><i class="fa fa-times"></i></a>
					</div>
					<div class="widget-toolbar" role="menu"><a data-toggle="dropdown" class="dropdown-toggle color-box selector" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
';
?>
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-01">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-01"  data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-colorbutton="false"  data-widget-refreshbutton="false" data-widget-load123="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard-map.php" data-widget-sortable="false" data-item-id="2">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Site Map</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body  no-padding" style="height:450px;">

						<!-- widget body text-->

						<iframe id="dashboard_map" class="lazy" data-src='https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard-map.php' name="dashboard_map" width="100%" height="450" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>

							<!--<p>Loading...</p>-->

						<!-- end widget body text-->

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="a_wid-id-001">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-001" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-colorbutton="false"  data-widget-refreshbutton="false" data-widget-sortable="false" data-item-id="3">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Live Weather</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div class="h530">
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
					<div class="widget-body  no-padding" style="padding:1% !important;width:auto !important;text-align:center;">
<style>
.inline-group .checkbox{
	float: left;
	/*margin-right: 35px;*/
	margin-right: 25px;
	margin-top:0px !important;
	margin-bottom:0px !important;
	padding-top: 5px;
}
.inline-group .checkbox:last-child{margin-right: 0;}

.inline-group .select{
	float: left;
	margin-right: 9px;
	margin-top:0px !important;
	margin-bottom:0px !important;
}
.mar-30{margin-right:35px !important;}

#a_wid-id-001.jarviswidget > div{font-size:12px;}
#a_wid-id-001 .form-group-sm .form-control, .input-sm {padding: 5px 0px; font-size:11px;}

</style>
		<section>
			<div class="inline-group- text-center" style="display:inline-block-;">
				<label class="select">Wind:
										<select class="input-sm" id="wmetricwind" onchange="windychange('wmetricwind')">
											<option value="default" selected>Default</option>
											<option value="kt">kt</option>
											<option value="m/s">m/s</option>
											<option value="km/h">km/h</option>
											<option value="mph">mph</option>
											<option value="bft">bft</option>
										</select> <i></i>
				</label>
				<label class="select">Temp<span class="hidden-md-">erature</span>:
										<select class="input-sm" id="wmetrictemp" onchange="windychange('wmetrictemp')">
											<option value="default" selected>Default</option>
											<option value="°C">°C</option>
											<option value="°F">°F</option>
										</select> <i></i>
				</label>
				<label class="select mar-30-">Forecast<span class="hidden-md-"> for</span>:
										<select class="input-sm" id="wcal" onchange="windychange('wcal')">
											<option value="" selected>Now</option>
											<option value="12">Next 12 hours</option>
											<option value="24">Next 24 hours</option>
										</select> <i></i>
				</label>
			</div>
			<div class="inline-group-" style="display:inline-block-;">
				<label class="checkbox-">
					<input type="checkbox" name="checkbox-inline" id="showmarker" onclick="windychange('showmarker')">
					<i></i>Show marker in the middle</label>
				<label class="checkbox-">
					<input type="checkbox" name="checkbox-inline" id="pressure" onclick="windychange('pressure')">
					<i></i>Pressure isolines</label>
				<label class="checkbox-">
					<input type="checkbox" name="checkbox-inline" id="spotforecast" onclick="windychange('spotforecast')">
					<i></i>Include spot forecast</label>
			</div>
		</section>

		<iframe id="windy1" class="lazy" width='100%' height='450' data-src='https://embed.windy.com/embed2.html?lat=33.303&lon=-111.819&zoom=5&level=surface&overlay=wind&menu=&message=true&marker=&calendar=&pressure=&type=map&location=coordinates&detail=&detailLat=33.303&detailLon=-111.819&metricWind=default&metricTemp=default&radarRange=-1' frameborder='0' style='margin-left:auto;margin-right:auto;'></iframe>
		<script>
			function windychange(myCheck) {
				var wmetrictemp="default";
				var wmetricwind="default";
				var wcal="";
				var wmarker="";
				var wpressure="";
				var wspot="";
			  var checkBox = document.getElementById(myCheck);
			  var iframewindy = document.getElementById("windy1");
			  if (document.getElementById("showmarker").checked == true){
				wmarker="true";
			  } else {
				wmarker="";
			  }
			  if (document.getElementById("pressure").checked == true){
				wpressure="true";
			  } else {
				wpressure="";
			  }
			  if (document.getElementById("spotforecast").checked == true){
				wspot="true";
			  } else {
				wspot="";
			  }
			var wmw = document.getElementById("wmetricwind");
			wmetricwind = wmw.options[wmw.selectedIndex].value;
			var wmt = document.getElementById("wmetrictemp");
			wmetrictemp = wmt.options[wmt.selectedIndex].value;
			var wct = document.getElementById("wcal");
			wcal = wct.options[wct.selectedIndex].value;

			  document.getElementById("windy1").src="https://embed.windy.com/embed2.html?lat=33.303&lon=-111.819&zoom=5&level=surface&overlay=wind&menu=&message=true&marker="+wmarker+"&calendar="+wcal+"&pressure="+wpressure+"&type=map&location=coordinates&detail="+wspot+"&detailLat=33.303&detailLon=-111.819&metricWind="+wmetricwind+"&metricTemp="+wmetrictemp+"&radarRange=-1";
			}
//var iframe = document.getElementById("windy");
//var elmnt = iframe.contentWindow.document.getElementsById("plugins")[0];
//elmnt.style.display = "none";

$( document ).ready(function() {
$("iframe[id='windy']").contents().find("div[id='plugins']").remove();
		//$("#windy").contents().find("#plugins").remove();
});
		</script>


					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-312">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-312" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="4">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Billing Exceptions</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-32-1" class="no-padding h530">
<?php
//$user_one=28;
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<?php

	$cust_arr=array();
	$firstcust="";
	if ($fdstmt = $mysqli->prepare("SELECT id FROM `exceptions` e,user up where up.user_id = '".$user_one."' and up.company_id = e.`Customer ID` LIMIT 1")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows == 0) {
			echo "<p class='centrr'>No data to show!</p>";
		}else{
?>
<iframe id="framecid" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/billing-exceptions-details.php?action=cid&cnt=<?php echo(rand(10,100)); ?>" width="100%" height="279px" frameBorder="0" scrolling="no"></iframe>
<iframe id="frame" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/billing-exceptions-details.php?action=mid&cnt=<?php echo(rand(10,100)); ?>" width="100%" height="279px" frameBorder="0" scrolling="no"></iframe>
<?php
		}
	}
?>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->


	<?php
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


		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-11"> -->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-11" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="5">
				<header>
					<span class="widget-icon"> <i class="fa fa-user"></i> </span>
					<h2><strong>Your Energy Team</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<iframe id="your_energy_team" class="lazy" data-src='https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/your-energy-team.php' name="your_energy_team" width="100%" height="360" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->


		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-21">-->
		
		<!--
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-21" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="6">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Summary</strong> <i></i></h2>

					<?php //echo $widgettools; ?>
				</header>

				<div class="no-padding">

					<div class="jarviswidget-editbox">

					</div>

					<div class="widget-body">
						<iframe id="framesummary" class="lazy" data-src="https://<?php //echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard-summarynew2.php?&ct=<?php //echo(rand(10,100)); ?>" width="100%" height="360" frameBorder="0" scrolling="no"></iframe>
					</div>

				</div>

			</div>
		-->
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->




		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-411">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-411" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="7">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Financial Markets</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">

			<iframe id="loadgraph" class="lazy" data-src='https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/subpages/stockchart-graph.php?ct=<?php echo time(); ?>&symbol=<?php //echo rawurlencode ($firstsymbol); ?>' width="100%" height="450" frameBorder="0" scrolling="no"></iframe>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->





		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-412">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-412" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="8">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Energy Markets</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">

			<iframe id="loadegraph" class="lazy" data-src='https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/subpages/energy-graph.php?ct=<?php echo time(); ?>&symbol=<?php //echo rawurlencode ($firstsymbol); ?>' width="100%" height="450" frameBorder="0" scrolling="no"></iframe>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->



		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-511">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-511" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="9">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>North America Streaming News</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">
						<iframe id="northamerica_id" name="rssfeed_frame" width="100%" height="450" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/subpages/northamericanews.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
						<script>
						$( document ).ready(function() {

						});
						</script>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->


		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-512">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-512" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false"  data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="10">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>European Streaming News</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">
						<iframe id="europenews_id" name="rssfeed_frame" width="100%" height="450" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/subpages/europenews.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
						<script>
						$( document ).ready(function() {

						});
						</script>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->





		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-31">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-31" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="11">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Savings Analysis</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-2-1" class="lazy" data-loader="ajax" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard_sa.php?<?php echo time()?>"></div>
				
				<!--
				<div id="wid-id-2-1" class="no-padding">
					<iframe id="saving_analysis"  name="saving_analysis" width="100%" height="100%" frameborder="0" class="lazy" data-src="https://<?php //echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard_sa-iframe.php?<?php //echo time()?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				-->
				
				<!--<div id="wid-id-2-1" class="lazy" data-loader="ajax" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard_sa_test.php?<?php echo time()?>"></div>-->
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-32">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-32" data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="12">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Focus Items</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-3-1" class="lazy" data-loader="ajax" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard_fi.php?<?php echo time();?>"></div>
				<!--<div id="wid-id-3-1" class="lazy" data-loader="ajax" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/dashboard_fi_test.php?<?php echo time();?>"></div>-->
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->


		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-666">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-666" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="13">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>New Accounts</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-666-1" class="no-padding h530">
					<iframe id="new_accounts_id"  name="new_accounts" width="100%" height="530" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/new_accounts.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->


		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-999">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-999" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="14">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Balance Forward Exceptions</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-999-1" class="no-padding h530">
					<iframe id="balance_forward_exceptions_id" name="balance_forward_exceptions" width="100%" height="530" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/balance_forward_exceptions.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-777">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-777" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="15">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Invoices Processed</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-777-1" class="no-padding h530">
					<iframe id="invoices_processed_id" name="invoices_processed" width="100%" height="528" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/invoices_processed.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="a_wid-id-888">-->
			<div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-888" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="16">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Supply Agreements</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-888-1" class="no-padding h530">
					<iframe id="contract_dates_id" name="contract_dates" width="100%" height="529" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/contract_dates.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
		
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-777">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-122" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="17">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>No. of Users</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-122-1" class="no-padding h530">
					<iframe id="no_of_users_id" name="no_of_users" width="100%" height="528" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/no_of_users.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
		
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-777">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-133" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="18">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Electricity</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-133-1" class="no-padding h530">
					<iframe id="widget_electric_id" name="widget_electric" width="100%" height="528" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/widget_electric.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
		
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-777">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-144" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="19">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Natural Gas</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-144-1" class="no-padding h530">
					<iframe id="widget_gas_id" name="widget_gas" width="100%" height="528" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/widget_gas.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
		
		<!-- NEW WIDGET START -->
		<!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="a_wid-id-777">-->
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-6 col-lg-6 grid-item grid-item-drag" id="a_wid-id-155" data-widget-fullscreenbutton="false" data-widget-editbutton="false"  data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-refreshbutton="false" data-widget-colorbutton="false" data-widget-sortable="false" data-item-id="20">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2><strong>Water</strong> <i></i></h2>

					<?php echo $widgettools; ?>
				</header>

				<!-- widget div-->
				<div id="wid-id-155-1" class="no-padding h530">
					<iframe id="widget_water_id" name="widget_water" width="100%" height="528" frameborder="0" class="lazy" data-src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/ajax/widget_water.php" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		<!--</article>-->
		<!-- WIDGET END -->
		
		
		

<?php } ?>
		</article>
	</div>

	<!-- end row -->
</section>
<!-- end widget grid -->
<br>
<br>
<?php
if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5))
{
?>
<div id="firesponse" class="dashboard-content"></div>
<div id="fitopdialog" class="dashboard-content"></div>
<div id="satopdialog" class="dashboard-content"></div>
<div id="sndialog" class="streamnewsdialog" title="Preview"></div>
<?php } ?>
<!-- end widget grid -->





<div class="map-content"></div>

<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){if($tmp_ao_permit == 1 or $tmp_cs_permit == 1){?>
$(document).ready(function(){
	<?php if($tmp_cs_permit==1){?>
	$("#Capturis").click(function(){
		window.open('assets/ajax/autologin.php?w=d1befa03c79ca0b84ecc488dea96bc68','_blank');
	});
	<?php } if($tmp_ao_permit==1){?>
	$("#Accuvio").click(function(){
		window.open('assets/ajax/autologin.php?w=fbce0bb98d18aca35b2938c78f52f57b','_blank');
	});
	<?php } ?>
});
<?php } } ?>

function navigateurl2(parturl){
	$('.dashboard-content').hide();
	$('.map-content').show();
	$('.map-content').load(parturl);
}
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){ ?>

// set some widgets to full width by default
localStorage.setItem("large_wid_ids", '["a_wid-id-01","a_wid-id-31","a_wid-id-32","a_wid-id-777"]');

<?php
	/* create a prepared statement */
	if ($stmt = $mysqli->prepare("SELECT dashboard FROM user WHERE user_id= '".$user_one."' LIMIT 1")) {

    /* bind parameters for markers */
    //$stmt->bind_param("s", $city);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($dashboard);

    /* fetch value */
    $stmt->fetch();

	}

	if (isset($dashboard) and !empty($dashboard)) {

	 $dbSettingPhpArray = unserialize($dashboard);
	 ?>

	var db_obj_storage = <?php echo json_encode($dbSettingPhpArray) ?>;

	if ("Plugin_settings__widget-grid" in db_obj_storage) {
		localStorage.setItem("Plugin_settings__widget-grid", db_obj_storage['Plugin_settings__widget-grid']);
	}

	if ("large_wid_ids" in db_obj_storage) {
		localStorage.setItem("large_wid_ids", db_obj_storage['large_wid_ids']);
	}

	if ("dragPositions" in db_obj_storage) {
		localStorage.setItem("dragPositions", db_obj_storage['dragPositions']);
	}

	if ("lock_ids" in db_obj_storage) {
		localStorage.setItem("lock_ids", db_obj_storage['lock_ids']);
	}

	if ("hidden_wid_ids" in db_obj_storage) {
		localStorage.setItem("hidden_wid_ids", db_obj_storage['hidden_wid_ids']);
	}

	if ("height_wid_ids" in db_obj_storage) {
		localStorage.setItem("height_wid_ids", db_obj_storage['height_wid_ids']);
	}

	<?php } ?>


	pageSetUp();

	// PAGE RELATED SCRIPTS

	// pagefunction

	var pagefunction = function() {
		//$('#wid-id-2-1').load('assets/ajax/dashboard_sa.php?'+Math.random());
		//$('#wid-id-3-1').load('assets/ajax/dashboard_fi.php?'+Math.random());
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$(".togglewidth").on( "click", function( event ) {
			var $athis = $( event.target ).closest( "article" );
		  if($athis.hasClass( "col-sm-6" ) == true){
			  $athis.removeClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.addClass( "col-sm-12 col-md-12 col-lg-12" );
		  }else{
			  $athis.addClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.removeClass( "col-sm-12 col-md-12 col-lg-12" );
		  }
		});

		$(".toggleclose").on( "click", function( event ) {
			//var $athis = $( event.target ).closest( "article" );
			var $athis = $( event.target ).closest( ".jarviswidget" );
			var wid_id = $athis.attr('id');
			$athis.addClass( "hidden" );

			/*
			if($athis.attr( "id" ) == 'a_wid-id-01') $('input[type="checkbox"]#w-site-map').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-11') $('input[type="checkbox"]#w-your-energy-adv').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-31') $('input[type="checkbox"]#w-saving-analysis').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-21') $('input[type="checkbox"]#w-summary').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-32') $('input[type="checkbox"]#w-focus-items').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-312') $('input[type="checkbox"]#w-billingexc').prop('checked', false);
			if($athis.attr( "id" ) == 'a_wid-id-001') $('input[type="checkbox"]#w-windy').prop('checked', false);
			*/

			$('input[type="checkbox"].chk-'+wid_id).prop('checked', false);


			save_hidden_widget();

		});


		/*

		//by amir

		$('input[type="checkbox"]#w-financial').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-financial", "y"),$('#a_wid-id-411').removeClass("hidden")) : (localStorage.setItem("sm-financial", "n"),$('#a_wid-id-411').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-north-america').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-north-america", "y"),$('#a_wid-id-511').removeClass("hidden")) : (localStorage.setItem("sm-north-america", "n"),$('#a_wid-id-511').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-energy').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-energy", "y"),$('#a_wid-id-412').removeClass("hidden")) : (localStorage.setItem("sm-energy", "n"),$('#a_wid-id-412').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-new-accounts').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-new-accounts", "y"),$('#a_wid-id-666').removeClass("hidden")) : (localStorage.setItem("sm-new-accounts", "n"),$('#a_wid-id-666').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-europe').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-europe", "y"),$('#a_wid-id-512').removeClass("hidden")) : (localStorage.setItem("sm-europe", "n"),$('#a_wid-id-512').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-contracts').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-contracts", "y"),$('#a_wid-id-888').removeClass("hidden")) : (localStorage.setItem("sm-contracts", "n"),$('#a_wid-id-888').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-balance-forward').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-balance-forward", "y"),$('#a_wid-id-999').removeClass("hidden")) : (localStorage.setItem("sm-balance-forward", "n"),$('#a_wid-id-411').addClass("hidden"))
		});

		$('input[type="checkbox"]#w-invoices-processed').click(function() {
		 $(this).is(":checked") ?  (localStorage.setItem("sm-invoices-processed", "y"),$('#a_wid-id-777').removeClass("hidden")) : (localStorage.setItem("sm-invoices-processed", "n"),$('#a_wid-id-777').addClass("hidden"))
		});

		//end by amit


		if (localStorage.getItem("sm-sitemap") == "n") {
			$('#a_wid-id-01').addClass("hidden");
			$('input[type="checkbox"]#w-site-map').prop('checked', false);
		}else{
			localStorage.setItem("sm-sitemap","y");
			$('#a_wid-id-01').removeClass("hidden");
			$('input[type="checkbox"]#w-site-map').prop('checked', true);
		}

		if (localStorage.getItem("sm-yourenergy") == "n") {
			$('#a_wid-id-11').addClass("hidden");
			$('input[type="checkbox"]#w-your-energy-adv').prop('checked', false);
		}else{
			localStorage.setItem("sm-yourenergy","y");
			$('#a_wid-id-11').removeClass("hidden");
			$('input[type="checkbox"]#w-your-energy-adv').prop('checked', true);
		}

		if (localStorage.getItem("sm-saving") == "n") {
			$('#a_wid-id-31').addClass("hidden");
			$('input[type="checkbox"]#w-saving-analysis').prop('checked', false);
		}else{
			localStorage.setItem("sm-saving","y");
			$('#a_wid-id-31').removeClass("hidden");
			$('input[type="checkbox"]#w-saving-analysis').prop('checked', true);
		}


		if (localStorage.getItem("sm-summary") == "n") {
			$('#a_wid-id-21').addClass("hidden");
			$('input[type="checkbox"]#w-summary').prop('checked', false);
		}else{
			localStorage.setItem("sm-summary","y");
			$('#a_wid-id-21').removeClass("hidden");
			$('input[type="checkbox"]#w-summary').prop('checked', true);
		}

		if (localStorage.getItem("sm-focus") == "n") {
			$('#a_wid-id-32').addClass("hidden");
			$('input[type="checkbox"]#w-focus-items').prop('checked', false);
		}else{
			localStorage.setItem("sm-focus","y");
			$('#a_wid-id-32').removeClass("hidden");
			$('input[type="checkbox"]#w-focus-items').prop('checked', true);
		}

		if (localStorage.getItem("sm-windy") == "n") {
			$('#a_wid-id-001').addClass("hidden");
			$('input[type="checkbox"]#w-windy').prop('checked', false);
		}else{
			localStorage.setItem("sm-windy","y");
			$('#a_wid-id-001').removeClass("hidden");
			$('input[type="checkbox"]#w-windy').prop('checked', true);
		}

		if (localStorage.getItem("sm-billingexc") == "n") {
			$('#a_wid-id-312').addClass("hidden");
			$('input[type="checkbox"]#w-billingexc').prop('checked', false);
		}else{
			localStorage.setItem("sm-billingexc","y");
			$('#a_wid-id-312').removeClass("hidden");
			$('input[type="checkbox"]#w-billingexc').prop('checked', true);
		}

		// by amir
		if (localStorage.getItem("sm-financial") == "n") {
			$('#a_wid-id-411').addClass("hidden");
			$('input[type="checkbox"]#w-financial').prop('checked', false);
		}else{
			localStorage.setItem("sm-financial","y");
			$('#a_wid-id-411').removeClass("hidden");
			$('input[type="checkbox"]#w-financial').prop('checked', true);
		}
		if (localStorage.getItem("sm-america") == "n") {
			$('#a_wid-id-511').addClass("hidden");
			$('input[type="checkbox"]#w-north-america').prop('checked', false);
		}else{
			localStorage.setItem("sm-america","y");
			$('#a_wid-id-511').removeClass("hidden");
			$('input[type="checkbox"]#w-north-america').prop('checked', true);
		}
		if (localStorage.getItem("sm-energy") == "n") {
			$('#a_wid-id-412').addClass("hidden");
			$('input[type="checkbox"]#w-energy').prop('checked', false);
		}else{
			localStorage.setItem("sm-energy","y");
			$('#a_wid-id-412').removeClass("hidden");
			$('input[type="checkbox"]#w-energy').prop('checked', true);
		}
		if (localStorage.getItem("sm-accounts") == "n") {
			$('#a_wid-id-666').addClass("hidden");
			$('input[type="checkbox"]#w-new-accounts').prop('checked', false);
		}else{
			localStorage.setItem("sm-accounts","y");
			$('#a_wid-id-666').removeClass("hidden");
			$('input[type="checkbox"]#w-new-accounts').prop('checked', true);
		}
		if (localStorage.getItem("sm-europe") == "n") {
			$('#a_wid-id-512').addClass("hidden");
			$('input[type="checkbox"]#w-europe').prop('checked', false);
		}else{
			localStorage.setItem("sm-europe","y");
			$('#a_wid-id-512').removeClass("hidden");
			$('input[type="checkbox"]#w-europe').prop('checked', true);
		}
		if (localStorage.getItem("sm-contracts") == "n") {
			$('#a_wid-id-888').addClass("hidden");
			$('input[type="checkbox"]#w-contracts').prop('checked', false);
		}else{
			localStorage.setItem("sm-contracts","y");
			$('#a_wid-id-888').removeClass("hidden");
			$('input[type="checkbox"]#w-contracts').prop('checked', true);
		}
		if (localStorage.getItem("sm-balance") == "n") {
			$('#a_wid-id-999').addClass("hidden");
			$('input[type="checkbox"]#w-balance-forward').prop('checked', false);
		}else{
			localStorage.setItem("sm-balance","y");
			$('#a_wid-id-999').removeClass("hidden");
			$('input[type="checkbox"]#w-balance-forward').prop('checked', true);
		}
		if (localStorage.getItem("sm-invoices") == "n") {
			$('#a_wid-id-777').addClass("hidden");
			$('input[type="checkbox"]#w-invoices-processed').prop('checked', false);
		}else{
			localStorage.setItem("sm-invoices","y");
			$('#a_wid-id-777').removeClass("hidden");
			$('input[type="checkbox"]#w-invoices-processed').prop('checked', true);
		}

		// end by amir

		*/





	};
	
	/*
	loadScript("//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
		loadScript("//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
			loadScript("//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
			loadScript("//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
			loadScript("//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
			loadScript("//cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
				loadScript("//cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
				loadScript("//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
			});
			});
			});
			});
			});
		});
	});
	*/
	
	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		pagefunction();
	});

	function launchunsa(dtype)
	{
		var distype="";
		if(dtype=="unread"){distype="?type=unread";}
		window.location = '<?php echo APP_URL;?>/index.php#assets/ajax/saving-analysis-edit.php'+distype;
	}

	function launchunfi(dtype)
	{
		var distype="";
		if(dtype=="unread"){distype="?type=unread";}
		window.location = '<?php echo APP_URL;?>/index.php#assets/ajax/focus-items-edit.php'+distype;
	}

	var theight=$(document).height();
	$( "#sndialog" ).dialog({
	  /*height: $(document).height(),*/
	  /*height: (screen.height*0.78),*/
      /*width: (screen.availWidth*0.78),*/
      show: "fade",
      hide: "fade",
	  title: 'News',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false
    });

	$("body").on("click",".ui-widget-overlay",function() {
		 $('#sndialog').dialog( "close" );
	});

	// end pagefunction

	// run pagefunction on load

	////pagefunction();

<?php }elseif(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
		$(".togglewidth").on( "click", function( event ) {
			var $athis = $( event.target ).closest( "article" );
		  if($athis.hasClass( "col-sm-6" ) == true){
			  $athis.removeClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.addClass( "col-sm-12 col-md-12 col-lg-12" );
		  }else{
			  $athis.addClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.removeClass( "col-sm-12 col-md-12 col-lg-12" );
		  }
		});
<?php } ?>
</script>


<!-- packery started -->


<style>
.grid {padding:0;}
.grid-item {padding-left:13px; padding-right:13px;}
.grid-item-drag header {cursor: move;}
div.ui-state-disabled.ui-draggable-disabled {opacity: 1;}
/*
div[role="content"] {pointer-events: none;}
*/

</style>


<script type="text/javascript">
	var $grid;
	loadScript("assets/js/packery.pkgd.js", function(){
		loadScript("assets/js/draggabilly.pkgd.js", function(){


			// // initialize Packery
			// //var $grid = $('.grid').packery({
			// $grid = $('.grid').packery({
			  // itemSelector: '.grid-item',
			  // // columnWidth helps with drop positioning
			  // //columnWidth: 100
			  // });


			// // make all items draggable
			// var $items = $grid.find('.grid-item-drag').draggable();
			// // bind drag events to Packery
			// $grid.packery('bindUIDraggableEvents', $items);





			// external js: packery.pkgd.js, draggabilly.pkgd.js

// add Packery.prototype methods



// get JSON-friendly data for items positions
Packery.prototype.getShiftPositions = function (attrName) {
  attrName = attrName || 'id';
  var _this = this;
  return this.items.map(function (item) {
    return {
      attr: item.element.getAttribute(attrName),
      x: item.rect.x / _this.packer.width };

  });
};

Packery.prototype.initShiftLayout = function (positions, attr) {
  if (!positions) {
    // if no initial positions, run packery layout
    this.layout();
    return;
  }
  // parse string to JSON
  if (typeof positions == 'string') {
    try {
      positions = JSON.parse(positions);
    } catch (error) {
      console.error('JSON parse error: ' + error);
      this.layout();
      return;
    }
  }

  attr = attr || 'id'; // default to id attribute
  this._resetLayout();
  // set item order and horizontal position from saved positions
  this.items = positions.map(function (itemPosition) {
    var selector = '[' + attr + '="' + itemPosition.attr + '"]';
    var itemElem = this.element.querySelector(selector);
    var item = this.getItem(itemElem);
    item.rect.x = itemPosition.x * this.packer.width;
    return item;
  }, this);
  this.shiftLayout();
};





// -----------------------------//

// init Packery
//var $grid = $('.grid').packery({
$grid = $('.grid').packery({
  itemSelector: '.grid-item',
  //columnWidth: '.grid-sizer',
  //percentPosition: true,
  //initLayout: false // disable initial layout
});

// get saved dragged positions
var initPositions = localStorage.getItem('dragPositions');

if (!Array.isArray(initPositions)) {
	//initPositions = [];
}
// init layout with saved positions
$grid.packery('initShiftLayout', initPositions, 'data-item-id');

// make draggable
/*
$grid.find('.grid-item').each(function (i, itemElem) {
  var draggie = new Draggabilly(itemElem);
  $grid.packery('bindDraggabillyEvents', draggie);
});
*/
			/*
			// make all items draggable
			var $items = $grid.find('.grid-item-drag').draggable();
			// bind drag events to Packery
			$grid.packery('bindUIDraggableEvents', $items);
			*/
			set_dragable();

			set_lock_wid();

			set_hidden_wid();

// save drag positions on event
$grid.on('dragItemPositioned', function () {
  // save drag positions
  var positions = $grid.packery('getShiftPositions', 'data-item-id');
  localStorage.setItem('dragPositions', JSON.stringify(positions));
  save_dashboard();
});
//# sourceURL=pen.js



//set_large_wid();
//set_pack();

		});
	}); // end loadScript

	function set_dragable() {

		// make all items draggable
		var $items = $grid.find('.grid-item-drag').draggable();
		// bind drag events to Packery
		$grid.packery('bindUIDraggableEvents', $items);
		/*
		$grid.on( 'dragItemPositioned', function( event, draggedItem ) {
			alert('here');
		} )
		*/
		
		/*
		$items.on('dragStart',function( event, pointer ) {
			alert('dragStart');
		});
		*/

		/*
		$grid.find('.grid-item-drag').each(function (i, itemElem) {
			console.log(itemElem);
			var draggie = new Draggabilly(itemElem);
			$grid.packery('bindDraggabillyEvents', draggie);
		});
		*/
	}

	function set_pack() {
		if ($grid !== undefined) {
			$grid.packery();
		}
	}
	/*
	function set_wid_width() {
		var $athis = $( event.target ).closest( "article" );
		  if($athis.hasClass( "col-sm-6" ) == true){
			  $athis.removeClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.addClass( "col-sm-12 col-md-12 col-lg-12" );
		  }else{
			  $athis.addClass( "col-sm-6 col-md-6 col-lg-6" );
			  $athis.removeClass( "col-sm-12 col-md-12 col-lg-12" );
		  }
	}
	*/
	
	$( ".jarviswidget > header" ).on( "mousedown", function(e) {
	  //$( this ).find("div[role='content']").css("opacity", 0);
	  //$( this ).find("div[role='content']").css("position", "relative").append("<div class='jar_overlay'></div>");
	  ////$( this ).find("div[role='content']").append("<div class='jar_overlay'></div>");
	  $( this ).parent().find("div[role='content']").append("<div class='jar_overlay'></div>");
	  /*
	    $("<div />").css({
			position: "absolute",
			width: "100%",
			height: "100%",
			left: 0,
			top: 0,
			zIndex: 1000000,  // to be on the safe side
			"background-color": "#000"
		}).appendTo($(".jarviswidget").css("position", "relative"));
		*/
	  //$(this).css("visibility", "hidden");
	  //console.log( "mousedown" );
	} );
	
	$( ".jarviswidget" ).on( "mouseup", function() {
	  //console.log( "mouseup" );
	  //$( this ).find("div[role='content']").css("visibility", "visible");
	  $( this ).find("div[role='content'] .jar_overlay").remove();
	} );

	$(".toggleheightpack").on( "click", function( event ) {
		event.preventDefault();

			var jwidgetobj = $( this ).parents(".jarviswidget");
			var jcontentobj = jwidgetobj.find("div[role='content']");
			var wjId = jwidgetobj.attr('id');

			if(jwidgetobj.hasClass( "full_height" ) == true){
			  jwidgetobj.removeClass( "full_height" );
			}else{
			  jwidgetobj.addClass( "full_height" );
			}

			if(jcontentobj.hasClass( "set_height" ) == true){
				jcontentobj.removeClass( "set_height" );
			}else{
				jcontentobj.addClass( "set_height" );
			}

			set_ar_height(wjId);

			set_pack();
			save_height_widget();
			  /*
			  save_large_widget();
			  */
		});

		function set_ar_height(jId) {

			//var $athis = $( this ).parents(".jarviswidget").find("iframe");
			var jwidget = $( "#"+jId )
			var jcontent = jwidget.find("div[role='content']");
			var cHeight = jcontent.height();
			//var jId = jwidget.attr('id');
			//var jId = wjId;
			//console.log(jId);

			/*
			  if(jwidget.hasClass( "full_height" ) == true){
				  jwidget.removeClass( "full_height" );
			  }else{
				  jwidget.addClass( "full_height" );
			  }
			*/

			  if (jId == 'a_wid-id-01') { // site map

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.css('cssText', 'height: 452px !important;');
					$("#dashboard_map").contents().find('#map_wrapper').height('450');
					jcontent.find('#dashboard_map').height('450');
					//$athis.height( cHeight/2 );

				  }else{
					////jcontent.addClass( "set_height" );

					jcontent.css('cssText', 'height: 652px !important;');
					$("#dashboard_map").contents().find('#map_wrapper').height('650');
					jcontent.find('#dashboard_map').height('650');
				  }
			  }

			  if (jId == 'a_wid-id-312') { //billing

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#framecid').height('279');
					jcontent.find('#frame').height('279');

				  }else{
					////jcontent.addClass( "set_height" );

					jcontent.css('cssText', 'height: 830px !important;');
					jcontent.find('#framecid').height('415');
					jcontent.find('#frame').height('415');
				  }
			  }

			  if (jId == 'a_wid-id-21') { // summary

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.css('cssText', 'max-height: 362px !important; height: 362px !important;');
					//jcontent.find('.set_col_1').addClass('height-344');
					jcontent.find('#framesummary').height('360');
					$("#framesummary").contents().find("body").find('.height-340').height(340);
					$("#framesummary").contents().find("body").find('.cnt2').height(349);
					$("#framesummary").contents().find("body").find('.cnt3').height(321);

				  }else{

					////jcontent.addClass( "set_height" );

					jcontent.css('cssText', 'max-height: 562px !important; height: 562px !important;');
					jcontent.find('.sum_col_1').removeClass('height-344');
					//jcontent.find('.sum_col_1').height('544');
					jcontent.find('#framesummary').height('560');
					$("#framesummary").contents().find("body").find('.height-340').height(540);
					$("#framesummary").contents().find("body").find('.cnt2').height(549);
					$("#framesummary").contents().find("body").find('.cnt3').height(521);

				  }
			  }

			  if (jId == 'a_wid-id-001') { //weather

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.addClass( "h530" );
					//jcontent.css('cssText', 'max-height: 562px !important;');
					jcontent.find('#windy1').height('450');
				  }else{
					////jcontent.addClass( "set_height" );

					jcontent.removeClass( "h530" );
					jcontent.css('cssText', 'max-height: 830px !important;');
					jcontent.find('#windy1').height('750');
				  }
			  }
			  if (jId == 'a_wid-id-11') { //energy team

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.css('cssText', 'max-height: 362px !important; height: 362px !important;');
					jcontent.css('overflow','hidden');

				  }else{
					////jcontent.addClass( "set_height" );

					jcontent.css('cssText', 'max-height: 562px !important; height: 562px !important;');
					jcontent.css('overflow','visible');
				  }
			  }
			  if (jId == 'a_wid-id-411') { //financial widget

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					$("#loadgraph").contents().find('.symbollist').height(390);
					jcontent.find('#loadgraph').height(409);
					$("#loadgraph").contents().find("body").find('#chartdiv').height(300);

				  }else{
					////jcontent.addClass( "set_height" );

					$("#loadgraph").contents().find('.symbollist').height(590);
					jcontent.find('#loadgraph').height(609);
					$("#loadgraph").contents().find("body").find('#chartdiv').height(500);
				  }
			  }
			  if (jId == 'a_wid-id-412') { //energy widget

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					//jcontent.find('.symbollist').height(416);
					$("#loadegraph").contents().find('.symbollist').height(390);
					jcontent.find('#loadegraph').height(409);
					$("#loadegraph").contents().find("body").find('#chartdiv').height(300);

				  }else{
					////jcontent.addClass( "set_height" );

					$("#loadegraph").contents().find('.symbollist').height(590);
					jcontent.find('#loadegraph').height(609);
					$("#loadegraph").contents().find("body").find('#chartdiv').height(500);
				  }
			  }
			  if (jId == 'a_wid-id-511') { //north america

				  if(jcontent.hasClass( "set_height" ) == false){
					//jcontent.removeClass( "set_height" );

					jcontent.find('#northamerica_id').height(450);
				  }else{
					//jcontent.addClass( "set_height" );

					jcontent.find('#northamerica_id').height(750);
				  }
			  }
			  if (jId == 'a_wid-id-512') { //europe

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					jcontent.find('#europenews_id').height(450);
				  }else{
					////jcontent.addClass( "set_height" );

					jcontent.find('#europenews_id').height(750);
				  }
			  }
			  if (jId == 'a_wid-id-31') { //saving analysis

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );

					/*
					if (typeof otable1 !== 'undefined') {
					  otable1.page.len(6).draw();
					}
					*/
					if (typeof otable_sa !== 'undefined') {
					  otable_sa.page.len(6).draw();
					}

					//jcontent.height('auto');
				  }else{

					////jcontent.addClass( "set_height" );

					/*
					if (typeof otable1 !== 'undefined') {

					  otable1.page.len(12).draw();
					}
					*/
					if (typeof otable_sa !== 'undefined') {
					  otable_sa.page.len(12).draw();
					}

					//jcontent.height(300);
				  }
			  }
			  if (jId == 'a_wid-id-32') { //focus items

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					/*
					if (typeof otable2 !== 'undefined') {
					  otable2.page.len(6).draw();
					}
					*/
					if (typeof otable_fi !== 'undefined') {
					  otable_fi.page.len(6).draw();
					}
					//jcontent.height('auto');
				  }else{
					////jcontent.addClass( "set_height" );
					/*
					if (typeof otable2 !== 'undefined') {
					  otable2.page.len(12).draw();
					}
					*/
					if (typeof otable_fi !== 'undefined') {
					  otable_fi.page.len(12).draw();
					}
					//jcontent.height(300);
				  }
			  }
			  if (jId == 'a_wid-id-666') { //new account

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#new_accounts_id').height(530);
					$("#new_accounts_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#new_accounts_id').height(730);
					$("#new_accounts_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  if (jId == 'a_wid-id-999') { //balance_forward_exceptions

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#balance_forward_exceptions_id').height(530);
					$("#balance_forward_exceptions_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					jcontent.addClass( "set_height" );
					////jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#balance_forward_exceptions_id').height(730);
					$("#balance_forward_exceptions_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  if (jId == 'a_wid-id-777') { //invoices_processed

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#invoices_processed_id').height(529);
					$("#invoices_processed_id").contents().find("body").find('#chartdiv').height(365);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#invoices_processed_id').height(729);
					$("#invoices_processed_id").contents().find("body").find('#chartdiv').height(535);
				  }
			  }
			  if (jId == 'a_wid-id-888') { //contract_dates

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#contract_dates_id').height(529);
					$("#contract_dates_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#contract_dates_id').height(729);
					$("#contract_dates_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  
			  
			  
			  
			  
			  
			  
			  if (jId == 'a_wid-id-122') { //no_of_users

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#no_of_users_id').height(529);
					$("#no_of_users_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#no_of_users_id').height(729);
					$("#no_of_users_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  
			  if (jId == 'a_wid-id-133') { //widget_electric

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#widget_electric_id').height(529);
					$("#widget_electric_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#widget_electric_id').height(729);
					$("#widget_electric_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  
			  if (jId == 'a_wid-id-444') { //widget_gas

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#widget_gas_id').height(529);
					$("#widget_gas_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#widget_gas_id').height(729);
					$("#widget_gas_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
			  
			  if (jId == 'a_wid-id-555') { //widget_water

				  if(jcontent.hasClass( "set_height" ) == false){
					////jcontent.removeClass( "set_height" );
					jcontent.css('cssText', 'height: 530px !important;');
					jcontent.find('#widget_water_id').height(529);
					$("#widget_water_id").contents().find("body").find('#chartdiv').height(500);
				  }else{
					////jcontent.addClass( "set_height" );
					jcontent.css('cssText', 'height: 730px !important;');
					jcontent.find('#widget_water_id').height(729);
					$("#widget_water_id").contents().find("body").find('#chartdiv').height(700);
				  }
			  }
		}

	$(".togglewidthpack").on( "click", function( event ) {
			var $athis = $( event.target ).closest( ".jarviswidget" );
		  if($athis.hasClass( "col-md-6" ) == true){
			  $athis.removeClass( "col-sm-12 col-md-6 col-lg-6" );
			  $athis.addClass( "col-sm-12 col-md-12 col-lg-12" );
		  }else{
			  $athis.addClass( "col-sm-12 col-md-6 col-lg-6" );
			  $athis.removeClass( "col-sm-12 col-md-12 col-lg-12" );
		  }
		  set_pack();
		  save_large_widget();
		});

	$(".togglelockpack").on( "click", function( event ) {
			var $athis = $( this ).parents(".jarviswidget");
			var $fa = $(this).find(".fa");
		  if($athis.hasClass( "grid-item-drag" ) == true){
			  $athis.removeClass( "grid-item-drag" );
			  $athis.removeClass( "ui-draggable" );
			  $fa.removeClass("fa-unlock");
			  $fa.addClass("fa-lock");
			  //$(this).data("original-title","Lock");
			  $(this).attr("data-original-title","Unlock"); //setter
			  $athis.draggable( "disable" ); /*$('#item-id').draggable( "destroy" ); */
			  //$(this).droppable("disable");
			  $athis.addClass( "grid-item-lock" );
		  }else{
			  $athis.addClass( "grid-item-drag" );
			  $fa.removeClass("fa-lock");
			  $fa.addClass("fa-unlock");
			  //$(this).data("original-title","Unlock");
			  $(this).attr("data-original-title","Lock"); //setter
			  $athis.draggable( "enable" );
			  $athis.removeClass( "grid-item-lock" );
		  }
		  save_lock_widget();
		});



		function save_large_widget() {
			var large_ids = [];
			$('.jarviswidget.col-md-12').each(function(i, obj) {
				large_ids[i] = $(this).attr('id');
			});
			localStorage.setItem("large_wid_ids", JSON.stringify(large_ids));
			save_dashboard();
			//console.log(large_ids);
		}

		function set_large_wid() {
			var local_large_ids = JSON.parse(localStorage.getItem("large_wid_ids"));
			//console.log(local_large_ids);
			if (Array.isArray(local_large_ids)) {
				$.each(local_large_ids,function(index, value) {
					//for (i = 0; i < local_large_ids.length; i++) {
						//console.log(local_large_ids[i]);
						var idd = '#'+value;
						//console.log(idd);
						$(idd).removeClass( "col-sm-12 col-md-6 col-lg-6" );
						$(idd).addClass( "col-sm-12 col-md-12 col-lg-12" );
					  //text += local_large_ids[i] + "<br>";
					//}
					//large_ids[i] = $(this).attr('id');
				});
			}
		}

		function save_lock_widget() {
			var lock_ids = [];
			$('.jarviswidget.grid-item-lock').each(function(i, obj) {
				lock_ids[i] = $(this).attr('id');
			});
			localStorage.setItem("lock_ids", JSON.stringify(lock_ids));
			save_dashboard();
			//console.log(large_ids);
		}

		function set_lock_wid() {
			var local_lock_ids = JSON.parse(localStorage.getItem("lock_ids"));
			//console.log(local_lock_ids);
			if (Array.isArray(local_lock_ids)) {
				$.each(local_lock_ids,function(index, value) {
					var idd = '#'+value;
					var $athis = $( idd );
					var $fa = $(idd).find(".togglelockpack .fa");
				  if($athis.hasClass( "grid-item-drag" ) == true){
					  $athis.removeClass( "grid-item-drag" );
					  $athis.removeClass( "ui-draggable" );
					  $fa.removeClass("fa-unlock");
					  $fa.addClass("fa-lock");
					  //$(this).data("original-title","Lock");
					  $(this).attr("data-original-title","Unlock"); //setter
					  $athis.draggable( "disable" );
					  //$(this).droppable("disable");
					  $athis.addClass( "grid-item-lock" );
				  }else{
					  $athis.addClass( "grid-item-drag" );
					  $fa.removeClass("fa-lock");
					  $fa.addClass("fa-unlock");
					  //$(this).data("original-title","Unlock");
					  $(this).attr("data-original-title","Lock"); //setter
					  $athis.draggable( "enable" );
					  $athis.removeClass( "grid-item-lock" );
				  }

				});
			}
		}

		function save_hidden_widget() {
			var hidden_wid_ids = [];
			$('.jarviswidget.hidden').each(function(i, obj) {
				hidden_wid_ids[i] = $(this).attr('id');
			});
			localStorage.setItem("hidden_wid_ids", JSON.stringify(hidden_wid_ids));
			save_dashboard();
			//console.log(large_ids);
		}

		function set_hidden_wid() {
			var hidden_wid_ids = JSON.parse(localStorage.getItem("hidden_wid_ids"));
			//console.log(local_large_ids);
			if (Array.isArray(hidden_wid_ids)) {
				$.each(hidden_wid_ids,function(index, value) {
						var idd = '#'+value;
						//console.log(idd);
						$(idd).addClass( "hidden" );
						$('input[type="checkbox"].chk-'+value).prop('checked', false);
						//console.log(value);
				});
			}
		}

		function save_height_widget() {
			var height_wid_ids = [];
			$('.jarviswidget.full_height').each(function(i, obj) {
				height_wid_ids[i] = $(this).attr('id');
			});
			localStorage.setItem("height_wid_ids", JSON.stringify(height_wid_ids));
			save_dashboard();
			//console.log(large_ids);
		}

		function set_height_wid() {

			var height_wid_ids = JSON.parse(localStorage.getItem("height_wid_ids"));
			//console.log(local_large_ids);
			if (Array.isArray(height_wid_ids)) {
				$.each(height_wid_ids,function(index, value) {
						var idd = '#'+value;
						//console.log(idd);
						////$(idd).addClass( "full_height" );
						////$(idd+' .toggleheightpack').click(); // trigger click

						$(idd).addClass( "full_height" );
						$(idd).find("div[role='content']").addClass( "set_height" );

						set_ar_height(value);
				});

				/*
				$.each(height_wid_ids,function(index, value) {
						var idd = '#'+value;
						//console.log(idd);
						//$(idd).addClass( "full_height" );
						////$(idd+' .toggleheightpack').click(); // trigger click
				});
				*/
				save_dashboard();
			}

		}


		$(".demo form section label input.checkbox").on( "click", function( event ) {
		  var wid_chk_class = $(this).attr('class');
		  //console.log(wid_chk_class);
		  var wid_id_str_arr = wid_chk_class.match("chk-(.*) ");
		  var wid_id_str = "#"+wid_id_str_arr[1];
		  //console.log(wid_id_str);
		  $(this).is(":checked") ?  $(wid_id_str).removeClass("hidden") : $(wid_id_str).addClass("hidden")
		  save_hidden_widget();
		  set_pack();
		  save_dashboard();
		});

		$(".jarviswidget-toggle-btn").on( "click", function( event ) {
		  setTimeout(function(){ set_pack(); }, 1000);
		});


		$('#reset-widget123').click(function() {
			localStorage.clear();
			location.reload();
		});

		$(document).ready(function() {
			//set_large_wid();
			//set_pack();
			  //alert("document ready occurred!");
			//set_lock_wid();

			set_height_wid();
		});

		$('iframe').load(function(){
			// do something...
			//set_height_wid();
			set_large_wid();
			set_pack();
			set_height_wid();
		});

		$(document).ajaxStop(function() {
		  // place code to be executed on completion of last outstanding ajax call here
		  set_large_wid();
		  set_pack();
		});

		$(function() {
			$('.lazy').Lazy({
				//placeholder: "https://develop2.vervantis.com/assets/img/select2-spinner.gif",
				// your configuration goes here
				scrollDirection: 'vertical',
				effect: 'fadeIn',
				visibleOnly: true,
				/*
				onError: function(element) {
					console.log('error loading ' + element.data('src'));
				},
				*/
				beforeLoad: function(element) {
					element.attr('id');
					//element.css("visibility", "hidden");
					element.addClass("set_spinner");
					//console.log(element.attr('id'));
				// called before an elements gets handled
				},
				afterLoad: function(element) {
					// called after an element was successfully handled
					element.removeClass("set_spinner");
				},

			});
		});

		// dont in ready
		function save_dashboard(){
			//console.log('saved dash dd');
			//console.log(JSON.stringify(localStorage));
			//console.log(localStorage);
			var mysettings = JSON.stringify(localStorage);
			$.ajax({
			  type: "POST",
			  url: "/assets/ajax/save_dashboard.php",
			  data: {settings: mysettings}
			  ,
			  //success: success
			  //,
			  //dataType: dataType
			   dataType : 'json'
			});
		}

</script>

<style>
.set_spinner{
  /*background-image: url("/assets/img/ajax-loader.gif");*/ /* The image used */
  background-image: url(data:image/gif;base64,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);

  /*background-color: #cccccc;*/ /* Used if the image is unavailable */
  background-position: center; /* Center the image */
  background-repeat: no-repeat; /* Do not repeat the image */
  /*background-size: cover;*/ /* Resize the background image to cover the entire container */
  /*background-size: 50px 50px;*/
}
</style>