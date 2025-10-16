<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");
		
$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");



// DB table to use
$table = 'service_group';

// Table's primary key
$primaryKey = 'ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`ID`',     'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'ID', 'dbnam' => 'service_group' ),
		array( 'db' => '`service_group_id`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'service_group_id', 'dbnam' => 'service_group' ),
		array( 'db' => '`service_group`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'service_group', 'dbnam' => 'service_group' ),
		array( 'db' => '`capturis_service_group_id`', 'dt' => 3,  'formatter' => function( $d, $row ) {return $d;},'field' => 'capturis_service_group_id', 'dbnam' => 'service_group' ),
		array( 'db' => '`capturis_service_group`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'capturis_service_group', 'dbnam' => 'service_group' ),
		array( 'db' => '`importDate`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'importDate', 'dbnam' => 'service_group' )
	);
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		//array_push($columns,array( 'db' => '`deregulated`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'deregulated', 'dbnam' => 'service_group' ));
	}

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
	'db'   => DATABASE,
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('../includes/ssp_servicegroup.inc.php' );

$joinQuery = "FROM service_group";
$extraWhere = "";
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
?>