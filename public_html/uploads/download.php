<?php
require "../assets/includes/connect.php";

$id = -1;
$file = "";
if(isset($_GET['id'])) $id = intval($_GET['id']);
if(isset($_GET['file'])) $file = $_GET['file'];

$asset = imageExists($id, $file);
$filename = NULL;
if(sizeof($asset) > 0){
	$filename = $asset[0]['name'];
	$asset = $asset[0];
}
if($filename == NULL){
	include "../404.php";
	die();
}

$userData = getUserInfo($id);
if($userData['usertype'] == "suspended"){
	include "../404.php";
	die();
}

incrementDownload($id, $file);
$file_url = 'uploaded/' . $filename;
$ext = pathinfo($file_url, PATHINFO_EXTENSION);

function sendHeaders(){
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header('Content-Description: File Transfer');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
}

function dieWith500(){
	include "../500.php";
	die();
}

function newTempFile(){
	$tmp = tempnam("project-build-cache", "os-file");
	if($tmp === FALSE) dieWith500();
	return $tmp;
}

function newTempZip($copyFrom){
	$tmp = tempnam("project-build-cache", "os-project");
	if($tmp === FALSE) dieWith500();
	$res = copy($copyFrom, $tmp);
	if($res === FALSE) dieWith500();
	return $tmp;
}

function zipOpen($filename){
	$zip = new ZipArchive;
	$res = $zip->open($filename);
	if($res !== TRUE) dieWith500();
	$zip->setArchiveComment("Project generated by OpenSprites http://opensprites.org");
	return $zip;
}

function getProjectJson($zip){
	$jsonStr = $zip->getFromName("project.json");
	if($jsonStr === FALSE) dieWith500();
	$json = json_decode($jsonStr, TRUE);
	if($json === NULL) dieWith500();
	return $json;
}

function setProjectJson($zip, $json){
	$jsonStr = json_encode($json, JSON_UNESCAPED_SLASHES);
	if($jsonStr === FALSE) dieWith500();
	$res = $zip->addFromString("project.json", $jsonStr);
	if($res === FALSE) dieWith500();
}

function addFile($zip, $filename, $localname){
	$res = $zip->addFile($filename, $localname);
	if($res === FALSE) dieWith500();
}

function closeAndPrint($zip, $tmpname){
	$res = $zip->close();
	if($res === FALSE) dieWith500();
	
	sendHeaders();
	header("Content-Length: " . filesize($tmpname));
	
	readfile($tmpname);
	
	unlink($tmpname);
}

function getScript($filename){
	$jsonStr = file_get_contents($filename);
	return json_decode($jsonStr, TRUE);
}

if($asset['assetType'] == "script"){
	$tmp = newTempZip("../assets/sb2/default.sb2");
	$zip = zipOpen($tmp);
	
	$project = getProjectJson($zip);
	$project['children'][0]['scripts'] = array(array(0, 0, getScript($file_url)));
	
	setProjectJson($zip, $project);
	
	header("Content-disposition: attachment; filename=\"" . $asset[0]['customName'] . ".sb2\"");
	
	closeAndPrint($zip, $tmp);
} else if($ext == "gif") {
	// todo: implement
} else {
	sendHeaders();
	header('Content-Length: ' . filesize($file_url));
	header("Content-disposition: attachment; filename=\"" . $asset[0]['customName'] . '.' . $ext . "\"");
	readfile($file_url);
}
?>