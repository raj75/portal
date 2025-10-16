<?php if (strlen($db_data_provider) > 1) { ?>
	/*"dataProvider": [<?php //echo $db_data_provider;?>]*/
	"dataProvider": <?php echo $db_data_provider_json ?> 
					
<?php } else { ?>


<?php if ($_GET['gid']=='col' and $_GET['cid']==10) { ?>
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
					<?php } else if ($_GET['gid']=='col' and $_GET['cid']==11) { ?>
					
					"dataProvider": [
						{
							"category": "category 1",
							"column-1": 8
						},
						{
							"category": "category 2",
							"column-1": 16
						},
						{
							"category": "category 3",
							"column-1": 2
						},
						{
							"category": "category 4",
							"column-1": 7
						},
						{
							"category": "category 5",
							"column-1": 5
						},
						{
							"category": "category 6",
							"column-1": 9
						},
						{
							"category": "category 7",
							"column-1": 4
						},
						{
							"category": "category 8",
							"column-1": 15
						},
						{
							"category": "category 9",
							"column-1": 12
						},
						{
							"category": "category 10",
							"column-1": 17
						},
						{
							"category": "category 11",
							"column-1": 18
						},
						{
							"category": "category 12",
							"column-1": 21
						},
						{
							"category": "category 13",
							"column-1": 24
						},
						{
							"category": "category 14",
							"column-1": 23
						},
						{
							"category": "category 15",
							"column-1": 24
						}
					]
					<?php } else if ( ($_GET['gid']=='col' || $_GET['gid']=='bar') and $_GET['cid']==12) { ?>
					"dataProvider": [
						{
							"category": "category 1",
							"open": 2,
							"close": 5
						},
						{
							"category": "category 2",
							"open": 4,
							"close": 7
						},
						{
							"category": "category 3",
							"open": 3,
							"close": 6
						},
						{
							"category": "category 3",
							"open": 5,
							"close": 8
						}
					]
					
					<?php } else if ( ($_GET['gid']=='col' and $_GET['cid']==13) || ($_GET['gid']=='bar' and $_GET['cid']==11) ) {?>
					
					"dataProvider": [
						{
							"category": "category 1",
							"column-1": "21",
							"column-2": "80"
						},
						{
							"category": "category 2",
							"column-1": "11",
							"column-2": "756"
						},
						{
							"category": "category 3",
							"column-1": "15",
							"column-2": "1008"
						}
					]	
					
					<?php } else if ( $_GET['gid']=='col' and $_GET['cid']==14 ) { ?>
					
					"dataProvider": [
						{
							"category": "category 1",
							"column-1": 8
						},
						{
							"category": "category 2",
							"column-1": 16
						},
						{
							"category": "category 3",
							"column-1": 2
						},
						{
							"category": "category 4",
							"column-1": 7
						},
						{
							"category": "category 5",
							"column-1": 5
						},
						{
							"category": "category 6",
							"column-1": 9
						},
						{
							"category": "category 7",
							"column-1": 4
						},
						{
							"category": "category 8",
							"color": "#d0c398",
							"column-1": 15
						}
					]
					
					<?php } else if ( ($_GET['gid']=='col' and $_GET['cid']==15) || ($_GET['gid']=='bar' and $_GET['cid']==14) ) { ?>
					
					"dataProvider": [
						{
							"category": "category 1",
							"graph 1": 8
						},
						{
							"category": "category 2",
							"graph 1": "160"
						},
						{
							"category": "category 3",
							"graph 1": "989"
						},
						{
							"category": "category 4",
							"graph 1": "1560"
						},
						{
							"category": "category 5",
							"graph 1": "5006"
						},
						{
							"category": "category 6",
							"graph 1": "9012"
						},
						{
							"category": "category 7",
							"graph 1": "40124"
						},
						{
							"category": "category 8",
							"graph 1": "150200"
						}
					]
					
					<?php } else if ( $_GET['gid']=='bar' and $_GET['cid']==13 ) {?>
					"dataProvider": [
						{
							"category": "category 1",
							"column-1": 8
						},
						{
							"category": "category 2",
							"column-1": 16
						},
						{
							"category": "category 3",
							"column-1": 2
						},
						{
							"category": "category 4",
							"column-1": 7
						},
						{
							"category": "category 5",
							"column-1": 5
						},
						{
							"category": "category 6",
							"column-1": 9
						},
						{
							"category": "category 7",
							"column-1": 4
						},
						{
							"category": "category 8",
							"column-1": 15,
							"dash length": 5,
							"fill alpha": 0.2
						}
					]
					
					<?php } else if ( $_GET['gid']=='bar' and $_GET['cid']==15 ) {?>
					
					"dataProvider": [
						{
							"category": "category 1",
							"graph 1": 8
						},
						{
							"category": "category 2",
							"graph 1": 5
						},
						{
							"category": "category 3",
							"graph 1": 11
						},
						{
							"category": "category 4",
							"graph 1": 6
						},
						{
							"category": "category 5",
							"graph 1": 9
						}
					]
					
					<?php } else if ($_GET['gid']=='col' || $_GET['gid']=='bar') { ?>
					
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
					<?php } ?>
					
					//----line data start---------
					
					<?php if ( $_GET['gid']=='line' and ($_GET['cid']==1 || $_GET['cid']==2 || $_GET['cid']==3 || $_GET['cid']==4 || $_GET['cid']==11 || $_GET['cid']==12 || $_GET['cid']==13) ) {?>
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
						},
						{
							"category": "category 4",
							"column-1": 1,
							"column-2": 3
						},
						{
							"category": "category 5",
							"column-1": 2,
							"column-2": 1
						},
						{
							"category": "category 6",
							"column-1": 3,
							"column-2": 2
						},
						{
							"category": "category 7",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==5 ) {?>
					"dataProvider": [
						{
							"date": "2014-03-01",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2014-03-02",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2014-03-03",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2014-03-04",
							"column-1": 1,
							"column-2": 3
						},
						{
							"date": "2014-03-05",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2014-03-06",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2014-03-07",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==6 ) {?>
					"dataProvider": [
						{
							"date": "2014-01",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2014-02",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2014-03",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2014-04",
							"column-1": 1,
							"column-2": 3
						},
						{
							"date": "2014-05",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2014-06",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2014-07",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==7 ) {?>
					"dataProvider": [
						{
							"date": "2001",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2002",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2003",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2004",
							"column-1": 4,
							"column-2": 3
						},
						{
							"date": "2005",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2006",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2007",
							"column-1": 4,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==8 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 08"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 09"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 10"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 11"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 12"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 13"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 14"
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==9 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 07:57"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 07:58"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 07:59"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 08:00"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 08:01"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 08:02"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 08:03"
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='line' and $_GET['cid']==10 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 07:57:57"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 07:57:58"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 07:57:59"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 07:58:00"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 07:58:01"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 07:58:02"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 07:58:03"
						}
					]
					<?php } ?>
					
					//----area data start---------
					
					<?php if ( $_GET['gid']=='area' and ($_GET['cid']==1 || $_GET['cid']==2 || $_GET['cid']==3 || $_GET['cid']==4) ) {?>
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
						},
						{
							"category": "category 4",
							"column-1": 1,
							"column-2": 3
						},
						{
							"category": "category 5",
							"column-1": 2,
							"column-2": 1
						},
						{
							"category": "category 6",
							"column-1": 3,
							"column-2": 2
						},
						{
							"category": "category 7",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==5 ) {?>
					"dataProvider": [
						{
							"date": "2014-03-01",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2014-03-02",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2014-03-03",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2014-03-04",
							"column-1": 1,
							"column-2": 3
						},
						{
							"date": "2014-03-05",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2014-03-06",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2014-03-07",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==6 ) {?>
					"dataProvider": [
						{
							"date": "2014-01",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2014-02",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2014-03",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2014-04",
							"column-1": 1,
							"column-2": 3
						},
						{
							"date": "2014-05",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2014-06",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2014-07",
							"column-1": 6,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==7 ) {?>
					"dataProvider": [
						{
							"date": "2001",
							"column-1": 8,
							"column-2": 5
						},
						{
							"date": "2002",
							"column-1": 6,
							"column-2": 7
						},
						{
							"date": "2003",
							"column-1": 2,
							"column-2": 3
						},
						{
							"date": "2004",
							"column-1": 4,
							"column-2": 3
						},
						{
							"date": "2005",
							"column-1": 2,
							"column-2": 1
						},
						{
							"date": "2006",
							"column-1": 3,
							"column-2": 2
						},
						{
							"date": "2007",
							"column-1": 4,
							"column-2": 8
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==8 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 08"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 09"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 10"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 11"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 12"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 13"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 14"
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==9 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 07:57"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 07:58"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 07:59"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 08:00"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 08:01"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 08:02"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 08:03"
						}
					]
					<?php } ?>
					
					<?php if ( $_GET['gid']=='area' and $_GET['cid']==10 ) {?>
					"dataProvider": [
						{
							"column-1": 8,
							"column-2": 5,
							"date": "2014-03-01 07:57:57"
						},
						{
							"column-1": 6,
							"column-2": 7,
							"date": "2014-03-01 07:57:58"
						},
						{
							"column-1": 2,
							"column-2": 3,
							"date": "2014-03-01 07:57:59"
						},
						{
							"column-1": 1,
							"column-2": 3,
							"date": "2014-03-01 07:58:00"
						},
						{
							"column-1": 2,
							"column-2": 1,
							"date": "2014-03-01 07:58:01"
						},
						{
							"column-1": 3,
							"column-2": 2,
							"date": "2014-03-01 07:58:02"
						},
						{
							"column-1": 6,
							"column-2": 8,
							"date": "2014-03-01 07:58:03"
						}
					]
					<?php } ?>

<?php } // end of else chart query ?>