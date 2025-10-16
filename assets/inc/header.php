<!DOCTYPE html>
<html lang="en-us" <?php echo implode(' ', array_map(function($prop, $value) {
			return $prop.'="'.$value.'"';
		}, array_keys($page_html_prop), $page_html_prop)) ;?>>
	<head>
<?php
if(login_check($mysqli) !== true)
	header("Location: login.php");

//if($_SESSION['user_id'] != 1) die();
	$_userimage="";
	$_gender="M";
	//$_username=(isset($_SESSION["username"])?$_SESSION["username"]:"");
   if ($stmt = $mysqli->prepare('SELECT gender,sound FROM `user` where user_id='.$_SESSION["user_id"].' LIMIT 1')) {

//('SELECT gender FROM `user` where id='.$_SESSION["user_id"].' LIMIT 1')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
                $stmt->bind_result($_gender,$sound_ar);
                $stmt->fetch();
		}
	}
	
	$u_id=md5($_SESSION['user_id']);


	$_userimage=checks3img($u_id.".png","profiles/users/profile image/",(($_gender == "M" || @trim($_gender == ""))?"male.png":"female.png"));
	if($_userimage==false){$_userimage="";}

	/*if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".$u_id.".png") and ($_gender == "M" || @trim($_gender == "")))
		$_userimage="male.png";
	elseif(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/profiles/users/profile\x20image/".$u_id.".png") and $_gender == "F")
		$_userimage="female.png";
	else
		$_userimage=$u_id.".png";*/

	$_username=ucwords(strtolower($_SESSION['fullname']));

?>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> <?php echo $page_title != "" ? $page_title." - " : ""; ?>Vervantis </title>
		<meta name="description" content="">
		<meta name="author" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/smartadmin-production-plugins.min.css?ct=1">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/smartadmin-skins.min.css">

		<!-- SmartAdmin RTL Support is under construction-->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/smartadmin-rtl.min.css">

		<?php

			if ($page_css) {
				foreach ($page_css as $css) {
					echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ASSETS_URL.'/assets/css/'.$css.'">';
				}
			}
		?>


		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/assets/css/demo.min.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" class="faviconico" href="<?php echo ASSETS_URL; ?>/assets/img/favicon.ico" type="image/x-icon">
		<link rel="icon" class="faviconico" href="<?php echo ASSETS_URL; ?>/assets/img/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<link rel="apple-touch-icon" href="<?php echo ASSETS_URL; ?>/assets/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo ASSETS_URL; ?>/assets/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo ASSETS_URL; ?>/assets/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo ASSETS_URL; ?>/assets/img/splash/touch-icon-ipad-retina.png">

		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="<?php echo ASSETS_URL; ?>/assets/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="<?php echo ASSETS_URL; ?>/assets/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="<?php echo ASSETS_URL; ?>/assets/img/splash/iphone.png" media="screen and (max-device-width: 320px)">
<style>
#header
{
	position:fixed;
	top:0px;
	width:100%;
}
#left-panel
{
	position:fixed;
}
<!--.goog-te-banner-frame.skiptranslate {display: none !important;}-->
body { top: 0px !important; }
.helpdesk{margin: 14px 3px;float: right;}
textarea#swal-input3{height:80px;}
.floatleft{float:left;}
.pointer{cursor:pointer;}
.helpdeskmodel{
	float:none !important;
	display:block !important;
}
.helpdeskmodel .modal-footer{text-align:center;}
.helpdeskrequired{color:red;}
.changetimezone ul{
    height: 250px;
    overflow-y: scroll;
}
.error{color:red;
	font-style: italic;
    /* background: #fff; */
    width: auto;
    margin-top: 4px;
    padding: 0;
}
#changepwdbox{
	width: 100% !important;
    height: 100% !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    background: rgba(0,0,0,.6) !important;
    z-index: 100 !important;
}
.chpwdhelp{
    font-style: italic;
	font-size:small;
}
.top-right-menus{
	position: absolute;
    width: 800px;
    right: 0;
}


.goog-tooltip {
    display: none !important;
}
.goog-tooltip:hover {
    display: none !important;
}
.goog-text-highlight {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}
#google_translate_element{width:300px;float:right;text-align:right;display:block}
.goog-te-banner-frame.skiptranslate { display: none !important;}
#goog-gt-tt{display: none !important; top: 0px !important; }
.goog-tooltip skiptranslate{display: none !important; top: 0px !important; }
/*.activity-root { display: hide !important;}
.status-message { display: hide !important;}
.started-activity-container { display: hide !important;}*/

/* system log notification*/
/*.activity-dropdown {display:inline-block; margin:15px; cursor:pointer;}*/

/*
#logo-group span#activity {width:auto; background-color: transparent; border:0; background-image: none; font-size: 13px; color:#333; font-weight: normal;}
#logo-group span#activity:hover{border:0; transition: none; box-shadow: none; }
#logo-group > span b.badge{right: -15px;}
*/

