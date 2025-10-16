<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$data_arr=array();
	if ($data_obj = $mysqli->prepare("SELECT period,amount,invoice FROM (SELECT period,amount,invoice FROM widget_invoice_process where company_id = '".$_SESSION['company_id']."' order by period desc limit 36) as ip order by period")) {

        $data_obj->execute();
        $data_obj->store_result();
        if ($data_obj->num_rows > 0) {
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
				
				if ($count <= 12) {
					$month_tds .= '<th>'.date("M Y",strtotime($period)).'</th>';
					$exp_tds .= '<td>'.$invoice.'</td>';
					$rec_tds .= '<td>'.$amount.'</td>';
					$persent_tds .= '<td>'.round( ( ($amount/$invoice) * 100 ) , 2).'</td>';
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

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<!--<h5 class="text-center"><strong>Calendar Period</strong></h5>-->

<!-- ------------------------Pie Chart------------------------------- -->
<!-- ------------------------Pie Chart------------------------------- -->
<!-- ------------------------Pie Chart------------------------------- -->

<!-- Styles -->
<style>
#piechartdiv {
  /* width: 100%;*/  
    position: absolute;
    float: left;
    height: 100px;
    top: 0;
    left: 50px;
}
.table_heading{
	font-size:14px;
	font-weight:bold;
	color:#fff;
	background-color:#4c4f53;
	padding:5px;
	padding-left:10px;
}

.ar_scroll{
	overflow-x: scroll !important;
	font-size:13px;
}
table.dataTable thead th, table.dataTable thead td{padding: 5px 5px;}
table.dataTable tbody th, table.dataTable tbody td{padding: 5px 5px;}
.dataTables_wrapper .dataTables_paginate .paginate_button{padding:0;}
.ar_scroll th{/*min-width:58px !important;*/}

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

window.addEventListener("load", function(){
	document.querySelector('g[aria-labelledby="id-210-title"]').style.display = "none";
	document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
});

$(document).ready(function() {
	/*
    $('#datatable_fixed_column3--').DataTable({
        "searching": false,
		"ordering":  false,
		"lengthChange": false,
		"scrollX": 400,
		"info": false,
		"pageLength": 3
    });
	*/
} );
</script>

<!-- HTML -->
<div id="piechartdiv---"></div>






<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/index.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/percent.js"></script>


<style>
#chartdivp {
  width: 100%;
  height: 500px;
}

#chartdivp {
	width: 100%;
    position: absolute;
    float: left;
    height: 100px;
    top: 0;
    left: 50px;
	z-index:1;
}
</style>

<div id="chartdivp"></div>

<script>
// Create root and chart
var root = am5.Root.new("chartdivp");
var chart = root.container.children.push( 
  am5percent.PieChart.new(root, {
    layout: root.verticalHorizontal
  }) 
);

// Define data
/*
var data = [{
  country: "France",
  sales: 100000
}, {
  country: "Spain",
  sales: 160000
}, {
  country: "United Kingdom",
  sales: 80000
}];
*/

var data = [ {
  "data": "Expected",
  "val": <?php echo $exp_val;?>
}, {
  "data": "Received",
  "val": <?php echo $rec_val;?>
} ];

// Create series
var series = chart.series.push(
  am5percent.PieSeries.new(root, {
    name: "Series",
    valueField: "val",
    categoryField: "data"
  })
);

series.labels.template.set("visible", false);
series.ticks.template.set("visible", false);

series.data.setAll(data);

// Add legend
/*
var legend = chart.children.push(am5.Legend.new(root, {
  centerX: am5.percent(50),
  x: am5.percent(50),
  layout: root.horizontalLayout
}));
*/

//legend.data.setAll(series.dataItems);
</script>





<!-- Styles -->
<style>
#chartdiv222 {
  width: 100%;
  height: 365px;
}
</style>

<!-- Resources -->
<!--<script src="https://cdn.amcharts.com/lib/5/index.js"></script>-->
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/xy.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/Animated.js"></script>


<!-- HTML -->
<div id="chartdiv222"></div>

<!-- Chart code -->
<script>
/**
 * ---------------------------------------
 * This demo was created using amCharts 5.
 * 
 * For more information visit:
 * https://www.amcharts.com/
 * 
 * Documentation is available at:
 * https://www.amcharts.com/docs/v5/
 * ---------------------------------------
 */


// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv222");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([am5themes_Animated.new(root)]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(
  am5xy.XYChart.new(root, {
    panX: false,
    panY: false,
    wheelX: "panX",
    wheelY: "zoomX",
    layout: root.verticalLayout
  })
);

// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
/*
chart.set(
  "scrollbarX",
  am5.Scrollbar.new(root, {
    orientation: "horizontal"
  })
);
*/

var data = [<?php echo implode(",",$data_arr);?>];

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {minGridDistance: 30});
xRenderer.labels.template.setAll({
  //rotation: -90,
  rotation: 320,
  //centerY: am5.p50,
  //centerX: am5.p100,
  centerX: am5.p100,
  paddingTop: 15
  //paddingRight: 15
});
var xAxis = chart.xAxes.push(
  am5xy.CategoryAxis.new(root, {
    categoryField: "period",
    renderer: xRenderer,
    tooltip: am5.Tooltip.new(root, {})
  })
);
xRenderer.grid.template.setAll({
  location: 1
})

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    min: 0,
    extraMax: 0.1,
    renderer: am5xy.AxisRendererY.new(root, {
      strokeOpacity: 0.1
    })
  })
);


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

var series1 = chart.series.push(
  am5xy.ColumnSeries.new(root, {
    name: "Amount",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "amount",
    categoryXField: "period",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      //labelText: "{name} in {categoryX}: {valueY} {info}"
    })
  })
);

series1.columns.template.setAll({
  tooltipY: am5.percent(10),
  templateField: "columnSettings"
});

series1.data.setAll(data);

var series2 = chart.series.push(
  am5xy.LineSeries.new(root, {
    name: "Invoices",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "invoice",
    categoryXField: "period",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      //labelText: "{name} in {categoryX}: {valueY} {info}"
    })
  })
);

series2.strokes.template.setAll({
  strokeWidth: 3,
  templateField: "strokeSettings"
});


series2.data.setAll(data);

series2.bullets.push(function() {
  return am5.Bullet.new(root, {
    sprite: am5.Circle.new(root, {
      strokeWidth: 3,
      stroke: series2.get("stroke"),
      radius: 5,
      fill: root.interfaceColors.get("background")
    })
  });
});

chart.set("cursor", am5xy.XYCursor.new(root, {}));

// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
/*
var legend = chart.children.push(
  am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50
  })
);
legend.data.setAll(chart.series.values);
*/

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);
series1.appear();

</script>






<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->
<!-- ------------------------table------------------------------- -->


<div class="widget-body no-padding">	
<div class="table_heading">Invoices Received</div>				
						<table id="datatable_fixed_column3" class="ar_scroll table table-responsive table-condensed" width="100%">
							<thead>
								<tr>
									<td></td>
									<?php echo $month_tds;?>
								</tr>
							</thead>
							<tbody>
							
								<tr>
									<td><strong>Expected</strong></td>
									<?php echo $exp_tds;?>
								</tr>
								
								<tr>
									<td><strong>Received</strong></td>
									<?php echo $rec_tds;?>
								</tr>
								
								<tr>
									<td><strong>%</strong></td>
									<?php echo $persent_tds;?>
								</tr>

							</tbody>
						</table>
</div>	
				<!--</div>-->