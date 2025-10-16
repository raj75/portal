<?php

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';


$sql_details = array(
	"type" => "Mysql",
	"user" => USER,
	"pass" => PASSWORD,
	"host" => HOST,
	"port" => "",
	"db"   => DATABASE,
	"dsn"  => "charset=utf8"
);

$table = "accounts";
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
Editor::inst( $db, $table, 'ID' )
	->fields(
		Field::inst( 'accounts.ID' ),
		
		Field::inst( 'accounts.delete_date'),
		Field::inst( 'accounts.delete_by'),		
		Field::inst( 'user.firstname'),
		Field::inst( 'user.lastname'),
		
		Field::inst( 'accounts.invoice_source' ),
		Field::inst( 'accounts.company_id' ),
		Field::inst( 'accounts.site_number' ),
		Field::inst( 'accounts.site_inactive_date' ),
		Field::inst( 'accounts.vendor_id' ),
		Field::inst( 'accounts.vendor_name' ),
		Field::inst( 'accounts.service_group_id' ),
		Field::inst( 'accounts.service_group' ),
		Field::inst( 'accounts.account_number1' ),
		Field::inst( 'accounts.account_number2' ),
		Field::inst( 'accounts.account_number3' ),
		Field::inst( 'accounts.legacy_account_number' ),
		Field::inst( 'accounts.service_point_location' ),
		Field::inst( 'accounts.name_key' ),
		Field::inst( 'accounts.account_active_date' ),
		Field::inst( 'accounts.account_inactive_date' ),
		Field::inst( 'accounts.meter_number' ),
		Field::inst( 'accounts.meter_active_date' ),
		Field::inst( 'accounts.meter_inactive_date' ),
		Field::inst( 'accounts.rate_id' ),
		Field::inst( 'accounts.activity_date' ),
		Field::inst( 'accounts.meter_status' ),
		Field::inst( 'accounts.gl_code' ),
		Field::inst( 'accounts.gl_reference' ),
		Field::inst( 'accounts.gl_group' ),
		Field::inst( 'accounts.notes' ),
		Field::inst( 'accounts.importDate' ),
		
	)
	->where( 'accounts.deleted', 1 )
	->leftJoin( 'user', 'user.user_id', '=', 'accounts.delete_by' )
	->process( $_POST )
	->json();

?>