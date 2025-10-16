<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

$ignorelist=array("Archive","Archive 1","Conversation History","Read","RSS Subscriptions","Sync Issues");

$out__id="";

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
	<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>

	<style>
	#ei_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	#ei_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#ei_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
	#ei_datatable_fixed_column .widget-body,#ei_datatable_fixed_column #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}
	.fullwidth{width:100% !important;}
	.padd5{padding:5px !important;}
	.tcenter{text-align:center;}
	.fnone{float:none !important;}
	#widget-grid-Out .email-list-table .inbox-data-from div {
    width: 400px !important;
	}
	</style>

	<br /><br />
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Tools
				<span>>
					Payment Notifications
				</span>
			</h1>
		</div>
	</div>




	<section id="widget-grid" class="">

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div align="center" style="padding-bottom:10px;">
					<button class="btn-primary wf-show1" align="center" id="button151" style="height: 30px !important;width: auto !important;">Outgoing Payment Notifications</button>
					<button class="btn-primary wf-show2" align="center" id="button161" style="height: 30px !important;width: auto !important;">Incoming Payment Notifications</button>
				</div>
			</article>
		</div>
		<!-- end row -->

	</section>




	<style>
	.inbox-load{cursor:pointer;}
	.flist{margin:0;padding:0;}
	.inbox-body .table-wrap{overflow-x:auto !important;}
	.inbox-nav-bar .page-title{width:auto !important;}
	</style>

	<!-- widget grid -->
	<section id="widget-grid-Out" class="eitable wfresponse1">

				<!-- row -->
				<div class="row">
				<div class="jarviswidget jarviswidget-color-blueDark padd5" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Outgoing Payment Notifications</h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
						<div class="widget-body no-padding" style="padding:1% !important;width:auto !important;">

						<?php
							 if ($stmt = $mysqli->prepare('SELECT folderId,`folder_name` FROM `payment_notifications_sync`.folderlist WHERE `order`=1 and folder_name="Sent Items"')){
								$stmt->execute();
								$stmt->store_result();
								if ($stmt->num_rows > 0) {
									$stmt->bind_result($out__id,$out_c_name);
									$stmt->fetch();


								}
							}else{
								header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
								exit();
							}
						?>
				<style>
				/*.inbox-side-bar{overflow: scroll !important;}*/
				.flist{
					overflow: scroll !important;
					height: 100% !important;
				}
				</style>
				<div class="inbox-nav-bar no-content-padding">

					<div class="btn-group pull-right inbox-paging hidden">
						<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-left"></i></strong></a>
						<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-right"></i></strong></a>
					</div>
					<span class="pull-right" style="display:none !important;"><strong>1-30</strong> of <strong>3,452</strong></span>

				</div>

				<div id="inbox-content" class="inbox-body no-content-padding">
					<div style="width:100% !important;margin-left:15px;">
						<button class="btn-primary hidden" align="" id="backtomail" style="float: left;margin-right: 6px;"><span class="glyphicon glyphicon-backward"></span> Back</button>
						<input placeholder="Search Mail" type="text" style="width:25%;" id="mailsearch">
						<button class="btn-primary" align="" id="mail-search">Search</button>
						<button class="btn-primary" align="" id="reset-search">Reset</button>
					</div>

					<div class="table-wrap custom-scroll animated fast fadeInRight" id="maillists" style="margin-left:0 !important;">
						<!-- ajax will fill this area -->
						LOADING...

					</div>
					<div class="table-wrap custom-scroll animated fast fadeInRight hidden" id="maildetails" style="margin-left:0 !important;"></div>


				</div>
			</div>


		</div>
		</div>

		</div>
		<!-- end row -->

	</section>
	<!-- end widget grid -->




	<section id="widget-grid-in" class="eitable wfresponse2">

		<!-- row -->
		<div class="row">
		<div class="jarviswidget jarviswidget-color-blueDark padd5" id="wid-id-3" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Incoming Payment Notifications</h2>

			</header>

			<!-- widget div-->
			<div>

				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->

				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
				<div class="widget-body no-padding" style="padding:1% !important;width:auto !important;">

		<style>
		/*.inbox-side-bar{overflow: scroll !important;}*/
		.flist{
			overflow: scroll !important;
			height: 100% !important;
		}
		</style>
		<div class="inbox-nav-bar no-content-padding">

			<div class="btn-group pull-right inbox-paging hidden">
				<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-left"></i></strong></a>
				<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-right"></i></strong></a>
			</div>
			<span class="pull-right" style="display:none !important;"><strong>1-30</strong> of <strong>3,452</strong></span>

		</div>

		<div id="inbox-content" class="inbox-body no-content-padding">
			<div style="width:100% !important;margin-left:215px;">
				<button class="btn-primary hidden" align="" id="backtomail" style="float: left;margin-right: 6px;"><span class="glyphicon glyphicon-backward"></span> Back</button>
				<input placeholder="Search Mail" type="text" style="width:25%;" id="mailsearch">
				<button class="btn-primary" align="" id="mail-search">Search</button>
				<button class="btn-primary" align="" id="reset-search">Reset</button>
			</div>
			<div class="inbox-side-bar">

				<h6> Folders <a href="javascript:void(0);" rel="tooltip" title="" data-placement="right" data-original-title="Refresh" class="pull-right txt-color-darken inbox-load"><i class="fa fa-refresh"></i></a></h6>
		<!--<i class="fa fa-plus pull-right"></i>-->
				<div class="flist">LOADING...</div>
			</div>

			<div class="table-wrap custom-scroll animated fast fadeInRight" id="maillists">
				<!-- ajax will fill this area -->
				LOADING...

			</div>
			<div class="table-wrap custom-scroll animated fast fadeInRight hidden" id="maildetails"></div>


		</div>
	</div>


