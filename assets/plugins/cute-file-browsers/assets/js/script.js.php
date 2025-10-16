<?php
/*if(isset($_COOKIE["docname"]) and ($_COOKIE["docname"] == "Energy Procurement Solutions" OR $_COOKIE["docname"] == "Energy Accounting" OR $_COOKIE["docname"] == "Data Management" OR $_COOKIE["docname"] == "Sustainability" OR $_COOKIE["docname"] == "Market Resources" OR $_COOKIE["docname"] == "Projects"))
{
	$docname=$_COOKIE["docname"];
}*/

if(isset($_GET["docname"]) 
	and (
	$_GET["docname"] == "Energy Procurement" 
	OR $_GET["docname"] == "Energy Accounting" 
	OR $_GET["docname"] == "Data Management" 
	OR $_GET["docname"] == "Sustainability"
	OR $_GET["docname"] == "Master Supply Agreements"
	OR $_GET["docname"] == "Projects"
	OR $_GET["docname"] == "Rate Optimization"
	OR $_GET["docname"] == "Rate Optimization:Regulated Information"
	OR $_GET["docname"] == "Rate Optimization:Utility Rate Reports"
	OR $_GET["docname"] == "Rate Optimization:Utility Rate Change Requests"
	OR $_GET["docname"] == "Energy Procurement:Direct Access Information"
	OR $_GET["docname"] == "Energy Procurement:Strategy" 
	OR $_GET["docname"] == "Energy Procurement:Dynamic Risk Management" 
	OR $_GET["docname"] == "Data Management:Data Analysis" 
	OR $_GET["docname"] == "Data Management:Custom Reports" 
	OR $_GET["docname"] == "Data Management:Consumption Reports" 
	OR $_GET["docname"] == "Energy Accounting:Invoice Validation" 
	OR $_GET["docname"] == "Energy Accounting:Utility Budgets" 
	OR $_GET["docname"] == "Energy Accounting:Exception Reports" 
	OR $_GET["docname"] == "Energy Accounting:Resolved Exceptions" 
	OR $_GET["docname"] == "Energy Accounting:Site and Account Changes" 
	OR $_GET["docname"] == "Sustainability:Sustainability Reports" 
	OR $_GET["docname"] == "Sustainability:Corporate Reports" 
	OR $_GET["docname"] == "Sustainability:Surveys"
	OR $_GET["docname"] == "Projects:Distributed Generation"
	OR $_GET["docname"] == "Projects:Efficiency Upgrades"
	OR $_GET["docname"] == "Projects:EV Charging"
	OR $_GET["docname"] == "Projects:Rebates and Incentives"
	OR $_GET["docname"] == "Projects:Other"
	)
)
{
	$docname=$folder_n=$_GET["docname"];
	if(preg_match("/([^\:]+):([^\:]+)/s",$docname,$tmp_docname))
	{
		array_shift($tmp_docname);
		$folder_n=$tmp_docname[1];
	}
}else
	die("No access");

