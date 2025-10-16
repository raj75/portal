<?php
//require_once("inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
	die("Access Restricted.");


$comp_id=$_SESSION["company_id"];
$user_one=$_SESSION['user_id'];


	$symbollist=array();

	if ($fwstmt = $mysqli->prepare("SELECT DISTINCT f.`symbol` FROM `financial_widget_data` f , financial_widget d where f.symbol=d.symbol")) {
		$fwstmt->execute();
		$fwstmt->store_result();
		if ($fwstmt->num_rows > 0) {
			$fwstmt->bind_result($ssymbol);
			while($fwstmt->fetch()){
				if(empty($ssymbol)) continue;
				array_push($symbollist,"'".$ssymbol."'");
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

	if(count($symbollist) ==0) die("<p style='text-align:center;margin-top: 42%;'>No data to show!</p>");	



	$symarrdash=array();
	$firstsymbol="";
	if ($fwstmt = $mysqli->prepare("SELECT `name`,`symbol`,price,last_price,price_change,price_change_pct,volume FROM financial_widget WHERE `symbol` IN (".implode(',',$symbollist).") and (widget='energy' or widget='Energy')  ORDER BY `sort`")) {
		$fwstmt->execute();
		$fwstmt->store_result();
		if ($fwstmt->num_rows > 0) {
			$fwstmt->bind_result($fwname,$fwsymbol,$fwprice,$fwlast_price,$fwprice_change,$fwprice_change_pct,$fwvolume);
			while($fwstmt->fetch()){
				if(empty($firstsymbol)) $firstsymbol=$fwsymbol;
				if(empty($fwname)) $fwname="";
				if(empty($fwprice)) $fwprice=0;
				if(empty($fwlast_price)) $fwlast_price=0;
				if(empty($fwprice_change)) $fwprice_change=0;
				if(empty($fwprice_change_pct)) $fwprice_change_pct=0;
				if(empty($fwvolume)) $fwvolume=0;
				$symarrdash[$fwsymbol]=array(
					"name"=>$fwname,
					"symbol"=>$fwsymbol,
					"price"=>round($fwprice,2),
					"last_price"=>round($fwlast_price,2),
					"price_change"=>round($fwprice_change,2),
					"price_change_pct"=>round($fwprice_change_pct,2),
					"volume"=>$fwvolume
				);
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//print_r($symarr);die();
if ( empty($_GET["symbol"]) ) {
	$_GET["symbol"] = $firstsymbol;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/css/bootstrap.min.css">
  <script src="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/js/jquery.js"></script>
  <script src="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/js/bootstrap/bootstrap.min.js"></script>
</head>
<body>
<?php

date_default_timezone_set('UTC');

ini_set("memory_limit", "-1");
ini_set('max_execution_time', 0);

$symboltable="financial_widget";
$datatable="financial_widget_data";

/*
$conn = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
//$conn = mysqli_connect("localhost", "root","","vervantis");


//$conn = mysqli_connect("localhost", "root","root","vervantis");
//$conn = mysqli_connect("localhost", "root","","vervantis");

if (!$conn) {
    //printf("Connect failed: %s\n", mysqli_connect_errno());
	die("<p style='text-align:center;margin-top: 42%;'>Error Occured. Please try again later!</p>");
    exit();
}
*/

if(isset($_GET["symbol"]) and !empty($_GET["symbol"])) $symbol= urldecode(@trim($_GET["symbol"])); else die("<p style='text-align:center;margin-top: 42%;'>No data exists for selected symbol</p>");

///////////////////////////////
//$symbol="B";
$symarr=array();
//echo "SELECT `name`,`symbol`,price,last_price,price_change,price_change_pct,volume FROM ".$symboltable." WHERE (widget='financial' or widget='Financial') and trim(symbol)='".mysqli_real_escape_string($conn,$symbol)."' LIMIT 1";
$symname=$sympchange=$symlastprice=$sympricechangepct="";
if ($cmsymbol = mysqli_query($mysqli,"SELECT `name`,`symbol`,price,last_price,price_change,price_change_pct,volume,updated_date FROM ".$symboltable." WHERE (widget='energy' or widget='Energy') and replace(symbol , ' ','')='".mysqli_real_escape_string($mysqli,$symbol)."' LIMIT 1")) {
	if (mysqli_num_rows($cmsymbol) > 0) {
		if(empty($cmrow["name"])) $cmrow["name"]="";
		if(empty($cmrow["price_change"])) $cmrow["price_change"]=0;
		if(empty($cmrow["last_price"])) $cmrow["last_price"]=0;
		if(empty($cmrow["price_change_pct"])) $cmrow["price_change_pct"]=0;
		$cmrow=mysqli_fetch_assoc($cmsymbol);
		$symname=$cmrow["name"];
		$sympchange=round($cmrow["price_change"],2);
		$symlastprice=round($cmrow["last_price"],2);
		$sympricechangepct=round($cmrow["price_change_pct"],2);
		$updated_date=$cmrow["updated_date"];
			/*"name"=>$cmrow["name"],
			"symbol"=>$cmrow["symbol"],
			"price"=>$cmrow["price"],
			"last_price"=>$cmrow["last_price"],
			"price_change"=>$cmrow["price_change"],
			"price_change_pct"=>$cmrow["price_change_pct"],
			"volume"=>$cmrow["volume"]*/
	}else die("<p style='text-align:center;margin-top: 42%;'>No data to show!</p>");
}else die("<p style='text-align:center;margin-top: 42%;'>No data to show!</p>");




if ($cksymbol = mysqli_query($mysqli,"SELECT `open`,`high`,low,close,adjusted_close,volume,date FROM ".$datatable." WHERE (widget='energy' or widget='Energy') and TRIM(symbol)='".mysqli_real_escape_string($mysqli,$symbol)."' ORDER BY `date`")) {
	if (mysqli_num_rows($cksymbol) > 0) {
		while($ckrow=mysqli_fetch_assoc($cksymbol)){
			$symarr[]=array(
				"Date"=>$ckrow["date"],
				"Open"=>(!empty($ckrow["open"])?round($ckrow["open"],2):0),
				"High"=>(!empty($ckrow["high"])?round($ckrow["high"],2):0),
				"Low"=>(!empty($ckrow["low"])?round($ckrow["low"],2):0),
				"Close"=>(!empty($ckrow["close"])?round($ckrow["close"],2):0),
				"Volume"=>(!empty($ckrow["volume"])?$ckrow["volume"]:0),
				"Adj Close"=>(!empty($ckrow["adjusted_close"])?round($ckrow["adjusted_close"],2):0),
				"Date123"=>"new Date(".strtotime($ckrow['date'])."*1000).getTime()"
			);
			// json string for chart 5
			$be_arr5[]='{date: new Date('.strtotime($ckrow["date"]).'*1000).getTime(),value: '.(!empty($ckrow["adjusted_close"])?round($ckrow["adjusted_close"],2):0).'}';
			//$be_arr5[]='{date: new Date('.strtotime($ckrow["date"]).').getTime(),value: '.(!empty($ckrow["adjusted_close"])?round($ckrow["adjusted_close"],2):0).'}';
			//$be_arr5[]='{date: new Date('.date("Y,m,d",strtotime($ckrow["date"])).').getTime(),value: '.(!empty($ckrow["adjusted_close"])?round($ckrow["adjusted_close"],2):0).'}';
		}
	}
}

//if(count($symarr) ==0) die("<p style='text-align:center;margin-top: 42%;'>No data to show".(!empty($symname)?" for ".$symname:"")."!</p>");

$json=json_encode($symarr);

$sympchange=(int)$sympchange;
if($sympchange > 0){ $arrowclass="glyphicon-arrow-up"; }
elseif($sympchange < 0){ $arrowclass="glyphicon-arrow-down"; }
else $arrowclass="";

//print_r($json);die();
?>

<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  /*height: 300px !important;*/
  height: 300px;
  max-width: 100%;
  margin-top:50px;
  margin-left:0 !important;
  margin-right:0 !important;
  padding-left:0 !important;
  padding-right:0 !important;
}
.glyphicon-arrow-down{color:red;}
.glyphicon-arrow-up{color:green;}
.borderbottom{border-bottom: 1px solid grey;}
g[aria-labelledby=id-47-title] {display:none !important;}
.cursorpoint{cursor:pointer;color:#333;}
.selected{background-color: #dcdcdc !important;}
.ovalshape{border-radius: 25px;margin-top:10px;background:none;border:none;font-size:13px;}
.ovalshape:focus {outline:0;}
.blackcolor{color:#000;}
.nopadding{margin:0;padding:0;position:sticky;bottom:0;width:100%;}
.fnt16{font-size:16px;}
</style>

<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/index.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/xy.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/Animated.js"></script>

<style>
#chartdiv111 {
  width: 100%;
  /*height: 100%;*/
   height: 300px;
}      
</style>

<script>

<!-- Chart code -->
var xAxis;
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv111");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

root.dateFormatter.setAll({
  dateFormat: "yyyy",
  dateFields: ["valueX"]
});


var data = [<?php echo implode(",",$be_arr5);?>];



// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  //focusable: true,
  //panX: true,
  //panY: true,
  //wheelX: "panX",
  //wheelY: "zoomX",
  //pinchZoomX:true
  
  panX: "none",
  panY: "none",
  wheelX: "none",
  wheelY: "none",
  pinchZoom: false,
  //layout: root.verticalLayout
}));

var easing = am5.ease.linear;


var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 20 });
xRenderer.labels.template.setAll({
  //rotation: -90,
  rotation: 290,
  //centerY: am5.p50,
  //centerX: am5.p100,
  centerX: am5.p100,
  paddingTop: 15
  //paddingRight: 15
});

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
//var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
  maxDeviation: 0.1,
  groupData: true,
  baseInterval: {
    timeUnit: "day",
	///timeUnit: "month",
    count: 1
  },
  /*
  renderer: am5xy.AxisRendererX.new(root, {
		minGridDistance: 20, 
  }),
  */
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));



var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.2,
  renderer: am5xy.AxisRendererY.new(root, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root, {
  //minBulletDistance: 10,
  //connect: false,
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  valueXField: "date",
  locationX: 0,
  tooltip: am5.Tooltip.new(root, {
    pointerOrientation: "horizontal",
    labelText: "{valueY}"
  })
}));

