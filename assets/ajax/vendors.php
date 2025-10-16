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

$vendor_options = dropdown_options($mysqli,'vendor');

//print_r($optionsArr);

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
	
	#ei_datatable_fixed_column .vendor_type {
		font-weight: 400 !important;
	}


	</style>
	
	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Vendors
			</h1>
		</div>
	</div>

	<section id="widget-grid" class="sitestable m-top20">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Vendors </h2>
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
											<input type="text" class="form-control" placeholder="Vendor ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Capturis Vendor ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Capturis Vendor Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Abbreviation" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Altname 1" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendoer Altname 2" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Altname 3" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Altname 4" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Altname 5" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Service Group ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Deregulated" />
										</th>
										<th class="hasinput">
											<select class="form-control vendor_type" name="vendor_type" id="vendor_type">
												<option value="">Select Vendor Type</option>
												<?php foreach($vendor_options['vendor_type'] as $key=>$val) { 
													echo "<option value='$key'>$val</option>";
												} ?>											
											</select>
											<!--<input type="text" class="form-control" placeholder="Vendor Type" />-->
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="State" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Address 1" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Address 2" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor City" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor State" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Zip" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Country" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Phone 1" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Phone 2" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Phone 3" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Fax 1" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Fax 2" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Email 1" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Email 2" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Webpage 1" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Vendor Webpage 2" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Import Date" />
										</th>


									</tr>
									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">Vendor ID </th>
										<th data-hide="phone,tablet">Capturis Vendor ID </th>
										<th data-hide="phone,tablet">Vendor Name </th>
										<th data-hide="phone,tablet">Capturis Vendor Name </th>
										<th data-hide="phone,tablet">Vendor Abbreviation </th>
										<th data-hide="phone,tablet">Vendor Altname 1 </th>
										<th data-hide="phone,tablet">Vendoer Altname 2 </th>
										<th data-hide="phone,tablet">Vendor Altname 3 </th>
										<th data-hide="phone,tablet">Vendor Altname 4 </th>
										<th data-hide="phone,tablet">Vendor Altname 5 </th>
										<th data-hide="phone,tablet">Service Group </th>
										<th data-hide="phone,tablet">Service Group ID </th>
										<th data-hide="phone,tablet">Deregulated </th>
										<th data-hide="phone,tablet">Vendor Type </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">Vendor Address 1 </th>
										<th data-hide="phone,tablet">Vendor Address 2 </th>
										<th data-hide="phone,tablet">Vendor City </th>

										<th data-hide="phone,tablet">Vendor State </th>
										<th data-hide="phone,tablet">Vendor Zip </th>
										<th data-hide="phone,tablet">Vendor Country </th>
										<th data-hide="phone,tablet">Vendor Phone 1 </th>
										<th data-hide="phone,tablet">Vendor Phone 2 </th>
										<th data-hide="phone,tablet">Vendor Phone 3 </th>
										<th data-hide="phone,tablet">Vendor Fax 1 </th>
										<th data-hide="phone,tablet">Vendor Fax 2 </th>
										<th data-hide="phone,tablet">Vendor Email 1 </th>
										<th data-hide="phone,tablet">Vendor Email 2 </th>

										<th data-hide="phone,tablet">Vendor Webpage 1 </th>
										<th data-hide="phone,tablet">Vendor Webpage 2 </th>
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
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">Vendor ID </th>

										<th data-hide="phone,tablet">Delete Date </th>
										<th data-hide="phone,tablet">Delete By </th>

										<th data-hide="phone,tablet">Capturis Vendor ID </th>
										<th data-hide="phone,tablet">Vendor Name </th>
										<th data-hide="phone,tablet">Capturis Vendor Name </th>
										<th data-hide="phone,tablet">Vendor Abbreviation </th>
										<th data-hide="phone,tablet">Vendor Altname 1 </th>
										<th data-hide="phone,tablet">Vendoer Altname 2 </th>
										<th data-hide="phone,tablet">Vendor Altname 3 </th>
										<th data-hide="phone,tablet">Vendor Altname 4 </th>
										<th data-hide="phone,tablet">Vendor Altname 5 </th>
										<th data-hide="phone,tablet">Service Group </th>
										<th data-hide="phone,tablet">Service Group ID </th>
										<th data-hide="phone,tablet">Deregulated </th>
										<th data-hide="phone,tablet">Vendor Type </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">Vendor Address 1 </th>
										<th data-hide="phone,tablet">Vendor Address 2 </th>
										<th data-hide="phone,tablet">Vendor City </th>

										<th data-hide="phone,tablet">Vendor State </th>
										<th data-hide="phone,tablet">Vendor Zip </th>
										<th data-hide="phone,tablet">Vendor Country </th>
										<th data-hide="phone,tablet">Vendor Phone 1 </th>
										<th data-hide="phone,tablet">Vendor Phone 2 </th>
										<th data-hide="phone,tablet">Vendor Phone 3 </th>
										<th data-hide="phone,tablet">Vendor Fax 1 </th>
										<th data-hide="phone,tablet">Vendor Fax 2 </th>
										<th data-hide="phone,tablet">Vendor Email 1 </th>
										<th data-hide="phone,tablet">Vendor Email 2 </th>

										<th data-hide="phone,tablet">Vendor Webpage 1 </th>
										<th data-hide="phone,tablet">Vendor Webpage 2 </th>
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
					ajax: 'assets/ajax/vendors-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'vendor_id',

					fields: [


						{
							"label": "Vendor ID:",
							"name": "vendor_id"
						},

						{
							"label": "Capturis Vendor ID:",
							"name": "capturis_vendor_id",

						},

						{
							"label": "Vendor Name:",
							"name": "vendor_name",
						},
						{
							"label": "Capturis Vendor Name:",
							"name": "capturis_vendor_name"
						},
						{
							"label": "Vendor Abbreviation:",
							"name": "vendor_abbreviation",

						},
						{
							"label": "Vendor Altname 1:",
							"name": "vendor_altname1"
						},
						{
							"label": "Vendoer Altname 2:",
							"name": "vendor_altname2"
						},

						{
							"label": "Vendor Altname 3:",
							"name": "vendor_altname3"
						},
						{
							"label": "Vendor Altname 4:",
							"name": "vendor_altname4"
						},
						{
							"label": "Vendor Altname 5:",
							"name": "vendor_altname5"
						},
						{
							"label": "Service Group:",
							"name": "service_group"
						},
						{
							"label": "Service Group ID:",
							"name": "service_group_id"
						},
						{
							"label": "Deregulated:",
							"name": "deregulated"
						},
						{
							"label": "Vendor Type:",
							"name": "vendor_type",
							type:  "select",
							options: [
								<?php foreach($vendor_options['vendor_type'] as $key=>$val) { 
										echo "{ label: '$key', value: '$val' },";
								} ?>
							],
						},
						{
							"label": "State:",
							"name": "state"
						},
						{
							"label": "Vendor Address 1:",
							"name": "vendorAddr1"
						},
						{
							"label": "Vendor Address 2:",
							"name": "vendorAddr2"
						},
						{
							"label": "Vendor City:",
							"name": "vendorCity"
						},
						{
							"label": "Vendor State:",
							"name": "vendorState"
						},
						{
							"label": "Vendor Zip:",
							"name": "vendorZip"
						},
						{
							"label": "Vendor Country:",
							"name": "vendorCountry"
						},
						{
							"label": "Vendor Phone 1:",
							"name": "vendorPhoneNbr1"
						},
						{
							"label": "Vendor Phone 2:",
							"name": "vendorPhoneNbr2"
						},
						{
							"label": "Vendor Phone 3:",
							"name": "vendorPhoneNbr3"
						},
						{
							"label": "Vendor Fax 1:",
							"name": "vendorFaxNbr1"
						},
						{
							"label": "Vendor Fax 2:",
							"name": "vendorFaxNbr2"
						},
						{
							"label": "Vendor Email 1:",
							"name": "vendorEmail1"
						},
						{
							"label": "Vendor Email 2:",
							"name": "VendorEmail2"
						},
						{
							"label": "Vendor Webpage 1:",
							"name": "vendorWebpage1"
						},
						{
							"label": "Vendor Webpage 2:",
							"name": "vendorWebpage2"
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
					   'url':'assets/ajax/vendors-ajax.php'
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
					 { data: 'vendor_id' },
					 { data: 'capturis_vendor_id' },
					 { data: 'vendor_name' },
					 { data: 'capturis_vendor_name' },
					 { data: 'vendor_abbreviation' },
					 { data: 'vendor_altname1' },
					 { data: 'vendor_altname2' },
					 { data: 'vendor_altname3' },
					 { data: 'vendor_altname4' },
					 { data: 'vendor_altname5' },
					 { data: 'service_group' },
					 { data: 'service_group_id' },
					 { data: 'deregulated' },
					 { data: 'vendor_type' },
					 { data: 'state' },
					 { data: 'vendorAddr1' },
					 { data: 'vendorAddr2' },
					 { data: 'vendorCity' },
					 { data: 'vendorState' },
					 { data: 'vendorZip' },
					 { data: 'vendorCountry' },
					 { data: 'vendorPhoneNbr1' },
					 { data: 'vendorPhoneNbr2' },
					 { data: 'vendorPhoneNbr3' },
					 { data: 'vendorFaxNbr1' },
					 { data: 'vendorFaxNbr2' },
					 { data: 'vendorEmail1' },
					 { data: 'VendorEmail2' },
					 { data: 'vendorWebpage1' },
					 { data: 'vendorWebpage2' },
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
						},
						{ 'extend': 'create', editor: editor },
						{ 'extend': 'edit',   editor: editor },
						{ 'extend': 'remove', editor: editor },



					],
					"autoWidth" : true


				});

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

				// Activate an inline edit on click of a table cell
				$('#ei_datatable_fixed_column').on( 'click', 'tbody td:not(:first-child)', function (e) {
					var tdtag = $(this);

					if (e.target.nodeName == 'I' || e.target.nodeName == 'A') {
						return;
					}
					//editor.disable( ['postalcode', 'country', 'state'] );
					//console.log(this);
					editor.inline( this );

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
					
					var vendor_str = otable.rows( { selected: true } ).data()[0]['vendor_type'];
					var vendor_type = $($.parseHTML(vendor_str)).text().trim();
					$('#DTE_Field_vendor_type').val(vendor_type);

					//console.log( otable.row({ selected: true }) );
				} );


				var undo_editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/vendors-undo.php',
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


				var undo_table;
				
				//undo_table = $('#undo_delete').DataTable();
				
				function undo_delete_datatable() {
				/* COLUMN FILTER  */
					undo_table = $('#undo_delete').DataTable({

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'ajax': {
					   'url':'assets/ajax/vendors-deleted.php'
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
					 { data: 'vendor.vendor_id' },
					 { data: 'vendor.delete_date' },

					 {  data: 'vendor.delete_by',
					    searchable: false,
						orderable:false,
						render: function ( data, type, row ) {
							//console.log(row);
							return row.user.firstname +' '+ row.user.lastname;
						}
					},

					 { data: 'vendor.capturis_vendor_id' },
					 { data: 'vendor.vendor_name' },
					 { data: 'vendor.capturis_vendor_name' },
					 { data: 'vendor.vendor_abbreviation' },
					 { data: 'vendor.vendor_altname1' },
					 { data: 'vendor.vendor_altname2' },
					 { data: 'vendor.vendor_altname3' },
					 { data: 'vendor.vendor_altname4' },
					 { data: 'vendor.vendor_altname5' },
					 { data: 'vendor.service_group' },
					 { data: 'vendor.service_group_id' },
					 { data: 'vendor.deregulated' },
					 { data: 'vendor.vendor_type' },
					 { data: 'vendor.state' },
					 { data: 'vendor.vendorAddr1' },
					 { data: 'vendor.vendorAddr2' },
					 { data: 'vendor.vendorCity' },
					 { data: 'vendor.vendorState' },
					 { data: 'vendor.vendorZip' },
					 { data: 'vendor.vendorCountry' },
					 { data: 'vendor.vendorPhoneNbr1' },
					 { data: 'vendor.vendorPhoneNbr2' },
					 { data: 'vendor.vendorPhoneNbr3' },
					 { data: 'vendor.vendorFaxNbr1' },
					 { data: 'vendor.vendorFaxNbr2' },
					 { data: 'vendor.vendorEmail1' },
					 { data: 'vendor.VendorEmail2' },
					 { data: 'vendor.vendorWebpage1' },
					 { data: 'vendor.vendorWebpage2' },
					 { data: 'vendor.importDate' },

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
						},
						/*
						{ 'extend': 'create', editor: editor },
						{ 'extend': 'edit',   editor: editor },
						{ 'extend': 'remove', editor: editor },
						*/



					],
					"autoWidth" : true


					});
				
				} // end undo delete table function

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
			
			$("#ei_datatable_fixed_column .vendor_type").on( 'keyup change', function () {
	        
				otable
					.column( $(this).parent().index()+':visible' )
					//.column( $(this).parent().index() )
					.search( this.value )
					.draw();
					
					/*
				otable
				.column( $(this).parent().index()+':visible' )
	            //.search(this.value, true, true, false)
				  //.search(this.value, false, false, false)
				  .search(this.value, false, false, false)
	            //.search(this.value, false,true,false)
				//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();
			
				//otable.search( this.value, false, false, false ).draw();
				*/

	    } );




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
				
				$('.hide_deleted').remove();
				$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');
				
				
			});

			//$('.hide_deleted').click( function() {
			$(document.body).on('click', '.hide_deleted' ,function(){
				$('#widget-grid').show();
				$('#widget-grid2').addClass('hide');
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
