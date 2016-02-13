<?php
include 'lib.php';

if($_SERVER['REQUEST_METHOD'] !== "POST"){
	die(json_encode(array("status"=>"error","message"=>"GET requests are not accepted")));
}

if(!isset($_POST['cid']) || !isset($_POST['assets'])) {
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

$assets = array();

// verify that each asset exists and isn't already in the collection
$raw = json_decode($_POST['assets'], TRUE);
if($raw !== NULL && is_array($raw)){
	for($i=0;$i<sizeof($raw);$i++){
		if(isset($raw[$i]) && is_array($raw[$i]) && isset($raw[$i]['userid']) && isset($raw[$i]['assetid'])){
			$auid = intval($raw[$i]['userid']);
			$aid = $raw[$i]['assetid'];
			if(assetExists($auid, $aid)){
				if(!assetIsInCollection($uid, $cid, array($auid, $aid))){
					array_push($assets, array("userid" => $auid, "hash" => $aid));
				}
			}
		}
	}
}

for($i=0;$i<sizeof($assets);$i++){
	addToCollection($userId, $id, $assets[$i]);
}

echo json_encode(array("status" => "success", "message" => "Assets added"));
?>