<?php
include '../assets/includes/connect.php';
if(!$is_admin){
	die("403");
}
$directory = "thumb-cache";
$files = glob("{$directory}/*");
foreach($files as $file){
  if(is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == "png")
    unlink($file);
}
echo "Success";
?>