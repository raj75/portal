<?php /*require_once("../ajax/inc/init.php");*/ ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
set_time_limit(0);

require '../../lib/s3/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

//error_reporting(0);
ini_set('max_execution_time', 0);
//require 'get_client.php';

$s3error=0;

if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){
?>
<!DOCTYPE html>
<link rel="stylesheet" type="text/css" media="screen" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/font-awesome.min.css">

<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
<link rel="stylesheet" type="text/css" media="screen" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/smartadmin-production-plugins.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/smartadmin-production.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/css/smartadmin-skins.min.css">

<style>
.s3filemanager .data.animated {
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}
.s3filemanager .data {
    z-index: -3;
}
.s3filemanager ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
	padding-left:0;
}

/* Chrome, Safari, Opera */
.s3filemanager @-webkit-keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

/* Standard syntax */
.s3filemanager @keyframes showSlowlyElement {
	100%   	{ transform: scale(1); opacity: 1; }
	0% 		{ transform: scale(1.2); opacity: 0; }
}

.s3filemanager .data li {
    border-radius: 3px;
    /*background-color: #373743;*/
    background-color: #fff;
    width: 307px;
    height: 118px;
    list-style-type: none;
    margin: 10px;
    display: inline-block;
    position: relative;
    overflow: hidden;
    padding: 0.3em;
    z-index: 1;
    cursor: pointer;
    box-sizing: border-box;
    transition: 0.3s background-color;
	width: 296px;
	width: 284px;
	border-radius: 1px;
	margin: 1px;
}

.s3filemanager li {
    display: list-item;
    text-align: -webkit-match-parent;
}

.s3filemanager .data li a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.s3filemanager a:-webkit-any-link {
    color: -webkit-link;
    cursor: pointer;
    text-decoration: underline;
}

.s3filemanager .icon.file.f-doc, .icon.file.f-docx, .icon.file.f-psd {
    box-shadow: 1.74em -2.1em 0 0 #03689b inset;
}

.s3filemanager .icon.file {
    width: 2.5em;
    height: 3em;
    line-height: 3em;
    text-align: center;
    border-radius: 0.25em;
    color: #fff;
    display: inline-block;
    margin: 0.9em 1.2em 0.8em 1.3em;
    margin: 0.9em 0.4em 0.8em 1.3em;
    position: relative;
    overflow: hidden;
    box-shadow: 1.74em -2.1em 0 0 #A4A7AC inset;
}

.s3filemanager .icon.file:first-line {
	font-size: 13px;
	font-weight: 700;
}
.s3filemanager .icon.file:after {
	content: '';
	position: absolute;
	z-index: -1;
	border-width: 0;
	border-bottom: 2.6em solid #DADDE1;
	border-right: 2.22em solid rgba(0, 0, 0, 0);
	top: -34.5px;
	right: -4px;
}
.s3filemanager .icon {
    font-size: 23px;
}

.s3filemanager .data li .name{
    line-height: 20px;
    /*width: 170px;*/
	width: auto;
    position: absolute;
    top: 40px;
}

.s3filemanager .data li .fname{
    color: #333;
    font-size: 15px;
    font-weight: 700;
	white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
	max-width: 206px;
	width:auto;
    display: inline-block;
	float:left;
}

