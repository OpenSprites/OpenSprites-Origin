<?php

  session_start();
  
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
  	<?php include '../Header.php'; ?>
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
            <div class="register" id="labels">Choose a Password (Please use a different password than your Scratch one):</div><input class="register" name="password" type="password" /><br>
            <div class="register" id="labels">Confirm Password:</div><input class="register" name="confirm_password" type="password" /><br>
			Please paste this code into <a href='http://scratch.mit.edu/projects/47606468/'>this</a> project:<br />
			<?php
				//get a random code...
				$id = uniqid();
				$_SESSION['user_code'] = $id;
				echo $id;
			?>
            <input class="register" type="Submit" id="submit" />
          </form>
        </p>
		<div id='left-reg-panel'>
			Already registered and submitted the code?  Enter your username and click this button to confirm your account.
			<form method='POST' action='register_check.php'>
				<input type='text' name='username_confirmation' />
				<input type='submit' value='Confirm' />
			</form>
		</div>
      </div>
    </div>
    
    <?php echo file_get_contents('../footer.html'); ?>
  </body>
</html>
