<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,55)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$cname=$_SESSION["company_id"];

if(isset($_GET["load"])){

$subquery = "";

if ( isset($_GET["showdemo"]) ) {
	if ( $_GET["showdemo"]==1 ) { // 1 mean hide
		$subquery = "?showdemo=1";
	} else if ( $_GET["showdemo"]==0 ) { // 0 mean show
		$subquery = "?showdemo=0";
	}
} else {
	$subquery = "?showdemo=1";
}


//echo "subquery==".$subquery;

//select min max contract date

$min_max_q = $mysqli->query("Select min(`Start Date`) as min_contract, max(`End Date`) as max_contract from contracts ");
$min_max = $min_max_q->fetch_assoc();

	// min month and year
	if ( date('Y',strtotime($min_max['min_contract'])) < 2000 ) {
		$min_month = 1;
		$min_year = 2000;
	} else {
		$min_month = date('m',strtotime($min_max['min_contract']));
		$min_year = date('Y',strtotime($min_max['min_contract']));
	}

	// max year and month
	$max_month = date('m',strtotime($min_max['max_contract']));
	$max_year = date('Y',strtotime($min_max['max_contract']));


	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link href="assets/plugins/datatables_ar/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

	<style>
	#cm_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	#cm_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#cm_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}

	.sssdrp{width: 85px !important;}
	</style>

	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>

	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Supplier Contracts </h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding">
							<table id="cm_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
								<thead>

									<tr>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Contract ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter State" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Vendor" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Supplier" />
										</th>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Company Name" />
										</th>
									<?php } ?>
									<?php if(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and 1==2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Supplier ID" />
										</th>
									<?php } ?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Initiated Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Start Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter End Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Product" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Commodity" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Notes" />
										</th>
										<th class="hasinput">
											<!--<input type="text" class="form-control" placeholder="Filter Status" />-->
											<select class="form-control sssdrp" id="fstatus">
												<option value="all">All</option>
												<option value="Active" selected>Active</option>
												<option value="Inactive">Inactive</option>
											</select>
											<!--
											<label class="select">
												<select name="addsstatus" id="addsstatus">
													<option value="">Status</option>
													<option value="Active"  >Active</option>
													<option value="Inactive"  >Inactive</option>
												</select>
											</label>
											-->
										</th>
										<?php if(1==2){?><th></th><?php } ?>
									</tr>
									<tr>
										<th data-hide="phone,tablet">Contract ID </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">Vendor </th>
										<th data-hide="phone,tablet">Supplier </th>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">Company Name </th>
									<?php } ?>
									<?php if(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and 1==2){?>
										<th data-hide="phone,tablet">Supplier ID </th>
									<?php } ?>
										<th data-hide="phone,tablet">Initiated Date </th>
										<th data-hide="phone,tablet">Start Date </th>
										<th data-hide="phone,tablet">End Date </th>
										<th data-hide="phone,tablet">Product </th>
										<th data-hide="phone,tablet">Commodity </th>
										<th data-hide="phone,tablet">Notes </th>
										<th data-hide="phone,tablet">Status </th>
										<?php if(1==2){?><th data-hide="phone,tablet">Action</th><?php } ?>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->



