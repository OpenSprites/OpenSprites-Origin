<?php
header("Content-Type: text/plain");
include '../assets/includes/connect.php';
if(!$is_admin){
	die("Go away");
}

echo file_get_contents("../.htaccess");
?>