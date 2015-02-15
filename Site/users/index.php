<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
<<<<<<< HEAD
	require "../assets/includes/avatar.php";
=======
	//require "../assets/includes/global_functions.php";
>>>>>>> origin/master
	
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
<<<<<<< HEAD
				display_user_avatar($username, 'x100', 'client');
=======
				// TEMPORARY, DO NOT REMOVE
				$size = 'x100';
				$raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username . "/");		
				$user_arr = json_decode($raw_json, true);		
				$user_avatar = $user_arr["thumbnail_url"];		
				$src = "http:" . $user_avatar;		
				echo '<img class="user-avatar ' . $size . '" src="' . $src . '">';
>>>>>>> origin/master
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
