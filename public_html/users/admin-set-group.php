<?php
require "../assets/includes/connect.php";

if(!$is_admin){
	include '../403.php';
	die();
}

if(!isset($_POST['userid']) || !isset($_POST['groups'])) die("Missing params");

$userid = intval($_POST['userid']);
$groups = json_decode($_POST['groups']);

setUserGroups($userid, $groups);

header("Content-Type: application/json");
echo json_encode(array("status" => "success"));
?>