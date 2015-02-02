<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('Header.html'); ?>
	<!--Keep this link in so the page renders correctly on PCs without PHP installed.-->
	<LINK REL=StyleSheet HREF="main.css" TYPE="text/css" MEDIA=screen>
</head>
<body>
	<link href='http://fonts.googleapis.com/css?family=Fredoka+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Bubblegum+Sans' rel='stylesheet' type='text/css'>
	<!--Imports navigation bar-->
	<?php echo file_get_contents('navbar.html'); ?>
	        <table id="page-table">
	        <!--
            <tr>
                <td colspan="2" id="header">
					<h2>OpenSprites</h2><br>
                    <p>Scripts and sprites to make something nice</p>
                    <div style="float: right; position: relative; bottom: 10px;">
                        <a href="#">Create a free account</a>
                        <!-- Put an image here for the three bars -->
                        <!-- Not sure if 10px is the optimal size, can be changed -->
                        <img src="assets/images/hamburger.png" width="10" height="10">
                    </div>
                </td>
            </tr>
            -->
            <tr>
                <td id="main-page"><div class="container">
                    <div id="sign-up">
                        <!-- We should hide this if the user is already logged in -->
                        <!-- <img style="float: left;" src="PUT_SOMETHING_HERE"> --><big>Create your account!<br>
                        Sign up for free, and start creating, sharing and downloading open source media to use in your Scratch Projects!<div id="sign-up-btn"><a href="#">Sign Up</a></div>
                    </div>
                    <div id="hot-scripts" class="showcase">
                        <!-- Use PHP or JavaScript or something to fill in this -->
                        <h1>Hot Scripts</h1>
                        <div class="showcase-highlight">
                            <figure class="highlight">
                                <img src="" width="160" height="120">
                                <figcaption>Undefined Script</figcaption>
                            </figure>
                            <dl style="list-style: none;">
                                <li><div class="file-title"><a href="#">Webcam Kinect</a> by <a href="#">dillon836</a></div><div class="file-version">2.0</div></li>
                                <li><div class="file-title"><a href="#">My cool thing</a> by <a href="#">coolspy23</a></div><div class="file-version">2.0</div></li>
                                <li><div class="file-title"><a href="#">Number Rounder</a> by <a href="#">muffins22</a></div><div class="file-version">1.4</div></li>
                            </dl>
                        </div>
                    </div>
                    <br>
                    <div id="hot-sprites" class="showcase">
                        <!-- Also use PHP or JS or something to fill this in -->
                        <h1>Hot Sprites</h1>
                        <div class="showcase-highlight">
                            <figure class="highlight">
                                <img src="" width="160" height="120">
                                <figcaption>Undefined Sprite</figcaption>
                            </figure>
                            <dl style="list-style: none;">
                                <li><div class="file-title"><a href="#">Webcam Kinect</a> by <a href="#">dillon836</a></div><div class="file-version">2.0</div></li>
                                <li><div class="file-title"><a href="#">My cool thing</a> by <a href="#">coolspy23</a></div><div class="file-version">2.0</div></li>
                                <li><div class="file-title"><a href="#">Number Rounder</a> by <a href="#">muffins22</a></div><div class="file-version">1.4</div></li>
                            </dl>
                        </div>
                    </div>
                </div></td>
                <td id="upload"><div class="container">
                    <div id="upload-head"><center>Upload</center></div>
                    <p>I don't know what would go here.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,<br>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse<br>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non<br>proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div></td>
            </tr>
            <tr>
                <td colspan="2" id="footer">
                    <?php echo file_get_contents('Footer.html'); ?>
                </td>
            </tr>
        </table>
</body>
</html>
