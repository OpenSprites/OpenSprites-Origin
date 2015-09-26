<?php
require __DIR__."../includes/connect.php";
header("Content-Type: text/plain");

var_dump($userInfo);
var_dump($logged_in_userid);
var_dump($logged_in_user);
var_dump($user_group);
var_dump($user_banned);
var_dump($user_admin);
?>