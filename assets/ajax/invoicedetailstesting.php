<?php
//header("Content-Disposition", "inline; filename=".$_GET['id'].".pdf");
require_once '../../lib/s3/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

//ini_set('memory_limit', '-1');
//set_time_limit(0);

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$cmpid=$_SESSION["company_id"];
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		</div>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		</div>
	</article>
<script>
<?php if(!isset($_GET['fromdashboard'])){ ?>
//alert("Yes");
<?php } ?>
$(".fromsecondpage").css("display", "none");
$(".fromdashboard").css("display", "none");
$(".fromthirdpage").css("display", "block");
window.scrollTo(0,0);
$(window).off("popstate");
history.pushState(null, null, '');
window.addEventListener('popstate', navback);
window.scrollTo(0,0);

function navback(){
	history.pushState(null, null, '');
	if($('.fromsecondpage').css('display') == 'block')
	{
		move_back();
	}else if($('.fromthirdpage').css('display') == 'block'){
		move_invoice_back();
	}else if($('.fromdashboard').css('display') == 'block'){
		move_back_dashboard();
	}
}
/*history.pushState(null, null, '');
window.addEventListener('popstate', function(event) {
	if($('.fromsecondpage').css('display') == 'block')
	{
		move_back();
	}else if($('.fromthirdpage').css('display') == 'block'){
		move_invoice_back();
	}
  history.pushState(null, null, '');
});*/
function move_invoice_back(){
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	parent.$('#sitesdetails').show();
	parent.$('#invoicedetails').html('');
	parent.$(".fromthirdpage").css("display", "none");
	parent.$(".fromthirdpage").css("display", "none");
	parent.$(".fromsecondpage").css("display", "block");
}
</script>
</div>
<?php

