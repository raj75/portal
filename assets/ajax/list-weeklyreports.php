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
#datatable_fixed_column .dsiredrp{width:auto !important;}
#datatable_fixed_column .dsiredrp {
    font-weight: 400 !important;
}
.
.flleft{float:left !important;}
</style>

<style>.dots-cont{position:fixed;left:50%;top:50%;text-align:center;width:auto;z-index:200 !important;}.dot{width:15px;height:15px;background:grey;display:inline-block;border-radius:50%;right:0;bottom:0;margin:0 2.5px;position:relative}.dots-cont>.dot{position:relative;bottom:0;animation-name:jump;animation-duration:.3s;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:ease}.dots-cont .dot-1{-webkit-animation-delay:.1s;animation-delay:.1s}.dots-cont .dot-2{-webkit-animation-delay:.2s;animation-delay:.2s}.dots-cont .dot-3{-webkit-animation-delay:.3s;animation-delay:.3s}@keyframes jump{from{bottom:0}to{bottom:20px}}@-webkit-keyframes jump{from{bottom:0}to{bottom:10px}}</style>

<span class="dots-cont"><span class="dot dot-1"></span><span class="dot dot-2"></span><span class="dot dot-3"></span></span>

<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr class="dropdown">
      <th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter ID" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Name" id="dsname" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Report Type" id="dsreporttype" />
			</th>
			<th class="hasinput">
				<input type="text" class="form-control" placeholder="Filter Date" />
			</th>
		</tr>
		<tr>
      <th data-hide="phone,tablet">ID</th>
			<th data-hide="phone,tablet">Name</th>
			<th data-hide="phone,tablet">Report Type</th>
			<th data-hide="phone,tablet">Date</th>
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
			"lengthMenu": [[25, 100, -1], [25, 100, "All"]],
			"pageLength": 100,
			"processing": false,
			"serverSide": true,
		"dom": 'Blfrtip',
		 "search": {
            "caseInsensitive": false
        },
        "buttons": [
            /*{
                text: 'Reset',
                action: function ( e, dt, node, config ) {
                    reloaddsireusa();
                }
            },*/
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            {
                'extend': 'pdfHtml5',
								'title' : 'Vervantis_PDF',
                'messageTop': 'Vervantis PDF Export',
								'orientation':'landscape'
            },
            //'pdfHtml5'
            {
                'extend': 'print',
				//'title' : 'Vervantis',
                'messageTop': 'Generated by Vervantis <i>(press Esc to close)</i>',
								'orientation':'landscape'
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
                        "targets": [ 0],
                        "visible": false,
                        "searchable": false
                    }
                ],
      "order": [[3,'desc']],
			"autoWidth" : true,
			"deferRender": true,
			"ajax": "assets/ajax/server_processing_weeklyreports.php",
			
			"drawCallback" : function(settings) {
				$(".dots-cont").hide();
			},					
			"preDrawCallback": function (settings) {
				$(".dots-cont").show();
			},  
	    });

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

          //updatedrps("name");
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
          $('#dsstate').val('<?php echo $_GET["state"]; ?>').change();
          $('#dscategory').val('Financial Incentive').change();
      <?php } ?>
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
