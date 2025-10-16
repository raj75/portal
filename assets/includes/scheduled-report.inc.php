<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

//Restrict Other than Admin and Employee
if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
	$user_one=$_SESSION['user_id'];
	$group_id=$_SESSION['group_id'];
}else{
	echo false;
	exit();
}

//Add New Scheduled Report
if(isset($_POST["new"]) and $_POST["new"]=="new")
{

	$error="Error occured";
	$sub_query=$new_value=$emaillist=array();

	if(isset($_POST['schname']) and @trim($_POST['schname']) != "")
	{
		$schname=$mysqli->real_escape_string(@trim($_POST['schname']));
	   if ($stmt = $mysqli->prepare('SELECT id FROM `scheduled_reports` where scheduled_report_name="'.$schname.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				$sub_query[]='scheduled_report_name="'.$schname.'"';
				$new_value['scheduled_report_name']=$schname;
			}else{
				echo json_encode(array('error'=>'Error Occured! Scheduled Report Name already exist.'));
				exit();
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Name required.'));
		exit();
	}

	if(isset($_POST['schstatus']) and @trim($_POST['schstatus']) != "")
	{
		$schstatus=$mysqli->real_escape_string(@trim($_POST['schstatus']));

		if($schstatus != "Active" and $schstatus != "Inactive"){
			echo json_encode(array('error'=>'Error Occured! Invalid Status!'));
			exit();
		}
		$sub_query[]='status="'.$schstatus.'"';
		$new_value['status']=$schstatus;
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Status required.'));
		exit();
	}

	if(isset($_POST['last_run_status']) and @trim($_POST['last_run_status']) != "")
	{
		$last_run_status=$mysqli->real_escape_string(@trim($_POST['last_run_status']));

		if($last_run_status != "Pending" and $last_run_status != "Running" and $last_run_status != "Complete" and $last_run_status != "Failed"){
			echo json_encode(array('error'=>'Error Occured! Invalid Run Status!'));
			exit();
		}
		$sub_query[]='last_run_status="'.$last_run_status.'"';
		$new_value['last_run_status']=$last_run_status;
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Run Status required.'));
		exit();
	}

	if(isset($_POST['schdesc']) and @trim($_POST['schdesc']) != "")
	{
		$schdesc=$mysqli->real_escape_string(@trim($_POST['schdesc']));

		$sub_query[]='description="'.$schdesc.'"';
		$new_value['description']=$schdesc;
	}else{
		echo json_encode(array('error'=>'Error Occured! Description required.'));
		exit();
	}

	if(isset($_POST['schemails']) and @trim($_POST['schemails']) != "")
	{
		//$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		//$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		$email=@trim($_POST['schemails']);

		$emaillist=explode(",",$email);

		if (!count($emaillist)) {
			// Not a valid email
			//echo json_encode(array('error'=>'The email address you entered is not valid'));
			echo json_encode(array('error'=>'Please enter the email.'));
			exit();
		}else{
			foreach($emaillist as $emailvl){
				if (!filter_var(@trim($emailvl), FILTER_VALIDATE_EMAIL)){
					echo json_encode(array('error'=>'The email address you entered is not valid'));
					exit();
				}
			}

			$sub_query[]='email="'.$mysqli->real_escape_string(@trim($_POST['schemails'])).'"';
			$new_value['email']=$mysqli->real_escape_string(@trim($_POST['schemails']));
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Email required.'));
		exit();
	}

	if(isset($_POST['sqlquery']) and @trim($_POST['sqlquery']) != "")
	{
		$sqlquery=$mysqli->real_escape_string(@trim($_POST['sqlquery']));

		$sub_query[]='sql_query="'.$sqlquery.'"';
		$new_value['sql_query']=$sqlquery;
	}else{
		echo json_encode(array('error'=>'Error Occured! Sql Query required.'));
		exit();
	}

	if(isset($_POST['schmin']) and @trim($_POST['schmin']) != "")
	{
		$schmin=$mysqli->real_escape_string(@trim($_POST['schmin']));

		$sub_query[]='minute="'.$schmin.'"';
		$new_value['minute']=$schmin;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Minute required.'));
		exit();
	}

	if(isset($_POST['schhour']) and @trim($_POST['schhour']) != "")
	{
		$schhour=$mysqli->real_escape_string(@trim($_POST['schhour']));

		$sub_query[]='hour="'.$schhour.'"';
		$new_value['hour']=$schhour;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Hour required.'));
		exit();
	}

	if(isset($_POST['schday']) and @trim($_POST['schday']) != "")
	{
		$schday=$mysqli->real_escape_string(@trim($_POST['schday']));

		$sub_query[]='day="'.$schday.'"';
		$new_value['day']=$schday;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Day required.'));
		exit();
	}

	if(isset($_POST['schmonth']) and @trim($_POST['schmonth']) != "")
	{
		$schmonth=$mysqli->real_escape_string(@trim($_POST['schmonth']));

		$sub_query[]='month="'.$schmonth.'"';
		$new_value['month']=$schmonth;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Month required.'));
		exit();
	}

	if(isset($_POST['schweekday']) and @trim($_POST['schweekday']) != "")
	{
		$schweekday=$mysqli->real_escape_string(@trim($_POST['schweekday']));

		$sub_query[]='weekday="'.$schweekday.'"';
		$new_value['weekday']=$schweekday;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Week Day required.'));
		exit();
	}

	if(count($sub_query)){
		$sub_query[]='created_by="'.$user_one.'"';
		$new_value['created_by']=$user_one;

		audit_log($mysqli,"scheduled_reports","INSERT",$new_value,"","","");
		$sql='INSERT INTO scheduled_reports SET '.implode(",",$sub_query);
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			$insertid=$mysqli->insert_id;
			if($lastuaffectedID == 1){
				if($new_value['status']=="Active"){
					//addcron($new_value['minute']." ".$new_value['hour']." ".$new_value['day']." ".$new_value['month']." ".$new_value['weekday'],$insertid);
				}
				echo json_encode(array("error"=>""));
				exit();
			}else{
				echo json_encode(array("error"=>$error));
			}
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}
//Add Scheduled Report ends

if(isset($_POST["edit"]) and $_POST["schid"] and !empty($_POST["schid"]))
{

	$error="Error occured";
	$sub_query=$new_value=$emaillist=array();
	$schid=$mysqli->real_escape_string(@trim($_POST['schid']));

    if ($stmt = $mysqli->prepare('SELECT id FROM `scheduled_reports` where id="'.$schid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			echo json_encode(array('error'=>'Error Occured! Scheduled Report not exist.'));
			exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Database error.'));
		exit();
	}

	if(isset($_POST['schname']) and @trim($_POST['schname']) != "")
	{
		$schname=$mysqli->real_escape_string(@trim($_POST['schname']));
		$sub_query[]='scheduled_report_name="'.$schname.'"';
		$new_value['scheduled_report_name']=$schname;
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Name required.'));
		exit();
	}

	if(isset($_POST['schstatus']) and @trim($_POST['schstatus']) != "")
	{
		$schstatus=$mysqli->real_escape_string(@trim($_POST['schstatus']));

		if($schstatus != "Active" and $schstatus != "Inactive"){
			echo json_encode(array('error'=>'Error Occured! Invalid Status!'));
			exit();
		}
		$sub_query[]='status="'.$schstatus.'"';
		$new_value['status']=$schstatus;
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Status required.'));
		exit();
	}

	if(isset($_POST['last_run_status']) and @trim($_POST['last_run_status']) != "")
	{
		$last_run_status=$mysqli->real_escape_string(@trim($_POST['last_run_status']));

		if($last_run_status != "Pending" and $last_run_status != "Running" and $last_run_status != "Complete" and $last_run_status != "Failed"){
			echo json_encode(array('error'=>'Error Occured! Invalid Run Status!'));
			exit();
		}
		$sub_query[]='last_run_status="'.$last_run_status.'"';
		$new_value['last_run_status']=$last_run_status;
	}else{
		echo json_encode(array('error'=>'Error Occured! Scheduled Report Run Status required.'));
		exit();
	}

	if(isset($_POST['schcreatedby']) and @trim($_POST['schcreatedby']) != "")
	{
		$schcreatedby=$mysqli->real_escape_string(@trim($_POST['schcreatedby']));

		if($group_id !=1 and $group_id !=2) $schcreatedby=$user_one;

		$sub_query[]='created_by="'.$schcreatedby.'"';
		$new_value['created_by']=$schcreatedby;
	}else{
		echo json_encode(array('error'=>'Error Occured! Created By required.'));
		exit();
	}

	if(isset($_POST['schdate']) and @trim($_POST['schdate']) != "")
	{
		$schdate=$mysqli->real_escape_string(@trim($_POST['schdate']));

		$sub_query[]='created_date="'.$schdate.'"';
		$new_value['created_date']=$schdate;
	}else{
		echo json_encode(array('error'=>'Error Occured! Creation Date required.'));
		exit();
	}

	if(isset($_POST['schdesc']) and @trim($_POST['schdesc']) != "")
	{
		$schdesc=$mysqli->real_escape_string(@trim($_POST['schdesc']));

		$sub_query[]='description="'.$schdesc.'"';
		$new_value['description']=$schdesc;
	}else{
		echo json_encode(array('error'=>'Error Occured! Description required.'));
		exit();
	}

	if(isset($_POST['schemails']) and @trim($_POST['schemails']) != "")
	{
		//$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		//$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		$email=@trim($_POST['schemails']);

		$emaillist=explode(",",$email);

		if (!count($emaillist)) {
			// Not a valid email
			//echo json_encode(array('error'=>'The email address you entered is not valid'));
			echo json_encode(array('error'=>'Please enter the email.'));
			exit();
		}else{
			foreach($emaillist as $emailvl){
				if (!filter_var(@trim($emailvl), FILTER_VALIDATE_EMAIL)){
					echo json_encode(array('error'=>'The email address you entered is not valid'));
					exit();
				}
			}

			$sub_query[]='email="'.$mysqli->real_escape_string(@trim($_POST['schemails'])).'"';
			$new_value['email']=$mysqli->real_escape_string(@trim($_POST['schemails']));
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Email required.'));
		exit();
	}

	if(isset($_POST['sqlquery']) and @trim($_POST['sqlquery']) != "")
	{
		$sqlquery=$mysqli->real_escape_string(@trim($_POST['sqlquery']));

		$sub_query[]='sql_query="'.$sqlquery.'"';
		$new_value['sql_query']=$sqlquery;
	}else{
		echo json_encode(array('error'=>'Error Occured! Sql Query required.'));
		exit();
	}

	if(isset($_POST['schmin']) and @trim($_POST['schmin']) != "")
	{
		$schmin=$mysqli->real_escape_string(@trim($_POST['schmin']));

		$sub_query[]='minute="'.$schmin.'"';
		$new_value['minute']=$schmin;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Minute required.'));
		exit();
	}

	if(isset($_POST['schhour']) and @trim($_POST['schhour']) != "")
	{
		$schhour=$mysqli->real_escape_string(@trim($_POST['schhour']));

		$sub_query[]='hour="'.$schhour.'"';
		$new_value['hour']=$schhour;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Hour required.'));
		exit();
	}

	if(isset($_POST['schday']) and @trim($_POST['schday']) != "")
	{
		$schday=$mysqli->real_escape_string(@trim($_POST['schday']));

		$sub_query[]='day="'.$schday.'"';
		$new_value['day']=$schday;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Day required.'));
		exit();
	}

	if(isset($_POST['schmonth']) and @trim($_POST['schmonth']) != "")
	{
		$schmonth=$mysqli->real_escape_string(@trim($_POST['schmonth']));

		$sub_query[]='month="'.$schmonth.'"';
		$new_value['month']=$schmonth;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Month required.'));
		exit();
	}

	if(isset($_POST['schweekday']) and @trim($_POST['schweekday']) != "")
	{
		$schweekday=$mysqli->real_escape_string(@trim($_POST['schweekday']));

		$sub_query[]='weekday="'.$schweekday.'"';
		$new_value['weekday']=$schweekday;
	}else{
		echo json_encode(array('error'=>'Error Occured! Schedule Time Week Day required.'));
		exit();
	}

	if(count($sub_query)){
		$sub_query[]='modified_by="'.$user_one.'"';
		$new_value['modified_by']=$user_one;


		if ($stmt_sss = $mysqli->prepare("Select id,minute,hour,day,month,weekday From scheduled_reports where id=".$schid)) {
			$stmt_sss->execute();
			$stmt_sss->store_result();
			if ($stmt_sss->num_rows > 0) {
				$stmt_sss->bind_result($schtid,$schminute,$schhour,$schday,$schmonth,$schweekday);
				$stmt_sss->fetch();
				$oldcrondata=$schminute." ".$schhour." ".$schday." ".$schmonth." ".$schweekday;
			}else{ echo json_encode(array("error"=>$error)); exit(); }
		}else{ echo json_encode(array("error"=>$error)); exit(); }


		audit_log($mysqli,"scheduled_reports","UPDATE",$new_value,'WHERE id='.$schid,"","");
		$sql='UPDATE scheduled_reports SET '.implode(",",$sub_query).' where id="'.$schid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
			if($new_value['status']=="Active"){
				//editcron($oldcrondata,$new_value['minute']." ".$new_value['hour']." ".$new_value['day']." ".$new_value['month']." ".$new_value['weekday'],$schid);
			}else{
				//deletecron($oldcrondata,$schid);
			}
			echo json_encode(array("error"=>""));
		}else{
			echo json_encode(array("error"=>$error));
		}
		exit();
	}
}
//Edit Scheduled Report ends

function addcron($crondata,$ticketid){
	$output = shell_exec('crontab -l');
	$cron_file = "/var/www/datahub360/content/assets/testcron/crontab.txt";
	file_put_contents($cron_file, $output.$crondata." /usr/bin/php /var/www/datahub360/content/assets/testcron/".$ticketid.".php".PHP_EOL);
	exec("crontab $cron_file");

	$my_file = "/var/www/datahub360/content/assets/testcron/".$ticketid.".php";
	if(file_exists($my_file)) @unlink($my_file);
	$handle = fopen($my_file, 'w');
	if(!$handle){json_encode(array("error"=>"Cron File write error")); exit(); }
	$data = "<?php ini_set('memory_limit', '-1');set_time_limit(0);require_once('main.php');firesqlquery($ticketid); ?>";
	fwrite($handle, $data);
	fclose($handle);
	chmod($my_file, 0777);
}

function editcron($oldcrondata,$newcrondata,$ticketid){
	$output = shell_exec('crontab -l');
	$cron_file = "/var/www/datahub360/content/assets/testcron/crontab.txt";
	//$remove_cron = str_replace($oldcrondata." /usr/bin/php /var/www/datahub360/content/assets/testcron/".$ticketid.".php"."\n", "", $output);
	$output = preg_replace('/[^\n]+\/assets\/testcron\/'.$ticketid.'\.php/s','',$output);
	file_put_contents($cron_file, $output.PHP_EOL);
	file_put_contents($cron_file, $output.$newcrondata." /usr/bin/php /var/www/datahub360/content/assets/testcron/".$ticketid.".php".PHP_EOL);
	exec("crontab $cron_file");

	$my_file = "/var/www/datahub360/content/assets/testcron/".$ticketid.".php";
	if(!file_exists($my_file)){
		$handle = fopen($my_file, 'w');
		if(!$handle){json_encode(array("error"=>"Cron File write error")); exit(); }
		$data = "<?php ini_set('memory_limit', '-1');set_time_limit(0);require_once('main.php');firesqlquery($ticketid); ?>";
		fwrite($handle, $data);
		fclose($handle);
		chmod($my_file, 0777);
	}
}

function deletecron($crondata,$ticketid){
	$output = shell_exec('crontab -l');
	$cron_file = "/var/www/datahub360/content/assets/testcron/crontab.txt";
	//$remove_cron = str_replace($crondata." /usr/bin/php /var/www/datahub360/content/assets/testcron/".$ticketid.".php"."\n", "", $output);
	$output = preg_replace('/[^\n]+\/assets\/testcron\/'.$ticketid.'\.php/s','',$output);
	file_put_contents($cron_file, $output.PHP_EOL);
	exec("crontab $cron_file");

	$my_file = "/var/www/datahub360/content/assets/testcron/".$ticketid.".php";
	if(file_exists($my_file)) @unlink($my_file);
}

function deleteallcron(){
	exec("crontab -r");
}

//Delete Company
if(isset($_POST["sid"]) and !empty($_POST["sid"]) and isset($_POST["action"]) and @trim($_POST["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();

	$sid=$mysqli->real_escape_string(@trim($_POST["sid"]));
	if($group_id != 1)
	{
		echo json_encode(array('error'=>'Error Occured! Not Authorized!.'));
		exit();
	}



	$stmtsk = $mysqli->prepare('Select id,minute,hour,day,month,weekday From scheduled_reports where id="'.$sid.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtsk->bind_result($schtid,$schminute,$schhour,$schday,$schmonth,$schweekday);
			$stmtsk->fetch();
			$oldcrondata=$schminute." ".$schhour." ".$schday." ".$schmonth." ".$schweekday;
			//audit_log($mysqli,"scheduled_reports","DELETE","",'WHERE id="'.$sid.'" LIMIT 1',"","");

			$stmtskks = $mysqli->prepare('DELETE FROM scheduled_reports where id="'.$sid.'" LIMIT 1');

			if($stmtskks){
				$stmtskks->execute();
				$lastcaffectedID=$stmtskks->affected_rows;
				if($lastcaffectedID==1)
				{
					echo json_encode(array('error'=>''));
					//deletecron($oldcrondata,$sid);
					exit();
				}else{
					echo json_encode(array('error'=>'Error Occured! Database error.'));
					exit();
				}
			}else{
				echo json_encode(array('error'=>'Error Occured! Database error.'));
				exit();
			}
		}else{
			echo json_encode(array('error'=>'Error Occured! Scheduled Reports doesn\'t exists.'));
			exit();
		}
	}else{
			echo json_encode(array('error'=>'Error Occured! Database error.'));
			exit();
	}
}
//Delete Scheduled Reports ends

//print_r($_POST);
echo false;
exit();
?>
