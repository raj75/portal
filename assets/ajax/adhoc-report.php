<?php require_once("inc/init.php"); ?>
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];

?>

<!--<link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css" />-->
<!--<link type="text/css" href="assets/css/ui.multiselect.css" rel="stylesheet" />-->

<link type="text/css" href="assets/css/jquery.uix.multiselect.css" rel="stylesheet" />

<style>

	
	/* multiselect styles */
	
	.multiselect {
		width: 760px;
		/*height: 450px !important;*/
		height: 480px !important;
	}
	
	/*
	.uix-multiselect{
		width: 760px;
		height: 450px !important;
	}
	*/
	#switcher {
		margin-top: 20px;
	}
	
	.multi_header{background-color:#4c4f53; color:#fff; /*text-align:center;*/ width:760px; padding:6px; font-size:15px;}
	
	.ui-widget-header{background:none; background-color:#e1d5d5;}
	
	.ui-multiselect .available .actions, .ui-multiselect .selected .actions {color:#000; /*text-align:center;*/ padding:6px; font-size:13px;}
	
	/*.ui-multiselect ul.available li , .ui-multiselect ul.selected li {font-size:14px; height: 30px; padding-top:4px; border-bottom:1px solid #e1d5d5;}*/
	
	.uix-multiselect .ui-state-default {font-size:14px; height: 30px; padding-top:4px; border-bottom:1px solid #e1d5d5; padding-left: 10px;}
	
	.ui-state-highlight, .ui-widget-content .ui-state-highlight, 
	.ui-widget-header .ui-state-highlight {color:#333; background-color: #fff; border-color: #e1d5d5;}
	.uix-multiselect .ui-state-default {border-top:0px;}
	.uix-multiselect .header-text{padding:5px; padding-left:10px;}
	
	.uix-control-right {margin-top:5px;}
	.uix-search {margin-top:5px; height:20px !important;}
	
	/*
	button[data-localekey="search"] {background-image: url(http://code.jquery.com/ui/1.9.2/themes/base/images/ui-icons_222222_256x240.png); background-position: -160px -112px;}
	*/
	
	button[data-localekey="search"] {width:20px !important; height:20px !important; position:relative; margin-right:3px;}
	
	button[data-localekey="search"] .fa {position:absolute; top:1px; left:2px; color:#6D6A69; }
	
	button[data-localekey="selectAll"] .fa {position:absolute; top:0px; left:4px; color:#6D6A69; font-size: 15px; font-weight: bold; }
	
	button[data-localekey="deselectAll"] .fa {position:absolute; top:0px; left:2px; color:#6D6A69; font-size: 15px; font-weight: bold; }
	
	.uix-control-right{width:20px !important; height:20px !important; position:relative;}
	
	.uix-search{width:288px !important;}
	
	/*.uix-control-right{display:none;}*/
	
	.ui-multiselect li a.action {top: 5px; right: 15px;}
	
	ul.connected-list{border-right:2px solid #e1d5d5 !important;}
	
	.multi_header span {padding-left:10px;}
	
	.multi_header i {font-size:12px;}
	
	.ui-icon-plus-ar {width:16px; height:16px; display:inline;}

	/*.option-selected i {margin-right:5px;}*/
	
	.option-selected .glyphicon {margin-right:5px; font-size: 11px; font-weight: normal;}
	
	.multiselect-available-list .option-element .fa-plus {margin-right:20px; font-size: 11px; float:right; margin-top: 6px;}
	
	
	.ui-draggable-dragging .fa-plus{
	   display:none;
	}
	
	.ui-sortable-helper .fa-minus{
	   display:none;
	}


	.multiselect-selected-list .option-element .fa-minus {margin-right:20px; font-size: 11px; float:right; margin-top: 5px;}
	
	.filters_div {/*height:482px;*/ height:513px; border:1px solid #aaa; background-color:#fff;}

	.full_width_filter{width:100%;}
	
	.select_filters{height:32px; padding:6px 5px 5px 15px; border-bottom:1px solid #aaa;}
	
	.ar_article{padding-left:0px; padding-right:0px; padding-bottom:25px;}
	
	#query_area{width:772px;}
	
	.filter_container {width: calc(100% - 800px); float:left; margin-left:20px;}
	
	.select_container {width: 760px; float:left; position: relative;}
	
	.select_container .bottom_border {position:absolute; height: 1px; bottom: -1px; width:100%; border-bottom: 1px solid #aaa;}
	
	@media only screen and (max-width: 900px) {
		
	}
	
	@media screen and (max-device-width:1200px), screen and (max-width:1200px) {
		.filter_container {
			width: 100% !important;
			float:unset;
			clear:both;
			margin-left:0px;
			padding-top:20px;
		}
		
		.select_container {
			width: 100% !important;
			float:unset;
			clear:both;
		}
	}
	
	.multiselect-available-list .multiselect-element-wrapper {
		position:relative;
	}
	
	.multiselect-available-list .multiselect-element-wrapper #overlay_ar {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #000;
		filter:alpha(opacity=50);
		-moz-opacity:0.2;
		-khtml-opacity: 0.2;
		opacity: 0.2;
		z-index: 10000;
	}
	
</style>


<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="glyphicon glyphicon-stats "></i> 
				Energy Accounting <span>> Ad Hoc Report Sample</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		
		<!--
		<article class="col-sm-12">
			
			<ul id="sortable1">
			  <li class="ui-state-default">Item 1</li>
			  <li class="ui-state-default ui-state-disabled">(I&apos;m not sortable or a drop target)</li>
			  <li class="ui-state-default ui-state-disabled">(I&apos;m not sortable or a drop target)</li>
			  <li class="ui-state-default">Item 4</li>
			</ul>
			 
			<h3 class="docs">Cancel sorting (but keep as drop targets):</h3>
			 
			<ul id="sortable2">
			  <li class="ui-state-default">Item 1</li>
			  <li class="ui-state-default ui-state-disabled">(I&apos;m not sortable)</li>
			  <li class="ui-state-default ui-state-disabled">(I&apos;m not sortable)</li>
			  <li class="ui-state-default">Item 4</li>
			</ul>

			
			
		</article>
		-->
		<!-- WIDGET END -->
	</div>
	<!-- end row -->

</section>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>-->
<script>//var $jQuery151 = $.noConflict(true);</script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>-->

<!--<script type="text/javascript" src="assets/js/ui.multiselect19.js"></script>-->

<section id="widget-grid" class="">
<form id="query_form" type="post">
<div class="row">

	<div class="select_container col-md-12- col-lg-6- col-xl-6-">
	
		<div class="multi_header"><i class="glyphicon glyphicon-th-list "></i> <span>FIELD SELECTION</span></div>
		<select id="table_fields" class="multiselect" multiple="multiple" name="table_fields[]">
		
		  <?php
			//SELECT COLUMN_NAME from information_schema.columns where table_name = 'adhoc'
			$adhoc_columns= $mysqli->prepare("SELECT COLUMN_NAME from information_schema.columns where table_name = 'adhoc'");
			//$adhoc_columns= $mysqli->prepare("SELECT COLUMN_NAME from information_schema.columns where table_name = 'adhoc' AND COLUMN_NAME like '%city%'");
				$adhoc_columns->execute();
				$adhoc_columns->store_result();
				if ($adhoc_columns->num_rows > 0) {
					$adhoc_columns->bind_result($column_name);
					while($adhoc_columns->fetch()){
						$col_val = str_replace(" ","_",$column_name);
						//echo "<option value='$col_val'>$column_name</option>";
						echo "<option value='$column_name'>$column_name</option>";
						//echo "column_name=".$column_name;
						//$datearr[]=array($datelist,$datedata);
					}
				}
			
		  ?>
		  
		  <!--
		  <option value="City">City</option>
		  <option value="Close Date">Close Date</option>
		  <option value="Delivery Address">Delivery Address</option>
		  <option value="DIVISION">DIVISION</option>
		  <option value="GL LOCATION TYPE">GL LOCATION TYPE</option>
		  <option value="GL SITE #">GL SITE #</option>
		  <option value="LOCATION TYPE">LOCATION TYPE</option>
		  <option value="Open Date">Open Date</option>
		  
		  <option value="Primary Contact Email">Primary Contact Email</option>
		  <option value="Primary Contact Fax">Primary Contact Fax</option>
		  <option value="Primary Contact Job Title">Primary Contact Job Title</option>
		  <option value="Primary Contact Name">Primary Contact Name</option>
		  <option value="Primary Contact Phone">Primary Contact Phone</option>
		  <option value="REGION">REGION</option>
		  <option value="Secondary Address">Secondary Address</option>
		  <option value="Secondary Contact Email">Secondary Contact Email</option>
		  <option value="Secondary Contact Fax">Secondary Contact Fax</option>
		  <option value="Secondary Contact Job Title">Secondary Contact Job Title</option>
		  <option value="Secondary Contact Name">Secondary Contact Name</option>
		  <option value="econdary Contact Phone">Secondary Contact Phone</option>
		  <option value="Site">Site</option>
		  <option value="Site #">Site #</option>
		  <option value="Average Square Foot">Average Square Foot</option>
		  <option value="Square Foot Effective Period">Square Foot Effective Period</option>
		  <option value="St">St</option>
		  <option value="TAX ID">TAX ID</option>
		  <option value="Weather Station">Weather Station</option>
		  <option value="Zip">Zip</option>
		  <option value="Amount Paid">Amount Paid</option>
		  
		  <option value="Balance Forward">Balance Forward</option>
		  <option value="Budget Billing Amount">Budget Billing Amount</option>
		  <option value="Check Number">Check Number</option>
		  
		  <option value="Date Check Cleared">Date Check Cleared</option>
		  <option value="Due Date">Due Date</option>
		  <option value="Entered Date">Entered Date</option>
		  <option value="Funds Received Date">Funds Received Date</option>
		  <option value="Invoice #">Invoice #</option>
		  <option value="Invoice Total Due">Invoice Total Due</option>
		  <option value="Late Fees">Late Fees</option>
		  <option value="Notes">Notes</option>
		  <option value="Notes Description">Notes Description</option>
		  <option value="Notified Date">Notified Date</option>
		  <option value="Paid Date">Paid Date</option>
		  <option value="Payment Type">Payment Type</option>
		  <option value="Period">Period</option>
		  <option value="Scanned Date">Scanned Date</option>
		  <option value="Service Days">Service Days</option>
		  <option value="Service From Date">Service From Date</option>
		  <option value="Service To Date">Service To Date</option>
		  
		  <option value="Status">Status</option>
		  <option value="Valid Date">Valid Date</option>
		  <option value="Vendor Invoice #">Vendor Invoice #</option>
		  <option value="Void Check Date">Void Check Date</option>
		  <option value="Billed Demand">Billed Demand</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  <option value="Amount">Amount</option>
		  
		  -->
		   
		  
		</select>
		
		
		<div class="bottom_border"></div>
	</div>
	
<!--------------filters---------------------------------->

	<div class="filter_container col-md-12- col-lg-6- col-xl-6-">
	
		<div class="filters_div">
			<div class="multi_header full_width_filter"><i class="glyphicon glyphicon-filter"></i> <span>FILTERS</span></div>
			<div class="ui-widget-header ui-corner-tr select_filters" >Select Following Filters</div>
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>From Month</b></label>
					<br>
					<select name="from_month" id="from_month">
						<option value="01">January</option>
					</select>
				</div>
				<div class="" style="padding-left:5px; float:left;">
					<label><b>Year</b></label>
					<br>
					<select name="from_year" id="from_year">
						<option value="2011">2011</option>
					</select>
				</div>
				
				<div class="" style="padding-left:15px; float:left">
					<label><b>To Month</b></label>
					<br>
					<select name="to_month" id="to_month">
						<option value="12">December</option>
					</select>
				</div>
				<div class="" style="padding-left:5px; float:left;">
					<label><b>Year</b></label>
					<br>
					<select name="to_year" id="to_year">
						<option value="2022">2022</option>
					</select>
				</div>
				<div class="col-md-2"></div>
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Services</b></label>
					<br>
					<select name="Service">
						<option value=''>All Services</option>
						<option>Water</option>
					</select>
				</div>
				
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Account Status</b></label>
					<br>
					<select name="Status">
						<!--<option value="">All Accounts</option>-->
						<option>Live</option>
					</select>
				</div>
				
				<div class="" style="padding-left:15px; float:left">
					<label><b>Site Status</b></label>
					<br>
					<select name="Site Status">
						<option value="">Live</option>
					</select>
				</div>
				
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Site</b></label>
					<br>
					<select name="Site">
						<option>FLORLAN1880</option>
					</select>
				</div>
				
				<div class="" style="padding-left:15px; float:left;">
					<label><b>Vendor</b></label>
					<br>
					<select name="Vendor">
						<option>ORLANDO UTILITIES COMMISSION</option>
					</select>
				</div>
				
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Account</b></label>
					<br>
					<select name="Account #">
						<option>6987500001-1880SSEMORANBLVD</option>
					</select>
				</div>
				
				<div class="" style="padding-left:15px; float:left">
					<label><b>State</b></label>
					<br>
					<select name="St">
						<option>FL</option>
					</select>
				</div>
				
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<label><b>Period Type</b></label>
					<br>
					<select name="Period Type">
						<option value="">Invoice</option>
						<option value="">Calendar</option>
					</select>
				</div>
			</div>
			
			<div class="row" style="padding-left:15px;">
				<br>
				<div class="" style="padding-left:15px; float:left">
					<button type="submit" class="btn-primary" id="create_query">Create Query</button>

				</div>
			</div>
			
		</div>
	</div>
</form>
</section>

<!-- Query part -->

<section id="widget-grid" class="">

<div class="row text-center" >
	<br><br>
	<div class="col-md-6 " style="float:none;">
		<div class="multi_header full_width_filter"><i class="glyphicon glyphicon-filter"></i> <span>QUERY</span></div>
		<textarea id="query_area" name="query_area" rows="10" cols="100"></textarea>
	</div>

</div>

</section>


<br>

<!-- NEW WIDGET START -->
		<section id="widget-grid2" class="sitestable m-top45 ">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>Adhoc Report Records </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding" id="adhoc-datatable-load">

								

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>
		




<script type="text/javascript">

	pageSetUp();
	/*
	var company_arr_js = <?php echo json_encode( $company_arr ); ?>;
	*/
	
	var _initfunc = function() {
		console.log('_initfunc');
		  //alert('here111');
		//alert($(".multiselect-available-list .option-element").html());
		//alert($('.multiselect-available-list .option-element').length);
		//$(".multiselect-available-list .multiselect-element-wrapper div div").each(function() {
			//alert('here');
			//console.log('here');
			//you can use this to access the current item
		//});
		
		var num_ar = 0;

		$('.multiselect-available-list .option-element div').each(function(){
			$(this).html($(this).text()+'<i class="fa fa-plus" aria-hidden="true"></i>');
			//console.log(num_ar);
			//$(this).html('<span class="glyphicon glyphicon-resize-vertical"></span>'+$(this).html());
			//num_ar++;
		});
		//alert(num_ar);
		
		// add sort icon on selected options
		$('.multiselect-selected-list .option-element div').each(function(){
			//console.log(num_ar);
			
			$(this).html('<span class="glyphicon glyphicon-resize-vertical"></span>'+$(this).text()+'<i class="fa fa-minus" aria-hidden="true"></i>');
			num_ar++;
		});
		
	};
	
	var _mycallback = function() {
		alert('call');
	}
	
	//-------select all------------
	var _selectallcallback = function() {
		//alert('call');
		$('.multiselect-selected-list .option-element div').each(function(){
			
			$(this).html('<span class="glyphicon glyphicon-resize-vertical"></span>'+$(this).text()+'<i class="fa fa-minus" aria-hidden="true"></i>');
			//num_ar++;
		});
	}
	
	//------deselect all----------
	var _deselectallcallback = function() {
		//alert('call');
		
		$('.multiselect-available-list .option-element div').each(function(){
			
			$(this).html($(this).text()+'<i class="fa fa-plus" aria-hidden="true"></i>');
			//num_ar++;
		});
	}
	
	//-----get all data of form--------------
	
	var select_fields;
	$("#query_form").submit(function (e) {
		e.preventDefault();
		//var select_fields;
		//var qry_where = "1=1 ";
		var qry_where = "";
		$('#query_form input, #query_form select, #query_form textarea').each(
			function(index){
				var input = $(this);
				var input_name = input.attr('name');
				var input_val = input.val();				
					
					// SELECT part
					if (input_name == 'table_fields[]') {
						select_fields = input_val;
						
						if (select_fields == "") {
							select_fields = "*";
						} else {
							input_val_new = input_val.toString().replaceAll("," , "` , `");
							select_fields = '`' + input_val_new + '`';
						}
						//continue;
						return true;
					}
					
					if (input_val == '') {
						return true;
					}
					
					if (input_name == 'from_month' || input_name == 'from_year' || input_name == 'to_month' || input_name == 'to_year') {
						//qry_btw = ;
						return true;
					}
					
					// WHERE part
					qry_where = qry_where + " AND `" + input_name + "` = '" + input_val +"'";
					
				//alert('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
				console.log('-----------------------------------------');
				//console.log('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
				console.log('Name: ' + input.attr('name') + '--Value: ' + input.val());
				
			}
			
			
		); // end of each
		
		var qry_btw = '';
		
		var from_month = $('#from_month').val();
		var from_year = $('#from_year').val();
		var to_month = $('#to_month').val();
		var to_year = $('#to_year').val();
		
		//qry_btw = ' AND Period between ' + from_year + from_month + ' AND ' + to_year + to_month;
		//convert(concat(Period,'01'),DATE)
		qry_btw = ' AND convert(concat(Period,"01"),DATE) between "' + from_year + '-' + from_month + '-01" AND "' + to_year + '-' + to_month + '-01"' ;
		
		// set value of query text area
		//$('#query_area').val('Select ' + select_fields + ' from Adhoc WHERE ' + qry_where + qry_btw);
		$('#query_area').val('Select ' + select_fields + ' from Adhoc WHERE 1=1 ' + qry_btw + qry_where + ' limit 10000');
			//$("#query_form");
		
		load_datatable();
		
		return false;
	});
	
	
	/*
	$("#query_form").submit(function (e) { 
	  e.preventDefault();
	  inputs={};
	  input_serialized =  $(this).serializeArray();
	  input_serialized.forEach(field => {
		inputs[field.name] = field.value;
	  })
	  console.log(inputs)
	});
	*/
	
	/*
	$("#query_form").submit(function (event) {
	
		
			var values = $(this).serializeArray();
			// In my case, I need to fetch these data before custom actions
			event.preventDefault();

		
		var inputs = {};
		$.each(values, function(k, v){
			inputs[v.name]= v.value;
		});
		console.log(inputs);
	});
	*/

	//--------------------------------------
	
	// pagefunction
	var pagefunction = function() {
		
		var defaultOptions = {
			availableListPosition: 'left',
			//moveEffect: 'blind',
			moveEffect: null,
			/*moveEffectOptions: {direction:'vertical'},*/
			moveEffectSpeed: 'fast',
			//moveEffectSpeed: 'slow',
			selectionMode: 'click,d&d',
			//sortable: true,
			sortable: true,
			created:_initfunc,
			
		};
				
		$(".multiselect").multiselect(defaultOptions);
		
		//$(".multiselect").multiselect('refresh',_mycallback); //working
		
		// add search icon in search button
		
		$("button[data-localekey='search']").html(function(){
			return '<i class="fa fa-search" aria-hidden="true"></i>';
		});
		
		$("button[data-localekey='deselectAll']").html(function(){
			return '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
		});
		
		$("button[data-localekey='selectAll']").html(function(){
			return '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
		});
		
		
		$(".multiselect").bind('multiselectChange', function(evt, ui) {
			//return;
			//alert('change');
		  //alert($this.html());
		  //alert($(this).html()); // gives <options> html
		  //alert($(this).html());
		  //console.log(this);
		  //console.log(ui.optionElements[0]);
		  //console.log('selected==');
		  //console.log(ui.selected);
		  //console.log('evt');
		  //console.log(evt);
		  console.log(ui);
		  
		  console.log(ui.optionElements[0].index);
		  
		  var ind_selected = ui.optionElements[0].index;
		  
		  
		  if (ui.selected==true) {
			  //console.log("ind_selected=="+ind_selected);
			  //multiselect-selected-list
			  //$(".option-selected:eq("+ind_selected+")").perpend("<span>+</span>");
			//console.log('selected');
			var selected_txt = ui.optionElements[0].innerText;
			
			// check avaialabe list
			var avil_len =  $( ".multiselect-available-list .option-element:visible" ).length ;
			////var selected_div_ar = $( ".multiselect-selected-list .option-element div:contains('"+selected_txt+"')" );
			////var selected_div_ar = $( ".multiselect-selected-list .option-element:last-child div" );
			////var selected_div_ar = $( ".multiselect-selected-list .option-element div:first .fa-plus" ).parent();
			if (avil_len == 0) {
				//var selected_div_ar = $( ".multiselect-selected-list .option-element div:first .fa-plus" ).parent();
				//var selected_div_ar = $( ".multiselect-selected-list .option-element div:last .fa-plus" ).parent();
				//var selected_div_ar = $( ".multiselect-selected-list .option-element div .fa-plus" ).eq(0).parent();
				var selected_div_ar = $( ".multiselect-selected-list .option-element div .fa-plus" ).eq(0).parent();				
			} else {				
				var selected_div_ar = $( ".multiselect-selected-list .option-element div .fa-plus" ).parent();				
			}
			  
			////$(".multiselect-available-list .option-element .glyphicon").remove();
			  
			//option-selected
		    //var selected_div_ar = $(".option-selected:eq("+ind_selected+") > div");
			
			
		  
		    ////var selected_html_ar = $(".option-selected:eq("+ind_selected+") > div").html();
			
			//var selected_html_ar = $(".option-selected:eq("+ind_selected+") > div").text();
			var selected_html_ar = selected_div_ar.text();
			
			//selected_div_ar.html('<i class="fa fa-arrows" aria-hidden="true"></i>'+selected_html_ar);
			////selected_div_ar.html('<span class="glyphicon glyphicon-resize-vertical"></span>'+selected_html_ar);
			
			selected_div_ar.html('<span class="glyphicon glyphicon-resize-vertical"></span>'+selected_html_ar+'<i class="fa fa-minus" aria-hidden="true"></i>');
			
		  } else {
			//multiselect-available-list
			// remove drag icon
			//$(".multiselect-available-list .option-element i").remove();
			////$(".multiselect-available-list .option-element .glyphicon").remove();
			
			var unselected_txt = ui.optionElements[0].innerText;
			
			// check selected list
			var unavil_len =  $( ".multiselect-selected-list .option-element" ).length ;
			
			////var unselected_div_ar = $( ".multiselect-available-list .option-element div:contains('"+unselected_txt+"')" );
			if (unavil_len > 0) {
				var unselected_div_ar = $( ".multiselect-available-list .option-element div .glyphicon" ).parent();
			} else {
				//var unselected_div_ar = $( ".multiselect-available-list .option-element div:first .glyphicon" ).parent();
				//var unselected_div_ar = $( ".multiselect-available-list .option-element div:last .glyphicon" ).parent();
				var unselected_div_ar = $( ".multiselect-available-list .option-element div .glyphicon" ).eq(0).parent();
			}
			
			$(".multiselect-available-list .option-element .glyphicon").remove();
			
			//alert(unselected_div_ar);
			//unselected_div_ar.find('.fa-minus').remove();
			
			var unselected_html_ar = unselected_div_ar.text();
			
			unselected_div_ar.html(unselected_html_ar+'<i class="fa fa-plus" aria-hidden="true"></i>');
			
			//unselected_div_ar.find('.fa-minus').remove();
			
			//cbName=cbName.replace("checkbox_", "");
			//var unselected_div_ar = $(".multiselect-available-list .option-element:eq("+ind_selected+") > div");
		  
		    //var unselected_html_ar = $(".multiselect-available-list .option-element:eq("+ind_selected+") > div").text();
			
			//unselected_div_ar.html(unselected_html_ar+'<i class="fa fa-plus" aria-hidden="true"></i>');
			
			//var old_html_ar = unselected_html_ar.replace('<i class="fa fa-arrows-v" aria-hidden="true"></i> ', "");
			//selected_div_ar.html(old_html_ar);
		  }
		  //alert($(".option-selected:eq("+ind_selected+") > div").html());
		  //alert(selected_html_ar);
		  //$(".option-selected:eq("+ind_selected+")").append("<span>+</span>");
		  //$(".option-selected:eq("+ind_selected+")").perpend("<span>+</span>");
		  // ui.optionElements: [JavaScript array of OPTION elements],
		  // ui.selected: {true|false} if the optionsElements were just selected or not
	    });
		
		//---------------select all click-----------------------
		
		$('button[data-localekey="selectAll"]').click(function() {
			//alert( "selectAll called." );
			$(".multiselect").multiselect('refresh',_selectallcallback); //working
		});
		
		//---------------deselect all click-----------------------
		
		$('button[data-localekey="deselectAll"]').click(function() {
			//alert( "deselectAll called." );
			$(".multiselect").multiselect('refresh',_deselectallcallback); //working
		});
		
		// not working multiselectselected is not present in our .js file
		/*
		$('.multiselect').bind('multiselectselected', function(event, options) {
		  // ui.option is the DOMOption node of ui.sender
		  alert('selected');
		});
		*/
		
		
		//multiselect-available-list
		//ui-state-default option-element
		//alert($('.multiselect-available-list').find('.option-element').text());
		/*
		$('#multiselect0_avListContent div .multiselect-element-wrapper div .option-element').each(function(index, obj){
		//$('.multiselect-available-list').find('.option-element').each(function() {
			alert('here');
			console.log(index);
			//you can use this to access the current item
		});
		*/
		/*
		$('.multiselect').bind('multiselectselected', function(event, options) {
			console.log('multiselectselected');
		    // ui.option is the DOMOption node of ui.sender
		});
		*/

	}
	
	//loadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js", function(){
		//loadScript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js", function(){
			//loadScript("assets/js/ui.multiselect19.js", function(){
				//pagefunction();
			//});
		 //});
	//});
	
	//loadScript("assets/js/jquery.uix.multiselect_debug.js?a=<?php //echo(microtime());?>", function(){
	//loadScript("assets/js/jquery.uix.multiselect.js?a=<?php //echo(microtime());?>", function(){
	loadScript("assets/js/jquery.uix.multiselect_ar.js?a=<?php echo(microtime());?>", function(){
		pagefunction();
	});
	 
$(document).ready(function(){
	
	//$("#create_query").click(function(){
		//$('#datatable_fixed_column').DataTable().ajax.reload();
		//otable.ajax.reload();
		//$('#response').load('assets/ajax/sites-add.php');
		//$('#adhoc-datatable-load').load('assets/ajax/adhoc-report-datatable.php?load=true&select='+select_fields);
		
		/*
		$.ajax({
		  method: "POST",
		  url: "some.php",
		  data: { name: "John", location: "Boston" }
		  cache: false
		})
		.done(function( html ) {
			$( "#adhoc-datatable" ).append( html );
		});
		*/
	//});
	
	
	
	
	//$(function(){
			//$.localise('ui-multiselect', {/*language: 'en',*/ path: 'js/locale/'});
			//$jQuery151(".multiselect").multiselect();
			//$(".multiselect").multiselect();
			//$('#switcher').themeswitcher();
		//});
		
	/*
    $( "#sortable1" ).sortable({
      items: "li:not(.ui-state-disabled)"
    });
 
    $( "#sortable2" ).sortable({
      cancel: ".ui-state-disabled"
    });
 
    $( "#sortable1 li, #sortable2 li" ).disableSelection();
	*/

});

	function load_datatable() {
		var table_fields = $("[name='table_fields[]']").val();
		//alert(table_fields);
		//$('#adhoc-datatable-load').load('assets/ajax/adhoc-report-datatable.php?load=true&select_fields='+encodeURI(table_fields));
		////$('#adhoc-datatable-load').load('assets/ajax/adhoc-report-datatable.php?load=true&select_fields='+encodeURIComponent(table_fields));
		
		//var txt = $("input").val();
		$.post("assets/ajax/adhoc-report-datatable.php?load=true", {select_fields: table_fields}, function(result){
			$("#adhoc-datatable-load").html(result);
		});
		 
	}
</script>