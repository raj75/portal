<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(checkpermission($mysqli,49)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!$_SESSION['user_id'])
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats"></i>
				Account Admin
			<span>>
				Start New Service
			</span>
		</h1>
	</div>
</div>

<?php
if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){
	if ($stmt = $mysqli->prepare('SELECT cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID,cd.`Billing City`,cd.`Billing State`,cd.`Billing Zip Code` FROM company_defaults cd, company c, user up WHERE cd.companyID=c.company_id and up.company_id=c.company_id and up.user_id='.$user_one)) {

	//('SELECT cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd, company c, user up WHERE cd.companyID=c.id and up.company_id=c.id and up.id='.$user_one)) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($cdEntityName,$cdTaxID,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdcompanyID,$cdBillingCity,$cdBillingState,$cdBillingZipcode);
			$stmt->fetch();

		}else die("<p style='text-align:center;'>No records found!</p>");
	}else die("<p style='text-align:center;'>Error Occured. Please try after sometimes!</p>");
}else if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$cdEntityName=$cdTaxID=$cdBillingAddress1=$cdBillingAddress2=$cdBillingAddress3=$cdcompanyID=$cdBillingCity=$cdBillingState=$cdBillingZipcode="";
}else die("<p style='text-align:center;'>Error Occured. Please try after sometimes!</p>");
?>
<style>
.sns{margin-top:3%;}
.sns section{font-size:14px !important;color:#000;}
.sns .fullwidth{width:100%;}
.sns .center{text-align:center;}
h3.htitle{text-align:center;text-decoration: underline;}
.sns footer{text-align:center;}
.sns footer button{float:none !important;}
.sns header{background: #00bfff !important;text-align: center !important;color: #fff !important;font-weight: bold !important;}
.sns .jarviswidget{width: 82%;margin: 0 auto;}
.sns #sns-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
.sns .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
    bottom: -1px !important;
    left: 29px !important;
}
#ustcontainer label,#vendorcontainer label,#acccontainer label,#metercontainer label{clear:both; }
</style>
<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">

<h3 class="htitle">TURN ON SERVICE REQUEST FORM</h3>
<section id="widget-grid" class="sns">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
					<div class="widget-body no-padding">
						<form id="start-new-service" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" ">
							<header>SITE INFORMATION</header>
							<fieldset>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){	?>
								<div class="row">
									<section class="col col-6">Company
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snscid" id="snscid" placeholder="Read" class="">
											<?php
													if ($stmtttt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')) {

														$stmtttt->execute();
														$stmtttt->store_result();
														if ($stmtttt->num_rows > 0) {
															$stmtttt->bind_result($company_id,$company_name);
															while($stmtttt->fetch()){
															?>
															<option value="<?php echo $company_id; ?>">&nbsp;&nbsp;<?php echo $company_name; ?></option>
															<?php
															}
														}
													}
											?>
											</select>
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>
<?php } ?>
								<div class="row">
									<section class="col col-6">Site Number
										<label class="input">
											<input type="text" name="snssitenumber" id="snssitenumber" placeholder="Site Number" value="">
										</label>
									</section>
									<section class="col col-6">LOCATION TYPE
										<label class="input">
											<input type="text" name="snsloctype" id="snsloctype" placeholder="LOCATION TYPE" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" name="snssitename" id="snssitename" placeholder="Site Name" value="">
										</label>
									</section>
									<section class="col col-6">REGION
										<label class="input">
											<input type="text" name="snsregion" id="snsregion" placeholder="REGION" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Entity Name
										<label class="input">
											<input type="text" name="snsentityname" id="snsentityname" placeholder="Site Name" value="<?php echo $cdEntityName; ?>">
										</label>
									</section>
									<section class="col col-6">DIVISION
										<label class="input">
											<input type="text" name="snsdivision" id="snsdivision" placeholder="DIVISION" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Federal Tax ID Number
										<label class="input">
											<input type="text" name="snsfederaltaxidno" id="snsfederaltaxidno" placeholder="Federal Tax ID Number" value="<?php echo $cdTaxID; ?>">
										</label>
									</section>
									<section class="col col-6">GL SITE #
										<label class="input">
											<input type="text" name="snsglsite" id="snsglsite" placeholder="GL SITE #" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Address 1
										<label class="input">
											<input type="text" name="snssiteaddr1" id="snssiteaddr1" placeholder="Site Address 1" value="">
										</label>
									</section>
									<section class="col col-6">Account Address 1
										<label class="input">
											<input type="text" name="snsaccaddr1" id="snsaccaddr1" placeholder="Account Address 1" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Address 2
										<label class="input">
											<input type="text" name="snssiteaddr2" id="snssiteaddr2" placeholder="Site Address 2" value="">
										</label>
									</section>
									<section class="col col-6">Account Address 2
										<label class="input">
											<input type="text" name="snsaccaddr2" id="snsaccaddr2" placeholder="Account Address 2" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site City
										<label class="input">
											<input type="text" name="snssitecity" id="snssitecity" placeholder="Site City" value="">
										</label>
									</section>
									<section class="col col-6">Account City
										<label class="input">
											<input type="text" name="snsacccity" id="snsacccity" placeholder="Account City" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site State
										<label class="input">
											<input type="text" name="snssitestate" id="snssitestate" placeholder="Site State" value="">
										</label>
									</section>
									<section class="col col-6">Account State
										<label class="input">
											<input type="text" name="snsaccstate" id="snsaccstate" placeholder="Account State" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Zip
										<label class="input">
											<input type="text" name="snssitezip" id="snssitezip" placeholder="Site Zip" value="">
										</label>
									</section>
									<section class="col col-6">Account Zip
										<label class="input">
											<input type="text" name="snssiteacczip" id="snssiteacczip" placeholder="Account Zip" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Name
										<label class="input">
											<input type="text" name="snssitecontname" id="snssitecontname" placeholder="Site Contact Name" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 1
										<label class="input">
											<input type="text" name="snsbilladdr1" id="snsbilladdr1" placeholder="Billing Address 1" value="<?php echo $cdBillingAddress1; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Title
										<label class="input">
											<input type="text" name="snssiteconttitle" id="snssiteconttitle" placeholder="Site Contact Title" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 2
										<label class="input">
											<input type="text" name="snsbilladdr2" id="snsbilladdr2" placeholder="Billing Address 2" value="<?php echo $cdBillingAddress2; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Telephone
										<label class="input">
											<input type="text" name="snssiteconttel" id="snssiteconttel" placeholder="Site Contact Telephone" value="">
										</label>
									</section>
									<section class="col col-6">Billing City
										<label class="input">
											<input type="text" name="snsbillcity" id="snsbillcity" placeholder="Billing City" value="<?php echo $cdBillingCity; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Fax
										<label class="input">
											<input type="text" name="snssitecontfax" id="snssitecontfax" placeholder="Site Contact Fax" value="">
										</label>
									</section>
									<section class="col col-6">Billing State
										<label class="input">
											<input type="text" name="snsbillstate" id="snsbillstate" placeholder="Billing State" value="<?php echo $cdBillingState; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
									</section>
									<section class="col col-6">Billing Zip
										<label class="input">
											<input type="text" name="snsbillzip" id="snsbillzip" placeholder="Billing Zip" value="<?php echo $cdBillingZipcode; ?>">
										</label>
									</section>
								</div>
							</fieldset>
							<header>LANDLORD INFORMATION</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Leased Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snsleasedloc" id="snsleasedloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Landlord Name
										<label class="input">
											<input type="text" name="snslandlordname" id="snslandlordname" placeholder="Landlord Name" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease Start Date
										<label class="input">
											<input type="text" name="snsleasestdate" id="snsleasestdate" placeholder="Lease Start Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact Number
										<label class="input">
											<input type="text" name="snscontno" id="snscontno" placeholder="Contact Number" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease End Date
										<label class="input">
											<input type="text" name="snsleaseenddt" id="snsleaseenddt" placeholder="Lease End Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact FAX
										<label class="input">
											<input type="text" name="snscontfax" id="snscontfax" placeholder="Contact FAX" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Previous Tenant
										<label class="input">
											<input type="text" name="snspretenant" id="snspretenant" placeholder="Previous Tenant" value="">
										</label>
									</section>
									<section class="col col-6">Contact Email
										<label class="input">
											<input type="text" name="snscontemail" id="snscontemail" placeholder="Contact Email" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Sublet
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snssublet" id="snssublet" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Address 1
										<label class="input">
											<input type="text" name="snsaddr1" id="snsaddr1" placeholder="Address 1" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">
									</section>
									<section class="col col-6">Address 2
										<label class="input">
											<input type="text" name="snsaddr2" id="snsaddr2" placeholder="Address 2" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Owned Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snsownloc" id="snsownloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">City
										<label class="input">
											<input type="text" name="snscity" id="snscity" placeholder="City" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Purchase Date
										<label class="input">
											<input type="text" name="snspurdt" id="snspurdt" placeholder="Purchase Date" value="">
										</label>
									</section>
									<section class="col col-6">State
										<label class="input">
											<input type="text" name="snsstate" id="snsstate" placeholder="State" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Previous Owner
										<label class="input">
											<input type="text" name="snsprevown" id="snsprevown" placeholder="Previous Owner" value="">
										</label>
									</section>
									<section class="col col-6">Zip
										<label class="input">
											<input type="text" name="snszip" id="snszip" placeholder="Zip" value="">
										</label>
									</section>
								</div>
							</fieldset>
							<header>SERVICES</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Turn On Date <i style="color:red;">*</i>
										<label class="input">
											<input type="text" name="snsturnondt" id="snsturnondt" placeholder="Turn On Date" value="">
										</label>
									</section>
									<section class="col col-6">Deposit Preference
										<label class="input">
											<input type="text" name="snsdeppre" id="snsdeppre" placeholder="Deposit Preference" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Construction
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snsconst" id="snsconst" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Check Deposit OK
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snscheckdpok" id="snscheckdpok" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">New Meters Required
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snsnwmetreq" id="snsnwmetreq" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Credit Card Deposit OK
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="snscrecarddpok" id="snscrecarddpok" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-3 center" id="ustcontainer">Utility Service Type <i style="color:red;">*</i>
										<label class="input">
											<input type="text" name="snsutsertp1" id="snsutsertp1" placeholder="" value="" class="snsutsertp">
										</label>

									</section>
									<section class="col col-3 center" id="vendorcontainer">Vendor Name <i style="color:red;">*</i>
										<label class="input">
											<input type="text" name="snsvenname1" id="snsvenname1" placeholder="" value="" class="snsvenname">
										</label>

									</section>
									<section class="col col-3 center" id="acccontainer">Account <i style="color:red;">*</i>
										<label class="input">
											<input type="text" name="snsacc1" id="snsacc1" placeholder="" value="" class="snsaccount">
										</label>

									</section>
									<section class="col col-3 center" id="metercontainer">Meter
										<label class="input">
											<input type="text" name="snsmet1" id="snsmet1" placeholder="" value="" class="snsmeter" style="float: left;width: 91%;"><a href="javascript:void(0)" style="float: right;font-weight: bold;font-size: 23px;color: red;"></a></label>
										</label>

									</section>
								</div>

								<div class="row text-center"><a href="javascript:void(0)" id="addmore" style="font-weight:bold;">+ Add More</a></div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">Special Instructions
										<label class="textarea"> <i class="icon-append fa fa-comment"></i> 									<textarea rows="4" id="snscomment" name="snscomment"></textarea> </label>
										<input type="hidden" name="sns" id="sns" placeholder="" value="sns">