series.fills.template.setAll({
  fillOpacity: 0.2,
  visible: true
});

series.strokes.template.setAll({
  strokeWidth: 2
});


// Set up data processor to parse string dates
// https://www.amcharts.com/docs/v5/concepts/data/#Pre_processing_data
series.data.processor = am5.DataProcessor.new(root, {
  dateFormat: "yyyy-MM-dd",
  dateFields: ["date"]
});

series.data.setAll(data);




/*
series.bullets.push(function() {
  var circle = am5.Circle.new(root, {
    radius: 4,
    fill: root.interfaceColors.get("background"),
    stroke: series.get("fill"),
    strokeWidth: 2
  })

  return am5.Bullet.new(root, {
    sprite: circle
  })
});




// add scrollbar
chart.set("scrollbarX", am5.Scrollbar.new(root, {
  orientation: "horizontal"
}));
*/

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  xAxis: xAxis,
  behavior: "none"
}));
cursor.lineY.set("visible", false);

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()

//function changezoom_ar() {
	
	$( document ).ready(function() {
		$(".ovalshape").click(function(){
			var min_date = new Date(xAxis.getPrivate("min"));
			var max_date = new Date(xAxis.getPrivate("max"));
			
			var newMaxDateStr = max_date.getFullYear() + "," + (max_date.getMonth() + 1) + "," + max_date.getDate();
			
			var fil_id = $(this).attr("id");
			//alert(fil_id);

			if (fil_id == 'b10d') {				
				var newMinDateFull = max_date;				
				var day10 = newMinDateFull.getDate() - 10;
				newMinDateFull.setDate(day10);
		 
			} else if (fil_id == 'b1m') {
				var newMinDateFull = new Date(max_date.setMonth(max_date.getMonth() - 1));
			} else if (fil_id == 'b3m') {
				var newMinDateFull = new Date(max_date.setMonth(max_date.getMonth() - 3));
			} else if (fil_id == 'b6m') {
				var newMinDateFull = new Date(max_date.setMonth(max_date.getMonth() - 6));
			} else if (fil_id == 'b1y') {
				var newMinDateFull = new Date(max_date.setFullYear(max_date.getFullYear() - 1));
			} else if (fil_id == 'bmax') {
				var newMinDateFull = min_date;
			} else {
				var newMinDateFull = new Date(max_date.setFullYear(max_date.getFullYear() - 1));
			}
		if(newMinDateFull < min_date) newMinDateFull=min_date;
			var newMinDateStr = newMinDateFull.getFullYear() + "," + (newMinDateFull.getMonth() + 1) + "," + newMinDateFull.getDate();

			/*
			xAxis.zoomToDates(
			  new Date(2020, 11, 1),
			  new Date(2019, 11, 1)
			);
			*/			
			
			xAxis.zoomToDates(
			  new Date(newMaxDateStr),
			  new Date(newMinDateStr)			  			  
			);			
			
		}); 
	});
	
