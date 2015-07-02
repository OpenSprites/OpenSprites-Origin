<?php
require '../assets/includes/connect.php';

unset($_SESSION["userId"]);
session_unset();
session_destroy();

et_regenerateToken();
?>