<?php
session_start();
$_SESSION['username'] = "No Name" // Must be already set
?>

<link type="text/css" rel="stylesheet" media="all" href="assets/plugins/jquerychat/css/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="assets/plugins/jquerychat/css/screen.css" />

<!--[if lte IE 7]>
<link type="text/css" rel="stylesheet" media="all" href="assets/plugins/jquerychat/css/screen_ie.css" />
<![endif]-->


<a href="javascript:void(0)" onclick="javascript:chatWith('janedoe')">Chat With Jane Doe</a>
<a href="javascript:void(0)" onclick="javascript:chatWith('johndoe')">Chat With John Doe</a>
<!-- YOUR BODY HERE -->

<script type="text/javascript" src="assets/plugins/jquerychat/js/jquery.js"></script>
<script type="text/javascript" src="assets/plugins/jquerychat/js/chat.js"></script>
