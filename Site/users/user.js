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

function update() {
    $.getJSON(OpenSprites.domain + '/site-api/stuff.php?userid=' + OpenSprites.view.user.id + '&_=' + new Date, function(result) {
        if(JSON.stringify(result) !== JSON.stringify(jsonOld)) {
            jsonOld = result;
            processAjax(result);
        } else if(Object.size(result, true) === 0) {
            $('#collections .main-inner').html('');
            $('#collections .main-inner').append('<h1>Uploads (None)</h1>');
        }
        
        setTimeout(update, updateInterval);
    });
}

function processAjax(json) {
    $('#collections .main-inner').html('');
    $('#collections .main-inner').append('<h1>Uploads (' + json.length + ')</h1>');
    
    for(var i = 0;i<json.length;i++) {
		var html = $("<div>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time);
		if(json[i].type == "image") html.css("background-image", "url("+OpenSprites.domain + json[i].url+")");
        $('#collections .main-inner').append(html);
    }
}

update();
