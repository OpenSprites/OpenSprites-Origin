<?php
require "../assets/includes/connect.php";
if(!$is_admin) {
    // user is not admin, display 404
    include '../404.php';
    die();
}
connectDatabase();
if(isset($_GET['action']) && isset($_GET['id']) && isset($_GET['reporter'])){
	if($_GET['action'] == "dismiss"){
		imagesQuery0("DELETE FROM `" . getReportsTableName() . "` WHERE `id`=? AND `reporter`=?", array($_GET['id'], $_GET['reporter']));
	} else if($_GET['action'] == "delete"){
		$userid = substr($_GET['id'], 0, strpos($_GET['id'], '/'));
		$hash = substr($_GET['id'], strpos($_GET['id'], '/') + 1);
		$res = imagesQuery("SELECT * FROM `" . getAssetsTableName() . "` WHERE `hash`=?", array($hash));
		if(sizeof($res) == 0) die("Error");
		$filename = $res[0]['name'];
		unlink("../uploads/uploaded/" . $filename);
		imagesQuery0("DELETE FROM `" . getAssetsTableName() . "` WHERE `hash`=?", array($hash));
		
		// todo: notify users that their files have been removed
	} if($_GET['action'] == "suspend"){
		$userInfo = getUserInfo(intval($_GET['id']));
		setAccountType($userInfo['username'], "suspended");
	}
	die("Success");
}
?>

<head>
    <title>
        OpenSprites Admin - Moderate
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='container'>
        <h1>Admin - Moderate</h1>
        <div class='reports-table'>
			<div class='row header'>
				<div class='cell'>Target</div>
				<div class='cell'>Reporter</div>
				<div class='cell'>Reason</div>
				<div class='cell'>Date/Time</div>
				<div class='cell'>Actions</div>
			</div>
		</div>
    </div>
	<style>
		* {
			font-weight: normal !important;
		}
		.cell.reason {
			width: 100%;
		}
		.cell.time, .cell.actions, .cell.report {
			white-space: nowrap;
		}
		.reports-table {
			position: absolute;
			left: 1em;
			width: calc(100% - 2em);
			display: table;
			border: 1px solid white;
			border-collapse: collapse;
		}
		.row {
			display: table-row;
			border: 1px solid white;
		}
		
		.cell {
			display: table-cell;
			border: 1px solid white;
			padding: 0.5em;
		}
		
		.ignore, .delete, .suspend {
			font-style: none;
			font-size: 0.9em;
			border: 1px solid white;
			border-radius: 0.5em;
			padding: 0.3em;
			transition: all 0.2s;
			background: transparent;
		}
		
		.ignore:hover, .delete:hover, .suspend:hover {
			background: rgba(255, 255, 255, 0.3);
		}
		
		h1 {
			text-align: center;
		}
	</style>
	<script>
		$.get("/site-api/moderate.php", function(reports) {
			var template = $("<div class='row'>" +
					"<div class='cell report'><span class='desc'></span> <a target='_blank'>View</a></div>" +
					"<div class='cell actions'>" +
						"<a class='ignore' href='javascript:void(0);'>Dismiss</a> " +
						"<a class='delete' href='javascript:void(0);'>Delete asset</a> " +
						"<a class='suspend' href='javascript:void(0);'>Suspend user</a></div>" +
				"</div>");
			for(var i=0;i<reports.length;i++){
				var row = template.clone();
				var report = reports[i];
				row.find(".report .desc").text((report['type'] == 1 ? "Asset: " : "User: ") + report['id']);
				row.find(".report a").attr("href", "/users/" + report['id']);
				row.find(".reporter").text(report['reporter']);
				row.find(".reason").text(report['reason']);
				row.find(".time").text(report['reportTime']);
				(function(report, row){
					row.find(".ignore").click(function(){
						$.get("reports.php", {"action":"dismiss","id":report['id'],"reporter":report['reporter']}, function(data){
							if(data == "Success") row.fadeOut(700, function(){
								row.remove();
							});
							console.log(data);
						});
					});
					row.find(".delete").click(function(){
						$.get("reports.php", {"action":"delete","id":report['id'],"reporter":report['reporter']}, function(data){
							if(data == "Success") row.find(".delete").text("Deleted");
							else row.find(".delete").text("Error");
							console.log(data);
						});
					});
					
					if(report['type'] == 0) row.find(".delete").hide();
					
					row.find(".suspend").click(function(){
						$.get("reports.php", {"action":"suspend","id":report['id'],"reporter":report['reporter']}, function(data){
							if(data == "Success") row.find(".delete").text("Suspended");
							else row.find(".suspend").text("Error");
							console.log(data);
						});
					});
				})(report, row);
				$(".reports-table").append(row);
			}
		});
	</script>
</body>
