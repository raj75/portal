<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION["user_id"];
$cname=$_SESSION["company_id"];

$chart_data_arr=array();

	$common_where = "AND cm.Status = 'Active' AND `End Date` >= NOW() order by `End Date` ASC LIMIT 5";
	
	if($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5){
		$sql = "SELECT DISTINCT
	cm.ContractID,
	cm.ClientID,
	cm.SupplierID,
	cm.AdvisorID,
	cm.MasterID,
	cm.STATUS,
	cm.`Initiated Date`,
	cm.`Start Date`,
	cm.`End Date`,
	cm.Product,
	sg.service_group,
	cm.Notes,
	IFNULL(vv.vendor_name,cm.SupplierID) AS supplier,
	cm.State,
	IFNULL(v.vendor_name,cm.VendorID) AS vendor_name,
	c.company_name
FROM
	contracts cm
JOIN
	company c
ON
	cm.ClientID = c.company_id
JOIN
	`user` u
ON
		c.company_id = u.company_id
JOIN
	service_group sg
ON
	sg.service_group_id = cm.Commodity 	
LEFT JOIN
 	vendor v
ON
 	v.vendor_id = cm.VendorID
LEFT JOIN
 	vendor vv
ON
 	vv.vendor_id = cm.SupplierID
WHERE
	u.user_id = '".mysqli_real_escape_string($mysqli,$user_one)."'
	AND cm.STATUS = 'Active'
	AND `End Date` >= NOW()
ORDER BY
	`End Date` ASC
LIMIT 5";
	}else{		
		$sql = "SELECT distinct cm.ContractID,cm.ClientID,cm.SupplierID,cm.AdvisorID,cm.MasterID,cm.Status,cm.`Initiated Date`,cm.`Start Date`,cm.`End Date`,cm.Product,sg.service_group,cm.Notes,v.vendor_name as supplier,cm.State,vv.vendor_name,c.company_name FROM contracts cm JOIN vendor v JOIN user u JOIN company c JOIN vendor vv JOIN service_group sg WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id and c.company_id=u.company_id and c.company_id=u.company_id and vv.vendor_id=cm.VendorID and sg.service_group_id=cm.Commodity $common_where";		
	}
	
	//echo $sql;
	//die();
	//if ($data_obj = $mysqli->prepare("SELECT name,from_date,to_date FROM contract_dates order by to_date desc limit 24")) {
	if ($data_obj = $mysqli->prepare($sql)) {

        $data_obj->execute();
        $data_obj->store_result();
        if ($data_obj->num_rows > 0) {
			
			//$data_obj->bind_result($name,$from_date,$to_date);
			$data_obj->bind_result($cm_ContractID,$cm_ClientID,$cm_SupplierID,$cm_AdvisorID,$cm_MasterID,$cm_Status,$cm_InitiatedDate,$cm_StartDate,$cm_EndDate,$cm_Product,$cm_Commodity,$cm_Notes,$v_vendor_name,$cm_State,$vv_vendor_name,$cm_company_name);
			
			// code start by amir
				
				$min_max_query = "SELECT min(`Start Date`) min , max(`End Date`) max FROM ($sql) as min_max";
			//echo $min_max_query;
				$min_max_obj = $mysqli->query($min_max_query);
				
				$min_max = $min_max_obj->fetch_array(MYSQLI_ASSOC);
				
				//$chart_min = date("m Y",strtotime($min_max['min']));
				//$chart_max = date("m Y",strtotime($min_max['max']));
				
				$chart_min = $min_max['min'];
				$chart_max = $min_max['max'];
				
				//$chart_max = "2020-12-31";
				
				if( $chart_max < date('Y-m-d') ) {
					$chart_max = date('Y-m-d', strtotime(' + 12 months'));
				}
				
				$countInd = 1;
				// code end by amir
				
			//$count = 1;
			while($data_obj->fetch()){
				//$data_arr[]='{"name": "'.$name.'","fromDate": '.date("d M Y",strtotime($from_date)).',"toDate": '.date("d M Y",strtotime($to_date)).'}';
				///$data_arr[]='{name: "'.$name.'",fromDate: "'.$from_date.'",toDate: "'.$to_date.'",color: colorSet.getIndex("'.rand(1,9).'").brighten(0)}';
				$chart_data_arr[]='{category: "Contract #'.$cm_ContractID.'",start: "'.$cm_StartDate.'",end: "'.$cm_EndDate.'",color: colorSet.getIndex('.$countInd.').brighten(0),state:"'.$cm_State.'"}';
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}

	if(!count($chart_data_arr)) die("No data to show!");
	
	

?>
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/frozen.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Chart code -->
<script>

			am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_frozen);
			am4core.useTheme(am4themes_animated);
			// Themes end

			//var chart = am4core.create("chartdiv", am4charts.XYChart);
			chart = am4core.create("chartdiv", am4charts.XYChart);
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

			chart.paddingRight = 30;
			chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm";

			//var colorSet = new am4core.ColorSet();
			colorSet = new am4core.ColorSet();
			colorSet.saturation = 0.4;
			
			chart.data = [<?php echo implode(",",$chart_data_arr);?>];
			

			//chart.dateFormatter.dateFormat = "dd/MM/yyyy";
			chart.dateFormatter.dateFormat = "dd/MM/yyyy";

			var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "category";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.inversed = true;

			//var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
			dateAxis = chart.xAxes.push(new am4charts.DateAxis());
			dateAxis.renderer.minGridDistance = 70;
			//dateAxis.renderer.minGridDistance = 20;
			
			
			dateAxis.renderer.labels.template.location = 0.0001;
			
			//dateAxis.renderer.grid.template.location = 0;
			//dateAxis.renderer.labels.template.verticalCenter = "middle";
			//dateAxis.renderer.labels.template.horizontalCenter = "left";
			
			
			
			dateAxis.renderer.labels.template.rotation = 320;
			
			
			
			dateAxis.renderer.labels.template.adapter.add("dx", function(dx, target) {
			  //if (target.dataItem && target.dataItem.index & 2 == 2) {
				//return dx;
				//return dx - 90;
				return dx - 30;
			  //}
			  //return dy;
			});
			
			
			//dateAxis.renderer.grid.template.disabled = true;
			
			//dateAxis.renderer.minGridDistance = 30;
			/////dateAxis.renderer.minGridDistance = 40;
			//dateAxis.baseInterval = { count: 1, timeUnit: "day" };
			dateAxis.baseInterval = { count: 30, timeUnit: "day" };
			////dateAxis.baseInterval = { count: 1, timeUnit: "day" };
			//timeUnit: "month", count: 1
			// dateAxis.max = new Date(2018, 0, 1, 24, 0, 0, 0).getTime();
			
			dateAxis.renderer.tooltipLocation = 0;
			//dateAxis.dateFormatter.dateFormat = "MM-yyyy";
			//dateAxis.dateFormats.setKey("day", "dd");
			dateAxis.dateFormats.setKey("month", "MMM yyyy");
			dateAxis.min = new Date('<?php echo $chart_min?>').getTime();
			dateAxis.max = new Date('<?php echo $chart_max?>').getTime();
			dateAxis.strictMinMax = true;
		
			
			dateAxis.gridIntervals.setAll([
			  { timeUnit: "month", count: 1 }
			]);

			var series1 = chart.series.push(new am4charts.ColumnSeries());
			series1.columns.template.height = am4core.percent(70);
			//series1.columns.template.tooltipText = "{task}: [bold]{openDateX}[/] - [bold]{dateX}[/]";
			series1.columns.template.tooltipText = "[bold]{openDateX}[/] - [bold]{dateX}[/]";

			//series1.columns.template.showTooltipOn = "always";
			series1.dataFields.openDateX = "start";
			series1.dataFields.dateX = "end";
			series1.dataFields.categoryY = "category";
			series1.columns.template.propertyFields.fill = "color"; // get color from data
			series1.columns.template.propertyFields.stroke = "color";
			series1.columns.template.strokeOpacity = 1;
			
			var valueLabel = series1.bullets.push(new am4charts.LabelBullet());
			valueLabel.label.text = "{state}";
			valueLabel.label.fontSize = 12;
			valueLabel.label.horizontalCenter = "center";
			//valueLabel.label.dx = 10;



			chart.scrollbarX = new am4core.Scrollbar();
			chart.scrollbarX.parent = chart.bottomAxesContainer;

			}); // end am4core.ready()

window.addEventListener("load", function(){
	//document.querySelector('g[aria-labelledby="id-210-title"]').style.display = "none";
	///document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
	try {
	  //document.querySelector('g[aria-label="Chart created using amCharts library"]').style.display = "none";
	  document.querySelector('g[aria-labelledby="id-66-title"]').style.display = "none";
	} catch (e) {}
	
});
</script>

<!-- HTML -->
<div id="chartdiv"></div>