<?php
require '../assets/includes/connect.php';
include '../assets/includes/collections.php';

function getAssetList($raw){
  $assets = array();
  for($i=0;$i<sizeof($raw);$i++){
	  $asset = $raw[$i];
	  $obj = array(
	  	"name" => $asset['customName'],
	  	"type" => $asset['assetType'],
	  	"url" => "/uploads/uploaded/" . $asset['name'],
	  	"filename" =>  $asset['name'],
	  	"md5" => $asset['hash'],
	  	"upload_time" => $asset['date'],
	  	"uploaded_by" => array(
	  		"name" => $asset["user"],
	  		"id" => intval($asset["userid"])
	  	),
		"downloads" => array(
			"this_week" => intval($asset['downloadsThisWeek']),
			"total" => intval($asset['downloadCount'])
		),
		"description" => $asset['description']
	  );
	  array_push($assets, $obj);
  }
  return $assets;
}

function getCollection($uid, $cid){
	$raw = getCollectionInfo($uid, $cid);
	return array(
	  	"name" => $raw['customName'],
	  	"url" => "/users/" . $raw['userid'] . "/collection_" . $raw['id'],
	  	"create_time" => $raw['date'],
		"collection_id" => $raw['id'],
	  	"created_by" => array(
	  		"name" => $raw["user"],
	  		"id" => intval($raw["userid"])
	  	),
		"downloads" => array(
			"this_week" => intval($raw['downloadsThisWeek']),
			"total" => intval($raw['downloadCount'])
		),
		"description" => $raw['description']
	  );
}

function getCollectionAssetList($uid, $cid){
	$raw = getAssetsInCollection($uid, $cid);
	$assets = array();
	for($i=0;$i<sizeof($raw);$i++){
		$asset = imagesQuery("SELECT * FROM `" . getAssetsTableName() . "` WHERE `userid`=? AND `hash`=?", array($raw[$i]['assetuserid'], $raw[$i]['assetid']));
		array_push($assets, $asset);
	}
	return getAssetList($assets);
}

header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/json");
?>
