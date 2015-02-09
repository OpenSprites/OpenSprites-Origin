<?php
  session_start();
  include 'auth/AuthFunctions.php';
  include 'config.php';
  
  if($ALLOW_ALTERNATE_REGISTERS !== true){
    header('Location: ./show_error.php?e=You%20are%20not%20allowed%20to%20do%20that');
    exit;
  }

  if(!isset($_POST["FormUsername"]) || !isset($_POST["FormPassword"])){
    header('Location: ./show_error.php?e=Please%20enter%20username%20and%20password');
    exit;
  }
  $post_username = $_POST["FormUsername"];
  $password_hash = GetHashForName($post_username);
  if(isset($password_hash)){
    header('Location: ./show_error.php?e=Invalid%20Username%20already%20taken');
    exit;
  }
  
  if(strlen($_POST["FormUsername"]) === 20){
    header('Location: ./show_error.php?e=Username%20too%20long');
    exit;
  }

  if(preg_match( '/^\w+$/' , $_POST["FormUsername"])){
    $hash = password_hash($_SESSION["FormPassword"], PASSWORD_BCRYPT);
    AddHashNamePair($_POST["FormUsername"], $hash);
    header('Location: ./login.php');
    exit;
  }else{
    header('Location: ./show_error.php?e=Invalid%20characters%20in%20password');
    exit;
  }
?>