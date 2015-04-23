<?php
include "../assets/includes/database.php";
connectDatabase();

$lastDate = "2015-04-23 19:18:10";

$stmt2 = $dbh->prepare("SELECT UNIX_TIMESTAMP(?) AS timestamp");
$stmt2->execute(array($lastDate));
$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$lastDate = $res2[0]['timestamp'];
header("Content-type: text/plain");
var_dump(
	$lastDate
);
?>