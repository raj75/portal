<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


//if(!isset($_SESSION['group_id']) or ($_SESSION['group_id'] != 1 and $_SESSION['group_id'] != 5))
if(!isset($_SESSION['group_id']) or $_SESSION['group_id'] != 1)
	die("Restricted Access. Please contact Vervantis Support (support@vervantis.com)!");

?>
<style>
.chkbx{color:#666 !important;font-size:13px !important;}
.uadd footer{text-align:center;}
.uadd footer button{float:none !important;}
#addgeneratepwd{float:right;padding:2px;}
.tcenter{text-align: center;}
.fnone{float:none !important;}
.cui,.uae{display:none;}
.dropdown-check-list {
  display: inline-block;
}

.dropdown-check-list .anchor {
  position: relative;
  cursor: pointer;
  display: inline-block;
  padding: 5px 50px 5px 10px;
  border: 1px solid #ccc;
}

.dropdown-check-list .anchor:after {
  position: absolute;
  content: "";
  border-left: 2px solid black;
  border-top: 2px solid black;
  padding: 5px;
  right: 10px;
  top: 20%;
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}

.dropdown-check-list .anchor:active:after {
  right: 8px;
  top: 21%;
}

.dropdown-check-list ul.items {
  padding: 2px;
  display: none;
  margin: 0;
  border: 1px solid #ccc;
  border-top: none;
}

.dropdown-check-list ul.items li {
  list-style: none;
}

.dropdown-check-list.visible .anchor {
  color: #0094ff;
}

.dropdown-check-list.visible .items {
  display: block;
}
#list1{
  margin-top: -40px;
  position: absolute;
	z-index: 9;
}
#list1 .items{background-color: #fff; }
.bhide{display:none; }
.ui-dialog-title{margin:0 !important;padding:0 !important; float:none !important; }
.thfirst {
	position:sticky;
	left:0;
	z-index:2;
	background-color: #eee;
	    background-image: -webkit-gradient(linear,0 0,0 100%,from(#f2f2f2),to(#fafafa));
	    background-image: -webkit-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -moz-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -ms-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -o-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -linear-gradient(top,#f2f2f2 0,#fafafa 100%);
}
.tdfirst{
	position:sticky;
	left:0;
	border-right-color:#aaa;
	background-color: #eee;
	    background-image: -webkit-gradient(linear,0 0,0 100%,from(#f2f2f2),to(#fafafa));
	    background-image: -webkit-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -moz-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -ms-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -o-linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    background-image: -linear-gradient(top,#f2f2f2 0,#fafafa 100%);
	    font-size: 12px;
}
.dinline{display:inline-flex; }
.dinline button{margin-left:1px; }
</style>
<?php
    $tt=rand(455,888);
    $companylistarr=array();
    $companylist="";
   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company')){
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $stmt->bind_result($__id,$__company);
      while($stmt->fetch()){
        $companylistarr[]= '<option value="'.$__id.'">'.$__company.'</option>"';
      }
    }
    if(count($companylistarr)) $companylist=implode("",$companylistarr);
    else{
      header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
      exit();
    }
  }else{
    header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
    exit();
  }
	$countrylist='<option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia, Plurinational State of</option><option value="BQ">Bonaire, Sint Eustatius and Saba</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, the Democratic Republic of the</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Côte d\'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curaçao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, Democratic People\'s Republic of</option><option value="KR">Korea, Republic of</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People\'s Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia, the former Yugoslav Republic of</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States of</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territory, Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Réunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena, Ascension and Tristan da Cunha</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin (French part)</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome and Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SX">Sint Maarten (Dutch part)</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="SS">South Sudan</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US" selected>United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela, Bolivarian Republic of</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.S.</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>';
?>
		<div id="add-dialog-message<?php echo $tt; ?>" title="Add Bulk Users">
						<form id="add-checkout-form<?php echo $tt; ?>" class="smart-form uadd<?php echo $tt; ?>" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd<?php echo $tt; ?>()">

							<fieldset>
                <div class="row">
                  <div class="table-responsive">
                    <div class="tcenter"><p class="cui"><span style="color:red;">--- </span>Check user inputs</p>&nbsp;<p class="uae"><span style="color:green;">--- </span>User already exists</p></div>
                    <div id="list1" class="dropdown-check-list" tabindex="100">
                      <span class="anchor">Add/Remove Fields</span>
                      <ul class="items">
                        <li><input type="checkbox" id="allselect" /> Select ALL </li>
                        <li><input type="checkbox" id="saddress" /> Address </li>
                        <li><input type="checkbox" id="scountry" /> Country </li>
                        <li><input type="checkbox" id="sstate" /> State</li>
                        <li><input type="checkbox" id="szipcode" /> Zipcode </li>
                        <li><input type="checkbox" id="sphone" /> Phone </li>
                        <li><input type="checkbox" id="smobile" /> Mobile </li>
                        <li><input type="checkbox" id="scsrusername" /> CSR Username</li>
                        <li><input type="checkbox" id="scsrpassword" /> CSR Password</li>
                        <li><input type="checkbox" id="submusername" /> UBM Username</li>
                        <li><input type="checkbox" id="submpassword" /> UBM Password</li>
												<li><input type="checkbox" id="submarchiveusername" /> UBM Archive Username</li>
                        <li><input type="checkbox" id="submarchivepassword" /> UBM Archive Password</li>
                        <li><input type="checkbox" id="sdisabledate" /> Disable date</li>
                        <li><input type="checkbox" id="ssendtome" checked /> Send me Email/Pass</li>
                        <li><input type="checkbox" id="ssendtouser" /> Send to user Email/Pass</li>
                      </ul>
                    </div>
                    <table class="table table-bordered table-striped hidden-mobile busercss">
                      <thead>
                      <tr>
                        <th class="thfirst">Email<i style="color:red;">*</i></th>
                        <th>Password<i style="color:red;">*</i></th>
                        <th>Firstname<i style="color:red;">*</i></th>
                        <th>Lastname<i style="color:red;">*</i></th>
                        <th>Title<i style="color:red;">*</i></th>
                        <th>Company<i style="color:red;">*</i></th>
                        <th>Usergroup<i style="color:red;">*</i></th>
                        <th>Gender<i style="color:red;">*</i></th>
                        <th>Status<i style="color:red;">*</i></th>
                        <th class="bhide newaddress">Address</th>
                        <th class="bhide newcountry">Country</th>
                        <th class="bhide newstate">State</th>
                        <th class="bhide newzipcode">Zipcode</th>
                        <th class="bhide newphone">Phone</th>
                        <th class="bhide newmobile">Mobile</th>
                        <th class="bhide newcsrusername">CSR Username</th>
                        <th class="bhide newcsrpassword">CSR Password</th>
                        <th class="bhide newubmusername">UBM Username</th>
                        <th class="bhide newubmpassword">UBM Password</th>
												<th class="bhide newubmarchiveusername">UBM Archive Username</th>
                        <th class="bhide newubmarchivepassword">UBM Archive Password</th>
                        <th class="bhide newdisabledate">Disable date</th>
                        <th class="newsendtome">Send me Email/Pass</th>
                        <th class="bhide newsendtouser">Send to user Email/Pass</th>
                        <th>&nbsp;</th>
                      </tr>
                      </thead>
                      <tbody id="butable<?php echo $tt; ?>">
                      <tr id="t123">
                        <td class="tdfirst"><input type="hidden" name="tname" value="123"><input type="text" value="" class="newemail" type="email" name="newemail" id="newemail123"></td>
                        <td class="dinline"><input type="text" value="" class="newpassword" name="newpassword" id="newpassword123" autocomplete="off" minlength="8" maxlength="20" required><button type="button" class="btn btn-primary genpwd" id="newgeneratepwd123" onclick="genpwd(123)">Generate Password</button></td>
                        <td><input type="text" value="" class="newfirstname" name="newfirstname" id="newfirstname123"></td>
                        <td><input type="text" value="" class="newlastname" name="newlastname" id="newlastname123"></td>
                        <td><input type="text" value="" class="newtitle" name="newtitle" id="newtitle123"></td>
                        <td>
                          <select class="newcompany" name="newcompany" id="newcompany123" placeholder="Company">
                            <?php echo $companylist; ?>
                          </select>
                        </td>
                        <td>
                          <select name="newusergroup" class="newusergroup" id="newusergroup123">
    												<option value="1">Vervantis Administrator</option>
    												<option value="2">Vervantis Employee</option>
    												<option value="3" selected>Client</option>
    												<option value="4">Vendor</option>
    												<option value="5">Client Administrator</option>
    												<option value="6">Sub Contractors</option>
    											</select>
                        </td>
                        <td><select name="newgender" class="newgender" id="newgender123">
      												<option value="M" selected>Male</option>
      												<option value="F">Female</option>
                        		</select>
                        </td>
                        <td>
                          <select name="newstatus" class="newstatus" id="newstatus123">
    												<option value="1" selected>Active</option>
    												<option value="0" >Inactive</option>
    												<option value="2" >Locked Out</option>
    												<option value="3" >Password Change</option>
    											</select>
                        </td>
                        <td class="bhide"><input type="text" value="" class="newaddress" name="newaddress" id="newaddress123"></td>
                        <td class="bhide">
													<select class="newcountry" name="newcountry" id="newcountry123" placeholder="Country">
                            <?php echo $countrylist; ?>
                          </select>
												</td>
                        <td class="bhide"><input type="text" value="" class="newstate" name="newstate" id="newstate123"></td>
                        <td class="bhide"><input type="text" value="" class="newzipcode" name="newzipcode" id="newzipcode123"></td>
                        <td class="bhide"><input type="text" value="" class="newphone" name="newphone" id="newphone123"></td>
                        <td class="bhide"><input type="text" value="" class="newmobile" name="newmobile" id="newmobile123"></td>
                        <td class="bhide"><input type="text" value="" class="newcsrusername" name="newcsrusername" id="newcsrusername123"></td>
                        <td class="bhide"><input type="text" value="" class="newcsrpassword" name="newcsrpassword" id="newcsrpassword123"></td>
                        <td class="bhide"><input type="text" value="" class="newubmusername" name="newubmusername" id="newubmusername123"></td>
                        <td class="bhide"><input type="text" value="" class="newubmpassword" name="newubmpassword" id="newubmpassword123"></td>
												<td class="bhide"><input type="text" value="" class="newubmarchiveusername" name="newubmarchiveusername" id="newubmarchiveusername123"></td>
                        <td class="bhide"><input type="text" value="" class="newubmarchivepassword" name="newubmarchivepassword" id="newubmarchivepassword123"></td>
                        <td class="bhide"><input type="date" value="" pattern="\d{4}-\d{2}-\d{2}" class="newdisabledate" name="newdisabledate" id="newdisabledate123"></td>
                        <td class=""><input type="checkbox" value="" class="newsendtome" name="newsendtome" id="newsendtome123"></td>
                        <td class="bhide"><input type="checkbox" value="" class="newsendtouser" name="newsendtouser" id="newsendtouser123"></td>
                        <td>&nbsp;</td>
                      </tr>
                      </tbody>
                    </table>
                    <div class="row text-center"><a href="javascript:void(0)" id="addmore<?php echo $tt; ?>" style="font-weight:bold;">+ Add More</a></div>
                  </div>
                </div>
							</fieldset>

							<footer class="tcenter">
								<button type="button" class="btn btn-primary fnone" id="add-bulkprofile-submit<?php echo $tt; ?>">
									Save
								</button>
								<button type="button" class="btn fnone" id="add-profile-cancel<?php echo $tt; ?>">
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

var checkList = document.getElementById('list1');
checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
  if (checkList.classList.contains('visible'))
    checkList.classList.remove('visible');
  else
    checkList.classList.add('visible');
}

function removeit(removeid){
	if(removeid == "") return false;
	$('#t'+removeid).remove();
}

function genpwd(gid){
	var newgpwd=generatePassword(8);//alert(newgpwd);
	$('input#newpassword'+gid).val(newgpwd);
}

$(function() {
$(document).ready(function() {
	$("#allselect").on('change',function(){
		if ($('#allselect').prop("checked")) {
			$("#list1 .items input:checkbox:not(:checked)").each(function() {
					$(this).click();
			});
    }else{
			$("#list1 .items input:checkbox:checked").each(function() {
					$(this).click();
			});
    }
	});

  $("#saddress").on('change',function(){
    if ($('#saddress').prop("checked")) {
      $( "th.newaddress").removeClass( "bhide" );
      $(".newaddress").closest("td").removeClass( "bhide" );
    }else{
      $( "th.newaddress").addClass( "bhide" );
      $(".newaddress").closest("td").addClass( "bhide" );
    }
  });

  $("#scountry").on('click',function(){
    if ($('#scountry').prop("checked")) {
      $( "th.newcountry").removeClass( "bhide" );
      $(".newcountry").closest("td").removeClass( "bhide" );
    }else{
      $( "th.newcountry").addClass( "bhide" );
      $(".newcountry").closest("td").addClass( "bhide" );
    }
  });

	$("#sstate").on('click',function(){
	  if ($('#sstate').prop("checked")) {
	    $( "th.newstate").removeClass( "bhide" );
	    $(".newstate").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newstate").addClass( "bhide" );
	    $(".newstate").closest("td").addClass( "bhide" );
	  }
	});

	$("#szipcode").on('click',function(){
	  if ($('#szipcode').prop("checked")) {
	    $( "th.newzipcode").removeClass( "bhide" );
	    $(".newzipcode").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newzipcode").addClass( "bhide" );
	    $(".newzipcode").closest("td").addClass( "bhide" );
	  }
	});

	$("#sphone").on('click',function(){
	  if ($('#sphone').prop("checked")) {
	    $( "th.newphone").removeClass( "bhide" );
	    $(".newphone").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newphone").addClass( "bhide" );
	    $(".newphone").closest("td").addClass( "bhide" );
	  }
	});

	$("#smobile").on('click',function(){
	  if ($('#smobile').prop("checked")) {
	    $( "th.newmobile").removeClass( "bhide" );
	    $(".newmobile").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newmobile").addClass( "bhide" );
	    $(".newmobile").closest("td").addClass( "bhide" );
	  }
	});

	$("#scsrusername").on('click',function(){
	  if ($('#scsrusername').prop("checked")) {
	    $( "th.newcsrusername").removeClass( "bhide" );
	    $(".newcsrusername").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newcsrusername").addClass( "bhide" );
	    $(".newcsrusername").closest("td").addClass( "bhide" );
	  }
	});

	$("#scsrpassword").on('click',function(){
	  if ($('#scsrpassword').prop("checked")) {
	    $( "th.newcsrpassword").removeClass( "bhide" );
	    $(".newcsrpassword").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newcsrpassword").addClass( "bhide" );
	    $(".newcsrpassword").closest("td").addClass( "bhide" );
	  }
	});

	$("#submusername").on('click',function(){
	  if ($('#submusername').prop("checked")) {
	    $( "th.newubmusername").removeClass( "bhide" );
	    $(".newubmusername").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newubmusername").addClass( "bhide" );
	    $(".newubmusername").closest("td").addClass( "bhide" );
	  }
	});

	$("#submpassword").on('click',function(){
	  if ($('#submpassword').prop("checked")) {
	    $( "th.newubmpassword").removeClass( "bhide" );
	    $(".newubmpassword").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newubmpassword").addClass( "bhide" );
	    $(".newubmpassword").closest("td").addClass( "bhide" );
	  }
	});

	$("#submarchiveusername").on('click',function(){
	  if ($('#submarchiveusername').prop("checked")) {
	    $( "th.newubmarchiveusername").removeClass( "bhide" );
	    $(".newubmarchiveusername").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newubmarchiveusername").addClass( "bhide" );
	    $(".newubmarchiveusername").closest("td").addClass( "bhide" );
	  }
	});

	$("#submarchivepassword").on('click',function(){
	  if ($('#submarchivepassword').prop("checked")) {
	    $( "th.newubmarchivepassword").removeClass( "bhide" );
	    $(".newubmarchivepassword").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newubmarchivepassword").addClass( "bhide" );
	    $(".newubmarchivepassword").closest("td").addClass( "bhide" );
	  }
	});

	$("#sdisabledate").on('click',function(){
	  if ($('#sdisabledate').prop("checked")) {
	    $( "th.newdisabledate").removeClass( "bhide" );
	    $(".newdisabledate").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newdisabledate").addClass( "bhide" );
	    $(".newdisabledate").closest("td").addClass( "bhide" );
	  }
	});

	$("#ssendtome").on('click',function(){
	  if ($('#ssendtome').prop("checked")) {
	    $( "th.newsendtome").removeClass( "bhide" );
	    $(".newsendtome").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newsendtome").addClass( "bhide" );
	    $(".newsendtome").closest("td").addClass( "bhide" );
	  }
	});

	$("#ssendtouser").on('click',function(){
	  if ($('#ssendtouser').prop("checked")) {
	    $( "th.newsendtouser").removeClass( "bhide" );
	    $(".newsendtouser").closest("td").removeClass( "bhide" );
	  }else{
	    $( "th.newsendtouser").addClass( "bhide" );
	    $(".newsendtouser").closest("td").addClass( "bhide" );
	  }
	});

  $("#addmore<?php echo $tt; ?>").on('click',function(){
    var milliseconds = new Date().getTime();
    $("#butable<?php echo $tt; ?>").append('<tr id="t'+milliseconds+'"><td class="tdfirst"><input type="hidden" name="tname" value="'+milliseconds+'"><input type="text" value="" class="newemail" type="email" name="newemail" id="newemail'+milliseconds+'"></td><td class="dinline"><input type="text" value="" class="newpassword" name="newpassword" id="newpassword'+milliseconds+'" autocomplete="off" minlength="8" maxlength="20" required><button type="button" class="btn btn-primary genpwd" id="newgeneratepwd'+milliseconds+'"   onclick="genpwd('+milliseconds+')">Generate Password!</button></td><td><input type="text" value="" class="newfirstname" name="newfirstname" id="newfirstname'+milliseconds+'"></td><td><input type="text" value="" class="newlastname" name="newlastname" id="newlastname'+milliseconds+'"></td><td><input type="text" value="" class="newtitle" name="newtitle" id="newtitle'+milliseconds+'"></td><td><select class="newcompany" name="newcompany" id="newcompany'+milliseconds+'" placeholder="Company" class=""><?php echo str_replace("'", "\'",$companylist); ?></select></td><td><select name="newusergroup" class="newusergroup" id="newusergroup'+milliseconds+'"><option value="1">Vervantis Administrator</option><option value="2">Vervantis Employee</option><option value="3" selected>Client</option><option value="4">Vendor</option><option value="5">Client Administrator</option><option value="6">Sub Contractors</option></select></td><td><select name="newgender" class="newgender" id="newgender'+milliseconds+'"><option value="M" selected>Male</option><option value="F">Female</option></select></td><td><select name="newstatus" class="newstatus" id="newstatus'+milliseconds+'"><option value="1" selected>Active</option><option value="0" >Inactive</option><option value="2" >Locked Out</option><option value="3" >Password Change</option></select></td><td class="bhide"><input type="text" value="" class="newaddress" name="newaddress" id="newaddress'+milliseconds+'"></td><td class="bhide"><select class="newcountry" name="newcountry" id="newcountry'+milliseconds+'" placeholder="Country"><?php echo str_replace(array("'","(",")"), array("\'","\(","\)"),$countrylist); ?></select></td><td class="bhide"><input type="text" value="" class="newstate" name="newstate" id="newstate'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newzipcode" name="newzipcode" id="newzipcode'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newphone" name="newphone" id="newphone'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newmobile" name="newmobile" id="newmobile'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newcsrusername" name="newcsrusername" id="newcsrusername'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newcsrpassword" name="newcsrpassword" id="newcsrpassword'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newubmusername" name="newubmusername" id="newubmusername'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newubmpassword" name="newubmpassword" id="newubmpassword'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newubmarchiveusername" name="newubmarchiveusername" id="newubmarchiveusername'+milliseconds+'"></td><td class="bhide"><input type="text" value="" class="newubmarchivepassword" name="newubmarchivepassword" id="newubmarchivepassword'+milliseconds+'"></td><td class="bhide"><input type="date" value="" pattern="\d{4}-\d{2}-\d{2}" class="newdisabledate" name="newdisabledate" id="newdisabledate'+milliseconds+'"></td><td class=""><input type="checkbox" value="" class="newsendtome" name="newsendtome" id="newsendtome'+milliseconds+'"></td><td class="bhide"><input type="checkbox" value="" class="newsendtouser" name="newsendtouser" id="newsendtouser'+milliseconds+'"></td><td><a href="javascript:void(0)" onclick="removeit\('+milliseconds+'\)" style="float: right;font-weight: bold;font-size: 23px;color: red;">-</a></td></tr>');
  });

  $("#add-bulkprofile-submit<?php echo $tt; ?>").on('click',function(){
    $(".busercss tr").css("border", "none") ;
    $(".uae").css("display", "none;");
    $(".cui").css("display", "none");
    mlist = [];
    arr2=[];
    $("input[name='tname']").each(function() {
        mlist.push($(this).val());
    });

		tmperrorlist=[];
		for (let i = 0; i < mlist.length; i++) {
			if($("#newemail"+mlist[0]).val()==""){tmperrorlist.push('Email'); }
			if($("#newpassword"+mlist[0]).val() == ""){tmperrorlist.push('Password'); }
			if($("#newfirstname"+mlist[0]).val()==""){tmperrorlist.push('Firstname'); }
			if($("#newlastname"+mlist[0]).val()==""){tmperrorlist.push('Lastname'); }
			if($("#newtitle"+mlist[0]).val()==""){tmperrorlist.push('Title'); }
			if($("#newcompany"+mlist[0]).val()==""){tmperrorlist.push('Company'); }
			if($("#usergroups"+mlist[0]).val()==""){tmperrorlist.push('User Groups'); }
			if($("#newgender"+mlist[0]).val()==""){tmperrorlist.push('Gender'); }
			if($("#status"+mlist[0]).val()==""){tmperrorlist.push('Status'); }
		}

		if(tmperrorlist.length > 0){
			$.smallBox({
				title : "Error in request.",
				content : "<i class='fa fa-clock-o'></i> <i>Please fill all columns marked (*)</i>",
				color : "#FFA07A",
				iconSmall : "fa fa-warning shake animated",
				timeout : 4000
			});
		}else{



	    var text="";
	    var formData = new FormData();
	    for (let i = 0; i < mlist.length; i++) {
	      //text += mlist[i] + "<br>";

	      /*formData.append('email'+mlist[i], $("#newemail"+mlist[i]).val());
	      formData.append('password'+mlist[i], $("#newpassword"+mlist[i]).val());
	      formData.append('fname'+mlist[i], $("#newfirstname"+mlist[i]).val());
	      formData.append('lname'+mlist[i], $("#newlastname"+mlist[i]).val());
	      formData.append('title'+mlist[i], $("#newtitle"+mlist[i]).val());
	      formData.append('company'+mlist[i], $("#newcompany"+mlist[i]).val());
	      formData.append('usergroups'+mlist[i], $("#newusergroup"+mlist[i]).val());
	      formData.append('gender'+mlist[i], $("#newgender"+mlist[i]).val());
	      formData.append('status'+mlist[i], $("#newstatus"+mlist[i]).val());*/


	      formData.append(mlist[i], JSON.stringify([{'email':$("#newemail"+mlist[i]).val(),'password':$("#newpassword"+mlist[i]).val(),'p': ajaxformhash($("#newpassword"+mlist[i]).val()),'fname':$("#newfirstname"+mlist[i]).val(),'lname':$("#newlastname"+mlist[i]).val(),'title':$("#newtitle"+mlist[i]).val(),'company':$("#newcompany"+mlist[i]).val(),'usergroups':$("#newusergroup"+mlist[i]).val(),'gender':$("#newgender"+mlist[i]).val(),'status':$("#newstatus"+mlist[i]).val(),'mlist':mlist[i]
				,'address':$("#newaddress"+mlist[i]).val(),'mlist':mlist[i]
				,'country':$("#newcountry"+mlist[i]).val(),'mlist':mlist[i]
				,'state':$("#newstate"+mlist[i]).val(),'mlist':mlist[i]
				,'zipcode':$("#newzipcode"+mlist[i]).val(),'mlist':mlist[i]
				,'phone':$("#newphone"+mlist[i]).val(),'mlist':mlist[i]
				,'mobile':$("#newmobile"+mlist[i]).val(),'mlist':mlist[i]
				,'csrusername':$("#newcsrusername"+mlist[i]).val(),'mlist':mlist[i]
				,'csrpassword':$("#newcsrpassword"+mlist[i]).val(),'mlist':mlist[i]
				,'ubmusername':$("#newubmusername"+mlist[i]).val(),'mlist':mlist[i]
				,'ubmpassword':$("#newubmpassword"+mlist[i]).val(),'mlist':mlist[i]
				,'ubmarchiveusername':$("#newubmarchiveusername"+mlist[i]).val(),'mlist':mlist[i]
				,'ubmarchivepassword':$("#newubmarchivepassword"+mlist[i]).val(),'mlist':mlist[i]
				,'disabledate':$("#newdisabledate"+mlist[i]).val(),'mlist':mlist[i]
				,'sendtome':$("#newsendtome"+mlist[i]).val(),'mlist':mlist[i]
				,'sendtouser':$("#newsendtouser"+mlist[i]).val(),'mlist':mlist[i]

			}]));
	    }

	    formData.append('addbulkuser', 'addbulkuser');


	    //console.log(Array.from(formData.entries()))

	    $.ajax({
	      type: 'post',
	      url: 'assets/includes/profileeditbulk.inc.php',
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
	              $("#add-dialog-message<?php echo $tt; ?>").dialog("close");
								$("#add-dialog-message<?php echo $tt; ?>").dialog('destroy').remove();
	              parent.$("#presponse").html('');
	              parent.$('#presponse').load('assets/ajax/user-pedit.php?ct=<?php echo time(); ?>');
	            });
	          }else{
	            var tmperror="Please try after sometime...";
	            tmperror=results.error;
	            if(results.err !=""){
	              var errarr=results.err.split("@@");
	              for (let i = 0; i < errarr.length; i++) {
	                 $("#t"+errarr[i]).css("border", "2px solid red");
	                 arr2.push(errarr[i]);
	              }
	              $(".cui").css("display", "block");
	            }

	            if(results.errexist !=""){
	              var errexistarr=results.errexist.split("@@");
	              for (let i = 0; i < errexistarr.length; i++) {
	                 $("#t"+errexistarr[i]).css("border", "2px solid green");
	                 arr2.push(errexistarr[i]);
	              }
	              $(".uae").css("display", "block");
	            }

	            let difference = mlist.filter(x => !arr2.includes(x));
	            for (let i = 0; i < difference.length; i++) {
	               $("#t"+difference[i]).remove();
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
	            timeout : 5000
	          });
	        }
	      }
	      });
	    return false;

		}
  });




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


/*$(".genpwd").on('click',function(){
	var athis=this;
	var newgpwd=generatePassword(8);alert(newgpwd);
	$(athis).prev('input').val(newgpwd);
});*/

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
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.8;
		$("#add-dialog-message<?php echo $tt; ?>").dialog({
			autoOpen : true,
			modal : true,
      width: dWidth,
			title : "<div class='widget-header'><h4 style='text-align:center;'><i class='icon-ok'></i>Add Bulk User</h4></div>",
			close: function(event, ui)
			{
					$(this).dialog("close");
					$(this).remove();
			}
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

    $('#add-profile-cancel<?php echo $tt; ?>').on('click',function(){
        $("#add-dialog-message<?php echo $tt; ?>").dialog("close");
				$("#add-dialog-message<?php echo $tt; ?>").dialog('destroy').remove();
        parent.$("#presponse").html("");
    });



		var $checkoutForm = $('#add-checkout-form<?php echo 123; ?>').validate({
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
