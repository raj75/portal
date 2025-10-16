<?php require_once("inc/init.php");
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

//require 'get_client.php';

$s3error=0;


if(checkpermission($mysqli,3)==false) die("Permission Denied! Please contact Vervantis.");
$user_one=$_SESSION["user_id"];



	$noimage="";
	$infotarget = $s3Client->doesObjectExist('datahub360-public', 'market news/noImage.png');
	if($infotarget)
	{
		$noimage= "https://datahub360-public.s3-us-west-2.amazonaws.com/market news/noImage.png";
	}else{
		die("Error");
		//logoff
	}

	function checks3image($keyname,$s3Client,$forcenoimage=0,$customfolder="market news"){
		global $noimage;
		$keyname=@trim($keyname);

		if($keyname == "") return $noimage;

		$infotarget = $s3Client->doesObjectExist('datahub360-public', $customfolder.'/'.$keyname);
		if($keyname != "" and $infotarget)
		{
			return "https://datahub360-public.s3-us-west-2.amazonaws.com/market news/".$keyname;
		}else{
			if($forcenoimage != 0){
				$infotarget = $s3Client->doesObjectExist('datahub360-public', 'market news/noImage.png');
				if($infotarget)
				{
					return "https://datahub360-public.s3-us-west-2.amazonaws.com/market news/noImage.png";
				}else{

					//logoff
				}
			}else{
				return $noimage;
			}
		}
	}

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] !=2) $tmpsql=' and b.status="1" ';
else $tmpsql='';

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
.cke_dialog_tab{
	display:inline-block !important;
}
.ck-editor__editable {
    min-height: 400px;
}
.s3link{max-width: 800px;}
</style>
<!-- Bread crumb is created dynamically -->

<div class="row">
	<div class="col-sm-9 full-display">
		<div class="well padding-10">
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
	<div id="form-postm" style="display:none;">
		<form id="form-new-postm" enctype="multipart/form-data">
			<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="new-post-titlem" id="new-post-titlem" size="100"></p>
			<p><span class="txt-color-blue float-left"><b>Post Banner:</b></span>&nbsp;<input type="file" name="new-post-bannerm" id="new-post-bannerm" size="100" class="float-left"></p>
			<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
			<textarea name="new-postm" id="new-postm" placeholder="Enter post description"></textarea>
			<br />
		</form>
	</div>
	<p><button class="btn btn-primary blog-post-btn" id="create-new-postm">Create new post</button>&nbsp;<button class="btn btn-primary blog-post-btn" id="submit-new-postm" style="display:none;">Submit</button></p>
	<br /><br />
	<hr id="breaklinem">
