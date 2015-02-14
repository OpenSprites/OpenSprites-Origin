<?php

  session_start();
  include '../config.php';
  
  //connect to server and login check via cookie
  include '../assets/includes/login_check.php';  //to be added later!
  
  if (isset($_SESSION['username'])) {
    header("Location: /");
  }
  
?>
<!DOCTYPE html>
<html>
  <head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
  	<?php echo file_get_contents('../Header.html'); ?>
  	<!-- do we really need this?  it should be in header.html -->
  	<LINK REL=StyleSheet HREF="main.css" TYPE="text/css" MEDIA=screen>
  </head>
  <body>
    <?php include '../navbar.php'; ?>
    
    <div class="container main">
      <div class="main-inner">
        <h1 id="opensprites-heading">Register</h1>
        <p>
          <form enctype="multipart/form-data" action="register.php" method="POST">
            Username: <input name="username" type="text" /><br>
            Password: <input name="password" type="password" /><br>
            Confirm password:  <input name="confirm_password" type="password" /><br>
            <input type="Submit"/>
          </form>
        </p>
      </div>
    </div>
    
    <?php echo file_get_contents('../footer.html'); ?>
  </body>
</html>
