<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//if(checkpermission($mysqli,9)==false) die("Permission Denied! Please contact Vervantis.");
//if(!isset($_SESSION["user_id"]))
//		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

//if($user_one != 1) die("Under Construction!");
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
    height: 48px;">
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 fromsecondpage" style="margin-top: 8px;margin-right: -23px;display:none;">
		<b><img id="mvbk" onclick="move_back_dsireusa()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;">Back</b>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Tools
			<span>>
				Incentives Map
			</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="dsireusatable m-top45">

	<!-- row -->
	<div class="row">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
					<h2>Incentives Map</h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-dsireusamap">
              <iframe src="" id="incetivesmap" height="600px" width="100%" frameBorder="0"></iframe>
          </div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<section id="dsireusadetails" class="m-top"></section>
<!-- end widget grid -->
<div id="response"></div>
<script type="text/javascript">
window.scrollTo(0,0);
$(document).ready(function(){
  $('#incetivesmap').attr('src','assets/ajax/subpages/incetivesmap-edit.php?load=true&ct=<?php echo time(); ?>');
});
</script>
