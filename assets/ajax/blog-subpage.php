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


if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2){
if(isset($_GET["newform"])){
?>	
<style>
.txt-center{text-align:center;}
</style>
	<div id="form-post" style="">
		<form id="form-new-post" enctype="multipart/form-data">
			<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="new-post-title" id="new-post-title" size="100"></p>
			<p><span class="txt-color-blue float-left"><b>Post Banner:</b></span>&nbsp;<input type="file" name="new-post-banner" id="new-post-banner" size="100" class="float-left"></p>
			<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
			<textarea name="new-post" id="new-post" placeholder="Enter post description"></textarea>
			<br />
			<footer class="txt-center">
				<button type="submit" class="btn btn-primary" id="add-new-blog">
					Submit
				</button>
				<button type="button" class="btn" id="add-blog-cancel">
					Cancel
				</button>
			</footer>
		</form>
	</div>
<?php




?>

<script type="text/javascript">
	pageSetUp();

	//var pagefunction = function() {

		$( document ).ready(function() {
			my_editor_new=null;
			$shmr=null;
			var my_editor = [];
			/*CKEDITOR.disableAutoInline = true;
			CKEDITOR.inline( 'new-post', {
				extraPlugins: 'sharedspace',
				sharedSpaces: {
					top: 'topSpace',
				}
			});*/
			
			my_editor_new = ckt("new-post");
			$("#new-post-title").val("");
			$("#new-post-banner").val("");
			$("#new-post").val("");
			
			$(document).off('submit', 'form#form-new-post');
			$( document ).on( "submit", "form#form-new-post", function() {
				CKupdate();
			  //disable the default form submission
			  //event.preventDefault();
				var formData = new FormData($(this)[0]);
				if($("#new-post-banner").val() == "")
				{
					alert("Image field cannot be empty");
					$("#new-post-banner").focus();
				}
				$.ajax({
					url: 'assets/includes/blog.inc.php',
					type: 'POST',
					data: formData,
					async: false,
					success: function (data) {
					  if(data != false)
					  {
						var result = JSON.parse(data);
						if(result.error == false)
						{
							my_editor_new.destroy(true);
							$('#blogdialog').dialog('close');
							parent.$( "#blogdialog" ).html("");
							parent.$( "#blogbox" ).html("");
							parent.$('#blogbox').load('assets/ajax/blog-pedit.php?ct=<?php echo time(); ?>&pgno=1');
							//location.reload(true);
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
			
			$(document).off('click', '#blogdialog .ui-dialog-titlebar-close,#blogdialog #add-blog-cancel');
			$( document ).on( "click", "#blogdialog .ui-dialog-titlebar-close,#blogdialog #add-blog-cancel", function() {
				my_editor_new.destroy(true);
				$("#new-post-title").val("");
				$("#new-post-banner").val("");
				$("#new-post").val("");
				$( "#blogdialog" ).html("");
			});	
		});



	 	
		function CKupdate(){
			for ( instance in CKEDITOR.instances )
				CKEDITOR.instances[instance].updateElement();
		}
		
		
	//};	
	
	
	
	function ckt(cktname)
	{
		var editor = CKEDITOR.replace( cktname, {
			filebrowserBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'assets/js/plugin/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Files',
			//filebrowserImageUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserImageUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Images&page=b',
			//filebrowserFlashUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			filebrowserFlashUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Flash'
		});
		CKFinder.setupCKEditor( editor, '../' );
		return editor;
	}


</script>



<?php
	}elseif(isset($_GET["editform"]) and isset($_GET["pid"]) and !empty(@trim($_GET["pid"]))){
		$pID=$mysqli->real_escape_string(@trim($_GET["pid"]));
		if(isset($_GET["pgno"]))$pgno=@trim($_GET["pgno"]);else $pgno=1;
		if(empty($pgno)) $pgno=1;
		
		$stmt = $mysqli->prepare('SELECT b.id, b.user_id, b.post_title, b.post_cont,b.post_banner, b.datetime, b.status,u.firstname,u.lastname FROM blog_posts b,user u where b.user_id=u.user_id and b.id='.$pID.' LIMIT 1');
		if(!$stmt){
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();		
		}

		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$postUser="No Name";
			$stmt->bind_result($postID,$postUserID,$postTitle,$postCont,$postBanner,$postDate,$postStatus,$rfirstname,$rlastname);
			$stmt->fetch();
			if($postUserID == $_SESSION["user_id"] or $_SESSION["group_id"] == 1 or $_SESSION["group_id"] ==2 or $postStatus == 1){	
				$postUser=$rfirstname." ".$rlastname;		
?>	
<style>
.txt-center{text-align:center;}
</style>
	<div id="edit-form-post">
		<hr>
		<form id="form-edit-post" class="edit-post" enctype="multipart/form-data">
			<p><span class="txt-color-blue"><b>Post Title:</b></span>&nbsp;<input type="text" name="edit-post-title" id="edit-post-title" size="100" value="<?php echo $postTitle; ?>"></p>
			<p><span class="txt-color-blue float-left"><b>Post Banner:</b></span>&nbsp;<input type="file" name="edit-post-banner" id="edit-post-banner" size="100" class="float-left"></p>
			<p class="clear"><span class="txt-color-blue"><b>Post Description:</b></span></p>
			<textarea name="edit-post" id="edit-post" placeholder="Enter post description"><?php echo $postCont; ?></textarea>
			<input type="hidden" name="post-id" value="<?php echo $postID; ?>">
			<br />
			<footer class="txt-center">
				<button type="submit" class="btn btn-primary" id="edit-blog-submit">
					Submit
				</button>
				<button type="button" class="btn" id="edit-blog-cancel">
					Cancel
				</button>
			</footer>			
			<br />
		</form>
	</div>

<script type="text/javascript">
	pageSetUp();

	//var pagefunction = function() {

		$( document ).ready(function() {
			my_editor_new=null;
			$shmr=null;
			var my_editor = [];
			/*CKEDITOR.disableAutoInline = true;
			CKEDITOR.inline( 'new-post', {
				extraPlugins: 'sharedspace',
				sharedSpaces: {
					top: 'topSpace',
				}
			});*/
			
			my_editor_new = ckt("edit-post");
			
			$(document).off('submit', 'form.edit-post');
			$( document ).on( "submit", "form.edit-post", function() {
				CKupdate();
			  //disable the default form submission
			  //event.preventDefault();
				var formData = new FormData($(this)[0]);

				$.ajax({
					url: 'assets/includes/blog.inc.php',
					type: 'POST',
					data: formData,
					async: false,
					success: function (data) {
					  if(data != false)
					  {
						var result = JSON.parse(data);
						if(result.error == false)
						{
							my_editor_new.destroy(true);
							$('#blogdialog').dialog('close');
							$( "#blogdialog" ).html("");
							$("document").remove(".paginationpart");
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
					contentType: false,
					processData: false
				});

				return false;
			});	
			
			$(document).off('click', '#blogdialog .ui-dialog-titlebar-close,#blogdialog #edit-blog-cancel');
			$( document ).on( "click", "#blogdialog .ui-dialog-titlebar-close,#blogdialog #edit-blog-cancel", function() {
				my_editor_new.destroy(true);
				$("#edit-post-title").val("");
				$("#edit-post-banner").val("");
				$("#edit-post").val("");
				$('#blogdialog').dialog('close');
				$( "#blogdialog" ).html("");
			});	
		});



	 	
		function CKupdate(){
			for ( instance in CKEDITOR.instances )
				CKEDITOR.instances[instance].updateElement();
		}
		
		
	//};	
	
	
	
	function ckt(cktname)
	{
		var editor = CKEDITOR.replace( cktname, {
			filebrowserBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'assets/js/plugin/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Files',
			//filebrowserImageUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserImageUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Images&page=b',
			//filebrowserFlashUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			filebrowserFlashUploadUrl : 'assets/includes/ckeditors3connector.php?command=QuickUpload&type=Flash'
		});
		CKFinder.setupCKEditor( editor, '../' );
		return editor;
	}
</script>



<?php
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();	
			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();	
		}
	}
}

?>