<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_input_vars', 10000);
 require_once("../inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

 $_SESSION["group_id"]=3;
if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");

$_SESSION["user_id"]=23;
$user_one=$_SESSION["user_id"];
?>

<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="http://www.bootstrapcdn.com//twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css">
        <style type="text/css">
            body{ padding:10px; font-size:12px; background:#F1F1F1; }
            h1{ font-size:2em; text-align:center; border-bottom:1px solid #CCC; margin-bottom:1em; }

            .selectMonths{ float:left; position:relative; display:inline-block; }
            .selectMonthsselect {height: 30px; }
            .selectMonths > i{ position:absolute; right:5px; top:5px; opacity:0.35; font-style:normal; font-size:18px; transition:0.2s; pointer-events:none; }
            .selectMonths > input{ text-transform:capitalize; padding-left:10px; cursor:default; cursor:pointer; }
            .selectMonths:hover > i{ opacity:.7; }
            .selectMonths + .selectMonths{ float:none; }

			.rangePicker.show.custom>.wrap {
				padding: 0 431px 119px 0 !important;
			}

        </style>

        <link rel="stylesheet" href="assets/css/picker.css">

         <!-- scripts -->
		 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="assets/js/tether.min.js"></script>
        <script src="assets/js/datePicker.js"></script>



        <script>

			var global_arr = new Array();
			var first_id=1;


			var lastday = function(y,m){
				return  new Date(y, m +1, 0).getDate();
			}

			function formatDate(date) {
				var d = new Date(date),
					month = '' + (d.getMonth() + 1),
					day = '' + d.getDate(),
					year = d.getFullYear();

				if (month.length < 2) month = '0' + month;
				if (day.length < 2) day = '0' + day;

				return [year, month, day].join('-');
			}
            $('.selectMonths:first input').rangePicker({ minDate:[12,2015], maxDate:[12,2020], RTL:false }).on('datePicker.done', function(e, result){

				   var data=$("#passed_sys").val();

                    if( result instanceof Array ){
						var from_date=formatDate(new Date(result[0][1], result[0][0] - 1));
						var to_date=formatDate(new Date(result[1][1], result[1][0] - 1));
						var cus_dates=from_date+'~'+to_date;

						data=data+'--'+'custom'+'--'+cus_dates;

						//loadfmap("custom",cus_dates);
                    }
                    else{
						data=data+'--'+'month'+'--'+result;

						//loadfmap("month",result);
                    }

					global_arr[first_id]=data;
					loadfmap(global_arr);
					//console.log(data);

                });
           $('.selectMonths:last input').rangePicker({ setDate:[[12,2015],[12,2020]], closeOnSelect:true });

		   $('.selectMonths input').rangePicker({ setDate:[[12,2015],[12,2020]], closeOnSelect:true });

			$('body').on('focus',".add_date_picker", function(){

				$.ajax({
				  type: "GET",
				  url: "assets/js/datePicker.js",
				  dataType: "script",
				  async: false
				});

				$('.selectMonths:first input').rangePicker({ minDate:[12,2015], maxDate:[12,2020], RTL:false }).on('datePicker.done', function(e, result){

					var data=$("#passed_sys").val();
                    if( result instanceof Array ){
						var from_date=formatDate(new Date(result[0][1], result[0][0] - 1));
						var to_date=formatDate(new Date(result[1][1], result[1][0] - 1));
						var cus_dates=from_date+'~'+to_date;

						data=data+'--'+'custom'+'--'+cus_dates;

						//loadfmap("custom",cus_dates);
                    }
                    else{
						//loadfmap("month",result);

						data=data+'--'+'month'+'--'+result;
                    }

					global_arr[first_id]=data;
					loadfmap(global_arr);
					//console.log(global_arr);
                });




		         $('.selectMonths input').rangePicker({ setDate:[[12,2015],[12,2020]], closeOnSelect:true,RTL:false }).on('datePicker.done', function(e, result){
                    var date_id=$(this).attr('id');
					var temp_arr = date_id.split("_");
					var auto_id = temp_arr[1];

					var get_symbol=$("#selectedsymbol_"+auto_id).val();

					var data=get_symbol;



					if( result instanceof Array ){

						var from_date=formatDate(new Date(result[0][1], result[0][0] - 1));
						var to_date=formatDate(new Date(result[1][1], result[1][0] - 1));
						var cus_dates=from_date+'~'+to_date;

						data=data+'--'+'custom'+'--'+cus_dates;

						//loadfmap("custom",cus_dates);
                    }
                    else{

						data=data+'--'+'month'+'--'+result;
						//loadfmap("month",result);
                    }

					global_arr[auto_id]=data;
					loadfmap(global_arr);
					//console.log(global_arr);
                });

			});

			/*$( document ).ready(function() {
				var data=$("#passed_sys").val();
						data=data+'--'+'month'+'--'+'12';
					global_arr[first_id]=data;

			});*/
        </script>

      <input type="hidden" name="passed_sys" id="passed_sys" value="<?php echo $_GET['symb']; ?>">
<?php

if(isset($_GET['symb']) and $_GET['symb'] != "")
{
	$fd_arr=array();
	$symb=$mysqli->real_escape_string($_GET['symb']);
	/*if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,`PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."' ORDER BY `CONTRACT YEAR`")) {*/
	if ($fdstmt = $mysqli->prepare("SELECT DISTINCT `PRODUCT SYMBOL`,GROUP_CONCAT(DISTINCT `PRODUCT DESCRIPTION`) AS `PRODUCT DESCRIPTION`,`CONTRACT MONTH`,`CONTRACT YEAR` FROM futures.nymex_future WHERE `PRODUCT SYMBOL` = '".$symb."'  GROUP BY `PRODUCT SYMBOL`,`CONTRACT MONTH`,`CONTRACT YEAR`  ORDER BY `CONTRACT YEAR`")) {
        $fdstmt->execute();
        $fdstmt->store_result();
        if ($fdstmt->num_rows > 0) {
			$fdstmt->bind_result($fd_symbol,$fd_pdesc,$fd_cmonth,$fd_cyear);
			//$fdstmt->bind_result($fd_symbol,$fd_cmonth,$fd_cyear);
			while($fdstmt->fetch()){
				$fd_arr[$fd_cyear][]=$fd_cmonth;
			}
				/*echo "<table>
						<tr><th>ID</th><td>$id</td></tr>
						<tr><th>Company</th><td>$Company</td></tr>
						<tr><th>Division</th><td>$Division</td></tr>
						<tr><th>Country</th><td>$Country</td></tr>
						<tr><th>State</th><td>$State</td></tr>
						<tr><th>City</th><td>$City</td></tr>
						<tr><th>Site Number</th><td>$Site_Number</td></tr>
						<tr><th>Site Name</th><td>$Site_Name</td></tr>
						<tr><th>Site Status</th><td>$Site_Status</td></tr>
					</table>";*/
		}
	}
	if(!count($fd_arr)) die("No data to show!");
	//else $fd_arr=array_unique($fd_arr);
?>
<style>
.dropdown-menu[title]::before {
    content: attr(title);
    /* then add some nice styling as needed, eg: */
    display: block;
    font-weight: bold;
    padding: 4px;
	text-align:center;
}
.dropdown-menu li{
	text-align:center;
}
hr{border-top: 1px solid #ccc;}
</style>


		<!-- <div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div>

		<div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div>

		<div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div> -->

        <!--<div class='selectMonths'>
            <input type='text' placeholder='Date of inquery' value='' readonly />
            <i>&#128197;</i>
        </div> -->



<b><img id="mvbk" onclick="fmove_back()" src="<?php echo ASSETS_URL; ?>/assets/img/back.png" width="35px" style="cursor: pointer;" />Back</b>



<hr class="simple">


			<p>
				<button id="add_tab" class="btn btn-primary">
					Add Symbol
				</button>
			</p>

			<div id="tabs2">
				<ul>
					<li>
						<a href="#tabs-1"><h3><?php echo $fd_symbol; ?></h3></a>
					</li>
				</ul>
				<div id="tabs-1">
					<p>
						<h3><?php //echo $fd_symbol." ".$fd_pdesc; ?></h3>
						<div class='selectMonths'>
							<input type='text' placeholder='Date of inquery' value='' readonly />
							<i>&#128197;</i>
						</div>
					</p>
				</div>
			</div>

			<!-- Demo -->
			<div id="addtab" title="<div class='widget-header'><h4><i class='fa fa-plus'></i> Add another tab</h4></div>">

				<form>

					<fieldset>
						<input name="authenticity_token" type="hidden">
						<div class="form-group">
							<label>Symbol</label>
							<!-- <input class="form-control" name="Symbol[]" id="tab_title" value="" placeholder="Symbol" type="text"> -->
							<select name="Symbol[]" id="tab_title">
							<option>SELECT</option>
								<?php

									$buit_query="SELECT futureslist.Symbol,CONCAT('(', futureslist.Symbol,') ', IF(futureslist.`Sub Group` IS NOT NULL AND futureslist.`Sub Group`<>'', CONCAT(futureslist.`Sub Group`, '-') , ''),
IF(futureslist.Category IS NOT NULL AND futureslist.Category<>'', CONCAT(futureslist.Category, '-') , ''),
IF(futureslist.`Sub Category` IS NOT NULL AND futureslist.`Sub Category`<>'' , CONCAT(futureslist.`Sub Category`, '-') , ''),
futureslist.Description) AS Selection FROM futures.futureslist WHERE futureslist.`Status` ='Active' AND futureslist.Symbol IS NOT NULL AND (futureslist.Volume>0 OR futureslist.`Open Interest`>0)
ORDER BY futureslist.`Sub Group`, futureslist.Category,futureslist.`Sub Category`";
									if ($fdstmt = $mysqli->prepare($buit_query))
									{

									   $fdstmt->execute();
											$fdstmt->store_result();
											if ($fdstmt->num_rows > 0) {
												$fdstmt->bind_result($p_symbol,$p_Selection);
												$j=0;
												while($fdstmt->fetch()){
													?>
													<option value="<?php echo $p_symbol; ?>"><?php echo $p_Selection; ?></option>
													<?php
												}
											}
									}
							?>
							</select>

						</div>

						<!-- <div class="form-group">
							<label>Content</label>
							<textarea class="form-control" name="tab_content" id="tab_content" placeholder="Tab Content" rows="3"></textarea>
						</div> -->

					</fieldset>

				</form>

			</div>


<hr>



<div style="width:8%;float:left">
	<label for="amount1">Weight1:</label>
	<input type="text" id="amount1" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q1">
	<div id="slider-vertical1" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount2">Weight2:</label>
	<input type="text" id="amount2" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q2">
	<div id="slider-vertical2" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount3">Weight3:</label>
	<input type="text" id="amount3" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q3">
	<div id="slider-vertical3" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount4">Weight4:</label>
	<input type="text" id="amount4" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q4">
	<div id="slider-vertical4" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount5">Weight5:</label>
	<input type="text" id="amount5" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q5">
	<div id="slider-vertical5" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount6">Weight6:</label>
	<input type="text" id="amount6" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q6">
	<div id="slider-vertical6" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount7">Weight7:</label>
	<input type="text" id="amount7" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q7">
	<div id="slider-vertical7" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount8">Weight8:</label>
	<input type="text" id="amount8" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q8">
	<div id="slider-vertical8" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount9">Weight9:</label>
	<input type="text" id="amount9" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q9">
	<div id="slider-vertical9" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount10">Weight10:</label>
	<input type="text" id="amount10" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q10">
	<div id="slider-vertical10" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount11">Weight11:</label>
	<input type="text" id="amount11" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q11">
	<div id="slider-vertical11" style="height:200px;"></div>
</div>

<div style="width:8%;float:left">
	<label for="amount12">Weight12:</label>
	<input type="text" id="amount12" readonly style="border:0; color:#f6931f; font-weight:bold; width: 35px;background:#F1F1F1">
	<input type="hidden" id="q12">
	<div id="slider-vertical12" style="height:200px;"></div>
</div>


<div style="clear:both">&nbsp;</div>
<hr>


<script>

		// DO NOT REMOVE : GLOBAL FUNCTIONS!

		$(document).ready(function() {

			pageSetUp();

			// menu
			$("#menu").menu();

			/*
			 * AUTO COMPLETE AJAX
			 */

			function log(message) {
				$("<div>").text(message).prependTo("#log");
				$("#log").scrollTop(0);
			}

			$("#city").autocomplete({
				source : function(request, response) {
					$.ajax({
						url : "http://ws.geonames.org/searchJSON",
						dataType : "jsonp",
						data : {
							featureClass : "P",
							style : "full",
							maxRows : 12,
							name_startsWith : request.term
						},
						success : function(data) {
							response($.map(data.geonames, function(item) {
								return {
									label : item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
									value : item.name
								}
							}));
						}
					});
				},
				minLength : 2,
				select : function(event, ui) {
					log(ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
				}
			});

			/*
			 * Spinners
			 */
			$("#spinner").spinner();
			$("#spinner-decimal").spinner({
				step : 0.01,
				numberFormat : "n"
			});

			$("#spinner-currency").spinner({
				min : 5,
				max : 2500,
				step : 25,
				start : 1000,
				numberFormat : "C"
			});

			/*
			 * CONVERT DIALOG TITLE TO HTML
			 * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
			 */
			$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
				_title : function(title) {
					if (!this.options.title) {
						title.html("&#160;");
					} else {
						title.html(this.options.title);
					}
				}
			}));


			/*
			* DIALOG SIMPLE
			*/

			// Dialog click
			$('#dialog_link').click(function() {
				$('#dialog_simple').dialog('open');
				return false;

			});

			$('#dialog_simple').dialog({
				autoOpen : false,
				width : 600,
				resizable : false,
				modal : true,
				title : "<div class='widget-header'><h4><i class='fa fa-warning'></i> Empty the recycle bin?</h4></div>",
				buttons : [{
					html : "<i class='fa fa-trash-o'></i>&nbsp; Delete all items",
					"class" : "btn btn-danger",
					click : function() {
						$(this).dialog("close");
					}
				}, {
					html : "<i class='fa fa-times'></i>&nbsp; Cancel",
					"class" : "btn btn-default",
					click : function() {
						$(this).dialog("close");
					}
				}]
			});

			/*
			* DIALOG HEADER ICON
			*/

			// Modal Link
			$('#modal_link').click(function() {
				$('#dialog-message').dialog('open');
				return false;
			});

			$("#dialog-message").dialog({
				autoOpen : false,
				modal : true,
				title : "<div class='widget-header'><h4><i class='icon-ok'></i> jQuery UI Dialog</h4></div>",
				buttons : [{
					html : "Cancel",
					"class" : "btn btn-default",
					click : function() {
						$(this).dialog("close");
					}
				}, {
					html : "<i class='fa fa-check'></i>&nbsp; OK",
					"class" : "btn btn-primary",
					click : function() {
						$(this).dialog("close");
					}
				}]

			});

			/*
			 * Remove focus from buttons
			 */
			$('.ui-dialog :button').blur();

			/*
			 * Just Tabs
			 */

			$('#tabs').tabs();

			/*
			 *  Simple tabs adding and removing
			 */

			$('#tabs2').tabs();

			// Dynamic tabs
			var tabTitle = $("#tab_title"), tabContent = $("#tab_content"), tabTemplate = "<li style='position:relative;'> <span class='air air-top-left delete-tab' style='top:7px; left:7px;'><button class='btn btn-xs font-xs btn-default hover-transparent'><i class='fa fa-times'></i></button></span></span><a href='#{href}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; #{label}</a></li>", tabCounter = 2;

			var tabs = $("#tabs2").tabs();

			// modal dialog init: custom buttons and a "close" callback reseting the form inside
			var dialog = $("#addtab").dialog({
				autoOpen : false,
				width : 600,
				resizable : false,
				modal : true,
				buttons : [{
					html : "<i class='fa fa-times'></i>&nbsp; Cancel",
					"class" : "btn btn-default",
					click : function() {
						/*$(this).dialog("close");*/
						$(".ui-dialog-titlebar-close").click();

					}
				}, {

					html : "<i class='fa fa-plus'></i>&nbsp; Add",
					"class" : "btn btn-danger",
					click : function() {
						addTab();
						$(".ui-dialog-titlebar-close").click();
						/* $(this).dialog("close");*/
					}
				}]
			});

			// addTab form: calls addTab function on submit and closes the dialog
			var form = dialog.find("form").submit(function(event) {
				addTab();
				$(".ui-dialog-titlebar-close").click();
				/*dialog.dialog("close");*/
				event.preventDefault();
			});

			// actual addTab function: adds new tab using the input from the form above
			function addTab() {
				var label = tabTitle.val() || "Tab " + tabCounter, id = "tabs-" + tabCounter, li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, label)), tabContentHtml = tabContent.val() || "<div class='selectMonths'><input id='datebox_" + tabCounter + "' type='text' placeholder='Date of inquery' class='add_date_picker' value='' readonly /><i>&#128197;</i></div>";

		        var hidden_symbol="<input type='hidden' name='selectedsymbol_"+tabCounter+"' id='selectedsymbol_"+tabCounter+"' value='"+label+"'>";
				tabs.find(".ui-tabs-nav").append(li);
				tabContentHtml=tabContentHtml+hidden_symbol;

				tabs.append("<div id='" + id + "'><p>" + tabContentHtml + "</p></div>");
				tabs.tabs("refresh");
				tabCounter++;

				// clear fields
				$("#tab_title").val("");
				$("#tab_content").val("");
			}

			// addTab button: just opens the dialog
			$("#add_tab").button().click(function() {
				dialog.dialog("open");
			});

			// close icon: removing the tab on click
			$("#tabs2").on("click", 'span.delete-tab', function() {

				var panelId = $(this).closest("li").remove().attr("aria-controls");

				var resarr = panelId.split("-");
				var getindex=resarr[1];
				if (typeof global_arr[getindex] !== 'undefined') {
					delete  global_arr[getindex];
				}

				$("#" + panelId).remove();
				tabs.tabs("refresh");
			});

			/*
			* ACCORDION
			*/
			//jquery accordion

		     var accordionIcons = {
		         header: "fa fa-plus",    // custom icon class
		         activeHeader: "fa fa-minus" // custom icon class
		     };

			$("#accordion").accordion({
				autoHeight : false,
				heightStyle : "content",
				collapsible : true,
				animate : 300,
				icons: accordionIcons,
				header : "h4",
			})

			/*
			 * PROGRESS BAR
			 */
			$("#progressbar").progressbar({
		     	value: 25,
		     	create: function( event, ui ) {
		     		$(this).removeClass("ui-corner-all").addClass('progress').find(">:first-child").removeClass("ui-corner-left").addClass('progress-bar progress-bar-success');
				}
			});

		})

		</script>

