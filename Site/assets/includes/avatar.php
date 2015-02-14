<?php
  // gets the user's image src and echos it
  $encoded = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $_GET['username'] . "/");
  
  preg_match('/thumbnail_url/', $encoded, $matches, PREG_OFFSET_CAPTURE, 3);
  $i = $matches[0];
  $start = $i[1];
  
  $short = substr($encoded, $start + 17);
  
  preg_match('/", /', $short, $matches, PREG_OFFSET_CAPTURE, 3);
  $i = $matches[0];
  $end = $i[1];
  
  echo 'http:' . substr($short, 0, $end);
?>
