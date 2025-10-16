<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];

if($_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

//$fmt = numfmt_create( 'de_DE', NumberFormatter::DECIMAL );
//echo numfmt_format($fmt, 1234567.891234567890000)."\n";
setlocale(LC_MONETARY,"en_US");
//setlocale(LC_MONETARY,"en_US.UTF-8");
//setlocale(LC_MONETARY,"en_US.ISO-8559-1");
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
		#datatable_fixed_column3_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.9% auto !important;
		}
		#datatable_fixed_column3_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#datatable_fixed_column3{border-bottom: 1px solid #ccc !important;}}
		</style>
				<!--<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
					<h2> Saving Analysis </h2>
				</header>

				<div>-->
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<table id="datatable_fixed_column3" class="table table-striped table-bordered table-hover table-responsive" width="100%">
							<thead>
								<tr>
									<th>Description</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
								</tr>
							</thead>
							<tbody>
<?php
	$stmtk = $mysqli->prepare("SELECT Description,Name,Email,Phone FROM contact_us");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//("SELECT sa.id,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read FROM saving_analysis sa,company c, user u WHERE sa.company_id=c.company_id and c.company_id=u.company_id and u.id= '".$user_one."' ORDER BY sa.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($Description,$Name,$Email,$Phone);
		while($stmtk->fetch()){
			//echo'<tr '.($sa_Read == "N"?'style="font-weight:bold;"':'').' onclick="loadsamenu('.$saID.')" class="putcursor" title="View">';
?>
			<tr>
				<td>
					<?php echo $Description;?>
				</td>
				<td>
					<?php echo $Name;?>
				</td>
				<td>
					<?php echo $Email;?>
				</td>
				<td>
					<?php echo $Phone;?>
				</td>
			</tr>
<?php
		}
	}
?>
							</tbody>
						</table>
					</div>
				<!--</div>-->
<script type="text/javascript">
var otable1;

/*
 $(otable1.table().container()).on('click', '#example-page-length a', function(){
       //otable1.page.len($(this).text()).draw();
	   otable1.page.len(12).draw();
    });
*/

function loadsamenu(said) {
	parent.$('#firesponse').html('');
    parent.$('#firesponse').load('assets/ajax/saving_analysis_add.php?action=view&said='+said+'&type=unread');
	parent.$('#wid-id-2-1').load('assets/ajax/dashboard_sa.php?rnd='+Math.random());
}
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

			otable1 = $("#datatable_fixed_column3").DataTable( {
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
				columnDefs: [ { type: 'date', 'targets': [6] } ],
				order: [[ 6, 'desc' ]],
				"autoWidth" : true
			});
	    /*var otable = $('#datatable_fixed_column').DataTable({
			 "iDisplayLength": 5,
	    	"bFilter": false,
	    	"bLengthChange": false,
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			}

	    });*/

		};

	//var pagedestroy = function(){}

	loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
		pagefunction();

		var sacontent = $("#a_wid-id-31").find("div[role='content']");

		if (sacontent.hasClass( "set_height" ) == true) {
			otable1.page.len(12).draw();
		}

	});
</script>
