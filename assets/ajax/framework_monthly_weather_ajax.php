<?php
//print_r($_POST);

$postal_code_ar = $_POST['columns'][1]['search']['value'];
if (trim($postal_code_ar)=="") {
	//return emply dataset for page load
	echo '{"draw":1,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
	die();
}

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

//print_r($_POST);

// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'country_code'; //use for count
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'country_code',     'dt' => 'country_code',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'postal_code',     'dt' => 'postal_code',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'year',     'dt' => 'year',  'formatter' => function( $d, $row ) {return "<span class='dt_year'>$d</span>";} ),
   array( 'db' => 'month',     'dt' => 'month',  'formatter' => function( $d, $row ) {
	   $month_num = $d;
	   $d = date("M", mktime(0, 0, 0, $month_num, 10)); //output: October
	   return "<span class='dt_month'>$d</span>";} ),
   array( 'db' => 'avg_temp',     'dt' => 'avg_temp',  'formatter' => function( $d, $row ) {return "<span class='dt_avgtemp'>$d</span>";} ),
   array( 'db' => 'min_temp',     'dt' => 'min_temp',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'max_temp',     'dt' => 'max_temp', 'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'base_temp',     'dt' => 'base_temp', 'formatter' => function( $d, $row ) {return $d;}),
   array( 'db' => 'hdd',     'dt' => 'hdd',  'formatter' => function( $d, $row ) {return $d;} ),
   array( 'db' => 'cdd',     'dt' => 'cdd',  'formatter' => function( $d, $row ) {return $d;} ),
   
   
);




// SQL server connection information
$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => DATABASE,
	'host' => HOST
);

$filter_qry = '';



/*
$sql = "SELECT
	a.country_code AS Country,
	a.postal_code AS `Zip/Postal Code`,
	YEAR(a.month_date) AS `Year`,
	MONTH(a.month_date) AS `Month`,
	a.avg_temp AS `Average Temp`,
	a.min_temp AS `Min Temp`,
	a.max_temp AS `Max Temp`,
	a.base_temp AS `Base Temp`,
	a.hdd AS `Heating Degree Days(HDD)`,
	a.cdd AS `Cooling Degree Days(CDD)`
FROM
	monthly_historical_weather AS a
WHERE
	a.country_code = ‘US’
	AND a.postal_code = ‘85258’	
	AND a.`month_date` BETWEEN ‘2020-01-01’ AND ‘2020-12-01’ ";
*/

// ---------country filter--------------
$country_ar= $_POST['columns'][0]['search']['value'];

if ( isset($country_ar) AND strlen($country_ar) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$country_ar_coma = str_replace("," , "','" , $country_ar);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND a.country_code IN ('$country_ar_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][0]['search']['value'] = "";
$_POST['columns'][0]['searchable'] = false;


// ---------postal code filter--------------
$postal_code= $_POST['columns'][1]['search']['value'];

if ( isset($postal_code) AND strlen($postal_code) > 1 ) {
	
	//$country_ar = $vendor_id;
	//$vendor_ids = mysqli_real_escape_string($mysqli,$country_ar); // comma seperated ids
	$postal_code = mysqli_real_escape_string($mysqli,$postal_code); // comma seperated ids
	$postal_code_coma = str_replace("," , "','" , $postal_code);
	//$filter_qry .= " AND c.SiteCountry = '$country_ar' ";
	$filter_qry .= " AND a.postal_code IN ('$postal_code_coma') ";
	//$filter_qry .= " AND i.VendorID IN ($vendor_ids) ";
}

$_POST['columns'][1]['search']['value'] = "";
$_POST['columns'][1]['searchable'] = false;

// ---------due date --> entry date filter--------------
//john: instead of due date, can you use entered date?
$from_to_month = $_POST['columns'][2]['search']['value'];

if ( isset($from_to_month) AND strlen($from_to_month) > 1 ) {
	
	$from_to_month_arr = explode("~",$from_to_month);
	$from_date = date("Y-m-d",strtotime("01 ".$from_to_month_arr[0]));
	$to_date = date("Y-m-d",strtotime("01 ".$from_to_month_arr[1])); 
	
	//$filter_qry .= " AND i.DueDate between '$due_date_start' AND '$due_date_end' ";
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	$filter_qry .= " AND a.month_date >= '$from_date' AND a.month_date <= '$to_date' ";
} else {
	// if due date is not selected then by default last one month data will show
	////$due_date_start = date("Y-m-d", strtotime(" - 1 month"));
	////$due_date_end = date("Y-m-d");
	//$filter_qry .= " AND i.DueDate >= '$due_date_start' AND i.DueDate <= '$due_date_end' ";
	////$filter_qry .= " AND a.month_date >= '$from_date' AND a.month_date <= '$to_date' ";
}

$_POST['columns'][2]['search']['value'] = "";
$_POST['columns'][2]['searchable'] = false;

	
$sql = "SELECT
			a.country_code,
			a.postal_code,
			YEAR(a.month_date) AS `year`,
			MONTH(a.month_date) AS `month`,
			a.avg_temp,
			a.min_temp,
			a.max_temp,
			a.base_temp,
			a.hdd,
			a.cdd
		FROM
			monthly_historical_weather AS a
		WHERE
			1=1 $filter_qry
			
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
