<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,63)==false) die("<h5 style='padding-top:30px;' align='center'>Permission Denied! Please contact Vervantis.</h5>");
$user_one=$_SESSION['user_id'];
$c_id=$_SESSION['company_id'];


?>
<link href="<?php echo ASSETS_URL; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Energy Accounting 
			<span>> 
				Resolved Exceptions Test
			</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="resolvedexptable">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
					<h2>Resolved Exceptions </h2>

				</header>

				<!-- widget div--> 
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-resolvedexp"></div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<section id="resolvedexptabledetails"></section>
<!-- end widget grid -->
<div id="response"></div>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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
	$(".resolvedexptable").fadeOut( "slow" );
	$('#resolvedexptabledetails').load('assets/ajax/resolved-excep-pedit-test.php?reid='+id);
}

function load_sacc1(aids,sno)
{
	parent.$('#sitesaccountcont').remove(); 
	parent.$('.siterow').after('<div id="sitesaccountcont" style="margin:0 !important;padding:0 !important;"></div>');
	parent.$('#sitesaccountcont').load('assets/ajax/details.php?pc=1&naids='+aids+'&sno='+sno+'');	
}

function movere_back(){
	parent.$('.resolvedexptable').show();
	parent.$('#resolvedexptabledetails').html('');
}

$(document).ready(function(){
	$('#list-resolvedexp').load('assets/ajax/list-resolvedexp-test.php?load=true');
});
</script>