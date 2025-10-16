<?php
if (@$_GET['test']==1) {
print_r($_POST);
die(); 
}
require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

// // DB table to use
// $table = 'ziputility';
 
// // Table's primary key
// //$primaryKey = 'postalcode';
// $primaryKey = 'ID';
 
// // Array of database columns which should be read and sent back to DataTables.
// // The `db` parameter represents the column name in the database, while the `dt`
// // parameter represents the DataTables column identifier. In this case simple
// // indexes	

// $columns = array(
    // array( 'db' => 'ID', 'dt' => 'ID' ),
    // array( 'db' => 'postalcode', 'dt' => 'postalcode' ),
    // array( 'db' => 'country',  'dt' => 'country' ),
    // array( 'db' => 'state',   'dt' => 'state' ),
    // array( 'db' => 'county', 'dt' => 'county', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'county');
														// } ),
	// array( 'db' => 'place', 'dt' => 'place', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'place');
														// } ),
	// array( 'db' => 'latitude', 'dt' => 'latitude', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'latitude');
														// } ),
	// array( 'db' => 'longitude', 'dt' => 'longitude', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'longitude');
														// } ),
	// array( 'db' => 'timezone', 'dt' => 'timezone', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'timezone');
														// } ),
	// array( 'db' => 'area_codes', 'dt' => 'area_codes', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'area_codes');
														// } ),
	// array( 'db' => 'utility_name', 'dt' => 'utility_name', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'utility_name');
														// } ),
	// array( 'db' => 'ownership', 'dt' => 'ownership', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'ownership');
														// } ),
	
	// array( 'db' => 'type', 'dt' => 'type', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'type');
														// } ),
	// array( 'db' => 'decommissioned', 'dt' => 'decommissioned', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'decommissioned');
														// } ),
	// array( 'db' => 'acceptable_cities', 'dt' => 'acceptable_cities', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'acceptable_cities');
														// } ),
	// array( 'db' => 'unacceptable_cities', 'dt' => 'unacceptable_cities', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'unacceptable_cities');
														// } ),
	// array( 'db' => 'world_region', 'dt' => 'world_region', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'world_region');
														// } ),
	// array( 'db' => 'irs_estimated_population_2015', 'dt' => 'irs_estimated_population_2015', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'irs_estimated_population_2015');
														// } ),
	// array( 'db' => 'zip2', 'dt' => 'zip2', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'zip2');
														// } ),
	// array( 'db' => 'eiaid', 'dt' => 'eiaid', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'eiaid');
														// } ),
	
	// array( 'db' => 'state2', 'dt' => 'state2', 'formatter' => 
														// function( $d, $row ) {
															// //return makehtml($d, $row, 'state2');
															// return $d;
														// } ),
	// array( 'db' => 'Delivery', 'dt' => 'Delivery', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'Delivery');
														// } ),
	// array( 'db' => 'Energy', 'dt' => 'Energy', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'Energy');
														// } ),
	// array( 'db' => 'Bundled', 'dt' => 'Bundled', 'formatter' => 
														// function( $d, $row ) {
															// return makehtml($d, $row, 'Bundled');
														// } ),
	
// );


// function makehtml($d, $row, $column ) {
	// return $d;
// }

// // SQL server connection information
// $sql_details = array(
    // 'user' => USER,
    // 'pass' => PASSWORD,
    // 'db'   => DATABASE,
    // 'host' => HOST
// );
 
 
// /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 // * If you just want to use the basic configuration for DataTables with PHP
 // * server-side, there is no need to edit below this line.
 // */

// require_once '../includes/ssp_zipcodes.class.php';  

// //$whereall = "deleted = 1";
// $whereall = "deleted = 1";

// echo json_encode(
    // SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
// );


// DB table to use
$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'ID';

$sql_details = array(
	"type" => "Mysql",
	"user" => USER,
	"pass" => PASSWORD,
	"host" => HOST,
	"port" => "",
	"db"   => DATABASE,
	"dsn"  => "charset=utf8"
);

// DataTables PHP library and database connection
//require_once ('../datatables/DataTables.php');
require_once '../datatables/DataTables.php';


// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;


// Build our Editor instance and process the data coming from _POST
//Editor::inst( $db, $table, 'ziputility.ID' )
Editor::inst( $db, $table, 'ID' )
	->fields(
	    Field::inst( 'ziputility.ID' ),
		Field::inst( 'ziputility.postalcode' ),
		Field::inst( 'ziputility.country' ),
		Field::inst( 'ziputility.state' ),		
		Field::inst( 'ziputility.county' ),
		Field::inst( 'ziputility.place' ),
		Field::inst( 'ziputility.latitude' ),
		Field::inst( 'ziputility.longitude' ),
		Field::inst( 'ziputility.timezone' ),
		
		Field::inst( 'ziputility.delete_date'),
		Field::inst( 'ziputility.delete_by'),
		
		Field::inst( 'ziputility.area_codes' ),
		Field::inst( 'ziputility.utility_name' ),
		Field::inst( 'ziputility.ownership' ),
		
		Field::inst( 'ziputility.type' ),
		Field::inst( 'ziputility.decommissioned' ),
		Field::inst( 'ziputility.acceptable_cities' ),
		Field::inst( 'ziputility.unacceptable_cities' ),
		Field::inst( 'ziputility.world_region' ),
		Field::inst( 'ziputility.irs_estimated_population_2015' ),
		Field::inst( 'ziputility.zip2' ),
		Field::inst( 'ziputility.eiaid' ),
		Field::inst( 'ziputility.state2' ),
		Field::inst( 'ziputility.Delivery' ),
		Field::inst( 'ziputility.Energy' ),
		Field::inst( 'ziputility.Bundled' ),
		Field::inst( 'ziputility.deleted'),
		
		Field::inst( 'user.firstname'),
		Field::inst( 'user.lastname')
		
		
	)
	->where( 'ziputility.deleted', 1 )
	->leftJoin( 'user', 'user.user_id', '=', 'ziputility.delete_by' )
	->process( $_POST )
	->json();

