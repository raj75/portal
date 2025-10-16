<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2 or $_SESSION['group_id'] != 5))
	die("Restricted Access!");

if(isset($_GET["action"]) and $_GET["action"]=="edit" and isset($_GET["said"]) and @trim($_GET["said"]) != 0 and @trim($_GET["said"]) != ""){
	$tmp_uid=$mysqli->real_escape_string(@trim($_GET["said"]));

	if ($stmtk = $mysqli->prepare("SELECT id,company_id,location,category,commodity,start,end,saving,link,_read,date_added FROM saving_analysis WHERE id='".$tmp_uid."'")) {
        $stmtk->execute();
        $stmtk->store_result();
        if ($stmtk->num_rows > 0) {
			$stmtk->bind_result($sa_Id,$sa_Companyid,$sa_Location,$sa_Category,$sa_Commodity,$sa_Start,$sa_End,$sa_Saving,$sa_Link,$sa_Read,$sa_Dateadded);
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

	#edit-dialog-message #sa-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#edit-dialog-message .dropzone .dz-preview .dz-details .dz-size,#edit-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#edit-dialog-message #sa-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#edit-dialog-message .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	.ui-dialog-title{
		width: 100%;
		text-align: center;
	}
	</style>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
		<div id="edit-dialog-message" title="Edit Saving Analysis">
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Company
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select name="editcid" id="editcid" placeholder="Company" class="selectautosave" saveme="company_id">
											<option value="">&nbsp;&nbsp;Select Company</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company order by company_name ')){
												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$stmt->bind_result($__id,$__companyname);
													while($stmt->fetch()){
														echo "<option value='".$__id."' ".($sa_Companyid == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__companyname."</option>";
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
									<section class="col col-6">Location
										<label class="select"> <i class="icon-append fa fa-map-marker"></i>
											<select name="editlocation" id="editlocation" placeholder="Location" class="selectautosave" saveme="location">
												<option value="">&nbsp;&nbsp;Select Location</option>
												<option value="1" <?php if($sa_Location==1){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Alabama</option>
												<option value="2" <?php if($sa_Location==2){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Alaska</option>
												<option value="3" <?php if($sa_Location==3){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Arizona</option>
												<option value="4" <?php if($sa_Location==4){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Arkansas</option>
												<option value="5" <?php if($sa_Location==5){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;California</option>
												<option value="6" <?php if($sa_Location==6){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Colorado</option>
												<option value="7" <?php if($sa_Location==7){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Connecticut</option>
												<option value="8" <?php if($sa_Location==8){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Delaware</option>
												<option value="9" <?php if($sa_Location==9){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Florida</option>
												<option value="10" <?php if($sa_Location==10){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Georgia</option>
												<option value="11" <?php if($sa_Location==11){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Hawaii</option>
												<option value="12" <?php if($sa_Location==12){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Idaho</option>
												<option value="13" <?php if($sa_Location==13){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Illinois</option>
												<option value="14" <?php if($sa_Location==14){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Indiana</option>
												<option value="15" <?php if($sa_Location==15){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Iowa</option>
												<option value="16" <?php if($sa_Location==16){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Kansas</option>
												<option value="17" <?php if($sa_Location==17){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Kentucky</option>
												<option value="18" <?php if($sa_Location==18){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Louisiana</option>
												<option value="19" <?php if($sa_Location==19){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Maine</option>
												<option value="20" <?php if($sa_Location==20){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Maryland</option>
												<option value="21" <?php if($sa_Location==21){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Massachusetts</option>
												<option value="22" <?php if($sa_Location==22){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Michigan</option>
												<option value="23" <?php if($sa_Location==23){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Minnesota</option>
												<option value="24" <?php if($sa_Location==24){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Mississippi</option>
												<option value="25" <?php if($sa_Location==25){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Missouri</option>
												<option value="26" <?php if($sa_Location==26){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Montana</option>
												<option value="27" <?php if($sa_Location==27){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Nebraska</option>
												<option value="28" <?php if($sa_Location==28){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Nevada</option>
												<option value="29" <?php if($sa_Location==29){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;New Hampshire</option>
												<option value="30" <?php if($sa_Location==30){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;New Jersey</option>
												<option value="31" <?php if($sa_Location==31){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;New Mexico</option>
												<option value="32" <?php if($sa_Location==32){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;New York</option>
												<option value="33" <?php if($sa_Location==33){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;North Carolina</option>
												<option value="34" <?php if($sa_Location==34){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;North Dakota</option>
												<option value="35" <?php if($sa_Location==35){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Ohio</option>
												<option value="36" <?php if($sa_Location==36){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Oklahoma</option>
												<option value="37" <?php if($sa_Location==37){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Oregon</option>
												<option value="38" <?php if($sa_Location==38){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Pennsylvania</option>
												<option value="39" <?php if($sa_Location==39){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Rhode Island</option>
												<option value="40" <?php if($sa_Location==40){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;South Carolina</option>
												<option value="41" <?php if($sa_Location==41){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;South Dakota</option>
												<option value="42" <?php if($sa_Location==42){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Tennessee</option>
												<option value="43" <?php if($sa_Location==43){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Texas</option>
												<option value="44" <?php if($sa_Location==44){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Utah</option>
												<option value="45" <?php if($sa_Location==45){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Vermont</option>
												<option value="46" <?php if($sa_Location==46){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Virginia[H]</option>
												<option value="47" <?php if($sa_Location==47){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Washington</option>
												<option value="48" <?php if($sa_Location==48){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;West Virginia</option>
												<option value="49" <?php if($sa_Location==49){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Wisconsin</option>
												<option value="50" <?php if($sa_Location==50){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Wyoming</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Category
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="editcategory" id="editcategory" placeholder="Category" class="selectautosave" saveme="category">
												<option value="">&nbsp;&nbsp;Select Category</option>
												<option value="1" <?php if($sa_Category==1){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Fixed Price</option>
												<option value="2" <?php if($sa_Category==2){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Index/Basis + Adder</option>
												<option value="3" <?php if($sa_Category==3){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Heat Rate</option>
												<option value="4" <?php if($sa_Category==4){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Hedge Block</option>
												<option value="5" <?php if($sa_Category==5){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Blend and Extend</option>
												<option value="6" <?php if($sa_Category==6){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Rate and Tariff Analysis</option>
												<option value="7" <?php if($sa_Category==7){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Procurement Recommendation</option>
												<option value="8" <?php if($sa_Category==8){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Budget Report</option>
												<option value="9" <?php if($sa_Category==9){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Meeting Agenda</option>
												<option value="10" <?php if($sa_Category==10){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;New Market Summary</option>
											</select>
										</label>
									</section>
									<section class="col col-6">Commodity
										<label class="select"> <i class="icon-append fa fa-flash"></i>
											<select name="editcommodity" id="editcommodity" placeholder="Commodity" class="selectautosave" saveme="commodity">
												<option value="">&nbsp;&nbsp;Select Commodity</option>
												<option value="1" <?php if($sa_Commodity==1){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Electricity</option>
												<option value="2" <?php if($sa_Commodity==2){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Natural Gas</option>
												<option value="3" <?php if($sa_Commodity==3){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Water</option>
												<option value="4" <?php if($sa_Commodity==4){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Fuel Oil</option>
												<option value="5" <?php if($sa_Commodity==5){echo "SELECTED='SELECTED'";} ?>>&nbsp;&nbsp;Trash</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="editstartdate" id="editstartdate" placeholder="Start date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($sa_Start)); ?>"  saveme="start">
										</label>
									</section>
									<section class="col col-6">End Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="editenddate" id="editenddate" placeholder="End date" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($sa_End)); ?>"  saveme="end">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Saving
										<label class="input"> <i class="icon-prepend fa fa-usd"></i>
											<input type="number" name="editsaving" id="editsaving" placeholder="Saving" value="<?php echo $sa_Saving; ?>" class="inputautosave"  saveme="saving">
											<input type="hidden" name="edit" id="edit" value="edit">
											<input type="hidden" name="editsaid" id="editsaid" value="<?php echo $sa_Id;?>">
										</label>
									</section>
									<section class="col col-6">Read
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="editread" id="editread" placeholder="Read" class="selectautosave" saveme="_read">
												<option value="Y" <?php echo ($sa_Read == "Y"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;Yes</option>
												<option value="N" <?php echo ($sa_Read != "Y"?"SELECTED='SELECTED'":''); ?>>&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Date Added
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="editdateadded" id="editdateadded" placeholder="Date Added" class="datepicker inputautosave" data-dateformat='mm/dd/yy' value="<?php echo date("m/d/Y",strtotime($sa_Dateadded)); ?>" saveme="date_added">
										</label>
									</section>
									<section class="col col-6"></section>
								</div>


								<div class="row">
									<section class="col col-12">Attached documents
									<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
									<div id="sas3display"></div>
									<div class="dropzone dz-clickable" id="sa-fileupload">
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
										$('#sas3display').html('');
										$('#sas3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $sa_Id; ?>');
										  var script = document.createElement("script");
  script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
  script.onload = loadedContent;
  document.head.append(script);
  function loadedContent(){
										Dropzone.autoDiscover = false;
										var myDropzone = new Dropzone("div#sa-fileupload", {
											paramName: "sas3filesupload",
											addRemoveLinks: false,
											url: "assets/includes/s3filepermission_2.inc.php?ct=<?php echo rand(2,99); ?>&masterid=<?php echo $sa_Id; ?>",
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
															$('#sas3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $sa_Id; ?>');
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
								<button type="submit" class="btn btn-primary" id="edit-sa-submit">
									Save
								</button>
								<button type="button" class="btn" id="edit-sa-cancel">
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

	function autosave(savename,saveval){

		var formData = new FormData();
		formData.append('saauto', <?php echo $sa_Id; ?>);
		formData.append('sasavename', savename);
		formData.append('savalue', saveval);

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

		$("#edit-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "90%",
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
				parent.$("#satable").html('');
				parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
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
					url: 'assets/includes/savinganalysisedit.inc.php',
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
	#add-dialog-message #sa-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
	#add-dialog-message .dropzone .dz-preview .dz-details .dz-size,#add-dialog-message .dropzone-previews .dz-preview .dz-details .dz-size {
		bottom: -1px !important;
		left: 29px !important;
	}
	#add-dialog-message .col-12{width:100% !important;}
	#add-dialog-message .ui-datepicker{top:166px !important;}
	.ui-dialog-title{
		width: 100%;
		text-align: center;
	}
	</style>
<!-- Add Section -->
<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
		<div id="add-dialog-message" title="Add Saving Analysis">
						<form id="add-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

							<fieldset>
								<div class="row">
									<section class="col col-6"> Company
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select name="addcid" id="addcid" placeholder="Company" class="">
											<option value="">&nbsp;&nbsp;Select Company</option>
										<?php
										   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company order by company_name ')){
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
									<section class="col col-6">Location
										<label class="select"> <i class="icon-append fa fa-map-marker"></i>
											<select name="addlocation" id="addlocation" placeholder="Location" class="">
												<option value="">&nbsp;&nbsp;Select Location</option>
												<option value="1">&nbsp;&nbsp;Alabama</option>
												<option value="2">&nbsp;&nbsp;Alaska</option>
												<option value="3">&nbsp;&nbsp;Arizona</option>
												<option value="4">&nbsp;&nbsp;Arkansas</option>
												<option value="5">&nbsp;&nbsp;California</option>
												<option value="6">&nbsp;&nbsp;Colorado</option>
												<option value="7">&nbsp;&nbsp;Connecticut</option>
												<option value="8">&nbsp;&nbsp;Delaware</option>
												<option value="9">&nbsp;&nbsp;Florida</option>
												<option value="10">&nbsp;&nbsp;Georgia</option>
												<option value="11">&nbsp;&nbsp;Hawaii</option>
												<option value="12">&nbsp;&nbsp;Idaho</option>
												<option value="13">&nbsp;&nbsp;Illinois</option>
												<option value="14">&nbsp;&nbsp;Indiana</option>
												<option value="15">&nbsp;&nbsp;Iowa</option>
												<option value="16">&nbsp;&nbsp;Kansas</option>
												<option value="17">&nbsp;&nbsp;Kentucky</option>
												<option value="18">&nbsp;&nbsp;Louisiana</option>
												<option value="19">&nbsp;&nbsp;Maine</option>
												<option value="20">&nbsp;&nbsp;Maryland</option>
												<option value="21">&nbsp;&nbsp;Massachusetts</option>
												<option value="22">&nbsp;&nbsp;Michigan</option>
												<option value="23">&nbsp;&nbsp;Minnesota</option>
												<option value="24">&nbsp;&nbsp;Mississippi</option>
												<option value="25">&nbsp;&nbsp;Missouri</option>
												<option value="26">&nbsp;&nbsp;Montana</option>
												<option value="27">&nbsp;&nbsp;Nebraska</option>
												<option value="28">&nbsp;&nbsp;Nevada</option>
												<option value="29">&nbsp;&nbsp;New Hampshire</option>
												<option value="30">&nbsp;&nbsp;New Jersey</option>
												<option value="31">&nbsp;&nbsp;New Mexico</option>
												<option value="32">&nbsp;&nbsp;New York</option>
												<option value="33">&nbsp;&nbsp;North Carolina</option>
												<option value="34">&nbsp;&nbsp;North Dakota</option>
												<option value="35">&nbsp;&nbsp;Ohio</option>
												<option value="36">&nbsp;&nbsp;Oklahoma</option>
												<option value="37">&nbsp;&nbsp;Oregon</option>
												<option value="38">&nbsp;&nbsp;Pennsylvania</option>
												<option value="39">&nbsp;&nbsp;Rhode Island</option>
												<option value="40">&nbsp;&nbsp;South Carolina</option>
												<option value="41">&nbsp;&nbsp;South Dakota</option>
												<option value="42">&nbsp;&nbsp;Tennessee</option>
												<option value="43">&nbsp;&nbsp;Texas</option>
												<option value="44">&nbsp;&nbsp;Utah</option>
												<option value="45">&nbsp;&nbsp;Vermont</option>
												<option value="46">&nbsp;&nbsp;Virginia[H]</option>
												<option value="47">&nbsp;&nbsp;Washington</option>
												<option value="48">&nbsp;&nbsp;West Virginia</option>
												<option value="49">&nbsp;&nbsp;Wisconsin</option>
												<option value="50">&nbsp;&nbsp;Wyoming</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Category
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="addcategory" id="addcategory" placeholder="Category" class="">
												<option value="">&nbsp;&nbsp;Select Category</option>
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
											</select>
										</label>
									</section>
									<section class="col col-6">Commodity
										<label class="select"> <i class="icon-append fa fa-flash"></i>
											<select name="addcommodity" id="addcommodity" placeholder="Commodity" class="">
												<option value="">&nbsp;&nbsp;Select Commodity</option>
												<option value="1">&nbsp;&nbsp;Electricity</option>
												<option value="2">&nbsp;&nbsp;Natural Gas</option>
												<option value="3">&nbsp;&nbsp;Water</option>
												<option value="4">&nbsp;&nbsp;Fuel Oil</option>
												<option value="5">&nbsp;&nbsp;Trash</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="addstartdate" id="addstartdate" placeholder="Start date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
									<section class="col col-6">End Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="addenddate" id="addenddate" placeholder="End date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Saving
										<label class="input"> <i class="icon-prepend fa fa-usd"></i>
											<input type="number" name="addsaving" id="addsaving" placeholder="Saving" value="">
											<input type="hidden" name="addnew" id="addnew" value="new">
										</label>
									</section>
									<section class="col col-6">Read
										<label class="select"> <i class="icon-append fa fa-sitemap"></i>
											<select name="addread" id="addread" placeholder="Read" class="">
												<option value="Y">&nbsp;&nbsp;Yes</option>
												<option value="N" SELECTED="SELECTED">&nbsp;&nbsp;No</option>
											</select>
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Date Added
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="adddateadded" id="adddateadded" placeholder="Date Added" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
									<section class="col col-6"></section>
								</div>

								<div class="row">
									<section class="col col-12">Attached documents
										<div class="dropzone dz-clickable" id="sa-fileupload">
												<div class="dz-message needsclick">
													<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
													<span class="text-uppercase">Drop files here or click to upload.</span>
												</div>
										</div>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary" id="add-sa-submit">
									Submit
								</button>
								<button type="button" class="btn" id="add-sa-cancel">
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

	var responsegot=0;
	var currentFile = null;
var script = document.createElement("script");
script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
script.onload = loadedContent;
document.head.append(script);
function loadedContent(){
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#sa-fileupload", {
		paramName: "saaddfilesupload",
		addRemoveLinks: true,
		url: "assets/includes/savinganalysiss3.inc.php",
		maxFiles:20,
		uploadMultiple: true,
		parallelUploads:20,
		timeout: 300000,
		maxFilesize: 3000,
		autoProcessQueue: false,
		init: function() {
			myDropz = this;

			$("#add-sa-submit").on("click", function(e) {
			  // Make sure that the form isn't actually being sent.
			  e.preventDefault();
			  e.stopPropagation();
						if($("#addcid").val() ==""){swal("","Please select company", "warning");}
						else if($("#addlocation").val() ==""){swal("","Please select location", "warning");}
						else if($("#addcategory").val() ==""){swal("","Please select category", "warning");}
						else if($("#addcommodity").val() ==""){swal("","Please select commodity", "warning");}
						else if($("#addstartdate").val() ==""){swal("","Please select start date", "warning");}
						else if($("#addenddate").val() ==""){swal("","Please select end date", "warning");}
						else if($("#addsaving").val() ==""){swal("","Please enter saving", "warning");}
						else if($("#addread").val() ==""){swal("","Please select read", "warning");}
						else if($("#adddateadded").val() ==""){swal("","Please select date added", "warning");}
						else if($("#addnew").val() ==""){swal("","Error occured please try after sometimes!", "warning");}
						else{
							if (myDropz.getQueuedFiles().length > 0)
							{
								myDropzone.on("sending", function(file, xhr, formData) {
									formData.append('cid', $("#addcid").val());
									formData.append('location', $("#addlocation").val());
									formData.append('category', $("#addcategory").val());
									formData.append('commodity', $("#addcommodity").val());
									formData.append('startdate', $("#addstartdate").val());
									formData.append('enddate', $("#addenddate").val());
									formData.append('saving', $("#addsaving").val());
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
											parent.$("#saresponse").html('');
											parent.$("#satable").html('');
											parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
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
										formData.append('location', $("#addlocation").val());
										formData.append('category', $("#addcategory").val());
										formData.append('commodity', $("#addcommodity").val());
										formData.append('startdate', $("#addstartdate").val());
										formData.append('enddate', $("#addenddate").val());
										formData.append('saving', $("#addsaving").val());
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
													parent.$("#saresponse").html('');
													parent.$("#satable").html('');
													parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');


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
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New Saving Analysis</h4></div>",
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
				parent.$("#saresponse").html('');
				parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php');
              }
		});

		$('#add-sa-cancel').click(function() {
			$("#add-dialog-message").dialog("close");
			$("#add-dialog-message").dialog('destroy');
			$("#add-dialog-message").remove();
			parent.$("#saresponse").html('');
		});
	};

	var pagedestroy = function() {
	}

	loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);

	function profileAdd(){
	}
</script>
<?php }
if(isset($_SESSION['group_id']) and ($_SESSION['group_id'] == 3 or $_SESSION['group_id'] == 5))
{
//PHP >7
//$fmt = numfmt_create( 'de_DE', NumberFormatter::DECIMAL );
//echo numfmt_format($fmt, 1234567.891234567890000)."\n";

//PHP<7
setlocale(LC_MONETARY,"en_US");
	if(isset($_GET["action"]) and $_GET["action"]=="view" and isset($_GET["said"]) and @trim($_GET["said"]) != 0 and @trim($_GET["said"]) != ""){
		$tmp_said=$mysqli->real_escape_string(@trim($_GET["said"]));

		$sql='UPDATE saving_analysis SET _read="Y" WHERE id="'.$tmp_said.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt)
		{
			$stmt->execute();
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}



		if ($stmtk = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.location,sa.category,sa.commodity,sa.start,sa.end,sa.saving,sa.link,sa._read,sa.date_added FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$tmp_said."' LIMIT 1")) {
			$stmtk->execute();
			$stmtk->store_result();
			if ($stmtk->num_rows > 0) {
				$stmtk->bind_result($sa_Id,$sa_Companyid,$c_Companyname,$sa_Location,$sa_Category,$sa_Commodity,$sa_Start,$sa_End,$sa_Saving,$sa_Link,$sa_Read,$sa_Dateadded);
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
							<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return false">

								<fieldset>
									<div class="row">
									<section class="col col-6">Location
										<label class="input"> <i class="icon-append fa fa-map-marker"></i>
											<input type="text" readonly="readonly" value="<?php if($sa_Location==1){echo "Alabama";}
				elseif($sa_Location==2){echo "Alaska";}
				elseif($sa_Location==3){echo "Arizona";}
				elseif($sa_Location==4){echo "Arkansas";}
				elseif($sa_Location==5){echo "California";}
				elseif($sa_Location==6){echo "Colorado";}
				elseif($sa_Location==7){echo "Connecticut";}
				elseif($sa_Location==8){echo "Delaware";}
				elseif($sa_Location==9){echo "Florida";}
				elseif($sa_Location==10){echo "Georgia";}
				elseif($sa_Location==11){echo "Hawaii";}
				elseif($sa_Location==12){echo "Idaho";}
				elseif($sa_Location==13){echo "Illinois";}
				elseif($sa_Location==14){echo "Indiana";}
				elseif($sa_Location==15){echo "Iowa";}
				elseif($sa_Location==16){echo "Kansas";}
				elseif($sa_Location==17){echo "Kentucky";}
				elseif($sa_Location==18){echo "Louisiana";}
				elseif($sa_Location==19){echo "Maine";}
				elseif($sa_Location==20){echo "Maryland";}
				elseif($sa_Location==21){echo "Massachusetts";}
				elseif($sa_Location==22){echo "Michigan";}
				elseif($sa_Location==23){echo "Minnesota";}
				elseif($sa_Location==24){echo "Mississippi";}
				elseif($sa_Location==25){echo "Missouri";}
				elseif($sa_Location==26){echo "Montana";}
				elseif($sa_Location==27){echo "Nebraska";}
				elseif($sa_Location==28){echo "Nevada";}
				elseif($sa_Location==29){echo "New Hampshire";}
				elseif($sa_Location==30){echo "New Jersey";}
				elseif($sa_Location==31){echo "New Mexico";}
				elseif($sa_Location==32){echo "New York";}
				elseif($sa_Location==33){echo "North Carolina";}
				elseif($sa_Location==34){echo "North Dakota";}
				elseif($sa_Location==35){echo "Ohio";}
				elseif($sa_Location==36){echo "Oklahoma";}
				elseif($sa_Location==37){echo "Oregon";}
				elseif($sa_Location==38){echo "Pennsylvania";}
				elseif($sa_Location==39){echo "Rhode Island";}
				elseif($sa_Location==40){echo "South Carolina";}
				elseif($sa_Location==41){echo "South Dakota";}
				elseif($sa_Location==42){echo "Tennessee";}
				elseif($sa_Location==43){echo "Texas";}
				elseif($sa_Location==44){echo "Utah";}
				elseif($sa_Location==45){echo "Vermont";}
				elseif($sa_Location==46){echo "Virginia[H]";}
				elseif($sa_Location==47){echo "Washington";}
				elseif($sa_Location==48){echo "West Virginia";}
				elseif($sa_Location==49){echo "Wisconsin";}
				elseif($sa_Location==50){echo "Wyoming";}?>">
										</label>
									</section>
									<section class="col col-6">Saving
										<label class="input"> <i class="icon-append fa fa-usd"></i>
											<input type="text" readonly="readonly" value="<?php echo money_format("%!i", $sa_Saving); ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Category
										<label class="input"> <i class="icon-append fa fa-sitemap"></i>
											<input type="text" readonly="readonly" value="<?php if($sa_Category==1){echo "Fixed Price";}
											elseif($sa_Category==2){echo "Index/Basis + Adder";}
											elseif($sa_Category==3){echo "Heat Rate";}
											elseif($sa_Category==4){echo "Hedge Block";}
											elseif($sa_Category==5){echo "Blend and Extend";}
											elseif($sa_Category==6){echo "Rate and Tariff Analysis";}
											elseif($sa_Category==7){echo "Procurement Recommendation";}
											elseif($sa_Category==8){echo "Budget Report";}
											elseif($sa_Category==9){echo "Meeting Agenda";}
											elseif($sa_Category==10){echo "New Market Summary";}?>">
										</label>
									</section>
									<section class="col col-6">Commodity
										<label class="input"> <i class="icon-append fa fa-flash"></i>
											<input type="text" readonly="readonly" value="<?php if($sa_Commodity==1){echo "Electricity";}
											elseif($sa_Commodity==2){echo "Natural Gas";}
											elseif($sa_Commodity==3){echo "Water";}
											elseif($sa_Commodity==4){echo "Fuel Oil";}
											elseif($sa_Commodity==5){echo "Trash";}?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Start Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" readonly="readonly" value="<?php echo date("m/d/Y",strtotime($sa_Start)); ?>">
										</label>
									</section>
									<section class="col col-6">End Date
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" readonly="readonly" value="<?php echo date("m/d/Y",strtotime($sa_End)); ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Date Added
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" readonly="readonly" value="<?php echo date("m/d/Y",strtotime($sa_Dateadded)); ?>">
										</label>
									</section>
									<section class="col col-6">Read
										<label class="input"> <i class="icon-append fa fa-sitemap"></i>
											<input type="text" readonly="readonly" value="<?php echo ($sa_Read == "Y"?"Yes":'No'); ?>">
										</label>
									</section>
								</div>

	<?php
		$files_list=array();

		if(@trim($sa_Link) != "")
		{
			$files_list=@explode("@@;@@",$sa_Link);
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
				$filepath='resources/Clients/'.strip_tags($c_Companyname).'/saving analysis/'.basename($files_list[$i]);
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
												<span title="Click to preview" class="icon file f-jpg putcursor" onclick="javascript:previewPop('<?php echo rawurlencode(getpresignedfile($files_list[$i],'resources/Clients/'.strip_tags($c_Companyname).'/saving analysis/')); ?>','<?php echo $status; ?>','<?php echo basename($files_list[$i]); ?>')"><?php echo pathinfo($files_list[$i], PATHINFO_EXTENSION); ?></span>
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
	window.location.href ="assets/includes/filedownload.inc.php?filename="+filename+"&ticket=<?php echo $tmp_said; ?>&type=sadownload";
}
//$(document).ready(function(){
parent.$( "#satopdialog" ).dialog({
	  height: 600,
      width: $(window).width()-100,
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	/*buttons: [
	{
		id: "download",
		text: "Download",
		click: function () {
			downloadfiles(filename);
		}
	},
	{
		id: "Cancel",
		text: "Cancel",
		click: function () {
			$(this).dialog('close');
		}
	}
],*/
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

					parent.$("#satopdialog").html('');
					parent.$("#satopdialog").html(discode);
					parent.$("#satopdialog").dialog("open");
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
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>View Saving Analysis</h4></div>",
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
					parent.$("#saresponse").html('');
					parent.$("#satable").html('');
					parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "?type=unread";} ?>');
				  }
			});

			$('#view-fi-cancel').click(function() {
				$("#view-dialog-message").dialog("close");
				$("#view-dialog-message").dialog('destroy');
				$("#view-dialog-message").remove();
				parent.$("#saresponse").html('');
				parent.$("#satable").html('');
				parent.$('#satable').load('assets/ajax/saving_analysis_pedit.php<?php if(isset($_GET["type"]) and $_GET["type"]=="unread"){echo "?type=unread";} ?>');
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
