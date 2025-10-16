<?php
require_once '../../../assets/includes/functions.php';
sec_session_start();
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");

if(!isset($_GET["energy_type"]) or (@trim($_GET["energy_type"]) != "NG" and @trim($_GET["energy_type"]) != "CO"))
	die("Error Occured. Please try after sometime.");
else
	$energy_type=@trim($_GET["energy_type"]);

$tmp_date=array();	
for($i=1;$i<=11;$i++)
{
	if(isset($_GET["cm".$i."_year"]) and @trim($_GET["cm".$i."_year"]) != 0 and @trim($_GET["cm".$i."_year"]) != "" and isset($_GET["cm".$i."_month"]) and @trim($_GET["cm".$i."_month"]) != "0" and @trim($_GET["cm".$i."_month"]) != "")
	{
		$tmp_date[]=array("month"=>@trim($_GET["cm".$i."_month"]),"year"=>@trim($_GET["cm".$i."_year"]));
	}
}
//print_r($tmp_date);exit();
if(count($tmp_date)==0)
	die("Nothing to show!");
	
require_once '../../../assets/includes/db_connect.php';
?>
<style>
#chartdiv {
width: 100%;
height: 85%;
}
.amcharts-compare-item-div{height:20px !important;}
</style>
<!--<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/amstock.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js"></script>-->

<link rel="stylesheet" href="style.css"	type="text/css">
<link rel="stylesheet" href="bootstrap.min.css"	type="text/css">        
<link rel="stylesheet" href="export.css"  type="text/css">

<script src="amcharts.js" type="text/javascript"></script>
<script src="serial.js" type="text/javascript"></script>
<script src="amstock.js" type="text/javascript"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>

<script src="export.js" type="text/javascript"></script>

<?php
	if ($stmtt = $mysqli->prepare("SELECT _month,_year,date(datetime) FROM `cme_tmp` where energy_type='".$mysqli->real_escape_string($energy_type)."' group by date(datetime) ORDER by datetime")) {
		$stmtt->execute();
		$stmtt->store_result();
		if ($stmtt->num_rows > 0) {
			echo '<script>var chartData21 = [];chartData22 = [];chartData23 = [];</script>';
			$stmtt->bind_result($_dmonth,$_dyear,$_datetime);
			while($stmtt->fetch()) {
				if ($stmttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/12,1) As open, ROUND(sum(_last)/12,1) AS close, ROUND(sum(_low)/12,1) As low, ROUND(sum(_high)/12,1) As high, ROUND(sum(_volume)/12,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 1 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmttt->execute();
					$stmttt->store_result();
					if ($stmttt->num_rows > 0) {
						$stmttt->bind_result($_supdated,$sopen,$sclose,$slow,$shigh,$svolume);
						$stmttt->fetch();
?>
<script>
				chartData21.push( {
				  "date": "<?php echo $_supdated; ?>",
				  "open": "<?php echo $sopen; ?>",
				  "close": "<?php echo $sclose; ?>",
				  "low": "<?php echo $slow; ?>",
				  "high": "<?php echo $shigh; ?>",
				  "volume": "<?php echo @str_replace(",","",$svolume); ?>",
				  "bullet": "round"
				} );
</script>
<?php
					}
				}

				if ($stmtttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/24,1) As open, ROUND(sum(_last)/24,1) AS close, ROUND(sum(_low)/24,1) As low, ROUND(sum(_high)/24,1) As high, ROUND(sum(_volume)/24,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 2 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($_s2updated,$s2open,$s2close,$s2low,$s2high,$s2volume);
						$stmtttt->fetch();
?>
<script>
				chartData22.push( {
				  "date": "<?php echo $_s2updated; ?>",
				  "open": "<?php echo $s2open; ?>",
				  "close": "<?php echo $s2close; ?>",
				  "low": "<?php echo $s2low; ?>",
				  "high": "<?php echo $s2high; ?>",
				  "volume": "<?php echo @str_replace(",","",$s2volume); ?>",
				  "bullet": "triangleUp"
				} );
</script>
<?php
					}
				}

				if ($stmttttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/36,1) As open, ROUND(sum(_last)/36,1) AS close, ROUND(sum(_low)/36,1) As low, ROUND(sum(_high)/36,1) As high, ROUND(sum(_volume)/36,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 3 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmttttt->execute();
					$stmttttt->store_result();
					if ($stmttttt->num_rows > 0) {
						$stmttttt->bind_result($_s3updated,$s3open,$s3close,$s3low,$s3high,$s3volume);
						$stmttttt->fetch();
?>
<script>
				chartData23.push( {
				  "date": "<?php echo $_s3updated; ?>",
				  "open": "<?php echo $s3open; ?>",
				  "close": "<?php echo $s3close; ?>",
				  "low": "<?php echo $s3low; ?>",
				  "high": "<?php echo $s3high; ?>",
				  "volume": "<?php echo @str_replace(",","",$s3volume); ?>",
				  "bullet": "square"
				} );
</script>
<?php
					}
				}				
			}
		}
	}



