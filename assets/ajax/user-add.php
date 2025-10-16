<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(!isset($_SESSION['group_id']) or ($_SESSION['group_id'] != 1 and $_SESSION['group_id'] != 5))
	die("Restricted Access!");
?>
<style>
.chkbx{color:#666 !important;font-size:13px !important;}
.uadd footer{text-align:center;}
.uadd footer button{float:none !important;}
#addgeneratepwd{float:right;padding:2px;}
</style>
		<div id="add-dialog-message" title="Add Profile">
						<form id="add-checkout-form" class="smart-form uadd" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">First Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addfname" id="addfname" placeholder="First name" value="">
										</label>
									</section>
									<section class="col col-6">Last Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addlname" id="addlname" placeholder="Last name" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Title
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addtitle" id="addtitle" placeholder="Title" value="">
										</label>
									</section>
									<section class="col col-6">Address
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="addaddress" id="addaddress" placeholder="Address" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">City
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="addcity" id="addcity" placeholder="City" value="">
										</label>
									</section>
									<section class="col col-6">State
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="addstate" id="addstate" placeholder="State" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Country
										<label class="select"> <i class="icon-append fa fa-bank"></i>
<select name="addcountry" id="addcountry">
	<option value="AF">Afghanistan</option>
	<option value="AX">Åland Islands</option>
	<option value="AL">Albania</option>
	<option value="DZ">Algeria</option>
	<option value="AS">American Samoa</option>
	<option value="AD">Andorra</option>
	<option value="AO">Angola</option>
	<option value="AI">Anguilla</option>
	<option value="AQ">Antarctica</option>
	<option value="AG">Antigua and Barbuda</option>
	<option value="AR">Argentina</option>
	<option value="AM">Armenia</option>
	<option value="AW">Aruba</option>
	<option value="AU">Australia</option>
	<option value="AT">Austria</option>
	<option value="AZ">Azerbaijan</option>
	<option value="BS">Bahamas</option>
	<option value="BH">Bahrain</option>
	<option value="BD">Bangladesh</option>
	<option value="BB">Barbados</option>
	<option value="BY">Belarus</option>
	<option value="BE">Belgium</option>
	<option value="BZ">Belize</option>
	<option value="BJ">Benin</option>
	<option value="BM">Bermuda</option>
	<option value="BT">Bhutan</option>
	<option value="BO">Bolivia, Plurinational State of</option>
	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
	<option value="BA">Bosnia and Herzegovina</option>
	<option value="BW">Botswana</option>
	<option value="BV">Bouvet Island</option>
	<option value="BR">Brazil</option>
	<option value="IO">British Indian Ocean Territory</option>
	<option value="BN">Brunei Darussalam</option>
	<option value="BG">Bulgaria</option>
	<option value="BF">Burkina Faso</option>
	<option value="BI">Burundi</option>
	<option value="KH">Cambodia</option>
	<option value="CM">Cameroon</option>
	<option value="CA">Canada</option>
	<option value="CV">Cape Verde</option>
	<option value="KY">Cayman Islands</option>
	<option value="CF">Central African Republic</option>
	<option value="TD">Chad</option>
	<option value="CL">Chile</option>
	<option value="CN">China</option>
	<option value="CX">Christmas Island</option>
	<option value="CC">Cocos (Keeling) Islands</option>
	<option value="CO">Colombia</option>
	<option value="KM">Comoros</option>
	<option value="CG">Congo</option>
	<option value="CD">Congo, the Democratic Republic of the</option>
	<option value="CK">Cook Islands</option>
	<option value="CR">Costa Rica</option>
	<option value="CI">Côte d'Ivoire</option>
	<option value="HR">Croatia</option>
	<option value="CU">Cuba</option>
	<option value="CW">Curaçao</option>
	<option value="CY">Cyprus</option>
	<option value="CZ">Czech Republic</option>
	<option value="DK">Denmark</option>
	<option value="DJ">Djibouti</option>
	<option value="DM">Dominica</option>
	<option value="DO">Dominican Republic</option>
	<option value="EC">Ecuador</option>
	<option value="EG">Egypt</option>
	<option value="SV">El Salvador</option>
	<option value="GQ">Equatorial Guinea</option>
	<option value="ER">Eritrea</option>
	<option value="EE">Estonia</option>
	<option value="ET">Ethiopia</option>
	<option value="FK">Falkland Islands (Malvinas)</option>
	<option value="FO">Faroe Islands</option>
	<option value="FJ">Fiji</option>
	<option value="FI">Finland</option>
	<option value="FR">France</option>
	<option value="GF">French Guiana</option>
	<option value="PF">French Polynesia</option>
	<option value="TF">French Southern Territories</option>
	<option value="GA">Gabon</option>
	<option value="GM">Gambia</option>
	<option value="GE">Georgia</option>
	<option value="DE">Germany</option>
	<option value="GH">Ghana</option>
	<option value="GI">Gibraltar</option>
	<option value="GR">Greece</option>
	<option value="GL">Greenland</option>
	<option value="GD">Grenada</option>
	<option value="GP">Guadeloupe</option>
	<option value="GU">Guam</option>
	<option value="GT">Guatemala</option>
	<option value="GG">Guernsey</option>
	<option value="GN">Guinea</option>
	<option value="GW">Guinea-Bissau</option>
	<option value="GY">Guyana</option>
	<option value="HT">Haiti</option>
	<option value="HM">Heard Island and McDonald Islands</option>
	<option value="VA">Holy See (Vatican City State)</option>
	<option value="HN">Honduras</option>
	<option value="HK">Hong Kong</option>
	<option value="HU">Hungary</option>
	<option value="IS">Iceland</option>
	<option value="IN">India</option>
	<option value="ID">Indonesia</option>
	<option value="IR">Iran, Islamic Republic of</option>
	<option value="IQ">Iraq</option>
	<option value="IE">Ireland</option>
	<option value="IM">Isle of Man</option>
	<option value="IL">Israel</option>
	<option value="IT">Italy</option>
	<option value="JM">Jamaica</option>
	<option value="JP">Japan</option>
	<option value="JE">Jersey</option>
	<option value="JO">Jordan</option>
	<option value="KZ">Kazakhstan</option>
	<option value="KE">Kenya</option>
	<option value="KI">Kiribati</option>
	<option value="KP">Korea, Democratic People's Republic of</option>
	<option value="KR">Korea, Republic of</option>
	<option value="KW">Kuwait</option>
	<option value="KG">Kyrgyzstan</option>
	<option value="LA">Lao People's Democratic Republic</option>
	<option value="LV">Latvia</option>
	<option value="LB">Lebanon</option>
	<option value="LS">Lesotho</option>
	<option value="LR">Liberia</option>
	<option value="LY">Libya</option>
	<option value="LI">Liechtenstein</option>
	<option value="LT">Lithuania</option>
	<option value="LU">Luxembourg</option>
	<option value="MO">Macao</option>
	<option value="MK">Macedonia, the former Yugoslav Republic of</option>
	<option value="MG">Madagascar</option>
	<option value="MW">Malawi</option>
	<option value="MY">Malaysia</option>
	<option value="MV">Maldives</option>
	<option value="ML">Mali</option>
	<option value="MT">Malta</option>
	<option value="MH">Marshall Islands</option>
	<option value="MQ">Martinique</option>
	<option value="MR">Mauritania</option>
	<option value="MU">Mauritius</option>
	<option value="YT">Mayotte</option>
	<option value="MX">Mexico</option>
	<option value="FM">Micronesia, Federated States of</option>
	<option value="MD">Moldova, Republic of</option>
	<option value="MC">Monaco</option>
	<option value="MN">Mongolia</option>
	<option value="ME">Montenegro</option>
	<option value="MS">Montserrat</option>
	<option value="MA">Morocco</option>
	<option value="MZ">Mozambique</option>
	<option value="MM">Myanmar</option>
	<option value="NA">Namibia</option>
	<option value="NR">Nauru</option>
	<option value="NP">Nepal</option>
	<option value="NL">Netherlands</option>
	<option value="NC">New Caledonia</option>
	<option value="NZ">New Zealand</option>
	<option value="NI">Nicaragua</option>
	<option value="NE">Niger</option>
	<option value="NG">Nigeria</option>
	<option value="NU">Niue</option>
	<option value="NF">Norfolk Island</option>
	<option value="MP">Northern Mariana Islands</option>
	<option value="NO">Norway</option>
	<option value="OM">Oman</option>
	<option value="PK">Pakistan</option>
	<option value="PW">Palau</option>
	<option value="PS">Palestinian Territory, Occupied</option>
	<option value="PA">Panama</option>
	<option value="PG">Papua New Guinea</option>
	<option value="PY">Paraguay</option>
	<option value="PE">Peru</option>
	<option value="PH">Philippines</option>
	<option value="PN">Pitcairn</option>
	<option value="PL">Poland</option>
	<option value="PT">Portugal</option>
	<option value="PR">Puerto Rico</option>
	<option value="QA">Qatar</option>
	<option value="RE">Réunion</option>
	<option value="RO">Romania</option>
	<option value="RU">Russian Federation</option>
	<option value="RW">Rwanda</option>
	<option value="BL">Saint Barthélemy</option>
	<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
	<option value="KN">Saint Kitts and Nevis</option>
	<option value="LC">Saint Lucia</option>
	<option value="MF">Saint Martin (French part)</option>
	<option value="PM">Saint Pierre and Miquelon</option>
	<option value="VC">Saint Vincent and the Grenadines</option>
	<option value="WS">Samoa</option>
	<option value="SM">San Marino</option>
	<option value="ST">Sao Tome and Principe</option>
	<option value="SA">Saudi Arabia</option>
	<option value="SN">Senegal</option>
	<option value="RS">Serbia</option>
	<option value="SC">Seychelles</option>
	<option value="SL">Sierra Leone</option>
	<option value="SG">Singapore</option>
	<option value="SX">Sint Maarten (Dutch part)</option>
	<option value="SK">Slovakia</option>
	<option value="SI">Slovenia</option>
	<option value="SB">Solomon Islands</option>
	<option value="SO">Somalia</option>
	<option value="ZA">South Africa</option>
	<option value="GS">South Georgia and the South Sandwich Islands</option>
	<option value="SS">South Sudan</option>
	<option value="ES">Spain</option>
	<option value="LK">Sri Lanka</option>
	<option value="SD">Sudan</option>
	<option value="SR">Suriname</option>
	<option value="SJ">Svalbard and Jan Mayen</option>
	<option value="SZ">Swaziland</option>
	<option value="SE">Sweden</option>
	<option value="CH">Switzerland</option>
	<option value="SY">Syrian Arab Republic</option>
	<option value="TW">Taiwan, Province of China</option>
	<option value="TJ">Tajikistan</option>
	<option value="TZ">Tanzania, United Republic of</option>
	<option value="TH">Thailand</option>
	<option value="TL">Timor-Leste</option>
	<option value="TG">Togo</option>
	<option value="TK">Tokelau</option>
	<option value="TO">Tonga</option>
	<option value="TT">Trinidad and Tobago</option>
	<option value="TN">Tunisia</option>
	<option value="TR">Turkey</option>
	<option value="TM">Turkmenistan</option>
	<option value="TC">Turks and Caicos Islands</option>
	<option value="TV">Tuvalu</option>
	<option value="UG">Uganda</option>
	<option value="UA">Ukraine</option>
	<option value="AE">United Arab Emirates</option>
	<option value="GB">United Kingdom</option>
	<option value="US" selected>United States</option>
	<option value="UM">United States Minor Outlying Islands</option>
	<option value="UY">Uruguay</option>
	<option value="UZ">Uzbekistan</option>
	<option value="VU">Vanuatu</option>
	<option value="VE">Venezuela, Bolivarian Republic of</option>
	<option value="VN">Viet Nam</option>
	<option value="VG">Virgin Islands, British</option>
	<option value="VI">Virgin Islands, U.S.</option>
	<option value="WF">Wallis and Futuna</option>
	<option value="EH">Western Sahara</option>
	<option value="YE">Yemen</option>
	<option value="ZM">Zambia</option>
	<option value="ZW">Zimbabwe</option>
</select>
										</label>
									</section>
									<section class="col col-6">Zip
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="addzip" id="addzip" placeholder="Zip" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Phone
										<label class="input"> <i class="icon-prepend fa fa-phone"></i>
											<input type="tel" name="addphone" id="addphone" placeholder="Phone" data-mask="(999) 999-9999" value="">
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Mobile
										<label class="input"> <i class="icon-prepend fa fa-tablet"></i>
											<input type="tel" name="addmobile" id="addmobile" placeholder="Mobile" data-mask="(999) 999-9999" value="">
										</label>
									</section>
									<section class="col col-6">Fax
										<label class="input"> <i class="icon-prepend fa fa-fax"></i>
											<input type="tel" name="addfax" id="addfax" placeholder="Fax" data-mask="(999) 999-9999" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Email
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input type="email" name="addemail" id="addemail" placeholder="E-mail" type="email" value="">
										</label>
									</section>
									<section class="col col-6">Profile Image
										<label for="file" class="input input-file">
											<div class="button"><input type="file" name="addfile" id="addfile" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Profile Image" readonly="" value="" id="addfile-text">
										</label>
									</section>
								</div>
							</fieldset>
							<fieldset>
								<div class="row">
									<section class="col col-6">DataHub 360 Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="addpassword" placeholder="DataHub 360 Password" id="addpassword" autocomplete="off" minlength="8" maxlength="20" required value="">
										</label>
									</section>
									<section class="col col-6">DataHub 360 Confirm Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="addpasswordConfirm" id="addpasswordConfirm" placeholder="DataHub 360 Confirm password" autocomplete="off" minlength="8" maxlength="20" required value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6"></section>
									<section class="col col-6">
											<button type="button" class="btn btn-primary" id="addgeneratepwd">Generate Password!</button>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">CSR Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addaccuviouser" id="addaccuviouser" placeholder="CSR User" value="">
										</label>
									</section>
									<section class="col col-6">CSR Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="addaccuviopass" id="addaccuviopass" placeholder="CSR Pass" value="" autocomplete="off">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addcapturisuser" id="addcapturisuser" placeholder="UBM User" value="">
										</label>
									</section>
									<section class="col col-6">UBM Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="addcapturispass" id="addcapturispass" placeholder="UBM Pass" value="" autocomplete="off">
											<input type="hidden" name="addnew" id="addnew" placeholder="Title" value="new">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM Archive Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addcapturisarchiveuser" id="addcapturisarchiveuser" placeholder="UBM Archive User" value="">
										</label>
									</section>
									<section class="col col-6">UBM Archive Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="addcapturisarchivepass" id="addcapturisarchivepass" placeholder="UBM Archive Pass" value="" autocomplete="off">
										</label>
									</section>
								</div>
							</fieldset>
							<fieldset>
								<div class="row">
<?php if($_SESSION['group_id'] == 1){ ?>
									<section class="col col-6">Disable Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="adddisabledate" id="adddisabledate" placeholder="Disable Date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
<?php } ?>
									<section class="col col-6">Company logo
										<label for="companylogo" class="input input-file">
											<div class="button"><input type="file" name="addcompanylogo" id="addcompanylogo" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Company Logo" readonly="" value="" id="addcompany-logo">
										</label>
									</section>
<?php if($_SESSION['group_id'] == 1){ ?>
								</div>
							</fieldset>
							<fieldset>
								<section>Vervantis Notes
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" name="addnotes" id="addnotes" placeholder="Vervantis Notes"></textarea>
									</label>
								</section>
							</fieldset>
							<fieldset>
								<div class="row">
									<section class="col col-6">Gender
										<label class="select"> <i class="icon-append fa fa-male"></i>
											<select name="addgender" id="addgender">
												<option value="" selected="" disabled="">Gender</option>
												<option value="M">Male</option>
												<option value="F">Female</option>
												<!--<option value="3">Prefer not to answer</option>-->
											</select></label>
									</section>

									<section class="col col-6">User Groups
										<label class="select"> <i class="icon-append fa fa-male"></i>
											<select name="addusergroups" id="addusergroups">
												<option value="" selected="" disabled="">User Groups</option>
												<option value="1">Vervantis Administrator</option>
												<option value="2">Vervantis Employee</option>
												<option value="3">Client</option>
												<option value="4">Vendor</option>
												<option value="5">Client Administrator</option>
												<option value="6">Sub Contractors</option>
											</select></label>
									</section>
								</div>
								<div class="row">
<?php } ?>
									<section class="col col-6">Status
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="addstatus" id="addstatus">
												<option value="1" >Active</option>
												<option value="0" >Inactive</option>
												<option value="2" >Locked Out</option>
												<option value="3" >Password Change</option>
											</select>
										</label>
									</section>
<?php if($_SESSION['group_id'] == 1){ ?>
									<section class="col col-6">Company
											<label class="select"> <i class="icon-append fa fa-building"></i>
											<select name="addcompany" id="addcompany" placeholder="Company" class="">
												<option value="">Select Company</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company')){
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
<?php } ?>
								</div>
							</fieldset>

							<fieldset>
							  <section>
								<label class="label">Columned checkboxes</label>

								<div class="row">
								  <div class="col col-12">
									<label class="checkbox">
									  <input checked="checked" name="checkbox" type="checkbox" id="mailme">
									  <i></i>Send me this email address and password through mail</label>
								  </div>
								  <div class="col col-12">
									<label class="checkbox">
									  <input name="checkbox" type="checkbox" id="mailuser">
									  <i></i>Send new user this email address and password through mail</label>
								  </div>
								</div>
							  </section>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="add-profile-submit">
									Save
								</button>
								<button type="button" class="btn" id="add-profile-cancel">
									Close
								</button>
							</footer>
						</form>
	</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
$(document).ready(function() {
//parent.otable.ajax.reload();
//parent.$('#test-table-id').DataTable().ajax.reload();
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});

	jQuery.validator.addMethod("atleastonenumber", function(value, element) {
		return this.optional(element) || /\d{1}/.test(value);
	}, "Password must include at least one number!");

	jQuery.validator.addMethod("atleastoneletter", function(value, element) {
		return this.optional(element) || /[a-zA-Z]{1}/.test(value);
	}, "Password must include at least one letter!");

	jQuery.validator.addMethod("atleastonecapletter", function(value, element) {
		return this.optional(element) || /[A-Z]{1}/.test(value);
	}, "Password must include at least one CAPS!");

	jQuery.validator.addMethod("atleastonesymbol", function(value, element) {
		//return this.optional(element) || /[ !"#$%&'()*+,-.\/:;<=>?@[\]^_`{|}~]{1}/.test(value);
		return this.optional(element) || /\W{1}/.test(value);
	}, "Password must include at least one symbol!");

	jQuery.validator.addMethod("nospace", function(value, element) {
		return this.optional(element) || /^\S{1,}/.test(value);
	}, "Password must not contain spaces!");

	//$('#add-dialog-message').dialog('open'); .on('changeDate', function(e) {
       // $('#add-profileForm').formValidation('revalidateField', 'addbirthdate');
    //})
	$("#addgeneratepwd").click(function(){
		var newpwd=generatePassword(8);
		$("#addpassword").val(newpwd);
		$("#addpasswordConfirm").val(newpwd);
	});
});

