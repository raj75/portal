<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];



// DB table to use
$table = 'Power_Generation';

// Table's primary key
$primaryKey = 'Entity ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`Entity ID`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Entity ID', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Entity Name`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Entity Name', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Plant Name`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Plant Name', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Sector`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Sector', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Plant State`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Plant State', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Nameplate Capacity (MW)`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Nameplate Capacity (MW)', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Net Summer Capacity (MW)`',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Net Summer Capacity (MW)', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Net Winter Capacity (MW)`',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Net Winter Capacity (MW)', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Technology`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Technology', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Energy Source Code`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Energy Source Code', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Operating Month`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Operating Month', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Operating Year`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Operating Year', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Status`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Status', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Latitude`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Latitude', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Longitude`',     'dt' => 14,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Longitude', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Prime Mover Code`',     'dt' => 15,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Prime Mover Code', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Retirement Month`',     'dt' => 16,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Retirement Month', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Retirement Year`',     'dt' => 17,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Retirement Year', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Derate Year`',     'dt' => 18,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Derate Year', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Derate Month`',     'dt' => 19,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Derate Month', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Derate of Summer Capacity (MW)`',     'dt' => 20,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Derate of Summer Capacity (MW)', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Uprate Year`',     'dt' => 21,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Uprate Year', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Uprate Month`',     'dt' => 22,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Uprate Month', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Planned Uprate of Summer Capacity (MW)`',     'dt' => 23,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Planned Uprate of Summer Capacity (MW)', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`County`',     'dt' => 24,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'County', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Google Map`',     'dt' => 25,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Google Map', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Bing Map`',     'dt' => 26,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Bing Map', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Balancing Authority Code`',     'dt' => 27,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Balancing Authority Code', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Unit Code`',     'dt' => 28,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Unit Code', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Sector Name`',     'dt' => 29,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Sector Name', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Type`',     'dt' => 30,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Type', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Plant ID`',     'dt' => 31,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Plant ID', 'dbnam' => 'Power_Generation' ),
		array( 'db' => '`Generator ID`',     'dt' => 32,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Generator ID', 'dbnam' => 'Power_Generation' )
	);
}



$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => "usapowergeneration",
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/sspusapgen.inc.php' );

$joinQuery = "FROM Power_Generation";
$extraWhere = "";
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
?>
