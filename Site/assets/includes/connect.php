<?php
  session_name("OpenSprites_Forum_session");
  session_start();
  
  require "/assets/includes/html_dom_parser.php";
  if(isset($_SESSION["userId"])) {
    $logged_in_userid = $_SESSION["userId"];
    $html = file_get_html('http://opensprites.x10.mx/forums/?p=member/' . $logged_in_userid);
    $logged_in_user = $html->find('h1#memberName', 0)->innertext;
  } else {
    $logged_in_userid = 'not logged in';
    $logged_in_user = 'not logged in';
  }
