<?php
//header("Content-Type: text/html; charset=ISO-8859-1");
header("Content-Type: text/html; charset=utf-8");
//header("Content-Type: text/html; charset=charset=utf-8");
error_reporting(E_ALL);
date_default_timezone_set('America/Phoenix');
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
sec_session_start();



if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2)
	die("Restricted Access");

if(!$_SESSION['user_id'])
	die("Restricted Access");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

$mysqli->set_charset('utf8mb4');

$cselected=$ccname="";

if(isset($_GET["cselected"]) and @trim($_GET["cselected"]) != ""){
	//$ccname=@preg_replace('/[^-a-zA-Z0-9_]/', '', @trim($_GET["cselected"]));
	//$cselected="&cselected=".$ccname;
	$ccname= $mysqli->real_escape_string(@trim($_GET["cselected"]));
	$cselected="&folderpath=".$ccname;
}

//temp
//$usermid="Aarons";

$fname="";
if(isset($_GET["action"]) and $_GET["action"]=="maillist"){
	$cnnaid=50;
	if(isset($_GET["fname"]) and !empty($_GET["fname"]) and $_GET["fname"] != "undefined")
	{
		//$fname=preg_replace('/[^\\-a-zA-Z0-9_\@\s]/', '', $_GET["fname"]);
		$ffname=$mysqli->real_escape_string($_GET["fname"]);
		$tmpsql=' and c.company_id='.$cname;
		if ($cstmt = $mysqli->prepare('SELECT folderid FROM fax.`emails` where folderid="'.$ffname.'" limit 1')) {
			$cstmt->execute();
			$cstmt->store_result();
			if ($cstmt->num_rows > 0) {
				$fname=$ffname;
			}
		}

	}

	if(isset($_GET["pgnav"]) and !empty($_GET["pgnav"])){$limitct=$_GET["pgnav"];$pno=$limitct+1;$limitct=(($limitct-1)*$cnnaid); }else{ $limitct=0;$pno=1;}

	$messages=array();
	if(isset($_GET["search"]) and @trim($_GET["search"]) != "")
	{
		$search=$mysqli->real_escape_string($_GET["search"]);

		$subsql=" and crs.company_id=".$ccname." ";

		$subsql="";

		if(!empty($ccname) and empty($fname)){
			$sql="SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid='".$mysqli->real_escape_string($ccname)."' and (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') ".$subsql."ORDER BY sentDateTime DESC LIMIT ".$limitct.",".$cnnaid;
			$ctsql="SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid='".$mysqli->real_escape_string($ccname)."' and (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') ".$subsql;

		}elseif( !empty($fname)){
			$sql= "SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') and  folderid ='".$fname."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",10";
			$ctsql="SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') and folderid ='".$fname."' ".$subsql;
		}else{
			$sql="SELECT DISTINCT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') and folderid ='".$folderpath."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",50";
			$ctsql="SELECT DISTINCT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where (subject like '% ".$search." %' OR subject like '% ".$search."' OR subject like '".$search." %' OR sender like '% ".$search." %' OR sender like '% ".$search."' OR sender like '".$search." %') and folderid ='".$folderpath."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",50";
		}
//echo $sql;
		$messages=array();
		if ($msgstmt = $mysqli->prepare($sql)) {
			$msgstmt->execute();
			$msgstmt->store_result();
			if ($msgstmt->num_rows > 0) {
				$msgstmt->bind_result($msg_id,$msg_sendername,$msg_subject,$msg_date,$msg_fpath,$msg_attachments,$is_extattachment);
				while($msgstmt->fetch()){
					$messages[]=array("id"=>$msg_id,"sendername"=>$msg_sendername,"subject"=>$msg_subject,"date"=>$msg_date,"fpath"=>$msg_fpath,"attachments"=>$msg_attachments);
				}
			}
		}
	}
	else{
		$messages=array();

		//if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $subsql=" and crs.company_id=".$cname." ";
		//else $subsql=" and crs.company_id=".$ccname." ";

		$subsql="";

		if(!empty($ccname) and empty($fname)){
			//$sql= "SELECT id,`from`,subject,date_recv,folder_id,attachments FROM libremail.`messages` where account_id='".$mysqli->real_escape_string($_GET["cselected"])."' and folder_id ='".$mysqli->real_escape_string($fname)."' ".$subsql." ORDER BY date_recv DESC LIMIT ".$limitct.",10";
			//$ctsql="SELECT id,`from`,subject,date_recv,folder_id,attachments FROM libremail.`messages` where account_id='".$mysqli->real_escape_string($_GET["cselected"])."' and folder_id ='".$mysqli->real_escape_string($fname)."' ".$subsql;


			$sql= "SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid ='".$ccname."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",50";
			$ctsql="SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid ='".$ccname."' ".$subsql;
		}elseif(!empty($fname)){
			$sql= "SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid ='".$fname."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",50";
			$ctsql="SELECT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid ='".$fname."' ".$subsql;
		}else{
			if($_GET["folderpath"]){$folderpath=$_GET["folderpath"];

				$sql= "SELECT DISTINCT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid= '".$folderpath."' ".$subsql." ORDER BY sentDateTime DESC LIMIT ".$limitct.",50";
				$ctsql="SELECT DISTINCT id,`sender`,subject,sentDateTime,folderid,attachments,external_attachments FROM fax.`emails` where folderid  ='".$folderpath."' ".$subsql." ORDER BY sentDateTime DESC";

			}
		}
//echo $sql;
		if ($msgstmt = $mysqli->prepare($sql)) {
			$msgstmt->execute();
			$msgstmt->store_result();
			if ($msgstmt->num_rows > 0) {
				$msgstmt->bind_result($msg_id,$msg_sendername,$msg_subject,$msg_date,$msg_fpath,$msg_attachments,$is_extattachment);
				while($msgstmt->fetch()){
					$tmparr=array();
					$tmparr=explode(":",$msg_sendername);
					if(count($tmparr) and count($tmparr) > 1) $msg_sendername=$tmparr[0]."&lt;".$tmparr[1]."&gt;";else continue;
					$messages[]=array("id"=>$msg_id,"sendername"=>$msg_sendername,"subject"=>$msg_subject,"date"=>$msg_date,"fpath"=>$msg_fpath,"attachments"=>$msg_attachments,"is_extattachment"=>$is_extattachment);
				}
			}
		}

	}
?>
<style>
.inbox-data-date{width:18% !important;}
.email-list-table td{line-height:unset !important;}
.email-list-table .inbox-data-from div{width:232px !important;}
.email-list-table .inbox-data-message{
	width: 50%;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
<table id="inbox-table" class="table table-striped table-hover email-list-table">
	<tbody>
<?php
if(!count($messages)) die("No Mail!");
$icount=1;
//rsort($messages);
foreach ($messages as $message) {
	$message['seen']=1;
	$message["subject"] = replaceAccents(@trim($message["subject"]));
	/*$fileslist=array();
	$attachments = $message->attachments();
	foreach ($attachments as $attachment) {
		$fileslist[]=$attachment->getFilename().".".$attachment->getExtension();
	}*/
?>
		<tr id="msg<?php echo $icount; ?>" class="<?php echo ($message['seen']==1?'read':'unread'); ?>">
			<td class="inbox-data-from hidden-xs hidden-sm" uid="<?php echo $message["id"]; ?>" subj="<?php echo $message["subject"]; ?>" fname="<?php echo $message['fpath']; ?>">
				<div>
					<?php echo str_replace('"','',$message["sendername"]); ?>
				</div>
			</td>
			<td class="inbox-data-messages" uid="<?php echo $message["id"]; ?>" subj="<?php echo $message["subject"]; ?>" fname="<?php echo $message['fpath']; ?>">
				<div>
					<?php
						if(strlen($message["subject"]) < 0){
							echo substr($message["subject"],0,50)."...";
						}else echo $message["subject"];
					?>
				</div>
			</td>
			<?php /*if(count($fileslist)){?>
			<td class="inbox-data-attachment hidden-xs" uid="<?php echo $message->header()->get('uid'); ?>" subj="<?php echo $message->header()->get('subject'); ?>" fname="<?php echo $fname; ?>">
				<div>
					<a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="FILES: <?php echo implode(", ",$fileslist); ?>" class="txt-color-darken"><i class="fa fa-paperclip fa-lg"></i></a>
				</div>
			<?php }else{?>
			<td>
			<?php } ?>
			</td><?php */ ?>
			<td class="inbox-data-attachments hidden-xs" uid="<?php echo $message["id"]; ?>" subj="<?php echo @trim($message["subject"]); ?>" fname="<?php echo $message['fpath']; ?>">
				<div>
					<?php
						//if(!empty($message['attachments']) and $message['attachments'] != "[]"){
						if(!empty($message['is_extattachment'])){
							//$afilename=@explode("@@@",$message['attachments']);
							//if(is_array($afilename) and count($afilename)){
						?>
					<span class="glyphicon glyphicon-paperclip"></span>
						<?php
						//	}
						}



					?>
				</div>
			</td>
			<td class="inbox-data-date hidden-xs" uid="<?php echo $message["id"]; ?>" subj="<?php echo @trim($message["subject"]); ?>" fname="<?php echo $message['fpath']; ?>">
				<div>
					<?php echo date("g:i a D, j M Y", strtotime($message["date"])); ?>
				</div>
			</td>
		</tr>
<?php ++$icount; } ?>
	</tbody>
</table>

			<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
		<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
			<style>
			.usage{
			  background-color: #fff;
			  margin: 20px 0;
			  padding-top: 29px;
			}
			.dataTables_paginate ul li {padding:0px !important;}
			.dataTables_paginate ul li a{margin:-1px !important;}
			.dt-buttons{
			float: right !important;
			margin: 0.5% auto !important;
			}
			.dataTables_wrapper .dataTables_length{
			float: right !important;
			margin: 1% 1% !important;
			}
			.dataTables_wrapper .dataTables_filter{
			float: left !important;
			width: auto !important;
			margin: 1% 1% !important;
			text-align:left !important;
			}
			.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
			.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
			.no-padding .dataTables_wrapper table, .no-padding>table{border-bottom:1px solid #cccccc !important;}
			</style>
<!-------New Added-->
<?php
if(isset($ctsql)){
	$totpgcnt=1;
	if ($ctstmt = $mysqli->prepare($ctsql)) {
		$ctstmt->execute();
		$ctstmt->store_result();
		//$totpgcnt=ceil($ctstmt->num_rows/$cnnaid);
		$total_pages=$ctstmt->num_rows;
	}
//$pno=1;
//$cnnaid=10;
if($totpgcnt > 1)$last=$totpgcnt-1;
if(!isset($cnnaid)) $cnnaid=50;
//$totpgcnt=100;
if(isset($_GET["pgnav"]) and !empty($_GET["pgnav"])){$tmppgno=$_GET["pgnav"];}else{$tmppgno=1;}


//$total_pages=50;
$num_results_on_page=$cnnaid;
//if(isset($_GET["page"])) $page=$_GET["page"];
//else $page=1;

$page=$tmppgno;

$ceilcal=ceil($total_pages / $num_results_on_page);

if ($ceilcal > 0){

$entries_cal=(($page-1)*$num_results_on_page);
?>
<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper no-footer">
	<div class="dataTables_info" id="datatable_fixed_column_info" role="status" aria-live="polite">Showing <?php echo ($entries_cal+1); ?> to <?php if($page < $ceilcal) echo ($entries_cal+10); elseif($page==$ceilcal) echo $total_pages; ?> of <?php echo $total_pages; ?> entries
	</div>
	<div class="dataTables_paginate paging_simple_numbers" id="datatable_fixed_column_paginate">
		<a class="paginate_button previous <?php if($page <= 1) echo "disabled"; ?>" aria-controls="datatable_fixed_column" data-dt-idx="0" tabindex="0" id="datatable_fixed_column_previous" <?php if($page > 1){ ?> onclick="paginateacc(<?php echo ($page-1); ?>)" <?php } ?>>Previous</a>
		<span>
		<?php

		if ($page > 3){
		?>
			<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="1" tabindex="0" onclick="paginateacc(1)">1</a>
			...
		<?php
		}

		if ($page-2 > 0){ ?>
			<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $page-2; ?>" tabindex="0" onclick="paginateacc(<?php echo $page-2; ?>)"><?php echo $page-2; ?></a>
		<?php }

		if ($page-1 > 0){ ?>
		<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $page-1; ?>" tabindex="0" onclick="paginateacc(<?php echo $page-1; ?>)"><?php echo $page-1; ?></a>
		<?php }	?>

		<a class="paginate_button current" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $page; ?>" tabindex="0" onclick="paginateacc(<?php echo $page; ?>)"><?php echo $page; ?></a>


		<?php
		if ($page+1 < $ceilcal+1){ ?>
		<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $page+1; ?>" tabindex="0" onclick="paginateacc(<?php echo $page+1; ?>)"><?php echo $page+1; ?></a>
		<?php }

		if ($page+2 < $ceilcal+1){ ?>
		<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $page+2; ?>" tabindex="0" onclick="paginateacc(<?php echo $page+2; ?>)"><?php echo $page+2; ?></a>
		<?php }


		if ($page < $ceilcal-2){ ?>
		...
		<a class="paginate_button" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo $ceilcal; ?>" tabindex="0" onclick="paginateacc(<?php echo $ceilcal; ?>)"><?php echo $ceilcal; ?></a>
		<?php } ?>
		</span>
		<a class="paginate_button next <?php if($page >= $ceilcal) echo "disabled"; ?>" aria-controls="datatable_fixed_column" data-dt-idx="<?php echo ($page+1); ?>" tabindex="0" id="datatable_fixed_column_next" <?php if($page < $ceilcal){ ?> onclick="paginateacc(<?php echo ($page+1); ?>)" <?php } ?>>Next</a></div>
</div>

<?php } ?>
<?php } ?>

<!--------------->
<?php //print_r($_GET);
$tmpuurl="";
if(isset($_GET) and count($_GET)){
	$tmpurlarr=array();
	foreach($_GET as $kky=>$vvl){
		if($kky=="pgnav") continue;
		$tmpurlarr[]=$kky."=".$vvl;
	}
	$tmpuurl=implode("&",$tmpurlarr);
	$tmpuurl=$tmpuurl."&pgnav=";
}

?>
<script>
function paginateacc(pgnav){

	loadURL("/assets/ajax/email/email-list-fax.php?<?php echo $tmpuurl; ?>"+pgnav, $('#inbox-content #maillists'));
	//alert(window.location.href);
}
	//Gets tooltips activated
	$("#inbox-table [rel=tooltip]").tooltip();

	$("#inbox-table input[type='checkbox']").change(function() {
		$(this).closest('tr').toggleClass("highlight", this.checked);
	});

	$("#inbox-table .inbox-data-messages").click(function() {
		$this = $(this);
		getMail($this);
	})
	$("#inbox-table .inbox-data-from").click(function() {
		$this = $(this);
		getMail($this);
	})
	$("#inbox-table .inbox-data-attachments").click(function() {
		$this = $(this);
		getMail($this);
	})
	$("#inbox-table .inbox-data-date").click(function() {
		$this = $(this);
		getMail($this);
	})
	function getMail($this) {
		//console.log($this.closest("tr").attr("id"));+"from="+frm+"date="+dtt
		uid=$this.attr("uid");
		subj=$this.attr("subj");
		fname=$this.attr("fname");
		$("#inbox-content #maildetails").removeClass('hidden');
		$("#inbox-content #maillists").addClass('hidden');
		$("#backtomail").removeClass('hidden');
		loadURL("/assets/ajax/email/email-opened-fax.php?uid="+uid+"&fname="+fname+"&subj="+subj+"&cts=<?php echo time(); ?><?php echo $cselected; ?>", $('#inbox-content > #maildetails'));
	}


	$('.inbox-table-icon input:checkbox').click(function() {
		enableDeleteButton();
	})

	$(".deletebutton").click(function() {
		$('#inbox-table td input:checkbox:checked').parents("tr").rowslide();
		//$(".inbox-checkbox-triggered").removeClass('visible');
		//$("#compose-mail").show();
	});

	function enableDeleteButton() {
		var isChecked = $('.inbox-table-icon input:checkbox').is(':checked');

		if (isChecked) {
			$(".inbox-checkbox-triggered").addClass('visible');
			//$("#compose-mail").hide();
		} else {
			$(".inbox-checkbox-triggered").removeClass('visible');
			//$("#compose-mail").show();
		}
	}

</script>
<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
}else if(isset($_GET["action"]) and $_GET["action"]=="folder"){
	$folders=array();

	$pfid=$fl_foldername=$mainfoldertmpsql="";
	if(!empty($ccname)) $mainfoldertmpsql=' and ef.folderId="'.$ccname.'"';

	if ($fstmt = $mysqli->prepare('SELECT DISTINCT folderId,folder_name FROM fax.`folderlist` ef where ef.`order`=1'.$mainfoldertmpsql.' LIMIT 1')) {
		$fstmt->execute();
		$fstmt->store_result();
		if ($fstmt->num_rows > 0) {
			$fstmt->bind_result($id,$fl_foldername);
			$fstmt->fetch();
			$folders[]=array("id"=>$id,"foldername"=>$fl_foldername."@@@".$id);
			$pfid=@trim($id);
			$pfname=@trim($fl_foldername);
		}
	}

	if(!count($folders) or empty($pfid) or empty($pfname)) die("No Folders!");

	$sql='SELECT DISTINCT folderId,folder_name FROM fax.`folderlist` where parent_folderId ="'.$pfid.'" and `order`=2';
	if ($fstmt = $mysqli->prepare($sql)) {
		$fstmt->execute();
		$fstmt->store_result();
		if ($fstmt->num_rows > 0) {
			$fstmt->bind_result($id,$fl_foldername);
			while($fstmt->fetch()){
				$folders[]=array("id"=>$id,"foldername"=>$pfname."/".$fl_foldername."@@@".$id);
			}
		}
	}

/*
	//if ($folstmt = $mysqli->prepare("SELECT DISTINCT crs.folderpath FROM `correspondence` crs, company c where c.company_id=crs.company_id".$subfolderquery)) {
	//if ($folstmt = $mysqli->prepare("SELECT id,`name` FROM libremail.`folders` where deleted !=1 and account_id=".$cname)) {
	if(!isset($_GET['cselected'])) $sql='SELECT distinct e.folderid,e.folderpath FROM email.`emails` e, vervantis.company c where e.folderpath LIKE concat(REPLACE(c.email_folder_path,"INBOX/Correspondence/",""),\'%\') and c.email_folder_path !="" and c.company_id='.$cname;
	else{
		if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2){die("No Folders!"); }
		else $sql='SELECT distinct e.folderid,e.folderpath FROM email.`emails` e,email.`emails` ee, vervantis.company c where e.folderpath LIKE concat(REPLACE(c.email_folder_path,"INBOX/Correspondence/",""),"%") and c.email_folder_path !="" and e.folderpath LIKE concat(ee.folderpath,"%") and ee.folderid="'.$ccname.'"';
	}
echo $sql;
	if ($folstmt = $mysqli->prepare($sql)) {
		$folstmt->execute();
		$folstmt->store_result();
		if ($folstmt->num_rows > 0) {
			$folstmt->bind_result($id,$fl_foldername);
			while($folstmt->fetch()){
				$folders[]=array("id"=>$id,"foldername"=>$fl_foldername."@@@".$id);
			}
		}
	}
*/

	if(!count($folders)) die("No Folders!");
	//$folders=array_unique($folders);
	//print_r($folders);die();


	$nested_array = array();

	foreach($folders as $item) {
	    $temp = &$nested_array;

	    foreach(explode('/', $item["foldername"]) as $key) {
	        $temp = &$temp[$key];
	    }

	    $temp = array();
	}

//print_r($nested_array);
//die();
?>
<style>
#foldercont {
  width: 300px;
  height: 360px;
  border: 1px solid #ccc;
  margin: 10px;
  padding: 5px;
}

#foldercont ul {
  padding: 0px;
  margin: 0px;
  text-align: left;
  overflow: hidden;
  overflow-y: scroll;
}

#foldercont ul li{
  list-style-type: none;
  font-size: 17px;
  padding: 5px;
}
.inbox-menu-lg ul{padding-left:7px !important}
.inbox-menu-lg li i,.inbox-menu-lg li a{display:inline-block !important;}
.inbox-menu-lg li a{padding-left:1px;}
.inbox-menu-lg .fa-folder-open-o{cursor:pointer;}
</style>
<script>
function loadfoldermail(fname) {
	$("#inbox-content #maildetails").addClass('hidden');
	$("#inbox-content #maillists").removeClass('hidden');
	$("#backtomail").addClass('hidden');
	$("#mailsearch").val('');
	$(".inbox-menu-lg li").removeClass('active');
	//str = fname.replace(/abc/g, '');
	$(".inbox-menu-lg li#"+fname.replaceAll(/[^A-Za-z0-9]/gi,"")).addClass('active');
	if(fname=="general"){ fname=''; }
	loadURL("/assets/ajax/email/email-list-fax.php?action=maillist&acc=<?php echo $cname; ?>&fname="+fname+"<?php echo $cselected; ?>", $('#inbox-content #maillists'));
}

