<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
die("<h5 style='padding-top:30px;' align='center'>Under Construction!</h5>"); 

?>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-sm-12">
				<!-- widget div-->
				<div id="iframecontainer" style="height:450px;<?php if(isset($_SESSION) and $_SESSION["group_id"] == 3){?>margin-bottom:4px;<?php } ?>">				
				<?php
					if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
					{
						?><iframe id="elfinderframe" src="assets/plugins/elfinder/elfinder.html" frameborder="0" width="100%" height="100%"></iframe><?php
					}elseif(isset($_SESSION) and $_SESSION["group_id"] == 3)
					{
						?><iframe src="assets/plugins/cute-file-browser-list/index.html?id=<?php echo rand(1,1000); ?>" frameborder="0" width="100%" height="100%" id="frame1"></iframe><?php
					}
					unset($_COOKIE["docname"]);
				?>
				</div>
				<!-- end widget div -->
		</article>
		<!-- WIDGET END -->
	</div>
	<!-- end row -->

</section>
<div id="dialog" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;}</style>
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

	$('#elfinderframe').load(function() {
		$("#iframecontainer").height(this.contentWindow.document.body.offsetHeight + 20 + 'px');
	});
});
</script>