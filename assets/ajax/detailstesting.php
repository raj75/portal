<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

//ini_set('memory_limit', '-1');
//set_time_limit(0);

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");
//if($_SESSION["group_id"] != 1) die("Under Maintenance");
$user_one=$_SESSION["user_id"];
$cmpid=$_SESSION["company_id"];

$cid="";
if(isset($_GET['cid']) and $_GET['cid'] != "") $cid=$mysqli->real_escape_string($_GET['cid']);

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $cid=$_SESSION["company_id"];

$acc_close_btn="OFF";
if ($stmtcheck = $mysqli->prepare('SELECT acc_close_btn FROM company where company_id="'.$cid.'" LIMIT 1')) {
	$stmtcheck->execute();
	$stmtcheck->store_result();
	if ($stmtcheck->num_rows > 0) {
		$stmtcheck->bind_result($acc_close_btn);
		$stmtcheck->fetch();
	}
}
?>
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<?php

if(isset($_GET['id']) and $_GET['id'] != "")
{
	$id=$mysqli->real_escape_string($_GET['id']);
	$addr=array();
	if(!empty($cid)) $tmpsql=' AND a.ClientID="'.$cid.'" '; else $tmpsql='';
		
if ($stmt = $mysqli->prepare('SELECT
	a.SiteID AS `SiteID`,
	b.company_name AS `Company Name`, 
	a.SiteName AS `Site Name`, 
	a.SiteAddress1 AS `Address`, 
	a.SiteAddress2 AS `Address 2`, 
	a.SiteAddress3 AS `Address 3`, 
	a.SiteCity AS City, 
	a.SiteState AS State, 
	a.SiteZip AS `Postal Code`, 
	a.SiteCountry AS Country, 
	a.SiteNumber AS `Site Number`, 
	a.Latitude AS Latitude, 
	a.Longitude AS Longitude
FROM
	ubm_database.tblSites AS a
LEFT JOIN
	vervantis.company AS b
ON 
	a.ClientID = b.company_id
WHERE
	a.SiteID =  "'.$id.'" '.$tmpsql.'  LIMIT 1
')) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($sid,$sitecompanyname,$site_name,$service_address1,$service_address2,$service_address3,$city,$state,$postal_code,$country,$site_number,$latitude,$longitude);
			$stmt->fetch();
				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
					$address=$site_name.",".$service_address1.",".$service_address2.",".$service_address3.",".$city.",".$state.",".$country.",".$postal_code;
					if($service_address1 != "") $addr[]=urlencode($service_address1);
					if($service_address2 != "") $addr[]=urlencode($service_address2);
					if($service_address3 != "") $addr[]=urlencode($service_address3);
					if($city != "") $addr[]=urlencode($city);
					if($state != "") $addr[]=urlencode($state);
					if($country != "") $addr[]=urlencode($country);
		}else{
			die('Wrong parameters provided');
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}//else
		//die('Wrong parameters provided');
	$temp_sites=$tmp_aid=$tmmp_aid=$tmmmp_aid=$vendor_arr=$serviceg_arr=$accno_arr=array();
	$temp_acc="";
		
if ($stmt = $mysqli->prepare("SELECT DISTINCT
	e.ClientID,e.SiteID,
	a.AccountID AS account_id,
	a.AccountNumber AS account_number1,
	a.AccountAlt1 AS account_number2,
	a.AccountAlt2 AS account_number3,
	b.VendorName AS vendor_name,
	b.VendorID AS vendor_id,
	c.ServiceTypeID AS service_group_id,
	d.ServiceTypeName AS service_group,
	IF (a.AccountInactiveDate IS NULL AND (a.AccountStatus IS NULL OR a.AccountStatus = 1), 'Active','Inactive') AS `STATUS`
FROM
	ubm_database.tblAccounts AS a
LEFT JOIN
	ubm_database.tblVendors AS b
ON
		a.VendorID = b.VendorID
LEFT JOIN
	ubm_database.tblServices AS c
ON
		a.AccountID = c.AccountID AND
		a.ClientID = c.ClientID AND
		a.EntityID = c.EntityID
LEFT JOIN
		ubm_database.tblServiceTypes AS d
ON
		c.ServiceTypeID = d.ServiceTypeID
LEFT JOIN
		ubm_database.tblSiteAllocations AS e
ON
		a.ClientID = e.ClientID
		AND	a.EntityID = e.EntityID
		AND a.AccountID = e.AccountID
		AND c.ServiceTypeID = e.ServiceTypeID
WHERE
 	e.SiteID = '".$id."'
	".(($_SESSION['group_id'] != 1 and $_SESSION['group_id'] != 2 )?" AND a.ClientID='".$cmpid."' ":"")."
AND a.DeleteStatus=0
AND c.DeleteStatus=0
GROUP BY
	c.ServiceTypeID,
	b.VendorName,
	b.VendorAddress1,
	b.VendorPhone,
	b.VendorEmail,
	b.VendorFax,
	a.AccountNumber,
	a.AccountInactiveDate")) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($a_ClientID,$a_SiteID,$a_id,$a_account_number1,$a_account_number2,$a_account_number3,$a_vendor_name,$a_vendor_id,$a_serviceg_id,$a_serviceg,$a_status);	
			//$stmt->bind_result($a_id,$a_site_name,$a_vendor_name,$a_sites_id,$a_vendor_id,$a_account_number1,$a_account_number2,$a_account_number3,$a_meter_id);
			while($stmt->fetch()){
					$a_vendor_name= (empty($a_vendor_name)?'':addslashes($a_vendor_name));
					$temp_acc_sub=array();
					if($a_account_number1 != "")
						$temp_acc_sub[] = $a_account_number1;
					/*if($a_account_number2 != "")
						$temp_acc_sub[] = $a_account_number2;
					if($a_account_number3 != "")
						$temp_acc_sub[] = $a_account_number3;*/
					if(count($temp_acc_sub) and $a_id != 0 and $a_id != ""){
						$tmp_aid[]=$a_id;

						//$tmmmp_aid[$a_id]=array('accid'=>$a_id,'vendorname'=>$a_vendor_name,'servicegp'=>$a_serviceg,'status'=>$a_status,'accno'=>$a_account_number1);
						$tmmmp_aid[$a_id]=array('accid'=>$a_id,'vendorid'=>$a_vendor_id,'vendorname'=>$a_vendor_name,'serviceid'=>$a_serviceg_id,'servicegp'=>$a_serviceg,'status'=>$a_status,'accno'=>$a_account_number1);
						foreach($tmmmp_aid as $vl)
							$tmmp_aid[]=$vl;

						$tmmmp_aid=array();
						$vendor_arr[$a_vendor_id]=$a_vendor_name;
						$serviceg_arr[$a_serviceg_id]=$a_serviceg;
						$accno_arr[$a_id]=$a_account_number1;
					}
			}
		}//else
			//die('No accounts present for this site!');
		

		$vendor_arr=array_unique($vendor_arr);
		$serviceg_arr=array_unique($serviceg_arr);
		$accno_arr=array_unique($accno_arr);
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
$tmp_aid = array_filter($tmp_aid);
$tmp_aid=array_unique($tmp_aid);//print_r($tmp_aid);


if($latitude == "" or $longitude == "" or empty($latitude) or empty($longitude)){

	$latitude = 71.5388001;
	$longitude = -66.885417;
}

$loc_arr=array('lat' => $latitude,'lng' => $longitude);

if(count($loc_arr) == 2)
{
?>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&sensor=true"></script>-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&key=<?php echo $_ENV['GKEY']; ?>"></script>
<script type="text/javascript" src="../js/infobox.js"></script>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<?php
}

?>
<script type="text/javascript">
<?php
if(count($loc_arr) == 2)
{
?>
	function initialize() {

    var svService = new google.maps.StreetViewService();
	var myCenter = new google.maps.LatLng(<?php echo $loc_arr["lat"]; ?>, <?php echo $loc_arr["lng"]; ?>);
    var panoRequest = {
        location: myCenter,
        preference: google.maps.StreetViewPreference.NEAREST,
        radius: 1000,
        source: google.maps.StreetViewSource.OUTDOOR
    };

	  var mapCanvas = document.getElementById("map_canvas");
	  var mapOptions = {center: myCenter, zoom: 15};
	  var map = new google.maps.Map(mapCanvas, mapOptions);
	  var marker = new google.maps.Marker({position:myCenter});
	  marker.setMap(map);

    svService.getPanorama(panoRequest, function(panoData, status){
        if (status === google.maps.StreetViewStatus.OK) {
            panorama = new google.maps.StreetViewPanorama(
                document.getElementById('map_street'),
                {
                    pano: panoData.location.pano,
                    pov: {
                        heading: 10,
                        pitch: 10
                    }
                });
			map.setStreetView(panorama);
        } else {
            //Handle other statuses here
        }
    });

	}
<?php
}
?>
<?php
if(isset($tmp_aid) and is_array($tmp_aid) and count($tmp_aid)> 0){
	$tmp_aid = array_filter($tmp_aid);
	?>var temarr=[];load_sacc1('<?php echo implode(",",$tmp_aid); ?>',temarr,'<?php echo $cid; ?>');<?php
}else{
?>
	parent.$('#sitesaccountcont').remove();
	parent.$('.siterow').after('<div id="sitesaccountcont" style="margin:0 !important;padding:0 !important;"><div class="row" style="background-color: #fff;margin: 20px 0;text-align:center;">No accounts present for this site!</div></div>');
<?php
}
?>
function load_sacc11(aids,sno,cid,arrparam)
{	//alert(arrparam);

	//parent.$('#sitesaccount').fadeOut(600);
	//parent.$('#sitesaccount').hide('slow');
	parent.$('#sitesaccountcont').remove();
	parent.$('.siterow').after('<div id="sitesaccountcont" style="margin:0 !important;padding:0 !important;"></div>');
	parent.$('#sitesaccountcont').load('assets/ajax/detailstesting.php?cid='+cid+'&pc=1&naids='+aids+'&sno=<?php echo $id; ?>&params='+arrparam);
}
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function editacc(aid)
{
	parent.$('#response').html('');
	parent.$('#response').load('assets/ajax/list-accountstesting.php?aid='+aid+'&editaid=true');
}
function deleteacc(aid)
{
	alert("Under Mainteinance");
}
<?php } ?>
</script>
</head>
<body <?php if(count($loc_arr) == 2 ){ ?>onload="initialize()"<?php }?>>
	<div id="map_street" style="height: 354px; width:33%;float:left;"></div>
	<div id="map_canvas" style="height: 354px; width:66%;float:right;"></div>
	<p></p>
</body>
</html>
<?php
//$tmmp_aid[$a_id]=array("accid"=>$a_id,"vendorname"=>$a_vendor_name,"servicegp"=>$a_serviceg);

	if(isset($tmp_aid) and count($tmp_aid)){
//print_r($serviceg_arr);
//print_r($vendor_arr);
//print_r($accno_arr);
$tmpservice="";$kk=0;
foreach($serviceg_arr as $key => $value){$kk++; $tmpservice= $tmpservice."<option value='".$key."'>".$value."</option>"; }

$tmpvendor="";
foreach((array)$vendor_arr as $kyt => $vll){$tmpvendor= $tmpvendor."<option value='".$kyt."'>".$vll."</option>";}

$tmpaccc="";
foreach((array)$accno_arr as $kyt => $vll){$tmpaccc= $tmpaccc."<option value='".$vll."'>".$vll."</option>";}
//print_r($tmmp_aid);
$acccarr=str_replace('"','\'',json_encode($tmmp_aid));//print_r($acccarr);
$acccarr=str_replace('(','\(',$acccarr);//print_r($acccarr);
$acccarr=str_replace(')','\)',$acccarr);//print_r($acccarr);
?>
	<script type="text/javascript">
parent.$('.siterow').append( "<div id='accfilter' style='text-align:center;'><style>#accfilter .sssdrp{width:auto !important;font-weight: 400 !important;} #accfilter table{width: auto !important;border: none !important;} #accfilter table th{border:none !important;padding: 2px!important;}#accfilter table tr{background: unset !important;}option{display:block !important;}</style><table class='table table-striped table-bordered table-hover dataTable no-footer'><thead><tr class='' role='row'><th class='hasinput' rowspan='1' colspan='1'><select id='selservice' class='form-control sssdrp'><option value=''>Select Service Group</option><?php echo $tmpservice; ?></select></th><th class='hasinput' rowspan='1' colspan='1'><select id='selvendor' class='form-control sssdrp'><option value=''>Select Vendor Name</option><?php echo $tmpvendor; ?></select></th><th class='hasinput' rowspan='1' colspan='1'><select id='selstatus' class='form-control sssdrp'><option value='Active'>Active</option><option value='Inactive'>Inactive</option><option value=''>All</option></select></th><th class='hasinput' rowspan='1' colspan='1'><select id='searchacc' class='form-control sssdrp'><option value=''>Select Account</option><?php echo $tmpaccc; ?></select ></th><th class='hasinput' rowspan='1' colspan='1'><button class='btn-primary' align='right' onclick='resetfilters()' style='height: 31px !important;width: auto !important;'>Reset</button></th></tr></thead></table></div><script>var accarr=<?php echo $acccarr;?>;$('#selstatus').on('change',function(e){var servicesel=$('#selservice').val();var vendorsel=$('#selvendor').val();var accsel=$('#searchacc').val();var optionSelected=$('option:selected',this);var valueSelected=this.value;var arrparam=[servicesel,vendorsel,valueSelected,accsel];var faids=[];var vaids=[];var saids=[];var thirdids=[];var i;for(i=0;i<accarr.length;i++){if(valueSelected==''||accarr[i].status==valueSelected){if(servicesel!=''&&vendorsel!=''&&accsel!=''){if(accarr[i].serviceid==servicesel&&accarr[i].vendorid==vendorsel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&vendorsel==''&&accsel!=''){if(accarr[i].serviceid==servicesel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&vendorsel!=''&&accsel==''){if(accarr[i].serviceid==servicesel&&accarr[i].vendorid==vendorsel){faids.push(accarr[i].accid)}}else if(servicesel==''&&vendorsel!=''&&accsel!=''){if(accarr[i].vendorid==vendorsel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel==''&&vendorsel==''&&accsel!=''){if(accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel==''&&vendorsel!=''&&accsel==''){if(accarr[i].vendorid==vendorsel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&vendorsel==''&&accsel==''){if(accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else{faids.push(accarr[i].accid)}}}for(i=0;i<accarr.length;i++){if($.inArray(accarr[i].accid,faids)>-1){vaids.push(accarr[i].vendorid);saids.push(accarr[i].serviceid);thirdids.push(accarr[i].accno)}}$('#selvendor option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),vaids)>-1)$(this).show();else $(this).hide()}});$('#selservice option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),saids)>-1)$(this).show();else $(this).hide()}});$('#searchacc option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),thirdids)>-1)$(this).show();else $(this).hide()}});load_sacc11(''+faids.join()+'','<?php echo $id; ?>','<?php echo $cid; ?>', '' + arrparam + '')});$('#selvendor').on('change',function(e){var servicesel=$('#selservice').val();var statussel=$('#selstatus').val();var accsel=$('#searchacc').val();var optionSelected=$('option:selected',this);var valueSelected=this.value;var arrparam=[servicesel,valueSelected,statussel,accsel];var faids=[];var vaids=[];var saids=[];var thirdids=[];var i;for(i=0;i<accarr.length;i++){if(valueSelected==''||accarr[i].vendorid==valueSelected){if(servicesel!=''&&statussel!=''&&accsel!=''){if(accarr[i].serviceid==servicesel&&accarr[i].status==statussel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&statussel==''&&accsel!=''){if(accarr[i].serviceid==servicesel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&statussel!=''&&accsel==''){if(accarr[i].serviceid==servicesel&&accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(servicesel==''&&statussel!=''&&accsel!=''){if(accarr[i].status==statussel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel==''&&statussel==''&&accsel!=''){if(accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(servicesel==''&&statussel!=''&&accsel==''){if(accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(servicesel!=''&&statussel==''&&accsel==''){if(accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else{faids.push(accarr[i].accid)}}}for(i=0;i<accarr.length;i++){if($.inArray(accarr[i].accid,faids)>-1){vaids.push(accarr[i].serviceid);saids.push(accarr[i].status);thirdids.push(accarr[i].accno)}}$('#selservice option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),vaids)>-1)$(this).show();else $(this).hide()}});$('#selstatus option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),saids)>-1)$(this).show();else $(this).hide()}});$('#searchacc option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),thirdids)>-1)$(this).show();else $(this).hide()}});load_sacc11(''+faids.join()+'','<?php echo $id; ?>','<?php echo $cid; ?>',''+arrparam+'')});$('#selservice').on('change',function(e){var vendorsel=$('#selvendor').val();var statussel=$('#selstatus').val();var accsel=$('#searchacc').val();var optionSelected=$('option:selected',this);var valueSelected=this.value;var arrparam=[valueSelected,vendorsel,statussel,accsel];var faids=[];var vaids=[];var saids=[];var thirdids=[];var i;for(i=0;i<accarr.length;i++){if(valueSelected==''||accarr[i].serviceid==valueSelected){if(vendorsel!=''&&statussel!=''&&accsel!=''){if(accarr[i].vendorid==vendorsel&&accarr[i].status==statussel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel==''&&accsel!=''){if(accarr[i].vendorid==vendorsel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel!=''&&accsel==''){if(accarr[i].vendorid==vendorsel&&accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel!=''&&accsel!=''){if(accarr[i].status==statussel&&accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel==''&&accsel!=''){if(accarr[i].accno==accsel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel!=''&&accsel==''){if(accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel==''&&accsel==''){if(accarr[i].vendorid==vendorsel){faids.push(accarr[i].accid)}}else{faids.push(accarr[i].accid)}}}for(i=0;i<accarr.length;i++){if($.inArray(accarr[i].accid,faids)>-1){vaids.push(accarr[i].vendorid);saids.push(accarr[i].status);thirdids.push(accarr[i].accno)}}$('#selvendor option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),vaids)>-1)$(this).show();else $(this).hide()}});$('#selstatus option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),saids)>-1)$(this).show();else $(this).hide()}});$('#searchacc option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),thirdids)>-1)$(this).show();else $(this).hide()}});load_sacc11(''+faids.join()+'','<?php echo $id; ?>','<?php echo $cid; ?>',''+arrparam+'')});$('#searchacc').on('change',function(e){var vendorsel=$('#selvendor').val();var statussel=$('#selstatus').val();var servicesel=$('#selservice').val();var optionSelected=$('option:selected',this);var valueSelected=this.value;var arrparam=[servicesel,vendorsel,statussel,valueSelected];var faids=[];var vaids=[];var saids=[];var thirdids=[];var i;for(i=0;i<accarr.length;i++){if(valueSelected==''||accarr[i].accno==valueSelected){if(vendorsel!=''&&statussel!=''&&servicesel!=''){if(accarr[i].vendorid==vendorsel&&accarr[i].status==statussel&&accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel==''&&servicesel!=''){if(accarr[i].vendorid==vendorsel&&accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel!=''&&servicesel==''){if(accarr[i].vendorid==vendorsel&&accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel!=''&&servicesel!=''){if(accarr[i].status==statussel&&accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel==''&&servicesel!=''){if(accarr[i].serviceid==servicesel){faids.push(accarr[i].accid)}}else if(vendorsel==''&&statussel!=''&&servicesel==''){if(accarr[i].status==statussel){faids.push(accarr[i].accid)}}else if(vendorsel!=''&&statussel==''&&servicesel==''){if(accarr[i].vendorid==vendorsel){faids.push(accarr[i].accid)}}else{faids.push(accarr[i].accid)}}}for(i=0;i<accarr.length;i++){if($.inArray(accarr[i].accid,faids)>-1){vaids.push(accarr[i].vendorid);saids.push(accarr[i].status);thirdids.push(accarr[i].servicegp)}}$('#selvendor option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),vaids)>-1)$(this).show();else $(this).hide()}});$('#selservice option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),thirdids)>-1)$(this).show();else $(this).hide()}});$('#selstatus option').each(function(){if($(this).val()!=''){if($.inArray($(this).val(),saids)>-1)$(this).show();else $(this).hide()}});load_sacc11(''+faids.join()+'','<?php echo $id; ?>','<?php echo $cid; ?>',''+arrparam+'')});function resetfilters(){$('#selservice').val('');$('#selvendor').val('');$('#selstatus').val('Active').change();$('#searchacc').val('')}$('#selstatus').val('Active').change()<\/script>" );
	</script>
<?php
	}
}else if(isset($_GET['aid']) and  $_GET['aid'] != "" and $_GET['aid'] > 0){
	$aid=$mysqli->real_escape_string($_GET["aid"]);

$temp_invoice=$__mtid=$temp_acc_sub=array();
$temp_acc="";
//New Query
	if ($stmt = $mysqli->prepare('SELECT a.meter_number,vendor_id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3, FROM accounts a,vendor v where a.id='.$aid.' and a.vendor_id=vendor_id and a.meter_number != "" and a.meter_number != 0')) {

//('SELECT a.meter_id,v.id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3, FROM accounts a,vendor v where a.id='.$aid.' and a.vendor_id=v.id and a.meter_id != "" and a.meter_id != 0')) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_mtid,$_v_vid,$_v_name,$_a1,$_a2,$_a3);
			while($stmt->fetch()) {
				$__mtid[]=$_mtid;
			}

			if($_a1 != "")
				$temp_acc_sub[] = $_a1;
			if($_a2 != "")
				$temp_acc_sub[] = $_a2;
			if($_a3 != "")
				$temp_acc_sub[] = $_a3;

			if(count($temp_acc_sub))
				$temp_acc = implode("-",$temp_acc_sub);
			else
				$temp_acc = "N/A";
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

if(count($__mtid)==0)
	die("No data to show");

//New Query Ends
	/*if ($stmt = $mysqli->prepare('SELECT sg.service_group,u.id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM `usage` u,service_group sg where u.meter_number IN('.implode(",",$__mtid).') and u.service_group_id=sg.service_group_id and sg.service_group_id != "" and sg.service_group_id != 0 order by sg.service_group_id,u.interval_end, u.meter_number desc')) {*/
		
	if ($stmt = $mysqli->prepare('SELECT
	b.ServiceTypeName AS service_group,
	a.id AS id, 
	a.meter_number AS meter_number, 
	a.interval_start AS interval_start, 
	a.interval_end AS interval_end, 
	a.interval_value AS interval_value, 
	a.unit_of_measure AS unit_of_measure, 
	a.cost AS cost
FROM
	vervantis.`usage` AS a
LEFT JOIN
	ubm_database.tblServiceTypes AS b
ON 
	a.service_group_id = b.ServiceTypeID
WHERE
	u.meter_number IN ( '.implode(",",$__mtid).' ) 
	AND 
	b.ServiceTypeID != "" 
	AND b.ServiceTypeID != 0 
ORDER BY
	b.ServiceTypeID,
	a.interval_end,
	a.meter_number DESC
LIMIT 10')) {

//('SELECT sg.service_group,u.id, u.meter_id, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM `usage` u,service_group sg where u.meter_id IN('.implode(",",$__mtid).') and u.service_group_id=sg.id and sg.service_group_id != "" and sg.service_group_id != 0 order by sg.service_group_id,u.interval_end, u.meter_id desc')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_sg,$_u_id,$_u_meter_id,$_u_interval_start,$_u_interval_end,$_u_interval_value,$_u_unit_of_measure,$_u_cost);
			while($stmt->fetch()) {
				$stime=strtotime($_u_interval_start);
				$smonth=date("F",$stime);
				$syear=date("Y",$stime);

				$etime=strtotime($_u_interval_end);
				$emonth=date("F",$etime);
				$eyear=date("Y",$etime);

				$datediff = $etime - $stime;
				$diff_date = floor($datediff/(60*60*24));

				if($diff_date == 0){
					$per_day_value=$per_day_cost=0;
				}else{
					$per_day_value=$_u_interval_value/$diff_date;
					$per_day_cost=$_u_cost/$diff_date;
				}

				//Same Date
				if(($_u_interval_start == $_u_interval_end) or ($syear == $eyear and $smonth == $emonth and $_u_interval_start != $_u_interval_end))
				{
					$temp_invoice[$_sg][$syear][$smonth][]=array("meter_id"=>$_u_meter_id,"units"=>$_u_interval_value,"sinterval"=>$_u_interval_start,"einterval"=>$_u_interval_end,"measure"=>$_u_unit_of_measure,"cost"=>$_u_cost,"sg"=>$_sg,"_u_id"=>$_u_id);
				}else{//if($_u_interval_start != $_u_interval_end and $syear == $eyear and $smonth != $emonth){
					$time   = $stime;
					$last   = date('m-Y', $etime);

					do {
						$month = date('m-Y', $time);
						$total = date('t', $time);
						$tmonth=date('F', $time);
						$tyear=date('Y', $time);

						if($syear==$tyear and $smonth==$tmonth)
						{
							$daysRemaining = (int)date('t', $stime) - (int)date('j', $stime);
							$temp_invoice[$_sg][$syear][$smonth][]=array("meter_id"=>$_u_meter_id,"units"=>$per_day_value*$daysRemaining,"sinterval"=>$_u_interval_start,"einterval"=>$_u_interval_end,"measure"=>$_u_unit_of_measure,"cost"=>$per_day_cost*$daysRemaining,"sg"=>$_sg,"_u_id"=>$_u_id);
						}elseif($eyear==$tyear and $emonth==$tmonth)
						{
							$daysPast = (int)date('j', $etime);
							$temp_invoice[$_sg][$eyear][$emonth][]=array("meter_id"=>$_u_meter_id,"units"=>$per_day_value*$daysPast,"sinterval"=>$_u_interval_start,"einterval"=>$_u_interval_end,"measure"=>$_u_unit_of_measure,"cost"=>$per_day_cost*$daysPast,"sg"=>$_sg,"_u_id"=>$_u_id);
						}else{
							$daysOfMonth = (int)date('t', $stime);
							$temp_invoice[$_sg][$tyear][$tmonth][]=array("meter_id"=>$_u_meter_id,"units"=>$per_day_value*$daysOfMonth,"sinterval"=>$_u_interval_start,"einterval"=>$_u_interval_end,"measure"=>$_u_unit_of_measure,"cost"=>$per_day_cost*$daysOfMonth,"sg"=>$_sg,"_u_id"=>$_u_id);
						}

						$time = strtotime('+1 month', $time);
					} while ($month != $last);
				}
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
	//print_r($temp_invoice);exit();
?>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
.usage{
  background-color: #fff;
  margin: 20px 0;
  padding-top: 29px;
}
.dataTables_paginate ul li {padding:0px !important;}
.dataTables_paginate ul li a{margin:-1px !important;}
</style>
<?php
//New Codes
$d_i=0;
$ts1=rand(650,900);
foreach($temp_invoice as $_ky=>$_vl)
{
	++$d_i;
	$ts=rand(650,900);
?>
	<div class="row usage">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<h3><?php echo $_ky; ?></h3>
				Vendor3: <?php echo $_v_name; ?><br />
				Vendor Address: <br />
				Vendor Phone: <br />
				Vendor Email: <br />
				Vendor Fax: <br />
				Account #: <?php echo $temp_acc; ?><br />
				Status: Inactive <br />
				Meters: <br />
				Rate: <br />
				<?php if((isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)) or $acc_close_btn=="ON"){ ?>
					<button class="btn-primary pull-right" align="right" id="req-account-close" style="height: 30px !important;width: auto !important;">Request Account Close</button>
				<?php } ?>
			</div>
			<div class="jarviswidget jarviswidget-color-blueDark col-xs-9 col-sm-9 col-md-9 col-lg-9" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Usage Details </h2>
				</header>
				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">

						<table id="datatable_fixed_column_acc<?php echo $ts1.$d_i; ?>" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>Meter Number(s)</th>
									<th>Status</th>
									<th>Year</th>
									<th>Month</th>
									<th>Cost</th>
									<th>Usage</th>
									<th>Unit of Measure</th>
									<th>Invoice</th>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
									<th>Action</th>
<?php } ?>
								</tr>
							</thead>
							<tbody>
<?php
krsort($_vl);
	foreach($_vl as $_kys=>$_vls)
	{
		foreach($_vls as $_kyy=>$_vll)
		{
			$_dis_meter_id=$_dis_units=$_dis_interval=$_dis_cost=$_dis_invoice=$_dis_edit=array();
			$_dis_measure="";
			foreach($_vll as $_kk=>$_vv)
			{
				$_dis_meter_id[]=$_vv["meter_id"];
				$_dis_units[]=$_vv["units"];
				$_dis_interval[]="<a href='javascript:void(0);' onclick='load__invoice(".$_vv['_u_id'].")'>".$_vv["sinterval"]." - ".$_vv["einterval"]."</a>";
				$_dis_cost[]=$_vv["cost"];
				$_dis_measure=$_vv["measure"];
				$_dis_invoice[]="<a href='javascript:void(0);' onclick='load__invoice(".$_vv['_u_id'].")'>Link</a>";
				if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
					$_dis_edit[]="&nbsp;&nbsp;<button onclick='loadeusage(".$_vv['_u_id'].")' title='View/Edit Usage Details' class='btn btn-xs btn-default'><i class='fa fa-pencil'></i></button><button onclick='deleteusage(".$_vv['_u_id'].")' title='Delete Usage' class='btn btn-xs btn-default'><i class='fa fa-times'></i></button>&nbsp;&nbsp;";
				}
			}
	?>
						<tr>
							<td><?php echo @implode(",",@array_unique($_dis_meter_id)); ?></td>
							<td><?php echo "Active"; ?></td>
							<td><?php echo $_kys; ?></td>
							<td><?php echo $_kyy; ?></td>
							<td><?php echo @round(@array_sum($_dis_cost),2); ?></td>
							<td><?php echo @round(@array_sum($_dis_units),2); ?></td>
							<td><?php echo $_dis_measure; ?></td>
							<td><?php echo @implode(", ",$_dis_interval); ?></td>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
							<td><?php echo @implode("<br />",$_dis_edit); ?></td>
<?php } ?>
						</tr>
	<?php
		}
	}
?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
	</div>
<?php
}
?>


</div>

<!-- end row -->
<!-- end widget grid -->
<div id="dialog_invoice" title="Invoice"></div>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();
	var pagefunction = function() {
		/* BASIC ;*/

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};
		//$(document).load(function(){
			$("table[id^='datatable_fixed_column_acc']").DataTable( {
				"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
				"pageLength": 12,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"pageLength": 5,
				"paging": true,
				"dom": 'Blfrtip',
				"buttons": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					}
				],
				"autoWidth" : true
			});
		//} );
	};
	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

