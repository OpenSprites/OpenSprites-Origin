<?php
require 'lib.php';

if($logged_in_userid === 0 || $user_banned) die(json_encode(array("status" => "error", "message"=>"Not logged in")));

if(!isset($_POST['type']) || !isset($_POST['id']) || !isset($_POST['reason'])) die(json_encode(array("status" => "error", "message"=>"Malformed request")));

$type = -1;
if($_POST['type'] === "user"){
	$type = 0;
} else if ($_POST['type'] === "asset") {
	$type = 1;
} else {
	die(json_encode(array("status" => "error", "message"=>"Malformed request")));
}
$id = $_POST['id'];

if($type === 0){
	$id = intval($id);
	if($id <= 0) die(json_encode(array("status" => "error", "message"=>"Malformed request")));
} else if ($type === 1){
	$id0 = intval(substr($id, 0, strpos($id, "/")));
	if($id0 <= 0) die(json_encode(array("status" => "error", "message"=>"Malformed request")));
	$id1 = substr($id, strpos($id, "/") + 1);
	if(strlen($id1) > 32) $id1 = substr($id1, 0, 32);
	if(preg_match("/^[a-f0-9]+$/", $id1) !== 1) die(json_encode(array("status" => "error", "message"=>"Malformed request")));
	$id = $id0 . "/" . $id1;
}

$reporter = $user;
$reason = $_POST['reason'];
if(strlen($reason) > 500) $reason = substr($reason, 0, 500);

$isAble = isUserAbleToReport($logged_in_userid);
if($isAble !== TRUE) die(json_encode(array("status" => "wait", "message"=>"Uh oh, you're reporting too fast! Please wait $isAble seconds")));

$success = addReport($type, $id, $reporter, $reason);

if($success) echo json_encode(array("status" => "success", "message"=>""));
else echo json_encode(array("status" => "error", "message"=>"You've already reported that " . $_POST['type']));
?>
