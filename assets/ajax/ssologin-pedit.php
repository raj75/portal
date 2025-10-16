<?php require_once("inc/init.php");
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");

//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 5)
	//die("Restricted Access");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];
if(isset($_GET["cid"]) and !empty(@trim($_GET["cid"])) and !isset($_GET["ssotype"])){
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cid=$_GET["cid"];
	else $cid=$cname;
	$company_id=$company_name=$idp_entity_id_okta=$idp_sso_url_okta=$idp_slo_url_okta=$idp_x509cert_okta=$idp_entity_id_azure=$idp_sso_url_azure=$idp_slo_url_azure=$idp_x509cert_azure=$idp_azure_tenant_id="";
	$idp_sso_choice=0;
		if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2)
			$sql='SELECT company_id,company_name,idp_entity_id_okta,idp_sso_url_okta,idp_slo_url_okta,idp_x509cert_okta,idp_entity_id_azure,idp_sso_url_azure,idp_slo_url_azure,idp_x509cert_azure,azure_tenant_id,sso_choice FROM company WHERE company_id="'.$cid.'" LIMIT 1';

	//SELECT u.id,u.email,u.usergroups_id,u.gender,u.status FROM user u';

		elseif($_SESSION["group_id"] == 5)
			$sql='SELECT company_id,company_name,idp_entity_id_okta,idp_sso_url_okta,idp_slo_url_okta,idp_x509cert_okta,idp_entity_id_azure,idp_sso_url_azure,idp_slo_url_azure,idp_x509cert_azure,azure_tenant_id,sso_choice FROM company where company_id="'.$cid.'" LIMIT 1';

	//SELECT id,email,usergroups_id,gender,status FROM user where (usergroups_id = 5 OR usergroups_id = 3) and company_id=(SELECT usp.company_id FROM user usp WHERE usp.id= "'.$user_one.'")';

		else
			die("Error Occured. Please try after sometime!");

		if ($stmt = $mysqli->prepare($sql)) {
	        $stmt->execute();
	        $stmt->store_result();
	        if ($stmt->num_rows > 0) {
				$stmt->bind_result($company_id,$company_name,$idp_entity_id_okta,$idp_sso_url_okta,$idp_slo_url_okta,$idp_x509cert_okta,$idp_entity_id_azure,$idp_sso_url_azure,$idp_slo_url_azure,$idp_x509cert_azure,$idp_azure_tenant_id,$idp_sso_choice);
				$stmt->fetch();


			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
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
	.w25{text-overflow: ellipsis !important;
    overflow: hidden; }
	.inlineset{float: left;
    margin-right: 10px !important;
    margin-top: 2px !important;}
	</style>
	
  		<div class="row">
  			<form id="noform" class="smart-form" novalidate="novalidate">
  				<fieldset>
  					<section class="col col-6">
							<div class="inline-group inlineset"><b>SSO Choice: </b></div>
							<div class="inline-group">
								<label class="radio">
									<input type="radio" name="ssotype" class="ssotype" id="ssotype0" <?php if($idp_sso_choice==0){echo 'checked=""';} ?> value="0">
									<i></i>No</label>
								<label class="radio">
									<input type="radio" name="ssotype" class="ssotype" id="ssotype1" <?php if($idp_sso_choice==1){echo 'checked=""';} ?> value="1">
									<i></i>Azure</label>
								<label class="radio">
									<input type="radio" name="ssotype" class="ssotype" id="ssotype2" <?php if($idp_sso_choice==2){echo 'checked=""';} ?> value="2">
									<i></i>Okta</label>
							</div>
  					</section>
  					<section class="col col-6">
  					</section>
  				</fieldset>
  			</form>
  		</div>	
	
	
	
				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>SSO List </h2>

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
							<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%"  style="table-layout: fixed; width: 100%">
								<thead>
									<tr>
										<th>SSO Type</th>
										<th data-hide="phone">Idp entity id </th>
										<th data-hide="phone" class="w25">Idp sso url </th>
										<th data-hide="phone" class="w25">Idp slo url </th>
										<th data-hide="phone" class="w25">Idp c509cert </th>
										<th data-hide="phone" class="w25">Tenant ID </th>
	<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
										<th data-hide="phone,tablet">Action</th>
	<?php } ?>
									</tr>
								</thead>
								<tbody>

				<?php /*if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){*/?>
				<tr>
					<td>Okta</td>
					<td class="w25"><?php echo $idp_entity_id_okta; ?></td>
					<td class="w25"><?php echo $idp_sso_url_okta; ?></td>
					<td class="w25"><?php echo $idp_slo_url_okta; ?></td>
					<td class="w25"><?php echo $idp_x509cert_okta; ?></td>
					<td class="w25"><?php  ?></td>
	<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
					<td>&nbsp;&nbsp;<button onclick="loadssoedit(<?php echo $company_id; ?>,'okta')" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button></td>
	<?php } ?>
				</tr>
				<?php /*}*/ ?>
				<tr>
					<td>Azure</td>
					<td class="w25"><?php echo $idp_entity_id_azure; ?></td>
					<td class="w25"><?php echo $idp_sso_url_azure; ?></td>
					<td class="w25"><?php echo $idp_slo_url_azure; ?></td>
					<td class="w25"><?php echo $idp_x509cert_azure; ?></td>
					<td class="w25"><?php echo $idp_azure_tenant_id; ?></td>
	<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){?>
					<td>&nbsp;&nbsp;<button onclick="loadssoedit(<?php echo $company_id; ?>,'azure')" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button></td>
	<?php } ?>
				</tr>

								</tbody>
							</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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
				var otable = $("#datatable_fixed_column").DataTable( {
					"pageLength": 12,
					"paging": true,
					"dom": 'Blfrtip',
					"autoWidth" : true
				});

		    // custom toolbar
		    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

		        otable
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

	function loadssoedit(cid,ssotype) {
		parent.$('#ssoresponse').html('');
	  parent.$('#ssoresponse').load('assets/ajax/ssologin-pedit.php?ssotype='+ssotype+'&cid='+cid);
	}
	<?php if(!$_SESSION["group_id"] == 1 and !$_SESSION["group_id"] == 2){?>
	function deleteuser(userid,uemail) {
		$('#presponse').html('');
		var r = confirm("Are you sure want to delete "+uemail+"!");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: 'assets/includes/profileedit.inc.php',
				data: {usr:userid,action:'delete'},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							//alert("Success");
							parent.$("#dtable").html('');
							parent.$('#dtable').load('assets/ajax/user-pedit.php');
						}else
							alert("Error in request. Please try again later.");
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}
	<?php } ?>
	$(".ssotype").change(function () {//alert("hi");
        if ($("#ssotype1").is(":checked")) {
            var ssoval=1;
        }
        else if ($("#ssotype2").is(":checked")) {
            var ssoval=2;
        }
        else 
            var ssoval=0;

			var formData = new FormData();

			formData.append('cid', '<?php echo $cid; ?>');
			formData.append('ssochoice', ssoval);

			$.ajax({
				type: 'post',
				url: 'assets/includes/ssologinedit.inc.php',
				data: formData,
				processData: false,
				contentType: false,
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							$.smallBox({
								title : "Changes Saved!",
								content:"",
								color : "#296191",
								timeout: 2000
							}, function() {
								//alert("Success");
								//$("#add-dialog-message").dialog("close");
								if ($('#add-dialog-message').dialog('isOpen') === true) {
									$("#add-dialog-message").dialog("close");
								}

							});
						}else{
							var tmperror="Please try after sometime...";
							$.smallBox({
								title : "Error in request.",
								content : "<i class='fa fa-clock-o'></i> <i></i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
						}
					}else{
						$.smallBox({
							title : "Error in request.",
							content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
							color : "#FFA07A",
							iconSmall : "fa fa-warning shake animated",
							timeout : 4000
						});
					}
				}
			  });
			//return false;		
    });
	</script>
