<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

if(checkpermission($mysqli,63)==false) die("<h5 style='padding-top:30px;' align='center'>Permission Denied! Please contact Vervantis.</h5>");

$user_one=$_SESSION['user_id'];
$c_id=$_SESSION['company_id'];


// DB table to use
$db='ubm_exceptions';
$table = 'mapExceptions';

// Table's primary key
$primaryKey = 'ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`ID`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ID', 'dbnam' => $table ),
		array( 'db' => '`ClientID`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ClientID', 'dbnam' => $table ),
		array( 'db' => '`ClientName`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ClientName', 'dbnam' => $table ),
		array( 'db' => '`ExceptionID`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionID', 'dbnam' => $table ),
		array( 'db' => '`SiteNumber`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteNumber', 'dbnam' => $table ),
		array( 'db' => '`VendorName`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'VendorName', 'dbnam' => $table),
		array( 'db' => '`AccountNumber`',  'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'AccountNumber', 'dbnam' => $table ),
		array( 'db' => '`ServiceType`',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ServiceType', 'dbnam' => $table ),
		array( 'db' => '`ExceptionDescription`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionDescription', 'dbnam' => $table ),
		array( 'db' => '`Resolution`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Resolution', 'dbnam' => $table ),
		array( 'db' => '`InvoiceAmount`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'InvoiceAmount', 'dbnam' => $table ),
		array( 'db' => '`CreatedDate`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'CreatedDate', 'dbnam' => $table ),
		array( 'db' => '`ModifiedDate`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ModifiedDate', 'dbnam' => $table ),
		array( 'db' => '`Priority`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Priority', 'dbnam' => $table )
	);
	
	/*$columns = array(
		array( 'db' => '`ClientID`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ClientID', 'dbnam' => $table ),
		array( 'db' => '`ClientName`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ClientName', 'dbnam' => $table ),
		array( 'db' => '`EntityID`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'EntityID', 'dbnam' => $table ),
		array( 'db' => '`SourceID`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SourceID', 'dbnam' => $table ),
		array( 'db' => '`ExceptionID`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionID', 'dbnam' => $table),
		array( 'db' => '`Priority`',  'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Priority', 'dbnam' => $table ),
		array( 'db' => '`VendorName`',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'VendorName', 'dbnam' => $table ),
		array( 'db' => '`AccountNumber`',     'dt' => 7,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'AccountNumber', 'dbnam' => $table ),
		array( 'db' => '`SiteNumber`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteNumber', 'dbnam' => $table ),
		array( 'db' => '`InvoiceID`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'InvoiceID', 'dbnam' => $table ),
		array( 'db' => '`ServiceType`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ServiceType', 'dbnam' => $table ),
		array( 'db' => '`DueDate`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DueDate', 'dbnam' => $table ),
		array( 'db' => '`ExceptionType`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionType', 'dbnam' => $table ),
		array( 'db' => '`ExceptionDescription`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionDescription', 'dbnam' => $table ),
		array( 'db' => '`Resolution`',     'dt' => 14,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'Resolution', 'dbnam' => $table ),
		array( 'db' => '`CreatedDate`',     'dt' => 15,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'CreatedDate', 'dbnam' => $table ),
		array( 'db' => '`InvoiceAmount`',     'dt' => 16,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'InvoiceAmount', 'dbnam' => $table ),
		array( 'db' => '`EnteredDate`',     'dt' => 17,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'EnteredDate', 'dbnam' => $table ),
		array( 'db' => '`NotesDescription`',     'dt' => 18,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'NotesDescription', 'dbnam' => $table ),
		array( 'db' => '`ResolvedBy`',     'dt' => 19,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ResolvedBy', 'dbnam' => $table ),
		array( 'db' => '`ReminderDate`',     'dt' => 20,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ReminderDate', 'dbnam' => $table ),
		array( 'db' => '`SiteName`',     'dt' => 21,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteName', 'dbnam' => $table ),
		array( 'db' => '`SiteAddress`',     'dt' => 22,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteAddress', 'dbnam' => $table ),
		array( 'db' => '`SiteState`',     'dt' => 23,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteState', 'dbnam' => $table ),
		array( 'db' => '`SummaryAccount`',     'dt' => 24,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SummaryAccount', 'dbnam' => $table ),
		array( 'db' => '`VendorInvoiceNumber`',     'dt' => 25,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'VendorInvoiceNumber', 'dbnam' => $table ),
		array( 'db' => '`NotesFlag`',     'dt' => 26,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'NotesFlag', 'dbnam' => $table ),
		array( 'db' => '`ErrorID`',     'dt' => 27,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ErrorID', 'dbnam' => $table ),
		array( 'db' => '`MeterNumber`',     'dt' => 28,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'MeterNumber', 'dbnam' => $table ),
		array( 'db' => '`MeterLocation`',     'dt' => 29,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'MeterLocation', 'dbnam' => $table ),
		array( 'db' => '`ModifiedDate`',     'dt' => 30,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ModifiedDate', 'dbnam' => $table ),
		array( 'db' => '`NotifyDate`',     'dt' => 31,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'NotifyDate', 'dbnam' => $table ),
		array( 'db' => '`ResolvedDate`',     'dt' => 32,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ResolvedDate', 'dbnam' => $table ),
		array( 'db' => '`InvoiceStatus`',     'dt' => 33,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'InvoiceStatus', 'dbnam' => $table ),
		array( 'db' => '`PaidDate`',     'dt' => 34,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'PaidDate', 'dbnam' => $table ),
		array( 'db' => '`ScanDate`',     'dt' => 35,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ScanDate', 'dbnam' => $table ),
		array( 'db' => '`ServiceStartDate`',     'dt' => 36,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ServiceStartDate', 'dbnam' => $table ),
		array( 'db' => '`ServiceEndDate`',     'dt' => 37,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ServiceEndDate', 'dbnam' => $table ),
		array( 'db' => '`ExceptionGroup`',     'dt' => 38,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ExceptionGroup', 'dbnam' => $table ),
		array( 'db' => '`CreatedDate2`',     'dt' => 39,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'CreatedDate2', 'dbnam' => $table ),
		array( 'db' => '`DateModified2`',     'dt' => 40,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DateModified2', 'dbnam' => $table ),
		array( 'db' => '`SLA`',     'dt' => 41,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SLA', 'dbnam' => $table ),
		array( 'db' => '`DisputeDescription`',     'dt' => 42,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DisputeDescription', 'dbnam' => $table ),
		array( 'db' => '`DisputeNotes`',     'dt' => 43,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DisputeNotes', 'dbnam' => $table ),
		array( 'db' => '`CreditNotes`',     'dt' => 44,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'CreditNotes', 'dbnam' => $table ),
		array( 'db' => '`AmountDisputed`',     'dt' => 45,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'AmountDisputed', 'dbnam' => $table )
	);*/

	/*$columns = array(
		//array( 'db' => '`c`.`company_name`', 'dt' => 0, 'field' => 'company_name' ),
		array( 'db' => '`c`.`company_name`', 'dt' => 0, 'formatter' => function( $d, $row ) {
$tempp='<a href="javascript:void(0);"';
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){$tempp=$tempp.' onmouseover="showversion(s.id,\"company\",\"company_name\",\"'.$ts.'\",\"list-sites\",\"assets/ajax/list-sites.php?load=true\")" id="'.$ts.'" class="showversion-link"';}
$tempp=$tempp.' onclick="load_details({$primaryKey})"> '.$d.'</a>';
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){$tempp=$tempp.'<a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p'.$ts.'"></a>';};
                return $tempp;},
            'field' => 'company_name' ),
		array( 'db' => '`s`.`division`',  'dt' => 1, 'field' => 'division' ),
		array( 'db' => '`s`.`country`',   'dt' => 2, 'field' => 'country' ),
		array( 'db' => '`s`.`state`',     'dt' => 3, 'field' => 'state'),
		array( 'db' => '`s`.`city`',     'dt' => 4, 'field' => 'city' ),
		array( 'db' => '`s`.`site_number`',     'dt' => 5, 'field' => 'site_number' ),
		array( 'db' => '`s`.`site_name`',     'dt' => 6, 'field' => 'site_name' ),
		array( 'db' => '`s`.`site_status`',     'dt' => 7, 'field' => 'site_status' )*//*,
		array( 'db' => '`u`.`start_date`', 'dt' => 6, 'field' => 'start_date', 'formatter' => function( $d, $row ) {
																		return date( 'jS M y', strtotime($d));
																	}),
		array('db'  => '`u`.`salary`',     'dt' => 7, 'field' => 'salary', 'formatter' => function( $d, $row ) {
																	return '$'.number_format($d);
																})*/
	//);
}

/*
$tempp='<a href="javascript:void(0);"';
$tempp=$tempp.if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){' onmouseover="showversion(\"s.id\",\"company\",\"company_name\",\"'.$ts.'\",\"list-sites\",\"assets/ajax/list-sites.php?load=true\")" id="'.$ts.'" class="showversion-link"'};
$tempp=$tempp.' onclick="load_details(\"s.id\")"> '.$d.'</a>';
$tempp=$tempp.if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){'<a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p'.$ts.'"></a>'};*/






$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => $db,
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/ssp_resolvedexp.inc.php' );

$joinQuery = "FROM ".$table;
$extraWhere = (($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" `ClientID`='".$c_id."' ");
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
?>
