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
$table = 'startstop_status';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
		$columns = array(
			array( 'db' => '`ss`.`id`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'id', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`c`.`company_name`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'company' ),
			array( 'db' => '`up`.`firstname`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'firstname', 'dbnam' => 'user' ),
			/*array( 'db' => 'CONCAT(`up`.`firstname`, " ", `up`.`lastname`)', 'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'firstname', 'as' => 'FullName', 'dbnam' => 'user' ),*/

			array( 'db' => '`up`.`lastname`', 'dt' => 3,  'formatter' => function( $d, $row ) {return $d;},'field' => 'lastname', 'dbnam' => 'user' ),
			array( 'db' => '`ss`.`status`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`request_type`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'request_type', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_number`',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_number', 'dbnam' => 'startstop_status'),
			array( 'db' => '`ss`.`site_name`',  'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`utility_service_type`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'utility_service_type', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`vendor_name`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'vendor_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`account_number`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'account_number', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`date_requested`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_requested', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`status_date`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status_date', 'dbnam' => 'startstop_status' ),

			array( 'db' => '`ss`.`date_contacted`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_contacted', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`deposit`',     'dt' => 14,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'deposit', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`entity_name`',     'dt' => 15,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'entity_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`meter`',     'dt' => 16,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'meter', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_address1`',     'dt' => 17,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_address1', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_city`',     'dt' => 18,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_city', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_state`',     'dt' => 19,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_state', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_zip`',     'dt' => 20,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_zip', 'dbnam' => 'startstop_status' )
		);
	}else{
		$columns = array(
			array( 'db' => '`ss`.`id`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'id', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`status`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`request_type`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'request_type', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_number`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_number', 'dbnam' => 'startstop_status'),
			array( 'db' => '`ss`.`site_name`',  'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`utility_service_type`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'utility_service_type', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`vendor_name`',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'vendor_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`account_number`',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'account_number', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`date_requested`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_requested', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`status_date`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status_date', 'dbnam' => 'startstop_status' ),

			array( 'db' => '`ss`.`date_contacted`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'date_contacted', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`deposit`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'deposit', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`entity_name`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'entity_name', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`meter`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'meter', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_address1`',     'dt' => 14,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_address1', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_city`',     'dt' => 15,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_city', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_state`',     'dt' => 16,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_state', 'dbnam' => 'startstop_status' ),
			array( 'db' => '`ss`.`site_zip`',     'dt' => 17,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_zip', 'dbnam' => 'startstop_status' )
		);
	}
}



// SQL server connection information

//$db_username 	= 'root';
//$db_password 	= '7Rjfz0cDjsSc';
//$db_name 		= 'vervantis';
//$db_host 		= 'develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com';

$db_username 	= USER;
$db_password 	= PASSWORD;
$db_name 		= DATABASE;
$db_host 		= HOST;

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/sspsss.inc.php' );

$joinQuery = "startstop_status ss Left Join company c ON c.company_id=ss.company_id LEFT JOIN `user` up ON up.user_id = ss.added_by_user_id";
$extraWhere = (((isset($_GET["showdemo"]) and $_GET["showdemo"]==1) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2))?" c.company_id != 9":"").(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) ? " c.company_id= ".$_SESSION["company_id"]:"");
$groupBy = "ss.id";
//$having = "";

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy)
);
?>
