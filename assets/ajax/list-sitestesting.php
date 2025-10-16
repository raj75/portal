<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

if(isset($_GET['sid']) and @trim($_GET['sid']) != "" and @trim($_GET['sid']) != 0 and isset($_GET['editsid']))
{
	$tmpsql="";
	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $tmpsql=" AND a.ClientID =".$_SESSION["company_id"];
	$sid=@trim($_GET['sid']);
	//if ($stmt = $mysqli->prepare('SELECT s.site_number,s.company_id,s.division,s.active_date,s.inactive_date,s.country,s.state,s.city,s.site_number,s.site_name,s.service_address1,s.service_address2,s.service_address3,s.site_type,s.postal_code,s.square_footage,s.number_of_floors,s.number_of_units,s.region,s.naics,s.sic,s.managed,s.alternate_name,s.ownership,s.year_built,s.number_of_employees,s.weekly_operating_hours,s.phone,s.hdd_cdd_balance_point,s.wban,s.weather_station_name,s.weather_station_city,s.site_status FROM sites s,company c, user up WHERE s.site_number="'.$sid.'" and s.company_id=company_id.id and up.company_id=company_id.id LIMIT 1')) {
	if ($stmt = $mysqli->prepare('"SELECT
	b.company_name AS Company,
	a.SiteName AS `Site Name`, 
	a.Division AS Division,
	a.SiteAddress1 AS `Address`, 
	a.SiteAddress2 AS `Address 2`, 
	a.SiteAddress3 AS `Address 3`, 
	a.SiteCity AS City, 
	a.SiteState AS State, 
	LEFT(a.SiteZip,5) AS `Postal Code`, 
	RIGHT(a.SiteZip,4) AS `Zip+4`, 
	a.SiteCountry AS Country, 
	a.SiteStatus AS `Site Status`,  
	a.SiteNumber AS `Site Number`, 
	a.Region AS `Region`, 
	a.ContactName1 AS `Contact`, 
	a.ContactPhone1 AS `Phone`, 
	a.ContactFax1 AS `Fax`, 
	a.ContactEmail1 AS `Email`, 
	a.SquareFootage AS `Square Feet`, 
	b.site_close_btn AS `Site Close Button`
FROM
	ubm_database.tblSites AS a
LEFT JOIN
	vervantis.company AS b
ON 
	a.ClientID = b.company_id 
WHERE
	a.SiteID = "'.$sid.'"'.$tmpsql)) {


//('SELECT s.id,s.company_id,s.division,s.active_date,s.inactive_date,s.country,s.state,s.city,s.site_number,s.site_name,s.service_address1,s.service_address2,s.service_address3,s.site_type,s.postal_code,s.square_footage,s.number_of_floors,s.number_of_units,s.region,s.naics,s.sic,s.managed,s.alternate_name,s.ownership,s.year_built,s.number_of_employees,s.weekly_operating_hours,s.phone,s.hdd_cdd_balance_point,s.wban,s.weather_station_name,s.weather_station_city,s.site_status FROM sites s,company c, user up WHERE s.site_number="'.$sid.'" and s.company_id=c.id and up.company_id=c.id LIMIT 1')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$Company_Id,$Division,$Active_Date,$Inactive_Date,$Country,$State,$City,$Site_Number,$Site_Name,$Service_Address1,$Service_Address2,$Service_Address3,$Site_Type,$Postal_Code,$Square_Footage,$Number_Of_Floors,$Number_Of_Units,$Region,$Naics,$Sic,$Managed,$Alternate_Name,$Ownership,$Year_Built,$Number_Of_Employees,$Weekly_Operating_Hours,$Phone,$Hdd_Cdd_Balance_Point,$Wban,$Weather_Station_Name,$Weather_Station_City,$Site_Status);
			$stmt->fetch();

?>
		<div id="edit-dialog-message" title="Edit Site">
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileSite()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" name="addsname" id="addsname" placeholder="Site name" value="<?php echo $Site_Name; ?>">
										</label>
									</section>
									<section class="col col-6">Site Number
										<label class="input">
											<input type="number" name="addsnumber" id="addsnumber" placeholder="Site number" value="<?php echo $Site_Number; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Company Name
										<label class="select">
											<!--<input type="text" name="addcompany" id="addcompany" placeholder="Company" value="">-->
											<select name="addcompany" id="addcompany" placeholder="Company">
												<option value="">Select Company</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company ORDER BY company_name')){

//('SELECT id,company_name FROM company ORDER BY company_name')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__company);
														while($stmt->fetch()){
															echo "<option value='".$__id."' ".(($Company_Id==$__id)?"Selected":"").">".$__company."</option>";
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
									<section class="col col-6">Division
										<label class="input">
											<input type="text" name="adddivision" id="adddivision" placeholder="Division" value="<?php echo $Division; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Active Date
										<label class="input">
											<input type="text" name="addadate" id="addadate" placeholder="Active Date" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($Active_Date)); ?>">
										</label>
									</section>
									<section class="col col-6">Inactive Date
										<label class="input">
											<input type="text" name="addinadate" id="addinadate" placeholder="Inactive Date" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($Inactive_Date)); ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Service Address1
										<label class="input">
											<input type="text" name="addsadd1" id="addsadd1" placeholder="Service Address1" value="<?php echo $Service_Address1; ?>">
										</label>
									</section>
									<section class="col col-6">Service Address2
										<label class="input">
											<input type="text" name="addsadd2" id="addsadd2" placeholder="Service Address2" value="<?php echo $Service_Address2; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Service Address3
										<label class="input">
											<input type="text" name="addsadd3" id="addsadd3" placeholder="Service Address3" value="<?php echo $Service_Address3; ?>">
										</label>
									</section>
									<section class="col col-6">City
										<label class="input">
											<input type="text" name="addcity" id="addcity" placeholder="City" value="<?php echo $City; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">State
										<label class="select">
											<select name="addstate" id="addstate" placeholder="State">
												<option value="">Select State</option>
												<option value="AL" <?php if($State=="AL"){echo "Selected";} ?>>Alabama</option>
												<option value="AK" <?php if($State=="AK"){echo "Selected";} ?>>Alaska</option>
												<option value="AZ" <?php if($State=="AZ"){echo "Selected";} ?>>Arizona</option>
												<option value="AR" <?php if($State=="AR"){echo "Selected";} ?>>Arkansas</option>
												<option value="CA" <?php if($State=="CA"){echo "Selected";} ?>>California</option>
												<option value="CO" <?php if($State=="CO"){echo "Selected";} ?>>Colorado</option>
												<option value="CT" <?php if($State=="CT"){echo "Selected";} ?>>Connecticut</option>
												<option value="DE" <?php if($State=="DE"){echo "Selected";} ?>>Delaware</option>
												<option value="DC" <?php if($State=="DC"){echo "Selected";} ?>>District Of Columbia</option>
												<option value="FL" <?php if($State=="FL"){echo "Selected";} ?>>Florida</option>
												<option value="GA" <?php if($State=="GA"){echo "Selected";} ?>>Georgia</option>
												<option value="HI" <?php if($State=="HI"){echo "Selected";} ?>>Hawaii</option>
												<option value="ID" <?php if($State=="ID"){echo "Selected";} ?>>Idaho</option>
												<option value="IL" <?php if($State=="IL"){echo "Selected";} ?>>Illinois</option>
												<option value="IN" <?php if($State=="IN"){echo "Selected";} ?>>Indiana</option>
												<option value="IA" <?php if($State=="IA"){echo "Selected";} ?>>Iowa</option>
												<option value="KS" <?php if($State=="KS"){echo "Selected";} ?>>Kansas</option>
												<option value="KY" <?php if($State=="KY"){echo "Selected";} ?>>Kentucky</option>
												<option value="LA" <?php if($State=="LA"){echo "Selected";} ?>>Louisiana</option>
												<option value="ME" <?php if($State=="ME"){echo "Selected";} ?>>Maine</option>
												<option value="MD" <?php if($State=="MD"){echo "Selected";} ?>>Maryland</option>
												<option value="MA" <?php if($State=="MA"){echo "Selected";} ?>>Massachusetts</option>
												<option value="MI" <?php if($State=="MI"){echo "Selected";} ?>>Michigan</option>
												<option value="MN" <?php if($State=="MN"){echo "Selected";} ?>>Minnesota</option>
												<option value="MS" <?php if($State=="MS"){echo "Selected";} ?>>Mississippi</option>
												<option value="MO" <?php if($State=="MO"){echo "Selected";} ?>>Missouri</option>
												<option value="MT" <?php if($State=="MT"){echo "Selected";} ?>>Montana</option>
												<option value="NE" <?php if($State=="NE"){echo "Selected";} ?>>Nebraska</option>
												<option value="NV" <?php if($State=="NV"){echo "Selected";} ?>>Nevada</option>
												<option value="NH" <?php if($State=="NH"){echo "Selected";} ?>>New Hampshire</option>
												<option value="NJ" <?php if($State=="NJ"){echo "Selected";} ?>>New Jersey</option>
												<option value="NM" <?php if($State=="NM"){echo "Selected";} ?>>New Mexico</option>
												<option value="NY" <?php if($State=="NY"){echo "Selected";} ?>>New York</option>
												<option value="NC" <?php if($State=="NC"){echo "Selected";} ?>>North Carolina</option>
												<option value="ND" <?php if($State=="ND"){echo "Selected";} ?>>North Dakota</option>
												<option value="OH" <?php if($State=="OH"){echo "Selected";} ?>>Ohio</option>
												<option value="OK" <?php if($State=="OK"){echo "Selected";} ?>>Oklahoma</option>
												<option value="OR" <?php if($State=="OR"){echo "Selected";} ?>>Oregon</option>
												<option value="PA" <?php if($State=="PA"){echo "Selected";} ?>>Pennsylvania</option>
												<option value="RI" <?php if($State=="RI"){echo "Selected";} ?>>Rhode Island</option>
												<option value="SC" <?php if($State=="SC"){echo "Selected";} ?>>South Carolina</option>
												<option value="SD" <?php if($State=="SD"){echo "Selected";} ?>>South Dakota</option>
												<option value="TN" <?php if($State=="TN"){echo "Selected";} ?>>Tennessee</option>
												<option value="TX" <?php if($State=="TX"){echo "Selected";} ?>>Texas</option>
												<option value="UT" <?php if($State=="UT"){echo "Selected";} ?>>Utah</option>
												<option value="VT" <?php if($State=="VT"){echo "Selected";} ?>>Vermont</option>
												<option value="VA" <?php if($State=="VA"){echo "Selected";} ?>>Virginia</option>
												<option value="WA" <?php if($State=="WA"){echo "Selected";} ?>>Washington</option>
												<option value="WV" <?php if($State=="WV"){echo "Selected";} ?>>West Virginia</option>
												<option value="WI" <?php if($State=="WI"){echo "Selected";} ?>>Wisconsin</option>
												<option value="WY" <?php if($State=="WY"){echo "Selected";} ?>>Wyoming</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Postalcode
										<label class="input">
											<input type="text" name="addzip" id="addzip" placeholder="Postalcode" value="<?php echo $Postal_Code; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Country
										<label class="select">
											<select name="addcountry" id="addcountry" placeholder="Country">
												<option value="">Select Country</option>
												<option value="US" <?php if($Country=="US"){echo "Selected";} ?>>US</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Site Type
										<label class="input">
											<input type="text" name="addsitetype" id="addsitetype" placeholder="Site Type" value="<?php echo $Site_Type; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Square Footage
										<label class="input">
											<input type="number" name="addsqfootage" id="addsqfootage" placeholder="Square Footage" value="<?php echo $Square_Footage; ?>">
										</label>
									</section>
									<section class="col col-6">Number Of Floors
										<label class="input">
											<input type="number" name="addnofloor" id="addnofloor" placeholder="Number Of Floors" value="<?php echo $Number_Of_Floors; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Number Of Units
										<label class="input">
											<input type="text" name="addnounits" id="addnounits" placeholder="Number Of Units" value="<?php echo $Number_Of_Units; ?>">
										</label>
									</section>
									<section class="col col-6">Region
										<label class="input">
											<input type="text" name="addregion" id="addregion" placeholder="Region" value="<?php echo $Region; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Naics
										<label class="input">
											<input type="text" name="addnaics" id="addnaics" placeholder="Naics" value="<?php echo $Naics; ?>">
										</label>
									</section>
									<section class="col col-6">Sic
										<label class="input">
											<input type="text" name="addsic" id="addsic" placeholder="Sic" value="<?php echo $Sic; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Managed
										<label class="input">
											<input type="text" name="addmanaged" id="addmanaged" placeholder="Managed" value="<?php echo $Managed; ?>">
										</label>
									</section>
									<section class="col col-6">Alternate Name
										<label class="input">
											<input type="text" name="addaltname" id="addaltname" placeholder="Alternate Name" value="<?php echo $Alternate_Name; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Ownership
										<label class="input">
											<input type="text" name="addownership" id="addownership" placeholder="Ownership" value="<?php echo $Ownership; ?>">
										</label>
									</section>
									<section class="col col-6">Year Built
										<label class="select">
											<select name="addybuilt" id="addybuilt">
												<option value="">Select Year Built</option>
												<?php
													for($i=1950;$i<=(int)date("Y");$i++)
														echo '<option value="'.$i.'" '.(($Year_Built==$i)?'Selected':'').'>'.$i.'</option>';
												?>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Number Of Employees
										<label class="input">
											<input type="number" name="addnoemp" id="addnoemp" placeholder="Number Of Employees" value="<?php echo $Number_Of_Employees; ?>">
										</label>
									</section>
									<section class="col col-6">Weekly Operating Hours
										<label class="input">
											<input type="number" name="addwohours" id="addwohours" placeholder="Weekly Operating Hours" value="<?php echo $Weekly_Operating_Hours; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Phone
										<label class="input">
											<input type="tel" name="addphone" id="addphone" placeholder="Phone" data-mask="(999) 999-9999" value="<?php echo $Phone; ?>">
										</label>
									</section>
									<section class="col col-6">HDD CDD Balance Point
										<label class="input">
											<input type="text" name="addhcbp" id="addhcbp" placeholder="HDD CDD Balance Point" value="<?php echo $Hdd_Cdd_Balance_Point; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Wban
										<label class="input">
											<input type="number" name="addwban" id="addwban" placeholder="Wban" value="<?php echo $Wban; ?>">
										</label>
									</section>
									<section class="col col-6">Weather Station Name
										<label class="input">
											<input type="text" name="addwstationname" id="addwstationname" placeholder="Weather Station Name" value="<?php echo $Weather_Station_Name; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Weather Station City
										<label class="input">
											<input type="text" name="addwscity" id="addwscity" placeholder="Weather Station City" value="<?php echo $Weather_Station_City; ?>">
											<input type="hidden" name="editsite" id="editsite" value="edit">
											<input type="hidden" name="sid" id="sid" value="<?php echo $id; ?>">
										</label>
									</section>
									<section class="col col-6">Site Status
										<label class="select">
											<select name="addsstatus" id="addsstatus">
												<option value="">Select Site Status</option>
												<option value="Active"  <?php if($Site_Status=="Active"){echo "Selected";} ?>>Active</option>
												<option value="Inactive"  <?php if($Site_Status=="Inactive"){echo "Selected";} ?>>Inactive</option>
											</select>
									</section>
								</div>

							<footer>
								<button type="submit" class="btn btn-primary" id="edit-site-submit">
									Submit
								</button>
								<button type="button" class="btn" id="edit-site-cancel">
									Cancel
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

		$("#edit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Site</h4></div>",
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

		});

		$('#edit-site-cancel').click(function() {;
			$("#edit-dialog-message").dialog("close");
			$("#edit-dialog-message").remove();
			$("#response").html("");
		});



		var $checkoutForm = $('#edit-checkout-form').validate({
		// Rules for form validation
			rules : {
				addcompany : {
					required : true
				},
				addsname : {
					required : true
				},
				addphone : {
					required : true
				},
				addsnumber : {
					required : true
				},
				adddivision : {
					required : true
				},
				addsadd1 : {
					required : true
				},
				addcity : {
					required : true
				},
				addstate : {
					required : true
				},
				addzip : {
					required : true
				},
				addcountry : {
					required : true
				},
				addsstatus : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				addcompany : {
					required : 'Please enter your company name'
				},
				addsname : {
					required : 'Please enter site name'
				},
				addsnumber : {
					required : 'Please enter site number'
				},
				addphone : {
					required : 'Please enter phone'
				},
				adddivision : {
					required : 'Please enter division'
				},
				addcity : {
					required : 'Please enter city'
				},
				addstate : {
					required : 'Please enter state'
				},
				addzip : {
					required : 'Please enter postal code'
				},
				addcountry : {
					required : 'Please enter country'
				},
				addsstatus : {
					required : 'Select site status'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('sid', $("#sid").val());
				formData.append('company', $("#addcompany").val());
				formData.append('sname', $("#addsname").val());
				formData.append('sitenumber', $("#addsnumber").val());
				formData.append('division', $("#adddivision").val());
				formData.append('phone', $("#addphone").val());
				formData.append('activedate', $("#addadate").val());
				formData.append('inactivedate', $("#addinadate").val());
				formData.append('siteadd1', $("#addsadd1").val());
				formData.append('siteadd2', $("#addsadd2").val());
				formData.append('siteadd3', $("#addsadd3").val());

				formData.append('city', $("#addcity").val());
				formData.append('state', $("#addstate").val());
				formData.append('zip', $("#addzip").val());
				formData.append('country', $("#addcountry").val());
				formData.append('sitetype', $("#addsitetype").val());
				formData.append('sqfootage', $("#addsqfootage").val());
				formData.append('nofloor', $("#addnofloor").val());
				formData.append('nounits', $("#addnounits").val());
				formData.append('region', $("#addregion").val());
				formData.append('naics', $("#addnaics").val());
				formData.append('sic', $("#addsic").val());
				formData.append('managed', $("#addmanaged").val());
				formData.append('altname', $("#addaltname").val());
				formData.append('ownership', $("#addownership").val());
				formData.append('yearbuilt', $("#addybuilt").val());
				formData.append('noemp', $("#addnoemp").val());

				formData.append('wohours', $("#addwohours").val());
				formData.append('hcbp', $("#addhcbp").val());
				formData.append('wban', $("#addwban").val());
				formData.append('wstationname', $("#addwstationname").val());
				formData.append('wscity', $("#addwscity").val());
				formData.append('sstatus', $("#addsstatus").val());

				formData.append('sedit', $("#editsite").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/sitesedit.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								alert("Success");
								$("#edit-dialog-message").dialog("close");
								parent.$("#list-sites").html('');
								$("#edit-dialog-message").remove();
								parent.$('#list-sites').load('assets/ajax/list-sitestesting.php?load=true');
							}else
								alert(results.error);
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

	function profileSite(){
		//return false;
	}
</script>




<?php
		}else
			die();
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}//else
		//die("Error Occured! Please try after sometime.");
}elseif(isset($_GET["load"])){
	$subquery=((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?"?showdemo=1":"");


   // company names
   $company_editor_json= "";
   $company_arr = [];
   $stmt = $mysqli->prepare('SELECT company_id,company_name FROM company ORDER BY company_name');

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$company);
			while($stmt->fetch()){
				$company_editor_json .= '{ label: "'.$company.'", value: "'.$id.'" },';
				$company_arr[$id] = $company;
			}
		}

?>

	<!--<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css" />-->
	<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables_ar/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />

<style>
table.dataTable.dt-checkboxes-select tbody tr,
table.dataTable thead th.dt-checkboxes-select-all,
table.dataTable tbody td.dt-checkboxes-cell {
  cursor: pointer;
}

table.dataTable thead th.dt-checkboxes-select-all,
table.dataTable tbody td.dt-checkboxes-cell {
  text-align: center;
}

div.dataTables_wrapper span.select-info,
div.dataTables_wrapper span.select-item {
  margin-left: 0.5em;
}

@media screen and (max-width: 640px) {
  div.dataTables_wrapper span.select-info,
  div.dataTables_wrapper span.select-item {
    margin-left: 0;
    display: block;
  }
}
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
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_column .sssdrp{width:auto !important;}
#datatable_fixed_column .sssdrp {
    font-weight: 400 !important;
}
.
.flleft{float:left !important;}

.DTED_Lightbox_Background{z-index:905 !important;}
.DTED_Lightbox_Wrapper{z-index:906 !important;}

	div.dataTables_filter label {float: left;}

	.show_deleted,.hide_deleted{margin-left:15px;}

	#undo_delete_filter {
		float: left;
		width: auto !important;
		margin: 1% 1% !important;
	}
	#undo_delete_length {
		float: right !important;
		margin: 1% 1% !important;
	}
	#undo_delete{border-bottom: 1px solid #ccc !important;}

