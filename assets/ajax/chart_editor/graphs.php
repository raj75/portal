<?php if (strlen($db_graphs)>1) { echo $db_graphs; } else { ?>
						
						
						
						<?php if (strlen($db_graphs)>1 and ($_GET['gid']!='other' and $_GET['cid']==4) || ($_GET['gid']!='other' and $_GET['cid']==5) ) { 
							
							echo $db_graphs;
							
						} else {?>
						
						<?php if (
									($_GET['gid']=='col' || $_GET['gid']=='bar') and 
									($_GET['cid']==1 || $_GET['cid']==2 || $_GET['cid']==3 || $_GET['cid']==4 || $_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8)
							      ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-2",
							"title": "graph 2",
							"type": "column",
							"valueField": "column-2",
						}
							<?php if ($_GET['cid']==4) {?>
							{
								"fillAlphas": 1,
								"id": "AmGraph-3",
								"newStack": true,
								"title": "graph 3",
								"type": "column",
								"valueField": "column-3",
							}
							<?php }?>
						<?php } ?>
						
						<?php if (($_GET['gid']=='col' || $_GET['gid']=='bar') and $_GET['cid']==9) {?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"labelText": "[[value]]",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"bullet": "round",
							"id": "AmGraph-2",
							"labelText": "[[value]]",
							"lineThickness": 2,
							"title": "graph 2",
							"valueField": "column-2"
						},
						<?php } ?>
						<?php if ($_GET['gid']=='col' and $_GET['cid']==10) { ?>
						{
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						}
						<?php } ?>
						
						
						<?php if ( ($_GET['gid']=='col' and $_GET['cid']==11) || ($_GET['gid']=='bar' and $_GET['cid']==10)) {?>
						{
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						}
						<?php } ?>
						
						<?php if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and $_GET['cid']==12) {?>
						{
							"balloonText": "open:[[open]] close:[[close]]",
							"closeField": "close",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"openField": "open",
							"title": "graph 1",
							"type": "column",
							"valueField": "Not set"
						}
						<?php } ?>
						
						<?php if ( ($_GET['gid']=='col' and $_GET['cid']==13) || ($_GET['gid']=='bar' and $_GET['cid']==11) ) {?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-2",
							"title": "graph 2",
							"type": "column",
							"valueField": "column-2",
							"valueAxis": "ValueAxis-2",
						},
						<?php } ?>
						<?php if ( $_GET['gid']=='col' and $_GET['cid']==14 ) {?>
						{
							"colorField": "color",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"lineColorField": "color",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						},
						<?php } ?>
						
						<?php if ( ($_GET['gid']=='col' and $_GET['cid']==15) || ($_GET['gid']=='bar' and $_GET['cid']==14) ) {?>
						{
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "graph 1"
						},
						<?php } ?>
						
						<?php if ( $_GET['gid']=='bar' and $_GET['cid']==13 ) {?>
						{
							"alphaField": "fill alpha",
							"dashLengthField": "dash length",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						},
						<?php } ?>
						
						<?php if ( $_GET['gid']=='bar' and $_GET['cid']==15 ) {?>
						{
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "graph 1"
						},
						<?php } ?>
						
						//-----lines graph start-------------
						
						<?php if ( $_GET['gid']=='line' and ($_GET['cid']==1 || $_GET['cid']==2 || $_GET['cid']==3 || $_GET['cid']==4 || $_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"bullet": "round",
							"id": "AmGraph-1",
							"title": "graph 1",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"bullet": "square",
							"id": "AmGraph-2",
							"title": "graph 2",
							"valueField": "column-2"
						}
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==11 ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"bullet": "round",
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "smoothedLine",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"bullet": "square",
							"id": "AmGraph-2",
							"title": "graph 2",
							"type": "smoothedLine",
							"valueField": "column-2"
						}
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==12 ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"id": "AmGraph-1",
							"lineThickness": 2,
							"title": "graph 1",
							"type": "step",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"id": "AmGraph-2",
							"lineThickness": 2,
							"title": "graph 2",
							"type": "step",
							"valueField": "column-2"
						}
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==13 ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"id": "AmGraph-1",
							"lineThickness": 2,
							"noStepRisers": true,
							"title": "graph 1",
							"type": "step",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"id": "AmGraph-2",
							"lineThickness": 2,
							"noStepRisers": true,
							"title": "graph 2",
							"type": "step",
							"valueField": "column-2"
						}
						<?php } ?>
						
						//-----area graph start-------------
						
						<?php if ( $_GET['gid']=='area' and ($_GET['cid']==1 || $_GET['cid']==2 || $_GET['cid']==3 || $_GET['cid']==4 || $_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) { ?>
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 0.7,
							"id": "AmGraph-1",
							"lineAlpha": 0,
							"title": "graph 1",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 0.7,
							"id": "AmGraph-2",
							"lineAlpha": 0,
							"title": "graph 2",
							"valueField": "column-2"
						}
						<?php } ?>
						
						//-----other graph start-------------
						
						<?php if ( $_GET['gid']=='other' and ($_GET['cid']==4) ) { ?>
						{
							"balloonText": "Open:<b>[[open]]</b><br>Low:<b>[[low]]</b><br>High:<b>[[high]]</b><br>Close:<b>[[close]]</b><br>",
							"closeField": "close",
							"fillAlphas": 0.9,
							"fillColors": "#7f8da9",
							"highField": "high",
							"id": "g1",
							"lineColor": "#7f8da9",
							"lowField": "low",
							"negativeFillColors": "#db4c3c",
							"negativeLineColor": "#db4c3c",
							"openField": "open",
							"title": "Price:",
							"type": "candlestick",
							"valueField": "close"
						}
						<?php } ?>
						
						<?php if ( $_GET['gid']=='other' and ($_GET['cid']==5) ) { ?>
						{
							"balloonText": "Open:<b>[[open]]</b><br>Low:<b>[[low]]</b><br>High:<b>[[high]]</b><br>Close:<b>[[close]]</b><br>",
							"closeField": "close",
							"fillColors": "#7f8da9",
							"highField": "high",
							"id": "g1",
							"lineColor": "#7f8da9",
							"lowField": "low",
							"negativeLineColor": "#db4c3c",
							"openField": "open",
							"title": "Price:",
							"type": "ohlc",
							"valueField": "close"
						}
						<?php } ?>
						
						<?php } // end of else ?>
						
<?php } // end of else ?>