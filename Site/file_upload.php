<?php
  
  include "uploads/GetHash.php";
  
  // Upload script, puts uploaded files in /uploads/ [scripts|sprites]
  // in file upload form, ScriptSpriteFile file chooser, ScratchVersion text, ScriptSpriteName text, IsScriptSprite text
  
  session_start();
  
  // TODO: make a page that errors are redirected to?
  
  // If you need to test it, comment out the test for if you are logged in
  if(!isset($_SESSION["username"])){
    header('Location: ./show_error.php?e=You%20cannot%20upload%20files%20if%20you%20are%20not%20logged%20in!');
    //echo "You cannot upload files if you are not logged in!";
    exit;
  }
  if(!isset($_FILES["ScriptSpriteFile"]) || !isset($_POST["ScratchVersion"]) || !isset($_POST["ScriptSpriteName"]) || !isset($_POST["IsScriptSprite"])){
    header('Location: ./show_error.php?e=Missing%20information%20about%20script%20/%20sprite');
    //echo "Missing info about Script/Sprite";
    exit;
  }
  if($_POST["IsScriptSprite"] !== "Script" && $_POST["IsScriptSprite"] !== "Sprite"){
    header('Location: ./show_error.php?e=Invalid%20script%20/%20sprite%20choice');
    //echo "Invalid Script/Sprite choice";
    exit;
  }
  // About 10 MB, the scratch site upload limit 
  if($_FILES["ScriptSpriteFile"]["size"] > 10000000){
    header('Location: ./show_error.php?e=File%20too%20large!%20The%20maximum%20limit%20is%2010%20MB');
    //echo "File too large!";
    exit;
  }
  // Name file after hash, to prevent attacks
  $ShaHash = (string) sha1_file($_FILES["ScriptSpriteFile"]["tmp_name"]);
  if(null !== GetNameForHash($ShaHash)){  //why would you do this, php?
    header('Location: ./show_error.php?e=That%20script%20/%20sprite%20has%20already%20been%20uploaded%20before');
    //echo "Script / Sprite has already been uploaded!";
    exit;
  }
  if($_POST["IsScriptSprite"] === "Script"){
    move_uploaded_file( $_FILES["ScriptSpriteFile"]['tmp_name'], "./uploads/scripts/".$ShaHash );
  }else{
    move_uploaded_file( $_FILES["ScriptSpriteFile"]['tmp_name'], "./uploads/sprites/".$ShaHash );
  }
  
  // TODO: add the name, hash, etc to a database
  // Mostly done
  
  // hash -> name
  InsertPairIntoFile($ShaHash, $_POST["ScriptSpriteName"], "Hashes");
  // hash -> user
  InsertPairIntoFile($ShaHash, $_SESSION["username"], "User");
  // hash -> version
  InsertPairIntoFile($ShaHash, $_POST["ScratchVersion"], "Version");
  // hash -> type (script / sprite)
  InsertPairIntoFile($ShaHash, $_POST["IsScriptSprite"], "Type");
  
  header("Location: ./"); // Redirect to main page, can be changed later
?>