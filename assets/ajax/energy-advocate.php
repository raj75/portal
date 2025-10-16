<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
if($_SESSION["group_id"] != 1)
	die("Restricted Access");
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
.nodisplay{display:none;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Dashboard Widgets  
			<span>> 
				Energy Team
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;" class="nodisplay">
				<button class="btn-primary" align="right" id="new-energy-advocate" style="height: 30px !important;width: auto !important;">Add Energy Advocate</button>
			</div>
			<div id="adtable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<section id="addetails"></section>
<div id="adresponse"></div>
<div id="adtopdialog"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#new-energy-advocate").click(function(){
		//$("#dialog-message").remove();
		$('#adresponse').html('');
		$('#adresponse').load('assets/ajax/energy-advocate-add.php?action=add');
	});
	$('#adtable').load("assets/ajax/energy-advocate-pedit.php");
});
</script>