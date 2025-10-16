<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(isset($_GET['id']) and $_GET['id'] != "" and $_GET['id'] > 0)
	$id=$_GET['id'];
else
	die('Wrong parameters provided4');
	
	if ($stmt = $mysqli->prepare('SELECT id,site_name,service_address1,service_address2,service_address3,city,state,postal_code,country FROM sites where site_number='.$id." LIMIT 1")) { 
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($sid,$site_name,$service_address1,$service_address2,$service_address3,$city,$state,$postal_code,$country);
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
		}else
			die('Wrong parameters provided3');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
	$temp_sites=array();
	$temp_acc="";
	if ($stmt = $mysqli->prepare('SELECT a.id,s.site_name,v.vendor_name,a.site_number,a.vendor_id,a.account_number1,a.account_number2,a.account_number3,a.meter_number FROM `accounts` a, vendor v, sites s where a.site_number=s.site_number and a.vendor_id=v.vendor_id and a.site_number='.$id.' and a.meter_number != "" and a.meter_number != 0 group by a.id')) { 

//('SELECT a.id,s.site_name,v.vendor_name,a.sites_id,a.vendor_id,a.account_number1,a.account_number2,a.account_number3,a.meter_id FROM `accounts` a, vendor v, sites s where a.sites_id=s.id and a.vendor_id=v.id and a.sites_id='.$id.' and a.meter_id != "" and a.meter_id != 0 group by a.id')) { 

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($a_id,$a_site_name,$a_vendor_name,$a_sites_id,$a_vendor_id,$a_account_number1,$a_account_number2,$a_account_number3,$a_meter_id);
			while($stmt->fetch()){
				$temp_sites[$a_id][$a_vendor_name][]=array("a_s_id"=>$a_sites_id,"a_v_id"=>$a_vendor_id,"a_s_name"=>$a_site_name,"a_v_name"=>$a_vendor_name,"a_acc1"=>$a_account_number1,"a_acc2"=>$a_account_number2,"a_acc3"=>$a_account_number3,"a_id"=>$a_id,"a_meter_id"=>$a_meter_id);
			}
		}else
			die('No accounts present for this site!');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}

	if(count($temp_sites)){
		foreach($temp_sites as $ky=>$vl)
		{
			$temp_acc .= "Vendor: ".key($vl);
			foreach($vl as $kys=>$vls)
			{
				//$temp_acc .= "Vendor: ".$kys;
				foreach($vls as $kyy=>$vll)
				{
					$temp_acc_sub=array();
					if($vls[$kyy]["a_acc1"] != "")
						$temp_acc_sub[] = $vls[$kyy]["a_acc1"];
					if($vls[$kyy]["a_acc2"] != "")
						$temp_acc_sub[] = $vls[$kyy]["a_acc2"];
					if($vls[$kyy]["a_acc3"] != "")
						$temp_acc_sub[] = $vls[$kyy]["a_acc3"];
					
					if(count($temp_acc_sub) and $vls[$kyy]['a_id'] != 0 and $vls[$kyy]['a_id'] != "")
						$temp_acc .= "<br />Account Number: <a href='".ASSETS_URL."/index.php#assets/ajax/accounts.php?mtid=".$vls[$kyy]['a_meter_id']."' target='_blank'>".implode("-",$temp_acc_sub)."</a>";
					else
						$temp_acc .= "<br />Account Number: N/A";
				}
				$temp_acc .= "<br />";
			}		
		}
	
	}
//echo $temp_acc;exit();
	
function Get_LatLng_From_Google_Maps($address) {
    $address = urlencode($address);
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false";
    $data = @file_get_contents($url);
    $jsondata = json_decode($data,true);
    if (!check_status($jsondata))   return array();

    $LatLng = array(
        'lat' => $jsondata["results"][0]["geometry"]["location"]["lat"],
        'lng' => $jsondata["results"][0]["geometry"]["location"]["lng"],
    );
    return $LatLng;
}

function check_status($jsondata) {
    if ($jsondata["status"] == "OK") return true;
    return false;
}
$loc_arr=array();
$loc_arr=Get_LatLng_From_Google_Maps($address);
if(count($loc_arr) == 2)
{
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&amp;sensor=false"></script>
<script type="text/javascript" src="../js/infobox.js"></script>
<script type="text/javascript">
	function initialize() {
		var secheltLoc = new google.maps.LatLng(<?php echo $loc_arr["lat"]; ?>, <?php echo $loc_arr["lng"]; ?>);

		var myMapOptions = {
			 zoom: 15
			,center: secheltLoc
			,mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var theMap = new google.maps.Map(document.getElementById("map_canvas"), myMapOptions);


		var marker = new google.maps.Marker({
			map: theMap,
			draggable: true,
			position: new google.maps.LatLng(<?php echo $loc_arr["lat"]; ?>, <?php echo $loc_arr["lng"]; ?>),
			visible: true
		});

		var boxText = document.createElement("div");
		boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: yellow; padding: 5px;";
		boxText.innerHTML = "<?php echo $temp_acc; ?>";

		var myOptions = {
			 content: boxText
			,disableAutoPan: false
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-140, 0)
			,zIndex: null
			,boxStyle: { 
			  background: "url('../img/tipbox.gif') no-repeat"
			  ,opacity: 0.75
			  ,width: "280px"
			 }
			,closeBoxMargin: "10px 2px 2px 2px"
			,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};

		google.maps.event.addListener(marker, "click", function (e) {
			ib.open(theMap, this);
		});

		var ib = new InfoBox(myOptions);

		ib.open(theMap, marker);
	}
</script>
<?php } ?>
</head>
<body <?php if(count($loc_arr) == 2){?>onload="initialize()"<?php }?>>
	<div id="map_canvas" style="height: 354px; width:100%;"></div>
	<p></p>
</body>

</html>