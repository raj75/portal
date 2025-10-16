<?php
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];
if($group_id != 1 and $group_id != 2)
	die("Restricted Access!");

if(isset($_GET["country"]) and isset($_GET["state"]) and @trim($_GET["country"]) != "" and @trim($_GET["state"]) != ""){
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
<?php
	$country = @trim($_GET["country"]);
	$state = @trim($_GET["state"]);
	$tmp_choice_1=$tmp_choice_2=$tmp_choice=array();
	
	$stmtsk = $mysqli->prepare('SELECT id FROM document where country="'.$country.'" and state="'.$state.'" LIMIT 1');
	if($stmtsk){
		$stmtsk->execute();
		$stmtsk->store_result();
		if ($stmtsk->num_rows > 0)
		{
			$stmtsk->bind_result($_id);
			$stmtsk->fetch();

			$stmtskkk = $mysqli->prepare('SELECT id,choice_name,status FROM `document_choice_2`');
			if($stmtskkk){
				$stmtskkk->execute();
				$stmtskkk->store_result();
				if ($stmtskkk->num_rows > 0)
				{
					$stmtskkk->bind_result($_d2_id,$_d2_choice_name,$_d2_status);
					while($stmtskkk->fetch()) {
						$tmp_choice_2[]=array("id"=>$_d2_id,"choice_name"=>$_d2_choice_name,"status"=>$_d2_status,"document"=>"","dsid"=>"","dsstatus"=>"");
					}
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();		
			}
			
			$stmtskk = $mysqli->prepare('SELECT id,choice_name,status FROM document_choice_1');
			if($stmtskk){
				$stmtskk->execute();
				$stmtskk->store_result();
				if ($stmtskk->num_rows > 0)
				{
					$stmtskk->bind_result($_d1_id,$_d1_choice_name,$_d1_status);
					while($stmtskk->fetch()) {
						for($z=0;$z<count($tmp_choice_2);$z++)
						{
							$tmp_choice_2[$z]["dsid"]="";
							$tmp_choice_2[$z]["document"]="";
							$stmtskkkk = $mysqli->prepare('SELECT id,document,status FROM document_save WHERE document_id="'.$_id.'" and document_choice_1_id="'.$_d1_id.'" and document_choice_2_id="'.$tmp_choice_2[$z]["id"].'" LIMIT 1');
							if($stmtskkkk){
								$stmtskkkk->execute();
								$stmtskkkk->store_result();
								if ($stmtskkkk->num_rows > 0)
								{
									$stmtskkkk->bind_result($_ds_id,$_ds_document,$_ds_status);
									$stmtskkkk->fetch();
									$tmp_choice_2[$z]["dsid"]=$_ds_id;
									$tmp_choice_2[$z]["document"]=$_ds_document;
									$tmp_choice_2[$z]["dsstatus"]=$_ds_status;
								}
							}else{
								header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
								exit();		
							}
						}
					
					
						$tmp_choice_1[]=array("did"=>$_id,"id"=>$_d1_id,"choice_name"=>$_d1_choice_name,"status"=>$_d1_status,"sub_choice"=>$tmp_choice_2);
					}
				}
			}else{
				header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
				exit();		
			}			
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}	

if(count($tmp_choice_1))
{
	$tmp_sub_tabs="";	
?>
<div id="tabs">
	<ul>
	<?php for($i=0;$i<count($tmp_choice_1);$i++){
		echo '<li><a href="#tabs-'.$i.'">'.$tmp_choice_1[$i]["choice_name"].'</a></li>';
	} ?>
	</ul>
	<?php for($i=0;$i<count($tmp_choice_1);$i++){
		echo '<div id="tabs-'.$i.'">';
		if(count($tmp_choice_1[$i]["sub_choice"])){
			echo '<div id="subtabs-'.$i.'"><ul>';
			$tmp_sub_tabs .= "$('#subtabs-".$i."').tabs();";
			for($j=0;$j<count($tmp_choice_1[$i]["sub_choice"]);$j++){				
				echo '<li><a href="#tabs-'.$i.'-'.$j.'">'.$tmp_choice_1[$i]["sub_choice"][$j]["choice_name"].'</a></li>';				
			}
			echo '</ul>';
			for($j=0;$j<count($tmp_choice_1[$i]["sub_choice"]);$j++){
				
				echo '<div id="tabs-'.$i.'-'.$j.'">';
				if($tmp_choice_1[$i]["sub_choice"][$j]["choice_name"] == "Utility Rates - Bar Chart")
				{
					?><iframe src="assets/ajax/map-bar-chart.php" border="0" style="width:100%;height:500px;border:none;"></iframe><?php
				}elseif($tmp_choice_1[$i]["sub_choice"][$j]["choice_name"] == "Market Prices")
				{
					?><iframe src="assets/ajax/map-market-prices.php" border="0" style="width:100%;height:500px;border:none;"></iframe><?php
				}else{
					echo '<div class="postCont">'.$tmp_choice_1[$i]["sub_choice"][$j]["document"].'</div>';
					$rand_num=rand(2,490);
					?>

					<a class="btn btn-primary h-show-more">Read more</a>
					<a class="btn btn-warning edit-post-button" data-pid="<?php echo $i.$j.$rand_num; ?>" href="javascript:void(0);"> Edit </a>
					<!--<a class="btn btn-success publishBlog" data-pid="<?php echo $i.$j.$rand_num; ?>" href="javascript:void(0);">Publish It</a>
					<a class="btn btn-danger delete-post-button" data-pid="<?php echo $i.$j.$rand_num; ?>" href="javascript:void(0);"> Delete </a>-->
					<div style="clear: both;display: none;" id="edit-form-post<?php echo $i.$j.$rand_num; ?>">
						<hr>
						<form enctype="multipart/form-data" class="edit-post" id="form-edit-post<?php echo $i.$j.$rand_num; ?>" data-fpid="<?php echo $i.$j.$rand_num; ?>">
							<p class="clear"><span class="txt-color-blue"><b>Edit:</b></span></p>
							<textarea placeholder="Enter post description" id="edit-post<?php echo $i.$j.$rand_num; ?>" name="edit-post" style=""><?php echo $tmp_choice_1[$i]["sub_choice"][$j]["document"]; ?></textarea>
							<input type="hidden" value="<?php echo $tmp_choice_1[$i]["sub_choice"][$j]["dsid"]; ?>" name="dsid">
							<input type="hidden" value="<?php echo $tmp_choice_1[$i]["id"]; ?>" name="dsc1id">
							<input type="hidden" value="<?php echo $tmp_choice_1[$i]["did"]; ?>" name="did">
							<input type="hidden" value="<?php echo $tmp_choice_1[$i]["sub_choice"][$j]["id"]; ?>" name="dsc2id">
							<button id="submit-edit-post<?php echo $i.$j.$rand_num; ?>" type="submit" class="btn btn-primary">Submit</button>&nbsp;<button data-epid="<?php echo $i.$j.$rand_num; ?>" type="button" class="btn btn-primary cancel-edit-post">Cancel</button>
							<br>
						</form>
					</div>
					<?php
				}
				echo '</div>';
			}
			echo '</div>';
		}
		echo '</div>';
	} ?>

</div>
<script src="assets/js/plugin/ckeditor/ckeditor.js"></script>
<script src="assets/js/plugin/ckfinder/ckfinder.js"></script>
<script src="assets/js/jquery.showmore.min.js"></script>
<script src="assets/js/base64_decode.js"></script>
  <script>
  $(function() {
    $( "#tabss" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
  });
	my_editor_new=null;
	$shmr=null;
	var my_editor = [];
	$('#tabs').tabs();
<?php echo $tmp_sub_tabs; ?>
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
	
	$shmr=$('.postCont').showMore({
		speedDown: 300,
			speedUp: 300,
			height: '90px',
			showText: 'Show more',
			hideText: 'Show less'
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
	
	function CKupdate(){
		for ( instance in CKEDITOR.instances )
			CKEDITOR.instances[instance].updateElement();
	}
	
	$( document ).on( "click", ".edit-post-button", function() {
		var pid=$(this).attr('data-pid');

		if($("#edit-form-post"+pid).css('display') == 'block')
		{my_editor[pid].destroy(true);}
		else
		{my_editor[pid] = ckt("edit-post"+pid);}
		$( "#edit-form-post"+pid ).toggle();
		$('html, body').animate({
			scrollTop: $("#submit-edit-post"+pid).offset().top
		}, 2000);
	});
	
	//Cancel Edit Post Form
	$( document ).on( "click", ".cancel-edit-post", function() {
		var pid=$(this).attr('data-epid');
		if($("#edit-form-post"+pid).css('display') == 'block')
		{my_editor[pid].destroy(true);}
		else
		{my_editor[pid] = ckt("edit-post"+pid);}
		$( "#edit-form-post"+pid ).toggle();
	});
	
	$( document ).on( "submit", "form.edit-post", function() {
		CKupdate();
		  //disable the default form submission
		  //event.preventDefault();
			ppid=$(this).attr('data-fpid');
			var formData = new FormData($(this)[0]);

			$.ajax({
				url: 'assets/includes/usmap.inc.php',
				type: 'POST',
				data: formData,
				async: false,
				success: function (data) {
				  if(data != false)
				  {
					var result = JSON.parse(data);
					if(result.error == false)
					{
						//my_editor=null;
						//parent.$("#docs-show").load("assets/ajax/map-pedit2.php?country=us&state=<?php echo $state; ?>");
						CKupdate();
						alert("Saved!");
						if($("#edit-form-post"+ppid).css('display') == 'block')
						{my_editor[ppid].destroy(true);}	
						$( "#edit-form-post"+ppid ).toggle();
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

	function ckt(cktname)
	{
		var editor = CKEDITOR.replace( cktname, {
			filebrowserBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'assets/js/plugin/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'assets/js/plugin/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : 'assets/js/plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
		});
		CKFinder.setupCKEditor( editor, '../' );
		return editor;
	}
  </script>
<?php 
	}
} 
?>