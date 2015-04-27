<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    if($logged_in_user !== 'not logged in') {
        header('Location: /');
    }
	
	// try to prevent the stupid login bug
	if(!isset($_GET['redir'])){
		// destroy session
		unset($_SESSION["userId"]);
		session_unset();
		session_destroy();
		
		// unset session cookie, try to unset dups if possible
		setcookie("OpenSprites_Forum_session", "", time() - 3600);
		setcookie("OpenSprites_Forum_session", "", time() - 3600, "/");
		setcookie("OpenSprites_Forum_session", "", time() - 3600, "/", ".opensprites.gwiddle.co.uk");
		
		// mark that the session has been destroyed
		header("Location: /login/?redir=true");
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include '../Header.html'; ?>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:500px;">
        <div class="main-inner">
            <!--<div style="position: absolute;left: 0;right: 0;height: 70px;" class="themebg"></div>-->
            <iframe scrolling="no" src="http://opensprites.gwiddle.co.uk/forums/?p=user/login&return=http://dev.opensprites.gwiddle.co.uk/&iframe=true" style="width: 100%; height: 360px;overflow: hidden;border: none;margin-top: -50px"></iframe>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/footer.html'); ?>
</body>
</html>
