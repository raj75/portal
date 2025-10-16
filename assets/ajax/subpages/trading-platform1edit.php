<?php //require_once("inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
 
if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2))
	die("Restricted Access!");

if(isset($_GET["load"]) and $_GET["load"]=="true"){
?>
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_columntp1_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_columntp1_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_columntp1{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_columntp1 .isodrp{width:auto !important;}
#datatable_fixed_columntp1 tr.dropdown select {
    font-weight: 400 !important;
}
.dtdisplay{
	box-shadow: none!important;
	-webkit-box-shadow: none!important;
	-moz-box-shadow: none!important;
	-webkit-border-radius: 0!important;
	display: block;
	width: 100%;
	height: 32px;
	padding: 6px 12px;
	font-size: 13px;
	line-height: 1.42857143;
	color: #555;
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	border-radius: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.dataTables_processing{top: 73px !important;}
</style>
<table id="datatable_fixed_columntp1" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
			<th class="hasinput d-1">
				<input type="text" class="form-control" placeholder="Filter Description" />
			</th>
			<th class="hasinput d-2">
				<input type="text" class="form-control" placeholder="Filter Exchange" />
			</th>
			<th class="hasinput d-3">
				<input type="text" class="form-control" placeholder="Filter Symbol" />
			</th>
			<th class="hasinput d-4">
				<input type="text" class="form-control" placeholder="Filter Commodity" />
			</th>
			<th class="hasinput d-5">
				<input type="text" class="form-control" placeholder="Filter Status" />
			</th>
			<th class="hasinput d-6">
				<input type="text" class="form-control" placeholder="Filter Contract Type" />
			</th>
			<th class="hasinput d-7">
				<input type="text" class="form-control" placeholder="Filter Date Code Min" />
			</th>
			<th class="hasinput d-8">
				<input type="text" class="form-control" placeholder="Filter Date Code Max" />
			</th>
			<th class="hasinput d-9">
				<input type="text" class="form-control" placeholder="Filter Max Date" />
			</th>
			<th class="hasinput d-10">
				<input type="text" class="form-control" placeholder="Filter Contracts" />
			</th>
			<th class="hasinput d-11">
				<input type="text" class="form-control" placeholder="Filter Spot Contract" />
			</th>
			<th class="hasinput d-12">
				<input type="text" class="form-control" placeholder="Filter Spot Price" />
			</th>
			<th class="hasinput d-13">
				<input type="text" class="form-control" placeholder="Filter 12 Strip" />
			</th>
			<th class="hasinput d-14"></th>
		</tr>
		<tr>
			<th data-hide="phone">Description</th>
			<th data-hide="phone">Exchange</th>
			<th data-hide="phone">Symbol</th>
			<th data-hide="phone,tablet">Commodity</th>
			<th data-hide="phone,tablet">Status</th>
			<th data-hide="phone,tablet">Contract Type</th>
			<th data-hide="phone,tablet">Date Code Min</th>
			<th data-hide="phone,tablet">Date Code Max</th>
			<th data-hide="phone,tablet">Max Date</th>
			<th data-hide="phone,tablet">Contracts</th>
			<th data-hide="phone,tablet">Spot Contract</th>
			<th data-hide="phone,tablet">Spot Price</th>
			<th data-hide="phone,tablet">12 Strip</th>
			<th data-hide="phone,tablet">Action</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	pageSetUp();

	// pagefunction
	var pagefunction = function() {

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
			var responsiveHelper_datatable_fixed_columntp1 = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
	    var otabletp1 = $('#datatable_fixed_columntp1').DataTable({
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 100,
			"processing": true,
			"serverSide": true,
		"dom": 'Blfrtip',
        /*"buttons": [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            {
                'extend': 'pdfHtml5',
				'title' : 'Vervantis_PDF',
                'messageTop': 'Vervantis PDF Export'
            },
            //'pdfHtml5'
            {
                'extend': 'print',
				//'title' : 'Vervantis',
                'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>'
            },
			{
				'text': 'Columns',
				'extend': 'colvis'
			}
        ],*/
		/*"columnDefs": [
			{ className: "switchcurrency", "targets": [ 4 ] },
			{ className: "switchcurrency", "targets": [ 5 ] },
			{ className: "switchcurrency", "targets": [ 6 ] },
			{ className: "switchcurrency", "targets": [ 7 ] }
		  ],*/
			"autoWidth" : true,
			"ajax": "assets/ajax/subpages/tp1_processing.php"/*,
			initComplete: function () {
				this.api().columns([0]).every( function () {
					 var column = this;
					 var select = $('<select class="form-control"><option value="">Filter ISO</option></select>')
						  .appendTo( $('#datatable_fixed_columntp1 .dropdown .d-1').empty() )
						  .on( 'change', function () {
							   var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
							   );
						  column
							   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
							   .draw();
						  } );
						  var darr = [];
						 column.data().unique().sort().each( function ( d, j ) {d = d.replace(/(<([^>]+)>)/ig,"");
								if(jQuery.inArray(d, darr) == -1 && d != ""){
									select.append( '<option value="'+d+'">'+d+'</option>' );
									darr.push(d);
								}
						 } );
				} );
			}*/
	    });

/*otabletp1.on( 'draw', function () {
    $('tr td:nth-child(5)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(6)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(7)').each(function (){
          $(this).addClass('switchcurrency')
    })
    $('tr td:nth-child(8)').each(function (){
          $(this).addClass('switchcurrency')
    })
});	*/

	   /* var otabletp1 = $('#datatable_fixed_columntp1').DataTable({
			 "iDisplayLength": 10,
			//"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	    	//"bFilter": false,
	    	//"bInfo": false,
	    	//"bLengthChange": false,
	    	//"bAutoWidth": false,
	    	//"bPaginate": false,
	    	//"bStateSave": true // saves sort state using localStorage
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'CT>r>"+
					"t"+
					"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
	        "oTableTools": {
	        	 "aButtons": [
	             "copy",
	             "csv",
	             "xls",
	                {
	                    "sExtends": "pdf",
	                    "sTitle": "Vervantis_PDF",
	                    "sPdfMessage": "Vervantis PDF Export",
	                    "sPdfSize": "letter"
	                },
	             	{
                    	"sExtends": "print",
                    	"sMessage": "Generated by Vervantis <i>(press Esc to close)</i>"
                	}
	             ],
	            "sSwfPath": "assets/js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
	        },
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_columntp1) {
					responsiveHelper_datatable_fixed_columntp1 = new ResponsiveDatatablesHelper($('#datatable_fixed_columntp1'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_columntp1.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_columntp1.respond();
			}

	    });*/

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_columntp1 thead th input.form-control[type=text]").on( 'keyup change', function () {
	        otabletp1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $("#datatable_fixed_columntp1 .isodrp").on( 'keyup change', function () {
	        otabletp1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

				if($(this).parent().index() == 0){updateiso(this.value);}
	    } );
	    $("#datatable_fixed_columntp1 .dtdisplay").on( 'keyup change', function () {
			var dateAr = this.value.split('/');
			var srchval=dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
			if(dateAr[2]== "undefined" || dateAr[1]== "undefined" || dateAr[0]== "undefined" || dateAr[2]== "" || dateAr[1]== "" || dateAr[0]== ""){
				srchval="";
			}
	        otabletp1
	            .column( $(this).parent().index()+':visible' )
	            .search( srchval )
	            .draw();
	    } );
		$("#to").datepicker({
		    defaultDate: "+1w",
		    changeMonth: true,
		    numberOfMonths: 3,
		    prevText: '<i class="fa fa-chevron-left"></i>',
		    nextText: '<i class="fa fa-chevron-right"></i>',
		    onClose: function (selectedDate) {
		        $("#from").datepicker("option", "minDate", selectedDate);
		    }
		});

		function updateiso(iso){
			$.ajax({
				type: 'post',
				url: 'assets/includes/getiso.php?iso='+iso,
				data: {iso:iso},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							$(".iso2 option").each(function() {
								if($(this).val() !=""){
									 if(  $.inArray( $(this).val(), results.node) > -1 )
										   $(this).show();
									 else
										   $(this).hide();
								}
							 });
							$('.iso2').val('').change();
						}//else
							//alert("Error in request. Please try again later.");
					}else{
						//alert("Error in request. Please try again later.");
					}
				}
			});
		}

	    /* END COLUMN FILTER */
