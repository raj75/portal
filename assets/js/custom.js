function showversion(vid,vtname,vfname,cid,tid,urlss)
{
	if(vid=="" || vid == 0 || vtname=="" || vfname=="" || cid=="")
	{return "Error";}

	$.ajax({
		type: 'post',
		url: 'assets/includes/version.inc.php',
		data: {vid:vid,vtname:vtname,vfname:vfname,action:'showversion',tuid:tid,tuurl:urlss,ct:Math.random()},
		success: function (result) {
			if (result != false)
			{
				var results = JSON.parse(result);
				if(results.version != "")
				{
					$('#p'+cid+'').attr("data-content",results.version.replace("\/","/"));
				}
			}
			$('#p'+cid+'').trigger('click');
		}
	  });
}

function rollback_audit_log(aid,actions,tid,urlss) {
	if(aid=="" || actions=="" || tid== "" || urlss==""){alert("Error Occured");return false;}

	var r = confirm("Are you sure want to "+actions+"?");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/auditlog.inc.php',
			data: {auid:aid,action:actions,tuid:tid,tuurl:urlss},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						if(tid != "" && urlss != ""){
							parent.$("#"+tid).html("");
							parent.$("#"+tid).load(urlss);
						}
					}else
						alert(results.error);
				}else{
					alert(results.error);
				}
			}
		  });
	}
}

function rollback_audit_log_new(aid,actions,tid,urlss) {
	if(aid=="" || actions=="" || tid== "" || urlss==""){alert("Error Occured");return false;}

	var r = confirm("Are you sure want to "+actions+"?");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/includes/auditlog.inc.php',
			data: {auid:aid,action:actions,tuid:tid,'rtype':'new'},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
						if(tid != "" && urlss != ""){
							parent.$("#"+tid).html("");
							parent.$("#"+tid).load(urlss);
						}
					}else
						alert(results.error);
				}else{
					alert(results.error);
				}
			}
		  });
	}
}


$( ".reqactivate" ).click(function() {
  var menutitle = $( this ).attr("menutitle");
  var r = confirm("Do you want send \""+menutitle+"\" activation request?");
	if (r == true) {
		$.ajax({
			type: 'post',
			url: 'assets/php/subscribe.php',
			data: {action:"reqactivate",menuname:menutitle},
			success: function (result) {
				if (result != false)
				{
					var results = JSON.parse(result);
					if(results.error == "")
					{
						alert("Success");
					}else
						alert(results.error);
				}else{
					alert(results.error);
				}
			}
		  });
	}
});

function generatePassword(passwordLength) {
	var numberChars = "0123456789";
	var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var lowerChars = "abcdefghiklmnopqrstuvwxyz";
  //var specialChars = "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~";
	var specialChars = "#%&*+-";
	var allChars = numberChars + upperChars + lowerChars+specialChars;
	var randPasswordArray = Array(passwordLength);
  randPasswordArray[0] = numberChars;
  randPasswordArray[1] = upperChars;
  randPasswordArray[2] = lowerChars;
  randPasswordArray[3] = specialChars;
  randPasswordArray = randPasswordArray.fill(allChars, 4);
  return shuffleArray(randPasswordArray.map(function(x) { return x[Math.floor(Math.random() * x.length)] })).join('');
}

function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}

$( document ).ready(function() {
	var tz = jstz.determine();
	$('#showtimezone').text(tz.name());

     $(".switchcurrency").each(function() {
       var cz=$(this).text();//alert(cz);
	   $(this).text((cz/0.9)+" EUR");
     });


//$('#jstimezone').text(Intl.DateTimeFormat().resolvedOptions().timeZone);

});

function set_sound_ar(varset) {
  //$.sound_on = true;
  $.sound_on = varset;
  //alert($.sound_on);
}