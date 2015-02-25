<?php
	require '../assets/includes/connect.php';
	require '../assets/includes/html_dom_parser.php';
	
	$username = $_GET['username_confirmation'];
	
	$query = "SELECT reg_key FROM user_data WHERE username='$username'";
	$row = mysqli_fetch_assoc(mysqli_query($connection, $query));
	$reg_key = $row['reg_key'];
	
	$project_page = file_get_html('http://scratch.mit.edu/projects/47606468/');
?>
