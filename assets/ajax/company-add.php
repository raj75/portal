<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");
?>
		<div id="add-dialog-message" title="Add Profile">
						<form id="add-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company Name
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="addcname" id="addcname" placeholder="Company name" value="">
										</label>
									</section>
									<section class="col col-6">Skype
										<label class="input"> <i class="icon-prepend fa fa-skype"></i>
											<input type="text" name="addskype" id="addskype" placeholder="Skype" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Foundation Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="addfoundationdate" id="addfoundationdate" placeholder="Foundation date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
									<section class="col col-6">Company Profile Image
										<label for="file" class="input input-file">
											<div class="button"><input type="file" name="addfile" id="addfile" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Profile Image" readonly="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">UBM
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="ubm" id="ubm">
												<option value="Capturis">Capturis</option>
												<option value="Cass">Cass</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-6">UBM Archive
										<label class="select"> <i class="icon-append fa fa-unlock"></i>
											<select name="ubmarchive" id="ubmarchive">
												<option value="None">None</option>
												<option value="Capturis">Capturis</option>
												<option value="Cass">Cass</option>
											</select> <i></i> </label>
									</section>
								</div>
							</fieldset>

							<fieldset>
								<section>Description
									<label class="textarea">
										<textarea rows="3" name="adddescription" id="adddescription" placeholder="Description"></textarea>
										<input type="hidden" name="addnew" id="addnew" placeholder="Title" value="new">
									</label>
								</section>
							</fieldset>

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
<script src="assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="assets/js/sha512.js"></script>
<script type="text/JavaScript" src="assets/js/forms.js"></script>
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

	//$('#add-dialog-message').dialog('open'); .on('changeDate', function(e) {
       // $('#add-profileForm').formValidation('revalidateField', 'addbirthdate');
    //})
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

		$("#add-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New Company</h4></div>",
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
				$("#add-dialog-message").dialog('destroy');
				$("#add-dialog-message").remove();
				parent.$("#response").html('');
              }
		});

		$('#add-profile-cancel').click(function() {
			$("#add-dialog-message").dialog("close");
			$("#add-dialog-message").dialog('destroy');
			$("#add-dialog-message").remove();
			parent.$("#response").html('');
		});



		var $checkoutForm = $('#add-checkout-form').validate({
		// Rules for form validation
			rules : {
				addcname : {
					required : true
				},
				addfoundaationdate : {
					required : true
				},
				addcompany : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				addcname : {
					required : 'Please enter your company name'
				},
				addfoundationdate : {
					required : 'Select foundation date'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('cname', $("#addcname").val());
				formData.append('skype', $("#addskype").val());
				formData.append('foundationdate', $("#addfoundationdate").val());
				formData.append('description', $("#adddescription").val());
				formData.append('ubm', $("#ubm").val());
				formData.append('ubmarchive', $("#ubmarchive").val());
				formData.append('file', $("#addfile")[0].files[0]);
				formData.append('new', $("#addnew").val());

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
								$("#add-dialog-message").dialog("close");
								$("#add-dialog-message").dialog('destroy');
								$("#add-dialog-message").remove();
								parent.$("#response").html('');
								parent.$("#dtable").html('');
								parent.$('#dtable').load('assets/ajax/company-pedit.php');
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

	function profileAdd(){
	}
</script>
