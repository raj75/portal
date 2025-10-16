<?php require_once("inc/init.php"); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span> <span>> Focus Items</span></h1>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid">

	<!-- row -->
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12">

			<!-- new widget -->
			<div class="col-sm-12 col-md-12 col-lg-12 jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false" data-widget-fullscreenbutton="false" style="padding:5px;">
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2> Focus Items </h2>
				</header>
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
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
			</div>
		</article>
	</div>

</section>
<!-- end widget grid -->

<script type="text/javascript">
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

	 var flot_updating_chart, flot_statsChart, flot_multigraph, calendar;

	pageSetUp();

	/*
	 * PAGE RELATED SCRIPTS
	 */

	// pagefunction

	var pagefunction = function() {
		/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		/* COLUMN FILTER  */
	    var otable = $('#datatable_fixed_column').DataTable({
			 "iDisplayLength": 10,
			//"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	    	"bFilter": false,
	    	//"bInfo": false,
	    	"bLengthChange": false,
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			}

	    });
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

	loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});

</script>
