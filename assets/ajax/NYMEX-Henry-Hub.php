<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");
?>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Charts
			<span>> Natural Gas > NYMEX Henry Hub </span>
		</h1>
	</div>
</div>
<style>
.fullwidth{width:100% !important;}
.marginleft-6{margin-left:6%;}
.back-white{background-color:#fff;}
.padleft-83{padding-left:83px;}
</style>
<div class="container fullwidth">
  <div class="embed-responsive embed-responsive-16by9 back-white">
	<p>&nbsp;</p>
<?php
	if ($stmt = $mysqli->prepare("SELECT _month, _year FROM cme_tmp WHERE energy_type='NG' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) >= DATE_ADD(date_format(now(), '%Y-%m-01'), INTERVAL 1 MONTH) group by _month,_year ORDER BY id")) { 
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$ct=0;
?>
<script src="
https://cdn.jsdelivr.net/npm/jquery-multiselect@1.0.0/jquery-MultiSelect.min.js
"></script>
<script>
function changedata(data)
{
	if(data != ""){
		$(".cm-date").html("");
		$(".cm-date").load("assets/ajax/chart-pedit.php?etype=NG"+data);
	}
}
</script>
	<div class="row">
		<div class="col-xs-3 padleft-83">
			<label>Select date: </label>
			<select class="marginleft-6" onchange="changedata(this.options[this.selectedIndex].value);">
<?php
				$stmt->bind_result($_month,$_year);
				while($stmt->fetch()) {
					if($ct==0)
					{
					?>	<script>changedata("&cm1_month=<?=$_month;?>&cm1_year=<?=$_year;?>");</script><?php
					}
					$ct++;
?>
				<option value="&cm1_month=<?=$_month;?>&cm1_year=<?=$_year;?>"><?=$_month." ".$_year;?></option>
<?php
				}
?>
			</select>
		</div>
		<div class="col-xs-9 cm-date"></div>
	</div>
<iframe class="embed-responsive-item" id="NGiframe" frameborder="0" width="100%" height="975px"></iframe>
<?php
		}else{?>
			<p class="marginleft-6">No data to show!</p>
<?php	}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
	$mysqli->close();
?>
  </div>
</div>