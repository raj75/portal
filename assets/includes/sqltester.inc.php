<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];


if(!isset($user_one) or !isset($group_id) or $group_id != 1)
{
	echo json_encode(array("error"=>"Restricted Access!"));
	exit();
}



if(isset($_POST["query"]) and isset($_POST["email"]))
{
	$query = @trim(@urldecode($_POST['query']));
	$emaillist = @trim(@urldecode($_POST['email']));
	$result=firesqlquery($emaillist,$query,HOST,USER,PASSWORD,DATABASE);
	if($result==false){ echo json_encode(array("error"=>"")); }
	else{ echo json_encode(array("error"=>$result)); }
	exit();
}

echo false;
exit();

function firesqlquery($email="",$sql_query="",$host="",$user="",$password="",$database=""){//return "Entered";
	//return $email.":".$host.":".$user.":".$password.":".$database.":".$sql_query;
	//if(empty($email) or empty($sql_query) or empty($host) or empty($user) or empty($password) or empty($database))
	//{return "Incorrect Data Provided."; exit(); }
//return $emaillist.":".$host.":".$user.":".$password.":".$database;
	$con = mysqli_connect($host,$user,$password,$database);
	if (mysqli_connect_errno()) {
	  return "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
	}

	$scheduled_report_name="TEST";


	$emaillist=explode(",",$email);
	if(!is_array($emaillist)) {return "Provide correct Email List."; exit(); }

	$ct=0;
	foreach($emaillist as $vl){
		$vl=@trim($vl);
		if (!filter_var($vl, FILTER_VALIDATE_EMAIL)) continue;
		$ct++;
	}
	if($ct==0) {return "Provide correct Email List."; exit(); }

	$result = mysqli_query($con, $sql_query);

	if(!$result){return "Sql error : " . mysqli_error(); }


	if(mysqli_num_rows($result) > 0){
		$csvheader=$list[]=array();

		while ($property = mysqli_fetch_field($result)) {
			$csvheader[]=$property->name;
		}

		$list[]=$csvheader;
		while( $row = mysqli_fetch_row( $result ) )
		{
			$list[]=$row;
		}

		$emailarr=$toattachment = $tolattachment=array();
		foreach($emaillist as $vl){
			$vl=@trim($vl);
			if (!filter_var($vl, FILTER_VALIDATE_EMAIL)) continue;
			if ($vl=="operations@vervantis.com") continue;
			$emailarr[]=array('name' => '', 'address' => $vl);
		}
		if(!count($emailarr) or !count($list)){
			mail("support@vervantis.com","Scheduled report csv failed","Scheduled report csv failed:".$scheduled_report_name);
			mail("bestwebsite777@gmail.com","Scheduled report csv failed","Scheduled report csv failed:".$scheduled_report_name);
			exit();
		}

		$temp_file = sys_get_temp_dir().'result'.time().'.csv';
		if (!$fp = fopen($temp_file, 'w+')) return FALSE;
		foreach ($list as $line) fputcsv($fp, $line);
		rewind($fp);

		$fsize=filesize($temp_file);

		$csvcustomfile = file_get_contents($temp_file);

		if ($csvcustomfile !== false){
			if($fsize > 3145728)
			$tolattachment[] = array('Name' => 'scheduled_report.csv', 'ContentType' => 'application/csv', 'Content' => $csvcustomfile,'Path'=>$temp_file,'size'=>$fsize);
			else
			$toattachment[] = array('Name' => 'scheduled_report.csv', 'ContentType' => 'application/csv', 'Content' => $csvcustomfile,'Path'=>$temp_file,'size'=>$fsize);
		}


		$mailArgs =  array('subject' => 'Scheduled report csv',
		    'replyTo' => array('name' => '', 'address' => 'noreply@vervantis.com'),
		    'toRecipients' => $emailarr,     // name is optional
		    'ccRecipients' => array(),     // name is optional, otherwise array of address=>email@address
		    'importance' => 'normal',
		    'conversationId' => '',   //optional, use if replying to an existing email to keep them chained properly in outlook
		    'body' => "Hi,<br> Please find ".$scheduled_report_name." csv<br><br>Support Vervantis",
		    'images' => array(),   //array of arrays so you can have multiple images. These are inline images. Everything else in attachments.
				'attachments' => $toattachment,
				'largeattachments' => $tolattachment
		  );

		if(custommsmail('noreply@vervantis.com', $mailArgs,'')==1){}
		else{
			mail("support@vervantis.com","Scheduled report csv failed","Scheduled report csv failed:".$scheduled_report_name);
			mail("bestwebsite777@gmail.com","Scheduled report csv failed","Scheduled report csv failed:".$scheduled_report_name);
		}

		//@unlink($temp_file);
	}else{ return "No Result Returned for Test"; exit(); }
	mysqli_free_result($result);

	mysqli_query($con, "INSERT INTO error_log set id=null, error='Y Finished TEST'");
	mysqli_close($con);

	return false;
}

function throwerror($error="",$con=null){
	mail("support@vervantis.com",$error,$error);
	mail("bestwebsite777@gmail.com",$error,$error);
	mysqli_query($con, "INSERT INTO error_log set id=null, error='".mysqli_real_escape_string($con,$error)."'");
	die($error);
}

function create_csv_string($data) {

  // Open temp file pointer
  if (!$fp = fopen('php://temp', 'w+')) return FALSE;

  // Loop data and write to file pointer
  foreach ($data as $line) fputcsv($fp, $line);

  // Place stream pointer at beginning
  rewind($fp);

  // Return the data
  return stream_get_contents($fp);

}

function send_csv_mail ($csvData, $body, $to = 'saurabh.singh@vervantis.com', $subject = 'Scheduled report csv', $from = 'support@vervantis.com') {

  // This will provide plenty adequate entropy
  $multipartSep = '-----'.md5(time()).'-----';

  // Arrays are much more readable
  $headers = array(
    "From: $from",
    "Reply-To: $from",
    "Content-Type: multipart/mixed; boundary=\"$multipartSep\""
  );

  // Make the attachment
  $attachment = chunk_split(base64_encode(create_csv_string($csvData)));

  // Make the body of the message
  $body = "--$multipartSep\r\n"
        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
        . "Content-Transfer-Encoding: 7bit\r\n"
        . "\r\n"
        . "$body\r\n"
        . "--$multipartSep\r\n"
        . "Content-Type: text/csv\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"file.csv\"\r\n"
        . "\r\n"
        . "$attachment\r\n"
        . "--$multipartSep--";

   // Send the email, return the result
   return mail($to, $subject, $body, implode("\r\n", $headers));

}

?>
