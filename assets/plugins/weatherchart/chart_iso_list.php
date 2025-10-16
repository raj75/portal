<?php
if(isset($_POST["iso"]) and @trim($_POST["iso"]) != "")
{
$mysqli=mysqli_connect("localhost","root","");
mysqli_select_db($mysqli,"warrick");
	$s_=array();
	
	if ($stmt = $mysqli->prepare('SELECT _pnode FROM warrick WHERE _iso="'.$_POST["iso"].'" and _pnode != "" GROUP BY _pnode')) { 
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($s_pnode);
			while($stmt->fetch()) {
				$s_[]= $s_pnode;
			}

			echo implode("@@",$s_);
			exit();
		}
	}
}
return false;
?>