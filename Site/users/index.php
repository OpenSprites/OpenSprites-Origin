<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
	
	// capitalise username correctly
	$username = $_GET['username'];
	$raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username . "/");
	$user_arr = json_decode($raw_json, true);
	$username = $user_arr["username"];
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
	<?php echo "<div id='background-img'></div>" ?>
	<div id='dark-overlay'><div id='overlay-inner'>
		<div id="user-pane-right">
			<div id='username'>
				<?php echo $username; ?>
			</div>
			<div id='description'>
				blah blah blah
			</div>
			<div id='follow'>
				follow
			</div>
			<div id='report'>
				report
			</div>
		</div>
		<div id="user-pane-left">
			<?php
				display_user_avatar($username, 'x100', 'client');
			?>
		</div>
	</div></div>

	<div class="container main" id="collections">
		<div class="main-inner">
			<h1>Collections</h1>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
