<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,123)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];

?>

<!--<link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css" />-->
<!--<link type="text/css" href="assets/css/ui.multiselect.css" rel="stylesheet" />-->

<link href="assets/css/plugins/select2/select2_smart_admin.min.css" rel="stylesheet" type="text/css" />

<!--
<link href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.css" rel="stylesheet" type="text/css" />
-->

<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />

<!--<link type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" rel="stylesheet" />-->
<!--<link type="text/css" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.css" rel="stylesheet" />-->

<!-- package with datatables,rowgroup,-->
<!--<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/plugin/datatables/rowgroup/datatables.rg.min.css" rel="stylesheet" type="text/css" />-->


<!-- package with jquery,datatables,rowgroup,-->
<!--<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/plugin/datatables/rowgroup/datatables.min.css" rel="stylesheet" type="text/css" />-->


<style>
	.select2-container {
		width: 100% !important;
	}
	
	.select_filters{height:32px; padding:6px 5px 5px 15px; border-bottom:1px solid #aaa;}
	/*#servicetype{width:300px; height:20px;}*/
	/*
	.select2{width:300px !important; /*height:20px;*/}
	.myinput{width:300px !important;}
	*/
	
	#far_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#far_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#far_datatable{border-bottom: 1px solid #ccc !important;}
	#far_datatable .isodrp{width:auto !important;}
	#far_datatable tr.dropdown select {
		font-weight: 400 !important;
	}
	
	.myrow{padding-left:15px; padding-bottom:15px; padding-top:0px !important;}
	.myfilter{padding-left:15px; float:left; padding-top:15px;}
</style>

<section id="widget-grid" class="">
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table"></i>
				Reports <span>> Exceptions</span>
		</h1>
		
	</div>
</div>
</section>





		<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="glyphicon glyphicon-filter"></i> </span>
						<h2>FILTERS </h2>
					</header>
					
					<div class="row" style="padding-left:15px; padding-bottom:15px;">
				
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						
						  <div class="row ">
						  
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Service Type</b></label>
								<br>					
								<select name="service_type" id="service_type" class="select2"  >
									<option>All</option>
									<option>Active</option>
									<option>Inactive</option>
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Create From</b></label>
								<br>
								<input id="entry_date_start" type="text" class="form-control myinput datepicker " />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Create To</b></label>
								<br>
								<input id="entry_date_end" type="text" class="form-control myinput datepicker" />
							</div>
							
						  </div>
						  
						  <div class="row ">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Vendor</b></label>
								<br>
								<select multiple name="vendor" id="vendor" class="select2">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Account #</b></label>
								<br>
								<select multiple name="account_no" id="account_no" class="select2">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Status</b></label>
								<br>
								<select name="account_status" id="account_status" class="select2">
									<option>All</option>
									<option>Active</option>
									<option>Inactive</option>
								</select>
							</div>
							
							
						
						  </div>
				
						<!--------------new row--------------------------->
				
						<div class="row ">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Exception Type</b></label>
								<br>
								<select name="account_status" id="account_status" class="select2">
								
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Priority</b></label>
								<br>
								<select name="account_status" id="account_status" class="select2">
								
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Invoice #</b></label>
								<br>
								<select multiple name="state" id="state" class="select2">
									
								</select>
							</div>
						
						</div>

						<!--------------new row--------------------------->
				
						<div class="row ">						
							
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Site</b></label>
								<br>
								<select multiple name="site" id="site" class="select2">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>State</b></label>
								<br>
								<select multiple name="state" id="state" class="select2">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Country</b></label>
								<br>
								<select multiple name="country" id="country" class="select2">
									
								</select>
							</div>
						
						</div>	
						
						<!--------------new row--------------------------->
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="" style="padding-left:15px; float:left">
								<button type="submit" class="btn-primary" id="create_query">Submit</button>

							</div>
						</div>

				
					</div>
				
				
				</div>
				<!-- end widget -->
		</section>

<!-- Query part -->

<br>

<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Exceptions Report Records </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->
							

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding" id="adhoc-datatable-load">							

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>



<!--<script src="https://code.jquery.com/jquery-3.7.1.js"></script>-->


<!--<script src="https://code.jquery.com/jquery-3.7.1.js"></script>-->
<script>
    //var $j = jQuery.noConflict();
    //alert($j.fn.jquery);
</script> 
<!-- package with datatables,rowgroup,-->
<!--<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/plugin/datatables/rowgroup/datatables.rg.min.js"></script>-->
	
<script type="text/javascript">

//var far_table;
// pagefunction
	var pagefunction = function() {
		
	}
	

    //var $jq_far = jQuery.noConflict();
    

	//$jq_far(document).ready(function(){
	$(document).ready(function(){
		
		$('.select2').select2({
			//placeholder: 'Select a month'
			//tags: true
		});		
		
	}); // ready end
		
		
		//loadScript("https://cdn.datatables.net/2.0.5/js/dataTables.js", function(){
		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
			
				//loadScript("https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.js", function(){
					//loadScript("https://cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.dataTables.js", function(){
						pagefunction();
					//});			
				//});	
				
		});
		//});
		
	</script>
	