<?php } ?>
<?php
if(isset($_GET["pgno"])){
	$pgno=$mysqli->real_escape_string(@trim($_GET["pgno"]));
?>
	<?php
		$tmp_error="";
		$stmt = $mysqli->prepare('SELECT b.id, b.user_id, b.post_title, b.post_cont,b.post_banner, b.datetime, b.status,u.firstname,u.lastname FROM market_news b,user u where b.user_id=u.user_id '.$tmpsql.' ORDER BY b.id DESC LIMIT '.(($pgno-1)*5).',5');
		if(!$stmt){
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();
		}
//('SELECT b.id, b.user_id, b.post_title, b.post_cont,b.post_banner, b.datetime, b.status,firstname,lastname FROM market_news b,user u where b.user_id=u.id ORDER BY b.id DESC');

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$postUser="No Name";
			$stmt->bind_result($postID,$postUserID,$postTitle,$postCont,$postBanner,$postDate,$postStatus,$rfirstname,$rlastname);
			while($stmt->fetch()){
				if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2 or $postStatus == 1){
					$postUser=$rfirstname." ".$rlastname;
					$stmtc = $mysqli->query("SELECT id FROM market_news_comments WHERE market_news_id=".$postID);
					$noComment=$stmtc->num_rows;
					if(!$noComment)
						$noComment=0;

					if(preg_match_all('/([^\"\']*amazonaws\.com[^<>\"\']*)/s',$postCont,$s3imageurlarr)){
						foreach($s3imageurlarr[1] as $kys=>$vls){
							$postCont=@str_replace($vls,checks3image(basename(parse_url(urldecode($vls), PHP_URL_PATH)),$s3Client) ,$postCont);
						}
					}

					if(preg_match_all('/([^\"\']*vervantis\.com[^<>\"\']*)/s',$postCont,$imageurlarr)){
						foreach($imageurlarr[1] as $ky=>$vl)
							$postCont=str_replace($vl,checks3image(basename($vl),$s3Client,0,"tmp/images"),$postCont);
					}

					if(preg_match_all('/src=\"([^\"]+)\" class=\"s3link\"/s',$postCont,$s3imageurlarr)){
						foreach($s3imageurlarr[1] as $kys=>$vls){
							$postCont=str_replace($vls,checks3image(basename(parse_url(urldecode($vls), PHP_URL_PATH)),$s3Client) ,$postCont);
						}
					}					
				?>
					<div class="row drm<?php echo $postID; ?>">
						<div id="post-containerm<?php echo $postID; ?>">
							<div class="col-md-4">
								<img src="<?php echo checks3image($postBanner,$s3Client); ?>" class="img-responsive blog-banner" alt="<?php echo $postTitle; ?>">
								<ul class="list-inline padding-10">
									<li>
										<i class="fa fa-calendar"></i>
										<span><?php echo date('M d, Y', strtotime($postDate)); ?></span>
									</li>
									<li>
										<i class="fa fa-comments"></i>
										<a href="javascript:void(0);" data-pid="<?php echo $postID; ?>" class="comments-togglem black-color" id="comments-togglem<?php echo $postID; ?>"> <?php echo $noComment; ?> Comments </a>
									</li>
								</ul>
							</div>
							<div class="col-md-8 padding-left-0">
								<h3 class="margin-top-0"><a href="javascript:void(0);"> <?php echo $postTitle; ?> </a><br><small class="font-xs"><i>Published by <a href="javascript:void(0);"><?php echo $postUser; ?></a></i></small></h3>
								<div class="postContm"><?php echo $postCont; ?></div>
								<a class="btn btn-primary h-show-more">Read more</a>
								<?php
								if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2)
								{?>
									<a class="btn btn-warning edit-post-buttonm" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"> Edit </a>
									<?php if($_SESSION["group_id"] == 1){ ?>
									<a class="btn btn-success publishBlogm" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"><?php echo ($postStatus==1?"Unpublish It":"Publish It"); ?></a>
									<?php } ?>
									<a class="btn btn-danger delete-post-buttonm" href="javascript:void(0);" data-pid="<?php echo $postID; ?>"> Delete </a>
								<?php } ?>
							</div>
							<?php
							if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2)
							{?>
							<div id="edit-form-postm<?php echo $postID; ?>" style="clear:both;display:none;margin-top:20px;padding:10px">
								<hr>
								<form id="form-edit-postm<?php echo $postID; ?>" class="edit-postm" enctype="multipart/form-data">
									<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-titlem" id="edit-post-titlem<?php echo $postID; ?>" size="100" value="<?php echo $postTitle; ?>"></p>
									<p><span class="txt-color-blue float-left"><b>Post Banner:</b></span>&nbsp;<input type="file" name="edit-post-bannerm" id="edit-post-bannerm<?php echo $postID; ?>" size="100" class="float-left"></p>
									<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
									<textarea name="edit-postm" id="edit-postm<?php echo $postID; ?>" placeholder="Enter post description"><?php echo $postCont; ?></textarea>
									<input type="hidden" name="post-idm" value="<?php echo $postID; ?>">
									<button class="btn btn-primary" type="submit" id="submit-edit-postm<?php echo $postID; ?>" data-pid="<?php echo $postID; ?>">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-postm" type="button" data-epid="<?php echo $postID; ?>">Cancel</button>
									<br />
								</form>
							</div>
							<?php } ?>
						</div>
						<br /><br />
						<div id="commentm<?php echo $postID; ?>" style="clear:both;display:none;">
							<div class="col-md-4"></div>
							<div class="chat-body profile-message col-md-8 padding-left-0">
								<ul>
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
									<form method="post" class="well padding-bottom-10" onsubmit="return postcommentm(<?php echo $postID; ?>)" id="cformm<?php echo $postID; ?>">
										<textarea rows="2" class="form-control" placeholder="What are you thinking?" id="new-commentm<?php echo $postID; ?>" name="new-commentm<?php echo $postID; ?>"></textarea>
										<input type="hidden" id="new-parentIDm<?php echo $postID; ?>" name="new-parentIDm<?php echo $postID; ?>" value="0">
										<input type="hidden" id="new-blogIDm<?php echo $postID; ?>" name="new-blogIDm<?php echo $postID; ?>" value="<?php echo $postID; ?>">
										<div class="margin-top-10">
											<input type="button" class="btn btn-sm btn-primary pull-right post-commentm" pc-id="<?php echo $postID; ?>" value="Post">
											<br/><br/>
										</div>
									</form>