<?php
}elseif(isset($_GET["ssotype"]) and !empty(@trim($_GET["ssotype"]))){
	$ssotype=@trim($_GET["ssotype"]);
	if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2) $cid=$_GET["cid"];
	else $cid=$cname;

	$idp_entity_id_okta=$idp_sso_url_okta=$idp_slo_url_okta=$idp_x509cert_okta=$idp_entity_id_azure=$idp_sso_url_azure=$idp_slo_url_azure=$idp_x509cert_azure=$idp_azure_tenant_id="";
	$sql='SELECT company_id,company_name,idp_entity_id_okta,idp_sso_url_okta,idp_slo_url_okta,idp_x509cert_okta,idp_entity_id_azure,idp_sso_url_azure,idp_slo_url_azure,idp_x509cert_azure,azure_tenant_id FROM company where company_id="'.$cid.'" LIMIT 1';
	if ($stmt = $mysqli->prepare($sql)) {
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($company_id,$company_name,$idp_entity_id_okta,$idp_sso_url_okta,$idp_slo_url_okta,$idp_x509cert_okta,$idp_entity_id_azure,$idp_sso_url_azure,$idp_slo_url_azure,$idp_x509cert_azure,$idp_azure_tenant_id);
					$stmt->fetch();
				}
	}
?>
<style>
.uadd footer{text-align:center;}
.uadd footer button{float:none !important;}
#ssoadd-checkout-form{width:100% !important; }
.fontbold{font-weight: bold;}
</style>
		<div id="add-dialog-message" title="Edit SSO: <?php echo @ucfirst($ssotype); ?>">
						<form id="ssoadd-checkout-form" class="smart-form uadd" novalidate="novalidate" method="post" enctype="multipart/form-data" onsubmit="">
							<fieldset>
								<section class="col col-3 fontbold">Step 1
									<label class="input"> <i class="icon-append fa fa-download"></i>
										<input type="button" name="downloadxml" id="downloadxml" placeholder="Metadata XML" value="Download Metadata XML">
									</label>
								</section>
								<section class="col col-3 fontbold">Step 2
									<label class="input"> <i class="icon-append fa fa-upload"></i>
										<input type="button" name="uploadxml" id="uploadxml" onclick="$('#imgupload').trigger('click');" placeholder="Federation Metadata XML" value="Federation Metadata XML">
										<input type="file" id="imgupload" style="display:none"/>
									</label>
								</section>
								<section class="col col-4 fontbold">Step 3
									<label class="input"> <i class="icon-append fa fa-upload"></i>
										<input type="text" name="tenantid" id="tenantid" placeholder="Tenant ID" value="">
									</label>
								</section>
								<section class="col col-2 fontbold">Step 4 (Final)
									<label class="input">
										<input type="submit" name="savexml" id="savexml" class="btn btn-primary" placeholder="save XML" value="Save">
									</label>
								</section>
							</fieldset>
							<fieldset>
								<section>Idp entity id
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" name="idpentityid" id="idpentityid" placeholder="Idp entity id"><?php if($ssotype=="okta"){echo $idp_entity_id_okta; }elseif($ssotype=="azure"){echo $idp_entity_id_azure; } ?></textarea>
									</label>
								</section>
								<section>Idp sso url
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" name="idpssourl" id="idpssourl" placeholder="Idp sso url"><?php if($ssotype=="okta"){echo $idp_sso_url_okta; }elseif($ssotype=="azure"){echo $idp_sso_url_azure; } ?></textarea>
									</label>
								</section>
								<section>Idp slo url
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" name="idpslourl" id="idpslourl" placeholder="Idp slo url"><?php if($ssotype=="okta"){echo $idp_slo_url_okta; }elseif($ssotype=="azure"){echo $idp_slo_url_azure; } ?></textarea>
									</label>
								</section>
								<section>Idp c509 cert
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" name="idpc509cert" id="idpc509cert" placeholder="Idp c509 cert"><?php if($ssotype=="okta"){echo $idp_x509cert_okta; }elseif($ssotype=="azure"){echo $idp_x509cert_azure; } ?></textarea>
									</label>
										<input type="hidden" name="cid" id="cid" placeholder="Title" value="<?php echo $cid; ?>">
										<input type="hidden" name="ssotype" id="ssotype" placeholder="Title" value="<?php echo $ssotype; ?>">
								</section>
							</fieldset>
						</form>
	</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var filedata="";
