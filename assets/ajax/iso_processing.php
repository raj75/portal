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
$table = 'iso';

// Table's primary key
$primaryKey = 'NODE_ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => 'ISO', 'dt' => 0,  'formatter' => function( $d, $row ) {return $d;},'field' => 'ISO', 'dbnam' => 'iso' ),
		array( 'db' => 'NODE_ID',  'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'NODE_ID', 'dbnam' => 'iso' ),
		array( 'db' => 'OPR_DT',   'dt' => 2,  'formatter' => function( $d, $row ) {return date( 'm/d/Y', strtotime($d));}, 'field' => 'OPR_DT', 'dbnam' => 'iso' ),
		array( 'db' => 'OPR_HR',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'OPR_HR', 'dbnam' => 'iso'),
		array( 'db' => 'LMP',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'LMP', 'dbnam' => 'iso' ),
		array( 'db' => 'MCC',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'MCC', 'dbnam' => 'iso' ),
		array( 'db' => 'MCE',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'MCE', 'dbnam' => 'iso' ),
		array( 'db' => 'MCL',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'MCL', 'dbnam' => 'iso' )
	);
}


$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => 'iso',
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/sspiso.inc.php' );

//$joinQuery = "FROM iso ORDER BY OPR_DT,OPR_HR";
$joinQuery = "FROM iso";
$extraWhere = "";
$groupBy = "";
//$having = "`u`.`salary` >= 140000";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns)
);
?>
