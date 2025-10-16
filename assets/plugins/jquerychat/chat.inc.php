<?php
session_start();
$_SESSION['username'] = "No Name" // Must be already set
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/loose.dtd" >

<html>
<head>
<title>Sample Chat Application</title>
<style>
body {
	background-color: #eeeeee;
	padding:0;
	margin:0 auto;
	font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
	font-size:11px;
}
</style>

<link type="text/css" rel="stylesheet" media="all" href="http://localhost/jvwarrick/dev/dev/assets/plugins/jquerychat/css/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="http://localhost/jvwarrick/dev/dev/assets/plugins/jquerychat/css/screen.css" />

<!--[if lte IE 7]>
<link type="text/css" rel="stylesheet" media="all" href="http://localhost/jvwarrick/dev/dev/assets/plugins/jquerychat/css/screen_ie.css" />
<![endif]-->

</head>
<body>
<div id="main_container">

<a href="javascript:void(0)" onclick="javascript:chatWith('janedoe')">Chat With Jane Doe</a>
<a href="javascript:void(0)" onclick="javascript:chatWith('johndoe')">Chat With John Doe</a>
<!-- YOUR BODY HERE -->

</div>

<script type="text/javascript" src="http://localhost/jvwarrick/dev/dev/assets/plugins/jquerychat/js/jquery.js"></script>
<script type="text/javascript" src="http://localhost/jvwarrick/dev/dev/assets/plugins/jquerychat/js/chat.js"></script>

</body>
</html>