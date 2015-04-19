///////////////////////////////////// File chooser GUI
function newTemplate(){
	return $("<div class='upload-image'>\
				<p>Name: <span class='name'>Sample Text</span><br/>\
					Type: <span class='type'></span><br/>\
					Filetype: <span class='ftype'></span><br/>\
					Size: <span class='size'></span><br/>\
					Status: <span class='status'></span><br/><br/>\
					<a href='javascript:void(0)' class='del'>Remove from queue</a>\
				</p>\
			</div>");
}

function getPrettySize(size){
	if(size < 1000) return size + " B";
	if(size < 1000000) return (Math.round((size)/1000 * 10) / 10) +" kB";
	else return (Math.round((size)/1000000 * 10) / 10) +" mB";
}

function getPrettyName(name){
	var ext = name.substring(name.lastIndexOf("."));
	var fileName = name.substring(0, name.lastIndexOf("."));
	if(fileName.length > 10) {
		fileName = fileName.substring(0, 4)+"..."+fileName.substring(fileName.length - 3);
	}
	return fileName+ext;
}

if (!(window.File && window.FileReader && window.FileList && window.Blob && window.FormData)) {
	$(".upload-message").addClass("error").text("Your browser doesn't have some features we need for upload to work! Update or try another browser");
}

var allFiles = {};

function processFiles(files){
	var totalFiles = files.length;
	var addedFiles = 0;
	for (var i = 0, f; f = files[i]; i++) {
		var template = newTemplate();
		
		var type = "unknown";
		if(f.name.endsWith(".jpg") || f.name.endsWith(".jpeg") || f.name.endsWith(".png") || f.name.endsWith(".gif") || f.name.endsWith(".svg")){
			type = "image";
		} else if(f.name.endsWith(".wav") || f.name.endsWith(".mp3")){
			type = "audio";
		} else if(f.name.endsWith(".sb2") || f.name.endsWith(".sprite2")){
			type = "projectArchive";
		} else if(f.name.endsWith(".json")){
			type = "script";
		}
		
		if(type == "unknown") continue;
		addedFiles++;
		
		template.find(".name").text(getPrettyName(f.name));
		template.find(".type").text("Local Upload");
		template.find(".ftype").text(type);
		template.find(".size").text(getPrettySize(f.size));
		template.find(".status").text("Ready to upload");
		
		$("#upload-area").append(template);
		
		if(type =="image"){
			(function(template, file){ // wrap it so we can continue processing asynchronously without screwing with the stack
				var reader = new FileReader();
				reader.onload = function(e) {
					template.css("background", "url("+e.target.result+")");
				}
				reader.readAsDataURL(file);
			})(template, f);
		}
		
		if(type == "audio"){
			(function(template, file){
				var reader = new FileReader();
				reader.onload = function(e) {
					var buffer = e.target.result;
				}
				reader.readAsArrayBuffer(file);
			})(template, f);
		}
		
		(function(template, file){
			var hashReader = new FileReader();
			hashReader.onload = function (e2) {
				var hash = md5(e2.target.result);
				allFiles[hash] = file;
				template.attr("data-id", hash);
				template.find(".del").click(function(){
					var parent = $(this).parent().parent();
					delete allFiles[parent.attr("data-id")];
					parent.remove();
				});
			};
			hashReader.readAsBinaryString(file);
		})(template, f);
	}
	
	if(addedFiles != totalFiles){
		$("#upload-status p").text("Added "+addedFiles+" files; "+(totalFiles - addedFiles)+" files unrecognized.");
	} else {
		$("#upload-status p").text("Added "+addedFiles+" files.");
	}
}

$("#uploadbtn").on("change", function(e){
	$(".upload-message").hide();
	
	var files = e.originalEvent.target.files;
	processFiles(files);
});

var uploadArea = $("#upload-area").get(0);
uploadArea.addEventListener('dragover', function(e){
	e.stopPropagation();
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
}, false);

uploadArea.addEventListener('drop', function(e){
	e.stopPropagation();
    e.preventDefault();
	$(".upload-message").hide();
	processFiles(e.dataTransfer.files);
}, false);


///////////////////////////////////////// Uploading
function uploadFiles(){
	var totalFiles = 0;
	var formData = new FormData();
	formData.append("token", window.uploadCsrfToken);
	for(key in allFiles){
		if(allFiles.hasOwnProperty(key)){
			totalFiles++;
			formData.append("uploadedfile[]", allFiles[key]);
		}
	}
	if(totalFiles == 0){
		return;
	}
	$("#upload-button-container").fadeOut();
	$("#upload-status p").text("Uploading...");
	
	$.ajax({
		url : "_matu_upload.php",
		type : 'POST',
		data : formData,
		async : true,
		xhr : function () {
			var xhr = jQuery.ajaxSettings.xhr();
			if (xhr instanceof window.XMLHttpRequest) {
				xhr.upload.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded * 98 / evt.total; // yes, IK 98% is hax and I should probably be setting sizes correctly...
						$(".progress .bar").css("width", percentComplete+"%");
					}
				}, false);
			}
			return xhr;
		},
		success : function (data) {
			$("#upload-button-container").fadeIn();
			$("#upload-status p").text("Upload complete");
			$(".progress .bar").css("width", "98%");
			try {
				var resp = JSON.parse(data);
				console.log(resp);
				$("#upload-status p").text(data.status);
				for (var i = 0; i < resp.results.length; i++) {
					var result = resp.results[i];
					var hash = result.hash;
					
					if (allFiles.hasOwnProperty(hash)) {
						if(result.status == "success"){
							delete allFiles[hash];
							$("[data-id="+hash+'] .del').remove();
							$("[data-id="+hash+'] .status').text("Uploaded!");
						} else {
							$("[data-id="+hash+'] .status').text("Failed");
						}
						$("[data-id="+hash+'] .status').append("<br/>"+result.message);
					}
				}
			} catch (e) {
				console.log(e);
				console.log(data);
				$("#upload-status p").text("Sorry! There was a problem receiving a response from our servers.");
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			$("#upload_overlay").fadeOut();
			console.log(jqXHR, textStatus, errorThrown);
			$("#upload-status p").text("Sorry! Something bad happened and your image wasn't uploaded.");
		},
		cache : false,
		contentType : false,
		dataType : "text",
		processData : false
	});
}

$("#upload-button").click(function(){
	uploadFiles();
});