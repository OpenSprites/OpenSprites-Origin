<?php
require '../assets/includes/connect.php';
require '../assets/includes/database.php';
header("Content-Type: application/json");

$response = array();
$max = 10;
$type = "all";
$sort = "popularity";

$types = ["all", "images", "scripts", "sounds"];
$sorts = ["popularity", "alphabetical", "newest", "oldest"];

if(isset($_GET['max'])){
	$max = intval($_GET['max']);
}

if(isset($_GET['type'])){
	$type = $_GET['type'];
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];
}

if(!in_array($type, $types)){
	$type = "all";
}

if(!in_array($sort, $sorts)){
	$sort = "popularity";
}

connectDatabase();
$query = "SELECT * FROM `" . getAssetsTableName() . "`";
$params = array();
if($type != "all"){
	$query .= "WHERE `assetType`=?";
	array_push($params, $type);
}

if($sort == "popularity"){
	// implement
} else if($sort == "popularity"){
	$query .= "ORDER BY `customName`";
} else if($sort == "alphabetical"){
	$query .= "ORDER BY `customName`";
} else if($sort == "newest"){
	$query .= "ORDER BY `date` DESC";
} else if($sort == "oldest"){
	$query .= "ORDER BY `date`";
}

$query .= " LIMIT ?";
array_push($params, $max);
$raw = imagesQuery($query, $params);

for($i=0;$i<sizeof($raw);$i++){
	$asset = $raw[$i];
	$obj = array(
		"name" => $asset['customName'],
		"type" => $asset['assetType'],
		"url" => "/uploads/uploaded/" . $asset['name'],
		"md5" => $asset['hash'],
		"upload_time" => $asset['date'],
		"uploaded_by" => array(
			"name" => $asset["user"],
			"id" => $asset["userid"]
		)
	);
	array_push($response, $obj);
}
echo json_encode($response, JSON_PRETTY_PRINT);

?>