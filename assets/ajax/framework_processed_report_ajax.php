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

/*
$columns = array(
   array( 'db' => 'InvoiceID',     'dt' => 'InvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorID',     'dt' => 'VendorID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountID',     'dt' => 'AccountID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountNumber',     'dt' => 'AccountNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'DueDate',     'dt' => 'DueDate',  'formatter' => function( $d, $row ) {return $d;} ),
   
);
*/

function isDate($dbdate) { 
	$timestamp = strtotime($dbdate); 
		
	if ((int) $timestamp > 0) { 
		return date('m/d/Y',$timestamp);
	} else { 
		return ""; 
	} 
} 

$columns = array(
   array( 'db' => 'VendorID',     'dt' => 'VendorID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountID',     'dt' => 'AccountID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountNumber',     'dt' => 'AccountNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'InvoiceID',     'dt' => 'InvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'InvoiceImageID',     'dt' => 'InvoiceImageID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorInvoiceNumber',     'dt' => 'VendorInvoiceNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'TotalDue',     'dt' => 'TotalDue',  
		  'formatter' => function( $d, $row ) { 
							/*return $d;*/ 
							return "$".number_format($d, 2, '.', ',');
						} ),
   array( 'db' => 'LateFee',     'dt' => 'LateFee',   
		  'formatter' => function( $d, $row ) { 
							/*return $d;*/ 
							return "$".number_format($d, 2, '.', ',');
						} ),
   array( 'db' => 'Period',     'dt' => 'Period',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'DueDate',     'dt' => 'DueDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'InvoiceDate',     'dt' => 'InvoiceDate',  'formatter' => function( $d, $row ) { return isDate($d); /*return $d;   return date('m/d/Y',strtotime($d)); */ } ),
   array( 'db' => 'ReceiptDate',     'dt' => 'ReceiptDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'EntryDate',     'dt' => 'EntryDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'ConsolidationNotificationDate',     'dt' => 'ConsolidationNotificationDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'ConsolidationReceivedDate',     'dt' => 'ConsolidationReceivedDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'VendorPaymentDate',     'dt' => 'VendorPaymentDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'VendorPaymentClearDate',     'dt' => 'VendorPaymentClearDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'InvoiceDeletedDate',     'dt' => 'InvoiceDeletedDate',  'formatter' => function( $d, $row ) {return isDate($d);} ),
   array( 'db' => 'InvoiceUpdated',     'dt' => 'InvoiceUpdated',  
		  'formatter' => function( $d, $row ) {
							if ((int) $d == 1) {
								return 'Yes';
							} else {
								return 'No';
							}
						} ),
   array( 'db' => 'FinalBill',     'dt' => 'FinalBill',  'formatter' => function( $d, $row ) {return $d;} ),   
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Country',     'dt' => 'Country',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'State',     'dt' => 'State',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteNumber',     'dt' => 'SiteNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteName',     'dt' => 'SiteName',  'formatter' => function( $d, $row ) {return $d;} ),
   
);