for($i=0;$i<count($tmp_date);$i++)
{
	echo '<script>var chartData'.$i.' = [];</script>';
	if ($stmt = $mysqli->prepare("SELECT DATE(_updated), _open As open, _last AS close, _low As low, _high As high, _volume AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and _month='".$mysqli->real_escape_string($tmp_date[$i]["month"])."' and _year='".$mysqli->real_escape_string($tmp_date[$i]["year"])."' ORDER BY id")) {
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($_updated,$open,$close,$low,$high,$volume);
			while($stmt->fetch()) {
?>
<script>
				chartData<?php echo $i; ?>.push( {
				  "date": "<?php echo $_updated; ?>",
				  "open": "<?php echo $open; ?>",
				  "close": "<?php echo $close; ?>",
				  "low": "<?php echo $low; ?>",
				  "high": "<?php echo $high; ?>",
				  "volume": "<?php echo @str_replace(",","",$volume); ?>"
				} );
</script>
<?php
			}
		}
	}
}
//$thread = $mysqli->thread_id;
$mysqli->close();
?>
<script>
//var tmp_arr=chartData21;alert(JSON.stringify(tmp_arr));
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "stock",
  "theme": "light",
  /*"dataLoader": {
    "url": "https://s3-us-west-2.amazonaws.com/s.cdpn.io/218423/data1.json"
  },*/

  "dataSets": [ 
<?php
for($i=0;$i<count($tmp_date);$i++)
{
?>  
	{
      "title": "<?php echo $tmp_date[$i]["month"]." ".$tmp_date[$i]["year"]; ?>",
      "fieldMappings": [ {
			"fromField": "open",
			"toField": "open"
		}, 
		{
			"fromField": "close",
			"toField": "close"
		}, 
		{
			"fromField": "high",
			"toField": "high"
		}, 
		{
			"fromField": "low",
			"toField": "low"
		},
		{
			"fromField": "volume",
			"toField": "volume"
		} ],
      "dataProvider": chartData<?php echo $i; ?>,
	  <?php if($i != 0){?>"compared":true,<?php } ?>
      "categoryField": "date"
    }<?php 
if($i==0){?>
	,{
      "title": "12 Month Strip",
      "fieldMappings": [ {
			"fromField": "open",
			"toField": "open"
		}, 
		{
			"fromField": "close",
			"toField": "close"
		}, 
		{
			"fromField": "high",
			"toField": "high"
		}, 
		{
			"fromField": "low",
			"toField": "low"
		},
		{
			"fromField": "volume",
			"toField": "volume"
		} ],
      "dataProvider": chartData21,
      "categoryField": "date",
	 },

	{
      "title": "24 Month Strip",
      "fieldMappings": [ {
			"fromField": "open",
			"toField": "open"
		}, 
		{
			"fromField": "close",
			"toField": "close"
		}, 
		{
			"fromField": "high",
			"toField": "high"
		}, 
		{
			"fromField": "low",
			"toField": "low"
		},
		{
			"fromField": "volume",
			"toField": "volume"
		} ],
      "dataProvider": chartData22,
      "categoryField": "date",
	 },

	{
      "title": "36 Month Strip",
      "fieldMappings": [ {
			"fromField": "open",
			"toField": "open"
		}, 
		{
			"fromField": "close",
			"toField": "close"
		}, 
		{
			"fromField": "high",
			"toField": "high"
		}, 
		{
			"fromField": "low",
			"toField": "low"
		},
		{
			"fromField": "volume",
			"toField": "volume"
		} ],
      "dataProvider": chartData23,
      "categoryField": "date",
	 }
<?php } if($i < count($tmp_date)){echo ",";}} ?>],

  "panels": [ {
    "showCategoryAxis": false,
    "title": "Price",
    "percentHeight": 70,
    "stockGraphs": [ {
      "id": "g1",
	  "openField": "open",
	  "closeField": "close",
	  "highField": "high",
	  "lowField": "low",
      "valueField": "close",
	  "bulletField": "bullet",
      "comparable": true,
      "compareField": "close",
      "balloonText": "[[title]]:<b>close [[value]]</b>",
      "compareGraphBalloonText": "[[title]]:<b>close [[value]]</b>",
	  "showBalloon": true,
	  "type": "line"
    } ],
    "stockLegend": {
      "periodValueTextComparing": "[[percents.value.close]]%",
      "periodValueTextRegular": "[[value.close]]"
    }
  }/*, {
    "title": "Volume",
    "percentHeight": 30,
    "stockGraphs": [ {
      "valueField": "volume",
      "type": "line",
      "showBalloon": true,
      "fillAlphas": 1
    } ],
    "stockLegend": {
      "periodValueTextRegular": "[[value.close]]"
    }
  }*/ ],

  "chartScrollbarSettings": {
    "graph": "g1"
  },

  "chartCursorSettings": {
    "valueBalloonsEnabled": true,
    "fullWidth": true,
    "cursorAlpha": 0.1,
    "valueLineBalloonEnabled": true,
    "valueLineEnabled": true,
    "valueLineAlpha": 0.5
  },

  "periodSelector": {
    "position": "left",
    "periods": [ {
      "period": "DD",
      "selected": true,
      "count": 1,
      "label": "1 day"
    },{
      "period": "DD",
      "selected": true,
      "count": 2,
      "label": "2 days"
    },{
      "period": "DD",
      "selected": true,
      "count": 3,
      "label": "3 days"
    },{
      "period": "WW",
      "selected": true,
      "count": 1,
      "label": "1 week"
    },{
      "period": "MM",
      "selected": true,
      "count": 1,
      "label": "1 month"
    }, {
      "period": "YYYY",
      "count": 1,
      "label": "1 year"
    }, {
      "period": "YTD",
      "label": "YTD"
    }, {
      "period": "MAX",
      "label": "MAX"
    } ]
  },

  "dataSetSelector": {
    "position": "left"
  },

  "export": {
    "enabled": true
  }
} );

