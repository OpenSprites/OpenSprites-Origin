Object.size = function(obj, t) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    for (key in obj) {
        if (obj[key].deleted == true && t) size--;
    }
    return size;
};

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

var updateInterval = 10000;
var jsonOld = [];
var model = OpenSprites.models.AssetList($("#collections .main-inner .content"));

function update() {
    $.getJSON(OpenSprites.domain + '/site-api/stuff.php?userid=' + OpenSprites.view.user.id + '&_=' + new Date, function(result) {
        if(JSON.stringify(result) !== JSON.stringify(jsonOld)) {
            jsonOld = result;
            processAjax(result);
        } else if(Object.size(result, true) === 0) {
            $('#collections .main-inner .heading').text("Uploads (None)");
        }
        
        setTimeout(update, updateInterval);
    });
}

function processAjax(json) {
    $('#collections .main-inner .heading').text('Uploads (' + json.length + ')');
    model.loadJson(json);
}

update();

// blurred background

/**
 * By Ken Fyrstenberg
 *
 * drawImageProp(context, image [, x, y, width, height [,offsetX, offsetY]])
 *
 * If image and context are only arguments rectangle will equal canvas
*/
function drawImageProp(ctx, img, x, y, w, h, offsetX, offsetY) {

    if (arguments.length === 2) {
        x = y = 0;
        w = ctx.canvas.width;
        h = ctx.canvas.height;
    }

    // default offset is center
    offsetX = typeof offsetX === "number" ? offsetX : 0.5;
    offsetY = typeof offsetY === "number" ? offsetY : 0.5;

    // keep bounds [0.0, 1.0]
    if (offsetX < 0) offsetX = 0;
    if (offsetY < 0) offsetY = 0;
    if (offsetX > 1) offsetX = 1;
    if (offsetY > 1) offsetY = 1;

    var iw = img.width,
        ih = img.height,
        r = Math.min(w / iw, h / ih),
        nw = iw * r,   // new prop. width
        nh = ih * r,   // new prop. height
        cx, cy, cw, ch, ar = 1;

    // decide which gap to fill    
    if (nw < w) ar = w / nw;
    if (nh < h) ar = h / nh;
    nw *= ar;
    nh *= ar;

    // calc source rectangle
    cw = iw / (nw / w);
    ch = ih / (nh / h);

    cx = (iw - cw) * offsetX;
    cy = (ih - ch) * offsetY;

    // make sure source rectangle is valid
    if (cx < 0) cx = 0;
    if (cy < 0) cy = 0;
    if (cw > iw) cw = iw;
    if (ch > ih) ch = ih;

    // fill image in dest. rectangle
    ctx.drawImage(img, cx, cy, cw, ch,  x, y, w, h);
}

function drawBg(){
	var canvas = document.getElementsByTagName('canvas');
	try {
        canvas = canvas[0];
		var context = canvas.getContext("2d");
		var img = new Image();
		img.onload = function() {
			drawImageProp(context, img);
			stackBlurCanvasRGB(canvas, 0, 0, canvas.width, canvas.height, 10);
		}
		img.src = "/uploads/avatar_blur.php?userid=" + OpenSprites.view.user.id;
	} catch(e) {}
}

drawBg();
$(window).resize(drawBg);

$('.modal.edit-profile input#bg').change(function() {
	var val = $(".modal.edit-profile input#bg").is(":checked");
	if(val) {
		$('.modal.edit-profile #bg_true').hide();
	} else {
		$('.modal.edit-profile #bg_true').show();
	}
});
		
$("#settings").click(function() {
	$("#aboutme").val(OpenSprites.view.user.profile.about);
	$("#text-location").val(OpenSprites.view.user.profile.location);
	if(OpenSprites.view.user.profile.bgcolor == "avatar"){
		$("#bg").prop("checked", true);
		$('.modal.edit-profile #bg_true').hide();
		$("#bgcolor").spectrum({
			color: "rgb(101, 149, 147)"
		});
	} else {
		$("#bgcolor").val(OpenSprites.view.user.profile.bgcolor);
		$("#bgcolor").spectrum({
			color: OpenSprites.view.user.profile.bgcolor
		});
		$("#bg").prop("checked", false);
		$('.modal.edit-profile #bg_true').show();
	}
	$(".modal-overlay, .modal.edit-profile").fadeIn();
});

$('.modal.edit-profile .btn.red').click(function() {
	$(".modal-overlay, .modal.edit-profile").fadeOut();
});

