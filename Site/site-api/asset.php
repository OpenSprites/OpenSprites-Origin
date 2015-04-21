<?php
require '../assets/includes/connect.php';
require '../assets/includes/database.php';
require 'lib.php';

$userid = 'false';
$hash = 'false';

if(isset($_GET['userid'])) {
    $userid = $_GET['userid'];
}

if(isset($_GET['hash'])) {
    $hash = $_GET['hash'];
}

if($userid === 'false' || $hash === 'false') {
	die(json_encode(array(array("status"=>"error","message"=>"Missing params"))));
}
try {
	connectDatabase();
} catch(Exception $e){
	die(json_encode(array(array("status"=>"error","message"=>"Cannot connect to database"))));
}

$query = "SELECT * FROM `" . getAssetsTableName() . "` WHERE `userid`=? AND `hash`=?";

$raw = imagesQuery($query, array(intval($userid), $hash));
$assets = getAssetList($raw);
echo json_encode($assets, JSON_PRETTY_PRINT);
?>
