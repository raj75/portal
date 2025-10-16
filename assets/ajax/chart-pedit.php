<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_GET["etype"]) or ($_GET["etype"] != "CO" and $_GET["etype"] !="NG") or !isset($_GET["cm1_month"]) or !isset($_GET["cm1_year"]) or @trim($_GET["cm1_month"]) == "" or @trim($_GET["cm1_month"]) == "0" or @trim($_GET["cm1_year"]) == "" or @trim($_GET["cm1_year"]) == "0")
	die("Error Occured. Please try after sometime!");
?>	
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<style>
.ui-multiselect-menu{width:auto !important;}
</style>
<?php
if ($stmt = $mysqli->prepare("SELECT _month, _year FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($_GET["etype"])."' and id not in (SELECT id FROM cme_tmp WHERE _month = '".$mysqli->real_escape_string(@trim($_GET["cm1_month"]))."' and _year = '".$mysqli->real_escape_string(@trim($_GET["cm1_year"]))."' and energy_type='".$mysqli->real_escape_string($_GET["etype"])."' group by _month,_year ORDER BY id) and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) >= DATE_ADD(date_format(now(), '%Y-%m-01'), INTERVAL 1 MONTH) group by _month,_year ORDER BY id")) { 
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
?>
			<label>Select dates to compare: </label>
			<select class="date-cmp" multiple="multiple">
<?php
				$stmt->bind_result($_month,$_year);
				while($stmt->fetch()) {
?>
				<option class="cmpdates" value="<?=$_month;?>:<?=$_year;?>"><?=$_month." ".$_year;?></option>
<?php
				}
?>
			</select>
<?php
		}
}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
?>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	$('.date-cmp').multiselect();
	$("#<?php echo $_GET["etype"];?>iframe").attr('src', "assets/plugins/chart/chart.php?energy_type=<?php echo $_GET["etype"];?>&cm1_month=<?php echo $_GET["cm1_month"];?>&cm1_year=<?php echo $_GET["cm1_year"];?>");
	$(".date-cmp").on( 'keyup change', function (event) {
		$thiss = this;
		var selectedoptions = [];
		$.each($("input[name='multiselect_0']:checked"), function(){            
			selectedoptions.push($(this).val());
		});

		if(selectedoptions.length > 10)
		{
			alert("Please select max 10 from the list");
		}else if(selectedoptions.length <= 10 && selectedoptions.length >= 1){
			datearr=[];
			datearr.push("cm1_month=<?php echo $_GET["cm1_month"];?>&cm1_year=<?php echo $_GET["cm1_year"];?>");
			for (i = 0; i < selectedoptions.length; i++) { 
				tmparr = selectedoptions[i].split(":");
				datearr.push("cm"+(i+2)+"_month="+tmparr[0]+"&cm"+(i+2)+"_year="+tmparr[1]);
			}			
			$("#<?php echo $_GET["etype"];?>iframe").attr('src', "assets/plugins/chart/chart.php?energy_type=<?php echo $_GET["etype"];?>&"+datearr.join('&'));
		}else{
			$("#<?php echo $_GET["etype"];?>iframe").attr('src', "assets/plugins/chart/chart.php?energy_type=<?php echo $_GET["etype"];?>&cm1_month=<?php echo $_GET["cm1_month"];?>&cm1_year=<?php echo $_GET["cm1_year"];?>");
		}
	});
});
</script>