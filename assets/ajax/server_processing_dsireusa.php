<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];



// DB table to use
$table = 'program';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`pg`.`id`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'id', 'dbnam' => 'program' ),
		array( 'db' => 'pg.`name`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'name', 'dbnam' => 'pg' ),
		array( 'db' => '`s`.`abbreviation`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'abbreviation', 'dbnam' => 'state' ),
		array( 'db' => '`pgct`.`name`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'name', 'dbnam' => 'program_category' ),
		array( 'db' => '`pt`.`name`', 'dt' => 4,  'formatter' => function( $d, $row ) {return $d;},'field' => 'name', 'dbnam' => 'program_type' ),
		array( 'db' => 'DATE(`pg`.`updated_ts`)',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'updated_ts', 'dbnam' => 'program'),
		array( 'db' => 'DATE(`pg`.`created_ts`)',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'created_ts', 'dbnam' => 'program')
	);
}



$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => "dsireusa",
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/sspdsireusa.inc.php' );

$joinQuery = "FROM program pg, program_category pgct, state s, program_type pt";
$extraWhere = "pg.program_category_id=pgct.id and s.id=pg.state_id and pg.program_type_id=pt.id";
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
?>
