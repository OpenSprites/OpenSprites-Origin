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
var totalFiles = 0;
var addedFiles = 0;
var totalSize = 0;

function processArchive(file){
	console.log("Reading archive: "+file.name);
	var reader = new FileReader();
	reader.onload = function (e) {
		var zip = new JSZip(e.target.result);
		console.log(zip);
		for(key in zip.files){
			var zipObj = zip.files[key];
			var f = zipObj;
			if(zipObj.dir) continue;
			totalFiles++;
			var type = "unknown";
			if(f.name.endsWith(".jpg") || f.name.endsWith(".jpeg") || f.name.endsWith(".png") || f.name.endsWith(".gif") || f.name.endsWith(".svg")){
				type = "image";
			} else if(f.name.endsWith(".wav") || f.name.endsWith(".mp3")){
				type = "audio";
			} else if(f.name.endsWith(".json")){
				type = "script";
			}
			
			if(type == "unknown") continue;
			addedFiles++;
			
			var asBinary = f.asBinary();
			var asArrayBuffer = f.asArrayBuffer();
			
			var template = newTemplate();
			template.find(".name").text(getPrettyName(f.name));
			template.find(".type").text("Local Upload");
			template.find(".ftype").text(type+" from archive");
			template.find(".size").text(getPrettySize(asBinary.length));
			template.find(".status").text("Ready to upload");
			
			totalSize += asBinary.length;
			if(totalSize > 8388608){
				$("#upload-status p").text("Some files were not added. Keep total file size below 8MB.");
				return;
			}
			
			if(type =="image"){
				var type2 = "jpeg";
				if(f.name.endsWith("svg")) type2 = "svg";
				else if(f.name.endsWith("png")) type2 = "png";
				template.css("background", "url(data:"+type+";base64,"+btoa(asBinary)+")");
			}
		
			if(type == "audio"){
				var buffer = asArrayBuffer;
				window.AudioContext = window.AudioContext || window.webkitAudioContext ;
				if (!AudioContext) return;
				var audioContext = new AudioContext();
				var canvas = document.createElement("canvas");
				var canvasWidth = canvas.width = 230; var canvasHeight = canvas.height = 230;
				var context = canvas.getContext('2d');
				audioContext.decodeAudioData(buffer, function(currentBuffer) {
                    var leftChannel = currentBuffer.getChannelData(0);
					var lineOpacity = canvasWidth / leftChannel.length  ;      
					context.save();
					context.fillStyle = '#222' ;
					context.fillRect(0,0,canvasWidth,canvasHeight );
					context.strokeStyle = 'rgb(101, 149, 147)';
					context.globalCompositeOperation = 'lighter';
					context.globalAlpha = 1;
					for (var i=0; i<  leftChannel.length; i += 4410) {
						var x = Math.floor ( canvasWidth * i / leftChannel.length ) ;
						var y = leftChannel[i] * canvasHeight / 2 ;
						context.beginPath();
						context.moveTo( x  , canvasHeight/2 + y );
						context.lineTo( x+1, canvasHeight/2 - y );
						context.stroke();
					}
					context.restore();
					template.css("background", "url("+canvas.toDataURL()+")");
                }, function(e){ console.log(e); });
			}
			var hash = md5(asBinary);
			if(allFiles.hasOwnProperty(hash)) return;
			allFiles[hash] = new Blob([f.asArrayBuffer()]);
			template.attr("data-id", hash);
			template.find(".del").click(function(){
				var parent = $(this).parent().parent();
				delete allFiles[parent.attr("data-id")];
				parent.remove();
			});
			$("#upload-area").append(template);
		}
	}
	reader.readAsArrayBuffer(file);
}

function processFiles(files){
	totalFiles = files.length;
	addedFiles = 0;
	totalSize = 0;
	for(key in allFiles){
		if(allFiles.hasOwnProperty(key)) totalSize += allFiles[key].size;
	}
	for (var i = 0, f; f = files[i]; i++) {
		totalSize += f.size;
		if(totalSize > 8388608){
			$("#upload-status p").text("Some files were not added. Keep total file size below 8MB.");
			return;
		}
	
		var template = newTemplate();
		
		var type = "unknown";
		if(f.name.endsWith(".jpg") || f.name.endsWith(".jpeg") || f.name.endsWith(".png") || f.name.endsWith(".gif") || f.name.endsWith(".svg")){
			type = "image";
		} else if(f.name.endsWith(".wav") || f.name.endsWith(".mp3")){
			type = "audio";
		} else if(f.name.endsWith(".sb2") || f.name.endsWith(".sprite2") || f.name.endsWith(".zip")){
			totalFiles--;
			processArchive(f);
			continue;
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
					window.AudioContext = window.AudioContext || window.webkitAudioContext ;
					if (!AudioContext) return;
					var audioContext = new AudioContext();
					var canvas = document.createElement("canvas");
					var canvasWidth = canvas.width = 230; var canvasHeight = canvas.height = 230;
					var context = canvas.getContext('2d');
					audioContext.decodeAudioData(buffer, 
                    function(currentBuffer) {
                        var leftChannel = currentBuffer.getChannelData(0);
						var lineOpacity = canvasWidth / leftChannel.length  ;      
						context.save();
						context.fillStyle = '#222' ;
						context.fillRect(0,0,canvasWidth,canvasHeight );
						context.strokeStyle = 'rgb(101, 149, 147)';
						context.globalCompositeOperation = 'lighter';
						context.globalAlpha = 1;
						for (var i=0; i<  leftChannel.length; i += 4410) {
							var x = Math.floor ( canvasWidth * i / leftChannel.length ) ;
							var y = leftChannel[i] * canvasHeight / 2 ;
							context.beginPath();
							context.moveTo( x  , canvasHeight/2 + y );
							context.lineTo( x+1, canvasHeight/2 - y );
							context.stroke();
						}
						context.restore();
						template.css("background", "url("+canvas.toDataURL()+")");
                    }, function(e){ console.log(e); });
				}
				reader.readAsArrayBuffer(file);
			})(template, f);
		}
		
		(function(template, file){
			var hashReader = new FileReader();
			hashReader.onload = function (e2) {
				var hash = md5(e2.target.result);
				if(allFiles.hasOwnProperty(hash)) return;
				allFiles[hash] = file;
				template.attr("data-id", hash);
				template.find(".del").click(function(){
					var parent = $(this).parent().parent();
					delete allFiles[parent.attr("data-id")];
					parent.remove();
				});
				$("#upload-area").append(template);
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
						var percentComplete = evt.loaded * 100 / evt.total;
						$(".progress .bar").css("width", percentComplete+"%");
					}
				}, false);
			}
			return xhr;
		},
		success : function (data) {
			$("#upload-button-container").fadeIn();
			$("#upload-status p").text("Upload complete");
			$(".progress .bar").css("width", "100%");
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
						$("[data-id="+hash+']').attr("data-url", result.image_url).attr("title", "Click to view your asset").click(function(){
							window.open("/uploads/uploaded/" + $(this).attr("data-url"));
						});
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