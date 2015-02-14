<?php
  // gets the user's image and echos it
  $raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $_GET['username'] . "/");
  $user_arr = json_decode($raw_json);
  $user_avatar = $user_arr["thumbnail-url"];
  echo 'Test';
  echo $user_avatar;  //removed closing tag for a reason - this will be included into the register.php page.
