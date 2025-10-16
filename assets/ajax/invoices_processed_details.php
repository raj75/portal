<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

	$where = "";
	$limit = "limit 36";

	if (isset($_GET['start']) and isset($_GET['end'])) {
		$start = mysqli_real_escape_string($mysqli,$_GET['start']);
		$end = mysqli_real_escape_string($mysqli,$_GET['end']);

		//$where = "where DATE_FORMAT(period,'%Y-%m-01') >= DATE_FORMAT($start ,'%Y-%m-01')  ";
		$where = "WHERE DATE_FORMAT(period, '%Y-%m') BETWEEN '".date("Y-m",strtotime($start))."' AND '".date("Y-m",strtotime($end))."'";
		$limit = "";
	}

//echo "SELECT period,amount,invoice FROM (SELECT period,amount,invoice FROM invoice_process $where order by period desc $limit) as ip order by period";

	$data_arr=array();
	$invoice_query = "SELECT period,amount,invoice FROM
										(SELECT period,amount,invoice FROM invoice_process $where order by period desc $limit) as ip
									order by period";
	if ($data_obj = $mysqli->prepare($invoice_query))
	{

        $data_obj->execute();
        $data_obj->store_result();
        if ($data_obj->num_rows > 0) {

			$min_max_query = "SELECT min(period) min , max(period) max FROM (SELECT period,amount,invoice FROM invoice_process $where order by period desc $limit) as ip order by period";

			$min_max_obj = $mysqli->query($min_max_query);

			$min_max = $min_max_obj->fetch_array(MYSQLI_ASSOC);

			$min_year = date("Y",strtotime($min_max['min']));
			$max_year = date("Y",strtotime($min_max['max']));


			$exp_val=0;
			$rec_val=0;
			//$persent_val=0;

			$month_tds = '';
			$exp_tds = '';
			$rec_tds = '';
			$persent_tds = '';

			$data_obj->bind_result($period,$amount,$invoice);
			$count = 1;
			while($data_obj->fetch()){
				$data_arr[]='{"period": "'.date("M Y",strtotime($period)).'","amount": '.$amount.',"invoice": '.$invoice.'}';
				$exp_val = $exp_val+$invoice;
				$rec_val = $rec_val+$amount;
				$persent_val = $rec_val+$amount;

				$table_arr[date("n",strtotime($period))][date("Y",strtotime($period))] = $amount;

				@$table_total_arr[date("Y",strtotime($period))] += $amount;

				/*
				if ($count <= 12) {
					$month_tds .= '<th>'.date("M Y",strtotime($period)).'</th>';
					$exp_tds .= '<td>'.$invoice.'</td>';
					$rec_tds .= '<td>'.$amount.'</td>';
					$persent_tds .= '<td>'.round( ( ($amount/$invoice) * 100 ) , 2).'</td>';
				}
				*/
				$count++;
			}
			ksort($table_arr,1);


		}

		//------------------
		// most recent table
		//------------------

		$last_data_obj = $mysqli->prepare("SELECT period,amount,invoice FROM
										 (SELECT period,amount,invoice FROM invoice_process order by period desc limit 12) as ip
										  order by month(period)");

		$last_data_obj->execute();
        $last_data_obj->store_result();
        if ($last_data_obj->num_rows > 0) {

			/*
			$exp_val=0;
			$rec_val=0;
			*/
			//$persent_val=0;
			/*
			$last_month_tds = '';
			$last_exp_tds = '';
			$last_rec_tds = '';
			$last_persent_tds = '';
			*/

			$last_data_obj->bind_result($period,$amount,$invoice);
			$count = 1;
			$last_data_html = "";
			while($last_data_obj->fetch()){

				//$data_arr[]='{"period": "'.date("M Y",strtotime($period)).'","amount": '.$amount.',"invoice": '.$invoice.'}';
				/*
				$exp_val = $exp_val+$invoice;
				$rec_val = $rec_val+$amount;
				$persent_val = $rec_val+$amount;
				*/

				$last_data_html .= "<tr>
									<td>".$invoice."</td>
								    <td>".$amount."</td>
								    <td>".round( ( ($amount/$invoice) * 100 ) , 2)."%</td>
									</tr>";

				if (count($data_arr)>0 and ($last_data_obj->num_rows)==$count) {
				$last_data_html .= "<tr>
									<td>".$exp_val."</td>
								    <td>".$rec_val."</td>
								    <td>".round( ( ($rec_val/$exp_val) * 100 ) , 2)."%</td>
									</tr>";
				}

				$count++;
			}

		}


	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

	if(!count($data_arr)) die("No data to show!");



?>
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 400px;
  background-color: #fff;
}
</style>



<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Export
//chart.exporting.menu = new am4core.ExportMenu();

// Data for both series
var data = [<?php echo implode(",",$data_arr);?>];

/* Create axes */
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "period";
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
valueAxis.title.text = "Amount";
valueAxis.title.fontWeight =500;
//valueAxis.calculateTotals = true;
//valueAxis.min = 0;
//valueAxis.max = 100;

/*
var range = valueAxis.axisRanges.create();
range.value = 300;
range.endValue = 1100;
*/

/* Create series */
var columnSeries = chart.series.push(new am4charts.ColumnSeries());
columnSeries.name = "Amount";
columnSeries.dataFields.valueY = "amount";
columnSeries.dataFields.categoryX = "period";

columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
columnSeries.columns.template.propertyFields.stroke = "stroke";
columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
columnSeries.tooltip.label.textAlign = "middle";

var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.name = "Invoices";
lineSeries.dataFields.valueY = "invoice";
lineSeries.dataFields.categoryX = "period";

var bullet = lineSeries.bullets.push(new am4charts.Bullet());
bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
var circle = bullet.createChild(am4core.Circle);
circle.radius = 4;
circle.fill = am4core.color("#fff");
circle.strokeWidth = 3;




chart.data = data;

}); // end am4core.ready()
</script>

