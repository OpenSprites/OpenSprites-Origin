<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include 'Header.html'; ?>
</head>
<body>
	<script>
		OpenSprites.view = OpenSprites.view || {};
		OpenSprites.view.browseType = <?php echo json_encode($mtype); ?>;
	</script>

    <!--Imports site-wide main styling-->
    <link href='main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:700px;position: relative;">
        <div class="main-inner">
            <div style="position: absolute;left: 0;right: 0;height: 70px;background: white;"></div>
            <!--Content here? -->
        </div>
    </div>
    
    <!--Footer -->
    <?php include 'footer.html'; ?>
