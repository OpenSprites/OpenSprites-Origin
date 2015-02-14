<?php
  // gets the user's image src
  function display_user_avatar($username_grabbed, $image_size) {
    $raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
    $user_arr = json_decode($raw_json, true);
    $user_avatar = $user_arr["thumbnail_url"];
    echo "<img class='profile-image $image_size' src='http:" . $user_avatar . "'";
  }
?>
