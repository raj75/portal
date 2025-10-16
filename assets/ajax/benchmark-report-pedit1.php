<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

//if($user_one != 1) die("Under Construction!"); 
?>
<style>
.colorccc{color:#666;font-size: 14px;}
</style>
<?php
if(isset($_GET["section1"])){
	$sec1_arr=array();
   if ($stmt_section1 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` WHERE month(date)=month(now()) Order by date desc')) { 
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($sec1_id,$sec1_date,$sec1_category,$sec1_unitcost,$sec1_usagecost,$sec1_weathercost,$sec1_energyunit,$sec1_totalchange);
			while($stmt_section1->fetch()){
				$sec1_arr[] = '{
			  "category": "'.$sec1_category.'",
			  "Unit Cost": '.$sec1_unitcost.',
			  "Usage Cost": '.$sec1_usagecost.',
			  "Weather Cost": '.$sec1_weathercost.',
			  "Total Change": '.$sec1_totalchange.'			
				}';
			}
?>	
	<style>
		body {
	  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
	}

	#chartdiv {
	  width: 100%;
	  height: 400px;
	  font-size:11px;
	}

	  </style>
	</head>
	<body>
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
	<h3 class="colorccc">Cost Drivers: August 2019 to August 2019</h3>
	<div id="chartdiv"></div>
	<script id="rendered-js">
		  // Themes begin
	// am4core.useTheme(am4themes_animated);
	// Themes end

	// Create chart instance
	var chart = am4core.create("chartdiv", am4charts.XYChart);

	// Add data
	chart.data = [
<?php
		echo implode(',',$sec1_arr);
?>	
	],



	chart.legend = new am4charts.Legend();
	chart.legend.position = "bottom";

	// Create axes
	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "category";
	categoryAxis.renderer.grid.template.opacity = 0;
	categoryAxis.renderer.line.strokeOpacity = 0.5;
	categoryAxis.renderer.ticks.template.strokeOpacity = 0.5;
	categoryAxis.renderer.ticks.template.length = 10;
	//categoryAxis.renderer.ticks.template.stroke = am4core.color("#495C43");

	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
	valueAxis.renderer.grid.template.opacity = 0;
	valueAxis.renderer.ticks.template.strokeOpacity = 0.5;
	//valueAxis.renderer.ticks.template.stroke = am4core.color("#495C43");
	valueAxis.renderer.ticks.template.length = 10;
	valueAxis.renderer.line.strokeOpacity = 0.5;
	//valueAxis.renderer.baseGrid.disabled = true;
	//valueAxis.renderer.minGridDistance = 40;
	valueAxis.renderer.baseGrid.strokeDasharray = "3,3";
	valueAxis.numberFormatter.numberFormat = "$#,###|$(#,##s)";
	//valueAxis.baseInterval = {timeUnit:"year", count:1};
	chart.colors.list = [
	  am4core.color("#6b7a75"),  
	  am4core.color("#4b99af"),
	  am4core.color("#003a45")
	];

	// Create series
	function createSeries(field, name) {
	  var series = chart.series.push(new am4charts.ColumnSeries());
	  series.dataFields.valueY = field;
	  series.dataFields.categoryX = "category";
	  series.stacked = true;
	  series.name = name;
	  series.columns.template.width = am4core.percent(20);
	  //series.range.template.width = am4core.percent(40);
	  series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
	  series.fillAlphas = 1;
	  /*series.fillColors = "#C72C95";
	  series.fillColors = "#616f6b"; // fill
	  series.fillColors = "#00333c"; // fill
	  series.fillColors = "#428ea5";*/ // fill
	  //series.fillColors = "#6b7a75";
	  //series.fillColors = "#003a45"; // fill
	  //series.fillColors = "#4b99af"; // fill
	  //series.fillColors = "#dfab24"; // fill




	  //categoryAxis.stroke =am4core.color("#");
	  //categoryAxis.stroke =am4core.color("#");
	  //categoryAxis.stroke =am4core.color("#dca01f");

	  var series2 = chart.series.push(new am4charts.StepLineSeries());
	  series2.dataFields.valueY = "Total Change";
	  series2.dataFields.categoryX = "category";
	  series2.strokeWidth = 3;
	  //series2.strokeDasharray = "3,3";
	  series2.noRisers = true;
	  series2.startLocation = .3;
	  series2.endLocation = .7;
	  //series2.stroke.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
	  //series2.label.text= "Test";
	   series2.dataItem.text = "My custom label";
	   //series.template.fill = am4core.color("#dca01f"); // fill

	  //series2.stroke.visibleInLegend = false;

	  var labelBullet = series.bullets.push(new am4charts.LabelBullet());
	  labelBullet.locationY = 0.5;
	  //labelBullet.label.text = "{valueY}";
	  labelBullet.label.fill = am4core.color("#000");

	}

	createSeries("Unit Cost", "Unit Cost");
	createSeries("Usage Cost", "Usage Cost");
	createSeries("Weather Cost", "Weather Cost");

	</script>
<?php
		}
   }	
}else if(isset($_GET["section3"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";
	$sec3_arr=array();
   if ($stmt_section3 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) { 
        $stmt_section3->execute();
        $stmt_section3->store_result();
        if ($stmt_section3->num_rows > 0) {
            $stmt_section3->bind_result($sec3_id,$sec3_date,$sec3_category,$sec3_unitcost,$sec3_usagecost,$sec3_weathercost,$sec3_energyunit,$sec3_totalchange);
			while($stmt_section3->fetch()){
				$sec3_arr[] = '{
				  "category": "'.@ucfirst(@strtolower($sec3_category)).'",
				  "Unit Cost": "'.$sec3_unitcost.'",
				  "Usage Cost": "'.$sec3_usagecost.'",
				   "Weather Cost": "'.$sec3_weathercost.'"			
				}';
			}
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 400px;
	font-size	: 11px;
}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "am4themes_animated",
  "legend": {
    //"horizontalGap": 10,
    //"maxColumns": 1,
    "position": "bottom",
    "useGraphSettings": true,
    "markerSize": 10,
	"markerWidth": 30,
	"markerHeight": 30,
  },
  "dataProvider": [
  <?php echo implode(',',$sec3_arr); ?>
	],
  "valueAxes": [{
    "stackType": "regular",
    "axisAlpha": 0.5,
    "gridAlpha": "start","position":"bottom"
  }],
  "graphs": [{
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "fillColors": "#6b7a75",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Unit Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Unit Cost",
	"fixedColumnWidth": 65
    //"columns.template.width" = am4core.percent(3);

  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
	"fillColors": "#003a45",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Usage Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Usage Cost",
	"fixedColumnWidth": 65
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
	"fillColors": "#4b99af",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Weather Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Weather Cost",
	"fixedColumnWidth": 65
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
	"fillColors": "#dfab24",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Total Change",
    "type": "column",
    "color": "#000000",
    "valueField": "Total Change",
	"fixedColumnWidth": 65
  }],
  //"rotate": true,
  "categoryField": "category",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }
});

/*
var chart2 = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "am4themes_animated",
  "legend": {
    "horizontalGap": 10,
    "maxColumns": 1,
    "position": "bottom",
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [{
  "category": "Electric",
   "Total Change": 5000
  }, {
    "category": "Natural Gas",
    "Total Change": 5000
  }],
  "valueAxes": [{
    "stackType": "regular",
    "axisAlpha": 0.5,
    "gridAlpha": 0
  }],
  "graphs": [{
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "fillColors": "#008800",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Unit Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Unit Cost"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Usage Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Usage Cost"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Weather Cost",
    "type": "column",
    "color": "#000000",
    "valueField": "Weather Cost"
  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Total Change",
    "type": "column",
    "color": "#000000",
    "valueField": "Total Change"
  }],
  //"rotate": true,
  "categoryField": "category",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }
});
*/
</script>
<h3 class="colorccc"><?php echo @ucfirst(@strtolower($category)); ?> Cost Drivers</h3>
<div id="chartdiv"></div>					
<?php
		}
   }
}else if(isset($_GET["section4"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";
	$sec4_arr=array();
   if ($stmt_section4 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,accrual FROM `benchmark_report` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) { 
        $stmt_section4->execute();
        $stmt_section4->store_result();
        if ($stmt_section4->num_rows > 0) {
            $stmt_section4->bind_result($sec4_id,$sec4_date,$sec4_category,$sec4_unitcost,$sec4_usagecost,$sec4_weathercost,$sec4_energyunit,$sec4_totalchange,$sec4_accrual);
			while($stmt_section4->fetch()){
				$sec4_arr[] = '{
				  "category": "'.@ucfirst(@strtolower($sec4_category)).'",
				  "Usage": "'.$sec4_usagecost.'",
				  "Accrual": "'.$sec4_accrual.'"	   
				}';
			}
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 400px;
	font-size	: 11px;
}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "am4themes_animated",
  "legend": {
    //"horizontalGap": 10,
    //"maxColumns": 1,
    "position": "bottom",
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [<?php echo implode(',',$sec4_arr); ?>],
  "valueAxes": [{
    "stackType": "regular",
    "axisAlpha": 0.5,
    "gridAlpha": 0
  }],
  "graphs": [{
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "fillColors": "#6b7a75",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Usage",
    "type": "column",
    "color": "#000000",
    "valueField": "Usage",
	"fixedColumnWidth": 65
    //"columns.template.width" = am4core.percent(20);

  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
	"fillColors": "#70903b",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Accrual",
    "type": "column",
    "color": "#000000",
    "valueField": "Accrual",
	"fixedColumnWidth": 65
  }],
  //"rotate": true,
  "categoryField": "category",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }
});
</script>
<h3 class="colorccc"><?php if($category=="electric"){$mtype="(kWh)";}elseif($category=="natural gas"){$mtype="(Therms)";}else$mtype="";  echo @ucfirst(@strtolower($category)).$mtype; ?> Performance Weather Adjusted</h3>
<div id="chartdiv"></div>					
<?php
		}
   }
}elseif(isset($_GET["section5"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";
	$sec5_arr=array();
   if ($stmt_section5 = $mysqli->prepare('SELECT id,date,category,x,y,value FROM `benchmark_report_bubbles` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) { 
        $stmt_section5->execute();
        $stmt_section5->store_result();
        if ($stmt_section5->num_rows > 0) {
            $stmt_section5->bind_result($sec5_id,$sec5_date,$sec5_category,$sec5_x,$sec5_y,$sec5_value);
			while($stmt_section5->fetch()){
				$sec5_arr[] = '{
					"title": "'.@ucfirst(@strtolower($sec5_category)).'",
					"color": "'.($sec5_category=="electric"?"#003a45":"#70903b").'",
					"x": "'.$sec5_x.'",
					"y": "'.$sec5_y.'",
					"value": "'.$sec5_value.'"				  
				}';
			}
?>	
<style>
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}

#chartdiv {
  width: 100%;
  height: 300px
  font-size:11px;
}
</style>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
am4core.ready(function() {
	/**
	 * ---------------------------------------
	 * This demo was created using amCharts 4.
	 * 
	 * For more information visit:
	 * https://www.amcharts.com/
	 * 
	 * Documentation is available at:
	 * https://www.amcharts.com/docs/v4/
	 * ---------------------------------------
	 */

	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end

	var chart = am4core.create("chartdiv", am4charts.XYChart);

	var valueAxisX = chart.xAxes.push(new am4charts.ValueAxis());
	valueAxisX.renderer.ticks.template.disabled = true;
	valueAxisX.renderer.axisFills.template.disabled = true;

	var valueAxisY = chart.yAxes.push(new am4charts.ValueAxis());
	valueAxisY.renderer.ticks.template.disabled = true;
	valueAxisY.renderer.axisFills.template.disabled = true;

	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.valueX = "x";
	series.dataFields.valueY = "y";
	series.dataFields.value = "value";
	series.strokeOpacity = 0;
	series.sequencedInterpolation = true;
	series.tooltip.pointerOrientation = "vertical";

	var bullet = series.bullets.push(new am4core.Circle());
	bullet.fill = am4core.color("#ff0000");
	bullet.propertyFields.fill = "color";
	bullet.strokeOpacity = 0;
	bullet.strokeWidth = 2;
	bullet.fillOpacity = 0.5;
	bullet.stroke = am4core.color("#ffffff");
	bullet.hiddenState.properties.opacity = 0;
	bullet.tooltipText = "[bold]{title}:\n[/]\Value: {value.value}\nX: {valueX.value}\nY:{valueY.value}";

	var outline = chart.plotContainer.createChild(am4core.Circle);
	outline.fillOpacity = 0;
	outline.strokeOpacity = 0.8;
	outline.stroke = am4core.color("#ff0000");
	outline.strokeWidth = 2;
	outline.hide(0);

	var blurFilter = new am4core.BlurFilter();
	outline.filters.push(blurFilter);

	bullet.events.on("over", function(event) {
		var target = event.target;
		chart.cursor.triggerMove({ x: target.pixelX, y: target.pixelY }, "hard");
		chart.cursor.lineX.y = target.pixelY;
		chart.cursor.lineY.x = target.pixelX - chart.plotContainer.pixelWidth;
		valueAxisX.tooltip.disabled = false;
		valueAxisY.tooltip.disabled = false;

		outline.radius = target.pixelRadius + 2;
		outline.x = target.pixelX;
		outline.y = target.pixelY;
		outline.show();
	})

	bullet.events.on("out", function(event) {
		chart.cursor.triggerMove(event.pointer.point, "none");
		chart.cursor.lineX.y = 0;
		chart.cursor.lineY.x = 0;
		valueAxisX.tooltip.disabled = true;
		valueAxisY.tooltip.disabled = true;
		outline.hide();
	})

	var hoverState = bullet.states.create("hover");
	hoverState.properties.fillOpacity = 1;
	hoverState.properties.strokeOpacity = 1;

	series.heatRules.push({ target: bullet, min: 7, max: 7, property: "radius" });

	bullet.adapter.add("tooltipY", function (tooltipY, target) {
		return -target.radius;
	})

	chart.cursor = new am4charts.XYCursor();
	//chart.cursor.behavior = "zoomXY";

	//chart.scrollbarX = new am4core.Scrollbar();
	//chart.scrollbarY = new am4core.Scrollbar();

	chart.data = [<?php echo implode(",",$sec5_arr); ?>];
});
</script>
<h3 class="colorccc"><?php if($category=="electric"){$mtype="kWh";}elseif($category=="natural gas"){$mtype="Therms";}else$mtype="";  echo @ucfirst(@strtolower($category)).": Avg Annual ".$mtype; ?> Per Sqft</h3>
<div id="chartdiv"></div>	
<?php
		}
   }		
}elseif(isset($_GET["section6"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";

	$color=array("#003a45","#4b99af","#6b7a75","#dfab24","#70903b","#003a45");
	$sec6_latlong=$sec6_mapData=array();
   if ($stmt_section6 = $mysqli->prepare('SELECT id,sitename,longitude,latitude,value FROM `benchmark_report_map` where category="'.$mysqli->real_escape_string($category).'"')) { 
        $stmt_section6->execute();
        $stmt_section6->store_result();
        if ($stmt_section6->num_rows > 0) {
            $stmt_section6->bind_result($sec6_id,$sec6_sitename,$sec6_longitude,$sec6_latitude,$sec6_value);
			while($stmt_section6->fetch()){
				$title=@ucfirst(@strtolower($sec6_sitename));
				$s6id=str_replace(" ","",$title);
				$sec6_latlong[] = '"'.$s6id.'": {"latitude":'.$sec6_longitude.',"longitude":'.$sec6_latitude.'}';
				$sec6_mapData[] = '{"id":"'.$s6id.'","name":"'.$title.'","value":'.$sec6_value.',"color":"'.$color[array_rand($color)].'"}';
			}
?>
<style>
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}

#chartdiv {
  width: 100%;
  height: 400px;
  font-size:11px;
}
</style>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
am4core.ready(function() {
/**
 * ---------------------------------------
 * This demo was created using amCharts 4.
 * 
 * For more information visit:
 * https://www.amcharts.com/
 * 
 * Documentation is available at:
 * https://www.amcharts.com/docs/v4/
 * ---------------------------------------
 */

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create map instance
var chart = am4core.create("chartdiv", am4maps.MapChart);

var title = chart.titles.create();
title.text = "";
title.textAlign = "middle";

var latlong = {<?php echo implode(",",$sec6_latlong); ?>};
 
var mapData = [<?php echo implode(",",$sec6_mapData); ?>];


// Add lat/long information to data
for(var i = 0; i < mapData.length; i++) {
  mapData[i].latitude = latlong[mapData[i].id].latitude;
  mapData[i].longitude = latlong[mapData[i].id].longitude;
}

// Set map definition
chart.geodata = am4geodata_worldLow;

// Set projection
chart.projection = new am4maps.projections.Miller();

// Create map polygon series
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
polygonSeries.exclude = ["AQ"];
polygonSeries.useGeodata = true;
polygonSeries.nonScalingStroke = true;
polygonSeries.strokeWidth = 0.5;

var imageSeries = chart.series.push(new am4maps.MapImageSeries());
imageSeries.data = mapData;
imageSeries.dataFields.value = "value";

var imageTemplate = imageSeries.mapImages.template;
imageTemplate.propertyFields.latitude = "latitude";
imageTemplate.propertyFields.longitude = "longitude";
imageTemplate.nonScaling = true

var circle = imageTemplate.createChild(am4core.Circle);
circle.fillOpacity = 0.3;
circle.propertyFields.fill = "color";
circle.tooltipText = "{name}: [bold]{value}[/]";

imageSeries.heatRules.push({
  "target": circle,
  "property": "radius",
  "min": 7,
  "max": 7,
  "dataField": "value"
})
chart.events.on("ready", function(ev) {
  var india = polygonSeries.getPolygonById("US");
  
  // Pre-zoom
  chart.zoomToMapObject(india);
  
  // Set active state
  setTimeout(function() {
    india.isActive = true;
  }, 1000);
});
});
</script>
<div id="chartdiv"></div>	
<?php	
		}
   }
}elseif(isset($_GET["section88"])){
?>	
<style>
@import url(https://fonts.googleapis.com/css?family=Lato);
body {
  font-family: Lato;
  font-size: 11px;
}

#chartdiv {
  width: 900px;
  max-width: 100%;
  height: 300px;
  border: 2px solid #eee;
  border-bottom: none;
}

#chartdata {
  width: 900px;
  max-width: 100%;
  border: 2px solid #eee;
  border-top: none;
}

#chartdata * {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

#chartdata table {
  width: 100%;
  border-collapse: collapse;
}

#chartdata table th,
#chartdata table td {
  text-align: center;
  padding: 5px 7px;
}

#chartdata table th {
  background: #999;
  color: #fff;
}

#chartdata table td {
  border: 1px solid #eee;
}

#chartdata table td.row-title {
  font-weight: bold;
  width: 150px;
}

#chartdata tr:hover td {
  background: #eee;
  cursor: pointer;
}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
//am4core.ready(function() {
/**
 * A plugin to automatically creata a data table for the chart
 * The plugin will check if the chart config has the following setting set: "dataTableId"
 */
AmCharts.addInitHandler(function(chart) {

  // check if export to table is enabled
  if (chart.dataTableId === undefined)
    return;

  // get chart data
  var data = chart.dataProvider;

  // create a table
  var holder = document.getElementById(chart.dataTableId);
  var table = document.createElement("table");
  holder.appendChild(table);
  var tr, td;

  // construct table
  for (var i = 0; i < chart.graphs.length; i++) {
    
    // add rows
    tr = document.createElement("tr");
    tr.setAttribute('data-valuefield', chart.graphs[i].valueField);
    table.appendChild(tr);
    td = document.createElement("td");
    td.className = "row-title";
    td.innerHTML = chart.graphs[i].title;
    tr.appendChild(td);
    
    for (var x = 0; x < chart.dataProvider.length; x++) {
      td = document.createElement('td');
      td.innerHTML = chart.dataProvider[x][chart.graphs[i].valueField];
      tr.appendChild(td);
    }
    
    tr.onclick = function(e){
      showOnly(this.getAttribute('data-valuefield'));
    }
    
  }

}, ["serial"]);

/**
 * Define chart data
 */
var chartData = [{
  "year": 1994,
  "cars": 1587,
  "motorcycles": 650,
  "bicycles": 121
}, {
  "year": 1995,
  "cars": 1567,
  "motorcycles": 683,
  "bicycles": 146
}, {
  "year": 1996,
  "cars": 1617,
  "motorcycles": 691,
  "bicycles": 138
}, {
  "year": 1997,
  "cars": 1630,
  "motorcycles": 642,
  "bicycles": 127
}];

var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "dataProvider": chartData,
  "dataTableId": "chartdata",
  "categoryField": "year",
  "categoryAxis": {
    "gridAlpha": 0.07,
    "axisColor": "#DADADA",
    "startOnAxis": false,
    "gridPosition": "start",
    "tickPosition": "start",
    "tickLength": 25,
    "boldLabels": true
  },
  "valueAxes": [{
    //"stackType": "regular",
    "gridAlpha": 0.07,
    "title": "Traffic incidents"
  }],
  "graphs": [{
    "type": "column",
    "title": "Cars",
    "valueField": "cars",
    "lineAlpha": 0,
    "fillAlphas": 0.6
  }, {
    "type": "column",
    "title": "Motorcycles",
    "valueField": "motorcycles",
    "lineAlpha": 0,
    "fillAlphas": 0.6
  }, {
    "type": "column",
    "title": "Bicycles",
    "valueField": "bicycles",
    "lineAlpha": 0,
    "fillAlphas": 0.6
  }],
  "chartCursor": {
    "cursorAlpha": 0,
    "categoryBalloonEnabled": false
  },
  "autoMargins": false,
  "marginLeft": 150,
  "marginRight": 0,
  "marginBottom": 25
});

function showOnly(valuefield){
  
  var new_data = [];
  var category_field = "year"; // always include this
  
  for(var i = 0; i < chartData.length; i++){
    var filtered_item = {};
    filtered_item[category_field] = chartData[i][category_field];
    filtered_item[valuefield] = chartData[i][valuefield];
    new_data.push(filtered_item);
  }
  
  chart.dataProvider = new_data;
  chart.validateData();
  
}
//});
</script>
<h3 class="colorccc">Electric (kWh)</h3>
<div id="chartdiv"></div>					
<div id="chartdata"></div>		
<?php	
}elseif(isset($_GET["section2"])){
	$category="electric";
	$sec2_arr[]=array();
   if ($stmt_section2 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,`Current Period Costs`,`% Change In Costs`,`Current Period Usage`,`Total Change In Usage`,`% Change In Usage`,`CY Unit Cost` FROM `benchmark_report` where month(date)=month(now()) Order by date desc')) { 
        $stmt_section2->execute();
        $stmt_section2->store_result();
        if ($stmt_section2->num_rows > 0) {
            $stmt_section2->bind_result($sec2_id,$sec2_date,$sec2_category,$sec2_unitcost,$sec2_usagecost,$sec2_weathercost,$sec2_energyunit,$sec2_totalchange,$sec2_currentperiodcosts,$sec2_percentchange,$sec2_currentperiodusage,$sec2_totalchangeinusage,$sec2_percentagechangusage,$sec2_cyunitcost);
			while($stmt_section2->fetch()){
				$title=@ucfirst(@strtolower($sec2_category));
				$sec2_arr[$sec2_category] = array("Current Period Cost"=>$sec2_currentperiodcosts,"Total Change In Cost"=>$sec2_totalchange,"Unit Cost"=>$sec2_unitcost,"Usage Cost"=>$sec2_usagecost,"Weather Cost"=>$sec2_weathercost,"% Change In Costs"=>$sec2_percentchange,"Current Period Usage"=>$sec2_currentperiodusage,"Total Change In Usage"=>$sec2_totalchangeinusage,"% Change In Usage"=>$sec2_percentagechangusage,"CY Unit Cost"=>$sec2_cyunitcost);
			}
			//print_r($sec2_arr);
?>
<style>
.smart-form {
    margin: 0;
    outline: 0;
    color: #666;
    position: relative;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table {
    width: 100%;
    margin-bottom: 18px;
}
table {
    max-width: 100%;
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
*, :after, :before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: grey;
	border: 1px solid #ccc;
}
body {
    font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
}
thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
}
.fc-border-separate thead tr, .table thead tr {
    background-color: #eee;
    background-image: -webkit-gradient(linear,0 0,0 100%,from(#f2f2f2),to(#fafafa));
    background-image: -webkit-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -moz-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -ms-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -o-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    font-size: 12px;
}
.smart-form *, .smart-form :after, .smart-form :before {
    margin: 0;
    padding: 0;
    box-sizing: content-box;
    -moz-box-sizing: content-box;
}
tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    border-top: 0;
}
.table-condensed.table>tbody>tr>td, .table-condensed.table>tbody>tr>th, .table-condensed.table>tfoot>tr>td, .table-condensed.table>tfoot>tr>th, .table-condensed.table>thead>tr>td, .table-condensed.table>thead>tr>th {
    padding: 5px 10px!important;
}
.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border-width: 1px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px 10px;
}
table.table-bordered thead td, table.table-bordered thead th {
    border-left-width: 0;
    border-top-width: 0;
}
.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border-bottom-width: 2px;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 5px;
}
.table>thead>tr>th {
    vertical-align: bottom;
    border-bottom: 2px solid #ddd;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
th {
    text-align: left;
}
td, th {
    padding: 0;
}
th {
    display: table-cell;
    vertical-align: inherit;
    font-weight: bold;
    text-align: -internal-center;
}
tbody {
    display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
}
.table-condensed.table>tbody>tr>td, .table-condensed.table>tbody>tr>th, .table-condensed.table>tfoot>tr>td, .table-condensed.table>tfoot>tr>th, .table-condensed.table>thead>tr>td, .table-condensed.table>thead>tr>th {
    padding: 5px 10px!important;
}
.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
    background-color: #f9f9f9;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px 10px;
}
table.table-bordered tbody td, table.table-bordered tbody th {
    border-left-width: 0;
    border-bottom-width: 0;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 5px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
} 
.border-right{border-right:1px solid #ddd;}
.border-bottom{border-bottom:1px solid #ddd;}
</style>
<h3 class="colorccc">Executive Summary: August 2019 to August 2019</h3>
<div class="table-responsive">
<table id="datatable_fixed_column" class="table-bordered table-striped table-condensed table-hover smart-form" width="100%">
	<thead>
		<tr>
		<td class="border-bottom">&nbsp;</td>
		<td class="border-bottom">Electric: kWh</td>
		<td class="border-right border-bottom">Natural Gas: Therms</td>
		<td class="border-right border-bottom">Grand Total</td>
		</tr>
	</thead>
	<tbody>
		<tr>
		<td>Current Period Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["Current Period Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["Current Period Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity(($sec2_arr["electric"]["Current Period Cost"] + $sec2_arr["natural gas"]["Current Period Cost"])); ?></td>
		</tr>
		<tr>
		<td>Total Change In Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["Total Change In Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["Total Change In Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity(($sec2_arr["electric"]["Total Change In Cost"] + $sec2_arr["natural gas"]["Total Change In Cost"])); ?></td>
		</tr>
		<tr>
		<td>Unit Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["Unit Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["Unit Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity(($sec2_arr["electric"]["Unit Cost"] + $sec2_arr["natural gas"]["Unit Cost"])); ?></td>
		</tr>
		<tr>
		<td>Usage Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["Usage Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["Usage Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity(($sec2_arr["electric"]["Usage Cost"] + $sec2_arr["natural gas"]["Usage Cost"])); ?></td>
		</tr>
		<tr>
		<td>Weather Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["Weather Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["Weather Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity(($sec2_arr["electric"]["Weather Cost"] + $sec2_arr["natural gas"]["Weather Cost"])); ?></td>
		</tr>
		<tr>
		<td>% Change In Costs</td>
		<td><?php echo $sec2_arr["electric"]["% Change In Costs"]; ?>%</td>
		<td class="border-right"><?php echo $sec2_arr["natural gas"]["% Change In Costs"]; ?>%</td>
		<td class="border-right"><?php echo ($sec2_arr["electric"]["% Change In Costs"] + $sec2_arr["natural gas"]["% Change In Costs"]); ?>%</td>
		</tr> 
		<tr>
		<td>Current Period Usage</td>
		<td><?php echo $sec2_arr["electric"]["Current Period Usage"]; ?></td>
		<td class="border-right"><?php echo $sec2_arr["natural gas"]["Current Period Usage"]; ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>Total Change In Usage</td>
		<td><?php echo $sec2_arr["electric"]["Total Change In Usage"]; ?></td>
		<td class="border-right"><?php echo $sec2_arr["natural gas"]["Total Change In Usage"]; ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>% Change In Usage</td>
		<td><?php echo $sec2_arr["electric"]["% Change In Usage"]; ?>%</td>
		<td class="border-right"><?php echo $sec2_arr["natural gas"]["% Change In Usage"]; ?>%</td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>CY Unit Cost</td>
		<td><?php echo adjustpolarity($sec2_arr["electric"]["CY Unit Cost"]); ?></td>
		<td class="border-right"><?php echo adjustpolarity($sec2_arr["natural gas"]["CY Unit Cost"]); ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>
<?php
		}
   }		
}elseif(isset($_GET["section7"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";

	$color=array("#003a45","#4b99af","#6b7a75","#dfab24","#70903b","#003a45");
	$sec7_arr=array();
   if ($stmt_section7 = $mysqli->prepare('SELECT id,sitename,`CY 3 Month Avg Use Per SQFT`,`PY Adj 3 Month Avg Use Per SQFT`,`% Change` FROM `benchmark_report_map` Where category="'.$mysqli->real_escape_string($category).'"')) { 
        $stmt_section7->execute();
        $stmt_section7->store_result();
        if ($stmt_section7->num_rows > 0) {
            $stmt_section7->bind_result($sec7_id,$sec7_sitename,$sec7_cmaups,$sec7_pamaups,$sec7_perchange);
?>
<style>
.smart-form {
    margin: 0;
    outline: 0;
    color: #666;
    position: relative;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table {
    width: 100%;
    margin-bottom: 18px;
}
table {
    max-width: 100%;
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
*, :after, :before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: grey;
	border: 1px solid #ccc;
}
body {
    font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
}
thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
}
.fc-border-separate thead tr, .table thead tr {
    background-color: #eee;
    background-image: -webkit-gradient(linear,0 0,0 100%,from(#f2f2f2),to(#fafafa));
    background-image: -webkit-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -moz-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -ms-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -o-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    background-image: -linear-gradient(top,#f2f2f2 0,#fafafa 100%);
    font-size: 12px;
}
.smart-form *, .smart-form :after, .smart-form :before {
    margin: 0;
    padding: 0;
    box-sizing: content-box;
    -moz-box-sizing: content-box;
}
tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}
.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
    border-top: 0;
}
.table-condensed.table>tbody>tr>td, .table-condensed.table>tbody>tr>th, .table-condensed.table>tfoot>tr>td, .table-condensed.table>tfoot>tr>th, .table-condensed.table>thead>tr>td, .table-condensed.table>thead>tr>th {
    padding: 5px 10px!important;
}
.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border-width: 1px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px 10px;
}
table.table-bordered thead td, table.table-bordered thead th {
    border-left-width: 0;
    border-top-width: 0;
}
.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border-bottom-width: 2px;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 5px;
}
.table>thead>tr>th {
    vertical-align: bottom;
    border-bottom: 2px solid #ddd;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
th {
    text-align: left;
}
td, th {
    padding: 0;
}
th {
    display: table-cell;
    vertical-align: inherit;
    font-weight: bold;
    text-align: -internal-center;
}
tbody {
    display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
}
.table-condensed.table>tbody>tr>td, .table-condensed.table>tbody>tr>th, .table-condensed.table>tfoot>tr>td, .table-condensed.table>tfoot>tr>th, .table-condensed.table>thead>tr>td, .table-condensed.table>thead>tr>th {
    padding: 5px 10px!important;
}
.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
    background-color: #f9f9f9;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px 10px;
}
table.table-bordered tbody td, table.table-bordered tbody th {
    border-left-width: 0;
    border-bottom-width: 0;
}
.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    /*border: 1px solid #ddd;*/
}
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
    padding: 5px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
} 
.border-right{border-right:1px solid #ddd;}
.border-bottom{border-bottom:1px solid #ddd;}
</style>
<h3 class="colorccc"><?php if($category=="electric"){$mtype="(kWh)";}elseif($category=="natural gas"){$mtype="(Therms)";}else$mtype="";  echo @ucfirst(@strtolower($category)).$mtype; ?></h3>
<div class="table-responsive">
<table id="datatable_fixed_column" class="table-bordered table-striped table-condensed table-hover smart-form" width="100%">
	<thead>
		<tr>
		<td class="border-bottom">Site Name</td>
		<td class="border-bottom">CY 3 Month Avg Use/SQFT</td>
		<td class="border-bottom">PY Adj 3 Month Avg Use/SQFT</td>
		<td class="border-bottom">% Change</td>
		</tr>
	</thead>
	<tbody>
<?php
	while($stmt_section7->fetch()){
		$title=@ucfirst(@strtolower($sec7_sitename));
		echo "<tr><td>".$title."</td><td>".$sec7_cmaups."</td><td>".$sec7_pamaups."</td><td>".$sec7_perchange."%</td></tr>";
	}

?>
	</tbody>
</table>
</div>
<?php
		}
   }		
}else if(isset($_GET["section8"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";

	$sec8_arr=array();
   if ($stmt_section8 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,accrual FROM `benchmark_report` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) { 
        $stmt_section8->execute();
        $stmt_section8->store_result();
        if ($stmt_section8->num_rows > 0) {
            $stmt_section8->bind_result($sec8_id,$sec8_date,$sec8_category,$sec8_unitcost,$sec8_usagecost,$sec8_weathercost,$sec8_energyunit,$sec8_totalchange,$sec8_accrual);
			while($stmt_section8->fetch()){
				$sec8_arr[] = '{
				  "category": "'.@ucfirst(@strtolower($sec8_category)).'",
				  "Usage": "'.$sec8_usagecost.'",
				  "Accrual": "'.$sec8_accrual.'"	   
				}';
			} 
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 400px;
	font-size	: 11px;
}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "am4themes_animated",
  "legend": {
    //"horizontalGap": 10,
    //"maxColumns": 1,
    "position": "bottom",
    "useGraphSettings": true,
    "markerSize": 10,
  },
  "dataProvider": [<?php echo implode(',',$sec8_arr); ?>],
  "valueAxes": [{
    "stackType": "regular",
    "axisAlpha": 0.5,
    "gridAlpha": 0
  }],
  "graphs": [{
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
    "fillColors": "#6b7a75",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Usage",
    "type": "column",
    "color": "#000000",
    "valueField": "Usage",
	"fixedColumnWidth": 65
    //"columns.template.width" = am4core.percent(20);

  }, {
    "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
    "fillAlphas": 0.8,
	"fillColors": "#70903b",
    "labelText": "[[value]]",
    "lineAlpha": 0.3,
    "title": "Accrual",
    "type": "column",
    "color": "#000000",
    "valueField": "Accrual",
	"fixedColumnWidth": 65
  }],
  //"rotate": true,
  "categoryField": "category",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  },
  "export": {
    "enabled": true
  }
});
</script>
<h3 class="colorccc">Usage in <?php if($category=="electric"){$mtype="(kWh)";}elseif($category=="natural gas"){$mtype="(Therms)";}else$mtype="";  echo $mtype; ?></h3>
<div id="chartdiv"></div>					
<?php
		}
   }
}
function adjustpolarity($value){
	if($value < 0) return $value= "-$".($value * -1);
	else return "$".$value;
}
?>