function load__invoice(id) {
	$('#dialog_invoice').html('<img src="<?php echo ASSETS_URL; ?>/assets/img/invoice/usage/invoice_'+id+'.jpg" width="100%" height="100%" />');
	$('#dialog_invoice').dialog({
		autoOpen : false,
		width : 800,
		resizable : false,
		modal : true,
		title : "Invoice",
		buttons : [{
			html : "<i class='fa fa-times'></i>&nbsp; Close",
			"class" : "btn btn-default",
			click : function() {
				$(this).dialog("close");
			}
		}]
	});
	$('#dialog_invoice').dialog('open');
}
$(document).ready(function(){
	//$(window).scrollTop($('.usage').offset().top);
	/*$('html, body').animate({
        scrollTop: $('.usage').offset().top
    }, 2000);*/
});
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadeusage(uid)
{
	parent.$("#response").html('');
	parent.$("#response").load('assets/ajax/list-usagetesting.php?edituid=true&uid='+uid);
}
function deleteusage(uid)
{
	alert("Under Maintainance");
}
<?php } ?>
</script>

<?php
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
}else if(isset($_GET['aids']) and  $_GET['aids'] != "" and isset($_GET['sno']) and  $_GET['sno'] != ""){
	$aid_arr=explode(",",$_GET['aids']);
	$sno=$mysqli->real_escape_string($_GET['sno']);

	if(is_array($aid_arr) and count($aid_arr)){
		foreach($aid_arr as $kyyyy=>$vllll){
				$aid=$mysqli->real_escape_string($vllll);

			$temp_invoice=$__mtid=$temp_acc_sub=array();
			$temp_acc="";

			//New Query
				if ($stmt = $mysqli->prepare('SELECT a.meter_number,v.vendor_id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3 FROM accounts a,vendor v where a.id='.$aid.' and a.vendor_id=v.vendor_id and a.meter_number != "" and a.meter_number != 0')) {

//('SELECT a.meter_id,v.id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3 FROM accounts a,vendor v where a.id='.$aid.' and a.vendor_id=v.id and a.meter_id != "" and a.meter_id != 0')) {

					$stmt->execute();
					$stmt->store_result();
					if ($stmt->num_rows > 0) {
						$stmt->bind_result($_mtid,$_v_vid,$_v_name,$_a1,$_a2,$_a3);
						while($stmt->fetch()) {
							$__mtid[]=$_mtid;
						}

						if($_a1 != "")
							$temp_acc_sub[] = $_a1;
						if($_a2 != "")
							$temp_acc_sub[] = $_a2;
						if($_a3 != "")
							$temp_acc_sub[] = $_a3;

						if(count($temp_acc_sub))
							$temp_acc = implode("-",$temp_acc_sub);
						else
							$temp_acc = "N/A";
					}
				}

			if(count($__mtid)==0)
				die("No data to show");

			//New Query Ends
/*$stmt = $mysqli->prepare('SELECT sg.service_group,u.id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM accounts a, `usage` u, sites s,service_group sg WHERE u.meter_number IN('.implode(",",$__mtid).') and a.company_id = u.company_id and a.vendor_id = u.vendor_id and a.account_number1 = u.account_number and a.meter_number = u.meter_number and s.company_id = a.company_id and s.site_number = a.site_number and u.service_group_id=sg.service_group_id and sg.service_group_id != "" and sg.service_group_id != 0')*/

				//print_r($temp_invoice);exit();
			?>
			<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
			<style>
			.usage{
			  background-color: #fff;
			  margin: 20px 0;
			  padding-top: 29px;
			}
			.dataTables_paginate ul li {padding:0px !important;}
			.dataTables_paginate ul li a{margin:-1px !important;}
			.dt-buttons{
			float: right !important;
			margin: 0.9% auto !important;
			}
			.dataTables_wrapper .dataTables_length{
			float: right !important;
			margin: 1% 1% !important;
			}
			.dataTables_wrapper .dataTables_filter{
			float: left !important;
			width: auto !important;
			margin: 1% 1% !important;
			text-align:left !important;
			}
			.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
			.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
			.no-padding .dataTables_wrapper table, .no-padding>table{border-bottom:1px solid #cccccc !important;}
			</style>
			<?php
			//New Codes
			$d_i=0;
			$ts1=rand(650,900);
			foreach($temp_invoice as $_ky=>$_vl)
			{
				++$d_i;
				$ts=rand(650,900);
			?>
				<div class="row usage">
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							<h3><?php echo $_ky; ?></h3>
							Vendor: <?php echo $_v_name; ?><br />
							Vendor Address: <br />
							Vendor Phone: <br />
							Vendor Email: <br />
							Vendor Fax: <br />
							Account #: <?php echo $temp_acc; ?><br />
							Status: Inactive <br />
							Meters: <br />
							Rate: <br />
						<?php if((isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)) or $acc_close_btn=="ON"){ ?>
							<button class="btn-primary" align="right" onclick="reqcloseacc('<?php echo $aid; ?>','<?php echo $sno; ?>')" style="height: 30px !important;width: auto !important;">Request Account Close</button>
						<?php } ?>
						</div>
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-9 col-sm-9 col-md-9 col-lg-9" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Usage Details </h2>
							</header>
							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body no-padding">

									<table id="datatable_fixed_column_acc<?php echo $ts1.$d_i; ?>" class="table table-striped table-bordered table-hover usagedatatable" width="100%">
										<thead>
											<!--<tr id="multiselect">
												<th class="hasinput">
												</th>
												<th class="hasinput">
													<select id="selectYear" name="selectYear[]" multiple="multiple"></select>
												</th>
												<th class="hasinput">
													<select id="selectMonth" name="selectMonth[]" multiple="multiple"></select>
												</th>
												<th class="hasinput">
													<select id="selectValue" name="selectValue[]" multiple="multiple"></select>
												</th>
											</tr>
											<tr>
												<th class="hasinput">
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Year" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Month" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Value" />
												</th>
											</tr>-->
											<tr>
												<th>Meter Number(s)</th>
												<th>Status</th>
												<th>Year</th>
												<th>Month</th>
												<th>Cost</th>
												<th>Usage</th>
												<th>Unit of Measure</th>
												<th>Invoice</th>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
												<th>Action</th>
			<?php } ?>
											</tr>
										</thead>
										<tbody>
			<?php
			krsort($_vl);
				foreach($_vl as $_kys=>$_vls)
				{
					foreach($_vls as $_kyy=>$_vll)
					{
						$_dis_meter_id=$_dis_units=$_dis_interval=$_dis_cost=$_dis_invoice=$_dis_edit=array();
						$_dis_measure="";
						foreach($_vll as $_kk=>$_vv)
						{
							$_dis_meter_id[]=$_vv["meter_id"];
							$_dis_units[]=$_vv["units"];
							$_dis_interval[]="<a href='javascript:void(0);' onclick='load__invoice(".$_vv['_u_id'].")'>".$_vv["sinterval"]." - ".$_vv["einterval"]."</a>";
							$_dis_cost[]=$_vv["cost"];
							$_dis_measure=$_vv["measure"];
							$_dis_invoice[]="<a href='javascript:void(0);' onclick='load__invoice(".$_vv['_u_id'].")'>Link</a>";
							if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
								$_dis_edit[]="&nbsp;&nbsp;<button onclick='loadeusage(".$_vv['_u_id'].")' title='View/Edit Usage Details' class='btn btn-xs btn-default'><i class='fa fa-pencil'></i></button><button onclick='deleteusage(".$_vv['_u_id'].")' title='Delete Usage' class='btn btn-xs btn-default'><i class='fa fa-times'></i></button>&nbsp;&nbsp;";
							}
						}
				?>
									<tr>
										<td><?php echo @implode(",",@array_unique($_dis_meter_id)); ?></td>
										<td><?php echo "Active"; ?></td>
										<td><?php echo $_kys; ?></td>
										<td><?php echo $_kyy; ?></td>
										<td><?php echo @round(@array_sum($_dis_cost),2); ?></td>
										<td><?php echo @round(@array_sum($_dis_units),2); ?></td>
										<td><?php echo $_dis_measure; ?></td>
										<td><?php echo @implode(", ",$_dis_interval); ?></td>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
										<td><?php echo @implode("<br />",$_dis_edit); ?></td>
			<?php } ?>
									</tr>
				<?php
					}
				}
			?>
										</tbody>
									</table>

								</div>
								<!-- end widget content -->

							</div>
							<!-- end widget div -->

						</div>
						<!-- end widget -->

					</article>
				</div>
			<?php
			}
			?>


			</div>

			<!-- end row -->
			<!-- end widget grid -->
