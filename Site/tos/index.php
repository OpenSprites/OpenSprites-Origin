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
		</div>
		<script src="/assets/lib/markdown-js/markdown.min.js"></script>
		<script>
			var tos = <?php echo json_encode(file_get_contents("https://raw.githubusercontent.com/OpenSprites/OpenSprites/master/ToS.md")); ?>;
			var content = $(".main-inner");
			content.html(markdown.toHTML(tos));
		</script>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
