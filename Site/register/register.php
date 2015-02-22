<?php
	require '../assets/includes/connect.php';
	
	$username = mysqli_real_escape_string($connection, $_POST['username']);
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$password = mysqli_real_escape_string($connection, $_POST['password']);
	$pass_conf = mysqli_real_escape_string($connection, $_POST['pass_conf']);
	
	$id = $_SESSION['login_code'];
	
	$good_username = false;
	$good_password = false;
	$good_email = false;
	
	if () {
		$good_username = true;
	}
	
	if () {
		$good_password = true;
	}
	
	if () {
		$good_email = true;
	}
	
	if ($good_email && $good_password && $good_username) {
		//register...
	}
?>