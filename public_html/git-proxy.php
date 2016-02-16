<?php
  // usage: https://opensprites.github.co.uk/live/alpha/git-proxy.php?p=main-style.css
  header("Access-Control-Allow-Origin: *");
  $mime_type = $_GET['mime'];
  if($mime_type != '') {
    header('Content-Type: ' . $mime_type);
  } else {
    if(substr($_GET['p'], -4) == '.css') {
      header('Content-Type: text/css');
    } else {
      header('Content-Type: text/javascript');
    }
    
  }
  // default case
  $url = 'https://raw.githubusercontent.com/OpenSprites/OpenSprites/master/public_html/' . $_GET['p'];
  
  echo file_get_contents($url);
?>
