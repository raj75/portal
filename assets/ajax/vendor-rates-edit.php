<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,51)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

?>
<br /><br />
<link href="../assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Admin 
			<span>> 
				Vendor Rates Edit
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="sitestable">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div align="right" style="padding-bottom:10px;display:none;">
				<button class="btn-primary" align="right" id="new-sites" style="height: 30px !important;width: auto !important;">Add New  Sites</button>
			</div>

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Vendor Rates </h2>

				</header>

				<!-- widget div--> 
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="vetable"></div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>

<!-- end widget grid -->
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<section id="vedetails"></section>
<?php } 
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<div id="ve-status"></div>
<div id="veopdialog"></div>
<?php } ?>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	/*$("#new-startstop").click(function(){
			$("#dialog-message").remove();
			$('#ss-status').load('assets/ajax/start-stop-status-pedit.php?action=view');
	});*/

	$('#vetable').load("assets/ajax/vendor-rates-pedit.php?load=true&ct=<?php mt_rand(2,77); ?>");
});
</script>