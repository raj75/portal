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
$table = 'accounts';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'ID', 'dt' => 'ID' ),
	array( 'db' => 'invoice_source', 'dt' => 'invoice_source' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'invoice_source');
														} ),	
    array( 'db' => 'company_id', 'dt' => 'company_id' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'company_id');
														} ),
	array( 'db' => 'site_number', 'dt' => 'site_number' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'site_number');
														} ),
	array( 'db' => 'site_inactive_date', 'dt' => 'site_inactive_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'site_inactive_date');
														} ),
	array( 'db' => 'vendor_id', 'dt' => 'vendor_id' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_id');
														} ),
	array( 'db' => 'vendor_name', 'dt' => 'vendor_name' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_name');
														} ),
	array( 'db' => 'service_group_id', 'dt' => 'service_group_id' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'service_group_id');
														} ),
	array( 'db' => 'service_group', 'dt' => 'service_group' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'service_group');
														} ),
	array( 'db' => 'account_number1', 'dt' => 'account_number1' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'account_number1');
														} ),
	array( 'db' => 'account_number2', 'dt' => 'account_number2' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'account_number2');
														} ),
	array( 'db' => 'account_number3', 'dt' => 'account_number3' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'account_number3');
														} ),
	array( 'db' => 'legacy_account_number', 'dt' => 'legacy_account_number' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'legacy_account_number');
														} ),
	array( 'db' => 'service_point_location', 'dt' => 'service_point_location' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'service_point_location');
														} ),
	array( 'db' => 'name_key', 'dt' => 'name_key' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'name_key');
														} ),
	array( 'db' => 'account_active_date', 'dt' => 'account_active_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'account_active_date');
														} ),
	array( 'db' => 'account_inactive_date', 'dt' => 'account_inactive_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'account_inactive_date');
														} ),
	array( 'db' => 'meter_number', 'dt' => 'meter_number' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'meter_number');
														} ),
	array( 'db' => 'meter_active_date', 'dt' => 'meter_active_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'meter_active_date');
														} ),
	array( 'db' => 'meter_inactive_date', 'dt' => 'meter_inactive_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'meter_inactive_date');
														} ),
	array( 'db' => 'rate_id', 'dt' => 'rate_id' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'rate_id');
														} ),
	array( 'db' => 'activity_date', 'dt' => 'activity_date' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'activity_date');
														} ),
	array( 'db' => 'meter_status', 'dt' => 'meter_status' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'meter_status');
														} ),
	array( 'db' => 'gl_code', 'dt' => 'gl_code' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'gl_code');
														} ),
	array( 'db' => 'gl_reference', 'dt' => 'gl_reference' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'gl_reference');
														} ),
	array( 'db' => 'gl_group', 'dt' => 'gl_group' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'gl_group');
														} ),
	array( 'db' => 'notes', 'dt' => 'notes' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'notes');
														} ),
	array( 'db' => 'importDate', 'dt' => 'importDate' , 'formatter' => 
														function( $d, $row ) {
															return makehtml($d, $row, 'importDate');
														} ),
   
);


function makehtml($d, $row, $column ) {
	//return $d;
	
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
 
//$table = 'accounts';
$and_qry = " ";

if ( ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) AND $_SESSION['company_id']==1) {
	$and_qry .= " ";
} else if ($_SESSION["group_id"] != 1 AND $_SESSION["group_id"] != 2 AND $_SESSION['company_id']!=1) {
	$and_qry .= " company_id = ".$_SESSION['company_id']." and ";
} else {
	$and_qry .= " company_id = ".$_SESSION['company_id']." and ";
}

$status_qry = "";

//$sql = "SELECT * FROM accounts WHERE $status_qry  $and_qry deleted=0 ";

/*$table = <<<EOT
 (
    $sql
 ) temp
EOT;*/

require_once '../includes/ssp_zipcodes.class.php'; 

$whereall = $and_qry."deleted = 0";
//$whereall = "vendor_id > 0";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
);