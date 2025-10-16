<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL
);

/*$temp_ress=mysqli_query($conn,"SELECT u.interface FROM `members` m, usergroups u where m.id=2 and m.usergroups=u.id") or die("no query");
if(mysqli_num_rows($temp_ress) != 0)
{

}*/
	$user_one=$_SESSION["user_id"];
	$_pdisablemenu=array();

if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2 || $_SESSION["group_id"] == 4){
	//$sql='SELECT id,disabled_menu_by_clientadmin,disabled_menu_by_admin,disabled_by FROM permission where user_id= "'.$user_one.'" LIMIT 1';
}else if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){

    $tmpsql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory`';

    $csql=$tmpsql.' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';

    $usql=$tmpsql.' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';




	//$sql='SELECT p.id,p.disabled_menu_by_clientadmin,p.disabled_menu_by_admin,p.disabled_by FROM permission p, user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.user_id="'.$user_one.'" and p.company_id= u.company_id LIMIT 1';

//SELECT p.id,p.disabled_menu_by_clientadmin,p.disabled_menu_by_admin,p.disabled_by FROM permission p, user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.id="'.$user_one.'" and p.company_id= u.company_id LIMIT 1';


    $colomnsarr=array(0=>array('`Futures Pricing`','`Locational Marginal Pricing`','`Live Weather`','`Streaming News`','`Verve Energy Blog`','`Market Commentary`','`Weekly Reports`','`Direct Access Information`','`Strategy`','`Dynamic Risk Management`','`Master Supply Agreements`','`Supplier Contracts`','`Utility Requirements`','`Regulated Information`','`Utility Rate Reports`','`Utility Rate Change Requests`','`Start New Service`','`Stop Service`','`StartStop Status`','`Correspondence`','`UBM Archive`','`UBM Software`','`Utility Budgets`','`Invoice Validation`','`Exception Reports`','`Resolved Exceptions`','`Site and Account Changes`','`Data Analysis`','`Consumption Reports`','`Custom Reports`','`Benchmark Report`','`CSR ESG Software`','`Sustainability Reports`','`Corporate Reports`','`Surveys`','`Distributed Generation`','`Efficiency Upgrades`','`EV Charging`','`Rebates and Incentives`','`Other`','`Site List`','`Vendors`','`Accounts`','`Invoices`','`Users Edit`','`User Permissions`','`Company Defaults`','`Chat`','`Account Report`','`Vendor Report`','`Processed Invoice Report`','`Accrual Report`','`Invoice Audit`','`Monthly Weather`','`Deposit Late Fee Report`','`Cost and Usage Report`','`GHG Report`','`Site Inventory`'),
        1=>array(48,59,52,60,2,3,102,31,42,46,54,55,56,30,41,61,49,50,51,58,114,35,37,36,62,63,64,33,65,34,78,38,39,74,75,40,66,67,97,69,9,92,93,80,12,76,57,105,117,119,120,122,126,127,128,129,130,131));

    $futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;

    $u_futurespricing=$u_locationalmarginalpricing=$u_liveweather=$u_streamingnews=$u_verveenergyblog=$u_marketcommentary=$u_weeklyreports=$u_directaccessinformation=$u_strategy=$u_dynamicriskmanagement=$u_mastersupplyagreements=$u_suppliercontracts=$u_utilityrequirements=$u_regulatedinformation=$u_utilityratereports=$u_utilityratechangerequests=$u_startnewservice=$u_stopservice=$u_startstopstatus=$u_correspondence=$u_ubmarchive=$u_ubmsoftware=$u_utilitybudgets=$u_invoicevalidation=$u_exceptionreports=$u_resolvedexceptions=$u_siteandaccountchanges=$u_dataanalysis=$u_consumptionreports=$u_customreports=$u_benchmarkreport=$u_csresgsoftware=$u_sustainabilityreports=$u_corporatereports=$u_surveys=$u_distributedgeneration=$u_efficiencyupgrades=$u_evcharging=$u_rebatesandincentives=$u_other=$u_sitelist=$u_vendors=$u_accounts=$u_invoices=$u_usersedit=$u_userpermissions=$u_companydefaults=$u_chat=$u_accountreport=$u_vendorreport=$u_processedinvoicereport=$u_accrualreport=$u_invoiceaudit=$u_monthlyweather=$u_depositlatefeereport=$u_costandusagereport=$u_ghgreport=$u_siteinventory=0;

		if ($cstmt = $mysqli->prepare($csql)) {
			$cstmt->execute();
			$cstmt->store_result();
			if ($cstmt->num_rows > 0) {
			    $cstmt->bind_result($futurespricing,$locationalmarginalpricing,$liveweather,$streamingnews,$verveenergyblog,$marketcommentary,$weeklyreports,$directaccessinformation,$strategy,$dynamicriskmanagement,$mastersupplyagreements,$suppliercontracts,$utilityrequirements,$regulatedinformation,$utilityratereports,$utilityratechangerequests,$startnewservice,$stopservice,$startstopstatus,$correspondence,$ubmarchive,$ubmsoftware,$utilitybudgets,$invoicevalidation,$exceptionreports,$resolvedexceptions,$siteandaccountchanges,$dataanalysis,$consumptionreports,$customreports,$benchmarkreport,$csresgsoftware,$sustainabilityreports,$corporatereports,$surveys,$distributedgeneration,$efficiencyupgrades,$evcharging,$rebatesandincentives,$other,$sitelist,$vendors,$accounts,$invoices,$usersedit,$userpermissions,$companydefaults,$chat,$accountreport,$vendorreport,$processedinvoicereport,$accrualreport,$invoiceaudit,$monthlyweather,$depositlatefeereport,$costandusagereport,$ghgreport,$siteinventory);

				$cstmt->fetch();
			}
		}else{
			@error_log("configuiphp csql error", 0);
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php');
			exit();
		}


		if ($ustmt = $mysqli->prepare($usql)) {
			$ustmt->execute();
			$ustmt->store_result();
			if ($ustmt->num_rows > 0) {
			    $ustmt->bind_result($u_futurespricing,$u_locationalmarginalpricing,$u_liveweather,$u_streamingnews,$u_verveenergyblog,$u_marketcommentary,$u_weeklyreports,$u_directaccessinformation,$u_strategy,$u_dynamicriskmanagement,$u_mastersupplyagreements,$u_suppliercontracts,$u_utilityrequirements,$u_regulatedinformation,$u_utilityratereports,$u_utilityratechangerequests,$u_startnewservice,$u_stopservice,$u_startstopstatus,$u_correspondence,$u_ubmarchivesoftware,$u_ubmsoftware,$u_utilitybudgets,$u_invoicevalidation,$u_exceptionreports,$u_resolvedexceptions,$u_siteandaccountchanges,$u_dataanalysis,$u_consumptionreports,$u_customreports,$u_benchmarkreport,$u_csresgsoftware,$u_sustainabilityreports,$u_corporatereports,$u_surveys,$u_distributedgeneration,$u_efficiencyupgrades,$u_evcharging,$u_rebatesandincentives,$u_other,$u_sitelist,$u_vendors,$u_accounts,$u_invoices,$u_usersedit,$u_userpermissions,$u_companydefaults,$u_chat,$u_accountreport,$u_vendorreport,$u_processedinvoicereport,$u_accrualreport,$u_invoiceaudit,$u_monthlyweather,$u_depositlatefeereport,$u_costandusagereport,$u_ghgreport,$u_siteinventory);

				$ustmt->fetch();
			}
		}else{
			@error_log("configuiphp usql error", 0);
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php');
			exit();
		}

//$abc=array();
		foreach($colomnsarr[0] as $ky=>$vl){
			$clname=strtolower(str_replace(array(" ","`"),"",$vl));
			//$adnname=$clname."arr";
			$unname=@trim('u_'.$clname);
			$ufname=$$unname;
			$adname=$$clname;//$$adnname;
			//if(!is_array($adnamearr) or count($adnamearr) !=2) die("Error Occured. Please try after sometime!");
			if(!isset($adname) or !isset($ufname)) die("Error Occured. Please try after sometime!");

			if ($adname==1 and $ufname==1){}else {$_pdisablemenu[]=$colomnsarr[1][$ky];}

			//$abc[]=array($adnamearr[0],$adnamearr[1],$ufname,$vl,$test);
		}

