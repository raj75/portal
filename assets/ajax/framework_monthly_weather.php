<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,127)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

//$_SESSION["group_id"] = 2; ////// for testing
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
	
	
	#mw_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#mw_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#mw_datatable{border-bottom: 1px solid #ccc !important;}
	#mw_datatable .isodrp{width:auto !important;}
	.select2-search{z-index:10;}
	/*.select2height{height:25px !important;}*/
	.select2-selection__clear {font-size:20px; margin-top:0px !important;}
	#reset_query {padding: 2px 10px 2px; margin-left: 5px; margin-top:-4px;} 
	.dataTables_wrapper.no-footer .dataTables_scrollBody {border-bottom:1px solid #cccccc;}
	#mw_datatable tbody tr {cursor: pointer;}
	/*
	#mw_datatable tbody tr td:nth-child(11) {
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
	
	
	#mw_datatable {
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
		<h1 class="page-title txt-color-blueDark" style="margin-bottom:18px;">
			<i class="fa fa-table"></i>
				Reports <span>> Monthly Weather</span> 
		</h1>
		
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
								<label><b>From Month</b></label>
								<br>
								
								<input class="mydatepickerhidden" type="hidden">
								
								<input id="from_month" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php //echo date('m/d/Y', strtotime(' - 1 month')); ?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>To Month</b></label>
								<br>
								
								<input class="mydatepickerhidden" type="hidden">
								
								<input id="to_month" type="text" class="form-control datepicker-- mydatepicker myinput" value="<?php //echo date("m/d/Y");?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Country</b></label>
								<br>
								<select multiple name="wa_country" id="wa_country" class="select2 select2height">
									<!--<option value="">Select Country</option>-->
								</select>
							</div>							
							
						</div>
						
						<div class="row myrow">
						
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label><b>Postal Code</b></label>
								<br>
								<select name="postal_code" id="postal_code" class="select2 select2height">
									<!--<option value="">Select Country</option>-->
								</select>
							</div>
						
						</div>
							
							
							
							
						
						</div>
						
						</form>
						<!--------------new row--------------------------->
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="" style="padding-left:15px; float:left">
								<button type="submit" class="btn-primary" id="create_query">Submit</button> 
								<button onclick="navigateurl('assets/ajax/framework_monthly_weather.php','Accounts Report')" type="button" class="btn btn-default" id="reset_query">Reset</button> 
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
		
		
		
		
		
<div id="datatable_container_ar" style="display:;">		
		<!------------------------------------------------------------------------------>
<!-- Query part -->

<br>

<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Monthly Weather Report </h2>
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

							<table id="mw_datatable" class="display hover" style="width:100%">
								<thead>
										
									<tr>		
										<th>Country</th>
										<th>Zip/Postal Code</th>
										<th>Year</th>
										<th>Month</th>
										<th>Average Temp</th>
										<th>Min Temp</th>
										<th>Max Temp</th>
										<th>Base Temp</th>
										<th>Heating Degree Days(HDD)</th>
										<th>Cooling Degree Days(CDD)</th>
										
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
	
	

</div>


<!---------chart div------------>
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  /*height: 500px;*/
  /*min-height: 150px;*/
  /*height: auto;*/
  height: 250px;
  /*min-height:200px;*/
}

</style>

<!-- Resources -->
<!--
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
-->
<!--<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>-->

<!-- Chart code -->
<script>
</script>

<!-- HTML -->
<div id="chartdiv"></div>
<!--------------------------------------------------------------->

<!-- NEW WIDGET START -->
		<section id="invoicedetails" class="sitestable m-top45 ">
		</section>

<!-------------------chart code start by amir----------------------->


<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"> 
	</script>

