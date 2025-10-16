<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

if(isset($_GET['veid']) and isset($_GET['action']) and $_GET['action'] == "details"){
	if(isset($_GET['veid']) and @trim($_GET['veid']) != "" and $_GET['veid'] > 0)
		$veid=$mysqli->real_escape_string(@trim($_GET['veid']));
	else
		die('Wrong parameters provided');
?>
<style>
.center{text-align:center;}
.center button{margin:5px;}
.ssse input[type=text]{width:90%;float:left;}
.ssse select{width:90%;float:left;}
.ssse textarea{width:98%;float:left;}
.ssse #sss-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
.ssse .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
    bottom: -1px !important;
    left: 29px !important;
}
.ssse .ssscomment{width:90%;float:left;}
.ssse th,.ssse td{border:none !important;padding:3px 10px !important;}
.ssse .showversion-link{float:left;margin-left: 3px;}
.ssse #logsshow{width:100%;
    height: 269px;
    overflow: auto;}
#wid-id--77 .nopadds{padding:0 !important;}
</style>
	<div class="row ssse" id="<?php echo $veid; ?>">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id--77" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>ID: <?php echo $veid; ?></h2>
					<span class="widget-icon" style="float: right;padding-right: 24px;cursor:pointer;" onclick="clearme(<?php echo $veid; ?>,12,true,0)"> <i class="fa fa-times"></i> </span>
				</header>
				<div class="row nopadds">
				<?php
				$disabled=$s3_foldername="";
				$address=array();
				//$todaydate=date('Y-m-d H:i:s');
				if ($vestmt = $mysqli->prepare('Select ID,rate_id,rate_name,state,vendor_id,vendor_name,service_group_id,service_group,capturis_vendor_id,capturis_vendor_name,capturis_rate_name,rateacuity_vendor_id,rateacuity_vendor_name,rateacuity_rate_id,rateacuity_rate_name,importDate From vendor_rates Where ID='.$veid.' LIMIT 1')) {




				$vestmt->execute();
				$vestmt->store_result();
				if ($vestmt->num_rows > 0) {
					$vestmt->bind_result($veid,$rate_id,$rate_name,$state,$vendor_id,$vendor_name,$service_group_id,$service_group,$capturis_vendor_id,$capturis_vendor_name,$capturis_rate_name,$rateacuity_vendor_id,$rateacuity_vendor_name,$rateacuity_rate_id,$rateacuity_rate_name,$importDate);
					$vestmt->fetch();

					$ts=$veid.rand(650,900);
					//if($importDate != "") $importDate=@date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($importDate)));
					if($importDate=="0000-00-00 00:00:00") $importDate="";
						?>
						<table id="cmacctable<?php echo $veid; ?>" class="table table-striped table-bordered table-hover" style="clear: both">
							<tr>
								<th width="14%">Rate ID:</th>
								<td><?php echo $rate_id; ?></td>
								<th width="15%"></th>
								<td></td>
								<th width="14%"></th>
								<td></td>
							</tr>
							<tr>
								<th width="14%">Rate Name:</th>
								<td><input type="text" value="<?php echo $rate_name; ?>" class="veinputautosave" saveme="rate_name"></td>
								<th width="15%">State:</th>
								<td><input type="text" value="<?php echo $state; ?>" class="veinputautosave" saveme="state"></td>
								<th width="14%">vendor Name:</th>
								<td><input type="text" value="<?php echo $vendor_name; ?>" class="veinputautosave" saveme="vendor_name"></td>
							</tr>

							<tr>
								<th width="14%">Service Group:</th>
								<td><input type="text" value="<?php echo $service_group; ?>" class="veinputautosave" saveme="service_group"></td>
								<th width="15%">Capturis vendor Name:</th>
								<td><input type="text" value="<?php echo $capturis_vendor_name; ?>" class="veinputautosave" saveme="capturis_vendor_name"></td>
								<th width="14%">Capturis Rate Name:</th>
								<td><input type="text" value="<?php echo $capturis_rate_name; ?>" class="veinputautosave" saveme="capturis_rate_name"></td>
							</tr>

							<tr>
								<th width="14%">Rateacuity Vendor Name:</th>
								<td><input type="text" value="<?php echo $rateacuity_vendor_name; ?>" class="veinputautosave" saveme="rateacuity_vendor_name"></td>
								<th width="15%">Rateacuity Rate Name:</th>
								<td><input type="text" value="<?php echo $rateacuity_rate_name; ?>" class="veinputautosave" saveme="rateacuity_rate_name"></td>
								<th width="14%">Import Date:</th>
								<td><input type="text" value="<?php echo $importDate; ?>" class="veinputautosave" saveme="importDate"></td>
							</tr>
<?php if(1==2){ ?>
							<tr>
								<th width="14%">Entity Name:</th>
								<td><input type="text" value="<?php echo $entity_name; ?>" class="veinputautosave" saveme="entity_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"entity_name",$disabled); ?></td>
								<th width="15%">Tax ID:</th>
								<td><input type="text" value="<?php echo $federal_tax_id; ?>" class="veinputautosave" saveme="federal_tax_id" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"federal_tax_id",$disabled); ?></td>
								<th width="14%">Site Number:</th>
								<td><input type="text" value="<?php echo $site_number; ?>" class="veinputautosave" saveme="site_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_number",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Utility Service Type:</th>
								<td><input type="text" value="<?php echo $utility_service_type; ?>" class="veinputautosave" saveme="utility_service_type" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"utility_service_type",$disabled); ?></td>
								<th width="15%">Vendor Name:</th>
								<td><input type="text" value="<?php echo $vendor_name; ?>" class="veinputautosave" saveme="vendor_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"vendor_name",$disabled); ?></td>
								<th width="14%">Site Name:</th>
								<?php ++$ts; ?>
								<td><input type="text" value="<?php echo $site_name; ?>" class="veinputautosave" saveme="site_name" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"site_name",$disabled); ?>
								 </td>
							</tr>

							<tr>
								<th width="14%">Date Requested:</th>
								<td><input type="text" value="<?php echo $date_requested; ?>" class="veinputautosave" saveme="date_requested" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"date_requested",$disabled); ?></td>
								<th width="15%">Date Contacted:</th>
								<td><input type="text" value="<?php echo $date_contacted; ?>" class="veinputautosave" saveme="date_contacted" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"date_contacted",$disabled); ?></td>
								<th width="14%">Contacted Method:</th>
								<td><input type="text" value="<?php echo $contacted_method; ?>" class="veinputautosave" saveme="contacted_method" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"contacted_method",$disabled); ?></td>
							</tr>

							<tr>
								<th width="14%">Prev Account Number:</th>
								<td><input type="text" value="<?php echo $previous_account_number; ?>" class="veinputautosave" saveme="previous_account_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"previous_account_number",$disabled); ?></td>
								<th width="15%">Account Number:</th>
								<td><input type="text" value="<?php echo $account_number; ?>" class="veinputautosave" saveme="account_number" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"account_number",$disabled); ?></td>
								<th width="14%">Meter:</th>
								<td><input type="text" value="<?php echo $meter; ?>" class="veinputautosave" saveme="meter" <?php echo $disabled; ?>>
								<?php echo checkversionavailability($mysqli,"startstop_status",$ssid,"meter",$disabled); ?></td>
							</tr>
