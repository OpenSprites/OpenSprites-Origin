<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/assets/includes/connect.php";  //Connect - includes session_start();
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
            <h1 id="opensprites-heading">Our server is a little confused...</h1>
            <div id="about">
                <img src='/assets/images/404.png' style='position: absolute; margin: auto; left: 0; right: 0;'>
                <div style='width: 100%; height: 470px;'>&nbsp;</div>
                <p style='position: absolute; margin: auto; top: 480px; left: 0; right: 0; width: 50%; text-align: center; font-size: 18px;'>We couldn't find the page you're looking for.<br>You may want to <a href='/'>go back to the main page</a>.<br/><br/>
				</p>
				<form action="/search.php" method="GET" style="text-align: center; margin-bottom: 2em;">
					Search for: <input type="text" name="q" id="404-search" placeholder="Search" /><button type="submit">Search</button>
				</form>
				<script>
					$("#404-search").val(location.pathname.replace(/[^a-zA-Z0-9]+/g, " ").trim());
				</script>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/footer.html'); ?>
</body>
</html>
