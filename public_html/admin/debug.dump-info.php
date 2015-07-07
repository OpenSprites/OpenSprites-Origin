<?php
include '../assets/includes/connect.php';
if(!$is_admin){
	die("Go away");
}

phpinfo();
?>