<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(isset($_GET["action"]) and $_GET["action"]=="chart" and isset($_GET["cid"]) and !empty($_GET["cid"])){
	$sid=$_GET["cid"];
	$symlist=$symarray=array();

	$sid=$_GET["cid"];

	$symlist=$symarray=$tpcchartarr=$temparrr=array();
	$tmpsql=$chart_name="";
	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $tmpsql=" and user_id=".$user_one;
	$sql="SELECT id,symbol_list,chart_name FROM ubm_ice.portfolio WHERE id=".$sid.$tmpsql;
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($sid,$symbol_list,$chart_name);
			$stmt->fetch();
			$temparrr=explode(",",$symbol_list);
			if(count($temparrr)){
				$tmmmarr=array();
				foreach($temparrr as $vll){
					$symarray[]=explode("@",$vll);
					//$tmmmarr=explode("@",$vll);
					//$symarray[]=array(str_replace(" ","",$tmmmarr[0]),$tmmmarr[1]);
				}
			}
			//$symarray[]=array("ICUS","BTC");
		}
	}

	if(count($symarray)==0) die("Error Occured. Please try after sometimes!");

	$year = date("Y");
	$fyear = date("Y",strtotime("+1 year"));;
	$month=date("n");
	$fmonth=date("n",strtotime("-1 month"));

	$ctt=count($symarray);
	$tempsubarr1=$tempsubarr2=$temparrf=array();

	foreach($symarray as $kyys=>$vlls){
		$tmp_startdate=$vlls[4];
		$tmp_enddate=$vlls[5];
		$subenddate=date("Y-m-",strtotime($tmp_enddate));

		//For Test
		$tmp_startdate="2017-01-01";
		//

		$tempsubarr1[]="SELECT ".($kyys+1)." AS ID, 'ERCOT Off-Peak 12 month strip' AS graphpoint, a.contract_date,b.date,b.settlement FROM ubm_ice.clearing_code_index a
JOIN ubm_ice.AR_MWIS b ON a.`code`=b.`code`
WHERE ((a.clearing_code='".$vlls[1]."') AND (a.`contract_date` BETWEEN DATE('".$tmp_startdate."') AND DATE(CONCAT('".$subenddate."', DAY(LAST_DAY(DATE('".$tmp_enddate."')))))))";

	$tempsubarr2[]="SELECT ".($ctt+1)." AS ID, 'ERCOT Off-Peak 24 month strip' AS graphpoint, a.contract_date,b.date,b.settlement FROM ubm_ice.clearing_code_index a
	JOIN ubm_ice.AR_MWIS b ON a.`code`=b.`code`
	WHERE ((a.clearing_code='".$vlls[1]."') AND (a.`contract_date` BETWEEN DATE('".$tmp_startdate."') AND DATE(CONCAT('".$subenddate."', DAY(LAST_DAY(DATE('".$tmp_enddate."')))))))";
			$ctt++;
		}
