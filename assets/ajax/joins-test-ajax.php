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

$table = 'datatabel1';


$sql_details = array(
	"type" => "Mysql",
	"user" => USER,
	"pass" => PASSWORD,
	"host" => HOST,
	"port" => "",
	"db"   => 'aamir',
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


/*
 * Example PHP implementation used for the join.html example
 */
Editor::inst( $db, 'users' )
    ->field(
        Field::inst( 'users.first_name' ),
        Field::inst( 'users.last_name' ),
        Field::inst( 'users.phone' ),
        Field::inst( 'users.site' )
            ->options( Options::inst()
                ->table( 'sites' )
                ->value( 'id' )
                ->label( 'name' )
            )
            ->validator( Validate::dbValues() ),
        Field::inst( 'sites.name' ),
				Field::inst( 'sites.part1' ),
				Field::inst( 'sites.part2' )
    )
    ->leftJoin( 'sites', 'sites.part2', '=', 'users.site' )
    ->process($_POST)
    ->json();

//print_r($_REQUEST);

//echo json_encode($_POST['data']);
?>