</div>
</div>

</div>
<!-- end row -->

</section>


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

		// PAGE RELATED SCRIPTS

		// pagefunction

		var pagefunction = function() {

			// fix table height
			tableHeightSize();

			$(window).resize(function() {
				tableHeightSize()
			})
			function tableHeightSize() {

				if ($('body').hasClass('menu-on-top')) {
					var menuHeight = 68;
					// nav height

					var tableHeight = ($(window).height() - 224) - menuHeight;
					if (tableHeight < (320 - menuHeight)) {
						$('.table-wrap').css('height', (320 - menuHeight) + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}

				} else {
					var tableHeight = $(window).height() - 224;
					if (tableHeight < 320) {
						$('.table-wrap').css('height', 320 + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}

				}

			}

	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			$('#widget-grid-in #choosecid').on('change', function() {
				loadInbox();
			});

			$('#widget-grid-Out #choosecid').on('change', function() {
				loadInboxout();
			});
	<?php } ?>


			/*
			 * LOAD INBOX MESSAGES
			 */
			loadInbox();
			loadInboxout();

			function loadInboxout() {
				var outcselected="<?php echo $out__id; ?>";

				if(outcselected != ""){
					outcselected="&cselected="+outcselected;
				}

				$("#widget-grid-Out #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-Out #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-Out #backtomail").addClass('hidden');
				$("#widget-grid-Out #mailsearch").val('');
				//loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=folder&section=widget-grid-Out"+outcselected, $('#widget-grid-Out #inbox-content .flist'));
				loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-Out"+outcselected, $('#widget-grid-Out #inbox-content #maillists'));
			}

			function loadInbox() {
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#widget-grid-in #choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>

				$("#widget-grid-in #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-in #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-in #backtomail").addClass('hidden');
				$("#widget-grid-in #mailsearch").val('');
				loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=folder&section=widget-grid-in"+cselected, $('#widget-grid-in #inbox-content .flist'));
				loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-in"+cselected, $('#widget-grid-in #inbox-content #maillists'));
			}

			/*
			 * Buttons (compose mail and inbox load)
			 */
			$("#widget-grid-in .inbox-load").click(function() {
				loadInbox();
			});

			$("#widget-grid-Out  .inbox-load").click(function() {
				loadInboxout();
			});

			$("#widget-grid-in #mail-search").click(function() {
				mailsearch = $("#widget-grid-in #mailsearch").val();
				/*if(mailsearch == "123"){
					alert("Please enter search text!");
					$("#mailsearch").focus();

				}else{*/
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#widget-grid-in #choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>
					var fname=$("#widget-grid-in ul.inbox-menu-lg li.activeli a").attr("ftype");
					$("#widget-grid-in #inbox-content #maildetails").addClass('hidden');
					$("#widget-grid-in #inbox-content #maillists").removeClass('hidden');
					$("#widget-grid-in #backtomail").addClass('hidden');
					//$(".inbox-menu-lg li").removeClass('active');
					//loadURL("/assets/ajax/email/email-list-payment-notifications.php?action=folder&search="+mailsearch, $('#inbox-content .flist'));
					loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-in&search="+mailsearch+"&fname="+fname+cselected, $('#widget-grid-in #inbox-content #maillists'));
				//}
			});

			$("#widget-grid-Out #mail-search").click(function() {
				outmailsearch = $("#widget-grid-Out #mailsearch").val();
				/*if(mailsearch == "123"){
					alert("Please enter search text!");
					$("#mailsearch").focus();

				}else{*/
				var outcselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				outcselected = $("#widget-grid-Out #choosecid option:selected").val();
				if(outcselected != ""){
					outcselected="&cselected="+outcselected;
				}
	<?php } ?>
					var outfname=$("#widget-grid-Out ul.inbox-menu-lg li.activeli a").attr("ftype");
					$("#widget-grid-Out #inbox-content #maildetails").addClass('hidden');
					$("#widget-grid-Out #inbox-content #maillists").removeClass('hidden');
					$("#widget-grid-Out #backtomail").addClass('hidden');
					//$(".inbox-menu-lg li").removeClass('active');
					//loadURL("/assets/ajax/email/email-list-payment-notifications.php?action=folder&search="+mailsearch, $('#inbox-content .flist'));
					loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-Out&search="+outmailsearch+"&fname="+outfname+outcselected, $('#widget-grid-Out #inbox-content #maillists'));
				//}
			});

			$("#widget-grid-in #reset-search").click(function() {
				$("#widget-grid-in #mailsearch").val('');
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#widget-grid-in #choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>
				var fname=$("#widget-grid-in ul.inbox-menu-lg li.activeli a").attr("ftype");
				$("#widget-grid-in #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-in #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-in #backtomail").addClass('hidden');
				loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-in&search=&fname="+fname+cselected, $('#widget-grid-in #inbox-content #maillists'));
			});

			$("#widget-grid-Out #reset-search").click(function() {
				$("#widget-grid-Out #mailsearch").val('');
				var outcselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				var outcselected="<?php echo $out__id; ?>";

				if(outcselected != ""){
					outcselected="&cselected="+outcselected;
				}
	<?php } ?>
				var outfname=$("#widget-grid-Out ul.inbox-menu-lg li.activeli a").attr("ftype");
				$("#widget-grid-Out #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-Out #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-Out #backtomail").addClass('hidden');
				loadURL("/assets/ajax/email/email-list-payment-notifications.php?ct=<?php echo time(); ?>&action=maillist&section=widget-grid-Out&search=&fname="+outfname+outcselected, $('#widget-grid-Out #inbox-content #maillists'));
			});

			$("#widget-grid-in #mailsearch").off('keypress');
			$("#widget-grid-in #mailsearch").on('keypress',function(e) {
				if(e.which == 13) {
					$("#widget-grid-in #mail-search").click();
				}
			});

			$("#widget-grid-Out #mailsearch").off('keypress');
			$("#widget-grid-Out #mailsearch").on('keypress',function(e) {
				if(e.which == 13) {
					$("#widget-grid-Out #mail-search").click();
				}
			});

			$("#widget-grid-in #backtomail").click(function() {
				$("#widget-grid-in #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-in #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-in #backtomail").addClass('hidden');
				//$("#mailsearch").val();
			});

			$("#widget-grid-Out #backtomail").click(function() {
				$("#widget-grid-Out #inbox-content #maildetails").addClass('hidden');
				$("#widget-grid-Out #inbox-content #maillists").removeClass('hidden');
				$("#widget-grid-Out #backtomail").addClass('hidden');
				//$("#mailsearch").val();
			});
		};

		// end pagefunction

		// load delete row plugin and run pagefunction

		loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js", pagefunction);
		//loadScript("js/plugin/bootstraptree/bootstrap-tree.min.js");
		//var pagefunction = function() {
			//loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js");
			//loadScript("/assets/js/plugin/bootstraptree/bootstrap-tree.min.js");

		//};

		// end pagefunction

		// run pagefunction on load

		//pagefunction();

	$(document).ready(function(){
		$('.wfresponse1').show();
		$('.wfresponse2').hide();
		$(".wf-show1").click(function(){
			$('.wfresponse1').show();
				$('.wfresponse2').hide();
		});
		$(".wf-show2").click(function(){
			$('.wfresponse2').show();
			$('.wfresponse1').hide();
		});
	});
	</script>
