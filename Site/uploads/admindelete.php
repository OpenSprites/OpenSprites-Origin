<?php
    require "../assets/includes/connect.php";

    if($is_admin == false) {
        include '../403.php';
        die();
    }
    
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
	
	$file_url = 'uploaded/'.$asset[0]['name'];
	
	imagesQuery0("DELETE FROM `".getAssetsTableName()."` WHERE `userid`=? AND `hash`=?", array($id, $file));
	
	if(isset($_GET['unlink']) && $_GET['unlink'] == "force_unlink"){
		// used in extreme cases when we need to delete the file completely. This should probably never be needed
		imagesQuery0("DELETE FROM `".getAssetsTableName()."` WHERE `hash`=?", array($file));
		unlink($file_url);
	} else {
		// if no one else has the file, delete it
		$res = imagesQuery("SELECT * FROM `".getAssetsTableName()."` WHERE `hash`=?", array($file));
		if(sizeof($res) == 0){
			unlink($file_url);
		}
	}

    header('Location: /');
?>
