<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];
$c_id=$_SESSION["company_id"];

//if($_SESSION["user_id"] != 1) die("Under Construction!");


if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");


if(isset($_GET['sid']) and isset($_GET['action']) and $_GET['action'] == "details"){
	if(isset($_GET['sid']) and @trim($_GET['sid']) != "" and $_GET['sid'] > 0)
		$sid=$mysqli->real_escape_string(@trim($_GET['sid']));
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
.autowidth{width:auto !important;}
.w-90{width:90% !important;}
</style>
	<div class="row ssse" id="<?php echo $sid; ?>">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--77" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Ticket Number: <?php echo $sid; ?></h2>
					<span class="widget-icon" style="float: right;padding-right: 24px;cursor:pointer;" onclick="clearme(<?php echo $sid; ?>,12,true,-1,1)"> <i class="fa fa-times"></i> </span>
				</header>
				<div class="row nopadds">
				<?php
				$disabled=$s3_foldername="";
				$address=array();
				$todaydate=date('Y-m-d H:i:s');
				if ($stmt = $mysqli->prepare('Select ss.id,ss.assigned,ss.site_number,ss.site_number,ss.location_type,ss.site_name,ss.region,ss.entity_name,ss.division,ss.federal_tax_id,ss.gl_site,ss.site_address1,ss.account_address1,ss.site_address2,ss.account_address2,ss.site_city,ss.account_city,ss.site_state,ss.account_state,ss.site_zip,ss.account_zip,ss.site_contact_name,ss.billing_address1,ss.site_contact_title,ss.billing_address2,ss.site_contact_telephone,ss.billing_city,ss.site_contact_fax,ss.billing_state,ss.billing_zip,ss.leased_location,ss.landlord_name,ss.lease_start_date,ss.landlord_phone,ss.lease_end_date,ss.landlord_fax,ss.tenant,ss.sale_date,ss.landlord_email,ss.sublet,ss.landlord_address1,ss.landlord_address2,ss.owned_location,ss.landlord_city,ss.landlord_state,ss.sale_owner,ss.landlord_zip,ss.date_requested,ss.deposit_preference,ss.construction,ss.check_deposit_ok,ss.meter_change,ss.credit_card_deposit_ok,ss.utility_service_type,ss.vendor_name,ss.account_number,ss.previous_account_number,ss.meter,ss.special_instructions,ss.status,ss.status_date,ss.request_type,ss.date_contacted,ss.contacted_method,ss.confirmation_number,ss.deposit,ss.deposit_method,ss.billing_cycle,ss.notes,ss.vendor_phone1,ss.vendor_phone2,ss.vendor_email1,ss.vendor_email2,ss.vendor_fax1,ss.vendor_fax2,ss.s3_foldername,ss.company_id From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where ss.id="'.$sid.'" and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:' Where ss.id="'.$sid.'"').' LIMIT 1')) {




				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($ssid,$assigned,$id,$site_number,$location_type,$site_name,$region,$entity_name,$division,$federal_tax_id,$gl_site,$site_address1,$account_address1,$site_address2,$account_address2,$site_city,$account_city,$site_state,$account_state,$site_zip,$account_zip,$site_contact_name,$billing_address1,$site_contact_title,$billing_address2,$site_contact_telephone,$billing_city,$site_contact_fax,$billing_state,$billing_zip,$leased_location,$landlord_name,$lease_start_date,$landlord_phone,$lease_end_date,$landlord_fax,$tenant,$sale_date,$landlord_email,$sublet,$landlord_address1,$landlord_address2,$owned_location,$landlord_city,$landlord_state,$sale_owner,$landlord_zip,$date_requested,$deposit_preference,$construction,$check_deposit_ok,$meter_change,$credit_card_deposit_ok,$utility_service_type,$vendor_name,$account_number,$previous_account_number,$meter,$special_instructions,$status,$status_date,$request_type,$date_contacted,$contacted_method,$confirmation_number,$deposit,$deposit_method,$billing_cycle,$notes,$vendor_phone1,$vendor_phone2,$vendor_email1,$vendor_email2,$vendor_fax1,$vendor_fax2,$s3_foldername,$s3_company_id);
					$stmt->fetch();

					if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and ($status == "Completed" or $status == "Cancelled")) $disabled=" disabled";
					else $disabled="";

					$ts=$id.rand(650,900);
					if($status_date=="0000-00-00 00:00:00") $status_date=$todaydate;
						?>
						<table id="cmacctable<?php echo $sid; ?>" class="table table-striped table-bordered table-hover" style="clear: both">
							<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) { ?>
							<tr>
								<th width="14%">Assigned:</th>
								<td colspan="1">
								<?php
									if ($stmt1 = $mysqli->prepare('Select user_id,firstname,lastname From user  Where usergroups_id=2')) {
									$stmt1->execute();
									$stmt1->store_result();
									if ($stmt1->num_rows > 0) {
										echo '<select class="ssselectautosave autowidth" saveme="assigned"><option value="">Select Employee</option>';
										$stmt1->bind_result($usid,$usfname,$uslsname);
										while($stmt1->fetch()){ ?>
											<option value="<?php echo $usid; ?>" <?php echo (($usid == $assigned and !empty($assigned)) ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;<?php echo $usfname." ".$uslsname; ?></option>
										<?php }
										echo '</select>';
									}else echo "No Employee present!";
								}else{
									header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
									exit();
								}




							?>
								</td>
								<th width="14%">Company:</th>
								<td colspan="1">
								<?php
									if ($stmtc = $mysqli->prepare('SELECT company_id,company_name FROM `company` where company_id !=1 and company_id != 2 order by company_name')) {
											$stmtc->execute();
											$stmtc->store_result();
											if ($stmtc->num_rows > 0) {
												echo '<select class="ssselectautosave autowidth w-90" saveme="company_id"><option value="">Select Employee</option>';
												$stmtc->bind_result($ccompany_id,$ccompany_name);
												while($stmtc->fetch()){ ?>
													<option value="<?php echo $ccompany_id; ?>" <?php echo (($ccompany_id == $s3_company_id) ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;<?php echo $ccompany_name; ?></option>
												<?php }
												echo '</select>';
											}else echo "No Company to show!";
										}else{
											header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
											exit();
										}
								?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<th width="14%">Status:</th>
								<td>
								<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) { ?>
									<select class="ssselectautosave" saveme="status">
										<option value="Completed" <?php echo ($status == "Completed" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Completed</option>
										<option value="Cancelled" <?php echo ($status == "Cancelled" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Cancelled</option>
										<option value="Pending" <?php echo ($status == "Pending" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Pending</option>
										<option value="Client Hold" <?php echo ($status == "Client Hold" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Client Hold</option>
										<option value="Vendor Hold" <?php echo ($status == "Vendor Hold" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Vendor Hold</option>
									</select><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"status",$disabled); ?>
								<?php }else{ echo $status; } ?>
								</td>
								<th width="15%">Status Date:</th>
								<td>
								<?php
									if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
								?>
									<input type="text" value="<?php echo @date('Y-m-d H:m:s',strtotime('-4 hour',strtotime($status_date))); ?>" class="sssinputautosave" saveme="status_date">

								<?php }else{ echo @date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($status_date))); } ?></td>
								<th width="14%">Request Type:</th>
								<td><?php echo $request_type; ?></td>
							</tr>

							<tr>
								<th width="14%">Entity Name:</th>
								<td><input type="text" value="<?php echo $entity_name; ?>" class="sssinputautosave" saveme="entity_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"entity_name",$disabled); ?></td>
								<th width="15%">Tax ID:</th>
								<td><input type="text" value="<?php echo $federal_tax_id; ?>" class="sssinputautosave" saveme="federal_tax_id" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"federal_tax_id",$disabled); ?></td>
								<th width="14%">Site Number:</th>
								<td><input type="text" value="<?php echo $site_number; ?>" class="sssinputautosave" saveme="site_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_number",$disabled); ?></td>
							</tr>

							<tr>
								<th width="15%">Tax ID:</th>
								<td><input type="text" value="<?php echo $federal_tax_id; ?>" class="sssinputautosave" saveme="federal_tax_id" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"federal_tax_id",$disabled); ?></td>
								<th width="14%">Site Number:</th>
								<td><input type="text" value="<?php echo $site_number; ?>" class="sssinputautosave" saveme="site_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_number",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Utility Service Type:</th>
								<td><input type="text" value="<?php echo $utility_service_type; ?>" class="sssinputautosave" saveme="utility_service_type" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"utility_service_type",$disabled); ?></td>
								<th width="15%">Vendor Name:</th>
								<td><input type="text" value="<?php echo $vendor_name; ?>" class="sssinputautosave" saveme="vendor_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_name",$disabled); ?></td>
								<th width="14%">Site Name:</th>
								<?php ++$ts; ?>
								<td><input type="text" value="<?php echo $site_name; ?>" class="sssinputautosave" saveme="site_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_name",$disabled); ?>
								 </td>
							</tr>

							<tr>
								<th width="14%">Date Requested:</th>
								<td><input type="text" value="<?php echo $date_requested; ?>" class="sssinputautosave" saveme="date_requested" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"date_requested",$disabled); ?></td>
								<th width="15%">Date Contacted:<i style="color:red;">*</i></th>
								<td><input type="text" id="date_contacted" value="<?php echo $date_contacted; ?>" class="sssinputautosave" saveme="date_contacted" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"date_contacted",$disabled); ?></td>
								<th width="14%">Contacted Method:<i style="color:red;">*</i></th>
								<td><input type="text" id="contacted_method" value="<?php echo $contacted_method; ?>" class="sssinputautosave" saveme="contacted_method" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"contacted_method",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Prev Account Number:</th>
								<td><input type="text" value="<?php echo $previous_account_number; ?>" class="sssinputautosave" saveme="previous_account_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"previous_account_number",$disabled); ?></td>
								<th width="15%">Account Number:<i style="color:red;">*</i></th>
								<td><input type="text" id="account_number" value="<?php echo $account_number; ?>" class="sssinputautosave" saveme="account_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_number",$disabled); ?></td>
								<th width="14%">Meter:</th>
								<td><input type="text" value="<?php echo $meter; ?>" class="sssinputautosave" saveme="meter" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"meter",$disabled); ?></td>
							</tr>

							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>

							<tr>
								<td></td>
								<th>Site</th>
								<th>Account</th>
								<th>Billing</th>
								<th>Landlord</th>
								<td></td>
							</tr>

							<tr>
								<th width="14%">Address1:</th>
								<td><input type="text" value="<?php echo $site_address1; ?>" class="sssinputautosave" saveme="site_address1" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_address1",$disabled); ?></td>
								<td><input type="text" value="<?php echo $account_address1; ?>" class="sssinputautosave" saveme="account_address1" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_address1",$disabled); ?></td>
								<td><input type="text" value="<?php echo $billing_address1; ?>" class="sssinputautosave" saveme="billing_address1" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_address1",$disabled); ?></td>
								<td><input type="text" value="<?php echo $landlord_address1; ?>" class="sssinputautosave" saveme="landlord_address1" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_address1",$disabled); ?></td>
								<td></td>
							</tr>

							<tr>
								<th width="14%">Address2:</th>
								<td><input type="text" value="<?php echo $site_address2; ?>" class="sssinputautosave" saveme="site_address2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_address2",$disabled); ?></td>
								<td><input type="text" value="<?php echo $account_address2; ?>" class="sssinputautosave" saveme="account_address2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_address2",$disabled); ?></td>
								<td><input type="text" value="<?php echo $billing_address2; ?>" class="sssinputautosave" saveme="billing_address2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_address2",$disabled); ?></td>
								<td><input type="text" value="<?php echo $landlord_address2; ?>" class="sssinputautosave" saveme="landlord_address2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_address2",$disabled); ?></td>
								<td></td>
							</tr>

							<tr>
								<th width="14%">City:</th>
								<td><input type="text" value="<?php echo $site_city; ?>" class="sssinputautosave" saveme="site_city" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_city",$disabled); ?></td>
								<td><input type="text" value="<?php echo $account_city; ?>" class="sssinputautosave" saveme="account_city" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_city",$disabled); ?></td>
								<td><input type="text" value="<?php echo $billing_city; ?>" class="sssinputautosave" saveme="billing_city" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_city",$disabled); ?></td>
								<td><input type="text" value="<?php echo $landlord_city; ?>" class="sssinputautosave" saveme="landlord_city" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_city",$disabled); ?></td>
								<td></td>
							</tr>

							<tr>
								<th width="14%">State:</th>
								<td><input type="text" value="<?php echo $site_state; ?>" class="sssinputautosave" saveme="site_state" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_state",$disabled); ?></td>
								<td><input type="text" value="<?php echo $account_state; ?>" class="sssinputautosave" saveme="account_state" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_state",$disabled); ?></td>
								<td><input type="text" value="<?php echo $billing_state; ?>" class="sssinputautosave" saveme="billing_state" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_state",$disabled); ?></td>
								<td><input type="text" value="<?php echo $landlord_state; ?>" class="sssinputautosave" saveme="landlord_state" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_state",$disabled); ?></td>
								<td></td>
							</tr>

							<tr>
								<th width="14%">Zip:</th>
								<td><input type="text" value="<?php echo $site_zip; ?>" class="sssinputautosave" saveme="site_zip" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_zip",$disabled); ?></td>
								<td><input type="text" value="<?php echo $account_zip; ?>" class="sssinputautosave" saveme="account_zip" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_zip",$disabled); ?></td>
								<td><input type="text" value="<?php echo $billing_zip; ?>" class="sssinputautosave" saveme="billing_zip" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_zip",$disabled); ?></td>
								<td><input type="text" value="<?php echo $landlord_zip; ?>" class="sssinputautosave" saveme="landlord_zip" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_zip",$disabled); ?></td>
								<td></td>
							</tr>

							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>

							<tr>
								<th width="14%">Leased Location:</th>
								<td><?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="leased_location">
										<option value="Y" <?php echo ($leased_location == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($leased_location == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
								<?php }else{ ?><input type="text" value="<?php echo $leased_location; ?>" class="sssinputautosave" saveme="leased_location" <?php echo $disabled; ?>><?php } ?>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"leased_location",$disabled); ?>
								</td>
								<th width="15%">Owned Location:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="owned_location">
										<option value="Y" <?php echo ($owned_location == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($owned_location == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $owned_location; ?>" class="sssinputautosave" saveme="owned_location" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"owned_location",$disabled); ?>
								</td>
								<th width="14%">Vendor Phone 1:</th>
								<td><input type="text" value="<?php echo $vendor_phone1; ?>" class="sssinputautosave" saveme="vendor_phone1" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_phone1",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Lease Start Date:</th>
								<td><input type="text" value="<?php echo $lease_start_date; ?>" class="sssinputautosave" saveme="lease_start_date" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"lease_start_date",$disabled); ?></td>
								<th width="15%">Purchase Date:</th>
								<td><input type="text" value="<?php echo $sale_date; ?>" class="sssinputautosave" saveme="sale_date" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"sale_date",$disabled); ?></td>
								<th width="14%">Vendor Phone 2:</th>
								<td><input type="text" value="<?php echo $vendor_phone2; ?>" class="sssinputautosave" saveme="vendor_phone2" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_phone2",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Lease End Date:</th>
								<td><input type="text" value="<?php echo $lease_end_date; ?>" class="sssinputautosave" saveme="lease_end_date" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"lease_end_date",$disabled); ?></td>
								<th width="15%">Previous Owner:</th>
								<td><input type="text" value="<?php echo $sale_owner; ?>" class="sssinputautosave" saveme="sale_owner" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"sale_owner",$disabled); ?></td>
								<th width="14%">Vendor Email 1:</th>
								<td><input type="text" value="<?php echo $vendor_email1; ?>" class="sssinputautosave" saveme="vendor_email1" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_email1",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Landlord Name:</th>
								<td><input type="text" value="<?php echo $landlord_name; ?>" class="sssinputautosave" saveme="landlord_name" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_name",$disabled); ?></td>
								<th width="15%">New Construction:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="construction">
										<option value="Y" <?php echo ($construction == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($construction == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $construction; ?>" class="sssinputautosave" saveme="construction" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"construction",$disabled); ?>
								</td>
								<th width="14%">Vendor Email 2:</th>
								<td><input type="text" value="<?php echo $vendor_email2; ?>" class="sssinputautosave" saveme="vendor_email2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_email2",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Contact Number:</th>
								<td><input type="text" value="<?php echo $landlord_phone; ?>" class="sssinputautosave" saveme="landlord_phone" <?php echo $disabled; ?>><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_phone",$disabled); ?></td>
								<th width="15%">New Meters Required:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="meter_change">
										<option value="Y" <?php echo ($meter_change == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($meter_change == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $meter_change; ?>" class="sssinputautosave" saveme="meter_change" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"meter_change",$disabled); ?>
								</td>
								<th width="14%">Vendor Fax 1:</th>
								<td><input type="text" value="<?php echo $vendor_fax1; ?>" class="sssinputautosave" saveme="vendor_fax1" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_fax1",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Contact FAX:</th>
								<td><input type="text" value="<?php echo $landlord_fax; ?>" class="sssinputautosave" saveme="landlord_fax" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_fax",$disabled); ?></td>
								<th width="15%">Sublet:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="sublet">
										<option value="Y" <?php echo ($sublet == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($sublet == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $sublet; ?>" class="sssinputautosave" saveme="sublet" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"sublet",$disabled); ?>
								</td>
								<th width="14%">Vendor Fax 2:</th>
								<td><input type="text" value="<?php echo $vendor_fax2; ?>" class="sssinputautosave" saveme="vendor_fax2" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_fax2",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Contact Email:</th>
								<td><input type="text" value="<?php echo $landlord_email; ?>" class="sssinputautosave" saveme="landlord_email" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"landlord_email",$disabled); ?></td>
								<th width="15%">Previous Tenant:</th>
								<td><input type="text" value="<?php echo $tenant; ?>" class="sssinputautosave" saveme="tenant" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"tenant",$disabled); ?></td>
								<th width="14%">Billing Cycle:</th>
								<td><input type="text" value="<?php echo $billing_cycle; ?>" class="sssinputautosave" saveme="billing_cycle" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"billing_cycle",$disabled); ?></td>
							</tr>

							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>

							<tr>
								<th width="14%">Location Type:</th>
								<td><input type="text" value="<?php echo $location_type; ?>" class="sssinputautosave" saveme="location_type" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"location_type",$disabled); ?></td>
								<th width="15%">Deposit Preference:</th>
								<td><input type="text" value="<?php echo $deposit_preference; ?>" class="sssinputautosave" saveme="deposit_preference" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"deposit_preference",$disabled); ?></td>
								<th width="14%">Confirmation Number:</th>
								<td><input type="text" value="<?php echo $confirmation_number; ?>" class="sssinputautosave" saveme="confirmation_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"confirmation_number",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Region:</th>
								<td><input type="text" value="<?php echo $region; ?>" class="sssinputautosave" saveme="region" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"region",$disabled); ?></td>
								<th width="15%">Deposit Method:</th>
								<td><input type="text" value="<?php echo $deposit_method; ?>" class="sssinputautosave" saveme="deposit_method" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"deposit_method",$disabled); ?></td>
								<th width="14%">Site Contact Name:</th>
								<td><input type="text" value="<?php echo $site_contact_name; ?>" class="sssinputautosave" saveme="site_contact_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_contact_name",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Division:</th>
								<td><input type="text" value="<?php echo $division; ?>" class="sssinputautosave" saveme="division" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"division",$disabled); ?></td>
								<th width="15%">Check Deposit Ok:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="check_deposit_ok">
										<option value="Y" <?php echo ($check_deposit_ok == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($check_deposit_ok == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $check_deposit_ok; ?>" class="sssinputautosave" saveme="check_deposit_ok" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"check_deposit_ok",$disabled); ?>
								</td>
								<th width="14%">Site Contact Title:</th>
								<td><input type="text" value="<?php echo $site_contact_title; ?>" class="sssinputautosave" saveme="site_contact_title" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_contact_title",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">GL Site:</th>
								<td><input type="text" value="<?php echo $gl_site; ?>" class="sssinputautosave" saveme="gl_site" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"gl_site",$disabled); ?></td>
								<th width="15%">Credit Card Deposit Ok:</th>
								<td>
									<?php if($disabled==""){ ?>
									<select class="ssselectautosave" saveme="credit_card_deposit_ok">
										<option value="Y" <?php echo ($credit_card_deposit_ok == "Y" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
										<option value="N" <?php echo ($credit_card_deposit_ok == "N" ? "SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
									</select>
									<?php }else{ ?><input type="text" value="<?php echo $credit_card_deposit_ok; ?>" class="sssinputautosave" saveme="credit_card_deposit_ok" <?php echo $disabled; ?>><?php } ?>
									<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"credit_card_deposit_ok",$disabled); ?>
								</td>
								<th width="14%">Contact Telephone:</th>
								<td><input type="text" value="<?php echo $site_contact_telephone; ?>" class="sssinputautosave" saveme="site_contact_telephone" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_contact_telephone",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%"></th>
								<td></td>
								<th width="15%">Deposit:<i style="color:red;">*</i></th>
								<td><input type="text" id="deposit" value="<?php echo $deposit; ?>" class="sssinputautosave" saveme="deposit" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"deposit",$disabled); ?></td>
								<th width="14%">Contact Fax:</th>
								<td><input type="text" value="<?php echo $site_contact_fax; ?>" class="sssinputautosave" saveme="site_contact_fax" <?php echo $disabled; ?>>
								<input type="hidden" name="editsssid" id="editsssid" value="<?php echo $sid; ?>">
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_contact_fax",$disabled); ?></td>
							</tr>
							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>

							<tr>
								<th width="14%">Special Instructions:</th>
								<td colspan="5"><textarea rows="4" class="sssinputautosave" saveme="special_instructions"  <?php echo $disabled; ?>><?php echo @stripslashes($special_instructions); ?></textarea><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"special_instructions",$disabled); ?></td>
							</tr>
							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>

							<tr>
								<th width="14%">Notes:</th>
								<td colspan="5"><textarea rows="4" class="sssinputautosave" saveme="notes"  <?php echo $disabled; ?>><?php echo @stripslashes($notes); ?></textarea><?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"notes",$disabled); ?></td>
							</tr>

							<tr>
								<td colspan="6"></td>
							</tr>

							<tr>
								<th width="14%">Attached Documents:</th>
								<td colspan="5">
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">
									<div id="ssss3display"></div>
									<div class="dropzone dz-clickable" id="sss-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
									<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
									<script type="text/javascript">
                                                                            var script = document.createElement("script");
                                                                            script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
                                                                            script.onload = loadedContent;
                                                                            document.head.append(script);
                                                                            function loadedContent(){
										Dropzone.autoDiscover = false;
										var myDropzone = new Dropzone("div#sss-fileupload", {
											paramName: "sssfilesupload",
											addRemoveLinks: false,
											url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>&sssid=<?php echo $ssid; ?>",
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
															$('#ssss3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&sssid=<?php echo $ssid; ?>');
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

									$(document).ready(function(){
										$('#ssss3display').html('');
										$('#ssss3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&sssid=<?php echo $ssid; ?>');
									});
									</script>
								<?php if($disabled == ""){ ?>
								<?php }else{ ?>

								<?php } ?>
								</td>
							</tr>
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


		<?php
		//$fieldnamearr=array("site_number"=>"Site Number","location_type"=>"Location Type","site_name"=>"Site Name","region"=>"Region","entity_name"=>"Entity Name","division"=>"Division","federal_tax_id"=>"Tax ID","gl_site"=>"GL Site","site_address1"=>"Site Address1","account_address1"=>"Account Address1","site_address2"=>"Site Address2","account_address2"=>"Account Address2","site_city"=>"Site City","account_city"=>"Account City","site_state"=>"Site State","account_state"=>"Account State","site_zip"=>"Site Zip","account_zip"=>"Account Zip","site_contact_name"=>"Site Contact Name","billing_address1"=>"Billing Address1","site_contact_title"=>"Site Contact Title","billing_address2"=>"Billing Address2","site_contact_telephone"=>"Contact Telephone","billing_city"=>"Billing City","site_contact_fax"=>"Contact Fax","billing_state"=>"Billing State","billing_zip"=>"Billing Zip","leased_location"=>"Leased Location","landlord_name"=>"Landlord Name","lease_start_date"=>"Lease Start Date","landlord_phone"=>"Contact Number","lease_end_date"=>"Lease End Date","landlord_fax"=>"Contact FAX","tenant"=>"Tenant","sale_date"=>"Purchase Date","landlord_email"=>"Contact Email","sublet"=>"Sublet","landlord_address1"=>"Landlord Address1","landlord_address2"=>"Landlord Address2","owned_location"=>"Owned Location","landlord_city"=>"Landlord City","landlord_state"=>"Landlord State","sale_owner"=>"Previous Owner","landlord_zip"=>"Landlord Zip","date_requested"=>"Date Requested","deposit_preference"=>"Deposit Preference","construction"=>"Construction","check_deposit_ok"=>"Check Deposit Ok","meter_change"=>"New Meters Required","credit_card_deposit_ok"=>"Credit Card Deposit Ok","utility_service_type"=>"Utility Service Type","vendor_name"=>"Vendor Name","account_number"=>"Account Name","previous_account_number"=>"Prev Account Number","meter"=>"Meter","special_instructions"=>"Special Instructions","status"=>"Status","date_completed"=>"Date Completed","request_type"=>"Request Type","status_date"=>"Status Date","contacted_method"=>"Contacted Method","confirmation_number"=>"Confirmation Number","deposit"=>"Deposit","deposit_method"=>"Deposit Method","billing_cycle"=>"Billing Cycle","notes"=>"Notes","vendor_phone1"=>"Vendor Phone 1","vendor_phone2"=>"Vendor Phone 2","vendor_email1"=>"Vendor Email 1","vendor_email2"=>"Vendor Email 2","vendor_fax1"=>"Vendor Fax 1","vendor_fax2"=>"Vendor Fax 2");

		echo showlogs($ssid,'startstop_status','ssdetails','assets/ajax/start-stop-status-pedit.php?action=details&sid='.$ssid,$disabled);
		?>
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
		format: 'yyyy/mm/dd HH:mm:ss ',
		//format: 'mm/dd/yyyy ',
            changeMonth: true,
            changeYear: true
	});
//$('.datetimepicker').datetimepicker();

	var tmpfocus=null;
	$(document).off("click",".ssselectautosave");
	$(document).on("click",".ssselectautosave",function() {
        //var tmpfocus= this;alert(tmpfocus.attr('class'));
		//parent.$("#wid-id--77 header").html(tmpfocus.attr('class'));
			prev_val = $(this).val();
    });


  $('.sssinputautosave').blur(function() {
	 // var tmpfocus= $(":focus");//alert(tmpfocus.attr('id'));
	 autosave($(this).attr("saveme"),$(this).val());
	 //setTimeout(function(){ tmpfocus.focus(); }, 10000);
  });

  $('.ssselectautosave').change(function() {
		<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
		if($(this).attr("saveme")=="company_id"){
			if (!confirm('Apply the new Company default (e.g. Billing Address, Tax ID, etc.)?')) {
				$(this).val(prev_val);
				return false;
			}
		}
		<?php } ?>
	 //var tmpfocus= $(":focus");
	 autosave($(this).attr("saveme"),$(this).val());
	//setTimeout(function(){ tmpfocus.focus(); }, 10000);
  });

  function autosave(savename,saveval){//if(saveval=="") return false;
	var formData = new FormData();
	formData.append('sssauto', $("#editsssid").val());
	formData.append('ssssavename', savename);
	formData.append('sssvalue', saveval);

	$.ajax({
		type: 'post',
		url: 'assets/includes/services.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
//var oldtable = $("#ss_datatable_fixed_column").DataTable();
//var oldinfo = oldtable.page.info();
//var oldpage = oldinfo.page;
//oldtable.page.len(6).draw(false);
//oldtable.page( 0 ).draw( false );


					//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
					$("a#"+savename+"").removeClass("nodis");

					//Disabled temporary for losing focus
					//$("#ssdetails").load("assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid=<?php echo $ssid; ?>");
					$("#logshow").load("assets/ajax/showlogs.php?pkey=<?php echo $ssid; ?>&tname=startstop_status&load=true&disb=<?php echo @trim($disabled); ?>&tuid=ssdetails&tuurl=<?php echo urlencode('assets/ajax/start-stop-status-pedit.php?action=details&sid='.$ssid); ?>&ct=<?php echo rand(0,100); ?>");
					//parent.$('#sstable').load("assets/ajax/start-stop-status-pedit.php?load=true&short=true&pgno=2&ct=<?php mt_rand(2,77); ?>");


					//////////////////////////////////////////////////////////
					var fticketno=$("#list-startstop-status #ss_datatable_fixed_column #fticketno").val();
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
					var fcompany=$("#list-startstop-status #ss_datatable_fixed_column #fcompany").val();
					var fusername=$("#list-startstop-status #ss_datatable_fixed_column #fusername").val();
					var flastname=$("#list-startstop-status #ss_datatable_fixed_column #flastname").val();
<?php } ?>
					var fstatus=$("#list-startstop-status #ss_datatable_fixed_column #fstatus option:selected").val();
					var fstatusdate=$("#list-startstop-status #ss_datatable_fixed_column #fstatusdate").val();
					var frequesttype=$("#list-startstop-status #ss_datatable_fixed_column #frequesttype option:selected").val();
					var fsitenumber=$("#list-startstop-status #ss_datatable_fixed_column #fsitenumber").val();
					var fsitename=$("#list-startstop-status #ss_datatable_fixed_column #fsitename").val();
					var futilservicetype=$("#list-startstop-status #ss_datatable_fixed_column #futilservicetype option:selected").val();
					var fvendorname=$("#list-startstop-status #ss_datatable_fixed_column #fvendorname").val();
					var faccno=$("#list-startstop-status #ss_datatable_fixed_column #faccno").val();
					/////////////////////////////////////////////////////////
					$("#sstable #ss_datatable_fixed_column").DataTable().ajax.reload( null, false );
					<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
						$("#ssdetails").load('assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid=<?php echo $sid; ?>');
					<?php } ?>


					/////////////////////////////////////////////////////////
					$("#list-startstop-status #ss_datatable_fixed_column #fticketno").val(fticketno);
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
					$("#list-startstop-status #ss_datatable_fixed_column #fcompany").val(fcompany);
					$("#list-startstop-status #ss_datatable_fixed_column #fusername").val(fusername);
					$("#list-startstop-status #ss_datatable_fixed_column #flastname").val(flastname);
<?php } ?>
					$("#list-startstop-status #ss_datatable_fixed_column #fstatus option:selected").val(fstatus);
					$("#list-startstop-status #ss_datatable_fixed_column #fstatusdate").val(fstatusdate);
					$("#list-startstop-status #ss_datatable_fixed_column #frequesttype option:selected").val(frequesttype);
					$("#list-startstop-status #ss_datatable_fixed_column #fsitenumber").val(fsitenumber);
					$("#list-startstop-status #ss_datatable_fixed_column #fsitename").val(fsitename);
					$("#list-startstop-status #ss_datatable_fixed_column #futilservicetype option:selected").val(futilservicetype);
					$("#list-startstop-status #ss_datatable_fixed_column #fvendorname").val(fvendorname);
					$("#list-startstop-status #ss_datatable_fixed_column #faccno").val(faccno);


					/////////////////////////////////////////////////////////


