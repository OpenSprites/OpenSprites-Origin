<?php
	require __DIR__."/../includes/connect.php";  //Connect - includes session_start();
	
	$mtype = $_GET['type'];
	
	$allowed_types = array("media", "script", "collections");
	
	$descriptions = array(
		"media" => "Media means any image or sound that you can use in a Scratch project. Find new and awesome media from OpenSprites users here.",
		"scripts" => "Browse Scratch scripts from OpenSprites users, and download them for your own projects.",
		"collections" => "Collections are groups of media and scripts. Some collections are meant to be used as sprites out of the box, others may just be a way of grouping media and scripts that have something in common. You can find new and awesome collections from OpenSprites users here!");
	
	if(!in_array($mtype, $allowed_types)){
		include "404.php";
		die();
	}
	
	$displayType = ucwords($mtype);
	if($displayType == "Script") $displayType = "Scripts";
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include 'Header.html'; ?>
</head>
<body>
	<script>
		OpenSprites.view = OpenSprites.view || {};
		OpenSprites.view.browseType = <?php echo json_encode($mtype); ?>;
	</script>

    <!--Imports site-wide main styling-->
    <link href='main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
            <div id="about">
                <h2 class="centered-heading"><?php echo $displayType; ?></h2>
                <p><?php echo $descriptions[$mtype]; ?></p>
			</div>
			
			
            <div id="feat-assets">
                <div class="box">
                    <h1>Featured <?php echo $displayType; ?></h1>
                    <div class="box-content assets-list" id="feat-assets-list">
						<p style="text-align: center;">Loading content, hang tight.<br/></p>
                    </div>
                </div>
            </div>
			
            <div id="top-assets">
                <div class="box">
                    <h1>Popular <?php echo $displayType; ?> From the Past Week</h1>
                    <div class="box-content assets-list" id="top-assets-list">
                        <p style="text-align: center;">Loading content, hang tight.<br/></p>
                    </div>
                </div>
            </div>
			
			<div id="new-assets">
                <div class="box">
                    <h1>Newest <?php echo $displayType; ?></h1>
                    <div class="box-content assets-list" id="new-assets-list">
                        <p style="text-align: center;">Loading content, hang tight.<br/></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('footer.html'); ?>
	<script>
		var models = {
			"featured": {
				target: $("#feat-assets-list"),
				params: {type: OpenSprites.view.browseType, sort: "featured", max: 15}
			},
			"top": {
				target: $("#top-assets-list"),
				params: {type: OpenSprites.view.browseType, sort: "popularity", max: 15}
			},
			"new": {
				target: $("#new-assets-list"),
				params: {type: OpenSprites.view.browseType, sort: "newest", max: 15}
			}
		};
		
		var apiEndpoint = "/site-api/list.php";
		
		for(key in models){
			var modelObj = models[key];
			var model = OpenSprites.models.AssetList(modelObj.target);
			modelObj.model = model;
			$.get(apiEndpoint, modelObj.params, (function(model){
				return function(data){
					model.loadJson(data);
				};
			})(model));
		}
		
		setInterval(function(){
			for(key in models){
				var model = models[key].model;
				var params = models[key].params;
				$.get(apiEndpoint, params, (function(model){
					return function(data){
						model.loadJson(data);
					};
				})(model));
			}
		}, 1000 * 60 * 2);
	</script>
</body>
</html>
