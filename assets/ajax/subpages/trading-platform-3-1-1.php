<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(isset($_GET["req"]) and $_GET["req"]=="innerstable" and isset($_GET["cc"]) and !empty($_GET["cc"])){
	$clearingcode=@trim($_GET["cc"]);
	$ttrand=rand(20,500)."r".rand(20,500);
	$tmp_arr=$tmpccarr=$tempccarr=array();
	$tmpccarr=@explode(",",$clearingcode);
	if(count($tmpccarr)) foreach($tmpccarr as $vl) $tempccarr[]='"'.$mysqli->real_escape_string($vl).'"';

	$symlist=$temparrrr=array();
?>
<link href="<?php echo "https://".$_SERVER['HTTP_HOST']; ?>/assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_column3-1-1<?php echo $ttrand; ?>_filter, #datatable_fixed_column3-1-1<?php echo $ttrand; ?>-4_filter,.dataTables_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column3-1-1<?php echo $ttrand; ?>_length,#datatable_fixed_column3-1-1<?php echo $ttrand; ?>-4_length,.dataTables_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column3-1-1<?php echo $ttrand; ?>,#datatable_fixed_column3-1-1<?php echo $ttrand; ?>-4,.innerdtable{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column3-1-1<?php echo $ttrand; ?> option,#datatable_fixed_column3-1-1<?php echo $ttrand; ?>-4 option,.innerdtable option {
  color: #555;
}
#datatable_fixed_column3-1-1<?php echo $ttrand; ?> tr.dropdown select, #datatable_fixed_column3-1-1<?php echo $ttrand; ?>-4  tr.dropdown select,.innerdtable  tr.dropdown select{font-weight: 400 !important;}
.red{color:red;display:none;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}
td.details-control {
    background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
}
.stable, .stable th,.stable td {
  border: 1px solid black !important;
}
.stable th {
  background-color:#BDBDBD !important;
}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2<?php echo $ttrand; ?>" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2> </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding">
						<table id="datatable_fixed_column3-1-1<?php echo $ttrand; ?>" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr class="dropdown">
									<th class="hasinput d-1">
										<input type="text" class="form-control" placeholder="Filter CODE" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter DATE" />
									</th>
									<th class="hasinput d-3">
										<input type="text" class="form-control" placeholder="Filter SETTLEMENT" />
									</th>
								</tr>
								<tr>
									<th data-hide="phone">CODE</th>
									<th data-hide="phone">DATE</th>
									<th data-hide="phone">SETTLEMENT</th>
								</tr>
							</thead>
							<tbody>


<?php
	$sqlin='SELECT a.`code`,a.date,a.settlement FROM ubm_ice.AR_MWIS a WHERE a.code IN ('.implode(",",$tempccarr).') ORDER BY a.date DESC';
	if($stmtin = $mysqli->prepare($sqlin)) {
        $stmtin->execute();
        $stmtin->store_result();
        if($stmtin->num_rows > 0) {
			$stmtin->bind_result($tpsincode,$tpsindate,$tpsinsettlement);
			while($stmtin->fetch()) {
			$symlist[$tpsincode]=	$tpsincode;
			$temparrrr[]= '{"Volume":0,"Date":"'.$tpsindate.'","'.$tpsincode.'":"'.$tpsinsettlement.'"}';
?>
							<tr>
								<td><?php echo $tpsincode; ?></td>
								<td><?php echo $tpsindate; ?></td>
								<td><?php echo $tpsinsettlement; ?></td>
							</tr>
<?php
			}
		}
	}
