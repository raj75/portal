<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
if(isset($_GET["action"]) and $_GET["action"]=="view" and isset($_GET["sid"])){
	$sid = $_GET["sid"];
?>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2><?php
						if($sid=="button1") echo "NOAA HDD Weekly Forecast";
						else if($sid=="button2") echo "NOAA CDD Weekly Forecast";
						else if($sid=="button3") echo "NOAA 6-10 Day Forecast";
						else if($sid=="button4") echo "NOAA 8-14 Day Forecast";
						else if($sid=="button5") echo "Windy Forecast";
						else if($sid=="button6") echo "Dark Sky Forecast";
						else if($sid=="button7") echo "National Hurricane Center";
						else if($sid=="button10") echo "Atlantic Hurricane Forecast";
						else if($sid=="button11") echo "Pacific Hurricane Forecast";
					?></h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
					<div class="widget-body no-padding" id="show-file" style="padding:1% !important;width:auto !important;text-align:center;">
<?php
	if($sid=="button1" || $sid=="button2"){

	$orig = file_get_contents(($sid=="button1"?"https://www.cpc.ncep.noaa.gov/products/analysis_monitoring/cdus/degree_days/hfstwpws.txt":"https://www.cpc.ncep.noaa.gov/products/analysis_monitoring/cdus/degree_days/cfstwpws.txt"));
	$a = htmlentities($orig);

	echo '<code>';
	echo '<pre style="width:auto;text-align:center;">';

	echo $a;

	echo '</pre>';
	echo '</code>';
	}elseif($sid=="button3" || $sid=="button4"){
		echo "<img src='".($sid=="button3"?"https://www.cpc.ncep.noaa.gov/products/predictions/610day/610temp.new.gif":"https://www.cpc.ncep.noaa.gov/products/predictions/814day/814temp.new.gif")."' height='50%' width='50%' style='display:block;margin-left: auto;margin-right: auto;'>";
	}elseif($sid=="button5"){
		?>
<style>
.inline-group .checkbox{
	float: left;
	margin-right: 35px;
	margin-top:0px !important;
	margin-bottom:0px !important;
	padding-top: 5px;
}
.inline-group .select{
	float: left;
	margin-right: 9px;
	margin-top:0px !important;
	margin-bottom:0px !important;
}
.mar-30{margin-right:35px !important;}
</style>
		<section>
			<div class="inline-group" style="display:inline-block;">
				<label class="select">Wind:
										<select class="input-sm" id="wmetricwind" onchange="windychange('wmetricwind')">
											<option value="default" selected>Default units</option>
											<option value="kt">kt</option>
											<option value="m/s">m/s</option>
											<option value="km/h">km/h</option>
											<option value="mph">mph</option>
											<option value="bft">bft</option>
										</select> <i></i>
				</label>
				<label class="select">Temperature:
										<select class="input-sm" id="wmetrictemp" onchange="windychange('wmetrictemp')">
											<option value="default" selected>Default units</option>
											<option value="°C">°C</option>
											<option value="°F">°F</option>
										</select> <i></i>
				</label>
				<label class="select mar-30">Forecast for:
										<select class="input-sm" id="wcal" onchange="windychange('wcal')">
											<option value="" selected>Now</option>
											<option value="12">Next 12 hours</option>
											<option value="24">Next 24 hours</option>
										</select> <i></i>
				</label>
				<label class="checkbox">
					<input type="checkbox" name="checkbox-inline" id="showmarker" onclick="windychange('showmarker')">
					<i></i>Show marker in the middle</label>
				<label class="checkbox">
					<input type="checkbox" name="checkbox-inline" id="pressure" onclick="windychange('pressure')">
					<i></i>Pressure isolines</label>
				<label class="checkbox">
					<input type="checkbox" name="checkbox-inline" id="spotforecast" onclick="windychange('spotforecast')">
					<i></i>Include spot forecast</label>
			</div>
		</section>

		<iframe id="windy" width='100%' height='600' src='https://embed.windy.com/embed2.html?lat=33.303&lon=-111.819&zoom=5&level=surface&overlay=wind&menu=&message=true&marker=&calendar=&pressure=&type=map&location=coordinates&detail=&detailLat=33.303&detailLon=-111.819&metricWind=default&metricTemp=default&radarRange=-1' frameborder='0' style='margin-left:auto;margin-right:auto;'></iframe>
		<script>
			function windychange(myCheck) {
				var wmetrictemp="default";
				var wmetricwind="default";
				var wcal="";
				var wmarker="";
				var wpressure="";
				var wspot="";
			  var checkBox = document.getElementById(myCheck);
			  var iframewindy = document.getElementById("windy");
			  if (document.getElementById("showmarker").checked == true){
				wmarker="true";
			  } else {
				wmarker="";
			  }
			  if (document.getElementById("pressure").checked == true){
				wpressure="true";
			  } else {
				wpressure="";
			  }
			  if (document.getElementById("spotforecast").checked == true){
				wspot="true";
			  } else {
				wspot="";
			  }
			var wmw = document.getElementById("wmetricwind");
			wmetricwind = wmw.options[wmw.selectedIndex].value;
			var wmt = document.getElementById("wmetrictemp");
			wmetrictemp = wmt.options[wmt.selectedIndex].value;
			var wct = document.getElementById("wcal");
			wcal = wct.options[wct.selectedIndex].value;

			  document.getElementById("windy").src="https://embed.windy.com/embed2.html?lat=33.303&lon=-111.819&zoom=5&level=surface&overlay=wind&menu=&message=true&marker="+wmarker+"&calendar="+wcal+"&pressure="+wpressure+"&type=map&location=coordinates&detail="+wspot+"&detailLat=33.303&detailLon=-111.819&metricWind="+wmetricwind+"&metricTemp="+wmetrictemp+"&radarRange=-1";
			}

		</script>
	<?php
	}elseif($sid=="button6"){
		echo "<iframe width='100%' height='600' src='https://maps.darksky.net/@temperature,42.020,-98.521,5' frameborder='0' style='margin-left:auto;margin-right:auto;'></iframe>";
	}elseif($sid=="button8"){
		echo "<iframe width='100%' height='450' src='https://www.nhc.noaa.gov/' frameborder='0' style='margin-left:auto;margin-right:auto;'></iframe>";
		?>
		<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
		<script>
		$(document).ready(function(){alert('hi');
		var $iFrameContents = $('iframe').contents(),
		$entryContent   = $iFrameContents.find('center');

		$iFrameContents.find('html').replaceWith($entryContent);
		});
		</script>
		<?php
	}elseif($sid=="button7"){?>
		<div style="border: 3px solid rgb(201, 0, 1); overflow: hidden; margin: 15px auto; max-width: 736px;">
<iframe scrolling="no" src="https://www.nhc.noaa.gov" style="border: 0px none; margin-left: -185px; height: 859px; margin-top: -533px; width: 926px;">
</iframe>
</div>
		<?php
	}elseif($sid=="button10" || $sid=="button11"){
		echo "<img src='".($sid=="button10"?"https://www.nhc.noaa.gov/xgtwo/two_atl_5d0.png":"https://www.nhc.noaa.gov/xgtwo/two_pac_5d0.png")."' height='50%' width='50%' style='display:block;margin-left: auto;margin-right: auto;'>";
	}else echo "Error occurred! Please try after sometime.";
?>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
<?php }else{ echo "Error Occured! Please try again later.";} ?>