//////var newtable = $("#sstable #ss_datatable_fixed_column").DataTable();
//var newinfo = newtable.page.info();alert(JSON.stringify(newinfo));
//var newpage = newinfo.page;//alert(newpage);

/////newtable.page.len(6).draw('page');
//newtable.page(1).draw(true);
//////$("#sstable #ss_datatable_fixed_column").DataTable().page(1).draw('page');

				}else if(results.error == 5)
				{
					Swal.fire("Mandatory:","Date Contacted, Contacted Method, Account Number and Deposit", "warning");
				}<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>else if(results.error == 6)
				{
					Swal.fire("Mandatory:","Entity Name,Tax ID,Billing Address1,Billing Address2,Billing City,Billing State,Billing Zip Code in Company Defaults", "warning");

					if(savename=="company_id"){
							$("#ssdetails").load('assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid=<?php echo $sid; ?>');
					}

				}<?php } ?>else{
					Swal.fire("Error in request.","Please try again later.", "warning");
				}
			}else{
				Swal.fire("","Error in request. Please try again later.", "warning");
			}
		}
	});
  }
<?php } ?>
});



function ssse_save(sid){
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

function ssse_cancel(sid){
		//$("#edit-sss-submit"+sid).text("Edit");
		//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
		//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
		//sssload_details(sid);

		clearme(sid);
		$("#ss_datatable_fixed_column").DataTable().page.len(12).draw(false);
}
</script>












<?php }elseif(isset($_GET['load'])){
$subquery=((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?"?showdemo=1":"");
?>


<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>

<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

<link href="/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Start/Stop Status </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
#ss_datatable_fixed_column_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#ss_datatable_fixed_column_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#ss_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
#ss_datatable_fixed_column .sssdrp{width:auto !important;}
#ss_datatable_fixed_column .sssdrp {
    font-weight: 400 !important;
}
</style>
					<div class="widget-body no-padding" id="list-startstop-status">
						<table id="ss_datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%" data-turbolinks="false" >
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
										<input type="text" class="form-control" id="fticketno" placeholder="Filter Ticket Number" />
									</th>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
									<th class="hasinput">
										<input type="text" class="form-control" id="fcompany" placeholder="Filter Company" />
									</th>
									<th class="hasinput msearch">
										<input type="text" class="form-control" id="fusername" placeholder="Filter User Name" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" id="flastname" placeholder="Filter Lastname" />
									</th>
<?php } ?>
									<th class="hasinput">
										<select class="form-control sssdrp" id="fstatus">
											<option value="">All</option>
