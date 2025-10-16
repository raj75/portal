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

$table = 'ziputility';
$id = mysqli_real_escape_string($mysqli,key($_POST['data']));


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
		
		Field::inst( 'deleted')
		
	)
	->process( $_POST )
	->json();

//print_r($_REQUEST);

//echo json_encode($_POST['data']);
?>