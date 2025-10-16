<?php
//error_reporting(E_ALL);
require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];



if(isset($_GET['msid']) and isset($_GET['action']) and $_GET['action']='edit' and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){
	if(isset($_GET['msid']) and $_GET['msid'] != "" and $_GET['msid'] > 0)
		$msid=$mysqli->real_escape_string(@trim($_GET['msid']));
	else
		die('Wrong parameters provided');
?>
	<style>
	#maedit-dialog-message .col-12{width:100% !important;}
	#maedit-dialog-message .required{vertical-align: bottom;
    line-height: 1;
    color: red;}
	</style>
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row siterow">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark ma" id="wid-id--1" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Master ID: <?php echo $msid; ?></h2>

				</header>
				<div class="row" style="margin:1px !important;">
		<?php
			if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
				$sqll = "SELECT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name,s3_foldername,c.company_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and ma.MasterID='".$msid."' LIMIT 1";
			}elseif($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
				$sqll = "SELECT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name,ma.s3_foldername,c.company_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$msid."' LIMIT 1";
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			if ($stmt = $mysqli->prepare($sqll)) {
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($map_ClientId,$map_MasterId,$map_VendorId,$map_Status,$map_StartDate,$map_EndDate,$map_Version,$map_ReviewedBy,$map_Notes,$vp_VendorName,$s3_foldername,$vp_companyname);
					$stmt->fetch();

					if($map_StartDate=="0000-00-00") $map_StartDate=date("Y-m-d");
					if($map_EndDate=="0000-00-00") $map_EndDate=date("Y-m-d");
						?>
		<div id="maedit-dialog-message" title="View Master Supply Agreements">
						<form id="maedit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" placeholder="Supplier" value="<?php echo $vp_companyname; ?>" Disabled>
										</label>
									</section>
									<section class="col col-6">Supplier
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" placeholder="Supplier" value="<?php echo $vp_VendorName; ?>" Disabled>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Status
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" placeholder="Status" value="<?php echo $map_Status; ?>" Disabled>
										</label>
									</section>
									<section class="col col-6">Version
										<label class="input"> <i class="icon-prepend fa fa-sort-numeric-asc"></i>
											<input type="number" placeholder="Version" value="<?php echo $map_Version; ?>" Disabled>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" placeholder="Start date" value="<?php echo date("m/d/Y",strtotime($map_StartDate)); ?>" Disabled>
										</label>
									</section>
									<section class="col col-6">End Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" placeholder="End date" value="<?php echo date("m/d/Y",strtotime($map_EndDate)); ?>" Disabled>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Reviewed By
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" placeholder="Reviewed By" value="<?php echo $map_ReviewedBy; ?>" Disabled>
										</label>
									</section>
									<section class="col col-6">Notes
										<label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
											<input type="text" placeholder="Notes" value="<?php echo $map_Notes; ?>" Disabled>
										</label>
									</section>
								</div>
								<?php if($s3_foldername != ""){ ?>
								<div class="row">
									<section class="col col-12">Attached documents
										<div id="mas3display"></div>
										<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
										<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
										<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
										<script type="text/javascript">
										$(document).ready(function(){
											$('#mas3display').html('');
											$('#mas3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $msid; ?>');
										});
										</script>
									</section>
								</div>
								<?php } ?>
							</fieldset>
						</form>
	</div>

<!-- end row -->



			<?php
				}else{
					die('Wrong parameters provided');
				}
			}else
				die('Error Occured! Please try after sometime.1');
			?>
				</div>
			</div>
		</article>
	</div>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script type="text/javascript">
