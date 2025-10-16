<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


if(isset($_SESSION['group_id']) and ($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2))
{

	if(isset($_GET["action"]) and $_GET["action"]=="edit" and isset($_GET["fiid"]) and @trim($_GET["fiid"]) != 0 and @trim($_GET["fiid"]) != ""){
		$tmp_fiid=$mysqli->real_escape_string(@trim($_GET["fiid"]));

		if ($stmtk = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$tmp_fiid."' LIMIT 1")) {

//("SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.id and fi.id='".$tmp_fiid."' LIMIT 1")) {

			$stmtk->execute();
			$stmtk->store_result();
			if ($stmtk->num_rows > 0) {
				$stmtk->bind_result($fi_Id,$fi_Companyid,$c_Companyname,$fi_Category,$fi_Description,$fi_Link,$fi_Read,$fi_Dateadded);
				$stmtk->fetch();
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
	.width-full,.col-12{
		width:100% !important;
	}
	#edit-dialog-message footer{text-align:center;}
	#edit-dialog-message footer button{float:none !important;}
	#edit-dialog-message #fi-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#edit-dialog-message .dropzone .dz-preview .dz-details .dz-size,#edit-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#edit-dialog-message #fi-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#edit-dialog-message .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#edit-dialog-message .col12{width:100% !important;}
	.ui-dialog-title{
		width: 100%;
		text-align: center;
	}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
			<div id="edit-dialog-message" title="Edit Focus Items">
							<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

								<fieldset>
									<div class="row">
										<section class="col col-6">Company Name
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="editcid" id="editcid" placeholder="Company Name" class="selectautosave" saveme="company_id">
												<option value="">&nbsp;&nbsp;Company Name</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company order by company_name ')){

//('SELECT id,company_name FROM company order by company_name ')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__companyname);
														while($stmt->fetch()){
															echo "<option value='".$__id."' ".($fi_Companyid == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__companyname."</option>";
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
										<section class="col col-6">Category
											<label class="select"> <i class="icon-append fa fa-sitemap"></i>
												<select name="editcategory" id="editcategory" placeholder="Category" class="selectautosave" saveme="category">
													<option value="">&nbsp;&nbsp;Category Name</option>
													<option value="1" <?php echo ($fi_Category == 1?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Fixed Price</option>
													<option value="2" <?php echo ($fi_Category == 2?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Index/Basis + Adder</option>
													<option value="3" <?php echo ($fi_Category == 3?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Heat Rate</option>
													<option value="4" <?php echo ($fi_Category == 4?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Hedge Block</option>
													<option value="5" <?php echo ($fi_Category == 5?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Blend and Extend</option>
													<option value="6" <?php echo ($fi_Category == 6?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Rate and Tariff Analysis</option>
													<option value="7" <?php echo ($fi_Category == 7?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Procurement Recommendation</option>
													<option value="8" <?php echo ($fi_Category == 8?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Budget Report</option>
													<option value="9" <?php echo ($fi_Category == 9?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Meeting Agenda</option>
													<option value="10" <?php echo ($fi_Category == 10?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;New Market Summary</option>
													<option value="11" <?php echo ($fi_Category == 11?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Other</option>
												</select>
											</label>
										</section>
									</div>

									<div class="row">
										<section class="col col-12 width-full">Description
											<label class="textarea"><i class="icon-append fa fa-comment"></i>
												<textarea rows="3" name="editdescription" id="editdescription" placeholder="Description" class="textautosave" saveme="description"><?php echo $fi_Description; ?></textarea>
											</label>
										</section>
									</div>

	<?php
if(1==2){
		$z=6;
		$files_list=array();

		if(@trim($fi_Link) != "")
		{
			$files_list=@explode("@@;@@",$fi_Link);
		}

		$files_len=count($files_list);
		if($files_len > 0)
		{
			$z=$z-$files_len;
			for($i=0;$i<$files_len;$i++)
			{
				//if ($number % 2 == 0) {}
	?>
									<div class="row fi1u">
										<section class="col col-10"><?php if($i==0){echo "Files Uploaded";}else{echo "<font class='stxt hidden'>Files Uploaded</font>";} ?>
											<label class="input">
												<input type="text" readonly="readonly" value="<?php echo $files_list[$i]; ?>" />
												<input type="hidden" class="fuploaded" name="fuploaded<?php echo ($i+1); ?>" value="<?php echo $files_list[$i]; ?>" />
											</label>
										</section>
										<section class="col col-2"><?php if($i==0){echo "&nbsp;";}else{echo "<font class='stxt hidden'>&nbsp;</font>";} ?>
											<label class="input">
												<input type="button" name="fi-uploaded-bt1" id="fi-uploaded-bt1" class="fi-uploaded-bt1" value="-" />
											</label>
										</section>
									</div>
	<?php
			}
		}

			if($z != 0)
			{
	?>
									<div class="row fi1">
										<section class="col col-10">File Upload
											<label class="input"> <i class="icon-append fa fa-upload"></i>
												<input type="file" name="fi-upload[]" id="fi-upload1" class="fi-upload" placeholder="Upload File" />
											</label>
										</section>
										<section class="col col-2">&nbsp;
											<label class="input">
												<input type="button" name="fi-upload-bt1" id="fi-upload-bt1" class="fi-upload-bt1" value="+" />
											</label>
										</section>
									</div>
	<?php
			}
}
	?>

									<div class="row">
										<section class="col col-6">Read
											<label class="select"> <i class="icon-append fa fa-sitemap"></i>
												<select name="editread" id="editread" placeholder="Read" class="selectautosave" saveme="_read">
													<option value="Y" <?php echo ($fi_Read == "Y"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
													<option value="N" <?php echo ($fi_Read != "Y"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
												</select>
											</label>
										</section>
										<section class="col col-6">Date Added
											<label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" name="editdateadded" id="editdateadded" placeholder="Date Added" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($fi_Dateadded)); ?>" saveme="date_added">
												<input type="hidden" name="edit" id="edit" value="edit">
												<input type="hidden" name="editfiid" id="editfiid" value="<?php echo $fi_Id; ?>">
											</label>
										</section>
									</div>

								<div class="row">
									<section class="col col-12">Attached documents
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
									<div id="fis3display"></div>
									<div class="dropzone dz-clickable" id="fi-fileupload">
											<div class="dz-message needsclick">
												<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
												<span class="text-uppercase">Drop files here or click to upload.</span>
											</div>
									</div>
									<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
									<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
									<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
									<script type="text/javascript">
									$(document).ready(function(){
										$('#fis3display').html('');
										$('#fis3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&fimasterid=<?php echo $fi_Id; ?>');
										  var script = document.createElement("script");
  script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
  script.onload = loadedContent;
  document.head.append(script);
  function loadedContent(){
										Dropzone.autoDiscover = false;
										var myDropzone = new Dropzone("div#fi-fileupload", {
											paramName: "fis3filesupload",
											addRemoveLinks: false,
											url: "assets/includes/s3filepermission_2.inc.php?ct=<?php echo rand(2,99); ?>&fimasterid=<?php echo $fi_Id; ?>",
											maxFiles:50,
											uploadMultiple: true,
											parallelUploads:50,
											timeout: 300000,
											maxFilesize: 3000,
											//autoProcessQueue: false,
											init: function() {
												myDropz = this;
												myDropz.on("successmultiple", function(file, result) {
													if (result != false)
													{
														var results = JSON.parse(result);
														if(results.error == "")
														{
															//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
															$('#fis3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&fimasterid=<?php echo $fi_Id; ?>');
															//$("#edit-dialog-message").dialog("close");
															//$("#edit-dialog-message").dialog('destroy');
															//$("#edit-dialog-message").remove();
															//parent.$('#firesponse').load('assets/ajax/focus_items_add.php?action=edit&fiid=<?php echo $fi_Id; ?>');
														}else if(results.error == 5)
														{
															Swal.fire("Error in request.","Please try again later.", "warning");
														}else{
															Swal.fire("Error in request.","Please try again later.", "warning");
														}
													}else{
														Swal.fire("","Error in request. Please try again later.", "warning");
													}
												});
												myDropz.on("complete", function(file) {
												   myDropz.removeAllFiles(true);
												});
											}
										});
  }
									});
									</script>
									</section>
								</div>
								</fieldset>
<?php if(1==2){ ?>
								<footer>
									<button type="submit" class="btn btn-primary" id="edit-fi-submit">
										Save
									</button>
									<button type="button" class="btn" id="edit-fi-cancel">
										Cancel
									</button>
								</footer>
<?php } ?>
							</form>
		</div>

	<!-- end row -->

	</section>
	<!-- end widget grid -->
	<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
	<script type="text/JavaScript" src="../assets/js/forms.js"></script>
	<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
	$(function() {
	$(document).ready(function() {
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
	$('.inputautosave').blur(function() {
	 autosave($(this).attr("saveme"),$(this).val());
	});

	$('.selectautosave').change(function() {
	 autosave($(this).attr("saveme"),$(this).val());
	});

	$('.textautosave').change(function() {
	 autosave($(this).attr("saveme"),$(this).val());
	});

	function autosave(savename,saveval){

		var formData = new FormData();
		formData.append('fiauto', <?php echo $fi_Id; ?>);
		formData.append('fisavename', savename);
		formData.append('fivalue', saveval);

		$.ajax({
			type: 'post',
			url: 'assets/includes/focusitemss3.inc.php',
			data: formData,
			processData: false,
			contentType: false,
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						//Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
						$("a#"+savename+"").removeClass("nodis");
						//$("#edit-dialog-message").dialog("close");
						//$("#edit-dialog-message").dialog('destroy');
						//$("#edit-dialog-message").remove();
						//parent.$("#saresponse").html('');
						//parent.$("#satable").html('');
						//parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
					}else if(results.error == 5)
					{
						Swal.fire("Mandatory:","Plese fill all required fields", "warning");
					}else{
						Swal.fire("Error in request.","Please try again later.", "warning");
					}
				}else{
					Swal.fire("","Error in request. Please try again later.", "warning");
				}
			}
		  });

	}

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
<?php if(1==2){ ?>
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
<?php } ?>
			$("#edit-dialog-message").dialog({
				autoOpen : true,
				modal : true,
				width: "90%",
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Focus Items</h4></div>",
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
					parent.$("#firesponse").html('');
					parent.$("#fitable").html('');
					parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
				  }
			});

			$('#edit-fi-cancel').click(function() {
				$("#edit-dialog-message").dialog("close");
				$("#edit-dialog-message").dialog('destroy');
				$("#edit-dialog-message").remove();
				parent.$("#firesponse").html('');
			});


<?php if(1==2){ ?>
			var $checkoutForm = $('#edit-checkout-form').validate({
			// Rules for form validation
				rules : {
					editcid : {
						required : true
					},
					editcategory : {
						required : true
					},
					editdescription : {
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
						required : 'Please select company'
					},
					editcategory : {
						required : 'Please enter category'
					},
					editdescription : {
						required : 'Please enter description'
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
					formData.append('category', $("#editcategory").val());
					formData.append('description', $("#editdescription").val());
					formData.append('read', $("#editread").val());
					formData.append('dateadded', $("#editdateadded").val());
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
					formData.append('fiid', $("#editfiid").val());
					formData.append('edit', $("#edit").val());

					$.ajax({
						type: 'post',
						url: 'assets/includes/focusitemsedit.inc.php',
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
									parent.$("#firesponse").html('');
									parent.$("#fitable").html('');
									parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
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
<?php } ?>
		};

		var pagedestroy = function() {
			//$('#profileForm').bootstrapValidator('destroy');
		}

		loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);
		//loadScript("assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction);
		// end pagefunction

		// run pagefunction on load

		//pagefunction();

		function profileAdd(){return false;
		}
	</script>
	<?php
			}
		}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
	}elseif(isset($_GET["action"]) and $_GET["action"] == "add"){
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
	#add-dialog-message footer{text-align:center;}
	#add-dialog-message footer button{float:none !important;}
	#add-dialog-message #fi-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#add-dialog-message .dropzone .dz-preview .dz-details .dz-size,#add-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#add-dialog-message .col-12{width:100% !important;}
	#add-dialog-message .ui-datepicker{top:166px !important;}
	#add-dialog-message .col12{width:100% !important;}
	.ui-dialog-title{
		width: 100%;
		text-align: center;
	}
	</style>
	<!-- Add Section -->
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
			<div id="add-dialog-message" title="Add Focus Items">
							<form id="add-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

								<fieldset>
									<div class="row">
										<section class="col col-6">Company Name
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="addcid" id="addcid" placeholder="Company Name" class="">
												<option value="">&nbsp;&nbsp;Company Name</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company order by company_name ')){

//SELECT id,company_name FROM company order by company_name ')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__companyname);
														while($stmt->fetch()){
															echo "<option value='".$__id."'>&nbsp;&nbsp;".$__companyname."</option>";
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
										<section class="col col-6">Category
											<label class="select"> <i class="icon-append fa fa-sitemap"></i>
												<select name="addcategory" id="addcategory" placeholder="Category" class="">
													<option value="">&nbsp;&nbsp;Category Name</option>
													<option value="1">&nbsp;&nbsp;Fixed Price</option>
													<option value="2">&nbsp;&nbsp;Index/Basis + Adder</option>
													<option value="3">&nbsp;&nbsp;Heat Rate</option>
													<option value="4">&nbsp;&nbsp;Hedge Block</option>
													<option value="5">&nbsp;&nbsp;Blend and Extend</option>
													<option value="6">&nbsp;&nbsp;Rate and Tariff Analysis</option>
													<option value="7">&nbsp;&nbsp;Procurement Recommendation</option>
													<option value="8">&nbsp;&nbsp;Budget Report</option>
													<option value="9">&nbsp;&nbsp;Meeting Agenda</option>
													<option value="10">&nbsp;&nbsp;New Market Summary</option>
													<option value="11">&nbsp;&nbsp;Other</option>
												</select>
											</label>
										</section>
									</div>

									<div class="row">
										<section class="col col-12 width-full">Description
											<label class="textarea"><i class="icon-append fa fa-comment"></i>
												<textarea rows="3" name="adddescription" id="adddescription" placeholder="Description"></textarea>
											</label>
										</section>
									</div>
<?php if(1==2){ ?>
									<div class="row fi1">
										<section class="col col-10">File Upload
											<label class="input"> <i class="icon-append fa fa-upload"></i>
												<input type="file" name="fi-upload[]" id="fi-upload1" class="fi-upload" placeholder="Upload File" />
											</label>
										</section>
										<section class="col col-2">&nbsp;
											<label class="input">
												<input type="button" name="fi-upload-bt1" id="fi-upload-bt1" class="fi-upload-bt1" value="+" />
											</label>
										</section>
									</div>
<?php } ?>
									<div class="row">
										<section class="col col-6">Read
											<label class="select"> <i class="icon-append fa fa-sitemap"></i>
												<select name="addread" id="addread" placeholder="Read" class="">
													<option value="Y">&nbsp;&nbsp;Yes</option>
													<option value="N" SELECTED="SELECTED">&nbsp;&nbsp;No</option>
												</select>
											</label>
										</section>
										<section class="col col-6">Date Added
											<label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" name="adddateadded" id="adddateadded" placeholder="Date Added" class="datepicker" data-dateformat='mm/dd/yy' value="">
												<input type="hidden" name="addnew" id="addnew" value="new">
											</label>
										</section>
									</div>

								<div class="row">
									<section class="col col-12">Attached documents
										<div class="dropzone dz-clickable" id="fi-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
								</fieldset>

								<footer>
									<button type="submit" class="btn btn-primary" id="add-fi-submit">
										Submit
									</button>
									<button type="button" class="btn" id="add-fi-cancel">
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
	<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
	<script type="text/javascript">
	$(function() {
	$(document).ready(function() {
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
<?php if(1==2){ ?>
		$('.fi-upload-bt1').click(function() {
			if($(".fi1").length < 7)
			{
				$(".fi1").last().after( '<div class="row fi1"><section class="col col-10"><label class="input"> <i class="icon-append fa fa-upload"></i><input type="file" name="fi-upload[]" class="fi-upload" placeholder="Upload File" /></label></section><section class="col col-2"><label class="input"><input type="button" class="fi-upload-bt" value="-" /></label></section></div>' );
			}else{
				alert("Upload Limit Reached!");
			}
		});
<?php } ?>

	var responsegot=0;
	var currentFile = null;
var script = document.createElement("script");
script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
script.onload = loadedContent;
document.head.append(script);
function loadedContent(){
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#fi-fileupload", {
		paramName: "fiaddfilesupload",
		addRemoveLinks: true,
		url: "assets/includes/focusitemss3.inc.php",
		maxFiles:20,
		uploadMultiple: true,
		parallelUploads:20,
		timeout: 300000,
		maxFilesize: 3000,
		autoProcessQueue: false,
		init: function() {
			myDropz = this;

			$("#add-fi-submit").on("click", function(e) {
			  // Make sure that the form isn't actually being sent.
			  e.preventDefault();
			  e.stopPropagation();
						if($("#addcid").val() ==""){swal("","Please select company", "warning");}
						else if($("#addcategory").val() ==""){swal("","Please select category", "warning");}
						else if($("#adddescription").val() ==""){swal("","Please enter description", "warning");}
						else if($("#addread").val() ==""){swal("","Please select read", "warning");}
						else if($("#adddateadded").val() ==""){swal("","Please select date added", "warning");}
						else if($("#addnew").val() ==""){swal("","Error occured please try after sometimes!", "warning");}
						else{
							if (myDropz.getQueuedFiles().length > 0)
							{
								myDropzone.on("sending", function(file, xhr, formData) {
									formData.append('cid', $("#addcid").val());
									formData.append('category', $("#addcategory").val());
									formData.append('description', $("#adddescription").val());
									formData.append('read', $("#addread").val());
									formData.append('dateadded', $("#adddateadded").val());
									formData.append('new', $("#addnew").val());
								});
								myDropz.processQueue();

								myDropz.on("successmultiple", function(file, result) {
									if (result != false)
									{
										var results = JSON.parse(result);
										if(results.error == "")
										{
											swal("","Added", "success");
											$("#add-checkout-form").get(0).reset();

											$("#add-dialog-message").dialog("close");
											$("#add-dialog-message").dialog('destroy');
											$("#add-dialog-message").remove();
											parent.$("#firesponse").html('');
											parent.$("#fitable").html('');
											parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
										}else if(results.error != "")
										{
											swal("Error in request.",results.error, "warning");
										}else{
											swal("Error in request.","Please try again later.", "warning");
										}
									}else{
										swal("","Error in request. Please try again later.", "warning");
									}
								});
								myDropz.on("complete", function(file) {
								   myDropz.removeAllFiles(true);
								});
								$('#add-checkout-form').trigger("reset")
							} else {
									//$('#maadd-checkout-form').submit();
									var formData = new FormData();
										formData.append('cid', $("#addcid").val());
										formData.append('category', $("#addcategory").val());
										formData.append('description', $("#adddescription").val());
										formData.append('read', $("#addread").val());
										formData.append('dateadded', $("#adddateadded").val());
										formData.append('new', $("#addnew").val());

									$.ajax({
										type: 'post',
										url: 'assets/includes/savinganalysiss3.inc.php',
										data: formData,
										processData: false,
										contentType: false,
										success: function (result) {
											if (result != false)
											{
												var results = JSON.parse(result);
												if(results.error == "")
												{
													swal("","Added", "success");
													$("#add-checkout-form").get(0).reset();

													$("#add-dialog-message").dialog("close");
													$("#add-dialog-message").dialog('destroy');
													$("#add-dialog-message").remove();
													parent.$("#firesponse").html('');
													parent.$("#fitable").html('');
													parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');


												}else if(results.error != "")
												{
													swal("Error in request.",results.error, "warning");
												}else{
													swal("Error in request.","Please try again later.", "warning");
												}
											}else{
												swal("","Error in request. Please try again later.", "warning");
											}
										}
									  });
							}
						}
			});
		}
	});
	}

		$('#add-checkout-form').on('click', '.fi-upload-bt', function() {
			$(this).closest(".fi1").remove();
		});
	});
	});

		pageSetUp();

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
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New Focus Items</h4></div>",
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
					parent.$("#firesponse").html('');
					parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
				  }
			});

			$('#add-fi-cancel').click(function() {
				$("#add-dialog-message").dialog("close");
				$("#add-dialog-message").dialog('destroy');
				$("#add-dialog-message").remove();
				parent.$("#firesponse").html('');
			});


<?php if(1==2){ ?>
			var $checkoutForm = $('#add-checkout-form').validate({
			// Rules for form validation
				rules : {
					addcid : {
						required : true
					},
					addcategory : {
						required : true
					},
					adddescription : {
						required : true
					},
					addread : {
						required : true
					},
					adddateadded : {
						required : true
					}
				},

				// Messages for form validation
				messages : {
					addcid : {
						required : 'Please select company'
					},
					addcategory : {
						required : 'Please enter category'
					},
					adddescription : {
						required : 'Please enter description'
					},
					addread : {
						required : 'Select read'
					},
					adddateadded : {
						required : 'Select date added'
					}
				},
				// Ajax form submition
				submitHandler : function(form) {
					/*if($(".fi-upload").length > 0)
					{
						var fs=0;
						$(".fi-upload").each(function() {
							if($($(this).val() != "")){
								fs=1;
							}
						});
						if(fs == 1){*/
							var formData = new FormData();
							formData.append('cid', $("#addcid").val());
							formData.append('category', $("#addcategory").val());
							formData.append('description', $("#adddescription").val());
							formData.append('read', $("#addread").val());
							formData.append('dateadded', $("#adddateadded").val());
							var ii=1;
							$(".fi-upload").each(function() {

								if($($(this).val() != "") && ii < 6){
									formData.append('file'+ii, $(this)[0].files[0]);
									++ii;
								}
							});
							formData.append('new', $("#addnew").val());

							$.ajax({
								type: 'post',
								url: 'assets/includes/focusitemsedit.inc.php',
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
											parent.$("#firesponse").html('');
											parent.$("#fitable").html('');
											parent.$('#fitable').load('assets/ajax/focus_items_pedit.php');
										}else
											alert("Error in request. Please try again later.");
									}else{
										alert("Error in request. Please try again later.");
									}
								}
							});
						/*}else{
							alert("Please Select Atleast One File");
							$(".fi-upload").first().focus();
						}
					}else{
						alert("Please Select Atleast One File");
						$(".fi-upload").first().focus();
					}*/
					return false;
				},
				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
<?php } ?>
		};

		var pagedestroy = function() {
		}

		loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);

		function profileAdd(){
		}
	</script>
<?php }
}
if(isset($_SESSION['group_id']) and ($_SESSION['group_id'] == 3 or $_SESSION['group_id'] == 5))
{
	if(isset($_GET["action"]) and $_GET["action"]=="view" and isset($_GET["fiid"]) and @trim($_GET["fiid"]) != 0 and @trim($_GET["fiid"]) != ""){
		$tmp_fiid=$mysqli->real_escape_string(@trim($_GET["fiid"]));

		$sql='UPDATE focus_items SET _read="Y" WHERE id="'.$tmp_fiid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt)
		{
			$stmt->execute();
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}



		if ($stmtk = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$tmp_fiid."' LIMIT 1")) {

//("SELECT fi.id,fi.company_id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi, company c where fi.company_id=c.id and fi.id='".$tmp_fiid."' LIMIT 1")) {
			$stmtk->execute();
			$stmtk->store_result();
			if ($stmtk->num_rows > 0) {
				$stmtk->bind_result($fi_Id,$fi_Companyid,$c_Companyname,$fi_Category,$fi_Description,$fi_Link,$fi_Read,$fi_Dateadded);
				$stmtk->fetch();
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
	#view-dialog-message footer{text-align:center;}
	#view-dialog-message footer button{float:none !important;}
	</style>
			<div id="view-dialog-message" title="View Focus Items">
							<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

								<fieldset>
									<div class="row">
										<section class="col col-6">Category
											<label class="input"> <i class="icon-append fa fa-sitemap"></i>
												<input type="text" readonly="readonly" value="<?php if($fi_Category == 1){echo "Fixed Price";}
													elseif($fi_Category == 2){echo "Index/Basis + Adder";}
													elseif($fi_Category == 3){echo "Heat Rate";}
													elseif($fi_Category == 4){echo "Hedge Block";}
													elseif($fi_Category == 5){echo "Blend and Extend";}
													elseif($fi_Category == 6){echo "Rate and Tariff Analysis";}
													elseif($fi_Category == 7){echo "Procurement Recommendation";}
													elseif($fi_Category == 8){echo "Budget Report";}
													elseif($fi_Category == 9){echo "Meeting Agenda";}
													elseif($fi_Category == 10){echo "New Market Summary";}
													elseif($fi_Category == 11){echo "Other";} ?>">
											</label>
										</section>
										<section class="col col-6">Date Added
											<label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" readonly="readonly" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($fi_Dateadded)); ?>">
											</label>
										</section>
									</div>

									<div class="row">
										<section class="col col-12 width-full">Description
											<label class="textarea"><i class="icon-append fa fa-comment"></i>
												<textarea rows="3" readonly="readonly"><?php echo $fi_Description; ?></textarea>
											</label>
										</section>
									</div>

	<?php
		$files_list=array();

		if(@trim($fi_Link) != "")
		{
			$files_list=@explode("@@;@@",$fi_Link);
		}

		$files_len=count($files_list);
		if($files_len > 0)
		{
?>
<style>
.input .icon.file::first-line {
    font-size: 13px;
    font-weight: 700;
}
.input .icon.file.f-gif::after,.input  .icon.file.f-jpg::after,.input  .icon.file.f-jpeg::after,.input  .icon.file.f-pdf::after, .input .icon.file.f-png::after{
	border-bottom-color: #c6393f;
}
.input .icon.file::after{
	border-bottom: 2.6em solid #dadde1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    border-width: 0 2.22em 2.6em 0;
    content: "";
    position: absolute;
    right: -4px;
    top: -34.5px;
	z-index: -1;
}
.input .icon.file.f-gif,.input  .icon.file.f-jpg,.input  .icon.file.f-jpeg,.input  .icon.file.f-pdf,.input  .icon.file.f-png{
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset;
}
.input .icon.file{
	border-radius: 0.25em;
	color: #fff;
    display: inline-block;
    height: 3em;
    line-height: 3em;
    overflow: hidden;
    position: relative;
    text-align: center;
    width: 2.5em;
}
.input .icon {
    font-size: 23px;
}
.ndownf{
	color: #1E90FF;
    font-size: 14px;
    cursor: pointer;
	margin-left: 42%;
}
.downcont{float:left;margin-right:20px;}
</style>
									<div class="row fi1u">
										<section class="col col-12">Files Uploaded
											<label class="input">
<?php
			for($i=0;$i<$files_len;$i++)
			{
				//if ($number % 2 == 0) {}
				$fileext=@strtolower(pathinfo(basename($files_list[$i]), PATHINFO_EXTENSION));
				$filepath='resources/Clients/'.strip_tags($c_Companyname).'/focus items/'.basename($files_list[$i]);
				$status=2;
				if(empty($fileext)) $status=0;
				if($fileext=="pdf"){
					$status=1;
				}else{
					$supportfileext=array("jpeg","png","gif","tiff","bmp","webm","mpeg4","3gpp","mov","avi","mpegps","wmv","flv","txt","css","html","php","c","cpp","h","hpp","js","doc","docx","xls","xlsx","ppt","pptx","pdf","pages","ai","psd","tiff","dxf","svg","eps","ps","ttf","xps","csv");
					if(in_array($fileext,$supportfileext)){
						$status=8;
					}elseif($s3Client->doesObjectExist('datahub360',$filepath.'.err')){
						$status=0;
					}
					if($s3Client->doesObjectExist('datahub360', $filepath.'.pdf')){
						$filepath=$filepath.'.pdf';
						$status=1;
					}
				}

				if($fileext=="mp4" or $fileext=="ogg" or $fileext=="avi" or $fileext=="flv" or $fileext=="mkv" or $fileext=="mov" or $fileext=="mpeg" or $fileext=="mpg" or $fileext=="m4v" or $fileext=="wmv"){
					$status=5;
				}elseif($fileext=="mp3" or $fileext=="m4a" or $fileext=="mp2" or $fileext=="m3u" or $fileext=="wma" or $fileext=="m4a" or $fileext=="m4a"){
					$status=6;
				}elseif($fileext=="jpg" or $fileext=="jpeg" or $fileext=="gif" or $fileext=="png" or $fileext=="tif" or $fileext=="bmp" or $fileext=="ico"){
					$status=7;
				}
	?>
												<div class="downcont">
												<span title="Click to preview" class="icon file f-jpg putcursor" onclick="javascript:previewPop('<?php echo rawurlencode(getpresignedfile(basename($files_list[$i]),'resources/Clients/'.strip_tags($c_Companyname).'/focus items/')); ?>','<?php echo $status; ?>','<?php echo basename($files_list[$i]); ?>');"><?php echo pathinfo($files_list[$i], PATHINFO_EXTENSION); ?></span>
												<div class="details"><span class="fsize"></span><span class="glyphicon glyphicon-download downloadf ndownf" onclick="downloadfiles('<?php echo @addslashes($files_list[$i]); ?>')" title="Download"></span></div>
												</div>
	<?php
			}
?>
											</label>
										</section>
									</div>
<?php
		}
	?>

								<footer>
									<button type="button" class="btn" id="view-fi-cancel">
										Close
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
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;}</style>
	<script type="text/javascript">
function downloadfiles(filename){
	window.location.href ="assets/includes/filedownload.inc.php?filename="+filename+"&ticket=<?php echo $tmp_fiid; ?>&type=fidownload";
}
//$(document).ready(function(){
parent.$( "#fitopdialog" ).dialog({
	  height: 600,
      width: $(window).width()-100,
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
		open: function (event, ui) {
			   // this is where we add an icon and a link
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

		}
    });
			function previewPop(rurl,rstatus,rname){
				  if ( rurl ) {rurl1=rawgurl=rawourl=rurl;
						if(rstatus==1){ discode='<object type="text/html" data="assets/plugins/pdfjs/web/viewer.php?file='+rurl+'&ofile='+rurl1+'&fname='+rname+'" style="overflow:auto;width:100%;height:85vh;"></object>'; }
						else if(rstatus==2){ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
						else if(rstatus==0){ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
						else if(rstatus==5){ discode='<video controls autoplay style="width:100%;height:80%;"><source src="'+rawourl+'" type="video/mp4" /></video>'; }
						else if(rstatus==6){ discode='<audio controls autoplay style="width:100%;"><source src="'+rawourl+'" type="audio/mpeg">Your browser does not support the audio element.</audio>'; }
						else if(rstatus==7){ discode='<img src="'+rawourl+'" width="100%" style="height: auto;max-height: 100%;width: auto;max-width:100%;" />'; }
						else if(rstatus==8){var rawgurl=encodeURIComponent(rawourl); discode='<style>#googleload{background: url("data:image/svg+xml;charset=utf-8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100%\" height=\"100%\" viewBox=\"0 0 100% 100%\"><text fill=\"%23FF0000\" x=\"50%\" y=\"50%\" font-family=\"\'Lucida Grande\', sans-serif\" font-size=\"24\" text-anchor=\"middle\">Loading...</text></svg>\') 0px 0px no-repeat;}</style><iframe src="https://docs.google.com/viewer?embedded=true&url='+rurl+'" frameborder="0" width="100%" height="100%" id="googleload" name="googleload" onload="checkerror()" dtct="ct<?php echo time(); ?>">Loading....</iframe><a href="'+rurl+'" download><div class="noshow"></div></a><script>/*var runct=0;window.begincheck = setInterval(reloadIFrame, 2500);*/function reloadIFrame() {runct=runct+1;if(Number(runct) > 8){clearInterval(window.begincheck);}else{document.getElementById("googleload").src=document.getElementById("googleload").src;}}function checkerror(){clearInterval(window.begincheck);};<\/script>preview_height=document.getElementById(\'googleload\').contentWindow.document.body.scrollHeight;document.getElementById(\'googleload\').height=preview_height;'; }
						else if(rstatus==9){ discode='<h3 align="center" style="margin-top:20%;">This is a large file and can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
						else{ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }

					  parent.$("#fitopdialog").html('');
					  parent.$("#fitopdialog").html(discode);
					  parent.$("#fitopdialog").dialog("open");
					  parent.$("#d-download").attr('href', rurl);
				  }
			}
//});

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

			//Custom Code


			$("#view-dialog-message").dialog({
				autoOpen : true,
				modal : true,
				width: "auto",
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>View Focus Items</h4></div>",
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
					$("#view-dialog-message").dialog('destroy');
					$("#view-dialog-message").remove();
					parent.$("#firesponse").html('');
					parent.$("#fitable").html('');
					parent.$('#fitable').load('assets/ajax/focus_items_pedit.php<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "?type=unread";} ?>');
				  }
			});

			$('#view-fi-cancel').click(function() {
				$("#view-dialog-message").dialog("close");
				$("#view-dialog-message").dialog('destroy');
				$("#view-dialog-message").remove();
				parent.$("#firesponse").html('');
				parent.$("#fitable").html('');
				parent.$('#fitable').load('assets/ajax/focus_items_pedit.php<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "?type=unread";} ?>');
			});




			/*$("#view-file").dialog({
				autoOpen : true,
				modal : true,
				width: "auto",
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>File Preview</h4></div>",
				 close : function(){
					$("#view-file").dialog('destroy');
					$("#view-file").remove();
					parent.$("#fitopdialog").html('');
				  }
			});*/
		};

		var pagedestroy = function() {
			//$('#profileForm').bootstrapValidator('destroy');
		}

		loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);

		//pagefunction();

		function profileAdd(){return false;
		}
	</script>
	<?php
			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	}
} ?>
