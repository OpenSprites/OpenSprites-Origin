<?php
	require "assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<!--Imports the metadata and information that will go in the <head> of every page-->
		<?php include('Header.html'); ?>
	</head>
	<body>
		<!--Imports site-wide main styling-->
		<link href='main-style.css' rel='stylesheet' type='text/css'>
	
		<!--Imports navigation bar-->
		<?php include 'navbar.php'; ?>
        
        <div clas="container main">
            <div class="main-inner">
                <div class="box" style="width:80%;margin-left:auto;margin-right:auto">
                    <h1 id="opensprites-heading">Contribution Attribution</h1>
                    <div class="box-content">
                        <h3>Developers:</h3>
					</div>
                </div>
            </div>
        </div>
        
		<!-- footer -->
	<?php echo file_get_contents('footer.html'); ?>
	</body>
</html>