.s3filemanager .data li .details {
    width: 55px;
    height: 10px;
    top: 60px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.s3filemanager .data li .details .fsize{
    color: #b6c1c9;
    font-size: 13px;
    font-weight: 400;
    width: auto;
    white-space: nowrap;
}

.s3filemanager .data li .ftime {
    color: #b6c1c9;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 77px;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
}

.s3filemanager .icon.file:after {
    content: '';
    position: absolute;
    z-index: -1;
    border-width: 0;
    border-bottom: 2.6em solid #DADDE1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    top: -34.5px;
    right: -4px;
}
.s3filemanager{
	font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
    font-size: 13px;
	color:#333;
}

.s3filemanager .icon.file.f-avi,
.s3filemanager .icon.file.f-flv,
.s3filemanager .icon.file.f-mkv,
.s3filemanager .icon.file.f-mov,
.s3filemanager .icon.file.f-mpeg,
.s3filemanager .icon.file.f-mpg,
.s3filemanager .icon.file.f-mp4,
.s3filemanager .icon.file.f-m4v,
.s3filemanager .icon.file.f-wmv {
	box-shadow: 1.74em -2.1em 0 0 #7e70ee inset;
}
.s3filemanager .icon.file.f-avi:after,
.s3filemanager .icon.file.f-flv:after,
.s3filemanager .icon.file.f-mkv:after,
.s3filemanager .icon.file.f-mov:after,
.s3filemanager .icon.file.f-mpeg:after,
.s3filemanager .icon.file.f-mpg:after,
.s3filemanager .icon.file.f-mp4:after,
.s3filemanager .icon.file.f-m4v:after,
.s3filemanager .icon.file.f-wmv:after {
	border-bottom-color: #5649c1;
}

.s3filemanager .icon.file.f-mp2,
.s3filemanager .icon.file.f-mp3,
.s3filemanager .icon.file.f-m3u,
.s3filemanager .icon.file.f-wma,
.s3filemanager .icon.file.f-xls,
.s3filemanager .icon.file.f-xlsx {
	box-shadow: 1.74em -2.1em 0 0 #5bab6e inset;
}
.s3filemanager .icon.file.f-mp2:after,
.s3filemanager .icon.file.f-mp3:after,
.s3filemanager .icon.file.f-m3u:after,
.s3filemanager .icon.file.f-wma:after,
.s3filemanager .icon.file.f-xls:after,
.s3filemanager .icon.file.f-xlsx:after {
	border-bottom-color: #448353;
}

.s3filemanager .icon.file.f-doc,
.s3filemanager .icon.file.f-docx,
.s3filemanager .icon.file.f-psd{
	box-shadow: 1.74em -2.1em 0 0 #03689b inset !important;
}

.s3filemanager .icon.file.f-doc:after,
.s3filemanager .icon.file.f-docx:after,
.s3filemanager .icon.file.f-psd:after {
	border-bottom-color: #2980b9;
}

.s3filemanager .icon.file.f-gif,
.s3filemanager .icon.file.f-jpg,
.s3filemanager .icon.file.f-jpeg,
.s3filemanager .icon.file.f-pdf,
.s3filemanager .icon.file.f-png {
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset !important;
}
.s3filemanager .icon.file.f-gif:after,
.s3filemanager .icon.file.f-jpg:after,
.s3filemanager .icon.file.f-jpeg:after,
.s3filemanager .icon.file.f-pdf:after,
.s3filemanager .icon.file.f-png:after {
	border-bottom-color: #c6393f;
}

.s3filemanager .icon.file.f-deb,
.s3filemanager .icon.file.f-dmg,
.s3filemanager .icon.file.f-gz,
.s3filemanager .icon.file.f-rar,
.s3filemanager .icon.file.f-zip,
.s3filemanager .icon.file.f-7z {
	box-shadow: 1.74em -2.1em 0 0 #867c75 inset;
}
.s3filemanager .icon.file.f-deb:after,
.s3filemanager .icon.file.f-dmg:after,
.s3filemanager .icon.file.f-gz:after,
.s3filemanager .icon.file.f-rar:after,
.s3filemanager .icon.file.f-zip:after,
.s3filemanager .icon.file.f-7z:after {
	border-bottom-color: #685f58;
}

.s3filemanager .icon.file.f-html,
.s3filemanager .icon.file.f-rtf,
.s3filemanager .icon.file.f-xml,
.s3filemanager .icon.file.f-xhtml {
	box-shadow: 1.74em -2.1em 0 0 #a94bb7 inset;
}
.s3filemanager .icon.file.f-html:after,
.s3filemanager .icon.file.f-rtf:after,
.s3filemanager .icon.file.f-xml:after,
.s3filemanager .icon.file.f-xhtml:after {
	border-bottom-color: #d65de8;
}

.s3filemanager .icon.file.f-js {
	box-shadow: 1.74em -2.1em 0 0 #d0c54d inset;
}
.s3filemanager .icon.file.f-js:after {
	border-bottom-color: #a69f4e;
}

.s3filemanager .icon.file.f-css,
.s3filemanager .icon.file.f-saas,
.s3filemanager .icon.file.f-scss {
	box-shadow: 1.74em -2.1em 0 0 #44afa6 inset;
}
.s3filemanager .icon.file.f-css:after,
.s3filemanager .icon.file.f-saas:after,
.s3filemanager .icon.file.f-scss:after {
	border-bottom-color: #30837c;
}

.s3filemanager .deleteit{
    position: absolute;
    left: 15px;
    top: 20px;
    color: red;
    cursor: pointer;
    z-index: 9999;
    width: 20px;
    height: 20px;
}

.s3filemanager .defaultcursor{cursor:default !important;}

.s3filemanager .pointercursor{cursor:pointer !important;}
.s3filemanager .editdesc,.s3filemanager .editfname {cursor:pointer !important;margin-left:2px;color:#1E90FF;vertical-align: top;}
.s3filemanager .editfname {
	float:left;
}

.s3filemanager .data li .fdesc{
    color: #000;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 20px !important;
    white-space: nowrap;
    position: absolute;
    display: inline-block;
	top: 4px;
}

.s3filemanager .data li .downloadf{
	margin-left:3px;
	color:#1E90FF;
	font-size:14px;
	cursor: pointer;
}

.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 6%;
    top: 0;
    width: 33%;
    z-index: 9999;
}

.fdesc span:first-child{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 145px !important;
    width: auto;
    display: inline-block;
}

.icon.folder {
    display: inline-block;
    margin: 1em;
	margin-right: 0.4em;
    background-color: transparent;
    overflow: hidden;
}

.icon.folder:before {
    content: '';
    float: left;
    background-color: #7ba1ad;
    width: 1.5em;
    height: 0.45em;
    margin-left: 0.07em;
    margin-bottom: -0.07em;
    border-top-left-radius: 0.1em;
    border-top-right-radius: 0.1em;
    box-shadow: 1.25em 0.25em 0 0em #7ba1ad;
}

.icon.folder:after {
    content: '';
    float: left;
    clear: left;
    background-color: #a0d4e4;
    width: 3em;
    height: 2.25em;
    border-radius: 0.1em;
}

.s3filemanager .breadcrumbs {
    color: #626569;
    margin-left: 20px;
    font-size: 24px;
    font-weight: 700;
    line-height: 35px;
	font-size: 19px;
}

.s3filemanager .nothingfound {
    background-color: #fff;
    border-radius: 10px;
    width: 23em;
    height: 21em;
    margin: 0 auto;
    display: none;
    font-family: Arial;
    -webkit-animation: showSlowlyElement 700ms;
    animation: showSlowlyElement 700ms;
}

.s3filemanager .nothingfound .nofiles {
    margin: 30px auto;
    top: 3em;
    border-radius: 50%;
    position: relative;
    background-color: #d72f6e;
    width: 11em;
    height: 11em;
    line-height: 11.4em;
}

.s3filemanager .nothingfound .nofiles:after {
    content: '×';
    position: absolute;
    color: #626569;
    font-size: 14em;
    margin-right: 0.092em;
    right: 0;
}

.s3filemanager .nothingfound span {
    margin: 0 auto auto 6.8em;
    color: #626569;
    font-size: 16px;
    font-weight: 700;
    line-height: 20px;
    height: 13px;
    position: relative;
    top: 2em;
}
.s3filemanager .showit{display:block !important;background:none !important;}
.placeright{float:right;}
.addfolder{line-height: 2;
    font-size: 19px;
    color: #626569;
    margin-right: 20px;}
.navback{margin-right:18px;cursor:pointer;}
.s3filemanager{background-color: #fff;}
body,html{background:#fff !important;}

#s3listview{margin-left:18px;}
#s3listview	.folder,#s3listview	.file{font-size:7px !important;}
#s3listview	.glyphicon-remove-circle{color:red;}
#s3listview	.glyphicon-download{color:#1E90FF;}
#s3listview	.editlfname{
	cursor: pointer !important;
    margin-left: 2px;
    color: #1E90FF;
    vertical-align: top;
}
#s3listview .icon.file:after {
    border-bottom-color: #c6393f;
}
#s3listview .icon.file:after{
	content: '';
    position: absolute;
    z-index: -1;
    border-width: 0;
    border-bottom: 2.6em solid #DADDE1;
    border-right: 2.22em solid rgba(0, 0, 0, 0);
    top: -7.5px;
    right: -4px;
}
.bold{font-weight:bold;}
#s3listview	.flength{
	text-overflow: ellipsis;
    overflow-x: hidden;
    max-width: 338px;
    display: inline-block;
    white-space: nowrap;
}
.txtright{text-align:right;}
.fileicon{font-size: 47px;
    margin-top: 42px;
    color: #D40202;
	margin-left: 20px;
    margin-right: -21px;
}
.fileicon .icontype{
	font-size: 14px;
    color: #fff;
    position: relative;
    left: -38px;
    top: -12px;
}
.listfileicon{
	font-size: 14px;
    color: #D40202;
    margin-right: -21px;
}
.icon-view{
    margin-top: 42px;
    color: #D40202;
	margin-left: 20px;
    margin-right: 10px;
	width: 43px !important;
    height: 51px !important;
    border-radius: 3px;
}
.icon-view::before{border-width: 8px !important;}
.icon-view::after{bottom: 16px !important;
    left: 10px !important;}
