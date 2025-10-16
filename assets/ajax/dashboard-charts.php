<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");
		
if(!isset($_GET["type"]) or @trim($_GET["type"]) == "")
	die("Invalid Parameters");
?>
<body style="overflow:hidden;">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<?php	
if($_GET["type"]=="costusage")
{
//SELECT a.meter_id,a.id,sg.service_group FROM sites s,company c, userprofile up,accounts a,service_group sg WHERE s.company_id=c.id and up.company_id=c.id and 4=up.user_id and up.company_id=c.id and a.sites_id=s.id and sg.id=a.service_group_id 
?>
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/modules/exporting.js"></script>
<script>
$(function () {
    $('#container').highcharts({
	    credits: {
            enabled: false
        },
		exporting: { enabled: false },
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Cost & Usage Monthly'
        },
        subtitle: {
            text: 'Source: Vervantis'
        },
        xAxis: [{
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value} Kwh',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Energy Consumed',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Unit Cost',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '${value}',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
			enabled: true,
			floating: true,
			verticalAlign: 'bottom',
			align:'center',
			y:20,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Unit Cost',
            type: 'column',
            yAxis: 1,
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
            tooltip: {
                valueSuffix: ' $'
            }

        }, {
            name: 'Energy Consumed',
            type: 'spline',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
            tooltip: {
                valueSuffix: 'Kwh'
            }
        }]
    });
});
</script>
<div id="container"></div>
<?php } ?>
<?php	
if($_GET["type"]=="prices")
{
?>
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/modules/exporting.js"></script>
<script>
$(function () {
    $('#container').highcharts({
	    credits: {
            enabled: false
        },
		exporting: { enabled: false },
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Prices'
        },
        subtitle: {
            text: 'Source: Vervantis'
        },
        xAxis: [{
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        legend: {
			enabled: true,
			floating: true,
			verticalAlign: 'bottom',
			align:'center',
			y:20,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Prices',
            type: 'spline',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
            tooltip: {
                valuePrefix: '$'
            }
        }]
    });
});
</script>
<div style="display:inline;">
	<div style="float:left;width:31%;height:100%;padding:5px;border-right:1px solid;font-size:14px;">
		<div style="width:100%;padding:2px;"><span style="float:left;font-weight:bold;">Crude Oil</span><span style="float:right;font-weight:bold;"><font color="green">&uarr;</font>105.36</span></div>
		<div style="width:100%;padding:2px;"><span style="float:left;font-weight:bold;">Heating Natural Gas</span><span style="float:right;font-weight:bold;"><font color="red">&darr;</font>3,0501</span></div>
	</div>
	<div style="float:right;width:60%;">
		<div>
			<span style="float:left;font-weight:bold;">Crude Oil</span><span style="float:right;font-weight:bold;">$105.36</span>
			<br /><hr style="border-top: dotted 1px;">
			<span style="float:left;font-weight:bold;"><font color="red">&darr;</font>0.39(0.37%)</span><span style="float:right;font-weight:bold;">Updated: 1:07pm EST</span>
		</div>
		<br />
		<div style="padding:20px;height:90%;border-top:1px solid;"><div id="container" style="width:100%;height:90%;"></div></div>
	</div>
</div>
<?php } ?>
<?php	
if($_GET["type"]=="energySpendForcast")
{
?>
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/modules/exporting.js"></script>
<script>
$(function () {
    $('#container').highcharts({
	    credits: {
            enabled: false
        },
		exporting: { enabled: false },
        title: {
            text: 'Energy Spend - YTD & Forecast',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: Vervantis',
            x: -20
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
			min: 0,
            title: {
                text: 'Energy Spend'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: 'Kwh'
        },
        legend: {
			enabled: true,
			floating: true,
			verticalAlign: 'bottom',
			align:'center',
			y:20
        },
        series: [{
            name: 'Washington',
            data: [67000, 145000, 178000, 210000, 156000, 120000, 78000, 98000, 136000, 210000, 198000, 145000]
        }, {
            name: 'New York',
            data: [51000, 65000, 11000, 23000, 75000, 34000, 28000, 56000, 76000, 34000, 72000, 98000]
        }, {
            name: 'Scottsdale',
            data: [45000, 34000, 78000, 120000, 340000, 230000, 310000, 390000, 410000, 360000, 374000, 390000]
        }]
    });
});
</script>
<div id="container"></div>
<?php } ?>
</body>