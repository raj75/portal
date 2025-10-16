<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted."); 

//print_r($_POST);
//die();

$user_one=$_SESSION["user_id"];

//unset($_SESSION['f_invoice_audit_chart_qry']);

//print_r($_POST);

// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'InvoiceID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

function isDate($dbdate) { 
	$timestamp = strtotime($dbdate); 
		
	if ((int) $timestamp > 0) { 
		return date('m/d/Y',$timestamp);
	} else { 
		return ""; 
	} 
} 

$columns = array(
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorID',     'dt' => 'VendorID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return "<span class='dt_vendorname'>$d</span>";} ),
   array( 'db' => 'AccountID',     'dt' => 'AccountID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountNumber',     'dt' => 'AccountNumber',  'formatter' => function( $d, $row ) {return "<span class='dt_accountno'>$d</span>";} ),
   array( 'db' => 'SiteID',     'dt' => 'SiteID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteNumber',     'dt' => 'SiteNumber', 'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteName',     'dt' => 'SiteName', 'formatter' => function( $d, $row ) {return $d;}),
   array( 'db' => 'InvoiceID',     'dt' => 'InvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'TotalDue',     'dt' => 'TotalDue',  'formatter' => function( $d, $row ) {return "<span class='dt_totaldue'>$$d</span>";} ),
   array( 'db' => 'InvoiceBegin',     'dt' => 'InvoiceBegin',  'formatter' => function( $d, $row ) {return "<span class='dt_invoicebegin'>".isDate($d)."</span>";} ),
   array( 'db' => 'InvoiceEnd',     'dt' => 'InvoiceEnd',  'formatter' => function( $d, $row ) {return "<span class='dt_invoiceend'>".isDate($d)."</span>";} ),
   array( 'db' => 'Period',     'dt' => 'Period',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'InvoiceServiceDays',     'dt' => 'InvoiceServiceDays',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'ReceiptDate',     'dt' => 'ReceiptDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'EntryDate',     'dt' => 'EntryDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'ConsolidationNotificationDate',     'dt' => 'ConsolidationNotificationDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'ConsolidationReceivedDate',     'dt' => 'ConsolidationReceivedDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'VendorPaymentDate',     'dt' => 'VendorPaymentDate', 'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'VendorPaymentClearDate',     'dt' => 'VendorPaymentClearDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),   
   array( 'db' => 'CheckVoidDate',     'dt' => 'CheckVoidDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   
);




// SQL server connection information
$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => DATABASE,
	'host' => HOST
);

$filter_qry = '';
$filter_subqry = '';
$order_by = $_POST['my_o_b'];
 
// get filters datatable

// ---------account id filter--------------
$account_id = $_POST['columns'][3]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
	$filter_subqry .= " AND AccountID IN ($account_ids) ";
}

$_POST['columns'][3]['search']['value'] = "";
$_POST['columns'][3]['searchable'] = false;

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][1]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND a.VendorID IN ($vendor_ids) ";
	$filter_subqry .= " AND VendorID IN ($vendor_ids) ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;

if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND a.ClientID IN ($client_id) ";
		$filter_subqry .= " AND ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND a.ClientID = 9 ";
		$filter_subqry .= " AND ClientID = 9 ";
		//$filter_qry .= " AND a.ClientID = 10 ";
		//$filter_subqry .= " AND ClientID = 10 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	
	//$company_id=10;
	
	// company id is client id
	////$filter_qry .= " AND a.ClientID = '$company_id' ";
	////$filter_subqry .= " AND ClientID = '$company_id' ";
	
	$filter_subqry .= " AND ClientID = 10 ";
	$filter_qry .= " AND d.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}

/*
// ---------country filter--------------
$country_ar= $_POST['columns'][15]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND h.SiteCountry IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][15]['search']['value'] = "";
$_POST['columns'][15]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][13]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND h.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][13]['search']['value'] = "";
$_POST['columns'][13]['searchable'] = false;

*/

// ---------site filter--------------
$site_id = $_POST['columns'][5]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND b.SiteID IN ($site_id) ";
	$filter_subqry .= " AND SiteID IN ($site_id) ";
}

$_POST['columns'][5]['search']['value'] = "";
$_POST['columns'][5]['searchable'] = false;


// ---------invoice date start --------------

$invoice_date_start = $_POST['columns'][10]['search']['value'];

if ( date('m/d/Y', strtotime($invoice_date_start)) === $invoice_date_start ) {

	$invoice_date_start = date("Y-m-d",strtotime($invoice_date_start));
	
	$filter_qry .= " AND a.InvoiceBegin >= '$invoice_date_start' ";
} else {
	$invoice_date_start = date("Y-m-d",strtotime(' - 1 months'));
	$filter_qry .= " AND a.InvoiceBegin >= '$invoice_date_start' ";
}

