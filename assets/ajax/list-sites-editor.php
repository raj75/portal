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


Editor::inst( $db, 'sites', 'id' )
    ->field(
		//Field::inst( 'sites.id' ),
		Field::inst( 'company_id' ),
        Field::inst( 'site_number' ),
		
        Field::inst( 'site_name' ),
        Field::inst( 'service_address1' ),
        Field::inst( 'city' ),            
        Field::inst( 'state' ),
		
		Field::inst( 'postal_code' ),
		Field::inst( 'site_status' ),
		
		//Field::inst( 'sites.active_date' ),
		
    )
    //->leftJoin( 'company', 'company.company_id', '=', 'sites.company_id' )
	//->leftJoin( 'user', 'user.company_id', '=', 'company.company_id' )
	//->where("user.user_id",$user_one)
	->debug(true)
    ->process($_POST)
    ->json();
	
?>