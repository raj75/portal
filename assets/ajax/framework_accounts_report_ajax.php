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

		

function setStatus($dbstatus) { 
		
	if ( is_null($dbstatus) ) {
		return 'Active';
	} else if ($dbstatus == 0) {
		return 'Inactive';
	} else if ($dbstatus == 1) {
		return 'Active';
	}
}
// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'AccountID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'AccountID',     'dt' => 'AccountID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountNumber',     'dt' => 'AccountNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountStatus',     'dt' => 'AccountStatus',  'formatter' => function( $d, $row ) { return setStatus($d); } ),
   array( 'db' => 'ServiceTypeName',     'dt' => 'ServiceTypeName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'ServiceStatus',     'dt' => 'ServiceStatus',  
		'formatter' => function( $d, $row ) { return setStatus($d); } ),
   array( 'db' => 'MeterNumber',     'dt' => 'MeterNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'MeterStatus',     'dt' => 'MeterStatus',  'formatter' => function( $d, $row ) {return setStatus($d);} ),
   array( 'db' => 'RateName',     'dt' => 'RateName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteNumber',     'dt' => 'SiteNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteName',     'dt' => 'SiteName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteStatus',     'dt' => 'SiteStatus',  'formatter' => function( $d, $row ) {return setStatus($d);} ),
   array( 'db' => 'SiteAddress1',     'dt' => 'SiteAddress1',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteCity',     'dt' => 'SiteCity',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteZip',     'dt' => 'SiteZip',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteCountry',     'dt' => 'SiteCountry',  'formatter' => function( $d, $row ) {return $d;} ),   
   array( 'db' => 'Allocation',     'dt' => 'Allocation',  
		'formatter' => function( $d, $row ) {
								return ($d*100).' %';
						} ),
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   
);




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
$account_id = $_POST['columns'][2]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
}

$_POST['columns'][2]['search']['value'] = "";
$_POST['columns'][2]['searchable'] = false;

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][1]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND g.VendorID IN ($vendor_ids) ";
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
		$filter_qry .= " AND d.ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND d.ClientID = 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND d.ClientID = '$company_id' ";
	//$filter_qry .= " AND d.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}


// ---------country filter--------------
$country_ar= $_POST['columns'][16]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND h.SiteCountry IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][16]['search']['value'] = "";
$_POST['columns'][16]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][14]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND h.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][14]['search']['value'] = "";
$_POST['columns'][14]['searchable'] = false;

// ---------site filter--------------
$site_id = $_POST['columns'][9]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND d.SiteID IN ($site_id) ";
}

$_POST['columns'][9]['search']['value'] = "";
$_POST['columns'][9]['searchable'] = false;


// ---------accountstatus filter--------------
$accountstatus = $_POST['columns'][3]['search']['value'];

if ( isset($accountstatus) AND strlen($accountstatus) > 1 ) {
	
	$accountstatus = mysqli_real_escape_string($mysqli,$accountstatus); // comma seperated ids
	//$state_ar_coma = str_replace("," , "','" , $state_ar);
	if ($accountstatus == 'active') {
		//$accountstatus = 1;
		$filter_qry .= " AND (a.AccountStatus = 1 or a.AccountStatus IS NULL) ";
	} else if ($accountstatus == 'inactive') {
		//$accountstatus = 0;
		$filter_qry .= " AND a.AccountStatus = 0 ";
	}
	//$filter_qry .= " AND a.AccountStatus = '$accountstatus' ";
}

$_POST['columns'][3]['search']['value'] = "";
$_POST['columns'][3]['searchable'] = false;

// ---------meterstatus filter--------------
$meterstatus = $_POST['columns'][7]['search']['value'];

if ( isset($meterstatus) AND strlen($meterstatus) > 1 ) {
	
	$meterstatus = mysqli_real_escape_string($mysqli,$meterstatus); // comma seperated ids
	//$state_ar_coma = str_replace("," , "','" , $state_ar);
	if ($meterstatus == 'active') {
		//$meterstatus = 1;
		$filter_qry .= " AND (c.MeterStatus = 1 or c.MeterStatus IS NULL) ";
	} else if ($meterstatus == 'inactive') {
		//$meterstatus = 0;
		$filter_qry .= " AND c.MeterStatus = 0 ";
	}
	//$filter_qry .= " AND c.MeterStatus = '$meterstatus' ";
}

