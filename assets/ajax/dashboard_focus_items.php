<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];

if(isset($_GET["db_fi"]))
{
	$stmtk = $mysqli->prepare("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.user_id = '".$user_one."' and up.company_id = fi.company_id ORDER BY fi.id DESC");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.id = '".$user_one."' and up.company_id = fi.company_id ORDER BY fi.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($fiID,$fiCompanyID,$fiCategory,$fiDescription,$dateadded,$fiRead);
		while($stmtk->fetch()){
			echo'<tr '.($fiRead == "N"?'style="font-weight:bold;"':'').'>
				<td>';
			if($fiCategory==1){echo "Fixed Price";}
			elseif($fiCategory==2){echo "Index/Basis + Adder";}
			elseif($fiCategory==3){echo "Heat Rate";}
			elseif($fiCategory==4){echo "Hedge Block";}
			elseif($fiCategory==5){echo "Blend and Extend";}

			echo '</td>
				<td>'.$fiDescription.'</td>
				<td>'.$dateadded.'</td>
				<td><a href="javascript:void(0);" onclick="launchfi(\'123\')">View</td>
			</tr>';
		}
	}
}elseif(isset($_GET["db_fi_dt"]))
{
	$stmtk = $mysqli->prepare("SELECT fi.id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi,company c,user up WHERE up.user_id = '".$user_one."' and c.company_id=fi.company_id and up.company_id=fi.company_id ORDER BY fi.id DESC");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//("SELECT fi.id,c.company_name,fi.category,fi.description,fi.link,fi._read,fi.date_added FROM focus_items fi,company c,user up WHERE up.id = '".$user_one."' and c.id=fi.company_id and up.company_id=fi.company_id ORDER BY fi.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($fiID,$cCompany,$fiCategory,$fiDescription,$fiLink,$fiRead,$fiDateadded);
		while($stmtk->fetch()){
			echo'<tr '.($fiRead == "N"?'style="font-weight:bold;"':'').'>
				<td>';
			if($fiCategory==1){echo "Fixed Price";}
			elseif($fiCategory==2){echo "Index/Basis + Adder";}
			elseif($fiCategory==3){echo "Heat Rate";}
			elseif($fiCategory==4){echo "Hedge Block";}
			elseif($fiCategory==5){echo "Blend and Extend";}

			echo '</td>
				<td>'.$fiDescription.'</td>
				<td>'.$fiDateadded.'</td>
				<td><a href="javascript:void(0);">View</td>
			</tr>';			
		}
	}
}elseif(isset($_GET["db_fi_un_dt"]))
{
	$stmtk = $mysqli->prepare("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.link,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.user_id = '".$user_one."' and up.company_id=fi.company_id and fi._read='N' ORDER BY fi.id DESC");
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//("SELECT fi.id,fi.company_id,fi.category,fi.description,fi.link,fi.date_added,fi._read FROM focus_items fi,user up WHERE up.id = '".$user_one."' and up.company_id=fi.company_id and fi._read='N' ORDER BY fi.id DESC");

	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($fiID,$fiCID,$fiCategory,$fiDescription,$fiLink,$fiDateadded,$fiRead);
		while($stmtk->fetch()){
			echo'<tr '.($fiRead == "N"?'style="font-weight:bold;"':'').'>
				<td>';
			if($fiCategory==1){echo "Fixed Price";}
			elseif($fiCategory==2){echo "Index/Basis + Adder";}
			elseif($fiCategory==3){echo "Heat Rate";}
			elseif($fiCategory==4){echo "Hedge Block";}
			elseif($fiCategory==5){echo "Blend and Extend";}

			echo '</td>
				<td>'.$fiDescription.'</td>
				<td>'.$fiDateadded.'</td>
				<td><a href="javascript:void(0);">View</td>
			</tr>';			
		}
	}
}
?>
