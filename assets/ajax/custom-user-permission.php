<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();
$user_one = $_SESSION["user_id"];

if(($_SESSION["group_id"]==5 or $_SESSION["group_id"]==1) and isset($_GET["userpermission"])){
	if(isset($_GET['userid']) and $_GET['userid'] != "" and $_GET['userid'] > 0)
		$_userid=$_GET['userid'];
	else die("Incorrect Parameters Provided!");

	$jsondata=$user_jsondata="";
	$_ppdisabled_menu=$_pdisabled_menu_a_arr=$_pdisabled_menu_c_arr=array();
		if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){
			$sql='SELECT ui.custom_interface,u.usergroups_id,u.company_id FROM users_interface ui, user u where u.user_id="'.$_userid.'" LIMIT 1';

//SELECT ui.custom_interface,u.usergroups_id,u.company_id FROM users_interface ui, user u where u.id="'.$_userid.'" LIMIT 1';

		}else{ 
			$sql='SELECT ui.custom_interface,u.usergroups_id,u.company_id FROM users_interface ui, user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and ui.user_id=u.user_id and ui.user_id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM user usp WHERE usp.user_id= "'.$user_one.'") LIMIT 1';

//			$sql='SELECT ui.custom_interface,u.usergroups_id,u.company_id FROM users_interface ui, user u where (u.usergroups_id = 5 OR u.usergroups_id = 3) and ui.user_id=u.id and ui.user_id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM user usp WHERE usp.id= "'.$user_one.'") LIMIT 1';

		}
		if ($stmt = $mysqli->prepare($sql)) { 
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($user_jsondata,$usergroups_id,$company_id);
				$stmt->fetch();
			}else{
				if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){
					$sql='SELECT ug.interface,u.usergroups_id,u.company_id FROM user u, usergroups ug where u.usergroups_id = ug.id and u.user_id="'.$_userid.'" LIMIT 1';

//SELECT ug.interface,u.usergroups_id,u.company_id FROM user u, usergroups ug where u.usergroups_id = ug.id and u.id="'.$_userid.'" LIMIT 1';

				}else{ 
					$sql='SELECT ug.interface,u.usergroups_id,u.company_id FROM user u, usergroups ug where u.usergroups_id = ug.id and (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.user_id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM user usp WHERE usp.user_id= "'.$user_one.'") LIMIT 1';

//'SELECT ug.interface,u.usergroups_id,u.company_id FROM user u, usergroups ug where u.usergroups_id = ug.id and (u.usergroups_id = 5 OR u.usergroups_id = 3) and u.id="'.$_userid.'" and u.company_id=(SELECT usp.company_id FROM user usp WHERE usp.id= "'.$user_one.'") LIMIT 1';

				}
				if ($stmts = $mysqli->prepare($sql)) { 
					$stmts->execute();
					$stmts->store_result();
					if ($stmts->num_rows > 0) {
						$stmts->bind_result($user_jsondata,$usergroups_id,$company_id);
						$stmts->fetch();
					}else
						exit(false);
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();		
				}//else
					//exit(false);
			}
		}else{
			header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
			exit();		
		}//else
			//exit(false);
