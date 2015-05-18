$(".fold.advanced-search .fold-toggle").click(function(){
	var fold = $(".fold.advanced-search");
	if(fold.hasClass("open")) fold.removeClass("open").addClass("closed");
	else fold.removeClass("close").addClass("open");
});