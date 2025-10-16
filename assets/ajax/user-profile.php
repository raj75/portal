<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

date_default_timezone_set('UTC');

if(isset($_GET["default"]) and $_GET["default"] == "true" and isset($_SESSION["user_id"]))
	$_GET["userid"]=$_SESSION["user_id"];

if(!isset($_GET["userid"]) or $_GET["userid"] == "")
	die("Wrong Parameters");

$userid=$_GET["userid"];
$usergrp=$_SESSION["group_id"];
$usercid=$_SESSION["company_id"];

	$_firstname=$_lastname=$_phone=$_email=$_aboutme=$_company=$address1="N/A";
	$_cuserimage="";
	$_cgender="M";
	$subsql='';
	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){
		//$subsql=' and u.usergroups_id='.$usergrp.' ';
		$subsql=' and c.company_id='.$usercid.' ';
	}
   if ($stmt = $mysqli->prepare('SELECT u.firstname,u.lastname,u.title,u.company_id,u.phone,u.mobile,u.fax,u.email,u.birthdate,u.accuvio_user,u.accuvio_pass,u.capturis_user,u.capturis_pass,u.capturis_archive_user,u.capturis_archive_pass,u.notes,u.address,u.city,u.state,u.country,u.zip,u.banner,u.gender,u.usergroups_id,u.status,u.disable_date,c.company_name FROM `user` u, company c where u.user_id='.$userid.' and u.company_id=c.company_id '.$subsql.'LIMIT 1')) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
                $stmt->bind_result($_firstname,$_lastname,$_title,$_company_id,$_phone,$_mobile,$_fax,$_email,$_birthdate,$_accuvio_user,$_accuvio_pass,$_capturis_user,$_capturis_pass,$_capturis_archive_user,$_capturis_archive_pass,$_notes,$_address,$_city,$_state,$_country,$_zip,$_banner,$_cgender,$_cusergroups,$_cstatus,$_csdisabledate,$_company);
                $stmt->fetch();

				if(@trim($_accuvio_user) != "") $_accuvio_user=ed_crypt(@trim($_accuvio_user),'d');
				if(@trim($_accuvio_pass) != "") $_accuvio_pass=ed_crypt(@trim($_accuvio_pass),'d');
				if(@trim($_capturis_user) != "") $_capturis_user=ed_crypt(@trim($_capturis_user),'d');
				if(@trim($_capturis_pass) != "") $_capturis_pass=ed_crypt(@trim($_capturis_pass),'d');
				if(@trim($_capturis_archive_user) != "") $_capturis_archive_user=ed_crypt(@trim($_capturis_archive_user),'d');
				if(@trim($_capturis_archive_pass) != "") $_capturis_archive_pass=ed_crypt(@trim($_capturis_archive_pass),'d');

				if($_city=="" and $_state=="" and $_zip=="") $address1="";
				else if($_city !="" and $_state=="" and $_zip=="") $address1=$_city;
				else if($_city !="" and $_state!="" and $_zip=="") $address1=$_city.", ".$_state;
				else if($_city !="" and $_state!="" and $_zip !="") $address1=$_city.", ".$_state." ".$_zip;
				else if($_city !="" and $_state=="" and $_zip !="") $address1=$_city." ".$_zip;
				else if($_city =="" and $_state!="" and $_zip !="") $address1=$_state." ".$_zip;
				else if($_city =="" and $_state=="" and $_zip !="") $address1=$_zip;
				else if($_city =="" and $_state!="" and $_zip !="") $address1=$_state;
		}else{
			die("Restricted Access. Contact Vervantis admin.");
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}


	$_cuserimage=checks3img(md5($userid).".png","profiles/users/profile image/",(($_cgender == "M" || @trim($_cgender == ""))?"male.png":"female.png"));
	if($_cuserimage==false){$_cuserimage="";}
	//else {$_cuserimage=$_cuserimage."&v=".mt_rand(6000,10000);}

	/*if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($userid).".png") and ($_cgender == "M" || @trim($_cgender == "")))
		$_cuserimage="male.png";
	elseif(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".md5($userid).".png") and $_cgender == "F")
		$_cuserimage="female.png";
	else
		$_cuserimage=md5($userid).".png";*/

	//$_ccemail=ucfirst(strtolower($_ccemail));

	//SELECT groupname FROM user u,usergroups ug where u.usergroups_id=ug.id

	if(@trim($_banner) == "") $_banner="#327694";


	$tmp_rid=mt_rand(10000,125000);
?>
<script>
/*
if (top.location.indexOf("vervantis.com")== -1){alert "Not"; }
if(parent.$(".helpdeskmodel").length){alert("Yes"); }else{ alert("No"); }*/
</script>
<!-- row -->
<style>
.uedit footer{text-align:center;}
.uedit footer button{float:none !important;}
.colorpicker{z-index: 99999;}
.bannerdisplay{height: 768px;width: 100%;background:<?php echo $_banner; ?>;}
.company-logo-dis{    max-height: 200px !important;
    margin-top: 33px;
    max-width: 200px !important;
	}
</style>
<div class="row">

	<div class="col-sm-12">


			<div class="well well-sm">

				<div class="row">
<!--  col-sm-12 col-md-12 col-lg-6 -->
					<div class="col-md-12">
						<div class="well well-light well-sm no-margin no-padding">

							<div class="row">

								<div class="col-sm-12">
									<div id="myCarousel" class="carousel fade profile-carousel">
										<div class="air air-bottom-right padding-10">
											<a href="javascript:void(0);" class="btn txt-color-white btn-primary btn-sm" id="change-banner<?php echo $tmp_rid; ?>"><i class="fa fa-link"></i> Change Banner</a>
										</div>
										<div class="air air-top-left padding-10">
											<h4 class="txt-color-white font-md textedit hidden"  data-pname="birthdate"><?php echo date("M d,Y",strtotime($_birthdate)); ?></h4>
										</div>
										<div class="carousel-inner">
											<div class="item active bannerdisplay"></div>
										</div>
									</div>
								</div>

								<div class="col-sm-12">

									<div class="row">

										<div class="col-sm-3 profile-pic">
											<img src="<?php echo $_cuserimage; ?>" width="100px">
										</div>
										<div class="col-sm-6">
											<div class="row">
												<div class="col-sm-6">
											<h1><?php if($_firstname !="" or $_lastname != ""){ ?><span class="textedit"  data-pname="firstname"><?php echo $_firstname; ?></span> <span class="semi-bold textedit"  data-pname="lastname"><?php echo $_lastname; ?></span>
											<br><?php } ?>
											<?php if($_title !=""){ ?><small> <span class="textedit"  data-pname="title"><?php echo $_title; ?></span></small>
											<br><?php } ?>
											<?php if($_company !=""){ ?><small><span class="textedit"  data-pname="company"><?php echo $_company; ?></span></small>
											&nbsp;</h1><?php } ?>

											<ul class="list-unstyled">
												<?php if($_address !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="icon-prepend fa fa-bank"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="address"><?php echo $_address; ?></span>
													</p>
												</li>
												<?php } ?>
												<?php if($address1 !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="icon-prepend fa fa-bank"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="address"><?php echo $address1; ?></span>
													</p>
												</li>
												<?php } ?>
												<li>
													<p class="text-muted">
														<i class="icon-prepend fa fa-bank"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="country">
