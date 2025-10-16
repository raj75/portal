<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");
		
if(!isset($_GET["mtid"]) and !isset($_GET["year"]) and !isset($_GET["month"]))
		die("Wrong Parameter Provided.");
else{
	$mtid=$mysqli->real_escape_string($_GET["mtid"]);
	$_year=$mysqli->real_escape_string($_GET["year"]);
	$_month=$mysqli->real_escape_string($_GET["month"]);
}
		
$user_one=$_SESSION["user_id"];


$temp_invoice=array();
	if ($stmt = $mysqli->prepare('SELECT u.user_id, a.id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure, sg.service_group FROM `usage` u, accounts a,service_group sg where u.meter_number='.$mtid.' and u.meter_number=a.meter_number and a.service_group_id=sg.service_group_id order by u.interval_end desc')) { 

//('SELECT u.id, a.id, u.meter_id, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure, sg.service_group FROM `usage` u, accounts a,service_group sg where u.meter_id='.$mtid.' and u.meter_id=a.meter_id and a.service_group_id=sg.service_group_id order by u.interval_end desc'))

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_u_id,$_a_id,$_u_meter_id,$_u_interval_start,$_u_interval_end,$_u_interval_value,$_u_unit_of_measure,$_sg_service_group);
			while($stmt->fetch()) {
				$stime=strtotime($_u_interval_start);
				$smonth=date("F",$stime);
				$syear=date("Y",$stime);

				$etime=strtotime($_u_interval_end);
				$emonth=date("F",$etime);
				$eyear=date("Y",$etime);				

				$datediff = $etime - $stime;
				$diff_date = floor($datediff/(60*60*24));		
				
				$per_day_value=$_u_interval_value/$diff_date;
				
				//Same Date
				if(($_u_interval_start == $_u_interval_end) or ($syear == $eyear and $smonth == $emonth and $_u_interval_start != $_u_interval_end))
				{
					$temp_invoice[$_u_meter_id][$syear][$smonth][]=$_u_interval_value;
				}else{//if($_u_interval_start != $_u_interval_end and $syear == $eyear and $smonth != $emonth){
					$time   = $stime;
					$last   = date('m-Y', $etime);
					
					do {
						$month = date('m-Y', $time);
						$total = date('t', $time);
						$tmonth=date('F', $time);
						$tyear=date('Y', $time);
						
						if($syear==$tyear and $smonth==$tmonth)
						{
							$daysRemaining = (int)date('t', $stime) - (int)date('j', $stime);
							$temp_invoice[$_u_meter_id][$syear][$smonth][]=$per_day_value*($daysRemaining);
						}elseif($eyear==$tyear and $emonth==$tmonth)
						{
							$daysPast = (int)date('j', $etime);
							$temp_invoice[$_u_meter_id][$eyear][$emonth][]=$per_day_value*$daysPast;						
						}else{
							$daysOfMonth = (int)date('t', $stime);
							$temp_invoice[$_u_meter_id][$tyear][$tmonth][]=$per_day_value*$daysOfMonth;						
						}

						$time = strtotime('+1 month', $time);
					} while ($month != $last);					
				}
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
?>
<style>
.accdetails-grid{background:#ffffff !important;}
</style>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- new widget -->
			<div class="col-sm-6 col-md-6 col-lg-6 jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-fullscreenbutton="false" style="padding-right:5px;">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2> Details </h2>
					<!-- <div class="widget-toolbar">
					add: non-hidden - to disable auto hide

					</div>-->
				</header>
				<table width="100%" class="table table-striped table-bordered table-hover accdetails-grid">
					<thead>
						<tr>
							<th data-hide="phone">Meter ID</th>
							<th data-hide="expand">Year</th>
							<th>Month</th>
							<th data-hide="phone">Volume</th>
						</tr>
					</thead>
					<tbody>
<?php
foreach($temp_invoice[$mtid] as $kys=>$vls)
{
	foreach($vls as $kyy=>$vll)
	{
		if($_year==$kys and $_month==$kyy)
		{
			foreach($vll as $k=>$v)
			{

			?>
						<tr>
							<td><?php echo $mtid; ?></td>
							<td><?php echo $kys; ?></td>
							<td><?php echo $kyy; ?></td>
							<td><?php echo round($v,2); ?></td>
						</tr>
			<?php
			}
		}
	}
}
?>
					</tbody>
				</table>
			</div>
			
			<!-- new widget -->
			<div class="col-sm-6 col-md-6 col-lg-6 jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-fullscreenbutton="false" style="padding-left:5px;">
				<header>
					<span class="widget-icon"> <i class="glyphicon glyphicon-picture"></i> </span>
					<h2> Invoice </h2>
				</header>
					<!-- well -->
					<div class="well">
						<div id="myCarousel" class="carousel fade">
							<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1" class=""></li>
								<li data-target="#myCarousel" data-slide-to="2" class=""></li>
							</ol>
							<div class="carousel-inner">
								<!-- Slide 1 -->
								<div class="item active">
									<img src="assets/img/demo/meterid3_1.jpg" alt="">
									<!--<div class="carousel-caption caption-right">
										<h4>Title 1</h4>
										<p>
											Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
										</p>
										<br>
										<a href="javascript:void(0);" class="btn btn-info btn-sm">Read more</a>
									</div>-->
								</div>
								<!-- Slide 2 -->
								<div class="item">
									<img src="assets/img/demo/meterid3_2.jpg" alt="">
									<!--<div class="carousel-caption caption-left">
										<h4>Title 2</h4>
										<p>
											Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
										</p>
										<br>
										<a href="javascript:void(0);" class="btn btn-danger btn-sm">Read more</a>
									</div>-->
								</div>
								<!-- Slide 3 -->
								<div class="item">
									<img src="assets/img/demo/meterid3_3.jpg" alt="">
									<!--<div class="carousel-caption">
										<h4>A very long thumbnail title here to fill the space</h4>
										<br>
									</div>-->
								</div>
							</div>
							<a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
							<a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
						</div>

					</div>
					<!-- end well -->				
			</div>
		</article>
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->
<script type="text/javascript">
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();
	
	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 */
	
	// PAGE RELATED SCRIPTS
	
	// pagefunction	
	var pagefunction = function() {
		$('.carousel.slide').carousel({
			interval : 3000,
			cycle : true
		});
	};

	pagefunction();
</script>