<?php
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,52)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1)
	die("Restricted Access");

//if($_SESSION["user_id"] != 1) die("Under Construction!");

$user_one=$_SESSION["user_id"];
?>
<style>
.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 2%;
    top: 0;
    width: 6%;
    z-index: 9999;
}
#fitopdialog{
	overflow-y:hidden !important;
}
</style>
<br /><br />
<link href="<?php echo ASSETS_URL; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Admin 
			<span>> 
				Scheduled Reports
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php 
			if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		?>
			<div align="right" style="padding-bottom:10px;">
				<button class="btn-primary" align="right" id="new-scheduled-report" style="height: 30px !important;width: auto !important;">Add New Scheduled Report</button>
			</div>
		<?php } ?>
		
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
					<h2>Scheduled Reports List </h2>

				</header>

				<!-- widget div--> 
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-scheduled-reports"></div>
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
<div id="response"></div>
<script type="text/javascript">
window.scrollTo(0,0);
$(document).ready(function(){
	$('#list-scheduled-reports').load("assets/ajax/subpages/list-scheduled-reports.php?load=true&ct=<?php echo time(); ?>");
 
	$("#new-scheduled-report").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$('#response').load("assets/ajax/subpages/scheduled-reports-add.php?action=new&ct=<?php echo time(); ?>");
	});
});
</script>