<?php } ?>
							<?php
							$commentUser = "Noname";
			$stmtt = $mysqli->prepare("SELECT id,user_id,comment,datetime,parent_id FROM market_news_comments WHERE parent_id = 0 and market_news_id=".$postID);
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

//('SELECT firstname,lastname FROM user where id='.$commentUserID.' LIMIT 1');

					if($stmtst->num_rows)
					{
						$crow = $stmtst->fetch_array();
						$commentUser=$crow["firstname"]." ".$crow["lastname"];
					}
					getCommentsm($mysqli,$postID,$commentID,$commentUserID,$comment,$commentDateTime,$commentPID,$commentUser);
				}
			}
							?>
								</ul>
							</div>


						</div>
					</div>
					<hr class="drm<?php echo $postID; ?>">
			<?php
				}else{
					$tmp_error="<center><div>Nothing to Show!</div></center>";
				}
			}
		}else{
			$tmp_error= "<center><div>Nothing to Show!</div></center>";
		}

		if($tmp_error != "")
			echo $tmp_error;
}else{die("Nothing to show!");}
	?>
		</div>

	</div>

</div>


<!-- end row -->
<?php
function getCommentsm($mysqli,$postID,$commentID,$commentUserID,$comment,$commentDateTime,$commentPID,$commentUser) {
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
			<a href="javascript:void(0);" class="text-info reply-linkm" id='<?php echo $commentID; ?>'><i class="fa fa-reply"></i> Reply</a>
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
	$commentUser="No Name";
	$stmtk = $mysqli->prepare("SELECT id,user_id,comment,datetime,parent_id FROM market_news_comments WHERE parent_id = ".$commentID." and market_news_id=".$postID);
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
//('SELECT firstname,lastname FROM user where id='.$commentUserID.' LIMIT 1');

			if($stmtsk->num_rows)
			{
				$row = $stmtsk->fetch_array();
				$commentUser=$row["firstname"]." ".$row["lastname"];
			}
			getCommentsm($mysqli,$postID,$commenttID,$commentUserID,$comment,$commentDateTime,$commenttPID,$commentUser);

		}
		echo "</ul>";
	}
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){
	if($commentPID == 0)
		echo "<input class=\"form-control input-xsm\" placeholder=\"Type and enter\" type=\"text\" style=\"margin-left:3%;\" id=\"textm".$commentID."\" data-pblogid=\"".$postID."\" data-pid=\"".$commentID."\">";
}
	echo "</li>";
}
?>
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
<!--<script src="<?php echo ASSETS_URL; ?>/assets/js/plugin/ckeditor/ckeditor.js"></script>
<script src="<?php echo ASSETS_URL; ?>/assets/js/plugin/ckfinder/ckfinder.js"></script>-->
<script src="<?php echo ASSETS_URL; ?>/assets/js/base64_decode.js"></script>
<?php } ?>
<script src="<?php echo ASSETS_URL; ?>/assets/js/jquery.showmore.min.js"></script>

