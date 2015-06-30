<?php
include 'lib.php';

$userid = 'false';
$cid = 'false';

if(isset($_GET['userid'])) {
    $userid = intval($_GET['userid']);
}

if(isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}

if($userid === 'false' || $cid === 'false') {
	die(json_encode(array("status"=>"error","message"=>"Missing params")));
}

$info = getCollection($userid, $cid);
$assets = getCollectionAssetList($userid, $cid);

echo json_encode(array("info" => $info, "assets" => $assets));
?>