//////////////////////////////		
//////////////////////////////		
//////////////////////////////		
///////ADDED FIX LATER////////
//////////////////////////////		
//////////////////////////////		
//////////////////////////////		
$usergroups_id=$_SESSION["group_id"];
//////////////////////////////



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
							}
						}
					}else
						die('No Menu List Available');
				}else
					die('Error Occured');

				if($usergroups_id==1 || $usergroups_id==2){// || $usergroups_id==4
				
				////////////////////////////////////////////
				///////////////////FIXED LATER//////////////
				////////////////////////////////////////////
				////////////////////////////////////////////
					//$sql='SELECT id,disabled_menu_by_clientadmin,disabled_menu_by_admin,disabled_by FROM permission where user_id="'.$_userid.'" LIMIT 1';
					$sql='SELECT id,disabled_menu_by_clientadmin,disabled_menu_by_admin,disabled_by FROM permission where company_id="'.$company_id.'" LIMIT 1';
					
				////////////////////////////////////////////
				////////////////////////////////////////////
				////////////////////////////////////////////
				////////////////////////////////////////////
				}elseif($usergroups_id==3 || $usergroups_id==5){
					$sql='SELECT id,disabled_menu_by_clientadmin,disabled_menu_by_admin,disabled_by FROM permission where company_id="'.$company_id.'" LIMIT 1';
				}else die("Error Occured. Please try after sometime!");
				
				
				if ($stmtp = $mysqli->prepare($sql)) { 
					$stmtp->execute();
					$stmtp->store_result();
					if ($stmtp->num_rows > 0) {
						$stmtp->bind_result($_pid,$_pdisabled_menu_c,$_pdisabled_menu_a,$_pdisabled_by);
					   $stmtp->fetch();
					   /*if($_pdisabled_menu_c != "" and $_pdisabled_menu_a != ""){
							$_disabled_menus=$_pdisabled_menu_c.",".$_pdisabled_menu_a;
							$_ppdisabled_menu=@array_unique(@explode(",",$_disabled_menus));
					   }else if($_pdisabled_menu_c != "" and $_pdisabled_menu_a == ""){
						   $_ppdisabled_menu=@explode(",",$_pdisabled_menu_c);
					   }else if($_pdisabled_menu_c == "" and $_pdisabled_menu_a != "")
						   $_ppdisabled_menu=@explode(",",$_pdisabled_menu_a);*/
					   
					   $_pdisabled_menu_c_arr=@explode(",",$_pdisabled_menu_c);
					   $_pdisabled_menu_a_arr=@explode(",",$_pdisabled_menu_a);
					   
					  // print_r($_pdisabled_menu_c_arr);
					  // print_r($_pdisabled_menu_a_arr);
					  // echo in_array(48,$_pdisabled_menu_c_arr);
					}
				}else{
					header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
					exit();		
				}//else
					//die('Error Occured');
			}else
				die('Error Occured');
		}else
			die('Wrong parameters provided');
		
		$_data = json_decode($user_jsondata);
		$_jdata=json_decode($user_jsondata,true);
		if (is_null($_data)) {
		   die("Error Occured");
		}
		//print("<pre>".print_r(json_decode($user_jsondata,true),true)."</pre>");
		function builddhtml($_jdata,$count=0){
			$html="";
			foreach($_jdata as $ky=>$vl){
				if(array_key_exists("children",$vl)){
					$html=$html."<tr><td><b>".$vl["title"]."</b></td></tr>".builddhtml($vl["children"],1);
				}else{
					$html=$html."<tr><td><input type='checkbox' name='p".$vl["id"]."' value='".$vl["id"]."'> ".($count > 0?$vl["title"]:"<b>".$vl["title"]."</b>")."</td></tr>";
				}
			}
			return $html;
		}
?>	
<!--<section id="widget-grids" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<table> <?php //echo builddhtml($_jdata); ?> </table> 
		</article>
	</div>
</section>-->
<style>
.redc{color: red !important;
    font-style: italic;
    padding-left: 5px;
    font-size: 12px;}
