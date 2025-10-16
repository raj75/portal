<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

//print_r($_POST);
//die();

$user_one=$_SESSION["user_id"];


?>

	<link href="assets/plugins/datatables_ar/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet" type="text/css" />	
	
	<link type="text/css" href="assets/plugins/datatables_ar/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
	
	<!--<link href="assets/css/editor.jqueryui.min.css" rel="stylesheet" type="text/css" /> -->
	<link href="assets/plugins/datatables_ar/datetime/1.1.2/css/dataTables.dateTime.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />
	
<style>
#notify_btn{margin-top:20px;}
.page-title{margin: 20px 0 20px;}

	#admin_notifications_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;		
	}
	#admin_notifications_length{
	float: right !important;
	margin: 1% 1% !important;	
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#admin_notifications{border-bottom: 1px solid #ccc !important;}
	#admin_notifications .widget-body,#admin_notifications #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}
	
	.m-top{margin-top:56px;}
	.m-top45{margin-top:47px;}
	.m-top77{margin-top:79px;}
	.m-bottom50{margin-bottom: -50px !important;font-weight:bold;z-index:98;margin-top: 15px;}
	.m-bottom50 span{vertical-align: top;}
	.sdrp{width:65px; font-weight:normal;}
	
	.DTED_Lightbox_Background{z-index:905 !important;}
	.DTED_Lightbox_Wrapper{z-index:906 !important;}
	
	/*
	div.DTE_Body div.DTE_Body_Content div.DTE_Field {
		width: 50%;
		padding: 5px 20px;
		box-sizing: border-box;
	}
	*/
	/*
	div.DTE_Body div.DTE_Form_Content {
		display:flex;
		flex-direction: row;
		flex-wrap: wrap;
	}
	
	div.DTE_Field select{
		width:100%;
	}
	*/
	
	.popover{max-width:500px;}
	
	#admin_notifications tbody td .fa{cursor:pointer;}
	
	div.dataTables_filter label {float: left;}
	
	#ui-datepicker-div{z-index:1001 !important;}
	
	.ui-datepicker-prev span{width:30px; cursor:pointer;}
	.ui-datepicker-next span{width:30px; cursor:pointer; margin-left:-25px !important;}
	.ui-state-hover{background-color:transparent !important; border-color:unset !important;}
</style>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-pencil-square-o fa-fw "></i> 
				Admin
			<span>&gt; 
				Notifications
			</span>
		</h1>
	</div>
	
</div>


<section id="widget-grid" class="">

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken jarviswidget-sortable" id="wid-id-1" data-widget-editbutton="false" role="widget">
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
								<header role="heading" class="ui-sortable-handle">
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h2>List Notifications</h2>
								<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

								<!-- widget div-->
								<div role="content">

									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->

									</div>
									<!-- end widget edit box -->

									<!-- widget content -->
									<div class="widget-body no-padding">

										<div class="table-responsive">
												
											<table id="admin_notifications" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th></th>
														<th>ID</th>
														<th>Subject</th>
														<th>Text</th>
														<th>Status</th>
														<th>Tab</th>
														<th>Start Date</th>
														<th>End Date</th>
														<th>Created Date</th>
													</tr>
												</thead>
												
												
												<tbody>
													
												</tbody>
											</table>
											
										</div>
									</div>
									<!-- end widget content -->

								</div>
								<!-- end widget div -->

							</div>
							<!-- end widget -->						
						
