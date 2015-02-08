<?php
  session_start();
  include 'auth/AuthFunctions.php';

  if(!isset($_POST["FormUsername"]) || !isset($_POST["FormPassword"])){
    header('Location: ./show_error.php?e=Please%20enter%20your%20username%20and%20password');
    exit;
  }
  $post_username = $_POST["FormUsername"];
  $password_hash = GetHashForName($post_username);
  if(!isset($password_hash)){
    header('Location: ./show_error.php?e=No%20hash%20found');
    exit;
  }
  //echo $_POST["FormPassword"];
  //echo $password_hash;
  if(password_verify($_POST["FormPassword"], $password_hash)){
    $_SESSION["username"] = $_POST["FormPassword"];
    header('Location: ./');
  }else{
    header('Location: ./show_error.php?e=Invalid%20Password');
    exit;
  }
?>