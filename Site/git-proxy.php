<?php
  // usage: http://opensprites.x10.mx/live/alpha/git-proxy.php?p=main-style.css
  header("Access-Control-Allow-Origin: *");
  $mime_type = $_GET['mime'];
  if($mime_type != '') {
    header('Content-Type: ' . $mime_type);
  } else {
    header('Content-Type: text/css');
  }
  // default case
  $url = 'https://raw.githubusercontent.com/OpenSprites/OpenSprites/master/Site/' . $_GET['p'];
  
  echo file_get_contents($url);
?>
