<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


if(isset($_SESSION['group_id']) and $_SESSION['group_id'] == 1) {
	if(isset($_GET["loadselect"]) and isset($_GET["cmpid"]) and @trim($_GET["cmpid"]) != 0){
		$tmp_select="<option value=''>&nbsp;&nbsp;Select Company Admin</option>";
		$tmp_cmpid=$mysqli->real_escape_string(@trim($_GET["cmpid"]));
	   if ($stmt = $mysqli->prepare("SELECT u.user_id,u.firstname,u.lastname FROM user u,company c Where u.company_id = c.company_id and c.company_id='".$tmp_cmpid."' and u.usergroups_id = 3 Order By u.firstname")){

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($__cmpid,$__cmpfirstname,$__cmplastname);
				while($stmt->fetch()){
					$tmp_select=$tmp_select."<option value='".$__cmpid."'>&nbsp;&nbsp;".$__cmpfirstname." ".$__cmplastname."</option>";
				}
			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
		echo $tmp_select;
		//echo json_encode(array("error"=>"","partselect"=>$tmp_select));

	}elseif(isset($_GET["action"]) and $_GET["action"]=="edit" and isset($_GET["adid"]) and @trim($_GET["adid"]) != 0 and @trim($_GET["adid"]) != ""){
		$tmp_adid=$mysqli->real_escape_string(@trim($_GET["adid"]));

		//if ($stmtk = $mysqli->prepare("SELECT ad.id,ad.company_id,c.company_name,ad.user_id,u.firstname,u.lastname,ad.date_added FROM energy_advocate ad,user u,company c Where ad.user_id = u.user_id and u.usergroups_id = 2 and ad.company_id = c.company_id and ad.id = '".$tmp_adid."' LIMIT 1")) {
		if ($stmtk = $mysqli->prepare("SELECT company_id,company_name,energy_advocate,company_admin,ubm_support FROM company c Where company_id != 1 and company_id='".$tmp_adid."' LIMIT 1")) {

//("SELECT ad.id,ad.company_id,c.company_name,ad.user_id,u.firstname,u.lastname,ad.date_added FROM energy_advocate ad,user u,company c Where ad.user_id = u.id and u.usergroups_id = 2 and ad.company_id = c.id and ad.id = '".$tmp_adid."' LIMIT 1")) {

			$stmtk->execute();
			$stmtk->store_result();
			if ($stmtk->num_rows > 0) {
				$stmtk->bind_result($ad_Companyid,$ad_Companyname,$ad_EnergyAdv,$ad_CmpAdm,$ad_UbmSup);
				$stmtk->fetch();
	?>
	<style>
	.dz-default span{
		left: 33%;
		position: relative;
		top: 40%
	}
	#add-energyadvocate{
		overflow:hidden;
	}
	.fi-upload{
		height: auto !important;
	}
	.width-full{
		width:100% !important;
	}
	#edit-energyadvocate footer{text-align:center;}
	#edit-energyadvocate footer button{float:none !important;}
	</style>
			<div id="edit-energyadvocate" title="Edit Focus Items">
				<h5 align="center" style="display:none;">Company Name: <?php echo $ad_Companyname; ?></h5>
				<hr style="display:none;">
							<form id="edit-energyadvocate-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

								<fieldset>
									<div class="row">
										<section class="col col-12">Energy Advocate
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="editedid" id="editedid" placeholder="Energy Advocate" class="">
												<option value="">&nbsp;&nbsp;Select Energy Advocate</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT user_id,firstname,lastname FROM user Where (usergroups_id = 1 or usergroups_id = 2) Order By firstname')){

//('SELECT id,firstname,lastname FROM user Where usergroups_id = 2 Order By firstname')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__firstname,$__lastname);
														while($stmt->fetch()){
															echo "<option value='".$__id."' ".($ad_EnergyAdv == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__firstname." ".$__lastname."</option>";
														}
													}
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
											?>
												<input type="hidden" id="editcid" name="editcid" value="<?php echo $ad_Companyid; ?>">
												<input type="hidden" name="editad" id="editad" value="edit">
											</select>
											</label>
										</section>

										<section class="col col-12">Company Admin
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="editcmpadid" id="editcmpadid" placeholder="Company Admin" class="">
												<option value="">&nbsp;&nbsp;Select Company Admin</option>
											<?php
											   if ($stmt = $mysqli->prepare("SELECT u.user_id,u.firstname,u.lastname FROM user u,company c Where u.company_id = c.company_id and c.company_id='".$tmp_adid."' and u.usergroups_id = 3 Order By u.firstname")){

//('SELECT id,firstname,lastname FROM user Where usergroups_id = 2 Order By firstname')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__caid,$__cafirstname,$__calastname);
														while($stmt->fetch()){
															echo "<option value='".$__caid."' ".($ad_CmpAdm == $__caid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__cafirstname." ".$__calastname."</option>";
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
									</div>
									<div class="row">
										<section class="col col-12">Ubm Support
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<?php
												$tmp_list=array();
											   /*if ($stmt = $mysqli->prepare("SELECT u.user_id,u.firstname,u.lastname,c.company_name FROM user u,company c Where u.company_id = c.company_id and (u.usergroups_id = 3 or u.usergroups_id = 5 or u.usergroups_id = 1 or u.usergroups_id = 2 or u.usergroups_id = 4) Order By u.firstname")){ */
											   if ($stmt = $mysqli->prepare("SELECT u.user_id,u.firstname,u.lastname,c.company_name FROM user u,company c Where u.company_id = c.company_id and (u.usergroups_id = 1 or u.usergroups_id = 2) Order By u.firstname")){

//('SELECT id,firstname,lastname FROM user Where usergroups_id = 2 Order By firstname')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__umid,$__umfirstname,$__umlastname,$__umcompanyname);
														while($stmt->fetch()){
															$tmp_list[$__umcompanyname][]=array("user_id"=>$__umid,"firstname"=>$__umfirstname,"lastname"=>$__umlastname);
															//echo "<option value='".$__umid."' ".($ad_UbmSup == $__umid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__umfirstname." ".$__umlastname."</option>";
														}
													}
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
												if(count($tmp_list)){
													ksort($tmp_list);
													echo '<select name="editubmid" id="editubmid" placeholder="UBM Support" class=""><option value="">&nbsp;&nbsp;Select Ubm Support</option>';
													foreach($tmp_list as $kkky=>$vvvl){
														echo '<optgroup label="'.$kkky.'">';
														if(is_array($vvvl) and count($vvvl)){
															foreach($vvvl as $kkkky=>$vvvvl){
																echo '<option value="'.$vvvvl["user_id"].'" '.($ad_UbmSup == $vvvvl["user_id"]?'SELECTED="SELECTED"':'').'>&nbsp;&nbsp;'.$vvvvl["firstname"].' '.$vvvvl["lastname"].'</option>';
															}
														}
													}
													echo "</select>";
												}
											?>
											</label>
										</section>
										<section class="col col-12"></section>
									</div>
								</fieldset>

								<footer>
									<button type="submit" class="btn btn-primary" id="edit-ad-submit">
										Save
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

			$("#edit-energyadvocate").dialog({
				autoOpen : true,
				modal : true,
				width: "auto",
				title : "<div class='widget-header'><h4 style='padding-left: 14px;'><i class='icon-ok'></i>Company Name: <?php echo $ad_Companyname ?></h4></div>",
				 close : function(){
					$("#edit-energyadvocate").dialog('destroy');
					$("#edit-energyadvocate").remove();
					parent.$("#adresponse").html('');
				  }
			});

			var $checkoutForm = $('#edit-energyadvocate-form').validate({
			// Rules for form validation
				rules : {
					editcid : {
						required : true
					},
					editedid : {
						required : true
					},
					editcmpadid : {
						required : true
					},
					editubmid : {
						required : true
					}
				},

				// Messages for form validation
				messages : {
					editcid : {
						required : 'Please select company'
					},
					editedid : {
						required : 'Please select energy advocate'
					},
					editcmpadid : {
						required : 'Please select company admin'
					},
					editubmid : {
						required : 'Please select ubm support'
					}
				},
				// Ajax form submition
				submitHandler : function(form) {
					var formData = new FormData();
					formData.append('cid', $("#editcid").val());
					formData.append('eaid', $("#editedid").val());
					formData.append('editad', $("#editad").val());
					formData.append('editcmpadid', $("#editcmpadid").val());
					formData.append('editubmid', $("#editubmid").val());

					$.ajax({
						type: 'post',
						url: 'assets/includes/energyadvocateedit.inc.php',
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
									$("#edit-energyadvocate").dialog("close");
									$("#edit-energyadvocate").dialog('destroy');
									$("#edit-energyadvocate").remove();
									parent.$("#adresponse").html('');
									parent.$("#adtable").html('');
									parent.$('#adtable').load('assets/ajax/energy-advocate-pedit.php');
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
	</style>
	<!-- Add Section -->
			<div id="add-energyadvocate" title="Add Energy Advocate">
							<form id="add-adcheckout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileAdd()">

								<fieldset>
									<div class="row">
										<section class="col col-12">Company Name
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="addcid" id="addcid" placeholder="Company Name" class="">
												<option value="">&nbsp;&nbsp;Select Company Name</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT company_id,company_name FROM company where (energy_advocate IS NULL or energy_advocate=0) and company_id != 1 order by company_name ')){

//('SELECT id,company_name FROM company where id not in (select company_id from energy_advocate) order by company_name ')){

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
										<section class="col col-12">Company Admin
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="addcmpadid" id="addcmpadid" placeholder="Company Admin" class="">
											</select>
											</label>
										</section>
									</div>

									<div class="row">
										<section class="col col-12">Energy Advocate
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<select name="addedid" id="addedid" placeholder="Energy Advocate" class="">
												<option value="">&nbsp;&nbsp;Select Energy Advocate</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT user_id,firstname,lastname FROM user Where (usergroups_id = 1 or usergroups_id = 2) Order By firstname')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__firstname,$__lastname);
														while($stmt->fetch()){
															echo "<option value='".$__id."'>&nbsp;&nbsp;".$__firstname." ".$__lastname."</option>";
														}
													}
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
											?>
												<input type="hidden" name="addnewad" id="addnewad" value="new">
											</select>
											</label>
										</section>
										<section class="col col-12">Ubm Support
											<label class="select"> <i class="icon-append fa fa-user"></i>
											<?php
												$tmp_list=array();
											   if ($stmt = $mysqli->prepare("SELECT u.user_id,u.firstname,u.lastname,c.company_name FROM user u,company c Where u.company_id = c.company_id and (u.usergroups_id = 3 or u.usergroups_id = 5 or u.usergroups_id = 1 or u.usergroups_id = 2 or u.usergroups_id = 4) Order By u.firstname")){

//('SELECT id,firstname,lastname FROM user Where usergroups_id = 2 Order By firstname')){

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__umid,$__umfirstname,$__umlastname,$__umcompanyname);
														while($stmt->fetch()){
															$tmp_list[$__umcompanyname][]=array("user_id"=>$__umid,"firstname"=>$__umfirstname,"lastname"=>$__umlastname);
															//echo "<option value='".$__umid."' ".($ad_UbmSup == $__umid?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__umfirstname." ".$__umlastname."</option>";
														}
													}
												}else{
													header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
													exit();
												}
												if(count($tmp_list)){
													ksort($tmp_list);
													echo '<select name="addubmid" id="addubmid" placeholder="UBM Support" class=""><option value="">&nbsp;&nbsp;Select Ubm Support</option>';
													foreach($tmp_list as $kkky=>$vvvl){
														echo '<optgroup label="'.$kkky.'">';
														if(is_array($vvvl) and count($vvvl)){
															foreach($vvvl as $kkkky=>$vvvvl){
																echo '<option value="'.$vvvvl["user_id"].'">&nbsp;&nbsp;'.$vvvvl["firstname"].' '.$vvvvl["lastname"].'</option>';
															}
														}
													}
													echo "</select>";
												}
											?>
											</label>
										</section>
									</div>
								</fieldset>

								<footer>
									<button type="submit" class="btn btn-primary" id="add-ad-submit">
										Submit
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
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
		$( "#addcid" ).change(function() {
			if($("#addcid").val() == ""){}else{
				$( "#addcmpadid" ).load( "assets/ajax/energy-advocate-add.php?loadselect=true&cmpid="+$("#addcid").val() );
				/*var formData = new FormData();
				formData.append('cmpid', $("#addcid").val());
				formData.append('loadselect', "true");
				$.ajax({
					type: 'post',
					url: 'assets/ajax/energy-advocate-add.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								$("#addcmpadid").html(results.partselect);
							}else
								alert("Error in request. Please try again later.");
						}else{
							alert("Error in request. Please try again later.");
						}
					}
				});*/
			}
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

			$("#add-energyadvocate").dialog({
				autoOpen : true,
				modal : true,
				width: "auto",
				title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add New Energy Advocate</h4></div>",
				 close : function(){
					$("#add-energyadvocate").dialog('destroy');
					$("#add-energyadvocate").remove();
					parent.$("#adresponse").html('');
				  }
			});

			$('#add-ad-cancel').click(function() {
				$("#add-energyadvocate").dialog("close");
				$("#add-energyadvocate").dialog('destroy');
				$("#add-energyadvocate").remove();
				parent.$("#adresponse").html('');
			});



			var $checkoutForm = $('#add-adcheckout-form').validate({
			// Rules for form validation
				rules : {
					addcid : {
						required : true
					},
					addcmpadid : {
						required : true
					},
					addedid : {
						required : true
					},
					addubmid : {
						required : true
					}
				},

				// Messages for form validation
				messages : {
					addcid : {
						required : 'Please select company'
					},
					addcmpadid : {
						required : 'Please select company admin'
					},
					addedid : {
						required : 'Please select energy advocate'
					},
					addubmid : {
						required : 'Please select ubm support'
					}
				},
				// Ajax form submition
				submitHandler : function(form) {
							var formData = new FormData();
							formData.append('cid', $("#addcid").val());
							formData.append('eaid', $("#addedid").val());
							formData.append('newad', $("#addnewad").val());
							formData.append('addcmpadid', $("#addcmpadid").val());
							formData.append('addubmid', $("#addubmid").val());

							$.ajax({
								type: 'post',
								url: 'assets/includes/energyadvocateedit.inc.php',
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
											$("#add-energyadvocate").dialog("close");
											$("#add-energyadvocate").dialog('destroy');
											$("#add-energyadvocate").remove();
											parent.$("#adresponse").html('');
											parent.$("#adtable").html('');
											parent.$('#adtable').load('assets/ajax/energy-advocate-pedit.php');
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
		}

		loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction);

		function profileAdd(){
		}
	</script>
<?php }
}
?>
