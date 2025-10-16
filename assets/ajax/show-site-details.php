<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");

//if(isset($_GET["cat"]) and @trim($_GET["cat"]) != "" and @trim($_GET["cat"]) != 0)
if(1==1)
{

	$id=2;
	$temp_aidd=array();
	if ($stmt = $mysqli->prepare('SELECT id,site_name,service_address1,service_address2,service_address3,city,state,postal_code,country FROM sites where id='.$id." LIMIT 1")) {
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
			die('Wrong parameters provided');
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
			//$temp_acc .= "Vendor: ".key($vl);
			foreach($vl as $kys=>$vls)
			{
				////$temp_acc .= "Vendor: ".$kys;
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
					{
						$temp_aidd[]=$temp_idd=$vls[$kyy]['a_id'];
						/*$temp_acc .= "<br />Account Number: <a href='javascript:void(0);' onclick='load_sacc(".$temp_idd.")'>".implode("-",$temp_acc_sub)."</a>";
						
						if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
							$temp_acc .= "<button onclick='editacc(".$temp_idd.")' title='View/Edit Account Details' class='btn btn-xs btn-default'><i class='fa fa-pencil'></i></button><button onclick='deleteacc(".$temp_idd.")' title='Delete Account' class='btn btn-xs btn-default'><i class='fa fa-times'></i></button>";
						}*/
					}//else
						//$temp_acc .= "<br />Account Number: N/A";
				}
				//$temp_acc .= "<br />";
				$temp_acc=$a_site_name;
			}		
		}
	
	}




	if(count($temp_aidd))
	{?>
	<section id="sitesaccount">
	<?php
		for($i=0;$i<count($temp_aidd);$i++)
		{
		?><div id="siteaccount-details<?php echo $i;?>">
<?php
	$aid=$temp_aidd[$i];

$temp_invoice=$__mtid=$temp_acc_sub=array();
$temp_acc="";
//New Query
	if ($stmt = $mysqli->prepare('SELECT a.meter_number,vendor_id,v.vendor_name,a.account_number1,a.account_number2,a.account_number3 FROM accounts a,vendor v where a.id='.$aid.' and a.vendor_id=vendor_id and a.meter_number != "" and a.meter_number != 0')) { 

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
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}

if(count($__mtid)==0)
	die("No data to show");

//New Query Ends
	if ($stmt = $mysqli->prepare('SELECT sg.service_group,u.id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure,u.cost FROM `usage` u,service_group sg where u.meter_number IN('.implode(",",$__mtid).') and u.service_group_id=sg.service_group_id and sg.service_group_id != "" and sg.service_group_id != 0 order by sg.service_group_id,u.interval_end, u.meter_number desc')) { 

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
<style>
.usage{
  background-color: #fff;
  margin: 20px 0;
  padding-top: 29px;
}
</style>
<?php
//New Codes
$d_i=0;
foreach($temp_invoice as $_ky=>$_vl)
{
	++$d_i;
	$ts=rand(650,900);
?>
	<div class="row usage">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<h3><?php echo $_ky; ?></h3>
				Vendor: 
				<?php echo $_v_name; ?>				
				<br />
				Account #: <?php echo $temp_acc; ?><br />
				Status: Inactive
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
						
						<table id="datatable_fixed_column_acc<?php echo $d_i; ?>" class="table table-striped table-bordered table-hover" width="100%">
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
									<th>Meter ID</th>
									<th>Status</th>
									<th>Year</th>
									<th>Month</th>
									<th>Cost</th>
									<th>Usage</th>
									<th>UOM</th>
									<th>Start-Stop Date</th>
									<th>Invoice</th>
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
				$_dis_interval[]=$_vv["sinterval"]." - ".$_vv["einterval"];
				$_dis_cost[]=$_vv["cost"];
				$_dis_measure=$_vv["measure"];
				$_dis_invoice[]="<a href='javascript:void(0);' onclick='load__invoice(".$_vv['_u_id'].")'>Link</a>";
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
							<td><?php echo @implode("<br />",$_dis_interval); ?></td>
							<td><?php echo @implode("<br />",$_dis_invoice); ?></td>
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
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

<?php
//New Codes 
$d_i=0;
foreach($temp_invoice as $_ky=>$_vl)
{
	++$d_i;
?>

		/* COLUMN FILTER  */
	    var otable<?php echo $d_i; ?> = $('#datatable_fixed_column_acc<?php echo $d_i; ?>').DataTable({
			"paging": true,
			"iDisplayLength": 12,
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'CT>r>"+
					"t"+
					"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
	        "oTableTools": {
	        	 "aButtons": [
	             "copy",
	             "csv",
	             "xls",
	                {
	                    "sExtends": "pdf",
	                    "sTitle": "Vervantis_PDF",
	                    "sPdfMessage": "Vervantis PDF Export",
	                    "sPdfSize": "letter"
	                },
	             	{
                    	"sExtends": "print",
                    	"sMessage": "Generated by Vervantis <i>(press Esc to close)</i>"
                	}
	             ],
	            "sSwfPath": "assets/js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
	        },
			"autoWidth" : true,
			"bDestroy": true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column_acc<?php echo $d_i; ?>'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}		
		
	    });



<?php } ?>
	};
	// load related plugins
	
	loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
	
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
</script>
		</div><?php	
		}
	?></section>
<?php }
}else{
	die("Error Occured! Please try after sometime.");
}
?>