///////////////////////////////////// Lib
(function($) {
    $.extend($.fn, {
        makeCssInline: function() {
            this.each(function(idx, el) {
                var style = el.style;
                var properties = [];
                for(var property in style) { 
                    if($(this).css(property)) {
                        properties.push(property + ':' + $(this).css(property));
                    }
                }
                this.style.cssText = properties.join(';');
                $(this).children().makeCssInline();
            });
			return this;
        }
    });
}(jQuery));


if (!HTMLCanvasElement.prototype.toBlob) {
	Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
		value: function (callback, type, quality) {
			var binStr = atob( this.toDataURL(type, quality).split(',')[1] ),
			len = binStr.length,
			arr = new Uint8Array(len);
			for (var i=0; i<len; i++ ) {
				arr[i] = binStr.charCodeAt(i);
			}
			callback( new Blob( [arr], {type: type || 'image/png'} ) );
		}
	});
}

Object.defineProperty(HTMLCanvasElement.prototype, 'toBinStr', {
	value: function (type, quality) {
		var binStr = atob( this.toDataURL(type, quality).split(',')[1] );
		return binStr;
	}
});

///////////////////////////////////// Script chooser GUI
function newScript(){
	var scriptCont =  $("<div class='script-container'><label>Select this script: </label><input type='checkbox' /><br/><pre class='blocks'></pre></div>");
	var rnd = Math.random();
	scriptCont.find("input").attr("id", "script"+rnd).addClass("script-check");
	scriptCont.find("label").attr("for", "script"+rnd);
	return scriptCont;
}

function newDivider(name){
	return $("<h3>").text(name).addClass("divider");
}

var scriptsToProcess = [];
var scriptHeadings = [];
var allScripts = [];

var stylesheet = "";
$.get("/assets/lib/scratchblocks2/scratchblocks2.css", function(data){
	stylesheet = data;
});

function processScripts(){
	if(scriptsToProcess.length == 0){
		scratchblocks2.parse("pre.blocks");
		if($("#block-container").html() == ""){
			$("#block-container").html("<p style='font-style: italic;'>No scripts were found in that project file!</p>");
		}
		$("#script-select-dialog, .modal-bg").show();
		return;
	}
	var currentScript = scriptsToProcess.pop();
	var json = null;
	try {
		json = JSON.parse(currentScript);
		var name = "Unknown";
		if(json.hasOwnProperty("objName")) name = json['objName'];
		if(json.hasOwnProperty("scripts")){
			enumerateScripts(name, json['scripts']);
		}
		if(json.hasOwnProperty("children")){
			var children = json['children'];
			for(var i=0;i<children.length;i++){
				var child = children[i];
				var cName = "Unknown";
				if(child.hasOwnProperty("objName")) cName = child['objName'];
				if(child.hasOwnProperty("scripts")){
					enumerateScripts(cName, child['scripts']);
				}
			}
		}
	} catch(e){
		console.log(e);
	}
	processScripts();
}

function enumerateScripts(name, scripts){
	var divider = newDivider(name);
	var index = scriptHeadings.length;
	scriptHeadings.push(divider);
	$("#jumpto").append($("<option>").val("" + index).text(name));
	$("#block-container").append(divider);
	for(var i=0;i<scripts.length;i++){
		var jsonScript = scripts[i];
		var index = allScripts.length;
		allScripts.push(jsonScript.slice(2)[0]);
		var scratchblocks = gen.generate(jsonScript);
		var html = newScript();
		html.find("pre").html(scratchblocks);
		html.find("input").attr("data-script", ""+index);
		$("#block-container").append(html);
	}
}

$("#jumpto").on("change", function(e){
	var val = $(this).val();
	if(val == "_null") return;
	val = parseInt(val);
	$("#block-container").scrollTop(scriptHeadings[val].position().top - 80);
});

$(".sel-all").click(function(){
	$(".script-check").prop("checked", true);
});

$(".sel-none").click(function(){
	$(".script-check").prop("checked", false);
});

$(".cancel").click(function(){
	$("#script-select-dialog, .modal-bg").fadeOut();
});