<?php
		}
?>
			<div id="dialog_invoice" title="Invoice"></div>
			<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
			<script type="text/javascript">
				function reqcloseacc(aid,sno){;
					//$('#datatable_fixed_column').DataTable().ajax.reload();
					//otable.ajax.reload();
					$("#dialog-message").remove();
					parent.$('#response').html('');
					parent.$('#response').load('assets/ajax/req_servicetesting.php?action=close&aid='+aid+'&sno='+sno);
				};
				pageSetUp();
				var pagefunction = function() {
					/* BASIC ;*/

						var breakpointDefinition = {
							tablet : 1024,
							phone : 480
						};
					//$(document).load(function(){
						$(".usagedatatable").DataTable( {
							"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
							"pageLength": 12,
							"retrieve": true,
							"scrollCollapse": true,
							"searching": true,
							"paging": true,
							"dom": 'Blfrtip',
							"buttons": [
								'copyHtml5',
								'excelHtml5',
								'csvHtml5',
								{
									'extend': 'pdfHtml5',
									'title' : 'Vervantis_PDF',
									'messageTop': 'Vervantis PDF Export'
								},
								//'pdfHtml5'
								{
									'extend': 'print',
									//'title' : 'Vervantis',
									'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
								},
								{
									'text': 'Columns',
									'extend': 'colvis'
								}
							],
							"autoWidth" : true
						} );
					//} );
				};
				// load related plugins
				loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

			function load__invoice(id) {
				$('#dialog_invoice').html('<img src="<?php echo ASSETS_URL; ?>/assets/img/invoice/usage/invoice_'+id+'.jpg" width="100%" height="100%" />');
				$('#dialog_invoice').dialog({
					autoOpen : false,
					width : 800,
					resizable : false,
					modal : true,
					title : "Invoice",
					buttons : [{
						html : "<i class='fa fa-times'></i>&nbsp; Close",
						"class" : "btn btn-default",
						click : function() {
							$(this).dialog("close");
						}
					}]
				});
				$('#dialog_invoice').dialog('open');
			}
			$(document).ready(function(){
				//$(window).scrollTop($('.usage').offset().top);
				/*$('html, body').animate({
					scrollTop: $('.usage').offset().top
				}, 2000);*/
			});
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
			function loadeusage(uid)
			{
				parent.$("#response").html('');
				parent.$("#response").load('assets/ajax/list-usagetesting.php?edituid=true&uid='+uid);
			}
			function deleteusage(uid)
			{
				alert("Under Maintainance");
			}
			<?php } ?>
			</script>
