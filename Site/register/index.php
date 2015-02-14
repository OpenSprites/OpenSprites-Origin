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
  	<style>
  	  input.register {
      	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
      	margin-bottom: 3px;
      	line-height: 18px;
      	font-size: 15px;
      	height: 24px;
      	padding: 0 0 0 3px;
      	width: 210px;
      	
      	display: inline-block;
      	padding: 4px;
      	margin-bottom: 9px;
      	font-size: 16px;
      	line-height: 18px;
      	color: #555;
      	border: 1px solid #ccc;
      	border-radius: 3px;
      	
      	margin-left: 0;
      }
      
      #submit.register {
        width: 90px;
        height: 30px;
        background-color: #55bee8;
        color: white;
        border-color: white;
        border-radius: 9px;
        
        transition: 100ms all;
        
        cursor: pointer;
      }
      
      #submit.register:hover {
        background-color: #E59D54;
      }
      
      #labels.register {
        width: 200px;
        margin-top: 20px;
      }
  	</style>
  </head>
  <body>
    <?php include '../navbar.php'; ?>
    
    <div class="container main">
      <div class="main-inner">
        <h1 id="opensprites-heading">Register</h1>
        <p>
          <form enctype="multipart/form-data" action="register.php" method="POST">
            <div class="register" id="labels">Your Scratch Username:</div><input name="username" type="text" /><br>
            <div class="register" id="labels">Choose a Password:</div><input name="password" type="password" /><br>
            <div class="register" id="labels">Confirm Password:</div><input name="confirm_password" type="password" /><br>
            <input class="register" type="Submit" id="submit" />
          </form>
        </p>
      </div>
    </div>
    
    <?php echo file_get_contents('../footer.html'); ?>
  </body>
</html>
