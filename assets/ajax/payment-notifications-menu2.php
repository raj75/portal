<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

//if(checkpermission($mysqli,133)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css" />
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

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

table.dataTable {
  width: 100% !important;
  table-layout: auto;
}

.dataTables_wrapper {
  overflow-x: auto;
}

table.dataTable tbody td.select-checkbox:before, table.dataTable tbody td.select-checkbox:after, table.dataTable tbody th.select-checkbox:before, table.dataTable tbody th.select-checkbox:after {
	top:unset !important;
}
.w-109{width:109px;}
.w-100{width:100%; }
.v-top{vertical-align:top; }
#edittable,#edittable th,#edittable td {
  border-collapse: collapse;
}
#edittable input{width: 90%; }
#edittable th{width:12%; }
#edittable .red{color:red; }
	</style>

	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
<style>
  #editForm .form-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  #editForm .form-row label {
    width: 20%;
    margin-right: 5px;
    font-weight: bold;
  }

  #editForm .form-row input,
  #editForm .form-row select {
    width: 25%;
    margin-right: 15px;
  }

  #editForm input[type="radio"] {
    width: auto;
  }
  .ui-dialog-title, .ui-dialog .ui-dialog-buttonpane {
    text-align: center !important; /* center align */
  }
  .ui-dialog .ui-dialog-buttonset {
    float: none !important;        /* remove float */
    display: inline-block;
  }
  .ui-dialog-title{width:100%; }
</style>

	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
	
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-pencil-square-o fa-fw "></i> 
					Admin
				<span>&gt; 
					Payment Notifications
				</span>
			</h1>
		</div>
		
	</div>
	
	<section id="widget-grid" class="sitestable">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Payment Notifications 2 </h2>
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
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter id" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter client_id" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter division" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter ubm_name" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter ubm_client_id" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter client_name" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter email_address" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter email_cc" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter s3_folder" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter s3_folder_csv" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter s3_folder_custom" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter custom_title" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter custom_content" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter multipage_email" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_invoice_images" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_custom_files" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_invoice_detail" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_invoice_credits" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_content" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_payment" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_gl_summary" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_1st_csv" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_alt_csv" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_email_csv" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_email_txt" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_prefix1" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_prefix2" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_prefix3" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_prefix4" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_prefix5" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_suffix1" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_suffix2" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_suffix3" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_suffix4" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter include_csv_suffix5" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter monday" /></th>	
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter tuesday" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter wednesday" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter thursday" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter friday" /></th>
<th class="hasinput"><input type="text" class="form-control" placeholder="Filter only_attachment" /></th>
									    <th class="w-109"></th>
									</tr>
									<tr>
										<th data-hide="phone,tablet">id </th>
										<th data-hide="phone,tablet">client_id </th>
										<th data-hide="phone,tablet">division </th>
										<th data-hide="phone,tablet">ubm_name </th>
										<th data-hide="phone,tablet">ubm_client_id </th>
										<th data-hide="phone,tablet">client_name </th>
										<th data-hide="phone,tablet">email_address </th>
										<th data-hide="phone,tablet">email_cc </th>
										<th data-hide="phone,tablet">s3_folder </th>
										<th data-hide="phone,tablet">s3_folder_csv </th>
										<th data-hide="phone,tablet">s3_folder_custom </th>
										<th data-hide="phone,tablet">custom_title </th>
										<th data-hide="phone,tablet">custom_content </th>
										<th data-hide="phone,tablet">multipage_email </th>
										<th data-hide="phone,tablet">include_invoice_images </th>
										<th data-hide="phone,tablet">include_custom_files </th>
										<th data-hide="phone,tablet">include_invoice_detail </th>
										<th data-hide="phone,tablet">include_invoice_credits </th>
										<th data-hide="phone,tablet">include_content </th>
										<th data-hide="phone,tablet">include_payment </th>
										<th data-hide="phone,tablet">include_gl_summary </th>
										<th data-hide="phone,tablet">include_1st_csv </th>
										<th data-hide="phone,tablet">include_alt_csv </th>
										<th data-hide="phone,tablet">include_email_csv </th>
										<th data-hide="phone,tablet">include_email_txt </th>
										<th data-hide="phone,tablet">include_csv_prefix1 </th>
										<th data-hide="phone,tablet">include_csv_prefix2 </th>
										<th data-hide="phone,tablet">include_csv_prefix3 </th>
										<th data-hide="phone,tablet">include_csv_prefix4 </th>
										<th data-hide="phone,tablet">include_csv_prefix5 </th>
										<th data-hide="phone,tablet">include_csv_suffix1 </th>
										<th data-hide="phone,tablet">include_csv_suffix2 </th>
										<th data-hide="phone,tablet">include_csv_suffix3 </th>
										<th data-hide="phone,tablet">include_csv_suffix4 </th>
										<th data-hide="phone,tablet">include_csv_suffix5 </th>
										<th data-hide="phone,tablet">monday </th>	
										<th data-hide="phone,tablet">tuesday </th>
										<th data-hide="phone,tablet">wednesday </th>
										<th data-hide="phone,tablet">thursday </th>
										<th data-hide="phone,tablet">friday </th>
										<th data-hide="phone,tablet">only_attachment </th>
										<th data-hide="phone,tablet">Action</th>
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

