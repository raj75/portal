<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
//if($user_one != 1) die("under construction!");



if(isset($_GET["action"]) and $_GET["action"]=="electricity"){
$ct_count=0;$first_iso="";
?>
	<form class="smart-form">
		<fieldset>
			<div class="row">
				<section class="col col-6"><b>Select ISO</b>
						<label class="select"> <i class="icon-append fa fa-user"></i>
						<select name="editregion" id="editregion" placeholder="Region" class="">
							<option value='All' Selected>&nbsp;&nbsp;All</option>
						<?php
							//if($_SESSION["group_id"] == 5) $msqll = ' company_id="'.$comp_id.'"';
							//else $msqll = ' company_id != 1';
						   if ($stmt = $mysqli->prepare('SELECT DISTINCT ISO FROM ubm_ice.clearing_code where (`GROUP`="Electricity" OR `GROUP`="Physical Environmental") AND Product <> "Real-Time" AND contract_type="Monthly" and ISO != "" and ISO IN ("PJM","NYISO","MISO","CAISO","ERCOT","ISO New England","SPP")')){
								$stmt->execute();
								$stmt->store_result();
								if ($stmt->num_rows > 0) {
									$ct_count=0;
									$stmt->bind_result($r_name);
									while($stmt->fetch()){
										$encodeiso=urlencode($r_name);
										echo "<option value='".$encodeiso."'>&nbsp;&nbsp;".$r_name."</option>";
										if($ct_count==0){$first_iso=$encodeiso;}
										$ct_count ++;
									}
								}
							}else{
								header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
								exit();
							}
						?>
						</select>
						</label>
				</section>
			</div>
		</fieldset>
	</form>
	<div id="tpcont3-1"></div>
	<div id="tpchartcont31-3dtable"></div>
	<iframe id="tpchartcont31-3dchart" src="" width="100%" height="500px" style="display:none;"></iframe>
<script>
$('#editregion').on('change', function() {
	$('#tpcont3-1').html('');
  $('#tpcont3-1').load("assets/ajax/subpages/trading-platform-3-1.php?action=iso&ct=<?php echo time(); ?>&iso="+this.value);
});
$(document).ready(function(){
	//loadURL("assets/ajax/subpages/trading-platform-3-1.php?action=pjm&ct=<?php echo time(); ?>", $('#tpcont3-1'));
	<?php if($first_iso != ""){ ?>
	$("#tpcont3-1").load("assets/ajax/subpages/trading-platform-3-1.php?action=iso&ct=<?php echo time(); ?>&iso=All");
	<?php } ?>
});
</script>
<?php
}
?>
