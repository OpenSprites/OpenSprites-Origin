<?php

  session_start();
  include '../config.php';
  
  //connect to server and login check via cookie
  
  if (isset($_SESSION['username'])) {
    header("Location: /");
  }
  
?>
<!DOCTYPE html>
<html>
  <head>
  <?php include 'Header.html'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
  <div style="padding:35px;">
    <?php if($USE_MSQLI){ ?>
    <form enctype="multipart/form-data" action="register.php" method="POST">
      Username: <input name="username" type="text" /><br>
      Password: <input name="password" type="password" /><br>
      Confirm password:  <input name="confirm_password" type="password" /><br>
      <input type="Submit"/>
    </form>
    <?php }else{ ?>
    <form enctype="multipart/form-data" action="../auth/handle_register.php" method="POST">
      Username: <input name="FormUsername" type="text" /><br>
      Password: <input name="FormPassword" type="password" /><br>
      <input type="Submit"/>
    </form>
    <?php } ?>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
