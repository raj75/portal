<?php
/** Database Settings **/
$domain = "127.0.0.1";
$user_id = "root";
$pasw = "Porsche911!";
$database_name = "TestData";
$tablename = "ForwardCommodity";
$sectablename = "log";


/** Messages **/
date_default_timezone_set('America/Phoenix');
$success_msg="updated without issue on ".date("d M Y  H:i:s");
$error_msg_website_change="error occurred on ".date("d M Y  H:i:s")." :- change in html structure";
$error_msg_access_denied="error occurred on ".date("d M Y  H:i:s")." :- Access Denied";			
$conn=mysqli_connect($domain, $user_id, $pasw, $database_name) or die("no db connection");

// Fetch the data
$query = mysqli_query($conn,"SELECT DISTINCT ForwardCommodity.`Basis Name` FROM ForwardCommodity");
$source = array();

/*
  Building the source string
*/
while ($row = mysqli_fetch_array($query,MYSQLI_BOTH)) 
{
  array_push($source, $row['Basis Name']);
}

/*
  Printing the source string
*/
echo json_encode($source);

// Close the connection
mysqli_close($conn);					

?>