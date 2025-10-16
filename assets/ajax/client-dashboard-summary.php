<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");
	
$uid=$_SESSION['user_id'];
$comp_id=$_SESSION['company_id'];
$first_company=0;

$showdemo=1;
$subquery=((isset($showdemo) and $showdemo==1)?"&showdemo=1":"");
?>
<style>
.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 2%;
    top: 0;
    width: 6%;
    z-index: 9999;
}
#fitopdialog{
	overflow-y:hidden !important;
}
.m-bottom50{font-weight:bold;z-index:98;}
.m-bottom50 span{vertical-align: top;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Dashboard Widgets  
			<span>> 
				Client Dashboard Summary
			</span>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
	
	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-bottom50">
			<input type="checkbox" <?php echo ($showdemo==1?"CHECKED":""); ?> value="Demo Company" id="hidedemo" class="flleft"><span class="flleft">Hide Demo Company</span>
		</article>
	<?php } ?>

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Edit Client Dashboard Summary</h2>
				</header>
			<div id="edit-dialog-message" title="Edit Client Dashboard Summary">
			<form class="smart-form">
				<fieldset>
					<div class="row">
						<section class="col col-6"><b>Select User Name To Edit</b>
								<label class="select"> <i class="icon-append fa fa-user"></i>
								<select name="editcid" id="editcid" placeholder="Company Name" class="">
								<?php
									if($_SESSION["group_id"] == 5) $msqll = ' company_id="'.$comp_id.'"';
									else $msqll = ' company_id != 1';
								   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company where '.$msqll.'  ORDER BY company_name')){ 

//('SELECT id,firstname,lastname FROM user where (usergroups_id=3 or usergroups_id=5) '.$msqll.'  ORDER BY firstname')){ 

										$stmt->execute();
										$stmt->store_result();
										if ($stmt->num_rows > 0) {
											$ct_count=0;
											$stmt->bind_result($__id,$c_name);
											while($stmt->fetch()){
												echo "<option value='".$__id."'>&nbsp;&nbsp;".$c_name."</option>";
												if($ct_count==0){$first_company=$__id;}
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
			</div>
			<div id="getdetails"></div>
			</div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<div id="cdsresponse"></div>
<script type="text/javascript">
$('#editcid').on('change', function() {
	$('#getdetails').html('');
  $('#getdetails').load('assets/ajax/client-dashboard-summary-edit.php?action=edit&cds='+this.value);
});
<?php
if(@trim($first_company) != 0 and @trim($first_company) != "" and @trim($first_company) != "0"){
		echo "$('#getdetails').load('assets/ajax/client-dashboard-summary-edit.php?action=edit&cds=".$first_company."');";
} ?>

	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
	$('#hidedemo').change(function () {
		if($('#hidedemo').prop("checked")==1){
			var showdemo=1;
		}else{
			var showdemo=0;
		}
		
	});
	<?php } ?>
</script>