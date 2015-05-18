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
				<input id="search-bar-input" type="text" /><br/>
				<div id="search-buttonsets">
					<div class="search-button-row">
						<div class="sort toggleset">Sort by: 
							<button class='sort-relevance'>Relevance</button><button class='sort-popularity'>Popularity</button><button class='sort-date'>Date</button><button class='sort-alphabetical'>Alphabetical</button>
						</div>
						<div class="direction toggleset">Sort direction: <button class='dir-desc'>Descending</button><button class='dir-asc'>Ascending</button>
						</div>
					</div>
					<div class="search-button-row">
						<div class="place toggleset">Search in: 
							<button class='place-both'>Both</button><button class='place-names'>Usernames/Titles</button><button class='place-descriptions'>Descriptions/About Sections</button>
						</div>
						<div class="filter toggleset">Filter by: 
							<button class='filter-all'>All</button><button class='filter-users'>Users</button><button class='filter-resources'>Resources</button><button class=	'filter-collections'>Collections</button>
						</div>
					</div>
				</div>
				<div class='advanced-search fold closed'>
					<p class='fold-toggle'>Advanced search options <span class='fold-arrow'></span></p>
					<div class='fold-content'>
						Put swag here
					</div>
				</div>
			</div>
		</div>
		<div class='search-results'>
			<h1 class='search-header'>Loading...</h1>
			<div class='search-results-content'>
				
			</div>
		</div>
	</div>
	
	<script src="search.js"></script>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