$(".ok").click(function(){
	$(this).text("Processing...");

	$(".script-check:checked").each(function(){
		var scriptJson = allScripts[parseInt($(this).attr("data-script"))];
		var file = JSON.stringify(scriptJson);
		var blob = new Blob([file], {type: "application/json"});
		
		var template = newTemplate();
		template.find(".name").text("Script");
		template.find(".type").text("Local Upload");
		template.find(".ftype").text("Script");
		template.find(".size").text(getPrettySize(file.length));
		template.find(".status").text("Ready to upload");
		
		// render script to canvas
		var canvas = document.createElement('canvas');
		var canvasWidth = canvas.width = 230; var canvasHeight = canvas.height = 230;
		var ctx = canvas.getContext('2d');
		var pre = $(this).parent().find("pre");
		var data = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">' +
			'<foreignObject width="100%" height="100%">' +
			'<div xmlns="http://www.w3.org/1999/xhtml" style="font-size: 40px;background:white;height:100%;">' +
				pre.html() +
				"<style type='text/css'>\n" +
					stylesheet +
				"\n</style>" +
			'</div>' +
			'</foreignObject>' +
			'</svg>';
		var DOMURL = window.URL || window.webkitURL || window;
		var svg = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
		var url = DOMURL.createObjectURL(svg);
		template.css("background-image", "url("+url+")");
		
		// md5 and everything
		var hash = md5(file);
		
		if(allFiles.hasOwnProperty(hash)) return;
		allFiles[hash] = blob;
		template.attr("data-id", hash);
		template.find(".del").click(function(){
			var parent = $(this).parent().parent();
			delete allFiles[parent.attr("data-id")];
			parent.remove();
		});
		$("#upload-area").append(template);
	});
	$("#script-select-dialog, .modal-bg").fadeOut();
});

///////////////////////////////////// File chooser GUI
function newTemplate(){
	var randomId = Math.random();
	return $("<div class='upload-image'>\
				<p>\
					<label for='hidden-asset-" + randomId + "' title='Don't show this asset on my profile, and don't associate it with me.'>Hidden</label>\
					<input type='checkbox' class='hide-asset' id='hidden-asset-" + randomId + "' />\
					Name: <input type='text' class='customName' placeholder='Enter a name' /><br/>\
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
	var fileName = name.substring(0, name.lastIndexOf("."));
	fileName = fileName.replace(/[\-_\.]/g, " ").split(" ").map(function(item){
		if(item.length == 0 || item.length == 1) return item;
		return item.substring(0, 1).toUpperCase() + item.substring(1);
	}).join(" ");
	return fileName;
}

if (!(window.File && window.FileReader && window.FileList && window.Blob && window.FormData)) {
	$(".upload-message").addClass("error").text("Your browser doesn't have some features we need for upload to work! Update or try another browser");
}

var allFiles = {};
var totalFiles = 0;
var addedFiles = 0;
var totalSize = 0;
var processedArchives = 0;
var totalArchives = 0;
var scriptsInterval = 0;
var hasScripts = false;

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
			if(f.name.toLowerCase().endsWith(".jpg") || f.name.toLowerCase().endsWith(".jpeg") || f.name.toLowerCase().endsWith(".png") || f.name.toLowerCase().endsWith(".gif") || f.name.toLowerCase().endsWith(".svg")){
				type = "image";
			} else if(f.name.toLowerCase().endsWith(".wav") || f.name.toLowerCase().endsWith(".mp3")){
				type = "audio";
			} else if(f.name.toLowerCase().endsWith(".json")){
				type = "script";
			}
			
			if(type == "unknown") continue;
			addedFiles++;
			
			if(type == "script") {
				scriptsToProcess.push(f.asText());
				hasScripts = true;
				continue;
			}
			
			var asBinary = f.asBinary();
			var asArrayBuffer = f.asArrayBuffer();
			
			var template = newTemplate();
			template.find(".customName").text(getPrettyName(f.name));
			template.find(".type").text("Local Upload");
			template.find(".ftype").text(type+" from archive");
			template.find(".size").text(getPrettySize(asBinary.length));
			template.find(".status").text("Ready to upload");
			
			totalSize += asBinary.length;
			if(totalSize > 50000000){
				console.log("Over size!");
				$("#upload-status p").text("Some files were not added. Keep total file size below 50MB.");
				return;
			}
			
			if(type =="image"){
				var type2 = "jpeg";
				if(f.name.toLowerCase().endsWith("svg")) type2 = "svg";
				else if(f.name.toLowerCase().endsWith("png")) type2 = "png";
				template.css("background", "url(data:image/"+type2+";base64,"+btoa(asBinary)+")");
			}
		
			if(type == "audio"){
				var buffer = asArrayBuffer;
				window.AudioContext = window.AudioContext || window.webkitAudioContext ;
				if(!!AudioContext){
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
			}
			var hash = md5(asBinary);
			if(allFiles.hasOwnProperty(hash)) continue;
			allFiles[hash] = new Blob([asArrayBuffer]);
			template.attr("data-id", hash);
			template.find(".del").click(function(){
				var parent = $(this).parent().parent();
				delete allFiles[parent.attr("data-id")];
				parent.remove();
			});
			$("#upload-area").append(template);
		}
		processedArchives++;
		$(".archivemsg").remove();
	}
	reader.readAsArrayBuffer(file);
}

