<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

//unset($_SESSION['rows']);
// DB table to use
$table = 'vendor';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'vendor_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'vendor_id', 'dt' => 'vendor_id' ),
	array( 'db' => 'capturis_vendor_id', 'dt' => 'capturis_vendor_id' ),	
    array( 'db' => 'vendor_name', 'dt' => 'vendor_name' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_name');
														} ),
	array( 'db' => 'capturis_vendor_name', 'dt' => 'capturis_vendor_name' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'capturis_vendor_name');
														} ),
	array( 'db' => 'vendor_abbreviation', 'dt' => 'vendor_abbreviation' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_abbreviation');
														} ),
	array( 'db' => 'vendor_altname1', 'dt' => 'vendor_altname1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_altname1');
														} ),
	array( 'db' => 'vendor_altname2', 'dt' => 'vendor_altname2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_altname2');
														} ),
	array( 'db' => 'vendor_altname3', 'dt' => 'vendor_altname3' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_altname3');
														} ),
	array( 'db' => 'vendor_altname4', 'dt' => 'vendor_altname4' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_altname4');
														} ),
	array( 'db' => 'vendor_altname5', 'dt' => 'vendor_altname5' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_altname5');
														} ),
	array( 'db' => 'service_group', 'dt' => 'service_group' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'service_group');
														} ),
	array( 'db' => 'service_group_id', 'dt' => 'service_group_id' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'service_group_id');
														} ),
	array( 'db' => 'deregulated', 'dt' => 'deregulated' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'deregulated');
														} ),
	array( 'db' => 'vendor_type', 'dt' => 'vendor_type' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_type');
														} ),
	array( 'db' => 'state', 'dt' => 'state' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'state');
														} ),
	array( 'db' => 'vendorAddr1', 'dt' => 'vendorAddr1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorAddr1');
														} ),
	array( 'db' => 'vendorAddr2', 'dt' => 'vendorAddr2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorAddr2');
														} ),
	array( 'db' => 'vendorCity', 'dt' => 'vendorCity' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorCity');
														} ),
	array( 'db' => 'vendorState', 'dt' => 'vendorState' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorState');
														} ),
	array( 'db' => 'vendorZip', 'dt' => 'vendorZip' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorZip');
														} ),
	array( 'db' => 'vendorCountry', 'dt' => 'vendorCountry' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorCountry');
														} ),
	array( 'db' => 'vendorPhoneNbr1', 'dt' => 'vendorPhoneNbr1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorPhoneNbr1');
														} ),
	array( 'db' => 'vendorPhoneNbr2', 'dt' => 'vendorPhoneNbr2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorPhoneNbr2');
														} ),
	array( 'db' => 'vendorPhoneNbr3', 'dt' => 'vendorPhoneNbr3' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorPhoneNbr3');
														} ),
	array( 'db' => 'vendorFaxNbr1', 'dt' => 'vendorFaxNbr1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorFaxNbr1');
														} ),
	array( 'db' => 'vendorFaxNbr2', 'dt' => 'vendorFaxNbr2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorFaxNbr2');
														} ),
	array( 'db' => 'vendorEmail1', 'dt' => 'vendorEmail1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorEmail1');
														} ),
	array( 'db' => 'VendorEmail2', 'dt' => 'VendorEmail2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'VendorEmail2');
														} ),
	array( 'db' => 'vendorWebpage1', 'dt' => 'vendorWebpage1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorWebpage1');
														} ),
	array( 'db' => 'vendorWebpage2', 'dt' => 'vendorWebpage2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendorWebpage2');
														} ),
	array( 'db' => 'importDate', 'dt' => 'importDate' ),
	
   
);


function makehtml($d, $row, $column ) {
	//return $d;
	
	global $table;
	global $mysqli;
	//print_r($row);
	
	$id = $row['vendor_id'];
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
//$whereall = "vendor_id > 0";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
);