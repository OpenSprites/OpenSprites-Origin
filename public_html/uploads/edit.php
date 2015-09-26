<?php
require __DIR__."../../includes/validate.php";
require __DIR__."../../includes/connect.php";

header("Content-Type: application/json");

if(!isset($_GET['hash']) || ($logged_in_userid == 0 && !$is_admin)) { // provide ability for admins to edit
	die(json_encode(array("status" => "error", "message" => "Missing params")));
}

$hash = $_GET['hash'];

$query = "UPDATE `" . getAssetsTableName() . "` SET";
$params = array();
if(isset($_GET['title'])){
	$query .= " `customName`=?";
	$title = $_GET['title'];
	if(hasBadWords($title)){
		die(json_encode(array("status" => "error", "message" => "Our bad word filter found a problem with your title.")));
	}
	
	// screw hackers, don't bother giving fancy errors if client-side validation is bypassed
	if(strlen($title) > 32) $title = substr($title, 0, 32);
	array_push($params, $title);
}

if(isset($_GET['description'])){
	if(isset($_GET['title'])){
		$query .= ",";
	}
	$query .= " `description`=?";
	$desc = $_GET['description'];
	
	if(hasBadWords($desc)){
		die(json_encode(array("status" => "error", "message" => "Our bad word filter found a problem with your description.")));
	}
	
	if(strlen($desc) > 500) $desc = substr($desc, 0, 500);
	array_push($params, $desc);
}

$query .= " WHERE `userid`=? AND `hash`=?";

if($is_admin){
	$userid = intval($_GET['userid']);
	array_push($params, $userid, $hash);
} else {
	array_push($params, $logged_in_userid, $hash);
}

try {
	connectDatabase();
	imagesQuery0($query, $params);
} catch(Exception $e){
	die(json_encode(array("status" => "error", "message" => "Database error.")));
}

echo json_encode(array("status" => "success", "message" => "Updated", "title" => htmlspecialchars($_GET['title']), "description" => $_GET['description']));
?>
