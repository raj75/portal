<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if($_SESSION["group_id"] != 1) die("Under Constructions!");
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
				<button class="btn-primary" align="right" id="new-vendor" style="height: 30px !important;width: auto !important;">Add New  Vendor</button>
			</div>
			<div id="dtable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<section id="vendordetails"></section>
<div id="response" class="hidden"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#new-vendor").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$('#response').html('');
		$('#response').load('assets/ajax/vendor-pedit.php?vNew=true');
	});
	$('#dtable').html('');
	$('#dtable').load('assets/ajax/vendor-pedit.php?vlist=all');
});
</script>