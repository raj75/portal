<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
	
ini_set('memory_limit', '-1');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//$sql = "Select `ID` , `City` from Adhoc limit 10";
//$result = $mysqli -> query($sql);

// Fetch all
//$data = $result -> fetch_all(MYSQLI_ASSOC);

//print_r($data);

	//setcookie('adhoc_export', '123', 86400, '/');
	setcookie('adhoc_export_status', 'loading', time() + (86400 * 30), "/"); // 86400 = 1 day
	
//$old_data = $db->sql( $rawquery )->fetch();
if (isset($_GET['export'])) {
	
	
	if ( $_GET['export']=='csvall' ) {
		
		$export = "csv";
		//$request = @unserialize($_COOKIE['KeepPost']);
		//print_r ($_SESSION);
		//die('==datatable_query');
		//print_r($request);
		//current(explode(".", $string));
		//$sql_query = $_SESSION['dt_query_all'];
		//$sql_query = current( explode("where", strtolower($string) ));
		$sql_query = current( explode("WHERE", $_SESSION['dt_query'] ));
		
	} else if ( $_GET['export']=='csvfilter' ) {
		
		$export = "csv";
		$sql_query = $_SESSION['dt_query'];		
		
	} else if ( $_GET['export']=='excelall' ) {
		
		$export = "excel";
		
		//$request = @unserialize($_COOKIE['KeepPost']);
		//print_r ($_SESSION);
		//die('==datatable_query');
		//print_r($request);
		//current(explode(".", $string));
		//$sql_query = $_SESSION['dt_query_all'];
		//$sql_query = current( explode("where", strtolower($string) ));
		$sql_query = current( explode("WHERE", $_SESSION['dt_query'] ));
		
	} else if ( $_GET['export']=='excelfilter' ) {
		
		$export = "excel";
		
		$sql_query = $_SESSION['dt_query'];
		
	}
}

// call aws
include_once("../includes/adhoc_aws.inc.php");




/*
 
// Load the database configuration file 
//include_once 'dbConfig.php'; 
 
// Fetch records from database 
$query = $mysqli->query($sql_query); 
			
			
			$fields = [];
			// Get field information for all fields
			$fieldinfo = $query -> fetch_fields();
			
			foreach ($fieldinfo as $val) {
				$fields[] = $val -> name;
				//printf("Name: %s\n", $val -> name);
				//printf("Table: %s\n", $val -> table);
				//printf("Max. Len: %d\n", $val -> max_length);
			}
			

	if ($export == "csv") {
		
		if($query->num_rows > 0){
			$delimiter = ","; 
			$filename = "adhoc-data_" . date('Y-m-d') . ".csv"; 
			 
			// Create a file pointer 
			$f = fopen('php://memory', 'w'); 
			 
			// Set column headers 
			//$fields = array('ID', 'City'); 
			$fields = [];
			// Get field information for all fields
			$fieldinfo = $query -> fetch_fields();
			
			foreach ($fieldinfo as $val) {
				$fields[] = $val -> name;
				//printf("Name: %s\n", $val -> name);
				//printf("Table: %s\n", $val -> table);
				//printf("Max. Len: %d\n", $val -> max_length);
			}
			//print_r($fields);
		  
			fputcsv($f, $fields, $delimiter); 
			 
			// Output each row of the data, format line as csv and write to file pointer 
			while($row = $query->fetch_assoc()){ 
				//$status = ($row['status'] == 1)?'Active':'Inactive'; 
				//$data_str = "";
				$data_arr = [];
				foreach ($fields as $key) {
					//$data_str .= $row['status']
					$data_arr[] = $row[$key];
				}
				//$lineData = array($row['ID'], $row['City']); 
				//fputcsv($f, $lineData, $delimiter); 
				fputcsv($f, $data_arr, $delimiter);
			} 
			 
			// Move back to beginning of file 
			fseek($f, 0); 
			
			setcookie('adhoc_export_status', 'done', time() + (86400 * 30), "/"); // 86400 = 1 day
			
			// Set headers to download file rather than displayed 
			header('Content-Type: text/csv'); 
			header('Content-Disposition: attachment; filename="' . $filename . '";'); 
			 
			//output all remaining data on a file pointer 
			fpassthru($f); 
		}
		
	} else if ($export == "excel") {
		
		
		if($query->num_rows > 0){
			
			
			
			//require __DIR__.'//SimpleXLSXGen.php';
			
			require_once '../php/SimpleXLSXGen.php';
			
			// convert array to string
			$excel_data_arr = [];
			
			$fields = [];
			// Get field information for all fields
			$fieldinfo = $query -> fetch_fields();
			
			foreach ($fieldinfo as $val) {
				$fields[] = $val -> name;
				//printf("Name: %s\n", $val -> name);
				//printf("Table: %s\n", $val -> table);
				//printf("Max. Len: %d\n", $val -> max_length);
			}
			//print_r($fields);
			
			//$fields_str = implode(",", $fields);
			
			$excel_data_arr[] = $fields;
		  
			//fputcsv($f, $fields, $delimiter); 
			 
			// Output each row of the data, format line as csv and write to file pointer 
			while($row = $query->fetch_assoc()){ 
				//$status = ($row['status'] == 1)?'Active':'Inactive'; 
				//$data_str = "";
				$data_arr = [];
				foreach ($fields as $key) {
					//$data_str .= $row['status']
					$data_arr[] = $row[$key];
					//$data_arr_str = $row[$key].",";
					//$excel_data_arr[] = $row[$key];
				}
				
				$excel_data_arr[] = $data_arr;
				//$lineData = array($row['ID'], $row['City']); 
				//fputcsv($f, $lineData, $delimiter); 
				//fputcsv($f, $data_arr, $delimiter);
			} 
			
			//$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $books );
			$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $excel_data_arr );
			
			$filename = "adhoc-data_" . date('Y-m-d') . ".xlsx"; 
			
			setcookie('adhoc_export_status', 'done', time() + (86400 * 30), "/"); // 86400 = 1 day
			//$xlsx->saveAs('books.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
			$xlsx->downloadAs($filename);
			
		}
		
	}
	
	//setcookie('adhoc_export', '123', 86400, '/');
	//setcookie('adhoc_export_status22', 'done11', time() + (86400 * 30), "/"); // 86400 = 1 day
		
		
exit; 
 */
 
?>