<?php
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}
$_SESSION["user_id"]=23;
$_SESSION["group_id"]=3;
if(!isset($_SESSION["user_id"]) and $_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION['user_id'];

$sql = "SELECT id, `Symbol`, `Description`, `Sub Group`, `Category`, `Sub Category`, `Volume`, `Open Interest`, `Contracts`, `Status` From futures.futureslist order by `Open Interest` DESC";
?>
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
#datatable_fixed_column option {
  color: #555;
}
#datatable_fixed_column tr.dropdown select{font-weight: 400 !important;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Futures </h2>

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
								<tr class="dropdown">
									<?php if(1==2){ ?><th class="hasinput d-1">
										<input type="text" class="form-control" placeholder="Filter Id" />
									</th><?php } ?>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Symbol" />
									</th>
									<th class="hasinput d-3">
										<input type="text" class="form-control" placeholder="Filter Description" />
									</th>
									<th class="hasinput d-4"></th>
									<th class="hasinput d-5"></th>
									<th class="hasinput d-6"></th>
									<th class="hasinput d-7">
										<input type="text" class="form-control" placeholder="Filter Volume" />
									</th>
									<th class="hasinput d-8">
										<input type="text" class="form-control" placeholder="Filter Open Interest" />
									</th>
									<th class="hasinput d-9">
										<input type="text" class="form-control" placeholder="Filter Contracts" />
									</th>
									<th class="hasinput d-10"></th>
								</tr>
								<tr>
									<?php if(1==2){ ?><th>Id</th><?php } ?>
									<th data-hide="phone">Symbol <?php if(1==2){?><!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Description <?php if(1==2){?><!--<select id="selectState" name="selectState[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Sub Group </th>
									<th data-hide="phone,tablet">Category </th>
									<th data-hide="phone,tablet">Sub Category </th>
									<th data-hide="phone,tablet">Volume</th>
									<th data-hide="phone,tablet">Open Interest</th>
									<th data-hide="phone,tablet">Contracts</th>
									<th data-hide="phone,tablet">Status</th>
								</tr>
							</thead>
							<tbody>
<?php
	if ($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($f_Id,$f_symbol,$f_description,$f_sub_group,$f_Category,$f_sub_category,$f_volume,$f_open_interest,$f_contracts,$f_status);
			while($stmt->fetch()) {
			?>
				<tr>
						<?php if(1==2){ ?><td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_Id; ?></a></td><?php } ?>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_symbol; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_description; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_sub_group; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_Category; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_sub_category; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_volume; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_open_interest; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_contracts; ?></a></td>
						<td><a href="javascript:void(0)" onclick="loadfview('<?php echo $f_symbol; ?>')"><?php echo $f_status; ?></a></td>
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

<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 *
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 *
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction
	var pagefunction = function() {
		//console.log("cleared");

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
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otable = $("#datatable_fixed_column").DataTable( {
				"lengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
				"pageLength": 100,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"order": [[ 6, "desc" ]],
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
						'extend': 'colvis',
						'columns': ':gt(0)'
					}
				],
				/*"columnDefs": [
						{
							"targets": [ 1 ],
							"visible": false,
							"searchable": false
						}
				],*/
				"autoWidth" : true,
				initComplete: function () {
					this.api().columns([8]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control" id="dstatid"><option value="">Filter Status</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-10').empty() )
							  .on( 'change', function () {
								   var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
								   );
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
							  } );
							  var darr = [];
							 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
									if(jQuery.inArray(d, darr) == -1 && d != ""){
										if(d=='Active'){
											select.append( '<option value="'+d+'" SELECTED>'+d+'</option>' );
										}else{
											select.append( '<option value="'+d+'">'+d+'</option>' );
										}
										darr.push(d);
									}
							 } );
							 val='Active';
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
					} );
					this.api().columns([2]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Sub Group</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-4').empty() )
							  .on( 'change', function () {
								   var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
								   );
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
							  } );
							  var darr = [];
							 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
									if(jQuery.inArray(d, darr) == -1 && d != ""){
										select.append( '<option value="'+d+'">'+d+'</option>' );
										darr.push(d);
									}
							 } );
					} );
					this.api().columns([3]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Category</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-5').empty() )
							  .on( 'change', function () {
								   var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
								   );
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
							  } );
							  var darr = [];
							 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
									if(jQuery.inArray(d, darr) == -1 && d != ""){
										select.append( '<option value="'+d+'">'+d+'</option>' );
										darr.push(d);
									}
							 } );
					} );
					this.api().columns([4]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Sub Category</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-6').empty() )
							  .on( 'change', function () {
								   var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
								   );
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
							  } );
							  var darr = [];
							 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
									if(jQuery.inArray(d, darr) == -1 && d != ""){
										select.append( '<option value="'+d+'">'+d+'</option>' );
										darr.push(d);
									}
							 } );
					} );

				}
			});
	    /*var otable = $('#datatable_fixed_column').DataTable({
			// "iDisplayLength": 5,
			//"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	    	//"bFilter": false,
	    	//"bInfo": false,
	    	//"bLengthChange": false,
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
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
		$('#datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}
$(document).ready(function(){
	//$('#dstatid').val('Active');
});
	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

function loadfview(symb){


	$('#ftable').hide();
	$('#fselect').html('');
    $('#fselect').load('assets/ajax/futures_details3.php?action=view&symb='+symb);

}
<?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
function loadfmenu(fid) {
	$('#fresponse').html('');
    $('#fresponse').load('assets/ajax/futures_add.php?action=view&fid='+fid<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "+'&type=unread'";} ?>);
}
<?php } ?>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
function loadfmenu(fid) {
	$('#fresponse').html('');
    $('#fresponse').load('assets/ajax/futures_add.php?action=edit&fid='+fid);
}

function deletef(fid) {
	$('#fresponse').html('');
	var r = confirm("Are you sure want to delete it!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/futuresedit.inc.php',
			data: {fid:fid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#ftable").html('');
						parent.$('#ftable').load('assets/ajax/futures_pedit.php');
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
