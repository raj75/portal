<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];



// DB table to use
$table = 'weekly_reports';

// Table's primary key
$primaryKey = 'ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`ID`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ID', 'dbnam' => 'vervantis' ),
		array( 'db' => '`report_name`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'report_name', 'dbnam' => 'vervantis' ),
		array( 'db' => '`report_type`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'report_type', 'dbnam' => 'vervantis' ),
		array( 'db' => 'DATE(`datetime`)',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'datetime', 'dbnam' => 'vervantis')
	);
}



$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => "vervantis",
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/sspweeklyreports.inc.php' );

$joinQuery = "FROM weekly_reports";
$extraWhere = "";
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
?>
