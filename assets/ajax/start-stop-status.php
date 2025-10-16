<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,51)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

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
span.flleft{
    vertical-align: top;
}
</style>
<br /><br />
<link href="../assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Account Admin
			<span>>
				Start/Stop Status
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
				<input type="checkbox" <?php echo ($showdemo==1?"CHECKED":""); ?> value="Demo Company" id="hidedemo" class="flleft"><span class="flleft"><strong>Hide Demo Company</strong></span>
			</article>
		<?php } ?>
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div id="sstable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<section id="ssdetails"></section>
<?php }
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
<div id="ss-status"></div>
<div id="ssopdialog"></div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#new-startstop").click(function(){
			$("#dialog-message").remove();
			$('#ss-status').load('assets/ajax/start-stop-status-pedit.php?action=view');
	});

	$('#sstable').load("assets/ajax/start-stop-status-pedit.php?load=true&ct=<?php echo time(); echo $subquery; ?>");
	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
	$(document).off("keyup change","#hidedemo");
	$(document).on("keyup change","#hidedemo",function() {
		if($('#hidedemo').is(':checked')){
			$('#sstable').load('assets/ajax/start-stop-status-pedit.php?load=true&ct=<?php echo time(); ?>&showdemo=1');
		}else{
			$('#sstable').load('assets/ajax/start-stop-status-pedit.php?load=true&ct=<?php echo time(); ?>&showdemo=0');
		}
	});
	<?php } ?>
});
</script>