#undo_delete_wrapper,#undo_delete{/*display:none;*/}
#datatable_fixed_column table.dataTable tr.selected td.select-checkbox:after,#datatable_fixed_column table.dataTable tr.selected th.select-checkbox:after {
    margin-top: -25px !important;
    margin-left: -5px !important;
}
</style>

<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>
	
	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>
	
<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%" data-turbolinks="false">
	<thead>
		<tr class="dropdown">
			<th class="hasinput">

			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Company" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Site Number" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Site Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Address" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter City" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter State" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Postal Code" />
			</th>
			<th class="hasinput">
				<select class="form-control sssdrp" id="sssdrp">
					<option value="all">Filter Status</option>
					<option value="Active" SELECTED>Active</option>
					<option value="Inactive">Inactive</option>
				</select>
			</th>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
			<!--<th class="hasinput"></th>-->
<?php } ?>
			<th class="hasinput">

			</th>
		</tr>
		<tr>
			<th data-hide="phone,tablet"></th>
			<th data-hide="phone,tablet">Company</th>
			<th data-hide="phone,tablet">Site Number</th>
			<th data-hide="phone,tablet">Site Name</th>
			<th data-hide="phone,tablet">Address</th>
			<th data-hide="phone,tablet">City</th>
			<th data-hide="phone,tablet">State</th>
			<th data-hide="phone,tablet">Postal Code</th>
			<th data-hide="phone,tablet">Site Status</th>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
			<!--<th data-hide="phone,tablet">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>-->
