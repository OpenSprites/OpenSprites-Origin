<?php
header("Content-Type: image/png");
if(!isset($_GET['userid'])){
	die(file_get_contents("../assets/images/defaultfile.png"));
}

$userid = intval($_GET['userid']);

$handle = curl_init("http://opensprites.gwiddle.co.uk/forums/uploads/avatars/" . $userid . ".png");
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if($httpCode == 404) {
    die(file_get_contents("../assets/images/defaultfile.png"));
}
curl_close($handle);

echo $response;

?>