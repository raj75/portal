<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");



// DB table to use
$table = 'vendor_rates';

// Table's primary key
$primaryKey = 'ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => 'ID', 'dt' => 0,  'formatter' => function( $d, $row ) {return $d;},'field' => 'ID', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'rate_id', 'dt' => 1,  'formatter' => function( $d, $row ) {return $d;},'field' => 'rate_id', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'rate_name',   'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'rate_name', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'vendor_name',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'vendor_name', 'dbnam' => 'vendor_rates'),
		array( 'db' => 'service_group',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'service_group', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'capturis_vendor_name',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'capturis_vendor_name', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'capturis_rate_name',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'capturis_rate_name', 'dbnam' => 'vendor_rates' ),
		array( 'db' => 'importDate',  'dt' => 7,  'formatter' => function( $d, $row ) {return ($d !=""?date( 'm/d/Y', strtotime($d)):"");}, 'field' => 'importDate', 'dbnam' => 'vendor_rates' )
	);
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

// require( 'ssp.class.php' );
require('../includes/ssp_vendorratesexp.inc.php' );

$joinQuery = "FROM vendor_rates";
$extraWhere = "";
$groupBy = "";
//$having = "`u`.`salary` >= 140000";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>