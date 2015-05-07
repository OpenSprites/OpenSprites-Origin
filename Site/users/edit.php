<?php
include '../assets/includes/connect.php';
header("Content-Type: text/plain");

if($logged_in_userid == 0 || $logged_in_userid == '0') {
	header("Location: /");
}

setProfileSettings($logged_in_userid, array("bgcolor" => $_GET['bgcolor']));

//header("Location: /users/$logged_in_userid/");
var_dump(getProfileSettings($logged_in_userid));
?>
