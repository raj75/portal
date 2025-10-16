<?php require_once("inc/init.php"); ?>
<?php
////error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

if(isset($_GET["action"]) and $_GET["action"]=="close" and isset($_GET["sid"]) and @trim($_GET["sid"]) != ""){
	$tmp_uid=$mysqli->real_escape_string(@trim($_GET["sid"]));

	$subcsql=$subccsql='';
	if(isset($_GET["cid"]) and @trim($_GET["cid"]) != ""){
		$tmp_cid=$mysqli->real_escape_string(@trim($_GET["cid"]));
		$subcsql=' and c.company_id='.$tmp_cid;
	}

	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){ 
		$subcsql=' and c.company_id='.$_SESSION["company_id"];
		$subccsql=' and c.ClientID='.$_SESSION["company_id"];
	}
	/*if ($stmtkkk = $mysqli->prepare('SELECT c.company_name,s.site_name,s.division,s.service_address1,s.service_address2,s.service_address3,s.city,s.state,s.postal_code,s.`zip+4`,s.country,s.site_status,s.site_number,s.region,s.contact1,s.phone1,s.fax1,s.email1,s.square_footage,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd INNER JOIN company c ON cd.companyID=c.company_id INNER JOIN sites s ON c.company_id=s.company_id where s.site_number="'.$tmp_uid.'"  '.$subcsql.' LIMIT 1')) {*/
	if ($stmtkkk = $mysqli->prepare('SELECT c.company_name,s.SiteName,s.Division,s.SiteAddress1,s.SiteAddress2,s.SiteAddress3,s.SiteCity,s.SiteState,s.SiteZip,s.SiteZip,s.Region,s.SiteStatus,s.SiteNumber,s.SiteCounty,s.ContactName1,s.ContactPhone1,s.ContactFax1,s.ContactEmail1,s.SquareFootage,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM vervantis.company_defaults cd INNER JOIN vervantis.company c ON cd.companyID=c.company_id INNER JOIN ubm_database.tblSites s ON c.company_id=s.ClientID where s.SiteNumber="'.$tmp_uid.'"  '.$subcsql.' LIMIT 1; ')) {

//('SELECT c.company_name,s.site_name,s.division,s.service_address1,s.service_address2,s.service_address3,s.city,s.state,s.postal_code,s.`zip+4`,s.country,s.site_status,s.site_number,s.region,s.contact1,s.phone1,s.fax1,s.email1,s.square_footage,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd, sites s, company c where s.id='.$tmp_uid.' and c.id=s.company_id and cd.companyID=c.id LIMIT 1')) {

        $stmtkkk->execute();
        $stmtkkk->store_result();
        if ($stmtkkk->num_rows > 0) {
			$stmtkkk->bind_result($company_name,$site_name,$site_division,$service_address1,$service_address2,$service_address3,$city,$state,$postal_code,$zip4,$country,$site_status,$site_number,$region,$contact1,$phone1,$fax1,$email1,$square_footage,$cdEntityName,$cdTaxID,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdcompanyID);
			$stmtkkk->fetch();
			if($zip4 != "" and $zip4 != "NULL") $postal_code= $postal_code." - ".$zip4;
?>
	<style>
	.dz-default span{
		left: 33%;
		position: relative;
		top: 40%
	}
	#add-dialog-message{
		overflow:hidden;
	}
	.width-full,.fullwidth{
		width:100% !important;
	}
	#edit-checkout-form header{background: #00bfff !important;text-align: center !important;color: #fff !important;font-weight: bold !important;}
	#edit-checkout-form footer{text-align:center;}
	#edit-checkout-form footer button{float:none !important;}
	</style>
		<div id="edit-dialog-message" title="Site Close Request">
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">
							<header>SITE INFORMATION</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Site Number
										<label class="input">
											<input type="text" tabindex="1" name="sssiteno" id="sssiteno" placeholder="Site Number" value="<?php echo $site_number; ?>" readonly="">
										</label>
									</section>
									<section class="col col-6">LOCATION TYPE
										<label class="input">
											<input type="text" tabindex="2" name="ssloctype" id="ssloctype" placeholder="LOCATION TYPE" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" tabindex="3" name="sssitename" id="sssitename" placeholder="Site Name" value="<?php echo $site_name; ?>" readonly>
										</label>
									</section>
									<section class="col col-6">REGION
										<label class="input">
											<input type="text" tabindex="4" name="ssregion" id="ssregion" placeholder="REGION" value="<?php echo $region; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Entity Name
										<label class="input">
											<input type="text" tabindex="5" name="ssentityname" id="ssentityname" placeholder="Site Name" value="<?php echo $cdEntityName; ?>">
										</label>
									</section>
									<section class="col col-6">DIVISION
										<label class="input">
											<input type="text" tabindex="6" name="ssdivision" id="ssdivision" placeholder="DIVISION" value="<?php echo $site_division; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Federal Tax ID Number
										<label class="input">
											<input type="text" tabindex="7" name="ssfedtaxidno" id="ssfedtaxidno" placeholder="Federal Tax ID Number" value="<?php echo $cdTaxID; ?>">
										</label>
									</section>
									<section class="col col-6">GL SITE #
										<label class="input">
											<input type="text" tabindex="8" name="ssglsite" id="ssglsite" placeholder="GL SITE #" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Address 1
										<label class="input">
											<input type="text" tabindex="9" name="sssiteaddr1" id="sssiteaddr1" placeholder="Site Address 1" value="<?php echo $service_address1; ?>">
										</label>
									</section>
									<section class="col col-6">Account Address 1
										<label class="input">
											<input type="text" tabindex="10" name="ssaccaddr1" id="ssaccaddr1" placeholder="Account Address 1" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Address 2
										<label class="input">
											<input type="text" tabindex="11" name="sssiteaddr2" id="sssiteaddr2" placeholder="Site Address 2" value="<?php echo $service_address2; ?>">
										</label>
									</section>
									<section class="col col-6">Account Address 2
										<label class="input">
											<input type="text" tabindex="12" name="ssaccaddr2" id="ssaccaddr2" placeholder="Account Address 2" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site City
										<label class="input">
											<input type="text" tabindex="13" name="sssitecity" id="sssitecity" placeholder="Site City" value="<?php echo $city; ?>">
										</label>
									</section>
									<section class="col col-6">Account City
										<label class="input">
											<input type="text" tabindex="14" name="ssacccity" id="ssacccity" placeholder="Account City" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site State
										<label class="input">
											<input type="text" tabindex="15" name="sssitestate" id="sssitestate" placeholder="Site State" value="<?php echo $state; ?>">
										</label>
									</section>
									<section class="col col-6">Account State
										<label class="input">
											<input type="text" tabindex="16" name="ssaccstate" id="ssaccstate" placeholder="Account State" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Zip
										<label class="input">
											<input type="text" tabindex="17" name="sssitezip" id="sssitezip" placeholder="Site Zip" value="<?php echo $postal_code; ?>">
										</label>
									</section>
									<section class="col col-6">Account Zip
										<label class="input">
											<input type="text" tabindex="18" name="ssacczip" id="ssacczip" placeholder="Account Zip" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Name
										<label class="input">
											<input type="text" tabindex="19" name="sssitecontname" id="sssitecontname" placeholder="Site Contact Name" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 1
										<label class="input">
											<input type="text" tabindex="20" name="ssbilladdr1" id="ssbilladdr1" placeholder="Billing Address 1" value="<?php echo $cdBillingAddress1; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Title
										<label class="input">
											<input type="text" tabindex="21" name="sssiteconttitle" id="sssiteconttitle" placeholder="Site Contact Title" value="">
										</label>
									</section>
									<section class="col col-6">Billing Address 2
										<label class="input">
											<input type="text" tabindex="22" name="ssbilladdr2" id="ssbilladdr2" placeholder="Billing Address 2" value="<?php echo $cdBillingAddress2; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Telephone
										<label class="input">
											<input type="text" tabindex="23" name="sssiteconttel" id="sssiteconttel" placeholder="Site Contact Telephone" value="<?php echo $phone1; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Address 3
										<label class="input">
											<input type="text" tabindex="24" name="ssbilladdr3" id="ssbilladdr3" placeholder="Billing Address 3" value="<?php echo $cdBillingAddress3; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Site Contact Fax
										<label class="input">
											<input type="text" tabindex="25" name="sssitecontfax" id="sssitecontfax" placeholder="Site Contact Fax" value="<?php echo $fax1; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Address 4
										<label class="input">
											<input type="text" tabindex="26" name="ssbilladdr4" id="ssbilladdr4" placeholder="Billing Address 4" value="">
										</label>
									</section>
								</div>
							</fieldset>
							<header>LANDLORD INFORMATION</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Leased Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select tabindex="27" name="ssleaseloc" id="ssleaseloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Landlord Name
										<label class="input">
											<input type="text" tabindex="28" name="sslandlordname" id="sslandlordname" placeholder="Landlord Name" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease Start Date
										<label class="input">
											<input type="text" tabindex="29" name="ssleasestdate" id="ssleasestdate" placeholder="Lease Start Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact Number
										<label class="input">
											<input type="text" tabindex="30" name="sscontno" id="sscontno" placeholder="Contact Number" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Lease End Date
										<label class="input">
											<input type="text" tabindex="31" name="ssleaseenddate" id="ssleaseenddate" placeholder="Lease End Date" value="">
										</label>
									</section>
									<section class="col col-6">Contact FAX
										<label class="input">
											<input type="text" tabindex="32" name="sscontfax" id="sscontfax" placeholder="Contact FAX" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">New Tenant
										<label class="input">
											<input type="text" tabindex="33" name="ssnewten" id="ssnewten" placeholder="New Tenant" value="">
										</label>
									</section>
									<section class="col col-6">Contact Email
										<label class="input">
											<input type="text" tabindex="34" name="sscontemail" id="sscontemail" placeholder="Contact Email" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Sublet
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select tabindex="35" name="sssublet" id="sssublet" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Address 1
										<label class="input">
											<input type="text" tabindex="36" name="ssaddr1" id="ssaddr1" placeholder="Address 1" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">
									</section>
									<section class="col col-6">Address 2
										<label class="input">
											<input type="text" tabindex="37" name="ssaddr2" id="ssaddr2" placeholder="Address 2" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Owned Location
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select tabindex="38" name="ssownloc" id="ssownloc" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">City
										<label class="input">
											<input type="text" tabindex="39" name="sscity" id="sscity" placeholder="City" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Sale Date
										<label class="input">
											<input type="text" tabindex="40" name="sssaledate" id="sssaledate" placeholder="Sale Date" value="">
										</label>
									</section>
									<section class="col col-6">State
										<label class="input">
											<input type="text" tabindex="41" name="ssstate" id="ssstate" placeholder="State" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">New Owner
										<label class="input">
											<input type="text" tabindex="42" name="ssnewown" id="ssnewown" placeholder="New Owner" value="">
										</label>
									</section>
									<section class="col col-6">Zip
										<label class="input">
											<input type="text" tabindex="43" name="sszip" id="sszip" placeholder="Zip" value="">
										</label>
									</section>
								</div>
							</fieldset>
							<header>SERVICES</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Turn Off Date<i style="color:red;">*</i>
										<label class="input">
											<input type="text" tabindex="44" name="ssturnoffdate" id="ssturnoffdate" placeholder="Turn Off Date" value="<?php @date("m/d/Y"); ?>">
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Require Meter Removal
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select tabindex="45" name="ssreqmetrem" id="ssreqmetrem" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N" selected>&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
									<section class="col col-6">
										<label class="select">
										</label>
									</section>
								</div>
								<div class="row">
<?php
	$utility_str=$vname_str=$acc_str=$meter_str="";
	$temp_acc="";
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
/////////////LOOK QUERY///////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
////////////////////////////////////////	
	
	
	
	/*if ($stmt = $mysqli->prepare('SELECT DISTINCT a.id,s.site_name,v.vendor_name,a.site_number,a.vendor_id,a.account_number1,a.account_number2,a.account_number3,v.service_group FROM `user` up INNER JOIN company c ON up.company_id = c.company_id INNER JOIN sites s ON c.company_id = s.company_id INNER JOIN `accounts` a ON a.site_number = s.site_number AND a.company_id = s.company_id INNER JOIN vendor v ON a.vendor_id = v.vendor_id WHERE a.site_number = "'.$site_number.'" '.$subcsql.' AND ( a.account_inactive_date = 0 OR a.account_inactive_date IS NULL ) GROUP BY a.account_number1, a.vendor_id')) {*/
	if ($stmt = $mysqli->prepare('SELECT
	c.AccountID,
	a.SiteName,
	ubm_database.tblVendors.VendorName,
	a.SiteNumber,
	c.VendorID,
	c.AccountNumber,
	c.AccountAlt1,
	c.AccountAlt2,
	d.user_id
FROM
	ubm_database.tblSites AS a
	INNER JOIN
	ubm_database.tblSiteAllocations AS b
	ON
		a.SiteID = b.SiteID AND
		a.ClientID = b.ClientID AND
		a.EntityID = b.EntityID
	INNER JOIN
	ubm_database.tblAccounts AS c
	ON
		c.AccountID = b.AccountID AND
		b.ClientID = c.ClientID AND
		b.EntityID = c.EntityID AND
		b.VendorID = c.VendorID
	INNER JOIN
	vervantis.`user` d
	ON
		a.ClientID = d.company_id
	INNER JOIN
	ubm_database.tblVendors
	ON
		c.VendorID = ubm_database.tblVendors.VendorID 
         WHERE a.SiteNumber = "'.$site_number.'" '.$subccsql.'  AND ( c.AccountInactiveDate = 0 OR c.AccountInactiveDate IS NULL ) GROUP BY c.AccountNumber, c.VendorID;')) {

//SELECT DISTINCT a.id,s.site_name,v.vendor_name,a.site_number,a.vendor_id,a.account_number1,a.account_number2,a.account_number3,v.service_group FROM vendor v INNER JOIN `accounts` a ON a.vendor_id=v.vendor_id INNER JOIN sites s ON a.site_number=s.site_number INNER JOIN company c ON s.company_id=c.company_id INNER JOIN user up ON up.company_id=c.company_id WHERE a.site_number="'.$site_number.'" '.$subcsql.' and (a.account_inactive_date = 0 OR a.account_inactive_date IS NULL)  group by a.account_number1,a.vendor_id
//('SELECT a.id,s.site_name,v.vendor_name,a.sites_id,a.vendor_id,a.account_number1,a.account_number2,a.account_number3,a.meter_id,v.commodity FROM `accounts` a, vendor v, sites s, company c, user up WHERE s.company_id=c.id and up.company_id=c.id and a.sites_id=s.site_number and a.vendor_id=v.id and a.sites_id="'.$site_number.'" and a.meter_id != "" and a.meter_id != 0 group by a.id')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($a_id,$a_site_name,$a_vendor_name,$a_sites_id,$a_vendor_id,$a_account_number1,$a_account_number2,$a_account_number3,$a_commodity);
			$ctt=46;
			while($stmt->fetch()){
				$temp_acc_sub=array();
				if($a_account_number1 != "")
					$temp_acc_sub[] = $a_account_number1;
				if($a_account_number2 != "")
					$temp_acc_sub[] = $a_account_number2;
				if($a_account_number3 != "")
					$temp_acc_sub[] = $a_account_number3;
					//$tmp_asites[]=array("a_s_id"=>$a_sites_id,"a_v_id"=>$a_vendor_id,"a_s_name"=>$a_site_name,"a_v_name"=>$a_vendor_name,"a_acc1"=>$a_account_number1,"a_acc2"=>$a_account_number2,"a_acc3"=>$a_account_number3,"a_id"=>$a_id,"a_meter_id"=>$a_meter_id);
				$utility_str=$utility_str.'<label class="input"><input type="text" class="ssutserty" placeholder="" value="'.$a_commodity.'" tabindex="'.$ctt.'"></label>';
				$vname_str=$vname_str.'<label class="input"><input type="text" class="ssvenname" placeholder="" value="'.$a_vendor_name.'" tabindex="'.$ctt.'"></label>';
				$acc_str=$acc_str.'<label class="input"><input type="text" class="ssacc" placeholder="" value="'.implode("-",$temp_acc_sub).'" tabindex="'.$ctt.'"></label>';
				//$meter_str=$meter_str.'<label class="input"><input type="text" class="ssmeter" placeholder="" value="'.$a_meter_id.'"></label>';
				$ctt++;
			}
		}else
			die('No accounts present for this site!');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

	if($utility_str != ""){
?>
									<section class="col col-4 center">Utility Service Type<i style="color:red;">*</i>
										<?php echo $utility_str; ?>
									</section>
									<section class="col col-4 center">Vendor Name<i style="color:red;">*</i>
										<?php echo $vname_str; ?>
									</section>
									<section class="col col-4 center">Account<i style="color:red;">*</i>
										<?php echo $acc_str; ?>
									</section>
	<?php } ?>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">Special Instructions
										<label class="textarea"> <i class="icon-append fa fa-comment"></i> 									<textarea tabindex="100" rows="4" id="sssplinst" name="sssplinst"></textarea> </label>
										<input type="hidden" name="ss" id="ss" placeholder="" value="ss">
										<input type="hidden" name="sscid" id="sscid" placeholder="" value="<?php echo $cdcompanyID; ?>">
									</section>
								</div>
							</fieldset>

							<footer>
								<button tabindex="101" type="submit" class="btn btn-primary" id="stop-service-submit">
									Close Site
								</button>
							</footer>
							<fieldset>
						</form>
	</div>

<!-- end row -->
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
	pageSetUp();

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


		$("#edit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Request Site Close</h4></div>",
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
				$("#edit-dialog-message").dialog('destroy');
				$("#edit-dialog-message").remove();
				parent.$("#response").html('');
              }
		});

		$('#edit-sa-cancel').click(function() {
			$("#edit-dialog-message").dialog("close");
			$("#edit-dialog-message").dialog('destroy');
			$("#edit-dialog-message").remove();
			parent.$("#response").html('');
		});



		var $checkoutForm = $('#edit-checkout-form').validate({
			rules : {
				ssturnoffdate : {
					required : true
				}
			},
			messages : {
				ssturnoffdate : {
					required : 'Please enter Turn Off Date'
				}
			},
		// Rules for form validation
			/*rules : {
				editcid : {
					required : true
				},
				editlocation : {
					required : true
				},
				editcategory : {
					required : true
				},
				editcommodity : {
					required : true
				},
				editstartdate : {
					required : true
				},
				editenddate : {
					required : true
				},
				editsaving : {
					required : true
				},
				editread : {
					required : true
				},
				editdateadded : {
					required : true
				}
			},*/

			// Messages for form validation
			/*messages : {
				editcid : {
					required : 'Please select company name'
				},
				editlocation : {
					required : 'Please enter location'
				},
				editcategory : {
					required : 'Please enter category'
				},
				editcommodity : {
					required : 'Please enter commodity'
				},
				editstartdate : {
					required : 'Select start date'
				},
				editenddate : {
					required : 'Select end date'
				},
				editsaving : {
					required : 'Please enter saving'
				},
				editread : {
					required : 'Select read'
				},
				editdateadded : {
					required : 'Select date added'
				}
			},*/
			// Ajax form submition
			submitHandler : function(form) {
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
				formData.append('siteclose', "true");

				formData.append('utility_service_type', Array.from($('.ssutserty').get(), e => e.value).join('@@'));
				formData.append('vendor_name', Array.from($('.ssvenname').get(), e => e.value).join('@@'));
				formData.append('account', Array.from($('.ssacc').get(), e => e.value).join('@@'));
				formData.append('meter', Array.from($('.ssmeter').get(), e => e.value).join('@@'));

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
				formData.append('special_instructions', $("#sssplinst").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/servicestesting.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								alert("Request received! We will process it soon.");
								$("#edit-dialog-message").dialog("close");
								$("#edit-dialog-message").dialog('destroy');
								$("#edit-dialog-message").remove();
								parent.$("#response").html('');
							}else
								alert("Error in request. Please try again later.");
						}else{
							alert("Error in request. Please try again later.");
						}
					}
				  });
				return false;
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

	function profileAdd(){
	}
