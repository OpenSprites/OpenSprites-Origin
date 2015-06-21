<?php
$username = "OpenSprites_user";
$password = "";
$db_name  = "OpenSprites_assets";
$assets_table_name = "os_assets";
$user_upload_table_name = "os_user_upload";
$user_report_table_name = "os_user_report";
$report_table_name = "os_reports";
$collections_table_name = "os_collections";
$collection_asset_table_name = "os_collection_asset";

$forum_username = "OpenSprites_os";
$forum_password = "";
$forum_db_name = "OpenSprites_os";
$forum_member_table = "et_member";
$forum_group_table = "et_group";
$forum_group_member_table = "et_member_group";
$forum_profile_data_table = "et_profile_data";

$dbh;
$forum_dbh;

// connect.php now automagically connects both databases, but other files may try to connect again
// until we remove all unnecessary connect statements, we'll need to keep state variables
$database_connected = FALSE;
$forum_database_connected = FALSE;

function getDbh(){
	global $dbh;
	return $dbh;
}

function getForumDbh(){
	global $forum_dbh;
	return $forum_dbh;
}

function connectForumDatabase(){
	global $forum_dbh;
	global $forum_username;
	global $forum_password;
	global $forum_db_name;
	global $forum_database_connected;
	if($forum_database_connected){
		return;
	}
	$conf = 'mysql:host=10.0.0.5;dbname='.$forum_db_name.';charset=utf8';
	$forum_dbh = new PDO($conf, $forum_username, $forum_password);
	$forum_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$forum_dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$forum_database_connected = TRUE;
}

