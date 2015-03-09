<?php
  session_name("OpenSprites_Forum_session");
  session_start();
  
  include 'http://dev.opensprites.x10.mx/assets/includes/html_dom_parser.php';
  $logged_in_userid = $_SESSION["userId"];
  $html = file_get_html('http://opensprites.x10.mx/forums/?p=member/' . $id);
  $logged_in_user =   $html->find('h1#memberName', 0)->innertext;
