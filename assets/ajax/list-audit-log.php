<?php
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");

if(isset($_GET["listaudit"]))
{
?>
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
		<style>
		#audit_datatable_fixed_column_filter{
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
		}
		.dt-buttons{
		float: right !important;
		margin: 0.9% auto !important;
		}
		#audit_datatable_fixed_column_length{
		float: right !important;
		margin: 1% 1% !important;
		}
		.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
		.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
		table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
		#audit_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
		</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Audit Log </h2>

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
						<table id="audit_datatable_fixed_column" class="table table-striped table-bordered table-hover table-responsive" width="100%">
							<thead>
								<tr>
									<!--<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter ID" />
									</th>-->
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter User ID" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Group ID" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter User Name" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter IP Address" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Table Name" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Table Row ID" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Edited Value" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Activity" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Date Time" />
									</th>
									<!--<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Session ID" />
									</th>-->
									<th></th>
								</tr>
								<tr>
									<!--<th>ID</th>-->
									<th data-hide="phone">User ID</th>
									<th data-hide="phone">Group ID</th>
									<th data-hide="expand">User Name</th>
									<th>IP Address</th>
									<th data-hide="phone">Table Name</th>
									<th data-hide="phone">Table Row ID</th>
									<th>Edited Value</th>
									<th data-hide="phone,tablet">Activity</th>
									<th data-hide="phone,tablet">Date Time</th>
									<?php if(1==2){?><!--<th data-hide="phone,tablet">Session ID</th>--><?php } ?>
									<th data-hide="phone,tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
	if ($stmt = $mysqli->prepare('SELECT al.id,al.user_id,u.usergroups_id,u.firstname,u.lastname,al.ip_address,al.table_name,al.table_row_id,al.edited_value,al.activity,al.modified,al.session_id,al.status FROM audit_log al,user u WHERE al.user_id=u.user_id ORDER BY al.modified DESC')) {

//('SELECT al.id,al.user_id,u.usergroups_id,u.firstname,u.lastname,al.ip_address,al.table_name,al.table_row_id,al.edited_value,al.activity,al.modified,al.session_id,al.status FROM audit_log al,user u WHERE al.user_id=u.id ORDER BY al.modified DESC')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$editedvalarr=array();
			$stmt->bind_result($id,$Userid,$Usergroupsid,$Firstname,$Lastname,$Ipaddress,$Tablename,$Tablerowid,$Editedvalue,$Activity,$Modified,$Sessionid,$Status);
			while($stmt->fetch()) {
				$editedval=$z="";
				$editedvalarr=unserialize(base64_decode($Editedvalue));
				$z=count($editedvalarr);
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"])){
						$editedval .= "<b>Title:</b> ".$editedvalarr[$i]["title"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
					}
					if(isset($editedvalarr[$i]["file"])){
						$editedval .= "<b>File:</b> ".$editedvalarr[$i]["file"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
					}
					if($i<$z){$editedval .= "<hr style='margin:2px' />";}
				}
			?>
				<tr>
					<!--<td><?php //echo $id; ?></td>-->
					<td><?php echo $Userid; ?></td>
					<td><?php echo $Usergroupsid; ?></td>
					<td><?php echo $Firstname." ".$Lastname; ?></td>
					<td><?php echo $Ipaddress; ?></td>
					<td><?php echo $Tablename; ?></td>
					<td><?php echo $Tablerowid; ?></td>
					<td><div style="height:60px;overflow-y:auto;"><?php echo $editedval; ?></div></td>
					<td><?php echo $Activity; ?></td>
					<td><?php echo date('M d, Y h:i', strtotime($Modified)); ?></td>
					<!--<td><?php //echo wordwrap($Sessionid,13,"<br>\n",TRUE); ?></td>-->
					<td>&nbsp;&nbsp;<button onclick="load_audit_log(<?php echo $id; ?>)" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></button><?php if($Activity=="UPDATE"){ if($Status != 1){?><button onclick="rollback_audit_logs(<?php echo $id; ?>,'rollback')" title="Roll Back" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></button><?php } if($Status != 2){ ?><button onclick="rollback_audit_logs(<?php echo $id; ?>,'forward')" title="Forward" class="btn btn-xs btn-default"><i class="fa fa-share"></i></button><?php }} ?>&nbsp;&nbsp;</td>
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
			var otable = $("#audit_datatable_fixed_column").DataTable( {
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
						'messageTop': 'Vervantis PDF Export',
						'orientation':'landscape'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
						'orientation':'landscape'
					},
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
				],
				"autoWidth" : true
			});
	    /*var otable = $('#audit_datatable_fixed_column').DataTable({
			 "iDisplayLength": 10,
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
	    $("#audit_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

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
		$('#audit_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
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

function load_audit_log(aid) {
	$('#auditresponse').html('');
    $('#auditresponse').load('assets/ajax/list-audit-log.php?aid='+aid);
}

function rollback_audit_logs(aid,actions) {
	if(aid=="" || actions==""){alert("Error Occured");return false;}
	$('#auditresponse').html('');
	var r = confirm("Are you sure want to "+actions+"?");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/auditlog.inc.php',
			data: {auid:aid,action:actions},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#audittable").html("");
						parent.$("#audittable").load("assets/ajax/list-audit-log.php?listaudit=all");
						//parent.$("#dtable").html('');
						//parent.$('#dtable').load('assets/ajax/company-pedit.php');
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
<?php
}elseif(isset($_GET["aid"]) and @trim($_GET["aid"]) != "" and @trim($_GET["aid"]) > 0){
	$aid=@trim($_GET['aid']);
	//if(!is_int($aid)){die("Error Occured! Please try after sometime.");}
	$a_editedval="";
	if ($stmt = $mysqli->prepare('SELECT al.id,al.user_id,u.usergroups_id,u.firstname,u.lastname,al.ip_address,al.table_name,al.table_row_id,al.edited_value,al.activity,al.modified,al.session_id FROM audit_log al,user u WHERE al.user_id=u.user_id and al.table_row_id != 0 and al.id="'.$aid.'" ORDER BY al.modified DESC LIMIT 1')) {

//('SELECT al.id,al.user_id,u.usergroups_id,u.firstname,u.lastname,al.ip_address,al.table_name,al.table_row_id,al.edited_value,al.activity,al.modified,al.session_id FROM audit_log al,user u WHERE al.user_id=u.id and al.table_row_id != 0 and al.id="'.$aid.'" ORDER BY al.modified DESC LIMIT 1')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($a_id,$a_Userid,$a_Usergroupsid,$a_Firstname,$a_Lastname,$a_Ipaddress,$a_Tablename,$a_Tablerowid,$a_Editedvalue,$a_Activity,$a_Modified,$a_Sessionid);
			$stmt->fetch();
			$a_editedvalarr=unserialize(base64_decode($a_Editedvalue));
			$z=count($a_editedvalarr);
			for($i=0;$i<$z;$i++)
			{
				if(isset($a_editedvalarr[$i]["title"])){
					$a_editedval .= "Title: ".$a_editedvalarr[$i]["title"]."&#13;&#10;Old Value: ".$a_editedvalarr[$i]["old"]."&#13;&#10;New Value: ".$a_editedvalarr[$i]["new"];
				}
				if(isset($a_editedvalarr[$i]["file"])){
					$a_editedval .= "File: ".$a_editedvalarr[$i]["file"]."&#13;&#10;Old Value: ".$a_editedvalarr[$i]["old"]."New Value: ".$a_editedvalarr[$i]["new"];
				}
				if($i<$z){$a_editedval .= "&#13;&#10;&#13;&#10;";}
			}
?>
		<div id="view-audit-dialog-message" class="view-audit-dialog-message" title="View Audit">
			<form class="smart-form" novalidate="novalidate" method="post" onsubmit="return false">
							<fieldset>
								<div class="row">
									<section class="col col-6">Audit ID
										<label class="input">
											<input type="text" readonly placeholder="Audit ID" value="<?php echo $a_id; ?>">
										</label>
									</section>
									<section class="col col-6">Audit Version
										<label class="input">
											<input type="text" readonly placeholder="Audit Version" value="<?php echo $a_Userid; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">User ID
										<label class="input">
											<input type="text" readonly placeholder="User ID" value="<?php echo $a_Userid; ?>">
										</label>
									</section>
									<section class="col col-6">Group ID
										<label class="input">
											<input type="text" readonly placeholder="Group ID" value="<?php echo $a_Usergroupsid; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">User Name
										<label class="input">
											<input type="text" readonly placeholder="User Name" value="<?php echo $a_Firstname." ".$a_Lastname; ?>">
										</label>
									</section>
									<section class="col col-6">IP Address
										<label class="input">
											<input type="text" readonly placeholder="IP Address" value="<?php echo $a_Ipaddress; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Table Name
										<label class="input">
											<input type="text" readonly placeholder="Table Name" value="<?php echo $a_Tablename; ?>">
										</label>
									</section>
									<section class="col col-6">Table Row ID
										<label class="input">
											<input type="text" readonly placeholder="Table Row ID" value="<?php echo $a_Tablerowid; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Date Time
										<label class="input">
											<input type="text" readonly placeholder="Date Time" value="<?php echo @date("m/d/Y h:m", @strtotime($a_Modified)); ?>">
										</label>
									</section>
									<section class="col col-6">Session ID
										<label class="input">
											<input type="text" readonly placeholder="Session ID" value="<?php echo $a_Sessionid; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Table Name
										<label class="input">
											<input type="text" readonly placeholder="User Name" value="<?php echo $a_Activity; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col-12">Edited Value
										<label class="textarea">
											<textarea rows="3" readonly placeholder="Edited Value"><?php echo html_entity_decode($a_editedval); ?></textarea>
										</label>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="button" class="btn" id="view-audit-close">
									Close
								</button>
							</footer>
						</form>
	</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script src="assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="assets/js/sha512.js"></script>
<script type="text/JavaScript" src="assets/js/forms.js"></script>
<script type="text/javascript">
$(function() {
	$(document).ready(function() {
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
	});
});
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
	 * TO LOAD A SCRIPT:
	 * var pagefunction = function (){
	 *  loadScript(".../plugin.js", run_after_loaded);
	 * }
	 *
	 * OR
	 *
	 * loadScript(".../plugin.js", run_after_loaded);
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction

	var pagefunction = function() {
		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title : function(title) {
				if (!this.options.title) {
					title.html("&#160;");
				} else {
					title.html(this.options.title);
				}
			}
		}));

		$("#view-audit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>View Audit</h4></div>",
			/*buttons : [{
				html : "Cancel",
				"class" : "btn btn-default",
				click : function() {
					$(this).dialog("close");
				}
			}, {
				html : "<i class='fa fa-check'></i>&nbsp; OK",
				"class" : "btn btn-primary",
				click : function() {
					$(this).dialog("close");
				}
			}]*/
             close : function(){
				$("#view-audit-dialog-message").dialog('destroy');
				$("#view-audit-dialog-message").remove();
				parent.$("#auditresponse").html('');
              }

		});

		$('#view-audit-close').click(function() {;
			$("#view-audit-dialog-message").dialog("close");
			$(".view-audit-dialog-message").remove();
			$(".view-audit-dialog-message").dialog('destroy');
			parent.$("#auditresponse").html("");
		});
	};

	var pagedestroy = function() {
		//$('#profileForm').bootstrapValidator('destroy');
	}

	loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);
	//loadScript("assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction);
	// end pagefunction

	// run pagefunction on load

	//pagefunction();
</script>
<?php
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
}else{
	die("Error Occured! Please try after sometime1.");
}
?>
