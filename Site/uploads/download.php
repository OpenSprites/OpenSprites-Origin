<?php

$file_url = 'http://dev.opensprites.gwiddle.co.uk/uploads/uploaded/' . $_GET['file'];
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . $_GET['name'] . '.' . substr($_GET['file'], strrpos($_GET['file'], '.') + 1) . "\"");

readfile($file_url);

?>
