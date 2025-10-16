<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Admin 
			<span>> 
				Audit Log
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
			<div id="audittable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<!-- end widget grid -->
<section id="auditlogdetails"></section>
<div id="auditresponse"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#audittable").load("assets/ajax/list-audit-log.php?listaudit=all");
});
</script>