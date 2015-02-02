<!-- TODO: prettify -->
<html>
  <head>
  <?php echo file_get_contents('Header.html'); ?>
  </head>
  <body>
  <form enctype="multipart/form-data" action="file_upload.php" method="POST">
    Script/Sprite file: <input name="ScriptSpriteFile" type="file" /><br>
    Scratch version (i.e. 1.4/2.0): <input name="ScratchVersion" type="text" /><br>
    Name of Script / Sprite: <input name="ScriptSpriteName" type="text" /><br>
    Is it a Script or a Sprite ("Script"/"Sprite"): <input name="IsScriptSprite" type="text" /><br>
    <input type="Submit"/>
  </form>
  <?php echo file_get_contents('Footer.html'); ?>
  </body>
</html>