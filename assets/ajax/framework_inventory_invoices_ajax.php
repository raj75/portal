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

$draw_post = (int) $_POST['draw']; // to refresh the datatable
// if site id is not set

$postsiteid = $_POST['columns'][1]['search']['value'];
if (trim($postsiteid)=="") {
	//return emply dataset for page load
	echo '{"draw":'.$draw_post.',"recordsTotal":0,"recordsFiltered":0,"data":[]}';
	die();
}

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
   array( 'db' => 'SiteID',     'dt' => 'SiteID',  'formatter' => function( $d, $row ) {return $d;} ),
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
   array( 'db' => 'VendorName',     'dt' => 'VendorName',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'AccountNumber',     'dt' => 'AccountNumber',  'formatter' => function( $d, $row ) {return $d;} ),
   
   
   
   array( 'db' => 'AccountStatus',     'dt' => 'AccountStatus',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'ServiceTypeName',     'dt' => 'ServiceTypeName',  'formatter' => function( $d, $row ) {return $d;} ),
   
   
   
   array( 'db' => 'ServiceCategory',     'dt' => 'ServiceCategory',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'LastInvoiceID',     'dt' => 'LastInvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'LastEndDate',     'dt' => 'LastEndDate',  'formatter' => function( $d, $row ) {return $d;} ),
   
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
$filter_client_id = '';
// get filters datatable

if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_client_id = " $client_id ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		//$filter_subqry .= " AND a.ClientID = 9 ";
		$filter_client_id = " 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	//$filter_subqry .= " AND a.ClientID = '$company_id' ";
	$filter_client_id = " $company_id ";
	//$filter_qry .= " AND e.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}

// ---------site id filter--------------
$siteid = $_POST['columns'][1]['search']['value'];

if ( isset($siteid) AND strlen($siteid) > 1 ) {
	
	$siteid = mysqli_real_escape_string($mysqli,$siteid); // comma seperated ids
	//$state_ar_coma = str_replace("," , "','" , $state_ar);
	//$filter_qry .= " AND c.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;



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
			a.SiteID,
			c.SiteState,
			c.SiteNumber,
			c.SiteName,
			c.SiteStatus,
			e.VendorName,
			d.AccountNumber,
			d.AccountStatus,
			f.ServiceTypeName,
			CASE
 				WHEN b.ServiceCategoryID = 1 THEN 'Electricity'
 				WHEN b.ServiceCategoryID = 2 THEN 'Gas/Heating'
 				WHEN b.ServiceCategoryID = 3 THEN 'Water/Sewer'
 				WHEN b.ServiceCategoryID = 4 THEN 'Waste'
 				WHEN b.ServiceCategoryID = 5 THEN 'Telecom'
 				ELSE 'Other'
 			END AS ServiceCategory,
			r.InvoiceID AS LastInvoiceID,
			r.ServiceEnd AS LastEndDate
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
		LEFT JOIN
			ubm_database.tblAccounts d
		ON
			a.AccountID = d.AccountID
		LEFT JOIN
			ubm_database.tblVendors e
		ON
			a.VendorID = e.VendorID
		LEFT JOIN
			ubm_database.tblServiceTypes f
		ON
			a.ServiceTypeID=f.ServiceTypeID
		LEFT JOIN
					(SELECT DISTINCT
						a.ClientID,
						a.AccountID,
						a.ServiceID,
						a.ServiceEnd,
						MAX(a.InvoiceID) AS InvoiceID
					FROM
						ubm_database.tblCostUsage a
					JOIN	
						
					(SELECT
						a.ClientID,
						a.AccountID,
						a.ServiceID,
						MAX(a.ServiceEnd) AS MostRecentEndDate
					FROM
						ubm_database.tblCostUsage a
					WHERE
						 a.ClientId IN ($filter_client_id)
					GROUP BY
						a.ClientID,
						a.AccountID,
						a.ServiceID) AS b
					ON
						a.ClientID=b.ClientID
						AND a.AccountID=b.AccountID
						AND a.ServiceID=b.ServiceID
						AND a.ServiceEnd=b.MostRecentEndDate
					WHERE
						 a.ClientId IN ($filter_client_id)
					GROUP BY
						a.ClientID,
						a.AccountID,
						a.ServiceID
						) AS r
		ON
			a.ClientID = r.ClientID
			AND a.AccountID = r.AccountID
			AND a.ServiceID = r.ServiceID
			
			
		WHERE
			1=1
			AND a.ClientID IN ($filter_client_id)
			AND (b.ServiceCategoryID IS NOT NULL AND b.ServiceCategoryID<>0)
			AND a.SiteID = $siteid
			
		GROUP BY
			a.ClientID,
			a.SiteID,
			d.AccountNumber,
			e.VendorID,
			a.ServiceTypeID
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
