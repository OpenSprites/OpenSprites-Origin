<?php
	require "../../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include '../../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
</head>
<body>
	<!--Imports site-wide main styling-->
	<link href='/main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			Include some swag about search here.
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../../footer.html'); ?>
</body>
</html>
