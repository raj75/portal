<?php
error_reporting(0); // Set E_ALL for debuging
require_once '../../../includes/db_connect.php';
require_once '../../../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

/*$_foldername = bin2hex(mhash_keygen_s2k(MHASH_SHA1, $user_one, "Ysbdkjb%*%(wb^&%", 16));
$dir = '../../../uploads/resources/'.$_foldername;
$dir = '../../filesssssssssssssssssssss';
if(!file_exists('../../../uploads/resources/'.$_foldername.'/'))
{
	mkdir('../../../uploads/resources/'.$_foldername.'/',0777);
	mkdir('../../../uploads/resources/'.$_foldername.'/RPF/',0777);
	mkdir('../../../uploads/resources/'.$_foldername.'/Market Reports/',0777);
	mkdir('../../../uploads/resources/'.$_foldername.'/Contracts/',0777);
	mkdir('../../../uploads/resources/'.$_foldername.'/Budget/',0777);
}*/



if(isset($_SESSION) and $_SESSION["group_id"] == 3)
{
	$temp_foldername=$_SESSION["username"].'('.$_SESSION["user_id"].')';
	$dir='../../uploads/resources/Clients/'.$temp_foldername;
	if(!file_exists('../../uploads/resources/Clients/'))
		mkdir('../../uploads/resources/Clients/',0777);
		
	if(!file_exists('../../uploads/resources/Clients/'.$temp_foldername.'/'))
		mkdir('../../uploads/resources/Clients/'.$temp_foldername.'/',0777);

	if(!file_exists('../../uploads/resources/Clients/'.$temp_foldername.'/RPF/'))
		mkdir('../../uploads/resources/Clients/'.$temp_foldername.'/RPF/',0777);

	if(!file_exists('../../uploads/resources/Clients/'.$temp_foldername.'/Market Reports/'))
		mkdir('../../uploads/resources/Clients/'.$temp_foldername.'/Market Reports/',0777);

	if(!file_exists('../../uploads/resources/Clients/'.$temp_foldername.'/Contracts/'))
		mkdir('../../uploads/resources/Clients/'.$temp_foldername.'/Contracts/',0777);

	if(!file_exists('../../uploads/resources/Clients/'.$temp_foldername.'/Budget/'))
		mkdir('../../uploads/resources/Clients/'.$temp_foldername.'/Budget/',0777);
}else
	die("No access");





// Run the recursive function 

$response = scan($dir);


// This function scans the files folder recursively, and builds a large array

function scan($dir){

	$files = array();

	// Is there actually such a folder/file?

	if(file_exists($dir)){
	
		foreach(scandir($dir) as $f) {
		
			if(!$f || $f[0] == '.') {
				continue; // Ignore hidden files
			}

			if(is_dir($dir . '/' . $f)) {

				// The path is a folder

				$files[] = array(
					"name" => $f,
					"type" => "folder",
					"path" => $dir . '/' . $f,
					"items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
				);
			}
			
			else {

				// It is a file

				$files[] = array(
					"name" => $f,
					"type" => "file",
					"path" => $dir . '/' . $f,
					"size" => filesize($dir . '/' . $f) // Gets the size of this file
				);
			}
		}
	
	}

	return $files;
}



// Output the directory listing as JSON

header('Content-type: application/json');

echo json_encode(array(
	"name" => $temp_foldername,
	"type" => "folder",
	"path" => $dir,
	"items" => $response
));
