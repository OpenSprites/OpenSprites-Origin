<?php
include '../assets/includes/connect.php';
header("Content-Type: application/json");

$userid = 0;
if(isset($_POST['userid'])){
	$userid = intval($_GET['userid']);
}

if($userid == 0 || ($userid != $logged_in_userid && !$is_admin)){
	die(json_encode(array("status" => "error", "message" => "403 permission denied")));
}

if(!isset($_POST['token']) || !isset($_COOKIE['avatarToken']) || $_POST['token'] != $_COOKIE['avatarToken']){
	die(json_encode(array("status" => "error", "message" => "403 go away")));
}

if(!isset($_FILES['avatar'])){
	die(json_encode(array("status" => "error", "message" => "400 missing avatar")));
}

if($_FILES['avatar']['error'] != 0){
	die(json_encode(array("status" => "error", "message" => "Whoops, there was a problem uploading your file (code " . $_FILES['avatar']['error'] . ")")));
}

if(filesize($_FILES['avatar']['tmp_name'] > 8388608)){
	die(json_encode(array("status" => "error", "message" => "Whoops, it seems your file is too big!")));
}

$type = exif_imagetype($_FILES['avatar']['tmp_name']);
if($type === FALSE || $type === 0) {
	die(json_encode(array("status" => "error", "message" => "Whoops, your image is not valid!")));
}

$res = copy($_FILES['avatar']['tmp_name'], "../forums/uploads/avatars/" . $userid . ".png");

if(!$res){
	die(json_encode(array("status" => "error", "message" => "Whoops, we had an internal server error. Try again later.")));
} else {
	echo json_encode(array("status" => "success", "message" => "Uploaded"));
}
?>