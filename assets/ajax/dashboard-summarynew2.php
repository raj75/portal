<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
	die("Access Restricted.");


$comp_id=$_SESSION["company_id"];
$user_one=$_SESSION['user_id'];

$sites_under_mgmt=$acc_under_mgmt_pwr=$acc_under_mgmt_gas=$cons_under_mgmt_gwh=$cons_under_mgmt_mmbtu=$val_saving_to_date=0;
$stmtat = $mysqli->prepare("SELECT sites_under_mgmt,acc_under_mgmt_pwr,acc_under_mgmt_gas,cons_under_mgmt_gwh,cons_under_mgmt_mmbtu,val_saving_to_date FROM company WHERE company_id='".$comp_id."' LIMIT 1");
if(!$stmtat){
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}

$stmtat->execute();
$stmtat->store_result();
if ($stmtat->num_rows > 0) {
	$stmtat->bind_result($sites_under_mgmt,$acc_under_mgmt_pwr,$acc_under_mgmt_gas,$cons_under_mgmt_gwh,$cons_under_mgmt_mmbtu,$val_saving_to_date);
	$stmtat->fetch();
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" media="screen" href="/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<style>
html,body,container{padding:0;margin:0;}
html{font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;font-size:11px !important;}
.height-340{height:340px;width:183px;margin-left:-14px;}
.cnt3{
		width: 147px !important;
		/*height: 321px !important;*/
		height: 321px;
		margin-top: 15px;
		margin-left: 2px;
	 }
.cnt1{
		width: 256px !important;
		/*margin-left: -47px !important;*/
	 }
.cnt2{
		margin-left: 9px !important;
		/*height:349px !important;*/
		height:349px;
	 }

.bigBoxinline{
    background-color: #004d60;
	margin-top: 51px;
    padding-left: 5px;
    padding-top: 15px;
    padding-right: 5px;
    padding-bottom: 15px;
    width: 116px;
    height: 68px;
    color: #fff;
    box-sizing: content-box;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    border-left: 5px solid rgba(0,0,0,.15);
    overflow: hidden;
    cursor: pointer;}

.bigboxicon{/*float:left;*/ display:inline;}
.bigboxnumber{display:inline;}
.ttle{text-align:center;}

@media only screen and (max-width: 620px) {

/*@media only screen and (min-device-width : 490px) and (max-device-width : 590px) {*/
/* Styles */

/*@media only screen and (max-width: 490px) and (min-width: 590px)  {*/
	.cnt1{
		width: 200px !important;
		margin-left: -20px !important;
	}
	.cnt2{
		width: 155px !important;
		margin-left: -10px !important;
	}
	.cnt3{
		width: 143px !important;
		margin-left: -35px;
	}
	.bigBoxinline{
		width: 95px;
		font-size:13px;
	}
	.sum_col_1 {
		padding-left: 5px;
	}
}
.bigboxnumber {
    float: right;
}
.width-51{margin-left:-51px; }
</style>
<?php
$count_sa=$count_fi=0;
if ($stmt = $mysqli->prepare('SELECT count(sa.id) FROM saving_analysis sa, company c,user up WHERE sa.company_id=c.company_id and _read="N" and up.user_id = "'.$user_one.'" and up.company_id = sa.company_id')) {

//('SELECT count(sa.id) FROM saving_analysis sa, company c,user up WHERE sa.company_id=c.id and _read="N" and up.id = "'.$user_one.'" and up.company_id = sa.company_id')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
		$stmt->bind_result($count_sa);
		$stmt->fetch();
	}
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}

if ($stmt = $mysqli->prepare('SELECT count(fi.id) FROM focus_items fi, company c,user up where fi.company_id=c.company_id and _read="N" and up.user_id = "'.$user_one.'" and up.company_id = fi.company_id')) {

//('SELECT count(fi.id) FROM focus_items fi, company c,user up where fi.company_id=c.id and _read="N" and up.id = "'.$user_one.'" and up.company_id = fi.company_id')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
		$stmt->bind_result($count_fi);
		$stmt->fetch();
	}
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}

 ?>
<div class="row" style="margin-bottom:6px;display: -webkit-inline-box;">
	<div class="col-sm-3 col-md-3 col-lg-3 height-344">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="bigBoxinline animated fadeIn fast" style="background-color: rgb(115, 158, 115);" onclick="navigateurl('assets/ajax/saving-analysis-edit.php?type=unread','Unread Saving Analysis')"><div id="bigBoxColor5"><p class="ttle">Unread<br>Saving Analysis</p><p></p><div class="bigboxicon"><i class="fa fa-check"></i></div><div class="bigboxnumber"><?php echo $count_sa; ?></div></div></div>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="bigBoxinline animated fadeIn fast" style="background-color: rgb(50, 118, 177);" onclick="navigateurl('assets/ajax/focus-items-edit.php?type=unread','Unread Focus Items')"><div id="bigBoxColor5"><p class="ttle">Unread<br>Focus Items</p><p></p><div class="bigboxicon"><i class="fa fa-bell swing animated"></i></div><div class="bigboxnumber"><?php echo $count_fi; ?></div></div></div>
		</div>
	</div>
	<div class="col-sm-9 col-md-9 col-lg-9 height-344 width-51">
