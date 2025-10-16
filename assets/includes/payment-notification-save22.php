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

$tablename="payment_notifications.client_list";

$rawData = file_get_contents("php://input");

// Decode the JSON into a PHP associative array
$data = json_decode($rawData, true);

//Add New Scheduled Report
if(isset($data["new"]) and $data["new"]=="new")
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

if(isset($data["edit"]) and $data["rid"] and !empty($data["rid"]))
{
	$multipage_email=$include_invoice_images=$include_custom_files=$include_invoice_detail=$include_invoice_credits=$include_content=$include_payment=$include_gl_summary=$include_1st_csv=$include_alt_csv=$include_email_csv=$include_email_txt=$monday=$tuesday=$wednesday=$thursday=$friday=$only_attachment=$rid=0;
	
	$client_id=$ubm_client_id=null;
	
	$client_name=$ubm_name=$division=$s3_folder=$s3_folder_csv=$s3_folder_custom=$custom_title=$custom_content=$include_csv_prefix1=$include_csv_prefix2=$include_csv_prefix3=$include_csv_prefix4=$include_csv_prefix5=$include_csv_suffix1=$include_csv_suffix2=$include_csv_suffix3=$include_csv_suffix4=$include_csv_suffix5=""; 
	
	$error="Error occured";
	$sub_query=$new_value=$emaillist=array();
	$rid=$mysqli->real_escape_string(@trim($data['rid']));
	
	if(isset($data['rid']) and @trim($data['rid']) != "" and @trim($data['rid']) !=0 )
	{
		$rid=$mysqli->real_escape_string(@trim($data['rid']));
	}else{
		echo json_encode(array('error'=>'Error occured. Please try after sometimes!'));
		exit();		
	}	

    if ($stmt = $mysqli->prepare('SELECT client_id FROM '.$tablename.' where id="'.$rid.'" LIMIT 1')) {

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0) {
			echo json_encode(array('error'=>'Error Occured! Client not found.'));
			exit();
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Database error.'));
		exit();
	}
	
	if(isset($data['client_id']) and @trim($data['client_id']) != "" and @trim($data['client_id']) !=0 )
	{
		$client_id=$mysqli->real_escape_string(@trim($data['client_id']));
		$sub_query[]='client_id="'.$client_id.'"';
	}else{
		echo json_encode(array('error'=>'Please enter valid CLient ID'));
		exit();		
	}	
	
	
	if(isset($data['ubm_client_id']) and @trim($data['ubm_client_id']) != "" and @trim($data['ubm_client_id']) !=0 )
	{
		$ubm_client_id=$mysqli->real_escape_string(@trim($data['ubm_client_id']));
		$sub_query[]='ubm_client_id="'.$ubm_client_id.'"';
	}else{
		echo json_encode(array('error'=>'Please enter valid UBM CLient ID'));
		exit();		
	}
	
	if(isset($data['client_name']) and @trim($data['client_name']) != "" )
	{
		$client_name=$mysqli->real_escape_string(@trim($data['client_name']));
		$sub_query[]='client_name="'.$client_name.'"';
	}else{
		echo json_encode(array('error'=>'Please enter valid CLient Name'));
		exit();		
	}
	
	if(isset($data['ubm_name']) and @trim($data['ubm_name']) != "" )
	{
		$ubm_name=$mysqli->real_escape_string(@trim($data['ubm_name']));
		$sub_query[]='ubm_name="'.$ubm_name.'"';
	}else{
		echo json_encode(array('error'=>'Please enter valid UBM Name'));
		exit();		
	}	
	
	
	

	if(isset($data['division']) and @trim($data['division']) != "")
	{
		$division=$mysqli->real_escape_string(@trim($data['division']));
		$sub_query[]='division="'.$division.'"';
	}else $sub_query[]='division=""';
	
	if(isset($data['s3_folder']) and @trim($data['s3_folder']) != "")
	{
		$s3_folder=$mysqli->real_escape_string(@trim($data['s3_folder']));
		$sub_query[]='s3_folder="'.$s3_folder.'"';
	}else $sub_query[]='s3_folder=""';
	
	if(isset($data['s3_folder_csv']) and @trim($data['s3_folder_csv']) != "")
	{
		$s3_folder_csv=$mysqli->real_escape_string(@trim($data['s3_folder_csv']));
		$sub_query[]='s3_folder_csv="'.$s3_folder_csv.'"';
	}else $sub_query[]='s3_folder_csv=""';
	
	if(isset($data['s3_folder_custom']) and @trim($data['s3_folder_custom']) != "")
	{
		$s3_folder_custom=$mysqli->real_escape_string(@trim($data['s3_folder_custom']));
		$sub_query[]='s3_folder_custom="'.$s3_folder_custom.'"';
	}else $sub_query[]='s3_folder_custom=""';
	
	if(isset($data['custom_title']) and @trim($data['custom_title']) != "")
	{
		$custom_title=$mysqli->real_escape_string(@trim($data['custom_title']));
		$sub_query[]='custom_title="'.$custom_title.'"';
	}else $sub_query[]='custom_title=""';
	
	if(isset($data['custom_content']) and @trim($data['custom_content']) != "")
	{
		$custom_content=$mysqli->real_escape_string(@trim($data['custom_content']));
		$sub_query[]='custom_content="'.$custom_content.'"';
	}else $sub_query[]='custom_content=""';
	
	if(isset($data['include_csv_prefix1']) and @trim($data['include_csv_prefix1']) != "")
	{
		$include_csv_prefix1=$mysqli->real_escape_string(@trim($data['include_csv_prefix1']));
		$sub_query[]='include_csv_prefix1="'.$include_csv_prefix1.'"';
	}else $sub_query[]='include_csv_prefix1=""';
	
	if(isset($data['include_csv_prefix2']) and @trim($data['include_csv_prefix2']) != "")
	{
		$include_csv_prefix2=$mysqli->real_escape_string(@trim($data['include_csv_prefix2']));
		$sub_query[]='include_csv_prefix2="'.$include_csv_prefix2.'"';
	}else $sub_query[]='include_csv_prefix2=""';
	
	if(isset($data['include_csv_prefix3']) and @trim($data['include_csv_prefix3']) != "")
	{
		$include_csv_prefix3=$mysqli->real_escape_string(@trim($data['include_csv_prefix3']));
		$sub_query[]='include_csv_prefix3="'.$include_csv_prefix3.'"';
	}else $sub_query[]='include_csv_prefix3=""';
	
	if(isset($data['include_csv_prefix4']) and @trim($data['include_csv_prefix4']) != "")
	{
		$include_csv_prefix4=$mysqli->real_escape_string(@trim($data['include_csv_prefix4']));
		$sub_query[]='include_csv_prefix4="'.$include_csv_prefix4.'"';
	}else $sub_query[]='include_csv_prefix4=""';
	
	if(isset($data['include_csv_prefix5']) and @trim($data['include_csv_prefix5']) != "")
	{
		$include_csv_prefix5=$mysqli->real_escape_string(@trim($data['include_csv_prefix5']));
		$sub_query[]='include_csv_prefix5="'.$include_csv_prefix5.'"';
	}else $sub_query[]='include_csv_prefix5=""';
	
	if(isset($data['include_csv_suffix1']) and @trim($data['include_csv_suffix1']) != "")
	{
		$include_csv_suffix1=$mysqli->real_escape_string(@trim($data['include_csv_suffix1']));
		$sub_query[]='include_csv_suffix1="'.$include_csv_suffix1.'"';
	}else $sub_query[]='include_csv_suffix1=""';
	
	if(isset($data['include_csv_suffix2']) and @trim($data['include_csv_suffix2']) != "")
	{
		$include_csv_suffix2=$mysqli->real_escape_string(@trim($data['include_csv_suffix2']));
		$sub_query[]='include_csv_suffix2="'.$include_csv_suffix2.'"';
	}else $sub_query[]='include_csv_suffix2=""';
	
	if(isset($data['include_csv_suffix3']) and @trim($data['include_csv_suffix3']) != "")
	{
		$include_csv_suffix3=$mysqli->real_escape_string(@trim($data['include_csv_suffix3']));
		$sub_query[]='include_csv_suffix3="'.$include_csv_suffix3.'"';
	}else $sub_query[]='include_csv_suffix3=""';
	
	if(isset($data['include_csv_suffix4']) and @trim($data['include_csv_suffix4']) != "")
	{
		$include_csv_suffix4=$mysqli->real_escape_string(@trim(include_csv_suffix4));
		$sub_query[]='include_csv_suffix4="'.$include_csv_suffix4.'"';
	}else $sub_query[]='include_csv_suffix4=""';
	
	if(isset($data['include_csv_suffix5']) and @trim($data['include_csv_suffix5']) != "")
	{
		$include_csv_suffix5=$mysqli->real_escape_string(@trim($data['include_csv_suffix5']));
		$sub_query[]='include_csv_suffix5="'.$include_csv_suffix5.'"';
	}else $sub_query[]='include_csv_suffix5=""';




	
	if(isset($data['multipage_email']) and @trim($data['multipage_email']) != "" and @trim($data['multipage_email']) != 0)
	{
		$sub_query[]='multipage_email=1';
	}else $sub_query[]='multipage_email=0';
	
	if(isset($data['include_invoice_images']) and @trim($data['include_invoice_images']) != "" and @trim($data['include_invoice_images']) != 0)
	{
		$sub_query[]='include_invoice_images=1';
	}else $sub_query[]='include_invoice_images=0';	
	
	if(isset($data['include_custom_files']) and @trim($data['include_custom_files']) != "" and @trim($data['include_custom_files']) != 0)
	{
		$sub_query[]='include_custom_files=1';
	}else $sub_query[]='include_custom_files=0';
	
	if(isset($data['include_invoice_detail']) and @trim($data['include_invoice_detail']) != "" and @trim($data['include_invoice_detail']) != 0)
	{
		$sub_query[]='include_invoice_detail=1';
	}else $sub_query[]='include_invoice_detail=0';
	
	if(isset($data['include_invoice_credits']) and @trim($data['include_invoice_credits']) != "" and @trim($data['include_invoice_credits']) != 0)
	{
		$sub_query[]='include_invoice_credits=1';
	}else $sub_query[]='include_invoice_credits=0';
	
	if(isset($data['include_content']) and @trim($data['include_content']) != "" and @trim($data['include_content']) != 0)
	{
		$sub_query[]='include_content=1';
	}else $sub_query[]='include_content=0';	
	
	if(isset($data['include_payment']) and @trim($data['include_payment']) != "" and @trim($data['include_payment']) != 0)
	{
		$sub_query[]='include_payment=1';
	}else $sub_query[]='include_payment=0';
	
	if(isset($data['include_gl_summary']) and @trim($data['include_gl_summary']) != "" and @trim($data['include_gl_summary']) != 0)
	{
		$sub_query[]='include_gl_summary=1';
	}else $sub_query[]='include_gl_summary=0';
	
	if(isset($data['include_1st_csv']) and @trim($data['include_1st_csv']) != "" and @trim($data['include_1st_csv']) != 0)
	{
		$sub_query[]='include_1st_csv=1';
	}else $sub_query[]='include_1st_csv=0';
	
	if(isset($data['include_alt_csv']) and @trim($data['include_alt_csv']) != "" and @trim($data['include_alt_csv']) != 0)
	{
		$sub_query[]='include_alt_csv=1';
	}else $sub_query[]='include_alt_csv=0';	
	
	if(isset($data['include_email_csv']) and @trim($data['include_email_csv']) != "" and @trim($data['include_email_csv']) != 0)
	{
		$sub_query[]='include_email_csv=1';
	}else $sub_query[]='include_email_csv=0';
	
	if(isset($data['include_email_txt']) and @trim($data['include_email_txt']) != "" and @trim($data['include_email_txt']) != 0)
	{
		$sub_query[]='include_email_txt=1';
	}else $sub_query[]='include_email_txt=0';
	
	if(isset($data['monday']) and @trim($data['monday']) != "" and @trim($data['monday']) != 0)
	{
		$sub_query[]='monday=1';
	}else $sub_query[]='monday=0';
	
	if(isset($data['tuesday']) and @trim($data['tuesday']) != "" and @trim($data['tuesday']) != 0)
	{
		$sub_query[]='tuesday=1';
	}else $sub_query[]='tuesday=0';	
	
	if(isset($data['wednesday']) and @trim($data['wednesday']) != "" and @trim($data['wednesday']) != 0)
	{
		$sub_query[]='wednesday=1';
	}else $sub_query[]='wednesday=0';
	
	if(isset($data['thursday']) and @trim($data['thursday']) != "" and @trim($data['thursday']) != 0)
	{
		$sub_query[]='thursday=1';
	}else $sub_query[]='thursday=0';
	
	if(isset($data['friday']) and @trim($data['friday']) != "" and @trim($data['friday']) != 0)
	{
		$sub_query[]='friday=1';
	}else $sub_query[]='friday=0';
	
	if(isset($data['only_attachment']) and @trim($data['only_attachment']) != "" and @trim($data['only_attachment']) != 0)
	{
		$sub_query[]='only_attachment=1';
	}else $sub_query[]='only_attachment=0';
	
	
	if(isset($data['email_address']) and @trim($data['email_address']) != "")
	{
		$emaillist=explode(";",@trim($data['email_address']));

		if (!count($emaillist)) {
			// Not a valid email
			//echo json_encode(array('error'=>'The email address you entered is not valid'));
			echo json_encode(array('error'=>'Please enter the email address.'));
			exit();
		}else{
			foreach($emaillist as $emailvl){
				if (!filter_var(@trim($emailvl), FILTER_VALIDATE_EMAIL)){
					echo json_encode(array('error'=>'The email address you entered is not valid'));
					exit();
				}
			}

			$email_address=$mysqli->real_escape_string(@trim($data['email_address']));
			$sub_query[]='email_address="'.$email_address.'"';
		}
	}else{
		echo json_encode(array('error'=>'Error Occured! Email required.'));
		exit();
	}
	
	if(isset($data['email_cc']) and @trim($data['email_cc']) != "")
	{
		$emaillist=explode(";",@trim($data['email_cc']));

		if (!count($emaillist)) {
			// Not a valid email
			//echo json_encode(array('error'=>'The email address you entered is not valid'));
			//echo json_encode(array('error'=>'Please enter the email address.'));
			//exit();
		}else{
			foreach($emaillist as $emailvl){
				if (!filter_var(@trim($emailvl), FILTER_VALIDATE_EMAIL)){
					echo json_encode(array('error'=>'The email cc you entered is not valid'));
					exit();
				}
			}

			$email_cc=$mysqli->real_escape_string(@trim($data['email_cc']));
			$sub_query[]='email_cc="'.$email_cc.'"';
		}
	}else $sub_query[]='email_cc=""';
	

	if(count($sub_query)){
		$sql='UPDATE '.$tablename.' SET '.implode(",",$sub_query).' where id="'.$rid.'"';
		$stmt = $mysqli->prepare($sql);
		if($stmt){
			$stmt->execute();
			$lastuaffectedID=$stmt->affected_rows;
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
if(isset($data["rid"]) and !empty($data["rid"]) and isset($data["action"]) and @trim($data["action"])=="delete")
{

	$error="Error occured";
	$sub_query=array();

	$rid=$mysqli->real_escape_string(@trim($data["rid"]));
	if($group_id != 1 and $group_id != 2)
	{
		echo json_encode(array('error'=>'Error Occured! Not Authorized!.'));
		exit();
	}



	$stmtsk = $mysqli->prepare('Select id FROM '.$tablename.' where id="'.$rid.'" LIMIT 1');

	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{

			$stmtskks = $mysqli->prepare('DELETE FROM '.$tablename.' where id="'.$rid.'" LIMIT 1');

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
			echo json_encode(array('error'=>'Error Occured! Record doesn\'t exists.'));
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