<?php
	if($_country=="AF"){?>Afghanistan
<?php }else if($_country=="AX"){?>Åland Islands
<?php }else if($_country=="AL"){?>Albania
<?php }else if($_country=="DZ"){?>Algeria
<?php }else if($_country=="AS"){?>American Samoa
<?php }else if($_country=="AD"){?>Andorra
<?php }else if($_country=="AO"){?>Angola
<?php }else if($_country=="AI"){?>Anguilla
<?php }else if($_country=="AQ"){?>Antarctica
<?php }else if($_country=="AG"){?>Antigua and Barbuda
<?php }else if($_country=="AR"){?>Argentina
<?php }else if($_country=="AM"){?>Armenia
<?php }else if($_country=="AW"){?>Aruba
<?php }else if($_country=="AU"){?>Australia
<?php }else if($_country=="AT"){?>Austria
<?php }else if($_country=="AZ"){?>Azerbaijan
<?php }else if($_country=="BS"){?>Bahamas
<?php }else if($_country=="BH"){?>Bahrain
<?php }else if($_country=="BD"){?>Bangladesh
<?php }else if($_country=="BB"){?>Barbados
<?php }else if($_country=="BY"){?>Belarus
<?php }else if($_country=="BE"){?>Belgium
<?php }else if($_country=="BZ"){?>Belize
<?php }else if($_country=="BJ"){?>Benin
<?php }else if($_country=="BM"){?>Bermuda
<?php }else if($_country=="BT"){?>Bhutan
<?php }else if($_country=="BO"){?>Bolivia, Plurinational State of
<?php }else if($_country=="BQ"){?>Bonaire, Sint Eustatius and Saba
<?php }else if($_country=="BA"){?>Bosnia and Herzegovina
<?php }else if($_country=="BW"){?>Botswana
<?php }else if($_country=="BV"){?>Bouvet Island
<?php }else if($_country=="BR"){?>Brazil
<?php }else if($_country=="IO"){?>British Indian Ocean Territory
<?php }else if($_country=="BN"){?>Brunei Darussalam
<?php }else if($_country=="BG"){?>Bulgaria
<?php }else if($_country=="BF"){?>Burkina Faso
<?php }else if($_country=="BI"){?>Burundi
<?php }else if($_country=="KH"){?>Cambodia
<?php }else if($_country=="CM"){?>Cameroon
<?php }else if($_country=="CA"){?>Canada
<?php }else if($_country=="CV"){?>Cape Verde
<?php }else if($_country=="KY"){?>Cayman Islands
<?php }else if($_country=="CF"){?>Central African Republic
<?php }else if($_country=="TD"){?>Chad
<?php }else if($_country=="CL"){?>Chile
<?php }else if($_country=="CN"){?>China
<?php }else if($_country=="CX"){?>Christmas Island
<?php }else if($_country=="CC"){?>Cocos (Keeling) Islands
<?php }else if($_country=="CO"){?>Colombia
<?php }else if($_country=="KM"){?>Comoros
<?php }else if($_country=="CG"){?>Congo
<?php }else if($_country=="CD"){?>Congo, the Democratic Republic of the
<?php }else if($_country=="CK"){?>Cook Islands
<?php }else if($_country=="CR"){?>Costa Rica
<?php }else if($_country=="CI"){?>Côte d'Ivoire
<?php }else if($_country=="HR"){?>Croatia
<?php }else if($_country=="CU"){?>Cuba
<?php }else if($_country=="CW"){?>Curaçao
<?php }else if($_country=="CY"){?>Cyprus
<?php }else if($_country=="CZ"){?>Czech Republic
<?php }else if($_country=="DK"){?>Denmark
<?php }else if($_country=="DJ"){?>Djibouti
<?php }else if($_country=="DM"){?>Dominica
<?php }else if($_country=="DO"){?>Dominican Republic
<?php }else if($_country=="EC"){?>Ecuador
<?php }else if($_country=="EG"){?>Egypt
<?php }else if($_country=="SV"){?>El Salvador
<?php }else if($_country=="GQ"){?>Equatorial Guinea
<?php }else if($_country=="ER"){?>Eritrea
<?php }else if($_country=="EE"){?>Estonia
<?php }else if($_country=="ET"){?>Ethiopia
<?php }else if($_country=="FK"){?>Falkland Islands (Malvinas)
<?php }else if($_country=="FO"){?>Faroe Islands
<?php }else if($_country=="FJ"){?>Fiji
<?php }else if($_country=="FI"){?>Finland
<?php }else if($_country=="FR"){?>France
<?php }else if($_country=="GF"){?>French Guiana
<?php }else if($_country=="PF"){?>French Polynesia
<?php }else if($_country=="TF"){?>French Southern Territories
<?php }else if($_country=="GA"){?>Gabon
<?php }else if($_country=="GM"){?>Gambia
<?php }else if($_country=="GE"){?>Georgia
<?php }else if($_country=="DE"){?>Germany
<?php }else if($_country=="GH"){?>Ghana
<?php }else if($_country=="GI"){?>Gibraltar
<?php }else if($_country=="GR"){?>Greece
<?php }else if($_country=="GL"){?>Greenland
<?php }else if($_country=="GD"){?>Grenada
<?php }else if($_country=="GP"){?>Guadeloupe
<?php }else if($_country=="GU"){?>Guam
<?php }else if($_country=="GT"){?>Guatemala
<?php }else if($_country=="GG"){?>Guernsey
<?php }else if($_country=="GN"){?>Guinea
<?php }else if($_country=="GW"){?>Guinea-Bissau
<?php }else if($_country=="GY"){?>Guyana
<?php }else if($_country=="HT"){?>Haiti
<?php }else if($_country=="HM"){?>Heard Island and McDonald Islands
<?php }else if($_country=="VA"){?>Holy See (Vatican City State)
<?php }else if($_country=="HN"){?>Honduras
<?php }else if($_country=="HK"){?>Hong Kong
<?php }else if($_country=="HU"){?>Hungary
<?php }else if($_country=="IS"){?>Iceland
<?php }else if($_country=="IN"){?>India
<?php }else if($_country=="ID"){?>Indonesia
<?php }else if($_country=="IR"){?>Iran, Islamic Republic of
<?php }else if($_country=="IQ"){?>Iraq
<?php }else if($_country=="IE"){?>Ireland
<?php }else if($_country=="IM"){?>Isle of Man
<?php }else if($_country=="IL"){?>Israel
<?php }else if($_country=="IT"){?>Italy
<?php }else if($_country=="JM"){?>Jamaica
<?php }else if($_country=="JP"){?>Japan
<?php }else if($_country=="JE"){?>Jersey
<?php }else if($_country=="JO"){?>Jordan
<?php }else if($_country=="KZ"){?>Kazakhstan
<?php }else if($_country=="KE"){?>Kenya
<?php }else if($_country=="KI"){?>Kiribati
<?php }else if($_country=="KP"){?>Korea, Democratic People's Republic of
<?php }else if($_country=="KR"){?>Korea, Republic of
<?php }else if($_country=="KW"){?>Kuwait
<?php }else if($_country=="KG"){?>Kyrgyzstan
<?php }else if($_country=="LA"){?>Lao People's Democratic Republic
<?php }else if($_country=="LV"){?>Latvia
<?php }else if($_country=="LB"){?>Lebanon
<?php }else if($_country=="LS"){?>Lesotho
<?php }else if($_country=="LR"){?>Liberia
<?php }else if($_country=="LY"){?>Libya
<?php }else if($_country=="LI"){?>Liechtenstein
<?php }else if($_country=="LT"){?>Lithuania
<?php }else if($_country=="MU"){?>Luxembourg
<?php }else if($_country=="MO"){?>Macao
<?php }else if($_country=="MK"){?>Macedonia, the former Yugoslav Republic of
<?php }else if($_country=="MG"){?>Madagascar
<?php }else if($_country=="MW"){?>Malawi
<?php }else if($_country=="MY"){?>Malaysia
<?php }else if($_country=="MV"){?>Maldives
<?php }else if($_country=="ML"){?>Mali
<?php }else if($_country=="MT"){?>Malta
<?php }else if($_country=="MH"){?>Marshall Islands
<?php }else if($_country=="MQ"){?>Martinique
<?php }else if($_country=="MR"){?>Mauritania
<?php }else if($_country=="MU"){?>Mauritius
<?php }else if($_country=="YT"){?>Mayotte
<?php }else if($_country=="MX"){?>Mexico
<?php }else if($_country=="FM"){?>Micronesia, Federated States of
<?php }else if($_country=="MD"){?>Moldova, Republic of
<?php }else if($_country=="MC"){?>Monaco
<?php }else if($_country=="MN"){?>Mongolia
<?php }else if($_country=="ME"){?>Montenegro
<?php }else if($_country=="MS"){?>Montserrat
<?php }else if($_country=="MA"){?>Morocco
<?php }else if($_country=="MZ"){?>Mozambique
<?php }else if($_country=="MM"){?>Myanmar
<?php }else if($_country=="NA"){?>Namibia
<?php }else if($_country=="NR"){?>Nauru
<?php }else if($_country=="NP"){?>Nepal
<?php }else if($_country=="NL"){?>Netherlands
<?php }else if($_country=="NC"){?>New Caledonia
<?php }else if($_country=="NZ"){?>New Zealand
<?php }else if($_country=="NI"){?>Nicaragua
<?php }else if($_country=="NE"){?>Niger
<?php }else if($_country=="NG"){?>Nigeria
<?php }else if($_country=="NU"){?>Niue
<?php }else if($_country=="NF"){?>Norfolk Island
<?php }else if($_country=="MO"){?>Northern Mariana Islands
<?php }else if($_country=="NO"){?>Norway
<?php }else if($_country=="OM"){?>Oman
<?php }else if($_country=="PK"){?>Pakistan
<?php }else if($_country=="PW"){?>Palau
<?php }else if($_country=="PS"){?>Palestinian Territory, Occupied
<?php }else if($_country=="PA"){?>Panama
<?php }else if($_country=="PG"){?>Papua New Guinea
<?php }else if($_country=="PY"){?>Paraguay
<?php }else if($_country=="PE"){?>Peru
<?php }else if($_country=="PH"){?>Philippines
<?php }else if($_country=="PN"){?>Pitcairn
<?php }else if($_country=="PL"){?>Poland
<?php }else if($_country=="PT"){?>Portugal
<?php }else if($_country=="PR"){?>Puerto Rico
<?php }else if($_country=="QA"){?>Qatar
<?php }else if($_country=="RE"){?>Réunion
<?php }else if($_country=="RO"){?>Romania
<?php }else if($_country=="RU"){?>Russian Federation
<?php }else if($_country=="RW"){?>Rwanda
<?php }else if($_country=="BL"){?>Saint Barthélemy
<?php }else if($_country=="SH"){?>Saint Helena, Ascension and Tristan da Cunha
<?php }else if($_country=="KN"){?>Saint Kitts and Nevis
<?php }else if($_country=="LC"){?>Saint Lucia
<?php }else if($_country=="MF"){?>Saint Martin (French part)
<?php }else if($_country=="PM"){?>Saint Pierre and Miquelon
<?php }else if($_country=="VC"){?>Saint Vincent and the Grenadines
<?php }else if($_country=="WS"){?>Samoa
<?php }else if($_country=="SM"){?>San Marino
<?php }else if($_country=="ST"){?>Sao Tome and Principe
<?php }else if($_country=="SA"){?>Saudi Arabia
<?php }else if($_country=="SN"){?>Senegal
<?php }else if($_country=="RS"){?>Serbia
<?php }else if($_country=="SC"){?>Seychelles
<?php }else if($_country=="SL"){?>Sierra Leone
<?php }else if($_country=="SG"){?>Singapore
<?php }else if($_country=="SX"){?>Sint Maarten (Dutch part)
<?php }else if($_country=="SK"){?>Slovakia
<?php }else if($_country=="SI"){?>Slovenia
<?php }else if($_country=="SB"){?>Solomon Islands
<?php }else if($_country=="SO"){?>Somalia
<?php }else if($_country=="ZA"){?>South Africa
<?php }else if($_country=="GS"){?>South Georgia and the South Sandwich Islands
<?php }else if($_country=="SS"){?>South Sudan
<?php }else if($_country=="ES"){?>Spain
<?php }else if($_country=="LK"){?>Sri Lanka
<?php }else if($_country=="SD"){?>Sudan
<?php }else if($_country=="SR"){?>Suriname
<?php }else if($_country=="SJ"){?>Svalbard and Jan Mayen
<?php }else if($_country=="SZ"){?>Swaziland
<?php }else if($_country=="SE"){?>Sweden
<?php }else if($_country=="CH"){?>Switzerland
<?php }else if($_country=="SY"){?>Syrian Arab Republic
<?php }else if($_country=="TW"){?>Taiwan, Province of China
<?php }else if($_country=="TJ"){?>Tajikistan
<?php }else if($_country=="TZ"){?>Tanzania, United Republic of
<?php }else if($_country=="TH"){?>Thailand
<?php }else if($_country=="TL"){?>Timor-Leste
<?php }else if($_country=="TG"){?>Togo
<?php }else if($_country=="TK"){?>Tokelau
<?php }else if($_country=="TO"){?>Tonga
<?php }else if($_country=="TT"){?>Trinidad and Tobago
<?php }else if($_country=="TN"){?>Tunisia
<?php }else if($_country=="TR"){?>Turkey
<?php }else if($_country=="TM"){?>Turkmenistan
<?php }else if($_country=="TC"){?>Turks and Caicos Islands
<?php }else if($_country=="TV"){?>Tuvalu
<?php }else if($_country=="UG"){?>Uganda
<?php }else if($_country=="UA"){?>Ukraine
<?php }else if($_country=="AE"){?>United Arab Emirates
<?php }else if($_country=="GB"){?>United Kingdom
<?php }else if($_country=="UM"){?>United States Minor Outlying Islands
<?php }else if($_country=="UY"){?>Uruguay
<?php }else if($_country=="UZ"){?>Uzbekistan
<?php }else if($_country=="VU"){?>Vanuatu
<?php }else if($_country=="VE"){?>Venezuela, Bolivarian Republic of
<?php }else if($_country=="VN"){?>Viet Nam
<?php }else if($_country=="VG"){?>Virgin Islands, British
<?php }else if($_country=="VI"){?>Virgin Islands, U.S.
<?php }else if($_country=="WF"){?>Wallis and Futuna
<?php }else if($_country=="EH"){?>Western Sahara
<?php }else if($_country=="YE"){?>Yemen
<?php }else if($_country=="ZM"){?>Zambia
<?php }else if($_country=="ZW"){?>Zimbabwe
<?php }else{ ?>United States <?php }
?>
														</span>
													</p>
												</li>
												<?php if(formatPhoneNumber($_phone) !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="fa fa-phone"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="phn1" data-mask="(999) 999-9999"><?php echo formatPhoneNumber($_phone); ?></span>
													</p>
												</li>
												<?php } ?>
												<?php if(formatPhoneNumber($_mobile) !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="fa fa-mobile"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="mobile" data-mask="(999) 999-9999"><?php echo formatPhoneNumber($_mobile); ?></span>
													</p>
												</li>
												<?php } ?>
												<?php if(formatPhoneNumber($_fax) !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="fa fa-fax"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="fax" data-mask="(999) 999-9999"><?php echo formatPhoneNumber($_fax); ?></span>
													</p>
												</li>
												<?php } ?>
												<?php if($_email !=""){ ?>
												<li>
													<p class="text-muted">
														<i class="fa fa-envelope"></i>&nbsp;&nbsp;<span class="txt-color-darken textedit"  data-pname="email"><?php echo $_email; ?></span>
													</p>
												</li>
												<?php } ?>
											</ul>
											<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
												<?php if($_notes !=""){ ?>
											<br>
											<p class="textedit" data-pname="aboutme_details"><?php echo $_notes; ?></p>
												<?php } ?>
											<?php } ?>
											<br>
											<br>
											<?php
											//if($_SESSION["group_id"]==1){
											?>
											<p><a href="javascript:void(0);" id="modal_link<?php echo $tmp_rid; ?>" class="btn btn-primary txt-color-white"> Edit </a></p>
											<div id="u-dialog-message<?php echo $tmp_rid; ?>" title="Edit Profile">
						<form id="checkout-form<?php echo $tmp_rid; ?>" class="smart-form uedit" novalidate="novalidate" method="post" onsubmit="return profileEdit()" enctype="multipart/form-data" autocomplete="off">

							<fieldset>
								<div class="row">
									<section class="col col-6">First Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="fname<?php echo $tmp_rid; ?>" id="fname<?php echo $tmp_rid; ?>" placeholder="First name" value="<?php echo $_firstname; ?>">
										</label>
									</section>
									<section class="col col-6">Last Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="lname<?php echo $tmp_rid; ?>" id="lname<?php echo $tmp_rid; ?>" placeholder="Last name" value="<?php echo $_lastname; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Title
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="title<?php echo $tmp_rid; ?>" id="title<?php echo $tmp_rid; ?>" placeholder="Title" value="<?php echo $_title; ?>">
										</label>
									</section>
									<section class="col col-6">Address
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="address<?php echo $tmp_rid; ?>" id="address<?php echo $tmp_rid; ?>" placeholder="Address" value="<?php echo $_address; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">City
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="city<?php echo $tmp_rid; ?>" id="city<?php echo $tmp_rid; ?>" placeholder="City" value="<?php echo $_city; ?>">
										</label>
									</section>
									<section class="col col-6">State
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="state<?php echo $tmp_rid; ?>" id="state<?php echo $tmp_rid; ?>" placeholder="State" value="<?php echo $_state; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Country
										<label class="select"> <i class="icon-append fa fa-bank"></i>
