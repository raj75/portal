<?php
//require_once("inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");



// DB table to use
$table = 'scheduled_reports';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){
	$columns = array(
		array( 'db' => '`s`.`id`', 'dt' => 0,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'id', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`scheduled_report_name`',     'dt' => 1,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'scheduled_report_name', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`minute`',     'dt' => 2,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'minute', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`hour`',     'dt' => 3,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'hour', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`day`',     'dt' => 4,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'day', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`month`',     'dt' => 5,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'month', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`weekday`',     'dt' => 6,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'weekday', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`email`', 'dt' => 7,  'formatter' => function( $d, $row ) {return $d;},'field' => 'email', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`up`.`firstname`',     'dt' => 8,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'firstname', 'dbnam' => 'users' ),
		array( 'db' => '`up`.`lastname`',     'dt' => 9,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'lastname', 'dbnam' => 'users' ),
		array( 'db' => '`s`.`created_date`',     'dt' => 10,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'created_date', 'dbnam' => 'scheduled_reports'),
		array( 'db' => '`s`.`next_run`',     'dt' => 11,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'next_run', 'dbnam' => 'scheduled_reports'),
		array( 'db' => '`s`.`status`',     'dt' => 12,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'status', 'dbnam' => 'scheduled_reports' ),
		array( 'db' => '`s`.`last_run_status`',     'dt' => 13,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'last_run_status', 'dbnam' => 'scheduled_reports' )
	);
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		array_push($columns,array( 'db' => '`s`.`modified_by`',     'dt' => 14,  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'modified_by', 'dbnam' => 'scheduled_reports' ));
	}
}






$sql_details = array(
	'user' => USER,
	'pass' => PASSWORD,
	'db'   => DATABASE,
	'host' => HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );

if(isset($_SESSION['group_id']) and ($_SESSION['group_id'] == 1)) $tmp_query="";
else $tmp_query=" and ".$user_one."= up.user_id";


require('../../includes/scheduled_reports_ssp.inc.php' );

$joinQuery = "FROM scheduled_reports s, user up";
$extraWhere = " s.created_by=up.user_id".$tmp_query;
$groupBy = "";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);




die();







	if ($stmt = $mysqli->prepare('SELECT s.site_number,c.company_name,s.division,s.country,s.state,s.city,s.site_number,s.site_name,s.site_status FROM sites s,company c, userprofile up WHERE s.company_id=c.company_id and up.company_id=c.company_id '.(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" and '".$user_one."'= up.user_id").' group by s.site_number')) {

//('SELECT s.id,c.company_name,s.division,s.country,s.state,s.city,s.site_number,s.site_name,s.site_status FROM sites s,company c, userprofile up WHERE s.company_id=c.id and up.company_id=c.id '.(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?"":" and '".$user_one."'= up.user_id").' group by s.id')) {

        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($id,$Company,$Division,$Country,$State,$City,$Site_Number,$Site_Name,$Site_Status);
			while($stmt->fetch()) {
				$ts=$id.rand(650,900);
			?>
				<tr>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
						<td><span onclick="load_details(<?php echo $id; ?>)"><?php echo $id; ?></span></td>
<?php } ?>
						<td><a href="javascript:void(0);"<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?> onmouseover="showversion('<?php echo $id; ?>','<?php echo 'company'; ?>','<?php echo 'company_name'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>" class="showversion-link"<?php } ?> onclick="load_details(<?php echo $id; ?>)"> <?php echo $Company; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>"></a><?php } ?>
						</td>
						<td><a href="javascript:void(0);"<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'division'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>" <?php } ?> onclick="load_details(<?php echo $id; ?>)"><?php echo $Division; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);"<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'country'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>"<?php } ?> onclick="load_details(<?php echo $id; ?>)"> <?php echo $Country; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);"<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'state'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>"<?php } ?> onclick="load_details(<?php echo $id; ?>)"> <?php echo $State; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);" onclick="load_details(<?php echo $id; ?>)" <?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'city'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>"<?php } ?>> <?php echo $City; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);" onclick="load_details(<?php echo $id; ?>)" <?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'site_number'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>" <?php } ?>> <?php echo $Site_Number; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);" onclick="load_details(<?php echo $id; ?>)" <?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'site_name'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>"<?php } ?>> <?php echo $Site_Name; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
						<td><a href="javascript:void(0);" onclick="load_details(<?php echo $id; ?>)" <?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><?php --$ts;?> class="showversion-link" onmouseover="showversion('<?php echo $id; ?>','<?php echo 'sites'; ?>','<?php echo 'site_status'; ?>','<?php echo $ts;?>','list-sites','assets/ajax/list-sites.php?load=true')" id="<?php echo $ts;?>"<?php } ?>> <?php echo $Site_Status; ?></a><?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)){?><a href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="<h4>Versions</h4>" data-content="None" data-html="true" id="p<?php echo $ts;?>">&nbsp;</a><?php } ?>
						</td>
<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
						<td><button onclick="loadsite(<?php echo $id; ?>)" title="View/Edit Site Details" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button><button onclick="deletesite(<?php echo $id; ?>,'<?php echo $Site_Name; ?>')" title="Delete Site" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button></td>
<?php } ?>
					</tr>
			<?php
			}
		}
	}
?>
