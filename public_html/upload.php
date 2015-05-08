<?php
    require "assets/includes/connect.php";  //Connect - includes session_start();
?>
<?php
  
  if(!isset($_SESSION["username"])){
    // header('Location: ./show_error.php?e=Please%20login%20before%20trying%20to%20upload'); // TODO: redirect to login page
  }
?>
<!-- TODO: prettify -->
<html>
  <head>
    <?php include 'Header.html'; ?>
  </head>
  <body>
   <!--Imports site-wide main styling-->
    <link href='main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
  <div style="padding:35px;">
    <form enctype="multipart/form-data" action="file_upload.php" method="POST">
      Script/Sprite file: <input name="ScriptSpriteFile" type="file" /><br>
      Scratch version (i.e. 1.4/2.0): <input name="ScratchVersion" type="text" /><br>
      Name of Script / Sprite: <input name="ScriptSpriteName" type="text" /><br>
      Is it a Script or a Sprite ("Script"/"Sprite"): <input name="IsScriptSprite" type="text" /><br>
      <input type="Submit"/>
    </form>
  </div>
  <?php echo file_get_contents('Footer.html'); ?>
  </body>
</html>