<?php
	}

////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
}else if(isset($_GET['pc']) and  $_GET['pc'] != "" and isset($_GET['naids']) and isset($_GET['sno']) and  $_GET['sno'] != ""){
	if(!isset($c_naid_arr)) $c_naid_arr=array();
	$pno=$_GET['pc'];
	$naid_arr=explode(",",$_GET['naids']);
	if(isset($_GET['params']) and !empty($_GET['params']))$params_arr=explode(",",$_GET['params']);
	else $params_arr=array();
	$naid_arr = array_filter($naid_arr);
	$naid_arr=array_unique($naid_arr);
	//if(count($naid_arr))$_GET['naids']=implode(",",$naid_arr);
	//else $_GET['naids']="";
	if(!count($naid_arr)) die("<p style='text-align:center;width:100%;background-color:#fff;margin-top:20px;'>No Accounts to Show</p>");;
	$sno=$mysqli->real_escape_string($_GET['sno']);
	if(isset($_GET["totpgcnt"])) $totpgcnt=$mysqli->real_escape_string($_GET['totpgcnt']);
	else $totpgcnt=0;

	
	$servicesql=$vendorsql=$statussql=$accsql="";
	if(count($params_arr)==4){
		if(!empty($params_arr[0])) $servicesql=" AND b.ServiceTypeID = '".$mysqli->real_escape_string($params_arr[0])."' ";
		if(!empty($params_arr[1])) $vendorsql=" AND a.VendorID = '".$mysqli->real_escape_string($params_arr[1])."' ";
		if($params_arr[2]=="Inactive") $statussql=" AND a.AccountStatus = '0' ";
		else if($params_arr[2]=="") $statussql=" ";
		else $statussql=" AND a.AccountStatus != '0' ";
		if(!empty($params_arr[3])) $accsql=" AND a.AccountNumber = '".$mysqli->real_escape_string($params_arr[3])."' ";
		
		//$vendorsql=$statussql=$accsql="";
	}
	
	$cnnaid=0;
	if($pno==1){		
		if ($stmtpgg = $mysqli->prepare("SELECT a.AccountID,b.ServiceTypeID,a.VendorID, a.AccountStatus,a.AccountNumber,
	a.VendorID AS vendor_id,
	e.ServiceTypeName AS service_group,
	b.ServiceTypeID AS service_group_id,
	d.VendorName AS vendor_name,
	d.VendorAddress1 AS vendorAddr1,
	CONCAT(
	'(',
	LEFT ( d.VendorPhone, 3 ),
	') ',
	MID( d.VendorPhone, 4, 3 ),
	'-',
	RIGHT ( d.VendorPhone, 4 )) AS vPhoneNbr1,	
	d.VendorEmail AS vendorEmail1,	
	CONCAT(
	'(',
	LEFT ( d.VendorFax, 3 ),
	') ',
	MID( d.VendorFax, 4, 3 ),
	'-',
	RIGHT ( d.VendorFax, 4 )) AS vFaxNbr1,
	
	a.AccountNumber AS account_number1,
	IF (a.AccountInactiveDate IS NULL AND (a.AccountStatus IS NULL OR a.AccountStatus = 1), 'Active','Inactive') AS `STATUS`,
	IFNULL(GROUP_CONCAT(DISTINCT IF(c.MeterNumber='Undefined', NULL, c.MeterNumber)), 'Undefined') AS meter_number,
	IFNULL(GROUP_CONCAT(DISTINCT tblRates.RateName), 'Undefined') AS rate_name

	FROM
		ubm_database.tblAccounts AS a
		LEFT JOIN
		ubm_database.tblServices AS b
		ON
		a.AccountID = b.AccountID
		LEFT JOIN
		ubm_database.tblMeters AS c
		ON
		b.ServiceID = c.ServiceID
		LEFT JOIN
		ubm_database.tblServiceTypes AS e
		ON
		b.ServiceTypeID = e.ServiceTypeID
		LEFT JOIN
		ubm_database.tblRates
		ON
		c.RateID = tblRates.RateID
		LEFT JOIN
		ubm_database.tblVendors AS d
		ON
		a.VendorID = d.VendorID
		LEFT JOIN
		ubm_database.tblSiteAllocations AS f
		ON
				a.ClientID = f.ClientID
				AND	a.EntityID = f.EntityID
				AND a.AccountID = f.AccountID
				AND b.ServiceTypeID = f.ServiceTypeID
		LEFT JOIN
		ubm_database.tblSites g
		ON
		f.SiteID =g.SiteID		
			
	WHERE
		a.AccountID IN (".implode(",",$naid_arr).")
		".(($_SESSION['group_id'] != 1 and $_SESSION['group_id'] != 2 )?" AND a.ClientID='".$cmpid."' ":((isset($cid) and !empty($cid))? " AND a.ClientID='".$cid."' ":""))."
		AND g.SiteID = '".$sno."'".$servicesql.$vendorsql.$statussql.$accsql."
	AND a.DeleteStatus=0
	AND b.DeleteStatus=0
	AND c.DeleteStatus=0
	GROUP BY
		a.VendorID,
		a.AccountID,
		b.ServiceID")){
		
			$stmtpgg->execute();
			$stmtpgg->store_result();
			if ($stmtpgg->num_rows > 0) {
				$stmtpgg->bind_result($c_accountid,$_service_group,$c_vendorid, $c_accountstatus,$c_accountnumber,$_vid,$_service_group,$_service_group_id,$_vendor_name,$_vendorAddr1,$_vendorPhoneNbr1,$_vendorEmail1,$_vendorFaxNbr1,$_account_number1,$_status,$_meter_number,$_rate_name);
				//$stmt->bind_result($_mtid,$_v_vid,$_v_name,$_a1,$_a2,$_a3);
				//$stmtpgg->fetch();
				//$cnnaid=$_rowspgg;
				$cnnaid=$stmtpgg->num_rows;
				$c_naid_arr=array();
				while($stmtpgg->fetch()){
					$c_naid_arr[]=array("cacc"=>$c_accountid,"csg"=>$_service_group,"cvid"=>$c_vendorid, "caccst"=>$c_accountstatus,"caccsno"=>$c_accountnumber,"s_vid"=>$_vid,"s_service_group"=>$_service_group,"s_service_group_id"=>$_service_group_id,"s_vendor_name"=>$_vendor_name,"s_vendorAddr1"=>$_vendorAddr1,"s_vendorPhoneNbr1"=>$_vendorPhoneNbr1,"s_vendorEmail1"=>$_vendorEmail1,"s_vendorFaxNbr1"=>$_vendorFaxNbr1,"s_account_number1"=>$_account_number1,"s_status"=>$_status,"s_meter_number"=>$_meter_number,"s_rate_name"=>$_rate_name);
				}
				//$naid_arr = array_filter($naid_arr);
				//$naid_arr=array_unique($naid_arr);				
			}		
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
		//$cnnaid=count($naid_arr);
		$totpgcnt= ceil(($cnnaid/20));
	}
	

	if($pno <= $totpgcnt){
?>
<style>
.rnext{
  width: 150px;
  height: 80px;
  background-color: yellow;
  -ms-transform: rotate(180deg); /* IE 9 */
  -webkit-transform: rotate(180deg); /* Safari 3-8 */
  transform: rotate(180deg);
}
.prevbut{float:left;font-weight:bold;cursor: pointer;}
.nextbut{float:right;font-weight:bold;cursor: pointer;}
</style>
<script type="text/javascript">
	function reqcloseacc(aid,sno){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$("#dialog-message").remove();
		parent.$('#response').html('');
		parent.$('#response').load('assets/ajax/req_servicetesting.php?action=close&aid='+aid+'&sno='+sno);
	};
	function paginateacc(pc){
		parent.parent.$('#sitesaccountcont').load('assets/ajax/detailstesting.php?cid=<?php echo $cid; ?>&pc='+pc+'&naids=<?php   echo $_GET['naids']; ?>&sno=<?php echo $_GET['sno']; ?>&ct=<?php echo rand(1,10); ?>&totpgcnt=<?php echo $totpgcnt; ?>&params=<?php echo (isset($_GET['params'])?$_GET['params']:",,,,"); ?>');
		//$("#dialog-message").remove();
		//parent.$('#response').html('');
		//parent.$('#response').load('assets/ajax/req_service.php?action=close&aid='+aid+'&sno='+sno);
	};
</script>
<?php //die("<p style='text-align:center;width:100%;background-color:#fff;margin-top:20px;'>No data to show</p>");
		if(count($c_naid_arr)){
			foreach($c_naid_arr as $kkyp=>$vvll){
			$temp_invoice=$__mtid=$temp_acc_sub=array();
			$temp_acc="";
			?>
			<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
			<style>
			.usage{
			  background-color: #fff;
			  margin: 20px 0;
			  padding-top: 29px;
			}
			.dataTables_paginate ul li {padding:0px !important;}
			.dataTables_paginate ul li a{margin:-1px !important;}
			.dt-buttons{
			float: right !important;
			margin: 0.9% auto !important;
			}
			.dataTables_wrapper .dataTables_length{
			float: right !important;
			margin: 1% 1% !important;
			}
			.dataTables_wrapper .dataTables_filter{
			float: left !important;
			width: auto !important;
			margin: 1% 1% !important;
			text-align:left !important;
			}
			.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
			.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
			.no-padding .dataTables_wrapper table, .no-padding>table{border-bottom:1px solid #cccccc !important;}
			</style>
			<?php
			//New Codes
			$d_i=0;
			$ts1=$ts=rand(650,900);
			?>		
			
				<div class="row usage">
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							<h3><?php echo $vvll["s_service_group"]; ?></h3>
							Vendor: <?php echo $vvll["s_vendor_name"]; ?><br />
							Vendor Address: <?php echo $vvll["s_vendorAddr1"]; ?><br />
							Vendor Phone: <?php echo $vvll["s_vendorPhoneNbr1"]; ?><br />
							Vendor Email: <?php echo $vvll["s_vendorEmail1"]; ?><br />
							Vendor Fax: <?php echo $vvll["s_vendorFaxNbr1"]; ?><br />
							Account #: <?php echo $vvll["s_account_number1"]; ?><br />
							Status: <?php echo $vvll["s_status"]; ?><br />
							Meters: <?php echo $vvll["s_meter_number"]; ?><br />
							Rate: <?php echo $vvll["s_rate_name"]; ?><br /><br />
						<?php if((isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)) or $acc_close_btn=="ON"){ ?>
							<button class="btn-primary" align="right" onclick="reqcloseacc('<?php echo $vvll["cacc"]; ?>','<?php echo $sno; ?>')" style="height: 30px !important;width: auto !important;">Request Account Close</button>
						<?php } ?>
							<br />
							<br />
						</div>
						<div class="jarviswidget jarviswidget-color-blueDark col-xs-9 col-sm-9 col-md-9 col-lg-9" data-widget-editbutton="false">
							<header>
								<span class="widget-icon"> <i class="fa fa-table"></i> </span>
								<h2>Usage Details </h2>
							</header>
							<!-- widget div-->
							<div>

								<!-- widget edit box -->
								<div class="jarviswidget-editbox">
									<!-- This area used as dropdown edit box -->

								</div>
								<!-- end widget edit box -->

								<!-- widget content -->
								<div class="widget-body no-padding">

									<table id="datatable_fixed_column_acc<?php echo $ts1.$d_i; ?>" class="table table-striped table-bordered table-hover usagedatatable" width="100%">
										<thead>
											<tr>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Period" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Meter Number(s)" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Year" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Month" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Unit of Measure" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Usage" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Cost" />
												</th>
												<th class="hasinput">
													<input type="text" class="form-control" placeholder="Filter Invoices" />
												</th>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id']) and 1==2){?>
												<th class="hasinput">
												</th>
			<?php } ?>
											</tr>
											<tr>
												<th>Period</th>
												<th>Meter Number(s)</th>
												<th>Year</th>
												<th>Month</th>
												<th>Unit of Measure</th>
												<th>Usage</th>
												<th>Cost</th>
												<th>Invoices</th>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id']) and 1==2){?>
												<th>Action</th>
			<?php } ?>
											</tr>
										</thead>
										<tbody>
			<?php //print_r($vvll);
$vvaid=$vvll["cacc"];			
$vv_vid=$vvll["cvid"];			
$vv_service_group=$vvll["s_service_group_id"];			
//$vv_accountstatus=$vvll["caccst"];	
if($vvll["caccst"]=="0") $vv_accountstatus=" AND e.AccountStatus = '0' ";
else if($vvll["caccst"]=="") $vv_accountstatus=" ";
else $vv_accountstatus=" AND e.AccountStatus != '0' ";		
		
if ($stmtid = $mysqli->prepare("SELECT
	a.MeterID AS `Meter ID`,
	c.MeterNumber AS `Meter Number`,
	LEFT ( a.CalPeriod, 4 ) AS `Year`,
	RIGHT ( a.CalPeriod, 2 ) AS `Month`,
	ROUND(SUM(a.CalUsageReport)*d.UnitConversion,0) AS `Usage`, 
	d.UnitConverted AS `Unit of Measure`,
	ROUND(SUM(a.CalBaseCost + a.CalUsageCost),2) AS cost,
	GROUP_CONCAT( DISTINCT a.InvoiceID ) AS Invoices,
	a.CalPeriod,
	f.SiteID,
	f.Allocation
FROM
	ubm_database.tblCostUsage a 
LEFT JOIN
	ubm_database.tblMeters AS c
ON 
	a.MeterID=c.MeterID	
LEFT JOIN
	ubm_database.tblUnits AS d
ON
	a.UnitID = d.UnitID	
LEFT JOIN
	ubm_database.tblAccounts AS e
ON
	a.AccountID = e.AccountID
LEFT JOIN
	ubm_database.tblSiteAllocations AS f
ON
	a.ClientID = f.ClientID 
	AND a.AccountID = f.AccountID 
	AND a.ServiceID = f.ServiceID
	AND a.MeterID = f.MeterID
WHERE
	".(($_SESSION['group_id'] != 1 and $_SESSION['group_id'] != 2 )?"a.ClientID='".$cmpid."' and ":((isset($cid) and !empty($cid))? " a.ClientID='".$cid."' and ":""))."
	e.VendorID = '".$vv_vid."'
	AND a.AccountID = '".$vvaid."'
	AND f.SiteID = '".$sno."'
	AND a.ServiceTypeID = '".$vv_service_group."'
	".$vv_accountstatus."
GROUP BY
	a.MeterID,
	a.`Year`,
	a.`Month` 
ORDER BY
	a.`Year` DESC,
	a.`Month` ASC")) {

					$stmtid->execute();
					$stmtid->store_result();
					if ($stmtid->num_rows > 0) {
						$stmtid->bind_result($u_mid,$u_mno,$u_year,$u_month,$u_usage,$u_uom,$u_cost,$u_invoices,$u_period,$u_sid,$u_alloc);
						//$stmtid->bind_result($u_mid,$u_mno,$u_year,$u_month,$u_uom,$u_usage,$u_cost,$u_invoices,$u_period);
						while($stmtid->fetch()) {
							$uinv=array();
							foreach(explode(",",$u_invoices) as $kk=>$vv)
								$uinv[]='<a href="javascript:void(0);" onclick="load_invoice('.$vv.')">'.$vv.'</a>';
			?>
									<tr id="idis">
										<td><?php echo $u_period; ?></td>
										<td><?php echo $u_mno; ?></td>
										<td><?php echo $u_year; ?></td>
										<td><?php echo $u_month; ?></td>
										<td><?php echo $u_uom; ?></td>
										<td><?php echo $u_usage; ?></td>
										<td>$<?php echo $u_cost; ?></td>
										<td><?php echo implode(",",$uinv); ?></td>
			<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id']) and 1==2){?>
										<td>EDIT</td>
			<?php } ?>
									</tr>
			<?php
				}
					}
			}else{
				?><script type='text/javascript'>window.top.location='https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/includes/logout.php?error=System error'; </script><?php
				//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			$vvaid=$vv_vid=$vv_service_group=null;
			?>
										</tbody>
									</table>

								</div>
								<!-- end widget content -->

							</div>
							<!-- end widget div -->

						</div>
						<!-- end widget -->

					</article>
				</div>


			</div>

			<!-- end row -->
			<!-- end widget grid -->
<script src="/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 *
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 *
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction
	var pagefunction = function() {
		//console.log("cleared");

		/* // DOM Position key index //

			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class

			Also see: http://legacy.datatables.net/usage/features
		*/

		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otable<?php echo $ts1.$d_i; ?> = $("#datatable_fixed_column_acc<?php echo $ts1.$d_i; ?>").DataTable( {
				"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
				"pageLength": 12,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				"dom": 'Blfrtip',
				"buttons": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					},
					{
						'text': 'Columns',
						'extend': 'colvis',
						'columns': ':gt(0)'
					}
				],
				"order": [[ 2, "desc" ]],
				"columnDefs": [
					{ 'visible': false, 'targets': [<?php if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){?>0<?php } ?>] },
				 {
							'targets': [ 2 ],
							'orderData': [ 2, 3 ]
						}, {
							'targets': [ 3 ],
							'orderData': [ 3, 2 ]
						}],
				"autoWidth" : true
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column_acc<?php echo $ts1.$d_i; ?> .sssdrp").on( 'keyup change', function () {
	        otable<?php echo $ts1.$d_i; ?>
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $("#datatable_fixed_column_acc<?php echo $ts1.$d_i; ?> thead th input[type=text]").on( 'keyup change', function () {;

	        otable<?php echo $ts1.$d_i; ?>
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });
	};

	function multifilter(nthis,fieldname,otable)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otable<?php echo $ts1.$d_i; ?>
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#datatable_fixed_column_acc<?php echo $ts1.$d_i; ?> tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}


	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>

<?php
			}
		}else die("<p style='text-align:center;width:100%;background-color:#fff;margin-top:20px;'>No data to show</p>");
?>
<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper no-footer" style="display:none;">
	<div class="dataTables_info" id="datatable_fixed_column_info" role="status" aria-live="polite"><?php if(1==2){  ?>)Showing <?php echo ((($pno-1)*4)+1); ?> to <?php if(($pno*4) > $cnnaid) echo $cnnaid; ?> of <?php echo $cnnaid; ?> entries
	<?php } ?></div>
	<div class="dataTables_paginate paging_simple_numbers" id="datatable_fixed_column_paginate">
		<a class="paginate_button previous <?php if($pno==1) echo "disabled"; ?>" aria-controls="datatable_fixed_column" data-dt-idx="0" tabindex="0" id="datatable_fixed_column_previous" onclick="paginateacc(<?php echo ($pno-1); ?>)">Previous</a>
		<span>
		<?php
			for($z=0;$z<$totpgcnt;$z++){
		?>
			<a class="paginate_button <?php if($pno==($z+1)) echo "current"; ?>" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo ($z+1); ?>" tabindex="0" onclick="paginateacc(<?php echo ($z+1); ?>)"><?php echo ($z+1); ?></a>
		<?php
			}
		?>
		</span>
		<a class="paginate_button next <?php if($pno==$totpgcnt) echo "disabled"; ?>" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo ($z+2); ?>" tabindex="0" id="datatable_fixed_column_next" onclick="paginateacc(<?php echo ($pno+1); ?>)">Next</a></div>
</div>
<script type="text/javascript">
	function load_invoice(id) {
		parent.$("#sitesdetails").fadeOut( "slow" );
		parent.$('#invoicedetails').load('assets/ajax/invoicedetailstesting.php?id='+id);
	}
</script>




<?php
	}
}else
	die("<p style='text-align:center;width:100%;background-color:#fff;margin-top:20px;'>No Accounts to Show</p>");
