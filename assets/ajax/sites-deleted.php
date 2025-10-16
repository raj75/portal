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

	$stmt = $mysqli->query("Select firstname,lastname from user where user_id = $d ");
	if ($stmt and $stmt->num_rows > 0) {
		$row=$stmt->fetch_assoc();
		return $row['firstname']." ".$row['lastname'];
	}else return "";
}

// DB table to use
$table = 'sites';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){

	$columns = array(

	 array(
        'db' => 'id',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
		, 'field' => 'id', 'dbnam' => 'sites'
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
//require('../includes/ssp.inc.php' );

$site_status = $_POST['columns'][10]['search']['value'];

$status_qry = "";


if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == 'all') {
		$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and";
	} else if ($site_status == "Active") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == "Inactive") {
		$status_qry = " s.site_status='Inactive' and ";
	}

	$_POST['columns'][10]['search']['value'] = "";
	$_POST['columns'][10]['searchable'] = false;

}


$and_qry = " ";

if ( ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) AND $_SESSION['company_id']==1) {
	if (isset($_GET["showdemo"]) and $_GET["showdemo"]==1) {
		$and_qry .= " c.company_id != 9 and ";
	} else {
		$and_qry .= " ";
	}
} else if ($_SESSION["group_id"] != 1 AND $_SESSION["group_id"] != 2 AND $_SESSION['company_id']!=1) {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
} else {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
}


//$joinQuery = "FROM sites s,company c, user up";
//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=1 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");
//$groupBy = "s.site_number";
//$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'

$sql = "SELECT s.id, c.company_name, s.company_id, s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date , s.delete_by , s.delete_date FROM sites s inner join company c on s.company_id = c.company_id WHERE $status_qry  $and_qry s.deleted=1 ";

	
	
$table = <<<EOT
 (
    $sql
 ) temp
EOT;

require_once '../includes/ssp.class.custom.php';

echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);
/*
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
*/
?>
