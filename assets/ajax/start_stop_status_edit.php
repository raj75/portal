<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");
		
$user_one=$_SESSION["user_id"];
$c_id=$_SESSION["company_id"];


if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");





if(isset($_GET['sid']) and isset($_GET['edit']) and !isset($_GET['action'])){
	if(isset($_GET['sid']) and @trim($_GET['sid']) != "" and $_GET['sid'] > 0)
		$sid=$mysqli->real_escape_string(@trim($_GET['sid']));
	else
		die('Wrong parameters provided');
?>

	<style>
	.dz-default span{
		left: 33%;
		position: relative;
		top: 40%
	}
	#add-dialog-message{
		overflow:hidden;
	}
	.fi-upload{
		height: auto !important;
	}
	.width-full{
		width:100% !important;
	}
	</style>
		<div id="edit-dialog-message" title="Edit Saving Analysis">
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company
										<label class="select"> <i class="icon-append fa fa-user"></i>

										</label>
									</section>

							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="edit-sa-submit">
									Save
								</button>
								<button type="button" class="btn" id="edit-sa-cancel">
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
	$('.datepicker')
	.datepicker({
		format: 'mm/dd/yyyy',
            changeMonth: true,
            changeYear: true
	});
});
});
	pageSetUp();
	
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
		
		var flen="<?php echo $z;?>";
		
		$('.fi-upload-bt1').click(function() {
			if($(".fi1").length < flen)
			{
				$(".fi1").last().after( '<div class="row fi1"><section class="col col-10"><label class="input"> <i class="icon-append fa fa-upload"></i><input type="file" name="fi-upload[]" class="fi-upload" placeholder="Upload File" /></label></section><section class="col col-2"><label class="input"><input type="button" class="fi-upload-bt" value="-" /></label></section></div>' );
			}else{
				alert("Upload Limit Reached!");
			}
		});
		
		$('#edit-checkout-form').on('click', '.fi-upload-bt', function() {
			$(this).closest(".fi1").remove();
		});
		
		$('.fi-uploaded-bt1').click(function() {
			$(this).closest(".fi1u").remove();
			++flen;
			$(".fi1u").first().children().children().removeClass("hidden");
		});		
		
	
		$("#edit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Saving Analysis</h4></div>",
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
				$("#edit-dialog-message").dialog('destroy');
				$("#edit-dialog-message").remove();
				parent.$("#saresponse").html('');
              }	
		});

		$('#edit-sa-cancel').click(function() {
			$("#edit-dialog-message").dialog("close");
			$("#edit-dialog-message").dialog('destroy');
			$("#edit-dialog-message").remove();
			parent.$("#saresponse").html('');
		});

		
		
		var $checkoutForm = $('#edit-checkout-form').validate({
		// Rules for form validation
			rules : {
				editcid : {
					required : true
				},
				editlocation : {
					required : true
				},
				editcategory : {
					required : true
				},
				editcommodity : {
					required : true
				},
				editstartdate : {
					required : true
				},
				editenddate : {
					required : true
				},
				editsaving : {
					required : true
				},
				editread : {
					required : true
				},
				editdateadded : {
					required : true
				}
			},
	
			// Messages for form validation
			messages : {
				editcid : {
					required : 'Please select company name'
				},
				editlocation : {
					required : 'Please enter location'
				},
				editcategory : {
					required : 'Please enter category'
				},
				editcommodity : {
					required : 'Please enter commodity'
				},
				editstartdate : {
					required : 'Select start date'
				},
				editenddate : {
					required : 'Select end date'
				},
				editsaving : {
					required : 'Please enter saving'
				},
				editread : {
					required : 'Select read'
				},
				editdateadded : {
					required : 'Select date added'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('cid', $("#editcid").val());
				formData.append('location', $("#editlocation").val());
				formData.append('category', $("#editcategory").val());
				formData.append('commodity', $("#editcommodity").val());
				formData.append('startdate', $("#editstartdate").val());
				formData.append('enddate', $("#editenddate").val());
				formData.append('saving', $("#editsaving").val());
				formData.append('read', $("#editread").val());
				formData.append('dateadded', $("#editdateadded").val());
				formData.append('said', $("#editsaid").val());
				formData.append('edit', $("#edit").val());
				var ii=1;
				$(".fi-upload").each(function() {
					
					if($($(this).val() != "") && ii <= flen){
						formData.append('file'+ii, $(this)[0].files[0]); 
						++ii;
					}
				});
				ii=1;
				$(".fuploaded").each(function() {
					
					if($($(this).val() != "") && ii <= 6){
						formData.append('fuploaded'+ii, $(this).val()); 
						++ii;
					}
				});

				$.ajax({
					type: 'post',
					url: 'assets/includes/ssavinganalysisedit.inc.php',
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
								$("#edit-dialog-message").dialog("close");
								$("#edit-dialog-message").dialog('destroy');
								$("#edit-dialog-message").remove();
								parent.$("#saresponse").html('');
								parent.$("#satable").html('');
								parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');								
							}else
								alert("Error in request. Please try again later.");
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




<?	
}

?>