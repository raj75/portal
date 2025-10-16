<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


//if(checkpermission($mysqli,51)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

$showdemo=1;
$subquery=((isset($showdemo) and $showdemo==1)?"&showdemo=1":"");
?>
<style>
.im-bottom50{font-weight: bold;z-index: 98;margin-top: -10px;}
.im-bottom50 span{vertical-align: top;}
</style>

<link href="../assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Invoices
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="sitestable">

	<!-- row -->
	<div class="row">
	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 im-bottom50">
			<input type="checkbox" <?php echo ($showdemo==1?"CHECKED":""); ?> value="Demo Company" id="hidedemo" class="flleft"><span class="flleft">Hide Demo Company</span>
		</article>
	<?php } ?>

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div align="right" style="padding-bottom:10px;display:none;">
				<button class="btn-primary" align="right" id="new-sites" style="height: 30px !important;width: auto !important;">Add New Invoices</button>
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
					<h2>Invoices </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="intable"></div>
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
<section id="indetails"></section>
<?php }
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<div id="in-status"></div>
<div id="inopdialog"></div>
<?php } ?>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script src="assets/js/plugin/sweetalert/sweetalert.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	/*$("#new-startstop").click(function(){
			$("#dialog-message").remove();
			$('#ss-status').load('assets/ajax/start-stop-status-pedit.php?action=view');
	});*/

	$('#intable').load("assets/ajax/list-invoices.php?load=true&ct=<?php echo mt_rand(2,77);  echo $subquery; ?>");

	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
	$('#hidedemo').change(function () {
		if($('#hidedemo').prop("checked")==1){
			var showdemo=1;
		}else{
			var showdemo=0;
		}
		$('#intable').load('assets/ajax/list-invoices.php?load=true&ct=<?php echo time(); ?>&showdemo='+showdemo);
	});
	<?php } ?>
});
</script>