function processFiles(files){
	totalFiles = files.length;
	addedFiles = 0;
	hasScripts = false;
	totalSize = 0;
	processedArchives = 0;
	totalArchives = 0;
	var hasSounds = false;
	for(key in allFiles){
		if(allFiles.hasOwnProperty(key)) totalSize += allFiles[key].size;
	}
	for (var i = 0, f; f = files[i]; i++) {
		totalSize += f.size;
		if(totalSize > 50000000){
			$("#upload-status p").text("Some files were not added. Keep total file size below 50MB.");
			return;
		}
	
		var template = newTemplate();
		
		var type = "unknown";
		if(f.name.toLowerCase().endsWith(".jpg") || f.name.toLowerCase().endsWith(".jpeg") || f.name.toLowerCase().endsWith(".png") || f.name.toLowerCase().endsWith(".gif") || f.name.toLowerCase().endsWith(".svg")){
			type = "image";
		} else if(f.name.toLowerCase().endsWith(".wav") || f.name.toLowerCase().endsWith(".mp3")){
			type = "audio";
		} else if(f.name.toLowerCase().endsWith(".sb2") || f.name.toLowerCase().endsWith(".sprite2") || f.name.toLowerCase().endsWith(".zip")){
			totalFiles--;
			totalArchives++;
			processArchive(f);
			continue;
		} else if(f.name.toLowerCase().endsWith(".json")){
			type = "script";
		}
		
		if(type == "unknown") continue;
		addedFiles++;
		
		if(type == "script") {
			var reader = new FileReader();
			reader.onload = function(e) {
				scriptsToProcess.push(e.target.result);
			}
			reader.readAsText(f);
			hasScripts = true;
			continue;
		}
		
		template.find(".customName").text(getPrettyName(f.name));
		template.find(".type").text("Local Upload");
		template.find(".ftype").text(type);
		template.find(".size").text(getPrettySize(f.size));
		template.find(".status").text("Ready to upload");
		
		var type2 = "";
		
		if(type =="image"){
			type2 = "jpeg";
			if(f.name.toLowerCase().endsWith("svg")) type2 = "svg";
			else if(f.name.toLowerCase().endsWith("png")) type2 = "png";
			else if(f.name.toLowerCase().endsWith("gif")) type2 = "gif";
			
			(function(template, file){ // wrap it so we can continue processing asynchronously without screwing with the stack
				var reader = new FileReader();
				reader.onload = function(e) {
					template.css("background", "url("+e.target.result+")");
					
					if(type2 == "jpeg"){
						var img = new Image(); // strip metadata for privacy
						img.onload = function(){
							var canv = document.createElement("canvas");
							canv.width = img.naturalWidth;
							canv.height = img.naturalHeight;
							var ctx = canv.getContext("2d");
							ctx.drawImage(img, 0, 0);
							
							var binStr = canv.toBinStr("image/jpeg", 0.99);
							var blob = canv.toBlob(function(blob){
								template.find(".size").text(getPrettySize(blob.size));
								var hash = md5(binStr);
								if(allFiles.hasOwnProperty(hash)) return;
								allFiles[hash] = blob;
								template.attr("data-id", hash);
								template.find(".del").click(function(){
									var parent = $(this).parent().parent();
									delete allFiles[parent.attr("data-id")];
									parent.remove();
								});
								$("#upload-area").append(template);
							}, "image/jpeg", 0.99);
						};
						img.src = e.target.result;
					}
				}
				reader.readAsDataURL(file);
			})(template, f);
		}
		
		if(type == "audio"){
			hasSounds = true;
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
						$(".audiomsg").remove();
                    }, function(e){ console.log(e); });
				}
				reader.readAsArrayBuffer(file);
			})(template, f);
		}
		if(type2 != "jpeg"){
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
	}
	
	if(addedFiles != totalFiles){
		$("#upload-status p").text("Some files unrecognized.");
	} else {
		$("#upload-status p").text("Added files.");
		if(hasSounds) $("#upload-status p").append(" <span class='audiomsg'>Generating previews for sounds...</span>");
		else if(totalArchives > 0) $("#upload-status p").append(" <span class='archivemsg'>Unpacking archives...</span>");
	}
	clearInterval(scriptsInterval);
	scriptsInterval = setInterval(function(){
		if(processedArchives != totalArchives) return;
		clearInterval(scriptsInterval);
		$("#block-container").html("");
		$(".ok").text("OK");
		scriptHeadings = [];
		allScripts = [];
		$("#jumpto").html("<option value='_null'>Jump to...</option>");
		if(hasScripts) processScripts();
	}, 100);
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

