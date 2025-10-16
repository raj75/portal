<?php require_once("inc/init.php");
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

set_time_limit(0);

//error_reporting(0);
ini_set('max_execution_time', 0);
//require 'get_client.php';

$s3error=0;




$user_one=$_SESSION["user_id"];

if(checkpermission($mysqli,2)==false) die("Permission Denied! Please contact Vervantis.");

	function checks3image($keyname,$s3Client){
		$keyname=@trim($keyname);
		$infothumb = $s3Client->doesObjectExist('datahub360-public', 'blog/thumbnails/'.$keyname);
		if($keyname != "" and $infothumb)
		{
			return "https://datahub360-public.s3-us-west-2.amazonaws.com/blog/thumbnails/".$keyname;
		}else{
			$infotarget = $s3Client->doesObjectExist('datahub360-public', 'blog/'.$keyname);
			if($keyname != "" and $infotarget)
			{
				return "https://datahub360-public.s3-us-west-2.amazonaws.com/blog/".$keyname;
			}else{
				$infotarget = $s3Client->doesObjectExist('datahub360-public','blog/noImage.png');
				if($infotarget)
				{
					return "https://datahub360-public.s3-us-west-2.amazonaws.com/blog/noImage.png";
				}else{

					//logoff
				}
			}
		}
	}
?>




<style>
.showmore_content {
position: relative;
overflow: hidden;
margin-bottom:14px;
}
.showmore_trigger {
width: 100%;
height: 45px;
line-height: 45px;
cursor: pointer;
display:none;
}
.showmore_trigger span {
display: block;
}
</style>
<!-- row -->
<div class="row" id="paginationpart">
	<div class="col-sm-9 full-display">
		<div class="well padding-10">
