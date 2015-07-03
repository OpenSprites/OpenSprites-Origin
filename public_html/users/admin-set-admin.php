<?php
require "../assets/includes/connect.php";

if(!$is_admin){
	include '../403.php';
	die();
}

if(!isset($_POST['userid']) || !isset($_POST['admin'])) die("Missing params");

$userid = intval($_POST['userid']);
$admin = json_decode($_POST['admin']);

$groups = getUserGroups($userid);

if($admin){
	if(!in_array(1, $groups)) array_push($groups, 1);
	setAccountTypeById($userid, "administrator");
} else {
	if(in_array(1, $groups)){
		$key = array_search(1, $groups);
		unset($groups[$key]);
	}
	setAccountTypeById($userid, "member");
}

setUserGroups($userid, $groups);

header("Content-Type: application/json");
echo json_encode(array("status" => "success"));
?>