<!-- jQuery UI Modal -->
<div id="editDialog" title="Edit Entry" style="display:none;">
  <form id="editForm" autocomplete="off">
    <input type="hidden" id="editId">
<table class="w-100" id="edittable" style="black;border-spacing: 0 20px; border-collapse: separate;">
   <tr>
      <th>
         Client ID:<span class="red">*</span>
      </th>
      <td>
         <input type="number" min="1" id="client_id" autocomplete="off" required><input type="hidden" id="id" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>Client Name:<span class="red">*</span></th>
      <td>
         <input type="text" id="client_name" autocomplete="off" required>
      </td>
   </tr>
   <tr>
      <th>	
         Ubm ClientID:<span class="red">*</span>
      </th>
      <td>
         <input type="number" min="1" id="ubm_client_id" autocomplete="off" required>
      </td>
	  <td>&nbsp;</td>
      <th>Ubm Name:<span class="red">*</span></th>
      <td>
         <input type="text" id="ubm_name" autocomplete="off" required>
      </td>
   </tr>
   <tr>
      <th>      
         Division:
      </th>
      <td>
         <input type="text" id="division">
      </td>
	  <td>&nbsp;</td>
      <th>Only Attachment:<span class="red">*</span></th>
      <td>
		  <label><input type="radio" name="only_attachment" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="only_attachment" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>    
         S3 Folder:
      </th>
      <td>
         <input type="text" id="s3_folder" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>S3 Folder Csv:</th>
      <td>
         <input type="text" id="s3_folder_csv" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>    
         S3 Folder Custom:
      </th>
      <td>
         <input type="text" id="s3_folder_custom" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>custom_title:</th>
      <td>
         <input type="text" id="custom_title" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>   
         Custom Content:
      </th>
      <td colspan=5>
		 <textarea id="custom_content" rows="5" cols="178" autocomplete="off"></textarea>
      </td>
   </tr>
   <tr>
      <th>Multipage Email:</th>
      <td>
		  <label><input type="radio" name="multipage_email" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="multipage_email" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>  
         Include Invoice Images:
      </th>
      <td>
		  <label><input type="radio" name="include_invoice_images" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_invoice_images" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include Custom Files:</th>
      <td>
		  <label><input type="radio" name="include_custom_files" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_custom_files" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Invoice Detail:
      </th>
      <td>
		  <label><input type="radio" name="include_invoice_detail" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_invoice_detail" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include Invoice Credits:</th>
      <td>
		  <label><input type="radio" name="include_invoice_credits" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_invoice_credits" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Content:
      </th>
      <td>
		  <label><input type="radio" name="include_content" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_content" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include Payment:</th>
      <td>
		  <label><input type="radio" name="include_payment" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_payment" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include GL Summary:
      </th>
      <td>
		  <label><input type="radio" name="include_gl_summary" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_gl_summary" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include 1st Csv:</th>
      <td>
		  <label><input type="radio" name="include_1st_csv" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_1st_csv" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Alt Csv:
      </th>
      <td>
		  <label><input type="radio" name="include_alt_csv" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_alt_csv" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include Email Csv:</th>
      <td>
		  <label><input type="radio" name="include_email_csv" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_email_csv" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Email Txt:
      </th>
      <td>
		  <label><input type="radio" name="include_email_txt" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="include_email_txt" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>Include Csv Prefix1:</th>
      <td>
         <input type="text" id="include_csv_prefix1" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Csv Prefix2:
      </th>
      <td>
         <input type="text" id="include_csv_prefix2" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>Include Csv Prefix3:</th>
      <td>
         <input type="text" id="include_csv_prefix3" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Csv Prefix4:
      </th>
      <td>
         <input type="text" id="include_csv_prefix4" autocomplete="off">
      </td>
   </tr>
   <tr>
	  <th>Include Csv Prefix5:</th>
      <td>
         <input type="text" id="include_csv_prefix5" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Csv Suffix1:
      </th>
      <td>
         <input type="text" id="include_csv_suffix1" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>Include Csv Suffix2:</th>
      <td>
         <input type="text" id="include_csv_suffix2" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>   
         Include Csv Suffix3:
      </th>
      <td>
         <input type="text" id="include_csv_suffix3" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>Include Csv Suffix4:</th>
      <td>
         <input type="text" id="include_csv_suffix4" autocomplete="off">
      </td>
	  <td>&nbsp;</td>
      <th>    
         Include Csv Suffix5:
      </th>
      <td>
         <input type="text" id="include_csv_suffix5" autocomplete="off">
      </td>
   </tr>
   <tr>
      <th>Monday:<span class="red">*</span></th>
      <td>
		  <label><input type="radio" name="monday" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="monday" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>   
         Tuesday:<span class="red">*</span>
      </th>
      <td>
		  <label><input type="radio" name="tuesday" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="tuesday" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th> Wednesday:<span class="red">*</span></th>
      <td>
		  <label><input type="radio" name="wednesday" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="wednesday" value="0"> No</label>
      </td>
	  <td>&nbsp;</td>
      <th>  
         Thursday:<span class="red">*</span>
      </th>
      <td>
		  <label><input type="radio" name="thursday" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="thursday" value="0"> No</label>
      </td>
   </tr>
   <tr>
      <th>	
         Friday:<span class="red">*</span>
      </th>
      <td>
		  <label><input type="radio" name="friday" value="1"> Yes</label>&nbsp;&nbsp;
		  <label><input type="radio" name="friday" value="0"> No</label>
      </td>
	  <td colspan="3">&nbsp;</td>
   </tr>
   <tr><td colspan="4" style="height: 10px;"></td></tr>
	<tr>
	<th rowspan="5" class="v-top">Email Address:<span class="red">*</span><br><span style="
    font-size: smaller;
    font-weight: normal;
    font-style: italic;
