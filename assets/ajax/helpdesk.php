<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

?>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Admin 
			<span>> 
				Help Desk
			</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="hrtable">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div align="right" style="padding-bottom:10px;" class="hidden">
				<button class="btn-primary" align="right" id="new-hr" style="height: 30px !important;width: auto !important;">Add New Help Request</button>
			</div>
			<div id="hrtable"></div>
		</article>
	</div>
	<!-- end row -->

</section>
<div id="hresponse"></div>
<script type="text/javascript">
$(document).ready(function(){
	$('#hrtable').load("assets/ajax/helpdesk-pedit.php?load=true&ct=<?php echo rand(9,33); ?>");
});
</script>
