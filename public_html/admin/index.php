<?php
require "../assets/includes/connect.php";

if(!$is_admin) {
    // user is not admin, display 404
    include '../404.php';
    die();
}
?>

<head>
    <title>
        OpenSprites Admin
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Palanquin' rel='stylesheet' type='text/css'>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='container'>
        <h1>Moderation Tools</h1>
		<h3>Moderate</h3>
        View all assets and moderate 'em.<br>
        <a id='moderates' href='moderate.php'>View</a>
		
		<h3>Add/remove OS moderators</h3>
        Exactly what the title says<br>
        <a id='moderates' href='edit-mods.php'>Go</a>
		
        <h3>Bulk Delete</h3>
        View all the uploads of a user and click to delete them.<br>
        <input type='number' inputmode='numeric' id='bulkdelete' value='0' min='0' placeholder='userid'>
        <a id='bulkdelete' href='bulkdelete.php?id=0' data='bulkdelete.php?id='>Go</a>
		
		<h3>Purge Thumbnail Cache</h3>
        Deletes all cached thumbnail files, forces a re-render on load. WARNING: Very dangerous! Audio thumbnails could get corrupted if generated at the same time.<br>
        <a id='purgethumbcache' href='../uploads/purge-thumb-cache.php'>Go</a>
		
		<h3>Useful Links</h3>
		<a href="https://opensprites.atlassian.net/wiki/display/OS/Moderation+Situation+Resolution+Guide">Moderation Situation Resolution Guide</a><br>
		<a href="https://opensprites.atlassian.net/wiki/display/OS/Developer+Agreement">Developer Agreement</a><br>
		<a href="http://opensprites.org:9988/">Citadel Mail service</a><br>
		<a href="https://opensprites.atlassian.net/wiki/display/KB/OpenSprites+KB"> OpenSprites Knowledge Base</a><br>
		<a href="https://opensprites.atlassian.net/wiki/display/OS/Useful+URLs">Developer URLs</a>
    </div>
    
    <script>
        $('input').change(function() {
            $('a#' + $(this).attr('id')).attr('href', $('a#' + $(this).attr('id')).attr('data') + $(this).val());
        });
		
		$("#purgethumbcahce").click(function(e){
			if(!confirm("Are you SURE you want to delete cached thumbnails! This will probably break them!")){
				e.preventDefault();
				return false;
			}
		});
    </script>
</body>
