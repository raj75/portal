<?php
require_once './db_connect.php';
require_once './functions.php';


if ($stmt = $mysqli->prepare("TRUNCATE TABLE  `cme_chart`")) {
	$stmt->execute();
}

runtwice("CO");
runtwice("NG");

function runtwice($energy_type=""){
	global $mysqli;
	$tmp_date=array();
	$ng_tmp="";
	if ($stmtt = $mysqli->prepare("SELECT _month,_year,date(datetime) FROM `cme_tmp` where energy_type='".$mysqli->real_escape_string($energy_type)."' group by date(datetime) ORDER by datetime")) {
		$stmtt->execute();
		$stmtt->store_result();
		if ($stmtt->num_rows > 0) {
			$ng_tmp=$ng_tmp. '<script>var chartData21 = [];chartData22 = [];chartData23 = [];</script>';
			$stmtt->bind_result($_dmonth,$_dyear,$_datetime);
			while($stmtt->fetch()) {
				$tmp_date[]=$_dmonth."@".$_dyear;
				if ($stmttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/12,1) As open, ROUND(sum(_last)/12,1) AS close, ROUND(sum(_low)/12,1) As low, ROUND(sum(_high)/12,1) As high, ROUND(sum(_volume)/12,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 1 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmttt->execute();
					$stmttt->store_result();
					if ($stmttt->num_rows > 0) {
						$stmttt->bind_result($_supdated,$sopen,$sclose,$slow,$shigh,$svolume);
						$stmttt->fetch();
	$ng_tmp=$ng_tmp.'
	<script>
				chartData21.push( {
				  "date": "'.$_supdated.'",
				  "open": "'.$sopen.'",
				  "close": "'.$sclose.'",
				  "low": "'.$slow.'",
				  "high": "'.$shigh.'",
				  "volume": "'.@str_replace(",","",$svolume).'",
				  "bullet": "round"
				} );
	</script>';

					}
				}

				if ($stmtttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/24,1) As open, ROUND(sum(_last)/24,1) AS close, ROUND(sum(_low)/24,1) As low, ROUND(sum(_high)/24,1) As high, ROUND(sum(_volume)/24,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 2 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmtttt->execute();
					$stmtttt->store_result();
					if ($stmtttt->num_rows > 0) {
						$stmtttt->bind_result($_s2updated,$s2open,$s2close,$s2low,$s2high,$s2volume);
						$stmtttt->fetch();
	$ng_tmp=$ng_tmp.'
	<script>
				chartData22.push( {
				  "date": "'.$_s2updated.'",
				  "open": "'.$s2open.'",
				  "close": "'.$s2close.'",
				  "low": "'.$s2low.'",
				  "high": "'.$s2high.'",
				  "volume": "'.@str_replace(",","",$s2volume).'",
				  "bullet": "triangleUp"
				} );
	</script>';

					}
				}

				if ($stmttttt = $mysqli->prepare("SELECT date(_updated),ROUND(sum(_open)/36,1) As open, ROUND(sum(_last)/36,1) AS close, ROUND(sum(_low)/36,1) As low, ROUND(sum(_high)/36,1) As high, ROUND(sum(_volume)/36,1) AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and date(datetime)='".$_datetime."' and date(CONCAT(_year,'-',month(str_to_date(_month,'%b')),'-01')) < DATE_ADD(date(CONCAT('".$_dyear."','-',month(str_to_date('".$_dmonth."','%b')),'-01')), INTERVAL 3 YEAR) ORDER BY id LIMIT 1 ")) {
					$stmttttt->execute();
					$stmttttt->store_result();
					if ($stmttttt->num_rows > 0) {
						$stmttttt->bind_result($_s3updated,$s3open,$s3close,$s3low,$s3high,$s3volume);
						$stmttttt->fetch();
	$ng_tmp=$ng_tmp.'
	<script>
				chartData23.push( {
				  "date": "'.$_s3updated.'",
				  "open": "'.$s3open.'",
				  "close": "'.$s3close.'",
				  "low": "'.$s3low.'",
				  "high": "'.$s3high.'",
				  "volume": "'.@str_replace(",","",$s3volume).'",
				  "bullet": "square"
				} );
	</script>';

					}
				}				
			}
		}
	}

	if($ng_tmp != ""){
			if ($stmtttt = $mysqli->prepare("INSERT INTO cme_chart SET id=null, energy_type='".$mysqli->real_escape_string($energy_type)."' , _values='".$mysqli->real_escape_string(preg_replace('/\s/', '',$ng_tmp))."'")) {
				$stmtttt->execute();
			}	
	}
	$ng_tmp=null;
	
	$tmp_date=array_values(array_unique($tmp_date));
	
	$length=count($tmp_date);
	for($i=0;$i<$length;$i++)
	{
		$ng_tmp="";
		$date_split=explode("@",$tmp_date[$i]);
		$ng_tmp=$ng_tmp.'<script>var chartData'.$i.' = [];</script>';
		if ($stmt = $mysqli->prepare("SELECT DATE(_updated), _open As open, _last AS close, _low As low, _high As high, _volume AS volume FROM cme_tmp WHERE energy_type='".$mysqli->real_escape_string($energy_type)."' and _month='".$mysqli->real_escape_string($date_split[0])."' and _year='".$mysqli->real_escape_string($date_split[1])."' ORDER BY id")) {
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($_updated,$open,$close,$low,$high,$volume);
				while($stmt->fetch()) {
	$ng_tmp=$ng_tmp.'
	<script>
					chartData'. $i.'.push( {
					  "date": "'.  $_updated.'",
					  "open": "'.  $open.'",
					  "close": "'.  $close.'",
					  "low": "'.  $low.'",
					  "high": "'.  $high.'",
					  "volume": "'.  @str_replace(",","",$volume).'"
					} );
	</script>';					
				}
				if ($stmttttt = $mysqli->prepare("INSERT INTO cme_chart SET id=null, energy_type='".$mysqli->real_escape_string($energy_type)."',_date='".$mysqli->real_escape_string($tmp_date[$i])."', _values='".$mysqli->real_escape_string(preg_replace('/\s/', '',$ng_tmp))."'")) {
					$stmttttt->execute();
				}
			}
		}
	}
}
//$thread = $mysqli->thread_id;

$mysqli->close();
?>