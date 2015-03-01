<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../Header.html'); ?>
	
	<!--Imports styling-->
	<link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	<!--Imports navigation bar-->
	<?php include "../navbar.php"; ?>
	
	<!-- Main wrapper -->
	<div class="container main" style="text-align:center;">
		<div class="main-inner" style="padding-bottom: 50px;">
			<h1 style="font-size:4em;margin-top:50px;">Choose an upload method:</h1>
		</div>
		<img src="fromLocal.png" class="method" onclick="window.location = 'local/';"><img src="fromScratch.png" class="method" onclick="window.location = 'scratch/';">
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
