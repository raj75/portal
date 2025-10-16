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
.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0 !important;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;}
.ui-dialog{top:48px !important;position:fixed;}
</style>

<link href="<?php echo ASSETS_URL; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row" style="position: fixed !important;width: 100%;z-index: 100;
    background: url(../assets/img/mybg.png) #fff;
    height: 48px;">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i>
				Market Resources
			<span>>
				Weekly Reports
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
					<h2>Weekly Reports </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding" id="list-weeklyreports"></div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->
<div id="dialog" title="Preview"></div>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
window.scrollTo(0,0);
	pageSetUp();

	// pagefunction
	var pagefunction = function() {
	};

	pagefunction();

	function load_details(id) {
		$.ajax({
			url: 'assets/includes/weeklyreports.inc.php',
			type: 'POST',
			data: {action:'s3load',pid:id},
			success: function (data) {
				if(data != false)
				{
					var result = JSON.parse(data);
					if(result.data)
					{	var rname="Download.pdf";
						discode='<object type="text/html" data="assets/plugins/pdfjs/web/pdfviewer.php?file='+encodeURIComponent(encodeURI(unescape(result.data)))+'&fname='+rname+'" style="overflow:auto;width:100%;height:85vh;"></object>';
						$("#dialog").html('');
						$("#dialog").html(discode);
						$("#dialog").dialog("open");
					}else{
						alert("Currently Unavaible. Please try after sometime!");
					}
				}else{
				alert("Currently Unavaible. Please try after sometime!");
				}
			}
		});
	}

$(document).ready(function(){
	$('#list-weeklyreports').load('assets/ajax/list-weeklyreports.php?load=true&ct=<?php echo time(); ?>');

		filename="";
		var theight=$(document).height();
	$( "#dialog" ).dialog({
		  /*height: $(document).height(),*/
		  height: (screen.height*0.78),
	      /*width: ((95*theight)/100),*/
				width: "80%",
	      show: "fade",
	      hide: "fade",
		  title: 'Preview',
		  resizable: false,
		  //bgiframe: true,
	      modal: true,
		  autoOpen: false,
	<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1)){ ?>
			open: function (event, ui) {
				   // this is where we add an icon and a link
				   $('#download-d-btn')
					.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

			}
	<?php } ?>
	    });

});
</script>
