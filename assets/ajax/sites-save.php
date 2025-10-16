<?php 
//print_r($_POST);
//die();
require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
$table = 'sites';
$pkey = 'id';
$id = mysqli_real_escape_string($mysqli, ltrim(key($_POST['data']),"row_") );

function save_tracking($oldDBdata) {
	//echo "in fucntion";
	global $mysqli;
	global $table;
	global $id;
	global $pkey;
	$user_id = $_SESSION["user_id"];
	$ip = $_SERVER['REMOTE_ADDR'];
	$session = session_id();
	$newvalue=array();
	$oldvalue=array();
	
	foreach($_POST['data']["row_".$id] as $key=>$val) {
		
		$val = trim(strip_tags($val));
		$dbval = trim(strip_tags($oldDBdata[$key]));
		if (!empty($dbval) and $val != $dbval) {
			//save in tracking array
			$newvalue[$key]=$val;
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
		
		$upquery = "insert into audit_log set user_id = $user_id , ip_address = '$ip' , table_name = '$table' , table_row_id = '$id' , pk_name = '$pkey' , edited_value = '$valser' , activity = 'UPDATE' , modified = NOW() , session_id = '$session' , status = '0' ";
		$result = $mysqli->query($upquery); 
	}
	
}

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");
		
$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");



$sql_details = array(
	"type" => "Mysql",
	"user" => USER,
	"pass" => PASSWORD,
	"host" => HOST,
	"port" => "",
	"db"   => DATABASE,
	"dsn"  => "charset=utf8"
);

// Table's primary key
//$primaryKey = 'id';
// DataTables PHP library
require_once '../datatables/DataTables.php';

$rawquery = "select * from $table where $pkey='$id' limit 1";
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


//Editor::inst( $db, 'sites', 'id' )
Editor::inst( $db, $table, $pkey )
    ->field(
		//Field::inst( 'sites.id' ),
		Field::inst( 'company_id' )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
        Field::inst( 'site_number' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Site Number is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		
        Field::inst( 'site_name' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Site Name is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
        Field::inst( 'service_address1' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Address is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
        Field::inst( 'city' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'City is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),          
        Field::inst( 'state' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'State is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		
		Field::inst( 'postal_code' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Postal Code is required' ) 
            ) )->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'site_status' )->setFormatter( function ( $val, $data, $opts ) {
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
		
		//Field::inst( 'sites.active_date' ),
		
    )
    //->leftJoin( 'company', 'company.company_id', '=', 'sites.company_id' )
	//->leftJoin( 'user', 'user.company_id', '=', 'company.company_id' )
	//->where("user.user_id",$user_one)
	//->debug(true)
    ->process($_POST)
    ->json();
	
	//------------------------------------------------------------------
	if ( $_POST['action']=='edit' and !isset( $_POST['data']['deleted'] ) ) {
		save_tracking($old_data);
	}
	
?>