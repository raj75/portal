<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!$_SESSION["group_id"] ==1 or !$_SESSION["group_id"] ==2 or !isset($_SESSION["user_id"]))
	die("Access Restricted.");
		
$user_one=$_SESSION["user_id"];
?>
	<style>
#ribbon .breadcrumb{top:0 !important;}
img {
    border: 0 none;
    max-width: 100%;
}
.page-header h1 {
    color: #efefef;
    font-size: 3.26em;
    text-align: center;
    text-shadow: 1px 1px 0 #000;
}
.timeline {
    list-style: outside none none;
    padding: 20px 0;
    position: relative;
}
.timeline::before {
    background-color: #eee;
    bottom: 0;
    content: " ";
    left: 50%;
    margin-left: -1.5px;
    position: absolute;
    top: 19px;
    width: 3px;
}
.tldate {
    background: #fff none repeat scroll 0 0;
    border: 3px solid #212121;
    color: #000;
    display: block;
    font-weight: bold;
    margin: 0 auto;
    padding: 3px 0;
    text-align: center;
    width: 200px;
}
.timeline li {
    margin-bottom: 25px;
    position: relative;
}
.timeline li::before, .timeline li::after {
    content: " ";
    display: table;
}
.timeline li::after {
    clear: both;
}
.timeline li::before, .timeline li::after {
    content: " ";
    display: table;
}
.timeline li .timeline-panel {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #d4d4d4;
    border-radius: 8px !important;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.15);
    float: left;
    padding: 20px;
    position: relative;
    width: 46%;
}
.timeline li .timeline-panel::before {
    border-color: transparent #ccc;
    border-style: solid;
    border-width: 15px 0 15px 15px;
    content: " ";
    display: inline-block;
    position: absolute;
    right: -15px;
    top: 26px;
}
.timeline li .timeline-panel::after {
    border-color: transparent #fff;
    border-style: solid;
    border-width: 14px 0 14px 14px;
    content: " ";
    display: inline-block;
    position: absolute;
    right: -14px;
    top: 27px;
}
.timeline li .timeline-panel.noarrow::before, .timeline li .timeline-panel.noarrow::after {
    border: 0 none;
    display: none;
    right: 0;
    top: 0;
}
.timeline li.timeline-inverted .timeline-panel {
    float: right;
}
.timeline li.timeline-inverted .timeline-panel::before {
    border-left-width: 0;
    border-right-width: 15px;
    left: -15px;
    right: auto;
}
.timeline li.timeline-inverted .timeline-panel::after {
    border-left-width: 0;
    border-right-width: 14px;
    left: -14px;
    right: auto;
}
.timeline li .tl-circ {
    background: #6a8db3 none repeat scroll 0 0;
    border: 3px solid #90acc7;
    border-radius: 50% !important;
    color: #fff;
    height: 35px;
    left: 50%;
    line-height: 35px;
    margin-left: -16px;
    position: absolute;
    text-align: center;
    top: 23px;
    width: 35px;
    z-index: 3;
}
.tl-heading h4 {
    color: #c25b4e;
    margin: 0;
}
.tl-body p, .tl-body ul {
    margin-bottom: 0;
}
.tl-body > p + p {
    margin-top: 5px;
}
@media (max-width: 991px) {
.timeline li .timeline-panel {
    width: 44%;
}
}
@media (max-width: 700px) {
.page-header h1 {
    font-size: 1.8em;
}
ul.timeline::before {
    left: 40px;
}
.tldate {
    width: 140px;
}
ul.timeline li .timeline-panel {
    width: calc(100% - 90px);
}
ul.timeline li .tl-circ {
    left: 22px;
    margin-left: 0;
    top: 22px;
}
ul.timeline > li > .tldate {
    margin: 0;
}
ul.timeline > li > .timeline-panel {
    float: right;
}
ul.timeline > li > .timeline-panel::before {
    border-left-width: 0;
    border-right-width: 15px;
    left: -15px;
    right: auto;
}
ul.timeline > li > .timeline-panel::after {
    border-left-width: 0;
    border-right-width: 14px;
    left: -14px;
    right: auto;
}
	</style>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Company News
		</h1>
	</div>
</div>
<section id="widget-grid" class="accountstable">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
	<div id="form-post" style="display:none;">
		<form id="form-new-post" enctype="multipart/form-data">
			<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="new-post-title" id="new-post-title" size="100"></p>
			<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
			<textarea name="new-post" id="new-post" placeholder="Enter post description"></textarea>
			<br />
		</form>
	</div>
	<p><button class="btn btn-primary blog-post-btn" id="create-new-post">Create new company news</button>&nbsp;<button class="btn btn-primary blog-post-btn" id="submit-new-post" style="display:none;">Submit</button></p>
	<br />
	<hr id="breakline">
<?php } ?>		
			<ul class="timeline">
