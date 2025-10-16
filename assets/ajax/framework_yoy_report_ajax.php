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
$primaryKey = 'Site_Number';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'State',     'dt' => 'State',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Number',     'dt' => 'Site_Number',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Name',     'dt' => 'Site_Name',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Site_Allocation',     'dt' => 'Site_Allocation',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Service_Type',     'dt' => 'Service_Type',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Scope',     'dt' => 'Scope',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Emissions_Factor_Year',     'dt' => 'Emissions_Factor_Year',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Usage',     'dt' => 'Usage',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Unit_of_Measure',     'dt' => 'Unit_of_Measure',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'Cost',     'dt' => 'Cost',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'CO2_Pounds',     'dt' => 'CO2_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'CH4_Pounds',     'dt' => 'CH4_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'N2O_Pounds',     'dt' => 'N2O_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'CO2-e_Pounds',     'dt' => 'CO2-e_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'NOx-Annual_Pounds',     'dt' => 'NOx-Annual_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'NOx-Ozone_Season_Pounds',     'dt' => 'NOx-Ozone_Season_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'SO2_Pounds',     'dt' => 'SO2_Pounds',  'formatter' => function( $d, $row ) {return $d;} ),
   
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

/*
// ---------account id filter--------------
$account_id = $_POST['columns'][7]['search']['value'];

if ( isset($account_id) AND (int)$account_id > 1 ) {
	
	//$vendor_id = (int) $vendor_id;
	$account_ids = mysqli_real_escape_string($mysqli,$account_id); // comma seperated ids
	$filter_qry .= " AND a.AccountID IN ($account_ids) ";
}

$_POST['columns'][7]['search']['value'] = "";
$_POST['columns'][7]['searchable'] = false;

*/

/*
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
*/

/*
// ---------due date --> entry date filter--------------
//john: instead of due date, can you use entered date?
$filter_date = $_POST['columns'][13]['search']['value'];

if ( isset($filter_date) AND strlen($filter_date) > 1 ) {
	
	$filter_date_arr = explode("~",$filter_date);
	$filter_date_start = date("Ym",strtotime($filter_date_arr[0]));
	$filter_date_end = date("Ym",strtotime($filter_date_arr[1])); 
	
	//$filter_qry .= " AND i.DueDate between '$due_date_start' AND '$due_date_end' ";
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND a.CalPeriod >= '$filter_date_start' AND a.CalPeriod <= '$filter_date_end' ";
} else {
	// if due date is not selected then by default last one month data will show
	$filter_date_start = date("Ym", strtotime(" - 6 month"));
	$filter_date_end = date("Ym");
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND a.CalPeriod >= '$filter_date_start' AND a.CalPeriod <= '$filter_date_end' ";
}

$_POST['columns'][13]['search']['value'] = "";
$_POST['columns'][13]['searchable'] = false;
*/

/*
if ($_SESSION["group_id"] == 1 OR $_SESSION["group_id"] == 2) {
// ---------client/company filter--------------
	$client_id = $_POST['columns'][18]['search']['value'];

	if ( isset($client_id) AND (int)$client_id > 1 ) {
		
		//$vendor_id = (int) $vendor_id;
		$client_id = mysqli_real_escape_string($mysqli,$client_id); // comma seperated ids
		//$filter_qry .= " AND i.VendorID = '$vendor_id' ";
		$filter_qry .= " AND a.ClientID IN ($client_id) ";
	} else {
		// if admin or employee login but no search then default company is demo company 9
		$filter_qry .= " AND a.ClientID = 9 ";
	}

	$_POST['columns'][18]['search']['value'] = "";
	$_POST['columns'][18]['searchable'] = false;

} else {
	// if other then admin or client login then use logged in user's company
	$company_id=$_SESSION['company_id'];
	// company id is client id
	$filter_qry .= " AND a.ClientID = '$company_id' ";
	//$filter_qry .= " AND e.ClientID = 10 "; // for testing on local
	
	$_POST['columns'][18]['search']['value'] = "";
	$_POST['columns'][18]['searchable'] = false;
}
*/

