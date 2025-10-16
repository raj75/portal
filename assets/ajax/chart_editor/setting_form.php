<?php

// moved to charting page
/*
	$_SESSION['popup'] = [];
	function popup_chart_settings () {
		$_SESSION['popup']['ds1'] = "column";
		$_SESSION['popup']['ds2'] = "column";
		$_SESSION['popup']['ds3'] = "column";
		
		if ($_GET['gid']=='col' and $_GET['cid']==9) {
			$_SESSION['popup']['ds2'] = "line";
		} else if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
			//$chart_type = "column";
		}
		//$_SESSION['popup']['chart_type'] = 
		
	}
	
	// call settings
	popup_chart_settings();
*/
	function set_chart_type($ds) {
		$chart_arr = ["line"=>"Line", "column"=>"Column", "step"=>"Step", "smoothed"=>"Smoothed", "smoothedline"=>"Smoothed Line", "candlestick"=>"Candlestick", "ohlc"=>"OHLC"];
		
		$options = "";	
		$ind = "ds$ds";
		echo "<br>ind==".$ind;
		echo "<br>ds$ds==".$_SESSION["popup"][$ind];
		
		foreach($chart_arr as $key=>$val) {
			$selected = "";
			echo "popup$ds==$key==".$_SESSION['popup']['ds'.$ds];
			if ($_SESSION['popup']['ds'.$ds] == $key) {
				$selected = "selected";
			}
			/*
			if ($key == "column") {
				$selected = "selected";
			}
			*/
			$options .= "<option value='$key' $selected>$val</option>";
		}
		
		return $options;																   
	}
	//fillAlphas
	function set_dsf_transparency($ds) {
		
		if ($ds==2 and $_SESSION['popup']['ds2'] == "line") {
			return 0;
		} else if ($_GET['gid']=='line') {
			return 0;			
		} else if ($_GET['gid']=='area') {
			return .7;			
		}
		return 1;																   
	}
	
	function set_ds_lthickness($ds) {
		
		if ($ds==2 and $_SESSION['popup']['ds2'] == "line") {
			return 2;
		} else if ($_GET['gid']=='line') {
			return 2;			
		}
		return 1;																   
	}
	
	function set_dstype($ds) {
		$chart_arr = ["round"=>"Round", "square"=>"Square", "triangleUp"=>"Triangle Up", "triangleDown"=>"Triangle Down", "triangleLeft"=>"Triangle Left", "triangleRight"=>"Triangle Right", "bubble"=>"Bubble", "diamond"=>"Diamond"];
		
		$options = "";	
		$ind = "ds$ds";
		
		foreach($chart_arr as $key=>$val) {
			$selected = "";
			//echo "popup$ds==$key==".$_SESSION['popup']['ds'.$ds];
			if ($_SESSION['popup']['dstype'.$ds] == $key) {
				$selected = "selected";
			}
			/*
			if ($key == "column") {
				$selected = "selected";
			}
			*/
			$options .= "<option value='$key' $selected>$val</option>";
		}
		
		return $options;
	}
	
	
	//print_r($_SESSION['popup']);
	//unset($_SESSION['popup']);
