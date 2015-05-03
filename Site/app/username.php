<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Logged in user</title>
  <style>
  @import url(http://fonts.googleapis.com/css?family=Open+Sans:300);
  </style>
</head>
<body style="background-color:#659593;font-family:Open Sans;color:white">
  <?php echo $logged_in_user; ?>
</body>
</html>