<script>

$( function() {
		$( "#slider-vertical1" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value:0,
			slide: function( event, ui ) {

				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount1" ).val( parseInt(per_value)+"%" );
				$( "#q1" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical1" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount1" ).val( parseInt(per_value)+"%" );
		$( "#q1" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical2" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value:0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount2" ).val( parseInt(per_value)+"%" );
				$( "#q2" ).val( per_value/100 );
			}
		});

		var per_value=((($( "#slider-vertical2" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount2" ).val( parseInt(per_value)+"%" );
		$( "#q2" ).val( per_value/100 );

	} );

	$( function() {
		$( "#slider-vertical3" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount3" ).val( parseInt(per_value)+"%" );
				$( "#q3" ).val( per_value/100 );
			}
		});

		var per_value=((($( "#slider-vertical3" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount3" ).val( parseInt(per_value)+"%" );
		$( "#q3" ).val( per_value/100 );

	} );

	$( function() {
		$( "#slider-vertical4" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount4" ).val( parseInt(per_value)+"%" );
				$( "#q4" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical4" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount4" ).val( parseInt(per_value)+"%" );
		$( "#q4" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical5" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount5" ).val( parseInt(per_value)+"%" );
				$( "#q5" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical5" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount5" ).val( parseInt(per_value)+"%" );
		$( "#q5" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical6" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount6" ).val( parseInt(per_value)+"%" );
				$( "#q6" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical6" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount6" ).val( parseInt(per_value)+"%" );
		$( "#q6" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical7" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount7" ).val( parseInt(per_value)+"%" );
				$( "#q7" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical7" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount7" ).val( parseInt(per_value)+"%" );
		$( "#q7" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical8" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount8" ).val( parseInt(per_value)+"%" );
				$( "#q8" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical8" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount8" ).val( parseInt(per_value)+"%" );
		$( "#q8" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical9" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount9" ).val( parseInt(per_value)+"%" );
				$( "#q9" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical9" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount9" ).val( parseInt(per_value)+"%" );
		$( "#q9" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical10" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount10" ).val( parseInt(per_value)+"%" );
				$( "#q10" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical10" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount10" ).val( parseInt(per_value)+"%" );
		$( "#q10" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical11" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount11" ).val( parseInt(per_value)+"%" );
				$( "#q11" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical11" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount11" ).val( parseInt(per_value)+"%" );
		$( "#q11" ).val( per_value/100 );
	} );

	$( function() {
		$( "#slider-vertical12" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 0,
			slide: function( event, ui ) {
				var per_value=(((ui.value/100)*4)+1)*100;
				$( "#amount12" ).val( parseInt(per_value)+"%" );
				$( "#q12" ).val( per_value/100 );
			}
		});
		var per_value=((($( "#slider-vertical12" ).slider( "value" )/100)*4)+1)*100;
		$( "#amount12" ).val( parseInt(per_value)+"%" );
		$( "#q12" ).val( per_value/100 );
	} );

function fmove_back(){
	$('#ftable').show();
	$('#fselect').html('');
	$('#fresponse').html('');
	$('#ftopdialog').html('');
	$('#fdetails').html('');
}
function loadfmap(data){

	var t_data=encodeURIComponent(JSON.stringify(data));

	$('#fresponse').html('');
	var q="&q1="+$("#q1").val()+"&q2="+$("#q2").val()+"&q3="+$("#q3").val()+"&q4="+$("#q4").val()+"&q5="+$("#q5").val()+"&q6="+$("#q6").val()+"&q7="+$("#q7").val()+"&q8="+$("#q8").val()+"&q9="+$("#q9").val()+"&q10="+$("#q10").val()+"&q11="+$("#q11").val()+"&q12="+$("#q12").val();

	var cus_type=t_data+"@@"+q;

	$('#fresponse').html('<iframe id="frame" src="assets/ajax/futures_details3.php?action=view&type='+cus_type+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
}
/*
function loadfmap(type,month){
	$('#fresponse').html('');
	var q="&q1="+$("#q1").val()+"&q2="+$("#q2").val()+"&q3="+$("#q3").val()+"&q4="+$("#q4").val()+"&q5="+$("#q5").val()+"&q6="+$("#q6").val()+"&q7="+$("#q7").val()+"&q8="+$("#q8").val()+"&q9="+$("#q9").val()+"&q10="+$("#q10").val()+"&q11="+$("#q11").val()+"&q12="+$("#q12").val();
	if(type=='custom'){
		var cus_type="custom@"+"<?php echo $fd_symbol; ?>"+"@"+month+q;
	}else{
		var cus_type="month@"+"<?php echo $fd_symbol; ?>"+"@"+month+q;
	}

	$('#fresponse').html('<iframe id="frame" src="assets/ajax/futures_details3.php?action=view&type='+cus_type+'&month='+month+'" width="100%" height="610" frameBorder="0" scrolling="no"></iframe>');
    //$('#fresponse').html('assets/ajax/futures_details.php?action=view&fdate='+fdate);
	//$("#frame").attr("src", "http://www.example.com/");
} */
</script>

<?php
}else if(isset($_GET['type']) and $_GET['type'] != "")
{
	$fd_arr=array();
	$types=explode("@@",$_GET['type']);
	//if(count($types) != 3) die("Wrong Parameter provided!");

	$cus_type=$mysqli->real_escape_string($types[0]);
	$tempData = html_entity_decode($cus_type);
    $cleanData = json_decode($types[0]);


	//$fsym=$mysqli->real_escape_string($types[1]);

	$q1=(float)$_GET['q1'];
	$q2=(float)$_GET['q2'];
	$q3=(float)$_GET['q3'];
	$q4=(float)$_GET['q4'];
	$q5=(float)$_GET['q5'];

	$q6=(float)$_GET['q6'];
	$q7=(float)$_GET['q7'];
	$q8=(float)$_GET['q8'];
	$q9=(float)$_GET['q9'];
	$q10=(float)$_GET['q10'];

	$q11=(float)$_GET['q11'];
	$q12=(float)$_GET['q12'];

	$cleanData = array_filter($cleanData);
	$graph_values=array();
	$i=0;

$ik=1;
$value_array=array();

foreach($cleanData as $values){

   if($ik==1){
	   $value_array[]='{
					"id": "g1",
					"balloon":{
					  "drop":true,
					  "adjustBorderColor":false,
					  "color":"#ffffff"
					},
					"bullet": "round",
					"bulletBorderAlpha": 1,
					"bulletColor": "#FFFFFF",
					"bulletSize": 5,
					"hideBulletsCount": 50,
					"lineThickness": 2,
					"title": "red line",
					"useLineColorForBulletBorder": true,
					"valueField": "value",
					"balloonText": "[[value]]"
				}';
   }else{
	   $value_array[]='{
			"id": "g'.$ik.'",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "green line",
            "useLineColorForBulletBorder": true,
            "valueField": "value'.$ik.'"
        }';
   }

    $exp_value=explode("--", $values);

	if($exp_value[1]=='custom'){
		$fsym=$exp_value[0];
		$month=$exp_value[2];

		$arr_date=explode("~",$month);
		$from_date=$arr_date[0];
		$to_date=$arr_date[1];

		$a_date = "2009-11-23";
		$date = new DateTime($to_date);
		$date->modify('last day of this month');
		$new_to_date=$date->format('Y-m-d');

		 $buit_query="SELECT a.TRADEDATE, (SUM(a.SETTLE)/('".$q1."'+'".$q2."'+'".$q3."'+'".$q4."'+'".$q5."'+'".$q6."'+'".$q7."'+'".$q8."'+'".$q9."'+'".$q10."'+'".$q11."'+'".$q12."')) AS strip_price
FROM (SELECT nymex_future.TRADEDATE, STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'), '%Y-%m-%d') AS FUTUREMONTH,
CASE WHEN nymex_future.`CONTRACT MONTH`=1 THEN nymex_future.SETTLE * '".$q1."'
WHEN nymex_future.`CONTRACT MONTH`=2 THEN nymex_future.SETTLE * '".$q2."'
WHEN nymex_future.`CONTRACT MONTH`=3 THEN nymex_future.SETTLE * '".$q3."'
WHEN nymex_future.`CONTRACT MONTH`=4 THEN nymex_future.SETTLE * '".$q4."'
WHEN nymex_future.`CONTRACT MONTH`=5 THEN nymex_future.SETTLE * '".$q5."'
WHEN nymex_future.`CONTRACT MONTH`=6 THEN nymex_future.SETTLE * '".$q6."'
WHEN nymex_future.`CONTRACT MONTH`=7 THEN nymex_future.SETTLE * '".$q7."'
WHEN nymex_future.`CONTRACT MONTH`=8 THEN nymex_future.SETTLE * '".$q8."'
WHEN nymex_future.`CONTRACT MONTH`=9 THEN nymex_future.SETTLE * '".$q9."'
WHEN nymex_future.`CONTRACT MONTH`=10 THEN nymex_future.SETTLE * '".$q10."'
WHEN nymex_future.`CONTRACT MONTH`=11 THEN nymex_future.SETTLE * '".$q11."'
WHEN nymex_future.`CONTRACT MONTH`=12 THEN nymex_future.SETTLE * '".$q12."'
ELSE nymex_future.SETTLE END AS SETTLE FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0) a
LEFT JOIN (SELECT nymex_future.TRADEDATE,MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',
nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MinMonth,MAX(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MaxMonth
FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0 GROUP BY nymex_future.TRADEDATE) b ON a.TRADEDATE = b.TRADEDATE
WHERE a.FUTUREMONTH >= '".$from_date."' AND a.FUTUREMONTH <= '".$new_to_date."' AND '".$from_date."' >= b.MinMonth  AND '".$new_to_date."' <= b.MaxMonth  GROUP BY a.TRADEDATE
";
	}else{
		$fsym=$exp_value[0];
		$month=$exp_value[2];;
		  $buit_query="SELECT a.TRADEDATE, (SUM(a.SETTLE)/('".$q1."'+'".$q2."'+'".$q3."'+'".$q4."'+'".$q5."'+'".$q6."'+'".$q7."'+'".$q8."'+'".$q9."'+'".$q10."'+'".$q11."'+'".$q12."')) AS strip_price
FROM (SELECT nymex_future.TRADEDATE,STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d') AS FUTUREMONTH,
CASE 	WHEN nymex_future.`CONTRACT MONTH`=1 THEN nymex_future.SETTLE * '".$q1."'
WHEN nymex_future.`CONTRACT MONTH`=2 THEN nymex_future.SETTLE * '".$q2."'
WHEN nymex_future.`CONTRACT MONTH`=3 THEN nymex_future.SETTLE * '".$q3."'
WHEN nymex_future.`CONTRACT MONTH`=4 THEN nymex_future.SETTLE * '".$q4."'
WHEN nymex_future.`CONTRACT MONTH`=5 THEN nymex_future.SETTLE * '".$q5."'
WHEN nymex_future.`CONTRACT MONTH`=6 THEN nymex_future.SETTLE * '".$q6."'
WHEN nymex_future.`CONTRACT MONTH`=7 THEN nymex_future.SETTLE * '".$q7."'
WHEN nymex_future.`CONTRACT MONTH`=8 THEN nymex_future.SETTLE * '".$q8."'
WHEN nymex_future.`CONTRACT MONTH`=9 THEN nymex_future.SETTLE * '".$q9."'
WHEN nymex_future.`CONTRACT MONTH`=10 THEN nymex_future.SETTLE * '".$q10."'
WHEN nymex_future.`CONTRACT MONTH`=11 THEN nymex_future.SETTLE * '".$q11."'
WHEN nymex_future.`CONTRACT MONTH`=12 THEN nymex_future.SETTLE * '".$q12."'
ELSE nymex_future.SETTLE END AS SETTLE FROM futures.nymex_future
WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0) a
LEFT JOIN (SELECT nymex_future.TRADEDATE,MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')) AS MinMonth,
DATE_ADD(MIN(STR_TO_DATE(CONCAT(nymex_future.`CONTRACT YEAR`,'-',nymex_future.`CONTRACT MONTH`,'-01'),'%Y-%m-%d')), INTERVAL '".$month."' month) AS MaxMonth
FROM futures.nymex_future WHERE `PRODUCT SYMBOL`= '".$fsym."' AND TRADEDATE <> 0 GROUP BY nymex_future.TRADEDATE) b ON a.TRADEDATE = b.TRADEDATE
WHERE a.FUTUREMONTH >= b.MinMonth AND a.FUTUREMONTH < b.MaxMonth GROUP BY a.TRADEDATE";
	}

		if ($fdstmt = $mysqli->prepare($buit_query))
		{

		   $fdstmt->execute();
				$fdstmt->store_result();
				if ($fdstmt->num_rows > 0) {
					$fdstmt->bind_result($fd_TRADEDATE,$fd_strip_price);
					$j=0;
					while($fdstmt->fetch()){$tfdate=$fd_TRADEDATE;
						//$tfdate=DateTime::createFromFormat("m/d/Y" , "".$fd_tradedate."")->format('Y-m-d');
						//$tfdate=date_format(date_create_from_format('Y-m-d', $fd_tradedate), 'm/d/Y');
						$fd_strip_price=($fd_strip_price==""?0:$fd_strip_price);
						//$fd_arr[]='{"date": "'.$tfdate.'","value": '.$fd_strip_price.'}';
						$graph_values[$tfdate][]=$fd_strip_price;
						$j++;

					}
				}
	    }
$i++;
$ik++;
} //foreach closed
//echo "<pre>";
//print_r($graph_values);exit;

$k=0;
foreach($graph_values as $key=>$date_values){
	$i=0;$j=2;$str_value='';
	foreach($date_values as $datevalue){

		if($i==0){
			$str_value.="value".'"'.": ".$datevalue;
		}else{
			$str_value.=",".'"'."value".$j.'"'.": ".$datevalue;
			$j++;
		}
		$i++;
	}

	$fd_arr[]='{"date": "'.$key.'","'.$str_value.'}';

	$k++;
}

//$fd_arr = array_slice($fd_arr, 0, 100);

//$fd_arr[]='{"date": "'.$tfdate.'","value": '.$fd_strip_price.'}';
	if(!count($fd_arr)) die("No data to show!");

	  $g_text= implode(",",$fd_arr);
	  //echo implode(",",$value_array);
	//print_r($fd_arr);

	 /* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $g_text);
fclose($myfile); */

?>
<style>
#fchartdiv {
	width	: 100%;
	height	: 500px;
}
#amcharts-chart-div a {display:none !important;}
</style>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<div id="fchartdiv"></div>
<script>
window.onload = function () {
var chart = AmCharts.makeChart("fchartdiv", {
    "type": "serial",
    "theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,
    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":true
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [<?php echo implode(",",$value_array);?>
	],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":true
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    },
    "dataProvider": [
<?php echo implode(",",$fd_arr);?>
	]
});

chart.addListener("rendered", zoomChart);

zoomChart();
function zoomChart() {
    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
}
}
</script>


<?php
}
?>
