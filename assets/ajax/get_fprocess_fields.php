<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

sec_session_start();

/*$mysqli = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
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
/*
SELECT DISTINCT
	a.ClientID,
	a.VendorID,
	e.VendorName,
	a.AccountID,
	d.AccountNumber, 	
	a.ServiceTypeID,
	c.ServiceTypeName,
	a.SiteID,
	b.SiteNumber,
	b.SiteState
FROM
		tblSiteAllocations AS a
	LEFT JOIN
		tblSites AS b
	ON
		a.SiteID = b.SiteID
	LEFT JOIN
		tblServiceTypes AS c
	ON
		a.ServiceTypeID = c.ServiceTypeID
	LEFT JOIN
		tblAccounts AS d
	ON
		a.AccountID = d.AccountID
	LEFT JOIN
		tblVendors AS e
	ON
		a.VendorID = e.VendorID
WHERE
	a.ClientID = 10
	AND a.VendorID=17
	AND a.ServiceTypeID=18
	AND a.SiteID=36898
	AND b.SiteState='NC'
*/

/*
$qry_str_from = "
			
			FROM
					ubm_newschema4.tblSiteAllocations AS a
				LEFT JOIN
					ubm_newschema4.tblSites AS b
				ON
					a.SiteID = b.SiteID
				LEFT JOIN
					ubm_newschema4.tblServiceTypes AS c
				ON
					a.ServiceTypeID = c.ServiceTypeID
				LEFT JOIN
					ubm_newschema4.tblAccounts AS d
				ON
					a.AccountID = d.AccountID
				LEFT JOIN
					ubm_newschema4.tblVendors AS e
				ON
					a.VendorID = e.VendorID
			
";
*/

$qry_str_from = " FROM ubm_database.tblSiteAllocations AS a LEFT JOIN ubm_database.tblSites AS b ON a.SiteID = b.SiteID LEFT JOIN ubm_database.tblServiceTypes AS c ON a.ServiceTypeID = c.ServiceTypeID LEFT JOIN ubm_database.tblAccounts AS d ON a.AccountID = d.AccountID LEFT JOIN ubm_database.tblVendors AS e ON a.VendorID = e.VendorID LEFT JOIN vervantis.company f ON a.ClientID=f.company_id ";

$where_qry = '';

if ($_POST['fieldid'] == 'vendor') {
	
	$where_qry .= return_where_qry();	
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.VendorID)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT a.VendorID as optionID,e.VendorName as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.VendorID)) as total_record $qry_str_from WHERE 1=1 $where_qry AND e.VendorName like '%".$search."%' ");
		$qry_str = "SELECT DISTINCT a.VendorID as optionID,e.VendorName as optionName $qry_str_from WHERE 1=1 $where_qry AND e.VendorName like '%".$search."%' order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
}

if ($_POST['fieldid'] == 'account') {
	
	$where_qry .= return_where_qry();
	 
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.AccountID)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT a.AccountID as optionID,d.AccountNumber as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.AccountID)) as total_record $qry_str_from WHERE 1=1 $where_qry AND d.AccountNumber like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT a.AccountID as optionID,d.AccountNumber as optionName $qry_str_from WHERE 1=1 $where_qry AND d.AccountNumber like '%".$search."%' order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
}

if ($_POST['fieldid'] == 'country') {	
	
	$where_qry .= return_where_qry();

	if(!isset($_POST['searchTerm'])){ 
	
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (b.SiteCountry)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT b.SiteCountry as optionID,b.SiteCountry as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (b.SiteCountry)) as total_record $qry_str_from WHERE 1=1 $where_qry AND b.SiteCountry like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT b.SiteCountry as optionID,b.SiteCountry as optionName $qry_str_from WHERE 1=1 $where_qry AND b.SiteCountry like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
}

if ($_POST['fieldid'] == 'state') {
		
	$where_qry .= return_where_qry();
		
	if(!isset($_POST['searchTerm'])){ 

		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (b.SiteState)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT b.SiteState as optionID,b.SiteState as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		//$fetchData = mysqli_query($mysqli,$qry_str);
		$fetchData = mysqli_query($mysqli,$qry_str);
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);	

		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (b.SiteState)) as total_record $qry_str_from WHERE 1=1 $where_qry AND b.SiteState like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT b.SiteState as optionID,b.SiteState as optionName $qry_str_from WHERE 1=1 $where_qry AND b.SiteState like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
}

