<?php
include_once '../assets/includes/connect.php';
include '../assets/includes/collections.php';

if(!isset($_GET['uid'])){
	include '../404.php';
	die();
}

$uid = intval($_GET['uid']);
$username = getUserInfo($uid)['username'];

$cid = NULL;
if(isset($_GET['cid'])){
	$cid = $_GET['cid'];
}

$is_creating = (isset($_GET['action']) && $_GET['action'] === "create");
if($is_creating){
	$cid = NULL;
}

$info = getCollectionInfo($uid, $cid);
$assets = getAssetsInCollection($uid, $cid);
?><!DOCTYPE html>
<html>
<head>
	<?php 
		include '../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
</head>
<body>
	<link href='../main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1><?php echo ($is_creating ? "New Collection" : $info['customName'] . "(#" . $info['id'] . ")"); ?></h1>
			<h2><?php echo ($uid === $logged_in_userid ? "You" : $username) . "(#" . $uid . ")"; ?></h2>
			<p>Include more stuff here</p>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
