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
$table = 'client_information';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'id', 'dt' => 'id' ),
	array( 'db' => 'client', 'dt' => 'client' ),	
    array( 'db' => 'primary_contact_name', 'dt' => 'primary_contact_name' ),
    array( 'db' => 'primary_contact_email',  'dt' => 'primary_contact_email' ),
    array( 'db' => 'secondary_contact_name',   'dt' => 'secondary_contact_name' ),
    array( 'db' => 'secondary_contact_email', 'dt' => 'secondary_contact_email' ),
	array( 'db' => 'tax_id', 'dt' => 'tax_id' ),
	array( 'db' => 'tax_id_alt', 'dt' => 'tax_id_alt' ),
	array( 'db' => 'address', 'dt' => 'address' ),
	array( 'db' => 'ap_name', 'dt' => 'ap_name' ),
	array( 'db' => 'ap_phone', 'dt' => 'ap_phone' ),
	array( 'db' => 'ap_email', 'dt' => 'ap_email' ),
	array( 'db' => 'utility_contact_name', 'dt' => 'utility_contact_name' ),
	array( 'db' => 'utility_phone', 'dt' => 'utility_phone' ),
	array( 'db' => 'utility_email', 'dt' => 'utility_email' ),
	array( 'db' => 'capturis_address', 'dt' => 'capturis_address' ),
	array( 'db' => 'capturis_email', 'dt' => 'capturis_email' ),
	array( 'db' => 'notes', 'dt' => 'notes' ),
   
);


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

//$whereall = "deleted = 0";
$whereall = "";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
);