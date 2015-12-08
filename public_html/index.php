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
    <!-- Spider web easter egg for Halloween theme-->
    <!--<img src="https://garretson.k12.sd.us/teacherresources/SiteAssets/SitePages/The%20Web!/white-spider-web-clipart-bcyE6k8Ki.gif" id="spiderweb" style="height:100px;width:100px;visibility:hidden;">-->
    
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
		<div class="welcome info">
			<div class="symbol warning" style="color:rgb(70, 103, 101);font-size:109px;float:left;margin-right:70px;margin-left:70px;margin-top:-1px;"></div>
			<h2 class="welcome-title" style="padding-left:10px;">OpenSprites is in development!
			<a href="javascript:void(0)" class="btn" style="float:right;font-size:0.5em;" onclick="$('.welcome.info').slideUp()">Dismiss</a>
			</h2>
			<p class="welcome-text" style="display:block;padding-left:10px;">The OpenSprites Team is still in the process of creating this site. This means that OpenSprites may not yet have all the stuff you want, and it may also have bugs, so be careful!<br>Stay tuned for more updates on our <a href="/blog" style="color:white;text-decoration:underline">blog</a>!</p>
		</div>
        </div>
        
		<!-- We don't really need this any more because the site purpose is explained below.
        <?php if($logged_in_user == 'not logged in') { ?>
        <!-- Info for new visitors.
		<div class="welcome signup">
			<table>
				<tr>
					<td width="260" style='text-align: center;'>
						<span class='symbol paperclip' style='color:rgb(70, 103, 101);font-size:109px;'></span>
					</td>
					<td valign="top">
						<h2 class="welcome-title">Share your Scratch resources with the world!
							<a href="javascript:void(0)" class="btn" style="float:right;font-size:0.5em;" onclick="$('.welcome.signup').slideUp()">Dismiss</a>
						</h2>
						<p class="welcome-text">OpenSprites allows users to share their sprites, scripts, costumes and other Scratch-related resources for others to easily download and use.</p>
						<a href="/register" class="btn">Create a free account</a>
					</td>
				</tr>
			</table>
        </div>
        <?php } ?>
		-->

            <div id="about">
                <h2 class="centered-heading">Welcome!</h2>
                <p>Welcome to OpenSprites, the free open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own creations, such as scripts, sprites, sounds, and costumes! Designed with children in mind, we promote the idea of creating and sharing, and provide a child-safe platform for this. So, what are you waiting for? Get creating and sharing!</p>
            </div>
            
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
