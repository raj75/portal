<?php 
	define('PROJECT_ROOT', getcwd());
?> 
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Include our stylesheet -->
		<link href="assets/css/styles.css" rel="stylesheet"/> 
		<link href="assets/css/bootstrap4.css" rel="stylesheet"/> 
		
		<!-- FancyBox --> 
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>-->

		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head> 
	<body>
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<!-- Include our script files -->
		<!--- <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->
		<!-- <script src="https://code.jquery.com/jquery-2.2.4.js"></script> -->
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<?php require("assets/js/script.js.php"); ?>
<script src="assets/js/sweetalert.js"></script>
		<!--<script src="assets/fancybox-master/dist/jquery.fancybox.js"></script>-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>



<script>
/*$('.fancybox-media').fancybox({
    type: 'iframe',
    width: 800,
    height: 580,
    // add
    fitToView: false,
    iframe : {
      preload : false
    }
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

	</body>
