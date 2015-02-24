<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
	
	// capitalise username correctly
	$username = $_GET['username']
	$raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username . "/");
	
	//get username from database - make sure they're registered
	
	$check_query = "SELECT username FROM user_data WHERE username=$username";
	$check_res = mysqli_query($connection, $check_query);
	$check_rows = mysqli_fetch_assoc($check_res);
	$user_registered = mysqli_num_rows($check_rows) != 0;
	
	
	if($raw_json == 'FALSE' or $raw_json == FALSE or $_GET['username'] == undefined or !$user_registered) {
		// user was not found, display error
		header("Location: /404.html");
	} else {
		// procceed
		$user_arr = json_decode($raw_json, true);
		$user = $user_arr["user"];
		$username = $user["username"];
	}
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
				<?php
					echo 'blah blah blah';
				?>
			</div>
			<div id='follow'>
				Follow
			</div>
			<div id='report'>
				Report
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
