<?php
include '../assets/includes/connect.php';
if(!$is_admin){
	die();
}

$files = glob('thumb-cache/');
foreach($files as $file){
  if(is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == "png")
    unlink($file);
}
?>