<?php
//print_r($_POST);

require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

unset($_SESSION['rows']);

//print_r($_POST);
//die();
if ( $_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) {
	$site_status = $_POST['columns'][11]['search']['value'];
}else{
	$site_status = $_POST['columns'][10]['search']['value'];
}

$status_qry = "";
if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry = " and cm.Status='Active' ";
	} else if ($site_status == 'all') {
		$status_qry = " and (cm.Status = 'Active' OR cm.Status = 'Inactive') "; //not working
	} else if ($site_status == "Active") {
		$status_qry = " and cm.Status='Active' ";
	} else if ($site_status == "Inactive") {
		$status_qry = " and cm.Status='Inactive' ";
	}

	if ( $_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) {
		$_POST['columns'][11]['search']['value'] = "";
		$_POST['columns'][11]['searchable'] = false;
	}else{
		$_POST['columns'][10]['search']['value'] = "";
		$_POST['columns'][10]['searchable'] = false;
	}

}

$and_qry = "";

if ( $_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) {

	if ( !isset($_GET["showdemo"]) ) { // by default hide demo 1 and 2
		$and_qry .= " and c.company_id NOT IN (9,32) ";
	} else if ( isset($_GET["showdemo"]) and $_GET["showdemo"]==1 ) { // showdemo 1 mean hide demo 1 and 2
		$and_qry .= " and c.company_id NOT IN (9,32) ";
	} else if ( isset($_GET["showdemo"]) and $_GET["showdemo"]==0 ) { // showdemo 0 main show demo 1 and 2
		$and_qry .= "";
	}
}

// initated date between last 3 year and forward 3 year
$date_qry = "";
//$date_qry = " and ( `Initiated Date` >= (now() - interval 3 year) AND `Initiated Date` <= (now() + interval 3 year) )";

$user_one=$_SESSION["user_id"];

	//distinct
	//SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity  WHERE cm.Status='Active' and c.company_id NOT IN (9,32)

	//group by
	//SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity  WHERE cm.Status='Active' and c.company_id NOT IN (9,32) group by cm.ContractID

	// entollment
	//SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name, e.Utility FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity LEFT JOIN enrollment e ON e.ID = cm.VendorID  WHERE cm.Status='Active' and c.company_id NOT IN (9,32) group by cm.ContractID

	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		/*
		$sql = "SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,v.vendor_name as supplier,cm.State,vv.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN vendor vv JOIN service_group sg WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and u.user_id='".$user_one."' and vv.vendor_id=cm.VendorID and c.company_id=u.company_id and sg.service_group_id=cm.Commodity $status_qry $and_qry $date_qry";
		*/

		/*
		$sql = "SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name, e.Utility as vendor_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity LEFT JOIN enrollment e ON e.ID = cm.VendorID WHERE u.user_id='".$user_one."' $status_qry $and_qry $date_qry group by cm.ContractID";
		*/
		$sql = "SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name, cm.VendorID as vendor_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity WHERE u.user_id='".$user_one."' $status_qry $and_qry $date_qry group by cm.ContractID";

		//$sql = "SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,v.vendor_name as supplier,cm.State,vv.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN vendor vv JOIN service_group sg WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and u.user_id='".$user_one."' and vv.vendor_id=cm.VendorID and c.company_id=u.company_id and sg.service_group_id=cm.Commodity $status_qry $and_qry $date_qry";

	}else{

		/*
		$sql = "SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,v.vendor_name as supplier,cm.State,vv.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN vendor vv JOIN service_group sg WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and c.company_id=u.company_id and c.company_id=u.company_id and vv.vendor_id=cm.VendorID and sg.service_group_id=cm.Commodity $status_qry $and_qry $date_qry";
		*/

		//$sql = "SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,e.Utility as vendor_name,c.company_name FROM contracts cm JOIN user u JOIN company c JOIN service_group sg LEFT JOIN enrollment e ON e.ID=cm.VendorID WHERE cm.ClientID=c.company_id and c.company_id=u.company_id and c.company_id=u.company_id and sg.service_group_id=cm.Commodity $status_qry $and_qry $date_qry";

		/*
		$sql = "SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name, e.Utility as vendor_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity LEFT JOIN enrollment e ON e.ID = cm.VendorID WHERE 1=1 $status_qry $and_qry $date_qry group by cm.ContractID";
		*/
		$sql = "SELECT cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,c.company_name, cm.VendorID as vendor_name FROM contracts cm INNER JOIN company c ON cm.ClientID=c.company_id INNER JOIN user u ON c.company_id=u.company_id INNER JOIN service_group sg ON sg.service_group_id=cm.Commodity WHERE 1=1 $status_qry $and_qry $date_qry group by cm.ContractID";

		/*
		SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID as supplier,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,cm.State,e.Utility as vendor_name,c.company_name FROM contracts cm JOIN user u JOIN company c JOIN service_group sg LEFT JOIN enrollment e ON e.ID=cm.VendorID WHERE cm.ClientID=c.company_id and c.company_id=u.company_id and c.company_id=u.company_id and sg.service_group_id=cm.Commodity and cm.Status='Active' and c.company_id NOT IN (9,32)
		*/
		//echo $sql;

	}

	//echo $sql;

$table = <<<EOT
 (
    $sql
 ) temp
EOT;

// DB table to use
//$table = 'ziputility';

// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ContractID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
   array( 'db' => 'ContractID', 'dt' => 'ContractID', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')" class="ar_contract_id">'.$d.'</a>';
														} ),
   array( 'db' => 'State',        'dt' => 'State', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')" class="ar_state">'.$d.'</a>';
														} ),
   array( 'db' => 'vendor_name',  'dt' => 'vendor_name', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'supplier', 		 'dt' => 'supplier', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
   //array( 'db' => 'company_name', 		 'dt' => 'company_name' ),
   //array( 'db' => 'SupplierID', 		 'dt' => 'SupplierID' ),
   array( 'db' => 'Initiated Date', 		 'dt' => 'Initiated_Date', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.@date("m/d/Y",strtotime($d)).'</a>';
														} ),
   array( 'db' => 'Start Date', 		 'dt' => 'Start_Date', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')" class="ar_start_date">'.date("m/d/Y",strtotime($d)).'</a>';
														} ),
   array( 'db' => 'End Date', 		 'dt' => 'End_Date', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')" class="ar_end_date">'.@date("m/d/Y",strtotime($d)).'</a>';
														} ),
   array( 'db' => 'Product', 		 'dt' => 'Product', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'service_group', 		 'dt' => 'service_group', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Notes', 		 'dt' => 'Notes', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
   array( 'db' => 'Status', 		 'dt' => 'Status', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
														} ),
);

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	$columns[] = array( 'db' => 'company_name', 		 'dt' => 'company_name', 'formatter' =>
														function( $d, $row ) {
															return '<a href="javascript:void(0);" onclick="cmload_details('.$row['ContractID'].')">'.$d.'</a>';
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
