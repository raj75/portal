<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<?php

if(isset($_GET['symb']) and $_GET['symb'] != "")
{
	$fd_arr=array();
	$symb=$mysqli->real_escape_string($_GET['symb']);
	/*if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,`PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."' ORDER BY `CONTRACT YEAR`")) {*/
	if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,GROUP_CONCAT(DISTINCT `PRODUCT DESCRIPTION`) AS `PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."'  GROUP BY `PRODUCT SYMBOL`,`CONTRACT MONTH`,`CONTRACT YEAR`  ORDER BY `CONTRACT YEAR`")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($fd_symbol,$fd_pdesc,$fd_cmonth,$fd_cyear);
			//$fdstmt->bind_result($fd_symbol,$fd_cmonth,$fd_cyear);
			while($fdstmt->fetch()){
				$fd_arr[$fd_cyear][]=$fd_cmonth;
			}
				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
	if(!count($fd_arr)) die("No data to show!");
	//else $fd_arr=array_unique($fd_arr);
?>
<style>
.dropdown-menu[title]::before {
    content: attr(title);
    /* then add some nice styling as needed, eg: */
    display: block;
    font-weight: bold;
    padding: 4px;
	text-align:center;
}
.dropdown-menu li{
	text-align:center;
}
hr{border-top: 1px solid #ccc;}
</style>
<b><img id="mvbk" onclick="fmove_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
<h3><?php echo $fd_symbol." ".$fd_pdesc; ?></h3>
<hr>
<div class="dropdown">
<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Select Date
<span class="caret"></span></button>
<ul class="dropdown-menu" title="Select Year">
<?php foreach($fd_arr as $ky=>$vl){ ?>
  <li class="dropdown-submenu">
	<a class="test" tabindex="-1" href="javascript:void(0)"><?php echo $ky; ?></a>
	<ul class="dropdown-menu" title="Select Month">
		<?php foreach($vl as $kys=>$vls){ ?>
	  <li><a tabindex="-1" href="javascript:void(0)" onclick="loadfmap('<?php echo $ky."@".$vls."@".$symb; ?>')"><?php echo $vls; ?></a></li>
		<?php } ?>
	</ul>
  </li>
<?php } ?>
</ul>
</div>
<script>
function fmove_back(){
	$('#ftable').show();
	$('#fselect').html('');
	$('#fresponse').html('');
	$('#ftopdialog').html('');
	$('#fdetails').html('');
}
function loadfmap(fdate){
	$('#fresponse').html('');
	$('#fresponse').html('<iframe id="frame" src="assets/ajax/futures_details.php?action=view&fdate='+fdate+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
    //$('#fresponse').html('assets/ajax/futures_details.php?action=view&fdate='+fdate);
	//$("#frame").attr("src", "http://www.example.com/");
}
</script>

<?php
}else if(isset($_GET['fdate']) and $_GET['fdate'] != "")
{
	$fd_arr=array();
	$tdate=explode("@",$_GET['fdate']);
	if(count($tdate) != 3) die("Wrong Parameter provided!");

	$fyear=$mysqli->real_escape_string($tdate[0]);
	$fmonth=$mysqli->real_escape_string($tdate[1]);
	$fsym=$mysqli->real_escape_string($tdate[2]);

	/*if ($fdstmt = $mysqli->prepare("SELECT nymex_future.`PRODUCT SYMBOL`, nymex_future.`CONTRACT MONTH`, nymex_future.`CONTRACT YEAR`, nymex_future.SETTLE, nymex_future.TRADEDATE FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$fsym."' AND `CONTRACT MONTH`='".$fmonth."' AND `CONTRACT YEAR`='".$fyear."' UNION ALL SELECT nymex_future_history.`PRODUCT SYMBOL`, nymex_future_history.`CONTRACT MONTH`, nymex_future_history.`CONTRACT YEAR`, nymex_future_history.SETTLE, nymex_future_history.TRADEDATE FROM futures.nymex_future_history WHERE `PRODUCT SYMBOL` = '".$fsym."' AND `CONTRACT MONTH`='".$fmonth."' AND `CONTRACT YEAR`='".$fyear."'")) {*/

	if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,`CONTRACT MONTH`,`CONTRACT YEAR`,SETTLE,TRADEDATE FROM futures.nymex_future
WHERE `PRODUCT SYMBOL`='".$fsym."' AND `CONTRACT MONTH`='".$fmonth."' and `CONTRACT YEAR`='".$fyear."' AND YEAR(TRADEDATE)>=2010
ORDER BY TRADEDATE")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($fd_symbol,$fd_cmonth,$fd_cyear,$fd_settle,$fd_tradedate);
			while($fdstmt->fetch()){$tfdate=$fd_tradedate;
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				$fd_settle=($fd_settle==""?0:$fd_settle);
				$fd_arr[]='{"date": "'.$tfdate.'","value": '.$fd_settle.'}';
			}


				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

	if(!count($fd_arr)) die("No data to show!");
?>
<style>
#fchartdiv {
	width	: 100%;
	height	: 500px;
}
#amcharts-chart-div a {display:none !important;}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<div id="fchartdiv"></div>
<script>
window.onload = function () {
var chart = AmCharts.makeChart("fchartdiv", {
    "type": "serial",
    "theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "id": "g1",
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
          "color":"#ffffff"
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "value",
        "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":true
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    },
    "dataProvider": [
<?php echo implode(",",$fd_arr);?>
	]
});

chart.addListener("rendered", zoomChart);

zoomChart();
function zoomChart() {
    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
}
}
</script>


<?php
}
?>