function addPanel() 
{

	if (document.getElementById("addPanelButton").checked==true)
	{
		newPanel = new AmCharts.StockPanel();
		//newPanel.allowTurningOff = true;
		newPanel.title = "Volume";
			
		chart.panels[0].showCategoryAxis=false;

		var graph = new AmCharts.StockGraph();
		graph.valueField = "volume";
		//chart.panels[0].stockGraphs[0].volumeField: "volume",

		graph.fillAlphas = 0.15;
		newPanel.addStockGraph(graph);

		var legend = new AmCharts.StockLegend();
		legend.markerType = "none";
		legend.markerSize = 0;
		newPanel.stockLegend = legend;

		chart.addPanelAt(newPanel, 1);
		chart.panels[1].stockGraphs[0].showBalloon=true;
	}
	else
	{
		//chart.panels[0].showCategoryAxis=true;
		//chart.panels[0].valueAxes.id="volume";
		//chart.panels[0].valueAxes.dashLength=5;
		//chart.panels[0].categoryAxis.dashLength=5;

		chart.removePanel(newPanel);
	}
	chart.validateNow();
}

function addBalloon() 
{				
	if (document.getElementById("addBalloonText").checked==true)
	{
		chart.panels[0].stockGraphs[0].showBalloon=true;
		chart.panels[0].stockGraphs[0].showGraphsBalloon=true;
		chart.panels[1].stockGraphs[0].showBalloon=true;
		chart.panels[1].stockGraphs[0].showGraphsBalloon=true;
	}
	else
	{
		chart.panels[0].stockGraphs[0].showBalloon=false;
		chart.panels[0].stockGraphs[0].showGraphsBalloon=false;
		chart.panels[1].stockGraphs[0].showBalloon=false;
		chart.panels[1].stockGraphs[0].showGraphsBalloon=false;
	}
	chart.validateNow();
}

