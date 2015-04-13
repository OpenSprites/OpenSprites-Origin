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
            window.location = '?method=' + type; // timeout made it look bad
			// if you really want it to look nice, you should use ajax
        }
    </script>
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
	<div class="container main" style="text-align:center;">
    <div class="main-inner">
		
    <!-- Select Method -->
    <?php if(!isset($_GET['method'])) { ?>
        <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
        <h1 style="font-size:3em;margin-top:10px;">Choose an upload method</h1>
		<div id='upload-method-select'>
			<img src="/assets/images/upload/fromLocal.png" class="method" onclick="selectMethod('local');">
			<img src="/assets/images/upload/fromScratch.png" class="method" onclick="selectMethod('scratch');">
			<img src="/assets/images/upload/fromUrl.png" class="method" onclick="selectMethod('url');">
		</div>
    <?php } else if($_GET['method'] == 'local') { ?>
    
    <!-- From Local File -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Select a file</h1>
    <?php } else if($_GET['method'] == 'scratch') { ?>
    
    <!-- From Scratch -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Select a Scratch project</h1>
	
	<?php } else if($_GET['method'] == 'url') { ?>
    
	<!-- From URL -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Enter a URL</h1>
	
    <?php
    } else {
        header('location: /upload/');
    }
    ?>
	
	<?php if(isset($_GET['method'])){ ?>
	
		<p><a href="/upload/">Back</a></p>
	
	<?php } ?>
	
	</div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
