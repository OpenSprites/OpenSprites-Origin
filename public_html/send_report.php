<?php
	require "assets/includes/connect.php";
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include 'Header.html';
	?>
</head>
<body>
	<link href='/main-style.css' rel='stylesheet' type='text/css'>
	<?php include "navbar.php"; ?>
	
	<script>
		OpenSprites.view = {rtype:"",rid:""};
	</script>
	<style>
		#report_details {
			margin: 0px;
			width: 99%;
			height: 200px;
			resize: vertical;
			border-radius: 0.3em;
		}
	</style>
	
	<!-- Main wrapper -->
	<div class="container main">
		<div class="main-inner">
			<h1>Send a Report</h1>
			<p>Use the form below to report something that needs moderator attention. Do not report unless you really think it needs attention; unnecessary reports prevent moderators from addressing real issues. You can read the <a href='/guidelines/' target='_blank'>Community Guidlines</a> or <a href='/tos/' target='_blank'>Terms of Service</a> at those links.<br/>Make sure to include a short and descriptive reason for your report, with additional links if required.</p>
			<form method="POST" id="report_form">
				<?php if($logged_in_userid===0){ ?>
				<p>Sorry, but you need to be logged in to send a report. If you don't want to log in, you can still <a href='mailto:support@opensprites.org'>send us an email</a> but it may take moderators longer to respond.</p>
				<?php } else if(!isset($_GET['type']) || !isset($_GET['id'])) { ?>
				<p>Sorry, but we couldn't figure out what you wanted to report!</p>
				<?php } else { 
					$type = $_GET['type'];
					$id = $_GET['id'];
					$id0 = "";
					$id1 = "";
					$not_found = FALSE;
					if($type==='user'){
						$id0 = intval($id);
						if(sizeof(forumQuery("SELECT * FROM `et_member` WHERE `memberId`=?", array($id0))) === 0)
							$not_found = TRUE;
					} else {
						$id0 = intval(substr($id, 0, strpos($id, "/")));
						$id1 = substr($id, strpos($id, "/") + 1);
						if(strlen($id1) > 32) $id1 = substr($id1, 0, 32);
						if(preg_match("/^[a-f0-9]+$/", $id1) !== 1) $not_found = TRUE;
						else if(sizeof(
								imagesQuery("SELECT * FROM `os_assets` WHERE `userid`=? AND `hash`=?",
								array($id0, $id1))) === 0)
							$not_found = TRUE;
						$id = $id0 . "/" . $id1;
					}
					if($not_found){
				?>
				<p>Sorry, but we couldn't find the thing you were trying to report! Maybe it already got deleted.</p>
				<?php } else { ?>
				<script>
					OpenSprites.view = <?php echo json_encode(array("rtype"=>$type,"rid"=>$id)); ?>;
				</script>
				<textarea id="report_details" placeholder="Enter a short and descriptive reason for your report." maxlength="500"></textarea><br/>
				<span class='report_details_count'>500 characters left.</span><br/>
				<button class='btn large send-report'>Send Report</button>
				<?php }} ?>
			</form>
			<p class='report-load' style='display:none;'>Sending report...</p>
			<p class='report-error' style='display:none;color:red;'></p>
			<p class='report-sent' style='display:none;'>Your report was sent! A moderator will look at it as soon as possible.</p>
			<br/><br/><br/><br/>
			<script>
				$("#report_details").keyup(function(){
					var len = $(this).val().length;
					if(len > 500) $(this).val($(this).val().substr(0, 500));
					else {
						var remaining = 500 - len;
						var text = (remaining == 0 ? "No characters" : (remaining == 1 ? "One character" : remaining + " characters")) + " left.";
						$(".report_details_count").text(text);
					}
				});
				$(".send-report").click(function(){
					var reason = $("#report_details").val();
					var type = OpenSprites.view.rtype;
					var id = OpenSprites.view.rid;
					$(".report-error").hide();
					$("form#report_form button, form#report_form textarea").attr("disabled","disabled");
					$(".report-load").show();
					$.post("/site-api/report.php", {type: type, reason: reason, id: id}, function(data){
						if(data.status == "success"){
							$("form#report_form, .report-load").hide();
							$(".report-sent").show();
						} else {
							$("form#report_form button, form#report_form textarea").removeAttr("disabled");
							$(".report-load").hide();
							$(".report-error").text("Error: " + data.message).show();
						}
					}).fail(function(){
						console.log(arguments);
						$("form#report_form button, form#report_form textarea").removeAttr("disabled");
						$(".report-load").hide();
						$(".report-error").text("Whoops, there was an error sending your report. Try again later.").show();
					});
				});
			</script>
		</div>
	</div>
	
	<!-- footer -->
	<?php echo file_get_contents('footer.html'); ?>
</body>
</html>