var entityid = "";
document.getElementById('imgupload').removeEventListener('change', handleFileSelect);
document.getElementById('imgupload').addEventListener('change', handleFileSelect, false);
function handleFileSelect(event) {
  const reader = new FileReader()
  reader.onload = handleFileLoad;
  reader.readAsText(event.target.files[0]);

	//entityid = filedata.match(/entityID=\"([^"]+)\"/gm);
	/*document.getElementById('idpentityid').textContent = filedata.match(/entityID=\"([^"]+)\"/gm);
	document.getElementById('idpssourl').textContent = filedata.match(/SingleSignOnService\s+Binding=\"[^"]+\"\s+Location=\"([^"]+)\"/gm);
	document.getElementById('idpslourl').textContent = filedata.match(/SingleLogoutService\s+Binding=\"[^"]+\"\s+Location=\"([^"]+)\"/gm);
	document.getElementById('idpc509cert').textContent = filedata.match(/IDPSSODescriptor[^>]+>.*?<X509Certificate>([^<]+)</gm);*/
}

function handleFileLoad(event) {
  //console.log(event);
  filedata = event.target.result;
	//alert(filedata);
		let pattern1=/entityID=\"([^"]+)\"/gm;
		let pattern2=/SingleSignOnService\s+Binding=\"[^"]+\"\s+Location=\"([^"]+)\"/gm;
		let pattern3=/SingleLogoutService\s+Binding=\"[^"]+\"\s+Location=\"([^"]+)\"/gm;
		let pattern4=/IDPSSODescriptor[^>]+>.*?<X509Certificate>([^<]+)</gm;
		//alert(filedata.match(pattern1));
		document.getElementById('idpentityid').textContent =getMatches(filedata, pattern1, 1);
		document.getElementById('idpssourl').textContent =getMatches(filedata, pattern2, 1);
		document.getElementById('idpslourl').textContent =getMatches(filedata, pattern3, 1);
		document.getElementById('idpc509cert').textContent =getMatches(filedata, pattern4, 1);
}

