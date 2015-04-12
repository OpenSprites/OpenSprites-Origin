<?php
    require $_SERVER['DOCUMENT_ROOT']."/assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Header.html'; ?>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include $_SERVER['DOCUMENT_ROOT']."/navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
            <iframe src='http://opensprites.gwiddle.co.uk/forums/?p=user/login&return=http://dev.opensprites.gwiddle.co.uk/'></iframe>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/footer.html'); ?>
</body>
</html>
