<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

//print_r($_SESSION);

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");


// DB table to use
////$table = 'sites';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$ts=123;
if(isset($_SESSION["group_id"]) and isset($_SESSION['user_id'])){


/*
	$columns = array(

	 array(
        'db' => 'id',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
		, 'field' => 'id', 'dbnam' => 'sites'
    ),

		array( 'db' => 'c.company_id',     'dt' => 'company_id',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_id', 'dbnam' => 'company_id' ),

		array( 'db' => 'c.company_name',     'dt' => 'company_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'company' ),
		array( 'db' => 's.site_number',     'dt' => 'site_number',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_number', 'dbnam' => 'sites' ),
		array( 'db' => 's.site_name',     'dt' => 'site_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_name', 'dbnam' => 'sites' ),
		array( 'db' => 's.service_address1', 'dt' => 'service_address1',  'formatter' => function( $d, $row ) {return $d;},'field' => 'service_address1', 'dbnam' => 'sites' ),
		array( 'db' => 's.city',     'dt' => 'city',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'city', 'dbnam' => 'sites' ),
		array( 'db' => 's.state',     'dt' => 'state',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'state', 'dbnam' => 'sites'),
		array( 'db' => 's.postal_code',  'dt' => 'postal_code',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'postal_code', 'dbnam' => 'sites' ),
		array( 'db' => 's.site_status',     'dt' => 'site_status',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_status', 'dbnam' => 'sites' )
	);
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		array_push($columns,array( 'db' => 's.active_date',     'dt' => 'active_date',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'active_date', 'dbnam' => 'sites' ));
	}
*/

	$columns = array(

	 array(
        'db' => 'id',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
		, 'field' => 'id', 'dbnam' => 'sites'
    ),

		array( 'db' => 'company_id',     'dt' => 'company_id',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_id', 'dbnam' => 'company' ),

		array( 'db' => 'company_name',     'dt' => 'company_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'company_name', 'dbnam' => 'company' ),
		//array( 'db' => 'site_number',     'dt' => 'site_number',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_number', 'dbnam' => 'sites' ),
		array( 'db' => 'site_number',     'dt' => 'site_number',  'formatter' => function( $d, $row ) {
															/*return makehtml($d, $row, 'site_number');*/ return $d;
														}, 'field' => 'site_number', 'dbnam' => 'sites' ),
		array( 'db' => 'site_name',     'dt' => 'site_name',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_name', 'dbnam' => 'sites' ),
		array( 'db' => 'service_address1', 'dt' => 'service_address1',  'formatter' => function( $d, $row ) {
															/*return makehtml($d, $row, 'service_address1');*/ return $d;
															},'field' => 'service_address1', 'dbnam' => 'sites' ),
		array( 'db' => 'city',     'dt' => 'city',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'city', 'dbnam' => 'sites' ),
		array( 'db' => 'state',     'dt' => 'state',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'state', 'dbnam' => 'sites'),
		array( 'db' => 'postal_code',  'dt' => 'postal_code',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'postal_code', 'dbnam' => 'sites' ),
		array( 'db' => 'site_status',     'dt' => 'site_status',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'site_status', 'dbnam' => 'sites' )
	);
	if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){
		array_push($columns,array( 'db' => 'active_date',     'dt' => 'active_date',  'formatter' => function( $d, $row ) {return $d;}, 'field' => 'active_date', 'dbnam' => 'sites' ));
	}
	


}



function makehtml($d, $row, $column ) {
	//global $table;
	$table = 'sites';
	global $mysqli;
	//print_r($row);
	
	$id = $row['id'];
	$ts=rand(1000,9000);
	//.$i.$j;
	
	$show_icon = '';
	
	// if ( isset($_SESSION['rows']) and is_array($_SESSION['rows'][$id]) ) {
		// echo "--session rows==";
		// print_r($_SESSION['rows']);
	// } else {
	//echo "Select edited_value from audit_log where table_name='$table' and table_row_id='$id'";
		$stmt = $mysqli->query("Select edited_value from audit_log where table_name='$table' and table_row_id='$id' ");
		if ($stmt->num_rows > 0) {
			//$_SESSION['rows'][$id] = [];
			$continue = 0;
			$editedvalueArr = [];
			while($row=$stmt->fetch_assoc()) {
				$editedvalue=$row['edited_value'];
				$editedvalarr=unserialize(base64_decode($editedvalue));
				$z=count($editedvalarr);
				
				for($i=0;$i<$z;$i++)
				{
					if(isset($editedvalarr[$i]["title"]) and trim($editedvalarr[$i]["title"]) == trim($column)){
						//show icon
						$show_icon = '<i class="fa fa-reply" aria-hidden="true"></i>';
						$continue = 1;
						continue;
						//$_SESSION['rows'][$id] = $column;
					}
				}
				if ($continue==1) {$continue=0; continue;}
				//print_r($editedvalarr);
				//die();
				//$editedvalueArr['']=$row['edited_value'];
			}
		}
	//}
	
	/*
	return '<a href="javascript:void(0);" onmouseover="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$d.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover-hover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>'.$show_icon.'
	';
	*/
	
	
	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'list-sites\',\'assets/ajax/list-sites.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover--" href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';
	
	//$tempp='<a href="javascript:void(0);" onclick="load_details(\''.$d.'\')"> '.$d.'</a> ';
	
	//return $tempp;
	
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
////require('../includes/ssp.inc.php' );

//print_r($_POST);
//die();



$site_status = $_POST['columns'][8]['search']['value'];

$status_qry = "";

/*
if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == 'all') {
		$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
	} else if ($site_status == "Active") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == "Inactive") {
		$status_qry = " s.site_status='Inactive' and ";
	}

	$_POST['columns'][8]['search']['value'] = "";
	$_POST['columns'][8]['searchable'] = false;

}
*/

if ( isset($_POST['search']) and $_POST['search']['value']!="" ) {
	//$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
} else {

	if ($site_status == "") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == 'all') {
		$status_qry = " (s.site_status = 'Active' OR s.site_status = 'Inactive') and"; //not working
	} else if ($site_status == "Active") {
		$status_qry = " s.site_status='Active' and ";
	} else if ($site_status == "Inactive") {
		$status_qry = " s.site_status='Inactive' and ";
	}

	$_POST['columns'][8]['search']['value'] = "";
	$_POST['columns'][8]['searchable'] = false;

}


$joinQuery = "FROM sites s,company c, user up";
//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=0 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");

/*
(
($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?
( (isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id")
*/

/*
$and_qry = " ";

if ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) {
	if (isset($_GET["showdemo"]) and $_GET["showdemo"]==1) {
		$and_qry .= " c.company_id != 9 and ";
	} else {
		$and_qry .= " ";
	}
} else {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
}
*/

$and_qry = " ";

if ( ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) AND $_SESSION['company_id']==1) {
	if (isset($_GET["showdemo"]) and $_GET["showdemo"]==1) {
		$and_qry .= " c.company_id != 9 and ";
	} else {
		$and_qry .= " ";
	}
} else if ($_SESSION["group_id"] != 1 AND $_SESSION["group_id"] != 2 AND $_SESSION['company_id']!=1) {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
} else {
	$and_qry .= " c.company_id = ".$_SESSION['company_id']." and ";
}

//$extraWhere = " $status_qry s.company_id=c.company_id and up.company_id=c.company_id and s.deleted=0 ".(($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2)?((isset($_GET["showdemo"]) and $_GET["showdemo"]==1)?" and c.company_id != 9":""):" and ".$user_one."= up.user_id");

$extraWhere = "1";

$groupBy = "s.site_number";
$having = "";
//$having = "`u`.`salary` >= 140000";
// and site_status='Active'
/*
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
*/



// new code--------------------------------

		//$sql = "SELECT `s`.`id` , `c`.`company_id` , `c`.`company_name` , `s`.`site_number` , `s`.`service_address1` , `s`.`city` , `s`.`state` , `s`.`postal_code` , `s`.`site_status` , `s`.`active_date` FROM sites s,company c, user up $extraWhere group by $groupBy";
		//$sql = "SELECT distinct (s.id) , c.company_id , c.company_name , s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date FROM sites s,company c, user up WHERE $extraWhere ";
		//$sql = "SELECT DISTINCT (s.id) , s.company_id, c.company_name , s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date FROM sites s LEFT JOIN company c ON s.company_id=c.company_id WHERE $extraWhere";
		
		//$sql = "SELECT DISTINCT s.company_id, c.company_name , s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date FROM (SELECT company_id FROM `user` WHERE user_id=38) up LEFT JOIN sites s ON up.company_id=s.company_id LEFT JOIN company c ON s.company_id=c.company_id WHERE  s.site_status=‘Active’ AND s.deleted=0  AND c.company_id != 9";
		
		//$sql = "SELECT distinct (s.id), s.company_id, c.company_name , s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date FROM (SELECT company_id FROM `user` WHERE user_id=20) up LEFT JOIN sites s ON up.company_id=s.company_id OR up.company_id=1 LEFT JOIN company c ON s.company_id=c.company_id WHERE  $status_qry s.deleted=0  ";
		
		$sql = "SELECT s.id, c.company_name, s.company_id, s.site_number , s.site_name , s.service_address1 , s.city , s.state , s.postal_code , s.site_status , s.active_date FROM sites s inner join company c on s.company_id = c.company_id WHERE $status_qry  $and_qry s.deleted=0 ";

	//echo $sql;
	
$table = <<<EOT
 (
    $sql
 ) temp
EOT;

//echo $sql;

//require_once '../includes/ssp.class.custom.php';
require('../includes/ssp.inc.php' );
 
// new code end --------------------------------

//$whereall = "deleted = 0";

/*
echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);
*/




echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);

/*
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns)
);
*/
die();