//function checknested(ifnested){
//	$( "p" ).next( ".selected" ).css( "background", "yellow" );
//}
$('.dir').click(function(e) {
    //e.stopPropagation();
    //$(this).children().slideToggle();
		//$(this).next( ul ).css("dislay","none");
		$(this).parent("li").next( "ul" ).toggle();
});
//if($(".inbox-menu-lg li").next("ul").length > 0) {
    //alert("Exists");
//}


$(".inbox-menu-lg ul").prev("li").find("i").removeClass( "fa-folder-o" ).addClass("fa-folder-open-o");
</script>
<?php
function nestedul($item){//print_r($item);
	if(!count($item)) return;
	//print_r($item);die();
	foreach($item as $ky=>$vl){
		$tmparr=explode("@@@",$ky);
		if(!count($tmparr)) continue;
		if(strpos($ky,"@@@")!==FALSE){
		?>
		<li id="<?php echo @preg_replace("/[^A-Za-z0-9]/s","",$tmparr[1]);?>" class="">
			<i class="fa fa-folder-o fa-1 dir" aria-hidden="true"></i><a href="javascript:void(0);" onclick="loadfoldermail('<?php echo $tmparr[1]; ?>')" ftype="<?php echo $tmparr[1]; ?>"> <?php echo $tmparr[0]; ?></a>
		<?php
	}
		if(count($vl)){
			?>
			<ul>
			<?php
			nestedul($vl);
			?>
			</ul>
			<?php
		}else{

		}
		if(strpos($ky,"@@@")!==FALSE){
		?>
		</li>
		<?php
	}
	}
}
?>
<div id="foldercont1">
<ul class="inbox-menu-lg">
	<li id="general" class="active">
		<a href="javascript:void(0);" onclick="loadfoldermail('general')"  ftype="">General</a>
	</li>
