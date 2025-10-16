<?php

require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();


/*$mysqli = mysqli_connect("develop-aurora-instance-1.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root","7Rjfz0cDjsSc","vervantis");
//$conn = mysqli_connect("localhost", "root","","vervantis");
if (!$mysqli) {
    printf("Connect failed: %s\n", mysqli_connect_errno());
    exit();
}*/

//print_r($_REQUEST);
$filter_qry = stripslashes(mysqli_real_escape_string($mysqli,urldecode(base64_decode($_GET['f_q']))));
$filter_subqry = stripslashes(mysqli_real_escape_string($mysqli,urldecode(base64_decode($_GET['f_sq']))));
$order_by = stripslashes(mysqli_real_escape_string($mysqli,urldecode(base64_decode($_GET['o_b']))));

if ($order_by=="SiteNumber") {
	$my_order_by = " e.SiteNumber";
} else if ($order_by=="vendor") {
	$my_order_by = " c.VendorName";
} else if ($order_by=="account") {
	$my_order_by = " d.AccountNumber";
} else {
	$my_order_by = " e.SiteNumber";
}
//echo $my_order_by;
//echo "<br>filter_qry==".$filter_qry."<br>";

$chart_data = [];
//print_r($_POST);
////$chart_qry_str = $_SESSION['f_invoice_audit_chart_qry'];
////unset($_SESSION['f_invoice_audit_chart_qry']);
//echo $chart_qry;


