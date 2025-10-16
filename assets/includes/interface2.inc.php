<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 5)
	die("Restricted Access");


$user_one = $_SESSION["user_id"];
$usergroups_id=$_SESSION["group_id"];
$ucid = $_SESSION["company_id"];

if(isset($_POST["userid"]) and isset($_POST["uper"]))
	echo update_user_interface($mysqli,$_POST["userid"],$_POST["uper"]);
else if(isset($_POST["cid"]) and isset($_POST["uper"]) and isset($_POST["aper"]))
	echo update_permission($mysqli,$_POST["cid"],$_POST["uper"],$_POST["aper"]);
else
	echo false;


function update_permission($mysqli,$cid='',$uper='',$aper='')
{
	global $user_one;
	global $usergroups_id;
	global $ucid;

	$cid=(int)@trim($cid);
	if($cid=="" or $cid==0) return false;

	$dmyc=$dmya=$dm=$uperarr=$aperarr=$tmpsql=$tmpusql=array();
	$checkit=0;

	$colomnsarr=array('`Futures Pricing`','`Locational Marginal Pricing`','`Live Weather`','`Streaming News`','`Verve Energy Blog`','`Market Commentary`','`Weekly Reports`','`Direct Access Information`','`Strategy`','`Dynamic Risk Management`','`Master Supply Agreements`','`Supplier Contracts`','`Utility Requirements`','`Regulated Information`','`Utility Rate Reports`','`Utility Rate Change Requests`','`Start New Service`','`Stop Service`','`StartStop Status`','`Correspondence`','`UBM Archive`','`UBM Software`','`Utility Budgets`','`Invoice Validation`','`Exception Reports`','`Resolved Exceptions`','`Site and Account Changes`','`Data Analysis`','`Consumption Reports`','`Custom Reports`','`Benchmark Report`','`CSR ESG Software`','`Sustainability Reports`','`Corporate Reports`','`Surveys`','`Distributed Generation`','`Efficiency Upgrades`','`EV Charging`','`Rebates and Incentives`','`Other`','`Site List`','`Vendors`','`Accounts`','`Invoices`','`Users Edit`','`User Permissions`','`Company Defaults`','`Adhoc Report`','`Chat`','`Account Report`','`Vendor Report`','`Processed Invoice Report`','`Accrual Report`','`Invoice Audit`','`Monthly Weather`','`Deposit Late Fee Report`','`Cost and Usage Report`','`GHG Report`','`Site Inventory`');

	if($_SESSION["group_id"]==1)
	{
		if($uper != "")
			$uperarr=explode(",",$uper);

		if($aper != "")
			$aperarr=explode(",",$aper);

		foreach($colomnsarr as $ky=>$vl){
			$clname=strtolower(str_replace(array(" ","`"),"",$vl));
			//if (!in_array($clname, $uperarr)) $tmpp='1';
			//else $tmpp='0';

			if (!in_array($clname, $aperarr)) $tmpp = 1;
			else $tmpp = 0;

			if ($tmpp != 1 ) $tmpusql[]=$vl.'=0';

			$tmpsql[]=$vl.'="'.$tmpp.'"';
		}

		if ($stmtcheck = $mysqli->prepare('SELECT company_id FROM company_permission where company_id="'.$mysqli->real_escape_string($cid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				if($stmtinsert = $mysqli->prepare('INSERT INTO company_permission SET company_id="'.$mysqli->real_escape_string($cid).'",'.implode(",",$tmpsql))){
					$stmtinsert->execute();
					$checkit=1;
					//return true;
				}else return false;


			}else{
				if($stmtupdate = $mysqli->prepare('UPDATE company_permission SET '.implode(",",$tmpsql).' WHERE company_id="'.$mysqli->real_escape_string($cid).'"')){
					$stmtupdate->execute();
					$checkit=1;
					//return true;
				}else return false;


			}
		}



		if($checkit==1){
			if ($stmtchecku = $mysqli->prepare('SELECT user_id FROM user where company_id="'.$mysqli->real_escape_string($cid).'" LIMIT 1')) {
				$stmtchecku->execute();
				$stmtchecku->store_result();
				if ($stmtchecku->num_rows == 0){			
					if(count($tmpusql)){
						if($stmtuupdate = $mysqli->prepare('UPDATE user_permission SET '.implode(",",$tmpusql).' WHERE user_id IN (SELECT user_id FROM user where company_id="'.$mysqli->real_escape_string($cid).'")')){
							$stmtuupdate->execute();
							return true;
						}else return false;
					}
				}
			}
			return true;
		}

		return false;

	}


	if($_SESSION["group_id"]==5 and 1==2)
	{
		if($cid != $ucid) return false;

		$futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$adhocreport=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;

		$csql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Adhoc Report`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory` FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" LIMIT 1';

		if ($stmtucheck = $mysqli->prepare($csql)) {
			$stmtucheck->execute();
			$stmtucheck->store_result();
			if ($stmtucheck->num_rows > 0) {
			    $stmtucheck->bind_result($futurespricing,$locationalmarginalpricing,$liveweather,$streamingnews,$verveenergyblog,$marketcommentary,$weeklyreports,$directaccessinformation,$strategy,$dynamicriskmanagement,$mastersupplyagreements,$suppliercontracts,$utilityrequirements,$regulatedinformation,$utilityratereports,$utilityratechangerequests,$startnewservice,$stopservice,$startstopstatus,$correspondence,$ubmarchive,$ubmsoftware,$utilitybudgets,$invoicevalidation,$exceptionreports,$resolvedexceptions,$siteandaccountchanges,$dataanalysis,$consumptionreports,$customreports,$benchmarkreport,$csresgsoftware,$sustainabilityreports,$corporatereports,$surveys,$distributedgeneration,$efficiencyupgrades,$evcharging,$rebatesandincentives,$other,$sitelist,$vendors,$accounts,$invoices,$usersedit,$userpermissions,$companydefaults,$adhocreport,$chat,$accountreport,$vendorreport,$processedinvoicereport,$accrualreport,$invoiceaudit,$monthlyweather,$depositlatefeereport,$costandusagereport,$ghgreport,$siteinventory);

				$stmtucheck->fetch();


			}else return false;
		}else return false;

		if($uper != "")
			$uperarr=explode(",",$uper);

		//if($aper != "")
			//$aperarr=explode(",",$aper);

		foreach($colomnsarr as $ky=>$vl){
			$clname=strtolower(str_replace(array(" ","`"),"",$vl));
			$adnname=$clname."arr";
			$adnamearr=$$adnname;
			if(!is_array($adnamearr) or count($adnamearr) !=2) return false;

			if (!in_array($clname, $uperarr) and $adnamearr[1]==1 ) $tmpp='1';
			else $tmpp='0';

			$tmpp .= ':'.$adnamearr[1];
			$tmpsql[]=$vl.'="'.$tmpp.'"';

			if ($tmpp != '1:1' ) $tmpusql[]=$vl.'=0';
		}

		if ($stmtcheck = $mysqli->prepare('SELECT company_id FROM company_permission where company_id="'.$mysqli->real_escape_string($cid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				if($stmtinsert = $mysqli->prepare('INSERT INTO company_permission SET company_id="'.$mysqli->real_escape_string($cid).'",'.implode(",",$tmpsql))){
					$stmtinsert->execute();
					$checkit=1;
					//return true;
				}else return false;


			}else{
				if($stmtupdate = $mysqli->prepare('UPDATE company_permission SET '.implode(",",$tmpsql).' WHERE company_id="'.$mysqli->real_escape_string($cid).'"')){
					$stmtupdate->execute();
					$checkit=1;
					//return true;
				}else return false;


			}
		}



		if($checkit==1){
			if(count($tmpusql)){
				if($stmtuupdate = $mysqli->prepare('UPDATE user_permission SET '.implode(",",$tmpusql).' WHERE user_id IN (SELECT user_id FROM user where company_id="'.$mysqli->real_escape_string($cid).'")')){
					$stmtuupdate->execute();
					return true;
				}else return false;
			}
			return true;
		}



	}
	return false;
}



function update_user_interface($mysqli,$userid='',$uper='')
{
	global $user_one;
	global $usergroups_id;
	global $ucid;

	$userid=(int)@trim($userid);
	if($userid=="" or $userid==0) return false;

	$dmyc=$dmya=$dm=$uperarr=$aperarr=$tmpsql=array();

	$colomnsarr=array('`Futures Pricing`','`Locational Marginal Pricing`','`Live Weather`','`Streaming News`','`Verve Energy Blog`','`Market Commentary`','`Weekly Reports`','`Direct Access Information`','`Strategy`','`Dynamic Risk Management`','`Master Supply Agreements`','`Supplier Contracts`','`Utility Requirements`','`Regulated Information`','`Utility Rate Reports`','`Utility Rate Change Requests`','`Start New Service`','`Stop Service`','`StartStop Status`','`Correspondence`','`UBM Archive`','`UBM Software`','`Utility Budgets`','`Invoice Validation`','`Exception Reports`','`Resolved Exceptions`','`Site and Account Changes`','`Data Analysis`','`Consumption Reports`','`Custom Reports`','`Benchmark Report`','`CSR ESG Software`','`Sustainability Reports`','`Corporate Reports`','`Surveys`','`Distributed Generation`','`Efficiency Upgrades`','`EV Charging`','`Rebates and Incentives`','`Other`','`Site List`','`Vendors`','`Accounts`','`Invoices`','`Users Edit`','`User Permissions`','`Company Defaults`','`Adhoc Report`','`Chat`','`Account Report`','`Vendor Report`','`Processed Invoice Report`','`Accrual Report`','`Invoice Audit`','`Monthly Weather`','`Deposit Late Fee Report`','`Cost and Usage Report`','`GHG Report`','`Site Inventory`');

	if($_SESSION["group_id"]==1)
	{
		if ($stmtcheck = $mysqli->prepare('SELECT user_id FROM user where user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows != 0){

			}else return false;
		}else return false;

		$futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$adhocreport=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;

		$u_futurespricing=$u_locationalmarginalpricing=$u_liveweather=$u_streamingnews=$u_verveenergyblog=$u_marketcommentary=$u_weeklyreports=$u_directaccessinformation=$u_strategy=$u_dynamicriskmanagement=$u_mastersupplyagreements=$u_suppliercontracts=$u_utilityrequirements=$u_regulatedinformation=$u_utilityratereports=$u_utilityratechangerequests=$u_startnewservice=$u_stopservice=$u_startstopstatus=$u_correspondence=$u_ubmarchive=$u_ubmsoftware=$u_utilitybudgets=$u_invoicevalidation=$u_exceptionreports=$u_resolvedexceptions=$u_siteandaccountchanges=$u_dataanalysis=$u_consumptionreports=$u_customreports=$u_benchmarkreport=$u_csresgsoftware=$u_sustainabilityreports=$u_corporatereports=$u_surveys=$u_distributedgeneration=$u_efficiencyupgrades=$u_evcharging=$u_rebatesandincentives=$u_other=$u_sitelist=$u_vendors=$u_accounts=$u_invoices=$u_usersedit=$u_userpermissions=$u_companydefaults=$u_adhocreport=$u_chat=$u_accountreport=$u_vendorreport=$u_processedinvoicereport=$u_accrualreport=$u_invoiceaudit=$u_monthlyweather=$u_depositlatefeereport=$u_costandusagereport=$u_ghgreport=$u_siteinventory=0;

		$partsql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Adhoc Report`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory`';


		$usql=$partsql.' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1';

		$csql=$partsql.' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1';

		if ($stmt = $mysqli->prepare($csql)) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
			    $stmt->bind_result($futurespricing,$locationalmarginalpricing,$liveweather,$streamingnews,$verveenergyblog,$marketcommentary,$weeklyreports,$directaccessinformation,$strategy,$dynamicriskmanagement,$mastersupplyagreements,$suppliercontracts,$utilityrequirements,$regulatedinformation,$utilityratereports,$utilityratechangerequests,$startnewservice,$stopservice,$startstopstatus,$correspondence,$ubmarchive,$ubmsoftware,$utilitybudgets,$invoicevalidation,$exceptionreports,$resolvedexceptions,$siteandaccountchanges,$dataanalysis,$consumptionreports,$customreports,$benchmarkreport,$csresgsoftware,$sustainabilityreports,$corporatereports,$surveys,$distributedgeneration,$efficiencyupgrades,$evcharging,$rebatesandincentives,$other,$sitelist,$vendors,$accounts,$invoices,$usersedit,$userpermissions,$companydefaults,$adhocreport,$chat,$accountreport,$vendorreport,$processedinvoicereport,$accrualreport,$invoiceaudit,$monthlyweather,$depositlatefeereport,$costandusagereport,$ghgreport,$siteinventory);

				$stmt->fetch();
			}
		}


		if ($ustmt = $mysqli->prepare($usql)) {
			$ustmt->execute();
			$ustmt->store_result();
			if ($ustmt->num_rows > 0) {
			    $ustmt->bind_result($u_futurespricing,$u_locationalmarginalpricing,$u_liveweather,$u_streamingnews,$u_verveenergyblog,$u_marketcommentary,$u_weeklyreports,$u_directaccessinformation,$u_strategy,$u_dynamicriskmanagement,$u_mastersupplyagreements,$u_suppliercontracts,$u_utilityrequirements,$u_regulatedinformation,$u_utilityratereports,$u_utilityratechangerequests,$u_startnewservice,$u_stopservice,$u_startstopstatus,$u_correspondence,$u_ubmarchive,$u_ubmsoftware,$u_utilitybudgets,$u_invoicevalidation,$u_exceptionreports,$u_resolvedexceptions,$u_siteandaccountchanges,$u_dataanalysis,$u_consumptionreports,$u_customreports,$u_benchmarkreport,$u_csresgsoftware,$u_sustainabilityreports,$u_corporatereports,$u_surveys,$u_distributedgeneration,$u_efficiencyupgrades,$u_evcharging,$u_rebatesandincentives,$u_other,$u_sitelist,$u_vendors,$u_accounts,$u_invoices,$u_usersedit,$u_userpermissions,$u_companydefaults,$u_adhocreport,$u_chat,$u_accountreport,$u_vendorreport,$u_processedinvoicereport,$u_accrualreport,$u_invoiceaudit,$u_monthlyweather,$u_depositlatefeereport,$u_costandusagereport,$u_ghgreport,$u_siteinventory);

				$ustmt->fetch();
			}
		}

		if($uper != "")
			$uperarr=explode(",",$uper);

		//if($aper != "")
			//$aperarr=explode(",",$aper);

		foreach($colomnsarr as $ky=>$vl){
			$clname=strtolower(str_replace(array(" ","`"),"",$vl));
			//$adnname=$clname."arr";
			$adname=$$clname;//$$adnname;
			//if(!is_array($adnamearr) or count($adnamearr) !=2) return false;

			if (!in_array($clname, $uperarr) and $adname==1 ) $tmpp='1';
			else $tmpp='0';

			$tmpsql[]=$vl.'="'.$tmpp.'"';
		}

		if ($stmtcheck = $mysqli->prepare('SELECT user_id FROM user_permission where user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				if($stmtinsert = $mysqli->prepare('INSERT INTO user_permission SET user_id="'.$mysqli->real_escape_string($userid).'",'.implode(",",$tmpsql))){
					$stmtinsert->execute();
					return true;
				}else return false;


			}else{
				if($stmtupdate = $mysqli->prepare('UPDATE user_permission SET '.implode(",",$tmpsql).' WHERE user_id="'.$mysqli->real_escape_string($userid).'"')){
					$stmtupdate->execute();
					return true;
				}else return false;


			}
		}

	}


	if($_SESSION["group_id"]==5)
	{

		if ($stmtcheck = $mysqli->prepare('SELECT user_id FROM user where company_id="'.$mysqli->real_escape_string($ucid).'" and user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows != 0){

			}else return false;
		}else return false;

		$futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$adhocreport=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;


		$u_futurespricing=$u_locationalmarginalpricing=$u_liveweather=$u_streamingnews=$u_verveenergyblog=$u_marketcommentary=$u_weeklyreports=$u_directaccessinformation=$u_strategy=$u_dynamicriskmanagement=$u_mastersupplyagreements=$u_suppliercontracts=$u_utilityrequirements=$u_regulatedinformation=$u_utilityratereports=$u_utilityratechangerequests=$u_startnewservice=$u_stopservice=$u_startstopstatus=$u_correspondence=$u_ubmarchive=$u_ubmsoftware=$u_utilitybudgets=$u_invoicevalidation=$u_exceptionreports=$u_resolvedexceptions=$u_siteandaccountchanges=$u_dataanalysis=$u_consumptionreports=$u_customreports=$u_benchmarkreport=$u_csresgsoftware=$u_sustainabilityreports=$u_corporatereports=$u_surveys=$u_distributedgeneration=$u_efficiencyupgrades=$u_evcharging=$u_rebatesandincentives=$u_other=$u_sitelist=$u_vendors=$u_accounts=$u_invoices=$u_usersedit=$u_userpermissions=$u_companydefaults=$u_adhocreport=$u_chat=$u_accountreport=$u_vendorreport=$u_processedinvoicereport=$u_accrualreport=$u_invoiceaudit=$u_monthlyweather=$u_depositlatefeereport=$u_costandusagereport=$u_ghgreport=$u_siteinventory=0;

		$partsql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Adhoc Report`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory`';

		$usql=$partsql.' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" and u.user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1';

		$csql=$partsql.' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" and u.user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1';

		if ($stmt = $mysqli->prepare($csql)) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
			    $stmt->bind_result($futurespricing,$locationalmarginalpricing,$liveweather,$streamingnews,$verveenergyblog,$marketcommentary,$weeklyreports,$directaccessinformation,$strategy,$dynamicriskmanagement,$mastersupplyagreements,$suppliercontracts,$utilityrequirements,$regulatedinformation,$utilityratereports,$utilityratechangerequests,$startnewservice,$stopservice,$startstopstatus,$correspondence,$ubmarchive,$ubmsoftware,$utilitybudgets,$invoicevalidation,$exceptionreports,$resolvedexceptions,$siteandaccountchanges,$dataanalysis,$consumptionreports,$customreports,$benchmarkreport,$csresgsoftware,$sustainabilityreports,$corporatereports,$surveys,$distributedgeneration,$efficiencyupgrades,$evcharging,$rebatesandincentives,$other,$sitelist,$vendors,$accounts,$invoices,$usersedit,$userpermissions,$companydefaults,$adhocreport,$chat,$accountreport,$vendorreport,$processedinvoicereport,$accrualreport,$invoiceaudit,$monthlyweather,$depositlatefeereport,$costandusagereport,$ghgreport,$siteinventory);

				$stmt->fetch();
			}
		}


		if ($ustmt = $mysqli->prepare($usql)) {
			$ustmt->execute();
			$ustmt->store_result();
			if ($ustmt->num_rows > 0) {
			    $ustmt->bind_result($u_futurespricing,$u_locationalmarginalpricing,$u_liveweather,$u_streamingnews,$u_verveenergyblog,$u_marketcommentary,$u_weeklyreports,$u_directaccessinformation,$u_strategy,$u_dynamicriskmanagement,$u_mastersupplyagreements,$u_suppliercontracts,$u_utilityrequirements,$u_regulatedinformation,$u_utilityratereports,$u_utilityratechangerequests,$u_startnewservice,$u_stopservice,$u_startstopstatus,$u_correspondence,$u_ubmarchive,$u_ubmsoftware,$u_utilitybudgets,$u_invoicevalidation,$u_exceptionreports,$u_resolvedexceptions,$u_siteandaccountchanges,$u_dataanalysis,$u_consumptionreports,$u_customreports,$u_benchmarkreport,$u_csresgsoftware,$u_sustainabilityreports,$u_corporatereports,$u_surveys,$u_distributedgeneration,$u_efficiencyupgrades,$u_evcharging,$u_rebatesandincentives,$u_other,$u_sitelist,$u_vendors,$u_accounts,$u_invoices,$u_usersedit,$u_userpermissions,$u_companydefaults,$u_adhocreport,$u_chat,$u_accountreport,$u_vendorreport,$u_processedinvoicereport,$u_accrualreport,$u_invoiceaudit,$u_monthlyweather,$u_depositlatefeereport,$u_costandusagereport,$u_ghgreport,$u_siteinventory);

				$ustmt->fetch();
			}
		}

		if($uper != "")
			$uperarr=explode(",",$uper);

		//if($aper != "")
			//$aperarr=explode(",",$aper);

		foreach($colomnsarr as $ky=>$vl){
			$clname=strtolower(str_replace(array(" ","`"),"",$vl));
			//$adnname=$clname."arr";
			$adname=$$clname;//$$adnname;
			//if(!is_array($adnamearr) or count($adnamearr) !=2) return false;

			if (!in_array($clname, $uperarr) and $adname==1 ) $tmpp='1';
			else $tmpp='0';

			$tmpsql[]=$vl.'="'.$tmpp.'"';
		}

		if ($stmtcheck = $mysqli->prepare('SELECT user_id FROM user_permission where user_id="'.$mysqli->real_escape_string($userid).'" LIMIT 1')) {
			$stmtcheck->execute();
			$stmtcheck->store_result();
			if ($stmtcheck->num_rows == 0){
				if($stmtinsert = $mysqli->prepare('INSERT INTO user_permission SET user_id="'.$mysqli->real_escape_string($userid).'",'.implode(",",$tmpsql))){
					$stmtinsert->execute();
					return true;
				}else return false;


			}else{
				if($stmtupdate = $mysqli->prepare('UPDATE user_permission SET '.implode(",",$tmpsql).' WHERE user_id="'.$mysqli->real_escape_string($userid).'"')){
					$stmtupdate->execute();
					return true;
				}else return false;


			}
		}
	}
	return false;
}
return false;
?>
