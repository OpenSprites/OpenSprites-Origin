<?php
include '../../assets/includes/connect.php';
include '../../assets/includes/collections.php';
header("Content-Type: text/plain");

$uid = intval($_GET['userid']);
$cid = $_GET['collectionid'];

var_dump(getCollectionInfo($uid, $cid));
echo "\n\n";
var_dump(getAssetsInCollection($uid, $cid));
?>