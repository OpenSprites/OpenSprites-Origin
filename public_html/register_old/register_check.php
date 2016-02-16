<?php
	require '../assets/includes/connect.php';
	require '../assets/includes/html_dom_parser.php';
	
	$username = $_GET['username_confirmation'];
	
	$is_good_reg = false;
	
	$query = "SELECT reg_key FROM user_data WHERE username='$username'";
	$row = mysqli_fetch_assoc(mysqli_query($connection, $query));
	$reg_key = $row['reg_key'];
	
	$project_comments = file_get_html('https://scratch.mit.edu/site-api/comments/project/47606468/');
	$comments = $project_comments -> find('.comment .info');
	foreach ($comments as $comment) {
		$creator = $comment -> find('name .a');
		$content = $comment -> find('.content');
		if ($creator == $username && $content == $reg_key) {
			$is_good_reg = true;
			break;
		}
	}
	
	if ($is_good_reg) {
		$query = "UPDATE user_data SET is_reg='true' WHERE username='$$username'";
		mysqli_query($connection, $query);
	}
	header("Location: /");
?>