<?php } ?>
			<th data-hide="phone,tablet"></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>


<?php if(1==1 || $_SESSION["group_id"] == 1) {?>
<!----------------------------undo delete---------------------------->

								<table id="undo_delete" class="table table-striped table-bordered table-hover hidden" width="100%">

								<thead>
									<tr class="dropdown">
										<th class="hasinput">

										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Company" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Site Number" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Site Name" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Address" />
										</th>
										<th class="hasinput">

										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Date" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter City" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter State" />
										</th>
										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Postal Code" />
										</th>
										<th class="hasinput">
											<select class="form-control sssdrp" id="sssdrpdel">
												<option value="all">Filter Status</option>
												<option value="Active" SELECTED>Active</option>
												<option value="Inactive">Inactive</option>
											</select>
										</th>
										<th class="hasinput">

										</th>
									</tr>
									<tr>
										<th data-hide="phone,tablet"></th>
										<th data-hide="phone,tablet">Company</th>
										<th data-hide="phone,tablet">Site Number</th>
										<th data-hide="phone,tablet">Site Name</th>
										<th data-hide="phone,tablet">Address</th>
										<th data-hide="phone,tablet">Delete Status</th>
										<th data-hide="phone,tablet">Delete Date</th>
										<th data-hide="phone,tablet">City</th>
										<th data-hide="phone,tablet">State</th>
										<th data-hide="phone,tablet">Postal Code</th>
										<th data-hide="phone,tablet">Site Status</th>
										<th data-hide="phone,tablet"></th>

									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>

<?php } ?>


