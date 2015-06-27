<?php

function addCollection($user, $userId, $customName, $assets){
	$id;
	$res;
	do {
		$id = md5(uniqid(mt_rand()));
		$res = imagesQuery("SELECT * FROM `" . getCollectionTableName() . "` WHERE `id`=?", array($id));
	} while(sizeof($res) > 0);
	
	imagesQuery0("INSERT INTO `" . getCollectionTableName() . "` (`id`, `user`,`userid`,`customName`,`date`)"
		." VALUES(?, ?, ?, ?, NOW())", array($id, $user, $userId, $customName));
	
	for($i=0;$i<sizeof($assets);$i++){
		imagesQuery0("INSERT INTO `" . getCollectionAssetTableName() . "` (`userid`, `collectionid`, `assetuserid`, `assetid`)"
			. " VALUES(?, ?, ?, ?)", array($userId, $id, $assets[$i]['userid'], $assets[$i]['hash']));
	}
}

function addToCollection($userId, $collectionId, $asset){
	imagesQuery0("INSERT INTO `" . getCollectionAssetTableName() . "` (`userid`, `collectionid`, `assetuserid`, `assetid`)"
			. " VALUES(?, ?, ?, ?)", array($userId, $collectionId, $asset['userid'], $asset['hash']));
}

function removeFromCollection($userId, $collectionId, $asset){
	imagesQuery0("DELETE FROM `" . getCollectionAssetTableName() . "` WHERE `userid`=? AND `collectionid`=? AND `assetuserid`=? AND `assetid`=?",
		array($userId, $collectionId, $asset['userid'], $asset['hash']));
}

function getCollectionInfo($userId, $collectionId){
	return imagesQuery("SELECT * FROM `" . getCollectionTableName() . "` WHERE `userid`=? AND `id`=?", array($userId, $collectionId));
}

function getAssetsInCollection($userId, $collectionId){
	return imagesQuery("SELECT * FROM `" . getCollectionAssetTableName() . "` WHERE `userid`=? AND `collectionid`=?", array($userId, $collectionId));
}

?>