<?php
include '../assets/includes/validate.php';
include '../assets/includes/connect.php';

header("Content-Type: application/json");

if(!isset($_GET['hash'])) {
	die(json_encode(array("status" => "error", "message" => "missing params")));
}
$hash = $_GET['hash'];


?>