<script type="text/javascript">

	pageSetUp();
	
	//--------------------------------------
	var mw_table;
	// pagefunction
	var pagefunction = function() {
		
		console.log('pagefunction');
		mw_table = new DataTable('#mw_datatable', {
			ajax: 'assets/ajax/framework_monthly_weather_ajax.php',
			
			'processing': false,
			//'processing': true,
			'serverSide': true,
			'deferRender': true,
			'serverMethod': 'post',

			"lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
			"pageLength": 50,
			//"pageLength": 12,
			"retrieve": true,
			"scrollCollapse": true,
			"searching": true,
			"paging": true,
			//"scrollX": true,

			columns: [
				{ data: 'country_code' },
				{ data: 'postal_code' },
				{ data: 'year' },
				{ data: 'month' },
				
				/*
				{ data: null , render: function ( data, type, full, meta ) {
				 const d = new Date();
				 var dt_month = data.month;
				 
				 console.log(dt_month);
				 //d.setMonth(month-1);
				 d.setMonth(dt_month-1);
				 const monthName = d.toLocaleString("default", {month: "long"});
				 return monthName;
				 //var dt_month_arr['1'=>'Jan',2=>];
				 //return ;
    } },
				*/
				
	
				{ data: 'avg_temp' },
				{ data: 'min_temp' },
				{ data: 'max_temp' },
				{ data: 'base_temp' },
				{ data: 'hdd' },
				{ data: 'cdd'},
				
			],	
			order: [[ 2, 'desc' ], [ 3, 'asc' ]],			
			//"dom": 'Blfrtip',
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
			
			
			"preDrawCallback": function (settings) {
				$(".dots-cont").show();
			},  
 
		})
		
		.on("draw.dt", function (e, dt, type, indexes) {
				
				$(".dots-cont").hide();
				
				/*
				var grp_tr = $("#mw_datatable tbody").find('tr:first');
				if (grp_tr.text() == 'No group') {
					grp_tr.hide();
				}
				*/
				
				var my_qry = dt.json.qry;
				//my_qry = my_qry.replace(/(?:\r\n|\r|\n)/g, '<br>');
				$(".qry_area").val(my_qry);
				
				console.log('draw.dt');
				// create chart
				create_chart();
				//hide_chart_logo();
				
				
		})
	  ;
		
		
		///------------------------------------------
		
		
		
		
		
	}

	var scroll_y = 0;
	$(document).ready(function(){

		
		$("#wa_country,#postal_code").select2({
			 allowClear: true,
			 //placeholder: "Select Company",
			 placeholder: {
				id: "",
				placeholder: "Select Company"
			 },
			 ajax: { 
				 url: "assets/ajax/get_fweather_fields.php",
				 
				 type: "post",
				 dataType: 'json',
				 delay: 250,
				 data: function (params) {
					 var ele_id = this.context.id;
					 
					 var wa_country = $('#wa_country').val();
					 var postal_code = $('#postal_code').val();					 
					 
					 var fprocessForm = $('#fprocessForm');
					 
					 //console.log(this);
					 //alert(co_id);
					 return {
						 fieldid: ele_id,
						 form_data: fprocessForm.serialize(),						
						 
						 wa_country: wa_country,
						 postal_code: postal_code,						 
						 
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
		
		
		/*$('.mydatepicker').datepicker({  format: 'mm/dd/yyyy'});*/
		/*
		$('.mydatepicker').datepicker({
		  format: 'yyyy',
		  viewMode: 'years',
		  minViewMode: 'years'
		});
		*/
		
		/*
		$(".mydatepicker").datepicker({
			//changeMonth: true, 
			//changeYear: true, 
			dateFormat: "MM yy",
			//yearRange: "-90:+00"
		});
		*/
		
		/*
		$('.mydatepicker').monthpicker({			
			// e.g. May 2022			
			altFormat:'MM yy'	
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
			fpr_table.column(2).search( value ).draw();
		  }
		*/
		 
		  //var table = $('#example').DataTable();
		  
		  
		  $('#create_query').on('click', function () {
			  console.log("create_query");
			//filterColumn('Edinburgh');
			var from_month = $('#from_month').val(); // mm/dd/yyyy
			var to_month = $('#to_month').val(); // mm/dd/yyyy
			var from_to_month = from_month +"~"+ to_month;
			
			mw_table.column(2).search( from_to_month );
			//mw_table.column(3).search( invoice_date_end );		
						
			//var wa_country_id = $('#wa_country').val();
			
			
			var country_ar = $('#wa_country').val();
			// check if country set 
			if (country_ar != null && country_ar.length > 0) {
				mw_table.column(0).search( country_ar ); // country
			} else {
				mw_table.column(0).search(""); // country
			}
			
			
			var postal_code = $('#postal_code').val();
			
			//alert(postal_code);
			
			if ( postal_code == null ) {
				alert("Please select Postal Code");
				return false;
			}
			
			// check if postal code set 
			if (postal_code != null && postal_code.length > 0) {
				mw_table.column(1).search( postal_code ); // postal code
			} else {
				mw_table.column(1).search(""); // postal code
			}
			
			/*
			var site_id = $('#site').val();
			//var site_id = $('#site11').val();
			// check if site set 
			if (site_id.length > 0) {
				mw_table.column(5).search( site_id ); // site
			} else {
				mw_table.column(5).search(""); // site
			}
			*/
			//---------------------------
			console.log("create_query_1");
			mw_table.draw(); // 
			console.log("create_query_2");
		  });
		  
		  
		
		
		//-------------------------------------
		
		$('#group_by').on('change', function (e) {
			e.preventDefault();
			//alert($(this).val());
			var group_option = $(this).val();
			// disable/enalbe group
			//https://datatables.net/reference/api/rowGroup().disable()
			if (group_option == 'none') {
				mw_table.rowGroup().disable().draw();
			} else {
				//table.rowGroup().dataSrc($(this).data('column'));
				mw_table.rowGroup().dataSrc($(this).val()).draw();
			}
		});
		
		
		//-----------------------------------------------------------------------
		
		
		//am4core.ready(function() {

// Themes begin
//am4core.useTheme(am4themes_animated);
// Themes end


		



		
		
	}); // document ready
	
	

//---------------chart functions ------------------
		var chart;
		//var colorSet;
		//var dateAxis;
		
		var heatLegend;
		var chart_row = 0;
		
function create_chart() {
	
	
//var chart = am4core.create("chartdiv", am4charts.XYChart);
chart = am4core.create("chartdiv", am4charts.XYChart);
chart.maskBullets = false;

var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());

//xAxis.dataFields.category = "weekday";
//yAxis.dataFields.category = "hour";

xAxis.dataFields.category = "month";
yAxis.dataFields.category = "year";

xAxis.renderer.opposite = true;

xAxis.renderer.grid.template.disabled = true;
/*xAxis.renderer.minGridDistance = 40;*/
xAxis.renderer.minGridDistance = 10;

yAxis.renderer.grid.template.disabled = true;
yAxis.renderer.inversed = true;
/*yAxis.renderer.minGridDistance = 30;*/
yAxis.renderer.minGridDistance = 10;

var series = chart.series.push(new am4charts.ColumnSeries());
//series.dataFields.categoryX = "weekday";
//series.dataFields.categoryY = "hour";
//series.dataFields.value = "value";

series.dataFields.categoryX = "month";
series.dataFields.categoryY = "year";
series.dataFields.value = "avgtemp";

series.sequencedInterpolation = true;
series.defaultState.transitionDuration = 3000;

var bgColor = new am4core.InterfaceColorSet().getFor("background");

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 1;
columnTemplate.strokeOpacity = 0.2;
columnTemplate.stroke = bgColor;
//columnTemplate.tooltipText = "{weekday}, {hour}: {value.workingValue.formatNumber('#.')}";
columnTemplate.width = am4core.percent(100);
columnTemplate.height = am4core.percent(100);

series.heatRules.push({
  target: columnTemplate,
  property: "fill",
  //min: am4core.color(bgColor),
  //max: chart.colors.getIndex(0)
  "min": am4core.color("#89CFF0"),
  "max": am4core.color("#FE595E"),

});

// heat legend
//var heatLegend = chart.bottomAxesContainer.createChild(am4charts.HeatLegend);
heatLegend = chart.bottomAxesContainer.createChild(am4charts.HeatLegend);
heatLegend.width = am4core.percent(100);
heatLegend.series = series;
heatLegend.valueAxis.renderer.labels.template.fontSize = 9;
/*heatLegend.valueAxis.renderer.minGridDistance = 30;*/
heatLegend.minColor = am4core.color("#89CFF0");
heatLegend.maxColor = am4core.color("#FE595E");

// heat legend behavior
series.columns.template.events.on("over", function(event) {
  handleHover(event.target);
})

series.columns.template.events.on("hit", function(event) {
  handleHover(event.target);
})

/*
function handleHover(column) {
  if (!isNaN(column.dataItem.value)) {
    heatLegend.valueAxis.showTooltipAt(column.dataItem.value)
  }
  else {
    heatLegend.valueAxis.hideTooltip();
  }
}
*/

series.columns.template.events.on("out", function(event) {
  heatLegend.valueAxis.hideTooltip();
})

//$( '#chartdiv' ).height(chart_row*25);


chart.data = reloadData();

console.log(chart_row);
			
			/*
			// Set cell size in pixels
			var cellSize = 20;
			chart.events.on("datavalidated", function(ev) {
			
			console.log('datavalidated');
			  // Get objects of interest
			  var chart11 = ev.target;
			  //var categoryAxis11 = chart11.yAxes.getIndex(0);
			  var categoryAxis11 = chart11.xAxes.getIndex(0);

			  // Calculate how we need to adjust chart height
			  var adjustHeight = chart11.data.length * cellSize - categoryAxis11.pixelHeight;

			  // get current chart height
			  var targetHeight = chart11.pixelHeight + adjustHeight;

			  // Set it on chart's container
			  chart11.svgContainer.htmlElement.style.height = targetHeight + "px";
			});
			*/
			
			/*
			// Set cell size in pixels
			var cellSize = 30;
			chart.events.on("datavalidated", function(ev) {

			  // Get objects of interest
			  var chart = ev.target;
			  //var categoryAxis = chart.yAxes.getIndex(0);

			  // Calculate how we need to adjust chart height
			  //var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

			  // get current chart height
			  //var targetHeight = chart.pixelHeight + adjustHeight;
			  
			  var setHeight = chart_row * cellSize;

			  // Set it on chart's container
			  //chart.svgContainer.htmlElement.style.height = targetHeight + "px";
			  chart.svgContainer.htmlElement.style.height = setHeight + "px";
			  
			});
			*/
			
			
			chart.events.on("datavalidated", function(ev) {
				var chart11 = ev.target;
				//$( '#chartdiv' ).height(chart_row*25);
				//chart11.resize();
				//chart_height = chart_row*25;
				//chart11.svgContainer.htmlElement.style.height = chart_height + "px";
				
			});
			
			
			

//}); // end am4core.ready()

} // create chart

// moved from create chart function
function handleHover(column) {
  if (!isNaN(column.dataItem.value)) {
    heatLegend.valueAxis.showTooltipAt(column.dataItem.value)
  }
  else {
    heatLegend.valueAxis.hideTooltip();
  }
}
//--------------------------------

function loadNewData() {
			chart.data = "";
			chart.data = reloadData();
		}
		

function reloadData() {
			
			
			console.log('reloadData');
			var newData = [];
			var colorInd = 0;
			var oldyear = '';
			chart_row = 0;
			$("#mw_datatable tbody tr").each(function(index, tr){
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
					var dt_month = $(this).find(".dt_month").text();
					var dt_year = $(this).find(".dt_year").text();
					
					var dt_avgtemp = $(this).find(".dt_avgtemp").text();
					
					
					if (dt_year!='') {
						item = {}
						//item ["category"] = "Contract #"+ar_contract_id;
						//item ["category"] = dt_accountno;
						item ["month"] = dt_month;
						item ["year"] = dt_year;
						
						item ["avgtemp"] = dt_avgtemp;
						
						colorInd++;
						
						newData.push(item);
					}
					
					// chart rows
					if (oldyear != dt_year ) {
						oldyear = dt_year;
						chart_row = chart_row + 1;
					}

			});
			
			var new_chart_height = (chart_row*30)+100;
			$( '#chartdiv' ).height(new_chart_height);
			console.log(new_chart_height);
			
			return newData;
			
			
			
			/*
			//-----------------------------------------------
			
			newData = [
  
  //{
    //"hour": "12pm",
    //"weekday": "Sun",
    //"value": 2990
  //},
  
  
  
  
    {
    "year": 2019,
    "month": 1,
    "avgtemp": 18
  },
  {
    "year": 2019,
    "month": 2,
    "avgtemp": 19
  },
  {
    "year": 2019,
    "month": 3,
    "avgtemp": 20
  },
  {
    "year": 2019,
    "month": 4,
    "avgtemp": 43
  },
  {
    "year": 2019,
    "month": 5,
    "avgtemp": 35
  },
  {
    "year": 2019,
    "month": 6,
    "avgtemp": 40
  },
  {
    "year": 2019,
    "month": 7,
    "avgtemp": 52
  },
  {
    "year": 2019,
    "month": 8,
    "avgtemp": 65
  },
  {
    "year": 2019,
    "month": 9,
    "avgtemp": 60
  },
  
  
  
  {
    "year": 2018,
    "month": 1,
    "avgtemp": 25
  },
  {
    "year": 2018,
    "month": 2,
    "avgtemp": 27
  },
  {
    "year": 2018,
    "month": 3,
    "avgtemp": 31
  },
  {
    "year": 2018,
    "month": 4,
    "avgtemp": 33
  },
  {
    "year": 2018,
    "month": 5,
    "avgtemp": 42
  },
  {
    "year": 2018,
    "month": 6,
    "avgtemp": 44
  },
  {
    "year": 2018,
    "month": 7,
    "avgtemp": 52
  },
  {
    "year": 2018,
    "month": 8,
    "avgtemp": 68
  },
  {
    "year": 2018,
    "month": 9,
    "avgtemp": 53
  },

];

*/


//return newData;

		}
		
		
		
	/*
	function move_back_ar () {
		$("#process_report").fadeIn( "slow" );
		$("#invoicedetails").fadeOut( "slow" );
		$("#invoicedetails").html('');
		
		$(".gobackbtn").hide();
		 window.scrollTo(0,scroll_y);
	}
	*/
	
	/*show hide datatable*/
	 // Hide menu once we know its width
	 
	/*
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
	*/	
	
	
	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		
		loadScript("assets/js/amchart/core/core.js", function(){
			loadScript("assets/js/amchart/core/charts.js", function(){
				loadScript("assets/js/amchart/core/themes/animated.js", function(){
					
					loadScript("assets/js/monthpicker_ar.js", function(){
					
						pagefunction();
						
						$('.mydatepickerhidden').monthpicker({
				
							// e.g. May 2022			
							//altFormat:'MM yy'	
							altFormat:'M yy'	
							//altFormat:'yy-mm-dd'
						});
					
					});
		
		});
			});
		});
		
	});

	
</script>

