<?php
require 'lib.php';

/* temporary thing */
$return = true;
if(rand(0, 1) == 0) {
    $return = 'Heh, tails. :P';
}

// return true if the reporting was successful, else an error message to be displayed to the user
echo $return;
?>
