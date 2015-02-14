<?php
  // user image
  function grab_user_avatar($username_grabbed) {
    $raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
    $user_arr = json_decode($raw_json, true);
    $user_avatar = $user_arr["thumbnail_url"];
    return "http:$user_avatar";
  }
  
  function update_user_avatar($username) {
    
  }
  
  function display_user_avatar($username, $size) {
    $src = grab_user_avatar($username);
    echo '<img class="user-avatar ' . $size . '" src="' . $src . '">'
  }
?>