.listiconsview{width: 11px !important;
height: 13px !important;}



@charset "utf-8";
/*! fileicon.css v0.1.1 | MIT License | github.com/picturepan2/fileicon.css */
/* fileicon.basic */
.file-icon {
  font-family: Arial, Tahoma, sans-serif;
  font-weight: 300;
  display: inline-block;
  width: 24px;
  height: 32px;
  background: #018fef;
  position: relative;
  border-radius: 2px;
  text-align: left;
  -webkit-font-smoothing: antialiased;
}
.file-icon::before {
  display: block;
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 0;
  height: 0;
  border-bottom-left-radius: 2px;
  border-width: 5px;
  border-style: solid;
  border-color: #fff #fff rgba(255,255,255,.35) rgba(255,255,255,.35);
}
.file-icon::after {
  display: block;
  content: attr(data-type);
  position: absolute;
  bottom: 0;
  left: 0;
  font-size: 10px;
  color: #fff;
  text-transform: lowercase;
  width: 100%;
  padding: 2px;
  white-space: nowrap;
  overflow: hidden;
}
/* fileicons */
.file-icon-xs {
  width: 12px;
  height: 16px;
  border-radius: 2px;
}
.file-icon-xs::before {
  border-bottom-left-radius: 1px;
  border-width: 3px;
}
.file-icon-xs::after {
  content: "";
  border-bottom: 2px solid rgba(255,255,255,.45);
  width: auto;
  left: 2px;
  right: 2px;
  bottom: 3px;
}
.file-icon-sm {
  width: 18px;
  height: 24px;
  border-radius: 2px;
}
.file-icon-sm::before {
  border-bottom-left-radius: 2px;
  border-width: 4px;
}
.file-icon-sm::after {
  font-size: 7px;
  padding: 2px;
}
.file-icon-lg {
  width: 48px;
  height: 64px;
  border-radius: 3px;
}
.file-icon-lg::before {
  border-bottom-left-radius: 2px;
  border-width: 8px;
}
.file-icon-lg::after {
  font-size: 16px;
  padding: 4px 6px;
}
.file-icon-xl {
  width: 96px;
  height: 128px;
  border-radius: 4px;
}
.file-icon-xl::before {
  border-bottom-left-radius: 4px;
  border-width: 16px;
}
.file-icon-xl::after {
  font-size: 24px;
  padding: 4px 10px;
}
/* fileicon.types */
.file-icon[data-type=zip],
.file-icon[data-type=rar] {
  background: #acacac;
}
.file-icon[data-type^=doc] {
  background: #307cf1;
}
.file-icon[data-type^=xls] {
  background: #0f9d58;
}
.file-icon[data-type^=ppt] {
  background: #d24726;
}
.file-icon[data-type=pdf] {
  background: #e13d34;
}
.file-icon[data-type=txt] {
  background: #5eb533;
}
.file-icon[data-type=mp3],
.file-icon[data-type=wma],
.file-icon[data-type=m4a],
.file-icon[data-type=flac] {
  background: #8e44ad;
}
.file-icon[data-type=mp4],
.file-icon[data-type=wmv],
.file-icon[data-type=mov],
.file-icon[data-type=avi],
.file-icon[data-type=mkv] {
  background: #7a3ce7;
}
.file-icon[data-type=bmp],
.file-icon[data-type=jpg],
.file-icon[data-type=jpeg],
.file-icon[data-type=gif],
.file-icon[data-type=png] {
  background: #f4b400;
}
</style>