$('.modal.edit-profile .btn.blue').click(function() {
	var thisBtn = $(this);
	thisBtn.text("Loading...").attr("disabled", "disabled");
	$(".modal.edit-profile .error").text("");
    var bg = $("#bgcolor").spectrum("get").toRgbString();
    if($(".modal.edit-profile input#bg").is(":checked")) {
        bg = 'avatar';
    }
    
    var aboutme = $('#aboutme').val();
    var location = $('#text-location').val();
    $.post("/users/edit.php", {userid: OpenSprites.user.id, about: aboutme, location: location, bgcolor: bg}, function(data){
		thisBtn.text("OK").removeAttr("disabled");
		if(typeof data == "object"){
			OpenSprites.view.user.profile = data;
			parseDesc(data['about']);
			$("#location").text(data['location']);
			
			if(data['bgcolor'] == "avatar"){
				$("#background-img").fadeOut(700, function(){
					$(this).remove();
					$("body").prepend($("<canvas id='background-img' style='display:none'></canvas>"));
					drawBg();
					$("#background-img").fadeIn();
				});
			} else {
				$("#background-img").fadeOut(700, function(){
					$(this).remove();
					$("body").prepend($("<div id='background-img' style='display:none;background-color:" + data['bgcolor'] + ";'></div>"));
					$("#background-img").fadeIn();
				});
			}
			
			$(".modal-overlay, .modal.edit-profile").fadeOut();
		} else {
			$(".modal.edit-profile .error").text("Error: " + data);
		}
	}).fail(function(){
		thisBtn.text("OK").removeAttr("disabled");
		$(".modal.edit-profile .error").text("Sorry, we were unable to update your profile. Try again later.");
	});
});

var mdHints = OpenSprites.models.MdHints($('#aboutme'));

$("#change-image input[type=file]").change(function(e){
	$("#cropper-container > img").cropper("destroy");
	$("#progress-container").text("");
	$("#cropper-container").text("Loading image...");
	$(".modal-overlay, .modal.cropavatar").fadeIn(700, function(){
		if(!window.Blob || !window.File || !window.FormData){
			$("#cropper-container").text("Whoops! Your browser doesn't support what we need to crop your avatar. Upgrade to the latest Firefox/Firefox-derivative or Chrome/Chromium/Opera.");
			return;
		}
		
		var reader = new FileReader();
		reader.onload = function(e) {
			var img = $("<img>").attr("src", e.target.result);
			$("#cropper-container").html("").append(img);
			img.cropper({
				aspectRatio: 1
			});
		}
		reader.readAsDataURL(e.originalEvent.target.files[0]);
	});
});

$(".modal.cropavatar .btn.blue").click(function(){
	var thisBtn = $(this);
	thisBtn.text("Uploading...").attr("disabled", "disabled");
	
	var canvas = $("#cropper-container > img").cropper("getCroppedCanvas", {width: 200, height: 200, fillColor: "#000000"});
	canvas.toBlob(function(blob){
		var formData = new FormData();
		formData.append("avatar", blob);
		formData.append("userid", OpenSprites.user.id);
		var avatarToken = Math.round(Math.random() * 100000000000).toString(16);
		formData.append("token", avatarToken);
		document.cookie = "avatarToken=" + avatarToken;
		
		$(".progress-container").text("Uploading...");
		
		$.ajax({
			url : "/users/user-avatar.php",
			type : 'POST',
			data : formData,
			async : true,
			xhr : function () {
				var xhr = jQuery.ajaxSettings.xhr();
				if (xhr instanceof window.XMLHttpRequest) {
					xhr.upload.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = Math.round(evt.loaded * 100 / evt.total);
							$(".progress-container").text("Uploading avatar ("+percentComplete+"%)");
						}
					}, false);
				}
				return xhr;
			},
			success : function (data) {
				thisBtn.text("Set Avatar").removeAttr("disabled");
				try {
					data = JSON.parse(data);
					if(data.status != "success"){
						$(".progress-container").text(data.message);
					} else {
						$(".modal-overlay, .modal.cropavatar").fadeOut();
						$(".user-avatar.x100").attr("src", "http://opensprites.gwiddle.co.uk/forums/uploads/avatars/"+OpenSprites.user.id+".png");
					}
				} catch(e){
					console.log(data);
					$(".progress-container").text("Whoops! We weren't able to recieve a response from our servers. Try again later");
				}
			},
			error : function (jqXHR, textStatus, errorThrown) {
				$(".progress-container").text("Whoops! We weren't able to upload your avatar. Try again later");
				thisBtn.text("Set Avatar").removeAttr("disabled");
			},
			cache : false,
			contentType : false,
			dataType : "text",
			processData : false
		});
	}, "image/png");
});

$(".modal.cropavatar .btn.red").click(function(){
	$(".modal-overlay, .modal.cropavatar").fadeOut();
});