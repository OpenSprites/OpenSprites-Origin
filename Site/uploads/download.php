<?php

$file_url = 'http://dev.opensprites.gwiddle.co.uk/uploads/uploaded/' . $_GET['file'];
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");

readfile($file_url);

?>
