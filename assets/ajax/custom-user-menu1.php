<?php

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
$user_one = $_SESSION["user_id"];

if(isset($_GET['userid']) and $_GET['userid'] != "" and $_GET['userid'] > 0)
	$_userid=$_GET['userid'];
else die("Incorrect Parameters Provided!");

if($_SESSION["group_id"]==1 and isset($_GET["editmenu"])){
$jsondata=$user_jsondata="";
	if ($stmt = $mysqli->prepare('SELECT custom_interface FROM users_interface where user_id="'.$_userid.'" LIMIT 1')) { 
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($user_jsondata);
			$stmt->fetch();
		}else{
			if ($stmts = $mysqli->prepare('SELECT interface FROM user u, usergroups ug where u.usergroups_id = ug.id and u.user_id="'.$_userid.'" LIMIT 1')) { 

//('SELECT interface FROM user u, usergroups ug where u.usergroups_id = ug.id and u.id="'.$_userid.'" LIMIT 1')) { 

				$stmts->execute();
				$stmts->store_result();
				if ($stmts->num_rows > 0) {
					$stmts->bind_result($user_jsondata);
					$stmts->fetch();
				}else
					exit(false);
			}else
				exit(false);
		}
	}else
		exit(false);

	if(@trim($user_jsondata) != ""){
		if(preg_match_all("/\"id\"\:([0-9]+)[},]+/s",$user_jsondata,$tmp_uarr))
		{
			array_shift($tmp_uarr);
			if ($stmt = $mysqli->prepare('SELECT id,title FROM interface')) { 
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($_id,$_title);
					$tmp_jarr=array();
				   while ($stmt->fetch()) {
					if(in_array($_id,$tmp_uarr[0]))
					{
						$user_jsondata=preg_replace('/"id":'.$_id.'([\,\}]{1}){1}/s','"id":'.$_id.',"title":"'.$_title.'"${1}',$user_jsondata);
						//continue;
					}else
						$tmp_jarr[] = '{"id":'.$_id.',"title":"'.$_title.'"}';	
				   }
				   $jsondata='['.implode(',',$tmp_jarr).']';
				}else
					die('No Menu List Available');
			}else
				die('Error Occured');
		}else
			die('Error Occured');
	}else
		die('Wrong parameters provided');		

	if(@trim($jsondata) == "")
		die('Wrong parameters provided');
		
	$_data = json_decode($user_jsondata);
	if (is_null($_data)) {
	   die("Error Occured");
	}

?>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.nestable.css">
<style>
#widget-gridss{background:#fff;}
#widget-gridss .saveit{text-align:center;height:auto !important;}
#widget-gridss .widget-body > row {margin:0px !important;}

</style>
<section id="widget-gridss">
	
			<div class="jarviswidget jarviswidget-color-blueDark padding10" id="wid-id-00" data-widget-fullscreenbutton="false" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2> Edit Menu </h2>					
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
					   <menu id="nestable-menu">
							<button type="button" data-action="expand-all">Expand All</button>
							<button type="button" data-action="collapse-all">Collapse All</button>
							<button type="button" data-action="reset-all">Reset</button>
							<button type="button" data-action="save-all">Save</button>
						</menu>

						<div class="cf nestable-lists">

							<div class="dd" id="nestable">
								<p><b>Remaining Menu</b></p>
								<ol class="dd-list dd1-list"></ol>
							</div>

							<div class="dd" id="nestable2">
								<p><b>User Menu</b></p>
								<ol class="dd-list dd2-list"></ol>
							</div>

						</div>

						<p><strong>Serialised Output (per list)</strong></p>

						<b>Remaining Menu</b>
						<textarea id="nestable-output"></textarea>
						<br />
						<b>User Menu</b>
						<textarea id="nestable2-output"></textarea>
						
					<div class="dd" id="nestable3">
						<ol class='dd-list dd3-list'></ol>
					</div>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
		<!-- WIDGET END -->
</section>
<script src="assets/js/jquery.nestable.js"></script>
<script>

$(document).ready(function()
{
	var userid = '<?php echo (isset($_GET['userid'])?$_GET['userid']:0); ?>';
	
	//var menulist = '[{"id":1},{"id":2,"children":[{"id":3},{"id":4},{"id":5,"children":[{"id":6},{"id":7},{"id":8}]},{"id":9},{"id":10}]},{"id":11},{"id":12}]';
	var menulist = '<?php echo $jsondata; ?>';
	var usermenu = '<?php echo $user_jsondata; ?>';	

    var output = '';
    $.each(JSON.parse(menulist), function (index, item) {
        output += buildItem(item);
    });
    $('.dd1-list').html(output);
    $('#nestable').nestable();
	
    var output = '';
    $.each(JSON.parse(usermenu), function (index, item) {
        output += buildItem(item);
    });
    $('.dd2-list').html(output);
    $('#nestable2').nestable();
	
	
	
	
    function buildItem(item) {
        var html = "<li class='dd-item' data-id='" + item.id + "'>";
        html += "<div class='dd-handle'>" + item.title + "</div>";
        if (item.children) {
            html += "<ol class='dd-list'>";
            $.each(item.children, function (index, sub) {
                html += buildItem(sub);
            });
            html += "</ol>";
        }
        html += "</li>";
        return html;
    }	
	
	
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);

    // activate Nestable for list 2
    $('#nestable2').nestable({
        group: 1
    })
    .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));
    updateOutput($('#nestable2').data('output', $('#nestable2-output')));

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
        if (action === 'reset-all') {
			var output = '';
			$.each(JSON.parse(menulist), function (index, item) {
				output += buildItem(item);
			});
			$('.dd1-list').html(output);
			$('#nestable').nestable();
			
			var output = '';
			$.each(JSON.parse(usermenu), function (index, item) {
				output += buildItem(item);
			});
			$('.dd2-list').html(output);
			$('#nestable2').nestable();
			
			menulists = menulist.replace(/\,\"title\"\:\"[a-zA-Z0-9 ]+\"/g, '');
			usermenus = usermenu.replace(/\,\"title\"\:\"[a-zA-Z0-9 ]+\"/g, '');
			$('#nestable-output').val(menulists);
			$('#nestable2-output').val(usermenus);
        }
        if (action === 'save-all') {
			if(userid == '' || userid == 0){
				alert('Incorrect User Id');
			}else{
				var jsondata = $('#nestable2-output').val();
				$.ajax({
					type: "POST",
					url: "assets/includes/interface.inc.php",
					data: "userid="+userid+"&jsondata="+jsondata,
					async: true,
					success: function(rstatus){
						if(rstatus == true)
							alert("Saved");
						else
							alert("Error Occured. Please try after sometime!");
					}
				});
			}
        }
    });
});
</script>
<?php
}

if(($_SESSION["group_id"]==1 or $_SESSION["group_id"]==5) and isset($_GET["editmenu"])){?>
<style>
#widget-grids article{height:200px}
#widget-grids{background:#fff;}
#widget-grids .saveit{text-align:center;height:auto !important;}
</style>
<section id="widget-grids"></section>
<script>
$(document).ready(function()
{
	$('#widget-grids').load('assets/ajax/custom-user-permission.php?userpermission=true&userid='+<?php echo $_userid; ?>);	
});
</script>
<?php	
}
?>