<?php require_once("inc/init.php");
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

set_time_limit(0);

//error_reporting(0);
ini_set('max_execution_time', 0);
//require 'get_client.php';

$s3error=0;




$user_one=$_SESSION["user_id"];

if(checkpermission($mysqli,2)==false) die("Permission Denied! Please contact Vervantis.");
?>




<style>
#blogbox .showmore_content {
position: relative;
overflow: hidden;
margin-bottom:14px;
}
#blogbox .showmore_trigger {
width: 100%;
height: 45px;
line-height: 45px;
cursor: pointer;
display:none;
}
#blogbox .showmore_trigger span {
display: block;
}
#blogbox{margin:0;padding:0;}
.rnext{
  width: 150px;
  height: 80px;
  background-color: yellow;
  -ms-transform: rotate(180deg); /* IE 9 */
  -webkit-transform: rotate(180deg); /* Safari 3-8 */
  transform: rotate(180deg);
}
#blogbox .prevbut{float:left;font-weight:bold;cursor: pointer;}
#blogbox .nextbut{float:right;font-weight:bold;cursor: pointer;}
#blogbox .usage{
  background-color: #fff;
  margin: 20px 0;
  padding-top: 29px;
}
#blogbox .dataTables_paginate ul li {padding:0px !important;}
#blogbox .dataTables_paginate ul li a{margin:-1px !important;}
#blogbox .dt-buttons{
float: right !important;
margin: 0.5% auto !important;
}
#blogbox .dataTables_wrapper .dataTables_length{
float: right !important;
margin: 1% 1% !important;
}
#blogbox .dataTables_wrapper .dataTables_filter{
float: left !important;
width: auto !important;
margin: 1% 1% !important;
text-align:left !important;
}
#blogbox .dataTables_wrapper .dataTables_info{margin-left:1% !important;}
#blogbox .dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
#blogbox .no-padding .dataTables_wrapper table, #blogbox .no-padding>table{border-bottom:1px solid #cccccc !important;}
.paddingright-10{padding-right:10px;}
</style>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row" id="<?php echo 's'.mt_rand(9, 9999999); ?>">
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">

			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-puzzle-piece"></i>
				Market Resources
			<span>>
				Verve Energy Blog
			</span>
		</h1>
	</div>
	<!-- end col -->
</div>
<!-- end row -->

<div class="row">
	<div class="col-sm-9 full-display">
		<div class="paddingright-10">
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
	<p><button class="btn btn-primary blog-post-btn" id="create-new-post">Create new post</button></p>
	<br /><br />
	<hr id="breakline">
<?php } ?>
		</div>
	</div>
</div>
<div id="blogbox"></div>
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
<div id="blogdialog" title="Preview"></div>
<script src="<?php echo ASSETS_URL; ?>/assets/js/plugin/ckeditor/ckeditor.js"></script>
<script src="<?php echo ASSETS_URL; ?>/assets/js/plugin/ckfinder/ckfinder.js"></script>
<script src="<?php echo ASSETS_URL; ?>/assets/js/base64_decode.js"></script>
<?php } ?>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.showmore.min.js"></script>
<script>
$(document).ready(function(){
	//setTimeout(function(){
		//$.getScript("<?php echo ASSETS_URL; ?>/assets/js/plugin/ckeditor/ckeditor.js");
		//$.getScript("<?php echo ASSETS_URL; ?>/assets/js/plugin/ckfinder/ckfinder.js");
		//$.getScript("<?php echo ASSETS_URL; ?>/assets/js/base64_decode.js");

	//}, 2 * 1000);
	$('#blogbox').load('assets/ajax/blog-pedit.php?ct=<?php echo time(); ?>&pgno=1');
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
	var theight=$(document).height();
	$( "#blogdialog" ).dialog({
	  /*height: $(document).height(),*/
	  height: (screen.height*0.78),
      width: (screen.width*0.78),
      show: "fade",
      hide: "fade",
	  title: 'Create New Post',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
		close: function(event, ui)
		{
			$(this).dialog("close");
			$( "#blogdialog" ).html("");
		}
    });

	$('#create-new-post').click(function() {
		$( "#blogdialog" ).html("");
		$( "#blogdialog" ).load( "assets/ajax/blog-subpage.php?ct=<?php echo time(); ?>&newform=true" );
		$('#blogdialog').dialog('open');
	});
<?php } ?>
});
</script>
