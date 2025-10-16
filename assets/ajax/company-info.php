<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();



if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!$_SESSION['user_id'])
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$company_id=$_SESSION['company_id'];

if(!isset($_GET["cid"])){
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-fw fa-inbox "></i>
				Admin
			<span>>
				Company Defaults
			</span>
		</h1>
	</div>
</div>

<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
	<section id="widget-grid-info" class="">

		<!-- row -->
		<div class="row">
			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:2%;">

			<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
			<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
			<style>
			#datatable_fixed_column_cp_filter{
			float: left;
			width: auto !important;
			margin: 1% 1% !important;
			}
			.dt-buttons{
			float: right !important;
			margin: 0.9% auto !important;
			}
			#datatable_fixed_column_cp_length{
			float: right !important;
			margin: 1% 1% !important;
			}
			.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
			.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
			table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
			#datatable_fixed_column_cp{border-bottom: 1px solid #ccc !important;}}
			</style>
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Company Defaults </h2>

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
							<table id="datatable_fixed_column_cp" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
									<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Company ID" />
										</th>

										<th class="hasinput">
											<input type="text" class="form-control" placeholder="Filter Company Name" />
										</th>

										<th class="hasinput">
										</th>
									</tr>
									<tr>
										<th data-hide="phone">Company ID</th>
										<th>Company Name</th>
										<th data-hide="phone,tablet">Action</th>
									</tr>
								</thead>
								<tbody>
	<?php
		$sql='SELECT company_id,company_name FROM company where company_id != 1 order by company_id';

		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($company_id,$company_name);
				while($stmt->fetch()) {
				?>
					<tr>

							<td><?php echo $company_id; ?></td>
							<td><?php echo $company_name; ?></td>
							<td><button class="btn-primary" onclick="loadcompanydefaults(<?php echo $company_id; ?>)">Edit Defaults</button></td>
						</tr>
				<?php
				}
			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	?>
								</tbody>
							</table>
						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
			</article>
		</div>
	</section>
	<?php } ?>
	<div id="responsecinfo"></div>
	<script type="text/javascript">
	<?php if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){ ?>
	loadcompanydefaults(<?php echo $company_id; ?>);
	<?php } ?>
	function loadcompanydefaults(cid) {
		$('#responsecinfo').html('');
		$('#responsecinfo').load('assets/ajax/company-info.php?cid='+cid);
	}
	</script>
<?php
}
if(isset($_GET["cid"]) and $_GET["cid"] !=""){

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)
	$company_id=$_GET["cid"];

