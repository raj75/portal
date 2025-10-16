<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$data_arr=array();
	if ($data_obj = $mysqli->prepare("SELECT period,invoice from widget_balance_forward where company_id = '".$_SESSION['company_id']."' order by period")) {

        $data_obj->execute();
        $data_obj->store_result();
        if ($data_obj->num_rows > 0) {
			$data_obj->bind_result($period,$invoice);
			while($data_obj->fetch()){
				$data_arr[]='{"period": "'.date("M Y",strtotime($period)).'","invoice": '.$invoice.'}';
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
  height: 500px;
}

body,tspan{
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;
  font-size:13px  !important;
  color: #333  !important;
}
html, body {
  width: 100%;
  height: 100%;
  margin: 0px;
}
.amcharts-chart-div > a {
    display: none !important;
}
</style>

<style>
#chartdiv111 {
  width: 100%;
  height: 100%;
}      </style>

<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/index.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/xy.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/Animated.js"></script>

<div id="chartdiv111"></div>

<script>
try {
              // Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv111");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
    panX: "none",
    panY: "none",
    wheelX: "none",
    wheelY: "none",
    pinchZoom: false
}));

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
cursor.lineY.set("visible", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
xRenderer.labels.template.setAll({
  //rotation: -90,
  rotation: 320,
  //centerY: am5.p50,
  //centerX: am5.p100,
  centerX: am5.p100,
  paddingTop: 15
  //paddingRight: 15
});

xRenderer.grid.template.setAll({
  location: 0
})

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.3,
  //categoryField: "country",
  categoryField: "period",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));

xAxis.children.push(am5.Label.new(root, {
    text: 'Calendar Period',
    textAlign: 'center',
    x: am5.p50,
    fontWeight: 'bold'
  }));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.3,
  text: 'yAxis title',
  renderer: am5xy.AxisRendererY.new(root, {
    strokeOpacity: 1
  })
}));

yAxis.children.unshift(am5.Label.new(root, {
    text: 'Number of Invoices',
    textAlign: 'center',
    y: am5.p50,
    rotation: -90,
    fontWeight: 'bold'
  }));

// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Number of Invoices",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "invoice",
  sequencedInterpolation: true,
  //categoryXField: "country",
  categoryXField: "period",
  tooltip: am5.Tooltip.new(root, {
    labelText: "{valueY}"
  })
}));


//series.columns.template.setAll({ strokeOpacity: 0, strokeWidth: 2 });
/*
series.columns.template.adapters.add("fill", function(fill, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

series.columns.template.adapters.add("stroke", function(stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});
*/

// Set data
var data = [<?php echo implode(",",$data_arr);?>];

xAxis.data.setAll(data);
series.data.setAll(data);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);            }
            catch( e ) {
              console.log( e );
            }
          </script>
		  