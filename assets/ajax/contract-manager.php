<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,55)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
?>
	<style>
	.noshow{
		height: 18%;
		opacity: 0.1;
		position: absolute;
		right: 2%;
		top: 0;
		width: 6%;
		z-index: 9999;
	}
	#cmopdialog{
		overflow-y:hidden !important;
	}
	.ui-dialog{top:48px !important;position:fixed;}
	</style>
	<br />
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Energy Procurement
				<span>>
					Supplier Contracts
				</span>
			</h1>
		</div>
	</div>
	<!-- widget grid -->
	<section id="widget-grid" class="cmtable">

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				<div class="row" style="padding-left:15px; padding-right:15px;">
					<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>	
					<div style="float:left;">
						<input type="checkbox" checked="checked" value="Demo" id="hidedemo" class="flleft"><span class="flleft"> Hide Demo 1&2 Company</span>
					</div>
					<?php } ?>
					
					<div align="right" style="padding-bottom:10px; float:right">
						<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>	
							<button class="btn-primary" align="right" id="new-cm" style="height: 30px !important;width: auto !important;">Add New Contract</button>
						<?php } ?>
					</div>
				</div>
				
				<div id="cmtable"></div>
			</article>
		</div>
		<!-- end row -->

	</section>
	<!-- end widget grid -->
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
	<section id="cmdetails"></section>
	<section id="cmaccdetails"></section>
	<?php }
	if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5){?>
	<div id="cmresponse"></div>
	<div id="cmopdialog"></div>
	<?php } ?>
	<script type="text/javascript">
	function cmload_details(cmid) {
		$(".cmtable").fadeOut( "slow" );
		$('#cmresponse').html('');
		$('#cmaccdetails').html('');
		$('#cmdetails').load('assets/ajax/contract-manager-pedit.php?&ct=<?php echo rand(9,33); ?>&action=details&<?php if(isset($_GET["cmid"])){?>noback=true&<?php } ?>cmid='+cmid);
	}

	function cmaccload_details(cmaccid,ccmid) {
		$(".cmtable").fadeOut( "slow" );
		$('#cmresponse').fadeOut( "slow" );
		$('#cmdetails').fadeOut( "slow" );
		$('#cmaccdetails').load('assets/ajax/contract-manager-peditsub.php?&ct=<?php echo rand(9,33); ?>&action=subdetails&<?php if(isset($_GET["cmid"])){?>noback=true&<?php } ?>cmsacceditid='+cmaccid+'&cmid='+ccmid);
	}
	$(document).ready(function(){
		<?php if(isset($_GET["cmid"])){?>$('#wid-id-2').hide();<?php } ?>
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){?>
		$("#new-cm").click(function(){
			//$('#datatable_fixed_column').DataTable().ajax.reload();
			//otable.ajax.reload();
			$("#dialog-message").remove();
			$(".cmtable").fadeOut( "slow" );
			$('#cmresponse').html('');
			$('#cmresponse').load('assets/ajax/contract-manager-pedit.php?action=add&ct=<?php echo rand(9,33); ?>');
		});
	<?php } ?>
		$('#cmtable').load("assets/ajax/contract-manager-pedit.php?load=true&ct=<?php echo rand(9,33); ?>");
		<?php if(!isset($_GET["cmid"])){?><?php }else{ ?>cmload_details(<?php echo $_GET["cmid"];?>);<?php } ?>
		
		$('#hidedemo').change(function () {
			if($('#hidedemo').prop("checked")==1){
				var showdemo=1;
			}else{
				var showdemo=0;
			}
			//$('#list-sites').load('assets/ajax/list-sites.php?load=true&ct=<?php echo time(); ?>&showdemo='+showdemo);
			$('#cmtable').load("assets/ajax/contract-manager-pedit.php?load=true&showdemo="+showdemo+"&ct=<?php echo rand(9,33); ?>");
		});
	});
	</script>	
