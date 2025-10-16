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

$table = 'accounts';
$id = mysqli_real_escape_string($mysqli,key($_POST['data']));

function save_tracking($oldDBdata) {
	//echo "in fucntion";
	global $mysqli;
	global $table;
	global $id;
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
		
		$upquery = "insert into audit_log set user_id = $user_id , ip_address = '$ip' , table_name = '$table' , table_row_id = '$id' , pk_name = 'ID' , edited_value = '$valser' , activity = 'UPDATE' , modified = NOW() , session_id = '$session' , status = '0' ";
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

$rawquery = "select * from $table where ID='$id' limit 1";
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
Editor::inst( $db, $table, 'ID' )
	->fields(
		Field::inst( 'invoice_source' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'company_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'site_number' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'site_inactive_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'vendor_name' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'service_group_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'service_group' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'account_number1' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'account_number2' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'account_number3' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'legacy_account_number' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'service_point_location' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'name_key' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'account_active_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'account_inactive_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'meter_number' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'meter_active_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'meter_inactive_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'rate_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'activity_date' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'meter_status' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'gl_code' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'gl_reference' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'gl_group' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'notes' )->setFormatter( function ( $val, $data, $opts ) {
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