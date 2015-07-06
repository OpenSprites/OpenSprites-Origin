<?php
	require "../../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include '../../Header.html'; // Imports the metadata and information that will go in the <head> of every page
	?>
	<style>
		code {
			background: black;
			padding: 0.2em;
			border-radius: 5px;
		}
	</style>
</head>
<body>
	<!--Imports site-wide main styling-->
	<link href='/main-style.css' rel='stylesheet' type='text/css'>
	
	<?php
		include "../../navbar.php"; // Imports navigation bar
	?>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h2>Notice</h2>
			<p>Search is incomplete as of right now. It cannot search for users or collections.</p>
			<h2>Basic searches</h2>
			<p>To do a basic search, enter the thing you want to search for, including only letters and numbers, then press the search button. If your search gives more than 20 results, the results will be categorized in pages. Press a button for a page number to switch pages.</p>
			<h2>Basic search options</h2>
			<p>The four basic search options are
				<ul>
					<li><b>Sort by:</b>
						<ul>
							<li>Relevance: This sorts the results and puts the ones that match your search the most on top</li>
							<li>Popularity: This sorts results by number of downloads (for assets and collections).</li>
							<li>Date: This sorts results by the date they were created</li>
							<li>Alphabetical: This sorts results alphabetically</li>
						</ul>
					</li>
					<li><b>Sort direction:</b>
						<ul>
							<li>Descending: The default direction for sort, ie most relevant, most popular, earliest date, A-Z</li>
							<li>Ascending: Inverted sort direction, ie least relevant, least popular, latest date, Z-A</li>
						</ul>
					</li>
					<li><b>Search in:</b>
						<ul>
							<li>Both: Searches in all areas of profiles, asset pages, and collection pages</li>
							<li>Usernames/Titles: Searches only the usernames of users, and titles of assets and collections only</li>
							<li>Descriptions/About Sections: Searches in descriptions of assets and collections, and in about sections of users only</li>
						</ul>
					</li>
					<li><b>Filter by:</b>
						<ul>
							<li>All: Displays all results</li>
							<li>Users: Displays only results that are users</li>
							<li>Resources: Displays only results that are scripts, images, or sounds</li>
							<li>Collections: Displays only results that are collections</li>
						</ul>
					</li>
				</ul>
			</p>
			<h2>Advanced searching</h2>
			<p>Expand the advanced search section to do an advanced search. You can also enter the symbols for advanced searches directly in the search bar. Multiple advanced search keywords can be used in the same search query.</p>
			<p><sub><sup>Note: Some advanced users may note that the following system is exactly how <code>MySQL boolean searches</code work.</sup></sub></p>
			<p>The advanced search options are:
				<ul>
					<li><b>Some of these words</b> | <code>(no symbol)</code><br/>Searches for results that contain one or more of the words, and the "Relevance" sort will sort results by the number of matched words. To use this in the search bar, enter space-separated words as usual. This is the default search mode.</li>
					<li><b>All of these words</b> | <code>+word</code><br/>Searches only for results that contain all of the given words. If a result does not contain one or more of the words, it is excluded. For example, <code>+world</code> would match "hello world" but not "hello opensprites"</li>
					<li><b>None of these words</b> | <code>-word</code><br/>Searches only for results that do not contain any of the given words. If a result contains one, it is excluded. For example, <code>-hello</code> would match "opensprites is cool" but not "hello opensprites"</li>
					<li><b>Words starting with</b> | <code>word*</code><br/>Searches for words starting with the given text. For example, <code>open*</code> would match both "open" and "opensprites"</li>
					<li><b>This phrase</b> | <code>"some phrase"</code><br/>Searches for results that contain the given phrase as it is written. For example, <code>"apple banana"</code> would match "apple banana cantaloupe" but not "banana apple"</li>
				</ul>
				You can also combine these symbols. For example, <code>+apple -banana cantaloupe</code> matches "apple cantaloupe", and "apple", but not "apple banana" or "cantaloupe"
			</p>
			<h2>Need more help?</h2>
			<p>You can post for help on the <a href="http://opensprites.org/forums/">forums</a> for a response from OpenSprites users, or send a ticket to our <a href="https://opensprites.atlassian.net/servicedesk/customer/portal/2" target="_blank">help desk</a> for a response from OpenSprites moderators.</p>
		</div>
		<br/><br/>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('../../footer.html'); ?>
</body>
</html>
