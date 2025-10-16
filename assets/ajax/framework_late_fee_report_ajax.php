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

$_SESSION["group_id"] = 2;
/*
	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sql = "SELECT DISTINCT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."'";
	}else{
		$sql = "SELECT DISTINCT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id";
	}
*/

		

// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'InvoiceID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'InvoiceID',     'dt' => 'InvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'EntryDate',     'dt' => 'EntryDate',  'formatter' => function( $d, $row ) {return date('m/d/Y',strtotime($d));} ),
   array( 'db' => 'DueDate',     'dt' => 'DueDate',  'formatter' => function( $d, $row ) {return date('m/d/Y',strtotime($d));} ),
   array( 'db' => 'Site_Number',     'dt' => 'Site_Number',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Country',     'dt' => 'Site_Country',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_State',     'dt' => 'Site_State',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Vendor_Name',     'dt' => 'Vendor_Name',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Account_Number',     'dt' => 'Account_Number',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Description',     'dt' => 'Description',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Cost',     'dt' => 'Cost',  'formatter' => function( $d, $row ) {return "$".number_format($d, 2, '.', ',');} ),
   
);



/*

*/

// SQL server connection information
$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => DATABASE,
	'host' => HOST
);



$filter_qry = '';
// get filters datatable

// ---------account id filter--------------
$account_id = $_POST['columns'][8]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
}

$_POST['columns'][8]['search']['value'] = "";
$_POST['columns'][8]['searchable'] = false;

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][7]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND c.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][7]['search']['value'] = "";
$_POST['columns'][7]['searchable'] = false;

// ---------due date --> entry date filter--------------
//john: instead of due date, can you use entered date?
$due_date = $_POST['columns'][2]['search']['value'];

if ( isset($due_date) AND strlen($due_date) > 1 ) {
	
	$due_date_arr = explode("~",$due_date);
	$due_date_start = date("Y-m-d",strtotime($due_date_arr[0]));
	$due_date_end = date("Y-m-d",strtotime($due_date_arr[1])); 
	
	//$filter_qry .= " AND i.DueDate between '$due_date_start' AND '$due_date_end' ";
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND e.EntryDate >= '$due_date_start' AND e.EntryDate <= '$due_date_end' ";
} else {
	// if due date is not selected then by default last one month data will show
	$due_date_start = date("Y-m-d", strtotime(" - 1 month"));
	$due_date_end = date("Y-m-d");
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND e.EntryDate >= '$due_date_start' AND e.EntryDate <= '$due_date_end' ";
}

$_POST['columns'][2]['search']['value'] = "";
$_POST['columns'][2]['searchable'] = false;


if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND e.ClientID IN ($client_id) ";
		$filter_subqry = " AND ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND e.ClientID = 9 ";
		$filter_subqry = " AND ClientID = 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND i.ClientID = '$company_id' ";
	$filter_subqry = " AND ClientID = '$company_id' ";
	//$filter_qry .= " AND e.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}

// ---------country filter--------------
$country_ar= $_POST['columns'][5]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND g.SiteCountry IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][5]['search']['value'] = "";
$_POST['columns'][5]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][6]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND g.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][6]['search']['value'] = "";
$_POST['columns'][6]['searchable'] = false;

// ---------site filter--------------
$site_id = $_POST['columns'][4]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND g.SiteID IN ($site_id) ";
}

$_POST['columns'][4]['search']['value'] = "";
$_POST['columns'][4]['searchable'] = false;

//$sql = "SELECT i.InvoiceID,i.VendorID,v.VendorName,i.AccountID,a.AccountNumber,i.DueDate FROM NewSchema9.tblInvoices i INNER JOIN NewSchema9.tblVendors v ON i.VendorID=v.VendorID INNER JOIN NewSchema9.tblAccounts a ON i.AccountID = a.AccountID";



