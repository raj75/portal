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

//if($user_one != 1) die("Under Maintainence!");
if(isset($_GET["load"])){
	////$sql = "SELECT ID,Commodity,State,Utility,ISO,`Market status`,`Account, POD ID, ESIID`,Prefix,`Name Key`,`Meter Number`,`LOA Required`,`Billing options`,`Purchase of Receivables (POR)`,`Consolidated billing option`,`Lead Time for Enrolls/Drops`,`Special Enrollment Requirements`,`Days for ES response`,Comments FROM enrollment order by ID";
	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />

	<style>
	#ei_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
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

	.DTED_Lightbox_Background{z-index:905 !important;}
	.DTED_Lightbox_Wrapper{z-index:906 !important;}
	</style>
	
	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						
						<h2>Utility Requirements </h2>

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
									<!--<tr id="multiselect">
										<th class="hasinput">
											<select id="selectCompany" name="selectCompany" multiple="multiple"></select>
										</th>
										<th class="hasinput">
											<select id="selectDivision" name="selectDivision[]" multiple="multiple"></select>
										</th>
										<th class="hasinput">
											<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>
										</th>
										<th class="hasinput">
											<select id="selectState" name="selectState[]" multiple="multiple"></select>
										</th>
										<th class="hasinput">
											<select id="selectCity" name="selectCity[]" multiple="multiple"></select>
										</th>
										<th class="hasinput">
										</th>
										<th class="hasinput">
										</th>
										<th class="hasinput">
											<select id="selectStatus" name="selectStatus[]" multiple="multiple"></select>
										</th>
									</tr>-->
									<tr>
										<th class="hasinput">

										</th>
									<?php //if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter ID" />
										</th>
									<?php //} ?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Commodity" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter State" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Utility" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter ISO" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Market Status" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Account, POD ID, ESIID" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Prefix" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Name Key" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Meter Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter LOA Required" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Billing Options" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Purchase of Receivables (POR)" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Consolidated billing option" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Lead Time for Enrolls/Drops" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Special Enrollment Requirements" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Days for ES response" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Comments" />
										</th>
										<?php if(1==2){?><th></th><?php } ?>
									</tr>
									<tr>
										<th data-hide="phone,tablet"> </th>
									<?php //if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">ID </th>
									<?php //} ?>
										<th data-hide="phone,tablet">Commodity </th>
										<th data-hide="phone,tablet">State </th>
										<th data-hide="phone,tablet">Utility </th>
										<th data-hide="phone,tablet">ISO </th>
										<th data-hide="phone,tablet">Market Status </th>
										<th data-hide="phone,tablet">Account, POD ID, ESIID </th>
										<th data-hide="phone,tablet">Prefix </th>
										<th data-hide="phone,tablet">Name Key </th>
										<th data-hide="phone,tablet">Meter Number </th>
										<th data-hide="phone,tablet">LOA Required </th>
										<th data-hide="phone,tablet">Billing Options </th>
										<th data-hide="phone,tablet">Purchase of Receivables (POR) </th>
										<th data-hide="phone,tablet">Consolidated billing option </th>
										<th data-hide="phone,tablet">Lead Time for Enrolls/Drops </th>
										<th data-hide="phone,tablet">Special Enrollment Requirements </th>
										<th data-hide="phone,tablet">Days for ES response </th>
										<th data-hide="phone,tablet">Comments </th>
										<?php if(1==2){?><th data-hide="phone,tablet">Action</th><?php } ?>
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

				/*----------------editor----------------*/
				var editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/enrollment-info-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'ID',

					fields: [

						{
							"label": "Commodity:",
							"name": "Commodity"
						},
						{
							"label": "State",
							"name": "State",
							/*
							"type":  "select",
							//"def": "AF",
							"options": [
								<?php echo $_SESSION['country'];?>
							]
							*/
							/*
							"options": [
								{ label: "AE", value: "AE" },
								{ label: "AF", value: "AF" },
								{ label: "US", value: "US" }
							]
							*/
						},

						{
							"label": "Utility:",
							"name": "Utility"
						},
						{
							"label": "ISO:",
							"name": "ISO",

						},
						{
							"label": "Market status:",
							"name": "Market status"
						},
						{
							"label": "Account, POD ID, ESIID:",
							"name": "Account, POD ID, ESIID"
						},
						{
							"label": "Prefix:",
							"name": "Prefix"
						},
						{
							"label": "Name Key:",
							"name": "Name Key"
						},
						{
							"label": "Meter Number:",
							"name": "Meter Number"
						},
						{
							"label": "LOA Required:",
							"name": "LOA Required"
						},


						{
							"label": "Billing options:",
							"name": "Billing options"
						},
						{
							"label": "Purchase of Receivables (POR):",
							"name": "Purchase of Receivables (POR)"
						},
						{
							"label": "Consolidated billing option:",
							"name": "Consolidated billing option"
						},
						{
							"label": "Lead Time for Enrolls/Drops:",
							"name": "Lead Time for Enrolls/Drops"
						},
						{
							"label": "Special Enrollment Requirements:",
							"name": "Special Enrollment Requirements"
						},
						{
							"label": "Days for ES response:",
							"name": "Days for ES response"
						},
						{
							"label": "Comments:",
							"name": "Comments"
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
					/*'serverMethod': 'post',*/
					'ajax': {
					   'url':'assets/ajax/enrollment-info-ajax.php',
					   'type':'post'
					},
					
					"drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},  

					"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"dom": 'Blfrtip',

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
					 { data: 'ID' },
					 { data: 'Commodity' },
					 { data: 'State' },
					 { data: 'Utility' },
					 { data: 'ISO' },
					 { data: 'Market status' },
					 { data: 'Account, POD ID, ESIID' },
					 { data: 'Prefix' },
					 { data: 'Name Key' },
					 { data: 'Meter Number' },
					 { data: 'LOA Required' },
					 { data: 'Billing options' },
					 { data: 'Purchase of Receivables (POR)' },
					 { data: 'Consolidated billing option' },
					 { data: 'Lead Time for Enrolls/Drops' },
					 { data: 'Special Enrollment Requirements' },
					 { data: 'Days for ES response' },
					 { data: 'Comments' }
					]
					,

					"searchCols": [
					/*
						null,
						null,
						null,
						{ "search": "US" },
						*/
					],
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

					//editor.disable( ['postalcode', 'country', 'state'] );
					//console.log(this);
					editor.inline( this );

				} );









			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter
			$("#ei_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

				otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

			});
		};

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

		
		/*
		loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
		 loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
		  loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
		   loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
			loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
			 loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
			  loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
			   loadScript("https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js", function(){
				 loadScript("https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js", function(){
					 loadScript("assets/js/dataTables.editor.min.js", function(){
						pagefunction();
					 });
				 });
			   });
			  });
			 });
			});
		   });
		  });
		 });
	    });
		*/
		
		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
			loadScript("assets/js/dataTables.editor.min.js", function(){
				pagefunction();
			});
		});


	<?php if($_SESSION["group_id"] == 3){?>
	function loadcmmenu(cmid) {
		$('#cmresponse').html('');
		$('#cmresponse').load('assets/ajax/enrollment-info.php?action=view&cmid='+cmid);
	}
	<?php } ?>
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
	function loadeimenu(eiid) {
		$('#eiresponse').html('');
		$('#eiresponse').load('assets/ajax/enrollment-info.php?action=edit&cmid='+cmid);
	}

	function deleteei(eiid) {
		$('#eiresponse').html('');
		var r = confirm("Are you sure want to delete it!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/enrollmentinfoedit.inc.php',
				data: {cmid:cmid,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							alert("Success");
							parent.$("#eitable").html('');
							parent.$('#eitable').load('assets/ajax/enrollment-info.php?load=true');
						}else
							alert("Error in request. Please try again later.");
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}
	<?php } ?>
	</script>
	<?php


}else{ ?>
	<style>
	.noshow{
		height: 18%;
		opacity: 0.1;
		position: absolute;
		right: 2%;
		top: 0;
		width: 6%;
		z-index: 9999;
	}
	#cmopdialog{
		overflow-y:hidden !important;
	}
	</style>
	<br /><br />
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Energy Procurement
				<span>>
					Utility Requirements
				</span>
			</h1>
		</div>
	</div>
	<!-- widget grid -->
	<section id="widget-grid" class="eitable">

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div align="right" style="padding-bottom:10px;display:none;">
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
					<button class="btn-primary" align="right" id="new-cm" style="height: 30px !important;width: auto !important;">Add New Utility Requirements</button>
	<?php } ?>
				</div>
				<div id="eitable"></div>
			</article>
		</div>
		<!-- end row -->

	</section>
	<!-- end widget grid -->
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
	<section id="eidetails"></section>
	<?php }
	if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3){?>
	<div id="eiresponse"></div>
	<div id="eiopdialog"></div>
	<?php } ?>
	<script type="text/javascript">
	$(document).ready(function(){
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
		$("#new-ei").click(function(){
			//$('#datatable_fixed_column').DataTable().ajax.reload();
			//otable.ajax.reload();
			$("#dialog-message").remove();
			$('#eiresponse').html('');
			$('#eiresponse').load('assets/ajax/enrollment-info.php?action=add');
		});
	<?php } ?>
		$('#eitable').load("assets/ajax/enrollment-info.php?load=true");
	});
	</script>
<?php
}
?>
