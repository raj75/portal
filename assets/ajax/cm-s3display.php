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

if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 4 or $_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){


	if(isset($_GET) and isset($_GET["ctid"]) and $_GET["ctid"] != "" and  isset($_GET["display"])){
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

		$cmid=$mysqli->real_escape_string(@trim($_GET['ctid']));

		if ($stmt = $mysqli->prepare('SELECT distinct cm.ContractID,cm.s3_foldername,c.company_id FROM contracts cm JOIN vendor v JOIN user u JOIN company c WHERE v.vendor_id=cm.SupplierID and cm.ClientID=c.company_id'.(($_SESSION["group_id"] == 3 || $_SESSION["group_id"] == 5)?' and c.company_id=u.company_id and  u.user_id="'.$user_one.'"':'').' and cm.ContractID="'.$cmid.'" LIMIT 1')) {

			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($ctid,$s3_foldername,$company_id);
				$stmt->fetch();

				if(($group_id==3 or $group_id==5) and $company_id != $cname) {echo "<p style='text-align:center;'>Error occured. Please try after sometime.</p>";die();}

				if($group_id==1 or $group_id==2) $cname=$company_id;

				if($s3_foldername==""){echo "<p style='text-align:center;'>Nothing to show!</p>";}
				else{


					if ($stmtttt = $mysqli->prepare('SELECT company_name FROM company WHERE company_id='.$cname)) {
						$stmtttt->execute();
						$stmtttt->store_result();
						if ($stmtttt->num_rows > 0) {
							$stmtttt->bind_result($company_name);
							$stmtttt->fetch();

							$infotarget = $s3Client->doesObjectExist('datahub360', 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/');
							if($infotarget)
							{
								$objects = $s3Client->getIterator('ListObjects', array(
									"Bucket" => "datahub360",
									"Prefix" => 'resources/Clients/'.$company_name.'/Supplier Contracts/'.$s3_foldername.'/'
								));
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

.s3filemanager .data li .name {
    color: #333;
    font-size: 15px;
    font-weight: 700;
    line-height: 20px;
    width: 150px;
    white-space: nowrap;
    display: inline-block;
    position: absolute;
    overflow: hidden;
    text-overflow: ellipsis;
    top: 40px;
}

.s3filemanager .data li .details {
    color: #b6c1c9;
    font-size: 13px;
    font-weight: 400;
    width: 55px;
    height: 10px;
    top: 64px;
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

.noshow{
	height: 18%;
    opacity: 0.1;
    position: absolute;
    right: 6%;
    top: 0;
    width: 33%;
    z-index: 9999;
}
</style>
<div class="s3filemanager">
	<ul class="data animated" style="">
<?php
								$tmpfilearr=array();
								foreach ($objects as $object){
									$fileext=pathinfo($object['Key'], PATHINFO_EXTENSION);
									if ($fileext){
										$filebasename=basename($object['Key']);
?>
		<li class="files">
			<a href="javascript:void(0);" title="<?php echo $filebasename; ?>" class="files" onclick="previewPop('<?php echo $filebasename; ?>')">
				<span class="icon file f-<?php echo $fileext; ?>">.<?php echo $fileext; ?></span>
				<span class="name"><?php echo $filebasename; ?></span>
				<span class="details"><?php echo formatSizeUnits($object['Size']); ?></span>
			</a>
		</li>
<?php
									}
									//echo basename$object['Key'] . "<br>";
								}
?>
	</ul>
</div>
<div class="nothingfound">
	<div class="nofiles"></div>
	<span></span>
</div>
<div id="cmdialog" title="Preview"></div>
<style>.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0;width: 12%;z-index: 9999;} #cmdialog{overflow:hidden !important;text-align:center;}</style>
<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="../assets/js/plugin/sweetalert/sweetalert.js"></script>
<script>
$(document).ready(function(){

});
function previewPop(filename){
	$.post("assets/includes/s3filepermission.inc.php",
	{
	  filename: filename,
	  ticket: <?php echo $cmid; ?>,
	  type: "cm"
	},
	function(rurl){
	  if(rurl !=false){var actualfile=filename; filename=rurl;
		  var file = filename.replace(/\.\.\//gi,'');
		  var exts = ['jpg','jpeg','gif','png','tif','bmp','ico'];
		  var extspdf = ['pdf'];
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
			  $("#cmdialog").html('');
			  $("#cmdialog").html(discode);
			  $("#cmdialog").dialog("open");
			  $("#cmd-download").attr('href', filename);
			} else if ( $.inArray ( get_ext.toLowerCase(), extspdf ) > -1 ){//alert(rurl);
			  discode='<object type="text/html" data="assets/plugins/pdfjs/web/viewer.php?file='+encodeURIComponent(rurl)+'&fname='+actualfile+'" style="overflow:auto;width:100%;height:85vh;"></object>';
			  $("#dialog").html('');
			  $("#dialog").html(discode);
			  $("#dialog").dialog("open");
			  $("#d-download").attr('href', filename);
			} else {
				filename=encodeURIComponent(filename);
			  discode='<iframe src="https://docs.google.com/viewer?embedded=true&url='+filename+'" frameborder="0" width="100%" height="100%" id="googleload"></iframe><a href="'+filename+'" download><div class="noshow"></div></a>';
			  $("#cmdialog").html('');
			  $("#cmdialog").html(discode);
			  $("#cmdialog").dialog("open");
			  $("#cmd-download").attr('href', filename);
			}
		  }else{swal("","Error Occured! Please try after sometime.", "warning");}
	  }else{swal("","Error Occured! Please try after sometime.", "warning");}
	});
}
$(document).ready(function(){
	filename="";
	$( "#cmdialog" ).dialog({
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
			   // this is where we add an icon and a link
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="cmd-download" download></a>');

		}
<?php } ?>
    });
});
</script>
</script>
<?php
								//echo implode(", ",$tmpfilearr);

							}else $s3error=1;
						}else $s3error=2;
					}else $s3error=3;
				}
			}else $s3error=4;
		}else $s3error=5;
	}else $s3error=6;

}else $s3error=7;

if($s3error != 0){echo $s3error ."<p style='text-align:center;'>Error Occured!. Please Try after sometimes.</p>";}



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
