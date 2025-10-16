<?php 
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';
//print_r($_GET); 
//Array ( [gid] => col [cid] => 1 ) 
$pcount = count($_POST);
//print_r($_POST);

//echo $_POST['chart_query'];

				$default_sql = "SELECT period as category,accounts as column_1,accounts*2 as column_2 from new_accounts order by category";
				//$default_sql = "SELECT category as category,column1 as column_1,column2 as column_2 from sample_charting limit 3";
				//query for pie chart
				/*SELECT category as category,column1 as column1 from sample_charting limit 3*/

				if (isset($_POST['chart_query']) || strlen($default_sql) > 1) {
					$db_data_provider = "";
					//$sql = "select * from sample_charting";
					if (isset($_POST['chart_query'])) {
						$sql = $_POST['chart_query'];
					} else {
						$sql = $default_sql; 
					}
					$result = $mysqli->query($sql);
					//$obj = $result->fetch_object();
					$column_s = "";
					
					// Get field information for all fields
					  $fieldinfo = $result -> fetch_fields();
					  //print_r($fieldinfo);
					  $db_graphs = "";
					  $value_axes = "";
					  $secondary_vertical_axes = "";
					  $axes_li = "";
					  //$graph_settings = "";
					  
					  /*
					  if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
						
						$graph_settings .= '
											"type": "column",
											"fillAlphas": 1,
						';
						
						
					  } else if ($_GET['gid']=='line') {
						$graph_settings .= '"bullet": "round",';
						
					  } else if ($_GET['gid']=='area') {
						$graph_settings .= '
											"fillAlphas": 0.7,
											"lineAlpha": 0,
						';
					  }
					  */
					  
							
							
					  
					  foreach ($fieldinfo as $key=>$val) {
						
						//echo ($val->name);
						if ($key==0) {
							continue;
						}
						
						$graph_settings = "";
						
						if(strpos($val->name,"_line") !== false){
							$graph_settings .= '"bullet": "round",';
							
						} else if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
						
							$graph_settings .= '
												"type": "column",
												"fillAlphas": 1,
							';
						
						
						} else if ($_GET['gid']=='line') {
							$graph_settings .= '"bullet": "round",';
						
						} else if ($_GET['gid']=='area') {
							$graph_settings .= '
											"fillAlphas": 0.7,
											"lineAlpha": 0,
							';
						} else if ($_GET['gid']=='other' and $_GET['cid']==6) {
							$graph_settings .= '"bullet": "round",';
						}
						
						if(strpos($val->name,"_axes") !== false){
							
							$graph_settings .= '"valueAxis": "ValueAxis-'.$key.'",';
							
							
							
							
								/*
								----------secondary_vertical_axes settings---------------
								*/
								$secondary_vertical_axes .= '{';
							
								$secondary_vertical_axes .= '"id": "ValueAxis-'.$key.'",';
								
								if ($pcount>0 and empty($_POST['svv_label_enable'])) {
									$secondary_vertical_axes .= '"labelsEnabled": false,';
								}
								
								if (isset($_POST['svva_color']) and !empty($_POST['svva_color'])) {
									$axes_2Color = $_POST['svva_color'];
									$secondary_vertical_axes .= '"color": "'.$axes_2Color.'",';
								}
								
								//$secondary_vertical_axes .= '"title": "Vertical Axis title",';
								//$secondary_vertical_axes .= '"position": "right",';
								
								
								if (isset($_POST['svv_width']) and !empty($_POST['svv_width'])) {
									$axes_2Thickness = $_POST['svv_width'];
									$secondary_vertical_axes .= '"axisThickness": '.$axes_2Thickness.',';
								}
								
								if (isset($_POST['svv_transparency']) and !empty($_POST['svv_transparency'])) {
									$axes_2Alpha = $_POST['svv_transparency'];
									$secondary_vertical_axes .= '"axisAlpha": '.$axes_2Alpha.',';
								}
								
								if (isset($_POST['svv_title']) and !empty($_POST['svv_title'])) {
									$axes_2title = $_POST['svv_title'];
									$secondary_vertical_axes .= '"title": "'.$axes_2title.'",';
								} else {
									//$secondary_vertical_axes .= '"title": "Vertical Axis title",';
								}
								
								if (isset($_POST['svv_size']) and !empty($_POST['svv_size'])) {
									$axes_2_font_size = $_POST['svv_size'];
									$secondary_vertical_axes .= '"titleFontSize": '.$axes_2_font_size.',';
								} else{
									$secondary_vertical_axes .= '"titleFontSize": 15,';
								}
								
								if (isset($_POST['svv_color']) and !empty($_POST['svv_color'])) {
									$axes_2_color = $_POST['svv_color'];
									$secondary_vertical_axes .= '"titleColor": "'.$axes_2_color.'",';
								}
								
								
								$axes_2_bold = (isset($_POST['svv_bold']) and $_POST['svv_bold']==1)?"true":"false";
								
								$secondary_vertical_axes .= '"titleBold": '.$axes_2_bold.',';	
								
								
								if (isset($_POST['svv_rotation']) and !empty($_POST['svv_rotation'])) {
									$axes_2_rotation = $_POST['svv_rotation'];
									$secondary_vertical_axes .= '"titleRotation": '.$axes_2_rotation.',';
								}
								
								if (isset($_POST['svv_ticklength']) and !empty($_POST['svv_ticklength'])) {
									$axes_2_tickLength = $_POST['svv_ticklength'];
									$secondary_vertical_axes .= '"tickLength": '.$axes_2_tickLength.',';
								}
								
								if (isset($_POST['svv_mticklength']) and !empty($_POST['svv_mticklength'])) {
									$axes_2_mtickLength = $_POST['svv_mticklength'];
									$secondary_vertical_axes .= '"minorTickLength": '.$axes_2_mtickLength.',';
								}
								
								if (isset($_POST['svv_tposition']) and $_POST['svv_tposition'] == 'start') {
									$axes_2_tickPosition = $_POST['svv_tposition'];
									$secondary_vertical_axes .= '"tickPosition": "'.$axes_2_tickPosition.'",';
								}
								
								if (isset($_POST['svv_aposition']) and !empty($_POST['svv_aposition'])) {
									$axes_2_position = $_POST['svv_aposition'];
									$secondary_vertical_axes .= '"position": "'.$axes_2_position.'",';
								}
								
								if (isset($_POST['svv_lfrequency']) and !empty($_POST['svv_lfrequency'])) {
									$axes_2_labelFrequency = $_POST['svv_lfrequency'];
									$secondary_vertical_axes .= '"labelFrequency": '.$axes_2_labelFrequency.',';
								}
								
								if (isset($_POST['svv_hidefirst']) and $_POST['svv_hidefirst']==1) {
									$secondary_vertical_axes .= '"showFirstLabel": false,';
								}
								
								if (isset($_POST['svv_hidelast']) and $_POST['svv_hidelast']==1) {
									$secondary_vertical_axes .= '"showLastLabel": false,';
								}
								
								if (isset($_POST['svv_alLocation']) and $_POST['svv_alLocation']=='inside') {
									$secondary_vertical_axes .= '"inside": true,';
								}
								
								if (isset($_POST['svv_tworows']) and $_POST['svv_tworows']==1) {
									$secondary_vertical_axes .= '"twoLineMode": true,';
								}
								if ($pcount>0 and empty($_POST['svv_mpchange'])) {
									$secondary_vertical_axes .= '"markPeriodChange": false,';
								}
								
								if (isset($_POST['svv_parsedate']) and $_POST['svv_parsedate']=='date') {
									$secondary_vertical_axes .= '"parseDates": true,';
								}
								
								if (isset($_POST['svv_minPeriod']) and !empty($_POST['svv_minPeriod'])) {
									$axes_2_minPeriod = $_POST['svv_minPeriod'];
									$secondary_vertical_axes .= '"minPeriod": "'.$axes_2_minPeriod.'",';
								}
								if (isset($_POST['svv_lugap']) and !empty($_POST['svv_lugap'])) {
									$axes_2_minHorizontalGap = $_POST['svv_lugap'];
									$secondary_vertical_axes .= '"minHorizontalGap": '.$axes_2_minHorizontalGap.',';
								}
								if (isset($_POST['svv_lrotation']) and !empty($_POST['svv_lrotation'])) {
									$axes_2_labelRotation = $_POST['svv_lrotation']; 
									$secondary_vertical_axes .= '"labelRotation": '.$axes_2_labelRotation.',';
								}
								
					
					
				
							$secondary_vertical_axes .= '},';
				
				
				
				
				
				
				
				
				
							
							/*
							$axisTitleOffset2 = (isset($_POST["axisTitleOffset2"]))?$_POST["axisTitleOffset2"]:"10";
							$title2 = (isset($_POST['axes_title2']))?$_POST['axes_title2']:"Right Axes Title";
							$position2 = (isset($_POST['axes_position2']))?$_POST['axes_position2']:"right";
							$titleBold2 = (isset($_POST['axes_bold2']) and $_POST['axes_bold2']==1)?"true":"false";
							$titleColor2 = (isset($_POST['axes_color2']))?$_POST['axes_color2']:'#000000';
							$titleFontSize = (isset($_POST['axes_font_size2']))?$_POST['axes_font_size2']:'14';
							$titleRotation2 = -90;
							if (isset($_POST['axes_rotation2']) and !empty($_POST['axes_rotation'])) {
								$titleRotation2 = $_POST['axes_rotation2'];
							}
							
							$value_axes .= '
							{
							"id": "ValueAxis-'.$key.'",
							"axisTitleOffset": "'.$axisTitleOffset2.'",
							"title": "'.$title2.'",
							"position": "'.$position2.'",
							"titleBold": '.$titleBold2.',
							"titleColor": "'.$titleColor2.'",
							"titleFontSize": "'.$titleFontSize.'",
							"titleRotation": '.$titleRotation2.',
							
							
							//"position": "right",
							//"gridAlpha": 0,
							//"title": "Axis title"
							
							
							},
							';
							*/
						}
						
						/*
						if(strpos($val->name,"_line") !== false){
							$graph_settings .= '"bullet": "round",';
						}
						*/
						//$gtitleind = "gTitle".$key;
						$gtitleind = "titleds".$key;
						$gtitle = ( isset($_POST[$gtitleind]) and !empty($_POST[$gtitleind]) )?$_POST[$gtitleind]:'Graph '.$key;
						
						
						
						//-----new form settings---------------
						
						if (isset($_POST['typeds'.$key]) and !empty($_POST['typeds'.$key])) {							
							$graph_settings .= '"type": "'.$_POST['typeds'.$key].'",';
						}
						if (isset($_POST['dsf'.$key.'_color']) and !empty($_POST['dsf'.$key.'_color'])) {							
							$graph_settings .= '"fillColors": "'.$_POST['dsf'.$key.'_color'].'",';
						}
						if (isset($_POST['dsf'.$key.'_transparency']) and !empty($_POST['dsf'.$key.'_transparency'])) {
							//$graph_settings .= '"fillAlphas": "'.$_POST['dsf'.$key.'_transparency'].'",';
							$graph_settings .= '"fillAlphas": '.$_POST['dsf'.$key.'_transparency'].',';
						}
						if (isset($_POST['dsl'.$key.'_color']) and !empty($_POST['dsl'.$key.'_color'])) {
							$graph_settings .= '"lineColor": "'.$_POST['dsl'.$key.'_color'].'",';
						}
						if (isset($_POST['ds'.$key.'_lthickness']) and !empty($_POST['ds'.$key.'_lthickness'])) {
							$graph_settings .= '"lineThickness": "'.$_POST['ds'.$key.'_lthickness'].'",';
						}						
						if (isset($_POST['ds'.$key.'_dlength']) and !empty($_POST['ds'.$key.'_dlength'])) {
							$graph_settings .= '"dashLength": "'.$_POST['ds'.$key.'_dlength'].'",';
						}
						if (isset($_POST['dsl'.$key.'_transparency']) and !empty($_POST['dsl'.$key.'_transparency'])) {
							$graph_settings .= '"lineAlpha": "'.$_POST['dsl'.$key.'_transparency'].'",';
						}
						if (isset($_POST['ds'.$key.'_rcorner']) and !empty($_POST['ds'.$key.'_rcorner'])) {
							$graph_settings .= '"cornerRadiusTop": "'.$_POST['ds'.$key.'_rcorner'].'",';
						}
						if (isset($_POST['ds'.$key.'_cwidth']) and !empty($_POST['ds'.$key.'_cwidth'])) {
							$graph_settings .= '"columnWidth": '.$_POST['ds'.$key.'_cwidth'].',';
						}
						if (isset($_POST['ds'.$key.'type']) and !empty($_POST['ds'.$key.'type'])) {
							$graph_settings .= '"bullet": "'.$_POST['ds'.$key.'type'].'",';
						}
						if (isset($_POST['ds'.$key.'co_color']) and !empty($_POST['ds'.$key.'co_color'])) {
							$graph_settings .= '"bulletColor": "'.$_POST['ds'.$key.'co_color'].'",';
						}
						if (isset($_POST['ds'.$key.'co_size']) and !empty($_POST['ds'.$key.'co_size'])) {
							$graph_settings .= '"bulletSize": "'.$_POST['ds'.$key.'co_size'].'",';
						}
						if (isset($_POST['ds'.$key.'co_transparency']) and !empty($_POST['ds'.$key.'co_transparency'])) {
							$graph_settings .= '"bulletAlpha": "'.$_POST['ds'.$key.'co_transparency'].'",';
						}
						if (isset($_POST['ds'.$key.'_bcolor']) and !empty($_POST['ds'.$key.'_bcolor'])) {
							$graph_settings .= '"bulletBorderColor": "'.$_POST['ds'.$key.'_bcolor'].'",';
						}
						if (isset($_POST['ds'.$key.'_bthikness']) and !empty($_POST['ds'.$key.'_bthikness'])) {
							$graph_settings .= '"bulletBorderThickness": "'.$_POST['ds'.$key.'_bthikness'].'",';
						}
						//$graph_settings .= 'bulletBorderAlpha:1,';
						if (isset($_POST['ds'.$key.'_btransparency']) and !empty($_POST['ds'.$key.'_btransparency'])) {
							$graph_settings .= '"bulletBorderAlpha": "'.$_POST['ds'.$key.'_btransparency'].'",';
							//$graph_settings .= '1,';
						}
						
						if (isset($_POST['labelPositiondL'.$key]) and $_POST['labelPositiondL'.$key] != 'none') {
							if (isset($_POST['labelPositiondL'.$key]) and !empty($_POST['labelPositiondL'.$key])) {
								$graph_settings .= '"labelAnchor": "middle",';
								$graph_settings .= '"labelPosition": "'.$_POST['labelPositiondL'.$key].'",'; 
							}
							if (isset($_POST['textdL'.$key]) and !empty($_POST['textdL'.$key])) {
								$graph_settings .= '"labelText": "'.$_POST['textdL'.$key].'",';
							} else {
								$graph_settings .= '"labelText": "[[value]]",';
							}
							if (isset($_POST['color_dL'.$key]) and !empty($_POST['color_dL'.$key])) {
								$graph_settings .= '"color": "'.$_POST['color_dL'.$key].'",';
							}
							if (isset($_POST['dL'.$key.'f_size']) and !empty($_POST['dL'.$key.'f_size'])) {
								$graph_settings .= '"fontSize": '.$_POST['dL'.$key.'f_size'].',';
							}
							if (isset($_POST['dL'.$key.'p_offset']) and !empty($_POST['dL'.$key.'p_offset'])) {
								$graph_settings .= '"labelOffset": '.$_POST['dL'.$key.'p_offset'].',';
							}
							if (isset($_POST['dL'.$key.'p_rotation']) and !empty($_POST['dL'.$key.'p_rotation'])) {
								$graph_settings .= '"labelRotation": '.$_POST['dL'.$key.'p_rotation'].',';
							}
							
							//"labelOffset": 10,
							
							
						}
						
						
						$db_graphs .= '
						{
							'.$graph_settings.'
							
							"id": "AmGraph-'.$key.'",
							
							"title": "'.$gtitle.'",
							
							"valueField": "'.$val->name.'",
						}, ';
						//printf("Name: %s\n", $val -> name);
						//printf("Table: %s\n", $val -> table);
						//printf("Max. Len: %d\n", $val -> max_length);
						
						//-----------------pie settings---------------
						$mix_settings = '"titleField": "category",
										 "valueField": "'.$val->name.'",';
						
						
					  }
					  
  
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$db_data_provider = "db";
						$db_data_provider_arr[] = $row;
						
						
						/*
						if (isset($row['column_2'])) {
							$column_s = '"column-2": "'.$row['column_2'].'",';
						}
						$db_data_provider .= '{';
							if (isset($row['category'])) {
								$db_data_provider .= '"category": "'.$row['category'].'"',
							}
							
							if ( strpos($mystring, $findme) ) {
								
							}
							
							"column-1": '.$row['column_1'].',
							'.$column_s.'
						$db_data_provider .='},';
						*/
					}
					
					//$db_data_provider_json =  str_replace("column_","column-",json_encode($db_data_provider_arr));
					$db_data_provider_json =  json_encode($db_data_provider_arr);
					//echo $db_data_provider;
				}
				
				/*
				----------category axes settings---------------
				*/
				$cat_axes_setings = '';
				
				/*
				if (isset($_POST['cat_start_on_axes']) and !empty($_POST['cat_start_on_axes'])) {
					$cat_axes_setings .= '"startOnAxis": true,';
				}
				
				if (isset($_POST['cat_ignore_axis_width']) and !empty($_POST['cat_ignore_axis_width'])) {
					$cat_axes_setings .= '"ignoreAxisWidth": true,';
				}
				
				if (isset($_POST['cat_axes_position']) and !empty($_POST['cat_axes_position'])) {
					$cat_axes_position = $_POST['cat_axes_position'];
					$cat_axes_setings .= '"position": "'.$cat_axes_position.'",';
				}
				*/
				
				//if ( $pcount==0 || ( isset($_POST['phh_label_enable']) and $_POST['phh_label_enable']==1 ) ) {
				
					if ($pcount>0 and empty($_POST['phh_label_enable'])) {
						//$cat_axes_color = $_POST['phh_color'];
						$cat_axes_setings .= '"labelsEnabled": false,';
					}
					
					if (isset($_POST['phh_title']) and !empty($_POST['phh_title'])) {
						$cat_axes_title = $_POST['phh_title'];
						$cat_axes_setings .= '"title": "'.$cat_axes_title.'",';
					} else {
						$cat_axes_setings .= '"title": "Category Axis title",';
					}
					
					$cat_axes_bold = (isset($_POST['phh_bold']) and $_POST['phh_bold']==1)?"true":"false";
					
					$cat_axes_setings .= '"titleBold": '.$cat_axes_bold.',';			
					
					
					if (isset($_POST['phh_color']) and !empty($_POST['phh_color'])) {
						$cat_axes_color = $_POST['phh_color'];
						$cat_axes_setings .= '"titleColor": "'.$cat_axes_color.'",';
					}
					
					if (isset($_POST['phh_size']) and !empty($_POST['phh_size'])) {
						$cat_axes_font_size = $_POST['phh_size'];
						$cat_axes_setings .= '"titleFontSize": '.$cat_axes_font_size.',';
					} else{
						$cat_axes_setings .= '"titleFontSize": 15,';
					}
					
					if (isset($_POST['phh_rotation']) and !empty($_POST['phh_rotation'])) {
						$cat_axes_rotation = $_POST['phh_rotation'];
						$cat_axes_setings .= '"titleRotation": '.$cat_axes_rotation.',';
					}
					
					if (isset($_POST['phha_color']) and !empty($_POST['phha_color'])) {
						$cat_axes_axisColor = $_POST['phha_color'];
						$cat_axes_setings .= '"color": "'.$cat_axes_axisColor.'",';
					}
					
					if (isset($_POST['phh_width']) and !empty($_POST['phh_width'])) {
						$cat_axisThickness = $_POST['phh_width'];
						$cat_axes_setings .= '"axisThickness": '.$cat_axisThickness.',';
					}
					
					if (isset($_POST['phh_transparency']) and !empty($_POST['phh_transparency'])) {
						$cat_axisAlpha = $_POST['phh_transparency'];
						$cat_axes_setings .= '"axisAlpha": '.$cat_axisAlpha.',';
					}
					if (isset($_POST['phh_ticklength']) and !empty($_POST['phh_ticklength'])) {
						$cat_axistickLength = $_POST['phh_ticklength'];
						$cat_axes_setings .= '"tickLength": '.$cat_axistickLength.',';
					}
					if (isset($_POST['phh_mticklength']) and !empty($_POST['phh_mticklength'])) {
						$cat_axismtickLength = $_POST['phh_mticklength'];
						$cat_axes_setings .= '"minorTickLength": '.$cat_axismtickLength.',';
					}
					if (isset($_POST['phh_tposition']) and $_POST['phh_tposition'] == 'start') {
						$cat_tickPosition = $_POST['phh_tposition'];
						$cat_axes_setings .= '"tickPosition": "'.$cat_tickPosition.'",';
					}
					if (isset($_POST['phh_aposition']) and !empty($_POST['phh_aposition'])) {
						$cat_position = $_POST['phh_aposition'];
						$cat_axes_setings .= '"position": "'.$cat_position.'",';
					}
					if (isset($_POST['phh_lfrequency']) and !empty($_POST['phh_lfrequency'])) {
						$cat_labelFrequency = $_POST['phh_lfrequency'];
						$cat_axes_setings .= '"labelFrequency": '.$cat_labelFrequency.',';
					}
					if (isset($_POST['phh_hidefirst']) and $_POST['phh_hidefirst']==1) {
						//$cat_labelFrequency = $_POST['phh_hidefirst'];
						$cat_axes_setings .= '"showFirstLabel": false,';
					}
					if (isset($_POST['phh_hidelast']) and $_POST['phh_hidelast']==1) {
						//$cat_labelFrequency = $_POST['phh_lfrequency'];
						$cat_axes_setings .= '"showLastLabel": false,';
					}
					if (isset($_POST['phh_alLocation']) and $_POST['phh_alLocation']=='inside') {
						$cat_axes_setings .= '"inside": true,';
					}
					if (isset($_POST['phh_tworows']) and $_POST['phh_tworows']==1) {
						$cat_axes_setings .= '"twoLineMode": true,';
					}
					if ($pcount>0 and empty($_POST['phh_mpchange'])) {
						$cat_axes_setings .= '"markPeriodChange": false,';
					}
					if (isset($_POST['phh_parsedate']) and $_POST['phh_parsedate']=='date') {
						$cat_axes_setings .= '"parseDates": true,';
					}
					if (isset($_POST['phh_minPeriod']) and !empty($_POST['phh_minPeriod'])) {
						$cat_minPeriod = $_POST['phh_minPeriod'];
						$cat_axes_setings .= '"minPeriod": "'.$cat_minPeriod.'",';
					}
					if (isset($_POST['phh_lugap']) and !empty($_POST['phh_lugap'])) {
						$cat_minHorizontalGap = $_POST['phh_lugap'];
						$cat_axes_setings .= '"minHorizontalGap": '.$cat_minHorizontalGap.',';
					}
					if (isset($_POST['phh_lrotation']) and !empty($_POST['phh_lrotation'])) {
						$cat_labelRotation = $_POST['phh_lrotation']; 
						$cat_axes_setings .= '"labelRotation": '.$cat_labelRotation.',';
					}
					
					if (isset($_POST['phh_equal']) and $_POST['phh_equal']==1) {
						//$cat_axes_setings .= '"equalSpacing": true,';
					}
					
					/*
					if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
						$cat_axes_setings .= '"gridThickness": 1,';
						if (isset($_POST['ghl_width']) and $_POST['ghl_width']>1) {
							$ghl_width = $_POST['ghl_width'];
							$cat_axes_setings .= '"gridThickness": "'.$ghl_width.'",';
						}
					} else { 
						$cat_axes_setings .= '"gridThickness": 0,';
					}
					*/
					
					
					
					/*
					if (isset($_POST['vminorGridEnabled']) and $_POST['vminorGridEnabled']==1) {
						//$vminorGridEnabled = $_POST['vminorGridEnabled'];
						$cat_axes_setings .= '"minorGridEnabled": true,';
						
						if (isset($_POST['gvl_mtransparency'])) {
							$minorGridEnabled = $_POST['gvl_mtransparency'];
							$cat_axes_setings .= '"minorGridEnabled": '.$minorGridEnabled.',';
						}
					}
					*/
					
					/*
					if (isset($_POST['ghl_color']) and !empty($_POST['ghl_color'])) {
						$gridColor = $_POST['ghl_color'];
						$cat_axes_setings .= '"gridColor": "'.$gridColor.'",';
					}
					*/
					
					if (isset($_POST['gvl_color']) and !empty($_POST['gvl_color'])) {
						$gridColor = $_POST['gvl_color'];
						$cat_axes_setings .= '"gridColor": "'.$gridColor.'",';
					}
					
					if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
					
						if (isset($_POST['gvl_width']) and !empty($_POST['gvl_width'])) {
							$vgridThickness = $_POST['gvl_width'];
							$cat_axes_setings .= '"gridThickness": '.$vgridThickness.',';
						}
					
						//$vgridAlpha = $_POST['vgridAlpha'];
						//$cat_axes_setings .= '"gridAlpha": '.$vgridAlpha.',';
					} else {
						$cat_axes_setings .= '"gridThickness": 0,';
					}
					
					if (isset($_POST['gvl_dlength']) and !empty($_POST['gvl_dlength'])) {
						$dashLength = $_POST['gvl_dlength'];
						$cat_axes_setings .= '"dashLength": '.$dashLength.',';
					}
					
					if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
						$gridAlpha = $_POST['gvl_transparency'];
						$cat_axes_setings .= '"gridAlpha": '.$gridAlpha.',';
					}
					
					if (isset($_POST['gvf_color']) and !empty($_POST['gvf_color'])) {
						$fillColor = $_POST['gvf_color'];
						$cat_axes_setings .= '"fillColor": "'.$fillColor.'",';
					}
					if (isset($_POST['gvf_transparency']) and !empty($_POST['gvf_transparency'])) {
						$fillAlpha = $_POST['gvf_transparency'];
						$cat_axes_setings .= '"fillAlpha": "'.$fillAlpha.'",';
					}
					
					if (isset($_POST['vminorGridEnabled']) and $_POST['vminorGridEnabled']==1) {
						//$mix_settings .= '"dataDateFormat": "YYYY-MM-DD",';
						$cat_axes_setings .= '"parseDates": true,';
						//$cat_axes_setings .= '"minPeriod": "MM",';
						//$cat_axes_setings .= '"minorGridEnabled": true,'; // only works with "parseDates": true // but it change the axis
					}
					
					if (isset($_POST['gvl_mtransparency'])  and !empty($_POST['gvl_mtransparency'])) {
						$vminorGridAlpha = $_POST['gvl_mtransparency'];
						//$cat_axes_setings .= '"minorGridAlpha": '.$vminorGridAlpha.','; // only works with "parseDates": true // but it change the axis
					}
					
					//"parseDates": true,					
					//"minorGridAlpha": 0.21,
					//"minorGridEnabled": true
					
					//gvl_transparency
					
					//$cat_axes_setings .= '"equalSpacing": true,';
					
					//"equalSpacing": true,
					
					//"labelRotation": 16.2,
					//"minHorizontalGap": 25
							//"minPeriod": "YYYY",
					//
					//
					

					
					//
					
					//
					
					
				//}
				
				
				/*
				if (isset($_POST['cat_label_rotate']) and !empty($_POST['cat_label_rotate'])) {
					$cat_label_rotate = $_POST['cat_label_rotate'];
					$cat_axes_setings .= '"labelRotation": '.$cat_label_rotate.',';
				}
				
				if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
					
					if (isset($_POST['gvl_width']) and !empty($_POST['gvl_width'])) {
						$vgridThickness = $_POST['gvl_width'];
						$cat_axes_setings .= '"gridThickness": '.$vgridThickness.',';
					}
				
					//$vgridAlpha = $_POST['vgridAlpha'];
					//$cat_axes_setings .= '"gridAlpha": '.$vgridAlpha.',';
				} else {
					$cat_axes_setings .= '"gridThickness": 0,';
				}
				
				if (isset($_POST['vminorGridEnabled']) and $_POST['vminorGridEnabled']==1) {
					//$vminorGridEnabled = $_POST['vminorGridEnabled'];
					$cat_axes_setings .= '"minorGridEnabled": true,';
					
					if (isset($_POST['gvl_mtransparency'])) {
						$minorGridEnabled = $_POST['gvl_mtransparency'];
						$cat_axes_setings .= '"minorGridEnabled": '.$minorGridEnabled.',';
					}
				}
				
				if (isset($_POST['gvl_color']) and !empty($_POST['gvl_color'])) {
					$gridColor = $_POST['gvl_color'];
					$cat_axes_setings .= '"gridColor": "'.$gridColor.'",';
				}
				*/
				
				
				
				
				
				
				
													/*
													$vgridThickness = 0;
													if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
														$vgridThickness = 1;
													}
													
													//$cat_axes_setings .= '"gridThickness":'..'';
													
													if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
														//$vgridThickness = $_POST['vgridAlpha'];
														$cat_axes_setings .= '"gridThickness": '.$vgridThickness.',';
													} else if (isset($_POST['gvl_width']) and !empty($_POST['gvl_width'])) {
														$gridThickness = $_POST['gvl_width'];
														$cat_axes_setings .= '"gridThickness": '.$gridThickness.',';
													}
													*/
				
				/*
				if (isset($_POST['gvl_dlength']) and !empty($_POST['gvl_dlength'])) {
					$dashLength = $_POST['gvl_dlength'];
					$cat_axes_setings .= '"dashLength": '.$dashLength.',';
				}				
				if (isset($_POST['vgridAlpha']) and $_POST['vgridAlpha']==1) {
					$gridAlpha = $_POST['gvl_transparency'];
					$cat_axes_setings .= '"gridAlpha": '.$gridAlpha.',';
				}
				*/
												/*
												if (isset($_POST['vminorGridEnabled']) and $_POST['vminorGridEnabled']==1) {
													$minorGridEnabled = $_POST['gvl_mtransparency'];
													$cat_axes_setings .= '"minorGridEnabled": '.$minorGridEnabled.',';
												}
												*/
				/*
				if (isset($_POST['gvf_color']) and !empty($_POST['gvf_color'])) {
					$fillColor = $_POST['gvf_color'];
					$cat_axes_setings .= '"fillColor": "'.$fillColor.'",';
				}
				if (isset($_POST['gvf_transparency']) and !empty($_POST['gvf_transparency'])) {
					$fillAlpha = $_POST['gvf_transparency'];
					$cat_axes_setings .= '"fillAlpha": "'.$fillAlpha.'",';
				}
				*/
				
												//$cat_axes_setings .= '"fillAlpha": 1,';
												/*
												if (isset($_POST['ghl_color']) and !empty($_POST['ghl_color'])) {
													$gridColor = $_POST['ghl_color'];
													$cat_axes_setings .= '"gridColor": "'.$gridColor.'",';
												}
												*/
												/*
												if (isset($_POST['ghl_color']) and !empty($_POST['ghl_color'])) {
													$gridColor = $_POST['ghl_color'];
													$cat_axes_setings .= '"gridColor": "'.$gridColor.'",';
												}
												*/
				
				
				
				
				/*
				----------primary_vertical_axes settings---------------
				*/
				$primary_vertical_axes = '{';
					
					$stackType = stackType();
					$primary_vertical_axes .= '"stackType": "'.$stackType.'",';
					
				
					if ($pcount>0 and empty($_POST['pvv_label_enable'])) {
						$primary_vertical_axes .= '"labelsEnabled": false,';
					}
					
					if (isset($_POST['pvva_color']) and !empty($_POST['pvva_color'])) {
						$axes_1Color = $_POST['pvva_color'];
						$primary_vertical_axes .= '"color": "'.$axes_1Color.'",';
					}
					
					if (isset($_POST['pvv_width']) and !empty($_POST['pvv_width'])) {
						$axes_1Thickness = $_POST['pvv_width'];
						$primary_vertical_axes .= '"axisThickness": '.$axes_1Thickness.',';
					}
					
					if (isset($_POST['pvv_transparency']) and !empty($_POST['pvv_transparency'])) {
						$axes_1Alpha = $_POST['pvv_transparency'];
						$primary_vertical_axes .= '"axisAlpha": '.$axes_1Alpha.',';
					}
					
					if (isset($_POST['pvv_title']) and !empty($_POST['pvv_title'])) {
						$axes_1title = $_POST['pvv_title'];
						$primary_vertical_axes .= '"title": "'.$axes_1title.'",';
					} else {
						//$primary_vertical_axes .= '"title": "Vertical Axis title",';
					}
					
					$axes_1_bold = (isset($_POST['pvv_bold']) and $_POST['pvv_bold']==1)?"true":"false";
					
					$primary_vertical_axes .= '"titleBold": '.$axes_1_bold.',';			
					
					
					if (isset($_POST['pvv_color']) and !empty($_POST['pvv_color'])) {
						$axes_1_color = $_POST['pvv_color'];
						$primary_vertical_axes .= '"titleColor": "'.$axes_1_color.'",';
					}
					
					if (isset($_POST['pvv_size']) and !empty($_POST['pvv_size'])) {
						$axes_1_font_size = $_POST['pvv_size'];
						$primary_vertical_axes .= '"titleFontSize": '.$axes_1_font_size.',';
					} else{
						$primary_vertical_axes .= '"titleFontSize": 15,';
					}
					
					if (isset($_POST['pvv_rotation']) and !empty($_POST['pvv_rotation'])) {
						$axes_1_rotation = $_POST['pvv_rotation'];
						$primary_vertical_axes .= '"titleRotation": '.$axes_1_rotation.',';
					}
					
					
					if (isset($_POST['pvv_ticklength']) and !empty($_POST['pvv_ticklength'])) {
						$axes_1_tickLength = $_POST['pvv_ticklength'];
						$primary_vertical_axes .= '"tickLength": '.$axes_1_tickLength.',';
					}
					if (isset($_POST['pvv_mticklength']) and !empty($_POST['pvv_mticklength'])) {
						$axes_1_mtickLength = $_POST['pvv_mticklength'];
						$primary_vertical_axes .= '"minorTickLength": '.$axes_1_mtickLength.',';
					}
					if (isset($_POST['pvv_tposition']) and $_POST['pvv_tposition'] == 'start') {
						$axes_1_tickPosition = $_POST['pvv_tposition'];
						$primary_vertical_axes .= '"tickPosition": "'.$axes_1_tickPosition.'",';
					}
					if (isset($_POST['pvv_aposition']) and !empty($_POST['pvv_aposition'])) {
						$axes_1_position = $_POST['pvv_aposition'];
						$primary_vertical_axes .= '"position": "'.$axes_1_position.'",';
					}
					if (isset($_POST['pvv_lfrequency']) and !empty($_POST['pvv_lfrequency'])) {
						$axes_1_labelFrequency = $_POST['pvv_lfrequency'];
						$primary_vertical_axes .= '"labelFrequency": '.$axes_1_labelFrequency.',';
					}
					if (isset($_POST['pvv_hidefirst']) and $_POST['pvv_hidefirst']==1) {
						//$cat_labelFrequency = $_POST['phh_hidefirst'];
						$primary_vertical_axes .= '"showFirstLabel": false,';
					}
					if (isset($_POST['pvv_hidelast']) and $_POST['pvv_hidelast']==1) {
						//$cat_labelFrequency = $_POST['phh_lfrequency'];
						$primary_vertical_axes .= '"showLastLabel": false,';
					}
					if (isset($_POST['pvv_alLocation']) and $_POST['pvv_alLocation']=='inside') {
						$primary_vertical_axes .= '"inside": true,';
					}
					if (isset($_POST['pvv_tworows']) and $_POST['pvv_tworows']==1) {
						$primary_vertical_axes .= '"twoLineMode": true,';
					}
					if ($pcount>0 and empty($_POST['pvv_mpchange'])) {
						$primary_vertical_axes .= '"markPeriodChange": false,';
					}
					if (isset($_POST['pvv_parsedate']) and $_POST['pvv_parsedate']=='date') {
						$primary_vertical_axes .= '"parseDates": true,';
					}
					if (isset($_POST['pvv_minPeriod']) and !empty($_POST['pvv_minPeriod'])) {
						$axes_1_minPeriod = $_POST['pvv_minPeriod'];
						$primary_vertical_axes .= '"minPeriod": "'.$axes_1_minPeriod.'",';
					}
					if (isset($_POST['pvv_lugap']) and !empty($_POST['pvv_lugap'])) {
						$axes_1_minHorizontalGap = $_POST['pvv_lugap'];
						$primary_vertical_axes .= '"minHorizontalGap": '.$axes_1_minHorizontalGap.',';
					}
					if (isset($_POST['pvv_lrotation']) and !empty($_POST['pvv_lrotation'])) {
						$axes_1_labelRotation = $_POST['pvv_lrotation']; 
						$primary_vertical_axes .= '"labelRotation": '.$axes_1_labelRotation.',';
					}
					
					if (isset($_POST['hgridAlpha']) and $_POST['hgridAlpha']==1) {
						$primary_vertical_axes .= '"gridThickness": 1,';
						if (isset($_POST['ghl_width']) and $_POST['ghl_width']>1) {
							$ghl_width = $_POST['ghl_width'];
							$primary_vertical_axes .= '"gridThickness": "'.$ghl_width.'",'; 
						}
						
						if (isset($_POST['ghl_transparency']) and !empty($_POST['ghl_transparency'])) {
							$ghl_transparency = $_POST['ghl_transparency'];
							$primary_vertical_axes .= '"gridAlpha": "'.$ghl_transparency.'",';
						}
						
						if (isset($_POST['ghl_mtransparency']) and !empty($_POST['ghl_mtransparency'])) {
							$ghl_mtransparency = $_POST['ghl_mtransparency'];
							$primary_vertical_axes .= '"minorGridAlpha": "'.$ghl_mtransparency.'",';
						}
						
					
					} else { 
						$primary_vertical_axes .= '"gridThickness": 0,';
					}
					
					if (isset($_POST['hminorGridEnabled']) and $_POST['hminorGridEnabled']==1) {
						$primary_vertical_axes .= '"minorGridEnabled": true,'; // only works with "parseDates": true // but it change the axis
					}
					
					if (isset($_POST['ghl_color']) and !empty($_POST['ghl_color'])) {
						$gridColor = $_POST['ghl_color'];
						$primary_vertical_axes .= '"gridColor": "'.$gridColor.'",';
					}
					
					if (isset($_POST['ghl_dlength']) and !empty($_POST['ghl_dlength'])) {
						$ghl_dlength = $_POST['ghl_dlength'];
						$primary_vertical_axes .= '"dashLength": "'.$ghl_dlength.'",';
					}
					
					if (isset($_POST['ghf_color']) and !empty($_POST['ghf_color'])) {
						$ghf_color = $_POST['ghf_color'];
						$primary_vertical_axes .= '"fillColor": "'.$ghf_color.'",';
					}
					
					if (isset($_POST['ghf_transparency']) and !empty($_POST['ghf_transparency'])) {
						$ghf_transparency = $_POST['ghf_transparency'];
						$primary_vertical_axes .= '"fillAlpha": '.$ghf_transparency.',';
					} else {
						$primary_vertical_axes .= '"fillAlpha": 1,';
					}
					
					//$primary_vertical_axes .= '"fillAlpha": 0.16,';
					//$primary_vertical_axes .= '"fillColor": "#008000",';
					
					
					//$primary_vertical_axes .= '"gridAlpha": 0.7,';
					//$primary_vertical_axes .= '"gridThickness": 7,';
					
					//"gridAlpha": (isset($_POST['ghl_transparency']))?$_POST['ghl_transparency']:0;,
					
					//"dashLength": "(isset($_POST['ghl_dlength']))?$_POST['ghl_dlength']:'1';",
					
					
				
				$primary_vertical_axes .= '},';
								
				
				
				//--------------back ground settings---------------
				$background_setings = '';
				
				if (isset($_POST['d3_angle']) and $_POST['d3_angle']>0) {
					$bg_angle = $_POST['d3_angle'];
					$background_setings .= '"angle": '.$bg_angle.',';
				}
				
				if (isset($_POST['d3_depth']) and $_POST['d3_depth']>0) {
					$bg_depth3D = $_POST['d3_depth'];
					$background_setings .= '"depth3D": '.$bg_depth3D.',';
				}
				
				/*
				if (isset($_POST['bg_auto_margin_offset']) and !empty($_POST['bg_auto_margin_offset'])) {
					$bg_auto_margin_offset = $_POST['bg_auto_margin_offset'];
					$background_setings .= '"autoMarginOffset": '.$bg_auto_margin_offset.',';
				}
				*/
				
				
				/*
				$bg_autoMargins = (isset($_POST['bg_autoMargins']) and $_POST['bg_autoMargins']==1)?"true":"false";
				
				//$background_setings .= '"autoMargins": '.$bg_autoMargins.',';
				*/
				
				if (isset($_POST['bg_margin_bottom']) and !empty($_POST['bg_margin_bottom'])) {
					$bg_margin_bottom = $_POST['bg_margin_bottom'];
					$background_setings .= '"marginBottom": '.$bg_margin_bottom.',';
				} else {
					$background_setings .= '"marginBottom": 20,';
				}
				
				if (isset($_POST['bg_margin_left']) and !empty($_POST['bg_margin_left'])) {
					$bg_margin_left = $_POST['bg_margin_left'];
					$background_setings .= '"marginLeft": '.$bg_margin_left.',';
				} else {
					$background_setings .= '"marginLeft": 10,';
				}
				
				if (isset($_POST['bg_margin_right']) and !empty($_POST['bg_margin_right'])) {
					$bg_margin_right = $_POST['bg_margin_right'];
					$background_setings .= '"marginRight": '.$bg_margin_right.',';
				} else if ( $_GET['gid']=='other' and ($_GET['cid']==2 || $_GET['cid']==3) ) {
					$background_setings .= '"marginRight": 160,';
				} else {
					$background_setings .= '"marginRight": 20,';
				}
				
				if (isset($_POST['bg_margin_top']) and !empty($_POST['bg_margin_top'])) {
					$bg_margin_top = $_POST['bg_margin_top'];
					$background_setings .= '"marginTop": '.$bg_margin_top.',';
				} else {
					$background_setings .= '"marginTop": 10,';
				}
				
				$bg_alpha = "1"; // also used for div of chart
				if (isset($_POST['paf_transparency']) and !empty($_POST['paf_transparency'])) {
					$bg_alpha = $_POST['paf_transparency'];
					$background_setings .= '"backgroundAlpha": '.$bg_alpha.',';
				}
				
				$bg_color = "#FFFFFF"; // also used for div of chart
				if (isset($_POST['paf_color']) and !empty($_POST['paf_color'])) {
					$bg_color = $_POST['paf_color'];
					$background_setings .= '"backgroundColor": "'.$bg_color.'",';
				}
				
				
				
				if (isset($_POST['paf_border_set'])) {
					if ($_POST['paf_border_set']==1) {
						$borderColor = $_POST['pab_color'];
						$background_setings .= '"borderColor": "'.$borderColor.'",';
						
						$borderAlpha = $_POST['pab_transparency'];
						$background_setings .= '"borderAlpha": "'.$borderAlpha.'",';
					} else if ($_POST['paf_border_set']==0) {
						$background_setings .= '"borderColor": "#ffffff",';
						$background_setings .= '"borderAlpha": "0",';
					}
				} else {
					$background_setings .= '"borderColor": "#000000",';
					$background_setings .= '"borderAlpha": ".5",';
				}
				
				if (isset($_POST['ar_rotate']) and $_POST['ar_rotate']==1) {
					$background_setings .= '"rotate": true,';
				}
				
				
				//borderColor
				
				//-----------------format chart area--------------------
				$format_chart_area = "";
				if (isset($_POST['caf_color']) and !empty($_POST['caf_color'])) {
					$plotAreaFillColors = $_POST['caf_color'];
					$format_chart_area .= '"plotAreaFillColors": "'.$plotAreaFillColors.'",';
				} else {
					$format_chart_area .= '"plotAreaFillColors": "#000000",';
				}
				if (isset($_POST['caf_transparency']) and !empty($_POST['caf_transparency'])) {
					$plotAreaFillAlphas = $_POST['caf_transparency'];
					$format_chart_area .= '"plotAreaFillAlphas": "'.$plotAreaFillAlphas.'",';
				}
				
				if (isset($_POST['plotAreaBorderColor'])) {
					//$plotAreaBorderColor = $_POST['plotAreaBorderColor'];
					//$format_chart_area .= '"plotAreaBorderColor": "'.$plotAreaBorderColor.'",';
					if ($_POST['plotAreaBorderColor']==1) {
						$format_chart_area .= '"plotAreaBorderColor": "#000000",';
						
						if (isset($_POST['cab_transparency']) and !empty($_POST['cab_transparency'])) {
							$plotAreaBorderAlpha = $_POST['cab_transparency'];
							$format_chart_area .= '"plotAreaBorderAlpha": "'.$plotAreaBorderAlpha.'",';
						}
					} else if ($_POST['plotAreaBorderColor']==0) {
						$format_chart_area .= '"plotAreaBorderColor": "#ffffff",';
						$format_chart_area .= '"plotAreaBorderAlpha": "0",';
					}
					
					/*
					if (isset($_POST['cab_transparency']) and !empty($_POST['cab_transparency'])) {
						$plotAreaBorderAlpha = $_POST['cab_transparency'];
						$format_chart_area .= '"plotAreaBorderAlpha": "'.$plotAreaBorderAlpha.'",';
					} else {
						//$format_chart_area .= '"plotAreaBorderAlpha": "0",';
					}
					*/
					
				} else {
					$format_chart_area .= '"plotAreaBorderColor": "#000000",';
					$format_chart_area .= '"plotAreaBorderAlpha": ".5",';
				}
				
				
				/*
				if (isset($_POST['cab_transparency']) and !empty($_POST['cab_transparency'])) {
					$plotAreaBorderAlpha = $_POST['cab_transparency'];
					$format_chart_area .= '"plotAreaBorderAlpha": "'.$plotAreaBorderAlpha.'",';
				} else {
					$format_chart_area .= '"plotAreaBorderAlpha": "0",';
				}
				*/
				
				
				
				if (isset($_POST['fontFamily']) and !empty($_POST['fontFamily'])) {
					$fontFamily = $_POST['fontFamily'];
					$format_chart_area .= '"fontFamily": "'.$fontFamily.'",';
				} else {
					$format_chart_area .= '"fontFamily": "Calibri",';
				}
				
				//-----format legend----------------------
				$format_legend = '"legend": {';
				
				if (isset($_POST['legend_position'])) {
					$legend_position = $_POST['legend_position'];
					if ($legend_position == 'none') {
						$format_legend .= '"enabled":false,';
					} else {
						$format_legend .= '"enabled":true,';
						//$format_legend .= '"useGraphSettings":true,';
					}
					$format_legend .= '"position":"'.$legend_position.'",';
				} else {
					$format_legend .= '"position":"bottom",';
				}
				
				if (isset($_POST['lpwidth']) and !empty($_POST['lpwidth'])) {
					$lpwidth = $_POST['lpwidth'];
					$format_legend .= '"width":'.$lpwidth.',';
				}
				
				if (isset($_POST['lpspacing']) and !empty($_POST['lpspacing'])) {
					$spacing = $_POST['lpspacing'];
					$format_legend .= '"spacing":'.$spacing.',';
				}
				if (isset($_POST['lpmaxcol']) and !empty($_POST['lpmaxcol'])) {
					$maxColumns = $_POST['lpmaxcol'];
					$format_legend .= '"maxColumns":'.$maxColumns.',';
				}
				if (isset($_POST['lpvalwidth']) and !empty($_POST['lpvalwidth'])) {
					$valueWidth = $_POST['lpvalwidth'];
					$format_legend .= '"valueWidth":'.$valueWidth.',';
				}
				if (isset($_POST['lphorizontal']) and !empty($_POST['lphorizontal'])) {
					$horizontalGap = $_POST['lphorizontal'];
					$format_legend .= '"horizontalGap":'.$horizontalGap.',';
				}
				if (isset($_POST['lpvertical']) and !empty($_POST['lpvertical'])) {
					$verticalGap = $_POST['lpvertical'];
					$format_legend .= '"verticalGap":'.$verticalGap.',';
				}
				
				if (isset($_POST['fill_color']) and !empty($_POST['fill_color'])) {
					$backgroundColor = $_POST['fill_color'];
					$format_legend .= '"backgroundColor":"'.$backgroundColor.'",';
				} 
				
				if (isset($_POST['fill_transparency']) and !empty($_POST['fill_transparency'])) {
					$backgroundAlpha = $_POST['fill_transparency'];
					$format_legend .= '"backgroundAlpha":"'.$backgroundAlpha.'",';
				}
				
				if (isset($_POST['border_color']) and !empty($_POST['border_color'])) {
					$borderColor = $_POST['border_color'];
					$format_legend .= '"borderColor":"'.$borderColor.'",';
				}
				if (isset($_POST['border_transparency']) and !empty($_POST['border_transparency'])) {
					$borderAlpha = $_POST['border_transparency'];
					$format_legend .= '"borderAlpha":"'.$borderAlpha.'",';
				}
				if (isset($_POST['legend_font_color']) and !empty($_POST['legend_font_color'])) {
					$legend_font_color = $_POST['legend_font_color'];
					$format_legend .= '"color":"'.$legend_font_color.'",';
				}
				if (isset($_POST['legend_font_size']) and !empty($_POST['legend_font_size'])) {
					$legend_font_size = $_POST['legend_font_size'];
					$format_legend .= '"fontSize":'.$legend_font_size.',';
				}
				
				//
				//
				
				
				
				
				
				
				
				$format_legend .= '},';
				
				
				
						//$col_names[] = $row['Field'];
					//}
				
