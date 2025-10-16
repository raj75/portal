<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

$stateabbr_arr=array();
$stmt_dsiremap = $mysqli->prepare('SELECT count(pg.state_id),s.abbreviation FROM dsireusa.`program` pg,dsireusa.state s where pg.state_id=s.id and pg.program_category_id=1 group by pg.state_id order by s.abbreviation');
$stmt_dsiremap->execute();
$stmt_dsiremap->store_result();
if ($stmt_dsiremap->num_rows > 0) {
  $stmt_dsiremap->bind_result($statecount,$stateabbr);
  while($stmt_dsiremap->fetch()) {
    if($stateabbr == "") continue;
    $stateabbr_arr[]=array("ct"=>$statecount,"state"=>$stateabbr);
  }
}


if(!count($stateabbr_arr)) die("Nothing to show!");
?>

<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px
}

</style>
<div id="chartdiv"></div>
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/usaLow.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

 // Create map instance
var chart = am4core.create("chartdiv", am4maps.MapChart);

// Set map definition
chart.geodata = am4geodata_usaLow;

// Set projection
chart.projection = new am4maps.projections.AlbersUsa();
chart.chartContainer.wheelable = false;
// Create map polygon series
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

//Set min/max fill color for each area
polygonSeries.heatRules.push({
  property: "fill",
  target: polygonSeries.mapPolygons.template,
  min: chart.colors.getIndex(1).brighten(1),
  max: chart.colors.getIndex(1).brighten(-0.3)
});

// Make map load polygon data (state shapes and names) from GeoJSON
polygonSeries.useGeodata = true;

// Set heatmap values for each state
polygonSeries.data = [
<?php
foreach ($stateabbr_arr as $key => $value) {
  echo '
  {id:"US-'.$value["state"].'",value:'.$value["ct"].'},
  ';
}
?>
];

// Set up heat legend
/*let heatLegend = chart.createChild(am4maps.HeatLegend);
heatLegend.series = polygonSeries;
heatLegend.align = "right";
heatLegend.valign = "bottom";
heatLegend.width = am4core.percent(20);
heatLegend.marginRight = am4core.percent(4);
heatLegend.minValue = 0;
heatLegend.maxValue = 40000000;*/

// Set up custom heat map legend labels using axis ranges
/*var minRange = heatLegend.valueAxis.axisRanges.create();
minRange.value = heatLegend.minValue;
minRange.label.text = "Little";
var maxRange = heatLegend.valueAxis.axisRanges.create();
maxRange.value = heatLegend.maxValue;
maxRange.label.text = "A lot!";

// Blank out internal heat legend value axis labels
heatLegend.valueAxis.renderer.labels.template.adapter.add("text", function(labelText) {
  return "";
});*/



// Configure series tooltip
var polygonTemplate = polygonSeries.mapPolygons.template;
polygonTemplate.tooltipText = "{name}: {value}";
polygonTemplate.nonScalingStroke = true;
polygonTemplate.strokeWidth = 0.5;
polygonTemplate.events.on("hit", function(ev) {
  var data = ev.target.dataItem.dataContext;
  if(data.value==0){alert("No records exists for "+data.name); }
  else{
    parent.$('#content').load('../assets/ajax/dsireusa.php?state='+data.id.replace("US-",""));
  }
});

// Create hover state and set alternative fill color
var hs = polygonTemplate.states.create("hover");
hs.properties.fill = am4core.color("#3c5bdc");

}); // end am4core.ready()
$(document).ready(function(){
  $('g[aria-labelledby$="-title"]').remove();
});
</script>
