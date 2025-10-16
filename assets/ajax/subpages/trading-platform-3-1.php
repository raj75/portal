<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];


if(isset($_GET["action"]) and $_GET["action"]=="iso" and isset($_GET["iso"]) and !empty($_GET["iso"])){
	$trand=rand(20,500)."tp3t".rand(20,500);
	$isotype=urldecode($_GET["iso"]);
	if(isset($_GET["innertable"]) and !empty($_GET["innertable"]))$innertablect=$_GET["innertable"];else $innertablect=1;
?>
<link href="<?php echo "https://".$_SERVER['HTTP_HOST']; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_column3-1<?php echo $trand; ?>_filter, #datatable_fixed_column3-1<?php echo $trand; ?>-4_filter,.dataTables_filter{
float: left !important;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column3-1<?php echo $trand; ?>_length,#datatable_fixed_column3-1<?php echo $trand; ?>-4_length,.dataTables_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column3-1<?php echo $trand; ?>,#datatable_fixed_column3-1<?php echo $trand; ?>-4,.innerdtable{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column3-1<?php echo $trand; ?> option,#datatable_fixed_column3-1<?php echo $trand; ?>-4 option,.innerdtable option {
  color: #555;
}
#datatable_fixed_column3-1<?php echo $trand; ?> tr.dropdown select, #datatable_fixed_column3-1<?php echo $trand; ?>-4  tr.dropdown select,.innerdtable  tr.dropdown select{font-weight: 400 !important;}
.red{color:red;display:none;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}
.clickit {
    cursor: pointer;
}
.stable, .stable th,.stable td {
  border: 1px solid black !important;
}
.stable th {
  background-color:#BDBDBD !important;
}
.toggleclose{float:right !important;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2<?php echo $trand; ?>" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2><?php echo $isotype; ?> </h2>

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
						<table id="datatable_fixed_column3-1<?php echo $trand; ?>" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr class="dropdown">
									<th class="hasinput d-0"></th>
									<th class="hasinput d-1">
										<input type="text" class="form-control" placeholder="Filter Symbol" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Description" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter TOU" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Trading Point" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Date Code Min" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Date Code Max" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Max Date" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Contracts" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Spot Contract" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Spot Price" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter 12 Strip" />
									</th>
									<th class="hasinput d-5">
										<select class="form-control tpdrp tp5">
											<option value="">Filter Status</option>
											<option value="Active">Active</option>
											<option value="Inactive">Inactive</option>
										</select>
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Hyperlink" />
									</th>
								</tr>
								<tr>
									<th data-hide="phone,tablet"></th>
									<th data-hide="phone">Symbol</th>
									<th data-hide="phone">Description</th>
									<th data-hide="phone">TOU</th>
									<th data-hide="phone">Trading Point</th>
									<th data-hide="phone">Date Code Min</th>
									<th data-hide="phone">Date Code Max</th>
									<th data-hide="phone">Max Date</th>
									<th data-hide="phone">Contracts</th>
									<th data-hide="phone">Spot Contract</th>
									<th data-hide="phone">Spot Price</th>
									<th data-hide="phone">12 Strip</th>
									<th data-hide="phone,tablet">Status</th>
									<th data-hide="phone">Hyperlink</th>
								</tr>
							</thead>
							<tbody>


<?php
	$symlist=$symarray=array();
	if($isotype == "All") $subquery=""; else $subquery=" AND ISO='".$mysqli->real_escape_string($isotype)."' ";
	$sql="SELECT DISTINCT IF (clearing_code IS NULL,Region,clearing_code) AS clearing_code,Description,TOU,TradingPoint,date_code_min,date_code_max,max_date,contracts,spot_contract,spot_price,12_strip,HYPERLINK,status
FROM (SELECT Region,clearing_code,Description,TOU,TradingPoint,date_code_min,date_code_max,max_date,contracts,spot_contract,spot_price,12_strip,HYPERLINK,ISO,`GROUP`,Product,contract_type,status FROM ubm_ice.clearing_code
UNION ALL
SELECT DISTINCT Region,NULL AS clearing_code,NULL AS Description,NULL AS TOU,NULL AS TradingPoint,NULL AS date_code_min,NULL AS date_code_max,NULL AS max_date,NULL AS contracts,NULL AS spot_contract,NULL AS spot_price,NULL AS 12_strip,NULL AS HYPERLINK,ISO,`GROUP`,Product,contract_type,status FROM ubm_ice.clearing_code) a
WHERE (`GROUP`='Electricity' OR `GROUP`='Physical Environmental') AND Product <> 'Real-Time' AND contract_type='Monthly'
 ".$subquery." ORDER BY a.Region, a.TradingPoint, a.TOU";
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
					$cct=1;
			$stmt->bind_result($tpclearing_code,$tpDescription,$tpTOU,$tpTradingPoint,$tpdate_code_min,$tpdate_code_max,$tpmax_date,$tpcontracts,$tpspot_contract,$tpspot_price,$tp12_strip,$tpHYPERLINK,$status);
			while($stmt->fetch()) {if(empty($tpclearing_code)) continue;
?>
							<tr>
								<td><?php if($tpdate_code_min != ""){ ?><i class="clickit glyphicon glyphicon-chevron-right" id="i3id<?php echo $cct; ?>"></i><?php } ?></td>
								<td><?php echo $tpclearing_code; ?></td>
								<td><?php echo $tpDescription; ?></td>
								<td><?php echo $tpTOU; ?></td>
								<td><?php echo $tpTradingPoint; ?></td>
								<td><?php echo $tpdate_code_min; ?></td>
								<td><?php echo $tpdate_code_max; ?></td>
								<td><?php echo $tpmax_date; ?></td>
								<td><?php echo $tpcontracts; ?></td>
								<td><?php echo $tpspot_contract; ?></td>
								<td><?php echo $tpspot_price; ?></td>
								<td><?php echo $tp12_strip; ?></td>
								<td><?php echo $status; ?></td>
								<td><a href="<?php echo $tpHYPERLINK; ?>" onclick="window.open(this.href,'popUpWindow','height=600,width=800,left=10,top=10,,scrollbars=yes,menubar=no'); return false;"><?php echo $tpHYPERLINK; ?></a></td>
							</tr>
<?php
				++$cct;
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
	var tmpno=0;
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
			var otable<?php echo $trand; ?> = $("#datatable_fixed_column3-1<?php echo $trand; ?>").DataTable( {
				"lengthMenu": [[6, 25, 50, 100, 500, -1], [6, 25, 50, 100, 500, "All"]],
				"pageLength": -1,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"columns": [
					{
						"orderable":      false
					},
					{ "data": "Clearing Code" },
					{ "data": "Description" },
					{ "data": "TOU" },
					{ "data": "Trading Point" },
					{ "data": "Date Code Min" },
					{ "data": "Date Code Max" },
					{ "data": "Max Date" },
					{ "data": "Contracts" },
					{ "data": "Spot Contract" },
					{ "data": "Spot Price" },
					{ "data": "12 Strip" },
					{ "data": "Status" },
					{ "data": "Hyperlink" }
				],
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
	    $("#datatable_fixed_column3-1<?php echo $trand; ?> thead th input[type=text]").on( 'keyup change', function () {

	        otable<?php echo $trand; ?>
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });

			$('#datatable_fixed_column3-1<?php echo $trand; ?>').off('keyup change', '.tp5');
			$('#datatable_fixed_column3-1<?php echo $trand; ?>').on('keyup change', '.tp5', function () {

				otable<?php echo $trand; ?>
						.column( $(this).parent().index()+':visible' )
						.search( this.value ? '^' + this.value + '$' : '', true, false)
						.draw();
			});

		$('#datatable_fixed_column3-1<?php echo $trand; ?> tbody').off('click', '.clickit');
		$('#datatable_fixed_column3-1<?php echo $trand; ?> tbody').on('click', '.clickit', function () {
			var tthis=$(this);
			var thisid=tthis.attr('id');
			var tr = tthis.closest('tr');
			var row = otable<?php echo $trand; ?>.row( tr );
			var randno=Math.floor(Math.random()*6)+1;

			parent.$("#tpchartcont3<?php echo $innertablect; ?>-3dtable").html('');
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				//destroyChild(row);
				/*if (isDataTable(otable<?php echo $trand; ?>s)) {
					otable<?php echo $trand; ?>s.detach();
					otable<?php echo $trand; ?>s.DataTable().destroy();
				}*/
				if(otable<?php echo $trand; ?>s) {
					otable<?php echo $trand; ?>s.detach();
					otable<?php echo $trand; ?>s.destroy();

				}
				//var otable<?php echo $trand; ?>s = $("#datatable_fixed_column3-1<?php echo $trand; ?>-4", row.child());
				//otable<?php echo $trand; ?>s.detach();
				//otable<?php echo $trand; ?>s.DataTable().destroy();
				tr.removeClass('shown');
				tthis.removeClass('glyphicon-chevron-down');
				tthis.addClass('glyphicon-chevron-right');
			}
			else {
				var oarr=row.data();
				// Open this row
				row.child( format(oarr,randno,thisid)).show();
				//createChild(row, 'child-table');
				tr.addClass('shown');
				tthis.removeClass('glyphicon-chevron-right');
				tthis.addClass('glyphicon-chevron-down');
//alert("#datatable_fixed_column3-1<?php echo $trand; ?>-4"+randno);

				var otable<?php echo $trand; ?>s = $("#datatable_fixed_column3-1<?php echo $trand; ?>-4"+randno).DataTable( {
					"lengthMenu": [[6, 12, 24, 36, 48, 60, -1], [6, 12, 24, 36, 48, 60, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"ajax": {
						"url": "assets/ajax/subpages/trading-platform-3-1-serverside.php?req=pjm&cc="+oarr["Clearing Code"]
					},
					"rowId": '17',
					"columnDefs": [
						{ "visible": false, "targets": 17 }
					],
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
					"autoWidth" : true/*,
					rowId: function(a) {//alert(a);
						return 'id_1';// + a.uid;
					}*/
				});
				var innerstablearr=[];
				otable<?php echo $trand; ?>s.on( 'click', 'tr', function () {
					var rthis=$(this);
					rthis.toggleClass('selected');
					var cc=rthis.attr("id");

					innerstablearr = innerstablearr.filter(function(item) {
						return item !== cc;
					});

					if(rthis.hasClass( "selected" )){
						innerstablearr.push(cc);
					}

					//$("#resultitable").remove( );
					$("#resultichart").remove( );
					//var tsrc="assets/ajax/subpages/trading-platform-3-1-1.php?req=innerstable&cc="+innerstablearr.join(',');
					var csrc="assets/ajax/subpages/trading-platform-3-1-1.php?req=innerschart&cc="+innerstablearr.join(',');
					rthis.parents('.innerddiv').after('<div id="resultichart"><iframe width="100%" height="425px" src="'+csrc+'"></iframe></div>');
					//rthis.parents('.innerddiv').after('<div id="resultitable"></div>');
					//$("#resultitable").load("assets/ajax/subpages/trading-platform-3-1-1.php?req=innerstable&cc="+innerstablearr.join(','), function() {
					//});
				} );

			}
		} );
	};

	function createChild ( row ) {
		$( ".inner" ).after( "<p>Test</p>" );
		}
	function destroyChild(row) {
		var dtable = $("table", row.child());
		dtable.detach();
		dtable.DataTable().destroy();

		// And then hide the row
		row.child.hide();
	}
//}

	function format ( d ,randno, tid) {//alert(JSON.stringify(d));
		tmpno=tmpno+1;
		return '<div class="jarviswidget jarviswidget-color-blueDark innerddiv" id="wid-id-312'+randno+'" data-widget-editbutton="false"><header><span class="widget-icon"> <i class="fa fa-table"></i> </span><h2> </h2><div class="jarviswidget-ctrls" role="menu"><a href="javascript:void(0);" class="button-icon toggleclose tp4itable" onclick="closeitable(\''+tid+'\')" rel="tooltip" title="" data-placement="bottom" data-original-title="Close"><i class="fa fa-times"></i></a></div></header><div><div class="jarviswidget-editbox"></div><div class="widget-body no-padding"><table id="datatable_fixed_column3-1<?php echo $trand; ?>-4'+randno+'" class="display table table-striped table-bordered table-hover innerdtable" width="100%"><thead><tr><th>Expiry</th><th>Year</th><th>Month</th><th>Day</th><th>Settlement</th><th>Range</th><th>Last Change</th><th>Last %Change</th><th>1mo Range</th><th>1mo Change</th><th>1mo %Change</th><th>1qtr Range</th><th>1qtr Change</th><th>1qtr %Change</th><th>1yr Range</th><th>1yr Change</th><th>1yr %Change</th><th></th></tr></thead></table></div></div></div>';
	}

	function multifilter(nthis,fieldname,otable<?php echo $trand; ?>)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otable<?php echo $trand; ?>
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#datatable_fixed_column3-1<?php echo $trand; ?> tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}

	function showchart(sid){
		alert("UnderConstruction!");
	}

	function deleteportfolio(sid){
		alert("UnderConstruction!");
	}

	function addpjm(sid){
		parent.$("#tpresponse2").css("display", "none");
		parent.$("#tpchartcont2").css("display", "none");
		parent.$("#tpresponse").css("display", "block");
		parent.$("#tpchartcont").css("display", "block");
		parent.$('#tpresponse').html('');
		loadURL("assets/ajax/subpages/trading-platform-1.php?action=symbollist&sid="+sid, $('#tpresponse'));
	}

	function closeitable(ttid){//alert(ttid);
		$("#"+ttid).trigger('click');
	}
$(document).ready(function(){
	$("#addtoportfolio").click(function() {
		parent.$('#button5').trigger('click');

	});
});

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>





<?php
}
?>
