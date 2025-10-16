<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
$user_one = $_SESSION["user_id"];
$usergroups_id=$_SESSION["group_id"];
$ucid = $_SESSION["company_id"];

if(($_SESSION["group_id"]==5 or $_SESSION["group_id"]==1) and isset($_GET["editpermission"])){
	if(isset($_GET['userid']) and $_GET['userid'] != "" and $_GET['userid'] > 0){
		$_userid=$_GET['userid'];



		$_pdisabled_menu_a_arr=$_pdisabled_menu_c_arr=array();

		$futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$adhocreport=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;


		$u_futurespricing=$u_locationalmarginalpricing=$u_liveweather=$u_streamingnews=$u_verveenergyblog=$u_marketcommentary=$u_weeklyreports=$u_directaccessinformation=$u_strategy=$u_dynamicriskmanagement=$u_mastersupplyagreements=$u_suppliercontracts=$u_utilityrequirements=$u_regulatedinformation=$u_utilityratereports=$u_utilityratechangerequests=$u_startnewservice=$u_stopservice=$u_startstopstatus=$u_correspondence=$u_ubmarchive=$u_ubmsoftware=$u_utilitybudgets=$u_invoicevalidation=$u_exceptionreports=$u_resolvedexceptions=$u_siteandaccountchanges=$u_dataanalysis=$u_consumptionreports=$u_customreports=$u_benchmarkreport=$u_csresgsoftware=$u_sustainabilityreports=$u_corporatereports=$u_surveys=$u_distributedgeneration=$u_efficiencyupgrades=$u_evcharging=$u_rebatesandincentives=$u_other=$u_sitelist=$u_vendors=$u_accounts=$u_invoices=$u_usersedit=$u_userpermissions=$u_companydefaults=$u_adhocreport=$u_chat=$u_accountreport=$u_vendorreport=$u_processedinvoicereport=$u_accrualreport=$u_invoiceaudit=$u_monthlyweather=$u_depositlatefeereport=$u_costandusagereport=$u_ghgreport=$u_siteinventory=0;

$tmpsql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Adhoc Report`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory`';

		//$cid=$_GET['cid'];

		if($_SESSION["group_id"]==1){
		    $usql=$tmpsql.' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.user_id="'.$mysqli->real_escape_string($_userid).'" LIMIT 1';

		    $csql=$tmpsql.' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.user_id="'.$mysqli->real_escape_string($_userid).'" LIMIT 1';
		}elseif($_SESSION["group_id"]==5){
		    $usql=$tmpsql.' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" and u.user_id="'.$mysqli->real_escape_string($_userid).'" LIMIT 1';

		    $csql=$tmpsql.' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" and u.user_id="'.$mysqli->real_escape_string($_userid).'" LIMIT 1';
		}
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

		if ($ugstmt = $mysqli->prepare("SELECT usergroups_id,email FROM `user` where user_id='".$mysqli->real_escape_string($_userid)."'")) {
			$ugstmt->execute();
			$ugstmt->store_result();
			if ($ugstmt->num_rows > 0) {
				$ugstmt->bind_result($u_gp,$u_email);

				$ugstmt->fetch();
			}else die("Error occured. Please try after sometime!");
		}else die("Error occured. Please try after sometime!");

	?>




	<style>
	.redc{color: red !important;
		font-style: italic;
		padding-left: 5px;
		font-size: 12px;}
	.htitl{text-align:center;border-bottom:1px solid black;}
	#wid-id-00 #pform label.toggle{margin-top:	-12px !important;}
	#wid-id-00 #pform table{
	margin-left: 26px;
	width: 75%;}
	#wid-id-00 #pform table tr{border-bottom: 5px solid transparent;}
	#wid-id-00 .saveit{text-align: center;}
	.cusmenu article{
		min-height: 200px;
		height: auto;
	}
	.cusmenu .selectbox{
		margin-top:20px;
		margin-bottom:10px;
		min-height: 37px;
	}
	.cusmenu .selectbox table{
		width: auto !important;
	}
	</style>
				<div class="jarviswidget jarviswidget-color-blueDark cusmenu" id="wid-id-00" data-widget-fullscreenbutton="false" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2> Edit User Permission (<?php echo $u_email; ?>)</h2>
					</header>

					<!-- widget div-->
					<div>
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body">
						<form action="" class="smart-form" id="pform">
											<!--<label class="toggle">
												<input type="checkbox" name="checkbox-toggle" checked="checked">
												<i data-swchon-text="ON" data-swchoff-text="OFF"></i></label>-->
				<?php if($_SESSION["group_id"]==1){ ?>
					<p class="htitl">Please Note: Disabled buttons need to be enabled in company permissions.</p>
				<?php } ?>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 selectbox">
				<table cellpadding="5">
					<tr>
					 <td><input type="radio" value="1" name="uselectall" class="uselectall"><b>Select All</b></td>
					 <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</td>
					 <td><input type="radio" value="2" name="uselectall" class="udeselectall"><b>Deselect All</b></td>
					</tr>
				</table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<table cellpadding="5">
				  <tr>
					 <td colspan="2"><b>Market Resources</b></td>
				  </tr>
				  <tr>
					<td>Futures Pricing</td>
	<?php if($futurespricing==1){ ?>
				<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="futurespricing" class="upinput" <?php if($u_futurespricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
				</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Locational Marginal Pricing</td>
	<?php if($locationalmarginalpricing==1){ ?>
				<td>					<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="locationalmarginalpricing" class="upinput" <?php if($u_locationalmarginalpricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label></td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Live Weather</td>
	<?php if($liveweather==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="liveweather" class="upinput" <?php if($u_liveweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Streaming News</td>
	<?php if($streamingnews==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="streamingnews" class="upinput" <?php if($u_streamingnews==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Verve Energy Blog</td>
	<?php if($verveenergyblog==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="verveenergyblog" class="upinput" <?php if($u_verveenergyblog==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Market Commentary</td>
	<?php if($marketcommentary==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="marketcommentary" class="upinput" <?php if($u_marketcommentary==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Weekly Reports</td>
	<?php if($weeklyreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="weeklyreports" class="upinput" <?php if($u_weeklyreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<table cellpadding="5">
			  <tr>
				 <td colspan="2"><b>Energy Procurement</b></td>
			  </tr>
			  <tr>
				<td>Direct Access Information</td>
	<?php if($directaccessinformation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="directaccessinformation" class="upinput" <?php if($u_directaccessinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Strategy</td>
	<?php if($strategy==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="strategy" class="upinput" <?php if($u_strategy==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Dynamic Risk Management</td>
	<?php if($dynamicriskmanagement==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dynamicriskmanagement" class="upinput" <?php if($u_dynamicriskmanagement==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Master Supply Agreements</td>
	<?php if($mastersupplyagreements==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="mastersupplyagreements" class="upinput" <?php if($u_mastersupplyagreements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Supplier Contracts</td>
	<?php if($suppliercontracts==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="suppliercontracts" class="upinput" <?php if($u_suppliercontracts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Utility Requirements</td>
	<?php if($utilityrequirements==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityrequirements" class="upinput" <?php if($u_utilityrequirements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<table cellpadding="5">
				  <tr>
					 <td colspan="2"><b>Rate Optimization</b></td>
				  </tr>
				  <tr>
					<td>Regulated Information</td>
	<?php if($regulatedinformation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="regulatedinformation" class="upinput" <?php if($u_regulatedinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Utility Rate Reports</td>
	<?php if($utilityratereports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratereports" class="upinput" <?php if($u_utilityratereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Utility Rate Change Requests</td>
	<?php if($utilityratechangerequests==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratechangerequests" class="upinput" <?php if($u_utilityratechangerequests==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Account Admin</b></td>
		  </tr>
		  <tr>
			<td>Start New Service</td>
	<?php if($startnewservice==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startnewservice" class="upinput" <?php if($u_startnewservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Stop Service</td>
	<?php if($stopservice==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="stopservice" class="upinput" <?php if($u_stopservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Start/Stop Status</td>
	<?php if($startstopstatus==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startstopstatus" class="upinput" <?php if($u_startstopstatus==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Correspondence</td>
	<?php if($correspondence==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="correspondence" class="upinput" <?php if($u_correspondence==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Energy Accounting</b></td>
		  </tr>
			<tr>
			<td>UBM Archive</td>
	<?php if($ubmarchive==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ubmarchive" class="upinput" <?php if($u_ubmarchive==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>UBM Software</td>
	<?php if($ubmsoftware==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ubmsoftware" class="upinput" <?php if($u_ubmsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Utility Budgets</td>
	<?php if($utilitybudgets==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilitybudgets" class="upinput" <?php if($u_utilitybudgets==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoice Validation</td>
	<?php if($invoicevalidation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoicevalidation" class="upinput" <?php if($u_invoicevalidation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Exception Reports</td>
	<?php if($exceptionreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="exceptionreports" class="upinput" <?php if($u_exceptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Resolved Exceptions</td>
	<?php if($resolvedexceptions==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="resolvedexceptions" class="upinput" <?php if($u_resolvedexceptions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Site and Account Changes</td>
	<?php if($siteandaccountchanges==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteandaccountchanges" class="upinput" <?php if($u_siteandaccountchanges==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Data Management</b></td>
		  </tr>
		  <tr>
			<td>Data Analysis</td>
	<?php if($dataanalysis==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dataanalysis" class="upinput" <?php if($u_dataanalysis==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Consumption Reports</td>
	<?php if($consumptionreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="consumptionreports" class="upinput" <?php if($u_consumptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Custom Reports</td>
	<?php if($customreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="customreports" class="upinput" <?php if($u_customreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Benchmark Report</td>
	<?php if($benchmarkreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="benchmarkreport" class="upinput" <?php if($u_benchmarkreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Adhoc Report</td>
	<?php if($adhocreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="adhocreport" class="upinput" <?php if($u_adhocreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Sustainability</b></td>
		  </tr>
		  <tr>
			<td>CSR/ESG Software</td>
	<?php if($csresgsoftware==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="csresgsoftware" class="upinput" <?php if($u_csresgsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Sustainability Reports</td>
	<?php if($sustainabilityreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sustainabilityreports" class="upinput" <?php if($u_sustainabilityreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Corporate Reports</td>
	<?php if($corporatereports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="corporatereports" class="upinput" <?php if($u_corporatereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Surveys</td>
	<?php if($surveys==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="surveys" class="upinput" <?php if($u_surveys==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Projects</b></td>
		  </tr>
		  <tr>
			<td>Distributed Generation</td>
	<?php if($distributedgeneration==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="distributedgeneration" class="upinput" <?php if($u_distributedgeneration==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Efficiency Upgrades</td>
	<?php if($efficiencyupgrades==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="efficiencyupgrades" class="upinput" <?php if($u_efficiencyupgrades==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>EV Charging</td>
	<?php if($evcharging==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="evcharging" class="upinput" <?php if($u_evcharging==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Rebates and Incentives</td>
	<?php if($rebatesandincentives==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="rebatesandincentives" class="upinput" <?php if($u_rebatesandincentives==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Other</td>
	<?php if($other==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="other" class="upinput" <?php if($u_other==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Admin</b></td>
		  </tr>
		  <tr>
			<td>User Edit</td>
	<?php if($usersedit==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="usersedit" class="upinput" <?php if($u_usersedit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>User Permissions</td>
	<?php if($userpermissions==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="userpermissions" class="upinput" <?php if($u_userpermissions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Company Defaults</td>
	<?php if($companydefaults==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="companydefaults" class="upinput" <?php if($u_companydefaults==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Other Menus</b></td>
		  </tr>
		  <tr>
			<td>Site List</td>
	<?php if($sitelist==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sitelist" class="upinput" <?php if($u_sitelist==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Vendors</td>
	<?php if($vendors==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendors" class="upinput" <?php if($u_vendors==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Accounts</td>
	<?php if($accounts==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accounts" class="upinput" <?php if($u_accounts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoices</td>
	<?php if($invoices==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoices" class="upinput" <?php if($u_invoices==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Chat</td>
	<?php if($chat==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="chat" class="upinput" <?php if($u_chat==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Report Framework</b></td>
		  </tr>
		  <tr>
			<td>Account Report</td>
	<?php if($accountreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accountreport" class="upinput" <?php if($u_accountreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Vendor Report</td>
	<?php if($vendorreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendorreport" class="upinput" <?php if($u_vendorreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Processed Invoice Report</td>
	<?php if($processedinvoicereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="processedinvoicereport" class="upinput" <?php if($u_processedinvoicereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Accrual Report</td>
	<?php if($accrualreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accrualreport" class="upinput" <?php if($u_accrualreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoice Audit</td>
	<?php if($invoiceaudit==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoiceaudit" class="upinput" <?php if($u_invoiceaudit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Monthly Weather</td>
	<?php if($monthlyweather==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="monthlyweather" class="upinput" <?php if($u_monthlyweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Deposit/Late Fee Report</td>
	<?php if($depositlatefeereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="depositlatefeereport" class="upinput" <?php if($u_depositlatefeereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Cost and Usage Report</td>
	<?php if($costandusagereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="costandusagereport" class="upinput" <?php if($u_costandusagereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>GHG Report</td>
	<?php if($ghgreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ghgreport" class="upinput" <?php if($u_ghgreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Site Inventory</td>
	<?php if($siteinventory==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteinventory" class="upinput" <?php if($u_siteinventory==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="upcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
		</div>
						</form>
						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->

	<script>
	$(document).ready(function()
	{
		$('input[type=radio][name=uselectall]').change(function() {
	    if (this.value == '1') {
				$('.upinput').prop( "checked", false).trigger('click');
	    }
	    else if (this.value == '2') {
				$('.upinput').prop( "checked", true).trigger('click');
	    }else{}
		});
		<?php if($usergroups_id==1){ ?>
			$(document).off('change', '.upinput');
			$(document).on('change', '.upinput', function() {
				//var uper = $("input:checkbox[name=upu]:not(:checked)").map(function(){return $(this).val()}).get().join();
				var uper = $("input:checkbox[class=upinput]:not(:checked)").map(function(){return $(this).val()}).get().join();

				$.ajax({
					type: "POST",
					url: "assets/includes/interface2.inc.php",
					data: "userid=<?php echo $_userid; ?>&uper="+uper,
					async: true,
					success: function(rstatus){
						if(rstatus == true){
							//alert("Saved");
							$.smallBox({
								title : "Saved",
								content : "<i class='fa fa-clock-o'></i> <i>Refresh Page to see changes...</i>",
								color : "#296191",
								iconSmall : "fa fa-thumbs-up bounce animated",
								timeout : 4000
							});
							//window.location.reload();
						}else{
							$.smallBox({
								title : "Error Occured.",
								content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
						}
					}
				});
			});
		<?php }elseif($usergroups_id==5){ ?>
		$(document).off('change', '.upinput');
		$(document).on('change', '.upinput', function() {
			var uper = $("input:checkbox[class=upinput]:not(:checked)").map(function(){return $(this).val()}).get().join();
			//var aper = $("input:checkbox[name=pa]:not(:checked)").map(function(){return $(this).val()}).get().join();

			$.ajax({
				type: "POST",
				url: "assets/includes/interface2.inc.php",
				data: "userid=<?php echo $_userid; ?>&uper="+uper,
				async: true,
				success: function(rstatus){
					if(rstatus == true){
						//alert("Saved");
						$.smallBox({
							title : "Saved",
							content : "<i class='fa fa-clock-o'></i> <i>Refresh Page to see changes...</i>",
							color : "#296191",
							iconSmall : "fa fa-thumbs-up bounce animated",
							timeout : 4000
						});
						//window.location.reload();
					}else{
						$.smallBox({
							title : "Error Occured.",
							content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
							color : "#FFA07A",
							iconSmall : "fa fa-warning shake animated",
							timeout : 4000
						});
					}
				}
			});
		});
		<?php } ?>
	});
	</script>






<?php
	}elseif(isset($_GET['cid']) and $_GET['cid'] != "" and $_GET['cid'] > 0){

		$_pdisabled_menu_a_arr=$_pdisabled_menu_c_arr=array();

		$futurespricing=$locationalmarginalpricing=$liveweather=$streamingnews=$verveenergyblog=$marketcommentary=$weeklyreports=$directaccessinformation=$strategy=$dynamicriskmanagement=$mastersupplyagreements=$suppliercontracts=$utilityrequirements=$regulatedinformation=$utilityratereports=$utilityratechangerequests=$startnewservice=$stopservice=$startstopstatus=$correspondence=$ubmarchive=$ubmsoftware=$utilitybudgets=$invoicevalidation=$exceptionreports=$resolvedexceptions=$siteandaccountchanges=$dataanalysis=$consumptionreports=$customreports=$benchmarkreport=$csresgsoftware=$sustainabilityreports=$corporatereports=$surveys=$distributedgeneration=$efficiencyupgrades=$evcharging=$rebatesandincentives=$other=$sitelist=$vendors=$accounts=$invoices=$usersedit=$userpermissions=$companydefaults=$adhocreport=$chat=$accountreport=$vendorreport=$processedinvoicereport=$accrualreport=$invoiceaudit=$monthlyweather=$depositlatefeereport=$costandusagereport=$ghgreport=$siteinventory=0;

		$tmpsql='SELECT cp.`Futures Pricing`,cp.`Locational Marginal Pricing`,cp.`Live Weather`,cp.`Streaming News`,cp.`Verve Energy Blog`,cp.`Market Commentary`,cp.`Weekly Reports`,cp.`Direct Access Information`,cp.`Strategy`,cp.`Dynamic Risk Management`,cp.`Master Supply Agreements`,cp.`Supplier Contracts`,cp.`Utility Requirements`,cp.`Regulated Information`,cp.`Utility Rate Reports`,cp.`Utility Rate Change Requests`,cp.`Start New Service`,cp.`Stop Service`,cp.`StartStop Status`,cp.`Correspondence`,cp.`UBM Archive`,cp.`UBM Software`,cp.`Utility Budgets`,cp.`Invoice Validation`,cp.`Exception Reports`,cp.`Resolved Exceptions`,cp.`Site and Account Changes`,cp.`Data Analysis`,cp.`Consumption Reports`,cp.`Custom Reports`,cp.`Benchmark Report`,cp.`CSR ESG Software`,cp.`Sustainability Reports`,cp.`Corporate Reports`,cp.`Surveys`,cp.`Distributed Generation`,cp.`Efficiency Upgrades`,cp.`EV Charging`,cp.`Rebates and Incentives`,cp.`Other`,cp.`Site List`,cp.`Vendors`,cp.`Accounts`,cp.`Invoices`,cp.`Users Edit`,cp.`User Permissions`,cp.`Company Defaults`,cp.`Adhoc Report`,cp.`Chat`,cp.`Account Report`,cp.`Vendor Report`,cp.`Processed Invoice Report`,cp.`Accrual Report`,cp.`Invoice Audit`,cp.`Monthly Weather`,cp.`Deposit Late Fee Report`,cp.`Cost and Usage Report`,cp.`GHG Report`,cp.`Site Inventory`';

		$cid=$_GET['cid'];

		if($_SESSION["group_id"]==1)
		    $csql=$tmpsql.' FROM `company_permission` cp, company u where u.company_id=cp.company_id and cp.company_id="'.$mysqli->real_escape_string($cid).'" LIMIT 1';
		elseif($_SESSION["group_id"]==5)
		$csql=$tmpsql.' FROM `company_permission` cp, company u where u.company_id=cp.company_id and u.company_id="'.$mysqli->real_escape_string($ucid).'" and u.company_id="'.$mysqli->real_escape_string($cid).'" LIMIT 1';

		if ($stmt = $mysqli->prepare($csql)) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
			    $stmt->bind_result($futurespricing,$locationalmarginalpricing,$liveweather,$streamingnews,$verveenergyblog,$marketcommentary,$weeklyreports,$directaccessinformation,$strategy,$dynamicriskmanagement,$mastersupplyagreements,$suppliercontracts,$utilityrequirements,$regulatedinformation,$utilityratereports,$utilityratechangerequests,$startnewservice,$stopservice,$startstopstatus,$correspondence,$ubmarchive,$ubmsoftware,$utilitybudgets,$invoicevalidation,$exceptionreports,$resolvedexceptions,$siteandaccountchanges,$dataanalysis,$consumptionreports,$customreports,$benchmarkreport,$csresgsoftware,$sustainabilityreports,$corporatereports,$surveys,$distributedgeneration,$efficiencyupgrades,$evcharging,$rebatesandincentives,$other,$sitelist,$vendors,$accounts,$invoices,$usersedit,$userpermissions,$companydefaults,$adhocreport,$chat,$accountreport,$vendorreport,$processedinvoicereport,$accrualreport,$invoiceaudit,$monthlyweather,$depositlatefeereport,$costandusagereport,$ghgreport,$siteinventory);

				$stmt->fetch();
			}
		}

		if ($ugstmt = $mysqli->prepare("SELECT company_name FROM `company` where company_id='".$mysqli->real_escape_string($cid)."'")) {
			$ugstmt->execute();
			$ugstmt->store_result();
			if ($ugstmt->num_rows > 0) {
				$ugstmt->bind_result($c_name);

				$ugstmt->fetch();
			}else die("Error occured. Please try after sometime!");
		}else die("Error occured. Please try after sometime!");

	?>




	<style>
	.redc{color: red !important;
		font-style: italic;
		padding-left: 5px;
		font-size: 12px;}
	.htitl{text-align:center;border-bottom:1px solid black;}
	#wid-id-00 #pform label.toggle{margin-top:	-12px !important;}
	#wid-id-00 #pform table{
	margin-left: 26px;
	width: 75%;}
	#wid-id-00 #pform table tr{border-bottom: 5px solid transparent;}
	#wid-id-00 .saveit{text-align: center;}
	.cusmenu article {
		min-height: 200px;
		height: auto;
	}
	.cusmenu .selectbox{
		margin-top:20px;
		margin-bottom:10px;
		min-height: 37px;
	}
	.cusmenu .selectbox table{
		width: auto !important;
	}
	</style>
				<div class="jarviswidget jarviswidget-color-blueDark cusmenu" id="wid-id-00" data-widget-fullscreenbutton="false" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2> Edit Company Permission (<?php echo $c_name; ?>)</h2>
					</header>

					<!-- widget div-->
					<div>
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body">
						<form action="" class="smart-form" id="pform">
											<!--<label class="toggle">
												<input type="checkbox" name="checkbox-toggle" checked="checked">
												<i data-swchon-text="ON" data-swchoff-text="OFF"></i></label>-->
				<?php if($_SESSION["group_id"]==1){ ?>
					<p class="htitl" style="display:none;">Please Note: checkbox 1 is User Permission and 2 is Admin Permission.</p>
				<?php } ?>
		<div class="row">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 selectbox">
				<table cellpadding="5">
					<tr>
					 <td><input type="radio" value="1" name="cselectall" class="cselectall"><b>Select All</b></td>
					 <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</td>
					 <td><input type="radio" value="2" name="cselectall" class="cdeselectall"><b>Deselect All</b></td>
					</tr>
				</table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<table cellpadding="5">
				  <tr>
					 <td colspan="2"><b>Market Resources</b></td>
				  </tr>
				  <tr>
					<td>Futures Pricing</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="futurespricing" class="pa" <?php if($futurespricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					 </td>
	 <?php }elseif($futurespricing==1){ ?>
				<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="futurespricing" class="pinput" <?php if($futurespricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
				</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Locational Marginal Pricing</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td><label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="locationalmarginalpricing" class="pa" <?php if($locationalmarginalpricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td></td>
	 <?php }elseif($locationalmarginalpricing==1){ ?>
				<td>					<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="locationalmarginalpricing" class="pinput" <?php if($locationalmarginalpricing==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Live Weather</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="liveweather" class="pa" <?php if($liveweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($liveweather==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="liveweather" class="pinput" <?php if($liveweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Streaming News</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="streamingnews" class="pa" <?php if($streamingnews==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($streamingnews==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="streamingnews" class="pinput" <?php if($streamingnews==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Verve Energy Blog</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="verveenergyblog" class="pa" <?php if($verveenergyblog==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($verveenergyblog==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="verveenergyblog" class="pinput" <?php if($verveenergyblog==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Market Commentary</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="marketcommentary" class="pa" <?php if($marketcommentary==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($marketcommentary==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="marketcommentary" class="pinput" <?php if($marketcommentary==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Weekly Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="weeklyreports" class="pa" <?php if($weeklyreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($weeklyreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="weeklyreports" class="pinput" <?php if($weeklyreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<table cellpadding="5">
			  <tr>
				 <td colspan="2"><b>Energy Procurement</b></td>
			  </tr>
			  <tr>
				<td>Direct Access Information</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="directaccessinformation" class="pa" <?php if($directaccessinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($directaccessinformation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="directaccessinformation" class="pinput" <?php if($directaccessinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Strategy</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="strategy" class="pa" <?php if($strategy==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($strategy==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="strategy" class="pinput" <?php if($strategy==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Dynamic Risk Management</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dynamicriskmanagement" class="pa" <?php if($dynamicriskmanagement==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($dynamicriskmanagement==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dynamicriskmanagement" class="pinput" <?php if($dynamicriskmanagement==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Master Supply Agreements</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="mastersupplyagreements" class="pa" <?php if($mastersupplyagreements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($mastersupplyagreements==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="mastersupplyagreements" class="pinput" <?php if($mastersupplyagreements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Supplier Contracts</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="suppliercontracts" class="pa" <?php if($suppliercontracts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($suppliercontracts==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="suppliercontracts" class="pinput" <?php if($suppliercontracts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Utility Requirements</td>
	<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityrequirements" class="pa" <?php if($utilityrequirements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($utilityrequirements==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityrequirements" class="pinput" <?php if($utilityrequirements==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<table cellpadding="5">
				  <tr>
					 <td colspan="2"><b>Rate Optimization</b></td>
				  </tr>
				  <tr>
					<td>Regulated Information</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="regulatedinformation" class="pa" <?php if($regulatedinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($regulatedinformation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="regulatedinformation" class="pinput" <?php if($regulatedinformation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Utility Rate Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratereports" class="pa" <?php if($utilityratereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($utilityratereports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratereports" class="pinput" <?php if($utilityratereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  <tr>
					<td>Utility Rate Change Requests</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratechangerequests" class="pa" <?php if($utilityratechangerequests==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($utilityratechangerequests==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilityratechangerequests" class="pinput" <?php if($utilityratechangerequests==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
				  </tr>
				  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Account Admin</b></td>
		  </tr>
		  <tr>
			<td>Start New Service</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startnewservice" class="pa" <?php if($startnewservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($startnewservice==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startnewservice" class="pinput" <?php if($startnewservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Stop Service</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="stopservice" class="pa" <?php if($stopservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($stopservice==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="stopservice" class="pinput" <?php if($stopservice==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Start/Stop Status</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startstopstatus" class="pa" <?php if($startstopstatus==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($startstopstatus==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="startstopstatus" class="pinput" <?php if($startstopstatus==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Correspondence</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="correspondence" class="pa" <?php if($correspondence==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($correspondence==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="correspondence" class="pinput" <?php if($correspondence==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Energy Accounting</b></td>
		  </tr>
		  <tr>
				<td>UBM Archive</td>
		<?php if($_SESSION["group_id"]==1){ ?>
				 <td>						<label class="toggle">
								<input type="checkbox" name="checkbox-toggle" value="ubmarchive" class="pa" <?php if($ubmarchive==1) echo 'checked'; ?>>
								<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
							</label></td>
		 <?php }elseif($ubmarchive==1){ ?>
						<td>
							<label class="toggle">
								<input type="checkbox" name="checkbox-toggle" value="ubmarchive" class="pinput" <?php if($ubmarchive==1) echo 'checked'; ?>>
								<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
							</label>
						</td>
		 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			<td>UBM Software</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ubmsoftware" class="pa" <?php if($ubmsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($ubmsoftware==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ubmsoftware" class="pinput" <?php if($ubmsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Utility Budgets</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilitybudgets" class="pa" <?php if($utilitybudgets==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($utilitybudgets==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="utilitybudgets" class="pinput" <?php if($utilitybudgets==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoice Validation</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoicevalidation" class="pa" <?php if($invoicevalidation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($invoicevalidation==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoicevalidation" class="pinput" <?php if($invoicevalidation==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Exception Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="exceptionreports" class="pa" <?php if($exceptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($exceptionreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="exceptionreports" class="pinput" <?php if($exceptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Resolved Exceptions</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="resolvedexceptions" class="pa" <?php if($resolvedexceptions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($resolvedexceptions==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="resolvedexceptions" class="pinput" <?php if($resolvedexceptions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Site and Account Changes</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteandaccountchanges" class="pa" <?php if($siteandaccountchanges==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label></td>
	 <?php }elseif($siteandaccountchanges==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteandaccountchanges" class="pinput" <?php if($siteandaccountchanges==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Data Management</b></td>
		  </tr>
		  <tr>
			<td>Data Analysis</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dataanalysis" class="pa" <?php if($dataanalysis==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($dataanalysis==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="dataanalysis" class="pinput" <?php if($dataanalysis==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Consumption Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="consumptionreports" class="pa" <?php if($consumptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($consumptionreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="consumptionreports" class="pinput" <?php if($consumptionreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Custom Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="customreports" class="pa" <?php if($customreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($customreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="customreports" class="pinput" <?php if($customreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Benchmark Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="benchmarkreport" class="pa" <?php if($benchmarkreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($benchmarkreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="benchmarkreport" class="pinput" <?php if($benchmarkreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Adhoc Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="adhocreport" class="pa" <?php if($adhocreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($adhocreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="adhocreport" class="pinput" <?php if($adhocreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Sustainability</b></td>
		  </tr>
		  <tr>
			<td>CSR/ESG Software</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="csresgsoftware" class="pa" <?php if($csresgsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($csresgsoftware==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="csresgsoftware" class="pinput" <?php if($csresgsoftware==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Sustainability Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sustainabilityreports" class="pa" <?php if($sustainabilityreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($sustainabilityreports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sustainabilityreports" class="pinput" <?php if($sustainabilityreports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Corporate Reports</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="corporatereports" class="pa" <?php if($corporatereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($corporatereports==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="corporatereports" class="pinput" <?php if($corporatereports==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Surveys</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="surveys" class="pa" <?php if($surveys==1) echo 'checked'; ?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
			 </td>
	 <?php }elseif($surveys==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="surveys" class="pinput" <?php if($surveys==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Projects</b></td>
		  </tr>
		  <tr>
			<td>Distributed Generation</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="distributedgeneration" class="pa" <?php if($distributedgeneration==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($distributedgeneration==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="distributedgeneration" class="pinput" <?php if($distributedgeneration==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Efficiency Upgrades</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="efficiencyupgrades" class="pa" <?php if($efficiencyupgrades==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($efficiencyupgrades==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="efficiencyupgrades" class="pinput" <?php if($efficiencyupgrades==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>EV Charging</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="evcharging" class="pa" <?php if($evcharging==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($evcharging==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="evcharging" class="pinput" <?php if($evcharging==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Rebates and Incentives</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="rebatesandincentives" class="pa" <?php if($rebatesandincentives==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($rebatesandincentives==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="rebatesandincentives" class="pinput" <?php if($rebatesandincentives==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Other</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
				<label class="toggle">
					<input type="checkbox" name="checkbox-toggle" value="other" class="pa" <?php if($other==1) echo 'checked'; ?>>
					<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
				</label>
			 </td>
	 <?php }elseif($other==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="other" class="pinput" <?php if($other==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>

			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Other Menus</b></td>
		  </tr>
		  <tr>
			<td>Site List</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sitelist" class="pa" <?php if($sitelist==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($sitelist==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="sitelist" class="pinput" <?php if($sitelist==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Vendors</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendors" class="pa" <?php if($vendors==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($vendors==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendors" class="pinput" <?php if($vendors==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Accounts</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accounts" class="pa" <?php if($accounts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($accounts==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accounts" class="pinput" <?php if($accounts==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoices</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoices" class="pa" <?php if($invoices==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($invoices==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoices" class="pinput" <?php if($invoices==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Chat</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="chat" class="pa" <?php if($chat==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($chat==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="chat" class="pinput" <?php if($chat==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Admin</b></td>
		  </tr>
		  <tr>
			<td>Users Edit</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="usersedit" class="pa" <?php if($usersedit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($usersedit==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="usersedit" class="pinput" <?php if($usersedit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>User Permissions</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="userpermissions" class="pa" <?php if($userpermissions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($userpermissions==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="userpermissions" class="pinput" <?php if($userpermissions==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Company Defaults</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="companydefaults" class="pa" <?php if($companydefaults==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($companydefaults==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="companydefaults" class="pinput" <?php if($companydefaults==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
			<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Report Framework</b></td>
		  </tr>
		  <tr>
			<td>Account Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
			 <td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accountreport" class="pa" <?php if($accountreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
			 </td>
	 <?php }elseif($accountreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accountreport" class="pinput" <?php if($accountreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Vendor Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendorreport" class="pa" <?php if($vendorreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($vendorreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="vendorreport" class="pinput" <?php if($vendorreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Processed Invoice Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="processedinvoicereport" class="pa" <?php if($processedinvoicereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($processedinvoicereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="processedinvoicereport" class="pinput" <?php if($processedinvoicereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Accrual Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accrualreport" class="pa" <?php if($accrualreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($accrualreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="accrualreport" class="pinput" <?php if($accrualreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Invoice Audit</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoiceaudit" class="pa" <?php if($invoiceaudit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($invoiceaudit==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="invoiceaudit" class="pinput" <?php if($invoiceaudit==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Monthly Weather</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="monthlyweather" class="pa" <?php if($monthlyweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($monthlyweather==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="monthlyweather" class="pinput" <?php if($monthlyweather==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Deposit/Late Fee Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="depositlatefeereport" class="pa" <?php if($depositlatefeereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($depositlatefeereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="depositlatefeereport" class="pinput" <?php if($depositlatefeereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Cost and Usage Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="costandusagereport" class="pa" <?php if($costandusagereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($costandusagereport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="costandusagereport" class="pinput" <?php if($costandusagereport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>GHG Report</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ghgreport" class="pa" <?php if($ghgreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($ghgreport==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="ghgreport" class="pinput" <?php if($ghgreport==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Site Inventory</td>
	<?php if($_SESSION["group_id"]==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteinventory" class="pa" <?php if($siteinventory==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }elseif($siteinventory==1){ ?>
					<td>
						<label class="toggle">
							<input type="checkbox" name="checkbox-toggle" value="siteinventory" class="pinput" <?php if($siteinventory==1) echo 'checked'; ?>>
							<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
						</label>
					</td>
	 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
			</article>
		</div>
						</form>
						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->

	<script>
	$(document).ready(function()
	{
		$('.cusmenu input[type=radio][name=cselectall]').change(function() {
	    if (this.value == '1') {
				$('.pa').prop( "checked", false).trigger('click');
				$('.pinput').prop( "checked", false).trigger('click');
	    }
	    else if (this.value == '2') {
				$('.pa').prop( "checked", true).trigger('click');
				$('.pinput').prop( "checked", true).trigger('click');
	    }else{}
		});
		<?php if($usergroups_id==1){ ?>
			/*$('[name=pa]').click(function(){
				if($(this).is(":not(:checked)")){
					$('[name=pu][type=checkbox][value='+$(this).val()+']').prop('checked', false);
				}
			});*/

			$(document).off('change', '.pa');
			$(document).on('change', '.pa', function() {
				//var uper = $("input:checkbox[name=pu]:not(:checked)").map(function(){return $(this).val()}).get().join();
				var aper = uper =$("input:checkbox[class=pa]:not(:checked)").map(function(){return $(this).val()}).get().join();

				$.ajax({
					type: "POST",
					url: "assets/includes/interface2.inc.php",
					data: "cid=<?php echo $cid; ?>&uper="+uper+"&aper="+aper,
					async: true,
					success: function(rstatus){
						if(rstatus == true){
							//alert("Saved");
							$.smallBox({
								title : "Saved",
								content : "<i class='fa fa-clock-o'></i> <i>Refresh Page to see changes...</i>",
								color : "#296191",
								iconSmall : "fa fa-thumbs-up bounce animated",
								timeout : 4000
							});
							//window.location.reload();
						}else{
							$.smallBox({
								title : "Error Occured.",
								content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
						}
					}
				});
			});
		<?php }elseif($usergroups_id==5){ ?>
		$(document).off('change', '.pinput');
		$(document).on('change', '.pinput', function() {
			var uper = $("input:checkbox[class=pinput]:not(:checked)").map(function(){return $(this).val()}).get().join();
			//var aper = $("input:checkbox[name=pa]:not(:checked)").map(function(){return $(this).val()}).get().join();

			$.ajax({
				type: "POST",
				url: "assets/includes/interface2.inc.php",
				data: "cid=<?php echo $cid; ?>&uper="+uper+"&aper=",
				async: true,
				success: function(rstatus){
					if(rstatus == true){
						//alert("Saved");
						$.smallBox({
							title : "Saved",
							content : "<i class='fa fa-clock-o'></i> <i>Refresh Page to see changes...</i>",
							color : "#296191",
							iconSmall : "fa fa-thumbs-up bounce animated",
							timeout : 4000
						});
						//window.location.reload();
					}else{
						$.smallBox({
							title : "Error Occured.",
							content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
							color : "#FFA07A",
							iconSmall : "fa fa-warning shake animated",
							timeout : 4000
						});
					}
				}
			});
		});
		<?php } ?>
	});
	</script>


<?php
	}
}else die("Wrong Parameters Provided!");
?>
