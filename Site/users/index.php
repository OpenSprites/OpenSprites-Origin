<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
	
	// get username
	error_reporting(0);
	$raw_json = file_get_contents("http://dev.opensprites.gwiddle.co.uk/site-api/user.php?userid=" . $_GET['username']);
	if($raw_json == 'FALSE') {
		$user_exist = false;
		header('Location: /404');
	} else {
		$user_exist = true;
		$user = json_decode($raw_json, true);
		$username = $user['username'];	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../Header.html'); ?>
	
	<!--Imports styling-->
	<link href='http://dev.opensprites.gwiddle.co.uk/users/user_style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	<!--Imports navigation bar-->
	<?php include "../navbar.php"; ?>
	
	<!-- Main wrapper -->
	<?php echo "<div id='background-img'></div>" ?>
	<div id='dark-overlay'><div id='overlay-inner'>
		<div id="user-pane-right">
			<?php if($user_exist) { ?>
			<div id='username'>
				<?php
				if($username==$logged_in_user) {echo 'You';} else {echo $username;}
				?>
			</div>
			<div id='description'>
				<?php
					echo $user['usertype'];
				?>
			</div>
			<div id='follow'>
				View Scratch Page
			</div>
			<div id='report'>
				Report
			</div>
			<?php } else { ?>
			<div id='username'>
				User not found!
			</div>
			<?php } ?>
		</div>
		<div id="user-pane-left">
			<?php
				if($user_exist) {
					display_user_avatar($username, 'x100', 'client');
				}
			?>
		</div>
	</div></div>

	<?php if($user_exist) { ?>
	<div class="container main" id="collections">
		<div class="main-inner">
			<h1>Collections</h1>
		</div>
	</div>
	<?php } ?>
	
	<!-- view scratch page link :D -->
	<script>
	$('#follow').onclick({
		window.open("http://scratch.mit.edu" + window.location.pathname);
	});
	</script>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
