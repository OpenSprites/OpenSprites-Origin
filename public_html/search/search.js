// utility
jQuery.extend({
  getQueryParameters : function(str) {
	  return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
  }
});

// advanced search expanding thingy
$(".fold.advanced-search .fold-toggle").click(function(){
	var fold = $(".fold.advanced-search");
	
	if(fold.hasClass("never-opened")){
		if($("#search-bar-input").val() !== "" && $("#search-bar-input").val() !== null){
			if(!confirm("Your current search will be cleared. Continue?")) return;
		}
		fold.removeClass("never-opened");
	}
	
	if(fold.hasClass("open")) fold.removeClass("open").addClass("closed");
	else fold.removeClass("closed").addClass("open");
});

// define search parameters
var SearchParams = {
	sort: "relevance",
	dir: "desc",
	place: "both",
	filter: "all",
	limit: 20,
	page: 0,
	query: OpenSprites.view.query
};
var totalPages = 0;

// set inputs to match query string
$("#search-input, #search-bar-input").val(OpenSprites.view.query);
$("#search-input").attr("disabled", "disabled");

function joinTerms(terms, prefix, suffix){
	var temp = [];
	for(var i = 0; i < terms.length; i++){
		if(terms[i] != null && terms[i] != "") temp.push(terms[i]);
	}
	terms = temp;
	if(terms.length == 0) return "";
	var joined = prefix + terms[0] + suffix;
	for(var i = 1; i < terms.length; i++){
		joined += " " + prefix + terms[i] + suffix;
	}
	return joined;
}

// advanced search
function buildQuery(){
	var someOf = $(".advanced-input.some-words").val();
	var allOf = $(".advanced-input.all-words").val();
	var noneOf = $(".advanced-input.no-words").val();
	
	someOf = someOf.split(" ").map(function(item){
		return item.replace(/[^a-zA-Z0-9]/g, "");
	});
	someOf = joinTerms(someOf, "", "");
	
	allOf = allOf.split(" ").map(function(item){
		return item.replace(/[^a-zA-Z0-9]/g, "");
	});
	allOf = joinTerms(allOf, "+", "");
	
	noneOf = noneOf.split(" ").map(function(item){
		return item.replace(/[^a-zA-Z0-9]/g, "");
	});
	noneOf = joinTerms(noneOf, "-", "");
	
	var prefixWords = [];
	$(".advanced-input.prefix-words").each(function(){
		prefixWords.push($(this).val());
	});
	prefixWords = prefixWords.map(function(item){
		return item.replace(/[^a-zA-Z0-9]/g, "");
	});
	prefixWords = joinTerms(prefixWords, "", "*");
	
	var literalWords = [];
	$(".advanced-input.literal-words").each(function(){
		var val = $(this).val().replace(/[^a-zA-Z0-9 ]/g, "");
		if(val !== "") literalWords.push("\"" + val + "\"");
	});
	literalWords = literalWords.join(" ");
	
	return [someOf, allOf, noneOf, prefixWords, literalWords].join(" ");
}

function registerAdvancedSearchHandlers(){
	$(".advanced-input").off().on("keyup", function(){
		var query = buildQuery();
		$("#search-input, #search-bar-input").val(query);
	});
	
	$(".duplicatable .buttons.minus").off().on("click", function(){
		if($(this).parent().parent().find(".duplicatable").length < 2) return;
		$(this).parent().remove();
	});
	
	$(".duplicatable .buttons.plus").off().on("click", function(){
		var clone = $(this).parent().clone();
		clone.find("input").val("");
		clone.insertAfter($(this).parent());
		
		registerAdvancedSearchHandlers();
	});
}

registerAdvancedSearchHandlers();

// parameters
$("#search-buttonsets .toggleset button").click(function(){
	$(this).parent().find("button").removeClass("selected");
	$(this).addClass("selected");
	
	SearchParams[$(this).attr("data-key")] = $(this).attr("data-value");
	SearchParams.page = 0;
	
	doSearch();
});

// pagination
function createPageButton(content, page, selected, nth){
	if(!nth){
		var button = $("<button>").addClass("page-button").text(content).attr("data-page", page).click(function(){
			$(this).parent().find("button").removeClass("selected");
			$(this).addClass("selected");
			SearchParams.page = parseInt($(this).attr("data-page"));
			doSearch();
		}).appendTo($(".pagination.toggleset"));
		if(selected) button.addClass("selected");
	} else {
		$("<button>").addClass("page-button").text(content).click(function(){
			$(this).parent().find("button").removeClass("selected");
			$(this).addClass("selected");
			
			var page = prompt("Which page?", page);
			if(page == null || page == "") return;
			
			page = parseInt(page) - 1;
            if(page < 0) page = 0;
            if(page > numPages - 1) page = numPages - 1;
            SearchParams.page = page;
			doSearch();
		}).appendTo($(".pagination.toggleset"));
	}
}

