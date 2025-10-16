<?php
//$_GET["iso"]="NY";
//$_GET["pnode"]="59TH STREET_GT_1,74TH STREET_GT_1";
if(!isset($_GET["iso"]) or !isset($_GET["pnode"]) or @trim($_GET["iso"]) == "" or @trim($_GET["pnode"]) == "")
	exit("Error Occured. Please try after sometime.");

$pnodelist=$iso_json=array();
$pnodelist=explode(",",@trim($_GET["pnode"]));
if(count($pnodelist) > 10)
	die("Error Occured! Selected list should not be more than 10.");
elseif(count($pnodelist) == 0)
	die("Error Occured! Selected list should be atleast 1.");

//print_r($pnodelist);exit();
$s_=array();
//array_walk($pnodelist, function ($v, $k) { $s_[] = "$v"; });
for($i=0;$i<count($pnodelist);$i++)
	$s_[] = '"'.$pnodelist[$i].'"';

//print_r($s_);var_dump($s_[0]);exit();
if(count($s_) == 0)
	die("Error Occured! No data to show.");

$mysqli=mysqli_connect("localhost","root","");
mysqli_select_db($mysqli,"warrick");
//require_once '../../../includes/db_connect.php';
//require_once '../../../includes/functions.php';
/*$s_=array();

if ($stmt = $mysqli->prepare('SELECT _pnode FROM warrick WHERE _iso="'.$_GET["iso"].'" and _pnode != "" and id IN ('.$_GET["pnode"].')')) { 
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($s_pnode);
		while($stmt->fetch()) {
			$s_[]= $s_pnode;
		}
	}
}

if(count($s_) == 0)
	die("Error Occured! No data to show.");*/

$stmt = $mysqli->prepare('SELECT id,_iso,_pnode,_start_interval,_lmp FROM warrick where _pnode!="" and _iso="'.$_GET["iso"].'" and _pnode IN ('.implode(",",$s_).') order by _pnode,_start_interval');
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($_id,$_iso,$pNode,$startInterval,$lmp);
	while($stmt->fetch()){
		$iso_json[$_iso][$pNode][]='['.(strtotime($startInterval)*1000).','.$lmp.']';
	}
}
?>
<script src="js/highslide-full.min.js" type="text/javascript"></script>
<script src="js/highslide.config.js" type="text/javascript"></script>
<script src="js/jquery-1.7.2.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/modernizr.js" type="text/javascript"></script>
<script>
jQuery.noConflict();
(function($){ // encapsulate jQuery
	$(function () {
var seriesOptions = [],
colors= ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1'],
        createChart = function () {

            $('#container').highcharts('StockChart', {

               /* rangeSelector: {
                    selected: 4
                },*/
				credits: {
					enabled: false
				},
			   rangeSelector : {
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
				},

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

                series: seriesOptions
            });
        };

<?php
$count=0;
foreach($iso_json as $ky=>$vl)
{ //if($ky=="NY") continue;
	foreach($vl as $kys=>$vls){
		if($count==10) {break;break;}
	?>
	          seriesOptions[<?php echo $count; ?>] = {
                name: '<?php echo $ky."-".$kys; ?>',
                data: [<?php echo implode(",",$vls);?>],
				color: colors[<?php if($count < 9){echo ($count+1);}else{echo (($count+1)%10);} ?>],
            };
createChart();	
<?php ++$count;}} ?>
});
})(jQuery);
</script>
<script src="js/highstock.js"></script>
<script src="js/exporting.js"></script>
<div id="container" style="height: 400px; min-width: 310px"></div>