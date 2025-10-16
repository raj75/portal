<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

/*$mysqli = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","weather2");
//$conn = mysqli_connect("localhost", "root","","vervantis");
if (!$mysqli) {
    printf("Connect failed: %s\n", mysqli_connect_errno());
    exit();
}*/

//print_r($_POST);

//$str = $_POST['form_data'];
parse_str($_POST['form_data'], $form_data_arr);

$results_per_page = (int) $_POST['pageSize'];  //pageSize
$page = (int) $_POST['page'];  //page
$page_first_result = ($page-1) * $results_per_page;

//print_r($form_data_arr);

/*
query by john
*/

$qry_str_from = " FROM ubm_database.tblSiteAllocations AS a LEFT JOIN ubm_database.tblSites AS b ON a.SiteID = b.SiteID LEFT JOIN ubm_database.tblServiceTypes AS c ON a.ServiceTypeID = c.ServiceTypeID LEFT JOIN ubm_database.tblAccounts AS d ON a.AccountID = d.AccountID LEFT JOIN ubm_database.tblVendors AS e ON a.VendorID = e.VendorID LEFT JOIN vervantis.company f ON a.ClientID=f.company_id ";

$where_qry = '';


if ($_POST['fieldid'] == 'wa_country') {
		
	$where_qry .= return_where_qry();	
	//$where_qry = "";
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT(country_code)) as total_record FROM postal_codes ");
		
		$qry_str = "SELECT DISTINCT(country_code) as optionID,country_code as optionName FROM postal_codes WHERE 1=1 limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT(country_code)) as total_record FROM postal_codes WHERE 1=1 and country_code like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT(country_code) as optionID,country_code as optionName from postal_codes WHERE 1=1 $where_qry AND country_code like '%".$search."%' limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
		//echo $qry_str;
	}
	
}


if ($_POST['fieldid'] == 'postal_code') {
		
	$where_qry .= return_where_qry();	
	//$where_qry = '';
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT DISTINCT(postal_code) as total_record FROM postal_codes ");
		
		$qry_str = "SELECT DISTINCT(postal_code) as optionID,postal_code as optionName from postal_codes WHERE 1=1 $where_qry limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT(postal_code)) as total_record from postal_codes WHERE 1=1  like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT(postal_code) as optionID,postal_code as optionName from postal_codes WHERE 1=1 $where_qry AND postal_code like '%".$search."%' limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);

	}
	
}


//echo $qry_str;

$total_rs = mysqli_fetch_array($totalQry);
$total_records = $total_rs['total_record'];
//params.page * 15) < data.total_count

//echo "<br>number_of_result==".$total_sites;
//echo "<br>page_first_result==".$page_first_result;

//if ( $page_first_result < $total_sites ) {
if ( $page * $results_per_page < $total_records ) {
	$paging = true;
} else {
	$paging = false;
}

$data = array();

$data['pagination'] = array("more"=>$paging);
//$data['pagination'] = array("more"=>true);

while ($row = mysqli_fetch_array($fetchData)) {    
    ////$data[] = array("id"=>$row['optionID'], "text"=>$row['optionName']);
	$data['results'][] = array("id"=>$row['optionID'], "text"=>$row['optionName']);
}

//----function to create query-------
function return_where_qry() {
	
	$p_field_id = $_POST['fieldid'];
	$f_where_qry = "";
	
	if ( isset($_POST['wa_country']) and count($_POST['wa_country']) > 0 ) {
		$wa_country = implode(',', $_POST['wa_country']);	
		$country_ar_coma = str_replace("," , "','" , $wa_country);
		$f_where_qry .= " AND country_code IN ('$wa_country') ";
	}
	
	//---------------monthly weather----------------------
	/*
	if ( isset($_POST['wa_country_ar']) and count($_POST['wa_country_ar']) > 0 ) {
		$wa_country_ar = implode(',', $_POST['wa_country_ar']);
		$wa_country_ar_coma = str_replace("," , "','" , $wa_country_ar);
		$f_where_qry .= " AND   IN ('$country_ar_coma') ";
	}
	*/
	
	return $f_where_qry;
	
}
//$data['pagination'] = array("more" => 5);
/*
$data = array();
$data[] = array("id"=>1, "text"=>"Aamir");
$data[] = array("id"=>2, "text"=>"Tahir");
*/

/*
$results = array(
  "results" => $breeds,
  "pagination" => array(
    "more" => $morePages
  )
);
*/

/*
echo '
{
  "results": [
    {
      "id": 1,
      "text": "Option 1"
    },
    {
      "id": 2,
      "text": "Option 2"
    }
  ],
  "pagination": {
    "more": true
  }
}
';
*/

echo json_encode($data);

/*
$data = array();
$data['pagination'] = array("more"=>true);
$data[] = array("id"=>1, "text"=>"Aamir");
$data[] = array("id"=>2, "text"=>"Tahir");
*/

/*
$data = array();

$data['pagination'] = array("more"=>true);
$data['results'][] = array("id"=>1, "text"=>"Aamir");
$data['results'][] = array("id"=>2, "text"=>"Tahir");
*/

//echo json_encode($data);
?>
