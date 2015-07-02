<?php
require 'lib.php';
et_regenerateToken();
echo json_encode(array("token"=>$_SESSION['token']));
?>