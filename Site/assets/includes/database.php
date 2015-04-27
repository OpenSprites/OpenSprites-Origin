<?php
$username = "OpenSprites_user";
$password = "swagmaster123"; // change this :P
$db_name  = "OpenSprites_assets";
$assets_table_name = "os_assets";
$user_upload_table_name = "os_user_upload";

$forum_username = "OpenSprites_os";
$forum_password = "ZfgKxh24PP";
$forum_db_name = "OpenSprites_os";
$forum_member_table = "et_member";
$forum_group_table = "et_group";
$forum_group_member_table = "et_member_group";
$forum_profile_data_table = "et_profile_data";

$dbh;
$forum_dbh;

function connectForumDatabase(){
	global $forum_dbh;
	global $forum_username;
	global $forum_password;
	global $forum_db_name;
	$conf = 'mysql:host=localhost;dbname='.$forum_db_name.';charset=utf8';
	$forum_dbh = new PDO($conf, $forum_username, $forum_password);
	$forum_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$forum_dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}

function forumQuery($query, $parameters){
	global $forum_dbh;
	$stmt = $forum_dbh->prepare($query);
	$stmt->execute($parameters);
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function setAccountType($username, $type){
	global $forum_dbh;
	global $forum_member_table;
	
	$stmt = $forum_dbh->prepare("UPDATE `$forum_member_table` SET `account`=? WHERE `username`=?");
	$stmt->execute(array($type, $username));
}

function getUserInfo($userid){
	global $forum_member_table;
	global $forum_group_table;
	global $forum_group_member_table;
	$res = forumQuery("SELECT * FROM `$forum_member_table` WHERE `memberId`=?", array($userid));
	if(sizeof($res) == 0) return FALSE;
	
	// the order by here is so we can avoid having to look through the entire array and access by groupId - 1
	$groupRes = forumQuery("SELECT * FROM `$forum_group_table` ORDER BY `groupId`", array());
	$memberGroupRes = forumQuery("SELECT * FROM `$forum_group_member_table` WHERE `memberId`=?", array($userid));
	$groups = array();
	for($i=0;$i<sizeof($memberGroupRes);$i++){
		$groupId = $memberGroupRes[$i]['groupId'];
		$groupName = $groupRes[intval($groupId) - 1]['name'];
		array_push($groups, $groupName);
	}
	
	// look up profile fields
	$about = "No about section given";
	$location = "No location set";
	$profileRes = forumQuery("SELECT * FROM `$forum_profile_data_table` WHERE `memberId`=?", array($userid));
	for($j=0;$j<sizeof($profileRes);$j++){
		if($profileRes[$j]['fieldId'] == 1) $about = $profileRes[$j]['data'];
		if($profileRes[$j]['fieldId'] == 2) $location = $profileRes[$j]['data'];
	}
	
	$userInfo = array(
		"userid" => $userid,
		"username" => $res[0]['username'],
		"usertype" => $res[0]['account'],
		"groups" => $groups,
		"about" => $about,
		"location" => $location
	);
	return $userInfo;
}

function connectDatabase(){
	global $dbh;
	global $username;
	global $password;
	global $db_name;
	global $assets_table_name;
	global $user_upload_table_name;
	$conf = 'mysql:host=localhost;dbname='.$db_name.';charset=utf8';
	$dbh = new PDO($conf, $username, $password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	if(!tableExists($assets_table_name)) createImagesTable();
	if(!tableExists($user_upload_table_name)) createUserUploadTable();
}

function getDatabaseError(){
	return $dbh->errorInfo();
}

function getAssetsTableName(){
	global $assets_table_name;
	return $assets_table_name;
}

function imagesQuery($query, $parameters){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare($query);
	$stmt->execute($parameters);
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function imagesQuery0($query, $parameters){ // couldn't think of a better name, sorry
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare($query);
	$stmt->execute($parameters);
}

function getAllImages(){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name`");
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function getImagesForUser($userId){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name` WHERE `userid`=? ORDER BY `date` DESC");
	$stmt->execute(array($userId));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function createImagesTable(){
	global $dbh;
	global $assets_table_name;
	$dbh->exec(
		"CREATE TABLE `$assets_table_name` (
			`name` VARCHAR(32) NOT NULL,
			`hash` VARCHAR(32) NOT NULL,
			`user` VARCHAR(32) NOT NULL,
			`userid` INT(11) NOT NULL,
			`assetType` VARCHAR(16) NOT NULL,
			`customName` VARCHAR(32) NOT NULL,
			`description` VARCHAR(500) NOT NULL DEFAULT 'No description provided',
			`date` DATETIME NOT NULL,
			`downloadCount` INT(11) NOT NULL DEFAULT 0,
			`downloadsThisWeek` INT(11) NOT NULL DEFAULT 0,
			`isFeatured` INT(11) NOT NULL DEFAULT 0,
			PRIMARY KEY `asset_ix` (`name`, `customName`, `userid`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
}

function createUserUploadTable(){
	global $dbh;
	global $user_upload_table_name;
	$dbh->exec(
		"CREATE TABLE `$user_upload_table_name` (
			`userid` INT(11) NOT NULL,
			`bytesUploaded` INT(11) NOT NULL,
			`lastUploadTime` DATETIME NOT NULL,
			PRIMARY KEY `user_ix` (`userid`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
}

function isUserAbleToUpload($userid, $post_size){
	global $dbh;
	global $user_upload_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$user_upload_table_name` WHERE `userid`=?");
	$stmt->execute(array($userid));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(sizeof($res) == 0){
		$stmt2 = $dbh->prepare("INSERT INTO `$user_upload_table_name` (`userid`,`bytesUploaded`,`lastUploadTime`)"
			. " VALUES(:userid, :postSize, NOW())");
		$stmt2->execute(array(":userid"=>$userid, ":postSize" => $post_size));
		return TRUE;
	} else {
		$lastDate = $res[0]['lastUploadTime'];
		
		$stmt2 = $dbh->prepare("SELECT UNIX_TIMESTAMP(?) as timestamp");
		$stmt2->execute(array($lastDate));
		$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		$lastDate = $res2[0]['timestamp'];
		
		$uploadSize = $res[0]['bytesUploaded'];
		
		$bytes_per_sec = 1024 * 1024 * 10 / 60; // 10MB / min
		$val1 = time() - $lastDate;
		$val2 = $uploadSize / $bytes_per_sec;
		if($val1 > $val2){
			$stmt3 = $dbh->prepare("UPDATE `$user_upload_table_name` SET `lastUploadTime`=NOW(), `bytesUploaded`=:bytes WHERE `userid`=:userid");
			$stmt3->execute(array(":bytes" => $post_size, ":userid" => $userid));
			return TRUE;
		} else {
			return $val2 - $val1; // no spam pls
		}
	}
}

function imageExists($userid, $hash){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name` WHERE `userid`=? AND `hash`=?");
	$stmt->execute(array($userid, $hash));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function incrementDownload($userid, $hash){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("UPDATE `$assets_table_name` SET `downloadCount`=`downloadCount` + 1, `downloadsThisWeek`=`downloadsThisWeek` + 1 WHERE `userid`=? AND `hash`=?");
	$stmt->execute(array($userid, $hash));
}

function addImageRow($name, $hash, $user, $userId, $assetType, $customName){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("INSERT INTO `$assets_table_name` (`name`,`hash`,`user`,`userid`,`assetType`,`customName`,`date`)"
		." VALUES(:name, :hash, :user, :userId, :assetType, :customName, NOW())");
	$stmt->execute(array(":name"=>$name, ":hash"=>$hash, ":user"=>$user, ":userId"=>$userId, ":assetType"=>$assetType,":customName"=>$customName));
}

function tableExists($id){
	global $dbh;
    $results = $dbh->query("SHOW TABLES LIKE '$id'");
    if($results->rowCount()>0){
		return TRUE;
	}
	return FALSE;
}
?>