<?php
if(isset($_GET) and isset($_GET["company_id"]) and !empty($_GET["company_id"]) and  isset($_GET["docname"]) and isset($_SESSION["docname"]) and @trim($_SESSION["docname"]) != ""){
		$user_one=$_SESSION['user_id'];
		$group_id=$_SESSION['group_id'];
		$cname=$_SESSION['company_id'];

		$tmparr=$docarr=$navarr=array();
		$cft=$listview=0;
		$navtmp='';

		if(isset($_GET["listview"]) and $_GET["listview"]==1){
			$listview=1;
		}

		$homename=$_SESSION["docname"];
		$company_id = $mysqli->real_escape_string(@trim($_GET["company_id"]));
		$docname=$dcname=@trim(@urldecode($_GET["docname"]));

		if(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5) and $company_id != $cname) {echo 'Permission Denied!';exit();}

		if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$company_id)) {
			$stmtttt->execute();
			$stmtttt->store_result();
			if ($stmtttt->num_rows > 0) {
				$stmtttt->bind_result($company_name);
				$stmtttt->fetch();

			}else{echo 'Permission Denied!';exit();}
		}else{echo 'Permission Denied!';exit();}

		if(!empty($docname))
			$navurl = $docname = $homename.':'.$mysqli->real_escape_string($docname);
		else $navurl = $docname = $homename;

		//$navback=' onclick="navfolder(\'\')" ';
		$navback=' onclick="navfolder(\'\','.$company_id.','.$listview.')" ';

		$profile = 'default';
		//$path = '../../lib/s3/credentials.ini';

		//$provider = CredentialProvider::ini($profile, $path);
		//$provider = CredentialProvider::memoize($provider);

		$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
      'credentials' => [
           'key' => $_ENV['aws_access_key_id'],
           'secret' => $_ENV['aws_secret_access_key']
       ]
		]);

		if($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5) $company_id=$cname; else $cname=$company_id;

		if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$company_id)) {

			$stmtttt->execute();
			$stmtttt->store_result();
			if ($stmtttt->num_rows > 0) {
				$stmtttt->bind_result($company_name);
				$stmtttt->fetch();
			}else{echo false;exit();}
		}else{echo false;exit();}

		$docarr=@explode(':',$docname);

		//print_r($navurl);
		//print_r($homename);
		//print_r(@str_replace($homename,'',$navurl));
		//print_r(@str_replace($homename,'',$navurl));


		//$navarr=@explode(':',@trim(@str_replace($homename,'',$navurl),":"));a:b:c:d:e
		$navarr=@explode(':',@trim(@preg_replace('/'.$homename.'/', '', $navurl, 1),":"));
		$ctnav=(count($navarr)-1);

		if($ctnav+1){
			$tmpnavcopy=array();
			foreach($navarr as $kyy => $vll){
				if($ctnav != $kyy){
					$tmparr[]='<span class="pointercursor" onclick="navfolder(\''.@urlencode((($kyy != 0)?implode(':',$tmpnavcopy).':':'').$vll).'\','.$company_id.','.$listview.')">'.$vll.'</span>';
					$navback=' onclick="navfolder(\''.@urlencode((($kyy != 0)?implode(':',$tmpnavcopy).':':'').$vll).'\','.$company_id.','.$listview.')" ';
				}else $tmparr[]='<span>'.$vll.'</span>';

				$tmpnavcopy[]=$vll;
			}

			$navarr=$tmparr;
		}

		unset($tmparr);

		if(count($docarr))
		{
			$_SESSION['navurl']=$folder_n=implode('/',$docarr);//print_r($_SESSION['navurl']);

			$info = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/'.$folder_n.'/');
			if(!$info)
			{
				/*$s3Client->putObject([
					'Bucket' => 'datahub360',
					'Key'    => 'resources/Clients/'.$company_name.'/'.$folder_n.'/'
				]);*/
				//echo false;
				//exit();
			}
		}else{echo false;exit();}

				if($folder_n==""){echo "<p style='text-align:center;'>Nothing to show!</p>";}
				else{
							$curr_folder = array();
							$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/'.$folder_n.'/');
							if($infotarget)
							{
								$folderobjects = $s3Client->ListObjects(array( 'Bucket' => 'datahub360', 'Delimiter' => '/','Prefix'=>'resources/Clients/'.$company_name.'/'.$folder_n.'/'));
								$curr_folder = $folderobjects->get("CommonPrefixes");
								unset($folderobjects);

								$objects = $s3Client->getIterator('ListObjects', array(
									"Bucket" => "datahub360",
									"Prefix" => 'resources/Clients/'.$company_name.'/'.$folder_n.'/',
									'Delimiter' => '/'
								));

	$navtmp='<div class="placeright addfolder pointercursor"><span class="glyphicon glyphicon-list mr5" style="margin-right:5px;" onclick="listview(1)" title="List View"></span><span class="glyphicon glyphicon-th" onclick="listview(0)" title="Icons View"></span></div>';
if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){
	$navtmp=$navtmp.'<div class="placeright addfolder pointercursor" onclick="addfolder('.$company_id.')"><i class="fa fa-plus"></i> Add folder </div>';
}
$navtmp=$navtmp.'<div class="search" style="display:none;"><input type="search" placeholder="Find a file.." style="display: none;"></div><div class="breadcrumbs">';
if(!empty(@trim($dcname))){
	$navtmp=$navtmp.'<span class="glyphicon glyphicon-chevron-left navback" title="Back"'.$navback.'></span>';
}
$navtmp=$navtmp.'<span class="folderName">';
if(!empty(@trim($dcname))){
	$navtmp=$navtmp.'<span class="pointercursor" onclick="navfolder(\'\','.$company_id.','.$listview.')">Home</span> &gt; ';
}else $navtmp=$navtmp.'Home';
if(count($navarr)){
	$navtmp=$navtmp.implode(' &gt; ',$navarr);
}
$navtmp=$navtmp.'</span></div>';
//$navtmp=$navtmp.'<script>function navfolder(fnav){window.location.href ="s3browser.inc.php?docname="+fnav+"&company_id='.$company_id.'&ct='.rand(2,99).'";}</script>';
$navtmp=str_replace("'","\'",$navtmp);
?>
<div class="s3filemanager<?php if($listview==1){ ?> table-responsive<?php } ?>">

<?php if(1==2){ ?>
<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
	<div class="placeright addfolder pointercursor" onclick="addfolder()"><i class="fa fa-plus"></i> Add folder </div>
<?php } ?>
	<div class="search">
				<input type="search" placeholder="Find a file.." style="display: none;">
	</div>
	<div class="breadcrumbs">
		<?php if(!empty(@trim($dcname))){ ?>
			<span class="glyphicon glyphicon-chevron-left navback" title="Back"<?php echo $navback; ?>></span>
		<?php } ?>
		<span class="folderName"><?php echo (!empty(@trim($dcname))?'<span class="pointercursor" onclick="navfolder(\'\')">Home</span> &gt; ':'Home').(count($navarr)?implode(' &gt; ',$navarr):''); ?></span>
	</div>
<?php } ?>



