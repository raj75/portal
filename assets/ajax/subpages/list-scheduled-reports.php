<?php //require_once("inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

if(isset($_GET["load"])){
?>
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#schdatatable_fixed_column_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#schdatatable_fixed_column_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#schdatatable_fixed_column{border-bottom: 1px solid #ccc !important;}
#schdatatable_fixed_column .schdrp{width:auto !important;}
#schdatatable_fixed_column .schdrp {
    font-weight: 400 !important;
}
.
.flleft{float:left !important;}
</style>
<table id="schdatatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Ticket No." />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Scheduled Report Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Schedule Minute" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Schedule Hour" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Schedule Day" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Schedule Month" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Schedule Week Day" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Email" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Created By" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Lastname" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Created Date" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Next Run Time" />
			</th>
			<th class="hasinput">
				<select class="form-control schdrp" id="schdrp">
					<option value="" SELECTED>Filter Status</option>
					<option value="Active">Active</option>
					<option value="Inactive">Inactive</option>
				</select>
			</th>
			<th class="hasinput">
				<select class="form-control schdrp" id="schrundrp">
					<option value="" SELECTED>Filter Last Run Status</option>
					<option value="Pending">Pending</option>
					<option value="Running">Running</option>
					<option value="Complete">Complete</option>
					<option value="Failed">Failed</option>
				</select>
			</th>
			<th class="hasinput"></th>
		</tr>
		<tr>
			<th data-hide="phone,tablet">Ticket No.</th>
			<th data-hide="phone,tablet">Scheduled Report Name</th>
			<th data-hide="phone,tablet">Schedule Minute</th>
			<th data-hide="phone,tablet">Schedule Hour</th>
			<th data-hide="phone,tablet">Schedule Day</th>
			<th data-hide="phone,tablet">Schedule Month</th>
			<th data-hide="phone,tablet">Schedule Week Day</th>
			<th data-hide="phone,tablet">Email</th>
			<th data-hide="phone,tablet">Created By</th>
			<th data-hide="phone,tablet">Lastname</th>
			<th data-hide="phone,tablet">Created Date</th>
			<th data-hide="phone,tablet">Next Run Time</th>
			<th data-hide="phone,tablet">Status</th>
			<th data-hide="phone,tablet">Last Run Status</th>
			<th data-hide="phone,tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	//$('#sssdrp option[value="Active"]').attr('selected', 'selected');
	pageSetUp();

	// pagefunction
	var pagefunction = function() {

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
			var responsiveHelper_schdatatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};
			var fixNewLine = {
			    exportOptions: {
			        format: {
			            body: function ( data, column, row ) {
										var htmlstr = data;
										var divstr = document.createElement("div");
										divstr.innerHTML = htmlstr;
										return divstr.innerText;
			            }
			        }
			    }
			};
		/* COLUMN FILTER  */
	    var schotable = $('#schdatatable_fixed_column').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 100,
			"processing": true,
			"serverSide": true,
		"dom": 'Blfrtip',
		 "search": {
            "caseInsensitive": false
        },
        "buttons": [
					$.extend( true, {}, fixNewLine, {
							'extend': 'copyHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							'extend': 'excelHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							'extend': 'csvHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export',
						'orientation':'landscape'
					} ),
					$.extend( true, {}, fixNewLine, {
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
						'orientation':'landscape'
					} ),
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
        ],
        "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return data +' '+ row[9]+'';
                },
                "targets": 8
            }
		],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": ""
        },
			"autoWidth" : true,
			"ajax": "assets/ajax/subpages/scheduled_reports_server_processing.php"
	    });

		schotable.columns( [9] ).visible( false );
	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#schdatatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        schotable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );

	    $("#schdatatable_fixed_column .schdrp").on( 'keyup change', function () {
	        schotable
				.column( $(this).parent().index()+':visible' )
	            .search(this.value, true, true, false)

	            //.search(this.value, false,true,false)
				//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();

	    } );
	};

	function multifilter(nthis,fieldname,schotable)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			schotable
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#schdatatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
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

<?php if(isset($_SESSION["group_id"]) and $_SESSION["group_id"] == 1 and isset($_SESSION['user_id'])){?>
function loadsch(sid) {
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/subpages/scheduled-reports-add.php?edit=true&sid='+sid);
}

function deletesch(sid,sname) {
	$('#response').html('');
	var r = confirm("Do you want to delete Scheduled Report TicketID: "+sid+" ?");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: '../assets/includes/scheduled-report.inc.php',
			data: {sid:sid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						$("#response").html("");
						parent.$("#list-scheduled-reports").html('');
						$('#list-scheduled-reports').load("../assets/ajax/subpages/list-scheduled-reports.php?load=true&ct=<?php echo time(); ?>");
					}else
						alert("Error in request. Please try again later.");
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		});
	}
}
<?php } ?>
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
