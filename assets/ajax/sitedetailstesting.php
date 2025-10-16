<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
	die('Access Restricted');

if(isset($_GET['id']) and $_GET['id'] != "")
	$id=$mysqli->real_escape_string($_GET['id']);
else
	die('Wrong parameters provided');

$cid="";
if(isset($_GET['cid']) and $_GET['cid'] != "") $cid=$mysqli->real_escape_string($_GET['cid']);
//if($cid==9) $cid=32;
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $cid=$_SESSION["company_id"];
?>
<style>
.sitetable{
border-spacing:5px !important;
border-collapse:unset !important;
}
</style>
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<?php if(!isset($_GET['fromdashboard'])){ ?>
			<script>
				$(".fromthirdpage").css("display", "none");
				$(".fromsecondpage").css("display", "block");
			</script>
			<?php }else{ ?>
			<script>
				$(".fromthirdpage").css("display", "none");
				$(".fromsecondpage").css("display", "none");
				$(".fromdashboard").css("display", "block");
			</script>
			<?php } ?>
		</div>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		<?php
			if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id']) and 1==2){
		?>
			<button class="btn-primary pull-right" align="right" id="add-new-account" style="height: 30px !important;width: auto !important;margin-bottom: 4px;">Add Account</button>
		<?php } ?>
		</div>
	</article>
</div>

<!-- row -->
<div class="row siterow">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--1" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Site Details </h2>

			</header>
			<div class="row">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
		<?php
		$address=array();
	//if ($stmt = $mysqli->prepare('SELECT c.company_name,s.site_name,s.division,s.service_address1,s.service_address2,s.service_address3,s.city,s.state,s.postal_code,s.`zip+4`,s.country,s.site_status,s.site_number,s.region,s.contact1,s.phone1,s.fax1,s.email1,s.square_footage,c.site_close_btn FROM sites s INNER JOIN company c ON c.company_id=s.company_id where s.site_number="'.$id.'" '.(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" and c.company_id = ".$cid).' LIMIT 1')) {
	if ($stmt = $mysqli->prepare('SELECT
	b.company_name AS company_name,
	a.SiteName AS site_name, 
	a.Division AS division,
	a.SiteAddress1 AS service_address1, 
	a.SiteAddress2 AS service_address2, 
	a.SiteAddress3 AS service_address3, 
	a.SiteCity AS city, 
	a.SiteState AS state, 
	LEFT(a.SiteZip,5) AS postal_code, 
	RIGHT(a.SiteZip,4) AS `zip+4`, 
	a.SiteCountry AS country, 
	a.SiteStatus AS site_status,  
	a.SiteNumber AS site_number, 
	a.Region AS region, 
	a.ContactName1 AS contact1, 
	a.ContactPhone1 AS phone1, 
	a.ContactFax1 AS fax1, 
	a.ContactEmail1 AS email1, 
	a.SquareFootage AS square_footage, 
	b.site_close_btn AS site_close_btn
FROM
	ubm_database.tblSites AS a
LEFT JOIN
	vervantis.company AS b
ON 
	a.ClientID = b.company_id 
WHERE
	a.SiteID="'.$id.'"
	'.(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" AND IF(a.ClientID=32, 9, a.ClientID) = ".$cid).' 
	AND a.DeleteStatus=0
	LIMIT 1')) {

//('SELECT c.company_name,s.site_name,s.division,s.service_address1,s.service_address2,s.service_address3,s.city,s.state,s.postal_code,s.`zip+4`,s.country,s.site_status,s.site_number,s.region,s.contact1,s.phone1,s.fax1,s.email1,s.square_footage FROM sites s, company c where s.id='.$id.' and c.id=s.company_id LIMIT 1')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($company_name,$site_name,$site_division,$service_address1,$service_address2,$service_address3,$city,$state,$postal_code,$zip4,$country,$site_status,$site_number,$region,$contact1,$phone1,$fax1,$email1,$square_footage,$site_close_btn);
			$stmt->fetch();
				if(@trim($service_address1) != "")
					$address[]=@trim($service_address1);
				if(@trim($service_address2) != "")
					$address[]=@trim($service_address2);
				if(@trim($service_address3) != "")
					$address[]=@trim($service_address3);
				$ts=$id.rand(650,900);
				if($zip4 != "" and $zip4 != "NULL") $postal_code= $postal_code." - ".$zip4;
				?>
					<table class='sitetable'>
						<tr><th>Site Number</th><td>
							<?php echo $site_number; ?>
						</td></tr>
						<tr><th>Site Name</th><td>
							<?php echo $site_name; ?>
						</td></tr>
						<tr><th>Division</th><td>
							<?php echo $site_division; ?>
						</td></tr>
						<tr><th>Region</th><td>
							<?php echo $region; ?>
						</td></tr>
						<tr><th>Address</th><td><?php //echo implode('<br />',$address); ?>
							<?php if(@trim($service_address1) != ""){?>
							<?php echo $service_address1; ?><br />
							<?php }
							if(@trim($service_address2) != ""){
							?>
							<?php echo $service_address2; ?><br />
							<?php }
							if(@trim($service_address3) != ""){
							?>
							<?php echo $service_address3; ?>
							<?php } ?>
						</td></tr>
						<tr><th>City</th><td>
							<?php echo $city; ?>
						</td></tr>
						<tr><th>State</th><td>
							<?php echo $state; ?>
						</td></tr>
						<tr><th>Country</th><td>
							<?php echo $country; ?>
						</td></tr>
						<tr><th>Postal Code</th><td>
							<?php echo $postal_code; ?>
						</td></tr>
						<tr><th>Site Status</th><td>
							<?php  echo (($site_status==1 or is_null($site_status))?"Active":"Inactive"); ?>
						</td></tr>

						<tr><th>Contact</th><td>
							<?php echo $contact1; ?>
						</td></tr>
						<tr><th>Phone</th><td>
							<?php echo format_phone($phone1); ?>
						</td></tr>
						<tr><th>Fax</th><td>
							<?php echo format_phone($fax1); ?>
						</td></tr>
						<tr><th>Email</th><td>
							<?php echo $email1; ?>
						</td></tr>
						<tr><th>Square Feet</th><td>
							<?php echo $square_footage; ?>
						</td></tr>
						<tr><th>Weather Station</th><td>
							<?php echo ""; ?>
						</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">
							<?php if((isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)) or $site_close_btn=="ON"){ ?><button class="btn-primary" align="right" onclick="reqclosesite('<?php echo $site_number; ?>')" style="height: 30px !important;width: auto !important;">Request to Close Site</button>
							<?php } ?>
						</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					</table>
				<?php
		}else
			die('Wrong parameters provided');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}//else
		//die('Error Occured! Please try after sometime.');
	if(isset($_GET['noback'])) $tmpurl="&noback=true"; else $tmpurl="";
		?>
			</div>
			<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" id="load-sdetails">
				<iframe src="assets/ajax/detailstesting.php?cid=<?php echo $cid; ?>&id=<?php echo $id.$tmpurl; ?>" style="width:100%;height:383px" frameBorder="0" scrolling="no"></iframe>
			</div>
			</div>
		</div>
	</article>
