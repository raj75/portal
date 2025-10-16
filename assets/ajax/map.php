<?php require_once("inc/init.php");
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
sec_session_start();

if($_SESSION["group_id"] !=1 and $_SESSION["group_id"] !=2)
	die("Restricted Access!");
?>
<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-puzzle-piece"></i> 
				Market Resources 
			<span>>  
				MAP
			</span>
		</h1>
	</div>
	<!-- end col -->

	<!-- right side of the page with the sparkline graphs -->
</div>
<!-- end row -->

<div class="row">
	<div class="col-sm-9 full-display">
		<div class="well padding-10">
<?php
if($_SESSION["group_id"] ==1 or $_SESSION["group_id"] ==2)
{
?>
<script src="assets/js/plugin/highcharts/highmaps.js"></script>
<script src="assets/js/plugin/highcharts/exporting.js"></script>
<script src="assets/js/plugin/highcharts/us-all.js"></script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
   
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" type="text/css" />-->
<style>
#usmap {
    height: 520px; 
    min-width: 310px; 
    max-width: 585px; 
    margin: 0 auto; 
}
.loading {
    margin-top: 10em;
    text-align: center;
    color: gray;
}
.model-footer{
	padding:5px !important;
}
#my-dialogs{
	height:auto !important;
}
</style>
<div id="usmap"></div>
<br />
<br />
<div id="my-dialogs" title="Map" style="display:none;">
    <textarea id="deditor" name="deditor" ></textarea>
	<input type="hidden" name="sname" id="sname" value="">
	<div class="modal-footer" style="padding:5px !important;">
		<button type="button" class="btn btn-primary" id="dialog-close">Close</button>
		<button type="button" class="btn btn-primary" id="dialog-save">Save changes</button>
	</div>
</div>
<?php } ?>
		</div>

	</div>

