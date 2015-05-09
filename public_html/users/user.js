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
	var canvasId = "background-img";
	var canvas = document.getElementById(canvasId);
	if(canvas !== null) {
		var context = canvas.getContext("2d");
	
		var img = new Image();
		img.onload = function() {
			drawImageProp(context, img);
			stackBlurCanvasRGB(canvas, 0, 0, canvas.width, canvas.height, 10);
		}
		img.src = "/uploads/avatar_blur.php?userid=" + OpenSprites.view.user.id;
	}
}

drawBg();
$(window).resize(drawBg);

/* fancy modal */
$("#settings").click(function() {
    $.get('/users/edit.php', function(data) {
        console.log(data);
        if(data == 'false') {
            json = {
                bgcolor: 'avatar'
            };
        } else {
            json = data;
        }
        
        $('.modal.edit-profile input[name=bgcolor]').val(json.bgcolor == 'avatar' ? 'rgb(101, 149, 147)' : json.bgcolor);
        $('.modal.edit-profile input#bg').val(json.bgcolor == 'avatar');
        
        $('.modal.edit-profile input[name=bgcolor]').spectrum({
            showButtons: false    
        });

        $('.modal.edit-profile input#bg').change(function() {
            var val = $(".modal.edit-profile input#bg:checked").map(function() {return this.value;}).get().join(",");
            console.log(val);
            if(val) {
                $('.modal.edit-profile #bg_true').hide();
            } else {
                $('.modal.edit-profile #bg_true').show();
            }
        });
        
        $(".modal-overlay, .modal.edit-profile").fadeIn();
    });
});

$('.modal.edit-profile .btn.red').click(function() {
	$(".modal-overlay, .modal.edit-profile").fadeOut();
});