<!-------------------chart code start by amir----------------------->

	<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<script>
		loadScript("assets/js/amchart/core/core.js", function(){
			loadScript("assets/js/amchart/core/charts.js", function(){
				loadScript("assets/js/amchart/themes/material.js", function(){

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

</script>

<!-- Chart code -->
<script>
		var chart;
		var colorSet;
		var dateAxis;

		function create_chart() {

			//am4core.ready(function() {

			// Themes begin
			//am4core.useTheme(am4themes_frozen);
			am4core.useTheme(am4themes_material);
			//am4core.useTheme(am4themes_animated);
			// Themes end

			//var chart = am4core.create("chartdiv", am4charts.XYChart);
			chart = am4core.create("chartdiv", am4charts.XYChart);
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

			chart.paddingRight = 30;
			chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm";
			//chart.dateFormatter.inputDateFormat = "MM-dd-yyyy HH:mm";

			//var colorSet = new am4core.ColorSet();
			colorSet = new am4core.ColorSet();
			colorSet.saturation = 0.4;

			//chart.data = [<?php //echo implode(",",$chart_data_arr);?>];
			//chart.data = "";

			chart.data = reloadData();


			//chart.dateFormatter.dateFormat = "dd/MM/yyyy";
			chart.dateFormatter.dateFormat = "MM/dd/yyyy";

			var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "category";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.inversed = true;
			categoryAxis.renderer.minGridDistance = 0;


			//var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
			dateAxis = chart.xAxes.push(new am4charts.DateAxis());
			dateAxis.renderer.minGridDistance = 70;
			//dateAxis.renderer.minGridDistance = 20;


			//dateAxis.renderer.grid.template.location = 0.5;
			//dateAxis.startLocation = 0.5;
			//dateAxis.endLocation = 0.5;

			// Setting up label rotation
			//dateAxis.renderer.labels.template.rotation = 90;
			//dateAxis.renderer.labels.template.horizontalCenter = "left";
			//dateAxis.renderer.labels.template.verticalCenter = "middle";


			dateAxis.renderer.labels.template.location = 0.0001;

			//dateAxis.renderer.grid.template.location = 0;
			//dateAxis.renderer.labels.template.verticalCenter = "middle";
			//dateAxis.renderer.labels.template.horizontalCenter = "left";

			dateAxis.renderer.labels.template.rotation = 320;

			dateAxis.renderer.labels.template.adapter.add("dx", function(dx, target) {
			  //if (target.dataItem && target.dataItem.index & 2 == 2) {
				return dx - 30;
			  //}
			  //return dy;
			});


			//dateAxis.renderer.grid.template.disabled = true;

			//dateAxis.renderer.minGridDistance = 30;
			/////dateAxis.renderer.minGridDistance = 40;
			//dateAxis.baseInterval = { count: 1, timeUnit: "day" };
			///dateAxis.baseInterval = { count: 30, timeUnit: "day" };
			////dateAxis.baseInterval = { count: 1, timeUnit: "day" };
			//timeUnit: "month", count: 1
			// dateAxis.max = new Date(2018, 0, 1, 24, 0, 0, 0).getTime();

			dateAxis.renderer.tooltipLocation = 0;
			//dateAxis.dateFormatter.dateFormat = "MM-yyyy";
			//dateAxis.dateFormats.setKey("day", "dd");
			dateAxis.dateFormats.setKey("month", "MMM yyyy");
			//dateAxis.min = new Date('<?php //echo $chart_min?>').getTime();
			//dateAxis.max = new Date('<?php //echo $chart_max?>').getTime();
			//dateAxis.strictMinMax = true;

			//var currentDate_ar = new Date();
			//past
			//var past_years = new Date(new Date().setFullYear(new Date().getFullYear() - 3));
			//future
			//var future_years = new Date(new Date().setFullYear(new Date().getFullYear() + 3));

			////dateAxis.min = new Date( past_years.getFullYear() , past_years.getMonth() ).getTime();
			////dateAxis.max = new Date( future_years.getFullYear() , future_years.getMonth() ).getTime();

			//dateAxis.min = new Date( <?php echo $min_year?> , <?php echo $min_month?> ).getTime();
			//dateAxis.max = new Date( <?php echo $max_year?> , <?php echo $max_month?> ).getTime();

			//dateAxis.strictMinMax = true;

			/*
			dateAxis.gridIntervals.setAll([
			  { timeUnit: "month", count: 1 }
			]);
			*/
			dateAxis.baseInterval = { count: 1, timeUnit: "month" };

			var series1 = chart.series.push(new am4charts.ColumnSeries());
			series1.columns.template.height = am4core.percent(70);
			//series1.columns.template.tooltipText = "{task}: [bold]{openDateX}[/] - [bold]{dateX}[/]";
			series1.columns.template.tooltipText = "[bold]{openDateX}[/] - [bold]{dateX}[/]";

			series1.dataFields.openDateX = "start";
			series1.dataFields.dateX = "end";
			series1.dataFields.categoryY = "category";
			series1.columns.template.propertyFields.fill = "color"; // get color from data
			series1.columns.template.propertyFields.stroke = "color";
			series1.columns.template.strokeOpacity = 1;

			var valueLabel = series1.bullets.push(new am4charts.LabelBullet());
			valueLabel.label.text = "{state}";
			valueLabel.label.fontSize = 12;
			valueLabel.label.horizontalCenter = "center";
			//valueLabel.label.dx = 10;



			chart.scrollbarX = new am4core.Scrollbar();
			chart.scrollbarX.parent = chart.bottomAxesContainer;

			//}); // end am4core.ready()




		} // end of function

		//function loadNewData(otable) {
		function loadNewData() {
			chart.data = "";
			chart.data = reloadData();
		}

		function reloadData() {

			var newData = [];

			$("#cm_datatable_fixed_column tbody tr").each(function(index, tr){
				//console.log($(this).find('.ar_contract_id').text());
					var colorInd = index;
					var ar_contract_id = $(this).find(".ar_contract_id").text();
					var ar_start_date = $(this).find(".ar_start_date").text();
					var ar_end_date = $(this).find(".ar_end_date").text();
					var ar_state = $(this).find(".ar_state").text();

					item = {}
					item ["category"] = "Contract #"+ar_contract_id;
					item ["start"] = ar_start_date;
					item ["end"] = ar_end_date;
					item ["color"] = colorSet.getIndex(colorInd).brighten(0);
					item ["state"] = ar_state;

					newData.push(item);

			});

			return newData;
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
		function set_contract_height() {
				// Set cell size in pixels
				var cellSize = 15;
				chart.events.on("datavalidated", function(ev) {

				  // Get objects of interest
				  var chart = ev.target;
				  var categoryAxis = chart.yAxes.getIndex(0);

				  // Calculate how we need to adjust chart height
				  var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

				  // get current chart height
				  var targetHeight = chart.pixelHeight + adjustHeight;

				  // Set it on chart's container
				  chart.svgContainer.htmlElement.style.height = targetHeight + "px";
				});
		}

		function set_min_max_date () {
			//alert(dateAxis.max);

			chart.events.on("ready", function () {

				var min_date = new Date(dateAxis.min);
				var min_year = min_date.getFullYear();

				var max_date = new Date(dateAxis.max);
				var max_year = max_date.getFullYear();
				//past
				var past_years_date = new Date(new Date().setFullYear(new Date().getFullYear() - 3)); // current - 3 years
				//future
				var future_years_date = new Date(new Date().setFullYear(new Date().getFullYear() + 3));

				//alert(min_year);

				var slider_min = min_date;

				if ( min_date < past_years_date ) {
					slider_min = past_years_date;
				}

				//if ( min_year > past_years_date.getFullYear() ) {
				if ( min_year > past_years_date.getFullYear() ) {
					slider_min = past_years_date;
				}
				//dateAxis.min = new Date( 2014 , 1 ).getTime();
				//dateAxis.max = new Date( 2026 , 5 ).getTime();
				// set min max if these are less then past and future 3 years
				var slider_max = max_date;
				if ( max_year > future_years_date.getFullYear() ) {
					slider_max = future_years_date;
				}

				// if no data between 3 year past and 3 year future
				// then zoom to resent month
				if ( past_years_date.getFullYear() >= max_year && past_years_date.getMonth() > max_date.getMonth() ) {
					//slider_min = min_date;
					//slider_max = max_date;

					//slider_max = max_date;
					var max_date_copy = max_date;
					//slider_min = new Date( max_date.setMonth(max_date.getMonth() - 1) );
					//alert("max_date=="+max_date.getMonth());
					slider_min = new Date( max_date_copy.setMonth(max_date_copy.getMonth() - 2) );
					slider_max = new Date( max_date_copy.setMonth(max_date_copy.getMonth() + 2) );
					//slider_min = max_date_copy;
					//alert( "slider_min=="+slider_min.getMonth() );
					//alert( "slider_max=="+slider_max.getMonth() );
					//slider_max = new Date ( max_date.setMonth(max_date.getMonth() - 1) );

				}

			  dateAxis.zoomToDates(
				//new Date(2050,01,01),
				//new Date(2060,01,01)
				//new Date(new Date().setFullYear(new Date().getFullYear() - 3)),
				//new Date(new Date().setFullYear(new Date().getFullYear() + 3))
				new Date( slider_min.getFullYear() , slider_min.getMonth() ),
				new Date( slider_max.getFullYear() , slider_max.getMonth() )
			  );
			});
		}

		////function focus_chart () {
		//current date
		//var currentDate_ar = new Date();
		//past
		//var past_years = new Date(new Date().setFullYear(new Date().getFullYear() - 3));
		//future
		//var future_years = new Date(new Date().setFullYear(new Date().getFullYear() + 3));

			//chart.events.on("ready", function () {
			  ////dateAxis.zoomToDates(
				////new Date(2050,01,01),
				////new Date(2060,01,01)
				//new Date(new Date().setFullYear(new Date().getFullYear() - 3)),
				//new Date(new Date().setFullYear(new Date().getFullYear() + 3))
			  ////);
			//});
		////}

</script>

<!-- HTML -->
<div id="chartdiv"></div>

<!-------------------chart code end by amir----------------------->


	<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
	<script type="text/javascript">

		/* DO NOT REMOVE : GLOBAL FUNCTIONS!
		 *
		 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
		 *
		 * // activate tooltips
		 * $("[rel=tooltip]").tooltip();
		 *
		 * // activate popovers
		 * $("[rel=popover]").popover();
		 *
		 * // activate popovers with hover states
		 * $("[rel=popover-hover]").popover({ trigger: "hover" });
		 *
		 * // activate inline charts
		 * runAllCharts();
		 *
		 * // setup widgets
		 * setup_widgets_desktop();
		 *
		 * // run form elements
		 * runAllForms();
		 *
		 ********************************
		 *
		 * pageSetUp() is needed whenever you load a page.
		 * It initializes and checks for all basic elements of the page
		 * and makes rendering easier.
		 *
		 */

		pageSetUp();

		/*
		 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
		 * eg alert("my home function");
		 *
		 * var pagefunction = function() {
		 *   ...
		 * }
		 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
		 *
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {
			//console.log("cleared");

			/* // DOM Position key index //

				l - Length changing (dropdown)
				f - Filtering input (search)
				t - The Table! (datatable)
				i - Information (records)
				p - Pagination (paging)
				r - pRocessing
				< and > - div elements
				<"#id" and > - div with an id
				<"class" and > - div with a class
				<"#id.class" and > - div with an id and class

				Also see: http://legacy.datatables.net/usage/features
			*/

			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;

				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};

			/* COLUMN FILTER  */
				var otable = $("#cm_datatable_fixed_column").DataTable( {

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'deferRender': true,
					'ajax': {
					   'url':'assets/ajax/contract-manager-ajax.php<?php echo $subquery; ?>'
					},

					"drawCallback" : function(settings) {
						$(".dots-cont").hide();
					},
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},

					'columns': [

					 { data: 'ContractID' },
					 { data: 'State' },
					 { data: 'vendor_name' },
					 { data: 'supplier' },
					 <?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
					 { data: 'company_name' },
					 <?php } ?>
					 //{ data: 'SupplierID' },
					 { data: 'Initiated_Date' },
					 { data: 'Start_Date' },
					 { data: 'End_Date' },
					 { data: 'Product' },
					 { data: 'service_group' },
					 { data: 'Notes' },
					 { data: 'Status' }

					]
					,


					"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
					"pageLength": 25,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"dom": 'Blfrtip',
					"buttons": [
						'copyHtml5',
						'excelHtml5',
						'csvHtml5',
						{
							'extend': 'pdfHtml5',
							'title' : 'Vervantis_PDF',
							'messageTop': 'Vervantis PDF Export'
						},
						//'pdfHtml5'
						{
							'extend': 'print',
							//'title' : 'Vervantis',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
						},
						{
							'text': 'Columns',
							'extend': 'colvis'
						}
					],
					"autoWidth" : true
				});

			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter

			$("#cm_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
			//alert("loadNewData");
				otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

				// start by amir
				//loadNewData(otable);
				loadNewData();
				// end by amir


			});


			///------------------
			otable.on( 'draw', function () {
				//alert('draw');
				// your code here
				create_chart();
				hide_chart_logo();
				set_contract_height();

				set_min_max_date();
				//focus_chart();
				//loadNewData();

					/*
					chart.events.on("ready", function () {

					  dateAxis.zoomToDates(
						//new Date(2050,01,01),
						//new Date(2060,01,01)
						new Date(new Date().setFullYear(new Date().getFullYear() - 3)),
						new Date(new Date().setFullYear(new Date().getFullYear() + 3))
					  );
					});
					*/
			});

			$("#cm_datatable_fixed_column .sssdrp").on( 'keyup change', function () {

				otable
					.column( $(this).parent().index()+':visible' )
					//.search(this.value, true, true, false)
					  //.search(this.value, false, false, false)
					  .search(this.value, false, false, false)
					//.search(this.value, false,true,false)
					//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
					.draw();

					//otable.search( this.value, false, false, false ).draw();

			} );
		};

		function multifilter(nthis,fieldname,otable)
		{
				var selectedoptions = [];
				$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
					selectedoptions.push($(this).val());
				});
				otable
				 .column( $(nthis).parent().index()+':visible' )
				 .search("^" + selectedoptions.join("|") + "$", true, false, true)
				 .draw();
		}

		function multilist(indexno)
		{
			var items=[], options=[];
			$('#cm_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
			   items.push( $(this).text() );
			});
			var items = $.unique( items );
			$.each( items, function(i, item){
				options.push('<option value="' + item + '">' + item + '</option>');
			})
			return options;
		}


		// load related plugins
		loadScript("assets/plugins/datatables_ar/1.10.19/js/jquery.dataTables.min.js", function(){
			loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
				loadScript("assets/plugins/datatables_ar/jszip/3.1.3/jszip.min.js", function(){
				loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/pdfmake.min.js", function(){
				loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/vfs_fonts.js", function(){
				loadScript("assets/plugins/datatables_ar/buttons/1.4.2/js/buttons.print.js", function(){
					loadScript("assets/plugins/datatables_ar/buttons/1.0.3/js/buttons.colVis.js", function(){
					loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
				});
				});
				});
				});
				});
			});
		});

	function loadcmmenu(cmid) {
		$('#cmresponse').html('');
		$('#cmresponse').load('assets/ajax/contract-manager-pedit.php?action=details&ct=<?php rand(2,99); ?>&cmid='+cmid);
	}
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>

	function addnewacc(cm_id){

		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		parent.$("#dialog-message").remove();
		parent.$(".cmtable").fadeOut( "slow" );
		parent.$('#cmresponse').fadeOut( "slow" );
		parent.$('#cmdetails').fadeOut( "slow" );
		parent.$('#cmaccdetails').load('assets/ajax/contract-manager-pedit.php?action=addcmacc&ctid='+cm_id+'&ct=<?php echo rand(9,33); ?>');
	}

	function deletecm(cmid) {
		$('#cmresponse').html('');
		var r = confirm("Are you sure want to delete it!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/contractmanageredit.inc.php',
				data: {cmid:cmid,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							alert("Success");
							parent.$("#cmtable").html('');
							parent.$('#cmtable').load('assets/ajax/contract-manager.php?load=true');
						}else
							alert("Error in request. Please try again later.");
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}
	<?php } ?>
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
///////////////MOVE BACK//////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////


	function move_back(){
		parent.$('#cmdetails').html('');
		parent.$('#cmresponse').html('');
		////parent.$('#cmtable').load("assets/ajax/contract-manager-pedit.php?load=true&ct=<?php echo rand(9,33); ?>");
		parent.$('.cmtable').show();
	}
	function move_back_acc(ccmid){
		parent.$('#cmaccdetails').html('');
		parent.$('#cmresponse').show();
		parent.$('#cmdetails').show();
		$("#ctacclist").load('assets/ajax/contract-manager-pedit.php?cmacclist=details&cccmid='+ccmid);
		//parent.$('#cmresponse').html('');
		//parent.$('#cmresponse').load('assets/ajax/contract-manager-pedit.php?action=details&ct=<?php rand(2,99); ?>&cmid='+ccmid);
		parent.$('#ctacclist').show();
	<?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){ ?>
		parent.$('#cmdetails .cmtable').show();
	<?php } ?>
	}
	</script>
	<?php


}elseif(isset($_GET['cmid']) and $_GET['action'] == "details"){
	if(isset($_GET['cmid']) and $_GET['cmid'] != "" and $_GET['cmid'] > 0)
		$cmid=$mysqli->real_escape_string(@trim($_GET['cmid']));
	else
		die('Wrong parameters provided');

	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) and isset($_SESSION['user_id'])){
?>
		<style>
		#cmacctable1{
		border-spacing:5px !important;
		border-collapse:unset !important;
		}
		</style>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
				</div>
			</article>
		</div>

		<!-- row -->
		<div class="row siterow">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--1" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Supplier Contract </h2>

					</header>
					<div class="row" style="margin:1px !important;">
					<?php
					$address=array();
				if ($stmt = $mysqli->prepare('SELECT DISTINCT cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.STATUS,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,sg.service_group,cm.Notes,c.company_name,cm.s3_foldername FROM
					contracts cm
					JOIN user u
					JOIN company c
					JOIN service_group sg
				WHERE
					cm.ClientID = c.company_id
					'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' AND c.company_id = u.company_id
					AND u.user_id = "'.$user_one.'"':'').'
					AND cm.ContractID="'.$cmid.'"
					AND sg.service_group_id = cm.Commodity
					LIMIT 1')) {

				/*if ($stmt = $mysqli->prepare('SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,sg.service_group,cm.Notes,v.vendor_name,c.company_name,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN service_group sg WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$cmid.'" and sg.service_group_id=cm.Commodity LIMIT 1')) {*/

	//('SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,v.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.id and c.id=u.company_id and  u.id="'.$user_one.'" and cm.ContractID="'.$cmid.'" LIMIT 1')) {

					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($cmd_ContractID,$cmd_Country,$cmd_State,$cmd_ClientID,$cmd_VendorID,$cmd_SupplierID,$cmd_AdvisorID,$cmd_MasterID,$cmd_Status,$cmd_InitiatedDate,$cmd_StartDate,$cmd_EndDate,$cmd_Product,$cmd_Commodity,$cmd_service_group,$cmd_Notes,$cmd_company_name,$cmd_s3_foldername);
						$stmt->fetch();
							?>
								<table id="cmacctable" class="table table-bordered table-striped" style="clear: both">
									<tbody>
										<tr>
											<th colspan="3">Contract Number: <?php echo $cmd_ContractID;?></th>
										</tr>
										<tr>
											<td style="width:33%;"><b>Client:</b> <?php echo $cmd_company_name;?></td>
											<td style="width:33%;"><b>VendorID:</b> <?php echo $cmd_VendorID;?></td>
											<td style="width:34%;"><b>AdvisorID:</b> <?php echo $cmd_AdvisorID;?></td>
										</tr>
										<tr>
											<td style="width:33%;"><b>State:</b> <?php echo $cmd_State;?></td>
											<td style="width:33%;"><b>Country:</b> <?php echo $cmd_Country;?></td>
											<td style="width:34%;"><b>MasterID:</b> <?php echo $cmd_MasterID;?></td>
										</tr>
										<tr>
											<td style="width:33%;"><b>Status:</b> <?php echo $cmd_Status;?></td>
											<td style="width:33%;"><b>Initiated Date:</b> <?php echo $cmd_InitiatedDate;?></td>
											<td style="width:34%;"><b>Start Date:</b> <?php echo $cmd_StartDate;?></td>
										</tr>
										<tr>
											<td style="width:33%;"><b>End Date:</b> <?php echo $cmd_EndDate;?></td>
											<td style="width:33%;"><b>Product:</b> <?php echo $cmd_Product;?></td>
											<td style="width:34%;"><b>Commodity:</b> <?php echo $cmd_service_group;?></td>
										</tr>
										<tr>
											<td style="width:33%;"> </td>
											<td style="width:33%;"><b>Notes:</b> <?php echo $cmd_Notes;?></td>
											<td style="width:34%;"></td>
										</tr>
									</tbody>
								</table>
							<?php	//$address=$site_name.",".$service_address1.",".$service_address2.",".$service_address3.",".$city.",".$state.",".$country.",".$postal_code;
						}else die('Nothing to show!');
							//die('Wrong parameters provided');
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();
				}//else
					//die('Error Occured! Please try after sometime.');
					?>
					</div>
				</div>
			</article>
		</div>
		<section id="widget-grid" class="cmtable">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<!-- Widget ID (each widget will need unique ID)-->
					<div align="right" style="padding-bottom:10px;">
					</div>
					<div id="cmtable">
	<?php
	$user_one=$_SESSION["user_id"];
		if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
			$sql = "SELECT distinct cacc.ContractAcctID,ct.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c JOIN contracts ct WHERE ct.ContractID=cacc.ContractID and ct.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."'";
	//SELECT distinct cacc.ContractAcctID,cacc.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c WHERE  cacc.ClientID=c.id and c.id=u.company_id and u.id='.$user_one."'";

		}else
			$sql = "SELECT distinct cacc.ContractAcctID,ct.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c JOIN contracts ct WHERE ct.ContractID=cacc.ContractID and ct.ClientID=c.company_id and c.company_id=u.company_id";
	//"SELECT distinct cacc.ContractAcctID,cacc.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c WHERE  cacc.ClientID=c.id and c.id=u.company_id";

		?>
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="assets/plugins/datatables_ar/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/datatables_ar/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
		<style>
		#cacc_datatable_fixed_column_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.5% auto !important;
		}
		#cacc_datatable_fixed_column_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#cacc_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
		</style>
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Contract Accounts </h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<table id="cacc_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<!--<tr id="multiselect">
											<th class="hasinput">
												<select id="selectCompany" name="selectCompany" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectDivision" name="selectDivision[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectState" name="selectState[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectCity" name="selectCity[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
											</th>
											<th class="hasinput">
											</th>
											<th class="hasinput">
												<select id="selectStatus" name="selectStatus[]" multiple="multiple"></select>
											</th>
										</tr>-->
										<tr>
										<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Client ID" />
											</th>
										<?php } ?>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Company Name" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Contract Account ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Account ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Meter ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Charge ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Charge Type" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Price" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Price Type ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter UOM" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Frequency ID" />
											</th>
											<?php if(1==2){?><th></th><?php } ?>
										</tr>
										<tr>
										<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
											<th data-hide="phone,tablet">Client ID </th>
										<?php } ?>
											<th data-hide="phone,tablet">Company Name </th>
											<th data-hide="phone,tablet">Contract Account ID </th>
											<th data-hide="phone,tablet">Account ID </th>
											<th data-hide="phone,tablet">Meter ID </th>
											<th data-hide="phone,tablet">Charge ID </th>
											<th data-hide="phone,tablet">Charge Type </th>
											<th data-hide="phone,tablet">Price </th>
											<th data-hide="phone,tablet">Price Type ID </th>
											<th data-hide="phone,tablet">UOM </th>
											<th data-hide="phone,tablet">Frequency ID </th>
											<?php if(1==2){?><th data-hide="phone,tablet">Action</th><?php } ?>
										</tr>
									</thead>
									<tbody>
		<?php
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($cacc_ContractAcctID,$cacc_ClientID,$cacc_ContractID,$cacc_AccountID,$cacc_MeterID,$cacc_ChargeID,$cacc_ChargeType,$cacc_Price,$cacc_PriceTypeID,$cacc_UOM,$cacc_FrequencyID,$cc_company_name);
					while($stmt->fetch()) {
					?>
						<tr>
						<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ClientID; ?></td>
						<?php } ?>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cc_company_name; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ContractAcctID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_AccountID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_MeterID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ChargeID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ChargeType; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_Price; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_PriceTypeID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_UOM; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_FrequencyID; ?></a></td>
								<?php if(1==2){?><td><?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><button onclick="loadcmmenu(<?php echo $cm_ContractID; ?>)" title="View/Edit Contract Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletecm(<?php echo $cm_ContractID; ?>)" title="Delete Contract" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?><?php if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){?><button onclick="loadcmmenu(<?php echo $cm_ContractID; ?>)" title="View Contract Details" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></button><?php } ?></td><?php } ?>
							</tr>
					<?php
					}
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
		?>
									</tbody>
								</table>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

			</div>
				</article>
			</div>
			<!-- end row -->

		</section>
		<!-- end widget grid -->
	<?php if($cmd_s3_foldername != "" or ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){ ?>
		<section id="widget-grid" class="cms3display">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Attached Documents: Supplier Contract </h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
								<?php } ?>
								<div id="cms3display"></div>
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<div class="dropzone dz-clickable" id="ctt-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
									<?php require_once("../js/plugin/dropzone4.0/dropzone.js.php"); ?>
									<!--<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>-->
								<?php } ?>
								<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
								<!--<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>-->
								<script href="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js"></script>
								<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
								<script type="text/javascript">
								$(document).ready(function(){
									$('#cms3display').html('');
									$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $cmd_ContractID; ?>');
									<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
											Dropzone.autoDiscover = false;
											var myDropzone = new Dropzone("div#ctt-fileupload", {
												paramName: "cts3filesupload",
												addRemoveLinks: false,
												url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>&contractid=<?php echo $cmd_ContractID; ?>",
												maxFiles:10,
												uploadMultiple: true,
												parallelUploads:10,
												timeout: 300000,
												maxFilesize: 3000,
												//autoProcessQueue: false,
												init: function() {
													myDropz = this;
													myDropz.on("successmultiple", function(file, result) {
														if (result != false)
														{
															var results = JSON.parse(result);
															if(results.error == "")
															{
																//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
																$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $cmd_ContractID; ?>');
															}else if(results.error == 5)
															{
																Swal.fire("Error in request.","Please try again later.", "warning");
															}else{
																Swal.fire("Error in request.","Please try again later.", "warning");
															}
														}else{
															Swal.fire("","Error in request. Please try again later.", "warning");
														}
													});
													myDropz.on("complete", function(file) {
													   myDropz.removeAllFiles(true);
													});
													myDropz.on("uploadprogress", function(file, progress, bytesSent) {
														if (file.previewElement) {
																var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
																progressElement.style.width = progress + "%";
																file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
														}
													});
												}
											});
									<?php } ?>

								});
								</script>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

			</div>
				</article>
			<!-- end row -->

		</section>
		<!-- end widget grid -->
	<?php } ?>
		<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
		<script type="text/javascript">

			/* DO NOT REMOVE : GLOBAL FUNCTIONS!
			 *
			 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
			 *
			 * // activate tooltips
			 * $("[rel=tooltip]").tooltip();
			 *
			 * // activate popovers
			 * $("[rel=popover]").popover();
			 *
			 * // activate popovers with hover states
			 * $("[rel=popover-hover]").popover({ trigger: "hover" });
			 *
			 * // activate inline charts
			 * runAllCharts();
			 *
			 * // setup widgets
			 * setup_widgets_desktop();
			 *
			 * // run form elements
			 * runAllForms();
			 *
			 ********************************
			 *
			 * pageSetUp() is needed whenever you load a page.
			 * It initializes and checks for all basic elements of the page
			 * and makes rendering easier.
			 *
			 */

			pageSetUp();

			/*
			 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
			 * eg alert("my home function");
			 *
			 * var pagefunction = function() {
			 *   ...
			 * }
			 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
			 *
			 */

			// PAGE RELATED SCRIPTS

			// pagefunction
			var pagefunction = function() {
				//console.log("cleared");

				/* // DOM Position key index //

					l - Length changing (dropdown)
					f - Filtering input (search)
					t - The Table! (datatable)
					i - Information (records)
					p - Pagination (paging)
					r - pRocessing
					< and > - div elements
					<"#id" and > - div with an id
					<"class" and > - div with a class
					<"#id.class" and > - div with an id and class

					Also see: http://legacy.datatables.net/usage/features
				*/

				/* BASIC ;*/
					var responsiveHelper_dt_basic = undefined;
					var responsiveHelper_datatable_fixed_column = undefined;
					var responsiveHelper_datatable_col_reorder = undefined;
					var responsiveHelper_datatable_tabletools = undefined;

					var breakpointDefinition = {
						tablet : 1024,
						phone : 480
					};

				/* COLUMN FILTER  */
					var otable = $("#cacc_datatable_fixed_column").DataTable( {
						"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
						"pageLength": 12,
						"retrieve": true,
						"scrollCollapse": true,
						"searching": true,
						"paging": true,
						"dom": 'Blfrtip',
						"buttons": [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							{
								'extend': 'pdfHtml5',
								'title' : 'Vervantis_PDF',
								'messageTop': 'Vervantis PDF Export'
							},
							//'pdfHtml5'
							{
								'extend': 'print',
								//'title' : 'Vervantis',
								'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
							},
							{
								'text': 'Columns',
								'extend': 'colvis'
							}
						],
						"autoWidth" : true
					});

				// custom toolbar
				$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

				// Apply the filter
				$("#cacc_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

					otable
						.column( $(this).parent().index()+':visible' )
						.search( this.value )
						.draw();

				});
			};

			function multifilter(nthis,fieldname,otable)
			{
					var selectedoptions = [];
					$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
						selectedoptions.push($(this).val());
					});
					otable
					 .column( $(nthis).parent().index()+':visible' )
					 .search("^" + selectedoptions.join("|") + "$", true, false, true)
					 .draw();
			}

			function multilist(indexno)
			{
				var items=[], options=[];
				$('#cm_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
				   items.push( $(this).text() );
				});
				var items = $.unique( items );
				$.each( items, function(i, item){
					options.push('<option value="' + item + '">' + item + '</option>');
				})
				return options;
			}


			// load related plugins
			loadScript("assets/plugins/datatables_ar/1.10.19/js/jquery.dataTables.min.js", function(){
				loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
					loadScript("assets/plugins/datatables_ar/jszip/3.1.3/jszip.min.js", function(){
					loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/pdfmake.min.js", function(){
					loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/vfs_fonts.js", function(){
					loadScript("assets/plugins/datatables_ar/buttons/1.4.2/js/buttons.print.js", function(){
						loadScript("assets/plugins/datatables_ar/buttons/1.0.3/js/buttons.colVis.js", function(){
						loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
					});
					});
					});
					});
					});
				});
			});
		</script>










	<?php
	}elseif(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		$disabled="";
	?>
		<style>
		#cmacctable1{
		border-spacing:5px !important;
		border-collapse:unset !important;
		}
		.center{text-align:center;}
		.center button{margin:5px;}
		#wid-id--99 label{width:90%;float:left;}
		#wid-id--99 #cm-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
		#wid-id--99 .dropzone .dz-preview .dz-details .dz-size,#wid-id--99 .dropzone-previews .dz-preview .dz-details .dz-size {
			bottom: -1px !important;
			left: 29px !important;
		}
		#wid-id--99 .cmcomment{width:90%;float:left;}
		#wid-id--99 .showversion-link{float:left;margin-left: 3px;}
		#wid-id--99 #logsshow{width:100%;
			height: 269px;
			overflow: auto;}
		#wid-id--99 .nopadds{padding:0 !important;}
		.blankline{height:0 !important;}
		</style>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
				</div>
			</article>
		</div>

		<!-- row -->
		<div class="row siterow">
			<?php
				$address=array();
				/*
				if ($stmt = $mysqli->prepare('SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,v.vendor_name,c.company_id,cm.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$cmid.'" LIMIT 1')) {
				*/
				/*
				echo 'SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,e.Utility,c.company_id,cm.s3_foldername FROM contracts cm JOIN enrollment e JOIN user u JOIN company c WHERE e.ID=cm.VendorID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$cmid.'" LIMIT 1';
				*/
				if ($stmt = $mysqli->prepare('SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,e.Utility,c.company_id,cm.s3_foldername FROM contracts cm JOIN user u JOIN company c LEFT JOIN enrollment e ON e.ID=cm.VendorID WHERE cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$cmid.'" LIMIT 1')) {

	//('SELECT distinct cm.ContractID,cm.Country,cm.State,cm.ClientID,cm.VendorID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,cm.Commodity,cm.Notes,v.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.id and c.id=u.company_id and  u.id="'.$user_one.'" and cm.ContractID="'.$cmid.'" LIMIT 1')) {

					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($cmd_ContractID,$cmd_Country,$cmd_State,$cmd_ClientID,$cmd_VendorID,$cmd_SupplierID,$cmd_AdvisorID,$cmd_MasterID,$cmd_Status,$cmd_InitiatedDate,$cmd_StartDate,$cmd_EndDate,$cmd_Product,$cmd_Commodity,$cmd_Notes,$cmd_vendor_name,$cmd_company_id,$cmd_s3_foldername);
						$stmt->fetch();
							?>
						<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--99" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Edit Supplier Contract: <?php echo $cmd_ContractID; ?> </h2>

					</header>
					<div class="row" style="margin:1px !important;">
						<form id="cmedit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>

								<div class="row">
									<section class="col col-6">Status
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select placeholder="Status" class="cmselectautosave" saveme="Status">
											<option value="">&nbsp;&nbsp;Select Status</option>
											<option value='Active' <?php if($cmd_Status=="Active"){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Active</option>
											<option value='Inactive' <?php if($cmd_Status=="Inactive"){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Inactive</option>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Status",$disabled); ?>
									</section>

									<section class="col col-6">MasterID
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $cmd_MasterID; ?>" class="cminputautosave" saveme="cmd_MasterID">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"MasterID",$disabled); ?>
									</section>


								</div>
								<div class="row">
									<section class="col col-6">Client<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select placeholder="Client" class="cmselectautosave" saveme="ClientID">
											<option value="">&nbsp;&nbsp;Select Client</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__id,$__companyname);
													while($stmt->fetch()){
														echo "<option value='".$__id."' ".($cmd_company_id == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__companyname."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"ClientID",$disabled); ?>
									</section>

									<section class="col col-6">Initiated Date
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" class="cminputautosave datepicker" saveme="Initiated Date" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y",strtotime($cmd_InitiatedDate)); ?>">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Initiated Date",$disabled); ?>
									</section>





								</div>

								<div class="row">
									<section class="col col-6">AdvisorID<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select placeholder="AdvisorID" class="cmselectautosave" saveme="AdvisorID">
											<option value="">&nbsp;&nbsp;Select AdvisorID</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT user_id,firstname,lastname,title FROM `user` where usergroups_id in (1,2) and user_id !=30 order by firstname')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($u__id,$u__firstname,$u__lastname,$u__title);
													while($stmt->fetch()){
														echo "<option value='".$u__id."' ".($cmd_AdvisorID == $u__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$u__firstname." ".$u__lastname." (".$u__title.")"."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"AdvisorID",$disabled); ?>
									</section>

									<section class="col col-6">Start Date
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" class="cminputautosave datepicker" saveme="Start Date" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y",strtotime($cmd_StartDate)); ?>">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Start Date",$disabled); ?>
									</section>



								</div>

								<div class="row">
									<section class="col col-6">Country<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select id="addcmcountryedit" placeholder="Country" class="cmselectautosave" saveme="Country">
											<option value="">&nbsp;&nbsp;Select Country</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT sortname,name FROM world.countries where sortname IN ("US","CA")')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($w_sortname,$w_name);
													while($stmt->fetch()){
														echo "<option value='".$w_sortname."' ".($cmd_Country == $w_sortname?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$w_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Country",$disabled); ?>
									</section>

									<section class="col col-6">End Date
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" class="cminputautosave datepicker" saveme="End Date" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y",strtotime($cmd_EndDate)); ?>">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"End Date",$disabled); ?>
									</section>

								</div>



								<div class="row">

									<section class="col col-6">State<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select">
										<select id="addcmstateedit" placeholder="State" class="cmselectautosave" saveme="State">
											<option value="">&nbsp;&nbsp;Select State</option>
										<?php
										   //if ($stmt = $mysqli->prepare('SELECT abbreviation,name FROM world.`states` where abbreviation is not null and country="'.$cmd_Country.'"')){
										   if ($stmt = $mysqli->prepare('SELECT abbreviation,name,country FROM world.`states` where abbreviation is not null and  country IN ("CA","US") ')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($s__abbr,$s_name,$s_country);
													while($stmt->fetch()){
														echo "<option data-country='".$s_country."' value='".$s__abbr."' ".($cmd_State == $s__abbr?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$s_name." (".$s__abbr.")"."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"State",$disabled); ?>
									</section>

									<section class="col col-6">Product
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $cmd_Product; ?>" class="cminputautosave" saveme="Product">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Product",$disabled); ?>
									</section>

								</div>



								<div class="row">
									<section class="col col-6">Vendor
										<p class="blankline">&nbsp;</p>
										<label class="select">
										<select id="addcmvendoredit" placeholder="Vendor" class="cmselectautosave" saveme="VendorID" data-selected="<?php echo $cmd_VendorID?>">
											<option value="">&nbsp;&nbsp;Select Vendor</option>
										<?php
										   //if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT ID,Utility,State FROM enrollment order by Utility')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name,$__state);
													while($stmt->fetch()){
														//echo "<option value='".$__vid."' ".($cmd_VendorID == $__vid?"SELECTED='SELECTED'":'')." data-state='".$__state."'>&nbsp;&nbsp;".$__vendor_name."</option>";
														echo "<option  data-state='".$__state."'>".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"VendorID",$disabled); ?>
									</section>

									<section class="col col-6">Commodity
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select placeholder="Commodity" class="cmselectautosave" saveme="Commodity">
											<option value="">&nbsp;&nbsp;Select Commodity</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT service_group_id,service_group FROM service_group')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($sg__sgid,$sg_name);
													while($stmt->fetch()){
														echo "<option value='".$sg__sgid."' ".($cmd_Commodity == $sg__sgid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$sg_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Commodity",$disabled); ?>
									</section>

								</div>

								<div class="row">

									<section class="col col-6">Supplier<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $cmd_SupplierID; ?>" class="cminputautosave" saveme="SupplierID">
										</label>

										<!--
										<label class="select">
										<select placeholder="Supplier" class="cmselectautosave" saveme="SupplierID">
											<option value="">&nbsp;&nbsp;Select Supplier</option>
										<?php
										/*
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name);
													while($stmt->fetch()){
														echo "<option value='".$__vid."' ".($cmd_SupplierID == $__vid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										*/
										?>
										</select>
										</label>
										-->
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"SupplierID",$disabled); ?>
									</section>

									<section class="col col-6">Notes
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $cmd_Notes; ?>" class="cminputautosave" saveme="Notes">
											<input type="hidden" value="<?php echo $cmd_ContractID; ?>" id="cmid">
										</label>
										<?php echo checkversionavailability($mysqli,"contracts",$cmd_ContractID,"Notes",$disabled); ?>
									</section>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</article>
	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<?php require_once("../js/plugin/dropzone4.0/dropzone.js.php"); ?>
	<!--<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>-->
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">

	// edit form
	var state_sel_edit = $('#addcmstateedit');
	var vendor_sel_edit = $('#addcmvendoredit');
	var vendor_options_edit = vendor_sel_edit.find('option');

	var country_sel_edit = $('#addcmcountryedit');
	var state_options_edit = state_sel_edit.find('option');

$(document).ready(function() {

	// for country
	$('#addcmcountryedit').on('change', function() {
		var target = this.value;
		state_sel_edit.empty().append(
			state_options_edit.filter(function(){
			  return $(this).attr('data-country') === target;
			})
		);
	});

	// edit form
	$('#addcmstateedit').on('change', function() {
		var targetedit = this.value;
		vendor_sel_edit.empty().append(
			vendor_options_edit.filter(function(){
			  return $(this).attr('data-state') === targetedit;
			})
		);
	});

	// on window load trigger change and set select
	state_sel_edit.trigger('change');
	////vendor_sel_edit.val(vendor_sel_edit.attr('data-selected')).attr("selected", "selected");
	// for country
	country_sel_edit.trigger('change');
	//alert(state_sel_edit.attr('data-selected'));
	//alert( $('#addcmstateedit').find(":selected").val() );
	state_sel_edit.val('<?php echo $cmd_State;?>');

	$('#addcmvendoredit').select2({
			/*placeholder: 'Select a month'*/
		tags: true
	});

	var IsExists = false;
	vendor_options_edit.each(function(){
		if (this.value == '<?php echo $cmd_VendorID;?>') {
			IsExists = true;
			return IsExists;
		}
	});

	if (IsExists === false) {
		var newOption = new Option('<?php echo $cmd_VendorID;?>', '<?php echo $cmd_VendorID;?>', false, false);
		vendor_sel_edit.append(newOption).trigger('change'); //university is id of select2
		vendor_sel_edit.val('<?php echo $cmd_VendorID;?>').trigger('change');
	}

	//vendor_sel_edit.val('t123').trigger('change');


  $('.cminputautosave').blur(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  $('.cmselectautosave').change(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  function autosave(savename,saveval){

	var formData = new FormData();
	formData.append('maauto', $("#cmid").val());
	formData.append('masavename', savename);
	formData.append('mavalue', saveval);

	$.ajax({
		type: 'post',
		url: 'assets/includes/contractmanager.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
					$("a#"+savename+"").removeClass("nodis");
					$("#logshow").load("assets/ajax/showlogs.php?pkey=<?php echo $cmd_ContractID; ?>&tname=contracts&load=true&disb=<?php echo @trim($disabled); ?>&tuid=cmdetails&tuurl=<?php echo urlencode('assets/ajax/contract-manager-pedit.php?action=details&cmid='.$cmd_ContractID); ?>&ct=<?php echo rand(0,100); ?>");

				}else if(results.error == 5)
				{
					Swal.fire("Mandatory:","Plese fill all required fields", "warning");
				}else{
					Swal.fire("Error in request.","Please try again later.", "warning");
				}
			}else{
				Swal.fire("","Error in request. Please try again later.", "warning");
			}
		}
	  });

  }
});
</script>
							<?php
					}else
						die('Wrong parameters provided');
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();
				}//else
					//die('Error Occured! Please try after sometime.');
					?>
		</div>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<button class="btn-primary pull-right" align="right" style="height: 30px !important;width: auto !important;" onclick="addnewacc(<?php echo $cmid; ?>)">Add New Contract Account</button>
				</div>
			</article>
		</div>
	<div id="ctacclist" style="margin:0 !important;padding:0 !important;"></div>

	<?php if($cmd_s3_foldername != "" or ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)){ ?>
		<section id="widget-grid" class="cms3display">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Attached Documents: Supplier Contract </h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
								<?php } ?>
								<div id="cms3display"></div>
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<div class="dropzone dz-clickable" id="ct-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
									<?php require_once("../js/plugin/dropzone4.0/dropzone.js.php"); ?>
								<?php } ?>
								<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
								<!--<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>-->
								<script href="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js"></script>
								<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
								<script type="text/javascript">
								$(document).ready(function(){
									$('#cms3display').html('');
									$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $cmd_ContractID; ?>');
									<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
											Dropzone.autoDiscover = false;
											var myDropzone = new Dropzone("div#ct-fileupload", {
												paramName: "cts3filesupload",
												addRemoveLinks: false,
												url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>&contractid=<?php echo $cmd_ContractID; ?>",
												maxFiles:10,
												uploadMultiple: true,
												parallelUploads:10,
												timeout: 300000,
												maxFilesize: 3000,
												//autoProcessQueue: false,
												init: function() {
													myDropz = this;
													myDropz.on("successmultiple", function(file, result) {
														if (result != false)
														{
															var results = JSON.parse(result);
															if(results.error == "")
															{
																//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
																$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $cmd_ContractID; ?>');
															}else if(results.error == 5)
															{
																Swal.fire("Error in request.","Please try again later.", "warning");
															}else{
																Swal.fire("Error in request.","Please try again later.", "warning");
															}
														}else{
															Swal.fire("","Error in request. Please try again later.", "warning");
														}
													});
													myDropz.on("complete", function(file) {
													   myDropz.removeAllFiles(true);
													});
													myDropz.on("uploadprogress", function(file, progress, bytesSent) {
														if (file.previewElement) {
																var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
																progressElement.style.width = progress + "%";
																file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
														}
													});
												}
											});
									<?php } ?>

								});
								</script>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

			</div>
				</article>
			<!-- end row -->

		</section>
		<!-- end widget grid -->
	<?php } ?>
		<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
		<script type="text/javascript">

			/* DO NOT REMOVE : GLOBAL FUNCTIONS!
			 *
			 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
			 *
			 * // activate tooltips
			 * $("[rel=tooltip]").tooltip();
			 *
			 * // activate popovers
			 * $("[rel=popover]").popover();
			 *
			 * // activate popovers with hover states
			 * $("[rel=popover-hover]").popover({ trigger: "hover" });
			 *
			 * // activate inline charts
			 * runAllCharts();
			 *
			 * // setup widgets
			 * setup_widgets_desktop();
			 *
			 * // run form elements
			 * runAllForms();
			 *
			 ********************************
			 *
			 * pageSetUp() is needed whenever you load a page.
			 * It initializes and checks for all basic elements of the page
			 * and makes rendering easier.
			 *
			 */

			pageSetUp();

			/*
			 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
			 * eg alert("my home function");
			 *
			 * var pagefunction = function() {
			 *   ...
			 * }
			 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
			 *
			 */

			// PAGE RELATED SCRIPTS

			// pagefunction
			var pagefunction = function() {
				//console.log("cleared");

				/* // DOM Position key index //

					l - Length changing (dropdown)
					f - Filtering input (search)
					t - The Table! (datatable)
					i - Information (records)
					p - Pagination (paging)
					r - pRocessing
					< and > - div elements
					<"#id" and > - div with an id
					<"class" and > - div with a class
					<"#id.class" and > - div with an id and class

					Also see: http://legacy.datatables.net/usage/features
				*/

				/* BASIC ;*/
					$("#ctacclist").load('assets/ajax/contract-manager-pedit.php?cmacclist=details&cccmid=<?php echo $cmd_ContractID; ?>');



					var responsiveHelper_dt_basic = undefined;
					var responsiveHelper_datatable_fixed_column = undefined;
					var responsiveHelper_datatable_col_reorder = undefined;
					var responsiveHelper_datatable_tabletools = undefined;

					var breakpointDefinition = {
						tablet : 1024,
						phone : 480
					};

				/* COLUMN FILTER  */
					var otable = $("#cacc_datatable_fixed_column").DataTable( {
						"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
						"pageLength": 12,
						"retrieve": true,
						"scrollCollapse": true,
						"searching": true,
						"paging": true,
						"dom": 'Blfrtip',
						"buttons": [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							{
								'extend': 'pdfHtml5',
								'title' : 'Vervantis_PDF',
								'messageTop': 'Vervantis PDF Export'
							},
							//'pdfHtml5'
							{
								'extend': 'print',
								//'title' : 'Vervantis',
								'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
							},
							{
								'text': 'Columns',
								'extend': 'colvis'
							}
						],
						"autoWidth" : true
					});

				// custom toolbar
				$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

				// Apply the filter
				$("#cacc_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

					otable
						.column( $(this).parent().index()+':visible' )
						.search( this.value )
						.draw();

				});
			};

			function multifilter(nthis,fieldname,otable)
			{
					var selectedoptions = [];
					$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
						selectedoptions.push($(this).val());
					});
					otable
					 .column( $(nthis).parent().index()+':visible' )
					 .search("^" + selectedoptions.join("|") + "$", true, false, true)
					 .draw();
			}

			function multilist(indexno)
			{
				var items=[], options=[];
				$('#cm_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
				   items.push( $(this).text() );
				});
				var items = $.unique( items );
				$.each( items, function(i, item){
					options.push('<option value="' + item + '">' + item + '</option>');
				})
				return options;
			}

			loadScript("assets/plugins/datatables_ar/1.10.19/js/jquery.dataTables.min.js", function(){
				loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
					loadScript("assets/plugins/datatables_ar/jszip/3.1.3/jszip.min.js", function(){
					loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/pdfmake.min.js", function(){
					loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/vfs_fonts.js", function(){
					loadScript("assets/plugins/datatables_ar/buttons/1.4.2/js/buttons.print.js", function(){
						loadScript("assets/plugins/datatables_ar/buttons/1.0.3/js/buttons.colVis.js", function(){
						loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
					});
					});
					});
					});
					});
				});
			});
		<?php
			if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		} ?>
		</script>

