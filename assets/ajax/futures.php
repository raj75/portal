<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,48)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION['user_id'];

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
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats"></i> 
				Market Resources 
			<span>> 
				Futures Pricing
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

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;">
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
				<button class="btn-primary" align="right" id="new-futures" style="height: 30px !important;width: auto !important;">Add New Futures</button>
<?php } ?>
			</div>
			<div id="ftable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
<section id="fdetails"></section>
<?php } 
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<div id="fselect"></div>
<div id="fresponse"></div>
<div id="ftopdialog"></div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
	$("#new-futures").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$("#dialog-message").remove();
		$('#fresponse').html('');
		$('#fresponse').load('assets/ajax/futures_add.php?action=add');
	});
<?php }
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
	//$('#ftable').load("assets/ajax/futures_pedit.php");
	loadURL("assets/ajax/futures_pedit.php", $('#ftable'));
<?php } ?>
});
</script>