<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,55)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$cname=$_SESSION["company_id"];

if(isset($_GET['cmsacceditid']) and $_GET['action'] == "subdetails"){
	if(isset($_GET['cmsacceditid']) and $_GET['cmsacceditid'] > 0 and isset($_GET['cmid']) and $_GET['cmid'] > 0){
		$cmsaccid=$mysqli->real_escape_string(@trim($_GET['cmsacceditid']));
		$ccmid=$mysqli->real_escape_string(@trim($_GET['cmid']));
	}else
		die('Wrong parameters provided1');

	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		$disabled="";
	?>
		<style>
		#cmacctable1{
		border-spacing:5px !important;
		border-collapse:unset !important;
		}
		.center{text-align:center;}
		.center button{margin:5px;}
		#wid-id--999 label{width:90%;float:left;}
		#wid-id--999 #ctaccedit-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
		#wid-id--999 .dropzone .dz-preview .dz-details .dz-size,#wid-id--999 .dropzone-previews .dz-preview .dz-details .dz-size {
			bottom: -1px !important;
			left: 29px !important;
		}
		#wid-id--999 .cmcomment{width:90%;float:left;}
		#wid-id--999 .showversion-link{float:left;margin-left: 3px;}
		#wid-id--999 #logsshow{width:100%;
			height: 269px;
			overflow: auto;}
		#wid-id--99 .nopadds{padding:0 !important;}
		.blankline{height:0 !important;}
		.fullwidth{width:100% !important;}
		</style>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="move_back_acc(<?php echo $ccmid; ?>)" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
				</div>
			</article>
		</div>

		<!-- row -->
		<div class="row siterow">
			<?php
				$disabled="";
				/*if ($stmt = $mysqli->prepare('SELECT distinct ac.ContractAcctID,ac.ContractID,ac.AccountID,ac.MeterID,ac.ChargeID,ac.`Charge Type`,ac.Price,ac.PriceTypeID,ac.UOM,ac.FrequencyID, c.company_id,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$cmsaccid.'" LIMIT 1')) {*/
				if ($stmt = $mysqli->prepare('SELECT distinct ac.ContractAcctID,ac.ContractID,ac.AccountID,ac.MeterID,ac.ChargeID,ac.`Charge Type`,ac.Price,ac.PriceTypeID,ac.UOM,ac.FrequencyID, c.company_name,ac.s3_foldername FROM contract_accounts ac JOIN user u JOIN company c JOIN contracts cm WHERE cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$cmsaccid.'" LIMIT 1')) {
					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($ac_ContractAcctID,$ac_ContractID,$ac_AccountID,$ac_MeterID,$ac_ChargeID,$ac_Charge_Type,$ac_Price,$ac_PriceTypeID,$ac_UOM,$ac_FrequencyID,$ac_company_id,$ac_s3_foldername);
						$stmt->fetch();
							?>

						<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--999" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Edit Contract Account: <?php echo $ac_ContractAcctID; ?> </h2>

					</header>
					<div class="row" style="margin:1px !important;">
						<form id="cmedit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company Name<span class="required">* (Read only)</span>
										<p class="blankline">&nbsp;</p>
										<label class="input"> <i class="icon-append fa fa-user"></i>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT c.company_name FROM company c,contracts ct where c.company_id=ct.ClientID and ct.ContractID="'.$ac_ContractID.'" LIMIT 1')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__companyname);
													$stmt->fetch();
													echo '<input type="text" value="'.$__companyname.'" disabled required>';
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</label>
									</section>
									<section class="col col-6">AccountID<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_AccountID; ?>" class="cmaccinputautosave" saveme="AccountID">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"SupplierID",$disabled); ?>
									</section>


								</div>

								<div class="row">
									<section class="col col-6">MeterID<span class="required">*</span>
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_MeterID; ?>" class="cmaccinputautosave" saveme="MeterID">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"MeterID",$disabled); ?>
									</section>
									<section class="col col-6">ChargeID
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_ChargeID; ?>" class="cmaccinputautosave" saveme="ChargeID">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"ChargeID",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Charge Type
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_Charge_Type; ?>" class="cmaccinputautosave" saveme="Charge Type">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"ChargeType",$disabled); ?>
									</section>
									<section class="col col-6">Price
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_Price; ?>" class="cmaccinputautosave" saveme="Price">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"Price",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">PriceTypeID
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_PriceTypeID; ?>" class="cmaccinputautosave" saveme="PriceTypeID">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"PriceTypeID",$disabled); ?>
									</section>
									<section class="col col-6">UOM
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_UOM; ?>" class="cmaccinputautosave" saveme="UOM">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"UOM",$disabled); ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">FrequencyID
										<p class="blankline">&nbsp;</p>
										<label class="input">
											<input type="text" value="<?php echo $ac_FrequencyID; ?>" class="cmaccinputautosave" saveme="FrequencyID">
											<input type="hidden" id="cmacceditid" value="<?php echo $ac_ContractAcctID; ?>">
										</label>
										<?php echo checkversionavailability($mysqli,"contract_accounts",$ac_ContractAcctID,"FrequencyID",$disabled); ?>
									</section>
									<section class="col col-6"></section>
								</div>

								<div class="row">
									<section class="col col-12 fullwidth">Attached Documents
										<p class="blankline">&nbsp;</p>
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
								<div id="cmaccedits3display"></div>
									<div class="dropzone dz-clickable" id="ctaccedit-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
									<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
								<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
								<!--<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>-->
								<script href="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js"></script>
								<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
								<script type="text/javascript">
									$(document).ready(function(){
										$('#cmaccedits3display').html('');
										$('#cmaccedits3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractaccid=<?php echo $ac_ContractAcctID; ?>');
											Dropzone.autoDiscover = false;
											var myDropzone = new Dropzone("div#ctaccedit-fileupload", {
												paramName: "ctaccedits3filesupload",
												addRemoveLinks: false,
												url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>&contractaccid=<?php echo $ac_ContractAcctID; ?>",
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
																$('#cmaccedits3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractaccid=<?php echo $ac_ContractAcctID; ?>');
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

									});
								</script>

									</section>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</article>
			<?php echo showlogs($cmsaccid,'contract_accounts','cmaccdetails','assets/ajax/contract-manager-peditsub.php?&ct='.rand(9,88).'&action=subdetails&cmsacceditid='.$cmsaccid.'&cmid='.$ccmid,$disabled,$cmsaccid); ?>
	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});
  $('.cmaccinputautosave').blur(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  function autosave(savename,saveval){

	var formData = new FormData();
	formData.append('maaaceditauto', $("#cmacceditid").val());
	formData.append('maaaceditsavename', savename);
	formData.append('maaaceditvalue', saveval);

	$.ajax({
		type: 'post',
		url: 'assets/includes/contractmanager.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
					$("a#"+savename+"").removeClass("nodis");
					$("#logshow<?php echo $cmsaccid; ?>").load("assets/ajax/showlogs.php?pkey=<?php echo $cmsaccid; ?>&tname=contract_accounts&load=true&disb=<?php echo @trim($disabled); ?>&tuid=cmaccdetails&tuurl=<?php echo urlencode('assets/ajax/contract-manager-peditsub.php?action=subdetails&cmsacceditid='.$cmsaccid.'&cmid='.$ccmid.'&logid='.$cmsaccid); ?>&ct=<?php echo rand(0,100); ?>");
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
</script>


							<?php
					}else
						die('Wrong parameters provided');
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();
				}//else
					//die('Error Occured! Please try after sometime.');
					?>
		</div>






<?php
	}else if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) and isset($_SESSION['user_id'])){
?>
		<style>
		#cmacctable1{
		border-spacing:5px !important;
		border-collapse:unset !important;
		}
		</style>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="move_back_acc(<?php echo $ccmid; ?>)" src="../assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
				</div>
			</article>
		</div>

		<!-- row -->
		<div class="row siterow">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--1" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Contract Account</h2>

					</header>
					<div class="row">
					<?php
				$disabled="";
				/*if ($stmt = $mysqli->prepare('SELECT distinct ac.ContractAcctID,ac.ContractID,ac.AccountID,ac.MeterID,ac.ChargeID,ac.`Charge Type`,ac.Price,ac.PriceTypeID,ac.UOM,ac.FrequencyID, c.company_name,ac.s3_foldername FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN contract_accounts ac WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$cmsaccid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').'  LIMIT 1')) {*/
				if ($stmt = $mysqli->prepare('SELECT distinct ac.ContractAcctID,ac.ContractID,ac.AccountID,ac.MeterID,ac.ChargeID,ac.`Charge Type`,ac.Price,ac.PriceTypeID,ac.UOM,ac.FrequencyID, c.company_name,ac.s3_foldername FROM contract_accounts ac JOIN user u JOIN company c JOIN contracts cm WHERE cm.ClientID=c.company_id and ac.ContractID=cm.ContractID and ac.ContractAcctID="'.$cmsaccid.'"'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').'  LIMIT 1')) {
					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($ac_ContractAcctID,$ac_ContractID,$ac_AccountID,$ac_MeterID,$ac_ChargeID,$ac_Charge_Type,$ac_Price,$ac_PriceTypeID,$ac_UOM,$ac_FrequencyID,$ac_company_name,$ac_s3_foldername);
						$stmt->fetch();
					?>
								<table id="cmacctable" class="table table-bordered table-striped" style="clear: both">
									<tbody>
										<tr>
											<th colspan="3">Contract Number: <?php echo $ac_ContractID;?></th>
										</tr>
										<tr>
											<th colspan="3">Contract Account: <?php echo $ac_ContractAcctID;?></th>
										</tr>
										<tr>
											<td style="width:33%;"><b>Client:</b> <?php echo $ac_company_name;?></td>
											<td style="width:33%;"><b>Account ID:</b> <?php echo $ac_AccountID;?></td>
											<td style="width:34%;"><b>Meter ID:</b> <?php echo $ac_MeterID;?></td>
										</tr>
										<tr>
											<td style="width:33%;"><b>Charge ID:</b> <?php echo $ac_ChargeID;?></td>
											<td style="width:33%;"><b>Charge Type:</b> <?php echo $ac_Charge_Type;?></td>
											<td style="width:34%;"><b>Price:</b> <?php echo $ac_Price;?></td>
										</tr>
										<tr>
											<td style="width:33%;"><b>Price Type ID:</b> <?php echo $ac_PriceTypeID;?></td>
											<td style="width:33%;"><b>UOM:</b> <?php echo $ac_UOM;?></td>
											<td style="width:34%;"><b>Frequency ID:</b> <?php echo $ac_FrequencyID;?></td>
										</tr>
									</tbody>
								</table>
							<?php
					}else
						die('Wrong parameters provided');
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();
				}//else
					//die('Error Occured! Please try after sometime.');
					?>
					</div>
				</div>
			</article>
		</div>

	<?php if($ac_s3_foldername != "" or ($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)){ ?>
		<section id="widget-grid" class="cms3display">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Attached Documents: Contract Account</h2>

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
								<div id="cmsacc3display"></div>
								<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
								<!--<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>-->
								<script href="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js"></script>
								<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
								<script type="text/javascript">
								$(document).ready(function(){
									$('#cmsacc3display').html('');
									$('#cmsacc3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractaccid=<?php echo $ac_ContractAcctID; ?>');
								});
								</script>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

			</div>
				</article>
			<!-- end row -->

		</section>
		<!-- end widget grid -->
	<?php }
	}else echo false;
}
?>
