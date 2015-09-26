<?php
require __DIR__."../../includes/connect.php";

if(!$is_admin){
	include '../403.php';
	die();
}

connectDatabase();

if(!isset($_GET['username']) || !isset($_GET['type'])) die("Missing params");

if($_GET['type'] == "ban"){
	setAccountType($_GET['username'], "suspended");
} else {
	if($_GET['type'] == "unban"){
		setAccountType($_GET['username'], "member");	
	} else {
		die("Welp, idk what to do.");
	}
}
echo "Success. <a href='javascript:window.history.back();'>Back</a>";
?>