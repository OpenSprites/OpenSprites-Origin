<?php
require '../assets/includes/connect.php';
connectDatabase();

//Checks if both the values have been provided
if(!(isset($_GET['md5']) and isset($_GET['featured']))){
    die("Not enough arguments supplied");
}

$md5 = mysqli_real_escape_string($con,$_GET['md5']);
$feature = boolval($_GET['featured']);

if ($is_admin == false){
    echo '403 - Permission Denied';
    die();
}

//Check if asset exists
$query = imagesQuery("SELECT * FROM `" . getAssetsTableName() . "` WHERE `md5`='" . $md5 . "'");
$numrows = mysqli_num_rows($query);
if ($numrows == 0){
    die("No such asset with that md5 hash.");
}else{
    //Then update the asset isFeatured
    imagesQuery("UPDATE `" . getAssetsTableName() . "` SET `isFeatured`='" . $feature . "' WHERE `md5`='" . $md5 . "'");
}

?>