///////////////////////////////////////// From URL
$("#url-button").click(function(){
	$("#url-input").fadeIn(200, function(){
		$(this).focus();
	});
});
$("#url-input").on("keydown keyup", function(e){
	$(this).removeClass("error");
	if(e.which == 13){
		var url = $(this).val();
		// TODO: implement
		$(this).fadeOut(200);
	}
});


///////////////////////////////////////// Uploading
function uploadFiles(){
	var totalFiles = 0;
	var formData = new FormData();
	var extraJson = {};
	var hiddenAssets = [];
	formData.append("token", window.uploadCsrfToken);
	for(key in allFiles){
		if(allFiles.hasOwnProperty(key)){
			totalFiles++;
			formData.append("uploadedfile[]", allFiles[key]);
			var customNameInput = $("[data-id="+key+"]").find(".customName");
			if(customNameInput.val() != null && customNameInput.val() != ""){
				extraJson[key] = customNameInput.val();
			}
			var isHidden = $("[data-id="+key+"]").find(".hide-asset").is(":checked");
			if(isHidden) hiddenAssets.append(key);
		}
	}
	formData.append("customNames", JSON.stringify(extraJson));
	formData.append("hiddenAssets", JSON.stringify(hiddenAssets));
	
	if(totalFiles == 0){
		return;
	}
	$("#upload-button-container").fadeOut();
	$("#upload-status p").text("Uploading...");
	$("input.customName").attr("disabled", "disabled");
	
	$.ajax({
		url : "upload.php",
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
						if(percentComplete > 99){
							$("#upload-status p").text("Processing uploads...");
						} else {
							$("#upload-status p").text("Uploading ("+Math.round(percentComplete)+"%)");
						}
					}
				}, false);
			}
			return xhr;
		},
		success : function (data) {
			$("#upload-button-container").fadeIn();
			$("#upload-status p").text("Upload complete");
			$("input.customName").attr("disabled", "disabled");
			$(".progress .bar").css("width", "100%");
			try {
				var resp = JSON.parse(data);
				console.log(resp);
				$("#upload-status p").text(data.status);
				
				if(resp.status == "sanic"){
					$("#error-dialog").html(resp.include_html);
					$("#error-dialog").find("p").append($("<a>").addClass("btn").addClass("blue").attr("href","javascript:void(0)").text("Got it").click(function(){
						$("#error-dialog").fadeOut();
					}));
					$("#error-dialog").fadeIn();
					return;
				}
				
				for (var i = 0; i < resp.results.length; i++) {
					var result = resp.results[i];
					var hash = result.hash;
					
					if (allFiles.hasOwnProperty(hash)) {
						if(result.status == "success"){
							delete allFiles[hash];
							$("[data-id="+hash+'] .del').remove();
							$("[data-id="+hash+'] .status').text("Uploaded!");
							$("[data-id="+hash+'] .customName').replaceWith($("<span>").text($("[data-id="+hash+'] .customName').val()));
							$("[data-id="+hash+']').removeClass("error");
						} else {
							$("[data-id="+hash+'] .status').text("Failed");
							$("[data-id="+hash+']').addClass("error");
						}
						$("[data-id="+hash+'] .status').append("<br/>"+result.message);
						$("[data-id="+hash+']').attr("data-url", result.image_url).attr("title", "Click to view your asset").click(function(){
							window.open("/users/" + OpenSprites.user.id + "/" + hash + "/");
						});
					}
				}
			} catch (e) {
				console.log(e);
				console.log(data);
				$("#upload-status p").text("Something bad happened and our servers sent back a bad response. Contact us if this continues.");
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			$("#upload_overlay").fadeOut();
			console.log(jqXHR, textStatus, errorThrown);
			$("#upload-status p").text("Sorry! Something bad happened and your image wasn't uploaded. Check your internet connection.");
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
