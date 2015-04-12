<?php

require '../assets/includes/html_dom_parser.php';

session_name("OpenSprites_Forum_session");
session_start();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, x-requested-with, content-type, accept");
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json');

function is_404($url) {
    $returned = get_headers('http://opensprites.gwiddle.co.uk/forums/?p=member/' . $url, 1)[0];
    return $returned == 'HTTP/1.1 404 Not Found';
}

function username_of($id) {
    $html = file_get_html('http://opensprites.gwiddle.co.uk/forums/?p=member/' . $id);
    return $html->find('h1#memberName', 0)->innertext;
}

function usertype_of($id) {
    $html = file_get_html('http://opensprites.gwiddle.co.uk/forums/?p=member/' . $id);
    $r = $html->find('p#memberGroup', 0)->first_child();
    if($r == null) {
        $r = $html->find('p#memberGroup', 0);
    }
    
    return $r->innertext;
}

function avatar_of($id) {
    $username_grabbed = username_of($id);
    $raw_json = file_get_contents("https://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
    $user_arr = json_decode($raw_json, true);
    $user_avatar = $user_arr["thumbnail_url"];
    return "http:$user_avatar";
    
    $html = file_get_html('http://opensprites.gwiddle.co.uk/forums/?p=member/' . $id);
    $r = $html->find('#memberProfile', 0)->first_child()->src;
    
    // temporary
    echo '<script>console.log("' . $r . '");</script>';
    
    if($r == null) {
        $username_grabbed = username_of($id);
        $raw_json = file_get_contents("https://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
        $user_arr = json_decode($raw_json, true);
        $user_avatar = $user_arr["thumbnail_url"];
        return "http:$user_avatar";
    }
    
    return $r;
}

function scratch_userid_of($id) {
    $username_grabbed = username_of($id);
    $raw_json = file_get_contents("https://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
    $user_arr = json_decode($raw_json, true);
    $user_avatar = $user_arr["user"]["pk"];
    return $user_avatar;
}

$userid = 'false';
if(isset($_GET['userid'])) {
    $userid = $_GET['userid'];
} else {
    $userid = $_SESSION["userId"];
}

error_reporting(0);
$raw = file_get_contents("http://opensprites.gwiddle.co.uk/forums/?p=member/".$userid);
if($raw === FALSE) {
    echo 'FALSE';
} else {
    $username = username_of($userid);
    $usertype = usertype_of($userid);
    $avatar = avatar_of($userid);
    $scratch_userid = scratch_userid_of($userid);
    
    echo '{"userid": ' . $userid . ', ';
    echo '"username": "' . $username . '", ';
    echo '"usertype": "' . $usertype . '", ';
    echo '"scratch_userid": ' . $scratch_userid . ', ';
    echo '"avatar": "' . $avatar . '"}';
}

?>
