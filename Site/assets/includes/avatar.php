<?php
// gets the user's image and echos it
$encoded = file_get_contents('http://scratch.mit.edu/site-api/users/all/' . $_GET['u'] . '/');
echo $encoded;
?>