<script type="text/javascript">
(function(a){a.fn.showMoreTxtM=function(b){var c={speedDown:300,speedUp:300,height:"265px",showText:"Show",hideText:"Hide"};var b=a.extend(c,b);return this.each(function(){var e=a(this),d=e.height();if(d>parseInt(b.height)){e.wrapInner('<div class="showmore_content" />');e.find(".showmore_content").css("height",b.height);e.append('<div class="showmore_trigger"><span class="more">'+b.showText+'</span><span class="less" style="display:none;">'+b.hideText+"</span></div>");e.find(".showmore_trigger").on("click",".more",function(){a(this).hide();a(this).next().show();a(this).parent().prev().animate({height:d},b.speedDown)});e.find(".showmore_trigger").on("click",".less",function(){a(this).hide();a(this).prev().show();a(this).parent().prev().animate({height:b.height},b.speedUp)})}})}})(jQuery);




pageSetUp();

var pagedestroy = function(){

}


//import ImageInsert from '@ckeditor/ckeditor5-image/src/imageinsert';

var pagefunction = function() {
	var my_editor;
	jQuery('.postContm').showMoreTxtM({
		speedDown: 300,
			speedUp: 300,
			height: '90px',
			showText: 'Show more',
			hideText: 'Show less'
	});
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
	my_editor_newm=null;
	$shmrm=null;
	var my_editorm = [];
<?php } ?>
	/*$(".postCont").shorten({showChars: 500});
	$(".h-show-more").on("click", function() {
		var $link = $(this);
		if(!$link.prev(".postCont").hasClass('moreCont'))
		{
			$(".postCont").shorten({showChars: 500});
		}
		$link.prev(".postCont").children(".moreCont").children(".morelink").click();
		var $link = $(this).text(getShowLinkText($link.text()));
	});*/

	//$(".h-show-more").on("click", function() {
	$( document ).off( "click", ".h-show-more");
	$( document ).on( "click", ".h-show-more", function() {
		var $link = $(this);
		if($link.prev(".postContm").find('.showmore_trigger').length != 0)
		{
			if($link.prev(".postContm").children('.showmore_trigger').children(".more")[0].style["display"]=="none")
			{
				$link.prev(".postContm").children('.showmore_trigger').children(".less").click();
			}else if($link.prev(".postContm").children('.showmore_trigger').children(".less")[0].style["display"]=="none"){
				$link.prev(".postContm").children('.showmore_trigger').children(".more").click();
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
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
	function CKupdate(){
		//for ( instance in CKEDITOR.instances )
			//CKEDITOR.instances[instance].updateElement();
	}

	$( document ).off( "submit", "form#form-new-postm");
	$( document ).on( "submit", "form#form-new-postm", function() {
		//CKupdate();
	  //disable the default form submission
	  //event.preventDefault();
		//var formData = new FormData($(this)[0]);
		//formData.delete("new-postm");
		//formData.append("new-postm", $("#new-postm").val());
		//const data = new FormData();
        //formData.append('new-postm', $("#new-postm").val());
		if($("#new-post-bannerm").val() == "")
		{
			alert("Image field cannot be empty");
			$("#new-post-bannerm").focus();
		}else{
			var selection = document.querySelector('#form-new-postm .ck-editor__editable') !== null;
			if (selection) {
				document.querySelector('#form-new-postm .ck-editor__editable').ckeditorInstance.destroy();
			}		
			var formData = new FormData($(this)[0]);
			$( "#form-postm" ).toggle();
			$( "#create-new-postm" ).toggle();
			$( "#submit-new-postm" ).toggle();
			$.ajax({
				url: 'assets/includes/marketnews.inc.php',
				type: 'POST',
				data: formData,
				success: function (data) {
				if(data != false)
				{
					var result = JSON.parse(data);
					if(result.error == false)
					{
						//location.reload(true);
						/*for(name in CKEDITOR.instances)
						{
							CKEDITOR.instances[name].destroy(true);
						}*/		
									
						parent.$('#marketnewsbox').load('assets/ajax/market-news-pedit.php?ct=<?php echo mt_rand(2,99); ?>&pgno=1');
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
		}
		return false;
	});

$( document ).off( "submit", "form.edit-postm");
$( document ).on( "submit", "form.edit-postm", function() {
	var pid=$(this).attr('data-pid');
	//CKupdate();
	  //disable the default form submission
	  //event.preventDefault();
	  	var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
		if (selection) {
			document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
		}
		var formData = new FormData($(this)[0]);
		$(this).toggle();
		$.ajax({
			url: 'assets/includes/marketnews.inc.php',
			type: 'POST',
			data: formData,
			success: function (data) {
			  if(data != false)
			  {
				var result = JSON.parse(data);
				if(result.error == false)
				{
					var displayBlogedit = "";
					if(result.user_id == '<?php echo $_SESSION["user_id"]; ?>' || '<?php echo $_SESSION["group_id"]; ?>' == 1 || '<?php echo $_SESSION["group_id"]; ?>' == 2){
					displayBlogedit = '&nbsp;<a class="btn btn-warning edit-post-buttonm" href="javascript:void(0);" data-pid="'+result.id+'"> Edit </a>&nbsp;';
					<?php if($_SESSION["group_id"] == 1){ ?>
					displayBlogedit = displayBlogedit + '<a class="btn btn-success publishBlogm" href="javascript:void(0);" data-pid="'+result.id+'">'+((result.publish != "Y")?"Publish It":"Unpublish It")+'</a>';
					<?php } ?>
					displayBlogedit = displayBlogedit + '<a class="btn btn-danger delete-post-buttonm" href="javascript:void(0);" data-pid="'+result.id+'"> Delete </a>';
					}

					if(result.blogBanner == ''){ displayBlogBannerImg = 'noImage.png';}else{displayBlogBannerImg = result.blogBanner;}

					$("#post-containerm"+result.id).html('<div class="col-md-4"><img src="'+displayBlogBannerImg+'" class="img-responsive blog-banner" alt="No Image">	<ul class="list-inline padding-10"><li><i class="fa fa-calendar"></i><a href="javascript:void(0);"> '+result.datetime+' </a></li><li><i class="fa fa-comments"></i><a href="javascript:void(0);" data-pid="'+result.id+'" class="comments-togglem"> '+result.noComments+' Comments </a></li></ul></div><div class="col-md-8 padding-left-0"><h3 class="margin-top-0"><a href="javascript:void(0);"> '+result.blogTitle+' </a><br><small class="font-xs"><i>Published by <a href="javascript:void(0);">'+result.blogUser+'</a></i></small></h3><div class="postContm abcm">'+base64_decode(result.blogContent)+'</div><a class="btn btn-primary h-show-more" href="javascript:void(0);">Read more</a>'+displayBlogedit+'</div><div id="edit-form-postm'+result.id+'" style="clear:both;display:none;margin-top:20px;padding:10px;"><hr><form id="form-edit-postm'+result.id+'" class="edit-postm" enctype="multipart/form-data">	<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-titlem" id="edit-post-titlem'+result.id+'" size="100" value="'+result.blogTitle+'"></p><p><span class="txt-color-blue float-left"><b>Post Banner:</b></span>&nbsp;<input type="file" name="edit-post-bannerm" id="edit-post-bannerm'+result.id+'" size="100" class="float-left"></p><p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p><textarea name="edit-postm" id="edit-postm'+result.id+'" class="ckeditor" placeholder="Enter post description">'+base64_decode(result.blogContent)+'</textarea><input type="hidden" name="post-idm" value="'+result.id+'"><button class="btn btn-primary" type="submit" id="submit-edit-postm'+result.id+'" data-pid="'+result.id+'">Submit</button>&nbsp;<button class="btn btn-primary cancel-edit-postm" id="cancel-edit-postm'+result.id+'" data-epid="'+result.id+'">Cancel</button><br /></form></div>');
					//my_editorm[result.id].destroy(true);
//$shmr=destroy(true);
//$('.postCont').destroy(true);
$shmr1m=null
	$shmr1m=$('.abcm').showMore({
		speedDown: 300,
	        speedUp: 300,
	        height: '90px',
	        showText: 'Show more',
	        hideText: 'Show less'
	});

					//alert($("#edit-post"+result.id).val());
					//$( "#edit-post"+result.id ).addClass( "ckeditor" );
					//$my_editor = CKEDITOR.replace("edit-post"+result.id);
					//alert($("#post-container"+result.id).html());
					//$("#post-container"+result.id).children(".postCont").shorten({showChars: 500});
					return false;
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

	$( document ).off( "click", "#create-new-postm");
	$( document ).on( "click", "#create-new-postm", function() {
		//$( "#form-postm" ).toggle();
		var CreateEditor;
		if($( "#create-new-postm" ).text() == "Create new post")
			{
				$( "#create-new-postm" ).text("Cancel");
				//my_editor_newm = ckt( "new-postm");
				ClassicEditor
				.create( document.querySelector( '#new-postm' ),
				{removePlugins: ['Title','MediaEmbed'],
  				placeholder: ''})
				.then(editor => {
					CreateEditor = editor;
				})
				.catch( error => {
						console.error( error );
				});
				/*document.querySelector( '#submit-new-postm' ).addEventListener( 'click', () => {
					//const editorData = editor.getData();
					alert( my_editor.getData());
					// ...
				} );*/
				
				//document.addEventListener("DOMContentLoaded", function(event){console.log(my_editor.getData())});				
				$("#new-post-titlem").val("");
				$("#new-post-bannerm").val("");
				//$("#new-postm").val("");
				//$('textarea#new-postm').html( CreateEditor.getData() );
			}
		else
			{
				$( "#create-new-postm" ).text("Create new post");
				//my_editor_newm.destroy(true);
				var selection = document.querySelector('#form-new-postm .ck-editor__editable') !== null;
				if (selection) {
					document.querySelector('#form-new-postm .ck-editor__editable').ckeditorInstance.destroy();
				}
				$("#new-post-titlem").val("");
				$("#new-post-bannerm").val("");
				$("#new-postm").val("");
			}

		$( "#submit-new-postm" ).toggle();
		$( "#form-postm" ).toggle();
	});

	//New Post form submit
	$( document ).off( "click", "#submit-new-postm");
	$( document ).on( "click", "#submit-new-postm", function() {
		$( "#form-new-postm" ).submit();
	});


	$( document ).off( "click", ".edit-post-buttonm");
	$( document ).on( "click", ".edit-post-buttonm", function() {
		var pid=$(this).attr('data-pid');
		if($("#edit-form-postm"+pid).css('display') == 'block')
		{
			$( "#edit-form-postm"+pid ).toggle();
			var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
			if (selection) {
				document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
			}
		}
		else
		{

			ClassicEditor
			.create( document.querySelector( "#edit-postm"+pid ),
				{removePlugins: ['Title','MediaEmbed'],
  				placeholder: ''} )
			.catch( error => {
					console.error( error );
			});
			//my_editorm[pid] = ckt( "edit-postm"+pid);
			$( "#edit-form-postm"+pid ).toggle();
		}
	});

	//Cancel Edit Post Form
	$( document ).off( "click", ".cancel-edit-postm");
	$( document ).on( "click", ".cancel-edit-postm", function() {
		var pid=$(this).attr('data-epid');
		if($("#edit-form-postm"+pid).css('display') == 'block')
		{
			var selection = document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable') !== null;
			if (selection) {
				document.querySelector('#form-edit-postm'+pid+' .ck-editor__editable').ckeditorInstance.destroy();
			}
		}
		$( "#edit-form-postm"+pid ).toggle();
	});

	$( document ).off( "keypress", ".input-xsm");
	$( document ).on( "keypress", ".input-xsm", function(event) {
	  if (event.keyCode == 13) {
	  var objj=$(this);
		tcomment=$(this).val();
		$(this).val("");
		tparentid=$(this).attr('data-pid');
		tblogid=$(this).attr('data-pblogid');
		if(tcomment != "" && tparentid != "" && tblogid != ""){
			$.ajax({
				type: "POST",
				url: "assets/includes/marketnewscomment.inc.php",
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
						$("#comments-togglem"+tblogid).html(results.nocomment+" Comments");
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

	$( document ).off( "click", ".reply-linkm");
	$( document ).on( "click", ".reply-linkm", function() {
	 var cid=$(this).attr('id');
	 $(window).scrollTop($('#textm'+cid).offset().top);
	 $('#textm'+cid).focus();
	});

	//function postcomment(blogid)
	//{
	$( document ).off( "click", ".post-commentm");
	$( document ).on( "click", ".post-commentm", function() {
		//event.preventDefault();
		blogid=$(this).attr('pc-id');
		ncomment=$("#new-commentm"+blogid).val();
		nparentid=$("#new-parentIDm"+blogid).val();
		nblogid=$("#new-blogIDm"+blogid).val();
		$("#new-commentm"+blogid).val("");
		if(ncomment != "" && nparentid != "" && nblogid != ""){
			$.ajax({
				type: "POST",
				url: "assets/includes/marketnewscomment.inc.php",
				data: "comment="+ncomment+"&parentid="+nparentid+"&blogid="+nblogid,
				success: function(status){
					if(status != false){
						if(nparentid == 0)
							var appendid="cformm";
						else
							var appendid="cformm";

						var result = JSON.parse(status);
						if(result.parent_id==0)
						{
							var displayreply="<ul class=\"list-inline font-xs\"><li><a href=\"javascript:void(0);\" class=\"text-info\" id=\""+result.id+"\"><i class=\"fa fa-reply\"></i> Reply</a></li></ul>";
						}
						$("#"+appendid+blogid).after("<li class=\"message\"><span class=\"message-text no-margin-left\"> <a href=\"javascript:void(0);\" class=\"username\">"+result.commentuser+"&nbsp;&nbsp;<small class=\"text-muted pull-right ultra-light\"> "+result.datetime+" </small></a> "+ncomment+"</span>"+displayreply+"<input type=\"text\" data-pid=\""+result.id+"\" data-pblogid=\""+blogid+"\" id=\"textm"+result.id+"\" style=\"margin-left:3%;\" placeholder=\"Type and enter\" class=\"form-control input-xsm\"></li>");
						$("#comments-togglem"+blogid).html(result.nocomment+" Comments");
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
		// clears the variable if left blank
		$( document ).on( "click", ".comments-togglem", function() {
		 $("#commentm" + $(this).attr('data-pid')).toggle();
		});
<?php if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){ ?>
<?php if($_SESSION["group_id"] == 1){ 	?>
		$( document ).on( "click", ".publishBlogm", function() {
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
				url: 'assets/includes/marketnews.inc.php',
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
	$( document ).off( "click", ".delete-post-buttonm");
	$( document ).on( "click", ".delete-post-buttonm", function() {
		if (confirm("Do you really want to do this?")) {
			var dpid=$(this).attr('data-pid');
			if(dpid != "" && dpid != 0)
			{
				$.ajax({
					url: 'assets/includes/marketnews.inc.php',
					type: 'POST',
					data: {action:'delete',pid:dpid},
					success: function (data) {
					  if(data != false)
					  {
						var result = JSON.parse(data);
						if(result.error == false)
						{
							$(".drm"+dpid+"").remove();
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

function ckt(cktname)
{ return;
	var editor = CKEDITOR.replace( cktname, {
		filebrowserBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl : 'assets/js/plugin/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Files',
		//filebrowserImageUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserImageUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Images&page=mn',
		//filebrowserFlashUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
		filebrowserFlashUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Flash'
	});
	CKFinder.setupCKEditor( editor, '../' );
	return editor;
}
<?php } ?>
	};

	// end pagefunction

	// run pagefunction
	pagefunction();
</script>
<?php
		$stmt = $mysqli->prepare('SELECT count(b.id) as totalblog FROM market_news b,user u where b.user_id=u.user_id '.$tmpsql.' LIMIT 1');
		if(!$stmt){
			//header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			//exit();
		}

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$stmt->bind_result($totalblog);
			$stmt->fetch();

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
		parent.$('#marketnewsbox').html('');
		parent.$('#marketnewsbox').load('assets/ajax/market-news-pedit.php?ct=<?php echo mt_rand(2,99); ?>&pgno='+pgno);
	};
</script>
