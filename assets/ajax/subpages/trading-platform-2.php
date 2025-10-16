<?php //require_once("../inc/init.php");
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
//if($user_one != 1) die("under construction");

if(isset($_GET["action"]) and $_GET["action"]=="portfolio"){
?>
<link rel="stylesheet" href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/fontawesome/css/fontawesome.min.css">
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
#datatable_fixed_column{border-bottom: 1px solid #ccc !important;}}
#datatable_fixed_column option {
  color: #555;
}
#datatable_fixed_column tr.dropdown select{font-weight: 400 !important;}
.red2{color:red;cursor:pointer;}
.blue{color:#3276b1;cursor:pointer;}
.tcenter{text-align:center;}
.padding2{padding:2px;}
.txtcenter{text-align:center;}
.fullwidth{width:100%;}
</style>
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Portfolio </h2>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body no-padding symlist">
						<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr class="dropdown" style="display:none;">
									<th class="hasinput d-1">
										<input type="text" class="form-control" placeholder="Filter Name" />
									</th>
									<th class="hasinput d-2">
										<input type="text" class="form-control" placeholder="Filter Symbol" />
									</th>
									<th class="hasinput d-9"></th>
								</tr>
								<tr>
									<th>
          <button type="button" id="cselectAll" class="main"> Select All </button></th>
									<th data-hide="phone">Chart Name</th>
									<th data-hide="phone">Lines Name</th>
									<th data-hide="phone,tablet">Action</th>
								</tr>
							</thead>
							<tbody>


<?php
	$symlist=$symarray=$tpcchartarr=$sortorder=array();
	$tmpsql="";
	$sql="SELECT sort FROM ubm_ice.sort_portfolio where user_id=".$user_one." LIMIT 1";
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($sort);
			$stmt->fetch();
			if(!empty($sort)){
				$sortorder=explode(",",$sort);
			}
		}
	}


	if($_SESSION["group_id"] != 1 and $_SESSION["group_id"] != 2) $tmpsql=" WHERE user_id=".$user_one;
	//$sql="SELECT id,symbol_list,chart_name,sort FROM ICE.portfolio".$tmpsql." ORDER BY sort";
	$sql="SELECT id,symbol_list,chart_name FROM ubm_ice.portfolio WHERE user_id=".$user_one;
	if($stmt = $mysqli->prepare($sql)) {
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
			$stmt->bind_result($sid,$symbol_list,$chart_name);
			while($stmt->fetch()) {
				if(!empty($symbol_list)){
					$symarray=array();
					$symlist=explode(",",$symbol_list);
					$tpcchartarr[$sid]=array("sid"=>$sid,"chartname"=>$chart_name);
					foreach($symlist as $vl){
						$symarraytemp=explode("@",$vl);
						//if(count($symarraytemp)) $symarray[]="Exchange:".$symarraytemp[0]." + Clearing Code:".$symarraytemp[1];
						if(count($symarraytemp)) $symarray[]=$symarraytemp[3];
					}
				}
?>
							<tr id="tr<?php echo $sid; ?>">
								<td><input dcid="<?php echo $sid; ?>" class="compareit" type="checkbox"/></td>
								<td><input type="text" class="tp2name fullwidth" value="<?php echo $chart_name; ?>" id="<?php echo $sid; ?>"></td>
								<td><?php echo implode(", ",$symarray); ?></td>
								<td class="txtcenter"><span class="glyphicon glyphicon-edit blue" title="Edit" onclick="editportfolio(<?php echo $sid; ?>)"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-remove red2" title="Delete" onclick="deleteportfolio(<?php echo $sid; ?>)"></span></td>
							</tr>
<?php
			}
		}
	}
