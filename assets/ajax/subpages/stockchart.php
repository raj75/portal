<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  .marbt5{margin-bottom: 6px;cursor:pointer;}
  </style>
</head>
<body>
<?php

date_default_timezone_set('UTC');

ini_set("memory_limit", "-1");
ini_set('max_execution_time', 0);

$symboltable="financial_widget";
$datatable="financial_widget_data";


//$conn = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
$conn = mysqli_connect("localhost", "root","","vervantis");
if (!$conn) {
    printf("Connect failed: %s\n", mysqli_connect_errno());
    exit();
}


$symarr=array();
$firstsymbol="";
if ($cksymbol = mysqli_query($conn,"SELECT `name`,`symbol`,price,last_price,price_change,price_change_pct,volume FROM ".$symboltable." WHERE widget='financial' ORDER BY `name`")) {
	if (mysqli_num_rows($cksymbol) > 0) {		
		while($ckrow=mysqli_fetch_assoc($cksymbol)){
			if(empty($firstsymbol)) $firstsymbol=$ckrow["symbol"];
			$symarr[$ckrow["symbol"]]=array(
				"name"=>$ckrow["name"],
				"symbol"=>$ckrow["symbol"],
				"price"=>$ckrow["price"],
				"last_price"=>$ckrow["last_price"],
				"price_change"=>$ckrow["price_change"],
				"price_change_pct"=>$ckrow["price_change_pct"],
				"volume"=>$ckrow["volume"]
			);
		}
	}
}
//print_r($symarr);die();
?>


<div class="container">
	<div class="row">
		<div class="col-sm-5">
<?php 
if(count($symarr)){
	foreach($symarr as $ky => $vl){ $vl["price_change"]=(int)$vl["price_change"];
		if($vl["price_change"] > 0){ $arrowclass="glyphicon-arrow-up"; }
		elseif($vl["price_change"] < 0){ $arrowclass="glyphicon-arrow-down"; }
		else $arrowclass="";
		
		if(!empty($vl["last_price"])) $vl["last_price"]=$vl["last_price"]+0;
?>
			<div class="row marbt5" id="<?php echo @trim($vl["symbol"]); ?>">
				<div class="col-sm-7"><?php echo $vl["name"]; ?></div>
				<div class="col-sm-1"><i class="glyphicon <?php echo $arrowclass; ?>"></i></div>
				<div class="col-sm-4"><?php echo $vl["last_price"]; ?></div>
			</div>
<?php 
	} 
}
?>
		</div>
		<div class="col-sm-7">
			<iframe id="loadgraph" style="width:100%;height:585px;border:none;"></iframe>
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
    $('#loadgraph').attr('src', 'stockchart-graph.php?symbol=<?php echo rawurlencode ($firstsymbol); ?>');
});
function showgraph(ssym){
	$('#loadgraph').attr('src', 'stockchart-graph.php?symbol='+ssym);
}

$('.marbt5').on('click', function() {
  $('#loadgraph').attr('src', 'stockchart-graph.php?symbol='+encodeURIComponent($(this).attr("id"))+'');
});
</script>
</body>
</html>