if ($_POST['fieldid'] == 'company') {	
	
	$where_qry .= return_where_qry();		
	
	if(!isset($_POST['searchTerm'])){ 		
	
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.ClientID)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT a.ClientID as optionID,f.company_name as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);	

		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.VendorID)) as total_record $qry_str_from WHERE 1=1 $where_qry AND f.company_name like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT a.ClientID as optionID,f.company_name as optionName $qry_str_from WHERE 1=1 $where_qry AND f.company_name like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
}

if ($_POST['fieldid'] == 'site') {
		
	$where_qry .= return_where_qry();	
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.SiteID)) as total_record $qry_str_from WHERE 1=1 $where_qry ");
		
		$qry_str = "SELECT DISTINCT a.SiteID as optionID,b.SiteNumber as optionName $qry_str_from WHERE 1=1 $where_qry order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(DISTINCT (a.SiteID)) as total_record $qry_str_from WHERE 1=1 $where_qry AND b.SiteNumber like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT a.SiteID as optionID,b.SiteNumber as optionName $qry_str_from WHERE 1=1 $where_qry AND b.SiteNumber like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
	
}


if ($_POST['fieldid'] == 'wa_country') {
		
	//$where_qry .= return_where_qry();	
	$where_qry = "";
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT DISTINCT a.country_code FROM postal_codes AS a ");
		
		$qry_str = "SELECT postal_code as optionID,postal_code as optionName FROM postal_codes AS a WHERE 1=1 order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(postal_code) as total_record WHERE 1=1 postal_code like '%".$search."%' ");
		
		$qry_str = "SELECT postal_code as optionID,postal_code as optionName WHERE 1=1 $where_qry AND postal_code like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
		$fetchData = mysqli_query($mysqli,$qry_str);
	}
	
}


if ($_POST['fieldid'] == 'postal_code') {
		
	$where_qry .= return_where_qry();	
	
	if(!isset($_POST['searchTerm'])){ 
		
		$totalQry = mysqli_query($mysqli,"SELECT postal_code FROM postal_codes ");
		
		$qry_str = "SELECT postal_code as optionID,postal_code as optionName WHERE 1=1 order by optionName limit $page_first_result , $results_per_page";
		$fetchData = mysqli_query($mysqli,$qry_str);
		
	}else{ 
		$search = mysqli_real_escape_string($mysqli,$_POST['searchTerm']);
		
		$totalQry = mysqli_query($mysqli,"SELECT count(postal_code) as total_record WHERE 1=1  like '%".$search."%' ");
		
		$qry_str = "SELECT DISTINCT a.SiteID as optionID,b.SiteNumber as optionName $qry_str_from WHERE 1=1 $where_qry AND b.SiteNumber like '%".$search."%' order by optionName limit $page_first_result , $results_per_page ";
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
	
	if ( isset($_POST['account_ids']) and count($_POST['account_ids']) > 0 AND $p_field_id != 'account' ) {
		$account_ids = implode(',', $_POST['account_ids']);		
		$f_where_qry .= " AND a.AccountID IN ($account_ids) ";
	}
	
	if ( isset($_POST['vendor_ids']) and count($_POST['vendor_ids']) > 0 AND $p_field_id != 'vendor' ) {
		$vendor_ids = implode(',', $_POST['vendor_ids']);		
		$f_where_qry .= " AND a.VendorID IN ($vendor_ids) ";
	}
	
	if ( isset($_POST['country_ar']) and count($_POST['country_ar']) > 0 AND $p_field_id != 'country' ) {
		$country_ar = implode(',', $_POST['country_ar']);
		$country_ar_coma = str_replace("," , "','" , $country_ar);
		$f_where_qry .= " AND  b.SiteCountry IN ('$country_ar_coma') ";
	}
	
	if ( isset($_POST['state_ar']) and count($_POST['state_ar']) > 0 AND $p_field_id != 'state' ) {
		$state_ar = implode(',', $_POST['state_ar']);
		$state_ar_coma = str_replace("," , "','" , $state_ar);
		$f_where_qry .= " AND b.SiteState IN ('$state_ar_coma') ";
	}
	
	if ( isset($_POST['client_ids']) and count($_POST['client_ids']) > 0 AND $p_field_id != 'company' ) {
		$client_ids = implode(',', $_POST['client_ids']);
		$f_where_qry .= " AND a.ClientID IN ($client_ids) ";
	}
	
	if ( isset($_POST['site_ids']) and count($_POST['site_ids']) > 0 AND $p_field_id != 'site' ) {
		$site_ids = implode(',', $_POST['site_ids']);
		$f_where_qry .= " AND a.SiteID IN ($site_ids) ";
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