if(isset($_GET['id']) and $_GET['id'] != "")
{
	$id=$mysqli->real_escape_string($_GET['id']);

	/*if ($i_stmt = $mysqli->prepare("SELECT DISTINCT a.invoice_number,a.account_number,a.currency,CONCAT('$', FORMAT(a.totalDue,2)),
	CONCAT('$', FORMAT(a.balanceForward,2)),CONCAT('$', FORMAT(a.lateFee,2)),
	a.providerFee,date(a.dueDate),date(a.invoiceUpdated), b.vendor_name,b.vendor_abbreviation,b.vendor_altname1,b.vendor_altname2,
	b.vendor_altname3,b.vendor_altname4,b.vendor_altname5,b.vendor_type,b.vendorAddr1, b.vendorAddr2,b.vendorCity,b.vendorState,
	b.vendorZip,b.vendorCountry,concat( '(', left(b.vendorPhoneNbr1,3) , ') ' , mid(b.vendorPhoneNbr1,4,3) , '-', right(b.vendorPhoneNbr1,4)) AS vPhoneNbr1,
	concat( '(', left(b.vendorPhoneNbr2,3) , ') ' , mid(b.vendorPhoneNbr2,4,3) , '-', right(b.vendorPhoneNbr2,4)) AS vPhoneNbr2,
	concat( '(', left(b.vendorPhoneNbr3,3) , ') ' , mid(b.vendorPhoneNbr3,4,3) , '-', right(b.vendorPhoneNbr3,4)) AS vPhoneNbr3,
	concat( '(', left(b.vendorFaxNbr1,3) , ') ' , mid(b.vendorFaxNbr1,4,3) , '-', right(b.vendorFaxNbr1,4)) AS vFaxNbr1,
	concat( '(', left(b.vendorFaxNbr2,3) , ') ' , mid(b.vendorFaxNbr2,4,3) , '-', right(b.vendorFaxNbr2,4)) AS vFaxNbr2,
	b.vendorEmail1,b.vendorEmail2,a.company_id FROM invoiceIndex a LEFT JOIN vendor b ON a.capturis_vendor_id=b.capturis_vendor_id
	WHERE ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" company_id = ".$cmpid." and ")."
	invoice_number='".$id."'  LIMIT 1")) {*/
	//$id="435546490";	
/*	if ($i_stmt = $mysqli->prepare("SELECT
	a.InvoiceID AS invoice_number,
	b.AccountNumber AS account_number, 
	a.Currency AS currency, 
	CONCAT('$',FORMAT(a.TotalDue,2)) AS totalDue,
	CONCAT('$',FORMAT( a.BalanceForward, 2 )) AS balanceForward,
	CONCAT('$',FORMAT(a.LateFee,2)) AS lateFee,
	NULL AS providerFee,
	a.DueDate AS dueDate,
	a.InvoiceUpdated AS invoiceUpdated,
	c.VendorName AS vendor_name, 
	c.VendorAbbreviation AS vendor_abbreviation, 
	c.VendorAlternativeName1 AS vendor_altname1, 
	c.VendorAlternativeName2 AS vendor_altname2, 
	c.VendorAlternativeName3 AS vendor_altname3, 
	c.VendorAlternativeName4 AS vendor_altname4, 
	c.VendorAlternativeName5 AS vendor_altname5, 
	c.VendorTypeID AS vendor_type, 
	c.VendorAddress1 AS vendorAddr1, 
	c.VendorAddress2 AS vendorAddr2, 
	c.VendorCity AS vendorCity, 
	c.VendorState AS vendorState, 
	c.VendorZip AS vendorZip, 
	c.VendorCountry AS vendorCountry, 
	concat('(',LEFT (c.VendorPhone,3),') ',mid(c.VendorPhone,4,3),'-',RIGHT (c.VendorPhone,4)) AS vPhoneNbr1 , 
	concat('(',LEFT (c.VendorContactPhone1,3),') ',mid(c.VendorContactPhone1,4,3),'-',RIGHT (c.VendorContactPhone1,4)) AS vPhoneNbr2,
	concat('(',LEFT (c.VendorContactPhone2,3),') ',mid(c.VendorContactPhone2,4,3),'-',RIGHT (c.VendorContactPhone2,4)) AS vPhoneNbr3,
	concat('(',LEFT (c.VendorContactFax1,3),') ',mid(c.VendorContactFax1,4,3),'-',RIGHT (c.VendorContactFax1,4)) AS vFaxNbr1,
	concat('(',LEFT (c.VendorContactFax2,3),') ',mid(c.VendorContactFax2,4,3),'-',RIGHT (c.VendorContactFax2,4)) AS vFaxNbr2,
	c.VendorContactEmail1, 
	c.VendorContactEmail2, 
	a.ClientID AS company_id
FROM
	NewSchema5.tblInvoices AS a
	LEFT JOIN
	NewSchema5.tblAccounts AS b
	ON 
		a.AccountID = b.AccountID
	LEFT JOIN
	NewSchema5.tblVendors AS c
	ON 
		a.VendorID = c.VendorID
WHERE
 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.ClientID = ".$cmpid." and ")."
	 a.InvoiceID = '".$id."'
	AND a.DeleteStatus=0
	AND b.DeleteStatus=0
	LIMIT 1")) {*/
		
/*		
	if ($i_stmt = $mysqli->prepare("SELECT
	a.InvoiceID,
	a.InvoiceUBMID,
	a.SourceID,
	a.InvoiceID AS invoice_number, 
	b.AccountNumber AS account_number, 
	a.Currency AS currency, 
	CONCAT('$',FORMAT(a.TotalDue,2)) AS totalDue,
	CONCAT('$',FORMAT( a.BalanceForward, 2 )) AS balanceForward,
	CONCAT('$',FORMAT(a.LateFee,2)) AS lateFee,
	NULL AS providerFee,
	a.DueDate AS dueDate,
	a.InvoiceUpdated AS invoiceUpdated,
	c.VendorName AS vendor_name, 
	c.VendorAbbreviation AS vendor_abbreviation, 
	c.VendorAlternativeName1 AS vendor_altname1, 
	c.VendorAlternativeName2 AS vendor_altname2, 
	c.VendorAlternativeName3 AS vendor_altname3, 
	c.VendorAlternativeName4 AS vendor_altname4, 
	c.VendorAlternativeName5 AS vendor_altname5, 
	c.VendorTypeID AS vendor_type, 
	c.VendorAddress1 AS vendorAddr1, 
	c.VendorAddress2 AS vendorAddr2, 
	c.VendorCity AS vendorCity, 
	c.VendorState AS vendorState, 
	c.VendorZip AS vendorZip, 
	c.VendorCountry AS vendorCountry, 
	concat('(',LEFT (c.VendorPhone,3),') ',mid(c.VendorPhone,4,3),'-',RIGHT (c.VendorPhone,4)) AS vPhoneNbr1 , 
	concat('(',LEFT (c.VendorContactPhone1,3),') ',mid(c.VendorContactPhone1,4,3),'-',RIGHT (c.VendorContactPhone1,4)) AS vPhoneNbr2,
	concat('(',LEFT (c.VendorContactPhone2,3),') ',mid(c.VendorContactPhone2,4,3),'-',RIGHT (c.VendorContactPhone2,4)) AS vPhoneNbr3,
	concat('(',LEFT (c.VendorContactFax1,3),') ',mid(c.VendorContactFax1,4,3),'-',RIGHT (c.VendorContactFax1,4)) AS vFaxNbr1,
	concat('(',LEFT (c.VendorContactFax2,3),') ',mid(c.VendorContactFax2,4,3),'-',RIGHT (c.VendorContactFax2,4)) AS vFaxNbr2,
	c.VendorContactEmail1, 
	c.VendorContactEmail2, 
	a.ClientID AS company_id
FROM
	ubm_database.tblInvoices AS a
LEFT JOIN
	ubm_database.tblAccounts AS b
ON 
	a.AccountID = b.AccountID
LEFT JOIN
	ubm_database.tblVendors AS c
ON 
	a.VendorID = c.VendorID
WHERE
".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.ClientID = ".$cmpid." and ")." 
	a.InvoiceID = '".$id."'")) {
*/
	if ($i_stmt = $mysqli->prepare("SELECT
	a.InvoiceID,
	a.InvoiceUBMID,
	a.InvoiceImageID,
	a.SourceID,
	a.InvoiceID AS invoice_number, 
	b.AccountNumber AS account_number, 
	b.AccountNumber AS currency, 
	CONCAT('$',FORMAT(a.TotalDue,2)) AS totalDue,
	CONCAT('$',FORMAT( a.BalanceForward, 2 )) AS balanceForward,
	CONCAT('$',FORMAT(a.LateFee,2)) AS lateFee,
	NULL AS providerFee,
	a.DueDate AS dueDate,
	a.InvoiceUpdated AS invoiceUpdated,
	c.VendorName AS vendor_name, 
	c.VendorAbbreviation AS vendor_abbreviation, 
	c.VendorAlternativeName1 AS vendor_altname1, 
	c.VendorAlternativeName2 AS vendor_altname2, 
	c.VendorAlternativeName3 AS vendor_altname3, 
	c.VendorAlternativeName4 AS vendor_altname4, 
	c.VendorAlternativeName5 AS vendor_altname5, 
	c.VendorTypeID AS vendor_type, 
	c.VendorAddress1 AS vendorAddr1, 
	c.VendorAddress2 AS vendorAddr2, 
	c.VendorCity AS vendorCity, 
	c.VendorState AS vendorState, 
	c.VendorZip AS vendorZip, 
	c.VendorCountry AS vendorCountry, 
	concat('(',LEFT (c.VendorPhone,3),') ',mid(c.VendorPhone,4,3),'-',RIGHT (c.VendorPhone,4)) AS vPhoneNbr1 , 
	concat('(',LEFT (c.VendorContactPhone1,3),') ',mid(c.VendorContactPhone1,4,3),'-',RIGHT (c.VendorContactPhone1,4)) AS vPhoneNbr2,
	concat('(',LEFT (c.VendorContactPhone2,3),') ',mid(c.VendorContactPhone2,4,3),'-',RIGHT (c.VendorContactPhone2,4)) AS vPhoneNbr3,
	concat('(',LEFT (c.VendorContactFax1,3),') ',mid(c.VendorContactFax1,4,3),'-',RIGHT (c.VendorContactFax1,4)) AS vFaxNbr1,
	concat('(',LEFT (c.VendorContactFax2,3),') ',mid(c.VendorContactFax2,4,3),'-',RIGHT (c.VendorContactFax2,4)) AS vFaxNbr2,
	c.VendorContactEmail1, 
	c.VendorContactEmail2, 
	a.ClientID AS company_id
FROM
	ubm_database.tblInvoices AS a
LEFT JOIN
	ubm_database.tblAccounts AS b
ON 
	a.AccountID = b.AccountID
LEFT JOIN
	ubm_database.tblVendors AS c
ON 
	a.VendorID = c.VendorID
WHERE
".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.ClientID = ".$cmpid." and ")." 
	a.InvoiceID = '".$id."'")) {		
		
        $i_stmt->execute();
        $i_stmt->store_result();
        if ($i_stmt->num_rows > 0) {
			$i_stmt->bind_result($i_InvoiceID,
	$i_InvoiceUBMID,
	$i_InvoiceImageID,
	$i_SourceID,$i_invoice_number,$i_account_number,$i_currency,$i_totalDue,$i_balanceForward,$i_lateFee,$i_providerFee,$i_dueDate,$i_invoiceUpdated,$i_vendor_name,$i_vendor_abbreviation,$i_vendor_altname1,$i_vendor_altname2,$i_vendor_altname3,$i_vendor_altname4,$i_vendor_altname5,$i_vendor_type,$i_vendorAddr1,$i_vendorAddr2,$i_vendorCity,$i_vendorState,$i_vendorZip,$i_vendorCountry,$i_vendorPhoneNbr1,$i_vendorPhoneNbr2,$i_vendorPhoneNbr3,$i_vendorFaxNbr1,$i_vendorFaxNbr2,$i_vendorEmail1,$i_vendorEmail2,$i_company_id);
			$i_stmt->fetch();
?>
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Invoice Summary </h2>
							</header>
							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body">
							<style>
							#invoicedetails-gp-a{width:100%;}
							#invoicedetails-gp-a td{width:50%;}
							</style>
							<table id="invoicedetails-gp-a" class="table table-bordered table-striped" style="clear: both">
								<tbody>
									<tr>
										<td><b>Invoice Number:</b> <?php echo $i_invoice_number;?></td>
										<td><b>Total Due:</b> <?php echo $i_totalDue;?></td>
									</tr>
									<tr>
										<td><b>Account Number:</b> <?php echo $i_account_number;?></td>
										<td><b>Balance Forward:</b> <?php echo $i_balanceForward;?></td>
									</tr>
									<tr>
										<td><b>Due Date:</b> <?php echo $i_dueDate;?></td>
										<td><b>Late Fee:</b> <?php echo $i_lateFee;?></td>
									</tr>
									<tr>
										<td><b>Invoice Updated:</b> <?php echo $i_invoiceUpdated;?></td>
										<td><?php if(1==0){ ?><b>Currency:</b> <?php echo $i_currency;?><?php } ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</article>
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Vendor Information </h2>
							</header>
							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body">
							<style>
							#invoicedetails-gp-b{width:100%;}
							#invoicedetails-gp-b td{width:33.33%;}
							</style>
							<table id="invoicedetails-gp-b" class="table table-bordered table-striped" style="clear: both">
								<tbody>
									<tr>
										<td><b>Name:</b> <?php echo $i_vendor_name;?></td>
										<td><b>Type:</b> <?php echo $i_vendor_type;?></td>
										<td><b>Phone Number1:</b> <?php echo $i_vendorPhoneNbr1;?></td>
									</tr>
									<tr>
										<td><b>Abbreviation:</b> <?php echo $i_vendor_abbreviation;?></td>
										<td><b>Address1:</b> <?php echo $i_vendorAddr1;?></td>
										<td><b>Phone Number2:</b> <?php echo $i_vendorPhoneNbr2;?></td>
									</tr>
									<tr>
										<td><b>Altname1:</b> <?php echo $i_vendor_altname1;?></td>
										<td><b>Address2:</b> <?php echo $i_vendorAddr2;?></td>
										<td><b>Phone Number3:</b> <?php echo $i_vendorPhoneNbr3;?></td>
									</tr>
									<tr>
										<td><b>Altname2:</b> <?php echo $i_vendor_altname2;?></td>
										<td><b>City:</b> <?php echo $i_vendorCity;?></td>
										<td><b>Fax Number1:</b> <?php echo $i_vendorFaxNbr1;?></td>
									</tr>
									<tr>
										<td><b>Altname3:</b> <?php echo $i_vendor_altname3;?></td>
										<td><b>State:</b> <?php echo $i_vendorState;?></td>
										<td><b>Fax Number2:</b> <?php echo $i_vendorFaxNbr2;?></td>
									</tr>
									<tr>
										<td><b>Altname4:</b> <?php echo $i_vendor_altname4;?></td>
										<td><b>Zip:</b> <?php echo $i_vendorZip;?></td>
										<td><b>Email1:</b> <?php echo $i_vendorEmail1;?></td>
									</tr>
									<tr>
										<td><b>Altname5	:</b> <?php echo $i_vendor_altname5;?></td>
										<td><b>Country:</b> <?php echo $i_vendorCountry;?></td>
										<td><b>Email2:</b> <?php echo $i_vendorEmail2;?></td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div>
			</article>


		<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
			<style>
			.usage{
			  background-color: #fff;
			  margin: 20px 0;
			  padding-top: 29px;
			}
			.dataTables_paginate ul li {padding:0px !important;}
			.dataTables_paginate ul li a{margin:-1px !important;}
			.dt-buttons{
			float: right !important;
			margin: 0.9% auto !important;
			}
			.dataTables_wrapper .dataTables_length{
			float: right !important;
			margin: 1% 1% !important;
			}
			.dataTables_wrapper .dataTables_filter{
			float: left !important;
			width: auto !important;
			margin: 1% 1% !important;
			text-align:left !important;
			}
			.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
			.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
			.no-padding .dataTables_wrapper table, .no-padding>table{border-bottom:1px solid #cccccc !important;}
			#datatable_fixed_column_idac{border-bottom:1px solid #cccccc !important}
			.space-bottom{margin-bottom: 60px !important;}
			</style>


			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Invoice Details </h2>
							</header>
							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body">

									<table id="datatable_fixed_column_idac" class="table table-striped table-bordered table-hover idacdatatable" width="100%">
										<thead>
											<tr>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Service Group" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter State" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Site Number" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Meter Number(s)" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Account Number" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Rate Name" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Line Item Description" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Sub Service" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Service Location" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Disconnect Date" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter From Date" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter To Date" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Period" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Previous Reading" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Previous Reading Type" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Current Reading" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Current Reading Type" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Meter Multiplier" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Should Accumulate Usage" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Actual Usage" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Unit Of Measure" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Demand" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Btu Factor" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Cost" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Service Cost" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Optimized Cost" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Check Date" />
												</th>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
												<th class="hasinput">
												</th>
			<?php } ?>
											</tr>
											<tr>
												<th>Service Group</th>
												<th>State</th>
												<th>Site Number</th>
												<th>Meter Number(s)</th>
												<th>Account Number</th>
												<th>Rate Name</th>
												<th>Line Item Description</th>
												<th>Sub Service</th>
												<th>Service Location</th>
												<th>Disconnect Date</th>
												<th>From Date</th>
												<th>To Date</th>
												<th>Period</th>
												<th>Previous Reading</th>
												<th>Previous Reading Type</th>
												<th>Current Reading</th>
												<th>Current Reading Type</th>
												<th>Meter Multiplier</th>
												<th>Should Accumulate Usage</th>
												<th>Actual Usage</th>
												<th>Unit Of Measure</th>
												<th>Demand</th>
												<th>Btu Factor</th>
												<th>Cost</th>
												<th>Service Cost</th>
												<th>Optimized Cost</th>
												<th>Check Date</th>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
												<th>Action</th>
			<?php } ?>
											</tr>
										</thead>
										<tbody>
			<?php
			/*if ($idac_stmt = $mysqli->prepare("SELECT DISTINCT a.service_group,a.state,a.site_number,a.meter_number,a.account_number,a.rate_name,a.line_item_description,a.sub_service,a.service_location,date(a.disconnect_date),date(a.from_date),date(a.to_date),a.period,a.previous_reading,a.previous_reading_type,a.current_reading,a.current_reading_type,a.meter_multiplier,a.should_accumulate_usage,a.actual_usage,a.unit_of_measure,a.demand,a.btu_factor,CONCAT('$', FORMAT(a.cost,2)),CONCAT('$', FORMAT(a.service_cost,2)),CONCAT('$', FORMAT(a.optimized_cost,2)),date(a.check_date) FROM invoiceData AS a WHERE ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" company_id = ".$cmpid." and ")." invoice_number='".$id."' ORDER BY a.usage_data, a.site_number, a.account_number, a.meter_number, a.sequence_number")) {*/

			/*if ($idac_stmt = $mysqli->prepare("SELECT DISTINCT c.service_group,c.state,c.site_number,c.meter_number,c.account_number,c.rate_name,c.line_item_description,c.sub_service,c.service_location,date(c.disconnect_date),date(c.from_date),date(c.to_date),c.period,c.previous_reading,c.previous_reading_type,c.current_reading,c.current_reading_type,c.meter_multiplier,c.should_accumulate_usage,c.actual_usage,c.unit_of_measure,c.demand,c.btu_factor,CONCAT('$', FORMAT(c.cost,2)),CONCAT('$', FORMAT(c.service_cost,2)),CONCAT('$', FORMAT(c.optimized_cost,2)),date(c.check_date) FROM (
(SELECT DISTINCT a.service_group,a.state,a.site_number,a.meter_number,a.account_number,a.rate_name,a.line_item_description,a.sub_service,a.service_location,a.disconnect_date,a.from_date,a.to_date,a.period,a.previous_reading,a.previous_reading_type,a.current_reading,a.current_reading_type,a.meter_multiplier,a.should_accumulate_usage,a.actual_usage,a.unit_of_measure,a.demand,a.btu_factor,a.cost,a.service_cost,a.optimized_cost,a.check_date,a.usage_data, a.sequence_number FROM invoiceData AS a WHERE ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.company_id = ".$cmpid." and ")." a.invoice_number='".$id."')
UNION ALL
(SELECT DISTINCT b.service_group,b.state,b.site_number,b.meter_number,b.account_number,b.rate_name,b.line_item_description,b.sub_service,b.service_location,b.disconnect_date,b.from_date,b.to_date,b.period,b.previous_reading,b.previous_reading_type,b.current_reading,b.current_reading_type,b.meter_multiplier,b.should_accumulate_usage,b.actual_usage,b.unit_of_measure,b.demand,b.btu_factor,b.cost,b.service_cost,b.optimized_cost,b.check_date,b.usage_data, b.sequence_number
FROM FTRinvoiceData AS b WHERE ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" b.company_id = ".$cmpid." and ")." b.invoice_number='".$id."') ) c")) {*/
	
/*			if ($idac_stmt = $mysqli->prepare("SELECT
	a.InvoiceID AS invoice,
	b.UsageID AS `usage`,
	c.ServiceTypeName AS service_group, 
	k.SiteState AS state, 
	k.SiteNumber AS site_number, 
	k.SiteName AS site_name,
	j.Allocation AS site_allocation,
	d.MeterNumber AS meter_number, 
	e.AccountNumber AS account_number, 
	IF(f.RateName IS NULL, 'Unspecified', f.RateName) AS rate_name, 
	g.LineItemDescription AS line_item_description, 
	h.LineItemCategory AS sub_service, 
	d.MeterLocation AS service_location, 
	NULL AS disconnect_date, 
	b.ReadBegin AS from_date, 
	b.ReadEnd AS to_date, 
	b.Period AS period, 
	b.ServiceBegin AS previous_reading, 
	IF(b.BeginEst=0,'Actual', 'Estimated') AS previous_reading_type, 
	b.ServiceEnd AS current_reading, 
	IF(b.EndEst=0,'Actual', 'Estimated') AS current_reading_type, 
	b.MeterMultiplier AS meter_multiplier, 
	b.ShouldAccumUsage AS should_accumulate_usage, 
	b.UsageQuantity AS actual_usage, 
	i.UnitName AS unit_of_measure, 
	b.DemandID AS demand, 
	NULL AS btu_factor, 
	CONCAT('$',FORMAT(b.Cost,2)) AS cost, 
	CONCAT('$',FORMAT(b.Cost,2)) AS service_cost, 
	CONCAT('$',FORMAT(b.Cost,2)) AS optimized_cost, 
	a.VendorPaymentDate AS check_date, 
	b.UsageID AS usage_data, 
	b.LineItemID AS sequence_number

FROM
	ubm_newschema4.tblInvoices AS a
LEFT JOIN
	ubm_newschema4.tblInvoiceLineItems AS b
ON 
	b.InvoiceID = a.InvoiceID
LEFT JOIN
	ubm_newschema4.tblServiceTypes AS c
ON 
	b.ServiceTypeID = c.ServiceTypeID
LEFT JOIN
	ubm_newschema4.tblMeters AS d
ON 
	b.MeterID = d.MeterID
LEFT JOIN
	ubm_newschema4.tblAccounts AS e
ON 
	b.AccountID = e.AccountID
LEFT JOIN
	ubm_newschema4.tblRates AS f
ON 
	b.RateID = f.RateID
LEFT JOIN
	ubm_newschema4.tblLineItemDescriptions AS g
ON 
		b.LineItemDescriptionID = g.LineItemDescriptionID
LEFT JOIN
	ubm_newschema4.tblLineItemCategories AS h
ON 
	b.LineItemCategoryID = h.LineItemCategoryID
LEFT JOIN
	ubm_newschema4.tblUnits AS i
ON 
	b.UnitID = i.UnitID
LEFT JOIN
	ubm_newschema4.tblSiteAllocations AS j
ON 
	b.AccountID = j.AccountID AND
	b.ServiceID = j.ServiceID AND
	b.MeterID = j.MeterID AND
	a.ClientID = j.ClientID
LEFT JOIN
	ubm_newschema4.tblSites AS k
ON 
	j.SiteID = k.SiteID

WHERE 
	".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.ClientID = ".$cmpid." and ")."
AND a.InvoiceID = '".$id."'
AND a.DeleteStatus=0
AND b.DeleteStatus=0
AND d.DeleteStatus=0
AND e.DeleteStatus=0
AND k.DeleteStatus=0
			
ORDER BY 	
	a.InvoiceID,
	b.UsageID")) {*/
		
			if ($idac_stmt = $mysqli->prepare("SELECT
	c.ServiceTypeName AS service_group, 
	k.SiteState AS state,
	k.SiteNumber AS site_number,
	d.MeterNumber AS meter_number, 
	e.AccountNumber AS account_number, 
	f.RateName AS rate_name, 
	g.LineItemDescription AS line_item_description, 
	h.LineItemCategory AS sub_service, 
	d.MeterLocation AS service_location, 
	NULL AS disconnect_date,
	a.ServiceBegin AS from_date,
	a.ServiceEnd AS to_date, 
	a.Period AS period,  
	a.ServiceBegin AS previous_reading, 
	a.BeginEst AS previous_reading_type, 
	a.ServiceEnd AS current_reading, 
	a.EndEst AS current_reading_type, 
	a.MeterMultiplier AS meter_multiplier, 
	a.ShouldAccumUsage AS should_accumulate_usage, 
	a.UsageQuantity AS actual_usage, 
	i.UnitName AS unit_of_measure, 
	a.DemandID AS demand, 
	NULL AS btu_factor,
	CONCAT('$',FORMAT(a.Cost,2)) AS cost, 
	CONCAT('$',FORMAT(a.Cost,2)) AS service_cost,
	CONCAT('$',FORMAT(a.Cost,2)) AS optimized_cost,
	b.VendorPaymentDate AS check_date, 
	a.UsageID AS usage_data, 
	a.LineItemID AS sequence_number 
FROM
	ubm_database.tblInvoiceLineItems AS a
LEFT JOIN
	ubm_database.tblServiceTypes AS c
ON 
	a.ServiceTypeID = c.ServiceTypeID
LEFT JOIN
	ubm_database.tblMeters AS d
ON 
	a.MeterID = d.MeterID
LEFT JOIN
	ubm_database.tblAccounts AS e
ON 
	a.AccountID = e.AccountID
LEFT JOIN
	ubm_database.tblRates AS f
ON 
	a.RateID = f.RateID
LEFT JOIN
	ubm_database.tblLineItemDescriptions AS g
ON 
	a.LineItemDescriptionID = g.LineItemDescriptionID
LEFT JOIN
	ubm_database.tblLineItemCategories AS h
ON 
	a.LineItemCategoryID = h.LineItemCategoryID
LEFT JOIN
	ubm_database.tblInvoices AS b
ON 
	a.InvoiceID = b.InvoiceID
LEFT JOIN
	ubm_database.tblUnits AS i
ON 
	a.UnitID = i.UnitID
LEFT JOIN
	ubm_database.tblSiteAllocations AS j
ON
	a.ClientID = j.ClientID 
	AND a.AccountID = j.AccountID 
	AND a.ServiceID = j.ServiceID
	AND a.MeterID = j.MeterID
LEFT JOIN
	ubm_database.tblSites AS k
ON
	j.SiteID=k.SiteID

WHERE 
".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" a.ClientID = ".$cmpid." and ")."
a.InvoiceID='".$id."'		
")) {

					$idac_stmt->execute();
					$idac_stmt->store_result();
					if ($idac_stmt->num_rows > 0) {
						$idac_stmt->bind_result($idac_service_group,$idac_state,$idac_site_number,$idac_meter_number,$idac_account_number,$idac_rate_name,$idac_line_item_description,$idac_sub_service,$idac_service_location,$idac_disconnect_date,$idac_from_date,$idac_to_date,$idac_period,$idac_previous_reading,$idac_previous_reading_type,$idac_current_reading,$idac_current_reading_type,$idac_meter_multiplier,$idac_should_accumulate_usage,$idac_actual_usage,$idac_unit_of_measure,$idac_demand,$idac_btu_factor,$idac_cost,$idac_service_cost,$idac_optimized_cost,$idac_check_date,$idac_usage_data,$idac_sequence_number);
						while($idac_stmt->fetch()) {
			?>
									<tr>
										<td><?php echo $idac_service_group; ?></td>
										<td><?php echo $idac_state; ?></td>
										<td><?php echo $idac_site_number; ?></td>
										<td><?php echo $idac_meter_number; ?></td>
										<td><?php echo $idac_account_number; ?></td>
										<td><?php echo $idac_rate_name; ?></td>
										<td><?php echo $idac_line_item_description; ?></td>
										<td><?php echo $idac_sub_service; ?></td>
										<td><?php echo $idac_service_location; ?></td>
										<td><?php echo $idac_disconnect_date; ?></td>
										<td><?php echo $idac_from_date; ?></td>
										<td><?php echo $idac_to_date; ?></td>
										<td><?php echo $idac_period; ?></td>
										<td><?php echo $idac_previous_reading; ?></td>
										<td><?php echo $idac_previous_reading_type; ?></td>
										<td><?php echo $idac_current_reading; ?></td>
										<td><?php echo $idac_current_reading_type; ?></td>
										<td><?php echo $idac_meter_multiplier; ?></td>
										<td><?php echo $idac_should_accumulate_usage; ?></td>
										<td><?php echo $idac_actual_usage; ?></td>
										<td><?php echo $idac_unit_of_measure; ?></td>
										<td><?php echo $idac_demand; ?></td>
										<td><?php echo $idac_btu_factor; ?></td>
										<td><?php echo $idac_cost; ?></td>
										<td><?php echo $idac_service_cost; ?></td>
										<td><?php echo $idac_optimized_cost; ?></td>
										<td><?php echo $idac_check_date; ?></td>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
										<td>EDIT</td>
			<?php } ?>
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
<script src="/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">

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
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction
	var pagefunction = function() {
		//console.log("cleared");

		/* // DOM Position key index //

			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class

			Also see: http://legacy.datatables.net/usage/features
		*/

		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otableidac = $(".idacdatatable").DataTable( {
				"lengthMenu": [[12, 25,50, -1], [12, 25,50, "All"]],
				"pageLength": 50,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"dom": 'Blfrtip',
				"buttons": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					},
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
				],
				"columnDefs": [
					{
						"targets": [ 7 ],
						"visible": false
					},
					{
						"targets": [ 8 ],
						"visible": false
					},
					{
						"targets": [ 12 ],
						"visible": false
					},
					{
						"targets": [ 13 ],
						"visible": false
					},
					{
						"targets": [ 14 ],
						"visible": false
					},
					{
						"targets": [ 15 ],
						"visible": false
					},
					{
						"targets": [ 16 ],
						"visible": false
					},
					{
						"targets": [ 17 ],
						"visible": false
					},
					{
						"targets": [ 18 ],
						"visible": false
					},
					{
						"targets": [ 21 ],
						"visible": false
					},
					{
						"targets": [ 22 ],
						"visible": false
					},
					{
						"targets": [ 24 ],
						"visible": false
					},
					{
						"targets": [ 25 ],
						"visible": false
					},
					{
						"targets": [ 26 ],
						"visible": false
					}
				],
				"autoWidth" : true
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		$(".idacdatatable thead th input[type=text]").on( 'keyup change', function () {;

			otableidac
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();

		});
	};

	function multifilter(nthis,fieldname,otable)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otableidac
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('.idacdatatable tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}


	// load related plugins
  loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>







					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 space-bottom">
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Invoice </h2>
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
			<?php
$keyname2=$keyname3=$keyname4="";	
$info2=$info3=$info4=null;		
if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) $cmpid=$i_company_id;
$bucket='datahub360-invoices';


if(empty($i_InvoiceUBMID))$i_InvoiceUBMID=$i_InvoiceID;
//$i_InvoiceUBMID,
	//$i_SourceID
	//$i_InvoiceImageID
	if($i_SourceID==2) $invoicename=$i_InvoiceImageID.'.pdf';
	else  $invoicename=$i_InvoiceUBMID.'.pdf';

    $profile = 'default';

    $s3Client = new S3Client([
        'region'      => 'us-west-2',
        'version'     => 'latest',
        'credentials' => [
             'key' => $_ENV['aws_access_key_id'],
             'secret' => $_ENV['aws_secret_access_key']
         ]
    ]);
	if($cmpid==9 || $cmpid==32) $keyname='./sample.pdf';
	else $keyname='./'.$cmpid.'/'.$i_SourceID.'/'.$invoicename;

	$info = $s3Client->doesObjectExist($bucket, $keyname);
	if(!$info)
	{
		echo "<p style='text-align:center;'>Nothing to show!</p>";
	}else{
		

		try {
			// Copy the object and replace its metadata (specifically, the content-type)
			/*$resultcmd = $s3Client->copyObject([
				'Bucket'               => $bucket,
				'CopySource'           => $bucket.'/'.$keyname,
				'Key'                  => $keyname,
				'MetadataDirective'    => 'REPLACE',       // This tells AWS to replace metadata
				'ContentType'          => 'application/pdf' // Set content-type to PDF
			]);*/
		
		if($cmpid==9 || $cmpid==32){
			$resultcmd = $s3Client->copyObject([
				'Key' => 'sample.pdf',
				'Bucket' =>  $bucket,
				'CopySource' => $bucket.'/sample.pdf',
				'MetadataDirective' => 'REPLACE',
				'ContentType'          => 'application/pdf'
			]);			
		}else{ 
			$resultcmd = $s3Client->copyObject([
				'Key' => $cmpid.'/'.$i_SourceID.'/'.$invoicename,
				'Bucket' =>  $bucket,
				'CopySource' => $bucket.'/'.$cmpid.'/'.$i_SourceID.'/'.$invoicename,
				'MetadataDirective' => 'REPLACE',
				'ContentType'          => 'application/pdf'
			]);
		}
			
			// Confirm the result
			//echo "File metadata updated successfully: " . $result['ObjectURL'] . "\n";
		} catch (AwsException $e) {
			// Catch any errors and display the message
			echo "Error updating metadata: " . $e->getMessage() . "\n";
		}		
		
		
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => $bucket,
			'Key'    => $keyname,
				'ContentType'  => 'application/pdf',
			'ContentDisposition'    => 'inline'
		]);
		
		$request = $s3Client->createPresignedRequest($cmd, '+4 minutes');
		$presignedUrl = (string) $request->getUri();
?>
		<embed type="application/pdf" src="<?php echo $presignedUrl; ?>"  height="1000px" style="width:100%">
<?php
	}



/*

if($_SESSION["company_id"]==9 or $_SESSION["company_id"]==32){$keyname=$keyname2="100006079.pdf"; }else
if($cmpid==49 or $cmpid==52){ //temp added
			if ($idac_stmt = $mysqli->prepare("SELECT DISTINCT *  FROM company WHERE (ubm_type='Cass' or ubmarchive_type='Cass') and (company_id=49 or company_id=52) LIMIT 1")) {

					$idac_stmt->execute();
					$idac_stmt->store_result();
					if ($idac_stmt->num_rows > 0) {
						$keyname='./52/cass/'.$id.'.pdf';
						$keyname2='./52/cass/0'.$id.'.pdf';
						$keyname3='./49/cass/'.$id.'.pdf';
						$keyname4='./49/cass/0'.$id.'.pdf';
					} else $keyname=$keyname3=$keyname4='./'.$cmpid.'/'.$id.'.pdf';
				}else $keyname=$keyname3=$keyname4='./'.$cmpid.'/'.$id.'.pdf';
}else{
			if ($idac_stmt = $mysqli->prepare("SELECT DISTINCT *  FROM company WHERE (ubm_type='Cass' or ubmarchive_type='Cass') and company_id=".$cmpid." LIMIT 1")) {

					$idac_stmt->execute();
					$idac_stmt->store_result();
					if ($idac_stmt->num_rows > 0) {
						$keyname='./'.$cmpid.'/cass/'.$id.'.pdf';
						$keyname2='./'.$cmpid.'/cass/0'.$id.'.pdf';
					} else $keyname='./'.$cmpid.'/'.$id.'.pdf';
				}else $keyname='./'.$cmpid.'/'.$id.'.pdf';
}

    $profile = 'default';

    $s3Client = new S3Client([
        'region'      => 'us-west-2',
        'version'     => 'latest',
        'credentials' => [
             'key' => $_ENV['aws_access_key_id'],
             'secret' => $_ENV['aws_secret_access_key']
         ]
    ]);
if($cmpid==49 or $cmpid==52){ //temp added

	if($cmpid==52){
		$info2=$info3=$info4=null;
		$info = $s3Client->doesObjectExist($bucket, $keyname);
		if(!$info)
		{
			$info2 = $s3Client->doesObjectExist($bucket, $keyname2);
			if($info2)
			{
				$keyname=$keyname2;
			}else{
				$info3 = $s3Client->doesObjectExist($bucket, $keyname3);
				if($info3)
				{
					$keyname=$keyname3;
				}else{
					$info4 = $s3Client->doesObjectExist($bucket, $keyname4);
					if($info4)
					{
						$keyname=$keyname4;
					}else echo "<p style='text-align:center;'>Nothing to show!</p>";
				}
			}
		}
	}else if($cmpid==49){
		$info2=$info3=$info4=null;
		$info = $s3Client->doesObjectExist($bucket, $keyname4);
		if(!$info)
		{
			$info2 = $s3Client->doesObjectExist($bucket, $keyname3);
			if($info2)
			{
				$keyname=$keyname3;
			}else{
				$info3 = $s3Client->doesObjectExist($bucket, $keyname2);
				if($info3)
				{
					$keyname=$keyname2;
				}else{
					$info4 = $s3Client->doesObjectExist($bucket, $keyname);
					if($info4)
					{

					}else echo "<p style='text-align:center;'>Nothing to show!</p>";
				}
			}
		}else $keyname=$keyname4;
	}

	if($info or $info2 or $info3 or $info4){
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => $bucket,
			'Key'    => $keyname,
				'ContentType'  => 'application/pdf',
			'ContentDisposition'    => 'inline'
		]);
	}

}else{
	$info2=null;
	$info = $s3Client->doesObjectExist($bucket, $keyname);
	if(!$info)
	{   if(empty($keyname2)){$info2=null; echo "<p style='text-align:center;'>Nothing to show!</p>"; } 
		else{
			$info2 = $s3Client->doesObjectExist($bucket, $keyname2);
			if($info2)
			{
				$keyname=$keyname2;
			}else echo "<p style='text-align:center;'>Nothing to show!</p>";
		}
	}

	if($info or $info2){
		$cmd = $s3Client->getCommand('GetObject', [
			'Bucket' => $bucket,
			'Key'    => $keyname,
				'ContentType'  => 'application/pdf',
			'ContentDisposition'    => 'inline'
		]);
	}
}


}*/
/**
  * Create a link to a S3 object from a bucket. If expiration is not empty, then it is used to create
  * a signed URL
  *
  * @param  string     $object The object name (full path)
  * @param  string     $bucket The bucket name
  * @param  string|int $expiration The Unix timestamp to expire at or a string that can be evaluated by strtotime
  * @throws InvalidDomainNameException
  * @return string
  */
 function getpresignedurl123($object, $bucket = '', $expiration = '')
 {
     $bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
     if (empty($bucket)) {
         throw new InvalidDomainNameException('An empty bucket name was given');
     }
     if ($expiration) {
         $command = $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $object]);
         return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
     } else {
         return $this->client->getObjectUrl($bucket, $object);
     }
 }
?>






								</div>
								<!-- end widget content -->

							</div>
							<!-- end widget div -->

						</div>
						<!-- end widget -->

					</article>


<?php
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
		}else
			die('Error Occured! Please try after sometime.');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}//else
		//die('Wrong parameters provided');

}else echo "Error Occured! Please try after sometime.";
?>
