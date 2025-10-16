<?php require_once("inc/init.php");
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");

//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 5)
	//die("Restricted Access");

$user_one=$_SESSION['user_id'];
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
					<h2>User Edit </h2>

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
<?php if($_SESSION["group_id"] == 1){?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter ID" />
									</th>
<?php } ?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Email" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter First Name" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Last Name" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter User Groups" />
									</th>
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Gender" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Company Name" />
									</th>
<?php } ?>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Status" />
									</th>
									<th></th>
								</tr>
								<tr>
<?php if($_SESSION["group_id"] == 1){?>
									<th data-hide="phone">ID</th>
<?php } ?>
									<th>Email</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th data-hide="phone">User Groups <?php if(1==2){?><!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--><?php }?></th>
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
									<th data-hide="phone,tablet">Gender <?php if(1==2){?><!--<select id="selectState" name="selectState[]" multiple="multiple"></select>--><?php }?></th>
									<th>Company Name</th>
<?php } ?>
									<th data-hide="phone,tablet">Status <?php if(1==2){?><!--<select id="selectCity" name="selectCity[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)
		$sql='SELECT u.firstname,u.lastname,u.user_id,u.email,u.usergroups_id,u.gender,c.company_name,u.status FROM user u LEFT JOIN company c ON u.company_id=c.company_id';

//SELECT u.id,u.email,u.usergroups_id,u.gender,u.status FROM user u';

	elseif($_SESSION["group_id"] == 5)
		$sql='SELECT u.firstname,u.lastname,u.user_id,u.email,u.usergroups_id,u.gender,c.company_name,u.status FROM user u LEFT JOIN company c ON u.company_id=c.company_id where (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.company_id=(SELECT usp.company_id FROM user usp WHERE usp.user_id= "'.$user_one.'")';

//SELECT id,email,usergroups_id,gender,status FROM user where (usergroups_id = 5 OR usergroups_id = 3) and company_id=(SELECT usp.company_id FROM user usp WHERE usp.id= "'.$user_one.'")';

	else
		die("Error Occured. Please try after sometime!");

	if ($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($fname,$lname,$id,$Email,$Usergroups,$Gender,$companyname,$Status);
			while($stmt->fetch()) {
				$ts=$id.rand(650,900);
			?>
				<tr>
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
						<td><?php echo $id; ?></td>
<?php } ?>
						<td><?php echo $Email; ?></td>
						<td><?php echo $fname; ?></td>
						<td><?php echo $lname; ?></td>
						<td><?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
							if($Usergroups==1) echo "Vervantis Administrator";
							else if($Usergroups==2) echo "Vervantis Employee";
							else if($Usergroups==3) echo "Client";
							else if($Usergroups==4) echo "Vendor";
							else if($Usergroups==5) echo "Client Administrator";
							else if($Usergroups==6) echo "Sub Contractors";
							else echo "Unknown Group";



							}else{echo ($Usergroups==5?"Administrator":"Standard"); }?>
						</td>
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
						<td><?php echo $Gender; ?></td>
						<td><?php echo $companyname; ?></td>
<?php } ?>
						<td><?php if($Status==1){echo "Active";}elseif($Status==0){echo "Inactive";}elseif($Status==2){echo "Locked Out";}elseif($Status==3){echo "Password Change";} ?>
						</td>
						<td>&nbsp;&nbsp;<button onclick="loadusermenu(<?php echo $id; ?>)" title="View/Edit User Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button>
						<?php if($_SESSION["group_id"] == 1){?><button onclick="deleteuser(<?php echo $id; ?>,'<?php echo $Email; ?>')" title="Delete User" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?>&nbsp;&nbsp;</td>
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
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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
			var otable = $("#datatable_fixed_column").DataTable( {
				"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
				"pageLength": 12,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"deferLoading": 57,
				"processing": true,
				"dom": 'Blfrtip',
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
						'messageTop': 'Vervantis PDF Export'
					} ),
					$.extend( true, {}, fixNewLine, {
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					} ),
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
				],
				"autoWidth" : true
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


	// load related plugins

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

function loadusermenu(userid) {
	parent.$('#presponse').html('');
    parent.$('#presponse').load('assets/ajax/user-profile.php?userid='+userid);
}
<?php if($_SESSION["group_id"] == 1){?>
function deleteuser(userid,uemail) {
	$('#presponse').html('');
	var r = confirm("Are you sure want to delete "+uemail+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/profileedit.inc.php',
			data: {usr:userid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						//alert("Success");
						parent.$("#dtable").html('');
						parent.$('#dtable').load('assets/ajax/user-pedit.php');
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