<?php } ?>
						</table>

			<?php
				}else
					die('Wrong parameters provided');
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			} //else die('Error Occured! Please try after sometime.');
			?>

				</div>
			</div>
		</article>


		<?php
		/*$fieldnamearr=array("site_number"=>"Site Number","location_type"=>"Location Type","site_name"=>"Site Name","region"=>"Region","entity_name"=>"Entity Name","division"=>"Division","federal_tax_id"=>"Tax ID","gl_site"=>"GL Site","site_address1"=>"Site Address1","account_address1"=>"Account Address1","site_address2"=>"Site Address2","account_address2"=>"Account Address2","site_city"=>"Site City","account_city"=>"Account City","site_state"=>"Site State","account_state"=>"Account State","site_zip"=>"Site Zip","account_zip"=>"Account Zip","site_contact_name"=>"Site Contact Name","billing_address1"=>"Billing Address1","site_contact_title"=>"Site Contact Title","billing_address2"=>"Billing Address2","site_contact_telephone"=>"Contact Telephone","billing_city"=>"Billing City","site_contact_fax"=>"Contact Fax","billing_state"=>"Billing State","billing_zip"=>"Billing Zip","leased_location"=>"Leased Location","landlord_name"=>"Landlord Name","lease_start_date"=>"Lease Start Date","landlord_phone"=>"Contact Number","lease_end_date"=>"Lease End Date","landlord_fax"=>"Contact FAX","tenant"=>"Tenant","sale_date"=>"Purchase Date","landlord_email"=>"Contact Email","sublet"=>"Sublet","landlord_address1"=>"Landlord Address1","landlord_address2"=>"Landlord Address2","owned_location"=>"Owned Location","landlord_city"=>"Landlord City","landlord_state"=>"Landlord State","sale_owner"=>"Previous Owner","landlord_zip"=>"Landlord Zip","date_requested"=>"Date Requested","deposit_preference"=>"Deposit Preference","construction"=>"Construction","check_deposit_ok"=>"Check Deposit Ok","meter_change"=>"New Meters Required","credit_card_deposit_ok"=>"Credit Card Deposit Ok","utility_service_type"=>"Utility Service Type","vendor_name"=>"Vendor Name","account_number"=>"Account Name","previous_account_number"=>"Prev Account Number","meter"=>"Meter","special_instructions"=>"Special Instructions","status"=>"Status","date_completed"=>"Date Completed","request_type"=>"Request Type","status_date"=>"Status Date","contacted_method"=>"Contacted Method","confirmation_number"=>"Confirmation Number","deposit"=>"Deposit","deposit_method"=>"Deposit Method","billing_cycle"=>"Billing Cycle","notes"=>"Notes","vendor_phone1"=>"Vendor Phone 1","vendor_phone2"=>"Vendor Phone 2","vendor_email1"=>"Vendor Email 1","vendor_email2"=>"Vendor Email 2","vendor_fax1"=>"Vendor Fax 1","vendor_fax2"=>"Vendor Fax 2");*/
		$ssid=$veid;
		echo showlogs($ssid,'vendor_rates','vedetails','assets/ajax/vendor-rates-pedit.php?action=details&veid='.$veid,$disabled);
		?>
	</div>
