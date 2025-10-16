<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

	$company_id = $_SESSION['company_id'];
	//$company_id = 1;
	
	$account_arr=array();
	if ($account_obj = $mysqli->prepare("SELECT
											a.company_id,
											CASE a.last_login
												WHEN 0 THEN 'Total Number of Users'
												WHEN 1 THEN 'Active Users (<7 days)'
												WHEN 2 THEN 'Passive User (<30 days)'
												WHEN 3 THEN 'Inactive Users (>30 days)'
											END AS last_login,
											a.num_users
										FROM
											wigdet_num_users AS a
										WHERE company_id=$company_id"
										)) {

//("SELECT count(e.id),date(e.`EST Date`) FROM `exceptions` e, `user` up where up.company_id = e.`Customer ID` and up.id=".$user_one." group BY date(e.`EST Date`) ORDER BY date(e.`EST Date`)"))

        $account_obj->execute();
        $account_obj->store_result();
        if ($account_obj->num_rows > 0) {
			$account_obj->bind_result($company_id,$last_login,$num_users);
			while($account_obj->fetch()){
				//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
				//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
				//$be_value=($be_value==""?0:$be_value);
				//$account_arr[]='{"period": "'.date("M Y",strtotime($period)).'","accounts": '.$accounts.'}';
				//$account_arr[]='{"last_login": "'.$last_login.'","num_users": "'.$num_users.'"}';
				$account_arr[]='{"users": "'.$last_login.'","value": '.$num_users.'}';
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
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
    panX: "none",
    panY: "none",
    wheelX: "none",
    wheelY: "none",
    pinchZoom: false,
    paddingLeft: 0
}));

// We don't want zoom-out button to appear while animating, so we hide it
chart.zoomOutButton.set("forceHidden", true);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var yRenderer = am5xy.AxisRendererY.new(root, {
  minGridDistance: 30,
  minorGridEnabled: true
});

yRenderer.grid.template.set("location", 1);

var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0,
  //categoryField: "network",
  categoryField: "users",  
  renderer: yRenderer,
  tooltip: am5.Tooltip.new(root, { themeTags: ["axis"] })
}));

var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0,
  min: 0,
  numberFormatter: am5.NumberFormatter.new(root, {
    "numberFormat": "#,###a"
  }),
  extraMax: 0.1,
  renderer: am5xy.AxisRendererX.new(root, {
    strokeOpacity: 0.1,
    minGridDistance: 80

  })
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Series 1",
  xAxis: xAxis,
  yAxis: yAxis,
  valueXField: "value",
  //valueXField: "num_users",
  //categoryYField: "network",
  categoryYField: "users",
  tooltip: am5.Tooltip.new(root, {
    pointerOrientation: "left",
    labelText: "{valueX}"
  })
}));


// Rounded corners for columns
series.columns.template.setAll({
  cornerRadiusTR: 5,
  cornerRadiusBR: 5,
  strokeOpacity: 0
});

// Make each column to be of a different color
series.columns.template.adapters.add("fill", function (fill, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

series.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});


// Set data
var data = [<?php echo implode(",",$account_arr);?>];

/*
var data = [
  {
    "users": "Total Number of Users",
    "value": 7
  },
  {
    "users": "Active Users (<7 days)",
    "value": 2
  },
  {
    "users": "Passive User (<30 days)",
    "value": 1
  },
  {
    "users": "Inactive Users (>30 days)",
    "value": 13
  },
  /*
  {
    "network": "Reddit",
    "value": 355000000
  },
  {
    "network": "TikTok",
    "value": 500000000
  },
  {
    "network": "Tumblr",
    "value": 624000000
  },
  {
    "network": "Twitter",
    "value": 329500000
  },
  {
    "network": "WeChat",
    "value": 1000000000
  },
  {
    "network": "Weibo",
    "value": 431000000
  },
  {
    "network": "Whatsapp",
    "value": 1433333333
  },
  {
    "network": "YouTube",
    "value": 1900000000
  }
  */
  /*
];
*/

yAxis.data.setAll(data);
series.data.setAll(data);
//sortCategoryAxis();

// Get series item by category
function getSeriesItem(category) {
  for (var i = 0; i < series.dataItems.length; i++) {
    var dataItem = series.dataItems[i];
    if (dataItem.get("categoryY") == category) {
      return dataItem;
    }
  }
}

chart.set("cursor", am5xy.XYCursor.new(root, {
  behavior: "none",
  xAxis: xAxis,
  yAxis: yAxis
}));


// Axis sorting
function sortCategoryAxis() {

  // Sort by value
  series.dataItems.sort(function (x, y) {
    return x.get("valueX") - y.get("valueX"); // descending
    //return y.get("valueY") - x.get("valueX"); // ascending
  })

  // Go through each axis item
  am5.array.each(yAxis.dataItems, function (dataItem) {
    // get corresponding series item
    var seriesDataItem = getSeriesItem(dataItem.get("category"));

    if (seriesDataItem) {
      // get index of series data item
      var index = series.dataItems.indexOf(seriesDataItem);
      // calculate delta position
      var deltaPosition = (index - dataItem.get("index", 0)) / series.dataItems.length;
      // set index to be the same as series data item index
      dataItem.set("index", index);
      // set deltaPosition instanlty
      dataItem.set("deltaPosition", -deltaPosition);
      // animate delta position to 0
      dataItem.animate({
        key: "deltaPosition",
        to: 0,
        duration: 1000,
        easing: am5.ease.out(am5.ease.cubic)
      })
    }
  });

  // Sort axis items by index.
  // This changes the order instantly, but as deltaPosition is set,
  // they keep in the same places and then animate to true positions.
  yAxis.dataItems.sort(function (x, y) {
    return x.get("index") - y.get("index");
  });
}


// update data with random values each 1.5 sec
setInterval(function () {
  //updateData();
}, 1500)

/*
function updateData() {
  am5.array.each(series.dataItems, function (dataItem) {
    var value = dataItem.get("valueX") + Math.round(Math.random() * 1000000000 - 500000000);
    if (value < 0) {
      value = 500000000;
    }
    // both valueY and workingValueY should be changed, we only animate workingValueY
    dataItem.set("valueX", value);
    dataItem.animate({
      key: "valueXWorking",
      to: value,
      duration: 600,
      easing: am5.ease.out(am5.ease.cubic)
    });
  })

  sortCategoryAxis();
}
*/

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>