<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(!isset($_SESSION["user_id"]))
	die("Resctricted Access!");

/*if(isset($_GET["default"]) and $_GET["default"] == "true" and isset($_SESSION["user_id"]))
	$_GET["userid"]=$_SESSION["user_id"];*/

if(!isset($_GET["cid"]) or $_GET["cid"] == "")
	die("Wrong Parameters");

$cid=$mysqli->real_escape_string($_GET["cid"]);

	$_firstname=$_lastname=$_phone=$_email=$_skype=$_aboutme=$_company="N/A";
   if ($stmt = $mysqli->prepare('SELECT company_name,foundation_date,about_company_details,status,ubm_type,ubmarchive_type FROM `company` where company_id='.$cid.' LIMIT 1')) {

//('SELECT company_name,skype,foundation_date,about_company_details,status FROM `company` where id='.$cid.' LIMIT 1'))

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
                $stmt->bind_result($_cname,$_foundation_date,$_about_company_details,$_status,$_ubm,$_ubmarchive);
                $stmt->fetch();
				if($_foundation_date == "" || $_foundation_date == "0000-00-00" || $_foundation_date == "1970-01-01") $_foundation_date=date("Y-m-d");
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

	$_cuserimage=checks3img(md5($cid).".png","profiles/company/logo/","blank-logo.png");
	if($_cuserimage==false){$_cuserimage="";}
	/*if(!file_exists("../../../uploads/profiles/company/logo/".md5($cid).".png"))
		$_cuserimage="blank-logo.png";
	else
		$_cuserimage=md5($cid).".png";*/

	$_companyname=ucfirst(strtolower($_cname));

	//SELECT groupname FROM user u,usergroups ug where u.usergroups_id=ug.id

?>

					<div id="company-profile-dialog-message" class="profile-edit-form" title="Edit Profile">
						<form id="checkout-form" class="smart-form" novalidate="novalidate" method="post" onsubmit="return profileEdit()" enctype="multipart/form-data">

							<fieldset>
								<div class="row">
									<center><img class="ss<?php echo time(); ?>" src="<?php echo $_cuserimage; ?>" width="100px">
									</center>
								</div>

								<div class="row">
									<section class="col col-6"><span>Company Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="cname" id="cname" placeholder="Company name" value="<?php echo $_cname; ?>">
										</label>
									</section>
									<section class="col col-6">Foundation Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="foundationdate" id="foundationdate" placeholder="foundationdate" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($_foundation_date)); ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM<?php echo (($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2)?'':' (<i>readonly</i>)'); ?>
										<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2 ){ ?>
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="ubm" id="ubm">
												<option value="Capturis" <?php if(@trim($_ubm)=="Capturis"){echo 'SELECTED="SELECTED"';} ?>>Capturis</option>
												<option value="Cass" <?php if(@trim($_ubm)=="Cass"){echo 'SELECTED="SELECTED"';} ?>>Cass</option>
											</select> <i></i> </label>
									<?php }else{ ?>
											<input type="text" placeholder="UBM" value="<?php if(@trim($_ubm)=='Capturis'){ echo 'Capturis';}elseif(@trim($_ubm)=='Cass'){echo 'Cass';} ?>">
									<?php } ?>
									</section>
									<section class="col col-6">UBM Archive<?php echo (($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2)?'':' (<i>readonly</i>)'); ?>
										<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2 ){ ?>
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="ubmarchive" id="ubmarchive">
												<option value="None" <?php if(@trim($_ubmarchive)=="None"){echo 'SELECTED="SELECTED"';} ?>>None</option>
												<option value="Capturis" <?php if(@trim($_ubmarchive)=="Capturis"){echo 'SELECTED="SELECTED"';} ?>>Capturis</option>
												<option value="Cass" <?php if(@trim($_ubmarchive)=="Cass"){echo 'SELECTED="SELECTED"';} ?>>Cass</option>
											</select> <i></i> </label>
									<?php }else{ ?>
											<input type="text" placeholder="UBM Archive" value="<?php if(@trim($_ubmarchive)=='None'){ echo 'None';}elseif(@trim($_ubmarchive)=='Capturis'){ echo 'Capturis';}elseif(@trim($_ubmarchive)=='Cass'){echo 'Cass';} ?>">
									<?php } ?>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Company Logo
										<label for="file" class="input input-file">
											<div class="button"><input type="file" name="file" id="file" onchange="this.parentNode.nextSibling.value = this.value.replace(/^.*(\\|\/|\:)/, '')">Browse</div><input type="text" placeholder="Profile Image" readonly="">
										</label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<section>Description
									<label class="textarea">
										<textarea rows="3" name="description" id="description" placeholder="Description"><?php echo $_about_company_details; ?></textarea>
										<input type="hidden" name="cpy" id="cpy" value="<?php echo $_cname; ?>">
										<input type="hidden" name="edit" id="edit" placeholder="Title" value="edit">
									</label>
								</section>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="profile-submit">
									Submit
								</button>
								<button type="button" class="btn" id="profile-cancel">
									Cancel
								</button>
							</footer>
						</form>
					</div>
<script src="assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="assets/js/sha512.js"></script>
<script type="text/JavaScript" src="assets/js/forms.js"></script>
<script type="text/javascript">
$(function() {
$(document).ready(function() {
	$('#datePicker')
	.datepicker({
		format: 'mm/dd/yyyy'
	}).on('changeDate', function(e) {
        $('#profileForm').formValidation('revalidateField', 'foundationdate');
    });
	//$('#company-profile-dialog-message').dialog('open');
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

		$("#company-profile-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Company Profile</h4></div>",
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
				$("#company-profile-dialog-message").dialog('destroy');
				$("#company-profile-dialog-message").remove();
				parent.$("#response").html('');
              }
		});

		$('#profile-cancel').click(function() {
			$("#company-profile-dialog-message").dialog("close");
			$("#company-profile-dialog-message").dialog('destroy');
			$("#company-profile-dialog-message").remove();
			parent.$("#response").html('');
		});



		var $checkoutForm = $('#checkout-form').validate({
		// Rules for form validation
			rules : {
				cname : {
					required : true
				},
				foundationdate : {
					required : true
				}<?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ ?>,
				status : {
					required : true,
					digits : true
				}<?php } ?>
			},

			// Messages for form validation
			messages : {
				cname : {
					required : 'Please enter company name'
				},
				foundationdate : {
					required : 'Select foundation date'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				/*$(form).ajaxSubmit({
					success : function() {
						$("#contact-form").addClass('submited');
					}
				});*/
				var formData = new FormData();
				formData.append('cname', $("#cname").val());
				//formData.append('skype', $("#skype").val());
				formData.append('foundationdate', $("#foundationdate").val());
				formData.append('description', $("#description").val());
				formData.append('ubm', $("#ubm").val());
				formData.append('ubmarchive', $("#ubmarchive").val());
				formData.append('cpy', <?php echo $cid; ?>);
				formData.append('file', $("#file")[0].files[0]);
				formData.append('edit', $("#edit").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/companyedit.inc.php',
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
								$("#company-profile-dialog-message").dialog("close");
								$("#company-profile-dialog-message").dialog('destroy');
								$("#company-profile-dialog-message").remove();
								parent.$("#response").html('');
								//$("#dialog-message").html('');
								parent.$("#dtable").html('');
								parent.$('#dtable').load('assets/ajax/company-pedit.php');
								parent.loadcompanymenu(<?php echo $cid; ?>);

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

	function profileEdit(){
	}
</script>
