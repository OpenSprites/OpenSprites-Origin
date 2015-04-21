<?php
/**
 * Cron job
 * To prevent popular assets from hogging the spotlight, we should reset download counts each week
 */
if(php_sapi_name() != 'cli'){ // let's not have anyone hacking this. Make sure it runs in CLI mode only
	header("Location: /403.php");
	die();
}
 
include "../assets/includes/database.php";

connectDatabase();
imagesQuery0("UPDATE `" . getAssetsTableName() . "` SET `downloadCount`=?", array(0));
?>