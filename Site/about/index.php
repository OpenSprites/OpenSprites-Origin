<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include '../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
</head>
<body>
	<!--Imports site-wide main styling-->
	<link href='../main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
		<h2 id="about-title">About us</h2>
            <p>OpenSprites is a free, open-source site made by the <a href="https://github.com/OpenSprites/OpenSprites/blob/master/README.md" target="_blank">OpenSprites team</a> that allows <a href='https://scratch.mit.edu'>Scratch</a> users to share their own scripts, sprites and project media. The site is almost entirely open-source, meaning that you can examine, modify and share our code without running into trouble. If you'd like to take a look at our source code, head over to our <a href="https://github.com/OpenSprites">GitHub organization</a>.</p>
		
		<!-- Move statistics to its own page
		<p>Statistics:</p>
		<p>Users on OpenSprites: <?php /* lol u serious? */ echo "32"; ?></p>
		<p>Total Sprites shared: <?php echo "321"; ?></p>
		<p>Total Scripts shared: <?php echo "193"; ?></p> -->
    </div>
	</div>

	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
