<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

//if($user_one != 1) die("Under Construction!");

?>
<style>
html,body{background:#fff;}
.underline{text-decoration: underline;font-size:25px}
.h5{margin-top:-15px;}
.margin-bottom{margin-bottom:10px;}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i> 
				Test <span>> Benchmark Report</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<h2 align="center" class="underline">Executive Summary</h2>
		<h4 align="center" class="h5">Reporting Period:August 2019</h4>
		
						<form class="smart-form" id="filter1" novalidate="novalidate" method="post" onsubmit="return false;" autocomplete="off">

							<fieldset>
								<div class="row">
									<section class="col col-2">Start Date
										<label class="select">
											<select name="sdate" id="sdate">
												<option value="2019-08">August 2019</option>
											</select>
										</label>
									</section>
									<section class="col col-2">End Date
										<label class="select">
											<select name="edate" id="edate">
												<option value="2019-08">August 2019</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Service Type
										<label class="select">
											<select name="stype" id="stype">
												<option value="electric">Electric</option>
												<option value="natural gas">Natural Gas</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Comparable
										<label class="select">
											<select name="compare" id="compare">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Site Name
										<label class="select">
											<select name="sitename" id="sitename">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-1">State
										<label class="select">
											<select name="state" id="state">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Region
										<label class="select">
											<select name="region" id="region">
												<option value="all">All</option>
											</select>
										</label>
									</section>
								</div>
							</fieldset>
						</form>
		<!-- NEW WIDGET START -->
		<article class="col-sm-6 margin-bottom">
			<iframe id="section1" src="assets/ajax/benchmark-report-pedit1.php?section1=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section2" src="assets/ajax/benchmark-report-pedit1.php?section2=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section3" src="assets/ajax/benchmark-report-pedit1.php?section3=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section4" src="assets/ajax/benchmark-report-pedit1.php?section4=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
	</div>
	
	
	<!-- row -->
	<div class="row">
		<h2 align="center" class="underline">Oppurtunity Identification</h2>
		<h5 align="center" class="h5">Reporting Period:August 2019</h5>
						<form class="smart-form" id="filter2" novalidate="novalidate" method="post" onsubmit="return false;" autocomplete="off">

							<fieldset>
								<div class="row">
									<section class="col col-2">Service Type
										<label class="select">
											<select name="stype" id="stype">
												<option value="electric" Selected>Electric</option>
												<option value="natural gas">Natural Gas</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Trend %
										<label class="select">
											<select name="trend" id="trend">
												<option value="10">10%</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Comparable
										<label class="select">
											<select name="compare" id="compare">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-2">Site Name
										<label class="select">
											<select name="sitename" id="sitename">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-1">State
										<label class="select">
											<select name="state" id="state">
												<option value="all">All</option>
											</select>
										</label>
									</section>
									<section class="col col-1">Region
										<label class="select">
											<select name="region" id="region">
												<option value="all">All</option>
											</select>
										</label>
									</section>
								</div>
							</fieldset>
						</form>
		<!-- NEW WIDGET START -->
		<article class="col-sm-6 margin-bottom">
			<iframe id="section5" src="assets/ajax/benchmark-report-pedit1.php?section5=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section6" src="assets/ajax/benchmark-report-pedit1.php?section6=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section7" src="assets/ajax/benchmark-report-pedit1.php?section7=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
		<article class="col-sm-6 margin-bottom">
			<iframe id="section8" src="assets/ajax/benchmark-report-pedit1.php?section8=true&ct=<?php echo time(); ?>" width="100%" height="450px"  frameBorder="0"></iframe>
		</article>
	</div>
</section>
<script>
$('#filter1 #stype').on('change', function() {
	var stype=this.value;
	$("#section3").attr("src", "assets/ajax/benchmark-report-pedit1.php?section3=true&stype="+stype+"&ct="+Math.random());
	$("#section4").attr("src", "assets/ajax/benchmark-report-pedit1.php?section4=true&stype="+stype+"&ct="+Math.random());
});
$('#filter2 #stype').on('change', function() {
	var stype=this.value;
	$("#section5").attr("src", "assets/ajax/benchmark-report-pedit1.php?section5=true&stype="+stype+"&ct="+Math.random());
	$("#section6").attr("src", "assets/ajax/benchmark-report-pedit1.php?section6=true&stype="+stype+"&ct="+Math.random());
	$("#section7").attr("src", "assets/ajax/benchmark-report-pedit1.php?section7=true&stype="+stype+"&ct="+Math.random());
	$("#section8").attr("src", "assets/ajax/benchmark-report-pedit1.php?section8=true&stype="+stype+"&ct="+Math.random());
});
</script>