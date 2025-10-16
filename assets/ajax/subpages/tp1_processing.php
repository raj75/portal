<?php //require_once("inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");



// DB table to use
$table = 'clearing_code';

// Table's primary key
$primaryKey = 'exchange';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => 'Description', 'dt' => 0,  'formatter' => function( $d, $row ) {return $d;},'field' => 'Description', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'exchange',  'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'exchange', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'clearing_code',   'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'clearing_code', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'GROUP',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'GROUP', 'dbnam' => 'clearing_code'),
		array( 'db' => 'status',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'contract_type',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'contract_type', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'date_code_min',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_code_min', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'date_code_max',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_code_max', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'max_date',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'max_date', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'contracts',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'contracts', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'spot_contract',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'spot_contract', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'spot_price',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'spot_price', 'dbnam' => 'clearing_code' ),
		array( 'db' => '12_strip',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => '12_strip', 'dbnam' => 'clearing_code' ),
		array( 'db' => 'region',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'region', 'dbnam' => 'clearing_code' )
	);
} 


$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => 'ubm_ice',
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../../includes/ssptp1.inc.php' );

$joinQuery = "FROM ".$table." WHERE `status`='Active' ORDER BY commodity DESC,commodity ASC,contract_type,description";
$extraWhere = "";
$groupBy = "";
//$having = "`u`.`salary` >= 140000";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>
