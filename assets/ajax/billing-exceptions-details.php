<?php //require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];
$c_id=$_SESSION["company_id"];
$user_one=28;

if(isset($_GET["action"]) and @trim($_GET["action"]) =="cid"){
	//("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.user_id=".$user_one." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)"))
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<?php
	$be_arr=$firstdatearr=$lastdatearr=array();
	$startdatelimit=$firstdate=$lastdate="";
	if ($fdstmt = $mysqli->prepare("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e where e.`Customer ID`=".$c_id." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)")) {

//("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.id=".$user_one." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)"))

        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($be_value,$be_date);
			while($fdstmt->fetch()){
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				$be_value=($be_value==""?0:$be_value);
				$be_arr[]='{"date": "'.$be_date.'","value": '.$be_value.'}';
				if(empty($startdatelimit)) $startdatelimit=$be_date;
				$lastdate=$be_date;
			}
		}
	}else{
		@error_log("billingexceptionsdetails fdstmt sql error", 0);
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//SELECT count(id),date(`EST Date`) FROM `exceptions` where `Customer ID`=10 and `Customer #`=315 group BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)
	if(!count($be_arr)) die("No data to show!");
	if(!empty($lastdate)){ $firstdate= @date('Y-m-d', @strtotime($lastdate.' -1 year'));$lastdate= @date('Y-m-d', @strtotime($lastdate)); }
	
	$datef = strtotime($firstdate);
	$dates = strtotime($startdatelimit);
	if($datef < $dates){ $firstdate= $startdatelimit; }
	$firstdatearr=explode("-",$firstdate);
	$lastdatearr=explode("-",$lastdate);
	if(count($firstdatearr) != 3 or count($lastdatearr) != 3) die();
?>
<style>
body ,tspan {
  font-family: "Open Sans",Arial,Helvetica,Sans-Serif !important;
  font-size:13px  !important;
  color: #333  !important;
}
#fchartdiv {
	width	: 100%;
	height	: 100%;
}
g[aria-labelledby=id-47-title] {display:none !important;}
</style>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<div id="fchartdiv"></div>
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

var chart = am4core.create("fchartdiv", am4charts.XYChart);

var data = [];

//chart.data = data;
chart.data =  [
<?php echo implode(",",$be_arr);?>
	];

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.minGridDistance = 60;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "value";
series.dataFields.dateX = "date";
series.tooltipText = "{value}"



chart.cursor = new am4charts.XYCursor();
chart.cursor.snapToSeries = series;
chart.cursor.xAxis = dateAxis;

