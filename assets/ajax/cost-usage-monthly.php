<?php
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,36)==false) die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];


	$where = "where 1=1 and service = 'electric'";
	$limit = "limit 36";

	/*
	if (isset($_GET['start']) and isset($_GET['end'])) {
		$start = mysqli_real_escape_string($mysqli,$_GET['start']);
		$end = mysqli_real_escape_string($mysqli,$_GET['end']);

		//$where = "where DATE_FORMAT(period,'%Y-%m-01') >= DATE_FORMAT($start ,'%Y-%m-01')  ";
		$where = "WHERE DATE_FORMAT(period, '%Y-%m') BETWEEN '".date("Y-m",strtotime($start))."' AND '".date("Y-m",strtotime($end))."'";
		$limit = "";
	}
	*/

//echo "SELECT period,amount,invoice FROM (SELECT period,amount,invoice FROM invoice_process $where order by period desc $limit) as ip order by period";

	$data_arr=array();
	$table_arr=array();

	$data_query = "SELECT `usage`,`cost`,`date` FROM (SELECT `usage`,`cost`,`date` from cost_usage $where order by date desc $limit) as cu order by date ";
	//echo $data_query;
	//die();

	if ($data_obj = $mysqli->prepare($data_query))
	{
        $data_obj->execute();
        $data_obj->store_result();
        if ($data_obj->num_rows > 0) {

			$min_max_query = "SELECT min(`date`) min , max(`date`) max FROM (SELECT `usage`,`cost`,`date` from cost_usage $where order by date desc $limit) as cu order by date";

			//echo $min_max_query;
			//die();

			$min_max_obj = $mysqli->query($min_max_query);

			$min_max = $min_max_obj->fetch_array(MYSQLI_ASSOC);

			$min_year = date("Y",strtotime($min_max['min']));
			$max_year = date("Y",strtotime($min_max['max']));

			$data_obj->bind_result($usage,$cost,$date);
			$count = 1;
			while($data_obj->fetch()){
				$data_arr[]='{"date": "'.date("M Y",strtotime($date)).'","usage": "'.$usage.'m","cost": "'.$cost.'"}';

				/* usage */
				$table_arr[date("n",strtotime($date))][date("Y",strtotime($date))] = $usage;

				@$table_total_arr[date("Y",strtotime($date))] += $usage;

				/* cost */
				$table_arr_cost[date("n",strtotime($date))][date("Y",strtotime($date))] = $cost;

				@$table_total_arr_cost[date("Y",strtotime($date))] += $cost;

				$count++;
			}
			//ksort($table_arr,1);
		}

	}else{
		//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		//exit();
	}

	if(!count($data_arr)) die("<br><br>No data to show!");

?>


<div class="row fixed">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Data Management <span>> Cost and Usage</span>
		</h1>
	</div>
</div>

<div class="row fixed">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form id="filter_form" action="">

			<div class="form-group">
		  <select class="form-control" id="peroid">
			<option>Last 12 months</option>
		  </select>
		  &nbsp; &nbsp; &nbsp;
		  <select class="form-control" id="service">
			<option>Electric</option>
		  </select>

		  </div>

		<!--<button type="submit" class="margin_left filter_invoice">Filter</button> -->
		</form>
		<!--<button onclick="navigateurl('assets/ajax/cost-usage-monthly.php','Cost and Usage')" id="reset_data">Reset</button>-->
	</div>
</div>
<br>


<script type="text/javascript">

loadScript("https://cdn.amcharts.com/lib/4/core.js", function(){
	loadScript("https://cdn.amcharts.com/lib/4/charts.js", function(){
		loadScript("https://cdn.amcharts.com/lib/4/themes/animated.js", function(){
			loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
				//$('#invoice_data').load('assets/ajax/invoices_processed_details.php');
				create_chart();
				hide_chart_logo();
			});
		});
	});
});

</script>


<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 400px;
  background-color: #fff;
}

#ui-datepicker-div{top:110px !important;}
.margin_left{margin-left:5px;}
.ui-datepicker-calendar {
    display: none;
 }
.ui-datepicker-prev , .ui-datepicker-next {display:none;}
#filter_form{padding-left:45px; float:left; width:auto;}
#reset_invoice{float:left; margin-left:8px;}

