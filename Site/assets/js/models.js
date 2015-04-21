OpenSprites.models = {};

OpenSprites.models.AssetList = function(json){
	var model = $("<div>");
	for(var i = 0;i<json.length;i++) {
		var html = $("<div>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time);
		if(json[i].type == "image"){ 
			html.css("background-image", "url("+OpenSprites.domain + json[i].url+")");
		}
        model.append(html);
    }
	return model;
};