//chart.scrollbarY = new am4core.Scrollbar();
chart.scrollbarX = new am4core.Scrollbar();
chart.events.on("ready", function () {
  dateAxis.zoomToDates(
    new Date(<?php echo $firstdatearr[0].", ".($firstdatearr[1]-1).", ".($firstdatearr[2]-1); ?>),
    new Date(<?php echo $lastdatearr[0].", ".($lastdatearr[1]-1).", ".($lastdatearr[2]-1); ?>)
  );

	chart.scrollbarX.events.on("rangechanged", function(event) {
		var start = chart.scrollbarX.range.start;
		var end = chart.scrollbarX.range.end;
		
		// Convert the start and end values to dates
		var startDate = dateAxis.positionToDate(start);
		var endDate = dateAxis.positionToDate(end);
		
		// Format the dates
		//var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
		
		var sdate=startDate.getFullYear()+"-"+startDate.getMonth()+"-1";
		var edate=endDate.getFullYear()+"-"+endDate.getMonth()+"-31";
		//alert(startDate.getFullYear()+"-"+(startDate.getMonth()+1)+"-1" + "\nEnd Date: " + endDate.getFullYear()+"-"+(endDate.getMonth()+1)+"-28");
		//parent.document.getElementById('frame').src ="https://localhost/assets/ajax/billing-exceptions-details.php?action=mid&cnt=50";
		window.parent.document.getElementById("frame").src ="<?php echo 'https://'.$_SERVER['HTTP_HOST']; ?>/assets/ajax/billing-exceptions-details.php?action=mid&cnt=50&sdate="+sdate+"&edate="+edate;
	});
});
window.addEventListener("load", function(){
	try
	{
		document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
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
}else if(isset($_GET["action"]) and @trim($_GET["action"]) =="mid"){
	//$mid=$mysqli->real_escape_string(@trim($_GET["mid"]));

	$be_arr=$firstdatearr=$lastdatearr=array();
	$startdatelimit=$firstdate=$lastdate="";
//"SELECT count(e.id),DATE_FORMAT(`EST Date`, '%b %Y') FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.user_id=".$user_one." GROUP BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)"
	//if ($fdstmt = $mysqli->prepare("SELECT count(e.id),DATE_FORMAT(`EST Date`, '%b %y') FROM `exceptions` e where e.`Customer ID`=".$c_id." GROUP BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)")) {
	//if ($fdstmt = $mysqli->prepare("SELECT count(e.id),DATE_FORMAT(`EST Date`, '%Y%m00') FROM `exceptions` e where e.`Customer ID`=".$c_id." GROUP BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)")) {
	if ($fdstmt = $mysqli->prepare("SELECT count(e.id),DATE_FORMAT(`EST Date`, '%Y-%m-01') FROM `exceptions` e where e.`Customer ID`=".$c_id." GROUP BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($be_value,$be_date);
			while($fdstmt->fetch()){
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				$be_value=($be_value==""?0:$be_value);
				$be_arr[]='{"date": "'.$be_date.'","value": '.$be_value.'}';
				if(empty($startdatelimit)) $startdatelimit=$be_date;
				$lastdate=$be_date;
			}
		}//print_r($be_arr);
	}else{
		@error_log("billingexceptionsdetails select from exceptions sql error", 0);
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//SELECT count(id),date(`EST Date`) FROM `exceptions` where `Customer ID`=10 and `Customer #`=315 group BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)
	if(!count($be_arr)) die("No data to show!");
	
$sdate=$edate="";	
if(isset($_GET["sdate"]) and isset($_GET["edate"])){
	if(!empty(@trim($_GET["sdate"])) and !empty(@trim($_GET["edate"]))){
		$sdate=@trim($_GET["sdate"]);
		$sdate=date("Y-m-d", strtotime('+1 month', strtotime($sdate)));
		$edate=@trim($_GET["edate"]);
		$edate=date("Y-m-d", strtotime('+1 month', strtotime($edate)));
	}
}else{
	if(!empty($lastdate)){ $firstdate= @date('Y-m-d', @strtotime($lastdate.' -1 year'));$lastdate= @date('Y-m-d', @strtotime($lastdate)); }
	
	$datef = strtotime($firstdate);
	$dates = strtotime($startdatelimit);
	if($datef < $dates){ $firstdate= $startdatelimit; }
	$firstdatearr=explode("-",$firstdate);
	$lastdatearr=explode("-",$lastdate);
	if(count($firstdatearr) != 3 or count($lastdatearr) != 3) die();
	$sdate=$firstdatearr[0]."-".$firstdatearr[1]."-".$firstdatearr[2];
	$edate=$lastdatearr[0]."-".$lastdatearr[1]."-31";	
}
?>
<!-- Styles -->
<style>
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

#chartdiv {
	width: 100%;
	height: 100%;
}
.amcharts-chart-div > a {
    display: none !important;
}
.amcharts-Sprite-group amcharts-Scrollbar-horizontal {
    display: none !important;
}
[role="scrollbar"] {
    display: none !important;
}
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- HTML -->
<div id="chartdiv"></div>
<!-- Chart code -->
<script>
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
chart.data = [<?php echo implode(",",$be_arr);?>];

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        //dateAxis.title.text = "Date";
		dateAxis.dataFields.category = "date";
		dateAxis.renderer.grid.template.location = 0;
		dateAxis.renderer.minGridDistance = 30;
		dateAxis.renderer.labels.template.horizontalCenter = "right";
		dateAxis.renderer.labels.template.verticalCenter = "middle";
		dateAxis.renderer.labels.template.rotation = 290;
		dateAxis.tooltip.disabled = true;
		dateAxis.dateFormats.setKey("day", "MMM YY");
		
		/*dateAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {return dy;
		  if (target.dataItem && target.dataItem.index & 2 == 2) {
			return dy + 25;
		  }
		  return dy;
		});	*/


		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;

				// Create series
		var series = chart.series.push(new am4charts.ColumnSeries());
		series.dataFields.valueY = "value";
		series.dataFields.dateX = "date";
		series.name = "Visits";
		series.columns.template.tooltipText = "{dateX.formatDate('MMM yy')}: [bold]{valueY}[/]";
		series.columns.template.fillOpacity = 0.8;
		
		var columnTemplate = series.columns.template;
		columnTemplate.strokeWidth = 2;
		columnTemplate.strokeOpacity = 1;
<?php if(!empty($sdate) and !empty($edate)){ ?>
        // Add chart cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "zoomX";

        // Add scrollbar
        chart.scrollbarX = new am4core.Scrollbar();

		
        // Zoom to a specific date range
        dateAxis.events.on("ready", function(ev) {
            dateAxis.zoomToDates(
                new Date("<?php echo $sdate; ?>"), // Year, Month (0-based), Day
                new Date("<?php echo $edate; ?>")  // Year, Month (0-based), Day
            );
        });
<?php } ?>
window.addEventListener("load", function(){
	try
	{
		document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
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
?>
