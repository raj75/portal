<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,60)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

$user_one=$_SESSION["user_id"];
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats"></i>
				Market Resources
			<span>>
				Streaming News
			</span>
		</h1>
	</div>
</div>
<style>
.margin-bt-5{margin-bottom: -5px !important;}
.fg_wid_footer,.fg_wid_header{display:none !important;}
.feedgrabbr_widgets .fg_widget{width:100% !important;}
.feedgrabbr_widgets .fg_wid_cont {
    margin-left: 0px !important;
    margin-right: 0px !important;
    border-radius: 0px !important;
}
#streamnewsarticle .widget-body.no-padding{margin:-3px 0 -3px 0 !important;}
.streamnewsdialog .ui-dialog-title{width:99%;font-size:27px !important;}
.ui-dialog-title{font-size:27px !important;}
.ui-dialog{width:80% !important;}
.ui-dialog{height:auto;max-height:70vh !important;}
div[aria-describedby="sndialog"]{overflow-y:scroll;}
.streamnewsdialog{width:99%;font-size: 17px;}
.ui-dialog-buttonpane {
    text-align: center !important;
}
.ui-dialog-buttonset {
    width: 100% !important;
}
</style>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="streamnewsarticle">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>North America Streaming News</h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox"></div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding margin-bt-5">
						<!--<iframe name="rssfeed_frame" width="100%" height="650" frameborder="0" src="assets/ajax/rss.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="yes" allowtransparency="true"></iframe>-->
						<iframe name="rssfeed_frame" width="100%" height="812" frameborder="0" src="/assets/ajax/subpages/northamericanews.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
					<div>
				</div>
			</div>
		</article>


		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="streamnewsarticle">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Europe Streaming News</h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox"></div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<!--<iframe name="rssfeed_frame" width="100%" height="650" frameborder="0" src="https://portal.vervantis.com/assets/ajax/rss2.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>-->
						<iframe name="rssfeed_frame" width="100%" height="812" frameborder="0" src="/assets/ajax/subpages/europenews.php?ct=<?php echo mt_rand(2,99); ?>" marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" allowtransparency="true"></iframe>
					<div>
				</div>
			</div>
		</article>
	</div>
	<!-- end row -->

</section>
<div id="sndialog" class="streamnewsdialog" title="Preview"></div>
<!-- end widget grid -->
<script type="text/javascript">
	var theight=$(document).height();
	$( "#sndialog" ).dialog({
	  /*height: $(document).height(),*/
	  /*height: (screen.height*0.5),*/
      /*width: (screen.availWidth*0.78),*/
      show: "fade",
      hide: "fade",
	  title: 'News',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
	close: function(event, ui) {
		//jQuery("#confirm-dialog").remove();
		},
	buttons: {
		"Close": function(event, ui) {
		  jQuery('.ui-dialog').dialog('close');
		}
	 }
    });
//$('.ui-dialog-title').hide();

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



		};
$( document ).ready(function() {
    //document.getElementById("na").src = "//www.rssmix.com/nb/5c52114d59bc0/";
});
$(document).on("click",".ui-dialog-buttonset button",function() {
     $('#sndialog').dialog( "close" );
});
</script>
