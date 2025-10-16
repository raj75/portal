<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

//print_r($_POST);
//die();

$user_one=$_SESSION["user_id"];

		
		$table = 'notifications';
		$pk_name = 'id';
		
		/*
		 * Example PHP implementation used for the index.html example
		 */
		 
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
		Editor::inst( $db, $table, $pk_name )
			->fields(
				Field::inst( 'id' ),
				Field::inst( 'title' ),
				Field::inst( 'description' ),
				Field::inst( 'status' ),
				Field::inst( 'type' ),
				/*
				Field::inst( 'status' ) ->getFormatter( function ( $val, $data ) {
										if ($val == 1) {
											return "Inactive";
										} else {
											return "Active";
										}
										} ),
				Field::inst( 'type' ) ->getFormatter( function ( $val, $data ) {
										if ($val == 1) {
											return "Change Log";
										} else if ($val == 2) {
											return "Announcements";
										}
										} ),
				*/
				Field::inst( 'start_date' )->validator( Validate::dateFormat( 'Y-m-d' ) )
											->getFormatter( Format::dateSqlToFormat( 'Y-m-d' ) )
											->setFormatter( Format::dateFormatToSql('Y-m-d' ) ),
				Field::inst( 'end_date' )->validator( Validate::dateFormat( 'Y-m-d' ) )
											->getFormatter( Format::dateSqlToFormat( 'Y-m-d' ) )
											->setFormatter( Format::dateFormatToSql('Y-m-d' ) ),
				Field::inst( 'created_by' )->set( Field::SET_CREATE ),
				Field::inst( 'created_date' )->getFormatter( Format::dateSqlToFormat( 'Y-m-d' ) )
											 ->setFormatter( Format::dateFormatToSql('Y-m-d' ) )
											 ->set( Field::SET_CREATE ),
				//Field::inst( 'created_date' )->set( Field::SET_EDIT )				
				
			)
			->on( 'preCreate', function ( $editor, &$values ) {
				$editor->field( 'created_by' )->setValue( $_SESSION['user_id'] );
			} )
			->on( 'preCreate', function ( $editor, &$values ) {
				$editor->field( 'created_date' )->setValue( date("Y-m-d") );
			} )
			/*
			->on( 'preEdit', function ( $editor, $id, &$values ) {
				$editor
					->field( 'last_updated_by' )
					->setValue( time() );
			} )
			*/
			->debug(true)
			->process( $_POST )
			->json();
	
	

?>