/*
$columns = array(
   array( 'db' => 'MasterID',        'dt' => 'MasterID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_contract_id">'.$d.'</a>';
														} ),
   array( 'db' => 'vendor_name', 		 'dt' => 'vendor_name', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Status', 		 'dt' => 'Status', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Start Date', 		 'dt' => 'Start_Date', 'formatter' => 
														function( $d, $row ) {
															if ((int) $d < 1) {
																$data = "01/01/2000";
															} else {
																$data = @date("m/d/Y",strtotime($d));
															}
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_start_date">'.$data.'</a>';
														} ),
   array( 'db' => 'End Date', 		 'dt' => 'End_Date', 'formatter' => 
														function( $d, $row ) {
															if ((int) $d < 1) {
																$data = date("m/d/Y");
															} else {
																$data = @date("m/d/Y",strtotime($d));
															}
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_end_date">'.$data.'</a>';
														} ),
   array( 'db' => 'Version', 		 'dt' => 'Version', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Reviewed By', 		 'dt' => 'Reviewed_By', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Notes', 		 'dt' => 'Notes', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   
);

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$columns[] = array( 'db' => 'ClientID', 		 'dt' => 'ClientID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} );
}

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$columns[] = array( 'db' => 'VendorID', 		 'dt' => 'VendorID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} );
}
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
$account_id = $_POST['columns'][3]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND i.AccountID IN ($account_ids) ";
}

$_POST['columns'][3]['search']['value'] = "";
$_POST['columns'][3]['searchable'] = false;

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][2]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][2]['search']['value'] = "";
$_POST['columns'][2]['searchable'] = false;

// ---------due date --> entry date filter--------------
//john: instead of due date, can you use entered date?
$due_date = $_POST['columns'][11]['search']['value'];

if ( isset($due_date) AND strlen($due_date) > 1 ) {
	
	$due_date_arr = explode("~",$due_date);
	$due_date_start = date("Y-m-d",strtotime($due_date_arr[0]));
	$due_date_end = date("Y-m-d",strtotime($due_date_arr[1])); 
	
	//$filter_qry .= " AND i.DueDate between '$due_date_start' AND '$due_date_end' ";
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND i.EntryDate >= '$due_date_start' AND i.EntryDate <= '$due_date_end' ";
} else {
	// if due date is not selected then by default last one month data will show
	$due_date_start = date("Y-m-d", strtotime(" - 1 month"));
	$due_date_end = date("Y-m-d");
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND i.EntryDate >= '$due_date_start' AND i.EntryDate <= '$due_date_end' ";
}

$_POST['columns'][11]['search']['value'] = "";
$_POST['columns'][11]['searchable'] = false;


if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND i.ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND i.ClientID = 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND i.ClientID = '$company_id' ";
	//$filter_qry .= " AND i.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}

// ---------country filter--------------
$country_ar= $_POST['columns'][7]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND c.SiteCountry IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][7]['search']['value'] = "";
$_POST['columns'][7]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][6]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND c.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][6]['search']['value'] = "";
$_POST['columns'][6]['searchable'] = false;

// ---------site filter--------------
$site_id = $_POST['columns'][5]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND c.SiteID IN ($site_id) ";
}

$_POST['columns'][5]['search']['value'] = "";
$_POST['columns'][5]['searchable'] = false;

//$sql = "SELECT i.InvoiceID,i.VendorID,v.VendorName,i.AccountID,a.AccountNumber,i.DueDate FROM NewSchema9.tblInvoices i INNER JOIN NewSchema9.tblVendors v ON i.VendorID=v.VendorID INNER JOIN NewSchema9.tblAccounts a ON i.AccountID = a.AccountID";

/*
$sql = "SELECT i.VendorID,
			v.VendorName,
			i.AccountID,
			a.AccountNumber,
			i.InvoiceID,
			i.InvoiceImageID,
			i.VendorInvoiceNumber,
			i.TotalDue,
			i.LateFee,
			i.Period,
			i.DueDate,
			i.InvoiceDate,
			i.ReceiptDate,
			i.EntryDate,
			i.ConsolidationNotificationDate,
			i.ConsolidationReceivedDate,
			i.VendorPaymentDate,
			i.VendorPaymentClearDate,
			i.InvoiceDeletedDate,
			i.InvoiceUpdated,
			i.FinalBill,
			i.ClientID
			FROM 
			NewSchema9.tblInvoices i 
			INNER JOIN NewSchema9.tblVendors v ON i.VendorID=v.VendorID 
			INNER JOIN NewSchema9.tblAccounts a ON i.AccountID = a.AccountID 
			WHERE 1=1 $filter_qry ";
*/
	
$sql = "SELECT
			i.ClientID,
			i.VendorID,
			v.VendorName,
			i.AccountID,
			a.AccountNumber,
			i.InvoiceID,
			i.InvoiceImageID,
			i.VendorInvoiceNumber,
			i.TotalDue,
			i.LateFee,
			i.Period,
			i.DueDate,
			i.InvoiceDate,
			i.ReceiptDate,
			i.EntryDate,
			i.ConsolidationNotificationDate,
			i.ConsolidationReceivedDate,
			i.VendorPaymentDate,
			i.VendorPaymentClearDate,
			i.InvoiceDeletedDate,
			i.InvoiceUpdated,
			i.FinalBill,
			GROUP_CONCAT(DISTINCT c.SiteNumber) AS SiteNumber,
			GROUP_CONCAT(DISTINCT c.SiteName) AS SiteName,
			GROUP_CONCAT(DISTINCT c.SiteState) AS State,
			GROUP_CONCAT(DISTINCT c.SiteCountry) AS Country
		FROM
			ubm_database.tblInvoices AS i
			INNER JOIN
			ubm_database.tblVendors AS v
			ON
				i.VendorID = v.VendorID
			INNER JOIN
			ubm_database.tblAccounts AS a
			ON
				i.AccountID = a.AccountID
			INNER JOIN
				ubm_database.tblSiteAllocations AS b
			ON
				i.ClientID = b.ClientID AND
				i.VendorID = b.VendorID AND
				i.AccountID = b.AccountID
			INNER JOIN
				ubm_database.tblSites AS c
			ON
				c.SiteID = b.SiteID
			WHERE
				1=1 $filter_qry
			GROUP BY
					i.InvoiceID";
					
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
