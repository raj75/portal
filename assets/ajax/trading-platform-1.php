<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
//if($user_one != 1) die("under construction!");

///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
if(isset($_GET["action"]) and $_GET["action"]=="chart"){
	$symlist=$symarray=$tpcchartarr=$temparrr=array();

	if(isset($_GET["sym"]) and !empty($_GET["sym"])){
		$symlist=explode(",",$_GET["sym"]);
		foreach($symlist as $vl){
			$symarray[]=explode("@",$vl);
		}
	}//else $symarray[]=array("ICUS","BTC");
	//$symarray[]=array("ICUS","BTC");
	if(count($symarray)==0) die();//die("Error Occured. Please try after sometimes!");

	$tmpsql=$chart_name="";

	//$year = date("Y");
	//$fyear = date("Y",strtotime("+1 year"));;
	//$month=date("n");
	//$fmonth=date("n",strtotime("-1 month"));

	$ctt=count($symarray);
	$tempsubarr1=$tempsubarr2=$temparrf=array();

	foreach($symarray as $kyys=>$vlls){
		if(!isset($vlls[4])) continue;
		$tmp_startdate=$vlls[4];
		$tmp_enddate=$vlls[5];
		$subenddate=date("Y-m-",strtotime($tmp_enddate));

		//For Test
		//$tmp_startdate="2017-01-01";
		//

		$tempsubarr1[]="SELECT ".($kyys+1)." AS ID, 'ERCOT Off-Peak 12 month strip' AS graphpoint, a.contract_date,b.date,b.settlement FROM ICE.clearing_code_index a
		JOIN ICE.AR_MWIS b ON a.`code`=b.`code`
		WHERE ((a.clearing_code='".$vlls[1]."') AND (a.`contract_date` BETWEEN DATE('".$tmp_startdate."') AND DATE(CONCAT('".$subenddate."', DAY(LAST_DAY(DATE('".$tmp_enddate."')))))))";

		$tempsubarr2[]="SELECT ".($ctt+1)." AS ID, 'ERCOT Off-Peak 24 month strip' AS graphpoint, a.contract_date,b.date,b.settlement FROM ICE.clearing_code_index a
		JOIN ICE.AR_MWIS b ON a.`code`=b.`code`
		WHERE ((a.clearing_code='".$vlls[1]."') AND (a.`contract_date` BETWEEN DATE('".$tmp_startdate."') AND DATE(CONCAT('".$subenddate."', DAY(LAST_DAY(DATE('".$tmp_enddate."')))))))";
			$ctt++;
		}
		////////Temp Fix
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


	) c LEFT JOIN ICE.sample_usage d ON MONTH(d.`Month`)=MONTH(c.contract_date)
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
		$tmppparr=array();
		foreach($vvl as $kkkvv=>$vvvkk){
			$tmppparr[]='"'.$kkkvv.'":'.(float)$vvvkk.'';
			//$tmppparr[]=$vvvkk;
			if(!in_array($kkkvv,$symlist)){$symlist[]= $kkkvv;};
			//if(!in_array($vvvkk,$symlist)){$symlist[]= $vvvkk;};
		}
		$temparrrr[]= '{"Volume":0,"Date":"'.$kky.'",'.implode(",",$tmppparr).'}';
	}
