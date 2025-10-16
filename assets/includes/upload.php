<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
$ds = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '../uploads';   //2

sec_session_start();
$user_one=$_SESSION["user_id"];
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3  
	$image_type = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	$detectedType = exif_imagetype($tempFile);
	if(!in_array($detectedType, $image_type))
		$extn=".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
	else
		$extn=".png";
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
    $targetHash = sha1(uniqid($_FILES['file']['name'] . microtime(), true)).$extn; 
    $targetFile =  $targetPath.$targetHash ;  //5
	//$targetFile =  $targetPath. $_FILES['file']['name'];

 
 
    if(move_uploaded_file($tempFile,$targetFile) === true)
	{
		$stmt = $mysqli->prepare("INSERT INTO media (name,hash_name,user_id,flag) VALUES ('".$mysqli->real_escape_string($_FILES['file']['name'])."', '".$targetHash."', '".$user_one."','0')") ;
		$stmt->execute();
		$lastinsertID=$stmt->insert_id;
		if($lastinsertID > 0) {
			if ($stmtt = $mysqli->prepare('SELECT id,name,description,hash_name,datetime FROM media where id="'.$lastinsertID.'" LIMIT 1')) { 
				$stmtt->execute();
				$stmtt->store_result();
				if ($stmtt->num_rows > 0) {
					$stmtt->bind_result($af_id,$af_name,$af_desc,$af_hash_name,$af_datetime);
					$stmtt->fetch();
					$arr["after_insert"]=array("id"=>$af_id,"name"=>$af_name,"filedesc"=>$af_desc,"hashname"=>$af_hash_name,"datetime"=>$af_datetime);
				}
			}
			$arr["error"]=false;		
			echo json_encode($arr);
			exit();
		}
		
		//echo true;
		//exit();
	}
	echo false;
}

	if(isset($_POST["list-files"])){                                                         
		$result  = array();
	 
		/*$files = scandir($storeFolder);                 //1
		if ( false!==$files ) {
			foreach ( $files as $file ) {
				if ( '.'!=$file && '..'!=$file) {       //2
					$obj['name'] = $file;
					$obj['size'] = filesize($storeFolder.$ds.$file);
					$result[] = $obj;
				}
			}
		}
		 
		header('Content-type: text/json');              //3
		header('Content-type: application/json');
		echo json_encode($result);*/
		if ($stmt = $mysqli->prepare('SELECT id,name,description,hash_name,datetime FROM media')) { 
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id,$_name,$_desc,$hash_name,$datetime);
				while($stmt->fetch()) {
					$result[] = array("id"=>$id,"name"=>$_name,"filedesc"=>$_desc,"hashname"=>$hash_name,"datetime"=>$datetime);
				}
			}
		}
		echo json_encode(array("filelist"=>$result));
		exit();	
	}
	
	if(isset($_POST["title"]) and @trim($_POST["title"]) != "" and isset($_POST["titleid"]) and @trim($_POST["titleid"]) != "" and @trim($_POST["titleid"]) != 0){
		$temp_title=trim($_POST["title"]);
		$temp_titleid=trim($_POST["titleid"]);
		if($user_one != 1)
			$temp_sql=" and user_id=".$user_one;
		else
			$temp_sql="";

		if ($stmt = $mysqli->prepare('UPDATE media set name="'.$mysqli->real_escape_string($temp_title).'" where id="'.$mysqli->real_escape_string($temp_titleid).'" '.$temp_sql)) { 
			$stmt->execute();
		}
		echo json_encode(array("error"=>false));
		exit();	
	}
	
	if(isset($_POST["desc"]) and @trim($_POST["desc"]) != "" and isset($_POST["descid"]) and @trim($_POST["descid"]) != "" and @trim($_POST["descid"]) != 0){
		$temp_desc=trim($_POST["desc"]);
		$temp_descid=trim($_POST["descid"]);
		if($user_one != 1)
			$temp_sql=" and user_id=".$user_one;
		else
			$temp_sql="";

		if($user_one != 1)
		{
			if ($stmt = $mysqli->prepare('SELECT id FROM media where id="'.$mysqli->real_escape_string($temp_descid).'" and user_id='.$user_one)) { 
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
				}else{
					echo json_encode(array("error"=>"You are not owner of this file."));
					exit();
				}
			}else{
				echo false;
				exit();
			}
		}
			
		if ($stmt = $mysqli->prepare('UPDATE media set description="'.$mysqli->real_escape_string($temp_desc).'" where id="'.$mysqli->real_escape_string($temp_descid).'" '.$temp_sql)) { 
			$stmt->execute();
		}
		echo json_encode(array("error"=>false));
		exit();	
	}
	
	if (isset($_POST) && $_POST['filename']) {
		//@unlink('upload-path'/".$_POST['filename']);
	}	
	
echo false;
?>
<!--- See more at: http://www.startutorial.com/articles/view/how-to-build-a-file-upload-form-using-dropzonejs-and-php#sthash.APTCQ8nP.dpuf-->