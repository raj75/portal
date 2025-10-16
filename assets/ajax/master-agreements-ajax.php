<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

//print_r($_POST);
//die();

$user_one=$_SESSION["user_id"];

	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sql = "SELECT DISTINCT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id and u.user_id='".$user_one."'";
	}else{
		$sql = "SELECT DISTINCT ma.ClientID,ma.MasterID,ma.VendorID,ma.Status,ma.`Start Date`,ma.`End Date`,ma.Version,ma.`Reviewed By`,ma.Notes,v.vendor_name FROM master_agreements ma JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=ma.VendorID and ma.ClientID=c.company_id and c.company_id=u.company_id";
	}
	
$table = <<<EOT
 (
    $sql
 ) temp
EOT;

// DB table to use
//$table = 'ziputility';
 
// Table's primary key
$primaryKey = 'MasterID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
   array( 'db' => 'MasterID',        'dt' => 'MasterID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_contract_id">'.$d.'</a>';
														} ),
   array( 'db' => 'vendor_name', 		 'dt' => 'vendor_name', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Status', 		 'dt' => 'Status', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Start Date', 		 'dt' => 'Start_Date', 'formatter' => 
														function( $d, $row ) {
															if ((int) $d < 1) {
																$data = "01/01/2000";
															} else {
																$data = @date("m/d/Y",strtotime($d));
															}
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_start_date">'.$data.'</a>';
														} ),
   array( 'db' => 'End Date', 		 'dt' => 'End_Date', 'formatter' => 
														function( $d, $row ) {
															if ((int) $d < 1) {
																$data = date("m/d/Y");
															} else {
																$data = @date("m/d/Y",strtotime($d));
															}
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')" class="ar_end_date">'.$data.'</a>';
														} ),
   array( 'db' => 'Version', 		 'dt' => 'Version', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Reviewed By', 		 'dt' => 'Reviewed_By', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Notes', 		 'dt' => 'Notes', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} ),
   
);

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$columns[] = array( 'db' => 'ClientID', 		 'dt' => 'ClientID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} );
}

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$columns[] = array( 'db' => 'VendorID', 		 'dt' => 'VendorID', 'formatter' => 
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="loadmamenu('.$row['MasterID'].')">'.$d.'</a>';
														} );
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

require_once '../includes/ssp.class.custom.php'; 

//$whereall = "deleted = 0";

echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);