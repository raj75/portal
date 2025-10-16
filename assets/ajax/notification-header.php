<?php
//print_r($_POST);

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

//if(checkpermission($mysqli,54)==false) die("Permission Denied! Please contact Vervantis.");
//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	//die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

//print_r($_POST);
//die();

$user_one=$_SESSION["user_id"];

$where = "";

$notify_qry = "SELECT id,title,description FROM notifications WHERE status=0 AND CURDATE() between start_date AND end_date AND (for_userid = '$user_one' OR for_userid IS NULL OR for_userid < 1)";

// get all unread
/*
$unread_notify_qry = "SELECT title,description FROM notifications WHERE status=0 AND CURDATE() between start_date and end_date 
and id NOT IN (SELECT notification_id from notification_status where user_id = $user_one group by notification_id)";
*/

$unread_notify_qry = "SELECT id,title,description FROM notifications WHERE status=0 AND CURDATE() between start_date and end_date 
and id NOT IN (SELECT notification_id from notification_status where user_id = $user_one group by notification_id)";

//$sql = "SELECT Lastname, Age FROM Persons ORDER BY Lastname";
$unread_result = $mysqli -> query($unread_notify_qry);
$unread_ids = [];
// Numeric array
while ($unread_row = $unread_result -> fetch_array(MYSQLI_ASSOC)) {
	$unread_ids[] = $unread_row['id'];
}

//print_r($unread_ids);
//die();

//SELECT * FROM notifications n left join notification_status ns On n.id=ns.notification_id WHERE n.status=0 AND CURDATE() between start_date and end_date order by ns.notification_id

//for read
if (isset($_GET['read']) and $_GET['read']==1) {
	foreach ($unread_ids as $id) {
		$mysqli -> query("insert ignore into notification_status set notification_id = $id , user_id = $user_one ");
	}
	die();
}
//for counter
if (isset($_GET['notify']) and $_GET['notify']==1) {
	echo count ($unread_ids);
	die();
	/*
	if ($stmt = $mysqli->prepare($notify_qry)){
		$stmt->execute();
		$stmt->store_result();
		echo $stmt->num_rows;
		die();
	}
	*/
}
//for tabs
if (isset($_GET['t'])) {
	if ($_GET['t'] == 1) {
		$where = " and type=1";
	} elseif ($_GET['t'] == 2) {
		$where = " and type=2";
	}
	
}
?>

									<ul class="notification-body">
									<?php
									$unread = "";
									if ($stmt = $mysqli->prepare($notify_qry.$where)){
										$stmt->execute();
										$stmt->store_result();
										if ($stmt->num_rows > 0) {
											$stmt->bind_result($id,$title,$description);
											while($stmt->fetch()){
												//echo "<option value='".$__id."' ".($map_ClientId == $__id?"SELECTED='SELECTED'":'').">&nbsp;&nbsp;".$__companyname."</option>";
												if ( in_array($id,$unread_ids) ) {
													$unread = "unread";
												}
											
									?>
										<li>
											<span class="<?php echo $unread?>">
												<a href="javascript:void(0);" class="msg">
													<!--<img src="img/avatars/4.png" alt="" class="air air-top-left margin-top-5" width="40" height="40">
													<span class="from">John Doe <i class="icon-paperclip"></i></span>
													<time>2 minutes ago</time>-->
													<span class="subject"><?php echo $title;?></span>
													<span class="msg-body"><?php echo nl2br($description);?></span>
												</a>
											</span>
										</li>
									<?php
									
											} // end of while
										} // end of num rows
									} // end of first if
									
									?>
									
									</ul>
									