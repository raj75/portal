<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
	$tmp_dry_bulb=$tmp_wet_bulb=$tmp_datetime=$tmp_wban=array();
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


<link href="../assets/plugins/jqueryslider/jquery-ui.min.css" rel="stylesheet">
<div id="chart-column" style="width:100%; height:400px;"></div>
<!--<br/><br/><br/>
Date Range: <input type="text" name="daterange2" value="01/01/2015 1:30 PM - 01/01/2015 2:00 PM" />
<div class="form-group" style="width:95%;">
	<p style="width:100%;text-align:center;">Temperature</p><input id="range-slider-1" type="text" name="range_1a" value="">
</div>
<br/><br/>

<div id="slidertitle">Units / Month</div>
<div id="slider1"></div>
<div id="slider1_value">0</div>
<br />
<br />-->

<!--<script src="https://code.jquery.com/jquery-2.1.4.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="../js/highcharts.js"></script>
<script src="../js/moment.min.js"></script>
<!--<script src="../js/plugin/ion-slider/ion.rangeSlider.min.js"></script>
<script src="jquery.js"></script>-->
<script src="../assets/plugins/jqueryslider/jquery-ui.min.js"></script>

<script>
$(function () {
	var chart;
	$(document).ready(function() {
		var tmp_dry_bulb=[<?php echo implode(",",$tmp_dry_bulb); ?>];
		var tmp_wet_bulb=[<?php echo implode(",",$tmp_wet_bulb); ?>];

		//createchart(tmp_dry_bulb,tmp_wet_bulb);
		//function createchart(tmp_dry_bulbd,tmp_wet_bulbd)
		//{
			//chart = $('#chart').highcharts({
			var chartColumn = new Highcharts.Chart({
				chart: {
					renderTo: 'chart-column',
					type: 'line'
				},
				title: {
					text: 'Monthly Average Temperature'
				},
				subtitle: {
					text: 'Source: Vervantis.com'
				},
				xAxis: {
					categories: <?php echo json_encode($tmp_datetime); ?>

				},
				yAxis: {
					title: {
						text: 'Temperature (°F)'
					}
				},
            rangeSelector:{
                enabled:true
            },
				plotOptions: {
					line: {
						dataLabels: {
							enabled: false
						},
						enableMouseTracking: false
					}
				},
				credits: {
					enabled: false
				},
				series: [{
					name: 'Dry Bulb Farenheit',
					data: tmp_dry_bulb
				}, {
					name: 'Wet Bulb Farenheit',
					data: tmp_wet_bulb
				}]
			});
		//}

		//Slider
		/*function ion_slider() {	
		    //* ion Range Sliders
		    $("#range-slider-1").ionRangeSlider({
		        min: <?php echo $minF; ?>,
		        max: <?php echo $maxF; ?>,
		        from: <?php echo $minF; ?>,
		        to: <?php echo $maxF; ?>,
		        type: 'double',
		        step: 1,
		        postfix: "°F",
		        prettify: false,
		        hasGrid: true,
				onFinish: function (data) {
					froms = $(".irs-from").html().match(/\d+/)[0];
					tos = $(".irs-to").html().match(/\d+/)[0];
					tmp_dry_bulbs=tmp_dry_bulb;
					tmp_wet_bulbs=tmp_wet_bulb;
					$.each(tmp_dry_bulbs, function(index, value){					  
					  if(value < froms || value > tos)
					  {
					   delete tmp_dry_bulbs[index];
					  }
					});
					$.each(tmp_wet_bulbs, function(index, value){					  
					  if(value < froms || value > tos)
					  {
					   delete tmp_wet_bulbs[index];
					  }
					});
					createchart(tmp_dry_bulbs,tmp_wet_bulbs);
				}
		    });
		}*/

	var min_value = 0;
	var max_value = 100;		
$( "#slider-range" ).slider({
  range: true,
  min: <?php echo $minF; ?>,
  max: <?php echo $maxF; ?>,
  values: [ <?php echo ($minF/2); ?>, <?php echo ($maxF/2); ?> ],
  slide: function( event, ui ) {
	$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
	var newdata = [];
	for (var i=0 ; i<6 ; i++) {
		newdata.push(ui.value * i);
	}
	alert(newdata);
	chart.series[0].setData (newdata);
  }
});
$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
  " - $" + $( "#slider-range" ).slider( "values", 1 ) );		
	});
});





                var $slider = $('input[name="slider1"]');
                
                $slider.bind('click', function(e) {
                    e.preventDefault();
                        chartColumn.series[0].data[0].update(parseInt($(this).val()));

                });

/*		var min_value = 0;
		var max_value = 100;

		$('#slider1').slider({
			min: min_value,
			max: max_value,
			step: 5,
			slide: function(event, ui) {
				$('#slider1_value').html('$' + ui.value);
				var newdata = [];
				for (var i=0 ; i<6 ; i++) {
					newdata.push(ui.value * i);
				}
				chart.series[0].setData (newdata);
			},
			stop: function(event, ui) {

			}
		});*/


</script>
<style>
input[type="range"] {
    -webkit-appearance: none;
    background-color: rgb(144, 144, 144);
    height: 3px;
}
input[type="range"]::-webkit-slider-thumb {
	-webkit-appearance: none;
	width: 11px;
	height: 15px;
	border:solid black 1px;
	background:#ffffff;
	-webkit-border-radius: 4px;
}
</style>
<div style="margin: 20px 0px 0px 60px">
    <form oninput="output1.value=slider1.value">
        <input type="range" name="slider1" value="42"/>
        <output name="output1" for="slider1">42</output>
    </form>
</div>
<p>
	<label for="amount">Price range:</label>
	<input type="text" id="amount" readonly="" style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider-range"></div>