<?php
/* OLD CODE
function is_404($url) {
    $returned = get_headers('http://opensprites.org/forums/?p=member/' . $url, 1)[0];
    return $returned == 'HTTP/1.1 404 Not Found';
}

function username_of($id) {
    $html = file_get_html('http://opensprites.org/forums/?p=member/' . $id);
    return $html->find('h1#memberName', 0)->innertext;
}

function usertype_of($id) {
    $html = file_get_html('http://opensprites.org/forums/?p=member/' . $id);
    $r = $html->find('p#memberGroup', 0)->first_child();
    if($r == null) {
        $r = $html->find('p#memberGroup', 0);
    }
    
    return $r->innertext;
}

function avatar_of($id) {
    
    if(get_headers('http://opensprites.org/uploads/avatars/' . $id . '.png')[0] == 'HTTP/1.1 404 Not Found') {
        $username_grabbed = username_of($id);
        $raw_json = file_get_contents("https://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
        $user_arr = json_decode($raw_json, true);
        $user_avatar = $user_arr["thumbnail_url"];
        return "http:$user_avatar";
        
        $html = file_get_html('http://opensprites.org/forums/?p=member/' . $id);
        return $html->find('#memberProfile', 0)->first_child()->src;
    } else {
        return 'http://opensprites.org/uploads/avatars/' . $id . '.png';
    }
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
$raw = file_get_contents("http://opensprites.org/forums/?p=member/".$userid);
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
///////////////////////// NEWER OLD CODE
$raw = file_get_html("http://opensprites.org/forums/?p=member/" . $userid);
if($raw == FALSE) {
    echo 'FALSE';
    die();
}

$r = preg_replace('/\s+/', '', $raw->find('p#memberGroup', 0)->plaintext);

if($raw->find('img.avatar', 0) !== null) {
    // they have an uploaded avatar image
    $avatar = 'http://opensprites.org/' . $raw->find('img.avatar', 0)->src;
} else {
    // they have not uploaded an avatar image
    $avatar = 'http://opensprites.org/assets/images/defaultfile.png';
}

$json = array('userid' => $userid, 'username' => $raw->find('h1#memberName', 0)->innertext, 'usertype' => $r, 'avatar' => $avatar);*/

////////////////// ACTUAL CODE (pretty short with database.php, right?)
require 'lib.php'; // lib has all the headers and other requires we need already

$userid = 'false';
if(isset($_GET['userid'])) {
    $userid = $_GET['userid'];
} else {
    $userid = $_SESSION["userId"];
}

connectForumDatabase();
$userInfo2 = getUserInfo(intval($userid));
$userInfo2['avatar'] = "http://opensprites.org/forums/uploads/avatars/" . $userid . ".png?_=" . time();

$handle = curl_init($userInfo2['avatar']);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if($httpCode == 404) {
    $userInfo2['avatar'] = "//opensprites.org/assets/images/defaultuser.png";
}
curl_close($handle);

echo json_encode($userInfo2, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

?>