">(minimum 1 email)</span></th>
	  <td rowspan="5">
	  <input type="email" name="email_address[]" id="email_address1" class="email_address" placeholder="Enter email" autocomplete="off" required><span class="red">*</span><br>
	  <input type="email" name="email_address[]" id="email_address2" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address3" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address4" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address5" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address6" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address7" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address8" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address9" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address10" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address11" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address12" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address13" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address14" class="email_address" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_address[]" id="email_address15" class="email_address" placeholder="Enter email" autocomplete="off"><br>
		</td>
		<td  rowspan="5">&nbsp;</td>
	<th rowspan="5" class="v-top">Email CC:</th>
	 <td rowspan="5">
	  <input type="email" name="email_cc[]" id="email_cc1" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc2" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc3" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc4" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc5" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc6" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc7" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc8" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc9" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc10" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc11" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc12" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc13" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc14" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  <input type="email" name="email_cc[]" id="email_cc15" class="email_cc" placeholder="Enter email" autocomplete="off"><br>
	  </td>
	  </tr>
	</table>
  </form>
</div>


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



				
<?php if(1==2){ ?>
				
				var editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/payment-notifications-save2.php',
					table: '#ei_datatable_fixed_column',

					idSrc:  'id',

					fields: [

/*						{
							"label": "Client:",
							"name": "client"
						},
						{
							"label": "Inbox",
							"name": "inbox",
						},

						{
							"label": "Schedule",
							"name": "schedule",
						},
						{
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
{"label": "client_id:","name": "client_id"},
{"label": "division:","name": "division"},
{"label": "ubm_name:","name": "ubm_name"},
{"label": "ubm_client_id:","name": "ubm_client_id"},
{"label": "client_name:","name": "client_name"},
{"label": "email_address:","name": "email_address"},
{"label": "email_cc:","name": "email_cc"},
{"label": "s3_folder:","name": "s3_folder"},
{"label": "s3_folder_csv:","name": "s3_folder_csv"},
{"label": "s3_folder_custom:","name": "s3_folder_custom"},
{"label": "custom_title:","name": "custom_title"},
{"label": "custom_content:","name": "custom_content"},
{"label": "multipage_email:","name": "multipage_email"},
{"label": "include_invoice_images:","name": "include_invoice_images"},
{"label": "include_custom_files:","name": "include_custom_files"},
{"label": "include_invoice_detail:","name": "include_invoice_detail"},
{"label": "include_invoice_credits:","name": "include_invoice_credits"},
{"label": "include_content:","name": "include_content"},
{"label": "include_payment:","name": "include_payment"},
{"label": "include_gl_summary:","name": "include_gl_summary"},
{"label": "include_1st_csv:","name": "include_1st_csv"},
{"label": "include_alt_csv:","name": "include_alt_csv"},
{"label": "include_email_csv:","name": "include_email_csv"},
{"label": "include_email_txt:","name": "include_email_txt"},
{"label": "include_csv_prefix1:","name": "include_csv_prefix1"},
{"label": "include_csv_prefix2:","name": "include_csv_prefix2"},
{"label": "include_csv_prefix3:","name": "include_csv_prefix3"},
{"label": "include_csv_prefix4:","name": "include_csv_prefix4"},
{"label": "include_csv_prefix5:","name": "include_csv_prefix5"},
{"label": "include_csv_suffix1:","name": "include_csv_suffix1"},
{"label": "include_csv_suffix2:","name": "include_csv_suffix2"},
{"label": "include_csv_suffix3:","name": "include_csv_suffix3"},
{"label": "include_csv_suffix4:","name": "include_csv_suffix4"},
{"label": "include_csv_suffix5:","name": "include_csv_suffix5"},
{"label": "monday:","name": "monday"},	
{"label": "tuesday:","name": "tuesday"},
{"label": "wednesday:","name": "wednesday"},
{"label": "thursday:","name": "thursday"},
{"label": "friday:","name": "friday"},
{"label": "only_attachment:","name": "only_attachment"},
						



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

<?php } ?>



			/* COLUMN FILTER  */
				var otable = $("#ei_datatable_fixed_column").DataTable( {

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					/*'responsive': true,*/
					//'scrollX': true,
					//processing': true,
					'ajax': {
					   'url':'/assets/ajax/payment-notification-ajax22.php'
					},
					
					 "drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},					
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					}, 


					'columns': [
						{ data: 'id' },
						{ data: 'client_id' },
						{ data: 'division' },
						{ data: 'ubm_name' },
						{ data: 'ubm_client_id' },
						{ data: 'client_name' },
						{ data: 'email_address' },
						{ data: 'email_cc' },
						{ data: 's3_folder' },
						{ data: 's3_folder_csv' },
						{ data: 's3_folder_custom' },
						{ data: 'custom_title' },
						{ data: 'custom_content' },
						{ data: 'multipage_email' },
						{ data: 'include_invoice_images' },
						{ data: 'include_custom_files' },
						{ data: 'include_invoice_detail' },
						{ data: 'include_invoice_credits' },
						{ data: 'include_content' },
						{ data: 'include_payment' },
						{ data: 'include_gl_summary' },
						{ data: 'include_1st_csv' },
						{ data: 'include_alt_csv' },
						{ data: 'include_email_csv' },
						{ data: 'include_email_txt' },
						{ data: 'include_csv_prefix1' },
						{ data: 'include_csv_prefix2' },
						{ data: 'include_csv_prefix3' },
						{ data: 'include_csv_prefix4' },
						{ data: 'include_csv_prefix5' },
						{ data: 'include_csv_suffix1' },
						{ data: 'include_csv_suffix2' },
						{ data: 'include_csv_suffix3' },
						{ data: 'include_csv_suffix4' },
						{ data: 'include_csv_suffix5' },
						{ data: 'monday' },	
						{ data: 'tuesday' },
						{ data: 'wednesday' },
						{ data: 'thursday' },
						{ data: 'friday' },
						{ data: 'only_attachment' },
						  {
							defaultContent: '',
							data: null,
							orderable: false,
							searchable: false,
							render: function (data, type, row) {
							  return `
								<button class="editBtn" style="width:53px;margin-right:3px;" 
            data-id="${row.id}"
            data-client_id="${row.client_id}"
            data-division="${row.division}"
            data-ubm_name="${row.ubm_name}"
            data-ubm_client_id="${row.ubm_client_id}"
            data-client_name="${row.client_name}"
            data-email_address="${row.email_address}"
            data-email_cc="${row.email_cc}"
            data-s3_folder="${row.s3_folder}"
            data-s3_folder_csv="${row.s3_folder_csv}"
            data-s3_folder_custom="${row.s3_folder_custom}"
            data-custom_title="${row.custom_title}"
            data-custom_content="${row.custom_content}"
            data-multipage_email="${row.multipage_email}"
            data-include_invoice_images="${row.include_invoice_images}"
            data-include_custom_files="${row.include_custom_files}"
            data-include_invoice_detail="${row.include_invoice_detail}"
            data-include_invoice_credits="${row.include_invoice_credits}"
            data-include_content="${row.include_content}"
            data-include_payment="${row.include_payment}"
            data-include_gl_summary="${row.include_gl_summary}"
            data-include_1st_csv="${row.include_1st_csv}"
            data-include_alt_csv="${row.include_alt_csv}"
            data-include_email_csv="${row.include_email_csv}"
            data-include_email_txt="${row.include_email_txt}"
            data-include_csv_prefix1="${row.include_csv_prefix1}"
            data-include_csv_prefix2="${row.include_csv_prefix2}"
            data-include_csv_prefix3="${row.include_csv_prefix3}"
            data-include_csv_prefix4="${row.include_csv_prefix4}"
            data-include_csv_prefix5="${row.include_csv_prefix5}"
            data-include_csv_suffix1="${row.include_csv_suffix1}"
            data-include_csv_suffix2="${row.include_csv_suffix2}"
            data-include_csv_suffix3="${row.include_csv_suffix3}"
            data-include_csv_suffix4="${row.include_csv_suffix4}"
            data-include_csv_suffix5="${row.include_csv_suffix5}"
            data-monday="${row.monday}"
            data-tuesday="${row.tuesday}"
            data-wednesday="${row.wednesday}"
            data-thursday="${row.thursday}"
            data-friday="${row.friday}"
            data-only_attachment="${row.only_attachment}"
          >Edit</button>
								<button class="btn-danger deleteBtn"  style="width:53px;" data-id="${row.id}">Delete</button>
							  `;
							}
						  }
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
						

					],
					columnDefs: [
						{
						  targets: [0,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34],   // Column indexes to hide (0 = first column)
						  visible: false,
						}
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

			/*otable.on('click', '.editBtn', function () {
			//const id = $(this).data('id');
   // $('#editId').val($(this).data('id'));
    //$('#editName').val($(this).data('name'));
    //$('#editEmail').val($(this).data('email'));
    //new bootstrap.Modal(document.getElementById('editModal')).show();
			});

			otable.on('click', '.deleteBtn', function () {
				const id = $(this).data('id');
				if (confirm('Are you sure you want to delete this entry?')) {
				  $.ajax({
					url: '/delete-entry.php',
					method: 'POST',
					contentType: 'application/json',
					data: JSON.stringify({ id }),
					success: function () {
					  table.ajax.reload(null, false);
					},
					error: function () {
					  alert('Delete failed.');
					}
				  });
				}
			});*/

		  // 💾 Submit Edit Form
		 /* $('#editForm').on('submit', function (e) {
			e.preventDefault();
			const id = $('#editId').val();
			const name = $('#editName').val();
			const email = $('#editEmail').val();

			$.ajax({
			  url: '/update-entry.php',
			  method: 'POST',
			  contentType: 'application/json',
			  data: JSON.stringify({ id, name, email }),
			  success: function () {
				bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
				table.ajax.reload(null, false);
			  },
			  error: function () {
				alert('Failed to update.');
			  }
			});
		  });*/
		  
		  
		  
		  
		  
		  // jQuery UI Dialog setup
		  $("#editDialog").dialog({
			autoOpen: false,
			modal: true,
			width: '70%',
			buttons: {
			  "Save": function () {
				  var dthis=this;
			var rid = $('#id').val();
			var client_id = $('#client_id').val();
			var client_name = $('#client_name').val();
			var ubm_client_id = $('#ubm_client_id').val();
			var ubm_name = $('#ubm_name').val();
			var division = $('#division').val();
			var only_attachment = $('input[name="only_attachment"]:checked').val();
			var s3_folder = $('#s3_folder').val();
			var s3_folder_csv = $('#s3_folder_csv').val();
			var s3_folder_custom = $('#s3_folder_custom').val();
			var custom_title = $('#custom_title').val();
			var custom_content = $('#custom_content').val();
			var multipage_email = $('input[name="multipage_email"]:checked').val();
			var include_invoice_images = $('input[name="include_invoice_images"]:checked').val();
			var include_custom_files = $('input[name="include_custom_files"]:checked').val();
			var include_invoice_detail = $('input[name="include_invoice_detail"]:checked').val();
			var include_invoice_credits = $('input[name="include_invoice_credits"]:checked').val();
			var include_content = $('input[name="include_content"]:checked').val();
			var include_payment = $('input[name="include_payment"]:checked').val();
			var include_gl_summary = $('input[name="include_gl_summary"]:checked').val();
			var include_1st_csv = $('input[name="include_1st_csv"]:checked').val();
			var include_alt_csv = $('input[name="include_alt_csv"]:checked').val();
			var include_email_csv = $('input[name="include_email_csv"]:checked').val();
			var include_email_txt = $('input[name="include_email_txt"]:checked').val();
			var include_csv_prefix1 = $('#include_csv_prefix1').val();
			var include_csv_prefix2 = $('#include_csv_prefix2').val();
			var include_csv_prefix3 = $('#include_csv_prefix3').val();
			var include_csv_prefix4 = $('#include_csv_prefix4').val();
			var include_csv_prefix5 = $('#include_csv_prefix5').val();
			var include_csv_suffix1 = $('#include_csv_suffix1').val();
			var include_csv_suffix2 = $('#include_csv_suffix2').val();
			var include_csv_suffix3 = $('#include_csv_suffix3').val();
			var include_csv_suffix4 = $('#include_csv_suffix4').val();
			var include_csv_suffix5 = $('#include_csv_suffix5').val();
			var monday = $('input[name="monday"]:checked').val();
			var tuesday = $('input[name="tuesday"]:checked').val();
			var wednesday = $('input[name="wednesday"]:checked').val();
			var thursday = $('input[name="thursday"]:checked').val();
			var friday = $('input[name="friday"]:checked').val();
			var edit = 1;

			var checkemail=0;
			var checkccemail=0;
			var checkclientid=0;
			var checkclientname=0;
			var checkubmclientid=0;
			var checkubmname=0;
			var tid=0;
			var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	
			var isValid = /^\d+$/.test(client_id) && parseInt(client_id) > 0;
			if (isValid) {
				$("#client_id").css('border', ''); // valid
				checkclientid=1;
			} else {
				$("#client_id").css('border', '2px solid red'); // invalid
			}
			
			
			var isValid = /^\d+$/.test(ubm_client_id) && parseInt(ubm_client_id) > 0;
			if (isValid) {
				$("#ubm_client_id").css('border', ''); // valid
				checkubmclientid=1;
			} else {
				$("#ubm_client_id").css('border', '2px solid red'); // invalid
			}			
			
			if(client_name.trim()==''){
				$("#client_name").css('border', '2px solid red');
			}else{
				$("#client_name").css('border', '');
				checkclientname=1;
			}

			if(ubm_name.trim()==''){
				$("#ubm_name").css('border', '2px solid red');
			}else{
				$("#ubm_name").css('border', '');
				checkubmname=1;
			}

			var validEmails = $('.email_address').map(function() {
				var tthis=this;
				$(tthis).css('border', '');
				var val = $(tthis).val().trim();
				if(val == ''){ return null; }
				if(!emailPattern.test(val)){checkemail=2; $(tthis).css('border', '2px solid red'); return null; }
				return val;
				//return val !== '' && emailPattern.test(val) ? val : null;
			}).get();
			
			var uniqueValues = [...new Set(validEmails)];
			email_address ='';
			if(uniqueValues.length == 0){
				$("#email_address1").css('border', '2px solid red');
				alert("Atleast 1 correct emailadress required!");
			}else if(checkemail==0){
				var email_address = uniqueValues.join(';');
				checkemail=1;
			}
			
			
			var validEmailscc = $('.email_cc').map(function() {
				var ttthis=this;
				$(ttthis).css('border', '');
				var valcc = $(ttthis).val().trim();
				if(valcc == ''){ return null; }
				if(!emailPattern.test(valcc)){checkccemail=2;  $(ttthis).css('border', '2px solid red'); return null; }
				return valcc;
				//return val !== '' && emailPattern.test(val) ? val : null;
			}).get();
			
			var uniqueValuescc = [...new Set(validEmailscc)];
			email_cc ='';
			if(uniqueValuescc.length != 0){
				var email_cc = uniqueValuescc.join(';');
			}


			
			if(rid == '' || rid < 1){
				alert("Error occured please try after sometimes!");
				tid=0;
			}else tid=1;
			
			//alert(uniqueValues);
			if(tid==1 && checkemail==1 && checkccemail != 2 && checkclientid == 1  && checkclientname == 1  && checkubmclientid == 1  && checkubmname == 1 ){
				$.ajax({
				  url: '/assets/includes/payment-notification-save22.php',
				  method: 'POST',
				  contentType: 'application/json',
				  data: JSON.stringify({ rid, client_id, client_name , ubm_client_id , ubm_name , division , only_attachment , s3_folder , s3_folder_csv , s3_folder_custom , custom_title , custom_content , multipage_email , include_invoice_images, include_custom_files , include_invoice_detail , include_invoice_credits , include_content , include_payment , include_gl_summary , include_1st_csv , include_alt_csv , include_email_csv , include_email_txt, include_csv_prefix1 , include_csv_prefix2 , include_csv_prefix3 , include_csv_prefix4 , include_csv_prefix5 , include_csv_suffix1 , include_csv_suffix2, include_csv_suffix3 , include_csv_suffix4 , include_csv_suffix5 , monday , tuesday , wednesday , thursday , friday,email_address,email_cc, edit }),
				  success: function (data) {
					if(data==false){
						
					}else{
						var results = JSON.parse(data);
						if(results.error==""){
							alert("Updated.");
							$("#editDialog").dialog("close");
							otable.ajax.reload(null, false);
						}else if(results.error != ""){//alert(data);
							alert(results.error);						
						}else{
							alert("Error occured please try after sometimes!");
						}
					}
				  },
				  error: function () {
					alert("Update failed.");
				  }
				});
			}else{
				alert("Please check all fields!");
				
			}


			  },
			  Cancel: function () {
				$(this).dialog("close");
			  }
			}
		  });

  // Edit button click
  otable.on('click', '.editBtn', function () {
	othis=$(this);//alert(othis.data('client_id'));
    $('#editId').val(othis.data('id'));
    $('#id').val(othis.data('id'));
    $('#client_id').val(othis.data('client_id'));
	$('#division').val(othis.data('division'));
    $('#ubm_name').val(othis.data('ubm_name'));
    $('#ubm_client_id').val(othis.data('ubm_client_id'));
    $('#client_name').val(othis.data('client_name'));
    //$('#email_address').val(othis.data('email_address'));
	if (othis.data('email_address') && othis.data('email_address').trim() !== '') {
	const emails = othis.data('email_address').split(';')
		.map(e => e.trim())
		.filter(e => e !== '');

	  //$('#emailList').empty();
	  let i = 1;
	  emails.forEach(function (email) {
		//$('#emailList').append('<li>' + email + '</li>');
		$('#email_address'+i).val(email);
		i++;
	  });
	}
    //$('#email_cc').val(othis.data('email_cc'));
	if (othis.data('email_cc') && othis.data('email_cc').trim() !== '') {
		const emailscc = othis.data('email_cc').split(';')
		.map(e => e.trim())
		.filter(e => e !== '');

	  //$('#emailList').empty();
	  i = 1;
	  emailscc.forEach(function (email) {
		//$('#emailList').append('<li>' + email + '</li>');
		$('#email_cc'+i).val(email);
		i++;
	  });
	}
    $('#s3_folder').val(othis.data('s3_folder'));
    $('#s3_folder_csv').val(othis.data('s3_folder_csv'));
    $('#s3_folder_custom').val(othis.data('s3_folder_custom'));
    $('#custom_title').val(othis.data('custom_title'));
    $('#custom_content').val(othis.data('custom_content'));
    //$('#multipage_email').val(othis.data('multipage_email'));
	if(othis.data('multipage_email')==1){ $('input[name="multipage_email"][value="1"]').prop('checked', true); }else{ $('input[name="multipage_email"][value="0"]').prop('checked', true); }
    //$('#include_invoice_images').val(othis.data('include_invoice_images'));
	if(othis.data('include_invoice_images')==1){ $('input[name="include_invoice_images"][value="1"]').prop('checked', true); }else{ $('input[name="include_invoice_images"][value="0"]').prop('checked', true); }
	
    //$('#include_custom_files').val(othis.data('include_custom_files'));
	if(othis.data('include_custom_files')==1){ $('input[name="include_custom_files"][value="1"]').prop('checked', true); }else{ $('input[name="include_custom_files"][value="0"]').prop('checked', true); }
    //$('#include_invoice_detail').val(othis.data('include_invoice_detail'));
	if(othis.data('include_invoice_detail')==1){ $('input[name="include_invoice_detail"][value="1"]').prop('checked', true); }else{ $('input[name="include_invoice_detail"][value="0"]').prop('checked', true); }
    //$('#include_content').val(othis.data('include_content'));
	if(othis.data('include_content')==1){ $('input[name="include_content"][value="1"]').prop('checked', true); }else{ $('input[name="include_content"][value="0"]').prop('checked', true); }
   // $('#include_payment').val(othis.data('include_payment'));
	if(othis.data('include_payment')==1){ $('input[name="include_payment"][value="1"]').prop('checked', true); }else{ $('input[name="include_payment"][value="0"]').prop('checked', true); }
   // $('#include_gl_summary').val(othis.data('include_gl_summary'));
	if(othis.data('include_gl_summary')==1){ $('input[name="include_gl_summary"][value="1"]').prop('checked', true); }else{ $('input[name="include_gl_summary"][value="0"]').prop('checked', true); }
    //$('#include_1st_csv').val(othis.data('include_1st_csv'));
	if(othis.data('include_1st_csv')==1){ $('input[name="include_1st_csv"][value="1"]').prop('checked', true); }else{ $('input[name="include_1st_csv"][value="0"]').prop('checked', true); }
   // $('#include_alt_csv').val(othis.data('include_alt_csv'));
	if(othis.data('include_alt_csv')==1){ $('input[name="include_alt_csv"][value="1"]').prop('checked', true); }else{ $('input[name="include_alt_csv"][value="0"]').prop('checked', true); }
    //$('#include_email_csv').val(othis.data('include_email_csv'));
	if(othis.data('include_email_csv')==1){ $('input[name="include_email_csv"][value="1"]').prop('checked', true); }else{ $('input[name="include_email_csv"][value="0"]').prop('checked', true); }
    //$('#include_email_txt').val(othis.data('include_email_txt'));
	if(othis.data('include_email_txt')==1){ $('input[name="include_email_txt"][value="1"]').prop('checked', true); }else{ $('input[name="include_email_txt"][value="0"]').prop('checked', true); }
    $('#include_csv_prefix1').val(othis.data('include_csv_prefix1'));
    $('#include_csv_prefix2').val(othis.data('include_csv_prefix2'));
    $('#include_csv_prefix3').val(othis.data('include_csv_prefix3'));
    $('#include_csv_prefix4').val(othis.data('include_csv_prefix4'));
    $('#include_csv_prefix5').val(othis.data('include_csv_prefix5'));
    $('#include_csv_suffix1').val(othis.data('include_csv_suffix1'));
    $('#include_csv_suffix2').val(othis.data('include_csv_suffix2'));
    $('#include_csv_suffix3').val(othis.data('include_csv_suffix3'));
    $('#include_csv_suffix4').val(othis.data('include_csv_suffix4'));
    $('#include_csv_suffix5').val(othis.data('include_csv_suffix5'));
   // $('#monday').val(othis.data('monday'));
    //$('#tuesday').val(othis.data('tuesday'));
    //$('#wednesday').val(othis.data('wednesday'));
    //$('#thursday').val(othis.data('thursday'));
    //$('#friday').val(othis.data('friday'));
	if(othis.data('monday')==1){ $('input[name="monday"][value="1"]').prop('checked', true); }else{ $('input[name="monday"][value="0"]').prop('checked', true); }
	if(othis.data('tuesday')==1){ $('input[name="tuesday"][value="1"]').prop('checked', true); }else{ $('input[name="tuesday"][value="0"]').prop('checked', true); }
	if(othis.data('wednesday')==1){ $('input[name="wednesday"][value="1"]').prop('checked', true); }else{ $('input[name="wednesday"][value="0"]').prop('checked', true); }
	if(othis.data('thursday')==1){ $('input[name="thursday"][value="1"]').prop('checked', true); }else{ $('input[name="thursday"][value="0"]').prop('checked', true); }
	if(othis.data('friday')==1){ $('input[name="friday"][value="1"]').prop('checked', true); }else{ $('input[name="friday"][value="0"]').prop('checked', true); }
    //$('#only_attachment').val(othis.data('only_attachment'));
	if(othis.data('only_attachment')==1){ $('input[name="only_attachment"][value="1"]').prop('checked', true); }else{ $('input[name="only_attachment"][value="0"]').prop('checked', true); }
     $('#editDialog').dialog('open');
  });

  // Delete button click
  otable.on('click', '.deleteBtn', function () {
    var rid = $(this).data('id');
    var action = 'delete';
    if (confirm("Delete this entry?")) {
      $.ajax({
        url: '/assets/includes/payment-notification-save22.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ rid, action }),
        success: function (data) {
			if(data==false){
				
			}else{
				var results = JSON.parse(data);
				if(results.error==""){
					alert("Deleted.");
					otable.ajax.reload(null, false);
				}else if(results.error != ""){//alert(data);
					alert(results.error);						
				}else{
					alert("Error occured please try after sometimes!");
				}
			}
        },
        error: function () {
          alert("Delete failed.");
        }
      });
    }
  });		  
		  
		  $('#edit123Form').on('submit', function (e) {
			e.preventDefault();
			var client_id = $('#client_id').val();
			var client_name = $('#client_name').val();
			var ubm_client_id = $('#ubm_client_id').val();
			var ubm_name = $('#ubm_name').val();
			var division = $('#division').val();
			var only_attachment = $('input[name="only_attachment"]:checked').val();
			var s3_folder = $('#s3_folder').val();
			var s3_folder_csv = $('#s3_folder_csv').val();
			var s3_folder_custom = $('#s3_folder_custom').val();
			var custom_title = $('#custom_title').val();
			var custom_content = $('#custom_content').val();
			var multipage_email = $('input[name="multipage_email"]:checked').val();
			var include_invoice_images = $('input[name="include_invoice_images"]:checked').val();
			var include_custom_files = $('input[name="include_custom_files"]:checked').val();
			var include_invoice_detail = $('input[name="include_invoice_detail"]:checked').val();
			var include_invoice_credits = $('input[name="include_invoice_credits"]:checked').val();
			var include_content = $('input[name="include_content"]:checked').val();
			var include_payment = $('input[name="include_payment"]:checked').val();
			var include_gl_summary = $('input[name="include_gl_summary"]:checked').val();
			var include_1st_csv = $('input[name="include_1st_csv"]:checked').val();
			var include_alt_csv = $('input[name="include_alt_csv"]:checked').val();
			var include_email_csv = $('input[name="include_email_csv"]:checked').val();
			var include_csv_prefix1 = $('#include_csv_prefix1').val();
			var include_csv_prefix2 = $('#include_csv_prefix2').val();
			var include_csv_prefix3 = $('#include_csv_prefix3').val();
			var include_csv_prefix4 = $('#include_csv_prefix4').val();
			var include_csv_prefix5 = $('#include_csv_prefix5').val();
			var include_csv_suffix1 = $('#include_csv_suffix1').val();
			var include_csv_suffix2 = $('#include_csv_suffix2').val();
			var include_csv_suffix3 = $('#include_csv_suffix3').val();
			var include_csv_suffix4 = $('#include_csv_suffix4').val();
			var include_csv_suffix5 = $('#include_csv_suffix5').val();
			var monday = $('input[name="monday"]:checked').val();
			var tuesday = $('input[name="tuesday"]:checked').val();
			var wednesday = $('input[name="wednesday"]:checked').val();
			var thursday = $('input[name="thursday"]:checked').val();
			var friday = $('input[name="friday"]:checked').val();
			var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

			var validEmails = $('.email_address').map(function() {
				var val = $(this).val().trim();
				return val !== '' && emailPattern.test(val) ? val : null;
			}).get();
			alert(validEmails);
<?php if(1==2){ ?>
			$.ajax({
			  url: '/update-entry.php',
			  method: 'POST',
			  contentType: 'application/json',
			  data: JSON.stringify({ id, name, email }),
			  success: function () {
				bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
				table.ajax.reload(null, false);
			  },
			  error: function () {
				alert('Failed to update.');
			  }
			});
<?php } ?>
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
		 //loadScript("assets/js/dataTables.editor.min.js", function(){
			pagefunction();
	     //});
		 });



	</script>