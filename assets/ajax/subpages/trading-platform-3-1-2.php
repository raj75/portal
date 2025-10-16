<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(isset($_GET["action"]) and $_GET["action"]=="pjm"){
?>
<link href="<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_column3-1_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column3-1_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column3-1{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column3-1 option {
  color: #555;
}
#datatable_fixed_column3-1 tr.dropdown select{font-weight: 400 !important;}
.red{color:red;display:none;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Electricity </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding symlist">
						<table id="datatable_fixed_column3-1" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr class="dropdown">
									<th class="hasinput d-0">
										<input type="text" class="form-control" placeholder="Expiry" /></th>
									<th class="hasinput d-1">
										<input type="text" class="form-control" placeholder="Filter Year" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Month" />
									</th>
									<th class="hasinput d-3">
										<input type="text" class="form-control" placeholder="Filter Day" />
									</th>
									<th class="hasinput d-4">
										<input type="text" class="form-control" placeholder="Filter Settlement" />
									</th>
									<th class="hasinput d-5">
										<input type="text" class="form-control" placeholder="Filter Range" />
									</th>
									<th class="hasinput d-6">
										<input type="text" class="form-control" placeholder="Filter Last Change" />
									</th>
									<th class="hasinput d-7">
										<input type="text" class="form-control" placeholder="Filter Last %Change" />
									</th>
									<th class="hasinput d-8">
										<input type="text" class="form-control" placeholder="Filter 1mo Range" />
									</th>
									<th class="hasinput d-9">
										<input type="text" class="form-control" placeholder="Filter 1mo Change" />
									</th>
									<th class="hasinput d-10">
										<input type="text" class="form-control" placeholder="Filter 1mo %change" />
									</th>
									<th class="hasinput d-11">
										<input type="text" class="form-control" placeholder="Filter 1qtr Range" />
									</th>
									<th class="hasinput d-12">
										<input type="text" class="form-control" placeholder="Filter 1qtr Change" />
									</th>
									<th class="hasinput d-13">
										<input type="text" class="form-control" placeholder="Filter 1qtr %Change" />
									</th>
									<th class="hasinput d-14">
										<input type="text" class="form-control" placeholder="Filter 1yr Range" />
									</th>
									<th class="hasinput d-15">
										<input type="text" class="form-control" placeholder="Filter 1yr Change" />
									</th>
									<th class="hasinput d-16">
										<input type="text" class="form-control" placeholder="Filter 1yr %Change" />
									</th>
								</tr>
								<tr>
									<th data-hide="phone,tablet">Expiry</th>
									<th data-hide="phone">Year</th>
									<th data-hide="phone">Month</th>
									<th data-hide="phone">Day</th>
									<th data-hide="phone">Settlement</th>
									<th data-hide="phone">Range</th>
									<th data-hide="phone">Last Change</th>
									<th data-hide="phone">Last %Change</th>
									<th data-hide="phone">1mo Range</th>
									<th data-hide="phone">1mo Change</th>
									<th data-hide="phone">1mo %change</th>
									<th data-hide="phone">1qtr Range</th>
									<th data-hide="phone,tablet">1qtr Change</th>
									<th data-hide="phone,tablet">1qtr %Change</th>
									<th data-hide="phone,tablet">1yr Range</th>
									<th data-hide="phone,tablet">1yr Change</th>
									<th data-hide="phone,tablet">1yr %Change</th>
								</tr>
							</thead>
							<tbody>

<?php
	$symlist=$symarray=array();
	$sql='SELECT
a.expiry,a.`Year`,a.`Month`,a.`Day`,
	CONCAT("$",ROUND(a.settlement,2)) AS settlement,

	CONCAT("$",ROUND(a.range_min,2),"-",ROUND(a.range_max,2)) AS `range`,
	CONCAT("$",ROUND(a.settlement-a.last_settlement,2)) AS last_change,
	CONCAT(ROUND(((a.settlement-a.last_settlement)/a.last_settlement)*100,2),"%") AS `last_%change`,

	CONCAT("$",ROUND(a.1mo_min,2), "-",	ROUND(a.1mo_max,2)) AS 1mo_range,
	CONCAT("$",ROUND(a.settlement-a.1mo_settlement,2)) AS 1mo_change,
	CONCAT(ROUND(((a.settlement-a.1mo_settlement)/a.1mo_settlement)*100,2),"%") AS `1mo_%change`,

	CONCAT("$",ROUND(a.1qtr_min,2), "-",	ROUND(a.1qtr_max,2)) AS 1qtr_range,
	CONCAT("$",ROUND(a.settlement-a.1qtr_settlement,2)) AS 1qtr_change,
	CONCAT(ROUND(((a.settlement-a.1qtr_settlement)/a.1qtr_settlement)*100,2),"%") AS `1qtr_%change`,

	CONCAT("$",ROUND(a.1yr_min,2), "-",	ROUND(a.1yr_max,2)) AS 1yr_range,
	CONCAT("$",ROUND(a.settlement-a.1yr_settlement,2)) AS 1yr_change,
	CONCAT(ROUND(((a.settlement-a.1yr_settlement)/a.1yr_settlement)*100,2),"%") AS `1yr_%change`
FROM
	ubm_ice.clearing_code_index a
	WHERE a.clearing_code="NPM" and a.`status`="Active"
ORDER BY a.`Year`,a.`Month`,a.`Day`';
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($tpsExpiry,$tpsYear,$tpsMonth,$tpsDay,$tpsSettlement,$tpsRange,$tpsLast_change,$tpsLast_pchange,$tps1mo_range,$tps1mo_change,$tps1mo_pchange,$tps1qtr_range,$tps1qtr_change,$tps1qtr_pchange,$tps1yr_range,$tps1yr_change,$tps1yr_pchange);
			while($stmt->fetch()) {
?>
							<tr>
								<td><?php echo $tpsExpiry; ?></td>
								<td><?php echo $tpsYear; ?></td>
								<td><?php echo $tpsMonth; ?></td>
								<td><?php echo $tpsDay; ?></td>
								<td><?php echo $tpsSettlement; ?></td>
								<td><?php echo $tpsRange; ?></td>
								<td><?php echo $tpsLast_change; ?></td>
								<td><?php echo $tpsLast_pchange; ?></td>
								<td><?php echo $tps1mo_range; ?></td>
								<td><?php echo $tps1mo_change; ?></td>
								<td><?php echo $tps1mo_pchange; ?></td>
								<td><?php echo $tps1qtr_range; ?></td>
								<td><?php echo $tps1qtr_change; ?></td>
								<td><?php echo $tps1qtr_pchange; ?></td>
								<td><?php echo $tps1yr_range; ?></td>
								<td><?php echo $tps1yr_change; ?></td>
								<td><?php echo $tps1yr_pchange; ?></td>
							</tr>
<?php
			}
		}
	}
?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
<script type="text/javascript">

	pageSetUp();


	// pagefunction
	var pagefunction = function() {
		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otable = $("#datatable_fixed_column3-1").DataTable( {
				"lengthMenu": [[6, 12, -1], [6, 12, "All"]],
				"pageLength": 6,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				//"order": [[ 6, "desc" ]],
				"order": [],
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
					}/*,
					{
						'text': 'Columns',
						'extend': 'colvis',
						'columns': ':gt(0)'
					}*/
				],
				"autoWidth" : true
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column3-1 thead th input[type=text]").on( 'keyup change', function () {

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
		$('#datatable_fixed_column3-1 tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>





<?php
}
?>
