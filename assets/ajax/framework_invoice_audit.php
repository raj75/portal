<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,126)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

//$_SESSION["group_id"] = 2; ////// for testing 
$mysqli2=$mysqli;
// -----------------db 2--------------
/*$mysqli2 = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
//$conn = mysqli_connect("localhost", "root","","vervantis");
if (!$mysqli2) {
    printf("Connect failed: %s\n", mysqli_connect_errno());
    exit();
}*/

// get user company
$company_name = '';
$company_options = '';

$qry_co = $mysqli->query("select company_id,company_name from company order by company_name");
		if ($qry_co->num_rows > 0) {
			while($row_co=$qry_co->fetch_assoc()) {
				$company_id=$row_co['company_id'];
				$company_name=$row_co['company_name'];
				$selected = ($company_id==9)?'selected':'';
				$company_options .= "<option value='$company_id' $selected >$company_name</option>";
			}
		}
		
//----------service type-----------
$service_options = "";
$qry_ser = $mysqli2->query("select ServiceTypeID,ServiceTypeName from ubm_database.tblServiceTypes order by ServiceTypeName");
		if ($qry_ser->num_rows > 0) {
			while($row_ser=$qry_ser->fetch_assoc()) {
				$service_id=$row_ser['ServiceTypeID'];
				$service_name=$row_ser['ServiceTypeName'];
				$service_options .= "<option value='$service_id' >$service_name</option>";
			}
		}

/*
for processed invoice report testing use:
NewSchema9.tbIinvoices
Use DueDate for the range for Entry Date Start and Entry Date End
Vendor use VendorID and
Account use AccountID
Vendor filter will pull VendorName from tblVendors table but use the VendorID in the query
tblAccount will be used for the Account # filter
Display the Account Number
Let’s see how that looks.  Skip site filter for now.
*/
?>

<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />

<!--<link href="assets/css/plugins/select2/select2_smart_admin.min.css" rel="stylesheet" type="text/css" />-->

<link rel="stylesheet"
			href= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />

<style>
	.select2-container {
		width: 100% !important;
	}
	/*
	.select2{width:300px !important; /*height:20px;*/}
	.myinput{width:300px !important;}
	*/
	.select_filters{height:32px; padding:6px 5px 5px 15px; border-bottom:1px solid #aaa;}
	.ui-datepicker {top:210px !important; z-index:10 !important;}
	
	
	
	.ui-state-active .ui-icon, .ui-state-focus .ui-icon, .ui-state-hover .ui-icon {
		/*background-image:url(assets/img/jqueryui/ui-icons_222222_256x240.png) !important; */
	}
	
	.ui-datepicker .ui-datepicker-next span, .ui-datepicker .ui-datepicker-prev span {
		background:url(assets/img/jqueryui/ui-icons_454545_256x240.png) !important;
		background-image:url(assets/img/jqueryui/ui-icons_454545_256x240.png) !important;
	}
	
	.ui-datepicker .ui-datepicker-prev span{
		background-position: -80px -192px !important;
	}
	
	.ui-datepicker .ui-datepicker-next span{
		background-position: -48px -192px !important;
	}
	
	
	#fiar_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#fiar_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#fiar_datatable{border-bottom: 1px solid #ccc !important;}
	#fiar_datatable .isodrp{width:auto !important;}
	.select2-search{z-index:10;}
	/*.select2height{height:25px !important;}*/
	.select2-selection__clear {font-size:20px; margin-top:0px !important;}
	#reset_query {padding: 2px 10px 2px; margin-left: 5px; margin-top:-4px;} 
	.dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom:1px solid #cccccc;}
	#fiar_datatable tbody tr {cursor: pointer;}
	/*
	#fiar_datatable tbody tr td:nth-child(11) {
	  width: 250px !important;
	  max-width: 250px !important;
	  word-break: break-all;
	  white-space: pre-line;
	}
	*/
	/*
	table.dataTable thead tr th:nth-child(11) {
	  width: 250px !important;
	  max-width: 250px !important;
	}
	*/
	/*
	table.dataTable th:nth-child(11)
	{
	  width: 200px !important;
	  max-width: 200px !important;
	  word-break: break-all;
	  white-space: pre-line;
	}

	table.dataTable td:nth-child(11)
	{
	  width: 200px !important;
	  max-width: 200px !important;
	  word-break: break-all;
	  white-space: pre-line;
	}
	*/
	
	/*
	table.dataTable tbody tr:hover {
	   background-color:#f6f6f6 !important;
	}
	
	table.dataTable > tbody > tr {
		background-color: transparent;
	}
	*/
	
	table.dataTable > tbody > tr:hover > * {
	  background-color: #f3eded !important;
	}
	
	
	.select2-search__field {padding-left:5px;}
	
	/*
	.mywid{width: 300px !important;}
	
	
	#fiar_datatable {
	  table-layout: fixed !important;
	  word-wrap: break-word;
	}
	*/
	/*
	table.dataTable {
	  table-layout: fixed !important;
	  //word-wrap: break-word;
	}
	
	table.dataTable th, table.dataTable td
	{
	  width: 70px !important;
	  max-width: 70px !important;
	  
	  //word-break: break-all;
	  //white-space: pre-line;
	}
	
	*/
	
	.gobackbtn{margin-top: 8px; margin-right: -23px; display: none;}
	
	.myrow{padding-left:15px; padding-bottom:15px; padding-top:0px !important;}
	.myfilter{padding-left:15px; float:left; padding-top:15px;}
	.select2group{height:32px !important; padding:6px 5px 5px 15px !important; border-bottom:1px solid #aaa;}
	
	.select2-container .select2-selection--single {height:40px; padding-top:5px;}
	
	.select2-container--default .select2-selection--single .select2-selection__arrow {top:5px;}
	
	.select2-search__field {padding-left:5px !important;}
	
	/* hide client id*/
	/*[data-cv-idx="17"] { display:none !important; }*/
	
#myoverlay {
  position: absolute;
  /*display: none;*/
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  /*background-color: rgba(0,0,0,0.5);*/
  background-color: rgba(0,0,0,0.1);
  z-index: 2;
  cursor: pointer;
}

#mytext{
  position: absolute;
  top: 50%;
  left: 50%;
  font-size: 20px;
  /*color: white;*/
  color: #4c4f53;
  transform: translate(-50%,-50%);
  -ms-transform: translate(-50%,-50%);
  font-style: italic;
}