?>
<!DOCTYPE html>
<html>
	<head>
		<title>chart created with amCharts | amCharts</title>
		<meta name="description" content="chart created using amCharts live editor" />
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<!-- amCharts javascript sources -->
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/pie.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/funnel.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/radar.js"></script>
		<?php if ( isset($_POST['theme']) and strlen($_POST['theme'])> 1 ) { ?>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/<?php echo $_POST['theme'];?>.js"></script>
		<?php } ?>

		<!-- amCharts javascript code -->
		<script type="text/javascript">
			//AmCharts.makeChart("chartdiv123", 
			AmCharts.makeChart("chartdiv", 
				{
					//"dataDateFormat": "YYYY-MM-DD JJ:NN:SS",
					//"dataDateFormat": "YYYY-MM-DD",
					<?php if ( $_GET['gid']=='other' and ($_GET['cid']==2 || $_GET['cid']==3 ) ) { ?>
					"type": "funnel", 
					"balloonText": "[[title]]:<b>[[value]]</b>",
					"labelPosition": "right",
					<?php if ($_GET['cid']==2) {?>
					"neckHeight": "30%",
					"neckWidth": "40%",
					<?php } else if ($_GET['cid']==3) { ?>
					"rotate": true,
					<?php } ?>
	
					<?php echo $mix_settings; ?>
					
					<?php } else if ($_GET['gid']=='pie') { ?>
					"type": "pie", 
					<?php echo $mix_settings;
						if ($_GET['cid']==2) {
							echo '"depth3D": 15,';
						} else if ($_GET['cid']==3) {
							echo '"innerRadius": "40%",';
						} else if ($_GET['cid']==4) {
							echo '"depth3D": 15,';
							echo '"innerRadius": "40%",';
						}
					?> 
					<?php } else if ($_GET['gid']=='other' and ($_GET['cid']==6 || $_GET['cid']==7) ) { ?>					
					"type": "radar",
					<?php } else { ?>					
					"type": "serial",
					<?php } ?>
					
					<?php 
						echo $background_setings;
						echo $format_chart_area;
						echo $format_legend;
					?>
					
					<?php if ( $_GET['gid']=='line' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) { ?>					
					"categoryField": "date",
					<?php } else if ( $_GET['gid']=='area' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) { ?>
					"categoryField": "date",
					<?php } else if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) { ?>
					"categoryField": "date",
					<?php } else { ?>
					"categoryField": "category",
					<?php } ?>
					
					<?php if ( ($_GET['gid']=='col' and $_GET['cid']==10) || ($_GET['gid']=='line' and $_GET['cid']==5) ) { ?>
					"dataDateFormat": "YYYY-MM-DD",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==6 ) { ?>
					"dataDateFormat": "YYYY-MM",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==7 ) { ?>
					"dataDateFormat": "YYYY",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==8 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH",
					<?php } ?>	
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==9 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH:NN",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==10 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH:NN:SS",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==5 ) { ?>
					"dataDateFormat": "YYYY-MM-DD",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==6 ) { ?>
					"dataDateFormat": "YYYY-MM",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==7 ) { ?>
					"dataDateFormat": "YYYY",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==8 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==9 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH:NN",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==10 ) { ?>
					"dataDateFormat": "YYYY-MM-DD HH:NN:SS",
					<?php } ?>
					
					<?php if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) { ?>
					"dataDateFormat": "YYYY-MM-DD",
					<?php } ?>
					
					
					
					
					<?php if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and 
							   ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8) 
							 ) { ?>
					"angle": 30,
					"depth3D": 30,
					<?php } ?>
					
					<?php if ( isset($_POST['col_rotate']) || $_GET['gid']=='bar' || ($_GET['gid']=='line' and $_GET['cid']==4) ) { ?>
					"rotate": true,
					<?php } elseif ( $_GET['gid']=='area' and $_GET['cid']==4 ) { ?>
					"rotate": true,
					<?php } ?>
					
					"startDuration": 1,
					
					<?php if (isset($_POST['text_color'])) { ?>
					"color": "<?php echo $_POST['text_color'];?>",
					<?php } ?>
					<?php if (isset($_POST['font_family'])) { ?>
					/*"fontFamily": "<?php echo $_POST['font_family'];?>",*/
					<?php } ?>
					<?php if (isset($_POST['font_size'])) { ?>
					"fontSize": <?php echo $_POST['font_size'];?>,
					<?php } ?>					
					<?php if (isset($_POST['theme'])) { ?>
					"theme": "<?php echo $_POST['theme'];?>",
					<?php } ?>
					
					//"theme": "dark", 
					
					"autoResize": <?php echo (isset($_POST['auto_resize']))?"true":"false";?>,
					
					"categoryAxis": {
						"gridPosition": "start",
						<?php if ( ($_GET['gid']=='col' and $_GET['cid']==10) || ($_GET['gid']=='line' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10 )) ) { ?>
						"parseDates": true,
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and ($_GET['cid']==5 || $_GET['cid']==6 || $_GET['cid']==7 || $_GET['cid']==8 || $_GET['cid']==9 || $_GET['cid']==10) ) { ?>
						"parseDates": true,
						<?php } ?>
						
						<?php if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) { ?>
						"parseDates": true,
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==6 ) { ?>
						"minPeriod": "MM",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==7 ) { ?>
						"minPeriod": "YYYY",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==8 ) { ?>
						"minPeriod": "hh",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==9 ) { ?>
						"minPeriod": "mm",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='line' and $_GET['cid']==10 ) { ?>
						"minPeriod": "ss",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and $_GET['cid']==6 ) { ?>
						"minPeriod": "MM",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and $_GET['cid']==7 ) { ?>
						"minPeriod": "YYYY",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and $_GET['cid']==8 ) { ?>
						"minPeriod": "hh",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and $_GET['cid']==9 ) { ?>
						"minPeriod": "mm",
						<?php } ?>
						
						<?php if ( $_GET['gid']=='area' and $_GET['cid']==10 ) { ?>
						"minPeriod": "ss",
						<?php } ?>
						
						<?php 
						
						$cat_axes_setings .= '
						//"dashLength": 1,
						//"minorGridEnabled": true,
						//"labelsEnabled": true,
						//"tickLength": 0,
						//"parseDates": true';
						
						echo $cat_axes_setings;
						
						?>		
							/*
							"parseDates": true,
							//"axisColor": "#DADADA",
							//"dashLength": 1,
							"gridAlpha": 1,
							"minorGridEnabled": true,
							"minorGridAlpha": 1,
							//"minPeriod": "MM"
							*/
							
							
					}, // end of category axes
					
					
					<?php if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and $_GET['cid']==10 ) { ?>
					"chartCursor": {
						"enabled": true
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==6 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "MMM YYYY"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==7 ) { ?>
					"chartCursor": {
						"enabled": true,
						"animationDuration": 0,
						"categoryBalloonDateFormat": "YYYY"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==8 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==9 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==10 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN:SS"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==6 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "MMM YYYY"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==7 ) { ?>
					"chartCursor": {
						"enabled": true,
						"animationDuration": 0,
						"categoryBalloonDateFormat": "YYYY"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==8 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==9 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==10 ) { ?>
					"chartCursor": {
						"enabled": true,
						"categoryBalloonDateFormat": "JJ:NN:SS"
					},
					"chartScrollbar": {
						"enabled": true
					},
					<?php } ?>
					
					<?php if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) { ?>
					"chartCursor": {
						"enabled": true
					},
					"chartScrollbar": {
						"enabled": true,
						"graph": "g1",
						"graphType": "line",
						"scrollbarHeight": 30
					},
					<?php } ?>
					
					"trendLines": [],
					"graphs": [
						
						<?php include_once("graphs.php");?>
						
					],
					"guides": [
						<?php if ( $_GET['gid']=='bar' and $_GET['cid']==15 ) {?>
						{
							"above": true,
							"dashLength": 5,
							"id": "Guide-1",
							"inside": true,
							"label": "max allowed value",
							"labelRotation": 90,
							"lineAlpha": 1,
							"lineColor": "#ff0000",
							"value": 10
						}
						<?php } ?>
					],
					"valueAxes": [
					
						// {
							// <?php //if ( $_GET['gid']=='other' and ($_GET['cid']==6 || $_GET['cid']==7) ) {?>
							// "axisTitleOffset": 20,
							// "id": "ValueAxis-1",
							// "minimum": 0,
							// "axisAlpha": 0.15,
							// "dashLength": 3,
							// <?php //if ($_GET['cid']==7) { ?>
							// "gridType": "circles",			
							// <?php //} ?>
							
							// <?php //} else if ( $_GET['gid']=='other' and ($_GET['cid']==4 || $_GET['cid']==5) ) {?>
							// "id": "ValueAxis-1",							
							// <?php //} else { ?>
		
							// "id": "ValueAxis-1",
							// "axisTitleOffset": "<?php //echo (isset($_POST['axisTitleOffset']))?$_POST['axisTitleOffset']:'10';?>",
							// "title": "<?php //echo (isset($_POST['axes_title']))?$_POST['axes_title']:'Axes Title';?>",
							// "position": "<?php //echo (isset($_POST['axes_position']))?$_POST['axes_position']:'left';?>",
							// "titleBold": <?php //echo (isset($_POST['axes_bold']))?'true':'false';?>,
							// "titleColor": "<?php //echo (isset($_POST['axes_color']))?$_POST['axes_color']:'#000000';?>",
							// "titleFontSize": "<?php //echo (isset($_POST['axes_font_size']))?$_POST['axes_font_size']:'14';?>",
							// <?php //if (isset($_POST['axes_rotation']) and !empty($_POST['axes_rotation'])) {?>
							// "titleRotation": "<?php //echo $_POST['axes_rotation']?>",
							// <?php //} ?>
							// "stackType": "<?php //echo stackType();?>",
							// <?php //if ( ($_GET['gid']=='col' and $_GET['cid']==15) || ($_GET['gid']=='bar' and $_GET['cid']==14) ) { ?>
							// "logarithmic": true,
							// <?php //} ?>
							
							// <?php //if (isset($_POST['hgridAlpha']) and $_POST['hgridAlpha']==1) {?>
							// "gridAlpha": <?php //echo (isset($_POST['ghl_transparency']))?$_POST['ghl_transparency']:0;?>,
							// <?php //} ?>
							// <?php //if (isset($_POST['hminorGridEnabled']) and $_POST['hminorGridEnabled']==1) {?>
							// "minorGridEnabled": true, // only works with "parseDates": true // but it change the axis
							// <?php //if (isset($_POST['ghl_mtransparency'])) {?>
							// //"minorGridEnabled": <?php //echo $_POST['ghl_mtransparency'];?>,
							// <?php //} ?>
							// <?php //} ?>
							
							// //"minorGridEnabled": <?php //echo (isset($_POST['hminorGridEnabled']))?$_POST['hminorGridEnabled']:($pcount==0?1:0);?>,
							
							
							// "gridColor": "<?php //echo (isset($_POST['ghl_color']))?$_POST['ghl_color']:'#000000';?>",
							
							// <?php //if (isset($_POST['hgridAlpha']) and $_POST['hgridAlpha']==1) { ?>
							// "gridThickness": 1,
							// <?php //if (isset($_POST['ghl_width']) and $_POST['ghl_width']>1) { ?>
							// "gridThickness": "<?php //echo $_POST['ghl_width']?>",
							// <?php //}
							// //} else { ?>
							// "gridThickness": 0,
							// <?php //} ?>
							
							// /*"gridThickness": "<?php //echo (isset($_POST['ghl_width']))?$_POST['ghl_width']:0;?>",*/
							// "dashLength": "<?php //echo (isset($_POST['ghl_dlength']))?$_POST['ghl_dlength']:'1';?>",
							// //gridAlpha
							// //"minorGridAlpha": "<?php //echo (isset($_POST['ghl_color']))?$_POST['ghl_color']:'#000000';?>",
							// "fillColor": "<?php //echo (isset($_POST['ghf_color']))?$_POST['ghf_color']:'#ffffff';?>",
							// ////--conflict with vertical lines 
							// ////"fillAlpha": "<?php //echo (isset($_POST['ghf_transparency']))?$_POST['ghf_transparency']:0;?>",
							// //"fillAlpha": 1,
							
							// <?php
							// //} // end of else
							// ?>
							
						// },
						
						<?php if ( ($_GET['gid']=='col' and $_GET['cid']==13) || ($_GET['gid']=='bar' and $_GET['cid']==11) ) {?>
						{
							"id": "ValueAxis-2",
							"position": "right",
							/*"gridAlpha": 0,*/
							"title": "Axis title"
						},
						<?php } ?>
						
						<?php
						
							echo $primary_vertical_axes;
							
							//for third axes
							echo $value_axes;
							//echo "111";
							echo $secondary_vertical_axes;
						?>
						
					],
					"allLabels": [],
					"balloon": {},
					<?php if ($_GET['gid']=='pie') {?>
					/*
					"legend": {
						"enabled": true,
						"align": "center",
						"markerType": "circle"
					},
					*/
					<?php } else if ($_GET['gid']!='other') {?>
					/*
					"legend": {
						"enabled": true,
						"useGraphSettings": true
					},
					*/
					<?php } ?>
					
					<?php 
					$set_title = isset($_POST['set_title'])?$_POST['set_title']:true;
					
					if ($set_title) {?> 
					
					"titles": [
						{
							"bold": <?php echo (isset($_POST['title_bold']))?"true":"false";?>,
							"color": "<?php echo (isset($_POST['title_color']))?$_POST['title_color']:'#000000';?>",						
							"id": "Title-1",
							"size": <?php echo (isset($_POST['chart_font_size']))?$_POST['chart_font_size']:"15";?>,							
							"text": "<?php echo (isset($_POST['title_text']))?$_POST['title_text']:'Chart Title';?>",
							"alpha": <?php echo (isset($_POST['transparency']))?$_POST['transparency']:"0.4";?>,
						}
					],
					
					<?php } ?>
					
					
					// "titles": [{
						// "color": "<?php echo (isset($_POST['title_color']))?$_POST['title_color']:'#000000';?>",
						// "text": "<?php echo (isset($_POST['title_text']))?$_POST['title_text']:'Chart Title';?>",
						// "id": "Title-1",
						// "size": <?php echo (isset($_POST['chart_title_size']))?$_POST['chart_title_size']:"15";?>,						
					// }],
					
					<?php include_once("data_provider.php");?>
					
					
					/*
					"dataProvider": [
						{
							"category": "2014-03-01",
							"column_1": 8
						},
						{
							"category": "2014-03-02",
							"column_1": 16
						},
						{
							"category": "2014-03-03",
							"column_1": 2
						},
						{
							"category": "2014-03-04",
							"column_1": 7
						},
						{
							"category": "2014-03-05",
							"column_1": 5
						},
						{
							"category": "2014-03-06",
							"column_1": 9
						},
						{
							"category": "2014-03-07",
							"column_1": 4
						},
						{
							"category": "2014-03-08",
							"column_1": 15
						},
						{
							"category": "2014-03-09",
							"column_1": 12
						},
						{
							"category": "2014-03-10",
							"column_1": 17
						},
						{
							"category": "2014-03-11",
							"column_1": 18
						},
						{
							"category": "2014-03-12",
							"column_1": 21
						},
						{
							"category": "2014-03-13",
							"column_1": 24
						},
						{
							"category": "2014-03-14",
							"column_1": 23
						},
						{
							"category": "2014-03-15",
							"column_1": 24
						}
					]
					*/
					
				}
			);
			
			
			
			
			/*
			AmCharts.makeChart("chartdiv",
				{
					"type": "serial",
					"categoryField": "category",
					"startDuration": 1,
					"categoryAxis": {
						"gridPosition": "start"
					},
					"trendLines": [],
					"graphs": [
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
							"valueField": "column-2"
						}
					],
					"guides": [],
					"valueAxes": [
						{
							"id": "ValueAxis-1",
							"title": "Axis title"
						}
					],
					"allLabels": [],
					"balloon": {},
					"legend": {
						"enabled": true,
						"useGraphSettings": true
					},
					"titles": [
						{
							"id": "Title-1",
							"size": 15,
							"text": "Chart Title"
						}
					],
					"dataProvider": [
						{
							"category": "category 1",
							"column-1": 8,
							"column-2": 5
						},
						{
							"category": "category 2",
							"column-1": 6,
							"column-2": 7
						},
						{
							"category": "category 3",
							"column-1": 2,
							"column-2": 3
						}
					]
				}
			);
			*/
			
			/*
	AmCharts.makeChart("chartdiv",
			{
	"type": "serial",
	"categoryField": "category",
	"startDuration": 1,
	"categoryAxis": {
		"gridPosition": "start",
		"parseDates": true,
		"minorGridAlpha": 0.1,
		"minorGridEnabled": true
	},
	"chartCursor": {
		"enabled": true
	},
	"chartScrollbar": {
		"enabled": true
	},
	"trendLines": [],
	"graphs": [
		{
			"fillAlphas": 1,
			"id": "AmGraph-1",
			"title": "graph 1",
			"type": "column",
			"valueField": "column-1"
		}
	],
	"guides": [],
	"valueAxes": [
		{
			"id": "ValueAxis-1",
			"title": "Axis title"
		}
	],
	"allLabels": [],
	"balloon": {},
	"titles": [
		{
			"id": "Title-1",
			"size": 15,
			"text": "Chart Title"
		}
	],
	"dataProvider": [
		{
			"category": "2014-03-01",
			"column-1": 8
		},
		{
			"category": "2014-03-02",
			"column-1": 16
		},
		{
			"category": "2014-03-03",
			"column-1": 2
		},
		{
			"category": "2014-03-04",
			"column-1": 7
		},
		{
			"category": "2014-03-05",
			"column-1": 5
		},
		{
			"category": "2014-03-06",
			"column-1": 9
		},
		{
			"category": "2014-03-07",
			"column-1": 4
		},
		{
			"category": "2014-03-08",
			"column-1": 15
		},
		{
			"category": "2014-03-09",
			"column-1": 12
		},
		{
			"category": "2014-03-10",
			"column-1": 17
		},
		{
			"category": "2014-03-11",
			"column-1": 18
		},
		{
			"category": "2014-03-12",
			"column-1": 21
		},
		{
			"category": "2014-03-13",
			"column-1": 24
		},
		{
			"category": "2014-03-14",
			"column-1": 23
		},
		{
			"category": "2014-03-15",
			"column-1": 24
		}
	]
}

);

*/
			
			
			
			
			<?php 
			if ($_GET['gid']!='pie') {
				echo create_axes_fields();
				echo create_graph_fields();
			}
			?>
			
			/*$(parent.document).find('#data_div_id').html("<?php echo rand(0,99);?>");*/
		</script>
	</head>
	<body>
		<div id="chartdiv" style="width: 100%; height: 350px; 
		<?php if ($bg_color != '#FFFFFF' and $bg_color != '#ffffff') {?>
		background-color: rgba(<?php echo $bg_color;?>, <?php echo $bg_alpha;?>);
		<?php } else { ?>
		background-color: <?php echo $bg_color;?>;
		<?php } ?>
		
		" ></div>
	</body>
