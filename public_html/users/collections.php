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
	<style>
		h1 {
			font-size: 48px;
			font-weight: 300;
			text-align: center;
		}
        
		#id {
			font-size: 20px;
			margin-top: -10px;
		}
        
	        #description {
	            font-size: 22px;
	            text-align: center;
	            margin-top: 30px;
	        }
	        
	        #collection-search {
	        	margin-bottom: 15px;
	        }
	        
	        /* When creating collection */
	        #create-collection {
	            width: 150px;
	            margin: auto;
	        }
	        
	</style>
</head>
<body>
	<link href='../main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1><?php echo ($is_creating ? "New Collection" : $info['customName'] . " <br><!--Is there a reason why we need this ID?--><div id='id'>(#" . $info['id'] . ")</div>"); ?></h1>
			<div id="description">
				<strong>By: </strong> <a href="http://opensprites.org/users/<?php echo urlencode($username); ?>" target="blank"><?php echo ($uid === $logged_in_userid ? "You" : $username) /*. " (#" . $uid . ")"*/; ?></a>
			</div>
            
			<!--<h2><?php //echo ($uid === $logged_in_userid ? "You" : $username) . " (#" . $uid . ")"; ?></h2>-->
            
			<?php if($is_creating){ ?>
			<form id="create-collection">
				<p class='status'></p>
				<input type="text" name="name" id="collection-name" placeholder="Collection name" maxlength="32" /><br/>
                <!-- I'll remove these deprecated <center> tags once I discovered a workaround.. -->
				<center><button class="btn orange" type="submit">Create Collection</button></center>
			</form>
            
			<?php } else { ?>
			<div id="collection-description">
				Render markdown here
			</div><br/>
            
				<?php if($uid == $logged_in_userid){ ?>
				<div id="collection-actions">
					<p class='label' style='float:left;margin-top:2px;margin-right:8px;'>Actions:</p>
					<button class="action btn orange" type="button" id="action-edit">Edit Details</button>
					<button class="action btn orange" type="button" id="action-add">Add Assets</button>
					<button class="action on-select btn orange" type="button" id="action-remove">Remove Selected Assets</button>
					<button class="action btn orange" type="button" id="action-collab">Manage Collaborators</button>
				</div><br/>
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
