<?php
include 'lib.php';
include 'collections.php';

$userid = 'false';
$cid = 'false';

if(isset($_GET['userid'])) {
    $userid = intval($_GET['userid']);
}

if(isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}

if($userid === 'false' || $cid === 'false') {
	die(json_encode(array(array("status"=>"error","message"=>"Missing params"))));
}

$info = getCollection(getCollectionInfo($userid, $cid));
$assets = getCollectionAssets(getAssetsInCollection($userid, $cid));

echo json_encode(array("info" => $info, "assets" => $assets));
?>