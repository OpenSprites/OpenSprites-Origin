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
		
		
	</body>
</html>