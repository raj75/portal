<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


if(checkpermission($mysqli,78)==false) die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];

//if($user_one != 1) die("Under Construction!");

$datearr=array();
if ($stmt_date = $mysqli->prepare('SELECT DISTINCT DATE_FORMAT(CONCAT(a.`year`,"-",a.`month`,"-01"),"%b %Y") AS list, CONCAT(a.`year`,"-",a.`month`,"-01") AS `data` FROM benchmark_report a WHERE a.unit_cost IS NOT NULL AND a.usage_cost IS NOT NULL AND a.weather_cost IS NOT NULL ORDER BY a.`year` DESC,a.`month` DESC')) {
	$stmt_date->execute();
	$stmt_date->store_result();
	if ($stmt_date->num_rows > 0) {
		$stmt_date->bind_result($datelist,$datedata);
		while($stmt_date->fetch()){
			$datearr[]=array($datelist,$datedata);
		}
	}
}
?>
<style>
html,body{background:#fff;}
.underline{text-decoration: underline;font-size:25px}
.h5{margin-top:-15px;}
.margin-bottom{margin-bottom:10px;}
.margin-bottom-20{margin-bottom:-20px;}
.benchmarkreport .smart-form{margin-left: 5px;width: 99%;}
.benchmarkreport .noborder{border:none !important;margin-top: 5px;}
.benchmarkreport .mtop19{margin-top: 24px;}
#trendrange{margin-top:10px;}
.trendleft, .trendright{border: 1px solid #ccc;
    text-align: center;
    padding: 2px;
    color: #ccc;
	cursor:pointer;}
.trendright{margin-left:4px;}
.ui-datepicker-calendar {
    display: none;
}
#tab1rp,#tab2rp{font-weight:unset !important;}
#filter1 label.button,#filter2 label.button{margin-top:17px;}
div[aria-describedby="bmdialog"]{width:80% !important;height:auto;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Data Mangement <span>> Benchmark Report</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="benchmarkreport">

	<div>

		<!-- widget edit box -->
		<div class="jarviswidget-editbox">
			<!-- This area used as dropdown edit box -->

		</div>
		<!-- end widget edit box -->

		<!-- widget content -->
		<div class="widget-body">
			<ul id="myTab1" class="nav nav-tabs bordered">
				<li class="active">
					<a href="#htab1" data-toggle="tab">Executive Summary</a>
				</li>
				<li>
					<a href="#htab2" data-toggle="tab">Opportunity Identification</a>
				</li>
			</ul>

			<div id="myTabContent1" class="tab-content padding-10">
				<div class="tab-pane fade in active" id="htab1">
	<!-- row -->
	<div class="row">

		<h2 align="center" class="underline">Executive Summary</h2>
		<h4 align="center" class="h5">Reporting Period: <b id="tab1rp"><?php echo date("M Y"); ?></b></h4>

						<form class="smart-form" id="filter1" novalidate="novalidate" method="post" onsubmit="return false;" autocomplete="off">

							<fieldset>
								<div class="row">
<?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ ?>
									<section class="col col-3">Company
										<label class="select">
											<select name="cname" class="cname">
											<?php
   if ($stmt_section1 = $mysqli->prepare("SELECT Distinct c.company_id,c.company_name FROM `company` c, benchmark_report br, user u where c.company_id=br.company_id and u.company_id=br.company_id")) {
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($ccid,$ccname);
			while($stmt_section1->fetch()){
				echo '<option value="'.$ccid.'">'.$ccname.'</option>';
			}
		}
   }
											?>
											</select>
										</label>
									</section>
<?php } ?>
									<section class="col col-1">Start Date
										<label class="select">
											<select name="sdate" class="sdate">
											<?php
												foreach($datearr as $ky=>$vl){
													echo '<option value="'.$vl[1].'">'.$vl[0].'</option>';
												}
											?>
											</select>
										</label>
									</section>
									<section class="col col-1">End Date
										<label class="select">
											<select name="edate" class="edate">
											<?php
												foreach($datearr as $ky=>$vl){
													echo '<option value="'.$vl[1].'">'.$vl[0].'</option>';
												}
											?>
											</select>
										</label>
									</section>
									<section class="col col-2">Service Type
										<label class="select">
											<select name="stype" class="stype">
												<option value="Electric">Electric</option>
												<option value="Natural Gas">Natural Gas</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Comparable
										<label class="select">
											<select name="compare" class="compare">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Site Name
										<label class="select">
											<select name="sitename" class="sitename">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">State
										<label class="select">
											<select name="state" class="state">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 1
										<label class="select">
											<select name="group1" class="group1">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 2
										<label class="select">
											<select name="group2" class="group2">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 3
										<label class="select">
											<select name="group3" class="group3">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 4
										<label class="select">
											<select name="group4" class="group4">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 5
										<label class="select">
											<select name="group5" class="group5">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">
										<label class="button">
											<button type="submit" id="tab1submit" class="btn btn-primary">
												Submit
											</button>
										</label>
									</section>
								</div>
							</fieldset>
						</form>
		<!-- NEW WIDGET START -->
		<article class="col-sm-6 margin-bottom">
			<iframe id="section1" src="" width="100%" height="400px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section2" src="" width="100%" height="400px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom-20">
			<iframe id="section3" src="" width="100%" height="350px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom-20">
			<iframe id="section4" src="" width="100%" height="350px"  frameBorder="0"></iframe>
		</article>
	</div>
	</div>
	<div class="tab-pane fade" id="htab2">

	<!-- row -->
	<div class="row">
		<h2 align="center" class="underline">Opportunity Identification</h2>
		<h5 align="center" class="h5">Reporting Period: <b id="tab2rp"><?php echo date("M Y"); ?></h5>
						<form class="smart-form" id="filter2" novalidate="novalidate" method="post" onsubmit="return false;" autocomplete="off">

							<fieldset>
								<div class="row">
