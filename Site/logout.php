<?php
  session_name("OpenSprites_Forum_session");
  session_set_cookie_params(0, '/', '.opensprites.gwiddle.co.uk');
  session_start();
  
  $_SESSION["userId"] = -1
?>
