<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

require_once '../php/SimpleXLSXGen.php';
 
if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

//$select_fields_str = "ID,".$_GET['select_fields'];
//$select_fields_str = $_GET['select_fields'];

//$select_fields_str = $_POST['select_fields'];
//$select_fields = explode(',',$select_fields_str);
$select_fields=array();
$select_fields_str=$select_val_str ="";
if(isset($_POST['select_fields'])){
	$select_fields = $_POST['select_fields'];
	$select_fields_str = implode(',',$_POST['select_fields']);
	if(isset($_POST['select_filter'])){ $select_val_str = implode(',',$_POST['select_filter']); }
}else die();
//$select_val_str = implode(',',$_POST['select_val']);

?>

<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />

<style>
	table.dataTable.dt-checkboxes-select tbody tr,
	table.dataTable thead th.dt-checkboxes-select-all,
	table.dataTable tbody td.dt-checkboxes-cell {
		cursor: pointer;
	}

	table.dataTable thead th.dt-checkboxes-select-all,
	table.dataTable tbody td.dt-checkboxes-cell {
		text-align: center;
	}

	div.dataTables_wrapper span.select-info,
	div.dataTables_wrapper span.select-item {
		margin-left: 0.5em;
	}

	@media screen and (max-width: 640px) {
		div.dataTables_wrapper span.select-info,
		div.dataTables_wrapper span.select-item {
			margin-left: 0;
			display: block;
		}
	}
	#adhoc_datatable_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	#adhoc_datatable_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#adhoc_datatable{border-bottom: 1px solid #ccc !important;}
	#adhoc_datatable .widget-body,#adhoc_datatable #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}

	.m-top{margin-top:56px;}
	.m-top45{margin-top:47px;}
	.m-top20{margin-top:20px;}
	.m-top77{margin-top:79px;}
	.m-bottom50{margin-bottom: -50px !important;font-weight:bold;z-index:98;margin-top: 15px;}
	.m-bottom50 span{vertical-align: top;}
	.sdrp{width:65px; font-weight:normal;}

	.DTED_Lightbox_Background{z-index:905 !important;}
	.DTED_Lightbox_Wrapper{z-index:906 !important;}


	div.DTE_Body div.DTE_Body_Content div.DTE_Field {
		width: 50%;
		padding: 5px 20px;
		box-sizing: border-box;
	}

	div.DTE_Body div.DTE_Form_Content {
		display:flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	div.DTE_Field select{
		width:100%;
	}

	.popover{max-width:500px;}

	#adhoc_datatable tbody td .fa{cursor:pointer;}

	/*
	div.DTE_Body div.DTE_Body_Content div.DTE_Field{
		padding: 5px 0%;
		float: left;
		width: 50%;
		clear: none;
	}
	div.DTE_Body div.DTE_Body_Content div.DTE_Field > label {
		width: 35%;
	}
	div.DTE_Body div.DTE_Body_Content div.DTE_Field > div.DTE_Field_Input{
		float: left;
		width: 60%;
	}
	*/

	div.dataTables_filter label {float: left;}

	.show_deleted,.hide_deleted{margin-left:15px;}

	#ei_datatable_fixed_column .vendor_type {
		font-weight: 400 !important;
	}


	#adhoc_datatable_filter {margin: 10px !important;}
	.dt-buttons {margin: 10px !important;}
	#adhoc_datatable_length {margin: 10px !important;}

	div.dt-buttons {
	  position: absolute;
	  float: left;
	  left: 275px;
	}
	.dt-buttons .buttons-collection {
		height:30px;
		padding-top:5px;
	}

	#adhoc_datatable_length {
		position: absolute;
	    float: left;
	    left: 465px;
	}

	.dataTables_wrapper .dataTables_info {margin-left:10px !important;}

	/* spinner css*/


	/*
		.spinner {
		  animation: rotate 2s linear infinite;
		  z-index: 2;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  margin: -25px 0 0 -25px;
		  width: 50px;
		  height: 50px;

		  & .path {
			stroke: hsl(210, 70, 75);
			stroke-linecap: round;
			animation: dash 1.5s ease-in-out infinite;
		  }

		}

		@keyframes rotate {
		  100% {
			transform: rotate(360deg);
		  }
		}

		@keyframes dash {
		  0% {
			stroke-dasharray: 1, 150;
			stroke-dashoffset: 0;
		  }
		  50% {
			stroke-dasharray: 90, 150;
			stroke-dashoffset: -35;
		  }
		  100% {
			stroke-dasharray: 90, 150;
			stroke-dashoffset: -124;
		  }
		}

		*/








	/* Absolute Center Spinner */
	.loading_ar {
	  position: fixed;
	  z-index: 999;
	  overflow: show;
	  margin: auto;
	  top: 0;
	  left: 0;
	  bottom: 0;
	  right: 0;
	  width: 50px;
	  height: 50px;
	  display:none;
	}

	/* Transparent Overlay

	/*
	.loading_ar:before {
	  content: '';
	  display: block;
	  position: fixed;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  background-color: rgba(255,255,255,0.5);
	}
	*/

	/* :not(:required) hides these rules from IE9 and below */
	.loading_ar:not(:required) {
	  /* hide "loading..." text */
	  font: 0/0 a;
	  color: transparent;
	  text-shadow: none;
	  background-color: transparent;
	  border: 0;
	}

	.loading_ar:not(:required):after {
	  content: '';
	  display: block;
	  font-size: 10px;
	  width: 50px;
	  height: 50px;
	  margin-top: -0.5em;

	  /*border: 7px solid rgba(33, 150, 243, 1.0);*/
	  border: 7px solid rgb(130, 141, 149);
	  border-radius: 100%;
	  border-bottom-color: transparent;
	  -webkit-animation: spinner 1s linear 0s infinite;
	  animation: spinner 1s linear 0s infinite;


	}

	/* Animation */

	@-webkit-keyframes spinner {
	  0% {
		-webkit-transform: rotate(0deg);
		-moz-transform: rotate(0deg);
		-ms-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg);
	  }
	  100% {
		-webkit-transform: rotate(360deg);
		-moz-transform: rotate(360deg);
		-ms-transform: rotate(360deg);
		-o-transform: rotate(360deg);
		transform: rotate(360deg);
	  }
	}
	@-moz-keyframes spinner {
	  0% {
		-webkit-transform: rotate(0deg);
		-moz-transform: rotate(0deg);
		-ms-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg);
	  }
	  100% {
		-webkit-transform: rotate(360deg);
		-moz-transform: rotate(360deg);
		-ms-transform: rotate(360deg);
		-o-transform: rotate(360deg);
		transform: rotate(360deg);
	  }
	}
	@-o-keyframes spinner {
	  0% {
		-webkit-transform: rotate(0deg);
		-moz-transform: rotate(0deg);
		-ms-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg);
	  }
	  100% {
		-webkit-transform: rotate(360deg);
		-moz-transform: rotate(360deg);
		-ms-transform: rotate(360deg);
		-o-transform: rotate(360deg);
		transform: rotate(360deg);
	  }
	}
	@keyframes spinner {
	  0% {
		-webkit-transform: rotate(0deg);
		-moz-transform: rotate(0deg);
		-ms-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg);
	  }
	  100% {
		-webkit-transform: rotate(360deg);
		-moz-transform: rotate(360deg);
		-ms-transform: rotate(360deg);
		-o-transform: rotate(360deg);
		transform: rotate(360deg);
	  }
	}

	#adhoc_datatable_filter{display:none !important;}
	#adhoc_datatable{padding-top: 50px !important;}
	#adhoc_datatable_wrapper .dt-buttons{left:0px !important;}
	#adhoc_datatable_length{left:185px !important;}
	#adhoc_datatable tbody td{font-size:11px !important;}

	</style>

	<!-- for datatable spinner-->
	<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>

	<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

	<!-- for download spinner-->

	<div id="loading_ar" class="loading_ar">Loading&#8230;</div>

	<!--
	<svg class="spinner" viewBox="0 0 50 50">
	  <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
	</svg>
	-->

		<table id="adhoc_datatable" class="table table-striped table-bordered table-hover" width="100%">
			<thead>
				<tr style="display:none;">
					<?php
						$js_columns = "";
						$tds = "";
						//$js_columns .= "{ data: 'ID' },";
						array_push($select_fields);
						foreach ($select_fields as $field) {

							$js_columns .= "{ data: '$field' },";

							$tds .= "<td>".$field."</td>";
					?>
							<th class="hasinput">
								<input type="text" class="form-control" />
							</th>
							<!--<th><?php //echo $field?></th>-->
					<?php
						}
					?>
				</tr>

				<?php
					echo "<tr>".$tds."</tr>";
				?>
			</thead>
			<tbody>

			</tbody>
		</table>