/*
SELECT
	a.ClientID,
	a.InvoiceID,
	e.EntryDate,
	e.DueDate,
	GROUP_CONCAT(DISTINCT g.SiteNumber) AS `Site_Number`,
	GROUP_CONCAT(DISTINCT g.SiteCountry) AS `Site_Country`,
	GROUP_CONCAT(DISTINCT g.SiteState) AS `Site_State`,
	UPPER(d.VendorName) AS `Vendor_Name`,
	c.AccountNumber AS `Account_Number`,
	b.LineItemDescription AS Description,
	a.Cost
FROM
	tblInvoices AS e
LEFT JOIN
	tblInvoiceLineItems AS a
ON
	a.InvoiceID = e.InvoiceID
LEFT JOIN
	tblLineItemDescriptions AS b
ON
	a.LineItemDescriptionID = b.LineItemDescriptionID
LEFT JOIN
	tblVendors AS d
ON
	e.VendorID = d.VendorID
LEFT JOIN
	tblAccounts c
ON
	e.AccountID = c.AccountID
LEFT JOIN
	(SELECT DISTINCT ClientID, AccountID, SiteID FROM tblSiteAllocations WHERE ClientID=10) f
ON
	a.ClientID = f.ClientID AND a.AccountID = f.AccountID
LEFT JOIN
	tblSites g
ON
	f.SiteID = g.SiteID	
	
WHERE
	(
		b.LineItemDescription LIKE “%Deposit%” OR
		b.LineItemDescription LIKE “%deposit%” OR
		b.LineItemDescription LIKE “%Late %” OR
		b.LineItemDescription LIKE “%late %”
	)
	AND a.DeleteStatus = 0
	AND a.AccountID IN ( 7646 )
	AND c.VendorID IN ( 2017 )
	AND e.ClientID IN ( 10 )
 	AND g.SiteCountry IN (‘US’)
  AND g.SiteState IN (‘AL’)
 	AND g.SiteID IN ( 36031 )
 	AND e.EntryDate >= ‘2024-01-01’
 	AND e.EntryDate <= ‘2024-09-01’
GROUP BY
	a.InvoiceID, a.LineitemID
*/
	
$sql = "SELECT
			a.ClientID,
			a.InvoiceID,
			e.EntryDate,
			e.DueDate,
			GROUP_CONCAT(DISTINCT g.SiteNumber) AS `Site_Number`,
			GROUP_CONCAT(DISTINCT g.SiteCountry) AS `Site_Country`,
			GROUP_CONCAT(DISTINCT g.SiteState) AS `Site_State`,
			UPPER(d.VendorName) AS `Vendor_Name`,
			c.AccountNumber AS `Account_Number`,
			b.LineItemDescription AS Description,
			a.Cost
		FROM
			ubm_database.tblInvoices AS e
		LEFT JOIN
			ubm_database.tblInvoiceLineItems AS a
		ON
			a.InvoiceID = e.InvoiceID
		LEFT JOIN
			ubm_database.tblLineItemDescriptions AS b
		ON
			a.LineItemDescriptionID = b.LineItemDescriptionID
		LEFT JOIN
			ubm_database.tblVendors AS d
		ON
			e.VendorID = d.VendorID
		LEFT JOIN
			ubm_database.tblAccounts c
		ON
			e.AccountID = c.AccountID
		LEFT JOIN
			(SELECT DISTINCT ClientID, AccountID, SiteID FROM ubm_database.tblSiteAllocations WHERE 1=1 $filter_subqry) f
		ON
			a.ClientID = f.ClientID AND a.AccountID = f.AccountID
		LEFT JOIN
			ubm_database.tblSites g
		ON
			f.SiteID = g.SiteID	
			
		WHERE
		(
			b.LineItemDescription LIKE '%Deposit%' OR
			b.LineItemDescription LIKE '%deposit%' OR
			b.LineItemDescription LIKE '%Late %' OR
			b.LineItemDescription LIKE '%late %'
		)
		AND a.DeleteStatus = 0
		$filter_qry
			
		GROUP BY
			a.InvoiceID, a.LineitemID
			
			";
					
			/*
			i.DueDate BETWEEN '2024-01-01' AND '2024-01-30'
				AND i.ClientID =10
			*/

//echo $sql;
//die();

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

$json2 = json_encode(array('qry'=>$sql));

echo json_encode(array_merge(json_decode($json1, true),json_decode($json2, true)));
