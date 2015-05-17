<?php
require 'lib.php';

function getNiceResultNumber($num){
	if($num < 1000) return $num . " results";
	if($num < 1000000) return "About " . floor($num / 1000) . "K results";
	return "About " . floor($num / 1000000) . "M results";
}

if(!isset($_GET['query']) && !isset($_POST['advancedQuery'])) die(json_encode(array("message" => "Missing query", "results" => array())));

$query = $_GET['query'];
$words = preg_split("/[^a-zA-Z0-9\+\-\*\"]+/", $query, -1, PREG_SPLIT_NO_EMPTY);

$warning = array();

$keywords = array();

function is_valid_word($word){
	return preg_match("/^[a-zA-Z0-9]+$/", $word);
}

$size = sizeof($words);
for($i=0;$i<$size;$i++){
	$word = $words[$i];
	if(substr($word, 0, 1) === "+" && is_valid_word(substr($word, 1, strlen($word) - 1))){
		array_push($keywords, $word);
	} else if(substr($word, 0, 1) === "-" && is_valid_word(substr($word, 1, strlen($word) - 1))){
		array_push($keywords, $word);
	} else if(substr($word, strlen($word) - 1, 1) === "*" && is_valid_word(substr($word, 0, strlen($word) - 1))){
		array_push($keywords, $word);
	} else if(substr($word, 0, 1) === "\"" && is_valid_word(substr($word, 1, strlen($word) - 1))){
		$j = $i + 1;
		$quote = $word;
		while(substr($words[$j], strlen($words[$j]) - 1, 1) !== "\""){
			if(is_valid_word($words[$j])){
				$quote .= " " . $words[$j];
			} else {
				$warning = "Malformed query (not a valid word in quote)";
			}
			$j++;
			if($j === $size - 1){
				if(substr($words[$j], strlen($words[$j]) - 1, 1) !== "\""){
					array_push($warning, "No closing quote on " . $words[$j]);
				}
				break;
			}
		}
		if(is_valid_word(substr($words[$j], 0, strlen($words[$j]) - 1)) && substr($words[$j], strlen($words[$j]) - 1, 1) === "\""){
			$quote .= " " . $words[$j];
			array_push($keywords, $quote);
		} else {
			array_push($warning, $words[$j] . " is not a valid search word");
		}
		$i = $j;
	} else if(is_valid_word($word)){
		array_push($keywords, $word);
	} else {
		array_push($warning, $word . " is not a valid search word");
	}
}

$sorts = ["relevance", "popularity", "date", "alphabetical"];
$dirs = ["desc", "asc"];
$places = ["both", "names", "descriptions"];
$filters = ["all", "users", "resources", "collections"];

$sort = "relevance";
$dir = "desc";
$place = "both";
$filter = "all";

if(isset($_GET['sort'])) $sort = $_GET['sort'];
if(isset($_GET['dir'])) $dir = $_GET['dir'];
if(isset($_GET['place'])) $place = $_GET['place'];
if(isset($_GET['filter'])) $filter = $_GET['filter'];

if(!in_array($sort, $sorts)){
	$sort = "relevance";
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

$fulltext_search = join(" ", $keywords);
$fulltext_search = getDbh()->quote($fulltext_search);

//                                                         \/ see above
$match_query .= "MATCH(`customName`,`description`) AGAINST($fulltext_search IN BOOLEAN MODE) ";

$sql_query = "SELECT *, $match_query as relevance FROM `os_assets` WHERE ";
if($filter == "all"){
	// add collections and users
} else if($filter == "users"){
	// welp, implement later
} else if($filter == "resources"){
	$sql_query .= "(`assetType`='image' OR `assetType`='script' OR `assetType`='sound') AND ";
} else if($filter == "collections"){
	// welp, implement later
}

$sql_query .= $match_query;

if($sort == "relevance"){
	$sql_query .= " ORDER BY relevance ";
} else if($sort == "popularity"){
	$sql_query .= " ORDER BY `downloadsThisWeek` ";
} else if($sort == "alphabetical"){
	$sql_query .= " ORDER BY `customName` ";
} else if($sort == "date"){
	$sql_query .= " ORDER BY `date` ";
} else {
	$sql_query .= " ORDER BY relevance ";
}

if($dir == "desc"){
	$sql_query .= "DESC ";
}

$res = imagesQuery($sql_query, array());

echo json_encode(array("message" => getNiceResultNumber(sizeof($res)), "warning" => $warning, "results" => getAssetList($res)));
?>