<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["group_id"]))
	die("Access Restricted.");
	
//var_export($_GET);	
if(isset($_GET['sites_under_mgmt']) && isset($_GET['acc_under_mgmt_pwr']) && isset($_GET['acc_under_mgmt_gas']))
{
?>
<style>
html,body,container{padding:0;margin:0;}
html{font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;font-size:13px !important;}
</style>
<div id="container"></div>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script id="rendered-js">

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("container", am4charts.XYChart);

// Add data
chart.data = [{
  "year": "",
  "sites_under_mgmt": <?php echo $_GET["sites_under_mgmt"]; ?>,
  "acc_under_mgmt_pwr": <?php echo $_GET["acc_under_mgmt_pwr"]; ?>,
  "acc_under_mgmt_gas": <?php echo $_GET["acc_under_mgmt_gas"]; ?> }];


var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "year";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;
categoryAxis.hidden = true;

var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;
valueAxis.hidden = true;
valueAxis.min = 0;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "year";
  series.name = name;
  series.columns.template.tooltipText = "[bold]{name}[/][font-size:13px][/][color:#000000]: {valueY}";
  series.columns.template.height = am4core.percent(100);
  series.columns.template.width = am4core.percent(80);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{name}";
  valueLabel.label.fill = am4core.color("#000000");
  //valueLabel.label.rotation = 320;
  valueLabel.label.truncate = false;
  valueLabel.label.wrap = true;
  valueLabel.label.maxWidth = 180;
  valueLabel.label.hideOversized = false;
  valueLabel.label.horizontalCenter = "left";
  valueLabel.locationY = 1;
  valueLabel.dy = 24;
  valueLabel.label.dx = -30;
  valueLabel.label.fontSize = 13;

  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
  categoryLabel.label.text = "{valueY}";
  categoryLabel.label.verticalCenter = "bottom";
  categoryLabel.label.dx = 4;
  categoryLabel.label.dy = 20;
  categoryLabel.label.fill = am4core.color("#ffffff");
  categoryLabel.label.hideOversized = false;
  categoryLabel.label.truncate = false;
  categoryLabel.label.fontSize = 13;
  
  categoryLabel.label.rotation = 0; 
}

chart.paddingBottom = 40;
chart.maskBullets = false;

createSeries("acc_under_mgmt_gas", "Natural Gas Accounts");
createSeries("acc_under_mgmt_pwr", "Electric Accounts");
createSeries("sites_under_mgmt", "Managed Sites");


//chart.legend = new am4charts.Legend();
</script>
<script>
$(document).ready(function(){
  $('g[aria-labelledby="id-65-title"]').remove();
});
</script>
<?php }else if(isset($_GET['cons_under_mgmt_gwh']) && isset($_GET['cons_under_mgmt_mmbtu']) && isset($_GET['val_saving_to_date']))
{
?>
<style>
html,body,container{padding:0;margin:0;}
html{font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;font-size:13px !important;}
</style>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<div id="container"></div>
<!-- Styles -->

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("container", am4charts.XYChart);


// Add data
chart.data = [{
  "year": "",
  "cons_under_mgmt_gwh": <?php echo $_GET['cons_under_mgmt_gwh']; ?>,
  "cons_under_mgmt_mmbtu": <?php echo $_GET['cons_under_mgmt_mmbtu']; ?>
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "year";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.grid.template.disabled = true;
categoryAxis.renderer.labels.template.disabled = true;
categoryAxis.hidden = true;


var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.inside = true;
valueAxis.renderer.labels.template.disabled = true;
valueAxis.min = 0;
valueAxis.title = false;
valueAxis.hidden = true;
valueAxis.axisAlpha= 0,
valueAxis.gridAlpha= 0.1,
valueAxis.labelsEnabled= false

chart.colors.list = [
  am4core.color("#67b7dc"),
  am4core.color("#D2691E"),
];

var title = chart.titles.create();
title.text = "Annual MMBtu";
title.fontSize = 13;
title.fontWeight = 600;
title.marginBottom = 7;

// Create series
function createSeries(field, name) {
  
  // Set up series
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.name = name;
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "year";
  series.sequencedInterpolation = true;
  
  // Make it stacked
  series.stacked = true;
  
  // Configure columns
  series.columns.template.width = am4core.percent(60);
  series.columns.template.tooltipText = "[bold]{name}[/][font-size:13px]: {valueY}";
  
  // Add label
  var labelBullet = series.bullets.push(new am4charts.LabelBullet());
  labelBullet.label.text = "{valueY}";
  labelBullet.label.fill = am4core.color("#ffffff");
  labelBullet.locationY = 0.5;
  labelBullet.label.fontSize = 13;
  
  
  return series;
}

createSeries("cons_under_mgmt_gwh", "Electric");
createSeries("cons_under_mgmt_mmbtu", "Natural Gas");

// Legend
chart.legend = new am4charts.Legend();
chart.legend.useDefaultMarker = true;
chart.legend.labels.template.text = "[font-size:13px]{name}[/]";
//chart.legend.labels.template.autoMargins = false;
//chart.legend.labels.template.valueWidth = 90;
var markerTemplate = chart.legend.markers.template;
markerTemplate.width = 10;
markerTemplate.height = 10;
markerTemplate.fontSize = 13;

}); // end am4core.ready()
</script>
<script>
$(document).ready(function(){
  $('g[aria-labelledby="id-65-title"]').remove();
  //$('g[aria-describedby="id-166-description"]').remove();
});
</script>
<?php }else if(isset($_GET['val_saving_to_date']))
{
?>
<style>
html,body,container{padding:0;margin:0;}
html{font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;font-size:13px !important;}
</style>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<div id="container"></div>
<!-- Styles -->
<!-- Styles -->
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("container", am4charts.PieChart);

// Add data
chart.data = [ {
  "country": "Value of Savings to Date",
  "litres": <?php echo $_GET['val_saving_to_date']; ?>
} ];

/*var title = chart.titles.create();
title.text = "Value of Savings to Date";
title.fontSize = 13;
title.fontWeight = 600;
title.marginBottom = 7;*/

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.stroke = am4core.color("#fff");
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
//pieSeries.slices.template.tooltipText = "[bold]Value of Savings to Date[/][font-size:13px]: ${value}";

pieSeries.ticks.template.disabled = true;
pieSeries.alignLabels = false;
pieSeries.labels.template.text = "${value}";
pieSeries.labels.template.radius = am4core.percent(-40);
pieSeries.labels.template.fill = am4core.color("white");

// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()
</script>
<script>
$(document).ready(function(){
  $('g[aria-labelledby="id-66-title"]').remove();
  //$('g[aria-describedby="id-166-description"]').remove();
});
</script>
<?php }else{die("Invalid Parameters");} ?>