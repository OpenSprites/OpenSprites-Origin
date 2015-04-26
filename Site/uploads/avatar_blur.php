<?php
header("Content-Type: image/png");
if(!isset($_GET['userid'])){
	die(file_get_contents("../assets/images/defaultfile.png"));
}

$userid = intval($_GET['userid']);
//                              \/ not sure how php will handle the underscore so let's be safe
if(file_exists("thumb-cache/".$userid."_blur.png")){
	die(file_get_contents("thumb-cache/".$userid."_blur.png"));
}

$handle = curl_init("http://opensprites.gwiddle.co.uk/forums/uploads/avatars/" . $userid . ".png");
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if($httpCode == 404) {
    die(file_get_contents("../assets/images/defaultfile.png"));
}
curl_close($handle);

$source = imagecreatefromstring($response);
$width = imagesx($source);
$height = imagesy($source);
$desired_width = 1920;
$desired_height = floor($height * ($desired_width / $width));
$resized = imagecreatetruecolor($desired_width, $desired_height);
imagecopyresampled($resized, $source, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
imagedestroy($source);

// http://stackoverflow.com/a/7245782/1021196
$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
imageconvolution($resized, $gaussian, 16, 0);

// output to browser
imagepng($resized);
// save to cache
imagepng($resized, "thumb-cache/".$userid."_blur.png");
imagedestroy($resized);
?>