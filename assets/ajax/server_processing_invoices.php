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
$table = 'invoiceIndex';

// Table's primary key
$primaryKey = 'invoice_number';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => 'invoice_number',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'invoice_number', 'dbnam' => 'invoiceIndex' ),
		array( 'db' => 'company_name',   'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'invoiceIndex' ),
		array( 'db' => 'capturis_company_id',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'capturis_company_id', 'dbnam' => 'invoiceIndex'),
		array( 'db' => 'capturis_vendor_id',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'capturis_vendor_id', 'dbnam' => 'invoiceIndex' ),
		array( 'db' => 'account_number',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'account_number', 'dbnam' => 'invoiceIndex' ),
		array( 'db' => 'totalDue',  'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'totalDue', 'dbnam' => 'invoiceIndex' ),
		array( 'db' => 'importDate', 'dt' => 6,  'formatter' => function( $d, $row ) {return ($d !=""?date( 'm/d/Y', strtotime($d)):"");},'field' => 'importDate', 'dbnam' => 'invoiceIndex' )
	);
}


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
require('../includes/ssp_invoices.inc.php' );

$joinQuery = "FROM invoiceIndex";
$extraWhere = ((($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?"invoiceIndex.company_id != 9":"");
$groupBy = "";
//$having = "`u`.`salary` >= 140000";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>