<select name="country<?php echo $tmp_rid; ?>" id="country<?php echo $tmp_rid; ?>">
	<option value="AF" <?php if($_country=="AF"){echo "selected";} ?>>Afghanistan</option>
	<option value="AX" <?php if($_country=="AX"){echo "selected";} ?>>Åland Islands</option>
	<option value="AL" <?php if($_country=="AL"){echo "selected";} ?>>Albania</option>
	<option value="DZ" <?php if($_country=="DZ"){echo "selected";} ?>>Algeria</option>
	<option value="AS" <?php if($_country=="AS"){echo "selected";} ?>>American Samoa</option>
	<option value="AD" <?php if($_country=="AD"){echo "selected";} ?>>Andorra</option>
	<option value="AO" <?php if($_country=="AO"){echo "selected";} ?>>Angola</option>
	<option value="AI" <?php if($_country=="AI"){echo "selected";} ?>>Anguilla</option>
	<option value="AQ" <?php if($_country=="AQ"){echo "selected";} ?>>Antarctica</option>
	<option value="AG" <?php if($_country=="AG"){echo "selected";} ?>>Antigua and Barbuda</option>
	<option value="AR" <?php if($_country=="AR"){echo "selected";} ?>>Argentina</option>
	<option value="AM" <?php if($_country=="AM"){echo "selected";} ?>>Armenia</option>
	<option value="AW" <?php if($_country=="AW"){echo "selected";} ?>>Aruba</option>
	<option value="AU" <?php if($_country=="AU"){echo "selected";} ?>>Australia</option>
	<option value="AT" <?php if($_country=="AT"){echo "selected";} ?>>Austria</option>
	<option value="AZ" <?php if($_country=="AZ"){echo "selected";} ?>>Azerbaijan</option>
	<option value="BS" <?php if($_country=="BS"){echo "selected";} ?>>Bahamas</option>
	<option value="BH" <?php if($_country=="BH"){echo "selected";} ?>>Bahrain</option>
	<option value="BD" <?php if($_country=="BD"){echo "selected";} ?>>Bangladesh</option>
	<option value="BB" <?php if($_country=="BB"){echo "selected";} ?>>Barbados</option>
	<option value="BY" <?php if($_country=="BY"){echo "selected";} ?>>Belarus</option>
	<option value="BE" <?php if($_country=="BE"){echo "selected";} ?>>Belgium</option>
	<option value="BZ" <?php if($_country=="BZ"){echo "selected";} ?>>Belize</option>
	<option value="BJ" <?php if($_country=="BJ"){echo "selected";} ?>>Benin</option>
	<option value="BM" <?php if($_country=="BM"){echo "selected";} ?>>Bermuda</option>
	<option value="BT" <?php if($_country=="BT"){echo "selected";} ?>>Bhutan</option>
	<option value="BO" <?php if($_country=="BO"){echo "selected";} ?>>Bolivia, Plurinational State of</option>
	<option value="BQ" <?php if($_country=="BQ"){echo "selected";} ?>>Bonaire, Sint Eustatius and Saba</option>
	<option value="BA" <?php if($_country=="BA"){echo "selected";} ?>>Bosnia and Herzegovina</option>
	<option value="BW" <?php if($_country=="BW"){echo "selected";} ?>>Botswana</option>
	<option value="BV" <?php if($_country=="BV"){echo "selected";} ?>>Bouvet Island</option>
	<option value="BR" <?php if($_country=="BR"){echo "selected";} ?>>Brazil</option>
	<option value="IO" <?php if($_country=="IO"){echo "selected";} ?>>British Indian Ocean Territory</option>
	<option value="BN" <?php if($_country=="BN"){echo "selected";} ?>>Brunei Darussalam</option>
	<option value="BG" <?php if($_country=="BG"){echo "selected";} ?>>Bulgaria</option>
	<option value="BF" <?php if($_country=="BF"){echo "selected";} ?>>Burkina Faso</option>
	<option value="BI" <?php if($_country=="BI"){echo "selected";} ?>>Burundi</option>
	<option value="KH" <?php if($_country=="KH"){echo "selected";} ?>>Cambodia</option>
	<option value="CM" <?php if($_country=="CM"){echo "selected";} ?>>Cameroon</option>
	<option value="CA" <?php if($_country=="CA"){echo "selected";} ?>>Canada</option>
	<option value="CV" <?php if($_country=="CV"){echo "selected";} ?>>Cape Verde</option>
	<option value="KY" <?php if($_country=="KY"){echo "selected";} ?>>Cayman Islands</option>
	<option value="CF" <?php if($_country=="CF"){echo "selected";} ?>>Central African Republic</option>
	<option value="TD" <?php if($_country=="TD"){echo "selected";} ?>>Chad</option>
	<option value="CL" <?php if($_country=="CL"){echo "selected";} ?>>Chile</option>
	<option value="CN" <?php if($_country=="CN"){echo "selected";} ?>>China</option>
	<option value="CX" <?php if($_country=="CX"){echo "selected";} ?>>Christmas Island</option>
	<option value="CC" <?php if($_country=="CC"){echo "selected";} ?>>Cocos (Keeling) Islands</option>
	<option value="CO" <?php if($_country=="CO"){echo "selected";} ?>>Colombia</option>
	<option value="KM" <?php if($_country=="KM"){echo "selected";} ?>>Comoros</option>
	<option value="CG" <?php if($_country=="CG"){echo "selected";} ?>>Congo</option>
	<option value="CD" <?php if($_country=="CD"){echo "selected";} ?>>Congo, the Democratic Republic of the</option>
	<option value="CK" <?php if($_country=="CK"){echo "selected";} ?>>Cook Islands</option>
	<option value="CR" <?php if($_country=="CR"){echo "selected";} ?>>Costa Rica</option>
	<option value="CI" <?php if($_country=="CI"){echo "selected";} ?>>Côte d'Ivoire</option>
	<option value="HR" <?php if($_country=="HR"){echo "selected";} ?>>Croatia</option>
	<option value="CU" <?php if($_country=="CU"){echo "selected";} ?>>Cuba</option>
	<option value="CW" <?php if($_country=="CW"){echo "selected";} ?>>Curaçao</option>
	<option value="CY" <?php if($_country=="CY"){echo "selected";} ?>>Cyprus</option>
	<option value="CZ" <?php if($_country=="CZ"){echo "selected";} ?>>Czech Republic</option>
	<option value="DK" <?php if($_country=="DK"){echo "selected";} ?>>Denmark</option>
	<option value="DJ" <?php if($_country=="DJ"){echo "selected";} ?>>Djibouti</option>
	<option value="DM" <?php if($_country=="DM"){echo "selected";} ?>>Dominica</option>
	<option value="DO" <?php if($_country=="DO"){echo "selected";} ?>>Dominican Republic</option>
	<option value="EC" <?php if($_country=="EC"){echo "selected";} ?>>Ecuador</option>
	<option value="EG" <?php if($_country=="EG"){echo "selected";} ?>>Egypt</option>
	<option value="SV" <?php if($_country=="SV"){echo "selected";} ?>>El Salvador</option>
	<option value="GQ" <?php if($_country=="GQ"){echo "selected";} ?>>Equatorial Guinea</option>
	<option value="ER" <?php if($_country=="ER"){echo "selected";} ?>>Eritrea</option>
	<option value="EE" <?php if($_country=="EE"){echo "selected";} ?>>Estonia</option>
	<option value="ET" <?php if($_country=="ET"){echo "selected";} ?>>Ethiopia</option>
	<option value="FK" <?php if($_country=="FK"){echo "selected";} ?>>Falkland Islands (Malvinas)</option>
	<option value="FO" <?php if($_country=="FO"){echo "selected";} ?>>Faroe Islands</option>
	<option value="FJ" <?php if($_country=="FJ"){echo "selected";} ?>>Fiji</option>
	<option value="FI" <?php if($_country=="FI"){echo "selected";} ?>>Finland</option>
	<option value="FR" <?php if($_country=="FR"){echo "selected";} ?>>France</option>
	<option value="GF" <?php if($_country=="GF"){echo "selected";} ?>>French Guiana</option>
	<option value="PF" <?php if($_country=="PF"){echo "selected";} ?>>French Polynesia</option>
	<option value="TF" <?php if($_country=="TF"){echo "selected";} ?>>French Southern Territories</option>
	<option value="GA" <?php if($_country=="GA"){echo "selected";} ?>>Gabon</option>
	<option value="GM" <?php if($_country=="GM"){echo "selected";} ?>>Gambia</option>
	<option value="GE" <?php if($_country=="GE"){echo "selected";} ?>>Georgia</option>
	<option value="DE" <?php if($_country=="DE"){echo "selected";} ?>>Germany</option>
	<option value="GH" <?php if($_country=="GH"){echo "selected";} ?>>Ghana</option>
	<option value="GI" <?php if($_country=="GI"){echo "selected";} ?>>Gibraltar</option>
	<option value="GR" <?php if($_country=="GR"){echo "selected";} ?>>Greece</option>
	<option value="GL" <?php if($_country=="GL"){echo "selected";} ?>>Greenland</option>
	<option value="GD" <?php if($_country=="GD"){echo "selected";} ?>>Grenada</option>
	<option value="GP" <?php if($_country=="GP"){echo "selected";} ?>>Guadeloupe</option>
	<option value="GU" <?php if($_country=="GU"){echo "selected";} ?>>Guam</option>
	<option value="GT" <?php if($_country=="GT"){echo "selected";} ?>>Guatemala</option>
	<option value="GG" <?php if($_country=="GG"){echo "selected";} ?>>Guernsey</option>
	<option value="GN" <?php if($_country=="GN"){echo "selected";} ?>>Guinea</option>
	<option value="GW" <?php if($_country=="GW"){echo "selected";} ?>>Guinea-Bissau</option>
	<option value="GY" <?php if($_country=="GY"){echo "selected";} ?>>Guyana</option>
	<option value="HT" <?php if($_country=="HT"){echo "selected";} ?>>Haiti</option>
	<option value="HM" <?php if($_country=="HM"){echo "selected";} ?>>Heard Island and McDonald Islands</option>
	<option value="VA" <?php if($_country=="VA"){echo "selected";} ?>>Holy See (Vatican City State)</option>
	<option value="HN" <?php if($_country=="HN"){echo "selected";} ?>>Honduras</option>
	<option value="HK" <?php if($_country=="HK"){echo "selected";} ?>>Hong Kong</option>
	<option value="HU" <?php if($_country=="HU"){echo "selected";} ?>>Hungary</option>
	<option value="IS" <?php if($_country=="IS"){echo "selected";} ?>>Iceland</option>
	<option value="IN" <?php if($_country=="IN"){echo "selected";} ?>>India</option>
	<option value="ID" <?php if($_country=="ID"){echo "selected";} ?>>Indonesia</option>
	<option value="IR" <?php if($_country=="IR"){echo "selected";} ?>>Iran, Islamic Republic of</option>
	<option value="IQ" <?php if($_country=="IQ"){echo "selected";} ?>>Iraq</option>
	<option value="IE" <?php if($_country=="IE"){echo "selected";} ?>>Ireland</option>
	<option value="IM" <?php if($_country=="IM"){echo "selected";} ?>>Isle of Man</option>
	<option value="IL" <?php if($_country=="IL"){echo "selected";} ?>>Israel</option>
	<option value="IT" <?php if($_country=="IT"){echo "selected";} ?>>Italy</option>
	<option value="JM" <?php if($_country=="JM"){echo "selected";} ?>>Jamaica</option>
	<option value="JP" <?php if($_country=="JP"){echo "selected";} ?>>Japan</option>
	<option value="JE" <?php if($_country=="JE"){echo "selected";} ?>>Jersey</option>
	<option value="JO" <?php if($_country=="JO"){echo "selected";} ?>>Jordan</option>
	<option value="KZ" <?php if($_country=="KZ"){echo "selected";} ?>>Kazakhstan</option>
	<option value="KE" <?php if($_country=="KE"){echo "selected";} ?>>Kenya</option>
	<option value="KI" <?php if($_country=="KI"){echo "selected";} ?>>Kiribati</option>
	<option value="KP" <?php if($_country=="KP"){echo "selected";} ?>>Korea, Democratic People's Republic of</option>
	<option value="KR" <?php if($_country=="KR"){echo "selected";} ?>>Korea, Republic of</option>
	<option value="KW" <?php if($_country=="KW"){echo "selected";} ?>>Kuwait</option>
	<option value="KG" <?php if($_country=="KG"){echo "selected";} ?>>Kyrgyzstan</option>
	<option value="LA" <?php if($_country=="LA"){echo "selected";} ?>>Lao People's Democratic Republic</option>
	<option value="LV" <?php if($_country=="LV"){echo "selected";} ?>>Latvia</option>
	<option value="LB" <?php if($_country=="LB"){echo "selected";} ?>>Lebanon</option>
	<option value="LS" <?php if($_country=="LS"){echo "selected";} ?>>Lesotho</option>
	<option value="LR" <?php if($_country=="LR"){echo "selected";} ?>>Liberia</option>
	<option value="LY" <?php if($_country=="LY"){echo "selected";} ?>>Libya</option>
	<option value="LI" <?php if($_country=="LI"){echo "selected";} ?>>Liechtenstein</option>
	<option value="LT" <?php if($_country=="LT"){echo "selected";} ?>>Lithuania</option>
	<option value="LU" <?php if($_country=="MU"){echo "selected";} ?>>Luxembourg</option>
	<option value="MO" <?php if($_country=="MO"){echo "selected";} ?>>Macao</option>
	<option value="MK" <?php if($_country=="MK"){echo "selected";} ?>>Macedonia, the former Yugoslav Republic of</option>
	<option value="MG" <?php if($_country=="MG"){echo "selected";} ?>>Madagascar</option>
	<option value="MW" <?php if($_country=="MW"){echo "selected";} ?>>Malawi</option>
	<option value="MY" <?php if($_country=="MY"){echo "selected";} ?>>Malaysia</option>
	<option value="MV" <?php if($_country=="MV"){echo "selected";} ?>>Maldives</option>
	<option value="ML" <?php if($_country=="ML"){echo "selected";} ?>>Mali</option>
	<option value="MT" <?php if($_country=="MT"){echo "selected";} ?>>Malta</option>
	<option value="MH" <?php if($_country=="MH"){echo "selected";} ?>>Marshall Islands</option>
	<option value="MQ" <?php if($_country=="MQ"){echo "selected";} ?>>Martinique</option>
	<option value="MR" <?php if($_country=="MR"){echo "selected";} ?>>Mauritania</option>
	<option value="MU" <?php if($_country=="MU"){echo "selected";} ?>>Mauritius</option>
	<option value="YT" <?php if($_country=="YT"){echo "selected";} ?>>Mayotte</option>
	<option value="MX" <?php if($_country=="MX"){echo "selected";} ?>>Mexico</option>
	<option value="FM" <?php if($_country=="FM"){echo "selected";} ?>>Micronesia, Federated States of</option>
	<option value="MD" <?php if($_country=="MD"){echo "selected";} ?>>Moldova, Republic of</option>
	<option value="MC" <?php if($_country=="MC"){echo "selected";} ?>>Monaco</option>
	<option value="MN" <?php if($_country=="MN"){echo "selected";} ?>>Mongolia</option>
	<option value="ME" <?php if($_country=="ME"){echo "selected";} ?>>Montenegro</option>
	<option value="MS" <?php if($_country=="MS"){echo "selected";} ?>>Montserrat</option>
	<option value="MA" <?php if($_country=="MA"){echo "selected";} ?>>Morocco</option>
	<option value="MZ" <?php if($_country=="MZ"){echo "selected";} ?>>Mozambique</option>
	<option value="MM" <?php if($_country=="MM"){echo "selected";} ?>>Myanmar</option>
	<option value="NA" <?php if($_country=="NA"){echo "selected";} ?>>Namibia</option>
	<option value="NR" <?php if($_country=="NR"){echo "selected";} ?>>Nauru</option>
	<option value="NP" <?php if($_country=="NP"){echo "selected";} ?>>Nepal</option>
	<option value="NL" <?php if($_country=="NL"){echo "selected";} ?>>Netherlands</option>
	<option value="NC" <?php if($_country=="NC"){echo "selected";} ?>>New Caledonia</option>
	<option value="NZ" <?php if($_country=="NZ"){echo "selected";} ?>>New Zealand</option>
	<option value="NI" <?php if($_country=="NI"){echo "selected";} ?>>Nicaragua</option>
	<option value="NE" <?php if($_country=="NE"){echo "selected";} ?>>Niger</option>
	<option value="NG" <?php if($_country=="NG"){echo "selected";} ?>>Nigeria</option>
	<option value="NU" <?php if($_country=="NU"){echo "selected";} ?>>Niue</option>
	<option value="NF" <?php if($_country=="NF"){echo "selected";} ?>>Norfolk Island</option>
	<option value="MP" <?php if($_country=="MO"){echo "selected";} ?>>Northern Mariana Islands</option>
	<option value="NO" <?php if($_country=="NO"){echo "selected";} ?>>Norway</option>
	<option value="OM" <?php if($_country=="OM"){echo "selected";} ?>>Oman</option>
	<option value="PK" <?php if($_country=="PK"){echo "selected";} ?>>Pakistan</option>
	<option value="PW" <?php if($_country=="PW"){echo "selected";} ?>>Palau</option>
	<option value="PS" <?php if($_country=="PS"){echo "selected";} ?>>Palestinian Territory, Occupied</option>
	<option value="PA" <?php if($_country=="PA"){echo "selected";} ?>>Panama</option>
	<option value="PG" <?php if($_country=="PG"){echo "selected";} ?>>Papua New Guinea</option>
	<option value="PY" <?php if($_country=="PY"){echo "selected";} ?>>Paraguay</option>
	<option value="PE" <?php if($_country=="PE"){echo "selected";} ?>>Peru</option>
	<option value="PH" <?php if($_country=="PH"){echo "selected";} ?>>Philippines</option>
	<option value="PN" <?php if($_country=="PN"){echo "selected";} ?>>Pitcairn</option>
	<option value="PL" <?php if($_country=="PL"){echo "selected";} ?>>Poland</option>
	<option value="PT" <?php if($_country=="PT"){echo "selected";} ?>>Portugal</option>
	<option value="PR" <?php if($_country=="PR"){echo "selected";} ?>>Puerto Rico</option>
	<option value="QA" <?php if($_country=="QA"){echo "selected";} ?>>Qatar</option>
	<option value="RE" <?php if($_country=="RE"){echo "selected";} ?>>Réunion</option>
	<option value="RO" <?php if($_country=="RO"){echo "selected";} ?>>Romania</option>
	<option value="RU" <?php if($_country=="RU"){echo "selected";} ?>>Russian Federation</option>
	<option value="RW" <?php if($_country=="RW"){echo "selected";} ?>>Rwanda</option>
	<option value="BL" <?php if($_country=="BL"){echo "selected";} ?>>Saint Barthélemy</option>
	<option value="SH" <?php if($_country=="SH"){echo "selected";} ?>>Saint Helena, Ascension and Tristan da Cunha</option>
	<option value="KN" <?php if($_country=="KN"){echo "selected";} ?>>Saint Kitts and Nevis</option>
	<option value="LC" <?php if($_country=="LC"){echo "selected";} ?>>Saint Lucia</option>
	<option value="MF" <?php if($_country=="MF"){echo "selected";} ?>>Saint Martin (French part)</option>
	<option value="PM" <?php if($_country=="PM"){echo "selected";} ?>>Saint Pierre and Miquelon</option>
	<option value="VC" <?php if($_country=="VC"){echo "selected";} ?>>Saint Vincent and the Grenadines</option>
	<option value="WS" <?php if($_country=="WS"){echo "selected";} ?>>Samoa</option>
	<option value="SM" <?php if($_country=="SM"){echo "selected";} ?>>San Marino</option>
	<option value="ST" <?php if($_country=="ST"){echo "selected";} ?>>Sao Tome and Principe</option>
	<option value="SA" <?php if($_country=="SA"){echo "selected";} ?>>Saudi Arabia</option>
	<option value="SN" <?php if($_country=="SN"){echo "selected";} ?>>Senegal</option>
	<option value="RS" <?php if($_country=="RS"){echo "selected";} ?>>Serbia</option>
	<option value="SC" <?php if($_country=="SC"){echo "selected";} ?>>Seychelles</option>
	<option value="SL" <?php if($_country=="SL"){echo "selected";} ?>>Sierra Leone</option>
	<option value="SG" <?php if($_country=="SG"){echo "selected";} ?>>Singapore</option>
	<option value="SX" <?php if($_country=="SX"){echo "selected";} ?>>Sint Maarten (Dutch part)</option>
	<option value="SK" <?php if($_country=="SK"){echo "selected";} ?>>Slovakia</option>
	<option value="SI" <?php if($_country=="SI"){echo "selected";} ?>>Slovenia</option>
	<option value="SB" <?php if($_country=="SB"){echo "selected";} ?>>Solomon Islands</option>
	<option value="SO" <?php if($_country=="SO"){echo "selected";} ?>>Somalia</option>
	<option value="ZA" <?php if($_country=="ZA"){echo "selected";} ?>>South Africa</option>
	<option value="GS" <?php if($_country=="GS"){echo "selected";} ?>>South Georgia and the South Sandwich Islands</option>
	<option value="SS" <?php if($_country=="SS"){echo "selected";} ?>>South Sudan</option>
	<option value="ES" <?php if($_country=="ES"){echo "selected";} ?>>Spain</option>
	<option value="LK" <?php if($_country=="LK"){echo "selected";} ?>>Sri Lanka</option>
	<option value="SD" <?php if($_country=="SD"){echo "selected";} ?>>Sudan</option>
	<option value="SR" <?php if($_country=="SR"){echo "selected";} ?>>Suriname</option>
	<option value="SJ" <?php if($_country=="SJ"){echo "selected";} ?>>Svalbard and Jan Mayen</option>
	<option value="SZ" <?php if($_country=="SZ"){echo "selected";} ?>>Swaziland</option>
	<option value="SE" <?php if($_country=="SE"){echo "selected";} ?>>Sweden</option>
	<option value="CH" <?php if($_country=="CH"){echo "selected";} ?>>Switzerland</option>
	<option value="SY" <?php if($_country=="SY"){echo "selected";} ?>>Syrian Arab Republic</option>
	<option value="TW" <?php if($_country=="TW"){echo "selected";} ?>>Taiwan, Province of China</option>
	<option value="TJ" <?php if($_country=="TJ"){echo "selected";} ?>>Tajikistan</option>
	<option value="TZ" <?php if($_country=="TZ"){echo "selected";} ?>>Tanzania, United Republic of</option>
	<option value="TH" <?php if($_country=="TH"){echo "selected";} ?>>Thailand</option>
	<option value="TL" <?php if($_country=="TL"){echo "selected";} ?>>Timor-Leste</option>
	<option value="TG" <?php if($_country=="TG"){echo "selected";} ?>>Togo</option>
	<option value="TK" <?php if($_country=="TK"){echo "selected";} ?>>Tokelau</option>
	<option value="TO" <?php if($_country=="TO"){echo "selected";} ?>>Tonga</option>
	<option value="TT" <?php if($_country=="TT"){echo "selected";} ?>>Trinidad and Tobago</option>
	<option value="TN" <?php if($_country=="TN"){echo "selected";} ?>>Tunisia</option>
	<option value="TR" <?php if($_country=="TR"){echo "selected";} ?>>Turkey</option>
	<option value="TM" <?php if($_country=="TM"){echo "selected";} ?>>Turkmenistan</option>
	<option value="TC" <?php if($_country=="TC"){echo "selected";} ?>>Turks and Caicos Islands</option>
	<option value="TV" <?php if($_country=="TV"){echo "selected";} ?>>Tuvalu</option>
	<option value="UG" <?php if($_country=="UG"){echo "selected";} ?>>Uganda</option>
	<option value="UA" <?php if($_country=="UA"){echo "selected";} ?>>Ukraine</option>
	<option value="AE" <?php if($_country=="AE"){echo "selected";} ?>>United Arab Emirates</option>
	<option value="GB" <?php if($_country=="GB"){echo "selected";} ?>>United Kingdom</option>
	<option value="US" <?php if($_country=="US" || $_country==""){echo "selected";} ?>>United States</option>
	<option value="UM" <?php if($_country=="UM"){echo "selected";} ?>>United States Minor Outlying Islands</option>
	<option value="UY" <?php if($_country=="UY"){echo "selected";} ?>>Uruguay</option>
	<option value="UZ" <?php if($_country=="UZ"){echo "selected";} ?>>Uzbekistan</option>
	<option value="VU" <?php if($_country=="VU"){echo "selected";} ?>>Vanuatu</option>
	<option value="VE" <?php if($_country=="VE"){echo "selected";} ?>>Venezuela, Bolivarian Republic of</option>
	<option value="VN" <?php if($_country=="VN"){echo "selected";} ?>>Viet Nam</option>
	<option value="VG" <?php if($_country=="VG"){echo "selected";} ?>>Virgin Islands, British</option>
	<option value="VI" <?php if($_country=="VI"){echo "selected";} ?>>Virgin Islands, U.S.</option>
	<option value="WF" <?php if($_country=="WF"){echo "selected";} ?>>Wallis and Futuna</option>
	<option value="EH" <?php if($_country=="EH"){echo "selected";} ?>>Western Sahara</option>
	<option value="YE" <?php if($_country=="YE"){echo "selected";} ?>>Yemen</option>
	<option value="ZM" <?php if($_country=="ZM"){echo "selected";} ?>>Zambia</option>
	<option value="ZW" <?php if($_country=="ZW"){echo "selected";} ?>>Zimbabwe</option>