<?php echo showlogs($cmd_ContractID,'contracts','cmdetails','assets/ajax/contract-manager-pedit.php?&ct='.rand(9,88).'&action=details&cmid='.$cmd_ContractID,$disabled); ?>









<?php
	}else echo false;
}elseif(isset($_GET['action']) and $_GET['action']='add' and !isset($_GET['ctid']) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){
?>
	<style>
	#cmadd-dialog-message #cm-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#cmadd-dialog-message .dropzone .dz-preview .dz-details .dz-size,#cmadd-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#cmadd-dialog-message .col-12{width:100% !important;}
	#cmadd-dialog-message .required{vertical-align: bottom;
    line-height: 1;
    color: red;}
	#cmadd-dialog-message footer{text-align:center;}
	#cmadd-dialog-message footer button{float:none !important;}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row siterow">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark ma" id="wid-id--1111" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Add New Supplier Contract</h2>

				</header>
				<div class="row">
		<div id="cmadd-dialog-message" title="Add Supplier Contract">
						<form id="cmadd-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">
							<fieldset>

								<div class="row">
									<section class="col col-6">Status
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select id="addcmstatus" placeholder="Status" class="cmselectautosave">
											<option value="">&nbsp;&nbsp;Select Status</option>
											<option value='Active'>&nbsp;&nbsp;Active</option>							<option value='Inactive'>&nbsp;&nbsp;Inactive</option>
										</select>
										</label>
									</section>
									<section class="col col-6">MasterID
										<label class="input">
											<input type="number" value="" class="cminputautosave" id="addcmmid">
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-6">Client<span class="required">*</span>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select id="addcmcid" placeholder="Client" class="cmselectautosave">
											<option value="">&nbsp;&nbsp;Select Client</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__id,$__companyname);
													while($stmt->fetch()){
														echo "<option value='".$__id."'>&nbsp;&nbsp;".$__companyname."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

									<section class="col col-6">Initiated Date
										<label class="input">
											<input type="text" value="" class="cminputautosave datepicker" id="addcminidate" data-dateformat='mm/dd/yy'>
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-6">Advisor<span class="required">*</span>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select id="addcmadid" placeholder="Advisor" class="cmselectautosave" saveme="AdvisorID">
											<option value="">&nbsp;&nbsp;Select Advisor</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT user_id,firstname,lastname,title FROM `user` where usergroups_id in (1,2) and user_id !=30 order by firstname')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($u__id,$u__firstname,$u__lastname,$u__title);
													while($stmt->fetch()){
														echo "<option value='".$u__id."'>&nbsp;&nbsp;".$u__firstname." ".$u__lastname." (".$u__title.")"."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

									<section class="col col-6">Start Date
										<label class="input">
											<input type="text" value="" class="cminputautosave datepicker" id="addcmsdate" data-dateformat='mm/dd/yy'>
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-6">Country<span class="required">*</span>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select id="addcmcountry" placeholder="Country" class="cmselectautosave">
											<option value="">&nbsp;&nbsp;Select Country</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT sortname,name FROM world.countries where sortname IN ("US","CA")')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($w_sortname,$w_name);
													while($stmt->fetch()){
														echo "<option value='".$w_sortname."'>&nbsp;&nbsp;".$w_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

									<section class="col col-6">End Date
										<label class="input">
											<input type="text" value="" class="cminputautosave datepicker" id="addcmedate" data-dateformat='mm/dd/yy'>
										</label>
									</section>

								</div>

								<div class="row">

									<section class="col col-6">State<span class="required">*</span>
										<label class="select">
										<select id="addcmstate" placeholder="State" class="cmselectautosave">
											<option value="">&nbsp;&nbsp;Select State</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT abbreviation,name,country FROM world.`states` where abbreviation is not null and  country IN ("CA","US") ')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($s__abbr,$s_name,$s_country);
													while($stmt->fetch()){
														echo "<option value='".$s__abbr."' data-country='".$s_country."'>&nbsp;&nbsp;".$s_name." (".$s__abbr.")"."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

									<section class="col col-6">Product
										<label class="input">
											<input type="text" value="" class="cminputautosave" id="addcmproduct">
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-6">Vendor
										<label class="select">
										<select id="addcmvendor" placeholder="Vendor" class="cmselectautosave" >
											<option value="">&nbsp;&nbsp;Select Vendor</option>
										<?php
										   //if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
											if ($stmt = $mysqli->prepare('SELECT DISTINCT ID,Utility,State FROM enrollment order by Utility')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name,$__state);
													while($stmt->fetch()){
														//echo "<option value='".$__vid."' data-state='".$__state."'>&nbsp;&nbsp;".$__vendor_name."</option>";
														echo "<option data-state='".$__state."'>".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

									<section class="col col-6">Commodity
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select placeholder="Commodity" class="cmselectautosave" id="addcmcommodity">
											<option value="">&nbsp;&nbsp;Select Commodity</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT service_group_id,service_group FROM service_group')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($sg__sgid,$sg_name);
													while($stmt->fetch()){
														echo "<option value='".$sg__sgid."'>&nbsp;&nbsp;".$sg_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-6">Supplier<span class="required">*</span>

										<label class="input">
											<input type="text" value="" class="cminputautosave" id="addcmsid">
										</label>
										<!--
										<label class="select">
										<select id="addcmsid" placeholder="Supplier" class="cmselectautosave">
											<option value="">&nbsp;&nbsp;Select Supplier</option>
										<?php
										/*
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name);
													while($stmt->fetch()){
														echo "<option value='".$__vid."'>&nbsp;&nbsp;".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
											*/
										?>
										</select>
										</label>
										-->

									</section>

									<section class="col col-6">Notes
										<label class="input">
											<input type="text" value="" class="cminputautosave" id="addcmnotes">
											<input type="hidden" value="new" id="action">
										</label>
									</section>

								</div>

								<div class="row">
									<section class="col col-12">Attached documents
										<div class="dropzone dz-clickable" id="cmadd-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="cmadd-submit">
									Save
								</button>
							</footer>
						</form>

	</div>

<!-- end row -->
				</div>
			</div>
		</article>
	</div>

	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<?php require_once("../js/plugin/dropzone4.0/dropzone.js.php"); ?>
	<!--<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>-->
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">

	// add form
	var state_sel = $('#addcmstate');
	var vendor_sel = $('#addcmvendor');
	var vendor_options = vendor_sel.find('option');

	var country_sel = $('#addcmcountry');
	//var vendor_sel = $('#addcmvendor');
	var state_options = state_sel.find('option');

$(document).ready(function() {

	// for country
	$('#addcmcountry').on('change', function() {
		var target = this.value;
		state_sel.empty().append(
			state_options.filter(function(){
			  return $(this).attr('data-country') === target;
			})
		);
	});

	// add from
	$('#addcmstate').on('change', function() {
	  //alert( this.value );


		//var target = state_sel.find(':selected').data('option');
		//var target = state_sel.find(':selected').val();
		var target = this.value;
		//alert();

		vendor_sel.empty().append(
			vendor_options.filter(function(){
			  //return $(this).data('option') === target;
			  //return $(this).data('state') === target;
			  return $(this).attr('data-state') === target;
			})
		);



	});

	$('#addcmvendor').select2({
			/*placeholder: 'Select a month'*/
		tags: true
	});

	//select 2 addcmvendor


	var responsegot=0;
	var currentFile = null;
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#cmadd-fileupload", {
		paramName: "cmaddfilesupload",
		addRemoveLinks: true,
		url: "assets/includes/contractmanager.inc.php",
		maxFiles:10,
		uploadMultiple: true,
		parallelUploads:10,
		timeout: 300000,
		maxFilesize: 3000,
		autoProcessQueue: false,
		init: function() {
			myDropz = this;


				$("#cmadd-submit").on("click", function(e) {
				  // Make sure that the form isn't actually being sent.
				  e.preventDefault();
				  e.stopPropagation();


					if($("#addcmcid").val() !="" && $("#addcmsid").val() !="" && $("#addcmadid").val() !="" && $("#addcmcountry").val() !="" && $("#addcmstate").val() !=""){
						if (myDropz.getQueuedFiles().length > 0)
						{
							myDropzone.on("sending", function(file, xhr, formData) {
								formData.append('ClientID', $("#addcmcid").val());
								formData.append('SupplierID', $("#addcmsid").val());
								formData.append('AdvisorID', $("#addcmadid").val());
								formData.append('MasterID', $("#addcmmid").val());
								formData.append('Country', $("#addcmcountry").val());
								formData.append('State', $("#addcmstate").val());
								formData.append('Status', $("#addcmstatus").val());
								formData.append('Initiated@Date', $("#addcminidate").val());
								formData.append('Start@Date', $("#addcmsdate").val());
								formData.append('End@Date', $("#addcmedate").val());
								formData.append('Product', $("#addcmproduct").val());
								formData.append('Commodity', $("#addcmcommodity").val());
								formData.append('VendorID', $("#addcmvendor").val());
								formData.append('Notes', $("#addcmnotes").val());
								formData.append('cmadd', 'new');
							});
							myDropz.processQueue();

							myDropz.on("successmultiple", function(file, result) {
								if (result != false)
								{
									var results = JSON.parse(result);
									if(results.error == "")
									{
										swal("","Added", "success");
										$("#cmadd-checkout-form").get(0).reset();
									}else if(results.error == 5)
									{
										swal("At least one entry mandatory in:","Client, Supplier, Advisor, Country, State", "warning");
									}else{
										swal("Error in request.","Please try again later.", "warning");
									}
								}else{
									swal("","Error in request. Please try again later.", "warning");
								}
							});
							myDropz.on("complete", function(file) {
							   myDropz.removeAllFiles(true);
							});
							myDropz.on("uploadprogress", function(file, progress, bytesSent) {
								if (file.previewElement) {
										var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
										progressElement.style.width = progress + "%";
										file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
								}
							});
							$('#cmadd-checkout-form').trigger("reset")
						} else {
								//$('#maadd-checkout-form').submit();
								var formData = new FormData();
								formData.append('ClientID', $("#addcmcid").val());
								formData.append('SupplierID', $("#addcmsid").val());
								formData.append('AdvisorID', $("#addcmadid").val());
								formData.append('MasterID', $("#addcmmid").val());
								formData.append('Country', $("#addcmcountry").val());
								formData.append('State', $("#addcmstate").val());
								formData.append('Status', $("#addcmstatus").val());
								formData.append('Initiated@Date', $("#addcminidate").val());
								formData.append('Start@Date', $("#addcmsdate").val());
								formData.append('End@Date', $("#addcmedate").val());
								formData.append('Product', $("#addcmproduct").val());
								formData.append('Commodity', $("#addcmcommodity").val());
								formData.append('VendorID', $("#addcmvendor").val());
								formData.append('Notes', $("#addcmnotes").val());
								formData.append('cmadd', 'new');

								$.ajax({
									type: 'post',
									url: 'assets/includes/contractmanager.inc.php',
									data: formData,
									processData: false,
									contentType: false,
									success: function (result) {
										if (result != false)
										{
											var results = JSON.parse(result);
											if(results.error == "")
											{
												swal("","Added", "success");
												$("#cmadd-checkout-form").get(0).reset();
											}else if(results.error == 5)
											{
												swal("At least one entry mandatory in:","Client, Supplier, Advisor, Country, State", "warning");
											}else{
												swal("Error in request.","Please try again later.", "warning");
											}
										}else{
											swal("","Error in request. Please try again later.", "warning");
										}
									}
								  });
						}
					}else{
						swal("At least one entry mandatory in:","Client, Supplier, Advisor, Country, State", "warning");
					}
				});

		}
	});


});

		/* DO NOT REMOVE : GLOBAL FUNCTIONS!
		 *
		 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
		 *
		 * // activate tooltips
		 * $("[rel=tooltip]").tooltip();
		 *
		 * // activate popovers
		 * $("[rel=popover]").popover();
		 *
		 * // activate popovers with hover states
		 * $("[rel=popover-hover]").popover({ trigger: "hover" });
		 *
		 * // activate inline charts
		 * runAllCharts();
		 *
		 * // setup widgets
		 * setup_widgets_desktop();
		 *
		 * // run form elements
		 * runAllForms();
		 *
		 ********************************
		 *
		 * pageSetUp() is needed whenever you load a page.
		 * It initializes and checks for all basic elements of the page
		 * and makes rendering easier.
		 *
		 */

		pageSetUp();

		/*
		 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
		 * eg alert("my home function");
		 *
		 * var pagefunction = function() {
		 *   ...
		 * }
		 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
		 *
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {

		};
	</script>
<?php
}elseif(isset($_GET['action']) and $_GET['action']='addcmacc' and isset($_GET['ctid']) and $_GET['ctid'] !=0 and !empty($_GET['ctid']) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){
	$cmid=$mysqli->real_escape_string(@trim($_GET['ctid']));
?>
	<style>
	#cmaccadd-dialog-message #cmaccadd-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#cmaccadd-dialog-message .dropzone .dz-preview .dz-details .dz-size,#cmaccadd-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#cmaccadd-dialog-message .col-12{width:100% !important;}
	#cmaccadd-dialog-message .required{vertical-align: bottom;
    line-height: 1;
    color: red;}
	#cmaccadd-dialog-message footer{text-align:center;}
	#cmaccadd-dialog-message footer button{float:none !important;}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<b><img id="mvbk" onclick="move_back_acc(<?php echo $cmid; ?>)" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row siterow">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark ma" id="wid-id--66" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Add Contract Account For Supplier Contract: <?php echo $cmid; ?></h2>

				</header>
				<div class="row">
		<div id="cmaccadd-dialog-message">
						<form id="cmaccadd-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">
							<fieldset>
								<div class="row">
									<section class="col col-6">Company Name<span class="required">*</span>
										<label class="input"> <i class="icon-append fa fa-user"></i>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT c.company_name FROM company c,contracts ct where c.company_id=ct.ClientID and ct.ContractID="'.$cmid.'" LIMIT 1')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__cname);
													$stmt->fetch();
													echo '<input type="Text" value="'.$__cname.'" required DISABLED>';
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</label>
									</section>
									<section class="col col-6">AccountID
										<label class="input">
											<input type="number" value="" required class="cminputautosave" id="addcmaccid">
										</label>
									</section>


								</div>

								<div class="row">
									<section class="col col-6">MeterID
										<label class="input">
											<input type="number" value="" required class="cminputautosave" id="addcmaccmtid">
										</label>
									</section>
									<section class="col col-6">ChargeID
										<label class="input">
											<input type="number" value="" class="cminputautosave" id="addcmaccchgid">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">ChargeType
										<label class="input">
											<input type="number" value="" class="cminputautosave" id="addcmaccchgtype">
										</label>
									</section>
									<section class="col col-6">Price
										<label class="input">
											<input type="number" placeholder="0.00" min="0" value="0" step="0.01" value="" class="cminputautosave" id="addcmaccprice">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">PriceTypeID
										<label class="input">
											<input type="number" value="" class="cminputautosave" id="addcmaccpricetypeid">
										</label>
									</section>
									<section class="col col-6">UOM
										<label class="input">
											<input type="text" value="" class="cminputautosave" id="addcmaccuom">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">FrequencyID
										<label class="input">
											<input type="text" value="" class="cminputautosave" id="addcmaccfqid">
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>

								<div class="row">
									<section class="col col-12">Attached documents
										<div class="dropzone dz-clickable" id="cmaccadd-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="cmaccadd-submit">
									Save
								</button>
							</footer>
						</form>
	</div>

<!-- end row -->
				</div>
			</div>
		</article>
	</div>

	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<?php require_once("../js/plugin/dropzone4.0/dropzone.js.php"); ?>
	<!--<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>-->
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});

	var responsegot=0;
	var currentFile = null;
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#cmaccadd-fileupload", {
		paramName: "cmaccaddfilesupload",
		addRemoveLinks: true,
		url: "assets/includes/contractmanager.inc.php",
		maxFiles:10,
		uploadMultiple: true,
		parallelUploads:10,
		timeout: 300000,
		maxFilesize: 3000,
		autoProcessQueue: false,
		init: function() {
			myDropz = this;


				$("#cmaccadd-submit").on("click", function(e) {
				  // Make sure that the form isn't actually being sent.
				  e.preventDefault();
				  e.stopPropagation();


					if($("#addcmaccid").val() !="" && $("#addcmaccmtid").val() !=""){
						if (myDropz.getQueuedFiles().length > 0)
						{
							myDropzone.on("sending", function(file, xhr, formData) {
								formData.append('AccountID', $("#addcmaccid").val());
								formData.append('MeterID', $("#addcmaccmtid").val());
								formData.append('ChargeID', $("#addcmaccchgid").val());
								formData.append('Charge@Type', $("#addcmaccchgtype").val());
								formData.append('Price', $("#addcmaccprice").val());
								formData.append('PriceTypeID', $("#addcmaccpricetypeid").val());
								formData.append('UOM', $("#addcmaccuom").val());
								formData.append('FrequencyID', $("#addcmaccfqid").val());
								formData.append('ContractID', '<?php echo $cmid; ?>');
								formData.append('cmaccadd', 'new');
							});
							myDropz.processQueue();

							myDropz.on("successmultiple", function(file, result) {
								if (result != false)
								{
									var results = JSON.parse(result);
									if(results.error == "")
									{
										swal("","Added", "success");
										$("#cmaccadd-checkout-form").get(0).reset();
									}else if(results.error == 5)
									{
										swal("At least one entry mandatory in:","ContractID, AccountID, MeterID", "warning");
									}else{
										swal("Error in request.","Please try again later.", "warning");
									}
								}else{
									swal("","Error in request. Please try again later.", "warning");
								}
							});
							myDropz.on("complete", function(file) {
							   myDropz.removeAllFiles(true);
							});
							myDropz.on("uploadprogress", function(file, progress, bytesSent) {
								if (file.previewElement) {
										var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
										progressElement.style.width = progress + "%";
										file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
								}
							});
							$('#cmaccadd-checkout-form').trigger("reset")
						} else {
								//$('#maadd-checkout-form').submit();
								var formData = new FormData();
								formData.append('AccountID', $("#addcmaccid").val());
								formData.append('MeterID', $("#addcmaccmtid").val());
								formData.append('ChargeID', $("#addcmaccchgid").val());
								formData.append('Charge@Type', $("#addcmaccchgtype").val());
								formData.append('Price', $("#addcmaccprice").val());
								formData.append('PriceTypeID', $("#addcmaccpricetypeid").val());
								formData.append('UOM', $("#addcmaccuom").val());
								formData.append('FrequencyID', $("#addcmaccfqid").val());
								formData.append('ContractID', '<?php echo $cmid; ?>');
								formData.append('cmaccadd', 'new');

								$.ajax({
									type: 'post',
									url: 'assets/includes/contractmanager.inc.php',
									data: formData,
									processData: false,
									contentType: false,
									success: function (result) {
										if (result != false)
										{
											var results = JSON.parse(result);
											if(results.error == "")
											{
												swal("","Added", "success");
												$("#cmaccadd-checkout-form").get(0).reset();
											}else if(results.error == 5)
											{
												swal("At least one entry mandatory in:","ContractID, AccountID, MeterID", "warning");
											}else{
												swal("Error in request.","Please try again later.", "warning");
											}
										}else{
											swal("","Error in request. Please try again later.", "warning");
										}
									}
								  });
						}
					}else{
						swal("At least one entry mandatory in:","ContractID, AccountID, MeterID", "warning");
					}
				});

		}
	});


});

		/* DO NOT REMOVE : GLOBAL FUNCTIONS!
		 *
		 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
		 *
		 * // activate tooltips
		 * $("[rel=tooltip]").tooltip();
		 *
		 * // activate popovers
		 * $("[rel=popover]").popover();
		 *
		 * // activate popovers with hover states
		 * $("[rel=popover-hover]").popover({ trigger: "hover" });
		 *
		 * // activate inline charts
		 * runAllCharts();
		 *
		 * // setup widgets
		 * setup_widgets_desktop();
		 *
		 * // run form elements
		 * runAllForms();
		 *
		 ********************************
		 *
		 * pageSetUp() is needed whenever you load a page.
		 * It initializes and checks for all basic elements of the page
		 * and makes rendering easier.
		 *
		 */

		pageSetUp();

		/*
		 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
		 * eg alert("my home function");
		 *
		 * var pagefunction = function() {
		 *   ...
		 * }
		 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
		 *
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {

		};
	</script>
<?php
}elseif(isset($_GET["cccmid"]) and isset($_GET["cmacclist"])){
	if(isset($_GET['cccmid']) and $_GET['cccmid'] != "" and $_GET['cccmid'] > 0)
		$cmd_ContractID=$mysqli->real_escape_string(@trim($_GET['cccmid']));
	else
		die('Wrong parameters provided');
?>
		<section id="widget-grid" class="cmtable">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<!-- Widget ID (each widget will need unique ID)-->
					<div align="right" style="padding-bottom:10px;">
					</div>
					<div id="cmtable">
	<?php
	$user_one=$_SESSION["user_id"];
		if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
			$sql = "SELECT distinct cacc.ContractAcctID,ct.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c JOIN contracts ct WHERE cacc.ContractID=ct.ContractID and ct.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and cacc.ContractID='".$cmd_ContractID."'";
	//SELECT distinct cacc.ContractAcctID,cacc.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c WHERE  cacc.ClientID=c.id and c.id=u.company_id and u.id='.$user_one."'";

		}else
			$sql = "SELECT distinct cacc.ContractAcctID,ct.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c JOIN contracts ct WHERE cacc.ContractID=ct.ContractID and ct.ClientID=c.company_id and c.company_id=u.company_id and cacc.ContractID='".$cmd_ContractID."'";
	//"SELECT distinct cacc.ContractAcctID,cacc.ClientID,cacc.ContractID,cacc.AccountID,cacc.MeterID,cacc.ChargeID,cacc.`Charge Type`,cacc.Price,cacc.PriceTypeID,cacc.UOM,cacc.FrequencyID,c.company_name FROM contract_accounts cacc JOIN user u JOIN company c WHERE  cacc.ClientID=c.id and c.id=u.company_id";

		?>
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="assets/plugins/datatables_ar/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/datatables_ar/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
		<style>
		#cacc_datatable_fixed_column_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.5% auto !important;
		}
		#cacc_datatable_fixed_column_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#cacc_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
		</style>
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Contract Accounts </h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<table id="cacc_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<!--<tr id="multiselect">
											<th class="hasinput">
												<select id="selectCompany" name="selectCompany" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectDivision" name="selectDivision[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectState" name="selectState[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
												<select id="selectCity" name="selectCity[]" multiple="multiple"></select>
											</th>
											<th class="hasinput">
											</th>
											<th class="hasinput">
											</th>
											<th class="hasinput">
												<select id="selectStatus" name="selectStatus[]" multiple="multiple"></select>
											</th>
										</tr>-->
										<tr>
										<?php if(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and 1==2){?>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Client ID" />
											</th>
										<?php } ?>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Company Name" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Contract Account ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Account ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Meter ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Charge ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Charge Type" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Price" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Price Type ID" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter UOM" />
											</th>
											<th class="hasinput">
												<input type="text" class="form-control" placeholder="Filter Frequency ID" />
											</th>
											<?php if(1==2){?><th></th><?php } ?>
										</tr>
										<tr>
										<?php if(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and 1==2){?>
											<th data-hide="phone,tablet">Client ID </th>
										<?php } ?>
											<th data-hide="phone,tablet">Company Name </th>
											<th data-hide="phone,tablet">Contract Account ID </th>
											<th data-hide="phone,tablet">Account ID </th>
											<th data-hide="phone,tablet">Meter ID </th>
											<th data-hide="phone,tablet">Charge ID </th>
											<th data-hide="phone,tablet">Charge Type </th>
											<th data-hide="phone,tablet">Price </th>
											<th data-hide="phone,tablet">Price Type ID </th>
											<th data-hide="phone,tablet">UOM </th>
											<th data-hide="phone,tablet">Frequency ID </th>
											<?php if(1==2){?><th data-hide="phone,tablet">Action</th><?php } ?>
										</tr>
									</thead>
									<tbody>
		<?php
			if ($stmt = $mysqli->prepare($sql)) {
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($cacc_ContractAcctID,$cacc_ClientID,$cacc_ContractID,$cacc_AccountID,$cacc_MeterID,$cacc_ChargeID,$cacc_ChargeType,$cacc_Price,$cacc_PriceTypeID,$cacc_UOM,$cacc_FrequencyID,$cc_company_name);
					while($stmt->fetch()) {
					?>
						<tr>
						<?php if(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and 1==2){?>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ClientID; ?></a></td>
						<?php } ?>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cc_company_name; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ContractAcctID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_AccountID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_MeterID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ChargeID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_ChargeType; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_Price; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_PriceTypeID; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_UOM; ?></a></td>
								<td><a href="javascript:void(0);" onclick="cmaccload_details(<?php echo $cacc_ContractAcctID.','.$cacc_ContractID; ?>)"><?php echo $cacc_FrequencyID; ?></a></td>
								<?php if(1==2){?><td><?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
								<button onclick="loadcmmenu(<?php echo $cm_ContractID; ?>)" title="View/Edit Contract Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletecm(<?php echo $cm_ContractID; ?>)" title="Delete Contract" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?><?php if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){?><button onclick="loadcmmenu(<?php echo $cm_ContractID; ?>)" title="View Contract Details" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></button><?php } ?></td><?php } ?>
							</tr>
					<?php
					}
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
		?>
									</tbody>
								</table>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

			</div>
				</article>
			</div>
			<!-- end row -->

		</section>
		<!-- end widget grid -->

<?php
}
?>
