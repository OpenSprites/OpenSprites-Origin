<?php
    require "../assets/includes/connect.php"; //Connect - includes session_start(); require "../assets/includes/avatar.php";
    
    /*if($logged_in_user == 'not logged in' or $user_banned) {
        header('Location: /');
        die;
    }*/
	
?>
<!DOCTYPE html>
<html>

<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php echo file_get_contents( '../Header.html'); ?>

    <!--Imports styling-->
    <link href='_matu_upload.css' rel='stylesheet' type='text/css'>
</head>

<body>
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>

    <div class="container main" style="text-align:center;">
        <div class="main-inner">
			
			<div id="upload-area">
				<div id="upload-button">
					<input type='file' name='uploadfiles[]' id='uploadbtn' multiple title="Select files to upload" />
					Select files
				</div>
				<p class='upload-message'>Drop files here</p>
			</div>

        </div>
    </div>

    <!-- footer -->
    <?php echo file_get_contents( '../footer.html'); ?>
	<script type="text/javascript" src="/assets/js/md5.js" />
	<script type="text/javascript" src="_matu_upload.js" />
</body>

</html>
