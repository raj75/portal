<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();



if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3)
	die("Restricted Access");
	
if(!$_SESSION['user_id'])
	die("Restricted Access");
	
$user_one=$_SESSION['user_id'];
?>
<style>
.oflow{overflow:hidden;}
</style>
<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="https://www.pjm.com/library/~/link.aspx?_id=0EA4BFBBA3AE41B49616035330F5D230&_z=z" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://www.ercot.com/content/cdr/contours/rtmLmp.html" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
</div>

<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="https://api.misoenergy.org/MISORTWD/lmpcontourmap.html" width="100%" height="610" frameBorder="0" scrolling="no" style="margin-top:-10%;"></iframe>
			</div>
		</article>
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://pricecontourmap.spp.org/pricecontourmap/" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
</div>

<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://www.nyiso.com/public/markets_operations/market_data/maps/index.jsp" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-6" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://www.caiso.com/PriceMap/Pages/default.aspx" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
</div>

<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-7" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="https://www.iso-ne.com/isoexpress/web/charts/guest-hub?p_p_id=lmpmapportlet_WAR_isonelmpmapportlet_INSTANCE_6n3S&p_p_lifecycle=0&p_p_state=pop_up&p_p_mode=view" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
		<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://www.ieso.ca/Power-Data" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
</div>
<div class="row">
		<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable oflow" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
				<iframe class="isoframe" src="http://ets.aeso.ca/ets_web/ip/Market/Reports/CSDReportServlet" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>
			</div>
		</article>
</div>