function forumQuery($query, $parameters){
	global $forum_dbh;
	$stmt = $forum_dbh->prepare($query);
	$stmt->execute($parameters);
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function forumQuery0($query, $parameters){
	global $forum_dbh;
	$stmt = $forum_dbh->prepare($query);
	$stmt->execute($parameters);
}

function setAccountType($username, $type){
	global $forum_dbh;
	global $forum_member_table;
	
	$stmt = $forum_dbh->prepare("UPDATE `$forum_member_table` SET `account`=? WHERE `username`=?");
	$stmt->execute(array($type, $username));
}

function setProfileSettings($userid, $settings){
	global $forum_dbh;
	
	$res = forumQuery("SELECT * FROM `os_profile_settings` WHERE `userid`=?", array($userid));
	if(sizeof($res) == 0){
		$stmt = $forum_dbh->prepare("INSERT INTO `os_profile_settings` (`userid`, `bgcolor`) VALUES (:userid, :bgcolor)");
		$stmt->execute(array(":userid" => $userid, ":bgcolor" => $settings['bgcolor']));
	} else {
		$stmt = $forum_dbh->prepare("UPDATE `os_profile_settings` SET `bgcolor`=:bgcolor WHERE `userid`=:userid");
		$stmt->execute(array(":userid" => $userid, ":bgcolor" => $settings['bgcolor']));
	}
}

function getProfileSettings($userid){
	global $forum_dbh;
	
	$res = forumQuery("SELECT * FROM `os_profile_settings` WHERE `userid`=?", array($userid));
	if(sizeof($res) == 0) return array("bgcolor" => "avatar");
	
	return $res[0];
}

function getUserInfo($userid){
	global $forum_member_table;
	global $forum_group_table;
	global $forum_group_member_table;
	global $forum_profile_data_table;
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
		if($profileRes[$j]['fieldId'] == 1) {
			$about = $profileRes[$j]['data'];
		}
		if($profileRes[$j]['fieldId'] == 2){
			$location = $profileRes[$j]['data'];
		}
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
	global $report_table_name;
	global $database_connected;
	global $collections_table_name;
	global $collection_asset_table_name;
	if($database_connected){
		return;
	}
	$conf = 'mysql:host=10.0.0.5;dbname='.$db_name.';charset=utf8';
	$dbh = new PDO($conf, $username, $password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$database_connected = TRUE;
}

function getDatabaseError(){
	return $dbh->errorInfo();
}

function getAssetsTableName(){
	global $assets_table_name;
	return $assets_table_name;
}

function getReportsTableName(){
	global $report_table_name;
	return $report_table_name;
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

function getCollectionTableName(){
	global $collections_table_name;
	return $collections_table_name;
}

function getCollectionAssetTableName(){
	global $collection_asset_table_name;
	return $collection_asset_table_name;
}

function addReport($type, $id, $reporter, $reason){
	global $report_table_name;
	// make sure we haven't already reported
	if(sizeof(imagesQuery("SELECT * FROM `$report_table_name` WHERE `reporter`=? AND `id`=?", array($reporter, $id))) > 0) return FALSE;
	imagesQuery0("INSERT INTO `$report_table_name` (
			`type`,
			`id`,
			`reporter`,
			`reason`,
			`reportTime`
		) VALUES(:type, :id, :reporter, :reason, NOW())", array(
			":type" => $type,
			":id" => $id,
			":reporter" => $reporter,
			":reason" => $reason
		)
	);
	return TRUE;
}

function getAllReports(){
	global $report_table_name;
	return imagesQuery("SELECT *  FROM `$report_table_name` ORDER BY `reportTime` DESC", array());
}

function isUserAbleToReport($userid){
	global $dbh;
	global $user_report_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$user_report_table_name` WHERE `userid`=?");
	$stmt->execute(array($userid));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(sizeof($res) == 0){
		$stmt2 = $dbh->prepare("INSERT INTO `$user_report_table_name` (`userid`,`lastReportTime`, `ipAddr`, `userAgent`)"
			. " VALUES(:userid, NOW(), :ip, :ua)");
		$stmt2->execute(array(":userid"=>$userid, ":ip" => $_SERVER['REMOTE_ADDR'], ":ua" => $_SERVER['HTTP_USER_AGENT']));
		return TRUE;
	} else {
		$lastDate = $res[0]['lastReportTime'];
		
		$stmt2 = $dbh->prepare("SELECT UNIX_TIMESTAMP(?) as timestamp");
		$stmt2->execute(array($lastDate));
		$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		$lastDate = $res2[0]['timestamp'];
		
		$reports_per_sec = 1 / 60; // 1 report per minute
		$val1 = time() - $lastDate;
		$val2 = 1 / $reports_per_sec;
		if($val1 > $val2){
			$stmt3 = $dbh->prepare("UPDATE `$user_report_table_name` SET `lastReportTime`=NOW(), `ipAddr`=:ip, `userAgent`=:ua WHERE `userid`=:userid");
			$stmt3->execute(array(":userid" => $userid, ":ip" => $_SERVER['REMOTE_ADDR'], ":ua" => $_SERVER['HTTP_USER_AGENT']));
			return TRUE;
		} else {
			return $val2 - $val1; // no spam pls
		}
	}
}

function isUserAbleToUpload($userid, $post_size){
	global $dbh;
	global $user_upload_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$user_upload_table_name` WHERE `userid`=?");
	$stmt->execute(array($userid));
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(sizeof($res) == 0){
		$stmt2 = $dbh->prepare("INSERT INTO `$user_upload_table_name` (`userid`,`bytesUploaded`,`lastUploadTime`, `ipAddr`, `userAgent`)"
			. " VALUES(:userid, :postSize, NOW(), :ip, :ua)");
		$stmt2->execute(array(":userid"=>$userid, ":postSize" => $post_size, ":ip" => $_SERVER['REMOTE_ADDR'], ":ua" => $_SERVER['HTTP_USER_AGENT']));
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
			$stmt3 = $dbh->prepare("UPDATE `$user_upload_table_name` SET `lastUploadTime`=NOW(), `bytesUploaded`=:bytes, `ipAddr`=:ip, `userAgent`=:ua WHERE `userid`=:userid");
			$stmt3->execute(array(":bytes" => $post_size, ":userid" => $userid, ":ip" => $_SERVER['REMOTE_ADDR'], ":ua" => $_SERVER['HTTP_USER_AGENT']));
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

function getUserGroups($userid){
	$user_groups0 = forumQuery("SELECT * FROM `et_member_group` WHERE `memberId`=?", array($userid));
	$user_groups = array();
	for($i=0; $i<sizeof($user_groups0); $i++){
		array_push($user_groups, $user_groups0[$i]['groupId']);
	}
	return $user_groups;
}

function getAllGroups(){
	$groups0 = forumQuery("SELECT * FROM `et_group`", array());
	$groups = array();
	for($i=0; $i<sizeof($groups0); $i++){
		$groups[$groups0[$i]['groupId']] = $groups0[$i]['name'];
	}
	return $groups;
}

function setUserGroups($userid, $groups){
	forumQuery0("DELETE FROM `et_member_group` WHERE `memberId`=?", array($userid));
	for($i=0;$i<sizeof($groups);$i++){
		$groupId = $groups[$i];
		forumQuery0("INSERT INTO `et_member_group` (`memberId`,`groupId`) VALUES (?, ?)", array($userid, $groupId));
	}
}
?>
