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
  	<link rel=StyleSheet href="../main.css" type="text/css" media=screen>
  	<style>
  	  input {
      	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
      	margin-bottom: 3px;
      	line-height: 18px;
      	font-size: 15px;
      	height: 32px;
      	padding: 0 0 0 3px;
      	width: 210px;
      	
      	display: inline-block;
      	padding: 4px;
      	margin-bottom: 9px;
      	font-size: 13px;
      	line-height: 18px;
      	color: #555;
      	border: 1px solid #ccc;
      	border-radius: 3px;
      }
      
      input:hover {
      	border: 3px solid #0F8BC0;
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
