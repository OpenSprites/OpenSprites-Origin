<?php
require '../assets/includes/connect.php';

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
	  		"id" => $asset["userid"]
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

header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/json");
?>
