<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(isset($_SESSION) and $_SESSION["group_id"] == 1){}else{die("Permission Denied! Please contact Vervantis."); }


die("<h5 style='padding-top:30px;' align='center'>Under Construction!</h5>"); 




//if(checkpermission($mysqli,42)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

$_COOKIE["docname"] = "Energy Procurement:Strategy";
//$_SESSION["docname"] = "Energy Procurement:Strategy";
$_COOKIE["appurl"] = APP_URL;
//$_SESSION["appurl"] = APP_URL;
$_COOKIE["uid"] = $user_one;


?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i> 
				Admin <span>> File Manager</span>
		</h1>
	</div>
</div>
<style>
.h800{height:800px;}
</style>

		<!-- NEW WIDGET START -->
		<article class="col-sm-12 h800">				
			<iframe src="assets/plugins/elfinders3/elfinder.php" frameborder="0" width="100%" height="100%" scrolling="auto"></iframe>
		</article>
		<!-- WIDGET END -->
<div id="dialog" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;}</style>
<script type="text/javascript">
$(document).ready(function(){
	filename="";
	$( "#dialog" ).dialog({
	  height: $(document).height(),
      width: $(document).height(),
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1)){ ?>
		open: function (event, ui) {
			   // this is where we add an icon and a link
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

		}
<?php } ?>
    });
});
</script>