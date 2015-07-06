<?php
include 'lib.php';

if($_SERVER['REQUEST_METHOD'] !== "POST"){
	die(json_encode(array("status"=>"error","message"=>"GET requests are not accepted")));
}

if(!isset($_POST['name'])) {
	die(json_encode(array("status"=>"error","message"=>"Missing params")));
}

if($logged_in_userid === 0) {
	die(json_encode(array("status"=>"error","message"=>"Not logged in")));
}

$assets = array();

// if the an asset list is posted, verify that each asset exists
if(isset($_POST['assets'])){
	$raw = json_decode($_POST['assets'], TRUE);
	if($raw !== NULL && is_array($raw)){
		for($i=0;$i<sizeof($raw);$i++){
			if(isset($raw[$i]) && is_array($raw[$i]) && isset($raw[$i]['userid']) && isset($raw[$i]['assetid'])){
				$uid = intval($raw[$i]['userid']);
				$aid = $raw[$i]['assetid'];
				if(assetExists($uid, $aid)){
					array_push($assets, array("userid" => $uid, "hash" => $aid));
				}
			}
		}
	}
}

$name = $_POST['name'];
if(strlen($name) > 32) $name = substr($name, 0, 32);

$cid = addCollection($logged_in_user, $logged_in_userid, $name, $assets);

echo json_encode(array("status" => "success", "message" => "Collection created", "collection_id" => $cid));
?>