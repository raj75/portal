<?php
require_once 'assets/includes/db_connect.php';
require_once 'assets/includes/functions.php';
@sec_session_start();

?>
		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">
			<!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional hre="" links. See documentation for details.
				-->

				<?php
if($_SESSION["user_id"] == 455555555){
					$ui = new SmartUI();
					$ui->create_nav($page_nav)->print_html();
}else{ echo '<span class="hidden">'.time().'</span>'; ?>
<style>
#left-panel nav,#left-panel nav #js-nav-menu-wrapper>ul{display: inline-flex;}
#left-panel nav #js-nav-menu-wrapper>ul .collapse-sign{margin-left:2px;}
#js-nav-menu-wrapper-left-btn,#js-nav-menu-wrapper-right-btn{margin-top: 18px;width:20px;text-align:center;}
#js-nav-menu-wrapper .main-menu-name{/* white-space: nowrap; */ border-right: 1px solid #222;
border-left: 1px solid #4E4E4E;}
#js-nav-menu-wrapper>ul li>a i.fa{line-height: 1 !important;}
#js-nav-menu-wrapper>ul li>a i{
	top: 3px;
    text-align: center;
	position: relative !important;
	font-size:24px;
	width:100%;
}
.overflow-hidden {overflow: hidden !important;}
#js-nav-menu-wrapper>ul>li>ul{z-index: 12 !important;position: fixed;/*width: auto;*/ white-space: nowrap;top: 121px;display: none !important;}
#js-nav-menu-wrapper .main-menu-name:hover ul{display: block !important;}
#js-nav-menu-wrapper>ul>li>a{text-align: center;line-height:1;width: 91px !important;padding: 6px 1px !important;
display: table;
height: 74px;
overflow: hidden;}
#js-nav-menu-wrapper .nav-link-text{text-overflow: ellipsis;
    display: table-row;
    vertical-align: middle;}
