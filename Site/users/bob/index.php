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
	<?php include "../user.php?username=bob"; ?>
	
	<!-- footer -->
	<?php echo file_get_contents('../../footer.html'); ?>
</body>
</html>