?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
<div style="text-align:center;margin-top:12px;margin-bottom:12px;"><button id="addtoportfolio" class="btn-primary" style="line-height:1.5;cursor:pointer;">Add new</button></div>
<script type="text/javascript">

	pageSetUp();


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

		/* COLUMN FILTER  */
			var otable = $("#datatable_fixed_column").DataTable( {
				"lengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
				"pageLength": 50,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": true,
				"paging": true,
				//"order": [[ 6, "desc" ]],
				"dom": 'Blfrtip',
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
						'extend': 'colvis',
						'columns': ':gt(0)'
					}
				],
				/*"columnDefs": [
						{
							"targets": [ 1 ],
							"visible": false,
							"searchable": false
						}
				],*/
				"autoWidth" : true/*,
				initComplete: function () {
					this.api().columns([8]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control" id="dstatid"><option value="">Filter Status</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-10').empty() )
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
										if(d=='Active'){
											select.append( '<option value="'+d+'" SELECTED>'+d+'</option>' );
										}else{
											select.append( '<option value="'+d+'">'+d+'</option>' );
										}
										darr.push(d);
									}
							 } );
							 val='Active';
							  column
								   .search( val.replace(/(<([^>]+)>)/ig,"") ? '^'+val.replace(/(<([^>]+)>)/ig,"")+'$' : '', true, false )
								   .draw();
					} );
					this.api().columns([2]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Sub Group</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-4').empty() )
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
					this.api().columns([3]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Category</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-5').empty() )
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
					this.api().columns([4]).every( function () {
						 var column = this;
						 var select = $('<select class="form-control"><option value="">Filter Sub Category</option></select>')
							  .appendTo( $('#datatable_fixed_column .dropdown .d-6').empty() )
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

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });
	};

	$('.tp2name').off( 'change');
	$('.tp2name').on('change',function(e){
		var tp2this=$(this);
		var tp2sid=tp2this.attr("id");
		var tp2name=tp2this.val();

		$.ajax({
			type: 'post',
			url: '../assets/includes/tradingplatform.inc.php',
			data: {uid:tp2sid,pname:tp2name},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						$("#charth2"+tp2sid).text(tp2name);
						//alert("Name Added to portfolio!");
					}else
						alert(results.error);
				}else{
					alert("Error in request. Please try again later.");
				}
			}
		  });
	});

	$('#cselectAll').off( 'click');
	$('#cselectAll').on('click',function(e){
			if($(this).hasClass('checkedAll')) {
				$('input.compareit').prop('checked', false);
				$(this).removeClass('checkedAll');
				$('#cselectAll').text(" Select All");
			} else {
				$('input.compareit').prop('checked', true);
				$(this).addClass('checkedAll');
				$('#cselectAll').text(" Deselect All");
			}

			callcompare();
	});

	$('input.compareit').off( 'change');
	$('input.compareit').on('change',function(e){
			callcompare();
	});

	function callcompare(){
		//var numItems = $('input.compareit').length;//listitemClass chartno
		$('input:checkbox.compareit').each(function () {vthis=$(this);
       if(this.checked){$("#chartno"+vthis.attr("dcid")).show();  }
			 else{$("#chartno"+vthis.attr("dcid")).hide(); }
  	})
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

	function showchart(sid){
		alert("UnderConstruction!");
	}

	function deleteportfolio(sid){
		if (confirm('Are you sure to delete this?')) {
			$.ajax({
				type: 'post',
				url: '../assets/includes/tradingplatform.inc.php',
				data: {deleteportfolio: sid},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							$("#tr"+sid).remove();
							$("#chartno"+sid).remove();
							//otable.destroy();
							otable.rows().invalidate().draw();
							alert("Portfolio deleted Successfully!");
						}else
							alert(results.error);
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}

	function editportfolio(sid){
		parent.$("#tpresponse2").css("display", "none");
		parent.$("#tpchartcont2").css("display", "none");
		parent.$("#tpresponse3").css("display", "none");
		parent.$("#tpchartcont3").css("display", "none");
		parent.$("#tpresponse").css("display", "block");
		parent.$("#tpchartcont").css("display", "block");
		//parent.$('#tpresponse').html('');
		//loadURL("assets/ajax/subpages/trading-platform-1.php?action=symbollist&sid=<?php /*echo $sid;*/ ?>", $('#tpresponse'));
		parent.$( "#tpresponse" ).load( "assets/ajax/trading-platform-1.php?action=symbollist&sid="+sid );
	}
$(document).ready(function(){
	$("#addtoportfolio").click(function() {
		parent.$('#button5').trigger('click');

	});
});

loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>



    <style>
        #chartlistid
        {
        list-style-type: none;
        }
        #chartlistid .listitemClass
        {
            display: inline-block;
			width:49%;
			padding:1px;
        }
		#chartlistid .listitemClass .intcon,#chartlistid .listitemClass .widget-body{
			padding:0 !important;
		}
        /* Output order styling */
        #outputvalues{
			margin: 0 2px 2px 2px;
			padding: 0.4em;
			padding-left: 1.5em;
			width: 250px;
			border: 2px solid dark-green;
			background : gray;
        }
        #chartlistid .listitemClass header
        {
			cursor: all-scroll;
        }
		#chartlistid .listitemClass .widget-body{
			margin-bottom: -4px;
		}
		#chartlistid .listitemClass .toggleclose{float:right !important;}
    </style>

    <script>
        $(function() {
            $( "#chartlistid" ).sortable({
            update: function(event, ui) {
                getIdsOfImages();
            }//end update
            });
        });

        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function (index) {
                values.push($(this).attr("id")
                        .replace("chartno", ""));
            });


			$.ajax({
				type: 'post',
				url: '../assets/includes/tradingplatform.inc.php',
				data: {chartids: values.join(",")},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							//alert("");
						}else
							alert(results.error);
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
        }
    </script>

    <div id = "chartlistid">
<?php
//$tpcchartarr=array_unique($tpcchartarr);
//$cttpchart=(count($tpcchartarr)*500);
$temparr=array();
$temptpcchartarr=$tpcchartarr;
if(count($sortorder)){
	foreach($sortorder as $kky=>$vll){
		if(isset($temptpcchartarr[$vll])){
			$temparr[]=$temptpcchartarr[$vll];
			unset($temptpcchartarr[$vll]);
		}
	}
	if(count($temptpcchartarr)){ //$temparr=array_merge($temparr,$temptpcchartarr);
		foreach($temptpcchartarr as $kcky=>$vcll){
			$temparr[]=$vcll;
		}
	}
}else $temparr=$tpcchartarr;

