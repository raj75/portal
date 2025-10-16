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

//$_SESSION["group_id"] = 2;
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
$primaryKey = 'Account_Number';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'ClientID',     'dt' => 'ClientID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Number',     'dt' => 'Site_Number',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Name',     'dt' => 'Site_Name',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Allocation',     'dt' => 'Allocation',  'formatter' => function( $d, $row ) { return ($d*100).' %'; } ),
   array( 'db' => 'Vendor_Name',     'dt' => 'Vendor_Name',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Account_Number',     'dt' => 'Account_Number',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Service_Type',     'dt' => 'Service_Type',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'ServiceBegin',     'dt' => 'ServiceBegin',  'formatter' => function( $d, $row ) { return date('m/d/Y',strtotime($d)); } ),
   array( 'db' => 'ServiceEnd',     'dt' => 'ServiceEnd',  'formatter' => function( $d, $row ) {return date('m/d/Y',strtotime($d));} ),
   array( 'db' => 'Last_InvoiceID',     'dt' => 'Last_InvoiceID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Last_InvoiceUBMID',     'dt' => 'Last_InvoiceUBMID',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Days_in_Last_Billing_Period',     'dt' => 'Days_in_Last_Billing_Period',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Days_Elapsed',     'dt' => 'Days_Elapsed',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Prior_Due_Date',     'dt' => 'Prior_Due_Date',  'formatter' => function( $d, $row ) {return date('m/d/Y',strtotime($d));} ),
   array( 'db' => 'Last_Notified_Date',     'dt' => 'Last_Notified_Date',  'formatter' => function( $d, $row ) {return date('m/d/Y',strtotime($d));;} ),
   array( 'db' => 'Service_Amount_of_Last_Bill',     'dt' => 'Service_Amount_of_Last_Bill',  'formatter' => function( $d, $row ) { return "$".number_format($d, 2, '.', ','); } ),
   array( 'db' => 'Daily_Average_Cost',     'dt' => 'Daily_Average_Cost',  'formatter' => function( $d, $row ) { return "$".number_format($d, 2, '.', ','); } ),
   
   array( 'db' => 'Accrual_Amount',     'dt' => 'Accrual_Amount',  'formatter' => function( $d, $row ) { return "$".number_format($d, 2, '.', ','); } ),
   
   array( 'db' => 'Usage',     'dt' => 'Usage',  'formatter' => function( $d, $row ) {return number_format($d, 2, '.', ',');} ),
   
   array( 'db' => 'Daily_Average_Usage',     'dt' => 'Daily_Average_Usage',  'formatter' => function( $d, $row ) {return number_format($d, 2, '.', ',');} ),
   
   array( 'db' => 'Accrual_Usage',     'dt' => 'Accrual_Usage',  'formatter' => function( $d, $row ) {return number_format($d, 2, '.', ',');} ),
   array( 'db' => 'Unit_of_Measure',     'dt' => 'Unit_of_Measure',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SiteCountry',     'dt' => 'SiteCountry',  'formatter' => function( $d, $row ) {return $d;} ),
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

//


// ---------cuttoff_date filter--------------
$cuttoff_date = $_POST['columns'][13]['search']['value'];

if ( isset($cuttoff_date) AND strlen($cuttoff_date) > 1 ) {
	
	$cuttoff_date = date("Y-m-d",strtotime($cuttoff_date));
	
	$filter_qry .= " AND j.`ConsolidationNotificationDate` <= '$cuttoff_date' ";
}

$_POST['columns'][13]['search']['value'] = "";
$_POST['columns'][13]['searchable'] = false;


// ---------service type filter--------------
$service_type_ids = $_POST['columns'][6]['search']['value'];

if ( isset($service_type_ids) AND (int)$service_type_ids > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$service_type_ids = mysqli_real_escape_string($mysqli,$service_type_ids); // comma seperated ids
	$filter_qry .= " AND a.ServiceTypeID IN ($service_type_ids) ";
}

$_POST['columns'][6]['search']['value'] = "";
$_POST['columns'][6]['searchable'] = false;

// ---------account id filter--------------
$account_id = $_POST['columns'][5]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
}

$_POST['columns'][5]['search']['value'] = "";
$_POST['columns'][5]['searchable'] = false;

// ---------vendor id filter--------------
$vendor_id = $_POST['columns'][4]['search']['value'];
//echo "vendor_id==".$vendor_id;
//die();
if ( isset($vendor_id) AND (int)$vendor_id > 0 ) {
	
	//$vendor_id = (int) $vendor_id;
	$vendor_ids = mysqli_real_escape_string($mysqli,$vendor_id); // comma seperated ids
	//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
	$filter_qry .= " AND e.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][4]['search']['value'] = "";
$_POST['columns'][4]['searchable'] = false;

// ---------site filter--------------
$site_id = $_POST['columns'][1]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND h.SiteID IN ($site_id) ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;

// ---------state filter--------------
$state_ar = $_POST['columns'][22]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND h.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][22]['search']['value'] = "";
$_POST['columns'][22]['searchable'] = false;

// ---------country filter--------------
$country_ar= $_POST['columns'][23]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	$filter_qry .= " AND h.SiteCountry IN ('$country_ar_coma') ";
}

