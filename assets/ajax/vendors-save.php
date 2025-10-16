<?php
//echo "id==".key($_POST['data']);
//die();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//print_r($_POST);
//session_start();
require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
/*
$mysqliW = new mysqli(HOST, USER, PASSWORD, 'world');

if ($mysqliW->connect_error) {
	try {
		$mysqliW->close();
	}
	catch(Exception $e) {
		echo $mysqliW->connect_error; die();
	}
}
*/
sec_session_start();

$table = 'vendor';
$pk_name = 'vendor_id';

$id = mysqli_real_escape_string($mysqli,key($_POST['data']));

function save_tracking($oldDBdata) {
	//echo "in fucntion";
	global $mysqli;
	global $table;
	global $id;
	global $pk_name;
	
	$user_id = $_SESSION["user_id"];
	$ip = $_SERVER['REMOTE_ADDR'];
	$session = session_id();
	$newvalue=array();
	$oldvalue=array();
	
	
	
	
	foreach($_POST['data'][$id] as $key=>$val) {
		//echo "<br>".$key."--".$val;
		
		$val = trim(strip_tags($val));
		$dbval = trim(strip_tags($oldDBdata[$key]));
		if (!empty($dbval) and $val != $dbval) {
			//$val = base64_encode(serialize($val));
			//save in tracking array
			
				$newvalue[$key]=$val;
				//$oldvalue[$key]=$oldDBdata[$key];
				$oldvalue[$key]=$dbval;
		}
	}
	
		
	$results = array_diff($oldvalue, $newvalue);
	
	$diff = array();
	foreach($results as $k => $v)
	{
			$diff[] = array('title' => $k,
							'old' => $v,
							'new' => $newvalue[$k]);
	}
	
	
	if(count($diff) > 0)
	{
		$valser = base64_encode(serialize($diff));
		
		$upquery = "insert into audit_log set user_id = $user_id , ip_address = '$ip' , table_name = '$table' , table_row_id = '$id' , pk_name = '$pk_name' , edited_value = '$valser' , activity = 'UPDATE' , modified = NOW() , session_id = '$session' , status = '0' ";
		//$sql = "SELECT id, firstname, lastname FROM MyGuests";
		//echo $upquery;
		$result = $mysqli->query($upquery); 
	}
	
	
	//die();
	//print_r($data);
}

$sql_details = array(
	"type" => "Mysql",
	"user" => USER,
	"pass" => PASSWORD,
	"host" => HOST,
	"port" => "",
	"db"   => DATABASE,
	"dsn"  => "charset=utf8"
);

// DataTables PHP library and database connection
//require_once ('../datatables/DataTables.php');
require_once '../datatables/DataTables.php';

$rawquery = "select * from $table where $pk_name='$id' limit 1";
//$data = $db->sql( $rawquery )->fetchAll();
$old_data = $db->sql( $rawquery )->fetch();
	
// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;


// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, $table, 'vendor_id' )
	->fields(
		Field::inst( 'capturis_vendor_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_name' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'capturis_vendor_name' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_abbreviation' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_altname1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_altname2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_altname3' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_altname4' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_altname5' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'service_group' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'service_group_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'deregulated' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_type' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'state' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorAddr1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorAddr2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorCity' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorState' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorZip' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorCountry' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorPhoneNbr1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorPhoneNbr2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorPhoneNbr3' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorFaxNbr1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorFaxNbr2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorEmail1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'VendorEmail2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorWebpage1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendorWebpage2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'importDate' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),	
			
		Field::inst( 'deleted'),
		Field::inst( 'delete_date')
		->setFormatter( function ( $val, $data, $opts ) {
			
			if ($_POST['data'][key($_POST['data'])]['deleted']==1) {
				return date('Y-m-d H:i:s');
			}
			
		}),
		Field::inst( 'delete_by')
		->setFormatter( function ( $val, $data, $opts ) {
			$user_one=$_SESSION["user_id"];
			if ($_POST['data'][key($_POST['data'])]['deleted']==1) {
				return $user_one;
			}
			
		})
		
	)
	->process( $_POST )
	->json();

//------------------------------------------------------------------
if ($_POST['action']=='edit') {
	save_tracking($old_data);
}

?>