<?php
	//if ($stmt_sss = $mysqli->prepare('Select DISTINCT ss.status From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up, sites s Where s.site_number=ss.site_number and up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:''))) {
	if ($stmt_sss = $mysqli->prepare('Select DISTINCT ss.status From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where up.company_id=ss.company_id':''))) {
        $stmt_sss->execute();
        $stmt_sss->store_result();
        if ($stmt_sss->num_rows > 0) {
			$stmt_sss->bind_result($sssstatus);
			while($stmt_sss->fetch()) {
				if($sssstatus == "") continue;
				echo '<option value="'.$sssstatus.'" '.($sssstatus=="Pending"?"SELECTED":"").'>'.$sssstatus.'</option>';

			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
?>
										</select>
									</th>
									<th class="hasinput">
										<select class="form-control sssdrp" id="frequesttype">
											<option value="">Filter Request Type</option>
<?php
	if ($stmt_sss = $mysqli->prepare('Select DISTINCT ss.request_type From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:''))) {

//('Select DISTINCT ss.request_type From startstop_status ss '.($_SESSION["group_id"] == 3 ? ', user up, sites s Where s.site_number=ss.site_number and up.company_id=ss.company_id and  up.id = '.$_SESSION["user_id"]:''))) {

        $stmt_sss->execute();
        $stmt_sss->store_result();
        if ($stmt_sss->num_rows > 0) {
			$stmt_sss->bind_result($sssrequest_type);
			while($stmt_sss->fetch()) {
				if($sssstatus == "") continue;
				echo '<option value="'.$sssrequest_type.'">'.$sssrequest_type.'</option>';

			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
?>
										</select>
									</th>
									<th class="hasinput">
										<input type="text" id="fsitenumber" class="form-control" placeholder="Filter Site Number" />
									</th>
									<th class="hasinput">
										<input type="text" id="fsitename" class="form-control" placeholder="Filter Site Name" />
									</th>
									<th class="hasinput">
										<select class="form-control sssdrp" id="futilservicetype">
											<option value="">Filter Utility Service Type</option>
<?php
	if ($stmt_sss = $mysqli->prepare('Select DISTINCT ss.utility_service_type From startstop_status ss '.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? ', user up Where up.company_id=ss.company_id and  up.user_id = '.$_SESSION["user_id"]:''))) {
        $stmt_sss->execute();
        $stmt_sss->store_result();
        if ($stmt_sss->num_rows > 0) {
			$stmt_sss->bind_result($sssutility_service_type);
			while($stmt_sss->fetch()) {
				if($sssstatus == "") continue;
				echo '<option value="'.$sssutility_service_type.'">'.$sssutility_service_type.'</option>';

			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
?>
										</select>
									</th>
									<th class="hasinput">
										<input type="text" id="fvendorname" class="form-control" placeholder="Filter Vendor Name" />
									</th>
									<th class="hasinput">
										<input type="text" id="faccno" class="form-control" placeholder="Filter Account Number" />
									</th>
									<th class="hasinput">
										<input type="text" id="fstatusdate" class="form-control" placeholder="Filter Date Requested" />
									</th>
									<th class="hasinput">
										<input type="text" id="fstatusdate" class="form-control" placeholder="Filter Status Date" />
									</th>
									<th class="hasinput">
										<input type="text" id="fdate_contacted" class="form-control" placeholder="Filter Date Contacted" />
									</th>
									<th class="hasinput">
										<input type="text" id="fdeposit" class="form-control" placeholder="Filter Deposit" />
									</th>
									<th class="hasinput">
										<input type="text" id="fentity_name" class="form-control" placeholder="Filter Entity Name" />
									</th>
									<th class="hasinput">
										<input type="text" id="fmeter" class="form-control" placeholder="Filter Meter" />
									</th>
									<th class="hasinput">
										<input type="text" id="fsite_address1" class="form-control" placeholder="Filter Site Address1" />
									</th>
									<th class="hasinput">
										<input type="text" id="fsite_city" class="form-control" placeholder="Filter Site City" />
									</th>
									<th class="hasinput">
										<input type="text" id="fsite_state" class="form-control" placeholder="Filter Site State" />
									</th>
									<th class="hasinput">
										<input type="text" id="fsite_zip" class="form-control" placeholder="Filter Site Zip" />
									</th>
								</tr>
								<tr>
									<th data-hide="phone">Ticket Number</th>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
									<th data-hide="phone">Company</th>
									<th data-hide="phone">User Name</th>
									<th data-hide="phone">Lastname</th>
<?php } ?>
									<th>Status</th>
									<th data-hide="phone,tablet">Request Type </th>
									<th data-hide="phone,tablet">Site Number </th>
									<th data-hide="phone,tablet">Site Name </th>
									<th data-hide="phone,tablet">Utility Service Type </th>
									<th data-hide="phone,tablet">Vendor Name</th>
									<th data-hide="phone,tablet">Account Number</th>
									<th data-hide="phone">Date Requested </th>
									<th data-hide="phone">Status Date </th>
									<th data-hide="phone,tablet">Date Contacted</th>
									<th data-hide="phone,tablet">Deposit</th>
									<th data-hide="phone,tablet">Entity Name</th>
									<th data-hide="phone,tablet">Meter</th>
									<th data-hide="phone,tablet">Site Address1</th>
									<th data-hide="phone,tablet">Site City</th>
									<th data-hide="phone,tablet">Site State</th>
									<th data-hide="phone,tablet">Site Zip</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
			<div id="mmmm"></div>
<script src="/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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
			 //$.fn.dataTable.moment( 'HH:mm MMM D, YY' );
			 //$.fn.dataTable.moment( 'MM/DD/YYYY HH:mm:ss ' );
//$.fn.dataTable.moment( 'MMM DD,YYYY hh:mm:ss A' );
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
			                return $(data).text();
			            }
			        }
			    }
			};

		/* COLUMN FILTER  */
			var ssseotable = $("#ss_datatable_fixed_column").DataTable( {
				"lengthMenu": [[1, 2, 3, 4, 5, 6, 12, 25, -1], [1, 2, 3, 4, 5, 6, 12, 25, "All"]],
				"pageLength": 12,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"dom": 'Blfrtip',
				"serverSide": true,
				"stateSave": true,

				"processing": false,
				"drawCallback" : function(settings) {
					 $(".dots-cont").hide();
				},
				"preDrawCallback": function (settings) {
					$(".dots-cont").show();
				},

				"buttons": [
					$.extend( true, {}, fixNewLine, {
							extend: 'copyHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							extend: 'excelHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							extend: 'csvHtml5'
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
					//'pdfHtml5'
					{
						'text': 'Columns',
						'extend': 'colvis',
						'exclude': [0]
					},
					{
						'text': 'Reset',
						'action': function ( e, dt, node, config ) {
							resetfilter(ssseotable);
						}
					}
				],
				"language": {
            "infoFiltered": ""
        },
        "columnDefs": [
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return data +' '+ row[3]+'';
                },
                "targets": 2
            },
            //{"visible": false, "targets": [3]}
/*{
    "targets": [3,12,13,14,15,16,17,18,19], //Comma separated values
    "visible": false,
    "searchable": true }*/
<?php }else{ ?>
/*{
    "targets": [3,9,10,11,12,13,14,15,16], //Comma separated values
    "visible": false,
    "searchable": true }*/

<?php } ?>
        ],

				"autoWidth" : true,
				"ajax": "assets/ajax/sss_processing.php<?php echo $subquery; ?>"
			});
<?php
$tmpfilterarr=array();
$filterarr=array("flastname","fsite_zip","fsite_state","fsite_city","fsite_address1","fmeter","fentity_name","fdeposit","fdate_contacted");
foreach($filterarr as $kytr){
	if(isset($_COOKIE[$kytr]) and !empty($_COOKIE[$kytr])){ }else{
		if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
		 if($kytr=="flastname"){$tmpfilterarr[]=3; }
		 elseif($kytr=="fsite_zip"){$tmpfilterarr[]=20; }
		 elseif($kytr=="fsite_state"){$tmpfilterarr[]=19; }
		 elseif($kytr=="fsite_city"){$tmpfilterarr[]=18; }
		 elseif($kytr=="fsite_address1"){$tmpfilterarr[]=17; }
		 elseif($kytr=="fmeter"){$tmpfilterarr[]=16; }
		 elseif($kytr=="fentity_name"){$tmpfilterarr[]=15; }
		 elseif($kytr=="fdeposit"){$tmpfilterarr[]=14; }
		 elseif($kytr=="fdate_contacted"){$tmpfilterarr[]=13; }
		}else{
		 if($kytr=="flastname"){$tmpfilterarr[]=3; }
		 elseif($kytr=="fsite_zip"){$tmpfilterarr[]=17; }
		 elseif($kytr=="fsite_state"){$tmpfilterarr[]=16; }
		 elseif($kytr=="fsite_city"){$tmpfilterarr[]=15; }
		 elseif($kytr=="fsite_address1"){$tmpfilterarr[]=14; }
		 elseif($kytr=="fmeter"){$tmpfilterarr[]=13; }
		 elseif($kytr=="fentity_name"){$tmpfilterarr[]=12; }
		 elseif($kytr=="fdeposit"){$tmpfilterarr[]=11; }
		 elseif($kytr=="fdate_contacted"){$tmpfilterarr[]=10; }
		}

	}
}

?>
			ssseotable.columns( [<?php echo implode(",",$tmpfilterarr); ?> ] ).visible( false );
			<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			//ssseotable.columns( [3,12,13,14,15,16,17,18,19] ).visible( false );
			<?php }else{ ?>
			//ssseotable.columns( [3,9,10,11,12,13,14,15,16] ).visible( false );
			<?php } ?>
	    /*var ssseotable = $('#datatable_fixed_column').DataTable({
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
	    //$("#ss_datatable_fixed_column .sssdrp").on( 'keyup change', function () {
	    $(document).off("keyup change","#ss_datatable_fixed_column .sssdrp");
	    $(document).on("keyup change","#ss_datatable_fixed_column .sssdrp",function() {
			var gthis=$(this);
	        ssseotable
	            .column( $(this).parent().index()+':visible' )
	            .search(this.value)
	            .draw();
	         setCookie(gthis.attr("id"),gthis.val(),4);
	    } );

	    //$("#ss_datatable_fixed_column thead th:not(.msearch) input[type=text]").on( 'keyup change', function () {
	    $(document).off("keyup change","#ss_datatable_fixed_column thead th:not(.msearch) input[type=text]");
	    $(document).on("keyup change","#ss_datatable_fixed_column thead th:not(.msearch) input[type=text]",function() {
			var gthis=$(this);
	        ssseotable
	            .column( gthis.parent().index()+':visible' )
	            //.column( gthis.parent().index() )
	            .search(this.value)
	            .draw();
			setCookie(gthis.attr("id"),gthis.val(),4);
	    });


	    //$("#ss_datatable_fixed_column thead th.msearch input[type=text]").on( 'keyup change', function () {
	    $(document).off("keyup change","#ss_datatable_fixed_column thead th.msearch input[type=text]");
	    $(document).on("keyup change","#ss_datatable_fixed_column thead th.msearch input[type=text]",function() {
			var gthis=$(this);
			//$('#mmmm').text(JSON.stringify(ssseotable.column(2).search(this.value)));
			ssseotable.columns([2]).search($(this).val()).draw();
	        //ssseotable.columns([3]).search($(this).val()).draw();
			setCookie(gthis.attr("id"),gthis.val(),4);

	    });

		 var searchactive =ssseotable
			.column(<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>4<?php }else{ ?>1<?php } ?>)
			.search('Pending')
			.draw();


<?php
$filterarr=array("fticketno","fcompany","fusername","flastname","fstatusdate","fsitenumber","fsitename","fvendorname","faccno","fstatus","frequesttype","futilservicetype","fsite_zip","fsite_state","fsite_city","fsite_address1","fmeter","fentity_name","fdeposit","fdate_contacted");
foreach($filterarr as $kytr){
	if(isset($_COOKIE[$kytr]) and !empty($_COOKIE[$kytr])){ ?>
	$("#<?php echo $kytr; ?>").val("<?php echo $_COOKIE[$kytr]; ?>");
	ssseotable
		.column( $("#<?php echo $kytr; ?>").parent().index()+':visible' )
		.search( "<?php echo $_COOKIE[$kytr]; ?>" )
		.draw();
<?php	}
}

?>
	};

	function resetfilter(ssseotable){
<?php
$filterarr=array("fticketno","fcompany","fusername","flastname","fstatusdate","fsitenumber","fsitename","fvendorname","faccno","frequesttype","futilservicetype","fsite_zip","fsite_state","fsite_city","fsite_address1","fmeter","fentity_name","fdeposit","fdate_contacted");
foreach($filterarr as $kytr){
	//@unset($_COOKIE[$kytr]);
	//if(isset($_COOKIE[$kytr])) setcookie($kytr, "", time() - 3600);
	//@setcookie($kytr, "", time() - 3600);
	 ?>
	 if(getCookie("<?php echo $kytr; ?>") != null){
	 eraseCookie("<?php echo $kytr; ?>");
	$("#<?php echo $kytr; ?>").val("");
	ssseotable
		.column( $("#<?php echo $kytr; ?>").parent().index()+':visible' )
		.search( "" )
		.draw();
	}
<?php
}
	//@unset($_COOKIE["pending"]);
	//if(isset($_COOKIE["pending"])) setcookie("pending", "", time() - 3600);
	//@setcookie("pending", "", time() - 3600);
?>
	eraseCookie("fstatus");
	$("#fstatus").val("Pending");
	ssseotable
		.column( $("#fstatus").parent().index()+':visible' )
		.search( "pending" )
		.draw();


	}

	function multifilter(nthis,fieldname,ssseotable)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			ssseotable
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#ss_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

<?php if(isset($_GET["short"]) and isset($_GET["pgno"]) and 1==2){ ?>
var newtable = $("#sstable #ss_datatable_fixed_column").DataTable();
//var newinfo = newtable.page.info();alert(JSON.stringify(newinfo));
//var newpage = newinfo.page;//alert(newpage);

newtable.page.len(6).draw('page');
//newtable.page(1).draw(true);
$("#sstable #ss_datatable_fixed_column").DataTable().page(2).draw('page');

<?php } ?>

	// load related plugins

/*loadScript("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js",function(){
	loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
		loadScript("https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js",function(){
		loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
			loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
			loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
			loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
			loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
				loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
				loadScript("https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
			});
			});
			});
			});
			});
		});
	});
});
});*/

/*
loadScript("assets/plugins/datatables1.11.3/datatables.custom.min.js", function(){
	loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
		loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
		loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
		loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
		loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
			loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
			loadScript("https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js", pagefunction)
		});
		});
		});
		});
		});
	});
});
*/

		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
			 //loadScript("assets/js/dataTables.editor.min.js", function(){
				pagefunction();
			 //});
		});

