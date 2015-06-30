<?php
include 'lib.php';

if($_SERVER['REQUEST_METHOD'] !== "POST"){
	die(json_encode(array("status"=>"error","message"=>"GET requests are not accepted")));
}

if(!isset($_POST['cid']) || !isset($_POST['name']) || !isset($_POST['description'])) {
	die(json_encode(array("status"=>"error","message"=>"Missing params")));
}

if($logged_in_userid === 0) {
	die(json_encode(array("status"=>"error","message"=>"Not logged in")));
}

$uid = $logged_in_userid;
if(isset($_POST['userid'])){
	$uid = intval($_POST['userid']);
}

if(!$is_admin && $uid !== $logged_in_userid){
	die(json_encode(array("status"=>"error","message"=>"Error 403, go away")));
}

$cid = $_POST['cid'];

if(!collectionExists($uid, $cid)){
	die(json_encode(array("status"=>"error","message"=>"That collection doesn't exist!")));
}

$name = $_POST['name'];
$desc = $_POST['description'];
if(strlen($name) > 32) $name = substr($name, 0, 32);
if(strlen($desc) > 500) $desc = substr($desc, 0, 500);

editCollection($uid, $cid, $name, $desc);

echo json_encode(array("status" => "success", "message" => "Collection edited", "collection_info" => getCollection($uid, $cid)));
?>