//////////////////////
$tempsubarr2[]=array();

		/*$sqltpcc="SELECT e.ID, e.graphpoint, e.date, SUM(e.sumproduct)/SUM(e.`usage`) AS strip_settlement FROM (
	SELECT c.ID, c.graphpoint, c.contract_date, c.date, c.settlement, d.`usage`, c.settlement*d.`usage` AS sumproduct
	FROM (

	".implode(" UNION ALL ",$tempsubarr1)." ".($ctt > 0? " UNION ALL ":"")." ".implode(" UNION ALL ",$tempsubarr2)."


	) c LEFT JOIN ICE.sample_usage d ON MONTH(d.`Month`)=MONTH(c.contract_date)
	WHERE (c.`date` BETWEEN DATE(CONCAT(2017,'-', 1, '-1')) AND DATE(CONCAT(YEAR(NOW()), '-',MONTH(NOW()),'-', DAY(NOW()))))

	 ) e GROUP BY e.ID, e.graphpoint, e.date";*/

	$sqltpcc="SELECT e.ID, e.graphpoint, e.date, SUM(e.sumproduct)/SUM(e.`usage`) AS strip_settlement FROM (
	SELECT c.ID, c.graphpoint, c.contract_date, c.date, c.settlement, d.`usage`, c.settlement*d.`usage` AS sumproduct
	FROM (

	".implode(" UNION ALL ",$tempsubarr1)."


	) c LEFT JOIN ubm_ice.sample_usage d ON MONTH(d.`Month`)=MONTH(c.contract_date)
	WHERE (c.`date` BETWEEN DATE(CONCAT(2017,'-', 1, '-1')) AND DATE(CONCAT(YEAR(NOW()), '-',MONTH(NOW()),'-', DAY(NOW()))))

	 ) e GROUP BY e.ID, e.graphpoint, e.date";

		if($tpccstmt = $mysqli->prepare($sqltpcc)) {
			$tpccstmt->execute();
			$tpccstmt->store_result();
			if($tpccstmt->num_rows > 0) {
				$tpccstmt->bind_result($tpccid,$tpccgraphpoint,$tpccdate,$tpccstrip_settlement);
				while($tpccstmt->fetch()){
					$temparrf[''.$tpccdate.''][str_replace(" ","_",$tpccid.' '.$tpccgraphpoint)]=$tpccstrip_settlement;
				}
			}
		}else die($mysqli -> error);
	//print_r($temparrf);die();
	$symlist=$temparrrr=array();
	foreach($temparrf as $kky=>$vvl){
		$tmppparr=array();//echo $kky;print_r($vvl);
		foreach($vvl as $kkkvv=>$vvvkk){
			$tmppparr[]='"'.$kkkvv.'":'.(float)$vvvkk.'';
			//$tmppparr[]=$vvvkk;
			if(!in_array($kkkvv,$symlist)){$symlist[]= $kkkvv;};
			//if(!in_array($vvvkk,$symlist)){$symlist[]= $vvvkk;};
		}

		//$datetmp = new DateTime($kky);

		$datestrtime=strtotime($kky);
		$tmpyear=date('Y', $datestrtime);
		$tmpmonth=date('m', $datestrtime);
		$tmpdat=date('d', $datestrtime);


		$temparrrr[]= '{"Volume":0,"Date":new Date('.$tmpyear.', '.$tmpmonth.', '.$tmpdat.'),'.implode(",",$tmppparr).'}';
		//$temparrrr[]= '{"Volume":0,"Date":'.$datetmp->getTimestamp().','.implode(",",$tmppparr).'}';
		//$temparrrr[]= '{"Volume":0,"Date":"'.$kky.'",'.implode(",",$tmppparr).'}';







		//$temparrrr[]= '{"Volume":0,"Date":"'.$kky.'",'.implode(",",$tmppparr).'}';
		//echo '<script>data.push({ date: "'.$kky.'", '.implode(",",$tmppparr).', quantity: 1 });</script>';

	}

	//echo 'date,'.implode(',',$tmppparr);
//print_r($symlist);print_r($temparrrr);
//data.push({ date: new Date(2000, 0, i), price1: price1, price2:price2, price3:price3, quantity: quantity });
?>
<style>
body { background-color: #30303d; color: #fff;font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px; }
#chartdiv {
  width: 100%;
  height: 350px;
  max-width: 100%;
}
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/plugins/rangeSelector.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/dark.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dark);
am4core.useTheme(am4themes_animated);
// Themes end
am4core.options.onlyShowOnViewport = true;
// Create chart
var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.padding(0, 15, 0, 15);
chart.preloader.disabled = true;
chart.logo.disabled=true;


chart.data = [
<?php if(isset($temparrrr) and count($temparrrr)) echo implode(',',$temparrrr); ?>
];

// Load external data
/*chart.dataSource.url = "test.csv";
chart.dataSource.parser = new am4core.CSVParser();
chart.dataSource.parser.options.useColumnNames = true;
chart.dataSource.parser.options.reverse = true;*/

// the following line makes value axes to be arranged vertically.
chart.leftAxesContainer.layout = "vertical";

// uncomment this line if you want to change order of axes
//chart.bottomAxesContainer.reverseOrder = true;

var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;
dateAxis.renderer.ticks.template.length = 8;
dateAxis.renderer.ticks.template.strokeOpacity = 0.1;
dateAxis.renderer.grid.template.disabled = true;
dateAxis.renderer.ticks.template.disabled = false;
dateAxis.renderer.ticks.template.strokeOpacity = 0.2;
dateAxis.renderer.minLabelPosition = 0.01;
dateAxis.renderer.maxLabelPosition = 0.99;
dateAxis.keepSelection = true;
dateAxis.minHeight = 30;

dateAxis.groupData = true;
dateAxis.minZoomCount = 5;

// these two lines makes the axis to be initially zoomed-in
// dateAxis.start = 0.7;
// dateAxis.keepSelection = true;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.tooltip.disabled = true;
valueAxis.zIndex = 1;
valueAxis.renderer.baseGrid.disabled = true;
// height of axis
valueAxis.height = am4core.percent(65);

valueAxis.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis.renderer.gridContainer.background.fillOpacity = 0.05;
valueAxis.renderer.inside = true;
valueAxis.renderer.labels.template.verticalCenter = "bottom";
valueAxis.renderer.labels.template.padding(2, 2, 2, 2);

//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis.renderer.fontSize = "0.8em"

