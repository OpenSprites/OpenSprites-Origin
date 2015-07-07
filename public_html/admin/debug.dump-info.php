<?php
header("Content-Type: text/plain");
include '../assets/includes/connect.php';
if(!$is_admin){
	die("Go away");
}

var_dump(phpinfo());
?>