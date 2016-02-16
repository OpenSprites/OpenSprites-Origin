<?php

  require '../assets/includes/connect.php';
  
  //connect to server and login check via cookie
  //require '../assets/includes/login_check.php';  //to be added later!
  
  if (isset($_SESSION['username'])) {
    header("Location: /");
  }
  
  if(isset($_GET['return'])) {
  	$return = $_GET['return'];
  }
  
  $_SESSION['init_time'] = time();
  
?>
<!DOCTYPE html>
<html>
  <head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
  	<?php include '../Header.html'; ?>
  	<link rel=StyleSheet href="register.css" TYPE="text/css" media=screen>
  </head>
  <body>
    <?php include '../navbar.php'; ?>
    <div class="container main">
      <div class="main-inner">
        <h1 id="opensprites-heading">Register</h1>
        <?php
        if(isset($return)) {
        	echo '<h2><b>' . $return . '</h2></b>';
        }
        ?>
        <p>
          <form method='POST' action='/register/register.php'>
            <div class="register" id="labels">Your Scratch Username:</div><input class="register" name="username" type="text" /><br>
			<div class="register" id="labels">Email:</div><input class="register" name="email" type="email" /><br>
            <div class="register" id="labels">Choose a Password:<br>(Please use a different password than your Scratch one)</div><input class="register" name="password" type="password" /><br>
            <div class="register" id="labels">Confirm Password:</div><input class="register" name="password_check" type="password" /><br>
			Please paste this code into <a href='https://scratch.mit.edu/projects/47606468/'>this</a> project:<br />
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
