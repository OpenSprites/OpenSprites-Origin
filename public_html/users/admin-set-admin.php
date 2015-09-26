<?php
require __DIR__."../../includes/connect.php";

if(!$is_admin){
	include '../403.php';
	die();
}

if(!isset($_POST['userid']) || !isset($_POST['admin'])) die("Missing params");

$userid = intval($_POST['userid']);
$admin = json_decode($_POST['admin']);

$username = getUserInfo($userid)['username'];

$groups = getUserGroups($userid);

if($admin){
	if(!in_array(1, $groups)) array_push($groups, 1);
	setAccountType($username, "administrator");
} else {
	if(in_array(1, $groups)){
		$key = array_search(1, $groups);
		unset($groups[$key]);
	}
	setAccountType($username, "member");
}

setUserGroups($userid, $groups);

header("Content-Type: application/json");
echo json_encode(array("status" => "success"));
?>