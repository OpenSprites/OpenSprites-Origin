$(".file_rename").click(function(){
	$("#file-name").val(OpenSprites.view.file.name);
	$("#file-desc").val(OpenSprites.view.file.description);
	$("#file-rename-dialog .input-error").text("");
	$(".modal-bg, #file-rename-dialog").fadeIn();
});

$("#file-rename-dialog .cancel").click(function(){
	$(".modal-bg, #file-rename-dialog").fadeOut();
});

$("#file-rename-dialog .ok").click(function(){
	$("#file-rename-dialog .input-error").text("");
	var name = $("#file-name").val();
	var desc = $("#file-desc").val();
	if(name == null || name == "" || name.length > 32){
		$("#file-rename-dialog .input-error").text("You must enter a name for your file less than 32 characters long");
		return;
	}
	
	if(desc == null || desc == "" || desc.length > 500){
		$("#file-rename-dialog .input-error").text("You must enter a description for your file less than 500 characters long");
		return;
	}
	
	$("#file-rename-dialog .rename-status").text("Saving...").parent().fadeIn();
	
	$.get("/uploads/edit.php", {hash: OpenSprites.view.file.md6, title: name, description: desc}, function(data){
		$("#file-rename-dialog .dialog-overlay").fadeOut();
		if(typeof data != "object"){
			$("#file-rename-dialog .input-error").text("Whoops! Our servers sent back a bad response. Try again later.");
			return;
		}
		
		if(data.status != "success"){
			$("#file-rename-dialog .input-error").text(data.message);
			return;
		}
		
		OpenSprites.view.file.name = data.title;
		OpenSprites.view.file.description = data.description;
		
		$(".modal-bg, #file-rename-dialog").fadeOut();
	}).fail(function(){
		$("#file-rename-dialog .dialog-overlay").fadeOut();
		$("#file-rename-dialog .input-error").text("Whoops! A problem prevented us from receiving a response from our servers.");
	});
});