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
	<div class="main container" id="about">
        <h2 class="centered-heading">Share your Scratch resources with the world!</h2>
        <p class="welcome-text">Spice up your Scratch projects with awesome sounds, costumes, backdrops and more - made by people just like you. OpenSprites is the place where Scratchers can upload and download super-cool resources to use in their projects, with new stuff being added daily by users all over the globe. All you need to do to get started is sign up!</p>
		<a href="/register" class="btn">Create a free account</a>
    </div>
    <?php } ?>

    <?php if($logged_in_user != 'not logged in') { ?>
    <!-- Info for logged in visitors. -->
  <div class="main container" id="about">
        <h2 class="centered-heading">Hey, <?php echo $logged_in_user?>!</h2>
        <p class="welcome-text">Check out today's popular resources below...</p>
    </div>
    <?php } ?>

  <div class="main container" id="top-assets">
        <h2 class="centered-heading">Top Resources</h2>
        <div class="box-content assets-list" id="top-assets-list"></div>
    </div>

    <div class="main container" id="feat-assets">
        <h2 class="centered-heading">Featured Resources</h2>
        <div class="box-content assets-list" id="feat-assets-list"></div>
    </div>

    <!-- footer -->
    <?php echo file_get_contents('footer.html'); ?>
	<script>
		var modelPopularity = OpenSprites.models.AssetList($("#top-assets-list"));
		$.get("/site-api/list.php?max=8&sort=popularity&type=all", function(data){
		modelPopularity.loadJson(data);
		});

        var modelFeatured = OpenSprites.models.AssetList($("#feat-assets-list"));
        $.get("/site-api/list.php?max=8&sort=featured&type=all", function(data){
            modelFeatured.loadJson(data);
        });
		//var model = OpenSprites.models.AssetList($("#feat-assets-list"));
		//var model = OpenSprites.models.AssetList($("#top-collections-list"));
		//var model = OpenSprites.models.AssetList($("#featured-collections"));
	</script>
</body>
</html>
