<?php
// Import the base Site API library for simplifying
// all our PHP code.
require 'lib.php';

// Figure out the User ID. It is generally one of
// three things:
//  [1] false, meaning it isn't set in either the session data
//	or the page URL ($_GET)
//  [2] any username, from the page URL: $userid = $_GET['userid']
//  [3] your username, from the session data: $userid = $_SESSION['userId']
$userid = 'false';
if(isset($_GET['userid'])) {
    $userid = $_GET['userid'];
} else {
    $userid = $_SESSION["userId"];
}

// In the case that it fits #1 ('false') we should echo out a quick
// message ('false' is very informative I know right?) and end the
// page.
if($userid === 'false') {
    echo 'FALSE';
    die();
}

// Now we need to connect to the database. lib.php makes this very
// easy, all we need to do is use the connectDatabase() function!
// Just in case it causes an error, we'll show that too.
try {
	connectDatabase();
} catch(Exception $e) {
	die(json_encode(array(array("status"=>"error","message"=>"Cannot connect to database"))));
}

// More lib.php stuff. This just gets the assets uploaded.
$raw = getImagesForUser($userid);
$assets = getAssetList($raw);

// Finally echo the JSON encoded value of $assets with pretty
// print to satisfy your eyes, and we're done!
echo json_encode($assets, JSON_PRETTY_PRINT);

/*

error_reporting(0);
$files = glob('../uploads/uploaded/' . $userid . '-*.*', GLOB_NOSORT);
$read_files = array();
for ($i = 0; $i < count($files); $i++) {
    if(substr($files[$i], -4) !== 'json') {
        array_push($read_files, json_decode(file_get_contents($files[$i] . '.json'), true));
    }
}

$files_sorted = array();
for($i = 0; $i < count($read_files); $i++) {
    $key = substr($read_files[$i]['name'], strlen($_GET['userid']) + 1, strrpos($read_files[$i]['name'], '.') - 2);
    //$key = $i + 1;
    $files_sorted[$key] = $read_files[$i];
}

krsort($files_sorted, SORT_NUMERIC);

echo json_encode($files_sorted, JSON_PRETTY_PRINT);*/

?>
