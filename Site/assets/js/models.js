OpenSprites.models = {};

/*OpenSprites.models.AssetList = function(json){
	var model = $("<div>");
	for(var i = 0;i<json.length;i++) {
		var html = $("<div>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time);
		if(json[i].type == "image"){ 
			html.css("background-image", "url("+OpenSprites.domain + json[i].url+")");
		}
        model.append(html);
    }
	return model;
};*/

OpenSprites.models.BaseModel = function(_target){
	var modelObj = {};
	modelObj._target = _target;
	modelObj.loadJson = function(json){
		
	};
	return modelObj;
};

OpenSprites.models.AssetList = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target); // attempting a java-class-like structure
	modelObj.loadJson = function(json){
		modelObj._target.html("");
		for(var i = 0;i<json.length;i++) {
			var html = $("<div>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time);
			if(json[i].type == "image"){ 
				html.css("background-image", "url("+OpenSprites.domain + json[i].url+")");
			}
        		modelObj._target.append(html);
		}
	};
	return modelObj;
};
