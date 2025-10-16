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

sec_session_start();

$table = 'client_information';

//$id = mysqli_real_escape_string($mysqli,key($_POST['data']));

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
Editor::inst( $db, $table, 'id' )
	->fields(
		Field::inst( 'client' ),
		Field::inst( 'primary_contact_name' ),
		Field::inst( 'primary_contact_email' ),
		Field::inst( 'secondary_contact_name' ),
		Field::inst( 'secondary_contact_email' ),		
		Field::inst( 'tax_id' ),
		Field::inst( 'tax_id_alt' ),
		Field::inst( 'address' ),
		Field::inst( 'ap_name' ),
		Field::inst( 'ap_phone' ),
		Field::inst( 'ap_email' ),
		Field::inst( 'utility_contact_name' ),
		Field::inst( 'utility_phone' ),
		Field::inst( 'utility_email' ),		
		Field::inst( 'capturis_address' ),
		Field::inst( 'capturis_email' ),		
		Field::inst( 'notes' ),
		
	)
	->process( $_POST )
	->json();

//------------------------------------------------------------------

?>