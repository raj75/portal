<?php
//error_reporting(E_ALL);
require_once("inc/init.php");
require_once '../includes/db_connect.php'; 
require_once '../includes/functions.php'; 
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];
$chart_id = (isset($_GET['chart_id']) and (int)$_GET['chart_id'] > 0)?(int)$_GET['chart_id']:"";
$form_settings = "";

function getsavechart () {
	global $mysqli;
	global $form_settings;
	$chart_id = (int) $_GET['chart_id'];
	$sql = "SELECT * from amcharts where Id = $chart_id";
	$result = $mysqli->query($sql);
				
	$result_rs = $result->fetch_array(MYSQLI_ASSOC);
	
	$_SESSION['db_chart'] = $result_rs;
	$form_settings = unserialize(base64_decode($result_rs['form_settings'])); 
	 
	$_GET['gid'] = $form_settings['chart_gid'];
	$_GET['cid'] = $form_settings['chart_cid'];
}

//$db_chart = "";

if ( isset($_GET['chart_id']) and $_GET['chart_id'] > 0 ) {
	getsavechart();
} else {
	unset($_SESSION['db_chart']);
}

print_r($form_settings);

$chart_gid = (isset($_GET['gid']))?$_GET['gid']:"";
$chart_cid = (isset($_GET['cid']))?$_GET['cid']:"";

//--------set session for am_chart.php------------------
$_SESSION['popup'] = [];
function popup_chart_settings () {
	$_SESSION['popup']['ds1'] = "column";
	$_SESSION['popup']['ds2'] = "column";
	$_SESSION['popup']['ds3'] = "column";
	
	if (isset($_GET['gid']) and isset($_GET['cid'])) {
		if ($_GET['gid']=='col' and $_GET['cid']==9) {
			$_SESSION['popup']['ds2'] = "line";
			$_SESSION['popup']['dstype2'] = "round";
		} else if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
			//$chart_type = "column";
		} else if ($_GET['gid']=='line') {
			$_SESSION['popup']['ds1'] = "line";
			$_SESSION['popup']['ds2'] = "line";
			$_SESSION['popup']['ds3'] = "line";
			
			$_SESSION['popup']['dstype1'] = "round";
			$_SESSION['popup']['dstype2'] = "square";
			$_SESSION['popup']['dstype3'] = "square";
			
		} else if ($_GET['gid']=='area') {
			$_SESSION['popup']['ds1'] = "area";
			$_SESSION['popup']['ds2'] = "area";
			$_SESSION['popup']['ds3'] = "area";
			
		}
	}
	//$_SESSION['popup']['chart_type'] = 
	
}

// call settings
////popup_chart_settings();

//fillAlphas
function js_dsf_transparency($ds) {
		
		if ($ds==2 and $_SESSION['popup']['ds2'] == "line") {
			return 100;
		}
		if ( ($ds==1 or $ds==2 or $ds==3) and $_GET['gid']=='area') {
			return 70;
		}
		return 0;																   
}

function js_ds_lthickness($ds) {
		
		if ($ds==2 and $_SESSION['popup']['ds2'] == "line") {
			return 2;
		}
		return 1;																   
}

