<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");
?>
		<div id="add-dialog-message" title="Add Site">
						<form id="add-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" name="addsname" id="addsname" placeholder="Site name" value="">
										</label>
									</section>
									<section class="col col-6">Site Number
										<label class="input">
											<input type="number" name="addsnumber" id="addsnumber" placeholder="Site number" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Company Name
										<label class="select"> <i></i>
											<!--<input type="text" name="addcompany" id="addcompany" placeholder="Company" value="">-->
											<select name="addcompany" id="addcompany" placeholder="Company">
												<option value="">Select Company</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT id,company_name FROM company ORDER BY company_name')){ 
													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__company);
														while($stmt->fetch()){
															echo "<option value='".$__id."'>".$__company."</option>";
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
											<input type="text" name="adddivision" id="adddivision" placeholder="Division" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Active Date
										<label class="input">
											<input type="text" name="addadate" id="addadate" placeholder="Active Date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
									<section class="col col-6">Inactive Date
										<label class="input">
											<input type="text" name="addinadate" id="addinadate" placeholder="Inactive Date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Service Address1
										<label class="input">
											<input type="text" name="addsadd1" id="addsadd1" placeholder="Service Address1" value="">
										</label>
									</section>
									<section class="col col-6">Service Address2
										<label class="input">
											<input type="text" name="addsadd2" id="addsadd2" placeholder="Service Address2" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Service Address3
										<label class="input">
											<input type="text" name="addsadd3" id="addsadd3" placeholder="Service Address3" value="">
										</label>
									</section>
									<section class="col col-6">City
										<label class="input">
											<input type="text" name="addcity" id="addcity" placeholder="City" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">State
										<label class="select"> <i></i>
											<select name="addstate" id="addstate" placeholder="State">
												<option value="">Select State</option>
												<option value="AL">Alabama</option>
												<option value="AK">Alaska</option>
												<option value="AZ">Arizona</option>
												<option value="AR">Arkansas</option>
												<option value="CA">California</option>
												<option value="CO">Colorado</option>
												<option value="CT">Connecticut</option>
												<option value="DE">Delaware</option>
												<option value="DC">District Of Columbia</option>
												<option value="FL">Florida</option>
												<option value="GA">Georgia</option>
												<option value="HI">Hawaii</option>
												<option value="ID">Idaho</option>
												<option value="IL">Illinois</option>
												<option value="IN">Indiana</option>
												<option value="IA">Iowa</option>
												<option value="KS">Kansas</option>
												<option value="KY">Kentucky</option>
												<option value="LA">Louisiana</option>
												<option value="ME">Maine</option>
												<option value="MD">Maryland</option>
												<option value="MA">Massachusetts</option>
												<option value="MI">Michigan</option>
												<option value="MN">Minnesota</option>
												<option value="MS">Mississippi</option>
												<option value="MO">Missouri</option>
												<option value="MT">Montana</option>
												<option value="NE">Nebraska</option>
												<option value="NV">Nevada</option>
												<option value="NH">New Hampshire</option>
												<option value="NJ">New Jersey</option>
												<option value="NM">New Mexico</option>
												<option value="NY">New York</option>
												<option value="NC">North Carolina</option>
												<option value="ND">North Dakota</option>
												<option value="OH">Ohio</option>
												<option value="OK">Oklahoma</option>
												<option value="OR">Oregon</option>
												<option value="PA">Pennsylvania</option>
												<option value="RI">Rhode Island</option>
												<option value="SC">South Carolina</option>
												<option value="SD">South Dakota</option>
												<option value="TN">Tennessee</option>
												<option value="TX">Texas</option>
												<option value="UT">Utah</option>
												<option value="VT">Vermont</option>
												<option value="VA">Virginia</option>
												<option value="WA">Washington</option>
												<option value="WV">West Virginia</option>
												<option value="WI">Wisconsin</option>
												<option value="WY">Wyoming</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Postalcode
										<label class="input">
											<input type="text" name="addzip" id="addzip" placeholder="Postalcode" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Country
										<label class="select"> <i></i>
											<select name="addcountry" id="addcountry" placeholder="Country">
												<option value="">Select Country</option>
												<option value="US">US</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Site Type
										<label class="input">
											<input type="text" name="addsitetype" id="addsitetype" placeholder="Site Type" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Square Footage
										<label class="input">
											<input type="number" name="addsqfootage" id="addsqfootage" placeholder="Square Footage" value="">
										</label>
									</section>
									<section class="col col-6">Number Of Floors
										<label class="input">
											<input type="number" name="addnofloor" id="addnofloor" placeholder="Number Of Floors" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Number Of Units
										<label class="input">
											<input type="text" name="addnounits" id="addnounits" placeholder="Number Of Units" value="">
										</label>
									</section>
									<section class="col col-6">Region
										<label class="input">
											<input type="text" name="addregion" id="addregion" placeholder="Region" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Naics
										<label class="input">
											<input type="text" name="addnaics" id="addnaics" placeholder="Naics" value="">
										</label>
									</section>
									<section class="col col-6">Sic
										<label class="input">
											<input type="text" name="addsic" id="addsic" placeholder="Sic" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Managed
										<label class="input">
											<input type="text" name="addmanaged" id="addmanaged" placeholder="Managed" value="">
										</label>
									</section>
									<section class="col col-6">Alternate Name
										<label class="input">
											<input type="text" name="addaltname" id="addaltname" placeholder="Alternate Name" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Ownership
										<label class="input">
											<input type="text" name="addownership" id="addownership" placeholder="Ownership" value="">
										</label>
									</section>
									<section class="col col-6">Year Built
										<label class="select">
											<select name="addybuilt" id="addybuilt">
												<option value="">Select Year Built</option>
												<?php
													for($i=1950;$i<=(int)date("Y");$i++)
														echo '<option value="'.$i.'">'.$i.'</option>';
												?>
											</select>
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Number Of Employees
										<label class="input">
											<input type="number" name="addnoemp" id="addnoemp" placeholder="Number Of Employees" value="">
										</label>
									</section>
									<section class="col col-6">Weekly Operating Hours
										<label class="input">
											<input type="number" name="addwohours" id="addwohours" placeholder="Weekly Operating Hours" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Phone
										<label class="input">
											<input type="tel" name="addphone" id="addphone" placeholder="Phone" data-mask="(999) 999-9999" value="">
										</label>
									</section>
									<section class="col col-6">HDD CDD Balance Point
										<label class="input">
											<input type="text" name="addhcbp" id="addhcbp" placeholder="HDD CDD Balance Point" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Wban
										<label class="input">
											<input type="number" name="addwban" id="addwban" placeholder="Wban" value="">
										</label>
									</section>
									<section class="col col-6">Weather Station Name
										<label class="input">
											<input type="text" name="addwstationname" id="addwstationname" placeholder="Weather Station Name" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Weather Station City
										<label class="input">
											<input type="text" name="addwscity" id="addwscity" placeholder="Weather Station City" value="">
											<input type="hidden" name="addnew" id="addnew" value="new">
										</label>
									</section>
									<section class="col col-6">Site Status
										<label class="select">
											<select name="addsstatus" id="addsstatus">
												<option value="">Select Site Status</option>
												<option value="Active">Active</option>
												<option value="Inactive">Inactive</option>
											</select>
									</section>
								</div>

							<footer>
								<button type="submit" class="btn btn-primary" id="add-profile-submit">
									Submit
								</button>
								<button type="button" class="btn" id="add-profile-cancel">
									Cancel
								</button>
							</footer>
						</form>
	</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="<?php echo ASSETS_URL; ?>/assets/js/sha512.js"></script> 
<script type="text/JavaScript" src="<?php echo ASSETS_URL; ?>/assets/js/forms.js"></script>
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
	
		$("#add-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New Sites</h4></div>",
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

		$('#add-profile-cancel').click(function() {
			$("#add-dialog-message").dialog("close");
			$("#add-dialog-message").remove();
			$("#response").html("");
		});

		
		
		var $checkoutForm = $('#add-checkout-form').validate({
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
				
				formData.append('snew', $("#addnew").val());

				$.ajax({
					type: 'post',
					url: '<?php echo ASSETS_URL; ?>/assets/includes/sitesedit.inc.php',
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
								$("#add-dialog-message").dialog("close");
								$("#add-dialog-message").remove();
								$("#response").html("");
								parent.$("#list-sites").html('');
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
	
	loadScript("<?php echo ASSETS_URL; ?>/assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);
	//loadScript("assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction);	
	// end pagefunction
	
	// run pagefunction on load

	//pagefunction();
	
	function profileAdd(){
	}
</script>