//}



</script>




						<style>
						.set_pad{padding:13px 13px 0; font-size:13px;}
						.symbollist .marbt55{margin-bottom: 6px;cursor:pointer;}
						.symbollist .glyphicon-arrow-down{color:red;}
						.symbollist .glyphicon-arrow-up{color:green;}
						.symbollist .borderleft{border-left: 1px solid #333;}
						.symbollist .selected{background-color: #dcdcdc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;}
						.symbollist{height: 416px;overflow-y: scroll;}

						</style>


	<div class="row set_pad">
		<div class="col-xs-5 col-sm-5 symbollist">
<?php
if(count($symarrdash)){
	foreach($symarrdash as $ky => $vl){ $vl["price_change"]=(float)$vl["price_change"];
		if($vl["price_change"] > 0){ $arrowclass="glyphicon-arrow-up"; }
		elseif($vl["price_change"] < 0){ $arrowclass="glyphicon-arrow-down"; }
		else $arrowclass="";

		if(!empty($vl["last_price"])) $vl["last_price"]=$vl["last_price"]+0;
?>
			<div class="row marbt55 <?php if($firstsymbol==$vl["symbol"]){echo 'selected'; } ?>" id="<?php echo @trim($vl["symbol"]); ?>">
				<div class="col-xs-7 col-sm-7 col-md-7"><?php echo @trim($vl["name"]); ?></div>
				<div class="col-xs-1 col-sm-1 col-md-1 text-right"><i class="glyphicon <?php echo $arrowclass; ?>"></i></div>
				<div class="col-xs-3 col-sm-3 col-md-3"><?php echo @trim(number_format($vl["last_price"],2,'.',',')); ?></div>
			</div>
<?php
	}
}
?>
		</div>
		<div class="col-xs-7 col-sm-7 <!--borderleft-->">









<!-- HTML -->
    <div id="controls" style="width: 100%; overflow: hidden; display:none;">
		<div style="float: left; margin-left: 15px;">
		From: <input type="text" id="fromfield" class="amcharts-input" />
		To: <input type="text" id="tofield" class="amcharts-input" />
		</div>
		<div style="float: right; margin-right: 15px;">
			<button id="b1me" class="amcharts-input">1m</button>
			<button id="b3me" class="amcharts-input">3m</button>
			<button id="b6me" class="amcharts-input">6m</button>
			<button id="b1ye" class="amcharts-input">1y</button>
			<button id="bytde" class="amcharts-input">YTD</button>
			<button id="bmaxe" class="amcharts-input">MAX</button>
		</div>
    </div>
<table width="100%" cellspacing="15" class="fnt16">
	<tr class="borderbottom">
		<td><span style="float:left;"><?php echo $symname; ?></span><span style="float:right;"><b><?php echo number_format($symlastprice+0,2,'.',','); ?></b></span></td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr class="borderbottom">
		<td><span style="float:left;"><i class="glyphicon <?php echo $arrowclass; ?>"></i><b class="blackcolor"><?php echo ($sympricechangepct+0); ?>(<?php echo $sympchange; ?>%)</b></span><span style="float:right;"><?php if(!empty($updated_date)){ ?><i>Updated <?php $date = date_create($updated_date, timezone_open('Pacific/Nauru'));echo date_format($date, 'm/d/Y g:i:sA'); ?></i><?php } ?></span></td>
	</tr>
</table>
<div class="nopadding">
	<table width="100%" cellspacing="15">
		<tr>
			<td colspan=2>
			<div id="chartdiv-_-_-"></div>
			<div id="chartdiv111"></div>
			</td>
		</tr>
	</table>
	<table style="width:100%;text-align:center;font-weight:bold;">
		<tr><td><button id="b10d" class="ovalshape">10D</button></td><td><button id="b1m" class="ovalshape">1M</button></td><td><button id="b3m" class="ovalshape">3M</button></td><td><button id="b6m" class="ovalshape">6M</button></td><td><button id="b1y" class="ovalshape">1Y</button></td><td><button id="bmax" class="ovalshape selected">All</button></td></tr>
	</table>
</div>




</div>
</div>


<script>
$( document ).ready(function() {
    //$('#loadegraph').attr('src', 'assets/ajax/subpages/energy-graph.php?ct=<?php echo time(); ?>&symbol=<?php echo rawurlencode ($firstsymbol); ?>');
	$( ".marbt55" ).removeClass( "selected" );
	var phpsymbol = "#<?php echo $symbol;?>";
	$('div[id="<?php echo $symbol;?>"]').addClass( "selected" );
});

$('.marbt55').on('click', function() {
	$this=$(this);
	//$( ".marbt55" ).removeClass( "selected" );
	//$this.addClass("selected");
    $(parent.document).find('#loadegraph').attr('src', 'assets/ajax/subpages/energy-graph.php?ct=<?php echo time(); ?>&symbol='+encodeURIComponent($(this).attr("id"))+'');
});
</script>






<script>
$(document).ready(function(){
  $('g[aria-labelledby="id-66-title"]').css({"display":"none !important"});

	$('.ovalshape').on('click', function() {
		$this=$(this);
		$( ".ovalshape" ).removeClass( "selected" );
		$this.addClass("selected");
	});

	$( "#b1y" ).trigger( "click" );
});
</script>
</body>
</html>
