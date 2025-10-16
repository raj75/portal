<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(checkpermission($mysqli,52)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

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
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Market Resources
			<span>>
				Live Weather
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

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="center" style="padding-bottom:10px;">
				<button class="btn-primary wf-show" align="center" id="button5" style="height: 30px !important;width: auto !important;">Windy Forecast</button>
				<?php if(1==2){ ?>
				<button class="btn-primary wf-show" align="center" id="button6" style="height: 30px !important;width: auto !important;">Dark Sky Forecast</button>
			<?php } ?>
				<button class="btn-primary wf-show" align="center" id="button10" style="height: 30px !important;width: auto !important;">Atlantic Hurricane Forecast</button>
				<button class="btn-primary wf-show" align="center" id="button11" style="height: 30px !important;width: auto !important;">Pacific Hurricane Forecast</button>
				<button class="btn-primary wf-show" align="center" id="button3" style="height: 30px !important;width: auto !important;">NOAA 6-10 Day Forecast</button>
				<button class="btn-primary wf-show" align="center" id="button4" style="height: 30px !important;width: auto !important;">NOAA 8-14 Day Forecast</button>
				<button class="btn-primary wf-show" align="center" id="button1" style="height: 30px !important;width: auto !important;">NOAA HDD Weekly Forecast</button>
				<button class="btn-primary wf-show" align="center" id="button2" style="height: 30px !important;width: auto !important;">NOAA CDD Weekly Forecast</button>
			</div>
			<div id="wfresponse"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<script type="text/javascript">
$(document).ready(function(){
	$(".wf-show").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		bid=$(this).attr("id");
		$('#wfresponse').html('');
		$('#wfresponse').load('assets/ajax/weather-forecast-pedit.php?action=view&sid='+bid);
	});
	$('#wfresponse').html('');
	$('#wfresponse').load('assets/ajax/weather-forecast-pedit.php?action=view&sid=button5');
});
</script>
