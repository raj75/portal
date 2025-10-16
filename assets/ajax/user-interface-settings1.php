<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
$user_one = $_SESSION["user_id"];
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 5)
	die("Restricted Access1");
?>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:2%;">
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
		<style>
		#datatable_fixed_column_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.9% auto !important;
		}
		#datatable_fixed_column_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
		</style>
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>User Interface Settings </h2>

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

						<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<!--<tr id="multiselect">
									<th class="hasinput">
										<select id="selectCompany" name="selectCompany" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectDivision" name="selectDivision[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectState" name="selectState[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectCity" name="selectCity[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
									</th>
									<th class="hasinput">
									</th>
									<th class="hasinput">
										<select id="selectStatus" name="selectStatus[]" multiple="multiple"></select>
									</th>
								</tr>-->
								<tr>
<?php if($_SESSION["group_id"] == 1){?><th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter ID" />
									</th>
<?php } ?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Email" />
									</th>
<?php if($_SESSION["group_id"] == 1){?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter User Groups" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Gender" />
									</th>
<?php } ?>
									<th class="hasinput">
									</th>
								</tr>
								<tr>
<?php if($_SESSION["group_id"] == 1){?>
									<th data-hide="phone">ID</th>
<?php } ?>
									<th>Email</th>
<?php if($_SESSION["group_id"] == 1){?>
									<th data-hide="phone">User Groups <?php if(1==2){?><!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--><?php } ?></th>
									<th data-hide="phone,tablet">Gender <?php if(1==2){?><!--<select id="selectState" name="selectState[]" multiple="multiple"></select>--><?php } ?></th>
<?php } ?>
									<th data-hide="phone,tablet">Action <?php if(1==2){?><!--<select id="selectCity" name="selectCity[]" multiple="multiple"></select>--><?php } ?></th>
								</tr>
							</thead>
							<tbody>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if($_SESSION["group_id"] == 1)
	$sql='SELECT user_id,email,usergroups_id,gender,status FROM user';

//SELECT id,email,usergroups_id,gender,status FROM user';

else
	$sql='SELECT id,email,usergroups_id,gender,status FROM user where company_id=(SELECT company_id FROM user WHERE user_id= "'.$user_one.'")';

//SELECT id,email,usergroups_id,gender,status FROM user where company_id=(SELECT company_id FROM user WHERE id= "'.$user_one.'")';

	if ($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$Email,$Usergroups,$Gender,$Status);
			while($stmt->fetch()) {
			?>
				<tr>
<?php if($_SESSION["group_id"] == 1){?>
						<td><?php echo $id; ?></td>
<?php } ?>
						<td><?php echo $Email; ?></td>
<?php if($_SESSION["group_id"] == 1){?>
						<td><?php echo $Usergroups; ?></td>
						<td><?php echo $Gender; ?></td>
<?php } ?>
						<td><button class="btn-primary" onclick="loadusermenu(<?php echo $id; ?>)"><?php if($_SESSION["group_id"] == 1){?>Edit User Menu<?php }else{echo "Edit Access permissions";} ?></button></td>
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

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->
<div id="response"></div>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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
			var otable = $("#datatable_fixed_column").DataTable( {
				"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
				"pageLength": 12,
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
				"autoWidth" : true
			});
	    /*var otable = $('test-table-id').DataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'CT>r>"+
					"t"+
					"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
	        "oTableTools": {
	        	 "aButtons": [
	             "copy",
	             "csv",
	             "xls",
	                {
	                    "sExtends": "pdf",
	                    "sTitle": "Vervantis_PDF",
	                    "sPdfMessage": "Vervantis PDF Export",
	                    "sPdfSize": "letter"
	                },
	             	{
                    	"sExtends": "print",
                    	"sMessage": "Generated by Vervantis <i>(press Esc to close)</i>"
                	}
	             ],
	            "sSwfPath": "assets/js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
	        },
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}

	    });*/

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    /* END COLUMN FILTER */
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
		$('#datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}



	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);


</script>
<script>
function loadusermenu(userid) {
	$('#response').html('');
    $('#response').load('assets/ajax/custom-user-menu1.php?editmenu=true&userid='+userid);
}
</script>
