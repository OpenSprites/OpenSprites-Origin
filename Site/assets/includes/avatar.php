<?php
// gets the user's image and echos it
$encoded = file_get_contents('http://scratch.mit.edu/site-api/users/all/' . $_GET['username'] . '/');
$decoded = json_decode($encoded);
echo 'Test';
echo $decoded;  //removed closing tag for a reason - this will be included into the register.php page.
