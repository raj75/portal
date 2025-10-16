<?php
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,36)==false) die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

?>
<style>
#ui-datepicker-div{top:110px !important;}
.margin_left{margin-left:5px;}
.ui-datepicker-calendar {
    display: none;
 }
.ui-datepicker-prev , .ui-datepicker-next {display:none;}
#filter_form{padding-left:45px; float:left;}
#reset_invoice{float:left; margin-left:8px;}
</style>



<div class="row fixed">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Energy Accounting <span>> Invoices Processed</span>
		</h1>
	</div>
</div>

<div class="row fixed">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form id="filter_form" action="">
		Start Date <input type="text" class="monthYearPicker" id="start_date" autocomplete="off"  required>
		<span class="padding_left">End Date</span> <input type="text" class="monthYearPicker" id="end_date" autocomplete="off" required>
		<button type="submit" class="margin_left filter_invoice">Filter</button>
		</form>
		<button onclick="navigateurl('assets/ajax/invoices-processed.php','Invoices Processed')" id="reset_invoice">Reset</button>
	</div>
</div>
<br>
<br>
<div id="invoice_data"></div>


<script type="text/javascript">
//$(document).ready(function(){
	$("#reset_invoice").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		//$('#invoice_data').html('');
		$('#invoice_data').load('assets/ajax/invoices_processed_details.php');
	});
//});
//loadURL("assets/ajax/invoices_processed_details.php", $('#invoice_data'));
$(document).ready(function(){
	//$(".filter_invoice").click(function(){
	$("#filter_form").submit(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		var startdate = $('#start_date').val();
		var enddate = $('#end_date').val();
		//$('#invoice_data').html('');
		$('#invoice_data').load('assets/ajax/invoices_processed_details.php?start='+encodeURI(startdate)+'&end='+encodeURI(enddate));
		return false;
	});

	//$(function() {
	$('.monthYearPicker').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'MM yy'
	}).focus(function() {
		var thisCalendar = $(this);
		$('.ui-datepicker-calendar').detach();
		$('.ui-datepicker-close').click(function() {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			thisCalendar.datepicker('setDate', new Date(year, month, 1));
		});
	});
});

    //$( ".datepicker" ).datepicker();
//});

//script.async = true; not working



loadScript("https://cdn.amcharts.com/lib/4/core.js", function(){
	loadScript("https://cdn.amcharts.com/lib/4/charts.js", function(){
		loadScript("https://cdn.amcharts.com/lib/4/themes/animated.js", function(){
			loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
				$('#invoice_data').load('assets/ajax/invoices_processed_details.php');
			});
		});
	});
});

</script>
