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
    <!--Imports site-wide main styling-->
    <link href='main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
        
        <?php if($logged_in_user == 'not logged in') { ?>
        <!-- Info for new visistors. -->
            <div class="welcome">
                <table>
                 <tr>
                 <td width="260">
                  <img src="/assets/images/sharingcats.png" width="200" height="100" id="cats">
                 </td>
                 <td valign="top">
                  <h2 class="welcome-title">Share your Scratch resources with the world!</h2>
                  <p class="welcome-text">OpenSprites allows users to share their sprites, scripts, costumes and other Scratch-related resources for others to easily download and use.</p>
                  <a href="register" class="btn">Create a free account</a>
                 </td>
                 </tr>
                </table>
        </div>
        <?php } ?>

            <div id="about">
                <h2>Welcome!</h2>
                <p>Welcome to OpenSprites, the free, open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own scripts, sprites and project media!</p>
            </div>
            <div id="top-assets">
                <div class="box">
                    <h1>Top Assets</h1>
                    <div class="box-content">
                        <div class="sortby toggleset">Sort by: </div><div class="types toggleset">Types: </div><br/>
                        <div id="top-assets-list" class='assets-list' data-sort='popularity' data-type='all'>Loading...</div>
                    </div>
                </div>
            </div>

            <div id="feat-assets">
                <div class="box">
                    <h1>Featured Assets</h1>
                    <div class="box-content">
                        <div class="sortby toggleset">Sort by: </div><div class="types toggleset">Types: </div><br/>
                        PHP guys, get in here!
                    </div>
                </div>
            </div>
			
			<div id="top-collections">
                <div class="box">
                    <h1>Top Collections</h1>
                    <div class="box-content">
                        PHP guys, get in here!
                    </div>
                </div>
            </div>
			
			<div id="feat-collections">
                <div class="box">
                    <h1>Featured Collections</h1>
                    <div class="box-content">
                        PHP guys, get in here!
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div id="teams-favorite">
           <div class="box">
               <h1>Featured Scripts and Sprites</h1>
                   <div class="box-content">
                        <p>The OS Team will choose two scripts and two sprites daily.
                        </p>
                        PHP guys, get in here!
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    
    <!-- footer -->
    <?php echo file_get_contents('footer.html'); ?>
	<script>
		var topAssetsListModel = OpenSprites.models.AssetList($("#top-assets-list"));
		function loadAssetList(elem, sort, max, type){
			elem = $(elem);
			$.get(OpenSprites.domain + "/site-api/list.php?sort="+sort+"&max="+max+"&type="+type, function(data){
				topAssetsListModel.loadJson(data);
			});
		}
		loadAssetList("#top-assets-list", "popularity", 15, "all");
	
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
		$(".sortby").each(function(){
			var listing = $(this).parent().find('.assets-list');
			for(key in orderBy){
				var button = $("<button>").attr("data-for", key).click(
					(function(listing, key){
						return function(){
							listing.attr("data-sort", key);
							loadAssetList(listing, key, 15, listing.attr("data-type"));
						};
					})(listing, key));
				button.text(orderBy[key]);
				if(key == "popularity") button.addClass("selected");
				$(this).append(button);
			}
		});
		$(".types").each(function(){
			var listing = $(this).parent().find('.assets-list');
			for(key in types){
				var button = $("<button>").attr("data-for", key).click(
					(function(listing, key){
						return function(){
							listing.attr("data-type", key);
							loadAssetList(listing, listing.attr("data-sort"), 15, key);
						};
					})(listing, key));
				button.text(types[key]);
				if(key == "all") button.addClass("selected");
				$(this).append(button);
			}
		});
		
		$(".toggleset button").click(function(){
			$(this).parent().find("button").removeClass("selected");
			$(this).addClass("selected");
		});
	</script>
</body>
</html>
