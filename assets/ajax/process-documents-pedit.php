<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']))
	die("Restricted Access!");

if($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2){}else die("Restricted Access!");

if($_SESSION["group_id"] == 2) $makereadonly=1;
else $makereadonly=0;

if(isset($_GET['inid']) and isset($_GET['action']) and $_GET['action'] == "details"){
	if(isset($_GET['inid']) and !empty(@trim($_GET['inid'])))
		$inid=$mysqli->real_escape_string(@trim($_GET['inid']));
	else
		die('Wrong parameters provided');
?>
<style>
.center{text-align:center;}
.center button{margin:5px;}
.ssse input[type=text]{width:90%;float:left;}
.ssse select{width:90%;float:left;}
.ssse textarea{width:98%;float:left;}
.ssse #sss-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
.ssse .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
    bottom: -1px !important;
    left: 29px !important;
}
.ssse .ssscomment{width:90%;float:left;}
.ssse th,.ssse td{border:none !important;padding:3px 10px !important;}
.ssse .showversion-link{float:left;margin-left: 3px;}
.ssse #logsshow{width:100%;
    height: 269px;
    overflow: auto;}
#wid-id--77 .nopadds{padding:0 !important;}
#cmacctable1{margin: 0 !important;
    margin-top: 2px !important;
    margin-bottom: 2px !important;}
.buttoncenter{text-align:center;}
</style>
	<div class="row ssse" id="<?php echo $inid; ?>">
		<!-- NEW WIDGET START -->
		<article class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="width: 42%">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--77" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Process Documents: <?php echo $inid; ?></h2>
					<span class="widget-icon" style="float: right;padding-right: 24px;cursor:pointer;" onclick="clearme(<?php echo $inid; ?>,12,true,0)"> <i class="fa fa-times"></i> </span>
				</header>
				<div class="row nopadds">
				<?php
				$disabled=$s3_foldername="";
				$address=array();
				//$todaydate=date('Y-m-d H:i:s');
				if ($vestmt = $mysqli->prepare('SELECT ID,`Group`,`Sub Group 1`,`Sub Group 2`,`Sub Group 3`,`Process Name`,Owner,Descriptions,`Created Date`,`Modified Date` FROM process_docs where ID='.$inid.' LIMIT 1')) {


				$vestmt->execute();
				$vestmt->store_result();
				if ($vestmt->num_rows > 0) {
					$vestmt->bind_result($p_id,$p_group,$p_subgroup1,$p_subgroup2,$p_subgroup3,$p_processname,$p_owner,$p_desc,$p_createddate,$p_modifieddate);
					$vestmt->fetch();
					$inid=$p_id;

					$ts=$inid.rand(650,900);
					//if($importDate != "") $importDate=@date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($importDate)));
					if($p_createddate=="0000-00-00") $p_createddate="";
					if($p_modifieddate=="0000-00-00") $p_modifieddate="";
						?>
						<table id="cmacctable<?php echo $inid; ?>" class="table table-striped table-bordered table-hover" style="clear: both">
							<tr>
								<th width="14%">Group:</th>
								<td><input type="text" value="<?php echo $p_group; ?>" id="group" class="ininputautosave" saveme="Group" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="15%">Sub Group 1:</th>
								<td><input type="text" value="<?php echo $p_subgroup1; ?>" id="subgroup1" class="ininputautosave" saveme="Sub Group 1" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="14%">Sub Group 2:</th>
								<td><input type="text" value="<?php echo $p_subgroup2; ?>" id="subgroup2" class="ininputautosave" saveme="Sub Group 2" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
							</tr>
							<tr>
								<th width="14%">Sub Group 3:</th>
								<td><input type="text" value="<?php echo $p_subgroup3; ?>" id="subgroup3" class="ininputautosave" saveme="Sub Group 3" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="15%">Process Name:</th>
								<td><input type="text" value="<?php echo $p_processname; ?>" id="processname" class="ininputautosave" saveme="Process Name" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="14%">Owner:</th>
								<td><input type="text" value="<?php echo $p_owner; ?>" id="owner" class="ininputautosave" saveme="Owner" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
							</tr>

							<tr>
								<th width="14%">Created Date:</th>
								<td><input type="text" value="<?php echo $p_createddate; ?>" id="createddate" class="ininputautosave editdatepicker" saveme="Created Date" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="15%">Modified Date:</th>
								<td><input type="text" value="<?php echo $p_modifieddate; ?>" id="modifieddate" class="ininputautosave editdatepicker" saveme="Modified Date" <?php if($makereadonly==1){ ?>readonly<?php } ?>></td>
								<th width="14%"></th>
								<td></td>
							</tr>
<?php if($makereadonly==0){ ?>
							<tr class="buttoncenter">
								<td colspan=6><input type="button" value="Submit" class="ininputedit"></td>
							</tr>
<?php } ?>
						</table>

			<?php
				}else
					die('Wrong parameters provided');
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			} //else die('Error Occured! Please try after sometime.');
			?>

				</div>
			</div>
		</article>
	</div>
