<?php
require_once('db_connect.php');
require_once('functions.php');
sec_session_start();

/*define ('DBPATH','localhost');
define ('DBUSER','root');
define ('DBPASS','');
define ('DBNAME','vervantis');

session_start();*/
$dbh=$mysqli;
//global $dbh;
//$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
//mysql_selectdb(DBNAME,$dbh);

if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if ($_GET['action'] == "sendchat") { sendChat(); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession(); } 

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function chatHeartbeat() {
	global $dbh;
	$items = '';
	$chatBoxes = array();
	$sql = "select 'from','message','sent' FROM chat where (chat.to = '".$dbh->real_escape_string($_SESSION['user_id'])."' AND recd = 0) order by id ASC";
	//$query = mysqli_query($sql);
	$stmtt = $dbh->prepare($sql);
	$stmtt->execute();
	$stmtt->store_result();
	if ($stmtt->num_rows > 0) {
		$stmtt->bind_result($cFrom,$cMessage,$csent);
		while($stmtt->fetch()){
			if (!isset($_SESSION['openChatBoxes'][$cFrom]) && isset($_SESSION['chatHistory'][$cFrom])) {
				$items = $_SESSION['chatHistory'][$cFrom];
			}

		$cMessage = sanitize($cMessage);

$items .= <<<EOD
{
	"s": "0",
	"f": "{$cFrom}",
	"m": "{$cMessage}"
},
EOD;

		if (!isset($_SESSION['chatHistory'][$cFrom])) {
			$_SESSION['chatHistory'][$cFrom] = '';
		}

$_SESSION['chatHistory'][$cFrom] .= <<<EOD
				   {
	"s": "0",
	"f": "{$cFrom}",
	"m": "{$cMessage}"
},
EOD;

			unset($_SESSION['tsChatBoxes'][$cFrom]);
			$_SESSION['openChatBoxes'][$cFrom] = $cSent;		
		}
	}

if (!empty($_SESSION['openChatBoxes'])) {
foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
	if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
		$now = time()-strtotime($time);
		$time = date('g:iA M dS', strtotime($time));

		$message = "Sent at $time";
		if ($now > 180) {
			$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

if (!isset($_SESSION['chatHistory'][$chatbox])) {
	$_SESSION['chatHistory'][$chatbox] = '';
}

$_SESSION['chatHistory'][$chatbox] .= <<<EOD
	{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
		$_SESSION['tsChatBoxes'][$chatbox] = 1;
	}
	}
	}
}

	$sql = "update chat set recd = 1 where chat.to = '".$dbh->real_escape_string($_SESSION['user_id'])."' and recd = 0";
	//$query = mysql_query($sql);
	$stmt = $dbh->prepare($sql) ;
	$stmt->execute();

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo ((isset($_SESSION) and isset($_SESSION["fullname"]))?$_SESSION["fullname"]:$_SESSION["email"]);?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	global $dbh;
	$from = $_SESSION['user_id'];
	$to = $_POST['to'];
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}

	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);

	$sql = "insert into chat (chat.from,chat.to,message,sent) values ('".$dbh->real_escape_string($from)."', '".$dbh->real_escape_string($to)."','".$dbh->real_escape_string($message)."',NOW())";
	//$query = mysql_query($sql);
	$stmt = $dbh->prepare($sql) ;
	$stmt->execute();

	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}