.ajax-dropdown{left:200px; width: 550px; max-height: 412px;}
.ajax-dropdown::before {left:2%}
.ajax-dropdown::after {left:2%}
.notification-body > li a.msg{padding-left: 0px !important;}
.ajax-notifications{max-height:365px;}
.ajax-dropdown > :last-child{padding-left:3px; padding-right:6px; padding-bottom:5px;}

.notification-body .msg-body, .notification-body .subject{max-height:unset; text-overflow:unset; white-space:unset;}
.notification-body > li > span{padding-bottom:8px;}

.soundicon{background-image:unset !important;}
.soundicon > span.glyphicon {font-size:18px; line-height: 23px; cursor:pointer; /*color:#fff;*/}
/*#setsound a:hover .glyphicon {color:#ccc !important;}*/
#setsound a.clssoundon {background-color:#3276b1 !important; border-color: #2c699d !important; color:#fff !important;}
#setsound a.clssoundoff {background-color: #f8f8f8 !important; color: #6D6A69 !important;}
#logo{margin-top: 9px !important;}
</style>
	</head>
	<body <?php echo implode(' ', array_map(function($prop, $value) {
			return $prop.'="'.$value.'"';
		}, array_keys($page_body_prop), $page_body_prop)) ;?>>
		<!-- POSSIBLE CLASSES: minified, fixed-ribbon, fixed-header, fixed-width
			 You can also add different skin classes such as "smart-skin-1", "smart-skin-2" etc...-->
		<?php
			if (!$no_main_header) {

		?>
				<!-- HEADER -->
				<header id="header">
					<div class="row">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div id="logo-group">

								<!-- PLACE YOUR LOGO HERE -->
								<span id="logo"> <img src="<?php echo ASSETS_URL; ?>/assets/img/vervantis_logo_small.png" alt="Vervantis" class="header-logo"> </span>
								<!-- END LOGO PLACEHOLDER -->
								
								
								
								
								
								
								<!-- Note: The activity badge color changes when clicked and resets the number to 0
					 Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
					 
								<span id="activity" class="activity-dropdown"> <span class="glyphicon glyphicon-bell"></span> <!--<b class="badge bg-color-red bounceIn animated"> 21 </b>--> </span>
								<!--<span id="activity" class="activity-dropdown "> System Log <b class="badge bg-color-red bounceIn animated"> 21 </b> </span>-->
								<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
								<div class="ajax-dropdown" style="display: none;">

									<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
									<div class="btn-group btn-group-justified" data-toggle="buttons">
										<label class="btn btn-default active">
											<input type="radio" name="activity" id="assets/ajax/notification-header.php?t=1">
											Change Log </label>
										<label class="btn btn-default">
											<input type="radio" name="activity" id="assets/ajax/notification-header.php?t=2">
											Announcements </label>
										<!--<label class="btn btn-default">
											<input type="radio" name="activity" id="assets/ajax/notification-ajax.php?t=3">
											Tasks (4) </label>-->
									</div>

									<!-- notification content -->
									<div class="ajax-notifications custom-scroll" style="opacity: 1;">
									
									</div>
									<!-- end notification content -->

									<!-- footer: refresh area -->
									<!--
									<span> Last updated on: 12/12/2013 9:43AM
										<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
											<i class="fa fa-refresh"></i>
										</button> </span>
									-->
									<!-- end footer -->

								</div>
								<!-- END AJAX-DROPDOWN -->
								
								
								
								
							</div>
							
							
							
							
							
								
								
								
								
								
								
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4 text-center">
							<div>

								<!-- PLACE YOUR DATAHUB360 HERE -->
								<span id="logo"> <img src="<?php echo ASSETS_URL; ?>/assets/img/datahub360.png" alt="Vervantis" class="header-logo"> </span>
								<!-- END DATAHUB360 PLACEHOLDER -->
							</div>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4 top-right-menus">
							<!-- pulled right: nav area -->
							<div class="pull-right">
								<!-- collapse menu button -->
								<div class="btn-header pull-right" id="hide-menu">
									<span> <a title="Collapse Menu" data-action="toggleMenu" href="javascript:void(0);"><i class="fa fa-reorder"></i></a> </span>
								</div>
								<!-- end collapse menu -->

								<!-- #MOBILE -->
								<!-- Top menu profile link : this shows only when top menu is active -->
								<ul class="header-dropdown-list hidden-xs padding-5" id="mobile-profile-img">
									<li class="">
										<a data-toggle="dropdown" class="dropdown-toggle no-margin userdropdown" href="javascript:void(0);">
											<img class="online" alt="<?php echo $_username; ?>" title="<?php echo $_username; ?>" src="<?php echo $_userimage; ?>">
										</a>
										<ul class="dropdown-menu pull-right">
											<!--<li>
												<a class="padding-10 padding-top-0 padding-bottom-0" href="javascript:void(0);"><i class="fa fa-cog"></i> Setting</a>
											</li>
											<li class="divider"></li>-->
											<li>
												<a class="padding-10 padding-top-0 padding-bottom-0" href="javascript:void(0);" onclick="navigateurl('assets/ajax/profile.php','Profile')"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
											</li>
											<li class="divider"></li>
											<!--<li>
												<a data-action="toggleShortcut" class="padding-10 padding-top-0 padding-bottom-0" href="javascript:void(0);"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
											</li>
											<li class="divider"></li>-->
											<li>
												<a data-action="launchFullscreen" class="padding-10 padding-top-0 padding-bottom-0" href="javascript:void(0);"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
											</li>
											<li class="divider"></li>
											<li>
												<a data-action="userLogout" class="padding-10 padding-top-5 padding-bottom-5" href="assets/includes/logout.php"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
											</li>
										</ul>
									</li>
								</ul>

								<!-- logout button -->
								<div class="btn-header transparent pull-right" id="logout">
									<span> <a data-logout-msg="You can improve your security further after logging out by closing this opened browser" data-action="userLogout" title="Sign Out" href="login.html"><i class="fa fa-sign-out"></i></a> </span>
								</div>
								<!-- end logout button -->
								<?php if(isset($_SESSION["group_id"]) and $_SESSION["group_id"] != 3 and 1==2) {?>
								<!-- search mobile button (this is hidden till mobile view port) -->
								<div class="btn-header transparent pull-right" id="search-mobile">
									<span> <a title="Search" href="javascript:void(0)"><i class="fa fa-search"></i></a> </span>
								</div>
								<!-- end search mobile button -->

								<!-- #SEARCH -->
								<!-- input: search field -->
								<form class="header-search pull-right" action="#assets/ajax/search.php">
									<input type="text" placeholder="Find reports and more" name="param" id="search-fld">
									<button type="submit">
										<i class="fa fa-search"></i>
									</button>
									<a title="Cancel Search" id="cancel-search-js" href="javascript:void(0);"><i class="fa fa-times"></i></a>
								</form>
								<!-- end input: search field -->
								<?php } ?>
								<div class="btn-header transparent pull-right" id="setsound">
									<span> <a class="soundicon bg-color-red-- <?php echo ($sound_ar==1)?"clssoundoff":"clssoundon";?>" title="Sound On/Off" data-action="launchFullscreen--" href="javascript:void(0);">
									<?php if ($sound_ar==1) {?>
										<span class="glyphicon glyphicon-volume-off"></span>
									<?php } else { ?>
										<span class="glyphicon glyphicon-volume-up"></span>
									<?php } ?>
									</a> </span>
								</div>
								<!-- fullscreen button -->
								<div class="btn-header transparent pull-right" id="fullscreen">
									<span> <a title="Full Screen" data-action="launchFullscreen" href="javascript:void(0);"><i class="fa fa-arrows-alt"></i></a> </span>
								</div>
								<!-- end fullscreen button -->

								<!-- #Voice Command: Start Speech -->
								<!-- NOTE: Voice command button will only show in browsers that support it. Currently it is hidden under mobile browsers.
										   You can take off the "hidden-sm" and "hidden-xs" class to display inside mobile browser
								<div id="speech-btn" class="btn-header transparent pull-right hidden-sm hidden-xs">
									<div>
										<a href="javascript:void(0)" title="Voice Command" data-action="voiceCommand"><i class="fa fa-microphone"></i></a>
										<div class="popover bottom"><div class="arrow"></div>
											<div class="popover-content">
												<h4 class="vc-title">Voice command activated <br><small>Please speak clearly into the mic</small></h4>
												<h4 class="vc-title-error text-center">
													<i class="fa fa-microphone-slash"></i> Voice command failed
													<br><small class="txt-color-red">Must <strong>"Allow"</strong> Microphone</small>
													<br><small class="txt-color-red">Must have <strong>Internet Connection</strong></small>
												</h4>
												<a href="javascript:void(0);" class="btn btn-success" onclick="commands.help()">See Commands</a>
												<a href="javascript:void(0);" class="btn bg-color-purple txt-color-white" onclick="$('#speech-btn .popover').fadeOut(50);">Close Popup</a>
											</div>
										</div>
									</div>
								</div>
								<!-- end voice command -->


								<button class="btn btn-primary btn-xs helpdesk">
										<?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ echo "Ethics Hotline"; }else{ echo "Help Desk"; } ?>
								</button>

								<!-- region -->
								<ul class="header-dropdown-list changetimezone hidden-xs hidden"></ul>

								<!-- region -->
								<ul class="header-dropdown-list changeregion hidden-xs hidden">
									<li>
										<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="notranslate"> America</span> <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Africa">Africa</a>
											</li>
											<li class="active">
												<a href="javascript:void(0);" class= "notranslate" id="America">America</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Antarctica">Antarctica</a>
											</li>
											<li class="active">
												<a href="javascript:void(0);" class= "notranslate" id="America">Arctic</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Asia">Asia</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Atlantic">Atlantic</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Australia">Australia</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Europe">Europe</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Indian">Indian</a>
											</li>
											<li>
												<a href="javascript:void(0);" class= "notranslate" id="Pacific">Pacific</a>
											</li>
										</ul>
									<li>
								</ul>
								<!-- region end -->

								<!-- multiple lang dropdown : find all flags in the flags page -->
								<ul class="header-dropdown-list changelanguage hidden-xs">
									<li>
										<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <img alt="United States" class="flag flag-us" src="assets/img/blank.gif"> <span class="notranslate" id="langdisplaytop"> English (US)</span> <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu pull-right">
											<li id="lang-en" class="active">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('en','us','English (US)');"><img alt="United States" class="flag flag-us" src="assets/img/blank.gif"> English (US)</a>
											</li>
											<li id="lang-fr">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('fr','fr','Français');"><img src="assets/img/blank.gif" class="flag flag-fr" alt="France"> Français</a>
											</li>
											<li id="lang-es">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('es','es','Español');"><img src="assets/img/blank.gif" class="flag flag-es" alt="Spanish"> Español</a>
											</li>
											<li id="lang-de">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('de','de','Deutsch');"><img src="assets/img/blank.gif" class="flag flag-de" alt="German"> Deutsch</a>
											</li>
											<li id="lang-ja">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('ja','jp','日本語');"><img src="assets/img/blank.gif" class="flag flag-jp" alt="Japan"> 日本語</a>
											</li>
											<li id="lang-zh-CN">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('zh-CN','cn','中文');"><img src="assets/img/blank.gif" class="flag flag-cn" alt="China"> 中文</a>
											</li>
											<li id="lang-it">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('it','it','Italiano');"><img src="assets/img/blank.gif" class="flag flag-it" alt="Italy"> Italiano</a>
											</li>
											<li id="lang-pt">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('pt','pt','Portugal');"><img src="assets/img/blank.gif" class="flag flag-pt" alt="Portugal"> Portugal</a>
											</li>
											<li id="lang-ru">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('ru','ru','Русский язык');"><img src="assets/img/blank.gif" class="flag flag-ru" alt="Russia"> Русский язык</a>
											</li>
											<li id="lang-ko">
												<a href="javascript:void(0);" class= "notranslate" onclick="translateLanguage('ko','kr','한국어');"><img src="assets/img/blank.gif" class="flag flag-kr" alt="Korea"> 한국어</a>
											</li>

										</ul>
									</li>
								</ul>
								<!-- end multiple lang -->

								<!-- multiple lang dropdown : find all flags in the flags page -->
								<ul class="header-dropdown-list changecurrency hidden-xs hidden">
									<li>
										<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="notranslate"> USD ($)</span> <i class="fa fa-angle-down"></i> </a>
										<ul class="dropdown-menu pull-right">
											<li id="curr-USD" class="active">
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('USD');"> USD ($)</a>
											</li>
											<li id="curr-EUR" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('EUR');"> EUR (€)</a>
											</li>
											<li id="curr-JPY" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('JPY');"> JPY (¥)</a>
											</li>
											<li id="curr-GBP" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('GBP');"> GBP (£)</a>
											</li>
											<li id="curr-CHF" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('CHF');"> CHF (CHF)</a>
											</li>
											<li id="curr-CAD" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('CAD');"> CAD ($)</a>
											</li>
											<li id="curr-AUD" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('AUD');"> AUD ($)</a>
											</li>
											<li id="curr-ZAR" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('ZAR');"> ZAR (R)</a>
											</li>
											<li id="curr-MXN" disabled>
												<a href="javascript:void(0);" class= "notranslate" onclick="changeCurrency('MXN');"> MXN ($)</a>
											</li>

										</ul>
									</li>
								</ul>
								<!-- end multiple lang -->
							</div>
							<!-- end pulled right: nav area -->
						</div>
					</div>



