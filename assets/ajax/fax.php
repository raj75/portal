<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

$ignorelist=array("Archive","Archive 1","Conversation History","Read","RSS Subscriptions","Sync Issues");

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" media="screen, print" href="/assets/js/plugin/dropzone4.0/dropzone.css">
	<script type="text/JavaScript" src="../assets/js/plugin/dropzone4.0/dropzone.js"></script>

	<style>
	#ei_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	#ei_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#ei_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
	#ei_datatable_fixed_column .widget-body,#ei_datatable_fixed_column #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}
	.fullwidth{width:100% !important;}
	.padd5{padding:5px !important;}
	.tcenter{text-align:center;}
	.fnone{float:none !important;}
	</style>

	<br /><br />
	<div class="row">
		<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
			<h1 class="page-title txt-color-blueDark">
				<i class="fa fa-table fa-fw "></i>
					Tools
				<span>>
					Fax
				</span>
			</h1>
		</div>
	</div>




	<section id="widget-grid" class="">

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<!-- Widget ID (each widget will need unique ID)-->
				<div align="center" style="padding-bottom:10px;">
					<button class="btn-primary wf-show1" align="center" id="button151" style="height: 30px !important;width: auto !important;">Outgoing Fax</button>
					<button class="btn-primary wf-show2" align="center" id="button161" style="height: 30px !important;width: auto !important;">Incoming Fax</button>
				</div>
			</article>
		</div>
		<!-- end row -->

	</section>






	<!-- widget grid -->
	<section id="widget-grid-Out" class="eitable wfresponse1">
		<div class="jarviswidget jarviswidget-color-blueDark padd5" id="wid-id-2" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Outgoing Fax</h2>

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

		<!-- row -->
		<div class="row">

			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


						<form id="fax_form" class="smart-form uedit" novalidate="novalidate" method="post" enctype="multipart/form-data" autocomplete="off">

							<fieldset>
								<div class="row">
									<section class="col col-6">Fax Number
										<label class="input"> <i class="icon-prepend fa fa-fax"></i>
											<input type="text" class="fax_number" name="fax_number" id="fax_number" placeholder="Fax Number">
										</label>
									</section>

									<section class="col col-6">Recipient Name
										<label class="input state-success"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="recipient_name" id="recipient_name" placeholder="Recipient Name">
										</label>
									</section>

								</div>
								<div class="row">
									<section class="col col-6">Recipient Company
										<label class="input"> <i class="icon-prepend fa fa-building"></i>
											<input type="text" name="recipient_company" id="recipient_company" placeholder="Recipient Company">
										</label>
									</section>
									<section class="col col-6">Recipient Phone Number
										<label class="input"> <i class="icon-prepend fa fa-phone"></i>
											<input type="text" class="recipient_phone_number" name="recipient_phone_number" id="recipient_phone_number" placeholder="Recipient Phone Number">
										</label>
									</section>

								</div>
								<div class="row">
									<section class="col col-6">Recipient Email Address
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input type="email" name="recipient_email_address" id="recipient_email_address" placeholder="Recipient Email Address">
										</label>
									</section>
									<section class="col col-6">Fax Title
										<label class="input"> <i class="icon-prepend fa fa-bars"></i>
											<input type="text" name="fax_title" id="fax_title" placeholder="Fax Title">
										</label>
									</section>
									<!--
									<section class="col col-6">Cover Page Message
										<label class="input"> <i class="icon-prepend fa fa-bank"></i>
											<input type="text" name="city39115" id="city39115" placeholder="City" value="Kr">
										</label>
									</section>
									-->
								</div>


							</fieldset>

							<fieldset>
								<section>Cover Page Message
									<label class="textarea"> <i class="icon-append fa fa-file-text-o"></i>
										<textarea rows="3" placeholder="Cover Page Message" name="cover_page_message" id="cover_page_message"></textarea>
									</label>
								</section>
							</fieldset>

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

							<footer class="tcenter">
								<button name="submit_fax" type="submit" class="btn btn-primary fnone" id="fax-submit">
									Send Fax
								</button>
								<input type="hidden" name="ar_file_upload" id="ar_file_upload">
							</footer>
						</form>


			</article>
		</div>
		<!-- end row -->
	</div>
	</div>
	</div>

	</section>
	<!-- end widget grid -->

	<script type="text/javascript">

	/*
		Dropzone.autoDiscover = false;

		var myDropzone = new Dropzone(".dropzone", {
		   //autoProcessQueue: false,
		   autoProcessQueue: true,
		   maxFilesize: 1,
		   acceptedFiles: ".jpeg,.jpg,.png,.gif"
		});
	*/
	   /*
		$('#uploadFile').click(function(){
		   myDropzone.processQueue();
		});
       */

	   Dropzone.autoDiscover = false;
	   var myDropz;
	var myDropzone = new Dropzone("div#s3browse-fileupload", {
		paramName: "s3browsefilesupload",
		addRemoveLinks: false,
		url: "assets/ajax/fax_post.php?upload=<?php echo rand(2,99); ?>",
		maxFiles:10,
		uploadMultiple: true,
		parallelUploads:10,
		timeout: 300000,
		maxFilesize: 3000,
		//acceptedFiles: "image/png,.jpg,.mp4,.mov,.webm,",
		acceptedFiles: "image/png,.jpg,application/pdf,.doc,.docx,.xls,.xlsx,.txt",
		//autoProcessQueue: false,
		init: function() {
			myDropz = this;
			//var myDropz = this;

			this.on("sending", function(file, xhr, data) {
				//alert('start');
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
				//$('#ar_file_upload').val()
				document.getElementById('ar_file_upload').value++;
				//alert('end');
			   //myDropz.removeAllFiles(true);
			});
		}
	});


		var $faxForm = $('#fax_form').validate({
			rules : {
				fax_number : {
					required : true
				},
				cover_page_message : {
					required: function() {
                        //returns true if phone is empty
                        return !$("#ar_file_upload").val();
                    }
                },
			    ar_file_upload: {
				    required: function() {
						//returns true if email is empty
						return !$("#cover_page_message").val();
					}
				}
				/*
				recipient_name : {
					required : true
				},
				recipient_company : {
					required : true
				},

				recipient_phone_number : {
					required : true
				},

				recipient_email_address : {
					required : true,
					email: true,
				},
				fax_title : {
					required : true,
				},
				cover_page_message : {
					required : true,
					minlength: 1,
				},
				*/
			},

			// Messages for form validation
			messages : {
				fax_number : {
					required : 'Please enter fax number'
				},
				cover_page_message: { required: "Cover page message is required if no file is selected." },
				ar_file_upload: { required: "Please select a file if no cover page message is entered." },
				/*
				recipient_name : {
					required : 'Please enter recipient name'
				},
				recipient_company : {
					required : 'Please enter recipient company'
				},

				recipient_phone_number : {
					required : 'Please enter recipient phone number'
				},

				recipient_email_address : {
					required : 'Please enter recipient email address'
				},
				fax_title : {
					required : 'Please enter fax title'
				},
				cover_page_message : {
					required : 'Please enter cover page message'
				},
				*/
			},
			// Ajax form submition
			submitHandler : function(form) {
				$.ajax({
					type: 'post',
					url: 'assets/ajax/fax_post.php',
					data: $('#fax_form').serialize(),
					success: function (msg) {

						if (msg == 'success') {
							alert('Email Sent Successfully');
							myDropz.removeAllFiles(true);
							$('#fax_form').trigger("reset");
						}
					}
				});
			},
			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		$(document).ready(function(){
			$('#recipient_phone_number').mask('(999) 999-9999');
			$('#fax_number').mask('(999) 999-9999');
			//$('#recipient_phone_number').mask('(XXX) XXX-XXXX');	not working
		});


	</script>









	<style>
	.inbox-load{cursor:pointer;}
	.flist{margin:0;padding:0;}
	.inbox-body .table-wrap{overflow-x:auto !important;}
	.inbox-nav-bar .page-title{width:auto !important;}
	</style>

	<section id="widget-grid-in" class="eitable wfresponse2">

		<!-- row -->
		<div class="row">
		<div class="jarviswidget jarviswidget-color-blueDark padd5" id="wid-id-3" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-table"></i> </span>
				<h2>Incoming Fax</h2>

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

		<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
		<!-- widget grid -->


				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<form class="smart-form">
						<fieldset>
							<div class="row">
								<section class="col col-6"><b>Select Folder</b>
										<label class="select"> <i class="icon-append fa fa-user"></i>
										<select name="choosecid" id="choosecid" placeholder="Folder Name" class="">
										<?php
										   if ($stmt = $mysqli->prepare('SELECT folderId,`folder_name` FROM fax.folderlist WHERE `order`=1 ORDER BY `folder_name`')){

		//('SELECT id,firstname,lastname FROM user where (usergroups_id=3 or usergroups_id=5) '.$msqll.'  ORDER BY firstname')){

												$stmt->execute();
												$stmt->store_result();
												if ($stmt->num_rows > 0) {
													$ct_count=0;
													$stmt->bind_result($__id,$c_name);
													while($stmt->fetch()){
														if(in_array($c_name,$ignorelist)) continue;
														echo "<option value='".$__id."' ".($ct_count==0?"SELECTED":"").">&nbsp;&nbsp;".$c_name."</option>";
														$ct_count ++;
													}
												}
											}else{
												header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
												exit();
											}
										?>
										</select>
										</label>
								</section>
							</div>
						</fieldset>
					</form>
				</article>

		<?php }else{
			$clientfid="";
			if ($stmt = $mysqli->prepare('SELECT folderid FROM fax.`emails` e, vervantis.company c where e.folderpath=c.email_folder_path and c.company_id='.$cname.' limit 1')){

		//('SELECT id,firstname,lastname FROM user where (usergroups_id=3 or usergroups_id=5) '.$msqll.'  ORDER BY firstname')){

			 $stmt->execute();
			 $stmt->store_result();
			 if ($stmt->num_rows > 0) {
				 $stmt->bind_result($clientfid);
				 $stmt->fetch();
			 }
		 }else{
			 header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			 exit();
		 }
		//$clientfid=1;
		if(empty($clientfid)) die("<h4 align='center'>You have no mails!</h4>");

		} ?>
		<style>
		/*.inbox-side-bar{overflow: scroll !important;}*/
		.flist{
			overflow: scroll !important;
			height: 100% !important;
		}
		</style>
		<div class="inbox-nav-bar no-content-padding">

			<div class="btn-group pull-right inbox-paging hidden">
				<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-left"></i></strong></a>
				<a href="javascript:void(0);" class="btn btn-default btn-sm"><strong><i class="fa fa-chevron-right"></i></strong></a>
			</div>
			<span class="pull-right" style="display:none !important;"><strong>1-30</strong> of <strong>3,452</strong></span>

		</div>

		<div id="inbox-content" class="inbox-body no-content-padding">
			<div style="width:100% !important;margin-left:215px;">
				<button class="btn-primary hidden" align="" id="backtomail" style="float: left;margin-right: 6px;"><span class="glyphicon glyphicon-backward"></span> Back</button>
				<input placeholder="Search Mail" type="text" style="width:25%;" id="mailsearch">
				<button class="btn-primary" align="" id="mail-search">Search</button>
				<button class="btn-primary" align="" id="reset-search">Reset</button>
			</div>
			<div class="inbox-side-bar">

				<h6> Folders <a href="javascript:void(0);" rel="tooltip" title="" data-placement="right" data-original-title="Refresh" class="pull-right txt-color-darken inbox-load"><i class="fa fa-refresh"></i></a></h6>
		<!--<i class="fa fa-plus pull-right"></i>-->
				<div class="flist">LOADING...</div>
			</div>

			<div class="table-wrap custom-scroll animated fast fadeInRight" id="maillists">
				<!-- ajax will fill this area -->
				LOADING...

			</div>
			<div class="table-wrap custom-scroll animated fast fadeInRight hidden" id="maildetails"></div>


		</div>
	</div>


</div>
</div>

</div>
<!-- end row -->

</section>


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

	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
			$('#choosecid').on('change', function() {
				loadInbox();
			});
	<?php } ?>


			/*
			 * LOAD INBOX MESSAGES
			 */
			loadInbox();
			function loadInbox() {
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>

				$("#inbox-content #maildetails").addClass('hidden');
				$("#inbox-content #maillists").removeClass('hidden');
				$("#backtomail").addClass('hidden');
				$("#mailsearch").val('');
				loadURL("/assets/ajax/email/email-list-fax.php?ct=<?php echo time(); ?>&action=folder"+cselected, $('#inbox-content .flist'));
				loadURL("/assets/ajax/email/email-list-fax.php?ct=<?php echo time(); ?>&action=maillist"+cselected, $('#inbox-content #maillists'));
			}

			/*
			 * Buttons (compose mail and inbox load)
			 */
			$(".inbox-load").click(function() {
				loadInbox();
			});

			$("#mail-search").click(function() {
				mailsearch = $("#mailsearch").val();
				/*if(mailsearch == "123"){
					alert("Please enter search text!");
					$("#mailsearch").focus();

				}else{*/
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>
					var fname=$("ul.inbox-menu-lg li.active a").attr("ftype");
					$("#inbox-content #maildetails").addClass('hidden');
					$("#inbox-content #maillists").removeClass('hidden');
					$("#backtomail").addClass('hidden');
					//$(".inbox-menu-lg li").removeClass('active');
					//loadURL("/assets/ajax/email/email-list-fax.php?action=folder&search="+mailsearch, $('#inbox-content .flist'));
					loadURL("/assets/ajax/email/email-list-fax.php?ct=<?php echo time(); ?>&action=maillist&search="+mailsearch+"&fname="+fname+cselected, $('#inbox-content #maillists'));
				//}
			});

			$("#reset-search").click(function() {
				$("#mailsearch").val('');
				var cselected="";
	<?php if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){ ?>
				cselected = $("#choosecid option:selected").val();
				if(cselected != ""){
					cselected="&cselected="+cselected;
				}
	<?php } ?>
				var fname=$("ul.inbox-menu-lg li.active a").attr("ftype");
				$("#inbox-content #maildetails").addClass('hidden');
				$("#inbox-content #maillists").removeClass('hidden');
				$("#backtomail").addClass('hidden');
				loadURL("/assets/ajax/email/email-list-fax.php?ct=<?php echo time(); ?>&action=maillist&search=&fname="+fname+cselected, $('#inbox-content #maillists'));
			});

			$(document).on('keypress',function(e) {
				if(e.which == 13) {
					$("#mail-search").click();
				}
			});

			$("#backtomail").click(function() {
				$("#inbox-content #maildetails").addClass('hidden');
				$("#inbox-content #maillists").removeClass('hidden');
				$("#backtomail").addClass('hidden');
				//$("#mailsearch").val();
			});
		};

		// end pagefunction

		// load delete row plugin and run pagefunction

		loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js", pagefunction);
		//loadScript("js/plugin/bootstraptree/bootstrap-tree.min.js");
		//var pagefunction = function() {
			//loadScript("/assets/js/plugin/delete-table-row/delete-table-row.min.js");
			//loadScript("/assets/js/plugin/bootstraptree/bootstrap-tree.min.js");

		//};

		// end pagefunction

		// run pagefunction on load

		//pagefunction();

	$(document).ready(function(){
		$('.wfresponse1').show();
		$('.wfresponse2').hide();
		$(".wf-show1").click(function(){
			$('.wfresponse1').show();
				$('.wfresponse2').hide();
		});
		$(".wf-show2").click(function(){
			$('.wfresponse2').show();
			$('.wfresponse1').hide();
		});
	});
	</script>
