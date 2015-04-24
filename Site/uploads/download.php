<?php
require "../assets/includes/connect.php";
connectDatabase();
$id = -1;
$file = "";
if(isset($_GET['id'])) $id = intval($_GET['id']);
if(isset($_GET['file'])) $file = $_GET['file'];

$asset = imageExists($id, $file);
$filename = NULL;
if(sizeof($asset) > 0){
	$filename = $asset[0]['name'];
}
if($filename == NULL){
	include "../404.php";
	die;
}

incrementDownload($id, $file);
$file_url = '/uploads/uploaded/'.$asset[0]['name'];
$ext = pathinfo($file_url, PATHINFO_EXTENSION);

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . $asset[0]['customName'] . '.' . $ext . "\"");

readfile($file_url);
?>
