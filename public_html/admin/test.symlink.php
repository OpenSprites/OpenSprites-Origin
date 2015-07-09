<?php
include '../assets/includes/connect.php';
if(!$is_admin){
	die("Go away");
}

header("Content-Type: text/plain");

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

deleteDir("../uploads/uploaded/");

// - sites root
// | - osdev.opensprites.org
// | | - public_html
// |   | - admin
// |     | - this file
// | - opensprites.org
// | | - public_html
// |   | - uploads
// |     | - uploaded

var_dump(shell_exec("ln -s ../../../opensprites.org/public_html/uploads/uploaded ../uploads/uploaded"));

?>