<?php
  $variable = file_get_contents('http://opensprites.org/site-api/user.php');
  $decoded = json_decode($variable);
  echo $decoded;
?>
