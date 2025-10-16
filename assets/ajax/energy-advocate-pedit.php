<?php
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

//if(!isset($_SESSION["user_id"]) and $_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	//die("Restricted Access");
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
					<h2>Energy Team </h2>

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
								<tr>
									<th data-hide="expand">Company ID</th>
									<th data-hide="phone">Company Name</th>
									<th data-hide="phone">Energy Advocate</th>
									<th data-hide="phone">Company Admin</th>
									<th data-hide="phone">Ubm Support</th>
									<th data-hide="phone,tablet">Date Added </th>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>
<?php
	if ($stmt = $mysqli->prepare('SELECT company_id,company_name,energy_advocate,date_added,company_admin,ubm_support FROM company Where company_id != 1 Order By company_name')) {

//('SELECT ad.id,ad.company_id,c.company_name,ad.user_id,u.firstname,u.lastname,ad.date_added FROM energy_advocate ad,user u,company c Where ad.user_id = u.id and u.usergroups_id = 2 and ad.company_id = c.id Order By c.company_name')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($ad_Companyid,$ad_Companyname,$ad_Empid,$ad_Dateadded,$ad_compadid,$ad_ubmid);
			while($stmt->fetch()) {
				$ad_Firstname=$ad_Lastname=$c_Firstname=$c_Lastname=$u_Firstname=$u_Lastname="";
				if(!empty($ad_Empid)){
					if ($stmtemp = $mysqli->prepare('SELECT firstname,lastname FROM user Where (usergroups_id = 1 or usergroups_id = 2) and user_id ='.$ad_Empid.' LIMIT 1')) {
					  $stmtemp->execute();
						$stmtemp->store_result();
						if ($stmtemp->num_rows > 0) {
							$stmtemp->bind_result($ad_Firstname,$ad_Lastname);
							$stmtemp->fetch();
						}
					}

				}

				if(!empty($ad_compadid)){
					if ($stmtcmpad = $mysqli->prepare('SELECT firstname,lastname FROM user Where company_id="'.$ad_Companyid.'" and usergroups_id = 3 and user_id ='.$ad_compadid.' LIMIT 1')) {
					  $stmtcmpad->execute();
						$stmtcmpad->store_result();
						if ($stmtcmpad->num_rows > 0) {
							$stmtcmpad->bind_result($c_Firstname,$c_Lastname);
							$stmtcmpad->fetch();
						}
					}

				}

				if(!empty($ad_ubmid)){
					if ($stmtubm = $mysqli->prepare('SELECT firstname,lastname FROM user Where company_id=1 and user_id ='.$ad_ubmid.' LIMIT 1')) {
					  $stmtubm->execute();
						$stmtubm->store_result();
						if ($stmtubm->num_rows > 0) {
							$stmtubm->bind_result($u_Firstname,$u_Lastname);
							$stmtubm->fetch();
						}
					}

				}
			?>
				<tr>
					<td><?php echo $ad_Companyid; ?></td>
					<td><?php echo $ad_Companyname; ?></td>
					<td><?php echo $ad_Firstname." ".$ad_Lastname; ?></td>
					<td><?php echo $c_Firstname." ".$c_Lastname; ?></td>
					<td><?php echo $u_Firstname." ".$u_Lastname; ?></td>
					<td><?php echo $ad_Dateadded; ?></td>
					<td>&nbsp;<?php if($_SESSION["group_id"] == 1){?><button onclick="loadadmenu(<?php echo $ad_Companyid; ?>)" title="Edit Energy Advocate" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletead(<?php echo $ad_Companyid; ?>)" title="Delete Energy Advocate" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button><?php } ?>&nbsp;</td>
				</tr>
			<?php
			}
		}
	}else{echo "Under Constructions!";
		//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		//exit();
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

	function loadadmenu(adid) {
		$('#adresponse').html('');
		$('#adresponse').load('assets/ajax/energy-advocate-add.php?action=edit&adid='+adid);
	}

	function deletead(adid) {
		$('#adresponse').html('');
		var r = confirm("Are you sure want to delete it!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/energyadvocateedit.inc.php',
				data: {adid:adid,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							alert("Success");
							parent.$("#adtable").html('');
							parent.$('#adtable').load('assets/ajax/energy-advocate-pedit.php');
						}else
							alert("Error in request. Please try again later.");
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}
</script>