<?php
if(isset($_GET["pgno"])){
	$pgno=$mysqli->real_escape_string(@trim($_GET["pgno"]));
?>



	<?php
		$stmt = $mysqli->prepare('SELECT b.id, b.user_id, b.post_title, b.post_cont,b.post_banner, b.datetime, b.status,u.firstname,u.lastname FROM blog_posts b,user u where b.user_id=u.user_id ORDER BY b.id DESC LIMIT '.(($pgno-1)*5).',5');
		if(!$stmt){
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
//SELECT b.id, b.user_id, b.post_title, b.post_cont,b.post_banner, b.datetime, b.status,u.firstname,u.lastname FROM blog_posts b,user u where b.user_id=u.id ORDER BY b.id DESC'

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$postUser="No Name";
			$stmt->bind_result($postID,$postUserID,$postTitle,$postCont,$postBanner,$postDate,$postStatus,$rfirstname,$rlastname);
			while($stmt->fetch()){
				if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2 or $postStatus == 1){
					$postUser=$rfirstname." ".$rlastname;

					$stmtc = $mysqli->query("SELECT id FROM threaded_comments WHERE blog_posts_id=".$postID);
					if(!$stmtc){
						header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
						exit();
					}
					$noComment=$stmtc->num_rows;
					if(!$noComment)
						$noComment=0;

					if(preg_match_all('/([^\"\']*amazonaws\.com[^<>\"\']*)/s',$postCont,$s3imageurlarr)){
						foreach($s3imageurlarr[1] as $kys=>$vls){
							$postCont=str_replace($vls,checks3image(basename(parse_url(urldecode($vls), PHP_URL_PATH)),$s3Client) ,$postCont);
						}
					}

					if(preg_match_all('/([^\"\']*vervantis\.com[^<>\"\']*)/s',$postCont,$imageurlarr)){
						foreach($imageurlarr[1] as $ky=>$vl)
							$postCont=str_replace($vl,checks3image(basename($vl),$s3Client,0,"tmp/images"),$postCont);
					}
				?>
					<div class="row dr<?php echo $postID; ?>">
						<div id="post-container<?php echo $postID; ?>">
							<div class="col-md-4">
								<img src="<?php echo checks3image($postBanner,$s3Client); ?>" class="img-responsive blog-banner" alt="<?php echo $postTitle; ?>">
								<ul class="list-inline padding-10">
									<li>
										<i class="fa fa-calendar"></i>
										<span><?php echo date('M d, Y', strtotime($postDate)); ?></span>
									</li>
									<li>
										<i class="fa fa-comments"></i>
										<a href="javascript:void(0);" data-pid="<?php echo $postID; ?>" class="comments-toggle black-color" id="comments-toggle<?php echo $postID; ?>"> <?php echo $noComment; ?> Comments </a>
									</li>
								</ul>
							</div>
							<div class="col-md-8 padding-left-0">
								<h3 class="margin-top-0"><a href="javascript:void(0);"> <?php echo $postTitle; ?> </a><br><small class="font-xs"><i>Published by <a href="javascript:void(0);"><?php echo $postUser; ?></a></i></small></h3>
								<div class="postCont"><?php echo $postCont; ?></div>
								<a class="btn btn-primary h-show-more">Read more</a>
								<?php
								if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2)
								{?>
									<a class="btn btn-warning edit-post-button" onclick="editblogpost(<?php echo $postID; ?>,<?php echo $pgno; ?>)" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"> Edit </a>
								<?php if($_SESSION["group_id"] == 1){ ?>
									<a class="btn btn-success publishBlog" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"><?php echo ($postStatus==1?"Unpublish It":"Publish It"); ?></a>
								<?php } ?>
									<a class="btn btn-danger delete-post-button" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"> Delete </a>
								<?php } ?>
							</div>
						</div>
						<br /><br />
						<div id="comment<?php echo $postID; ?>" style="clear:both;display:none;">
							<div class="col-md-4"></div>
							<div class="chat-body profile-message col-md-8 padding-left-0">
								<ul>
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
									<form method="post" class="well padding-bottom-10" onsubmit="return postcomment(<?php echo $postID; ?>)" id="cform<?php echo $postID; ?>">
										<textarea rows="2" class="form-control" placeholder="What are you thinking?" id="new-comment<?php echo $postID; ?>" name="new-comment<?php echo $postID; ?>"></textarea>
										<input type="hidden" id="new-parentID<?php echo $postID; ?>" name="new-parentID<?php echo $postID; ?>" value="0">
										<input type="hidden" id="new-blogID<?php echo $postID; ?>" name="new-blogID<?php echo $postID; ?>" value="<?php echo $postID; ?>">
										<div class="margin-top-10">
											<input type="button" class="btn btn-sm btn-primary pull-right post-comment" pc-id="<?php echo $postID; ?>" value="Post">
											<br/><br/>
										</div>
									</form>
<?php } ?>
							<?php
							$commentUser = "Noname";
			$stmtt = $mysqli->prepare("SELECT id,user_id,comment,datetime,parent_id FROM threaded_comments WHERE parent_id = 0 and blog_posts_id=".$postID);
			if(!$stmtt){
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			$stmtt->execute();
			$stmtt->store_result();
			if ($stmtt->num_rows > 0) {
				$stmtt->bind_result($commentID,$commentUserID,$comment,$commentDateTime,$commentPID);
				while($stmtt->fetch()){
					$stmtst = $mysqli->query('SELECT firstname,lastname FROM user where user_id='.$commentUserID.' LIMIT 1');

//SELECT firstname,lastname FROM user where id='.$commentUserID.' LIMIT 1'

					if($stmtst->num_rows)
					{
						$crow = $stmtst->fetch_array();
						$commentUser=$crow["firstname"]." ".$crow["lastname"];
					}
					getComments($mysqli,$postID,$commentID,$commentUserID,$comment,$commentDateTime,$commentPID,$commentUser);
				}
			}
							?>
								</ul>
							</div>


						</div>
					</div>
					<hr class="dr<?php echo $postID; ?>">
			<?php
				}
			}
		}

}else{die("Nothing to show!");}
	?>
		</div>

	</div>

</div>

