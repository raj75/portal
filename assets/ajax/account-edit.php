<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];
?>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Accounts Edit
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="accountstable">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php 
			if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){
		?>
			<div align="right" style="padding-bottom:10px;">
				<button class="btn-primary" align="right" id="new-sites" style="height: 30px !important;width: auto !important;">Add New Account</button>
			</div>
		<?php } ?>
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Hide / Show Columns </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-accounts"></div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<section id="accountdetails"></section>
<!-- end widget grid -->
<div id="response"></div>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();
	
	// pagefunction	
	var pagefunction = function() {
	};
	
	pagefunction();
	
function load_details(id) {
	//$(window).scrollTop($('#response').offset().top);
	/*$('#response').html('<iframe src="assets/ajax/details.php?id='+id+'" style="width:100%;height:500px" frameBorder="0" scrolling="no"></iframe>');

	$('html, body').animate({
        scrollTop: $('#response').offset().top
    }, 2000);*/
	$(".sitestable").fadeOut( "slow" );
	$('#sitesdetails').load('assets/ajax/sitedetails.php?id='+id);
}

$(document).ready(function(){
	$("#new-sites").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$('#response').load('assets/ajax/sites-add.php');
	});
	$('#list-accounts').load('assets/ajax/list-accounts.php?load=true');
});
</script>