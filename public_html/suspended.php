<?php
    require "assets/includes/suspendedconnect.php";  //Connect - includes session_start();
?>
<html>
  <head>
  <?php include 'Header.html'; ?>
  </head>
  <body>
  <?php include 'navbar.php'; ?>
      <div clas="container main">
                  <div class="main-inner">
                <div class="box" style="width:50%;margin-left:auto;margin-right:auto">
                    <h1 id="opensprites-heading">Account Suspended</h1>
                    <div class="box-content" align="center">
                        <img src="img/fly-you-fools.gif" />
                      <p>Please contact us to appeal your suspension, or if you think this is in error.</p>
                  </div>
                    </div>
          </div>
      </div>
  <?php include 'footer.html'; ?>
  </body>
</html>

