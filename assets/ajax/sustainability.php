<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$_COOKIE["docname"] == "Sustainability";
$_COOKIE["appurl"] = APP_URL;
$_COOKIE["uid"] = $user_one;
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-globe "></i> 
				Sustainability
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-sm-12">
	<?php
		if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
		{
	?>			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-0" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-cloud"></i> </span>
					<h2>My Document! </h2>
				</header>
	<?php
		}
	?>
				<!-- widget div-->
				<div style="<?php if(isset($_SESSION) and $_SESSION["group_id"] == 3){?>height:450px;margin-bottom:4px;<?php }else{echo "height:900px;";} ?>">				
				<?php
					if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
					{
						?><iframe src="assets/plugins/elfinder/elfinder.html" frameborder="0" width="100%" height="100%"></iframe><?php
					}elseif(isset($_SESSION) and $_SESSION["group_id"] == 3)
					{
						?><iframe src="assets/plugins/cute-file-browsers/index.php?id=<?php echo rand(1,1000); ?>&docname=Sustainability" frameborder="0" width="100%" height="100%" id="frame1"></iframe><?php
					}
				?>
				</div>
				<!-- end widget div -->
	<?php
		if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
		{
	?>
			</div>
			<!-- end widget -->
	<?php
		}
	?>
		</article>
		<!-- WIDGET END -->
	</div>
	<!-- end row -->

</section>
<div id="dialog" title="Preview"></div>
<style>.noshow{height: 12% !important;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;}</style>
<script type="text/javascript">
$(document).ready(function(){
	filename="";
$( "#dialog" ).dialog({
	  height: $(document).height(),
      width: $(document).height(),
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
<?php if(isset($_SESSION) and $_SESSION["group_id"] == 3){ ?>
		open: function (event, ui) {
			   // this is where we add an icon and a link
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

		}
<?php } ?>
    });
});
</script>