/*
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

*/

/*
// ---------state filter--------------
$state_ar = $_POST['columns'][4]['search']['value'];

if ( isset($state_ar) AND strlen($state_ar) > 1 ) {
	
	$state_ar = mysqli_real_escape_string($mysqli,$state_ar); // comma seperated ids
	//$filter_qry .= " AND c.SiteState = '$state_ar' ";
	$state_ar_coma = str_replace("," , "','" , $state_ar);
	$filter_qry .= " AND h.SiteState IN ('$state_ar_coma') ";
}

$_POST['columns'][4]['search']['value'] = "";
$_POST['columns'][4]['searchable'] = false;
*/

/*
// ---------site filter--------------
$site_id = $_POST['columns'][0]['search']['value'];

if ( isset($site_id) AND (int)$site_id > 1 ) {
	
	$site_id = mysqli_real_escape_string($mysqli,$site_id); // comma seperated ids
	//$filter_qry .= " AND c.SiteID = '$site_id' ";
	$filter_qry .= " AND h.SiteID IN ($site_id) ";
}

$_POST['columns'][0]['search']['value'] = "";
$_POST['columns'][0]['searchable'] = false;

//for meter show hide
$meter_check = (isset($_POST['meter_check']))?$_POST['meter_check']:0;

if ($meter_check == 1) {
	$group_by_qry = 'a.MeterID,';
} else {
	$group_by_qry = '';
}
*/

//$sql = "SELECT i.InvoiceID,i.VendorID,v.VendorName,i.AccountID,a.AccountNumber,i.DueDate FROM NewSchema9.tblInvoices i INNER JOIN NewSchema9.tblVendors v ON i.VendorID=v.VendorID INNER JOIN NewSchema9.tblAccounts a ON i.AccountID = a.AccountID";



