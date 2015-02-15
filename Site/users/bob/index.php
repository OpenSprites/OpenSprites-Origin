<?php
	require "../../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../../Header.html'); ?>
</head>
<body>
	<!--Imports styling-->
	<link href='../../main-style.css' rel='stylesheet' type='text/css'>
	<link href='../user-style.css' rel='stylesheet' type='text/css'>
	
	<!--Imports navigation bar-->
	<?php include "../../navbar.php"; ?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1 id="opensprites-heading">Todo</h1>
			<div id="about">
				<h2>Welcome!</h2>
				<p>Welcome to OpenSprites, the website that is designed for members of the <a href="http://www.scratch.mit.edu">Scratch</a> community to upload and share scripts, sprites, pictures, and more!</p>
			</div>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../../footer.html'); ?>
</body>
</html>
