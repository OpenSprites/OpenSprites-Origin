<?php

  session_start();
  
?>
<html>
  <head>
  <?php include 'Header.html'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
  <div style="padding:35px;">
    <form enctype="multipart/form-data" action="handle_register.php" method="POST">
      Username you want to register: <input name="FormUsername" type="text" /><br>
      Password of that account: <input name="FormPassword" type="password" /><br>
      <input type="Submit"/>
    </form>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
