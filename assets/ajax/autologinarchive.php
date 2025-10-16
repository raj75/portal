<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();



if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access. Please contact Vervantis Support (support@vervantis.com)!");

if(!$_SESSION['user_id'])
	die("Restricted Access. Please contact Vervantis Support (support@vervantis.com)!");

$user_one=$_SESSION['user_id'];
$ucompany_id=$_SESSION['company_id'];


if($ucompany_id==9 or $ucompany_id==32){
	$cassurl="https://acme5.expensesmart.com/Default.aspx?ReturnUrl=%2f";
}else{
	$cassurl="https://www.expensesmart.com/Default.aspx?ReturnUrl=%2f";
}

$w=null;
if(isset($_GET["w"]) and $_GET["w"] == "d1befa03c79ca0b84ecc488dea96bc68")
	$w="Capturis";
elseif(isset($_GET["w"]) and $_GET["w"] == "fbce0bb98d18aca35b2938c78f52f57b")
	$w="Accuvio";
elseif(isset($_GET["w"]) and $_GET["w"] == "ytbefa03c79ca0b84ecc488dea96r390")
	$w="Archive";
else
	die("Restricted Access. Please contact Vervantis Support (support@vervantis.com)!");














$stmtat = $mysqli->prepare("SELECT up.accuvio_user,up.accuvio_pass,up.capturis_user,up.capturis_pass,up.capturis_archive_user,up.capturis_archive_pass,c.ubmarchive_type,c.ubm_type FROM user up,company c WHERE up.user_id= '".$user_one."' and c.company_id=up.company_id LIMIT 1");
if(!$stmtat){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}

$ch=null;
$referrer="https://eco.accuvio.com/Account/Login.aspx?ReturnUrl=%2f";
$agent="Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48";


//"SELECT up.accuvio_user,up.accuvio_pass,up.capturis_user,up.capturis_pass FROM user u WHERE u.id= '".$user_one."' LIMIT 1");"SELECT up.accuvio_user,up.accuvio_pass,up.capturis_user,up.capturis_pass FROM user u WHERE u.user_id= '".$user_one."' LIMIT 1");

