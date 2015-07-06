if(OpenSprites.view.isCreatingCollection){
	$("#create-collection").on("submit", function(e){
		e.preventDefault();
		var name = $("#collection-name").val();
		if(name == null || name.trim() == "") return false;
		
		$("#create-collection input, #create-collection button").attr("disabled", "disabled");
		$("#create-collection .status").text("Creating collection...");
		
		$.post("/site-api/collection_create.php", {name: name}, function(data){
			$("#create-collection input, #create-collection button").removeAttr("disabled");
			if(data['status'] != "success"){
				$("#create-collection .status").text("Uh oh! There was a problem creating your collection: " + data['message']);
			} else {
				var cid = data['collection_id'];
				var collectionLocation = "/users/" + OpenSprites.user.id + "/collection/" + cid + "/";
				$("#create-collection .status").html("Click <a href='" + collectionLocation + "'>here</a> to continue.");
				location.href = collectionLocation;
			}
		}).fail(function(){
			console.log(arguments);
			$("#create-collection .status").text("Whoops! We couldn't contact OpenSprites servers. Try again later.");
			$("#create-collection input, #create-collection button").removeAttr("disabled");
		});
		
		return false;
	});
} else {
	
}