function setPages(currentPage, pages){
    if(typeof currentPage === "string") currentPage = parseInt(currentPage);
    if(typeof pages === "string") pages = parseInt(pages);
    numPages = pages;
	$(".pagination.toggleset button").remove();
	
	if(currentPage == 0 && pages == 1) return;
	
	console.log(currentPage, pages);
	
	if(currentPage == 0){
		createPageButton("1", 0, true, false);
	} else if(currentPage == 1){
		createPageButton("<", 0, false, false);
		createPageButton("1", 0, false, false);
		createPageButton("2", 1, true, false);
	} else if(currentPage == 2){
		createPageButton("<", 0, false, false);
		createPageButton("1", 0, false, false);
		createPageButton("2", 2, false, false);
		createPageButton("3", 3, true, false);
	} else {
		createPageButton("<<", 0, false, false);
		createPageButton("<", currentPage - 1, false, false);
		createPageButton("" + (currentPage - 2 + 1), currentPage - 2, false, false);
		createPageButton("" + (currentPage - 1 + 1), currentPage - 1, false, false);
		createPageButton("" + (currentPage + 1), currentPage, true, false);
	}
	
	if(currentPage == pages - 1) {
	} else if(currentPage == pages - 2){
		createPageButton("" + (currentPage + 1 + 1), currentPage + 1, false, false);
		createPageButton(">", currentPage + 1, false, false);
	} else {
		createPageButton("" + (currentPage + 1 + 1), currentPage + 1, false, false);
		createPageButton("" + (currentPage + 2 + 1), currentPage + 2, false, false);
		createPageButton(">", currentPage + 1, false, false);
		createPageButton(">>", pages - 1, false, false);
	}
	
	createPageButton("Go to...", -1, false, true);
}

// actually perform search
function doSearch(){
		var query = $("#search-bar-input").val();
		if(query == null || query == "" || typeof query == "undefined"){
			$(".search-results-content").html("Enter a search query.");
			$(".search-results").removeClass("loading");
			$(".search-header").text("No results");
			return;
		}
		
		if(history.pushState && arguments.length === 0){
			history.pushState({query: query}, '', '/search/?q=' + encodeURIComponent(query));
		}
		
		SearchParams.query = query;
		OpenSprites.view.query = query;
		$("#search-input").val(OpenSprites.view.query);
		
		$(".search-header").html("Loading...");
		$(".search-results").addClass("loading");
		$.get("/site-api/search.php", SearchParams, function(data){
			console.log(data);
			$(".search-results-content").html("");
			$(".search-results").removeClass("loading");
			$(".search-header").text(data.message);
			
			setPages(SearchParams.page, Math.ceil(data.num_results / SearchParams.limit));
			
			if(data.warning.length > 0){
				for(var i=0;i<data.warning.length;i++){
					$(".search-popup .search-message").append("<br/>").append($("<span>").addClass("search-link").text(data.warning[i]));
				}
			}
			for(var i = 0; i < data.results.length; i++){
				var result = data.results[i];
				var resultRow = $("<p>").addClass("result");
				
				if(result.type == "image" || result.type == "sound"){ 
					$("<img />").addClass("search-preview").attr("href", "/uploads/thumbnail.php?file=" + result.filename).appendTo(resultRow);
				} else if (result.type == "script"){
					(function(url){
						$.get(url, function(data){
							var json = [0, 0, data];
							var scratchblocks = gen.generate(json);
							var preClass = "blocks" + Math.round(Math.random() * 100000000);
							var pre = $("<pre>").addClass(preClass).css("display", "none").html(scratchblocks).appendTo($("body"));
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
							$("<div>").addClass("search-preview").attr("style", "background: url("+url+") white;background-size:cover !important;").appendTo(resultRow);
							pre.remove();
						});
					})(result.url);
				}
				
				resultRow.append($("<a>").attr("href", "/users/" + result.uploaded_by.id + "/" + result.md5 + "/").text(result.name));
				resultRow.append("<br/>By: ");
				resultRow.append($("<a>").attr("href", "/users/" + result.uploaded_by.id).text(result.uploaded_by.name));
				$(".search-results-content").append(resultRow);
			}
		}).fail(function(){
			$(".search-results").removeClass("loading");
			$(".search-results-content").html("Sorry about that. Try again later.");
			$(".search-header").text("Search failed!");
		});
};

// HTML5 swag
if(history.pushState){
	window.onpopstate = function(e){
		var params = $.getQueryParameters();
		if(typeof e.state !== 'undefined' && e.state !== null && e.state.hasOwnProperty("query")){
			var query = e.state.query;
			SearchParams.query = query;
			SearchParams.page = 0;
			OpenSprites.view.query = query;
			$("#search-input, #search-bar-input").val(OpenSprites.view.query);
			doSearch(true);
		} else if(params.hasOwnProperty("q")){
			var query = params.q;
			SearchParams.query = query;
			SearchParams.page = 0;
			OpenSprites.view.query = query;
			$("#search-input, #search-bar-input").val(OpenSprites.view.query);
			doSearch(true);
		}
	};
	history.replaceState({query:OpenSprites.view.query}, '', '/search/?q=' + encodeURIComponent(OpenSprites.view.query));
}

// register search event handlers
$(".search-button").click(function(){
	SearchParams.page = 0;
	doSearch();
});

$("#search-bar-input").keyup(function(e){
	if(e.which == 13){
		SearchParams.page = 0;
		doSearch();
	}
});

// on load, run the search immediately
doSearch();