</div>
<div id="docs-show"></div>
<script src="assets/js/plugin/ckeditor/ckeditor.js"></script>
<script src="assets/js/jquery.shorten.js"></script>
<script src="assets/js/base64_decode.js"></script>
<script type="text/javascript">
	pageSetUp();

	var pagefunction = function() {
		$('#tabs').tabs();
		$('#subtabs-1').tabs();
		$('#subtabs-2').tabs();
		$('#subtabs-3').tabs();
		var $my_dialog = null;
		var $my_editor = null;
		var state_name="";
		function pointClick() {
			state_name=this.properties['hc-key'];
			$("#docs-show").load("assets/ajax/map-pedit2.php?country=us&state="+state_name);
			$('html, body').animate({
					scrollTop: $("#docs-show").offset().top
				}, 2000);
			/*$.ajax({
				type: "POST",
				url: "assets/includes/usmap.inc.php",
				data: {country:"us",state:state_name,load:"load"},
				success: function(statuss){
					if(statuss != false){					
						var results = JSON.parse(statuss);
						if(results.error=="")
						{alert(results.result);
							//$("#deditor").html(results.description);
							$("#tabs-1a").html(results.d1);
							$("#tabs-1b").html(results.d2);
							$("#tabs-1c").html(results.d3);
							$("#tabs-1d").html(results.d4);
							$("#tabs-1e").html(results.d5);
							$("#tabs-1f").html(results.d6);
							$("#tabs-1g").html(results.d7);
							$("#tabs-2a").html(results.d8);
							$("#tabs-2b").html(results.d9);
							$("#tabs-2c").html(results.d10);
							$("#tabs-2d").html(results.d11);
							$("#tabs-2e").html(results.d12);
							$("#tabs-2f").html(results.d13);
							$("#tabs-2g").html(results.d14);
							$("#tabs-3a").html(results.d15);
							$("#tabs-3b").html(results.d16);
							$("#tabs-3c").html(results.d17);
							$("#tabs-3d").html(results.d18);
							$("#tabs-3e").html(results.d19);
							$("#tabs-3f").html(results.d20);
							$("#tabs-3g").html(results.d21);
							$("#sname").val(state_name);
							if(CKEDITOR.instances['deditor']){
								$my_editor.destroy(true);					
							}
							//$my_editor = CKEDITOR.replace("deditor");
							//$("#my-dialogs").css("display","block");
							$('html, body').animate({
									scrollTop: $("#dialog-save").offset().top
								}, 2000);
							$("#deditor").focus();
							
						}else{
							alert(results.error);
						}
					}else{
						alert("Error Occured! Please try after sometime.");
					}
				},
				beforeSend:function()
				{

				}
			});	*/





			
			/*$my_dialog = $("#my-dialog").dialog({
				autoOpen: false,title: this.name, width: '80%', modal: true,
				create: function(){
					$.ajax({
						type: "POST",
						url: "assets/includes/usmap.inc.php",
						data: {country:"us",state:state_name,load:"load"},
						success: function(statuss){
							if(statuss != false){					
								var results = JSON.parse(statuss);
								if(results.error=="")
								{
									$("#deditor").html(results.description);
									$("#sname").val(state_name);
									$my_editor = CKEDITOR.replace("deditor");
									$my_dialog.dialog('open');
								}else{
									alert(results.error);
								}
							}else{
								alert("Error Occured! Please try after sometime.");
							}
						},
						beforeSend:function()
						{

						}
					});
				},
				close: function(){	
					if(CKEDITOR.instances['deditor']){
						//Reset the textarea element
						$my_editor.destroy(true);					
					}
					//Reset the dialog div element
					$my_dialog.dialog('destroy');
				}/*,
				_allowInteraction: function(event) {
					console.log(event.target);
					return !!$(event.target).find('[class*="cke"]').length || this._super(event);
				}*/
			//});*/
		}

		$("#dialog-close").click(function(){
			if(CKEDITOR.instances['deditor']){
				$my_editor.destroy(true);					
			}
			$("#my-dialogs").css("display","none");
			//$my_dialog.dialog('destroy');			
		});
		
		$("#dialog-save").click(function(){
			description=CKEDITOR.instances.deditor.getData();
			state_name=$("#sname").val();
			$my_editor.destroy(true);
			$("#my-dialogs").css("display","none");
			//$my_dialog.dialog('destroy');
			$.ajax({
				type: "POST",
				url: "assets/includes/usmap.inc.php",
				data: {country:"us",state:state_name,description:description},
				success: function(statuss){
					if(statuss != false){					
						var results = JSON.parse(statuss);
						if(results.error=="")
						{
							alert("Success");
						}else{
							alert(results.error);
						}
					}else{
						alert("Error Occured! Please try after sometime.");
					}
				},
				beforeSend:function()
				{

				}
			});	
		});
		
		// Prepare demo data
		var data = [
<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

	if ($stmt = $mysqli->prepare('SELECT id,country,state,document,document_value FROM document where country="us" order by id')) { 
        $stmt->execute();
        $stmt->store_result();
		$tmp_count=$stmt->num_rows;
        if ($tmp_count > 0) {
			$stmt->bind_result($id,$_country,$_state,$_document,$_document_value);
			$i=0;
			while($stmt->fetch()) {
				echo '{"hc-key":"'.$_state.'","value":'.$_document_value."}".($i!=$tmp_count?",":"");
				$i++;
			}
		}
	}else{
		header('Location: https://'.$_SERVER['HTTP_HOST'] .'/assets/includes/logout.php?error=System error');
		exit();		
	}
?>
		];

		// Initiate the chart
		$('#usmap').highcharts('Map', {

			title : {
				text : 'Regulatory Intelligence'
			},

			colorAxis: {
				dataClasses: [{
					from: 0,
					to: 0,
					color: '#001bc0',
					name: 'Regulated'
				}, {
					from: 1,
					to: 1,
					color: '#009ae2',
					name: 'Deregulated'
				}]
			},

			credits: {
				  enabled: false
			},

			series : [{
				data : data,
				mapData: Highcharts.maps['countries/us/us-all'],
				joinBy: 'hc-key',
				name: 'US STATE',
				states: {
					hover: {
						color: '#BADA55'
					}
				},
				dataLabels: {
					enabled: true,
					format: '{point.name}'
				},
				point: {
					events: {
						click: pointClick
					}
				},
			}, {
				name: 'Separators',
				type: 'mapline',
				data: Highcharts.geojson(Highcharts.maps['countries/us/us-all'], 'mapline'),
				color: 'silver',
				showInLegend: false,
				enableMouseTracking: false
			}]
		});
		//CKEDITOR.replace( 'ckeditor', { height: '380px', startupFocus : true} );
		
		$.widget('ui.dialog', $.ui.dialog, {
			_allowInteraction: function(event) {
				return !!$(event.target).closest('[class*="cke"]').length || this._super(event);
			}
		});
	};

	// end pagefunction

	// run pagefunction
	//pagefunction();
	loadScript("assets/js/plugin/ckeditor/ckeditor.js", pagefunction);
</script>