<?php if(1==2){ /*if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){*/?>
		$('#selectCompany').empty().append( multilist(2).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(3).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(4).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(5).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(6).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(9).join() );
		$("#selectStatus").multiselect();
<?php }else if(1==3){/*}else{*/ ?>
		$('#selectCompany').empty().append( multilist(1).join() );
		$("#selectCompany").multiselect();
		$('#selectDivision').empty().append( multilist(2).join() );
		$("#selectDivision").multiselect();
		$('#selectCountry').empty().append( multilist(3).join() );
		$("#selectCountry").multiselect();
		$('#selectState').empty().append( multilist(4).join() );
		$("#selectState").multiselect();
		$('#selectCity').empty().append( multilist(5).join() );
		$("#selectCity").multiselect();
		$('#selectStatus').empty().append( multilist(8).join() );
		$("#selectStatus").multiselect();
<?php } ?>
		$("#selectCompany").on( 'keyup change', function () {multifilter(this,"selectCompany",otabletp1)});
		$("#selectDivision").on( 'keyup change', function () {multifilter(this,"selectDivision",otabletp1)});
		$("#selectCountry").on( 'keyup change', function () {multifilter(this,"selectCountry",otabletp1)});
		$("#selectState").on( 'keyup change', function () {multifilter(this,"selectState",otabletp1)});
		$("#selectCity").on( 'keyup change', function () {multifilter(this,"selectCity",otabletp1)});
		$("#selectStatus").on( 'keyup change', function () {multifilter(this,"selectStatus",otabletp1)});
	};

	function multifilter(nthis,fieldname,otabletp1)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otabletp1
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#datatable_fixed_columntp1 tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}

	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);

<?php if(isset($_SESSION["group_id"]) and ($_SESSION["group_id"] == 1 or $_SESSION["group_id"] == 2) and isset($_SESSION['user_id'])){?>
function loadsite(sid) {
	parent.$('#response').html('');
    parent.$('#response').load('assets/ajax/list-sites.php?editsid=true&sid='+sid);
}

function deletesite(sid,sname) {
	$('#response').html('');
	var r = confirm("Do you want to delete Site: "+sname+"!");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/sitesedit.inc.php',
			data: {sid:sid,action:'delete'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						parent.$("#list-sites").html('');
						parent.$('#list-sites').load('assets/ajax/list-sites.php?load=true');
					}else
						alert("Error in request. Please try again later.");
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		});
	}
}
<?php } ?>
</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