<?php
function getComments($mysqli,$postID,$commentID,$commentUserID,$comment,$commentDateTime,$commentPID,$commentUser) {
	echo "<li class=\"message\">";
	echo "<span class=\"message-text no-margin-left\"> <a href=\"javascript:void(0);\" class=\"username\">".$commentUser."&nbsp;&nbsp;<small class=\"text-muted pull-right ultra-light\"> ".date('g:iA F jS, Y', strtotime($commentDateTime))." </small></a> ".$comment."</span>";
	//echo "<div class='aut'>".$commentUser."</div>";
	//echo "<div class='comment-body'>".$comment."</div>";
	//echo "<div class='timestamp'>".$commentDateTime."</div>";
	//echo "<a href='#comment_form' class='reply' id='".$commentID."'>Reply</a>";
	if($commentPID == 0){
	?><ul class="list-inline font-xs">
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
		<li>
			<a href="javascript:void(0);" class="text-info reply-link" id='<?php echo $commentID; ?>'><i class="fa fa-reply"></i> Reply</a>
		</li>
<?php } ?>
		<!--<li>
			<a href="javascript:void(0);" class="text-muted">Show All Comments (14)</a>
		</li>
		<li>
			<a href="javascript:void(0);" class="text-primary">Edit</a>
		</li>
		<li>
			<a href="javascript:void(0);" class="text-danger">Delete</a>
		</li>-->
	</ul><?php
	}
	$commentUser="Noname";
	$stmtk = $mysqli->prepare("SELECT id,user_id,comment,datetime,parent_id FROM threaded_comments WHERE parent_id = ".$commentID." and blog_posts_id=".$postID);
	if(!$stmtk){
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}
	$stmtk->execute();
	$stmtk->store_result();
	if ($stmtk->num_rows > 0) {
		$stmtk->bind_result($commenttID,$commentUserID,$comment,$commentDateTime,$commenttPID);
		echo "<ul>";
		while($stmtk->fetch()){
			$stmtsk = $mysqli->query('SELECT firstname,lastname FROM user where user_id='.$commentUserID.' LIMIT 1');
			if(!$stmtsk){
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();
			}
			if($stmtsk->num_rows)
			{
				$row = $stmtsk->fetch_array();
				$commentUser=$row["firstname"]." ".$row["lastname"];
			}
			getComments($mysqli,$postID,$commenttID,$commentUserID,$comment,$commentDateTime,$commenttPID,$commentUser);

		}
		echo "</ul>";
	}
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){
	if($commentPID == 0)
		echo "<input class=\"form-control input-xs\" placeholder=\"Type and enter\" type=\"text\" style=\"margin-left:3%;\" id=\"text".$commentID."\" data-pblogid=\"".$postID."\" data-pid=\"".$commentID."\">";
}
	echo "</li>";
}
?>