$(document).ready(function() {

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
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {

		};
	</script>
<?php
}elseif(isset($_GET['msid']) and isset($_GET['action']) and $_GET['action']='edit' and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){
	if(isset($_GET['msid']) and $_GET['msid'] != "" and $_GET['msid'] > 0)
		$msid=$mysqli->real_escape_string(@trim($_GET['msid']));
	else
		die('Wrong parameters provided');
?>
	<style>
	#maedit-dialog-message #ma-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#maedit-dialog-message .dropzone .dz-preview .dz-details .dz-size,#maedit-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#maedit-dialog-message .col-12{width:100% !important;}
	#maedit-dialog-message .required{vertical-align: bottom;
    line-height: 1;
    color: red;}
	.center{text-align:center;}
	.center button{margin:5px;}
	#wid-id--88 label{width:90%;float:left;}
	#wid-id--88 #sss-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#wid-id--88 .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#wid-id--88 .ssscomment{width:90%;float:left;}
	#wid-id--88 th,.ssse td{border:none !important;padding:3px 10px !important;}
	#wid-id--88 .showversion-link{float:left;margin-left: 3px;}
	#wid-id--88 #logsshow{width:100%;
		height: 269px;
		overflow: auto;}
	#wid-id--88 .nopadds{padding:0 !important;}
	.blankline{height:0 !important;}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row siterow">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark ma" id="wid-id--88" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Master ID: <?php echo $msid; ?></h2>

				</header>
				<div class="row" style="margin:1px !important;">
		<?php
			$disabled="";
			if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
				$sqll = "SELECT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name,s3_foldername,c.company_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."' and ma.MasterID='".$msid."' LIMIT 1";
			}elseif($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
				$sqll = "SELECT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name,ma.s3_foldername,c.company_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and ma.MasterID='".$msid."' LIMIT 1";
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			if ($stmt = $mysqli->prepare($sqll)) {
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($map_ClientId,$map_MasterId,$map_VendorId,$map_Status,$map_StartDate,$map_EndDate,$map_Version,$map_ReviewedBy,$map_Notes,$vp_VendorName,$s3_foldername,$company_name);
					$stmt->fetch();

					if($map_StartDate=="0000-00-00") $map_StartDate=date("Y-m-d");
					if($map_EndDate=="0000-00-00") $map_EndDate=date("Y-m-d");
						?>
		<div id="maedit-dialog-message" title="Edit Master Supply Agreements">
						<form id="maedit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-user"></i>
									<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
										<select name="editmacid" id="editmacid" placeholder="Company" class="selectautosave" saveme="ClientID">
											<option value="">&nbsp;&nbsp;Select Company</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__id,$__companyname);
													while($stmt->fetch()){
														echo "<option value='".$__id."' ".($map_ClientId == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__companyname."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
									<?php }else echo $company_name; ?>
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"ClientID",$disabled); ?>
									</section>
									<section class="col col-6">Supplier<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="select">
										<select name="editmasupplier" id="editmasupplier" placeholder="Supplier" class="selectautosave" saveme="VendorID">
											<option value="">&nbsp;&nbsp;Select Supplier</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name);
													while($stmt->fetch()){
														echo "<option value='".$__vid."' ".($map_VendorId == $__vid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"VendorID",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Status
										<p class="blankline">&nbsp;</p>
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="editmastatus" id="editmastatus" placeholder="Status" class="selectautosave" saveme="Status">
												<option value="Active" <?php echo ($map_Status == "Active"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Active</option>
												<option value="Inactive" <?php echo ($map_Status == "Inactive"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Inactive</option>
											</select>
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"Status",$disabled); ?>
									</section>
									<section class="col col-6">Version
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-prepend fa fa-sort-numeric-asc"></i>
											<input type="number" name="editmaversion" id="editmaversion" placeholder="Version" value="<?php echo $map_Version; ?>" class="inputautosave" saveme="Version">
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"Version",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="editmastartdate" id="editmastartdate" placeholder="Start date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($map_StartDate)); ?>" saveme="Start Date">
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"Start Date",$disabled); ?>
									</section>
									<section class="col col-6">End Date
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="editmaenddate" id="editmaenddate" placeholder="End date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($map_EndDate)); ?>" saveme="End Date">
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"End Date",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Reviewed By
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="editmareviewedby" id="editmareviewedby" placeholder="Reviewed By" value="<?php echo $map_ReviewedBy; ?>" class="inputautosave" saveme="Reviewed By">
											<input type="hidden" name="editma" id="editma" value="edit">
											<input type="hidden" name="editmapid" id="editmapid" value="<?php echo $map_MasterId;?>">
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"Reviewed By",$disabled); ?>
									</section>
									<section class="col col-6">Notes
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
											<input type="text" name="editmanotes" id="editmanotes" placeholder="Notes" value="<?php echo $map_Notes; ?>" class="inputautosave" saveme="Notes">
										</label>
										<?php echo checkversionavailability($mysqli,"master_agreements",$map_MasterId,"Notes",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-12">Attached documents
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">
								<?php } ?>
									<div id="mas3display"></div>
								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
									<div class="dropzone dz-clickable" id="ma-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
				
								<?php } ?>
									<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
									<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
									<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
									<script type="text/javascript">
  								<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>                                                                         
                                                                        var script = document.createElement("script");
                                                                        script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
                                                                        script.onload = loadedContent;
                                                                        document.head.append(script);
                                                                        function loadedContent(){
										Dropzone.autoDiscover = false;
										var myDropzone = new Dropzone("div#ma-fileupload", {
											paramName: "mas3filesupload",
											addRemoveLinks: false,
											url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>&masterid=<?php echo $msid; ?>",
											maxFiles:10,
											uploadMultiple: true,
											parallelUploads:10,
											timeout: 300000,
											maxFilesize: 3000,
											//autoProcessQueue: false,
											init: function() {
												myDropz = this;
												myDropz.on("successmultiple", function(file, result) {
													if (result != false)
													{
														var results = JSON.parse(result);
														if(results.error == "")
														{
															//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
															$('#mas3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $msid; ?>');
														}else if(results.error == 5)
														{
															Swal.fire("Error in request.","Please try again later.", "warning");
														}else{
															Swal.fire("Error in request.","Please try again later.", "warning");
														}
													}else{
														Swal.fire("","Error in request. Please try again later.", "warning");
													}
												});
												myDropz.on("complete", function(file) {
												   myDropz.removeAllFiles(true);
												});
												myDropz.on("uploadprogress", function(file, progress, bytesSent) {
													if (file.previewElement) {
															var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
															progressElement.style.width = progress + "%";
															file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
													}
												});
											}
										});
                                                                        }  
                                                                <?php } ?>
									$(document).ready(function(){
										$('#mas3display').html('');
										$('#mas3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $msid; ?>');

								
									});
									</script>
									</section>
								</div>
							</fieldset>
						</form>
	</div>

