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
		OpenSprites.view = {};
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
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<div id="search-bar">
				<input id="search-bar-input" type="text" /><br/>
				<div id="search-buttonsets">
					<div class="search-button-row">
						<div class="search-button-label">
							Sort by:
						</div>
						<div class="sort toggleset">
							<button class='sort-relevance selected' data-key="sort" data-value="relevance">Relevance</button><button class='sort-popularity' data-key="sort" data-value="popularity">Popularity</button><button class='sort-date' data-key="sort" data-value="date">Date</button><button class='sort-alphabetical' data-key="sort" data-value="alphabetical">Alphabetical</button>
						</div>
						<div class="search-button-label">
							Sort direction:
						</div>
						<div class="direction toggleset">
							<button class='dir-desc selected' data-key="dir" data-value="desc">Descending</button><button class='dir-asc' data-key="dir" data-value="asc">Ascending</button>
						</div>
					</div>
					<div class="search-button-row">
						<div class="search-button-label">
							Search in:
						</div>
						<div class="place toggleset">
							<button class='place-both selected' data-key="place" data-value="both">Both</button><button class='place-names' data-key="place" data-value="names">Usernames/Titles</button><button class='place-descriptions' data-key="place" data-value="descriptions">Descriptions/About Sections</button>
						</div>
						<div class="search-button-label">
							Filter by:
						</div>
						<div class="filter toggleset">
							<button class='filter-all selected' data-key="filter" data-value="all">All</button><button class='filter-users' data-key="filter" data-value="users">Users</button><button class='filter-resources'  data-key="filter" data-value="resources">Resources</button><button class='filter-collections' data-key="filter" data-value="collections">Collections</button>
						</div>
					</div>
				</div>
				<div class='advanced-search fold closed'>
					<p class='fold-toggle'>Advanced search options <span class='fold-arrow'></span></p>
					<div class='fold-content'>
						Put swag here
					</div>
				</div>
				<button class='search-button'>Search!</button>
				<div class="pagination toggleset">
					
				</div>
			</div>
		</div>
		<div class='search-results'>
			<h1 class='search-header'>Loading...</h1>
			<p class='search-loading'><span class="symbol loading search-link"></span></p>
			<div class='search-results-content'>
				<p>
					Loading...
				</p>
			</div>
		</div>
	</div>
	<br/><br/>
	
	<script src="search.js"></script>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
