<?php
  session_start();
  include 'AuthFunctions.php';
  include '../config.php';
  
  if(!isset($_SESSION["username"])){
    header('Location: ../');
  }
  if(!isset($_POST["OldPassword"]) || !isset($_POST["NewPassword"])){
    header('Location: ../show_error.php?e=Please%20enter%20your%20old%20password%20and%20new%20password');
    exit;
  }
  $username = $_SESSION["username"];
  $password_hash = GetHashForName($username);
  if(!isset($password_hash)){
    header('Location: ../show_error.php?e=No%20hash%20found');
    exit;
  }

  if(password_verify($_POST["OldPassword"], $password_hash)){
    ReplaceNameHash($_SESSION["username"], password_hash($_POST["NewPassword"], PASSWORD_BCRYPT));
    header('Location: ../');
    exit;
  }else{
    header('Location: ../show_error.php?e=Invalid%20Password');
    exit;
  }
?>