<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["group_id"]))
	die('Access Restricted');

if(isset($_GET['id']) and $_GET['id'] != "")
	$id=$mysqli->real_escape_string($_GET['id']);
else
	die('Wrong parameters provided');

//if($_SESSION["user_id"] != 1) die("Under Construction!");
?>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<meta charset="utf-8">
<style>
.centerit{margin:0 auto;}
.sitetable{
border-spacing:5px !important;
border-collapse:unset !important;
}
.w75{width:75%; }
.w70{width:70%; }
.clearboth{clear:both !important; }
.red{color:red;}
#list-dsireusa{border: 1px solid #ccc !important;}
.strikeit{text-decoration: line-through;}
</style>
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<?php if(!isset($_GET['fromdashboard'])){ ?>
			<script>
				$(".fromthirdpage").css("display", "none");
				$(".fromsecondpage").css("display", "block");
			</script>
			<?php }else{ ?>
			<script>
				$(".fromthirdpage").css("display", "none");
				$(".fromsecondpage").css("display", "none");
				$(".fromdashboard").css("display", "block");
			</script>
			<?php } ?>
		</div>
	</article>
</div>

<!-- row -->
<div class="row siterow">

	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php
		$address=$incentivetypearr=$citiesarr=$renewablearr=$applicablesectorarr=array();
    //$id=3004;
    $programename=$implementingsector=$pcategory=$pgstate=$incentivetype=$websiteurl=$cities=$renewable=$applicablesector=$administrator=$fundingsource="";
		$website_up=0;
	if ($stmt = $mysqli->prepare('SELECT pg.id,pg.name,pg.websiteurl,pg.administrator,pg.fundingsource,summary,updated_ts,created_ts,website_up FROM dsireusa.`program` pg WHERE pg.id='.$id.' LIMIT 1')) {
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($pgid,$programename,$websiteurl,$administrator,$fundingsource,$summary,$lastupdated,$createddate,$website_up);
			$stmt->fetch();

        if ($stmt1 = $mysqli->prepare('SELECT isec.name FROM dsireusa.`program` pg,dsireusa.implementing_sector isec WHERE pg.implementing_sector_id=isec.id and pg.id='.$id)) {
              $stmt1->execute();
              $stmt1->store_result();
              if ($stmt1->num_rows > 0) {
      			$stmt1->bind_result($implementingsector);
      			$stmt1->fetch();
          }
        }

        if ($stmt2 = $mysqli->prepare('SELECT pc.name FROM dsireusa.`program` pg,dsireusa.program_category pc WHERE pg.program_category_id=pc.id and pg.id='.$id)) {
              $stmt2->execute();
              $stmt2->store_result();
              if ($stmt2->num_rows > 0) {
      			$stmt2->bind_result($pcategory);
      			$stmt2->fetch();
          }
        }

        if ($stmt3 = $mysqli->prepare('SELECT s.abbreviation FROM dsireusa.`program` pg,dsireusa.state s WHERE  pg.state_id=s.id and pg.id='.$id)) {
              $stmt3->execute();
              $stmt3->store_result();
              if ($stmt3->num_rows > 0) {
      			$stmt3->bind_result($pgstate);
      			$stmt3->fetch();
          }
        }

        if ($stmt4 = $mysqli->prepare('SELECT pt.name FROM dsireusa.`program` pg,dsireusa.program_type pt WHERE pg.program_type_id=pt.id and pg.id='.$id)) {
              $stmt4->execute();
              $stmt4->store_result();
              if ($stmt4->num_rows > 0) {
      			$stmt4->bind_result($incentivetype);
      			$stmt4->fetch();
          }
        }

        if ($stmt5 = $mysqli->prepare('SELECT c.name FROM dsireusa.program_city prc,dsireusa.city c WHERE prc.city_id=c.id and  prc.program_id='.$id)) {
          $stmt5->execute();
          $stmt5->store_result();
          if ($stmt5->num_rows > 0) {
      			$stmt5->bind_result($cities);
            while($stmt5->fetch()){
              $citiesarr[]=$cities;
            }
            $cities=implode(", ",$citiesarr);
          }
        }

        if ($stmt6 = $mysqli->prepare('SELECT t.name FROM dsireusa.`program_technology` pt ,dsireusa.technology t WHERE pt.technology_id=t.id and t.active=1 AND pt.`program_id` ='.$id)) {
          $stmt6->execute();
          $stmt6->store_result();
          if ($stmt6->num_rows > 0) {
      			$stmt6->bind_result($renewable);
            while($stmt6->fetch()){
              $renewablearr[]=$renewable;
            }
            $renewable=implode(", ",$renewablearr);
          }
        }

        if ($stmt7 = $mysqli->prepare('SELECT s.name FROM dsireusa.`program_sector` ps,dsireusa.`sector` s WHERE ps.sector_id=s.id AND ps.`program_id` ='.$id)) {
          $stmt7->execute();
          $stmt7->store_result();
          if ($stmt7->num_rows > 0) {
      			$stmt7->bind_result($applicablesector);
            while($stmt7->fetch()){
              $applicablesectorarr[]=$applicablesector;
            }
            $applicablesector=implode(", ",$applicablesectorarr);
          }
        }



				?>
        <style>
        .pgfull-width {
          width: 100%;
          background: #FFA07A;
        }
        .pgwrap {
          width: 80%;
          max-width: 24em;
          margin: 0 auto;
          padding: 0.25em 0.625em;
          color:white;
          font-weight:normal;
          text-align:center;
        }
        </style>

        <style>
          .pgtable{
            width:66%;
            margin:0 auto !important;
          }
          .pgheader{
            text-align: center;
            font-size: 27px;
            color: #333;
            font-weight: 400;
          }
          .pgheader1{
            text-align: center;
            font-size: 30px;
            font-weight: 400;
          }
          .pginnerheader{
            background-color:#404040;
          }
          .pginnerheader1{
            background-color:#333;
          }
          .pginnerheader,.pginnerheader1{
            color:#fff;
            border-bottom: 1px solid #ccc;
            height:42px;
          }
          .textmiddle{
            vertical-align: middle;
            /*line-height: 3;*/
            padding-left: 16px;
            max-width:547px;
            overflow: scroll;
            word-wrap: break-word;
          }
          .textcenter{text-align: center;}
          .bgcolorlightred{background-color:#FFA07A;color:#fff; }
          .trborder{
            border:1px solid #ccc;
          }
          .bgcolorshade{background-color:#f2f2f2}
          .contactcss1{width:50%;float:left;padding-left: 20px;border:3px solid #fff;}
          .contactcss2{width:50%;float:right;padding-left:20px;border:3px solid #fff;}
          .nocentertext{text-align:justify !important;}
          .bgcolorlight{background-color:#f0f0f5;}
          .contactdetails{width:100%;}
          .contactcase{background-color:#F8F8F8;padding-left: 18px;padding-bottom:18px;}
          .pgbox{
            width: 100% !important;
            margin: 0 auto;
          }
          .bodyminheight,.pgbox .widget-body body{min-height: auto !important;}
          .txtcenter{text-align: center;}
          .w100{width:100%;}
          .w50{width:50%;}
          .vtop{vertical-align: top;}
          .f22{font-size:22px;}
          .p2{padding:2px;}
					.wordbreak{word-break: break-all;}
					.incentivesclose{cursor:pointer;}
					#dsireusadetails a{word-break: break-word;}
        </style>

        <div class="jarviswidget jarviswidget-color-blueDark pgbox" id="wid-id-2" data-widget-editbutton="false">
          <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Incentive Details </h2>
						<span class="widget-icon" style="float: right;margin-right: -2px;"> <i class="fa fa fa-close incentivesclose"></i> </span>
          </header>
          <div>
            <div class="jarviswidget-editbox"></div>
            <div class="widget-body no-padding">
              <table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
                <thead></thead>
                <tbody class="bodyminheight">
                    <tr><th width="300px">Program ID:</th><td><?php echo $pgid; ?></td></tr>
                    <tr><th width="300px">Name:</th><td><?php echo $programename; ?></td></tr>
                    <tr><th width="300px">Created Date:</th><td><?php echo @date("M d, Y", @strtotime($createddate)); ?></td></tr>
                    <tr><th width="300px">Updated Date:</th><td><?php echo @date("M d, Y", @strtotime($lastupdated)); ?></td></tr>
                    <tr><th colspan="2">&nbsp;</th></tr>
                    <tr><th colspan="2" class="txtcenter f22">Program Overview</th></tr>
                    <tr><th width="300px">Implementing Sector:</th><td><?php echo $implementingsector; ?></td></tr>
                    <tr><th width="300px">Category:</th><td><?php echo $pcategory; ?></td></tr>
                    <tr><th width="300px">State:</th><td><?php echo $pgstate; ?></td></tr>
                    <tr><th width="300px">Incentive Type:</th><td><?php echo $incentivetype; ?></td></tr>
                    <tr><th width="300px">Web Site:</th><td class="wordbreak"><?php if(!empty($websiteurl)){ if($website_up==1){ ?><a href="<?php echo $websiteurl; ?>" target="_blank"><?php echo $websiteurl; ?></a><?php }else{ echo '<i class="strikeit">'.$websiteurl.'</i>'; } } ?></td></tr>
                <?php if(!empty($administrator)){ ?>
                    <tr><th width="300px">Administrator:</th><td><?php echo $administrator; ?></td></tr>
                <?php } if(!empty($fundingsource)){ ?>
                    <tr><th width="300px">Funding Source:</th><td><?php echo $fundingsource; ?></td></tr>
                <?php } ?>
                    <tr><th width="300px">Cities:</th><td><?php echo $cities; ?></td></tr>
                <?php if(1==2){ ?>
                    <tr><th width="300px">Eligible Renewable/Other Technologies:</th><td><?php echo "Pending work: Find Mapping"; ?></td></tr>
                <?php } ?>
                    <tr><th width="300px">Applicable Sectors:</th><td><?php echo $applicablesector; ?></td></tr>
                    <?php
                    if ($stmt8 = $mysqli->prepare('SELECT pd.label,pd.value FROM dsireusa.`program_detail` pd WHERE pd.label != "" AND pd.value !="" AND pd.`program_id` ='.$id)) {
                      $stmt8->execute();
                      $stmt8->store_result();
                      if ($stmt8->num_rows > 0) {
                        $stmt8->bind_result($pdvalue,$pdname);
                        while($stmt8->fetch()){
                    ?>
                        <tr><th width="300px"><?php echo $pdvalue; ?>:</th><td><?php echo $pdname; ?></td></tr>
                    <?php
                        }
                      }
                    }
                    ?>


                    <?php
                    $parametersarr=$sparameterarr=$sectorsarr=$techarr=$setidarr=array();
                    if ($stmt9 = $mysqli->prepare('SELECT pr.parameter_set_id,pr.source,pr.qualifier,pr.amount,pr.units,t.name FROM dsireusa.`program` pg, dsireusa.parameter pr,dsireusa.parameter_set ps,dsireusa.`parameter_set_technology` pst, dsireusa.technology t where pr.parameter_set_id=ps.id and ps.program_id=pg.id and pst.technology_id=t.id and pst.set_id=pr.parameter_set_id and pg.id='.$id.'  group by pr.id')) {
                      $stmt9->execute();
                      $stmt9->store_result();
                      if ($stmt9->num_rows > 0) {
                        $ctcss=0;
                        $stmt9->bind_result($ipsetid,$isource,$iqualifier,$iamount,$iunits,$iname);

                        while($stmt9->fetch()){
                          $setidarr[]=$ipsetid;
                          $parametersarr[$ipsetid][]=array("source"=>$isource,"qualifier"=>$iqualifier,"amount"=>$iamount,"units"=>$iunits,"techname"=>$iname);
                        }
                      }
                    }



                    if(count($setidarr)){
                      if ($stmt9 = $mysqli->prepare('SELECT pst.set_id,t.name FROM dsireusa.`parameter_set_technology` pst,dsireusa.technology t where pst.set_id in ('.implode(",",$setidarr).') and pst.technology_id=t.id')) {
                        $stmt9->execute();
                        $stmt9->store_result();
                        if ($stmt9->num_rows > 0) {
                          $ctcss=0;
                          $stmt9->bind_result($ipsetid,$iname);

                          while($stmt9->fetch()){
                                $techarr[$ipsetid][]=$iname;
                          }
                        }
                      }
                    }
                        if ($stmt9 = $mysqli->prepare('SELECT DISTINCT s.name FROM dsireusa.`program` pg, dsireusa.program_sector pc, dsireusa.sector s where pc.sector_id=s.id and pc.`program_id`=pg.id and pg.id='.$id)) {
                          $stmt9->execute();
                          $stmt9->store_result();
                          if ($stmt9->num_rows > 0) {
                            $ctcss=0;
                            $stmt9->bind_result($iname);
                            while($stmt9->fetch()){
                                  $sectorsarr[]=$iname;
                            }
                          }
                        }
/*
                        if ($stmt99 = $mysqli->prepare('SELECT DISTINCT s.name FROM dsireusa.`program` pg, dsireusa.program_sector pc, dsireusa.sector s where pc.sector_id=s.id and pc.`program_id`=pg.id and pg.id='.$id)) {
                          $stmt99->execute();
                          $stmt99->store_result();
                          if ($stmt99->num_rows > 0) {;
                            $stmt99->bind_result($iname);
                            while($stmt99->fetch()){
                                  $techarr[]=array("name"=>$iname);
                            }
                          }
                        }
*/



                          if(count($parametersarr)){ ?>
                            <tr><th colspan="2">&nbsp;</th></tr>
                            <tr><th colspan="2" class="txtcenter f22">Incentives</th></tr>
                          <?php
                            foreach($parametersarr as $kyy=>$vll){
                                if(count($vll)){
                                    if(isset($techarr[$kyy])){
                                  ?>
                              <tr><th width="300px">Technologies:</th><td><?php echo @implode(", ",$techarr[$kyy]); ?></td></tr>
                                <?php
                                    }
                                }

                                if(count($sectorsarr)){ ?>
                              <tr><th width="300px">Sectors:</th><td><?php echo @implode(", ",$sectorsarr); ?></td></tr>
                                <?php
                                }
                                if(count($vll)){ ?>
                                  <tr><th width="300px">Parameters:</th><td><?php
                                foreach($vll as $ky=>$vl){
                                    echo "The ".$vl["source"];
                                    if(!empty($vl["qualifier"])){
                                      echo " has a";
                                      if($vl["qualifier"]=="max") echo " maximum";
                                      elseif($vl["qualifier"]=="min") echo " minimum";
                                    }else echo " is";

                                    if(!empty($vl["qualifier"])) echo " of";

                                    if(!empty($vl["units"]) and $vl["units"]=="$") echo " ".$vl["units"];
                                    if(!empty($vl["amount"])){
                                      if(!empty($vl["units"]) and $vl["units"]!="$") echo " ";
                                      echo round($vl["amount"],2);
                                    }

                                    if(!empty($vl["units"]) and $vl["units"]!="$") echo " ".$vl["units"];

                                    if(count($vll) != $ky+1) echo ", ";
                                }

                              ?></td></tr>
                                <?php
                                }
                                if(count($parametersarr) != $kyy+1){
                              ?>
                                <tr><th colspan="2"></th></tr>
                              <?php
                            }
                          }
                        }
                          /*
                              foreach($tmparr as $ky=>$vl){
                                if(!empty($vl["Name"])){ ?>
                                      <tr><th width="300px">Name:</th><td><?php echo $vl["Name"]; ?></td></tr>
                                <?php }
                                if(!empty($vl["Effective Date"])){ ?>
                                      <tr><th width="300px">Effective Date:</th><td><?php echo @date("M d, Y", @strtotime($vl["Effective Date"])); ?></td></tr>
                                <?php }
                                if(!empty($vl["Website"])){ ?>
                                      <tr><th width="300px">Website:</th><td><a href="<?php echo $vl["Website"]; ?>" target="_blank"><?php echo $vl["Website"]; ?></a></td></tr>
                                <?php }
                                  if(count($tmparr) != $ky+1){
                                ?>
                                  <tr><th colspan="2"></th></tr>
                                <?php
                                }
                              }
                              */
                    ?>




                    <tr><th colspan="2">&nbsp;</th></tr>
                    <tr><th colspan="2" class="txtcenter f22">Summary</th></tr>
                    <tr><td colspan="2"><?php echo strip_tags(preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', @trim($summary)),"<br><p><h1><h2><h3><h4><h5><div><img><i><table><tbody><tr><th><td><thead><span><ol><li><ul><dl><dt><b><strong><textarea><hr><strong><pre><u>"); ?></td></tr>

        <?php
        if ($stmt9 = $mysqli->prepare('SELECT a.code,a.effectivetext,a.website,a.enacted,a.website_up FROM dsireusa.`authority` a WHERE a.program_id = '.$id)) {
          $stmt9->execute();
          $stmt9->store_result();
          if ($stmt9->num_rows > 0) {
            $ctcss=0;
            $stmt9->bind_result($acode,$aeffectivetext,$awebsite,$aenacted,$awebsiteup);
            $tmparr=array();

            while($stmt9->fetch()){
              if(empty($acode) and empty($aeffectivetext) and empty($awebsite)){}else{
                  $tmparr[]=array("Name"=>@str_replace("Â"," ",$acode),"Effective Date"=>$aeffectivetext,"Website"=>$awebsite,"Enacted"=>$aenacted);
              }
            }
              if(count($tmparr)){ ?>
                <tr><th colspan="2">&nbsp;</th></tr>
                <tr><th colspan="2" class="txtcenter f22">Authorities</th></tr>
              <?php
                  foreach($tmparr as $ky=>$vl){
                    if(!empty($vl["Name"])){ ?>
                          <tr><th width="300px">Name:</th><td><?php echo $vl["Name"]; ?></td></tr>
                    <?php }
										if(!empty($vl["Enacted"])){ ?>
                          <tr><th width="300px">Date Enacted:</th><td><?php echo @date("M d, Y", @strtotime($vl["Enacted"])); ?></td></tr>
                    <?php }
                    if(!empty($vl["Effective Date"])){ ?>
                          <tr><th width="300px">Effective Date:</th><td><?php echo @date("M d, Y", @strtotime($vl["Effective Date"])); ?></td></tr>
                    <?php }
                    if(!empty($vl["Website"])){ ?>
                          <tr><th width="300px">Website:</th><td><?php if($awebsiteup==1){ ?><a href="<?php echo $vl["Website"]; ?>" target="_blank"><?php echo $vl["Website"]; ?></a><?php }else{ echo '<i class="strikeit">'.$vl["Website"].'</i>'; } ?></td></tr>
                    <?php }
                      if(count($tmparr) != $ky+1){
                    ?>
                      <tr><th colspan="2"></th></tr>
                    <?php
                    }
                  }
              }
          }
        }
        ?>
                      <tr><th colspan="2">&nbsp;</th></tr>
                      <tr><td colspan="2">
                        <table class="w100">
                          <tr><td class="w50 vtop p2">
                              <table class="w100 table table-striped table-bordered table-hover">
                                <tr><th colspan="2" class="txtcenter f22">Contact</th></tr>
                          <?php
                          if ($stmt10 = $mysqli->prepare('SELECT c.first_name,c.last_name,c.organization_name,c.address,c.city,c.zip,c.state_id,c.phone,c.email FROM dsireusa.`program_contact` pc,dsireusa.`contact` c WHERE pc.contact_id=c.id and pc.webvisible=1 and c.web_visible_default=1 AND pc.`program_id`='.$id)) {
                            $stmt10->execute();
                            $stmt10->store_result();
                            if ($stmt10->num_rows > 0) {
                              $stmt10->bind_result($pcfirstname,$pclastname,$pcorganization,$pcaddress,$pccity,$pczip,$pcstate,$pcphone,$pcemail);
                              $stmt10->fetch();

                              if(!empty($pcstate)){
                                if ($stmt11 = $mysqli->prepare('SELECT `abbreviation` FROM dsireusa.`state` WHERE id='.$pcstate)) {
                                  $stmt11->execute();
                                  $stmt11->store_result();
                                  if ($stmt11->num_rows > 0) {
                                    $stmt11->bind_result($pcsstate);
                                    $stmt11->fetch();
                                    if(!empty($pcsstate)) $pcstate=$pcsstate;
                                  }
                                }
                              }
                              $tmparr=array();
                              if(!empty($pccity)) $tmparr[]=$pccity;
                              if(!empty($pcstate) or !empty($pczip)) $tmparr[]=$pcstate." ".$pczip;
                              if(!empty($pcfirstname) or !empty($pclastname)){ ?>
                               <tr><th>Name:</th><td><?php echo $pcfirstname." ".$pclastname; ?></td></tr>
                             <?php } if(!empty($pcorganization)){ ?>
                               <tr><th>Organization:</th><td><?php echo $pcorganization; ?></td></tr>
                             <?php } if(!empty($pcaddress) or count($tmparr)){ ?>
                               <tr><th>Address:</th><td><?php echo $pcaddress; ?><br /><?php if(count($tmparr)) echo implode(", ",$tmparr); ?></td></tr>
                             <?php } if(!empty($pcphone)){ ?>
                               <tr><th>Phone:</th><td><?php echo $pcphone; ?></td></tr>
                             <?php } if(!empty($pcemail)){ ?>
                               <tr><th>Email:</th><td><?php echo $pcemail; ?></td></tr>
                          <?php }
                        }else{
                                echo "<tr><td colspan='2'>N/A</td></tr>";
                        }
                      }else{
                              echo "<tr><td colspan='2'>N/A</td></tr>";
                      }

                          ?>
                              </table>
                          </td>
                          <td class="w50 vtop p2">
                            <table class="w100 table table-striped table-bordered table-hover">
                              <tr><th colspan="2" class="txtcenter f22">Memos</th></tr>
                  <?php
                    if ($stmt12 = $mysqli->prepare('SELECT sm.added,sm.memo,c.first_name,c.last_name  FROM dsireusa.`subscription_memo` sm, dsireusa.contact c WHERE sm.added_by_user=c.id and c.web_visible_default=1 and sm.`program_id` ='.$id)) {
                      $stmt12->execute();
                      $stmt12->store_result();
                      if ($stmt12->num_rows > 0) {
                        $stmt12->bind_result($madded,$mmemo,$mfirstname,$mlastname);
                        while($stmt12->fetch()){
                    ?>
                              <tr><th>Name: </th><td><?php echo $mfirstname.' '.$mlastname; ?></td></tr>
                              <tr><th>Date: </th><td><?php echo @date("M d, Y", @strtotime($madded)); ?></td></tr>
                              <tr><th>Memo: </th><td><?php echo str_replace("Â","",strip_tags($mmemo,"<br><p><h1><h2><h3><h4><h5><div><img><i><table><tbody><tr><th><td><thead><span><ol><li><ul><dl><dt><b><strong><textarea><hr><strong><pre><u>")); ?></td></tr>
                              <tr><th colspan="2"></th></tr>
                              <?php if(1==2){ ?>
                              <tr><td><b><?php echo @date("m/d/y", @strtotime($madded)); ?></b> by <?php echo $mfirstname.' '.$mlastname; ?></td></tr>
                              <tr><td><?php echo $mmemo; ?></td></tr>
                            <?php } ?>
                    <?php }
                      }else{
                              echo "<tr><td colspan='2'>N/A</td></tr>";
                      }
                  }
                ?>
                            </table>
                          </td></tr>
                        </table>
                      </td></tr>
</tbody>
</table>
<?php
		}else
			die('Wrong parameters provided');
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();
	}//else
		//die('Error Occured! Please try after sometime.');
	if(isset($_GET['noback'])) $tmpurl="&noback=true"; else $tmpurl="";
		?>
<!--			</div>
			</div>
		</div>-->
	</article>
</div>

<?php
function format_phone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    elseif(strlen($phone) == 1)
		return "";
	else
        return $phone;
}
?>
<script>
/*$(window).off("popstate");
history.pushState(null, null, '');
window.addEventListener('popstate', navback);
window.scrollTo(0,0);*/

function navback(){
	history.pushState(null, null, '');
	if($('.fromsecondpage').css('display') == 'block'){
		move_back_dsireusa()();
	}
}
/*$(window).on('popstate', function (e) {alert("2456");
    var state = e.originalEvent.state;
    if (state !== null) {
        //load content with ajax
    }
});
$(window).on('pushstate', function (e) {alert("2456");
    var state = e.originalEvent.state;
    if (state !== null) {
        //load content with ajax
    }
});*/
$(document).off("click",".incentivesclose");
$(document).on("click",".incentivesclose",function() {
	parent.$('#dsireusadetails').html('');
});
</script>
