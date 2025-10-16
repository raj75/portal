<?php
require_once '../../../includes/db_connect.php';
require_once '../../../includes/functions.php';
	$tmp_dry_bulb=$tmp_wet_bulb=$tmp_datetime=$tmp_wban=$t_dry_bulb=$t_wet_bulb=array();
	if ($stmt = $mysqli->prepare('SELECT id,_wban,_datetime,_dry_bulb_farenheit,_wet_bulb_farenheit FROM `weather` where _wban=3011')) { 
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
				$t_dry_bulb[]=array("date"=>(str_replace(" ","T",$_datetime)).".000Z","value"=>$dry_bulb);
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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>amCharts Responsive Example</title>
	<script src="amcharts.js"></script>
	<script src="serial.js"></script>
	<script src="//www.amcharts.com/lib/3/amstock.js"></script>
	<script src="responsive.min.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <style>
    body, html {
      height: 100%;
      padding: 0;
      margin: 0;
      font-family: Verdana;
      font-size: 12px;
    }
    </style>
    <script>
	$(document).ready(function(){
	AmCharts.ready(function(){
    var chartData1 = [];
    var chartData2 = [];

    generateChartData();

    function generateChartData() {
      var firstDate = new Date();
      firstDate.setDate( firstDate.getDate() - 500 );
      firstDate.setHours( 0, 0, 0, 0 );

      for ( var i = 0; i < 5; i++ ) {
        var newDate = new Date( firstDate );
        newDate.setDate( newDate.getDate() + i );

        var a1 = Math.round( Math.random() * ( 40 + i ) ) + 100 + i;
        var b1 = Math.round( Math.random() * ( 1000 + i ) ) + 500 + i * 2;

        var a2 = Math.round( Math.random() * ( 100 + i ) ) + 200 + i;
        var b2 = Math.round( Math.random() * ( 1000 + i ) ) + 600 + i * 2;

        chartData1.push( {
          "date": newDate,
          "value": a1/*,
          "volume": b1*/
        } );
        chartData2.push( {
          "date": newDate,
          "value": a2/*,
          "volume": b2*/
        } );
      }
    }
	chartData1=<?php echo json_encode($t_dry_bulb); ?>;
//alert(JSON.stringify(chartData1));
   var chart = AmCharts.makeChart( "chartdiv", {
      "type": "stock",
      "theme": "none",

      "dataSets": [ {
          "title": "first data set",
          "fieldMappings": [ {
            "fromField": "value",
            "toField": "value"
          }/*, {
            "fromField": "volume",
            "toField": "volume"
          }*/ ],
          "dataProvider": chartData1,
          "categoryField": "date"
        },

        {
          "title": "second data set",
          "fieldMappings": [ {
            "fromField": "value",
            "toField": "value"
          }/*, {
            "fromField": "volume",
            "toField": "volume"
          }*/ ],
          "dataProvider": chartData2,
          "categoryField": "date"
        }
      ],

      "panels": [ {
          "showCategoryAxis": false,
          "title": "Value",
          "percentHeight": 70,
          "stockGraphs": [ {
            "id": "g1",
            "valueField": "value",
            "comparable": true,
            "compareField": "value",
            "balloonText": "[[title]]:<b>[[value]]</b>",
            "compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
          } ],
          "stockLegend": {
            "periodValueTextComparing": "[[percents.value.close]]%",
            "periodValueTextRegular": "[[value.close]]"
          }
        }/*,
        {
          "title": "Volume",
          "percentHeight": 30,
          "stockGraphs": [ {
            "valueField": "volume",
            "type": "column",
            "showBalloon": false,
            "fillAlphas": 1
          } ],
          "stockLegend": {
            "periodValueTextRegular": "[[value.close]]"
          }
        }*/
      ],

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

      "responsive": {
        "enabled": true
      }
    } );	
	
	});
 });
    </script>
  </head>

  <body>
    <div id="chartdiv" style="width: 100%; height: 100%;"></div>
  </body>

</html>