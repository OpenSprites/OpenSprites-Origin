<?php
require 'lib.php';

if(!$is_admin){
	include "../403.php";
	die();
}
connectDatabase();

echo json_encode(getAllReports());
?>