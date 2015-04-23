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
    $('#collections .main-inner').append(OpenSprites.models.AssetList(json));
}

update();
