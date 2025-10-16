<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
$cname=$_SESSION['company_id'];



//Show Logs

if(isset($_GET["tname"]) and @trim($_GET["tname"]) != "" and isset($_GET["pkey"]) and @trim($_GET["pkey"]) != "" and @trim($_GET["pkey"]) != 0 and isset($_GET["tuid"]) and @trim($_GET["tuid"]) != "" and isset($_GET["tuurl"]) and @trim($_GET["tuurl"]) != "" and isset($_GET["disb"]))
{
	$primarykey=$mysqli->real_escape_string(@trim($_GET["pkey"]));
	$tablename=$mysqli->real_escape_string(@trim($_GET["tname"]));
	$tid=@trim($_GET["tuid"]);
	$turl=@urldecode(@trim($_GET["tuurl"]));
	$disabled=@trim($_GET["disb"]);

	$logid="";
	if(isset($_GET["logid"])){$logid=$_GET["logid"];}

	$fieldnamearr=array();

//function showlogs($mysqli,$primarykey,$tablename,$fieldnamearr=array())
//{
	if(empty($primarykey) or empty($tablename)) return "";

	if($tablename=="startstop_status"){
		$fieldnamearr=array("site_number"=>"Site Number","location_type"=>"Location Type","site_name"=>"Site Name","region"=>"Region","entity_name"=>"Entity Name","division"=>"Division","federal_tax_id"=>"Tax ID","gl_site"=>"GL Site","site_address1"=>"Site Address1","account_address1"=>"Account Address1","site_address2"=>"Site Address2","account_address2"=>"Account Address2","site_city"=>"Site City","account_city"=>"Account City","site_state"=>"Site State","account_state"=>"Account State","site_zip"=>"Site Zip","account_zip"=>"Account Zip","site_contact_name"=>"Site Contact Name","billing_address1"=>"Billing Address1","site_contact_title"=>"Site Contact Title","billing_address2"=>"Billing Address2","site_contact_telephone"=>"Contact Telephone","billing_city"=>"Billing City","site_contact_fax"=>"Contact Fax","billing_state"=>"Billing State","billing_zip"=>"Billing Zip","leased_location"=>"Leased Location","landlord_name"=>"Landlord Name","lease_start_date"=>"Lease Start Date","landlord_phone"=>"Contact Number","lease_end_date"=>"Lease End Date","landlord_fax"=>"Contact FAX","tenant"=>"Tenant","sale_date"=>"Purchase Date","landlord_email"=>"Contact Email","sublet"=>"Sublet","landlord_address1"=>"Landlord Address1","landlord_address2"=>"Landlord Address2","owned_location"=>"Owned Location","landlord_city"=>"Landlord City","landlord_state"=>"Landlord State","sale_owner"=>"Previous Owner","landlord_zip"=>"Landlord Zip","date_requested"=>"Date Requested","deposit_preference"=>"Deposit Preference","construction"=>"Construction","check_deposit_ok"=>"Check Deposit Ok","meter_change"=>"New Meters Required","credit_card_deposit_ok"=>"Credit Card Deposit Ok","utility_service_type"=>"Utility Service Type","vendor_name"=>"Vendor Name","account_number"=>"Account Name","previous_account_number"=>"Prev Account Number","meter"=>"Meter","special_instructions"=>"Special Instructions","status"=>"Status","date_completed"=>"Date Completed","request_type"=>"Request Type","status_date"=>"Status Date","contacted_method"=>"Contacted Method","confirmation_number"=>"Confirmation Number","deposit"=>"Deposit","deposit_method"=>"Deposit Method","billing_cycle"=>"Billing Cycle","notes"=>"Notes","vendor_phone1"=>"Vendor Phone 1","vendor_phone2"=>"Vendor Phone 2","vendor_email1"=>"Vendor Email 1","vendor_email2"=>"Vendor Email 2","vendor_fax1"=>"Vendor Fax 1","vendor_fax2"=>"Vendor Fax 2");
	}elseif($tablename=="startstop_status"){
		$fieldnamearr=array("ClientID"=>"CompanyID","VendorID"=>"Supplier","Status"=>"Status","Version"=>"Version","Start Date"=>"Start Date","End Date"=>"End Date","Reviewed By"=>"Reviewed By","Notes"=>"Notes");
	}elseif($tablename=="contractsss"){
		$fieldnamearr=array("ContractID"=>"ContractID","Country"=>"Country","State"=>"State","ClientID"=>"Client","VendorID"=>"Vendor","SupplierID"=>"Supplier","AdvisorID"=>"AdvisorID","Notes"=>"Notes");
	}



	$error="Error occured";
	$sub_query=$new_value=$finfo=$editedvalarr=array();
	$z=$t=$v=$editedvalue=$zz="";
	$l=1;

	$tsss=mt_rand(2,99);

	$old_value_arr=$editedvalarr=array();

	if ($stmt = $mysqli->query('SELECT distinct a.modified,u.usergroups_id,u.firstname,u.lastname,a.edited_value,a.activity,a.status,a.id,a.user_id,c.company_name FROM `audit_log` a,user u,company c where a.table_name="'.$tablename.'" and a.table_row_id="'.$primarykey.'" and a.user_id=u.user_id and u.company_id=c.company_id ORDER BY a.modified DESC LIMIT 40')) {
        if ($stmt->num_rows > 0) {

			$kk="";
			while($row=$stmt->fetch_row()) {
				$editedvalue=$row[4];
				if($editedvalue=="")
				{
					echo "";
					exit();
				}

				$utype="";
				if($row[1]==1) $utype="Vervantis Admin";
				if($row[1]==2) $utype="Vervantis Employee";
				if($row[1]==3) $utype="Client";
				if($row[1]==4) $utype="Client Admin";

				$fullname=$row[2]." ".$row[3]." (".$row[9].")";

				$editedvalarr= @unserialize(base64_decode($editedvalue));
				if(is_array($editedvalarr)) $z=count($editedvalarr); else $z=0;
//print_r($editedvalarr);
//echo "<br>";
//print_r($fieldnamearr);die();

				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"])){
						$fldname=$editedvalarr[$i]["title"];
						if(count($fieldnamearr) and in_array($fldname,$fieldnamearr)){
							$fldname=$fieldnamearr[$fldname];
						}


						$edv=$editedvalarr[$i]["old"];
						$zz = $zz."<tr><td class='nodis'>".$row[0]."</td><td>".date("M d,Y h:i:s A", strtotime($row[0]))."</td><td>".$fldname."</td><td>".@stripslashes($edv)."</td>";
						if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ $zz=$zz."<td>".$row[5]."</td>";}
						$zz=$zz."<td>".$fullname."</td>";

						if(@trim($disabled) == ""){ $zz=$zz."<td><button onclick=\"rollback_audit_log_new(".$row[7].",'rollback','".$tid."','".@urldecode($turl)."')\" title=\"Roll Back\" class=\"btn btn-xs btn-default\"><i class=\"fa fa-reply\"></i></button></td>";
						}

						$zz=$zz."</tr>";

						/*if($row[2]=="UPDATE"){
							if($row[3] != 1){
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'rollback\''.$kk.')" title="Roll Back" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></button>';
							}
							if($row[3] != 2){
								$zz = $zz.'<button onclick="rollback_audit_log('.$row[4].',\'forward\''.$kk.')" title="forward" class="btn btn-xs btn-default"><i class="fa fa-share"></i></button>';
							}
						}
						$zz = $zz."</td></tr>";*/
					}
					if(isset($editedvalarr[$i]["file"])){
						//$editedval .= "<b>File:</b> ".$editedvalarr[$i]["file"]."<br />"."<b>Old Value:</b> ".$editedvalarr[$i]["old"]."<br />"."<b>New Value:</b> ".$editedvalarr[$i]["new"];
						//$l++;
					}
				}
			}
		}
	}else{
		echo "";
		exit();
	}