?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
<script type="text/javascript">
	var tmpno=0;
	pageSetUp();


	// pagefunction
	var pagefunction = function() {
		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
			var otable<?php echo $ttrand; ?> = $("#datatable_fixed_column3-1-1<?php echo $ttrand; ?>").DataTable( {
				"lengthMenu": [[6, 12, -1], [6, 12, "All"]],
				"pageLength": 6,
				//"order": [[ 6, "desc" ]],
				"order": [],
				"dom": 'Blfrtip',
				"buttons": [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					{
						'extend': 'pdfHtml5',
						'title' : 'Vervantis_PDF',
						'messageTop': 'Vervantis PDF Export'
					},
					//'pdfHtml5'
					{
						'extend': 'print',
						//'title' : 'Vervantis',
						'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
					}/*,
					{
						'text': 'Columns',
						'extend': 'colvis',
						'columns': ':gt(0)'
					}*/
				],
				"autoWidth" : true
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column3-1-1<?php echo $ttrand; ?> thead th input[type=text]").on( 'keyup change', function () {

	        otable<?php echo $ttrand; ?>
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });
	};



	function multifilter(nthis,fieldname,otable<?php echo $ttrand; ?>)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otable<?php echo $ttrand; ?>
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#datatable_fixed_column3-1-1<?php echo $ttrand; ?> tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}
$(document).ready(function(){

});

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>
<?php
}if(isset($_GET["req"]) and $_GET["req"]=="innerschart" and isset($_GET["cc"]) and !empty($_GET["cc"])){
	$clearingcode=@trim($_GET["cc"]);
	$ttrand=rand(20,500)."r".rand(20,500);
	$tmp_arr=$tmpccarr=$tempccarr=array();
	$tmpccarr=@explode(",",$clearingcode);
	if(count($tmpccarr)) foreach($tmpccarr as $vl) $tempccarr[]='"'.$mysqli->real_escape_string($vl).'"';

	$symlist=$temparrrr=array();

	//$sqlin='SELECT a.`code`,a.date,a.settlement FROM ICE.AR_MWIS a WHERE a.code IN ('.implode(",",$tempccarr).') ORDER BY a.date ASC';
	$sqlin='SELECT d.`code`, d.date, d.settlement, c.legend FROM
(SELECT b.`code`, b.date,	b.settlement FROM	ubm_ice.AR_MWIS b WHERE	b.`code` IN ('.implode(",",$tempccarr).') ) d
LEFT JOIN
(SELECT a.`code`,CONCAT(DATE_FORMAT(STR_TO_DATE(a.`Month`, "%m"), "%b"), IF(a.contract_type="Monthly", " ", CONCAT(" ",a.`Day`,", ")), a.`Year`) AS legend FROM ubm_ice.clearing_code_index a WHERE a.`code` IN ('.implode(",",$tempccarr).')) c
ON c.`code`=d.`code`
ORDER BY d.date ASC';
	if($stmtin = $mysqli->prepare($sqlin)) {
        $stmtin->execute();
        $stmtin->store_result();
        if($stmtin->num_rows > 0) {
					$stmtin->bind_result($tpsincode,$tpsindate,$tpsinsettlement,$tpslegend);
					while($stmtin->fetch()) {
					//$symlist[$tpslegend]=	$tpslegend;
					$symlist[$tpsincode]=	array($tpsincode,$tpslegend);
					$temparrrr[]= '{"Volume":0,"Date":"'.$tpsindate.'","'.$tpsincode.'":"'.$tpsinsettlement.'"}';
			}
		}
	}
?>
<style>
body { background-color: #30303d; color: #fff;font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px; }
#chartdiv {
  width: 100%;
  height: 350px;
  max-width: 100%;
}
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/plugins/rangeSelector.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/dark.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dark);
am4core.useTheme(am4themes_animated);
// Themes end
am4core.options.onlyShowOnViewport = true;
// Create chart
var chart = am4core.create("chartdiv", am4charts.XYChart);
chart.padding(0, 15, 0, 15);
chart.preloader.disabled = true;
chart.logo.disabled=true;


chart.data = [
<?php if(isset($temparrrr) and count($temparrrr)) echo implode(',',$temparrrr); ?>
];

// the following line makes value axes to be arranged vertically.
chart.leftAxesContainer.layout = "vertical";

var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;
dateAxis.renderer.ticks.template.length = 8;
dateAxis.renderer.ticks.template.strokeOpacity = 0.1;
dateAxis.renderer.grid.template.disabled = true;
dateAxis.renderer.ticks.template.disabled = false;
dateAxis.renderer.ticks.template.strokeOpacity = 0.2;
dateAxis.renderer.minLabelPosition = 0.01;
dateAxis.renderer.maxLabelPosition = 0.99;
dateAxis.keepSelection = true;
dateAxis.minHeight = 30;

