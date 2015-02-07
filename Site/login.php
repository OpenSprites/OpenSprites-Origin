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
    <form enctype="multipart/form-data" action="/assets/includes/login.php" method="POST">
      Username: <input name="FormUsername" type="text" /><br>
      Password: <input name="FormPassword" type="password" /><br>
      <input type="Submit"/>
    </form>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
