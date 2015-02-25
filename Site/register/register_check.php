<?php
	require '../assets/includes/connect.php';
	require '../assets/includes/html_dom_parser.php';
	
	$username = $_GET['username_confirmation'];
	
	$query = "SELECT reg_key FROM user_data WHERE username='$username'";
	$row = mysqli_fetch_assoc(mysqli_query($connection, $query));
	$reg_key = $row['reg_key'];
	
	$project_comments = file_get_html('http://scratch.mit.edu/site-api/comments/project/47606468/');
	$comments = $project_comments -> find('.comment .info .content');
	foreach ($comments as $comment) {
		//do stuff...
	}
?>