<!-- end widget grid -->

<script type="text/javascript">

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

var pagefunction = function() {

				//$('#example').DataTable();

				var select_fields_str = encodeURIComponent('<?php echo $select_fields_str;?>');
				var select_val_str = encodeURIComponent('<?php echo $select_val_str;?>');

				//var select_fields_str = "";

				var otable = $("#adhoc_datatable").DataTable( {

					'processing': false,
					'serverSide': true,
					'deferRender': true,

					"drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},

					"lengthMenu": [[10, 50, 100], [10, 50, 100]],
					"pageLength": 50,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": true,
					"paging": true,

					//"scrollX":true,

					"dom": 'Blfrtip',
					//"dom": 'Blfrtip',

					/*
					"ajax": {
					   "url": 'assets/ajax/adhoc-report-ajax.php?select_fields='+select_fields_str,
					   "type": 'POST',

					   //"select_fields": '<?php //echo $select_fields_str;?>',
				    },
					*/

					"ajax": {
					   "url": 'assets/ajax/adhoc-report-ajax.php?select_fields='+select_fields_str+'&f='+select_val_str,
					   "type": 'POST',

					   //"select_fields": '<?php //echo $select_fields_str;?>',
				    },


					//'ajax': 'assets/ajax/adhoc-report-ajax.php?select_fields=<?php echo $select_fields_str;?>',

					columnDefs: [{
						"defaultContent": "-",
						"targets": "_all"
					}],

					'columns': [
					/*
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false,
						searchable: false,
					 },
					 */
					 <?php
						echo $js_columns;
					 ?>

					]
					,
					/*
					"buttons": [
						//'copyHtml5',
						{
							'extend': 'copyHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'excelHtml5',
						{
							'extend': 'excelHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						//'csvHtml5',
						{
							'extend': 'csvHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'extend': 'pdfHtml5',
							exportOptions: { columns: ':visible:not(:first-child)' },
							'title' : 'Vervantis_PDF',
							'messageTop': 'Vervantis PDF Export',

						},
						//'pdfHtml5'
						{
							'extend': 'print',
							//'title' : 'Vervantis',
							'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
							//'columns': ':visible:not(:first-child)'
							exportOptions: { columns: ':visible:not(:first-child)' }
						},
						{
							'text': 'Columns',
							'extend': 'colvis',
							//'columns': ':visible:not(:first-child)'
							////columns: ':gt(1)'
						},


					],
					*/


					/*
					"buttons": [

						//'excelHtml5',
						////{
							////'extend': 'excelHtml5',



							//exportOptions: { columns: ':visible:not(:first-child)' }
						////},


						//'csvHtml5',
						{
							'extend': 'csvHtml5',

							action: function (e, dt, node, config)
							{

								window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvall';
							},
							//exportOptions: { columns: ':visible:not(:first-child)' }
						},





					],
					*/

					"buttons": [{
						extend: 'collection',
						text: 'Export',
						buttons: ['export',
<?php if(1==2){ ?>
							{ //extend: 'csv',
								text: 'Export All To CSV',              //Export all to CSV file
								/*
								action: function (e, dt, node, config)
								{
									window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvall';
								}
								*/
								action: function ( e, dt, node, config ) {
									$('#loading_ar').show();
									//alert( 'Button activated' );
									/*$.get("assets/ajax/adhoc-report-ajax-csv.php?export=csvall", function(data, status){
										$('#loading_ar').hide();
										//alert("Data: " + data + "\nStatus: " + status);
										downloadit(data);
										//alert("Please check your notifications section to download Adhoc csv file");
								    });*/
									//Window=window.open('assets/ajax/adhoc-report-ajax-csv.php?export=csvall','_blank');//window.setTimeout(function(){Window.close();},1000);
									
									$.ajax({
									 type: "POST",
									 url: 'assets/includes/adhoc_aws.inc.php',
									 data: {export: 'csvall'},
									 dataType: 'json',
									 success: function(data){
										$('#loading_ar').hide();
										if(data==false){
											alert("Error occured. Please try after sometimes.");
										}else{
											if(data["error"]=="" && data["url"] !=""){
												download(data["url"]);
											}else if(data["error"]==99){
												alert("Total records exceed 1 million. Please adjust the filters to reduce the number of records.");
											}else if(data["error"]==88){
												alert("Nothing to show.");
											}else{ 
												alert("Error occured. Please try after sometimes.");
											}
										}
									 //console.log(data);
									 },
									 error: function(xhr, status, error){
										 $('#loading_ar').hide();
									 //console.error(xhr);
										alert("Error occured. Please try after sometimes.");
									 }
									});
									
								}

								/*
								customize: function (csv) {
									alert('custom');
								  //adhoc_datatable_length
								  $('#loading_ar').show();

								  // ... put your code...
								  ////window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvall';
								  ////window.location.href = 'assets/includes/sql2link.inc.php?export=csvall';
								  $.get("assets/ajax/adhoc-report-ajax-csv.php?export=csvall", function(data, status){
									alert("Data: " + data + "\nStatus: " + status);

								  });



								  if (window) {
									// ...will check for download window
									var exp_interval = setInterval(function () {
										console.log("export report=");
										let export_cookie = getCookie('adhoc_export_status');
										console.log(export_cookie);

										if (export_cookie == 'done') {
											console.log('clear interval');
											$('#loading_ar').hide();
											clearInterval(exp_interval);
										}
									}, 1000);

								  }


								}
								*/
							},
<?php } ?>
							{ //extend: 'csv',
								text: 'Export to CSV',              //Export filter records to CSV file
								/*
								action: function (e, dt, node, config)
								{
									window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvfilter';
								}
								*/

								action: function ( e, dt, node, config ) {
									$('#loading_ar').show();
									/* //alert( 'Button activated' );
									$.get("assets/ajax/adhoc-report-ajax-csv.php?export=csvfilter", function(data, status){
										$('#loading_ar').hide();
										//alert("Data: " + data + "\nStatus: " + status);
										downloadit(data);
										//alert("Please check your notifications section to download Adhoc csv file");
								    });*/
									//Window=window.open('assets/ajax/adhoc-report-ajax-csv.php?export=csvfilter','_blank');//window.setTimeout(function(){Window.close();},1000);
									
									
									$.ajax({
									 type: "POST",
									 url: 'assets/includes/adhoc_aws.inc.php',
									 data: {export: 'csvfilter'},
									 dataType: 'json',
									 success: function(data){
										$('#loading_ar').hide();
										if(data==false){
											alert("Error occured. Please try after sometimes.");
										}else{
											if(data["error"]=="" && data["url"] !=""){
												download(data["url"]);
											}else if(data["error"]==99){
												alert("Total records exceed 1 million. Please adjust the filters to reduce the number of records.");
											}else if(data["error"]==88){
												alert("Nothing to show.");
											}else{ 
												alert("Error occured. Please try after sometimes.");
											}
										}
									 //console.log(data);
									 },
									 error: function(xhr, status, error){
										 $('#loading_ar').hide();
									 //console.error(xhr);
										alert("Error occured. Please try after sometimes.");
									 }
									});	
									
								}

								/*
								customize: function (csv) {

								  //adhoc_datatable_length
								  $('#loading_ar').show();

								  // ... put your code...
								  window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvfilter';

								  if (window) {
									// ...will check for download window
									var exp_interval = setInterval(function () {
										//console.log("export report=");
										let export_cookie = getCookie('adhoc_export_status');
										console.log(export_cookie);

										if (export_cookie == 'done') {
											console.log('clear interval');
											$('#loading_ar').hide();
											clearInterval(exp_interval);
										}
									}, 1000);

								  }
								}
								*/

							},
<?php if(1==2){ ?>
							{ extend: 'excel',
								text: 'Export All To Excel',
								//Export filter records to excel file
								/*
								action: function (e, dt, node, config)
								{
									window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=excelall';
								}
								*/
								customize: function (excel) {

								  //adhoc_datatable_length
									$('#loading_ar').show();

								  // ... put your code...
								  //window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=excelall';
								  //Window = window.open("assets/ajax/adhoc-report-ajax-csv.php?export=excelall", '_blank');
								 // Window=window.open('assets/ajax/adhoc-report-ajax-csv.php?export=excelall','_blank');window.setTimeout(function(){Window.close();},1000);
									
									$.ajax({
									 type: "POST",
									 url: 'assets/includes/adhoc_aws.inc.php',
									 data: {export: 'excelall'},
									 dataType: 'json',
									 success: function(data){
										$('#loading_ar').hide();
										if(data==false){
											alert("Error occured. Please try after sometimes.");
										}else{
											if(data["error"]=="" && data["url"] !=""){
												download(data["url"]);
											}else{ 
												alert("Error occured. Please try after sometimes.");
											}
										}
									 //console.log(data);
									 },
									 error: function(xhr, status, error){
										 $('#loading_ar').hide();
									 //console.error(xhr);
										alert("Error occured. Please try after sometimes.");
									 }
									});





									//window.open("assets/ajax/adhoc-report-ajax-csv.php?export=excelall",'Download','width=200,height=200');

								  /*if (window) {
									// ...will check for download window
									var exp_interval = setInterval(function () {
										//console.log("export report=");
										let export_cookie = getCookie('adhoc_export_status');
										console.log(export_cookie);

										if (export_cookie == 'done') {
											console.log('clear interval');
											$('#loading_ar').hide();
											clearInterval(exp_interval);
										}
									}, 1000);

								  }*/
								}
							},


							{ extend: 'excel',
								text: 'Filtered Records To Excel',              //Export filter records to excel file
								/*
								action: function (e, dt, node, config)
								{
									window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=excelfilter';
								}
								*/
								customize: function (excel) {

								  //adhoc_datatable_length
								  $('#loading_ar').show();

								  // ... put your code...
								  //window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=excelfilter';
								 // Window = window.open("assets/ajax/adhoc-report-ajax-csv.php?export=excelfilter","_blank");
								  //Window =window.open('assets/ajax/adhoc-report-ajax-csv.php?export=excelfilter','_blank');window.setTimeout(function(){Window.close();},1000);
								  //window.open("assets/ajax/adhoc-report-ajax-csv.php?export=excelfilter",'Download','width=200,height=200');
								  
									$.ajax({
									 type: "POST",
									 url: 'assets/includes/adhoc_aws.inc.php',
									 data: {export: 'excelfilter'},
									 dataType: 'json',
									 success: function(data){
										$('#loading_ar').hide();
										if(data==false){
											alert("Error occured. Please try after sometimes.");
										}else{
											if(data["error"]=="" && data["url"] !=""){
												download(data["url"]);
											}else{ 
												alert("Error occured. Please try after sometimes.");
											}
										}
									 //console.log(data);
									 },
									 error: function(xhr, status, error){
										 $('#loading_ar').hide();
									 //console.error(xhr);
										alert("Error occured. Please try after sometimes.");
									 }
									});								  
								  
								  
								  

								  /*if (window) {
									// ...will check for download window
									var exp_interval = setInterval(function () {
										//console.log("export report=");
										let export_cookie = getCookie('adhoc_export_status');
										console.log(export_cookie);

										if (export_cookie == 'done') {
											console.log('clear interval');
											$('#loading_ar').hide();
											clearInterval(exp_interval);
										}
									}, 1000);

								  }*/
								}
							},
<?php } ?>

							/*
							{ extend: 'csv',
								text: 'EXPORT TEST',

								customize: function (csv) {

								  //console.log('start loader here');

								  //$(".buttons-csv").attr('disabled', true);
								  //adhoc_datatable_length
								  $('#loading_ar').show();

								  // ... put your code...
								  window.location.href = 'assets/ajax/adhoc-report-ajax-csv.php?export=csvall';

								  if (window) {
									// ...will check for download window

									//setInterval({ console.log("export report="); }, 400);
									var exp_interval = setInterval(function () {
										console.log("export report=");
										let export_cookie = getCookie('adhoc_export_status');
										console.log(export_cookie);

										if (export_cookie == 'done') {
											console.log('clear interval');
											$('#loading_ar').hide();
											clearInterval(exp_interval);
										}
									}, 1000);

									//console.log('stop loader here');
									//$(".buttons-csv").attr('disabled', false);
									//$('#adhoc_datatable_length').show();
								  }
								}
							},
							*/


							/*
							'csv', 'pdf', { extend: 'excel',
								text: 'Export Current Page',            //Export to Excel only the current page and highlight the first row as headers
								exportOptions: {
									modifier: {
										page: 'current'
									}
								},
								customize: function (xlsx)
								{
									var sheet = xlsx.xl.worksheets['sheet1.xml'];
									$('row:first c', sheet).attr('s', '7');
								}
							*/
						]
					}
					,
					{
						'text': 'Columns',
						'extend': 'colvis'
					}

					],



					/*
					buttons: [
						//'copyHtml5',
						'excelHtml5',
						'csvHtml5',
						'pdfHtml5'
					],
					*/

					//dom: 'Bfrtip',
					//"searching": true,
					//"lengthChange": false,

					// drawCallback is defined above
					//"drawCallback": function( settings ) {
						//alert( 'DataTables has redrawn the table' );
						//otable.columns.adjust().draw();
					//},

					"initComplete": function (settings, json) {
						//$('#adhoc_datatable').DataTable().columns.adjust();
						//$('#adhoc_datatable_wrapper').css( 'display', 'block' );
						//otable.columns.adjust().draw();
						$('#wid-id-3').css({'width': '100%'});

						var child_width= $('#adhoc_datatable').width();
						//then apply this to parent.
						$('#wid-id-3').css({'width': child_width});

						//width: calc(100% - 80px);

						//$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
						//$("#adhoc_datatable").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
					},


				});


				/*
				$.fn.dataTable.ext.buttons.export =
				{
					className: 'buttons-alert',
					"text": "Export All Test",
					action: function (e, dt, node, config)
					{
						var SearchData = table.search();
						var OrderData = table.order();
						alert("Test Data for Searching: " + SearchData);
						alert("Test Data for Ordering: " + OrderData);
					}
				};
				*/

				/*
				$.fn.dataTable.ext.buttons.export =
				{
					className: 'buttons-alert',
					id: 'ExportButton',
					text: "Export All Test III",
					action: function (e, dt, node, config)
					{
						alert('here2');
						var SearchData = dt.rows({ filter: 'applied' }).data();
						var OrderData = dt.order();
						alert("Test Data for Searching: " + SearchData);
						alert("Test Data for Ordering: " + OrderData);
					}
				};
				*/

				// Apply the filter
				/*
				$("#adhoc_datatable .sdrp").on( 'keyup change', function () {
					otable
						.column( $(this).parent().index()+':visible' )
						.search( this.value )
						.draw();
				});
				*/

				// Apply the filter
				$(document.body).on('onmouseleave change', '#adhoc_datatable thead th input[type=text]' ,function(){

					otable
						.column( $(this).parent().index()+':visible' )
						//.column( $(this).parent().index() )
						.search( this.value )
						.draw();

				});


				/*var timeout;
				$(document.body).on('keyup change', '#adhoc_datatable thead th input[type=text]' ,function(){
					if(timeout) {
							clearTimeout(timeout);
							timeout = null;
					}
					timeout = setTimeout(filterout(otable,$(this)), 5000);
				});*/


				/*var timeout;
				$(document.body).off('keyup change', '#adhoc_datatable thead th input[type=text]');
				$(document.body).on('keyup change', '#adhoc_datatable thead th input[type=text]' ,function(){
					if(timeout) {
							clearTimeout(timeout);
							timeout = null;
					}
					timeout = setTimeout(filterout(otable), 5000);

				});
			*/
				function filterout(otable,tthis){
					otable
						.column( tthis.parent().index()+':visible' )
						//.column( $(this).parent().index() )
						.search( tthis.value )
						.draw();
				}



				/*
				var otable = $("#ei_datatable_fixed_column").DataTable( {

					'processing': false,
					'serverSide': true,
					'serverMethod': 'post',
					'deferRender': true,
					'ajax': {
					   'url':'assets/ajax/adhoc-report-ajax.php'
					},

					"drawCallback" : function(settings) {
						 $(".dots-cont").hide();
					},
					"preDrawCallback": function (settings) {
						$(".dots-cont").show();
					},



					//'select': true,
					'select': {
						style:    'os',
						//style: 'single',
						selector: 'td:first-child'
					},
					//'stateSave': true,

					"order": [[ 1, 'asc' ]],


					'columns': [
					 {
						data: null,
						defaultContent: '',
						className: 'select-checkbox',
						orderable: false,
						searchable: false,
					 },
					 <?php
						echo $js_columns;
					 ?>

					]
					,



					"lengthMenu": [[12, 25, -1], [12, 25, "All"]],
					"pageLength": 12,
					"retrieve": true,
					"scrollCollapse": true,
					"searching": false,
					"paging": true,
					"dom": 'Blfrtip',


					"autoWidth" : true


				});

				*/


}

				loadScript("assets/plugins/datatables1.11.3/datatables.min.js", function(){
				 //loadScript("assets/js/dataTables.editor.min.js", function(){
					pagefunction();
					 //});
				 });
				 
	function download(url){
		$('<iframe>', { id:'idown', src:url }).hide().appendTo('body').click();
	}

</script>
