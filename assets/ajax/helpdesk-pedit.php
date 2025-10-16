<?php
//error_reporting(E_ALL);
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,55)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$cname=$_SESSION["company_id"];

if(isset($_GET["load"])){
	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sql = "SELECT distinct h.id,h.user_id,u.firstname,u.lastname,h.subject,h.message,h.priority,h.datetime,h.notes,h.status,c.company_name FROM helpdesk h,company c,user u WHERE u.company_id=c.company_id and u.user_id=h.user_id and u.user_id='".$user_one."' order by h.id desc";
	}else{
		$sql = "SELECT distinct h.id,h.user_id,u.firstname,u.lastname,h.subject,h.message,h.priority,h.datetime,h.notes,h.status,c.company_name FROM helpdesk h,company c,user u WHERE u.company_id=c.company_id and u.user_id=h.user_id order by h.id desc";
	}
	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<style>
	#cm_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#cm_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#cm_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
	</style>
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Help Requests </h2>

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
							<table id="cm_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
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
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter User Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Company Name" />
										</th>
									<?php } ?>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Subject" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Message" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Priroity" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Notes" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Status" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Date" />
										</th>
									</tr>
									<tr>
									<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">User Name </th>
										<th data-hide="phone,tablet">Company Name </th>
									<?php } ?>
										<th data-hide="phone,tablet">Subject </th>
										<th data-hide="phone,tablet">Message </th>
										<th data-hide="phone,tablet">Priroity </th>
										<th data-hide="phone,tablet">Notes </th>
										<th data-hide="phone,tablet">Status </th>
										<th data-hide="phone,tablet">Date </th>
									</tr>
								</thead>
								<tbody>
	<?php
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($h_id,$h_uid,$h_firstname,$h_lastname,$h_subject,$h_message,$h_priority,$h_date,$h_notes,$h_status,$h_cname);
				while($stmt->fetch()) {
				?>
					<tr>
					<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_firstname." ".$h_lastname; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_cname; ?></a></td>
					<?php } ?>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_subject; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_message; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_priority; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_notes; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo $h_status; ?></a></td>
							<td><a href="javascript:void(0);" onclick="loadhmenu('<?php echo $h_id; ?>')"><?php echo @date("m/d/Y",strtotime($h_date)); ?></a></td>
						</tr>
				<?php
				}
			}
		}else{
			//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
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
				var otable = $("#cm_datatable_fixed_column").DataTable( {
					"lengthMenu": [[25, 50, -1], [25, 50, "All"]],
					"pageLength": 25,
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

			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter
			$("#cm_datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

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
			$('#cm_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
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

	function loadhmenu(hid) {
		parent.$('#hresponse').html('');
		parent.$('#hresponse').load('assets/ajax/helpdesk-pedit.php?ct=<?php echo rand(2,99); ?>&hid='+hid);
	}
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>

	function addnewacc(cm_id){

		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		parent.$("#dialog-message").remove();
		parent.$(".cmtable").fadeOut( "slow" );
		parent.$('#cmresponse').fadeOut( "slow" );
		parent.$('#cmdetails').fadeOut( "slow" );
		parent.$('#cmaccdetails').load('assets/ajax/contract-manager-pedit.php?action=addcmacc&ctid='+cm_id+'&ct=<?php echo rand(9,33); ?>');
	}

	function deletecm(cmid) {
		$('#cmresponse').html('');
		var r = confirm("Are you sure want to delete it!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/contractmanageredit.inc.php',
				data: {cmid:cmid,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							alert("Success");
							parent.$("#cmtable").html('');
							parent.$('#cmtable').load('assets/ajax/contract-manager.php?load=true');
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
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
///////////////MOVE BACK//////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
	</script>
	<?php


}elseif($_GET["hid"]){
	$tmp_rid=$mysqli->real_escape_string(@trim($_GET["hid"]));

	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sql = "SELECT distinct h.id,h.user_id,u.firstname,u.lastname,h.subject,h.message,h.priority,h.datetime,h.notes,h.status,c.company_name FROM helpdesk h,company c,user u WHERE u.company_id=c.company_id and u.user_id=h.user_id and u.user_id='".$user_one."' and h.id='".$tmp_rid."' Limit 1";
	}else{
		$sql = "SELECT distinct h.id,h.user_id,u.firstname,u.lastname,h.subject,h.message,h.priority,h.datetime,h.notes,h.status,c.company_name FROM helpdesk h,company c,user u WHERE u.company_id=c.company_id and u.user_id=h.user_id and h.id='".$tmp_rid."'  Limit 1";
	}

	if ($stmt = $mysqli->prepare($sql)) {
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($h_id,$h_uid,$h_firstname,$h_lastname,$h_subject,$h_message,$h_priority,$h_date,$h_notes,$h_status,$h_cname);
			$stmt->fetch();
?>
<style>
.ui-dialog-title{    width: 100%;
    text-align: center !important;}
footer{text-align:center !important;}
footer button{float:none !important;}
.col-12{width:100%;}
i.readonly{font-size: 11px;color: red;}
</style>
					<div id="u-dialog-message<?php echo $tmp_rid; ?>" class="hshow" title="Edit Profile">
						<form id="checkout-form<?php echo $tmp_rid; ?>" class="smart-form uedit" novalidate="novalidate" method="post" onsubmit="return profileEdit()" enctype="multipart/form-data" autocomplete="off">

							<fieldset>
								<div class="row">
									<section class="col col-6">First Name <i class="readonly">(readonly)</i>
										<label class="input">
											<input type="text" disabled placeholder="First name" value="<?php echo $h_firstname; ?>">
										</label>
									</section>
									<section class="col col-6">Last Name <i class="readonly">(readonly)</i>
										<label class="input">
											<input type="text" Disabled placeholder="Last name" value="<?php echo $h_lastname; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Company <i class="readonly">(readonly)</i>
										<label class="input">
											<input type="text" Disabled placeholder="Title" value="<?php echo $h_cname; ?>">
										</label>
									</section>
									<section class="col col-6">Date <i class="readonly">(readonly)</i>
										<label class="input">
											<input type="text" Disabled placeholder="Date" value="<?php echo @date("m/d/Y",strtotime($h_date));; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-12">Subject
										<label class="input">
											<input type="text" id="subject<?php echo $tmp_rid; ?>" name="subject<?php echo $tmp_rid; ?>" placeholder="Subject" value="<?php echo $h_subject; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-12">Message
										<label class="textarea">
											<textarea id="hmessage<?php echo $tmp_rid; ?>" name="hmessage<?php echo $tmp_rid; ?>" style="height:80px;width:100%;"><?php echo $h_message; ?></textarea>
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-12">Notes
										<label class="textarea">
											<textarea id="hnotes<?php echo $tmp_rid; ?>" name="hnotes<?php echo $tmp_rid; ?>" style="height:80px;width:100%;"><?php echo $h_notes; ?></textarea>
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Status
										<label class="select">
										<select name="status<?php echo $tmp_rid; ?>" id="status<?php echo $tmp_rid; ?>" placeholder="Status" class="">
											<option value="" <?php if($h_status!="Open" and $h_status!="Closed"){echo "Selected"; } ?>>Select Status</option>
											<option value="Open" <?php if($h_status=="Open"){echo "Selected"; } ?>>Open</option>
											<option value="Closed" <?php if($h_status=="Closed"){echo "Selected"; } ?>>Closed</option>
										</select>
										</label>
									</section>
									<section class="col col-6">Priority
										<label class="select">
										<select name="priority<?php echo $tmp_rid; ?>" id="priority<?php echo $tmp_rid; ?>" placeholder="Priority" class="">
											<option value="" <?php if($h_priority!="Low" and $h_priority!="Medium" and $h_priority!="High" and $h_priority!="Urgent" and $h_priority!="Critical"){echo "Selected"; } ?>>Select Priority</option>
											<option value="Low" <?php if($h_priority=="Low"){echo "Selected"; } ?>>Low</option>
											<option value="Medium" <?php if($h_priority=="Medium"){echo "Selected"; } ?>>Medium</option>
											<option value="High" <?php if($h_priority=="High"){echo "Selected"; } ?>>High</option>
											<option value="Urgent" <?php if($h_priority=="Urgent"){echo "Selected"; } ?>>Urgent</option>
											<option value="Critical" <?php if($h_priority=="Critical"){echo "Selected"; } ?>>Critical</option>
										</select>
										</label>
									</section>
									<input type="hidden" value="<?php echo $h_id; ?>" id="hid<?php echo $tmp_rid; ?>" name="hid<?php echo $tmp_rid; ?>">
								</div>
							<footer>
								<button type="submit" class="btn btn-primary" id="h-submit<?php echo $tmp_rid; ?>">
									Save
								</button>
								<button type="button" class="btn" id="h-cancel<?php echo $tmp_rid; ?>">
									Close
								</button>
							</footer>
							</form>
						</div>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="<?php echo ASSETS_URL; ?>/assets/js/sha512.js"></script>
<script type="text/JavaScript" src="<?php echo ASSETS_URL; ?>/assets/js/forms.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
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
				$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
				$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
				//parent.loadusermenu(<?php /*echo $userid;*/ ?>);
			}
		});
	$("#h-cancel<?php echo $tmp_rid; ?>").on('click', function (e) {
		$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
		$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
	});

	$("#h-submit<?php echo $tmp_rid; ?>").on('click', function (e) {
		var hsub=$("#subject<?php echo $tmp_rid; ?>").val();
		var hmsg=$("#hmessage<?php echo $tmp_rid; ?>").val();
		var hprty=$("#priority<?php echo $tmp_rid; ?>").val();
		var hnotes=$("#hnotes<?php echo $tmp_rid; ?>").val();
		var hstatus=$("#status<?php echo $tmp_rid; ?>").val();
		var hid=$("#hid<?php echo $tmp_rid; ?>").val();
		if(hsub != '' && hmsg != '' && hprty != ''){
			$.post("assets/includes/helpdesk.inc.php",
			{
			  subject: hsub,
			  hid: hid,
			  message: hmsg,
			  priority : hprty,
			  notes : hnotes,
			  status : hstatus
			},
			function(result){
			  if(result == true){
				Swal.fire("","Submitted successfully!", "success");
				parent.$("#hrtable").html('');
				parent.$('#hrtable').load('assets/ajax/helpdesk-pedit.php?load=true&ct=<?php echo rand(9,33); ?>');
				$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
				$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
			  }else if(result==6){Swal.fire("","All fields required!", "warning");}
			  else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
		}else{Swal.fire("","All fields required!", "warning");}
	});
});
<?php
		}else die("Error Occured. Please try after sometimes.");
	}else die("Error Occured. Please try after sometimes.");
}else die("Error Occured. Please try after sometimes.");
?>
