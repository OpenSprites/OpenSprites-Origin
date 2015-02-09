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
    <?php if($USE_MYSQLI){ ?>
    
    <form enctype="multipart/form-data" action= "assets/includes/login.php" method="POST">
      Username: <input name="username" type="text" /><br>
      Password: <input name="password" type="password" /><br>
      <input type="Submit"/>
    </form>
    
    <?php }else{ ?>
    
    <form enctype="multipart/form-data" action= "auth/validate_login.php" method="POST">
      Username: <input name="FormUsername" type="text" /><br>
      Password: <input name="FormPassword" type="password" /><br>
      <input type="Submit"/>
    </form>
    
    <?php } ?>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
