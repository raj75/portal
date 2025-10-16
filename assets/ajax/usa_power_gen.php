<?php require_once("inc/init.php");
if(!isset($_SESSION))
{
	require_once '../includes/db_connect.php';
	require_once '../includes/functions.php';
	sec_session_start();
}

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
	die("Restricted Access");

//if(checkpermission($mysqli,78)==false) die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];
//if($user_one != 1) die("Under Construction!");
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Tools <span>> Power Generation</span>
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
					<h2>Power Generations</h2>

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
              <iframe src="" id="usapowergenmap" height="600px" width="100%" scrolling="no" frameBorder="0" style="overflow: hidden;"></iframe>
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
<section id="widget-grid1" class="dsireusatable m-top45">

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
					<h2>Power Generation </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-usapgen"></div>
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
  $('#usapowergenmap').attr('src','assets/ajax/subpages/usapowergen-edit3.php?load=true&ct=<?php echo time(); ?>');
	$('#list-usapgen').load('assets/ajax/list-usapgen.php?load=true&ct=<?php echo time(); ?>');
  //$('#usapowergenmap').attr('src','https://carbonbrief.github.io/us-energy-map-operating/');
});

function zmap(zid,zlat,zlong){
  document.getElementById('usapowergenmap').contentWindow.zoomto(zlat,zlong);
	$([document.documentElement, document.body]).animate({
			scrollTop: $("h1").offset().top
	}, 800);
}
</script>
