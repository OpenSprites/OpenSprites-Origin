<?php
$username = "opensprites";
$password = "swagmaster123"; // change this :P
$db_name  = "opensprites";
$assets_table_name = "os_assets";

$dbh;

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

function getAllImages(){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("SELECT * FROM `$assets_table_name`");
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function createImagesTable(){
	global $dbh;
	global $assets_table_name;
	$dbh->exec(
		"CREATE TABLE `$assets_table_name` (
			`name` VARCHAR(20) NOT NULL,
			`hash` VARCHAR(32) NOT NULL,
			`user` VARCHAR(32) NOT NULL,
			`userid` INT(11) NOT NULL,
			`date` DATETIME NOT NULL,
			UNIQUE KEY `asset_ix` (`name`, `hash`)
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

function addImageRow($name, $hash, $user, $userId){
	global $dbh;
	global $assets_table_name;
	$stmt = $dbh->prepare("INSERT INTO `$assets_table_name` (`name`,`hash`,`user`,`userid`,`date`) VALUES(:name, :hash, :user, :userId, NOW())");
	$stmt->execute(array(":name"=>$name, ":hash"=>$hash, ":user"=>$user, ":userId"=>$userId));
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