<?php if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){ ?>
										<input type="hidden" name="snscid" id="snscid" placeholder="" value="<?php echo $cdcompanyID; ?>">
<?php } ?>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">
										<div class="dropzone dz-clickable" id="sns-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="sns-submit">
									Save
								</button>
							</footer>
						</form>

					</div>

			</div>
		</article>
	</div>
</section>
<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
<script type="text/JavaScript" src="../assets/js/forms.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
<script type="text/javascript">
function removeit(removeid){
	if(removeid == "") return false;
	$('#luts'+removeid).remove();
	$('#lven'+removeid).remove();
	$('#lacc'+removeid).remove();
	$('#lmeter'+removeid).remove();
}

var script = document.createElement("script");
script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
script.onload = loadedContent;
document.head.append(script);
function loadedContent(){
		var responsegot=0;
		var currentFile = null;
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("div#sns-fileupload", {
			paramName: "snsfilesupload",
			addRemoveLinks: true,
			url: "assets/includes/services.inc.php",
			maxFiles:10,
			uploadMultiple: true,
			parallelUploads:10,
			timeout: 300000,
			maxFilesize: 3000,
			autoProcessQueue: false,
			init: function() {
				myDropz = this;
				  /*$("#sns-submit").click(function (e) {
					e.preventDefault();
					e.stopPropagation();
					if (myDropz.files.length) {
					  myDropz.processQueue(); // upload files and submit the form
					} else {
					  $('#start-new-service').submit(); // submit the form
					}
				  });*/

					$("#sns-submit").on("click", function(e) {
                      // Make sure that the form isn't actually being sent.
                      e.preventDefault();
                      e.stopPropagation();


						if(Array.from($('.snsutsertp').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.snsvenname').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.snsaccount').get(), e => e.value).join('@@').replace(/@/g, '') !="" && $("#snsturnondt").val() != ""){
							if (myDropz.getQueuedFiles().length > 0)
							{
								myDropzone.on("sending", function(file, xhr, formData) {
									formData.append('sns', $("#sns").val());
									formData.append('site_number', $("#snssitenumber").val());
									formData.append('company_id', $("#snscid").val());
									formData.append('location_type', $("#snsloctype").val());
									formData.append('site_name', $("#snssitename").val());
									formData.append('region', $("#snsregion").val());
									formData.append('entity_name', $("#snsentityname").val());
									formData.append('division', $("#snsdivision").val());
									formData.append('federal_tax_id', $("#snsfederaltaxidno").val());
									formData.append('gl_site', $("#snsglsite").val());
									formData.append('site_address1', $("#snssiteaddr1").val());
									formData.append('account_address1', $("#snsaccaddr1").val());
									formData.append('site_address2', $("#snssiteaddr2").val());
									formData.append('account_address2', $("#snsaccaddr2").val());
									formData.append('site_city', $("#snssitecity").val());
									formData.append('account_city', $("#snsacccity").val());
									formData.append('site_state', $("#snssitestate").val());
									formData.append('account_state', $("#snsaccstate").val());
									formData.append('site_zip', $("#snssitezip").val());
									formData.append('account_zip', $("#snssiteacczip").val());
									formData.append('site_contact_name', $("#snssitecontname").val());
									formData.append('billing_address1', $("#snsbilladdr1").val());
									formData.append('site_contact_title', $("#snssiteconttitle").val());
									formData.append('billing_address2', $("#snsbilladdr2").val());
									formData.append('site_contact_telephone', $("#snssiteconttel").val());
									formData.append('billing_city', $("#snsbillcity").val());
									formData.append('site_contact_fax', $("#snssitecontfax").val());
									formData.append('billing_state', $("#snsbillstate").val());
									formData.append('billing_zip', $("#snsbillzip").val());
									formData.append('leased_location', $("#snsleasedloc").val());
									formData.append('landlord_name', $("#snslandlordname").val());
									formData.append('lease_start_date', $("#snsleasestdate").val());
									formData.append('landlord_phone', $("#snscontno").val());
									formData.append('lease_end_date', $("#snsleaseenddt").val());
									formData.append('landlord_fax', $("#snscontfax").val());
									formData.append('tenant', $("#snspretenant").val());
									formData.append('landlord_email', $("#snscontemail").val());
									formData.append('sublet', $("#snssublet").val());
									formData.append('landlord_address1', $("#snsaddr1").val());
									formData.append('landlord_address2', $("#snsaddr2").val());
									formData.append('owned_location', $("#snsownloc").val());
									formData.append('landlord_city', $("#snscity").val());
									formData.append('sale_date', $("#snspurdt").val());
									formData.append('landlord_state', $("#snsstate").val());
									formData.append('sale_owner', $("#snsprevown").val());
									formData.append('landlord_zip', $("#snszip").val());
									formData.append('date_requested', $("#snsturnondt").val());
									formData.append('deposit_preference', $("#snsdeppre").val());
									formData.append('construction', $("#snsconst").val());
									formData.append('check_deposit_ok', $("#snscheckdpok").val());
									formData.append('meter_change', $("#snsnwmetreq").val());
									formData.append('credit_card_deposit_ok', $("#snscrecarddpok").val());
									/*formData.append('utility_service_type1', $("#snsutsertp1").val());
									formData.append('utility_service_type2', $("#snsutsertp2").val());
									formData.append('utility_service_type3', $("#snsutsertp3").val());
									formData.append('utility_service_type4', $("#snsutsertp4").val());
									formData.append('utility_service_type5', $("#snsutsertp5").val());
									formData.append('utility_service_type6', $("#snsutsertp6").val());
									formData.append('utility_service_type7', $("#snsutsertp7").val());
									formData.append('vendor_name1', $("#snsvenname1").val());
									formData.append('vendor_name2', $("#snsvenname2").val());
									formData.append('vendor_name3', $("#snsvenname3").val());
									formData.append('vendor_name4', $("#snsvenname4").val());
									formData.append('vendor_name5', $("#snsvenname5").val());
									formData.append('vendor_name6', $("#snsvenname6").val());
									formData.append('vendor_name7', $("#snsvenname7").val());
									formData.append('account1', $("#snsacc1").val());
									formData.append('account2', $("#snsacc2").val());
									formData.append('account3', $("#snsacc3").val());
									formData.append('account4', $("#snsacc4").val());
									formData.append('account5', $("#snsacc5").val());
									formData.append('account6', $("#snsacc6").val());
									formData.append('account7', $("#snsacc7").val());
									formData.append('meter1', $("#snsmet1").val());
									formData.append('meter2', $("#snsmet2").val());
									formData.append('meter3', $("#snsmet3").val());
									formData.append('meter4', $("#snsmet4").val());
									formData.append('meter5', $("#snsmet5").val());
									formData.append('meter6', $("#snsmet6").val());
									formData.append('meter7', $("#snsmet7").val());*/
									formData.append('utility_service_type', Array.from($('.snsutsertp').get(), e => e.value).join('@@'));
									formData.append('vendor_name', Array.from($('.snsvenname').get(), e => e.value).join('@@'));
									formData.append('account', Array.from($('.snsaccount').get(), e => e.value).join('@@'));
									if(Array.from($('.snsmeter').get(), e => e.value).join('@@').replace(/@/g, '') !=""){
										formData.append('meter', Array.from($('.snsmeter').get(), e => e.value).join('@@'));
									}

									formData.append('special_instructions', $("#snscomment").val());




								});
								myDropz.processQueue();

								myDropz.on("successmultiple", function(file, result) {
									if (result != false)
									{
										var results = JSON.parse(result);
										if(results.error == "")
										{
											Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
											$("#start-new-service").get(0).reset();
										}else if(results.error == 5)
										{
											Swal.fire("At least one entry mandatory in:","Utility Service Type, Vendor Name, Account and Turn On Date", "warning");
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
								$('#start-new-service').trigger("reset")
							} else {
									$('#start-new-service').submit();
							}
						}else{
							Swal.fire("At least one entry mandatory in:","Utility Service Type, Vendor Name, Account and Turn On Date", "warning");
						}
                    });

			}
		});
}

$(function() {
	$(document).ready(function() {
                var responsegot=0;
		var currentFile = null;
		$("#addmore").on('click',function(){
			var milliseconds = new Date().getTime();
			$("#ustcontainer").append('<label class="input" id="luts'+milliseconds+'"><input type="text" name="snsutsertp'+milliseconds+'" id="snsutsertp'+milliseconds+'" placeholder="" value="" class="snsutsertp"></label>');
			$("#vendorcontainer").append('<label class="input" id="lven'+milliseconds+'"><input type="text" name="snsvenname'+milliseconds+'" id="snsvenname'+milliseconds+'" placeholder="" value="" class="snsvenname"></label>');
			$("#acccontainer").append('<label class="input" id="lacc'+milliseconds+'"><input type="text" name="snsacc'+milliseconds+'" id="snsacc'+milliseconds+'" placeholder="" value="" class="snsaccount"></label>');
			$("#metercontainer").append('<label class="input" id="lmeter'+milliseconds+'"><input type="text" name="snsmet'+milliseconds+'" id="snsmet'+milliseconds+'" placeholder="" value="" class="snsmeter" style="float: left;width: 91%;"><a href="javascript:void(0)" onclick="removeit('+milliseconds+')" style="float: right;font-weight: bold;font-size: 23px;color: red;">-</a></label>');
		});
	});
});
	/*$(function() {
	$(document).ready(function() {
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
	});
	});*/
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



		var $checkoutForm = $('#start-new-service').validate({
		// Rules for form validation
			/*rules : {
				addfname : {
					required : true
				},
				addlname : {
					required : true
				},
				addemail : {
					required : true,
					email : true
				},
				addphone : {
					required : true
				},
				addgender : {
					required : true
				},
				addcompany : {
					required : true
				},
				addpassword : {
					required : false,
					minlength : 3,
					maxlength : 20
				},
				addpasswordConfirm : {
					required : false,
					minlength : 3,
					maxlength : 20,
					equalTo : '#addpassword'
				},
				addusergroups : {
					required : true,
					digits : true
				},
				addusername : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				addfname : {
					required : 'Please enter your first name'
				},
				addlname : {
					required : 'Please enter your last name'
				},
				addemail : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				addphone : {
					required : 'Please enter your phone number'
				},
				addgender : {
					required : 'Please enter your gender'
				},
				addcompany : {
					required : 'Select company'
				},
				addpassword : {
					required : 'Please enter your password'
				},
				addpasswordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as confirm password'
				},
				addusername : {
					required : 'Please enter your username'
				},
				addusergroups : {
					required : 'Please enter your usergroup'
				}
			},*/
			// Ajax form submition
			submitHandler : function(form) {

				if(Array.from($('.snsutsertp').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.snsvenname').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.snsaccount').get(), e => e.value).join('@@').replace(/@/g, '') !="" && $("#snsturnondt").val() != ""){


					var formData = new FormData();
					formData.append('sns', $("#sns").val());
					formData.append('site_number', $("#snssitenumber").val());
					formData.append('company_id', $("#snscid").val());
					formData.append('location_type', $("#snsloctype").val());
					formData.append('site_name', $("#snssitename").val());
					formData.append('region', $("#snsregion").val());
					formData.append('entity_name', $("#snsentityname").val());
					formData.append('division', $("#snsdivision").val());
					formData.append('federal_tax_id', $("#snsfederaltaxidno").val());
					formData.append('gl_site', $("#snsglsite").val());
					formData.append('site_address1', $("#snssiteaddr1").val());
					formData.append('account_address1', $("#snsaccaddr1").val());
					formData.append('site_address2', $("#snssiteaddr2").val());
					formData.append('account_address2', $("#snsaccaddr2").val());
					formData.append('site_city', $("#snssitecity").val());
					formData.append('account_city', $("#snsacccity").val());
					formData.append('site_state', $("#snssitestate").val());
					formData.append('account_state', $("#snsaccstate").val());
					formData.append('site_zip', $("#snssitezip").val());
					formData.append('account_zip', $("#snssiteacczip").val());
					formData.append('site_contact_name', $("#snssitecontname").val());
					formData.append('billing_address1', $("#snsbilladdr1").val());
					formData.append('site_contact_title', $("#snssiteconttitle").val());
					formData.append('billing_address2', $("#snsbilladdr2").val());
					formData.append('site_contact_telephone', $("#snssiteconttel").val());
					formData.append('billing_city', $("#snsbillcity").val());
					formData.append('site_contact_fax', $("#snssitecontfax").val());
					formData.append('billing_state', $("#snsbillstate").val());
					formData.append('billing_zip', $("#snsbillzip").val());
					formData.append('leased_location', $("#snsleasedloc").val());
					formData.append('landlord_name', $("#snslandlordname").val());
					formData.append('lease_start_date', $("#snsleasestdate").val());
					formData.append('landlord_phone', $("#snscontno").val());
					formData.append('lease_end_date', $("#snsleaseenddt").val());
					formData.append('landlord_fax', $("#snscontfax").val());
					formData.append('tenant', $("#snspretenant").val());
					formData.append('landlord_email', $("#snscontemail").val());
					formData.append('sublet', $("#snssublet").val());
					formData.append('landlord_address1', $("#snsaddr1").val());
					formData.append('landlord_address2', $("#snsaddr2").val());
					formData.append('owned_location', $("#snsownloc").val());
					formData.append('landlord_city', $("#snscity").val());
					formData.append('sale_date', $("#snspurdt").val());
					formData.append('landlord_state', $("#snsstate").val());
					formData.append('sale_owner', $("#snsprevown").val());
					formData.append('landlord_zip', $("#snszip").val());
					formData.append('date_requested', $("#snsturnondt").val());
					formData.append('deposit_preference', $("#snsdeppre").val());
					formData.append('construction', $("#snsconst").val());
					formData.append('check_deposit_ok', $("#snscheckdpok").val());
					formData.append('meter_change', $("#snsnwmetreq").val());
					formData.append('credit_card_deposit_ok', $("#snscrecarddpok").val());
					/*formData.append('utility_service_type1', $("#snsutsertp1").val());
					formData.append('utility_service_type2', $("#snsutsertp2").val());
					formData.append('utility_service_type3', $("#snsutsertp3").val());
					formData.append('utility_service_type4', $("#snsutsertp4").val());
					formData.append('utility_service_type5', $("#snsutsertp5").val());
					formData.append('utility_service_type6', $("#snsutsertp6").val());
					formData.append('utility_service_type7', $("#snsutsertp7").val());
					formData.append('vendor_name1', $("#snsvenname1").val());
					formData.append('vendor_name2', $("#snsvenname2").val());
					formData.append('vendor_name3', $("#snsvenname3").val());
					formData.append('vendor_name4', $("#snsvenname4").val());
					formData.append('vendor_name5', $("#snsvenname5").val());
					formData.append('vendor_name6', $("#snsvenname6").val());
					formData.append('vendor_name7', $("#snsvenname7").val());
					formData.append('account1', $("#snsacc1").val());
					formData.append('account2', $("#snsacc2").val());
					formData.append('account3', $("#snsacc3").val());
					formData.append('account4', $("#snsacc4").val());
					formData.append('account5', $("#snsacc5").val());
					formData.append('account6', $("#snsacc6").val());
					formData.append('account7', $("#snsacc7").val());
					formData.append('meter1', $("#snsmet1").val());
					formData.append('meter2', $("#snsmet2").val());
					formData.append('meter3', $("#snsmet3").val());
					formData.append('meter4', $("#snsmet4").val());
					formData.append('meter5', $("#snsmet5").val());
					formData.append('meter6', $("#snsmet6").val());
					formData.append('meter7', $("#snsmet7").val());*/
					formData.append('utility_service_type', Array.from($('.snsutsertp').get(), e => e.value).join('@@'));
					formData.append('vendor_name', Array.from($('.snsvenname').get(), e => e.value).join('@@'));
					formData.append('account', Array.from($('.snsaccount').get(), e => e.value).join('@@'));
					if(Array.from($('.snsmeter').get(), e => e.value).join('@@').replace(/@/g, '') !=""){
						formData.append('meter', Array.from($('.snsmeter').get(), e => e.value).join('@@'));
					}

					formData.append('special_instructions', $("#snscomment").val());



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
									Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
									$("#start-new-service").get(0).reset();
								}else if(results.error == 5)
								{
									Swal.fire("At least one entry mandatory in:","Utility Service Type, Vendor Name, Account and Turn On Date", "warning");
								}else{
									Swal.fire("Error in request.","Please try again later.", "warning");
								}
							}else{
								Swal.fire("","Error in request. Please try again later.", "warning");
							}
						}
					  });
					return false;
				}else{
					Swal.fire("At least one entry mandatory in:","Utility Service Type, Vendor Name, Account and Turn On Date", "warning");
				}
			},
			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
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