//echo $zz;
	if($zz != ""){
	?>
	<style>
	#logsshow<?php echo $logid; ?>_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#logsshow<?php echo $logid; ?>_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#logsshow<?php echo $logid; ?>{border-bottom: 1px solid #ccc !important;width:100% !important;}
	#logsshow<?php echo $logid; ?> .sssdrp{width:auto !important;}
	#logsshow<?php echo $logid; ?> .sssdrp {
		font-weight: 400 !important;
	}
	#logsshow<?php echo $logid; ?> .nodis{display:none !important;}
	.nopadd{padding:0 !important;}
	</style>
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<link href="/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
				<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
				<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--44<?php echo $tsss; ?>" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Logs</h2>
					</header>
					<div class="widget-body nopadd">
				<table class='table table-bordered table-striped' id='logsshow<?php echo $logid; ?>'>
					<thead>
						<tr>
							<th class="nodis"></th>
							<th class="hasinput">
								<input type="text" class="form-control" placeholder="Filter Date" />
							</th>
							<th class="hasinput">
								<input type="text" class="form-control" placeholder="Filter Field Name" id="slfieldname" />
							</th>
							<th class="hasinput">
								<input type="text" class="form-control" placeholder="Filter Previous Value" />
							</th>
<?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?>
							<th class="hasinput">
								<input type="text" class="form-control" placeholder="Filter Action" />
							</th>
