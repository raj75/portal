<?php require_once("inc/init.php"); ?>
<?php 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Landing Page Edit </h1>
	</div>
</div>

<div class="row hidden" id="move-back">
	<article class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		<b><img onclick="move_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
	</article>
</div>

<!-- widget grid -->
<section id="widget-grid">

	<!-- row -->
	<div class="row" id="initial-content">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-1" data-widget-editbutton="false">		
				<header>
					<span class="widget-icon"> <i class="fa fa-cloud"></i> </span>
					<h2>Company News</h2>
				</header>		
				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body no-padding" id="company-news"></div>
				</div>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row hidden" id="market-news-content">
		<article class="col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-2" data-widget-editbutton="false">		
				<header>
					<span class="widget-icon"> <i class="fa fa-cloud"></i> </span>
					<h2>Market News</h2>
				</header>		
				<!-- widget div-->
				<div>
					<!-- widget content -->
					<div class="widget-body no-padding" id="market-news"></div>
				</div>
			</div>
		</article>
	</div>	

	<!-- row -->
	<div class="row hidden" id="document-content-ufi">

		<!-- NEW WIDGET START -->
		<article class="col-sm-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-8" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-cloud"></i> </span>
					<h2>Unread Focus Items</h2>
				</header>

				<!-- widget div-->
				<div id="document-unread-focus-items">
					<!-- widget content -->
					<div class="widget-body no-padding">						
						<table id="datatable_fixed_column6" class="table table-striped table-bordered table-hover table-responsive" width="100%">
							<thead>
								<tr>
									<th>Category</th>
									<th>Description</th>
									<th>Link</th>
									<th>Read</th>
								</tr>
							</thead>
							<tbody>	
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>
								<tr>
									<td>Cat1</td>
									<td>Sample Description</td>
									<td><a href="javascript:void(0);">Click</td>
									<td>No</td>
								</tr>								
							</tbody>
						</table>
					</div>	
				</div>				

			</div>
			<!-- end widget -->
		</article>
		<!-- WIDGET END -->
	</div>
	<!-- end row -->	
	
</section>
<!-- end widget grid -->
<!--<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>-->
<div id="dialog" title="Basic dialogs"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#company-news").load("assets/ajax/company_news.php");
});
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();
	
	/*
	 * PAGE RELATED SCRIPTS
	 */

	// pagefunction
	
	var pagefunction = function() {
}
	
	// end pagefunction

	// destroy generated instances 
	// pagedestroy is called automatically before loading a new page
	// only usable in AJAX version!

	var pagedestroy = function(){

	}

	// end destroy
	
	// run pagefunction on load
	//pagefunction();

	// load related plugins
	pagefunction();
</script>