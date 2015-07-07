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
$info = NULL;
$assets = NULL;
if(isset($_GET['cid'])){
	$cid = $_GET['cid'];
}

$is_creating = ((isset($_GET['action']) && $_GET['action'] === "create") || $_GET['cid'] == "create");
if($is_creating){
	$cid = NULL;
} else {
	$info = getCollectionInfo($uid, $cid);
	$assets = getAssetsInCollection($uid, $cid);
}
?><!DOCTYPE html>
<html>
<head>
	<?php 
		include '../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
	<script>
		OpenSprites.view = OpenSprites.view || {};
		OpenSprites.view.isCreatingCollection = <?php echo json_encode($is_creating); ?>;
	</script>
</head>
<body>
	<link href='../main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1><?php echo ($is_creating ? "New Collection" : $info['customName'] . " (#" . $info['id'] . ")"); ?></h1>
			<h2><?php echo ($uid === $logged_in_userid ? "You" : $username) . " (#" . $uid . ")"; ?></h2>
			<?php if($is_creating){ ?>
			<form id="create-collection">
				<p class='status'></p>
				<input type="text" name="name" id="collection-name" placeholder="Collection name" maxlength="32" /><br/>
				<button type="submit">Create Collection</button>
			</form>
			<?php } else { ?>
			<div id="collection-description">
				Render markdown here
			</div>
				<?php if($uid == $logged_in_userid){ ?>
				<div id="collection-actions">
					<p class='label'>Actions:</p>
					<button class="action" type="button" id="action-edit">Edit Details</button>
					<button class="action" type="button" id="action-add">Add Assets</button>
					<button class="action on-select" type="button" id="action-remove">Remove Selected Assets</button>
					<button class="action" type="button" id="action-collab">Manage Collaborators</button>
				</div>
				<?php } ?>
			<div id="collection-assets">
				<div id="collection-search">
					<input type="text" placeholder="Search in collection" />
				</div>
				Render asset list here
			</div>
			<?php } ?>
		</div>
	</div>
	<br/><br/>
	
	<script src="/users/collections.js"></script>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>