$chart_qry_str = "SELECT
			a.ClientID,
			a.VendorID,
			c.VendorName,
			a.AccountID,
			d.AccountNumber,
			b.SiteID,
			e.SiteNumber,
			e.SiteName,
			a.InvoiceID,
			a.TotalDue,
			a.InvoiceBegin,
			a.InvoiceEnd,
			a.Period,
			a.InvoiceServiceDays,
			a.ReceiptDate,
			a.EntryDate,
			a.ConsolidationNotificationDate,
			a.ConsolidationReceivedDate,
			a.VendorPaymentDate,
			a.VendorPaymentClearDate,
			a.CheckVoidDate
		FROM
			ubm_database.tblInvoices AS a
		INNER JOIN
			(SELECT DISTINCT ClientID, AccountID, SiteID FROM ubm_database.tblSiteAllocations where 1=1  $filter_subqry  ) b
		ON
			a.ClientID = b.ClientID AND a.AccountID = b.AccountID
		INNER JOIN
			ubm_database.tblVendors c
		ON
			a.VendorID = c.VendorID
		INNER JOIN
			ubm_database.tblAccounts d
		ON
			a.AccountID = d.AccountID
		INNER JOIN
			ubm_database.tblSites e
		ON
			b.SiteID = e.SiteID	
		WHERE
			1 = 1
			 $filter_qry
			AND a.DeletedInvoice = 0 
			
			
		ORDER BY
			$my_order_by ASC
			
			";
			
			/*
			ORDER BY
			a.InvoiceID ASC
			*/
			
			
		//echo "<br>".$chart_qry_str."<br>";	

		$chart_qry = mysqli_query($mysqli,$chart_qry_str);
	
		$i=0;
		$cat_count = 0;
		$cat_arr = [];
		
		
		/*
		while ($row_co = mysqli_fetch_array($chart_qry)) {
			$chart_data [$i]["category"] = $row_co['VendorName']." / ".$row_co['AccountNumber'];
			$chart_data [$i]["fromDate"] = $row_co['InvoiceBegin'];
			$chart_data [$i]["toDate"] = $row_co['InvoiceEnd'];
			$chart_data [$i]["color"] = "#89CFF0";
			$chart_data [$i]["invoiceid"] = $row_co['InvoiceID'];
			$chart_data [$i]["totaldue"] = $row_co['TotalDue'];
			$chart_data [$i]["vendorname"] = $row_co['VendorName'];
			
			$chart_data [$i]["category_account"] = $row_co['VendorName']."_".$row_co['AccountNumber'];
			
			$cat_arr[] = $chart_data [$i]["category_account"];
			
			//echo "<br>".$chart_data [$i]["category"];
			
			//if ( in_array($chart_data [$i]["category_account"], $cat_arr) ) {
				
				//echo "<br>cat==".$row_co['VendorName']." / ".$row_co['AccountNumber'];
				//echo "<br>old_cat==".$old_cat;
				
				
				//$cat_count++;
				//$old_cat = $row_co['VendorName']." / ".$row_co['AccountNumber'];
				
				
			//}
			
			$i++;
		}
		*/
		
		if ( mysqli_num_rows($chart_qry) > 0 ) {
			
			$colorArray = ["#89CFF0","#7393B3","#0096FF","#6495ED","#4169E1","#4682B4"];
			
			while ($row_co = mysqli_fetch_array($chart_qry)) {
				$chart_data ["data"][$i]["category"] = $row_co['SiteNumber']." / ".$row_co['VendorName']." / ".$row_co['AccountNumber'];
				$chart_data ["data"][$i]["fromDate"] = $row_co['InvoiceBegin'];
				$chart_data ["data"][$i]["toDate"] = $row_co['InvoiceEnd'];
				//$chart_data ["data"][$i]["color"] = "#89CFF0";
				//$chart_data ["data"][$i]["color"] = $colorArray[rand(0,5)];
				$chart_data ["data"][$i]["columnSettings"] = ["fill"=> $colorArray[rand(0,5)]];
				$chart_data ["data"][$i]["invoiceid"] = $row_co['InvoiceID'];
				$chart_data ["data"][$i]["totaldue"] = $row_co['TotalDue'];
				$chart_data ["data"][$i]["vendorname"] = $row_co['VendorName'];
				
				//$chart_cats [$i] = $row_co['VendorName']." / ".$row_co['AccountNumber'];
				
				////$chart_cats [$i]['category'] = $row_co['VendorName']." / ".$row_co['AccountNumber'];
				
				$chart_cats [$i] = $row_co['SiteNumber']." / ".$row_co['VendorName']." / ".$row_co['AccountNumber'];
				
				//$cat_arr[] = $chart_data [$i]["category_account"];
				
				// category array
				////$chart_data ["category_ar"][$i]["category"] = $row_co['VendorName']." / ".$row_co['AccountNumber'];
				
				//echo "<br>".$chart_data [$i]["category"];
				
				//if ( in_array($chart_data [$i]["category_account"], $cat_arr) ) {
					
					//echo "<br>cat==".$row_co['VendorName']." / ".$row_co['AccountNumber'];
					//echo "<br>old_cat==".$old_cat;
					
					
					//$cat_count++;
					//$old_cat = $row_co['VendorName']." / ".$row_co['AccountNumber'];
					
					
				//}
				
				$i++;
			}
			
			$cat_arr_uni = array_count_values($chart_cats);
			
			$cat_ind=0;
			$cat_arr_uni_ar = [];
			foreach($cat_arr_uni as $cat_val=>$cat_count) {
				$cat_arr_uni_ar [$cat_ind]["category"] = $cat_val;
				$cat_ind++;
			}
			////$cat_arr_uni = array_unique($chart_cats,SORT_REGULAR);
			////$cat_arr_uni = array_values( array_flip( array_flip( $chart_cats ) ) );
			
			//echo "<br>count arrr=".count($cat_arr_uni);
			
			//echo "<pre>";
			//print_r($cat_arr);
			
			//echo "<br>cat_count==".$cat_count;
			
			////$chart_data [] = count($cat_arr_uni);
			
			$chart_data ["category_ar"] = $cat_arr_uni_ar;
		
		} // end of if
		
		/*
		if ($qry_co->num_rows > 0) {
			$i=0;
			while($row_co=$qry_co->fetch_assoc()) {
				
				$chart_data [$i]["category"] = $row_co['VendorName']." / ".$row_co['AccountNumber'];
				$chart_data [$i]["fromDate"] = $row_co['InvoiceBegin'];
				$chart_data [$i]["toDate"] = $row_co['InvoiceEnd'];
				$chart_data [$i]["color"] = "#89CFF0";
				$chart_data [$i]["invoiceid"] = $row_co['InvoiceID'];
				$chart_data [$i]["totaldue"] = $row_co['TotalDue'];
				$chart_data [$i]["vendorname"] = $row_co['VendorName'];
				
				$i++;
				
				$company_id=$row_co['company_id'];
				$company_name=$row_co['company_name'];
				$selected = ($company_id==9)?'selected':'';
				$company_options .= "<option value='$company_id' $selected >$company_name</option>";
				
			}
		}
		*/

/*
$item = [];
$item [0]["category"] = "123456";
$item [0]["fromDate"] = "10/01/2024";
$item [0]["toDate"] = "12/31/2024";
$item [0]["color"] = "#89CFF0";
$item [0]["invoiceid"] = "22222";
$item [0]["totaldue"] = "2525";
$item [0]["vendorname"] = "vendor name";
*/

echo json_encode($chart_data);

//echo '{"category":"123456","fromDate":"10\/01\/2024","toDate":"12\/31\/2024","color":"#89CFF0","invoiceid":"22222","totaldue":"2525","vendorname":"vendor name"}';

//echo implode(',', $item);
