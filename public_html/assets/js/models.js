OpenSprites.models = {};

OpenSprites.models.BaseModel = function(_target){
	var modelObj = {};
	modelObj._target = _target;
	modelObj.loadJson = function(json){
		
	};
	return modelObj;
};

OpenSprites.data = {};
OpenSprites.data.scratchblocks2css = "";
$.get("/assets/lib/scratchblocks2/scratchblocks2.css", function(data){
	OpenSprites.data.scratchblocks2css = data;
});
OpenSprites.data.__ = $(document).keydown.bind($(document));

OpenSprites.models.ScriptPreview = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target);
	modelObj.loadJson = function(json){
		json = [0, 0, json];
		var scratchblocks = gen.generate(json);
		var preClass = "blocks" + Math.round(Math.random() * 100000000);
		var pre = $("<pre>").addClass(preClass).css("display", "none").html(scratchblocks).appendTo(_target);
		scratchblocks2.parse("pre." + preClass);
		
		var data = '<svg xmlns="http://www.w3.org/2000/svg" width="500" height="1000">' +
			'<foreignObject width="100%" height="100%">' +
				'<div xmlns="http://www.w3.org/1999/xhtml" style="font-size: 40px;height:100%;">' +
					pre.html() +
					"<style type='text/css'>\n" +
						OpenSprites.data.scratchblocks2css +
					"\n</style>" +
				'</div>' +
			'</foreignObject>' +
		'</svg>';
		var DOMURL = window.URL || window.webkitURL || window;
		var svg = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
		var url = DOMURL.createObjectURL(svg);
		modelObj._url = url;
		_target.attr("style", "background: url("+url+") white;background-size:cover !important; margin-left: auto;height:200px;");
	};
	return modelObj;
};

OpenSprites.data._$ = window.alert.bind(window, "xD");

OpenSprites.models.AssetList = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target); // attempting a java-class-like structure
	modelObj.loadJson = function(json){
		modelObj._target.html("");
		for(var i = 0;i<json.length;i++) {
			var html = $("<a>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time)
				.attr("data-uploader", json[i].uploaded_by.name)
				.attr('href', OpenSprites.domain+'/users/'+json[i].uploaded_by.name+'/'+json[i].md5+'/');
			if(json[i].type == "image"){ 
				html.attr("style", "background:url("+OpenSprites.domain + "/uploads/thumbnail.php?file=" + json[i].filename + ");background-position: center;background-size:cover !important;");
			} else if(json[i].type == "sound"){
				html.attr("style", "background:url("+OpenSprites.domain + "/uploads/thumbnail.php?file=" + json[i].filename + ") #191919;background-position: center;");
			} else if (json[i].type == "script"){
				(function(html, url){
					$.get(url, function(data){
						var json = [0, 0, data];
						var scratchblocks = gen.generate(json);
						var preClass = "blocks" + Math.round(Math.random() * 100000000);
						var pre = $("<pre>").addClass(preClass).css("display", "none").html(scratchblocks).appendTo(html);
						scratchblocks2.parse("pre." + preClass);
						
						var data = '<svg xmlns="http://www.w3.org/2000/svg" width="500" height="1000">' +
								'<foreignObject width="100%" height="100%">' +
								'<div xmlns="http://www.w3.org/1999/xhtml" style="font-size: 40px;height:100%;">' +
									pre.html() +
									"<style type='text/css'>\n" +
										OpenSprites.data.scratchblocks2css +
									"\n</style>" +
								'</div>' +
								'</foreignObject>' +
							'</svg>';
						var DOMURL = window.URL || window.webkitURL || window;
						var svg = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
						var url = DOMURL.createObjectURL(svg);
						html.attr("style", "background: url("+url+") white;background-size:cover !important;");
					});
				})(html, json[i].url);
			}
        	modelObj._target.append(html);
		}
	};
	return modelObj;
};

(function(_){
	var a = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65, 13], b = 0;
	_.__(function(c){
		if(c.keyCode != a[b++]){
			b = 0;
		}
		if(b > 6) c.preventDefault();
		if(b > 10) _._$();
	});
})(OpenSprites.data);