</html>
<?php 
function stackType() {
	$stack = "none";
	
	//if ($_GET['gid']=='col' || $_GET['gid']=='bar') {
		if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and ($_GET['cid']==2 || $_GET['cid']==4 || $_GET['cid']==6 || $_GET['cid']==12) ) {
			$stack = "regular";
		} else if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and ($_GET['cid']==3 || $_GET['cid']==7) ) {
			$stack = "100%";
		} else if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and ($_GET['cid']==8) ) {
			$stack = "3d";
		} else if (($_GET['gid']=='line') and ($_GET['cid']==3)) {
			$stack = "100%";
		} else if (($_GET['gid']=='area') and ($_GET['cid']==2)) {
			$stack = "regular";
		} else if (($_GET['gid']=='area') and ($_GET['cid']==3)) {
			$stack = "100%";
		}
	//}
	return $stack; 
}

function create_axes_fields() {
	global $fieldinfo;
	$axes_li = '';
	$return = '';
	
	foreach ($fieldinfo as $key=>$val) {
						
		if ($key==0) {
			continue;
		}
			
		if(strpos($val->name,"_axes") !== false){
			//$graph_settings .= '"bullet": "round",';
			//$axes_li = '<li role="presentation" id="li_axestab2"><a href="#axestab" aria-controls="profile" role="tab" data-toggle="tab"><i class="am pull-left"></i> <span>Value Axes 2</span></a></li>';
			$axes_li = '<li role="presentation" id="li_axestab2">
							<a href="#axestab2" aria-controls="profile" role="tab" data-toggle="tab">
								<i class="am pull-left"></i> <span>Value Axes 2</span>
							</a>
						</li>';
						
			$axes_fileds = '<div role="tabpanel" class="tab-pane fade" id="axestab2">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Axis title offset:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="axisTitleOffset2" id="axisTitleOffset2" value="10" >
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Position:</label>
						<div class="col-sm-8">
							<select name="axes_position2" class="form-control">
							<option value="right">right</option>
							   <option value="left">left</option>							   
							   <option value="top">top</option>
							   <option value="bottom">bottom</option>
							</select>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="axes_title2" id="axes_title2" value="Right Axes Title">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Title bold:</label>
						<div class="col-sm-8">
							<div class="checkbox">
							  <label><input type="checkbox" name="axes_bold2" id="axes_bold2" value="1"></label>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_family">Title Color:</label>
						<div class="col-sm-8">
							<div id="cp-component--" class="input-group my_color_picker">
								<input type="text" class="form-control" name="axes_color2" id="axes_color2" >
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title font size:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="axes_font_size2" id="axes_font_size2" value="14">
						</div>
					  </div>
					  <div class="form-group">
						<label class="control-label col-sm-4" for="font_size">Title rotation:</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="axes_rotation2" id="axes_rotation2" value="-90">
						</div>
					  </div>
					
				</div>';
		}		
		
	}
	
	if (strlen($axes_li)>1) {
		$axes_li = json_encode($axes_li);
		$axes_fileds = json_encode($axes_fileds);
		
		//$return .= "$(parent.document).find('#li_axestab2').remove();";
		$return .= ' if( $(parent.document).find("#li_axestab2").length == 0 ) { $(parent.document).find("#li_axestab1").after( '.$axes_li.' ); } ';
		//add fields
		//$return .= "$(parent.document).find('#axestab2').remove();";
		$return .= ' if( $(parent.document).find("#axestab2").length == 0 ) { $(parent.document).find("#axestab").after( '.$axes_fileds.' ); } ';
		
	} else {
		$return .= "$(parent.document).find('#li_axestab2').remove();";
		$return .= "$(parent.document).find('#axestab2').remove();";
	}
	
	return $return;
}

