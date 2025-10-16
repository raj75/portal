<?php require_once("inc/init.php"); 
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();
	
if(!isset($_SESSION["group_id"]))
		die("Access Restricted.");
		
if(!isset($_GET["mtid"]))
		die("Wrong Parameter Provided.");
else
	$mtid=$mysqli->real_escape_string($_GET["mtid"]);
		
$user_one=$_SESSION["user_id"];


$temp_invoice=array();
	if ($stmt = $mysqli->prepare('SELECT u.user_id, a.id, u.meter_number, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure, sg.service_group FROM `usage` u, accounts a,service_group sg where u.meter_number='.$mtid.' and u.meter_number=a.meter_number and a.service_group_id=sg.service_group_id order by u.interval_end desc')) { 

//('SELECT u.id, a.id, u.meter_id, u.interval_start, u.interval_end, u.interval_value, u.unit_of_measure, sg.service_group FROM `usage` u, accounts a,service_group sg where u.meter_id='.$mtid.' and u.meter_id=a.meter_id and a.service_group_id=sg.service_group_id order by u.interval_end desc'))


        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			$stmt->bind_result($_u_id,$_a_id,$_u_meter_id,$_u_interval_start,$_u_interval_end,$_u_interval_value,$_u_unit_of_measure,$_sg_service_group);
			while($stmt->fetch()) {
				$stime=strtotime($_u_interval_start);
				$smonth=date("F",$stime);
				$syear=date("Y",$stime);

				$etime=strtotime($_u_interval_end);
				$emonth=date("F",$etime);
				$eyear=date("Y",$etime);				

				$datediff = $etime - $stime;
				$diff_date = floor($datediff/(60*60*24));		
				
				$per_day_value=$_u_interval_value/$diff_date;
				
				//Same Date
				if(($_u_interval_start == $_u_interval_end) or ($syear == $eyear and $smonth == $emonth and $_u_interval_start != $_u_interval_end))
				{
					$temp_invoice[$_u_meter_id][$syear][$smonth][]=$_u_interval_value;
				}else{//if($_u_interval_start != $_u_interval_end and $syear == $eyear and $smonth != $emonth){
					$time   = $stime;
					$last   = date('m-Y', $etime);
					
					do {
						$month = date('m-Y', $time);
						$total = date('t', $time);
						$tmonth=date('F', $time);
						$tyear=date('Y', $time);
						
						if($syear==$tyear and $smonth==$tmonth)
						{
							$daysRemaining = (int)date('t', $stime) - (int)date('j', $stime);
							$temp_invoice[$_u_meter_id][$syear][$smonth][]=$per_day_value*($daysRemaining);
						}elseif($eyear==$tyear and $emonth==$tmonth)
						{
							$daysPast = (int)date('j', $etime);
							$temp_invoice[$_u_meter_id][$eyear][$emonth][]=$per_day_value*$daysPast;						
						}else{
							$daysOfMonth = (int)date('t', $stime);
							$temp_invoice[$_u_meter_id][$tyear][$tmonth][]=$per_day_value*$daysOfMonth;						
						}

						$time = strtotime('+1 month', $time);
					} while ($month != $last);					
				}
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
/*foreach($temp_invoice[$mtid] as $kys=>$vls)
{
	//print_r($kys); print_r($vls);
	foreach($vls as $kyy=>$vll)
	{
		echo $kys.":".$kyy.":".array_sum($vll)."<br />";
	}
}*/	
	
	
//print_r($temp_invoice);	
//exit();
?>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Company 
			<span>> 
				Sites
			</span>
			<span>> 
				Accounts
			</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Hide / Show Columns </h2>

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
						
						<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr id="multiselect">
									<th class="hasinput">
										<!--<select id="selectMeterId" name="selectMeterId" multiple="multiple"></select>-->
									</th>
									<th class="hasinput">
										<select id="selectYear" name="selectYear[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectMonth" name="selectMonth[]" multiple="multiple"></select>
									</th>
									<th class="hasinput">
										<select id="selectValue" name="selectValue[]" multiple="multiple"></select>
									</th>
								</tr>
								<tr>
									<th class="hasinput">
										<!--<input type="text" class="form-control" placeholder="Filter Meter ID" />-->
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Year" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Month" />
									</th>
									<th class="hasinput">
										<input type="text" class="form-control" placeholder="Filter Value" />
									</th>
								</tr>
								<tr>
									<th data-hide="phone">Meter ID</th>
									<th data-hide="expand">Year <!--<select id="selectCompany" name="selectCompany[]" multiple="multiple"></select>--></th>
									<th>Month</th>
									<th data-hide="phone">Volume <!--<select id="selectCountry" name="selectCountry[]" multiple="multiple"></select>--></th>
								</tr>
							</thead>
							<tbody>
<?php
if(isset($temp_invoice) and count($temp_invoice) and count($temp_invoice[$mtid]))
{
	foreach($temp_invoice[$mtid] as $kys=>$vls)
	{
		foreach($vls as $kyy=>$vll)
		{
	?>
						<tr onclick='load_details("<?php echo $mtid; ?>","<?php echo $kys; ?>","<?php echo $kyy; ?>")' class='navlink'>
							<td><?php echo $mtid; ?></td>
							<td><?php echo $kys; ?></td>
							<td><?php echo $kyy; ?></td>
							<td><?php echo round(array_sum($vll),2); ?></td>
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

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->
<!--<iframe src="assets/ajax/gmapplotter.php" style="width:100%;height:500px" frameBorder="0" scrolling="no"></iframe>-->
<div id="response"></div>
<!--<select id="selectId" name="selectId[]" multiple="multiple" size="5"></select>-->
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
		//$('#response').load('assets/ajax/details.php?id='+id);
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
	    var otable = $('#datatable_fixed_column').DataTable({
			// "iDisplayLength": 5,
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
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}		
		
	    });
	    
	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
	    	   
	    // Apply the filter
	    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
	    	
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	            
	    } );
	    /* END COLUMN FILTER */

		//$('#selectMeterId').empty().append( multilist(2).join() );
		//$("#selectMeterId").multiselect();
		$('#selectYear').empty().append( multilist(2).join() );
		$("#selectYear").multiselect();
		$('#selectMonth').empty().append( multilist(3).join() );
		$("#selectMonth").multiselect();
		$('#selectValue').empty().append( multilist(4).join() );
		$("#selectValue").multiselect();

		$("#selectMeterId").on( 'keyup change', function () {multifilter(this,"selectMeterId",otable)});
		$("#selectYear").on( 'keyup change', function () {multifilter(this,"selectYear",otable)});
		$("#selectMonth").on( 'keyup change', function () {multifilter(this,"selectMonth",otable)});
		$("#selectValue").on( 'keyup change', function () {multifilter(this,"selectValue",otable)});
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
	
	loadScript("assets/js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("assets/js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("assets/js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("assets/js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("assets/js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
	
function load_details(id,year,month) {
    //$('#response').load('assets/ajax/details.php?id='+id);
	$('#response').load('assets/ajax/accdetails.php?mtid='+id+'&year='+year+'&month='+month);
	//$(window).scrollTop($('#response').offset().top);
	$('html, body').animate({
        scrollTop: $('#response').offset().top
    }, 2000);
}
</script>