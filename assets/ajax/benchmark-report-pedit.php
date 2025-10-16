<?php require_once("inc/init.php");
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");

if(checkpermission($mysqli,78)==false) die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];



$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
?>
<style>
.colorccc{color:#666;font-size: 12px;}
.bmargin{margin-bottom:-44px;}
g[aria-labelledby="id-66-title"],g[filter^='url("#filter-id'][aria-labelledby^='id'][transform^='translate('] {
	display:none !important;
}
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;
  font-size: 11px;
  color:#333;
}
</style>
<?php
if(isset($_GET["section1"]) and isset($_GET["cid"]) and isset($_GET["sdate"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){
	$sec1_arr=array();

	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];

	$servicetyp=@trim($_GET["stype"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);

   //if ($stmt_section1 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` WHERE month(date)=month(now()) and Year(date)=Year(now()) Order by date desc')) {

//Temporary Fix
   /*if ($stmt_section1 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` Order by date desc')) {
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($sec1_id,$sec1_date,$sec1_category,$sec1_unitcost,$sec1_usagecost,$sec1_weathercost,$sec1_energyunit,$sec1_totalchange);
			while($stmt_section1->fetch()){
				$sec1_arr[] = '{
			  "category": "'.@ucfirst(@strtolower($sec1_category)).'",
			  "Unit Cost": '.$sec1_unitcost.',
			  "Usage Cost": '.$sec1_usagecost.',
			  "Weather Cost": '.$sec1_weathercost.',
			  "Total Change": '.$sec1_totalchange.'
				}';
			}*/
//$cid=14;
/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/

$time=@strtotime($startdate);
$smonth=date("M",$time);
$syear=date("Y",$time);

$time=@strtotime($enddate);
$emonth=date("M",$time);
$eyear=date("Y",$time);

$sql='
   SELECT
	a.company_id,
	a.service_group_id,
	DATE_FORMAT("'.$startdate.'", "%b %Y") AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Cost Drivers" AS graph,
	CONCAT("Cost Drivers: ",DATE_FORMAT("'.$startdate.'", "%b %Y"), " to ",DATE_FORMAT("'.$enddate.'", "%b %Y") )  AS title,
	IF(service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	ROUND(SUM(a.unit_cost),2) AS `Unit Cost`,
	ROUND(SUM(a.usage_cost),2) AS `Usage Cost`,
	ROUND(SUM(a.weather_cost),2) AS `Weather Cost`,
	ROUND(SUM(a.total_change_in_cost),2) AS `Total Change in Cost`
FROM 	benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) >= "'.$startdate.'"
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	-- AND a.service_group_id=IF("'.$servicetyp.'"="Electric", 2, 1)
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,a.service_group_id';

   if ($stmt_section1 = $mysqli->prepare($sql)) {
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($sec1_cid,$sec1_service_group_id,$sec1_startdate,$sec1_enddate,$sec1_graph,$sec1_title,$sec1_servicetype,$sec1_unitcost,$sec1_usagecost,$sec1_weathercost,$sec1_totalchangecost);
			while($stmt_section1->fetch()){
				if(empty($sec1_unitcost)) $sec1_unitcost=0;
				if(empty($sec1_usagecost)) $sec1_usagecost=0;
				if(empty($sec1_weathercost)) $sec1_weathercost=0;
				if(empty($sec1_totalchangecost)) $sec1_totalchangecost=0;
				$sec1_arr[] = '{
			  "category": "'.@ucwords(@strtolower($sec1_servicetype)).'",
			  "Unit Cost": '.$sec1_unitcost.',
			  "Usage Cost": '.$sec1_usagecost.',
			  "Weather Cost": '.$sec1_weathercost.',
			  "Total Change": '.$sec1_totalchangecost.'
				}';
			}
?>
	<style>
		body {
	  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;;
	}

	#chartdiv {
	  width: 100%;
	  height: 350px;
	  font-size:11px;
	}
	.glyphicon-info-sign{
		cursor:pointer;
		z-index:9999;
	}
	  </style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	</head>
	<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
	<h4 class="colorccc bmargin"><?php echo $sec1_graph; ?>: <?php echo $smonth." ".$syear; ?> to <?php echo $emonth." ".$eyear; ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart1"></span></h4>
	<div id="chartdiv"></div>
	<script>
	$(function() {
		$('#infochart1').click(function(e) {
			parent.$('#bmdialog').html( "This graph demonstrates the variances in cost due to unit, usage, or weather-related changes.  The graph summarizes the changes over the period selected.<br /><br /> Unit Cost is the change in cost that can directly be attributed to the change in the unit costs (e.g. $ per kWh or Therm).  This could be because of a rate change (summer/winter), taxes, or other components.<br /><br /> Usage Cost is the change in cost that can directly be attributed to change in usage not weather-related.  A higher usage cost will typically be caused by a change in operations or improved/reduced efficiency.<br /><br /> Weather Cost is the change in cost that can directly be attributed to a change in usage that is weather-related.  We use historical and current cooling degree days (CDD) and heating degree days (HDD) to make the weather adjustment.<br /><br /> Total Change is all of the itemized cost categories combined." );
			parent.$("#bmdialog").dialog("open");
			e.preventDefault();
		})
	});
	</script>
	<script id="rendered-js">
		  // Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end

	// Create chart instance
	var chart = am4core.create("chartdiv", am4charts.XYChart);

	// Add data
	chart.data = [
<?php
	if(count($sec1_arr)){
		echo implode(',',$sec1_arr);
	}
?>
			/*{
             "category": "Electric",
              "Unit Cost": -40190,
              "Usage Cost": 24341,
              "Weather Cost": -7149,
              "Total Change": -22998
                },{
              "category": "Natural Gas",
              "Unit Cost": 6417,
              "Usage Cost": 8399,
              "Weather Cost": -14335,
              "Total Change": 482
                }*/
	],


chart.legend = new am4charts.Legend();
chart.legend.position = "top";
chart.legend.align = "right";
chart.legend.contentAlign = "right";
//chart.zoomOutButton.disabled = true;
chart.responsive.enabled = true;
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.grid.template.location = 0;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.numberFormatter.numberFormat = "$#,###|-$#,##s";
//valueAxis.min = -900000;
//valueAxis.max = 900000;

valueAxis.renderer.ticks.template.disabled = false;
valueAxis.renderer.ticks.template.strokeOpacity = 0.3;
valueAxis.renderer.ticks.template.stroke = am4core.color("#333");
valueAxis.renderer.ticks.template.strokeWidth = 1;
valueAxis.renderer.ticks.template.length = 10;

categoryAxis.renderer.ticks.template.disabled = false;
categoryAxis.renderer.ticks.template.strokeOpacity = 0.3;
categoryAxis.renderer.ticks.template.stroke = am4core.color("#333");
categoryAxis.renderer.ticks.template.strokeWidth = 1;
categoryAxis.renderer.ticks.template.length = 10;

function createSeries(field, name, scolor) {
      var series = chart.series.push(new am4charts.ColumnSeries());
      series.dataFields.valueY = field;
      series.dataFields.categoryX = "category";
      series.stacked = true;
      series.name = name;
      series.columns.template.width = am4core.percent(20);
      series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
      series.fillAlphas = 1;
      series.columns.template.fill = am4core.color(scolor);
    series.strokeWidth=0;
}
createSeries("Unit Cost", "Unit Cost","#4b99af");
createSeries("Usage Cost", "Usage Cost","#003a45");
createSeries("Weather Cost", "Weather Cost","#6b7a75");
var referenceline = chart.series.push(new am4charts.StepLineSeries());
referenceline.dataFields.valueY = "Total Change";
referenceline.dataFields.categoryX = "category";
referenceline.strokeWidth = 3;
referenceline.noRisers = true;
referenceline.startLocation = .3;
referenceline.endLocation = .7;
referenceline.stroke = am4core.color("#dfab24");
referenceline.hiddenInLegend = true;
//referenceline.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
referenceline.zIndex=1;
var valueLabel = referenceline.bullets.push(new am4charts.LabelBullet());
valueLabel.label.text = "[bold]{name}[/]\n[font-size:14px]Total Change: {valueY}";;
valueLabel.label.fontSize = 20;
valueLabel.label.dy=-10;
valueLabel.label.background.fill = am4core.color("#EEEEEE");
valueLabel.label.background.fillOpacity = 0.5;
</script>
<?php
		}
   }
}else if(isset($_GET["section3"]) and isset($_GET["cid"])  and isset($_GET["sdate"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){

	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);

	//if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	//else $category="electric";
	$sec3_arr=array();


//$cid=14;
/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/

	$sql='SELECT
	a.company_id,
	a.service_group_id,
	a.`year`,a.`month`,
		DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) AS date,
	DATE_FORMAT(CONCAT(a.`year`,"-",a.`month`,"-01"),\'%b %Y\') AS graph_date,
		DATE_FORMAT("'.$startdate.'", \'%b %Y\') AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", \'%b %Y\') AS `End Date`,
	\'Cost Drivers2\' AS graph,
	CONCAT(IF(service_group_id=1, \'Natural Gas\', \'Electric\'), \' Cost Drivers\')  AS title,
	IF(service_group_id=1, \'Natural Gas\', \'Electric\') AS `Service Type`,
	ROUND(SUM(a.unit_cost),2) AS `Unit Cost`,
	ROUND(SUM(a.usage_cost),2) AS `Usage Cost`,
	ROUND(SUM(a.weather_cost),2) AS `Weather Cost`,
	ROUND(SUM(a.total_change_in_cost),2) AS `Total Change in Cost`
FROM 	benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"=\'Electric\', 2, 1)
	AND IF("'.$sitenamefilter.'"=\'All\',1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"=\'All\',1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"=\'All\',1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,a.service_group_id, a.`year`,a.`month`';


   if ($stmt_section3 = $mysqli->prepare($sql)) {
        $stmt_section3->execute();
        $stmt_section3->store_result();
        if ($stmt_section3->num_rows > 0) {
            $stmt_section3->bind_result($sec3_cid,$sec3_servicegroupid,$sec3_year,$sec3_month,$sec3_date,$sec3_graphdate,$sec3_sdate,$sec3_edate,$sec3_graph,$sec3_title,$sec3_servicetype,$sec3_unitcost,$sec3_usagecost,$sec3_weathercost,$sec3_totalchange);
			while($stmt_section3->fetch()){
				$sec3_arr[] = '{
				  "Date":"'. date("M", mktime(0, 0, 0, $sec3_month, 10))."-".date('y',strtotime("01/01/".$sec3_year)).'",
				  "Unit Cost": "'.$sec3_unitcost.'",
				  "Usage Cost": "'.$sec3_usagecost.'",
				   "Weather Cost": "'.$sec3_weathercost.'",
					"Total Change":"'.$sec3_totalchange.'"
				}';
			}



 /*  if ($stmt_section3 = $mysqli->prepare('SELECT id,DATE_FORMAT(date,"%b"),DATE_FORMAT(date,"%y"),category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` Where date > DATE_SUB(concat(year(curdate()),"-",month(curdate()),"-01"), INTERVAL 1 YEAR) and category="'.$mysqli->real_escape_string($category).'" Order by date')) {
   //if ($stmt_section3 = $mysqli->prepare('SELECT id,DATE_FORMAT(date,"%b"),year(date),category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change` FROM `benchmark_report` Where (id=1 or id=2) and category="'.$mysqli->real_escape_string($category).'" Order by date')) {
        $stmt_section3->execute();
        $stmt_section3->store_result();
        if ($stmt_section3->num_rows > 0) {
            $stmt_section3->bind_result($sec3_id,$sec3_month,$sec3_year,$sec3_category,$sec3_unitcost,$sec3_usagecost,$sec3_weathercost,$sec3_energyunit,$sec3_totalchange);
			while($stmt_section3->fetch()){
				$sec3_arr[] = '{
				  "Date":"'.$sec3_month."-".$sec3_year.'",
				  "Unit Cost": "'.$sec3_unitcost.'",
				  "Usage Cost": "'.$sec3_usagecost.'",
				   "Weather Cost": "'.$sec3_weathercost.'",
					"Total Change":"'.$sec3_totalchange.'"
				}';
			}*/
			//"category": "'.@ucfirst(@strtolower($sec3_category)).'",
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 350px;
	font-size	: 11px;
}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<!--<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>-->
<script>
am4core.ready(function() {
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartdiv", am4charts.XYChart);
//chart.zoomOutButton.disabled = true;

	// Add data
	chart.data = [
<?php
		echo implode(',',$sec3_arr);
?>
/*{
            "Date":"Sep-18",
            "Unit Cost": "-39970",
            "Usage Cost": "39931",
            "Weather Cost": "-3629",
            "Total Change":"-13798"
          },{
            "Date":"Oct-18",
            "Unit Cost": "-36970",
            "Usage Cost": "36931",
            "Weather Cost": "-2629",
            "Total Change":"-10798"
          },{
            "Date":"Nov-18",
            "Unit Cost": "-31970",
            "Usage Cost": "31931",
            "Weather Cost": "-2129",
            "Total Change":"-10398"
          },{
            "Date":"Dec-18",
            "Unit Cost": "-41970",
            "Usage Cost": "41931",
            "Weather Cost": "-3129",
            "Total Change":"-11398"
          },{
            "Date":"Jan-19",
            "Unit Cost": "-51970",
            "Usage Cost": "31931",
            "Weather Cost": "-4129",
            "Total Change":"-21398"
          },{
            "Date":"Feb-19",
            "Unit Cost": "-57970",
            "Usage Cost": "36931",
            "Weather Cost": "-4929",
            "Total Change":"-23398"
          },{
            "Date":"Mar-19",
            "Unit Cost": "-49970",
            "Usage Cost": "25931",
            "Weather Cost": "-9929",
            "Total Change":"-23398"
          },{
            "Date":"Apr-19",
            "Unit Cost": "-49970",
            "Usage Cost": "25931",
            "Weather Cost": "-9929",
            "Total Change":"-23398"
          },{
            "Date":"May-19",
            "Unit Cost": "-41770",
            "Usage Cost": "24931",
            "Weather Cost": "-8929",
            "Total Change":"-22398"
          },{
            "Date":"Jun-19",
            "Unit Cost": "-41070",
            "Usage Cost": "24631",
            "Weather Cost": "-8029",
            "Total Change":"-22898"
          },{
            "Date":"Jul-19",
            "Unit Cost": "-40070",
            "Usage Cost": "24231",
            "Weather Cost": "-7029",
            "Total Change":"-22698"
          },{
            "Date":"Aug-19",
            "Unit Cost": "-40170",
            "Usage Cost": "24331",
            "Weather Cost": "-7129",
            "Total Change":"-22798"
          },{
            "Date":"Sep-19",
            "Unit Cost": "-40190",
            "Usage Cost": "24341",
            "Weather Cost": "-7149",
            "Total Change":"-22998"
          }*/
	];



// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "Date";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.numberFormatter.numberFormat = "$#,###|-$#,##s";
//valueAxis.min = -70000;
//valueAxis.max = 70000;

valueAxis.renderer.ticks.template.disabled = false;
valueAxis.renderer.ticks.template.strokeOpacity = 0.3;
valueAxis.renderer.ticks.template.stroke = am4core.color("#333");
valueAxis.renderer.ticks.template.strokeWidth = 1;
valueAxis.renderer.ticks.template.length = 10;

categoryAxis.renderer.ticks.template.disabled = false;
categoryAxis.renderer.ticks.template.strokeOpacity = 0.3;
categoryAxis.renderer.ticks.template.stroke = am4core.color("#333");
categoryAxis.renderer.ticks.template.strokeWidth = 1;
categoryAxis.renderer.ticks.template.length = 10;

function createSeries(field, name, scolor) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.stacked = true;
  series.name = name;
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "Date";
  series.strokeWidth=0;
  series.sequencedInterpolation = true;
  series.columns.template.fill = am4core.color(scolor);
  series.columns.template.width = am4core.percent(60);
  series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
  //series.columns.template.height = am4core.percent(50);
  var labelBullet = series.bullets.push(new am4charts.LabelBullet());
}
createSeries("Unit Cost", "Unit Cost","#4b99af");
createSeries("Usage Cost", "Usage Cost","#003a45");
createSeries("Weather Cost", "Weather Cost","#6b7a75");

var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.name = "Total Change";
lineSeries.dataFields.valueY = "Total Change";
lineSeries.dataFields.categoryX = "Date";
lineSeries.stroke = am4core.color("#fdd400");
lineSeries.strokeWidth = 3;
lineSeries.tooltip.label.textAlign = "middle";
lineSeries.zIndex=1;
var bullet = lineSeries.bullets.push(new am4charts.Bullet());
bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
bullet.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
var circle = bullet.createChild(am4core.Circle);
circle.radius = 2;
circle.strokeWidth = 3;
// Legend
chart.legend = new am4charts.Legend();
chart.legend.position = "top";
chart.legend.align = "right";
chart.legend.contentAlign = "right";
//chart.zoomOutButton.disabled = true;

//chart.svgContainer.htmlElement.style.height = "80%";
});
</script>
<script>
$(function() {
	$('#infochart3').click(function(e) {
		parent.$('#bmdialog').html( "This graph demonstrates the variances in cost due to unit, usage, or weather-related changes.  The graph illustrates the change over the past 12 months.<br/><br/>Unit Cost is the change in cost that can directly be attributed to the change in the unit costs (e.g. $ per kWh or Therm). This could be because of a rate change (summer/winter), taxes, or other components.<br/><br/>Usage Cost is the change in cost that can directly be attributed to change in usage not weather-related.  A higher usage cost will typically be caused by a change in operations or improved/reduced efficiency.<br/><br/>Weather Cost is the change in cost that can directly be attributed to a change in usage that is weather-related.  We use historical and current cooling degree days (CDD) / heating degree days (HDD) to make the weather adjustment.<br/><br/>Total Change is all of the itemized cost categories combined" );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
<h4 class="colorccc bmargin"><?php echo @ucwords(@strtolower($sec3_title)); ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart3"></span></h4>
<div id="chartdiv"></div>
<?php
		}
   }
}else if(isset($_GET["section4"]) and isset($_GET["cid"])  and isset($_GET["sdate"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){

	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);


	//if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	//else $category="electric";
	$sec4_arr=array();


//$cid=14;
/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/


   //if ($stmt_section4 = $mysqli->prepare('SELECT id,DATE_FORMAT(date,"%b"),DATE_FORMAT(date,"%y"),category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,accrual FROM `benchmark_report` where date > DATE_SUB(concat(year(curdate()),"-",month(curdate()),"-01"), INTERVAL 1 YEAR) and category="'.$mysqli->real_escape_string($category).'" Order by date')) {

   //Temporary Fix

	$sql='SELECT
	a.company_id,
	a.service_group_id,
	a.`year`,a.`month`,
	DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) AS date,
	DATE_FORMAT(CONCAT(a.`year`,"-",a.`month`,"-01"),\'%b %Y\') AS graph_date,
	DATE_FORMAT("'.$startdate.'", \'%b %Y\') AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", \'%b %Y\') AS `End Date`,
	\'Performance Weather Adjusted\' AS graph,
	CONCAT(IF(service_group_id=1, \'Natural Gas (Therms)\', \'Electric (kWh)\'), \' Performance Weather Adjusted\')  AS title,
	IF(service_group_id=1, \'Natural Gas\', \'Electric\') AS `Service Type`,
	ROUND(SUM(a.weather_adjusted_usage),2) AS `Adj Prior Usage`,
	ROUND(SUM(a.billed_usage),2) AS `Actual Usage`,
	ROUND(SUM(a.accrued_usage),2) AS `Accrued Usage`
FROM 	benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"=\'Electric\', 2, 1)
	AND IF("'.$sitenamefilter.'"=\'All\',1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"=\'All\',1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"=\'All\',1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,a.service_group_id, a.`year`,a.`month`';

   if ($stmt_section4 = $mysqli->prepare($sql)) {
        $stmt_section4->execute();
        $stmt_section4->store_result();
        if ($stmt_section4->num_rows > 0) {
            $stmt_section4->bind_result($sec4_cid,$sec4_servicegroupid,$sec4_year,$sec4_month,$sec4_date,$sec4_graphdate,$sec4_sdate,$sec4_edate,$sec4_graph,$sec4_title,$sec4_servicetype,$sec4_adjpriorusage,$sec4_actualusage,$sec4_accruedusage);
			while($stmt_section4->fetch()){
				$sec4_arr[] = '{
				  "Date": "'.date("M", mktime(0, 0, 0, $sec4_month, 10))."-".date('y',strtotime("01/01/".$sec4_year)).'",
				  "Actual Usage": "'.$sec4_actualusage.'",
				  "Accrued Usage": "'.$sec4_accruedusage.'",
				  "Adj Prior Usage": "'.$sec4_adjpriorusage.'"
				}';

				/*$sec4_arr[] = '{
				  "Date": "'.$sec4_month."-".$sec4_year.'",
				  "Usage": "'.$sec4_usagecost.'",
				  "Accrual": "'.$sec4_accrual.'",
				  "Total Change": "'.$sec4_totalchange.'"
				}';*/
			}


   /*if ($stmt_section4 = $mysqli->prepare('SELECT id,DATE_FORMAT(date,"%b"),DATE_FORMAT(date,"%y"),category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,accrual FROM `benchmark_report`  Order by date')) {
        $stmt_section4->execute();
        $stmt_section4->store_result();
        if ($stmt_section4->num_rows > 0) {
            $stmt_section4->bind_result($sec4_id,$sec4_month,$sec4_year,$sec4_category,$sec4_unitcost,$sec4_usagecost,$sec4_weathercost,$sec4_energyunit,$sec4_totalchange,$sec4_accrual);
			while($stmt_section4->fetch()){
				$sec4_arr[] = '{
				  "Date": "'.$sec4_month."-".$sec4_year.'",
				  "Usage": "'.$sec4_usagecost.'",
				  "Accrual": "'.$sec4_accrual.'",
				  "Total Change": "'.$sec4_totalchange.'"
				}';
			}*/
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 350px;
	font-size	: 11px;
}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!--<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>-->
<script src="https://www.amcharts.com/lib/version/4.1.8/core.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/charts.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/themes/animated.js"></script>
<script>
$(function() {
	$('#infochart4').click(function(e) {
		parent.$('#bmdialog').html( "The graph demonstrates the usage categories of the service type selected over the past 12 months.<br /><br />Actual Usage is the actual usage that has been invoiced by the vendor.<br /><br />Accrued Usage is the estimated usage for the most recent month where we have incomplete billing.  We calculate the daily cost of the existing billed days and multiply by the number of missing days.<br /><br />Adj Prior Usage is the actual usage billed for the same period last year and we have adjusted it to the current year's weather.  We use historical and current cooling degree days (CDD) / heating degree days (HDD) to make the weather adjustment." );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
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

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);


// Add data
chart.data = [<?php echo implode(',',$sec4_arr); ?>/*{
                  "Date": "Sep-18",
                  "Usage": "39931",
                  "Accrual": "0",
                  "Total Change": "13798"
                },{
                  "Date": "Oct-18",
                  "Usage": "36931",
                  "Accrual": "0",
                  "Total Change": "10798"
                },{
                  "Date": "Nov-18",
                  "Usage": "31931",
                  "Accrual": "0",
                  "Total Change": "10398"
                },{
                  "Date": "Dec-18",
                  "Usage": "41931",
                  "Accrual": "0",
                  "Total Change": "11398"
                },{
                  "Date": "Jan-19",
                  "Usage": "31931",
                  "Accrual": "0",
                  "Total Change": "21398"
                },{
                  "Date": "Feb-19",
                  "Usage": "36931",
                  "Accrual": "0",
                  "Total Change": "13398"
                },{
                  "Date": "Mar-19",
                  "Usage": "25931",
                  "Accrual": "0",
                  "Total Change": "13398"
                },{
                  "Date": "Apr-19",
                  "Usage": "25931",
                  "Accrual": "0",
                  "Total Change": "23398"
                },{
                  "Date": "May-19",
                  "Usage": "24931",
                  "Accrual": "0",
                  "Total Change": "22398"
                },{
                  "Date": "Jun-19",
                  "Usage": "24631",
                  "Accrual": "0",
                  "Total Change": "12898"
                },{
                  "Date": "Jul-19",
                  "Usage": "24231",
                  "Accrual": "0",
                  "Total Change": "12698"
                },{
                  "Date": "Aug-19",
                  "Usage": "24331",
                  "Accrual": "0",
                  "Total Change": "22798"
                },{
                  "Date": "Sep-19",
                  "Usage": "24341",
                  "Accrual": "9791",
                  "Total Change": "22998"
                }*/ ];
// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "Date";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
//valueAxis.numberFormatter.numberFormat = "$#,###|-$#,##s";
valueAxis.numberFormatter.numberFormat = "#,###|-#,##s";
valueAxis.title = "Usage";
//valueAxis.max = 50000;

valueAxis.renderer.ticks.template.disabled = false;
valueAxis.renderer.ticks.template.strokeOpacity = 0.3;
valueAxis.renderer.ticks.template.stroke = am4core.color("#333");
valueAxis.renderer.ticks.template.strokeWidth = 1;
valueAxis.renderer.ticks.template.length = 10;

categoryAxis.renderer.ticks.template.disabled = false;
categoryAxis.renderer.ticks.template.strokeOpacity = 0.3;
categoryAxis.renderer.ticks.template.stroke = am4core.color("#333");
categoryAxis.renderer.ticks.template.strokeWidth = 1;
categoryAxis.renderer.ticks.template.length = 10;

// Create series
function createSeries(field, name, scolor) {

  // Set up series
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.name = name;
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "Date";
  series.strokeWidth=0;
  series.sequencedInterpolation = true;
  series.columns.template.fill = am4core.color(scolor);
  series.stacked = true;
  series.columns.template.width = am4core.percent(60);
  series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
  var labelBullet = series.bullets.push(new am4charts.LabelBullet());
  labelBullet.locationY = 0.5;
}

createSeries("Actual Usage", "Actual Usage","#003a45");
createSeries("Accrued Usage", "Accrued Usage","#70903b");
var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.name = "Adj Prior Usage";
lineSeries.dataFields.valueY = "Adj Prior Usage";
lineSeries.dataFields.categoryX = "Date";
lineSeries.stroke = am4core.color("#fdd400");
lineSeries.strokeWidth = 3;
lineSeries.tooltip.label.textAlign = "middle";
lineSeries.zIndex=1;
var bullet = lineSeries.bullets.push(new am4charts.Bullet());
bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
bullet.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
var circle = bullet.createChild(am4core.Circle);
circle.radius = 2;
circle.strokeWidth = 3;
// Legend
chart.legend = new am4charts.Legend();
chart.legend.position = "top";
chart.legend.align = "right";
chart.legend.contentAlign = "right";
//chart.zoomOutButton.disabled = true;
});
window.addEventListener("load", function(){
	try
	{
		document.querySelector('g[aria-labelledby="id-47-title"]').style.display = "none";
	}
	catch(e)
	{
	}
	try
	{
		document.querySelector('g[aria-labelledby="id-65-title"]').style.display = "none";
	}
	catch(e)
	{
	}
});
</script>
<h4 class="colorccc bmargin"><?php if(strtolower($sec4_servicetype)=="electric"){$mtype="(kWh)"; }elseif(strtolower($sec4_servicetype)=="natural gas"){$mtype="(Therms)";}else $mtype="";  echo @ucwords(@strtolower($sec4_servicetype)).$mtype; ?> Performance Weather Adjusted&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart4"></span></h4>
<div id="chartdiv"></div>
<?php
		}
   }
}elseif(isset($_GET["section55"])){
	if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	else $category="electric";
	$sec5_arr=$c7list=array();
	$colorarr=array("#a44b54","#c5cac8","#7a8e54");
   if ($stmt_section5 = $mysqli->prepare('SELECT id,date,category,x,y,value FROM `benchmark_report_bubbles` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) {
        $stmt_section5->execute();
        $stmt_section5->store_result();
        if ($stmt_section5->num_rows > 0) {
            $stmt_section5->bind_result($sec5_id,$sec5_date,$sec5_category,$sec5_x,$sec5_y,$sec5_value);
			$ctcolor=0;
			while($stmt_section5->fetch()){
				$sec5_arr[] = '{
					"title": "'.@ucwords(@strtolower($sec5_category)).'",
					"color": "'.@$colorarr[$ctcolor].'",
					"x": "'.$sec5_x.'",
					"y": "'.$sec5_y.'",
					"value": "'.$sec5_value.'"
				}';
				//"color": "'.($sec5_category=="electric"?"#003a45":"#70903b").'",
				if($ctcolor==3) $ctcolor=0;
				else ++$ctcolor;
			}

			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));
			}
?>
<style>
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
}

#chartdiv {
  width: 100%;
  height: 300px;
  font-size:11px;
}
</style>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
am4core.ready(function() {
var chart = am4core.create("chartdiv", am4charts.XYChart);
// Add data
    chart.data = [
				<?php if(count($c7list)==0 or in_array(1,$c7list)) { ?>
				{
                    "title": "Electric",
                    "color": "#a44b54",
                    "x": "1350",
                    "y": "61",
                    "value": "33397058"
                },
				<?php } if(count($c7list)==0 or in_array(3,$c7list)) { ?>
				{
                    "title": "Electric",
                    "color": "#c5cac8",
                    "x": "6419",
                    "y": "71",
                    "value": "36485828"
                },
				<?php } if(count($c7list)==0 or in_array(5,$c7list)) { ?>
				{
                    "title": "Electric",
                    "color": "#7a8e54",
                    "x": "15714",
                    "y": "76",
                    "value": "41118986"
                },
				<?php } if(count($c7list)==0 or in_array(7,$c7list)) { ?>
				{
                    "title": "Electric",
                    "color": "#7a8e54",
                    "x": "36065",
                    "y": "82",
                    "value": "22918688"
                },
				<?php } if(count($c7list)==0 or in_array(9,$c7list)) { ?>
				{
                    "title": "Electric",
                    "color": "#a44b54",
                    "x": "9291",
                    "y": "71",
                    "value": "9421233"
                }
				<?php } ?>
				];
//chart.responsive.enabled = true;
// Create axes
//var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
//categoryAxis.dataFields.category = "category";
//categoryAxis.renderer.grid.template.location = 0;
var categoryAxis = chart.xAxes.push(new am4charts.ValueAxis());
//categoryAxis.dataFields.category = "category";
//categoryAxis.renderer.grid.template.location = 0;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

valueAxis.renderer.ticks.template.disabled = false;
valueAxis.renderer.ticks.template.strokeOpacity = 0.3;
valueAxis.renderer.ticks.template.stroke = am4core.color("#333");
valueAxis.renderer.ticks.template.strokeWidth = 1;
valueAxis.renderer.ticks.template.length = 10;
//valueAxis.min=0;

categoryAxis.renderer.ticks.template.disabled = false;
categoryAxis.renderer.ticks.template.strokeOpacity = 0.3;
categoryAxis.renderer.ticks.template.stroke = am4core.color("#333");
categoryAxis.renderer.ticks.template.strokeWidth = 1;
categoryAxis.renderer.ticks.template.length = 10;

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueX = "x";
series.dataFields.valueY = "y";
series.strokeWidth = 0;
var bullet = series.bullets.push(new am4charts.CircleBullet());
    bullet.propertyFields.fill = "color";
    bullet.strokeOpacity = 0;
// Create value axis range
var range = valueAxis.axisRanges.create();
range.value = 20;
range.grid.stroke = am4core.color("#ccc");
range.grid.strokeWidth = 2;
range.grid.strokeOpacity = 1;
range.label.text="[bold]{name}[/]\n[font-size:14px]Sites Selected Use/SQFT: 68";
range.label.dx=0;
range.label.dy=0;
range.label.background.fill = am4core.color("#333");
range.label.background.fillOpacity = 0.3;
range.zIndex=1;
});
/*
am4core.ready(function() {

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

	valueAxisY.renderer.ticks.template.disabled = false;
	valueAxisY.renderer.ticks.template.strokeOpacity = 1;
	valueAxisY.renderer.ticks.template.stroke = am4core.color("#808080");
	valueAxisY.renderer.ticks.template.strokeWidth = 2;
	valueAxisY.renderer.ticks.template.length = 10;

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
});*/
</script>
<h4 class="colorccc"><?php if($category=="electric"){$mtype="kWh";}elseif($category=="natural gas"){$mtype="Therms";}else$mtype="";  echo @ucwords(@strtolower($category)).": Avg Annual ".$mtype; ?> Per Sqft</h4>
<div id="chartdiv"></div>
<?php
		}
   }
}elseif(isset($_GET["section5"]) and isset($_GET["cid"])  and isset($_GET["sdate"])  and isset($_GET["trendpercentage"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){

	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$trendpercentage=@trim($_GET["trendpercentage"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);

	$chart5js = "";
	$c5query = "";
	if (isset($_GET["chqstr"])) {
		$chart5js = 'arrow.showTooltipOn = "always";' ;
		//$chart5js = 'arrow.alwaysShowTooltip = true;' ;

		$c5str = $mysqli->real_escape_string($_GET["chqstr"]);
		$c5query = " and (".str_replace("`","'",str_replace("__","=",str_replace("~"," and ",str_replace(":",") OR (",$c5str)))).")";

		$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id='.$cid.' AND 1=1 '.$c5query.'
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`;';
	} else {
$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id='.$cid.'
	AND	a.unit_cost IS NOT NULL
	AND a.usage_cost IS NOT NULL
	AND a.weather_cost IS NOT NULL
	-- AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) = "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"="Electric", 2, 1)
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`;';
	}



	$sec5_arr=$c7list=array();
	$colorarr=array("#a44b54","#c5cac8","#7a8e54");

   if ($stmt_section5 = $mysqli->prepare($sql)) {
        $stmt_section5->execute();
        $stmt_section5->store_result();
        if ($stmt_section5->num_rows > 0) {
            $stmt_section5->bind_result($sec5_cid,$sec5_sitenumber,$sec5_sitename,$sec5_servicegroupid,$sec5_edate,$sec5_graph,$sec5_servicetype,$sec5_title,$sec5_avgannualuserpersqft,$sec5_sqft,$sec5_cy3monthavgusesqft,$sec5_pyadj3monthavgusesqft,$sec5_perchange,$sec5_latitude,$sec5_longitude,$sec5_year,$sec5_month,$sec5_color);
			$ctcolor=0;
			while($stmt_section5->fetch()){
				//if($sec5_perchange>0){$stype="up";$tmpcolor="#7a8e54"; }elseif($sec5_perchange<0){$stype="down";$tmpcolor="#a44b54"; }else{$stype="";$tmpcolor="#c5cac8"; }
				/*
				$sec5_arr[] = '{
					"title": "'.@ucwords(@strtolower($sec5_title)).'",
					"color": "'.$tmpcolor.'",
					"x": "'.$sec5_latitude.'",
					"y": "'.$sec5_longitude.'",
					"value": "'.$sec5_sqft.'"	,
					"stype":"'.$stype.'"
				}';
				*/

				//"color": "'.($sec5_category=="electric"?"#003a45":"#70903b").'",

				if ($sec5_sqft > 0) {
					if($sec5_color==0){
						$stype="<span><img src='../img/arrow-up.png' width='10' height='10'></span>";
						$tmpcolor="#a44b54";
					}elseif($sec5_color==1){
						$stype="<span><img src='../img/arrow-down.png' width='10' height='10'></span>";
						$tmpcolor="#7a8e54";
					}else{
						$stype="";
						$tmpcolor="#c5cac8";
					}

					$sec5_arr[] = '{
						"title": "'.@ucwords(@strtolower($sec5_sitename)).'",
						"rcolor": "'.$tmpcolor.'",
						"ax": "'.$sec5_sqft.'",
						"ay": "'.$sec5_avgannualuserpersqft.'",
						"change": "'.$sec5_perchange.'",
						"stype":"'.$stype.'",
						"cid":"'.$sec5_cid.'",
						"sitenumber":"'.$sec5_sitenumber.'",
						"servicegroupid":"'.$sec5_servicegroupid.'",
						"year":"'.$sec5_year.'",
						"month":"'.$sec5_month.'",
						"sqftax":"'.adjustpolaritynumber($sec5_sqft).'",
						"graph":"'.$sec5_graph.'",
						"sitename":"'.$sec5_sitename.'"

					}';
				}


				if($ctcolor==3) $ctcolor=0;
				else ++$ctcolor;
			}
			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));
			}



   /*if ($stmt_section5 = $mysqli->prepare('SELECT id,date,category,x,y,value FROM `benchmark_report_bubbles` where month(date)=9 and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) {
        $stmt_section5->execute();
        $stmt_section5->store_result();
        if ($stmt_section5->num_rows > 0) {
            $stmt_section5->bind_result($sec5_id,$sec5_date,$sec5_category,$sec5_x,$sec5_y,$sec5_value);
			$ctcolor=0;
			while($stmt_section5->fetch()){
				$sec5_arr[] = '{
					"title": "'.@ucfirst(@strtolower($sec5_category)).'",
					"color": "'.@$colorarr[$ctcolor].'",
					"x": "'.$sec5_x.'",
					"y": "'.$sec5_y.'",
					"value": "'.$sec5_value.'"
				}';
				//"color": "'.($sec5_category=="electric"?"#003a45":"#70903b").'",
				if($ctcolor==3) $ctcolor=0;
				else ++$ctcolor;
			}

			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));
			}*/
?>
<style>
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
}

#chartdiv {
  width: 100%;
  height: 334px;
  font-size:11px;
}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function() {
	$('#infochart5').click(function(e) {
		parent.$('#bmdialog').html( "Scatter plot of your locations based on Average Annual Usage per Square Foot versus Square feet.  This shows which sites are performing best and helps identify the underperforming locations.<br /><br />A green dot means that the 3 Month Average Usage per SQFT has declined since the same period last year.  Positive outcome.<br /><br />A red dot means that the 3 Month Average Usage per SQFT has increased since the same period last year.  Negative outcome.<br /><br />A gray dot is a red dot that has been filtered out by the trend %.  The trend % filter is used to filter out locations that we ignoring for our opportunity identification analysis." );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
<h4 class="colorccc"><?php echo $sec5_title; ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart5"></span></h4>
<div id="chartdiv"></div>

<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<script src="https://www.amcharts.com/lib/version/4.1.8/core.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/charts.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/themes/animated.js"></script>
<script>

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

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
chart.data = [<?php echo implode(",",$sec5_arr); ?>];

chart.scrollbarX = new am4core.Scrollbar();
chart.scrollbarY = new am4core.Scrollbar();

chart.scrollbarX.hide();
chart.scrollbarY.hide();

chart.scrollbarX.minHeight =6;
chart.scrollbarX.startGrip.height = 3;
chart.scrollbarX.startGrip.width = 3;
chart.scrollbarX.endGrip.height = 3;
chart.scrollbarX.endGrip.width = 3;

chart.scrollbarY.minWidth =6;
chart.scrollbarY.startGrip.height = 3;
chart.scrollbarY.startGrip.width = 3;
chart.scrollbarY.endGrip.height = 3;
chart.scrollbarY.endGrip.width = 3;


chart.scrollbarX.startGrip.icon.disabled = true;
chart.scrollbarX.endGrip.icon.disabled = true;
chart.scrollbarY.startGrip.icon.disabled = true;
chart.scrollbarY.endGrip.icon.disabled = true;

chart.scrollbarX.paddingRight=10;
chart.scrollbarX.paddingLeft=10;
chart.scrollbarY.paddingTop=10;
chart.scrollbarY.paddingBottom=10;



/*
var img = grip.createChild(am4core.circle);
img.width = 15;
img.height = 15;
img.fill = am4core.color("#999");
img.rotation = 45;
img.align = "center";
img.valign = "middle";
*/

// Create axes
var valueAxisX = chart.xAxes.push(new am4charts.ValueAxis());
valueAxisX.title.text = 'SQFT';
valueAxisX.renderer.minGridDistance = 40;
valueAxisX.renderer.labels.template.rotation = 270;

valueAxisX.renderer.labels.template.adapter.add("dx", function(dx, target) {
	return dx - 18;
});

// Create value axis
var valueAxisY = chart.yAxes.push(new am4charts.ValueAxis());
valueAxisY.title.text = 'Avg Annual Use Per SQFT';

// Add cursor
chart.cursor = new am4charts.XYCursor();
//chart.cursor.behavior = "zoomY";
//chart.cursor.lineX.disabled = true;

valueAxisX.events.on("startchanged", valueAxisZoomed);
valueAxisX.events.on("endchanged", valueAxisZoomed);

function valueAxisZoomed(ev) {
  var start = ev.target.minZoomed;
  var end = ev.target.maxZoomed;
  if (start > 0) {
	  chart.scrollbarX.show();
	  chart.scrollbarY.show();
  } else {
	  chart.scrollbarX.hide();
	  chart.scrollbarY.hide();
  }
  //console.log("New range: " + start + " -- " + end);
}



// Create series
var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.dataFields.valueY = "ay";
lineSeries.dataFields.valueX = "ax";
lineSeries.strokeOpacity = 0;




// Add a bullet
var bullet = lineSeries.bullets.push(new am4charts.Bullet());

// Add a triangle to act as am arrow
//var arrow = bullet.createChild(am4core.Triangle);
var arrow = bullet.createChild(am4core.Circle);
arrow.horizontalCenter = "middle";
arrow.verticalCenter = "middle";
arrow.strokeWidth = 0;
//arrow.fill = chart.colors.getIndex(0);
//arrow.fill = "{color}";
//arrow.direction = "top";
arrow.fill = am4core.color("#ff0000");
arrow.propertyFields.fill = "rcolor";
arrow.width = 14;
arrow.height = 14;
//arrow.tooltipText = "{title} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {ax} \n % Change: {change} ";
arrow.tooltipText = "{sitename} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {sqftax} \n % Change: {change}";

// Add simple bullet
var labelBullet = lineSeries.bullets.push(new am4charts.LabelBullet());
//labelBullet.label.html = "<i class='fa fa-arrow-{stype}' style='font-size:9px;margin-left:-2px;color:black;'></i>";
labelBullet.label.html = "{stype}";
//labelBullet.fill = am4core.color("#ff0000");
labelBullet.propertyFields.fill = "rcolor";
labelBullet.tooltipText = "{sitename} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {sqftax} \n % Change: {change} ";

<?php echo $chart5js; ?>

bullet.events.on("hit", function(event){
	var c5data = event.target.dataItem.dataContext;

	var chqstr = "a.company_id__"+c5data.cid+"~a.site_number__`"+c5data.sitenumber+"`~a.service_group_id__"+c5data.servicegroupid+"~a.year__"+c5data.year+"~a.month__"+c5data.month;


	var iframe6Src = parent.$('#section6').attr('src');
	var iframe7Src = parent.$('#section7').attr('src');

	if(iframe6Src.indexOf("chqstr") != -1){iframe6Src =iframe6Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe6Src =iframe6Src+"&chqstr="+chqstr;}
	if(iframe7Src.indexOf("chqstr") != -1){iframe7Src =iframe7Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe7Src =iframe7Src+"&chqstr="+chqstr;}

	parent.$("#section6").attr("src", iframe6Src);
	parent.$("#section7").attr("src", iframe7Src);

});

labelBullet.events.on("hit", function(event){
	var c5data = event.target.dataItem.dataContext;

	var chqstr = "a.company_id__"+c5data.cid+"~a.site_number__`"+c5data.sitenumber+"`~a.service_group_id__"+c5data.servicegroupid+"~a.year__"+c5data.year+"~a.month__"+c5data.month;


	var iframe6Src = parent.$('#section6').attr('src');
	var iframe7Src = parent.$('#section7').attr('src');

	if(iframe6Src.indexOf("chqstr") != -1){iframe6Src =iframe6Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe6Src =iframe6Src+"&chqstr="+chqstr;}
	if(iframe7Src.indexOf("chqstr") != -1){iframe7Src =iframe7Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe7Src =iframe7Src+"&chqstr="+chqstr;}

	parent.$("#section6").attr("src", iframe6Src);
	parent.$("#section7").attr("src", iframe7Src);

});

// Add simple bullet
////var labelBullet = lineSeries.bullets.push(new am4charts.LabelBullet());
////labelBullet.label.html = "<i class='fa fa-arrow-{stype}' style='font-size:9px;margin-left:-2px;color:grey;'></i>";


var outline = chart.plotContainer.createChild(am4core.Circle);
outline.fillOpacity = 0;
outline.strokeOpacity = 0.8;
outline.stroke = am4core.color("#ff0000");
outline.strokeWidth = 2;
outline.hide(0);


window.addEventListener("load", function(){
	try
	{
		document.querySelector('g[aria-labelledby="id-47-title"]').style.display = "none";
	}
	catch(e)
	{
	}
	try
	{
		document.querySelector('g[aria-labelledby="id-65-title"]').style.display = "none";
	}
	catch(e)
	{
	}
});
</script>
<?php
		}
   }
}elseif(isset($_GET["section6"]) and isset($_GET["cid"])  and isset($_GET["sdate"])  and isset($_GET["trendpercentage"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){
	//if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	//else $category="electric";

	$color=array("#a44b54","#c5cac8","#7a8e54");
	$sec6_latlong=$sec6_mapData=$c7list=array();

//$cid=14;


	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$trendpercentage=@trim($_GET["trendpercentage"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);


/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/


   //if ($stmt_section5 = $mysqli->prepare('SELECT id,date,category,x,y,value FROM `benchmark_report_bubbles` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) {

$chart5js = "";
	$c5query = "";
	if (isset($_GET["chqstr"])) {
		$chart5js = 'arrow.showTooltipOn = "always";' ;
		//$chart5js = 'arrow.alwaysShowTooltip = true;' ;
		$c5str = $mysqli->real_escape_string($_GET["chqstr"]);

		$c5query = " and (".str_replace("`","'",str_replace("__","=",str_replace("~"," and ",str_replace(":",") OR (",$c5str)))).")";

		$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'" AND 1=1 '.$c5query.'
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`;';


	} else {


$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	-- AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) = "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"="Electric", 2, 1)
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
	AND a.square_footage IS NOT NULL
	AND a.cy_3_month_avg_usesqft IS NOT NULL

GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`
ORDER BY a.pct_change DESC';

}

   if ($stmt_section6 = $mysqli->prepare($sql)) {
        $stmt_section6->execute();
        $stmt_section6->store_result();
        if ($stmt_section6->num_rows > 0) {
            $stmt_section6->bind_result($sec6_cid,$sec6_sitenumber,$sec6_sitename,$sec6_servicegroupid,$sec6_edate,$sec6_graph,$sec6_servicetype,$sec6_title,$sec6_avgannualuserpersqft,$sec6_sqft,$sec6_cy3monthavgusesqft,$sec6_pyadj3monthavgusesqft,$sec6_perchange,$sec6_latitude,$sec6_longitude,$sec6_year,$sec6_month,$sec6_color);
			//$ctcolor=0;
			while($stmt_section6->fetch()){
				//if(empty($sec6_sqft)) $sec6_sqft=0;
				$title=@ucwords(@strtolower($sec6_sitename));
				$s6id=str_replace(" ","",$title);

				//if($sec6_perchange>0){$tmpcolor="#7a8e54"; /*green*/ }elseif($sec6_perchange<0){$tmpcolor="#a44b54"; /*red*/ }else{$tmpcolor="#c5cac8"; /*grey*/}

				if($sec6_color==0){
					$stype="up";
					$tmpcolor="#a44b54"; 
					$stypeimg = ',"stypeimg":"../img/arrow-'.$stype.'.png"';
				}elseif($sec6_color==1){
					$stype="down";
					$tmpcolor="#7a8e54"; 
					$stypeimg = ',"stypeimg":"../img/arrow-'.$stype.'.png"';
				}else{
					$stype="";
					$tmpcolor="#c5cac8"; 
					$stypeimg = '';
				}

				$sec6_latlong[] = '"'.$s6id.'": {"latitude":'.$sec6_latitude.',"longitude":'.$sec6_longitude.'}';

				//$sec6_mapData[] = '{"id":"'.$s6id.'","name":"'.$title.'","value":"'.$sec6_sqft.'","color":"'.@$tmpcolor.'","ax":"'.@$sec6_sqft.'","ay":"'.@$sec6_avgannualuserpersqft.'","change":"'.@$sec6_perchange.'","cid":"'.$sec6_cid.'","sitenumber":"'.$sec6_sitenumber.'","servicegroupid":"'.$sec6_servicegroupid.'","year":"'.$sec6_year.'","month":"'.$sec6_month.'","sitename":"'.$sec6_sitename.'","sqftax":"'.adjustpolaritynumber($sec6_sqft).'","stype":"'.$stype.'","stypeimg":"../img/arrow-'.$stype.'.png"}';

				if(!empty($stype))$stypeimg='../img/arrow-'.$stype.'.png';else $stypeimg='';
				$sec6_mapData[] = '{"id":"'.$s6id.'","name":"'.$title.'","value":"'.$sec6_sqft.'","color":"'.@$tmpcolor.'","ax":"'.@$sec6_sqft.'","ay":"'.@$sec6_avgannualuserpersqft.'","change":"'.@$sec6_perchange.'","cid":"'.$sec6_cid.'","sitenumber":"'.$sec6_sitenumber.'","servicegroupid":"'.$sec6_servicegroupid.'","year":"'.$sec6_year.'","month":"'.$sec6_month.'","sitename":"'.$sec6_sitename.'","sqftax":"'.adjustpolaritynumber($sec6_sqft).'","stype":"'.$stype.'","stypeimg":"'.$stypeimg.'"}';
				//if($ctcolor==3) $ctcolor=0;
				//else ++$ctcolor;

				$sec6_mapData[] = '{"id":"'.$s6id.'","name":"'.$title.'","value":"'.$sec6_sqft.'","color":"'.@$tmpcolor.'","ax":"'.@$sec6_sqft.'","ay":"'.@$sec6_avgannualuserpersqft.'","change":"'.@$sec6_perchange.'","cid":"'.$sec6_cid.'","sitenumber":"'.$sec6_sitenumber.'","servicegroupid":"'.$sec6_servicegroupid.'","year":"'.$sec6_year.'","month":"'.$sec6_month.'","sitename":"'.$sec6_sitename.'","sqftax":"'.adjustpolaritynumber($sec6_sqft).'","stype":"'.$stype.'","stypeimg":"'.$stypeimg.'"}';
				

			}






   /*if ($stmt_section6 = $mysqli->prepare('SELECT id,sitename,longitude,latitude,value FROM `benchmark_report_map` where category="'.$mysqli->real_escape_string($category).'"')) {
        $stmt_section6->execute();
        $stmt_section6->store_result();
        if ($stmt_section6->num_rows > 0) {
            $stmt_section6->bind_result($sec6_id,$sec6_sitename,$sec6_longitude,$sec6_latitude,$sec6_value);
			$ctcolor=0;
			while($stmt_section6->fetch()){
				$title=@ucfirst(@strtolower($sec6_sitename));
				$s6id=str_replace(" ","",$title);
				$sec6_latlong[] = '"'.$s6id.'": {"latitude":'.$sec6_longitude.',"longitude":'.$sec6_latitude.'}';
				$sec6_mapData[] = '{"id":"'.$s6id.'","name":"'.$title.'","value":'.$sec6_value.',"color":"'.@$color[$ctcolor].'"}';
				if($ctcolor==3) $ctcolor=0;
				else ++$ctcolor;
			}*/



			//$sec6_latlong = array('"1800-plazaatbucklandhills": {"latitude":38,"longitude":-97}','"1660-mallatlakeplaza": {"latitude":40.3675,"longitude":-82.9962}','"8250-chautauquamall": {"latitude":47.7306,"longitude":-120.935}','"2340-lincolnwoodtowncenter": {"latitude":34.3061,"longitude":-106.118}','"0721-mallatfairfieldcommons": {"latitude":38.8651,"longitude":-77.0264}');

			//$sec6_mapData = array('{"id":"1800-plazaatbucklandhills","name":"1800-plaza at buckland hills","value":2130,"color":"#a44b54"}','{"id":"1660-mallatlakeplaza","name":"1660-mall at lake plaza","value":2776,"color":"#7a7a7a"}','{"id":"8250-chautauquamall","name":"8250-chautauqua mall","value":2152,"color":"#7a8e54"}','{"id":"2340-lincolnwoodtowncenter","name":"2340-lincolnwood town center","value":2777,"color":""}','{"id":"0721-mallatfairfieldcommons","name":"0721-mall at fairfield commons","value":2111,"color":"#a44b54"}');



			/*
			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));

				if(count($c7list) !=0){
					$sec6_latlong=$sec6_mapData=array();
					if(in_array(1,$c7list)){
						$sec6_latlong[] = '"1800-plazaatbucklandhills": {"latitude":38,"longitude":-97}';
						$sec6_mapData[] = '{"id":"1800-plazaatbucklandhills","name":"1800-plaza at buckland hills","value":2130,"color":"#a44b54"}';
					}

					if(in_array(3,$c7list)){
						$sec6_latlong[] = '"1660-mallatlakeplaza": {"latitude":40.3675,"longitude":-82.9962}';
						$sec6_mapData[] = '{"id":"1660-mallatlakeplaza","name":"1660-mall at lake plaza","value":2776,"color":"#7a7a7a"}';
					}

					if(in_array(5,$c7list)){
						$sec6_latlong[] = '"8250-chautauquamall": {"latitude":47.7306,"longitude":-120.935}';
						$sec6_mapData[] = '{"id":"8250-chautauquamall","name":"8250-chautauqua mall","value":2152,"color":"#7a8e54"}';
					}

					if(in_array(7,$c7list)){
						$sec6_latlong[] = '"2340-lincolnwoodtowncenter": {"latitude":34.3061,"longitude":-106.118}';
						$sec6_mapData[] = '{"id":"2340-lincolnwoodtowncenter","name":"2340-lincolnwood town center","value":2777,"color":""}';
					}

					if(in_array(9,$c7list)){
						$sec6_latlong[] = '"0721-mallatfairfieldcommons": {"latitude":38.8651,"longitude":-77.0264}';
						$sec6_mapData[] = '{"id":"0721-mallatfairfieldcommons","name":"0721-mall at fairfield commons","value":2111,"color":"#a44b54"}';
					}
				}
			}
			*/
?>
<style>
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
}

#chartdiv {
  width: 100%;
  height: 350px;
  font-size:11px;
}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
$(function() {
	$('#infochart6').click(function(e) {
		parent.$('#bmdialog').html( "The map illustrates the location of your sites and their respective regional performance.<br /><br />A green dot means that the 3 Month Average Usage per SQFT has declined since the same period last year.  Positive outcome.<br /><br />A red dot means that the 3 Month Average Usage per SQFT has increased since the same period last year.  Negative outcome.<br /><br />A gray dot is a red dot that has been filtered out by the trend %.  The trend % filter is used to filter out locations that we ignoring for our opportunity identification analysis." );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
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
//circle.fillOpacity = 0.3;
circle.fillOpacity = 1;
circle.propertyFields.fill = "color";
//circle.tooltipText = "{name}: [bold]{value}[/]";
//circle.tooltipText = "{name} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {ax} \n % Change: {change} ";
circle.tooltipText = "{sitename} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {sqftax} \n % Change: {change}";

var imageSeriesTemplate = imageTemplate;
var marker = imageSeriesTemplate.createChild(am4core.Image);
//marker.href = '../img/arrow-down.png';
marker.propertyFields.href = "stypeimg";
marker.width = 30;
marker.height = 10;
marker.nonScaling = false;
marker.tooltipText = "{sitename} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {sqftax} \n % Change: {change}";
marker.dy = 5;
//marker.tooltip.getFillFromObject = false;
//marker.tooltip.label.propertyFields.fill = "color";
//marker.tooltip.background.propertyFields.stroke = "color";
marker.horizontalCenter = "middle";
marker.verticalCenter = "bottom";

/*
// Add simple bullet
var labelBullet = imageTemplate.push(new am4maps.LabelBullet());
labelBullet.label.html = "<i class='fa fa-arrow-{stype}' style='font-size:9px;margin-left:-2px;color:black;'></i>";
//labelBullet.fill = am4core.color("#ff0000");
labelBullet.propertyFields.fill = "rcolor";
labelBullet.tooltipText = "{sitename} \n Avg Annual Use Per SQFT: {ay} \n SQFT: {sqftax} \n % Change: {change} ";
*/

circle.events.on("hit", function(event){
	var c5data = event.target.dataItem.dataContext;

	var chqstr = "a.company_id__"+c5data.cid+"~a.site_number__`"+c5data.sitenumber+"`~a.service_group_id__"+c5data.servicegroupid+"~a.year__"+c5data.year+"~a.month__"+c5data.month;

	console.log(c5data);

	var iframe5Src = parent.$('#section5').attr('src');
	var iframe7Src = parent.$('#section7').attr('src');

	if(iframe5Src.indexOf("chqstr") != -1){iframe5Src =iframe5Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe5Src =iframe5Src+"&chqstr="+chqstr;}
	if(iframe7Src.indexOf("chqstr") != -1){iframe7Src =iframe7Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe7Src =iframe7Src+"&chqstr="+chqstr;}

	parent.$("#section5").attr("src", iframe5Src);
	parent.$("#section7").attr("src", iframe7Src);

	console.log(iframe5Src);
	//console.log(event.target.dataItem.dataContext);
});

imageSeriesTemplate.events.on("hit", function(event){
	var c5data = event.target.dataItem.dataContext;

	var chqstr = "a.company_id__"+c5data.cid+"~a.site_number__`"+c5data.sitenumber+"`~a.service_group_id__"+c5data.servicegroupid+"~a.year__"+c5data.year+"~a.month__"+c5data.month;

	console.log(c5data);

	var iframe5Src = parent.$('#section5').attr('src');
	var iframe7Src = parent.$('#section7').attr('src');

	if(iframe5Src.indexOf("chqstr") != -1){iframe5Src =iframe5Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe5Src =iframe5Src+"&chqstr="+chqstr;}
	if(iframe7Src.indexOf("chqstr") != -1){iframe7Src =iframe7Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe7Src =iframe7Src+"&chqstr="+chqstr;}

	parent.$("#section5").attr("src", iframe5Src);
	parent.$("#section7").attr("src", iframe7Src);

	console.log(iframe5Src);
	//console.log(event.target.dataItem.dataContext);
});

imageSeries.heatRules.push({
  "target": circle,
  "property": "radius",
  "min": 7,
  "max": 7,
  "dataField": "value"
});
// Create a zoom control
var zoomControl = new am4maps.ZoomControl();
chart.zoomControl = zoomControl;
zoomControl.slider.height = 100;
//chart.homeZoomLevel = 0;
chart.homeZoomLevel = 2;
chart.homeGeoPoint = {
  latitude: 39.381266,
  longitude: -97.922211
};

/*
// Add button to zoom out
var home = chart.chartContainer.createChild(am4core.Button);
home.label.text = "Reset";
home.align = "right";
home.events.on("hit", function(ev) {
  chart.goHome();
});
 */


/*chart.events.on("ready", function(ev) {
  chart.zoomToMapObject(polygonSeries.getPolygonById("US"));
});*/

chart.chartContainer.wheelable = false;

/*
chart.events.on("ready", function(ev) {
  var zoomto = polygonSeries.getPolygonById("US");
  //var zoomto = polygonSeries.getPolygonById("IN");

  // Pre-zoom
  chart.zoomToMapObject(zoomto);

  // Set active state
  setTimeout(function() {
    zoomto.isActive = true;
  }, 1000);
});
*/


/*chart.events.on("ready", function(ev) {
  var india = polygonSeries.getPolygonById("US");

  // Pre-zoom
  chart.zoomToMapObject(india);

  // Set active state
  setTimeout(function() {
    india.isActive = true;
  }, 1000);
});*/
//chart.zoomToMapObject(polygonSeries.getPolygonById("US"));
});
</script>
<span class="glyphicon glyphicon-info-sign" id="infochart6"></span>
<div id="chartdiv"></div>
<?php
		}
   }
}elseif(isset($_GET["section88"])){
?>
<style>
@import url(https://fonts.googleapis.com/css?family=Lato);
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
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
<h4 class="colorccc">Electric (kWh)</h4>
<div id="chartdiv"></div>
<div id="chartdata"></div>
<?php
}else if(isset($_GET["section2"]) and isset($_GET["cid"]) and isset($_GET["sdate"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){


	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);

//$cid=14;
/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/

$time=@strtotime($startdate);
$smonth=date("F",$time);
$syear=date("Y",$time);

$time=@strtotime($enddate);
$emonth=date("F",$time);
$eyear=date("Y",$time);


	//$category="electric";


	$sql='SELECT a.company_id,
	DATE_FORMAT("'.$startdate.'", "%b %Y") AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Executive Summary" AS graph,
	"Natural Gas: Therms" AS `column`,
	CONCAT("Executive Summary: ",DATE_FORMAT("'.$startdate.'", "%b %Y"), " to ",DATE_FORMAT("'.$enddate.'", "%b %Y") )  AS title,
	ROUND(SUM(a.billed_cost),2) AS `Current Period Cost`,
	ROUND(SUM(a.total_change_in_cost),2) AS `Total Change in Cost`,
	ROUND(SUM(a.unit_cost),2) AS `Unit Cost`,
	ROUND(SUM(a.usage_cost),2) AS `Usage Cost`,
	ROUND(SUM(a.weather_cost),2) AS `Weather Cost`,
	ROUND(((SUM(a.billed_cost)-(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))/(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))*100,2) AS `% Change in Costs`,
	ROUND(SUM(a.current_period_usage),2) AS `Current Period Usage`,
	ROUND(SUM(a.total_change_in_usage),2) AS `Total Change in Usage`,
	ROUND(((SUM(a.current_period_usage)-(SUM(a.current_period_usage)-	SUM(a.total_change_in_usage)))/(SUM(a.current_period_usage)-SUM(a.total_change_in_usage)))*100,2) AS `% Change in Usage`,
	ROUND(SUM(a.billed_cost)/SUM(a.current_period_usage),2) AS `CY Unit Cost`
FROM	benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) >= "'.$startdate.'"
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
	AND a.service_group_id=1
GROUP BY a.company_id,a.service_group_id
UNION ALL
SELECT a.company_id,
	DATE_FORMAT("'.$startdate.'", "%b %Y") AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Executive Summary" AS graph,
	"Electric: kWh" AS `column`,
	CONCAT("Executive Summary: ",DATE_FORMAT("'.$startdate.'", "%b %Y"), " to ",DATE_FORMAT("'.$enddate.'", "%b %Y") )  AS title,
	ROUND(SUM(a.billed_cost),2) AS `Current Period Cost`,
	ROUND(SUM(a.total_change_in_cost),2) AS `Total Change in Cost`,
	ROUND(SUM(a.unit_cost),2) AS `Unit Cost`,
	ROUND(SUM(a.usage_cost),2) AS `Usage Cost`,
	ROUND(SUM(a.weather_cost),2) AS `Weather Cost`,
	ROUND(((SUM(a.billed_cost)-(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))/(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))*100,2) AS `% Change in Costs`,
	ROUND(SUM(a.current_period_usage),2) AS `Current Period Usage`,
	ROUND(SUM(a.total_change_in_usage),2) AS `Total Change in Usage`,
	ROUND(((SUM(a.current_period_usage)-(SUM(a.current_period_usage)-	SUM(a.total_change_in_usage)))/(SUM(a.current_period_usage)-SUM(a.total_change_in_usage)))*100,2) AS `% Change in Usage`,
	ROUND(SUM(a.billed_cost)/SUM(a.current_period_usage),2) AS `CY Unit Cost`
FROM benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) >= "'.$startdate.'"
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
	AND a.service_group_id=2
GROUP BY a.company_id,a.service_group_id
UNION ALL
SELECT a.company_id,
	DATE_FORMAT("'.$startdate.'", "%b %Y") AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Executive Summary" AS graph,
	"Grand Total" AS `column`,
	CONCAT("Executive Summary: ",DATE_FORMAT("'.$startdate.'", "%b %Y"), " to ",DATE_FORMAT("'.$enddate.'", "%b %Y") )  AS title,
	ROUND(SUM(a.billed_cost),2) AS `Current Period Cost`,
	ROUND(SUM(a.total_change_in_cost),2) AS `Total Change in Cost`,
	ROUND(SUM(a.unit_cost),2) AS `Unit Cost`,
	ROUND(SUM(a.usage_cost),2) AS `Usage Cost`,
	ROUND(SUM(a.weather_cost),2) AS `Weather Cost`,
	ROUND(((SUM(a.billed_cost)-(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))/(SUM(a.billed_cost)-	SUM(a.total_change_in_cost)))*100,2) AS `% Change in Costs`,
	NULL AS `Current Period Usage`,
	NULL AS `Total Change in Usage`,
	NULL AS `% Change in Usage`,
	NULL AS `CY Unit Cost`
FROM benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) >= "'.$startdate.'"
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id';

	$sec2_arr[]=array();
   if ($stmt_section2 = $mysqli->prepare($sql)) {
        $stmt_section2->execute();
        $stmt_section2->store_result();
        if ($stmt_section2->num_rows > 0) {
            $stmt_section2->bind_result($sec2_cid,$sec2_sdate,$sec2_edate,$sec2_graph,$sec2_column,$sec2_title,$sec2_currentperiodcosts,$sec2_totalchange,$sec2_unitcost,$sec2_usagecost,$sec2_weathercost,$sec2_percentchange,$sec2_currentperiodusage,$sec2_totalchangeinusage,$sec2_percentagechangusage,$sec2_cyunitcost);
			while($stmt_section2->fetch()){
				$title=@ucwords(@strtolower($sec2_column));
				$sec2_arr[$sec2_column] = array("Current Period Cost"=>$sec2_currentperiodcosts,
												"Total Change In Cost"=>$sec2_totalchange,
												"Unit Cost"=>$sec2_unitcost,
												"Usage Cost"=>$sec2_usagecost,
												"Weather Cost"=>$sec2_weathercost,
												"% Change In Costs"=>$sec2_percentchange,
												"Current Period Usage"=>$sec2_currentperiodusage,
												"Total Change In Usage"=>$sec2_totalchangeinusage,
												"% Change In Usage"=>$sec2_percentagechangusage,
												"CY Unit Cost"=>$sec2_cyunitcost);
			}


  /* if ($stmt_section2 = $mysqli->prepare('SELECT id,date,category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,`Current Period Costs`,`% Change In Costs`,`Current Period Usage`,`Total Change In Usage`,`% Change In Usage`,`CY Unit Cost` FROM `benchmark_report` where month(date)=month(now()) Order by date desc')) {
        $stmt_section2->execute();
        $stmt_section2->store_result();
        if ($stmt_section2->num_rows > 0) {
            $stmt_section2->bind_result($sec2_id,$sec2_date,$sec2_category,$sec2_unitcost,$sec2_usagecost,$sec2_weathercost,$sec2_energyunit,$sec2_totalchange,$sec2_currentperiodcosts,$sec2_percentchange,$sec2_currentperiodusage,$sec2_totalchangeinusage,$sec2_percentagechangusage,$sec2_cyunitcost);
			while($stmt_section2->fetch()){
				$title=@ucfirst(@strtolower($sec2_category));
				$sec2_arr[$sec2_category] = array("Current Period Cost"=>$sec2_currentperiodcosts,"Total Change In Cost"=>$sec2_totalchange,"Unit Cost"=>$sec2_unitcost,"Usage Cost"=>$sec2_usagecost,"Weather Cost"=>$sec2_weathercost,"% Change In Costs"=>$sec2_percentchange,"Current Period Usage"=>$sec2_currentperiodusage,"Total Change In Usage"=>$sec2_totalchangeinusage,"% Change In Usage"=>$sec2_percentagechangusage,"CY Unit Cost"=>$sec2_cyunitcost);
			}*/
			//print_r($sec2_arr);
?>
<style>
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
  font-size: 11px;
}
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
    font-size: 11px;
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
	font-size:11px;
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
.table-responsive{height:350px;}
.table-responsive th,.table-responsive td{font-size:11px !important;}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function() {
	$('#infochart2').click(function(e) {
		parent.$('#bmdialog').html( "The table provides detail about the cost changes over the period selected.<br /><br />Current Period Cost is the total of all costs during the period selected.<br /><br />Total Change In Cost is the sum of the month-over-month cost changes during the period selected.<br /><br />Unit Cost is the change in cost that can directly be attributed to the change in the unit costs (e.g. $ per kWh or Therm).  This could be because of a rate change (summer/winter), taxes, or other components.<br /><br />Usage Cost is the change in cost that can directly be attributed to change in usage not weather-related.  A higher usage cost will typically be caused by a change in operations or improved/reduced efficiency.<br /><br />Weather Cost is the change in cost that can directly be attributed to a change in usage that is weather-related.  We use historical and current cooling degree days (CDD) and heating degree days (HDD) to make the weather adjustment.<br /><br />% Change In Costs is the percentage change (increase or decrease) from the last period cost<br /><br />Current Period Usage is the total billed usage for the period selected.<br /><br />Total Change In Usage is the sum of the month-over-month usage changes during the period selected.<br /><br />% Change In Usage is the percentage usage change (increase or decrease) from the last period usage<br /><br />CY Unit Cost is the calculated current year unit cost.  The unit cost is the total cost divided by the total usage." );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
<h4 class="colorccc">Executive Summary: <?php echo date("M Y",strtotime($startdate)); ?> to <?php echo date("M Y",strtotime($enddate)); ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart2"></span></h4>
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
		<td><?php if(isset($sec2_arr["Electric: kWh"]["Current Period Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["Current Period Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Natural Gas: Therms"]["Current Period Cost"])) echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["Current Period Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Grand Total"]["Current Period Cost"])) echo adjustpolarity($sec2_arr["Grand Total"]["Current Period Cost"]); ?></td>
		</tr>
		<tr>
		<td>Total Change In Cost</td>
		<td><?php if(isset($sec2_arr["Electric: kWh"]["Total Change In Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["Total Change In Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Natural Gas: Therms"]["Total Change In Cost"])) echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["Total Change In Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Grand Total"]["Total Change In Cost"])) echo adjustpolarity($sec2_arr["Grand Total"]["Total Change In Cost"]); ?></td>
		</tr>
		<tr>
		<td>Unit Cost</td>
		<td><?php if(isset($sec2_arr["Electric: kWh"]["Unit Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["Unit Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Natural Gas: Therms"]["Unit Cost"]))echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["Unit Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Grand Total"]["Unit Cost"]))echo adjustpolarity($sec2_arr["Grand Total"]["Unit Cost"]); ?></td>
		</tr>
		<tr>
		<td>Usage Cost</td>
		<td><?php if(isset($sec2_arr["Electric: kWh"]["Usage Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["Usage Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Natural Gas: Therms"]["Usage Cost"])) echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["Usage Cost"]); ?></td>
		<td class="border-right"><?php if(isset($sec2_arr["Grand Total"]["Usage Cost"])) echo adjustpolarity($sec2_arr["Grand Total"]["Usage Cost"]); ?></td>
		</tr>
		<tr>
		<td>Weather Cost</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["Weather Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["Weather Cost"]); ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["Weather Cost"])) echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["Weather Cost"]); ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Grand Total"]["Weather Cost"])) echo adjustpolarity($sec2_arr["Grand Total"]["Weather Cost"]); ?></td>
		</tr>
		<tr>
		<td>% Change In Costs</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["% Change In Costs"])) echo $sec2_arr["Electric: kWh"]["% Change In Costs"]."%"; ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["% Change In Costs"])) echo $sec2_arr["Natural Gas: Therms"]["% Change In Costs"]."%"; ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Grand Total"]["% Change In Costs"])) echo $sec2_arr["Grand Total"]["% Change In Costs"]."%"; ?></td>
		</tr>
		<tr>
		<td>Current Period Usage</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["Current Period Usage"])) echo @str_replace("$","",adjustpolarity($sec2_arr["Electric: kWh"]["Current Period Usage"])); ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["Current Period Usage"])) echo @str_replace("$","",adjustpolarity($sec2_arr["Natural Gas: Therms"]["Current Period Usage"])); ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>Total Change In Usage</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["Total Change In Usage"])) echo @str_replace("$","",adjustpolarity($sec2_arr["Electric: kWh"]["Total Change In Usage"])); ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["Total Change In Usage"])) echo @str_replace("$","",adjustpolarity($sec2_arr["Natural Gas: Therms"]["Total Change In Usage"])); ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>% Change In Usage</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["% Change In Usage"])) echo $sec2_arr["Electric: kWh"]["% Change In Usage"]."%"; ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["% Change In Usage"])) echo $sec2_arr["Natural Gas: Therms"]["% Change In Usage"]."%"; ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
		<tr>
		<td>CY Unit Cost</td>
		<td><?php  if(isset($sec2_arr["Electric: kWh"]["CY Unit Cost"])) echo adjustpolarity($sec2_arr["Electric: kWh"]["CY Unit Cost"]); ?></td>
		<td class="border-right"><?php  if(isset($sec2_arr["Natural Gas: Therms"]["CY Unit Cost"])) echo adjustpolarity($sec2_arr["Natural Gas: Therms"]["CY Unit Cost"]); ?></td>
		<td class="border-right">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>
<?php
		}
   }
}elseif(isset($_GET["section7"]) and isset($_GET["cid"]) and isset($_GET["sdate"])  and isset($_GET["trendpercentage"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){
	//if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	//else $category="electric";

	$color=array("#003a45","#4b99af","#6b7a75","#dfab24","#70903b","#003a45");
	$sec7_arr=array();



//$cid=14;
	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$trendpercentage=@trim($_GET["trendpercentage"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);


/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/


   //if ($stmt_section5 = $mysqli->prepare('SELECT id,date,category,x,y,value FROM `benchmark_report_bubbles` where month(date)=month(now()) and category="'.$mysqli->real_escape_string($category).'" Order by date desc')) {

	$chart5js = "";
	$c5query = "";
	if (isset($_GET["chqstr"])) {
		//$chart5js = 'arrow.showTooltipOn = "always";' ;
		//$chart5js = 'arrow.alwaysShowTooltip = true;' ;

		$c5str = $mysqli->real_escape_string($_GET["chqstr"]);

		$c5query = " and (".str_replace("`","'",str_replace("__","=",str_replace("~"," and ",str_replace(":",") OR (",$c5str)))).")";


$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'" AND 1=1 '.$c5query.'
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`';


	} else {


$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month,
	IF((a.pct_change*100)<'.$trendpercentage.' AND a.pct_change>=0,2, IF((a.pct_change*100)<0,1,0)) AS color -- 2 is gray, 1 is green, 0 is red
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	-- AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) = "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"="Electric", 2, 1)
	AND IF("'.$sitenamefilter.'"="All",1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"="All",1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"="All",1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
	AND a.square_footage IS NOT NULL
	AND a.cy_3_month_avg_usesqft IS NOT NULL

GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`
ORDER BY a.pct_change DESC';

}




/*
	$sql='SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", "%b %Y") AS `End Date`,
	"Performance Weather Adjusted" AS graph,
	IF(a.service_group_id=1, "Natural Gas", "Electric") AS `Service Type`,
	CONCAT(IF(service_group_id=1, "Natural Gas: ", "Electric: "), "Avg Annual kWh Per SqFt")  AS title,
	ROUND((a.annual_usage/a.square_footage),4) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	ROUND(a.cy_3_month_avg_usesqft,4) AS `CY 3 Month Avg Use/SqFt`,
	ROUND(a.py_3_month_avg_usesqft,4) AS `PY Adj 3 Month Avg Use/SqFt`,
	ROUND(a.pct_change*100,2) AS `% Change`,
	a.latitude,
	a.longitude,
	a.year,
	a.month
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'"
	AND	a.unit_cost IS NOT NULL
	AND a.usage_cost IS NOT NULL
	AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) = "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"=\'Electric\', 2, 1)
	AND a.site_name = IF("'.$sitenamefilter.'"=\'All\',a.site_name,"'.$sitenamefilter.'")
	AND a.comparable = IF("'.$comparablefilter.'"=\'All\',a.comparable,"'.$comparablefilter.'")
	AND a.state = IF("'.$statefilter.'"=\'All\',a.state,"'.$statefilter.'")
	AND a.grouping3 = IF("'.$regionfilter.'"=\'All\',a.grouping3,"'.$regionfilter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`';

*/



/*   if ($stmt_section7 = $mysqli->prepare('SELECT
	a.company_id,
	a.site_number,
	a.site_name,
	a.service_group_id,
	DATE_FORMAT("'.$enddate.'", \'%b %Y\') AS `End Date`,
	\'Performance Weather Adjusted\' AS graph,
	IF(a.service_group_id=1, \'Natural Gas\', \'Electric\') AS `Service Type`,
	CONCAT(IF(service_group_id=1, \'Natural Gas: \', \'Electric: \'), \'Avg Annual kWh Per SqFt\')  AS title,
	(a.annual_usage/a.square_footage) AS `Avg Annual Use Per SQFT`,
	a.square_footage AS `SQFT`,
	a.cy_3_month_avg_usesqft AS `CY 3 Month Avg Use/SqFt`,
	a.py_3_month_avg_usesqft AS `PY Adj 3 Month Avg Use/SqFt`,
	a.pct_change AS `% Change`,
	a.latitude,
	a.longitude
FROM
	benchmark_report AS a
	WHERE a.company_id="'.$cid.'"
	AND	a.unit_cost IS NOT NULL
	AND a.usage_cost IS NOT NULL
	AND a.weather_cost IS NOT NULL
	-- AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) = "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"=\'Electric\', 2, 1)
	AND a.site_name = IF("'.$sitenamefilter.'"=\'All\',a.site_name,"'.$sitenamefilter.'")
	AND a.comparable = IF("'.$comparablefilter.'"=\'All\',a.comparable,"'.$comparablefilter.'")
	AND a.state = IF("'.$statefilter.'"=\'All\',a.state,"'.$statefilter.'")
	AND a.grouping3 = IF("'.$regionfilter.'"=\'All\',a.grouping3,"'.$regionfilter.'")
GROUP BY a.company_id,	a.site_number, a.service_group_id, a.`year`,a.`month`')) {
*/
		if ($stmt_section7 = $mysqli->prepare($sql)){
        $stmt_section7->execute();
        $stmt_section7->store_result();
        if ($stmt_section7->num_rows > 0) {
            $stmt_section7->bind_result($sec7_cid,$sec7_sitenumber,$sec7_sitename,$sec7_servicegroupid,$sec7_edate,$sec7_graph,$sec7_servicetype,$sec7_title,$sec7_avgannualuserpersqft,$sec7_sqft,$sec7_cy3monthavgusesqft,$sec7_pyadj3monthavgusesqft,$sec7_perchange,$sec7_latitude,$sec7_longitude,$sec7_year,$sec7_month,$sec7_color);


  /* if ($stmt_section7 = $mysqli->prepare('SELECT id,sitename,`CY 3 Month Avg Use Per SQFT`,`PY Adj 3 Month Avg Use Per SQFT`,`% Change` FROM `benchmark_report_map` Where category="'.$mysqli->real_escape_string($category).'"')) {
        $stmt_section7->execute();
        $stmt_section7->store_result();
        if ($stmt_section7->num_rows > 0) {
            $stmt_section7->bind_result($sec7_id,$sec7_sitename,$sec7_cmaups,$sec7_pamaups,$sec7_perchange);*/
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
body {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
  font-size: 11px;
}
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
.table-responsive{max-height:300px;font-size:11px;}
.table-responsive td,.table-responsive th{font-size:11px !important;}


#datatable_fixed_column_filter{
	float: left;
    text-align: unset;
    margin-bottom: 3px;
    margin-top: 0 !important;
}
#datatable_fixed_column_length{
	text-align: unset;
	float: right;
}
#datatable_fixed_column_paginate{
	text-align: unset;
    float: right !important;
    margin-top: 0;
}
#datatable_fixed_column_info{
    text-align: unset;
    float: left !important;
}
#datatable_fixed_column_filter{
	float:right !important;
	margin-top:-36px !important;
}
.chart7report tbody tr.selected td{
	background-color: #a6b4cd !important;
}
#chart7reset{margin-left: 10px;background:#d9d9d9;
	border: 0;
    padding: 6px 11px;
    font-size: 11px;
    cursor: pointer;
    border-radius: 3px;
}
.table-responsive{margin-top:-6px;}

.table-bordered>thead>tr>td,.table-bordered>tbody>tr:last-child>td{
    border: 1px solid #ddd !important;
}
table.dataTable.no-footer {
    border-bottom: 1px solid #ddd !important;
}
.table-bordered>tbody>tr:last-child>td{
    border-left: none !important;
	border-right: none !important;
}
.dataTables_wrapper.no-footer .dataTables_scrollBody{border-bottom:none;}
.table-bordered>thead>tr>td, .table-bordered>tbody>tr:last-child>td{border:0}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function() {
	$('#infochart7').click(function(e) {
		parent.$('#bmdialog').html( "The table shows the calculated Average Usage per Square Foot compared to the previous year and the % change.<br /><br />In this table, a user can select the sites that will be viewed in the other tables.  The reset button will revert the graphs to the original state.  A search function is also available.<br /><br />CY 3 Month Avg Use/SQFT is the calculated current year 3-month average usage per square foot<br /><br />PY 3 Month Avg Use/SQFT is the calculated previous year 3-month average usage per square foot<br /><br />% Change is the year-over-year delta in calculated 3-month average usage per square foot" );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
<h4 class="colorccc"><?php if(@strtolower($servicetyp)=="electric"){$mtype="(kWh)";}elseif(@strtolower($servicetyp)=="natural gas"){$mtype="(Therms)";}else$mtype="";  echo @ucwords(@strtolower($servicetyp)).$mtype; ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart7"></span>&nbsp;<button id="chart7reset" class="chreset"> Reset </button></h4>
<div class="table-responsive">
<table id="datatable_fixed_column" class="display table-bordered table-striped table-condensed table-hover smart-form chart7report" width="100%">
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
		$title=@ucwords(@strtolower($sec7_sitename));
		echo "<tr chart7data='".@str_replace(" ","",$title)."' data-qstr='a.company_id__".$sec7_cid."~a.site_number__`".$sec7_sitenumber."`~a.service_group_id__".$sec7_servicegroupid."~a.year__".$sec7_year."~a.month__".$sec7_month."'><td>".$title."</td><td>".$sec7_cy3monthavgusesqft."</td><td>".$sec7_pyadj3monthavgusesqft."</td><td>".$sec7_perchange."%</td>
		</tr>
		";
	}
?>

		<!--
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr chart7data=''><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		-->


	</tbody>
</table>
</div>
<script src="../js/libs/jquery-2.1.1.min.js"></script>
<script src="../js/libs/jquery-ui-1.10.3.min.js"></script>
<script src="../js/jquery.multiSelect.js" type="text/javascript"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.js"></script>
<script>
$('.chart7report tbody').on( 'click', 'tr', function () {
	$(this).toggleClass('selected');

	var chart7arrs = [];
	$.each($(".chart7report tbody tr.selected"), function(){
		if($(this).attr("chart7data") == ""){ return;}
		chart7arrs.push($(this).attr("chart7data"));
	});

	var chart7qstr = [];
	$.each($(".chart7report tbody tr.selected"), function(){
		if($(this).attr("data-qstr") == ""){ return;}
		chart7qstr.push($(this).attr("data-qstr"));
	});
	//alert(chart7arrs.join(":"));

	var c7slist=chart7arrs.join(":");

	var chqstr=chart7qstr.join(":");

	//$("#section3").attr("src", "assets/ajax/benchmark-report-pedit.php?section3=true&stype="+stype+"&ct="+Math.random());
	var iframe5Src = parent.$('#section5').attr('src');
	var iframe6Src = parent.$('#section6').attr('src');
	//var iframe8Src = parent.$('#section8').attr('src');

	if(iframe5Src.indexOf("chqstr") != -1){iframe5Src =iframe5Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe5Src =iframe5Src+"&chqstr="+chqstr;}
	if(iframe6Src.indexOf("chqstr") != -1){iframe6Src =iframe6Src.replace(/(chqstr=[^&]*)/g, "chqstr="+chqstr);}
	else{iframe6Src =iframe6Src+"&chqstr="+chqstr;}

	/*
	if(iframe5Src.indexOf("c7list") != -1){iframe5Src =iframe5Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe5Src =iframe5Src+"&c7list="+c7slist;}
	if(iframe6Src.indexOf("c7list") != -1){iframe6Src =iframe6Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe6Src =iframe6Src+"&c7list="+c7slist;}
	*/

	/*
	if(iframe8Src.indexOf("c7list") != -1){iframe8Src =iframe8Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe8Src =iframe8Src+"&c7list="+c7slist;}
	*/

	parent.$("#section5").attr("src", iframe5Src);
	parent.$("#section6").attr("src", iframe6Src);
	//parent.$("#section8").attr("src", iframe8Src);

} );

/*
$("#chart7reset").click(function(){
	$(".chart7report tbody tr").removeClass("selected");
	//$(".chart7report tbody tr").removeAttr("selected");
	var iframe5Src2 = parent.$('#section5').attr('src');
	var iframe6Src2 = parent.$('#section6').attr('src');
	var iframe8Src2 = parent.$('#section8').attr('src');

	if(iframe5Src2.indexOf("c7list") != -1){iframe5Src2 =iframe5Src2.replace(/(c7list=[^&]*)/g, "");}
	//else{iframe5Src2 =iframe5Src+"&c7list="+c7slist;}
	if(iframe6Src2.indexOf("c7list") != -1){iframe6Src2 =iframe6Src2.replace(/(c7list=[^&]*)/g, "");}
	//else{iframe6Src2 =iframe6Src+"&c7list="+c7slist;}
	if(iframe8Src2.indexOf("c7list") != -1){iframe8Src2 =iframe8Src2.replace(/(c7list=[^&]*)/g, "");}
	//else{iframe8Src2 =iframe8Src+"&c7list="+c7slist;}


	parent.$("#section5").attr("src", iframe5Src2);
	parent.$("#section6").attr("src", iframe6Src2);
	parent.$("#section8").attr("src", iframe8Src2);
});
*/

$(".chreset").click(function(){
	////$(".chart7report tbody tr").removeClass("selected");
	//$(".chart7report tbody tr").removeAttr("selected");
	var iframe5Src2 = parent.$('#section5').attr('src');
	var iframe6Src2 = parent.$('#section6').attr('src');
	var iframe7Src2 = parent.$('#section7').attr('src');

	if(iframe5Src2.indexOf("chqstr") != -1){iframe5Src2 =iframe5Src2.replace(/(chqstr=[^&]*)/g, "");}
	//else{iframe5Src2 =iframe5Src+"&c7list="+c7slist;}
	if(iframe6Src2.indexOf("chqstr") != -1){iframe6Src2 =iframe6Src2.replace(/(chqstr=[^&]*)/g, "");}
	//else{iframe6Src2 =iframe6Src+"&c7list="+c7slist;}
	if(iframe7Src2.indexOf("chqstr") != -1){iframe7Src2 =iframe7Src2.replace(/(chqstr=[^&]*)/g, "");}
	//else{iframe8Src2 =iframe8Src+"&c7list="+c7slist;}


	parent.$("#section5").attr("src", iframe5Src2);
	parent.$("#section6").attr("src", iframe6Src2);
	parent.$("#section7").attr("src", iframe7Src2);
});

$('.chart7chkbox').click(function(){
	//var showchartid=$(this).attr("chart7data");

	var chart7arr = [];
	$.each($(".chart7chkbox:checked"), function(){
		chart7arr.push($(this).attr("chart7data"));
	});

	var c7slist=chart7arr.join(":");

	//$("#section3").attr("src", "assets/ajax/benchmark-report-pedit.php?section3=true&stype="+stype+"&ct="+Math.random());
	var iframe5Src = parent.$('#section5').attr('src');
	var iframe6Src = parent.$('#section6').attr('src');
	var iframe8Src = parent.$('#section8').attr('src');

	if(iframe5Src.indexOf("c7list") != -1){iframe5Src =iframe5Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe5Src =iframe5Src+"&c7list="+c7slist;}
	if(iframe6Src.indexOf("c7list") != -1){iframe6Src =iframe6Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe6Src =iframe6Src+"&c7list="+c7slist;}
	if(iframe8Src.indexOf("c7list") != -1){iframe8Src =iframe8Src.replace(/(c7list=[^&]*)/g, "c7list="+c7slist);}
	else{iframe8Src =iframe8Src+"&c7list="+c7slist;}


	parent.$("#section5").attr("src", iframe5Src);
	parent.$("#section6").attr("src", iframe6Src);
	parent.$("#section8").attr("src", iframe8Src);

});
	//pageSetUp();
pagefunction();
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
	function pagefunction() {
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};
		var otable = $("#datatable_fixed_column").DataTable( {
				"lengthMenu": [[10, 25, -1], [10, 25, "All"]],
				"pageLength": 10,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": false,
				"scrollY": "235px",
				//"dom": 'Blfrtip',
				"dom": 'frt',
				/*"buttons": [
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
				],*/
				"order": [[ 0, "desc" ]],
				"autoWidth" : true
			});
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

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
		$('#datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}
</script>
<?php
		}
   }
}else if(isset($_GET["section8"]) and isset($_GET["cid"]) and isset($_GET["sdate"])  and isset($_GET["trendpercentage"]) and isset($_GET["edate"]) and isset($_GET["stype"]) and isset($_GET["compare"]) and isset($_GET["sitename"]) and isset($_GET["state"]) and (isset($_GET["group1"]) or isset($_GET["group2"]) or isset($_GET["group3"]) or isset($_GET["group4"]) or isset($_GET["group5"]))){
	//if(isset($_GET["stype"]) and $_GET["stype"] != "") $category=$_GET["stype"];
	//else $category="electric";

	$sec8_arr=$c7list=array();

//$cid=14;
/*$startdate="2019-02-01";
$enddate="2020-01-01";
$servicetyp="Electric";
$comparablefilter="All";
$sitenamefilter="All";
$statefilter="All";
$regionfilter="All";*/

	if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		$cid=$_GET["cid"];
	}elseif($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5){
		$cid=$_SESSION["company_id"];
	}else die(false);

	$startdate=$_GET["sdate"];
	$enddate=$_GET["edate"];
	$servicetyp=@trim($_GET["stype"]);
	$trendpercentage=@trim($_GET["trendpercentage"]);
	$comparablefilter=@trim($_GET["compare"]);
	$sitenamefilter=@trim($_GET["sitename"]);
	$statefilter=@trim($_GET["state"]);
	$group1filter=@trim($_GET["group1"]);
	$group2filter=@trim($_GET["group2"]);
	$group3filter=@trim($_GET["group3"]);
	$group4filter=@trim($_GET["group4"]);
	$group5filter=@trim($_GET["group5"]);



   if ($stmt_section8 = $mysqli->prepare('SELECT
	a.company_id,
	a.service_group_id,
	a.`year`,a.`month`,
	DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) AS date,
	DATE_FORMAT(CONCAT(a.`year`,"-",a.`month`,"-01"),\'%b %Y\') AS graph_date,
	DATE_FORMAT("'.$startdate.'", \'%b %Y\') AS `Start Date`,
	DATE_FORMAT("'.$enddate.'", \'%b %Y\') AS `End Date`,
	\'Performance Weather Adjusted\' AS graph,
	CONCAT(IF(service_group_id=1, \'Natural Gas (Therms)\', \'Electric (kWh)\'), \' Performance Weather Adjusted\')  AS title,
	IF(service_group_id=1, \'Natural Gas\', \'Electric\') AS `Service Type`,
	ROUND(SUM(a.weather_adjusted_usage),2) AS `Adj Prior Usage`,
	ROUND(SUM(a.billed_usage),2) AS `Actual Usage`,
	ROUND(SUM(a.accrued_usage),2) AS `Accrued Usage`
FROM 	benchmark_report AS a
WHERE a.company_id="'.$cid.'"
	-- AND	a.unit_cost IS NOT NULL
	-- AND a.usage_cost IS NOT NULL
	-- AND a.weather_cost IS NOT NULL
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01"))>=DATE_SUB("'.$enddate.'", INTERVAL 11 MONTH)
	AND DATE(CONCAT(a.`year`,"-",a.`month`,"-01")) <= "'.$enddate.'"
	AND a.service_group_id=IF("'.$servicetyp.'"=\'Electric\', 2, 1)
	AND IF("'.$sitenamefilter.'"=\'All\',1=1,a.site_name="'.$sitenamefilter.'")
	AND IF("'.$comparablefilter.'"=\'All\',1=1,a.comparable="'.$comparablefilter.'")
	AND IF("'.$statefilter.'"=\'All\',1=1,a.state="'.$statefilter.'")
	AND IF("'.$group1filter.'"="All",1=1,a.grouping1="'.$group1filter.'")
	AND IF("'.$group2filter.'"="All",1=1,a.grouping2="'.$group2filter.'")
	AND IF("'.$group3filter.'"="All",1=1,a.grouping3="'.$group3filter.'")
	AND IF("'.$group4filter.'"="All",1=1,a.grouping4="'.$group4filter.'")
	AND IF("'.$group5filter.'"="All",1=1,a.grouping5="'.$group5filter.'")
GROUP BY a.company_id,a.service_group_id, a.`year`,a.`month`')) {
        $stmt_section8->execute();
        $stmt_section8->store_result();
        if ($stmt_section8->num_rows > 0) {
            $stmt_section8->bind_result($sec8_cid,$sec8_servicegroupid,$sec8_year,$sec8_month,$sec8_date,$sec8_graphdate,$sec8_sdate,$sec8_edate,$sec8_graph,$sec8_title,$sec8_servicetype,$sec8_adjpriorusage,$sec8_actualusage,$sec8_accruedusage);
			while($stmt_section8->fetch()){
				$sec8_arr[] = '{
				  "Date": "'.date("M", mktime(0, 0, 0, $sec8_month, 10))."-".date('y',strtotime("01/01/".$sec8_year)).'",
				  "Actual Usage": "'.$sec8_actualusage.'",
				  "Accrued Usage": "'.$sec8_accruedusage.'",
				  "Adj Prior Usage": "'.$sec8_adjpriorusage.'"
				}';
			}
			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));
			}

   /*if ($stmt_section8 = $mysqli->prepare('SELECT id,DATE_FORMAT(date,"%b"),DATE_FORMAT(date,"%y"),category,`unit cost`,`usage cost`,`weather cost`,`energy unit`,`total change`,accrual FROM `benchmark_report` where date > DATE_SUB(concat(year(curdate()),"-",month(curdate()),"-01"), INTERVAL 1 YEAR) and category="'.$mysqli->real_escape_string($category).'" Order by date')) {
        $stmt_section8->execute();
        $stmt_section8->store_result();
        if ($stmt_section8->num_rows > 0) {
            $stmt_section8->bind_result($sec8_id,$sec8_month,$sec8_year,$sec8_category,$sec8_unitcost,$sec8_usagecost,$sec8_weathercost,$sec8_energyunit,$sec8_totalchange,$sec8_accrual);
			while($stmt_section8->fetch()){
				$sec8_arr[] = '{
				  "Date": "'.$sec8_month."-".$sec8_year.'",
				  "Usage": "'.$sec8_usagecost.'",
				  "Accrual": "'.$sec8_accrual.'"
				}';
			}
			if(isset($_GET["c7list"]) and @trim($_GET["c7list"]) != ""){
				$c7list=explode(":",@trim($_GET["c7list"]));
			}*/
?>
<style>
#chartdiv {
	width		: 100%;
	height		: 350px;
	font-size	: 11px;
}
.glyphicon-info-sign{
	cursor:pointer;
	z-index:9999;
}
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function() {
	$('#infochart8').click(function(e) {
		parent.$('#bmdialog').html( "The graph demonstrates the usage categories of the service type selected over the past 12 months.<br /><br />Actual Usage is the actual usage that has been invoiced by the vendor.<br /><br />Accrued Usage is the estimated usage for the most recent month where we have incomplete billing.  We calculate the daily cost of the existing billed days and multiply by the number of missing days.<br /><br />Adj Prior Usage is the actual usage billed for the same period last year and we have adjusted it to the current year's weather.  We use historical and current cooling degree days (CDD) / heating degree days (HDD) to make the weather adjustment." );
		parent.$("#bmdialog").dialog("open");
		e.preventDefault();
	})
});
</script>
<!--<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>-->
<script src="https://www.amcharts.com/lib/version/4.1.8/core.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/charts.js"></script>
<script src="https://www.amcharts.com/lib/version/4.1.8/themes/animated.js"></script>
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

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);


// Add data
chart.data = [<?php echo implode(',',$sec8_arr); ?>
				<?php /* if(count($c7list)==0 or in_array(1,$c7list)){ ?>
				{
                  "Date": "Sep-18",
                  "Usage": "39931",
                  "Accrual": "0",
                  "Total Change": "13798"
                },
				<?php } if(count($c7list)==0 or in_array(3,$c7list)){ ?>
				{
                  "Date": "Oct-18",
                  "Usage": "36931",
                  "Accrual": "0",
                  "Total Change": "10798"
                },
				<?php } if(count($c7list)==0 or in_array(5,$c7list)){ ?>
				{
                  "Date": "Nov-18",
                  "Usage": "31931",
                  "Accrual": "0",
                  "Total Change": "10398"
                },
				<?php } if(count($c7list)==0 or in_array(7,$c7list)){ ?>
				{
                  "Date": "Dec-18",
                  "Usage": "41931",
                  "Accrual": "0",
                  "Total Change": "11398"
                },
				<?php } if(count($c7list)==0 or in_array(9,$c7list)){ ?>
				{
                  "Date": "Jan-19",
                  "Usage": "31931",
                  "Accrual": "0",
                  "Total Change": "21398"
                },
				<?php } if(count($c7list)==0 or in_array(1,$c7list)){ ?>
				{
                  "Date": "Feb-19",
                  "Usage": "36931",
                  "Accrual": "0",
                  "Total Change": "13398"
                },
				<?php } if(count($c7list)==0 or in_array(3,$c7list)){ ?>
				{
                  "Date": "Mar-19",
                  "Usage": "25931",
                  "Accrual": "0",
                  "Total Change": "13398"
                },
				<?php } if(count($c7list)==0 or in_array(5,$c7list)){ ?>
				{
                  "Date": "Apr-19",
                  "Usage": "25931",
                  "Accrual": "0",
                  "Total Change": "23398"
                },
				<?php } if(count($c7list)==0 or in_array(7,$c7list)){ ?>
				{
                  "Date": "May-19",
                  "Usage": "24931",
                  "Accrual": "0",
                  "Total Change": "22398"
                },
				<?php } if(count($c7list)==0 or in_array(9,$c7list)){ ?>
				{
                  "Date": "Jun-19",
                  "Usage": "24631",
                  "Accrual": "0",
                  "Total Change": "12898"
                },
				<?php } if(count($c7list)==0 or in_array(1,$c7list)){ ?>
				{
                  "Date": "Jul-19",
                  "Usage": "24231",
                  "Accrual": "0",
                  "Total Change": "12698"
                },
				<?php } if(count($c7list)==0 or in_array(3,$c7list)){ ?>
				{
                  "Date": "Aug-19",
                  "Usage": "24331",
                  "Accrual": "0",
                  "Total Change": "22798"
                },
				<?php } if(count($c7list)==0 or in_array(5,$c7list)){ ?>
				{
                  "Date": "Sep-19",
                  "Usage": "24341",
                  "Accrual": "9791",
                  "Total Change": "22998"
                }
				<?php }  */ ?>
];
// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "Date";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
//valueAxis.numberFormatter.numberFormat = "$#,###|-$#,##s";
valueAxis.numberFormatter.numberFormat = "#,###|-#,##s";
valueAxis.title = "Usage";
//valueAxis.max = 50000;
// Create series
function createSeries(field, name, scolor) {

  // Set up series
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.name = name;
  series.dataFields.valueY = field;
  series.dataFields.categoryX = "Date";
  series.strokeWidth=0;
  series.sequencedInterpolation = true;
  series.columns.template.fill = am4core.color(scolor);
  series.stacked = true;
  series.columns.template.width = am4core.percent(60);
  series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
  var labelBullet = series.bullets.push(new am4charts.LabelBullet());
  labelBullet.locationY = 0.5;
}
//categoryAxis.renderer.grid.template.disabled = true;
//valueAxis.renderer.grid.template.disabled = true;
valueAxis.renderer.ticks.template.disabled = false;
valueAxis.renderer.ticks.template.strokeOpacity = 0.3;
valueAxis.renderer.ticks.template.stroke = am4core.color("#333");
valueAxis.renderer.ticks.template.strokeWidth = 1;
valueAxis.renderer.ticks.template.length = 10;

categoryAxis.renderer.ticks.template.disabled = false;
categoryAxis.renderer.ticks.template.strokeOpacity = 0.3;
categoryAxis.renderer.ticks.template.stroke = am4core.color("#333");
categoryAxis.renderer.ticks.template.strokeWidth = 1;
categoryAxis.renderer.ticks.template.length = 10;

createSeries("Actual Usage", "Actual Usage","#003a45");
createSeries("Accrued Usage", "Accrued Usage","#70903b");
var lineSeries = chart.series.push(new am4charts.LineSeries());
lineSeries.name = "Adj Prior Usage";
lineSeries.dataFields.valueY = "Adj Prior Usage";
lineSeries.dataFields.categoryX = "Date";
lineSeries.stroke = am4core.color("#fdd400");
lineSeries.strokeWidth = 3;
lineSeries.tooltip.label.textAlign = "middle";
lineSeries.zIndex=1;
var bullet = lineSeries.bullets.push(new am4charts.Bullet());
bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
bullet.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
var circle = bullet.createChild(am4core.Circle);
circle.radius = 2;
circle.strokeWidth = 3;
// Legend
chart.legend = new am4charts.Legend();
chart.legend.position = "top";
chart.legend.align = "right";
chart.legend.contentAlign = "right";
//chart.zoomOutButton.disabled = true;
});
/*
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
});*/
window.addEventListener("load", function(){
	try
	{
		document.querySelector('g[aria-labelledby="id-47-title"]').style.display = "none";
	}
	catch(e)
	{
	}
	try
	{
		document.querySelector('g[aria-labelledby="id-65-title"]').style.display = "none";
	}
	catch(e)
	{
	}
});
</script>
<h4 class="colorccc bmargin">Usage In <?php if(strtolower($sec8_servicetype)=="electric"){$mtype="(kWh)"; }elseif(strtolower($sec8_servicetype)=="natural gas"){$mtype="(Therms)";}else $mtype="";  echo @ucwords(@strtolower($sec8_servicetype)).$mtype; ?>&nbsp;<span class="glyphicon glyphicon-info-sign" id="infochart8"></span></h4>
<div id="chartdiv"></div>
<?php
		}
   }
}else if(isset($_GET["section888"])){
?>
<style>
#chartdiv {
	width	: 100%;
	height	: 350px;
}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/xy.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>



<div id="chartdiv"></div>
<script>
// SVG images are only temporarily hosted here for the sake of the demo
// download these great icons from
/*
https://www.google.co.id/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#safe=off&q=nucleo%20social%20icons
*/

var dataProvider = [ {
    "y": 40,
    "x": 14000,
    "value": 59,
    "bullet": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/arrowdown.png",
	"scolor":"#a44b54",
  "title": "Facebook"
  }, {
    "y": 80,
    "x": 34000,
    "value": 50,
    "bullet": "",
	"scolor":"#c5cac8",
  "title": "Twitter"
  }, {
    "y": 60,
    "x": 25000,
    "value": 19,
    "bullet": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/arrowup.png",
	"scolor":"#7a8e54",
  "title": "Pinterest"
  }, {
    "y": 75,
    "x": 55000,
    "value": 65,
    "bullet": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/arrowdown.png",
	"scolor":"#a44b54",
  "title": "Google+"
  }, {
    "y": 55,
    "x": 30000,
    "value": 92,
    "bullet": "",
	"scolor":"#c5cac8",
  "title": "WhatsApp"
  }, {
    "y": 90,
    "x": 40000,
    "value": 8,
    "bullet": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/arrowup.png",
	"scolor":"#7a8e54",
  "title": "Instagram"
  }, {
    "y": 35,
    "x": 20000,
    "value": 35,
    "bullet": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/arrowdown.png",
	"scolor":"#a44b54",
  "title": "LinkedIn"
  } ];

var chart = AmCharts.makeChart( "chartdiv", {
  "type": "xy",
  "balloon":{
   "fixedPosition":true,
  },
  "dataProvider": dataProvider,
  "valueAxes": [ {
    "position": "bottom",
    "axisAlpha": 0
  }, {
    "minMaxMultiplier": 1.2,
    "axisAlpha": 0,
    "position": "left"
  }],
  "startDuration": 1.5,
  "graphs": [{
    //"balloonText": "[[title]]: <b>[[value]]</b>",
    // start with just the circle...
	"showBalloon": false,
    "bullet": "circle",
    "bulletBorderAlpha": 0.2,
    "bulletAlpha": 0.8,
    "lineAlpha": 0,
    "fillAlphas": 0,
    "valueField": "value",
    "xField": "x",
    "yField": "y",
    "minBulletSize": 30,
    "maxBulletSize": 30,
    "colorField": "scolor", // add this for colors based on value of bubble
  }, {
    // start with just the circle...
    "showBalloon": false,
    "bullet": "custom",
    "customBulletField": "bullet",
    //"balloonText": "ad: <b>4</b>",
    "maxBulletSize": 20,
    "minBulletSize": 20,
    "bulletBorderAlpha": 0.2,
    "bulletAlpha": 0.8,
    "lineAlpha": 0,
    "fillAlphas": 0,
    "valueField": "value",
    "xField": "x",
    "yField": "y",
    "colorField": "color", // add this for colors based on value of bubble
  }],
  "marginLeft": 46,
  "marginBottom": 35,
  "export": {
    "enabled": false
  },
  "addClassNames": true,
} );
chart.events.on("ready", function(ev) {
  valueAxis.min = 0;
});
</script>
<?php
}

function adjustpolarity($value){//return $value;
	global $formatter;
	//if($value < 0) return $value= "-$".($value * -1);
	if($value < 0) return $value= "-".$formatter->formatCurrency(($value * -1), 'USD');
	else return $formatter->formatCurrency($value, 'USD');
}

function adjustpolaritynumber($value){//return $value;
	global $formatter;
	$value=(float)$value;
	//if($value < 0) return $value= "-$".($value * -1);
	if($value < 0) return preg_replace("/\.[0-9]*/","",str_replace("$","","-".$formatter->formatCurrency(($value * -1), 'USD')));
	else return preg_replace("/\.[0-9]*/","",str_replace("$","",$formatter->formatCurrency($value, 'USD')));
}

function formatMoney($number, $fractional=false) {return $number;
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
}
?>
