<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<style type="text/css">
		.feed-container{overflow:hidden;margin:0;padding:0;height:648px;}
		.error-list { font-size: 10px;list-style-position: inside; padding: 0;margin: 10px;}
		.error-list ul{ }
		.error-list li{color:#666;}
		.feed-container .description p{margin:0 !important;padding:0 !important;}
	</style>
	<link media="screen" type="text/css" rel="stylesheet" href="https://portal.vervantis.com/assets/css/streaming.css?743">
	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<script type="text/javascript">
function HideLastElem() {
    var a = $(document).height() - $("#feed-header").outerHeight(true);
    var b = 0;
    $(".item-list .item").each(function() {
        b = b + $(this).outerHeight(true);
        if (a < b) {
            $(this).hide()
        }
    })
}

function initScrollBar() {
    var a = $(document).height() - $("#feed-header").outerHeight(true) - $(".footer").outerHeight(true);
    $("#feed-body").css({
        overflowY: "scroll",
        height: a + "px"
    })
}

function initAutoScroll(a) {
    if ($(".item-list .item").length > 0) {
        slide_time = a;
        var b = $(document).height() - $("#feed-header").outerHeight(true) - $(".footer").outerHeight(true);
        $("#feed-body").css({
            height: b + "px",
            overflow: "hidden"
        });
        $(".item-list").css({
            marginTop: -1 * $(".item-list").outerHeight(true) - 1
        });
        $(".item-list").append($(".item-list").html());
        is_auto_slider = true;
        SlideTimer = setInterval(function() {
            doAutoScroll()
        }, a * 1e3)
    }
}

function doAutoScroll() {
    var a = $(".item-list .item:eq(" + topElem + ")").outerHeight(true);
    $(".item-list").animate({
        marginTop: "-=" + a + "px"
    }, 500, function() {
        var a = $(".item-list .item:eq(0)").outerHeight(true);
        var b = parseInt($(".item-list").css("marginTop"));
        $(".item-list").css({
            marginTop: b + a + "px"
        });
        $(".item-list").append($(".item-list .item:eq(0)"))
    })
}

function CalculateContentHeight() {
    var a = $(document).height() - $("#feed-header").outerHeight(true) - $(".footer").outerHeight(true);
    $("#feed-body").css({
        height: a + "px"
    })
}
var topElem = 0;
var SlideTimer = null;
var is_auto_slider = false;
var slide_time = 0;

$(document).ready(function() {
    $("body").hover(function() {
        if (is_auto_slider) {
            clearInterval(SlideTimer)
        }
    }, function() {
        if (is_auto_slider) {
            SlideTimer = setInterval(function() {
                doAutoScroll()
            }, slide_time * 1e3)
        }
    })
});	
	
	</script>
	<script type="text/javascript">
		function InitFeed(){initAutoScroll(4);}
	</script>
</head>

<body onload="InitFeed();">
	<div class="feed-container">
		<div class="content" id="feed-body">
			<div class="item-list">
<?php
	$xml=("http://www.rssmix.com/u/8306210/rss.xml");
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($xml);

	$x=$xmlDoc->getElementsByTagName('item');
	foreach ($x as $ky=>$vl) {
	  $item_title=$x->item($ky)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
	  $item_link=$x->item($ky)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
	  if(!empty($x->item($ky)->getElementsByTagName('description')) and !empty($x->item($ky)->getElementsByTagName('description')->item(0)->childNodes->item(0))){
	  $item_desc=$x->item($ky)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
	  }else $item_desc="";
	  $item_guid=$x->item($ky)->getElementsByTagName('guid')->item(0)->childNodes->item(0)->nodeValue;

	  if(preg_match("/feedproxy/s",$item_link,$nosave)) $item_link=$item_guid;
	  if(!preg_match("/http[s]{0,1}\:\/\//s",$item_link,$nosave)) $item_link="https://www.eia.gov".$item_link;

	  echo '<div class="item">';
	  echo '<div class="title"> <a href="'.$item_link.'" target="_blank">'.mb_strimwidth($item_title, 0, 70, '...').'</a> </div>';	  
	  echo '<div class="description">'.mb_strimwidth($item_desc, 0, 120, '...').'</div>';	  
	  echo '</div>';
	}

?>
			</div>
		</div>
	</div>
</body>

</html>