<script src="../assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/JavaScript" src="../assets/js/sha512.js"></script>
<script type="text/JavaScript" src="../assets/js/forms.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
<script>
$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		format: 'yyyy/mm/dd HH:mm:ss ',
		//format: 'mm/dd/yyyy ',
            changeMonth: true,
            changeYear: true
	});
//$('.datetimepicker').datetimepicker();
  $('.veinputautosave').blur(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  $('.veselectautosave').change(function() {
	 autosave($(this).attr("saveme"),$(this).val());
  });

  function autosave(savename,saveval){

	var formData = new FormData();
	formData.append('veauto', <?php echo $veid; ?>);
	formData.append('vesavename', savename);
	formData.append('vevalue', saveval);

	$.ajax({
		type: 'post',
		url: 'assets/includes/vendorrates.inc.php',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.error == "")
				{
					//swal("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
					$("a#"+savename+"").removeClass("nodis");
$("#logshow").load("assets/ajax/showlogs.php?pkey=<?php echo $veid; ?>&tname=vendor_rates&load=true&disb=<?php echo @trim($disabled); ?>&tuid=vedetails&tuurl=<?php echo urlencode('assets/ajax/vendor-rates-pedit.php?action=details&veid='.$veid); ?>&ct=<?php echo rand(0,100); ?>");
					parent.$('#vetable').load("assets/ajax/vendor-rates-pedit.php?load=true&ct=<?php mt_rand(2,77); ?>");
				}else if(results.error == 5)
				{
					swal("Mandatory:","ID", "warning");
				}else{
					swal("Error in request.","Please try again later.", "warning");
				}
			}else{
				swal("","Error in request. Please try again later.", "warning");
			}
		}
	});

  }
});



function ssse_save(sid){
	/*if($("#edit-sss-submit"+sid).text()=="Edit"){
		$("#edit-sss-submit"+sid).text("Save");
		$("#cmacctable"+sid+" input[type=text]").prop("disabled", false);
		$(".sss #sss-fileupload").css("display", "block");
		$("#cmacctable"+sid+" input[type=text]").css("border", "1px solid #ccc");
		$("#s3display").css("display", "none");
	}else{
		sssload_details(sid);
		//$("#edit-sss-submit"+sid).text("Edit");
		//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
		//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
	}*/
	//"cmacctable"+sid
}