<?php if($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2){ ?>
									<section class="col col-2">Company
										<label class="select">
											<select name="cname" class="cname">
											<?php
   if ($stmt_section1 = $mysqli->prepare("SELECT Distinct c.company_id,c.company_name FROM `company` c, benchmark_report br, user u where c.company_id=br.company_id and u.company_id=br.company_id")) {
        $stmt_section1->execute();
        $stmt_section1->store_result();
        if ($stmt_section1->num_rows > 0) {
            $stmt_section1->bind_result($ccid,$ccname);
			while($stmt_section1->fetch()){
				echo '<option value="'.$ccid.'">'.$ccname.'</option>';
			}
		}
   }
											?>
											</select>
										</label>
									</section>
<?php } ?>
									<section class="col col-2">Report Month
										<label class="select">
											<select name="edate" class="edate">
											<?php
												foreach($datearr as $ky=>$vl){
													echo '<option value="'.$vl[1].'">'.$vl[0].'</option>';
												}
											?>
											</select>
										</label>
									</section>
									<section class="col col-1">Service Type
										<label class="select">
											<select name="stype" class="stype">
												<option value="Electric" Selected>Electric</option>
												<option value="Natural Gas">Natural Gas</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Trend %
										<label class="input">
											<input type="text" value="10%" id="trendpercentage" class="trendpercentage">
										</label>
									</section>
									<section class="col col-1">&nbsp;
										<label class="input">
											<div id="trendrange" class="trendrange"></div>
										</label>
									</section>
									<section class="col col-1">
										<label class="span mtop19">
											<span class="glyphicon glyphicon-chevron-left trendleft"></span><span class="glyphicon glyphicon-chevron-right trendright"></span>
										</label>
									</section>
									<section class="col col-1">Comparable
										<label class="select">
											<select name="compare" class="compare">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Site Name
										<label class="select">
											<select name="sitename" class="sitename">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-1">State
										<label class="select">
											<select name="state" class="state">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 1
										<label class="select">
											<select name="group1" class="group1">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 2
										<label class="select">
											<select name="group2" class="group2">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 3
										<label class="select">
											<select name="group3" class="group3">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 4
										<label class="select">
											<select name="group4" class="group4">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Group 5
										<label class="select">
											<select name="group5" class="group5">
												<option value="All">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">
										<label class="button">
											<button type="submit" id="tab2submit" class="btn btn-primary">
												Submit
											</button>
										</label>
									</section>
								</div>
							</fieldset>
						</form>
		<!-- NEW WIDGET START -->
		<article class="col-sm-6 margin-bottom">
			<iframe id="section5" src="" width="100%" height="400px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section6" src="" width="100%" height="400px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom-20">
			<iframe id="section7" src="" width="100%" height="340px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom-20">
			<iframe id="section8" src="" width="100%" height="375px"  frameBorder="0"></iframe>
		</article>
	</div>
				</div>
			</div>

		</div>
	</div>
