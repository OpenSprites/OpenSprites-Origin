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
                <p>Welcome to OpenSprites, the free open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own creations, such as scripts, sprites, sounds, and costumes! Designed with kids in mind, we promote the idea of creating and sharing, and provide a kid-safe platform for this. So, what are you waiting for? Get creating and sharing!</p>
            </div>
            
			<div id="General Assets" class="box">
                    <h1>General Media</h1>
                    <div class="box-content" id="general-assets-list"></div>
            </div>

            <div id="feat-assets" class="box">
                    <h1>Featured Media</h1>
                    <div class="box-content" id="feat-assets-list"></div>
            </div>
			
			<div id="top-collections" class="box">
                    <h1>Top Collections</h1>
					<div class="box-content" id="top-collections-list"></div>
            </div>
			
			<div id="feat-collections" class="box">
                    <h1>Featured Collections</h1>
                    <div class="box-content" id="featured-collections"></div>
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
		var model = OpenSprites.models.SortableAssetList($("#general-assets-list"));
	</script>
</body>
</html>
