<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,63)==false) die("<h5 style='padding-top:30px;' align='center'>Permission Denied! Please contact Vervantis.</h5>");
if(!isset($_SESSION["group_id"]))
	die("<h5 style='padding-top:30px;' align='center'>Access Restricted</h5>");

$user_one=$_SESSION['user_id'];
$c_id=$_SESSION['company_id'];



if(isset($_GET['reid']) and $_GET['reid'] != "" and $_GET['reid'] > 0)
	$reid=$_GET['reid'];
else
	die("<h5 style='padding-top:30px;' align='center'>Wrong parameters provided</h5>");

//$user_one=29;
?>
<style>
.sitetable{
border-spacing:5px !important;
border-collapse:unset !important;
}
#wid-id---1 table{    
	margin: 0 auto;
    height: 322px;
    border-collapse: separate;
    border-spacing: 11px !important;
    width: 718px;}
</style>
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="movere_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
		</div>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

		</div>
	</article>
</div>

<!-- row -->
<div class="row siterow">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id---1" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Resolved Exceptions Details </h2>

			</header>
			<div class="row">
		<?php
		$address=array();
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		//$resolvedexcepsql='SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e,user up where e.`Customer ID`=up.company_id and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
		$resolvedexcepsql='SELECT
	a.ClientName,
	a.ClientID,
	a.EntityID,
	a.ExceptionID,
	a.Priority,
	a.VendorName,
	a.AccountNumber,
	a.SiteNumber,
	a.InvoiceID,
	a.ServiceType,
	a.ExceptionType,
	a.ExceptionDescription,
	a.Resolution,
	a.CreatedDate,
	a.InvoiceAmount,
	a.EnteredDate,
	a.NotesDescription,
	a.ModifiedDate
FROM
	ubm_exceptions.mapExceptions AS a WHERE a.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
	}else{
		//$resolvedexcepsql='SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e,user up where e.`Customer ID`=up.company_id and up.user_id= "'.$user_one.'" and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
		$resolvedexcepsql='SELECT
	a.ClientName,
	a.ClientID,
	a.EntityID,
	a.ExceptionID,
	a.Priority,
	a.VendorName,
	a.AccountNumber,
	a.SiteNumber,
	a.InvoiceID,
	a.ServiceType,
	a.ExceptionType,
	a.ExceptionDescription,
	a.Resolution,
	a.CreatedDate,
	a.InvoiceAmount,
	a.EnteredDate,
	a.NotesDescription,
	a.ModifiedDate
		FROM ubm_exceptions.mapExceptions AS a WHERE a.ClientID= "'.$c_id.'"  and a.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
	}

	if ($stmte = $mysqli->prepare($resolvedexcepsql)) { 

//('SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e,user up where e.`Customer ID`=up.company_id and up.id= "'.$user_one.'" and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1')) {

        $stmte->execute();
        $stmte->store_result();
        if ($stmte->num_rows > 0) {
			//$stmte->bind_result($ee_id,$ee_ubm,$ee_custid,$ee_cust,$ee_custdesc,$ee_res,$ee_vendid,$ee_vendname,$ee_acc,$ee_check,$ee_checkamt,$ee_checkdate,$ee_duedate,$ee_docid,$ee_service,$ee_site,$ee_sitename,$ee_sitestate,$ee_error,$ee_errmsg,$ee_estdate);
			$stmte->bind_result($ee_clientname,$ee_clientid,$ee_entityid,$ee_exceptionid,$ee_priority,$ee_vendorname,$ee_accountnumber,$ee_sitenumber,$ee_invoiceid,$ee_servicetype,$ee_exceptiontype,$ee_exceptiondesc,$ee_resolution,$ee_createddate,$ee_invoiceamount,$ee_entereddate,$ee_notesdesc,$ee_modifieddate);
			$stmte->fetch();
				//$ts=$ee_id.rand(650,900);
				?>
					<table>
<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
						<tr><th>Client #</th><th> : </th><td><?php echo $ee_clientid; ?></td><th>Client Name</th><th> : </th><td><?php echo $ee_clientname; ?></td></tr>
<?php } ?>
						<tr><th>Entity #</th><th> : </th><td><?php echo $ee_entityid; ?></td><th>Exception #</th><th> : </th><td><?php echo $ee_exceptionid; ?></td></tr>
						<tr><th>Priority</th><th> : </th><td><?php echo $ee_priority; ?></td><th>Vendor Name</th><th> : </th><td><?php echo $ee_vendorname; ?></td></tr>
						<tr><th>Account #</th><th> : </th><td><?php echo $ee_accountnumber; ?></td><th>Site #</th><th> : </th><td><?php echo $ee_sitenumber; ?></td></tr>
						<tr><th>Invoice #</th><th> : </th><td><?php echo $ee_invoiceid; ?></td><th>Service Type</th><th> : </th><td><?php echo $ee_servicetype; ?></td></tr>
						<tr><th>Exception Type</th><th> : </th><td><?php echo $ee_exceptiontype; ?></td><th>Exception Resolution</th><th> : </th><td><?php echo $ee_resolution; ?></td></tr>
						<tr><th>Created Date</th><th> : </th><td><?php echo $ee_createddate; ?></td><th>Invoice Amount</th><th> : </th><td><?php echo $ee_invoiceamount; ?></td></tr>
						<tr><th>Entered Date</th><th> : </th><td><?php echo $ee_entereddate; ?></td><th>Notes Description</th><th> : </th><td><?php echo $ee_notesdesc; ?></td></tr>
						<tr><th>Modified Date</th><th> : </th><td><?php echo $ee_modifieddate; ?></td><th></th><th> </th><td></td></tr>
					</table>
				<?php
		}else
			die('Wrong parameters provided');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}//else
		//die('Error Occured! Please try after sometime.');
		?>
			</div>
		</div>
	</article>
</div>