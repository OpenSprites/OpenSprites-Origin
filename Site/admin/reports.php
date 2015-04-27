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
        OpenSprites Admin - Reports
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='container'>
        <h1>Admin - Reports</h1>
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
		.row.header .cell {
			font-weight: 900 !important;
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
		$.get("/site-api/reports.php", function(reports){
			var template = $("<div class='row'>" +
					"<div class='cell report'><span class='desc'></span> <a target='_blank'>View</a></div>" +
					"<div class='cell reporter'></div>" +
					"<div class='cell reason'></div>" +
					"<div class='cell time'></div>" +
					"<div class='cell actions'>" +
						"<a class='ignore' href='javascript:void(0);'>Ignore</a> " +
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
						// todo
					});
					row.find(".delete").click(function(){
						// todo
					});
					row.find(".suspend").click(function(){
						// todo
					});
				})(report, row);
				$(".reports-table").append(row);
			}
		});
	</script>
</body>
