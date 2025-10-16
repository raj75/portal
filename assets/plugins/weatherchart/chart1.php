<?php
//$_GET["wban"]="3892,3011";
if(!isset($_GET["wban"]) or @trim($_GET["wban"]) == "")
	exit("Error Occured. Please try after sometime.");

$wbanlist=array();
$wbanlist=explode(",",@trim($_GET["wban"]));
if(count($wbanlist) > 3)
	die("Error Occured! Selected list should not be more than 3.");
elseif(count($wbanlist) == 0)
	die("Error Occured! Selected list should be atleast 1.");

//print_r($wbanlist);exit();
require_once '../../../assets/includes/db_connect.php';
require_once '../../../assets/includes/functions.php';
/*if ($stmt = $mysqli->prepare('SELECT id,_wban,_name,_state,_location FROM `station` where _state != "" and _location != "" group by _wban')) { 
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($s_id,$s_wban,$s_name,$s_state,$s_location);
		while($stmt->fetch()) {
			$s_arr[$s_wban]=array("id"=>$s_id,"wban"=>$s_wban,"name"=>$s_state,"location"=>$s_location);
		}
	}
}*/

	$wban_arr=$tmp_dry_bulb=$tmp_wet_bulb=$tmp_datetime=$tmp_wban=$tmp_avg_bulb=$tmp_max_bulb=$tmp_min_bulb=$tmp_wt_blb=$tmp_dy_blb=array();
	if ($stmt = $mysqli->prepare('SELECT id,_wban,_datetime,_dry_bulb_farenheit,_wet_bulb_farenheit FROM `weather` where _wban IN ('.@trim($_GET["wban"]).') ORDER BY _wban,_datetime')) { 
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$wban,$_datetime,$dry_bulb,$wet_bulb);
			while($stmt->fetch()) {
				$wban_arr[$wban]["dry_bulb"][]=array(strtotime($_datetime)*1000,(int)$dry_bulb);
				$wban_arr[$wban]["wet_bulb"][]=array(strtotime($_datetime)*1000,(int)$wet_bulb);
				$wban_arr[$wban]["avg_bulb"][]=array(strtotime($_datetime)*1000,((((int)$dry_bulb)+((int)$wet_bulb))/2));
				if((int)$dry_bulb > (int)$wet_bulb)
				{
					$wban_arr[$wban]["max_bulb"][]=array(strtotime($_datetime)*1000,(int)$dry_bulb);
					$wban_arr[$wban]["min_bulb"][]=array(strtotime($_datetime)*1000,(int)$wet_bulb);
				}
				else
				{
					$wban_arr[$wban]["max_bulb"][]=array(strtotime($_datetime)*1000,(int)$wet_bulb);
					$wban_arr[$wban]["min_bulb"][]=array(strtotime($_datetime)*1000,(int)$dry_bulb);
				}
				//$wban_arr[$wban][]=array("dry_bulb"=>array(strtotime($_datetime)*1000,(int)$dry_bulb),"wet_bulb"=>array(strtotime($_datetime)*1000,(int)$wet_bulb),"avg_bulb"=>array(strtotime($_datetime)*1000,((((int)$dry_bulb)+((int)$wet_bulb))/2)),"min_bulb"=>$tmp_max_bulb,"max_bulb"=>$tmp_min_bulb);
				//$tmp_wt_blb[]=(int)$wet_bulb;
				//$tmp_dy_blb[]=(int)$dry_bulb;
			}
		}
	}

	if(count($wban_arr))
	{
		//echo $minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		//$minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
		//echo $maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		//$maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
	}else
		die("No data show!");
//print_r($wban_arr);exit();
//echo json_encode($tmp_avg_bulb);exit();

//exit();
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="js/highstock.js"></script>
<script src="js/exporting.js"></script>
<div id="container" style="height: 500px; min-width: 500px"></div>

<script>
$(function() {
var data_dry_bulb=data_wet_bulb=[];
//data_dry_bulb=<?php //echo json_encode($tmp_dry_bulb); ?>;
//data_wet_bulb=<?php //echo json_encode($tmp_wet_bulb); ?>;
//data_avg_bulb=<?php //echo json_encode($tmp_avg_bulb); ?>;
//data_min_bulb=<?php //echo json_encode($tmp_min_bulb); ?>;
//data_max_bulb=<?php //echo json_encode($tmp_max_bulb); ?>;


	//$.getJSON('abc.json', function(data) {
		// Create the chart
		$('#container').highcharts('StockChart', {			
		  chart: {
					spacingBottom: 100
				},
			rangeSelector : {
				selected : 1
			},

			title : {
				text : 'Monthly Average Temperature'
			},
			subtitle: {
				text: 'Source: Vervantis.com'
			},
			yAxis: {
				title: {
					text: 'Temperature (°F)',
				},
			    opposite: false,
                labels: {
                    align: 'left',
                    x: 0
                }
			},
			credits: {
				enabled: false
			},
			exporting: {
				enabled: false
			}/*,
			legend: {
				enabled: true,
				floating: true,
				verticalAlign: 'bottom',
				align:'center',
				y:40
			}*/,
			legend: {
				y:40,
				enabled: true,
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true,
                labelFormatter: function() {
                    return '<div class="' + this.name + '-arrow"></div><span style="font-family: \'Advent Pro\', sans-serif; font-size:12px">' + this.name +'</span>';
                }
            },			
			series : [/*{
				name : 'Dry temperature',
				data : data_dry_bulb,
				tooltip: {
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}},{
				name : 'Wet Temperature',
				data : data_wet_bulb,
				tooltip: {
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}},{
				name : 'Minimum Temperature',
				data : data_min_bulb,
				visible: false,
				tooltip: {
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}}	,{
				name : 'Average Temperature',
				data : data_avg_bulb,
				tooltip: {
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}},{
				name : 'Maximum Temperature',
				data : data_max_bulb,
				visible: false,
				tooltip: {
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}}*/
<?php
foreach($wban_arr as $kys => $vls)
{
	foreach($vls as $ky => $vl)
	{
		echo "{
				name : '".$kys."-".$ky."',
				data : ".json_encode($vl).",
				".(preg_match('/avg_bulb/s', $ky, $ign)?'':"visible:false,")."//visible: false,
				tooltip: {
					pointFormat: '<span style=\"color:{point.color}\">\u25CF</span> {series.name}: <b>{point.y} °F</b><br/>',
					valueDecimals: 0
				}},";
	}

}
?>				
				/*,
                dataGrouping: {
                        approximation: "sum",
                        enabled: true,
                        forced: true,
                        units: [['month',[1]]],
                        
                    }
			*/]
		});
	//});

});
</script>