/*
SELECT
		h.SiteState AS State,
		h.SiteNumber AS `Site Number`,
		h.SiteName AS `Site Name`,
		f.Allocation AS `Site Allocation`,
		i.ServiceTypeName AS `Service Type`,		
		i.GHGScope AS Scope,
		j.Year AS `Emissions Factor Year`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
    d.UnitConverted AS `Unit of Measure`,
    ROUND(SUM((a.CalBaseCost + a.CalUsageCost) * f.Allocation), 2) AS Cost,
 		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CO2 Factor', j.FactorRate_converted, 0)),2) AS `CO2 Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CH4 Factor', j.FactorRate_converted, 0)),2) AS `CH4 Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='N2O Factor', j.FactorRate_converted, 0)),2) AS `N2O Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CO2 Factor', j.FactorRate_converted, 0)),2) AS `CO2-e Pounds`,  		
		0 AS `NOx-Annual Pounds`,
		0 AS `NOx-Ozone Season Pounds`,
		0 AS `SO2 Pounds`
FROM
	ubm_database.tblCostUsage  a
LEFT JOIN
    ubm_database.tblMeters AS c ON a.MeterID = c.MeterID	
LEFT JOIN
    ubm_database.tblUnits AS d ON a.UnitID = d.UnitID	
LEFT JOIN
    ubm_database.tblAccounts AS e ON a.AccountID = e.AccountID
LEFT JOIN
		ubm_database.tblVendors AS g ON e.VendorID = g.VendorID
LEFT JOIN
    ubm_database.tblSiteAllocations AS f ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		ubm_database.tblSites AS h ON f.SiteID = h.SiteID		
LEFT JOIN
		ubm_database.tblServiceTypes AS i ON a.ServiceTypeID = i.ServiceTypeID			
LEFT JOIN
		ubm_ghg.tblScope1 j
ON
		i.EPAFuelType=j.FuelType
WHERE
		-- Use Variables like this: j.Year=‘[3]’
		j.Year='2024' -- This is the EPA Year
		-- Use Variables like this: a.ClientID= ‘[5]’
		AND a.ClientID=10
		
		-- Use Variables like this: a.CalPeriod BETWEEN [1] AND [2]
		AND a.CalPeriod BETWEEN 202401 AND 202412 -- This is the Calendar date data
		AND h.SiteID IS NOT NULL
		AND i.GHGScope = 1  -- This selects combustion
GROUP BY
    f.SiteID,
		a.ServiceTypeID,
		f.Allocation
	
-- ---
UNION ALL		
-- SCOPE 2
SELECT
		h.SiteState AS State,
		h.SiteNumber AS `Site Number`,
		h.SiteName AS `Site Name`,
		f.Allocation AS `Site Allocation`,
		i.ServiceTypeName AS `Service Type`,		
		i.GHGScope AS Scope,
		j.Year AS `Emissions Factor Year`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
    d.UnitConverted AS `Unit of Measure`,
    ROUND(SUM((a.CalBaseCost + a.CalUsageCost) * f.Allocation), 2) AS Cost,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CO2_converted/1000),2) AS `CO2 Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CH4_converted/1000),2) AS `CH4 Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.N2O_converted/1000),2) AS `N2O Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CO2e_converted/1000),2) AS `CO2-e Pounds`, 	
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.`Annual NOx`/1000),2) AS `NOx-Annual Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.`Ozone Season NOx`/1000),2) AS `NOx-Ozone Season Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.SO2/1000),2) AS `SO2 Pounds`
FROM
	ubm_database.tblCostUsage  a
LEFT JOIN
    ubm_database.tblMeters AS c ON a.MeterID = c.MeterID	
LEFT JOIN
    ubm_database.tblUnits AS d ON a.UnitID = d.UnitID	
LEFT JOIN
    ubm_database.tblAccounts AS e ON a.AccountID = e.AccountID
LEFT JOIN
		ubm_database.tblVendors AS g ON e.VendorID = g.VendorID
LEFT JOIN
    ubm_database.tblSiteAllocations AS f ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		ubm_database.tblSites AS h ON f.SiteID = h.SiteID		
LEFT JOIN
		ubm_database.tblServiceTypes AS i ON a.ServiceTypeID = i.ServiceTypeID			
LEFT JOIN
		ubm_ghg.tblScope2 j
ON
		h.SiteState=j.State
WHERE
		-- Use Variables like this: j.Year=‘[4]’
		j.Year='2022' -- This is the eGrid Year
		-- Use Variables like this: a.ClientID= ‘[5]’
		AND a.ClientID=10
		
		-- Use Variables like this: a.CalPeriod BETWEEN [1] AND [2]
		AND a.CalPeriod BETWEEN 202401 AND 202412 -- This is the Calendar date data		
		
		AND h.SiteID IS NOT NULL
		AND i.GHGScope = 2  -- This selects electricity
GROUP BY
    f.SiteID,
		a.ServiceTypeID,
		f.Allocation
        
         limit 10
        ;
        
       
;
*/
	
$sql = "

SELECT
		h.SiteState AS State,
		h.SiteNumber AS `Site_Number`,
		h.SiteName AS `Site_Name`,
		f.Allocation AS `Site_Allocation`,
		i.ServiceTypeName AS `Service_Type`,		
		i.GHGScope AS Scope,
		j.Year AS `Emissions_Factor_Year`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
    d.UnitConverted AS `Unit_of_Measure`,
    ROUND(SUM((a.CalBaseCost + a.CalUsageCost) * f.Allocation), 2) AS Cost,
 		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CO2 Factor', j.FactorRate_converted, 0)),2) AS `CO2_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CH4 Factor', j.FactorRate_converted, 0)),2) AS `CH4_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='N2O Factor', j.FactorRate_converted, 0)),2) AS `N2O_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*MAX(IF(j.FactorName='CO2 Factor', j.FactorRate_converted, 0)),2) AS `CO2-e_Pounds`,  		
		0 AS `NOx-Annual_Pounds`,
		0 AS `NOx-Ozone_Season_Pounds`,
		0 AS `SO2_Pounds`
FROM
	ubm_database.tblCostUsage  a
LEFT JOIN
    ubm_database.tblMeters AS c ON a.MeterID = c.MeterID	
LEFT JOIN
    ubm_database.tblUnits AS d ON a.UnitID = d.UnitID	
