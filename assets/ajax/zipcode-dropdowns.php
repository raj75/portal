<?php
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

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
 
 
if(!empty($_POST["country"])){
    // Fetch state data based on the specific country 
	$country = $mysqli->real_escape_string($_POST['country']);
	
	if ($country == 'all') {
		$query = "SELECT DISTINCT state From ziputility order by state"; 
	} else {
		$query = "SELECT DISTINCT state From ziputility where country = '$country' order by state"; 
	}
    $result = $mysqli->query($query); 
     
    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
        echo '<option value="">All</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['state'].'">'.$row['state'].'</option>'; 
        } 
    }else{ 
        //echo '<option value="">State not available</option>'; 
    } 
	
} else if ($_GET['dep']=='country') {
	
	/*
	include_once( $_SERVER['DOCUMENT_ROOT']."/php/DataTables.php" );
 
	$countries = $db
		->select( 'country', ['id as value', 'name as label'], ['continent' => $_REQUEST['values']['continent']] )
		->fetchAll();
	 
	echo json_encode( [
		'options' => [
			'country' => $countries
		]
	] );
	*/

//print_r($_POST);
//die();

	$country = $mysqli->real_escape_string($_POST['values']['country']);
	
    $query = "SELECT DISTINCT state From ziputility where country = '$country' order by state"; 
    $result = $mysqli->query($query); 
    
	$statesArr = [];
    if($result->num_rows > 0){
        
        while($row = $result->fetch_assoc()){
            $statesArr[] = $row['state'];
        }
		
		echo json_encode( [
			'options' => [
				'state' => $statesArr,
				'state2' => $statesArr
			]
		] );
    } 
	
}

?>