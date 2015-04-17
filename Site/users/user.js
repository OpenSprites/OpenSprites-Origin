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
    $.getJSON('/site-api/stuff.php?userid=' + OpenSprites.view.user.id + '&_=' + new Date, function(result) {
        if(JSON.stringify(result) !== JSON.stringify(jsonOld)) {
            jsonOld = result;
            processAjax(result);
        } else if(Object.size(result, true) === 0) {
            $('.main-inner').html('');
            $('.main-inner').append('<h1>Uploads (' + Object.size(result, true) + ')</h1>');
        }
        
        setTimeout(update, updateInterval);
    });
}

function processAjax(json) {
    $('.main-inner').html('');
    $('.main-inner').append('<h1>Uploads (' + Object.size(json, true) + ')</h1>');
    
    for(var i = Object.size(json, false); i > 0; i--) {
        if(jsonOld.hasOwnProperty(i) && typeof jsonOld[i].deleted === 'undefined') {
            var html = '<a href="../../uploads/' + json[i].name + '" class="file">';

            if(json[i].type === 'image')
                html += '<img src="../../uploads/uploaded/' + json[i].name + '">';
            else
                html += '<img src="../../assets/images/defaultfile.png">';

            html += '</a>';
            $('.main-inner').append(html);
        }
    }
}

update();
