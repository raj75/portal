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
						<table id="datatable_fixed_column3" class="table table-striped table-bordered table-hover table-responsive" width="100%" data-turbolinks="false">
							<thead>
								<tr>
									<th>Location</th>
									<th>Category</th>
									<th>Commodity</th>
									<th>Start</th>
									<th>End</th>
									<th>Saving</th>
									<th>Date Added</th>
								</tr>
							</thead>
							<tbody>
<?php
	$stmtk = $mysqli->prepare("SELECT sa.id,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read, sa.date_added FROM saving_analysis sa,company c, user u WHERE sa.company_id=c.company_id and c.company_id=u.company_id and u.user_id= '".$user_one."' ORDER BY sa.id DESC");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//("SELECT sa.id,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read FROM saving_analysis sa,company c, user u WHERE sa.company_id=c.company_id and c.company_id=u.company_id and u.id= '".$user_one."' ORDER BY sa.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($saID,$sa_Location,$sa_Category,$sa_Commodity,$saStart,$saEnd,$saSaving,$saLink,$sa_Read,$sa_dateadded);
		while($stmtk->fetch()){
			echo'<tr '.($sa_Read == "N"?'style="font-weight:bold;"':'').' onclick="loadsamenu('.$saID.')" class="putcursor" title="View">';
?>
				<td>
				<?php if($sa_Location==1){echo "Alabama";}
				elseif($sa_Location==2){echo "Alaska";}
				elseif($sa_Location==3){echo "Arizona";}
				elseif($sa_Location==4){echo "Arkansas";}
				elseif($sa_Location==5){echo "California";}
				elseif($sa_Location==6){echo "Colorado";}
				elseif($sa_Location==7){echo "Connecticut";}
				elseif($sa_Location==8){echo "Delaware";}
				elseif($sa_Location==9){echo "Florida";}
				elseif($sa_Location==10){echo "Georgia";}
				elseif($sa_Location==11){echo "Hawaii";}
				elseif($sa_Location==12){echo "Idaho";}
				elseif($sa_Location==13){echo "Illinois";}
				elseif($sa_Location==14){echo "Indiana";}
				elseif($sa_Location==15){echo "Iowa";}
				elseif($sa_Location==16){echo "Kansas";}
				elseif($sa_Location==17){echo "Kentucky";}
				elseif($sa_Location==18){echo "Louisiana";}
				elseif($sa_Location==19){echo "Maine";}
				elseif($sa_Location==20){echo "Maryland";}
				elseif($sa_Location==21){echo "Massachusetts";}
				elseif($sa_Location==22){echo "Michigan";}
				elseif($sa_Location==23){echo "Minnesota";}
				elseif($sa_Location==24){echo "Mississippi";}
				elseif($sa_Location==25){echo "Missouri";}
				elseif($sa_Location==26){echo "Montana";}
				elseif($sa_Location==27){echo "Nebraska";}
				elseif($sa_Location==28){echo "Nevada";}
				elseif($sa_Location==29){echo "New Hampshire";}
				elseif($sa_Location==30){echo "New Jersey";}
				elseif($sa_Location==31){echo "New Mexico";}
				elseif($sa_Location==32){echo "New York";}
				elseif($sa_Location==33){echo "North Carolina";}
				elseif($sa_Location==34){echo "North Dakota";}
				elseif($sa_Location==35){echo "Ohio";}
				elseif($sa_Location==36){echo "Oklahoma";}
				elseif($sa_Location==37){echo "Oregon";}
				elseif($sa_Location==38){echo "Pennsylvania";}
				elseif($sa_Location==39){echo "Rhode Island";}
				elseif($sa_Location==40){echo "South Carolina";}
				elseif($sa_Location==41){echo "South Dakota";}
				elseif($sa_Location==42){echo "Tennessee";}
				elseif($sa_Location==43){echo "Texas";}
				elseif($sa_Location==44){echo "Utah";}
				elseif($sa_Location==45){echo "Vermont";}
				elseif($sa_Location==46){echo "Virginia[H]";}
				elseif($sa_Location==47){echo "Washington";}
				elseif($sa_Location==48){echo "West Virginia";}
				elseif($sa_Location==49){echo "Wisconsin";}
				elseif($sa_Location==50){echo "Wyoming";}
				?>
				</td>
				<td>
				<?php if($sa_Category==1){echo "Fixed Price";}
				elseif($sa_Category==2){echo "Index/Basis + Adder";}
				elseif($sa_Category==3){echo "Heat Rate";}
				elseif($sa_Category==4){echo "Hedge Block";}
				elseif($sa_Category==5){echo "Blend and Extend";}
				elseif($sa_Category==6){echo "Rate and Tariff Analysis";}
				elseif($sa_Category==7){echo "Procurement Recommendation";}
				elseif($sa_Category==8){echo "Budget Report";}
				elseif($sa_Category==9){echo "Meeting Agenda";}
				elseif($sa_Category==10){echo "New Market Summary";}
				?>
				</td>
				<td>
				<?php if($sa_Commodity==1){echo "Electricity";}
				elseif($sa_Commodity==2){echo "Natural Gas";}
				elseif($sa_Commodity==3){echo "Water";}
				elseif($sa_Commodity==4){echo "Fuel Oil";}
				elseif($sa_Commodity==5){echo "Trash";}
				?>
				</td>
<?php
				echo '<td>'.$saStart.'</td>
				<td>'.$saEnd.'</td>
				<td>$'.money_format("%!i", $saSaving).'</td>
				<td>'.$sa_dateadded.'</td>
			</tr>';
		}
	}
?>
							</tbody>
						</table>
					</div>
				<!--</div>-->
<script type="text/javascript">
//var otable1;
var otable_sa;

/*
 $(otable1.table().container()).on('click', '#example-page-length a', function(){
       //otable1.page.len($(this).text()).draw();
	   otable1.page.len(12).draw();
    });
*/

function loadsamenu(said) {
	parent.$('#firesponse').html('');
    parent.$('#firesponse').load('assets/ajax/saving_analysis_add.php?action=view&said='+said+'&type=unread');
	//parent.$('#wid-id-2-1').load('assets/ajax/dashboard_sa.php?rnd='+Math.random());
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

			//otable1 = $("#datatable_fixed_column3").DataTable( {
			otable_sa = $("#datatable_fixed_column3").DataTable( {
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
			
			otable_sa.on('click', 'tbody tr', (e) => {
				let classList = e.currentTarget.classList;
			 
				if (classList.contains('selected')) {
					classList.remove('selected');
				}
				else {
					otable_sa.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
					classList.add('selected');
				}
			});
			
			$(document).on("click","#view-fi-cancel,.ui-dialog-titlebar-close",function() {
				otable_sa.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
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
			//otable1.page.len(12).draw();
			otable_sa.page.len(12).draw();
		}
		
		// get height of datatable
		//alert( $("#datatable_fixed_column3").height() + 36 + 40 );
		

	});
</script>
