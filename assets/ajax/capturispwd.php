<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

//if(!isset($_SESSION))
sec_session_start();


if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1))
	die("Restricted Access!");
//if(checkpermission($mysqli,34)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

?>

<div class="row dashboard-content">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa-fw fa fa-home"></i> 
				Admin <span>> Change capturis password</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="s3section fixed">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Capturis Password Edit </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">	
	
	
	
		<article class="col-sm-12 p0">
			<div class="row">
				<form id="capturis-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data"  onsubmit="return checksubmit()">
					<fieldset>
						<div class="row">
							<section class="col col-6">Username
								<label class="input"> <i class="icon-prepend fa fa-user"></i>
									<input type="text" name="username" id="username" placeholder="Username" value="">
								</label>
							</section>
							<section class="col col-6">Current Password
								<label class="input"> <i class="icon-prepend fa fa-lock"></i>
									<input type="text" name="password" id="password" placeholder="password" value="">
								</label>
							</section>
							<section class="col col-6">New Password
								<label class="input"> <i class="icon-prepend fa fa-lock"></i>
									<input type="text" name="newPwd" id="newPwd" placeholder="New password" value="">
								</label>
							</section>
						</div>
					</fieldset>
					<footer style="text-align:center;">
						<button type="submit" class="btn btn-primary" id="capturissubmit" style="float:none !important;">
							Submit
						</button>
					</footer>
				</form>
			</div>
		</article>
		
		
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->	
		</article>
		
	</div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){


	 $("#capturissubmit").click(function(){
		var uName=$("#username").val();
		var passwrd=$("#password").val();
		var newPwd=$("#newPwd").val();	
		
		if(uName=="" || passwrd=="" || newPwd==""){alert("Username or Current Password Or New Password cannot be empty");}
		else{ 
			$.ajax({
				type: "POST",
				url: "assets/includes/capturispw.php",
				data: "uName="+uName+"&passwrd="+passwrd+"&newPwd="+newPwd,
				success: function(status){
					alert(status);
				},
				error: function (xhr,ajaxOptions,throwError){
					alert("Error Occured.");
				  },
			});	
		}
	})
});

function checksubmit(){
	
	return false;
	
}
</script>

