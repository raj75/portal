<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

$cname=$_SESSION["company_id"];


$fsubsql="";
//if(isset($_POST) and isset($_POST["companyid"])){
if(isset($_GET) and isset($_GET["companyid"])){
	$companyid=$_GET["companyid"];
}else die("Error occured, Please try after sometimes");

if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
	if(empty(@trim($companyid))){}
	else $fsubsql=" WHERE b.company_id=".$companyid;
}else $fsubsql=" WHERE b.company_id=$cname";

$vnamearr=$servicesarr=$statesarr=array();

/*if ($stmt = $mysqli->prepare("SELECT a.state_name,b.state_id FROM adhoc_state AS a INNER JOIN adhoc_state_xref AS b ON a.state_id=b.state_id $fsubsql")) {
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_statename,$_stateid);
		while($stmt->fetch()){
			$statesarr[]=array($_stateid,$_statename);
			//$statesarr=array_unique($statesarr);
		}
		if(count($statesarr)){ echo json_encode(array_values($statesarr)); }
		else echo false;
	}else echo false;
}else echo false;
*/

//if ($stmt = $mysqli->prepare("SELECT 'All Vendors' AS Filter UNION ALL SELECT a.Filter AS Filter FROM adhoc_filters a WHERE a.Type='Vendor Name' AND a.Filter!='All Vendors'")) {
if ($stmt = $mysqli->prepare("SELECT DISTINCT a.vendor_name,b.vendor_id FROM adhoc_vendor AS a INNER JOIN adhoc_vendor_xref AS b ON a.vendor_id=b.vendor_id $fsubsql ORDER BY a.vendor_name")) {
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_vendorname,$vendorid);
		while($stmt->fetch()){
			if(empty($_vendorname)) continue;
			$vnamearr[]=array($vendorid,$_vendorname);
			//$vnamearr=array_unique($vnamearr);
		}
	}else die("Nothing to show!");
}else die("Error Occured! Please contact Vervantis admin.");


//if ($stmt = $mysqli->prepare("SELECT 'All Services' AS Filter UNION ALL SELECT a.Filter AS Filter FROM adhoc_filters a WHERE a.Type='Service Type' AND a.Filter!='All Services'")) {
if ($stmt = $mysqli->prepare("SELECT DISTINCT a.service_type,b.service_type_id FROM adhoc_service_type AS a INNER JOIN adhoc_service_type_xref AS b ON a.service_id=b.service_type_id $fsubsql ORDER BY a.service_type")) {
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_servicetype,$_serviceid);
		while($stmt->fetch()){
			if(empty($_servicetype)) continue;
			$servicesarr[]=array($_serviceid,$_servicetype);
			//$servicesarr=array_unique($servicesarr);
		}
	}else die("Nothing to show!");
}else die("Error Occured! Please contact Vervantis admin.");


//if ($stmt = $mysqli->prepare("SELECT 'All States' AS Filter UNION ALL SELECT a.Filter AS Filter FROM adhoc_filters a WHERE a.Type='Location State/Province' AND a.Filter!='All States'")) {
if ($stmt = $mysqli->prepare("SELECT DISTINCT a.state_name,b.state_id FROM adhoc_state AS a INNER JOIN adhoc_state_xref AS b ON a.state_id=b.state_id $fsubsql ORDER BY a.state_name")) {
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_statename,$_stateid);
		while($stmt->fetch()){
			if(empty($_statename)) continue;
			$statesarr[]=array($_stateid,$_statename);
			//$statesarr=array_unique($statesarr);
		}
	}else die("Nothing to show!");
}else die("Error Occured! Please contact Vervantis admin.");


?>
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Services</b></label>
					<br>
					<select name="Service" id="Service">
						<option value="">All Services</option>
						<?php
						foreach($servicesarr as $vl){ ?>
							<option value="<?php echo $vl[0]; ?>"><?php echo $vl[1]; ?></option>
						<?php }
						?>
					</select>
				</div>

				<div class="" style="padding-left:15px; float:left">
					<label><b>State</b></label>
					<br>
					<select name="St" id="St">
						<option value="">All States</option>
						<?php
						foreach($statesarr as $vl){ ?>
							<option value="<?php echo $vl[0]; ?>"><?php echo $vl[1]; ?></option>
						<?php }
						?>
					</select>
				</div>
			</div>

			<div class="row" style="padding-left:15px;">
				<br>

				<div class="" style="padding-left:15px; float:left;">
					<label><b>Vendor</b></label>
					<br>
					<select name="Vendor" id="Vendor">
						<option value="">All Vendors</option>
						<?php
						foreach($vnamearr as $vl){ ?>
							<option value="<?php echo $vl[0]; ?>"><?php echo $vl[1]; ?></option>
						<?php }
						?>
					</select>
				</div>

			</div>