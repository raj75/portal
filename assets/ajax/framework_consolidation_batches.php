<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,121)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

$_SESSION["group_id"] = 2;

// get user company
$company_name = '';
$company_options = '';

$qry_co = $mysqli->query("select company_id,company_name from company order by company_name");
		if ($qry_co->num_rows > 0) {
			while($row_co=$qry_co->fetch_assoc()) {
				$company_id=$row_co['company_id'];
				$company_name=$row_co['company_name'];
				$selected = ($company_id==9)?'selected':'';
				$company_options .= "<option value='$company_id' $selected >$company_name</option>";
			}
		}

?>

<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet"
			href= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />

<style>
	.select2-container {
		width: 100% !important;
	}
	/*
	.select2{width:300px !important; /*height:20px;*/}
	.myinput{width:300px !important;}
	*/
	.select_filters{height:32px; padding:6px 5px 5px 15px; border-bottom:1px solid #aaa;}
	
	.ui-datepicker {top:195px !important; z-index:10 !important;}
	
	
	
	.ui-state-active .ui-icon, .ui-state-focus .ui-icon, .ui-state-hover .ui-icon {
		/*background-image:url(assets/img/jqueryui/ui-icons_222222_256x240.png) !important; */
	}
	
	.ui-datepicker .ui-datepicker-next span, .ui-datepicker .ui-datepicker-prev span {
		background:url(assets/img/jqueryui/ui-icons_454545_256x240.png) !important;
		background-image:url(assets/img/jqueryui/ui-icons_454545_256x240.png) !important;
	}
	
	.ui-datepicker .ui-datepicker-prev span{
		background-position: -80px -192px !important;
	}
	
	.ui-datepicker .ui-datepicker-next span{
		background-position: -48px -192px !important;
	}
	
	
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
	
	.select2group{height:32px !important; padding:6px 5px 5px 15px !important; border-bottom:1px solid #aaa;}
	
	.select2-container .select2-selection--single {height:40px; padding-top:5px;}
	
	.select2-container--default .select2-selection--single .select2-selection__arrow {top:5px;}
	
</style>

<section id="widget-grid" class="">
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table"></i>
				Reports <span>> Consolidation Batches</span>
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
					
					<form type="POST" name="fprocess-" id="fprocessForm-">
				
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
						  <div class="row myrow">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Entry Date Start</b></label>
								<br>
								<input id="entry_date_start" type="text" class="form-control myinput datepicker-- mydatepicker " value="<?php echo date('m/d/Y', strtotime(' - 1 month')); ?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Entry Date End</b></label>
								<br>
								<input id="entry_date_end" type="text" class="form-control myinput datepicker-- mydatepicker" value="<?php echo date("m/d/Y");?>"  autocomplete="off" />
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Service Type</b></label>
								<br>					
								<select name="service_type" id="service_type" class="select2-- myinput"  >
									<option>All</option>
									<option>Active</option>
									<option>Inactive</option>
								</select>
							</div>
						
						  </div>
				
						
				
						  <div class="row myrow">
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Site</b></label>
								<br>
								<select multiple name="site" id="site" class="select2-- myinput">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>State</b></label>
								<br>
								<select multiple name="state" id="state" class="select2--">
									
								</select>
							</div>
							
							<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left:15px; float:left">
								<label><b>Country</b></label>
								<br>
								<select multiple name="country" id="country" class="select2--">
									
								</select>
							</div>
							
							<?php if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) { ?>
								
								<div class="myfilter col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<label><b>Company</b></label>
									<br>
									<select multiple name="company" id="company" data-placeholder="Select Company" class="select2 select2height">
										<!--<option value="">Select Company</option>-->
										<?php echo $company_options; ?>
									</select>
								</div>
								
								<?php } ?>
							
						  </div>	
						
						<!--------------new row--------------------------->
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
							<br>
							<div class="" style="padding-left:15px; float:left">
								<button type="submit" class="btn-primary" id="create_query">Submit</button>

							</div>
						</div>

				
					</div>
					
					</form>
					
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
						<h2>Consolidation Batches Records </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->
							

						</div>
						<!-- end widget edit box -->

						
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>

<!-- package with datatables,rowgroup,-->
<!--<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/plugin/datatables/rowgroup/datatables.rg.min.js"></script>-->

<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"> 
	</script>
	
<script type="text/javascript">

	pageSetUp();

	//var far_table;
	// pagefunction
	
	var pagefunction = function() {		
		
	}
	

    //var $jq_far = jQuery.noConflict();
    

	//$jq_far(document).ready(function(){
	$(document).ready(function(){
		
		/*
		$('.select2').select2({
			//placeholder: 'Select a month'
			//tags: true
		});
		*/
		
		$("#service_type,#country,#state,#site").select2({
			 allowClear: true,
			 //placeholder: "Select Company",
			 placeholder: {
				id: "",
				placeholder: "Select Company"
			 },
			 
		 });
		
		// date picker
		 
		$('.mydatepicker').datepicker({  format: 'mm/dd/yyyy'});
		
		
		$(".mydatepicker").click(function(){
			
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
		});
		
		$(".mydatepicker").keyup(function(){
			
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
		});
		
		$(document).on('click','.ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon',function(){
			//alert('next');
			$(".ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon").text('');
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
	

