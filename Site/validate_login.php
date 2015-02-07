<?php
  session_start();
  include 'auth/AuthFunctions.php';
  
  if(!isset($_POST["FormUsername"]) || !isset($_POST["FormPassword"])){
    header('Location ./show_error.php?e=Invalid%20Username/Password');
    exit;
  }
  $password_hash = GetHashForName($_POST["FormUsername"]);
  if(!isset($password_hash)){
    header('Location ./show_error.php?e=Invalid%20Username/Password');
    exit;
  }
  if(password_verify($_POST["FormPassword"], $password_hash)){
    $_SESSION["username"] = $_POST["FormPassword"];
    header('Location ./');
  }else{
    header('Location ./show_error.php?e=Invalid%20Username/Password');
    exit;
  }
?>