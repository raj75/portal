<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

//print_r($_SESSION);

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");


//$mysqli = new mysqli("localhost","my_user","my_password","my_db");

// DB table to use
$table = 'ubm_database.tblSites';

// Table's primary key
$primaryKey = 'SiteID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
		$columns = array(
		array(
			'db' => 'SiteID',
			'dt' => 'DT_RowId',
			'formatter' => function( $d, $row ) {
				// Technically a DOM id cannot start with an integer, so we prefix
				// a string. This can also be useful if you have multiple tables
				// to ensure that the id is unique with a different prefix
				return 'row_'.$d;
			}
			, 'field' => 'SiteID', 'dbnam' => 'ubm_database.tblSites'
		),
		array( 'db' => 'company_name',     'dt' => 'company_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'vervantis.company' ),
		array( 'db' => 'SiteNumber',     'dt' => 'SiteNumber',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteNumber', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteName', 'dt' => 'SiteName',  'formatter' => function( $d, $row ) {return $d;},'field' => 'SiteName', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteAddress1',     'dt' => 'SiteAddress1',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteAddress1', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteCity',     'dt' => 'SiteCity',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteCity', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteState', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteZip',     'dt' => 'SiteZip',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteZip', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteStatus',     'dt' => 'SiteStatus',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteStatus', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'company_id',     'dt' => 'company_id',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_id', 'dbnam' => 'vervantis.company' )
	);


}




$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => "ubm_database",
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
////require('../includes/ssp.inc.php' );

//print_r($_POST);
//die();



$site_status = @trim($_POST['columns'][8]['search']['value']);

$status_qry = array();

/*
if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == 'all') {
		$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
	} else if ($site_status == "Active") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == "Inactive") {
		$status_qry = " s.site_status='Inactive' and ";
	}

	$_POST['columns'][8]['search']['value'] = "";
	$_POST['columns'][8]['searchable'] = false;

}
*/

if ( isset($_POST['search']) and !empty($_POST['search']['value']) ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry[] = ' (s.SiteStatus=1 OR s.SiteStatus IS NULL) ';
	} else if ($site_status == 'all') {
		//$status_qry = "";//" (s.SiteStatus = 'Active' OR s.SiteStatus = 'Inactive') and";
	} else if ($site_status == "Active") {
		$status_qry[] = ' (s.SiteStatus=1 or s.SiteStatus IS NULL) ';
	} else if ($site_status == "Inactive") {
		$status_qry[] = ' (s.SiteStatus="Inactive" or s.SiteStatus=0) ';
	}

	$_POST['columns'][8]['search']['value'] = "";
	$_POST['columns'][8]['searchable'] = false;

}


$joinQuery = "FROM ubm_database.tblSites s,vervantis.company c";
//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=0 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");

/*
(
($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?
( (isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id")
*/

/*
$and_qry = " ";

if ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) {
	if (isset($_GET["showdemo"]) and $_GET["showdemo"]==1) {
		$and_qry .= " c.company_id != 9 and ";
	} else {
		$and_qry .= " ";
	}
} else {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
}
*/

$and_qry = array();
 
if ( ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) AND $_SESSION['company_id']==1) {
	if (isset($_GET["showdemo"]) and $_GET["showdemo"]==1) {
		$and_qry[]= " s.ClientID != 9 ";
	} else {
		//$and_qry .= " ";
	}
} else if ($_SESSION["group_id"] != 1 AND $_SESSION["group_id"] != 2 AND $_SESSION['company_id']!=1) {
	$and_qry[]= " s.ClientID = ".$_SESSION['company_id']." ";
} else {
	$and_qry[]= " s.ClientID = ".$_SESSION['company_id']." ";
}

$and_qry[]= " s.DeleteStatus=0 AND s.SiteNumber <> '' ";
 
//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=0 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");

$extraWhere = "1";

$groupBy = "s.SiteNumber";
$having = "";

$subsql="";	
if(count($status_qry) or count($and_qry)){
	$resultarr= array_filter(array_merge($status_qry, $and_qry));
	if(count($resultarr)) $subsql=implode(" and ",$resultarr);
}
	
	$sql= 'SELECT
	s.SiteID,
	c.company_name, 	
	s.SiteNumber, 
	s.SiteName, 
	s.SiteAddress1, 
	s.SiteCity, 
	s.SiteState, 
	s.SiteZip,  
  IF ((s.SiteStatus IS NULL OR s.SiteStatus = 1), "Active","Inactive") AS SiteStatus,
	s.SiteActiveDate AS `Active Date`,
	c.company_id
FROM
	ubm_database.tblSites AS s
INNER JOIN
	vervantis.company AS c
ON 
	s.ClientID = c.company_id WHERE '.$subsql;

	/*$sql= 'SELECT
	s.SiteID,
	c.company_name, 	
	s.SiteNumber, 
	s.SiteName, 
	s.SiteAddress1, 
	s.SiteCity, 
	s.SiteState, 
	s.SiteZip, 
	IFNULL(s.SiteStatus, "Active") AS `SiteStatus`, 
	s.SiteActiveDate AS `Active Date`
FROM
	ubm_database.tblSites AS s
INNER JOIN
	vervantis.company AS c
ON 
	s.ClientID = c.company_id WHERE '.$subsql;*/
	//echo $sql;die();
    	
$table = <<<EOT
 (
    $sql
 ) temp
EOT;
//if($user_one==1) echo $table;
//if($_SESSION['group_id'] == 1){echo $sql;die();}
//echo $sql;die();

//require_once '../includes/ssp.class.custom.php';
require('../includes/ssp.inctesting.php' );
 
// new code end --------------------------------

//$whereall = "deleted = 0";

/*
echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);
*/




echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);

/*
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns)
);
*/
die();

