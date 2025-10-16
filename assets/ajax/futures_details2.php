<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_input_vars', 10000);
 require_once("../inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

 $_SESSION["group_id"]=3;
if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");

$_SESSION["user_id"]=23;
$user_one=$_SESSION["user_id"];
?>

<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="http://www.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css">
        <style type="text/css">
            body{ padding:10px; font-size:12px; background:#F1F1F1; }
            h1{ font-size:2em; text-align:center; border-bottom:1px solid #CCC; margin-bottom:1em; }

            .selectMonths{ float:right; position:relative; display:inline-block; }
            .selectMonthsselect {height: 30px; }
            .selectMonths > i{ position:absolute; right:5px; top:5px; opacity:0.35; font-style:normal; font-size:18px; transition:0.2s; pointer-events:none; }
            .selectMonths > input{ text-transform:capitalize; padding-left:10px; cursor:default; cursor:pointer; }
            .selectMonths:hover > i{ opacity:.7; }
            .selectMonths + .selectMonths{ float:none; }

        </style>

        <link rel="stylesheet" href="assets/css/picker.css">

         <!-- scripts -->
		 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="assets/js/tether.min.js"></script>
        <script src="assets/js/datePicker.js"></script>
        <script>
			var lastday = function(y,m){
				return  new Date(y, m +1, 0).getDate();
			}

			function formatDate(date) {
				var d = new Date(date),
					month = '' + (d.getMonth() + 1),
					day = '' + d.getDate(),
					year = d.getFullYear();

				if (month.length < 2) month = '0' + month;
				if (day.length < 2) day = '0' + day;

				return [year, month, day].join('-');
			}
            $('.selectMonths:first input')
                .rangePicker({ minDate:[12,2015], maxDate:[12,2020], RTL:true })
                // subscribe to the "done" event after user had selected a date
                .on('datePicker.done', function(e, result){
                    if( result instanceof Array ){
						var from_date=formatDate(new Date(result[0][1], result[0][0] - 1));
						var to_date=formatDate(new Date(result[1][1], result[1][0] - 1));
						var cus_dates=from_date+'~'+to_date;
                        //console.log(new Date(result[0][1], result[0][0] - 1), new Date(result[1][1], result[1][0] - 1));
						//var cus_dates=new Date(result[0][1], result[0][0] - 1+'~'+new Date(result[1][1], result[1][0] - 1;
						loadfmap("custom",cus_dates);
                    }
                    else{
						loadfmap("month",result);
                        //console.log(result);
                    }
                });

            // update settings
           $('.selectMonths:last input').rangePicker({ setDate:[[12,2015],[12,2020]], closeOnSelect:true });

        </script>


<?php

if(isset($_GET['symb']) and $_GET['symb'] != "")
{
	$fd_arr=array();
	$symb=$mysqli->real_escape_string($_GET['symb']);
	/*if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,`PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."' ORDER BY `CONTRACT YEAR`")) {*/
	if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,GROUP_CONCAT(DISTINCT `PRODUCT DESCRIPTION`) AS `PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."'  GROUP BY `PRODUCT SYMBOL`,`CONTRACT MONTH`,`CONTRACT YEAR`  ORDER BY `CONTRACT YEAR`")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($fd_symbol,$fd_pdesc,$fd_cmonth,$fd_cyear);
			//$fdstmt->bind_result($fd_symbol,$fd_cmonth,$fd_cyear);
			while($fdstmt->fetch()){
				$fd_arr[$fd_cyear][]=$fd_cmonth;
			}
				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
		}
	}
	if(!count($fd_arr)) die("No data to show!");
	//else $fd_arr=array_unique($fd_arr);
?>
<style>
.dropdown-menu[title]::before {
    content: attr(title);
    /* then add some nice styling as needed, eg: */
    display: block;
    font-weight: bold;
    padding: 4px;
	text-align:center;
}
.dropdown-menu li{
	text-align:center;
}
hr{border-top: 1px solid #ccc;}
</style>


<!-- <div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div>-->

        <div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div>

<b><img id="mvbk" onclick="fmove_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
<h3><?php echo $fd_symbol." ".$fd_pdesc; ?></h3>
<hr>



<div style="width:8%;float:left">
	<label for="amount1">Weight1:</label>
	<input type="text" id="amount1" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q1">
	<div id="slider-vertical1" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount2">Weight2:</label>
	<input type="text" id="amount2" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q2">
	<div id="slider-vertical2" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount3">Weight3:</label>
	<input type="text" id="amount3" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q3">
	<div id="slider-vertical3" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount4">Weight4:</label>
	<input type="text" id="amount4" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q4">
	<div id="slider-vertical4" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount5">Weight5:</label>
	<input type="text" id="amount5" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q5">
	<div id="slider-vertical5" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount6">Weight6:</label>
	<input type="text" id="amount6" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q6">
	<div id="slider-vertical6" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount7">Weight7:</label>
	<input type="text" id="amount7" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q7">
	<div id="slider-vertical7" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount8">Weight8:</label>
	<input type="text" id="amount8" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q8">
	<div id="slider-vertical8" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount9">Weight9:</label>
	<input type="text" id="amount9" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q9">
	<div id="slider-vertical9" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount10">Weight10:</label>
	<input type="text" id="amount10" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q10">
	<div id="slider-vertical10" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount11">Weight11:</label>
	<input type="text" id="amount11" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q11">
	<div id="slider-vertical11" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount12">Weight12:</label>
	<input type="text" id="amount12" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q12">
	<div id="slider-vertical12" style="height:200px;"></div>
</div>


<div style="clear:both">&nbsp;</div>
<hr>

<script>

$( function() {
		$( "#slider-vertical1" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value:0,
			slide: function( event, ui ) {

				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount1" ).val( parseInt(per_value)+"%" );
				$( "#q1" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical1" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount1" ).val( parseInt(per_value)+"%" );
		$( "#q1" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical2" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value:0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount2" ).val( parseInt(per_value)+"%" );
				$( "#q2" ).val( per_value/100 );
			}
		});

		var per_value=((($( "#slider-vertical2" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount2" ).val( parseInt(per_value)+"%" );
		$( "#q2" ).val( per_value/100 );

	} );

	$( function() {
		$( "#slider-vertical3" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount3" ).val( parseInt(per_value)+"%" );
				$( "#q3" ).val( per_value/100 );
			}
		});

		var per_value=((($( "#slider-vertical3" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount3" ).val( parseInt(per_value)+"%" );
		$( "#q3" ).val( per_value/100 );

	} );

	$( function() {
		$( "#slider-vertical4" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount4" ).val( parseInt(per_value)+"%" );
				$( "#q4" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical4" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount4" ).val( parseInt(per_value)+"%" );
		$( "#q4" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical5" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount5" ).val( parseInt(per_value)+"%" );
				$( "#q5" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical5" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount5" ).val( parseInt(per_value)+"%" );
		$( "#q5" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical6" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount6" ).val( parseInt(per_value)+"%" );
				$( "#q6" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical6" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount6" ).val( parseInt(per_value)+"%" );
		$( "#q6" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical7" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount7" ).val( parseInt(per_value)+"%" );
				$( "#q7" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical7" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount7" ).val( parseInt(per_value)+"%" );
		$( "#q7" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical8" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount8" ).val( parseInt(per_value)+"%" );
				$( "#q8" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical8" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount8" ).val( parseInt(per_value)+"%" );
		$( "#q8" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical9" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount9" ).val( parseInt(per_value)+"%" );
				$( "#q9" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical9" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount9" ).val( parseInt(per_value)+"%" );
		$( "#q9" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical10" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount10" ).val( parseInt(per_value)+"%" );
				$( "#q10" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical10" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount10" ).val( parseInt(per_value)+"%" );
		$( "#q10" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical11" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount11" ).val( parseInt(per_value)+"%" );
				$( "#q11" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical11" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount11" ).val( parseInt(per_value)+"%" );
		$( "#q11" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical12" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount12" ).val( parseInt(per_value)+"%" );
				$( "#q12" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical12" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount12" ).val( parseInt(per_value)+"%" );
		$( "#q12" ).val( per_value/100 );
	} );

function fmove_back(){
	$('#ftable').show();
	$('#fselect').html('');
	$('#fresponse').html('');
	$('#ftopdialog').html('');
	$('#fdetails').html('');
}
function loadfmap(type,month){
	$('#fresponse').html('');
	var q="&q1="+$("#q1").val()+"&q2="+$("#q2").val()+"&q3="+$("#q3").val()+"&q4="+$("#q4").val()+"&q5="+$("#q5").val()+"&q6="+$("#q6").val()+"&q7="+$("#q7").val()+"&q8="+$("#q8").val()+"&q9="+$("#q9").val()+"&q10="+$("#q10").val()+"&q11="+$("#q11").val()+"&q12="+$("#q12").val();
	if(type=='custom'){
		var cus_type="custom@"+"<?php echo $fd_symbol; ?>"+"@"+month+q;
	}else{
		var cus_type="month@"+"<?php echo $fd_symbol; ?>"+"@"+month+q;
	}

	$('#fresponse').html('<iframe id="frame" src="assets/ajax/futures_details2.php?action=view&type='+cus_type+'&month='+month+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
    //$('#fresponse').html('assets/ajax/futures_details.php?action=view&fdate='+fdate);
	//$("#frame").attr("src", "http://www.example.com/");
}
</script>

<?php
}else if(isset($_GET['type']) and $_GET['type'] != "")
{
	$fd_arr=array();
	$types=explode("@",$_GET['type']);
	if(count($types) != 3) die("Wrong Parameter provided!");

	$cus_type=$mysqli->real_escape_string($types[0]);
	$fsym=$mysqli->real_escape_string($types[1]);

	$q1=(float)$_GET['q1'];
	$q2=(float)$_GET['q2'];
	$q3=(float)$_GET['q3'];
	$q4=(float)$_GET['q4'];
	$q5=(float)$_GET['q5'];

	$q6=(float)$_GET['q6'];
	$q7=(float)$_GET['q7'];
	$q8=(float)$_GET['q8'];
	$q9=(float)$_GET['q9'];
	$q10=(float)$_GET['q10'];

	$q11=(float)$_GET['q11'];
	$q12=(float)$_GET['q12'];

	if($cus_type=='custom'){
		$month=$mysqli->real_escape_string($types[2]);
		$arr_date=explode("~",$month);
		$from_date=$arr_date[0];
		$to_date=$arr_date[1];

		$a_date = "2009-11-23";
		$date = new DateTime($to_date);
		$date->modify('last day of this month');
		$new_to_date=$date->format('Y-m-d');

		 $buit_query="SELECT a.TRADEDATE, (SUM(a.SETTLE)/('".$q1."'+'".$q2."'+'".$q3."'+'".$q4."'+'".$q5."'+'".$q6."'+'".$q7."'+'".$q8."'+'".$q9."'+'".$q10."'+'".$q11."'+'".$q12."')) AS strip_price
FROM (SELECT nymex_future.TRADEDATE, STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'), '%Y-%m-%d') AS FUTUREMONTH,
CASE WHEN nymex_future.`CONTRACT MONTH`=1 THEN nymex_future.SETTLE * '".$q1."'
WHEN nymex_future.`CONTRACT MONTH`=2 THEN nymex_future.SETTLE * '".$q2."'
WHEN nymex_future.`CONTRACT MONTH`=3 THEN nymex_future.SETTLE * '".$q3."'
WHEN nymex_future.`CONTRACT MONTH`=4 THEN nymex_future.SETTLE * '".$q4."'
WHEN nymex_future.`CONTRACT MONTH`=5 THEN nymex_future.SETTLE * '".$q5."'
WHEN nymex_future.`CONTRACT MONTH`=6 THEN nymex_future.SETTLE * '".$q6."'
WHEN nymex_future.`CONTRACT MONTH`=7 THEN nymex_future.SETTLE * '".$q7."'
WHEN nymex_future.`CONTRACT MONTH`=8 THEN nymex_future.SETTLE * '".$q8."'
WHEN nymex_future.`CONTRACT MONTH`=9 THEN nymex_future.SETTLE * '".$q9."'
WHEN nymex_future.`CONTRACT MONTH`=10 THEN nymex_future.SETTLE * '".$q10."'
WHEN nymex_future.`CONTRACT MONTH`=11 THEN nymex_future.SETTLE * '".$q11."'
WHEN nymex_future.`CONTRACT MONTH`=12 THEN nymex_future.SETTLE * '".$q12."'
ELSE nymex_future.SETTLE END AS SETTLE FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0) a
LEFT JOIN (SELECT nymex_future.TRADEDATE,MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',
nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MinMonth,MAX(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MaxMonth
FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0 GROUP BY nymex_future.TRADEDATE) b ON a.TRADEDATE = b.TRADEDATE
WHERE a.FUTUREMONTH >= '".$from_date."' AND a.FUTUREMONTH <= '".$new_to_date."' AND '".$from_date."' >= b.MinMonth  AND '".$new_to_date."' <= b.MaxMonth  GROUP BY a.TRADEDATE
";
	}else{
		$month=$mysqli->real_escape_string($types[2]);
		 $buit_query="SELECT a.TRADEDATE, (SUM(a.SETTLE)/('".$q1."'+'".$q2."'+'".$q3."'+'".$q4."'+'".$q5."'+'".$q6."'+'".$q7."'+'".$q8."'+'".$q9."'+'".$q10."'+'".$q11."'+'".$q12."')) AS strip_price
FROM (SELECT nymex_future.TRADEDATE,STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d') AS FUTUREMONTH,
CASE 	WHEN nymex_future.`CONTRACT MONTH`=1 THEN nymex_future.SETTLE * '".$q1."'
WHEN nymex_future.`CONTRACT MONTH`=2 THEN nymex_future.SETTLE * '".$q2."'
WHEN nymex_future.`CONTRACT MONTH`=3 THEN nymex_future.SETTLE * '".$q3."'
WHEN nymex_future.`CONTRACT MONTH`=4 THEN nymex_future.SETTLE * '".$q4."'
WHEN nymex_future.`CONTRACT MONTH`=5 THEN nymex_future.SETTLE * '".$q5."'
WHEN nymex_future.`CONTRACT MONTH`=6 THEN nymex_future.SETTLE * '".$q6."'
WHEN nymex_future.`CONTRACT MONTH`=7 THEN nymex_future.SETTLE * '".$q7."'
WHEN nymex_future.`CONTRACT MONTH`=8 THEN nymex_future.SETTLE * '".$q8."'
WHEN nymex_future.`CONTRACT MONTH`=9 THEN nymex_future.SETTLE * '".$q9."'
WHEN nymex_future.`CONTRACT MONTH`=10 THEN nymex_future.SETTLE * '".$q10."'
WHEN nymex_future.`CONTRACT MONTH`=11 THEN nymex_future.SETTLE * '".$q11."'
WHEN nymex_future.`CONTRACT MONTH`=12 THEN nymex_future.SETTLE * '".$q12."'
ELSE nymex_future.SETTLE END AS SETTLE FROM futures.nymex_future
WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0) a
LEFT JOIN (SELECT nymex_future.TRADEDATE,MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MinMonth,
DATE_ADD(MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')), INTERVAL '".$month."' month) AS MaxMonth
FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0 GROUP BY nymex_future.TRADEDATE) b ON a.TRADEDATE = b.TRADEDATE
WHERE a.FUTUREMONTH >= b.MinMonth AND a.FUTUREMONTH < b.MaxMonth GROUP BY a.TRADEDATE
";
	}
//echo $buit_query;

if ($fdstmt = $mysqli->prepare($buit_query))
{

   $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($fd_TRADEDATE,$fd_strip_price);
			while($fdstmt->fetch()){$tfdate=$fd_TRADEDATE;
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				$fd_strip_price=($fd_strip_price==""?0:$fd_strip_price);
				$fd_arr[]='{"date": "'.$tfdate.'","value": '.$fd_strip_price.'}';
			}


				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
		}
}


	if(!count($fd_arr)) die("No data to show!");
?>
<style>
#fchartdiv {
	width	: 100%;
	height	: 500px;
}
#amcharts-chart-div a {display:none !important;}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<div id="fchartdiv"></div>
<script>
window.onload = function () {
var chart = AmCharts.makeChart("fchartdiv", {
    "type": "serial",
    "theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "id": "g1",
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
          "color":"#ffffff"
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "value",
        "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":true
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    },
    "dataProvider": [
<?php echo implode(",",$fd_arr);?>
	]
});

chart.addListener("rendered", zoomChart);

zoomChart();
function zoomChart() {
    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
}
}
</script>


<?php
}
?>