define("APP_URL", $_GET["docname"]);
?>
<script>
$(function(){

	var filemanager = $('.filemanager'),
		breadcrumbs = $('.breadcrumbs'),
		fileList = filemanager.find('.data');

	// Start by fetching the file data from scan.php with an AJAX request

	$.get('scan.php?cnt=<?php echo rand(0,999); ?>&docname=<?php echo $docname; ?>', function(data) {

		var response = [data],
			currentPath = '',
			breadcrumbsUrls = [];

		var folders = [],
			files = [];

		// This event listener monitors changes on the URL. We use it to
		// capture back/forward navigation in the browser.

		$(window).on('hashchange', function(){

			goto(window.location.hash);

			// We are triggering the event. This will execute 
			// this function on page load, so that we show the correct folder:

		}).trigger('hashchange');


		// Hiding and showing the search box

		filemanager.find('.search').click(function(){

			var search = $(this);

			search.find('span').hide();
			search.find('input[type=search]').show().focus();

		});


		// Listening for keyboard input on the search field.
		// We are using the "input" event which detects cut and paste
		// in addition to keyboard input.

		filemanager.find('input').on('input', function(e){

			folders = [];
			files = [];

			var value = this.value.trim();

			if(value.length) {

				filemanager.addClass('searching');

				// Update the hash on every key stroke
				window.location.hash = 'search=' + value.trim();

			}

			else {

				filemanager.removeClass('searching');
				window.location.hash = encodeURIComponent(currentPath);

			}

		}).on('keyup', function(e){

			// Clicking 'ESC' button triggers focusout and cancels the search

			var search = $(this);

			if(e.keyCode == 27) {

				search.trigger('focusout');

			}

		}).focusout(function(e){

			// Cancel the search

			var search = $(this);

			if(!search.val().trim().length) {

				window.location.hash = encodeURIComponent(currentPath);
				search.hide();
				search.parent().find('span').show();

			}

		});


		// Clicking on folders

		fileList.on('click', 'li.folders', function(e){
			e.preventDefault();

			var nextDir = $(this).find('a.folders').attr('href');

			if(filemanager.hasClass('searching')) {

				// Building the breadcrumbs

				breadcrumbsUrls = generateBreadcrumbs(nextDir);

				filemanager.removeClass('searching');
				filemanager.find('input[type=search]').val('').hide();
				filemanager.find('span').show();
			}
			else {
				breadcrumbsUrls.push(nextDir);
			}

			window.location.hash = encodeURIComponent(nextDir);
			currentPath = nextDir;
		});


		// Clicking on breadcrumbs

		breadcrumbs.on('click', 'a', function(e){
			e.preventDefault();

			var index = breadcrumbs.find('a').index($(this)),
				nextDir = breadcrumbsUrls[index];

			breadcrumbsUrls.length = Number(index);

			window.location.hash = encodeURIComponent(nextDir);

		});


		// Navigates to the given hash (path)

		function goto(hash) {

			hash = decodeURIComponent(hash).slice(1).split('=');

			if (hash.length) {
				var rendered = '';

				// if hash has search in it

				if (hash[0] === 'search') {

					filemanager.addClass('searching');
					rendered = searchData(response, hash[1].toLowerCase());

					if (rendered.length) {
						currentPath = hash[0];
						render(rendered);
					}
					else {
						render(rendered);
					}

				}

				// if hash is some path

				else if (hash[0].trim().length) {

					rendered = searchByPath(hash[0]);

					if (rendered.length) {

						currentPath = hash[0];
						breadcrumbsUrls = generateBreadcrumbs(hash[0]);
						render(rendered);

					}
					else {
						currentPath = hash[0];
						breadcrumbsUrls = generateBreadcrumbs(hash[0]);
						render(rendered);
					}

				}

				// if there is no hash

				else {
					currentPath = data.path;
					breadcrumbsUrls.push(data.path);
					render(searchByPath(data.path));
				}
			}
		}

		// Splits a file path and turns it into clickable breadcrumbs

		function generateBreadcrumbs(nextDir){
			var path = nextDir.split('/').slice(0);
			for(var i=1;i<path.length;i++){
				path[i] = path[i-1]+ '/' +path[i];
			}
			return path;
		}


		// Locates a file by path

		function searchByPath(dir) {
			var path = dir.split('/'),
				demo = response,
				flag = 0;

			for(var i=0;i<path.length;i++){
				for(var j=0;j<demo.length;j++){
					if(demo[j].name === path[i]){
						flag = 1;
						demo = demo[j].items;
						break;
					}
				}
			}

			demo = flag ? demo : [];
			return demo;
		}


		// Recursively search through the file tree

		function searchData(data, searchTerms) {

			data.forEach(function(d){
				if(d.type === 'folder') {

					searchData(d.items,searchTerms);

					if(d.name.toLowerCase().match(searchTerms)) {
						folders.push(d);
					}
				}
				else if(d.type === 'file') {
					if(d.name.toLowerCase().match(searchTerms)) {
						files.push(d);
					}
				}
			});
			return {folders: folders, files: files};
		}


		// Render the HTML for the file manager

		function render(data) {

			var scannedFolders = [],
				scannedFiles = [];

			if(Array.isArray(data)) {

				data.forEach(function (d) {

					if (d.type === 'folder') {
						scannedFolders.push(d);
					}
					else if (d.type === 'file') {
						scannedFiles.push(d);
					}

				});

			}
			else if(typeof data === 'object') {

				scannedFolders = data.folders;
				scannedFiles = data.files;

			}


			// Empty the old result and make the new one

			fileList.empty().hide();

			if(!scannedFolders.length && !scannedFiles.length) {
				filemanager.find('.nothingfound').show();
			}
			else {
				filemanager.find('.nothingfound').hide();
			}

			if(scannedFolders.length) {

				scannedFolders.forEach(function(f) {

					var itemsLength = f.items.length,
						name = escapeHTML(f.name),
						
						icon = '<span class="icon folder"></span>',
						iconpath = 'images/icons/' + f.path + '.png';
					
					
					function imageExists(image_url){
						return false;
						var http = new XMLHttpRequest();

						http.open('HEAD', image_url, false);
						http.send();

						return http.status != 404;

					}
					
					if (imageExists(iconpath)) {
						icon = '<div style="display:inline-block;margin:5px 5px 5px 5px;border-radius:8px;width:100px;height:100px;background-position: center center;background-size: cover; background-repeat:no-repeat;background-image: url(\'' +iconpath + '\');"></div>';
					} else if(itemsLength) {
						//icon = '<span class="icon folder full"></span>';
						icon = '<span class="icon folder full foldersicon"></span>';
					}
					
					

					if(itemsLength == 1) {
						itemsLength += ' item';
					}
					else if(itemsLength > 1) {
						itemsLength += ' items';
					}
					else {
						itemsLength = 'Empty';
					}
					
					/*var folder = $('<li class="folders"><a href="'+ f.path +'" title="'+ f.path +'" class="folders">'+icon+'<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');*/
					var folder = $('<li class="folders"><table width="100%"><tr><td width="20px"><a href="'+ f.path +'" title="'+ f.path +'" class="afoldersicon">'+icon+'</a></td><td width="39%" class="clfname"><a href="'+ f.path +'" title="'+ f.path +'" class="foldersname">'+ name +'</a></td><td width="14%" class="cldetails">' + itemsLength + '</td><td width="14%" class="cldetails">&nbsp;</td><td><i class="fa fa-times-circle red cldelete" title="Delete" onclick="confirmDelete(\''+escape(f.path)+'\');"></i></td></tr></table></li>');
					fileList.append(folder)
					
				});

			}

			if(scannedFiles.length) {

				scannedFiles.forEach(function(f) {

					var fileSize = bytesToSize(f.size),
						name = escapeHTML(f.name),
						fileType = name.split('.'),
						icon = '<span class="file-list"></span>';

					fileType = fileType[fileType.length-1];
					
					if (fileType == "db") {
						return;
					}
					/*var file = $('<li class="files"><a href="javascript:void(0);" title="'+ f.path +'" class="files Popup" onclick="previewPop(\''+escape(f.path)+'\');">'+icon+'<span class="name">'+ name +'</span><span class="name2">'+ fileType +'</span><span class="details">'+fileSize+'</span></a></li>');*/
					var file = $('<li class="files"><table width="100%"><tr><td width="20px" onclick="previewPop(\''+escape(f.path)+'\');">'+icon+'</td><td width="39%" class="clfname" onclick="previewPop(\''+escape(f.path)+'\');">'+ name +'</td><td width="14%" class="cldetails">'+ fileType +'</td><td width="14%" class="cldetails">'+fileSize+'</td><td><i class="fa fa-times-circle red cldelete" title="Delete" onclick="confirmDelete(\''+escape(f.path)+'\');"></i></td></tr></table></li>');
					
/*
					if (fileType == "jpg") {
						icon = '<div style="display:inline-block;margin:20px 30px 0px 25px;border-radius:8px;width:60px;height:70px;background-position: center center;background-size: cover; background-repeat:no-repeat;background-image: url(\'' + f.path + '\');"></div>';
					} else if (fileType == "jpeg") {
						icon = '<div style="display:inline-block;margin:20px 30px 0px 25px;border-radius:8px;width:60px;height:70px;background-position: center center;background-size: cover; background-repeat:no-repeat;background-image: url(\'' + f.path + '\');"></div>';
					} else if (fileType == "png") {
						icon = '<div style="display:inline-block;margin:20px 30px 0px 25px;border-radius:8px;width:60px;height:70px;background-position: center center;background-size: cover; background-repeat:no-repeat;background-image: url(\'' + f.path + '\');"></div>';
					} else if (fileType == "gif") {
						icon = '<div style="display:inline-block;margin:20px 30px 0px 25px;border-radius:8px;width:60px;height:70px;background-position: center center;background-size: cover; background-repeat:no-repeat;background-image: url(\'' + f.path + '\');"></div>';
					} else {
						icon = '<span class="icon file f-'+fileType+'">.'+fileType+'</span>';
					} 
					
					
					if (fileType == "jpg") {
						var file = $('<li class="files"><a data-fancybox="images" href="'+ f.path+'" title="'+ f.path +'" target="_blank" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
					} else if (fileType == "jpeg") {
							var file = $('<li class="files"><a data-fancybox="images" href="'+ f.path+'" title="'+ f.path +'" target="_blank" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');file.appendTo(fileList);
					} else if (fileType == "png") {
							var file = $('<li class="files"><a data-fancybox="images" href="'+ f.path+'" title="'+ f.path +'" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');file.appendTo(fileList);
					} else if (fileType == "gif") {
						var file = $('<li class="files"><a data-fancybox="images" href="'+ f.path+'" title="'+ f.path +'" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');file.appendTo(fileList);
					} else if (fileType == "pdf") {
						var file = $('<li class="files"><a data-fancybox data-type="iframe" data-src="'+ f.path+'" title="'+ f.path +'" href="javascript:;" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
					} else if (fileType == "mp4") {
						var file = $('<li class="files"><a data-fancybox data-type="iframe" data-src="'+ f.path+'" title="'+ f.path +'" href="javascript:;" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
					} else {
						var file = $('<li class="files"><a href="'+ f.path+'" title="'+ f.path +'" target="_blank" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
					}
*/
					file.appendTo(fileList);
				});

			}


			// Generate the breadcrumbs

			var url = '';

			if(filemanager.hasClass('searching')){

				url = '<span>Search results: </span>';
				fileList.removeClass('animated');

			}
			else {

				fileList.addClass('animated');

				breadcrumbsUrls.forEach(function (u, i) {

					var name = u.split('/');

					/*if (i !== breadcrumbsUrls.length - 1) {
						url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a> <span class="arrow">→</span> ';
						document.getElementById("backButton").href = "#"+u;
						
					}
					else {
						url += '<span class="folderName">' + name[name.length-1] + '</span>';
					}*/
					if (i !== breadcrumbsUrls.length - 1) {
						if(i == 0 && ("<?php echo $docname; ?>"=="Energy Procurement" || "<?php echo $docname; ?>"=="Market Resources" || "<?php echo $docname; ?>"=="Energy Accounting" || "<?php echo $docname; ?>"=="Sustainability" || "<?php echo $docname; ?>"=="Data Management" || "<?php echo $docname; ?>"=="Projects" || "<?php echo $docname; ?>"=="Rate Optimization" || "<?php echo $docname; ?>"=="Rate Optimization:Regulated Information" || "<?php echo $docname; ?>"=="Energy Procurement:Dynamic Risk Management" || "<?php echo $docname; ?>"=="Energy Procurement:Strategy" || "<?php echo $docname; ?>"=="Energy Procurement:Direct Access Information" || "<?php echo $docname; ?>"=="Data Management:Data Analysis" || "<?php echo $docname; ?>"=="Data Management:Custom Reports" || "<?php echo $docname; ?>"=="Data Management:Consumption Reports" || "<?php echo $docname; ?>"=="Energy Accounting:Invoice Validation" || "<?php echo $docname; ?>"=="Energy Accounting:Utility Budgets" || "<?php echo $docname; ?>"=="Sustainability:Sustainability Reports" || "<?php echo $docname; ?>"=="Sustainability:Corporate Reports" || "<?php echo $docname; ?>"=="Sustainability:Surveys" || "<?php echo $docname; ?>"=="Market Resources:Commodity Markets" || "<?php echo $docname; ?>"=="Energy Accounting:Exception Reports" || "<?php echo $docname; ?>"=="Energy Accounting:Resolved Exceptions" || "<?php echo $docname; ?>"=="Energy Accounting:Site and Account Changes" || "<?php echo $docname; ?>"=="Projects:Other" || "<?php echo $docname; ?>"=="Projects:Rebates and Incentives" || "<?php echo $docname; ?>"=="Projects:EV Charging" || "<?php echo $docname; ?>"=="Projects:Efficiency Upgrades" || "<?php echo $docname; ?>"=="Projects:Distributed Generation" || "<?php echo $docname; ?>"=="Rate Optimization:Utility Rate Change Requests" || "<?php echo $docname; ?>"=="Rate Optimization:Utility Rate Reports")){}else{
							url += '<a href="'+u+'"><span class="folderName">' + (i==0?"<?php echo $folder_n; ?>":name[name.length-1]) + '</span></a> <span class="arrow">→</span> ';
						}
					}
					else {
						url += '<span class="folderName">' + (breadcrumbsUrls.length==1?"<?php echo $folder_n; ?>":name[name.length-1]) + '</span>';
					}

				});

			}

			breadcrumbs.text('').append(url);


			// Show the generated elements

			fileList.fadeIn(); 

		}


		// This function escapes special html characters in names

		function escapeHTML(text) {
			return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
		}


		// Convert file sizes from bytes to human readable units

		function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return '0 Bytes';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}

	});
});
//Custom Code
function previewPop(filename){
				$.post("assets/php/filepermission.php",
				{
				  url: filename,
				  role: "client"
				},
				function(rurl){
				  if(rurl !=false){ filename=encodeURIComponent(rurl);}
					  var file = filename.replace(/\.\.\//gi,'');
					  var exts = ['jpg','jpeg','gif','png','tif','bmp','ico'];
					  // first check if file field has any value
					  if ( file ) {
						// split file name at dot
						var get_ext = file.split('.');
						// reverse name to check extension
						get_ext = get_ext.reverse();
						// check file type is valid as given in 'exts' array
						if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
						  discode='<img src="'+filename+'" width="100%" style="height:auto;max-height:100%;" />';
						  parent.$("#dialog").html('');
						  parent.$("#dialog").html(discode);
						  parent.$("#dialog").dialog("open");
						  parent.$("#d-download").attr('href', filename);
						} else {
						  discode='<iframe src="https://docs.google.com/viewer?embedded=true&url='+filename+'" frameborder="0" width="100%" height="100%" id="googleload"></iframe><a href="'+filename+'" download><div class="noshow"></div></a>';
						  parent.$("#dialog").html('');
						  parent.$("#dialog").html(discode);
						  parent.$("#dialog").dialog("open");
						  parent.$("#d-download").attr('href', filename);
						}
					  }else{alert(file);}				  
				  
				  
				});
}