<?php
		$tmp_posts=array();
		$tmp_count=1;
		//$currdatetime = date('Y/m/d H:i:s', time());
		$stmt = $mysqli->prepare('SELECT cn.id, cn.user_id, cn.post_title, cn.post_cont,cn.post_banner, cn.datetime, cn.status,u.firstname,u.lastname FROM company_news cn,user u where cn.user_id=u.user_id ORDER BY cn.datetime DESC');
if(!$stmt){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
//('SELECT cn.id, cn.user_id, cn.post_title, cn.post_cont,cn.post_banner, cn.datetime, cn.status,u.firstname,u.lastname FROM company_news cn,user u where cn.user_id=u.id ORDER BY cn.datetime DESC');

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$postUser="No Name";
			$stmt->bind_result($postID,$postUserID,$postTitle,$postCont,$postBanner,$postDate,$postStatus,$rfirstname,$rlastname);
			while($stmt->fetch()){	
					$postUser=$rfirstname." ".$rlastname;
					
					$tmp_posts[date('M Y', strtotime($postDate))][]=array("pid"=>$postID,"puid"=>$postUserID,"ptitle"=>$postTitle,"pdesc"=>$postCont,"pbanner"=>$postBanner,"pdate"=>$postDate,"pstatus"=>$postStatus,"puser"=>$postUser);
			}
			//print_r($tmp_posts);
			foreach($tmp_posts as $ky => $vl){
				echo '<li class="dtitle"><div class="tldate">'.$ky.'</div></li>';
				foreach($vl as $kys => $vls){
?>
			<li id="cn<?php echo $vls["pid"]; ?>" class="dis-content<?php if($tmp_count % 2 == 0){echo " timeline-inverted";} ?>">
			  <div class="tl-circ"></div>
			  <div class="timeline-panel">
				<div class="tl-heading">
				  <h4><?php echo $vls["ptitle"]; ?></h4>
				  <p><i class="text-muted">Published by <span class="text-info"><?php echo $postUser; ?></span></i><p>
				  <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php	echo date('M d, Y', strtotime($vls["pdate"])); ?></small></p>
				</div>
				<div class="tl-body">
				  <?php echo $vls["pdesc"]; ?>
				</div>
				<p>&nbsp;</p>
				<div>
						<a class="btn btn-warning edit-post-button" href="javascript:void(0);" data-pid="<?php echo $vls["pid"]; ?>"> Edit </a>
						<a class="btn btn-success publishBlog" href="javascript:void(0);" data-pid="<?php echo $vls["pid"]; ?>"><?php echo ($postStatus==1?"Unpublish It":"Publish It"); ?></a>
						<a class="btn btn-danger delete-post-button" href="javascript:void(0);" data-pid="<?php echo $vls["pid"]; ?>"> Delete </a>
				</div>
				<div id="edit-form-post<?php echo $vls["pid"]; ?>" style="clear:both;display:none;margin-top:20px;padding:10px">
					<hr>
					<form id="form-edit-post<?php echo $vls["pid"]; ?>" class="edit-post" enctype="multipart/form-data">
						<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-title" id="edit-post-title<?php echo $vls["pid"]; ?>" size="69" value="<?php echo $vls["ptitle"]; ?>"></p>
						<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
						<textarea name="edit-post" id="edit-post<?php echo $vls["pid"]; ?>" placeholder="Enter post description"><?php echo $vls["pdesc"]; ?></textarea>
						<input type="hidden" name="post-id" value="<?php echo $vls["pid"]; ?>">
						<button class="btn btn-primary" type="submit" id="submit-edit-post<?php echo $vls["pid"]; ?>">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-post" type="button" data-epid="<?php echo $vls["pid"]; ?>">Cancel</button>
						<br />
					</form>
				</div>

			  </div>
			</li>
<?php
					$tmp_count++;
				}
			}
		} 