function ssse_cancel(sid){
		//$("#edit-sss-submit"+sid).text("Edit");
		//$("#cmacctable"+sid+" input[type=text]").prop("disabled", true);
		//$("#cmacctable"+sid+" input[type=text]").css("border", "none");
		//sssload_details(sid);
		clearme(sid);
		$("#ss_datatable_fixed_column").DataTable().page.len(12).draw(false);
}
</script>












<?php }elseif(isset($_GET["load"]) and $_GET["load"]=="true"){
?>
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_column_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_column .isodrp{width:auto !important;}
#datatable_fixed_column tr.dropdown select {
    font-weight: 400 !important;
}
.dtdisplay{
	box-shadow: none!important;
	-webkit-box-shadow: none!important;
	-moz-box-shadow: none!important;
	-webkit-border-radius: 0!important;
	display: block;
	width: 100%;
	height: 32px;
	padding: 6px 12px;
	font-size: 13px;
	line-height: 1.42857143;
	color: #555;
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	border-radius: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.dataTables_processing{top: 73px !important;}
</style>
<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Rate ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Rate Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Vendor Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Service Group" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Capturis Vendor Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Capturis Rate Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Import Date" />
			</th>
		</tr>
		<tr>
			<th data-hide="expand">ID</th>
			<th>Rate ID</th>
			<th>Rate Name</th>
			<th data-hide="phone">Vendor Name</th>
			<th data-hide="phone,tablet">Service Group</th>
			<th data-hide="phone,tablet">Capturis Vendor Name</th>
			<th data-hide="phone,tablet">Capturis Rate Name</th>
			<th>Import Date</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script src="assets/js/datePicker.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();

	// pagefunction
	var pagefunction = function() {

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
			var fixNewLine = {
			    exportOptions: {
			        format: {
			            body: function ( data, column, row ) {
										var htmlstr = data;
										var divstr = document.createElement("div");
										divstr.innerHTML = htmlstr;
										return divstr.innerText;
			            }
			        }
			    }
			};
		/* COLUMN FILTER  */
	    var otable = $('#datatable_fixed_column').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 25,
			"processing": true,
			"serverSide": true,
		"dom": 'Blfrtip',
        "buttons": [
					$.extend( true, {}, fixNewLine, {
							'extend': 'copyHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							'extend': 'excelHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
							'extend': 'csvHtml5'
					} ),
					$.extend( true, {}, fixNewLine, {
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					} ),
					$.extend( true, {}, fixNewLine, {
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					} ),
					{
						'text': 'Columns',
						'extend': 'colvis'
					}
        ],
		/*"columnDefs": [
			{ className: "switchcurrency", "targets": [ 4 ] },
			{ className: "switchcurrency", "targets": [ 5 ] },
			{ className: "switchcurrency", "targets": [ 6 ] },
			{ className: "switchcurrency", "targets": [ 7 ] }
		  ],*/
			"autoWidth" : true,
			"ajax": "assets/ajax/server_processing_vendorrates.php"/*,
			initComplete: function () {
				this.api().columns([0]).every( function () {
					 var column = this;
					 var select = $('<select class="form-control"><option value="">Filter ISO</option></select>')
						  .appendTo( $('#datatable_fixed_column .dropdown .d-1').empty() )
						  .on( 'change', function () {
							   var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
							   );
						  column
							   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
							   .draw();
						  } );
						  var darr = [];
						 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
								if(jQuery.inArray(d, darr) == -1 && d != ""){
									select.append( '<option value="'+d+'">'+d+'</option>' );
									darr.push(d);
								}
						 } );
				} );
			}*/
	    });