</div>
<?php
function format_phone($phone)
{
		if(empty($phone)) return "";
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    elseif(strlen($phone) == 1)
		return "";
	else
        return $phone;
}
?>
<script>
$(window).off("popstate");
history.pushState(null, null, '');
window.addEventListener('popstate', navback);
window.scrollTo(0,0);

function navback(){
	history.pushState(null, null, '');
	if($('.fromsecondpage').css('display') == 'block')
	{
		move_back();
	}else if($('.fromthirdpage').css('display') == 'block'){
		move_invoice_back();
	}else if($('.fromdashboard').css('display') == 'block'){
		move_back_dashboard();
	}
}
/*$(window).on('popstate', function (e) {alert("2456");
    var state = e.originalEvent.state;
    if (state !== null) {
        //load content with ajax
    }
});
$(window).on('pushstate', function (e) {alert("2456");
    var state = e.originalEvent.state;
    if (state !== null) {
        //load content with ajax
    }
});*/
function move_back(){
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	$('.sitestable').show();
	$('#sitesdetails').html('');
	$(".fromthirdpage").css("display", "none");
	$(".fromsecondpage").css("display", "none");
	if($('#list-sites').is(':empty')){
		move_back_dashboard();
	}
}

function move_back_dashboard(){
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	window.removeEventListener("popstate", navback);
	//$('.sitestable').show();
	$('#sitesdetails').html('');
	$(".fromthirdpage").css("display", "none");
	$(".fromsecondpage").css("display", "none");
	parent.$('.dashboard-content').show();
	parent.$('.map-content').html('');
	parent.$('.map-content').hide();
}
function reqclosesite(sid){
	//$('#datatable_fixed_column').DataTable().ajax.reload();
	//otable.ajax.reload();
	$("#dialog-message").remove();
	parent.$('#response').html('');
	parent.$('#response').load('assets/ajax/req_servicetesting.php?action=close&cid=<?php echo $cid; ?>&sid='+sid+'');
};
<?php
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
?>
$(document).ready(function(){
	$("#add-new-account").click(function(){
		$('#response').load('assets/ajax/list-accountstesting.php?addnewacc=true&cid=<?php echo $cid; ?>&sid=<?php echo $id; ?>');
	});
});
<?php } ?>
</script>
