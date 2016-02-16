<?php
  $json = file_get_contents('https://opensprites.org/site-api/user.php');
  
  $obj = json_decode($json);
  echo $obj->{'username'}; 
?>
<!-- test -->
