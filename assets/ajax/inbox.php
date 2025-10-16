<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(checkpermission($mysqli,58)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!$_SESSION['user_id'])
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];
//if($user_one != 1) die("Under Maintainence!");
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) die("Under Maintainence!");

$usermid="Aarons";
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-fw fa-inbox "></i>
				Account Admin
			<span>>
				Correspondence
			</span>
		</h1>
	</div>
</div>
<style>
.inbox-load{cursor:pointer;}
.flist{margin:0;padding:0;}
.inbox-body .table-wrap{overflow-x:auto !important;}
.inbox-nav-bar .page-title{width:auto !important;}
</style>
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<form class="smart-form">
				<fieldset>
					<div class="row">
						<section class="col col-6"><b>Select Company</b>
								<label class="select"> <i class="icon-append fa fa-user"></i>
								<select name="choosecid" id="choosecid" placeholder="Company Name" class="">
								<?php
								   if ($stmt = $mysqli->prepare('SELECT DISTINCT f.folderId,f.`folder_name` FROM email.folderlist f,company c WHERE f.folder_name LIKE concat(c.email_folder_path,"%") and c.email_folder_path !="" and f.`order`=3 and f.parent_folderId=(SELECT folderId FROM email.`folderlist`  where folder_name="Correspondence" and `order`=2 and parent_folderId=(SELECT folderId FROM email.`folderlist`  where folder_name="Inbox" and `order`=1)) ORDER BY f.`folder_name`')){

//('SELECT id,firstname,lastname FROM user where (usergroups_id=3 or usergroups_id=5) '.$msqll.'  ORDER BY firstname')){

										$stmt->execute();
										$stmt->store_result();
										if ($stmt->num_rows > 0) {
											$ct_count=0;
											$stmt->bind_result($__id,$c_name);
											while($stmt->fetch()){
												echo "<option value='".$__id."' ".($ct_count==0?"SELECTED":"").">&nbsp;&nbsp;".$c_name."</option>";
												$ct_count ++;
											}
										}
									}else{
										header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
										exit();
									}
								?>
								</select>
								</label>
						</section>
					</div>
				</fieldset>
			</form>
		</article>
	</div>
	<!-- end row -->

</section>
<?php }else{
	$clientfid="";
	if ($stmt = $mysqli->prepare('SELECT folderid FROM email.`emails` e, vervantis.company c where e.folderpath=c.email_folder_path and c.company_id='.$cname.' limit 1')){

//('SELECT id,firstname,lastname FROM user where (usergroups_id=3 or usergroups_id=5) '.$msqll.'  ORDER BY firstname')){

	 $stmt->execute();
	 $stmt->store_result();
	 if ($stmt->num_rows > 0) {
		 $stmt->bind_result($clientfid);
		 $stmt->fetch();
	 }
 }else{
	 header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
	 exit();
 }
//$clientfid=1;
if(empty($clientfid)) die("<h4 align='center'>You have no mails!</h4>");

} ?>
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
		$('#choosecid').on('change', function() {
			loadInbox();
		});
<?php } ?>


		/*
		 * LOAD INBOX MESSAGES
		 */
		loadInbox();
		function loadInbox() {
			var cselected="";
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			cselected = $("#choosecid option:selected").val();
			if(cselected != ""){
				cselected="&cselected="+cselected;
			}
<?php } ?>

			$("#inbox-content #maildetails").addClass('hidden');
			$("#inbox-content #maillists").removeClass('hidden');
			$("#backtomail").addClass('hidden');
			$("#mailsearch").val('');
			loadURL("/assets/ajax/email/email-list.php?ct=<?php echo time(); ?>&action=folder"+cselected, $('#inbox-content .flist'));
			loadURL("/assets/ajax/email/email-list.php?ct=<?php echo time(); ?>&action=maillist"+cselected, $('#inbox-content #maillists'));
		}

		/*
		 * Buttons (compose mail and inbox load)
		 */
		$(".inbox-load").click(function() {
			loadInbox();
		});

		$("#mail-search").click(function() {
			mailsearch = $("#mailsearch").val();
			/*if(mailsearch == "123"){
				alert("Please enter search text!");
				$("#mailsearch").focus();

			}else{*/
			var cselected="";
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			cselected = $("#choosecid option:selected").val();
			if(cselected != ""){
				cselected="&cselected="+cselected;
			}
<?php } ?>
				var fname=$("ul.inbox-menu-lg li.active a").attr("ftype");
				$("#inbox-content #maildetails").addClass('hidden');
				$("#inbox-content #maillists").removeClass('hidden');
				$("#backtomail").addClass('hidden');
				//$(".inbox-menu-lg li").removeClass('active');
				//loadURL("/assets/ajax/email/email-list.php?action=folder&search="+mailsearch, $('#inbox-content .flist'));
				loadURL("/assets/ajax/email/email-list.php?ct=<?php echo time(); ?>&action=maillist&search="+mailsearch+"&fname="+fname+cselected, $('#inbox-content #maillists'));
			//}
		});

		$("#reset-search").click(function() {
			$("#mailsearch").val('');
			var cselected="";
<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			cselected = $("#choosecid option:selected").val();
			if(cselected != ""){
				cselected="&cselected="+cselected;
			}
<?php } ?>
			var fname=$("ul.inbox-menu-lg li.active a").attr("ftype");
			$("#inbox-content #maildetails").addClass('hidden');
			$("#inbox-content #maillists").removeClass('hidden');
			$("#backtomail").addClass('hidden');
			loadURL("/assets/ajax/email/email-list.php?ct=<?php echo time(); ?>&action=maillist&search=&fname="+fname+cselected, $('#inbox-content #maillists'));
		});

		$(document).on('keypress',function(e) {
			if(e.which == 13) {
				$("#mail-search").click();
			}
		});

		$("#backtomail").click(function() {
			$("#inbox-content #maildetails").addClass('hidden');
			$("#inbox-content #maillists").removeClass('hidden');
			$("#backtomail").addClass('hidden');
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


</script>
