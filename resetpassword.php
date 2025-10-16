<?php
//header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload; X-XSS-protection: 1; mode=block");
if (isset($_GET['key'])) {
	@setcookie("resetpw",@trim($_GET['key']),(time() + (60*1)),"/");
}
header("Location: ./");
die();
?>
<?php
include_once 'assets/includes/db_connect.php';
include_once 'assets/includes/functions.php';

sec_session_start();
if (login_check($mysqli) == true)
   header("Location: index.php");

$error=0;
$errmsg="";
if (isset($_GET['key'])) {
	$pkey = @trim($_GET['key']);
	if($pkey == ""){
		$error=1;
		$errmsg="Error Occured. Please try to contact Vervantis!";
	}else{

//"SELECT id,email FROM user WHERE hkey = ? LIMIT 1";

		//$stmt = $mysqli->prepare("SELECT user_id FROM user WHERE hkey !='' and hkey = ? LIMIT 1");
		$stmt = $mysqli->prepare("SELECT user_id FROM user WHERE hkey !='' and hkey = '".$mysqli->real_escape_string($pkey)."' LIMIT 1");

		if ($stmt) {
			//$stmt->bind_param('s', $pkey);
			$stmt->execute();
			$stmt->store_result();
			$cnt=$stmt->num_rows;
			if ($cnt > 0) {



			}else{
				$error=2;
				$errmsg="Link Expired or Doesn't Exist.<br />You will be redirected to password reset page shortly!</a>";

			}

		}else{
			$error=1;
			$errmsg="Error Occured. Please try to contact Vervantis!";
		}
	}
}else{
	$error=1;
	$errmsg="Error Occured. Please try to contact Vervantis!";

}





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
	if (!isset($_GET['key'])) {

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
				echo "<p style='text-align:center !important;'>".@htmlspecialchars($errmsg, ENT_QUOTES)."</p>";


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
				<input type="hidden" class="form-control" name="pwkey" id="pwkey" value="<?php echo $_GET['key']; ?>">
			</div>
			<p style="color:red;text-align:center;" id="error"></p>
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
	<script type="text/javascript" src="assets/plugins/jquery/jquery.min.js"></script>
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
				$("#error").html("Please enter Password");
			  }else if(/^([a-zA-Z0-9\W]){8,20}$/.test(pw)== false){
				$('#shake').jshake();
				$("#error").html("Password length must be between 8 - 20 charaters!");
			  }else if(/\d{1}/.test(pw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one number!");
			  }else if(/[a-zA-Z]{1}/.test(pw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one letter!");
			  }else if(/[A-Z]{1}/.test(pw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one CAPS!");
			  }else if(/\W{1}/.test(pw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one symbol!");
			  }else if(cpw==""){
				$('#shake').jshake();
				$("#error").html("Please enter Confirm Password");
			  }else if(/^([a-zA-Z0-9\W]){8,20}$/.test(cpw)== false){
				$('#shake').jshake();
				$("#error").html("Password length must be between 8 - 20 charaters!");
			  }else if(/\d{1}/.test(cpw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one number!");
			  }else if(/[a-zA-Z]{1}/.test(cpw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one letter!");
			  }else if(/[A-Z]{1}/.test(cpw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one CAPS!");
			  }else if(/\W{1}/.test(cpw)== false){
				$('#shake').jshake();
				$("#error").html("Password must include at least one symbol!");
			  }else if(pw!=cpw){
				$('#shake').jshake();
				$("#error").html("Password and Confirm password not matched!");
			  }else {
				  $.ajax({
				   type: "POST",
				   url: "assets/includes/forgotpassword.inc.php",
					data: "pw="+ajaxformhash(pw)+"&pwkey="+pwkey,
				   success: function(status){
					if(status==true)    {
					 $("form").html("Password changed successfully. You will be redirected to login page shortly!");
						setTimeout(function(){
							window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/login.php");
						},5000);
					}else{
						$('#shake').jshake();
						$("#error").html(status);
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
<script type="text/javascript" src="../assets/plugins/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- JS Implementing Plugins -->
<script type="text/javascript" src="../assets/plugins/countdown/jquery.countdown.js"></script>
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
			window.location.replace("https://<?php echo $_SERVER['HTTP_HOST']; ?>/forgotpassword.php");
		},5000);
    });
<?php
	}
?>
</script>
<!--[if lt IE 9]>
    <script src="../assets/plugins/respond.js"></script>
    <script src="../assets/plugins/html5shiv.js"></script>
<![endif]-->

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29166220-1']);
  _gaq.push(['_setDomainName', 'htmlstream.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>



</body>
</html>
