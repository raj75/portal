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

function delete_by($d) {
	if(empty($d)) return "";
	global $mysqli;

	$stmt = $mysqli->query("Select firstname,lastname from vervantis.user where user_id = $d ");
	if ($stmt and $stmt->num_rows > 0) {
		$row=$stmt->fetch_assoc();
		return $row['firstname']." ".$row['lastname'];
	}else return "";
}

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
		array( 'db' => 'SiteName',     'dt' => 'SiteName',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteName', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteAddress1',  'dt' => 'SiteAddress1',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteAddress1', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'DeleteStatus',     'dt' => 'DeleteStatus',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DeleteStatus', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'DeletedDate',     'dt' => 'DeletedDate',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'DeletedDate', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteCity',     'dt' => 'SiteCity',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteCity', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteState',     'dt' => 'SiteState',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteState', 'dbnam' => 'ubm_database.tblSites'),
		array( 'db' => 'SiteZip',  'dt' => 'SiteZip',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteZip', 'dbnam' => 'ubm_database.tblSites' ),
		array( 'db' => 'SiteStatus',     'dt' => 'SiteStatus',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteStatus', 'dbnam' => 'ubm_database.tblSites' )
	);	
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		array_push($columns,array( 'db' => 'SiteActiveDate',     'dt' => 'SiteActiveDate',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'SiteActiveDate', 'dbnam' => 'ubm_database.tblSites' ));
	}
}

/*
	 array(
        'db' => 'SiteID',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
		, 'field' => 'SiteID', 'dbnam' => 'tblSites'
    ),

		array( 'db' => 'company_id',     'dt' => 'company_id',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_id', 'dbnam' => 'company' ),

		array( 'db' => 'company_name',     'dt' => 'company_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'company' ),
		array( 'db' => 'site_number',     'dt' => 'site_number',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_number', 'dbnam' => 'sites' ),
		array( 'db' => 'site_name',     'dt' => 'site_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_name', 'dbnam' => 'sites' ),
		array( 'db' => 'service_address1', 'dt' => 'service_address1',  'formatter' => function( $d, $row ) {return $d;},'field' => 'service_address1', 'dbnam' => 'sites' ),
		array( 'db' => 'city',     'dt' => 'city',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'city', 'dbnam' => 'sites' ),
		array( 'db' => 'state',     'dt' => 'state',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'state', 'dbnam' => 'sites'),
		array( 'db' => 'postal_code',  'dt' => 'postal_code',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'postal_code', 'dbnam' => 'sites' ),
		array( 'db' => 'site_status',     'dt' => 'site_status',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_status', 'dbnam' => 'sites' ),
		array( 'db' => 'delete_by',     'dt' => 'delete_by',  'formatter' => function( $d, $row ) {return delete_by($d);}, 'field' => 'delete_by', 'dbnam' => 'sites' ),
		array( 'db' => 'delete_date',     'dt' => 'delete_date',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'delete_date', 'dbnam' => 'sites' ),
	);
*/

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
//require('../includes/ssp.inc.php' );

$site_status = $_POST['columns'][10]['search']['value'];
$site_status = $_POST['columns'][8]['search']['value'];

$status_qry = array();


if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry[] = " (s.SiteStatus='Active' OR s.SiteStatus IS NULL) ";
	} else if ($site_status == 'all') {
		//$status_qry = "";//" (s.SiteStatus = 'Active' OR s.SiteStatus = 'Inactive') and";
	} else if ($site_status == "Active" || $site_status == "NULL" || empty($site_status)) {
		$status_qry[] = "  (s.SiteStatus='Active' or  s.SiteStatus=1 or s.SiteStatus IS NULL)  ";
	} else if ($site_status == "Inactive") {
		$status_qry[] = "(s.SiteStatus='Inactive' or s.SiteStatus=0) ";
	}

	$_POST['columns'][8]['search']['value'] = "";
	$_POST['columns'][8]['searchable'] = false;

}


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

$and_qry[]="s.DeleteStatus =1";

$joinQuery = "FROM ubm_database.tblSites s,vervantis.company c";
$extraWhere = "1";

$groupBy = "s.SiteNumber";
$having = "";
//$joinQuery = "FROM sites s,company c, user up";
//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=1 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");
//$groupBy = "s.site_number";
//$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'

//$sql = "SELECT s.id, c.company_name, s.company_id, s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date , s.delete_by , s.delete_date FROM sites s inner join company c on s.company_id = c.company_id WHERE $status_qry  $and_qry s.deleted=1 ";

$subsql="";	
if(count($status_qry) or count($and_qry)){
	$resultarr= array_filter(array_merge($status_qry, $and_qry));
	if(count($resultarr)) $subsql=implode(" and ",$resultarr);
}


		$sql= "SELECT
	s.SiteID,
	c.company_name, 
	s.ClientID AS company_id, 
	s.SiteNumber, 
	s.SiteName, 
	s.SiteAddress1, 
	s.SiteCity, 
	s.SiteState, 
	s.SiteZip, 
IF ((s.SiteStatus IS NULL OR s.SiteStatus = 1), 'Active','Inactive') AS SiteStatus,
	s.SiteActiveDate,
	s.DeleteStatus,
	s.DeletedDate
FROM
	ubm_database.tblSites AS s
INNER JOIN
	vervantis.company AS c
ON 
	s.ClientID = c.company_id WHERE ".$subsql;
	
	
$table = <<<EOT
 (
    $sql
 ) temp
EOT;

require_once '../includes/ssp.class.custom.php';
//require('../includes/ssp.inctesting.php' );

echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
	//SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
/*
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
*/
?>