function changeType(type) 
{
	chart.panels[0].stockGraphs[0].type = type;
	//chart.panels[0].stockGraphs[0].compareGraphType = type;
	if (type=="candlestick") 
	{
		chart.panels[0].stockGraphs[0].fillAlphas=1;
		//chart.panels[0].stockGraphs[0].compareGraphFillAlphas=1;
	}
	else
	{
		chart.panels[0].stockGraphs[0].fillAlphas=0;
		//chart.panels[0].stockGraphs[0].compareGraphFillAlphas=0;
	}
	
	if (type=="line")
	{
		chart.panels[0].stockGraphs[0].balloonText="[[title]]: close <b>[[close]]</b>";
		chart.panels[0].stockGraphs[0].compareGraphBalloonText="[[title]]: close <b>[[close]]</b>";
	}
	else
	{
		chart.panels[0].stockGraphs[0].balloonText="[[title]] :<br>open <b>[[open]]</b><br>close <b>[[close]]</b><br>low <b>[[low]]</b><br>high <b>[[high]]</b>";
		chart.panels[0].stockGraphs[0].compareGraphBalloonText="[[title]] :<br>open <b>[[open]]</b><br>close <b>[[close]]</b><br>low <b>[[low]]</b><br>high <b>[[high]]</b>";
	}

	if(document.getElementById('addPanelButton').checked) {
		chart.panels[1].stockGraphs[0].balloonText="volume:<b>[[volume]]</b>";
	}
	chart.validateNow();
}

function changeCM(type) 
{
	if (type=="percentage") 
	{
		chart.panels[0].stockLegend.periodValueTextComparing="[[percents.value.close]]%";
	}
	else
	{
		chart.panels[0].stockLegend.periodValueTextComparing="[[value.close]]";
	}
	chart.validateNow();
}
</script>
    <div class="container">
        <div class="row">
            <div class="col-md-2 text-left">
                <label>Volume Panel</label>
            </div>
            <div class="col-md-10">
                <div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="addPanelButton" onclick="addPanel()" value="add panel">
                    <label class="onoffswitch-label" for="addPanelButton">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 text-left">
                <label>Balloon Text</label>
            </div>
            <div class="col-md-10">
                <div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch2" class="onoffswitch-checkbox" id="addBalloonText" onclick="addBalloon()" value="add balloon" checked>
                    <label class="onoffswitch-label" for="addBalloonText">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
		
        <div class="row">
            <div class="col-md-12 text-left">   
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary active" onclick="changeCM('percentage');">
                        <input name="cmoptions" id="cmoption1" type="radio">Percentage
                    </label>
                    <label class="btn btn-primary" onclick="changeCM('price');">
                        <input name="cmoptions" id="cmoption2" type="radio">Price
                    </label>  
                </div>
	        </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12 text-left">   
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary active" onclick="changeType('line');">
                        <input name="options" id="option1" type="radio">Line
                    </label>
                    <label class="btn btn-primary" onclick="changeType('ohlc');">
                        <input name="options" id="option2" type="radio">OHLC
                    </label>
                    <label class="btn btn-primary" onclick="changeType('candlestick');">
                        <input name="options" id="option3" type="radio">Candlestick
                    </label>    
                </div>
	        </div>
        </div>
<div id="chartdiv"></div>
</div>