<?php
require __DIR__."/../includes/suspendedconnect.php";
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
          <img src="/assets/images/blocked.gif" />
          <p>Please contact us to appeal your suspension, or if you think this is in error.</p>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.html'; ?>
  </body>
</html>