function generatePassword(passwordLength) {
	var numberChars = "0123456789";
	var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var lowerChars = "abcdefghiklmnopqrstuvwxyz";
  //var specialChars = "!#$%&()*+,-./:;<=>?@[\]^_`{|}~";
	var specialChars = "#%&*+-";
	var allChars = numberChars + upperChars + lowerChars+specialChars;
	var randPasswordArray = Array(passwordLength);
  randPasswordArray[0] = numberChars;
  randPasswordArray[1] = upperChars;
  randPasswordArray[2] = lowerChars;
  randPasswordArray[3] = specialChars;
  randPasswordArray = randPasswordArray.fill(allChars, 4);
  return shuffleArray(randPasswordArray.map(function(x) { return x[Math.floor(Math.random() * x.length)] })).join('');
}

function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}



/*
$("#file").change(function() {
$("#message").empty(); // To remove the previous error message
var file = this.files[0];
var imagefile = file.type;
var match= ["image/jpeg","image/png","image/jpg"];
if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
{
$('#previewing').attr('src','noimage.png');
$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
return false;
}
});*/

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
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New User</h4></div>",
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
			close: function(event, ui) {
			        $(this).empty().dialog('destroy');
			    }
		});

		$('#add-profile-cancel').click(function() {
			$("#add-dialog-message").dialog("close");
		});



		var $checkoutForm = $('#add-checkout-form').validate({
		// Rules for form validation
			rules : {
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
<?php if($_SESSION['group_id'] == 1){ ?>
				addgender : {
					required : true
				},
				/*addbirthdate : {
					required : true
				},*/
				addcompany : {
					required : true
				},
<?php } ?>
				addpassword : {
					required : true,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true
				},
				addpasswordConfirm : {
					required : true,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true,
					equalTo : '#addpassword'
				}
<?php if($_SESSION['group_id'] == 1){ ?>
				,
				addusergroups : {
					required : true
				}
<?php } ?>
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
<?php if($_SESSION['group_id'] == 1){ ?>
				addgender : {
					required : 'Please enter your gender'
				},
				/*addbirthdate : {
					required : 'Select birthdate'
				},*/
				addcompany : {
					required : 'Select company'
				},
<?php } ?>
				addpassword : {
					required : 'Please enter your password'
				},
				addpasswordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as confirm password'
				}
<?php if($_SESSION['group_id'] == 1){ ?>
				,
				addusergroups : {
					required : 'Please select your usergroup'
				}
<?php } ?>
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();

				formData.append('fname', $("#addfname").val());
				formData.append('lname', $("#addlname").val());
				formData.append('title', $("#addtitle").val());
				formData.append('address', $("#addaddress").val());
				formData.append('city', $("#addcity").val());
				formData.append('state', $("#addstate").val());
				formData.append('zip', $("#addzip").val());
				formData.append('country', $("#addcountry").val());
				formData.append('email', $("#addemail").val());
				formData.append('phone', $("#addphone").val());
				formData.append('mobile', $("#addmobile").val());
				formData.append('fax', $("#addfax").val());
<?php if($_SESSION['group_id'] == 1){ ?>
				formData.append('gender', $("#addgender").val());
				formData.append('disabledate', $("#adddisabledate").val());
				formData.append('company', $("#addcompany").val());
<?php } ?>
				formData.append('password', $("#addpassword").val());
				formData.append('accuviouser', $("#addaccuviouser").val());
				formData.append('accuviopass', $("#addaccuviopass").val());
				formData.append('capturisuser', $("#addcapturisuser").val());
				formData.append('capturispass', $("#addcapturispass").val());
				formData.append('capturisarchiveuser', $("#addcapturisarchiveuser").val());
				formData.append('capturisarchivepass', $("#addcapturisarchivepass").val());
<?php if($_SESSION['group_id'] == 1){ ?>
				formData.append('usergroups', $("#addusergroups").val());
				formData.append('notes', $("#addnotes").val());
<?php } ?>
				formData.append('status', $("#addstatus").val());
				formData.append('p', ajaxformhash($("#addpassword").val()));
				formData.append('file', $("#addfile")[0].files[0]);
				formData.append('companylogo', $("#addcompanylogo")[0].files[0]);
				formData.append('new', $("#addnew").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/profileedit.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								$.smallBox({
									title : "Changes Saved!",
									content:"",
									color : "#296191",
									timeout: 2000
								}, function() {
									//alert("Success");
									$("#add-dialog-message").dialog("close");
									parent.$("#dtable").html('');
									parent.$('#dtable').load('assets/ajax/user-pedit.php');
								});
							}else{
								var tmperror="Please try after sometime...";
								if(results.error==200){
									tmperror="Email already exist.";
								}
								$.smallBox({
									title : "Error in request.",
									content : "<i class='fa fa-clock-o'></i> <i>"+tmperror+"</i>",
									color : "#FFA07A",
									iconSmall : "fa fa-warning shake animated",
									timeout : 4000
								});
							}
						}else{
							$.smallBox({
								title : "Error in request.",
								content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
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

	loadScript("assets/js/sha512.js", function(){
		loadScript("assets/js/forms.js", function(){
			loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
				loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction)
			});
		});
	});
	//loadScript("assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction);
	// end pagefunction

	// run pagefunction on load

	//pagefunction();
	$('#addcompanylogo').change( function() {
		var cfilename = $(this).val().replace(/C:\\fakepath\\/i, '');
		$('#addcompany-logo').val( cfilename );
	});
	$('#addfile').change( function() {
		var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
		$('#addfile-text').val( filename );
	});
	function profileAdd(){
	}
</script>
