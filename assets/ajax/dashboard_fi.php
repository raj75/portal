<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];

if($_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");
?>
<style>
.vertical{
    //writing-mode:tb-rl;
    -webkit-transform:rotate(90deg);
    -moz-transform:rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform:rotate(90deg);
    transform: rotate(90deg);
    white-space:nowrap;
    display:block;
	height: 88px;
	width: 189px;
	margin-top: 50px;
	background-color:#000;
	color:#fff;
}
.widget-height{height:304px;}
.round-btn{border-radius: 24px;}
.vcenter {
    /*display: inline-block;
    vertical-align: middle;*/
}
.fmfolders{
	text-decoration:none;
	padding:40px;
}
.fmfolders img{
	padding-bottom:26px;
}
.margin-bottom-30{
	margin-bottom: 18px;
    margin-top: 18px;
}
.marginbottom-14{
	margin-bottom:14px;
}
.margintop{
	margin-top:-4px;
}
#content{
	opacity:1 !important;
}
</style>
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
		<style>
		#datatable_fixed_column2_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.9% auto !important;
		}
		#datatable_fixed_column2_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#datatable_fixed_column2{border-bottom: 1px solid #ccc !important;}}
		</style>
				<!--<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2> Focus Items </h2>
				</header>-->
				<!--<div>-->
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<table id="datatable_fixed_column2" class="table table-striped table-bordered table-hover table-responsive" width="100%" data-turbolinks="false">
							<thead>
								<tr>
									<th class="hasinput">
										<select id="selectcategory" name="selectcategory" class="form-control">
											<option value="">Filter Category</option>
											<option value="1">Fixed Price</option>
											<option value="2">Index/Basis + Adder</option>
											<option value="3">Heat Rate</option>
											<option value="4">Hedge Block</option>
											<option value="5">Blend and Extend</option>
											<option value="6">Rate and Tariff Analysis</option>
											<option value="7">Procurement Recommendation</option>
											<option value="8">Budget Report</option>
											<option value="9">Meeting Agenda</option>
											<option value="10">New Market Summary</option>
											<option value="11">Others</option>
										</select>
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Description" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Date Added" />
									</th>
								</tr>
								<tr>
									<th>Category</th>
									<th>Description</th>
									<th>Date Added</th>
								</tr>
							</thead>
							<tbody>
<?php
	$stmtk = $mysqli->prepare("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.user_id = '".$user_one."' and up.company_id = fi.company_id ORDER BY fi.id DESC");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.id = '".$user_one."' and up.company_id = fi.company_id ORDER BY fi.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($fiID,$fiCompanyID,$fiCategory,$fiDescription,$dateadded,$fiRead);
		while($stmtk->fetch()){
			echo'<tr '.($fiRead == "N"?'style="font-weight:bold;"':'').' onclick="loadfimenu('.$fiID.')" class="putcursor" title="View">
				<td>';
			if($fiCategory==1){echo "Fixed Price";}
			elseif($fiCategory==2){echo "Index/Basis + Adder";}
			elseif($fiCategory==3){echo "Heat Rate";}
			elseif($fiCategory==4){echo "Hedge Block";}
			elseif($fiCategory==5){echo "Blend and Extend";}
			elseif($fiCategory==6){echo "Rate and Tariff Analysis";}
			elseif($fiCategory==7){echo "Procurement Recommendation";}
			elseif($fiCategory==8){echo "Budget Report";}
			elseif($fiCategory==9){echo "Meeting Agenda";}
			elseif($fiCategory==10){echo "New Market Summary";}
			elseif($fiCategory==11){echo "Others";}

			echo '</td>
				<td>'.$fiDescription.'</td>
				<td>'.$dateadded.'</td>
			</tr>';
		}
	}
?>
							</tbody>
						</table>
					</div>
				<!--</div>-->
<script type="text/javascript">
//var otable2;
var otable_fi;
function loadfimenu(fiid) {
	parent.$('#firesponse').html('');
    parent.$('#firesponse').load('assets/ajax/focus_items_add.php?action=view&fiid='+fiid+'&type=unread');
	//$('#wid-id-3-1').load('assets/ajax/dashboard_fi.php?rnd='+Math.random());
}
/*function loadsamenu(said) {
	$('#firesponse').html('');
    $('#firesponse').load('assets/ajax/saving_analysis_add.php?action=view&said='+said+'&type=unread');
}*/
	pageSetUp();

	var pagefunction = function() {
		/*var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;*/

		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

			//otable2 = $("#datatable_fixed_column2").DataTable( {
			otable_fi = $("#datatable_fixed_column2").DataTable( {
				"lengthMenu": [[6,12, 25, -1], [6,12, 25, "All"]],
				"pageLength": 6,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"dom": 'Blfrtip',
				"buttons": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					},
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
				],
				columnDefs: [ { type: 'date', 'targets': [2] } ],
				order: [[ 2, 'desc' ]],
				"autoWidth" : true
			});
			
			otable_fi.on('click', 'tbody tr', (e) => {
				let classList = e.currentTarget.classList;
			 
				if (classList.contains('selected')) {
					classList.remove('selected');
				}
				else {
					otable_fi.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
					classList.add('selected');
				}
			});
			// de select row when popup close
			
			
			$(document).on("click","#view-fi-cancel,.ui-dialog-titlebar-close",function() {
				otable_fi.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
			});
		/*$('#datatable_fixed_column2').DataTable({
				 "iDisplayLength": 5,
				"bFilter": false,
				"bLengthChange": false,
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column2'), breakpointDefinition);
					}
				}

			});*/
			// Apply the filter
			$("#datatable_fixed_column2 thead th input[type=text]").on( 'keyup change', function () {

				otable_fi
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

			});
			
			$("#datatable_fixed_column2 thead th #selectcategory").on( 'keyup change', function () {
				$thisfi=$(this);
				var categoryfi=$('#selectcategory').find(":selected").text();
				otable_fi
					.column( $thisfi.parent().index()+':visible' )
					.search( categoryfi )
					.draw();
				if(categoryfi=="Filter Category"){
					otable_fi
						.column( $thisfi.parent().index()+':visible' )
						.search( "" )
						.draw();					
				}
			});
		};

	//var pagedestroy = function(){}
	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>
