<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();


if(isset($_GET["region"]) and ($_GET["region"]=="Arctic" || $_GET["region"]=="Africa" || $_GET["region"]=="America" || $_GET["region"]=="Antarctica" || $_GET["region"]=="Asia" || $_GET["region"]=="Atlantic" || $_GET["region"]=="Australia" || $_GET["region"]=="Europe" || $_GET["region"]=="Indian" || $_GET["region"]=="Pacific")){
	if($_GET["region"]=="Africa") $region=1;
	else if($_GET["region"]=="America") $region=2;
	else if($_GET["region"]=="Antarctica") $region=4;
	else if($_GET["region"]=="Arctic") $region=8;
	else if($_GET["region"]=="Asia") $region=16;
	else if($_GET["region"]=="Atlantic") $region=32;
	else if($_GET["region"]=="Australia") $region=64;
	else if($_GET["region"]=="Europe") $region=128;
	else if($_GET["region"]=="Indian") $region=256;
	else if($_GET["region"]=="Pacific") $region=512;


	$timezoneIdentifiers = timezone_identifiers_list($region);//print_r($timezoneIdentifiers);
	if(is_array($timezoneIdentifiers) and count($timezoneIdentifiers)){
		$tmp_arr=array();
		foreach($timezoneIdentifiers as $ky=>$vl){
			$tmp_arr[]='<li '.(($_SESSION["timezone_set"] != "" and $_SESSION["timezone_set"]==$vl)?"class='active'":"").'><a href="javascript:void(0);" class= "notranslate" id="'.$vl.'">'.$vl.'</a></li>';
		}
		
		echo 	'<li><a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="notranslate">'.(in_array($_SESSION["timezone_set"],$timezoneIdentifiers)?$_SESSION["timezone_set"]:$timezoneIdentifiers[0]).'</span> <i class="fa fa-angle-down"></i> </a><ul class="dropdown-menu pull-right">'.implode("",$tmp_arr).'</ul></li>';
		$_SESSION["timezone_set"]="";
	}	
}else echo "";

?>