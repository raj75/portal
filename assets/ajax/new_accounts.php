<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//$sql = "SELECT * FROM simplecolumn";
//$result = mysqli_query($con, $sql);

//$mysqli->prepare("select * from aamir.invoice_process");
//$mysqli->   points to "vervantis" database

	$account_arr=array();
	if ($account_obj = $mysqli->prepare("SELECT period,accounts from widget_new_accounts where company_id = '".$_SESSION['company_id']."' order by period")) {

//("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.id=".$user_one." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)"))

        $account_obj->execute();
        $account_obj->store_result();
        if ($account_obj->num_rows > 0) {
			$account_obj->bind_result($period,$accounts);
			while($account_obj->fetch()){
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				//$be_value=($be_value==""?0:$be_value);
				$account_arr[]='{"period": "'.date("M Y",strtotime($period)).'","accounts": '.$accounts.'}';
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//SELECT count(id),date(`EST Date`) FROM `exceptions` where `Customer ID`=10 and `Customer #`=315 group BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)
	if(!count($account_arr)) die("No data to show!");

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
/*
#chartdiv {
	width: 100%;
	height: 100%;
}
*/
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
var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 25 });
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

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.3,
  renderer: am5xy.AxisRendererY.new(root, {
    strokeOpacity: 1
  })
}));


// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "New Accounts",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "accounts",
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
var data = [<?php echo implode(",",$account_arr);?>];

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