<?php } ?>
							<th class="hasinput">
								<input type="text" class="form-control" placeholder="Filter By User" />
							</th>
<?php if($disabled == ""){ ?>
							<th class="hasinput"></th>
<?php } ?>
						</tr>
						<tr><th class="nodis">DefaultDate</th><th>Date</th><th>Field Name</th><th>Previous Value</th><?php if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){ ?><th>Action</th><?php } ?><th>By User</th><?php if($disabled == ""){ ?><th>Roll Back</th><?php } ?></tr>
					</thead>
					<tbody><?php echo $zz; ?></tbody>
				</table>


					</div>
				</div>
			</article>

	<script type="text/javascript">

		pageSetUp();


		// pagefunction
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

			/* COLUMN FILTER  */
				var otable = $("#logsshow<?php echo $logid; ?>").DataTable( {
					"lengthMenu": [[1, 2, 3, 4, 5, 6, 12, 25, -1], [1, 2, 3, 4, 5, 6, 12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,
					"dom": 'Blfrtip',
					"order": [[ 0, "desc" ]],
					//"stateSave": true,
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
						}/*,
						{
							'text': 'Columns',
							'extend': 'colvis'
						}*/
					],
					"autoWidth" : true
				});
				otable.columns( [0] ).visible( false );

			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');


			$("#logsshow<?php echo $logid; ?> .sssdrp").on( 'keyup change', function () {
				otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

			} );
			$("#logsshow<?php echo $logid; ?> thead th input[type=text]").on( 'keyup change', function () {

				otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

			});
		};

		function filterColumn (slfieldname) {
			$("#logsshow<?php echo $logid; ?>").DataTable().column( 2 ).search(slfieldname,
				true,
				true
			).draw();

			$('html, body').animate({
				scrollTop: $("#wid-id--44<?php echo $tsss; ?>").offset().top
			}, 2000);
		}

		function multifilter(nthis,fieldname,otable)
		{
				var selectedoptions = [];
				$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
					selectedoptions.push($(this).val());
				});
				otable
				 .column( $(nthis).parent().index()+':visible' )
				 .search("^" + selectedoptions.join("|") + "$", true, false, true)
				 .draw();
		}

		function multilist(indexno)
		{
			var items=[], options=[];
			$('#logsshow<?php echo $logid; ?> tbody tr td:nth-child('+indexno+')').each( function(){
			   items.push( $(this).text() );
			});
			var items = $.unique( items );
			$.each( items, function(i, item){
				options.push('<option value="' + item + '">' + item + '</option>');
			})
			return options;
		}

		loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);


	</script>
<?php
	}
}
?>
