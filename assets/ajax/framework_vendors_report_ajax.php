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

		

// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'ClientID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'ServiceTypeName',     'dt' => 'ServiceTypeName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteCountry',     'dt' => 'SiteCountry',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorAddress1',     'dt' => 'VendorAddress1',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorAddress2',     'dt' => 'VendorAddress2',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorAddress3',     'dt' => 'VendorAddress3',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorCity',     'dt' => 'VendorCity',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorState',     'dt' => 'VendorState',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorZip',     'dt' => 'VendorZip',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorCountry',     'dt' => 'VendorCountry',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorPhone',     'dt' => 'VendorPhone',  
					'formatter' => function( $d, $row ) {
						//return $d;

						if (strlen($d) == 10) {
							$output = "(".substr($d, -10, -7) . ") " . substr($d, -7, -4) . "-" . substr($d, -4); 
							return $output;
						} else {
							return $d;
						}
					} ),
   array( 'db' => 'VendorFax',     'dt' => 'VendorFax',  
					'formatter' => function( $d, $row ) {
						if (strlen($d) == 10) {
							$output = "(".substr($d, -10, -7) . ") " . substr($d, -7, -4) . "-" . substr($d, -4); 
							return $output;
						} else {
							return $d;
						}
					} ),
   array( 'db' => 'VendorEmail',     'dt' => 'VendorEmail',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'VendorStatus',     'dt' => 'VendorStatus', 
        'formatter' => function( $d, $row ) {
							if ((int) $d == 1) {
								return 'Active';
							} else {
								return 'Inactive';
							}
						} ),
   
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


/*
// ---------account id filter--------------
$account_id = $_POST['columns'][1]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;
*/

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][4]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND g.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][4]['search']['value'] = "";
$_POST['columns'][4]['searchable'] = false;


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
$country_ar= $_POST['columns'][11]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND h.SiteCountry IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][11]['search']['value'] = "";
$_POST['columns'][11]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][9]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND h.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][9]['search']['value'] = "";
$_POST['columns'][9]['searchable'] = false;

// ---------service id filter--------------
$service_id = $_POST['columns'][1]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($service_id) AND (int)$service_id > 0 ) {
	$service_ids = mysqli_real_escape_string($mysqli,$service_id); // comma seperated ids
	$filter_qry .= " AND f.ServiceTypeID IN ($service_ids) ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;

/*
// ---------site filter--------------
$site_id = $_POST['columns'][8]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND d.SiteID IN ($site_id) ";
}

$_POST['columns'][8]['search']['value'] = "";
$_POST['columns'][8]['searchable'] = false;

*/


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
	
$sql = "SELECT DISTINCT
			a.ClientID,
			f.ServiceTypeName,
			h.SiteState,
			h.SiteCountry,
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
			ubm_database.tblAccounts AS a
			INNER JOIN
			ubm_database.tblServices AS b
			ON
				a.AccountID = b.AccountID
			INNER JOIN
			ubm_database.tblMeters AS c
			ON
				b.ServiceID = c.ServiceID
			INNER JOIN
			ubm_database.tblSiteAllocations AS d
			ON
				c.MeterID = d.MeterID
			INNER JOIN
			ubm_database.tblServiceTypes AS f
			ON
				b.ServiceTypeID = f.ServiceTypeID
			INNER JOIN
			ubm_database.tblVendors AS g
			ON
				a.VendorID = g.VendorID
			INNER JOIN
			ubm_database.tblSites AS h
			ON
				d.SiteID = h.SiteID
		WHERE
			1 = 1 
			
			$filter_qry
			
			AND g.VendorStatus=1
			
		ORDER BY
			f.ServiceTypeID,
			h.SiteState	
			
					
			";
			
			//AND	f.ServiceTypeID IN (1,2,3) AND a.ClientID IN (38) AND h.SiteState IN ('AZ', 'AL') AND g.VendorStatus=1

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
