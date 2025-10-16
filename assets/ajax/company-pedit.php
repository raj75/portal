<?php
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");
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
					<h2>Company Edit </h2>

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
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter ID" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Company Name" />
									</th>
									<!--<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Status" />
									</th>-->
									<th></th>
								</tr>
								<tr>
									<th data-hide="phone">ID</th>
									<th data-hide="expand">Company Name <?php if(1==2){?><!--<select id="selectCompany" name="selectCompany[]" multiple="multiple"></select>--><?php } ?></th>
									<?php if(1==2){?><!--<th data-hide="phone,tablet">Status </th>--><?php } ?>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
	if ($stmt = $mysqli->prepare('SELECT company_id,company_name,status FROM company')) {

//('SELECT id,company_name,skype,status FROM company')

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$Companyname,$Status);
			while($stmt->fetch()) {
				$ts=$id.rand(650,900);
			?>
				<tr>
						<td><?php echo $id; ?></td>
						<td><?php echo $Companyname; ?>
							<a href="javascript:void(0);" onclick="showversion('<?php echo $id; ?>','<?php echo 'company'; ?>','<?php echo 'company_name'; ?>','<?php echo $ts;?>','dtable','assets/ajax/company-pedit.php')" id="<?php echo $ts;?>"> <i class="icon-prepend fa fa-question-circle"></i></a><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a></td>
						</td>
						<!--<td><?php //echo $Status; ?></td>-->
						<td>&nbsp;&nbsp;<button onclick="loadcompanymenu(<?php echo $id; ?>)" title="View/Edit Company Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletecompany(<?php echo $id; ?>,'<?php echo $Companyname; ?>')" title="Delete Company" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button>&nbsp;&nbsp;</td>
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

function loadcompanymenu(cid) {
	$('#response').html('');
    $('#response').load('assets/ajax/company-profile.php?ct=<?php echo(mt_rand(10,100)); ?>&cid='+cid);
}

function deletecompany(cid,cname) {
	$('#response').html('');
	var r = confirm("Are you sure want to delete "+cname+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/companyedit.inc.php',
			data: {cpy:cid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#dtable").html('');
						parent.$('#dtable').load('assets/ajax/company-pedit.php');
					}else
						alert(results.error);
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		  });
	}
}
</script>
