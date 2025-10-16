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
$table = 'enrollment';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'ID', 'dt' => 'ID' ),
    array( 'db' => 'Commodity', 'dt' => 'Commodity' ),
    array( 'db' => 'State',  'dt' => 'State' ),
    array( 'db' => 'Utility',   'dt' => 'Utility' ),
    array( 'db' => 'ISO', 'dt' => 'ISO', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'ISO');
														} ),
	array( 'db' => 'Market status', 'dt' => 'Market status', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Market status');
														} ),
	array( 'db' => 'Account, POD ID, ESIID', 'dt' => 'Account, POD ID, ESIID', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Account, POD ID, ESIID');
														} ),
	array( 'db' => 'Prefix', 'dt' => 'Prefix', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Prefix');
														} ),
	array( 'db' => 'Name Key', 'dt' => 'Name Key', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Name Key');
														} ),
	array( 'db' => 'Meter Number', 'dt' => 'Meter Number', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Meter Number');
														} ),
	array( 'db' => 'LOA Required', 'dt' => 'LOA Required', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'LOA Required');
														} ),
	array( 'db' => 'Billing options', 'dt' => 'Billing options', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Billing options');
														} ),
	array( 'db' => 'Purchase of Receivables (POR)', 'dt' => 'Purchase of Receivables (POR)', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Purchase of Receivables (POR)');
														} ),
	
	array( 'db' => 'Consolidated billing option', 'dt' => 'Consolidated billing option', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Consolidated billing option');
														} ),
	array( 'db' => 'Lead Time for Enrolls/Drops', 'dt' => 'Lead Time for Enrolls/Drops', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Lead Time for Enrolls/Drops');
														} ),
	array( 'db' => 'Special Enrollment Requirements', 'dt' => 'Special Enrollment Requirements', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Special Enrollment Requirements');
														} ),
	array( 'db' => 'Days for ES response', 'dt' => 'Days for ES response', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Days for ES response');
														} ),
	array( 'db' => 'Comments', 'dt' => 'Comments', 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'Comments');
														} )
	
	
	/*
    array( 'db' => 'date_of_birth','dt' => 4,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    )
	*/
   
);


function makehtml($d, $row, $column ) {
	
	return $d;
	exit();
	
	
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
   //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
   SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);