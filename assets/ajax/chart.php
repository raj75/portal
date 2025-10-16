<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$s_arr=array();
if ($stmt = $mysqli->prepare('SELECT id,_wban,_name,_state,_location FROM `station` where _state != "" and _location != "" order by _state,_name')) {
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($s_id,$s_wban,$s_name,$s_state,$s_location);
		while($stmt->fetch()) {
			$s_arr[$s_state][]=array("id"=>$s_id,"wban"=>$s_wban,"name"=>$s_name,"state"=>$s_state,"location"=>$s_location);
		}
	}
}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

?>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<style>
.ui-multiselect-menu{width:auto !important;max-width:50% !important;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Reports
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php
if(count($s_arr))
{
?>
			Choose from list to show weather: <select id="selectWban" name="selectWban[]" multiple="multiple" style="width:100px">
<?php
	foreach($s_arr as $kys => $vls)
	{
		echo '<optgroup label="State: '.$kys.'">';
		foreach($vls as $ky => $vl)
		{
			echo '<option value="'.$vls[$ky]["wban"].'">Wban: '.$vls[$ky]["wban"].' , Name: '.$vls[$ky]["name"].' , Location: '.$vls[$ky]["location"].'</option>';
		}
	}
	echo '</select>';
}
?>
		</article>
	</div>
</section>

<!--<iframe src="assets/plugins/weatherchart/chart1.php?wban=" frameborder="0" width="100%" height="600px"></iframe>-->
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#selectWban").multiselect();
	$("#selectWban").on( 'keyup change', function () {
		var selectedoptions = [];
		$.each($("input[name='multiselect_selectWban']:checked"), function(){
			selectedoptions.push($(this).val());
		});

		if(selectedoptions.length > 3)
		{
			alert("Please select max 3 from the list");
		}else if(selectedoptions.length <= 3 && selectedoptions.length >= 1){
			$('#loadchart').remove();
			$('#widget-grid').after('<iframe src="assets/plugins/weatherchart/chart1.php?wban='+selectedoptions.join(',')+'" frameborder="0" width="100%" height="600px" id="loadchart"></iframe>');
		}else{
			$('#loadchart').remove();
		}
	});
});
</script>