<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
<script type="text/JavaScript" src="../assets/js/forms.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
<script>
$(document).ready(function() {
 <?php if($disabled == ""){ 
 if($makereadonly==0){ ?>
	$('.editdatepicker')
	.datepicker({
  dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true  // Custom format
});


	$('.ininputedit').click(function() {
		var formData = new FormData();
		formData.append('inauto', <?php echo $inid; ?>);
		formData.append("group", $("#group").val());
		formData.append("subgroup1", $("#subgroup1").val());
		formData.append("subgroup2", $("#subgroup2").val());
		formData.append("subgroup3", $("#subgroup3").val());
		formData.append("processname", $("#processname").val());
		formData.append("owner", $("#owner").val());
		formData.append("createddate", $("#createddate").val());
		formData.append("modifieddate", $("#modifieddate").val());

		$.ajax({
			type: 'post',
			url: 'assets/includes/processdocs.inc.php',
			data: formData,
			processData: false,
			contentType: false,
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						//swal("Saved", "");
						//alert("Saved");
				$.smallBox({
					title : "Saved",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
						//$("a#"+savename+"").removeClass("nodis");

						//parent.$("#indetails").load('assets/ajax/process-documents-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&inid=<?php echo $inid; ?>');
						//parent.otable.reload();
						$("#ei_datatable_fixed_column").DataTable().page.len(12).draw();
					}else if(results.error == 5)
					{
						//swal("Mandatory:","id", "warning");
				$.smallBox({
					title : "Mandatory: id required",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});
						//alert("Mandatory: id");
					}else{
						//alert("Error in request. Please try again later.");
						$.smallBox({
							title : "Error in request. Please try again later.",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
					}
				}else{
					//alert("Error in request. Please try again later.");
						$.smallBox({
							title : "Error in request. Please try again later.",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
				}
			}
		});
	});

<?php 
 }
	} ?>
});



function in_save(inid){
	/*if($("#edit-sss-submit"+sid).text()=="Edit"){
		$("#edit-sss-submit"+sid).text("Save");
		$("#cmacctable"+sid+" input[type=text]").prop("disabled", false);
		$(".sss #sss-fileupload").css("display", "block");
		$("#cmacctable"+sid+" input[type=text]").css("border", "1px solid #ccc");
		$("#s3display").css("display", "none");
	}else{
		sssload_details(sid);
		//$("#edit-sss-submit"+sid).text("Edit");
		//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
		//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
	}*/
	//"cmacctable"+sid
}

function in_cancel(inid){
		//$("#edit-sss-submit"+sid).text("Edit");
		//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
		//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
		//sssload_details(sid);
		clearme(inid);
		$("#in_datatable_fixed_column").DataTable().page.len(12).draw(false);
}
</script>












<?php }elseif(isset($_GET['action']) and $_GET['action'] == "createnew" and $makereadonly==0){ $disabled=""; ?>
	<style>
	.center{text-align:center;}
	.center button{margin:5px;}
	.ssse input[type=text]{width:90%;float:left;}
	.ssse select{width:90%;float:left;}
	.ssse textarea{width:98%;float:left;}
	.ssse #sss-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	.ssse .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	.ssse .ssscomment{width:90%;float:left;}
	.ssse th,.ssse td{border:none !important;padding:3px 10px !important;}
	.ssse .showversion-link{float:left;margin-left: 3px;}
	.ssse #logsshow{width:100%;
		height: 269px;
		overflow: auto;}
	#wid-id--77 .nopadds{padding:0 !important;}
	#cmacctable1{margin: 0 !important;
		margin-top: 2px !important;
		margin-bottom: 2px !important;}
	.buttoncenter{text-align:center;}
	</style>
		<div class="row ssse" id="createnew">
			<!-- NEW WIDGET START -->
			<article class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="width: 42%">
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--77" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Process Documents: New</h2>
						<span class="widget-icon" style="float: right;padding-right: 24px;cursor:pointer;" onclick="clearme(0,12,true,0)"> <i class="fa fa-times"></i> </span>
					</header>
					<div class="row nopadds">

							<table id="cmacctablenew" class="table table-striped table-bordered table-hover" style="clear: both">
								<tr>
									<th width="14%">Group:</th>
									<td><input type="text" value="" id="group" saveme="Group"></td>
									<th width="15%">Sub Group 1:</th>
									<td><input type="text" value="" id="subgroup1" saveme="Sub Group 1"></td>
									<th width="14%">Sub Group 2:</th>
									<td><input type="text" value="" id="subgroup2" saveme="Sub Group 2"></td>
								</tr>
								<tr>
									<th width="14%">Sub Group 3:</th>
									<td><input type="text" value="" id="subgroup3" saveme="Sub Group 3"></td>
									<th width="15%">Process Name:</th>
									<td><input type="text" value="" id="processname" saveme="Process Name"></td>
									<th width="14%">Owner:</th>
									<td><input type="text" value="" id="owner" saveme="Owner"></td>
								</tr>

								<tr>
									<th width="14%">Created Date:</th>
									<td><input type="text" class="datepicker" value="" id="createddate" saveme="Created Date"></td>
									<th width="15%">Modified Date:</th>
									<td><input type="text" class="datepicker" value="" id="modifieddate" saveme="Modified Date"></td>
									<th width="14%"></th>
									<td></td>
								</tr>

								<tr class="buttoncenter">
									<td colspan=6><input type="button" value="Submit" class="ininputnew"></td>
								</tr>							
							</table>

					</div>
				</div>
			</article>
		</div>
	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script>
	$(document).ready(function() {
	 <?php if($disabled == ""){ ?>
		$('.datepicker')
		.datepicker({
			format: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true
		});
	//$('.datetimepicker').datetimepicker();
	$(".cancel-edit-postm").trigger("click");
	  $('.ininputnew').click(function() {
		var formData = new FormData();
		formData.append('innew', 'new');
		formData.append("group", $("#group").val());
		formData.append("subgroup1", $("#subgroup1").val());
		formData.append("subgroup2", $("#subgroup2").val());
		formData.append("subgroup3", $("#subgroup3").val());
		formData.append("processname", $("#processname").val());
		formData.append("owner", $("#owner").val());
		formData.append("createddate", $("#createddate").val());
		formData.append("modifieddate", $("#modifieddate").val());

		$.ajax({
			type: 'post',
			url: 'assets/includes/processdocs.inc.php',
			data: formData,
			processData: false,
			contentType: false,
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						//swal("Saved", "");
						//alert("Saved");
						$.smallBox({
							title : "Saved",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
						$("#indetails").html("");
						//$("a#"+savename+"").removeClass("nodis");

						//parent.$("#indetails").load('assets/ajax/process-documents-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&inid=<?php //echo $inid; ?>');
						//parent.otable.reload();
						$("#ei_datatable_fixed_column").DataTable().page.len(12).draw();
						//parent.otable.ajax.reload();
					}else if(results.error == 5)
					{
						//swal("Mandatory:","id", "warning");
						//alert("Mandatory: id");
						$.smallBox({
							title : "Mandatory: id required",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
					}else{
						//alert("Error in request. Please try again later.");
						$.smallBox({
							title : "Error in request. Please try again later.",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
					}
				}else{
					//alert("Error in request. Please try again later.");
						$.smallBox({
							title : "Error in request. Please try again later.",
							content : "",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 4000
						});
				}
			}
		});

	  });
	<?php } ?>
	});



	function in_save(inid){
		/*if($("#edit-sss-submit"+sid).text()=="Edit"){
			$("#edit-sss-submit"+sid).text("Save");
			$("#cmacctable"+sid+" input[type=text]").prop("disabled", false);
			$(".sss #sss-fileupload").css("display", "block");
			$("#cmacctable"+sid+" input[type=text]").css("border", "1px solid #ccc");
			$("#s3display").css("display", "none");
		}else{
			sssload_details(sid);
			//$("#edit-sss-submit"+sid).text("Edit");
			//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
			//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
		}*/
		//"cmacctable"+sid
	}

	function in_cancel(inid){
			//$("#edit-sss-submit"+sid).text("Edit");
			//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
			//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
			//sssload_details(sid);
			clearme(inid);
			$("#in_datatable_fixed_column").DataTable().page.len(12).draw(false);
	}
	</script>


<?php } ?>