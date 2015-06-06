<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    if($logged_in_user !== 'not logged in') {
        header('Location: /');
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
    <div class="container main" style="height:440px;">
        <div class="main-inner">
            <!-- <div style="position: absolute;left: 0;right: 0;height: 70px;" class="themebg"></div> -->
            <iframe scrolling="no" src="http://opensprites.org/forums/?p=user/login&return=<?php echo urlencode(isset($_GET['return']) ? $_GET['return'] : '/'); ?>&iframe=true" style="width: 100%; height: 300px;overflow: hidden;border: none;margin-top: -50px"></iframe>
        </div>
    </div>
    
    <!-- footer -->
    <?php include "../footer.html"; ?>
</body>
</html>
