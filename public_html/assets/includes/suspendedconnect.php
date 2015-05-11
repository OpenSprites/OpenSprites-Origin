<?php
// reduced functionality connect.php

require_once("database.php");
connectDatabase();
connectForumDatabase();

session_name("OpenSprites_Forum_session");
session_set_cookie_params(0, '/', '.opensprites.gwiddle.co.uk');
session_start();

$logged_in_userid = 0;
$user = 'not logged in';
$logged_in_user = 'not logged in';
$user_group = 'N/A';
$user_banned = false;
if(isset($_SESSION["userId"])) {
    $userInfo = getUserInfo(intval($_SESSION["userId"]));
    $logged_in_userid = $userInfo['userid'];
    $logged_in_user = $userInfo['username'];
    $user_group = ucwords($userInfo['usertype']);
    $user_banned = ($user_group == 'suspended');
}
?>
