<?php
require 'lib.php';

function getNiceResultNumber($num){
	if($num < 1000) return $num . " results";
	if($num < 1000000) return "About " . floor($num / 1000) . "K results";
	return "About " . floor($num / 1000000) . "M results";
}

if(!isset($_GET['query'])) die(json_encode(array("message" => "Missing query", "results" => array())));

$query = $_GET['query'];
$words = preg_split("/[^a-zA-Z0-9]+/", $query, -1, PREG_SPLIT_NO_EMPTY);

$sorts = ["popularity", "date", "alphabetical"];
$dirs = ["desc", "asc"];
$places = ["both", "names", "descriptions"];
$filters = ["all", "users", "resources", "collections"];

$sort = "popularity";
$dir = "desc";
$place = "both";
$filter = "all";

if(isset($_GET['sort'])) $sort = $_GET['sort'];
if(isset($_GET['dir'])) $dir = $_GET['dir'];
if(isset($_GET['place'])) $place = $_GET['place'];
if(isset($_GET['filter'])) $filter = $_GET['filter'];

if(!in_array($sort, $sorts)){
	$sort = "popularity";
}
if(!in_array($dir, $dirs)){
	$dir = "desc";
}
if(!in_array($filter, $filters)){
	$filter = "all";
}
if(!in_array($place, $places)){
	$place = "both";
}

// a bug (?) in MySQL prevents using query parameters in MATCH, so we have to escape
// http://stackoverflow.com/a/13682516/1021196

$fulltext_search = join(" +", $words);
$fulltext_search = getDbh()->quote($fulltext_search);

$sql_query = "SELECT * FROM `os_assets` WHERE ";
if($filter == "all"){
	// add collections and users
} else if($filter == "users"){
	// welp, implement later
} else if($filter == "resources"){
	$sql_query .= "`assetType`='image' OR `assetType`='script' OR `assetType`='sound' ";
} else if($filter == "collections"){
	// welp, implement later
}

//                                                         \/ see above
$sql_query .= "MATCH(`customName`,`description`) AGAINST($fulltext_search IN BOOLEAN MODE) ";

if($sort == "popularity"){
	$sql_query .= " ORDER BY `downloadsThisWeek` ";
} else if($sort == "alphabetical"){
	$sql_query .= " ORDER BY `customName` ";
} else if($sort == "date"){
	$sql_query .= " ORDER BY `date` ";
}

if($dir == "desc"){
	$sql_query .= "DESC ";
}

$res = imagesQuery($sql_query, array());

echo json_encode(array("message" => getNiceResultNumber(sizeof($res)), "results" => getAssetList($res)));
?>