<!--<h4 class="text-center">Invoices Processed</h4>-->
<!-- HTML -->
<div id="chartdiv"></div>
<!--<h5 class="text-center"><strong>Calendar Period</strong></h5>-->

<!-- ------------------------Pie Chart------------------------------- -->
<!-- ------------------------Pie Chart------------------------------- -->
<!-- ------------------------Pie Chart------------------------------- -->

<!-- Styles -->
<style>
#piechartdiv {
    width: 10%;
    position: absolute;
    float: left;
    height: 130px;
    top: 140px;
    right: 50px;
}
.table_heading{
	font-size:14px;
	font-weight:bold;
	color:#fff;
	background-color:#4c4f53 !important;
	padding:5px;
	padding-left:10px;
}

.ar_scroll{
	overflow-x: scroll !important;
	font-size:13px;
	background-color: #fff;
}
/*.ar_scroll th{min-width:58px !important;}*/
table.dataTable thead th, table.dataTable thead td{padding: 5px 5px;}
table.dataTable tbody th, table.dataTable tbody td{padding: 5px 5px;}
.dataTables_wrapper .dataTables_paginate .paginate_button{padding:0;}
.ar_big{width:70%; float:left;}
.ar_small{width:30%; float:left;}
/*.big_border{border-right:2px solid #000 !important;}*/
.no-padding>.table-bordered{border-right:3px solid #ddd !important;}
.no-padding>table:first-child tr td:last-child{border-right:3px solid #ddd !important;}

</style>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("piechartdiv", am4charts.PieChart);

// Add data
chart.data = [ {
  "data": "Expected",
  "val": <?php echo $exp_val;?>
}, {
  "data": "Received",
  "val": <?php echo $rec_val;?>
} ];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "val";
pieSeries.dataFields.category = "data";
pieSeries.slices.template.stroke = am4core.color("#fff");
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.labels.template.disabled = true;

// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()

//window.addEventListener("load", function(){
	//document.querySelector('g[aria-labelledby="id-210-title"]').style.display = "none";
	////document.querySelectorAll('g[aria-labelledby^="id-"][aria-labelledby$="-title"]').style.display = "none";
	//[id^="edit-tid"][id$="-view"]
	//document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
	//document.querySelector('g[aria-labelledby^="id-"][aria-labelledby$="-title"]').style.display = "none";
//});

var gdivs = document.querySelectorAll('g[aria-labelledby^="id-"][aria-labelledby$="-title"]').forEach(function(el) {
  el.style.display = "none";
})

$(document).ready(function() {
    $('#datatable_fixed_column3').DataTable({
        "searching": false,
		"ordering":  false,
		"lengthChange": false,
		"scrollX": 400,
		"info": false,
		"pageLength": 3
    });

	//$(".ar_big").addClass("big_border");
	$(".ar_big").css( "border-right", "2px solid #000 !important" );
} );
</script>

<!-- HTML -->
<div id="piechartdiv"></div>


<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->


<div class="widget-body-- no-padding-- overflow-hidden">
<!--<div class="table_heading">Invoices Received</div>	-->

						<table id="datatable_fixed_column123" class="ar_scroll table table-bordered table-hover table-responsive ar_big">
							<thead>
								<tr class="bg-secondary text-white">
										<th colspan="<?php echo (($max_year-$min_year)+2);?>" class="text-center table_heading">Invoices Received</th>

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
										if (isset($table_arr[$mon][$i])) { echo $table_arr[$mon][$i]; }
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

						<table id="datatable_fixed_column3--" class="ar_scroll table-bordered table-hover table table-responsive ar_small">
							<thead>
								<tr>
									<th colspan="3" class="text-center table_heading">Most recent 12 months</th>
								</tr>
								<tr>
									<th>Expected</th>
									<th>Received</th>
									<th>Received %</th>
								</tr>
							</thead>
							<tbody>

								<tr>
									<?php echo $last_data_html;?>
								</tr>

							</tbody>
						</table>
</div>

				<!--</div>-->