function getMatches(string, regex, index) {
  index || (index = 1); // default to the first capturing group
  var matches = [];
  var match;
	var result="";
  while (match = regex.exec(string)) {
    //matches.push(match[index]);
		result=match[index];
		break;
  }
  //return JSON.stringify(matches);
	return result;
}

$(function() {
$(document).ready(function() {

	/*jQuery.validator.addMethod("atleastonenumber", function(value, element) {
		return this.optional(element) || /\d{1}/.test(value);
	}, "Password must include at least one number!");

	jQuery.validator.addMethod("atleastoneletter", function(value, element) {
		return this.optional(element) || /[a-zA-Z]{1}/.test(value);
	}, "Password must include at least one letter!");

	jQuery.validator.addMethod("atleastonecapletter", function(value, element) {
		return this.optional(element) || /[A-Z]{1}/.test(value);
	}, "Password must include at least one CAPS!");

	jQuery.validator.addMethod("atleastonesymbol", function(value, element) {
		//return this.optional(element) || /[ !"#$%&'()*+,-.\/:;<=>?@[\]^_`{|}~]{1}/.test(value);
		return this.optional(element) || /\W{1}/.test(value);
	}, "Password must include at least one symbol!");

	jQuery.validator.addMethod("nospace", function(value, element) {
		return this.optional(element) || /^\S{1,}/.test(value);
	}, "Password must not contain spaces!");

	//$('#add-dialog-message').dialog('open'); .on('changeDate', function(e) {
       // $('#add-profileForm').formValidation('revalidateField', 'addbirthdate');
    //})
	$("#addgeneratepwd").click(function(){
		var newpwd=generatePassword(8);
		$("#addpassword").val(newpwd);
		$("#addpasswordConfirm").val(newpwd);
	});*/

	$('#downloadxml').on('click', function() {
      //alert( this.value );
			//$('#listsso').load('https://develop1.vervantis.com/saml/metadata.php/<?php echo $cid; ?>');
			downloadFile('https://develop1.vervantis.com/saml/metadata.php/<?php echo $cid; ?>', 'metadata.xml')
  });

	$('#savexml').on('click', function() {

  });
});



});
function downloadFile(url, fileName) {
  fetch(url, { method: 'get', mode: 'no-cors', referrerPolicy: 'no-referrer' })
    .then(res => res.blob())
    .then(res => {
      const aElement = document.createElement('a');
      aElement.setAttribute('download', fileName);
      const href = URL.createObjectURL(res);
      aElement.href = href;
      aElement.setAttribute('target', '_blank');
      aElement.click();
      URL.revokeObjectURL(href);
    });
};

