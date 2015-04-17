<?php

require '../assets/includes/html_dom_parser.php';
session_name("OpenSprites_Forum_session");
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, x-requested-with, content-type, accept");
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json');

$userid = 'false';
if(isset($_GET['userid'])) {
    $userid = $_GET['userid'];
} else {
    $userid = $_SESSION["userId"];
}

if($userid === 'false') {
    echo 'FALSE';
    die();
}

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

echo json_encode($files_sorted, JSON_PRETTY_PRINT);

?>