$_POST['columns'][23]['search']['value'] = "";
$_POST['columns'][23]['searchable'] = false;

// -----------------------



if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][0]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND a.ClientID IN ($client_id) ";
		$sub_qry_client_id = " a.ClientId IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND a.ClientID = 9 ";
		$sub_qry_client_id = " a.ClientId = 9 ";
	}

	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND a.ClientID = '$company_id' ";
	$sub_qry_client_id = " a.ClientId = '$company_id' ";
	//$filter_qry .= " AND e.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][0]['search']['value'] = "";
	$_POST['columns'][0]['searchable'] = false;
}

//$sql = "SELECT i.InvoiceID,i.VendorID,v.VendorName,i.AccountID,a.AccountNumber,i.DueDate FROM NewSchema9.tblInvoices i INNER JOIN NewSchema9.tblVendors v ON i.VendorID=v.VendorID INNER JOIN NewSchema9.tblAccounts a ON i.AccountID = a.AccountID";



/*
SELECT
		r.ClientID,
		h.SiteNumber AS `Site_Number`,
		h.SiteName AS `Site_Name`,
		f.Allocation,
		UPPER(g.VendorName) AS `Vendor_Name`,
		e.AccountNumber AS `Account_Number`,
		i.ServiceTypeName AS `Service_Type`,		
		a.ServiceBegin,
		a.ServiceEnd,
		r.InvoiceID AS `Last_InvoiceID`,
		j.InvoiceUBMID AS `Last_InvoiceUBMID`,
		a.InvoiceDays AS `Days_in_Last_Billing_Period`,
		DATEDIFF(NOW(), a.ServiceEnd) AS `Days_Elapsed`,
		j.`DueDate` AS `Prior_Due_Date`,
		j.`ConsolidationNotificationDate` AS `Last_Notified_Date`,
		ROUND(SUM((a.BaseCost + a.UsageCost) * f.Allocation), 2) AS `Service_Amount_of_Last_Bill`,
		ROUND(SUM((a.BaseCost + a.UsageCost) * f.Allocation)/InvoiceDays, 2)  AS `Daily_Average_Cost`,
		GREATEST(ROUND(((SUM(a.BaseCost + a.UsageCost) * f.Allocation) / InvoiceDays) * DATEDIFF(NOW(), a.ServiceEnd), 2), 0) AS `Accrual_Amount`,
		ROUND(SUM(a.UsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
		ROUND(SUM(a.UsageReport * d.UnitConversion * f.Allocation)/InvoiceDays, 0) AS `Daily_Average_Usage`,
		GREATEST(ROUND((SUM(a.UsageReport * d.UnitConversion * f.Allocation)/InvoiceDays) * DATEDIFF(NOW(), a.ServiceEnd), 0), 0) AS `Accrual_Usage`,
    d.UnitConverted AS `Unit_of_Measure`
FROM
		(SELECT DISTINCT
			a.ClientID,
			a.AccountID,
			a.ServiceID,
			a.ServiceEnd,
			MAX(a.InvoiceID) AS InvoiceID
		FROM
			tblCostUsage a
		JOIN	
			
		(SELECT
			a.ClientID,
			a.AccountID,
			a.ServiceID,
			MAX(a.ServiceEnd) AS MostRecentEndDate
		FROM
			tblCostUsage a
		WHERE
			a.ClientID=38
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
			a.ClientId=38
							
		GROUP BY
			a.ClientID,
			a.AccountID,
			a.ServiceID) AS r
LEFT JOIN
		tblCostUsage  a
ON
		r.InvoiceID=a.InvoiceID
		
LEFT JOIN
    tblMeters AS c
ON
		a.MeterID = c.MeterID	
LEFT JOIN
    tblUnits AS d
ON
		a.UnitID = d.UnitID	
LEFT JOIN
    tblAccounts AS e
ON
		a.AccountID = e.AccountID
LEFT JOIN
		tblVendors AS g
ON
		e.VendorID = g.VendorID
LEFT JOIN
    tblSiteAllocations AS f
ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		tblSites AS h
ON
		f.SiteID = h.SiteID		
LEFT JOIN
		tblServiceTypes AS i
ON
		a.ServiceTypeID = i.ServiceTypeID				
LEFT JOIN
		tblInvoices j
ON
		r.ClientID=j.ClientID AND
		r.InvoiceID=j.InvoiceID
WHERE
		a.ClientID=38
		
		AND h.SiteID IS NOT NULL
		-- AND h.SiteID IN ()
		-- AND a.VendorID IN ()
		AND a.ServiceTypeID IN (3)
		-- AND a.AccountID IN ()
		-- AND h.SiteState IN (‘FL’)
		AND h.SiteCountry IN (‘US’)
		AND j.`ConsolidationNotificationDate` <= ‘2024-10-07’ -- Accrual Consolidation Cutoff Date
		
GROUP BY
    f.SiteID,
		a.AccountID,
		a.ServiceTypeID,			
		a.InvoiceID,
		f.Allocation
;
*/
	
