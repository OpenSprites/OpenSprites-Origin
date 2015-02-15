<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/global_functions.php";
	
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
	<?php echo "<img id='background-img' src='bg/" . $username . "_custom.png' "; ?>onerror='this.src="bg/default.png"'>
	<div id='dark-overlay'>
		<div id="user-pane-right">
			<div id='username'>
				<?php echo $username; ?>
			</div>
		</div>
		<div id="user-pane-left">
			<?php
				display_user_avatar($username, 'x100');
			?>
		</div>
	</div>

	<div class="container main" id="collections">
		<div class="main-inner">
			<h1>Collections</h1>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
