<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

if(checkpermission($mysqli,125)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$postID=rand(20,100);

if(!isset($_SESSION['group_id']))
	die("Restricted Access!");

if($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2){}else die("Restricted Access!");

if($_SESSION["group_id"] == 2) $makereadonly=1;
else $makereadonly=0;


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

	div.dataTables_filter input {
		width: 10em !important;
	}
	/*.ck-editor__main{
		overflow-x: scroll;
		max-height: 278px;
	}*/
	#top-message {
		display: none; /* Initially hidden */
		position: fixed;
		top: 40%;
		left: 0;
		width: 100%;
		background-color: #f8d7da;
		color: #721c24;
		padding: 10px;
		text-align: center;
		z-index: 1000;
		border-bottom: 1px solid #f5c6cb;
    }	
	#widget-grid .container{width:100%;}
	#wid-id-3{float:right;display:none;padding-left:10px;}
	.no-display{display:none;}
	.half-height-div {
        height: 50vh !important; /* 50% of the viewport height */
    }
	.ck-editor__main{overflow-y: scroll;
    height: 50vh;}
.loader {
    border: 10px solid #f3f3f3;
    -webkit-animation: spin 1s linear infinite;
    animation: spin 1s linear infinite;
    border-top: 10px solid #555;
    border-radius: 50%;
  width: 100px;
  height: 100px;
  -webkit-animation: spin 1s linear infinite;
  animation: spin 1s linear infinite;
position: absolute;
  z-index: 99999;
  top:45%;
  left:45%
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.ck-restricted-editing_mode_standard{height:100%;}
	</style>

	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
	
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-pencil-square-o fa-fw "></i> 
					Intranet
				<span>&gt; 
					Process Documents
				</span>
			</h1>
		</div>
		
	</div>
	
	<section id="widget-grid" class="sitestable">
		<div class="container">
			<div class="row">

				<div class="jarviswidget jarviswidget-color-blueDark col-sm-5 col-md-5 col-lg-5" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Process Documents </h2>
					</header>

							<!-- widget div-->
							<div id="datatablecont" class="">

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
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter ID" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Group" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Sub Group 1" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Sub Group 2" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Sub Group 3" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Process Name" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Owner" />
												</th>									
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Created Date" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Modified Date" />
												</th>

											</tr>
											<tr>
												<th data-hide="phone,tablet">ID </th>
												<th data-hide="phone,tablet">Group </th>
												<th data-hide="phone,tablet">Sub Group 1 </th>
												<th data-hide="phone,tablet">Sub Group 2 </th>
												<th data-hide="phone,tablet">Sub Group 3 </th>
												<th data-hide="phone,tablet">Process Name </th>
												<th data-hide="phone,tablet">Owner </th>
												<th data-hide="phone,tablet">Created Date </th>
												<th data-hide="phone,tablet">Modified Date </th>
												
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


				<div class="jarviswidget jarviswidget-color-blueDark col-sm-7 col-md-7 col-lg-7 half-height-div" id="wid-id-3" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false"  data-widget-deletebutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Process </h2>
						<span class="widget-icon delete-btn" style="float: right;padding-right: 24px;cursor:pointer;"> <i class="fa fa-times"></i> </span>
					</header>
					</header>

							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body">
									<div id="edit-form-postm" class="" style="margin-top:-20px;"></div>
								</div>
							</div>
				</div>
			</div>
		</div>
	</section>
<?php $postCont=""; ?>	
	<div id="top-message" style=""> Upload in progress. Please be patient! </div>
	<section id="indetails"></section>
	<div class="loader"></div>
	<script src="assets/js/base64_decode.js"></script>
	<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(".loader").hide();
	$(".ck-editor__top").hide();
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
					ajax: 'assets/ajax/process-documents-save.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'ID',

					fields: [
						{
							"label": "Group:",
							"name": "Group"
						},
						{
							"label": "Sub Group 1:",
							"name": "Sub Group 1"
						},
						{
							"label": "Sub Group 2:",
							"name": "Sub Group 2",
						},

						{
							"label": "Sub Group 3:",
							"name": "Sub Group 3",
						},
						{
							"label": "Process Name:",
							"name": "Process Name",
						},
						{
							"label": "Owner:",
							"name": "Owner",
						},
						{
							"label": "Created Date:",
							"name": "Created Date",
						},
						{
							"label": "Modified Date:",
							"name": "Modified Date",
						}
						/*{
							"label": "Banking Information:",
							"name": "banking_info",
							"type": "select",
							"options": ['Yes', 'No'],
						},
						{
							"label": "Receiver:",
							"name": "receiver",
							"type": "textarea",

						},
						{
							"label": "Notes:",
							"name": "notes",
							"type": "textarea",
						},
						*/



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
					'responsive': true,
					'ajax': {
					   'url':'/assets/ajax/process-documents-ajax.php'
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
					 { data: 'ID' },
					 { data: 'Group' },
					 { data: 'Sub Group 1' },
					 { data: 'Sub Group 2' },
					 { data: 'Sub Group 3' },
					 { data: 'Process Name' },
					 { data: 'Owner' },
					 { data: 'Created Date' },
					 { data: 'Modified Date' }
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
						/*{
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
						},*/
						<?php if($makereadonly==0){ ?>
						{
							'text': 'New'
							, 'className': 'dt-button buttons-collection createnew'
							//'columns': ':visible:not(:first-child)'
							//columns: ':gt(1)'
						},
						<?php } ?>
						{
							'text': <?php if($makereadonly==0){ ?>'Edit' <?php }else{ ?> 'View' <?php } ?>
							, 'className': 'dt-button buttons-collection editit'
							//'columns': ':visible:not(:first-child)'
							//columns: ':gt(1)'
						}<?php if($makereadonly==0){ ?>,{
							'text': 'Delete'
							, 'className': 'dt-button buttons-selected buttons-remove'
							//'columns': ':visible:not(:first-child)'
							//columns: ':gt(1)'
						}<?php } ?>,{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							columns: ':gt(1)'
						}

						//,						
						//{ 'extend': 'create', editor: editor },
						//{ 'extend': 'edit',   editor: editor },
						//{ 'extend': 'remove', editor: editor }
						

					],
					"autoWidth" : true


				});

				
				otable.on('responsive-resize', function (e, datatable, columns) {
					var count = columns.reduce(function (a, b) {
						return b === false ? a + 1 : a;
					}, 0);
				 
					//console.log(count + ' column(s) are hidden');
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


	//Cancel Edit Post Form
	$( document ).off( "click", ".cancel-edit-postm");
	$( document ).on( "click", ".cancel-edit-postm", function() {
		var pid=$(this).attr('data-epid');
		//if($("#edit-form-postm"+pid).css('display') == 'block')
		//{
			var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
			if (selection) {
				document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
			}
		//}
		$("#edit-form-postm").html('');
		$("#wid-id-3").hide();
		$(".ck-editor__top").hide();
		//$("#wid-id-2").removeClass('col-sm-5 col-md-5 col-lg-5').addClass('col-sm-12 col-md-12 col-lg-12');
		return false;
	});

	
	//Cancel Edit Post Form
	$( document ).off( "click", "#wid-id-3 .delete-btn");
	$( document ).on( "click", "#wid-id-3 .delete-btn", function(event) {
		event.preventDefault();
		var pid=$(this).attr('data-epid');
		//if($("#edit-form-postm"+pid).css('display') == 'block')
		//{
			var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
			if (selection) {
				document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
			}
		//}
		$("#edit-form-postm").html('');
		$("#wid-id-3").hide();
		//$("#wid-id-2").removeClass('col-sm-5 col-md-5 col-lg-5').addClass('col-sm-12 col-md-12 col-lg-12');
		return false;
	});

<?php if($makereadonly==0){ ?>

	//New
	$( document ).off( "click", ".createnew");
	$( document ).on( "click", ".createnew", function() {
		$("#indetails").load('assets/ajax/process-documents-pedit.php?ct=<?php echo rand(0,100); ?>&action=createnew');
		return false;
	});

<?php } ?>	

	//Edit
	$( document ).off( "click", ".editit");
	$( document ).on( "click", ".editit", function() {
		//alert("hi");
		//$(".ck-editor__top").show();
		if ($('#ei_datatable_fixed_column tr.selected td:first-child').length) {
			var p_id=Number($('#ei_datatable_fixed_column tr.selected td:first-child').html());

			if(isNaN(p_id) !== true){
				$("#indetails").load('assets/ajax/process-documents-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&inid='+p_id);
			}else{
				//alert("Error occured. Please try after sometimes.");
				$.smallBox({
					title : "Error in request. Please try again later.",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
			}
		}
		return false;
	});
<?php if($makereadonly==0){ ?>	
	//Edit
	$( document ).off( "click", ".buttons-remove");
	$( document ).on( "click", ".buttons-remove", function() {
		//alert("hi");
		if ($('#ei_datatable_fixed_column tr.selected td:first-child').length) {
			var p_id=Number($('#ei_datatable_fixed_column tr.selected td:first-child').html());

			if(isNaN(p_id) !== true){
				var result = confirm("Are you sure to delete it?");
				if (result) {
					var myKeyVals = { id : p_id};
					var saveData = $.ajax({
					  type: 'POST',
					  url: "assets/includes/processdocs.inc.php?action=deleteit",
					  data: myKeyVals,
					  dataType: "text",
					  success: function(resultData) {
						if(resultData != false)
						{
							var result = JSON.parse(resultData);
							if(result.error == false)
							{						
								//alert("Deleted");
								$.smallBox({
									title : "Deleted",
									content : "",
									color : "#296191",
									iconSmall : "fa fa-bell bounce animated",
									timeout : 4000
								});
								$("#ei_datatable_fixed_column").DataTable().page.len(12).draw();
							}else{
								//alert("Error occured. Please try after sometimes.");
								$.smallBox({
									title : "Error in request. Please try again later.",
									content : "",
									color : "#296191",
									iconSmall : "fa fa-bell bounce animated",
									timeout : 4000
								});
							}
						}
					  }
					});
					$("#in_datatable_fixed_column").DataTable().page.len(12).draw(false);
					saveData.error(function() { 
					//alert("Error occured. Please try after sometimes."); 
								$.smallBox({
									title : "Error in request. Please try again later.",
									content : "",
									color : "#296191",
									iconSmall : "fa fa-bell bounce animated",
									timeout : 4000
								});
					});
				}				
			}else{
				//alert("Error occured. Please try after sometimes.");
				$.smallBox({
					title : "Error in request. Please try again later.",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
			}
		}
		return false;
	});

<?php } ?>


	$( document ).off( "click", "#ei_datatable_fixed_column tr");
	$( document ).on( "click", "#ei_datatable_fixed_column tr", function() {
		var dtthis=$(this);
		$('#ei_datatable_fixed_column tr').each(function() {
			$(this).removeClass('selected'); 
		});		
		if (dtthis.hasClass('selected')) {
			dtthis.removeClass('selected');
		} else {
			dtthis.removeClass('selected').addClass('selected');
			var s_id=Number($('#ei_datatable_fixed_column tr.selected td:first-child').html());
			if(isNaN(s_id) !== true){
				o_details(s_id);
			}
		}	
	});
	
	//Cancel Edit Post Form
	/*$( document ).off( "click", ".enable-edit-postm");
	$( document ).on( "click", ".enable-edit-postm", function() {
		var pid=$(this).attr('data-epid');
		editor.disableReadOnlyMode( 'enable-edit-postm'+pid );
		//$('#enable-edit-postm'+pid ).ckeditorGet().setReadOnly(true);
		//return false;
	});*/
<?php if($makereadonly==0){ ?> 	
$( document ).off( "submit", "form.edit-postm");
$( document ).on( "submit", "form.edit-postm", function() {
	var pid=$(this).attr('data-pid');
	//CKupdate();
	  //disable the default form submission
	  //event.preventDefault();
	  	/*var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
		if (selection) {
			document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
		}*/
		var formData = new FormData($(this)[0]);
		$.ajax({
			url: 'assets/includes/processdocs.inc.php',
			type: 'POST',
			data: formData,
			beforeSend: function(msg){
				$("#top-message").show();
			},
			success: function (data) {
				$("#top-message").hide();
			  if(data != false)
			  {
				var result = JSON.parse(data);
				if(result.error == false)
				{
					//alert("Saved!");
					$.smallBox({
						title : "Saved!",
						content : "",
						color : "#296191",
						iconSmall : "fa fa-bell bounce animated",
						timeout : 4000
					});
					$(".cancel-edit-postm").trigger("click");
					//$("#in_datatable_fixed_column").DataTable().page.len(12).draw(false);
					//$(".select-checkbox").trigger("click");
					//$("table.dataTable tr.selected td.select-checkbox:after").trigger("click");
					//$("td.select-checkbox:after").trigger("click");
					//$("tr.odd").removeClass('selected');
					//$("tr.even").removeClass('selected');
					//$("td.select-checkbox").removeClass(':after');
					//$("#wid-id-2").removeClass('col-sm-5 col-md-5 col-lg-5').addClass('col-sm-12 col-md-12 col-lg-12');
					//table.columns().checkboxes.deselect(true);
					return false;
				}else{
					//alert(result.error);
					$.smallBox({
						title : result.error,
						content : "",
						color : "#296191",
						iconSmall : "fa fa-bell bounce animated",
						timeout : 4000
					});
				}
			  }else{
				//alert("Error Occured. Please try after sometimes!");
				$.smallBox({
					title : "Error in request. Please try again later.",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
			  }
			},
			cache: false,
			contentType: false,
			processData: false
		});
		$("#top-message").hide();
		return false;
	});
<?php } ?>	
	function o_details(did){
		if(did == "" || did == 0) return false;
		$(".cancel-edit-postm").trigger("click");
		$(".cancel-edit-postm").trigger("click");
			//var did=$this.closest('td').next().text();
			//alert($(".2").closest('tr').attr('class'));
			$(".loader").hide();
			$(".loader").show();
			$.ajax({
				url: 'assets/includes/processdocs.inc.php',
				type: 'POST',
				data: { id: did, show: "yes" }
			})
			.done(function( msg ) {$(".loader").hide();
			  if(msg != false)
			  {
				var result = JSON.parse(msg);
				if(result.error == false)
				{
					$("#wid-id-3").show();
					//$("#wid-id-2").removeClass('col-sm-12 col-md-12 col-lg-12').addClass('col-sm-5 col-md-5 col-lg-5');
					$("#edit-form-postm").html('<hr><form id="form-edit-postm'+result.id+'" class="edit-postm" enctype="multipart/form-data"><p class="clear"><span class="txt-color-blue"><b>Description:</b></span></p><textarea name="edit-postm" id="edit-postm'+result.id+'" class="ckeditor" placeholder="Enter description" style="width:100%;height:auto;min-height:100px;">'+decodeFromBase64(result.docdescriptions)+'</textarea><?php if($makereadonly==0){ ?><input type="hidden" name="post-idm" value="'+result.id+'"><div style="padding: 0;text-align: center;margin: 0;margin-top:5px;"><button class="btn btn-primary enable-edit-postm"  type="button" id="enable-edit-postm'+result.id+'" data-epid="'+result.id+'">Edit</button>&nbsp;<button class="btn btn-primary submit-edit-postm no-display" type="submit" id="submit-edit-postm'+result.id+'" data-pid="'+result.id+'">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-postm no-display"  type="button" id="cancel-edit-postm'+result.id+'" data-epid="'+result.id+'">Cancel</button></div><?php } ?><br /></form>');
					//my_editorm[result.id].destroy(true);
					ClassicEditor
					.create( document.querySelector( "#edit-postm"+result.id ),
						{removePlugins: ['Title','MediaEmbed'],
						placeholder: ''} )
					.then( editor => {
						editor.enableReadOnlyMode( 'edit-postm'+result.id );
						$(".ck-editor__top").hide();
						<?php if($makereadonly==0){ ?>
						document.getElementById('enable-edit-postm'+result.id).addEventListener('click', function() {
							//editor.setReadOnly(false);
							editor.disableReadOnlyMode( 'edit-postm'+result.id );
							$('#enable-edit-postm'+result.id).hide();
							$('#submit-edit-postm'+result.id).show();
							$('#cancel-edit-postm'+result.id).show();
							$(".ck-editor__top").show();
						});
						<?php } ?>
					} )
					.catch( error => {
							console.error( error );
					});
					//CKEDITOR.instances.'edit-postm'+result.id.config.readOnly = true;
					
					
					return false;
				}else{
					alert(result.error);
				}
			  }else{
				//alert("Error");
				$.smallBox({
					title : "Error in request. Please try again later.",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
			  }
			});
			//$(".loader").hide();
	}
	
	function clearme(ceid){
		$("#indetails").html('');
	}
	
	function clearmeedit(ceid){
		$("#indetails").html('');
	}
	
	function decodeFromBase64(base64) {
		return new TextDecoder().decode(Uint8Array.from(atob(base64), c => c.charCodeAt(0)));
	}
	
	//$(document).ready(function () {

         //set editor1 readonly
         //CKEDITOR.replace('editor1', {readOnly:true});
        // CKEDITOR.replace('editor2');

         //set editor2 readonly
         //CKEDITOR.instances.editor2.config.readOnly = true;

    //});

	function EnableEditor2(ckid) {
	 //CKEDITOR.instances.editor2.setReadOnly(false);
	 editor.disableReadOnlyMode( ckid );
	}
	</script>
