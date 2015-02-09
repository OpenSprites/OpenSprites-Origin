<?php

  session_start();
  if (isset($_SESSION['username'])) {
    header("Location: /");
  }
  
?>
<html>
  <head>
  <?php include 'Header.html'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
  <div style="padding:35px;">
    <form enctype="multipart/form-data" action="register.php" method="POST">
      Username: <input name="username" type="text" /><br>
      Password: <input name="password" type="password" /><br>
      Confirm password:  <input name="confirm_assword" type="password" /><br>
      <input type="Submit"/>
    </form>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
