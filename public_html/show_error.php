<?php
require __DIR__."/../includes/connect.php";  //Connect - includes session_start();
?>
<?php
  
  if(!isset($_GET["e"])){
    header("Location: ./");
  }
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
                    <h1 id="opensprites-heading">Uh oh! Error alert!</h1>
                    <div class="box-content" align="center">
                      <p><?php echo htmlspecialchars($_GET["e"]) ?></p>
                      <p>Why not go to the <a href="/">home page</a> or go <a style="cursor:pointer" onclick="window.history.back()">back</a>?</p>
                  </div>
                    </div>
          </div>
      </div>
  <?php include 'Footer.html'; ?>
  </body>
</html>

