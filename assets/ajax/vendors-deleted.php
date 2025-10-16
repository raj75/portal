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

$table = "vendor";
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
Editor::inst( $db, $table, 'vendor_id' )
	->fields(
		Field::inst( 'vendor.vendor_id' ),
		Field::inst( 'vendor.capturis_vendor_id' ),
		Field::inst( 'vendor.vendor_name' ),
		Field::inst( 'vendor.capturis_vendor_name' ),
		Field::inst( 'vendor.vendor_abbreviation' ),
		Field::inst( 'vendor.vendor_altname1' ),
		Field::inst( 'vendor.vendor_altname2' ),
		Field::inst( 'vendor.vendor_altname3' ),
		Field::inst( 'vendor.vendor_altname4' ),
		Field::inst( 'vendor.vendor_altname5' ),
		Field::inst( 'vendor.service_group' ),
		Field::inst( 'vendor.service_group_id' ),
		Field::inst( 'vendor.deregulated' ),
		Field::inst( 'vendor.vendor_type' ),
		Field::inst( 'vendor.state' ),
		Field::inst( 'vendor.vendorAddr1' ),
		Field::inst( 'vendor.vendorAddr2' ),
		Field::inst( 'vendor.vendorCity' ),
		Field::inst( 'vendor.vendorState' ),
		Field::inst( 'vendor.vendorZip' ),
		Field::inst( 'vendor.vendorCountry' ),
		Field::inst( 'vendor.vendorPhoneNbr1' ),
		Field::inst( 'vendor.vendorPhoneNbr2' ),
		Field::inst( 'vendor.vendorPhoneNbr3' ),
		Field::inst( 'vendor.vendorFaxNbr1' ),
		Field::inst( 'vendor.vendorFaxNbr2' ),
		Field::inst( 'vendor.vendorEmail1' ),
		Field::inst( 'vendor.VendorEmail2' ),
		Field::inst( 'vendor.vendorWebpage1' ),
		Field::inst( 'vendor.vendorWebpage2' ),
		Field::inst( 'vendor.importDate' ),	
		
		Field::inst( 'vendor.delete_date'),
		Field::inst( 'vendor.delete_by'),
		
		Field::inst( 'user.firstname'),
		Field::inst( 'user.lastname')
		
	)
	->where( 'vendor.deleted', 1 )
	->leftJoin( 'user', 'user.user_id', '=', 'vendor.delete_by' )
	->process( $_POST )
	->json();

?>