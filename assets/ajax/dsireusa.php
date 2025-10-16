<?php require_once("inc/init.php");
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
if(isset($_GET["state"]) and !empty($_GET["state"])) $state=$_GET["state"];
else $state="";

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

$_COOKIE["docname"] = "Projects:Rebates and Incentives";
$_SESSION["docname"] = "Projects:Rebates and Incentives";
$_COOKIE["appurl"] = APP_URL;
$_SESSION["appurl"] = APP_URL;
$_COOKIE["uid"] = $user_one;
?>
	<style>
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	.fullwidth{width:100% !important;}
	.padd12{padding:12px !important;}
	.tcenter{text-align:center;}
	.fnone{float:none !important;}
	#list-dsireusa{border: 1px solid #ccc !important;margin:-14px;}
	.margin32{margin-top:32px;}
	.margin-32{margin-top:-32px;}
	</style>

	<div class="row fixed1">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Projects
				<span>>
					Rebates & Incentives
				</span>
			</h1>
		</div>
	</div>


	<section id="widget-grid" class="fixed1">

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div align="center" style="padding-bottom:10px;">
					<button class="btn-primary wf-show1" align="center" id="button151" style="height: 30px !important;width: auto !important;">Rebates and Incentives</button>
					<button class="btn-primary wf-show2" align="center" id="button161" style="height: 30px !important;width: auto !important;">Documents</button>
				</div>
			</article>
		</div>
		<!-- end row -->

	</section>

	<!-- widget grid -->
	<section id="widget-grid-Out" class="eitable wfresponse1">

				<!-- row -->
				<div class="row">
				<div class="jarviswidget jarviswidget-color-blueDark padd12" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Rebates and Incentives</h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
						<div class="widget-body no-padding" style="padding:1% !important;width:auto !important;">
							<div id="mapcontainer"><iframe id="incetivesmap" src="" height="600px" width="100%" frameBorder="0"></iframe></div>
							<div id="list-dsireusa">
							</div>
						</div>


					</div>
				</div>

		</div>
		<!-- end row -->

	</section>
	<!-- end widget grid -->
	<section id="dsireusadetails" class=""></section>



	<section id="widget-grid-in" class="eitable wfresponse2 s3section fixed1">
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
					.s3section{margin-top:60px !important;}
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

				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">

					<article class="col-sm-12 p0">
				<?php
					if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
					{
						$cname="";
			?>
					<div class="row">
						<form id="s3cidform" class="smart-form" novalidate="novalidate">
							<fieldset>
								<section class="col col-6"><b>Select Company: </b>
										<select name="s3cid" id="s3cid" placeholder="Read" class="">
										<?php
												if ($stmtttt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM company c, user u WHERE c.company_id=u.company_id and (u.usergroups_id=3 or u.usergroups_id=5)')) {
													$scnt=0;
													$stmtttt->execute();
													$stmtttt->store_result();
													if ($stmtttt->num_rows > 0) {
														$stmtttt->bind_result($company_id,$company_name);
														while($stmtttt->fetch()){
															if($scnt==0){$cname=$company_id; ++$scnt;}
														?>
														<option value="<?php echo $company_id; ?>">&nbsp;&nbsp;<?php echo $company_name; ?></option>
														<?php
														}
													}
												}
										?>
										</select>
								</section>
								<section class="col col-6">
								</section>
							</fieldset>
						</form>
					</div>
				<?php
					}elseif(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){ $cname=$_SESSION["company_id"]; }
				?>
					<div class="row" id="navheader"></div>
							<!-- widget div-->
						<div id="iframe_container" style="position: fixed; left: 0%; top: 276px; right: 0%; bottom: 165px; background: white; ">
							<iframe id="s3browse" name="s3browse" scrolling="no" style="position: absolute; height:100%; width:100%;border:none;z-index:-11;" src="assets/includes/s3browser.inc.php?ct=<?php echo rand(5,100); ?>&docname=&company_id=<?php echo $cname; ?>" onload = "document.body.style.height = frames.s3browse.document.body.offsetHeight + parseInt(document.getElementById('iframe_container').style.top) + parseInt(document.getElementById('iframe_container').style.bottom) + 'px'" >
							</iframe>
						</div>
							<!-- end widget div -->
					</article>




<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
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
<?php } ?>



