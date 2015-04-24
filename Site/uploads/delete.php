<?php
    require "../assets/includes/connect.php";
    
    connectDatabase();
	$id = $logged_in_userid;
	$file = "";
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
	
	imagesQuery0("DELETE FROM `".getAssetsTableName()."` WHERE `userid`=? AND `hash`=?", array($id, $hash));
	
	$res = imagesQuery("SELECT * FROM `".getAssetsTableName()."` WHERE `hash`=?", array($file));
	if(sizeof($res) == 0){
		unlink($file_url);
	}

    header('Location: /');
?>