<div class="row" style="margin-bottom:6px;">
							<!--
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 sum_col_1 height-344">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="bigBoxinline animated fadeIn fast" style="background-color: rgb(115, 158, 115);" onclick="parent.navigateurl('assets/ajax/saving-analysis-edit.php?type=unread','Unread Saving Analysis')"><div id="bigBoxColor5" class="ttle"><p class="ttle">Unread<br>Saving Analysis</p><p></p><div class="bigboxicon"><i class="fa fa-check"></i></div><div class="bigboxnumber"><?php //echo $count_sa; ?></div></div></div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="bigBoxinline animated fadeIn fast" style="background-color: rgb(50, 118, 177);" onclick="parent.navigateurl('assets/ajax/focus-items-edit.php?type=unread','Unread Focus Items')"><div id="bigBoxColor5" class="ttle"><p class="ttle">Unread<br>Focus Items</p><p></p><div class="bigboxicon"><i class="fa fa-bell swing animated"></i></div><div class="bigboxnumber"><?php //echo $count_fi; ?></div></div></div>
								</div>
							</div>
							-->
							<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 sum_col_1 height-344">




<div class="container---">
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<div id="container1" class="height-340 cnt1"></div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<div id="container2" class="height-340 cnt2"></div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<div id="container3" class="height-340 cnt3"></div>
		</div>
	</div>
</div>