</section>
<div id="dialog" title="Preview"></div>
<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<?php } ?>
	<script type="text/javascript">
		/* DO NOT REMOVE : GLOBAL FUNCTIONS!
		 *
		 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
		 *
		 * // activate tooltips
		 * $("[rel=tooltip]").tooltip();
		 *
		 * // activate popovers
		 * $("[rel=popover]").popover();
		 *
		 * // activate popovers with hover states
		 * $("[rel=popover-hover]").popover({ trigger: "hover" });
		 *
		 * // activate inline charts
		 * runAllCharts();
		 *
		 * // setup widgets
		 * setup_widgets_desktop();
		 *
		 * // run form elements
		 * runAllForms();
		 *
		 ********************************
		 *
		 * pageSetUp() is needed whenever you load a page.
		 * It initializes and checks for all basic elements of the page
		 * and makes rendering easier.
		 *
		 */

		pageSetUp();

		// PAGE RELATED SCRIPTS

		// pagefunction

		var pagefunction = function() {

			// fix table height
			tableHeightSize();

			$(window).resize(function() {
				tableHeightSize()
			})
			function tableHeightSize() {

				if ($('body').hasClass('menu-on-top')) {
					var menuHeight = 68;
					// nav height

					var tableHeight = ($(window).height() - 224) - menuHeight;
					if (tableHeight < (320 - menuHeight)) {
						$('.table-wrap').css('height', (320 - menuHeight) + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}

				} else {
					var tableHeight = $(window).height() - 224;
					if (tableHeight < 320) {
						$('.table-wrap').css('height', 320 + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}

				}

			}
		};

		// end pagefunction

		// load delete row plugin and run pagefunction

		//loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js", pagefunction);
		//loadScript("js/plugin/bootstraptree/bootstrap-tree.min.js");
		//var pagefunction = function() {
			//loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js");
			//loadScript("/assets/js/plugin/bootstraptree/bootstrap-tree.min.js");

		//};

		// end pagefunction

		// run pagefunction on load

		//pagefunction();

		function load_details(id) {
			//$(window).scrollTop($('#response').offset().top);
			/*$('#response').html('<iframe src="assets/ajax/details.php?id='+id+'" style="width:100%;height:500px" frameBorder="0" scrolling="no"></iframe>');

			$('html, body').animate({
		        scrollTop: $('#response').offset().top
		    }, 2000);*/
			//$(".dsireusatable").fadeOut( "slow" );
			$('#dsireusadetails').load('assets/ajax/dsireusadetails.php?<?php if(isset($_GET["sid"])){?>noback=true&<?php } ?>id='+id+'<?php if(isset($_GET["state"])){?>&state=<?php echo $state; } ?>');
			$('html, body').animate({
        scrollTop: $("#dsireusadetails").offset().top
	    }, 1000);
		}

	window.scrollTo(0,0);
	$(document).ready(function(){
		$('.wfresponse1').show();
		$('.wfresponse2').hide();
		$('.fixed1').removeClass('fixed');
		$('#widget-grid').removeClass('margin32');
		$('#widget-grid article').addClass('margin-32');
		$(".wf-show1").click(function(){
			$('.wfresponse1').show();
			$('.wfresponse2').hide();
			$('.fixed1').removeClass('fixed');
			$('#widget-grid').removeClass('margin32');
			$('#widget-grid article').addClass('margin-32');
		});
		$(".wf-show2").click(function(){
			$('.wfresponse2').show();
			$('.wfresponse1').hide();
			//$('#dsireusadetails').hide();
			$('#dsireusadetails').html('');
			$('.fixed1').addClass('fixed');
			$('#widget-grid').addClass('margin32');
			$('#widget-grid article').removeClass('margin-32');
		});

		$('#incetivesmap').attr('src','assets/ajax/subpages/incetivesmap-edit.php?load=true&ct=<?php echo time(); ?>');
		$('#list-dsireusa').load('assets/ajax/list-dsireusa.php?load=true&ct=<?php echo time(); if(isset($_GET["state"])){?>&state=<?php echo $state; } ?>');







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
	<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5 or $_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1)){ ?>
			open: function (event, ui) {
				   // this is where we add an icon and a link
				   $('#download-d-btn')
					.wrap('<a href="javascript:void(0);" id="d-download" download></a>');

			}
	<?php } ?>
	    });

	//var the_height = document.getElementById('s3browse').contentWindow.document.body.scrollHeight;
	//document.getElementById('s3browse').height = the_height;



	<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
	document.getElementById('s3browse-fileupload').width = screen.width;
		$('#s3cid').on('change', function() {
		   $('#s3browse').attr('src', 'assets/includes/s3browser.inc.php?ct=<?php echo rand(5,100); ?>&docname=&company_id='+this.value);
		});

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
					data.append("ticket", $('#s3cid option:selected').val());
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
			}
		});
	<?php } ?>
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

	<?php if(isset($_SESSION) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){ ?>
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
				//parent.$('#cms3display').load('assets/ajax/start-stop-status-s3display.php?ct=<?php echo rand(0,100); ?>&s3displayfiles=view&contractid=<?php echo $company_id; ?>');
				document.getElementById("s3browse").contentDocument.location.reload(true);
			  }else if(rurl == 9){
				 Swal.fire("","Error Occured! Foldername already exists.", "warning");
			  }else{Swal.fire("","Error Occured! Please try after sometime.", "warning");}
			});
			}
		}
		//location.reload(true);
	}
	<?php } ?>
	</script>