<?php
foreach($nested_array as $nky=>$item) {
	$tmparr=explode("@@@",$nky);
	if(!count($tmparr)) continue;
	if(strpos($nky,"@@@")!==FALSE){
		?>
		<li id="<?php echo $tmparr[1];?>" class="">
			<i class="fa fa-folder-o fa-1 dir" aria-hidden="true"></i><a href="javascript:void(0);" onclick="loadfoldermail('<?php echo $tmparr[1]; ?>')" ftype="<?php echo $tmparr[1]; ?>"> <?php echo $tmparr[0]; ?></a>
		<?php
	}
	if(count($item)){
		?>
		<ul>
		<?php
		nestedul($item);
		?>
		</ul>
		<?php
	}
	if(strpos($nky,"@@@")!==FALSE){
	?>
	</li>
	<?php
}
}
/*
	foreach($folders as $ky=>$vl){
		//if($ky==0) continue;
		$fnamearr=explode("/",$vl['foldername']);
		$tempfname=end($fnamearr);
		$tempspace="";
		if(count($fnamearr) > 1){
			foreach($fnamearr as $vvvvl)
				$tempspace=$tempspace."&nbsp;&nbsp;";
		}
		//if(preg_match("/INBOX\/Correspondence\/".$usermid."\/([^~]*)/s",$vl,$tempno)){$email_folder_path
		//if(preg_match("/".str_replace('/', '\/',$email_folder_path)."\/([^~]*)/s",$vl,$tempno)){
	?>
				<li id="<?php echo $vl['id'];?>" <?php if(count($fnamearr) > 1){ echo 'class="nested"';} ?> onclick="checknested(<?php if(count($fnamearr) > 1){echo 1; }else{ echo 0; } ?>)">
					<a href="javascript:void(0);" onclick="loadfoldermail('<?php echo $vl['id']; ?>')" ftype="<?php echo $vl['id']; ?>"><?php echo $tempspace; ?><i class="fa fa-caret-right"></i> <?php echo $tempfname; ?></a>
				</li>
	<?php //}
	}
	*/
?>
</ul>
</div>
<?php
}else echo "Nothing to show!";


function replaceAccents($str)
{
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í',
               'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü',
               'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë',
               'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û',
               'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ',
               'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę',
               'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ',
               'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ',
               'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń',
               'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ',
               'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š',
               'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů',
               'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž',
               'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ',
               'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ',
               'ǿ', '€', '™', '˜');
    $b = array('');
    return str_replace($a, $b, $str);
}
?>