?>
			</ul>
		</article>
	</div>
</section>
<script src="assets/js/plugin/ckeditor/ckeditor.js"></script>
<script src="assets/js/jquery.showmore.min.js"></script>
<script src="assets/js/base64_decode.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
	my_editor_new=null;
	var my_editor = [];

 
	function CKupdate(){
		for ( instance in CKEDITOR.instances )
			CKEDITOR.instances[instance].updateElement();
	}

	$( document ).off( "submit", "form#form-new-post");
	$( document ).on( "submit", "form#form-new-post", function() {
		CKupdate();
	  //disable the default form submission
	  //event.preventDefault();
		var formData = new FormData($(this)[0]);
		$.ajax({
			url: 'assets/includes/companynews.inc.php',
			type: 'POST',
			data: formData,
			async: false,
			success: function (data) {
			  if(data != false)
			  {
				var result = JSON.parse(data);
				if(result.error == false)
				{
					var displayCNedit = "";
					displayCNedit = '<a class="btn btn-warning edit-post-button" href="javascript:void(0);" data-pid="'+result.id+'"> Edit </a>&nbsp;<a class="btn btn-success publishBlog" href="javascript:void(0);" data-pid="'+result.id+'">'+((result.publish != "Y")?"Publish It":"Unpublish It")+'</a><a class="btn btn-danger delete-post-button" href="javascript:void(0);" data-pid="'+result.id+'"> Delete </a>';

					if($( ".tldate" ).first().html() == result.catdatetime){
						$( ".tldate" ).first().parent().after('<li id="cn'+result.id+'" class="dis-content"><div class="tl-circ"></div><div class="timeline-panel"><div class="tl-heading"><h4>'+result.cnTitle+'</h4><p><i class="text-muted">Published by <span class="text-info">'+result.cnUser+'</span></i><p><p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> '+result.datetime+'</small></p></div><div class="tl-body"> '+base64_decode(result.cnContent)+'</div><p>&nbsp;</p><div> '+displayCNedit+'</div><div id="edit-form-post'+result.id+'" style="clear:both;display:none;margin-top:20px;padding:10px"><hr><form id="form-edit-post'+result.id+'" class="edit-post" enctype="multipart/form-data">	<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-title" id="edit-post-title'+result.id+'" size="69" value="'+result.cnTitle+'"></p><p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p><textarea name="edit-post" id="edit-post'+result.id+'" placeholder="Enter post description">'+base64_decode(result.cnContent)+'</textarea><input type="hidden" name="post-id" value="'+result.id+'"><button class="btn btn-primary" type="submit" id="submit-edit-post'+result.id+'">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-post" id="cancel-edit-post'+result.id+'" data-epid="'+result.id+'">Cancel</button><br /></form></div></div></li>');
					}else{
						$(".timeline").prepend('<li class="dtitle"><div class="tldate">'+result.catdatetime+'</div></li><li id="cn'+result.id+'" class="dis-content"><div class="tl-circ"></div><div class="timeline-panel"><div class="tl-heading"><h4>'+result.cnTitle+'</h4><p><i class="text-muted">Published by <span class="text-info">'+result.cnUser+'</span></i><p><p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> '+result.datetime+'</small></p></div><div class="tl-body"> '+base64_decode(result.cnContent)+'</div><p>&nbsp;</p><div> '+displayCNedit+'</div><div id="edit-form-post'+result.id+'" style="clear:both;display:none;margin-top:20px;padding:10px"><hr><form id="form-edit-post'+result.id+'" class="edit-post" enctype="multipart/form-data">	<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-title" id="edit-post-title'+result.id+'" size="69" value="'+result.cnTitle+'"></p><p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p><textarea name="edit-post" id="edit-post'+result.id+'" placeholder="Enter post description">'+base64_decode(result.cnContent)+'</textarea><input type="hidden" name="post-id" value="'+result.id+'"><button class="btn btn-primary" type="submit" id="submit-edit-post'+result.id+'">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-post" id="cancel-edit-post'+result.id+'" data-epid="'+result.id+'">Cancel</button><br /></form></div></div></li>');
					}					
					my_editor_new.destroy(true);


					$( "#form-post" ).toggle();
					if($( "#create-new-post" ).text() == "Create new company news")
						{
							$( "#create-new-post" ).text("Cancel");
							my_editor_new = CKEDITOR.replace("new-post");
						}
					else
						{
							$( "#create-new-post" ).text("Create new company news");
							my_editor_new.destroy(true);
						}

					$( "#submit-new-post" ).toggle();
					
					resetpostorder();
				}else{
					alert(result.error);
				}
			  }else{
				alert("Error");
			  }
			},
			cache: false,
			contentType: false,
			processData: false
		});

		return false;
	});

