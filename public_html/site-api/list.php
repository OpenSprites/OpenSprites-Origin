<?php
// Import the magic of lib.php that makes our Site API
// stuff super easy!
require 'lib.php';

// Default values for the search.
$max = 10;
$type = "all";
$sort = "popularity";

// These are the valid ?type and ?sort options.
$types = ["all", "image", "script", "sound", "media", "collections"];
$sorts = ["popularity", "alphabetical", "newest", "oldest", "featured"];

// Check ?max.
if(isset($_GET['max'])){
	$max = intval($_GET['max']);
}

// Check ?type.
if(isset($_GET['type'])){
	$type = $_GET['type'];
}

// Check ?sort.
if(isset($_GET['sort'])){
	$sort = $_GET['sort'];
}

// Just in case $type isn't a valid type, reset it
// to "all".
if(!in_array($type, $types)){
	$type = "all";
}

// Same here - if $sort isn't a valid sort, reset it to
// "poplarity".
if(!in_array($sort, $sorts)){
	$sort = "popularity";
}

// Connect to the database - this function is defined in lib.php.
connectDatabase();

// Start constructing the query. The query always starts like this:
//	SELECT * FROM `[table name]`
$query = "SELECT * FROM `" . getAssetsTableName() . "`";

// It can have paramaters though so let's handle those..
// This part isn't that complicated - it's just a bunch of
// statements checking for the various sorts and types,
// then changing the query around to fit those.
$params = array();
if($type == "all"){
	if($sort == "featured"){
		$query .= " WHERE `isFeatured`=?";
		array_push($params, 1);
	}
} else if($type == "media") {
	$query .= " WHERE (`assetType`=? OR `assetType`=?)";
	array_push($params, "image", "sound");
} else if($type == "collections") {
	$query .= " WHERE (`assetType`=?)";
	array_push($params,"collections");
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

// Finsih off the query then send it to the database.
$query .= " LIMIT ?";
array_push($params, $max);
$raw = imagesQuery($query, $params);

// $raw stores the raw assets but we need to change that
// to JSON.
$response = getAssetList($raw);

// Finally echo out the JSON encoded response, with pretty print
// and un-escaped slashes.
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
