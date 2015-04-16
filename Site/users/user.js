var updateInterval = 10000;
var jsonOld = [];

function update() {
    $.getJSON('/site-api/stuff.php?userid=' + OpenSprites.view.user.id, function(result) {
        if(JSON.stringify(result) !== JSON.stringify(jsonOld)) {
            jsonOld = result;
            processAjax(result);
        } else if(result.length === 0) {
            $('.main-inner').html('');
            $('.main-inner').append('<h1>Uploads (' + result.length + ')</h1>');
        }
        
        setTimeout(update, updateInterval);
    });
}

function processAjax(json) {
    $('.main-inner').html('');
    $('.main-inner').append('<h1>Uploads (' + json.length + ')</h1>');
    
    for(var i = 0; i < json.length; i++) {
        var html = '<a href="../../uploads/' + json[i].name + '" class="file">';
        
        if(json[i].type === 'image')
            html += '<img src="../../uploads/uploaded/' + json[i].name + '">';
        else
            html += '<img src="../../assets/images/defaultfile.png">';
            
        html += '</a>';
        $('.main-inner').append(html);
    }
}

update();
