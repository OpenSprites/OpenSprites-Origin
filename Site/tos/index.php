<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include '../Header.html'; ?>
    <style>
    h5 {
        font-size: 1.1em;
        margin-top: -10px;
        font-weight: normal;
        margin-bottom: 0;
    }
    
    h3 {
        font-size: 1.5em;
        /* margin-top: -10px; */
        font-weight: normal;
        margin-bottom: 8px;
    }
    </style>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='../main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main">
        <div class="main-inner">
            <h1 id="opensprites-heading">Terms Of Service</h1>
            <p>Someone insert something here. The basic ideas we want are.
				<ul>
					<li>No hacking</li>
					<li>No DDOS or anything</li>
					<li>We can revoke your access to OS at any time for any reason</li>
					<li>Etc.</li>
				</ul>
			</p>     
            <p style='font-size: 0.8em;padding-bottom:20px'>TOS revision one. Originally written by MegaApuTurkUltra on June 13, 2015. Most recent edits by MegaApuTurkUltra on June 13, 2015 This policy is subject to change without prior notice.</p>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
