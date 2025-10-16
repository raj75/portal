<?php
require_once '../../../includes/db_connect.php';
require_once '../../../includes/functions.php';
	$tmp_dry_bulb=$tmp_wet_bulb=$tmp_datetime=$tmp_wban=$t_dry_bulb=$t_wet_bulb=array();
	if ($stmt = $mysqli->prepare('SELECT id,_wban,_datetime,_dry_bulb_farenheit,_wet_bulb_farenheit FROM `weather` where _wban=3011 order by _datetime')) { 
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {$m=0;
			$stmt->bind_result($id,$wban,$_datetime,$dry_bulb,$wet_bulb);
			while($stmt->fetch()) {
				$tmp_dry_bulb[]=$dry_bulb;
				$tmp_wet_bulb[]=$wet_bulb;
//$myDateTime = DateTime::createFromFormat('Y-m-d H:i:s',@trim($_datetime));
//$_datetime = $myDateTime->format('Y-m-d-h-i-s');
				$tmp_datetime[]=$_datetime;
				$tmp_wban[]=$wban;
				$t_dry_bulb[]=array("Time"=>$_datetime,"Temp"=>$dry_bulb);$m++;
			?>
			<?php
			}
		}
	}

	if(count($tmp_dry_bulb) and count($tmp_wet_bulb))
	{
		//echo $minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		$minF = floor(min(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
		//echo $maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)));
		$maxF = ceil(max(array_merge($tmp_dry_bulb,$tmp_wet_bulb)) / 20) * 20;
	}else
		die("Error occurred");
		
echo json_encode($t_dry_bulb);
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- EXTERNAL LIBS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>

    <!-- EXAMPLE SCRIPT -->
    <script>

      // onload callback
      function drawChart() {

        var public_key = 'dZ4EVmE8yGCRGx5XRX1W';

			results=<?php echo json_encode($t_dry_bulb); ?>;
          var data = new google.visualization.DataTable();

          data.addColumn('datetime', 'Time');
          data.addColumn('number', 'Temp');
          //data.addColumn('number', 'Wind Speed MPH');

          $.each(results, function (i, row) {
            data.addRow([
              (new Date(row.timestamp)),
              parseFloat(row.tempf)/*,
              parseFloat(row.windspeedmph)*/
            ]);
          });

          var chart = new google.visualization.LineChart($('#chart').get(0));

          chart.draw(data, {
            title: 'Wimp Weather Station'
          });
       /* // JSONP request
        var jsonData = $.ajax({
          url: 'https://data.sparkfun.com/output/' + public_key + '.json',
          data: {page: 1},
          dataType: 'jsonp',
        }).done(function (results) {

          var data = new google.visualization.DataTable();

          data.addColumn('datetime', 'Time');
          data.addColumn('number', 'Temp');
          data.addColumn('number', 'Wind Speed MPH');

          $.each(results, function (i, row) {
            data.addRow([
              (new Date(row.timestamp)),
              parseFloat(row.tempf),
              parseFloat(row.windspeedmph)
            ]);
          });

          var chart = new google.visualization.LineChart($('#chart').get(0));

          chart.draw(data, {
            title: 'Wimp Weather Station'
          });

        });*/

      }

      // load chart lib
      google.load('visualization', '1', {
        packages: ['corechart']
      });

      // call drawChart once google charts is loaded
      google.setOnLoadCallback(drawChart);

    </script>

  </head>
  <body>
    <div id="chart" style="width: 100%;"></div>
  </body>
</html>