</section>

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
				
				
				//---------------------datatable editor start--------------------------
				
				var neditor = new $.fn.dataTable.Editor( {
					ajax: "assets/ajax/notification-ajax.php",
					table: "#admin_notifications",
					fields: [ {
							label: "Subject:",
							name: "title"
						}, {
							label: "Text:",
							name: "description",
							type:  "textarea",
						}, {
							label: "Status:",
							name: "status",
							type:  "select",
							options: [
								{ label: "Active", value: "0" },
								{ label: "Inactive", value: "1" },
							]
						}, {
							label: "Tab:",
							name: "type",
							type:  "select",
							options: [
								{ label: "Change Log", value: "1" },
								{ label: "Announcements", value: "2" },
							]
						}, {
							label: "Start Date:",
							name: "start_date",
							//https://datatables.net/forums/discussion/24851
							//type: "datetime"
							type: "date",
							dateFormat: "yy-mm-dd",
						}, {
							label: "End Date:",
							name: "end_date",
							//type: "datetime"
							type: "date",
							dateFormat: "yy-mm-dd",
						}, 
					]
				} );
				
				//---------------------datatable list start--------------------------
				
				$('#admin_notifications').DataTable( {
					dom: "Bfrtip",
					
					'processing': true,
					'serverSide': true,
					"retrieve": true,
					'serverMethod': 'post',
					'ajax': {
					   'url':'assets/ajax/notification-ajax.php'
					},
					
					order: [[ 1, 'desc' ]],
					/*
					columnDefs: [ {
						orderable: false,
						className: 'select-checkbox',
						targets:   0
					} ],
					*/
					columns: [
					
						{
							data: null,
							defaultContent: '',
							className: 'select-checkbox',
							orderable: false,
							searchable: false,
						},
					
						{
						   data: 'id',
						   name: 'id',
						   visible: false 
						},
						{ data: "title" },
						{ data: "description" },
						//{ data: "status" },
						{
							data: "status",
							render: function (val, type, row) {
								return val == 0 ? "Active" : "Inactive";
							}
						},
						//{ data: "type" },
						{
							data: "type",
							render: function (val, type, row) {
								if (val == 1) {
									return "Change Log";
								} else if (val == 2) {
									return "Announcements";
								} else {
									return "";
								}
							}
						},
						{ data: "start_date" },
						{ data: "end_date" },
						{ data: "created_date" },
						
					],
					"searching": true,
					select: {
						style:    'os',
						selector: 'td:first-child'
					},
					buttons: [
						{ extend: "create", editor: neditor },
						{ extend: "edit",   editor: neditor },
						{ extend: "remove", editor: neditor }
					],
					
					//scrollX: true,
				} );
				
				
				
		} // end of page function 


//var neditor; // use a global for the submit and return data rendering in the examples
 
$(document).ready(function() {
	/*
    neditor = new $.fn.dataTable.Editor( {
        ajax: "../php/staff.php",
        table: "#admin_notifications",
        fields: [ {
                label: "First name:",
                name: "first_name"
            }, {
                label: "Last name:",
                name: "last_name"
            }, {
                label: "Position:",
                name: "position"
            }, {
                label: "Office:",
                name: "office"
            }, {
                label: "Extension:",
                name: "extn"
            }, {
                label: "Start date:",
                name: "start_date",
                type: "datetime"
            }, {
                label: "Salary:",
                name: "salary"
            }
        ]
    } );
 
    // Activate an inline edit on click of a table cell
    $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
        neditor.inline( this );
    } );
 
 */
 
    
} );



		loadScript("assets/plugins/datatables_ar/1.10.19/js/jquery.dataTables.min.js", function(){
		 loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
		  loadScript("assets/plugins/datatables_ar/jszip/3.1.3/jszip.min.js", function(){
		   loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/pdfmake.min.js", function(){
			loadScript("assets/plugins/datatables_ar/pdfmake/0.1.36/vfs_fonts.js", function(){
			 loadScript("assets/plugins/datatables_ar/buttons/1.4.2/js/buttons.print.js", function(){
			  loadScript("assets/plugins/datatables_ar/buttons/1.0.3/js/buttons.colVis.js", function(){
			   loadScript("assets/plugins/datatables_ar/buttons/1.5.2/js/buttons.html5.min.js", function(){
				 loadScript("assets/plugins/datatables_ar/select/1.3.1/js/dataTables.select.min.js", function(){
					loadScript("assets/plugins/datatables_ar/datetime/1.1.2/js/dataTables.dateTime.min.js", function(){
					 
					 
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
	    });
</script>