function confirmDelete(filename){
	var swalWithBootstrapButtons = Swal.mixin(
	{
		customClass:
		{
			confirmButton: "btn btn-primary",
			cancelButton: "btn btn-danger mr-2"
		},
		buttonsStyling: false
	});
	swalWithBootstrapButtons
		.fire(
		{
			title: "Are you sure to delete "+decodeURIComponent(filename.split('/').pop())+"?",
			text: "You won't be able to revert this!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel!",
			reverseButtons: true
		})
		.then(function(result)
		{
			if (result.value)
			{
				
				$.post("assets/php/filepermission.php",
				{
				  url: filename,
				  role: "client",
				  action: "delete"
				},
				function(rurl){
				  if(rurl !=false){
					swalWithBootstrapButtons.fire(
						"Deleted!",
						"Your file has been deleted.",
						"success"
					);
					setTimeout(function(){ self.location.reload(); }, 3000);
				  }else{
					swalWithBootstrapButtons.fire(
						"Cancelled",
						"Couldnot delete. Please try after sometime!",
						"error"
					);				  
				  }
				});
			}
			else if (
				// Read more about handling dismissals
				result.dismiss === Swal.DismissReason.cancel
			)
			{
				swalWithBootstrapButtons.fire(
					"Cancelled",
					"Your imaginary file is safe :)",
					"error"
				);
			}
		});
}
</script>