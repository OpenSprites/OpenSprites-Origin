<?php
header("Content-Type: text/plain");
include '../assets/includes/connect.php';
if(!$is_admin){
	die("Go away");
}

$ht1 = file_get_contents("../.htaccess");
$db1 = file_get_contents("../assets/includes/database.php");

$ht2 = file_get_contents("https://raw.githubusercontent.com/OpenSprites/OpenSprites/master/public_html/assets/includes/database.php");
$db2 = file_get_contents("https://raw.githubusercontent.com/OpenSprites/OpenSprites/master/public_html/.htaccess");

echo ".htaccess: ";
if($ht1 == $ht2){
	echo "Up to date";
} else {
	echo "Not updated!";
}
echo "\n";

$db1 = preg_replace("/password = \"[^\"]+\"", "password = \"\"", $db1);

echo "database.php: ";
if($db1 == $db2){
	echo "Up to date";
} else {
	echo "Not updated!";
}
echo "\n";
?>