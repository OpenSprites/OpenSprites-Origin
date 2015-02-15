<?php

  session_start();
  include 'config.php';
  
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
  	<link rel=StyleSheet href="register.css" TYPE="text/css" media=screen>
  </head>
  <body>
    <?php include '../navbar.php'; ?>
    
    <div class="container main">
      <div class="main-inner">
        <h1 id="opensprites-heading">Register</h1>
        <p>
          <form enctype="multipart/form-data" action="register.php" method="POST">
            <div class="register" id="labels">Your Scratch Username:</div><input class="register" name="username" type="text" /><br>
			<div class="register" id="labels">Email:</div><input class="register" name="email" type="text" /><br>
            <div class="register" id="labels">Choose a Password:</div><input class="register" name="password" type="password" /><br>
            <div class="register" id="labels">Confirm Password:</div><input class="register" name="confirm_password" type="password" /><br>
            <input class="register" type="Submit" id="submit" />
          </form>
        </p>
      </div>
    </div>
    
    <?php echo file_get_contents('../footer.html'); ?>
  </body>
</html>