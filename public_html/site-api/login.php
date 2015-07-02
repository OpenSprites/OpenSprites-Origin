<?php
include 'lib.php';
require_once "../assets/lib/phpass/PasswordHash.php";

if($_SERVER['REQUEST_METHOD'] !== "POST"){
	die(json_encode(array("status"=>"error","code"=>"post", "message"=>"GET requests are not accepted")));
}

if($logged_in_userid !== 0) {
	die(json_encode(array("status"=>"error","code"=>"loggedin","message"=>"Already logged in")));
}

if(!isset($_POST['token']) || !isset($_POST['username']) || !isset($_POST['password'])) {
	die(json_encode(array("status"=>"error","code"=>"badreq","message"=>"Missing params")));
}

if($_SESSION['token'] !== $_POST['token']){
	die(json_encode(array("status"=>"error","code"=>"haxxor","message"=>"Bad request, refresh and try again")));
}

$username = $_POST['username'];
$password = $_POST['password'];

$userObj = forumQuery("SELECT * FROM `et_member` WHERE `username`=? OR `email`=?", array($username, $username));
if(sizeof($userObj) === 0) {
	die(json_encode(array("status"=>"error","code"=>"failed","message"=>"Wrong username or password")));
}

$userObj = $userObj[0];
$userId = intval($userObj['memberId']);

$hasher = new PasswordHash(8, FALSE);
if(!$hasher->CheckPassword($password, $userObj['password'])){
	// failed, deal with spamming
	$isAble = isFailedLoginAcceptable($userId);
	if($isAble === TRUE){
		die(json_encode(array("status"=>"error","code"=>"failed","message"=>"Wrong username or password")));
	} else {
		// perhaps require a captcha? A 2 minute lockout doesn't seem that secure
		die(json_encode(array("status"=>"error","code"=>"failed","wait"=>$isAble,"message"=>"Wrong username or password, wait $isAble seconds before trying again")));
	}
} else {
	// login was successful
	$_SESSION["userId"] = $userId;
	echo json_encode(array("status"=>"success","code"=>"success","message"=>"Login successful"));
}
?>