<div class="modal-dialog helpdeskmodel hidden">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close helpdeskclose" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 class="modal-title text-center">
			<?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ echo "Submit Anonymous Concern/Complaint"; }else{ echo "Help Request"; } ?>
			</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="subject">Subject <span class="helpdeskrequired">*<span></label>
						<input type="text" id="subject" class="form-control" placeholder="Subject" required="required">
					</div>
					<div class="form-group">
						<label for="requestdetails"><?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ echo "Concern/Complaint Details"; }else{ echo "Request Details"; } ?> <span class="helpdeskrequired">*<span></label>
						<textarea class="form-control" id="requestdetails" placeholder="Request Details" rows="5" required="required"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="helpdeskinfo hidden" style="top: -250px;left: -16vw;overflow:auto;position: absolute;background: #fff;width: 70vw;border: 1px solid #333;opacity: 1;z-index: 99;">
							<div class="arrow"></div>
							<button type="button" class="helpdeskinfoclose close" data-dismiss="modal" aria-hidden="true" style="
								float: right;
								margin-right: 5px;
								margin-top: 5px;
							">×</button>
							<h3 class="popover-title text-center">Priority Definitions and Target Responses</h3>
							<div class="popover-content">
								<ul>
									<li>Priority 1
										<ul>
											<li>Relate to all Service Outages.</li>
											<li>Target response time: 0 to 30 minutes for initial response.</li>
											<li>Target status updates: Hourly.</li>
										</ul>
									</li>
									<li>Priority 2
										<ul>
											<li>Relate to any failure of any material function(s) of a Service for general users that do not represent a Service Outage.</li>
											<li>Target response time: 30 minutes for initial response between 6 am-6 pm US Mountain Time, Monday-Friday; no longer than 16 hours when contacted outside of the standard hours listed above.</li>
											<li>Target status updates: Once each business day.</li>
										</ul>
									</li>
									<li>Priority 3
										<ul>
											<li>Relate to any failure of a Service that affects the functionality of the Service for general users that is not within the scope of a Priority 1 or Priority 2 request.</li>
											<li>Target response time: 90 minutes for initial response between 6 am-6 pm US Mountain Time, Monday-Friday. Next business day when contacted outside of the hours listed above.</li>
											<li>Resolution status updates: As needed.</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
						<?php if(1==2){ ?>
						<label for="priority">Priority <span id="showhelpinfo" class="glyphicon glyphicon-info-sign pointer" aria-hidden="true"></span>
						</label>
						<select class="form-control" id="priority">
							<option value="Low" selected>Low</option>
							<option value="Medium">Medium</option>
							<option value="High">High</option>
							<option value="Urgent">Urgent</option>
							<option value="Critical">Critical</option>
						</select>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary helpdesksubmit">Save changes</button>
			<button type="button" class="btn btn-default helpdeskclose" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
<?php /*print_r($_SESSION);*/ ?>
<?php if($_SESSION['newuser'] != 'No'){ ?>
<div class="divMessageBox animated fadeIn fast changepwd" id="changepwdbox">
	<div class="MessageBoxContainer animated fadeIn fast">
		<div class="MessageBoxMiddle">
			<span class="MsgTitle">Password Change Required (every 90 days)</span>
			<p class="pText">Please enter your new password</p>
			<input class="form-control" type="password" id="changepwd" placeholder="Password" value="">
			<p class="error" id="changeerror"></p>
			<br><br>
			<div class="MessageBoxButtonSection">
				<button id="changepwdbutton" class="btn btn-default btn-sm botTempo"> Submit</button>
			</div>

			<p class="chpwdhelp">Your password must have:</p>
			<ul class="chpwdhelp" style="clear: both;width: 100%;/* align-self: center; */margin: 0 auto;/* width: 100%; */">
				<li>At least eight characters</li>
				<li>Numbers and letters</li>
				<li>Upper and lower case letters</li>
				<li>Special characters</li>
			</ul>
		</div>
	</div>
</div>
<?php } ?>


<div id="google_translate_element" class="hidden"></div>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'google_translate_element'); //remove the layout
  }
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit&SameSite=None" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php if($_SESSION['newuser'] != 'No'){ ?>
<script src="assets/js/sha512.js"></script>
<script src="assets/js/forms.js"></script>
<script src="assets/js/plugin/jquery-form/jquery-form.min.js"></script>
<?php } ?>