<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();
	/*
	var company_arr_js = <?php echo json_encode( $company_arr ); ?>;
	*/
	// pagefunction
	var pagefunction = function() {

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




		var editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/sites-save.php',
					table: '#datatable_fixed_column',

					//idSrc:  'site_number',

					fields: [
					{
						label: "Company Name:",
						name:  "company_id",
						editField: "company_id",
						type:  "select",
						options: [
							<?php echo $company_editor_json;?>
						]
					},
					/*
						{
							"label": "Company Name:",
							"name": "company_name"
						},
					*/
						{
							"label": "Site Number:",
							"name": "site_number",

						},

						{
							"label": "Site Name:",
							"name": "site_name",
						},
						{
							"label": "Service Address:",
							"name": "service_address1"
						},
						{
							"label": "City:",
							"name": "city",

						},
						{
							"label": "State:",
							"name": "state"
						},
						{
							"label": "Postal Code:",
							"name": "postal_code"
						},
						{
							"label": "Site Status:",
							"name": "site_status",
							type:  "select",
							options: [
								{ label: "Active", value: "Active" },
								{ label: "Inactive", value: "Inactive" },
							],
							
							def: function ( d ) {
								 console.log(d);
								 
								 /*
                                var selected = table.row( { selected: true } );
 
                                if ( selected.any() ) {
                                    d.s_status = selected.data().site_status;
									console.log('before return');
									console.log(d.s_status);
                                    return d.s_status;
                                }
								*/
								
                            }
						},


					]
				} );
				
				//-------------------------------------------------------
				//editor.on('open', function(e) {

					//console.log('open 11');
					//console.log(this);
					//console.log(e.target.s.includeFields[7]); // site status

					//var fldname = e.target.s.includeFields[7];
					//input_val = editor.field(fldname).val();
					
					//alert(input_val);
					//console.log(input_val);

					//editor.field(fldname).val('Active');
					//editor.field(fldname).val('Active');
					
					//setTimeout(function() {
						//var fldname = e.target.s.includeFields[7]; // site_status
						//alert(fldname);
						//console.log(editor);
						//input_val = editor.field(fldname).val();
						//input_val = $('#DTE_Field_site_status').val();
						//$('#DTE_Field_site_status').val('Inactive').trigger("change");
						//alert(input_val);
						//editor.field(fldname).val("'"+input_val+"'");
						//ditor.field(fldname).val('Active');
						//alert($('#DTE_Field_site_status').val());
						//alert($('#DTE_Field_site_status').val());
					//}, 2);
					
					//$("#DTE_Field_site_status option[value='Inactive']").attr("selected", "selected");
					//document.querySelector("#DTE_Field_site_status option[value='Inactive']").setAttribute('selected',true);
					//exit();

					//editor.field(fldname).val(input_val).change();
					
					//editor.field(fldname).set(input_val);

					/*
					var inner_div = document.createElement("div");
					inner_div.innerHTML = input_val;
					var inner_text = inner_div.textContent || inner_div.innerText || "";

					editor.field(fldname).set(inner_text.trim());
					*/

					//console.log('open 22');
					/*
					editor.show(); //Shows all fields
					editor.hide('ID');
					editor.hide('Field_Name_1');
					*/
				//});
				//-------------------------------------------------------------

		var fixNewLine = {
				exportOptions: {
						columns: [':not(:first-child):visible'],
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
	    var otable = $('#datatable_fixed_column').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 100,
			"processing": false,
			"serverSide": true,
		"dom": 'Blfrtip',
		 "search": {
            "caseInsensitive": false
        },
		
		"drawCallback" : function(settings) {
			 $(".dots-cont").hide();
		},					
		"preDrawCallback": function (settings) {
			$(".dots-cont").show();
		},
		//rowId: 2,
		/*
		"columnDefs": [ {
            "orderable": false,
            "className": 'select-checkbox',
            "targets":   0
        } ],
		*/

		'columns': [

			 {
				data: null,
				defaultContent: '',
				className: 'select-checkbox',
				orderable: false,
				searchable: false,
			 },

			 //null,
			 {data:'company_name'},
			 {data:'SiteNumber'},
			 {data:'SiteName'},
			 {data:'SiteAddress1'},
			 {data:'SiteCity'},
			 {data:'SiteState'},
			 {data:'SiteZip'},
			 {data:'SiteStatus'}, // dont change order of these columns and site_status should be on index 8 see in server_processing page
			 //{data: "company_id", visible:false }
			 //{data:'active_date'},
			 {data:'company_id'},

		],
		columnDefs: [
		  { targets: 9, visible: false }
		],

        "select": {
            "style":    'os',
            "selector": 'td:first-child'
        },
        "order": [[ 1, 'asc' ]],

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
				'extend': 'colvis',
				'columns': [1,2,3,4,5,6,7,8]
			},
			
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) ) { ?>
			{ 'extend': 'create', editor: editor },
			{ 'extend': 'edit',   editor: editor },
			{ 'extend': 'remove', editor: editor }
			<?php } ?>
			
        ],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": ""
        },
			"autoWidth" : true,
			"deferRender": true,
			"ajax": "assets/ajax/server_processingtesting.php<?php echo $subquery; ?>",
			'serverMethod': 'post',
	    });


		//--------------------------------------------------------------------
				// Activate an inline edit on click of a table cell
				///$('#datatable_fixed_column').on( 'click', 'tbody td:not(:first-child)', function (e) {
				$('#datatable_fixed_column').on( 'click', 'tbody td:not(:first-child):not(:nth-child(3))', function (e) {


					var tdtag = $(this);
					console.log(e.target.nodeName);
					//if(this.tagName != 'td') {
					//if($(this).prop('tagName') == 'i') {
					if (e.target.nodeName == 'A' || e.target.nodeName == 'I') {
						///console.log('in if');
						return;
						//editor.inline( this );

					} else {
						editor.inline( this, {
							scope: 'cell'
						} );
					}


					////editor.inline( this );

				} );
				// Edit record
				//$('.buttons-edit').click(function (e) {
				$("#datatable_fixed_column_wrapper").on("click", ".buttons-edit", function (e) {
					//alert('this');
					//e.preventDefault();
					//console.log('edit');
					//editor.disable( ['postalcode', 'country', 'state'] );

					/*
					editor.edit( otable.row({ selected: true }).index(), {
						//title: 'Edit record',
						//buttons: 'Update'
					} );
					*/

					//var idx = otable.cell('.selected', 1).index();
					//var data = otable.rows( idx.row ).data();
					//console.log( data );


					var co_str = otable.rows( { selected: true } ).data()[0]['company_id'];
					//var co_id = $($.parseHTML(co_str)).filter('a:eq(0)').text().trim();
					var co_id = $($.parseHTML(co_str)).text().trim();
					//alert(co_id);

					$('#DTE_Field_company_id').val(co_id);

					var ar_status_str = otable.rows( { selected: true } ).data()[0]['site_status'];
					//var ar_status = $($.parseHTML(ar_status_str)).filter('a:eq(0)').text().trim();
					var ar_status = $($.parseHTML(ar_status_str)).text().trim();

					$('#DTE_Field_site_status').val(ar_status);

					var all_inputs = $(".DTED_Lightbox_Wrapper .DTE_Action_Edit .DTE_Body_Content form .DTE_Field input");
					all_inputs.each(function(i, obj) {
						var input_val = $(this).val();
						var inner_div = document.createElement("div");
						inner_div.innerHTML = input_val;
						var inner_text = inner_div.textContent || inner_div.innerText || "";

						$(this).val(inner_text.trim());
					});

					//var co_id = co_obj.find('a:eq(0)').text();
					//var co_id = co_obj.filter('a:eq:0').text();

					//var co_id = co_obj.text();
					/*
					var co_name =  $('#datatable_fixed_column');
					//.find("td:eq(1)").text();
					*/
					//console.log(ar_status);

					/*
					var all_inputs = $(".DTED_Lightbox_Wrapper .DTE_Action_Edit .DTE_Body_Content form .DTE_Field input");
					all_inputs.each(function(i, obj) {
						var input_val = $(this).val();
						var inner_div = document.createElement("div");
						inner_div.innerHTML = input_val;
						var inner_text = inner_div.textContent || inner_div.innerText || "";

						$(this).val(inner_text.trim());
					});
					*/
					//var input_val = $(this).find('input').val();



					//console.log('111');
					//console.log( otable.row({ selected: true }) );



				} );

				editor.on('open', function(e) {

					var fldname = e.target.s.includeFields[0];
					input_val = editor.field(fldname).val();

					var inner_div = document.createElement("div");
					inner_div.innerHTML = input_val;
					var inner_text = inner_div.textContent || inner_div.innerText || "";

					editor.field(fldname).set(inner_text.trim());

				});

				editor.on( 'preSubmit', function ( e, o, a ) {
					if (a == 'remove') {
						o.action = "edit"; // Change action from delete to edit

						// Loop through selected records and set deleted value
						for (var key in o.data) {
							if (o.data.hasOwnProperty(key)) {
								o.data[key].deleted = 1;
								o.data[key].delete_date = 1;
								o.data[key].delete_by = 1;
							}
						}
					}
				} );


				<?php if(1==1 || $_SESSION["group_id"] == 1) {?>
			//----------------------------datatable for undo delete--------------------------
			//-------------------------------------------------------------------------------
			//var undo_table;
			//$(document).ready(function (){

				//----------------editor---------------

			var undo_editor = new $.fn.dataTable.Editor( {
					ajax: 'assets/ajax/sites-undo-save-testing.php',
					//ajax: "assets/ajax/sites-deletedtesting.php",
					table: '#undo_delete',

				} );


				//--------------------------------------------------------------
				
				var undo_table;
				
				//undo_table = $('#undo_delete').DataTable();
				
				function undo_delete_datatable() {
					//$('#undo_delete').DataTable().destroy();
					//alert('undo_delete_datatable');
					undo_table = $('#undo_delete').DataTable({
						"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
							"pageLength": 100,
							"processing": false,
							
							"drawCallback" : function(settings) {
								 $(".dots-cont").hide();
							},					
							"preDrawCallback": function (settings) {
								$(".dots-cont").show();
							},
		
							"serverSide": true,
						"dom": 'Blfrtip',
						 "search": {
							"caseInsensitive": false
						},

						'columns': [

							 {
								data: null,
								defaultContent: '',
								className: 'select-checkbox',
								orderable: false,
								searchable: false,
							 },

							 //null,
							 {data:'company_name'},
							 {data:'SiteNumber'},
							 {data:'SiteName'},
							 {data:'SiteAddress1'},
							 {data:'DeleteStatus'},
							 {data:'DeletedDate'},
							 {data:'SiteCity'},
							 {data:'SiteState'},
							 {data:'SiteZip'},
							 {data:'SiteStatus'},
							 //{data: 'company_id', visible:false },

							 //{data:'active_date'},

						],
						/*
						"select": {
							"style":    'os',
							"selector": 'td:first-child'
						},
						"order": [[ 1, 'asc' ]],
						*/
						"buttons": [

							{ 'extend': 'remove', 'text': 'Undo Delete', editor: undo_editor

							,
								formMessage: function ( e, dt ) {
									var rows = undo_table.rows( {selected: true} ).indexes();

									return rows.length === 1 ?
									'Are you sure you wish to undo this record?' :
									'Are you sure you wish to undo these '+rows.length+' records'
								},
								formButtons: [
									'Undelete',
								]
							},

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
								'extend': 'colvis',
								'columns': [1,2,3,4,5,6,7,8]
								//exclude: [ 1 ]
							},

						],
						"language": {
							"lengthMenu": "Show _MENU_ entries",
							"zeroRecords": "No matching records found",
							"info": "Showing _START_ to _END_ of _TOTAL_ entries",
							"infoEmpty": "Showing 0 to 0 of 0 entries",
							"infoFiltered": ""
						},
							"autoWidth" : true,
							//"ajax": "assets/ajax/sites-deletedtesting.php",
							"ajax": "assets/ajax/sites-deletedtesting.php",
							'serverMethod': 'post',
						  'select': {
							 'style': 'multi'
						  },
						  'order': [[1, 'asc']]


					   });
					   
				} // undo_delete_datatable


					   undo_editor.on( 'preSubmit', function ( e, o, a ) {
							if (a == 'remove') {
								o.action = "edit"; // Change action from delete to edit

								// Loop through selected records and set deleted value
								for (var key in o.data) {
									if (o.data.hasOwnProperty(key)) {
										o.data[key].deleted = 0;
									}
								}
							}
						} );

						undo_editor.on( 'submitSuccess', function ( e, o, a ) {
							otable.ajax.reload();
						} );

						editor.on( 'submitSuccess', function ( e, o, a ) {
							undo_table.ajax.reload();
						} );


						//----deleted records --------------
						// Apply the filter
							$("#undo_delete .sdrp").on( 'keyup change', function () {
								undo_table
									.column( $(this).parent().index()+':visible' )
									.search( this.value )
									.draw();

								if ($(this).hasClass('dd_country')) {
									var val = this.value;
									if (!val) {
										val = 'all';
									}
									getState(val);
								}

							} );

							//undo_table.columns( [1,13,14,15,16,17,18,19,20,21,22,23,24] ).visible( false );


						// custom toolbar
						///$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

						// Apply the filter
						$(document.body).on('keyup change', '#undo_delete thead th input[type=text]' ,function(){

							//console.log('here');
							//console.log($(this).parent().index());

							undo_table
								.column( $(this).parent().index()+':visible' )
								//.column( $(this).parent().index() )
								.search( this.value )
								.draw();

						});


						<?php } ?>


					//add deleted record button ei_datatable_fixed_column_filter
					//$('<button class="show_deleted  dt-button buttons-html5 dt-buttons">Show Deleted</button>').appendTo('#ei_datatable_fixed_column_filter');
					$('#datatable_fixed_column_filter').append('<button class="show_deleted">Show Deleted</button>');
					//$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');



			//});

			$('.show_deleted').click( function() {
				
				if ( !$.fn.dataTable.isDataTable( '#undo_delete' ) ) {
					undo_delete_datatable();
				}
				
				////$("#datatable_fixed_column_wrapper").css("display","none");
				////$('#undo_delete_wrapper, #undo_delete').css("display","block");
				$("#datatable_fixed_column_wrapper").addClass("hidden");
				$('#undo_delete_wrapper, #undo_delete').removeClass("hidden");
				
				$('.hide_deleted').remove();
				$('#undo_delete_filter').append('<button class="hide_deleted">Hide Deleted</button>');
				
				
			});

			//$('.hide_deleted').click( function() {
			$(document.body).on('click', '.hide_deleted' ,function(){
				//alert('1');
				////$('#datatable_fixed_column_wrapper').css("display","block");
				////$('#undo_delete_wrapper, #undo_delete').css("display","none");
				$("#datatable_fixed_column_wrapper").removeClass("hidden");
				$('#undo_delete_wrapper, #undo_delete').addClass("hidden");
			});







