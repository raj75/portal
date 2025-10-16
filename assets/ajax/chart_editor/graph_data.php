<?php

$sql = "DESC sample_charting";

$result = $mysqli->query($sql);
//$obj = $result->fetch_object();
while($row = $result->fetch_array()){
    $col_names[] = $row['Field'];
}

//print_r($col_names);

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<!--create chart in iframe-->
	
	<iframe id="chart_iframe_id" src="assets/ajax/chart_editor/am_chart.php?gid=<?php echo $chart_gid?>&cid=<?php echo $chart_cid?>" class="lazy" name="chart_iframe"
	 marginwidth="0" marginheight="0" vspace="0" hspace="0" scrolling="no" 
	 allowtransparency="true" width="100%" height="500" frameborder="0">
	</iframe>
	
	
	<?php //include_once('./chart_editor/am_chart.php');?>
	
 </div>
 
 <!-- query section-->
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:100px;">
<!--
	<form target="chart_iframe" id="query_form" method="post" class="form-horizontal query_form" name="query_form" action="assets/ajax/chart_editor/am_chart.php?gid=<?php echo $chart_gid?>&cid=<?php echo $chart_cid?>">
-->
		<div class="form-group--">
		<label for="chart_query">Write Query</label>
		<textarea class="form-control" id="chart_query" name="chart_query" rows="4"><?php 
		if (isset($_SESSION['db_chart'])) {
			echo base64_decode($_SESSION['db_chart']['chart_query']);
		//} else if ( ($_GET['gid'] == 'area' || $_GET['gid'] == 'line') and ($_GET['cid'] == 5 || $_GET['cid'] == 6 || $_GET['cid'] == 7 || $_GET['cid'] == 8 || $_GET['cid'] == 9|| $_GET['cid'] == 10) ) {
		} else if ( ($_GET['gid'] == 'area' || $_GET['gid'] == 'line') and ($_GET['cid'] == 5 || $_GET['cid'] == 6 || $_GET['cid'] == 7 || $_GET['cid'] == 8 || $_GET['cid'] == 9) ) {
			echo "SELECT period as date,accounts as column_1,accounts*2 as column_2 from new_accounts order by date";
		} else {
			echo "SELECT period as category,accounts as column_1,accounts*2 as column_2 from new_accounts order by category";
		}
		?></textarea>
		<!--<textarea class="form-control" id="chart_query" name="chart_query" rows="4">SELECT period as category,accounts as column_1 from new_accounts order by category</textarea>-->
		</div>
		<button type="submit" class="btn btn-default">Submit Query</button>
<!--
	</form>
-->
	 <br>
 <br>
 </div>

 <!-- data section-->
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<!--create chart in iframe-->
	<!--
	<div id="data_div_id">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>category</th>
					<th>column-1</th>
					<th>column-2</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					//$sql = "select * from sample_charting";

					//$result = $mysqli->query($sql);
					
					//while($row = $result->fetch_array()){
						
				?>
				<tr>
					<td><?php //echo $row['category'];?></td>
					<td><?php //echo $row['column1'];?></td>
					<td><?php //echo $row['column2'];?></td>
				</tr>
				<?php //} ?>
			</tbody>
		</table>
	</div>
	-->
 </div>