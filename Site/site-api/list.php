<?php
require 'lib.php';

$max = 10;
$type = "all";
$sort = "popularity";

$types = ["all", "image", "script", "sound"];
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
	$query .= " WHERE `assetType`=?";
	array_push($params, $type);
}

if($sort == "popularity"){
	$query .= " ORDER BY `downloadCount`";
} else if($sort == "alphabetical"){
	$query .= " ORDER BY `customName`";
} else if($sort == "newest"){
	$query .= " ORDER BY `date` DESC";
} else if($sort == "oldest"){
	$query .= " ORDER BY `date`";
}

$query .= " LIMIT ?";
array_push($params, $max);
$raw = imagesQuery($query, $params);

$response = getAssetList($raw);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