</script>
<?php
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
}elseif(isset($_GET["action"]) and $_GET["action"]=="close" and isset($_GET["aid"]) and @trim($_GET["aid"]) != 0 and @trim($_GET["aid"]) != "" and isset($_GET["sno"]) and @trim($_GET["sno"]) != ""){
	$tmp_aid=$mysqli->real_escape_string(@trim($_GET["aid"]));
	$tmp_sno=$mysqli->real_escape_string(@trim($_GET["sno"]));

	$subcsql='';
	if(isset($_GET["cid"]) and @trim($_GET["cid"]) != ""){
		$tmp_cid=$mysqli->real_escape_string(@trim($_GET["cid"]));
		$subcsql=' and c.company_id='.$tmp_cid;
	}

	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $subcsql=' and c.company_id='.$_SESSION["company_id"];

	if ($stmtkkk = $mysqli->prepare('SELECT c.company_name,s.SiteName,s.Division,s.SiteAddress1,s.SiteAddress2,s.SiteAddress3,s.SiteCity,s.SiteState,s.SiteZip,s.SiteZip,s.SiteCountry,s.SiteStatus,s.SiteNumber,s.Region,s.ContactName1,s.ContactPhone1,s.ContactFax1,s.ContactEmail1,s.SquareFootage,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd INNER JOIN company c ON cd.companyID=c.company_id INNER JOIN ubm_database.tblSites s ON c.company_id=s.ClientID where s.SiteID="'.$tmp_sno.'" '.$subcsql.'  LIMIT 1')) {

//('SELECT c.company_name,s.site_name,s.division,s.service_address1,s.service_address2,s.service_address3,s.city,s.state,s.postal_code,s.`zip+4`,s.country,s.site_status,s.site_number,s.region,s.contact1,s.phone1,s.fax1,s.email1,s.square_footage,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.companyID FROM company_defaults cd, sites s, company c where s.id='.$tmp_sno.' and c.id=s.company_id and cd.companyID=c.id LIMIT 1')) {

        $stmtkkk->execute();
        $stmtkkk->store_result();
        if ($stmtkkk->num_rows > 0) {
			$stmtkkk->bind_result($company_name,$site_name,$site_division,$service_address1,$service_address2,$service_address3,$city,$state,$postal_code,$zip4,$country,$site_status,$site_number,$region,$contact1,$phone1,$fax1,$email1,$square_footage,$cdEntityName,$cdTaxID,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdcompanyID);
			$stmtkkk->fetch();
			if($zip4 != "" and $zip4 != "NULL") $postal_code= $postal_code." - ".$zip4;

			/*if ($stmta = $mysqli->prepare('SELECT a.meter_number,vendor_id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3 FROM accounts a,vendor v where a.id='.$tmp_aid.' and a.vendor_id=vendor_id and a.meter_number != "" and a.meter_number != 0')) {

//('SELECT a.meter_id,v.id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3 FROM accounts a,vendor v where a.id='.$tmp_aid.' and a.vendor_id=v.id and a.meter_id != "" and a.meter_id != 0')) {
				$stmta->execute();
				$stmta->store_result();
				if ($stmta->num_rows > 0) {
					$stmta->bind_result($_mtid,$_v_vid,$_v_name,$_a1,$_a2,$_a3);
					while($stmta->fetch()) {

					}
				}
			}*/
			$temp_aasites=array();
			$tmp_list=$tmp_list_ini="";
			$utility=$meterini=$vendorini="";
?>
<script>var temp_aasites=[];</script>
<?php
if ($stmtaa = $mysqli->prepare('SELECT DISTINCT
	d.AccountID,
	a.ClientID,
	a.SiteID AS ID,
	c.VendorName AS vendor_name,
	a.SiteNumber AS site_number,
	d.AccountNumber AS account_number1,
	d.AccountAlt1 AS account_number2,
	d.AccountAlt2 AS account_number3,
	f.MeterNumber AS meter_number,
	e.ServiceTypeName AS service_group
FROM
	ubm_database.tblSites a
LEFT JOIN
	ubm_database.tblSiteAllocations b
ON
	a.ClientID = b.ClientID
	AND a.SiteID = b.SiteID
INNER JOIN
	ubm_database.tblVendors c
ON
	b.VendorID = c.VendorID
INNER JOIN
	ubm_database.tblAccounts d
ON
	b.AccountID = d.AccountID
INNER JOIN
	ubm_database.tblServiceTypes e
ON
	b.ServiceTypeID = e.ServiceTypeID
INNER JOIN
	ubm_database.tblMeters f
ON
	b.MeterID = f.MeterID
WHERE
	a.SiteID="'.$tmp_sno.'"
	AND b.DeleteStatus=0
	AND d.DeleteStatus=0
	AND f.DeleteStatus=0')) {
			/*if ($stmtaa = $mysqli->prepare('SELECT a.ID,
			v.VendorName,
			a.site_number,
			a.AccountNumber,
			a.AccountAlt1,
			a.AccountAlt2,
			a.meter_number,
			v.service_group 
			FROM ubm_database.tblVendors v
			INNER JOIN 
			ubm_database.tblAccounts a 
			ON a.VendorID=v.VendorID 
			INNER JOIN 
			ubm_database.tblSites s ON 
			a.site_number=s.SiteNumber 
			AND a.company_id = s.ClientID 
			INNER JOIN 
			company c ON s.ClientID=c.company_id 
			INNER JOIN 
			user up ON up.company_id=c.company_id 
			WHERE a.site_number="'.$site_number.'" '.$subcsql.' group by a.AccountNumber')) {*/

			//if ($stmtaa = $mysqli->prepare('SELECT a.ID,v.vendor_name,a.site_number,a.account_number1,a.account_number2,a.account_number3,a.meter_number,v.service_group FROM vendor v INNER JOIN `accounts` a ON a.vendor_id=v.vendor_id INNER JOIN ubm_database.tblSites s ON a.site_number=s.SiteNumber AND a.company_id = s.ClientID INNER JOIN company c ON s.ClientID=c.company_id INNER JOIN user up ON up.company_id=c.company_id WHERE a.site_number="'.$site_number.'" '.$subcsql.' group by a.account_number1')) {
			//if ($stmtaa = $mysqli->prepare('SELECT a.ID,v.vendor_name,a.site_number,a.account_number1,a.account_number2,a.account_number3,a.meter_number,v.service_group FROM `accounts` a, vendor v, sites s, company c, user up WHERE s.company_id=c.company_id and up.company_id=c.company_id and a.site_number=s.site_number and a.vendor_id=v.vendor_id and a.site_number="'.$site_number.'" group by a.ID')) {

//'SELECT a.ID,v.vendor_name,a.site_number,a.account_number1,a.account_number2,a.account_number3,a.meter_number,v.service_group FROM `accounts` a, vendor v, sites s, company c, user up WHERE s.company_id=c.company_id and up.company_id=c.company_id and a.site_number=s.site_number and a.vendor_id=v.vendor_id and a.site_number="'.$site_number.'" and a.meter_number != "" and a.meter_number != 0 group by a.ID'
//('SELECT a.id,v.vendor_name,a.sites_id,a.account_number1,a.account_number2,a.account_number3,a.meter_id,v.commodity FROM `accounts` a, vendor v, sites s, company c, user up WHERE s.company_id=c.id and up.company_id=c.id and a.sites_id=s.site_number and a.vendor_id=v.id and a.sites_id="'.$site_number.'" and a.meter_id != "" and a.meter_id != 0 group by a.id')) {

				$stmtaa->execute();
				$stmtaa->store_result();
				if ($stmtaa->num_rows > 0) {
					$stmtaa->bind_result($aa_id,$aa_clientid,$aa_siteid,$aa_vendor_name,$aa_site_name,$aa_account_number1,$aa_account_number2,$aa_account_number3,$aa_meter_id,$aa_commodity);
					while($stmtaa->fetch()){
						$temp_acc_sub=array();
						if($aa_account_number1 != "")
							$temp_acc_sub[] = $aa_account_number1;
						if($aa_account_number2 != "")
							$temp_acc_sub[] = $aa_account_number2;
						if($aa_account_number3 != "")
							$temp_acc_sub[] = $aa_account_number3;

						//$temp_aasites[$aa_id][]=array("a_id"=>$aa_id,"a_v_name"=>$aa_vendor_name,"a_s_name"=>$aa_site_name,"a_acc1"=>$aa_account_number1,"a_acc2"=>$aa_account_number2,"a_acc3"=>$aa_account_number3,"a_meter_id"=>$aa_meter_id"a_commodity"=>$aa_commodity);
						$tmp_acc_drp = @implode("-",$temp_acc_sub);
						$tmp_list= $tmp_list."<option value='".$tmp_acc_drp."'>".$tmp_acc_drp."</option>";

						if($aa_id==$tmp_aid){
							$utility=$aa_commodity;
							$meterini=$aa_meter_id;
							$vendorini=$aa_vendor_name;
							$tmp_list_ini= $tmp_list_ini."<option value='".$tmp_acc_drp."' Selected>".$tmp_acc_drp."</option>";
						}else{
							$tmp_list_ini= $tmp_list_ini."<option value='".$tmp_acc_drp."'>".$tmp_acc_drp."</option>";
						}

						?><script>temp_aasites["<?php echo $tmp_acc_drp;?>"]={"a_id":"<?php echo $aa_id;?>","a_v_name":"<?php echo $aa_vendor_name;?>","a_s_name":"<?php echo $aa_site_name;?>","a_acc1":"<?php echo $aa_account_number1;?>","a_acc2":"<?php echo $aa_account_number2;?>","a_acc3":"<?php echo $aa_account_number3;?>","a_meter_id":"<?php echo $aa_meter_id;?>","a_commodity":"<?php echo $aa_commodity;?>"};</script><?php
					}
				}else
					die('No accounts present for this site!');
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			//if(!count($temp_aasites)) die('No accounts present for this site!');
?>
	<style>
	.dz-default span{
		left: 33%;
		position: relative;
		top: 40%
	}
	#add-dialog-message{
		overflow:hidden;
	}
	.width-full,.fullwidth{
		width:100% !important;
	}
	#edit-checkout-form header{background: #00bfff !important;text-align: center !important;color: #fff !important;font-weight: bold !important;}
	#edit-checkout-form footer{text-align:center;}
	#edit-checkout-form .pcenter{text-align:center !important;float:none !important;}
	#edit-checkout-form footer button{float:none !important;}
	.22width{width:22% !important;}
	.10width{width:10% !important;}
	.vali{vertical-align: middle;line-height: 5;}
	.remdetbut {
		background-color: Transparent;
		background-repeat:no-repeat;
		border: none !important;
		cursor:pointer;
		overflow: hidden;
		outline:none !important;
		color:red !important;
	}
	</style>
		<div id="edit-dialog-message" title="Edit Saving Analysis">
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">
							<header>SITE INFORMATION</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Site Number
										<label class="input">
											<input type="text" name="sssiteno" id="sssiteno" placeholder="Site Number" value="<?php echo $site_number; ?>" readonly="">
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
											<input type="text" name="sssitename" id="sssitename" placeholder="Site Name" value="<?php echo $site_name; ?>" readonly>
										</label>
									</section>
									<section class="col col-6">REGION
										<label class="input">
											<input type="text" name="ssregion" id="ssregion" placeholder="REGION" value="<?php echo $region; ?>">
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
											<input type="text" name="ssdivision" id="ssdivision" placeholder="DIVISION" value="<?php echo $site_division; ?>">
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
											<input type="text" name="sssiteaddr1" id="sssiteaddr1" placeholder="Site Address 1" value="<?php echo $service_address1; ?>">
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
											<input type="text" name="sssiteaddr2" id="sssiteaddr2" placeholder="Site Address 2" value="<?php echo $service_address2; ?>">
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
											<input type="text" name="sssitecity" id="sssitecity" placeholder="Site City" value="<?php echo $city; ?>">
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
											<input type="text" name="sssitestate" id="sssitestate" placeholder="Site State" value="<?php echo $state; ?>">
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
											<input type="text" name="sssitezip" id="sssitezip" placeholder="Site Zip" value="<?php echo $postal_code; ?>">
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
											<input type="text" name="sssiteconttel" id="sssiteconttel" placeholder="Site Contact Telephone" value="<?php echo $phone1; ?>">
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
											<input type="text" name="sssitecontfax" id="sssitecontfax" placeholder="Site Contact Fax" value="<?php echo $fax1; ?>">
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
									<section class="col col-6">Turn Off Date<i style="color:red;">*</i>
										<label class="input">
											<input type="text" name="ssturnoffdate" id="ssturnoffdate" placeholder="Turn Off Date" value="">
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
									<section class="col center 22width" id="ust">Utility Service Type<i style="color:red;">*</i>
										<label class="input label1">
											<input type="text" name="ssutserty1" id="ssutserty1" class="ssutserty" placeholder="" value="<?php echo $utility;?>" tabindex="1">
										</label>
									</section>
									<section class="col center 22width" id="vn">Vendor Name<i style="color:red;">*</i>
										<label class="input label1">
											<input type="text" name="ssvenname1" id="ssvenname1" class="ssvenname" placeholder="" value="<?php echo $vendorini;?>" tabindex="2">
										</label>
									</section>
									<section class="col center 22width" id="accc">Account<i style="color:red;">*</i>
										<label class="input label1">
											<select id='ssacc1' class='form-control selectdp' tabindex="3"><?php echo $tmp_list_ini;?></select>
										</label>
									</section>
									<section class="col center 22width" id="mte">Meter
										<label class="input label1">
											<input type="text" name="ssmeter1" id="ssmeter1" class="ssmeter" placeholder="" value="<?php echo $meterini;?>" tabindex="4">
										</label>
									</section>
									<section class="col center 10width" id="remdet">&nbsp;
										<label class="input label1">
											<input type="button" id="removemoredetails1" class="remdetbut" placeholder="" value="- Remove" onclick="remdet(1)">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-12 center pcenter">
										<label class="input">
											<a href="javascript:void(0)" id="addmoredetails">+ Add more</a>
										</label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-12 fullwidth">Special Instructions
										<label class="textarea"> <i class="icon-append fa fa-comment"></i> 								  <textarea rows="4" id="sssplinst" name="sssplinst"></textarea> </label>
										<input type="hidden" name="ss" id="ss" placeholder="" value="ss">
										<input type="hidden" name="sscid" id="sscid" placeholder="" value="<?php echo $cdcompanyID; ?>">
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="stop-service-submit">
									Close Account
								</button>
								<button type="submit" class="btn btn-primary" id="edit-sa-cancel">
									Cancel
								</button>
							</footer>
							<fieldset>
						</form>
	</div>

<!-- end row -->
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
	pageSetUp();

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

		$( document ).off( "change", ".selectdp" );
		$(document).on('change', ".selectdp", function(){
			var ids=$(this).attr("id").replace ( /[^\d.]/g, '' );
			var aidd= $(this).val();
			$('#ssutserty'+ids).val(temp_aasites[aidd]["a_commodity"]);
			$('#ssvenname'+ids).val(temp_aasites[aidd]["a_v_name"]);
			$('#ssmeter'+ids).val(temp_aasites[aidd]["a_meter_id"]);
		});

		var ranno=1;
		$('#addmoredetails').click(function() {
			ranno=ranno + 1;
			var ssaccl="<?php echo $tmp_list;?>";
			$("#ust").append('<label class="input label'+ranno+'"><input type="text" name="ssutserty'+ranno+'" class="ssutserty" id="ssutserty'+ranno+'" placeholder="" value="" tabindex="1"></label>');
			$("#vn").append('<label class="input label'+ranno+'"><input type="text" name="ssvenname'+ranno+'" class="ssvenname" id="ssvenname'+ranno+'" placeholder="" value="" tabindex="2"></label>');
			$("#accc").append('<label class="input label'+ranno+'"><select id="ssacc'+ranno+'" class="form-control selectdp" tabindex="3">'+ssaccl+'</select></label>');
			$("#mte").append('<label class="input label'+ranno+'"><input type="text" name="ssmeter'+ranno+'" class="ssmeter" id="ssmeter'+ranno+'" placeholder="" value="" tabindex="4"></label>');
			$("#remdet").append('<label class="input label'+ranno+'"><input type="button" id="removemoredetails'+ranno+'" class="remdetbut" placeholder="" value="- Remove" onclick="remdet(\''+ranno+'\')"></label>');
		});

		$( document ).off( "change", ".selectdpp" );
	   $(document).on('change', '.selectdpp', function(){
		   var values = [];
		   $.each($('select option:selected'), function(){
			   values.push($(this).val());
		   });
			alert(values);
		   console.log(values);
	   });


		$("#edit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Request Account Close</h4></div>",
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
				$("#edit-dialog-message").dialog('destroy');
				$("#edit-dialog-message").remove();
				parent.$("#response").html('');
              }
		});

		$('#edit-sa-cancel').click(function() {
			$("#edit-dialog-message").dialog("close");
			$("#edit-dialog-message").dialog('destroy');
			$("#edit-dialog-message").remove();
			parent.$("#response").html('');
		});



		var $checkoutForm = $('#edit-checkout-form').validate({
			rules : {
				ssturnoffdate : {
					required : true
				}
			},
			messages : {
				ssturnoffdate : {
					required : 'Please enter Turn Off Date'
				}
			},
		// Rules for form validation
			/*rules : {
				editcid : {
					required : true
				},
				editlocation : {
					required : true
				},
				editcategory : {
					required : true
				},
				editcommodity : {
					required : true
				},
				editstartdate : {
					required : true
				},
				editenddate : {
					required : true
				},
				editsaving : {
					required : true
				},
				editread : {
					required : true
				},
				editdateadded : {
					required : true
				}
			},*/

			// Messages for form validation
			/*messages : {
				editcid : {
					required : 'Please select company name'
				},
				editlocation : {
					required : 'Please enter location'
				},
				editcategory : {
					required : 'Please enter category'
				},
				editcommodity : {
					required : 'Please enter commodity'
				},
				editstartdate : {
					required : 'Select start date'
				},
				editenddate : {
					required : 'Select end date'
				},
				editsaving : {
					required : 'Please enter saving'
				},
				editread : {
					required : 'Select read'
				},
				editdateadded : {
					required : 'Select date added'
				}
			},*/
			// Ajax form submition
			submitHandler : function(form) {
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

				formData.append('utility_service_type', Array.from($('.ssutserty').get(), e => e.value).join('@@'));
				formData.append('vendor_name', Array.from($('.ssvenname').get(), e => e.value).join('@@'));
				formData.append('account', Array.from($('.selectdp').get(), e => e.value).join('@@'));
				formData.append('meter', Array.from($('.ssmeter').get(), e => e.value).join('@@'));

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
				formData.append('special_instructions', $("#sssplinst").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/servicestesting.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								alert("Request received! We will process it soon.");
								$("#edit-dialog-message").dialog("close");
								$("#edit-dialog-message").dialog('destroy');
								$("#edit-dialog-message").remove();
								parent.$("#response").html('');
							}else
								alert("Error in request. Please try again later.");
						}else{
							alert("Error in request. Please try again later.");
						}
					}
				  });
				return false;
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

	function profileAdd(){
	}

	function remdet(accdetid){
		$('.label'+accdetid+'').remove();
	}

function addmoredetails(ranno){
			$("#ust").append('<label class="input label'+ranno+'"><input type="text" name="ssutserty'+ranno+'" id="ssutserty'+ranno+'" placeholder="" value=""></label>');
			$("#vn").append('<label class="input label'+ranno+'"><input type="text" name="ssvenname'+ranno+'" id="ssvenname'+ranno+'" placeholder="" value=""></label>');
			$("#accc").append('<label class="input label'+ranno+'"><input type="text" name="ssacc'+ranno+'" id="ssacc'+ranno+'" placeholder="" value=""></label>');
			$("#mte").append('<label class="input label'+ranno+'"><input type="text" name="ssmeter'+ranno+'" id="ssmeter'+ranno+'" placeholder="" value=""></label>');
			$("#remdet").append('<label class="input label'+ranno+'"><input type="button" id="removemoredetails'+ranno+'" class="remdetbut" placeholder="" value="- Remove" onclick="remdet(\''+ranno+'\')"></label>');
}
</script>
<?php
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
} ?>