/*loadScript("assets/plugins/newdatatables/datatables.js", function(){
		pagefunction();
});	*/

<?php if($_SESSION["group_id"] == 5 || $_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 2 || $_SESSION["group_id"] == 1){ ?>
function sssload_details(sid) {
	//alert($("#ss_datatable_fixed_column").DataTable().page.info().page);
	////clearme(sid,6,false,$("#ss_datatable_fixed_column").DataTable().page.info().page);
	/*$.get('assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid='+sid, function (pagedata){
		parent.$('#ssopdialog').html('');
		$("#ssdetails").append(pagedata);
	});*/
	$("#ssdetails").load('assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid='+sid);
	//ssseotable.pageLength(6) ;
	//$("#ss_datatable_fixed_column").DataTable().page.len(6).draw();
}
function loadsssmenu(sid) {
	sssload_details(sid);
	$("#ss_datatable_fixed_column").DataTable().page.len(6).draw(false);
}
function clearme(ctid,pglength=12,loadit=false,pageno=0,close=0){
	if(close !=0){
		var tmparr=[];
		if($("#date_contacted").val() == ""){tmparr.push("Date Contacted");}
		if($("#contacted_method").val() == ""){tmparr.push("Contacted Method");}
		if($("#account_number").val() == ""){tmparr.push("Account Number");}
		if($("#deposit").val() == ""){tmparr.push("Deposit");}

		if(tmparr.length > 0){
			Swal.fire("Required Fields:",tmparr.join(", "), "warning");
			/*Swal.fire({
			  title: "Required Fields:",
			  text: tmparr.join(", "),
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ["Later", "Fill Now"],
			})
			.then((willDelete) => {
			  if (willDelete) {

			  }else{
				if(pageno != 0){
					pageno =  (((pageno*12)/6)+1);
				}

				$( "#"+ctid+"" ).remove();
				$("#ss_datatable_fixed_column").DataTable().page.len(pglength).draw(loadit);
			  }
			});*/
		}
		var tmparr=[];
		return false;
	}
	if(pageno != 0){
		pageno =  (((pageno*12)/6)+1);
	}

	$( "#"+ctid+"" ).remove();
	$("#ss_datatable_fixed_column").DataTable().page.len(pglength).draw(loadit);
	//$("#ss_datatable_fixed_column").DataTable().row( this ).remove().draw( false );
	//$("#ss_datatable_fixed_column").DataTable().page(3).draw();
}
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
function deletesss(sid){
}

<?php } ?>

<?php } ?>
</script>
<?php } ?>
