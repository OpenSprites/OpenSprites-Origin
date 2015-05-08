$(".file_rename").click(function(){
	$("#file-name").val(OpenSprites.view.file.name);
	$("#file-desc").val(OpenSprites.view.file.description);
	$("#file-rename-dialog .input-error").text("Sample Text").css("opacity", "0");
	$(".modal-overlay, .modal").fadeIn();
});

$("#file-name").keyup(function(){
	if($(this).val().length > 32){
		$(".modal .input-error").text("You must enter a name for your resource less than 32 characters long").css("opacity", "1");
	} else {
		$(".modal .input-error").text("Sample Text").css("opacity", "0");
	}
});

$("#file-desc").keyup(function(){
	if($(this).val().length > 500){
		$(".modal .input-error").text("You must enter a description for your resource less than 500 characters long").css("opacity", "1");
	} else {
		$(".modal .input-error").text("Sample Text").css("opacity", "0");
	}
});


$(".modal .btn.red").click(function(){
	$(".modal-overlay, .modal").fadeOut();
});

console.log('debug');
$(".modal .btn.blue").click(function(){
	console.log(this);
	
	$(".modal .input-error").text("Sample Text").css("opacity", "0");
	var name = $("#file-name").val();
	var desc = $("#file-desc").val();
	if(name == null || name == "" || name.length > 32){
		$(".modal .input-error").text("You must enter a name for your resource less than 32 characters long").css("opacity", "1");
		return;
	}
	
	if(desc == null || desc == "" || desc.length > 500){
		$(".modal .input-error").text("You must enter a description for your resource less than 500 characters long").css("opacity", "1");
		return;
	}
	
	$(".modal .rename-status").text("Saving...").parent().fadeIn();
	
	$.get("/uploads/edit.php", {hash: OpenSprites.view.file.md5, title: name, description: desc, userid: OpenSprites.view.file.uploaded_by.id}, function(data){
		// userid is ignored unless you're an admin, in which case we need to know it
		$(".modal .dialog-overlay").fadeOut();
		if(typeof data != "object"){
			$(".modal .input-error").text("Whoops! Our servers sent back a bad response. Try again later.").css("opacity", "1");
			return;
		}
		
		if(data.status != "success"){
			$(".modal .input-error").text(data.message).css("opacity", "1");
			return;
		}
		
		OpenSprites.view.file.name = data.title;
		$(".asset-name").html(data.title);
		OpenSprites.view.file.description = data.description;
		$(".desc").html(data.description);
		
		$(".modal-overlay, .modal").fadeOut();
	}).fail(function(){
		$(".modal .dialog-overlay").fadeOut();
		$(".modal .input-error").text("Whoops! A problem prevented us from receiving a response from our servers.").css("opacity", "1");
	});
});

$(".image-url").focus(function(){
    this.select();
});
