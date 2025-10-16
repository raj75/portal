<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");
?>
<br /><br />
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;">
				<button class="btn-primary" align="right" id="new-company" style="height: 30px !important;width: auto !important;">Add New Company</button>
			</div>
			<div id="dtable"><?php require_once("company-pedit.php"); ?></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<section id="companydetails"></section>
<div id="response" class="hidden"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#new-company").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$("#dialog-message").remove();
		$('#response').html('');
		$('#response').load('assets/ajax/company-add.php');
	});
});
</script>