$_POST['columns'][7]['search']['value'] = "";
$_POST['columns'][7]['searchable'] = false;

// ---------sitestatus filter--------------
$sitestatus = $_POST['columns'][11]['search']['value'];

if ( isset($sitestatus) AND strlen($sitestatus) > 1 ) {
	
	$sitestatus = mysqli_real_escape_string($mysqli,$sitestatus); // comma seperated ids
	//$state_ar_coma = str_replace("," , "','" , $state_ar);
	if ($sitestatus == 'active') {
		//$sitestatus = 1;
		$filter_qry .= " AND (h.SiteStatus = 1 or h.SiteStatus IS NULL) ";
	} else if ($sitestatus == 'inactive') {
		//$sitestatus = 0;
		$filter_qry .= " AND h.SiteStatus = 0 ";
	}
	//$filter_qry .= " AND h.SiteStatus = '$sitestatus' ";
}

$_POST['columns'][11]['search']['value'] = "";
$_POST['columns'][11]['searchable'] = false;

/*
$sql = "SELECT
	g.VendorName,
	a.AccountNumber,
	a.AccountStatus,
	f.ServiceTypeName,
	b.ServiceStatus,
	c.MeterNumber,
	c.MeterStatus,
	e.RateName,
	h.SiteNumber,
	h.SiteName,
	h.SiteStatus,
	h.SiteAddress1,
	h.SiteCity,
	h.SiteState,
	h.SiteZip,
	h.SiteCountry,
	d.Allocation
FROM
	tblAccounts a
INNER JOIN
	tblServices b
ON
	a.AccountID = b.AccountID
INNER JOIN
	tblMeters c
ON
	b.ServiceID = c.ServiceID
INNER JOIN
	tblSiteAllocations d
ON
	c.MeterID = d.MeterID
INNER JOIN
	tblRates e
ON
	c.RateID = e.RateID
INNER JOIN
	tblServiceTypes f
ON
	b.ServiceTypeID = f.ServiceTypeID
INNER JOIN
	tblVendors g
ON
	a.VendorID = g.VendorID
INNER JOIN
	tblSites h
ON
	d.SiteID = h.SiteID
WHERE
	1=1  AND a.AccountID IN (10456) AND g.VendorID IN (14) AND a.ClientID IN (10) AND h.SiteCountry IN ('US')  AND h.SiteState IN ('AL')  AND h.SiteID IN (36486) ";
*/
	
$sql = "SELECT
		a.AccountID,
		g.VendorName,
		a.AccountNumber,
		a.AccountStatus,
		f.ServiceTypeName,
		b.ServiceStatus,
		c.MeterNumber,
		c.MeterStatus,
		e.RateName,
		h.SiteNumber,
		h.SiteName,
		h.SiteStatus,
		h.SiteAddress1,
		h.SiteCity,
		h.SiteState,
		h.SiteZip,
		h.SiteCountry,
		d.Allocation,
		d.ClientID
			FROM
				ubm_database.tblAccounts a
			INNER JOIN
				ubm_database.tblServices b
			ON
				a.AccountID = b.AccountID
			INNER JOIN
				ubm_database.tblMeters c
			ON
				b.ServiceID = c.ServiceID
			INNER JOIN
				ubm_database.tblSiteAllocations d
			ON
				c.MeterID = d.MeterID
			INNER JOIN
				ubm_database.tblRates e
			ON
				c.RateID = e.RateID
			INNER JOIN
				ubm_database.tblServiceTypes f
			ON
				b.ServiceTypeID = f.ServiceTypeID
			INNER JOIN
				ubm_database.tblVendors g
			ON
				a.VendorID = g.VendorID
			INNER JOIN
				ubm_database.tblSites h
			ON
				d.SiteID = h.SiteID
			WHERE
				1=1  
			
				$filter_qry
			
					
			";

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
