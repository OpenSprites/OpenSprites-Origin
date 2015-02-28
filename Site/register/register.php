<?php
	require '../assets/includes/connect.php';
	
	$taken = FALSE;
	$length = FALSE;
	$goodEmail = FALSE;
	$passCheck = FALSE;
	$goodUsername = FALSE;
	$maxUsernameLength = 50;
	$minPasswordLength = 5;
	
	$is_bot = false;
	
	$min_time = 1.5;
	
	if (empty($_POST)) {
		$_SESSION['init_time'] = time();
	} else {
		//check if enough time has passed
		$timediff = time() - $_SESSION['init_time'];
		if ($timediff <= $min_time) {  //time diff is too small!
			$is_bot = true;
		}
	}
	
	if (isset($_POST['username'], $_POST['password'], $_POST['password_check'], $_POST['email'])) {
		$username = mysqli_real_escape_string($connection, $_POST['username']);
		$query = "SELECT username FROM user_data WHERE username='$username'";
		$result = mysqli_query($connection, $query);
		if (mysqli_num_rows($result) > 0) {
			$taken = TRUE;
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$goodEmail = TRUE;
		}
		if ($_POST['password'] == $_POST['password_check'] && strlen($_POST['password']) > $minPasswordLength) {
			$passCheck = TRUE;
		}
		if (strlen($_POST['username']) <= $maxUsernameLength && preg_match('/^[a-zA-Z0-9_-]*$/', $username)) {
			$goodUsername = TRUE;
		}
		if (!$taken && $goodEmail && $passCheck && $goodUsername && !$is_bot && isset($_SESSION['init_time'])) {
			//all is good, so register!
			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$hashedPassword = password_hash(mysqli_real_escape_string($connection, $_POST['password']), PASSWORD_DEFAULT);
			$key = $username . hash("sha512", uniqid($username, true));
			
			//Can edit this to your preference.  Just keep the username, password, and user_key - those are the important ones!
			$query = "
				INSERT INTO user_data (username, password, email, user_key, date, ip, reg_key, is_reg) 
				VALUES ('$username', '$hashedPassword', '$email', '$key', CURDATE(), '" . $_SERVER['REMOTE_ADDR'] . "', '" . $_SESSION['user_code'] . ", false')
			";
			mysqli_query($connection, $query);
			header("Location: /register/done");
		}
	}
	header("Location: /register/fail");
?>
