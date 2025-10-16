<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

	$company_id = $_SESSION['company_id'];
	//$company_id = 46;
	
	$account_arr=array();
	if ($account_obj = $mysqli->prepare("SELECT b.company_id, DATE_FORMAT(b.period, '%b %Y') AS period, SUM(b.`usage`) AS `usage`, SUM(b.cost) AS cost
										FROM (
												SELECT
													a.company_id,
													a.period,
													a.`usage`,
													a.cost
												FROM
													widget_electric AS a
												WHERE a.period >= CURDATE() - INTERVAL 12 MONTH
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 0 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 3 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 4 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 5 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 6 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 7 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 8 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 9 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 10 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
												UNION ALL SELECT DISTINCT a.company_id, DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') AS period, 0 AS `usage`, 0 AS cost FROM widget_electric AS a
										) AS b
										where b.company_id = $company_id
										GROUP BY
												b.company_id,
												b.period
										ORDER BY
												b.company_id,
												b.period"
										)) {

//("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.id=".$user_one." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)"))

        $account_obj->execute();
        $account_obj->store_result();
        if ($account_obj->num_rows > 0) {
			$account_obj->bind_result($company_id,$period,$usage,$cost);
			while($account_obj->fetch()){
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				//$be_value=($be_value==""?0:$be_value);
				//$account_arr[]='{"period": "'.date("M Y",strtotime($period)).'","accounts": '.$accounts.'}';
				//$account_arr[]='{"last_login": "'.$last_login.'","num_users": "'.$num_users.'"}';
				$account_arr[]="{period: '$period', usage: $usage, cost: $cost}";
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//SELECT count(id),date(`EST Date`) FROM `exceptions` where `Customer ID`=10 and `Customer #`=315 group BY DATE_FORMAT(`EST Date`, '%Y%m') ORDER BY date(`EST Date`)
	if(!count($account_arr)) die("No data to show!");
?>





<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>

<!-- Resources -->
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/index.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/xy.js"></script>
<script src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/js/amchart/v5/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {


// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([am5themes_Animated.new(root)]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(
  am5xy.XYChart.new(root, {
    panX: "none",
    panY: "none",
    wheelX: "none",
    wheelY: "none",
    pinchZoom: false,
    paddingLeft: 0,
    layout: root.verticalLayout
  })
);

// Add chart title



// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
chart.set(
  "scrollbarX",
  am5.Scrollbar.new(root, {
    //orientation: "horizontal"
	 visible: false
  })
);

var data = [<?php echo implode(",",$account_arr);?>];

/*
var data = [
  {
    period: "2016",
    usage: 23.5,
    cost: 21.1
  },
  {
    period: "2017",
    usage: 26.2,
    cost: 30.5
  },
  {
    period: "2018",
    usage: 30.1,
    cost: 34.9
  },
  {
    period: "2019",
    usage: 29.5,
    cost: 31.1
  },
 */
  /*
  {
    year: "2020",
    income: 30.6,
    expenses: 28.2,
    strokeSettings: {
      stroke: chart.get("colors").getIndex(1),
      strokeWidth: 3,
      strokeDasharray: [5, 5]
    }
  },
  */
  /*
  {
    year: "2021",
    income: 34.1,
    expenses: 32.9,
    columnSettings: {
      strokeWidth: 1,
      strokeDasharray: [5],
      fillOpacity: 0.2
    },
    info: "(projection)"
  }
  */
 /*
];
*/

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {
  minorGridEnabled: true,
  minGridDistance: 60
});

xRenderer.labels.template.setAll({
  //rotation: -90,
  rotation: 320,
  //centerY: am5.p50,
  //centerX: am5.p100,
  //centerX: am5.p100,
  //paddingTop: 15
  //paddingRight: 15
});

var xAxis = chart.xAxes.push(
  am5xy.CategoryAxis.new(root, {
    categoryField: "period",
	
    renderer: xRenderer,
	/*
	 baseInterval: {
      timeUnit: "month",
      count: 1
    },
	*/
	
    tooltip: am5.Tooltip.new(root, {})
  })
);

xRenderer.grid.template.setAll({
  location: 1
})

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    min: 0,
    extraMax: 0.1,
    renderer: am5xy.AxisRendererY.new(root, {
      strokeOpacity: 0.1
    })
  })
);

yAxis.children.unshift(am5.Label.new(root, {
    text: 'kWh',
    textAlign: 'center',
    y: am5.p50,
    rotation: -90,
    //fontWeight: 'bold'
	fontSize: 13,
	fontWeight: "600",
	paddingBottom: 20
}));

chart.children.unshift(am5.Label.new(root, {
  text: "Cost vs Usage",
  fontSize: 13,
  fontWeight: "600",
  //textAlign: "center",
  x: am5.percent(50),
  centerX: am5.percent(50),
  paddingTop: 0,
  paddingBottom: 20
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

var series1 = chart.series.push(
  am5xy.ColumnSeries.new(root, {
    name: "Usage",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "usage",
    categoryXField: "period",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      //labelText: "{name} in {categoryX}: {valueY} {info}"
	  labelText: "{categoryX}: ${valueY}"
    })
  })
);

series1.columns.template.setAll({
  tooltipY: am5.percent(10),
  templateField: "columnSettings"
});

series1.data.setAll(data);

var series2 = chart.series.push(
  am5xy.LineSeries.new(root, {
    name: "Cost",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "cost",
    categoryXField: "period",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      //labelText: "{name} in {categoryX}: {valueY} {info}"
	  labelText: "{categoryX}: {valueY} kWh"
    })
  })
);

series2.strokes.template.setAll({
  strokeWidth: 3,
  templateField: "strokeSettings"
});


series2.data.setAll(data);

series2.bullets.push(function () {
  return am5.Bullet.new(root, {
    sprite: am5.Circle.new(root, {
      strokeWidth: 3,
      stroke: series2.get("stroke"),
      radius: 5,
      fill: root.interfaceColors.get("background")
    })
  });
});

chart.set("cursor", am5xy.XYCursor.new(root, {}));

series1.set("fill", am5.color("#64c4ed"));
series1.set("stroke", am5.color("#64c4ed"));
series2.set("fill", am5.color("#a5bdfd"));
series2.set("stroke", am5.color("#a5bdfd"));

// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(
  am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50
  })
);
legend.data.setAll(chart.series.values);

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);
series1.appear();


}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>