/*otable.on( 'draw', function () {
    $('tr td:nth-child(5)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(6)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(7)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(8)').each(function (){
          $(this).addClass('switchcurrency')
    })
});	*/

	   /* var otable = $('#datatable_fixed_column').DataTable({
			 "iDisplayLength": 10,
			//"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	    	//"bFilter": false,
	    	//"bInfo": false,
	    	//"bLengthChange": false,
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
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
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}

	    });*/

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input.form-control[type=text]").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $("#datatable_fixed_column .isodrp").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

				if($(this).parent().index() == 0){updateiso(this.value);}
	    } );
	    $("#datatable_fixed_column .dtdisplay").on( 'keyup change', function () {
			var dateAr = this.value.split('/');
			var srchval=dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
			if(dateAr[2]== "undefined" || dateAr[1]== "undefined" || dateAr[0]== "undefined" || dateAr[2]== "" || dateAr[1]== "" || dateAr[0]== ""){
				srchval="";
			}
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( srchval )
	            .draw();
	    } );
		$("#to").datepicker({
		    defaultDate: "+1w",
		    changeMonth: true,
		    numberOfMonths: 3,
		    prevText: '<i class="fa fa-chevron-left"></i>',
		    nextText: '<i class="fa fa-chevron-right"></i>',
		    onClose: function (selectedDate) {
		        $("#from").datepicker("option", "minDate", selectedDate);
		    }
		});

		function updateiso(iso){
			$.ajax({
				type: 'post',
				url: 'assets/includes/getiso.php?iso='+iso,
				data: {iso:iso},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							$(".iso2 option").each(function() {
								if($(this).val() !=""){
									 if(  $.inArray( $(this).val(), results.node) > -1 )
										   $(this).show();
									 else
										   $(this).hide();
								}
							 });
							$('.iso2').val('').change();
						}//else
							//alert("Error in request. Please try again later.");
					}else{
						//alert("Error in request. Please try again later.");
					}
				}
			});
		}

	    /* END COLUMN FILTER */
<?php if(1==2){ /*if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){*/?>
		$('#selectCompany').empty().append( multilist(2).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(3).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(4).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(5).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(6).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(9).join() );
		$("#selectStatus").multiselect();
<?php }else if(1==3){/*}else{*/ ?>
		$('#selectCompany').empty().append( multilist(1).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(2).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(3).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(4).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(5).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(8).join() );
		$("#selectStatus").multiselect();
<?php } ?>
		$("#selectCompany").on( 'keyup change', function () {multifilter(this,"selectCompany",otable)});
		$("#selectDivision").on( 'keyup change', function () {multifilter(this,"selectDivision",otable)});
		$("#selectCountry").on( 'keyup change', function () {multifilter(this,"selectCountry",otable)});
		$("#selectState").on( 'keyup change', function () {multifilter(this,"selectState",otable)});
		$("#selectCity").on( 'keyup change', function () {multifilter(this,"selectCity",otable)});
		$("#selectStatus").on( 'keyup change', function () {multifilter(this,"selectStatus",otable)});
	};

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
		$('#datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
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

<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadsite(sid) {
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/list-sites.php?editsid=true&sid='+sid);
}

function deletesite(sid,sname) {
	$('#response').html('');
	var r = confirm("Do you want to delete Site: "+sname+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/sitesedit.inc.php',
			data: {sid:sid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#list-sites").html('');
						parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');
					}else
						alert("Error in request. Please try again later.");
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		});
	}
}

function veload_details(veid) {
	//alert($("#ss_datatable_fixed_column").DataTable().page.info().page);
	//clearme(sid,6,false,$("#ss_datatable_fixed_column").DataTable().page.info().page);
	/*$.get('assets/ajax/start-stop-status-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&sid='+sid, function (pagedata){
		parent.$('#ssopdialog').html('');
		$("#ssdetails").append(pagedata);
	});*/
	$("#vedetails").load('assets/ajax/vendor-rates-pedit.php?ct=<?php echo rand(0,100); ?>&action=details&veid='+veid);
	//otable.pageLength(6) ;
	//$("#ss_datatable_fixed_column").DataTable().page.len(6).draw();
}
function loadsssmenu(sid) {
	sssload_details(sid)
	//$("#ss_datatable_fixed_column").DataTable().page.len(6).draw(false);
}
function clearme(ctid,pglength=12,loadit=false,pageno=0){
	if(pageno != 0){
		pageno =  (((pageno*12)/6)+1);
	}

	$( "#"+ctid+"" ).remove();
	$("#ss_datatable_fixed_column").DataTable().page.len(pglength).draw(loadit);
	//$("#ss_datatable_fixed_column").DataTable().row( this ).remove().draw( false );
	//$("#ss_datatable_fixed_column").DataTable().page(3).draw();
}
<?php } ?>
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
