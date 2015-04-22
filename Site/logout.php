<?php
  require 'assets/includes/connect.php';
  
  $_SESSION = array();
  session_destroy();
  if(isset($_GET['return'])){
    header('Location: ' . $_GET['return']);
  } else {
    header('Location: /');
  }
?>