/*.ar_scroll th{min-width:58px !important;}*/
table.dataTable thead th, table.dataTable thead td{padding: 5px 5px;}
table.dataTable tbody th, table.dataTable tbody td{padding: 5px 5px;}
.dataTables_wrapper .dataTables_paginate .paginate_button{padding:0;}
.ar_big{width:70%; float:left;}
.ar_small{width:30%; float:left;}
/*.big_border{border-right:2px solid #000 !important;}*/
.no-padding>.table-bordered{border-right:3px solid #ddd !important;}
.no-padding>table:first-child tr td:last-child{border-right:3px solid #ddd !important;}

#filter_form .form-group select {
    display: inline-block;
    width: auto;
    /*vertical-align: middle;*/
}

.ar_scroll{
	overflow-x: scroll !important;
	font-size:13px;
	background-color: #fff;
}

</style>



<!-- Chart code -->
<script>
function create_chart() {
//am4core.ready(function() {

// Themes begin
//am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Export
//chart.exporting.menu = new am4core.ExportMenu();

// Data for both series
var data = [<?php echo implode(",",$data_arr);?>];

/* Create axes */
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "date";
categoryAxis.renderer.minGridDistance = 30;
categoryAxis.renderer.labels.template.rotation = 320;
//categoryAxis.renderer.labels.template.rotation = -90;

categoryAxis.renderer.labels.template.adapter.add("dx", function(dx, target) {
  //if (target.dataItem && target.dataItem.index & 2 == 2) {
    return dx - 30;
  //}
  //return dy;
});

/* Create value axis */

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
//valueAxis.title.text = "Amount";
//valueAxis.title.fontWeight =500;

//valueAxis.calculateTotals = true;
//valueAxis.min = 0;
//valueAxis.max = 100;

// Second value axis

var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
//valueAxis2.title.text = "Units sold";
valueAxis2.renderer.opposite = true;


/*
var range = valueAxis.axisRanges.create();
range.value = 300;
range.endValue = 1100;
*/

/* Create series */
var columnSeries = chart.series.push(new am4charts.ColumnSeries());
columnSeries.name = "Usage";
columnSeries.dataFields.valueY = "usage";
columnSeries.dataFields.categoryX = "date";

columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
columnSeries.columns.template.propertyFields.stroke = "stroke";
columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
columnSeries.tooltip.label.textAlign = "middle";

var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.name = "Cost";
lineSeries.dataFields.valueY = "cost";
lineSeries.dataFields.categoryX = "date";
lineSeries.yAxis = valueAxis2;

var bullet = lineSeries.bullets.push(new am4charts.Bullet());
bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
var circle = bullet.createChild(am4core.Circle);
circle.radius = 4;
circle.fill = am4core.color("#fff");
circle.strokeWidth = 3;

// Add legend
chart.legend = new am4charts.Legend();

chart.data = data;

//}); // end am4core.ready()

} // end of function

function hide_chart_logo() {
	var gdivs = document.querySelectorAll('g[aria-labelledby^="id-"][aria-labelledby$="-title"]').forEach(function(el) {
	  el.style.display = "none";
	});
}

</script>

<!--<h4 class="text-center">Invoices Processed</h4>-->
<!-- HTML -->
<h4 class="text-center"><strong>Montreal, QC (500 William Street)</strong></h4>
<br>
<div id="chartdiv"></div>
<!--<h5 class="text-center"><strong>Calendar Period</strong></h5>-->

<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->


<div class="widget-body-- no-padding-- overflow-hidden">
<!--<div class="table_heading">Invoices Received</div>	-->

						<table id="datatable_fixed_column123" class="ar_scroll table table-bordered table-hover table-responsive ar_big">
							<thead>
								<tr class="bg-secondary text-white">
										<th colspan="<?php echo (($max_year-$min_year)+2);?>" class="text-center table_heading">Volume (mmbtu)</th>

								</tr>
								<tr>
										<th>Months</th>
										<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
											<th><?php echo $i; ?></th>
										<?php } ?>
								</tr>
							</thead>
							<?php //foreach($table_arr as $month=>$year) {
									for($mon = 1; $mon <= 12; $mon++) {

//										$table_arr as $month=>$year) {
							?>
									<tr>
									<?php echo "<td>".date("F", mktime(0, 0, 0, $mon, 10))."</td>"; // month name?>
									<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
										<td>
										<?php //if (isset($year[$i])) { echo $year[$i]; }
										if (isset($table_arr[$mon][$i])) { echo $table_arr[$mon][$i]."m"; }
										?>
										</td>
							<?php   } ?>
									</tr>
								  <?php }?>

							<?php //echo $table_html; ?>
							<tr>
										<td>Total</td>
										<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
											<td><?php echo $table_total_arr[$i]; ?></td>
										<?php } ?>
								</tr>

						</table>

						<!-- ------------------------cost table------------------------------- -->
						<!-- ------------------------cost table------------------------------- -->

						<table id="datatable_fixed_column12" class="ar_scroll table table-bordered table-hover table-responsive ar_small">
							<thead>
								<tr class="bg-secondary text-white">
										<th colspan="<?php echo (($max_year-$min_year)+2);?>" class="text-center table_heading">Cost</th>

								</tr>
								<tr>
										<th>Months</th>
										<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
											<th><?php echo $i; ?></th>
										<?php } ?>
								</tr>
							</thead>
							<?php //foreach($table_arr as $month=>$year) {
									for($mon = 1; $mon <= 12; $mon++) {

//										$table_arr as $month=>$year) {
							?>
									<tr>
									<?php echo "<td>".date("F", mktime(0, 0, 0, $mon, 10))."</td>"; // month name?>
									<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
										<td>
										<?php //if (isset($year[$i])) { echo $year[$i]; }
										if (isset($table_arr_cost[$mon][$i])) { echo "$ ".$table_arr_cost[$mon][$i]; }
										?>
										</td>
							<?php   } ?>
									</tr>
								  <?php }?>

							<?php //echo $table_html; ?>
							<tr>
										<td>Total</td>
										<?php for($i = $min_year; $i <= $max_year; $i++) { ?>
											<td><?php echo $table_total_arr_cost[$i]; ?></td>
										<?php } ?>
								</tr>

						</table>


</div>
