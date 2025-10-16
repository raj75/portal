<!-- Oct 11, 2022 -->
<?php
require_once 'assets/includes/db_connect.php';
require_once 'assets/includes/functions.php';
sec_session_start();
if(login_check($mysqli) == true){
require_once("assets/inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("assets/inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC. */



/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("assets/inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
include("assets/inc/nav.php");

?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//include("assets/inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<!-- FOOTER -->
	<?php
		include("assets/inc/footer.php");
	?>
<!-- END FOOTER -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
	//include required scripts
	include("assets/inc/scripts.php");
	//include footer
	include("assets/inc/google-analytics.php");
}elseif(isset($_COOKIE["resetpw"])){
	$error=0;
	$errmsg="";
	$pkey = @trim($_COOKIE['resetpw']);
	if($pkey == ""){
		$error=1;
		$errmsg="Error Occured. Please try to contact Vervantis!";
	}else{
		$stmt = $mysqli->prepare("SELECT user_id FROM user WHERE hkey !='' and hkey = '".$mysqli->real_escape_string($pkey)."' LIMIT 1");

		if ($stmt) {
			//$stmt->bind_param('s', $pkey);
			$stmt->execute();
			$stmt->store_result();
			$cnt=$stmt->num_rows;
			if ($cnt > 0) {



			}else{
				$error=2;
				$errmsg="Link Expired or Doesn't Exist.\nYou will be redirected to password reset page shortly!";

			}

		}else{
			$error=1;
			$errmsg="Error Occured. Please try to contact Vervantis!";
		}
	}
	setcookie("resetpw", "", time() - 3600);


?>

	<!DOCTYPE html>
	<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
	<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
	<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
	<head>
		<title>Vervantis | Client Reset Password</title>

		<!-- Meta -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Vervantis Energy Consultants offer clients secure cloud based access to all their energy and utility data via our online portal. Reset password here…">
		<meta name="robots" content="noindex, nofollow">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">

		<!-- CSS Global Compulsory -->
		<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/style.css">

		<!-- CSS Implementing Plugins -->
		<link rel="stylesheet" href="../assets/plugins/line-icons/line-icons.css">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">

		<!-- CSS Page Style -->
		<link rel="stylesheet" href="../assets/css/pages/page_log_reg_v2.css">

		<!-- CSS Theme -->
		<link rel="stylesheet" href="../assets/css/themes/blue.css">

		<!-- CSS Customization -->
		<link rel="stylesheet" href="../assets/css/custom.css">

		<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
		<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/js/jquery.ui.shake.js"></script>
		<style>
			.ruls{margin-left:27px;}
			ul li{font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;color:#777;}
		</style>
	</head>

	<body>
		<?php
		if (isset($_GET['error'])) {
			echo '<p class="error">Error Logging In!</p>';
		}
		if (!isset($pkey)) {

			echo '<p class="error">Error Occurred!</p>';exit();
		}
		?>

	<!--=== Content Part ===-->
	<div class="container">
		<div id="shake">
			<!--Reg Block-->
			<div class="reg-block" id="box">
				<div class="clogo">Vervantis</div>
				<div class="dlogo">DataHub</div>
			<?php
				if($error !=0){
					echo "<p style='text-align:center !important;'>".@str_replace("\n","<br>",@htmlspecialchars($errmsg, ENT_QUOTES))."</p>";


				}else{
			?>
				<div class="reg-block-header">
					<h2>Reset Password</h2>
				</div>

				<form action="#" method="post" name="login_form" onsubmit="return false">
				<div class="input-group margin-bottom-20">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" placeholder="Password" name="password" id="password">
					<span class="input-group-addon" onclick="showpw('password')"><i class="fa fa-eye-slash"></i></span>
				</div>
				<div class="input-group margin-bottom-20">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" placeholder="Confirm Password" name="cpassword" id="cpassword">
					<span class="input-group-addon" onclick="showpw('cpassword')"><i class="fa fa-eye-slash"></i></span>
					<input type="hidden" class="form-control" name="pwkey" id="pwkey" value="<?php echo $pkey; ?>">
				</div>
				<p style="color:red;text-align:center;" id="rserror"></p>
				 <span class='msg'></span>

				<span id="user-result"></span>

				<hr>

					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<input class="btn-u btn-block" value="Reset Password" type="submit" id="submit-fpass">
						</div>
					</div>
				<br>
				<br>
				<p class="ruls">Your password must have:</p>
				<ul>
					<li>At least eight characters</li>
					<li>Numbers and letters</li>
					<li>Upper and lower case letters</li>
					<li>Special characters</li>
				</ul>


				</form>
				<?php } ?>
			</div>
			<!--End Reg Block-->
		</div>
	</div><!--/container-->

	<!--=== End Content Part ===-->
	<!-- JS Global Compulsory -->
		<script type="text/javascript" src="assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery-migrate-1.4.1.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.0.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.2.min.js"></script>
	<?php if($error ==0){ ?>
		<link rel="stylesheet" property="stylesheet" href="assets/css/jshake-1.1.min.css">
		<script type="text/javascript" src="assets/js/jshake-1.1.min.js"></script>
		<script type="text/JavaScript" src="assets/js/sha512.js"></script>
		<script type="text/JavaScript" src="assets/js/forms.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#submit-fpass").click(function(){
				  pw=$("#password").val();
				  cpw=$("#cpassword").val();
				  pwkey=$("#pwkey").val();
				  if(pw==""){
					$('#shake').jshake();
					$("#rserror").html("Please enter Password");
				  }else if(/^([a-zA-Z0-9\W]){8,20}$/.test(pw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password length must be between 8 - 20 charaters!");
				  }else if(/\d{1}/.test(pw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one number!");
				  }else if(/[a-zA-Z]{1}/.test(pw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one letter!");
				  }else if(/[A-Z]{1}/.test(pw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one CAPS!");
				  }else if(/\W{1}/.test(pw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one symbol!");
				  }else if(cpw==""){
					$('#shake').jshake();
					$("#rserror").html("Please enter Confirm Password");
				  }else if(/^([a-zA-Z0-9\W]){8,20}$/.test(cpw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password length must be between 8 - 20 charaters!");
				  }else if(/\d{1}/.test(cpw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one number!");
				  }else if(/[a-zA-Z]{1}/.test(cpw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one letter!");
				  }else if(/[A-Z]{1}/.test(cpw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one CAPS!");
				  }else if(/\W{1}/.test(cpw)== false){
					$('#shake').jshake();
					$("#rserror").html("Password must include at least one symbol!");
				  }else if(pw!=cpw){
					$('#shake').jshake();
					$("#rserror").html("Password and Confirm password not matched!");
				  }else {
					  $.ajax({
					   type: "POST",
					   url: "assets/includes/forgotpassword.inc.php",
						data: "pw="+ajaxformhash(pw)+"&pwkey="+pwkey,
					   success: function(status){
						if(status==true)    {
						 $("form").html("Password changed successfully. You will be redirected to login page shortly!");
							setTimeout(function(){
								window.location.href = './';
								//window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/login.php");
							},5000);
						}else{
							$('#shake').jshake();
							$("#rserror").html(status);
							if(status==""){status = "Password reset failed.  Try again later.  If this issue persists, please contact support@vervantis.com.";}
							$("#rserror").html(status);
							//$("#error").html("Error Occured. Please contact admin!");
						}
					   },
					   beforeSend:function()
					   {
						//$("#add_err").css('display', 'inline', 'important');
						//$("#add_err").html("<img src='images/ajax-loader.gif' /> Loading...")
					   }
					  });
				  }
				return false;
			});
		});

		function showpw(pwname) {
		  var x = document.getElementById(pwname);
		  if (x.type === "password") {
			x.type = "text";
		  } else {
			x.type = "password";
		  }
		}
		</script>
	<?php } ?>
	<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- JS Implementing Plugins -->
	<script type="text/javascript" src="../assets/plugins/countdown/jquery.plugin.min.js"></script>
	<script type="text/javascript" src="../assets/plugins/countdown/jquery.countdown.min.js"></script>
	<script type="text/javascript" src="../assets/plugins/backstretch/jquery.backstretch.min.js"></script>
	<script type="text/javascript">
		$.backstretch([
		  "../assets/img/bg-login2.jpg",
		  "../assets/img/bg-login.jpg",
		  ], {
			fade: 1000,
			duration: 7000
		});
	</script>
	<!-- JS Page Level -->
	<script type="text/javascript" src="../assets/js/app.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			App.init();
	<?php
		if(isset($error) and $error==2){
	?>
			setTimeout(function(){
				forgotpw();
				//window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/forgotpassword.php");
			},5000);
		});

		function forgotpw(cvalue="load"){
			var minutes=1;
			var cname="forgotpassword";
			if (minutes) {
				var date = new Date();
				date.setTime(date.getTime()+(minutes*60*1000));
				var expires = "; expires="+date.toGMTString();
			} else {
				var expires = "";
			}
			document.cookie = cname+"="+cvalue+expires+"; path=/";
			window.location.href = './';
		}
	<?php
		}
	?>
	</script>
	<!--[if lt IE 9]>
		<script src="../assets/plugins/respond.js"></script>
		<script src="../assets/plugins/html5shiv.min.js"></script>
	<![endif]-->



	</body>
	</html>

<?php
}elseif(isset($_COOKIE["forgotpassword"])){
	$usermail="";
	if($_COOKIE["forgotpassword"] != "load") $usermail= @htmlspecialchars($_COOKIE["forgotpassword"]) ;
	setcookie("forgotpassword", "", time() - 3600);
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Vervantis | Client Password Change</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vervantis Energy Consultants offer clients secure cloud based access to all their energy and utility data via our online portal. Change password here…">
	<meta name="robots" content="index, follow">

	<!-- FAVICONS -->
	<link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="../assets/plugins/line-icons/line-icons.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!-- CSS Page Style -->
    <link rel="stylesheet" href="../assets/css/pages/page_log_reg_v2.css">

    <!-- CSS Theme -->
    <link rel="stylesheet" href="../assets/css/themes/blue.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="../assets/css/custom.css">

	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
    <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.ui.shake.js"></script>
	<script type="text/javascript" src="//www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
	<?php
	if (isset($_GET['error'])) {
		if(isset($_GET['errormsg']))
			echo '<p class="error">'.@htmlspecialchars($_GET['errormsg'], ENT_QUOTES).'</p>';
		else
			echo '<p class="error">Error Logging In!</p>';
	}
	?>

<!--=== Content Part ===-->
<div class="container">
	<div id="shake">
		<!--Reg Block-->
		<div class="reg-block" id="box">
			<div class="clogo">Vervantis</div>
			<div class="dlogo">DataHub</div>
			<div class="reg-block-header">
				<h2>Reset Password</h2>
			</div>

			<form action="#" method="post" name="login_form" onsubmit="return false">
			<div class="input-group margin-bottom-20">
				<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				<input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?php if ($usermail != "") echo filter_var($usermail, FILTER_SANITIZE_EMAIL); else echo ''; ?>">
			</div>
			<div class="input-group div-captcha">
				<div class="g-recaptcha" data-sitekey="6LdcblUUAAAAAEwZWBWHZL7v5FIcv-EgazHsavis" data-expired-callback="recaptchaExpired"></div>
			</div>
			<p style="color:red;text-align:center;" id="fperror"></p>
			 <span class='msg'></span>

			<span id="user-result"></span>

			<hr>

				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<input class="btn-u btn-block" value="Submit" type="submit" id="submit-fpass">
					</div>
				</div>
			</form>
		</div>
		<!--End Reg Block-->
	</div>
</div><!--/container-->

<!--=== End Content Part ===-->

<!-- JS Global Compulsory -->
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-1.4.1.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.0.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.2.min.js"></script>
	<link rel="stylesheet" property="stylesheet" href="assets/css/jshake-1.1.min.css">
	<script type="text/javascript" src="assets/js/jshake-1.1.min.js"></script>
	<script type="text/JavaScript" src="assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="assets/js/forms.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#submit-fpass").click(function(){
				$("#fperror").html('');
			  username=$("#email").val();
			  if(username==""){
				$('#shake').jshake();
				$("#fperror").html("Please enter email.");
			  }else {
				  $.ajax({
				   type: "POST",
				   url: "assets/includes/forgotpassword.inc.php",
					data: "email="+username+"&captcha="+grecaptcha.getResponse(),
				   success: function(status){
					if(status==true){
					 //$("form").html("If you are already a registered user, you'll receive a password reset email shortly.<br><br><i>Note: Please check your spam/junk mail folder if the email does not show up in your inbox.</i>");
					 $("form").html("The email with further instructions was sent to the submitted email address. <br>If you don’t receive a message in 5 minutes, check the junk folder. <br>If you are still experiencing any problems, contact support at support@vervantis.com");
					}else if(status=="wrongcaptcha"){
						grecaptcha.reset();
						$('#shake').jshake();
						$("#fperror").html("Please verify that you are not a robot");
					}else{
						grecaptcha.reset();
						$('#shake').jshake();
						if(status==""){status = "Password reset failed.  Try again later.  If this issue persists, please contact support@vervantis.com.";}
						$("#fperror").html(status);
						//$("#error").html("Error Occured. Please try after sometime!");
					}
				   },
				   beforeSend:function()
				   {
					//$("#add_err").css('display', 'inline', 'important');
					//$("#add_err").html("<img src='images/ajax-loader.gif' /> Loading...")
				   }

				  });
			  }
			return false;
		});
		$(".clogo,.dlogo").click(function(){
			window.location.href = './';
		});
	});
	</script>
<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- JS Implementing Plugins -->
<script type="text/javascript" src="../assets/plugins/countdown/jquery.plugin.min.js"></script>
<script type="text/javascript" src="../assets/plugins/countdown/jquery.countdown.min.js"></script>
<script type="text/javascript" src="../assets/plugins/backstretch/jquery.backstretch.min.js"></script>
<script type="text/javascript">
    $.backstretch([
      "../assets/img/bg-login2.jpg",
      "../assets/img/bg-login.jpg",
      ], {
        fade: 1000,
        duration: 7000
    });
</script>
<!-- JS Page Level -->
<script type="text/javascript" src="../assets/js/app.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
    });
</script>
<!--[if lt IE 9]>
    <script src="../assets/plugins/respond.js"></script>
    <script src="../assets/plugins/html5shiv.min.js"></script>
<![endif]-->


</body>
</html>

<?php
}else{
//}elseif(login_check($mysqli) !== true and !isset($_COOKIE["forgotpassword"]) and !isset($_COOKIE["resetpw"])){
	$disablecaptcha=0;

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$clientip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$clientip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$clientip = $_SERVER['REMOTE_ADDR'];
	}

    if($stmt = $mysqli->prepare("SELECT date FROM `user_tracking` where ipaddress='".$mysqli->real_escape_string($clientip)."' LIMIT 1 "))
	{
        $stmt->execute();
        $stmt->store_result();
		if ($stmt->num_rows == 1) {
			$disablecaptcha=1;
		}
	}

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Vervantis | Client Login</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vervantis Energy Consultants offer clients secure cloud based access to all their energy and utility data via our online portal. Log-in here…">
	<meta name="robots" content="index, follow">

	<!-- FAVICONS -->
	<link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">

    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="../assets/plugins/line-icons/line-icons.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!-- CSS Page Style -->
    <link rel="stylesheet" href="../assets/css/pages/page_log_reg_v2.css">

    <!-- CSS Theme -->
    <link rel="stylesheet" href="../assets/css/themes/blue.css">

    <!-- CSS Customization -->
    <link rel="stylesheet" href="../assets/css/custom.css">

    <!-- CSS Login -->
    <link rel="stylesheet" href="../assets/css/login.css">

	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
    <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.ui.shake.js"></script>
	<?php if($disablecaptcha==0){ ?>
	<script type="text/javascript" src="//www.google.com/recaptcha/api.js" async defer></script>
	<?php } ?>
</head>

<body>
	<?php
$ressbrowser= get_browser(null, true);
if(is_array($ressbrowser) and isset($ressbrowser["browser"])){
	$browsername=strtolower($ressbrowser["browser"]);
	if($browsername=="firefox" || $browsername=="chrome" || $browsername=="opera" || $browsername=="safari" || $browsername=="microsoft edge"){
	}else{ ?>
		<h3 align="center" style="color:#fffaf0 !important">Browser Not Supported</h3>
		<h5 align="center" style="color:#fffaf0 !important">Please use Chrome, Firefox, Safari, Edge or Opera for best performance.</h5>

	<?php }
}
	?>
<!--=== Content Part ===-->
<div class="container">
	<div id="shake">
		<!--Reg Block-->
		<div class="reg-block" id="box">
			<div class="clogo">Vervantis</div>
			<div class="dlogo">DataHub</div>
			<div class="reg-block-header hidden">
				<h2>Sign In</h2>
			</div>

			<form action="#" method="post" name="login_form" id="login_form" onsubmit="return false">
			<div class="input-group margin-bottom-20">
				<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				<input type="text" class="form-control" placeholder="Email" name="email" id="email">
			</div>
			<div class="input-group margin-bottom-20">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" placeholder="Password" name="password" id="password">
				<input type="hidden" name="xit6n" id="xit6n" value="3852h5">
				<span class="input-group-addon" onclick="showpw()"><i class="fa fa-eye-slash"></i></span>
			</div>
			<?php if($disablecaptcha==0){ ?>
			<div class="input-group div-captcha">
				<div class="g-recaptcha" data-sitekey="6LdcblUUAAAAAEwZWBWHZL7v5FIcv-EgazHsavis" data-expired-callback="recaptchaExpired"></div>
			</div>
			<?php } ?>
			<p id="error">	<?php if (isset($_GET['error'])) {
				if(@strtolower($_GET['error'])=="system error" or @strtolower($_GET['error'])=="system errors") {
					if(@strtolower($_GET['error'])=="system error"){ ?>
					<script>window.top.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/includes/logout.php?error=system errors");</script>
					<?php }
					echo "The system is currently unavailable.<br>It will be back online shortly.<br>Please contact support@vervantis.com<br>for more information";
			}else echo @htmlspecialchars($_GET['error'], ENT_QUOTES); }
			?></p>
			<div class='progress-ring'>
				<div class='progress-ring__wrap'>
					<div class='progress-ring__circle'></div>
				</div>
				<div class='progress-ring__wrap'>
					<div class='progress-ring__circle'></div>
				</div>
				<div class='progress-ring__wrap'>
					<div class='progress-ring__circle'></div>
				</div>
				<div class='progress-ring__wrap'>
					<div class='progress-ring__circle'></div>
				</div>
				<div class='progress-ring__wrap'>
					<div class='progress-ring__circle'></div>
				</div>
			</div>
			<!--<?php
				/*$error = $_GET['error'];
				if ($error==1)
				{
					print "<p>Invalid email or password</p>";

				}
				elseif ($error==2)
				{
					print "<p>Too many login attempts.  Click to <a class=color-green href=page_registration1.html>reset</a></p>";
				}*/
			?>-->
			 <span class='msg'></span>

			<span id="user-result"></span>

			<hr>

				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<input class="btn-u btn-block" value="Log In" type="submit" id="submit-login">
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-6 col-md-offset-1">
					<div class="checkbox">
						<input id="alwaysin" type="checkbox">
						<p>Always stay signed in</p>
					</div>
				</div>
				<div class="col-md-5">
					<p></p>
					<p><a class="color-green" href="javascript:void(0)" onclick="forgotpw()">Forgot Login?</a></p>
				</div>
			</div>
		</div>
		<!--End Reg Block-->
	</div>
</div><!--/container-->
<!--=== End Content Part ===-->

<!-- JS Global Compulsory -->
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-1.4.1.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.0.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-migrate-3.3.2.min.js"></script>
	<link rel="stylesheet" property="stylesheet" href="assets/css/jshake-1.1.min.css">
	<script type="text/javascript" src="assets/js/jshake-1.1.min.js"></script>
	<script type="text/JavaScript" src="assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="assets/js/forms.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		var refreshme='';
		$("#submit-login").click(function(){
			$("#error").html("");
			$(".progress-ring").show();
			  username=$("#email").val();
			  encsi=$("#xit6n").val();
			  p=ajaxformhash($("#password").val());
			  alwaysin=$('#alwaysin').is(':checked');
			  $.ajax({
			   type: "POST",
			   url: "assets/includes/process_login.php",
				data: "email="+username+"&password=&csrf-token="+p+"&encsi="+encsi+"&alwaysin="+alwaysin+"&refresh="+refreshme<?php if($disablecaptcha==0){ ?>+"&captcha="+ grecaptcha.getResponse()<?php } ?>,
			   success: function(status){
				$(".progress-ring").delay(1000).hide();
				if(status==true){
				 window.location="/";
				}else{
					<?php if($disablecaptcha==0){ ?>
					grecaptcha.reset();
					<?php } ?>
					$(".progress-ring").delay(1).hide();
					if(status=="changepwd"){
						$('#shake').jshake();
						$("#error").html("Your Password is more than 90 days old.<br> You will be redirected to password reset page shortly!");
						setTimeout(function(){
							forgotpw(username);
							//window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/forgotpassword.php?email="+username);
						},5000);
					}else if(status=="duplicatelog"){
						if (confirm("A user is logged in with same email. Do you want to kick out the existing user?") == true) {
							refreshme='Yes';
							$("#submit-login").click();
						}
					}<?php if($disablecaptcha==0){ ?>else if(status=="wrongcaptcha"){
						$('#shake').jshake();
						$("#error").html("Please verify that you are not a robot");
					}<?php } ?>else if(status=="blocked"){
						$('#shake').jshake();
						$("#error").html("Your account is Locked Out.<br> Please contact Vervantis Support!");
					}else if(status=="pwreset"){
						$('#shake').jshake();
						$("#error").html("Please change password.<br> You will be redirected to password reset page shortly!");
            setTimeout(function(){
							forgotpw(username);
							//window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/forgotpassword.php?email="+username);
						},5000);
					}else if(status=="inactive"){
						$('#shake').jshake();
						$("#error").html("Your account is inactive.<br> Please contact Vervantis Support!");
					}else{
						$('#shake').jshake();
						$("#error").html("Invalid email or password");
					}
				//$("#add_err").css('display', 'inline', 'important');
				 //$("#add_err").html("<img src='images/alert.png' />Wrong username or password");
				}
			   },error: function(jqXHR, exception) {$(".progress-ring").delay(200).hide(); $("#error").html("Error Occured. Please try after sometime!");},
			   beforeSend:function()
			   {
				//$("#add_err").css('display', 'inline', 'important');
				//$("#add_err").html("<img src='images/ajax-loader.gif' /> Loading...")
			   }
			  });
			return false;
		});
	});

	function showpw() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
		x.type = "text";
	  } else {
		x.type = "password";
	  }
	}
	function recaptchaExpired(){
		//grecaptcha.reset();
		location.reload();
	}

	function forgotpw(cvalue="load"){
		var minutes=1;
		var cname="forgotpassword";
		if (minutes) {
			var date = new Date();
			date.setTime(date.getTime()+(minutes*60*1000));
			var expires = "; expires="+date.toGMTString();
		} else {
			var expires = "";
		}
		document.cookie = cname+"="+cvalue+expires+"; path=/";
		window.location.href = './';
	}
	</script>
<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- JS Implementing Plugins -->
<script type="text/javascript" src="../assets/plugins/countdown/jquery.plugin.min.js"></script>
<script type="text/javascript" src="../assets/plugins/countdown/jquery.countdown.min.js"></script>
<script type="text/javascript" src="../assets/plugins/backstretch/jquery.backstretch.min.js"></script>
<script type="text/javascript">
    $.backstretch([
      "../assets/img/bg-login2.jpg",
      "../assets/img/bg-login.jpg",
      ], {
        fade: 1000,
        duration: 7000
    });
</script>
<!-- JS Page Level -->
<script type="text/javascript" src="../assets/js/app.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
    });
</script>
<!--[if lt IE 9]>
    <script src="../assets/plugins/respond.js"></script>
    <script src="../assets/plugins/html5shiv.min.js"></script>
<![endif]-->


</body>
</html>
<?php

}
?>
