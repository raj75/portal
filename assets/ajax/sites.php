<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//if(checkpermission($mysqli,9)==false) die("Permission Denied! Please contact Vervantis.");
//if(!isset($_SESSION["user_id"]))
//		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];


$showdemo=1;
$subquery=((isset($showdemo) and $showdemo==1)?"&showdemo=1":"");
?>
<style>
.m-top{margin-top:56px;}
.m-top45{margin-top:47px;}
.m-top77{margin-top:79px;}
.m-bottom50{margin-bottom: -50px !important;font-weight:bold;z-index:98;margin-top: 15px;}
.m-bottom50 span{vertical-align: top;}
</style>

<link href="<?php echo ASSETS_URL; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row" style="position: fixed !important;width: 100%;z-index: 100;
    background: url(../assets/img/mybg.png) #fff;
    height: 48px;<?php if(isset($_GET["fromdashboard"])){ echo 'margin-top:-44px;';} ?>">
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 fromthirdpage" style="margin-top: 8px;margin-right: -23px;display:none;">
		<b><img id="mvbk" onclick="move_invoice_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 fromsecondpage" style="margin-top: 8px;margin-right: -23px;display:none;">
		<b><img id="mvbk" onclick="move_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 fromdashboard" style="margin-top: 8px;margin-right: -23px;display:none;">
		<b><img id="mvbk" onclick="move_back_dashboard()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Company
			<span>>
				Site List
			</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="sitestable m-top45">

	<!-- row -->
	<div class="row">
	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-bottom50">
			<input type="checkbox" <?php echo ($showdemo==1?"CHECKED":""); ?> value="Demo Company" id="hidedemo" class="flleft"><span class="flleft">Hide Demo Company</span>
		</article>
	<?php } ?>

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-top">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Site List </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-sites"></div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<section id="sitesdetails" class="m-top"></section>
<section id="invoicedetails" class="m-top77"></section>
<!-- end widget grid -->
<div id="response"></div>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
window.scrollTo(0,0);
	pageSetUp();

	// pagefunction
	var pagefunction = function() {
	};

	pagefunction();

function load_details(id) {
	//$(window).scrollTop($('#response').offset().top);
	/*$('#response').html('<iframe src="assets/ajax/details.php?id='+id+'" style="width:100%;height:500px" frameBorder="0" scrolling="no"></iframe>');

	$('html, body').animate({
        scrollTop: $('#response').offset().top
    }, 2000);*/
	$(".sitestable").fadeOut( "slow" );
	$('#sitesdetails').load('assets/ajax/sitedetails.php?<?php if(isset($_GET["fromdashboard"])){ echo 'fromdashboard=true&'; } ?><?php if(isset($_GET["sid"])){?>noback=true&<?php } ?>id='+id);
}

function load_sacc1(aids,sno)
{
	parent.$('#sitesaccountcont').remove();
	parent.$('.siterow').after('<div id="sitesaccountcont" style="margin:0 !important;padding:0 !important;"></div>');
	parent.$('#sitesaccountcont').load('assets/ajax/details.php?pc=1&naids='+aids+'&sno='+sno+'');
}

$(document).ready(function(){

	<?php if(isset($_GET["sid"])){?>$('#wid-id-2').hide();<?php } ?>
	<?php if(!isset($_GET["fromdashboard"])){ ?>
	$("#new-sites").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		$('#response').load('assets/ajax/sites-add.php');
	});
	$('#list-sites').load('assets/ajax/list-sites.php?load=true&ct=<?php echo time(); echo $subquery; ?>');
	<?php } ?>
	<?php if(!isset($_GET["sid"])){?><?php }else{ ?>load_details('<?php echo $_GET["sid"];?>');<?php } ?>

	<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id']) and !isset($_GET["fromdashboard"])){ ?>
	$('#hidedemo').change(function () {
		if($('#hidedemo').prop("checked")==1){
			var showdemo=1;
		}else{
			var showdemo=0;
		}
		$('#list-sites').load('assets/ajax/list-sites.php?load=true&ct=<?php echo time(); ?>&showdemo='+showdemo);
	});
	<?php } ?>
});
</script>
