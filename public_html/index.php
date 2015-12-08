<?php
    require "assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include 'Header.html'; ?>
</head>
<body>
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
    
    <!-- Main wrapper -->
    <?php if($logged_in_user == 'not logged in') { ?>
    <!-- Info for new visitors. -->
	<div class="main container" id="top-assets">
        <h2 class="centered-heading">Share your Scratch resources with the world!</h2>
        <p class="welcome-text">OpenSprites allows users to share their sprites, scripts, costumes and other Scratch-related resources for others to easily download and use.</p>
		<a href="/register" class="btn">Create a free account</a>
    </div>
    <?php } ?>

	<div class="main container" id="about">
        <h2 class="centered-heading">Welcome!</h2>
        <p>Welcome to OpenSprites, the free open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own creations, such as scripts, sprites, sounds, and costumes! Designed with children in mind, we promote the idea of creating and sharing, and provide a child-safe platform for this. So, what are you waiting for? Get creating and sharing!</p>
    </div>
        
	<div class="main container" id="top-assets">
        <h2 class="centered-heading">Top Media</h2>
        <div class="box-content assets-list" id="top-assets-list"></div>
    </div>
    
    <div class="main container" id="feat-assets">
        <h2 class="centered-heading">Featured Assets</h2>
        <div class="box-content assets-list" id="top-assets-list"></div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('footer.html'); ?>
	<script>
		var modelPopularity = OpenSprites.models.AssetList($("#top-assets-list"));
		$.get("/site-api/list.php?max=15&sort=popularity&type=all", function(data){
		modelPopularity.loadJson(data);
		});

        var modelFeatured = OpenSprites.models.AssetList($("#feat-assets-list"));
        $.get("/site-api/list.php?max=15&sort=featured&type=all", function(data){
            modelFeatured.loadJson(data);
        });
		//var model = OpenSprites.models.AssetList($("#feat-assets-list"));
		//var model = OpenSprites.models.AssetList($("#top-collections-list"));
		//var model = OpenSprites.models.AssetList($("#featured-collections"));
	</script>
</body>
</html>