//print_r($_GET);
	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="/assets/css/bootstrap-colorpicker.css">
	<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.css" rel="stylesheet">-->
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>-->
	
	<style>
		
		.charting,.charting > div{width: 100% !important;overflow: auto !important;}
		.charting a:hover,
		.charting a:focus{
			text-decoration: none;
			outline: none;
		}
		.charting .vertical-tab{
			font-family: 'Poppins', sans-serif;
			/*display: table;*/
			display: flex;
			border-radius: 0;
		}
		.charting .vertical-tab .nav-tabs{
			/*display: table-cell;*/
			width: 25%;
			min-width: 25%;
			border: none;
			float:left;
		}
		.charting .vertical-tab .nav-tabs li{ float: none; }
		.charting .vertical-tab .nav-tabs li a{
			/*color: #333;*/
			color: #c7c7d1;
			background-color: transparent;
			font-size: 14px;
			font-weight: 700;
			line-height: 18px;
			text-transform: capitalize;
			/*text-align: center;*/
			padding: 15px 15px;
			margin: 0;
			border-radius: 0;
			border: none;
			position: relative;
			z-index: 1;
			transition: all 0.3s ease 0s;
		}
		.charting .vertical-tab .nav-tabs li a:hover,
		.charting .vertical-tab .nav-tabs li.active a,
		.charting .vertical-tab .nav-tabs li.active a:hover{
			color: #000;
			background-color: #fff;
			border: none;
		}
		.charting .vertical-tab .nav-tabs li a:before,
		.charting .vertical-tab .nav-tabs li a:after{
			content: '';
			background-color: #535364;
			height: 100%;
			width: 100%;
			position: absolute;
			left: 0;
			bottom: 0;
			z-index: -1;
			transition: all 0.3s ease 0s;
		}
		.charting .vertical-tab .nav-tabs li a:after{
			background-color: #F35349;
			width: 4px;
			transform: scaleY(0);
		}
		.vertical-tab .nav-tabs li a:hover:before,
		.charting .vertical-tab .nav-tabs li.active a:before{
			transform: scaleY(0);
		}
		.charting .vertical-tab .nav-tabs li a:hover:after,
		.charting .vertical-tab .nav-tabs li.active a:after{
			transform: scaleY(1);
		}
		.charting .vertical-tab .nav-tabs li a i{
			font-size: 14px;
			margin: 0 0 5px;
			/*display: block;*/
			transition: all 0.3s ease 0s;
		}
		.charting .vertical-tab .nav-tabs li a:hover i,
		.charting .vertical-tab .nav-tabs li.active a i{
			color: #F35349;
		}
		.charting .vertical-tab .tab-content{ 
			color: #00639e;
			font-size: 14px;
			letter-spacing: 0.5px;
			line-height: 23px;
			padding: 15px 15px 10px;
			margin-top: 0px;
			padding-top: 0px;
			/*display: table-cell;*/
			float:left;
			width:100%;
		}
		.charting .vertical-tab .tab-content h3{
			font-size: 20px;
			font-weight: 600;
			text-transform: capitalize;
			margin: 0 0 4px;
		}
		@media only screen and (max-width: 479px){
			.charting .vertical-tab{
				padding: 0;
				margin: 0;
			}
			.charting .vertical-tab .nav-tabs{
				width: 100%;
				display: block;
				margin: 0 0 4px;
			}
			.charting .vertical-tab .nav-tabs li a{ margin: 0 0 1px; }
			.charting .vertical-tab .nav-tabs li a:after{
				width: 100%;
				height: 4px;
				transform: scaleY(1) scaleX(0);
			}
			.charting .vertical-tab .nav-tabs li a:hover:before,
			.charting .vertical-tab .nav-tabs li.active a:before{
				/*transform: scaleX(0);*/
			}
			.charting .vertical-tab .nav-tabs li a:hover:after,
			.charting .vertical-tab .nav-tabs li.active a:after{
				/*transform: scaleX(1);*/
			}
			.charting .vertical-tab .tab-content{
				font-size: 14px;
				margin-top: 0;
				display: block;
			}
			.charting .vertical-tab .tab-content h3{ font-size: 18px; }
		}
		
		.charting .modal-body{padding:0;}
		.charting .vertical-tab .nav-tabs::before {content: none;} 
		
		.charting .vertical-tab ul li i {
			width: 22px;
			height: 22px;
			/*border-radius: 4px;*/
			/*float: right;*/
			margin: 0 10px 0 0;
			cursor: pointer;
			background-repeat: no-repeat;
			background-position: center;
		}
		
		.settings_form{display:flex;}
		
		.charting .settings_form .vertical-tab ul li i{
			width: 0px;
		}
		
		/*.modal-dialog{width:900px;}*/
		.modal-dialog{width:1050px;}
		
		.charting .vertical-tab ul li span.icon_name {margin: 0 0 0 15px;}
		
		.charting .vertical-tab .nav-tabs li.active {border:none !important;}
		.charting .vertical-tab .nav-tabs li.active a {border:none !important;}
		
		.charting .vertical-tab .tab-content ul.am-grid {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		
		.charting .vertical-tab .tab-content ul.am-grid li {
			background-repeat: no-repeat;
			background-position: center;
			float: left;
			width: 20%;
			height: 149px;
			border-style: none solid solid none;
			border-width: 1px;
			border-color: rgba(0,0,0,.05);
			cursor: pointer;
			position: relative;
			display: table;
		}
		
		/*.charting .vertical-tab .nav-tabs { height:100vh; }*/
		
		
		.charting .vertical-tab .tab-content ul.am-grid li span {
			/*position: absolute;*/
			/*display: block;*/
			bottom: 0;
			width: 100%;
			text-align: center;
			padding: 10px 0;
			display: table-cell; 
			vertical-align: bottom;
		}
		
		.form-group .control-label{color:#333;}
		
		.am_settings, .am_graph_data {display:none;}
		
		.am_settings {padding-right:0; width: 645px !important;}
		.am_graph_data {padding-left:0; flex:auto;}
		.am_graph_data div:first-child {
		  padding:0;
		}
		
		.am_settings .vertical-tab .nav-tabs{
			/*display: table-cell;*/
			width: 28%;
			min-width: 28%; 
			border: none;
			float:left;
			z-index:0;
		}
		
		.charting .vertical-tab .tab-content input{ 
			/*color: #555555;*/
		}
		
		.update_chart{margin-left:220px;}
		.save_chart{float:right; margin-right:2%;}
		
		/*.colorpicker {top:156px !important;}*/
		.colorpicker {top:35px !important; /*left:72px;*/ right:0 !important;}
		.colorpicker.colorpicker-with-alpha {width:150px;}
		.dropdown-menu {left:unset !important;}

/*
		.graph_item{padding:30px; display:inline-block; background-color:#c9c9c9;}
		.tab-content{padding-top:20px;}
*/

		#chart_font_size_slider,#title_size_slider,#axes_font_size_slider,#cat_axes_font_size_slider,#cat_axes_rotation_slider,#cat_label_rotate_slider,#transparency_slider,#lpwidth_slider,#lpspacing_slider,#lpmaxcol_slider,#lpvalwidth_slider,#lphorizontal_slider,#lpvertical_slider,#fill_transparency_slider,#border_transparency_slider,#legend_font_size_slider,#ghl_width_slider,#ghl_dlength_slider,#ghl_transparency_slider,#ghl_mtransparency_slider,#ghf_transparency_slider,#gvl_width_slider,#gvl_dlength_slider,#gvl_transparency_slider,#gvl_mtransparency_slider,#gvf_transparency_slider,#caf_transparency_slider,#cab_transparency_slider,#paf_transparency_slider,#pab_transparency_slider,#d3_angle_slider,#d3_depth_slider,#dL1f_size_slider,#dL2f_size_slider,#dL3f_size_slider,#dL1p_offset_slider,#dl2p_offset_slider,#dL3p_offset_slider,#dL1p_rotation_slider,#dL2p_rotation_slider,#dL3p_rotation_slider,#phh_width_slider,#phh_transparency_slider,#phh_size_slider,#phh_rotation_slider,#phh_ticklength_slider,#phh_mticklength_slider,#phh_lfrequency_slider,#phh_lugap_slider,#phh_lrotation_slider,#pvv_width_slider,#pvv_transparency_slider,#pvv_size_slider,#pvv_rotation_slider,#pvv_ticklength_slider,#pvv_mticklength_slider,#pvv_lfrequency_slider,#pvv_lugap_slider,#pvv_lrotation_slider,#svv_width_slider,#svv_transparency_slider,#svv_size_slider,#svv_rotation_slider,#svv_ticklength_slider,#svv_mticklength_slider,#svv_lfrequency_slider,#svv_lugap_slider,#svv_lrotation_slider,#dsf1_transparency_slider,#dsf2_transparency_slider,#dsf2_transparency_slider,#ds1_lthickness_slider,#ds2_lthickness_slider,#ds3_lthickness_slider,#ds1_dlength_slider,#ds2_dlength_slider,#ds3_dlength_slider,#dsl1_transparency_slider,#dsl2_transparency_slider,#dsl3_transparency_slider,#ds1_rcorner_slider,#ds2_rcorner_slider,#ds3_rcorner_slider,#ds1_cwidth_slider,#ds2_cwidth_slider,#ds2_cwidth_slider,#ds1co_size_slider,#ds2co_size_slider,#ds3co_size_slider,#ds1co_transparency_slider,#ds2co_transparency_slider,#ds3co_transparency_slider,#ds1_bthikness_slider,#ds2_bthikness_slider,#ds3_bthikness_slider,#ds1_btransparency_slider,#ds2_btransparency_slider,#ds3_btransparency_slider		{
			margin-top: 13px;
		}

		#chart_font_size_handle,#title_size_handle,#axes_font_size_handle,#cat_axes_font_size_handle,#cat_axes_rotation_handle,#cat_label_rotate_handle,#transparency_handle,#lpwidth_handle,#lpspacing_handle,#lpmaxcol_handle,#lpvalwidth_handle,#lphorizontal_handle,#lpvertical_handle,#fill_transparency_handle,#border_transparency_handle,#legend_font_size_handle,#ghl_width_handle,#ghl_dlength_handle,#ghl_transparency_handle,#ghl_mtransparency_handle,#ghf_transparency_handle,#gvl_width_handle,#gvl_dlength_handle,#gvl_transparency_handle,#gvl_mtransparency_handle,#gvf_transparency_handle,#caf_transparency_handle,#cab_transparency_handle,#paf_transparency_handle,#pab_transparency_handle,#d3_angle_handle,#d3_depth_handle,#dL1f_size_handle,#dL2f_size_handle,#dL3f_size_handle,#dL1p_offset_handle,#dL2p_offset_handle,#dL3p_offset_handle,#dL1p_rotation_handle,#dL2p_rotation_handle,#dL3p_rotation_handle,#phh_width_handle,#phh_transparency_handle,#phh_size_handle,#phh_rotation_handle,#phh_ticklength_handle,#phh_mticklength_handle,#phh_lfrequency_handle,#phh_lugap_handle,#phh_lrotation_handle,#pvv_width_handle,#pvv_transparency_handle,#pvv_size_handle,#pvv_rotation_handle,#pvv_ticklength_handle,#pvv_mticklength_handle,#pvv_lfrequency_handle,#pvv_lugap_handle,#pvv_lrotation_handle,#svv_width_handle,#svv_transparency_handle,#svv_size_handle,#svv_rotation_handle,#svv_ticklength_handle,#svv_mticklength_handle,#svv_lfrequency_handle,#svv_lugap_handle,#svv_lrotation_handle,#dsf1_transparency_handle,#dsf2_transparency_handle,#dsf3_transparency_handle,#ds1_lthickness_handle,#ds2_lthickness_handle,#ds3_lthickness_handle,#ds1_dlength_handle,#ds2_dlength_handle,#ds3_dlength_handle,#dsl1_transparency_handle,#dsl2_transparency_handle,#dsl3_transparency_handle,#ds1_rcorner_handle,#ds2_rcorner_handle,#ds3_rcorner_handle,#ds1_cwidth_handle,#ds2_cwidth_handle,#ds3_cwidth_handle,#ds1co_size_handle,#ds2co_size_handle,#ds3co_size_handle,#ds1co_transparency_handle,#ds2co_transparency_handle,#ds3co_transparency_handle,#ds1_bthikness_handle,#ds2_bthikness_handle,#ds3_bthikness_handle,#ds1_btransparency_handle,#ds2_btransparency_handle,#ds3_btransparency_handle		{
			width: 2em;
			height: 1.6em;
			top: 50%;
			margin-top: -.8em;
			text-align: center;
			line-height: 1.6em;		
	  }
	  #transparency_handle,#ghl_transparency_handle,#ghl_mtransparency_handle,#ghf_transparency_handle,#gvl_transparency_handle,#gvl_mtransparency_handle,#gvf_transparency_handle,#cab_transparency_handle,#caf_transparency_handle,#paf_transparency_handle,#pab_transparency_handle,#fill_transparency_handle,#border_transparency_handle,#dsf1_transparency_handle,#dsf2_transparency_handle,#dsf3_transparency_handle,#dsl1_transparency_handle,#dsl2_transparency_handle,#dsl3_transparency_handle,#ds1co_transparency_handle,#ds2co_transparency_handle,#ds3co_transparency_handle,#phh_transparency_handle,#pvv_transparency_handle,#svv_transparency_handle{width: 35px;}
	  
	  
	  /*.my_color_picker{position:relative;}*/
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li{float:left;}
	  .am_settings .vertical-tab .exceltabs .nav-tabs{width: 100%; min-width: 100%;}
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a::before, 
	  .charting .vertical-tab .exceltabs .nav-tabs li a::after {
		  background-color:#c9c9c9;		  
	  }
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a:hover::after, 
	  .charting .vertical-tab .exceltabs .nav-tabs li.active a::after {
		  transform: scaleY(0);
	  }
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a {color:#555;}
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a i {margin:0;}
	  
	  .charting .vertical-tab .exceltabs ul li i {height:10px;}
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a:hover i, 
	  .charting .vertical-tab .exceltabs .nav-tabs li.active a i
	  {
		  color: #555;
	  }
	  
	  .charting .vertical-tab .exceltabs .nav-tabs > li.active > a {
		  margin-top: 2px !important; 
		  line-height: 20px !important;
	  }
	  
	  .charting .vertical-tab .exceltabs .nav-tabs li a {line-height: 22px;}
	  
	  .charting .vertical-tab .exceltabs .tab-content {padding-left:0px; padding-right:0px; padding-top:1px; padding-bottom:0px;}

	  .ar_mb_0{margin-bottom:0px}
	  .auto_span{margin-left:10px; font-size:12px !important; letter-spacing: normal !important;}
	  .modal-dialog-chart{width:50% !important;}

	</style> 
	

	<br /><br />
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i> 
					Tools
				<span>> 
					Charting
				</span>
				<a href="#false" class="update_chart btn btn-primary btn-sm">Update</a>
				<span class="auto_span"><input type="checkbox" id="auto_update_chart" name="auto_update_chart" checked> Auto Update</span>
				
				
			</h1>				
		</div>
		
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<div class="pull-right-- save_chart form-group">
				<a href="#false" class="pull-left btn btn-primary btn-sm"  data-toggle="modal" data-target="#saveformpopup"><?php echo ($chart_id>0)?"Update":"Save";?> Chart</a>
				<select class="form-control pull-left load_chart" style="width:unset;">
					<option value="0">Select Chart</option>
					<?php
						$mychart = "SELECT Id,chart_name FROM amcharts where status = 0 and user_id = $user_one order by Id desc";
						//echo $min_max_query;
						$mychartqry = $mysqli->query($mychart);
						if($mychartqry){
							while ( $mychartrs = $mychartqry->fetch_array(MYSQLI_ASSOC) ) {
								$selected = "";
								if ($mychartrs['Id'] == $_GET['chart_id']) {
									$selected = "selected";
								}
								echo "<option value='".$mychartrs['Id']."' $selected>".$mychartrs['chart_name']."</option>";
							}
						}
					?>
				</select>
				
			</div>
		</div>
	</div>
	<!-- widget grid -->
	<section id="widget-grid" class="charting">

		<!-- row -->
		<div class="row">

			<!-- POPUP --NEW WIDGET START -->
			<article class="am_popup col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<?php include_once('./chart_editor/select_graph.php');?>				
											
			</article>
			
			<form target="chart_iframe" id="settings_form" method="post" class="form-horizontal settings_form" name="settings_form" action="assets/ajax/chart_editor/am_chart.php?gid=<?php echo $chart_gid?>&cid=<?php echo $chart_cid?>">
			<!-- SETTINGS --NEW WIDGET START -->
			
			<article class="am_settings col-xs-12 col-sm-5 col-md-5 col-lg-5">
				
			<?php include_once('./chart_editor/setting_form.php');?>				
											
			</article>
			
			<!-- SETTINGS --NEW WIDGET START -->
			
			<article class="am_graph_data col-xs-12 col-sm-7 col-md-7 col-lg-7">
				
			<?php include_once('./chart_editor/graph_data.php');?>				
											
			</article>
			
			<input type="hidden" name="chart_gid" value="<?php echo $chart_gid?>">
			<input type="hidden" name="chart_cid" value="<?php echo $chart_cid?>">
			
			<input type="hidden" name="h_chart_name" id="h_chart_name">
			<input type="hidden" name="h_chart_id" id="h_chart_id" value="0">
			
			</form>
		
		</div>
		<!-- end row -->

	</section>
	<!-- end widget grid -->
	
	<!-- Modal save form -->
	<div class="modal fade" id="saveformpopup" tabindex="-1" role="dialog" 
		 aria-labelledby="saveformLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-chart">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<button type="button" class="close" 
					   data-dismiss="modal">
						   <span aria-hidden="true">&times;</span>
						   <span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="saveformLabel">
						Save Current Chart
					</h4>
				</div>
				
				
					<!-- Modal Body -->
					<div class="modal-body">					
						
						  <div class="form-group" id="chartnamecontainer">
							<label for="exampleInputEmail1">Enter Chart Name</label>
							  <input type="text" class="form-control" id="chartnameinput" placeholder="Chart Name" value="<?php echo (isset($_SESSION['db_chart']))?$_SESSION['db_chart']['chart_name']:"";?>"/>
							  <span class="help-block chartnameerror hide">Please enter chart name</span>
						  </div>
						  
						
					</div>
					
					<!-- Modal Footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="save_new_chart">Save Changes</button>
					</div>
				
				
			</div>
		</div>
	</div>
	<!-- Modal save form end -->

	
	<script type="text/javascript">
		
		function update_chart() {
			if ($('#auto_update_chart').is(':checked')) {
				//$("#settings_form").submit();
				if ($('#db_inprocess').val() == 0) {
					$(".update_chart").click();
					console.log("update chart is click"); 
				}
			}					
		}
			
		$(window).load(function(){
			//$('#chartModal').modal('show');
		});
		
		$(document).ready(function(){
			<?php if (!isset($_GET['gid'])) {?>
				$("#chartModal").modal('show');
			<?php } else { ?>
				$('.am_popup').hide();
				$('.am_settings').show();
				$('.am_graph_data').show();
				$('body').removeClass('modal-open');
			<?php } ?>
		});
		
		$('#chartModal').on('hidden.bs.modal', function () {
			// do something
			$("#chartModal .chartlist li:first").click();
			return false;
			/*
			$('.am_popup').hide();
			$('.am_settings').show();
			$('.am_graph_data').show();
			*/
		});
		
		$("#chartModal .vertical-tab .tab-content ul.am-grid li").click(function(){
			var group_chart_id = $(this).find('span').attr('id');
			var idArr = group_chart_id.split('-');
			var group_id = idArr[0];
			var chart_id = idArr[1];
			
			navigateurl('assets/ajax/charting.php?gid='+group_id+'&cid='+chart_id+'','Charting');
			
			//console.log($(this).find('span').attr('id'));
			//$('.am_popup').hide();
			//$('.am_settings').show();
			//$('.am_graph_data').show();
			//alert("The paragraph was clicked.");
		});
		
		/*
		$("#chartModal .chartlist li").click(function(){
			console.log($(this).attr('id'));
		});
		*/
		
		
		
		$(".update_chart").click(function(){
			$("#settings_form").submit();
		});
		
		$('#settings_form :input').blur(function() {
			//update_chart();
			//$("#settings_form").submit();
		});
		$("#settings_form :input").change(function() {
			update_chart();
			//$("#settings_form").submit();
		});
		
		$("#resetchart").click(function(){
			//alert('here');
			$('.am_popup').show();
			$("#chartModal").modal('show');
		});
	
/*	
      $(function () {

        $('.settings_form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'post.php',
            data: $('form').serialize(),
            success: function () {
              alert('form was submitted');
            }
          });

        });

      });
*/   
	</script>
	
	<script>
          $(document).ready(function(){
			  /*
              $('#text_color').colorpicker({
                  color: '#000000',
                  //format: 'rgba'
              });
			  */
			  
			var handle1 = $( "#chart_font_size_handle" );
			$( "#chart_font_size_slider" ).slider({
			  value:15,
			  min: 8,
			  max: 50,
			  step: 1,
			  create: function() {
				handle1.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle1.text( ui.value );
				$('#chart_font_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle2 = $( "#title_size_handle" );
			$( "#title_size_slider" ).slider({
			  value:15,
			  min: 8,
			  max: 50,
			  step: 1,
			  create: function() {
				handle2.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle2.text( ui.value );
				$('#title_size').val(ui.value);
			  }
			});
			
			var handle3 = $( "#axes_font_size_handle" );
			$( "#axes_font_size_slider" ).slider({
			  value:14,
			  min: 8,
			  max: 50,
			  step: 1,
			  create: function() {
				handle3.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle3.text( ui.value );
				$('#axes_font_size').val(ui.value);
			  }
			});
			
			var handle4 = $( "#cat_axes_font_size_handle" );
			$( "#cat_axes_font_size_slider" ).slider({
			  value:14,
			  min: 8,
			  max: 50,
			  step: 1,
			  create: function() {
				handle4.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle4.text( ui.value );
				$('#cat_axes_font_size').val(ui.value);
			  }
			});
			
			var handle5 = $( "#cat_axes_rotation_handle" );
			$( "#cat_axes_rotation_slider" ).slider({
			  value:0,
			  min: -90,
			  max: 0,
			  step: 15,
			  create: function() {
				handle5.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle5.text( ui.value );
				$('#cat_axes_rotation').val(ui.value);
			  }
			});
			
			var handle6 = $( "#cat_label_rotate_handle" );
			$( "#cat_label_rotate_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 90,
			  step: 15,
			  create: function() {
				handle6.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle6.text( ui.value );
				$('#cat_label_rotate').val(ui.value);
			  }
			});
			
			var handle7 = $( "#transparency_handle" );
			$( "#transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle7.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle7.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				//$("#settings_form").submit();
				update_chart();
			  }
			});
			
			var handle8 = $( "#lpwidth_handle" );
			$( "#lpwidth_slider" ).slider({
			  value:400,
			  min: 100,
			  max: 1000,
			  step: 100,
			  create: function() {
				handle8.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle8.text( ui.value );
				$('#lpwidth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle9 = $( "#lpspacing_handle" );
			$( "#lpspacing_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle9.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle9.text( ui.value );
				$('#lpspacing').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle99 = $( "#lpmaxcol_handle" );
			$( "#lpmaxcol_slider" ).slider({
			  value:2,
			  min: 0,
			  max: 10,
			  step: 1,
			  create: function() {
				handle99.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle99.text( ui.value );
				$('#lpmaxcol').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle10 = $( "#lpvalwidth_handle" );
			$( "#lpvalwidth_slider" ).slider({
			  value:34,
			  min: 0,
			  max: 100,
			  step: 5,
			  create: function() {
				handle10.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle10.text( ui.value );
				$('#lpvalwidth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle11 = $( "#lphorizontal_handle" );
			$( "#lphorizontal_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle11.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle11.text( ui.value );
				$('#lphorizontal').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle12 = $( "#lpvertical_handle" );
			$( "#lpvertical_slider" ).slider({
			  value:10,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle12.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle12.text( ui.value );
				$('#lpvertical').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle13 = $( "#fill_transparency_handle" );
			$( "#fill_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle13.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle13.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#fill_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle14 = $( "#border_transparency_handle" );
			$( "#border_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle14.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle14.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#border_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle15 = $( "#legend_font_size_handle" );
			$( "#legend_font_size_slider" ).slider({
			  value:10,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle15.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle15.text( ui.value );
				$('#legend_font_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle16 = $( "#ghl_width_handle" );
			$( "#ghl_width_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle16.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle16.text( ui.value );
				$('#ghl_width').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle17 = $( "#ghl_dlength_handle" );
			$( "#ghl_dlength_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle17.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle17.text( ui.value );
				$('#ghl_dlength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle18 = $( "#ghl_transparency_handle" );
			$( "#ghl_transparency_slider" ).slider({
			  value:75,
			  min: 0,
			  max: 100,
			  step: 1,
			   create: function() {
				handle18.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle18.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ghl_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle19 = $( "#ghl_mtransparency_handle" );
			$( "#ghl_mtransparency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			   create: function() {
				handle19.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle19.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ghl_mtransparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle199 = $( "#ghf_transparency_handle" );
			$( "#ghf_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle199.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle199.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ghf_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle20 = $( "#gvl_width_handle" );
			$( "#gvl_width_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle20.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle20.text( ui.value );
				$('#gvl_width').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle21 = $( "#gvl_dlength_handle" );
			$( "#gvl_dlength_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle21.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle21.text( ui.value );
				$('#gvl_dlength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle22 = $( "#gvl_transparency_handle" );
			$( "#gvl_transparency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle22.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle22.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#gvl_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle23 = $( "#gvl_mtransparency_handle" );
			$( "#gvl_mtransparency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle23.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle23.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#gvl_mtransparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle24 = $( "#gvf_transparency_handle" );
			$( "#gvf_transparency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle24.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle24.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#gvf_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle25 = $( "#caf_transparency_handle" );
			$( "#caf_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 10,
			  create: function() {
				handle25.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle25.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#caf_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle26 = $( "#cab_transparency_handle" );
			$( "#cab_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 10,
			  create: function() {
				handle26.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle26.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#cab_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle27 = $( "#paf_transparency_handle" );
			$( "#paf_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 10,
			  create: function() {
				handle27.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle27.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#paf_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle28 = $( "#pab_transparency_handle" );
			$( "#pab_transparency_slider" ).slider({
			  value:50,
			  min: 0,
			  max: 100,
			  step: 10,
			  create: function() {
				handle28.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle28.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#pab_transparency').val(decimal_val);
				//handle28.text( ui.value );
				//$('#pab_transparency').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle29 = $( "#d3_angle_handle" );
			$( "#d3_angle_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle29.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle29.text( ui.value );
				$('#d3_angle').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle30 = $( "#d3_depth_handle" );
			$( "#d3_depth_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle30.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle30.text( ui.value );
				$('#d3_depth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle31 = $( "#dL1f_size_handle" );
			$( "#dL1f_size_slider" ).slider({
			  value:12,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle31.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle31.text( ui.value );
				$('#dL1f_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle311 = $( "#dL2f_size_handle" );
			$( "#dL2f_size_slider" ).slider({
			  value:12,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle311.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle311.text( ui.value );
				$('#dL2f_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle3111 = $( "#dL3f_size_handle" );
			$( "#dL3f_size_slider" ).slider({
			  value:12,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle3111.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle3111.text( ui.value );
				$('#dL3f_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle32 = $( "#dL1p_offset_handle" );
			$( "#dL1p_offset_slider" ).slider({
			  value:5,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle32.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle32.text( ui.value );
				$('#dL1p_offset').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle32a = $( "#dL2p_offset_handle" );
			$( "#dL2p_offset_slider" ).slider({
			  value:5,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle32a.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle32a.text( ui.value );
				$('#dL2p_offset').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle32b = $( "#dL3p_offset_handle" );
			$( "#dL3p_offset_slider" ).slider({
			  value:5,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle32b.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle32b.text( ui.value );
				$('#dL3p_offset').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle33 = $( "#dL1p_rotation_handle" );
			$( "#dL1p_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle33.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle33.text( ui.value );
				$('#dL1p_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle33a = $( "#dL2p_rotation_handle" );
			$( "#dL2p_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle33a.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle33a.text( ui.value );
				$('#dL2p_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle33b = $( "#dL3p_rotation_handle" );
			$( "#dL3p_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle33b.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle33b.text( ui.value );
				$('#dL3p_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle34 = $( "#phh_width_handle" );
			$( "#phh_width_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle34.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle34.text( ui.value );
				$('#phh_width').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle35 = $( "#phh_transparency_handle" );
			$( "#phh_transparency_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle35.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle35.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#phh_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle36 = $( "#phh_size_handle" );
			$( "#phh_size_slider" ).slider({
			  value:15,
			  min: 0,
			  max: 30,
			  step: 1,
			  create: function() {
				handle36.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle36.text( ui.value );
				$('#phh_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle37 = $( "#phh_rotation_handle" );
			$( "#phh_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle37.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle37.text( ui.value );
				$('#phh_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle337 = $( "#phh_ticklength_handle" );
			$( "#phh_ticklength_slider" ).slider({
			  value:12,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle337.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle337.text( ui.value );
				$('#phh_ticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle38 = $( "#phh_mticklength_handle" );
			$( "#phh_mticklength_slider" ).slider({
			  value:7,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle38.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle38.text( ui.value );
				$('#phh_mticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle39 = $( "#phh_lfrequency_handle" );
			$( "#phh_lfrequency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle39.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle39.text( ui.value );
				$('#phh_lfrequency').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle40 = $( "#phh_lugap_handle" );
			$( "#phh_lugap_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 1000,
			  step: 1,
			  create: function() {
				handle40.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle40.text( ui.value );
				$('#phh_lugap').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle41 = $( "#phh_lrotation_handle" );
			$( "#phh_lrotation_slider" ).slider({
			  value:-30,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle41.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle41.text( ui.value );
				$('#phh_lrotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle42 = $( "#pvv_width_handle" );
			$( "#pvv_width_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle42.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle42.text( ui.value );
				$('#pvv_width').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle43 = $( "#pvv_transparency_handle" );
			$( "#pvv_transparency_slider" ).slider({
			  value:75,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle43.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle43.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#pvv_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  } 
			});
			var handle44 = $( "#pvv_size_handle" );
			$( "#pvv_size_slider" ).slider({
			  value:15,
			  min: 0,
			  max: 30,
			  step: 1,
			  create: function() {
				handle44.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle44.text( ui.value );
				$('#pvv_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle45 = $( "#pvv_rotation_handle" );
			$( "#pvv_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle45.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle45.text( ui.value );
				$('#pvv_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle46 = $( "#pvv_ticklength_handle" );
			$( "#pvv_ticklength_slider" ).slider({
			  value:7,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle46.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle46.text( ui.value );
				$('#pvv_ticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle47 = $( "#pvv_mticklength_handle" );
			$( "#pvv_mticklength_slider" ).slider({
			  value:7,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle47.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle47.text( ui.value );
				$('#pvv_mticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle48 = $( "#pvv_lfrequency_handle" );
			$( "#pvv_lfrequency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle48.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle48.text( ui.value );
				$('#pvv_lfrequency').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle49 = $( "#pvv_lugap_handle" );
			$( "#pvv_lugap_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 1000,
			  step: 1,
			  create: function() {
				handle49.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle49.text( ui.value );
				$('#pvv_lugap').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle50 = $( "#pvv_lrotation_handle" );
			$( "#pvv_lrotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle50.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle50.text( ui.value );
				$('#pvv_lrotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			
			var handle51 = $( "#svv_width_handle" );
			$( "#svv_width_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle51.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle51.text( ui.value );
				$('#svv_width').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle52 = $( "#svv_transparency_handle" );
			$( "#svv_transparency_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 100,
			  step: 1,	  
			  create: function() {
				handle52.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle52.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#svv_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle53 = $( "#svv_size_handle" );
			$( "#svv_size_slider" ).slider({
			  value:15,
			  min: 0,
			  max: 30,
			  step: 1,
			  create: function() {
				handle53.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle53.text( ui.value );
				$('#svv_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle54 = $( "#svv_rotation_handle" );
			$( "#svv_rotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle54.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle54.text( ui.value );
				$('#svv_rotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle55 = $( "#svv_ticklength_handle" );
			$( "#svv_ticklength_slider" ).slider({
			  value:12,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle55.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle55.text( ui.value );
				$('#svv_ticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle56 = $( "#svv_mticklength_handle" );
			$( "#svv_mticklength_slider" ).slider({
			  value:7,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle56.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle56.text( ui.value );
				$('#svv_mticklength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle57 = $( "#svv_lfrequency_handle" );
			$( "#svv_lfrequency_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle57.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle57.text( ui.value );
				$('#svv_lfrequency').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle58 = $( "#svv_lugap_handle" );
			$( "#svv_lugap_slider" ).slider({
			  value:25,
			  min: 0,
			  max: 1000,
			  step: 5,
			  create: function() {
				handle58.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle58.text( ui.value );
				$('#svv_lugap').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle59 = $( "#svv_lrotation_handle" );
			$( "#svv_lrotation_slider" ).slider({
			  value:0,
			  min: -180,
			  max: 180,
			  step: 1,
			  create: function() {
				handle59.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle59.text( ui.value );
				$('#svv_lrotation').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			//data series
			var handle60 = $( "#dsf1_transparency_handle" );
			$( "#dsf1_transparency_slider" ).slider({
			  //value:<?php //echo js_dsf_transparency(1)?>,
			  value: 0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle60.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle60.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsf1_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle61 = $( "#dsf2_transparency_handle" );
			$( "#dsf2_transparency_slider" ).slider({
			  //value:<?php //echo js_dsf_transparency(2)?>,
			  value: 0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle61.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle61.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsf2_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle62 = $( "#dsf3_transparency_handle" );
			$( "#dsf3_transparency_slider" ).slider({
			  //value:<?php //echo js_dsf_transparency(3)?>,
			  value: 0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle62.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle62.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsf3_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			var handle63 = $( "#ds1_lthickness_handle" );
			$( "#ds1_lthickness_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle63.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle63.text( ui.value );
				$('#ds1_lthickness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle64 = $( "#ds2_lthickness_handle" );
			$( "#ds2_lthickness_slider" ).slider({
			  //value:<?php //echo js_ds_lthickness(2);?>,
			  value: 1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle64.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle64.text( ui.value );
				$('#ds2_lthickness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle65 = $( "#ds3_lthickness_handle" );
			$( "#ds3_lthickness_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle65.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle65.text( ui.value );
				$('#ds3_lthickness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle66 = $( "#ds1_dlength_handle" );
			$( "#ds1_dlength_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle66.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle66.text( ui.value );
				$('#ds1_dlength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle67 = $( "#ds2_dlength_handle" );
			$( "#ds2_dlength_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle67.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle67.text( ui.value );
				$('#ds2_dlength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle68 = $( "#ds3_dlength_handle" );
			$( "#ds3_dlength_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle68.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle68.text( ui.value );
				$('#ds3_dlength').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle69 = $( "#dsl1_transparency_handle" );
			$( "#dsl1_transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle69.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle69.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsl1_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle70 = $( "#dsl2_transparency_handle" );
			$( "#dsl2_transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle70.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle70.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsl2_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle71 = $( "#dsl3_transparency_handle" );
			$( "#dsl3_transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle71.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle71.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#dsl3_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle72 = $( "#ds1_rcorner_handle" );
			$( "#ds1_rcorner_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle72.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle72.text( ui.value );
				$('#ds1_rcorner').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle73 = $( "#ds2_rcorner_handle" );
			$( "#ds2_rcorner_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle73.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle73.text( ui.value );
				$('#ds2_rcorner').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle74 = $( "#ds3_rcorner_handle" );
			$( "#ds3_rcorner_slider" ).slider({
			  value:0,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle74.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle74.text( ui.value );
				$('#ds3_rcorner').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle75 = $( "#ds1_cwidth_handle" );
			$( "#ds1_cwidth_slider" ).slider({
			  value:.8,
			  min: 0,
			  max: 1,
			  step: .1,
			  create: function() {
				handle75.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle75.text( ui.value );
				$('#ds1_cwidth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle76 = $( "#ds2_cwidth_handle" );
			$( "#ds2_cwidth_slider" ).slider({
			  value:.8,
			  min: 0,
			  max: 1,
			  step: .1,
			  create: function() {
				handle76.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle76.text( ui.value );
				$('#ds2_cwidth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle77 = $( "#ds3_cwidth_handle" );
			$( "#ds3_cwidth_slider" ).slider({
			  value:.8,
			  min: 0,
			  max: 1,
			  step: .1,
			  create: function() {
				handle77.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle77.text( ui.value );
				$('#ds3_cwidth').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle78 = $( "#ds1co_size_handle" );
			$( "#ds1co_size_slider" ).slider({
			  value:8,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle78.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle78.text( ui.value );
				$('#ds1co_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle79 = $( "#ds2co_size_handle" );
			$( "#ds2co_size_slider" ).slider({
			  value:8,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle79.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle79.text( ui.value );
				$('#ds2co_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle80 = $( "#ds3co_size_handle" );
			$( "#ds3co_size_slider" ).slider({
			  value:8,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle80.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle80.text( ui.value );
				$('#ds3co_size').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle81 = $( "#ds1co_transparency_handle" );
			$( "#ds1co_transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle81.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle81.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds1co_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle82 = $( "#ds2co_transparency_handle" );
			$( "#ds2co_transparency_slider" ).slider({
			 value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle82.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle82.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds2co_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle83 = $( "#ds3co_transparency_handle" );
			$( "#ds3co_transparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle83.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle83.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds3co_transparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle84 = $( "#ds1_bthikness_handle" );
			$( "#ds1_bthikness_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle84.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle84.text( ui.value );
				$('#ds1_bthikness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle85 = $( "#ds2_bthikness_handle" );
			$( "#ds2_bthikness_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle85.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle85.text( ui.value );
				$('#ds2_bthikness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle86 = $( "#ds3_bthikness_handle" );
			$( "#ds3_bthikness_slider" ).slider({
			  value:1,
			  min: 0,
			  max: 50,
			  step: 1,
			  create: function() {
				handle86.text( $( this ).slider( "value" ) );
			  },
			  slide: function( event, ui ) {
				handle86.text( ui.value );
				$('#ds3_bthikness').val(ui.value);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle87 = $( "#ds1_btransparency_handle" );
			$( "#ds1_btransparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle87.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle87.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds1_btransparency').val(decimal_val);
			  },
			  stop: function( event, ui ) { 
				update_chart();
			  }
			});
			var handle88 = $( "#ds2_btransparency_handle" );
			$( "#ds2_btransparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle88.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle88.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds2_btransparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			var handle89 = $( "#ds3_btransparency_handle" );
			$( "#ds3_btransparency_slider" ).slider({
			  value:40,
			  min: 0,
			  max: 100,
			  step: 1,
			  create: function() {
				handle89.text( $( this ).slider( "value" )+'%' );
			  },
			  slide: function( event, ui ) {
				handle89.text( ui.value+'%' );
				var decimal_val = 1-(ui.value/100);
				$('#ds3_btransparency').val(decimal_val);
			  },
			  stop: function( event, ui ) {
				update_chart();
			  }
			});
			
			
  
          });
		  
		  loadScript("assets/js/bootstrap-colorpicker.min.js", function(){					
			//pagefunction();		
			
			$('#cp_text_color').colorpicker({
				color: '#000000',
				container: '#cp_text_color',
				//popover: false,
				//inline: true,
				//format: 'rgba'
            });
			$('#cp_text_color').colorpicker().on('changeColor',
			//$('#cp_text_color').colorpicker().on('colorpickerDestroy',
            function(ev) {
				//$("#settings_form").submit();
				update_chart();
                //changeTableColor('myColorCode');
				//alert('color');
            });
			$('#cp_axes_color').colorpicker({
                color: '#000000',
				container: '#cp_axes_color',
            });
			$('#cp_cat_axes_color').colorpicker({
                color: '#000000',
				container: '#cp_cat_axes_color',
            });
			$('#cp_bg_boder_color').colorpicker({
                color: '#000000',
				container: '#cp_bg_boder_color',
            });			
			$('#cp_title_color').colorpicker({
                color: '#000000',
				container: '#cp_title_color',
            });			
			$('#cp_axes_color').colorpicker({
                color: '#000000',
				container: '#cp_axes_color',
            });
			$('#cp_bg_color').colorpicker({
                color: '#FFFFFF',
				container: '#cp_bg_color',
            });
			$('#cp_fill_color').colorpicker({
                color: '#ffffff',
				container: '#cp_fill_color',
            });
			$('#cp_fill_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_border_color').colorpicker({
                color: '#ffffff',
				container: '#cp_border_color',
            });
			$('#cp_border_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_legend_font_color').colorpicker({
                color: '#000000',
				container: '#cp_legend_font_color',
            });
			$('#cp_legend_font_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ghl_color').colorpicker({
                color: '#000000',
				container: '#cp_ghl_color',
            });
			$('#cp_ghl_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ghf_color').colorpicker({
                color: '#ffffff',
				container: '#cp_ghf_color',
            });
			$('#cp_ghf_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_gvl_color').colorpicker({
                color: '#000000',
				container: '#cp_gvl_color',
            });
			$('#cp_gvl_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_gvf_color').colorpicker({
                color: '#ffffff',
				container: '#cp_gvf_color',
            });
			$('#cp_gvf_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_caf_color').colorpicker({ 
                color: '#ffffff',
				container: '#cp_caf_color', 
            });
			$('#cp_caf_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_paf_color').colorpicker({
                color: '#ffffff',
				container: '#cp_paf_color',
            });
			$('#cp_paf_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_pab_color').colorpicker({
                color: '#000000',
				container: '#cp_pab_color',
            });
			$('#cp_pab_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_color_dL1').colorpicker({
                color: '#000000',
				container: '#cp_color_dL1',
            });
			$('#cp_color_dL1').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_color_dL2').colorpicker({
                color: '#000000',
				container: '#cp_color_dL2',
            });
			$('#cp_color_dL2').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_color_dL3').colorpicker({
                color: '#000000',
				container: '#cp_color_dL3',
            });
			$('#cp_color_dL3').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_phha_color').colorpicker({
                color: '#000000',
				container: '#cp_phha_color',
            });
			$('#cp_phha_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_phh_color').colorpicker({
                color: '#000000',
				container: '#cp_phh_color',
            });
			$('#cp_phh_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_pvva_color').colorpicker({
                color: '#000000',
				container: '#cp_pvva_color',
            });
			$('#cp_pvva_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_pvv_color').colorpicker({
                color: '#000000',
				container: '#cp_pvv_color',
            });
			$('#cp_pvv_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_svva_color').colorpicker({
                color: '#000000',
				container: '#cp_svva_color',
            });
			$('#cp_svva_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_svv_color').colorpicker({
                color: '#000000',
				container: '#cp_svv_color',
            });
			$('#cp_svv_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsf1_color').colorpicker({
                color: '#FF6600',
				container: '#cp_dsf1_color',
            });
			$('#cp_dsf1_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsf2_color').colorpicker({
                color: '#FCD202',
				container: '#cp_dsf2_color',
            });
			$('#cp_dsf2_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsf3_color').colorpicker({
                color: '#C35454',
				container: '#cp_dsf3_color',
            });
			$('#cp_dsf3_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsl1_color').colorpicker({
                color: '#ff6600',
				container: '#cp_dsl1_color',
            });
			$('#cp_dsl1_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsl2_color').colorpicker({
                color: '#fcd202',
				container: '#cp_dsl2_color',
            });
			$('#cp_dsl2_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_dsl3_color').colorpicker({
                color: '#008000',
				container: '#cp_dsl3_color',
            });
			$('#cp_dsl3_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds1co_color').colorpicker({
                color: '#FF0000',
				container: '#cp_ds1co_color',
            });
			$('#cp_ds1co_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds2co_color').colorpicker({
                color: '#FF0000',
				container: '#cp_ds2co_color',
            });
			$('#cp_ds2co_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds3co_color').colorpicker({
                color: '#FF0000',
				container: '#cp_ds3co_color',
            });
			$('#cp_ds3co_color').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds1_bcolor').colorpicker({
                color: '#0000FF',
				container: '#cp_ds1_bcolor',
            });
			$('#cp_ds1_bcolor').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds2_bcolor').colorpicker({
                color: '#0000FF',
				container: '#cp_ds2_bcolor',
            });
			$('#cp_ds2_bcolor').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			$('#cp_ds3_bcolor').colorpicker({
                color: '#0000FF',
				container: '#cp_ds3_bcolor',
            });
			$('#cp_ds3_bcolor').colorpicker().on('changeColor',
            function(ev) {
				update_chart();
            });
			
			
			//set form by popup charts
			<?php 
			echo "$(function () {";
			
			if ( isset($_GET['gid']) and isset($_GET['cid']) ) {
				
				if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
					
					echo "$('select[name=\"typeds1\"]').val('column');
						  $('select[name=\"typeds2\"]').val('column');
						  $('select[name=\"typeds3\"]').val('column');
						  
						  ";
						
						/*
						$graph_settings .= '
											"type": "column",
											"fillAlphas": 1,
						';
						*/
				}
				
				if ($_GET['gid']=='line') {
					
					echo "$('select[name=\"typeds1\"]').val('line');
						  $('select[name=\"typeds2\"]').val('line');
						  $('select[name=\"typeds3\"]').val('line');
						  
						  $('select[name=\"ds1type\"]').val('round');
						  $('select[name=\"ds2type\"]').val('square');
						  $('select[name=\"ds3type\"]').val('square');
						  
						hs=$('#dsf1_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#dsf2_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#dsf3_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#ds1_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
						
						hs=$('#ds2_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
						
						hs=$('#ds3_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
						
						  
						  ";
				}
				
				if ($_GET['gid']=='area') {
					
					echo "$('select[name=\"typeds1\"]').val('area');
						  $('select[name=\"typeds2\"]').val('area');
						  $('select[name=\"typeds3\"]').val('area');
						  
						hs=$('#dsf1_transparency_slider').slider();
						hs.slider('option', 'value',30);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 30 });
						
						hs=$('#dsf2_transparency_slider').slider();
						hs.slider('option', 'value',30);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 30 });
						
						hs=$('#dsf3_transparency_slider').slider();
						hs.slider('option', 'value',30);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 30 });
						  ";
				}
				
				
				if ( $_GET['gid']=='col' and $_GET['cid']==9 ) {
					echo "$('select[name=\"typeds2\"]').val('line');
					
						hs=$('#dsf2_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#ds2_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
					
					
						$('select[name=\"ds2type\"]').val('round');";

				}
				
				if ( $_GET['gid']=='col' and $_GET['cid']==10 ) {		
					/*echo "$('input[name=\"phh_parsedate\"]').prop(\"checked\", true);
						  $('select[name=\"phh_minPeriod\"]').val('MM');";*/
				}
				
				if ( $_GET['gid']=='bar' and $_GET['cid']==9 ) {
					echo "$('select[name=\"typeds2\"]').val('line');
					
						hs=$('#dsf2_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#ds2_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
					
					
						$('select[name=\"ds2type\"]').val('round');";
				}
				
				if  ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and 
					  ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8) 
					) {
					echo "hs=$('#d3_angle_slider').slider();
						  hs.slider('option', 'value',30);
						  hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 30 });
						  
						  hs=$('#d3_depth_slider').slider();
						  hs.slider('option', 'value',30);
						  hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 30 });
						  ";
				}
				
				
						if ( ($_GET['gid']=='col' and $_GET['cid']==10) || ($_GET['gid']=='line' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10 )) ) {
								  //"parseDates": true,
							echo "$('input[name=\"phh_parsedate\"]').prop(\"checked\", true);";
						}
						
						if ( $_GET['gid']=='area' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) {
							//"parseDates": true,
							echo "$('input[name=\"phh_parsedate\"]').prop(\"checked\", true);";
						} 
						
						if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) {
							//"parseDates": true,
							echo "$('input[name=\"phh_parsedate\"]').prop(\"checked\", true);";
						}
						
						if ( $_GET['gid']=='line' and $_GET['cid']==6 ) {
							//"minPeriod": "MM",
							echo "$('select[name=\"phh_minPeriod\"]').val('MM');";
						}
						
						if ( $_GET['gid']=='line' and $_GET['cid']==7 ) {
							//"minPeriod": "YYYY",
							echo "$('select[name=\"phh_minPeriod\"]').val('YYYY');";
						}
						
						if ( $_GET['gid']=='line' and $_GET['cid']==8 ) {
							//"minPeriod": "hh",
							echo "$('select[name=\"phh_minPeriod\"]').val('hh');";
						}
						
						if ( $_GET['gid']=='line' and $_GET['cid']==9 ) {
							//"minPeriod": "mm",
							echo "$('select[name=\"phh_minPeriod\"]').val('mm');";
						}
						
						if ( $_GET['gid']=='line' and $_GET['cid']==10 ) {
							//"minPeriod": "ss",
							echo "$('select[name=\"phh_minPeriod\"]').val('ss');";
						}
						
						if ( $_GET['gid']=='line' and ($_GET['cid']==11) ) {
							echo "$('select[name=\"typeds1\"]').val('smoothedLine');
								  $('select[name=\"typeds2\"]').val('smoothedLine');
								  $('select[name=\"typeds3\"]').val('smoothedLine');								  
								  ";
						}
						
						if ( $_GET['gid']=='line' and ($_GET['cid']==12 || $_GET['cid']==13) ) {
							echo "$('select[name=\"typeds1\"]').val('step');
								  $('select[name=\"typeds2\"]').val('step');
								  $('select[name=\"typeds3\"]').val('step');
								  
								  $('select[name=\"ds1type\"]').val('');
								  $('select[name=\"ds2type\"]').val('');
								  $('select[name=\"ds3type\"]').val('');
								  
								    hs=$('#ds1_lthickness_slider').slider();
									hs.slider('option', 'value',2);
									hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
									
									hs=$('#ds2_lthickness_slider').slider();
									hs.slider('option', 'value',2);
									hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
									
									hs=$('#ds3_lthickness_slider').slider();
									hs.slider('option', 'value',2);
									hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
							  
								  ";
						}
						
						if ( $_GET['gid']=='area' and $_GET['cid']==6 ) {
							//"minPeriod": "MM",
							echo "$('select[name=\"phh_minPeriod\"]').val('MM');";
						}
						
						if ( $_GET['gid']=='area' and $_GET['cid']==7 ) {
							//"minPeriod": "YYYY",
							echo "$('select[name=\"phh_minPeriod\"]').val('YYYY');";
						}
						
						if ( $_GET['gid']=='area' and $_GET['cid']==8 ) {
							//"minPeriod": "hh",
							echo "$('select[name=\"phh_minPeriod\"]').val('hh');";
						}
						
						if ( $_GET['gid']=='area' and $_GET['cid']==9 ) {
							//"minPeriod": "mm",
							echo "$('select[name=\"phh_minPeriod\"]').val('mm');";
						}
						
						if ( $_GET['gid']=='area' and $_GET['cid']==10 ) {
							//"minPeriod": "ss",
							echo "$('select[name=\"phh_minPeriod\"]').val('ss');";
						}
						
						if ( $_GET['gid']=='other' and ($_GET['cid']==6|| $_GET['cid']==7) ) {
							echo "
						$('select[name=\"ds1type\"]').val('round'); 
						$('select[name=\"ds2type\"]').val('square');
						$('select[name=\"ds3type\"]').val('square');
						  
						hs=$('#dsf1_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#dsf2_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#dsf3_transparency_slider').slider();
						hs.slider('option', 'value',100);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 100 });
						
						hs=$('#ds1_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
						
						hs=$('#ds2_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
						
						hs=$('#ds3_lthickness_slider').slider();
						hs.slider('option', 'value',2);
						hs.slider('option','slide').call(hs,null,{ handle: $('.ui-slider-handle', hs), value: 2 });
							  
								  ";
						}
				 
			} // end of first if
			
			//---update chart on page load---
			echo "$('.update_chart').click();";
			
			echo "});";
			?>
			//---update chart on page load---
			//$(".update_chart").click();
			
			/*
			$(window).resize(function(){
				document.getElementById('chart_iframe_id').contentDocument.location.reload(true);
			});
			*/
			
			// Selecting the iframe element
		
		
		// Adjusting the iframe height onload event
		//frame.onload = function()
		// function execute while load the iframe
		//{
		// set the height of the iframe as
		// the height of the iframe content
		/*
		frame.style.height =
		frame.contentWindow.document.body.scrollHeight + 'px';
		**/

		// set the width of the iframe as the
		// width of the iframe content
		//frame.style.width =
		//frame.contentWindow.document.body.scrollWidth+'px';
			
		//}
		
			$(window).resize(function(){
				//alert(1);
				//document.getElementById('chart_iframe_id').contentDocument.location.reload(true);
				
				//var frame = document.getElementById("chart_iframe_id");
				//alert(frame.contentWindow.document.body.scrollWidth+'px');
				
				//frame.style.width = frame.contentWindow.document.body.scrollWidth+'px';
			});
			
			//reload chart
			//function reload_chart () {
				
			$('#saveformpopup').on('hidden.bs.modal', function () {
				// do something
				$("#chartnamecontainer").removeClass("has-error");
				$(".chartnameerror").addClass("hide");
				//$('#chartnameinput').val(""); comment due to update chart
			});
				
			$('.load_chart').on('change', function() {
			  var chart_id = this.value;
			  //alert( chart_id );
			  navigateurl('assets/ajax/charting.php?chart_id='+chart_id+'&'+Math.random(),'Charting');
			});
			
			//
			$("#save_new_chart").on("click",function (e)
			{
				//validate chart name
				var chartname = $("#chartnameinput").val().trim();

				if (chartname.length == 0 || chartname == "") {
					$("#chartnamecontainer").addClass("has-error");
					$(".chartnameerror").removeClass("hide");
					return false;
				} else {
					$("#chartnamecontainer").removeClass("has-error");
					$(".chartnameerror").addClass("hide");
					
					$("#h_chart_name").val( chartname );
					$("#h_chart_id").val( $(".load_chart").val() );
				}
				
				e.preventDefault();
				var chartdatastring = $("#settings_form").serialize();
				//var chartdatajson = JSON.stringify(chartdatastring);
				$.ajax(
				{
					type:'post',
					url:'assets/ajax/chart_editor/save_chart.php',
					data:chartdatastring,
					//data:chartdatajson,
					//data: {chart_settings: chartdatajson},
					//data: {chart_settings: chartdatastring},
					//dataType : 'json',
					
					beforeSend:function()
					{
						//launchpreloader();
					},
					complete:function()
					{
						//stopPreloader();
					},
					success:function(result)
					{
						 //alert(result);
						 var newchartdata = $.parseJSON(result);

						 alert(newchartdata.newchartid);
						 
						 var newchartid = newchartdata.newchartid;
						 
						 $("#saveformpopup").modal('hide');
						 navigateurl('assets/ajax/charting.php?chart_id='+newchartid+'&'+Math.random(),'Charting');
						 //alert("Chart Saved Successfully");
					}
				});
			});
				
			//}
			<?php if ($chart_id > 0) {?>
				//getChartCheckboxes();
				set_db_settings();
			<?php } ?>
			
		  });
		  
		  
		 
		  
		  
		  
		  function set_db_settings() {
			  //alert('settting');
			$("#db_inprocess").val(1);
			 
			var amChartjsArrDB = <?php echo json_encode($form_settings); ?>;			
			//alert(amChartjsArrDB.length);
			var chartInputArr = ['phh_title','pvv_title','svv_title'];
			var chartcheckArr = [];
			
			getChartCheckboxes(chartcheckArr);
			//reset check boxes
			reset_chart_settings();
			
			$("input[name=phh_alLocation][value=inside]").prop('checked', true);
			
			for (var amChartKey in amChartjsArrDB) {
				
				var chartfield = $('.am_settings .exceltabs [name='+amChartKey+']');
				
				if ( chartfield.attr('type') == 'checkbox' ) {
					chartfield.prop( "checked", true );
				} else if ( chartfield.attr('type') == 'text' ) {
					chartfield.val(amChartjsArrDB[amChartKey]);
					if (amChartKey.includes("_color")) {						
						$('#cp_'+amChartKey).colorpicker('setValue', "#"+amChartjsArrDB[amChartKey]+"");
					} else if (amChartKey.includes("_bcolor")) {
						$('#cp_'+amChartKey).colorpicker('setValue', "#"+amChartjsArrDB[amChartKey]+"");
					}
					
				//} else if ( chartfield.attr('type') == 'radio' && amChartjsArrDB[amChartKey] == chartfield.val() ) {
				} else if ( chartfield.attr('type') == 'radio' ) {
					////console.log( "DB val=="+amChartjsArrDB[amChartKey]+"--input val=="+chartfield.val() );
					//$('input[name=[name='+amChartKey+']][value=" + value + "]").attr('checked', 'checked');
					//$('.am_settings .exceltabs [name='+amChartKey+'][value='+chartfield.val()+']').prop( "checked", true );
					//chartfield.prop( "checked", true );
					//chartfield.val("'"+[amChartjsArrDB[amChartKey]]+"'");
					$("input[name="+amChartKey+"][value="+amChartjsArrDB[amChartKey]+"]").prop('checked', true); 
				} else if ( chartfield.attr('type') == 'hidden' ) {
					console.log('#'+amChartKey+'_slider');
					if($('#'+amChartKey+'_slider').length){  
						dbhs=$('#'+amChartKey+'_slider').slider();
						var dbhs_inputval = amChartjsArrDB[amChartKey];
						var dbhs_sliderval = amChartjsArrDB[amChartKey];
						
						if (amChartKey.includes("transparency") && dbhs_inputval != 1) {
							//dbhs_val = 100-(dbhs_val*100); 
							//dbhs_val = 100 - Math.round(dbhs_val * 100);
							dbhs_sliderval = 100 - (100 - Math.round(dbhs_inputval * 100)); 
						}
						dbhs.slider('option', 'value',dbhs_inputval);
						dbhs.slider('option','slide').call(dbhs,null,{ handle: $('.ui-slider-handle', dbhs), value: dbhs_sliderval });
						//chartfield.val();
					}
				}
				
				//console.log(amChartKey+"=="); 
				//console.log(chartfield);
				
				continue;
				
				if (amChartjsArrDB.hasOwnProperty(amChartKey)) {
					
					if(chartInputArr.indexOf(amChartKey) !== -1){
						//input field
						$('.am_settings input[name='+amChartKey+']').val(amChartjsArrDB[amChartKey]);
						
					} else if(chartcheckArr.indexOf(amChartKey) !== -1 && (amChartjsArrDB[amChartKey] == "on" || amChartjsArrDB[amChartKey] == 1) ){
						console.log('input[name='+amChartKey+']');
						//checkbox
						$('.am_settings input[name='+amChartKey+']').prop( "checked", true );
						//$('.am_settings input[name='+amChartKey+']').val(amChartjsArrDB[amChartKey]);
					}
					//console.log(amChartKey + " -> " + amChartjsArrDB[amChartKey]);
				}
			}
			
			//$('#cp_phha_color').colorpicker({color : "#ffffff"});
			////$('#cp_phha_color').colorpicker('setValue', "#ffffff");
			/*
			for (i=0; i<amChartjsArrDB.length; i++) {
				//yourValue=fromPHP[i];
				var amChartKey = amChartjsArrDB[i].Key;
				var amChartValue = amChartjsArrDB[i];
				alert(amChartKey);
				//console.log("key="+amChartKey);
			}
			*/
			$("#db_inprocess").val(0);
		  }
		  
		  function reset_chart_settings() {
			/*
			var resetCheckArr = ['phh_label_enable','pvv_label_enable','svv_label_enable'];
			for (var checkKey in resetCheckArr) {
				$('.am_settings input[name='+resetCheckArr[checkKey]+']').prop( "checked", false );
			}
			*/
			// reset all checkboxes
			$('.am_settings .exceltabs input:checkbox').removeAttr('checked');
			$('.am_settings .exceltabs input:radio').removeAttr('checked');
		  }
		  
		  function getChartCheckboxes (chartcheckArr) {
			  //var selected = [];
			$('.am_settings .exceltabs input[type=checkbox]').each(function() {
			   //if ($(this).is(":checked")) {
				   chartcheckArr.push($(this).attr('name'));
			   //}
			});
			
			console.log(chartcheckArr);
		  }
		  
		  
      </script>
	  