<?php
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){
?>
		////otable.columns( [0] ).visible( false );
<?php } ?>
	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );

	    $("#datatable_fixed_column .sssdrp").on( 'keyup change', function () {
	        
			otable
				.column( $(this).parent().index()+':visible' )
	            //.search(this.value, true, true, false)
				  //.search(this.value, false, false, false)
				  .search(this.value, false, false, false)
	            //.search(this.value, false,true,false)
				//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();
			
				//otable.search( this.value, false, false, false ).draw();

	    } );
		
		$("#undo_delete .sssdrp").on( 'keyup change', function () {
	        undo_table
				.column( $(this).parent().index()+':visible' )
	            ////.search(this.value, true, true, false)
				.search(this.value, false, false, false)
	            //.search(this.value, false,true,false)
				//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();

	    } );


		/*
			 var searchactive =otable
				.column(7)
	            //.search("^Active$",  true, true, false)
	            .search("Active",  true, true, false)
	            //.search('Active', false,true,false)
				//.search( 'Active '.replace(/(<([^>]+)>)/ig,"") ? '^'+' Active'.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();
		*/

	    /* END COLUMN FILTER */
<?php if(1==2){ /*if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){*/?>
		$('#selectCompany').empty().append( multilist(2).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(3).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(4).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(5).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(6).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(9).join() );
		$("#selectStatus").multiselect();
<?php }else if(1==3){/*}else{*/ ?>
		$('#selectCompany').empty().append( multilist(1).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(2).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(3).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(4).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(5).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(8).join() );
		$("#selectStatus").multiselect();
<?php } ?>
		//$("#selectCompany").on( 'keyup change', function () {multifilter(this,"selectCompany",otable)});
		//$("#selectDivision").on( 'keyup change', function () {multifilter(this,"selectDivision",otable)});
		//$("#selectCountry").on( 'keyup change', function () {multifilter(this,"selectCountry",otable)});
		//$("#selectState").on( 'keyup change', function () {multifilter(this,"selectState",otable)});
		//$("#selectCity").on( 'keyup change', function () {multifilter(this,"selectCity",otable)});
		//$("#selectStatus").on( 'keyup change', function () {multifilter(this,"selectStatus",otable)});

		//$('.sssdrp option:eq(1)').prop('selected', true);
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

	/*loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});*/

	/*
	loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
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
	 loadScript("assets/js/dataTables.editor.min.js", function(){
		pagefunction();
		 });
	 });

<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadsite(sid) {
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/list-sitestesting.php?editsid=true&sid='+sid);
}

function deletesite(sid,sname) {
	$('#response').html('');
	var r = confirm("Do you want to delete Site: "+sname+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/sitesedit.inc.php',
			data: {sid:sid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#list-sites").html('');
						parent.$('#list-sites').load('assets/ajax/list-sitestesting.php?load=true');
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
$( document ).ready(function() {
		$('#sssdrp option[value="Active"]').attr('selected', 'selected');
});
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
