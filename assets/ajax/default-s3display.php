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
$uqid=time();
if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){

/////////
//Saving Analysis
/////
if(isset($_GET) and isset($_GET["masterid"]) and $_GET["masterid"] != "" and  isset($_GET["s3displayfiles"])){
?>

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
    width: 250px;
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
    width: 170px;
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
	max-width: 150px;
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
	box-shadow: 1.74em -2.1em 0 0 #03689b inset;
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
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset;
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




		$user_one=$_SESSION['user_id'];
		$group_id=$_SESSION['group_id'];
		$cname=$_SESSION['company_id'];

		$profile = 'default';

		$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
			'credentials' => [
           'key' => $_ENV['aws_access_key_id'],
           'secret' => $_ENV['aws_secret_access_key']
       ]
		]);

		$s3sid=$mysqli->real_escape_string(@trim($_GET['masterid']));

		if ($stmt = $mysqli->prepare("SELECT sa.id,sa.company_id,c.company_name,sa.link FROM saving_analysis sa, company c where sa.company_id=c.company_id and sa.id='".$s3sid."' LIMIT 1")) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($sa_id,$company_id,$company_name,$s3_filenames);
				$stmt->fetch();

				if($s3_filenames==""){echo "<p style='text-align:center;'>Nothing to show!</p>";}
				else{

							$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/');
							if($infotarget)
							{
								$files_list=array();

								if(@trim($s3_filenames) != "")
								{
									$files_list=@explode("@@;@@",$s3_filenames);
								}

								$files_len=count($files_list);
								if($files_len > 0)
								{

?>

<div class="s3filemanager">
	<ul class="data animated" style="">
<?php
								$tmpfilearr=array();//2019-06-23 00:52:45.000000  M d,Y h:i:sA
								foreach ($files_list as $object){
									if($s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/saving analysis/'.basename($object)) == false) continue;
									$fileext=pathinfo($object, PATHINFO_EXTENSION);
									if ($fileext){
										$filebasename=basename($object);
										$headers = $s3Client->headObject(array(
											  "Bucket" => 'datahub360',
											  "Key" => 'resources/Clients/'.$company_name.'/saving analysis/'.$object
											));
										$headarr=$headers->toArray();//print_r($headarr);die();
										$fdesc="";
										if(isset($headarr["Metadata"]) and isset($headarr["Metadata"]["fdesc"])){ $fdesc=$headarr["Metadata"]["fdesc"]; }

										$ftime=@date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($headarr['LastModified']->format(\DateTime::ISO8601))));
										$tss=mt_rand(2,99).mt_rand(2,99);
										$filebname=pathinfo($filebasename, PATHINFO_FILENAME);
?>
		<li class="files">
			<i class="fa fa-times deleteit" onclick="deleteit('<?php echo $filebasename; ?>')"></i>
			<a href="javascript:void(0);" class="files defaultcursor">
				<span class="pointercursor file-icon icon-view" data-type="<?php  echo $fileext; ?>" onclick="previewPop('<?php echo @addslashes($filebasename); ?>')"  title="Click to preview: <?php echo $filebasename; ?>"></span>

				<span class="fdesc">
					<span id="tdesc<?php echo $tss; ?>"><?php echo (($fdesc=="")?"Add Description":$fdesc); ?></span>
					<span class="glyphicon glyphicon-edit editdesc"  onclick="editdesc('<?php echo @addslashes($filebasename); ?>','tdesc<?php echo $tss; ?>','<?php echo $fdesc; ?>')"></span>
				</span>

				<span class="name">
					<span id="tname<?php echo $tss; ?>" class="fname"><?php echo $filebasename; ?></span>
					<span class="glyphicon glyphicon-edit editfname"  onclick="editfname('<?php echo @addslashes($filebasename); ?>','tname<?php echo $tss; ?>','<?php echo @addslashes($filebname); ?>','<?php echo $fdesc; ?>')"></span>
				</span>

				<span class="details"><span class="fsize"><?php echo formatSizeUnits($headarr['ContentLength']); ?></span><span class="glyphicon glyphicon-download downloadf" onclick="downloadfiles('<?php echo @addslashes($filebasename); ?>')" title="Download"></span></span>
				<span class="ftime"><?php echo $ftime; ?></span>
			</a>
		</li>
<?php
									}
								}
?>
	</ul>
</div>
<div class="nothingfound">
	<div class="nofiles"></div>
	<span></span>
</div>
<div id="<?php echo $uqid; ?>dialog" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;text-align:center;}</style>
<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
<script>
function downloadfiles(filename){
	window.location.href ="assets/includes/filedownload.inc.php?filename="+filename+"&ticket=<?php echo $s3sid; ?>&type=sadownload";
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
		$.post("assets/includes/s3filepermission_2.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $s3sid; ?>,
		  type: "sadelete"
		},
		function(rurl){
		  if(rurl == true){
			parent.$('#sas3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $s3sid; ?>');
			Swal.fire("","Deleted Successfully!", "success");
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
		$.post("assets/includes/s3filepermission_2.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $s3sid; ?>,
		  fvalue: text,
		  type: "safiledesc"
		},
		function(rurl){
		  if(rurl == true){
			//if(value==""){value="Add Description";}
			//$('#'+descid+'').text(value);
			Swal.fire("","File description added successfully!", "success");
			parent.$('#sas3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $s3sid; ?>');
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
			$.post("assets/includes/s3filepermission_2.inc.php",
			{
			  filename: filename,
			  ticket: <?php echo $s3sid; ?>,
			  fvalue: efname,
			  fdesc: fdescs,
			  type: "safilename"
			},
			function(rurl){
			  if(rurl == true){
				//if(value==""){value="Add Description";}
				//$('#'+descid+'').text(value);
				Swal.fire("","File description added successfully!", "success");
				parent.$('#sas3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&masterid=<?php echo $s3sid; ?>');
			  }else if(rurl==6){Swal.fire("","File name already exists!", "warning");}
				else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
		}else{Swal.fire("","Filename cannot be empty!", "warning");}
	}
}

async function previewPop(filename){
	$.post("assets/includes/s3filepermission_2.inc.php",
	{
	  filename: filename,
	  ticket: <?php echo $s3sid; ?>,
	  type: "saview"
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
			parent.$("#<?php echo $uqid; ?>dialog").html('');
			parent.$("#<?php echo $uqid; ?>dialog").html(discode);
			parent.$("#<?php echo $uqid; ?>dialog").dialog("open");
			parent.$("#d-download").attr('href', rawourl);
			parent.$("#d-download").css({"text-align": "center"});
			parent.$("#b-conts").css({"text-align": "center"});
      parent.$("#<?php echo $uqid; ?>dialog").dialog('option', 'title', rname);
      parent.$("#d-cancel").click(function() {
        parent.$("#<?php echo $uqid; ?>dialog").dialog('close');
        return false;
      });
	  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
	});
}
$(document).ready(function(){
	filename="";
	$( "#<?php echo $uqid; ?>dialog" ).dialog({
	  height: screen.height,
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
});
</script>
</script>
<?php
								//echo implode(", ",$tmpfilearr);
								}

							}//else $s3error=1;
				}
			}else $s3error=4;
		}else $s3error=5;
	}



////////
//Saving Analysis Ends
////




/////////
//Focus Items Starts
/////
if(isset($_GET) and isset($_GET["fimasterid"]) and $_GET["fimasterid"] != "" and  isset($_GET["s3displayfiles"])){
?>

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
    width: 250px;
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
    width: 170px;
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
	max-width: 150px;
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
	box-shadow: 1.74em -2.1em 0 0 #03689b inset;
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
	box-shadow: 1.74em -2.1em 0 0 #e15955 inset;
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




		$user_one=$_SESSION['user_id'];
		$group_id=$_SESSION['group_id'];
		$cname=$_SESSION['company_id'];

		$profile = 'default';

		$s3Client = new S3Client([
			'region'      => 'us-west-2',
			'version'     => 'latest',
			'credentials' => [
           'key' => $_ENV['aws_access_key_id'],
           'secret' => $_ENV['aws_secret_access_key']
       ]
		]);

		$s3sid=$mysqli->real_escape_string(@trim($_GET['fimasterid']));

		if ($stmt = $mysqli->prepare("SELECT fi.id,fi.company_id,c.company_name,fi.link FROM focus_items fi, company c where fi.company_id=c.company_id and fi.id='".$s3sid."' LIMIT 1")) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($fi_id,$company_id,$company_name,$s3_filenames);
				$stmt->fetch();

				if($s3_filenames==""){echo "<p style='text-align:center;'>Nothing to show!</p>";}
				else{

							$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/');
							if($infotarget)
							{
								$files_list=array();

								if(@trim($s3_filenames) != "")
								{
									$files_list=@explode("@@;@@",$s3_filenames);
								}

								$files_len=count($files_list);
								if($files_len > 0)
								{

?>

<div class="s3filemanager">
	<ul class="data animated" style="">
<?php
								$tmpfilearr=array();//2019-06-23 00:52:45.000000  M d,Y h:i:sA
								foreach ($files_list as $object){
									if($s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/focus items/'.basename($object)) == false) continue;
									$fileext=pathinfo($object, PATHINFO_EXTENSION);
									if ($fileext){
										$filebasename=basename($object);
										$headers = $s3Client->headObject(array(
											  "Bucket" => 'datahub360',
											  "Key" => 'resources/Clients/'.$company_name.'/focus items/'.$object
											));
										$headarr=$headers->toArray();//print_r($headarr);die();
										$fdesc="";
										if(isset($headarr["Metadata"]) and isset($headarr["Metadata"]["fdesc"])){ $fdesc=$headarr["Metadata"]["fdesc"]; }

										$ftime=@date('M d,Y h:i:s A',strtotime('-4 hour',strtotime($headarr['LastModified']->format(\DateTime::ISO8601))));
										$tss=mt_rand(2,99).mt_rand(2,99);
										$filebname=pathinfo($filebasename, PATHINFO_FILENAME);
?>
		<li class="files">
			<i class="fa fa-times deleteit" onclick="deleteit('<?php echo $filebasename; ?>')"></i>
			<a href="javascript:void(0);" class="files defaultcursor">
				<span class="pointercursor file-icon icon-view" data-type="<?php  echo $fileext; ?>" onclick="previewPop('<?php echo @addslashes($filebasename); ?>')"  title="Click to preview: <?php echo $filebasename; ?>"></span>

				<span class="fdesc">
					<span id="tdesc<?php echo $tss; ?>"><?php echo (($fdesc=="")?"Add Description":$fdesc); ?></span>
					<span class="glyphicon glyphicon-edit editdesc"  onclick="editdesc('<?php echo @addslashes($filebasename); ?>','tdesc<?php echo $tss; ?>','<?php echo $fdesc; ?>')"></span>
				</span>

				<span class="name">
					<span id="tname<?php echo $tss; ?>" class="fname"><?php echo $filebasename; ?></span>
					<span class="glyphicon glyphicon-edit editfname"  onclick="editfname('<?php echo @addslashes($filebasename); ?>','tname<?php echo $tss; ?>','<?php echo @addslashes($filebname); ?>','<?php echo $fdesc; ?>')"></span>
				</span>

				<span class="details"><span class="fsize"><?php echo formatSizeUnits($headarr['ContentLength']); ?></span><span class="glyphicon glyphicon-download downloadf" onclick="downloadfiles('<?php echo @addslashes($filebasename); ?>')" title="Download"></span></span>
				<span class="ftime"><?php echo $ftime; ?></span>
			</a>
		</li>
<?php
									}
								}
?>
	</ul>
</div>
<div class="nothingfound">
	<div class="nofiles"></div>
	<span></span>
</div>
<div id="<?php echo $uqid; ?>dialog" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;text-align:center;}</style>
<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<!--<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>-->
<script>
function downloadfiles(filename){
	window.location.href ="assets/includes/filedownload.inc.php?filename="+filename+"&ticket=<?php echo $s3sid; ?>&type=fidownload";
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
		$.post("assets/includes/s3filepermission_2.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $s3sid; ?>,
		  type: "fidelete"
		},
		function(rurl){
		  if(rurl == true){
			parent.$('#fis3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&fimasterid=<?php echo $s3sid; ?>');
			Swal.fire("","Deleted Successfully!", "success");
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
		$.post("assets/includes/s3filepermission_2.inc.php",
		{
		  filename: filename,
		  ticket: <?php echo $s3sid; ?>,
		  fvalue: text,
		  type: "fifiledesc"
		},
		function(rurl){
		  if(rurl == true){
			//if(value==""){value="Add Description";}
			//$('#'+descid+'').text(value);
			Swal.fire("","File description added successfully!", "success");
			parent.$('#fis3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&fimasterid=<?php echo $s3sid; ?>');
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
			$.post("assets/includes/s3filepermission_2.inc.php",
			{
			  filename: filename,
			  ticket: <?php echo $s3sid; ?>,
			  fvalue: efname,
			  fdesc: fdescs,
			  type: "fifilename"
			},
			function(rurl){
			  if(rurl == true){
				//if(value==""){value="Add Description";}
				//$('#'+descid+'').text(value);
				Swal.fire("","File description added successfully!", "success");
				parent.$('#fis3display').load('assets/ajax/default-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&fimasterid=<?php echo $s3sid; ?>');
			  }else if(rurl==6){Swal.fire("","File name already exists!", "warning");}
				else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
		}else{Swal.fire("","Filename cannot be empty!", "warning");}
	}
}

async function previewPop(filename){
	$.post("assets/includes/s3filepermission_2.inc.php",
	{
	  filename: filename,
	  ticket: <?php echo $s3sid; ?>,
	  type: "fiview"
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
			parent.$("#<?php echo $uqid; ?>dialog").html('');
			parent.$("#<?php echo $uqid; ?>dialog").html(discode);
			parent.$("#<?php echo $uqid; ?>dialog").dialog("open");
			parent.$("#d-download").attr('href', rawourl);
			parent.$("#d-download").css({"text-align": "center"});
			parent.$("#b-conts").css({"text-align": "center"});
      parent.$("#<?php echo $uqid; ?>dialog").dialog('option', 'title', rname);
      parent.$("#d-cancel").click(function() {
        parent.$("#<?php echo $uqid; ?>dialog").dialog('close');
        return false;
      });
	  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
	});
}
$(document).ready(function(){
	filename="";
	$( "#<?php echo $uqid; ?>dialog" ).dialog({
	  height: screen.height,
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
});
</script>
</script>
<?php
								//echo implode(", ",$tmpfilearr);
								}

							}//else $s3error=1;
				}
			}else $s3error=4;
		}else $s3error=5;
	}


/////////////////////
///Focus Items ends
////////////////////



}



if($s3error != 0 and !isset($_GET["s3drpdisplay"])){echo $s3error ."<p style='text-align:center;'>Error Occured!. Please Try after sometimes.</p>";}
else if($s3error != 0 and isset($_GET["s3drpdisplay"])){echo false;}
else if($s3error == 0 and isset($_GET["s3drpdisplay"])){echo json_encode(array("error"=>"","s3files"=>$tmpfilearr));}
else echo false;

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