$sql = "SELECT
		r.ClientID,
		h.SiteNumber AS `Site_Number`,
		h.SiteName AS `Site_Name`,
		f.Allocation,
		UPPER(g.VendorName) AS `Vendor_Name`,
		e.AccountNumber AS `Account_Number`,
		i.ServiceTypeName AS `Service_Type`,		
		a.ServiceBegin,
		a.ServiceEnd,
		r.InvoiceID AS `Last_InvoiceID`,
		j.InvoiceUBMID AS `Last_InvoiceUBMID`,
		a.InvoiceDays AS `Days_in_Last_Billing_Period`,
		DATEDIFF(NOW(), a.ServiceEnd) AS `Days_Elapsed`,
		j.`DueDate` AS `Prior_Due_Date`,
		j.`ConsolidationNotificationDate` AS `Last_Notified_Date`,
		ROUND(SUM((a.BaseCost + a.UsageCost) * f.Allocation), 2) AS `Service_Amount_of_Last_Bill`,
		ROUND(SUM((a.BaseCost + a.UsageCost) * f.Allocation)/InvoiceDays, 2)  AS `Daily_Average_Cost`,
		GREATEST(ROUND(((SUM(a.BaseCost + a.UsageCost) * f.Allocation) / InvoiceDays) * DATEDIFF(NOW(), a.ServiceEnd), 2), 0) AS `Accrual_Amount`,
		ROUND(SUM(a.UsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
		ROUND(SUM(a.UsageReport * d.UnitConversion * f.Allocation)/InvoiceDays, 0) AS `Daily_Average_Usage`,
		GREATEST(ROUND((SUM(a.UsageReport * d.UnitConversion * f.Allocation)/InvoiceDays) * DATEDIFF(NOW(), a.ServiceEnd), 0), 0) AS `Accrual_Usage`,
		d.UnitConverted AS `Unit_of_Measure`,
		h.SiteState AS SiteState,
		h.SiteCountry AS SiteCountry
FROM
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
			$sub_qry_client_id
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
			$sub_qry_client_id
		GROUP BY
			a.ClientID,
			a.AccountID,
			a.ServiceID
			) AS r
LEFT JOIN
		ubm_database.tblCostUsage  a
ON
		r.InvoiceID=a.InvoiceID
		
LEFT JOIN
    ubm_database.tblMeters AS c
ON
		a.MeterID = c.MeterID	
LEFT JOIN
    ubm_database.tblUnits AS d
ON
		a.UnitID = d.UnitID	
LEFT JOIN
    ubm_database.tblAccounts AS e
ON
		a.AccountID = e.AccountID
LEFT JOIN
		ubm_database.tblVendors AS g
ON
		e.VendorID = g.VendorID
LEFT JOIN
    ubm_database.tblSiteAllocations AS f
ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		ubm_database.tblSites AS h
ON
		f.SiteID = h.SiteID		
LEFT JOIN
		ubm_database.tblServiceTypes AS i
ON
		a.ServiceTypeID = i.ServiceTypeID				
LEFT JOIN
		ubm_database.tblInvoices j
ON
		r.ClientID=j.ClientID AND
		r.InvoiceID=j.InvoiceID
WHERE
		1=1 
		
		$filter_qry
		
GROUP BY
    f.SiteID,
		a.AccountID,
		a.ServiceTypeID,			
		a.InvoiceID,
		f.Allocation
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
