<?php
    require $_SERVER['DOCUMENT_ROOT']."/assets/includes/connect.php";  //Connect - includes session_start();
    if($logged_in_user !== 'not logged in') {
        header('Location: /');
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        include $_SERVER['DOCUMENT_ROOT'].'/Header.html'; // Imports the metadata and information that will go in the <head> of every page
    ?>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <?php
        include $_SERVER['DOCUMENT_ROOT']."/navbar.php"; // Imports navigation bar
    ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:700px;position: relative;">
        <div class="main-inner">
            <div style="position: absolute;left: 0;right: 0;height: 70px;background: white;"></div>
            <iframe scrolling="no" src="https://opensprites.org/forums/?p=user/join&amp;return=https://opensprites.org/&amp;iframe=true" style="width: 100%; height: 620px;overflow: hidden;border: none;"></iframe>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/footer.html'); ?>
</body>
</html>
