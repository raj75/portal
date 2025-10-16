<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,128)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

$_SESSION["group_id"] = 2;

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
	
	
	#flf_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#flf_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#flf_datatable{border-bottom: 1px solid #ccc !important;}
	#flf_datatable .isodrp{width:auto !important;}
	.select2-search{z-index:10;}
	/*.select2height{height:25px !important;}*/
	.select2-selection__clear {font-size:20px; margin-top:0px !important;}
	#reset_query {padding: 2px 10px 2px; margin-left: 5px; margin-top:-4px;} 
	.dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom:1px solid #cccccc;}
	#flf_datatable tbody tr {cursor: pointer;}
	/*
	#flf_datatable tbody tr td:nth-child(11) {
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
	
	
	#flf_datatable {
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
				Reports <span>> Deposit/Late Fee Report</span>
		</h1>
		
	</div>
</div>
</section>

<div id="process_report">
		<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

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
								<label><b>Entry Date Start</b></label>
								<br>
								<input id="entry_date_start" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php echo date('m/d/Y', strtotime(' - 1 month')); ?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Entry Date End</b></label>
								<br>
								<input id="entry_date_end" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php echo date("m/d/Y");?>"  autocomplete="off" />
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
									<!--<option value="">Select Account</option>-->
								</select>
							</div>
						
						
						
							
							
							
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Country</b></label>
								<br>
								<select multiple name="country" id="country" class="select2 select2height">
									<!--<option value="">Select Country</option>-->
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>State</b></label>
								<br>
								<select multiple name="state" id="state" class="select2 select2height">
									<!--<option value="">Select State</option>-->								
								</select>
							</div>
							
						  </div>
						  
						  <div class="row myrow">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Site</b></label>
								<br>
								<!--<select multiple name="site" id="site11" class="select2">-->
								<select multiple name="site" id="site" class="select2 select2height">
									<!--<option value="">Select Site</option>-->
								</select>
							</div>	

							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Group Data By</b></label>
								<br>
								<select name="group_by" id="group_by" class="select2 select2group">
									<option value='none' selected>None</option>
									<option value='Vendor_Name'>Vendor</option>
									<option value='Account_Number'>Account</option>
									<option value='Site_Number'>Site</option>
									<option value='Site_State'>State</option>
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
								<button onclick="navigateurl('assets/ajax/framework_late_fee_report.php','Accounts Report')" type="button" class="btn btn-default" id="reset_query">Reset</button> 
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
						<h2>Deposit/Late Fee Report </h2>
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

							<table id="flf_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>Client ID</th>
										<th>Invoice ID</th>
										<th>Entry Date</th>
										<th>Due Date</th>
										<th>Site Number</th>
										<th>Site Country</th>
										<th>Site State</th>
										<th>Vendor Name</th>
										<th>Account Number</th>
										<th>Description</th>
										<th>Cost</th>
										
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
	var flf_table;
	// pagefunction
	var pagefunction = function() {
		
		
		flf_table = new DataTable('#flf_datatable', {
			ajax: 'assets/ajax/framework_late_fee_report_ajax.php',
			
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
				{ data: 'InvoiceID' },
				{ data: 'EntryDate' },
				{ data: 'DueDate' },
				{ data: 'Site_Number' },
				{ data: 'Site_Country' },
				{ data: 'Site_State' },
				{ data: 'Vendor_Name' },
				{ data: 'Account_Number' },
				{ data: 'Description' },
				{ data: 'Cost' },
				
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
				//var grp_txt = $("#flf_datatable tbody tr:first-child").text();
				//var grp_txt = $("#flf_datatable tbody").find('tr:first').text();
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
					 var vendor_ids = $('#vendor').val();
					 var account_ids = $('#account').val();
					 
					 <?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
					 var client_ids = $('#company').val();
					 <?php } ?>
					 
					 var country_ar = $('#country').val();
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
			flf_table.column(2).search( value ).draw();
		  }
		*/
		 
		  //var table = $('#example').DataTable();
		  
		  $('#create_query').on('click', function () {
			//filterColumn('Edinburgh');
			var entry_date_start = $('#entry_date_start').val(); // mm/dd/yyyy
			var entry_date_end = $('#entry_date_end').val(); // mm/dd/yyyy
			var start_end_date = entry_date_start +"~"+ entry_date_end;
			
			// validations
			if (isValidDate(entry_date_start) == false) {
				alert("Entry Date Start is not valid date");
				return false;
			}
			
			if (isValidDate(entry_date_end) == false) {
				alert("Entry Date End is not valid date");
				return false;
			}
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			
				if ( $('#company').val() < 1 ) {
					alert("Please select Company");
					return false;
				}
				
			<?php } ?>
			
			//flf_table.column(3).search( start_end_date ).draw(); // due date
			flf_table.column(2).search( start_end_date ); // entry date
			
			var vendor_id = $('#vendor').val();
			
			//alert(vendor_id.length);
			// check if vendor set 
			if (vendor_id.length > 0) {
				flf_table.column(7).search( vendor_id ); // vendor
			} else {
				flf_table.column(7).search(""); // vendor
			}
			
			var account_id = $('#account').val();
			// check if account set 
			if (account_id.length > 0) {
				flf_table.column(8).search( account_id ); // account
			} else {
				flf_table.column(8).search(""); // account
			}
			
			<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
			var client_id = $('#company').val();
			// check if account set 
			if (client_id.length > 0) {
				flf_table.column(0).search( client_id ); // client/company
			} else {
				flf_table.column(0).search(""); // client/company
			}
			<?php } ?>
			
			var country_ar = $('#country').val();
			// check if country set 
			if (country_ar.length > 0) {
				flf_table.column(5).search( country_ar ); // country
			} else {
				flf_table.column(5).search(""); // country
			}
			
			var state_ar = $('#state').val();
			// check if state set 
			if (state_ar.length > 0) {
				flf_table.column(6).search( state_ar ); // state
			} else {
				flf_table.column(6).search(""); // state
			}
			
			var site_id = $('#site').val();
			//var site_id = $('#site11').val();
			// check if site set 
			if (site_id.length > 0) {
				flf_table.column(4).search( site_id ); // site
			} else {
				flf_table.column(4).search(""); // site
			}
			
			//---------------------------
			
			flf_table.draw(); // 
		  });
	
		// show column invoice updated to admin (group id 1) only
		////flf_table.column(16).visible(false);   // To hide
		
		////flf_table.column( 21 ).visible( false ); // hide client/company
		//flf_table.column( 19 ).visible( false ); // hide country
		//flf_table.column( 20 ).visible( false ); // hide state
		//flf_table.column( 21 ).visible( false ); // hide site
		/*
		flf_table.on('xhr', function () {
			var datatable_json = flf_table.ajax.json();
			//alert(json.data.length + ' row(s) were loaded');
			//alert(datatable_json.qry);
			var my_qry = datatable_json.qry;
			//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
			$(".qry_area").val(my_qry);
		});
		*/
		
		// validate date		
		
		//https://www.scaler.com/topics/date-validation-in-javascript/
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
		
		// datatable row click
		
		//$('#flf_datatable tbody').on('click', 'tr', function() {
		$(document).on("click","#flf_datatable tbody tr",function() {
			var dt_invoice_id = flf_table.row(this).data().InvoiceID;
			console.log(dt_invoice_id);
			
			load_invoice(dt_invoice_id);
			//console.log('clicked: ' + flf_table.row(this).data()[0])
			//console.log( flf_table.row(this).data().InvoiceID )
		});
		
		/*
		flf_table.on('click', 'tbody tr', function () {
			let data = flf_table.row(this).data();
		 
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
				flf_table.rowGroup().disable().draw();
			} else {
				
				var order_ind;
				//VendorName
				//VendorState
				
				if (group_option == 'Vendor_Name') {
					order_ind = 7;
				} else if (group_option == 'Account_Number') {
					order_ind = 8;
				} else if (group_option == 'Site_Number') {
					order_ind = 4;
				} else if (group_option == 'Site_State') {
					order_ind = 6;
				} 
				
				flf_table.order([order_ind, 'asc']).rowGroup().dataSrc($(this).val()).draw();
				
				//table.rowGroup().dataSrc($(this).data('column'));
				//flf_table.rowGroup().dataSrc($(this).val()).draw();
			}
		});
		
		// hide no group tr
		flf_table.on( 'draw', function () {
			// your code here
			//alert('draw');
			var grp_tr = $("#flf_datatable tbody").find('tr:first');
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
		pagefunction();
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