$_POST['columns'][10]['search']['value'] = "";
$_POST['columns'][10]['searchable'] = false;

// ---------invoice date end --------------
$invoice_date_end = $_POST['columns'][11]['search']['value'];

if ( date('m/d/Y', strtotime($invoice_date_end)) === $invoice_date_end ) {

	$invoice_date_end = date("Y-m-d",strtotime($invoice_date_end));
	
	$filter_qry .= " AND a.InvoiceEnd <= '$invoice_date_end' ";
} else {	
	$invoice_date_end = date("Y-m-d");
	$filter_qry .= " AND a.InvoiceEnd <= '$invoice_date_end' ";
}

$_POST['columns'][11]['search']['value'] = "";
$_POST['columns'][11]['searchable'] = false;


/*
$sql = "SELECT DISTINCT
	a.ClientID,
	f.ServiceTypeName,
	h.SiteState,
	g.VendorName,
	g.VendorAddress1,
	g.VendorAddress2,
	g.VendorAddress3,
	g.VendorCity,
	g.VendorState,
	g.VendorZip,
	g.VendorCountry,
	g.VendorPhone,
	g.VendorFax,
	g.VendorEmail,
	g.VendorStatus
FROM
	tblAccounts AS a
	INNER JOIN
	tblServices AS b
	ON
		a.AccountID = b.AccountID
	INNER JOIN
	tblMeters AS c
	ON
		b.ServiceID = c.ServiceID
	INNER JOIN
	tblSiteAllocations AS d
	ON
		c.MeterID = d.MeterID
	INNER JOIN
	tblServiceTypes AS f
	ON
		b.ServiceTypeID = f.ServiceTypeID
	INNER JOIN
	tblVendors AS g
	ON
		a.VendorID = g.VendorID
	INNER JOIN
	tblSites AS h
	ON
		d.SiteID = h.SiteID
WHERE
	1 = 1 AND	f.ServiceTypeID IN (1,2,3) AND a.ClientID IN (38) AND h.SiteState IN ('AZ', 'AL') AND g.VendorStatus=1
ORDER BY
	f.ServiceTypeID,
	h.SiteState ";
*/
	
$sql = "SELECT
			a.ClientID,
			a.VendorID,
			c.VendorName,
			a.AccountID,
			d.AccountNumber,
			b.SiteID,
			e.SiteNumber,
			e.SiteName,
			a.InvoiceID,
			a.TotalDue,
			a.InvoiceBegin,
			a.InvoiceEnd,
			a.Period,
			a.InvoiceServiceDays,
			a.ReceiptDate,
			a.EntryDate,
			a.ConsolidationNotificationDate,
			a.ConsolidationReceivedDate,
			a.VendorPaymentDate,
			a.VendorPaymentClearDate,
			a.CheckVoidDate
		FROM
			ubm_database.tblInvoices AS a
		INNER JOIN
			(SELECT DISTINCT ClientID, AccountID, SiteID FROM ubm_database.tblSiteAllocations where 1=1 ".$filter_subqry." ) b
		ON
			a.ClientID = b.ClientID AND a.AccountID = b.AccountID
		INNER JOIN
			ubm_database.tblVendors c
		ON
			a.VendorID = c.VendorID
		INNER JOIN
			ubm_database.tblAccounts d
		ON
			a.AccountID = d.AccountID
		INNER JOIN
			ubm_database.tblSites e
		ON
			b.SiteID = e.SiteID	
		WHERE
			1 = 1
			".$filter_qry."
			AND a.DeletedInvoice = 0 
			
			
		ORDER BY
			a.InvoiceID ASC
			
			
			";
			
			//AND	f.ServiceTypeID IN (1,2,3) AND a.ClientID IN (38) AND h.SiteState IN ('AZ', 'AL') AND g.VendorStatus=1

//echo $sql;
//die();

$_SESSION['f_invoice_audit_chart_qry'] = $sql;

$table = <<<EOT
 (
    $sql
 ) temp
EOT;
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require_once '../includes/ssp.class.custom.php'; 

//$whereall = "deleted = 0";

/*
echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);
*/

$json1 = json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);

//echo "<br>filter_qry_ajax==".$filter_qry."<br>";

$json2 = json_encode( array( 'qry'=>$sql, 'f_q'=>urlencode(base64_encode($filter_qry)), 'f_sq'=>urlencode(base64_encode($filter_subqry)), 'o_b'=>urlencode(base64_encode($order_by)) ) );

echo json_encode(array_merge(json_decode($json1, true),json_decode($json2, true)));
