<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");

//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	//die("Restricted Access");

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
   array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteNumber',     'dt' => 'SiteNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteName',     'dt' => 'SiteName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteStatus',     'dt' => 'SiteStatus',  'formatter' => function( $d, $row ) {
	    if ((int) $d == 1) {
			return 'Active';
		} else if ((int) $d == 0) {
			return 'Inactive';
		}
   } ),
   array( 'db' => 'Electricity',     'dt' => 'Electricity',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Gas_Heating',     'dt' => 'Gas_Heating',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Water_Sewer',     'dt' => 'Water_Sewer',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Waste',     'dt' => 'Waste',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Telecom',     'dt' => 'Telecom',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteID',     'dt' => 'SiteID',  'formatter' => function( $d, $row ) {return $d;} ),
   
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

if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND a.ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND a.ClientID = 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND a.ClientID = '$company_id' ";
	//$filter_qry .= " AND a.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}


// ---------state filter--------------
$state_ar = $_POST['columns'][1]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND c.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;


// ---------sitestatus filter--------------
$sitestatus = $_POST['columns'][4]['search']['value'];

if ( isset($sitestatus) AND strlen($sitestatus) > 1 ) {
	
	$sitestatus = mysqli_real_escape_string($mysqli,$sitestatus); // comma seperated ids
	//$state_ar_coma = str_replace("," , "','" , $state_ar);
	if ($sitestatus == 'active') {
		$sitestatus = 1;
	} else if ($sitestatus == 'inactive') {
		$sitestatus = 0;
	}
	$filter_qry .= " AND c.SiteStatus = '$sitestatus' ";
}

$_POST['columns'][4]['search']['value'] = "";
$_POST['columns'][4]['searchable'] = false;

/*
SELECT DISTINCT
	a.ClientID,
	c.SiteState,
	c.SiteNumber,
	c.SiteName,
	c.SiteStatus,
	COUNT(DISTINCT IF(b.ServiceCategoryID=1, a.AccountID, NULL)) AS `Electricity`,
	COUNT(DISTINCT IF(b.ServiceCategoryID=2, a.AccountID, NULL)) AS `Gas/Heating`,
	COUNT(DISTINCT IF(b.ServiceCategoryID=3, a.AccountID, NULL)) AS `Water/Sewer`,
	COUNT(DISTINCT IF(b.ServiceCategoryID=4, a.AccountID, NULL)) AS Waste,
	COUNT(DISTINCT IF(b.ServiceCategoryID=5, a.AccountID, NULL)) AS Telecom
FROM
	ubm_database.tblSiteAllocations a
LEFT JOIN
	ubm_database.tblServiceTypes b
ON
	a.ServiceTypeID=b.ServiceTypeID
LEFT JOIN
	ubm_database.tblSites c
ON
	a.SiteID=c.SiteID
WHERE
	a.ClientID=10
	AND (b.ServiceCategoryID IS NOT NULL AND b.ServiceCategoryID<>0)
GROUP BY
	a.ClientID,
	a.SiteID;
;
*/
//-----------Important-------------
// dont add ; in query
//----------------------------------

$sql = "SELECT DISTINCT
			a.ClientID,
			c.SiteState,
			c.SiteNumber,
			c.SiteName,
			c.SiteStatus,
			COUNT(DISTINCT IF(b.ServiceCategoryID=1, a.AccountID, NULL)) AS `Electricity`,
			COUNT(DISTINCT IF(b.ServiceCategoryID=2, a.AccountID, NULL)) AS `Gas_Heating`,
			COUNT(DISTINCT IF(b.ServiceCategoryID=3, a.AccountID, NULL)) AS `Water_Sewer`,
			COUNT(DISTINCT IF(b.ServiceCategoryID=4, a.AccountID, NULL)) AS Waste,
			COUNT(DISTINCT IF(b.ServiceCategoryID=5, a.AccountID, NULL)) AS Telecom,
			c.SiteID
		FROM
			ubm_database.tblSiteAllocations a
		LEFT JOIN
			ubm_database.tblServiceTypes b
		ON
			a.ServiceTypeID=b.ServiceTypeID
		LEFT JOIN
			ubm_database.tblSites c
		ON
			a.SiteID=c.SiteID
		WHERE
			1=1 
			$filter_qry
			AND (b.ServiceCategoryID IS NOT NULL AND b.ServiceCategoryID<>0)
		GROUP BY
			a.ClientID,
			a.SiteID
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
