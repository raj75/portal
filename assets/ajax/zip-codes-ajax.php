<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

unset($_SESSION['rows']);
// DB table to use
$table = 'ziputility';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'ID', 'dt' => 'ID' ),
    array( 'db' => 'postalcode', 'dt' => 'postalcode' ),
    array( 'db' => 'country',  'dt' => 'country' ),
    array( 'db' => 'state',   'dt' => 'state' ),
    array( 'db' => 'county', 'dt' => 'county', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'county');
														} ),
	array( 'db' => 'place', 'dt' => 'place', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'place');
														} ),
	array( 'db' => 'latitude', 'dt' => 'latitude', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'latitude');
														} ),
	array( 'db' => 'longitude', 'dt' => 'longitude', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'longitude');
														} ),
	array( 'db' => 'timezone', 'dt' => 'timezone', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'timezone');
														} ),
	array( 'db' => 'area_codes', 'dt' => 'area_codes', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'area_codes');
														} ),
	array( 'db' => 'utility_name', 'dt' => 'utility_name', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'utility_name');
														} ),
	array( 'db' => 'ownership', 'dt' => 'ownership', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'ownership');
														} ),
	
	array( 'db' => 'type', 'dt' => 'type', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'type');
														} ),
	array( 'db' => 'decommissioned', 'dt' => 'decommissioned', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'decommissioned');
														} ),
	array( 'db' => 'acceptable_cities', 'dt' => 'acceptable_cities', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'acceptable_cities');
														} ),
	array( 'db' => 'unacceptable_cities', 'dt' => 'unacceptable_cities', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'unacceptable_cities');
														} ),
	array( 'db' => 'world_region', 'dt' => 'world_region', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'world_region');
														} ),
	array( 'db' => 'irs_estimated_population_2015', 'dt' => 'irs_estimated_population_2015', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'irs_estimated_population_2015');
														} ),
	array( 'db' => 'zip2', 'dt' => 'zip2', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'zip2');
														} ),
	array( 'db' => 'eiaid', 'dt' => 'eiaid', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'eiaid');
														} ),
	
	array( 'db' => 'state2', 'dt' => 'state2', 'formatter' => 
														function( $d, $row ) {
															//return makehtml($d, $row, 'state2');
															return $d;
														} ),
	array( 'db' => 'Delivery', 'dt' => 'Delivery', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Delivery');
														} ),
	array( 'db' => 'Energy', 'dt' => 'Energy', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Energy');
														} ),
	array( 'db' => 'Bundled', 'dt' => 'Bundled', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Bundled');
														} ),
	
	
	/*
    array( 'db' => 'date_of_birth','dt' => 4,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    )
	*/
   
);


function makehtml($d, $row, $column ) {
	global $table;
	global $mysqli;
	//print_r($row);
	
	$id = $row['ID'];
	$ts=rand(1000,9000);
	//.$i.$j;
	
	$show_icon = '';
	
	// if ( isset($_SESSION['rows']) and is_array($_SESSION['rows'][$id]) ) {
		// echo "--session rows==";
		// print_r($_SESSION['rows']);
	// } else {
	
		$stmt = $mysqli->query("Select edited_value from audit_log where table_name='$table' and table_row_id='$id' ");
		if ($stmt->num_rows > 0) {
			//$_SESSION['rows'][$id] = [];
			$continue = 0;
			$editedvalueArr = [];
			while($row=$stmt->fetch_assoc()) {
				$editedvalue=$row['edited_value'];
				$editedvalarr=unserialize(base64_decode($editedvalue));
				$z=count($editedvalarr);
				
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and trim($editedvalarr[$i]["title"]) == trim($column)){
						//show icon
						$show_icon = '<i class="fa fa-reply" aria-hidden="true"></i>';
						$continue = 1;
						continue;
						//$_SESSION['rows'][$id] = $column;
					}
				}
				if ($continue==1) {$continue=0; continue;}
				//print_r($editedvalarr);
				//die();
				//$editedvalueArr['']=$row['edited_value'];
			}
		}
	//}
	
	/*
	return '<a href="javascript:void(0);" onmouseover="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$d.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover-hover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>'.$show_icon.'
	';
	*/
	
	/*
	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover-hover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';
	*/
	
	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';
	
}

// SQL server connection information
$sql_details = array(
    'user' => USER,
    'pass' => PASSWORD,
    'db'   => DATABASE,
    'host' => HOST
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require_once '../includes/ssp_zipcodes.class.php'; 

$whereall = "deleted = 0";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
);