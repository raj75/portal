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
	
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-pencil-square-o fa-fw "></i> 
					Admin
				<span>&gt; 
					Client Information
				</span>
			</h1>
		</div>
		
	</div>
	
	<section id="widget-grid" class="sitestable">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Client Information </h2>
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
											<input type="text" class="form-control" placeholder="Filter Client" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Primary Contact Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Primary Contact Email" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Secondary Contact Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Secondary Contact Email" />
										</th>										
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Tax ID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Tax ID Alt" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Client Address" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter AP Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter AP Phone" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter AP Email" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility Contact Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility Phone" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility Email" />
										</th>										
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Capturis Address" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Capturis Email" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Notes" />
										</th>

									</tr>
									<tr>
									    <th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">Client </th>
										<th data-hide="phone,tablet">Primary Contact Name </th>
										<th data-hide="phone,tablet">Primary Contact Email </th>
										<th data-hide="phone,tablet">Secondary Contact Name </th>
										<th data-hide="phone,tablet">Secondary Contact Email </th>										
										<th data-hide="phone,tablet">Tax ID </th>
										<th data-hide="phone,tablet">Tax ID Alt </th>
										<th data-hide="phone,tablet">Client Address </th>
										<th data-hide="phone,tablet">AP Name </th>
										<th data-hide="phone,tablet">AP Phone </th>
										<th data-hide="phone,tablet">AP Email </th>										
										<th data-hide="phone,tablet">Utility Contact Name </th>
										<th data-hide="phone,tablet">Utility Phone </th>
										<th data-hide="phone,tablet">Utility Email </th>
										<th data-hide="phone,tablet">Capturis Address </th>
										<th data-hide="phone,tablet">Capturis Email </th>
										<th data-hide="phone,tablet">Notes </th>
										
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
					ajax: 'assets/ajax/client-information-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'id',

					fields: [

						{
							"label": "Client:",
							"name": "client"
						},
						{
							"label": "Primary Contact Name:",
							"name": "primary_contact_name",
						},

						{
							"label": "Primary Contact Email:",
							"name": "primary_contact_email",
						},
						{
							"label": "Secondary Contact Name:",
							"name": "secondary_contact_name",
						},
						{
							"label": "Secondary Contact Email:",
							"name": "secondary_contact_email",
						},
						{
							"label": "Tax ID:",
							"name": "tax_id",
						},
						{
							"label": "Tax ID Alt:",
							"name": "tax_id_alt",
						},
						{
							"label": "Client Address:",
							"name": "address",
						},
						{
							"label": "AP Name:",
							"name": "ap_name",
						},
						{
							"label": "AP Phone:",
							"name": "ap_phone",
						},
						{
							"label": "AP Email:",
							"name": "ap_email",
						},
						{
							"label": "Utility Contact Name:",
							"name": "utility_contact_name",
						},
						{
							"label": "Utility Phone:",
							"name": "utility_phone",
						},
						
						{
							"label": "Utility Email:",
							"name": "utility_email",
						},
						
						{
							"label": "Capturis Address:",
							"name": "capturis_address",
						},
						{
							"label": "Capturis Email:",
							"name": "capturis_email",
						},						
						{
							"label": "Notes:",
							"name": "notes",
							"type": "textarea",
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
					'scrollX': true,
					'serverMethod': 'post',
					'ajax': {
					   'url':'/assets/ajax/client-information-ajax.php'
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


					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false
					 },
					 { data: 'client' },
					 { data: 'primary_contact_name' },
					 { data: 'primary_contact_email' },
					 { data: 'secondary_contact_name' },
					 { data: 'secondary_contact_email' },
					 { data: 'tax_id' },
					 { data: 'tax_id_alt' },
					 { data: 'address' },
					 { data: 'ap_name' },
					 { data: 'ap_phone' },
					 { data: 'ap_email' },
					 { data: 'utility_contact_name' },
					 { data: 'utility_phone' },
					 { data: 'utility_email' },
					 { data: 'capturis_address' },
					 { data: 'capturis_email' },
					 { data: 'notes' }
					]
					,
					
					/*
					"searchCols": [
						null,
						null,
						null,
						{ "search": "US" },
					],
					*/


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

				

				

				

				// Apply the filter
				$("#ei_datatable_fixed_column .sdrp").on( 'keyup change', function () {
					otable
						.column( $(this).parent().index()+':visible' )
						.search( this.value )
						.draw();

				} );

				//otable.columns( [1,13,14,15,16,17,18,19,20,21,22,23,24] ).visible( false );


			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter
			$(document.body).on('keyup change', '#ei_datatable_fixed_column thead th input[type=text]' ,function(){

				otable
					.column( $(this).parent().index()+':visible' )
					//.column( $(this).parent().index() )
					.search( this.value )
					.draw();

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



		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		 loadScript("assets/js/dataTables.editor.min.js", function(){
			pagefunction();
	     });
		 });



	</script>
