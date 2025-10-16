<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();


//if(checkpermission($mysqli,61)==false) die("Permission Denied! Please contact Vervantis.");

if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){}else die("Permission Denied! Please contact Vervantis.");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];
$_COOKIE["docname"] = "Intranet:General Info";
$_SESSION["docname"] = "Intranet:General Info";
$_COOKIE["appurl"] = APP_URL;
$_SESSION["appurl"] = APP_URL;
$_COOKIE["uid"] = $user_one;
//$company_name
?>
<style>
body{overflow-x: scroll !important;}
.p0{padding:0 !important;}
#s3cidform{font-size:15px;}
#navheader .addfolder{line-height: 2;
    font-size: 19px;
    color: #626569;
    margin-right: 20px;}
#navheader .navback{margin-right:18px;cursor:pointer;}
#navheader .placeright{float:right;}
#navheader .pointercursor{cursor:pointer !important;}
#navheader .breadcrumbs {
    color: #626569;
    margin-left: 20px;
    font-size: 24px;
    font-weight: 700;
    line-height: 35px;
    font-size: 19px;
}
#navheader{margin:0;background:#fff;margin-right:20px;}
#navheader .mr5{margin-right:5px;}
.s3section .ui-dialog{top:125px !important;}
.s3section{margin-top:50px !important;}
body {overflow-y: scroll; overflow-x: auto}
.fixed{position:fixed;width:100%;}
.page-footer{position:fixed;}
html,body{background:#fff;}
#s3browse-fileupload .dz-default{height: 20px !important;top: 65px !important;left: 39% !important;margin:0 !important;padding:0 !important;width:auto !important;}
#s3browsedrp .dropzone .dz-preview .dz-details .dz-size, .dropzone-previews .dz-preview .dz-details .dz-size {
	bottom: -1px !important;
	left: 29px !important;
}
#s3browse-fileupload .dz-message{margin: 1em 0 !important;}
#s3browsedrp{bottom: 55px;padding-right: 4px !important;padding-left:3px !important;}
#s3browse-fileupload{padding:0 !important;min-height: 108px !important;}
.noshow{height: 12%;opacity: 0.8;position: absolute;right: 2%;top: 0 !important;width: 12%;z-index: 9999;} #dialog{overflow:hidden !important;}
.ui-dialog{top:48px !important;position:fixed;}
</style>
<div class="row fixed">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Intranet <span>> General Info</span>
		</h1>
	</div>
</div>
<script>
window.onscroll = function()
{
frames.s3browse.document.documentElement.scrollTop = window.pageYOffset;
frames.s3browse.document.body.scrollTop = window.pageYOffset; // Google Chrome, Safari, documents without valid doctype
}

window.onresize=function()
{
document.body.style.height = frames.s3browse.document.body.offsetHeight + parseInt(document.getElementById('iframe_container').style.top) + parseInt(document.getElementById('iframe_container').style.bottom) + 'px';
}
</script>
<!-- widget grid -->
<section id="widget-grid" class="s3section fixed">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-sm-12 p0">

		<div class="row" id="navheader"></div>
				<!-- widget div-->
			<div id="iframe_container" style="position: fixed; left: 0%; top: 276px; right: 0%; bottom: 165px; background: white; ">
				<iframe id="s3browse" name="s3browse" scrolling="no" style="position: absolute; height:100%; width:100%;border:none;z-index:-11;" src="assets/includes/s3browser.inc.php?ct=<?php echo rand(5,100); ?>&docname=&company_id=<?php echo $cname; ?>" onload = "document.body.style.height = frames.s3browse.document.body.offsetHeight + parseInt(document.getElementById('iframe_container').style.top) + parseInt(document.getElementById('iframe_container').style.bottom) + 'px'" >
				</iframe>
			</div>
				<!-- end widget div -->
		</article>
		<!-- WIDGET END -->
	</div>
	<!-- end row -->

</section>

	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css?v=1">
	<div class="row fixed" id="s3browsedrp">
		<section class="col col-12 fullwidth">
			<div class="dropzone dz-clickable" id="s3browse-fileupload">
					<div class="dz-message needsclick">
						<i class="fa fa-cloud-upload text-muted mb-3"></i> <br>
						<span class="text-uppercase">Drop files here or click to upload.</span>
					</div>
			</div>
		</section>
	</div>

