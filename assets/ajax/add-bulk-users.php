<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) die("Restricted Access");

if($_SESSION["user_id"] != 1) die("Under Maintenance");
?>
<br /><br />
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 5){?>
			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;">
				<button class="btn-primary" align="right" id="new-user" style="height: 30px !important;width: 115px !important;">Add New User</button>
			</div>
<?php } ?>
			<div id="dtable"></div>
		</article>
	</div>

</section>

<div id="presponse"></div>
<script type="text/javascript">
$(document).ready(function(){
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 5){?>
	$("#new-user").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		//$('#presponse').html('');
		document.getElementById('presponse').innerHTML='';
		$('#presponse').load('assets/ajax/user-add.php');
	});
<?php } ?>

	$('#dtable').load('assets/ajax/user-bpedit.php');
});
</script>