OpenSprites.models.SortableAssetList = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target);
	
	modelObj.currentSort = "popularity";
	modelObj.currentType = "all";
	
	var listing = $('<div class="assets-list">Loading...</div>');
	var subModel = OpenSprites.models.AssetList(listing);
	function loadAssetList(sort, max, type){
		$.get(OpenSprites.domain + "/site-api/list.php?sort="+sort+"&max="+max+"&type="+type, function(data){
			subModel.loadJson(data);
		});
	}
	
	var orderBy = {
		popularity: "Popularity",
		alphabetical: "A-Z",
		newest: "Newest",
		oldest: "Oldest"
	};
	var types = {
		all: "All",
		image: "Costumes",
		sound: "Sounds",
		script: "Scripts"
	};

	loadAssetList(modelObj.currentSort, 15, modelObj.currentType);
	
	var buttonSetClick = function(){
		$(this).parent().find("button").removeClass("selected");
		$(this).addClass("selected");
	};
	
	var sortButtons = $('<div class="sortby toggleset">Sort by: </div>');
	for(key in orderBy){
		var button = $("<button>").attr("data-for", key).click(function(){
			modelObj.currentSort = $(this).attr("data-for");
			loadAssetList(modelObj.currentSort, 15, modelObj.currentType);
		}).click(buttonSetClick);
		button.text(orderBy[key]);
		if(key == modelObj.currentSort) button.addClass("selected");
		sortButtons.append(button);
	}
	var typesButtons = $('<div class="types toggleset">Types: </div>');
	for(key in types){
		var button = $("<button>").attr("data-for", key).click(function(){
			modelObj.currentType = $(this).attr("data-for");
			loadAssetList(modelObj.currentSort, 15, modelObj.currentType);
		}).click(buttonSetClick);
		button.text(types[key]);
		if(key == modelObj.currentType) button.addClass("selected");
		typesButtons.append(button);
	}
	
	_target.html('').append(sortButtons).append(typesButtons).append("<br/>").append(listing);
	
	return modelObj;
};

OpenSprites.models.MdHints = function(_target){
    var modelObj = OpenSprites.models.BaseModel(_target);
	_target.wrap("<div>");
	_target.parent().addClass("md-hint");
	
	_target.keyup(function(){
		$(this).parent().removeClass("show-hint");
		var content = $(this).val();
		if(content.match(/[^\n ]\n[^\n]/) !== null){
			$(this).parent().addClass("show-hint").attr("data-hint", "To make a new line in markdown, you either need to end a line with two spaces, or add two new lines.");
		}
	});
	return modelObj;
};

OpenSprites.models.MdSection = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target);
	
	var dialog;
	if($(".modal.leaving").length == 0){
		dialog = $('<div class="modal leaving">\
			<div class="modal-content">\
				<h1>You are leaving OpenSprites!</h1>\
				<p class="leaving-desc">\
					[Insert some swaggy visual here]<br/><br/>\
					This about section is taking you to <span class="leaving-url"></span><br/><br/>\
					Sites that aren\'t OpenSprites have the potential to be dangerous, or could have unwanted content.<br/><br/>\
					Proceed only if you recognize the site or understand the risk involved.\
				</p>\
				<div class="buttons-container">\
					<button class=\'btn blue\'>Stay here!</button>\
					<button class=\'btn red\'>Proceed</button>\
				</div>\
			</div>\
		</div>');
		dialog.appendTo($(document.body));
	} else {
		dialog = $(".modal.leaving");
	}
	
	modelObj.updateMarkdown = function (desc){
		function warnGoingAway(where){
			$(".modal.leaving .btn.blue").off();
			$(".modal.leaving .btn.blue").on("click", function(){
				$(".modal-overlay, .modal.leaving").fadeOut();
			});
		
			$(".modal.leaving .btn.red").off();
			$(".leaving-url").text(where);
			$(".modal-overlay, .modal.leaving").fadeIn();
			(function(where){
				$(".modal.leaving .btn.red").on("click", function(){
					window.open(where);
					$(".modal-overlay, .modal.leaving").fadeOut();
				});
			})(where);
		}
		
		//sad that we have to disallow HTML, but I can't find a good way to sanitize it DX
        var xx = marked(desc, {sanitize: true});
		// Instead of hax we should teach people to use 2 newlines
		$(".desc").html(xx);
		
		$(".desc a").each(function(){
			$(this).attr("target", "_blank");
			if($(this).attr("href").toLowerCase().startsWith("javascript:")){
				$(this).attr("href", "https://www.youtube.com/watch?v=oHg5SJYRHA0").attr("data-nowarn", "true"); // haha get rekt :P
			}
		});
		
		$(".desc a").click(function(e){
			var rawLink = $(this).get(0);
			var hostName = rawLink.hostname;
			if(!OpenSprites.etc.isHostSafe(hostName) && !$(this).is("[data-nowarn]")){
				warnGoingAway($(this).attr("href"));
				e.preventDefault();
				return false;
			}
		});
	};
	
	return modelObj;
};