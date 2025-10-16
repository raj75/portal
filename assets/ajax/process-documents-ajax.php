<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
	
if(!isset($_SESSION['group_id']))
	die("Restricted Access!");

if($_SESSION['group_id'] == 1 or $_SESSION['group_id'] == 2){}else die("Restricted Access!");

unset($_SESSION['rows']);
// DB table to use
$db='vervantis';
$table = 'process_docs';
 
// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ID';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes	

$columns = array(
    array( 'db' => 'ID', 'dt' => 'ID' ),
    array( 'db' => 'Group', 'dt' => 'Group' ),
	array( 'db' => 'Sub Group 1', 'dt' => 'Sub Group 1' ),
    array( 'db' => 'Sub Group 2',  'dt' => 'Sub Group 2' ),
    array( 'db' => 'Sub Group 3',   'dt' => 'Sub Group 3' ),
    array( 'db' => 'Process Name', 'dt' => 'Process Name' ),
	array( 'db' => 'Owner', 'dt' => 'Owner' ),
	array( 'db' => 'Created Date', 'dt' => 'Created Date' ),
	array( 'db' => 'Modified Date', 'dt' => 'Modified Date' )
   
);


function makehtml($d, $row, $column ) {return false;
	global $table;
	global $mysqli;
	//print_r($row);
	
	$id = $row['ID'];
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
    'db'   => $db,
    'host' => HOST
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

//require_once '../includes/ssp_zipcodes.class.php'; 
require_once '../includes/processdocuments.class.custom.php'; 

//$whereall = "deleted = 0";
$whereall = "status='0'";

echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);