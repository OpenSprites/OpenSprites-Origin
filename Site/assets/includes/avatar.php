<?php
  // gets the user's image src
  $raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $_GET['username'] . "/");
  $user_arr = json_decode($raw_json, true);
  $user_avatar = $user_arr["thumbnail_url"];
  // no closing tag for a reason
