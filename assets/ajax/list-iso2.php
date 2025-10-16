<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

if(isset($_GET['sid']) and @trim($_GET['sid']) != "" and @trim($_GET['sid']) != 0 and isset($_GET['editsid']))
{
	$sid=@trim($_GET['sid']);
	if ($stmt = $mysqli->prepare('SELECT s.site_number,s.company_id,s.division,s.active_date,s.inactive_date,s.country,s.state,s.city,s.site_number,s.site_name,s.service_address1,s.service_address2,s.service_address3,s.site_type,s.postal_code,s.square_footage,s.number_of_floors,s.number_of_units,s.region,s.naics,s.sic,s.managed,s.alternate_name,s.ownership,s.year_built,s.number_of_employees,s.weekly_operating_hours,s.phone,s.hdd_cdd_balance_point,s.wban,s.weather_station_name,s.weather_station_city,s.site_status FROM sites s,company c, user up WHERE s.site_number="'.$sid.'" and s.company_id=c.company_id and up.company_id=c.company_id LIMIT 1')) {

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
								parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');
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
	}else
		die("Error Occured! Please try after sometime.");
}elseif(isset($_GET["load"]) and $_GET["load"]=="true"){
?>
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
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
#datatable_fixed_column .isodrp{width:auto !important;}
</style>
<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr id="multiselect" style="display:none;">
			<th class="hasinput">
				<select id="selectCompany" name="selectISO[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectDivision" name="selectNODEID[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectCountry" name="selectOPRDT[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectState" name="selectOPRHR[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectCity" name="selectLMP[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectCity" name="selectMCC[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectCity" name="selectMCE[]" multiple="multiple"></select>
			</th>
			<th class="hasinput">
				<select id="selectCity" name="selectMCL[]" multiple="multiple"></select>
			</th>
		</tr>
		<tr class="dropdown">
			<th class="hasinput d-1">
				<select class="form-control isodrp">
					<option value="">Filter ISO</option>
					<option value="CAISO">CAISO</option>
					<option value="ERCOT">ERCOT</option>
					<option value="MISO">MISO</option>
					<option value="NE">NE</option>
					<option value="NY">NY</option>
					<option value="PJM">PJM</option>
				</select>
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter NODE ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter OPR DT" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter OPR HR" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter LMP" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Site MCC" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Site MCE" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Site MCL" />
			</th>
		</tr>
		<tr>
			<th data-hide="expand">ISO</th>
			<th>NODE ID</th>
			<th data-hide="phone">OPR DT</th>
			<th data-hide="phone,tablet">OPR HR</th>
			<th data-hide="phone,tablet">LMP</th>
			<th data-hide="phone,tablet">MCC</th>
			<th data-hide="phone,tablet">MCE</th>
			<th data-hide="phone,tablet">MCL</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();

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

		/* COLUMN FILTER  */
	    var otable = $('#datatable_fixed_column').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 100,
			"processing": true,
			"serverSide": true,
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
			"autoWidth" : true,
			"ajax": "assets/ajax/iso_processing.php"/*,
			initComplete: function () {
				this.api().columns([0]).every( function () {
					 var column = this;
					 var select = $('<select class="form-control"><option value="">Filter ISO</option></select>')
						  .appendTo( $('#datatable_fixed_column .dropdown .d-1').empty() )
						  .on( 'change', function () {
							   var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
							   );
						  column
							   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
							   .draw();
						  } );
						  var darr = [];
						 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
								if(jQuery.inArray(d, darr) == -1 && d != ""){
									select.append( '<option value="'+d+'">'+d+'</option>' );
									darr.push(d);
								}
						 } );
				} );
			}*/
	    });


	   /* var otable = $('#datatable_fixed_column').DataTable({
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
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $("#datatable_fixed_column .isodrp").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
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
		$("#selectCompany").on( 'keyup change', function () {multifilter(this,"selectCompany",otable)});
		$("#selectDivision").on( 'keyup change', function () {multifilter(this,"selectDivision",otable)});
		$("#selectCountry").on( 'keyup change', function () {multifilter(this,"selectCountry",otable)});
		$("#selectState").on( 'keyup change', function () {multifilter(this,"selectState",otable)});
		$("#selectCity").on( 'keyup change', function () {multifilter(this,"selectCity",otable)});
		$("#selectStatus").on( 'keyup change', function () {multifilter(this,"selectStatus",otable)});
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

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadsite(sid) {
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/list-sites.php?editsid=true&sid='+sid);
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
						parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');
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
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