function create_graph_fields() {
	global $fieldinfo;
	$graph_li = '';
	$return = '';
	
	$fieldinfo_rev = array_reverse($fieldinfo,true);
	
	foreach ($fieldinfo_rev as $key=>$val) {
		
		
		if ($key==0) {
			continue;
		}

			
		//if(strpos($val->name,"_axes") !== false){
			//$graph_settings .= '"bullet": "round",';
			//$axes_li = '<li role="presentation" id="li_axestab2"><a href="#axestab" aria-controls="profile" role="tab" data-toggle="tab"><i class="am pull-left"></i> <span>Value Axes 2</span></a></li>';
			
			$graph_li = '<li role="presentation" id="li_graphtab'.$key.'">
							<a href="#graphtab'.$key.'" aria-controls="profile" role="tab" data-toggle="tab">
								<i class="am pull-left"></i> <span>Graph '.$key.'</span>
							</a>
						</li>';
						
			$graph_fileds = '<div role="tabpanel" class="tab-pane fade" id="graphtab'.$key.'">
					<div class="form-group">
						<label class="control-label col-sm-4" for="text_color">Title:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="gTitle'.$key.'" id="gTitle'.$key.'" value="Graph '.$key.'" >
						</div>
					  </div>
					  
					
				</div>';
		//}		
		
	//} // end of for loop
	
	if (strlen($graph_li)>1) {
		$graph_li = json_encode($graph_li);
		$graph_fileds = json_encode($graph_fileds);
		
		
		//$return .= '$(parent.document).find("#li_cat_axes").after( '.$graph_li.' );' ;
		//cataxestab
		
		
		
		//$return .= "$(parent.document).find('#li_axestab2').remove();";
		$return .= ' if( $(parent.document).find("#li_graphtab'.$key.'").length == 0 ) { $(parent.document).find("#li_cat_axes").after( '.$graph_li.' ); } ';
		//add fields
		//$return .= "$(parent.document).find('#axestab2').remove();";
		$return .= ' if( $(parent.document).find("#graphtab'.$key.'").length == 0 ) { $(parent.document).find("#cataxestab").after( '.$graph_fileds.' ); } ';
		
	} else {
		//$return .= "$(parent.document).find('#li_axestab2').remove();";
		//$return .= "$(parent.document).find('#axestab2').remove();";
	}
	
} // end of for loop for testing
	
	return $return;
}
?>