</select>
										</label>

									</section>
									<section class="col col-6">Zip
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="zip<?php echo $tmp_rid; ?>" id="zip<?php echo $tmp_rid; ?>" placeholder="Zip" value="<?php echo $_zip; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Phone
										<label class="input"> <i class="icon-prepend fa fa-phone"></i>
											<input type="tel" name="phone<?php echo $tmp_rid; ?>" id="phone<?php echo $tmp_rid; ?>" placeholder="Phone" data-mask="(999) 999-9999" value="<?php echo $_phone; ?>">
										</label>
									</section>
									<section class="col col-6">
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Mobile
										<label class="input"> <i class="icon-prepend fa fa-tablet"></i>
											<input type="tel" name="mobile<?php echo $tmp_rid; ?>" id="mobile<?php echo $tmp_rid; ?>" placeholder="Mobile" data-mask="(999) 999-9999" value="<?php echo $_mobile; ?>">
										</label>
									</section>
									<section class="col col-6">Fax
										<label class="input"> <i class="icon-prepend fa fa-fax"></i>
											<input type="tel" name="fax<?php echo $tmp_rid; ?>" id="fax<?php echo $tmp_rid; ?>" placeholder="Fax" data-mask="(999) 999-9999" value="<?php echo $_fax; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Email Address<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)');?>
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input type="email" <?php echo (($_SESSION["group_id"]==1)?'name="newemail'.$tmp_rid.'" id="newemail'.$tmp_rid.'"':'readonly="true"'); ?> placeholder="Email Address" value="<?php echo $_email; ?>" >
											<input type="hidden" <?php echo (($_SESSION["group_id"]==1)?'name="email'.$tmp_rid.'" id="email'.$tmp_rid.'"':'readonly="true"'); ?> placeholder="Email Address" value="<?php echo $_email; ?>">
										</label>
									</section>
									<section class="col col-6">Profile Image
										<label for="file" class="input input-file">
											<div class="button"><input type="file" name="file<?php echo $tmp_rid; ?>" id="file<?php echo $tmp_rid; ?>" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Profile Image" readonly="" value="" id="file-text<?php echo $tmp_rid; ?>">
										</label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-6">DataHub 360 Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="password<?php echo $tmp_rid; ?>" placeholder="DataHub 360 Password" id="password<?php echo $tmp_rid; ?>" autocomplete="off" value="">
										</label>
									</section>
									<section class="col col-6">DataHub 360 Confirm Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="passwordConfirm<?php echo $tmp_rid; ?>" id="passwordConfirm<?php echo $tmp_rid; ?>" placeholder="DataHub 360 Confirm password" autocomplete="off" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">CSR Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="editaccuviouser<?php echo $tmp_rid; ?>" id="editaccuviouser<?php echo $tmp_rid; ?>" placeholder="CSR User" value="<?php echo $_accuvio_user; ?>">
										</label>
									</section>
									<section class="col col-6">CSR Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="editaccuviopass<?php echo $tmp_rid; ?>" id="editaccuviopass<?php echo $tmp_rid; ?>" placeholder="CSR Pass" value="" autocomplete="off">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="editcapturisuser<?php echo $tmp_rid; ?>" id="editcapturisuser<?php echo $tmp_rid; ?>" placeholder="UBM User" value="<?php echo $_capturis_user; ?>">
										</label>
									</section>
									<section class="col col-6">UBM Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="editcapturispass<?php echo $tmp_rid; ?>" id="editcapturispass<?php echo $tmp_rid; ?>" placeholder="UBM Pass" value="" autocomplete="off">
										<input type="hidden" name="editusr<?php echo $tmp_rid; ?>" id="editusr<?php echo $tmp_rid; ?>" value="<?php echo $userid; ?>">
										<input type="hidden" name="editrole<?php echo $tmp_rid; ?>" id="editrole<?php echo $tmp_rid; ?>" value="<?php echo (($_SESSION["group_id"]==1)?'admin':'default');?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM Archive Username
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="editcapturisarchiveuser<?php echo $tmp_rid; ?>" id="editcapturisarchiveuser<?php echo $tmp_rid; ?>" placeholder="UBM Archive User" value="<?php echo $_capturis_archive_user; ?>">
										</label>
									</section>
									<section class="col col-6">UBM Archive Password
										<label class="input"> <i class="icon-prepend fa fa-lock"></i>
											<input type="password" name="editcapturisarchivepass<?php echo $tmp_rid; ?>" id="editcapturisarchivepass<?php echo $tmp_rid; ?>" placeholder="UBM Archive Pass" value="" autocomplete="off">
										<input type="hidden" name="editusr<?php echo $tmp_rid; ?>" id="editusr<?php echo $tmp_rid; ?>" value="<?php echo $userid; ?>">
										<input type="hidden" name="editrole<?php echo $tmp_rid; ?>" id="editrole<?php echo $tmp_rid; ?>" value="<?php echo (($_SESSION["group_id"]==1)?'admin':'default');?>">
										</label>
									</section>
								</div>
							</fieldset>
							<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2 || $_SESSION["group_id"]==5){ ?>
							<fieldset>
								<div class="row">
									<section class="col col-6">Company logo
										<label for="companylogo" class="input input-file">
											<div class="button"><input type="file" name="companylogo<?php echo $tmp_rid; ?>" id="companylogo<?php echo $tmp_rid; ?>" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Company Logo" readonly="" value="" id="company-logo<?php echo $tmp_rid; ?>">
										</label>
									</section>
									<section class="col col-6">Status<?php echo (($_SESSION["group_id"]==1 || $_SESSION["group_id"]==5)?'':' (<i>readonly</i>)'); ?>
										<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==5 ){ ?>
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="status<?php echo $tmp_rid; ?>" id="status<?php echo $tmp_rid; ?>">
												<option value="1" <?php if(@trim($_cstatus)==1){echo 'SELECTED="SELECTED"';} ?>>Active</option>
												<option value="0" <?php if(@trim($_cstatus)==0){echo 'SELECTED="SELECTED"';} ?>>Disabled</option>
												<option value="2" <?php if(@trim($_cstatus)==2){echo 'SELECTED="SELECTED"';} ?>>Locked Out</option>
												<option value="3" <?php if(@trim($_cstatus)==3){echo 'SELECTED="SELECTED"';} ?>>Password Reset</option>
											</select> <i></i> </label>
									<?php }else{ ?>
											<input type="text" placeholder="Status" value="<?php if(@trim($_cstatus)=='1'){ echo 'Active';}elseif(@trim($_cstatus)=='0'){echo 'Disabled';}elseif(@trim($_cstatus)=='2'){echo 'Locked Out';}elseif(@trim($_cstatus)=='3'){echo 'Password Reset';} ?>">
									<?php } ?>
									</section>
								</div>
							</fieldset>
							<?php } ?>
							<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
							<fieldset>
								<section>Vervantis Notes<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)'); ?>
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" <?php if($_SESSION["group_id"]==1){?> name="notes<?php echo $tmp_rid; ?>" id="notes<?php echo $tmp_rid; ?>" <?php }else{ ?> readonly="" <?php } ?> placeholder="Vervantis Notes"><?php echo $_notes; ?></textarea>
									</label>
								</section>
							</fieldset>
							<?php } ?>
							<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
							<fieldset>
								<div class="row">
									<section class="col col-6">Gender<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)'); ?>
									<?php if($_SESSION["group_id"]==1){ ?>
										<label class="select"> <i class="icon-append fa fa-male"></i>
											<select name="gender<?php echo $tmp_rid; ?>" id="gender<?php echo $tmp_rid; ?>">
												<option value="" selected="" disabled="">Gender</option>
												<option value="M" <?php if(@trim($_cgender)=='M'){echo 'SELECTED="SELECTED"';} ?>>Male</option>
												<option value="F" <?php if(@trim($_cgender)=='F'){echo 'SELECTED="SELECTED"';} ?>>Female</option>
												<!--<option value="3">Prefer not to answer</option>-->
											</select> <i></i> </label>
									<?php }else{ ?>
											<input type="text" placeholder="Gender" value="<?php if(@trim($_cgender)=='M'){ echo 'Male';}elseif(@trim($_cgender)=='F'){echo 'Female';} ?>">
									<?php } ?>
									</section>
									<section class="col col-6">User Groups<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)'); ?>
									<?php if(1==2){ ?>
										<label class="input"> <i class="icon-append fa fa-group"></i>
											<input type="number" <?php if($_SESSION["group_id"]==1){ ?> name="usergroups<?php echo $tmp_rid; ?>" id="usergroups<?php echo $tmp_rid; ?>" <?php }else{?> readonly="" <?php } ?> placeholder="User Groups" value="<?php echo $_cusergroups; ?>">
										</label>
									<?php } ?>
									<?php if($_SESSION["group_id"]==1){ ?>
										<label class="select"> <i class="icon-append fa fa-male"></i>
											<select name="usergroups<?php echo $tmp_rid; ?>" id="usergroups<?php echo $tmp_rid; ?>">
												<option value="" selected="" disabled="">User Groups</option>
												<option value="1" <?php if(@trim($_cusergroups)=='1'){echo 'SELECTED="SELECTED"';} ?>>Vervantis Administrator</option>
												<option value="2" <?php if(@trim($_cusergroups)=='2'){echo 'SELECTED="SELECTED"';} ?>>Vervantis Employee</option>
												<option value="3" <?php if(@trim($_cusergroups)=='3'){echo 'SELECTED="SELECTED"';} ?>>Client</option>
												<option value="4" <?php if(@trim($_cusergroups)=='4'){echo 'SELECTED="SELECTED"';} ?>>Vendor</option>
												<option value="5" <?php if(@trim($_cusergroups)=='5'){echo 'SELECTED="SELECTED"';} ?>>Client Administrator</option>
												<option value="6" <?php if(@trim($_cusergroups)=='6'){echo 'SELECTED="SELECTED"';} ?>>Sub Contractors</option>
											</select> <i></i> </label>
									<?php }else{ ?>
											<input type="text" placeholder="User Groups" value="<?php echo $_cusergroups; ?>">
									<?php } ?>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Disable Date<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)'); ?>
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<?php if($_SESSION["group_id"]==1){ ?>
											<input type="text" name="disabledate<?php echo $tmp_rid; ?>" id="disabledate<?php echo $tmp_rid; ?>" placeholder="Disable Date" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y",strtotime($_csdisabledate)); ?>">
											<?php }else if($_SESSION["group_id"]==2 || $_SESSION["group_id"]==5){?>
											<input type="text" placeholder="Disable Date" readonly="true" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y",strtotime($_csdisabledate)); ?>">
											<?php } ?>
										</label>
									</section>
									<section class="col col-6">Company	<?php echo (($_SESSION["group_id"]==1)?'':' (<i>readonly</i>)'); ?>
											<?php if($_SESSION["group_id"]==1){ ?>
											<label class="select"> <i class="icon-append fa fa-building"></i>
											<select name="company<?php echo $tmp_rid; ?>" id="company<?php echo $tmp_rid; ?>" placeholder="Company" class="">
												<option value="">Select Company</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company')){

//('SELECT id,company_name FROM company')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__company);
														while($stmt->fetch()){
															echo "<option value='".$__id."' ".($_company_id == $__id?"SELECTED='SELECTED'":'').">".$__company."</option>";
														}
													}
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
											?>
											</select>
											</label>
											<?php }else{ ?>
											<input type="text" placeholder="Company" readonly="" value="<?php echo $_company; ?>">
									<?php } ?>
									</section>
								</div>
							</fieldset>
							<?php } ?>
							<footer>
								<button type="submit" class="btn btn-primary" id="profile-submit<?php echo $tmp_rid; ?>">
									Save
								</button>
								<button type="button" class="btn" id="profile-cancel<?php echo $tmp_rid; ?>">
									Close
								</button>
							</footer>
						</form>
											</div>

											<div id="dialog-message-banner<?php echo $tmp_rid; ?>" title="Edit Banner">
						<form id="checkout-form-banner<?php echo $tmp_rid; ?>" class="smart-form" novalidate="novalidate" method="post" onsubmit="return profileEdit()" enctype="multipart/form-data">

							<fieldset>
								<div class="row">
									<section class="col col-12">Profile Banner
										<label for="file" class="input input-file">
											<input class="form-control bcolorpicker" placeholder="Banner" name="banner-name<?php echo $tmp_rid; ?>" id="banner-name<?php echo $tmp_rid; ?>" type="text" value="<?php echo $_banner; ?>">
											<input type="hidden" name="editbusr<?php echo $tmp_rid; ?>" id="editbusr<?php echo $tmp_rid; ?>" value="<?php echo $userid; ?>">
										</label>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="button" class="btn" id="profile-banner-cancel<?php echo $tmp_rid; ?>">
									Close
								</button>
								<button type="submit" class="btn btn-primary" id="profile-banner-submit<?php echo $tmp_rid; ?>">
									Save
								</button>
							</footer>
						</form>
											</div>

											<?php //} ?>
											</div>
											<div class="col-sm-4">
											<?php
												$_cimage=checks3img(md5($_company_id).".png","profiles/company/logo/","blank-logo.png");
												if($_cimage==false){$_cimage="";}

											?>
												<img src="<?php echo $_cimage; ?>" class="company-logo-dis">
											</div>
											</div>
										</div>
									</div>

								</div>

							</div>
							<!-- end row -->
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script src="assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
$(document).ready(function() {
	/*$('#datePicker')
	.datepicker({
		format: 'mm/dd/yyyy'
	}).on('changeDate', function(e) {
        $('#profileForm').formValidation('revalidateField', 'birthdate<?php echo $tmp_rid; ?>');
    });
	*/
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
	<?php if($_SESSION["group_id"]==1){ ?>

		$('#newemail<?php echo $tmp_rid; ?>').on('change', function() {
			if($("#email<?php echo $tmp_rid; ?>").val() != $("#newemail<?php echo $tmp_rid; ?>").val()){
				if (confirm('Do you want to replace \n'+$("#email<?php echo $tmp_rid; ?>").val()+'\n with \n'+$("#newemail<?php echo $tmp_rid; ?>").val())) {

				} else {
					$('#newemail<?php echo $tmp_rid; ?>').val($("#email<?php echo $tmp_rid; ?>").val());
				}
			}
	  });
	<?php } ?>
});



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

				/*
		 * COLOR PICKER
		 */
		loadScript("./assets/js/plugin/colorpicker/bootstrap-colorpicker.min.js", initializeColorpicker);

		function initializeColorpicker() {

			if($('.colorpicker.dropdown-menu').length){
				$('.colorpicker.dropdown-menu').remove();
			}

		    $('.bcolorpicker').colorpicker();
		}


		$('#modal_link<?php echo $tmp_rid; ?>').on( "click", function() {
			$('#u-dialog-message<?php echo $tmp_rid; ?>').dialog('open');
			return false;
		});

		$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog({
			autoOpen : false,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Profile</h4></div>",
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
				$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
				$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
				$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
				$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
				parent.loadusermenu(<?php echo $userid; ?>,"");
			}
		});

		$('#profile-cancel<?php echo $tmp_rid; ?>').on( "click", function() {
			$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
			$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
			$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog("close");
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
			parent.loadusermenu(<?php echo $userid; ?>,"");
		});



		var $checkoutForm = $('#checkout-form<?php echo $tmp_rid; ?>').validate({
		// Rules for form validation
			rules : {
				fname<?php echo $tmp_rid; ?> : {
					required : true
				},
				lname<?php echo $tmp_rid; ?> : {
					required : true
				},
				<?php if($_SESSION["group_id"]==1){ ?>email<?php echo $tmp_rid; ?> : {
					required : true,
					email : true
				},
				gender<?php echo $tmp_rid; ?> : {
					required : true
				},
				company<?php echo $tmp_rid; ?> : {
					required : true
				},<?php } ?>
				password<?php echo $tmp_rid; ?> : {
					required : false,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true
				},
				passwordConfirm<?php echo $tmp_rid; ?> : {
					required : false,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true,
					equalTo : '#password<?php echo $tmp_rid; ?>'
				}<?php if($_SESSION["group_id"]==1){ ?>,
				usergroups<?php echo $tmp_rid; ?> : {
					required : true,
					digits : true
				}<?php } ?>
			},

			// Messages for form validation
			messages : {
				fname<?php echo $tmp_rid; ?> : {
					required : 'Please enter your first name'
				},
				lname<?php echo $tmp_rid; ?> : {
					required : 'Please enter your last name'
				},
				<?php if($_SESSION["group_id"]==1){ ?>email<?php echo $tmp_rid; ?> : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				gender<?php echo $tmp_rid; ?> : {
					required : 'Please enter your gender'
				},
				company<?php echo $tmp_rid; ?> : {
					required : 'Select company'
				},<?php } ?>
				password<?php echo $tmp_rid; ?> : {
					required : 'Please enter your password'
				},
				passwordConfirm<?php echo $tmp_rid; ?> : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as confirm password'
				}<?php if($_SESSION["group_id"]==1){ ?>,usergroups<?php echo $tmp_rid; ?> : {
					required : 'Please select usergroup'
				}<?php } ?>
			},
			// Ajax form submition
			submitHandler : function(form) {
				/*$(form).ajaxSubmit({
					success : function() {
						$("#contact-form").addClass('submited');
					}
				});*/
				var formData = new FormData();
				formData.append('fname', $("#fname<?php echo $tmp_rid; ?>").val());
				formData.append('lname', $("#lname<?php echo $tmp_rid; ?>").val());
				formData.append('title', $("#title<?php echo $tmp_rid; ?>").val());
				formData.append('address', $("#address<?php echo $tmp_rid; ?>").val());
				formData.append('city', $("#city<?php echo $tmp_rid; ?>").val());
				formData.append('state', $("#state<?php echo $tmp_rid; ?>").val());
				formData.append('country', $("#country<?php echo $tmp_rid; ?>").val());
				formData.append('zip', $("#zip<?php echo $tmp_rid; ?>").val());
				<?php if($_SESSION["group_id"]==1){ ?>formData.append('email', $("#email<?php echo $tmp_rid; ?>").val());formData.append('newemail', $("#newemail<?php echo $tmp_rid; ?>").val());<?php } ?>
				formData.append('phone', $("#phone<?php echo $tmp_rid; ?>").val());
				formData.append('mobile', $("#mobile<?php echo $tmp_rid; ?>").val());
				formData.append('fax', $("#fax<?php echo $tmp_rid; ?>").val());
				<?php if($_SESSION["group_id"]==1){ ?>formData.append('gender', $("#gender<?php echo $tmp_rid; ?>").val());
				formData.append('disabledate', $("#disabledate<?php echo $tmp_rid; ?>").val());
				formData.append('company', $("#company<?php echo $tmp_rid; ?>").val());<?php } ?>
				formData.append('password', $("#password<?php echo $tmp_rid; ?>").val());
				formData.append('accuviouser', $("#editaccuviouser<?php echo $tmp_rid; ?>").val());
				formData.append('accuviopass', $("#editaccuviopass<?php echo $tmp_rid; ?>").val());
				formData.append('capturisuser', $("#editcapturisuser<?php echo $tmp_rid; ?>").val());
				formData.append('capturispass', $("#editcapturispass<?php echo $tmp_rid; ?>").val());
				formData.append('capturisarchiveuser', $("#editcapturisarchiveuser<?php echo $tmp_rid; ?>").val());
				formData.append('capturisarchivepass', $("#editcapturisarchivepass<?php echo $tmp_rid; ?>").val());
				<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==5){ ?>
				formData.append('status', $("#status<?php echo $tmp_rid; ?>").val());
				<?php } ?>
				<?php if($_SESSION["group_id"]==1){ ?>
				formData.append('usergroups', $("#usergroups<?php echo $tmp_rid; ?>").val());
				formData.append('notes', $("#notes<?php echo $tmp_rid; ?>").val());
				<?php } ?>
				formData.append('usr', $("#editusr<?php echo $tmp_rid; ?>").val());
				formData.append('role', $("#editrole<?php echo $tmp_rid; ?>").val());
				formData.append('p', ajaxformhash($("#password<?php echo $tmp_rid; ?>").val()));
				formData.append('file', $("#file<?php echo $tmp_rid; ?>")[0].files[0]);
				<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2 || $_SESSION["group_id"]==5){ ?>
				formData.append('companylogo', $("#companylogo<?php echo $tmp_rid; ?>")[0].files[0]);
				<?php } ?>

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
								/**************EDIT IT ********************/
								$.smallBox({
									title : "Changes Saved!",
									content:"",
									color : "#296191",
									timeout: 2000
								}, function() {
									$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
									$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
									$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog("close");
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
									parent.loadusermenu(<?php echo $userid; ?>,results.image);
									parent.$("#dtable").html('');
									parent.$('#dtable').load('assets/ajax/user-pedit.php');
								});

							}else{
								if(results.error=="The new email address you entered is not valid") alert(results.error);
								else if(results.error=="The new email address already exists. Please enter another one.") alert(results.error);
								else if(results.error=="The email address you entered is not valid") alert(results.error);
								else if(results.error=="Invalid file Size or Type") alert(results.error);
								else alert("A system error has occurred.  Please try again later.");
							}
						}else{
							alert("A system error has occurred.  Please try again later.");
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


		$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog({
			autoOpen : false,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Banner</h4></div>",
			close : function(){
				$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
				$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
				$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
				$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
				$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
				parent.loadusermenu(<?php echo $userid; ?>,"");
			}
		});


		$('#change-banner<?php echo $tmp_rid; ?>').on( "click", function() {
			$('#dialog-message-banner<?php echo $tmp_rid; ?>').dialog('open');
			return false;
		});

		$('#profile-banner-cancel<?php echo $tmp_rid; ?>').on( "click", function() {
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog("close");
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
			$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
			$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
			$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
			$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
			parent.loadusermenu(<?php echo $userid; ?>,"");
		});


		var $checkoutForm = $('#checkout-form-banner<?php echo $tmp_rid; ?>').validate({
		// Rules for form validation
			rules : {},

			// Messages for form validation
			messages : {},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('usr', $("#editbusr<?php echo $tmp_rid; ?>").val());
				formData.append('banner-edit', $("#editbusr<?php echo $tmp_rid; ?>").val());
				formData.append('banner-name', $("#banner-name<?php echo $tmp_rid; ?>").val());

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
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog("close");
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").dialog('destroy');
									$("#dialog-message-banner<?php echo $tmp_rid; ?>").remove();
									$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog("close");
									$("#u-dialog-message<?php echo $tmp_rid; ?>").dialog('destroy');
									$("#u-dialog-message<?php echo $tmp_rid; ?>").remove();
									parent.loadusermenu(<?php echo $userid; ?>,"");
								});
							}else{
								alert(results.error);
							}
						}else{
							alert("A system error has occurred.  Please try again later.");
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

		$('#companylogo<?php echo $tmp_rid; ?>').change( function() {
			var cfilename = $(this).val().replace(/C:\\fakepath\\/i, '');
			$('#company-logo<?php echo $tmp_rid; ?>').val( cfilename );
		});

		$('#file<?php echo $tmp_rid; ?>').change( function() {
			var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
			$('#file-text<?php echo $tmp_rid; ?>').val( filename );
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

	function profileEdit(){
	}
</script>
