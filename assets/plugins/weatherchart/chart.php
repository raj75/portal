<?php
$iso_json=array();
require_once '../../../includes/db_connect.php';
require_once '../../../includes/functions.php';
//$mysqli=mysqli_connect("localhost","vervantis","vervantis");
//mysqli_select_db($mysqli,"test_test");

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




/*
$stmt = $mysqli->prepare('SELECT id,_iso,_pnode,_start_interval,_lmp FROM warrick where _pnode!="" order by _pnode,_start_interval');
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($_id,$_iso,$pNode,$startInterval,$lmp);
	while($stmt->fetch()){
		$iso_json[$_iso][$pNode][]='['.(strtotime($startInterval)*1000).','.$lmp.']';
	}
}*/
?>
<script src="js/highslide-full.min.js" type="text/javascript"></script>
<script src="js/highslide.config.js" type="text/javascript"></script>
<script src="js/jquery-1.7.2.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/modernizr.js" type="text/javascript"></script>
<script>
jQuery.noConflict();
(function($){ // encapsulate jQuery
		var tmp_dry_bulb=[<?php echo implode(",",$tmp_dry_bulb); ?>];
		var tmp_wet_bulb=[<?php echo implode(",",$tmp_wet_bulb); ?>];
var seriesOptions = [];
colors= ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1'];
	          seriesOptions[0] = {
                name: 'Dry-Bulb',
                data: tmp_dry_bulb,
				color: colors[0],
            };
	          seriesOptions[1] = {
                name: 'Wet-Bulb',
                data: tmp_wet_bulb,
				color: colors[1],
            };

            $('#container').highcharts('StockChart', {

               /* rangeSelector: {
                    selected: 4
                },*/
			   /*rangeSelector : {
					buttons: [{
						type: 'hour',
						count: 1,
						text: '1h'
					}, {
						type: 'day',
						count: 1,
						text: '1d'
					}, {
						type: 'month',
						count: 1,
						text: '1m'
					}, {
						type: 'year',
						count: 1,
						text: '1y'
					}, {
						type: 'all',
						text: 'All'
					}],
					inputEnabled: true, // it supports only days
					selected : 4 // all
				},*/
				/*xAxis: {
					categories: <?php echo json_encode($tmp_datetime); ?>
				},*/
                yAxis: {
                    labels: {
                        formatter: function () {
                            return /*(this.value > 0 ? ' + ' : '') +*/ '$' + this.value;
                        }
                    },
                    plotLines: [{
                        value: 0,
                        width: 2,
                        color: 'silver'
                    }]
                },

                plotOptions: {
					series: {
						marker: {
							enabled: false
						}
					}
                },

                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>${point.y}</b><br/>',
                    valueDecimals: 2
                },
,
				credits: {
					enabled: false
				},
				/*series: [{
					name: 'Dry Bulb Farenheit',
					data: tmp_dry_bulb
				}, {
					name: 'Wet Bulb Farenheit',
					data: tmp_wet_bulb
				}]*/
                series: seriesOptions
            });
        };

})(jQuery);
</script>
<script src="js/highstock.js"></script>
<script src="js/exporting.js"></script>
<div id="container" style="height: 400px; min-width: 310px"></div>