//print_r($abc);
//print_r($_pdisablemenu);






}else die("Error Occurred. Please try after sometime!");



//////////////////////////////////////////////////////////////////////////////
	/*if ($stmtp = $mysqli->prepare($sql)) {
		$stmtp->execute();
		$stmtp->store_result();
		if ($stmtp->num_rows > 0) {
			$stmtp->bind_result($_pid,$_pdisabled_menu_c,$_pdisabled_menu_a,$_pdisabled_by);
		   $stmtp->fetch();

		   $_pdisablemenu = @array_merge(@explode(",",$_pdisabled_menu_c), @explode(",",$_pdisabled_menu_a));
		}
	}*/

	$tmp=$tmp_schat=array();
	$interface="";
	if ($stmt = $mysqli->prepare("SELECT custom_interface FROM users_interface where user_id = ? LIMIT 1")) {
        $stmt->bind_param('i', $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
                $stmt->bind_result($interface);
                $stmt->fetch();
		}
	}else{
		@error_log("configuiphp custom interface sql error", 0);
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php');
		exit();
	}

	if(@trim($interface)=="")
	{
	   if ($stmt = $mysqli->prepare("SELECT ug.interface FROM `user` u, usergroups ug where u.user_id= ? and u.usergroups_id=ug.id")) {

//("SELECT ug.interface FROM `user` u, usergroups ug where u.id= ? and u.usergroups_id=ug.id")) {

			$stmt->bind_param('i', $_SESSION["user_id"]);

			// Execute the prepared query.
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows > 0) {
					$stmt->bind_result($interface);
					$stmt->fetch();
			}
		}else{
			@error_log("configuiphp ug interface error", 0);
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	}

	if($_SESSION["group_id"] == 1){
		$query="SELECT * FROM interface";
		if ($stmts = $mysqli->prepare($query)) {
			$stmts->execute();
			$stmts->store_result();
			if ($stmts->num_rows > 0) {
				$stmts->bind_result($id, $title, $url, $icon);
			   while ($stmts->fetch()) {
			   //Skip Admin Menus from Horizontal Display
					$tmp[$title]= array(
						"title" => $title,
						"url" => $url,
						"icon" => $icon,
						"disable"=> 0);
				}
			}
		}else{
			@error_log("configuiphp interface sql error", 0);
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	}elseif($_SESSION["group_id"] == 2 || $_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 4 || $_SESSION["group_id"] == 5){
		$query="SELECT * FROM interface";
		if ($stmts = $mysqli->prepare($query)) {
			$stmts->execute();
			$stmts->store_result();
			if ($stmts->num_rows > 0) {
				$stmts->bind_result($id, $title, $url, $icon);
			   while ($stmts->fetch()) {
					if(in_array($id,$_pdisablemenu)){
						$tmp[$title]= array(
							"title" => $title,
							"url" => "#",
							"icon" => $icon,
							"disable"=> 1);
					}else{
						$tmp[$title]= array(
							"title" => $title,
							"url" => $url,
							"icon" => $icon,
							"disable"=> 0);
					}
				}
			}
		}else{
			@error_log("configuiphp interface sql error", 0);
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	}

	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2 || $_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 4 || $_SESSION["group_id"] == 5){
		$jsonparsed=json_decode($interface,true);
		if(is_array($jsonparsed) and count($jsonparsed))
			$tmp=nestit($mysqli,$jsonparsed,false,$_pdisablemenu);
	}else{
		@error_log("configuiphp group session error", 0);
		die("Restricted Access!");
	}
//if(in_array (5,$_pdisablemenu)){echo 2;}else echo 3;die();
	function nestit($mysqli,$jsonparsed,$childr=false,$_pdisablemenu=array())
	{
		$lastElement = end($jsonparsed);
		$tmp=array();
		foreach($jsonparsed as $kys => $vls)
		{
			$query="SELECT id,title,url,icon FROM interface where id=".$jsonparsed[$kys]['id'];
			if ($stmts = $mysqli->prepare($query)) {
				$stmts->execute();
				$stmts->store_result();
				if ($stmts->num_rows > 0) {
					$stmts->bind_result($id, $title, $url, $icon);
				   while ($stmts->fetch()) {
						if($id==105) continue;
						$temp_arr=array();
						//if($id == 5){echo $id; print_r($_pdisablemenu);var_dump(in_array ($id,$_pdisablemenu));die("123");}
						$temp_arr["title"]=$title;
						$temp_arr["url"]=$url;
						if(in_array ($id,$_pdisablemenu) == true){
							$temp_arr["disable"]=1;
						}else{
							$temp_arr["disable"]=0;
						}
						if($childr==false)
							$temp_arr["icon"]=$icon;

						if(isset($jsonparsed[$kys]["children"]))
							$temp_arr["sub"] = nestit($mysqli,$jsonparsed[$kys]["children"],true,$_pdisablemenu);

						$tmp[$title]=$temp_arr;
					}
				}
			}else{
				@error_log("configuiphp interface id sql error", 0);
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php');
				exit();
			}
		}
		return $tmp;
	}

	/*if ($stmt = $mysqli->prepare('SELECT user_id,firstname,lastname,designation FROM userprofile where  user_id != '.$_SESSION["user_id"])) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_uid,$_fnm,$_lnm,$_dgn);
			while($stmt->fetch()) {
				$tmp_schat[]='
			  	<a href="#" class="usr"
				  	data-chat-id="'.$_uid.'"
				  	data-chat-fname="'.$_fnm.'"
				  	data-chat-lname="'.$_lnm.'"
				  	data-chat-status="online"
				  	data-chat-alertmsg=""
				  	data-chat-alertshow="false"
				  	<i class="online" title="online"></i>'.$_fnm.' '.$_lnm.'
			  	</a>
				';
			}
		}
	}*/
	if(!in_array(105,$_pdisablemenu)){
        	$tmp["smartchat"]= array(
        		"title" => "Chat <font class='online-users' id='onlinechat'></font>",
        		"icon" => "fa fa-lg fa-fw fa-comment-o",
        		"disable" => 0,
        		/*-----"icon_badge" => array(
        			'content' => '!',
        			'class' => 'bg-color-pink flash animated'
        		),-----*/
        		"sub" => '
        			<div class="display-users">
        				<!--<input class="form-control chat-user-filter" placeholder="Filter" type="text">-->
        				'.implode("",$tmp_schat).'
        				<!--<a href="assets/ajax/chat.php" class="btn btn-xs btn-default btn-block sa-chat-learnmore-btn">About the API</a>-->
        			</div>'
        	);
	}
	$page_nav = $tmp;

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>
