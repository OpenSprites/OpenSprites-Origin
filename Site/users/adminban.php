<?php
require "../assets/includes/connect.php";

if(!$is_admin){
	include '../403.php';
	die();
}

connectDatabase();

if(!isset($_GET['username']) || !isset($_GET['type'])) die("Missing params");

if($_GET['type'] == "ban"){
	setSuspendedStatus($_GET['username'], TRUE);
} else {
	if($_GET['type'] == "unban"){
		setSuspendedStatus($_GET['username'], FALSE);	
	} else {
		die("Welp, idk what to do.");
	}
}
echo "Success";
?>