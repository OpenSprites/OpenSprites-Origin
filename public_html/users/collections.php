<?php
include_once '../assets/includes/connect.php';
include '../assets/includes/collections.php';
header("Content-Type: text/plain");

$uid = $_GET['uid'];
$cid = $_GET['cid'];

var_dump(getCollectionInfo($uid, $cid));
echo "\n\n";
var_dump(getAssetsInCollection($uid, $cid));
?>