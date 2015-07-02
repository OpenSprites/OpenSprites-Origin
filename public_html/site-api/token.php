<?php
require '../assets/includes/connect.php';
et_regenerateToken();
echo json_encode(array("token"=>$_SESSION['token']));
?>