<div id="dialog" title="Preview"></div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js"></script>
  <script>
  var script = document.createElement("script");
  script.src = "../assets/js/plugin/dropzone4.0/dropzone.js?v=1";
  script.onload = loadedContent;
  document.head.append(script);

  function loadedContent(){
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("div#s3browse-fileupload", {
      paramName: "s3browsefilesupload",
      addRemoveLinks: false,
      url: "assets/includes/s3filepermission.inc.php?ct=<?php echo rand(2,99); ?>",
      maxFiles:10,
      uploadMultiple: true,
      parallelUploads:10,
      timeout: 300000,
      maxFilesize: 3000,
      //autoProcessQueue: false,
      init: function() {
        myDropz = this;

        this.on("sending", function(file, xhr, data) {
          data.append("ticket", <?php echo $cname; ?>);
        });
        myDropz.on("successmultiple", function(file, result) {
          if (result != false)
          {
            var results = JSON.parse(result);
            if(results.error == "")
            {
              //Swal.fire("Thank you for your request.","You can view the status in the Start/Stop Status page", "success");
              document.getElementById("s3browse").contentDocument.location.reload(true);
            }else if(results.error == 5)
            {
              Swal.fire("Error in request.","Please try again later.", "warning");
            }else{
              Swal.fire("Error in request.","Please try again later.", "warning");
            }
          }else{
            Swal.fire("","Error in request. Please try again later.", "warning");
          }
        });
        myDropz.on("complete", function(file) {
           myDropz.removeAllFiles(true);
        });
        myDropz.on("uploadprogress", function(file, progress, bytesSent) {
          if (file.previewElement) {
              var progressElement = file.previewElement.querySelector("[data-dz-uploadprogress]");
              progressElement.style.width = progress + "%";
              file.previewElement.querySelector(".progress-text").textContent = Math.ceil(progress) + "%";
          }
        });
      }
    });
  }
  </script>

<script type="text/javascript">
$(document).ready(function(){
	filename="";
	var theight=$(document).height();
$( "#dialog" ).dialog({
	  /*height: $(document).height(),*/
	  height: (screen.height*0.78),
      /*width: ((95*theight)/100),*/
      show: "fade",
      hide: "fade",
	  title: 'Preview',
	  resizable: false,
	  //bgiframe: true,
      modal: true,
	  autoOpen: false,

		open: function (event, ui) {
			   // this is where we add an icon and a link
			   $('#download-d-btn')
				.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

		},
    close: function(event, ui)
    {
        $(this).dialog("close");
        $(this).empty();
    }

    });

//var the_height = document.getElementById('s3browse').contentWindow.document.body.scrollHeight;
//document.getElementById('s3browse').height = the_height;

document.getElementById('s3browse-fileupload').width = screen.width;
	$('#s3cid').on('change', function() {
	   $('#s3browse').attr('src', 'assets/includes/s3browser.inc.php?ct=<?php echo rand(5,100); ?>&docname=&company_id='+this.value);
	});
});

function navfolder(fnav,cmpid,ltype){
	if(ltype==''){ltype=0;}
	$('#s3browse').attr('src', 'assets/includes/s3browser.inc.php?ct=<?php echo rand(5,100); ?>&docname='+fnav+'&company_id='+cmpid+'&listview='+ltype);
}

function listview(ltype){
	if(ltype==''){ltype=0;}
	var iurl=document.getElementById("s3browse").contentWindow.location.href;
	if(iurl.indexOf("listview") != -1){
	//document.getElementById("s3browse").contentDocument.location.reload(true);
		if(ltype==1){
			$('#s3browse').attr('src',iurl.replace("listview=0", "listview="+ltype));
		}else{
			$('#s3browse').attr('src',iurl.replace("listview=1", "listview="+ltype));
		}
	}else{
		$('#s3browse').attr('src',iurl+"&listview="+ltype);
	}
}

async function addfolder(cmpid){
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
		$.post("assets/includes/s3filepermission.inc.php",
		{
		  ticket: cmpid,
		  fvalue: afold,
		  type: "s3clfolderadd"
		},
		function(rurl){
		  if(rurl == true){
			//if(value==""){value="Add Description";}
			//$('#'+descid+'').text(value);
			Swal.fire("","Folder added successfully!", "success");
			//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php /*echo $company_id;*/ ?>');
			document.getElementById("s3browse").contentDocument.location.reload(true);
		  }else if(rurl == 9){
			 Swal.fire("","Error Occured! Foldername already exists.", "warning");
		  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
		});
		}
	}
	//location.reload(true);
}
</script>
