<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

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
.m-bottom50{margin-bottom: -50px !important;font-weight:bold;z-index:98;margin-top: 15px;}
.m-bottom50 span{vertical-align: top;}
</style>
<?php if(!isset($_GET["type"])) { ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Dashboard Widgets  
			<span>> 
				Focus Items
			</span>
		</h1>
	</div>
</div>
<?php } ?>
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
			<div align="right" style="padding-bottom:10px;">
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
				<button class="btn-primary" align="right" id="new-focus-items" style="height: 30px !important;width: auto !important;">Add New Focus Items</button>
<?php } ?>
			</div>
			<div id="fitable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<?php
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)
{
?>
<section id="focusitemsdetails"></section>
<?php } 
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)
{
?>
<div id="firesponse"></div>
<div id="fitopdialog"></div>
<script type="text/javascript">
$(document).ready(function(){
<?php }
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)
{
?>
	$("#new-focus-items").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$("#dialog-message").remove();
		$('#firesponse').html('');
		$('#firesponse').load('assets/ajax/focus_items_add.php?action=add');
	});
<?php }	
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
	$('#fitable').load("assets/ajax/focus_items_pedit.php?ct=Math.random()<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "&type=unread";} echo $subquery; ?>");
<?php } ?>

	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
	$('#hidedemo').change(function () {
		if($('#hidedemo').prop("checked")==1){
			var showdemo=1;
		}else{
			var showdemo=0;
		}
		$('#fitable').load("assets/ajax/focus_items_pedit.php?ct=Math.random()<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "&type=unread"; } ?>&showdemo="+showdemo);
	});
	<?php } ?>
});
</script>