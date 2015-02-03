<?php

  //Firedrake969's login script v000
  
  require "connect.php";
	
	$username = mysqli_real_escape_string($connection, $_POST['username']);  //username sent from login form
	$password = mysqli_real_escape_string($connection, $_POST['password']);  //same
	
	$query = "SELECT password FROM user_data WHERE username='$username'";  //get hashed pass
	$result = mysqli_query($connection, $query);  //query
	$row = mysqli_fetch_assoc($result);
	$hashed_pass = $row["password"];  //get hashed pass

	if (password_verify($password, $hashed_pass)) {  //can log in?  then do so
		$username_query = "SELECT username FROM user_data WHERE user_key = '$user_key'";  //get the username based on the key found
		$username_result = mysqli_query($connection, $username_query);
		$username_row = mysqli_fetch_assoc($username_result);
		$fetch_username = $username_row["username"];  //actual username
		$new_key = $fetch_username . hash("sha512", uniqid($fetch_username, true));  //create new key in cookie
		$key_query = "UPDATE user_data SET user_key = '$new_key' WHERE username = '$fetch_username'";  //set in database
		mysqli_query($connection, $key_query);
		setcookie("login_key", $new_key, time()+60*60*24*365, "/");  //set the new cookie - will override old
		$_SESSION["username"] = $fetch_username;  //set username in session - easy to find if logged in based on session variable now
	} else {  //invalid credentials
		setcookie("key", "", time()-3600, "/");  //destroy cookie
	}
	header("Location: /");  //redirect to home page - can be changed later
?>