LEFT JOIN
    ubm_database.tblAccounts AS e ON a.AccountID = e.AccountID
LEFT JOIN
		ubm_database.tblVendors AS g ON e.VendorID = g.VendorID
LEFT JOIN
    ubm_database.tblSiteAllocations AS f ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		ubm_database.tblSites AS h ON f.SiteID = h.SiteID		
LEFT JOIN
		ubm_database.tblServiceTypes AS i ON a.ServiceTypeID = i.ServiceTypeID			
LEFT JOIN
		ubm_ghg.tblScope1 j
ON
		i.EPAFuelType=j.FuelType
WHERE
		-- Use Variables like this: j.Year=‘[3]’
		j.Year='2024' -- This is the EPA Year
		-- Use Variables like this: a.ClientID= ‘[5]’
		AND a.ClientID=10
		
		-- Use Variables like this: a.CalPeriod BETWEEN [1] AND [2]
		AND a.CalPeriod BETWEEN 202401 AND 202412 -- This is the Calendar date data
		AND h.SiteID IS NOT NULL
		AND i.GHGScope = 1  -- This selects combustion
GROUP BY
    f.SiteID,
		a.ServiceTypeID,
		f.Allocation
	
-- ---
UNION ALL		
-- SCOPE 2
SELECT
		h.SiteState AS State,
		h.SiteNumber AS `Site_Number`,
		h.SiteName AS `Site_Name`,
		f.Allocation AS `Site_Allocation`,
		i.ServiceTypeName AS `Service_Type`,		
		i.GHGScope AS Scope,
		j.Year AS `Emissions_Factor_Year`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation), 0) AS `Usage`,
    d.UnitConverted AS `Unit_of_Measure`,
    ROUND(SUM((a.CalBaseCost + a.CalUsageCost) * f.Allocation), 2) AS Cost,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CO2_converted/1000),2) AS `CO2_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CH4_converted/1000),2) AS `CH4_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.N2O_converted/1000),2) AS `N2O_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.CO2e_converted/1000),2) AS `CO2-e_Pounds`, 	
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.`Annual NOx`/1000),2) AS `NOx-Annual_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.`Ozone Season NOx`/1000),2) AS `NOx-Ozone_Season_Pounds`,
		ROUND(SUM(a.CalUsageReport * d.UnitConversion * f.Allocation)*(j.SO2/1000),2) AS `SO2_Pounds`
FROM
	ubm_database.tblCostUsage  a
LEFT JOIN
    ubm_database.tblMeters AS c ON a.MeterID = c.MeterID	
LEFT JOIN
    ubm_database.tblUnits AS d ON a.UnitID = d.UnitID	
LEFT JOIN
    ubm_database.tblAccounts AS e ON a.AccountID = e.AccountID
LEFT JOIN
		ubm_database.tblVendors AS g ON e.VendorID = g.VendorID
LEFT JOIN
    ubm_database.tblSiteAllocations AS f ON
    a.ClientID = f.ClientID
    AND a.AccountID = f.AccountID
    AND a.ServiceID = f.ServiceID
    AND a.MeterID = f.MeterID
LEFT JOIN
		ubm_database.tblSites AS h ON f.SiteID = h.SiteID		
LEFT JOIN
		ubm_database.tblServiceTypes AS i ON a.ServiceTypeID = i.ServiceTypeID			
LEFT JOIN
		ubm_ghg.tblScope2 j
ON
		h.SiteState=j.State
WHERE
		-- Use Variables like this: j.Year=‘[4]’
		j.Year='2022' -- This is the eGrid Year
		-- Use Variables like this: a.ClientID= ‘[5]’
		AND a.ClientID=10
		
		-- Use Variables like this: a.CalPeriod BETWEEN [1] AND [2]
		AND a.CalPeriod BETWEEN 202401 AND 202412 -- This is the Calendar date data		
		
		AND h.SiteID IS NOT NULL
		AND i.GHGScope = 2  -- This selects electricity
GROUP BY
    f.SiteID,
		a.ServiceTypeID,
		f.Allocation
        
         limit 10
        
        
       
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
