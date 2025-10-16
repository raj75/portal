<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(isset($_GET["load"])){

?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<style>
	#ma_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	#ma_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#ma_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
	</style>
	
	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>

	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
	
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Master Supply Agreements </h2>

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
							<table id="ma_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									
									<tr>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Client ID" />
										</th>
									<?php } ?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Master ID" />
										</th>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Vendor ID" />
										</th>
									<?php } ?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Supplier" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Status" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Start Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter End Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Version" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Reviewed By" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Notes" />
										</th>
										<?php if(1==2){?><th></th><?php } ?>
									</tr>
									<tr>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">Client ID </th>
									<?php } ?>
										<th data-hide="phone,tablet">Master ID </th>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">Vendor ID </th>
									<?php } ?>
										<th data-hide="phone,tablet">Supplier </th>
										<th data-hide="phone,tablet">Status </th>
										<th data-hide="phone,tablet">Start Date </th>
										<th data-hide="phone,tablet">End Date </th>
										<th data-hide="phone,tablet">Version </th>
										<th data-hide="phone,tablet">Reviewed By </th>
										<th data-hide="phone,tablet">Notes </th>
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

	<!-- Styles -->
<style>
#chartdivmaster {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
<script>
		loadScript("assets/js/amchart/core/core.js", function(){
			loadScript("assets/js/amchart/core/charts.js", function(){
				loadScript("assets/js/amchart/themes/frozen.js", function(){
					loadScript("assets/js/amchart/core/themes/animated.js", function(){
						//create_chart();
						//hide_chart_logo();
					});
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
			am4core.useTheme(am4themes_frozen);
			am4core.useTheme(am4themes_animated);
			// Themes end

			//var chart = am4core.create("chartdiv", am4charts.XYChart);
			chart = am4core.create("chartdivmaster", am4charts.XYChart);
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

			chart.paddingRight = 30;
			chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm";

			//var colorSet = new am4core.ColorSet();
			colorSet = new am4core.ColorSet();
			colorSet.saturation = 0.4;

			//chart.data = [<?php //echo implode(",",$chart_data_arr);?>];
			chart.data = reloadData();


			//chart.dateFormatter.dateFormat = "dd/MM/yyyy";
			chart.dateFormatter.dateFormat = "dd/MM/yyyy";

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
				//return dx;
				//return dx - 90;
				return dx - 30;
			  //}
			  //return dy;
			});


			//dateAxis.renderer.grid.template.disabled = true;

			dateAxis.renderer.tooltipLocation = 0;
			//dateAxis.dateFormatter.dateFormat = "MM-yyyy";
			//dateAxis.dateFormats.setKey("day", "dd");
			dateAxis.dateFormats.setKey("month", "MMM yyyy");
			//dateAxis.min = new Date('<?php //echo $chart_min?>').getTime();
			//dateAxis.max = new Date('<?php //echo $chart_max?>').getTime();
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

		function loadNewData() {
			// var chartData = reloadData();
			chart.data = "";
			chart.data = reloadData();
		}
		
		function reloadData() {
			
			var newData = [];
			
			$("#ma_datatable_fixed_column tbody tr").each(function(index, tr){

					var colorInd = index;
					var ar_contract_id = $(this).find(".ar_contract_id").text();
					var ar_start_date = $(this).find(".ar_start_date").text();
					var ar_end_date = $(this).find(".ar_end_date").text();
					
					item = {}
					item ["category"] = "Contract #"+ar_contract_id;
					item ["start"] = ar_start_date;
					item ["end"] = ar_end_date;
					item ["color"] = colorSet.getIndex(colorInd).brighten(0);

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
		
		function set_macontract_height() {
				// Set cell size in pixels
				var cellSize = 15;
				chart.events.on("datavalidated", function(ev) {
				  
				  // Get objects of interest
				  var chart = ev.target;
				  //console.log(chart);
				  var categoryAxis = chart.yAxes.getIndex(0);
				  
				  // Calculate how we need to adjust chart height
				  var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

				  // get current chart height
				  var targetHeight = chart.pixelHeight + adjustHeight;
				  
				  //alert(targetHeight);

				  // Set it on chart's container
				  chart.svgContainer.htmlElement.style.height = targetHeight + "px";
				});
		}	

</script>

<!-- HTML -->
<div id="chartdivmaster"></div>

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
				var otable = $("#ma_datatable_fixed_column").DataTable( {
					
					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'deferRender': true,
					'ajax': {
					   'url':'assets/ajax/master-agreements-ajax.php'
					},
					
					"drawCallback" : function(settings) {
						$(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},
					
					'columns': [
					 
					 <?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
					 { data: 'ClientID' }, 
					 <?php } ?>
					 { data: 'MasterID' }, 
					 <?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
					 { data: 'VendorID' },
					 <?php } ?>
					 { data: 'vendor_name' },
					 { data: 'Status' },
					 { data: 'Start_Date' },
					 { data: 'End_Date' },
					 { data: 'Version' },
					 { data: 'Reviewed_By' },
					 { data: 'Notes' },
					 										
										
					]
					,
					
					
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
			/*var otable = $('#datatable_fixed_column').DataTable({
				// "iDisplayLength": 5,
				//"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
				//"bFilter": false,
				//"bInfo": false,
				//"bLengthChange": false,
				//"bAutoWidth": false,
				//"bPaginate": false,
				//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'CT>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"oTableTools": {
					 "aButtons": [
					 "copy",
					 "csv",
					 "xls",
						{
							"sExtends": "pdf",
							"sTitle": "Vervantis_PDF",
							"sPdfMessage": "Vervantis PDF Export",
							"sPdfSize": "letter"
						},
						{
							"sExtends": "print",
							"sMessage": "Generated by Vervantis <i>(press Esc to close)</i>"
						}
					 ],
					"sSwfPath": "assets/js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
				},
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}

			});*/

			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
			// Apply the filter
			$("#ma_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
				otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

				// start by amir
				loadNewData();
				// end by amir
			});
			
			///------------------
			otable.on( 'draw', function () {
				//alert('draw');
				// your code here
				create_chart();
				hide_chart_logo();
				set_macontract_height();	
				//loadNewData();
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

		/*loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
					loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
						loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
					});
				});
			});
		});*/
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

	function loadmamenu(msid) {
		$("#ma-widget-grid").fadeOut( "slow" );
		parent.$('#masteragreementdetails').load('assets/ajax/master-agreements-pedit.php?ct=<?php echo rand(2,99); ?>&action=edit&msid='+msid);
	}
	function move_back(){
		parent.$('#matable').load("assets/ajax/master-agreements.php?load=true");
		parent.$('#ma-widget-grid').show();
		parent.$('#masteragreementdetails').html('');
	}
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
	function deletema(said) {
		$('#maresponse').html('');
		var r = confirm("Are you sure want to delete it!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/master-agreements.inc.php',
				data: {said:said,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							alert("Success");
							parent.$("#satable").html('');
							parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
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
	</script>
	<?php


}else{
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
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Energy Procurement
			<span>>
				Master Supply Agreements
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="ma-widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;">
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
				<button class="btn-primary" align="right" id="new-master-agreements" style="height: 30px !important;width: auto !important;">Add New Master Supply Agreement</button>
<?php } ?>
			</div>
			<div id="matable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->

<section id="masteragreementdetails"></section>
<?php
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<div id="maresponse"></div>
<div id="maopdialog"></div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
	$("#new-master-agreements").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$("#ma-widget-grid").fadeOut( "slow" );
		$('#masteragreementdetails').load('assets/ajax/master-agreements-pedit.php?ct=<?php echo rand(2,99); ?>&action=add');
	});
<?php }
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
	$('#matable').load("assets/ajax/master-agreements.php?load=true&ct=<?php echo rand(2,99); ?>");
<?php } ?>
});
</script>
<?php
}
?>
