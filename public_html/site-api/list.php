<?php
require 'lib.php';

$max = 10;
$type = "all";
$sort = "popularity";

$types = ["all", "image", "script", "sound", "media", "collections"];
$sorts = ["popularity", "alphabetical", "newest", "oldest", "featured"];

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
if($type == "all"){
	if($sort == "featured"){
		$query .= " WHERE `isFeatured`=?";
		array_push($params, 1);
	}
} else if($type == "media") {
	$query .= " WHERE (`assetType`=? OR `assetType`=?)";
	array_push($params, "image", "sound");
} else {
	$query .= " WHERE `assetType`=?";
	array_push($params, $type);
}

if($type != "all" && $sort == "featured"){
	$query .= " AND `isFeatured`=?";
	array_push($params, 1);
}

if($sort == "popularity"){
	$query .= " ORDER BY `downloadsThisWeek` DESC";
} else if($sort == "alphabetical"){
	$query .= " ORDER BY `customName`";
} else if($sort == "newest"){
	$query .= " ORDER BY `date` DESC";
} else if($sort == "oldest"){
	$query .= " ORDER BY `date`";
} else if($sort == "featured"){
	$query .= " ORDER BY `date`";
}

$query .= " LIMIT ?";
array_push($params, $max);
$raw = imagesQuery($query, $params);

$response = getAssetList($raw);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
