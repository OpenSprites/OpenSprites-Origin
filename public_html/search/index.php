<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include '../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
	<script>
		OpenSprites.view.query = <?php
			if(isset($_GET['q'])) echo json_encode($_GET['q']);
			else if(isset($_GET['query'])) echo json_encode($_GET['query']);
			else echo json_encode("");
		?>;
	</script>
	<link href='/main-style.css' rel='stylesheet' type='text/css'>
	<link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	
	<?php
		include "../navbar.php"; // Imports navigation bar
	?>
	<script>
		$("#search-input").val(OpenSprites.view.query);
	</script>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<div id="search-bar">
				
			</div>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