function downloadFileold(uri, name)
{
    var link = document.createElement("a");
    // If you don't know the name or want to use
    // the webserver default set name = ''
    link.setAttribute('download', name);
		link.setAttribute('target', '_blank');
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    link.remove();
}
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
	 * TO LOAD A SCRIPT:
	 * var pagefunction = function (){
	 *  loadScript(".../plugin.js", run_after_loaded);
	 * }
	 *
	 * OR
	 *
	 * loadScript(".../plugin.js", run_after_loaded);
	 */

	// PAGE RELATED SCRIPTS

	// pagefunction

	var pagefunction = function() {
		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title : function(title) {
				if (!this.options.title) {
					title.html("&#160;");
				} else {
					title.html(this.options.title);
				}
			}
		}));

		$("#add-dialog-message").dialog({
			autoOpen : true,
			modal : true,
			width: "60%",
			title : "<div class='widget-header'><h4><i class='icon-ok'></i>Edit SSO: <?php echo @ucfirst($ssotype); ?></h4></div>",
			close: function(event, ui) {
			        $(this).empty().dialog('destroy');
			    }
		});

		$('#add-profile-cancel').click(function() {
			$("#add-dialog-message").dialog("close");
		});



		var $checkoutForm = $('#ssoadd-checkout-form').validate({
		// Rules for form validation
	/*		rules : {
				addfname : {
					required : true
				},
				addlname : {
					required : true
				},
				addemail : {
					required : true,
					email : true
				},
				addpassword : {
					required : true,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true
				},
				addpasswordConfirm : {
					required : true,
					minlength : 8,
					maxlength : 20,
					atleastonenumber : true,
					atleastoneletter : true,
					atleastonecapletter : true,
					atleastonesymbol : true,
					equalTo : '#addpassword'
				}
			},

			// Messages for form validation
			messages : {
				addfname : {
					required : 'Please enter your first name'
				},
				addlname : {
					required : 'Please enter your last name'
				},
				addemail : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				addpassword : {
					required : 'Please enter your password'
				},
				addpasswordConfirm : {
					required : 'Please enter your password one more time',
					equalTo : 'Please enter the same password as confirm password'
				}
},*/
			// Ajax form submition
			submitHandler : function(form) {
				var formData = new FormData();

				formData.append('idpentityid', $("#idpentityid").val());
				formData.append('idpssourl', $("#idpssourl").val());
				formData.append('idpslourl', $("#idpslourl").val());
				formData.append('idpc509cert', $("#idpc509cert").val());
				formData.append('cid', $("#cid").val());
				formData.append('ssotype', $("#ssotype").val());
				formData.append('tenantid', $("#tenantid").val());

				$.ajax({
					type: 'post',
					url: 'assets/includes/ssologinedit.inc.php',
					data: formData,
					processData: false,
					contentType: false,
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
								$.smallBox({
									title : "Changes Saved!",
									content:"",
									color : "#296191",
									timeout: 2000
								}, function() {
									//alert("Success");
									//$("#add-dialog-message").dialog("close");
									if ($('#add-dialog-message').dialog('isOpen') === true) {
									    $("#add-dialog-message").dialog("close");
									}
									parent.$("#listsso").html('');
									parent.$('#listsso').load('assets/ajax/ssologin-pedit.php?cid=<?php echo $cid; ?>&ct=<?php echo time(); ?>');
								});
							}else{
								var tmperror="Please try after sometime...";
								$.smallBox({
									title : "Error in request.",
									content : "<i class='fa fa-clock-o'></i> <i>"+tmperror+"</i>",
									color : "#FFA07A",
									iconSmall : "fa fa-warning shake animated",
									timeout : 4000
								});
							}
						}else{
							$.smallBox({
								title : "Error in request.",
								content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
								color : "#FFA07A",
								iconSmall : "fa fa-warning shake animated",
								timeout : 4000
							});
						}
					}
				  });
				return false;
			},
			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	};

	var pagedestroy = function() {
		//$('#profileForm').bootstrapValidator('destroy');
	}

	loadScript("assets/js/sha512.js", function(){
		loadScript("assets/js/forms.js", function(){
			loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
				loadScript("assets/js/plugin/jquery-form/jquery-form.min.js", pagefunction)
			});
		});
	});
	//loadScript("assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js", pagefunction);
	// end pagefunction

	// run pagefunction on load

	//pagefunction();
</script>



<?php } ?>
