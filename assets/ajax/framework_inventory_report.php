<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,131)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

//$_SESSION["group_id"] = 2;  

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
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
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
	.myinput{width:300px !important; padding-left: 10px;}
	*/
	.select_filters{height:32px; padding:6px 5px 5px 15px; border-bottom:1px solid #aaa;}
	.ui-datepicker {top:185px !important; z-index:10 !important;}
	
	
	
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
	
	
	#finventory_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#finventory_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#finventory_datatable{border-bottom: 1px solid #ccc !important;}
	#finventory_datatable .isodrp{width:auto !important;}
	.select2-search{z-index:10;}
	/*.select2height{height:25px !important;}*/
	.select2-selection__clear {font-size:20px; margin-top:0px !important;}
	#reset_query {padding: 2px 10px 2px; margin-left: 5px; margin-top:-4px;} 
	.dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom:1px solid #cccccc;}
	#finventory_datatable tbody tr {cursor: pointer;}
	#site_invoices_datatable tbody tr {cursor: pointer;}
	/*
	#finventory_datatable tbody tr td:nth-child(11) {
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
	
	
	#finventory_datatable {
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
	
	.btn-default.btn-on.active{background-color: #5BB75B;color: white;}
	.btn-default.btn-off.active{background-color: #DA4F49;color: white;}
	
	.debug_qry{display:none;}
</style>

<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

<section id="widget-grid" class="">
<div class="row">
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 gobackbtn">
		<b><img id="movebk" onclick="move_back_ar()" src="https://develop2.vervantis.com/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table"></i>
				Reports <span>> Site Inventory Report</span>
		</h1>
		
	</div>
</div>
</section>

<div id="process_report">
		<!-- NEW WIDGET START -->
		<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 " style="display:;">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="glyphicon glyphicon-filter"></i> </span>
						<h2>FILTERS </h2>
					</header>
					
					<div class="row" style="padding-left:15px; padding-bottom:15px;">
				
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							
						  <div class="row ">
						  
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>State</b></label>
								<br>
								<select multiple name="state" id="state" class="select2">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Site Status</b></label>
								<br>
								<select name="sitestatus" id="sitestatus" class="select2">
									<option value=''>All</option>
									<option value='active'>Active</option>
									<option value='inactive'>Inactive</option>
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
						
						<!--------------new row--------------------------->
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="" style="padding-left:15px; float:left">
								<button type="submit" class="btn-primary" id="create_query">Submit</button>
								<button onclick="navigateurl('assets/ajax/framework_inventory_report.php','Accounts Report')" type="button" class="btn btn-default" id="reset_query">Reset</button> 

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

<!-- Query part -->

<br>

<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Site Inventory Report </h2>
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

							<table id="finventory_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>ClientID</th>
										<th>SiteState</th>
										<th>SiteNumber</th>
										<th>SiteName</th>
										<th>SiteStatus</th>
										<th>Electricity</th>
										<th>Gas/Heating</th>
										<th>Water/Sewer</th>
										<th>Waste</th>
										<th>Telecom</th>
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
	
	
	<!-- NEW WIDGET START -->
		<!--
		<section id="siteinvoices" class="sitestable m-top45 ">
		</section>
		-->
		
		<section id="siteinvoices" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="siteinvoices_inner" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Site Invoices </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						<a name="site_invoices_start"></a>
						<!-- widget content -->
						<div class="widget-body no-padding" id="adhoc-datatable-load--">

							<table id="site_invoices_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>ClientID</th>
										<th>SiteID</th>
										<th>SiteState</th>
										<th>SiteNumber</th>
										<th>SiteName</th>
										<th>SiteStatus</th>
										<th>VendorName</th>
										<th>AccountNumber</th>
										<th>AccountStatus</th>
										<th>ServiceTypeName</th>
										<th>ServiceCategory</th>
										<th>LastInvoiceID</th>
										<th>LastEndDate</th>
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
	
	<div style="display:none;">Site Invoices Query</div>
	<textarea style="display:none;" class="qry_area_invoice" rows="20" cols="200"></textarea>
	
	
	

</div>

<!-- NEW WIDGET START -->
		<section id="invoicedetails" class="sitestable m-top45 ">
		</section>
		
		
		
		

<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"> 
	</script>

<script type="text/javascript">

	pageSetUp();
	
	//--------------------------------------
	var finventory_table;
	var site_inv_table;
	// pagefunction
	var pagefunction = function() {
		
		
		finventory_table = new DataTable('#finventory_datatable', {
			//ajax: 'assets/ajax/framework_cost_usage_report_ajax.php',
			ajax: {
				url: 'assets/ajax/framework_inventory_report_ajax.php',
				data: function (d) {
					
				}
			},
			
			//'processing': false,
			'processing': true,
			'serverSide': true,
			'deferRender': true,
			'serverMethod': 'post',

			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			"retrieve": true,
			"scrollCollapse": true,
			"searching": true,
			"paging": true,
			//"scrollX": true,
					

			columns: [
				{ data: 'ClientID' },
				{ data: 'SiteState' },
				{ data: 'SiteNumber' },
				{ data: 'SiteName' },
				{ data: 'SiteStatus' },
				{ data: 'Electricity' },
				{ data: 'Gas_Heating' },
				{ data: 'Water_Sewer' },
				{ data: 'Waste' },
				{ data: 'Telecom' },
			],
			
			"dom": 'Blfrtip',
			"autoWidth" : true,
			"scrollX": true,
			/*
			rowGroup: {
				dataSrc: 'AccountNumber'
			},
			*/
			//rowGroup: true,
			
			
			"buttons": [						
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':not(".no-colvis")'
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
				////var meter_column_int = finventory_table.column(9);				
				////meter_column_int.visible(false);
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
			drawCallback: function (settings) {
				$(".dots-cont").hide();
				//console.log(settings.json);
				var datatable_json = settings.json;
				//alert(json.data.length + ' row(s) were loaded');
				//alert(datatable_json.qry);
				var my_qry = datatable_json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				$(".qry_area").val(my_qry);
				
				//hide no group row
				//var grp_txt = $("#finventory_datatable tbody tr:first-child").text();
				//var grp_txt = $("#finventory_datatable tbody").find('tr:first').text();
				//console.log(grp_txt);
			},
			
			/*
			"drawCallback" : function(settings) {
				 $(".dots-cont").hide();
			},		
			*/
			
			"preDrawCallback": function (settings) {
				$(".dots-cont").show();
			},  
 
		});
		
		
		
		//-----------site invoices datatable-----------
		
		site_inv_table = new DataTable('#site_invoices_datatable', {
			//ajax: 'assets/ajax/framework_cost_usage_report_ajax.php',
			ajax: {
				
				url: 'assets/ajax/framework_inventory_invoices_ajax.php',
				data: function (d) {
					
				}
				
			},
			
			//'processing': false,
			'processing': true,
			'serverSide': true,
			'deferRender': true,
			'serverMethod': 'post',

			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			"retrieve": true,
			"scrollCollapse": true,
			"searching": true,
			"paging": true,
			//"scrollX": true,
					

			columns: [
				{ data: 'ClientID' },
				{ data: 'SiteID' },
				{ data: 'SiteState' },
				{ data: 'SiteNumber' },
				{ data: 'SiteName' },
				{ data: 'SiteStatus' },
				{ data: 'VendorName' },
				{ data: 'AccountNumber' },
				{ data: 'AccountStatus' },
				{ data: 'ServiceTypeName' },
				{ data: 'ServiceCategory' },
				{ data: 'LastInvoiceID' },
				{ data: 'LastEndDate' },
			],
			
			//"dom": 'Blfrtip',
			//https://datatables.net/reference/option/dom
			"dom": 'tp',
			"autoWidth" : true,
			"scrollX": true,
			
			
			
			"buttons": [						
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':not(".no-colvis")'
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
				
			},
			
			drawCallback: function (settings) {
				$(".dots-cont").hide();
				
				var datatable_json = settings.json;
				
				var my_qry = datatable_json.qry;
				
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
	///////temporary hidden
	//////////$(".qry_area_invoice").val(my_qry);
				
			},
			
			"preDrawCallback": function (settings) {
				$(".dots-cont").show();
			},  
 
		});
		
		
		
		
		
	}

	var scroll_y = 0;
	$(document).ready(function(){

		// for venoder
		 //$("#vendor,#account,#country,#state").select2({
		$("#state").select2({
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
										 
					 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
					 var client_ids = $('#company').val();
					 <?php } ?>
					
					 var state_ar = $('#state').val();					 
					 var fprocessForm = $('#fprocessForm');
					 
					 return {
						 fieldid: ele_id,
						 form_data: fprocessForm.serialize(),
						 
						 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
						 client_ids: client_ids,
						 <?php } ?>
						 
						 state_ar: state_ar,
						
						 
						 searchTerm: params.term, // search term
						 //page:params.page||1
						 page:params.page||1,
						 pageSize: params.pageSize || 15						 
					 };
				 },
				 
				 /*
				 processResults: function (response) {
					 return {
						 results: response,
					 };
				 },
				 */
				
				 cache: true
			 }
		 });
    
		 
		 //-------------------------
		 
		 // date picker
		 
		//$('.mydatepicker').datepicker({  format: 'mm/dd/yyyy'});
		
		/* moved down at end of page
		https://www.jqueryscript.net/time-clock/jquery-ui-month-picker.html
		$('.mydatepicker').monthpicker({
			// e.g. May 2022			
			//altFormat:'MM yy'	
			altFormat:'M yy'	
			//altFormat:'yy-mm-dd'
		});
		*/
					
		 
		 // datatable filter
		
		/*
		  function filterColumn( value ) {
			finventory_table.column(2).search( value ).draw();
		  }
		*/
		 
		  //var table = $('#example').DataTable();
		  
		  $('#create_query').on('click', function () {
			//filterColumn('Edinburgh');
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			
				if ( $('#company').val() < 1 ) {
					alert("Please select Company");
					return false;
				}
				
			<?php } ?>
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			var client_id = $('#company').val();
			// check if account set 
			if (client_id.length > 0) {
				finventory_table.column(0).search( client_id ); // client/company
			} else {
				finventory_table.column(0).search(""); // client/company
			}
			<?php } ?>			
			
			var state_ar = $('#state').val();
			// check if state set 
			if (state_ar.length > 0) {
				finventory_table.column(1).search( state_ar ); // state
			} else {
				finventory_table.column(1).search(""); // state
			}
			
			var sitestatus = $('#sitestatus').val();
			// check if state set 
			if (sitestatus.length > 0) {
				finventory_table.column(4).search( sitestatus ); // state
			} else {
				finventory_table.column(4).search(""); // state
			}
			
			//---------------------------
			
			finventory_table.draw(); // 
			//site_inv_table.draw(); // 
			//site_inv_table.clear().draw();
			//clear datatable
			site_inv_table.column(1).search(''); // site id
			site_inv_table.draw();

			//destroy datatable
			//site_inv_table.destroy();
		  });
		
		
		
		
		//-------------------------------------
		$('#group_by').on('change', function (e) {
			e.preventDefault();
			//alert($(this).val());
			var group_option = $(this).val();
			// disable/enalbe group
			//https://datatables.net/reference/api/rowGroup().disable()
			if (group_option == 'none') {
				finventory_table.rowGroup().disable().draw();
			} else {
				//table.rowGroup().dataSrc($(this).data('column'));
				finventory_table.rowGroup().dataSrc($(this).val()).draw();
			}
		});
		
		// hide no group tr
		finventory_table.on( 'draw', function () {
			// your code here
			//alert('draw');
			var grp_tr = $("#finventory_datatable tbody").find('tr:first');
			if (grp_tr.text() == 'No group') {
				grp_tr.hide();
			}
			console.log(grp_tr);
		});
		
	});
	
	// load invoices of selected site
	$(document).on("click","#finventory_datatable tbody tr",function() {
			var SiteID = finventory_table.row(this).data().SiteID;
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			var client_id = $('#company').val();
			// check if account set 
			if (client_id.length > 0) {
				site_inv_table.column(0).search( client_id ); // client/company
			} else {
				site_inv_table.column(0).search(""); // client/company
			}
			<?php } ?>	
			
			//var ClientID = finventory_table.row(this).data().ClientID;
			//console.log(SiteID);
			
			console.log("site_invoices");
			console.log(SiteID);
			//site_inv_table.column(0).search(11); // site id
			site_inv_table.column(1).search(SiteID); // site id
			site_inv_table.draw(); //
			
			//window.scrollTo(0,0);
			/*
			$('html, body').animate({
				scrollTop: $("#site_invoices_datatable").offset().top
			}, 2000);
			*/
			document.location.href="#site_invoices_start";
		
			//site_invoices(ClientID,SiteID);
			//load_invoice(dt_invoice_id);
			//console.log('clicked: ' + faccrual_table.row(this).data()[0])
			//console.log( faccrual_table.row(this).data().InvoiceID )
		});
		
	/*
	function site_invoices (SiteNumber) {
		console.log("site_invoices");
	}
	*/
	
	/*
	
	function site_invoices(siteid) {
		
		console.log("site_invoices");
		console.log(siteid);
		site_inv_table.column(0).search(11); // site id
		site_inv_table.column(1).search(siteid); // site id
		site_inv_table.draw(); //
	
		//$("#process_report").fadeOut( "slow" );
		//window.scrollTo({ top: 0, behavior: 'smooth' });
		//$('#invoicedetails').load('assets/ajax/invoicedetails.php?id='+id);
		//scroll_y = this.scrollY;
		//console.log("scroll_y");
		//console.log(scroll_y);
		
		$('#siteinvoices').load('assets/ajax/site_invoices_ajax.php?siteid='+siteid);
		
		$("#invoicedetails").fadeIn( "slow" );
		$(".gobackbtn").show();
		
		
	}
	*/
	
	$(document).on("click","#site_invoices_datatable tbody tr",function() {
		var LastInvoiceID = site_inv_table.row(this).data().LastInvoiceID;
		
		if (LastInvoiceID === undefined || LastInvoiceID === null) {
			// do something 
			//alert("No invoices available");
			
			Swal.fire("No invoices available!");

			return false;
		}
		
		load_invoice(LastInvoiceID);
		
	});
	
	function move_back_ar () {
		$("#process_report").fadeIn( "slow" );
		$("#invoicedetails").fadeOut( "slow" );
		$("#invoicedetails").html('');
		
		$(".gobackbtn").hide();
		 window.scrollTo(0,scroll_y);
	}
	
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
	
	/*
	
	function stoperror() {
	   return true;
	}
	
	window.onerror = stoperror;
	*/
	
	
	
	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		loadScript("assets/js/monthpicker_ar.js", function(){
			pagefunction();
			
			//https://www.jqueryscript.net/time-clock/jquery-ui-month-picker.html
			$('.mydatepickerhidden').monthpicker({
				
				// e.g. May 2022			
				//altFormat:'MM yy'	
				altFormat:'M yy'	
				//altFormat:'yy-mm-dd'
			});
		});
	});

	
</script>

<script>
    
/*
    window.addEventListener("scroll", function (event) {

        let scroll_y = this.scrollY;
        let scroll_x = this.scrollX;
        console.log(scroll_x, scroll_y);
       
    });
*/
</script>
