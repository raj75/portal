<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

$user_one=$_SESSION["user_id"];

if(!isset($_SESSION['group_id']) and ($_SESSION['group_id'] != 1 or $_SESSION['group_id'] != 2 or $_SESSION['group_id'] != 3 or $_SESSION['group_id'] != 5))
	die("<h5 style='padding-top:30px;' align='center'>Restricted Access!</h5>");

if(isset($_GET["load"])){
?>
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable_fixed_column_filter{
float: left;
width: auto !important;
margin: 1% 1% !important;
}
.dt-buttons{
float: right !important;
margin: 0.9% auto !important;
}
#datatable_fixed_column_length{
float: right !important;
margin: 1% 1% !important;
}
.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
table.dataTable thead th, table.dataTable thead td{border-top:1px solid #cccccc !important;border-bottom:1px solid #cccccc !important;}
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}
#datatable_fixed_column .typedrp{width:auto !important;}
#datatable_fixed_column .typedrp {
    font-weight: 400 !important;
}
.
.flleft{float:left !important;}
</style>
<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Entity ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Entity Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Plant Name" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Sector" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Plant State" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Name Capacity (MW)" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Name Summer Capacity (MW)" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Name Winter Capacity (MW)" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Technology" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Energy Source Code" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Operating Month" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Operating Year" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Status" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Latitude" />
			</th>
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Longitude" />
			</th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Prime Mover Code" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Retirement Month" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Retirement Year" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Derate Year" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Derate Month" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Derate of Summer Capacity (MW)" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Uprate Year" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Uprate Month" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Planned Uprate of Summer Capacity (MW)" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="County" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Google Map" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Bing Map" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Balancing Authority Code" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Unit Code" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Sector Name" /></th>
      <th class="hasinput">
        <select class="form-control typedrp" id="typedrp">
					<option value="" SELECTED>All</option>
					<option value="Operating">Operating</option>
					<option value="Planned">Planned</option>
					<option value="Retired">Retired</option>
				</select>
      </th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Plant ID" /></th>
      <th class="hasinput"><input type="text" class="form-control" placeholder="Generator ID" /></th>
		</tr>
		<tr>
      <th data-hide="phone,tablet">Entity ID</th>
			<th data-hide="phone,tablet">Entity Name</th>
			<th data-hide="phone,tablet">Plant Name</th>
			<th data-hide="phone,tablet">Sector</th>
			<th data-hide="phone,tablet">Plant State</th>
			<th data-hide="phone,tablet">Name Capacity (MW)</th>
			<th data-hide="phone,tablet">Name Summer Capacity (MW)</th>
			<th data-hide="phone,tablet">Name Winter Capacity (MW)</th>
			<th data-hide="phone,tablet">Technology</th>
			<th data-hide="phone,tablet">Energy Source Code</th>
			<th data-hide="phone,tablet">Operating Month</th>
			<th data-hide="phone,tablet">Operating Year</th>
			<th data-hide="phone,tablet">Status</th>
			<th data-hide="phone,tablet">Latitude</th>
			<th data-hide="phone,tablet">Longitude</th>
			<th data-hide="phone,tablet">Prime Mover Code</th>
			<th data-hide="phone,tablet">Planned Retirement Month</th>
			<th data-hide="phone,tablet">Planned Retirement Year</th>
			<th data-hide="phone,tablet">Planned Derate Year</th>
			<th data-hide="phone,tablet">Planned Derate Month</th>
			<th data-hide="phone,tablet">Planned Derate of Summer Capacity (MW)</th>
			<th data-hide="phone,tablet">Planned Uprate Year</th>
			<th data-hide="phone,tablet">Planned Uprate Month</th>
			<th data-hide="phone,tablet">Planned Uprate of Summer Capacity (MW)</th>
			<th data-hide="phone,tablet">County</th>
			<th data-hide="phone,tablet">Google Map</th>
			<th data-hide="phone,tablet">Bing Map</th>
			<th data-hide="phone,tablet">Balancing Authority Code</th>
			<th data-hide="phone,tablet">Unit Code</th>
			<th data-hide="phone,tablet">Sector Name</th>
			<th data-hide="phone,tablet">Type</th>
			<th data-hide="phone,tablet">Plant ID</th>
			<th data-hide="phone,tablet">Generator ID</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script src="assets/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
	//$('#sssdrp option[value="Active"]').attr('selected', 'selected');
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
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;

			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};

		/* COLUMN FILTER  */
	    var otable = $('#datatable_fixed_column').DataTable({
			"lengthMenu": [[12,25, 100, -1], [12,25, 100, "All"]],
			"pageLength": 25,
			"processing": true,
			"serverSide": true,
		"dom": 'Blfrtip',
		 "search": {
            "caseInsensitive": false
        },
        "buttons": [
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
        ],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": ""
        },
        "columnDefs": [
                    {
                        "targets": [ 0,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,31,32],
                        "visible": false,
                        "searchable": false
                    }
                ],
      "order": [[1,'desc']],
			"autoWidth" : true,
			"ajax": "assets/ajax/server_processing_usapgen.php"
	    });

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

          updatedrps("name");
	    } );

      $("#datatable_fixed_column #dsstate").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
          updatedrps("state");
				//if($(this).parent().index() == 0){updateiso(this.value);}
	    } );

      $("#datatable_fixed_column #dscategory").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
          updatedrps("category");
				//if($(this).parent().index() == 0){updateiso(this.value);}
	    } );

      $("#datatable_fixed_column #dspolicy").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
          updatedrps("policy");
				//if($(this).parent().index() == 0){updateiso(this.value);}
	    } );

	    /* END COLUMN FILTER */

      <?php if(isset($_GET["state"]) and !empty($_GET["state"])){ ?>
          //$('#dsstate').val('<?php echo $_GET["state"]; ?>').change();
          //$('#dscategory').val('Financial Incentive').change();
      <?php } ?>
      $("#datatable_fixed_column .typedrp").on( 'keyup change', function () {
	        otable
				.column( $(this).parent().index()+':visible' )
	            .search(this.value, true, true, false)

	            //.search(this.value, false,true,false)
				//.search( this.value.replace(/(<([^>]+)>)/ig,"") ? '^ '+this.value.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
	            .draw();

	    } );
	};

  function reloaddsireusa(){
    parent.$('#list-dsireusa').load('assets/ajax/list-dsireusa.php?load=true&ct=<?php echo time(); ?>' );
  }

  function updatedrps(drptype){
    var dsname=$("#dsname").val();
    var dsstate=$("#dsstate").val();
    var dscategory=$("#dscategory").val();
    var dspolicy=$("#dspolicy").val();

    if(drptype != "state"){
      $.ajax({
        type: 'post',
        url: 'assets/includes/getdsireusa.php?state=1',
        data: {name:encodeURI(dsname),state:dsstate,category:encodeURI(dscategory),policy:encodeURI(dspolicy)},
        success: function (result) {
          if (result != false)
          {
            var results = JSON.parse(result);
            if(results.error == "")
            {
              $("#dsstate option").each(function(key, value) {
                if($(this).val() !=""){
                   if(  $.inArray( $(this).val(), results.state) > -1 )
                       $(this).show();
                   else
                       $(this).hide();
                }
               });
              //$('#dsstate').val('').change();
            }//else
              //alert("Error in request. Please try again later.");
          }else{
            //alert("Error in request. Please try again later.");
          }
        }
      });
    }else{
      if(dsstate==""){
        $("#dsstate option").each(function(key, value) {
          if($(this).val() !=""){
                 $(this).show();
          }
        });
      }
    }

    if(drptype != "category"){
      $.ajax({
        type: 'post',
        url: 'assets/includes/getdsireusa.php?category=1',
        data: {name:encodeURI(dsname),state:dsstate,category:encodeURI(dscategory),policy:encodeURI(dspolicy)},
        success: function (result) {
          if (result != false)
          {
            var results = JSON.parse(result);
            if(results.error == "")
            {
              $("#dscategory option").each(function(key, value) {
                if($(this).val() !=""){
                   if(  $.inArray( $(this).val(), results.category) > -1 )
                       $(this).show();
                   else
                       $(this).hide();
                }
               });
              //$('#dscategory').val('').change();
            }//else
              //alert("Error in request. Please try again later.");
          }else{
            //alert("Error in request. Please try again later.");
          }
        }
      });
    }else{
      if(dscategory==""){
        $("#dscategory option").each(function(key, value) {
          if($(this).val() !=""){
                 $(this).show();
          }
        });
      }
    }

    if(drptype != "policy"){
      $.ajax({
        type: 'post',
        url: 'assets/includes/getdsireusa.php?policy=1',
        data: {name:encodeURI(dsname),state:dsstate,category:encodeURI(dscategory),policy:encodeURI(dspolicy)},
        success: function (result) {
          if (result != false)
          {
            var results = JSON.parse(result);
            if(results.error == "")
            {
              $("#dspolicy option").each(function(key, value) {
                if($(this).val() !=""){
                   if(  $.inArray( $(this).val(), results.policy) > -1 )
                       $(this).show();
                   else
                       $(this).hide();
                }
               });
              //$('#dspolicy').val('').change();
            }//else
              //alert("Error in request. Please try again later.");
          }else{
            //alert("Error in request. Please try again later.");
          }
        }
      });
    }else{
      if(dspolicy==""){
        $("#dspolicy option").each(function(key, value) {
          if($(this).val() !=""){
                 $(this).show();
          }
        });
      }
    }
  }

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
		$('#datatable_fixed_column tbody tr td:nth-child('+indexno+')').each( function(){
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



</script>
<?php }else{
	die("Error Occured! Please try after sometime.");
}
?>