<?php if($listview==1){ ?>
	<table width="98.5%" id="s3listview">
<?php }else{ ?>
	<ul class="data animated" style="">
<?php } ?>
<?php
								if(is_array($curr_folder)==true and count($curr_folder)){
									++$cft;
									foreach((array)$curr_folder as $kys=>$vls){
										if(preg_match('/(\/\/)+/',$vls['Prefix'],$nosave)) continue;
										$flname=basename($vls['Prefix']);
										$tsss=mt_rand(2,99).mt_rand(2,99);

										$fnav=@urlencode((!empty($dcname)?$dcname.':':'').$flname);
?>
<?php if($listview==1){ ?>
<tr>
	<td onclick="navfolder('<?php echo $fnav; ?>',<?php echo $listview; ?>)" width="20px"><span class="icon folder pointercursor"></span></td>
	<td class="clfname bold" width="36%">
		<span id="tfname<?php echo $tsss; ?>" class="pointercursor flength" onclick="navfolder('<?php echo $fnav; ?>',<?php echo $listview; ?>)"><?php echo $flname; ?></span>
	<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
		<span class="glyphicon glyphicon-edit editlfname"  onclick="editflname('<?php echo @addslashes($flname); ?>','tfname<?php echo $tsss; ?>')"></span>
	<?php } ?>
	</td>
	<td class="cldetails" width="30%"></td>
	<td class="cldetails"></td>
	<td class="cldetails"></td>
	<td>&nbsp;</td>
	<td><?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?><span class="glyphicon glyphicon-remove-circle pointercursor" onclick="fdeleteit('<?php echo $flname; ?>')" title="Delete"></span><?php } ?></td>
</tr>
<?php }else{ ?>
		<li class="files">
		<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
			<i class="fa fa-times deleteit" onclick="fdeleteit('<?php echo $flname; ?>')"></i>
		<?php } ?>
			<a href="javascript:void(0);" class="files defaultcursor">
				<span class="icon folder full pointercursor" onclick="navfolder('<?php echo $fnav; ?>',<?php echo $listview; ?>)"></span>

				<span class="fdesc">

				</span>

				<span class="name">
					<span id="tfname<?php echo $tsss; ?>" class="fname pointercursor"><?php echo $flname; ?></span>
				<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
					<span class="glyphicon glyphicon-edit editfname"  onclick="editflname('<?php echo @addslashes($flname); ?>','tfname<?php echo $tsss; ?>')"></span>
				<?php } ?>
				</span>

				<span class="details"></span>
				<span class="ftime"></span>
			</a>
		</li>
<?php } ?>



<?php
										/*$files[] = array(
											"name" => $fname,
											"type" => "folder",
											"path" => rtrim($homefolder,"/")."/".$fname,
											"items" => generatetree($foldername.$fname."/")
										);*/
									}
								}


								$tmpfilearr=array();//2019-06-23 00:52:45.000000  M d,Y h:i:sA
								foreach ($objects as $object){//var_dump($object);
									//if(preg_match('/(\/)$/s', $vl["Key"], $nosave)) continue;
									$fileext=pathinfo($object['Key'], PATHINFO_EXTENSION);
									if ($fileext){
										++$cft;
										$filebasename=basename($object['Key']);
										if(strpos($filebasename, '.') === (int) 0) continue;
										$headers = $s3Client->headObject(array(
											  "Bucket" => 'datahub360',
											  "Key" => $object['Key']
											));
										$headarr=$headers->toArray();
										$fdesc="";
										if(isset($headarr["Metadata"]) and isset($headarr["Metadata"]["fdesc"])){ $fdesc=$headarr["Metadata"]["fdesc"]; }

										$ftime=@date('M d, Y h:i:s A',strtotime('-4 hour',strtotime($object['LastModified']->format(\DateTime::ISO8601))));
										$tss=mt_rand(2,99).mt_rand(2,99);
										$filebname=pathinfo($filebasename, PATHINFO_FILENAME);
?>
<?php if($listview==1){ ?>
<tr>
	<td onclick="previewPop('<?php echo @addslashes($filebasename); ?>')" width="20px"><span class="file-icon file-icon-xs pointercursor listiconsview" data-type="<?php  echo $fileext; ?>"></span></td>
	<td class="clfname bold" width="35%">

		<span id="tname<?php echo $tss; ?>" class="fleft flength" onclick="previewPop('<?php echo @addslashes($filebasename); ?>')"><?php echo $filebasename; ?></span>

		<span class="glyphicon glyphicon-edit editlfname"  onclick="editfname('<?php echo @addslashes($filebasename); ?>','tname<?php echo $tss; ?>','<?php echo @addslashes($filebname); ?>','<?php echo $fdesc; ?>')"></span>

	</td>
	<td class="cldetails" width="30%">

		<span id="tdesc<?php echo $tss; ?>" class="flength"><?php echo (($fdesc=="" && ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2))?"Add Description":$fdesc); ?></span>

		<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
			<span class="glyphicon glyphicon-edit editlfname"  onclick="editdesc('<?php echo @addslashes($filebasename); ?>','tdesc<?php echo $tss; ?>','<?php echo $fdesc; ?>')"></span>
		<?php } ?>

	</td>
	<td class="cldetails"><span class="ftime"><?php echo $ftime; ?></span></td>
	<td class="cldetails txtright"><?php echo formatSizeUnits($object['Size']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;</td>
	<td><?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?><span class="glyphicon glyphicon-remove-circle pointercursor" onclick="deleteit('<?php echo $filebasename; ?>')" title="Delete"></span>&nbsp;&nbsp;<?php } ?><span class="glyphicon glyphicon-download downloadf pointercursor" onclick="downloadfiles('<?php echo @addslashes($filebasename); ?>')" title="Download"></span></td>
</tr>
<?php }else{ ?>
		<li class="files">
		<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
			<i class="fa fa-times deleteit" onclick="deleteit('<?php echo $filebasename; ?>')"></i>
		<?php } ?>
			<a href="javascript:void(0);" class="files defaultcursor">
				<span class="pointercursor file-icon icon-view" data-type="<?php  echo $fileext; ?>" onclick="previewPop('<?php echo @addslashes($filebasename); ?>')"  title="Click to preview: <?php echo $filebasename; ?>"></span>

				<span class="fdesc">
					<span id="tdesc<?php echo $tss; ?>"><?php echo (($fdesc=="" && ($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2))?"Add Description":$fdesc); ?></span>
				<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
					<span class="glyphicon glyphicon-edit editdesc"  onclick="editdesc('<?php echo @addslashes($filebasename); ?>','tdesc<?php echo $tss; ?>','<?php echo $fdesc; ?>')"></span>
				<?php } ?>
				</span>

				<span class="name">
					<span id="tname<?php echo $tss; ?>" class="fname"><?php echo $filebasename; ?></span>
				<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
					<span class="glyphicon glyphicon-edit editfname"  onclick="editfname('<?php echo @addslashes($filebasename); ?>','tname<?php echo $tss; ?>','<?php echo @addslashes($filebname); ?>','<?php echo $fdesc; ?>')"></span>
				<?php } ?>
				</span>

				<span class="details"><span class="fsize"><?php echo formatSizeUnits($object['Size']); ?></span><span class="glyphicon glyphicon-download downloadf" onclick="downloadfiles('<?php echo @addslashes($filebasename); ?>')" title="Download"></span></span>
				<span class="ftime"><?php echo $ftime; ?></span>
			</a>
		</li>
