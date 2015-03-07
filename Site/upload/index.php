<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../Header.html'); ?>
	
	<!--Imports styling-->
	<link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <script>
        function selectMethod(type) {
            $('.main').fadeOut(250);
            setTimeout(function() {
                window.location = '?method=' + type;
            }, 250);
        }
        
        window.onload = function() {
            $('.main').fadeIn(250);
        };
    </script>
	<!--Imports navigation bar-->
	<?php include "../navbar.php"; ?>
	
	<!-- Select Method -->
	<?php if(!isset($_GET['method'])) { ?>
    <div class="container main" style="text-align:center;display:none;">
		<div class="main-inner" style="padding-bottom: 50px;">
		 <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
		<h1 style="font-size:3em;margin-top:10px;">Choose an upload method</h1>
		<img src="fromLocal.png" class="method" onclick="selectMethod('local');"><img src="fromScratch.png" class="method" onclick="selectMethod('scratch');">
		</div>
		
	</div>
	<?php } else if($_GET['method'] == 'local') { ?>
	
	<!-- From Local File -->
    <div class="container main" style="text-align:center;display:none;">
		<div class="main-inner" style="padding-bottom: 50px;">
			<h1 style="font-size:4em;margin-top:50px;">Upload</h1>
			<h1 style="font-size:3em;margin-top:10px;">Select a file</h1>
		</div>
	</div>
	<?php } else if($_GET['method'] == 'scratch') { ?>
	
	<!-- From Scratch -->
	<div class="container main" style="text-align:center;display:none;">
		<div class="main-inner" style="padding-bottom: 50px;">
			<h1 style="font-size:4em;margin-top:50px;">Upload</h1>
			<h1 style="font-size:3em;margin-top:10px;">Select a Scratch project</h1>
		</div>
	</div>
	
	<?php
    } else {
        header('location: /upload/');
    }
    ?>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
