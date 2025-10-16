<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

if(isset($_GET['uid']) and @trim($_GET['uid']) != "" and @trim($_GET['uid']) != 0 and isset($_GET['edituid']))
{
	$uid=$mysqli->real_escape_string(@trim($_GET['uid']));
	if ($stmt = $mysqli->prepare('SELECT sg.service_group,u.user_id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM `usage` u,service_group sg where u.user_id="'.$uid.'" and u.service_group_id=sg.service_group_id and sg.service_group_id != "" and sg.service_group_id != 0 order by sg.service_group_id,u.interval_end, u.meter_number desc LIMIT 1')) { 

//('SELECT sg.service_group,u.id, u.meter_id, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM `usage` u,service_group sg where u.id="'.$uid.'" and u.service_group_id=sg.id and sg.service_group_id != "" and sg.service_group_id != 0 order by sg.service_group_id,u.interval_end, u.meter_id desc LIMIT 1')) { 

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_sg,$_u_id,$_u_meter_id,$_u_interval_start,$_u_interval_end,$_u_interval_value,$_u_unit_of_measure,$_u_cost);
			$stmt->fetch();

?>
		<div id="edit-usage-message" title="Edit Usage">
						<form id="edit-usagecheckout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileSite()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Meter Id (<i>Readonly</i>)
										<label class="input">
											<input type="text" name="meterid" id="meterid" placeholder="Meter Id" value="<?php echo $_u_meter_id; ?>">
										</label>
									</section>
									<section class="col col-6">Service Group (<i>Readonly</i>)
										<label class="input">
											<input type="text" name="sgid" id="sgid" placeholder="Service Group" value="<?php echo $_sg; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Interval Start
										<label class="input">
											<input type="text" name="addintstart" id="addintstart" placeholder="Interval Start" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($_u_interval_start)); ?>">
										</label>
									</section>
									<section class="col col-6">Interval End
										<label class="input">
											<input type="text" name="addintend" id="addintend" placeholder="Interval End" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($_u_interval_end)); ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Interval Value
										<label class="input">
											<input type="text" name="addintval" id="addintval" placeholder="Interval Value" value="<?php echo $_u_interval_value; ?>">
										</label>
									</section>
									<section class="col col-6">Unit Of Measure
										<label class="input">
											<input type="text" name="addunitm" id="addunitm" placeholder="Unit Of Measure" value="<?php echo $_u_unit_of_measure; ?>" Readonly>
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Cost
										<label class="input">
											<input type="text" name="addcost" id="addcost" placeholder="Cost" value="<?php echo $_u_cost; ?>">
										</label>
									</section>
									<section class="col col-6">
										<input type="hidden" name="adduid" id="adduid" value="<?php echo $_u_id; ?>">
										<input type="hidden" name="editfusage" id="editfusage" value="edit">
									</section>
								</div>

							<footer>
								<button type="submit" class="btn btn-primary" id="edit-usage-submit">
									Submit
								</button>
								<button type="button" class="btn" id="edit-usage-cancel">
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
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
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
	
		$("#edit-usage-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Usage</h4></div>",
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
	
		});

		$('#edit-usage-cancel').click(function() {
			$("#edit-usage-message").dialog("close");
			parent.$("#response").html('');
			$("#edit-usage-message").remove();
			
		});

		
		
		var $checkoutForm = $('#edit-usagecheckout-form').validate({
		// Rules for form validation
			rules : {
				addintstart : {
					required : true
				},
				addintend : {
					required : true
				},
				addintval : {
					required : true
				},
				addunitm : {
					required : true
				},
				addcost : {
					required : true
				}
			},
	
			// Messages for form validation
			messages : {
				addintstart : {
					required : 'Please enter interval start'
				},
				addintend : {
					required : 'Please enter interval end'
				},
				addintval : {
					required : 'Please enter interval value'
				},
				addunitm : {
					required : 'Please enter unit of measure'
				},
				addcost : {
					required : 'Please enter cost'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('uid', $("#adduid").val());
				formData.append('intstart', $("#addintstart").val());
				formData.append('intend', $("#addintend").val());
				formData.append('intval', $("#addintval").val());
				formData.append('unitm', $("#addunitm").val());
				formData.append('cost', $("#addcost").val());
				
				formData.append('uedit', $("#editfusage").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/sitesedit.inc.php',
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
								$("#edit-usage-message").dialog("close");
								parent.$("#response").html('');
								$("#edit-usage-message").remove();
								//parent.$('#sitesaccount').load('assets/ajax/details.php?aid='+aid);
								//parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');								
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
	
	function profileSite(){
		//return false;
	}
</script>	




<?php
		}else
			die();
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}//else
		//die("Error Occured! Please try after sometime.");
}elseif(isset($_GET["addnewacc"]) and isset($_GET["sid"]) and @trim($_GET["sid"]) != "" and @trim($_GET["sid"]) != 0){
	$sid=$mysqli->real_escape_string(@trim($_GET["sid"]));
	if ($stmt = $mysqli->prepare('SELECT s.site_id,s.site_name FROM sites s,company c, user up WHERE s.site_id="'.$sid.'" and s.company_id=c.company_id and up.company_id=c.company_id LIMIT 1')) { 

//('SELECT s.id,s.site_name FROM sites s,company c, user up WHERE s.id="'.$sid.'" and s.company_id=c.id and up.company_id=c.id LIMIT 1')) { 

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($Id,$Site_Name);
			$stmt->fetch();
?>
		<div id="add-account-message" title="Add Account">
						<form id="add-acccheckout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileSite()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Account Number 1
										<label class="input">
											<input type="text" name="addacc1" id="addacc1" placeholder="Account Number 1" value="">
										</label>
									</section>
									<section class="col col-6">Account Number 2
										<label class="input">
											<input type="text" name="addacc2" id="addacc2" placeholder="Account Number 2" value="">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Account Number 3
										<label class="input">
											<input type="text" name="addacc3" id="addacc3" placeholder="Account Number 3" value="">
										</label>
									</section>
									<section class="col col-6">Site Name <i>(Readonly)</i>
										<label class="input">
											<input type="text" name="addsname" id="addsname" placeholder="Site name" value="<?php echo $Site_Name; ?>" Readonly>
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Vendor Name
										<label class="select"> <i></i>
											<select name="addvendor" id="addvendor" placeholder="Vendor">
												<option value="">Select Vendor</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT vendor_id,vendor_name FROM Vendor ORDER BY vendor_name')){ 

//('SELECT id,vendor_name FROM Vendor ORDER BY vendor_name')){ 

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__vendor);
														while($stmt->fetch()){
															echo "<option value='".$__id."'>".$__vendor."</option>";
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
									<section class="col col-6">Commodity Id
										<label class="input">
											<input type="text" name="addcommodity" id="addcommodity" placeholder="Commodity" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Meter Id <i>each seperated by comma (,)</i>
										<label class="input">
											<input type="text" name="addmeterid" id="addmeterid" placeholder="Meter Id" value="">
										</label>
									</section>
									<section class="col col-6">Service Group
										<label class="select"> <i></i>
											<select name="addservicegroup" id="addservicegroup" placeholder="Service Group">
												<option value="">Select Service Group</option>
											<?php
											   if ($stmt = $mysqli->prepare('SELECT service_group_id,service_group FROM service_group ORDER BY service_group')){ 

//('SELECT id,service_group FROM service_group ORDER BY service_group')){ 

													$stmt->execute();
													$stmt->store_result();
													if ($stmt->num_rows > 0) {
														$stmt->bind_result($__id,$__service_gp);
														while($stmt->fetch()){
															echo "<option value='".$__id."'>".$__service_gp."</option>";
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
									<section class="col col-6">Active Date
										<label class="input">
											<input type="text" name="addadate" id="addadate" placeholder="Active Date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
									<section class="col col-6">Inactive Date
										<label class="input">
											<input type="text" name="addinadate" id="addinadate" placeholder="Inactive Date" class="datepicker" data-dateformat='mm/dd/yy' value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Rate Id
										<label class="input">
											<input type="number" name="addrateid" id="addrateid" placeholder="Rate Id" value="">
										</label>
									</section>
									<section class="col col-6">Invoice Source
										<label class="input">
											<input type="text" name="addinvoicesource" id="addinvoicesource" placeholder="Invoice Source" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Invoice Tracked
										<label class="input">
											<input type="text" name="addinvoicetracked" id="addinvoicetracked" placeholder="Invoice Tracked" value="">
										</label>
									</section>
									<section class="col col-6">Managed
										<label class="input">
											<input type="text" name="addmanaged" id="addmanaged" placeholder="Managed" value="">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Utility Meter
										<label class="input">
											<input type="text" name="addutilitymeter" id="addutilitymeter" placeholder="Utility Meter" value="">
										</label>
									</section>
									<section class="col col-6">
										<input type="hidden" name="addsid" id="addsid" value="<?php echo $Id; ?>">
										<input type="hidden" name="addaccount" id="addaccount" value="add">
									</section>
								</div>

							<footer>
								<button type="submit" class="btn btn-primary" id="add-account-submit">
									Submit
								</button>
								<button type="button" class="btn" id="add-account-cancel">
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
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
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
	
		$("#add-account-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Add Account</h4></div>",
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
	
		});

		$('#add-account-cancel').click(function() {;
			$("#add-account-message").dialog("close");
			$("#add-account-message").remove();
			$("#response").html('');
		});

		
		
		var $checkoutForm = $('#add-acccheckout-form').validate({
		// Rules for form validation
			rules : {
				addacc1 : {
					required : true
				},
				addvendor : {
					required : true
				},
				addmeterid : {
					required : true
				},
				addservicegroup : {
					required : true
				}
			},
	
			// Messages for form validation
			messages : {
				addacc1 : {
					required : 'Please enter your account number1'
				},
				addvendor : {
					required : 'Please select vendor name'
				},
				addmeterid : {
					required : 'Please enter meter id'
				},
				addservicegroup : {
					required : 'Please select service group'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('sid', $("#addsid").val());
				formData.append('account1', $("#addacc1").val());
				formData.append('account2', $("#addacc2").val());
				formData.append('account3', $("#addacc3").val());
				//formData.append('sname', $("#addsname").val());
				formData.append('vendor', $("#addvendor").val());
				formData.append('commodity', $("#addcommodity").val());
				formData.append('meter', $("#addmeterid").val());
				formData.append('servicegp', $("#addservicegroup").val());
				formData.append('activedate', $("#addadate").val());
				formData.append('inactivedate', $("#addinadate").val());
				formData.append('rateid', $("#addrateid").val());
				formData.append('invoicesrc', $("#addinvoicesource").val());				
				formData.append('invoicetrk', $("#addinvoicetracked").val());
				formData.append('managed', $("#addmanaged").val());
				formData.append('utilitymtr', $("#addutilitymeter").val());
				
				formData.append('aaddnew', $("#addaccount").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/sitesedit.inc.php',
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
								$("#add-account-message").dialog("close");
								parent.$("#response").html('');
								$("#add-account-message").remove();
								parent.$("#load-sdetails").html('<iframe src="assets/ajax/details.php?id=<?php echo $Id; ?>" style="width:100%;height:383px" frameBorder="0" scrolling="no"></iframe>');
								//parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');								
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
	
	function profileSite(){
		//return false;
	}
</script>	




<?php
		}else
			die();
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}//else
		//die("Error Occured! Please try after sometime.");

}else{
	die("Error Occured! Please try after sometime.");
}
?>