<?php
include '../assets/includes/connect.php';

if($logged_in_userid == 0 || $logged_in_userid == 'not_logged_in') {
	header("Location: /");
}

setProfileSettings($logged_in_userid, array($_GET['bgcolor']));

header("Location: /users/$logged_in_userid/");
?>
