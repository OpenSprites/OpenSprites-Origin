<?php

  session_start();
  include 'config.php';
?>
<html>
  <head>
  <?php include 'Header.html'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
  <div style="padding:35px;">
    <!-- Add more account info here? -->
    
    Change password: <br>
    <form enctype="multipart/form-data" action="auth/change_password.php" method="POST">
      Old Password: <input name="username" type="OldPassword" /><br>
      New Password: <input name="password" type="NewPassword" /><br>
      <input type="Submit"/>
    </form>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
