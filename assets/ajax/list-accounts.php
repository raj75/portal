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

if(isset($_GET['aid']) and @trim($_GET['aid']) != "" and @trim($_GET['aid']) != 0 and isset($_GET['editaid']))
{
	$aid=$mysqli->real_escape_string(@trim($_GET['aid']));
	if ($stmt = $mysqli->prepare('SELECT a.id, a.site_number,s.site_name, a.vendor_id,a.commodity_id,a.meter_number,a.service_group_id,a.active_date,a.inactive_date,a.account_number1,a.account_number2,a.account_number3,a.rate_id,a.invoice_source,a.invoice_tracked,a.managed,a.utility_meter FROM accounts a, sites s,company c, user up WHERE a.id="'.$aid.'" and a.site_number=s.site_number and s.company_id=c.company_id and up.company_id=c.company_id group by a.id LIMIT 1')) { 

//('SELECT a.id, a.sites_id,s.site_name, a.vendor_id,a.commodity_id,a.meter_id,a.service_group_id,a.active_date,a.inactive_date,a.account_number1,a.account_number2,a.account_number3,a.rate_id,a.invoice_source,a.invoice_tracked,a.managed,a.utility_meter FROM accounts a, sites s,company c, user up WHERE a.id="'.$aid.'" and a.sites_id=s.id and s.company_id=c.id and up.company_id=c.id group by a.id LIMIT 1')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($Id,$Sites_Id,$Site_Name,$Vendor_Id,$Commodity_Id,$Meter_Id,$Service_Group_Id,$Active_Date,$Inactive_Date,$Account_Number1,$Account_Number2,$Account_Number3,$Rate_Id,$Invoice_Source,$Invoice_Tracked,$Managed,$Utility_Meter);
			$stmt->fetch();

?>
		<div id="edit-account-message" title="Edit Account">
						<form id="edit-acccheckout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return profileSite()">

							<fieldset>
								<div class="row">
									<section class="col col-6">Account Number 1
										<label class="input">
											<input type="text" name="addacc1" id="addacc1" placeholder="Account Number 1" value="<?php echo $Account_Number1; ?>">
										</label>
									</section>
									<section class="col col-6">Account Number 2
										<label class="input">
											<input type="text" name="addacc2" id="addacc2" placeholder="Account Number 2" value="<?php echo $Account_Number2; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Account Number 3
										<label class="input">
											<input type="text" name="addacc3" id="addacc3" placeholder="Account Number 3" value="<?php echo $Account_Number3; ?>">
										</label>
									</section>
									<section class="col col-6">Site Name
										<label class="input">
											<input type="text" name="addsname" id="addsname" placeholder="Site name" value="<?php echo $Site_Name; ?>" Readonly>
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Vendor Name
										<label class="select">
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
															echo "<option value='".$__id."' ".(($Vendor_Id==$__id)?"Selected":"").">".$__vendor."</option>";
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
											<input type="text" name="addcommodity" id="addcommodity" placeholder="Commodity" value="<?php echo $Commodity_Id; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Meter Id <i>each seperated by comma (,)</i>
										<label class="input">
											<input type="text" name="addmeterid" id="addmeterid" placeholder="Meter Id" value="<?php echo $Meter_Id; ?>">
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
															echo "<option value='".$__id."' ".(($Service_Group_Id==$__id)?"Selected":"").">".$__service_gp."</option>";
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
											<input type="text" name="addadate" id="addadate" placeholder="Active Date" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($Active_Date)); ?>">
										</label>
									</section>
									<section class="col col-6">Inactive Date
										<label class="input">
											<input type="text" name="addinadate" id="addinadate" placeholder="Inactive Date" class="datepicker" data-dateformat='mm/dd/yy' value="<?php echo @date("m/d/Y", @strtotime($Inactive_Date)); ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Rate Id
										<label class="input">
											<input type="number" name="addrateid" id="addrateid" placeholder="Rate Id" value="<?php echo $Rate_Id; ?>">
										</label>
									</section>
									<section class="col col-6">Invoice Source
										<label class="input">
											<input type="text" name="addinvoicesource" id="addinvoicesource" placeholder="Invoice Source" value="<?php echo $Invoice_Source; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Invoice Tracked
										<label class="input">
											<input type="text" name="addinvoicetracked" id="addinvoicetracked" placeholder="Invoice Tracked" value="<?php echo $Invoice_Tracked; ?>">
										</label>
									</section>
									<section class="col col-6">Managed
										<label class="input">
											<input type="text" name="addmanaged" id="addmanaged" placeholder="Managed" value="<?php echo $Managed; ?>">
										</label>
									</section>
								</div>
								
								<div class="row">
									<section class="col col-6">Utility Meter
										<label class="input">
											<input type="text" name="addutilitymeter" id="addutilitymeter" placeholder="Utility Meter" value="<?php echo $Utility_Meter; ?>">
										</label>
									</section>
									<section class="col col-6">
										<input type="hidden" name="addaid" id="addaid" value="<?php echo $Id; ?>">
										<input type="hidden" name="editaccount" id="editaccount" value="edit">
									</section>
								</div>

							<footer>
								<button type="submit" class="btn btn-primary" id="edit-account-submit">
									Submit
								</button>
								<button type="button" class="btn" id="edit-account-cancel">
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
	
		$("#edit-account-message").dialog({
			autoOpen : true,
			modal : true,
			width: "auto",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit Account</h4></div>",
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

		$('#edit-account-cancel').click(function() {;
			$("#edit-account-message").dialog("close");
			$("#edit-account-message").remove();
			$("#response").html("");
		});

		
		
		var $checkoutForm = $('#edit-acccheckout-form').validate({
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
				formData.append('aid', $("#addaid").val());
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
				
				formData.append('aedit', $("#editaccount").val());

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
								$("#edit-account-message").dialog("close");
								parent.$("#response").html('');
								$("#edit-account-message").remove();
								parent.$("#load-sdetails").html('<iframe src="assets/ajax/details.php?id=<?php echo $Sites_Id; ?>" style="width:100%;height:383px" frameBorder="0" scrolling="no"></iframe>');
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
//}elseif(isset($_GET["addnewacc"]) and isset($_GET["sid"]) and @trim($_GET["sid"]) != "" and @trim($_GET["sid"]) != 0){
}elseif(isset($_GET["addnewacc"]) and isset($_GET["sid"]) and @trim($_GET["sid"]) != ""){
	$sid=$mysqli->real_escape_string(@trim($_GET["sid"]));
	if ($stmt = $mysqli->prepare('SELECT s.site_number,s.site_name FROM sites s,company c, user up WHERE s.site_number="'.$sid.'" and s.company_id=c.company_id and up.company_id=c.company_id LIMIT 1')) { 

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
											   if ($stmt = $mysqli->prepare('SELECT vendor_id,vendor_name FROM vendor ORDER BY vendor_name')){ 

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