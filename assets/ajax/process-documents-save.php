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

if(!isset($_SESSION['group_id']))
	die("Restricted Access!");

if($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2){}else die("Restricted Access!");

$db='vervantis';
$table = 'process_docs';
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
	
	
	//$aid = $id;
	
	////$rawquery = "select * from $table where ID='$id' limit 1";
	//$data = $db->sql( $rawquery )->fetchAll();
	////$data = $db->sql( $rawquery )->fetch();
	//$oldvalue = $data;
	
	foreach($_POST['data'][$id] as $key=>$val) {
		
		$val = trim(strip_tags($val));
		$dbval = trim(strip_tags($oldDBdata[$key]));
		if (!empty($dbval) and $val != $dbval) {
			//$val = base64_encode(serialize($val));
			//save in tracking array
			
				$newvalue[$key]=$val;
				//$oldvalue[$key]=$oldDBdata[$key];
				$oldvalue[$key]=$dbval;
			
			////$upquery = "insert into audit_log set user_id = $user_id , ip_address = '$ip' , table_name = '$table' , table_row_id = '$id' , pk_name = 'ID' , edited_value = '$val' , activity = 'UPDATE' , modified = NOW() , session_id = '$session' , status = '0' ";
			
			////echo $upquery;
			////$result = $mysqli->query($upquery);
			
			//$db->sql( $upquery );
			//echo "<br>".$val ."!=". $dbval;
			//echo "<br>save in tracking";
		}
	}
	
	/*
	$newvalue=array();
	$oldvalue=array();
	*/
	///$id = key($_POST['data']);
	///$aid = $id;
	
	///$newvalue['county'] = $_POST['data'][$id]['county'];
	//$oldvalue['county'] = $data['county'];
	///$oldvalue = $data;
	
	//print_r($newvalue);
	
	//print_r($oldvalue);
	
	//die('------------');
	
	$results = array_diff($oldvalue, $newvalue);
	//print_r($results); 
	//die('---result---');
	$diff = array();
	foreach($results as $k => $v)
	{
			$diff[] = array('title' => $k,
							'old' => $v,
							'new' => $newvalue[$k]);
	}
	
	//print_r($diff);
	
	//die('--diff--');
	
	//if(count($diff) > 0 and $aid > 0)
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
	"db"   => $db,
	"dsn"  => "charset=utf8"
);

// DataTables PHP library and database connection
//require_once ('../datatables/DataTables.php');
require_once '../datatables/DataTables.php';

////$rawquery = "select * from $table where ID='$id' limit 1";
//$data = $db->sql( $rawquery )->fetchAll();
////$old_data = $db->sql( $rawquery )->fetch();
	
//save to tracking
//save_tracking($db); //for testing 
/*
$new_value = array();
$id = key($_POST['data']);
$new_value['county'] = $_POST['data'][$id]['county'];
*/
//echo audit_log($mysqliW,$table,'UPDATE',$new_value,'WHERE ID='.$id,'','','ID');

////print_r($data);
//die();

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
		Field::inst( 'Group' ),
		Field::inst( '`Sub Group 1`' ),
		Field::inst( '`Sub Group 2`' ),
		Field::inst( '`Sub Group 3`' ),
		Field::inst( '`Process Name`' ),
		Field::inst( 'Owner' ),
		Field::inst( '`Created Date`' ),
		Field::inst( '`Modified Date`' ),
		
		/*
		Field::inst( 'client' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Client is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return $val;
            } ),
		Field::inst( 'inbox' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Inbox is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return $val;
            } ),
		Field::inst( 'schedule' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Schedule is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return $val;
            } ),		
		Field::inst( 'banking_info' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Banking Information is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'receiver' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Receiver is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		Field::inst( 'notes' )->validator( Validate::notEmpty( ValidateOptions::inst()
                ->message( 'Notes is required' ) 
            ) )
			->setFormatter( function ( $val, $data, $opts ) {
                 return strip_tags($val);
            } ),
		*/
		
	)
	->process( $_POST )
	->json();

//------------------------------------------------------------------
if ($_POST['action']=='edit') {
	//save_tracking($old_data);
}

//print_r($_REQUEST);

//echo json_encode($_POST['data']);
?>