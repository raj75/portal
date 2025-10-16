<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,52)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
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
				Energy Procurement
			<span>>
				Trading Platform
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
				<button class="btn-primary tp-show" align="center" id="button5" style="height: 30px !important;width: auto !important;">Symbol List</button>
				<button class="btn-primary tp-show" align="center" id="button6" style="height: 30px !important;width: auto !important;">Portfolio</button>
				<button class="btn-primary tp-show" align="center" id="button10" style="height: 30px !important;width: auto !important;">Electricity</button>
				<button class="btn-primary tp-show" align="center" id="button11" style="height: 30px !important;width: auto !important;">Natural Gas</button>
			</div>
			<div id="tpresponse"></div>
			<div id="tpchartcont"><iframe id="tpchart" border="none" width="100%" height="530px" style="display:none"></iframe></div>
			<div id="tpresponse2"></div>
			<div id="tpchartcont2"><iframe id="tpchart2" border="none" width="100%" height="530px" style="display:none"></iframe></div>
			<div id="tpresponse3"></div>
			<div id="tpchartcont3"><iframe id="tpchart3" border="none" width="100%" height="530px" style="display:none"></iframe></div>
			<div id="tpresponse4"></div>
			<div id="tpchartcont4"><iframe id="tpchart4" border="none" width="100%" height="530px" style="display:none"></iframe></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!--<section id="sslcontainer" class="m-top"></section>-->
<!-- end widget grid -->
<script type="text/javascript">
$(document).ready(function(){
	$( "#button5" ).click(function() {
		$('#tpchart').attr('src', "");
		$("#tpchart").css("display", "none");
		$("#tpresponse2").css("display", "none");
		$("#tpchartcont2").css("display", "none");
		$("#tpresponse3").css("display", "none");
		$("#tpchartcont3").css("display", "none");
		$("#tpresponse4").css("display", "none");
		$("#tpchartcont4").css("display", "none");
		$("#tpresponse").css("display", "block");
		$("#tpchartcont").css("display", "block");
		$('#tpresponse').html('');
		//loadURL("assets/ajax/trading-platform-1.php?action=symbollist", $('#tpresponse'));
		$('#tpresponse').load('assets/ajax/trading-platform-1.php?action=symbollist&ct='+Math.random());
	});
	$( "#button6" ).click(function() {
		$("#tpresponse").css("display", "none");
		$("#tpchartcont").css("display", "none");
		$("#tpresponse3").css("display", "none");
		$("#tpchartcont3").css("display", "none");
		$("#tpresponse4").css("display", "none");
		$("#tpchartcont4").css("display", "none");
		$("#tpresponse2").css("display", "block");
		$("#tpchartcont2").css("display", "block");
		//$("#tpchart2").attr('src', 'assets/ajax/subpages/trading-platform-2.php?action=portfolio');
		//$("#tpchart2").css("display", "block");
		$('#tpresponse2').html('');
		$('#tpresponse2').load('assets/ajax/subpages/trading-platform-2.php?action=portfolio');
	});

	$( "#button10" ).click(function() {
		$("#tpresponse").css("display", "none");
		$("#tpchartcont").css("display", "none");
		$("#tpresponse2").css("display", "none");
		$("#tpchartcont2").css("display", "none");
		$("#tpresponse4").css("display", "none");
		$("#tpchartcont4").css("display", "none");
		$("#tpresponse3").css("display", "block");
		$("#tpchartcont3").css("display", "block");
		$('#tpresponse3').html('');
		$('#tpresponse3').load('assets/ajax/subpages/trading-platform-3.php?action=electricity');
	});

	$( "#button11" ).click(function() {
		$("#tpresponse").css("display", "none");
		$("#tpchartcont").css("display", "none");
		$("#tpresponse2").css("display", "none");
		$("#tpchartcont2").css("display", "none");
		$("#tpresponse3").css("display", "none");
		$("#tpchartcont3").css("display", "none");
		$("#tpresponse4").css("display", "block");
		$("#tpchartcont4").css("display", "block");
		$('#tpresponse4').html('');
		$('#tpresponse4').load('assets/ajax/subpages/trading-platform-4.php?action=naturalgas');
	});
$('#tpresponse').html('');
	//loadURL("assets/ajax/subpages/trading-platform-1.php?action=symbollist", $('#tpresponse'));
loadURL("assets/ajax/trading-platform-1.php?action=symbollist", $('#tpresponse'));
	//loadURL("assets/ajax/subpages/trading-platform-1.php?action=chart", $('#tpchart'));
	//document.getElementById('tpchart').src = "assets/ajax/subpages/trading-platform-1.php?action=chart";
//$('#tpchart').attr('src', "assets/ajax/subpages/trading-platform-1.php?action=chart");
});
</script>
