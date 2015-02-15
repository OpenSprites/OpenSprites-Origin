<?php
  // user image
  function grab_user_avatar($username_grabbed) {
    $raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username_grabbed . "/");
    $user_arr = json_decode($raw_json, true);
    $user_avatar = $user_arr["thumbnail_url"];
    return "http:" . $user_avatar;
  }
  
  function update_user_avatar($username) {
    $next_avatar = grab_user_avatar($username);
    $avatar_query = "UPDATE user_data SET avatar='$next_avatar' WHERE username='$username'";
    mysqli_query($connection, $avatar_query);
  }
  
  function display_user_avatar($username, $size, $method) {
    if ($method=='server') {
      $disp_query = "SELECT avatar FROM user_data WHERE username='$username'"
      $disp_result = mysqli_query($connection, $disp_query);
      $src = (mysqli_fetch_assoc($disp_result))['avatar'];
    } else {
      $src = grab_user_avatar($username);
    }
    echo '<img class="user-avatar ' . $size . '" src="' . $src . '">';
  }
?>