<script type="text/javascript">
$( document ).ready(function() {
	/*$.expr[':'].textEquals = function(a, i, m) {
		return $(a).text().match("^" + m[3] + "$");
	};*/
<?php if($_SESSION['newuser'] != 'No'){ ?>
$('#changepwdbutton').click(function() {
	var changepwd=$('#changepwd').val();
	changepwd=changepwd.trim();
	$('#changeerror').text("");

	if(changepwd == ""){
		$('#changeerror').text("Password cannot be empty!");
	}else if(/\d{1}/.test(changepwd) == false){
		$('#changeerror').text("Password must include at least one number!");
	}else if(/[a-zA-Z]{1}/.test(changepwd) == false){
		$('#changeerror').text("Password must include at least one letter!");
	}else if(/[A-Z]{1}/.test(changepwd) == false){
		$('#changeerror').text("Password must include at least one CAPS!");
	}else if(/\W{1}/.test(changepwd) == false){
		$('#changeerror').text("Password must include at least one symbol!");
	}else if(/^\S{1,}/.test(changepwd) == false){
		$('#changeerror').text("Password must not contain spaces!");
	}else if(changepwd.length < 8){
		$('#changeerror').text("Password must be minimum 8 characters long!");
	}else{


		var formData = new FormData();

		formData.append('newpwdchange', changepwd);
		formData.append('p', ajaxformhash(changepwd));
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
						$("#changepwdbox").addClass("hidden");
						$.smallBox({
							title : "Changes Saved!",
							content:"",
							color : "#296191",
							timeout: 2000
						}, function() {
							//alert("Success");
						});
					}else if(results.error == 6)
					{
						$('#changeerror').text("New Password must be different from earlier password");
						$( '#changeerror' ).focus();
						$.smallBox({
							title : "This password was used earlier",
							content : "<i class='fa fa-clock-o'></i> <i>Please enter different password</i>",
							color : "#FFA07A",
							iconSmall : "fa fa-warning shake animated",
							timeout : 4000
						});
					}else{
						$.smallBox({
							title : "Error in request.",
							content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
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
	}
});
<?php } ?>



$('.changeregion .dropdown-menu a').click(function() {
	var regionname=$(this).text();
	$(".changeregion .dropdown-menu li.active").removeClass("active");
	$(this).closest('li').addClass("active");
	$(".changeregion>li>a>span").text(regionname);

	$(".changetimezone").load("assets/includes/generatetimezone.php?region="+regionname);

  /*$('#google_translate_element select option').each(function(){
    if($(this).val().indexOf(langg) > -1) {
        $(this).parent().val($(this).val());
        var container = document.getElementById('google_translate_element');
        var select = container.getElementsByTagName('select')[0];
        triggerHtmlEvent(select, 'change');
    }
  });

    $('.changelanguage>li>a>img').removeClass('flag-us');
    $('.changelanguage>li>a>img').addClass('flag-'+flagsym);
	$('.changelanguage .dropdown-menu li.active').removeClass('active');
	$('.changelanguage .dropdown-menu li#lang-'+langg).addClass('active');*/
    //$('#lang-'+langg).addClass('active');
});

<?php
	$_SESSION["timezone_set"]="";
	if(isset($_SESSION['timezone']) and $_SESSION['timezone'] !=""){
		if(preg_match('/^([^\/]+)/s', $_SESSION['timezone'], $region_arr)){
			$autoregion=$region_arr[1];
			$region_arr=null;
			if($autoregion=="Africa" || $autoregion=="America" || $autoregion=="Antarctica" || $autoregion=="Arctic" || $autoregion=="Asia" || $autoregion=="Atlantic" || $autoregion=="Australia" || $autoregion=="Europe" || $autoregion=="Indian" || $autoregion=="Pacific"){
			$_SESSION["timezone_set"]=$_SESSION['timezone'];
?>
			var autoregion='<?php echo $autoregion; ?>';

			//$(".changeregion .dropdown-menu a:textEquals("+autoregion+")").trigger("click");
				$(".changeregion .dropdown-menu a#"+autoregion).trigger("click");
<?php
			}
		}
	}
?>

$(".helpdesksubmit").on('click', function (e) {
		var hsub=$(".helpdeskmodel #subject").val();
		var hmsg=$(".helpdeskmodel #requestdetails").val();
		<?php if(1==2){ ?>
		var hprty=$(".helpdeskmodel #priority").val();
		if(hsub != '' && hmsg != '' && hprty != ''){
		<?php } ?>
		if(hsub != '' && hmsg != ''){
			$.post("assets/includes/helpdesk.inc.php",
			{
			  subject: hsub,
			  uid: <?php echo $_SESSION['user_id']; ?>,
			  message: hmsg<?php if(1==2){ ?>,
			  priority : hprty<?php } ?>
			},
			function(result){
			  if(result == true){
				Swal.fire("","Request submitted successfully!", "success");
				$(".helpdeskmodel").addClass("hidden");
			  }else if(result==6){Swal.fire("","All fields required!", "warning");}
			  else{Swal.fire("","Error Occured! Please try after sometime.", "warning");$(".helpdeskmodel").addClass("hidden");}
			});
		}else{Swal.fire("","All fields required!", "warning");}
});



//$("[rel=popover-hover]").popover({ trigger: "hover" });
	$(".helpdeskinfoclose").on('click', function (e) {
		//if ($(e.target).closest(".helpdeskinfo").length === 0) {
			//if($( '.helpdeskinfo' ).is(":visible")){
			//$(".helpdeskinfo").css("display","none !important;");
			//$( '.helpdeskinfo' ).hide();
			$(".helpdeskinfo").addClass("hidden");
			//});
		//}
	});

	$("#showhelpinfo").on('click', function (e) {
		//$(".helpdeskinfo").show();
		$(".helpdeskinfo").removeClass("hidden");
		//$( '.helpdeskinfo' ).toggle();
		//$(".helpdeskinfo").css("display","block !important;");
	});

	$(".helpdesk").on('click', function (e) {
		$(".helpdeskmodel").removeClass("hidden");
	});

	$(".helpdeskclose").on('click', function (e) {
		$(".helpdeskmodel").addClass("hidden");
	});
});
function triggerHtmlEvent(element, eventName) {
	var event;
	if(document.createEvent) {
		event = document.createEvent('HTMLEvents');
		event.initEvent(eventName, true, true);
		element.dispatchEvent(event);
	} else {
		event = document.createEventObject();
		event.eventType = eventName;
		element.fireEvent('on' + event.eventType, event);
	}
}

function translateLanguage(langg,flagsym,ltype){
  $('#google_translate_element select option').each(function(){
    if($(this).val().indexOf(langg) > -1) {
        $(this).parent().val($(this).val());
        var container = document.getElementById('google_translate_element');
        var select = container.getElementsByTagName('select')[0];
        triggerHtmlEvent(select, 'change');
    }
  });

    $('.changelanguage>li>a>img').removeClass();
    $('.changelanguage>li>a>img').addClass('flag flag-'+flagsym);
	$('.changelanguage .dropdown-menu li.active').removeClass('active');
	$('.changelanguage .dropdown-menu li#lang-'+langg).addClass('active');
    //$('#lang-'+langg).addClass('active');
	$('#langdisplaytop').text(ltype);
}

function changeCurrency(newcurr){
<?php if(1==2){ ?>
    $('.changecurrency>li>a>span').text(newcurr);
	$('.changecurrency .dropdown-menu li.active').removeClass('active');
	$('.changecurrency .dropdown-menu li#curr-'+newcurr).addClass('active');
    //$('#lang-'+langg).addClass('active');
<?php } ?>
}
<!-- Flag click handler -->
 /*       $('.translation-links a').click(function(e) {
  e.preventDefault();
  var lang = $(this).data('lang');
  $('#google_translate_element select option').each(function(){
    if($(this).text().indexOf(lang) > -1) {
        $(this).parent().val($(this).val());
        var container = document.getElementById('google_translate_element');
        var select = container.getElementsByTagName('select')[0];
        triggerHtmlEvent(select, 'change');
    }
});
});*/
        </script>
				</header>
				<!-- END HEADER -->

				<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
				Note: These tiles are completely responsive,
				you can add as many as you like
				-->
				<div id="shortcut">
					<ul>
						<li>
							<a href="#assets/ajax/inbox.php" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
						</li>
						<li>
							<a href="#assets/ajax/calendar.php" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
						</li>
						<li>
							<a href="#assets/ajax/gmap-xml.php" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
						</li>
						<li>
							<a href="#assets/ajax/invoice.php" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
						</li>
						<li>
							<a href="#assets/ajax/gallery.php" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
						</li>
						<li>
							<a href="#assets/ajax/profile.php" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
						</li>
					</ul>
<!-- YOUR BODY HERE -->
				</div>
				<!-- END SHORTCUT AREA -->

		<?php
			}
		?>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.js"></script>
<script src="<?php echo ASSETS_URL; ?>/assets/js/chat.js"></script>
<style>
<!--#left-panel nav ul{
	overflow: hidden !important;
    display: -webkit-inline-box !important;
}
.navul{
	margin-right: -131px;
}
.navulfit{
	overflow: hidden !important;
    display: -webkit-inline-box !important;
}-->
</style>
 <audio id="beep" src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/sound/messagebox.mp3"  autostart="false"  ></audio>
<script>
var bell = new Audio('/assets/sound/messagebox.mp3');
function beep(file,volume){
    var snd = new Audio(file);
    volume = volume ? volume : 0.5; // defaults to 50% volume level
    snd.volume = volume;

    // LISTENER: Rewind the playhead when play has ended
    snd.addEventListener('ended',function(){
        this.pause();
        this.currentTime=0;
    });

    // Play the sound
    snd.play();
}
$(document).ready(function(){
getuserlist();
function getuserlist(){
		temp_arr="";
		$.ajax({
				url: '<?php echo ASSETS_URL; ?>/assets/includes/get-user-list.inc.php',
				type: 'POST',
				data: {getuserlist:true},
				async: true,
				success: function (data) {
				if(data != false)
				{
					var result = JSON.parse(data);
					if(result.error==false)
					{
						$(".online-users").html("");
						$(".online-users").append("("+result.onlineUser.length+")");
						$(".display-users").html("");
						$('a[title="Chat <font class=\'online-users\' id=\'onlinechat\'></font>"]').attr('title','Chat');
						for(i=0;i<result.onlineUser.length;i++)
						{
							var uname=result.onlineUser[i].username;
							var uid=result.onlineUser[i].userid;
							var coname=result.onlineUser[i].company_name;
							temp_arr = temp_arr + '<a title="online" href="javascript:void(0);" onclick="javascript:chatWith(\''+uid+'\')"><i class="online-user" title="Online"></i>'+uname+'<br><span class="coname">'+coname+'</span></a>';
							/*$(".display-users").append(temp_arr).find("a").on("click",function() {
								chatWith(uid);
								return false;
							});*/
						}

						$.each(result.replyFrom, function (index,value) {
								chatWith(index);
						});
						$(".display-users").append(temp_arr);

						if (result.replyFrom.length !== 0) {
							//if(!document.hasFocus()){
						var originaltitle=document.title;
								//document.title ="&#9679;"+originaltitle.replace("&#9679;", "");
						document.title ="●"+originaltitle.replace("●", "");
							//}
							//$(".faviconico").attr("href","https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/favicon_notification.ico");
							//try{
							//bell.play();
							//}catch(err){}
						}else{
							//var originaltitle=document.title;
							//document.title = originaltitle.replace("●", "");
						}
					}else if(result.error=="kick"){
						window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/includes/logout.php?error=You sign in on another device!");
					}else if(result.error=="Timeout"){
						window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/includes/logout.php?error=Timeout due to inactivity!");
					}else{
						//alert("Error Occured");
					}
				}else{
					//alert("Error Occured");
				}
			},
			cache: false,
		});
		setTimeout(getuserlist, 6000);
}

    ['click', 'touchstart', 'mousemove'].forEach(evt =>
        document.addEventListener(evt, resetTitle, false)
    );

    const idleDurationSecs = 28800;    // X number of seconds 1000=1sec
    const redirectUrl = "https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/includes/logout.php?error=Timeout due to inactivity!";  // Redirect idle users to this URL
    let idleTimeout; // variable to hold the timeout, do not modify

    const resetIdleTimeout = function() {

        // Clears the existing timeout
        if(idleTimeout) clearTimeout(idleTimeout);
		resetTitle();
        // Set a new idle timeout to load the redirectUrl after idleDurationSecs
        idleTimeout = setTimeout(() => location.href = redirectUrl, idleDurationSecs * 1000);
    };

    // Init on page load
    resetIdleTimeout();

    // Reset the idle timeout on any of the events listed below
    ['click', 'touchstart', 'mousemove'].forEach(evt =>
        document.addEventListener(evt, resetIdleTimeout, false)
    );

////////////////////////////////////
////////////////////////////////////
});

function resetTitle() {
	var otitle=document.title;
	if(otitle.indexOf("●") != -1){
		document.title =otitle.replace("●", "");
	}
};


/*$(document).ready(function(){
    //if ($(window).width() < 1499) {//alert("less");
        //$(".top-menu").css("display", "none");
    //}

$("#left-panel nav ul").addClass("navulfit");
  $(window).resize(function(){
        if ($(window).width() <= 1499) {//alert($(window).width());
            //$(".top-menu").css("display", "none");
			$("#js-nav-menu-wrapper-left-btn").removeClass("hidden");
			$("#js-nav-menu-wrapper-right-btn").removeClass("hidden");
        }else{
			$("#js-nav-menu-wrapper-left-btn").addClass("hidden");
			$("#js-nav-menu-wrapper-right-btn").addClass("hidden");
		}
  });
});*/
</script>

<script>
/*notification script*/
var db_beep_ar = <?php echo $sound_ar;?>;
var set_beep_ar;
//$.sound_on = false;
$(document).ready(function(){
	$( "#logo-group #activity" ).click(function() {
	  //alert( "Handler for .click() called." );
	  $( ".ajax-dropdown .btn-group .active" ).click();
	  //ajax-dropdown btn-group active
	});
	
	$('.soundicon').click(function() {
			
			//alert(db_beep);
			
			if (db_beep_ar == 1) {
				set_beep_ar = 0;
				db_beep_ar = 0;
				set_sound_ar(true);
				$(".soundicon .glyphicon").removeClass("glyphicon-volume-off");
				$(".soundicon .glyphicon").addClass("glyphicon-volume-up");
				$("#setsound a").removeClass("clssoundoff");
				$("#setsound a").addClass("clssoundon");
				
			} else {
				set_beep_ar = 1;
				db_beep_ar = 1;
				set_sound_ar(false);
				$(".soundicon .glyphicon").removeClass("glyphicon-volume-up");
				$(".soundicon .glyphicon").addClass("glyphicon-volume-off");
				$("#setsound a").removeClass("clssoundon");
				$("#setsound a").addClass("clssoundoff");
				
			}
			$.ajax({
                url: 'assets/includes/settings_ar.php',
                type: 'POST',
                data: {beep: set_beep_ar},
                success: function (result) {
					if (result==1) {
						
					} else if (result==0) {
						
					}
                }
            });
		
	});
	
	//alert(db_beep);
	var db_beep_ar_2 = 1-db_beep_ar; // convert 1 to 0 and 0 to 1
 	
	set_sound_ar(!!db_beep_ar_2); // convert to boolean
	
});
</script>
