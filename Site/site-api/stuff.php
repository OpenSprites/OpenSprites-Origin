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
} else {
    $files = glob('../uploads/uploaded/' . $logged_in_userid . '-*.*', GLOB_NOSORT);
    print_r($files);
    for ($i = 1; $i <= count($files); $i++) {
        echo $files[i];
    }
}
?>
