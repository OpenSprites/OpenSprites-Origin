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
		
		<div class="welcome info">
			<table>
				<tr>
					<td width="260">
						<img src="/assets/images/indev.png" width="200" height="100" id="cats">
					</td>
					<td valign="top">
						<h2 class="welcome-title">OpenSprites is in development!
							<a href="javascript:void(0)" class="btn" style="float:right;font-size:0.5em;" onclick="$('.welcome.info').slideUp()">Dismiss</a>
						</h2>
						<p class="welcome-text">The OpenSprites Team is still in the process of creating this site. This means that OpenSprites may not yet have all the stuff you want, and it may also have bugs, so be careful!<br>Stay tuned for more updates on our <a href="/blog" style="color:white;text-decoration:underline">blog</a>!</p>
					</td>
				</tr>
			</table>
        </div><br/><br/>
        
        <?php if($logged_in_user == 'not logged in') { ?>
        <!-- Info for new visistors. -->
		<div class="welcome signup">
			<table>
				<tr>
					<td width="260">
						<img src="/assets/images/sharingcats.png" width="200" height="100" id="cats">
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

            <div id="about">
                <h2>Welcome!</h2>
                <p>Welcome to OpenSprites, the free, open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own scripts, sprites and project media! Please note that the site is still in alpha stages, and may be buggy, incomplete or otherwise unworthy for intended use. Come back soon!</p>
            </div>
            <div id="top-assets">
                <div class="box">
                    <h1>Top Assets</h1>
                    <div class="box-content" id="top-assets-list">
                        
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
		var model = OpenSprites.models.SortableAssetList($("#top-assets-list"));
	</script>
</body>
</html>
