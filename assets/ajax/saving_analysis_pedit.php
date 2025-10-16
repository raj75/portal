<?php require_once("inc/init.php"); ?>
<?php
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]) and $_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION['user_id'];

$dis_type = "";
if(isset($_GET["type"]) and $_GET["type"]=="unread")
	$dis_type = " and sa._read='N'";

if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){
	$sql = "SELECT sa.id,sa.company_id,c.company_name,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read FROM saving_analysis sa, company c, user u WHERE sa.company_id=c.company_id and c.company_id=u.company_id and u.user_id='$user_one'".$dis_type;

//SELECT sa.id,sa.company_id,c.company_name,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read FROM saving_analysis sa, company c, user u WHERE sa.company_id=c.company_id and c.company_id=up.company_id and u.id='.$user_one."'".$dis_type;

}else
	$sql = "SELECT sa.id,sa.company_id,c.company_name,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read FROM saving_analysis sa, company c WHERE sa.company_id=c.company_id".$dis_type.((($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":"");
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
					<h2>Saving Analysis </h2>

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
										<input type="text" class="form-control" placeholder="Filter Location" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Category" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Commodity" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Start" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter End" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Saving" />
									</th>
									<th></th>
								</tr>
								<tr>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><th data-hide="phone">ID</th>
									<th data-hide="expand">Company <?php if(1==2){?><!--<select id="selectCompany" name="selectCompany[]" multiple="multiple"></select>--><?php }?></th><?php } ?>
									<th>Location</th>
									<th data-hide="phone">Category <?php if(1==2){?><!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Commodity <?php if(1==2){?><!--<select id="selectState" name="selectState[]" multiple="multiple"></select>--><?php }?></th>
									<th data-hide="phone,tablet">Start </th>
									<th data-hide="phone,tablet">End </th>
									<th data-hide="phone,tablet">Saving </th>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>
<?php
	if ($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($sa_Id,$sa_Companyid,$c_Companyname,$sa_Location,$sa_Category,$sa_Commodity,$sa_Start,$sa_End,$sa_Saving,$sa_Link,$sa_Read);
			while($stmt->fetch()) {
			?>
				<tr <?php if($sa_Read == "N"){echo 'style="font-weight:bold;"';}?>>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><td><?php echo $sa_Id; ?></td>
						<td><?php echo $c_Companyname; ?></td><?php } ?>
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
						<td><?php echo $sa_Start; ?>
						</td>
						<td><?php echo $sa_End; ?>
						</td>
						<td><?php echo $sa_Saving; ?>
						</td>
						<td><?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?><button onclick="loadsamenu(<?php echo $sa_Id; ?>)" title="View/Edit Saving Analysis Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletesa(<?php echo $sa_Id; ?>)" title="Delete Saving Analysis" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?><?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?><button onclick="loadsamenu(<?php echo $sa_Id; ?>)" title="View Saving Analysis Details" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></button><?php } ?></td>
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

<?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
function loadsamenu(said) {
	$('#saresponse').html('');
    $('#saresponse').load('assets/ajax/saving_analysis_add.php?action=view&said='+said<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "+'&type=unread'";} ?>);
}
<?php } ?>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
function loadsamenu(said) {
	$('#saresponse').html('');
    $('#saresponse').load('assets/ajax/saving_analysis_add.php?action=edit&said='+said);
}

function deletesa(said) {
	$('#saresponse').html('');
	var r = confirm("Are you sure want to delete it!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/savinganalysisedit.inc.php',
			data: {said:said,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#satable").html('');
						parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
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
