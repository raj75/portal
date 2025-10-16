$(document).ready(function(){
	$( document ).on( "keypress", ".ui-chatbox-input-box", function(event) {
		if (event.keyCode == 13) {
			var $ctThis=$(this);
			var ctid=$ctThis.attr('data-ctid');
			var ctText=$ctThis.val();
			passMessage(ctid,ctText);
			$('#cha'+ctid).animate({ scrollTop: $('#cha'+ctid)[0].scrollHeight }, 1);
			$ctThis.val("");
			return false;
		}
		//return false;
	});

	$( document ).on( "click", ".ui-chatbox-icon", function(event) {
		var $ctThis=$(this);
		var ctid=$ctThis.attr('data-ctool-id');
		var ctype=$ctThis.attr('data-original-title');
		if(ctype=="Hide")
		{
			$( "#script"+ctid ).remove();
			$( "#custom-chatbox"+ctid ).remove();
			if($('.custom-chatbox').length > 0)
			{
				$( '.custom-chatbox').each(function( i, val ) {
				  var $tempthis=$(this);
				  $tempthis.css("right",(235*i));
				});
				return false;
			}

			$.ajax({
				url: 'assets/includes/chat.inc.php',
				type: 'POST',
				data: {sDestroy:true,uname:ctid},
				//async: false,
				success: function (data) {
				  if(data == false){
					alert("Error");
				  }
				},
				cache: false,
			});
		}else if(ctype=="Minimize")
		{
			$( "#innerContent"+ctid ).toggle();
		}
	});
});

function chatWith(uid)
{
	if(uid==0 || uid == "")
		return false;

	createChatPOPUP(uid);
	return false;
}

function createChatPOPUP(uid)
{
	if($('#custom-chatbox'+uid).length > 0)
	{
		$('#custom-chatbox'+uid).effect("pulsate");
		return false;
	}

	var shiftRight = ($('.custom-chatbox').length * 235);
	//Get User Name
	$.ajax({
		url: 'assets/includes/chat.inc.php',
		type: 'POST',
		data: {uname:uid},
		//async: false,
		success: function (data) {
		  if(data != false)
		  {
			var result = JSON.parse(data);
			if(result.error == false)
			{
				var temp_message="";
				if(result.chat_history.length>0)
				{
					for(j=0;j<result.chat_history.length;j++)
					{
						temp_message=temp_message+'<div style="display: block; max-width: 208px;" class="ui-chatbox-msg"><b>'+result.chat_history[j].bName+': </b><span>'+result.chat_history[j].message+'</span></div>';
					}
				}


				$("body").append('<div class="ui-widget ui-chatbox custom-chatbox" outline="0" style="width: 228px; right: '+shiftRight+'px;" data-cid="'+uid+'" id="custom-chatbox'+uid+'"><div class="ui-widget-header ui-chatbox-titlebar online ui-dialog-header"><span class="uname"><i title="online"></i>'+result.uname+'</span><span class="cname">'+result.cname+'</span><a data-original-title="Hide" data-ctool-id="'+uid+'" data-placement="top" rel="tooltip" href="javascript:void(0);" class="ui-chatbox-icon" role="button"><i class="fa fa-times"></i></a><a data-original-title="Minimize" data-placement="top" data-ctool-id="'+uid+'" rel="tooltip" href="javascript:void(0);" class="ui-chatbox-icon" role="button"><i class="fa fa-minus"></i></a></div><div class="false ui-widget-content ui-chatbox-content" id="innerContent'+uid+'"><span class="alert-msg"></span><div id="cha'+uid+'" class="ui-widget-content ui-chatbox-log custom-scroll">'+temp_message+'</div><div class="ui-widget-content ui-chatbox-input"><textarea class="ui-widget-content ui-chatbox-input-box" style="width: 218px;" data-ctid="'+uid+'"></textarea></div></div></div><script id="script'+uid+'">syncMessage();function syncMessage(){var temp_status=false;$.ajax({url: "assets/includes/chat.inc.php",type: "POST",data: {uname:'+uid+',getMessage:true},/*async: false,*/success: function (datas) {if(datas != false){var results = JSON.parse(datas);if(results.error == false){for(i=0;i<results.getMessage.length;i++){$("#cha'+uid+'").append(\'<div style="display: block; max-width: 208px;" class="ui-chatbox-msg"><b>\'+results.getMessage[i].userone+\': </b><span>\'+results.getMessage[i].message+\'</span></div>\');$(\'#cha'+uid+'\').animate({ scrollTop: $(\'#cha'+uid+'\')[0].scrollHeight }, 1);if($( "#innerContent'+uid+'").css("display")=="none"){$("#custom-chatbox'+uid+'").effect("pulsate");}}temp_status=true;if(results.getMessage.length > 0){var ootitle=document.title;document.title ="●"+ootitle.replace("●", "");}}else{alert(results.error);}}else{}},cache: false,});if(temp_status == true){setTimeout(syncMessage, 2000);}}</script>');

				$('#cha'+uid).animate({ scrollTop: $('#cha'+uid)[0].scrollHeight }, 1);
			}else{
				alert(result.error);
			}
		  }else{
			alert("Error");
		  }
		},
		cache: false,
	});
}

function passMessage(uid,uText)
{
	if(uid == "" || uid == 0 || uText == "")
		return false;

	$.ajax({
		url: 'assets/includes/chat.inc.php',
		type: 'POST',
		data: {uid:uid,uText:uText},
		//async: false,
		success: function (data) {
		  if(data != false)
		  {
			var result = JSON.parse(data);
			if(result.error == false)
			{
				$("#cha"+uid).append('<div style="display: block; max-width: 208px;" class="ui-chatbox-msg"><b>Me: </b><span>'+result.uText+'</span></div>');
			}else{
				alert(result.error);
			}
		  }else{
			alert("Error");
		  }
		},
		cache: false,
	});
}
