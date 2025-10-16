<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css" />
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />

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
	
	.dataTables_processing {height:0px !important; padding-top:0px !important;}


	</style>

	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
	
	<section id="widget-grid" class="sitestable m-top45">
<?php if(1==2){ ?>
				<a href="JavaScript:void(0);" title="DataTable Join" onclick="navigateurl('assets/ajax/datatable_join_test.php','DataTable Join Test')">DataTable Join</a>
<?php } ?>
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Zip Codes </h2>
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
											<input type="text" class="form-control" placeholder="Filter ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Postal Code" />
										</th>
										<th class="hasinput">
											<?php echo getSelect($mysqli,'country','US');?>
										</th>
										<th class="hasinput">
											<?php echo getSelect($mysqli,'state');?>
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter County" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Place" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Latitude" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Longitude" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Timezone" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Areacode" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Ownership" />
										</th>


										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Type" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Decommissioned" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Acceptable Cities" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Unacceptable Cities" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter World Region" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Est Population" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Alt Zip" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Eia ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Alt State" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Delivery" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Energy" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Bundled" />
										</th>



									</tr>
									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">ID </th>
										<th data-hide="phone,tablet">Postal Code </th>
										<th data-hide="phone,tablet">Country </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">County </th>
										<th data-hide="phone,tablet">Place </th>
										<th data-hide="phone,tablet">Latitude </th>
										<th data-hide="phone,tablet">Longitude </th>
										<th data-hide="phone,tablet">Timezone </th>
										<th data-hide="phone,tablet">Areacode </th>
										<th data-hide="phone,tablet">Utility Name </th>
										<th data-hide="phone,tablet">Ownership </th>

										<th data-hide="phone,tablet">Type </th>
										<th data-hide="phone,tablet">Decommissioned </th>
										<th data-hide="phone,tablet">Acceptable Cities </th>
										<th data-hide="phone,tablet">Unacceptable Cities </th>
										<th data-hide="phone,tablet">World Region </th>
										<th data-hide="phone,tablet">Est Population </th>
										<th data-hide="phone,tablet">Alt Zip </th>
										<th data-hide="phone,tablet">EiaID </th>
										<th data-hide="phone,tablet">Alt State </th>
										<th data-hide="phone,tablet">Delivery </th>
										<th data-hide="phone,tablet">Energy </th>
										<th data-hide="phone,tablet">Bundled </th>
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

