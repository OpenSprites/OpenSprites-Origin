<?php
    require "../../assets/includes/connect.php"; //Connect - includes session_start(); require "../assets/includes/avatar.php";
    
    if($logged_in_user == 'not logged in' or $user_banned) {
        header('Location: /');
        die;
    }
	
	$errors = array(
		"toobig" => "That file is too big for our servers to handle! Try a file smaller than 8MB."
	);
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo file_get_contents( '../Header.html'); ?>

    <!-- Dropzone -->
    <link href='dropzone.css' rel='stylesheet' type='text/css'>
    <script src="dropzone.js"></script>
</head>

<body>
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>

    <div class="container main" style="text-align:center;">
        <div class="main-inner">
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
        </div>
    </div>

    <!-- footer -->
    <?php echo file_get_contents( '../../footer.html'); ?>
</body>

</html>