<!-- end row -->


			<?php
				}else{
					die('Wrong parameters provided');
				}
			}else
				die('Error Occured! Please try after sometime.1');
			?>
				</div>
			</div>
		</article>
		<?php echo showlogs($map_MasterId,'master_agreements','masteragreementdetails','assets/ajax/master-agreements-pedit.php?ct='.rand(9,88).'&action=edit&msid='.$map_MasterId,$disabled); ?>
	</div>

	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js?v=1"></script>
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});
  $('.inputautosave').blur(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  $('.selectautosave').change(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  function autosave(savename,saveval){

	var formData = new FormData();
	formData.append('maauto', $("#editmapid").val());
	formData.append('masavename', savename);
	formData.append('mavalue', saveval);

	$.ajax({
		type: 'post',
		url: 'assets/includes/masteragreements.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
					//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
					$("a#"+savename+"").removeClass("nodis");
					$("#logshow").load("assets/ajax/showlogs.php?pkey=<?php echo $map_MasterId; ?>&tname=master_agreements&load=true&disb=<?php echo @trim($disabled); ?>&tuid=masteragreementdetails&tuurl=<?php echo urlencode('assets/ajax/master-agreements-pedit.php?action=edit&msid='.$map_MasterId); ?>&ct=<?php echo rand(0,100); ?>");
				}else if(results.error == 5)
				{
					Swal.fire("Mandatory:","Plese fill all required fields", "warning");
				}else{
					Swal.fire("Error in request.","Please try again later.", "warning");
				}
			}else{
				Swal.fire("","Error in request. Please try again later.", "warning");
			}
		}
	  });

  }
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
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {

		};
	</script>