.mychartrow{
	/*min-height:200px;*/
	height:200px;
	position: relative;
	display: none;
}

.mydatamess {
	font-size: 15px;
  /*color: white;*/
  color: #4c4f53;
  padding:20px;
  padding-top:0px;
  text-align:center;
}

.crash_mess {
	height:100px;
	position: relative;
	display: none;
	text-align:center;
	font-size: 15px;
}

.debug_qry{display:none;}

</style>

<style>
#chartdiv {
  width: 100%;
  /*height: 500px;*/
  /*height:1000px;*/
  min-height:200px;
}

</style>

<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

<section id="widget-grid" class="">
<div class="row">
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 gobackbtn">
		<b><img id="movebk" onclick="move_back_ar()" src="https://develop2.vervantis.com/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark" style="margin-bottom:18px;">
			<i class="fa fa-table"></i>
				Reports <span>> Invoice Audit Report</span> 
		</h1>
		
		<div id="show_hide_table_div"><input type="checkbox" name="" id="show_hide_table" checked> Hide Table</div>
		
		<br>
		
	</div>
</div>
</section>

<div id="process_report">


		<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 " style="display:;">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="glyphicon glyphicon-filter"></i> </span>
						<h2>FILTERS </h2>
					</header>
					
					<div class="row myrow">
					
						<form type="POST" name="fprocess" id="fprocessForm">
					
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							
						
						<div class="row myrow">
						
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>InvoiceStart</b></label>
								<br>
								<input id="invoice_date_start" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php echo date('m/d/Y', strtotime(' - 1 month')); ?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>InvoiceEnd</b></label>
								<br>
								<input id="invoice_date_end" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php echo date("m/d/Y");?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Vendor</b></label>
								<br>
								<select multiple name="vendor" id="vendor" class="select2-- myinput">
									<!--<option value="">Select Vendor</option>-->
								</select>
							</div>
							
						</div>
						
						<div class="row myrow">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Account #</b></label>
								<br>
								<select multiple name="account" id="account" class="select2-- myinput">
								</select>
							</div>
							
						
						
						<!--
						</div>
						-->
				
						<!--------------new row--------------------------->
						<!--
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:15px">		
						-->
							
							<!--
							<div class="myfilter">
								<label><b>Country</b></label>
								<br>
								<select multiple name="country" id="country" class="select2 select2height">
								</select>
							</div>
							
							<div class="myfilter">
								<label><b>State</b></label>
								<br>
								<select multiple name="state" id="state" class="select2 select2height">								
								</select>
							</div>
							-->
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Site</b></label>
								<br>
								
								<select multiple name="site" id="site" class="select2 select2height">
									
								</select>
							</div>
													

							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Group Data By</b></label>
								<br>
								<select name="group_by" id="group_by" class="select2 select2group">
									<option value='none' selected>None</option>
									<option value='VendorName'>Vendor</option>
									<option value='AccountNumber'>Account</option>
									<option value='SiteName'>Site</option>
								</select>
							</div>
						
						</div>
						
						<div class="row myrow">
							
							
							
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Order By</b></label>
								<br>
								<select name="order_by" id="order_by" class="select2 select2height">
									<option value="SiteNumber">Site Number</option>
									<option value="vendor">Vendor Name</option>
									<option value="account">Account Number</option>
								</select>
							</div>
							
							<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Company</b></label>
								<br>
								<select multiple name="company" id="company" data-placeholder="Select Company" class="select2 select2height">
									<!--<option value="">Select Company</option>-->
									<?php echo $company_options; ?>
								</select>
							</div>
							
							<?php } ?>
						
						</div>
						
						</div>
						
						</form>
						<!--------------new row--------------------------->
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="" style="padding-left:15px; float:left">
								<button type="submit" class="btn-primary" id="create_query">Submit</button> 
								<button onclick="navigateurl('assets/ajax/framework_invoice_audit.php','Accounts Report')" type="button" class="btn btn-default" id="reset_query">Reset</button> 
							</div>
						</div>
						
						
						<!--------------new row--------------------------->
						<!--------------debug qry--------------------------->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="debug_qry" style="padding-left:15px; float:left; border:1px solid #e9e9e9;">
								<textarea class="qry_area" rows="20" cols="200"></textarea>
							</div>
						</div>

				
					</div>
				
				
				</div>
				<!-- end widget -->
		</section>
		
		
		
		
<div id="datatable_container_ar" style="display:none;">		
		<!------------------------------------------------------------------------------>
<!-- Query part -->

<br>

<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Invoice Audit Report </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding" id="adhoc-datatable-load">

							<table id="fiar_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>Client ID</th>
										<th>Vendor ID</th>
										<th>Vendor Name</th>
										<th>Account ID</th>
										<th>Account Number</th>
										<th>Site ID</th>
										<th>Site Number</th>
										<th>Site Name</th>
										<th>Invoice ID</th>
										<th>Total Due</th>
										<th>Invoice Begin</th>
										<th>Invoice End</th>
										<th>Period</th>
										<th>Invoice Service Days</th>
										<th>Receipt Date</th>
										<th>Entry Date</th>
										<th>Consolidation Notification Date</th>
										<th>Consolidation Received Date</th>
										<th>Vendor Payment Date</th>
										<th>Vendor Payment Clear Date</th>
										<th>Check Void Date</th>
										
									</tr>
									
								</thead>
								
							</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>
	
