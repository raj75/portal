<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(checkpermission($mysqli,50)==false) die("Permission Denied! Please contact Vervantis.");
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
				Stop Service
			</span>
		</h1>
	</div>
</div>

<?php
if ($stmt = $mysqli->prepare('SELECT cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd, company c, user up WHERE cd.companyID=c.company_id and up.company_id=c.company_id and up.user_id='.$user_one)) { 

//('SELECT cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd, company c, user up WHERE cd.companyID=c.id and up.company_id=c.id and up.id='.$user_one)) { 

	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($cdEntityName,$cdTaxID,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdcompanyID);
		$stmt->fetch();
?>
<style>
.ss{margin-top:3%;}
.ss section{font-size:14px !important;color:#000;}
.ss .fullwidth{width:100%;}
.ss .center{text-align:center;}
h3.htitle{text-align:center;text-decoration: underline;}
.ss footer{text-align:center;}
.ss footer button{float:none !important;}
.ss header{background: #00bfff !important;text-align: center !important;color: #fff !important;font-weight: bold !important;}
.ss .jarviswidget{width: 82%;margin: 0 auto;}
.ss #ss-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
.ss .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
    bottom: -1px !important;
    left: 29px !important;
} 
</style>
<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
<h3 class="htitle">TURN OFF SERVICE REQUEST FORM</h3>
<section id="widget-grid" class="ss">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
					<div class="widget-body no-padding">
						<form id="stop-service-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data">
							<header>SITE INFORMATION</header>
							<fieldset>				
								<div class="row">
									<section class="col col-6">Site Number
										<label class="input">
											<input type="text" name="sssiteno" id="sssiteno" placeholder="Site Number" value="">
										</label>
									</section>
									<section class="col col-6">LOCATION TYPE
										<label class="input">
											<input type="text" name="ssloctype" id="ssloctype" placeholder="LOCATION TYPE" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" name="sssitename" id="sssitename" placeholder="Site Name" value="">
										</label>
									</section>
									<section class="col col-6">REGION
										<label class="input">
											<input type="text" name="ssregion" id="ssregion" placeholder="REGION" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Entity Name
										<label class="input">
											<input type="text" name="ssentityname" id="ssentityname" placeholder="Site Name" value="<?php echo $cdEntityName; ?>">
										</label>
									</section>
									<section class="col col-6">DIVISION
										<label class="input">
											<input type="text" name="ssdivision" id="ssdivision" placeholder="DIVISION" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Federal Tax ID Number
										<label class="input">
											<input type="text" name="ssfedtaxidno" id="ssfedtaxidno" placeholder="Federal Tax ID Number" value="<?php echo $cdTaxID; ?>">
										</label>
									</section>
									<section class="col col-6">GL SITE #
										<label class="input">
											<input type="text" name="ssglsite" id="ssglsite" placeholder="GL SITE #" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Address 1
										<label class="input">
											<input type="text" name="sssiteaddr1" id="sssiteaddr1" placeholder="Site Address 1" value="">
										</label>
									</section>
									<section class="col col-6">Account Address 1
										<label class="input">
											<input type="text" name="ssaccaddr1" id="ssaccaddr1" placeholder="Account Address 1" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Address 2
										<label class="input">
											<input type="text" name="sssiteaddr2" id="sssiteaddr2" placeholder="Site Address 2" value="">
										</label>
									</section>
									<section class="col col-6">Account Address 2
										<label class="input">
											<input type="text" name="ssaccaddr2" id="ssaccaddr2" placeholder="Account Address 2" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site City
										<label class="input">
											<input type="text" name="sssitecity" id="sssitecity" placeholder="Site City" value="">
										</label>
									</section>
									<section class="col col-6">Account City
										<label class="input">
											<input type="text" name="ssacccity" id="ssacccity" placeholder="Account City" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site State
										<label class="input">
											<input type="text" name="sssitestate" id="sssitestate" placeholder="Site State" value="">
										</label>
									</section>
									<section class="col col-6">Account State
										<label class="input">
											<input type="text" name="ssaccstate" id="ssaccstate" placeholder="Account State" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Zip
										<label class="input">
											<input type="text" name="sssitezip" id="sssitezip" placeholder="Site Zip" value="">
										</label>
									</section>
									<section class="col col-6">Account Zip
										<label class="input">
											<input type="text" name="ssacczip" id="ssacczip" placeholder="Account Zip" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Contact Name
										<label class="input">
											<input type="text" name="sssitecontname" id="sssitecontname" placeholder="Site Contact Name" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 1
										<label class="input">
											<input type="text" name="ssbilladdr1" id="ssbilladdr1" placeholder="Billing Address 1" value="<?php echo $cdBillingAddress1; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Contact Title
										<label class="input">
											<input type="text" name="sssiteconttitle" id="sssiteconttitle" placeholder="Site Contact Title" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 2
										<label class="input">
											<input type="text" name="ssbilladdr2" id="ssbilladdr2" placeholder="Billing Address 2" value="<?php echo $cdBillingAddress2; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Contact Telephone
										<label class="input">
											<input type="text" name="sssiteconttel" id="sssiteconttel" placeholder="Site Contact Telephone" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 3
										<label class="input">
											<input type="text" name="ssbilladdr3" id="ssbilladdr3" placeholder="Billing Address 3" value="<?php echo $cdBillingAddress3; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Site Contact Fax
										<label class="input">
											<input type="text" name="sssitecontfax" id="sssitecontfax" placeholder="Site Contact Fax" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 4
										<label class="input">
											<input type="text" name="ssbilladdr4" id="ssbilladdr4" placeholder="Billing Address 4" value="">
										</label>
									</section>
								</div>
							</fieldset>
							<header>LANDLORD INFORMATION</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Leased Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="ssleaseloc" id="ssleaseloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Landlord Name
										<label class="input">
											<input type="text" name="sslandlordname" id="sslandlordname" placeholder="Landlord Name" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease Start Date
										<label class="input">
											<input type="text" name="ssleasestdate" id="ssleasestdate" placeholder="Lease Start Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact Number
										<label class="input">
											<input type="text" name="sscontno" id="sscontno" placeholder="Contact Number" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease End Date
										<label class="input">
											<input type="text" name="ssleaseenddate" id="ssleaseenddate" placeholder="Lease End Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact FAX
										<label class="input">
											<input type="text" name="sscontfax" id="sscontfax" placeholder="Contact FAX" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">New Tenant
										<label class="input">
											<input type="text" name="ssnewten" id="ssnewten" placeholder="New Tenant" value="">
										</label>
									</section>
									<section class="col col-6">Contact Email
										<label class="input">
											<input type="text" name="sscontemail" id="sscontemail" placeholder="Contact Email" value="">
										</label>
									</section>
								</div>	
								<div class="row">
									<section class="col col-6">Sublet
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="sssublet" id="sssublet" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Address 1
										<label class="input">
											<input type="text" name="ssaddr1" id="ssaddr1" placeholder="Address 1" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">
									</section>
									<section class="col col-6">Address 2
										<label class="input">
											<input type="text" name="ssaddr2" id="ssaddr2" placeholder="Address 2" value="">
										</label>
									</section>
								</div>	
								<div class="row">
									<section class="col col-6">Owned Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="ssownloc" id="ssownloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">City
										<label class="input">
											<input type="text" name="sscity" id="sscity" placeholder="City" value="">
										</label>
									</section>
								</div>	
								<div class="row">
									<section class="col col-6">Sale Date
										<label class="input">
											<input type="text" name="sssaledate" id="sssaledate" placeholder="Sale Date" value="">
										</label>
									</section>
									<section class="col col-6">State
										<label class="input">
											<input type="text" name="ssstate" id="ssstate" placeholder="State" value="">
										</label>
									</section>
								</div>	
								<div class="row">
									<section class="col col-6">New Owner
										<label class="input">
											<input type="text" name="ssnewown" id="ssnewown" placeholder="New Owner" value="">
										</label>
									</section>
									<section class="col col-6">Zip
										<label class="input">
											<input type="text" name="sszip" id="sszip" placeholder="Zip" value="">
										</label>
									</section>
								</div>									
							</fieldset>
							<header>SERVICES</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Turn Off Date
										<label class="input">
											<input type="text" name="ssturnoffdate" id="ssturnoffdate" placeholder="Turn On Date" value="">
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>	
								<div class="row">
									<section class="col col-6">Require Meter Removal
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="ssreqmetrem" id="ssreqmetrem" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">
										<label class="select">
										</label>
									</section>
								</div>	
								<div class="row">
									<section class="col col-3 center">Utility Service Type
										<label class="input">
											<input type="text" name="ssutserty1" id="ssutserty1" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty2" id="ssutserty2" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty3" id="ssutserty3" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty4" id="ssutserty4" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty5" id="ssutserty5" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty6" id="ssutserty6" placeholder="" value="" class="ssutsertp">
										</label>
										<label class="input">
											<input type="text" name="ssutserty7" id="ssutserty7" placeholder="" value="" class="ssutsertp">
										</label>
									</section>
									<section class="col col-3 center">Vendor Name
										<label class="input">
											<input type="text" name="ssvenname1" id="ssvenname1" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname2" id="ssvenname2" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname3" id="ssvenname3" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname4" id="ssvenname4" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname5" id="ssvenname5" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname6" id="ssvenname6" placeholder="" value="" class="ssvenname">
										</label>
										<label class="input">
											<input type="text" name="ssvenname7" id="ssvenname7" placeholder="" value="" class="ssvenname">
										</label>
									</section>
									<section class="col col-3 center">Account
										<label class="input">
											<input type="text" name="ssacc1" id="ssacc1" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc2" id="ssacc2" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc3" id="ssacc3" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc4" id="ssacc4" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc5" id="ssacc5" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc6" id="ssacc6" placeholder="" value="" class="ssaccount">
										</label>
										<label class="input">
											<input type="text" name="ssacc7" id="ssacc7" placeholder="" value="" class="ssaccount">
										</label>
									</section>
									<section class="col col-3 center">Meter
										<label class="input">
											<input type="text" name="ssmeter1" id="ssmeter1" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter2" id="ssmeter2" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter3" id="ssmeter3" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter4" id="ssmeter4" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter5" id="ssmeter5" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter6" id="ssmeter6" placeholder="" value="" class="ssmeter">
										</label>
										<label class="input">
											<input type="text" name="ssmeter7" id="ssmeter7" placeholder="" value="" class="ssmeter">
										</label>
									</section>
								</div>									
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">Special Instructions
										<label class="textarea"> <i class="icon-append fa fa-comment"></i> 									<textarea rows="4" id="sssplinst" name="sssplinst"></textarea> </label>
										<input type="hidden" name="ss" id="ss" placeholder="" value="ss">
										<input type="hidden" name="sscid" id="sscid" placeholder="" value="<?php echo $cdcompanyID; ?>">
									</section>
								</div>							
							</fieldset>
							
							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">
										<div class="dropzone dz-clickable" id="ss-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>							
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="stop-service-submit">
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
<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
<script type="text/javascript">
$(function() {
	$(document).ready(function() {
		var responsegot=0;
		var currentFile = null;
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("div#ss-fileupload", {
			paramName: "ssfilesupload",
			addRemoveLinks: true,
			url: "assets/includes/services.inc.php",
			addRemoveLinks: true,
			maxFiles:10,
			uploadMultiple: true,
			parallelUploads:10,
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
				  
					$("#stop-service-submit").on("click", function(e) {
                      // Make sure that the form isn't actually being sent.
                      e.preventDefault();
                      e.stopPropagation();


					  
						if(Array.from($('.ssutsertp').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssvenname').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssaccount').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssmeter').get(), e => e.value).join('@@').replace(/@/g, '') !=""){
							if (myDropz.getQueuedFiles().length > 0)
							{  
								myDropzone.on("sending", function(file, xhr, formData) {
								formData.append('ss', $("#ss").val());
								formData.append('site_number', $("#sssiteno").val());
								formData.append('company_id', $("#sscid").val());
								formData.append('location_type', $("#ssloctype").val());
								formData.append('site_name', $("#sssitename").val());
								formData.append('region', $("#ssregion").val());
								formData.append('entity_name', $("#ssentityname").val());
								formData.append('division', $("#ssdivision").val());
								formData.append('federal_tax_id', $("#ssfedtaxidno").val());
								formData.append('gl_site', $("#ssglsite").val());
								formData.append('site_address1', $("#sssiteaddr1").val());
								formData.append('account_address1', $("#ssaccaddr1").val());
								formData.append('site_address2', $("#sssiteaddr2").val());
								formData.append('account_address2', $("#ssaccaddr2").val());
								formData.append('site_city', $("#sssitecity").val());
								formData.append('account_city', $("#ssacccity").val());
								formData.append('site_state', $("#sssitestate").val());
								formData.append('account_state', $("#ssaccstate").val());
								formData.append('site_zip', $("#sssitezip").val());
								formData.append('account_zip', $("#ssacczip").val());
								formData.append('site_contact_name', $("#sssitecontname").val());
								formData.append('billing_address1', $("#ssbilladdr1").val());
								formData.append('site_contact_title', $("#sssiteconttitle").val());
								formData.append('billing_address2', $("#ssbilladdr2").val());
								formData.append('site_contact_telephone', $("#sssiteconttel").val());
								formData.append('billing_address3', $("#ssbilladdr3").val());
								formData.append('site_contact_fax', $("#sssitecontfax").val());
								formData.append('billing_address4', $("#ssbilladdr4").val());
								formData.append('leased_location', $("#ssleaseloc").val());
								formData.append('landlord_name', $("#sslandlordname").val());
								formData.append('lease_start_date', $("#ssleasestdate").val());
								formData.append('landlord_phone', $("#sscontno").val());
								formData.append('lease_end_date', $("#ssleaseenddate").val());
								formData.append('landlord_fax', $("#sscontfax").val());
								formData.append('tenant', $("#ssnewten").val());
								formData.append('landlord_email', $("#sscontemail").val());
								formData.append('sublet', $("#sssublet").val());
								formData.append('landlord_address1', $("#ssaddr1").val());
								formData.append('landlord_address2', $("#ssaddr2").val());
								formData.append('owned_location', $("#ssownloc").val());
								formData.append('landlord_city', $("#sscity").val());
								formData.append('sale_date', $("#sssaledate").val());
								formData.append('landlord_state', $("#ssstate").val());
								formData.append('sale_owner', $("#ssnewown").val());
								formData.append('landlord_zip', $("#sszip").val());
								formData.append('date_requested', $("#ssturnoffdate").val());
								formData.append('meter_change', $("#ssreqmetrem").val());

								formData.append('utility_service_type', Array.from($('.ssutsertp').get(), e => e.value).join('@@'));
								formData.append('vendor_name', Array.from($('.ssvenname').get(), e => e.value).join('@@'));
								formData.append('account', Array.from($('.ssaccount').get(), e => e.value).join('@@'));
								formData.append('meter', Array.from($('.ssmeter').get(), e => e.value).join('@@'));	

								formData.append('special_instructions', $("#sssplinst").val());




								});					
								myDropz.processQueue(); 

								myDropz.on("successmultiple", function(file, result) {
									if (result != false)
									{
										var results = JSON.parse(result);
										if(results.error == "")
										{
											swal("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
											$("#stop-service-form").get(0).reset();							
										}else if(results.error == 5)
										{
											swal("At least one entry mandatory in:","Utility Service Type, Vendor Name,Account and Meter", "warning");						
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
								$('#stop-service-form').trigger("reset")
							} else {                 
									$('#stop-service-form').submit();
							} 
						}else{
							swal("At least one entry mandatory in:","Utility Service Type, Vendor Name,Account and Meter", "warning");
						}
                    });
			
			}
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


		
		
		var $checkoutForm = $('#stop-service-form').validate({
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
				if(Array.from($('.ssutsertp').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssvenname').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssaccount').get(), e => e.value).join('@@').replace(/@/g, '') !="" && Array.from($('.ssmeter').get(), e => e.value).join('@@').replace(/@/g, '') !=""){

					var formData = new FormData();
					formData.append('ss', $("#ss").val());
					formData.append('site_number', $("#sssiteno").val());
					formData.append('company_id', $("#sscid").val());
					formData.append('location_type', $("#ssloctype").val());
					formData.append('site_name', $("#sssitename").val());
					formData.append('region', $("#ssregion").val());
					formData.append('entity_name', $("#ssentityname").val());
					formData.append('division', $("#ssdivision").val());
					formData.append('federal_tax_id', $("#ssfedtaxidno").val());
					formData.append('gl_site', $("#ssglsite").val());
					formData.append('site_address1', $("#sssiteaddr1").val());
					formData.append('account_address1', $("#ssaccaddr1").val());
					formData.append('site_address2', $("#sssiteaddr2").val());
					formData.append('account_address2', $("#ssaccaddr2").val());
					formData.append('site_city', $("#sssitecity").val());
					formData.append('account_city', $("#ssacccity").val());
					formData.append('site_state', $("#sssitestate").val());
					formData.append('account_state', $("#ssaccstate").val());
					formData.append('site_zip', $("#sssitezip").val());
					formData.append('account_zip', $("#ssacczip").val());
					formData.append('site_contact_name', $("#sssitecontname").val());
					formData.append('billing_address1', $("#ssbilladdr1").val());
					formData.append('site_contact_title', $("#sssiteconttitle").val());
					formData.append('billing_address2', $("#ssbilladdr2").val());
					formData.append('site_contact_telephone', $("#sssiteconttel").val());
					formData.append('billing_address3', $("#ssbilladdr3").val());
					formData.append('site_contact_fax', $("#sssitecontfax").val());
					formData.append('billing_address4', $("#ssbilladdr4").val());
					formData.append('leased_location', $("#ssleaseloc").val());
					formData.append('landlord_name', $("#sslandlordname").val());
					formData.append('lease_start_date', $("#ssleasestdate").val());
					formData.append('landlord_phone', $("#sscontno").val());
					formData.append('lease_end_date', $("#ssleaseenddate").val());
					formData.append('landlord_fax', $("#sscontfax").val());
					formData.append('tenant', $("#ssnewten").val());
					formData.append('landlord_email', $("#sscontemail").val());
					formData.append('sublet', $("#sssublet").val());
					formData.append('landlord_address1', $("#ssaddr1").val());
					formData.append('landlord_address2', $("#ssaddr2").val());
					formData.append('owned_location', $("#ssownloc").val());
					formData.append('landlord_city', $("#sscity").val());
					formData.append('sale_date', $("#sssaledate").val());
					formData.append('landlord_state', $("#ssstate").val());
					formData.append('sale_owner', $("#ssnewown").val());
					formData.append('landlord_zip', $("#sszip").val());
					formData.append('date_requested', $("#ssturnoffdate").val());
					formData.append('meter_change', $("#ssreqmetrem").val());
					/*formData.append('utility_service_type1', $("#ssutserty1").val());
					formData.append('utility_service_type2', $("#ssutserty2").val());
					formData.append('utility_service_type3', $("#ssutserty3").val());
					formData.append('utility_service_type4', $("#ssutserty4").val());
					formData.append('utility_service_type5', $("#ssutserty5").val());
					formData.append('utility_service_type6', $("#ssutserty6").val());
					formData.append('utility_service_type7', $("#ssutserty7").val());
					formData.append('vendor_name1', $("#ssvenname1").val());
					formData.append('vendor_name2', $("#ssvenname2").val());
					formData.append('vendor_name3', $("#ssvenname3").val());
					formData.append('vendor_name4', $("#ssvenname4").val());
					formData.append('vendor_name5', $("#ssvenname5").val());
					formData.append('vendor_name6', $("#ssvenname6").val());
					formData.append('vendor_name7', $("#ssvenname7").val());
					formData.append('account1', $("#ssacc1").val());
					formData.append('account2', $("#ssacc2").val());
					formData.append('account3', $("#ssacc3").val());
					formData.append('account4', $("#ssacc4").val());
					formData.append('account5', $("#ssacc5").val());
					formData.append('account6', $("#ssacc6").val());
					formData.append('account7', $("#ssacc7").val());
					formData.append('meter1', $("#ssmeter1").val());
					formData.append('meter2', $("#ssmeter2").val());
					formData.append('meter3', $("#ssmeter3").val());
					formData.append('meter4', $("#ssmeter4").val());
					formData.append('meter5', $("#ssmeter5").val());
					formData.append('meter6', $("#ssmeter6").val());
					formData.append('meter7', $("#ssmeter7").val());*/
					formData.append('utility_service_type', Array.from($('.ssutsertp').get(), e => e.value).join('@@'));
					formData.append('vendor_name', Array.from($('.ssvenname').get(), e => e.value).join('@@'));
					formData.append('account', Array.from($('.ssaccount').get(), e => e.value).join('@@'));
					formData.append('meter', Array.from($('.ssmeter').get(), e => e.value).join('@@'));	

					formData.append('special_instructions', $("#sssplinst").val());


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
									swal("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
									$("#stop-service-form").get(0).reset();							
								}else if(results.error == 5)
								{
									swal("At least one entry mandatory in:","Utility Service Type, Vendor Name,Account and Meter", "warning");						
								}else{
									swal("Error in request.","Please try again later.", "warning");
								}
							}else{
								swal("","Error in request. Please try again later.", "warning");	
							}
						}
					  });
					return false;
				}else{
					swal("At least one entry mandatory in:","Utility Service Type, Vendor Name,Account and Meter", "warning");
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
</script>
<?php
	}else
		die("No records found!");
}else{
	header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	exit();		
}//else
	//die("Error Occured! Please try after sometime.");
?>