#js-nav-menu-wrapper ul li span,#js-nav-menu-wrapper-left-btn span,#js-nav-menu-wrapper-right-btn span{top: 8px !important;}
#js-nav-menu-wrapper .main-menu-name{overflow: visible; }
#js-nav-menu-wrapper ul li ul li>a {
   width:100%;
   font-size: 13px !important;
   /*font-weight:300 !important;*/
}
#js-nav-menu-wrapper ul li ul li>a:after {
  clear:both;
  content: "" !important;
  display:none !important;
}
#js-nav-menu-wrapper .collapse-sign{display:none;}
#js-nav-menu-wrapper .main-menu-name .nav-link-text{
   font-size: 13px !important;
   text-overflow: ellipsis;
}
#js-nav-menu-wrapper ul li ul:before {content:"" !important;display:none;}
#js-nav-menu-wrapper-left-btn,#js-nav-menu-wrapper-right-btn,#js-nav-menu-wrapper-left-btn span,#js-nav-menu-wrapper-right-btn span{color: #c0bbb7;}
#js-nav-menu-wrapper-left-btn:hover,#js-nav-menu-wrapper-right-btn:hover,#js-nav-menu-wrapper-left-btn span:hover,#js-nav-menu-wrapper-right-btn span:hover{color: #fff;}
#ulmainmenu{overflow:visible !important;}
</style>
<a href="javascript:void(0);" id="js-nav-menu-wrapper-left-btn" class=""><span class="glyphicon glyphicon-chevron-left"></span></a>
<div id="js-nav-menu-wrapper" style="margin-left: 3px;display: inline-flex;width: 95.2vw !important;" class="overflow-hidden">
<?php
	if(count($page_nav)){
		echo '<ul style="margin-left: 0px;overflow:visible !important;" id="ulmainmenu" data-fresh="'.time().'">';
		foreach($page_nav as $nky => $nvl){
			if(isset($nvl["disable"]) and $nvl["disable"]==0){}else continue;
			if(isset($nvl["icon"]) and preg_match("/(fa)/s",$nvl["icon"],$nosave)) $n_icon="fa fa-lg fa-fw ".$nvl["icon"];
			else if(isset($nvl["icon"]) and preg_match("/(glyphicon)/s",$nvl["icon"],$nosave)) $n_icon="glyphicon ".$nvl["icon"];
			else $n_icon="";

			echo '<li class="main-menu-name"><a href="JavaScript:void(0);"  '.(isset($nvl["url"])?'onclick="navigateurl(\''.$nvl["url"].'\',\''.$nvl["title"].'\')"':"").'><i class="'.$n_icon.'"></i><span class="nav-link-text">'.$nvl["title"].'</span>'.((isset($nvl["sub"]) and is_array($nvl["sub"]) and count($nvl["sub"]))?'<b class="collapse-sign"><span class="glyphicon glyphicon-chevron-down"></span></b>':"").'</a>';
			if(isset($nvl["sub"]) and is_array($nvl["sub"]) and count($nvl["sub"])){
				echo '<ul>';
				foreach($nvl["sub"] as $nsubky => $nsubvl){
					if(isset($nsubvl["disable"]) and $nsubvl["disable"]==0){}else continue;
					////////////////////////////////////////////////////////////////

					////////////////////////////////
if($nsubvl["title"]=="UBM Software" || $nsubvl["title"]=="CSR/ESG Software" || $nsubvl["title"]=="UBM Archive"){
$mysqlii = new mysqli(HOST, USER, PASSWORD,DATABASE);
//require_once '/assets/includes/db_connect.php';
//require_once '/assets/includes/functions.php';
if(!isset($_SESSION))
	sec_session_start();

$ttmp_cs_permit=$ttmp_ao_permit=$ttmp_csa_permit="";
$user_one=$_SESSION['user_id'];

if(checkpermission($mysqlii,114)==false) $ttmp_csa_permit='alert(\'Restricted Access. Please contact Vervantis!\')';
if(checkpermission($mysqlii,35)==false) $ttmp_cs_permit='alert(\'Restricted Access. Please contact Vervantis!\')';
if(checkpermission($mysqlii,38)==false) $ttmp_ao_permit='alert(\'Restricted Access. Please contact Vervantis!\')';


	//$ttmp_ao_permit=$ttmp_cs_permit='alert(\'Error Occured! No Login Data\')';
	$stmtat = $mysqlii->prepare("SELECT u.accuvio_user,u.accuvio_pass,u.capturis_user,u.capturis_pass,u.capturis_archive_user,u.capturis_archive_pass FROM user u WHERE u.user_id= '".$user_one."' LIMIT 1");
	$stmtat->execute();
	$stmtat->store_result();
	if ($stmtat->num_rows > 0) {
		$stmtat->bind_result($at_accuvio_user,$at_accuvio_pass,$at_capturis_user,$at_capturis_pass,$at_capturis_archive_user,$at_capturis_archive_pass);
		while($stmtat->fetch()){
			if((@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "") or (@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "") or (@trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != "")){

					if(@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "" and $ttmp_ao_permit == ""){$ttmp_ao_permit='window.open(\'assets/ajax/autologin.php?w=fbce0bb98d18aca35b2938c78f52f57b\',\'_blank\')';}
					if(@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "" and $ttmp_cs_permit==""){$ttmp_cs_permit='window.open(\'assets/ajax/autologin.php?w=d1befa03c79ca0b84ecc488dea96bc68\',\'_blank\')';}
					if(@trim($at_capturis_archive_user) != "" and @trim($at_capturis_archive_pass) != "" and $ttmp_csa_permit==""){$ttmp_csa_permit='window.open(\'assets/ajax/autologinarchive.php?w=ytbefa03c79ca0b84ecc488dea96r390\',\'_blank\')';}
			}
		}
	}

	if($ttmp_ao_permit=="") $ttmp_ao_permit='alert(\'Error Occured! No Login Data\')';
	if($ttmp_cs_permit=="") $ttmp_cs_permit='alert(\'Error Occured! No Login Data\')';
	if($ttmp_csa_permit=="") $ttmp_csa_permit='alert(\'Error Occured! No Login Data\')';

   if($nsubvl["title"]=="UBM Software") $temp_onclick=$ttmp_cs_permit;
   elseif($nsubvl["title"]=="UBM Archive") $temp_onclick=$ttmp_csa_permit;
   elseif($nsubvl["title"]=="CSR/ESG Software") $temp_onclick=$ttmp_ao_permit;
   else $temp_onclick="";
					echo '<li class=""><a href="JavaScript:void(0);" onclick="'.$temp_onclick.'">'.@trim($nsubvl["title"]).'</a>';

					echo '</li>';
}else{



					echo '<li class=""><a href="JavaScript:void(0);"   '.(isset($nsubvl["url"])?'onclick="navigateurl(\''.$nsubvl["url"].'\',\''.$nsubvl["title"].'\')"':"").'>'.@trim($nsubvl["title"]).'</a>';

					echo '</li>';
}
				}
				echo '</ul>';
			}elseif(isset($nvl["sub"]) and !is_array($nvl["sub"]) and $nvl["sub"] != ""){
				echo '<ul><li><div class="display-users"></div></li></ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
?>
</div>
<a href="javascript:void(0);" id="js-nav-menu-wrapper-right-btn" class=""><span class="glyphicon glyphicon-chevron-right"></span></a>
<script>
$(document).ready(function(){
	var currurl=window.location.href;
	if(currurl.indexOf("undefined") != -1){
		window.location.href = "https://<?php echo $_SERVER['HTTP_HOST']; ?>/";
	}
	/*if(currurl.indexOf("index.php") != -1){
		window.location.href = "https://<?php echo $_SERVER['HTTP_HOST']; ?>/";
	}*/

//$("#js-nav-menu-wrapper").css("width", document.getElementById("ulmainmenu").offsetWidth);

  if(rlength() > 0){
	$("#js-nav-menu-wrapper-left-btn").show();
	$("#js-nav-menu-wrapper-right-btn").show();
  }else{
	$("#js-nav-menu-wrapper-left-btn").hide();
	$("#js-nav-menu-wrapper-right-btn").hide();
	$("#ulmainmenu").css("margin-left", "0px");
  }

	var lcount=1;
	var rcount=0;
	$("#js-nav-menu-wrapper-right-btn").click(function(){
		if(rcount==1){return false;}
		else{lcount=0;rcount=1;}

		$("#ulmainmenu").animate({
			marginLeft: '-='+rlength()+'px'
		}, 500);
	});
	$("#js-nav-menu-wrapper-left-btn").click(function(){
		if(lcount==1){return false;}
		else{lcount=1;rcount=0;}

		$("#ulmainmenu").animate({
			marginLeft: '+='+rlength()+'px'
		}, 500);
	});


	$(window).resize(function(){
	  if(rlength() > 0){
		$("#js-nav-menu-wrapper-left-btn").show();
		$("#js-nav-menu-wrapper-right-btn").show();
	  }else{
		$("#js-nav-menu-wrapper-left-btn").hide();
		$("#js-nav-menu-wrapper-right-btn").hide();
		$("#ulmainmenu").css("margin-left", "0px");
	  }
	});
	//js-nav-menu-wrapper-left-btn
});
function rlength(){
	var mainmenulenght=document.getElementById("ulmainmenu").scrollWidth;
	var mainmenuclength=document.getElementById("ulmainmenu").clientWidth;
	return mainmenulenght-mainmenuclength;
}
</script>
<?php }// print_r($_COOKIE); echo "11111111111111";
				?>

			</nav>
		</aside>
		<!-- END NAVIGATION -->