$(document).on('change', '#snscid', function() {
	var formData = new FormData();
	formData.append('cid', $("#snscid").val());
	$.ajax({
		type: 'post',
		url: 'assets/includes/services_loaddefault.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
					//$("#start-new-service").get(0).reset();

					$("#snsentityname").val(results.EntityName);
					$("#snsfederaltaxidno").val(results.TaxID);
					$("#snsbilladdr1").val(results.BillingAddress1);
					$("#snsbilladdr2").val(results.BillingAddress2);
					//$("#").val(results.BillingAddress3);
					//$("#snscid").val(results.companyID);
					$("#snsbillcity").val(results.BillingCity);
					$("#snsbillstate").val(results.BillingState);
					$("#snsbillzip").val(results.BillingZipcode);
					//$("#").val(results.EntityName);
					//$("#").val(results.EntityName);
					//$("#").val(results.EntityName);
					//$("#").val(results.EntityName);
				}else if(results.error == 5)
				{
					Swal.fire("Error in request.","Please try again later.", "warning");
				}else{
					Swal.fire("Error in request.","Please try again later.", "warning");
				}
			}else{
				Swal.fire("","Error in request. Please try again later.", "warning");
			}
		}
	});
});

$( document ).ready(function() {
  $("#snscid option:first").attr('selected','selected').change();;
});


</script>
<?php
/*}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();
}//else
	//die("Error Occured! Please try after sometime.");
*/
?>
