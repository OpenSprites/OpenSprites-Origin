<?php
    require "assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <?php /* Imports the metadata and information that will go in the <head> of every page */
        include 'Header.html';
    ?>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='main-style.css' rel='stylesheet' type='text/css'>
    
    <?php /* imports the navigation bar */
        require "navbar.php";
    ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
            <h1 id="opensprites-heading">Our server is a little confused...</h1>
            <div id="about">
                <img src='<?php readfile("404image.txt"); ?>' style='position: absolute; margin: auto; left: 0; right: 0;'>
                <div style='width: 100%; height: 400px;'>&nbsp;</div>
                <p style='position: absolute; margin: auto; top: 480px; left: 0; right: 0; width: 50%; text-align: center; font-size: 18px;'>We couldn't find the page you're looking for.<br>You may want to <a href='/'>go back to the main page</a>.</p>
            </div>
        </div>
    </div>
    
    <?php /* footer */
        require 'footer.html';
    ?>
</body>
</html>