<?php
if(isset($symlist) and count($symlist)){
	foreach($symlist as $kyty=>$vtvl){ ?>
var seriess<?php echo $kyty; ?> = chart.series.push(new am4charts.LineSeries());
seriess<?php echo $kyty; ?>.dataFields.dateX = "Date";
seriess<?php echo $kyty; ?>.dataFields.valueY = "<?php echo $vtvl; ?>";
seriess<?php echo $kyty; ?>.tooltipText = "<?php echo $symarray[$kyty][3]; ?> {valueY.value}";
//seriess<?php /*echo $kyty;*/ ?>.name = "MSFT: <?php echo $vtvl; ?>";
seriess<?php echo $kyty; ?>.name = "<?php echo $symarray[$kyty][3]; ?>";
seriess<?php echo $kyty; ?>.defaultState.transitionDuration = 0;
<?php	}
}
?>
/*var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.dateX = "Date";
series.dataFields.valueY = "Open";
series.tooltipText = "{valueY.value}";
series.name = "MSFT: Value";
series.defaultState.transitionDuration = 0;

var seriess = chart.series.push(new am4charts.LineSeries());
seriess.dataFields.dateX = "Date";
seriess.dataFields.valueY = "High";
seriess.tooltipText = "{valueY.value}";
seriess.name = "MSFT: High";
seriess.defaultState.transitionDuration = 0;
*/
var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.tooltip.disabled = true;
// height of axis
valueAxis2.height = am4core.percent(35);
valueAxis2.zIndex = 3
// this makes gap between panels
valueAxis2.marginTop = 30;
valueAxis2.renderer.baseGrid.disabled = true;
valueAxis2.renderer.inside = true;
valueAxis2.renderer.labels.template.verticalCenter = "bottom";
valueAxis2.renderer.labels.template.padding(2, 2, 2, 2);
//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis2.renderer.fontSize = "0.8em"

valueAxis2.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis2.renderer.gridContainer.background.fillOpacity = 0.05;

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.dateX = "Date";
series2.dataFields.valueY = "Volume";
series2.yAxis = valueAxis2;
series2.tooltipText = "{valueY.value}";
//series2.name = "MSFT: Volume";
series2.name = "Volume";
// volume should be summed
series2.groupFields.valueY = "sum";
series2.defaultState.transitionDuration = 0;
<?php if(isset($_GET["vol"]) and $_GET["vol"]==1){}else{ ?>
series2.hide();
series2.hidden = true;
<?php } ?>
series2.hiddenInLegend = true;
//series2.disabled = true;

chart.cursor = new am4charts.XYCursor();
/*var title = chart.titles.create();
title.text = "<?php echo $chart_name; ?>";
title.fontSize = 25;
title.marginBottom = 30;*/
/*
var scrollbarX = new am4charts.XYChartScrollbar();
<?php
//if(isset($symlist) and count($symlist)){
	//foreach($symlist as $kyty=>$vtvl){ ?>
scrollbarX.series.push(seriess1);
<?php	//}
//}
?>
scrollbarX.marginBottom = 20;
scrollbarX.scrollbarChart.xAxes.getIndex(0).minHeight = undefined;
chart.scrollbarX = scrollbarX;
*/

// Add range selector
var selector = new am4plugins_rangeSelector.DateAxisRangeSelector();
selector.container = document.getElementById("controls");
selector.axis = dateAxis;
selector.position = "bottom";

chart.exporting.menu = new am4core.ExportMenu();
chart.exporting.menu.items = [{
  "label": "Export",
  "menu": [
    { "type": "png", "label": "PNG" }
  ]
}];
chart.exporting.filePrefix = "<?php echo $chart_name; ?>";
/*function exportPNG() {
  chart.exporting.export("png");
}

var options = chart.exporting.getFormatOptions("png");
options.keepTainted = true;
chart.exporting.setFormatOptions("png", options);*/

chart.legend = new am4charts.Legend();

function showhidevolume(){
	if (series2.isHiding || series2.isHidden) {
	series2.show();
	} else {
		series2.hide();
	}
 }
}); // end am4core.ready()
</script>

<!-- HTML -->
<div id="controls"></div>
<div id="chartdiv"></div>
<div style="text-align:center;margin-top:12px;"><button id="editportfolio" sid="<?php echo $sid; ?>" style="line-height:1.5;cursor:pointer;">Edit</button></div>
<script>
$( document ).ready(function() {
	$(document).off("click","#editportfolio");
	$(document).on("click","#editportfolio",function() {
		parent.$('#tpresponse').html('');
		parent.$("#tpresponse2").css("display", "none");
		parent.$("#tpchartcont2").css("display", "none");
		parent.$("#tpresponse3").css("display", "none");
		parent.$("#tpchartcont3").css("display", "none");
		parent.$("#tpresponse").css("display", "block");
		parent.$("#tpchartcont").css("display", "block");
		//loadURL("assets/ajax/subpages/trading-platform-1.php?action=symbollist&sid=<?php echo $sid; ?>", $('#tpresponse'));
		parent.$( "#tpresponse" ).load( "assets/ajax/trading-platform-1.php?action=symbollist&sid=<?php echo $sid; ?>" );
	});
});
</script>

<?php } ?>