</section>
<div id="bmdialog" class="bmdialog"></div>
<script>
$( function() {
	$("#bmdialog").dialog({
		autoOpen : false, modal : true, show : "blind", hide : "blind"
	});

    $( "#trendrange" ).slider({
      //range: true,
      min: 0,
      max: 100,
      values: [ 15 ],
      slide: function( event, ui ) {
		//if(ui.values[ 0 ] != parseInt($('#trendpercentage').val())){
			$( "#trendpercentage" ).val( ui.values[ 0 ]+"%");
		//}
      }
    });
	$('.trendleft').click(function(){
		var tpercentage= parseInt($('#trendpercentage').val());
		if(tpercentage > 0){
			if(tpercentage <= 5){$('#trendpercentage').val("0%");}
			else {$('#trendpercentage').val((tpercentage - 5)+"%");}
			$('#trendrange a').css('left',tpercentage+'%');
		}
	});
	$('.trendright').click(function(){
		var tpercentage= parseInt($('#trendpercentage').val());
		if(tpercentage < 100){
			if(tpercentage >= 95){$('#trendpercentage').val("100%");}
			else {$('#trendpercentage').val((tpercentage + 5)+"%");}
		}
		$('#trendrange a').css('left',tpercentage+'%');
	});
	$( "#trendpercentage" ).on( "change", function() {
	  var tpercentage= parseInt($('#trendpercentage').val());
		$('#trendrange a').css('left',tpercentage+'%');
	});


	updatefilter();
});

$('#filter1 .sdate').on('change', function() {
	var sdate=$( "#filter1 .sdate" ).val();
	var edate=$( "#filter1 .edate" ).val();

	if(new Date(sdate) > new Date(edate)){ $( "#filter1 .edate" ).val(sdate); }

	updatefilter();
});

$('#filter1 .edate').on('change', function() {
	var sdate=$( "#filter1 .sdate" ).val();
	var edate=$( "#filter1 .edate" ).val();

	if(new Date(sdate) > new Date(edate)){ $( "#filter1 .sdate" ).val(edate); }

	updatefilter();
});

$('#filter2 .edate').on('change', function() {
	updatefilter2();
});

