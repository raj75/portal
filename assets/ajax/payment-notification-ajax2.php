<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

unset($_SESSION['rows']);
// DB table to use
$table = 'client_list';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
array( 'db' => 'id', 'dt' => 'id' ),
array( 'db' => 'client_id', 'dt' => 'client_id' ),
array( 'db' => 'division', 'dt' => 'division' ),
array( 'db' => 'ubm_name', 'dt' => 'ubm_name' ),
array( 'db' => 'ubm_client_id', 'dt' => 'ubm_client_id' ),
array( 'db' => 'client_name', 'dt' => 'client_name' ),
array( 'db' => 'email_address', 'dt' => 'email_address' ),
array( 'db' => 'email_cc', 'dt' => 'email_cc' ),
array( 'db' => 's3_folder', 'dt' => 's3_folder' ),
array( 'db' => 's3_folder_csv', 'dt' => 's3_folder_csv' ),
array( 'db' => 's3_folder_custom', 'dt' => 's3_folder_custom' ),
array( 'db' => 'custom_title', 'dt' => 'custom_title' ),
array( 'db' => 'custom_content', 'dt' => 'custom_content' ),
array( 'db' => 'multipage_email', 'dt' => 'multipage_email' ),
array( 'db' => 'include_invoice_images', 'dt' => 'include_invoice_images' ),
array( 'db' => 'include_custom_files', 'dt' => 'include_custom_files' ),
array( 'db' => 'include_invoice_detail', 'dt' => 'include_invoice_detail' ),
array( 'db' => 'include_invoice_credits', 'dt' => 'include_invoice_credits' ),
array( 'db' => 'include_content', 'dt' => 'include_content' ),
array( 'db' => 'include_payment', 'dt' => 'include_payment' ),
array( 'db' => 'include_gl_summary', 'dt' => 'include_gl_summary' ),
array( 'db' => 'include_1st_csv', 'dt' => 'include_1st_csv' ),
array( 'db' => 'include_alt_csv', 'dt' => 'include_alt_csv' ),
array( 'db' => 'include_email_csv', 'dt' => 'include_email_csv' ),
array( 'db' => 'include_email_txt', 'dt' => 'include_email_txt' ),
array( 'db' => 'include_csv_prefix1', 'dt' => 'include_csv_prefix1' ),
array( 'db' => 'include_csv_prefix2', 'dt' => 'include_csv_prefix2' ),
array( 'db' => 'include_csv_prefix3', 'dt' => 'include_csv_prefix3' ),
array( 'db' => 'include_csv_prefix4', 'dt' => 'include_csv_prefix4' ),
array( 'db' => 'include_csv_prefix5', 'dt' => 'include_csv_prefix5' ),
array( 'db' => 'include_csv_suffix1', 'dt' => 'include_csv_suffix1' ),
array( 'db' => 'include_csv_suffix2', 'dt' => 'include_csv_suffix2' ),
array( 'db' => 'include_csv_suffix3', 'dt' => 'include_csv_suffix3' ),
array( 'db' => 'include_csv_suffix4', 'dt' => 'include_csv_suffix4' ),
array( 'db' => 'include_csv_suffix5', 'dt' => 'include_csv_suffix5' ),
array( 'db' => 'monday', 'dt' => 'monday' ),
array( 'db' => 'tuesday', 'dt' => 'tuesday' ),
array( 'db' => 'wednesday', 'dt' => 'wednesday' ),
array( 'db' => 'thursday', 'dt' => 'thursday' ),
array( 'db' => 'friday', 'dt' => 'friday' ),
array( 'db' => 'only_attachment', 'dt' => 'only_attachment' ),
   
);

////Not required
function makehtml($d, $row, $column ) {
	global $table;
	global $mysqli;
	//print_r($row);
	
	$id = $row['id'];
	$ts=rand(1000,9000);
	//.$i.$j;
	
	$show_icon = '';
	
	// if ( isset($_SESSION['rows']) and is_array($_SESSION['rows'][$id]) ) {
		// echo "--session rows==";
		// print_r($_SESSION['rows']);
	// } else {

		$stmt = $mysqli->query("Select edited_value from audit_log where table_name='$table' and table_row_id='$id' ");
		if ($stmt->num_rows > 0) {
			//$_SESSION['rows'][$id] = [];
			$continue = 0;
			$editedvalueArr = [];
			while($row=$stmt->fetch_assoc()) {
				$editedvalue=$row['edited_value'];
				$editedvalarr=unserialize(base64_decode($editedvalue));
				$z=count($editedvalarr);
				
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and trim($editedvalarr[$i]["title"]) == trim($column)){
						//show icon
						$show_icon = '<i class="fa fa-reply" aria-hidden="true"></i>';
						$continue = 1;
						continue;
						//$_SESSION['rows'][$id] = $column;
					}
				}
				if ($continue==1) {$continue=0; continue;}
				//print_r($editedvalarr);
				//die();
				//$editedvalueArr['']=$row['edited_value'];
			}
		}
	//}

	/*
	return '<a href="javascript:void(0);" onmouseover="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$d.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover-hover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>'.$show_icon.'
	';
	*/
	
	/*
	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover-hover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';
	*/
	
	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';
	
}

// SQL server connection information
$sql_details = array(
    'user' => USER,
    'pass' => PASSWORD,
    'db'   => "payment_notifications",
    'host' => HOST
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require_once '../includes/ssp_zipcodes.class.php'; 

//$whereall = "deleted = 0";
$whereall = "";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
);