?>
<!--
<form target="chart_iframe" id="settings_form" method="post" class="form-horizontal settings_form" name="settings_form" action="assets/ajax/chart_editor/am_chart.php?gid=<?php echo $chart_gid?>&cid=<?php echo $chart_cid?>">
-->
<div class="">
		<div class="vertical-tab" role="tabpanel">
			<!-- Nav tabs -->
			
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#axistab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Axis</span></a>
				</li>
				<li role="presentation"><a href="#chartareatab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Chart Area</span></a>
				</li>
				<li role="presentation"><a href="#charttitletab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Chart Title</span></a>
				</li>
				<li role="presentation"><a href="#dataseriestab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Data Series</span></a>
				</li>
				<li role="presentation"><a href="#datalabelstab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Data Labels</span></a>
				</li>
				<li role="presentation"><a href="#gridlinestab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Gridlines</span></a>
				</li>
				<li role="presentation"><a href="#legendtab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Legend</span></a>
				</li>
				<li role="presentation"><a href="#plotareatab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Format Plot Area</span></a>
				</li>
				<li role="presentation"><a id="resetchart" href="#resettab--" aria-controls="home--" role="tab--" data-toggle="tab--">
					<i class="am pull-left"></i> <span>Reset Chart</span></a>
				</li> 
				
				
				
				<!-- old settings-->
				<!--
				<li role="presentation"><a href="#appeartab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Appearance</span></a>
				</li>
				<li role="presentation"><a href="#backgroudtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Background and plot area</span></a>
				</li>
				<li role="presentation"><a href="#generaltab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>General Settings</span></a>
				</li>
				<li role="presentation"><a href="#titletab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Title</span></a>
				</li>
				<?php if (isset($_GET['gid']) and $_GET['gid']!='pie' and $_GET['gid']!='other') { ?>
				<li role="presentation" id="li_cat_axes"><a href="#cataxestab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Category Axes</span></a>
				</li>
				<li role="presentation" id="li_axestab1"><a href="#axestab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Value Axes</span></a>
				</li>
				<?php } ?>
				<li role="presentation" id="li_sample"><a href="#sampletab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Sample</span></a>
				</li>
				-->
				
			</ul>
			<!--
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#appeartab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Appearance</span></a>
				</li>
				<li role="presentation"><a href="#backgroudtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Background and plot area</span></a>
				</li>
				<li role="presentation"><a href="#generaltab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>General Settings</span></a>
				</li>
				<li role="presentation"><a href="#titletab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Title</span></a>
				</li>
				<?php if (isset($_GET['gid']) and $_GET['gid']!='pie' and $_GET['gid']!='other') { ?>
				<li role="presentation" id="li_cat_axes"><a href="#cataxestab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Category Axes</span></a>
				</li>
				<li role="presentation" id="li_axestab1"><a href="#axestab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Value Axes</span></a>
				</li>
				<?php } ?>
				<li role="presentation" id="li_sample"><a href="#sampletab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="am pull-left"></i> <span>Sample</span></a>
				</li>
				
			</ul>
			-->
			<!-- Tab panes -->
			<div class="tab-content tabs exceltabs">
			
				
				<div role="tabpanel" class="tab-pane fade in active" id="axistab">					
					  
					<ul id="myTab1" class="nav nav-tabs bordered margin-bottom-5">
							<li class="active">
								<a href="#s1" data-toggle="tab">Primary<br>Horizontal</a>
							</li>
							<li>
								<a href="#s2" data-toggle="tab">Primary<br>Vertical</a>
							</li>
							<li>
								<a href="#s3" data-toggle="tab">Secondary<br>Vertical</a>
							</li>
														
						</ul>
						
						
						<div id="myTabContent1" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1">
								
								<ul id="myTab1-1" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#ss1" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss2" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss3" data-toggle="tab" class="padding-5" title="Axis Options"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
									</li>
																
								</ul>
								
								<div id="myTabContent1-1" class="tab-content"> 
									<div class="tab-pane fade in active" id="ss1">
									<div class="panel-group smart-accordion-default" id="accordion-ss1">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss1" href="#collapseOne-ss1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Horizontal Axis </a></h4>
											</div>
											<div id="collapseOne-ss1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-5" for="font_family">Labels Enabled:</label>
															<div class="col-sm-7">
																<div class="checkbox">
																	<label><input type="checkbox" name="phh_label_enable" checked value=1></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
																<div id="cp_phha_color" class="input-group">
																	<input type="text" class="form-control" name="phha_color" id="phha_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Width:</label>
															<div class="col-sm-8">
															  <div id="phh_width_slider">
																  <div id="phh_width_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="phh_width" id="phh_width" value="0">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="phh_transparency_slider">
																  <div id="phh_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="phh_transparency" id="phh_transparency" value="1">
															</div>
														</div>
																
													</div>
												</div>
											</div>
										</div>	
								
									</div>
									</div>
								
								<div class="tab-pane fade in" id="ss2">
								<div class="panel-group smart-accordion-default" id="accordion-ss2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss2" href="#collapseOne-ss2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Horizontal Axis </a></h4>
										</div>
										<div id="collapseOne-ss2" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Title:</label>
														<div class="col-sm-8">
														  <input type="text" name="phh_title" class="form-control">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Size:</label>
														<div class="col-sm-8">
														  <div id="phh_size_slider">
															  <div id="phh_size_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_size" id="phh_size" value="15">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_phh_color" class="input-group">
																<input type="text" class="form-control" name="phh_color" id="phh_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Bold:</label>
														<div class="col-sm-8">
															<div class="checkbox">
																<label><input type="checkbox" name="phh_bold" value=1></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Rotation:</label>
														<div class="col-sm-8">
														  <div id="phh_rotation_slider">
															  <div id="phh_rotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_rotation" id="phh_rotation" value="0">
														</div>
													</div>
												
												</div>
											</div>
										</div>
									</div>									
									
								</div>
								</div>
								
								<div class="tab-pane fade in" id="ss3">
								<div class="panel-group smart-accordion-default" id="accordion-ss3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss3" href="#collapseOne-ss3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Horizontal Axis </a></h4>
										</div>
										<div id="collapseOne-ss3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick length:</label>
														<div class="col-sm-8">
														  <div id="phh_ticklength_slider">
															  <div id="phh_ticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_ticklength" id="phh_ticklength" value="12">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minor tick length:</label>
														<div class="col-sm-8">
														  <div id="phh_mticklength_slider">
															  <div id="phh_mticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_mticklength" id="phh_mticklength" value="7">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick position:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label class="control-label"><input type="radio" name="phh_tposition" value="middle" checked>Middle</label>&nbsp;&nbsp;
																<label  class="control-label"><input type="radio" name="phh_tposition" value="start">Start</label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Axis position:</label>
														<div class="col-sm-8">
															<select name="phh_aposition" class="form-control">
															    <option value="bottom">Bottom</option>
															    <option value="top">Top</option>															   
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Frequency:</label>
														<div class="col-sm-8">
														  <div id="phh_lfrequency_slider">
															  <div id="phh_lfrequency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_lfrequency" id="phh_lfrequency" value="0">
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Hide Lables</b></label> 														
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">First:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="phh_hidefirst" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Last:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="phh_hidelast" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis label location</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Outside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="phh_alLocation" value="outside" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Inside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="phh_alLocation" value="inside"></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Two Rows:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="phh_tworows" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Mark period change:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="phh_mpchange" value=1 checked></label>
														  </div>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Type</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Text/Numeric:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="phh_parsedate" value="numeric" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Date:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="phh_parsedate" value="date"></label>
															</div>
														</div>
													</div>
													
													<!--
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Equal spacing:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="phh_equal" value=1></label>
														  </div>
														</div>
													</div>
													-->
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Date format:</label>
														<div class="col-sm-8">
															<select name="phh_minPeriod" class="form-control">
															   <option value="DD">DD</option>
															   <option value="MM">MM</option>
															   <option value="YYYY" selected>YYYY</option>
															   <option value="hh">hh</option>
															   <option value="mm">mm</option>
															   <option value="ss">ss</option>
															   <option value="fff">fff</option>
															</select>
														</div>
													</div> 
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label unit gap:</label>
														<div class="col-sm-8">
														  <div id="phh_lugap_slider">
															  <div id="phh_lugap_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_lugap" id="phh_lugap" value="0"> 
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Rotation:</label>
														<div class="col-sm-8">
														  <div id="phh_lrotation_slider">
															  <div id="phh_lrotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="phh_lrotation" id="phh_lrotation" value="-30">
														</div>
													</div>
													<!--
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Limit</b></label>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minimum:</label>
														<div class="col-sm-8">
														  <input type="text" name="phh_min" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Maximum:</label>
														<div class="col-sm-8">
														  <input type="text" name="phh_max" class="form-control">
														</div> 
													</div>	
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minimum Date:</label>
														<div class="col-sm-8">
														  <input type="text" name="phh_min_date" class="form-control" placeholder="YYYY-MM-DD">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Maximum Date:</label>
														<div class="col-sm-8">
														  <input type="text" name="phh_max_date" class="form-control" placeholder="YYYY-MM-DD">
														</div> 
													</div>
													-->
												</div>
											</div>
										</div>
									</div>
							
								</div>
								</div>
								
								</div>
							</div>
							
							
														
							<div class="tab-pane fade" id="s2">
								<ul id="myTab1-2" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#ss1-2" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss2-2" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss3-2" data-toggle="tab" class="padding-5" title="Axis Options"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
									</li>
																
								</ul>
								
								<div id="myTabContent1-2" class="tab-content"> 
									<div class="tab-pane fade in active" id="ss1-2">
									<div class="panel-group smart-accordion-default" id="accordion-ss1-2">
																			
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss1-2" href="#collapseOne-ss1-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
											</div>
											<div id="collapseOne-ss1-2" class="panel-collapse collapse in">
												<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-5" for="font_family">Labels Enabled:</label>
															<div class="col-sm-7">
																<div class="checkbox">
																	<label><input type="checkbox" name="pvv_label_enable" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
																<div id="cp_pvva_color" class="input-group">
																	<input type="text" class="form-control" name="pvva_color" id="pvva_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Width:</label>
															<div class="col-sm-8">
															  <div id="pvv_width_slider">
																  <div id="pvv_width_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="pvv_width" id="pvv_width" value=".4">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="pvv_transparency_slider">
																  <div id="pvv_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="pvv_transparency" id="pvv_transparency" value=".25">
															</div>
														</div>
												</div>
											</div>
										</div>
								
									</div>
									</div>
								
								<div class="tab-pane fade in" id="ss2-2">
								<div class="panel-group smart-accordion-default" id="accordion-ss2-2">
																	
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss2-2" href="#collapseOne-ss2-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
										</div>
										<div id="collapseOne-ss2-2" class="panel-collapse collapse in">
											<div class="panel-body">
												    <div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Title:</label>
														<div class="col-sm-8">
														  <input type="text" name="pvv_title" class="form-control">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Size:</label>
														<div class="col-sm-8">
														  <div id="pvv_size_slider">
															  <div id="pvv_size_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_size" id="pvv_size" value="15">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_pvv_color" class="input-group">
																<input type="text" class="form-control" name="pvv_color" id="pvv_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Bold:</label>
														<div class="col-sm-8">
															<div class="checkbox">
																<label><input type="checkbox" name="pvv_bold" value=1></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Rotation:</label>
														<div class="col-sm-8">
														  <div id="pvv_rotation_slider">
															  <div id="pvv_rotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_rotation" id="pvv_rotation" value="0">
														</div>
													</div>
											</div>
										</div> 
									</div>
								</div>
								</div>
								
								<div class="tab-pane fade in" id="ss3-2">
								<div class="panel-group smart-accordion-default" id="accordion-ss3-2">
									
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss3-2" href="#collapseOne-ss3-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
										</div>
										<div id="collapseOne-ss3-2" class="panel-collapse collapse in">
											<div class="panel-body">
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick length:</label>
														<div class="col-sm-8">
														  <div id="pvv_ticklength_slider">
															  <div id="pvv_ticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_ticklength" id="pvv_ticklength" value="7">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minor tick length:</label>
														<div class="col-sm-8">
														  <div id="pvv_mticklength_slider">
															  <div id="pvv_mticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_mticklength" id="pvv_mticklength" value="7">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick position:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label class="control-label"><input type="radio" name="pvv_tposition" checked value="middle">Middle</label>&nbsp;&nbsp;
																<label  class="control-label"><input type="radio" name="pvv_tposition" value="start">Start</label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Axis position:</label>
														<div class="col-sm-8">
															<select name="pvv_aposition" class="form-control">
															   <option value="left">Left</option>
															   <option value="right">Right</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Frequency:</label>
														<div class="col-sm-8">
														  <div id="pvv_lfrequency_slider">
															  <div id="pvv_lfrequency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_lfrequency" id="pvv_lfrequency" value="0">
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Hide Lables</b></label> 														
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">First:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="pvv_hidefirst" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Last:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="pvv_hidelast" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis label location</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Outside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="pvv_alLocation" value="outside" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Inside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="pvv_alLocation" value="inside"></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Two Rows:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="pvv_tworows" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Mark period change:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="pvv_mpchange" value=1 checked></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Type</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Text/Numeric:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="pvv_parsedate" value="numeric" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Date:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="pvv_parsedate" value="date"></label>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Date format:</label>
														<div class="col-sm-8">
															<select name="pvv_minPeriod" class="form-control">
															   <option value="DD">DD</option>
															   <option value="MM">MM</option>
															   <option value="YYYY">YYYY</option>
															   <option value="hh">hh</option>
															   <option value="mm">mm</option>
															   <option value="ss">ss</option>
															   <option value="fff">fff</option>
															</select>
														</div>
													</div>
													
													<!--
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Equal spacing:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="pvv_equal" value=1></label>
														  </div>
														</div>
													</div>
													-->
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label unit gap:</label>
														<div class="col-sm-8">
														  <div id="pvv_lugap_slider">
															  <div id="pvv_lugap_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_lugap" id="pvv_lugap" value="0">
														</div>
													</div>

													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Rotation:</label>
														<div class="col-sm-8">
														  <div id="pvv_lrotation_slider">
															  <div id="pvv_lrotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pvv_lrotation" id="pvv_lrotation" value="0">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Limit</b></label>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minimum:</label>
														<div class="col-sm-8">
														  <input type="text" name="pvv_min" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Maximum:</label>
														<div class="col-sm-8">
														  <input type="text" name="pvv_max" class="form-control">
														</div> 
													</div> 
											</div>
										</div>
									</div>
							
								</div>
								</div>
							</div>
							
						</div>
						
							
							<div class="tab-pane fade" id="s3">
								<ul id="myTab1-3" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#ss1-3" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss2-3" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
									</li>
									<li>
										<a href="#ss3-3" data-toggle="tab" class="padding-5" title="Fill & Border"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
									</li>
																
								</ul>
								
								<div id="myTabContent1-3" class="tab-content"> 
									<div class="tab-pane fade in active" id="ss1-3">
									<div class="panel-group smart-accordion-default" id="accordion-ss1-3">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss1-3" href="#collapseOne-ss1-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
											</div>
											<div id="collapseOne-ss1-3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-5" for="font_family">Labels Enabled:</label>
															<div class="col-sm-7">
																<div class="checkbox">
																	<label><input type="checkbox" name="svv_label_enable" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
																<div id="cp_svva_color" class="input-group">
																	<input type="text" class="form-control" name="svva_color" id="svva_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Width:</label>
															<div class="col-sm-8">
															  <div id="svv_width_slider">
																  <div id="svv_width_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="svv_width" id="svv_width" value="0">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="svv_transparency_slider">
																  <div id="svv_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="svv_transparency" id="svv_transparency" value="1">
															</div>
														</div>
																
													</div>
												</div>
											</div>
										</div>	
																		
									</div>
									</div>
								
								<div class="tab-pane fade in" id="ss2-3">
								<div class="panel-group smart-accordion-default" id="accordion-ss2-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss2-3" href="#collapseOne-ss2-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
										</div>
										<div id="collapseOne-ss2-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Title:</label>
														<div class="col-sm-8">
														  <input type="text" name="svv_title" class="form-control">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Size:</label>
														<div class="col-sm-8">
														  <div id="svv_size_slider">
															  <div id="svv_size_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_size" id="svv_size" value="15">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_svv_color" class="input-group">
																<input type="text" class="form-control" name="svv_color" id="svv_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Bold:</label>
														<div class="col-sm-8">
															<div class="checkbox">
																<label><input type="checkbox" name="svv_bold" value=1></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Rotation:</label>
														<div class="col-sm-8">
														  <div id="svv_rotation_slider">
															  <div id="svv_rotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_rotation" id="svv_rotation" value="0">
														</div>
													</div>
												
												</div>
											</div>
										</div>
									</div>
									
								</div>
								</div>
								
								<div class="tab-pane fade in" id="ss3-3">
								<div class="panel-group smart-accordion-default" id="accordion-ss3-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-ss3-3" href="#collapseOne-ss3-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical Axis </a></h4>
										</div>
										<div id="collapseOne-ss3-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick length:</label>
														<div class="col-sm-8">
														  <div id="svv_ticklength_slider">
															  <div id="svv_ticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_ticklength" id="svv_ticklength" value="12">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minor tick length:</label>
														<div class="col-sm-8">
														  <div id="svv_mticklength_slider">
															  <div id="svv_mticklength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_mticklength" id="svv_mticklength" value="7">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Tick position:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label class="control-label"><input type="radio" name="svv_tposition" value="middle" checked>Middle</label>&nbsp;&nbsp;
																<label  class="control-label"><input type="radio" name="svv_tposition" value="start">Start</label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Axis position:</label>
														<div class="col-sm-8">
															<select name="svv_aposition" class="form-control">
															   <option value="right">Right</option>
															   <option value="left">Left</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Frequency:</label>
														<div class="col-sm-8">
														  <div id="svv_lfrequency_slider">
															  <div id="svv_lfrequency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_lfrequency" id="svv_lfrequency" value="0">
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Hide Lables</b></label> 														
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">First:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="svv_hidefirst" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Last:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="svv_hidelast" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis label location</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Outside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="svv_alLocation" value="outside" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Inside:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="svv_alLocation" value="inside"></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Two Rows:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="svv_tworows" value=1></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Mark period change:</label>
														<div class="col-sm-8">
														  <div class="checkbox">
															<label><input type="checkbox" name="svv_mpchange" value=1 checked></label>
														  </div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Type</b></label>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Text/Numeric:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="svv_parsedate" value="numeric" checked></label>
															</div>
														</div>
													</div>
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Date:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="svv_parsedate" value="date"></label>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Date format:</label>
														<div class="col-sm-8">
															<select name="svv_minPeriod" class="form-control">
															   <option value="DD">DD</option>
															   <option value="MM">MM</option>
															   <option value="YYYY">YYYY</option>
															   <option value="hh">hh</option>
															   <option value="mm">mm</option>
															   <option value="ss">ss</option>
															   <option value="fff">fff</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label unit gap:</label>
														<div class="col-sm-8">
														  <div id="svv_lugap_slider">
															  <div id="svv_lugap_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_lugap" id="svv_lugap" value="25">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Label Rotation:</label>
														<div class="col-sm-8">
														  <div id="svv_lrotation_slider">
															  <div id="svv_lrotation_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="svv_lrotation" id="svv_lrotation" value="0">
														</div>
													</div>	

													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Axis Limit</b></label>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Minimum:</label>
														<div class="col-sm-8">
														  <input type="text" name="svv_min" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Maximum:</label>
														<div class="col-sm-8">
														  <input type="text" name="svv_max" class="form-control">
														</div> 
													</div>													
													
												</div>
											</div>
										</div>
									</div>
																
								</div>
								</div>
							</div>
							
						</div>
				
				</div>
				</div>
				
				
				<div role="tabpanel" class="tab-pane fade" id="chartareatab">					
					 <ul id="myTab-2" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1-2" data-toggle="tab" class="padding-5" title="Fill & Border" ><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
							</li>
							<li>
								<a href="#s2-2" data-toggle="tab" class="padding-5" title="Font" ><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
							</li>
					 </ul>
					 
					 <div id="myTabContent1-2" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1-2">
								<div class="panel-group smart-accordion-default" id="accordion-2-1">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2-1" href="#collapseOne-2-1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill & Border </a></h4>
										</div>
										<div id="collapseOne-2-1" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
												
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_caf_color" class="input-group">
																<input type="text" class="form-control" name="caf_color" id="caf_color" placeholder="Text color" value="#ffffff">
																<span class="input-group-addon"><i></i></span>
														    </div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Transparency:</label>
														<div class="col-sm-8">
														  <div id="caf_transparency_slider">
															  <div id="caf_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="caf_transparency" id="caf_transparency" value=".5">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Border</b></label> 														
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Enabled:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="plotAreaBorderColor" value="1" checked></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Disabled:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="plotAreaBorderColor" value="0"></label>
															</div>
														</div>
													</div>
												
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="cab_transparency_slider">
															  <div id="cab_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="cab_transparency" id="cab_transparency" value=".5">
														</div>
													</div>	
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
							
							<div class="tab-pane fade in" id="s2-2">
								<div class="panel-group smart-accordion-default" id="accordion-2-2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2-2" href="#collapseOne-2-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Font </a></h4>
										</div>
										<div id="collapseOne-2-2" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Font:</label>
														<div class="col-sm-8">
															<select name="fontFamily" class="form-control">
															   <option value="Verdana">Verdana</option>
															   <option value="Calibri">Calibri</option>
															   <option value="Arial">Arial</option>
															   <option value="Times New Roman">Times New Roman</option>
															   <option value="Comic Sans MS">Comic Sans MS</option>
															   <option value="Helvetica" selected>Helvetica</option>
															</select>
														</div>
													</div>
																		  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
						</div>
						
						
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="charttitletab">					
					  
					<ul id="myTab-3" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1-3" data-toggle="tab" class="padding-5" title="Text Options" ><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
							</li>
							
														
						</ul>
						
						
						<div id="myTabContent1-3" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1-3">
								<div class="panel-group smart-accordion-default" id="accordion-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-3" href="#collapseOne-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Title </a></h4>
										</div>
										<div id="collapseOne-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0"> 
														<label class="control-label col-sm-4" for="font_family">Enabled:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="set_title" value=1 ></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Disabled:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="set_title" value=0 checked></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Text:</label>
														<div class="col-sm-8">
														  <input type="text" name="title_text" class="form-control" value="Chart Title">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Font Size:</label>
														<div class="col-sm-8">
															<div id="chart_font_size_slider">
															  <div id="chart_font_size_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="chart_font_size" id="chart_font_size" placeholder="Font size" value="15">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color Picker:</label>
														<div class="col-sm-8">
														  <div id="cp_text_color" class="input-group">
																<input type="text" class="form-control" name="title_color" id="title_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
												
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="transparency_slider">
															  <div id="transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="transparency" id="transparency" value="0.4">
														</div>
													</div>	
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
							
						</div>
						
						
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="dataseriestab">					
					
					<ul id="myTab4" class="nav nav-tabs bordered margin-bottom-5">
							<li class="active">
								<a href="#ds1" data-toggle="tab">Data<br>Series1</a>
							</li>
							<li>
								<a href="#ds2" data-toggle="tab">Data<br>Series2</a>
							</li>
							<li>
								<a href="#ds3" data-toggle="tab">Data<br>Series3</a>
							</li>
														
					</ul>
					
					<div id="myTabContent4" class="tab-content"> 
						<div class="tab-pane fade in active" id="ds1">	
						
							<ul id="myTab4-1" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#ds1_1" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
								</li>
								<li>
									<a href="#ds1_2" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/2.png" width="25" /></a>
								</li>
								<li>
									<a href="#ds1_3" data-toggle="tab" class="padding-5" title="Axis Options"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
								</li>
															
							</ul>
							
							<div id="myTabContent4-1" class="tab-content">
								<div class="tab-pane fade in active" id="ds1_1">
								<div class="panel-group smart-accordion-default" id="accordion_ds1_1">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds1_1" href="#collapseOne_ds1_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill and Line </a></h4>
											</div>
											<div id="collapseOne_ds1_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Title:</label>
															<div class="col-sm-8">
																<input type="text" name="titleds1" class="form-control">
															</div>
														</div>
																												
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="typeds1" class="form-control">
																   <?php //echo set_chart_type(1); ?>
																   <option value="line">Line</option>
																   <option value="column">Column</option>
																   <option value="step">Step</option>
																   <option value="smoothed">Smoothed</option>
																   <option value="smoothedLine">Smoothed Line</option>
																   <option value="candlestick">Candlestick</option>
																   <option value="ohlc">OHLC</option>
																</select>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsf1_color" class="input-group">
																	<input type="text" class="form-control" name="dsf1_color" id="dsf1_color" placeholder="Text color" value="#FF6600">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsf1_transparency_slider">
																  <div id="dsf1_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsf1_transparency" id="dsf1_transparency" value="1<?php //echo set_dsf_transparency(1);?>">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Line</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsl1_color" class="input-group">
																	<input type="text" class="form-control" name="dsl1_color" id="dsl1_color" placeholder="Text color" value="#ff6600">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds1_lthickness_slider">
																  <div id="ds1_lthickness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_lthickness" id="ds1_lthickness" value="1<?php //echo set_ds_lthickness(1);?>">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Dash length:</label>
															<div class="col-sm-8">
															  <div id="ds1_dlength_slider">
																  <div id="ds1_dlength_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_dlength" id="ds1_dlength" value="0">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsl1_transparency_slider">
																  <div id="dsl1_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsl1_transparency" id="dsl1_transparency" value=".4">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Plot Axis</b></label> 														
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Primary:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="paptitle" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Secondary:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="pastitle"></label> 
																</div>
															</div>
														</div>
														
														
																
													</div>
												</div>
											</div>
									</div>
								</div>
								</div>
								<div class="tab-pane fade" id="ds1_2">
									<div class="panel-group smart-accordion-default" id="accordion_ds1_2">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds1_2" href="#collapseOne_ds1_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Shape </a></h4>
											</div>
											<div id="collapseOne_ds1_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Rounded Corners:</label>
															<div class="col-sm-8">
															  <div id="ds1_rcorner_slider">
																  <div id="ds1_rcorner_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_rcorner" id="ds1_rcorner" value="0">
															</div>
														</div>	

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Column Width:</label>
															<div class="col-sm-8">
															  <div id="ds1_cwidth_slider">
																  <div id="ds1_cwidth_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_cwidth" id="ds1_cwidth" value=".8">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								</div>
								<div class="tab-pane fade" id="ds1_3">
									<div class="panel-group smart-accordion-default" id="accordion_ds1_3">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds1_3" href="#collapseOne_ds1_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Chart Options </a></h4>
											</div>
											<div id="collapseOne_ds1_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="ds1type" class="form-control">
																   <option value="">Not set</option>
																   <?php echo set_dstype(1);?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds1co_color" class="input-group">
																	<input type="text" class="form-control" name="ds1co_color" id="ds1co_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Size:</label>
															<div class="col-sm-8">
															  <div id="ds1co_size_slider">
																  <div id="ds1co_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1co_size" id="ds1co_size" value="8">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds1co_transparency_slider">
																  <div id="ds1co_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1co_transparency" id="ds1co_transparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								<div class="panel-group smart-accordion-default" id="accordion_ds1_33">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds1_33" href="#collapseOne_ds1_33"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Border </a></h4>
											</div>
											<div id="collapseOne_ds1_33" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds1_bcolor" class="input-group">
																	<input type="text" class="form-control" name="ds1_bcolor" id="ds1_bcolor" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds1_bthikness_slider">
																  <div id="ds1_bthikness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_bthikness" id="ds1_bthikness" value="1">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds1_btransparency_slider">
																  <div id="ds1_btransparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds1_btransparency" id="ds1_btransparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								
								</div>
							</div>
													
						</div>
						<div class="tab-pane fade" id="ds2">
							
								<ul id="myTab4-2" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#ds2_1" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
									</li>
									<li>
										<a href="#ds2_2" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/2.png" width="25" /></a>
									</li>
									<li>
										<a href="#ds2_3" data-toggle="tab" class="padding-5" title="Axis Options"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
									</li>
																
								</ul>
							<div id="myTabContent4-2" class="tab-content">
								<div class="tab-pane fade in active" id="ds2_1">
								<div class="panel-group smart-accordion-default" id="accordion_ds2_1">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds2_1" href="#collapseOne_ds2_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill and Line </a></h4>
											</div>
											<div id="collapseOne_ds2_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Title:</label>
															<div class="col-sm-8">
																<input type="text" name="titleds2" class="form-control">
															</div>
														</div>
																												
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="typeds2" class="form-control">
																   <?php //echo set_chart_type(2); ?>
																   <option value="line">Line</option>
																   <option value="column">Column</option>
																   <option value="step">Step</option>
																   <option value="smoothed">Smoothed</option>
																   <option value="smoothedLine">Smoothed Line</option>
																   <option value="candlestick">Candlestick</option>
																   <option value="ohlc">OHLC</option>
																</select>		
																<!--
																<select name="ds2type" class="form-control">
																   <option value="">Not set</option>
																   <option value="round">Round</option>
																   <option value="square">Square</option>
																   <option value="triangleUp">Triangle Up</option>
																   <option value="triangleDown">Triangle Down</option>
																   <option value="triangleLeft">Triangle Left</option>
																   <option value="triangleRight">Triangle Right</option>
																   <option value="bubble">Bubble</option>
																   <option value="diamond">Diamond</option>
																</select>
																-->
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsf2_color" class="input-group">
																	<input type="text" class="form-control" name="dsf2_color" id="dsf2_color" placeholder="Text color" value="#FCD202">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsf2_transparency_slider">
																  <div id="dsf2_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsf2_transparency" id="dsf2_transparency" value="1<?php //echo set_dsf_transparency(2);?>">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Line</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsl2_color" class="input-group">
																	<input type="text" class="form-control" name="dsl2_color" id="dsl2_color" placeholder="Text color" value="#fcd202">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds2_lthickness_slider">
																  <div id="ds2_lthickness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_lthickness" id="ds2_lthickness" value="1<?php //echo set_ds_lthickness(2)?>">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Dash length:</label>
															<div class="col-sm-8">
															  <div id="ds2_dlength_slider">
																  <div id="ds2_dlength_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_dlength" id="ds2_dlength" value="0">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsl2_transparency_slider">
																  <div id="dsl2_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsl2_transparency" id="dsl2_transparency" value=".4">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Plot Axis</b></label> 														
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Primary:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="pap2title" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Secondary:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="pas2title"></label> 
																</div>
															</div>
														</div>
														
														
																
													</div>
												</div>
											</div>
									</div>
								</div>
								</div>
								<div class="tab-pane fade" id="ds2_2">
									<div class="panel-group smart-accordion-default" id="accordion_ds2_2">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds2_2" href="#collapseOne_ds2_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Shape </a></h4>
											</div>
											<div id="collapseOne_ds2_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Rounded Corners:</label>
															<div class="col-sm-8">
															  <div id="ds2_rcorner_slider">
																  <div id="ds2_rcorner_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_rcorner" id="ds2_rcorner" value="0">
															</div>
														</div>	

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Column Width:</label>
															<div class="col-sm-8">
															  <div id="ds2_cwidth_slider">
																  <div id="ds2_cwidth_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_cwidth" id="ds2_cwidth" value=".8">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								</div>
								<div class="tab-pane fade" id="ds2_3">
									<div class="panel-group smart-accordion-default" id="accordion_ds2_3">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds2_3" href="#collapseOne_ds2_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Chart Options </a></h4>
											</div>
											<div id="collapseOne_ds2_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="ds2type" class="form-control">
																   <option value="">Not set</option>
																   <?php echo set_dstype(2);?>
																   <!--
																   <option value="round">Round</option>
																   <option value="square">Square</option>
																   <option value="triangleUp">Triangle Up</option>
																   <option value="triangleDown">Triangle Down</option>
																   <option value="triangleLeft">Triangle Left</option>
																   <option value="triangleRight">Triangle Right</option>
																   <option value="bubble">Bubble</option>
																   <option value="diamond">Diamond</option>
																   -->
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds2co_color" class="input-group">
																	<input type="text" class="form-control" name="ds2co_color" id="ds2co_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Size:</label>
															<div class="col-sm-8">
															  <div id="ds2co_size_slider">
																  <div id="ds2co_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2co_size" id="ds2co_size" value="8">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds2co_transparency_slider">
																  <div id="ds2co_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2co_transparency" id="ds2co_transparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								<div class="panel-group smart-accordion-default" id="accordion_ds2_33">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds2_33" href="#collapseOne_ds2_33"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Border </a></h4>
											</div>
											<div id="collapseOne_ds2_33" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds2_bcolor" class="input-group">
																	<input type="text" class="form-control" name="ds2_bcolor" id="ds2_bcolor" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds2_bthikness_slider">
																  <div id="ds2_bthikness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_bthikness" id="ds2_bthikness" value="1">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds2_btransparency_slider">
																  <div id="ds2_btransparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds2_btransparency" id="ds2_btransparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="ds3">							
							
								<ul id="myTab4-3" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#ds3_1" data-toggle="tab" class="padding-5" title="Fill & Line"><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
									</li>
									<li>
										<a href="#ds3_2" data-toggle="tab" class="padding-5" title="Axis Title"><img src="<?php echo APP_URL; ?>/assets/img/charts/2.png" width="25" /></a>
									</li>
									<li>
										<a href="#ds3_3" data-toggle="tab" class="padding-5" title="Axis Options"><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
									</li>
																
								</ul>
								
							<div id="myTabContent4-3" class="tab-content">
								<div class="tab-pane fade in active" id="ds3_1">
								<div class="panel-group smart-accordion-default" id="accordion_ds3_1">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds3_1" href="#collapseOne_ds3_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill and Line </a></h4>
											</div>
											<div id="collapseOne_ds3_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Title:</label>
															<div class="col-sm-8">
																<input type="text" name="titleds3" class="form-control">
															</div>
														</div>
																												
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="typeds3" class="form-control">
																   <?php //echo set_chart_type(3); ?>
																   <option value="line">Line</option>
																   <option value="column">Column</option>
																   <option value="step">Step</option>
																   <option value="smoothed">Smoothed</option>
																   <option value="smoothedLine">Smoothed Line</option>
																   <option value="candlestick">Candlestick</option>
																   <option value="ohlc">OHLC</option>
																</select>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsf3_color" class="input-group">
																	<input type="text" class="form-control" name="dsf3_color" id="dsf3_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsf3_transparency_slider">
																  <div id="dsf3_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsf3_transparency" id="dsf3_transparency" value="1<?php //echo set_dsf_transparency(3);?>">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Line</b></label> 														
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_dsl3_color" class="input-group">
																	<input type="text" class="form-control" name="dsl3_color" id="dsl3_color" placeholder="Text color" value="#008000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds3_lthickness_slider">
																  <div id="ds3_lthickness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_lthickness" id="ds3_lthickness" value=".4">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Dash length:</label>
															<div class="col-sm-8">
															  <div id="ds3_dlength_slider">
																  <div id="ds3_dlength_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_dlength" id="ds3_dlength" value="0">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="dsl3_transparency_slider">
																  <div id="dsl3_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dsl3_transparency" id="dsl3_transparency" value=".4">
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Plot Axis</b></label> 														
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Primary:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="pap3title" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Secondary:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="pas3title"></label> 
																</div>
															</div>
														</div>
														
														
																
													</div>
												</div>
											</div>
									</div>
								</div>
								</div>
								<div class="tab-pane fade" id="ds3_2">
									<div class="panel-group smart-accordion-default" id="accordion_ds3_2">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds3_2" href="#collapseOne_ds3_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Shape </a></h4>
											</div>
											<div id="collapseOne_ds3_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Rounded Corners:</label>
															<div class="col-sm-8">
															  <div id="ds3_rcorner_slider">
																  <div id="ds3_rcorner_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_rcorner" id="ds3_rcorner" value="0">
															</div>
														</div>	

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Column Width:</label>
															<div class="col-sm-8">
															  <div id="ds3_cwidth_slider">
																  <div id="ds3_cwidth_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_cwidth" id="ds3_cwidth" value=".8">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								</div>
								<div class="tab-pane fade" id="ds3_3">
									<div class="panel-group smart-accordion-default" id="accordion_ds3_3">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds3_3" href="#collapseOne_ds3_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Chart Options </a></h4>
											</div>
											<div id="collapseOne_ds3_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Type:</label>
															<div class="col-sm-8">
																<select name="ds3type" class="form-control">
																   <option value="line">Not set</option>
																   <option value="column">Round</option>
																   <option value="step">Square</option>
																   <option value="smoothed">Triangle Up</option>
																   <option value="smoothedline">Triangle Down</option>
																   <option value="candlestick">Triangle Left</option>
																   <option value="ohlc">Triangle Right</option>
																   <option value="ohlc">Bubble</option>
																   <option value="ohlc">Diamond</option>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds3co_color" class="input-group">
																	<input type="text" class="form-control" name="ds3co_color" id="ds3co_color" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Size:</label>
															<div class="col-sm-8">
															  <div id="ds3co_size_slider">
																  <div id="ds3co_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3co_size" id="ds3co_size" value="8">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds3co_transparency_slider">
																  <div id="ds3co_transparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3co_transparency" id="ds3co_transparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								<div class="panel-group smart-accordion-default" id="accordion_ds3_33">
									<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_ds3_33" href="#collapseOne_ds3_33"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Border </a></h4>
											</div>
											<div id="collapseOne_ds3_33" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
													
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_ds3_bcolor" class="input-group">
																	<input type="text" class="form-control" name="ds3_bcolor" id="ds3_bcolor" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Thickness:</label>
															<div class="col-sm-8">
															  <div id="ds3_bthikness_slider">
																  <div id="ds3_bthikness_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_bthikness" id="ds3_bthikness" value="1">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Transparency:</label>
															<div class="col-sm-8">
															  <div id="ds3_btransparency_slider">
																  <div id="ds3_btransparency_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="ds3_btransparency" id="ds3_btransparency" value=".4">
															</div>
														</div>
																
													</div>
												</div>
											</div>
									</div>
								</div>
								
								
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="datalabelstab">	

					<ul id="myTab5" class="nav nav-tabs bordered margin-bottom-5">
							<li class="active">
								<a href="#dL1" data-toggle="tab">Data<br>Label-1</a>
							</li>
							<li>
								<a href="#dL2" data-toggle="tab">Data<br>Label-2</a>
							</li>
							<li>
								<a href="#dL3" data-toggle="tab">Data<br>Label-3</a>
							</li>
														
					</ul>
					
					<div id="myTabContent5" class="tab-content">
					
						<div class="tab-pane fade in active" id="dL1">
						
							<ul id="myTab5-1" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#dL1_1" data-toggle="tab" class="padding-5" title="" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL1_2" data-toggle="tab" class="padding-5" title="Font" ><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL1_3" data-toggle="tab" class="padding-5" title="Properties" ><img src="<?php echo APP_URL; ?>/assets/img/charts/3.png" width="25" /></a>
								</li>
							</ul>
							
							<div id="myTabContent5-1" class="tab-content">
								<div class="tab-pane fade in active" id="dL1_1">
									<div class="panel-group smart-accordion-default" id="accordion_dL1_1">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL1_1" href="#collapseOne_dL1_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Position </a></h4>
											</div>
											<div id="collapseOne_dL1_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">None:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="none" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Top:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="top"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Bottom:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="bottom"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Center:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="middle"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Inside:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="inside"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Left:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="left"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Right:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL1" value="right"></label>
																</div>
															</div>
														</div>
						  
													</div>
												</div>
											</div>
											
										</div>								
									</div>
									
									<div class="panel-group smart-accordion-default" id="accordion_dL1_11">
										<div class="panel panel-default">
									<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL1_11" href="#collapseOne_dL1_11"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Container </a></h4>
											</div>
											<div id="collapseOne_dL1_11" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Text:</label>
															<div class="col-sm-8">
															  <input type="text" class="form-control" name="textdL1" id="textdL1" placeholder="" value="[[value]]">
															</div>
														</div>
						  
													</div>
												</div>
											</div>
									</div>
									</div>
																
									
								</div>
								
								<div class="tab-pane fade in" id="dL1_2">
								
									<div class="panel-group smart-accordion-default" id="accordion_dL1_2">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL1_2" href="#collapseOne_dL1_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Font </a></h4>
											</div>
											<div id="collapseOne_dL1_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_color_dL1" class="input-group">
																	<input type="text" class="form-control" name="color_dL1" id="color_dL1" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Size:</label>
															<div class="col-sm-8">
															  <div id="dL1f_size_slider">
																  <div id="dL1f_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL1f_size" id="dL1f_size" value="12">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								<div class="tab-pane fade in" id="dL1_3">
									<div class="panel-group smart-accordion-default" id="accordion_dL1_3">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL1_3" href="#collapseOne_dL1_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Properties </a></h4>
											</div>
											<div id="collapseOne_dL1_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Offset:</label>
															<div class="col-sm-8">
															  <div id="dL1p_offset_slider">
																  <div id="dL1p_offset_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL1p_offset" id="dL1p_offset" value="5">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Rotation:</label>
															<div class="col-sm-8">
															  <div id="dL1p_rotation_slider">
																  <div id="dL1p_rotation_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL1p_rotation" id="dL1p_rotation" value="0">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
						
						</div>
						
						<div class="tab-pane fade in" id="dL2">
						
							<ul id="myTab5-2" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#dL2_1" data-toggle="tab" class="padding-5" title="" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL2_2" data-toggle="tab" class="padding-5" title="Font" ><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL2_3" data-toggle="tab" class="padding-5" title="Properties" ><img src="<?php echo APP_URL; ?>/assets/img/charts/3.png" width="25" /></a>
								</li>
							</ul>
							
							<div id="myTabContent5-2" class="tab-content">
								<div class="tab-pane fade in active" id="dL2_1">
									<div class="panel-group smart-accordion-default" id="accordion_dL2_1">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL2_1" href="#collapseOne_dL2_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Position </a></h4>
											</div>
											<div id="collapseOne_dL2_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">None:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="none" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Top:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="top"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Bottom:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="bottom"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Center:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="middle"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Inside:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="inside"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Left:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="left"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Right:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL2" value="right"></label>
																</div>
															</div>
														</div>
						  
													</div>
												</div>
											</div>
											
										</div>								
									</div>
									
									<div class="panel-group smart-accordion-default" id="accordion_dL2_11">
										<div class="panel panel-default">
									<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL2_11" href="#collapseOne_dL2_11"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Container </a></h4>
											</div>
											<div id="collapseOne_dL2_11" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Text:</label>
															<div class="col-sm-8">
															  <input type="text" class="form-control" name="textdL2" id="textdL2" placeholder="" value="[[value]]">
															</div>
														</div>
						  
													</div>
												</div>
											</div>
									</div>
									</div>
																
									
								</div>
								
								<div class="tab-pane fade in" id="dL2_2">
								
									<div class="panel-group smart-accordion-default" id="accordion_dL2_2">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL2_2" href="#collapseOne_dL2_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Font </a></h4>
											</div>
											<div id="collapseOne_dL2_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_color_dL2" class="input-group">
																	<input type="text" class="form-control" name="color_dL2" id="color_dL2" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Size:</label>
															<div class="col-sm-8">
															  <div id="dL2f_size_slider">
																  <div id="dL2f_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL2f_size" id="dL2f_size" value="12">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								<div class="tab-pane fade in" id="dL2_3">
									<div class="panel-group smart-accordion-default" id="accordion_dL2_3">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL2_3" href="#collapseOne_dL2_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Properties </a></h4>
											</div>
											<div id="collapseOne_dL2_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Offset:</label>
															<div class="col-sm-8">
															  <div id="dL2p_offset_slider">
																  <div id="dL2p_offset_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL2p_offset" id="dL2p_offset" value="5">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Rotation:</label>
															<div class="col-sm-8">
															  <div id="dL2p_rotation_slider">
																  <div id="dL2p_rotation_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL2p_rotation" id="dL2p_rotation" value="0">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
						
						</div>
						
						<div class="tab-pane fade in" id="dL3">
						
							<ul id="myTab5-3" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#dL3_1" data-toggle="tab" class="padding-5" title="" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL3_2" data-toggle="tab" class="padding-5" title="Font" ><img src="<?php echo APP_URL; ?>/assets/img/charts/5.png" width="25" /></a>
								</li>
								<li>
									<a href="#dL3_3" data-toggle="tab" class="padding-5" title="Properties" ><img src="<?php echo APP_URL; ?>/assets/img/charts/3.png" width="25" /></a>
								</li>
							</ul>
							
							<div id="myTabContent5-3" class="tab-content">
								<div class="tab-pane fade in active" id="dL3_1">
									<div class="panel-group smart-accordion-default" id="accordion_dL3_1">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL3_1" href="#collapseOne_dL3_1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Position </a></h4>
											</div>
											<div id="collapseOne_dL3_1" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">None:</label>
															<div class="col-sm-8">
																<div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="none" checked></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Top:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="top"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Bottom:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="bottom"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Center:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="middle"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Inside:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="inside"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Left:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="left"></label>
																</div>
															</div>
														</div>
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Right:</label>
															<div class="col-sm-8">
															  <div class="radio">
																	<label><input type="radio" name="labelPositiondL3" value="right"></label>
																</div>
															</div>
														</div>
						  
													</div>
												</div>
											</div>
											
										</div>								
									</div>
									
									<div class="panel-group smart-accordion-default" id="accordion_dL3_11">
										<div class="panel panel-default">
									<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL3_11" href="#collapseOne_dL3_11"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Label Container </a></h4>
											</div>
											<div id="collapseOne_dL3_11" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group ar_mb_0">
															<label class="control-label col-sm-4" for="font_family">Text:</label>
															<div class="col-sm-8">
															  <input type="text" class="form-control" name="textdL3" id="textdL3" placeholder="" value="[[value]]">
															</div>
														</div>
						  
													</div>
												</div>
											</div>
									</div>
									</div>
																
									
								</div>
								
								<div class="tab-pane fade in" id="dL3_2">
								
									<div class="panel-group smart-accordion-default" id="accordion_dL3_2">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL3_2" href="#collapseOne_dL3_2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Font </a></h4>
											</div>
											<div id="collapseOne_dL3_2" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4" for="font_family">Color:</label>
															<div class="col-sm-8">
															  <div id="cp_color_dL3" class="input-group">
																	<input type="text" class="form-control" name="color_dL3" id="color_dL3" placeholder="Text color" value="#000000">
																	<span class="input-group-addon"><i></i></span>
															  </div>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-sm-4">Size:</label>
															<div class="col-sm-8">
															  <div id="dL3f_size_slider">
																  <div id="dL3f_size_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL3f_size" id="dL3f_size" value="12">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								<div class="tab-pane fade in" id="dL3_3">
									<div class="panel-group smart-accordion-default" id="accordion_dL3_3">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_dL3_3" href="#collapseOne_dL3_3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Properties </a></h4>
											</div>
											<div id="collapseOne_dL3_3" class="panel-collapse collapse in">
												<div class="panel-body no-padding">
													<div class="panel-body">
														
														<div class="form-group">
															<label class="control-label col-sm-4">Offset:</label>
															<div class="col-sm-8">
															  <div id="dL3p_offset_slider">
																  <div id="dL3p_offset_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL3p_offset" id="dL3p_offset" value="5">
															</div>
														</div>
														
														<div class="form-group">
															<label class="control-label col-sm-4">Rotation:</label>
															<div class="col-sm-8">
															  <div id="dL3p_rotation_slider">
																  <div id="dL3p_rotation_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="dL3p_rotation" id="dL3p_rotation" value="0">
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
						
						</div>
						
						
					</div>
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="gridlinestab">					
					  
					<ul id="myTab-6" class="nav nav-tabs bordered">
						<li class="active">
							<a href="#s1-6" data-toggle="tab" class="padding-5" title="Gridlines" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
						</li>
						<li>
							<a href="#s2-6" data-toggle="tab" class="padding-5" title="Fill & Line" ><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
						</li>
					</ul>
					
					<div id="myTabContent1-6" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1-6">
								<div class="panel-group smart-accordion-default" id="accordion-6">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-6" href="#collapseOne-6"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Gridlines </a></h4>
										</div>
										<div id="collapseOne-6" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-8" for="font_family">Primary Major Horizontal:</label>
														<div class="col-sm-4">
															<label class="checkbox">
																<input type="checkbox" name="hgridAlpha" value="1" checked>
															</label>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-8" for="font_family">Primary Major Vertical:</label>
														<div class="col-sm-4">
														  <label class="checkbox">
																<input type="checkbox" name="vgridAlpha" value="1">
														  </label>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-8" for="font_family">Primary Minor Horizontal:</label>
														<div class="col-sm-4">
														  <label class="checkbox">
																<input type="checkbox" name="hminorGridEnabled" value="1">
														  </label>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-8" for="font_family">Primary Minor Vertical:</label>
														<div class="col-sm-4">
														  <label class="checkbox">
																<input type="checkbox" name="vminorGridEnabled" value="1">
														  </label>
														</div>
													</div>
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
								
								
							</div>
							
							<div class="tab-pane fade in" id="s2-6">
							
								<div class="panel-group smart-accordion-default" id="accordion-6-2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-6-2" href="#collapseOne-6-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Horizontal </a></h4>
										</div>
										<div id="collapseOne-6-2" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Line</b></label> 														
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_ghl_color" class="input-group">
																<input type="text" class="form-control" name="ghl_color" id="ghl_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Width:</label>
														<div class="col-sm-8">
														  <div id="ghl_width_slider">
															  <div id="ghl_width_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="ghl_width" id="ghl_width" value="1">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Dash Length:</label>
														<div class="col-sm-8">
														  <div id="ghl_dlength_slider">
															  <div id="ghl_dlength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="ghl_dlength" id="ghl_dlength" value="0">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="ghl_transparency_slider">
															  <div id="ghl_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="ghl_transparency" id="ghl_transparency" value=".25">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Minor Transparency:</label>
														<div class="col-sm-8">
														  <div id="ghl_mtransparency_slider">
															  <div id="ghl_mtransparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="ghl_mtransparency" id="ghl_mtransparency" value="1">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_ghf_color" class="input-group">
																<input type="text" class="form-control" name="ghf_color" id="ghf_color" placeholder="Text color" value="#ffffff">
																<span class="input-group-addon"><i></i></span> 
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="ghf_transparency_slider">
															  <div id="ghf_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="ghf_transparency" id="ghf_transparency" value=".5">
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="panel-group smart-accordion-default" id="accordion-6-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-6-3" href="#collapseOne-6-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Vertical </a></h4>
										</div>
										<div id="collapseOne-6-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Line</b></label> 														
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_gvl_color" class="input-group">
																<input type="text" class="form-control" name="gvl_color" id="gvl_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Width:</label>
														<div class="col-sm-8">
														  <div id="gvl_width_slider">
															  <div id="gvl_width_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="gvl_width" id="gvl_width" value="1">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Dash Length:</label>
														<div class="col-sm-8">
														  <div id="gvl_dlength_slider">
															  <div id="gvl_dlength_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="gvl_dlength" id="gvl_dlength" value="0">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="gvl_transparency_slider">
															  <div id="gvl_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="gvl_transparency" id="gvl_transparency" value="1">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Minor Transparency:</label>
														<div class="col-sm-8">
														  <div id="gvl_mtransparency_slider">
															  <div id="gvl_mtransparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="gvl_mtransparency" id="gvl_mtransparency" value="1">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_gvf_color" class="input-group">
																<input type="text" class="form-control" name="gvf_color" id="gvf_color" placeholder="Text color" value="#ffffff">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="gvf_transparency_slider">
															  <div id="gvf_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="gvf_transparency" id="gvf_transparency" value="1">
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							
						</div>
						
					
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="legendtab">					
									
					  
					<ul id="myTab-7" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1-7" data-toggle="tab" class="padding-5" title="Legend Options" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
							</li>
							<li>
								<a href="#s2-7" data-toggle="tab" class="padding-5" title="Fill and Border" ><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
							</li>
														
						</ul>
						
						
						<div id="myTabContent1-7" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1-7">
								<div class="panel-group smart-accordion-default" id="accordion-7">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-7" href="#collapseOne-7"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Legend Position </a></h4>
										</div>
										<div id="collapseOne-7" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">None:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="legend_position" value="none" ></label>
															</div>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Right:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="legend_position" value="right"></label>
															</div>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Top:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="legend_position" value="top"></label>
															</div>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Left:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="legend_position" value="left"></label>
															</div>
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Bottom:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="legend_position" value="bottom" checked></label>
															</div>
														</div>
													</div>
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
								
								
								
								<div class="panel-group smart-accordion-default" id="accordion-7-1">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-7-1" href="#collapseOne-7-1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Spacing and Padding </a></h4>
										</div>
										<div id="collapseOne-7-1" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Width:</label>
														<div class="col-sm-8">
															<div id="lpwidth_slider">
															  <div id="lpwidth_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="lpwidth" id="lpwidth" value="400">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Spacing:</label>
														<div class="col-sm-8">
														  <div id="lpspacing_slider">
															  <div id="lpspacing_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="lpspacing" id="lpspacing" value="0">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Max Columns:</label>
														<div class="col-sm-8">
														  <div id="lpmaxcol_slider">
															  <div id="lpmaxcol_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="lpmaxcol" id="lpmaxcol" value="2">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Value Width:</label>
														<div class="col-sm-8">
														  <div id="lpvalwidth_slider">
															  <div id="lpvalwidth_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="lpvalwidth" id="lpvalwidth" value="34">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Horizontal:</label>
														<div class="col-sm-8">
														  <div id="lphorizontal_slider">
															  <div id="lphorizontal_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="lphorizontal" id="lphorizontal" value="0"> 
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Vertical:</label>
														<div class="col-sm-8">
															<div id="lpvertical_slider">
																  <div id="lpvertical_handle" class="ui-slider-handle"></div>
																</div>
															  <input type="hidden" class="form-control" name="lpvertical" id="lpvertical" value="10">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_size">Alignment:</label>
														<div class="col-sm-8">
															<select name="lpalignment" class="form-control">
															   <option value="left">Left</option>
															   <option value="center">Center</option>
															   <option value="right">Right</option>
															</select>
														</div>
													</div>
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
								
								
							</div>
							
							<div class="tab-pane fade in" id="s2-7">
							
								<div class="panel-group smart-accordion-default" id="accordion-7-2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-7-2" href="#collapseOne-7-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill and Border </a></h4>
										</div>
										<div id="collapseOne-7-2" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_fill_color" class="input-group">
																<input type="text" class="form-control" name="fill_color" id="fill_color" placeholder="Text color" value="#ffffff">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="fill_transparency_slider">
															  <div id="fill_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="fill_transparency" id="fill_transparency" value=".4">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Border</b></label>														
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_border_color" class="input-group">
																<input type="text" class="form-control" name="border_color" id="border_color" placeholder="Text color" value="#ffffff">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="border_transparency_slider">
															  <div id="border_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="border_transparency" id="border_transparency" value=".4">
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="panel-group smart-accordion-default" id="accordion-7-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-7-3" href="#collapseOne-7-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Text Fill & Outline </a></h4>
										</div>
										<div id="collapseOne-7-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Font</b></label> 														
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
														  <div id="cp_legend_font_color" class="input-group">
																<input type="text" class="form-control" name="legend_font_color" id="legend_font_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
														  </div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-4">Size:</label>
														<div class="col-sm-8">
														  <div id="legend_font_size_slider">
															  <div id="legend_font_size_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="legend_font_size" id="legend_font_size" value="10">
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							
							</div>
							
						</div>
				
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="plotareatab">					
					  
					<ul id="myTab-8" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1-8" data-toggle="tab" class="padding-5" title="Legend Options" ><img src="<?php echo APP_URL; ?>/assets/img/charts/1.png" width="25" /></a>
							</li>
							<li>
								<a href="#s2-8" data-toggle="tab" class="padding-5" title="Effects" ><img src="<?php echo APP_URL; ?>/assets/img/charts/2.png" width="25" /></a>
							</li>
							<li>
								<a href="#s3-8" data-toggle="tab" class="padding-5" title="Chart Options" ><img src="<?php echo APP_URL; ?>/assets/img/charts/4.png" width="25" /></a>
							</li>
														
					</ul>
						
					<div id="myTabContent1-8" class="tab-content"> 
						
						<div class="tab-pane fade in active" id="s1-8">
								<div class="panel-group smart-accordion-default" id="accordion-8-1">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-8-1" href="#collapseOne-8-1"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Fill & Border </a></h4>
										</div>
										<div id="collapseOne-8-1" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
												
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Fill</b></label> 														
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_paf_color" class="input-group">
																<input type="text" class="form-control" name="paf_color" id="paf_color" placeholder="" value="#ffffff">
																<span class="input-group-addon"><i></i></span>
														    </div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Transparency:</label>
														<div class="col-sm-8">
														  <div id="paf_transparency_slider">
															  <div id="paf_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="paf_transparency" id="paf_transparency" value=".5">
														</div>
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>Border</b></label> 														
													</div>
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-4" for="font_family">Enabled:</label>
														<div class="col-sm-8">
															<div class="radio">
																<label><input type="radio" name="paf_border_set" value="1"></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Disabled:</label>
														<div class="col-sm-8">
														  <div class="radio">
																<label><input type="radio" name="paf_border_set" value="0" checked></label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Color:</label>
														<div class="col-sm-8">
															<div id="cp_pab_color" class="input-group">
																<input type="text" class="form-control" name="pab_color" id="pab_color" placeholder="Text color" value="#000000">
																<span class="input-group-addon"><i></i></span>
														    </div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4">Transparency:</label>
														<div class="col-sm-8">
														  <div id="pab_transparency_slider">
															  <div id="pab_transparency_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="pab_transparency" id="pab_transparency" value=".5">
														</div>
													</div>	
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
							
							<div class="tab-pane fade in" id="s2-8">
								<div class="panel-group smart-accordion-default" id="accordion-8-2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-8-2" href="#collapseOne-8-2"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> 3D Effects </a></h4>
										</div>
										<div id="collapseOne-8-2" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
												
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-12 text-left padding-0" for="text_color"><b>3-D Format</b></label> 														
													</div>													
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Angle:</label>
														<div class="col-sm-8">
														  <div id="d3_angle_slider">
															  <div id="d3_angle_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="d3_angle" id="d3_angle" value="0">
														</div>
													</div>
													
													<div class="form-group">
														<label class="control-label col-sm-4" for="font_family">Depth:</label>
														<div class="col-sm-8">
														  <div id="d3_depth_slider">
															  <div id="d3_depth_handle" class="ui-slider-handle"></div>
															</div>
														  <input type="hidden" class="form-control" name="d3_depth" id="d3_depth" value="0">
														</div>
													</div>
																		  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
							
							<div class="tab-pane fade in" id="s3-8">
								<div class="panel-group smart-accordion-default" id="accordion-8-3">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-8-3" href="#collapseOne-8-3"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Chart </a></h4>
										</div>
										<div id="collapseOne-8-3" class="panel-collapse collapse in">
											<div class="panel-body no-padding">
												<div class="panel-body">
													
													<div class="form-group ar_mb_0">
														<label class="control-label col-sm-7" for="font_family">Switch Rows/Colums:</label>
														<div class="col-sm-5">
															<label class="checkbox">
																<input type="checkbox" name="ar_rotate" value="1"> 
															</label>
														</div>
													</div>	
					  
												</div>
											</div>
										</div>
									</div>								
								</div>
							</div>
						
					</div>
						
				</div>
				
				<!--
				<div role="tabpanel" class="tab-pane fade" id="resettab">					
					  
					resettab
				</div>
				-->
			
			
			
			
			
			
			
			
			<!-- old settings-->
				<div role="tabpanel" class="tab-pane fade" id="appeartab">
					
					  <div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Text color:</label> 
						<div class="col-sm-8">
							<div id="cp_text_color--" class="input-group">
								<!--<input type="text" value="#269faf" class="form-control" />-->
								<input type="text" class="form-control" name="text_color" id="text_color" placeholder="Text color" value="#000000">
								<span class="input-group-addon"><i></i></span>
							</div>

						  
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Font family:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="font_family" id="font_family" placeholder="Font family" value="Verdana">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Font size:</label>
						<div class="col-sm-8">
							<div id="font_size_slider--">
							  <div id="font_size_handle--" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" class="form-control" name="font_size" id="font_size" placeholder="Font size" value="11">
						</div>
					  </div>
					  
					  
					  
					  
					
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="backgroudtab">
					<div class="form-group">
						<label class="control-label col-sm-5 text-left" for="text_color"><b>PLOT AREA</b></label>
						
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_family">Angle:</label>
						<div class="col-sm-7">							
							  <input type="text" class="form-control" name="bg_angle" id="bg_angle">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Depth3d:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_depth3D" id="bg_depth3D">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5 text-left" for="text_color"><b>MARGINS</b></label>						
					  </div>
					  					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Margin bottom:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_margin_bottom" id="bg_margin_bottom" value="20">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Margin left:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_margin_left" id="bg_margin_left" value="10">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Margin right:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_margin_right" id="bg_margin_right" value="20">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Margin top:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_margin_top" id="bg_margin_top" value="10">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-12 text-left" for="text_color"><b>BACKGROUND AND BORDER</b></label>
						
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Background alpha:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_alpha" id="bg_alpha">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Background color:</label>
						<div class="col-sm-7">
							<div id="cp_bg_color" class="input-group my_color_picker">
							  <input type="text" class="form-control" name="bg_color" id="bg_color">
							  <span class="input-group-addon"><i></i></span>
							</div>							
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Border alpha:</label>
						<div class="col-sm-7">
							  <input type="text" class="form-control" name="bg_margin_top" id="bg_margin_top">
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-5" for="font_size">Border color:</label>
						<div class="col-sm-7">
							<div id="cp_bg_boder_color" class="input-group my_color_picker">
								<input type="text" class="form-control" name="bg_boder_color" id="bg_boder_color" >
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					  </div>
					  
					  
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="generaltab">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Rotate:</label>
						<div class="col-sm-8">
						  <label><input type="checkbox" name="col_rotate" id="col_rotate"></label>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Auto resize:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="auto_resize" id="auto_resize"></label>
							</div>
						  <!--<input type="text" class="form-control" name="" id="font_family" >-->
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Theme:</label>
						<div class="col-sm-8">
						  <!--<input type="text" class="form-control" name="" id="font_size" >-->
							<select name="theme" class="form-control">
							   <option value="">Not set</option>
							   <option value="light">light</option>
							   <option value="dark">dark</option>
							   <option value="black">black</option>
							   <option value="patterns">patterns</option>
							   <option value="chalk">chalk</option>
							</select>
						</div>
					  </div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="titletab">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Bold:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="title_bold123" id="title_bold123"></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Color:</label>
						<div class="col-sm-8">
							<div id="cp_title_color" class="input-group my_color_picker">
								<input type="text" class="form-control" name="title_color1231" id="title_color123" value="#000000" >
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Size:</label>
						<div class="col-sm-8">
							<div id="title_size_slider">
							  <div id="title_size_handle" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" class="form-control" name="title_sizeaaa" id="title_sizeaaa" placeholder="Size" value="15">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Text:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="title_textaaa" id="title_textaaa" placeholder="Text" value="Chart Title" >
					  </div>
					  
					</div>
					
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="cataxestab">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Start on axis:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="cat_start_on_axes" id="cat_start_on_axes"></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Ignore axis width:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="cat_ignore_axis_width" id="cat_ignore_axis_width"></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Position:</label>
						<div class="col-sm-8">
							<select name="cat_axes_position" class="form-control">
							    <option value="bottom">bottom</option>
								<option value="top">top</option>
							    <option value="left">left</option>
							    <option value="right">right</option>
							</select>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="cat_axes_title" id="cat_axes_title" value="Category Axis title">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Title bold:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="cat_axes_bold" id="cat_axes_bold" value=1></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Title Color:</label>
						<div class="col-sm-8">
							<div id="cp_cat_axes_color" class="input-group my_color_picker">
								<input type="text" class="form-control" name="cat_axes_color" id="cat_axes_color" >
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title font size:</label>
						<div class="col-sm-8">
							<div id="cat_axes_font_size_slider">
							  <div id="cat_axes_font_size_handle" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" class="form-control" name="cat_axes_font_size" id="cat_axes_font_size" value="14">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title rotation:</label>
						<div class="col-sm-8">
							<div id="cat_axes_rotation_slider">
							  <div id="cat_axes_rotation_handle" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" class="form-control" name="cat_axes_rotation" id="cat_axes_rotation" value="0">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Label rotation:</label>
						<div class="col-sm-8">
						
						    <div id="cat_label_rotate_slider">
							  <div id="cat_label_rotate_handle" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" name="cat_label_rotate" id="cat_label_rotate" value="0">
						</div>
					  </div>
					
				</div>
				
				<div role="tabpanel" class="tab-pane fade" id="axestab">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Axis title offset:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="axisTitleOffset" id="axisTitleOffset" value="10" >
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Position:</label>
						<div class="col-sm-8">
							<select name="axes_position" class="form-control">
							   <option value="left">left</option>
							   <option value="right">right</option>
							   <option value="top">top</option>
							   <option value="bottom">bottom</option>
							</select>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="axes_title" id="axes_title" value="Axis title">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Title bold:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="axes_bold" id="axes_bold"></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Title Color:</label>
						<div class="col-sm-8">
							<div id="cp_axes_color" class="input-group my_color_picker">
								<input type="text" class="form-control" name="axes_color" id="axes_color" >
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title font size:</label>
						<div class="col-sm-8">
							<div id="axes_font_size_slider">
							  <div id="axes_font_size_handle" class="ui-slider-handle"></div>
							</div>
						  <input type="hidden" class="form-control" name="axes_font_size" id="axes_font_size" value="14">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title rotation:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="axes_rotation" id="axes_rotation">
						</div>
					  </div>
					
				</div>
				
				
				
				<!-- sample-->
				
				
				<div role="tabpanel" class="tab-pane fade exceltabs" id="sampletab--">
					
					
					<!--
					<ul id="myTab1" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Tab 1 </a>
							</li>
							<li>
								<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Tab 2 </a>
							</li>
														
						</ul>
						
						
						<div id="myTabContent1" class="tab-content"> 
							<div class="tab-pane fade in active" id="s1--">
								<div class="panel-group smart-accordion-default" id="accordion--">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #1 </a></h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body no-padding">
										<div class="panel-body">
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #2 </a></h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #3 </a></h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
						</div>
							</div>
							<div class="tab-pane fade" id="s2--">
								<div class="panel-group smart-accordion-default" id="accordion2">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #1 </a></h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body no-padding">
										<div class="panel-body">
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #2 </a></h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapseThree" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #3 </a></h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
						</div>
							</div>
							
						</div>
						
						-->
						
						
					<!--
					 <div class="panel-group smart-accordion-default" id="accordion">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #1 </a></h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
									<div class="panel-body">
										
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										
											<thead>
												<tr>
													<th>Column name</th>
													<th>Column name</th>
													<th>Column name</th>
													<th>Column name</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Row 1</td>
													<td>Row 2</td>
													<td>Row 3</td>
													<td>Row 4</td>
												</tr>
												<tr>
													<td>Row 1</td>
													<td>Row 2</td>
													<td>Row 3</td>
													<td>Row 4</td>
												</tr>
												<tr>
													<td>Row 1</td>
													<td>Row 2</td>
													<td>Row 3</td>
													<td>Row 4</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #2 </a></h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> Collapsible Group Item #3 </a></h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
									<div class="panel-body">
										Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
									</div>
								</div>
							</div>
						</div>
						
						-->
					
				</div>
				<!-- sample end-->
				
				
		</div>
</div>
<input type="hidden" id="db_inprocess" value="0">
<!--
</form>
-->

