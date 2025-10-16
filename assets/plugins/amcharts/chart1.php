<?php
require_once '../../../includes/db_connect.php';
require_once '../../../includes/functions.php';
	$tmp_dry_bulb=$tmp_wet_bulb=$tmp_datetime=$tmp_wban=$t_dry_bulb=$t_wet_bulb=array();
	if ($stmt = $mysqli->prepare('SELECT id,_wban,_datetime,_dry_bulb_farenheit,_wet_bulb_farenheit FROM `weather` where _wban=3011 order by _datetime')) { 
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$wban,$_datetime,$dry_bulb,$wet_bulb);
			while($stmt->fetch()) {
				$tmp_dry_bulb[]=$dry_bulb;
				$tmp_wet_bulb[]=$wet_bulb;
//$myDateTime = DateTime::createFromFormat('Y-m-d H:i:s',@trim($_datetime));
//$_datetime = $myDateTime->format('Y-m-d-h-i-s');
				$tmp_datetime[]=$_datetime;
				$tmp_wban[]=$wban;
				$t_dry_bulb[]=array("date"=>(str_replace(" ","T",$_datetime)).".000Z","visits"=>$dry_bulb);
			?>
			<?php
			}
		}
	}

	if(count($tmp_dry_bulb) and count($tmp_wet_bulb))
	{
		//echo $minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		$minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
		//echo $maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		$maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
	}else
		die("Error occurred");
		
echo json_encode($t_dry_bulb);
?>

<style>
#chartdiv {
	width	: 100%;
	height	: 500px;
}
</style>
<!--<script src="//www.amcharts.com/lib/3/amcharts.js"></script>
<script src="//www.amcharts.com/lib/3/serial.js"></script>
<script src="//www.amcharts.com/lib/3/themes/light.js"></script>-->
<script src="amcharts.js"></script>
<script src="serial.js"></script>
<script src="light.js"></script>
<script>
var chartData = generatechartData();

function generatechartData() {
    var chartData = [];
    var firstDate = new Date();
    firstDate.setDate(firstDate.getDate() - 150);

    for (var i = 0; i < 150; i++) {
        // we create date objects here. In your data, you can have date strings
        // and then set format of your dates using chart.dataDateFormat property,
        // however when possible, use date objects, as this will speed up chart rendering.
        var newDate = new Date(firstDate);
        newDate.setDate(newDate.getDate() + i);

        var visits = Math.round(Math.random() * 90 - 45);

        chartData.push({
            date: newDate,
            visits: visits
        });
    }
    return chartData;
}
chartData=<?php echo json_encode($t_dry_bulb); ?>;

var chart = AmCharts.makeChart("chartdiv", {
    "theme": "light",
    "type": "serial",
    "dataProvider": chartData,
    "valueAxes": [{
        "inside":true,
        "axisAlpha": 0
    }],
    "graphs": [{
        "id":"g1",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]]</span><br>[[value]]</div>",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletBorderColor": "#FFFFFF",
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "lineColor": "#fdd400",
        "negativeLineColor": "#67b7dc",
        "valueField": "visits"
    }],
    "chartScrollbar": {

    },
    "chartCursor": {},
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "axisAlpha": 0,
        "minHorizontalGap": 55
    }
});

chart.addListener("dataUpdated", zoomChart);
zoomChart();

function zoomChart() {
    if (chart) {
        if (chart.zoomToIndexes) {
            chart.zoomToIndexes(130, chartData.length - 1);
        }
    }
}
</script>
<div id="chartdiv"></div>
