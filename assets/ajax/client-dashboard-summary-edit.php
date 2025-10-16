<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$uid=$_SESSION['user_id'];
$comp_id=$_SESSION['company_id'];

if(!isset($_SESSION['group_id']) and $_SESSION['group_id'] != 3 and $_SESSION["group_id"] != 5)
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");
	
if(isset($_GET["action"]) and $_GET["action"]=="edit" and isset($_GET["cds"]))
{
	$cds_sum=$cds_aump=$cds_aumg=$cds_cumg=$cds_cumm=$cds_vstd="";
	$cdsid=$mysqli->real_escape_string(@trim($_GET["cds"]));
	if($_SESSION['group_id'] != 1) $cdsid=$comp_id;

	if($stmtk = $mysqli->prepare("SELECT sites_under_mgmt,acc_under_mgmt_pwr, acc_under_mgmt_gas, cons_under_mgmt_gwh, cons_under_mgmt_mmbtu,val_saving_to_date FROM company WHERE company_id='".$cdsid."' LIMIT 1")){ 

//("SELECT sites_under_mgmt,acc_under_mgmt_pwr, acc_under_mgmt_gas, cons_under_mgmt_gwh, cons_under_mgmt_mmbtu, val_saving_to_date,user_id FROM client_dashboard_summary WHERE user_id='".$cdsid."' LIMIT 1"))

        $stmtk->execute();
        $stmtk->store_result();
        if ($stmtk->num_rows > 0) {
			$stmtk->bind_result($cds_sum,$cds_aump,$cds_aumg,$cds_cumg,$cds_cumm,$cds_vstd);
			$stmtk->fetch();
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
?>
<style>
.txt-center{text-align:center;}
.nofloat{float:none !important;}
</style>
						<form id="edit-checkout-form" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="return false">
							<fieldset>
								<div class="row">
									<section class="col col-6">Sites Under Mgmt
										<label class="input"> <i class="icon-append fa fa-map-marker"></i>
											<input type="text" name="editsum" id="editsum" placeholder="Sites Under Mgmt" value="<?php echo $cds_sum; ?>">
										</label>
									</section>
									<section class="col col-6">Value Of Saving To Date
										<label class="input"> <i class="icon-append fa fa-money"></i>
											<input type="text" name="editvstd" id="editvstd" placeholder="Value Of Saving To Date" value="<?php echo $cds_vstd; ?>">
										</label>
									</section>
								</div>
								<br />
								Accounts Under Mgmt:
								<div class="row">
									<section class="col col-6">Power
										<label class="input"> <i class="icon-append fa fa-flash"></i>
											<input type="text" name="editpower" id="editpower" placeholder="Power" value="<?php echo $cds_aump; ?>">
										</label>
									</section>
									<section class="col col-6">Gas
										<label class="input"> <i class="icon-append fa fa-fire"></i>
											<input type="text" name="editgas" id="editgas" placeholder="Gas" value="<?php echo $cds_aumg; ?>">
										</label>
									</section>
								</div>
								<br />
								Consumption Under Mgmt:
								<div class="row">
									<section class="col col-6">GWh
										<label class="input"> <i class="icon-append fa fa-flash"></i>
											<input type="text" name="editgwh" id="editgwh" placeholder="Power" value="<?php echo $cds_cumg; ?>">
										</label>
									</section>
									<section class="col col-6">MMBtu
										<label class="input"> <i class="icon-append fa fa-flash"></i>
											<input type="text" name="editmmbtu" id="editmmbtu" placeholder="Gas" value="<?php echo $cds_cumm; ?>">
											<input type="hidden" name="cdsid" id="cdsid" value="<?php echo $cdsid; ?>">
											<input type="hidden" name="editcds" id="editcds" value="editcds">
										</label>
									</section>
								</div>
							</fieldset>

							<footer class="txt-center">
								<button type="submit" class="btn btn-primary nofloat" id="edit-cds-submit">
									Update
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
		var $checkoutForm = $('#edit-checkout-form').validate({
		// Rules for form validation
			rules : {
				editsum : {
					required : true
				},
				editvstd : {
					required : true
				},
				editpower : {
					required : true
				},
				editgas : {
					required : true
				},
				editgwh : {
					required : true
				},
				editmmbtu : {
					required : true
				}
			},
	
			// Messages for form validation
			messages : {
				editsum : {
					required : 'Please enter Sites Under Mgmt'
				},
				editvstd : {
					required : 'Please enter Value Of Saving To Date'
				},
				editpower : {
					required : 'Please enter Accounts Under Mgmt(Power)'
				},
				editgas : {
					required : 'Please enter Accounts Under Mgmt(Gas)'
				},
				editgwh : {
					required : 'Please enter Consumption Under Mgmt(GWh)'
				},
				editmmbtu : {
					required : 'Please enter Consumption Under Mgmt(MMBtu)'
				}
			},
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('sum', $("#editsum").val());
				formData.append('vstd', $("#editvstd").val());
				formData.append('power', $("#editpower").val());
				formData.append('gas', $("#editgas").val());
				formData.append('gwh', $("#editgwh").val());
				formData.append('mmbtu', $("#editmmbtu").val());
				formData.append('cdsid', $("#cdsid").val());
				formData.append('edit', $("#editcds").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/clientdashboardsummaryedit.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								cdsid=$("#cdsid").val();
								alert("Success");
								//parent.$("#cdsresponse").html('');
								parent.$("#getdetails").html('');
								parent.$('#getdetails').load('assets/ajax/client-dashboard-summary-edit.php?action=edit&cds='+cdsid);								
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
<?php
}
?>