<!--datatable_container_ar end-->
</div>

	
	
	<section id="widget-grid22" class="m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-33" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="glyphicon glyphicon-filter"></i> </span>
						<h2>Invoice Audit Chart </h2>
					</header>
					
					<div class="row " >
					
					<div class="mydatamess" id="mydatamess">No Data Available</div>
					<div class="mychartrow" id="mychartrow">
						<div id="myoverlay">
						  <div id="mytext">Chart Loading...</div>
						  <!--
						  <div id="mytext">
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
							<span class="sr-only">Loading...</span>
						  </div>
						  -->
						</div>
					</div>
					
						<div class="crash_mess" id="crash_mess">
							<b>Too much data to display</b><br>
Please narrow your search or add filters, then try again.
						</div>
						
						<div id="chartdiv" style="display:none;"></div>
					</div>
					
				</div>
				
				
	</section>
	
	

</div>

<!-- NEW WIDGET START -->
		<section id="invoicedetails" class="sitestable m-top45 ">
		</section>

<!-------------------chart code start by amir----------------------->



<!--
<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
-->

<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"> 
	</script>

<script type="text/javascript">

	pageSetUp();
	
	//--------------------------------------
	var fiar_table;
	
	var f_q;
	var f_sq;
	var o_b;
	// pagefunction
	var pagefunction = function() {
		
		console.log('pagefunction');
		fiar_table = new DataTable('#fiar_datatable', {
			//ajax: 'assets/ajax/framework_invoice_audit_ajax.php',
			
			ajax: {
				url: 'assets/ajax/framework_invoice_audit_ajax.php',
				data: function (d) {
					//d.my_o_b = 'myValue';
					// d.custom = $('#myInput').val();
					// etc
					d.my_o_b = $('#order_by').val();
				}
			},
			
			//'processing': false,
			'processing': true,
			'serverSide': true,
			'deferRender': true,
			'serverMethod': 'post',

			"lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
			"pageLength": 50,
			"retrieve": true,
			"scrollCollapse": true,
			"searching": true,
			"paging": true,
			//"scrollX": true,

			columns: [
				{ data: 'ClientID' },
				{ data: 'VendorID' },
				{ data: 'VendorName' },
				{ data: 'AccountID' },
				{ data: 'AccountNumber' },
				{ data: 'SiteID' },
				{ data: 'SiteNumber' },
				{ data: 'SiteName' },
				{ data: 'InvoiceID' },
				{ data: 'TotalDue'},
				{ data: 'InvoiceBegin' },
				{ data: 'InvoiceEnd' },
				{ data: 'Period' },
				{ data: 'InvoiceServiceDays' },
				{ data: 'ReceiptDate' },
				{ data: 'EntryDate' },
				{ data: 'ConsolidationNotificationDate' },
				{ data: 'ConsolidationReceivedDate' },
				{ data: 'VendorPaymentDate' },
				{ data: 'VendorPaymentClearDate' },
				{ data: 'CheckVoidDate' },
				
			],			
			"dom": 'Blfrtip',
			"autoWidth" : true,
			"scrollX": true,
			/*
			rowGroup: {
				dataSrc: 'AccountNumber'
			},
			*/
			rowGroup: true,
			
			
			"buttons": [						
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							//columns: ':not(".no-colvis")'
							//columns: ':gt(1)'
						},
						{
							'extend': 'excelHtml5',
							//exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'csvHtml5',
						{
							'extend': 'csvHtml5',
							//exportOptions: { columns: ':visible:not(:first-child)' }
						},
					],
					
			initComplete: function (settings, json) {
				//$('[data-cv-idx="3"]').hide();
				//console.log(json.qry);
				////var my_qry = json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				////$(".qry_area").val(my_qry);
			},
			/*
			drawCallback: function (settings) {
				//console.log(json);
				var api = this.api();
				//console.log(json);
				////var my_qry = json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				////$(".qry_area").val(my_qry);
			},
			*/
			
			
			/*
			drawCallback: function (settings) {
				$(".dots-cont").hide();
				//console.log(settings.json);
				var datatable_json = settings.json;
				//alert(json.data.length + ' row(s) were loaded');
				//alert(datatable_json.qry);
				var my_qry = datatable_json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				//$(".qry_area").val(my_qry);
				
				//hide no group row
				//var grp_txt = $("#fpr_datatable tbody tr:first-child").text();
				//var grp_txt = $("#fpr_datatable tbody").find('tr:first').text();
				//console.log(grp_txt);
				console.log('drawCallback');
				
			
			
			},
			*/
			
			/*
			"drawCallback" : function(settings) {
				 $(".dots-cont").hide();
			},		
			*/
			
			"preDrawCallback": function (settings) {
				$(".dots-cont").show();
			},  
 
		})
		
		.on("draw.dt", function (e, dt, type, indexes) {
				
				$(".dots-cont").hide();

				
				//var datatable_json = settings.json;
				//console.log(dt.json);
				//var datatable_json = settings.json;
				
				//alert(json.data.length + ' row(s) were loaded');
				//alert(datatable_json.qry);
				var my_qry = dt.json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				$(".qry_area").val(my_qry);
				
				f_q = dt.json.f_q;
				f_sq = dt.json.f_sq;
				o_b = dt.json.o_b;
				var dt_records = dt.json.recordsTotal;
				console.log(dt_records);
				
				
				var grp_tr = $("#fiar_datatable tbody").find('tr:first');
				if (grp_tr.text() == 'No group') {
					grp_tr.hide();
				}
				console.log('draw.dt');
				////$( '#chartdiv' ).height(5000);
				// create chart
				//document.getElementById("myoverlay").style.display = "block";
				//if (dt_records > 0) {
					create_chart(dt_records);
				//}
				
				
				////hide_chart_logo();
				
		})
	  ;
		
		
		///------------------------------------------
		
		
		
		
		
	}

	var scroll_y = 0;
	$(document).ready(function(){

		
		$("#vendor,#account,#country,#state,#site").select2({
			 allowClear: true,
			 //placeholder: "Select Company",
			 placeholder: {
				id: "",
				placeholder: "Select Company"
			 },
			 ajax: { 
				 url: "assets/ajax/get_fprocess_fields.php",
				 
				 type: "post",
				 dataType: 'json',
				 delay: 250,
				 data: function (params) {
					 var ele_id = this.context.id;
					 var vendor_ids = $('#vendor').val();
					 var account_ids = $('#account').val();
					 
					 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
					 var client_ids = $('#company').val();
					 <?php } ?>
					 
					 var country_ar = $('#country').val();
					 var state_ar = $('#state').val();
					 var site_ids = $('#site').val();
					 
					 var fprocessForm = $('#fprocessForm');
					 
					 //console.log(this);
					 //alert(co_id);
					 return {
						 fieldid: ele_id,
						 form_data: fprocessForm.serialize(),
						 //coid: co_id,
						 vendor_ids: vendor_ids,
						 account_ids: account_ids,
						 
						 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
						 client_ids: client_ids,
						 <?php } ?>
						 
						 country_ar: country_ar,
						 state_ar: state_ar,
						 site_ids: site_ids,
						 
						 searchTerm: params.term, // search term
						 //page:params.page||1
						 page:params.page||1,
						 pageSize: params.pageSize || 15						 
					 };
				 },
				 
				
				
				 cache: true
			 }
		 });
		
		
		 
		 //-------------------------
		 
		 // date picker
		
		
		$('.mydatepicker').datepicker({  format: 'mm/dd/yyyy'});
		
		
		$(".mydatepicker").click(function(){
			
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
		});
		
		$(".mydatepicker").keyup(function(){
			
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
		});
		
		$(document).on('click','.ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon',function(){
			//alert('next');
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
		});
		
		
		 
		 // datatable filter
		
		/*
		  function filterColumn( value ) {
			fpr_table.column(2).search( value ).draw();
		  }
		*/
		 
		  //var table = $('#example').DataTable();
		  
		  
		  $('#create_query').on('click', function () {
			  console.log("create_query");
			  //show_loading();
			  //$("#chartdiv").hide();
			  //document.getElementById("myoverlay").style.display = "block";
			//filterColumn('Edinburgh');
			var invoice_date_start = $('#invoice_date_start').val(); // mm/dd/yyyy
			var invoice_date_end = $('#invoice_date_end').val(); // mm/dd/yyyy
			
			fiar_table.column(10).search( invoice_date_start );
			fiar_table.column(11).search( invoice_date_end );
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			
				if ( $('#company').val() < 1 ) {
					alert("Please select Company");
					return false;
				}
				
			<?php } ?>			
						
			var vendor_id = $('#vendor').val();
			
			//alert(vendor_id.length);
			// check if vendor set 
			if (vendor_id.length > 0) {
				fiar_table.column(1).search( vendor_id ); // vendor
			} else {
				fiar_table.column(1).search(""); // vendor
			}
			
			var account_id = $('#account').val();
			// check if account set 
			if (account_id.length > 0) {
				fiar_table.column(3).search( account_id ); // account
			} else {
				fiar_table.column(3).search(""); // account
			}
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			var client_id = $('#company').val();
			// check if account set 
			if (client_id.length > 0) {
				fiar_table.column(0).search( client_id ); // account
			} else {
				fiar_table.column(0).search(""); // account
			}
			<?php } ?>
			/*
			var country_ar = $('#country').val();
			// check if country set 
			if (country_ar.length > 0) {
				fiar_table.column(6).search( country_ar ); // country
			} else {
				fiar_table.column(6).search(""); // country
			}
			
			var state_ar = $('#state').val();
			// check if state set 
			if (state_ar.length > 0) {
				fiar_table.column(5).search( state_ar ); // state
			} else {
				fiar_table.column(5).search(""); // state
			}
			*/
			
			var site_id = $('#site').val();
			//var site_id = $('#site11').val();
			// check if site set 
			if (site_id.length > 0) {
				fiar_table.column(5).search( site_id ); // site
			} else {
				fiar_table.column(5).search(""); // site
			}
			//---------------------------
			console.log("create_query_1");
			fiar_table.draw(); // 
			console.log("create_query_2");
		  });
		  
		  
		//$(".dt-button-collection").data("data-cv-idx").hide();
		//fiar_table.column(17).visible(false);   // To hide
		// show column invoice updated to admin (group id 1) only
		////fpr_table.column(16).visible(false);   // To hide
		
		////fpr_table.column( 21 ).visible( false ); // hide client/company
		//fpr_table.column( 19 ).visible( false ); // hide country
		//fpr_table.column( 20 ).visible( false ); // hide state
		//fpr_table.column( 21 ).visible( false ); // hide site
		/*
		fpr_table.on('xhr', function () {
			var datatable_json = fpr_table.ajax.json();
			//alert(json.data.length + ' row(s) were loaded');
			//alert(datatable_json.qry);
			var my_qry = datatable_json.qry;
			//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
			$(".qry_area").val(my_qry);
		});
		*/
		
		// validate date		
		
		//https://www.scaler.com/topics/date-validation-in-javascript/
		
		/*
		function isValidDate(date) {
	
			//MM/DD/YYYY
			let dateformat = /^(0?[1-9]|1[0-2])[\/](0?[1-9]|[1-2][0-9]|3[01])[\/]\d{4}$/;

			// Matching the date through regular expression      
			if (date.match(dateformat)) {
				let operator = date.split('/');

				// Extract the string into month, date and year      
				let datepart = [];
				if (operator.length > 1) {
					datepart = date.split('/');
				}
				let month = parseInt(datepart[0]);
				let day = parseInt(datepart[1]);
				let year = parseInt(datepart[2]);

				// Create a list of days of a month      
				let ListofDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
				if (month == 1 || month > 2) {
					if (day > ListofDays[month - 1]) {
						//to check if the date is out of range     
						return false;
					}
				} else if (month == 2) {
					let leapYear = false;
					if ((!(year % 4) && year % 100) || !(year % 400)) leapYear = true;
					if ((leapYear == false) && (day >= 29)) return false;
					else
						if ((leapYear == true) && (day > 29)) {
							//console.log('Invalid date format!');
							return false;
						}
				}
			} else {
				//console.log("Invalid date format!");
				return false;
			}
			//return "Valid date";
			return true;
		}
		*/
		
		// datatable row click
		
		//$('#fpr_datatable tbody').on('click', 'tr', function() {
			
			
		$(document).on("click","#fiar_datatable tbody tr",function() {
			var dt_invoice_id = fiar_table.row(this).data().InvoiceID;
			console.log(dt_invoice_id);
			
			load_invoice(dt_invoice_id);
			//console.log('clicked: ' + fpr_table.row(this).data()[0])
			//console.log( fpr_table.row(this).data().InvoiceID )
		});
		
		
		/*
		fpr_table.on('click', 'tbody tr', function () {
			let data = fpr_table.row(this).data();
		 
			alert('You clicked on ' + data[0] + "'s row");
		});
		*/
		
		
		function load_invoice(id) {
			console.log(id);
			//parent.$("#sitesdetails").fadeOut( "slow" );
			//parent.$('#invoicedetails').load('assets/ajax/invoicedetails.php?id='+id);
			$("#process_report").fadeOut( "slow" );
			//window.scrollTo({ top: 0, behavior: 'smooth' });
			//$('#invoicedetails').load('assets/ajax/invoicedetails.php?id='+id);
			scroll_y = this.scrollY;
			console.log("scroll_y");
			console.log(scroll_y);
			$('#invoicedetails').load('assets/ajax/invoicedetailstesting.php?id='+id);
			$("#invoicedetails").fadeIn( "slow" );
			$(".gobackbtn").show();
			
		}
		
		
		//-------------------------------------
		
		$('#group_by').on('change', function (e) {
			e.preventDefault();
			//alert($(this).val());
			var group_option = $(this).val();
			// disable/enalbe group
			//https://datatables.net/reference/api/rowGroup().disable()
			if (group_option == 'none') {
				fiar_table.rowGroup().disable().draw();
			} else {
				
				var order_ind;
				//VendorName
				//VendorState
				
				if (group_option == 'VendorName') {
					order_ind = 2;
				} else if (group_option == 'AccountNumber') {
					order_ind = 4;
				} else if (group_option == 'SiteName') {
					order_ind = 7;
				} 
				
				fiar_table.order([order_ind, 'asc']).rowGroup().dataSrc($(this).val()).draw();
				//table.rowGroup().dataSrc($(this).data('column'));
				//fiar_table.rowGroup().dataSrc($(this).val()).draw();
			}
		});
		
		
		/*
		// hide no group tr
		fiar_table.on( 'draw', function () {
			// your code here
			console.log('draw 1');
			var grp_tr = $("#fiar_datatable tbody").find('tr:first');
			if (grp_tr.text() == 'No group') {
				grp_tr.hide();
			}
			console.log('draw 2');
			// create chart
			create_chart();
			hide_chart_logo();
			//set_contract_height();
			//console.log(grp_tr);
			
		});
		*/
		
		
		
		


		
		
	});
	
	
	//---------------chart functions ------------------

		//var chart;
		//var colorSet;
		//var dateAxis;
		
		//var root;
		
		function create_chart(dt_total) {
			
			
			console.log('create_chart');
			//show_loading();
			
			//console.log(root);
			
			//am5.ready(function() {
				
				am5.array.each(am5.registry.rootElements, function(root) {
					//console.log(root);
				  if (root.dom.id == "chartdiv") {
					root.dispose();
				  }
				});
				
			//});
			
			if (dt_total > 0) {
				show_loading();
				$("#mydatamess").hide();
				$("#crash_mess").hide();
			} else { // no data in chart
				hide_loading();
				$("#chartdiv").hide();
				$("#mydatamess").show();
				$("#crash_mess").hide();
			}
			
			
			//if(!!root) root.dispose();
				
			// Create root element
			// https://www.amcharts.com/docs/v5/getting-started/#Root_element
			var root = am5.Root.new("chartdiv");
			//root = am5.Root.new("chartdiv");
			root.dateFormatter.setAll({
			  dateFormat: "yyyy-MM-dd",
			  dateFields: ["valueX", "openValueX"]
			});

			/*
			// Set themes
			// https://www.amcharts.com/docs/v5/concepts/themes/
			root.setThemes([
			  am5themes_Animated.new(root)
			]);
			*/


			// Create chart
			// https://www.amcharts.com/docs/v5/charts/xy-chart/
			var chart = root.container.children.push(am5xy.XYChart.new(root, {
			  panX: false,
			  panY: false,
			  wheelX: "panX",
			  wheelY: "zoomX",
			  layout: root.verticalLayout
			}));


			// Add legend
			// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
			var legend = chart.children.push(am5.Legend.new(root, {
			  centerX: am5.p50,
			  x: am5.p50
			}))

			////var colors = chart.get("colors");


			// Create axes
			// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
			var yAxis = chart.yAxes.push(
			  am5xy.CategoryAxis.new(root, {
				categoryField: "category",
				/*renderer: am5xy.AxisRendererY.new(root, { inversed: true }),*/
				renderer: am5xy.AxisRendererY.new(root, { inversed: true, minGridDistance: 10 }),
				
				tooltip: am5.Tooltip.new(root, {
				  themeTags: ["axis"],
				  animationDuration: 200
				})
			  })
			);

			var xAxis = chart.xAxes.push(
			  am5xy.DateAxis.new(root, {
				baseInterval: { timeUnit: "minute", count: 1 },
				renderer: am5xy.AxisRendererX.new(root, {})
			  })
			);


			// Add series
			// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
			var series = chart.series.push(am5xy.ColumnSeries.new(root, {
			  xAxis: xAxis,
			  yAxis: yAxis,
			  openValueXField: "fromDate",
			  valueXField: "toDate",
			  categoryYField: "category",
			  sequencedInterpolation: true
			}));

			series.columns.template.setAll({
			  templateField: "columnSettings",
			  strokeOpacity: 0,
			  tooltipText: "{category}: {openValueX.formatDate('yyyy-MM-dd')} - {valueX.formatDate('yyyy-MM-dd')}"
			});

			series.data.processor = am5.DataProcessor.new(root, {
			  dateFields: ["fromDate", "toDate"],
			  //dateFormat: "yyyy-MM-dd HH:mm",
			  dateFormat: "yyyy-MM-dd",
			  colorFields: ["columnSettings.fill"]
			});

			// Add scrollbars
			chart.set("scrollbarX", am5.Scrollbar.new(root, {
			  orientation: "horizontal"
			}));

			//am5.net.load("https://assets.codepen.io/t-160/gantt_data.json").then(function(result) {
				//assets/ajax/framework_invoice_audit_chart.php
			//am5.net.load("assets/ajax/framework_invoice_audit_chart.php").then(function(result) {
			am5.net.load("assets/ajax/framework_invoice_audit_chart.php?f_q="+f_q+"&f_sq="+f_sq+"&o_b="+o_b).then(function(result) {
			  var data = am5.JSONParser.parse(result.response);
			  
			  /*
			  var categories = [];
			  am5.array.each(data, function(item) {
				if (categories.indexOf(item.category) == -1) {
				  categories.push(item.category);
				}
			  });
			  am5.array.each(categories, function(category, index) {
				categories[index] = {
				  category: category
				}
			  });
			  */
			  
			  var tot_cats = data.category_ar.length;
			  $( '#chartdiv' ).height(tot_cats*25); // total cats with height of one bar
			  
			  
			  ////yAxis.data.setAll(categories);
			  ////series.data.setAll(data);
			  
			  yAxis.data.setAll(data.category_ar);
			  series.data.setAll(data.data);
			  
			}).catch(function(result) {
			  // Error
			  ////console.log("Error loading " + result.xhr.responseURL);
			});
			
			/*
			chart.events.on("datavalidated", function() {
				console.log("Chart data reloaded and processed!");
			});
			*/
			
			var timeout;
			
			series.events.on("datavalidated", function() {
				console.log("Chart data reloaded and processed!");
				
				  if (timeout) {
					clearTimeout(timeout);
				  }
				  timeout = setTimeout(function() {
					//root.events.off("frameended", exportChart);
					
					var mycanvas = $(".am5-layer-30").get(0);
					console.log("mycanvas");
					console.log(mycanvas);
					var mycanvas_ctx = mycanvas.getContext('2d');
					
					console.log("mycanvas_ctx");
					console.log(mycanvas_ctx);
					
					console.log("Chart ready!");
					
					//console.log(mycanvas.toDataURL());
					console.log("mycanvas data size");
					console.log(mycanvas.toDataURL().length);
					
					if (mycanvas.toDataURL().length == 6) {
						// chart crashed
						$("#crash_mess").show();
						//chart_crash_mess();
						$("#chartdiv").hide();
					}
					
					/*
					if(mycanvas.toDataURL() == document.getElementById('blank').toDataURL())
						alert('It is blank');
					else
						alert('Save it!');
					*/
					
				  }, 100)
				  
				hide_loading();
				//$("#chartdiv").show();
			});
			
			// Make stuff animate on load
			// https://www.amcharts.com/docs/v5/concepts/animations/
			series.appear();
			chart.appear(1000, 100);
			
			// Listen for when data is fully processed
			/*
			chart.events.on("datavalidated", function() {
				console.log("Chart data reloaded and processed!");
			});
			*/
			

/*		
			// Create a loading indicator
var indicator = root.container.children.push(am5.Container.new(root, {
  width: am5.p100,
  height: am5.p100,
  layer: 1000,
  background: am5.Rectangle.new(root, {
    fill: am5.color(0xffffff),
    fillOpacity: 0.7
  })
}));

indicator.children.push(am5.Label.new(root, {
  text: "Loading...",
  fontSize: 25,
  x: am5.p50,
  y: am5.p50,
  centerX: am5.p50,
  centerY: am5.p50
}));
*/

			
			series.columns.template.events.on("click", function(ev) {
			  console.log("Clicked on a column", ev.target);
				var data_bar = ev.target.dataItem.dataContext;
				console.log(data_bar.invoiceid);
				var invoiceid = data_bar.invoiceid;
				
				$("#process_report").fadeOut( "slow" );
				//window.scrollTo({ top: 0, behavior: 'smooth' });
				//$('#invoicedetails').load('assets/ajax/invoicedetails.php?id='+id);
				scroll_y = this.scrollY;
				console.log("scroll_y");
				console.log(scroll_y);
				$('#invoicedetails').load('assets/ajax/invoicedetailstesting.php?id='+invoiceid);
				$("#invoicedetails").fadeIn( "slow" );
				$(".gobackbtn").show();
				$("#show_hide_table_div").hide();
			});
			
			//const root = am5.Root.new('chartdiv');
			root._logo.dispose();
			
			
			
			//document.getElementById("myoverlay").style.display = "none";
			//document.getElementById("mychartrow").style.height = 0;
			//hide_loading();
			//$("#chartdiv").show();
			
			
			
			
			
			////am4core.useTheme(am4themes_animated); disable for performance
			
			//am4core.options.onlyShowOnViewport = true; only start initializing when its container scrolls into view. 
			
			
			
			/*
			var chart = am4core.create("chartdiv", am4charts.XYChart);
			
			chart.svgContainer.autoResize = false;
			chart.svgContainer.measure();
			
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

			chart.paddingRight = 30;
			chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

			//var colorSet = new am4core.ColorSet();
			colorSet = new am4core.ColorSet();
			colorSet.saturation = 0.4;
			
			////chart.data = reloadData();
			//chart.data = '';
			// Set up data source
			chart.dataSource.url = "assets/ajax/framework_invoice_audit_chart.php";
			
			

			var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
			//categoryAxis.dataFields.category = "name";
			categoryAxis.dataFields.category = "category";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.inversed = true;
			categoryAxis.renderer.minGridDistance = 10;

			var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
			//dateAxis.dateFormatter.dateFormat = "yyyy-MM-dd HH:mm";
			dateAxis.dateFormatter.dateFormat = "yyyy-MM-dd";
			//dateAxis.renderer.minGridDistance = 70;
			dateAxis.renderer.minGridDistance = 70;
			//dateAxis.baseInterval = { count: 30, timeUnit: "minute" };
			//dateAxis.baseInterval = { count: 1, timeUnit: "month" };
			dateAxis.baseInterval = { count: 1, timeUnit: "day" };
			//dateAxis.max = new Date(2018, 0, 1, 24, 0, 0, 0).getTime();
			//dateAxis.min = dateAxis.min;
			//dateAxis.max = dateAxis.max;
			//dateAxis.strictMinMax = true;
			dateAxis.renderer.tooltipLocation = 0;
			
			dateAxis.dateFormats.setKey("month", "MMM yyyy");

			var series1 = chart.series.push(new am4charts.ColumnSeries());
			series1.columns.template.width = am4core.percent(80);
			series1.columns.template.tooltipText = " InvoiceID: {invoiceid} \n Date Range: {openDateX} - {dateX} \n Amount Due: ${totaldue}";

			series1.dataFields.openDateX = "fromDate";
			series1.dataFields.dateX = "toDate";
			//series1.dataFields.categoryY = "name";
			series1.dataFields.categoryY = "category";
			series1.columns.template.propertyFields.fill = "color"; // get color from data
			series1.columns.template.propertyFields.stroke = "color";
			series1.columns.template.strokeOpacity = 1;

			chart.scrollbarX = new am4core.Scrollbar();
			chart.scrollbarX.parent = chart.bottomAxesContainer;
			
			dateAxis.renderer.labels.template.location = 0.0001;
			
			//REM: To deactivate other columns on click
			  //series1.columns.template.events.on("hit", function(event){
				
				
				//series1.columns.each(function(column){
				  //if(column !== event.target){
					//column.setState("default");
					//column.isActive = false
				  //}
				//})
				
			  //});
			  
			// show invoice detail page
			series1.columns.template.events.on("hit", function(ev) {
				//console.log(ev.target);
				//alert('click');
				//this.getDetails(id,title,ev.target)
				var data_bar = ev.target.dataItem.dataContext;
				console.log(data_bar.invoiceid);
				var invoiceid = data_bar.invoiceid;
				
				$("#process_report").fadeOut( "slow" );
				//window.scrollTo({ top: 0, behavior: 'smooth' });
				//$('#invoicedetails').load('assets/ajax/invoicedetails.php?id='+id);
				scroll_y = this.scrollY;
				console.log("scroll_y");
				console.log(scroll_y);
				$('#invoicedetails').load('assets/ajax/invoicedetailstesting.php?id='+invoiceid);
				$("#invoicedetails").fadeIn( "slow" );
				$(".gobackbtn").show();
				
			}, this);
			
			
			*/
			
			
			
			
			
			/*
			// Set cell size in pixels
			var cellSize = 20;
			chart.events.on("datavalidated", function(ev) {

			  // Get objects of interest
			  var chart11 = ev.target;
			  var categoryAxis11 = chart11.yAxes.getIndex(0);

			  // Calculate how we need to adjust chart height
			  var adjustHeight = chart11.data.length * cellSize - categoryAxis11.pixelHeight;

			  // get current chart height
			  var targetHeight = chart11.pixelHeight + adjustHeight;

			  // Set it on chart's container
			  chart11.svgContainer.htmlElement.style.height = targetHeight + "px";
			});
			*/



  
		}
		
		function loadNewData() {
			chart.data = "";
			chart.data = reloadData();
		}

		function reloadData() {
			
			console.log('reloadData');
			var newData = [];
			var colorInd = 0;
			var ajaxData
			
			/*
			$("#fiar_datatable tbody tr").each(function(index, tr){
				//console.log($(this).find('.ar_contract_id').text());
					////var colorInd = index;
					////var myArray = [1,2,3,4];
					//var colorInd = myArray[~~(Math.random() * myArray.length)];
					if (colorInd > 5) {
						colorInd = 0;
					}
					var colorArray = ["#89CFF0","#7393B3","#0096FF","#6495ED","#4169E1","#4682B4"];
					//var colorValue = colorArray[~~(Math.random() * colorArray.length)];
					//const colorValue = colorArray[Math.floor(Math.random() * colorArray.length)];  
					var dt_accountno = $(this).find(".dt_accountno").text();
					var dt_invoicebegin = $(this).find(".dt_invoicebegin").text();
					var dt_invoiceend = $(this).find(".dt_invoiceend").text();
					var dt_invoice = $(this).find(".dt_invoice").text();
					var dt_totaldue = $(this).find(".dt_totaldue").text();
					var dt_vendorname = $(this).find(".dt_vendorname").text();
					
					
					if (dt_accountno!='') {
						item = {}
						//item ["category"] = "Contract #"+ar_contract_id;
						//item ["category"] = dt_accountno;
						item ["category"] = dt_vendorname +" / "+ dt_accountno;
						item ["fromDate"] = dt_invoicebegin;
						item ["toDate"] = dt_invoiceend;
						////item ["color"] = colorSet.getIndex(colorInd).brighten(0);
						////item ["color"] = colorSet.getIndex(colorInd).brighten(0);
						item ["color"] = colorArray[colorInd];
						item ["invoiceid"] = dt_invoice;
						item ["totaldue"] = dt_totaldue;
						item ["vendorname"] = dt_vendorname;
						
						colorInd++;
						
						newData.push(item);
					}

			});
			*/
			
			$.ajax({
				type: 'POST',
				url: "assets/ajax/framework_invoice_audit_chart.php",
				
				//dataType: 'json',
				//context: document.body,
				//global: false,
				async: true,
				//async: false,
				success: function(data) {
					//chart.dataProvider = data;
					//chart.validateNow();
					
					ajaxData = data;
					//newData = JSON.parse(data);
					//newData = Object.entries(data);
					
					//newData = Array.from(Object.entries(data));
					
					ajaxData = JSON.parse(data);
					console.log('Data==');
					console.log(data);
					
					//ajaxData = JSON.parse(JSON.stringify(data));
					console.log('ajaxData==');
					console.log(ajaxData);
					
					chart.data = ajaxData;
					chart.invalidateData();
					
				}
			}); 
	
			/*
			item = {}
			item ["category"] = "123456";
			item ["fromDate"] = "10/01/2024";
			item ["toDate"] = "12/31/2024";
			item ["color"] = "#89CFF0";
			item ["invoiceid"] = "22222";
			item ["totaldue"] = "2525";
			item ["vendorname"] = "vendor name";
			
			
			newData.push(item);
			
			console.log("new data==");
			console.log(newData);
			*/
			
			//return newData;
			//return ajaxData;
		}


		function hide_chart_logo() {

			try {
				var gdivs = document.querySelectorAll('g[aria-labelledby^="id-"][aria-labelledby$="-title"]').forEach(function(el) {
				  el.style.display = "none";
				});
			} catch(e) {}


			//document.querySelector('g[filter="url(\"#filter-id-66")\"]').style.display = "none";
			//document.querySelector('g[filter="url(\"#filter-id-66")\"]').style.display = "none";
			try {
				document.querySelector('g[aria-label="Chart created using amCharts library"]').style.display = "none";
			} catch(e) {}

		}
	
	
	function move_back_ar () {
		$("#process_report").fadeIn( "slow" );
		$("#invoicedetails").fadeOut( "slow" );
		$("#invoicedetails").html('');
		
		$(".gobackbtn").hide();
		$("#show_hide_table_div").show();
		
		 window.scrollTo(0,scroll_y);
	}
	
	/*show hide datatable*/
	 // Hide menu once we know its width
    $('#show_hide_table').click(function() {
        var $dt_table_ar = $('#datatable_container_ar');
        if ($dt_table_ar.is(':visible')) {
            // Slide away
            $dt_table_ar.slideUp();
        }
        else {
            // Slide in
            $dt_table_ar.slideDown();
        }
    });
	
	/* show hide chart */
	function show_loading() {
		document.getElementById("mychartrow").style.display = "block";
		$("#chartdiv").hide();
	}
	
	function hide_loading() {
		document.getElementById("mychartrow").style.display = "none";
		$("#chartdiv").show();
	}
	
	/*
	function chart_crash_mess() {
		//document.getElementById("mychartrow").style.display = "none";
		$("#crash_mess").show();
	}
	*/
	
	
	
	

	
	
	/*
	
	function stoperror() {
	   return true;
	}
	
	window.onerror = stoperror;
	*/
	
	
	/*
	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		
		loadScript("assets/js/amchart/core/core.js", function(){
			loadScript("assets/js/amchart/core/charts.js", function(){
				loadScript("assets/js/amchart/core/themes/animated.js", function(){
					
					pagefunction();
				//loadScript("assets/js/amchart/themes/frozen.js", function(){
					//loadScript("assets/js/amchart/core/themes/animated.js", function(){
						////create_chart();
						////hide_chart_logo();
						////set_contract_height();
					//});
				//});

				});
			});
		});

		
	});
	*/
	
	
	
	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		loadScript("https://cdn.amcharts.com/lib/5/index.js", function(){			
			loadScript("https://cdn.amcharts.com/lib/5/xy.js", function(){
				loadScript("https://cdn.amcharts.com/lib/5/themes/Animated.js", function(){						
						pagefunction();						
				});
			});			
		});
	});

	
</script>

