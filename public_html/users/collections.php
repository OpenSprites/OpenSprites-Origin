<?php
include '../assets/includes/connect.php';
include '../assets/includes/collections.php';
header("Content-Type: text/plain");

var_dump(getCollectionInfo($uid, $cid));
echo "\n\n";
var_dump(getAssetsInCollection($uid, $cid));
?>