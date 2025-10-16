<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(checkpermission($mysqli,63)==false) die("<h5 style='padding-top:30px;' align='center'>Permission Denied! Please contact Vervantis.</h5>");
if(!isset($_SESSION["group_id"]))
	die("<h5 style='padding-top:30px;' align='center'>Access Restricted</h5>");

$user_one=$_SESSION['user_id'];



if(isset($_GET['reid']) and $_GET['reid'] != "" and $_GET['reid'] > 0)
	$reid=$_GET['reid'];
else
	die("<h5 style='padding-top:30px;' align='center'>Wrong parameters provided</h5>");

//$user_one=29;
?>
<style>
.sitetable{
border-spacing:5px !important;
border-collapse:unset !important;
}
#wid-id---1 table{    
	margin: 0 auto;
    height: 322px;
    border-collapse: separate;
    border-spacing: 11px !important;
    width: 718px;}
</style>
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<?php if(!isset($_GET['noback'])){ ?><b><img id="mvbk" onclick="movere_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b><?php } ?>
		</div>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

		</div>
	</article>
</div>

<!-- row -->
<div class="row siterow">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id---1" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Resolved Exceptions Details </h2>

			</header>
			<div class="row">
		<?php
		$address=array();
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		$resolvedexcepsql='SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e,user up where e.`Customer ID`=up.company_id and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
	}else{
		$resolvedexcepsql='SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e JOIN user up ON  e.`Customer ID`=up.company_id WHERE up.user_id= "'.$user_one.'" and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1';
	}

	if ($stmte = $mysqli->prepare($resolvedexcepsql)) { 

//('SELECT e.ID,e.UBM,e.`Customer ID`,e.`Customer #`,e.`Customer Description`,e.Resolution,e.`Vendor #`,e.`Vendor Name`,e.`Account #`,e.`Check #`,e.`Check Amt`,e.`Check Date`,e.`Due Date`,e.DocID,e.Service,e.`Site #`,e.`Site Name`,e.`Site State`,e.`Error #`,e.`Error Message`,e.`EST Date` from exceptions e,user up where e.`Customer ID`=up.company_id and up.id= "'.$user_one.'" and e.ID="'.$mysqli->real_escape_string($reid).'" LIMIT 1')) {

        $stmte->execute();
        $stmte->store_result();
        if ($stmte->num_rows > 0) {
			$stmte->bind_result($ee_id,$ee_ubm,$ee_custid,$ee_cust,$ee_custdesc,$ee_res,$ee_vendid,$ee_vendname,$ee_acc,$ee_check,$ee_checkamt,$ee_checkdate,$ee_duedate,$ee_docid,$ee_service,$ee_site,$ee_sitename,$ee_sitestate,$ee_error,$ee_errmsg,$ee_estdate);
			$stmte->fetch();
				$ts=$ee_id.rand(650,900);
				?>
					<table>
<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
						<tr><th>ID</th><th> : </th><td><?php echo $ee_id; ?></td><th>Customer ID</th><th> : </th><td><?php echo $ee_custid; ?></td></tr>
						<tr><th>Customer #</th><th> : </th><td><?php echo $ee_cust; ?></td><?php if(1==2){ ?><th>Company</th><th> : </th><td><?php echo $ee_ubm; ?></td> <?php }else{ ?><th>Customer Description</th><th> : </th><td colspan=4><?php echo $ee_custdesc; ?></td> <?php } ?></tr>
<?php } ?>
						<tr><th>Vendor #</th><th> : </th><td><?php echo $ee_vendid; ?></td><th>Vendor Name</th><th> : </th><td><?php echo $ee_vendname; ?></td></tr>
						<tr><th>Account #</th><th> : </th><td><?php echo $ee_acc; ?></td><th>Check #</th><th> : </th><td><?php echo $ee_check; ?></td></tr>
						<tr><th>Check Amt</th><th> : </th><td><?php echo $ee_checkamt; ?></td><th>Check Date</th><th> : </th><td><?php echo $ee_checkdate; ?></td></tr>
						<tr><th>Due Date</th><th> : </th><td><?php echo $ee_duedate; ?></td><th>DocID</th><th> : </th><td><?php echo $ee_docid; ?></td></tr>
						<tr><th>Service</th><th> : </th><td><?php echo $ee_service; ?></td><th>Site #</th><th> : </th><td><?php echo $ee_site; ?></td></tr>
						<tr><th>Site Name</th><th> : </th><td><?php echo $ee_sitename; ?></td><th>Site State</th><th> : </th><td><?php echo $ee_sitestate; ?></td></tr>
						<tr><th>Error #</th><th> : </th><td><?php echo $ee_error; ?></td><th>EST Date</th><th> : </th><td><?php echo $ee_estdate; ?></td></tr>
						<tr><th>Error Message</th><th> : </th><td colspan=4><?php echo $ee_errmsg; ?></td></tr>
						<tr><th>Resolution</th><th> : </th><td colspan=4><?php echo $ee_res; ?></td></tr>
					</table>
				<?php
		}else
			die('Wrong parameters provided');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}//else
		//die('Error Occured! Please try after sometime.');
		?>
			</div>
		</div>
	</article>
</div>