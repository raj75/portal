<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

//if(checkpermission($mysqli,30)==false) die("Permission Denied! Please contact Vervantis.");

if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 5)
	die("Restricted Access. Please contact Vervantis Support (support@vervantis.com)!");

$user_one=$_SESSION['user_id'];
$cname=$_SESSION['company_id'];

?>
<style>
.borderbottom{border-bottom: 1px dashed !important;}
.paddingleft0{padding-left: 0 !important;}
</style>
<div class="row fixed">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i>
				Admin <span>> SSO Login</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="">
  <div class="row">
		<article class="col-sm-12 p0">
    <!-- Company List -->
    <?php
  		if(isset($_SESSION) and ($_SESSION["group_id"] == 2 or $_SESSION["group_id"] == 1))
  		{
  			$cname="";
  ?>
  		<div class="row">
  			<form id="selectform" class="smart-form borderbottom" novalidate="novalidate">
  				<fieldset>
  					<section class="col col-6 paddingleft0"><b>Select Company: </b>
  							<select name="selectcompany" id="selectcompany" placeholder="Read" class="">
  							<?php
  									if ($stmtttt = $mysqli->prepare('SELECT DISTINCT c.company_id,c.company_name FROM `user` u INNER JOIN company c ON u.company_id=c.company_id')) {
  										$scnt=0;
  										$stmtttt->execute();
  										$stmtttt->store_result();
  										if ($stmtttt->num_rows > 0) {
  											$stmtttt->bind_result($company_id,$company_name);
  											while($stmtttt->fetch()){
  												if($scnt==0){$cname=$company_id; ++$scnt;}
  											?>
  											<option value="<?php echo $company_id; ?>">&nbsp;&nbsp;<?php echo $company_name; ?></option>
  											<?php
  											}
  										}
  									}
  							?>
  							</select>
  					</section>
  					<section class="col col-6">
  					</section>
  				</fieldset>
  			</form>
  		</div>
  	<?php
  		}elseif(isset($_SESSION) and ($_SESSION["group_id"] == 3 or $_SESSION["group_id"] == 5)){ $cname=$_SESSION["company_id"]; }
  	?>
		</article>
	</div>
	<div class="row">
		<article class="col-sm-12 p0">
    <!-- List -->
      <div class="row" id="listsso">

      </div>
		</article>
	</div>
  <div class="row">
		<article class="col-sm-12 p0">
      <!-- Edit-->
      <div class="row" id="editsso">

      </div>
		</article>
	</div>
</section>
<div id="ssoresponse"></div>
<script type="text/javascript">
$(document).ready(function(){
	<?php if(!empty($cname)){ ?>
	$('#listsso').load('assets/ajax/ssologin-pedit.php?cid=<?php echo $cname; ?>&ct=<?php echo time(); ?>');
	<?php } ?>
  $('#selectcompany').on('change', function() {
      //alert( this.value );
			$('#listsso').load('assets/ajax/ssologin-pedit.php?cid='+this.value+'&ct=<?php echo time(); ?>');
  });

});
</script>
