<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

if(checkpermission($mysqli,63)==false) die("<h5 style='padding-top:30px;' align='center'>Permission Denied! Please contact Vervantis.</h5>");

$user_one=$_SESSION["user_id"];

if(isset($_GET["load"])){
?>
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
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_column .resolvedexpdrp{width:auto !important;}
#datatable_fixed_column .resolvedexpdrp {
    font-weight: 400 !important;
}
</style>

<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
			<th></th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Client ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Client Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Exception ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Site #" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Vendor Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Account #" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter ServiceType" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Exception Description" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Resolution" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Invoice Amount" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Created Date" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Modified Date" />
			</th>
			<th class="hasinput">
				<select class="form-control repdrp" id="repdrp">
					<option value="">Filter Priority</option>
					<?php
						//if(isset($_SESSION['group_id']) and ($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2)){
							if ($stmtp = $mysqli->prepare('SELECT DISTINCT `Priority` from ubm_exceptions.mapExceptions WHERE `Priority` IS NOT NULL AND `Priority` !="" ORDER BY `Priority`')){

								$stmtp->execute();
								$stmtp->store_result();
								if ($stmtp->num_rows > 0) {
									$stmtp->bind_result($__priority);
									while($stmtp->fetch()){
										echo "<option value='".$__priority."'>".$__priority."</option>";
									}
								}
							}else{
								header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
								exit();
							}
						//}
					?>
				</select>
			</th>
		</tr>
		<tr>
			<th></th>
			<th>Client ID</th>
			<th>Client Name</th>
			<th>Exception ID</th>
			<th>Site #</th>
			<th>Vendor Name</th>
			<th>Account #</th>
			<th>ServiceType</th>
			<th>Exception Description</th>
			<th>Resolution</th>
			<th>Invoice Amount</th>
			<th>Created Date</th>
			<th>Modified Date</th>
			<th>Priority</th>
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
			var responsiveHelper_datatable_fixed_column = undefined;
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
	    var otable = $('#datatable_fixed_column').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 25,
			"processing": false,
			"serverSide": true,
			"order": [[ 12, 'desc' ]],
		"dom": 'Blfrtip',
		
		"drawCallback" : function(settings) {
			$(".dots-cont").hide();
		},					
		"preDrawCallback": function (settings) {
			$(".dots-cont").show();
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
						'orientation':'landscape',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					} ),
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
        ],
			"autoWidth" : true,
			"ajax": "assets/ajax/server_processing_resolvedexp.php",
			"initComplete":function( settings, json){
				otable.columns(13).search('Fatal', true, false).draw();
			}
	    });
<?php
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){
?>
		otable.columns( [0,1,2] ).visible( false );
<?php }else{ ?>
		otable.columns( [0] ).visible( false );
<?php } ?>
	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );

	$("#datatable_fixed_column .repdrp").off();
	    $("#datatable_fixed_column .repdrp").on( 'keyup change', function () {
	        otable
				.column( $(this).parent().index()+':visible' )
	            .search(this.value)
	            .draw();

	    } );
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
			 var searchactive =otable
				.column(1)
	            .search('')
	            .draw();
<?php } ?>
	    /* END COLUMN FILTER */
<?php if(1==2){ /*if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){*/?>
		$('#selectCompany').empty().append( multilist(2).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(3).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(4).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(5).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(6).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(9).join() );
		$("#selectStatus").multiselect();
<?php }else if(1==3){/*}else{*/ ?>
		$('#selectCompany').empty().append( multilist(1).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(2).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(3).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(4).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(5).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(8).join() );
		$("#selectStatus").multiselect();
<?php } ?>
		//$("#selectCompany").on( 'keyup change', function () {multifilter(this,"selectCompany",otable)});
		//$("#selectDivision").on( 'keyup change', function () {multifilter(this,"selectDivision",otable)});
		//$("#selectCountry").on( 'keyup change', function () {multifilter(this,"selectCountry",otable)});
		//$("#selectState").on( 'keyup change', function () {multifilter(this,"selectState",otable)});
		//$("#selectCity").on( 'keyup change', function () {multifilter(this,"selectCity",otable)});
		//$("#selectStatus").on( 'keyup change', function () {multifilter(this,"selectStatus",otable)});

		//$('.sssdrp option:eq(1)').prop('selected', true);
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

<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadsite(sid) { return false;
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/list-sites.php?editsid=true&sid='+sid);
}

function deletesite(sid,sname) {return false;
	$('#response').html('');
	var r = confirm("Do you want to delete Site: "+sname+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/sitesedit.inc.php',
			data: {sid:sid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#list-sites").html('');
						parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');
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
$( document ).ready(function() {
		$("#datatable_fixed_column .repdrp option[value='Fatal']").attr('selected', 'selected');
		//$("#datatable_fixed_column").DataTable().columns(13).search('Fatal', true, false).draw();
});
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
