<?php require_once("inc/init.php");
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

//require 'get_client.php';

$s3error=0;


if(checkpermission($mysqli,3)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION["user_id"];
?>

<style>
#marketnewsbox .showmore_content {
position: relative;
overflow: hidden;
margin-bottom:14px;
}
#marketnewsbox .showmore_trigger {
width: 100%;
height: 45px;
line-height: 45px;
cursor: pointer;
display:none;
}
#marketnewsbox .showmore_trigger span {
display: block;
}
#marketnewsbox{margin:0;padding:0;}
.rnext{
  width: 150px;
  height: 80px;
  background-color: yellow;
  -ms-transform: rotate(180deg); /* IE 9 */
  -webkit-transform: rotate(180deg); /* Safari 3-8 */
  transform: rotate(180deg);
}
#marketnewsbox .prevbut{float:left;font-weight:bold;cursor: pointer;}
#marketnewsbox .nextbut{float:right;font-weight:bold;cursor: pointer;}
#marketnewsbox .usage{
  background-color: #fff;
  margin: 20px 0;
  padding-top: 29px;
}
#marketnewsbox .dataTables_paginate ul li {padding:0px !important;}
#marketnewsbox .dataTables_paginate ul li a{margin:-1px !important;}
#marketnewsbox .dt-buttons{
float: right !important;
margin: 0.5% auto !important;
}
#marketnewsbox .dataTables_wrapper .dataTables_length{
float: right !important;
margin: 1% 1% !important;
}
#marketnewsbox .dataTables_wrapper .dataTables_filter{
float: left !important;
width: auto !important;
margin: 1% 1% !important;
text-align:left !important;
}
#marketnewsbox .dataTables_wrapper .dataTables_info{margin-left:1% !important;}
#marketnewsbox .dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
#marketnewsbox .no-padding .dataTables_wrapper table, #marketnewsbox .no-padding>table{border-bottom:1px solid #cccccc !important;}
</style>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">

			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-puzzle-piece"></i>
				Market Resources
			<span>>
				Market Commentary
			</span>
		</h1>
	</div>
	<!-- end col -->
</div>
<!-- end row -->

<div id="marketnewsbox"></div>

<script>
$('#marketnewsbox').load('assets/ajax/market-news-pedit.php?ct=<?php echo mt_rand(2,99); ?>&pgno=1');
</script>
