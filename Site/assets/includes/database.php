<?php
$username = "OpenSprites_user";
$password = "swagmaster123"; // change this :P
$db_name  = "OpenSprites_assets";
$assets_table_name = "os_assets";

$forum_username = "OpenSprites_os";
$forum_password = "ZfgKxh24PP";
$forum_db_name = "OpenSprites_os";
$forum_member_table = "et_member";
$forum_group_table = "et_group";
$forum_group_member_table = "et_member_group";

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
	
	$userInfo = array(
		"userid" => $userid,
		"username" => $res[0]['username'],
		"usertype" => $res[0]['account'],
		"groups" => $groups
	);
	return $userInfo;
}

function connectDatabase(){
	global $dbh;
	global $username;
	global $password;
	global $db_name;
	global $assets_table_name;
	$conf = 'mysql:host=localhost;dbname='.$db_name.';charset=utf8';
	$dbh = new PDO($conf, $username, $password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	if(!tableExists($assets_table_name)) createImagesTable();
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
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name` WHERE `userid`=? ORDER BY `date`");
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
			`date` DATETIME NOT NULL,
			`downloadCount` INT(11) NOT NULL DEFAULT 0,
			`downloadsThisWeek` INT(11) NOT NULL DEFAULT 0,
			PRIMARY KEY `asset_ix` (`name`, `customName`, `userid`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
}

function imageExists($hash){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name` WHERE `hash`=?");
	$stmt->execute(array($hash));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
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