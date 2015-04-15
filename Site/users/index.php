<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    require "../assets/includes/avatar.php";
    
    // get username
    error_reporting(0);
    $raw_json = file_get_contents("http://dev.opensprites.gwiddle.co.uk/site-api/user.php?userid=" . $_GET['id']);
    if($raw_json == 'FALSE') {
        $user_exist = false;
    } else {
        $user_exist = true;
        $user = json_decode($raw_json, true);
        $username = $user['username'];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='http://<?php
        echo $_SERVER['SERVER_NAME']; // Imports styling
    ?>/users/user_style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <?php if($user_exist) {?>
    
    <!-- Main wrapper -->
    <?php
        // background-image is a blurred avatar image
        echo "<div id='background-img' style='background-image:url(\"" . $user['avatar'] . "\");'></div>";
    ?>
    <div id='dark-overlay'><div id='overlay-inner'>
        <div id="user-pane-right">
            <?php if($user_exist) { ?>
            <div id='username'>
                <?php
                if($username==$logged_in_user) {echo 'You';} else {echo $username;}
                ?>
            </div>
            <div id='description'>
                <?php
                    echo $user['usertype'];
                ?>
            </div>
            <div id='follow' onclick='window.location = "https://scratch.mit.edu/users/<?php echo $username; ?>";'>
                View Scratch Page
            </div>
            <div id='report'>
                Report
            </div>
            <?php } else { ?>
            <div id='username'>
                User not found!
            </div>
            <?php } ?>
        </div>
        <div id="user-pane-left">
            <?php
                if($user_exist) {
                    display_user_avatar($username, 'x100', 'client');
                }
            ?>
        </div>
    </div></div>

    <?php if($user_exist) { ?>
    <div class="container main" id="collections">
        <div class="main-inner">
            <h1>Recent Uploads</h1>
        </div>
    </div>
    <?php } ?>
    
    <?php } else {?>
    <div class="container main" style='margin-top: 40px;'>
        <div class="main-inner">
            <h1 id="opensprites-heading">Our server is a little confused...</h1>
            <div id="about">
                <img src='/assets/images/404.png' style='position: absolute; margin: auto; left: 0; right: 0;'>
                <div style='width: 100%; height: 470px;'>&nbsp;</div>
                <p style='position: absolute; margin: auto; top: 480px; left: 0; right: 0; width: 50%; text-align: center; font-size: 18px;'>We couldn't find the user you're looking for.<br>You may want to <a href='/'>go back to the main page</a>.</p>
            </div>
        </div>
    </div>
    <?php }?>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
