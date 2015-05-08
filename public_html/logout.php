<?php
  require 'assets/includes/connect.php';
  
  unset($_SESSION["userId"]);
  session_unset();
  session_destroy();
  if(isset($_GET['return'])){
    header('Location: ' . $_GET['return']);
  } else {
    header('Location: /');
  }
?>