<!-- end row -->
<script type="text/javascript">
(function(a){a.fn.showMoreTxt=function(b){var c={speedDown:300,speedUp:300,height:"265px",showText:"Show",hideText:"Hide"};var b=a.extend(c,b);return this.each(function(){var e=a(this),d=e.height();if(d>parseInt(b.height)){e.wrapInner('<div class="showmore_content" />');e.find(".showmore_content").css("height",b.height);e.append('<div class="showmore_trigger"><span class="more">'+b.showText+'</span><span class="less" style="display:none;">'+b.hideText+"</span></div>");e.find(".showmore_trigger").on("click",".more",function(){a(this).hide();a(this).next().show();a(this).parent().prev().animate({height:d},b.speedDown)});e.find(".showmore_trigger").on("click",".less",function(){a(this).hide();a(this).prev().show();a(this).parent().prev().animate({height:b.height},b.speedUp)})}})}})(jQuery);

	pageSetUp();

	var pagefunction = function() {
		jQuery('.postCont').showMoreTxt({
			speedDown: 300,
				speedUp: 300,
				height: '90px',
				showText: 'Show more',
				hideText: 'Show less'
		});

	$( document ).ready(function() {
			$(".h-show-more").off("click","**");
			$(".h-show-more").on("click", function() {
				var $link = $(this);
				if($link.prev(".postCont").find('.showmore_trigger').length != 0)
				{
					if($link.prev(".postCont").children('.showmore_trigger').children(".more")[0].style["display"]=="none")
					{
						$link.prev(".postCont").children('.showmore_trigger').children(".less").click();
					}else if($link.prev(".postCont").children('.showmore_trigger').children(".less")[0].style["display"]=="none"){
						$link.prev(".postCont").children('.showmore_trigger').children(".more").click();
					}
				}
				var $link = $(this).text(getShowLinkText($link.text()));
			});

			function getShowLinkText(currentText) {
				var newText = '';

				if (currentText.toUpperCase() === "READ MORE") {
					newText = "Read less";
				} else {
					newText = "Read more";
				}

				return newText;
			}

				// clears the variable if left blank
				$(document).off('click', '.comments-toggle');
				$( document ).on( "click", ".comments-toggle", function() {
				 $("#comment" + $(this).attr('data-pid')).toggle();
				});
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
<?php if($_SESSION["group_id"] == 1){ ?>
				$(document).off('click', '.publishBlog');
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
						url: 'assets/includes/blog.inc.php',
						type: 'POST',
						data: {publishBlog:oldText,blogId:pbid},
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
<?php } ?>
			$(document).off('click', '.delete-post-button');
			$( document ).on( "click", ".delete-post-button", function() {
				if (confirm("Do you really want to do this?")) {
					var dpid=$(this).attr('data-pid');
					if(dpid != "" && dpid != 0)
					{
						$.ajax({
							url: 'assets/includes/blog.inc.php',
							type: 'POST',
							data: {action:'delete',pid:dpid},
							success: function (data) {
							  if(data != false)
							  {
								var result = JSON.parse(data);
								if(result.error == false)
								{
									//$(".dr"+dpid+"").remove();
									parent.$( "#blogbox" ).html("");
									parent.$('#blogbox').load('assets/ajax/blog-pedit.php?ct=<?php echo time(); ?>&pgno=<?php echo $pgno; ?>');
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

			$(document).off('keypress', '.input-xs');
			$( document ).on( "keypress", ".input-xs", function(event) {
			  if (event.keyCode == 13) {
			  var objj=$(this);
				tcomment=$(this).val();
				$(this).val("");
				tparentid=$(this).attr('data-pid');
				tblogid=$(this).attr('data-pblogid');
				if(tcomment != "" && tparentid != "" && tblogid != ""){
					$.ajax({
						type: "POST",
						url: "assets/includes/comment.inc.php",
						data: "comment="+tcomment+"&parentid="+tparentid+"&blogid="+tblogid,
						success: function(statuss){
							if(statuss != false){
								var results = JSON.parse(statuss);
								var displaysreply="";
								var cdate=new Date(results.datetime);
								//alert(cdate.format("g:iA F jS, Y"));
								if(results.parent_id==0)
								{
									displaysreply="<ul class=\"list-inline font-xs\"><li><a href=\"javascript:void(0);\" class=\"text-info\" id=\""+results.id+"\"><i class=\"fa fa-reply\"></i> Reply</a></li>";
								}
								objj.before("<li class=\"message\"><span class=\"message-text no-margin-left\"> <a href=\"javascript:void(0);\" class=\"username\">"+results.commentuser+"&nbsp;&nbsp;<small class=\"text-muted pull-right ultra-light\"> "+results.datetime+" </small></a> "+tcomment+"</span></li>"+displaysreply);
								$("#comments-toggle"+tblogid).html(results.nocomment+" Comments");
							}else{

							}
						},
						beforeSend:function()
						{

						}
					});
				}else
					alert("Please type the text");


				event.preventDefault();
			  }
			});

			$(document).off('click', '.reply-link');
			$( document ).on( "click", ".reply-link", function() {
			 var cid=$(this).attr('id');
			 $(window).scrollTop($('#text'+cid).offset().top);
			 $('#text'+cid).focus();
			});

			//function postcomment(blogid)
			//{
			$(document).off('click', '.post-comment');
			$( document ).on( "click", ".post-comment", function() {
				//event.preventDefault();
				blogid=$(this).attr('pc-id');
				ncomment=$("#new-comment"+blogid).val();
				nparentid=$("#new-parentID"+blogid).val();
				nblogid=$("#new-blogID"+blogid).val();
				$("#new-comment"+blogid).val("");
				if(ncomment != "" && nparentid != "" && nblogid != ""){
					$.ajax({
						type: "POST",
						url: "assets/includes/comment.inc.php",
						data: "comment="+ncomment+"&parentid="+nparentid+"&blogid="+nblogid,
						success: function(status){
							if(status != false){
								if(nparentid == 0)
									var appendid="cform";
								else
									var appendid="cform";

								var result = JSON.parse(status);
								if(result.parent_id==0)
								{
									var displayreply="<ul class=\"list-inline font-xs\"><li><a href=\"javascript:void(0);\" class=\"text-info\" id=\""+result.id+"\"><i class=\"fa fa-reply\"></i> Reply</a></li></ul>";
								}
								$("#"+appendid+blogid).after("<li class=\"message\"><span class=\"message-text no-margin-left\"> <a href=\"javascript:void(0);\" class=\"username\">"+result.commentuser+"&nbsp;&nbsp;<small class=\"text-muted pull-right ultra-light\"> "+result.datetime+" </small></a> "+ncomment+"</span>"+displayreply+"<input type=\"text\" data-pid=\""+result.id+"\" data-pblogid=\""+blogid+"\" id=\"text"+result.id+"\" style=\"margin-left:3%;\" placeholder=\"Type and enter\" class=\"form-control input-xs\"></li>");
								$("#comments-toggle"+blogid).html(result.nocomment+" Comments");
							}else{

							}
						},
						beforeSend:function()
						{

						}
					});
				}else
					alert("Please type the text");
			return false;
			});
<?php } ?>

		});

	};
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
<?php if($_SESSION["group_id"] == 1){ ?>


<?php } ?>
	function editblogpost(epid,pgno){
		$( "#blogdialog" ).html('');
		$( "#blogdialog" ).load( "assets/ajax/blog-subpage.php?ct=<?php echo time(); ?>&editform=true&pid="+epid+"&pgno="+pgno);
		$("#blogdialog").dialog("option","title","Edit Blog Post").dialog('open');
	}
<?php } ?>
	// end pagefunction

	var pagedestroy = function(){

		my_editor_new=null;
		$shmr=null;
		$shmr1=null;
		my_editor.destroy(true);
		my_editor=null;

		newText=null;
		oldText=null;



		$('.comments-toggle').find('*').addBack().off().remove();
		$('.delete-post-button').find('*').addBack().off().remove();
		$('.publishBlog').find('*').addBack().off().remove();
		$('.post-comment').find('*').addBack().off().remove();
		$('.reply-link').find('*').addBack().off().remove();
		$('.input-xs').find('*').addBack().off().remove();
		$('.cancel-edit-post').find('*').addBack().off().remove();
		$('#submit-new-post').find('*').addBack().off().remove();
		$('#form-new-post').find('*').addBack().off().remove();
		$('#create-new-post').find('*').addBack().off().remove();
		$('#form-post').find('*').addBack().off().remove();
		$('.edit-post').find('*').addBack().off().remove();
		$('.abc').find('*').addBack().off().remove();
		$('.postCont').find('*').addBack().off().remove();
		$('.h-show-more').find('*').addBack().off().remove();
		$('.breakline').find('*').addBack().off().remove();

		// destroy vector map objects
		//$('#vector-map').find('*').addBack().off().remove();

		// destroy todo
		//$("#sortable1, #sortable2").sortable("destroy");
		//$('.todo .checkbox > input[type="checkbox"]').off();

		// destroy misc events
		//$("#rev-toggles").find(':checkbox').off();
		//$('#chat-container').find('*').addBack().off().remove();

		// debug msg
		if (debugState){
			//root.console.log("✔ Calendar, Flot Charts, Vector map, misc events destroyed");
		}

	}


	// run pagefunction
	pagefunction();
</script>
<?php
		$stmt_nav = $mysqli->prepare('SELECT count(b.id) as totalblog FROM blog_posts b,user u where b.user_id=u.user_id LIMIT 1');
		if(!$stmt_nav){
			//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			//exit();
		}

		$stmt_nav->execute();
		$stmt_nav->store_result();
		if ($stmt_nav->num_rows > 0) {
			$stmt_nav->bind_result($totalblog);
			$stmt_nav->fetch();

		}
		$next=0;
		$totalcount= ceil($totalblog/5);
?>
<style>
.ndataTables_wrapper .ndataTables_paginate {
    padding-bottom: 0.25em !important;
    margin-right: 1% !important;
}
.ndataTables_wrapper .ndataTables_length, .ndataTables_wrapper .ndataTables_filter, .ndataTables_wrapper .ndataTables_info, .ndataTables_wrapper .ndataTables_processing, .ndataTables_wrapper .ndataTables_paginate {
    color: #333;
}
.ndataTables_wrapper .ndataTables_paginate {
    float: right;
    text-align: right;
    padding-top: 0.25em;
}
div.ndataTables_paginate {
    float: right;
    margin: 0;
}
.ndataTables_wrapper .ndataTables_paginate .npaginate_button.disabled, .ndataTables_wrapper .ndataTables_paginate .npaginate_button.disabled:hover, .ndataTables_wrapper .ndataTables_paginate .npaginate_button.disabled:active {
    cursor: default;
    color: #666 !important;
    border: 1px solid transparent;
    background: transparent;
    box-shadow: none;
}
.ndataTables_wrapper .ndataTables_paginate .npaginate_button {
    box-sizing: border-box;
    display: inline-block;
    min-width: 1.5em;
    padding: 0.5em 1em;
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    *cursor: hand;
    color: #333 !important;
    border: 1px solid transparent;
    border-radius: 2px;
}
.disabled {
    color: #fff;
}
.ndataTables_wrapper .ndataTables_paginate .npaginate_button.current, .ndataTables_wrapper .ndataTables_paginate .npaginate_button.current:hover {
    color: #333 !important;
    border: 1px solid #979797;
    background-color: white;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc));
    background: -webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -moz-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -ms-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: -o-linear-gradient(top, #fff 0%, #dcdcdc 100%);
    background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%);
}
.ndataTables_wrapper .ndataTables_paginate .npaginate_button:hover {
    color: white !important;
    border: 1px solid #111;
    background-color: #585858;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111));
    background: -webkit-linear-gradient(top, #585858 0%, #111 100%);
    background: -moz-linear-gradient(top, #585858 0%, #111 100%);
    background: -ms-linear-gradient(top, #585858 0%, #111 100%);
    background: -o-linear-gradient(top, #585858 0%, #111 100%);
    background: linear-gradient(to bottom, #585858 0%, #111 100%);
}
.ndataTables_wrapper .ndataTables_paginate .npaginate_button {
    box-sizing: border-box;
    display: inline-block;
    min-width: 1.5em;
    padding: 0.5em 1em;
    margin-left: 2px;
    text-align: center;
    text-decoration: none !important;
    cursor: pointer;
    *cursor: hand;
    color: #333 !important;
    border: 1px solid transparent;
    border-radius: 2px;
}
.ndataTables_wrapper {
    position: relative;
    clear: both;
    *zoom: 1;
    zoom: 1;
}
.ndataTables_wrapper .ndataTables_info {
    margin-left: 1% !important;
}
.ndataTables_wrapper .ndataTables_info {
    clear: both;
    float: left;
    padding-top: 0.755em;
}
div.ndataTables_info {
    padding-top: 9px;
    font-size: 13px;
    font-weight: 700;
    font-style: italic;
    color: #969696;
}
.ndataTables_wrapper .ndataTables_paginate {
    padding-bottom: 0.25em !important;
    margin-right: 1% !important;
}
.ndataTables_wrapper{
    margin-bottom: 44px;
    margin-top: -5px;
}
</style>
<div id="ndatatable_fixed_column_wrapper" class="ndataTables_wrapper no-footer">
	<div class="ndataTables_info" id="ndatatable_fixed_column_info" role="status" aria-live="polite">Showing <?php echo ((($pgno-1)*5)+1); ?> to <?php if(($pgno*5) < $totalblog) {echo ($pgno*5);}else{echo $totalblog;} ?> of <?php echo $totalblog; ?> entries
	</div>
	<div class="ndataTables_paginate paging_simple_numbers" id="ndatatable_fixed_column_paginate">
		<a class="npaginate_button previous <?php if($pgno==1) echo "disabled"; ?>" aria-controls="ndatatable_fixed_column" data-dt-idx="0" tabindex="0" id="ndatatable_fixed_column_previous" onclick="paginateacc(<?php echo ($pgno-1); ?>)">Previous</a>
		<span>
		<?php
		for($i=1;$i<=$totalcount;$i++){
			if($i>$pgno and $next==0){$next=$i;}
		?>
					<a class="npaginate_button <?php if($i==$pgno){ ?>current<?php } ?>" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo ($i); ?>" tabindex="0" onclick="paginateacc(<?php echo ($i); ?>)"><?php echo ($i); ?></a>
		<?php } ?>
				</span>
		<?php /*if($next>0){*/ ?>
		<a class="npaginate_button next <?php if($pgno==$totalcount) echo "disabled"; ?>" aria-controls="ndatatable_fixed_column" data-dt-idx="4" tabindex="0" id="ndatatable_fixed_column_next" onclick="paginateacc(<?php echo $next; ?>)">Next</a></div>
		<?php /*}*/ ?>
</div>
<script type="text/javascript">
	function paginateacc(pgno){
		$('#blogbox').load('assets/ajax/blog-pedit.php?ct=<?php echo time(); ?>&pgno='+pgno);
	};
</script>
