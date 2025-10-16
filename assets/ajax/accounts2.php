<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css" />
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />

	<style>
	table.dataTable.dt-checkboxes-select tbody tr,
	table.dataTable thead th.dt-checkboxes-select-all,
	table.dataTable tbody td.dt-checkboxes-cell {
	  cursor: pointer;
	}

	table.dataTable thead th.dt-checkboxes-select-all,
	table.dataTable tbody td.dt-checkboxes-cell {
	  text-align: center;
	}

	div.dataTables_wrapper span.select-info,
	div.dataTables_wrapper span.select-item {
	  margin-left: 0.5em;
	}

	@media screen and (max-width: 640px) {
	  div.dataTables_wrapper span.select-info,
	  div.dataTables_wrapper span.select-item {
	    margin-left: 0;
	    display: block;
	  }
	}
	#ei_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#ei_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#ei_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
	#ei_datatable_fixed_column .widget-body,#ei_datatable_fixed_column #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}

	.m-top{margin-top:56px;}
	.m-top45{margin-top:47px;}
	.m-top20{margin-top:20px;}
	.m-top77{margin-top:79px;}
	.m-bottom50{margin-bottom: -50px !important;font-weight:bold;z-index:98;margin-top: 15px;}
	.m-bottom50 span{vertical-align: top;}
	.sdrp{width:65px; font-weight:normal;}

	.DTED_Lightbox_Background{z-index:905 !important;}
	.DTED_Lightbox_Wrapper{z-index:906 !important;}


	div.DTE_Body div.DTE_Body_Content div.DTE_Field {
		width: 50%;
		padding: 5px 20px;
		box-sizing: border-box;
	}

	div.DTE_Body div.DTE_Form_Content {
		display:flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	div.DTE_Field select{
		width:100%;
	}

	.popover{max-width:500px;}

	#ei_datatable_fixed_column tbody td .fa{cursor:pointer;}

	/*
	div.DTE_Body div.DTE_Body_Content div.DTE_Field{
		padding: 5px 0%;
		float: left;
		width: 50%;
		clear: none;
	}
	div.DTE_Body div.DTE_Body_Content div.DTE_Field > label {
		width: 35%;
	}
	div.DTE_Body div.DTE_Body_Content div.DTE_Field > div.DTE_Field_Input{
		float: left;
		width: 60%;
	}
	*/

	div.dataTables_filter label {float: left;}

	.show_deleted,.hide_deleted{margin-left:15px;}

	#undo_delete_filter {
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
	}
	#undo_delete_length {
		float: right !important;
		margin: 1% 1% !important;
	}
	#undo_delete{border-bottom: 1px solid #ccc !important;}


	</style>
	
	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}
	.datatable-container {
    overflow-x: hidden; /* Hides any horizontal overflow */
    width: 100%; /* Or any specific width */
	}

	.datatable-container table,#undo_delete {
		table-layout: fixed;
		width: 100%; /* Or any fixed width */
	}
	</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Accounts
			</h1>
		</div>
	</div>

	<section id="widget-grid" class="sitestable m-top20">

				<div class="jarviswidget jarviswidget-color-blueDark datatable-container" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Accounts </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding">
							<table id="ei_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
								<thead>

									<tr>
									    <th></th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Invoice Source" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Company Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Site Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Site Inactive Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 1" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 2" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 3" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Legacy Account Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Point Location" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Name Key" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Active Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Inactive Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Active Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Inactive Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Rate Id" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Activity Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Status" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Code" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Reference" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Group" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Notes" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Import Date" />
										</th>

									</tr>

									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">ID </th>
										<th data-hide="phone,tablet">Invoice Source </th>
										<th data-hide="phone,tablet">Company Id </th>
										<th data-hide="phone,tablet">Site Number </th>
										<th data-hide="phone,tablet">Site Inactive Date </th>
										<th data-hide="phone,tablet">Vendor Id </th>
										<th data-hide="phone,tablet">Vendor Name </th>
										<th data-hide="phone,tablet">Service Group Id </th>
										<th data-hide="phone,tablet">Service Group </th>
										<th data-hide="phone,tablet">Account Number 1 </th>
										<th data-hide="phone,tablet">Account Number 2 </th>
										<th data-hide="phone,tablet">Account Number 3 </th>
										<th data-hide="phone,tablet">Legacy Account Number </th>
										<th data-hide="phone,tablet">Service Point Location </th>
										<th data-hide="phone,tablet">Name Key </th>
										<th data-hide="phone,tablet">Account Active Date </th>
										<th data-hide="phone,tablet">Account Inactive Date </th>
										<th data-hide="phone,tablet">Meter Number </th>

										<th data-hide="phone,tablet">Meter Active Date </th>
										<th data-hide="phone,tablet">Meter Inactive Date </th>
										<th data-hide="phone,tablet">Rate Id </th>
										<th data-hide="phone,tablet">Activity Date </th>
										<th data-hide="phone,tablet">Meter Status </th>
										<th data-hide="phone,tablet">GL Code </th>
										<th data-hide="phone,tablet">GL Reference </th>
										<th data-hide="phone,tablet">GL Group </th>
										<th data-hide="phone,tablet">Notes </th>
										<th data-hide="phone,tablet">Import Date </th>


									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>

	<section id="widget-grid2" class="sitestable m-top45 hide">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Deleted Records </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding">
							<table id="undo_delete" class="table table-striped table-bordered table-hover" width="100%">
								<thead>

									<tr>
									    <th></th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="" />
										</th>
										<th class="hasinput">

										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Invoice Source" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Company Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Site Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Site Inactive Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group Id" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 1" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 2" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Number 3" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Legacy Account Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Point Location" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Name Key" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Active Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Account Inactive Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Active Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Inactive Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Rate Id" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Activity Date" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Meter Status" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Code" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Reference" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="GL Group" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Notes" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Import Date" />
										</th>

									</tr>

									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">ID </th>
										<th data-hide="phone,tablet">Delete Date </th>
										<th data-hide="phone,tablet">Delete By </th>
										<th data-hide="phone,tablet">Invoice Source </th>
										<th data-hide="phone,tablet">Company Id </th>
										<th data-hide="phone,tablet">Site Number </th>
										<th data-hide="phone,tablet">Site Inactive Date </th>
										<th data-hide="phone,tablet">Vendor Id </th>
										<th data-hide="phone,tablet">Vendor Name </th>
										<th data-hide="phone,tablet">Service Group Id </th>
										<th data-hide="phone,tablet">Service Group </th>
										<th data-hide="phone,tablet">Account Number 1 </th>
										<th data-hide="phone,tablet">Account Number 2 </th>
										<th data-hide="phone,tablet">Account Number 3 </th>
										<th data-hide="phone,tablet">Legacy Account Number </th>
										<th data-hide="phone,tablet">Service Point Location </th>
										<th data-hide="phone,tablet">Name Key </th>
										<th data-hide="phone,tablet">Account Active Date </th>
										<th data-hide="phone,tablet">Account Inactive Date </th>
										<th data-hide="phone,tablet">Meter Number </th>

										<th data-hide="phone,tablet">Meter Active Date </th>
										<th data-hide="phone,tablet">Meter Inactive Date </th>
										<th data-hide="phone,tablet">Rate Id </th>
										<th data-hide="phone,tablet">Activity Date </th>
										<th data-hide="phone,tablet">Meter Status </th>
										<th data-hide="phone,tablet">GL Code </th>
										<th data-hide="phone,tablet">GL Reference </th>
										<th data-hide="phone,tablet">GL Group </th>
										<th data-hide="phone,tablet">Notes </th>
										<th data-hide="phone,tablet">Import Date </th>


									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>



	<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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

		pageSetUp();

		/*
		 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
		 * eg alert("my home function");
		 *
		 * var pagefunction = function() {
		 *   ...
		 * }
		 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
		 *
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {
			//console.log("cleared");

			/* // DOM Position key index //

				l - Length changing (dropdown)
				f - Filtering input (search)
				t - The Table! (datatable)
				i - Information (records)
				p - Pagination (paging)
				r - pRocessing
				< and > - div elements
				<"#id" and > - div with an id
				<"class" and > - div with a class
				<"#id.class" and > - div with an id and class

				Also see: http://legacy.datatables.net/usage/features
			*/

			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;

				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};



			var editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/accounts-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'ID',

					fields: [

						{
							"label": "ID:",
							"name": "ID"
						},

						{
							"label": "Invoice Source:",
							"name": "invoice_source",
						},

						{
							"label": "Company Id:",
							"name": "company_id",
						},
						{
							"label": "Site Number:",
							"name": "site_number"
						},
						{
							"label": "Site Inactive Date:",
							"name": "site_inactive_date",

						},
						{
							"label": "Vendor Id:",
							"name": "vendor_id"
						},
						{
							"label": "Vendor Name:",
							"name": "vendor_name"
						},

						{
							"label": "Service Group Id:",
							"name": "service_group_id"
						},
						{
							"label": "Service Group:",
							"name": "service_group"
						},
						{
							"label": "Account Number 1:",
							"name": "account_number1"
						},
						{
							"label": "Account Number 2:",
							"name": "account_number2"
						},
						{
							"label": "Account Number 3:",
							"name": "account_number3"
						},
						{
							"label": "Legacy Account Number:",
							"name": "legacy_account_number"
						},
						{
							"label": "Service Point Location:",
							"name": "service_point_location"
						},
						{
							"label": "Name Key:",
							"name": "name_key"
						},
						{
							"label": "Account Active Date:",
							"name": "account_active_date"
						},
						{
							"label": "Account Inactive Date:",
							"name": "account_inactive_date"
						},
						{
							"label": "Meter Number:",
							"name": "meter_number"
						},
						{
							"label": "Meter Active Date:",
							"name": "meter_active_date"
						},
						{
							"label": "Meter Inactive Date:",
							"name": "meter_inactive_date"
						},
						{
							"label": "Rate Id:",
							"name": "rate_id"
						},
						{
							"label": "Activity Date:",
							"name": "activity_date"
						},
						{
							"label": "Meter Status:",
							"name": "meter_status"
						},
						{
							"label": "GL Code:",
							"name": "gl_code"
						},
						{
							"label": "GL Reference:",
							"name": "gl_reference"
						},
						{
							"label": "GL Group:",
							"name": "gl_group"
						},
						{
							"label": "Notes:",
							"name": "notes"
						},
						{
							"label": "Import Date:",
							"name": "importDate"
						},


					]
				} );


			/* COLUMN FILTER  */
				var otable = $("#ei_datatable_fixed_column").DataTable( {

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'deferRender': true,
					'ajax': {
					   'url':'assets/ajax/accounts-ajax.php'
					},
					
					 "drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},  

					//'select': true,
					'select': {
						style:    'os',
						//style: 'single',
						selector: 'td:first-child'
					},
					//'stateSave': true,

					"order": [[ 1, 'asc' ]],


					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false,
						searchable: false,
					 },
					 { data: 'ID' },
					 { data: 'invoice_source' },
					 { data: 'company_id' },
					 { data: 'site_number' },
					 { data: 'site_inactive_date' },
					 { data: 'vendor_id' },
					 { data: 'vendor_name' },
					 { data: 'service_group_id' },
					 { data: 'service_group' },
					 { data: 'account_number1' },
					 { data: 'account_number2' },
					 { data: 'account_number3' },
					 { data: 'legacy_account_number' },
					 { data: 'service_point_location' },
					 { data: 'name_key' },
					 { data: 'account_active_date' },
					 { data: 'account_inactive_date' },
					 { data: 'meter_number' },
					 { data: 'meter_active_date' },
					 { data: 'meter_inactive_date' },
					 { data: 'rate_id' },
					 { data: 'activity_date' },
					 { data: 'meter_status' },
					 { data: 'gl_code' },
					 { data: 'gl_reference' },
					 { data: 'gl_group' },
					 { data: 'notes' },
					 { data: 'importDate' },
					]
					,



					"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"dom": 'Blfrtip',
					"buttons": [
						//'copyHtml5',
						{
							'extend': 'copyHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'excelHtml5',
						{
							'extend': 'excelHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'csvHtml5',
						{
							'extend': 'csvHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'pdfHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' },
							'title' : 'Vervantis_PDF',
							'messageTop': 'Vervantis PDF Export',
							'orientation':'landscape'
						},
						//'pdfHtml5'
						{
							'extend': 'print',
							//'title' : 'Vervantis',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
							'orientation':'landscape',
							//'columns': ':visible:not(:first-child)'
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':gt(1)'
						},

						{ 'extend': 'create', editor: editor },
						{ 'extend': 'edit',   editor: editor },
						{ 'extend': 'remove', editor: editor },



					],
					"autoWidth" : true


				});

				$('#ei_datatable_fixed_column').on( 'click', 'tbody td:not(:first-child):not(:nth-child(2))', function (e) {
				//$('#ei_datatable_fixed_column').on( 'click', 'tbody td:not(:first-child)', function (e) {


					var tdtag = $(this);
					console.log(e.target.nodeName);
					//if(this.tagName != 'td') {
					//if($(this).prop('tagName') == 'i') {
					if (e.target.nodeName == 'A' || e.target.nodeName == 'I') {
						///console.log('in if');
						return;
						//editor.inline( this );

					} else {
						editor.inline( this, {
							scope: 'cell'
						} );
					}


					////editor.inline( this );

				} );

				editor.on('open', function(e) {

					var fldname = e.target.s.includeFields[0];
					console.log(fldname);
					input_val = editor.field(fldname).val();

					var inner_div = document.createElement("div");
					inner_div.innerHTML = input_val;
					var inner_text = inner_div.textContent || inner_div.innerText || "";

					editor.field(fldname).set(inner_text.trim());

				});

				editor.on( 'preSubmit', function ( e, o, a ) {
					if (a == 'remove') {
						o.action = "edit"; // Change action from delete to edit

						// Loop through selected records and set deleted value
						for (var key in o.data) {
							if (o.data.hasOwnProperty(key)) {
								o.data[key].deleted = 1;
								o.data[key].delete_date = 1;
								o.data[key].delete_by = 1;
							}
						}
					}
				} );


				// Edit record
				$(document).on('click', '.buttons-edit', function (e) {
					//e.preventDefault();
					editor.edit( otable.row({ selected: true }).index(), {
						//title: 'Edit record',
						//buttons: 'Update'
					} );

					var all_inputs = $(".DTED_Lightbox_Wrapper .DTE_Action_Edit .DTE_Body_Content form .DTE_Field input");
					all_inputs.each(function(i, obj) {
						var input_val = $(this).val();
						var inner_div = document.createElement("div");
						inner_div.innerHTML = input_val;
						var inner_text = inner_div.textContent || inner_div.innerText || "";

						$(this).val(inner_text.trim());
					});
					//var input_val = $(this).find('input').val();

					//console.log( otable.row({ selected: true }) );
				} );


				var undo_editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/accounts-undo.php',
					table: '#undo_delete',

				} );

				undo_editor.on( 'preSubmit', function ( e, o, a ) {
					if (a == 'remove') {
						o.action = "edit"; // Change action from delete to edit

						// Loop through selected records and set deleted value
						for (var key in o.data) {
							if (o.data.hasOwnProperty(key)) {
								o.data[key].deleted = 0;
							}
						}
					}
				} );

				undo_editor.on( 'submitSuccess', function ( e, o, a ) {
					otable.ajax.reload();
				} );

				editor.on( 'submitSuccess', function ( e, o, a ) {
					undo_table.ajax.reload();
				} );



				/* COLUMN FILTER  */
				
				var undo_table;
				
				function undo_delete_datatable () {

				undo_table = $('#undo_delete').DataTable({

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'ajax': {
					   'url':'assets/ajax/accounts-deleted.php'
					},

					"drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},

					//'select': true,
					'select': {
						style:    'os',
						//style: 'single',
						selector: 'td:first-child'
					},
					//'stateSave': true,

					"order": [[ 1, 'asc' ]],


					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false,
						searchable: false
					 },
					 { data: 'accounts.ID' },
					 { data: 'accounts.delete_date' },

					 {  data: 'accounts.delete_by',
					    searchable: false,
						orderable:false,
						render: function ( data, type, row ) {
							//console.log(row);
							return row.user.firstname +' '+ row.user.lastname;
						}
					},

					 { data: 'accounts.invoice_source' },
					 { data: 'accounts.company_id' },
					 { data: 'accounts.site_number' },
					 { data: 'accounts.site_inactive_date' },
					 { data: 'accounts.vendor_id' },
					 { data: 'accounts.vendor_name' },
					 { data: 'accounts.service_group_id' },
					 { data: 'accounts.service_group' },
					 { data: 'accounts.account_number1' },
					 { data: 'accounts.account_number2' },
					 { data: 'accounts.account_number3' },
					 { data: 'accounts.legacy_account_number' },
					 { data: 'accounts.service_point_location' },
					 { data: 'accounts.name_key' },
					 { data: 'accounts.account_active_date' },
					 { data: 'accounts.account_inactive_date' },
					 { data: 'accounts.meter_number' },
					 { data: 'accounts.meter_active_date' },
					 { data: 'accounts.meter_inactive_date' },
					 { data: 'accounts.rate_id' },
					 { data: 'accounts.activity_date' },
					 { data: 'accounts.meter_status' },
					 { data: 'accounts.gl_code' },
					 { data: 'accounts.gl_reference' },
					 { data: 'accounts.gl_group' },
					 { data: 'accounts.notes' },
					 { data: 'accounts.importDate' },

					]
					,



					"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"dom": 'Blfrtip',
					"buttons": [

						{ 'extend': 'remove', 'text': 'Undo Delete', editor: undo_editor

						,
							formMessage: function ( e, dt ) {
								var rows = undo_table.rows( {selected: true} ).indexes();

								return rows.length === 1 ?
                                'Are you sure you wish to undo this record?' :
                                'Are you sure you wish to undo these '+rows.length+' records'

								//var rows = dt.rows( e.modifier() ).data().pluck('first_name');
								//return 'Are you sure you wish to delete these '+rows.length+' rows';
							},
							formButtons: [
								'Undelete',
								//{ text: 'Cancel', action: function () { this.close(); } }
							]
						},

						//'copyHtml5',
						{
							'extend': 'copyHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'excelHtml5',
						{
							'extend': 'excelHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'csvHtml5',
						{
							'extend': 'csvHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'pdfHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' },
							'title' : 'Vervantis_PDF',
							'messageTop': 'Vervantis PDF Export',
							'orientation':'landscape'
						},
						//'pdfHtml5'
						{
							'extend': 'print',
							//'title' : 'Vervantis',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
							'orientation':'landscape',
							//'columns': ':visible:not(:first-child)'
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':gt(1)'
						},




					],
					"autoWidth" : true


				});
				
				} // end of function undo_delete_datatable

				// Apply the filter
				$("#ei_datatable_fixed_column .sdrp").on( 'keyup change', function () {
					otable
						.column( $(this).parent().index()+':visible' )
						.search( this.value )
						.draw();

					if ($(this).hasClass('dd_country')) {
						var val = this.value;
						if (!val) {
							val = 'all';
						}
						getState(val);
					}

				} );




			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter
			$(document.body).on('keyup change', '#ei_datatable_fixed_column thead th input[type=text]' ,function(){

				//console.log('here');
				//console.log($(this).parent().index());

				otable
					.column( $(this).parent().index()+':visible' )
					//.column( $(this).parent().index() )
					.search( this.value )
					.draw();

			});




					//add deleted record button ei_datatable_fixed_column_filter
					//$('<button class="show_deleted  dt-button buttons-html5 dt-buttons">Show Deleted</button>').appendTo('#ei_datatable_fixed_column_filter');
					$('#ei_datatable_fixed_column_filter').append('<button class="show_deleted">Show Deleted</button>');
					////$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');



			//});

			$('.show_deleted').click( function() {
				
				if ( !$.fn.dataTable.isDataTable( '#undo_delete' ) ) {
					undo_delete_datatable();
				}
				
				$('#widget-grid').hide();
				$('#widget-grid2').removeClass('hide');
				//$('#widget-grid2').show();
				
				$('.hide_deleted').remove();
				$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');
			});

			//$('.hide_deleted').click( function() {
			$(document.body).on('click', '.hide_deleted' ,function(){
				$('#widget-grid').show();
				$('#widget-grid2').addClass('hide');
				//$('#widget-grid2').show();
			});



		}; // end of pagefunction

		function multifilter(nthis,fieldname,otable)
		{
				var selectedoptions = [];
				$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
					selectedoptions.push($(this).val());
				});
				otable
				 .column( $(nthis).parent().index()+':visible' )
				 .search("^" + selectedoptions.join("|") + "$", true, false, true)
				 .draw();
		}

		function multilist(indexno)
		{
			var items=[], options=[];
			$('#ei_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
			   items.push( $(this).text() );
			});
			var items = $.unique( items );
			$.each( items, function(i, item){
				options.push('<option value="' + item + '">' + item + '</option>');
			})
			return options;
		}

		function getState (country) {
		//$('#state').on('change', function(){
			//var country = $(this).val();
			if(country){
				$.ajax({
					type:'POST',
					url:'assets/ajax/zipcode-dropdowns.php',
					data:'country='+country,
					success:function(html){
						$('.dd_state').html(html);
					}
				});
			}

		}

		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		 loadScript("assets/js/dataTables.editor.min.js", function(){
			pagefunction();
	     });
		 });











	</script>

<?php
 function getSelect($mysqli,$column,$select="") {

	$_SESSION[$column] = '';

	$html = '<select class="form-control sdrp dd_'.$column.'" id="fstatus">
				<option value="">All</option>';

	if ($column == 'state') {
		$qry = "Select DISTINCT $column From ziputility where country='US' order by $column";
	} else {
		$qry = "Select DISTINCT $column From ziputility order by $column";
	}

	if ($stmt_sss = $mysqli->prepare( $qry ) ) {
        $stmt_sss->execute();
        $stmt_sss->store_result();
        if ($stmt_sss->num_rows > 0) {
			$stmt_sss->bind_result($sssstatus);
			while($stmt_sss->fetch()) {

				if($sssstatus == "") continue;

				$selected = '';
				if (!empty($select) and $select==$sssstatus) {$selected = "selected";}

				$html .= '<option value="'.$sssstatus.'" '.$selected.'>'.$sssstatus.'</option>';

				$_SESSION[$column] .= "'".$sssstatus."',";
			}
		}
	}

	$html .= '</select>';
	return $html;
 }
?>