<?php } ?>
<?php
									}
								}
?>
<?php if($listview==0){ ?>
	</ul>
<?php }else{ ?>
	</table>
<?php } ?>
	<div class="nothingfound <?php if($cft == 0){echo 'showit';} ?>">
		<div class="nofiles"></div>
		<span>No files here.</span>
	</div>
</div>
<script type="text/JavaScript" src="../../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script>
async function editflname(fldname,tfname){
	const {value: eflname} = await Swal.fire({
	title: 'Enter folder name',
	  input: 'text',
	  inputPlaceholder: 'Enter folder name...',
	  inputValue: fldname,
	  showCancelButton: true,
	  inputValidator: (value) => {
		if (!value) {
		  return 'Folder name cannot be empty!'
		}
	  }
	})

	if (eflname) {
		if(eflname != ''){
			$.post("s3filepermissionpart2.inc.php",
			{
			  foldername: fldname,
			  ticket: <?php echo $company_id; ?>,
			  fvalue: eflname,
			  type: "s3clfoldernameedit"
			},
			function(rurl){
			  if(rurl == true){
				//if(value==""){value="Add Description";}
				//$('#'+descid+'').text(value);
				Swal.fire("","Folder edited successfully!", "success");
				//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
				location.reload(true);
			  }else if(rurl==9){Swal.fire("","Folder name already exists!", "warning");}
				else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
		}else{Swal.fire("","Filename cannot be empty!", "warning");}
	}
}

async function addfolder(){
	const {value: afold} = await Swal.fire({
	title: 'Enter folder name',
	  input: 'text',
	  inputPlaceholder: '',
	  //inputValue: ovalue,
	  showCancelButton: true,
	  inputValidator: (value) => {
		if (!value) {
		  return 'Folder name cannot be empty!'
		}
	  }
	})

	if (afold) {
		if(afold != ''){
		$.post("s3filepermission.inc.php",
		{
		  ticket: <?php echo $company_id; ?>,
		  fvalue: afold,
		  type: "s3clfolderadd"
		},
		function(rurl){
		  if(rurl == true){
			//if(value==""){value="Add Description";}
			//$('#'+descid+'').text(value);
			Swal.fire("","Folder added successfully!", "success");
			//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
			location.reload(true);
		  }else if(rurl == 9){
			 Swal.fire("","Error Occured! Foldername already exists.", "warning");
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
		});
		}
	}
	//location.reload(true);
}
function downloadfiles(filename){
	window.location.href ="filedownload.inc.php?filename="+filename+"&ticket=<?php echo $company_id; ?>&type=s3cldownload";
}

function navfolder(fnav,ltype){
	window.location.href ="s3browser.inc.php?docname="+fnav+"&company_id=<?php echo $company_id; ?>&ct=<?php echo rand(2,99); ?>&listview="+ltype;
}

<?php if($_SESSION["group_id"] == 1 || $_SESSION["group_id"] == 2){ ?>
async function fdeleteit(foldername){
	Swal.fire({
	  title: 'Are you sure?',
	  text: "Once deleted, you will not be able to recover this folder!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Delete'
	}).then((result) => {
	  if (result.value) {
		$.post("s3filepermissionpart2.inc.php",
		{
		  s3clfoldername: foldername,
		  ticket: <?php echo $company_id; ?>,
		  type: "s3clfoldelete"
		},
		function(rurl){
		  if(rurl == true){
			//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
			Swal.fire("","Deleted Successfully!", "success");
			location.reload(true);
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
		});
	  }else {
		Swal.fire("Your folder is safe!");
	  }
	})
	//Swal.fire("Under Construction!");
}

async function deleteit(filename){
	Swal.fire({
	  title: 'Are you sure?',
	  text: "Once deleted, you will not be able to recover this imaginary file!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Delete'
	}).then((result) => {
	  if (result.value) {
		$.post("s3filepermission.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $company_id; ?>,
		  type: "s3cldelete"
		},
		function(rurl){
		  if(rurl == true){
			//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
			Swal.fire("","Deleted Successfully!", "success");
			location.reload(true);
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
		});
	  }else {
		Swal.fire("Request Canceled!");
	  }
	})
}


async function editdesc(filename,descid,ovalue){
	const {value: text} = await Swal.fire({
	title: 'Enter file description',
	  input: 'textarea',
	  inputPlaceholder: 'Add file description...',
	  inputValue: ovalue,
	  showCancelButton: true,
	})

	if (text) {
		$.post("s3filepermission.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $company_id; ?>,
		  fvalue: text,
		  type: "s3clfiledesc"
		},
		function(rurl){
		  if(rurl == true){
			//if(value==""){value="Add Description";}
			//$('#'+descid+'').text(value);
			Swal.fire("","File description added successfully!", "success");
			//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
			location.reload(true);
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
		});
	}
}

async function editfname(filename,descid,ovalue,fdescs){
	const {value: efname} = await Swal.fire({
	title: 'Enter file name',
	  input: 'text',
	  inputPlaceholder: 'Enter file name...',
	  inputValue: ovalue,
	  showCancelButton: true,
	  inputValidator: (value) => {
		if (!value) {
		  return 'File name cannot be empty!'
		}
	  }
	})

	if (efname) {
		if(efname != ''){
			$.post("s3filepermission.inc.php?id=<?php echo mt_rand(1,44); ?>",
			{
			  filename: filename,
			  ticket: <?php echo $company_id; ?>,
			  fvalue: efname,
			  fdesc: fdescs,
			  type: "s3clfilename"
			},
			function(rurl){
			  if(rurl == true){
				//if(value==""){value="Add Description";}
				//$('#'+descid+'').text(value);
				Swal.fire("","File description added successfully!", "success");
				//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
				location.reload(true);
			  }else if(rurl==6){Swal.fire("","File name already exists!", "warning");}
				else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
		}else{Swal.fire("","Filename cannot be empty!", "warning");}
	}
}
<?php } ?>
async function previewPop(filename){
	$.post("s3filepermission.inc.php",
	{
	  filename: filename,
	  ticket: <?php echo $company_id; ?>,
	  type: "s3clview"
	},
	function(data){
	  if(data !=false){
			var result = JSON.parse(data);
			rurl=result.presignedurl;
			rurl1=result.oripresignedurl;
			rstatus=result.status;
			rname=result.name;
      //if(rstatus==5 || rstatus==6 || rstatus==7 || rstatus==8){
        var rawfile=decodeURI(unescape(rurl));
        myArray = /([^\*]+\/)[^\?\/]+\?/g.exec(rawfile);
        var rawourl=myArray[1] + rname + "?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=" + decodeURI(unescape(rurl1));
      //}
			//discode='<img src="'+filename+'" width="100%" style="height: auto;max-height: 100%;width: auto" />';
			/*if(rstatus==1){ discode='<div id="overhead" style="width: 78%;height: 54px;position: fixed;background-color: #333;text-align: center;vertical-align: middle;line-height: 3;color: #c0bbb7;font-size: 18px;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;"><span id="filenamepdf">'+rname+'</span><a id="downloadpdf" title="Download" download target="_blank" href="'+rurl1+'" style=""><img src="assets/img/arrowdown.png" style="filter: invert(100%);"></a></div><object type="application/pdf" width="100%" style="height: 85vh;max-height: 100%;width:100%;" data="'+rurl+'"></object>'; }*/
			if(rstatus==1){ discode='<object type="text/html" data="assets/plugins/pdfjs/web/viewer.php?file='+rurl+'&ofile='+rurl1+'&fname='+rname+'" style="overflow:auto;width:100%;height:85vh;"></object>'; }
			else if(rstatus==2){ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
			else if(rstatus==0){ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
			else if(rstatus==5){ discode='<video controls autoplay style="width:100%;height:80%;"><source src="'+rawourl+'" type="video/mp4" /></video>'; }
			else if(rstatus==6){ discode='<audio controls autoplay style="width:100%;"><source src="'+rawourl+'" type="audio/mpeg">Your browser does not support the audio element.</audio>'; }
			else if(rstatus==7){ discode='<img src="'+rawourl+'" width="100%" style="height: auto;max-height: 100%;width: auto;max-width:100%;" />'; }
			else if(rstatus==8){var rawgurl=encodeURIComponent(rawourl); discode='<style>#googleload{background: url("data:image/svg+xml;charset=utf-8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100%\" height=\"100%\" viewBox=\"0 0 100% 100%\"><text fill=\"%23FF0000\" x=\"50%\" y=\"50%\" font-family=\"\'Lucida Grande\', sans-serif\" font-size=\"24\" text-anchor=\"middle\">Loading...</text></svg>\') 0px 0px no-repeat;}</style><iframe src="https://docs.google.com/viewer?embedded=true&url='+rawgurl+'" frameborder="0" width="100%" height="100%" id="googleload" name="googleload" onload="checkerror()" dtct="ct<?php echo time(); ?>">Loading....</iframe><a href="'+rawgurl+'" download><div class="noshow"></div></a><script>/*var runct=0;window.begincheck = setInterval(reloadIFrame, 2500);*/function reloadIFrame() {runct=runct+1;if(Number(runct) > 8){clearInterval(window.begincheck);}else{document.getElementById("googleload").src=document.getElementById("googleload").src;}}function checkerror(){clearInterval(window.begincheck);};<\/script>preview_height=document.getElementById(\'googleload\').contentWindow.document.body.scrollHeight;document.getElementById(\'googleload\').height=preview_height;'; }
      else if(rstatus==9){ discode='<h3 align="center" style="margin-top:20%;">This is a large file and can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
			else{ discode='<h3 align="center" style="margin-top:20%;">This file type can\’t be viewed online.</h3><div id="b-conts"><a class="btn btn-primary" id="d-download" download>Download</a>&nbsp;&nbsp;<button type="cancel" class="btn btn-primary" id="d-cancel">Cancel</button></div>'; }
			parent.$("#dialog").html('');
			parent.$("#dialog").html(discode);
			parent.$("#dialog").dialog("open");
			parent.$("#d-download").attr('href', rawourl);
			parent.$("#d-download").css({"text-align": "center"});
			parent.$("#b-conts").css({"text-align": "center"});
      parent.$("#dialog").dialog('option', 'title', rname);
      parent.$("#d-cancel").click(function() {
        parent.$("#dialog").dialog('close');
        return false;
      });




			/*
			filename=rurl;
		  var file = filename.replace(/\.\.\//gi,'');
		  var exts = ['jpg','jpeg','gif','png','tif','bmp','ico'];
		  // first check if file field has any value
		  if ( file ) {
			// split file name at dot
			//var get_ext = file.split('.');
			var get_ext = filename.split(/\#|\?/)[0].split('.').pop().trim();
			// reverse name to check extension
			//get_ext = get_ext.reverse();
			// check file type is valid as given in 'exts' array
			if ( $.inArray ( get_ext.toLowerCase(), exts ) > -1 ){
			  discode='<img src="'+filename+'" width="100%" style="height: auto;max-height: 100%;width: auto" />';
			  parent.$("#dialog").html('');
			  parent.$("#dialog").html(discode);
			  parent.$("#dialog").dialog("open");
			  parent.$("#d-download").attr('href', filename);
			} else {
				//filename=encodeURIComponent(filename);
				//filename=urlEncodeS3Key(filename);
				//filename=urlencodefors3(filename);
				filename = filename.replace(/\?/g, "%3F");
				filename=filename.replace(/\&/g, "%26");
			  discode='<style>#googleload{background: url("data:image/svg+xml;charset=utf-8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100%\" height=\"100%\" viewBox=\"0 0 100% 100%\"><text fill=\"%23FF0000\" x=\"50%\" y=\"50%\" font-family=\"\'Lucida Grande\', sans-serif\" font-size=\"24\" text-anchor=\"middle\">Loading...</text></svg>\') 0px 0px no-repeat;}</style><iframe src="https://docs.google.com/viewer?embedded=true&url='+filename+'" frameborder="0" width="100%" height="100%" id="googleload" name="googleload" onload="checkerror()" dtct="ct<?php echo time(); ?>">Loading....</iframe><a href="'+filename+'" download><div class="noshow"></div></a><script>var runct=0;window.begincheck = setInterval(reloadIFrame, 2500);function reloadIFrame() {runct=runct+1;if(Number(runct) > 8){clearInterval(window.begincheck);}else{document.getElementById("googleload").src=document.getElementById("googleload").src;}}function checkerror(){clearInterval(window.begincheck);};<\/script>';
			  parent.$("#dialog").html('');
			  parent.$("#dialog").html(discode);
			  parent.$("#dialog").dialog("open");
			  parent.$("#d-download").attr('href', filename);
			}
			//preview_height = document.getElementById('googleload').contentWindow.document.body.scrollHeight;
			//document.getElementById('googleload').height = preview_height;
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}

			*/
	  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
	});
}

function urlencodefors3(originalurl){
	originalurl = originalurl.replace(/\?/g, "%3F");
	return originalurl.replace(/\&/g, "%26");
}

function urlEncodeS3Key( rawurl ) {

	//key = urlEncodedFormat( key, "utf-8" );
	rawurl = encodeURIComponent( rawurl);

	// At this point, we have a key that has been encoded too aggressively by
	// ColdFusion. Now, we have to go through and un-escape the characters that
	// AWS does not expect to be encoded.

	// The following are "unreserved" characters in the RFC 3986 spec for Uniform
	// Resource Identifiers (URIs) - http://tools.ietf.org/html/rfc3986#section-2.3
	//rawurl = replace( rawurl, "%2E", ".", "all" );
	rawurl = rawurl.replace(/%2E/g, ".");

	//rawurl = replace( rawurl, "%2D", "-", "all" );
	rawurl = rawurl.replace(/%2D/g, "-");

	//rawurl = replace( rawurl, "%5F", "_", "all" );
	rawurl = rawurl.replace(/%5F/g, "_");

	//rawurl = replace( rawurl, "%7E", "~", "all" );
	rawurl = rawurl.replace(/%7E/g, "~");

	// Technically, the "/" characters can be encoded and will work. However, if the
	// bucket name is included in this key, then it will break (since it will bleed
	// into the S3 domain: "s3.amazonaws.com%2fbucket"). As such, I like to unescape
	// the slashes to make the function more flexible. Plus, I think we can all agree
	// that regular slashes make the URLs look nicer.
	//rawurl = replace( rawurl, "%2F", "/", "all" );
	rawurl = rawurl.replace(/%2F/g, "/");

	// This one isn't necessary; but, I think it makes for a more attactive URL.
	// --
	// NOTE: That said, it looks like Amazon S3 may always interpret a "+" as a
	// space, which may not be the way other servers work. As such, we are leaving
	// the "+"" literal as the encoded hex value, %2B.
	//rawurl = replace( rawurl, "%20", "+", "all" );
	rawurl = rawurl.replace(/%20/g, "+");

	return( rawurl );

}


$(document).ready(function(){
	filename="";
	parent.$( "#dialog" ).dialog({
	  /*height: screen.height,*/
	  /*height: $(document).height(),*/
	  /*height: screen.height,*/
      width: "80%",
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){ ?>
		open: function (event, ui) {
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

		}
<?php } ?>
    });
	parent.$( "#navheader" ).html('<?php echo $navtmp; ?>');
});
window.onscroll = function()
{
	//frames.googleload.document.documentElement.scrollTop = window.pageYOffset;
	//frames.googleload.document.body.scrollTop = window.pageYOffset;
}
</script>
<?php
								//echo implode(", ",$tmpfilearr);

							}//else $s3error=1;
				}
	}





if($s3error != 0 and !isset($_GET["s3drpdisplay"])){echo $s3error ."<p style='text-align:center;'>Error Occured!. Please Try after sometimes.</p>";}
else if($s3error != 0 and isset($_GET["s3drpdisplay"])){echo false;}
else if($s3error == 0 and isset($_GET["s3drpdisplay"])){echo json_encode(array("error"=>"","s3files"=>$tmpfilearr));}
else echo false;
}


 function getpresignedurll($object, $bucket = '', $expiration = '')
 {
	 $bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
	 if (empty($bucket)) {
		 throw new InvalidDomainNameException('An empty bucket name was given');
	 }
	 if ($expiration) {
		 $command = $this->client->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $object]);
		 return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
	 } else {
		 return $this->client->getObjectUrl($bucket, $object);
	 }
 }

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
?>