?>
<style>
body { font-family: "Open Sans",Arial,Helvetica,Sans-Serif; font-size: 13px; background-color: #30303d; color: #fff; }
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

// Create chart
var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.padding(0, 15, 0, 15);
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
valueAxis.renderer.fontSize = "0.8em";

<?php
if(isset($symlist) and count($symlist)){
	foreach($symlist as $kyty=>$vtvl){ ?>
var seriess<?php echo $kyty; ?> = chart.series.push(new am4charts.LineSeries());
seriess<?php echo $kyty; ?>.dataFields.dateX = "Date";
seriess<?php echo $kyty; ?>.dataFields.valueY = "<?php echo $vtvl; ?>";
seriess<?php echo $kyty; ?>.tooltipText = "{valueY.value}";
//seriess<?php /*echo $kyty;*/ ?>.tooltipText = "<?php /*echo $symarray[$kyty][3];*/ ?> {valueY.value}";
//seriess<?php /*echo $kyty;*/ ?>.name = "MSFT: <?php /*echo $vtvl;*/ ?>";
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
//series2.name = "Volume";
// volume should be summed
series2.groupFields.valueY = "sum";
series2.defaultState.transitionDuration = 0;
series2.hide();
series2.hidden = true;
series2.hiddenInLegend = true;

chart.cursor = new am4charts.XYCursor();
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

chart.legend = new am4charts.Legend();
//////////Added later
$(document).on("click", "#shvolume", function() {
	if (series2.isHiding || series2.isHidden) {
	series2.show();
	} else {
		series2.hide();
	}
 });
}); // end am4core.ready()
</script>
<?php
$chartname="";

if(isset($symarray[0][4]) and isset($symarray[0][5])) $chartname=$symarray[0][4]." to ".$symarray[0][5];
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $tmpsql=" WHERE user_id=".$user_one;
//$sql="SELECT id,symbol_list,chart_name,sort FROM ICE.portfolio".$tmpsql." ORDER BY sort";
if(isset($_GET["sid"]) and !empty($_GET["sid"])){
	$sidd=$_GET["sid"];
	$sql="SELECT id,symbol_list,chart_name FROM ICE.portfolio WHERE id='".$mysqli->real_escape_string($sidd)."' and user_id=".$user_one;
	if($stmt = $mysqli->prepare($sql)) {
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows > 0) {
					$stmt->bind_result($sid,$symbol_list,$chart_name);
					$stmt->fetch();
					if(!empty($chart_name)) $chartname=$chart_name;
				}
	}
}
?>

<!-- HTML -->
<style>
.jarviswidget-color-blueDarkss>header {
    border-color: #45474b!important;
    background: #4c4f53;
    color: #fff;
}
#charth2{margin-left:5px;}
</style>
<div class="jarviswidget jarviswidget-color-blueDark listitemClass" id="chartno"  data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-colorbutton="false" data-widget-refreshbutton="false" data-widget-sortable="false" role="widget">
	<header>
		<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
		<p id="charth2">Chart Name: <input id="chartname" style="width:250px" type="text" value="<?php echo $chartname; ?>"><button id="shvolume" style="line-height:1.5;cursor:pointer;float:right;margin-right:12px;">Show/Hide Volume</button><button id="addportfolio" style="line-height:1.5;cursor:pointer;float:right;margin-right:12px;"><?php if(isset($_GET["sid"]) and !empty($_GET["sid"])){ echo "Update Portfolio"; }else echo "Add to Portfolio"; ?></button></p>
		<div class="jarviswidget-ctrls" role="menu">
			<a href="javascript:void(0);" class="button-icon toggleclose takescreenshot" rel="tooltip" title="Take Screenshot" did="chartid" dname="chartname" data-placement="bottom" data-original-title="Close"><i class="glyphicon glyphicon-print"></i></a>
		</div>
	</header>
	<div class="intcon">
		<!-- widget edit box -->
		<div class="jarviswidget-editbox">
			<!-- This area used as dropdown edit box -->

		</div>
		<!-- end widget edit box -->
		<div class="widget-body">
			<div id="controls"></div>
			<div id="chartdiv"></div>
			<div style="text-align:center;margin-top:12px;display:none;"><button id="addportfolio" style="line-height:1.5;cursor:pointer;"><?php if(isset($_GET["sid"]) and !empty($_GET["sid"])){ echo "Update Portfolio"; }else echo "Add to Portfolio"; ?></button></div>
	</div>
	</div>
	</div>
<script>
$( document ).ready(function() {
	$( "#addportfolio" ).click(function() {
	<?php if(count($symarray)){ ?>
		var chartname=$("#chartname").val();
		$.ajax({
			type: 'post',
			//url: '../../includes/tradingplatform.inc.php',
			url: '../includes/tradingplatform.inc.php',
			data: {uid:<?php echo $_SESSION["user_id"]; ?>,chartname:chartname,symlist:'<?php echo $_GET["sym"]; ?>',sid:'<?php if(isset($_GET["sid"]) and !empty($_GET["sid"])){ echo $_GET["sid"]; } ?>'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Added to portfolio!");
						//parent.$("#ftable").html('');
						//parent.$('#ftable').load('assets/ajax/futures_pedit.php');
					}else
						alert(results.error);
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		  });
	<?php }else{ ?>
		alert("Please add symbols!");
	<?php } ?>
	});
});
</script>
<?php


