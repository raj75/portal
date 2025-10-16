<?php
//print_r($_POST);

require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");
	
$cname=$_SESSION["company_id"];

//unset($_SESSION['rows']);
// DB table to use
$table = 'adhoc';

// Table's primary key
//$primaryKey = 'postalcode';
$primaryKey = 'ID';

//print_r($_POST);
//die();
$select_fields = explode(',',$_GET['select_fields']);
//$select_fields = explode(',',$_POST['select_fields']);
//$select_fields = $_POST['select_fields'];
$select_filter = explode(',',$_GET['f']);

/*
if(isset($_POST['draw']))
{
    $KeepPost = $_POST;
    $KeepPost['length'] = -1;
    $PostKept = serialize($KeepPost);
    setcookie("KeepPost",$PostKept,time() + (60*60*24*7));
}
*/

//print_r($_GET);
//die();

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
    //array( 'db' => 'ID', 'dt' => 'ID' ),
	//array( 'db' => 'City', 'dt' => 'City' ),
	//array( 'db' => 'Delivery Address', 'dt' => 'Delivery Address' ),

	/*
	array( 'db' => 'capturis_vendor_id', 'dt' => 'capturis_vendor_id' ),
    array( 'db' => 'vendor_name', 'dt' => 'vendor_name' , 'formatter' =>
														function( $d, $row ) {
															return makehtml($d, $row, 'vendor_name');
														} ),
	array( 'db' => 'capturis_vendor_name', 'dt' => 'capturis_vendor_name' , 'formatter' =>
														function( $d, $row ) {
															return makehtml($d, $row, 'capturis_vendor_name');
														} ),
	*/

);

					foreach ($select_fields as $field) {
						$sub_array = array( "db" => "$field", "dt" => "$field" );
						array_push($columns,$sub_array);
					}


/*
function makehtml($d, $row, $column ) {
	//return $d;

	global $table;
	global $mysqli;
	//print_r($row);

	$id = $row['vendor_id'];
	$ts=rand(1000,9000);
	//.$i.$j;

	$show_icon = '';

	// if ( isset($_SESSION['rows']) and is_array($_SESSION['rows'][$id]) ) {
		// echo "--session rows==";
		// print_r($_SESSION['rows']);
	// } else {

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



	return $d.' <a href="javascript:void(0);" onclick="showversion(\''.$id.'\',\''.$table.'\',\''.$column.'\',\''.$ts.'\',\'start-stop-status-pedit\',\'assets/ajax/start-stop-status-pedit.php?load=true\')" id="'.$ts.'" class="showversion-link">'.$show_icon.'</a>
	<a class="ar_popover" href="javascript:void(0);" rel="popover" data-placement="top" data-original-title="Versions" data-content="None" data-html="true" id="p'.$ts.'"></a>
	';



}
*/

// SQL server connection information
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

require_once '../includes/ssp.class.custom.php';

//$whereall = "deleted = 0";
//$whereall = "vendor_id > 0";
$extraWhere=$joinQuery =$having =$groupBy="";
$subsql=array();

if(count($select_filter) >= 5){
	$startdate=$select_filter[0];
	$enddate=$select_filter[1];
	$services=@trim(@urldecode($select_filter[2]));
	$state=@trim(@urldecode($select_filter[3]));
	$vendor=@trim(@urldecode($select_filter[4]));
	if(isset($select_filter[5])) $company_id=@trim($select_filter[5]);
	else $company_id="";
	
	if($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2){
		
	}else $company_id=$cname;

	//$extraWhere="`Service Type`=IF('".$services."'='All Services', `Service Type`, '".$services."') AND `Vendor Name`=IF('".$vendor."'='All Vendors', `Vendor Name`, '".$vendor."') AND `Location State/Province`=IF('".$state."'='All States', `Location State/Province`, '".$state."') AND `Bill Month` BETWEEN '".$startdate."' AND '".$enddate."'";

	if(!empty($services) and $services !="All Services") $subsql[]=" `service_type_id`= '".$services."' ";
	if(!empty($state) and $state !="All States") $subsql[]=" `state_id`= '".$state."' ";
	if(!empty($vendor) and $vendor !="All Vendors") $subsql[]=" `vendor_id`= '".$vendor."' ";
	
	if(!empty($company_id)) $subsql[]=" `company_id`= '".$company_id."' ";
	
	$subsql[]=" `Period` BETWEEN '".$startdate."' AND '".$enddate."' ";

	$whereall = $extraWhere=" ".implode(" AND ",$subsql);// echo $whereall; die();
/*$whereall =" `Vendor Name`='Flathead Electric Cooperative, Inc.'
AND
`Service Type`='Electric'
AND
`Location State/Province`='NY'
AND
`Bill Month` BETWEEN '2018-01-01' AND '2023-01-01' ";*/
}

echo json_encode(
    //SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	 //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )

	 //SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
	 SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $whereall )
	 //SSP::simplewithwhere( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
);