function updatefilter(){
	var ccid=<?php if(isset($_SESSION) and ($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2)){ ?>$( "#filter1 .cname" ).val();<?php }elseif(isset($_SESSION) and ($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5)){echo $_SESSION["company_id"].";"; }else echo "0;"; ?>
	var sdate=$( "#filter1 .sdate" ).val();
	var edate=$( "#filter1 .edate" ).val();

	if(new Date(sdate) <= new Date(edate))
	{
		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {comparable_filter:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .compare").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {site_name_filter:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .sitename").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {state_filter:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .state").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {group1:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .group1").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {group2:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .group2").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {group3:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .group3").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {group4:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .group4").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});

		$.ajax({
			type: 'GET',
			url: './assets/ajax/subpages/benchmark-report-getlist.php',
			data: {group5:true,cid:ccid,sdate:sdate,edate:edate},
			success: function (result) {
				if (result != false)
				{
					var results = decodeURIComponent(result);
					$("#filter1 .group5").html(results);
				}else{
					//alert("Error in request. Please try again later.");
				}
			}
		});


	}else alert("Start Date should be less than or equal to End Date");
}


function updatefilter2(){
	var ccid=<?php if(isset($_SESSION) and ($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2)){ ?>$( "#filter2 .cname" ).val();<?php }elseif(isset($_SESSION) and ($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5)){echo $_SESSION["company_id"].";"; }else echo "0;"; ?>
	var edate=$( "#filter2 .edate" ).val();
	var sdate=edate;

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {comparable_filter:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .compare").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {site_name_filter:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .sitename").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {state_filter:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .state").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {group1:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .group1").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {group2:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .group2").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {group3:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .group3").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {group4:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .group4").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});

	$.ajax({
		type: 'GET',
		url: './assets/ajax/subpages/benchmark-report-getlist.php',
		data: {group5:true,cid:ccid,sdate:sdate,edate:edate},
		success: function (result) {
			if (result != false)
			{
				var results = decodeURIComponent(result);
				$("#filter2 .group5").html(results);
			}else{
				//alert("Error in request. Please try again later.");
			}
		}
	});
}

$('#filter1 .cname').off('change');
$('#filter1 .cname').on('change', function() {
	updatefilter();
});

$('#filter2 .cname').off('change');
$('#filter2 .cname').on('change', function() {
	updatefilter2();
});

$('#filter1 button').off('click');
$('#filter1 button').on('click', function() {
	var tab1cid= <?php if(isset($_SESSION) and ($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2)){ ?>$( "#filter1 .cname" ).val();<?php }elseif(isset($_SESSION) and ($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5)){echo $_SESSION["company_id"].";"; }else echo "0;"; ?>
	var tab1sdate=$( "#filter1 .sdate" ).val();
	var tab1edate=$( "#filter1 .edate" ).val();
	var tab1stype=$( "#filter1 .stype" ).val();
	var tab1compare=$( "#filter1 .compare" ).val();
	var tab1sitename=$( "#filter1 .sitename" ).val();
	var tab1state=$( "#filter1 .state" ).val();
	var tab1group1=$( "#filter1 .group1" ).val();
	var tab1group2=$( "#filter1 .group2" ).val();
	var tab1group3=$( "#filter1 .group3" ).val();
	var tab1group4=$( "#filter1 .group4" ).val();
	var tab1group5=$( "#filter1 .group5" ).val();

	var tmpdate=tab1edate;
	if(tmpdate != ""){
		var mydate = new Date(tmpdate);
		var month = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][mydate.getMonth()];
		$("#tab1rp").html(month + ' ' + mydate.getFullYear());
	}

	$("#section1").attr("src", "assets/ajax/benchmark-report-pedit.php?section1=true&cid="+tab1cid+"&sdate="+tab1sdate+"&edate="+tab1edate+"&stype="+tab1stype+"&compare="+tab1compare+"&sitename="+tab1sitename+"&state="+tab1state+"&group1="+tab1group1+"&group2="+tab1group2+"&group3="+tab1group3+"&group4="+tab1group4+"&group5="+tab1group5+"&ct="+Math.random());
	$("#section2").attr("src", "assets/ajax/benchmark-report-pedit.php?section2=true&cid="+tab1cid+"&sdate="+tab1sdate+"&edate="+tab1edate+"&stype="+tab1stype+"&compare="+tab1compare+"&sitename="+tab1sitename+"&state="+tab1state+"&group1="+tab1group1+"&group2="+tab1group2+"&group3="+tab1group3+"&group4="+tab1group4+"&group5="+tab1group5+"&ct="+Math.random());
	$("#section3").attr("src", "assets/ajax/benchmark-report-pedit.php?section3=true&cid="+tab1cid+"&sdate="+tab1sdate+"&edate="+tab1edate+"&stype="+tab1stype+"&compare="+tab1compare+"&sitename="+tab1sitename+"&state="+tab1state+"&group1="+tab1group1+"&group2="+tab1group2+"&group3="+tab1group3+"&group4="+tab1group4+"&group5="+tab1group5+"&ct="+Math.random());
	$("#section4").attr("src", "assets/ajax/benchmark-report-pedit.php?section4=true&cid="+tab1cid+"&sdate="+tab1sdate+"&edate="+tab1edate+"&stype="+tab1stype+"&compare="+tab1compare+"&sitename="+tab1sitename+"&state="+tab1state+"&group1="+tab1group1+"&group2="+tab1group2+"&group3="+tab1group3+"&group4="+tab1group4+"&group5="+tab1group5+"&ct="+Math.random());
});

$('#filter2 button').off('click');
$('#filter2 button').on('click', function() {
	var tab2cid=<?php if(isset($_SESSION) and ($_SESSION["group_id"]==1 or $_SESSION["group_id"]==2)){ ?>$( "#filter2 .cname" ).val();<?php }elseif(isset($_SESSION) and ($_SESSION["group_id"]==3 or $_SESSION["group_id"]==5)){echo $_SESSION["company_id"].";"; }else echo "0;"; ?>
	var tab2edate=$( "#filter2 .edate" ).val();
	var tab2sdate=tab2edate;
	var tab2stype=$( "#filter2 .stype" ).val();
	var tab2trendpercentage=$( "#filter2 .trendpercentage" ).val();
	tab2trendpercentage=tab2trendpercentage.replace("%","");
	var tab2compare=$( "#filter2 .compare" ).val();
	var tab2sitename=$( "#filter2 .sitename" ).val();
	var tab2state=$( "#filter2 .state" ).val();
	var tab2group1=$( "#filter2 .group1" ).val();
	var tab2group2=$( "#filter2 .group2" ).val();
	var tab2group3=$( "#filter2 .group3" ).val();
	var tab2group4=$( "#filter2 .group4" ).val();
	var tab2group5=$( "#filter2 .group5" ).val();

	var tmpdate2=tab2edate;
	if(tmpdate2 != ""){
		var mydate2 = new Date(tmpdate2);
		var month2 = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"][mydate2.getMonth()];
		$("#tab2rp").html(month2 + ' ' + mydate2.getFullYear());
	}

	$("#section5").attr("src", "assets/ajax/benchmark-report-pedit.php?section5=true&cid="+tab2cid+"&sdate="+tab2sdate+"&edate="+tab2edate+"&stype="+tab2stype+"&trendpercentage="+tab2trendpercentage+"&compare="+tab2compare+"&sitename="+tab2sitename+"&state="+tab2state+"&group1="+tab2group1+"&group2="+tab2group2+"&group3="+tab2group3+"&group4="+tab2group4+"&group5="+tab2group5+"&ct="+Math.random());
	$("#section6").attr("src", "assets/ajax/benchmark-report-pedit.php?section6=true&cid="+tab2cid+"&sdate="+tab2sdate+"&edate="+tab2edate+"&stype="+tab2stype+"&trendpercentage="+tab2trendpercentage+"&compare="+tab2compare+"&sitename="+tab2sitename+"&state="+tab2state+"&group1="+tab2group1+"&group2="+tab2group2+"&group3="+tab2group3+"&group4="+tab2group4+"&group5="+tab2group5+"&ct="+Math.random());
	$("#section7").attr("src", "assets/ajax/benchmark-report-pedit.php?section7=true&cid="+tab2cid+"&sdate="+tab2sdate+"&edate="+tab2edate+"&stype="+tab2stype+"&trendpercentage="+tab2trendpercentage+"&compare="+tab2compare+"&sitename="+tab2sitename+"&state="+tab2state+"&group1="+tab2group1+"&group2="+tab2group2+"&group3="+tab2group3+"&group4="+tab2group4+"&group5="+tab2group5+"&ct="+Math.random());
	$("#section8").attr("src", "assets/ajax/benchmark-report-pedit.php?section8=true&cid="+tab2cid+"&sdate="+tab2sdate+"&edate="+tab2edate+"&stype="+tab2stype+"&trendpercentage="+tab2trendpercentage+"&compare="+tab2compare+"&sitename="+tab2sitename+"&state="+tab2state+"&group1="+tab2group1+"&group2="+tab2group2+"&group3="+tab2group3+"&group4="+tab2group4+"&group5="+tab2group5+"&ct="+Math.random());
});
$( document ).ready(function() {
	updatefilter2();
    $("#filter1 button").click();
    $("#filter2 button").click();
});
</script>
