<?php
	require "assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('Header.html'); ?>
</head>
<body>
	<!--Imports site-wide main styling-->
	<link href='main-style.css' rel='stylesheet' type='text/css'>
	
	<!--Imports navigation bar-->
	<?php include "navbar.php"; ?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1 id="opensprites-heading">OpenSprites - Share Sprites, Scripts, and More!</h1>
			<div id="about">
				<h2>Welcome!</h2>
				<p>Welcome to OpenSprites, the free, open-source site that allows <a href='//scratch.mit.edu'>Scratch</a> users to share their own scripts, sprites and project media!</p>
			</div>
			<div id="top-sprites">
				<div class="box">
					<h1>Top Sprites</h1>
					<div class="box-content">
						<p>Sort by: 
							<select id="sortby">
								<option>Popularity (downloads)</option>
								<option>Ratings</option>
								<option>A-Z</option>
								<option>Newest</option>
								<option>Oldest</option>
							</select>
						</p>
						PHP guys, get in here!
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('footer.html'); ?>
</body>
</html>