<?php
}elseif(isset($_GET['action']) and $_GET['action']='add' and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){
?>
	<style>
	#maadd-dialog-message #ma-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#maadd-dialog-message .dropzone .dz-preview .dz-details .dz-size,#maadd-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#maadd-dialog-message .col-12{width:100% !important;}
	#maadd-dialog-message .required{vertical-align: bottom;
    line-height: 1;
    color: red;}
	#maadd-dialog-message footer{text-align:center;}
	#maadd-dialog-message footer button{float:none !important;}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<b><img id="mvbk" onclick="move_back()" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>
			</div>
		</article>
	</div>

	<!-- row -->
	<div class="row siterow">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark ma" id="wid-id--1" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Add Master Supply Agreements</h2>

				</header>
				<div class="row" style="margin:1px !important;">
		<div id="maadd-dialog-message" title="Add Master Supply Agreements">
						<form id="maadd-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company<span class="required">*</span>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select name="addmacid" id="addmacid" placeholder="Company" class="selectautosave" saveme="ClientID">
											<option value="">&nbsp;&nbsp;Select Company</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__id,$__companyname);
													while($stmt->fetch()){
														echo "<option value='".$__id."'>&nbsp;&nbsp;".$__companyname."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>
									<section class="col col-6">Supplier<span class="required">*</span>
										<label class="select">
										<select name="addmasupplier" id="addmasupplier" placeholder="Supplier" class="selectautosave" saveme="VendorID">
											<option value="">&nbsp;&nbsp;Select Supplier</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT DISTINCT vendor_id,vendor_name FROM vendor order by vendor_name')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__vid,$__vendor_name);
													while($stmt->fetch()){
														echo "<option value='".$__vid."'>&nbsp;&nbsp;".$__vendor_name."</option>";
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Status
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="addmastatus" id="addmastatus" placeholder="Status" class="selectautosave" saveme="Status">
												<option value="Active" SELECTED='SELECTED'>&nbsp;&nbsp;Active</option>
												<option value="Inactive">&nbsp;&nbsp;Inactive</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Version
										<label class="input"> <i class="icon-prepend fa fa-sort-numeric-asc"></i>
											<input type="number" name="addmaversion" id="addmaversion" placeholder="Version" value="" class="inputautosave" saveme="Version">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="addmastartdate" id="addmastartdate" placeholder="Start date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="" saveme="Start Date">
										</label>
									</section>
									<section class="col col-6">End Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="addmaenddate" id="addmaenddate" placeholder="End date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="" saveme="End Date">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Reviewed By
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addmareviewedby" id="addmareviewedby" placeholder="Reviewed By" value="" class="inputautosave" saveme="Reviewed By">
											<input type="hidden" name="addma" id="addma" value="new">
										</label>
									</section>
									<section class="col col-6">Notes
										<label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
											<input type="text" name="addmanotes" id="addmanotes" placeholder="Notes" value="" class="inputautosave" saveme="Notes">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-12">Attached documents
										<div class="dropzone dz-clickable" id="ma-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary" id="ma-submit">
									Save
								</button>
							</footer>
						</form>
	</div>

<!-- end row -->
				</div>
			</div>
		</article>
	</div>

	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
var script = document.createElement("script");
script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
script.onload = loadedContent;
document.head.append(script);
function loadedContent(){
        var responsegot=0;
	var currentFile = null;
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#ma-fileupload", {
		paramName: "maaddfilesupload",
		addRemoveLinks: true,
		url: "assets/includes/masteragreements.inc.php",
		maxFiles:10,
		uploadMultiple: true,
		parallelUploads:10,
		timeout: 300000,
		maxFilesize: 3000,
		autoProcessQueue: false,
		init: function() {
			myDropz = this;


				$("#ma-submit").on("click", function(e) {
				  // Make sure that the form isn't actually being sent.
				  e.preventDefault();
				  e.stopPropagation();


					if($("#addmacid").val() !="" && $("#addmasupplier").val() !=""){
						if (myDropz.getQueuedFiles().length > 0)
						{
							myDropzone.on("sending", function(file, xhr, formData) {
								formData.append('ClientID', $("#addmacid").val());
								formData.append('VendorID', $("#addmasupplier").val());
								formData.append('Status', $("#addmastatus").val());
								formData.append('Version', $("#addmaversion").val());
								formData.append('Start@Date', $("#addmastartdate").val());
								formData.append('End@Date', $("#addmaenddate").val());
								formData.append('Reviewed@By', $("#addmareviewedby").val());
								formData.append('Notes', $("#addmanotes").val());
								formData.append('maadd', 'new');
							});
							myDropz.processQueue();

							myDropz.on("successmultiple", function(file, result) {
								if (result != false)
								{
									var results = JSON.parse(result);
									if(results.error == "")
									{
										swal("","Added", "success");
										$("#maadd-checkout-form").get(0).reset();
									}else if(results.error == 5)
									{
										swal("At least one entry mandatory in:","Company, Vendor", "warning");
									}else{
										swal("Error in request.","Please try again later.", "warning");
									}
								}else{
									swal("","Error in request. Please try again later.", "warning");
								}
							});
							myDropz.on("complete", function(file) {
							   myDropz.removeAllFiles(true);
							});
							myDropz.on("uploadprogress", function(file, progress, bytesSent) {
								if (file.previewElement) {
										var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
										progressElement.style.width = progress + "%";
										file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
								}
							});
							$('#maadd-checkout-form').trigger("reset")
						} else {
								//$('#maadd-checkout-form').submit();
								var formData = new FormData();
								formData.append('ClientID', $("#addmacid").val());
								formData.append('VendorID', $("#addmasupplier").val());
								formData.append('Status', $("#addmastatus").val());
								formData.append('Version', $("#addmaversion").val());
								formData.append('Start@Date', $("#addmastartdate").val());
								formData.append('End@Date', $("#addmaenddate").val());
								formData.append('Reviewed@By', $("#addmareviewedby").val());
								formData.append('Notes', $("#addmanotes").val());
								formData.append('maadd', 'new');

								$.ajax({
									type: 'post',
									url: 'assets/includes/masteragreements.inc.php',
									data: formData,
									processData: false,
									contentType: false,
									success: function (result) {
										if (result != false)
										{
											var results = JSON.parse(result);
											if(results.error == "")
											{
												swal("","Added", "success");
												$("#maadd-checkout-form").get(0).reset();
											}else if(results.error == 5)
											{
												swal("At least one entry mandatory in:","Company, Vendor", "warning");
											}else{
												swal("Error in request.","Please try again later.", "warning");
											}
										}else{
											swal("","Error in request. Please try again later.", "warning");
										}
									}
								  });
						}
					}else{
						swal("At least one entry mandatory in:","Company, Vendor", "warning");
					}
				});

		}
	});    
}            
$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});


	var responsegot=0;
	var currentFile = null;
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
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {

		};
	</script>
<?php
}
?>
