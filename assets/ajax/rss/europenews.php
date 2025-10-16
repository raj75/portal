<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
    <title>Test</title>
<style>
.bcolor{font-family: 'Open Sans',Arial,Helvetica,Sans-Serif !important;color: #333 !important;font-size:13px !important;}

.fg_padder{width:350px;}
.fg_widget
{
	text-align: left;
}
.fg_story
{
	width: 100%;
	overflow: hidden;
	padding-bottom: 5px;
	margin-bottom: 5px;
	border-bottom: 1px solid #E0E0E0;
}
.fg_story A:link, .fg_story A:visited
{
	text-decoration: none;
}
DIV.fg_storytext DIV
{
	line-height: 1.3em;
}
DIV.fg_storytext DIV SPAN
{
	line-height: 1.3em;
}

.fg_story A:hover SPAN.fg_title
{
	text-decoration: underline;
}
.fg_divider
{
	height: 1px;
	margin-top: 1px;
	border-top: 1px solid #D0D0D0;
	clear: both;
}
.fg_wid_cont2
{
	position: relative;
}
.fg_widget_in
{
	overflow: hidden;
}
.fg_storydate, .fg_sourcename
{
	color: inherit;
	font-size:inherit;
}

.fg_imgcont
{
	float: left;
	width: 80px;
	height: 50px;
	overflow: hidden;
	margin-right: 6px;
	margin-top: 3px;
}
.fg_imgcont IMG
{
	height: 100%;
	width: auto;
	-ms-interpolation-mode: bicubic;
	outline: 0px;
	border: 0px;
}


/* for image scroller */
.fg_l_imsc .fg_imgcont {
	float:none;
	background-size:cover;
	height:150px;
	width:100%;
	margin:0px 0px 5px 0px;
	background-position:center;
}
.fg_l_imsc .fg_col .fg_padder {padding-bottom:0}
.fg_l_imsc .fg_story {
	margin-bottom:10px;
}



.fg_scalewidth .fg_imgcont IMG
{
	height: auto;
	width: 100%;
	max-width:100%;
}
.fg_wid_cont .fg_imgcont IMG 
{
	display:block;
}
.fg_storytext
{
	overflow: hidden;
}
.fg_wid_cont .fg_source
{
	display: block;
}
.fg_title
{
margin-right:8px;
}
.fg_hidedividers .fg_padder .fg_story
{
	border-color: transparent;
}
.fg_hidefoot .fg_wid_footer, .fg_hideheadfoot .fg_wid_footer
{
	display: none;
}
.fg_hidefoot .fg_wid_cont
{
	margin-bottom: 1px;
}
.fg_hidehead .fg_wid_cont
{
	margin-top: 1px;
}
.fg_hideheadfoot .fg_wid_cont
{
	margin-top: 1px;
	margin-bottom: 1px;
}
.fg_hidehead .fg_wid_header, .fg_hideheadfoot .fg_wid_header
{
	display: none;
}
.fg_headerbold .fg_wid_header SPAN
{
	font-weight: bold;
}

.fg_linksbold .fg_storytext SPAN.fg_title, .fg_linksbold .fg_twititem SPAN
{
	font-weight: bold;
}

.fg_headlinebold .fg_headline SPAN.fg_title
{
	font-weight: bold;
}
.fg_hidedates .fg_storydate
{
	display: none;
}
.fg_hidesource .fg_sourcename
{
	display: none;
}
.fg_hideimages .fg_imgcont
{
	display: none;
}
/*fonts*/
.fg_font_arial, .fg_font_arial span, .fg_font_arial p
{
	font-family: arial, sans-serif;
}
.fg_font_treb, .fg_font_treb span, .fg_font_treb p
{
	font-family: "trebuchet ms", helvetica, sans-serif;
}
.fg_font_times, .fg_font_times span, .fg_font_times p
{
	font-family: "times new roman", times, serif;
}
.fg_font_georgia, .fg_font_georgia span, .fg_font_georgia p
{
	font-family: georgia, times, serif;
}
.fg_font_verdana, .fg_font_verdana span, .fg_font_verdana p
{
	font-family: verdana, helvetica, sans-serif;
}
.fg_font_tahoma, .fg_font_tahoma span, .fg_font_tahoma p
{
	font-family: tahoma, helvetica, sans-serif;
}
.fg_widget_in
{
	border-radius: 5px;
}
.fg_padder
{
	padding: 8px;
}
.fg_wid_header
{
	/*height: 28px;
	line-height:28px;*/
	border-radius: 5px 5px 0px 0px;
	padding-left: 10px;
	padding-top:8px;
	padding-bottom:8px;
}
.fg_wid_header SPAN
{
	/*line-height: 28px;*/
	color:inherit;
	font-size:inherit;
}
.fg_wid_header img {
	vertical-align:middle;
	margin-right:1em;
}
.fg_wid_header img[src=""] {
    display: none;
}
.fg_wid_footer
{
	height: 25px;
	border-radius: 0px 0px 5px 5px;
	padding-left: 8px;
}
.fg_wid_footer A
{
	line-height: 25px;
}
.fg_wid_cont
{
	margin-left: 1px;
	margin-right: 1px;
	border-radius: 3px;
	overflow: hidden;
	position: relative;
}
/*2 column layouts*/
.fg_l_2col .fg_col
{
	float: left;
}
.fg_l_2col .fg_col
{
	width: 50%;
}
.fg_hdivider
{
	position: absolute;
	top: 5px;
	bottom: 5px;
	left: 50%;
	width: 1px;
	background-color: #D0D0D0;
}
/*headline story*/
.fg_headline
{
	padding: 8px;
	font-size: 10pt;
	border-radius: 3px 3px 0px 0px;
}
.fg_headline .fg_divider
{
	display: none;
}
.fg_headline .fg_imgcont
{
	width: 90px;
	height: 60px;
}
.fg_headline .fg_story
{
	border: 0px;
	padding: 0px;
	margin: 0px;
}
DIV.fg_wid_footer A IMG
{
	border: none;
	vertical-align: middle;
	background-color:transparent;
	max-width:100%;
}
DIV.fg_wid_footer A
{
	float: left;
	font-size: 11px;
	text-decoration: none;
}
/*skins*/
.fg_light
{
	background-color: #8EC1DA;
	color: #FFFFFF;
}
.fg_light .fg_wid_cont
{
	background-color: #FFFFFF;
}
.fg_light .fg_headline
{
	background-color: #E8E8E8;
	color: #000000;
}
.fg_light .fg_source
{
	color: #808080;
}
/*
.fg_light .fg_storytext, .fg_light .fg_twititem
{
	color: #303030;
}
*/
.fg_light .fg_snippet
{
color:#707070;
}

.fg_dark
{
	background-color: #404040;
	color: #FFFFFF;
}
.fg_dark .fg_wid_cont
{
	background-color: #101010;
}
.fg_dark .fg_divider
{
	border-color: #404040;
}
/*
.fg_dark .fg_storytext, .fg_dark .fg_twititem
{
	color: #F0F0F0;
}
*/
.fg_dark .fg_headline
{
	background-color: #606060;
	color: #FFFFFF;
}
.fg_dark .fg_story
{
	border-color: #606060;
}
.fg_dark .fg_source
{
	color: #B0B0B0;
}
.fg_dark .fg_snippet
{
color:#a0a0a0;
}

/*style headline*/
.fg_headline .fg_wid_header
{
	height: 24px;
	line-height: 24px;
	padding-left: 10px;
}
.fg_headline .fg_wid_footer
{
	display: none;
}
.fg_headline .fg_padder
{
	padding: 1px;
}
.fg_headline A
{
	color: inherit;
	line-height: 120%;
}
.fg_squarecorners, DIV.fg_squarecorners .fg_wid_cont, DIV.fg_squarecorners .fg_headline
{
	border-radius: 0px;
}
/*slider*/
.fg_l_fb .fg_padder
{
	padding: 0px;
	height: 100%;
}
.fg_l_fb .fg_wid_cont
{
	/*border-radius: 0px;*/
	position: relative;
}
.fg_l_fb .fg_story
{
	height: 100%;
	float: left;
	padding: 0px;
	margin: 0px;
	border: 0px;
	position: relative;
}
.fg_l_fb .fg_imgcont
{
	width: 100%;
	height: 100%;
	margin: 0px;
}
.fg_l_fb .fg_storytext
{
	position: absolute;
	bottom: 0px;
	left: 0px;
	background: url(black63pct.png);
	padding: 0px;
	line-height: 1.2em;
	width: 100%;
	color: #FFFFFF;
}
.fg_l_fb .fg_col
{
	position: absolute;
	left: 0px;
	height: 100%;
}
.fg_l_fb .fg_wid_cont2
{
	height: 100%;
}
.fg_l_fb .fg_storytext DIV
{
	margin: 4px;
}
.fg_l_fb .fg_wid_cont .fg_source
{
	display: inline;
}
.fg_l_fb .fg_noimage
{
	background: url(noimage.png) center center;
}
.fg_l_fb .fg_col
{
	-webkit-transition: left 0.5s;
	-moz-transition: left 0.5s;
	transition: left 0.5s;
}

.fg_fb_lbutton, .fg_fb_rbutton {
	position:absolute;
	opacity:0.0;
	height:48px;
	width:48px;
	background-color:#000000;
	transition:opacity 0.3s;
	cursor:pointer;
	z-index:10;
	display:flex;
	justify-content: center;
	align-items: center;
}

.fg_fb_lbutton img, .fg_fb_rbutton img {display:block;margin:0;} 

.fg_fb_lbutton
{
	top:50%;
	left:0;
	transform:translateY(-50%);
}

.fg_fb_rbutton 
{
	top:50%;
	right:0;
	transform:translateY(-50%);
}

.fg_wid_cont:hover .fg_fb_lbutton, .fg_wid_cont:hover .fg_fb_rbutton {opacity: 0.7}


.fg_hideborders .fg_wid_cont
{
	margin: 0px;
}
/*
.fg_twititem
{
	color: #303030;
}
.fg_twititem A
{
	color: inherit;
}
.fg_twititem .fg_imgcont IMG
{
	width: auto;
	height: auto;
}
.fg_twititem A:hover
{
	text-decoration: underline;
}
.fg_twituser
{
	display: block;
	font-weight: bold;
	padding-left: 28px;
	background: url(twitbird2.png) no-repeat left center;
	line-height: 22px;
}
*/
/*
.fg_l_fb .fg_twititem .fg_imgcont
{
	width: 96px;
	height: 96px;
	margin: 20% auto 0px;
	float: none;
}
.fg_l_fb .fg_twititem .fg_imgcont IMG
{
	height: 100%;
}
.fg_l_fb .fg_twititem SPAN
{
	display: block;
	margin: 4px;
}
.fg_l_fb .fg_twititem
{
	background-color: #003040;
}
*/


.fg_wid_footer_ad IMG
{
	float: right;
	line-height: 62px;
}
.fg_wid_footer_adcont
{
	float: left;
	width: 160px;
}
.fg_l_hero3 .fg_widget_in, .fg_l_hero3 .fg_widget_in, .fg_l_hero4 .fg_widget_in
{
	overflow: hidden;
}
.fg_l_hero3 .fg_wid_cont, .fg_l_hero4 .fg_wid_cont, .fg_l_hero5 .fg_wid_cont
{
	height: 100%;
	margin: 0px;
}
.fg_l_hero3 .fg_col, .fg_l_hero4 .fg_col, .fg_l_hero5 .fg_col
{
	width: 100%;
	height: 100%;
}
.fg_l_hero3 .fg_hero_story1
{
	width: 50%;
	height: 100%;
	float: left;
}
.fg_l_hero3 .fg_hero_story2, .fg_l_hero3 .fg_hero_story3
{
	height: 50%;
	width: 50%;
	float: left;
}
.fg_l_hero4 .fg_col1, .fg_l_hero4 .fg_col2
{
	width: 50%;
	height: 100%;
	float: left;
}
.fg_l_hero4 .fg_hero_story1, .fg_l_hero4 .fg_hero_story4
{
	height: 63%;
}
.fg_l_hero4 .fg_hero_story2, .fg_l_hero4 .fg_hero_story3
{
	height: 37%;
}
.fg_l_hero5 .fg_hero_story1, .fg_l_hero5 .fg_hero_story2
{
	height: 66%;
	width: 50%;
	float: left;
}

.fg_l_hero5 .fg_hero_story3, .fg_l_hero5 .fg_hero_story5
{
	height: 34%;
	width: 33%;
	float: left;
}
.fg_l_hero5 .fg_hero_story4
{
	height: 34%;
	width: 34%;
	float: left;
}

.fg_hero_story
{
	width: 100%;
	height: 100%;
	overflow: hidden;
}
.fg_hero_story A
{
	position: relative;
	display: block;
	width: 100%;
	height: 100%;
}
.fg_hero_story .fg_imgcont
{
	overflow: hidden;
	margin: 0px;
	float: none;
	width: 100%;
	height: 100%;
}
.fg_hero_story .fg_imgcont IMG
{
	width: 100%;
	height: auto;
	max-width:300%;
	max-height:300%;
}
.fg_hero_story .fg_storytext
{
	position: absolute;
	bottom: 0px;
	left: 0px;
	width: 100%;
	background: url(black63pct.png);
}
.fg_hero_story .fg_storytext DIV
{
	margin: 5px;
	line-height: 1.3em;
}
.fg_hero_story .fg_storytext SPAN
{
	line-height: inherit;
}
/*image scaling*/
.fg_l_hero3 .fg_hero_story1 .fg_imgcont
{
	height: 100%;
}
.fg_l_hero3 .fg_hero_story1 .fg_imgcont IMG
{
	width: auto;
	height: 100%;
}
.fg_l_hero4 .fg_hero_story1 .fg_imgcont IMG, .fg_l_hero4 .fg_hero_story4 .fg_imgcont IMG
{
	width: auto;
	height: 100%;
}
.fg_l_hero5 .fg_hero_story1 .fg_imgcont IMG, .fg_l_hero5 .fg_hero_story2 .fg_imgcont IMG
{
	width: auto;
	height: 100%;
}
/*borders*/
.fg_l_hero3 .fg_hero_border .fg_hero_story1 .fg_hero_story
{
	margin-right: 1px;
	width: auto;
}
.fg_l_hero3 .fg_hero_border .fg_hero_story2
{
	margin-bottom: 1px;
}
.fg_l_hero4 .fg_hero_border .fg_hero_story1
{
	margin-bottom: 1px;
	margin-right: 1px;
}
.fg_l_hero4 .fg_hero_border .fg_hero_story2
{
	margin-right: 1px;
}
.fg_l_hero4 .fg_hero_border .fg_hero_story3
{
	margin-bottom: 1px;
}
.fg_l_hero5 .fg_hero_border .fg_hero_story1 .fg_hero_story
{
	margin-right: 1px;
	width: auto;
}
.fg_l_hero5 .fg_hero_border .fg_hero_story3, .fg_l_hero5 .fg_hero_border .fg_hero_story5
{
	margin-top: 1px;
}
.fg_l_hero5 .fg_hero_border .fg_hero_story4 .fg_hero_story
{
	margin: 1px 1px 0px;
	width: auto;
}
/* snippets */
.fg_snippet
{
	font-size:80%;
	/*display:none;*/
}
.fg_showsnips .fg_snippet
{
	display:block;
}
/* image list */
.fg_l_il .fg_wid_cont {background-color:transparent;margin:0px;border-radius:4px}
.fg_l_il .fg_story {padding:0px;margin:0px;border:0px;background-color:#000000;margin-top:1px}
.fg_l_il .fg_story:first-child {margin-top:0px}
.fg_l_il .fg_padder {padding:0px}

.fg_l_il .fg_story a {position:relative;display:block;padding:10px}

.fg_l_il .fg_imgcont {float:none;position:absolute;left:0px;top:0px;width:100%;opacity:0.5;margin:0px;height:auto;}
.fg_l_il .fg_imgcont img {height:auto;width:100%}

.fg_l_il .fg_storytext {color:white;padding-top:5px;position:relative;} 
.fg_l_il .fg_storytext div {padding:0px}

.fg_l_il .fg_hidedividers .fg_story {margin-top:0px;}

.fg_textdropshadow .fg_storytext {text-shadow:1px 1px 0px rgba(0,0,0,0.5)}

/* newsbar */

.fg_l_nb {overflow:hidden}
.fg_l_nb .fg_dark {background-color:#000000;color:#ffffff}
.fg_l_nb .fg_dark .fg_header {color:#f0f0f0}
.fg_l_nb .fg_light {background-color:#ffffff;color:#202020}
.fg_l_nb .fg_light .fg_header {color:#ffffff}

.fg_l_nb .fg_wid_header {float:left;margin-right:10px;height:100%;border-radius:0px}
.fg_l_nb .fg_wid_header span {display:block;line-height:inherit;}
.fg_l_nb .fg_wid_cont {overflow:hidden;background-color:transparent;position:relative;border-radius:0px;margin:0px;margin-left:10px}
.fg_l_nb .fg_story, .fg_l_nb .fg_col, .fg_l_nb .fg_padder  {padding:0px;margin:0px;border:0px}
.fg_l_nb DIV.fg_storytext DIV {line-height:inherit}
.fg_l_nb {word-break:break-all}
/*.fg_l_nb .fg_title {word-break:break-all}*/

.fg_l_nb .fg_nb_buttons {float:right;height:auto;width:58px;padding-left:4px}
.fg_nb_pause, .fg_nb_next, .fg_nb_prev, .fg_nb_play {zoom:1;float:left;background:url(nb-buttons.png) no-repeat;width:17px;text-decoration:none;margin-right:2px;opacity:0.6;filter: alpha(opacity = 60);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=60)"}
.fg_nb_pause:hover, .fg_nb_prev:hover, .fg_nb_next:hover {opacity:1.0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";filter: alpha(opacity = 100);text-decoration:none}
.fg_nb_dark .fg_nb_pause {background-position:0px center}
.fg_nb_dark .fg_nb_prev  {background-position:-16px center;width:15px}
.fg_nb_dark .fg_nb_next {background-position:-30px center;width:15px}
.fg_nb_dark .fg_nb_play {background-position:-90px center}
.fg_nb_light .fg_nb_pause {background-position:-44px center}
.fg_nb_light .fg_nb_prev  {background-position:-60px center;width:15px}
.fg_nb_light .fg_nb_next {background-position:-74px center;width:15px}
.fg_nb_light .fg_nb_play {background-position:-107px center;}

.fg_nb_mask {position:absolute;zoom:1;width:100%;height:100%;opacity:0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter: alpha(opacity = 0);display:none;top:0px}
.fg_dark .fg_nb_mask {background-color:#000000}
.fg_light .fg_nb_mask {background-color:#ffffff}

.fg_nb_var {display:block;overflow:hidden}
.fg_nb_fade {position:absolute;right:0px;top:0px;bottom:0px;width:30px;}
.fg_dark .fg_nb_fade {background-image: linear-gradient(to left, rgb(0, 0, 0) 0%, rgba(0, 0, 0, 0) 100%);}
.fg_light .fg_nb_fade {background-image: linear-gradient(to left, rgb(255, 255, 255) 0%, rgba(255, 255, 255, 0) 100%);}

/* newsbar popup */
.fg_popup {
	display:none;
	z-index:10001;
	position:absolute;
	/*width:500px;*/
	/*max-height:500px;*/
	border:#e0e0e0 1px solid;
	background-color:white;
	border-radius:4px;
	padding:20px;box-shadow:0px 0px 30px rgba(0,0,0,0.4);left:50%;top:50%;transform:translate(-50%,-50%);}
.fg_popupmask {display:none;/*zoom:1;*/z-index:10000;position:fixed;top:0px;left:0px;width:100%;height:100%;opacity:0.5;filter: alpha(opacity = 50);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";background-color:#000000;}


.fg_popup .fg_pp_imgcont {float:left;max-width:45%;margin-right:8px;margin-bottom:8px}
.fg_fullstory {line-height:1.4em;}
.fg_fullstory p {margin:0px;padding:0px;margin-bottom:2em;}
.fg_fullstory img {max-width:100%}
.fg_popupcont {/*max-height:400px;*/height:100%;overflow-y:auto;overflow-x:hidden;width:100%;padding-right:6px}
.fg_popupcont h3 {margin:0px;padding:0px;padding-right:20px;line-height:1em;margin-bottom:7px;font-weight:normal}
.fg_fullstory_date {display:block;line-height:1em;margin-bottom:10px;}
.fg_pp_imgcont img {width:100%}

.fg_popupspin {width:32px;height:32px;background:url(popupspin.gif);}

.fg_popup_close {display:block;background:url(popupclose4.png);text-decoration:none;width:37px;height:37px;outline:0px}
.fg_popup_hclose {position:absolute;top:-15px;right:-15px;}

.fg_story a {cursor:pointer}
.dnone{display:none;}
.fg_title{font-weight:bold;}
.desccontent{display:none;}




.fg_col,
.fg_padder,
.fg_padder a,
.fg_padder a img {
    display: block;
    margin: 0;
    padding: 0;
}

.fg_col {
	height: 100%;
	min-height: 100vh;
    overflow: hidden;
    position: relative;
	width:100%;
}

.fg_padder {position: absolute;width:100%;}

/*a:hover {outline:2px dashed grey}*/
.fg_title {
	width: 100% !important;
	margin-top:0;
	margin-bottom:0;
	white-space: nowrap !important;
	overflow: hidden !important;
	text-overflow: ellipsis !important;
}
</style>
</head> 
<body>
<?php
/*require '/var/www/html/scripts/lib/awssdk/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Credentials\CredentialProvider;
use Aws\S3\Exception\S3Exception;


$profile = 'default';
//$path = '../../../lib/awssdk/credentials.ini';
$path = '/var/www/html/scripts/lib/awssdk/credentials.ini';

$provider = CredentialProvider::ini($profile, $path);
$provider = CredentialProvider::memoize($provider);

$s3Client = new S3Client([
	'region'      => 'us-west-2',
	'version'     => 'latest',
	'credentials' => $provider
]);*/

$tablename="rss";
$imageloc="https://datahub360-public.s3-us-west-2.amazonaws.com/streaming%20news/";

$conn = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
//$conn = mysqli_connect("localhost", "root","","vervantis");
if (!$conn) {
    printf("Connect failed: %s\n", mysqli_connect_errno());
    exit();
}

if ($ckmainstmt = mysqli_query($conn,"SELECT id,title,image,description,url,published_date,logo FROM ".$tablename." WHERE newstype='Europe' order by published_date desc LIMIT 0,63")) {
	if (mysqli_num_rows($ckmainstmt) > 0) {
		while ($ckrow = mysqli_fetch_assoc($ckmainstmt)) {
			$feed[]=array(
			'image'  => $ckrow['image'],
			'title' => $ckrow['title'],
			'desc'  => $ckrow['description'],
			'link'  => $ckrow['url'],
			'date'  => $ckrow['published_date'],
			'logo'  => $ckrow['logo']
		  );
		}
		mysqli_free_result($ckmainstmt);
	}
}
//shuffle($feed);

//print_r($feed);die();
$limit = 63;
$limit = 30;
?>
	<div class="fg_col"><div class="fg_padder">
<?php for ( $x = 0; $x < $limit; $x++ ) {//print_r($feed[ $x ]);die();
	$tempdesc="";
	if(!isset($feed[ $x ]['title'])) continue;
   // $img = ($feed[ $x ]['image'])?$feed[ $x ]['image']:"");
	//if(isset($feed[ $x ]['desc']) and preg_match("/img[\s]+src=\"([^<>\"]+)\"/s",$feed[ $x ]['desc'],$tmp_img)) $img =@str_replace("&amp;","&",$tmp_img[1]); else $img="";
	$imgarr=array();
	if(!empty($feed[ $x ]['image'])) $imgarr=explode(",",$feed[ $x ]['image']);
    $title = str_replace( '&amp;', '&', $feed[ $x ]['title'] );
    $link = "javascript:void(0)";//$feed[ $x ]['link'];
    $description = @strip_tags($feed[ $x ]['desc']);
    //$date = date( 'l F d, Y', strtotime( $feed[ $x ]['date'] ) );
    $date = date( 'm/d/y h:i:s a', strtotime( $feed[ $x ]['date'] ) );
	$tempdesc=preg_replace("/(<img)([^<>]+src=[\'\"]{1})/s","$1 style='width:20%;height:auto;max-height:20%;' $2".$imageloc,$feed[ $x ]['desc']);
	
	if(isset($feed[ $x ]['image']) and !empty($feed[ $x ]['image'])) $tempdesc="<img src='".$imageloc.$feed[ $x ]['image']."' style='width:20%;height:auto;max-height:20%;'><br>".$tempdesc; 
	
	$rsslogo=$feed[ $x ]['logo'];

	if(!empty(@trim($rsslogo))) $tempdesc="<img src='".$imageloc."logo/".$rsslogo."' style='width: 11%;height: auto;max-height: 20%;margin-bottom: 20px;margin-top: -9px;'><br>".$tempdesc;

	$tempdesc="<p>Source: <a href='".$feed[ $x ]['link']."' target='_blank'>".$feed[ $x ]['link']."</a></p>".$tempdesc;
	/*if(count($imgarr) and isset($imgarr[0])){
		$tmpdss=array();
		foreach($imgarr as $kys=>$vls) $tmpdss[]="<img src='".$imageloc.$vls."' style='width:20%;max-height:20%;'>";
		
		$tempdesc=implode("&nbsp;&nbsp;",$tmpdss).$tempdesc;
	}*/
?>
		 <div class="fg_story" style="border-color: rgb(88, 95, 105); display: block; opacity: 1;">
			<div class="desccontent" id="nadesc<?php echo $x; ?>"><?php echo $tempdesc; ?></div>
			<a href="<?php echo $link; ?>" class="fg_popuplink naplink" id="<?php echo $x; ?>">
			<?php if(count($imgarr) and isset($imgarr[0])){ ?>
				<div class="fg_imgcont" style="width:80px;height:50px;">
				
					<img src="<?php echo $imageloc.$imgarr[0]; ?>" style="height: auto; width: 100%; margin-left: 0px;">
				</div>
			<?php } ?>
				<div class="fg_storytext">
					<div>
						<p class="fg_title bcolor" id="nafgtitle<?php echo $x; ?>" style="color:#000000;"><?php echo $title; ?></p>
						<div class="fg_snippet bcolor" style=""><?php echo @trim(strlen($description) >250?substr($description, 0, 250)."...":$description); ?></div>
						<span class="fg_source" style="font-size:8pt;">
							<span class="fg_sourcename dnone"><?php echo $feed[ $x ]['link']; ?> - </span>
							<span class="fg_storydate bcolor"><?php echo $date; ?></span>
						</span>
					</div>
				</div>
			</a>
		</div>
<?php } ?>
		</div>
    </div>
	<span id="abc">&nbsp;</span>
</body>
<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script>
(function(){var e=jQuery,f="jQuery.pause",d=1,b=e.fn.animate,a={};function c(){return new Date().getTime()}e.fn.animate=function(k,h,j,i){var g=e.speed(h,j,i);g.complete=g.old;return this.each(function(){if(!this[f]){this[f]=d++}var l=e.extend({},g);b.apply(e(this),[k,e.extend({},l)]);a[this[f]]={run:true,prop:k,opt:l,start:c(),done:0}})};e.fn.pause=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&g.run){g.done+=c()-g.start;if(g.done>g.opt.duration){delete a[this[f]]}else{e(this).stop();g.run=false}}})};e.fn.resume=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&!g.run){g.opt.duration-=g.done;g.done=0;g.run=true;g.start=c();b.apply(e(this),[g.prop,e.extend({},g.opt)])}})}})();

	$(document).ready(function() {
		
	$(".fg_padder").hover(function() {
	  $(this).pause();
	}, function() {
	  $(this).resume();
	});

	var imageColumn = $('.fg_padder');
		
	origColumnHeight = imageColumn.height();

		var columnDupe = imageColumn.contents()
									.clone()
									.addClass('dupe')
									.appendTo(imageColumn);
		
		function scrollColumn() {
			imageColumn.css({'top': '0'})
					   .animate({top: -origColumnHeight},80000, 'linear', scrollColumn);
			
		}
		
		scrollColumn();
	});

	  // next add the onclick handler
	  $(".naplink").click(function() {
		  var naattr=$( this ).attr("id");
		  var natempcontent= $( "#nadesc"+naattr ).html();
		  var natemptitle= $( "#nafgtitle"+naattr ).html();
			parent.$("#dialog").html(natempcontent); 
			parent.$("#dialog").dialog("option","title","Europe News: "+natemptitle).dialog("open");
			return false;
	  });
</script> 
</html>