//print_r($temparr);
foreach($temparr as $vl){
?>
	<div class="jarviswidget jarviswidget-color-blueDark listitemClass" id="chartno<?php echo $vl['sid']; ?>"  data-widget-fullscreenbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-colorbutton="false" data-widget-refreshbutton="false" data-widget-sortable="false" role="widget">
		<header>
			<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
			<h2 id="charth2<?php echo $vl['sid']; ?>"> <?php echo $vl['chartname']; ?> </h2>
			<div class="jarviswidget-ctrls" role="menu">
				<?php if(1==2){ ?><a href="javascript:void(0);" class="button-icon toggleclose takescreenshot" rel="tooltip" title="Take Screenshot" did="<?php echo $vl['sid']; ?>" dname="<?php echo $vl['chartname']; ?>" data-placement="bottom" data-original-title="Close"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;<?php } ?>

				<a href="javascript:void(0);" class="button-icon toggleclose" rel="tooltip" title="" data-placement="bottom" data-original-title="Close" onclick="deleteportfolio(<?php echo $vl['sid']; ?>)"><i class="fa fa-times"></i></a>
				<button id="shv<?php echo $vl['sid']; ?>" onclick="shvolumecall(<?php echo $vl['sid']; ?>)" style="line-height:1.5;cursor:pointer;float:right;margin-right:12px;margin-top:3px;color: black !important;">Show Volume</button>
			</div>
		</header>
		<div class="intcon">
			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->

			</div>
			<!-- end widget edit box -->
			<div class="widget-body">
				<iframe id="tpcc<?php echo $vl['sid']; ?>" src="assets/ajax/subpages/trading-platform-2-1.php?action=chart&cid=<?php echo $vl['sid']; ?>" width="100%" height="450px" border="0" frameBorder="0"></iframe>
			</div>
		</div>
	</div>
<?php //data-id="echo $vl['sid']; "
}
?>
    </div>
<script type="text/javascript" src="assets/js/html2canvas.min.js"></script>
<script type="text/javascript" src="assets/js/canvas2image.js"></script>
<script>
function shvolumecall(ssid){
	var frame_src = document.getElementById("tpcc"+ssid).src;
	//var returnValue = preg_replace('/&vol=*/s', '', frame_src);
	var returnValue=frame_src.replace(/&vol=[0-9]*/, "");
	//var shvname=document.getElementById("shv"+ssid).html();
	var shvname=$( "#shv"+ssid ).html();
	if(shvname=="Show Volume"){
		$("#tpcc"+ssid).attr('src', returnValue+"&vol=1");
		$("#shv"+ssid).text("Hide Volume");
	}else{
		$("#tpcc"+ssid).attr('src', returnValue+"&vol=0");
		$("#shv"+ssid).text("Show Volume");
	}

	//$("tpcc"+ssid).showhidevolume();
	//document.getElementById("tpcc"+ssid)[0].contentWindow.document.body.showhidevolume();
	//window.frames['tpcc'+ssid].showhidevolume();
	/*var el = document.getElementById("tpcc"+ssid);

	if(el.contentWindow)
	{
	   el.contentWindow.showhidevolume();
	}
	else if(el.contentDocument)
	{
	   el.contentDocument.showhidevolume();
	}*/
}
$( document ).ready(function() {


	$(document).off("click",".takescreenshot");
	$(document).on("click",".takescreenshot",function() {
		var rrthis=$(this);
		var rrdid=rrthis.attr("did");
		var rrdname=rrthis.attr("dname");
//document.getElementById('tpcc'+rrdid).contentWindow.exportPNG();
$("#tpcc"+rrdid).prop('contentWindow').exportPNG();
	//	$("#tpcc"+rrdid)[0].contentWindow.exportPNG();




/*
		var chartno="#chartno"+rrdid;
		//var rtest = $(chartno iframe "#chartdiv").get(0);
		var rtest = $("#chartno"+rrdid).get(0);
		html2canvas(rtest).then(function(canvas) {
			var canvasWidth = canvas.width;
			var canvasHeight = canvas.height;
			$('.toCanvas').after(canvas);
			Canvas2Image.saveAsImage(canvas, canvasWidth, canvasHeight, "jpeg", rrdname);
		});*/
	});
});

	function deleteportfolio(sid){
		if (confirm('Are you sure to delete this?')) {
			$.ajax({
				type: 'post',
				url: '../assets/includes/tradingplatform.inc.php',
				data: {deleteportfolio: sid},
				success: function (result) {
					if (result != false)
					{
						var results = JSON.parse(result);
						if(results.error == "")
						{
							$("#tr"+sid).remove();
							$("#chartno"+sid).remove();
							//otable.destroy();
							otable.rows().invalidate().draw();
							alert("Portfolio deleted Successfully!");
						}else
							alert(results.error);
					}else{
						alert("Error in request. Please try again later.");
					}
				}
			  });
		}
	}

</script>
<?php } ?>