$stmtat->execute();
$stmtat->store_result();
if ($stmtat->num_rows > 0) {
	$stmtat->bind_result($at_accuvio_user,$at_accuvio_pass,$at_capturis_user,$at_capturis_pass,$at_capturis_archive_user,$at_capturis_archive_pass,$at_ubmarchive,$at_ubm);
	$stmtat->fetch();
		if((@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "") or (@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "") or (@trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != "")){
///////test
if($w=="Accuvio" and @trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){
	//$at_accuvio_user=ed_crypt(@trim($at_accuvio_user),'d');
	//$at_accuvio_pass=ed_crypt(@trim($at_accuvio_pass),'d');
//echo "USer:".$at_accuvio_user."&&&&&".$at_accuvio_pass;die();

}
/////test ends




?>
<HTML>
<HEAD>
<TITLE><?php if($w=="Archive") echo "UBM Archive";elseif($w=="Capturis") echo "UBM Software";else echo $w; ?> Auto Login</TITLE>
<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
	loginForm();
    function loginForm() {
		<?php
			if(($w=="Archive" and @trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != "") or ($w=="Capturis" and @trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "") or ($w=="Accuvio" and @trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "")){?>
				$('.rbDecorated').click();
			<?php }else{?>
				alert("Incorrect User Login Information!");
				window.close();
			<?php }

			if(($w=="Archive" and @trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != "" and @trim($at_ubmarchive)=="None")){ ?>
				alert("None selected for UBM Archive Type!");
				window.close();
			<?php } ?>
    }
});
</script>
</HEAD>
<BODY>
	<h1><img src="../img/select2-spinner.gif" />Loading ...</h1>
    <form <?php if($w=="Archive" and @trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != ""){
			//echo 'action="https://cibill.nisc.coop/cgi-bin/wspd_cgi.sh/WService=wsDb1Ci1Setup1/cihtm/cihv.htm"';
			if(@trim($at_ubmarchive)=="Capturis") echo 'action="https://portal.capturis.com/web/cihtm/cihv.htm"';
			elseif(@trim($at_ubmarchive)=="Cass") echo 'action='.$cassurl;
			elseif(@trim($at_ubmarchive)=="None") {die("None selected for UBM Archive Type"); }
			$at_capturis_archive_user=ed_crypt(@trim($at_capturis_archive_user),'d');
			$at_capturis_archive_pass=ed_crypt(@trim($at_capturis_archive_pass),'d');
		}elseif($w=="Capturis" and @trim($at_capturis_user) != "" and @trim($at_capturis_pass) != ""){
			//echo 'action="https://cibill.nisc.coop/cgi-bin/wspd_cgi.sh/WService=wsDb1Ci1Setup1/cihtm/cihv.htm"';
			if(@trim($at_ubm)=="Capturis") echo 'action="https://portal.capturis.com/web/cihtm/cihv.htm"';
			elseif(@trim($at_ubm)=="Cass") echo 'action='.$cassurl;
			$at_capturis_user=ed_crypt(@trim($at_capturis_user),'d');
			$at_capturis_pass=ed_crypt(@trim($at_capturis_pass),'d');
		}elseif($w=="Accuvio" and @trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){
			echo 'action="https://eco.accuvio.com/Account/login.aspx?ReturnUrl=%2f"';
			$at_accuvio_user=ed_crypt(@trim($at_accuvio_user),'d');
			$at_accuvio_pass=ed_crypt(@trim($at_accuvio_pass),'d');
			//$at_accuvio_user=@trim($at_accuvio_user);
			//$at_accuvio_pass=@trim($at_accuvio_pass);
		}?>  method="post" style="display:none;">

		<?php
		if($w=="Archive" and @trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != ""){
			if(@trim($at_ubmarchive)=="Capturis"){
				?>
				<input type="hidden" size="25" name="emailid" tabindex="1" value="<?php echo @trim($at_capturis_archive_user); ?>" autocomplete="off">
				<input type="hidden" size="25" name="passwd" tabindex="2" value="<?php echo @trim($at_capturis_archive_pass); ?>" autocomplete="off">
				<input type="hidden" name="pref" value="">
				<input type="hidden" name="ssize">
				<input type="hidden" name="x" value="49">
				<input type="hidden" name="y" value="15">
				<input class="rbDecorated" type="submit" />
				<?php
			}elseif(@trim($at_ubmarchive)=="Cass"){
				$viewstate=$viewstateg=$ev="";
				$tmp_data = crawls($cassurl);
				if(preg_match("/<input[^<>]+name=\"__VIEWSTATE\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_viewstate))
				{
				  array_shift($tmp_viewstate);
				  $viewstate=$tmp_viewstate[0];
				}
				if(preg_match("/<input[^<>]+name=\"__VIEWSTATEGENERATOR\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_viewstateg))
				{
				  array_shift($tmp_viewstateg);
				  $viewstateg=$tmp_viewstateg[0];
				}
				if(preg_match("/<input[^<>]+name=\"__EVENTVALIDATION\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_ev))
				{
				  array_shift($tmp_ev);
				  $ev=$tmp_ev[0];
				}
		 ?>
				<input type="hidden" name="txtUserID" type="text" id="txtUserID" value="<?php echo @trim($at_capturis_archive_user); ?>" autocomplete="off">
				<input type="hidden" name="txtPassword" type="password" id="txtPassword" value="<?php echo @trim($at_capturis_archive_pass); ?>" autocomplete="off">
		    <input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
		    <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
		    <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
		    <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="<?php echo $viewstate; ?>" />
		    <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="<?php echo $viewstateg; ?>" />
		    <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="<?php echo $ev; ?>" />
		    <input class="rbDecorated"  type="submit" name="btnLogin" value="Login" id="btnLogin" />
			<?php
			}
		}elseif($w=="Capturis" and @trim($at_capturis_user) != "" and @trim($at_capturis_pass) != ""){
			if(@trim($at_ubm)=="Capturis"){
				?>
				<input type="hidden" size="25" name="emailid" tabindex="1" value="<?php echo @trim($at_capturis_user); ?>" autocomplete="off">
				<input type="hidden" size="25" name="passwd" tabindex="2" value="<?php echo @trim($at_capturis_pass); ?>" autocomplete="off">
				<input type="hidden" name="pref" value="">
				<input type="hidden" name="ssize">
				<input type="hidden" name="x" value="49">
				<input type="hidden" name="y" value="15">
				<input class="rbDecorated" type="submit" />
				<?php
			}elseif(@trim($at_ubm)=="Cass"){
				$viewstate=$viewstateg=$ev="";
				$tmp_data = crawls($cassurl);
				if(preg_match("/<input[^<>]+name=\"__VIEWSTATE\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_viewstate))
				{
				  array_shift($tmp_viewstate);
				  $viewstate=$tmp_viewstate[0];
				}
				if(preg_match("/<input[^<>]+name=\"__VIEWSTATEGENERATOR\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_viewstateg))
				{
				  array_shift($tmp_viewstateg);
				  $viewstateg=$tmp_viewstateg[0];
				}
				if(preg_match("/<input[^<>]+name=\"__EVENTVALIDATION\"[^<>]+value=\"([^<>\"]+)/s",$tmp_data,$tmp_ev))
				{
				  array_shift($tmp_ev);
				  $ev=$tmp_ev[0];
				}
		 ?>
				<input type="hidden" name="txtUserID" type="text" id="txtUserID" value="<?php echo @trim($at_capturis_user); ?>" autocomplete="off">
				<input type="hidden" name="txtPassword" type="password" id="txtPassword" value="<?php echo @trim($at_capturis_pass); ?>" autocomplete="off">
		    <input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
		    <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
		    <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
		    <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="<?php echo $viewstate; ?>" />
		    <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="<?php echo $viewstateg; ?>" />
		    <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="<?php echo $ev; ?>" />
		    <input class="rbDecorated"  type="submit" name="btnLogin" value="Login" id="btnLogin" />
			<?php
			}
		}elseif($w=="Accuvio" and @trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){
			$at_accuvio_user=@trim($at_accuvio_user);
			$at_accuvio_pass=@trim($at_accuvio_pass);
			$ori_at_accuvio_pass =$at_accuvio_pass;
			$at_accuvio_pass = str_replace("\\", "\\\\", $at_accuvio_pass);
			$at_accuvio_pass = str_replace("'", "", $at_accuvio_pass);
			$viewstate=$viewstateg=$ev=$tsm="";

			echo "Step 1";
			$tmp_data = sycrawls("https://eco.accuvio.com/login.aspx?ReturnUrl=%2f");
			if(preg_match("/id=\"__VIEWSTATE\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_viewstate))
			{
				array_shift($tmp_viewstate);
				$viewstate=$tmp_viewstate[0];
			}
			if(preg_match("/id=\"__VIEWSTATEGENERATOR\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_viewstateg))
			{
				array_shift($tmp_viewstateg);
				$viewstateg=$tmp_viewstateg[0];
			}
			if(preg_match("/id=\"__EVENTVALIDATION\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_ev))
			{
				array_shift($tmp_ev);
				$ev=$tmp_ev[0];
			}

			if(preg_match("/(\%3b\%3bSystem\.Web\.Extensions[^<>\"]+)\"/s",$tmp_data,$tmp_tsm))
			{
				array_shift($tmp_tsm);
				$tsm=urlencode($tmp_tsm[0]);
			}

			$fields = array(
					'__LASTFOCUS' => '',
					'ctl00_RadScriptManager1_TSM' => $tsm,
					'__EVENTTARGET' => 'ctl00$loginContentMiddle$RadNextButton',
					'__EVENTARGUMENT' => '',
					'__VIEWSTATE' => $viewstate,
					'__VIEWSTATEGENERATOR' => $viewstateg,
					'__VIEWSTATEENCRYPTED' => '',
					'__EVENTVALIDATION' => $ev,
					'ctl00_FormDecorator1_ClientState' => '',
					'ctl00$loginContentMiddle$UserName' => @trim($at_accuvio_user),
					'UserName_ClientState' => '{"enabled":true,"emptyMessage":"Email address","validationText":"'.@trim($at_accuvio_user).'","valueAsString":"'.@trim($at_accuvio_user).'","lastSetTextBoxValue":"'.@trim($at_accuvio_user).'"}',
					'RadNextButton_ClientState' => '{"text":"NEXT","value":"","checked":false,"target":"","navigateUrl":"","commandName":"","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":"mainLoginUsername","readOnly":false,"primary":false,"enabled":true}',
					'ctl00$loginContentMiddle$Password' => '',
					'Password_ClientState' => '{"enabled":true,"emptyMessage":"Password","validationText":"","valueAsString":"","lastSetTextBoxValue":""}',
					'RadBackButton_ClientState' => '{"text":"BACK","value":"","checked":false,"target":"","navigateUrl":"","commandName":"","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":null,"readOnly":false,"primary":false,"enabled":true}',
					'RadLoginButton_ClientState' => '{"text":"SIGN IN","value":"","checked":false,"target":"","navigateUrl":"","commandName":"Login","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":"mainLoginPassword","readOnly":false,"primary":false,"enabled":true}'
			);
			echo "Step 44";
			curljson("https://eco.accuvio.com/Account/Login.aspx/GetIsSsoUser",'{username: "'.@trim($at_accuvio_user).'"}');//die();
			echo "Step 33";
			$raw=scurl_post("https://eco.accuvio.com/login.aspx?ReturnUrl=%2f",$fields,1);
			//echo $raw;
			//die();
			echo "Step 2";
			$viewstate=$viewstateg=$ev=$tsm="";
			if($raw==false) die("Error");
			else $tmp_data=$raw;

			//die();
			if(preg_match("/id=\"__VIEWSTATE\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_viewstate))
			{
				array_shift($tmp_viewstate);
				$viewstate=$tmp_viewstate[0];
			}
			if(preg_match("/id=\"__VIEWSTATEGENERATOR\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_viewstateg))
			{
				array_shift($tmp_viewstateg);
				$viewstateg=$tmp_viewstateg[0];
			}
			if(preg_match("/id=\"__EVENTVALIDATION\" value=\"([^<>\"]+)\"/s",$tmp_data,$tmp_ev))
			{
				array_shift($tmp_ev);
				$ev=$tmp_ev[0];
			}

			if(preg_match("/(\%3b\%3bSystem\.Web\.Extensions[^<>\"]+)\"/s",$tmp_data,$tmp_tsm))
			{
				array_shift($tmp_tsm);
				$tsm=urlencode($tmp_tsm[0]);
			}


		?>

		<input type="text" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
		<input type="text" name="ctl00_RadScriptManager1_TSM" id="ctl00_RadScriptManager1_TSM" value="<?php echo $tsm; ?>" />
		<input type="text" name="__EVENTTARGET" id="__EVENTTARGET" value="ctl00$loginContentMiddle$RadLoginButton" />
		<input type="text" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
		<input type="text" name="__VIEWSTATE" id="__VIEWSTATE" value="<?php echo $viewstate; ?>" />



		<input type="text" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="<?php echo $viewstateg; ?>" />
		<input type="text" name="__VIEWSTATEENCRYPTED" id="__VIEWSTATEENCRYPTED" value="" />
		<input type="text" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="<?php echo $ev; ?>" />

		<input id="ctl00_FormDecorator1_ClientState" name="ctl00_FormDecorator1_ClientState" type="text" />






		<input id="ctl00$loginContentMiddle$UserName" name="ctl00$loginContentMiddle$UserName" value="<?php echo @trim($at_accuvio_user); ?>" />

		<input id="UserName_ClientState" name="UserName_ClientState" type="text" value='{"enabled":true,"emptyMessage":"Email address","validationText":"<?php echo @trim($at_accuvio_user); ?>","valueAsString":"<?php echo @trim($at_accuvio_user); ?>","lastSetTextBoxValue":"<?php echo @trim($at_accuvio_user); ?>"}' />

		<input id="RadNextButton_ClientState" name="RadNextButton_ClientState" type="text" value='{"text":"NEXT","value":"","checked":false,"target":"","navigateUrl":"","commandName":"","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":"mainLoginUsername","readOnly":false,"primary":false,"enabled":true}' />

		<input id="ctl00$loginContentMiddle$Password" name="ctl00$loginContentMiddle$Password" size="20" class="riTextBox riEnabled textbox1" title="" type="password" value="<?php echo @trim($ori_at_accuvio_pass); ?>" />

		<input id="Password_ClientState" name="Password_ClientState" type="text" value='{"enabled":true,"emptyMessage":"Password","validationText":"<?php echo @trim($at_accuvio_pass); ?>","valueAsString":"<?php echo @trim($at_accuvio_pass); ?>","lastSetTextBoxValue":"<?php echo @trim($at_accuvio_pass); ?>"}' />

		<input id="RadBackButton_ClientState" name="RadBackButton_ClientState" type="text" value='{"text":"BACK","value":"","checked":false,"target":"","navigateUrl":"","commandName":"","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":null,"readOnly":false,"primary":false,"enabled":true}' />

		<input id="RadLoginButton_ClientState" name="RadLoginButton_ClientState" type="text" value='{"text":"SIGN IN","value":"","checked":false,"target":"","navigateUrl":"","commandName":"Login","commandArgument":"","autoPostBack":true,"selectedToggleStateIndex":0,"validationGroup":"mainLoginPassword","readOnly":false,"primary":false,"enabled":true}' />

		<input class="rbDecorated" type="submit" name="ctl00$loginContentMiddle$RadLoginButton_input" id="RadLoginButton_input" value="SIGN IN" />

		<?php } ?>
    </FORM>
</BODY>
</HTML>
<?php
			}
}

function crawls($url)
{

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, getRandomUserAgent());
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,identity');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Accept-Charset:	ISO-8859-1,utf-8;q=0.7,*;q=0.7",
	  "Accept-Language:	en-us,en;q=0.5",
	  "Connection: keep-alive",
	  "Keep-Alive: 300",
	  "Expect:"
	));
	return curl_exec ($ch);
}


	function sycrawls($url)
	{
	  global $ch;
	  global $agent;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, true);
		//curl_setopt($ch, CURLOPT_USERAGENT, getRandomUserAgent());
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,identity');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Accept-Charset:	ISO-8859-1,utf-8;q=0.7,*;q=0.7",
		  "Accept-Language:	en-us,en;q=0.5",
		  "Connection: keep-alive",
		  "Keep-Alive: 300",
		  "Expect:"
		));
		return curl_exec ($ch);
	}

	function scurl_post($url,$fields,$nofollow=1){

	  //$ch = curl_init();
	  global $ch;
	  global $agent;
	  global $referrer;

	  /*$fields = array(
	      'field_name_1' => 'Value 1',
	      'field_name_2' => 'Value 2',
	      'field_name_3' => 'Value 3'
	  );*/

	  $fields_string = http_build_query($fields);

	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_POST, TRUE);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  //curl_setopt($ch, CURLOPT_USERAGENT, getRandomUserAgent());
	  if($nofollow==1) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	  else curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);


	  curl_setopt($ch, CURLOPT_REFERER, $referrer);
	  //curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

	  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
	  curl_setopt($ch, CURLOPT_USERAGENT, $agent);

	  if (curl_errno($ch)) {
	    //$error_msg = curl_error($ch);
	    $data=false;
	  }else{$data = curl_exec($ch); }
	  //curl_close($ch);
	  return $data;
	}

	function curljson($url,$data_string){
	  global $ch;
	  global $agent;
	  global $referrer;
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
	  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	  curl_setopt($ch, CURLOPT_REFERER, $referrer);

	  $result = curl_exec($ch);

	  //curl_close($ch);
	  return $result;
	}



function getRandomUserAgent()
{
    $userAgents=array(
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
        "Opera/9.20 (Windows NT 6.0; U; en)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
        "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48"
    );
    $random = rand(0,count($userAgents)-1);

    return $userAgents[$random];
}
?>
