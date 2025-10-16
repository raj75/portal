	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Include our stylesheet -->
	<link href="assets/css/styles.css" rel="stylesheet"/>
	<div class="filemanager">

		<div class="search">
			<input type="search" placeholder="Find a file.." />
		</div>

		<div class="breadcrumbs"></div>

		<ul class="data"></ul>

		<div class="nothingfound">
			<div class="nofiles"></div>
			<span>No files here.</span>
		</div>

	</div>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/vader/jquery-ui.css">
	<script src="assets/js/jquery-1.11.0.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</div>
	<!-- Include our script files -->
	<?php require("assets/js/script.js.php"); ?>
	<script>	
	/*$('.Popup').click(function() {
     var NWin = window.open($(this).prop('href'), '', 'height=800,width=800');
     if (window.focus)
     {
       NWin.focus();
     }
     return false;
    });*/
	</script>
<style>
.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 6%;
    top: 0;
    width: 33%;
    z-index: 9999;
}
</style>