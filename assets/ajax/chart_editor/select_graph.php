			<!-- Button trigger modal -->
			<!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#chartModal">Open Graphs</button>-->
			<!-- Modal -->
			<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

							</button>
							 <h4 class="modal-title" id="myModalLabel">Select Graph</h4>

						</div>
						<div class="modal-body">
							<div role="tabpanel">
							
							<!--	
							<div class="container">
							<div class="row">
								<div class="col-md-6">
							-->	
								
									<div class="vertical-tab" role="tabpanel">
										<!-- Nav tabs -->
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active"><a href="#columntab" aria-controls="home" role="tab" data-toggle="tab">
												<!--<i class="fa fa-user"></i>--><i class="am pull-left" style="background-image: url(/assets/img/charts/landing/col.png);"></i> <span class="icon_name">Column</span></a>
											</li>
											<li role="presentation"><a href="#bartab" aria-controls="profile" role="tab" data-toggle="tab">
												<i class="am pull-left" style="background-image: url(/assets/img/charts/landing/bar.png);"></i> <span class="icon_name">Bar</span></a>
											</li>
											<li role="presentation"><a href="#linetab" aria-controls="messages" role="tab" data-toggle="tab">
												<i class="am pull-left" style="background-image: url(/assets/img/charts/landing/line.png);"></i> <span class="icon_name">Line</span></a>
											</li>
											<li role="presentation"><a href="#areatab" aria-controls="messages" role="tab" data-toggle="tab">
												<i class="am pull-left" style="background-image: url(/assets/img/charts/landing/area.png);"></i> <span class="icon_name">Area</span></a>
											</li>
											<li role="presentation"><a href="#pietab" aria-controls="messages" role="tab" data-toggle="tab">
												<i class="am pull-left" style="background-image: url(/assets/img/charts/landing/pie.png);"></i> <span class="icon_name">Pie & Donut</span></a>
											</li>
											<li role="presentation"><a href="#othertab" aria-controls="messages" role="tab" data-toggle="tab">
												<i class="am pull-left" style="background-image: url(/assets/img/charts/landing/other.png);"></i> <span class="icon_name">Other chart types</span></a>
											</li>
										</ul>
										<!-- Tab panes -->
										<div class="tab-content tabs">
											<div role="tabpanel" class="tab-pane fade in active" id="columntab">
												
												<ul class="am-grid chartlist">
													<!--<li style="background-image: url('//live.amcharts.com/static/samples/column/clustered.png');"><span id="col-1">clustered</span></li>-->
													<li style="background-image: url('/assets/img/charts/column/clustered.png');"><span id="col-1">clustered</span></li>
													<li style="background-image: url('/assets/img/charts/column/stacked.png');"><span id="col-2">stacked</span></li>
													<li style="background-image: url('/assets/img/charts/column/100-percent-stacked.png');"><span id="col-3">100% stacked</span></li>
													<li style="background-image: url('/assets/img/charts/column/clustered-and-stacked.png');"><span id="col-4">cluster & stack</span></li>
													<li style="background-image: url('/assets/img/charts/column/3D-clustered.png');"><span id="col-5">3D clustered</span></li>
													<li style="background-image: url('/assets/img/charts/column/3D-stacked.png');"><span id="col-6">3D stacked</span></li>
													<li style="background-image: url('/assets/img/charts/column/3D-100-percent-stacked.png');"><span id="col-7">3D 100% stacked</span></li>
													<li style="background-image: url('/assets/img/charts/column/3D-column.png');"><span id="col-8">3D column</span></li>
													<li style="background-image: url('/assets/img/charts/column/column-and-line.png');"><span id="col-9">column and line</span></li>
													<li style="background-image: url('/assets/img/charts/column/column-with-date-based-data.png');"><span id="col-10">date based data</span></li>
													<li style="background-image: url('/assets/img/charts/column/column-with-scroll.png');"><span id="col-11">column with scroll</span></li>
													<li style="background-image: url('/assets/img/charts/column/floating-columns.png');"><span id="col-12">floating columns</span></li>
													<li style="background-image: url('/assets/img/charts/column/two-value-axes.png');"><span id="col-13">two value axes</span></li>
													<li style="background-image: url('/assets/img/charts/column/custom-color.png');"><span id="col-14">custom colors</span></li>
													<li style="background-image: url('/assets/img/charts/column/logarithmic-scale.png');"><span id="col-15">logarithmic scale</span></li>
												</ul>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="bartab">
												<ul class="am-grid chartlist">
												   <li style="background-image: url('/assets/img/charts/bar/clustered.png');"><span id="bar-1">clustered</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/stacked.png');"><span id="bar-2">stacked</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/100-percent-stacked.png');"><span id="bar-3">100% stacked</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/clustered-and-stacked.png');"><span id="bar-4">clustered and stacked</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/3D-clustered.png');"><span id="bar-5">3D clustered</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/3D-stacked.png');"><span id="bar-6">3D stacked</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/3D-100-percent-stacked.png');"><span id="bar-7">3D 100% stacked</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/3D-bar.png');"><span id="bar-8">3D bar</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/bar-and-line.png');"><span id="bar-9">bar and line</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/bar-with-scroll.png');"><span id="bar-10">bar with scroll</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/two-value-axes.png');"><span id="bar-11">two value axes</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/floating-bars.png');"><span id="bar-12">floating bars</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/dashed-stroke.png');"><span id="bar-13">dashed stroke</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/logarithmic-scale.png');"><span id="bar-14">logarithmic scale</span></li>
												   <li style="background-image: url('/assets/img/charts/bar/bar-with-guide.png');"><span id="bar-15">bar with guide</span></li>
												</ul>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="linetab">												
												<ul class="am-grid">
													<li style="background-image: url('/assets/img/charts/line/line.png');"><span id="line-1">line</span></li>
													<li style="background-image: url('/assets/img/charts/line/stacked-line.png');"><span id="line-2">stacked line</span></li>
													<li style="background-image: url('/assets/img/charts/line/100-percent-stacked-line.png');"><span id="line-3">100% stacked line</span></li>
													<li style="background-image: url('/assets/img/charts/line/rotated-line.png');"><span id="line-4">rotated line</span></li>
													<li style="background-image: url('/assets/img/charts/line/date-series-daily.png');"><span id="line-5">date series, daily</span></li>
													<li style="background-image: url('/assets/img/charts/line/date-series-monthly.png');"><span id="line-6">date series, monthly</span></li>
													<li style="background-image: url('/assets/img/charts/line/date-series-yearly.png');"><span id="line-7">date series, yearly</span></li>
													<li style="background-image: url('/assets/img/charts/line/time-series-hourly.png');"><span id="line-8">time series, hourly</span></li>
													<li style="background-image: url('/assets/img/charts/line/time-series-minutes.png');"><span id="line-9">time series, minutes</span></li>
													<li style="background-image: url('/assets/img/charts/line/time-series-seconds.png');"><span id="line-10">time series, seconds</span></li>
													<li style="background-image: url('/assets/img/charts/line/smoothed-line.png');"><span id="line-11">smoothed line</span></li>
													<li style="background-image: url('/assets/img/charts/line/step-line.png');"><span id="line-12">step line</span></li>
													<li style="background-image: url('/assets/img/charts/line/step-no-risers.png');"><span id="line-13">step no risers</span></li>
												</ul>											
											</div>
											<div role="tabpanel" class="tab-pane fade" id="areatab">
												<ul class="am-grid">
													<li style="background-image: url('/assets/img/charts/area/area.png');"><span id="area-1">area</span></li>
													<li style="background-image: url('/assets/img/charts/area/stacked-area.png');"><span id="area-2">stacked area</span></li>
													<li style="background-image: url('/assets/img/charts/area/100-percent-stacked-area.png');"><span id="area-3">100% stacked area</span></li>
													<li style="background-image: url('/assets/img/charts/area/rotated-area.png');"><span id="area-4">rotated area</span></li>
													<li style="background-image: url('/assets/img/charts/area/date-series-daily.png');"><span id="area-5">date series, daily</span></li>
													<li style="background-image: url('/assets/img/charts/area/date-series-monthly.png');"><span id="area-6">date series, monthly</span></li>
													<li style="background-image: url('/assets/img/charts/area/date-series-yearly.png');"><span id="area-7">date series, yearly</span></li>
													<li style="background-image: url('/assets/img/charts/area/time-series-hourly.png');"><span id="area-8">time series, hourly</span></li>
													<li style="background-image: url('/assets/img/charts/area/time-series-minutes.png');"><span id="area-9">time series, minutes</span></li>
													<li style="background-image: url('/assets/img/charts/area/time-series-seconds.png');"><span id="area-10">time series, seconds</span></li>
												</ul>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="pietab">
												<ul class="am-grid">
													<li style="background-image: url('/assets/img/charts/pie/pie.png');"><span id="pie-1">pie</span></li>
													<li style="background-image: url('/assets/img/charts/pie/3d-pie.png');"><span id="pie-2">3D pie</span></li>
													<li style="background-image: url('/assets/img/charts/pie/donut.png');"><span id="pie-3">donut</span></li>
													<li style="background-image: url('/assets/img/charts/pie/3d-donut.png');"><span id="pie-4">3D donut</span></li>
													
												</ul>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="othertab">
												<ul class="am-grid">
													<li style="background-image: url('/assets/img/charts/other/angular-gauge.png');"><span id="other-1">angular gauge</span></li>
													<li style="background-image: url('/assets/img/charts/other/funnel.png');"><span id="other-2">funnel</span></li>
													<li style="background-image: url('/assets/img/charts/other/pyramid.png');"><span id="other-3">pyramid</span></li>
													<li style="background-image: url('/assets/img/charts/other/candlestick.png');"><span id="other-4">candlestick</span></li>
													<li style="background-image: url('/assets/img/charts/other/ohlc.png');"><span id="other-5">OHLC</span></li>
													<li style="background-image: url('/assets/img/charts/other/radar.png');"><span id="other-6">radar</span></li>
													<li style="background-image: url('/assets/img/charts/other/polar.png');"><span id="other-7">polar</span></li>
												</ul>
											</div>
										</div>
									</div>
									
									
						<!--			
								</div>
							</div>
						</div>
						-->		
								
								
								<!-- Nav tabs -->
								<!--
								<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active"><a href="#columntab" aria-controls="columnstab" role="tab" data-toggle="tab">Column</a>

									</li>
									<li role="presentation"><a href="#bartab" aria-controls="bartab" role="tab" data-toggle="tab">Bar</a>

									</li>
								</ul>
								-->
								<!-- Tab panes -->
								<!--
								<div class="tab-content">
								
									<div role="tabpanel" class="tab-pane active" id="columntab">
										<span class="graph_item"><a href="#false">Clustered</a></span>
										<span class="graph_item"><a href="#false">Stacked</a></span>
										<span class="graph_item"><a href="#false">100% Stacked</a></span>
									</div>
									
									<div role="tabpanel" class="tab-pane" id="bartab">
										<span class="graph_item"><a href="#false">Clustered</a></span>
										<span class="graph_item"><a href="#false">Stacked</a></span>
										<span class="graph_item"><a href="#false">100% Stacked</a></span>
									</div>
								</div>
								-->
							</div>
						</div>
						<!--
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary save">Save changes</button>
						</div>
						-->
					</div>
				</div>
			</div>