$( document ).off( "submit", "form.edit-post");
$( document ).on( "submit", "form.edit-post", function() {
	CKupdate();
	  //disable the default form submission
	  //event.preventDefault();
		var formData = new FormData($(this)[0]);

		$.ajax({
			url: 'assets/includes/companynews.inc.php',
			type: 'POST',
			data: formData,
			async: false,
			success: function (data) {
			  if(data != false)
			  {
				var result = JSON.parse(data);
				if(result.error == false)
				{
					var displayCNedit = "";
					displayCNedit = '<a class="btn btn-warning edit-post-button" href="javascript:void(0);" data-pid="'+result.id+'"> Edit </a>&nbsp;<a class="btn btn-success publishBlog" href="javascript:void(0);" data-pid="'+result.id+'">'+((result.publish != "Y")?"Publish It":"Unpublish It")+'</a><a class="btn btn-danger delete-post-button" href="javascript:void(0);" data-pid="'+result.id+'"> Delete </a>';
					
					$("#cn"+result.id).html('<div class="tl-circ"></div><div class="timeline-panel"><div class="tl-heading"><h4>'+result.cnTitle+'</h4><p><i class="text-muted">Published by <span class="text-info">'+result.cnUser+'</span></i><p><p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> '+result.datetime+'</small></p></div><div class="tl-body"> '+base64_decode(result.cnContent)+'</div><p>&nbsp;</p><div> '+displayCNedit+'</div><div id="edit-form-post'+result.id+'" style="clear:both;display:none;margin-top:20px;padding:10px"><hr><form id="form-edit-post'+result.id+'" class="edit-post" enctype="multipart/form-data">	<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-title" id="edit-post-title'+result.id+'" size="69" value="'+result.cnTitle+'"></p><p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p><textarea name="edit-post" id="edit-post'+result.id+'" placeholder="Enter post description">'+base64_decode(result.cnContent)+'</textarea><input type="hidden" name="post-id" value="'+result.id+'"><button class="btn btn-primary" type="submit" id="submit-edit-post'+result.id+'">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-post" id="cancel-edit-post'+result.id+'" data-epid="'+result.id+'">Cancel</button><br /></form></div></div>');
					my_editor[result.id].destroy(true);
				}else{
					alert(result.error);
				}
			  }else{
				alert("Error");
			  }
			},
			cache: false,
			contentType: false,
			processData: false
		});

		return false;
	});

	$( document ).off( "click", "#create-new-post");
	$( document ).on( "click", "#create-new-post", function() {
		$( "#form-post" ).toggle();
		if($( "#create-new-post" ).text() == "Create new company news")
			{
				$( "#create-new-post" ).text("Cancel");
				my_editor_new = CKEDITOR.replace("new-post");
				$("#new-post-title").val("");
				$("#new-post").val("");
			}
		else
			{
				$( "#create-new-post" ).text("Create new company news");
				my_editor_new.destroy(true);
				$("#new-post-title").val("");
				$("#new-post").val("");
			}

		$( "#submit-new-post" ).toggle();
	});
	
	//New Post form submit
	$( document ).off( "click", "#submit-new-post");
	$( document ).on( "click", "#submit-new-post", function() {
		$( "#form-new-post" ).submit();
	});	
	
	
	$( document ).off( "click", ".edit-post-button");
	$( document ).on( "click", ".edit-post-button", function() {
		var pid=$(this).attr('data-pid');
		if($("#edit-form-post"+pid).css('display') == 'block')
		{my_editor[pid].destroy(true);}
		else
		{my_editor[pid] = CKEDITOR.replace("edit-post"+pid);}
		$( "#edit-form-post"+pid ).toggle();
	});

	//Cancel Edit Post Form
	$( document ).off( "click", ".cancel-edit-post");
	$( document ).on( "click", ".cancel-edit-post", function() {
		var pid=$(this).attr('data-epid');
		if($("#edit-form-post"+pid).css('display') == 'block')
		{my_editor[pid].destroy(true);}
		else
		{my_editor[pid] = CKEDITOR.replace("edit-post"+pid);}
		$( "#edit-form-post"+pid ).toggle();
	});	
		
		$( document ).off( "click", ".publishBlog");
		$( document ).on( "click", ".publishBlog", function() {
			var pthis=$(this);
			var pbid=pthis.attr('data-pid');
			var pubText=pthis.text();

			var newText="";
			var oldText="";
			if (pubText.toUpperCase() === "PUBLISH IT") {
				newText = "Unpublish It";
				oldText = "Publish";
			} else {
				newText = "Publish It";
				oldText = "Unpublish";	
			}

			$.ajax({
				url: 'assets/includes/companynews.inc.php',
				type: 'POST',
				data: {publishCN:oldText,cnId:pbid},
				async: false,
				success: function (data) {
				  if(data != false)
				  {
					var result = JSON.parse(data);
					if(result.error == false)
					{
						pthis.text(newText);
					}else{
						alert(result.error);
					}
				  }else{
					alert("Error");
				  }
				},
				cache: false,
			});
		});
		
	$( document ).off( "click", ".delete-post-button");
	$( document ).on( "click", ".delete-post-button", function() {
		if (confirm("Do you really want to do this?")) {
			var dpid=$(this).attr('data-pid');
			if(dpid != "" && dpid != 0)
			{
				$.ajax({
					url: 'assets/includes/companynews.inc.php',
					type: 'POST',
					data: {action:'delete',pid:dpid},
					async: false,
					success: function (data) {
					  if(data != false)
					  {
						var result = JSON.parse(data);
						if(result.error == false)
						{
							if(($("#cn"+dpid+"").prev("li").hasClass("dtitle") && $("#cn"+dpid+"").next("li").hasClass("dtitle")) || ($("#cn"+dpid+"").prev("li").hasClass("dtitle") && !$("#cn"+dpid+"").next().hasClass("dis-content")))
							{
								$("#cn"+dpid+"").prev(".dtitle").remove();
							}
							$("#cn"+dpid+"").remove();
							resetpostorder();
						}else{
							alert(result.error);
						}
					  }else{
						alert("Error");
					  }
					},
					cache: false,
				});			
			}else{
				alert("Error Occured! Please try after sometime.");
			}
		}
	});

function resetpostorder(){
	var tmpthiss=null;
	var tmpcountt=1;
	$('.dis-content').each(function(){
		tmpthiss=$(this);

		if ( tmpcountt % 2 === 0 && tmpthiss.hasClass( "timeline-inverted" ) != true ) {
			tmpthiss.addClass("timeline-inverted");
		}else if(tmpcountt % 2 !== 0 && tmpthiss.hasClass("timeline-inverted") == true){
			tmpthiss.removeClass("timeline-inverted");
		}
		tmpcountt=tmpcountt+1
	 });
}
});
</script>
<!--[if lt IE 9]>
    <script src="assets/plugins/respond.js"></script>
    <script src="assets/plugins/html5shiv.js"></script>    
<![endif]-->