//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
}elseif(isset($_GET["action"]) and $_GET["action"]=="symbollist"){
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />-->
<link rel="stylesheet" media="all" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="assets/js/datePicker.js"></script>
<style>
#datatable_fixed_column_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column option {
  color: #555;
}
#datatable_fixed_column tr.dropdown select{font-weight: 400 !important;}
.red{color:red;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}

#datatable_fixed_column-sl_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}

#datatable_fixed_column-sl_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column-sl{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column-sl option {
  color: #555;
}
#datatable_fixed_column-sl tr.dropdown select{font-weight: 400 !important;}
.red{color:red;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}


#datatable_fixed_columntp1_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.5% auto !important;
}
#datatable_fixed_columntp1_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_columntp1{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_columntp1 .isodrp{width:auto !important;}
#datatable_fixed_columntp1 tr.dropdown select {
    font-weight: 400 !important;
}
.dataTables_processing{top: 73px !important;}
/* .selectsymtp1{text-align:center;width:100%;} */
.parentselectcont{position:relative;margin:0 !important;padding:0 !important;}
.parentselectcont .select2-container--default{position:absolute;zoom:unset !important;z-index:999}
/* #sslcontainer{padding:0 !important;margin:0 !important;width:100%;height:500px;} */
.textcenter{text-align: center !important;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Symbol List </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="tp1-list">
<table id="datatable_fixed_columntp1" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
			<th class="hasinput d-1">
				<input type="text" class="form-control" placeholder="Filter Description" />
			</th>
			<th class="hasinput d-2">
				<select class="form-control tpdrp tp2">
					<option value="">Filter Exchange</option>
<?php
	if ($stmt_exchange = $mysqli->prepare('SELECT DISTINCT exchange FROM ICE.clearing_code ORDER BY exchange')) {
        $stmt_exchange->execute();
        $stmt_exchange->store_result();
        if ($stmt_exchange->num_rows > 0) {
			$stmt_exchange->bind_result($dexchange);
			while($stmt_exchange->fetch()) {
				echo '<option value="'.$dexchange.'">'.$dexchange.'</option>';

			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
?>
				</select>
			</th>
			<th class="hasinput d-3">
				<input type="text" class="form-control" placeholder="Filter Symbol" />
			</th>
			<th class="hasinput d-4">
				<select class="form-control tpdrp tp4">
					<option value="">Filter Commodity</option>
<?php
if ($stmt_commodity = $mysqli->prepare('SELECT DISTINCT `GROUP` FROM ICE.clearing_code where `GROUP` != "" ORDER BY `GROUP`')) {
			$stmt_commodity->execute();
			$stmt_commodity->store_result();
			if ($stmt_commodity->num_rows > 0) {
		$stmt_commodity->bind_result($dGROUP);
		while($stmt_commodity->fetch()) {
			echo '<option value="'.$dGROUP.'">'.$dGROUP.'</option>';

		}
	}
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}
?>
			</select>
			</th>
			<th class="hasinput d-5">
				<select class="form-control tpdrp tp5">
					<option value="">Filter Status</option>
					<option value="Active">Active</option>
					<option value="Inactive">Inactive</option>
				</select>
			</th>
			<th class="hasinput d-6">
				<select class="form-control tpdrp tp6">
					<option value="">Filter Contract Type</option>
					<option value="Daily">Daily</option>
					<option value="Monthly">Monthly</option>
				</select>
			</th>
			<th class="hasinput d-7">
				<input type="text" class="form-control" placeholder="Filter Date Code Min" />
			</th>
			<th class="hasinput d-8">
				<input type="text" class="form-control" placeholder="Filter Date Code Max" />
			</th>
			<th class="hasinput d-9">
				<input type="text" class="form-control" placeholder="Filter Max Date" />
			</th>
			<th class="hasinput d-10">
				<input type="text" class="form-control" placeholder="Filter Contracts" />
			</th>
			<th class="hasinput d-11">
				<input type="text" class="form-control" placeholder="Filter Spot Contract" />
			</th>
			<th class="hasinput d-12">
				<input type="text" class="form-control" placeholder="Filter Spot Price" />
			</th>
			<th class="hasinput d-13">
				<input type="text" class="form-control" placeholder="Filter 12 Strip" />
			</th>
			<th class="hasinput d-14"></th>
		</tr>
		<tr>
			<th data-hide="phone">Description</th>
			<th data-hide="phone">Exchange</th>
			<th data-hide="phone">Symbol</th>
			<th data-hide="phone,tablet">Commodity</th>
			<th data-hide="phone,tablet">Status</th>
			<th data-hide="phone,tablet">Contract Type</th>
			<th data-hide="phone,tablet">Date Code Min</th>
			<th data-hide="phone,tablet">Date Code Max</th>
			<th data-hide="phone,tablet">Max Date</th>
			<th data-hide="phone,tablet">Contracts</th>
			<th data-hide="phone,tablet">Spot Contract</th>
			<th data-hide="phone,tablet">Spot Price</th>
			<th data-hide="phone,tablet">12 Strip</th>
			<th data-hide="phone,tablet">Action</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

<script src="../../assets/js/jquery.multiSelect.js" type="text/javascript"></script>


			<?php if(1==2){ ?><iframe id="sslcontainer" width="100%" height="500px"></iframe>
			<div id="sslcontainer" width="100%"></div><?php } ?>
			<input type="hidden" id="reloadval" value="">
			<!-- end widget div -->
			<!-- WIDGET END -->
			<!-- end widget -->
<section id="sslcontainer" class="m-top"></section>
<script src="assets/plugins/monthpicker/jquery.maskedinput.min.js"></script>
<!--<script src="assets/plugins/monthpicker/MonthPicker.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script type="text/javascript">
	pageSetUp();


	// pagefunction
	var pagefunction = function() {

		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

			var chartsymbol=[];

	    var otabletp1 = $('#datatable_fixed_columntp1').DataTable({
			"lengthMenu": [[6, 25, 50, 100, 500, -1], [6, 25, 50, 100, 500, "All"]],
			"pageLength": <?php if(isset($_GET["sid"])){ ?>6<?php }else{ ?>25<?php } ?>,
			"processing": true,
			"serverSide": true,
		"dom": 'Blfrtip',
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
		/*"columnDefs": [
			{ className: "switchcurrency", "targets": [ 4 ] },
			{ className: "switchcurrency", "targets": [ 5 ] },
			{ className: "switchcurrency", "targets": [ 6 ] },
			{ className: "switchcurrency", "targets": [ 7 ] }
		  ],*/
			"autoWidth" : true,
			"ajax": "assets/ajax/subpages/tp1_processing.php",
			initComplete: function () {
				/*this.api().columns([8]).every( function () {
					 var column = this;
					 var select = $('<select class="form-control" id="dstatid"><option value="">Filter Status</option></select>')
							.appendTo( $('#datatable_fixed_column .dropdown .d-10').empty() )
							.on( 'change', function () {
								 var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								 );
							column
								 .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								 .draw();
							} );
							var darr = [];
						 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
								if(jQuery.inArray(d, darr) == -1 && d != ""){
									if(d=='Active'){
										select.append( '<option value="'+d+'" SELECTED>'+d+'</option>' );
									}else{
										select.append( '<option value="'+d+'">'+d+'</option>' );
									}
									darr.push(d);
								}
						 } );
						 val='Active';
							column
								 .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								 .draw();
				} );*/
			}




			/*,
			initComplete: function () {
				this.api().columns([0]).every( function () {
					 var column = this;
					 var select = $('<select class="form-control"><option value="">Filter ISO</option></select>')
						  .appendTo( $('#datatable_fixed_columntp1 .dropdown .d-1').empty() )
						  .on( 'change', function () {
							   var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
							   );
						  column
							   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
							   .draw();
						  } );
						  var darr = [];
						 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
								if(jQuery.inArray(d, darr) == -1 && d != ""){
									select.append( '<option value="'+d+'">'+d+'</option>' );
									darr.push(d);
								}
						 } );
				} );
			}*/
	    });

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_columntp1 thead th input.form-control[type=text]").on( 'keyup change', function () {
	        otabletp1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );

			$("#datatable_fixed_columntp1 .tpdrp").on( 'keyup change', function () {
					otabletp1
							.column( $(this).parent().index()+':visible' )
							.search( this.value )
							.draw();

				//if($(this).parent().index() == 0){updateiso(this.value);}
			} );

			$( document ).ready(function() {
					setTimeout(function(){ $("#datatable_fixed_columntp1 .tp5").val("Active").change(); }, 1000);
			});


		var sslarray = [];
//////////////////////
//////////////////////
//////////////////////
//////////////////////
//////////////////////
//////////////////////
		$( document ).off( 'click', '.selectsymtp1');
		$( document ).on( 'click', '.selectsymtp1', function () {
			var pthis=$(this);
			var sslarray = [];
			var tempreloadval=$("#reloadval").val();
			if(tempreloadval !=""){
				var sslarray = tempreloadval.split(',');

				//tempreloadval= tempreloadval + ",";
			}
			if(sslarray.length > 5){alert("Maximum 6 Symbols allowed to Add"); }
			else{
				var exchn=pthis.attr("exchgn");
				var symbl=pthis.attr("symbl");
				if(exchn == "" || symbl==""){return false; }

				sslarray.unshift(exchn+'@'+symbl);//alert(tempreloadval + sslarray.join(','));

				//$('#sslcontainer').attr('src', "../assets/ajax/subpages/trading-platform-1-ssl.php?ssl="+sslarray.join(","));
				//parent.$('#sslcontainer').attr('src', "../assets/ajax/trading-platform-1-ssl.php?ct="+Math.random()+"&ssl="+sslarray.join(","));
				//$('#sslcontainer').load("../assets/ajax/trading-platform-11-sss.php?ct="+Math.random()+"&ssl="+sslarray.join(","));
				$("#reloadval").val(sslarray.join(','));
				//$('#sslcontainer').load("../assets/ajax/trading-platform-1-ssl.php?ct="+Math.random()+"&ssl="+ tempreloadval + sslarray.join(','));
				$('#sslcontainer').load("../assets/ajax/trading-platform-1-ssl.php?ct="+Math.random()+"&ssl="+sslarray.join(','));
			}
			otabletp1.page.len(6).draw();
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


function selectsymbol(exchn,symbl,alertres,ct){
	if(exchn == "" || symbl==""){return false;}


			var otablesl = $("#datatable_fixed_column-sl").DataTable( {
				"lengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
				"pageLength": 10,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				//"order": [[ 6, "desc" ]],
				"dom": 'Blfrtip',
				"buttons": [
					//'copyHtml5',
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
						'extend': 'colvis',
						'columns': ':gt(0)'
					}
				]
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column-sl thead th input[type=text]").on( 'keyup change', function () {

	        otablesl
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });







				$.ajax({
					type: 'post',
					url: '../assets/includes/tradingplatform.inc.php',
					data: {exchn:exchn,symbl:symbl},
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
		///////////////////
					var fsymbol= symbl;
					var fexchange= exchn;
					var fdescription= results.sresult.description;
					var fcontracttype= results.sresult.contracttype;
					var fspot_contract= results.sresult.contractstart;
					var fspot_contract1= results.sresult.contractend;
					if(fcontracttype != "Monthly"){var tmpdate='<input type="text" class="changedated" value="'+fspot_contract+'"  rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctenddaily" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }else{ var tmpdate='<input type="text" class="changedatemm"  value="'+fspot_contract+'" rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctendmonth" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }
					//if(fcontracttype != "Monthly"){var tmpdate=fspot_contract; }else{ var tmpdate='<input type="text" class="changedate">' }

					otablesl.row.add( [
					'<input type="text" class="inputsbname" rctid="'+ct+'" exid="'+fexchange+'" ccid="'+fsymbol+'" value="">',
					fsymbol,
					fdescription,
					'<select class="spmonth" rctid="'+ct+'"><option value="1">Static</option><option selected value="spot">Spot</option></select>',
					'<select id="tags'+ct+'" class="strmonth dtags" rctid="'+ct+'"  type="text"><option value="0">Static</option><option value="12" selected>12</option><option value="24">24</option><option value="36">36</option></select>',
					tmpdate,
					tmpdateend,
					'<span class="glyphicon glyphicon-remove red symchoiceminus" rexid="'+fexchange+'" rccid="'+fsymbol+'" rctid="'+ct+'"></span><input type="hidden" class="fsct" rctid="'+ct+'" value="'+fspot_contract+'"><input type="hidden" class="fsctend" rctid="'+ct+'" value="'+fspot_contract1+'"><input type="hidden" class="contracttype" rctid="'+ct+'" value="'+fcontracttype+'">' ] ).draw();

					//$('#tags'+ct).select2({
					$('.dtags').select2({
					tags: true/*,
					  createTag: function (params) {
						var term = $.trim(params.term);

						if (Number.isInteger(term) === false) {
						  return null;
						}

						if (term >100 || term < 0) {
						  return null;
						}

						return {
						  id: term,
						  text: term,
						  newTag: true // add additional parameters
						}
						}*/
					});
					//$(".select .select2-container").css({"position": "relative !important", "z-index":"99 !important"});
					//.select2-container{position:relative !important;z-index:99 !important;}
			//symchoiceadd2(fsymbol,fexchange,ct);
				  ct=(ct+1);

		//////////////////
							}else
								alert(results.error);
						}else{
							alert("Error in request. Please try again later.");
						}
					}
				  });

}

function symchoiceadd2(rccid,rexid,rctid){

		  if(chartsymbol.length >= 6){alert("Maximum 6 Symbols allowed to Add"); }
		  else{

			var ctaddstart="";

			if($('.changedated[rctid="'+rctid+'"]').length){ var ctaddstart=$('.changedated[rctid="'+rctid+'"]').val(); }
			else if($('.changedatemm[rctid="'+rctid+'"]').length){ var ctaddstart=$('.changedatemm[rctid="'+rctid+'"]').val(); }

			var inputsbname=$('.inputsbname[rctid="'+rctid+'"]').val();//alert(inputsbname);
			var ctend=$('.ctend[rctid="'+rctid+'"]').val();

			var drstart=$('.spmonth[rctid="'+rctid+'"]').val();
			var drend=$('.strmonth[rctid="'+rctid+'"]').val();


			var contracttyp=$('.contracttype[rctid="'+rctid+'"]').val();

			chartsymbol.push(rexid+'@'+rccid+'@'+rctid+'@'+inputsbname+'@'+ctaddstart+'@'+ctend+'@'+drstart+'@'+drend+'@'+contracttyp);

			if(chartsymbol.length > 0){
				parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?action=chart&sym="+chartsymbol.join(","));
				parent.$('#tpchart').show();
				$("#tpchart").css("display", "block");
				$("#tpchartcont").css("display", "block");
			}
		  }
}



$(document).ready(function(){
<?php if(isset($_GET["sid"]) and !empty($_GET["sid"])){ ?>
	$('#sslcontainer').load("assets/ajax/trading-platform-1-ssl.php?ct="+Math.random()+"&ssl=preload&sid=<?php echo $_GET['sid']; ?>");
<?php } ?>



	/*if(ochartsymbol.length > 0){
		chartsymbol=ochartsymbol;
		var i;
		var ct=1;

setTimeout(function(){
		for (i = 0; i < ochartsymbol.length; i++) {
			var fields = ochartsymbol[i].split('@');//alert(fields);
			//$('.selectsym[exid="'+fields[0]+'"][ccid="'+fields[1]+'"]').trigger("click");
			selectsymbol(fields[0],fields[1],1,(i+1));
		}
setTimeout(function(){
		for (i = 0; i < ochartsymbol.length; i++) {
			$('.inputsbname[rctid="'+fields[2]+'"]').val(fields[3]);
			$('.changedatemm[rctid="'+fields[2]+'"]').val(fields[4]);
			$('.ctend[rctid="'+fields[2]+'"]').val(fields[5]);
			$('.spmonth[rctid="'+fields[2]+'"]').val(fields[6]);
			$('.strmonth[rctid="'+fields[2]+'"]').val(fields[7]);

			//$('.symchoiceadd[rctid="'+fields[2]+'"]').css("display","none !important");
			//$('.symchoiceminus[rctid="'+fields[2]+'"]').css("display","block !important");
		}

}, 1000);
		parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?action=chart&sym="+chartsymbol.join(","));
		parent.$('#tpchart').show();

}, 1000);

//alert(chartsymbol);
	}*/
});

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);



</script>
<?php
}else{ echo "Error Occured! Please try again later.";} ?>
