<?php
  session_start();
  
  if(!isset($_GET["e"])){
    header("Location: ./");
  }
?>
<html>
  <head>
  <?php include 'Header.php'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
  <div style="padding:35px;">
    <?php echo htmlspecialchars($_GET["e"]) ?>
  </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>