</div>
</div>
</div>
</div>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script id="rendered-js">
am4core.ready(function() {

// Themes begin

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("container1", am4charts.XYChart);
//chart.responsive.enabled = true;
// Add data
chart.data = [{
  "year": "",
  "sites_under_mgmt": <?php echo $sites_under_mgmt; ?>,
  "acc_under_mgmt_pwr": <?php echo $acc_under_mgmt_pwr; ?>,
  "acc_under_mgmt_gas": <?php echo $acc_under_mgmt_gas; ?> }];
//chart.hideCredits=true;

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "year";
//categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;
categoryAxis.hidden = true;
categoryAxis.tooltip.disabled = true;
categoryAxis.renderer.labels.template.disabled = true;

var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.opposite = true;
valueAxis.hidden = true;
valueAxis.min = 0;
valueAxis.tooltip.disabled = true;
valueAxis.renderer.labels.template.disabled = true;

var title = chart.titles.create();
title.text = "Managed Portfolio";
title.fontSize = 13;
title.fontWeight = 600;
title.marginBottom = 7;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "year";
  series.name = name;
  //series.columns.template.tooltipText = "[bold]{name}[/][font-size:13px][/][color:#000000]: {valueY}";
  series.columns.template.height = am4core.percent(100);
  series.columns.template.width = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{name}";
  valueLabel.label.fill = am4core.color("#000000");
  //valueLabel.label.rotation = 320;
  valueLabel.label.truncate = false;
  valueLabel.label.wrap = true;
  valueLabel.label.maxWidth = 240;
  valueLabel.label.hideOversized = false;
  valueLabel.label.horizontalCenter = "center";
  valueLabel.locationY = 1;
  valueLabel.dy = 24;
  valueLabel.label.dx = -22;
  valueLabel.label.fontSize = 10;
  valueLabel.label.align = "center";

  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
  categoryLabel.label.text = "{valueY}";
  categoryLabel.label.verticalCenter = "bottom";
  categoryLabel.label.dx = 0;
  categoryLabel.label.dy = 20;
  categoryLabel.label.fill = am4core.color("#ffffff");
  categoryLabel.label.hideOversized = false;
  categoryLabel.label.truncate = false;
  categoryLabel.label.fontSize = 11;

  categoryLabel.label.rotation = 0;
}

chart.paddingBottom = 40;
chart.maskBullets = false;

createSeries("acc_under_mgmt_gas", "Natural Gas Accounts");
createSeries("acc_under_mgmt_pwr", "Electric Accounts");
createSeries("sites_under_mgmt", "Managed Sites");


//chart.legend = new am4charts.Legend();









// Create chart instance
var chart2 = am4core.create("container2", am4charts.XYChart);


// Add data
chart2.data = [{
  "year": "",
  "cons_under_mgmt_gwh": <?php echo $cons_under_mgmt_gwh; ?>,
  "cons_under_mgmt_mmbtu": <?php echo $cons_under_mgmt_mmbtu; ?>
}];

// Create axes
var categoryAxis2 = chart2.xAxes.push(new am4charts.CategoryAxis());
categoryAxis2.dataFields.category = "year";
categoryAxis2.renderer.grid.template.location = 0;
categoryAxis2.renderer.grid.template.disabled = true;
categoryAxis2.renderer.labels.template.disabled = true;
categoryAxis2.hidden = true;


var valueAxis2 = chart2.yAxes.push(new am4charts.ValueAxis());
valueAxis2.renderer.inside = true;
valueAxis2.renderer.labels.template.disabled = true;
valueAxis2.min = 0;
valueAxis2.title = false;
valueAxis2.hidden = true;
valueAxis2.axisAlpha= 0,
valueAxis2.gridAlpha= 0.1,
valueAxis2.labelsEnabled= false

chart2.colors.list = [
  am4core.color("#67b7dc"),
  am4core.color("#D2691E"),
];

var title2 = chart2.titles.create();
title2.text = "Annual MMBtu";
title2.fontSize = 13;
title2.fontWeight = 600;
title2.marginBottom = 7;

// Create series
function createSeries2(field, name) {

  // Set up series
  var series2 = chart2.series.push(new am4charts.ColumnSeries());
  series2.name = name;
  series2.dataFields.valueY = field;
  series2.dataFields.categoryX = "year";
  series2.sequencedInterpolation = true;

  // Make it stacked
  series2.stacked = true;

  // Configure columns
  series2.columns.template.width = am4core.percent(60);
  //series2.columns.template.tooltipText = "[bold]{name}[/][font-size:13px]: {valueY}";


  // Add label
  var labelBullet2 = series2.bullets.push(new am4charts.LabelBullet());
  labelBullet2.label.text = "{valueY}";
  labelBullet2.label.fill = am4core.color("#ffffff");
  labelBullet2.locationY = 0.5;
  labelBullet2.label.fontSize = 11;

  //labelBullet2.label.hideOversized = true;


  return series2;
}

createSeries2("cons_under_mgmt_gwh", "Electric");
createSeries2("cons_under_mgmt_mmbtu", "Natural Gas");

// Legend
chart2.legend = new am4charts.Legend();
chart2.legend.useDefaultMarker = true;
chart2.legend.horizontalGap = 0;
chart2.legend.labels.template.text = "[font-size:10px]{name}[/]";
//chart2.legend.labels.template.autoMargins = false;
//chart2.legend.labels.template.valueWidth = 90;
var markerTemplate2 = chart2.legend.markers.template;
markerTemplate2.width = 10;
markerTemplate2.height = 10;
markerTemplate2.fontSize = 10;







// Create chart instance
var chart3 = am4core.create("container3", am4charts.PieChart);

// Add data
chart3.data = [ {
  "country": "Value of Savings to Date",
  "litres": <?php echo $val_saving_to_date; ?>
} ];

var title3 = chart3.titles.create();
title3.text = "Benefit to Date";
title3.fontSize = 13;
title3.fontWeight = 600;
title3.marginBottom = 0;

// Add and configure Series
var pieSeries3 = chart3.series.push(new am4charts.PieSeries());
pieSeries3.dataFields.value = "litres";
pieSeries3.dataFields.category = "country";
pieSeries3.slices.template.stroke = am4core.color("#fff");
pieSeries3.slices.template.strokeWidth = 2;
pieSeries3.slices.template.strokeOpacity = 1;
pieSeries3.slices.template.tooltipText = "";
//pieSeries3.slices.template.tooltipText = "[bold]Value of Savings to Date[/][font-size:13px]: ${value}";

pieSeries3.ticks.template.disabled = true;
pieSeries3.alignLabels = false;
pieSeries3.labels.template.text = "${value}";
pieSeries3.labels.template.radius = am4core.percent(-100);
pieSeries3.labels.template.fill = am4core.color("white");

// This creates initial animation
pieSeries3.hiddenState.properties.opacity = 1;
pieSeries3.hiddenState.properties.endAngle = -90;
pieSeries3.hiddenState.properties.startAngle = -90;

// Legend
/*chart3.legend = new am4charts.Legend();
chart3.legend.useDefaultMarker = true;
chart3.legend.labels.template.text = "[font-size:10px]Total Savings[/]";
var markerTemplate3 = chart3.legend.markers.template;
markerTemplate3.width = 0;
markerTemplate3.height = 0;
markerTemplate3.fontSize = 10;*/
}); // end am4core.ready()

$(document).ready(function(){
  $('g[aria-labelledby$="-title"]').remove();
});
</script>
</body>
</html>
