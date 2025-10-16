<?php require_once("inc/init.php"); ?>
<!-- row -->
<div class="row">

	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><!-- PAGE HEADER --><i class="fa-fw fa fa-file-o"></i> Profile</h1>
	</div>
	<!-- end col -->

	<!-- right side of the page with the sparkline graphs -->
</div>
<!-- end row -->

<!-- row -->

<div class="row">

	<div class="col-sm-12">
		<div id="presponse"></div>
	</div>
</div>

<!-- end row -->

</section>
<!-- end widget grid -->
<script type="text/javascript">
	function loadusermenu(userid,image) {
		
		var ddate = new Date();
		if(image != ""){ var isrc=image;}
		else{var isrc=$('a.userdropdown img.online').attr('src');}
		//isrc=isrc.split('?')[0];
		//$("a.userdropdown img.online").attr("src", isrc+"&ct"+Math.floor(Math.random()*1000)+"="+ new Date().getTime());
		//$("a.userdropdown img.online").attr("src", isrc+"&chg="+ddate.getTime());
		$("a.userdropdown").html('');
		$("a.userdropdown").html('<img class="online" dataatrr="'+ddate+'" src="'+isrc+'">');
		//$("a.userdropdown img.online").attr("src", isrc);
		$('#presponse').html('');
		$('#presponse').load('assets/ajax/user-profile.php?userid='+userid);
	}

	pageSetUp();
	
	var pagefunction = function() {
	
	};

	pagefunction();
	$(document).ready(function(){
		$('#presponse').html('')
		$('#presponse').load('assets/ajax/user-profile.php?default=true');
	});
</script>