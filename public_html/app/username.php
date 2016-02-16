<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logged in user</title>
    <style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:300);
  
    span {
    margin:0px;
    display:inline-block;
    width:130px;
    white-space: nowrap;
    overflow:hidden !important;
    text-overflow: ellipsis;
    }
    </style>
</head>
<body style="background-color:#659593;font-family:Open Sans;color:white;margin:0">
    <span><?php echo $logged_in_user; ?></span>
</body>
</html>
