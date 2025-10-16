<?php
//error_reporting(E_ALL);
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
/*
$mysqliW = new mysqli(HOST, USER, PASSWORD, 'world');

if ($mysqliW->connect_error) {
	try {
		$mysqliW->close();
	}
	catch(Exception $e) {
		echo $mysqliW->connect_error; die();
	}
}
*/
sec_session_start();

if(checkpermission($mysqli,56)==false) die("Permission Denied! Please contact Vervantis.");
if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2 and $_SESSION["group_id"] != 3 and $_SESSION["group_id"] != 5)
	die("Restricted Access");

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

	?>
	<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
	<!--<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->
	<!--<link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />-->
	<!--<link href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.css" rel="stylesheet" type="text/css" />-->


	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet" type="text/css" />
	<!--<link href="assets/css/editor.jqueryui.min.css" rel="stylesheet" type="text/css" /> -->
	<link href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" type="text/css" />

	<style>
	#ei_datatable_fixed_column_filter{
	float: left;
	width: auto !important;
	margin: 1% 1% !important;
	}
	.dt-buttons{
	float: right !important;
	margin: 0.5% auto !important;
	}
	#ei_datatable_fixed_column_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
	#ei_datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
	#ei_datatable_fixed_column .widget-body,#ei_datatable_fixed_column #wid-id-2,#eitable,#eitable div[role="content"]{width: 100% !important;overflow: auto;}

	.m-top{margin-top:56px;}
	.m-top45{margin-top:47px;}
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

	#ei_datatable_fixed_column tbody td .fa{cursor:pointer;}

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


	</style>

	<section id="widget-grid" class="sitestable m-top45">

				<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-table"></i> </span>
						<h2>DataTable Join Test </h2>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->

						<!-- widget content -->
						<div class="widget-body no-padding">
							<table id="example" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>First name</th>
									<th>Last name</th>
									<th>Phone #</th>
									<th>Location</th>
									<th>Part 1</th>
									<th>Part 2</th>
								</tr>
							</thead>

						</table>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->

				</div>
				<!-- end widget -->
	</section>


	<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
	<script type="text/javascript">

		/* DO NOT REMOVE : GLOBAL FUNCTIONS!
		 *
		 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
		 *
		 * // activate tooltips
		 * $("[rel=tooltip]").tooltip();
		 *
		 * // activate popovers
		 * $("[rel=popover]").popover();
		 *
		 * // activate popovers with hover states
		 * $("[rel=popover-hover]").popover({ trigger: "hover" });
		 *
		 * // activate inline charts
		 * runAllCharts();
		 *
		 * // setup widgets
		 * setup_widgets_desktop();
		 *
		 * // run form elements
		 * runAllForms();
		 *
		 ********************************
		 *
		 * pageSetUp() is needed whenever you load a page.
		 * It initializes and checks for all basic elements of the page
		 * and makes rendering easier.
		 *
		 */

		pageSetUp();

		/*
		 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
		 * eg alert("my home function");
		 *
		 * var pagefunction = function() {
		 *   ...
		 * }
		 * loadScript("assets/js/plugin/_PLUGIN_NAME_.js", pagefunction);
		 *
		 */

		// PAGE RELATED SCRIPTS

		// pagefunction
		var pagefunction = function() {
			//console.log("cleared");

			/* // DOM Position key index //

				l - Length changing (dropdown)
				f - Filtering input (search)
				t - The Table! (datatable)
				i - Information (records)
				p - Pagination (paging)
				r - pRocessing
				< and > - div elements
				<"#id" and > - div with an id
				<"class" and > - div with a class
				<"#id.class" and > - div with an id and class

				Also see: http://legacy.datatables.net/usage/features
			*/

			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;

				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};





				var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    editor = new $.fn.dataTable.Editor( {
        ajax: "assets/ajax/joins-test-ajax.php",
        table: "#example",
        fields: [ {
                label: "First name:",
                name: "users.first_name"
            }, {
                label: "Last name:",
                name: "users.last_name"
            }, {
                label: "Phone #:",
                name: "users.phone"
            }, {
                label: "Site:",
                name: "users.site",
                type: "select",
                placeholder: "Select a location"
            },
			{
                label: "Part 1:",
                name: "sites.part1"
            },
			{
                label: "Part 2:",
                name: "sites.part2"
            }
        ]
    } );

    $('#example').DataTable( {
        dom: "Bfrtip",
        ajax: {
            url: "assets/ajax/joins-test-ajax.php",
            type: 'POST'
        },
        columns: [
            { data: "users.first_name" },
            { data: "users.last_name" },
            { data: "users.phone" },
            { data: "sites.name" },
			{ data: "sites.part1" },
			{ data: "sites.part2" }
        ],
        select: true,
        buttons: [
            { extend: "create", editor: editor },
            { extend: "edit",   editor: editor },
            { extend: "remove", editor: editor }
        ]
    } );
} );

				// //--------------------------------------------------------------------
				// // Activate an inline edit on click of a table cell
				// $('#ei_datatable_fixed_column').on( 'click', 'tbody td:not(:first-child)', function (e) {

					// //console.log($(this).prop('tagName'));
					// console.log(e.target.nodeName);

					// //console.log($(this).attr('tagName'));
					// //console.log($(this).prop('tagName'));

					// //console.log(editor.field());
					// //console.log(e);
					// //console.log(this);
					// var tdtag = $(this);
					// //if(this.tagName != 'td') {
					// //if($(this).prop('tagName') == 'i') {
					// if (e.target.nodeName == 'I' || e.target.nodeName == 'A') {
						// ///console.log('in if');
						// return;
						// //tdtag = $(this).parents('td');
						// //tdtag =
					// }

					// //console.log(tdtag);

					// //var filter_val;

					// //filter_val = tdtag.find('a').first().text();
					// //console.log(filter_val);

					// editor.disable( ['postalcode', 'country', 'state'] );
					// //console.log(this);
					// editor.inline( this );

					// //console.log(e.target.s.includeFields[0]); //undefined
					// ///console.log($(this).html());
					// //console.log(this.innerHTML);

					// //var index = editor.cell().index();
					// //console.log(index);

					// //console.log($(this).find('input').val());

					// //tdtag.find('input').val(filter_val);

					// //console.log(tdtag.find('input').first().val());
					// //console.log(this);

					// //console.log('--------------------------');

					// ////var input_val = $(this).find('input').val();

					// ////var inner_div = document.createElement("div");
					// ////inner_div.innerHTML = input_val;
					// ////var inner_text = inner_div.textContent || inner_div.innerText || "";

					// ////$(this).find('input').val(inner_text);

					// //console.log($(this).html());
					// //editor.field( 'postalcode' ).disable();
				// } );

				// editor.on('setData', function(e, json, data, action) {
					// //data.changeFlag = 'changed';
					// //var index = editor.cell( cell ).index();
					// //console.log(index);

					// //return;
				// });

				// editor.on('initEdit', function(e) {
					// //console.log('initEdit');
					// //console.log(e);
					// /*
					// editor.show(); //Shows all fields
					// editor.hide('ID');
					// editor.hide('Field_Name_1');
					// */
				// });

				// editor.on('open', function(e) {

					// //console.log('open 11');
					// //console.log(this);
					// //console.log(e.target.s.includeFields[0]);

					// var fldname = e.target.s.includeFields[0];
					// input_val = editor.field(fldname).val();

					// //editor.field(fldname).val();
					// //editor.field(fldname).set('amir');

					// var inner_div = document.createElement("div");
					// inner_div.innerHTML = input_val;
					// var inner_text = inner_div.textContent || inner_div.innerText || "";

					// editor.field(fldname).set(inner_text.trim());

					// //console.log('open 22');
					// /*
					// editor.show(); //Shows all fields
					// editor.hide('ID');
					// editor.hide('Field_Name_1');
					// */
				// });

			// editor.on( 'preSubmit', function ( e, o, a ) {
                // if (a == 'remove') {
                    // o.action = "edit"; // Change action from delete to edit

                    // // Loop through selected records and set deleted value
                    // for (var key in o.data) {
                        // if (o.data.hasOwnProperty(key)) {
                            // o.data[key].deleted = 1;
                        // }
                    // }
                // }
            // } );

				// // // Edit record
				// // $('#ei_datatable_fixed_column_wrapper').on('click', '.buttons-create', function (e) {
					// // //e.preventDefault();
					// // alert('edit');
					// // //editor.field( ['postalcode', 'country', 'state'] ).enable();
					// // editor.edit(

					// // //$(this).closest('tr'), {
						// // //title: 'Edit record',
						// // //buttons: 'Update'
					// // //}
					// // );
					// // editor.enable( ['postalcode', 'country', 'state'] );
				// // } );

				// // add record
				// $(document).on("click", ".buttons-create", function() {
					// editor.enable( ['postalcode', 'country', 'state'] );
					// editor.create( {
						// //title: 'Create new record',
						// //buttons: 'Add'
					// } );

					// editor.dependent( 'country', 'assets/ajax/zipcode-dropdowns.php?dep=country' );

				// } );

				// // Edit record
				// $(document).on('click', '.buttons-edit', function (e) {
					// //e.preventDefault();
					// editor.disable( ['postalcode', 'country', 'state'] );
					// editor.edit( otable.row({ selected: true }).index(), {
						// //title: 'Edit record',
						// //buttons: 'Update'
					// } );

					// var all_inputs = $(".DTED_Lightbox_Wrapper .DTE_Action_Edit .DTE_Body_Content form .DTE_Field input");
					// all_inputs.each(function(i, obj) {
						// var input_val = $(this).val();
						// var inner_div = document.createElement("div");
						// inner_div.innerHTML = input_val;
						// var inner_text = inner_div.textContent || inner_div.innerText || "";

						// $(this).val(inner_text.trim());
					// });
					// //var input_val = $(this).find('input').val();



					// //console.log('111');
					// //console.log( otable.row({ selected: true }) );



				// } );

				// // Apply the filter
				// $("#ei_datatable_fixed_column .sdrp").on( 'keyup change', function () {
					// otable
						// .column( $(this).parent().index()+':visible' )
						// .search( this.value )
						// .draw();

					// if ($(this).hasClass('dd_country')) {
						// var val = this.value;
						// if (!val) {
							// val = 'all';
						// }
						// getState(val);
					// }

				// } );

				//otable.columns( [1,13,14,15,16,17,18,19,20,21,22,23,24] ).visible( false );


			// custom toolbar
			$("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

			// Apply the filter
			$(document.body).on('keyup change', '#ei_datatable_fixed_column thead th input[type=text]' ,function(){

				//console.log('here');
				//console.log($(this).parent().index());

				otable
					.column( $(this).parent().index()+':visible' )
					//.column( $(this).parent().index() )
					.search( this.value )
					.draw();

			});
		};

		function multifilter(nthis,fieldname,otable)
		{
				var selectedoptions = [];
				$.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
					selectedoptions.push($(this).val());
				});
				otable
				 .column( $(nthis).parent().index()+':visible' )
				 .search("^" + selectedoptions.join("|") + "$", true, false, true)
				 .draw();
		}

		function multilist(indexno)
		{
			var items=[], options=[];
			$('#ei_datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
			   items.push( $(this).text() );
			});
			var items = $.unique( items );
			$.each( items, function(i, item){
				options.push('<option value="' + item + '">' + item + '</option>');
			})
			return options;
		}

		function getState (country) {
		//$('#state').on('change', function(){
			//var country = $(this).val();
			if(country){
				$.ajax({
					type:'POST',
					url:'assets/ajax/zipcode-dropdowns.php',
					data:'country='+country,
					success:function(html){
						$('.dd_state').html(html);
					}
				});
			}
			/*
			else{
				$('#city').html('<option value="">Select state first</option>');
			}
			*/
		//});
		}

		//loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
			///loadScript("https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.js", function(){

			//loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){

				///loadScript("assets/js/dataTables.editor.min.js", function(){

				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
				///loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
				///loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
					///loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
					///loadScript("https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js", pagefunction)
				///});
				///});
				///});
				///});
				///});
				///});
			///});
		//});



		/*

				loadScript("https://cdn.datatables.net/v/dt/jqc-1.12.4/moment-2.18.1/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.js", function(){


					loadScript("assets/js/dataTables.editor.min.js", function(){

						pagefunction();

				});

				});
		*/

		loadScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function(){
		 loadScript("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js", function(){
		  loadScript("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js", function(){
		   loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js", function(){
			loadScript("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js", function(){
			 loadScript("https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.js", function(){
			  loadScript("https://cdn.datatables.net/buttons/1.0.3/js/buttons.colVis.js", function(){
			   loadScript("https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js", function(){
				 loadScript("https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js", function(){
				 loadScript("assets/js/dataTables.editor.min.js", function(){
					pagefunction();
			     });
				 });
			   });
			  });
			 });
			});
		   });
		  });
		 });
	    });


		$(document).ajaxComplete(function(event, xhr, settings) {

			/*
			if (settings.url == 'assets/ajax/zip-codes-ajax.php') {
			  $(".ar_popover").popover({ trigger: "hover" });

			  $('.showversion-link').mouseout(function() {
				  //$(this).parent().find('.ar_popover').trigger('mouseout');
				  ////$('.ar_popover').trigger('mouseout');
			  });
			}
			*/
		});





	</script>

<?php
 function getSelect($mysqli,$column,$select="") {

	$_SESSION[$column] = '';

	$html = '<select class="form-control sdrp dd_'.$column.'" id="fstatus">
				<option value="">All</option>';

	if ($column == 'state') {
		$qry = "Select DISTINCT $column From ziputility where country='US' order by $column";
	} else {
		$qry = "Select DISTINCT $column From ziputility order by $column";
	}

	if ($stmt_sss = $mysqli->prepare( $qry ) ) {
        $stmt_sss->execute();
        $stmt_sss->store_result();
        if ($stmt_sss->num_rows > 0) {
			$stmt_sss->bind_result($sssstatus);
			while($stmt_sss->fetch()) {

				if($sssstatus == "") continue;

				$selected = '';
				if (!empty($select) and $select==$sssstatus) {$selected = "selected";}

				$html .= '<option value="'.$sssstatus.'" '.$selected.'>'.$sssstatus.'</option>';

				$_SESSION[$column] .= "'".$sssstatus."',";
			}
		}
	}

	$html .= '</select>';
	return $html;
 }
?>
