<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
if(!isset($_SESSION))
{
	sec_session_start();
}

if(!isset($_SESSION["user_id"]) and $_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION['user_id'];

$dis_type="";
if(isset($_GET["type"]) and $_GET["type"]=="unread")
	$dis_type=" and fi._read='N'";

if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){
	$sql = "SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi,company c,user u WHERE u.user_id = '".$user_one."' and u.company_id = fi.company_id and fi.company_id=c.company_id".$dis_type;

//SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi,company c,user u WHERE u.id = '".$user_one."' and u.company_id = fi.company_id and fi.company_id=c.id".$dis_type;

}else
	$sql = "SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.company_id".$dis_type.((($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":"")

//	$sql = "SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.id".$dis_type

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
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Focus Items </h2>

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
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter ID" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Company" />
									</th><?php } ?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Category" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Description" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Date Added" />
									</th>
									<th></th>
								</tr>
								<tr>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><th data-hide="phone">ID</th>
									<th data-hide="expand">Company <?php if(1==2){?><!--<select id="selectCompany" name="selectCompany[]" multiple="multiple"></select>--><?php }?></th><?php } ?>
									<th data-hide="phone">Category <?php if(1==2){?><!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Description <?php if(1==2){?><!--<select id="selectState" name="selectState[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Date Added </th>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>
<?php
	if ($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($fi_Id,$fi_Companyid,$c_Companyname,$fi_Category,$fi_Description,$fi_Link,$fi_Read,$fi_Dateadded);
			while($stmt->fetch()) {
			?>
				<tr <?php if($fi_Read == "N"){echo 'style="font-weight:bold;"';}?>>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><td><?php echo $fi_Id; ?></td>
						<td><?php echo $c_Companyname; ?></td>
						</td><?php } ?>
						<td>
						<?php if($fi_Category==1){echo "Fixed Price";}
							elseif($fi_Category==2){echo "Index/Basis + Adder";}
							elseif($fi_Category==3){echo "Heat Rate";}
							elseif($fi_Category==4){echo "Hedge Block";}
							elseif($fi_Category==5){echo "Blend and Extend";}
							elseif($fi_Category==6){echo "Rate and Tariff Analysis";}
							elseif($fi_Category==7){echo "Procurement Recommendation";}
							elseif($fi_Category==8){echo "Budget Report";}
							elseif($fi_Category==9){echo "Meeting Agenda";}
							elseif($fi_Category==10){echo "New Market Summary";}
							?>
						</td>
						<td><?php echo $fi_Description; ?>
						</td>
						<td><?php echo $fi_Dateadded; ?>
						</td>
						<td>&nbsp;<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><button onclick="loadfimenu(<?php echo $fi_Id; ?>)" title="View/Edit Focus Items Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletefi(<?php echo $fi_Id; ?>)" title="Delete Focus Items" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?><?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?><button onclick="loadfimenu(<?php echo $fi_Id; ?>)" title="View Focus Items Details" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></button><?php } ?>&nbsp;</td>
					</tr>
			<?php
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
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
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				order: [[1, 'asc']],
<?php } ?>
				"autoWidth" : true
			});


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


	// load related plugins

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);




<?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
function loadfimenu(fiid) {
	$('#firesponse').html('');
    $('#firesponse').load('assets/ajax/focus_items_add.php?action=view&fiid='+fiid<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "+'&type=unread'";} ?>);
}
<?php } ?>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
function loadfimenu(fiid) {
	$('#firesponse').html('');
    $('#firesponse').load('assets/ajax/focus_items_add.php?action=edit&fiid='+fiid);
}

function deletefi(fiid) {
	$('#firesponse').html('');
	var r = confirm("Are you sure want to delete it!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/focusitemsedit.inc.php',
			data: {fiid:fiid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#fitable").html('');
						parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
					}else
						alert("Error in request. Please try again later.");
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		  });
	}
}
<?php }?>
</script>