if ($stmt = $mysqli->prepare('SELECT cd.companyID,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Email`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.`Billing City`,cd.`Billing State`,cd.`Billing Zip Code`,cd.`Billing Contact 1 Name`,cd.`Billing Contact 1 Title`,cd.`Billing Contact 1 Phone`,cd.`Billing Contact 1 Email`,cd.`Billing Contact 1 Fax`,cd.`Billing Contact 2 Name`,cd.`Billing Contact 2 Title`,cd.`Billing Contact 2 Phone`,cd.`Billing Contact 2 Email`,cd.`Billing Contact 2 Fax`,cd.`Internal Contact 1 Name`,cd.`Internal Contact 1 Title`,cd.`Internal Contact 1 Phone`,cd.`Internal Contact 1 Email`,cd.`Internal Contact 1 Fax`,cd.`Internal Contact 2 Name`,cd.`Internal Contact 2 Title`,cd.`Internal Contact 2 Phone`,cd.`Internal Contact 2 Email`,cd.`Internal Contact 2 Fax` FROM company_defaults cd, company c WHERE cd.companyID=c.company_id and cd.companyID='.$company_id)) {

//('SELECT cd.ID,cd.companyID,cd.`Entity Name`,cd.`Tax ID`,cd.`Billing Email`,cd.`Billing Address1`,cd.`Billing Address2`,cd.`Billing Address3`,cd.`Billing City`,cd.`Billing State`,cd.`Billing Zip Code`,cd.`Billing Contact 1 Name`,cd.`Billing Contact 1 Title`,cd.`Billing Contact 1 Phone`,cd.`Billing Contact 1 Email`,cd.`Billing Contact 1 Fax`,cd.`Billing Contact 2 Name`,cd.`Billing Contact 2 Title`,cd.`Billing Contact 2 Phone`,cd.`Billing Contact 2 Email`,cd.`Billing Contact 2 Fax`,cd.`Internal Contact 1 Name`,cd.`Internal Contact 1 Title`,cd.`Internal Contact 1 Phone`,cd.`Internal Contact 1 Email`,cd.`Internal Contact 1 Fax`,cd.`Internal Contact 2 Name`,cd.`Internal Contact 2 Title`,cd.`Internal Contact 2 Phone`,cd.`Internal Contact 2 Email`,cd.`Internal Contact 2 Fax` FROM company_defaults cd, company c, user up WHERE cd.companyID=c.id and up.company_id=c.id and up.id='.$user_id))

	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($cdcompanyID,$cdEntityName,$cdTaxID,$cdBillingEmail,$cdBillingAddress1,$cdBillingAddress2,$cdBillingAddress3,$cdBillingCity,$cdBillingState,$cdBillingZipCode,$cdBillingContact1Name,$cdBillingContact1Title,$cdBillingContact1Phone,$cdBillingContact1Email,$cdBillingContact1Fax,$cdBillingContact2Name,$cdBillingContact2Title,$cdBillingContact2Phone,$cdBillingContact2Email,$cdBillingContact2Fax,$cdInternalContact1Name,$cdInternalContact1Title,$cdInternalContact1Phone,$cdInternalContact1Email,$cdInternalContact1Fax,$cdInternalContact2Name,$cdInternalContact2Title,$cdInternalContact2Phone,$cdInternalContact2Email,$cdInternalContact2Fax);
		$stmt->fetch();
	}

