<?php require_once("inc/init.php");
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if(!isset($_SESSION))
	sec_session_start();

if(!isset($_SESSION["user_id"]))
		die("Access Restricted.");

$user_one=$_SESSION["user_id"];
$cmpid=$_SESSION["company_id"];

if(isset($_GET["ssl"]) and !empty($_GET["ssl"])){
?>
<script>var ochartsymbol=[];</script>
<?php
	$symlist=$symarray=array();
	$sslarr=array();
	if($_GET["ssl"] != "preload"){
		$sslarr= @explode(",",$_GET["ssl"]);
	}else{
		if(isset($_GET["sid"]) and !empty($_GET["sid"])){
			$osid=$_GET["sid"];
			if($_SESSION["group_id"] != 1) $tmp_sql=" and user_id=".$user_one." "; else $tmp_sql="";
			$sql="SELECT id,symbol_list,chart_name FROM ICE.portfolio WHERE id=".$osid.$tmp_sql." LIMIT 1";
			if($stmt = $mysqli->prepare($sql)) {
				$stmt->execute();
				$stmt->store_result();
				if($stmt->num_rows > 0) {
					$stmt->bind_result($sid,$symbol_list,$chart_name);
					$stmt->fetch();
					if(!empty($symbol_list)){
						?>
						<script>parent.$("#reloadval").val("<?php echo $symbol_list; ?>");</script>
						<?php
						$sslarr= @explode(",",$symbol_list);
					}
				}
			}
			?>
			<script>


			</script>
			<?php
		}
	}
	if(!count($sslarr)) die("No data to show!");

	foreach($sslarr as $vl){
		//$symarray[]=explode("@",$vl);
		?>
		<script>ochartsymbol.push('<?php echo $vl; ?>');</script>
		<?php
	}
?>
<style>
.rnext{
  width: 150px;
  height: 80px;
  background-color: yellow;
  -ms-transform: rotate(180deg); /* IE 9 */
  -webkit-transform: rotate(180deg); /* Safari 3-8 */
  transform: rotate(180deg);
}
.prevbut{float:left;font-weight:bold;cursor: pointer;}
.nextbut{float:right;font-weight:bold;cursor: pointer;}
</style>
<link href="assets/css/jquery.multiSelect.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
<link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/plugins/datatables1.11.3/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/monthpicker/MonthPicker.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<style>
	.usage{
	  background-color: #fff;
	}
	.dataTables_paginate ul li {padding:0px !important;}
	.dataTables_paginate ul li a{margin:-1px !important;}
	.dt-buttons{
	float: right !important;
	margin: 0.9% auto !important;
	}
	.dataTables_wrapper .dataTables_length{
	float: right !important;
	margin: 1% 1% !important;
	}
	.dataTables_wrapper .dataTables_filter{
	float: left !important;
	width: auto !important;
	margin: 1% 1% !important;
	text-align:left !important;
	}
	.dataTables_wrapper .dataTables_info{margin-left:1% !important;}
	.dataTables_wrapper .dataTables_paginate{padding-bottom:0.25em !important;margin-right:1% !important;}
	.no-padding .dataTables_wrapper table, .no-padding>table{border-bottom:1px solid #cccccc !important;}
	.textcenter{text-align: center !important;}
	.padding0{padding:0 !important;}
	</style>
	<?php
	//New Codes
	$d_i=0;
	$ts1=$ts=rand(650,900);
	?>
<script>var chartsymbol=[];</script>
<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding01">
		<div class="jarviswidget jarviswidget-color-blueDark col-xs-12 col-sm-12 col-md-12 col-lg-12" data-widget-editbutton="false">
			<header>
				<span class="widget-icon"> <i class="fa fa-bar-chart"></i> </span>
				<h2>Selected Symbol List </h2>
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

					<table id="datatable_fixed_column-sl" class="table table-striped table-bordered table-hover usagedatatable" width="100%">
						<thead>
							<tr class="dropdown">
								<th class="hasinput d-1">
									<input type="text" class="form-control" placeholder="Filter Name" />
								</th>
								<th class="hasinput d-2">
									<input type="text" class="form-control" placeholder="Filter Symbol" />
								</th>
								<th class="hasinput d-3">
									<input type="text" class="form-control" placeholder="Filter Description" />
								</th>
								<th class="hasinput d-4">
									<input type="text" class="form-control" placeholder="Filter Spot Month" />
								</th>
								<th class="hasinput d-5">
									<input type="text" class="form-control" placeholder="Filter Strip Months" />
								</th>
								<th class="hasinput d-6">
									<input type="text" class="form-control" placeholder="Filter Contract Start" />
								</th>
								<th class="hasinput d-7">
									<input type="text" class="form-control" placeholder="Filter Contract End" />
								</th>
								<th class="hasinput d-8"></th>
							</tr>
							<tr>
								<th data-hide="phone">Name</th>
								<th data-hide="phone">Symbol</th>
								<th data-hide="phone,tablet">Description</th>
								<th data-hide="phone,tablet">Spot Month </th>
								<th data-hide="phone,tablet">Strip Months </th>
								<th data-hide="phone,tablet">Contract Start </th>
								<th data-hide="phone,tablet">Contract End</th>
								<th data-hide="phone,tablet">Action</th>
							</tr>
						</thead>
						<tbody>
<?php
$ct=0;
foreach($sslarr as $ky=>$vl){++$ct;
	$brokedvl=explode("@",$vl);
	if(!count($brokedvl)) continue;
	//"SELECT a.Description,a.exchange,a.clearing_code,a.`GROUP` AS commodity,a.`status`,a.contract_type,a.date_code_min,a.date_code_max,a.max_date,a.contracts,a.spot_contract,a.spot_price,a.12_strip,DATE_ADD(a.spot_contract, INTERVAL 12 MONTH) FROM ICE.clearing_code a WHERE a.`status`='Active' and a.exchange='".$mysqli->real_escape_string($brokedvl[0])."' and a.clearing_code='".$mysqli->real_escape_string($brokedvl[1])."' LIMIT 1"
	if($stmt = $mysqli->prepare("SELECT a.Description,a.exchange,a.clearing_code,a.`GROUP` AS commodity,a.`status`,a.contract_type,a.date_code_min,a.date_code_max,a.max_date,a.contracts,a.spot_contract,a.spot_price,a.12_strip,DATE_ADD(a.spot_contract, INTERVAL 12 MONTH) FROM ICE.clearing_code a WHERE a.exchange='".$mysqli->real_escape_string($brokedvl[0])."' and a.clearing_code='".$mysqli->real_escape_string($brokedvl[1])."' LIMIT 1")) {
		$stmt->execute();
		$stmt->store_result();
		if($stmt->num_rows > 0) {
			$stmt->bind_result($tpdescription,$tpexchange,$tpclearing_code,$tpcommodity,$tpstatus,$tpcontract_type,$tpdate_code_min,$tpdate_code_max,$tpmax_date,$tpcontracts,$tpspot_contract,$tpspot_price,$tp12_strip,$tpspot_contract1);
			$stmt->fetch();

			//$ssllist[]=array($tpclearing_code,$tpdescription,$tpcontract_type,$tpspot_contract,$tpspot_contract1,"contracttype"=>$tpcontract_type);


			if($tpcontract_type != "Monthly"){$tmpdate='<input type="text" class="changedated" value="'.$tpspot_contract.'"  rctid="'.$ct.'" disabled>';$tmpdateend='<input type="text" class="ctend ctenddaily" value="'.$tpspot_contract1.'" rctid="'.$ct.'" disabled>'; }else{ $tmpdate='<input type="text" class="changedatemm"  value="'.$tpspot_contract.'" rctid="'.$ct.'" disabled>';$tmpdateend='<input type="text" class="ctend ctendmonth" value="'.$tpspot_contract1.'" rctid="'.$ct.'" disabled>'; }




			echo '<tr><td><input type="text" class="inputsbname" rctid="'.$ct.'" exid="'.$tpexchange.'" ccid="'.$tpclearing_code.'" value="'.$tpdescription.'"></td>';
			echo '<td>'.$tpclearing_code.'</td>';
			echo '<td>'.$tpdescription.'</td>';
			echo '<td><select class="spmonth" rctid="'.$ct.'"><option value="1">Static</option><option selected value="spot">Spot</option></select></td>';
			echo '<td><select id="tags'.$ct.'" class="form-control strmonth dtags" rctid="'.$ct.'"  type="text"><option value="0">Static</option><option value="12" selected>12</option><option value="24">24</option><option value="36">36</option></select></td>';
			echo '<td>'.$tmpdate.'</td>';
			echo '<td>'.$tmpdateend.'</td>';
			echo '<td class="textcenter"><span class="glyphicon glyphicon-remove red symchoiceminus" rexid="'.$tpexchange.'" rccid="'.$tpclearing_code.'" rctid="'.$ct.'"></span><input type="hidden" class="fsct" rctid="'.$ct.'" value="'.$tpspot_contract.'"><input type="hidden" class="fsctend" rctid="'.$ct.'" value="'.$tpspot_contract1.'"><input type="hidden" class="contracttype" rctid="'.$ct.'" value="'.$tpcontract_type.'"></td></tr>';
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

	</article>
</div>

<!-- end row -->
<!-- end widget grid -->
<!--<script src="../assets/js/libs/jquery-2.1.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
<script src="../assets/plugins/monthpicker/jquery.maskedinput.min.js"></script>
<script src="../assets/plugins/monthpicker/MonthPicker.min.js"></script>
<script src="../assets/js/datePicker.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="/assets/js/jquery.multiSelect.js" type="text/javascript"></script>
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

		/* COLUMN FILTER  */
			var otables1 = $("#datatable_fixed_column-sl").DataTable( {
				"lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
				"pageLength": 10,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": false,
				"paging": false,
				"info" : false,
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
				"autoWidth" : true
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column-sl .sssdrp").on( 'keyup change', function () {
	        otables1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    } );
	    $("#datatable_fixed_column-sl thead th input[type=text]").on( 'keyup change', function () {;

	        otables1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });

	var ct=1;




		$('body').off( 'focus', '.changedatemm');
		$('body').on('focus',".changedatemm", function(){
			//$(this).MonthPicker({Button: false });
			$(this).MonthPicker({Button: false, MonthFormat: 'yy-mm-01'});
			//this).MonthPicker({ Button: false,'option', 'MonthFormat','yy-mm-dd' });
    /*$(this).datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });*/
		});

		$(document).off( 'blur', '.changedatemm');
		$(document).on('blur',".changedatemm", function(){
			var thischgndtmm=$(this);
			var newchangemonth=thischgndtmm.val();
			var chgndtmrctid=thischgndtmm.attr("rctid");
			var chgndtmmstmonth=$('.strmonth[rctid="'+chgndtmrctid+'"]').val();
			$('.ctend[rctid="'+chgndtmrctid+'"]').val(addMonthsUTC(new Date($('.ctend[rctid="'+chgndtmrctid+'"]').val()),chgndtmmstmonth));
			//alert(chartsymbol);
			//////////////////////////////////
			//recreatechart(thischgndtmm);
			recreatechartall();
		});

		$(document).off( 'blur', '.changedated');
		$(document).on('blur',".changedated", function(){
			var thischgndtmm=$(this);
			var newchangemonth=thischgndtmm.val();
			var chgndtmrctid=thischgndtmm.attr("rctid");
			var chgndtmmstmonth=$('.strmonth[rctid="'+chgndtmrctid+'"]').val();
			$('.ctend[rctid="'+chgndtmrctid+'"]').val(addMonthsUTC(new Date($('.ctend[rctid="'+chgndtmrctid+'"]').val()),chgndtmmstmonth));
			//alert(chartsymbol);

			/////////////////////////////
			//recreatechart(thischgndtmm);
			recreatechartall();
		});

		$(document).off( 'blur', '.ctend');
		$(document).on('blur',".ctend", function(){
			//var thisctend=$(this);

			//////////////////////////////////
			//recreatechart(thisctend);
			recreatechartall();
		});

		$(document).off( 'blur', '.inputsbname');
		$(document).on('blur',".inputsbname", function(){
			//var thisinputsbname=$(this);
			//var chartsymbol=[];
			//recreatechart(thisinputsbname);
			recreatechartall();
		});


function recreatechart(thisrecreate){
	var chartsymbol=[];
	//$(document).on("each",".symchoiceminus",function() {alert($(this).attr("rctid"));
	$('span.symchoiceminus').each(function(){//alert($(this).attr("rctid"));
		recreatechartnew($(this).attr("rctid"));
	});
}

function recreatechartold(thisrecreate){
			var recreaterctid=thisrecreate.attr("rctid");
			var recreateend=$('.ctend[rctid="'+recreaterctid+'"]').val();
			if($('.changedated[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedated[rctid="'+recreaterctid+'"]').val(); }
			else if($('.changedatemm[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedatemm[rctid="'+recreaterctid+'"]').val(); }

			var recreaterexid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rexid');
			var recreaterccid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rccid');
			var recreateregex=recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@';
			//alert(tempregex);alert(chartsymbol);
			/*chartsymbol.filter(function(recreateword,recreateindex){
				if(recreateword.match(recreateregex)){
					chartsymbol.splice(recreateindex, 1);*/
//////////////////////
			var recreateinputsbname=$('.inputsbname[rctid="'+recreaterctid+'"]').val();
			var recreatedrstart=$('.spmonth[rctid="'+recreaterctid+'"]').val();
			var recreatedrend=$('.strmonth[rctid="'+recreaterctid+'"]').val();
			var recreatecontracttyp=$('.contracttype[rctid="'+recreaterctid+'"]').val();

			chartsymbol.push(recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@'+recreateinputsbname+'@'+recreatechangemonth+'@'+recreateend+'@'+recreatedrstart+'@'+recreatedrend+'@'+recreatecontracttyp);
			parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?ct="+ Math.random() +"&action=chart&sym="+chartsymbol.join(","));
///////////////////////////
				/*	return true;
				}else{
					return false;
				}
			});	*/

}



		$('body').off( 'focus', '.ctendmonth');
		$('body').on('focus',".ctendmonth", function(){
			$(this).MonthPicker({Button: false, MonthFormat: 'yy-mm-01'  });
		});

		$('body').off( 'focus', '.ctenddaily');
		$('body').on('focus',".ctenddaily", function(){
			$(this).datepicker({
			  changeMonth: true,
			  changeYear: true,
			  dateFormat: 'yy-mm-dd'
			});
		});

		$('body').off( 'focus', '.changedated');
		$('body').on('focus',".changedated", function(){
			$(this).datepicker({
			  changeMonth: true,
			  changeYear: true,
			  dateFormat: 'yy-mm-dd'
			});
		});


////////////////////////////////

		//$( ".selectsym" ).click(function() {
		$( document ).off( 'click', '.selectsym');
		$( document ).on( 'click', '.selectsym', function () {
			var pthis=$(this);
			var ctid=pthis.attr("ctid");
			var fdescription= $('.fdescription'+ctid).html();
			var fexchange= $('.fexchange'+ctid).html();
			var fsymbol= $('.fsymbol'+ctid).html();
			var fcontracttype= $('.fcontracttype'+ctid).html();
			var fspot_contract= $('.fspot_contract'+ctid).html();
			var fspot_contract1= $('.fspot_contract1'+ctid).html();
			if(fcontracttype != "Monthly"){var tmpdate='<input type="text" class="changedated" value="'+fspot_contract+'"  rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctenddaily" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }else{ var tmpdate='<input type="text" class="changedatemm"  value="'+fspot_contract+'" rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctendmonth" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }
			//if(fcontracttype != "Monthly"){var tmpdate=fspot_contract; }else{ var tmpdate='<input type="text" class="changedate">' }

			otablesl.row.add( [
			'<input type="text" class="inputsbname" rctid="'+ct+'" exid="'+fexchange+'" ccid="'+fsymbol+'" value="">',
			fsymbol,
			fdescription,
			'<select class="spmonth" rctid="'+ct+'"><option value="1">Static</option><option selected value="spot">Spot</option></select>',
			'<select id="tags'+ct+'" class="strmonth" rctid="'+ct+'"  type="text"><option value="0">Static</option><option value="12" selected>12</option><option value="24">24</option><option value="36">36</option></select>',
			tmpdate,
			tmpdateend,
			'<span class="glyphicon glyphicon-remove red symchoiceminus" rexid="'+fexchange+'" rccid="'+fsymbol+'" rctid="'+ct+'"></span><input type="hidden" class="fsct" rctid="'+ct+'" value="'+fspot_contract+'"><input type="hidden" class="fsctend" rctid="'+ct+'" value="'+fspot_contract1+'"><input type="hidden" class="contracttype" rctid="'+ct+'" value="'+fcontracttype+'">' ] ).draw();

			$('#tags'+ct).select2({
				  //tags: true
			  createTag: function (params) {
				var term = $.trim(params.term);

				if (Number.isInteger(term) === false) {
				  return null;
				}

				if (term >100 || term < 0) {
				  return null;
				}

				return {
				  id: term,
				  text: term,
				  newTag: true
				}
				}
			});
			symchoiceadd(fsymbol,fexchange,ct);
		  ct=(ct+1);
		});


		$(document).off("change",".strmonth");
		$(document).on("change",".strmonth",function() {
			var pthis=$(this);
			var rctid=pthis.attr("rctid");
			var current_date=new Date();
			var cmonth = current_date.getMonth();

			var cntstart="";
			var strmonth="";
			var selectedmonth=12;

			if(pthis.val() !=""){ strmonth=pthis.val(); }

			if($('.changedated[rctid="'+rctid+'"]').length){ var cntstart=$('.changedated[rctid="'+rctid+'"]'); }
			else if($('.changedatemm[rctid="'+rctid+'"]').length){ var cntstart=$('.changedatemm[rctid="'+rctid+'"]'); }

			if($('.ctenddaily[rctid="'+rctid+'"]').length){ var cnttend=$('.ctenddaily[rctid="'+rctid+'"]'); }
			else if($('.ctendmonth[rctid="'+rctid+'"]').length){ var cnttend=$('.ctendmonth[rctid="'+rctid+'"]'); }

			if(cntstart != "" && strmonth != "" && cntstart.val() != ""){
				if(strmonth !="0"){
					cnttend.prop('disabled', true);
					selectedmonth=strmonth;
				}else{
					cnttend.prop('disabled', false);
					selectedmonth=cmonth;
				}

				$('.ctend[rctid="'+rctid+'"]').val(addMonthsUTC(new Date(cntstart.val()),selectedmonth));
			}
			//////////////////////////////////////
			//recreatechart(pthis);
			recreatechartall();
		});




		$(document).off("change",".spmonth");
		$(document).on("change",".spmonth",function() {
			var pthis=$(this);
			var rctid=pthis.attr("rctid");

			var current_date=new Date();
			var cmonth = current_date.getMonth();
			var curr_date = new Date();

			var fsct="";
			var fsctend="";
			var cntstart="";
			var strmonth="";
			var selectedmonth=12;
			if($('.fsct[rctid="'+rctid+'"]').val() !=""){ fsct=$('.fsct[rctid="'+rctid+'"]').val(); }
			if($('.fsctend[rctid="'+rctid+'"]').val() !=""){ fsctend=$('.fsctend[rctid="'+rctid+'"]').val(); }
			if($('.strmonth[rctid="'+rctid+'"]').val() !=""){ strmonth=$('.strmonth[rctid="'+rctid+'"]').val(); }

			if($('.changedated[rctid="'+rctid+'"]').length){ var cntstart=$('.changedated[rctid="'+rctid+'"]'); }
			else if($('.changedatemm[rctid="'+rctid+'"]').length){ var cntstart=$('.changedatemm[rctid="'+rctid+'"]'); }

			if(cntstart != "" && fsct !="" && strmonth != ""){
				if(strmonth !="0"){
					selectedmonth=strmonth;
				}else{
					selectedmonth=cmonth;
				}

				if(pthis.val()=="spot"){
					cntstart.prop('disabled', true);
					cntstart.val(fsct);

					$('.ctend[rctid="'+rctid+'"]').val(addMonthsUTC(new Date(fsct),selectedmonth));

				}else{
					var mysqldateformat=mysqlDate();
					cntstart.prop('disabled', false);
					cntstart.val(mysqldateformat);

					$('.ctend[rctid="'+rctid+'"]').val(addMonthsUTC(new Date(mysqldateformat),selectedmonth));
				}
			}

			//recreatechart(pthis);
			recreatechartall();
		});

		$(document).off("click",".symchoiceminus");
		$(document).on("click",".symchoiceminus",function() {
			var rthis=$(this);
			var rccid=rthis.attr("rccid");
			var rexid=rthis.attr("rexid");
			var rctid=rthis.attr("rctid");
			if(chartsymbol.length < 0){
			  //alert("Minimum 1 Symbols allowed to exist");
			  alert("Error occured. Please try after sometime.");
			}else{
				rthis.closest('tr').remove();
				recreatechartall();
				//otablesl.draw();
				//otablesl.row( rthis.parents('tr') ).remove().draw();
		  }
		});

		function mysqlDate123(mdate){
			mdate = mdate || new Date();
			return mdate.toISOString().split('T')[0];
		}

		function addMonthsUTC(adate,mcount){
			var aCurrentDate = new Date(adate);
			aCurrentDate.setMonth(aCurrentDate.getMonth() + parseInt(mcount));
			return mysqlDate(aCurrentDate);
		}

		/*function mysqlDate(mdate){
			mdate = mdate || new Date();
			var utoffset = mdate.getTimezoneOffset()
			//mdate = new Date(mdate.getTime() + (utoffset*60*1000))
			return mdate.toISOString().split('T')[0]
		}*/
		function mysqlDate(date){
			date = date || new Date();
			return date.toISOString().split('T')[0];
		}

		function addMonths(date, months) {
			var ddate=new Date();
			var d = ddate.getDate();
			ddate.setMonth(ddate.getMonth() + 1 +months);
			if (ddate.getDate() != d) {
			  ddate.setDate(0);
			}
			return ddate;
		}



	};

	function multifilter(nthis,fieldname,otables1)
	{
			var selectedoptions = [];
            $.each($("input[name='multiselect_"+fieldname+"']:checked"), function(){
                selectedoptions.push($(this).val());
            });
			otables1
	         .column( $(nthis).parent().index()+':visible' )
			 .search("^" + selectedoptions.join("|") + "$", true, false, true)
			 .draw();
	}

	function multilist(indexno)
	{
		var items=[], options=[];
		$('#datatable_fixed_column-sl tbody tr td:nth-child('+indexno+')').each( function(){
		   items.push( $(this).text() );
		});
		var items = $.unique( items );
		$.each( items, function(i, item){
			options.push('<option value="' + item + '">' + item + '</option>');
		})
		return options;
	}



function selectsymbol(exchn,symbl,alertres,ct){
	if(exchn == "" || symbl==""){return false;}


			var otablesl = $("#datatable_fixed_column-sl").DataTable( {
				"lengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
				"pageLength": 10,
				"retrieve": true,
				"scrollCollapse": true,
				"searching": false,
				"paging": false,
				//"order": [[ 6, "desc" ]],
				"dom": 'lfrtip',
				"buttons": [
					//'copyHtml5',
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
				]
			});

	    // custom toolbar
	    $("div.toolbar").html('<div class="text-right"><img src="assets/img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	    // Apply the filter
	    $("#datatable_fixed_column-sl thead th input[type=text]").on( 'keyup change', function () {

	        otablesl
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();

	    });








/*
		$.ajax({
			type: 'post',
			url: '../assets/includes/tradingplatform.inc.php',
			data: {exchn:exchn,symbl:symbl},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
///////////////////
			var fsymbol= symbl;
			var fexchange= exchn;
			var fdescription= results.sresult.description;
			var fcontracttype= results.sresult.contracttype;
			var fspot_contract= results.sresult.contractstart;
			var fspot_contract1= results.sresult.contractend;
			if(fcontracttype != "Monthly"){var tmpdate='<input type="text" class="changedated" value="'+fspot_contract+'"  rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctenddaily" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }else{ var tmpdate='<input type="text" class="changedatemm"  value="'+fspot_contract+'" rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctendmonth" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }
			//if(fcontracttype != "Monthly"){var tmpdate=fspot_contract; }else{ var tmpdate='<input type="text" class="changedate">' }

			otablesl.row.add( [
			'<input type="text" class="inputsbname" rctid="'+ct+'" exid="'+fexchange+'" ccid="'+fsymbol+'" value="">',
			fsymbol,
			fdescription,
			'<select class="spmonth" rctid="'+ct+'"><option value="1">Static</option><option selected value="spot">Spot</option></select>',
			'<select id="tags'+ct+'" class="strmonth" rctid="'+ct+'"  type="text"><option value="0">Static</option><option value="12" selected>12</option><option value="24">24</option><option value="36">36</option></select>',
			tmpdate,
			tmpdateend,
			'<span class="glyphicon glyphicon-remove red symchoiceminus" rexid="'+fexchange+'" rccid="'+fsymbol+'" rctid="'+ct+'"></span><input type="hidden" class="fsct" rctid="'+ct+'" value="'+fspot_contract+'"><input type="hidden" class="fsctend" rctid="'+ct+'" value="'+fspot_contract1+'"><input type="hidden" class="contracttype" rctid="'+ct+'" value="'+fcontracttype+'">' ] ).draw();

			$('#tags'+ct).select2({
				  //tags: true
			  createTag: function (params) {
				var term = $.trim(params.term);

				if (Number.isInteger(term) === false) {
				  return null;
				}

				if (term >100 || term < 0) {
				  return null;
				}

				return {
				  id: term,
				  text: term,
				  newTag: true // add additional parameters
				}
				}
			});
			//symchoiceadd(fsymbol,fexchange,ct);
		  ct=(ct+1);

//////////////////
					}else{
						if(alertres!=1)	alert(results.error);
					}
				}else{
					if(alertres!=1)	alert("Error in request. Please try again later.");
				}
			}
		  });

*/
				$.ajax({
					type: 'post',
					url: 'assets/includes/tradingplatform.inc.php',
					data: {exchn:exchn,symbl:symbl},
					success: function (result) {
						if (result != false)
						{
							var results = JSON.parse(result);
							if(results.error == "")
							{
		///////////////////
					var fsymbol= symbl;
					var fexchange= exchn;
					var fdescription= results.sresult.description;
					var fcontracttype= results.sresult.contracttype;
					var fspot_contract= results.sresult.contractstart;
					var fspot_contract1= results.sresult.contractend;
					if(fcontracttype != "Monthly"){var tmpdate='<input type="text" class="changedated" value="'+fspot_contract+'"  rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctenddaily" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }else{ var tmpdate='<input type="text" class="changedatemm"  value="'+fspot_contract+'" rctid="'+ct+'" disabled>';var tmpdateend='<input type="text" class="ctend ctendmonth" value="'+fspot_contract1+'" rctid="'+ct+'" disabled>'; }
					//if(fcontracttype != "Monthly"){var tmpdate=fspot_contract; }else{ var tmpdate='<input type="text" class="changedate">' }

					otablesl.row.add( [
					'<input type="text" class="inputsbname" rctid="'+ct+'" exid="'+fexchange+'" ccid="'+fsymbol+'" value="">',
					fsymbol,
					fdescription,
					'<select class="spmonth" rctid="'+ct+'"><option value="1">Static</option><option selected value="spot">Spot</option></select>',
					'<select id="tags'+ct+'" class="strmonth dtags" rctid="'+ct+'"  type="text"><option value="0">Static</option><option value="12" selected>12</option><option value="24">24</option><option value="36">36</option></select>',
					tmpdate,
					tmpdateend,
					'<span class="glyphicon glyphicon-remove red symchoiceminus" rexid="'+fexchange+'" rccid="'+fsymbol+'" rctid="'+ct+'"></span><input type="hidden" class="fsct" rctid="'+ct+'" value="'+fspot_contract+'"><input type="hidden" class="fsctend" rctid="'+ct+'" value="'+fspot_contract1+'"><input type="hidden" class="contracttype" rctid="'+ct+'" value="'+fcontracttype+'">' ] ).draw();

					//$('#tags'+ct).select2({
					$('.dtags').select2({
					tags: true/*,
					  createTag: function (params) {
						var term = $.trim(params.term);

						if (Number.isInteger(term) === false) {
						  return null;
						}

						if (term >100 || term < 0) {
						  return null;
						}

						return {
						  id: term,
						  text: term,
						  newTag: true // add additional parameters
						}
						}*/
					});
					//$(".select .select2-container").css({"position": "relative !important", "z-index":"99 !important"});
					//.select2-container{position:relative !important;z-index:99 !important;}
			//symchoiceadd2(fsymbol,fexchange,ct);
				  ct=(ct+1);

		//////////////////
							}else
								alert(results.error);
						}else{
							alert("Error in request. Please try again later.");
						}
					}
				  });

}

function symchoiceadd2(rccid,rexid,rctid){

		  if(chartsymbol.length >= 6){alert("Maximum 6 Symbols allowed to Add"); }
		  else{

			var ctaddstart="";

			if($('.changedated[rctid="'+rctid+'"]').length){ var ctaddstart=$('.changedated[rctid="'+rctid+'"]').val(); }
			else if($('.changedatemm[rctid="'+rctid+'"]').length){ var ctaddstart=$('.changedatemm[rctid="'+rctid+'"]').val(); }

			var inputsbname=$('.inputsbname[rctid="'+rctid+'"]').val();//alert(inputsbname);
			var ctend=$('.ctend[rctid="'+rctid+'"]').val();

			var drstart=$('.spmonth[rctid="'+rctid+'"]').val();
			var drend=$('.strmonth[rctid="'+rctid+'"]').val();


			var contracttyp=$('.contracttype[rctid="'+rctid+'"]').val();

			chartsymbol.push(rexid+'@'+rccid+'@'+rctid+'@'+inputsbname+'@'+ctaddstart+'@'+ctend+'@'+drstart+'@'+drend+'@'+contracttyp);

			parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?action=chart&sym="+chartsymbol.join(","));
			parent.$('#tpchart').show();
			$("#tpchart").css("display", "block");
			$("#tpchartcont").css("display", "block");
		  }
}



$(document).ready(function(){
  $(".strmonth").select2({
	tags: true
  });

	var chartsymbol=[];
	recreatechartall();

	//$sslarr


	//$(document).on("each",".symchoiceminus",function() {alert($(this).attr("rctid"));
	//$('.symchoiceminus').each(function(){//alert($(this).attr("rctid"));
		//recreatechartnew($(this).attr("rctid"));
	//});


	if(ochartsymbol.length > 0){
		chartsymbol=ochartsymbol;
		var i;
		var ct=1;

setTimeout(function(){
		for (i = 0; i < ochartsymbol.length; i++) {
			var fields = ochartsymbol[i].split('@');
			$('.inputsbname[rctid="'+fields[2]+'"]').val(fields[3]);
			$('.changedatemm[rctid="'+fields[2]+'"]').val(fields[4]);
			$('.ctend[rctid="'+fields[2]+'"]').val(fields[5]);
			$('.spmonth[rctid="'+fields[2]+'"]').val(fields[6]);
			$('.strmonth[rctid="'+fields[2]+'"]').val(fields[7]);
			$('.strmonth[rctid="'+fields[2]+'"]').val(fields[7]).trigger('change')

			//$('.symchoiceadd[rctid="'+fields[2]+'"]').css("display","none !important");
			//$('.symchoiceminus[rctid="'+fields[2]+'"]').css("display","block !important");
		}

		//parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?action=chart&sym="+chartsymbol.join(","));
		//parent.$('#tpchart').show();

}, 2000);

	}


	/*if(ochartsymbol.length > 0){
		chartsymbol=ochartsymbol;
		var i;
		var ct=1;

setTimeout(function(){
		for (i = 0; i < ochartsymbol.length; i++) {
			var fields = ochartsymbol[i].split('@');//alert(fields);
			//$('.selectsym[exid="'+fields[0]+'"][ccid="'+fields[1]+'"]').trigger("click");
			selectsymbol(fields[0],fields[1],1,(i+1));
		}
setTimeout(function(){
		for (i = 0; i < ochartsymbol.length; i++) {
			$('.inputsbname[rctid="'+fields[2]+'"]').val(fields[3]);
			$('.changedatemm[rctid="'+fields[2]+'"]').val(fields[4]);
			$('.ctend[rctid="'+fields[2]+'"]').val(fields[5]);
			$('.spmonth[rctid="'+fields[2]+'"]').val(fields[6]);
			$('.strmonth[rctid="'+fields[2]+'"]').val(fields[7]);

			//$('.symchoiceadd[rctid="'+fields[2]+'"]').css("display","none !important");
			//$('.symchoiceminus[rctid="'+fields[2]+'"]').css("display","block !important");
		}

}, 1000);
		parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?action=chart&sym="+chartsymbol.join(","));
		parent.$('#tpchart').show();

}, 1000);

//alert(chartsymbol);
	}*/
});


function recreatechartall(){
	var chartsymbol=[];
	$('.symchoiceminus').each(function(){//alert($(this).attr("rctid"));
		var recreaterctid=$(this).attr("rctid");
		var recreateend=$('.ctend[rctid="'+recreaterctid+'"]').val();
		if($('.changedated[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedated[rctid="'+recreaterctid+'"]').val(); }
		else if($('.changedatemm[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedatemm[rctid="'+recreaterctid+'"]').val(); }

		var recreaterexid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rexid');
		var recreaterccid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rccid');
		var recreateregex=recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@';

		var recreateinputsbname=$('.inputsbname[rctid="'+recreaterctid+'"]').val().replaceAll(" ", "");
		var recreatedrstart=$('.spmonth[rctid="'+recreaterctid+'"]').val();
		var recreatedrend=$('.strmonth[rctid="'+recreaterctid+'"]').val();
		var recreatecontracttyp=$('.contracttype[rctid="'+recreaterctid+'"]').val();

		chartsymbol.push(recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@'+recreateinputsbname+'@'+recreatechangemonth+'@'+recreateend+'@'+recreatedrstart+'@'+recreatedrend+'@'+recreatecontracttyp);
	});
	if(chartsymbol.length > 0){
		var tmpchatsymb=chartsymbol.join(',');
		parent.$("#reloadval").val(tmpchatsymb);
		parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?ct="+ Math.random() +"&action=chart&sym="+chartsymbol.join(",")+"&sid=<?php if(isset($_GET['sid']) and !empty($_GET['sid'])){ echo $_GET['sid']; } ?>");
		$("#tpchart").css("display", "block");
		$("#tpchartcont").css("display", "block");
	}
}




function recreatechartnew(recreaterctid){//alert(recreaterctid);
			//var recreaterctid=thisrecreate.attr("rctid");
			var recreateend=$('.ctend[rctid="'+recreaterctid+'"]').val();
			if($('.changedated[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedated[rctid="'+recreaterctid+'"]').val(); }
			else if($('.changedatemm[rctid="'+recreaterctid+'"]').length){ var recreatechangemonth=$('.changedatemm[rctid="'+recreaterctid+'"]').val(); }

			var recreaterexid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rexid');
			var recreaterccid=$('.symchoiceminus[rctid="'+recreaterctid+'"]').attr('rccid');
			var recreateregex=recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@';
			//alert(tempregex);
			//alert(chartsymbol);
			/*chartsymbol.filter(function(recreateword,recreateindex){
				if(recreateword.match(recreateregex)){
					chartsymbol.splice(recreateindex, 1);*/
//////////////////////
			var recreateinputsbname=$('.inputsbname[rctid="'+recreaterctid+'"]').val();
			var recreatedrstart=$('.spmonth[rctid="'+recreaterctid+'"]').val();
			var recreatedrend=$('.strmonth[rctid="'+recreaterctid+'"]').val();
			var recreatecontracttyp=$('.contracttype[rctid="'+recreaterctid+'"]').val();

			chartsymbol.push(recreaterexid+'@'+recreaterccid+'@'+recreaterctid+'@'+recreateinputsbname+'@'+recreatechangemonth+'@'+recreateend+'@'+recreatedrstart+'@'+recreatedrend+'@'+recreatecontracttyp);
			parent.$('#tpchart').attr('src', "assets/ajax/trading-platform-1.php?ct="+ Math.random +"&action=chart&sym="+chartsymbol.join(","));
			$("#tpchart").css("display", "block");
			$("#tpchartcont").css("display", "block");
///////////////////////////
			/*		return true;
				}else{
					return false;
				}
			});	*/

}


	// load related plugins
loadScript("assets/plugins/datatables1.11.3/datatables.min.js", pagefunction);
</script>
<?php
}
?>
