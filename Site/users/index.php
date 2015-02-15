<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	$username = $_GET['username'];
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../Header.html'); ?>
	
	<!--Imports styling-->
	<link href='user_style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	<!--Imports navigation bar-->
	<?php include "../navbar.php"; ?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<?php echo "<img id='background-img' src='" . $username . "_bg_img.png'>";
			<div id='dark-overlay'>
				<div id='username'>
					<?php echo $username ?>
				</div>
			</div>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
