<?php
include '../assets/includes/connect.php';

if(!isset($_GET['json']) || ($logged_in_userid == 0)) {
	die("Missing params");
}

$json = json_decode($_GET['json']);

connectForumDatabase();

?>