dateAxis.groupData = true;
dateAxis.minZoomCount = 5;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.tooltip.disabled = true;
valueAxis.zIndex = 1;
valueAxis.renderer.baseGrid.disabled = true;
// height of axis
valueAxis.height = am4core.percent(100);

valueAxis.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis.renderer.gridContainer.background.fillOpacity = 0.05;
valueAxis.renderer.inside = true;
valueAxis.renderer.labels.template.verticalCenter = "bottom";
valueAxis.renderer.labels.template.padding(2, 2, 2, 2);

//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis.renderer.fontSize = "0.8em"

<?php
if(isset($symlist) and count($symlist)){
	foreach($symlist as $kyty=>$vtvl){
		$ttt=str_replace(" ","",$kyty);
		?>
var seriess<?php echo $ttt; ?> = chart.series.push(new am4charts.LineSeries());
seriess<?php echo $ttt; ?>.dataFields.dateX = "Date";
seriess<?php echo $ttt; ?>.dataFields.valueY = "<?php echo $vtvl[0]; ?>";
seriess<?php echo $ttt; ?>.tooltipText = "<?php echo $vtvl[1]; ?> {valueY.value}";
//seriess<?php /*echo $kyty;*/ ?>.name = "MSFT: <?php /*echo $vtvl;*/ ?>";
seriess<?php echo $ttt; ?>.name = "<?php echo $vtvl[1]; ?>";
seriess<?php echo $ttt; ?>.defaultState.transitionDuration = 0;
<?php	}
}
?>

/*var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.tooltip.disabled = true;
// height of axis
valueAxis2.height = am4core.percent(35);
valueAxis2.zIndex = 3
// this makes gap between panels
valueAxis2.marginTop = 30;
valueAxis2.renderer.baseGrid.disabled = true;
valueAxis2.renderer.inside = true;
valueAxis2.renderer.labels.template.verticalCenter = "bottom";
valueAxis2.renderer.labels.template.padding(2, 2, 2, 2);
//valueAxis.renderer.maxLabelPosition = 0.95;
valueAxis2.renderer.fontSize = "0.8em"

valueAxis2.renderer.gridContainer.background.fill = am4core.color("#000000");
valueAxis2.renderer.gridContainer.background.fillOpacity = 0.05;*/

chart.cursor = new am4charts.XYCursor();

// Add range selector
var selector = new am4plugins_rangeSelector.DateAxisRangeSelector();
selector.container = document.getElementById("controls");
selector.axis = dateAxis;
selector.position = "bottom";

/*chart.exporting.menu = new am4core.ExportMenu();
chart.exporting.menu.items = [{
  "label": "Export",
  "menu": [
    { "type": "png", "label": "PNG" }
  ]
}];
chart.exporting.filePrefix = "";*/
$('#exportpng').off('click');
$('#exportpng').on('click', function(e) {
	chart.exporting.export("png");
});
$('#exportcsv').off('click');
$('#exportcsv').on('click', function(e) {
	chart.exporting.export("csv");
});

chart.legend = new am4charts.Legend();
}); // end am4core.ready()
</script>
<style>
#exportcont{
	margin-top: -13px;
	float: right;
	padding-right: 10px;
}
#exportpng,#exportcsv{
	appearance: auto;
	    -webkit-writing-mode: horizontal-tb !important;
	    text-rendering: auto;
	    color: -internal-light-dark(black, white);
	    letter-spacing: normal;
	    word-spacing: normal;
	    text-transform: none;
	    text-indent: 0px;
	    text-shadow: none;
	    display: inline-block;
	    text-align: center;
	    align-items: flex-start;
	    cursor: default;
	    background-color: -internal-light-dark(rgb(239, 239, 239), rgb(59, 59, 59));
	    box-sizing: border-box;
	    margin: 0em;
	    font: 400 13.3333px Arial;
	    padding: 1px 6px;
	    border-width: 2px;
	    border-style: outset;
	    border-color: -internal-light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
	    border-image: initial;
			margin: 0px 0px 0px 0.4em;
}
</style>
<!-- HTML -->
<div id="controls"></div>
<p id="exportcont">Export <button id="exportpng">PNG</button><button id="exportcsv">CSV</button></p>
<div id="chartdiv"></div>
<?php
}//else{ echo "<h3>Error Occured. Please try after sometime!</h3>";}
?>
