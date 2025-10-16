<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,130)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

//$_SESSION["group_id"] = 2;
$mysqli2 =$mysqli;
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
		
//----------scope 1 years-----------
$year1_options = "";
$qry_ser = $mysqli2->query("SELECT DISTINCT `Year` FROM	ubm_ghg.tblScope1 ORDER BY `Year` DESC");
		if ($qry_ser->num_rows > 0) {
			while($row_ser=$qry_ser->fetch_assoc()) {
				//$year_id=$row_ser['Year'];
				$year_name=$row_ser['Year'];
				if ( $year_name == date('Y') ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				$year1_options .= "<option $selected>$year_name</option>";
			}
		}
		
//----------scope 2 years-----------
$year2_options = "";
$qry_ser = $mysqli2->query("SELECT DISTINCT `Year` FROM	ubm_ghg.tblScope2 ORDER BY `Year` DESC");
		if ($qry_ser->num_rows > 0) {
			while($row_ser=$qry_ser->fetch_assoc()) {
				//$year_id=$row_ser['Year'];
				$year_name=$row_ser['Year'];
				if ( $year_name == date('Y') ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				$year2_options .= "<option $selected>$year_name</option>";
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
	
	
	#fghg_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#fghg_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#fghg_datatable{border-bottom: 1px solid #ccc !important;}
	#fghg_datatable .isodrp{width:auto !important;}
	.select2-search{z-index:10;}
	/*.select2height{height:25px !important;}*/
	.select2-selection__clear {font-size:20px; margin-top:0px !important;}
	#reset_query {padding: 2px 10px 2px; margin-left: 5px; margin-top:-4px;} 
	.dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom:1px solid #cccccc;}
	#fghg_datatable tbody tr {cursor: pointer;}
	/*
	#fghg_datatable tbody tr td:nth-child(11) {
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
	
	
	#fghg_datatable {
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
				Reports <span>> GHG Report</span>
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
				
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						
						  <div class="row ">
						  
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Consumption Period From</b></label>
								<br>
								
								<input class="mydatepickerhidden" type="hidden">
								
								<input id="entry_date_start" type="text" class="form-control datepicker-- mydatepicker  myinput" value="<?php echo date('M Y', strtotime(' - 6 month')); ?>"  autocomplete="off" />
								
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Consumption Period To</b></label>
								<br>
								
								<input class="mydatepickerhidden" type="hidden">
								
								<input id="entry_date_end" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php echo date("M Y");?>"  autocomplete="off" />
							</div>
							
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Scope 1 Emissions Factor Year</b></label>
								<br>					
								<select name="scope1year" id="scope1year" class="select2"  >
									<?php echo $year1_options; ?>
								</select>
							</div>
							
						  </div>							
						
						
						  <div class="row ">
						  
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Scope 2 Emissions Factor Year</b></label>
								<br>					
								<select name="scope2year" id="scope2year" class="select2"  >
									<?php echo $year2_options; ?>
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
								<button onclick="navigateurl('assets/ajax/framework_ghg_report.php','Accounts Report')" type="button" class="btn btn-default" id="reset_query">Reset</button> 

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
						<h2>GHG Report </h2>
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

							<table id="fghg_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>State</th>
										<th>Site Number</th>
										<th>Site Name</th>
										<th>Site Allocation</th>
										<th>Service Type</th>
										<th>Scope</th>
										<th>Emissions Factor Year</th>
										<th>Usage</th>
										<th>Unit of Measure</th>
										<th>Cost</th>
										<th>CO2 Pounds</th>
										<th>CH4 Pounds</th>
										<th>N2O Pounds</th>
										<th>CO2-e Pounds</th>
										<th>NOx-Annual Pounds</th>
										<th>NOx-Ozone Season Pounds</th>
										<th>SO2 Pounds</th>
										<th>CalPeriod</th>
										<th>ClientID</th>
										<th>scope1year</th>
										<th>scope2year</th>
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
	var fghg_table;
	// pagefunction
	var pagefunction = function() {
		
		
		fghg_table = new DataTable('#fghg_datatable', {
			//ajax: 'assets/ajax/framework_cost_usage_report_ajax.php',
			ajax: {
				url: 'assets/ajax/framework_ghg_report_ajax.php',
				data: function (d) {
					var meter_val = $('input[name="show_meter"]:checked').val();
					//console.log("meter_val=="+meter_val);
					//var meter_column = fghg_table.column(9);	
					
					d.meter_check = meter_val;
					// d.custom = $('#myInput').val();
					// etc
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
				{ data: 'State' },
				{ data: 'Site_Number' },
				{ data: 'Site_Name' },
				{ data: 'Site_Allocation' },
				{ data: 'Service_Type' },
				{ data: 'Scope' },
				{ data: 'Emissions_Factor_Year' },
				{ data: 'Usage' },
				{ data: 'Unit_of_Measure' },
				{ data: 'Cost' },
				{ data: 'CO2_Pounds' },
				{ data: 'CH4_Pounds' },				
				{ data: 'N2O_Pounds' },
				{ data: 'CO2-e_Pounds' },
				{ data: 'NOx-Annual_Pounds' },
				{ data: 'NOx-Ozone_Season_Pounds' },
				{ data: 'SO2_Pounds' },
				{ data: 'CalPeriod' },
				{ data: 'ClientID' },
				{ data: 'scope1year' },
				{ data: 'scope2year' },
				
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
				//var meter_column_int = fghg_table.column(9);				
				//meter_column_int.visible(false);
				fghg_table.column(17).visible(false);
				fghg_table.column(18).visible(false);
				fghg_table.column(19).visible(false);
				fghg_table.column(20).visible(false);
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
				//var grp_txt = $("#fghg_datatable tbody tr:first-child").text();
				//var grp_txt = $("#fghg_datatable tbody").find('tr:first').text();
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
		
		
	}

	var scroll_y = 0;
	$(document).ready(function(){

		// for venoder
		 //$("#vendor,#account,#country,#state").select2({
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
					 //var vendor_ids = $('#vendor').val();
					 var account_ids = $('#account').val();
					 
					 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
					 var client_ids = $('#company').val();
					 <?php } ?>
					 
					 //var country_ar = $('#country').val();
					 var state_ar = $('#state').val();
					 //var site_ids = $('#site11').val();
					 var site_ids = $('#site').val();
					 
					 var fprocessForm = $('#fprocessForm');
					 /*
					 var co_id = "";
					 if (ele_id=='site' && $('#company').length && $('#company').val() > 0) {
						co_id = $('#company').val();
					 }
					 */
					 //console.log(this);
					 //alert(co_id);
					 return {
						 fieldid: ele_id,
						 form_data: fprocessForm.serialize(),
						 //coid: co_id,
						 //vendor_ids: vendor_ids,
						 account_ids: account_ids,
						 
						 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
						 client_ids: client_ids,
						 <?php } ?>
						 
						 //country_ar: country_ar,
						 state_ar: state_ar,
						 site_ids: site_ids,
						 
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
			fghg_table.column(2).search( value ).draw();
		  }
		*/
		 
		  //var table = $('#example').DataTable();
		  
		  $('#create_query').on('click', function () {
			//filterColumn('Edinburgh');
			var entry_date_start = $('#entry_date_start').val(); // mm/dd/yyyy
			var entry_date_end = $('#entry_date_end').val(); // mm/dd/yyyy
			var start_end_date = entry_date_start +"~"+ entry_date_end;
			
			// validations			
			// jan 2024 length is 8 
			if (entry_date_start.length != 8) {
				alert("From Month is not valid");
				return false;
			}
			
			if (entry_date_end.length != 8) {
				alert("To Month is not valid");
				return false;
			}
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			
				if ( $('#company').val() < 1 ) {
					alert("Please select Company");
					return false;
				}
				
			<?php } ?>
			
			//CalPeriod
			fghg_table.column(17).search( start_end_date ); // CalPeriod
			
			//var vendor_id = $('#vendor').val();
			
			//alert(vendor_id.length);
			// check if vendor set 
			/*
			if (vendor_id.length > 0) {
				fghg_table.column(7).search( vendor_id ); // vendor
			} else {
				fghg_table.column(7).search(""); // vendor
			}
			*/		
			
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			var client_id = $('#company').val();
			// check if account set 
			if (client_id.length > 0) {
				fghg_table.column(18).search( client_id ); // client/company
			} else {
				fghg_table.column(18).search(""); // client/company
			}
			<?php } ?>
			
			//scope 1 year
			var scope1year = $('#scope1year').val();
			fghg_table.column(19).search( scope1year );
			
			//scope 2 year
			var scope2year = $('#scope2year').val();
			fghg_table.column(20).search( scope2year );
			
					
			//---------------------------
			
			fghg_table.draw(); // 
		  });
	
		// show column invoice updated to admin (group id 1) only
		////fghg_table.column(16).visible(false);   // To hide
		
		////fghg_table.column( 21 ).visible( false ); // hide client/company
		//fghg_table.column( 19 ).visible( false ); // hide country
		//fghg_table.column( 20 ).visible( false ); // hide state
		//fghg_table.column( 21 ).visible( false ); // hide site
		/*
		fghg_table.on('xhr', function () {
			var datatable_json = fghg_table.ajax.json();
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
		
		//$('#fghg_datatable tbody').on('click', 'tr', function() {
		/*
		$(document).on("click","#fghg_datatable tbody tr",function() {
			var dt_invoice_id = fghg_table.row(this).data().InvoiceID;
			console.log(dt_invoice_id);
			
			load_invoice(dt_invoice_id);
			//console.log('clicked: ' + fghg_table.row(this).data()[0])
			//console.log( fghg_table.row(this).data().InvoiceID )
		});
		*/
		
		/*
		fghg_table.on('click', 'tbody tr', function () {
			let data = fghg_table.row(this).data();
		 
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
				fghg_table.rowGroup().disable().draw();
			} else {
				//table.rowGroup().dataSrc($(this).data('column'));
				fghg_table.rowGroup().dataSrc($(this).val()).draw();
			}
		});
		
		// hide no group tr
		fghg_table.on( 'draw', function () {
			// your code here
			//alert('draw');
			var grp_tr = $("#fghg_datatable tbody").find('tr:first');
			if (grp_tr.text() == 'No group') {
				grp_tr.hide();
			}
			console.log(grp_tr);
		});
		
	});
	
	function move_back_ar () {
		$("#process_report").fadeIn( "slow" );
		$("#invoicedetails").fadeOut( "slow" );
		$("#invoicedetails").html('');
		
		$(".gobackbtn").hide();
		 window.scrollTo(0,scroll_y);
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