<?php if(1==1 || $_SESSION["group_id"] == 1) {?>
<!----------------------------undo delete---------------------------->

<form id="undo_delete_form" action="" onsubmit="return false;" method="POST">

	<section id="widget-grid2" class="sitestable m-top45 hide">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
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
											<input type="text" class="form-control" placeholder="Filter ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Postal Code" />
										</th>
										<th class="hasinput">
											<?php echo getSelect($mysqli,'country','US');?>
										</th>
										<th class="hasinput">
											<?php echo getSelect($mysqli,'state');?>
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter County" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Place" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Latitude" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Longitude" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Timezone" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Date" />
										</th>
										<th class="hasinput">
											<!--<input type="text" class="form-control" placeholder="Filter Name" />-->
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Areacode" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Ownership" />
										</th>


										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Type" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Decommissioned" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Acceptable Cities" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Unacceptable Cities" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter World Region" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Est Population" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Alt Zip" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Eia ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Alt State" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Delivery" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Energy" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Bundled" />
										</th>



									</tr>

									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">ID </th>
										<th data-hide="phone,tablet">Postal Code </th>
										<th data-hide="phone,tablet">Country </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">County </th>
										<th data-hide="phone,tablet">Place </th>
										<th data-hide="phone,tablet">Latitude </th>
										<th data-hide="phone,tablet">Longitude </th>
										<th data-hide="phone,tablet">Timezone </th>

										<th data-hide="phone,tablet">Delete Date </th>
										<th data-hide="phone,tablet">Delete By </th>

										<th data-hide="phone,tablet">Areacode </th>
										<th data-hide="phone,tablet">Utility Name </th>
										<th data-hide="phone,tablet">Ownership </th>

										<th data-hide="phone,tablet">Type </th>
										<th data-hide="phone,tablet">Decommissioned </th>
										<th data-hide="phone,tablet">Acceptable Cities </th>
										<th data-hide="phone,tablet">Unacceptable Cities </th>
										<th data-hide="phone,tablet">World Region </th>
										<th data-hide="phone,tablet">Est Population </th>
										<th data-hide="phone,tablet">Alt Zip </th>
										<th data-hide="phone,tablet">EiaID </th>
										<th data-hide="phone,tablet">Alt State </th>
										<th data-hide="phone,tablet">Delivery </th>
										<th data-hide="phone,tablet">Energy </th>
										<th data-hide="phone,tablet">Bundled </th>

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

</form>
<?php } ?>

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
					ajax: 'assets/ajax/zip-codes-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'ID',

					fields: [

						{
							"label": "Postal Code:",
							"name": "postalcode"
						},
						{
							"label": "Country",
							"name": "country",
							"type":  "select",
							//"def": "AF",
							"options": [
								<?php echo $_SESSION['country'];?>
							]
							/*
							"options": [
								{ label: "AE", value: "AE" },
								{ label: "AF", value: "AF" },
								{ label: "US", value: "US" }
							]
							*/
						},

						{
							"label": "State",
							"name": "state",
							"type":  "select",
							"options": [
								<?php echo $_SESSION['state'];?>
							]
						},
						{
							"label": "County:",
							"name": "county"
						},
						{
							"label": "Place:",
							"name": "place",

						},
						{
							"label": "Latitude:",
							"name": "latitude"
						},
						{
							"label": "Longitude:",
							"name": "longitude"
						},
						{
							"label": "Timezone:",
							"name": "timezone"
						},
						{
							"label": "Area Codes:",
							"name": "area_codes"
						},
						{
							"label": "Utility Name:",
							"name": "utility_name"
						},
						{
							"label": "Ownership:",
							"name": "ownership"
						},


						{
							"label": "Type:",
							"name": "type"
						},
						{
							"label": "Decommissioned:",
							"name": "decommissioned"
						},
						{
							"label": "Acceptable Cities:",
							"name": "acceptable_cities"
						},
						{
							"label": "Unacceptable Cities:",
							"name": "unacceptable_cities"
						},
						{
							"label": "World Region:",
							"name": "world_region"
						},
						{
							"label": "Est Population:",
							"name": "irs_estimated_population_2015"
						},
						{
							"label": "Alt Zip:",
							"name": "zip2"
						},
						{
							"label": "EiaID:",
							"name": "eiaid"
						},
						{
							"label": "Alt State:",
							"name": "state2",
							"type":  "select",
							"options": [
								<?php echo $_SESSION['state'];?>
							]
						},
						{
							"label": "Delivery:",
							"name": "Delivery"
						},
						{
							"label": "Energy:",
							"name": "Energy"
						},
						{
							"label": "Bundled:",
							"name": "Bundled"
						},




						// {
							// "label": "user_type:",
							// "name": "user_type",
							// "type": "select",
							// "options": [
								// "male",
								// "female"
							// ]
						// }

					]
				} );









			/* COLUMN FILTER  */
				var otable = $("#ei_datatable_fixed_column").DataTable( {

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'ajax': {
					   'url':'/assets/ajax/zip-codes-ajax.php'
					},
					
					 "drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},  
					/*
					"preXhr" : function(settings) {
						// TEST
						alert('preXhr');
		 
					},             
		 
					"initComplete": function(settings, json) {
						alert('initComplete');
					},
					*/			
								
					/*
					"language": {
						'processing': '<style>.container123{position: absolute;top: 50%;left: 50%; -moz-transform: translateX(-50%) translateY(-50%); -webkit-transform: translateX(-50%) translateY(-50%); transform: translateX(-50%) translateY(-50%); }.dots-cont{position:absolute;left:10px;top:50%;text-align:center;width:100%;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style><span class="dots-cont container123"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>',
					},
					*/
					
					//processing: true,
					//"language": { "processing": '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' },

					/*
					columnDefs: [{
						targets: "_all",
						orderable: true,
						searchable: true,

						//targets: 0,
						//searchable: true,
						//visible: false,
					 }],
					 */

					//'select': true,
					'select': {
						style:    'os',
						//style: 'single',
						selector: 'td:first-child'
					},
					//'stateSave': true,



					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false
					 },
					 { data: 'ID' },
					 { data: 'postalcode' },
					 { data: 'country' },
					 { data: 'state' },
					 { data: 'county' },
					 { data: 'place' },
					 { data: 'latitude' },
					 { data: 'longitude' },
					 { data: 'timezone' },
					 { data: 'area_codes' },
					 { data: 'utility_name' },
					 { data: 'ownership' },
					 { data: 'type' },
					 { data: 'decommissioned' },
					 { data: 'acceptable_cities' },
					 { data: 'unacceptable_cities' },
					 { data: 'world_region' },
					 { data: 'irs_estimated_population_2015' },
					 { data: 'zip2' },
					 { data: 'eiaid' },
					 { data: 'state2' },
					 { data: 'Delivery' },
					 { data: 'Energy' },
					 { data: 'Bundled' }
					]
					,

					"searchCols": [
						null,
						null,
						null,
						{ "search": "US" },
					],



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

						},
						//'pdfHtml5'
						{
							'extend': 'print',
							//'title' : 'Vervantis',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
							//'columns': ':visible:not(:first-child)'
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':gt(1)'
						}

						,


						{ 'extend': 'create', editor: editor },
						{ 'extend': 'edit',   editor: editor },
						{ 'extend': 'remove', editor: editor }

					],
					"autoWidth" : true


				});

				//--------------------------------------------------------------------
				// Activate an inline edit on click of a table cell
				$('#ei_datatable_fixed_column').on( 'click', 'tbody td:not(:first-child)', function (e) {

					//console.log($(this).prop('tagName'));
					console.log(e.target.nodeName);

					//console.log($(this).attr('tagName'));
					//console.log($(this).prop('tagName'));

					//console.log(editor.field());
					//console.log(e);
					//console.log(this);
					var tdtag = $(this);
					//if(this.tagName != 'td') {
					//if($(this).prop('tagName') == 'i') {
					if (e.target.nodeName == 'I' || e.target.nodeName == 'A') {
						///console.log('in if');
						return;
						//tdtag = $(this).parents('td');
						//tdtag =
					}

					//console.log(tdtag);

					//var filter_val;

					//filter_val = tdtag.find('a').first().text();
					//console.log(filter_val);

					editor.disable( ['postalcode', 'country', 'state'] );
					//console.log(this);
					editor.inline( this );

					//console.log(e.target.s.includeFields[0]); //undefined
					///console.log($(this).html());
					//console.log(this.innerHTML);

					//var index = editor.cell().index();
					//console.log(index);

					//console.log($(this).find('input').val());

					//tdtag.find('input').val(filter_val);

					//console.log(tdtag.find('input').first().val());
					//console.log(this);

					//console.log('--------------------------');

					////var input_val = $(this).find('input').val();

					////var inner_div = document.createElement("div");
					////inner_div.innerHTML = input_val;
					////var inner_text = inner_div.textContent || inner_div.innerText || "";

					////$(this).find('input').val(inner_text);

					//console.log($(this).html());
					//editor.field( 'postalcode' ).disable();
				} );

				editor.on('setData', function(e, json, data, action) {
					//data.changeFlag = 'changed';
					//var index = editor.cell( cell ).index();
					//console.log(index);

					//return;
				});

				editor.on('initEdit', function(e) {
					//console.log('initEdit');
					//console.log(e);
					/*
					editor.show(); //Shows all fields
					editor.hide('ID');
					editor.hide('Field_Name_1');
					*/
				});

				editor.on('open', function(e) {

					//console.log('open 11');
					//console.log(this);
					//console.log(e.target.s.includeFields[0]);

					var fldname = e.target.s.includeFields[0];
					input_val = editor.field(fldname).val();

					//editor.field(fldname).val();
					//editor.field(fldname).set('amir');

					var inner_div = document.createElement("div");
					inner_div.innerHTML = input_val;
					var inner_text = inner_div.textContent || inner_div.innerText || "";

					editor.field(fldname).set(inner_text.trim());

					//console.log('open 22');
					/*
					editor.show(); //Shows all fields
					editor.hide('ID');
					editor.hide('Field_Name_1');
					*/
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

				// // Edit record
				// $('#ei_datatable_fixed_column_wrapper').on('click', '.buttons-create', function (e) {
					// //e.preventDefault();
					// alert('edit');
					// //editor.field( ['postalcode', 'country', 'state'] ).enable();
					// editor.edit(

					// //$(this).closest('tr'), {
						// //title: 'Edit record',
						// //buttons: 'Update'
					// //}
					// );
					// editor.enable( ['postalcode', 'country', 'state'] );
				// } );

				// add record
				$(document).on("click", ".buttons-create", function() {
					editor.enable( ['postalcode', 'country', 'state'] );
					editor.create( {
						//title: 'Create new record',
						//buttons: 'Add'
					} );

					editor.dependent( 'country', 'assets/ajax/zipcode-dropdowns.php?dep=country' );

				} );

				// Edit record
				$(document).on('click', '.buttons-edit', function (e) {
					//e.preventDefault();
					editor.disable( ['postalcode', 'country', 'state'] );
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



					//console.log('111');
					//console.log( otable.row({ selected: true }) );



				} );

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

				otable.columns( [1,13,14,15,16,17,18,19,20,21,22,23,24] ).visible( false );


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




			<?php if(1==1 || $_SESSION["group_id"] == 1) {?>
			//----------------------------datatable for undo delete--------------------------
			//-------------------------------------------------------------------------------
			//var undo_table;
			//$(document).ready(function (){

				//----------------editor---------------

			var undo_editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/zip-codes-undo_delete-save.php',
					table: '#undo_delete',

					idSrc:  'ID',

					fields: [

						{
							"label": "Postal Code:",
							"name": "postalcode"
						},
						{
							"label": "Country",
							"name": "country",
							"type":  "select",
							//"def": "AF",
							"options": [
								<?php echo $_SESSION['country'];?>
							]
							/*
							"options": [
								{ label: "AE", value: "AE" },
								{ label: "AF", value: "AF" },
								{ label: "US", value: "US" }
							]
							*/
						},

						{
							"label": "State",
							"name": "state",
							"type":  "select",
							"options": [
								<?php echo $_SESSION['state'];?>
							]
						},
						{
							"label": "County:",
							"name": "county"
						},
						{
							"label": "Place:",
							"name": "place",

						},
						{
							"label": "Latitude:",
							"name": "latitude"
						},
						{
							"label": "Longitude:",
							"name": "longitude"
						},
						{
							"label": "Timezone:",
							"name": "timezone"
						},
						{
							"label": "Area Codes:",
							"name": "area_codes"
						},
						{
							"label": "Utility Name:",
							"name": "utility_name"
						},
						{
							"label": "Ownership:",
							"name": "ownership"
						}




						// {
							// "label": "user_type:",
							// "name": "user_type",
							// "type": "select",
							// "options": [
								// "male",
								// "female"
							// ]
						// }

					]
				} );


				//--------------------------------------------------------------
					var undo_table = $('#undo_delete').DataTable({





					"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
				    //"dom": "Bfrtip",
					"dom": 'Blfrtip',
				    "searching" : true,
				    "paging":   true,
				    "processing": true,
				    "serverSide": true,
					"serverMethod": "post",
				    "ajax": "assets/ajax/zip-codes-deleted.php",

					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false,
						searchable: false
					 },
					 { data: 'ziputility.ID' },
					 { data: 'ziputility.postalcode' },
					 { data: 'ziputility.country' },
					 { data: 'ziputility.state' },
					 { data: 'ziputility.county' },
					 { data: 'ziputility.place' },
					 { data: 'ziputility.latitude' },
					 { data: 'ziputility.longitude' },
					 { data: 'ziputility.timezone' },

					 { data: 'ziputility.delete_date' },

					 {  data: 'ziputility.delete_by',
					    searchable: false,
						orderable:false,
						render: function ( data, type, row ) {
							//console.log(row);
							return row.user.firstname +' '+ row.user.lastname;
						}
					},

					 { data: 'ziputility.area_codes' },
					 { data: 'ziputility.utility_name' },
					 { data: 'ziputility.ownership' },
					 { data: 'ziputility.type' },
					 { data: 'ziputility.decommissioned' },
					 { data: 'ziputility.acceptable_cities' },
					 { data: 'ziputility.unacceptable_cities' },
					 { data: 'ziputility.world_region' },
					 { data: 'ziputility.irs_estimated_population_2015' },
					 { data: 'ziputility.zip2' },
					 { data: 'ziputility.eiaid' },
					 { data: 'ziputility.state2' },
					 { data: 'ziputility.Delivery' },
					 { data: 'ziputility.Energy' },
					 { data: 'ziputility.Bundled' }
					]
					,

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
						{
							'extend': 'copyHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'excelHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'csvHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'pdfHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' },
							'title' : 'Vervantis_PDF',
							'messageTop': 'Vervantis PDF Export',
						},
						{
							'extend': 'print',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'text': 'Columns',
							'extend': 'colvis',
							columns: ':gt(1)'
						}

					],


					/*
						  'columnDefs': [
							 {
								'targets': 0,
								'checkboxes': {
								   'selectRow': true
								}
							 }
						  ],
					*/
						  'select': {
							 'style': 'multi'
						  },
						  'order': [[1, 'asc']]


					   });


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


						//----deleted records --------------
						// Apply the filter
							$("#undo_delete .sdrp").on( 'keyup change', function () {
								undo_table
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

							undo_table.columns( [1,13,14,15,16,17,18,19,20,21,22,23,24] ).visible( false );


						// custom toolbar
						///$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

						// Apply the filter
						$(document.body).on('keyup change', '#undo_delete thead th input[type=text]' ,function(){

							//console.log('here');
							//console.log($(this).parent().index());

							undo_table
								.column( $(this).parent().index()+':visible' )
								//.column( $(this).parent().index() )
								.search( this.value )
								.draw();

						});


						<?php } ?>


					//add deleted record button ei_datatable_fixed_column_filter
					//$('<button class="show_deleted  dt-button buttons-html5 dt-buttons">Show Deleted</button>').appendTo('#ei_datatable_fixed_column_filter');
					$('#ei_datatable_fixed_column_filter').append('<button class="show_deleted">Show Deleted</button>');
					$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');



			//});

			$('.show_deleted').click( function() {
				$('#widget-grid').hide();
				$('#widget-grid2').removeClass('hide');
				//$('#widget-grid2').show();
			});

			$('.hide_deleted').click( function() {
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
			/*
			else{
				$('#city').html('<option value="">Select state first</option>');
			}
			*/
		//});
		}

		//loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
			///loadScript("https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.js", function(){

			//loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){

				///loadScript("assets/js/dataTables.editor.min.js", function(){

				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
				///loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
					///loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
					///loadScript("https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js", pagefunction)
				///});
				///});
				///});
				///});
				///});
				///});
			///});
		//});



		/*

				loadScript("https://cdn.datatables.net/v/dt/jqc-1.12.4/moment-2.18.1/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.js", function(){


					loadScript("assets/js/dataTables.editor.min.js", function(){

						pagefunction();

				});

				});
		*/

		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		 loadScript("assets/js/dataTables.editor.min.js", function(){
			pagefunction();
	     });
		 });




		$(document).ajaxComplete(function(event, xhr, settings) {

			/*
			if (settings.url == 'assets/ajax/zip-codes-ajax.php') {
			  $(".ar_popover").popover({ trigger: "hover" });

			  $('.showversion-link').mouseout(function() {
				  //$(this).parent().find('.ar_popover').trigger('mouseout');
				  ////$('.ar_popover').trigger('mouseout');
			  });
			}
			*/
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