?>
<style>
.ci{margin-top:3%;}
.ci section{font-size:14px !important;color:#000;}
.ci .fullwidth{width:100%;}
.ci .center{text-align:center;}
h3.htitle{text-align:center;text-decoration: underline;}
.ci footer{text-align:center;}
.ci footer button{float:none !important;}
.ci header{background: #00bfff !important;text-align: center !important;color: #fff !important;font-weight: bold !important;}
.ci .jarviswidget{width: 82%;margin: 0 auto;}
</style>
<h3 class="htitle"></h3>
<section id="widget-grid" class="ci">
	<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
					<div class="widget-body no-padding">
						<form id="ci-edit" class="smart-form" novalidate="novalidate" method="post" enctype="multipart/form-data" ">
							<header>COMPANY INFO</header>
							<fieldset>
								<div class="row">
									<section class="col col-6">Entity Name
										<label class="input">
											<input type="text" name="cientityname" id="cientityname" placeholder="Entity Name" value="<?php if(isset($cdEntityName)) echo $cdEntityName; ?>">
										</label>
									</section>
									<section class="col col-6">Tax ID
										<label class="input">
											<input type="text" name="citaxid" id="citaxid" placeholder="Tax ID" value="<?php if(isset($cdTaxID)) echo $cdTaxID; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Email
										<label class="input">
											<input type="text" name="cibillingemail" id="cibillingemail" placeholder="Billing Email" value="<?php if(isset($cdBillingEmail)) echo $cdBillingEmail; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Address1
										<label class="input">
											<input type="text" name="cibillingaddress1" id="cibillingaddress1" placeholder="Billing Address1" value="<?php if(isset($cdBillingAddress1)) echo $cdBillingAddress1; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Address2
										<label class="input">
											<input type="text" name="cibillingaddress2" id="cibillingaddress2" placeholder="Billing Address2" value="<?php if(isset($cdBillingAddress2)) echo $cdBillingAddress2; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Address3
										<label class="input">
											<input type="text" name="cibillingaddress3" id="cibillingaddress3" placeholder="Billing Address3" value="<?php if(isset($cdBillingAddress3)) echo $cdBillingAddress3; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing City
										<label class="input">
											<input type="text" name="cibillingcity" id="cibillingcity" placeholder="Billing City" value="<?php if(isset($cdBillingCity)) echo $cdBillingCity; ?>">
										</label>
									</section>
									<section class="col col-6">Billing State
										<label class="input">
											<input type="text" name="cibillingstate" id="cibillingstate" placeholder="Billing State" value="<?php if(isset($cdBillingState)) echo $cdBillingState; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Zip Code
										<label class="input">
											<input type="text" name="cdbillingzipcode" id="cdbillingzipcode" placeholder="Billing Zip Code" value="<?php if(isset($cdBillingZipCode)) echo $cdBillingZipCode; ?>">
										</label>
									</section>
									<section class="col col-6">
										<label class="input">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Contact 1 Name
										<label class="input">
											<input type="text" name="cibillingcontact1name" id="cibillingcontact1name" placeholder="Billing Contact 1 Name" value="<?php if(isset($cdBillingContact1Name)) echo $cdBillingContact1Name; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Contact 1 Title
										<label class="input">
											<input type="text" name="cibillingcontact1title" id="cibillingcontact1title" placeholder="Billing Contact 1 Title" value="<?php if(isset($cdBillingContact1Title)) echo $cdBillingContact1Title; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Contact 1 Phone
										<label class="input">
											<input type="text" name="cibillingcontact1phone" id="cibillingcontact1phone" placeholder="Billing Contact 1 Phone" value="<?php if(isset($cdBillingContact1Phone)) echo $cdBillingContact1Phone; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Contact 1 Email
										<label class="input">
											<input type="text" name="cibillingcontact1email" id="cibillingcontact1email" placeholder="Billing Contact 1 Email" value="<?php if(isset($cdBillingContact1Email)) echo $cdBillingContact1Email; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Contact 1 Fax
										<label class="input">
											<input type="text" name="cibillingcontact1fax" id="cibillingcontact1fax" placeholder="Billing Contact 1 Fax" value="<?php if(isset($cdBillingContact1Fax)) echo $cdBillingContact1Fax; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Contact 2 Name
										<label class="input">
											<input type="text" name="cibillingcontact2name" id="cibillingcontact2name" placeholder="Billing Contact 2 Name" value="<?php if(isset($cdBillingContact2Name)) echo $cdBillingContact2Name; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Contact 2 Title
										<label class="input">
											<input type="text" name="cibillingcontact2title" id="cibillingcontact2title" placeholder="Billing Contact 2 Title" value="<?php if(isset($cdBillingContact2Title)) echo $cdBillingContact2Title; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Contact 2 Phone
										<label class="input">
											<input type="text" name="cibillingcontact2phone" id="cibillingcontact2phone" placeholder="Billing Contact 2 Phone" value="<?php if(isset($cdBillingContact2Phone)) echo $cdBillingContact2Phone; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Billing Contact 2 Email
										<label class="input">
											<input type="text" name="cibillingcontact2email" id="cibillingcontact2email" placeholder="Billing Contact 2 Email" value="<?php if(isset($cdBillingContact2Email)) echo $cdBillingContact2Email; ?>">
										</label>
									</section>
									<section class="col col-6">Billing Contact 2 Fax
										<label class="input">
											<input type="text" name="cibillingcontact2fax" id="cibillingcontact2fax" placeholder="Billing Contact 2 Fax" value="<?php if(isset($cdBillingContact2Fax)) echo $cdBillingContact2Fax; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Internal Contact 1 Name
										<label class="input">
											<input type="text" name="ciinternalcontact1name" id="ciinternalcontact1name" placeholder="Internal Contact 1 Name" value="<?php if(isset($cdInternalContact1Name)) echo $cdInternalContact1Name; ?>">
										</label>
									</section>
									<section class="col col-6">Internal Contact 1 Title
										<label class="input">
											<input type="text" name="ciinternalcontact1title" id="ciinternalcontact1title" placeholder="Internal Contact 1 Title" value="<?php if(isset($cdInternalContact1Title)) echo $cdInternalContact1Title; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Internal Contact 1 Phone
										<label class="input">
											<input type="text" name="ciinternalcontact1phone" id="ciinternalcontact1phone" placeholder="Internal Contact 1 Phone" value="<?php if(isset($cdInternalContact1Phone)) echo $cdInternalContact1Phone; ?>">
										</label>
									</section>
									<section class="col col-6">Internal Contact 1 Email
										<label class="input">
											<input type="text" name="ciinternalcontact1email" id="ciinternalcontact1email" placeholder="Internal Contact 1 Email" value="<?php if(isset($cdInternalContact1Email)) echo $cdInternalContact1Email; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Internal Contact 1 Fax
										<label class="input">
											<input type="text" name="ciinternalcontact1fax" id="ciinternalcontact1fax" placeholder="Internal Contact 1 Fax" value="<?php if(isset($cdInternalContact1Fax)) echo $cdInternalContact1Fax; ?>">
										</label>
									</section>
									<section class="col col-6">Internal Contact 2 Name
										<label class="input">
											<input type="text" name="ciinternalcontact2name" id="ciinternalcontact2name" placeholder="Internal Contact 2 Name" value="<?php if(isset($cdInternalContact2Name)) echo $cdInternalContact2Name; ?>">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">Internal Contact 2 Title
										<label class="input">
											<input type="text" name="ciinternalcontact2title" id="ciinternalcontact2title" placeholder="Internal Contact 2 Title" value="<?php if(isset($cdInternalContact2Title)) echo $cdInternalContact2Title; ?>">
										</label>
									</section>
									<section class="col col-6">Internal Contact 2 Phone
										<label class="input">
											<input type="text" name="ciinternalcontact2phone" id="ciinternalcontact2phone" placeholder="Internal Contact 2 Phone" value="<?php if(isset($cdInternalContact2Phone)) echo $cdInternalContact2Phone; ?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-6">Internal Contact 2 Email
										<label class="input">
											<input type="text" name="ciinternalcontact2email" id="ciinternalcontact2email" placeholder="Internal Contact 2 Email" value="<?php if(isset($cdInternalContact2Email)) echo $cdInternalContact2Email; ?>">
										</label>
									</section>
									<section class="col col-6">Internal Contact 2 Fax
										<label class="input">
											<input type="text" name="ciinternalcontact2fax" id="ciinternalcontact2fax" placeholder="Internal Contact 2 Fax" value="<?php if(isset($cdInternalContact2Fax)) echo $cdInternalContact2Fax; ?>">
											<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($cdcompanyID))echo $cdcompanyID;else echo $company_id; ?>">
										</label>
									</section>
								</div>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary" id="ci-submit">
									Update
								</button>
							</footer>
						</form>

					</div>

			</div>
		</article>
	</div>
</section>
<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
<script type="text/JavaScript" src="../assets/js/forms.js"></script>
<script type="text/javascript">
	/*$(function() {
	$(document).ready(function() {
		$('.datepicker')
		.datepicker({
			format: 'mm/dd/yyyy',
				changeMonth: true,
				changeYear: true
		});
	});
	});*/
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




		var $checkoutForm = $('#ci-edit').validate({
		// Rules for form validation
			/*rules : {
				addfname : {
					required : true
				},
				addlname : {
					required : true
				},
				addemail : {
					required : true,
					email : true
				},
				addphone : {
					required : true
				},
				addgender : {
					required : true
				},
				addcompany : {
					required : true
				},
				addpassword : {
					required : false,
					minlength : 3,
					maxlength : 20
				},
				addpasswordConfirm : {
					required : false,
					minlength : 3,
					maxlength : 20,
					equalTo : '#addpassword'
				},
				addusergroups : {
					required : true,
					digits : true
				},
				addusername : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				addfname : {
					required : 'Please enter your first name'
				},
				addlname : {
					required : 'Please enter your last name'
				},
				addemail : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				addphone : {
					required : 'Please enter your phone number'
				},
				addgender : {
					required : 'Please enter your gender'
				},
				addcompany : {
					required : 'Select company'
				},
				addpassword : {
					required : 'Please enter your password'
				},
				addpasswordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as confirm password'
				},
				addusername : {
					required : 'Please enter your username'
				},
				addusergroups : {
					required : 'Please enter your usergroup'
				}
			},*/
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();
				formData.append('company_id', $("#company_id").val());
				formData.append('Entity Name', $("#cientityname").val());
				formData.append('Tax ID', $("#citaxid").val());
				formData.append('Billing Email', $("#cibillingemail").val());
				formData.append('Billing Address1', $("#cibillingaddress1").val());
				formData.append('Billing Address2', $("#cibillingaddress2").val());
				formData.append('Billing Address3', $("#cibillingaddress3").val());
				formData.append('Billing City', $("#cibillingcity").val());
				formData.append('Billing State', $("#cibillingstate").val());
				formData.append('Billing Zip Code', $("#cdbillingzipcode").val());
				formData.append('Billing Contact 1 Name', $("#cibillingcontact1name").val());
				formData.append('Billing Contact 1 Title', $("#cibillingcontact1title").val());
				formData.append('Billing Contact 1 Phone', $("#cibillingcontact1phone").val());
				formData.append('Billing Contact 1 Email', $("#cibillingcontact1email").val());
				formData.append('Billing Contact 1 Fax', $("#cibillingcontact1fax").val());
				formData.append('Billing Contact 2 Name', $("#cibillingcontact2name").val());
				formData.append('Billing Contact 2 Title', $("#cibillingcontact2title").val());
				formData.append('Billing Contact 2 Phone', $("#cibillingcontact2phone").val());
				formData.append('Billing Contact 2 Email', $("#cibillingcontact2email").val());
				formData.append('Billing Contact 2 Fax', $("#cibillingcontact2fax").val());
				formData.append('Internal Contact 1 Name', $("#ciinternalcontact1name").val());
				formData.append('Internal Contact 1 Title', $("#ciinternalcontact1title").val());
				formData.append('Internal Contact 1 Phone', $("#ciinternalcontact1phone").val());
				formData.append('Internal Contact 1 Email', $("#ciinternalcontact1email").val());
				formData.append('Internal Contact 1 Fax', $("#ciinternalcontact1fax").val());
				formData.append('Internal Contact 2 Name', $("#ciinternalcontact2name").val());
				formData.append('Internal Contact 2 Title', $("#ciinternalcontact2title").val());
				formData.append('Internal Contact 2 Phone', $("#ciinternalcontact2phone").val());
				formData.append('Internal Contact 2 Email', $("#ciinternalcontact2email").val());
				formData.append('Internal Contact 2 Fax', $("#ciinternalcontact2fax").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/companyinfo.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								$.smallBox({
									title : "Success",
									content : "<i class='fa fa-clock-o'></i> <i>Company Information Updated!</i>",
									color : "#296191",
									iconSmall : "fa fa-thumbs-up bounce animated",
									timeout : 4000
								});
								//alert("Company Information Updated!");
								parent.$('#responsecinfo').html('');
								//$("#start-new-service").get(0).reset();
								//$('#start-new-service').prepend('<h3 class="alert alert-primary">Request received! We will process it soon.</h3>');//document.getElementById('myFormId').reset();
							}else{
								$.smallBox({
									title : "Error Occured.",
									content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
									color : "#FFA07A",
									iconSmall : "fa fa-warning shake animated",
									timeout : 4000
								});
								//alert("Error in request. Please try again later.");
								//$("#start-new-service").html('');
								//$('#message').html('Error in request. Please try again later.');
							}
						}else{
							$.smallBox({
								title : "Error Occured.",
								content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
							//alert("Error in request. Please try again later.");
							//$("#start-new-service").html('');
							//$('#message').html('Error in request. Please try again later.');
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
</script>
<?php
	//}else
		//die("<p style='text-align:center;font-weight:bold;display:none;'>No records found!</p><script>alert('No records found!');</script>");
}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
//else
	//die("<p style='text-align:center;'>Error Occured! Please try after sometime.</p>");



}
?>