.htitl{text-align:center;border-bottom:1px solid black;}
#wid-id-00 #pform label.toggle{margin-top:	-12px !important;}
#wid-id-00 #pform table{
margin-left: 26px;
width: 75%;}
#wid-id-00 #pform table tr{border-bottom: 5px solid transparent;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-00" data-widget-fullscreenbutton="false" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2> Edit Permission </h2>					
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
					<form action="" class="smart-form" id="pform">
										<!--<label class="toggle">
											<input type="checkbox" name="checkbox-toggle" checked="checked">
											<i data-swchon-text="ON" data-swchoff-text="OFF"></i></label>-->
			<?php if($_SESSION["group_id"]==1){?>
				<p class="htitl">Please Note: checkbox 1 is User Permission and 2 is Admin Permission.</p>
			<?php } ?>
	<div class="row">
	<?php if(1==2){ ?>
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
			  <tr>
				 <td>Dashboard</td>
				 <td>
				 <?php if($_SESSION["group_id"]==1){?>
					<input type="checkbox" name="pu" value="1" <?php if(!in_array(1,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="1" <?php if(!in_array(1,$_pdisabled_menu_a_arr)) echo 'checked';?>>2
				 <?php }elseif(!in_array(1,$_pdisabled_menu_a_arr)){ ?>
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" <?php if(!in_array(1,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
				 <?php }else echo "<span class='redc'>(No Access)</span></td>"; ?>
				 </td>
			  </tr>
		</table>
		</article>
	<?php } ?>
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<table cellpadding="5">
			  <tr>
				 <td colspan="2"><b>Market Resources</b></td>
			  </tr>
			  <tr>
				<td>Futures Pricing</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="48" <?php if(!in_array(48,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="48" <?php if(!in_array(48,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(48,$_pdisabled_menu_a_arr)){ ?>
			<td>
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="48" class="pinput" <?php if(!in_array(48,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>			
			</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Locational Marginal Pricing</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="59" <?php if(!in_array(59,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="59" <?php if(!in_array(59,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(59,$_pdisabled_menu_a_arr)){ ?>
			<td>					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="59" class="pinput" <?php if(!in_array(59,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label></td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Live Weather</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="52" <?php if(!in_array(52,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="52" <?php if(!in_array(52,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(52,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="52" class="pinput" <?php if(!in_array(52,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Streaming News</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="60" <?php if(!in_array(60,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="60" <?php if(!in_array(60,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(60,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="60" class="pinput" <?php if(!in_array(60,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Verve Energy Blog</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="2" <?php if(!in_array(2,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="2" <?php if(!in_array(2,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(2,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="2" class="pinput" <?php if(!in_array(2,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Market Commentary</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="3" <?php if(!in_array(3,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="3" <?php if(!in_array(3,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(3,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="3" class="pinput" <?php if(!in_array(3,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<table cellpadding="5">
		  <tr>
			 <td colspan="2"><b>Energy Procurement</b></td>
		  </tr>
		  <tr>
			<td>Direct Access Information</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="31" <?php if(!in_array(31,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="31" <?php if(!in_array(31,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(31,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="31" class="pinput" <?php if(!in_array(31,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Energy Procurement</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="42" <?php if(!in_array(42,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="42" <?php if(!in_array(42,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(42,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="42" class="pinput" <?php if(!in_array(42,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Dynamic Risk Management</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="46" <?php if(!in_array(46,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="46" <?php if(!in_array(46,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(46,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="46" class="pinput" <?php if(!in_array(46,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Master Supply Agreements</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="54" <?php if(!in_array(54,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="54" <?php if(!in_array(54,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(54,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="54" class="pinput" <?php if(!in_array(54,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Supplier Contracts</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="55" <?php if(!in_array(55,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="55" <?php if(!in_array(55,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(55,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="55" class="pinput" <?php if(!in_array(55,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  <tr>
			<td>Utility Requirements</td>
<?php if($_SESSION["group_id"]==1){?>
			 <td><input type="checkbox" name="pu" value="56" <?php if(!in_array(56,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="56" <?php if(!in_array(56,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(56,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="56" class="pinput" <?php if(!in_array(56,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
		  </tr>
		  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<table cellpadding="5">
			  <tr>
				 <td colspan="2"><b>Rate Optimization</b></td>
			  </tr>
			  <tr>
				<td>Regulated Information</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="30" <?php if(!in_array(30,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="30" <?php if(!in_array(30,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(30,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="30" class="pinput" <?php if(!in_array(30,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Utility Rate Reports</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="41" <?php if(!in_array(41,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="41" <?php if(!in_array(41,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(41,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="41" class="pinput" <?php if(!in_array(41,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  <tr>
				<td>Utility Rate Change Requests</td>
<?php if($_SESSION["group_id"]==1){?>
				 <td><input type="checkbox" name="pu" value="61" <?php if(!in_array(61,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="61" <?php if(!in_array(61,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(61,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="61" class="pinput" <?php if(!in_array(61,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
			  </tr>
			  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Account Admin</b></td>
      </tr>
      <tr>
		<td>Start New Service</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="49" <?php if(!in_array(49,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="49" <?php if(!in_array(49,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(49,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="49" class="pinput" <?php if(!in_array(49,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Stop Service</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="50" <?php if(!in_array(50,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="50" <?php if(!in_array(50,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(50,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="50" class="pinput" <?php if(!in_array(50,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Start/Stop Status</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="51" <?php if(!in_array(51,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="51" <?php if(!in_array(51,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(51,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="51" class="pinput" <?php if(!in_array(51,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Correspondence</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="58" <?php if(!in_array(58,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="58" <?php if(!in_array(58,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(58,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="58" class="pinput" <?php if(!in_array(58,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Energy Accounting</b></td>
      </tr>
      <tr>
		<td>UBM Software</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="35" <?php if(!in_array(35,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="35" <?php if(!in_array(35,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(35,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="35" class="pinput" <?php if(!in_array(35,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Utility Budgets</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="37" <?php if(!in_array(37,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="37" <?php if(!in_array(37,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(37,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="37" class="pinput" <?php if(!in_array(37,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Invoice Validation</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="36" <?php if(!in_array(36,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="36" <?php if(!in_array(36,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(36,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="36" class="pinput" <?php if(!in_array(36,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Exception Reports</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="62" <?php if(!in_array(62,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="62" <?php if(!in_array(62,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(62,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="62" class="pinput" <?php if(!in_array(62,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Resolved Exceptions</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="63" <?php if(!in_array(63,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="63" <?php if(!in_array(63,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(63,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="63" class="pinput" <?php if(!in_array(63,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Site &amp; Account Changes</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="64" <?php if(!in_array(64,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="64" <?php if(!in_array(64,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(64,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="64" class="pinput" <?php if(!in_array(64,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Data Management</b></td>
      </tr>
      <tr>
		<td>Data Analysis</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="33" <?php if(!in_array(33,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="33" <?php if(!in_array(33,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(33,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="33" class="pinput" <?php if(!in_array(33,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Consumption Reports</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="65" <?php if(!in_array(65,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="65" <?php if(!in_array(65,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(65,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="65" class="pinput" <?php if(!in_array(65,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Custom Reports</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="34" <?php if(!in_array(34,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="34" <?php if(!in_array(34,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(34,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="34" class="pinput" <?php if(!in_array(34,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Sustainability</b></td>
      </tr>
      <tr>
		<td>CSR/ESG Software</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="38" <?php if(!in_array(38,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="38" <?php if(!in_array(38,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(38,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="38" class="pinput" <?php if(!in_array(38,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Sustainability Reports</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="39" <?php if(!in_array(39,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="39" <?php if(!in_array(39,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(39,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="39" class="pinput" <?php if(!in_array(39,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Corporate Reports</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="74" <?php if(!in_array(74,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="74" <?php if(!in_array(74,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(74,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="74" class="pinput" <?php if(!in_array(74,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Surveys</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="75" <?php if(!in_array(75,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="75" <?php if(!in_array(75,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(75,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="75" class="pinput" <?php if(!in_array(75,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Projects</b></td>
      </tr>
      <tr>
		<td>Distributed Generation</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="40" <?php if(!in_array(40,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="40" <?php if(!in_array(40,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(40,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="40" class="pinput" <?php if(!in_array(40,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Efficiency Upgrades</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="66" <?php if(!in_array(66,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="66" <?php if(!in_array(66,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(66,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="66" class="pinput" <?php if(!in_array(66,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>EV Charging</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="67" <?php if(!in_array(67,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="67" <?php if(!in_array(67,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(67,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="67" class="pinput" <?php if(!in_array(67,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Rebates &amp; Incentives</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="68" <?php if(!in_array(68,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="68" <?php if(!in_array(68,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(68,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="68" class="pinput" <?php if(!in_array(68,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Other</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="69" <?php if(!in_array(69,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="69" <?php if(!in_array(69,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(69,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="69" class="pinput" <?php if(!in_array(69,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
		
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Dashboard Widgets</b></td>
      </tr>
      <tr>
		<td>Energy Advocate</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="45" <?php if(!in_array(45,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="45" <?php if(!in_array(45,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(45,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="45" class="pinput" <?php if(!in_array(45,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Client Dashboard Summary</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="17" <?php if(!in_array(17,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="17" <?php if(!in_array(17,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(17,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="17" class="pinput" <?php if(!in_array(17,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Saving Analysis</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="6" <?php if(!in_array(6,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="6" <?php if(!in_array(6,$_pdisabled_menu_a_arr)) echo 'checked';?>></td>
 <?php }elseif(!in_array(6,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="6" class="pinput" <?php if(!in_array(6,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>Focus Items</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="5" <?php if(!in_array(5,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="5" <?php if(!in_array(5,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(5,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="5" class="pinput" <?php if(!in_array(5,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
	<?php if(1==2){ ?>	
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
		<td colspan="2"><b>Site List</b></td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="9" <?php if(!in_array(9,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="9" <?php if(!in_array(9,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(9,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="9" class="pinput" <?php if(!in_array(9,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
	  </table>
		</article>
	<?php } ?>
<?php if($_SESSION["group_id"]==1){?>
		<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
	<table cellpadding="5">
      <tr>
         <td colspan="2"><b>Admin</b></td>
      </tr>
      <tr>
		<td>Users Edit</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="12" <?php if(!in_array(12,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="12" <?php if(!in_array(12,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(12,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="12" class="pinput" <?php if(!in_array(12,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
      <tr>
		<td>User Permissions</td>
<?php if($_SESSION["group_id"]==1){?>
         <td><input type="checkbox" name="pu" value="13" <?php if(!in_array(13,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="13" <?php if(!in_array(13,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
 <?php }elseif(!in_array(13,$_pdisabled_menu_a_arr)){ ?>
				<td>					
					<label class="toggle">
						<input type="checkbox" name="checkbox-toggle" value="13" class="pinput" <?php if(!in_array(13,$_pdisabled_menu_c_arr)) echo 'checked';?>>
						<i data-swchon-text="ON" data-swchoff-text="OFF" class="pcheck"></i>
					</label>
				</td>
 <?php }else echo "<td><span class='redc'>(No Access)</span></td>"; ?>
      </tr>
<?php if(1==2){?>
      <tr>
         <td>Company Edit</td><td><input type="checkbox" name="p46" value="pu" <?php if(!in_array(14,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="14" <?php if(!in_array(14,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
      </tr>
      <tr>
         <td>Vendor Edit</td><td><input type="checkbox" name="p47" value="pu" <?php if(!in_array(15,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="15" <?php if(!in_array(15,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
      </tr>
      <tr>
         <td>Audit Log</td><td><input type="checkbox" name="p48" value="pu" <?php if(!in_array(16,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="16" <?php if(!in_array(16,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
      </tr>
      <tr>
         <td>Company Defaults</td><td><input type="checkbox" name="pu" value="1" <?php if(!in_array(1,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="1" <?php if(!in_array(1,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
      </tr>
      <tr>
         <td>Company News</td><td><input type="checkbox" name="pu" value="57" <?php if(!in_array(57,$_pdisabled_menu_c_arr)) echo 'checked';?>>1 <input type="checkbox" name="pa" value="57" <?php if(!in_array(57,$_pdisabled_menu_a_arr)) echo 'checked';?>>2</td>
      </tr>
<?php } ?>
	  </table>
		</article>
<?php } ?>
<?php if($usergroups_id==1 || $usergroups_id==2 || $usergroups_id==4){ ?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 saveit">
			<a class="btn btn-primary btn-sm cusbt" href="javascript:void(0);">Save</a>
		</article>
<?php } ?>
	</div>
					</form>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
		<!-- WIDGET END -->
<script>
$(document).ready(function()
{
	$( ".cusbt" ).click(function() {
		var uper = $("input:checkbox[name=pu]:not(:checked)").map(function(){return $(this).val()}).get().join();
		var aper = $("input:checkbox[name=pa]:not(:checked)").map(function(){return $(this).val()}).get().join();

		$.ajax({
			type: "POST",
			url: "assets/includes/interface.inc.php",
			data: "userid=<?php echo $_userid; ?>&uper="+uper+"&aper="+aper,
			async: true,
			success: function(rstatus){
				if(rstatus == true){
					alert("Saved");
					<?php if($user_one==$_userid){ ?>
					window.location.reload();
					<?php } ?>
				}else{
					alert("False");
				}
			}
		});
	});
	<?php if($usergroups_id==3 || $usergroups_id==5){ ?>
	$(document).on('change', '.pinput', function() {
		var uper = $("input:checkbox[class=pinput]:not(:checked)").map(function(){return $(this).val()}).get().join();
		var aper = $("input:checkbox[name=pa]:not(:checked)").map(function(){return $(this).val()}).get().join();

		$.ajax({
			type: "POST",
			url: "assets/includes/interface.inc.php",
			data: "userid=<?php echo $_userid; ?>&uper="+uper+"&aper="+aper,
			async: true,
			success: function(rstatus){
				if(rstatus == true){
					//alert("Saved");
					$.smallBox({
						title : "Saved",
						content : "<i class='fa fa-clock-o'></i> <i>Refresh Page to see changes...</i>",
						color : "#296191",
						iconSmall : "fa fa-thumbs-up bounce animated",
						timeout : 4000
					});
					<?php if($user_one==$_userid){ ?>
					//window.location.reload();
					<?php } ?>
				}else{
					$.smallBox({
						title : "Error Occured.",
						content : "<i class='fa fa-clock-o'></i> <i>Please try after sometime...</i>",
						color : "#FFA07A",
						iconSmall : "fa fa-warning shake animated",
						timeout : 4000
					});
				}
			}
		});
	});
	<?php } ?>
});
</script>
<?php
}
?>